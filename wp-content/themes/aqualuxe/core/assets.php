<?php
namespace AquaLuxe\Core;

class Assets {
    public static function enqueue() : void {
        $manifest = self::manifest();
        $css = $manifest['/css/main.css'] ?? null;
        $js  = $manifest['/js/app.js'] ?? null;

    $theme_uri  = \get_template_directory_uri();
    $theme_path = \get_template_directory();

        // Styles
        if ($css) {
            \wp_enqueue_style('aqualuxe-main', $theme_uri . '/assets/dist' . $css, [], null, 'all');
        } else {
            $fallback_css = $theme_path . '/assets/dist/css/main.css';
            if (file_exists($fallback_css)) {
                \wp_enqueue_style('aqualuxe-main', $theme_uri . '/assets/dist/css/main.css', [], filemtime($fallback_css), 'all');
            }
        }

        $script_enqueued = false;
        // Scripts
        if ($js) {
            \wp_enqueue_script('aqualuxe-app', $theme_uri . '/assets/dist' . $js, ['jquery'], null, true);
            $script_enqueued = true;
        } else {
            $fallback_js = $theme_path . '/assets/dist/js/app.js';
            if (file_exists($fallback_js)) {
                \wp_enqueue_script('aqualuxe-app', $theme_uri . '/assets/dist/js/app.js', ['jquery'], filemtime($fallback_js), true);
                $script_enqueued = true;
            }
        }

        // Localize only if our script is present
        if ($script_enqueued) {
            \wp_localize_script('aqualuxe-app', 'AquaLuxe', [
                'ajaxUrl'   => \admin_url('admin-ajax.php'),
                'nonce'     => \wp_create_nonce('aqlx_nonce'),
                'i18n'      => [
                    'addedToWishlist' => \esc_html__('Added to wishlist', 'aqualuxe'),
                    'removedFromWishlist' => \esc_html__('Removed from wishlist', 'aqualuxe'),
                ],
                'hasWoo'    => class_exists('WooCommerce'),
            ]);
        }
    }

    private static function manifest() : array {
    $path = \get_template_directory() . '/assets/dist/mix-manifest.json';
        if (!file_exists($path)) {
            return [];
        }
        $json = file_get_contents($path);
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }
}
