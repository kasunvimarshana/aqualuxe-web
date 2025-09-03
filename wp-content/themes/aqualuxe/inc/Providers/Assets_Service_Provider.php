<?php
/**
 * Enqueue scripts/styles with Webpack assets manifest support.
 */

namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class Assets_Service_Provider
{
    public function register(Container $c): void
    {
        if (\function_exists('add_action')) {
            \call_user_func('add_action', 'wp_enqueue_scripts', [$this, 'enqueue_assets']);
            \call_user_func('add_action', 'admin_notices', [$this, 'maybe_show_build_notice']);
            \call_user_func('add_action', 'enqueue_block_editor_assets', [$this, 'enqueue_editor_assets']);
        }
    }

    public function boot(Container $c): void {}

    private function asset_uri(string $file): string
    {
    $base_uri = (\function_exists('get_template_directory_uri') ? \call_user_func('get_template_directory_uri') : '') . '/assets/dist/';
    $manifest_path = (\function_exists('get_template_directory') ? \call_user_func('get_template_directory') : __DIR__ . '/../../') . '/assets/dist/mix-manifest.json';
        if (\file_exists($manifest_path)) {
            $manifest = \json_decode((string) \file_get_contents($manifest_path), true);
            $keys = ['/' . $file, '/assets/dist/' . $file];
            foreach ($keys as $key) {
                if (isset($manifest[$key])) {
                    $versioned = ltrim($manifest[$key], '/');
                    return $base_uri . preg_replace('#^assets/dist/#', '', $versioned);
                }
            }
        }
        return $base_uri . ltrim($file, '/');
    }

    public function enqueue_assets(): void
    {
        $manifest_path = (\function_exists('get_template_directory') ? \call_user_func('get_template_directory') : __DIR__ . '/../../') . '/assets/dist/mix-manifest.json';
        if (!\file_exists($manifest_path)) {
            // Do not enqueue raw assets if build not present
            return;
        }
        if (!\function_exists('wp_enqueue_style') || !\function_exists('wp_register_script')) { return; }

        $tailwind = $this->asset_uri('tailwind.css');
        $style = $this->asset_uri('theme.css');
        $script = $this->asset_uri('theme.js');

        \call_user_func('wp_enqueue_style', 'aqualuxe-tailwind', $tailwind, [], null, 'all');
        \call_user_func('wp_enqueue_style', 'aqualuxe-theme', $style, ['aqualuxe-tailwind'], null, 'all');

        // Register script to allow localization prior to enqueue
        \call_user_func('wp_register_script', 'aqualuxe-theme', $script, ['wp-i18n'], null, true);
        if (\function_exists('wp_script_add_data')) { \call_user_func('wp_script_add_data', 'aqualuxe-theme', 'defer', true); }

        // Pass theme options to JS (e.g., dark mode default)
        $dark_default = \function_exists('get_theme_mod') ? \call_user_func('get_theme_mod', 'aqualuxe_dark_mode_default', 'system') : 'system';
        $ajax_url = \function_exists('admin_url') ? \call_user_func('admin_url', 'admin-ajax.php') : '/wp-admin/admin-ajax.php';
        $nonce = \function_exists('wp_create_nonce') ? \call_user_func('wp_create_nonce', 'aqlx_ajax') : '';
        $wishlist_count = 0;
        if (\function_exists('is_user_logged_in') && \call_user_func('is_user_logged_in') && \function_exists('get_current_user_id') && \function_exists('get_user_meta')) {
            $uid = \call_user_func('get_current_user_id');
            $list = (array) \call_user_func('get_user_meta', $uid, '_aqlx_wishlist', true);
            $wishlist_count = count(array_filter(array_map('intval', $list)));
        }
        \call_user_func('wp_localize_script', 'aqualuxe-theme', 'AQLX', [
            'darkModeDefault' => is_string($dark_default) ? $dark_default : 'system',
            'ajaxUrl' => $ajax_url,
            'nonce' => $nonce,
            'wishlistCount' => $wishlist_count,
        ]);

        \call_user_func('wp_enqueue_script', 'aqualuxe-theme');

        // Optional skin stylesheet if present
        $skin = \function_exists('get_option') ? \call_user_func('get_option', 'aqualuxe_skin', (\function_exists('apply_filters') ? \call_user_func('apply_filters', 'aqualuxe_active_skin', 'default') : 'default')) : 'default';
        $skin_file = \sprintf('skin-%s.css', (\function_exists('sanitize_key') ? \call_user_func('sanitize_key', (string) $skin) : (string) $skin));
        $skin_path = (\function_exists('get_template_directory') ? \call_user_func('get_template_directory') : __DIR__ . '/../../') . '/assets/dist/' . $skin_file;
        if (\file_exists($skin_path)) {
            \call_user_func('wp_enqueue_style', 'aqualuxe-skin', $this->asset_uri($skin_file), ['aqualuxe-theme'], null, 'all');
        }
    }

    /**
     * Show an admin notice if assets haven't been built yet.
     */
    public function maybe_show_build_notice(): void
    {
        if (!\function_exists('current_user_can') || !\call_user_func('current_user_can', 'manage_options')) {
            return;
        }
        $manifest_path = (\function_exists('get_template_directory') ? \call_user_func('get_template_directory') : __DIR__ . '/../../') . '/assets/dist/mix-manifest.json';
        if (!\file_exists($manifest_path)) {
            $msg = \function_exists('esc_html__') ? \call_user_func('esc_html__', 'AquaLuxe assets are not built. Run the build to generate assets/dist/mix-manifest.json.', 'aqualuxe') : 'AquaLuxe assets are not built. Run the build to generate assets/dist/mix-manifest.json.';
            echo '<div class="notice notice-warning"><p>' . $msg . '</p></div>';
        }
    }

    /**
     * Enqueue editor stylesheet for block editor.
     */
    public function enqueue_editor_assets(): void
    {
    $manifest_path = (\function_exists('get_template_directory') ? \call_user_func('get_template_directory') : __DIR__ . '/../../') . '/assets/dist/mix-manifest.json';
        if (!\file_exists($manifest_path)) {
            return;
        }
        $editor = $this->asset_uri('editor.css');
    if (\function_exists('wp_enqueue_style')) { \call_user_func('wp_enqueue_style', 'aqualuxe-editor', $editor, [], null, 'all'); }
    }
}
