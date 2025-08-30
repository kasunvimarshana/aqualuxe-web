<?php
/**
 * Bookings Module
 *
 * @package AquaLuxe\Modules\Bookings
 */

namespace AquaLuxe\Modules\Bookings;

/**
 * Bookings Module Class
 */
class Module extends \AquaLuxe\Module {
    /**
     * Module ID
     *
     * @var string
     */
    protected $id = 'bookings';

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Bookings';

    /**
     * Module description
     *
     * @var string
     */
    protected $description = 'Adds booking functionality to the theme with calendar, availability checking, and payment integration.';

    /**
     * Module version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $dependencies = [];

    /**
     * Initialize module
     */
    public function init() {
        // Load module files
        $this->load_files();

        // Register post types and taxonomies
        $this->register_post_types();
        $this->register_taxonomies();

        // Register hooks
        $this->register_hooks();

        // Register settings
        $this->register_module_settings();

        // Register shortcodes
        $this->register_shortcodes();

        // Register REST API endpoints
        $this->register_rest_api();
    }

    /**
     * Load module files
     */
    private function load_files() {
        // Load classes
        require_once $this->path . 'classes/Booking.php';
        require_once $this->path . 'classes/Calendar.php';
        require_once $this->path . 'classes/Availability.php';
        require_once $this->path . 'classes/Payment.php';
        require_once $this->path . 'classes/Email.php';
        require_once $this->path . 'classes/Admin.php';
        require_once $this->path . 'classes/Widget.php';
        require_once $this->path . 'classes/Shortcodes.php';
        require_once $this->path . 'classes/REST_API.php';
    }

    /**
     * Register post types
     */
    private function register_post_types() {
        // Register booking post type
        register_post_type('aqualuxe_booking', [
            'labels' => [
                'name' => __('Bookings', 'aqualuxe'),
                'singular_name' => __('Booking', 'aqualuxe'),
                'add_new' => __('Add New', 'aqualuxe'),
                'add_new_item' => __('Add New Booking', 'aqualuxe'),
                'edit_item' => __('Edit Booking', 'aqualuxe'),
                'new_item' => __('New Booking', 'aqualuxe'),
                'view_item' => __('View Booking', 'aqualuxe'),
                'search_items' => __('Search Bookings', 'aqualuxe'),
                'not_found' => __('No bookings found', 'aqualuxe'),
                'not_found_in_trash' => __('No bookings found in trash', 'aqualuxe'),
                'all_items' => __('All Bookings', 'aqualuxe'),
                'menu_name' => __('Bookings', 'aqualuxe'),
            ],
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'booking'],
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 20,
            'menu_icon' => 'dashicons-calendar-alt',
            'supports' => ['title', 'author'],
            'show_in_rest' => true,
        ]);

