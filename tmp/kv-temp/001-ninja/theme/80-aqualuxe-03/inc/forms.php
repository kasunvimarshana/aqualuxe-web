<?php
/** Simple newsletter form handler (no third-party, stores locally) */

if (!defined('ABSPATH')) { exit; }

add_action('template_redirect', function(){
    if (empty($_POST['aqlx_newsletter'])) return; // not our form

    // CSRF
    if (empty($_POST['_aqualuxe_nonce']) || !wp_verify_nonce($_POST['_aqualuxe_nonce'], 'newsletter')) {
        aqlx_newsletter_redirect('err');
    }

    // Honeypot
    if (!empty($_POST['aqlx_company'])) {
        aqlx_newsletter_redirect('ok'); // silently accept bots
    }

    // Rate limit by IP (60s)
    $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '0.0.0.0';
    $key = 'aqlx_nl_' . md5($ip);
    if (get_transient($key)) {
        aqlx_newsletter_redirect('slow');
    }
    set_transient($key, 1, MINUTE_IN_SECONDS);

    // Validate email
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    if (!$email || !is_email($email)) {
        aqlx_newsletter_redirect('err');
    }

    // Consent is required
    $consent = !empty($_POST['consent']);
    if (!$consent) {
        aqlx_newsletter_redirect('consent');
    }

    // Store locally (append unique)
    $list = get_option('aqlx_newsletters', []);
    if (!is_array($list)) $list = [];
    $exists = false;
    foreach ($list as $row) { if (!empty($row['email']) && strtolower($row['email']) === strtolower($email)) { $exists = true; break; } }
    if (!$exists) {
        $ip_hash = defined('NONCE_SALT') ? hash_hmac('sha256', $ip, NONCE_SALT) : md5($ip);
    $list[] = ['email' => $email, 'time' => time(), 'ip' => $ip_hash, 'consent' => true];
        update_option('aqlx_newsletters', $list, false);
    }

    aqlx_newsletter_redirect('ok');
});

function aqlx_newsletter_redirect(string $code) {
    $ref = wp_get_referer();
    if (!$ref) $ref = home_url('/');
    wp_safe_redirect(add_query_arg('nl', $code, $ref));
    exit;
}
