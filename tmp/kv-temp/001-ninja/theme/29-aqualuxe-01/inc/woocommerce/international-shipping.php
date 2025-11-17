<?php
/**
 * AquaLuxe International Shipping Support
 *
 * @package AquaLuxe
 */

/**
 * Add international shipping support
 */
function aqualuxe_international_shipping_support() {
    // Check if international shipping is enabled
    if (!get_theme_mod('aqualuxe_enable_international_shipping', true)) {
        return;
    }
    
    // Add country selector to header
    function aqualuxe_country_selector() {
        // Get countries
        $countries = WC()->countries->get_countries();
        
        // Get current country
        $current_country = isset($_COOKIE['aqualuxe_country']) ? sanitize_text_field($_COOKIE['aqualuxe_country']) : WC()->countries->get_base_country();
        
        // Display country selector
        ?>
        <div class="aqualuxe-country-selector">
            <select id="aqualuxe-country-select" class="country-select">
                <?php foreach ($countries as $code => $country) : ?>
                    <option value="<?php echo esc_attr($code); ?>" <?php selected($current_country, $code); ?>>
                        <?php echo esc_html($country); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }
    add_action('aqualuxe_header_actions', 'aqualuxe_country_selector', 30);
    
    // Add country selector script
    function aqualuxe_country_selector_script() {
        // Check if international shipping is enabled
        if (!get_theme_mod('aqualuxe_enable_international_shipping', true)) {
            return;
        }
        
        // Enqueue script
        wp_enqueue_script('aqualuxe-country-selector', AQUALUXE_URI . '/assets/js/country-selector.js', array('jquery'), AQUALUXE_VERSION, true);
        
        // Localize script
        wp_localize_script('aqualuxe-country-selector', 'aqualuxeCountry', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-country-selector-nonce'),
            'countries' => WC()->countries->get_countries(),
        ));
    }
    add_action('wp_enqueue_scripts', 'aqualuxe_country_selector_script');
    
    // AJAX set country
    function aqualuxe_ajax_set_country() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-country-selector-nonce')) {
            wp_send_json_error('Invalid nonce');
        }
        
        // Get country
        $country = isset($_POST['country']) ? sanitize_text_field($_POST['country']) : WC()->countries->get_base_country();
        
        // Set cookie
        setcookie('aqualuxe_country', $country, time() + (86400 * 30), '/');
        
        // Send response
        wp_send_json_success(array(
            'country' => $country,
        ));
    }
    add_action('wp_ajax_aqualuxe_set_country', 'aqualuxe_ajax_set_country');
    add_action('wp_ajax_nopriv_aqualuxe_set_country', 'aqualuxe_ajax_set_country');
    
    // Set default country
    function aqualuxe_set_default_country($country) {
        // Get current country
        $current_country = isset($_COOKIE['aqualuxe_country']) ? sanitize_text_field($_COOKIE['aqualuxe_country']) : $country;
        
        return $current_country;
    }
    add_filter('woocommerce_customer_get_shipping_country', 'aqualuxe_set_default_country', 10, 1);
    add_filter('woocommerce_customer_get_billing_country', 'aqualuxe_set_default_country', 10, 1);
    
    // Add international shipping rates
    function aqualuxe_international_shipping_rates($rates, $package) {
        // Get current country
        $current_country = isset($_COOKIE['aqualuxe_country']) ? sanitize_text_field($_COOKIE['aqualuxe_country']) : WC()->countries->get_base_country();
        
        // Get base country
        $base_country = WC()->countries->get_base_country();
        
        // Check if international shipping
        if ($current_country !== $base_country) {
            // Get shipping zones
            $shipping_zones = WC_Shipping_Zones::get_zones();
            $found_zone = false;
            
            // Check if country is in a shipping zone
            foreach ($shipping_zones as $zone_id => $zone) {
                $zone_obj = new WC_Shipping_Zone($zone_id);
                $zone_locations = $zone_obj->get_zone_locations();
                
                foreach ($zone_locations as $location) {
                    if ($location->type === 'country' && $location->code === $current_country) {
                        $found_zone = true;
                        break 2;
                    }
                }
            }
            
            // If country is not in a shipping zone, add international shipping rate
            if (!$found_zone) {
                // Get shipping regions
                $shipping_regions = get_option('aqualuxe_shipping_regions', array(
                    'europe' => array(
                        'name' => __('Europe', 'aqualuxe'),
                        'countries' => array('GB', 'FR', 'DE', 'IT', 'ES', 'NL', 'BE', 'AT', 'CH', 'SE', 'DK', 'NO', 'FI', 'PT', 'IE', 'GR', 'PL'),
                        'rate' => 15,
                    ),
                    'north_america' => array(
                        'name' => __('North America', 'aqualuxe'),
                        'countries' => array('US', 'CA', 'MX'),
                        'rate' => 25,
                    ),
                    'asia_pacific' => array(
                        'name' => __('Asia Pacific', 'aqualuxe'),
                        'countries' => array('JP', 'CN', 'KR', 'AU', 'NZ', 'SG', 'HK', 'TW', 'MY', 'TH', 'VN', 'ID', 'PH'),
                        'rate' => 35,
                    ),
                    'rest_of_world' => array(
                        'name' => __('Rest of World', 'aqualuxe'),
                        'countries' => array(),
                        'rate' => 45,
                    ),
                ));
                
                // Find shipping region for country
                $shipping_rate = $shipping_regions['rest_of_world']['rate'];
                $shipping_region_name = $shipping_regions['rest_of_world']['name'];
                
                foreach ($shipping_regions as $region_id => $region) {
                    if (in_array($current_country, $region['countries'])) {
                        $shipping_rate = $region['rate'];
                        $shipping_region_name = $region['name'];
                        break;
                    }
                }
                
                // Add international shipping rate
                $rates['international_shipping'] = new WC_Shipping_Rate(
                    'international_shipping',
                    sprintf(__('International Shipping (%s)', 'aqualuxe'), $shipping_region_name),
                    $shipping_rate,
                    array(),
                    'international_shipping'
                );
            }
        }
        
        return $rates;
    }
    add_filter('woocommerce_package_rates', 'aqualuxe_international_shipping_rates', 10, 2);
    
    // Add shipping settings to customizer
    function aqualuxe_shipping_customizer_settings($wp_customize) {
        // Add shipping settings section
        $wp_customize->add_section('aqualuxe_shipping_settings', array(
            'title' => __('Shipping Settings', 'aqualuxe'),
            'description' => __('Configure international shipping settings for your store.', 'aqualuxe'),
            'panel' => 'aqualuxe_woocommerce',
            'priority' => 40,
        ));
        
        // Add shipping settings
        $wp_customize->add_setting('aqualuxe_enable_international_shipping', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));
        
        $wp_customize->add_control('aqualuxe_enable_international_shipping', array(
            'label' => __('Enable International Shipping', 'aqualuxe'),
            'description' => __('Allow customers to select their country and see appropriate shipping rates.', 'aqualuxe'),
            'section' => 'aqualuxe_shipping_settings',
            'type' => 'checkbox',
        ));
        
        // Add shipping display setting
        $wp_customize->add_setting('aqualuxe_shipping_display', array(
            'default' => 'dropdown',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        ));
        
        $wp_customize->add_control('aqualuxe_shipping_display', array(
            'label' => __('Country Selector Display', 'aqualuxe'),
            'description' => __('Choose how to display the country selector.', 'aqualuxe'),
            'section' => 'aqualuxe_shipping_settings',
            'type' => 'select',
            'choices' => array(
                'dropdown' => __('Dropdown', 'aqualuxe'),
                'flags' => __('Flags', 'aqualuxe'),
                'both' => __('Flags and Dropdown', 'aqualuxe'),
            ),
        ));
        
        // Add shipping position setting
        $wp_customize->add_setting('aqualuxe_shipping_position', array(
            'default' => 'header',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        ));
        
        $wp_customize->add_control('aqualuxe_shipping_position', array(
            'label' => __('Country Selector Position', 'aqualuxe'),
            'description' => __('Choose where to display the country selector.', 'aqualuxe'),
            'section' => 'aqualuxe_shipping_settings',
            'type' => 'select',
            'choices' => array(
                'header' => __('Header', 'aqualuxe'),
                'footer' => __('Footer', 'aqualuxe'),
                'both' => __('Both Header and Footer', 'aqualuxe'),
            ),
        ));
    }
    add_action('customize_register', 'aqualuxe_shipping_customizer_settings');
    
    // Add shipping settings to admin menu
    function aqualuxe_shipping_admin_menu() {
        add_submenu_page(
            'woocommerce',
            __('International Shipping', 'aqualuxe'),
            __('International Shipping', 'aqualuxe'),
            'manage_options',
            'aqualuxe-shipping-settings',
            'aqualuxe_shipping_settings_page'
        );
    }
    add_action('admin_menu', 'aqualuxe_shipping_admin_menu');
    
    // Shipping settings page
    function aqualuxe_shipping_settings_page() {
        // Save settings
        if (isset($_POST['aqualuxe_save_shipping_settings']) && check_admin_referer('aqualuxe_shipping_settings')) {
            // Get shipping regions
            $shipping_regions = array();
            
            foreach ($_POST['region_id'] as $key => $id) {
                if (empty($id)) {
                    continue;
                }
                
                $shipping_regions[$id] = array(
                    'name' => sanitize_text_field($_POST['region_name'][$key]),
                    'countries' => isset($_POST['region_countries'][$key]) ? array_map('sanitize_text_field', $_POST['region_countries'][$key]) : array(),
                    'rate' => (float) $_POST['region_rate'][$key],
                );
            }
            
            // Save shipping regions
            update_option('aqualuxe_shipping_regions', $shipping_regions);
            
            // Show success message
            echo '<div class="notice notice-success"><p>' . esc_html__('Shipping settings saved.', 'aqualuxe') . '</p></div>';
        }
        
        // Get shipping regions
        $shipping_regions = get_option('aqualuxe_shipping_regions', array(
            'europe' => array(
                'name' => __('Europe', 'aqualuxe'),
                'countries' => array('GB', 'FR', 'DE', 'IT', 'ES', 'NL', 'BE', 'AT', 'CH', 'SE', 'DK', 'NO', 'FI', 'PT', 'IE', 'GR', 'PL'),
                'rate' => 15,
            ),
            'north_america' => array(
                'name' => __('North America', 'aqualuxe'),
                'countries' => array('US', 'CA', 'MX'),
                'rate' => 25,
            ),
            'asia_pacific' => array(
                'name' => __('Asia Pacific', 'aqualuxe'),
                'countries' => array('JP', 'CN', 'KR', 'AU', 'NZ', 'SG', 'HK', 'TW', 'MY', 'TH', 'VN', 'ID', 'PH'),
                'rate' => 35,
            ),
            'rest_of_world' => array(
                'name' => __('Rest of World', 'aqualuxe'),
                'countries' => array(),
                'rate' => 45,
            ),
        ));
        
        // Get countries
        $countries = WC()->countries->get_countries();
        
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('International Shipping Settings', 'aqualuxe'); ?></h1>
            
            <form method="post" action="">
                <?php wp_nonce_field('aqualuxe_shipping_settings'); ?>
                
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><?php esc_html_e('Base Country', 'aqualuxe'); ?></th>
                            <td>
                                <p><?php echo esc_html(WC()->countries->get_countries()[WC()->countries->get_base_country()]); ?> (<?php echo esc_html(WC()->countries->get_base_country()); ?>)</p>
                                <p class="description"><?php esc_html_e('This is your base country set in WooCommerce settings.', 'aqualuxe'); ?></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <h2><?php esc_html_e('Shipping Regions', 'aqualuxe'); ?></h2>
                <p><?php esc_html_e('Define shipping regions and rates for international shipping. These rates will be used when a country is not covered by your WooCommerce shipping zones.', 'aqualuxe'); ?></p>
                
                <table class="widefat" id="aqualuxe-shipping-regions-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Region ID', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Region Name', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Countries', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Shipping Rate', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Actions', 'aqualuxe'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($shipping_regions as $id => $region) : ?>
                            <tr>
                                <td>
                                    <input type="text" name="region_id[]" value="<?php echo esc_attr($id); ?>" required>
                                </td>
                                <td>
                                    <input type="text" name="region_name[]" value="<?php echo esc_attr($region['name']); ?>" required>
                                </td>
                                <td>
                                    <select name="region_countries[<?php echo esc_attr(count($shipping_regions) - 1); ?>][]" class="aqualuxe-country-select" multiple style="width: 100%; height: 150px;">
                                        <?php foreach ($countries as $code => $country) : ?>
                                            <option value="<?php echo esc_attr($code); ?>" <?php selected(in_array($code, $region['countries']), true); ?>>
                                                <?php echo esc_html($country); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="region_rate[]" value="<?php echo esc_attr($region['rate']); ?>" step="0.01" min="0" required>
                                </td>
                                <td>
                                    <button type="button" class="button remove-region" <?php echo ($id === 'rest_of_world') ? 'disabled' : ''; ?>><?php esc_html_e('Remove', 'aqualuxe'); ?></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <button type="button" class="button add-region"><?php esc_html_e('Add Region', 'aqualuxe'); ?></button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                
                <p class="submit">
                    <input type="submit" name="aqualuxe_save_shipping_settings" class="button button-primary" value="<?php esc_attr_e('Save Settings', 'aqualuxe'); ?>">
                </p>
            </form>
        </div>
        
        <script>
            jQuery(document).ready(function($) {
                // Add region
                $('.add-region').on('click', function() {
                    var index = $('#aqualuxe-shipping-regions-table tbody tr').length;
                    var countryOptions = '';
                    
                    <?php foreach ($countries as $code => $country) : ?>
                        countryOptions += '<option value="<?php echo esc_attr($code); ?>"><?php echo esc_html($country); ?></option>';
                    <?php endforeach; ?>
                    
                    var row = '<tr>' +
                        '<td><input type="text" name="region_id[]" required></td>' +
                        '<td><input type="text" name="region_name[]" required></td>' +
                        '<td><select name="region_countries[' + index + '][]" class="aqualuxe-country-select" multiple style="width: 100%; height: 150px;">' + countryOptions + '</select></td>' +
                        '<td><input type="number" name="region_rate[]" step="0.01" min="0" required></td>' +
                        '<td><button type="button" class="button remove-region"><?php esc_html_e('Remove', 'aqualuxe'); ?></button></td>' +
                        '</tr>';
                    
                    $('#aqualuxe-shipping-regions-table tbody').append(row);
                });
                
                // Remove region
                $(document).on('click', '.remove-region', function() {
                    $(this).closest('tr').remove();
                });
            });
        </script>
        <?php
    }
    
    // Add checkout optimization
    function aqualuxe_optimize_checkout() {
        // Add express checkout option
        function aqualuxe_express_checkout() {
            // Check if on checkout page
            if (!is_checkout()) {
                return;
            }
            
            // Display express checkout option
            ?>
            <div class="aqualuxe-express-checkout">
                <h3><?php esc_html_e('Express Checkout', 'aqualuxe'); ?></h3>
                <div class="aqualuxe-express-checkout-buttons">
                    <?php do_action('aqualuxe_express_checkout_buttons'); ?>
                </div>
            </div>
            <?php
        }
        add_action('woocommerce_before_checkout_form', 'aqualuxe_express_checkout', 5);
        
        // Add PayPal express checkout button
        function aqualuxe_paypal_express_checkout_button() {
            // Check if PayPal is enabled
            if (!get_theme_mod('aqualuxe_enable_paypal_express', true)) {
                return;
            }
            
            // Display PayPal button
            ?>
            <div class="aqualuxe-paypal-button">
                <button type="button" class="button paypal-button">
                    <span class="paypal-button-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M9.93 11.22h3.57c1.67 0 2.92-.42 3.75-1.27.83-.85 1.25-2.04 1.25-3.57 0-1.54-.42-2.73-1.25-3.57-.83-.85-2.08-1.27-3.75-1.27H6.82L3.27 20.73h3.64l1.27-5.46h1.75zm1.69-7.2h1.88c.83 0 1.46.21 1.88.63.42.42.63 1.04.63 1.88 0 .83-.21 1.46-.63 1.88-.42.42-1.04.63-1.88.63h-1.88l.83-5.01z" fill="#003087"/>
                            <path d="M20.73 9.93c-.83-.85-2.08-1.27-3.75-1.27h-3.57c-1.67 0-2.92.42-3.75 1.27-.83.85-1.25 2.04-1.25 3.57 0 1.54.42 2.73 1.25 3.57.83.85 2.08 1.27 3.75 1.27h3.57c1.67 0 2.92-.42 3.75-1.27.83-.85 1.25-2.04 1.25-3.57 0-1.54-.42-2.73-1.25-3.57zm-2.5 5.46c-.42.42-1.04.63-1.88.63h-1.88l-.83-5.01h1.88c.83 0 1.46.21 1.88.63.42.42.63 1.04.63 1.88 0 .83-.21 1.46-.63 1.88z" fill="#0070E0"/>
                        </svg>
                    </span>
                    <span class="paypal-button-text"><?php esc_html_e('PayPal Checkout', 'aqualuxe'); ?></span>
                </button>
            </div>
            <?php
        }
        add_action('aqualuxe_express_checkout_buttons', 'aqualuxe_paypal_express_checkout_button', 10);
        
        // Add Apple Pay express checkout button
        function aqualuxe_apple_pay_express_checkout_button() {
            // Check if Apple Pay is enabled
            if (!get_theme_mod('aqualuxe_enable_apple_pay', true)) {
                return;
            }
            
            // Display Apple Pay button
            ?>
            <div class="aqualuxe-apple-pay-button">
                <button type="button" class="button apple-pay-button">
                    <span class="apple-pay-button-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M17.72 7.22c-.91 0-1.67.3-2.3.89-.63.59-1.04 1.37-1.23 2.33-.19.96-.04 1.74.44 2.33.48.59 1.19.89 2.11.89.91 0 1.67-.3 2.3-.89.63-.59 1.04-1.37 1.23-2.33.19-.96.04-1.74-.44-2.33-.48-.59-1.19-.89-2.11-.89zM12 4c-4.42 0-8 3.58-8 8s3.58 8 8 8 8-3.58 8-8-3.58-8-8-8zm5.72 11.72c-.91 0-1.67-.3-2.3-.89-.63-.59-1.04-1.37-1.23-2.33-.19-.96-.04-1.74.44-2.33.48-.59 1.19-.89 2.11-.89.91 0 1.67.3 2.3.89.63.59 1.04 1.37 1.23 2.33.19.96.04 1.74-.44 2.33-.48.59-1.19.89-2.11.89z" fill="#000"/>
                        </svg>
                    </span>
                    <span class="apple-pay-button-text"><?php esc_html_e('Apple Pay', 'aqualuxe'); ?></span>
                </button>
            </div>
            <?php
        }
        add_action('aqualuxe_express_checkout_buttons', 'aqualuxe_apple_pay_express_checkout_button', 20);
        
        // Add Google Pay express checkout button
        function aqualuxe_google_pay_express_checkout_button() {
            // Check if Google Pay is enabled
            if (!get_theme_mod('aqualuxe_enable_google_pay', true)) {
                return;
            }
            
            // Display Google Pay button
            ?>
            <div class="aqualuxe-google-pay-button">
                <button type="button" class="button google-pay-button">
                    <span class="google-pay-button-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M12 4c-4.42 0-8 3.58-8 8s3.58 8 8 8 8-3.58 8-8-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z" fill="#4285F4"/>
                            <path d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 6c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z" fill="#34A853"/>
                            <path d="M12 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 3c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1z" fill="#FBBC05"/>
                            <path d="M12 11c-.55 0-1 .45-1 1s.45 1 1 1 1-.45 1-1-.45-1-1-1z" fill="#EA4335"/>
                        </svg>
                    </span>
                    <span class="google-pay-button-text"><?php esc_html_e('Google Pay', 'aqualuxe'); ?></span>
                </button>
            </div>
            <?php
        }
        add_action('aqualuxe_express_checkout_buttons', 'aqualuxe_google_pay_express_checkout_button', 30);
        
        // Add checkout steps
        function aqualuxe_checkout_steps() {
            // Check if on checkout page
            if (!is_checkout()) {
                return;
            }
            
            // Get current step
            $step = isset($_GET['step']) ? absint($_GET['step']) : 1;
            
            // Display checkout steps
            ?>
            <div class="aqualuxe-checkout-steps">
                <div class="aqualuxe-checkout-step <?php echo $step === 1 ? 'active' : ($step > 1 ? 'completed' : ''); ?>" data-step="1">
                    <span class="step-number">1</span>
                    <span class="step-title"><?php esc_html_e('Customer Information', 'aqualuxe'); ?></span>
                </div>
                <div class="aqualuxe-checkout-step <?php echo $step === 2 ? 'active' : ($step > 2 ? 'completed' : ''); ?>" data-step="2">
                    <span class="step-number">2</span>
                    <span class="step-title"><?php esc_html_e('Shipping', 'aqualuxe'); ?></span>
                </div>
                <div class="aqualuxe-checkout-step <?php echo $step === 3 ? 'active' : ($step > 3 ? 'completed' : ''); ?>" data-step="3">
                    <span class="step-number">3</span>
                    <span class="step-title"><?php esc_html_e('Payment', 'aqualuxe'); ?></span>
                </div>
                <div class="aqualuxe-checkout-step <?php echo $step === 4 ? 'active' : ''; ?>" data-step="4">
                    <span class="step-number">4</span>
                    <span class="step-title"><?php esc_html_e('Review', 'aqualuxe'); ?></span>
                </div>
            </div>
            <?php
        }
        add_action('woocommerce_before_checkout_form', 'aqualuxe_checkout_steps', 10);
        
        // Add checkout steps script
        function aqualuxe_checkout_steps_script() {
            // Check if on checkout page
            if (!is_checkout()) {
                return;
            }
            
            // Enqueue script
            wp_enqueue_script('aqualuxe-checkout-steps', AQUALUXE_URI . '/assets/js/checkout-steps.js', array('jquery'), AQUALUXE_VERSION, true);
            
            // Localize script
            wp_localize_script('aqualuxe-checkout-steps', 'aqualuxeCheckout', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe-checkout-steps-nonce'),
                'i18n' => array(
                    'next' => __('Next', 'aqualuxe'),
                    'previous' => __('Previous', 'aqualuxe'),
                    'continue_to_shipping' => __('Continue to Shipping', 'aqualuxe'),
                    'continue_to_payment' => __('Continue to Payment', 'aqualuxe'),
                    'continue_to_review' => __('Review Order', 'aqualuxe'),
                    'place_order' => __('Place Order', 'aqualuxe'),
                ),
            ));
        }
        add_action('wp_enqueue_scripts', 'aqualuxe_checkout_steps_script');
        
        // AJAX update checkout step
        function aqualuxe_ajax_update_checkout_step() {
            // Check nonce
            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-checkout-steps-nonce')) {
                wp_send_json_error('Invalid nonce');
            }
            
            // Get step
            $step = isset($_POST['step']) ? absint($_POST['step']) : 1;
            
            // Send response
            wp_send_json_success(array(
                'step' => $step,
            ));
        }
        add_action('wp_ajax_aqualuxe_update_checkout_step', 'aqualuxe_ajax_update_checkout_step');
        add_action('wp_ajax_nopriv_aqualuxe_update_checkout_step', 'aqualuxe_ajax_update_checkout_step');
        
        // Add checkout optimization settings to customizer
        function aqualuxe_checkout_customizer_settings($wp_customize) {
            // Add checkout settings section
            $wp_customize->add_section('aqualuxe_checkout_settings', array(
                'title' => __('Checkout Settings', 'aqualuxe'),
                'description' => __('Configure checkout settings for your store.', 'aqualuxe'),
                'panel' => 'aqualuxe_woocommerce',
                'priority' => 50,
            ));
            
            // Add checkout settings
            $wp_customize->add_setting('aqualuxe_enable_express_checkout', array(
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ));
            
            $wp_customize->add_control('aqualuxe_enable_express_checkout', array(
                'label' => __('Enable Express Checkout', 'aqualuxe'),
                'description' => __('Show express checkout options at the top of the checkout page.', 'aqualuxe'),
                'section' => 'aqualuxe_checkout_settings',
                'type' => 'checkbox',
            ));
            
            // Add PayPal express setting
            $wp_customize->add_setting('aqualuxe_enable_paypal_express', array(
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ));
            
            $wp_customize->add_control('aqualuxe_enable_paypal_express', array(
                'label' => __('Enable PayPal Express', 'aqualuxe'),
                'description' => __('Show PayPal express checkout button.', 'aqualuxe'),
                'section' => 'aqualuxe_checkout_settings',
                'type' => 'checkbox',
            ));
            
            // Add Apple Pay setting
            $wp_customize->add_setting('aqualuxe_enable_apple_pay', array(
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ));
            
            $wp_customize->add_control('aqualuxe_enable_apple_pay', array(
                'label' => __('Enable Apple Pay', 'aqualuxe'),
                'description' => __('Show Apple Pay express checkout button.', 'aqualuxe'),
                'section' => 'aqualuxe_checkout_settings',
                'type' => 'checkbox',
            ));
            
            // Add Google Pay setting
            $wp_customize->add_setting('aqualuxe_enable_google_pay', array(
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ));
            
            $wp_customize->add_control('aqualuxe_enable_google_pay', array(
                'label' => __('Enable Google Pay', 'aqualuxe'),
                'description' => __('Show Google Pay express checkout button.', 'aqualuxe'),
                'section' => 'aqualuxe_checkout_settings',
                'type' => 'checkbox',
            ));
            
            // Add checkout steps setting
            $wp_customize->add_setting('aqualuxe_enable_checkout_steps', array(
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ));
            
            $wp_customize->add_control('aqualuxe_enable_checkout_steps', array(
                'label' => __('Enable Checkout Steps', 'aqualuxe'),
                'description' => __('Show checkout steps at the top of the checkout page.', 'aqualuxe'),
                'section' => 'aqualuxe_checkout_settings',
                'type' => 'checkbox',
            ));
            
            // Add one-page checkout setting
            $wp_customize->add_setting('aqualuxe_enable_one_page_checkout', array(
                'default' => false,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ));
            
            $wp_customize->add_control('aqualuxe_enable_one_page_checkout', array(
                'label' => __('Enable One-Page Checkout', 'aqualuxe'),
                'description' => __('Show all checkout fields on a single page instead of steps.', 'aqualuxe'),
                'section' => 'aqualuxe_checkout_settings',
                'type' => 'checkbox',
            ));
        }
        add_action('customize_register', 'aqualuxe_checkout_customizer_settings');
    }
    add_action('init', 'aqualuxe_optimize_checkout');
}
add_action('init', 'aqualuxe_international_shipping_support');