        // Register bookable item post type
        register_post_type('aqualuxe_bookable', [
            'labels' => [
                'name' => __('Bookable Items', 'aqualuxe'),
                'singular_name' => __('Bookable Item', 'aqualuxe'),
                'add_new' => __('Add New', 'aqualuxe'),
                'add_new_item' => __('Add New Bookable Item', 'aqualuxe'),
                'edit_item' => __('Edit Bookable Item', 'aqualuxe'),
                'new_item' => __('New Bookable Item', 'aqualuxe'),
                'view_item' => __('View Bookable Item', 'aqualuxe'),
                'search_items' => __('Search Bookable Items', 'aqualuxe'),
                'not_found' => __('No bookable items found', 'aqualuxe'),
                'not_found_in_trash' => __('No bookable items found in trash', 'aqualuxe'),
                'all_items' => __('All Bookable Items', 'aqualuxe'),
                'menu_name' => __('Bookable Items', 'aqualuxe'),
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=aqualuxe_booking',
            'query_var' => true,
            'rewrite' => ['slug' => 'bookable'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'show_in_rest' => true,
        ]);
    }

    /**
     * Register taxonomies
     */
    private function register_taxonomies() {
        // Register booking status taxonomy
        register_taxonomy('aqualuxe_booking_status', 'aqualuxe_booking', [
            'labels' => [
                'name' => __('Booking Statuses', 'aqualuxe'),
                'singular_name' => __('Booking Status', 'aqualuxe'),
                'search_items' => __('Search Booking Statuses', 'aqualuxe'),
                'all_items' => __('All Booking Statuses', 'aqualuxe'),
                'parent_item' => __('Parent Booking Status', 'aqualuxe'),
                'parent_item_colon' => __('Parent Booking Status:', 'aqualuxe'),
                'edit_item' => __('Edit Booking Status', 'aqualuxe'),
                'update_item' => __('Update Booking Status', 'aqualuxe'),
                'add_new_item' => __('Add New Booking Status', 'aqualuxe'),
                'new_item_name' => __('New Booking Status Name', 'aqualuxe'),
                'menu_name' => __('Booking Statuses', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'booking-status'],
            'show_in_rest' => true,
        ]);

        // Register bookable category taxonomy
        register_taxonomy('aqualuxe_bookable_category', 'aqualuxe_bookable', [
            'labels' => [
                'name' => __('Bookable Categories', 'aqualuxe'),
                'singular_name' => __('Bookable Category', 'aqualuxe'),
                'search_items' => __('Search Bookable Categories', 'aqualuxe'),
                'all_items' => __('All Bookable Categories', 'aqualuxe'),
                'parent_item' => __('Parent Bookable Category', 'aqualuxe'),
                'parent_item_colon' => __('Parent Bookable Category:', 'aqualuxe'),
                'edit_item' => __('Edit Bookable Category', 'aqualuxe'),
                'update_item' => __('Update Bookable Category', 'aqualuxe'),
                'add_new_item' => __('Add New Bookable Category', 'aqualuxe'),
                'new_item_name' => __('New Bookable Category Name', 'aqualuxe'),
                'menu_name' => __('Bookable Categories', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'bookable-category'],
            'show_in_rest' => true,
        ]);
    }

    /**
     * Register hooks
     */
    private function register_hooks() {
        // Add meta boxes
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        
        // Save post meta
        add_action('save_post_aqualuxe_booking', [$this, 'save_booking_meta']);
        add_action('save_post_aqualuxe_bookable', [$this, 'save_bookable_meta']);
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
        
        // Add booking form to single bookable item
        add_filter('the_content', [$this, 'add_booking_form_to_content']);
        
        // Add booking shortcodes
        add_action('init', [$this, 'register_shortcodes']);
        
        // Register widgets
        add_action('widgets_init', [$this, 'register_widgets']);
        
        // Add booking data to REST API
        add_action('rest_api_init', [$this, 'register_rest_api']);
        
        // Process booking form submission
        add_action('wp_ajax_aqualuxe_booking_submit', [$this, 'process_booking_submission']);
        add_action('wp_ajax_nopriv_aqualuxe_booking_submit', [$this, 'process_booking_submission']);
        
        // Check availability
        add_action('wp_ajax_aqualuxe_check_availability', [$this, 'check_availability']);
        add_action('wp_ajax_nopriv_aqualuxe_check_availability', [$this, 'check_availability']);
        
        // Process payment
        add_action('wp_ajax_aqualuxe_process_payment', [$this, 'process_payment']);
        add_action('wp_ajax_nopriv_aqualuxe_process_payment', [$this, 'process_payment']);
        
        // Send booking emails
        add_action('aqualuxe_booking_created', [$this, 'send_booking_emails']);
        add_action('aqualuxe_booking_status_changed', [$this, 'send_status_change_email'], 10, 3);
        
        // Add booking dashboard to user account
        add_action('woocommerce_account_bookings_endpoint', [$this, 'user_bookings_endpoint']);
        add_filter('woocommerce_account_menu_items', [$this, 'add_bookings_to_account_menu']);
        add_filter('woocommerce_get_endpoint_url', [$this, 'bookings_endpoint_url'], 10, 4);
        
        // Add booking column to admin list
        add_filter('manage_aqualuxe_booking_posts_columns', [$this, 'add_booking_columns']);
        add_action('manage_aqualuxe_booking_posts_custom_column', [$this, 'render_booking_columns'], 10, 2);
        
        // Add booking filters to admin list
        add_action('restrict_manage_posts', [$this, 'add_booking_filters']);
        add_filter('parse_query', [$this, 'filter_bookings_by_custom_filters']);
        
        // Add booking status counts to admin menu
        add_filter('views_edit-aqualuxe_booking', [$this, 'add_booking_status_counts']);
    }

    /**
     * Register module settings
     */
    private function register_module_settings() {
        $this->register_settings([
            [
                'option_name' => 'booking_page',
                'args' => [
                    'type' => 'integer',
                    'default' => 0,
                    'sanitize_callback' => 'absint',
                ],
            ],
            [
                'option_name' => 'confirmation_page',
                'args' => [
                    'type' => 'integer',
                    'default' => 0,
                    'sanitize_callback' => 'absint',
                ],
            ],
            [
                'option_name' => 'calendar_first_day',
                'args' => [
                    'type' => 'integer',
                    'default' => 0, // 0 = Sunday, 1 = Monday
                    'sanitize_callback' => 'absint',
                ],
            ],
            [
                'option_name' => 'time_format',
                'args' => [
                    'type' => 'string',
                    'default' => 'g:i a', // 12-hour format
                    'sanitize_callback' => 'sanitize_text_field',
                ],
            ],
            [
                'option_name' => 'date_format',
                'args' => [
                    'type' => 'string',
                    'default' => 'F j, Y', // January 1, 2025
                    'sanitize_callback' => 'sanitize_text_field',
                ],
            ],
            [
                'option_name' => 'min_booking_notice',
                'args' => [
                    'type' => 'integer',
                    'default' => 0, // hours
                    'sanitize_callback' => 'absint',
                ],
            ],
            [
                'option_name' => 'max_booking_advance',
                'args' => [
                    'type' => 'integer',
                    'default' => 365, // days
                    'sanitize_callback' => 'absint',
                ],
            ],
            [
                'option_name' => 'enable_payments',
                'args' => [
                    'type' => 'boolean',
                    'default' => true,
                    'sanitize_callback' => 'rest_sanitize_boolean',
                ],
            ],
            [
                'option_name' => 'payment_methods',
                'args' => [
                    'type' => 'array',
                    'default' => ['stripe', 'paypal'],
                    'sanitize_callback' => [$this, 'sanitize_payment_methods'],
                ],
            ],
            [
                'option_name' => 'require_payment',
                'args' => [
                    'type' => 'boolean',
                    'default' => true,
                    'sanitize_callback' => 'rest_sanitize_boolean',
                ],
            ],
            [
                'option_name' => 'admin_email_notification',
                'args' => [
                    'type' => 'boolean',
                    'default' => true,
                    'sanitize_callback' => 'rest_sanitize_boolean',
                ],
            ],
            [
                'option_name' => 'customer_email_notification',
                'args' => [
                    'type' => 'boolean',
                    'default' => true,
                    'sanitize_callback' => 'rest_sanitize_boolean',
                ],
            ],
            [
                'option_name' => 'admin_email',
                'args' => [
                    'type' => 'string',
                    'default' => get_option('admin_email'),
                    'sanitize_callback' => 'sanitize_email',
                ],
            ],
            [
                'option_name' => 'email_from_name',
                'args' => [
                    'type' => 'string',
                    'default' => get_bloginfo('name'),
                    'sanitize_callback' => 'sanitize_text_field',
                ],
            ],
            [
                'option_name' => 'email_from_address',
                'args' => [
                    'type' => 'string',
                    'default' => get_option('admin_email'),
                    'sanitize_callback' => 'sanitize_email',
                ],
            ],
        ]);
    }

    /**
     * Register shortcodes
     */
    private function register_shortcodes() {
        $shortcodes = new Shortcodes($this);
        $shortcodes->register();
    }

    /**
     * Register REST API endpoints
     */
    private function register_rest_api() {
        $rest_api = new REST_API($this);
        $rest_api->register();
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        // Booking details meta box
        add_meta_box(
            'aqualuxe_booking_details',
            __('Booking Details', 'aqualuxe'),
            [$this, 'render_booking_details_meta_box'],
            'aqualuxe_booking',
            'normal',
            'high'
        );
        
        // Customer details meta box
        add_meta_box(
            'aqualuxe_customer_details',
            __('Customer Details', 'aqualuxe'),
            [$this, 'render_customer_details_meta_box'],
            'aqualuxe_booking',
            'normal',
            'high'
        );
        
        // Payment details meta box
        add_meta_box(
            'aqualuxe_payment_details',
            __('Payment Details', 'aqualuxe'),
            [$this, 'render_payment_details_meta_box'],
            'aqualuxe_booking',
            'side',
            'high'
        );
        
        // Booking notes meta box
        add_meta_box(
            'aqualuxe_booking_notes',
            __('Booking Notes', 'aqualuxe'),
            [$this, 'render_booking_notes_meta_box'],
            'aqualuxe_booking',
            'normal',
            'default'
        );
        
        // Bookable item settings meta box
        add_meta_box(
            'aqualuxe_bookable_settings',
            __('Booking Settings', 'aqualuxe'),
            [$this, 'render_bookable_settings_meta_box'],
            'aqualuxe_bookable',
            'normal',
            'high'
        );
        
        // Availability settings meta box
        add_meta_box(
            'aqualuxe_availability_settings',
            __('Availability Settings', 'aqualuxe'),
            [$this, 'render_availability_settings_meta_box'],
            'aqualuxe_bookable',
            'normal',
            'high'
        );
        
        // Pricing settings meta box
        add_meta_box(
            'aqualuxe_pricing_settings',
            __('Pricing Settings', 'aqualuxe'),
            [$this, 'render_pricing_settings_meta_box'],
            'aqualuxe_bookable',
            'normal',
            'high'
        );
    }

    /**
     * Render booking details meta box
     *
     * @param \WP_Post $post
     */
    public function render_booking_details_meta_box($post) {
        // Get booking object
        $booking = new Booking($post->ID);
        
        // Get template
        $this->get_template_part('admin/booking-details', null, [
            'booking' => $booking,
            'module' => $this,
        ]);
    }

    /**
     * Render customer details meta box
     *
     * @param \WP_Post $post
     */
    public function render_customer_details_meta_box($post) {
        // Get booking object
        $booking = new Booking($post->ID);
        
        // Get template
        $this->get_template_part('admin/customer-details', null, [
            'booking' => $booking,
            'module' => $this,
        ]);
    }

    /**
     * Render payment details meta box
     *
     * @param \WP_Post $post
     */
    public function render_payment_details_meta_box($post) {
        // Get booking object
        $booking = new Booking($post->ID);
        
        // Get template
        $this->get_template_part('admin/payment-details', null, [
            'booking' => $booking,
            'module' => $this,
        ]);
    }

    /**
     * Render booking notes meta box
     *
     * @param \WP_Post $post
     */
    public function render_booking_notes_meta_box($post) {
        // Get booking object
        $booking = new Booking($post->ID);
        
        // Get template
        $this->get_template_part('admin/booking-notes', null, [
            'booking' => $booking,
            'module' => $this,
        ]);
    }

    /**
     * Render bookable settings meta box
     *
     * @param \WP_Post $post
     */
    public function render_bookable_settings_meta_box($post) {
        // Get template
        $this->get_template_part('admin/bookable-settings', null, [
            'post' => $post,
            'module' => $this,
        ]);
    }

    /**
     * Render availability settings meta box
     *
     * @param \WP_Post $post
     */
    public function render_availability_settings_meta_box($post) {
        // Get template
        $this->get_template_part('admin/availability-settings', null, [
            'post' => $post,
            'module' => $this,
        ]);
    }

    /**
     * Render pricing settings meta box
     *
     * @param \WP_Post $post
     */
    public function render_pricing_settings_meta_box($post) {
        // Get template
        $this->get_template_part('admin/pricing-settings', null, [
            'post' => $post,
            'module' => $this,
        ]);
    }

    /**
     * Save booking meta
     *
     * @param int $post_id
     */
    public function save_booking_meta($post_id) {
        // Check if our nonce is set
        if (!isset($_POST['aqualuxe_booking_nonce'])) {
            return;
        }
        
        // Verify that the nonce is valid
        if (!wp_verify_nonce($_POST['aqualuxe_booking_nonce'], 'aqualuxe_booking_meta')) {
            return;
        }
        
        // If this is an autosave, our form has not been submitted, so we don't want to do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check the user's permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Get booking object
        $booking = new Booking($post_id);
        
        // Update booking data
        if (isset($_POST['aqualuxe_booking_data'])) {
            $booking_data = $_POST['aqualuxe_booking_data'];
            
            // Update booking item
            if (isset($booking_data['bookable_id'])) {
                $booking->set_bookable_id(absint($booking_data['bookable_id']));
            }
            
            // Update booking dates
            if (isset($booking_data['start_date']) && isset($booking_data['end_date'])) {
                $booking->set_dates(
                    sanitize_text_field($booking_data['start_date']),
                    sanitize_text_field($booking_data['end_date'])
                );
            }
            
            // Update booking status
            if (isset($booking_data['status'])) {
                $booking->set_status(sanitize_key($booking_data['status']));
            }
            
            // Update booking quantity
            if (isset($booking_data['quantity'])) {
                $booking->set_quantity(absint($booking_data['quantity']));
            }
            
            // Update booking price
            if (isset($booking_data['price'])) {
                $booking->set_price(floatval($booking_data['price']));
            }
        }
        
        // Update customer data
        if (isset($_POST['aqualuxe_customer_data'])) {
            $customer_data = $_POST['aqualuxe_customer_data'];
            
            $booking->set_customer_data([
                'name' => isset($customer_data['name']) ? sanitize_text_field($customer_data['name']) : '',
                'email' => isset($customer_data['email']) ? sanitize_email($customer_data['email']) : '',
                'phone' => isset($customer_data['phone']) ? sanitize_text_field($customer_data['phone']) : '',
                'address' => isset($customer_data['address']) ? sanitize_textarea_field($customer_data['address']) : '',
            ]);
        }
        
        // Update payment data
        if (isset($_POST['aqualuxe_payment_data'])) {
            $payment_data = $_POST['aqualuxe_payment_data'];
            
            $booking->set_payment_data([
                'method' => isset($payment_data['method']) ? sanitize_key($payment_data['method']) : '',
                'status' => isset($payment_data['status']) ? sanitize_key($payment_data['status']) : '',
                'transaction_id' => isset($payment_data['transaction_id']) ? sanitize_text_field($payment_data['transaction_id']) : '',
                'amount' => isset($payment_data['amount']) ? floatval($payment_data['amount']) : 0,
                'currency' => isset($payment_data['currency']) ? sanitize_text_field($payment_data['currency']) : '',
                'date' => isset($payment_data['date']) ? sanitize_text_field($payment_data['date']) : '',
            ]);
        }
        
        // Add booking note
        if (isset($_POST['aqualuxe_booking_note']) && !empty($_POST['aqualuxe_booking_note'])) {
            $booking->add_note(
                sanitize_textarea_field($_POST['aqualuxe_booking_note']),
                isset($_POST['aqualuxe_booking_note_type']) ? sanitize_key($_POST['aqualuxe_booking_note_type']) : 'private',
                isset($_POST['aqualuxe_booking_note_author']) ? absint($_POST['aqualuxe_booking_note_author']) : get_current_user_id()
            );
        }
        
        // Save booking
        $booking->save();
    }

    /**
     * Save bookable meta
     *
     * @param int $post_id
     */
    public function save_bookable_meta($post_id) {
        // Check if our nonce is set
        if (!isset($_POST['aqualuxe_bookable_nonce'])) {
            return;
        }
        
        // Verify that the nonce is valid
        if (!wp_verify_nonce($_POST['aqualuxe_bookable_nonce'], 'aqualuxe_bookable_meta')) {
            return;
        }
        
        // If this is an autosave, our form has not been submitted, so we don't want to do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check the user's permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Update booking settings
        if (isset($_POST['aqualuxe_booking_settings'])) {
            $booking_settings = $_POST['aqualuxe_booking_settings'];
            
            // Update booking type
            if (isset($booking_settings['type'])) {
                update_post_meta($post_id, '_aqualuxe_booking_type', sanitize_key($booking_settings['type']));
            }
            
            // Update booking duration
            if (isset($booking_settings['duration'])) {
                update_post_meta($post_id, '_aqualuxe_booking_duration', absint($booking_settings['duration']));
            }
            
            // Update booking duration unit
            if (isset($booking_settings['duration_unit'])) {
                update_post_meta($post_id, '_aqualuxe_booking_duration_unit', sanitize_key($booking_settings['duration_unit']));
            }
            
            // Update booking capacity
            if (isset($booking_settings['capacity'])) {
                update_post_meta($post_id, '_aqualuxe_booking_capacity', absint($booking_settings['capacity']));
            }
            
            // Update booking buffer
            if (isset($booking_settings['buffer'])) {
                update_post_meta($post_id, '_aqualuxe_booking_buffer', absint($booking_settings['buffer']));
            }
            
            // Update booking buffer unit
            if (isset($booking_settings['buffer_unit'])) {
                update_post_meta($post_id, '_aqualuxe_booking_buffer_unit', sanitize_key($booking_settings['buffer_unit']));
            }
        }
        
        // Update availability settings
        if (isset($_POST['aqualuxe_availability_settings'])) {
            $availability_settings = $_POST['aqualuxe_availability_settings'];
            
            // Update availability type
            if (isset($availability_settings['type'])) {
                update_post_meta($post_id, '_aqualuxe_availability_type', sanitize_key($availability_settings['type']));
            }
            
            // Update availability rules
            if (isset($availability_settings['rules']) && is_array($availability_settings['rules'])) {
                $rules = [];
                
                foreach ($availability_settings['rules'] as $rule) {
                    $rules[] = [
                        'type' => isset($rule['type']) ? sanitize_key($rule['type']) : '',
                        'from' => isset($rule['from']) ? sanitize_text_field($rule['from']) : '',
                        'to' => isset($rule['to']) ? sanitize_text_field($rule['to']) : '',
                        'bookable' => isset($rule['bookable']) ? (bool) $rule['bookable'] : true,
                        'priority' => isset($rule['priority']) ? absint($rule['priority']) : 10,
                    ];
                }
                
                update_post_meta($post_id, '_aqualuxe_availability_rules', $rules);
            }
        }
        
        // Update pricing settings
        if (isset($_POST['aqualuxe_pricing_settings'])) {
            $pricing_settings = $_POST['aqualuxe_pricing_settings'];
            
            // Update base price
            if (isset($pricing_settings['base_price'])) {
                update_post_meta($post_id, '_aqualuxe_base_price', floatval($pricing_settings['base_price']));
            }
            
            // Update pricing type
            if (isset($pricing_settings['type'])) {
                update_post_meta($post_id, '_aqualuxe_pricing_type', sanitize_key($pricing_settings['type']));
            }
            
            // Update pricing rules
            if (isset($pricing_settings['rules']) && is_array($pricing_settings['rules'])) {
                $rules = [];
                
                foreach ($pricing_settings['rules'] as $rule) {
                    $rules[] = [
                        'type' => isset($rule['type']) ? sanitize_key($rule['type']) : '',
                        'from' => isset($rule['from']) ? sanitize_text_field($rule['from']) : '',
                        'to' => isset($rule['to']) ? sanitize_text_field($rule['to']) : '',
                        'adjustment' => isset($rule['adjustment']) ? floatval($rule['adjustment']) : 0,
                        'adjustment_type' => isset($rule['adjustment_type']) ? sanitize_key($rule['adjustment_type']) : 'fixed',
                        'priority' => isset($rule['priority']) ? absint($rule['priority']) : 10,
                    ];
                }
                
                update_post_meta($post_id, '_aqualuxe_pricing_rules', $rules);
            }
        }
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Enqueue styles
        $this->enqueue_style('aqualuxe-bookings', 'assets/css/bookings.css');
        
        // Enqueue scripts
        $this->enqueue_script('aqualuxe-bookings', 'assets/js/bookings.js', ['jquery', 'jquery-ui-datepicker', 'jquery-ui-slider']);
        
        // Localize script
        wp_localize_script('aqualuxe-bookings', 'aqualuxeBookings', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-bookings-nonce'),
            'i18n' => [
                'selectDate' => __('Please select a date', 'aqualuxe'),
                'selectTime' => __('Please select a time', 'aqualuxe'),
                'selectEndDate' => __('Please select an end date', 'aqualuxe'),
                'selectEndTime' => __('Please select an end time', 'aqualuxe'),
                'enterName' => __('Please enter your name', 'aqualuxe'),
                'enterEmail' => __('Please enter a valid email address', 'aqualuxe'),
                'enterPhone' => __('Please enter your phone number', 'aqualuxe'),
                'selectQuantity' => __('Please select a quantity', 'aqualuxe'),
                'bookingSuccess' => __('Your booking has been submitted successfully!', 'aqualuxe'),
                'bookingError' => __('There was an error submitting your booking. Please try again.', 'aqualuxe'),
                'checkingAvailability' => __('Checking availability...', 'aqualuxe'),
                'available' => __('Available', 'aqualuxe'),
                'notAvailable' => __('Not available', 'aqualuxe'),
                'processingPayment' => __('Processing payment...', 'aqualuxe'),
                'paymentSuccess' => __('Payment successful!', 'aqualuxe'),
                'paymentError' => __('There was an error processing your payment. Please try again.', 'aqualuxe'),
            ],
            'settings' => [
                'dateFormat' => $this->get_setting('date_format', 'F j, Y'),
                'timeFormat' => $this->get_setting('time_format', 'g:i a'),
                'firstDay' => $this->get_setting('calendar_first_day', 0),
                'minBookingNotice' => $this->get_setting('min_booking_notice', 0),
                'maxBookingAdvance' => $this->get_setting('max_booking_advance', 365),
                'enablePayments' => $this->get_setting('enable_payments', true),
                'requirePayment' => $this->get_setting('require_payment', true),
                'paymentMethods' => $this->get_setting('payment_methods', ['stripe', 'paypal']),
            ],
        ]);
    }

    /**
     * Enqueue admin scripts and styles
     *
     * @param string $hook
     */
    public function admin_scripts($hook) {
        // Only enqueue on booking and bookable item pages
        $screen = get_current_screen();
        
        if (!$screen || !in_array($screen->id, ['aqualuxe_booking', 'aqualuxe_bookable'])) {
            return;
        }
        
        // Enqueue styles
        $this->enqueue_style('aqualuxe-bookings-admin', 'assets/css/bookings-admin.css');
        
        // Enqueue scripts
        $this->enqueue_script('aqualuxe-bookings-admin', 'assets/js/bookings-admin.js', ['jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable']);
        
        // Localize script
        wp_localize_script('aqualuxe-bookings-admin', 'aqualuxeBookingsAdmin', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-bookings-admin-nonce'),
            'i18n' => [
                'confirmDelete' => __('Are you sure you want to delete this item?', 'aqualuxe'),
                'confirmCancel' => __('Are you sure you want to cancel this booking?', 'aqualuxe'),
                'confirmApprove' => __('Are you sure you want to approve this booking?', 'aqualuxe'),
                'confirmReject' => __('Are you sure you want to reject this booking?', 'aqualuxe'),
                'confirmComplete' => __('Are you sure you want to mark this booking as completed?', 'aqualuxe'),
                'confirmRefund' => __('Are you sure you want to refund this booking?', 'aqualuxe'),
                'addRule' => __('Add Rule', 'aqualuxe'),
                'removeRule' => __('Remove', 'aqualuxe'),
                'dateRange' => __('Date Range', 'aqualuxe'),
                'weekDay' => __('Week Day', 'aqualuxe'),
                'time' => __('Time', 'aqualuxe'),
                'custom' => __('Custom', 'aqualuxe'),
                'bookable' => __('Bookable', 'aqualuxe'),
                'notBookable' => __('Not Bookable', 'aqualuxe'),
                'fixed' => __('Fixed', 'aqualuxe'),
                'percentage' => __('Percentage', 'aqualuxe'),
            ],
            'settings' => [
                'dateFormat' => $this->get_setting('date_format', 'F j, Y'),
                'timeFormat' => $this->get_setting('time_format', 'g:i a'),
                'firstDay' => $this->get_setting('calendar_first_day', 0),
            ],
        ]);
    }

    /**
     * Add booking form to content
     *
     * @param string $content
     * @return string
     */
    public function add_booking_form_to_content($content) {
        // Only add to bookable items
        if (!is_singular('aqualuxe_bookable')) {
            return $content;
        }
        
        // Get booking form
        ob_start();
        $this->get_template_part('booking-form', null, [
            'bookable_id' => get_the_ID(),
            'module' => $this,
        ]);
        $booking_form = ob_get_clean();
        
        // Add booking form to content
        $content .= $booking_form;
        
        return $content;
    }

    /**
     * Register widgets
     */
    public function register_widgets() {
        register_widget('\AquaLuxe\Modules\Bookings\Widget');
    }

    /**
     * Process booking submission
     */
    public function process_booking_submission() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-bookings-nonce')) {
            wp_send_json_error(['message' => __('Invalid nonce', 'aqualuxe')]);
        }
        
