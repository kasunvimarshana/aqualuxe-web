<?php
namespace AquaLuxe\Admin;

use AquaLuxe\Core\Modules as CoreModules;

class ModuleManager
{
    public static function init(): void
    {
        \add_action('admin_menu', [__CLASS__, 'menu']);
    }

    public static function menu(): void
    {
        \add_theme_page(
            \__('AquaLuxe Modules', 'aqualuxe'),
            \__('AquaLuxe Modules', 'aqualuxe'),
            'manage_options',
            'aqualuxe-modules',
            [__CLASS__, 'render']
        );
    }

    private static function build_defaults(array $registry): array
    {
        $defaults = [];
        foreach ($registry as $slug => $meta) {
            $defaults[$slug] = isset($meta['enabled']) ? (bool) $meta['enabled'] : true;
        }
        return $defaults;
    }

    private static function current_config(array $registry): array
    {
        $defaults = self::build_defaults($registry);
        $db = (array) (\function_exists('get_option') ? \call_user_func('get_option', 'aqualuxe_modules', []) : []);
        return array_merge($defaults, $db);
    }

    public static function render(): void
    {
        if (!\current_user_can('manage_options')) {
            return;
        }

        $registry = CoreModules::registry();

        // Current screen help tab
        if (function_exists('get_current_screen')) {
            $screen = \get_current_screen();
            if ($screen && method_exists($screen, 'add_help_tab')) {
                $screen->add_help_tab([
                    'id'      => 'aqlx-modules-help',
                    'title'   => __('About Modules', 'aqualuxe'),
                    'content' => '<p>' . \esc_html__('Enable or disable theme modules. Some modules require companion plugins; unmet requirements prevent loading even if enabled. Changes take effect immediately. If routes are added (e.g., sitemap), rewrites are flushed automatically.', 'aqualuxe') . '</p>',
                ]);
            }
        }

        // Handle save
        if (isset($_POST['aqlx_modules_save'])) {
            \check_admin_referer('aqlx_modules_save');
            $prev = self::current_config($registry);
            $enabledSlugs = [];
            if (isset($_POST['modules']) && is_array($_POST['modules'])) {
                foreach (array_keys($_POST['modules']) as $slug) {
                    $enabledSlugs[] = sanitize_key((string) $slug);
                }
            }
            $new = [];
            foreach ($registry as $slug => $meta) {
                $new[$slug] = in_array($slug, $enabledSlugs, true);
            }
            \update_option('aqualuxe_modules', $new, false);
            // If sitemap-like modules changed, flush rewrites to register/unregister routes
            $changed = array_diff_assoc($new, $prev);
            if ($changed && function_exists('flush_rewrite_rules')) {
                \flush_rewrite_rules(false);
            }
            \add_settings_error('aqlx_modules', 'saved', \__('Modules settings saved.', 'aqualuxe'), 'updated');
        }

        $config = self::current_config($registry);
        \settings_errors('aqlx_modules');

        echo '<div class="wrap">';
        echo '<h1>' . \esc_html(get_admin_page_title()) . '</h1>';
        echo '<p>' . \esc_html__('Toggle theme modules. Some modules may require companion plugins; unmet requirements prevent loading.', 'aqualuxe') . '</p>';
        echo '<form method="post">';
        \wp_nonce_field('aqlx_modules_save');
        echo '<table class="widefat fixed striped" style="max-width:960px;">';
        echo '<thead><tr>'
            . '<th style="width:180px;">' . \esc_html__('Module', 'aqualuxe') . '</th>'
            . '<th>' . \esc_html__('Description', 'aqualuxe') . '</th>'
            . '<th style="width:160px;">' . \esc_html__('Requirements', 'aqualuxe') . '</th>'
            . '<th style="width:120px;">' . \esc_html__('Enabled', 'aqualuxe') . '</th>'
            . '</tr></thead><tbody>';

        foreach ($registry as $slug => $meta) {
            $name = isset($meta['name']) ? (string) $meta['name'] : ucfirst(str_replace('-', ' ', (string) $slug));
            $desc = isset($meta['description']) ? (string) $meta['description'] : '';
            [$reqOk, $reqText] = self::requirements_status($meta);
            $checked = !empty($config[$slug]);
            echo '<tr>';
            echo '<td><strong>' . \esc_html($name) . '</strong><br><code>' . \esc_html($slug) . '</code></td>';
            echo '<td>' . \wp_kses_post($desc ?: '') . '</td>';
            echo '<td>' . ($reqOk ? '<span class="dashicons dashicons-yes" style="color:#46b450"></span> ' . \esc_html__('OK', 'aqualuxe') : '<span class="dashicons dashicons-warning" style="color:#d63638"></span> ' . \esc_html($reqText)) . '</td>';
            echo '<td><label><input type="checkbox" name="modules[' . \esc_attr($slug) . ']" value="1"' . ($checked ? ' checked' : '') . '> ' . \esc_html__('Enable', 'aqualuxe') . '</label></td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
        echo '<p><button class="button button-primary" type="submit" name="aqlx_modules_save" value="1">' . \esc_html__('Save Changes', 'aqualuxe') . '</button></p>';
        echo '</form>';
        echo '</div>';
    }

    private static function requirements_status(array $meta): array
    {
        $missing = [];
        if (!empty($meta['requires']) && is_array($meta['requires'])) {
            foreach ($meta['requires'] as $cond) {
                if (!self::check_condition((string) $cond)) {
                    $missing[] = (string) $cond;
                }
            }
        }
        if (!empty($meta['requiresAny']) && is_array($meta['requiresAny'])) {
            $anyOk = false;
            foreach ($meta['requiresAny'] as $cond) {
                if (self::check_condition((string) $cond)) {
                    $anyOk = true; break;
                }
            }
            if (!$anyOk) {
                $missing = array_merge($missing, array_map('strval', (array) $meta['requiresAny']));
            }
        }
        if (!$missing) {
            return [true, ''];
        }
        return [false, sprintf(\__('Missing: %s', 'aqualuxe'), implode(', ', $missing))];
    }

    private static function check_condition(string $cond): bool
    {
        if (strpos($cond, ':') === false) {
            return true;
        }
        list($type, $name) = explode(':', $cond, 2);
        $name = trim((string) $name);
        switch (strtolower(trim((string) $type))) {
            case 'function':
                return \function_exists($name);
            case 'class':
                return \class_exists($name);
            case 'defined':
                return \defined($name);
            default:
                return true;
        }
    }
}
