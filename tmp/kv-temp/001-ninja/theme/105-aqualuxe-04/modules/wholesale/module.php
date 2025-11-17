<?php
/**
 * Wholesale/B2B Module
 *
 * Handles wholesale and B2B functionality
 *
 * @package AquaLuxe\Modules\Wholesale
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class AquaLuxe_Wholesale_Module
 *
 * Manages wholesale and B2B operations
 */
class AquaLuxe_Wholesale_Module {
    
    /**
     * Single instance of the class
     *
     * @var AquaLuxe_Wholesale_Module
     */
    private static $instance = null;

    /**
     * Module configuration
     *
     * @var array
     */
    private $config = array(
        'name'        => 'Wholesale/B2B',
        'version'     => '1.0.0',
        'description' => 'Wholesale and B2B functionality for bulk orders and trade accounts',
        'enabled'     => true,
        'dependencies' => array(),
    );

    /**
     * Get instance
     *
     * @return AquaLuxe_Wholesale_Module
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        if ($this->config['enabled']) {
            $this->init_hooks();
        }
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_ajax_aqualuxe_wholesale_request', array($this, 'handle_wholesale_request'));
        add_action('wp_ajax_nopriv_aqualuxe_wholesale_request', array($this, 'handle_wholesale_request'));
        
        // WooCommerce integration
        if (class_exists('WooCommerce')) {
            add_action('woocommerce_product_meta_end', array($this, 'add_wholesale_pricing_display'));
            add_filter('woocommerce_product_get_price', array($this, 'apply_wholesale_pricing'), 10, 2);
        }
        
        // User role management
        add_action('init', array($this, 'create_wholesale_user_roles'));
        add_action('user_register', array($this, 'handle_wholesale_registration'));
        
        // Admin hooks
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
    }

    /**
     * Register custom post types
     */
    public function register_post_types() {
        // Wholesale Requests
        register_post_type('aqualuxe_wholesale', array(
            'label'               => __('Wholesale Request', 'aqualuxe'),
            'description'         => __('Wholesale account and bulk order requests', 'aqualuxe'),
            'labels'              => aqualuxe_get_post_type_labels('Wholesale Request', 'Wholesale Requests'),
            'supports'            => array('title', 'editor', 'custom-fields', 'author'),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capability_type'     => 'post',
            'show_in_rest'        => true,
            'menu_icon'           => 'dashicons-businessman',
            'menu_position'       => 25,
        ));

        // B2B Quotes
        register_post_type('aqualuxe_quote', array(
            'label'               => __('B2B Quote', 'aqualuxe'),
            'description'         => __('Business-to-business price quotes', 'aqualuxe'),
            'labels'              => aqualuxe_get_post_type_labels('B2B Quote', 'B2B Quotes'),
            'supports'            => array('title', 'editor', 'custom-fields', 'author'),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => 'edit.php?post_type=aqualuxe_wholesale',
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capability_type'     => 'post',
            'show_in_rest'        => false,
            'menu_icon'           => 'dashicons-money-alt',
        ));
    }

    /**
     * Register custom taxonomies
     */
    public function register_taxonomies() {
        // Wholesale Categories
        register_taxonomy('wholesale_category', array('aqualuxe_wholesale'), array(
            'labels' => array(
                'name'          => __('Wholesale Categories', 'aqualuxe'),
                'singular_name' => __('Wholesale Category', 'aqualuxe'),
                'menu_name'     => __('Categories', 'aqualuxe'),
            ),
            'hierarchical'      => true,
            'public'            => false,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => false,
            'show_tagcloud'     => false,
            'show_in_rest'      => true,
        ));

        // Quote Status
        register_taxonomy('quote_status', array('aqualuxe_quote'), array(
            'labels' => array(
                'name'          => __('Quote Status', 'aqualuxe'),
                'singular_name' => __('Status', 'aqualuxe'),
                'menu_name'     => __('Status', 'aqualuxe'),
            ),
            'hierarchical'      => false,
            'public'            => false,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => false,
            'show_tagcloud'     => false,
            'show_in_rest'      => false,
        ));
    }

