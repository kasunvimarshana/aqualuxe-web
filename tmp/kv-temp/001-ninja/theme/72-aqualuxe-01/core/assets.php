<?php
namespace AquaLuxe\Core;

class Assets {
    public static function enqueue() : void {
        $manifest = self::manifest();
        $css = $manifest['/css/main.css'] ?? null;
        $js  = $manifest['/js/app.js'] ?? null;

        // Styles
        if ($css) {
            wp_enqueue_style('aqualuxe-main', get_template_directory_uri() . '/assets/dist' . $css, [], null, 'all');
        }
        // Scripts
        if ($js) {
            wp_enqueue_script('aqualuxe-app', get_template_directory_uri() . '/assets/dist' . $js, ['jquery'], null, true);
        }

        // Localize
        wp_localize_script('aqualuxe-app', 'AquaLuxe', [
            'ajaxUrl'   => admin_url('admin-ajax.php'),
            'nonce'     => wp_create_nonce('aqlx_nonce'),
            'i18n'      => [
                'addedToWishlist' => esc_html__('Added to wishlist', 'aqualuxe'),
                'removedFromWishlist' => esc_html__('Removed from wishlist', 'aqualuxe'),
            ],
            'hasWoo'    => class_exists('WooCommerce'),
        ]);
    }

    private static function manifest() : array {
        $path = get_template_directory() . '/assets/dist/mix-manifest.json';
        if (!file_exists($path)) {
            return [];
        }
        $json = file_get_contents($path);
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }
}
