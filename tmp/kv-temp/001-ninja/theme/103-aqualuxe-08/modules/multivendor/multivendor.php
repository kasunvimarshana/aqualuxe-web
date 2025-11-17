<?php
/**
 * Multivendor Marketplace Module
 * 
 * Provides multivendor marketplace functionality with vendor management,
 * commission tracking, and vendor-specific features.
 * 
 * @package AquaLuxe
 * @subpackage Modules
 * @since 1.0.0
 * @author AquaLuxe Development Team
 */

namespace AquaLuxe\Modules;

use AquaLuxe\Core\Base_Module;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Multivendor Marketplace Module Class
 *
 * Responsible for:
 * - Vendor registration and management
 * - Commission tracking and payments
 * - Vendor-specific product management
 * - Multi-vendor order processing
 * - Vendor dashboard and analytics
 * - Review and rating system for vendors
 *
 * @since 1.0.0
 */
class Multivendor extends Base_Module {

    /**
     * Vendor roles and capabilities
     *
     * @var array
     */
    private $vendor_capabilities = array(
        'manage_vendor_products',
        'edit_vendor_products',
        'delete_vendor_products',
        'view_vendor_orders',
        'manage_vendor_inventory',
        'view_vendor_analytics',
        'manage_vendor_profile',
    );

    /**
     * Get the module name.
     *
     * @return string The module name.
     */
    public function get_name(): string {
        return 'Multivendor Marketplace';
    }

    /**
     * Get the module description.
     *
     * @return string The module description.
     */
    public function get_description(): string {
        return 'Complete multivendor marketplace functionality with vendor management, commission tracking, and vendor-specific features.';
    }

    /**
     * Get the module version.
     *
     * @return string The module version.
     */
    public function get_version(): string {
        return '1.0.0';
    }

    /**
     * Get the module dependencies.
     *
     * @return array Array of required dependencies.
     */
    public function get_dependencies(): array {
        return array( 'woocommerce' ); // Requires WooCommerce
    }

    /**
     * Module-specific setup.
     *
     * @return void
     */
    protected function setup(): void {
        // Only setup if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            $this->log( 'WooCommerce not found, multivendor module disabled', 'warning' );
            return;
        }

        $this->register_vendor_role();
        $this->setup_hooks();

