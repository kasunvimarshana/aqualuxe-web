<?php
defined('ABSPATH') || exit;

// Store referral code from query into cookie
add_action('init', function () {
    if (empty($_GET['ref'])) return;
    $code = sanitize_text_field(wp_unslash($_GET['ref']));
    if (!preg_match('/^[A-Za-z0-9_-]{3,64}$/', $code)) return;
    $ttl = 60 * 60 * 24 * 30; // 30 days
    $path = defined('COOKIEPATH') && COOKIEPATH ? COOKIEPATH : '/';
    $domain = defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '';
    setcookie('aqlx_ref', $code, time() + $ttl, $path, $domain, function_exists('is_ssl') ? is_ssl() : false, true);
});

if (!function_exists('aqlx_get_referral_code')) {
    function aqlx_get_referral_code(): string
    {
        return isset($_COOKIE['aqlx_ref']) ? sanitize_text_field(wp_unslash($_COOKIE['aqlx_ref'])) : '';
    }
}

// Attach referral to WooCommerce order
add_action('woocommerce_checkout_create_order', function ($order) {
    if (!is_object($order)) return;
    $code = aqlx_get_referral_code();
    if ($code) {
        $order->update_meta_data('_aqlx_ref', $code);
    }
}, 10, 1);

// Simple admin report (Tools > AquaLuxe Referrals)
add_action('admin_menu', function () {
    add_management_page(
        __('AquaLuxe Referrals', 'aqualuxe'),
        __('Referrals', 'aqualuxe'),
        'manage_options',
        'aqlx-referrals',
        function () {
            if (!current_user_can('manage_options')) return;
            echo '<div class="wrap"><h1>' . esc_html__('AquaLuxe Referrals', 'aqualuxe') . '</h1>';
            if (!class_exists('WooCommerce')) {
                echo '<p>' . esc_html__('WooCommerce is not active.', 'aqualuxe') . '</p></div>';
                return;
            }
            // Aggregate last 200 orders by referral code
            $orders = wc_get_orders([
                'limit' => 200,
                'status' => array_keys(wc_get_order_statuses()),
                'meta_key' => '_aqlx_ref',
                'meta_compare' => 'EXISTS',
            ]);
            $agg = [];
            foreach ($orders as $order) {
                $code = (string) $order->get_meta('_aqlx_ref');
                if (!$code) continue;
                if (!isset($agg[$code])) $agg[$code] = ['count' => 0, 'revenue' => 0.0];
                $agg[$code]['count'] += 1;
                $agg[$code]['revenue'] += (float) $order->get_total();
            }
            if (empty($agg)) {
                echo '<p>' . esc_html__('No referral data yet.', 'aqualuxe') . '</p></div>';
                return;
            }
            echo '<table class="widefat striped"><thead><tr><th>' . esc_html__('Code', 'aqualuxe') . '</th><th>' . esc_html__('Orders', 'aqualuxe') . '</th><th>' . esc_html__('Revenue', 'aqualuxe') . '</th></tr></thead><tbody>';
            foreach ($agg as $code => $row) {
                echo '<tr><td>' . esc_html($code) . '</td><td>' . (int) $row['count'] . '</td><td>' . wc_price($row['revenue']) . '</td></tr>';
            }
            echo '</tbody></table></div>';
        }
    );
});
