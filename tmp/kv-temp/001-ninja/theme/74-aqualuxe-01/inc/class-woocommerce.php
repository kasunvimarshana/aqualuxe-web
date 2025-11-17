<?php
namespace AquaLuxe;

if (!class_exists('WooCommerce')) { return; }

class WooIntegration {
    public static function init(): void {
        \add_action('wp_ajax_aqualuxe_quickview', [self::class, 'quickview']);
        \add_action('wp_ajax_nopriv_aqualuxe_quickview', [self::class, 'quickview']);
        \add_action('wp_enqueue_scripts', [self::class, 'localize']);
    }

    public static function localize(): void {
        \wp_localize_script('aqualuxe-woo', 'AquaLuxeWoo', [
            'ajax_url' => \admin_url('admin-ajax.php'),
            'nonce' => \wp_create_nonce('aqualuxe_woo'),
        ]);
    }

    public static function quickview(): void {
        \check_ajax_referer('aqualuxe_woo');
        $id = \absint($_GET['id'] ?? 0);
        if (!$id) \wp_send_json_error(['message' => __('Invalid product ID', 'aqualuxe')]);
        $product = \wc_get_product($id);
        if (!$product) \wp_send_json_error(['message' => __('Product not found', 'aqualuxe')]);
        \ob_start();
        $GLOBALS['post'] = \get_post($id); \setup_postdata($GLOBALS['post']);
        \locate_template('templates/woo-quickview.php', true, false);
        \wp_reset_postdata();
        $html = \ob_get_clean();
        \wp_send_json_success(['html' => $html]);
    }
}

WooIntegration::init();
