<?php
/** Currency module: simple cookie-based currency switcher; Woo optional */
if (!defined('ABSPATH')) { exit; }

// Allowed currencies (centralized)
if (!function_exists('aqualuxe_allowed_currencies')) {
    function aqualuxe_allowed_currencies() {
        return ['USD','EUR','GBP'];
    }
}

add_action('init', function(){
    if (isset($_GET['ax_currency'])) {
        $cur = strtoupper(sanitize_text_field($_GET['ax_currency']));
        if (!preg_match('/^[A-Z]{3}$/', $cur)) return;
    // Enforce allowed list
    if (!in_array($cur, aqualuxe_allowed_currencies(), true)) return;
        $lifetime = time()+3600*24*30;
        $path = defined('COOKIEPATH') && COOKIEPATH ? COOKIEPATH : '/';
        $domain = defined('COOKIE_DOMAIN') && COOKIE_DOMAIN ? COOKIE_DOMAIN : '';
        $secure = is_ssl();
        $httponly = true;
        if (PHP_VERSION_ID >= 70300) {
            setcookie('ax_currency', $cur, [
                'expires' => $lifetime,
                'path' => $path,
                'domain' => $domain,
                'secure' => $secure,
                'httponly' => $httponly,
                'samesite' => 'Lax'
            ]);
        } else {
            setcookie('ax_currency', $cur, $lifetime, $path, $domain, $secure, $httponly);
        }
        $_COOKIE['ax_currency'] = $cur;
        if (!is_admin()) { wp_safe_redirect(remove_query_arg('ax_currency')); exit; }
    }
});

add_shortcode('ax_currency_switcher', function(){
    $allowed = aqualuxe_allowed_currencies();
    $cookie = isset($_COOKIE['ax_currency']) ? strtoupper(preg_replace('/[^A-Z]/','',$_COOKIE['ax_currency'])) : '';
    $woo_default = get_option('woocommerce_currency');
    $fallback = (is_string($woo_default) && in_array($woo_default, $allowed, true)) ? $woo_default : (isset($allowed[0]) ? $allowed[0] : 'USD');
    $current = $cookie ?: $fallback;
    $currencies = $allowed;
    $label = esc_html__('Currency','aqualuxe');
    // Preserve existing query params on click (except ax_currency)
    $base = [];
    foreach (($_GET ?? []) as $k => $v) {
        if ($k === 'ax_currency') continue;
        if (!is_scalar($v)) continue;
        $base[$k] = $v;
    }
    $out = '<div class="ax-currency" role="group" aria-label="'.$label.'">'
         . '<span class="ax-currency-label">'.$label.'</span>'
         . '<nav class="ax-currency-group" aria-label="'.$label.'">';
    $i = 0;
    foreach ($currencies as $c) {
        // Add a small textual space and a dot separator (aria-hidden) as no-CSS fallbacks so items don't butt up together
        if ($i++ > 0) { $out .= ' <span class="ax-cur-sep" aria-hidden="true">&middot;</span> '; }
        $params = $base; $params['ax_currency'] = $c;
        $href = esc_url(add_query_arg($params));
        $active = ($current === $c);
        if ($active) {
            $out .= '<span class="ax-cur-pill is-active" aria-current="true" aria-disabled="true">'.$c.'</span>';
        } else {
            $out .= '<a class="ax-cur-pill" href="'.$href.'" data-currency="'.esc_attr($c).'" rel="nofollow">'.$c.'</a>';
        }
    }
    $out .= '</nav></div>';
    return $out;
});

// Woo: filter currency if plugin allows (requires a currency switcher plugin normally; here we demonstrate using filter if present)
add_filter('woocommerce_currency', function($currency){
    $cur = isset($_COOKIE['ax_currency']) ? strtoupper(preg_replace('/[^A-Z]/','',$_COOKIE['ax_currency'])) : '';
    $allowed = aqualuxe_allowed_currencies();
    if ($cur && in_array($cur, $allowed, true)) return $cur;
    return $currency;
});