        // Get booking data
        $bookable_id = isset($_POST['bookable_id']) ? absint($_POST['bookable_id']) : 0;
        $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
        $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';
        $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;
        
        // Get customer data
        $customer_name = isset($_POST['customer_name']) ? sanitize_text_field($_POST['customer_name']) : '';
        $customer_email = isset($_POST['customer_email']) ? sanitize_email($_POST['customer_email']) : '';
        $customer_phone = isset($_POST['customer_phone']) ? sanitize_text_field($_POST['customer_phone']) : '';
        $customer_address = isset($_POST['customer_address']) ? sanitize_textarea_field($_POST['customer_address']) : '';
        $customer_notes = isset($_POST['customer_notes']) ? sanitize_textarea_field($_POST['customer_notes']) : '';
        
        // Validate booking data
        if (!$bookable_id || !$start_date) {
            wp_send_json_error(['message' => __('Invalid booking data', 'aqualuxe')]);
        }
        
        // Validate customer data
        if (!$customer_name || !$customer_email) {
            wp_send_json_error(['message' => __('Invalid customer data', 'aqualuxe')]);
        }
        
        // Check availability
        $availability = new Availability($bookable_id);
        $is_available = $availability->check($start_date, $end_date, $quantity);
        
        if (!$is_available) {
            wp_send_json_error(['message' => __('The selected date/time is not available', 'aqualuxe')]);
        }
        
