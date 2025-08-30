<?php
namespace AquaLuxe\Modules\Affiliate;

class Module {
    public static function init(): void {
        add_action('init', [__CLASS__, 'capture_ref']);
        add_action('woocommerce_thankyou', [__CLASS__, 'attach_ref_to_order']);
    }

    public static function capture_ref(): void {
        if (!empty($_GET['ref'])) {
            $ref = preg_replace('/[^a-zA-Z0-9_-]/','', $_GET['ref']);
            setcookie('aqlx_ref', $ref, time()+60*60*24*30, '/');
        }
    }

    public static function attach_ref_to_order($order_id): void {
        if (!$order_id || empty($_COOKIE['aqlx_ref'])) return;
        update_post_meta($order_id, '_aqlx_ref', sanitize_text_field($_COOKIE['aqlx_ref']));
    }
}
