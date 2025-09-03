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
    add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    add_action('admin_notices', [$this, 'maybe_show_build_notice']);
    add_action('enqueue_block_editor_assets', [$this, 'enqueue_editor_assets']);
    }

    public function boot(Container $c): void {}

    private function asset_uri(string $file): string
    {
        $base_uri = \get_template_directory_uri() . '/assets/dist/';
        $manifest_path = \get_template_directory() . '/assets/dist/mix-manifest.json';
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
    $manifest_path = \get_template_directory() . '/assets/dist/mix-manifest.json';
    if (!\file_exists($manifest_path)) {
            // Do not enqueue raw assets if build not present
            return;
        }

        $tailwind = $this->asset_uri('tailwind.css');
        $style = $this->asset_uri('theme.css');
        $script = $this->asset_uri('theme.js');

        \wp_enqueue_style('aqualuxe-tailwind', $tailwind, [], null, 'all');
        \wp_enqueue_style('aqualuxe-theme', $style, ['aqualuxe-tailwind'], null, 'all');

        // Register script to allow localization prior to enqueue
        \wp_register_script('aqualuxe-theme', $script, ['wp-i18n'], null, true);
        \wp_script_add_data('aqualuxe-theme', 'defer', true);

        // Pass theme options to JS (e.g., dark mode default)
        $dark_default = \get_theme_mod('aqualuxe_dark_mode_default', 'system');
        \wp_localize_script('aqualuxe-theme', 'AQLX', [
            'darkModeDefault' => is_string($dark_default) ? $dark_default : 'system',
        ]);

        \wp_enqueue_script('aqualuxe-theme');

        // Optional skin stylesheet if present
        $skin = \get_option('aqualuxe_skin', \apply_filters('aqualuxe_active_skin', 'default'));
        $skin_file = \sprintf('skin-%s.css', \sanitize_key((string) $skin));
        $skin_path = \get_template_directory() . '/assets/dist/' . $skin_file;
        if (\file_exists($skin_path)) {
            \wp_enqueue_style('aqualuxe-skin', $this->asset_uri($skin_file), ['aqualuxe-theme'], null, 'all');
        }
    }

    /**
     * Show an admin notice if assets haven't been built yet.
     */
    public function maybe_show_build_notice(): void
    {
        if (!\current_user_can('manage_options')) {
            return;
        }
        $manifest_path = \get_template_directory() . '/assets/dist/mix-manifest.json';
        if (!\file_exists($manifest_path)) {
            echo '<div class="notice notice-warning"><p>'
                . esc_html__('AquaLuxe assets are not built. Run the build to generate assets/dist/mix-manifest.json.', 'aqualuxe')
                . '</p></div>';
        }
    }

    /**
     * Enqueue editor stylesheet for block editor.
     */
    public function enqueue_editor_assets(): void
    {
        $manifest_path = \get_template_directory() . '/assets/dist/mix-manifest.json';
        if (!\file_exists($manifest_path)) {
            return;
        }
        $editor = $this->asset_uri('editor.css');
        \wp_enqueue_style('aqualuxe-editor', $editor, [], null, 'all');
    }
}
