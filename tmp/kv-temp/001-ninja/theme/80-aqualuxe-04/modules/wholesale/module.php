<?php
/** Wholesale/B2B pricing module (minimal): adds a role + simple discount display */

add_action('init', function(){
    add_role('wholesale_customer', __('Wholesale Customer','aqualuxe'), ['read'=>true]);
});

function aqlx_is_wholesale_user(){ $u = wp_get_current_user(); return in_array('wholesale_customer', (array)$u->roles, true); }

add_filter('woocommerce_product_get_price', function($price){ return aqlx_is_wholesale_user() ? round($price*0.9, 2) : $price; });
add_filter('woocommerce_product_get_regular_price', function($price){ return aqlx_is_wholesale_user() ? round($price*0.9, 2) : $price; });
