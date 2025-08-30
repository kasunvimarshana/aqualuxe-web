<?php
/**
 * AquaLuxe Multi-Currency Support
 *
 * @package AquaLuxe
 */

/**
 * Add multi-currency support
 */
function aqualuxe_multi_currency_support() {
    // Check if multi-currency is enabled
    if (!get_theme_mod('aqualuxe_enable_multi_currency', true)) {
        return;
    }
    
    // Add currency switcher to header
    function aqualuxe_currency_switcher() {
        // Get currencies
        $currencies = get_option('aqualuxe_currencies', array(
            'USD' => array(
                'symbol' => '$',
                'rate' => 1,
                'name' => 'US Dollar',
            ),
            'EUR' => array(
                'symbol' => '€',
                'rate' => 0.85,
                'name' => 'Euro',
            ),
            'GBP' => array(
                'symbol' => '£',
                'rate' => 0.75,
                'name' => 'British Pound',
            ),
            'CAD' => array(
                'symbol' => 'CA$',
                'rate' => 1.25,
                'name' => 'Canadian Dollar',
            ),
            'AUD' => array(
                'symbol' => 'A$',
                'rate' => 1.35,
                'name' => 'Australian Dollar',
            ),
            'JPY' => array(
                'symbol' => '¥',
                'rate' => 110,
                'name' => 'Japanese Yen',
            ),
        ));
        
        // Get current currency
        $current_currency = isset($_COOKIE['aqualuxe_currency']) ? sanitize_text_field($_COOKIE['aqualuxe_currency']) : 'USD';
        
        // Display currency switcher
        ?>
        <div class="aqualuxe-currency-switcher">
            <select id="aqualuxe-currency-select" class="currency-select">
                <?php foreach ($currencies as $code => $currency) : ?>
                    <option value="<?php echo esc_attr($code); ?>" <?php selected($current_currency, $code); ?>>
                        <?php echo esc_html($currency['symbol'] . ' ' . $code); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }
    add_action('aqualuxe_header_actions', 'aqualuxe_currency_switcher', 20);
    
    // Add currency switcher script
    function aqualuxe_currency_switcher_script() {
        // Check if multi-currency is enabled
        if (!get_theme_mod('aqualuxe_enable_multi_currency', true)) {
            return;
        }
        
        // Get currencies
        $currencies = get_option('aqualuxe_currencies', array(
            'USD' => array(
                'symbol' => '$',
                'rate' => 1,
                'name' => 'US Dollar',
            ),
            'EUR' => array(
                'symbol' => '€',
                'rate' => 0.85,
                'name' => 'Euro',
            ),
            'GBP' => array(
                'symbol' => '£',
                'rate' => 0.75,
                'name' => 'British Pound',
            ),
            'CAD' => array(
                'symbol' => 'CA$',
                'rate' => 1.25,
                'name' => 'Canadian Dollar',
            ),
            'AUD' => array(
                'symbol' => 'A$',
                'rate' => 1.35,
                'name' => 'Australian Dollar',
            ),
            'JPY' => array(
                'symbol' => '¥',
                'rate' => 110,
                'name' => 'Japanese Yen',
            ),
        ));
        
        // Enqueue script
        wp_enqueue_script('aqualuxe-currency-switcher', AQUALUXE_URI . '/assets/js/currency-switcher.js', array('jquery'), AQUALUXE_VERSION, true);
        
        // Localize script
        wp_localize_script('aqualuxe-currency-switcher', 'aqualuxeCurrency', array(
            'currencies' => $currencies,
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-currency-switcher-nonce'),
        ));
    }
    add_action('wp_enqueue_scripts', 'aqualuxe_currency_switcher_script');
    
    // AJAX set currency
    function aqualuxe_ajax_set_currency() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-currency-switcher-nonce')) {
            wp_send_json_error('Invalid nonce');
        }
        
        // Get currency
        $currency = isset($_POST['currency']) ? sanitize_text_field($_POST['currency']) : 'USD';
        
        // Set cookie
        setcookie('aqualuxe_currency', $currency, time() + (86400 * 30), '/');
        
        // Send response
        wp_send_json_success(array(
            'currency' => $currency,
        ));
    }
    add_action('wp_ajax_aqualuxe_set_currency', 'aqualuxe_ajax_set_currency');
    add_action('wp_ajax_nopriv_aqualuxe_set_currency', 'aqualuxe_ajax_set_currency');
    
    // Filter product prices
    function aqualuxe_filter_product_price($price, $product) {
        // Get current currency
        $current_currency = isset($_COOKIE['aqualuxe_currency']) ? sanitize_text_field($_COOKIE['aqualuxe_currency']) : 'USD';
        
        // Get currencies
        $currencies = get_option('aqualuxe_currencies', array(
            'USD' => array(
                'symbol' => '$',
                'rate' => 1,
                'name' => 'US Dollar',
            ),
            'EUR' => array(
                'symbol' => '€',
                'rate' => 0.85,
                'name' => 'Euro',
            ),
            'GBP' => array(
                'symbol' => '£',
                'rate' => 0.75,
                'name' => 'British Pound',
            ),
            'CAD' => array(
                'symbol' => 'CA$',
                'rate' => 1.25,
                'name' => 'Canadian Dollar',
            ),
            'AUD' => array(
                'symbol' => 'A$',
                'rate' => 1.35,
                'name' => 'Australian Dollar',
            ),
            'JPY' => array(
                'symbol' => '¥',
                'rate' => 110,
                'name' => 'Japanese Yen',
            ),
        ));
        
        // Check if currency exists
        if (!isset($currencies[$current_currency])) {
            return $price;
        }
        
        // Get currency rate
        $rate = $currencies[$current_currency]['rate'];
        
        // Get currency symbol
        $symbol = $currencies[$current_currency]['symbol'];
        
        // Get price
        $price_value = $product->get_price();
        
        // Convert price
        $converted_price = $price_value * $rate;
        
        // Format price
        $formatted_price = wc_price($converted_price, array(
            'currency' => $current_currency,
        ));
        
        // Add data attribute with original price
        $formatted_price = str_replace('<span', '<span data-original-price="' . esc_attr($price_value) . '"', $formatted_price);
        
        return $formatted_price;
    }
    add_filter('woocommerce_get_price_html', 'aqualuxe_filter_product_price', 10, 2);
    
    // Add currency settings to customizer
    function aqualuxe_currency_customizer_settings($wp_customize) {
        // Add currency settings section
        $wp_customize->add_section('aqualuxe_currency_settings', array(
            'title' => __('Currency Settings', 'aqualuxe'),
            'description' => __('Configure multi-currency settings for your store.', 'aqualuxe'),
            'panel' => 'aqualuxe_woocommerce',
            'priority' => 30,
        ));
        
        // Add currency settings
        $wp_customize->add_setting('aqualuxe_enable_multi_currency', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));
        
        $wp_customize->add_control('aqualuxe_enable_multi_currency', array(
            'label' => __('Enable Multi-currency Support', 'aqualuxe'),
            'description' => __('Allow customers to switch between different currencies.', 'aqualuxe'),
            'section' => 'aqualuxe_currency_settings',
            'type' => 'checkbox',
        ));
        
        // Add currency position setting
        $wp_customize->add_setting('aqualuxe_currency_position', array(
            'default' => 'left',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        ));
        
        $wp_customize->add_control('aqualuxe_currency_position', array(
            'label' => __('Currency Position', 'aqualuxe'),
            'description' => __('Choose the position of the currency symbol.', 'aqualuxe'),
            'section' => 'aqualuxe_currency_settings',
            'type' => 'select',
            'choices' => array(
                'left' => __('Left ($99.99)', 'aqualuxe'),
                'right' => __('Right (99.99$)', 'aqualuxe'),
                'left_space' => __('Left with space ($ 99.99)', 'aqualuxe'),
                'right_space' => __('Right with space (99.99 $)', 'aqualuxe'),
            ),
        ));
        
        // Add currency decimal setting
        $wp_customize->add_setting('aqualuxe_currency_decimals', array(
            'default' => '2',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_currency_decimals', array(
            'label' => __('Currency Decimals', 'aqualuxe'),
            'description' => __('Number of decimal places to show in prices.', 'aqualuxe'),
            'section' => 'aqualuxe_currency_settings',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 0,
                'max' => 4,
                'step' => 1,
            ),
        ));
        
        // Add currency thousand separator setting
        $wp_customize->add_setting('aqualuxe_currency_thousand_separator', array(
            'default' => ',',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_currency_thousand_separator', array(
            'label' => __('Thousand Separator', 'aqualuxe'),
            'description' => __('Symbol to use for thousand separator.', 'aqualuxe'),
            'section' => 'aqualuxe_currency_settings',
            'type' => 'text',
        ));
        
        // Add currency decimal separator setting
        $wp_customize->add_setting('aqualuxe_currency_decimal_separator', array(
            'default' => '.',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_currency_decimal_separator', array(
            'label' => __('Decimal Separator', 'aqualuxe'),
            'description' => __('Symbol to use for decimal separator.', 'aqualuxe'),
            'section' => 'aqualuxe_currency_settings',
            'type' => 'text',
        ));
    }
    add_action('customize_register', 'aqualuxe_currency_customizer_settings');
    
    // Add currency settings to admin menu
    function aqualuxe_currency_admin_menu() {
        add_submenu_page(
            'woocommerce',
            __('Currency Settings', 'aqualuxe'),
            __('Currency Settings', 'aqualuxe'),
            'manage_options',
            'aqualuxe-currency-settings',
            'aqualuxe_currency_settings_page'
        );
    }
    add_action('admin_menu', 'aqualuxe_currency_admin_menu');
    
    // Currency settings page
    function aqualuxe_currency_settings_page() {
        // Save settings
        if (isset($_POST['aqualuxe_save_currency_settings']) && check_admin_referer('aqualuxe_currency_settings')) {
            // Get currencies
            $currencies = array();
            
            foreach ($_POST['currency_code'] as $key => $code) {
                if (empty($code)) {
                    continue;
                }
                
                $currencies[$code] = array(
                    'symbol' => sanitize_text_field($_POST['currency_symbol'][$key]),
                    'rate' => (float) $_POST['currency_rate'][$key],
                    'name' => sanitize_text_field($_POST['currency_name'][$key]),
                );
            }
            
            // Save currencies
            update_option('aqualuxe_currencies', $currencies);
            
            // Show success message
            echo '<div class="notice notice-success"><p>' . esc_html__('Currency settings saved.', 'aqualuxe') . '</p></div>';
        }
        
        // Get currencies
        $currencies = get_option('aqualuxe_currencies', array(
            'USD' => array(
                'symbol' => '$',
                'rate' => 1,
                'name' => 'US Dollar',
            ),
            'EUR' => array(
                'symbol' => '€',
                'rate' => 0.85,
                'name' => 'Euro',
            ),
            'GBP' => array(
                'symbol' => '£',
                'rate' => 0.75,
                'name' => 'British Pound',
            ),
            'CAD' => array(
                'symbol' => 'CA$',
                'rate' => 1.25,
                'name' => 'Canadian Dollar',
            ),
            'AUD' => array(
                'symbol' => 'A$',
                'rate' => 1.35,
                'name' => 'Australian Dollar',
            ),
            'JPY' => array(
                'symbol' => '¥',
                'rate' => 110,
                'name' => 'Japanese Yen',
            ),
        ));
        
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Currency Settings', 'aqualuxe'); ?></h1>
            
            <form method="post" action="">
                <?php wp_nonce_field('aqualuxe_currency_settings'); ?>
                
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><?php esc_html_e('Base Currency', 'aqualuxe'); ?></th>
                            <td>
                                <p><?php esc_html_e('USD (US Dollar) is the base currency. All other currencies are converted from this base.', 'aqualuxe'); ?></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <h2><?php esc_html_e('Currencies', 'aqualuxe'); ?></h2>
                
                <table class="widefat" id="aqualuxe-currencies-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Currency Code', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Currency Symbol', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Currency Name', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Exchange Rate', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Actions', 'aqualuxe'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($currencies as $code => $currency) : ?>
                            <tr>
                                <td>
                                    <input type="text" name="currency_code[]" value="<?php echo esc_attr($code); ?>" required>
                                </td>
                                <td>
                                    <input type="text" name="currency_symbol[]" value="<?php echo esc_attr($currency['symbol']); ?>" required>
                                </td>
                                <td>
                                    <input type="text" name="currency_name[]" value="<?php echo esc_attr($currency['name']); ?>" required>
                                </td>
                                <td>
                                    <input type="number" name="currency_rate[]" value="<?php echo esc_attr($currency['rate']); ?>" step="0.0001" min="0.0001" required>
                                </td>
                                <td>
                                    <button type="button" class="button remove-currency" <?php echo ($code === 'USD') ? 'disabled' : ''; ?>><?php esc_html_e('Remove', 'aqualuxe'); ?></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <button type="button" class="button add-currency"><?php esc_html_e('Add Currency', 'aqualuxe'); ?></button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                
                <p class="submit">
                    <input type="submit" name="aqualuxe_save_currency_settings" class="button button-primary" value="<?php esc_attr_e('Save Settings', 'aqualuxe'); ?>">
                </p>
            </form>
        </div>
        
        <script>
            jQuery(document).ready(function($) {
                // Add currency
                $('.add-currency').on('click', function() {
                    var row = '<tr>' +
                        '<td><input type="text" name="currency_code[]" required></td>' +
                        '<td><input type="text" name="currency_symbol[]" required></td>' +
                        '<td><input type="text" name="currency_name[]" required></td>' +
                        '<td><input type="number" name="currency_rate[]" step="0.0001" min="0.0001" required></td>' +
                        '<td><button type="button" class="button remove-currency"><?php esc_html_e('Remove', 'aqualuxe'); ?></button></td>' +
                        '</tr>';
                    
                    $('#aqualuxe-currencies-table tbody').append(row);
                });
                
                // Remove currency
                $(document).on('click', '.remove-currency', function() {
                    $(this).closest('tr').remove();
                });
            });
        </script>
        <?php
    }
}
add_action('init', 'aqualuxe_multi_currency_support');