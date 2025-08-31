<?php
/** Multicurrency module: lightweight cookie-based switcher with rates.
 * Not a replacement for full payment gateway currency handling; for demo/UX only.
 */

add_action('init', function(){
    if (isset($_GET['set_currency'])) {
        $cur = strtoupper(sanitize_text_field($_GET['set_currency']));
        $allowed = aqlx_currency_allowed();
        if (isset($allowed[$cur])) {
            setcookie('aqlx_currency', $cur, time()+YEAR_IN_SECONDS, COOKIEPATH ?: '/');
            $_COOKIE['aqlx_currency'] = $cur;
        }
        if (!is_admin()) wp_safe_redirect(remove_query_arg('set_currency'));
        exit;
    }
});

function aqlx_currency_allowed(){
    return apply_filters('aqlx/currencies', [
        'USD' => ['symbol'=>'$','rate'=>1],
        'EUR' => ['symbol'=>'€','rate'=>0.92],
        'GBP' => ['symbol'=>'£','rate'=>0.78],
        'LKR' => ['symbol'=>'Rs','rate'=>300],
    ]);
}

function aqlx_currency_current(){
    $allowed = aqlx_currency_allowed();
    $cur = strtoupper($_COOKIE['aqlx_currency'] ?? 'USD');
    return isset($allowed[$cur]) ? $cur : 'USD';
}

add_shortcode('aqualuxe_currency_switcher', function(){
    $allowed = aqlx_currency_allowed();
    $cur = aqlx_currency_current();
    $out = '<form method="get" action="" class="inline-flex gap-2 items-center">';
    $out .= '<label class="text-sm">' . esc_html__('Currency','aqualuxe') . '</label>';
    $out .= '<select name="set_currency" onchange="this.form.submit()" class="border px-2 py-1">';
    foreach ($allowed as $code=>$data){
        $out .= '<option value="' . esc_attr($code) . '"' . selected($cur,$code,false) . '>' . esc_html($code) . '</option>';
    }
    $out .= '</select></form>';
    return $out;
});

// WooCommerce integration (display only)
if (!function_exists('aqlx_convert_price')){
    function aqlx_convert_price($price){
        $allowed = aqlx_currency_allowed(); $cur = aqlx_currency_current();
        $rate = $allowed[$cur]['rate'] ?? 1;
        return round(floatval($price) * $rate, 2);
    }
}

add_filter('woocommerce_currency', function($currency){ return aqlx_currency_current(); });
add_filter('woocommerce_product_get_price', function($price){ return aqlx_convert_price($price); });
add_filter('woocommerce_product_get_regular_price', function($price){ return aqlx_convert_price($price); });
add_filter('woocommerce_cart_item_price', function($price, $cart_item, $cart_item_key){ return $price; }, 10, 3);
