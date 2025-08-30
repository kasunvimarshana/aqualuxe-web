<?php
/**
 * AquaLuxe Demo Importer Helper
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Demo Importer Helper Class
 */
class AquaLuxe_Demo_Importer_Helper {

    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * Constructor.
     */
    public function __construct() {
        // Nothing to do here.
    }

    /**
     * Get instance of this class.
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Download remote file.
     *
     * @param string $url URL of file to download.
     * @param array  $args Arguments.
     * @return string|WP_Error Downloaded file path or WP_Error.
     */
    public function download_file($url, $args = array()) {
        // Parse args.
        $args = wp_parse_args($args, array(
            'timeout' => 30,
        ));

        // Download file.
        $response = wp_remote_get($url, $args);

        // Check for error.
        if (is_wp_error($response)) {
            return $response;
        }

        // Get remote file content.
        $file_content = wp_remote_retrieve_body($response);

        // Check for error.
        if (empty($file_content)) {
            return new WP_Error('file_empty', __('Downloaded file is empty.', 'aqualuxe'));
        }

        // Get file name from URL.
        $file_name = basename($url);

        // Create temporary file.
        $temp_file = wp_tempnam($file_name);

        // Write file content to temporary file.
        $file_written = file_put_contents($temp_file, $file_content);

        // Check for error.
        if (!$file_written) {
            return new WP_Error('file_write', __('Could not write file to temporary directory.', 'aqualuxe'));
        }

        return $temp_file;
    }

    /**
     * Get file data from a file.
     *
     * @param string $file File path.
     * @return string File data.
     */
    public function get_file_data($file) {
        // Check if file exists.
        if (!file_exists($file)) {
            return '';
        }

        // Get file data.
        $file_data = file_get_contents($file);

        // Check for error.
        if (!$file_data) {
            return '';
        }

        return $file_data;
    }

    /**
     * Get JSON data from a file.
     *
     * @param string $file File path.
     * @return array|bool JSON data or false.
     */
    public function get_json_data($file) {
        // Get file data.
        $file_data = $this->get_file_data($file);

        // Check for error.
        if (empty($file_data)) {
            return false;
        }

        // Decode JSON data.
        $json_data = json_decode($file_data, true);

        // Check for error.
        if (empty($json_data) || !is_array($json_data)) {
            return false;
        }

        return $json_data;
    }

    /**
     * Import XML file.
     *
     * @param string $file File path.
     * @return array|WP_Error Import results or WP_Error.
     */
    public function import_xml($file) {
        // Check if file exists.
        if (!file_exists($file)) {
            return new WP_Error('file_not_found', __('XML file not found.', 'aqualuxe'));
        }

        // Include WordPress Importer.
        if (!class_exists('WP_Importer')) {
            require ABSPATH . '/wp-admin/includes/class-wp-importer.php';
        }

        // Include WordPress Importer API.
        if (!class_exists('WP_Import')) {
            require AQUALUXE_DEMO_DIR . '/includes/wordpress-importer/wordpress-importer.php';
        }

        // Create importer instance.
        $importer = new WP_Import();
        $importer->fetch_attachments = true;

        // Import XML file.
        ob_start();
        $importer->import($file);
        $output = ob_get_clean();

        return array(
            'output' => $output,
            'imported' => true,
        );
    }

