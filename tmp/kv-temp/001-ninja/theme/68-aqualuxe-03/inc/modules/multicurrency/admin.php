<?php
/**
 * Admin settings page for multicurrency module
 */
add_action('admin_menu', function() {
    add_options_page(
        __('Multicurrency Settings', 'aqualuxe'),
        __('Multicurrency', 'aqualuxe'),
        'manage_options',
        'aqualuxe-multicurrency',
        'aqualuxe_multicurrency_settings_page'
    );
});

function aqualuxe_multicurrency_settings_page() {
    if ( ! current_user_can('manage_options') ) return;
    if ( isset($_POST['aqualuxe_multicurrency_save']) && check_admin_referer('aqualuxe_multicurrency_save') ) {
        update_option('aqualuxe_multicurrency_currencies', wp_unslash($_POST['currencies']));
        update_option('aqualuxe_multicurrency_country_map', wp_unslash($_POST['country_map']));
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }
    $currencies = get_option('aqualuxe_multicurrency_currencies', "USD|$|1\nEUR|€|0.92\nGBP|£|0.78");
    $country_map = get_option('aqualuxe_multicurrency_country_map', "GB|GBP\nDE|EUR\nFR|EUR\nIT|EUR");
    ?>
    <div class="wrap">
        <h1><?php _e('Multicurrency Settings', 'aqualuxe'); ?></h1>
        <form method="post">
            <?php wp_nonce_field('aqualuxe_multicurrency_save'); ?>
            <h2><?php _e('Currencies', 'aqualuxe'); ?></h2>
            <p><?php _e('Format: CODE|SYMBOL|RATE per line (e.g. USD|$|1)', 'aqualuxe'); ?></p>
            <textarea name="currencies" rows="6" cols="60"><?php echo esc_textarea($currencies); ?></textarea>
            <h2><?php _e('Country to Currency Map', 'aqualuxe'); ?></h2>
            <p><?php _e('Format: COUNTRY_CODE|CURRENCY_CODE per line (e.g. GB|GBP)', 'aqualuxe'); ?></p>
            <textarea name="country_map" rows="6" cols="60"><?php echo esc_textarea($country_map); ?></textarea>
            <p><input type="submit" name="aqualuxe_multicurrency_save" class="button button-primary" value="<?php esc_attr_e('Save Changes', 'aqualuxe'); ?>"></p>
        </form>
    </div>
    <?php
}

// Filters to use admin settings
add_filter('aqualuxe_multicurrency_currencies', function($currencies) {
    $raw = get_option('aqualuxe_multicurrency_currencies');
    if (!$raw) return $currencies;
    $lines = explode("\n", $raw);
    $out = [];
    foreach ($lines as $line) {
        $parts = array_map('trim', explode('|', $line));
        if (count($parts) === 3) {
            $out[$parts[0]] = [ 'symbol' => $parts[1], 'rate' => (float)$parts[2] ];
        }
    }
    return $out ?: $currencies;
});
add_filter('aqualuxe_multicurrency_country_map', function($map) {
    $raw = get_option('aqualuxe_multicurrency_country_map');
    if (!$raw) return $map;
    $lines = explode("\n", $raw);
    $out = [];
    foreach ($lines as $line) {
        $parts = array_map('trim', explode('|', $line));
        if (count($parts) === 2) {
            $out[$parts[0]] = $parts[1];
        }
    }
    return $out ?: $map;
});