        $this->log( 'Multivendor module setup complete' );
    }

    /**
     * Setup WordPress hooks.
     *
     * @return void
     */
    private function setup_hooks(): void {
        // Vendor registration
        add_action( 'init', array( $this, 'handle_vendor_registration' ) );
        add_action( 'user_register', array( $this, 'process_vendor_registration' ) );

        // Product management
        add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_vendor_product_tab' ) );
        add_action( 'woocommerce_product_data_panels', array( $this, 'vendor_product_panel' ) );
        add_action( 'woocommerce_process_product_meta', array( $this, 'save_vendor_product_data' ) );

        // Order management
        add_action( 'woocommerce_order_status_completed', array( $this, 'process_vendor_commission' ) );
        add_filter( 'woocommerce_order_item_meta_end', array( $this, 'display_vendor_info' ), 10, 4 );

        // Admin hooks
        if ( is_admin() ) {
            add_action( 'admin_menu', array( $this, 'add_admin_menus' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
        }

        // Frontend hooks
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
        add_action( 'init', array( $this, 'add_vendor_endpoints' ) );
        add_filter( 'query_vars', array( $this, 'add_query_vars' ) );
        add_action( 'template_redirect', array( $this, 'vendor_dashboard_template' ) );

        // AJAX handlers
        add_action( 'wp_ajax_aqualuxe_vendor_application', array( $this, 'handle_vendor_application' ) );
        add_action( 'wp_ajax_nopriv_aqualuxe_vendor_application', array( $this, 'handle_vendor_application' ) );

        // Customizer integration
        add_action( 'customize_register', array( $this, 'register_customizer_settings' ) );
    }

    /**
     * Called on WordPress 'init' action.
     *
     * @return void
     */
    public function on_init(): void {
        // Register custom post types for vendor-specific content
        $this->register_vendor_post_types();
        
        // Setup rewrite rules for vendor pages
        $this->setup_vendor_rewrite_rules();
    }

    /**
     * Enqueue frontend assets.
     *
     * @return void
     */
    public function enqueue_assets(): void {
        // Only enqueue on vendor-related pages
        if ( $this->is_vendor_page() ) {
            wp_enqueue_script(
                'aqualuxe-multivendor',
                AQUALUXE_ASSETS_URI . '/dist/js/multivendor.js',
                array( 'aqualuxe-main', 'jquery' ),
                AQUALUXE_VERSION,
                true
            );

            wp_localize_script( 'aqualuxe-multivendor', 'aqualuxe_multivendor', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'aqualuxe_multivendor_nonce' ),
                'strings'  => array(
                    'applying'         => esc_html__( 'Submitting application...', 'aqualuxe' ),
                    'application_sent' => esc_html__( 'Application submitted successfully!', 'aqualuxe' ),
                    'error_occurred'   => esc_html__( 'An error occurred. Please try again.', 'aqualuxe' ),
                ),
            ) );
        }
    }

    /**
     * Register vendor user role.
     *
     * @return void
     */
    private function register_vendor_role(): void {
        add_role(
            'vendor',
            esc_html__( 'Vendor', 'aqualuxe' ),
            array_fill_keys( $this->vendor_capabilities, true )
        );

        // Add capabilities to administrator
        $admin_role = get_role( 'administrator' );
        if ( $admin_role ) {
            foreach ( $this->vendor_capabilities as $cap ) {
                $admin_role->add_cap( $cap );
            }
            $admin_role->add_cap( 'manage_vendors' );
            $admin_role->add_cap( 'edit_vendor_commissions' );
        }
    }

    /**
     * Register vendor-specific post types.
     *
     * @return void
     */
    private function register_vendor_post_types(): void {
        // Vendor profile post type
        register_post_type( 'vendor_profile', array(
            'labels' => array(
                'name'          => esc_html__( 'Vendor Profiles', 'aqualuxe' ),
                'singular_name' => esc_html__( 'Vendor Profile', 'aqualuxe' ),
                'add_new'       => esc_html__( 'Add New Vendor', 'aqualuxe' ),
                'add_new_item'  => esc_html__( 'Add New Vendor Profile', 'aqualuxe' ),
                'edit_item'     => esc_html__( 'Edit Vendor Profile', 'aqualuxe' ),
                'new_item'      => esc_html__( 'New Vendor Profile', 'aqualuxe' ),
                'view_item'     => esc_html__( 'View Vendor Profile', 'aqualuxe' ),
                'search_items'  => esc_html__( 'Search Vendors', 'aqualuxe' ),
            ),
            'public'       => true,
            'show_ui'      => true,
            'show_in_menu' => 'aqualuxe-multivendor',
            'supports'     => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
            'has_archive'  => true,
            'rewrite'      => array( 'slug' => 'vendors' ),
        ) );

        // Vendor commission post type
        register_post_type( 'vendor_commission', array(
            'labels' => array(
                'name'          => esc_html__( 'Vendor Commissions', 'aqualuxe' ),
                'singular_name' => esc_html__( 'Vendor Commission', 'aqualuxe' ),
            ),
            'public'       => false,
            'show_ui'      => true,
            'show_in_menu' => 'aqualuxe-multivendor',
            'supports'     => array( 'title', 'custom-fields' ),
        ) );
    }

    /**
     * Check if current page is vendor-related.
     *
     * @return bool True if vendor page.
     */
    private function is_vendor_page(): bool {
        global $wp_query;

        return is_page_template( 'templates/vendor-dashboard.php' ) ||
               is_singular( 'vendor_profile' ) ||
               is_post_type_archive( 'vendor_profile' ) ||
               ( isset( $wp_query->query_vars['vendor_dashboard'] ) );
    }

    /**
     * Add vendor endpoints for frontend dashboard.
     *
     * @return void
     */
    public function add_vendor_endpoints(): void {
        add_rewrite_endpoint( 'vendor-dashboard', EP_ROOT | EP_PAGES );
        add_rewrite_endpoint( 'vendor-products', EP_ROOT | EP_PAGES );
        add_rewrite_endpoint( 'vendor-orders', EP_ROOT | EP_PAGES );
        add_rewrite_endpoint( 'vendor-analytics', EP_ROOT | EP_PAGES );
    }

    /**
     * Add query vars for vendor endpoints.
     *
     * @param array $vars Query vars.
     * @return array Modified query vars.
     */
    public function add_query_vars( array $vars ): array {
        $vendor_vars = array(
            'vendor-dashboard',
            'vendor-products',
            'vendor-orders',
            'vendor-analytics',
        );

        return array_merge( $vars, $vendor_vars );
    }

    /**
     * Handle vendor dashboard template loading.
     *
     * @return void
     */
    public function vendor_dashboard_template(): void {
        global $wp_query;

        if ( isset( $wp_query->query_vars['vendor-dashboard'] ) ) {
            if ( ! $this->is_vendor_or_admin() ) {
                wp_redirect( wp_login_url( home_url( '/vendor-dashboard/' ) ) );
                exit;
            }

            $template = locate_template( 'templates/vendor-dashboard.php' );
            if ( $template ) {
                include $template;
                exit;
            }
        }
    }

    /**
     * Check if current user is vendor or admin.
     *
     * @return bool True if vendor or admin.
     */
    private function is_vendor_or_admin(): bool {
        return current_user_can( 'manage_vendor_products' ) || current_user_can( 'manage_options' );
    }

    /**
     * Handle vendor application AJAX.
     *
     * @return void
     */
    public function handle_vendor_application(): void {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'aqualuxe_multivendor_nonce' ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Security check failed', 'aqualuxe' ) ) );
        }

        // Validate required fields
        $required_fields = array( 'business_name', 'business_email', 'business_description' );
        foreach ( $required_fields as $field ) {
            if ( empty( $_POST[ $field ] ) ) {
                wp_send_json_error( array( 
                    'message' => sprintf( 
                        esc_html__( 'Please fill in the %s field', 'aqualuxe' ), 
                        $field 
                    )
                ) );
            }
        }

        // Process application
        $application_data = array(
            'business_name'        => sanitize_text_field( $_POST['business_name'] ),
            'business_email'       => sanitize_email( $_POST['business_email'] ),
            'business_description' => sanitize_textarea_field( $_POST['business_description'] ),
            'business_phone'       => sanitize_text_field( $_POST['business_phone'] ?? '' ),
            'business_website'     => esc_url_raw( $_POST['business_website'] ?? '' ),
            'user_id'              => get_current_user_id(),
            'application_date'     => current_time( 'mysql' ),
            'status'               => 'pending',
        );

        // Save application
        $application_id = wp_insert_post( array(
            'post_title'   => sprintf( 'Vendor Application - %s', $application_data['business_name'] ),
            'post_type'    => 'vendor_application',
            'post_status'  => 'private',
            'meta_input'   => $application_data,
        ) );

        if ( $application_id ) {
            // Send notification email to admin
            $this->send_vendor_application_notification( $application_id, $application_data );

            wp_send_json_success( array(
                'message' => esc_html__( 'Your vendor application has been submitted successfully. We will review it and get back to you soon.', 'aqualuxe' ),
            ) );
        } else {
            wp_send_json_error( array(
                'message' => esc_html__( 'Failed to submit application. Please try again.', 'aqualuxe' ),
            ) );
        }
    }

    /**
     * Send vendor application notification email.
     *
     * @param int   $application_id Application post ID.
     * @param array $application_data Application data.
     * @return void
     */
    private function send_vendor_application_notification( int $application_id, array $application_data ): void {
        $admin_email = get_option( 'admin_email' );
        $subject = sprintf( 'New Vendor Application: %s', $application_data['business_name'] );
        
        $message = sprintf(
            "A new vendor application has been submitted:\n\n" .
            "Business Name: %s\n" .
            "Email: %s\n" .
            "Phone: %s\n" .
            "Website: %s\n" .
            "Description: %s\n\n" .
            "Review application: %s",
            $application_data['business_name'],
            $application_data['business_email'],
            $application_data['business_phone'],
            $application_data['business_website'],
            $application_data['business_description'],
            admin_url( 'post.php?post=' . $application_id . '&action=edit' )
        );

        wp_mail( $admin_email, $subject, $message );
    }

    /**
     * Get service name for dependency injection.
     *
     * @return string Service name.
     */
    public function get_service_name(): string {
        return 'multivendor';
    }
}