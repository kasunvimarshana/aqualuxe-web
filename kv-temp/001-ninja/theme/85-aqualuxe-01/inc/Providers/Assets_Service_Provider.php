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
    }

    public function boot(Container $c): void {}

    private function asset_uri(string $file): string
    {
        $base_uri = get_template_directory_uri() . '/assets/dist/';
        $manifest_path = get_template_directory() . '/assets/dist/manifest.json';
        if (file_exists($manifest_path)) {
            $manifest = json_decode((string) file_get_contents($manifest_path), true);
            if (isset($manifest[$file]['file'])) {
                return $base_uri . ltrim($manifest[$file]['file'], '/');
            }
        }
        return $base_uri . ltrim($file, '/');
    }

    public function enqueue_assets(): void
    {
        $style = $this->asset_uri('theme.css');
        $script = $this->asset_uri('theme.js');

        wp_enqueue_style('aqualuxe-theme', $style, [], null, 'all');
        wp_enqueue_script('aqualuxe-theme', $script, ['wp-i18n'], null, true);
        wp_script_add_data('aqualuxe-theme', 'defer', true);

        // Optional skin stylesheet if present
        $skin = get_option('aqualuxe_skin', apply_filters('aqualuxe_active_skin', 'default'));
        $skin_file = sprintf('skin-%s.css', sanitize_key((string) $skin));
        $skin_path = get_template_directory() . '/assets/dist/' . $skin_file;
        if (file_exists($skin_path)) {
            wp_enqueue_style('aqualuxe-skin', $this->asset_uri($skin_file), ['aqualuxe-theme'], null, 'all');
        }
    }
}