        // Create booking
        $booking = new Booking();
        $booking->set_bookable_id($bookable_id);
        $booking->set_dates($start_date, $end_date);
        $booking->set_quantity($quantity);
        $booking->set_customer_data([
            'name' => $customer_name,
            'email' => $customer_email,
            'phone' => $customer_phone,
            'address' => $customer_address,
            'notes' => $customer_notes,
        ]);
        
        // Set status to pending
        $booking->set_status('pending');
        
        // Calculate price
        $booking->calculate_price();
        
        // Save booking
        $booking_id = $booking->save();
        
        if (!$booking_id) {
            wp_send_json_error(['message' => __('Error creating booking', 'aqualuxe')]);
        }
        
        // Process payment if enabled
        $payment_required = $this->get_setting('require_payment', true);
        $payment_enabled = $this->get_setting('enable_payments', true);
        
        if ($payment_required && $payment_enabled) {
            // Get payment data
            $payment_method = isset($_POST['payment_method']) ? sanitize_key($_POST['payment_method']) : '';
            $payment_token = isset($_POST['payment_token']) ? sanitize_text_field($_POST['payment_token']) : '';
            
            // Validate payment data
            if (!$payment_method || !$payment_token) {
                wp_send_json_error(['message' => __('Invalid payment data', 'aqualuxe')]);
            }
            
            // Process payment
            $payment = new Payment($booking);
            $payment_result = $payment->process($payment_method, $payment_token);
            
            if (!$payment_result['success']) {
                wp_send_json_error(['message' => $payment_result['message']]);
            }
        }
        
