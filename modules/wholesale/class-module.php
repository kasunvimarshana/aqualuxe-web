<?php
/**
 * Wholesale Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Wholesale;

use AquaLuxe\Core\Abstracts\Abstract_Module;

defined('ABSPATH') || exit;

/**
 * Wholesale Module Class
 */
class Module extends Abstract_Module {

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Wholesale';

    /**
     * Instance
     *
     * @var Module
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Module
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize module
     */
    public function init() {
        add_action('init', array($this, 'register_user_roles'));
        add_action('init', array($this, 'register_post_type'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_ajax_aqualuxe_wholesale_application', array($this, 'handle_wholesale_application'));
        add_action('wp_ajax_nopriv_aqualuxe_wholesale_application', array($this, 'handle_wholesale_application'));
        add_shortcode('aqualuxe_wholesale_form', array($this, 'wholesale_form_shortcode'));
        
        // WooCommerce integration
        if (class_exists('WooCommerce')) {
            add_action('woocommerce_product_options_pricing', array($this, 'add_wholesale_pricing_fields'));
            add_action('woocommerce_process_product_meta', array($this, 'save_wholesale_pricing_fields'));
            add_filter('woocommerce_get_price_html', array($this, 'wholesale_pricing_display'), 10, 2);
        }
    }

    /**
     * Register wholesale user roles
     */
    public function register_user_roles() {
        // Add wholesale customer role
        add_role('wholesale_customer', __('Wholesale Customer', 'aqualuxe'), array(
            'read' => true,
            'view_wholesale_prices' => true,
            'place_wholesale_orders' => true,
        ));

        // Add wholesale manager role
        add_role('wholesale_manager', __('Wholesale Manager', 'aqualuxe'), array(
            'read' => true,
            'edit_posts' => true,
            'manage_wholesale_accounts' => true,
            'view_wholesale_reports' => true,
        ));
    }

    /**
     * Register wholesale applications post type
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => _x('Wholesale Applications', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Application', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Wholesale Apps', 'Admin Menu text', 'aqualuxe'),
            'add_new_item'          => __('Add New Application', 'aqualuxe'),
            'edit_item'             => __('Edit Application', 'aqualuxe'),
            'view_item'             => __('View Application', 'aqualuxe'),
            'all_items'             => __('All Applications', 'aqualuxe'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 28,
            'menu_icon'          => 'dashicons-businessperson',
            'supports'           => array('title', 'custom-fields'),
        );

        register_post_type('aqualuxe_wholesale_app', $args);
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        if (is_page() || is_user_logged_in()) {
            wp_enqueue_script('aqualuxe-wholesale', $this->get_url() . '/assets/wholesale.js', array('jquery'), '1.0.0', true);
            wp_enqueue_style('aqualuxe-wholesale', $this->get_url() . '/assets/wholesale.css', array(), '1.0.0');
            
            wp_localize_script('aqualuxe-wholesale', 'aqualuxe_wholesale', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_wholesale_nonce'),
                'is_wholesale_customer' => current_user_can('view_wholesale_prices'),
            ));
        }
    }

    /**
     * Handle wholesale application
     */
    public function handle_wholesale_application() {
        check_ajax_referer('aqualuxe_wholesale_nonce', 'nonce');

        $company_name = sanitize_text_field($_POST['company_name']);
        $contact_name = sanitize_text_field($_POST['contact_name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $address = sanitize_textarea_field($_POST['address']);
        $business_type = sanitize_text_field($_POST['business_type']);
        $years_in_business = intval($_POST['years_in_business']);
        $annual_volume = sanitize_text_field($_POST['annual_volume']);

        // Validate required fields
        if (!$company_name || !$contact_name || !$email) {
            wp_send_json_error('Please fill in all required fields');
        }

        // Create application post
        $application_data = array(
            'post_type' => 'aqualuxe_wholesale_app',
            'post_title' => $company_name . ' - ' . $contact_name,
            'post_status' => 'pending',
            'meta_input' => array(
                '_company_name' => $company_name,
                '_contact_name' => $contact_name,
                '_email' => $email,
                '_phone' => $phone,
                '_address' => $address,
                '_business_type' => $business_type,
                '_years_in_business' => $years_in_business,
                '_annual_volume' => $annual_volume,
                '_application_status' => 'pending',
                '_application_date' => current_time('mysql')
            )
        );

        $application_id = wp_insert_post($application_data);

        if ($application_id) {
            // Send notification to admin
            $this->send_application_notification($application_id);
            wp_send_json_success('Application submitted successfully');
        } else {
            wp_send_json_error('Failed to submit application');
        }
    }

    /**
     * Send application notification
     */
    private function send_application_notification($application_id) {
        $admin_email = get_option('admin_email');
        $company_name = get_post_meta($application_id, '_company_name', true);
        $contact_name = get_post_meta($application_id, '_contact_name', true);

        $subject = __('New Wholesale Application', 'aqualuxe');
        $message = sprintf(
            __('A new wholesale application has been submitted:\n\nCompany: %s\nContact: %s\n\nPlease review in the admin area.', 'aqualuxe'),
            $company_name,
            $contact_name
        );

        wp_mail($admin_email, $subject, $message);
    }

    /**
     * Add wholesale pricing fields to WooCommerce products
     */
    public function add_wholesale_pricing_fields() {
        global $post;

        echo '<div class="options_group">';
        
        woocommerce_wp_text_input(array(
            'id' => '_wholesale_price',
            'label' => __('Wholesale Price', 'aqualuxe'),
            'desc_tip' => true,
            'description' => __('Enter the wholesale price for this product.', 'aqualuxe'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => '0.01',
                'min' => '0'
            )
        ));

        woocommerce_wp_text_input(array(
            'id' => '_wholesale_min_quantity',
            'label' => __('Minimum Wholesale Quantity', 'aqualuxe'),
            'desc_tip' => true,
            'description' => __('Minimum quantity required for wholesale pricing.', 'aqualuxe'),
            'type' => 'number',
            'custom_attributes' => array(
                'min' => '1'
            )
        ));

        echo '</div>';
    }

    /**
     * Save wholesale pricing fields
     */
    public function save_wholesale_pricing_fields($post_id) {
        $wholesale_price = isset($_POST['_wholesale_price']) ? sanitize_text_field($_POST['_wholesale_price']) : '';
        $wholesale_min_qty = isset($_POST['_wholesale_min_quantity']) ? sanitize_text_field($_POST['_wholesale_min_quantity']) : '';

        update_post_meta($post_id, '_wholesale_price', $wholesale_price);
        update_post_meta($post_id, '_wholesale_min_quantity', $wholesale_min_qty);
    }

    /**
     * Display wholesale pricing for wholesale customers
     */
    public function wholesale_pricing_display($price_html, $product) {
        if (!current_user_can('view_wholesale_prices')) {
            return $price_html;
        }

        $wholesale_price = get_post_meta($product->get_id(), '_wholesale_price', true);
        $wholesale_min_qty = get_post_meta($product->get_id(), '_wholesale_min_quantity', true);

        if ($wholesale_price) {
            $wholesale_html = '<div class="wholesale-pricing">';
            $wholesale_html .= '<span class="retail-price">' . __('Retail: ', 'aqualuxe') . $price_html . '</span>';
            $wholesale_html .= '<span class="wholesale-price">' . __('Wholesale: ', 'aqualuxe') . wc_price($wholesale_price) . '</span>';
            
            if ($wholesale_min_qty) {
                $wholesale_html .= '<span class="wholesale-min-qty">' . sprintf(__('(Min qty: %s)', 'aqualuxe'), $wholesale_min_qty) . '</span>';
            }
            
            $wholesale_html .= '</div>';
            
            return $wholesale_html;
        }

        return $price_html;
    }

    /**
     * Wholesale form shortcode
     */
    public function wholesale_form_shortcode($atts) {
        ob_start();
        $this->load_template('wholesale-application-form');
        return ob_get_clean();
    }
}