<?php
// Multicurrency module: lightweight, cached rates + formatting + Woo integration

// Determine current currency (GET param > cookie > site default)
if (\function_exists('add_filter')) {
    \call_user_func('add_filter', 'aqualuxe/currency/current', function($cur){
        $raw = isset($_GET['currency']) ? $_GET['currency'] : '';
        $wanted = $raw ? strtoupper((\function_exists('sanitize_text_field') ? \call_user_func('sanitize_text_field', $raw) : $raw)) : '';
        $allowed = \function_exists('apply_filters') ? \call_user_func('apply_filters', 'aqualuxe/currency/allowed', ['USD','EUR','GBP']) : ['USD','EUR','GBP'];
        if ($wanted && in_array($wanted, $allowed, true)) {
            // Persist preference
            $cookiePath = \defined('COOKIEPATH') ? \constant('COOKIEPATH') : '/';
            $cookieDomain = \defined('COOKIE_DOMAIN') ? \constant('COOKIE_DOMAIN') : '';
            $secure = (\function_exists('is_ssl') ? \call_user_func('is_ssl') : false);
            \setcookie('aqlx_currency', $wanted, time()+60*60*24*30, $cookiePath, $cookieDomain, $secure, true);
            return $wanted;
        }
        if (!empty($_COOKIE['aqlx_currency']) && in_array($_COOKIE['aqlx_currency'], $allowed, true)) {
            return $_COOKIE['aqlx_currency'];
        }
        return $cur ?: 'USD';
    }, 10, 1);
}

// Fetch rates (default via exchangerate.host, cached). Filter allows overriding provider.
function aqlx_mc_get_rates(string $base = 'USD'): array {
    $key = 'aqlx_rates_' . $base;
    $cached = \function_exists('get_transient') ? \call_user_func('get_transient', $key) : null;
    if (is_array($cached) && $cached) return $cached;
    $rates = \function_exists('apply_filters') ? \call_user_func('apply_filters', 'aqualuxe/multicurrency/rates', []) : [];
    if (!$rates) {
        $url = 'https://api.exchangerate.host/latest?base=' . rawurlencode($base) . '&symbols=USD,EUR,GBP';
        $resp = \function_exists('wp_remote_get') ? \call_user_func('wp_remote_get', $url, ['timeout' => 8]) : null;
        $ok = $resp && (!\function_exists('is_wp_error') || !\call_user_func('is_wp_error', $resp));
        if ($ok) {
            $code = \function_exists('wp_remote_retrieve_response_code') ? (int) \call_user_func('wp_remote_retrieve_response_code', $resp) : 200;
            if (200 === $code) {
                $bodyStr = \function_exists('wp_remote_retrieve_body') ? (string) \call_user_func('wp_remote_retrieve_body', $resp) : '';
                $body = json_decode($bodyStr, true);
                $rates = isset($body['rates']) && is_array($body['rates']) ? $body['rates'] : [];
            }
        }
    }
    if ($rates && \function_exists('set_transient')) { \call_user_func('set_transient', $key, $rates, 6 * (\defined('HOUR_IN_SECONDS') ? \constant('HOUR_IN_SECONDS') : 3600)); }
    return is_array($rates) ? $rates : [];
}

// Format money using intl if present
function aqlx_mc_format(float $amount, string $currency): string {
    if (class_exists('NumberFormatter')) {
        try { $fmt = new \NumberFormatter((\function_exists('get_locale') ? \call_user_func('get_locale') : 'en_US'), \NumberFormatter::CURRENCY); return $fmt->formatCurrency($amount, $currency); } catch (\Throwable $e) {}
    }
    $symbol = $currency === 'EUR' ? '€' : ($currency === 'GBP' ? '£' : '$');
    return $symbol . number_format($amount, 2);
}

// Filter WooCommerce price HTML to convert to selected currency
if (class_exists('WooCommerce') && \function_exists('add_filter')) {
    \call_user_func('add_filter', 'woocommerce_get_price_html', function($price_html, $product){
        $target = \function_exists('apply_filters') ? \call_user_func('apply_filters', 'aqualuxe/currency/current', 'USD') : 'USD';
        $base = \function_exists('apply_filters') ? \call_user_func('apply_filters', 'aqualuxe/currency/base', 'USD') : 'USD';
        if (!is_string($price_html) || !$product) return $price_html;
        $rates = aqlx_mc_get_rates($base);
        $rate = isset($rates[$target]) && $rates[$target] > 0 ? (float) $rates[$target] : 1.0;
        // Compute representative price (min variation if variable)
        $raw = (float) (method_exists($product, 'get_price') ? $product->get_price() : 0);
        if ($raw <= 0 && method_exists($product, 'get_variation_price')) {
            $raw = (float) $product->get_variation_price('min', true);
        }
        $converted = $raw * (strtoupper($base) === strtoupper($target) ? 1 : $rate);
        $formatted = aqlx_mc_format($converted, $target);
        // Replace any existing currency/amount loosely
        return preg_replace('/\$?\s?\d+[\.,\d]*/', $formatted, $price_html) ?: $formatted;
    }, 10, 2);
}

// Back-compat: symbol filter keeps working
if (\function_exists('add_filter')) {
    \call_user_func('add_filter', 'aqualuxe/currency/symbol', function($symbol){
        $cur = \function_exists('apply_filters') ? \call_user_func('apply_filters', 'aqualuxe/currency/current', 'USD') : 'USD';
        return $cur ?: ($symbol ?: 'USD');
    });
}

// Accessible currency switcher shortcode (non-JS fallback form)
add_shortcode('aqualuxe_currency_switcher', function(){
    $allowed = apply_filters('aqualuxe/currency/allowed', ['USD','EUR','GBP']);
    $current = apply_filters('aqualuxe/currency/current', 'USD');
    $action = esc_url(add_query_arg([]));
    $out = '<form method="get" action="' . $action . '" class="aqlx-currency" aria-label="' . esc_attr__('Currency selector','aqualuxe') . '">';
    $out .= '<label class="sr-only" for="aqlx-currency-select">' . esc_html__('Select currency','aqualuxe') . '</label>';
    $out .= '<select id="aqlx-currency-select" name="currency" onchange="this.form.submit()">';
    foreach ($allowed as $c) {
        $sel = selected($current, $c, false);
        $out .= '<option value="' . esc_attr($c) . '" ' . $sel . '>' . esc_html($c) . '</option>';
    }
    $out .= '</select>';
    // Preserve other query args
    foreach ($_GET as $k=>$v) {
        if ($k === 'currency') continue;
        if (is_array($v)) continue; // keep simple
        $out .= '<input type="hidden" name="' . esc_attr($k) . '" value="' . esc_attr(wp_unslash((string)$v)) . '" />';
    }
    $out .= '<noscript><button type="submit" class="button">' . esc_html__('Update','aqualuxe') . '</button></noscript>';
    $out .= '</form>';
    return $out;
});