        // Send emails
        do_action('aqualuxe_booking_created', $booking_id);
        
        // Return success
        wp_send_json_success([
            'booking_id' => $booking_id,
            'message' => __('Booking created successfully', 'aqualuxe'),
            'redirect' => get_permalink($this->get_setting('confirmation_page', 0)) . '?booking_id=' . $booking_id,
        ]);
    }

    /**
     * Check availability
     */
    public function check_availability() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-bookings-nonce')) {
            wp_send_json_error(['message' => __('Invalid nonce', 'aqualuxe')]);
        }
        
        // Get booking data
        $bookable_id = isset($_POST['bookable_id']) ? absint($_POST['bookable_id']) : 0;
        $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
        $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';
        $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;
        
        // Validate booking data
        if (!$bookable_id || !$start_date) {
            wp_send_json_error(['message' => __('Invalid booking data', 'aqualuxe')]);
        }
        
        // Check availability
        $availability = new Availability($bookable_id);
        $is_available = $availability->check($start_date, $end_date, $quantity);
        
        // Calculate price
        $price = 0;
        
        if ($is_available) {
            $booking = new Booking();
            $booking->set_bookable_id($bookable_id);
            $booking->set_dates($start_date, $end_date);
            $booking->set_quantity($quantity);
            $price = $booking->calculate_price();
        }
        
        // Return result
        wp_send_json_success([
            'available' => $is_available,
            'price' => $price,
            'formatted_price' => $this->format_price($price),
        ]);
    }

    /**
     * Process payment
     */
    public function process_payment() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-bookings-nonce')) {
            wp_send_json_error(['message' => __('Invalid nonce', 'aqualuxe')]);
        }
        
        // Get payment data
        $booking_id = isset($_POST['booking_id']) ? absint($_POST['booking_id']) : 0;
        $payment_method = isset($_POST['payment_method']) ? sanitize_key($_POST['payment_method']) : '';
        $payment_token = isset($_POST['payment_token']) ? sanitize_text_field($_POST['payment_token']) : '';
        
        // Validate payment data
        if (!$booking_id || !$payment_method || !$payment_token) {
            wp_send_json_error(['message' => __('Invalid payment data', 'aqualuxe')]);
        }
        
        // Get booking
        $booking = new Booking($booking_id);
        
        if (!$booking->exists()) {
            wp_send_json_error(['message' => __('Invalid booking', 'aqualuxe')]);
        }
        
        // Process payment
        $payment = new Payment($booking);
        $payment_result = $payment->process($payment_method, $payment_token);
        
        if (!$payment_result['success']) {
            wp_send_json_error(['message' => $payment_result['message']]);
        }
        
        // Return success
        wp_send_json_success([
            'message' => __('Payment processed successfully', 'aqualuxe'),
            'redirect' => get_permalink($this->get_setting('confirmation_page', 0)) . '?booking_id=' . $booking_id,
        ]);
    }

    /**
     * Send booking emails
     *
     * @param int $booking_id
     */
    public function send_booking_emails($booking_id) {
        // Get booking
        $booking = new Booking($booking_id);
        
        if (!$booking->exists()) {
            return;
        }
        
        // Send emails
        $email = new Email($booking);
        
        // Send admin notification
        if ($this->get_setting('admin_email_notification', true)) {
            $email->send_admin_notification();
        }
        
        // Send customer notification
        if ($this->get_setting('customer_email_notification', true)) {
            $email->send_customer_notification();
        }
    }

    /**
     * Send status change email
     *
     * @param int $booking_id
     * @param string $old_status
     * @param string $new_status
     */
    public function send_status_change_email($booking_id, $old_status, $new_status) {
        // Get booking
        $booking = new Booking($booking_id);
        
        if (!$booking->exists()) {
            return;
        }
        
        // Send status change email
        $email = new Email($booking);
        $email->send_status_change_notification($old_status, $new_status);
    }

    /**
     * User bookings endpoint
     */
    public function user_bookings_endpoint() {
        // Get user bookings
        $user_id = get_current_user_id();
        $bookings = $this->get_user_bookings($user_id);
        
        // Get template
        $this->get_template_part('account/bookings', null, [
            'bookings' => $bookings,
            'module' => $this,
        ]);
    }

    /**
     * Add bookings to account menu
     *
     * @param array $items
     * @return array
     */
    public function add_bookings_to_account_menu($items) {
        // Add bookings item
        $items['bookings'] = __('Bookings', 'aqualuxe');
        
        return $items;
    }

    /**
     * Bookings endpoint URL
     *
     * @param string $url
     * @param string $endpoint
     * @param string $value
     * @param string $permalink
     * @return string
     */
    public function bookings_endpoint_url($url, $endpoint, $value, $permalink) {
        if ($endpoint === 'bookings') {
            // Get booking page URL
            $booking_page_id = $this->get_setting('booking_page', 0);
            
            if ($booking_page_id) {
                $url = get_permalink($booking_page_id);
                
                if ($value) {
                    $url = add_query_arg('booking_id', $value, $url);
                }
            }
        }
        
        return $url;
    }

    /**
     * Add booking columns
     *
     * @param array $columns
     * @return array
     */
    public function add_booking_columns($columns) {
        $new_columns = [];
        
        // Add checkbox
        if (isset($columns['cb'])) {
            $new_columns['cb'] = $columns['cb'];
        }
        
        // Add custom columns
        $new_columns['title'] = __('Booking', 'aqualuxe');
        $new_columns['bookable'] = __('Bookable Item', 'aqualuxe');
        $new_columns['customer'] = __('Customer', 'aqualuxe');
        $new_columns['dates'] = __('Dates', 'aqualuxe');
        $new_columns['status'] = __('Status', 'aqualuxe');
        $new_columns['payment'] = __('Payment', 'aqualuxe');
        $new_columns['price'] = __('Price', 'aqualuxe');
        $new_columns['date'] = __('Created', 'aqualuxe');
        
        return $new_columns;
    }

    /**
     * Render booking columns
     *
     * @param string $column
     * @param int $post_id
     */
    public function render_booking_columns($column, $post_id) {
        // Get booking
        $booking = new Booking($post_id);
        
        if (!$booking->exists()) {
            return;
        }
        
        switch ($column) {
            case 'bookable':
                $bookable_id = $booking->get_bookable_id();
                $bookable_title = get_the_title($bookable_id);
                echo '<a href="' . esc_url(get_edit_post_link($bookable_id)) . '">' . esc_html($bookable_title) . '</a>';
                break;
                
            case 'customer':
                $customer = $booking->get_customer_data();
                echo esc_html($customer['name']) . '<br>';
                echo '<a href="mailto:' . esc_attr($customer['email']) . '">' . esc_html($customer['email']) . '</a>';
                break;
                
            case 'dates':
                $start_date = $booking->get_start_date();
                $end_date = $booking->get_end_date();
                
                echo esc_html(date_i18n($this->get_setting('date_format', 'F j, Y'), strtotime($start_date)));
                
                if ($end_date && $end_date !== $start_date) {
                    echo ' - ' . esc_html(date_i18n($this->get_setting('date_format', 'F j, Y'), strtotime($end_date)));
                }
                break;
                
            case 'status':
                $status = $booking->get_status();
                echo '<span class="booking-status booking-status--' . esc_attr($status) . '">' . esc_html($this->get_status_label($status)) . '</span>';
                break;
                
            case 'payment':
                $payment = $booking->get_payment_data();
                
                if ($payment && isset($payment['status'])) {
                    echo '<span class="payment-status payment-status--' . esc_attr($payment['status']) . '">' . esc_html($this->get_payment_status_label($payment['status'])) . '</span>';
                } else {
                    echo '<span class="payment-status payment-status--none">' . esc_html__('None', 'aqualuxe') . '</span>';
                }
                break;
                
            case 'price':
                $price = $booking->get_price();
                echo esc_html($this->format_price($price));
                break;
        }
    }

    /**
     * Add booking filters
     *
     * @param string $post_type
     */
    public function add_booking_filters($post_type) {
        if ($post_type !== 'aqualuxe_booking') {
            return;
        }
        
        // Add status filter
        $statuses = $this->get_booking_statuses();
        $current_status = isset($_GET['booking_status']) ? sanitize_key($_GET['booking_status']) : '';
        
        echo '<select name="booking_status">';
        echo '<option value="">' . esc_html__('All Statuses', 'aqualuxe') . '</option>';
        
        foreach ($statuses as $status => $label) {
            echo '<option value="' . esc_attr($status) . '" ' . selected($current_status, $status, false) . '>' . esc_html($label) . '</option>';
        }
        
        echo '</select>';
        
        // Add date filter
        $current_date = isset($_GET['booking_date']) ? sanitize_text_field($_GET['booking_date']) : '';
        
        echo '<input type="text" name="booking_date" placeholder="' . esc_attr__('Filter by date', 'aqualuxe') . '" value="' . esc_attr($current_date) . '" class="date-picker" />';
    }

    /**
     * Filter bookings by custom filters
     *
     * @param \WP_Query $query
     */
    public function filter_bookings_by_custom_filters($query) {
        global $pagenow;
        
        // Check if we're in the correct screen
        if (!is_admin() || $pagenow !== 'edit.php' || !isset($query->query['post_type']) || $query->query['post_type'] !== 'aqualuxe_booking') {
            return;
        }
        
        // Filter by status
        if (isset($_GET['booking_status']) && !empty($_GET['booking_status'])) {
            $status = sanitize_key($_GET['booking_status']);
            
            $query->query_vars['meta_query'][] = [
                'key' => '_aqualuxe_booking_status',
                'value' => $status,
                'compare' => '=',
            ];
        }
        
        // Filter by date
        if (isset($_GET['booking_date']) && !empty($_GET['booking_date'])) {
            $date = sanitize_text_field($_GET['booking_date']);
            $date_timestamp = strtotime($date);
            
            if ($date_timestamp) {
                $start_of_day = date('Y-m-d 00:00:00', $date_timestamp);
                $end_of_day = date('Y-m-d 23:59:59', $date_timestamp);
                
                $query->query_vars['meta_query'][] = [
                    'relation' => 'OR',
                    [
                        'key' => '_aqualuxe_booking_start_date',
                        'value' => [$start_of_day, $end_of_day],
                        'compare' => 'BETWEEN',
                        'type' => 'DATETIME',
                    ],
                    [
                        'key' => '_aqualuxe_booking_end_date',
                        'value' => [$start_of_day, $end_of_day],
                        'compare' => 'BETWEEN',
                        'type' => 'DATETIME',
                    ],
                ];
            }
        }
    }

    /**
     * Add booking status counts
     *
     * @param array $views
     * @return array
     */
    public function add_booking_status_counts($views) {
        global $wpdb;
        
        // Get booking statuses
        $statuses = $this->get_booking_statuses();
        
        // Get counts
        $counts = $wpdb->get_results("
            SELECT meta_value, COUNT(*) as count
            FROM $wpdb->postmeta
            WHERE meta_key = '_aqualuxe_booking_status'
            GROUP BY meta_value
        ");
        
        // Format counts
        $status_counts = [];
        
        foreach ($counts as $count) {
            $status_counts[$count->meta_value] = $count->count;
        }
        
        // Add views
        $new_views = [];
        
        // Add all view
        $new_views['all'] = $views['all'];
        
        // Add status views
        foreach ($statuses as $status => $label) {
            $count = isset($status_counts[$status]) ? $status_counts[$status] : 0;
            $new_views[$status] = sprintf(
                '<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
                add_query_arg('booking_status', $status, admin_url('edit.php?post_type=aqualuxe_booking')),
                isset($_GET['booking_status']) && $_GET['booking_status'] === $status ? 'current' : '',
                $label,
                $count
            );
        }
        
        return $new_views;
    }

    /**
     * Get user bookings
     *
     * @param int $user_id
     * @return array
     */
    public function get_user_bookings($user_id) {
        $args = [
            'post_type' => 'aqualuxe_booking',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_aqualuxe_booking_customer_email',
                    'value' => get_userdata($user_id)->user_email,
                    'compare' => '=',
                ],
            ],
        ];
        
        $query = new \WP_Query($args);
        $bookings = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $bookings[] = new Booking(get_the_ID());
            }
            wp_reset_postdata();
        }
        
        return $bookings;
    }

    /**
     * Get booking statuses
     *
     * @return array
     */
    public function get_booking_statuses() {
        return [
            'pending' => __('Pending', 'aqualuxe'),
            'confirmed' => __('Confirmed', 'aqualuxe'),
            'cancelled' => __('Cancelled', 'aqualuxe'),
            'completed' => __('Completed', 'aqualuxe'),
            'refunded' => __('Refunded', 'aqualuxe'),
            'failed' => __('Failed', 'aqualuxe'),
        ];
    }

    /**
     * Get status label
     *
     * @param string $status
     * @return string
     */
    public function get_status_label($status) {
        $statuses = $this->get_booking_statuses();
        return isset($statuses[$status]) ? $statuses[$status] : $status;
    }

    /**
     * Get payment statuses
     *
     * @return array
     */
    public function get_payment_statuses() {
        return [
            'pending' => __('Pending', 'aqualuxe'),
            'completed' => __('Completed', 'aqualuxe'),
            'failed' => __('Failed', 'aqualuxe'),
            'refunded' => __('Refunded', 'aqualuxe'),
            'cancelled' => __('Cancelled', 'aqualuxe'),
        ];
    }

    /**
     * Get payment status label
     *
     * @param string $status
     * @return string
     */
    public function get_payment_status_label($status) {
        $statuses = $this->get_payment_statuses();
        return isset($statuses[$status]) ? $statuses[$status] : $status;
    }

    /**
     * Format price
     *
     * @param float $price
     * @return string
     */
    public function format_price($price) {
        return sprintf(
            get_woocommerce_price_format(),
            get_woocommerce_currency_symbol(),
            number_format($price, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator())
        );
    }

    /**
     * Sanitize payment methods
     *
     * @param array $methods
     * @return array
     */
    public function sanitize_payment_methods($methods) {
        $sanitized = [];
        
        if (is_array($methods)) {
            foreach ($methods as $method) {
                $sanitized[] = sanitize_key($method);
            }
        }
        
        return $sanitized;
    }
}

// Register module
return new Module();