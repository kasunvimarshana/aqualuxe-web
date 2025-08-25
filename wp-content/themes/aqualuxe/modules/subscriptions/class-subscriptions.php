<?php
/**
 * Subscriptions Module
 *
 * @package AquaLuxe
 * @subpackage Modules\Subscriptions
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Subscriptions;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Subscriptions Module Class
 * 
 * This class handles subscription and membership functionality for aquatic businesses.
 */
class Subscriptions {
    /**
     * Instance of this class
     *
     * @var Subscriptions
     */
    private static $instance = null;

    /**
     * Module slug
     *
     * @var string
     */
    private $slug = 'subscriptions';

    /**
     * Constructor
     */
    public function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Include required files
     *
     * @return void
     */
    private function includes() {
        // Include admin files only in admin
        if ( is_admin() ) {
            $admin_file = __DIR__ . '/admin/class-admin.php';
            if ( file_exists( $admin_file ) ) {
                require_once $admin_file;
            }
        }

        // Include frontend files
        $frontend_files = array(
            __DIR__ . '/inc/class-subscription.php',
            __DIR__ . '/inc/class-membership.php',
            __DIR__ . '/inc/class-member.php',
            __DIR__ . '/inc/class-subscription-shortcodes.php',
            __DIR__ . '/inc/class-subscription-widgets.php',
        );
        foreach ( $frontend_files as $file ) {
            if ( file_exists( $file ) ) {
                require_once $file;
            }
        }

        // Include WooCommerce integration if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) ) {
            $woo_file = __DIR__ . '/inc/class-woocommerce-integration.php';
            if ( file_exists( $woo_file ) ) {
                require_once $woo_file;
            }
        }
    }
    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Register post types and taxonomies
        add_action( 'init', [ $this, 'register_post_types' ] );
        add_action( 'init', [ $this, 'register_taxonomies' ] );

        // Register shortcodes
        add_action( 'init', [ $this, 'register_shortcodes' ] );

        // Register widgets
        add_action( 'widgets_init', [ $this, 'register_widgets' ] );

        // Register REST API endpoints
        add_action( 'rest_api_init', [ $this, 'register_rest_routes' ] );

        // Enqueue scripts and styles
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

        // Add menu items
        add_action( 'admin_menu', [ $this, 'add_menu_items' ] );

        // Add settings
        add_action( 'admin_init', [ $this, 'register_settings' ] );

        // Add meta boxes
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );

        // Save post meta
        add_action( 'save_post', [ $this, 'save_meta_box_data' ] );

        // User profile fields
        add_action( 'show_user_profile', [ $this, 'add_user_profile_fields' ] );
        add_action( 'edit_user_profile', [ $this, 'add_user_profile_fields' ] );
        add_action( 'personal_options_update', [ $this, 'save_user_profile_fields' ] );
        add_action( 'edit_user_profile_update', [ $this, 'save_user_profile_fields' ] );

        // Content restriction
        add_action( 'template_redirect', [ $this, 'restrict_content' ] );
        add_filter( 'the_content', [ $this, 'filter_restricted_content' ] );

        // Subscription expiration check
        add_action( 'aqualuxe_daily_cron', [ $this, 'check_subscription_expirations' ] );

        // Schedule daily cron if not already scheduled
        if ( ! wp_next_scheduled( 'aqualuxe_daily_cron' ) ) {
            wp_schedule_event( time(), 'daily', 'aqualuxe_daily_cron' );
        }
    }

    /**
     * Register post types
     *
     * @return void
     */
    public function register_post_types() {
        // Register subscription plan post type
        register_post_type(
            'aqlx_subscription',
            [
                'labels'              => [
                    'name'                  => __( 'Subscription Plans', 'aqualuxe' ),
                    'singular_name'         => __( 'Subscription Plan', 'aqualuxe' ),
                    'menu_name'             => __( 'Subscriptions', 'aqualuxe' ),
                    'name_admin_bar'        => __( 'Subscription Plan', 'aqualuxe' ),
                    'add_new'               => __( 'Add New', 'aqualuxe' ),
                    'add_new_item'          => __( 'Add New Subscription Plan', 'aqualuxe' ),
                    'new_item'              => __( 'New Subscription Plan', 'aqualuxe' ),
                    'edit_item'             => __( 'Edit Subscription Plan', 'aqualuxe' ),
                    'view_item'             => __( 'View Subscription Plan', 'aqualuxe' ),
                    'all_items'             => __( 'All Subscription Plans', 'aqualuxe' ),
                    'search_items'          => __( 'Search Subscription Plans', 'aqualuxe' ),
                    'parent_item_colon'     => __( 'Parent Subscription Plans:', 'aqualuxe' ),
                    'not_found'             => __( 'No subscription plans found.', 'aqualuxe' ),
                    'not_found_in_trash'    => __( 'No subscription plans found in Trash.', 'aqualuxe' ),
                    'featured_image'        => __( 'Subscription Plan Image', 'aqualuxe' ),
                    'set_featured_image'    => __( 'Set subscription plan image', 'aqualuxe' ),
                    'remove_featured_image' => __( 'Remove subscription plan image', 'aqualuxe' ),
                    'use_featured_image'    => __( 'Use as subscription plan image', 'aqualuxe' ),
                ],
                'public'              => true,
                'publicly_queryable'  => true,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'query_var'           => true,
                'rewrite'             => [ 'slug' => 'subscription-plan' ],
                'capability_type'     => 'post',
                'has_archive'         => true,
                'hierarchical'        => false,
                'menu_position'       => null,
                'menu_icon'           => 'dashicons-id-alt',
                'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
                'show_in_rest'        => true,
            ]
        );

        // Register membership post type
        register_post_type(
            'aqlx_membership',
            [
                'labels'              => [
                    'name'                  => __( 'Memberships', 'aqualuxe' ),
                    'singular_name'         => __( 'Membership', 'aqualuxe' ),
                    'menu_name'             => __( 'Memberships', 'aqualuxe' ),
                    'name_admin_bar'        => __( 'Membership', 'aqualuxe' ),
                    'add_new'               => __( 'Add New', 'aqualuxe' ),
                    'add_new_item'          => __( 'Add New Membership', 'aqualuxe' ),
                    'new_item'              => __( 'New Membership', 'aqualuxe' ),
                    'edit_item'             => __( 'Edit Membership', 'aqualuxe' ),
                    'view_item'             => __( 'View Membership', 'aqualuxe' ),
                    'all_items'             => __( 'All Memberships', 'aqualuxe' ),
                    'search_items'          => __( 'Search Memberships', 'aqualuxe' ),
                    'parent_item_colon'     => __( 'Parent Memberships:', 'aqualuxe' ),
                    'not_found'             => __( 'No memberships found.', 'aqualuxe' ),
                    'not_found_in_trash'    => __( 'No memberships found in Trash.', 'aqualuxe' ),
                ],
                'public'              => false,
                'publicly_queryable'  => false,
                'show_ui'             => true,
                'show_in_menu'        => 'edit.php?post_type=aqualuxe_subscription',
                'query_var'           => false,
                'rewrite'             => false,
                'capability_type'     => 'post',
                'has_archive'         => false,
                'hierarchical'        => false,
                'menu_position'       => null,
                'supports'            => [ 'title', 'custom-fields' ],
                'show_in_rest'        => false,
            ]
        );
    }

    /**
     * Register taxonomies
     *
     * @return void
     */
    public function register_taxonomies() {
        // Register subscription category taxonomy
        register_taxonomy(
            'subscription_category',
            [ 'aqualuxe_subscription' ],
            [
                'labels'            => [
                    'name'                       => __( 'Subscription Categories', 'aqualuxe' ),
                    'singular_name'              => __( 'Subscription Category', 'aqualuxe' ),
                    'search_items'               => __( 'Search Subscription Categories', 'aqualuxe' ),
                    'popular_items'              => __( 'Popular Subscription Categories', 'aqualuxe' ),
                    'all_items'                  => __( 'All Subscription Categories', 'aqualuxe' ),
                    'parent_item'                => __( 'Parent Subscription Category', 'aqualuxe' ),
                    'parent_item_colon'          => __( 'Parent Subscription Category:', 'aqualuxe' ),
                    'edit_item'                  => __( 'Edit Subscription Category', 'aqualuxe' ),
                    'update_item'                => __( 'Update Subscription Category', 'aqualuxe' ),
                    'add_new_item'               => __( 'Add New Subscription Category', 'aqualuxe' ),
                    'new_item_name'              => __( 'New Subscription Category Name', 'aqualuxe' ),
                    'separate_items_with_commas' => __( 'Separate subscription categories with commas', 'aqualuxe' ),
                    'add_or_remove_items'        => __( 'Add or remove subscription categories', 'aqualuxe' ),
                    'choose_from_most_used'      => __( 'Choose from the most used subscription categories', 'aqualuxe' ),
                    'not_found'                  => __( 'No subscription categories found.', 'aqualuxe' ),
                    'menu_name'                  => __( 'Categories', 'aqualuxe' ),
                ],
                'hierarchical'      => true,
                'public'            => true,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => 'subscription-category' ],
                'show_in_rest'      => true,
            ]
        );

        // Register subscription tag taxonomy
        register_taxonomy(
            'subscription_tag',
            [ 'aqualuxe_subscription' ],
            [
                'labels'            => [
                    'name'                       => __( 'Subscription Tags', 'aqualuxe' ),
                    'singular_name'              => __( 'Subscription Tag', 'aqualuxe' ),
                    'search_items'               => __( 'Search Subscription Tags', 'aqualuxe' ),
                    'popular_items'              => __( 'Popular Subscription Tags', 'aqualuxe' ),
                    'all_items'                  => __( 'All Subscription Tags', 'aqualuxe' ),
                    'parent_item'                => __( 'Parent Subscription Tag', 'aqualuxe' ),
                    'parent_item_colon'          => __( 'Parent Subscription Tag:', 'aqualuxe' ),
                    'edit_item'                  => __( 'Edit Subscription Tag', 'aqualuxe' ),
                    'update_item'                => __( 'Update Subscription Tag', 'aqualuxe' ),
                    'add_new_item'               => __( 'Add New Subscription Tag', 'aqualuxe' ),
                    'new_item_name'              => __( 'New Subscription Tag Name', 'aqualuxe' ),
                    'separate_items_with_commas' => __( 'Separate subscription tags with commas', 'aqualuxe' ),
                    'add_or_remove_items'        => __( 'Add or remove subscription tags', 'aqualuxe' ),
                    'choose_from_most_used'      => __( 'Choose from the most used subscription tags', 'aqualuxe' ),
                    'not_found'                  => __( 'No subscription tags found.', 'aqualuxe' ),
                    'menu_name'                  => __( 'Tags', 'aqualuxe' ),
                ],
                'hierarchical'      => false,
                'public'            => true,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => 'subscription-tag' ],
                'show_in_rest'      => true,
            ]
        );

        // Register access level taxonomy
        register_taxonomy(
            'access_level',
            [ 'aqualuxe_subscription', 'post', 'page', 'product' ],
            [
                'labels'            => [
                    'name'                       => __( 'Access Levels', 'aqualuxe' ),
                    'singular_name'              => __( 'Access Level', 'aqualuxe' ),
                    'search_items'               => __( 'Search Access Levels', 'aqualuxe' ),
                    'popular_items'              => __( 'Popular Access Levels', 'aqualuxe' ),
                    'all_items'                  => __( 'All Access Levels', 'aqualuxe' ),
                    'parent_item'                => __( 'Parent Access Level', 'aqualuxe' ),
                    'parent_item_colon'          => __( 'Parent Access Level:', 'aqualuxe' ),
                    'edit_item'                  => __( 'Edit Access Level', 'aqualuxe' ),
                    'update_item'                => __( 'Update Access Level', 'aqualuxe' ),
                    'add_new_item'               => __( 'Add New Access Level', 'aqualuxe' ),
                    'new_item_name'              => __( 'New Access Level Name', 'aqualuxe' ),
                    'separate_items_with_commas' => __( 'Separate access levels with commas', 'aqualuxe' ),
                    'add_or_remove_items'        => __( 'Add or remove access levels', 'aqualuxe' ),
                    'choose_from_most_used'      => __( 'Choose from the most used access levels', 'aqualuxe' ),
                    'not_found'                  => __( 'No access levels found.', 'aqualuxe' ),
                    'menu_name'                  => __( 'Access Levels', 'aqualuxe' ),
                ],
                'hierarchical'      => true,
                'public'            => true,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => 'access-level' ],
                'show_in_rest'      => true,
            ]
        );
    }

    /**
     * Register shortcodes
     *
     * @return void
     */
    public function register_shortcodes() {
        if ( class_exists( 'Subscription_Shortcodes' ) ) {
            $shortcodes = new Subscription_Shortcodes();
            $shortcodes->register_shortcodes();
        } else {
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                error_log( 'Subscription_Shortcodes class not found. Shortcodes will not be registered.' );
            }
        }
    }

    /**
     * Register widgets
     *
     * @return void
     */
    public function register_widgets() {
        // Widgets are registered in the Subscription_Widgets class
    }

    /**
     * Register REST API endpoints
     *
     * @return void
     */
    public function register_rest_routes() {
        // Register REST API endpoints if needed
    }

    /**
     * Enqueue scripts and styles
     *
     * @return void
     */
    public function enqueue_scripts() {
        // Enqueue frontend scripts and styles
        wp_enqueue_style( 'aqualuxe-subscriptions', AQUALUXE_MODULES_DIR . $this->slug . '/assets/css/subscriptions.css', [], AQUALUXE_VERSION );
        wp_enqueue_script( 'aqualuxe-subscriptions', AQUALUXE_MODULES_DIR . $this->slug . '/assets/js/subscriptions.js', [ 'jquery' ], AQUALUXE_VERSION, true );

        // Localize script
        wp_localize_script(
            'aqualuxe-subscriptions',
            'aqualuxeSubscriptions',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'aqualuxe-subscriptions-nonce' ),
                'messages' => [
                    'confirmCancel' => __( 'Are you sure you want to cancel your subscription? This action cannot be undone.', 'aqualuxe' ),
                    'processingRequest' => __( 'Processing your request...', 'aqualuxe' ),
                    'errorOccurred' => __( 'An error occurred. Please try again.', 'aqualuxe' ),
                ],
            ]
        );
    }

    /**
     * Enqueue admin scripts and styles
     *
     * @return void
     */
    public function admin_enqueue_scripts() {
        // Enqueue admin scripts and styles
        wp_enqueue_style( 'aqualuxe-subscriptions-admin', AQUALUXE_MODULES_DIR . $this->slug . '/assets/css/admin.css', [], AQUALUXE_VERSION );
        wp_enqueue_script( 'aqualuxe-subscriptions-admin', AQUALUXE_MODULES_DIR . $this->slug . '/assets/js/admin.js', [ 'jquery' ], AQUALUXE_VERSION, true );

        // Localize script
        wp_localize_script(
            'aqualuxe-subscriptions-admin',
            'aqualuxeSubscriptionsAdmin',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'aqualuxe-subscriptions-admin-nonce' ),
            ]
        );
    }

    /**
     * Add menu items
     *
     * @return void
     */
    public function add_menu_items() {
        // Add settings page
        add_submenu_page(
            'edit.php?post_type=aqualuxe_subscription',
            __( 'Subscription Settings', 'aqualuxe' ),
            __( 'Settings', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-subscription-settings',
            [ $this, 'render_settings_page' ]
        );

        // Add members page
        add_submenu_page(
            'edit.php?post_type=aqualuxe_subscription',
            __( 'Members', 'aqualuxe' ),
            __( 'Members', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-subscription-members',
            [ $this, 'render_members_page' ]
        );

        // Add reports page
        add_submenu_page(
            'edit.php?post_type=aqualuxe_subscription',
            __( 'Subscription Reports', 'aqualuxe' ),
            __( 'Reports', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-subscription-reports',
            [ $this, 'render_reports_page' ]
        );
    }

    /**
     * Register settings
     *
     * @return void
     */
    public function register_settings() {
        // Register settings
        register_setting( 'aqualuxe_subscription_settings', 'aqualuxe_subscription_settings' );

        // Add settings sections
        add_settings_section(
            'aqualuxe_subscription_general',
            __( 'General Settings', 'aqualuxe' ),
            [ $this, 'render_general_settings_section' ],
            'aqualuxe_subscription_settings'
        );

        add_settings_section(
            'aqualuxe_subscription_content',
            __( 'Content Restriction Settings', 'aqualuxe' ),
            [ $this, 'render_content_settings_section' ],
            'aqualuxe_subscription_settings'
        );

        add_settings_section(
            'aqualuxe_subscription_payment',
            __( 'Payment Settings', 'aqualuxe' ),
            [ $this, 'render_payment_settings_section' ],
            'aqualuxe_subscription_settings'
        );

        add_settings_section(
            'aqualuxe_subscription_emails',
            __( 'Email Settings', 'aqualuxe' ),
            [ $this, 'render_email_settings_section' ],
            'aqualuxe_subscription_settings'
        );

        // Add settings fields
        add_settings_field(
            'account_page',
            __( 'Account Page', 'aqualuxe' ),
            [ $this, 'render_account_page_field' ],
            'aqualuxe_subscription_settings',
            'aqualuxe_subscription_general'
        );

        add_settings_field(
            'pricing_page',
            __( 'Pricing Page', 'aqualuxe' ),
            [ $this, 'render_pricing_page_field' ],
            'aqualuxe_subscription_settings',
            'aqualuxe_subscription_general'
        );

        add_settings_field(
            'login_redirect',
            __( 'Login Redirect', 'aqualuxe' ),
            [ $this, 'render_login_redirect_field' ],
            'aqualuxe_subscription_settings',
            'aqualuxe_subscription_general'
        );

        add_settings_field(
            'restricted_content_message',
            __( 'Restricted Content Message', 'aqualuxe' ),
            [ $this, 'render_restricted_content_message_field' ],
            'aqualuxe_subscription_settings',
            'aqualuxe_subscription_content'
        );

        add_settings_field(
            'restricted_content_redirect',
            __( 'Restricted Content Redirect', 'aqualuxe' ),
            [ $this, 'render_restricted_content_redirect_field' ],
            'aqualuxe_subscription_settings',
            'aqualuxe_subscription_content'
        );

        add_settings_field(
            'payment_gateway',
            __( 'Payment Gateway', 'aqualuxe' ),
            [ $this, 'render_payment_gateway_field' ],
            'aqualuxe_subscription_settings',
            'aqualuxe_subscription_payment'
        );

        add_settings_field(
            'currency',
            __( 'Currency', 'aqualuxe' ),
            [ $this, 'render_currency_field' ],
            'aqualuxe_subscription_settings',
            'aqualuxe_subscription_payment'
        );

        add_settings_field(
            'welcome_email',
            __( 'Welcome Email', 'aqualuxe' ),
            [ $this, 'render_welcome_email_field' ],
            'aqualuxe_subscription_settings',
            'aqualuxe_subscription_emails'
        );

        add_settings_field(
            'expiration_reminder_email',
            __( 'Expiration Reminder Email', 'aqualuxe' ),
            [ $this, 'render_expiration_reminder_email_field' ],
            'aqualuxe_subscription_settings',
            'aqualuxe_subscription_emails'
        );

        add_settings_field(
            'expired_email',
            __( 'Expired Email', 'aqualuxe' ),
            [ $this, 'render_expired_email_field' ],
            'aqualuxe_subscription_settings',
            'aqualuxe_subscription_emails'
        );

        add_settings_field(
            'cancellation_email',
            __( 'Cancellation Email', 'aqualuxe' ),
            [ $this, 'render_cancellation_email_field' ],
            'aqualuxe_subscription_settings',
            'aqualuxe_subscription_emails'
        );
    }

    /**
     * Add meta boxes
     *
     * @return void
     */
    public function add_meta_boxes() {
        // Add meta boxes for subscription plans
        add_meta_box(
            'aqualuxe-subscription-details',
            __( 'Subscription Details', 'aqualuxe' ),
            [ $this, 'render_subscription_details_meta_box' ],
            'aqualuxe_subscription',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe-subscription-features',
            __( 'Subscription Features', 'aqualuxe' ),
            [ $this, 'render_subscription_features_meta_box' ],
            'aqualuxe_subscription',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe-subscription-restrictions',
            __( 'Content Restrictions', 'aqualuxe' ),
            [ $this, 'render_subscription_restrictions_meta_box' ],
            'aqualuxe_subscription',
            'side',
            'default'
        );

        // Add meta boxes for memberships
        add_meta_box(
            'aqualuxe-membership-details',
            __( 'Membership Details', 'aqualuxe' ),
            [ $this, 'render_membership_details_meta_box' ],
            'aqualuxe_membership',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe-membership-history',
            __( 'Membership History', 'aqualuxe' ),
            [ $this, 'render_membership_history_meta_box' ],
            'aqualuxe_membership',
            'normal',
            'high'
        );

        // Add meta box for content restriction to supported post types
        $post_types = apply_filters( 'aqualuxe_subscription_restricted_post_types', [ 'post', 'page', 'product' ] );
        foreach ( $post_types as $post_type ) {
            add_meta_box(
                'aqualuxe-content-restriction',
                __( 'Content Restriction', 'aqualuxe' ),
                [ $this, 'render_content_restriction_meta_box' ],
                $post_type,
                'side',
                'default'
            );
        }
    }

    /**
     * Render subscription details meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_subscription_details_meta_box( $post ) {
        wp_nonce_field( 'aqualuxe_subscription_details', 'aqualuxe_subscription_details_nonce' );

        $price = get_post_meta( $post->ID, '_aqualuxe_subscription_price', true );
        $duration = get_post_meta( $post->ID, '_aqualuxe_subscription_duration', true );
        $duration_unit = get_post_meta( $post->ID, '_aqualuxe_subscription_duration_unit', true );
        $trial_enabled = get_post_meta( $post->ID, '_aqualuxe_subscription_trial_enabled', true );
        $trial_duration = get_post_meta( $post->ID, '_aqualuxe_subscription_trial_duration', true );
        $trial_duration_unit = get_post_meta( $post->ID, '_aqualuxe_subscription_trial_duration_unit', true );
        $signup_fee = get_post_meta( $post->ID, '_aqualuxe_subscription_signup_fee', true );
        $max_members = get_post_meta( $post->ID, '_aqualuxe_subscription_max_members', true );
        $product_id = get_post_meta( $post->ID, '_aqualuxe_subscription_product_id', true );

        // Default values
        if ( ! $duration_unit ) {
            $duration_unit = 'month';
        }

        if ( ! $trial_duration_unit ) {
            $trial_duration_unit = 'day';
        }
        ?>
        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-subscription-price"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></label>
            <input type="number" id="aqualuxe-subscription-price" name="aqualuxe_subscription_price" value="<?php echo esc_attr( $price ); ?>" step="0.01" min="0">
            <p class="description"><?php esc_html_e( 'The price of the subscription.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-subscription-duration"><?php esc_html_e( 'Duration', 'aqualuxe' ); ?></label>
            <input type="number" id="aqualuxe-subscription-duration" name="aqualuxe_subscription_duration" value="<?php echo esc_attr( $duration ); ?>" step="1" min="0">
            <select id="aqualuxe-subscription-duration-unit" name="aqualuxe_subscription_duration_unit">
                <option value="day" <?php selected( $duration_unit, 'day' ); ?>><?php esc_html_e( 'Day(s)', 'aqualuxe' ); ?></option>
                <option value="week" <?php selected( $duration_unit, 'week' ); ?>><?php esc_html_e( 'Week(s)', 'aqualuxe' ); ?></option>
                <option value="month" <?php selected( $duration_unit, 'month' ); ?>><?php esc_html_e( 'Month(s)', 'aqualuxe' ); ?></option>
                <option value="year" <?php selected( $duration_unit, 'year' ); ?>><?php esc_html_e( 'Year(s)', 'aqualuxe' ); ?></option>
            </select>
            <p class="description"><?php esc_html_e( 'The duration of the subscription. Leave empty for lifetime access.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-subscription-trial-enabled"><?php esc_html_e( 'Enable Trial', 'aqualuxe' ); ?></label>
            <input type="checkbox" id="aqualuxe-subscription-trial-enabled" name="aqualuxe_subscription_trial_enabled" value="1" <?php checked( $trial_enabled, '1' ); ?>>
            <p class="description"><?php esc_html_e( 'Enable a trial period for this subscription.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row aqualuxe-subscription-trial-fields" <?php echo $trial_enabled ? '' : 'style="display:none;"'; ?>>
            <label for="aqualuxe-subscription-trial-duration"><?php esc_html_e( 'Trial Duration', 'aqualuxe' ); ?></label>
            <input type="number" id="aqualuxe-subscription-trial-duration" name="aqualuxe_subscription_trial_duration" value="<?php echo esc_attr( $trial_duration ); ?>" step="1" min="0">
            <select id="aqualuxe-subscription-trial-duration-unit" name="aqualuxe_subscription_trial_duration_unit">
                <option value="day" <?php selected( $trial_duration_unit, 'day' ); ?>><?php esc_html_e( 'Day(s)', 'aqualuxe' ); ?></option>
                <option value="week" <?php selected( $trial_duration_unit, 'week' ); ?>><?php esc_html_e( 'Week(s)', 'aqualuxe' ); ?></option>
                <option value="month" <?php selected( $trial_duration_unit, 'month' ); ?>><?php esc_html_e( 'Month(s)', 'aqualuxe' ); ?></option>
            </select>
            <p class="description"><?php esc_html_e( 'The duration of the trial period.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-subscription-signup-fee"><?php esc_html_e( 'Signup Fee', 'aqualuxe' ); ?></label>
            <input type="number" id="aqualuxe-subscription-signup-fee" name="aqualuxe_subscription_signup_fee" value="<?php echo esc_attr( $signup_fee ); ?>" step="0.01" min="0">
            <p class="description"><?php esc_html_e( 'One-time fee charged at the beginning of the subscription.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-subscription-max-members"><?php esc_html_e( 'Maximum Members', 'aqualuxe' ); ?></label>
            <input type="number" id="aqualuxe-subscription-max-members" name="aqualuxe_subscription_max_members" value="<?php echo esc_attr( $max_members ); ?>" step="1" min="0">
            <p class="description"><?php esc_html_e( 'Maximum number of members allowed for this subscription. Leave empty for unlimited.', 'aqualuxe' ); ?></p>
        </div>

        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
            <div class="aqualuxe-meta-box-row">
                <label for="aqualuxe-subscription-product-id"><?php esc_html_e( 'WooCommerce Product', 'aqualuxe' ); ?></label>
                <select id="aqualuxe-subscription-product-id" name="aqualuxe_subscription_product_id">
                    <option value=""><?php esc_html_e( 'Select a product', 'aqualuxe' ); ?></option>
                    <?php
                    $products = wc_get_products( [
                        'limit' => -1,
                        'orderby' => 'title',
                        'order' => 'ASC',
                        'return' => 'ids',
                    ] );

                    foreach ( $products as $product_id ) {
                        $product = wc_get_product( $product_id );
                        echo '<option value="' . esc_attr( $product_id ) . '" ' . selected( $product_id, $product_id, false ) . '>' . esc_html( $product->get_name() ) . '</option>';
                    }
                    ?>
                </select>
                <p class="description"><?php esc_html_e( 'Link this subscription to a WooCommerce product.', 'aqualuxe' ); ?></p>
            </div>
        <?php endif; ?>

        <script>
            jQuery(document).ready(function($) {
                $('#aqualuxe-subscription-trial-enabled').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('.aqualuxe-subscription-trial-fields').show();
                    } else {
                        $('.aqualuxe-subscription-trial-fields').hide();
                    }
                });
            });
        </script>
        <?php
    }

    /**
     * Render subscription features meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_subscription_features_meta_box( $post ) {
        wp_nonce_field( 'aqualuxe_subscription_features', 'aqualuxe_subscription_features_nonce' );

        $features = get_post_meta( $post->ID, '_aqualuxe_subscription_features', true );
        if ( ! is_array( $features ) ) {
            $features = [];
        }
        ?>
        <div class="aqualuxe-subscription-features">
            <p><?php esc_html_e( 'Add features for this subscription plan. These will be displayed on the pricing page.', 'aqualuxe' ); ?></p>
            
            <div class="aqualuxe-subscription-features-list">
                <?php
                if ( ! empty( $features ) ) {
                    foreach ( $features as $index => $feature ) {
                        ?>
                        <div class="aqualuxe-subscription-feature-item">
                            <input type="text" name="aqualuxe_subscription_features[]" value="<?php echo esc_attr( $feature ); ?>" class="widefat">
                            <button type="button" class="button button-small aqualuxe-remove-feature"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
                        </div>
                        <?php
                    }
                }
                ?>
                <div class="aqualuxe-subscription-feature-item">
                    <input type="text" name="aqualuxe_subscription_features[]" value="" class="widefat" placeholder="<?php esc_attr_e( 'Add a feature', 'aqualuxe' ); ?>">
                    <button type="button" class="button button-small aqualuxe-remove-feature"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
                </div>
            </div>
            
            <button type="button" class="button aqualuxe-add-feature"><?php esc_html_e( 'Add Feature', 'aqualuxe' ); ?></button>
        </div>

        <script>
            jQuery(document).ready(function($) {
                // Add feature
                $('.aqualuxe-add-feature').on('click', function() {
                    var featureItem = $('.aqualuxe-subscription-feature-item:last').clone();
                    featureItem.find('input').val('');
                    $('.aqualuxe-subscription-features-list').append(featureItem);
                });

                // Remove feature
                $(document).on('click', '.aqualuxe-remove-feature', function() {
                    var featuresCount = $('.aqualuxe-subscription-feature-item').length;
                    if (featuresCount > 1) {
                        $(this).closest('.aqualuxe-subscription-feature-item').remove();
                    } else {
                        $(this).closest('.aqualuxe-subscription-feature-item').find('input').val('');
                    }
                });
            });
        </script>
        <?php
    }

    /**
     * Render subscription restrictions meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_subscription_restrictions_meta_box( $post ) {
        wp_nonce_field( 'aqualuxe_subscription_restrictions', 'aqualuxe_subscription_restrictions_nonce' );

        $access_levels = get_terms( [
            'taxonomy' => 'access_level',
            'hide_empty' => false,
        ] );

        $selected_levels = wp_get_object_terms( $post->ID, 'access_level', [ 'fields' => 'ids' ] );
        ?>
        <p><?php esc_html_e( 'Select the access levels that this subscription grants.', 'aqualuxe' ); ?></p>
        
        <?php if ( ! empty( $access_levels ) && ! is_wp_error( $access_levels ) ) : ?>
            <ul class="aqualuxe-access-levels-list">
                <?php foreach ( $access_levels as $level ) : ?>
                    <li>
                        <label>
                            <input type="checkbox" name="aqualuxe_subscription_access_levels[]" value="<?php echo esc_attr( $level->term_id ); ?>" <?php checked( in_array( $level->term_id, $selected_levels ) ); ?>>
                            <?php echo esc_html( $level->name ); ?>
                        </label>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p><?php esc_html_e( 'No access levels found.', 'aqualuxe' ); ?></p>
            <p><a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=access_level&post_type=aqualuxe_subscription' ) ); ?>"><?php esc_html_e( 'Create access levels', 'aqualuxe' ); ?></a></p>
        <?php endif; ?>
        <?php
    }

    /**
     * Render membership details meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_membership_details_meta_box( $post ) {
        wp_nonce_field( 'aqualuxe_membership_details', 'aqualuxe_membership_details_nonce' );

        $user_id = get_post_meta( $post->ID, '_aqualuxe_membership_user_id', true );
        $subscription_id = get_post_meta( $post->ID, '_aqualuxe_membership_subscription_id', true );
        $status = get_post_meta( $post->ID, '_aqualuxe_membership_status', true );
        $start_date = get_post_meta( $post->ID, '_aqualuxe_membership_start_date', true );
        $end_date = get_post_meta( $post->ID, '_aqualuxe_membership_end_date', true );
        $order_id = get_post_meta( $post->ID, '_aqualuxe_membership_order_id', true );
        $auto_renew = get_post_meta( $post->ID, '_aqualuxe_membership_auto_renew', true );

        // Default values
        if ( ! $status ) {
            $status = 'active';
        }

        // Get users
        $users = get_users( [
            'orderby' => 'display_name',
            'order' => 'ASC',
        ] );

        // Get subscription plans
        $subscriptions = get_posts( [
            'post_type' => 'aqualuxe_subscription',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ] );
        ?>
        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-membership-user-id"><?php esc_html_e( 'User', 'aqualuxe' ); ?></label>
            <select id="aqualuxe-membership-user-id" name="aqualuxe_membership_user_id" required>
                <option value=""><?php esc_html_e( 'Select a user', 'aqualuxe' ); ?></option>
                <?php foreach ( $users as $user ) : ?>
                    <option value="<?php echo esc_attr( $user->ID ); ?>" <?php selected( $user_id, $user->ID ); ?>><?php echo esc_html( $user->display_name . ' (' . $user->user_email . ')' ); ?></option>
                <?php endforeach; ?>
            </select>
            <p class="description"><?php esc_html_e( 'The user who owns this membership.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-membership-subscription-id"><?php esc_html_e( 'Subscription Plan', 'aqualuxe' ); ?></label>
            <select id="aqualuxe-membership-subscription-id" name="aqualuxe_membership_subscription_id" required>
                <option value=""><?php esc_html_e( 'Select a subscription plan', 'aqualuxe' ); ?></option>
                <?php foreach ( $subscriptions as $subscription ) : ?>
                    <option value="<?php echo esc_attr( $subscription->ID ); ?>" <?php selected( $subscription_id, $subscription->ID ); ?>><?php echo esc_html( $subscription->post_title ); ?></option>
                <?php endforeach; ?>
            </select>
            <p class="description"><?php esc_html_e( 'The subscription plan for this membership.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-membership-status"><?php esc_html_e( 'Status', 'aqualuxe' ); ?></label>
            <select id="aqualuxe-membership-status" name="aqualuxe_membership_status">
                <option value="active" <?php selected( $status, 'active' ); ?>><?php esc_html_e( 'Active', 'aqualuxe' ); ?></option>
                <option value="pending" <?php selected( $status, 'pending' ); ?>><?php esc_html_e( 'Pending', 'aqualuxe' ); ?></option>
                <option value="cancelled" <?php selected( $status, 'cancelled' ); ?>><?php esc_html_e( 'Cancelled', 'aqualuxe' ); ?></option>
                <option value="expired" <?php selected( $status, 'expired' ); ?>><?php esc_html_e( 'Expired', 'aqualuxe' ); ?></option>
            </select>
            <p class="description"><?php esc_html_e( 'The status of this membership.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-membership-start-date"><?php esc_html_e( 'Start Date', 'aqualuxe' ); ?></label>
            <input type="date" id="aqualuxe-membership-start-date" name="aqualuxe_membership_start_date" value="<?php echo esc_attr( $start_date ); ?>" required>
            <p class="description"><?php esc_html_e( 'The start date of this membership.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-membership-end-date"><?php esc_html_e( 'End Date', 'aqualuxe' ); ?></label>
            <input type="date" id="aqualuxe-membership-end-date" name="aqualuxe_membership_end_date" value="<?php echo esc_attr( $end_date ); ?>">
            <p class="description"><?php esc_html_e( 'The end date of this membership. Leave empty for lifetime access.', 'aqualuxe' ); ?></p>
        </div>

        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
            <div class="aqualuxe-meta-box-row">
                <label for="aqualuxe-membership-order-id"><?php esc_html_e( 'Order ID', 'aqualuxe' ); ?></label>
                <input type="text" id="aqualuxe-membership-order-id" name="aqualuxe_membership_order_id" value="<?php echo esc_attr( $order_id ); ?>">
                <p class="description"><?php esc_html_e( 'The WooCommerce order ID associated with this membership.', 'aqualuxe' ); ?></p>
            </div>
        <?php endif; ?>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-membership-auto-renew"><?php esc_html_e( 'Auto Renew', 'aqualuxe' ); ?></label>
            <input type="checkbox" id="aqualuxe-membership-auto-renew" name="aqualuxe_membership_auto_renew" value="1" <?php checked( $auto_renew, '1' ); ?>>
            <p class="description"><?php esc_html_e( 'Whether this membership should automatically renew.', 'aqualuxe' ); ?></p>
        </div>
        <?php
    }

    /**
     * Render membership history meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_membership_history_meta_box( $post ) {
        $history = get_post_meta( $post->ID, '_aqualuxe_membership_history', true );
        if ( ! is_array( $history ) ) {
            $history = [];
        }
        ?>
        <div class="aqualuxe-membership-history">
            <?php if ( ! empty( $history ) ) : ?>
                <table class="widefat">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Date', 'aqualuxe' ); ?></th>
                            <th><?php esc_html_e( 'Action', 'aqualuxe' ); ?></th>
                            <th><?php esc_html_e( 'Details', 'aqualuxe' ); ?></th>
                            <th><?php esc_html_e( 'User', 'aqualuxe' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $history as $entry ) : ?>
                            <tr>
                                <td><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $entry['date'] ) ) ); ?></td>
                                <td><?php echo esc_html( $entry['action'] ); ?></td>
                                <td><?php echo esc_html( $entry['details'] ); ?></td>
                                <td><?php echo esc_html( $entry['user'] ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p><?php esc_html_e( 'No history found.', 'aqualuxe' ); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render content restriction meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_content_restriction_meta_box( $post ) {
        wp_nonce_field( 'aqualuxe_content_restriction', 'aqualuxe_content_restriction_nonce' );

        $restriction_type = get_post_meta( $post->ID, '_aqualuxe_restriction_type', true );
        $custom_message = get_post_meta( $post->ID, '_aqualuxe_restriction_message', true );

        $access_levels = get_terms( [
            'taxonomy' => 'access_level',
            'hide_empty' => false,
        ] );

        $selected_levels = wp_get_object_terms( $post->ID, 'access_level', [ 'fields' => 'ids' ] );
        ?>
        <p><?php esc_html_e( 'Restrict access to this content.', 'aqualuxe' ); ?></p>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-restriction-type"><?php esc_html_e( 'Restriction Type', 'aqualuxe' ); ?></label>
            <select id="aqualuxe-restriction-type" name="aqualuxe_restriction_type">
                <option value="" <?php selected( $restriction_type, '' ); ?>><?php esc_html_e( 'No Restriction', 'aqualuxe' ); ?></option>
                <option value="members" <?php selected( $restriction_type, 'members' ); ?>><?php esc_html_e( 'All Members', 'aqualuxe' ); ?></option>
                <option value="levels" <?php selected( $restriction_type, 'levels' ); ?>><?php esc_html_e( 'Specific Access Levels', 'aqualuxe' ); ?></option>
            </select>
        </div>

        <div class="aqualuxe-restriction-levels" <?php echo 'levels' === $restriction_type ? '' : 'style="display:none;"'; ?>>
            <?php if ( ! empty( $access_levels ) && ! is_wp_error( $access_levels ) ) : ?>
                <p><?php esc_html_e( 'Select the access levels that can view this content.', 'aqualuxe' ); ?></p>
                <ul class="aqualuxe-access-levels-list">
                    <?php foreach ( $access_levels as $level ) : ?>
                        <li>
                            <label>
                                <input type="checkbox" name="aqualuxe_content_access_levels[]" value="<?php echo esc_attr( $level->term_id ); ?>" <?php checked( in_array( $level->term_id, $selected_levels ) ); ?>>
                                <?php echo esc_html( $level->name ); ?>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p><?php esc_html_e( 'No access levels found.', 'aqualuxe' ); ?></p>
                <p><a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=access_level&post_type=aqualuxe_subscription' ) ); ?>"><?php esc_html_e( 'Create access levels', 'aqualuxe' ); ?></a></p>
            <?php endif; ?>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-restriction-message"><?php esc_html_e( 'Custom Message', 'aqualuxe' ); ?></label>
            <textarea id="aqualuxe-restriction-message" name="aqualuxe_restriction_message" rows="3" class="widefat"><?php echo esc_textarea( $custom_message ); ?></textarea>
            <p class="description"><?php esc_html_e( 'Custom message to display to non-members. Leave empty to use the default message.', 'aqualuxe' ); ?></p>
        </div>

        <script>
            jQuery(document).ready(function($) {
                $('#aqualuxe-restriction-type').on('change', function() {
                    if ($(this).val() === 'levels') {
                        $('.aqualuxe-restriction-levels').show();
                    } else {
                        $('.aqualuxe-restriction-levels').hide();
                    }
                });
            });
        </script>
        <?php
    }

    /**
     * Save meta box data
     *
     * @param int $post_id
     * @return void
     */
    public function save_meta_box_data( $post_id ) {
        // Check if our nonce is set
        if ( ! isset( $_POST['aqualuxe_subscription_details_nonce'] ) && 
             ! isset( $_POST['aqualuxe_subscription_features_nonce'] ) && 
             ! isset( $_POST['aqualuxe_subscription_restrictions_nonce'] ) && 
             ! isset( $_POST['aqualuxe_membership_details_nonce'] ) && 
             ! isset( $_POST['aqualuxe_content_restriction_nonce'] ) ) {
            return;
        }

        // Verify the nonce
        if ( isset( $_POST['aqualuxe_subscription_details_nonce'] ) && 
             ! wp_verify_nonce( $_POST['aqualuxe_subscription_details_nonce'], 'aqualuxe_subscription_details' ) ) {
            return;
        }

        if ( isset( $_POST['aqualuxe_subscription_features_nonce'] ) && 
             ! wp_verify_nonce( $_POST['aqualuxe_subscription_features_nonce'], 'aqualuxe_subscription_features' ) ) {
            return;
        }

        if ( isset( $_POST['aqualuxe_subscription_restrictions_nonce'] ) && 
             ! wp_verify_nonce( $_POST['aqualuxe_subscription_restrictions_nonce'], 'aqualuxe_subscription_restrictions' ) ) {
            return;
        }

        if ( isset( $_POST['aqualuxe_membership_details_nonce'] ) && 
             ! wp_verify_nonce( $_POST['aqualuxe_membership_details_nonce'], 'aqualuxe_membership_details' ) ) {
            return;
        }

        if ( isset( $_POST['aqualuxe_content_restriction_nonce'] ) && 
             ! wp_verify_nonce( $_POST['aqualuxe_content_restriction_nonce'], 'aqualuxe_content_restriction' ) ) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check the user's permissions
        if ( isset( $_POST['post_type'] ) && 'aqualuxe_subscription' === $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        } elseif ( isset( $_POST['post_type'] ) && 'aqualuxe_membership' === $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }

        // Save subscription details
        if ( isset( $_POST['post_type'] ) && 'aqualuxe_subscription' === $_POST['post_type'] ) {
            // Save subscription details
            if ( isset( $_POST['aqualuxe_subscription_price'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_subscription_price', sanitize_text_field( $_POST['aqualuxe_subscription_price'] ) );
            }

            if ( isset( $_POST['aqualuxe_subscription_duration'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_subscription_duration', sanitize_text_field( $_POST['aqualuxe_subscription_duration'] ) );
            }

            if ( isset( $_POST['aqualuxe_subscription_duration_unit'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_subscription_duration_unit', sanitize_text_field( $_POST['aqualuxe_subscription_duration_unit'] ) );
            }

            if ( isset( $_POST['aqualuxe_subscription_trial_enabled'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_subscription_trial_enabled', '1' );
            } else {
                update_post_meta( $post_id, '_aqualuxe_subscription_trial_enabled', '' );
            }

            if ( isset( $_POST['aqualuxe_subscription_trial_duration'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_subscription_trial_duration', sanitize_text_field( $_POST['aqualuxe_subscription_trial_duration'] ) );
            }

            if ( isset( $_POST['aqualuxe_subscription_trial_duration_unit'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_subscription_trial_duration_unit', sanitize_text_field( $_POST['aqualuxe_subscription_trial_duration_unit'] ) );
            }

            if ( isset( $_POST['aqualuxe_subscription_signup_fee'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_subscription_signup_fee', sanitize_text_field( $_POST['aqualuxe_subscription_signup_fee'] ) );
            }

            if ( isset( $_POST['aqualuxe_subscription_max_members'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_subscription_max_members', sanitize_text_field( $_POST['aqualuxe_subscription_max_members'] ) );
            }

            if ( isset( $_POST['aqualuxe_subscription_product_id'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_subscription_product_id', sanitize_text_field( $_POST['aqualuxe_subscription_product_id'] ) );
            }

            // Save subscription features
            if ( isset( $_POST['aqualuxe_subscription_features'] ) ) {
                $features = array_filter( array_map( 'sanitize_text_field', $_POST['aqualuxe_subscription_features'] ) );
                update_post_meta( $post_id, '_aqualuxe_subscription_features', $features );
            }

            // Save subscription access levels
            if ( isset( $_POST['aqualuxe_subscription_access_levels'] ) ) {
                $access_levels = array_map( 'absint', $_POST['aqualuxe_subscription_access_levels'] );
                wp_set_object_terms( $post_id, $access_levels, 'access_level' );
            } else {
                wp_set_object_terms( $post_id, [], 'access_level' );
            }
        }

        // Save membership details
        if ( isset( $_POST['post_type'] ) && 'aqualuxe_membership' === $_POST['post_type'] ) {
            if ( isset( $_POST['aqualuxe_membership_user_id'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_membership_user_id', absint( $_POST['aqualuxe_membership_user_id'] ) );
            }

            if ( isset( $_POST['aqualuxe_membership_subscription_id'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_membership_subscription_id', absint( $_POST['aqualuxe_membership_subscription_id'] ) );
            }

            if ( isset( $_POST['aqualuxe_membership_status'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_membership_status', sanitize_text_field( $_POST['aqualuxe_membership_status'] ) );
            }

            if ( isset( $_POST['aqualuxe_membership_start_date'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_membership_start_date', sanitize_text_field( $_POST['aqualuxe_membership_start_date'] ) );
            }

            if ( isset( $_POST['aqualuxe_membership_end_date'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_membership_end_date', sanitize_text_field( $_POST['aqualuxe_membership_end_date'] ) );
            }

            if ( isset( $_POST['aqualuxe_membership_order_id'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_membership_order_id', sanitize_text_field( $_POST['aqualuxe_membership_order_id'] ) );
            }

            if ( isset( $_POST['aqualuxe_membership_auto_renew'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_membership_auto_renew', '1' );
            } else {
                update_post_meta( $post_id, '_aqualuxe_membership_auto_renew', '' );
            }

            // Add history entry for status change
            $old_status = get_post_meta( $post_id, '_aqualuxe_membership_status', true );
            $new_status = isset( $_POST['aqualuxe_membership_status'] ) ? sanitize_text_field( $_POST['aqualuxe_membership_status'] ) : '';

            if ( $old_status !== $new_status && $new_status ) {
                $this->add_membership_history( $post_id, 'status_change', sprintf( __( 'Status changed from %s to %s', 'aqualuxe' ), $old_status, $new_status ), get_current_user_id() );
            }
        }

        // Save content restriction
        if ( isset( $_POST['aqualuxe_restriction_type'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_restriction_type', sanitize_text_field( $_POST['aqualuxe_restriction_type'] ) );
        }

        if ( isset( $_POST['aqualuxe_restriction_message'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_restriction_message', sanitize_textarea_field( $_POST['aqualuxe_restriction_message'] ) );
        }

        if ( isset( $_POST['aqualuxe_content_access_levels'] ) ) {
            $access_levels = array_map( 'absint', $_POST['aqualuxe_content_access_levels'] );
            wp_set_object_terms( $post_id, $access_levels, 'access_level' );
        } elseif ( isset( $_POST['aqualuxe_restriction_type'] ) && 'levels' === $_POST['aqualuxe_restriction_type'] ) {
            wp_set_object_terms( $post_id, [], 'access_level' );
        }
    }

    /**
     * Add user profile fields
     *
     * @param \WP_User $user
     * @return void
     */
    public function add_user_profile_fields( $user ) {
        // Get user memberships
        $memberships = $this->get_user_memberships( $user->ID );
        ?>
        <h2><?php esc_html_e( 'Memberships', 'aqualuxe' ); ?></h2>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Active Memberships', 'aqualuxe' ); ?></th>
                <td>
                    <?php if ( ! empty( $memberships ) ) : ?>
                        <table class="widefat">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e( 'Subscription Plan', 'aqualuxe' ); ?></th>
                                    <th><?php esc_html_e( 'Status', 'aqualuxe' ); ?></th>
                                    <th><?php esc_html_e( 'Start Date', 'aqualuxe' ); ?></th>
                                    <th><?php esc_html_e( 'End Date', 'aqualuxe' ); ?></th>
                                    <th><?php esc_html_e( 'Actions', 'aqualuxe' ); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ( $memberships as $membership ) : ?>
                                    <?php
                                    $subscription_id = get_post_meta( $membership->ID, '_aqualuxe_membership_subscription_id', true );
                                    $subscription = get_post( $subscription_id );
                                    $status = get_post_meta( $membership->ID, '_aqualuxe_membership_status', true );
                                    $start_date = get_post_meta( $membership->ID, '_aqualuxe_membership_start_date', true );
                                    $end_date = get_post_meta( $membership->ID, '_aqualuxe_membership_end_date', true );
                                    ?>
                                    <tr>
                                        <td><?php echo esc_html( $subscription ? $subscription->post_title : __( 'Unknown', 'aqualuxe' ) ); ?></td>
                                        <td><?php echo esc_html( ucfirst( $status ) ); ?></td>
                                        <td><?php echo esc_html( $start_date ? date_i18n( get_option( 'date_format' ), strtotime( $start_date ) ) : '' ); ?></td>
                                        <td><?php echo esc_html( $end_date ? date_i18n( get_option( 'date_format' ), strtotime( $end_date ) ) : __( 'Lifetime', 'aqualuxe' ) ); ?></td>
                                        <td>
                                            <a href="<?php echo esc_url( get_edit_post_link( $membership->ID ) ); ?>"><?php esc_html_e( 'Edit', 'aqualuxe' ); ?></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p><?php esc_html_e( 'No memberships found.', 'aqualuxe' ); ?></p>
                    <?php endif; ?>
                    <p><a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=aqualuxe_membership' ) ); ?>" class="button"><?php esc_html_e( 'Add Membership', 'aqualuxe' ); ?></a></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save user profile fields
     *
     * @param int $user_id
     * @return void
     */
    public function save_user_profile_fields( $user_id ) {
        if ( ! current_user_can( 'edit_user', $user_id ) ) {
            return;
        }

        // No fields to save currently
    }

    /**
     * Restrict content
     *
     * @return void
     */
    public function restrict_content() {
        if ( ! is_singular() ) {
            return;
        }

        $post_id = get_the_ID();
        $restriction_type = get_post_meta( $post_id, '_aqualuxe_restriction_type', true );

        if ( ! $restriction_type ) {
            return;
        }

        $has_access = $this->user_has_access( $post_id );

        if ( ! $has_access ) {
            $settings = get_option( 'aqualuxe_subscription_settings', [] );
            $redirect_page = isset( $settings['restricted_content_redirect'] ) ? $settings['restricted_content_redirect'] : '';

            if ( $redirect_page ) {
                wp_redirect( get_permalink( $redirect_page ) );
                exit;
            }
        }
    }

    /**
     * Filter restricted content
     *
     * @param string $content
     * @return string
     */
    public function filter_restricted_content( $content ) {
        if ( ! is_singular() ) {
            return $content;
        }

        $post_id = get_the_ID();
        $restriction_type = get_post_meta( $post_id, '_aqualuxe_restriction_type', true );

        if ( ! $restriction_type ) {
            return $content;
        }

        $has_access = $this->user_has_access( $post_id );

        if ( ! $has_access ) {
            $custom_message = get_post_meta( $post_id, '_aqualuxe_restriction_message', true );
            $settings = get_option( 'aqualuxe_subscription_settings', [] );
            $default_message = isset( $settings['restricted_content_message'] ) ? $settings['restricted_content_message'] : __( 'This content is restricted to members only.', 'aqualuxe' );

            $message = $custom_message ? $custom_message : $default_message;
            $pricing_page = isset( $settings['pricing_page'] ) ? $settings['pricing_page'] : '';

            ob_start();
            ?>
            <div class="aqualuxe-restricted-content">
                <p><?php echo wp_kses_post( $message ); ?></p>
                <?php if ( ! is_user_logged_in() ) : ?>
                    <p><?php esc_html_e( 'Please log in to access this content.', 'aqualuxe' ); ?></p>
                    <p>
                        <a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" class="button"><?php esc_html_e( 'Log In', 'aqualuxe' ); ?></a>
                        <a href="<?php echo esc_url( wp_registration_url() ); ?>" class="button"><?php esc_html_e( 'Register', 'aqualuxe' ); ?></a>
                    </p>
                <?php elseif ( $pricing_page ) : ?>
                    <p><?php esc_html_e( 'Please upgrade your membership to access this content.', 'aqualuxe' ); ?></p>
                    <p>
                        <a href="<?php echo esc_url( get_permalink( $pricing_page ) ); ?>" class="button"><?php esc_html_e( 'View Membership Options', 'aqualuxe' ); ?></a>
                    </p>
                <?php endif; ?>
            </div>
            <?php
            return ob_get_clean();
        }

        return $content;
    }

    /**
     * Check if user has access to content
     *
     * @param int $post_id
     * @return boolean
     */
    public function user_has_access( $post_id ) {
        // Administrators always have access
        if ( current_user_can( 'manage_options' ) ) {
            return true;
        }

        $restriction_type = get_post_meta( $post_id, '_aqualuxe_restriction_type', true );

        if ( ! $restriction_type ) {
            return true;
        }

        if ( ! is_user_logged_in() ) {
            return false;
        }

        $user_id = get_current_user_id();

        // Check if user has active membership
        if ( 'members' === $restriction_type ) {
            return $this->user_has_active_membership( $user_id );
        }

        // Check if user has required access level
        if ( 'levels' === $restriction_type ) {
            $content_levels = wp_get_object_terms( $post_id, 'access_level', [ 'fields' => 'ids' ] );
            $user_levels = $this->get_user_access_levels( $user_id );

            if ( empty( $content_levels ) ) {
                return true;
            }

            return count( array_intersect( $content_levels, $user_levels ) ) > 0;
        }

        return true;
    }

    /**
     * Check if user has active membership
     *
     * @param int $user_id
     * @return boolean
     */
    public function user_has_active_membership( $user_id ) {
        $memberships = $this->get_user_memberships( $user_id, 'active' );
        return ! empty( $memberships );
    }

    /**
     * Get user access levels
     *
     * @param int $user_id
     * @return array
     */
    public function get_user_access_levels( $user_id ) {
        $memberships = $this->get_user_memberships( $user_id, 'active' );
        $access_levels = [];

        if ( empty( $memberships ) ) {
            return $access_levels;
        }

        foreach ( $memberships as $membership ) {
            $subscription_id = get_post_meta( $membership->ID, '_aqualuxe_membership_subscription_id', true );
            $subscription_levels = wp_get_object_terms( $subscription_id, 'access_level', [ 'fields' => 'ids' ] );
            $access_levels = array_merge( $access_levels, $subscription_levels );
        }

        return array_unique( $access_levels );
    }

    /**
     * Get user memberships
     *
     * @param int $user_id
     * @param string $status
     * @return array
     */
    public function get_user_memberships( $user_id, $status = '' ) {
        $args = [
            'post_type' => 'aqualuxe_membership',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_aqualuxe_membership_user_id',
                    'value' => $user_id,
                ],
            ],
        ];

        if ( $status ) {
            $args['meta_query'][] = [
                'key' => '_aqualuxe_membership_status',
                'value' => $status,
            ];
        }

        return get_posts( $args );
    }

    /**
     * Add membership history
     *
     * @param int $membership_id
     * @param string $action
     * @param string $details
     * @param int $user_id
     * @return void
     */
    public function add_membership_history( $membership_id, $action, $details, $user_id ) {
        $history = get_post_meta( $membership_id, '_aqualuxe_membership_history', true );
        if ( ! is_array( $history ) ) {
            $history = [];
        }

        $user = get_user_by( 'id', $user_id );
        $user_name = $user ? $user->display_name : __( 'System', 'aqualuxe' );

        $history[] = [
            'date' => current_time( 'mysql' ),
            'action' => $action,
            'details' => $details,
            'user' => $user_name,
        ];

        update_post_meta( $membership_id, '_aqualuxe_membership_history', $history );
    }

    /**
     * Check subscription expirations
     *
     * @return void
     */
    public function check_subscription_expirations() {
        $args = [
            'post_type' => 'aqualuxe_membership',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_aqualuxe_membership_status',
                    'value' => 'active',
                ],
                [
                    'key' => '_aqualuxe_membership_end_date',
                    'value' => '',
                    'compare' => '!=',
                ],
                [
                    'key' => '_aqualuxe_membership_end_date',
                    'value' => date( 'Y-m-d' ),
                    'compare' => '<=',
                    'type' => 'DATE',
                ],
            ],
        ];

        $memberships = get_posts( $args );

        foreach ( $memberships as $membership ) {
            $auto_renew = get_post_meta( $membership->ID, '_aqualuxe_membership_auto_renew', true );

            if ( $auto_renew ) {
                // Renew membership
                $this->renew_membership( $membership->ID );
            } else {
                // Expire membership
                update_post_meta( $membership->ID, '_aqualuxe_membership_status', 'expired' );
                $this->add_membership_history( $membership->ID, 'expired', __( 'Membership expired', 'aqualuxe' ), 0 );

                // Send expiration email
                $this->send_expired_email( $membership->ID );
            }
        }

        // Check for upcoming expirations (7 days)
        $args = [
            'post_type' => 'aqualuxe_membership',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_aqualuxe_membership_status',
                    'value' => 'active',
                ],
                [
                    'key' => '_aqualuxe_membership_end_date',
                    'value' => '',
                    'compare' => '!=',
                ],
                [
                    'key' => '_aqualuxe_membership_end_date',
                    'value' => date( 'Y-m-d', strtotime( '+7 days' ) ),
                    'compare' => '=',
                    'type' => 'DATE',
                ],
            ],
        ];

        $memberships = get_posts( $args );

        foreach ( $memberships as $membership ) {
            // Send expiration reminder email
            $this->send_expiration_reminder_email( $membership->ID );
        }
    }

    /**
     * Renew membership
     *
     * @param int $membership_id
     * @return void
     */
    public function renew_membership( $membership_id ) {
        $subscription_id = get_post_meta( $membership_id, '_aqualuxe_membership_subscription_id', true );
        $end_date = get_post_meta( $membership_id, '_aqualuxe_membership_end_date', true );
        $duration = get_post_meta( $subscription_id, '_aqualuxe_subscription_duration', true );
        $duration_unit = get_post_meta( $subscription_id, '_aqualuxe_subscription_duration_unit', true );

        if ( $duration && $duration_unit ) {
            $new_end_date = date( 'Y-m-d', strtotime( "+{$duration} {$duration_unit}", strtotime( $end_date ) ) );
            update_post_meta( $membership_id, '_aqualuxe_membership_end_date', $new_end_date );
            $this->add_membership_history( $membership_id, 'renewed', sprintf( __( 'Membership renewed until %s', 'aqualuxe' ), $new_end_date ), 0 );
        }
    }

    /**
     * Send expired email
     *
     * @param int $membership_id
     * @return void
     */
    public function send_expired_email( $membership_id ) {
        $user_id = get_post_meta( $membership_id, '_aqualuxe_membership_user_id', true );
        $user = get_user_by( 'id', $user_id );

        if ( ! $user ) {
            return;
        }

        $subscription_id = get_post_meta( $membership_id, '_aqualuxe_membership_subscription_id', true );
        $subscription = get_post( $subscription_id );

        if ( ! $subscription ) {
            return;
        }

        $settings = get_option( 'aqualuxe_subscription_settings', [] );
        $subject = isset( $settings['expired_email_subject'] ) ? $settings['expired_email_subject'] : __( 'Your membership has expired', 'aqualuxe' );
        $message = isset( $settings['expired_email_message'] ) ? $settings['expired_email_message'] : __( 'Your membership has expired. Please renew to continue accessing member content.', 'aqualuxe' );

        $pricing_page = isset( $settings['pricing_page'] ) ? $settings['pricing_page'] : '';
        $pricing_url = $pricing_page ? get_permalink( $pricing_page ) : '';

        $message = str_replace( [
            '{user_name}',
            '{subscription_name}',
            '{expiration_date}',
            '{pricing_url}',
        ], [
            $user->display_name,
            $subscription->post_title,
            get_post_meta( $membership_id, '_aqualuxe_membership_end_date', true ),
            $pricing_url,
        ], $message );

        wp_mail( $user->user_email, $subject, $message );
    }

    /**
     * Send expiration reminder email
     *
     * @param int $membership_id
     * @return void
     */
    public function send_expiration_reminder_email( $membership_id ) {
        $user_id = get_post_meta( $membership_id, '_aqualuxe_membership_user_id', true );
        $user = get_user_by( 'id', $user_id );

        if ( ! $user ) {
            return;
        }

        $subscription_id = get_post_meta( $membership_id, '_aqualuxe_membership_subscription_id', true );
        $subscription = get_post( $subscription_id );

        if ( ! $subscription ) {
            return;
        }

        $settings = get_option( 'aqualuxe_subscription_settings', [] );
        $subject = isset( $settings['expiration_reminder_email_subject'] ) ? $settings['expiration_reminder_email_subject'] : __( 'Your membership is expiring soon', 'aqualuxe' );
        $message = isset( $settings['expiration_reminder_email_message'] ) ? $settings['expiration_reminder_email_message'] : __( 'Your membership is expiring soon. Please renew to continue accessing member content.', 'aqualuxe' );

        $pricing_page = isset( $settings['pricing_page'] ) ? $settings['pricing_page'] : '';
        $pricing_url = $pricing_page ? get_permalink( $pricing_page ) : '';

        $message = str_replace( [
            '{user_name}',
            '{subscription_name}',
            '{expiration_date}',
            '{pricing_url}',
        ], [
            $user->display_name,
            $subscription->post_title,
            get_post_meta( $membership_id, '_aqualuxe_membership_end_date', true ),
            $pricing_url,
        ], $message );

        wp_mail( $user->user_email, $subject, $message );
    }

    /**
     * Render settings page
     *
     * @return void
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Subscription Settings', 'aqualuxe' ); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'aqualuxe_subscription_settings' );
                do_settings_sections( 'aqualuxe_subscription_settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render members page
     *
     * @return void
     */
    public function render_members_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Members', 'aqualuxe' ); ?></h1>
            <p><?php esc_html_e( 'Manage your members and their subscriptions.', 'aqualuxe' ); ?></p>
            
            <?php
            // Create an instance of our members list table
            require_once __DIR__ . '/admin/class-members-list-table.php';
            $members_table = new \AquaLuxe\Modules\Subscriptions\Admin\Members_List_Table();
            $members_table->prepare_items();
            ?>
            
            <form method="post">
                <?php $members_table->display(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render reports page
     *
     * @return void
     */
    public function render_reports_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Subscription Reports', 'aqualuxe' ); ?></h1>
            <p><?php esc_html_e( 'View reports and statistics for your subscriptions.', 'aqualuxe' ); ?></p>
            
            <div class="aqualuxe-subscription-reports">
                <div class="aqualuxe-subscription-report-card">
                    <h2><?php esc_html_e( 'Active Memberships', 'aqualuxe' ); ?></h2>
                    <div class="aqualuxe-subscription-report-value">
                        <?php
                        $active_memberships = get_posts( [
                            'post_type' => 'aqualuxe_membership',
                            'posts_per_page' => -1,
                            'meta_query' => [
                                [
                                    'key' => '_aqualuxe_membership_status',
                                    'value' => 'active',
                                ],
                            ],
                        ] );
                        echo count( $active_memberships );
                        ?>
                    </div>
                </div>
                
                <div class="aqualuxe-subscription-report-card">
                    <h2><?php esc_html_e( 'Total Members', 'aqualuxe' ); ?></h2>
                    <div class="aqualuxe-subscription-report-value">
                        <?php
                        $memberships = get_posts( [
                            'post_type' => 'aqualuxe_membership',
                            'posts_per_page' => -1,
                            'meta_query' => [
                                [
                                    'key' => '_aqualuxe_membership_user_id',
                                    'compare' => 'EXISTS',
                                ],
                            ],
                        ] );
                        $user_ids = [];
                        foreach ( $memberships as $membership ) {
                            $user_id = get_post_meta( $membership->ID, '_aqualuxe_membership_user_id', true );
                            if ( $user_id ) {
                                $user_ids[] = $user_id;
                            }
                        }
                        echo count( array_unique( $user_ids ) );
                        ?>
                    </div>
                </div>
                
                <div class="aqualuxe-subscription-report-card">
                    <h2><?php esc_html_e( 'Expiring Soon', 'aqualuxe' ); ?></h2>
                    <div class="aqualuxe-subscription-report-value">
                        <?php
                        $expiring_memberships = get_posts( [
                            'post_type' => 'aqualuxe_membership',
                            'posts_per_page' => -1,
                            'meta_query' => [
                                [
                                    'key' => '_aqualuxe_membership_status',
                                    'value' => 'active',
                                ],
                                [
                                    'key' => '_aqualuxe_membership_end_date',
                                    'value' => '',
                                    'compare' => '!=',
                                ],
                                [
                                    'key' => '_aqualuxe_membership_end_date',
                                    'value' => [ date( 'Y-m-d' ), date( 'Y-m-d', strtotime( '+30 days' ) ) ],
                                    'compare' => 'BETWEEN',
                                    'type' => 'DATE',
                                ],
                            ],
                        ] );
                        echo count( $expiring_memberships );
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="aqualuxe-subscription-reports-section">
                <h2><?php esc_html_e( 'Membership by Plan', 'aqualuxe' ); ?></h2>
                <table class="widefat">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Subscription Plan', 'aqualuxe' ); ?></th>
                            <th><?php esc_html_e( 'Active Members', 'aqualuxe' ); ?></th>
                            <th><?php esc_html_e( 'Total Members', 'aqualuxe' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $subscription_plans = get_posts( [
                            'post_type' => 'aqualuxe_subscription',
                            'posts_per_page' => -1,
                            'orderby' => 'title',
                            'order' => 'ASC',
                        ] );
                        
                        foreach ( $subscription_plans as $plan ) :
                            $active_count = count( get_posts( [
                                'post_type' => 'aqualuxe_membership',
                                'posts_per_page' => -1,
                                'meta_query' => [
                                    [
                                        'key' => '_aqualuxe_membership_subscription_id',
                                        'value' => $plan->ID,
                                    ],
                                    [
                                        'key' => '_aqualuxe_membership_status',
                                        'value' => 'active',
                                    ],
                                ],
                            ] ) );
                            
                            $total_count = count( get_posts( [
                                'post_type' => 'aqualuxe_membership',
                                'posts_per_page' => -1,
                                'meta_query' => [
                                    [
                                        'key' => '_aqualuxe_membership_subscription_id',
                                        'value' => $plan->ID,
                                    ],
                                ],
                            ] ) );
                        ?>
                            <tr>
                                <td><?php echo esc_html( $plan->post_title ); ?></td>
                                <td><?php echo esc_html( $active_count ); ?></td>
                                <td><?php echo esc_html( $total_count ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }

    /**
     * Render general settings section
     *
     * @return void
     */
    public function render_general_settings_section() {
        echo '<p>' . esc_html__( 'Configure general settings for subscriptions and memberships.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render content settings section
     *
     * @return void
     */
    public function render_content_settings_section() {
        echo '<p>' . esc_html__( 'Configure settings for content restriction.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render payment settings section
     *
     * @return void
     */
    public function render_payment_settings_section() {
        echo '<p>' . esc_html__( 'Configure payment settings for subscriptions.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render email settings section
     *
     * @return void
     */
    public function render_email_settings_section() {
        echo '<p>' . esc_html__( 'Configure email settings for subscriptions.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render account page field
     *
     * @return void
     */
    public function render_account_page_field() {
        $settings = get_option( 'aqualuxe_subscription_settings', [] );
        $account_page = isset( $settings['account_page'] ) ? $settings['account_page'] : '';

        wp_dropdown_pages( [
            'name'              => 'aqualuxe_subscription_settings[account_page]',
            'selected'          => $account_page,
            'show_option_none'  => __( 'Select a page', 'aqualuxe' ),
        ] );
        echo '<p class="description">' . esc_html__( 'Select the page where members can manage their subscriptions.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render pricing page field
     *
     * @return void
     */
    public function render_pricing_page_field() {
        $settings = get_option( 'aqualuxe_subscription_settings', [] );
        $pricing_page = isset( $settings['pricing_page'] ) ? $settings['pricing_page'] : '';

        wp_dropdown_pages( [
            'name'              => 'aqualuxe_subscription_settings[pricing_page]',
            'selected'          => $pricing_page,
            'show_option_none'  => __( 'Select a page', 'aqualuxe' ),
        ] );
        echo '<p class="description">' . esc_html__( 'Select the page where subscription plans are displayed.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render login redirect field
     *
     * @return void
     */
    public function render_login_redirect_field() {
        $settings = get_option( 'aqualuxe_subscription_settings', [] );
        $login_redirect = isset( $settings['login_redirect'] ) ? $settings['login_redirect'] : '';

        wp_dropdown_pages( [
            'name'              => 'aqualuxe_subscription_settings[login_redirect]',
            'selected'          => $login_redirect,
            'show_option_none'  => __( 'Default', 'aqualuxe' ),
        ] );
        echo '<p class="description">' . esc_html__( 'Select the page to redirect to after login.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render restricted content message field
     *
     * @return void
     */
    public function render_restricted_content_message_field() {
        $settings = get_option( 'aqualuxe_subscription_settings', [] );
        $restricted_content_message = isset( $settings['restricted_content_message'] ) ? $settings['restricted_content_message'] : __( 'This content is restricted to members only.', 'aqualuxe' );
        ?>
        <textarea name="aqualuxe_subscription_settings[restricted_content_message]" rows="3" class="large-text"><?php echo esc_textarea( $restricted_content_message ); ?></textarea>
        <p class="description"><?php esc_html_e( 'Message to display when content is restricted.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Render restricted content redirect field
     *
     * @return void
     */
    public function render_restricted_content_redirect_field() {
        $settings = get_option( 'aqualuxe_subscription_settings', [] );
        $restricted_content_redirect = isset( $settings['restricted_content_redirect'] ) ? $settings['restricted_content_redirect'] : '';

        wp_dropdown_pages( [
            'name'              => 'aqualuxe_subscription_settings[restricted_content_redirect]',
            'selected'          => $restricted_content_redirect,
            'show_option_none'  => __( 'No Redirect', 'aqualuxe' ),
        ] );
        echo '<p class="description">' . esc_html__( 'Select the page to redirect to when content is restricted.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render payment gateway field
     *
     * @return void
     */
    public function render_payment_gateway_field() {
        $settings = get_option( 'aqualuxe_subscription_settings', [] );
        $payment_gateway = isset( $settings['payment_gateway'] ) ? $settings['payment_gateway'] : 'woocommerce';
        ?>
        <select name="aqualuxe_subscription_settings[payment_gateway]">
            <option value="woocommerce" <?php selected( $payment_gateway, 'woocommerce' ); ?>><?php esc_html_e( 'WooCommerce', 'aqualuxe' ); ?></option>
            <option value="stripe" <?php selected( $payment_gateway, 'stripe' ); ?>><?php esc_html_e( 'Stripe', 'aqualuxe' ); ?></option>
            <option value="paypal" <?php selected( $payment_gateway, 'paypal' ); ?>><?php esc_html_e( 'PayPal', 'aqualuxe' ); ?></option>
        </select>
        <p class="description"><?php esc_html_e( 'Select the payment gateway to use for subscriptions.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Render currency field
     *
     * @return void
     */
    public function render_currency_field() {
        $settings = get_option( 'aqualuxe_subscription_settings', [] );
        $currency = isset( $settings['currency'] ) ? $settings['currency'] : 'USD';
        ?>
        <select name="aqualuxe_subscription_settings[currency]">
            <option value="USD" <?php selected( $currency, 'USD' ); ?>><?php esc_html_e( 'US Dollar ($)', 'aqualuxe' ); ?></option>
            <option value="EUR" <?php selected( $currency, 'EUR' ); ?>><?php esc_html_e( 'Euro (€)', 'aqualuxe' ); ?></option>
            <option value="GBP" <?php selected( $currency, 'GBP' ); ?>><?php esc_html_e( 'British Pound (£)', 'aqualuxe' ); ?></option>
            <option value="CAD" <?php selected( $currency, 'CAD' ); ?>><?php esc_html_e( 'Canadian Dollar ($)', 'aqualuxe' ); ?></option>
            <option value="AUD" <?php selected( $currency, 'AUD' ); ?>><?php esc_html_e( 'Australian Dollar ($)', 'aqualuxe' ); ?></option>
        </select>
        <p class="description"><?php esc_html_e( 'Select the currency to use for subscriptions.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Render welcome email field
     *
     * @return void
     */
    public function render_welcome_email_field() {
        $settings = get_option( 'aqualuxe_subscription_settings', [] );
        $welcome_email_subject = isset( $settings['welcome_email_subject'] ) ? $settings['welcome_email_subject'] : __( 'Welcome to {site_name}', 'aqualuxe' );
        $welcome_email_message = isset( $settings['welcome_email_message'] ) ? $settings['welcome_email_message'] : __( 'Thank you for joining {site_name}. Your membership is now active.', 'aqualuxe' );
        ?>
        <h4><?php esc_html_e( 'Subject', 'aqualuxe' ); ?></h4>
        <input type="text" name="aqualuxe_subscription_settings[welcome_email_subject]" value="<?php echo esc_attr( $welcome_email_subject ); ?>" class="large-text">
        
        <h4><?php esc_html_e( 'Message', 'aqualuxe' ); ?></h4>
        <textarea name="aqualuxe_subscription_settings[welcome_email_message]" rows="5" class="large-text"><?php echo esc_textarea( $welcome_email_message ); ?></textarea>
        
        <p class="description">
            <?php esc_html_e( 'Available placeholders:', 'aqualuxe' ); ?>
            <code>{user_name}</code>, <code>{subscription_name}</code>, <code>{site_name}</code>, <code>{expiration_date}</code>, <code>{account_url}</code>
        </p>
        <?php
    }

    /**
     * Render expiration reminder email field
     *
     * @return void
     */
    public function render_expiration_reminder_email_field() {
        $settings = get_option( 'aqualuxe_subscription_settings', [] );
        $expiration_reminder_email_subject = isset( $settings['expiration_reminder_email_subject'] ) ? $settings['expiration_reminder_email_subject'] : __( 'Your membership is expiring soon', 'aqualuxe' );
        $expiration_reminder_email_message = isset( $settings['expiration_reminder_email_message'] ) ? $settings['expiration_reminder_email_message'] : __( 'Your membership is expiring soon. Please renew to continue accessing member content.', 'aqualuxe' );
        ?>
        <h4><?php esc_html_e( 'Subject', 'aqualuxe' ); ?></h4>
        <input type="text" name="aqualuxe_subscription_settings[expiration_reminder_email_subject]" value="<?php echo esc_attr( $expiration_reminder_email_subject ); ?>" class="large-text">
        
        <h4><?php esc_html_e( 'Message', 'aqualuxe' ); ?></h4>
        <textarea name="aqualuxe_subscription_settings[expiration_reminder_email_message]" rows="5" class="large-text"><?php echo esc_textarea( $expiration_reminder_email_message ); ?></textarea>
        
        <p class="description">
            <?php esc_html_e( 'Available placeholders:', 'aqualuxe' ); ?>
            <code>{user_name}</code>, <code>{subscription_name}</code>, <code>{site_name}</code>, <code>{expiration_date}</code>, <code>{pricing_url}</code>
        </p>
        <?php
    }

    /**
     * Render expired email field
     *
     * @return void
     */
    public function render_expired_email_field() {
        $settings = get_option( 'aqualuxe_subscription_settings', [] );
        $expired_email_subject = isset( $settings['expired_email_subject'] ) ? $settings['expired_email_subject'] : __( 'Your membership has expired', 'aqualuxe' );
        $expired_email_message = isset( $settings['expired_email_message'] ) ? $settings['expired_email_message'] : __( 'Your membership has expired. Please renew to continue accessing member content.', 'aqualuxe' );
        ?>
        <h4><?php esc_html_e( 'Subject', 'aqualuxe' ); ?></h4>
        <input type="text" name="aqualuxe_subscription_settings[expired_email_subject]" value="<?php echo esc_attr( $expired_email_subject ); ?>" class="large-text">
        
        <h4><?php esc_html_e( 'Message', 'aqualuxe' ); ?></h4>
        <textarea name="aqualuxe_subscription_settings[expired_email_message]" rows="5" class="large-text"><?php echo esc_textarea( $expired_email_message ); ?></textarea>
        
        <p class="description">
            <?php esc_html_e( 'Available placeholders:', 'aqualuxe' ); ?>
            <code>{user_name}</code>, <code>{subscription_name}</code>, <code>{site_name}</code>, <code>{expiration_date}</code>, <code>{pricing_url}</code>
        </p>
        <?php
    }

    /**
     * Render cancellation email field
     *
     * @return void
     */
    public function render_cancellation_email_field() {
        $settings = get_option( 'aqualuxe_subscription_settings', [] );
        $cancellation_email_subject = isset( $settings['cancellation_email_subject'] ) ? $settings['cancellation_email_subject'] : __( 'Your membership has been cancelled', 'aqualuxe' );
        $cancellation_email_message = isset( $settings['cancellation_email_message'] ) ? $settings['cancellation_email_message'] : __( 'Your membership has been cancelled. We\'re sorry to see you go.', 'aqualuxe' );
        ?>
        <h4><?php esc_html_e( 'Subject', 'aqualuxe' ); ?></h4>
        <input type="text" name="aqualuxe_subscription_settings[cancellation_email_subject]" value="<?php echo esc_attr( $cancellation_email_subject ); ?>" class="large-text">
        
        <h4><?php esc_html_e( 'Message', 'aqualuxe' ); ?></h4>
        <textarea name="aqualuxe_subscription_settings[cancellation_email_message]" rows="5" class="large-text"><?php echo esc_textarea( $cancellation_email_message ); ?></textarea>
        
        <p class="description">
            <?php esc_html_e( 'Available placeholders:', 'aqualuxe' ); ?>
            <code>{user_name}</code>, <code>{subscription_name}</code>, <code>{site_name}</code>, <code>{pricing_url}</code>
        </p>
        <?php
    }
}

// Initialize the module
new Subscriptions();