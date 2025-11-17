<?php
namespace AquaLuxe\Modules\Affiliates;

const COOKIE = 'aqualuxe_ref';

\add_action('init', function(){
    $ref = \sanitize_text_field($_GET['ref'] ?? '');
    if ($ref && !headers_sent()) {
        setcookie(COOKIE, $ref, time()+30*\DAY_IN_SECONDS, \COOKIEPATH, \COOKIE_DOMAIN);
    }
});

if (\class_exists('WooCommerce')) {
    \add_action('woocommerce_checkout_update_order_meta', function($order_id){
        if (!empty($_COOKIE[COOKIE])) {
            \update_post_meta($order_id, '_aqualuxe_ref', \sanitize_text_field($_COOKIE[COOKIE]));
        }
    });
}
