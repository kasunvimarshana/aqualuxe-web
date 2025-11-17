<?php
namespace AquaLuxe\Modules\Multicurrency;

if (!defined('ABSPATH')) { exit; }

class Multicurrency {
    private static $allowed = ['USD','EUR','LKR'];

    public static function init(): void {
        if (!class_exists('WooCommerce')) { return; }
        \add_filter('woocommerce_currency', [__CLASS__, 'currency']);
        \add_action('init', [__CLASS__, 'capture_switch']);
        \add_shortcode('al_currency_switcher', [__CLASS__, 'switcher']);
    }

    public static function capture_switch(): void {
        if (isset($_GET['al_currency'])) {
            $c = strtoupper(\sanitize_text_field(\wp_unslash($_GET['al_currency'])));
            if (in_array($c, self::$allowed, true)) {
                \setcookie('al_currency', $c, time()+\MONTH_IN_SECONDS, \COOKIEPATH, \COOKIE_DOMAIN, \is_ssl());
                $_COOKIE['al_currency'] = $c;
            }
        }
    }

    public static function currency($curr) {
    $c = isset($_COOKIE['al_currency']) ? strtoupper(\sanitize_text_field(\wp_unslash($_COOKIE['al_currency']))) : $curr;
        return in_array($c, self::$allowed, true) ? $c : $curr;
    }

    public static function switcher(): string {
        $current = \function_exists('get_woocommerce_currency') ? \get_woocommerce_currency() : 'USD';
        $out = '<form method="get" class="al_currency_switcher"><label for="al_currency">' . esc_html__('Currency', 'aqualuxe') . '</label><select id="al_currency" name="al_currency" onchange="this.form.submit()">';
        foreach (self::$allowed as $c) {
            $sel = \selected($current, $c, false);
            $out .= '<option value="' . esc_attr($c) . '" ' . $sel . '>' . esc_html($c) . '</option>';
        }
        $out .= '</select></form>';
        return $out;
    }
}