    /**
     * Import WooCommerce product attributes.
     *
     * @param array $attributes Attributes data.
     * @return array|WP_Error Import results or WP_Error.
     */
    public function import_product_attributes($attributes) {
        // Check if WooCommerce is active.
        if (!class_exists('WooCommerce')) {
            return new WP_Error('woocommerce_not_active', __('WooCommerce is not active.', 'aqualuxe'));
        }

        // Check if attributes data is valid.
        if (empty($attributes) || !is_array($attributes)) {
            return new WP_Error('invalid_attributes', __('Invalid attributes data.', 'aqualuxe'));
        }

        $results = array();

        // Import attributes.
        foreach ($attributes as $attribute) {
            // Check if attribute data is valid.
            if (empty($attribute['name'])) {
                continue;
            }

            // Create attribute.
            $attribute_id = wc_create_attribute(array(
                'name' => $attribute['name'],
                'slug' => isset($attribute['slug']) ? $attribute['slug'] : sanitize_title($attribute['name']),
                'type' => isset($attribute['type']) ? $attribute['type'] : 'select',
                'order_by' => isset($attribute['order_by']) ? $attribute['order_by'] : 'menu_order',
                'has_archives' => isset($attribute['has_archives']) ? $attribute['has_archives'] : 0,
            ));

            // Check for error.
            if (is_wp_error($attribute_id)) {
                $results[] = array(
                    'name' => $attribute['name'],
                    'error' => $attribute_id->get_error_message(),
                );
                continue;
            }

            // Import attribute terms.
            if (isset($attribute['terms']) && is_array($attribute['terms'])) {
                $attribute_taxonomy = wc_attribute_taxonomy_name_by_id($attribute_id);

                foreach ($attribute['terms'] as $term) {
                    // Check if term data is valid.
                    if (empty($term['name'])) {
                        continue;
                    }

                    // Create term.
                    $term_id = wp_insert_term(
                        $term['name'],
                        $attribute_taxonomy,
                        array(
                            'slug' => isset($term['slug']) ? $term['slug'] : sanitize_title($term['name']),
                            'description' => isset($term['description']) ? $term['description'] : '',
                        )
                    );

                    // Check for error.
                    if (is_wp_error($term_id)) {
                        $results[] = array(
                            'name' => $attribute['name'] . ' - ' . $term['name'],
                            'error' => $term_id->get_error_message(),
                        );
                    } else {
                        $results[] = array(
                            'name' => $attribute['name'] . ' - ' . $term['name'],
                            'imported' => true,
                        );
                    }
                }
            }

            $results[] = array(
                'name' => $attribute['name'],
                'imported' => true,
            );
        }

        return $results;
    }

    /**
     * Import WooCommerce tax rates.
     *
     * @param array $tax_rates Tax rates data.
     * @return array|WP_Error Import results or WP_Error.
     */
    public function import_tax_rates($tax_rates) {
        // Check if WooCommerce is active.
        if (!class_exists('WooCommerce')) {
            return new WP_Error('woocommerce_not_active', __('WooCommerce is not active.', 'aqualuxe'));
        }

        // Check if tax rates data is valid.
        if (empty($tax_rates) || !is_array($tax_rates)) {
            return new WP_Error('invalid_tax_rates', __('Invalid tax rates data.', 'aqualuxe'));
        }

        $results = array();

        // Import tax rates.
        foreach ($tax_rates as $tax_rate) {
            // Check if tax rate data is valid.
            if (empty($tax_rate['country'])) {
                continue;
            }

            // Create tax rate.
            $tax_rate_id = WC_Tax::_insert_tax_rate(array(
                'tax_rate_country' => $tax_rate['country'],
                'tax_rate_state' => isset($tax_rate['state']) ? $tax_rate['state'] : '',
                'tax_rate' => isset($tax_rate['rate']) ? $tax_rate['rate'] : 0,
                'tax_rate_name' => isset($tax_rate['name']) ? $tax_rate['name'] : '',
                'tax_rate_priority' => isset($tax_rate['priority']) ? $tax_rate['priority'] : 1,
                'tax_rate_compound' => isset($tax_rate['compound']) ? $tax_rate['compound'] : 0,
                'tax_rate_shipping' => isset($tax_rate['shipping']) ? $tax_rate['shipping'] : 1,
                'tax_rate_order' => isset($tax_rate['order']) ? $tax_rate['order'] : 0,
                'tax_rate_class' => isset($tax_rate['class']) ? $tax_rate['class'] : '',
            ));

            // Check for error.
            if (!$tax_rate_id) {
                $results[] = array(
                    'name' => $tax_rate['country'] . ' - ' . (isset($tax_rate['name']) ? $tax_rate['name'] : ''),
                    'error' => __('Failed to import tax rate.', 'aqualuxe'),
                );
                continue;
            }

            // Import tax rate postcodes.
            if (isset($tax_rate['postcodes']) && is_array($tax_rate['postcodes'])) {
                foreach ($tax_rate['postcodes'] as $postcode) {
                    WC_Tax::_update_tax_rate_postcodes($tax_rate_id, $postcode);
                }
            }

            // Import tax rate cities.
            if (isset($tax_rate['cities']) && is_array($tax_rate['cities'])) {
                foreach ($tax_rate['cities'] as $city) {
                    WC_Tax::_update_tax_rate_cities($tax_rate_id, $city);
                }
            }

            $results[] = array(
                'name' => $tax_rate['country'] . ' - ' . (isset($tax_rate['name']) ? $tax_rate['name'] : ''),
                'imported' => true,
            );
        }

        return $results;
    }

