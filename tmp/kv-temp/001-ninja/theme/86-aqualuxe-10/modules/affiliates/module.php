<?php
// Capture referral parameter and set a cookie
add_action('init', function(){
    $ref = isset($_GET['ref']) ? sanitize_text_field(wp_unslash($_GET['ref'])) : '';
    if ($ref) {
        setcookie('aqlx_ref', $ref, time()+30*DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
        do_action('aqualuxe/affiliate/captured', $ref);
    }
});

add_shortcode('aqualuxe_referral', function(){
    $ref = isset($_COOKIE['aqlx_ref']) ? sanitize_text_field(wp_unslash($_COOKIE['aqlx_ref'])) : '';
    return $ref ? '<span class="text-sm opacity-80">' . esc_html__('Referred by:', 'aqualuxe') . ' ' . esc_html($ref) . '</span>' : '';
});
