<?php
/**
 * Admin settings for international shipping optimization
 */
add_action('admin_menu', function() {
    add_options_page(
        __('Shipping Optimization', 'aqualuxe'),
        __('Shipping Optimization', 'aqualuxe'),
        'manage_options',
        'aqualuxe-shipping',
        'aqualuxe_shipping_settings_page'
    );
});

function aqualuxe_shipping_settings_page() {
    if ( ! current_user_can('manage_options') ) return;
    if ( isset($_POST['aqualuxe_shipping_save']) && check_admin_referer('aqualuxe_shipping_save') ) {
        update_option('aqualuxe_shipping_estimates', wp_unslash($_POST['estimates']));
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }
    $estimates = get_option('aqualuxe_shipping_estimates', "flat_rate|5-10 business days\nfree_shipping|7-14 business days\nlocal_pickup|Same day");
    ?>
    <div class="wrap">
        <h1><?php _e('Shipping Optimization', 'aqualuxe'); ?></h1>
        <form method="post">
            <?php wp_nonce_field('aqualuxe_shipping_save'); ?>
            <h2><?php _e('Estimated Delivery Times', 'aqualuxe'); ?></h2>
            <p><?php _e('Format: METHOD_ID|ESTIMATE per line (e.g. flat_rate|5-10 business days)', 'aqualuxe'); ?></p>
            <textarea name="estimates" rows="6" cols="60"><?php echo esc_textarea($estimates); ?></textarea>
            <p><input type="submit" name="aqualuxe_shipping_save" class="button button-primary" value="<?php esc_attr_e('Save Changes', 'aqualuxe'); ?>"></p>
        </form>
    </div>
    <?php
}

// Filter to use admin settings for shipping estimates
add_filter('aqualuxe_shipping_estimates', function($estimates) {
    $raw = get_option('aqualuxe_shipping_estimates');
    if (!$raw) return $estimates;
    $lines = explode("\n", $raw);
    $out = [];
    foreach ($lines as $line) {
        $parts = array_map('trim', explode('|', $line));
        if (count($parts) === 2) {
            $out[$parts[0]] = $parts[1];
        }
    }
    return $out ?: $estimates;
});