    /**
     * Import WooCommerce shipping zones.
     *
     * @param array $shipping_zones Shipping zones data.
     * @return array|WP_Error Import results or WP_Error.
     */
    public function import_shipping_zones($shipping_zones) {
        // Check if WooCommerce is active.
        if (!class_exists('WooCommerce')) {
            return new WP_Error('woocommerce_not_active', __('WooCommerce is not active.', 'aqualuxe'));
        }

        // Check if shipping zones data is valid.
        if (empty($shipping_zones) || !is_array($shipping_zones)) {
            return new WP_Error('invalid_shipping_zones', __('Invalid shipping zones data.', 'aqualuxe'));
        }

        $results = array();

        // Import shipping zones.
        foreach ($shipping_zones as $shipping_zone) {
            // Check if shipping zone data is valid.
            if (empty($shipping_zone['name'])) {
                continue;
            }

            // Create shipping zone.
            $zone = new WC_Shipping_Zone();
            $zone->set_zone_name($shipping_zone['name']);
            $zone->set_zone_order(isset($shipping_zone['order']) ? $shipping_zone['order'] : 0);
            $zone_id = $zone->save();

            // Check for error.
            if (!$zone_id) {
                $results[] = array(
                    'name' => $shipping_zone['name'],
                    'error' => __('Failed to import shipping zone.', 'aqualuxe'),
                );
                continue;
            }

            // Import shipping zone locations.
            if (isset($shipping_zone['locations']) && is_array($shipping_zone['locations'])) {
                foreach ($shipping_zone['locations'] as $location) {
                    // Check if location data is valid.
                    if (empty($location['type']) || empty($location['code'])) {
                        continue;
                    }

                    $zone->add_location($location['code'], $location['type']);
                }
                $zone->save();
            }

            // Import shipping zone methods.
            if (isset($shipping_zone['methods']) && is_array($shipping_zone['methods'])) {
                foreach ($shipping_zone['methods'] as $method) {
                    // Check if method data is valid.
                    if (empty($method['id'])) {
                        continue;
                    }

                    // Add shipping method to zone.
                    $instance_id = $zone->add_shipping_method($method['id']);

                    // Set shipping method settings.
                    if ($instance_id && isset($method['settings']) && is_array($method['settings'])) {
                        $shipping_method = WC_Shipping_Zones::get_shipping_method($instance_id);
                        foreach ($method['settings'] as $key => $value) {
                            $shipping_method->instance_settings[$key] = $value;
                        }
                        $shipping_method->instance_settings['enabled'] = 'yes';
                        update_option($shipping_method->get_instance_option_key(), $shipping_method->instance_settings);
                    }
                }
            }

            $results[] = array(
                'name' => $shipping_zone['name'],
                'imported' => true,
            );
        }

        return $results;
    }

