<?php
defined('ABSPATH') || exit;

if (!class_exists('WooCommerce')) return;

// Allowed currencies
$allowed = apply_filters('aqlx_multicurrency_allowed', ['USD','EUR','GBP']);

add_filter('woocommerce_currency', function ($currency) use ($allowed) {
    if (!empty($_GET['currency'])) {
        $c = strtoupper(sanitize_text_field($_GET['currency']));
        if (in_array($c, $allowed, true)) {
            setcookie('aqlx_currency', $c, time() + 31536000, '/');
            return $c;
        }
    }
    if (!empty($_COOKIE['aqlx_currency']) && in_array($_COOKIE['aqlx_currency'], $allowed, true)) {
        return $_COOKIE['aqlx_currency'];
    }
    return $currency;
});
