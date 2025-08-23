<?php
/**
 * Multicurrency live rates sync (Open Exchange Rates, fixer.io, or similar)
 * Requires free/paid API key. Example uses fixer.io (https://fixer.io/)
 */
add_action('aqualuxe_multicurrency_sync_rates', function() {
    $api_key = get_option('aqualuxe_multicurrency_api_key');
    if (!$api_key) return;
    $currencies = apply_filters('aqualuxe_multicurrency_currencies', []);
    $base = 'USD';
    $symbols = implode(',', array_keys($currencies));
    $url = 'https://data.fixer.io/api/latest?access_key=' . urlencode($api_key) . '&base=' . $base . '&symbols=' . urlencode($symbols);
    $response = wp_remote_get($url, ['timeout' => 10]);
    if (is_wp_error($response)) return;
    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (empty($data['success']) || empty($data['rates'])) return;
    $lines = [];
    foreach ($currencies as $code => $info) {
        $rate = isset($data['rates'][$code]) ? (float)$data['rates'][$code] : $info['rate'];
        $lines[] = $code . '|' . $info['symbol'] . '|' . $rate;
    }
    update_option('aqualuxe_multicurrency_currencies', implode("\n", $lines));
});

// Manual sync button in admin
add_action('admin_notices', function() {
    if (isset($_GET['aqualuxe_sync_rates']) && current_user_can('manage_options')) {
        do_action('aqualuxe_multicurrency_sync_rates');
        echo '<div class="updated"><p>Rates synced from API.</p></div>';
    }
    if (get_current_screen() && get_current_screen()->id === 'settings_page_aqualuxe-multicurrency') {
        echo '<div class="notice notice-info"><p><a href="' . esc_url(add_query_arg('aqualuxe_sync_rates', 1)) . '" class="button">Sync Live Rates</a></p></div>';
    }
});

// API key field in admin
add_action('admin_init', function() {
    register_setting('general', 'aqualuxe_multicurrency_api_key');
    add_settings_field('aqualuxe_multicurrency_api_key', 'Multicurrency API Key', function() {
        echo '<input type="text" name="aqualuxe_multicurrency_api_key" value="' . esc_attr(get_option('aqualuxe_multicurrency_api_key')) . '" class="regular-text">';
        echo '<p class="description">Enter your fixer.io or compatible API key for live rates.</p>';
    }, 'general');
});
