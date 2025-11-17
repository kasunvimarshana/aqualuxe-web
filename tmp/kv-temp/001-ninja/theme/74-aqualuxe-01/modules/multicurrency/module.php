<?php
namespace AquaLuxe\Modules\MultiCurrency;

const COOKIE = 'aqualuxe_currency';

\add_action('init', function(){
    $cur = \sanitize_text_field($_GET['currency'] ?? '');
    if ($cur && !headers_sent()) setcookie(COOKIE, $cur, time()+30*\DAY_IN_SECONDS, \COOKIEPATH, \COOKIE_DOMAIN);
});

if (\class_exists('WooCommerce')) {
    \add_filter('woocommerce_currency', function($currency){
        $sel = $_COOKIE[COOKIE] ?? '';
        return $sel ?: $currency;
    });
    \add_filter('woocommerce_product_get_price', function($price){
        $sel = $_COOKIE[COOKIE] ?? '';
        $rates = \apply_filters('aqualuxe_currency_rates', [ 'USD' => 1, 'EUR' => 0.92, 'GBP' => 0.78 ]);
        $base = \get_option('woocommerce_currency');
        if ($sel && isset($rates[$base], $rates[$sel]) && $rates[$base] > 0) {
            $price = (float)$price * ($rates[$sel]/$rates[$base]);
        }
        return $price;
    });
}

\add_action('aqualuxe/header/actions', function(){
    $currencies = \apply_filters('aqualuxe_currencies', ['USD','EUR','GBP']);
    echo '<form method="get" class="text-sm">';
    echo '<select name="currency" class="bg-transparent border rounded px-2 py-1" onchange="this.form.submit()">';
    $current = $_COOKIE[COOKIE] ?? \get_option('woocommerce_currency', 'USD');
    foreach ($currencies as $c) {
        printf('<option value="%1$s" %2$s>%1$s</option>', \esc_attr($c), selected($current, $c, false));
    }
    echo '</select></form>';
});
