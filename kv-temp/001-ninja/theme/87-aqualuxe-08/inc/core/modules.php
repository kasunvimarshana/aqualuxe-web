<?php
namespace AquaLuxe\Core;

class Modules
{
    public static function bootstrap(): void
    {
        // 1) Build registry from modules folder (auto-discovery + optional module.json)
        $registry = self::registry();

        // 2) Defaults from registry (enabled flag), then merge with DB option and filter for overrides
        $defaults = [];
        foreach ($registry as $slug => $meta) {
            $defaults[$slug] = isset($meta['enabled']) ? (bool) $meta['enabled'] : true;
        }

    $db = \function_exists('get_option') ? (array) \call_user_func('get_option', 'aqualuxe_modules', []) : [];
        $config = array_merge($defaults, $db);

        // Back-compat: allow old filter to override on/off per slug
        if (\function_exists('apply_filters')) {
            $config = (array) \call_user_func('apply_filters', 'aqualuxe/modules/config', $config);
        }

        // 3) Load enabled modules when requirements are met
        foreach ($config as $slug => $enabled) {
            if (!$enabled) {
                continue;
            }
            if (!isset($registry[$slug])) {
                continue;
            }
            $meta = $registry[$slug];
            // Requirements check using simple predicates like function:, class:, defined:
            if (!self::meets_requirements($meta)) {
                // Log unmet requirements
                $logger = \AquaLuxe\Core\Container::get('logger');
                if ($logger && method_exists($logger, 'warning')) {
                    $logger->warning('Module {slug} skipped due to unmet requirements', ['slug' => $slug, 'requires' => $meta['requires'] ?? [], 'requiresAny' => $meta['requiresAny'] ?? []]);
                }
                // Optionally surface a notice in admin for visibility
                if ((\function_exists('is_admin') ? \call_user_func('is_admin') : false) && \function_exists('add_action') && (\function_exists('current_user_can') ? \call_user_func('current_user_can', 'manage_options') : false)) {
                    \call_user_func('add_action', 'admin_notices', static function () use ($meta) {
                        $name = isset($meta['name']) ? $meta['name'] : ($meta['slug'] ?? 'module');
                            $template = 'AquaLuxe: Module "%s" not loaded due to missing requirements.';
                        if (\function_exists('__')) { $template = (string) \call_user_func('__', 'AquaLuxe: Module "%s" not loaded due to missing requirements.', 'aqualuxe'); }
                            $message = sprintf($template, (string) $name);
                        if (\function_exists('esc_html')) { $message = (string) \call_user_func('esc_html', $message); }
                            echo '<div class="notice notice-warning"><p>' . $message . '</p></div>';
                    });
                }
                /**
                 * Filter: observe skipped modules with metadata.
                 *
                 * @param array $meta Module metadata.
                 */
                if (\function_exists('do_action')) { \call_user_func('do_action', 'aqualuxe/modules/skipped', $meta); }
                continue;
            }

            $file = AQUALUXE_MODULES . '/' . $slug . '/module.php';
            if (file_exists($file)) {
                try {
                    require_once $file;
                } catch (\Throwable $e) {
                    $logger = \AquaLuxe\Core\Container::get('logger');
                    if ($logger && method_exists($logger, 'error')) {
                        $logger->error('Error loading module {slug}: {msg}', ['slug' => $slug, 'msg' => $e->getMessage()]);
                    }
                }
            }
        }
    }

    /**
     * Discover modules and read metadata from module.json when present.
     * Returns associative array [ slug => meta ]. Meta always includes 'slug'.
     */
    public static function registry(): array
    {
        $dir = defined('AQUALUXE_MODULES') ? AQUALUXE_MODULES : null;
        if (!$dir || !is_dir($dir)) {
            return [];
        }
        // Avoid silencing errors; handle failures explicitly
        $entries = scandir($dir);
        if ($entries === false) {
            $entries = [];
        }
        $modules = [];
        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            $path = $dir . '/' . $entry;
            if (!is_dir($path)) {
                continue;
            }
            $module_php = $path . '/module.php';
            if (!file_exists($module_php)) {
                continue; // not a module
            }
            $meta = [
                'slug' => $entry,
                'enabled' => true,
            ];
            $json = $path . '/module.json';
            if (file_exists($json)) {
                $raw = file_get_contents($json);
                $data = json_decode((string) $raw, true);
                if (is_array($data)) {
                    $meta = array_merge($meta, $data);
                }
            }
            $modules[$entry] = $meta;
        }
        /**
         * Filter the module registry before loading.
         *
         * @param array $modules Associative array of module metadata keyed by slug.
         */
    if (\function_exists('apply_filters')) { return (array) \call_user_func('apply_filters', 'aqualuxe/modules/registry', $modules); }
        return $modules;
    }

    /**
     * Validate requirements declared in module meta.
     * Supports keys:
     *  - requires:    array of conditions, all must be true
     *  - requiresAny: array of conditions, at least one must be true
     * Condition formats (strings):
     *  - function:some_function
     *  - class:Some\\Class
     *  - defined:SOME_CONSTANT
     *
     * @param array $meta Module metadata array.
     * @return bool Whether all declared requirements are met.
     */
    private static function meets_requirements(array $meta): bool
    {
        $allOk = true;
        if (!empty($meta['requires']) && is_array($meta['requires'])) {
            foreach ($meta['requires'] as $cond) {
                if (!self::check_condition((string) $cond)) {
                    $allOk = false;
                    break;
                }
            }
        }

        $anyOk = true;
        if (!empty($meta['requiresAny']) && is_array($meta['requiresAny'])) {
            $anyOk = false;
            foreach ($meta['requiresAny'] as $cond) {
                if (self::check_condition((string) $cond)) {
                    $anyOk = true;
                    break;
                }
            }
        }

        return $allOk && $anyOk;
    }

    /**
     * Check a single requirement condition string.
     *
     * @param string $cond Condition string in the form type:name (function:foo, class:Bar\\Baz, defined:CONST).
     * @return bool True if the condition is satisfied.
     */
    private static function check_condition(string $cond): bool
    {
        if (strpos($cond, ':') === false) {
            return true; // unknown format, don't block
        }
        list($type, $name) = explode(':', $cond, 2);
        $name = trim($name);
        switch (trim(strtolower($type))) {
            case 'function':
                return function_exists($name);
            case 'class':
                return class_exists($name);
            case 'defined':
                return defined($name);
            default:
                return true;
        }
    }
}