    /**
     * Create wholesale user roles
     */
    public function create_wholesale_user_roles() {
        // Only create roles if they don't exist
        if (!get_role('wholesale_customer')) {
            add_role('wholesale_customer', __('Wholesale Customer', 'aqualuxe'), array(
                'read'                  => true,
                'view_wholesale_prices' => true,
                'place_wholesale_orders' => true,
            ));
        }

        if (!get_role('b2b_manager')) {
            add_role('b2b_manager', __('B2B Manager', 'aqualuxe'), array(
                'read'                    => true,
                'edit_posts'              => true,
                'edit_others_posts'       => true,
                'publish_posts'           => true,
                'read_private_posts'      => true,
                'manage_wholesale'        => true,
                'approve_wholesale_requests' => true,
                'view_wholesale_analytics' => true,
            ));
        }
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=aqualuxe_wholesale',
            __('Wholesale Settings', 'aqualuxe'),
            __('Settings', 'aqualuxe'),
            'manage_options',
            'aqualuxe-wholesale-settings',
            array($this, 'render_settings_page')
        );

        add_submenu_page(
            'edit.php?post_type=aqualuxe_wholesale',
            __('Wholesale Analytics', 'aqualuxe'),
            __('Analytics', 'aqualuxe'),
            'view_wholesale_analytics',
            'aqualuxe-wholesale-analytics',
            array($this, 'render_analytics_page')
        );
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'aqualuxe_wholesale_details',
            __('Wholesale Details', 'aqualuxe'),
            array($this, 'render_wholesale_meta_box'),
            'aqualuxe_wholesale',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe_quote_details',
            __('Quote Details', 'aqualuxe'),
            array($this, 'render_quote_meta_box'),
            'aqualuxe_quote',
            'normal',
            'high'
        );
    }

    /**
     * Render wholesale meta box
     */
    public function render_wholesale_meta_box($post) {
        wp_nonce_field('aqualuxe_wholesale_meta', 'aqualuxe_wholesale_nonce');
        
        $company_name = get_post_meta($post->ID, '_wholesale_company_name', true);
        $business_type = get_post_meta($post->ID, '_wholesale_business_type', true);
        $annual_volume = get_post_meta($post->ID, '_wholesale_annual_volume', true);
        $status = get_post_meta($post->ID, '_wholesale_status', true);
        $admin_notes = get_post_meta($post->ID, '_wholesale_admin_notes', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="wholesale_company_name"><?php esc_html_e('Company Name', 'aqualuxe'); ?></label></th>
                <td><input type="text" id="wholesale_company_name" name="wholesale_company_name" value="<?php echo esc_attr($company_name); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="wholesale_business_type"><?php esc_html_e('Business Type', 'aqualuxe'); ?></label></th>
                <td><input type="text" id="wholesale_business_type" name="wholesale_business_type" value="<?php echo esc_attr($business_type); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="wholesale_annual_volume"><?php esc_html_e('Annual Volume', 'aqualuxe'); ?></label></th>
                <td><input type="text" id="wholesale_annual_volume" name="wholesale_annual_volume" value="<?php echo esc_attr($annual_volume); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="wholesale_status"><?php esc_html_e('Status', 'aqualuxe'); ?></label></th>
                <td>
                    <select id="wholesale_status" name="wholesale_status">
                        <option value="pending" <?php selected($status, 'pending'); ?>><?php esc_html_e('Pending', 'aqualuxe'); ?></option>
                        <option value="approved" <?php selected($status, 'approved'); ?>><?php esc_html_e('Approved', 'aqualuxe'); ?></option>
                        <option value="rejected" <?php selected($status, 'rejected'); ?>><?php esc_html_e('Rejected', 'aqualuxe'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="wholesale_admin_notes"><?php esc_html_e('Admin Notes', 'aqualuxe'); ?></label></th>
                <td><textarea id="wholesale_admin_notes" name="wholesale_admin_notes" class="large-text" rows="4"><?php echo esc_textarea($admin_notes); ?></textarea></td>
            </tr>
        </table>
        <?php
    }

    /**
     * Handle wholesale registration
     */
    public function handle_wholesale_registration($user_id) {
        if (isset($_POST['wholesale_application']) && $_POST['wholesale_application'] === '1') {
            // Validate and sanitize inputs
            $company_name = sanitize_text_field($_POST['wholesale_company_name'] ?? '');
            $business_type = sanitize_text_field($_POST['wholesale_business_type'] ?? '');
            $description = wp_kses_post($_POST['wholesale_business_description'] ?? '');
            
            // Create wholesale request post
            $request_id = wp_insert_post(array(
                'post_title'   => sprintf(__('Wholesale Request - User #%d', 'aqualuxe'), $user_id),
                'post_content' => $description,
                'post_status'  => 'pending',
                'post_type'    => 'aqualuxe_wholesale',
                'post_author'  => $user_id,
                'meta_input'   => array(
                    '_wholesale_company_name'    => $company_name,
                    '_wholesale_business_type'   => $business_type,
                    '_wholesale_tax_id'          => sanitize_text_field($_POST['wholesale_tax_id'] ?? ''),
                    '_wholesale_annual_volume'   => sanitize_text_field($_POST['wholesale_annual_volume'] ?? ''),
                    '_wholesale_request_date'    => current_time('mysql'),
                    '_wholesale_status'          => 'pending',
                ),
            ));

            if ($request_id && !is_wp_error($request_id)) {
                // Send notification emails
                $this->send_wholesale_request_notifications($request_id, $user_id);
            }
        }
    }

    /**
     * Handle AJAX wholesale requests
     */
    public function handle_wholesale_request() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_wholesale_nonce')) {
            wp_die(__('Security check failed', 'aqualuxe'), 403);
        }

        $action = sanitize_text_field($_POST['wholesale_action'] ?? '');

        switch ($action) {
            case 'submit_application':
                $this->process_wholesale_application();
                break;
                
            case 'request_quote':
                $this->process_quote_request();
                break;
                
            case 'update_application':
                $this->update_wholesale_application();
                break;
                
            default:
                wp_send_json_error(__('Invalid action', 'aqualuxe'));
        }
    }

    /**
     * Process wholesale application
     */
    private function process_wholesale_application() {
        if (!is_user_logged_in()) {
            wp_send_json_error(__('Please login to submit wholesale application', 'aqualuxe'));
        }

        $user_id = get_current_user_id();
        $company_name = sanitize_text_field($_POST['company_name'] ?? '');
        $business_type = sanitize_text_field($_POST['business_type'] ?? '');
        $annual_volume = sanitize_text_field($_POST['annual_volume'] ?? '');
        $description = wp_kses_post($_POST['description'] ?? '');

        if (empty($company_name) || empty($business_type)) {
            wp_send_json_error(__('Please fill in all required fields', 'aqualuxe'));
        }

        // Create wholesale request
        $request_id = wp_insert_post(array(
            'post_title'   => sprintf(__('Wholesale Application - %s', 'aqualuxe'), $company_name),
            'post_content' => $description,
            'post_status'  => 'pending',
            'post_type'    => 'aqualuxe_wholesale',
            'post_author'  => $user_id,
            'meta_input'   => array(
                '_wholesale_company_name'    => $company_name,
                '_wholesale_business_type'   => $business_type,
                '_wholesale_annual_volume'   => $annual_volume,
                '_wholesale_request_date'    => current_time('mysql'),
                '_wholesale_status'          => 'pending',
                '_wholesale_ip_address'      => $this->get_client_ip(),
                '_wholesale_user_agent'      => sanitize_text_field($_SERVER['HTTP_USER_AGENT'] ?? ''),
            ),
        ));

        if ($request_id && !is_wp_error($request_id)) {
            // Send notifications
            $this->send_wholesale_request_notifications($request_id, $user_id);
            
            wp_send_json_success(array(
                'message' => __('Wholesale application submitted successfully! We will review and contact you within 2-3 business days.', 'aqualuxe'),
                'request_id' => $request_id,
            ));
        } else {
            wp_send_json_error(__('Failed to submit application. Please try again.', 'aqualuxe'));
        }
    }

    /**
     * Process quote request
     */
    private function process_quote_request() {
        if (!is_user_logged_in()) {
            wp_send_json_error(__('Please login to request quotes', 'aqualuxe'));
        }

        $user_id = get_current_user_id();
        $products = json_decode(stripslashes($_POST['products'] ?? '[]'), true);
        $notes = wp_kses_post($_POST['notes'] ?? '');

        if (empty($products) || !is_array($products)) {
            wp_send_json_error(__('Please select products for quote', 'aqualuxe'));
        }

        // Create quote request
        $quote_id = wp_insert_post(array(
            'post_title'   => sprintf(__('Quote Request - %s', 'aqualuxe'), date('Y-m-d H:i')),
            'post_content' => $notes,
            'post_status'  => 'pending',
            'post_type'    => 'aqualuxe_quote',
            'post_author'  => $user_id,
            'meta_input'   => array(
                '_quote_products'      => wp_json_encode($products),
                '_quote_request_date'  => current_time('mysql'),
                '_quote_status'        => 'pending',
                '_quote_total_items'   => count($products),
                '_quote_ip_address'    => $this->get_client_ip(),
            ),
        ));

        if ($quote_id && !is_wp_error($quote_id)) {
            // Set quote status taxonomy
            wp_set_object_terms($quote_id, 'pending', 'quote_status');
            
            // Send notifications
            $this->send_quote_request_notifications($quote_id, $user_id);
            
            wp_send_json_success(array(
                'message' => __('Quote request submitted successfully! We will send you a detailed quote within 24 hours.', 'aqualuxe'),
                'quote_id' => $quote_id,
            ));
        } else {
            wp_send_json_error(__('Failed to submit quote request. Please try again.', 'aqualuxe'));
        }
    }

    /**
     * Apply wholesale pricing for qualified users
     */
    public function apply_wholesale_pricing($price, $product) {
        if (!is_user_logged_in()) {
            return $price;
        }

        $user = wp_get_current_user();
        if (!in_array('wholesale_customer', $user->roles)) {
            return $price;
        }

        $wholesale_price = get_post_meta($product->get_id(), '_wholesale_price', true);
        $wholesale_discount = get_option('aqualuxe_wholesale_discount', 15);

        if ($wholesale_price && is_numeric($wholesale_price)) {
            return floatval($wholesale_price);
        }

        // Apply percentage discount if no specific wholesale price set
        if ($wholesale_discount && is_numeric($wholesale_discount)) {
            return $price * (1 - ($wholesale_discount / 100));
        }

        return $price;
    }

    /**
     * Add wholesale pricing display on product pages
     */
    public function add_wholesale_pricing_display() {
        if (!is_user_logged_in()) {
            return;
        }

        $user = wp_get_current_user();
        if (!in_array('wholesale_customer', $user->roles)) {
            return;
        }

        global $product;
        $wholesale_price = get_post_meta($product->get_id(), '_wholesale_price', true);
        $wholesale_discount = get_option('aqualuxe_wholesale_discount', 15);

        if ($wholesale_price || $wholesale_discount) {
            echo '<div class="wholesale-pricing">';
            echo '<h4>' . esc_html__('Wholesale Pricing', 'aqualuxe') . '</h4>';
            
            if ($wholesale_price) {
                echo '<p class="wholesale-price">' . sprintf(esc_html__('Wholesale Price: %s', 'aqualuxe'), wc_price($wholesale_price)) . '</p>';
            } else {
                echo '<p class="wholesale-discount">' . sprintf(esc_html__('Wholesale Discount: %d%% off', 'aqualuxe'), $wholesale_discount) . '</p>';
            }
            
            echo '</div>';
        }
    }

    /**
     * Send wholesale request notifications
     */
    private function send_wholesale_request_notifications($request_id, $user_id) {
        $user = get_user_by('id', $user_id);
        $company_name = get_post_meta($request_id, '_wholesale_company_name', true);
        
        if (!$user) {
            return;
        }
        
        // Email to admin
        $admin_email = get_option('admin_email');
        $admin_subject = sprintf(__('New Wholesale Application - %s', 'aqualuxe'), $company_name);
        $admin_message = sprintf(
            __('A new wholesale application has been submitted.\n\nCompany: %s\nApplicant: %s (%s)\nRequest ID: %d\n\nReview the application in the admin panel.', 'aqualuxe'),
            $company_name,
            $user->display_name,
            $user->user_email,
            $request_id
        );
        
        wp_mail($admin_email, $admin_subject, $admin_message);
        
        // Email to applicant
        $user_subject = __('Wholesale Application Received - AquaLuxe', 'aqualuxe');
        $user_message = sprintf(
            __('Thank you for your wholesale application.\n\nWe have received your application for %s and will review it within 2-3 business days.\n\nReference ID: %d\n\nBest regards,\nAquaLuxe Team', 'aqualuxe'),
            $company_name,
            $request_id
        );
        
        wp_mail($user->user_email, $user_subject, $user_message);
    }

    /**
     * Send quote request notifications
     */
    private function send_quote_request_notifications($quote_id, $user_id) {
        $user = get_user_by('id', $user_id);
        $products_data = get_post_meta($quote_id, '_quote_products', true);
        $products = json_decode($products_data, true);
        $product_count = is_array($products) ? count($products) : 0;
        
        if (!$user) {
            return;
        }
        
        // Email to admin
        $admin_email = get_option('admin_email');
        $admin_subject = sprintf(__('New Quote Request - #%d', 'aqualuxe'), $quote_id);
        $admin_message = sprintf(
            __('A new quote request has been submitted.\n\nCustomer: %s (%s)\nQuote ID: %d\nProducts: %d items\n\nReview the request in the admin panel.', 'aqualuxe'),
            $user->display_name,
            $user->user_email,
            $quote_id,
            $product_count
        );
        
        wp_mail($admin_email, $admin_subject, $admin_message);
        
        // Email to customer
        $user_subject = sprintf(__('Quote Request Received - #%d', 'aqualuxe'), $quote_id);
        $user_message = sprintf(
            __('Thank you for your quote request.\n\nWe have received your request for %d products and will send you a detailed quote within 24 hours.\n\nQuote Reference: #%d\n\nBest regards,\nAquaLuxe Team', 'aqualuxe'),
            $product_count,
            $quote_id
        );
        
        wp_mail($user->user_email, $user_subject, $user_message);
    }

    /**
     * Update wholesale application
     */
    private function update_wholesale_application() {
        if (!current_user_can('manage_wholesale')) {
            wp_send_json_error(__('Insufficient permissions', 'aqualuxe'));
        }

        $request_id = intval($_POST['request_id'] ?? 0);
        $status = sanitize_text_field($_POST['status'] ?? '');
        $notes = wp_kses_post($_POST['admin_notes'] ?? '');

        if (!$request_id || !$status) {
            wp_send_json_error(__('Invalid request data', 'aqualuxe'));
        }

        // Update post meta
        update_post_meta($request_id, '_wholesale_status', $status);
        update_post_meta($request_id, '_wholesale_admin_notes', $notes);
        update_post_meta($request_id, '_wholesale_updated', current_time('mysql'));

        // Update post status
        wp_update_post(array(
            'ID' => $request_id,
            'post_status' => $status === 'approved' ? 'publish' : 'pending',
        ));

        // If approved, upgrade user role
        if ($status === 'approved') {
            $user_id = get_post_field('post_author', $request_id);
            $user = new WP_User($user_id);
            $user->add_role('wholesale_customer');
        }

        wp_send_json_success(array(
            'message' => __('Application updated successfully', 'aqualuxe'),
        ));
    }

    /**
     * Get client IP address
     */
    private function get_client_ip() {
        $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }

    /**
     * Enqueue module assets
     */
    public function enqueue_assets() {
        if ($this->should_enqueue_assets()) {
            wp_enqueue_script(
                'aqualuxe-wholesale',
                AQUALUXE_ASSETS_URI . '/js/modules/wholesale.js',
                array('jquery', 'aqualuxe-app'),
                AQUALUXE_VERSION,
                true
            );

            wp_localize_script('aqualuxe-wholesale', 'aqualuxeWholesale', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('aqualuxe_wholesale_nonce'),
                'strings' => array(
                    'submit_application' => __('Submit Application', 'aqualuxe'),
                    'request_quote'      => __('Request Quote', 'aqualuxe'),
                    'processing'         => __('Processing...', 'aqualuxe'),
                    'error'             => __('An error occurred. Please try again.', 'aqualuxe'),
                ),
            ));
        }
    }

    /**
     * Check if assets should be enqueued
     */
    private function should_enqueue_assets() {
        return is_page('wholesale') || 
               is_page('b2b') || 
               is_singular('product') ||
               (is_user_logged_in() && in_array('wholesale_customer', wp_get_current_user()->roles));
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Wholesale Settings', 'aqualuxe'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('aqualuxe_wholesale_settings');
                do_settings_sections('aqualuxe_wholesale_settings');
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Default Wholesale Discount (%)', 'aqualuxe'); ?></th>
                        <td>
                            <input type="number" name="aqualuxe_wholesale_discount" value="<?php echo esc_attr(get_option('aqualuxe_wholesale_discount', 15)); ?>" min="0" max="100" />
                            <p class="description"><?php esc_html_e('Default discount percentage for wholesale customers', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Minimum Order Amount', 'aqualuxe'); ?></th>
                        <td>
                            <input type="number" name="aqualuxe_wholesale_min_order" value="<?php echo esc_attr(get_option('aqualuxe_wholesale_min_order', 1000)); ?>" min="0" step="0.01" />
                            <p class="description"><?php esc_html_e('Minimum order amount for wholesale customers', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render analytics page
     */
    public function render_analytics_page() {
        $total_requests = wp_count_posts('aqualuxe_wholesale');
        $total_quotes = wp_count_posts('aqualuxe_quote');
        
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Wholesale Analytics', 'aqualuxe'); ?></h1>
            <div class="aqualuxe-analytics-grid">
                <div class="aqualuxe-stat-card">
                    <h3><?php esc_html_e('Total Wholesale Requests', 'aqualuxe'); ?></h3>
                    <p class="aqualuxe-stat-number"><?php echo esc_html($total_requests->pending + $total_requests->publish); ?></p>
                </div>
                <div class="aqualuxe-stat-card">
                    <h3><?php esc_html_e('Pending Requests', 'aqualuxe'); ?></h3>
                    <p class="aqualuxe-stat-number"><?php echo esc_html($total_requests->pending); ?></p>
                </div>
                <div class="aqualuxe-stat-card">
                    <h3><?php esc_html_e('Approved Requests', 'aqualuxe'); ?></h3>
                    <p class="aqualuxe-stat-number"><?php echo esc_html($total_requests->publish); ?></p>
                </div>
                <div class="aqualuxe-stat-card">
                    <h3><?php esc_html_e('Total Quote Requests', 'aqualuxe'); ?></h3>
                    <p class="aqualuxe-stat-number"><?php echo esc_html($total_quotes->pending + $total_quotes->publish); ?></p>
                </div>
            </div>
        </div>
        <style>
        .aqualuxe-analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .aqualuxe-stat-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            text-align: center;
        }
        .aqualuxe-stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #0073aa;
            margin: 10px 0 0 0;
        }
        </style>
        <?php
    }
}

// Initialize the module
AquaLuxe_Wholesale_Module::get_instance();