    /**
     * Import WooCommerce shipping methods.
     *
     * @param array $shipping_methods Shipping methods data.
     * @return array|WP_Error Import results or WP_Error.
     */
    public function import_shipping_methods($shipping_methods) {
        // Check if WooCommerce is active.
        if (!class_exists('WooCommerce')) {
            return new WP_Error('woocommerce_not_active', __('WooCommerce is not active.', 'aqualuxe'));
        }

        // Check if shipping methods data is valid.
        if (empty($shipping_methods) || !is_array($shipping_methods)) {
            return new WP_Error('invalid_shipping_methods', __('Invalid shipping methods data.', 'aqualuxe'));
        }

        $results = array();

        // Import shipping methods.
        foreach ($shipping_methods as $method_id => $settings) {
            // Check if shipping method data is valid.
            if (empty($method_id) || empty($settings) || !is_array($settings)) {
                continue;
            }

            // Update shipping method settings.
            update_option('woocommerce_' . $method_id . '_settings', $settings);

            $results[] = array(
                'name' => $method_id,
                'imported' => true,
            );
        }

        return $results;
    }

    /**
     * Import WooCommerce payment gateways.
     *
     * @param array $payment_gateways Payment gateways data.
     * @return array|WP_Error Import results or WP_Error.
     */
    public function import_payment_gateways($payment_gateways) {
        // Check if WooCommerce is active.
        if (!class_exists('WooCommerce')) {
            return new WP_Error('woocommerce_not_active', __('WooCommerce is not active.', 'aqualuxe'));
        }

        // Check if payment gateways data is valid.
        if (empty($payment_gateways) || !is_array($payment_gateways)) {
            return new WP_Error('invalid_payment_gateways', __('Invalid payment gateways data.', 'aqualuxe'));
        }

        $results = array();

        // Import payment gateways.
        foreach ($payment_gateways as $gateway_id => $settings) {
            // Check if payment gateway data is valid.
            if (empty($gateway_id) || empty($settings) || !is_array($settings)) {
                continue;
            }

            // Update payment gateway settings.
            update_option('woocommerce_' . $gateway_id . '_settings', $settings);

            $results[] = array(
                'name' => $gateway_id,
                'imported' => true,
            );
        }

        return $results;
    }

    /**
     * Import WooCommerce emails.
     *
     * @param array $emails Emails data.
     * @return array|WP_Error Import results or WP_Error.
     */
    public function import_emails($emails) {
        // Check if WooCommerce is active.
        if (!class_exists('WooCommerce')) {
            return new WP_Error('woocommerce_not_active', __('WooCommerce is not active.', 'aqualuxe'));
        }

        // Check if emails data is valid.
        if (empty($emails) || !is_array($emails)) {
            return new WP_Error('invalid_emails', __('Invalid emails data.', 'aqualuxe'));
        }

        $results = array();

        // Import emails.
        foreach ($emails as $email_id => $settings) {
            // Check if email data is valid.
            if (empty($email_id) || empty($settings) || !is_array($settings)) {
                continue;
            }

            // Update email settings.
            update_option('woocommerce_' . $email_id . '_settings', $settings);

            $results[] = array(
                'name' => $email_id,
                'imported' => true,
            );
        }

        return $results;
    }

    /**
     * Import WordPress settings.
     *
     * @param array $settings Settings data.
     * @return array|WP_Error Import results or WP_Error.
     */
    public function import_settings($settings) {
        // Check if settings data is valid.
        if (empty($settings) || !is_array($settings)) {
            return new WP_Error('invalid_settings', __('Invalid settings data.', 'aqualuxe'));
        }

        $results = array();

        // Import settings.
        foreach ($settings as $option_name => $option_value) {
            // Update option.
            update_option($option_name, $option_value);

            $results[] = array(
                'name' => $option_name,
                'imported' => true,
            );
        }

        return $results;
    }

