<?php
/** Affiliate module: referral tracking via query param, stores cookie */
if (!defined('ABSPATH')) { exit; }

add_action('init', function(){
    if (isset($_GET['ref'])) {
        $ref = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $_GET['ref']);
        if ($ref !== '') {
            setcookie('ax_ref', $ref, time()+3600*24*30, COOKIEPATH ?: '/', COOKIE_DOMAIN ?: '', is_ssl(), true);
            $_COOKIE['ax_ref'] = $ref;
        }
    }
});

add_action('wp_footer', function(){
    if (empty($_COOKIE['ax_ref'])) return;
    echo '<div class="fixed bottom-2 left-2 text-xs bg-slate-900/80 text-white px-2 py-1 rounded" role="status" aria-live="polite">'.esc_html__('Referred by: ','aqualuxe').esc_html($_COOKIE['ax_ref']).'</div>';
});
