<?php
namespace AquaLuxe\Modules\Wholesale;

\add_action('init', function(){
    \add_role('aqualuxe_wholesale', \__('Wholesale', 'aqualuxe'), ['read' => true]);
});

// Wholesale price display (10% off) if logged in with role
if (\class_exists('WooCommerce')) {
    \add_filter('woocommerce_product_get_price', function($price, $product){
        if (\current_user_can('aqualuxe_wholesale')) {
            return \wc_format_decimal((float)$price * 0.9, \wc_get_price_decimals());
        }
        return $price;
    }, 10, 2);
}