    /**
     * Import WordPress menus.
     *
     * @param array $menus Menus data.
     * @return array|WP_Error Import results or WP_Error.
     */
    public function import_menus($menus) {
        // Check if menus data is valid.
        if (empty($menus) || !is_array($menus)) {
            return new WP_Error('invalid_menus', __('Invalid menus data.', 'aqualuxe'));
        }

        $results = array();

        // Import menus.
        foreach ($menus as $menu_location => $menu_name) {
            // Check if menu location and name are valid.
            if (empty($menu_location) || empty($menu_name)) {
                continue;
            }

            // Get menu by name.
            $menu = get_term_by('name', $menu_name, 'nav_menu');

            // Check if menu exists.
            if (!$menu) {
                $results[] = array(
                    'name' => $menu_name,
                    'error' => __('Menu not found.', 'aqualuxe'),
                );
                continue;
            }

            // Assign menu to location.
            $locations = get_theme_mod('nav_menu_locations');
            $locations[$menu_location] = $menu->term_id;
            set_theme_mod('nav_menu_locations', $locations);

            $results[] = array(
                'name' => $menu_name,
                'imported' => true,
            );
        }

        return $results;
    }

    /**
     * Import WordPress homepage and blog page.
     *
     * @param array $pages Pages data.
     * @return array|WP_Error Import results or WP_Error.
     */
    public function import_pages($pages) {
        // Check if pages data is valid.
        if (empty($pages) || !is_array($pages)) {
            return new WP_Error('invalid_pages', __('Invalid pages data.', 'aqualuxe'));
        }

        $results = array();

        // Import homepage.
        if (isset($pages['homepage'])) {
            // Get page by title.
            $homepage = get_page_by_title($pages['homepage']);

            // Check if page exists.
            if (!$homepage) {
                $results[] = array(
                    'name' => $pages['homepage'],
                    'error' => __('Homepage not found.', 'aqualuxe'),
                );
            } else {
                // Set homepage.
                update_option('show_on_front', 'page');
                update_option('page_on_front', $homepage->ID);

                $results[] = array(
                    'name' => $pages['homepage'],
                    'imported' => true,
                );
            }
        }

        // Import blog page.
        if (isset($pages['blog'])) {
            // Get page by title.
            $blog_page = get_page_by_title($pages['blog']);

            // Check if page exists.
            if (!$blog_page) {
                $results[] = array(
                    'name' => $pages['blog'],
                    'error' => __('Blog page not found.', 'aqualuxe'),
                );
            } else {
                // Set blog page.
                update_option('page_for_posts', $blog_page->ID);

                $results[] = array(
                    'name' => $pages['blog'],
                    'imported' => true,
                );
            }
        }

        return $results;
    }

    /**
     * Import WooCommerce pages.
     *
     * @param array $pages Pages data.
     * @return array|WP_Error Import results or WP_Error.
     */
    public function import_woocommerce_pages($pages) {
        // Check if WooCommerce is active.
        if (!class_exists('WooCommerce')) {
            return new WP_Error('woocommerce_not_active', __('WooCommerce is not active.', 'aqualuxe'));
        }

        // Check if pages data is valid.
        if (empty($pages) || !is_array($pages)) {
            return new WP_Error('invalid_pages', __('Invalid pages data.', 'aqualuxe'));
        }

        $results = array();

        // Import WooCommerce pages.
        foreach ($pages as $page_option => $page_title) {
            // Check if page option and title are valid.
            if (empty($page_option) || empty($page_title)) {
                continue;
            }

            // Get page by title.
            $page = get_page_by_title($page_title);

            // Check if page exists.
            if (!$page) {
                $results[] = array(
                    'name' => $page_title,
                    'error' => __('Page not found.', 'aqualuxe'),
                );
                continue;
            }

            // Set WooCommerce page.
            update_option($page_option, $page->ID);

            $results[] = array(
                'name' => $page_title,
                'imported' => true,
            );
        }

        return $results;
    }
}

// Initialize the demo importer helper
AquaLuxe_Demo_Importer_Helper::get_instance();