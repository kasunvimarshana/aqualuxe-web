<?php
/**
 * Affiliates/Referrals module (lightweight):
 * - Adds an [aqualuxe_referral] shortcode that captures a ?ref= code to a cookie and prints a referral notice.
 * - Safe by default, no plugin dependency.
 */

if (!defined('ABSPATH')) { exit; }

add_shortcode('aqualuxe_referral', function($atts){
    $atts = shortcode_atts([
        'param' => 'ref',
        'cookie' => 'aqlx_ref',
        'days' => 30
    ], $atts, 'aqualuxe_referral');

    $param = sanitize_key($atts['param']);
    $cookie = sanitize_key($atts['cookie']);
    $days = max(1, (int) $atts['days']);

    if (!empty($_GET[$param])) {
        $code = sanitize_text_field(wp_unslash($_GET[$param]));
        setcookie($cookie, $code, time() + (DAY_IN_SECONDS * $days), COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
        $_COOKIE[$cookie] = $code; // make it available immediately
    }

    $code = isset($_COOKIE[$cookie]) ? sanitize_text_field($_COOKIE[$cookie]) : '';
    if (!$code) return '';

    return '<div class="aqlx-referral" role="status">' . sprintf(esc_html__('Referred by %s','aqualuxe'), esc_html($code)) . '</div>';
});
