<?php
/**
 * Events Module
 *
 * @package AquaLuxe\Modules\Events
 */

namespace AquaLuxe\Modules\Events;

/**
 * Events Module Class
 */
class Module extends \AquaLuxe\Module {
    /**
     * Module ID
     *
     * @var string
     */
    protected $id = 'events';

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Events';

    /**
     * Module description
     *
     * @var string
     */
    protected $description = 'Adds event management and ticketing functionality to the theme.';

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
        require_once $this->path . 'classes/Event.php';
        require_once $this->path . 'classes/Ticket.php';
        require_once $this->path . 'classes/Calendar.php';
        require_once $this->path . 'classes/Registration.php';
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
        // Register event post type
        register_post_type('aqualuxe_event', [
            'labels' => [
                'name' => __('Events', 'aqualuxe'),
                'singular_name' => __('Event', 'aqualuxe'),
                'add_new' => __('Add New', 'aqualuxe'),
                'add_new_item' => __('Add New Event', 'aqualuxe'),
                'edit_item' => __('Edit Event', 'aqualuxe'),
                'new_item' => __('New Event', 'aqualuxe'),
                'view_item' => __('View Event', 'aqualuxe'),
                'search_items' => __('Search Events', 'aqualuxe'),
                'not_found' => __('No events found', 'aqualuxe'),
                'not_found_in_trash' => __('No events found in trash', 'aqualuxe'),
                'all_items' => __('All Events', 'aqualuxe'),
                'menu_name' => __('Events', 'aqualuxe'),
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'event'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 20,
            'menu_icon' => 'dashicons-calendar-alt',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'comments'],
            'show_in_rest' => true,
        ]);

        // Register ticket post type
        register_post_type('aqualuxe_ticket', [
            'labels' => [
                'name' => __('Tickets', 'aqualuxe'),
                'singular_name' => __('Ticket', 'aqualuxe'),
                'add_new' => __('Add New', 'aqualuxe'),
                'add_new_item' => __('Add New Ticket', 'aqualuxe'),
                'edit_item' => __('Edit Ticket', 'aqualuxe'),
                'new_item' => __('New Ticket', 'aqualuxe'),
                'view_item' => __('View Ticket', 'aqualuxe'),
                'search_items' => __('Search Tickets', 'aqualuxe'),
                'not_found' => __('No tickets found', 'aqualuxe'),
                'not_found_in_trash' => __('No tickets found in trash', 'aqualuxe'),
                'all_items' => __('All Tickets', 'aqualuxe'),
                'menu_name' => __('Tickets', 'aqualuxe'),
            ],
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=aqualuxe_event',
            'query_var' => true,
            'rewrite' => ['slug' => 'ticket'],
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'supports' => ['title', 'author'],
            'show_in_rest' => true,
        ]);

        // Register registration post type
        register_post_type('aqualuxe_registration', [
            'labels' => [
                'name' => __('Registrations', 'aqualuxe'),
                'singular_name' => __('Registration', 'aqualuxe'),
                'add_new' => __('Add New', 'aqualuxe'),
                'add_new_item' => __('Add New Registration', 'aqualuxe'),
                'edit_item' => __('Edit Registration', 'aqualuxe'),
                'new_item' => __('New Registration', 'aqualuxe'),
                'view_item' => __('View Registration', 'aqualuxe'),
                'search_items' => __('Search Registrations', 'aqualuxe'),
                'not_found' => __('No registrations found', 'aqualuxe'),
                'not_found_in_trash' => __('No registrations found in trash', 'aqualuxe'),
                'all_items' => __('All Registrations', 'aqualuxe'),
                'menu_name' => __('Registrations', 'aqualuxe'),
            ],
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=aqualuxe_event',
            'query_var' => true,
            'rewrite' => ['slug' => 'registration'],
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'supports' => ['title', 'author'],
            'show_in_rest' => true,
        ]);
    }

    /**
     * Register taxonomies
     */
    private function register_taxonomies() {
        // Register event category taxonomy
        register_taxonomy('aqualuxe_event_category', 'aqualuxe_event', [
            'labels' => [
                'name' => __('Event Categories', 'aqualuxe'),
                'singular_name' => __('Event Category', 'aqualuxe'),
                'search_items' => __('Search Event Categories', 'aqualuxe'),
                'all_items' => __('All Event Categories', 'aqualuxe'),
                'parent_item' => __('Parent Event Category', 'aqualuxe'),
                'parent_item_colon' => __('Parent Event Category:', 'aqualuxe'),
                'edit_item' => __('Edit Event Category', 'aqualuxe'),
                'update_item' => __('Update Event Category', 'aqualuxe'),
                'add_new_item' => __('Add New Event Category', 'aqualuxe'),
                'new_item_name' => __('New Event Category Name', 'aqualuxe'),
                'menu_name' => __('Categories', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'event-category'],
            'show_in_rest' => true,
        ]);

        // Register event tag taxonomy
        register_taxonomy('aqualuxe_event_tag', 'aqualuxe_event', [
            'labels' => [
                'name' => __('Event Tags', 'aqualuxe'),
                'singular_name' => __('Event Tag', 'aqualuxe'),
                'search_items' => __('Search Event Tags', 'aqualuxe'),
                'all_items' => __('All Event Tags', 'aqualuxe'),
                'parent_item' => __('Parent Event Tag', 'aqualuxe'),
                'parent_item_colon' => __('Parent Event Tag:', 'aqualuxe'),
                'edit_item' => __('Edit Event Tag', 'aqualuxe'),
                'update_item' => __('Update Event Tag', 'aqualuxe'),
                'add_new_item' => __('Add New Event Tag', 'aqualuxe'),
                'new_item_name' => __('New Event Tag Name', 'aqualuxe'),
                'menu_name' => __('Tags', 'aqualuxe'),
            ],
            'hierarchical' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'event-tag'],
            'show_in_rest' => true,
        ]);

        // Register ticket type taxonomy
        register_taxonomy('aqualuxe_ticket_type', 'aqualuxe_ticket', [
            'labels' => [
                'name' => __('Ticket Types', 'aqualuxe'),
                'singular_name' => __('Ticket Type', 'aqualuxe'),
                'search_items' => __('Search Ticket Types', 'aqualuxe'),
                'all_items' => __('All Ticket Types', 'aqualuxe'),
                'parent_item' => __('Parent Ticket Type', 'aqualuxe'),
                'parent_item_colon' => __('Parent Ticket Type:', 'aqualuxe'),
                'edit_item' => __('Edit Ticket Type', 'aqualuxe'),
                'update_item' => __('Update Ticket Type', 'aqualuxe'),
                'add_new_item' => __('Add New Ticket Type', 'aqualuxe'),
                'new_item_name' => __('New Ticket Type Name', 'aqualuxe'),
                'menu_name' => __('Ticket Types', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'ticket-type'],
            'show_in_rest' => true,
        ]);

        // Register registration status taxonomy
        register_taxonomy('aqualuxe_registration_status', 'aqualuxe_registration', [
            'labels' => [
                'name' => __('Registration Statuses', 'aqualuxe'),
                'singular_name' => __('Registration Status', 'aqualuxe'),
                'search_items' => __('Search Registration Statuses', 'aqualuxe'),
                'all_items' => __('All Registration Statuses', 'aqualuxe'),
                'parent_item' => __('Parent Registration Status', 'aqualuxe'),
                'parent_item_colon' => __('Parent Registration Status:', 'aqualuxe'),
                'edit_item' => __('Edit Registration Status', 'aqualuxe'),
                'update_item' => __('Update Registration Status', 'aqualuxe'),
                'add_new_item' => __('Add New Registration Status', 'aqualuxe'),
                'new_item_name' => __('New Registration Status Name', 'aqualuxe'),
                'menu_name' => __('Statuses', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'registration-status'],
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
        add_action('save_post_aqualuxe_event', [$this, 'save_event_meta']);
        add_action('save_post_aqualuxe_ticket', [$this, 'save_ticket_meta']);
        add_action('save_post_aqualuxe_registration', [$this, 'save_registration_meta']);
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
        
        // Add event content to single event
        add_filter('the_content', [$this, 'add_event_content']);
        
        // Add event shortcodes
        add_action('init', [$this, 'register_shortcodes']);
        
        // Register widgets
        add_action('widgets_init', [$this, 'register_widgets']);
        
        // Add event data to REST API
        add_action('rest_api_init', [$this, 'register_rest_api']);
        
        // Process registration form submission
        add_action('wp_ajax_aqualuxe_event_registration', [$this, 'process_registration']);
        add_action('wp_ajax_nopriv_aqualuxe_event_registration', [$this, 'process_registration']);
        
        // Process payment
        add_action('wp_ajax_aqualuxe_process_event_payment', [$this, 'process_payment']);
        add_action('wp_ajax_nopriv_aqualuxe_process_event_payment', [$this, 'process_payment']);
        
        // Send registration emails
        add_action('aqualuxe_registration_created', [$this, 'send_registration_emails']);
        add_action('aqualuxe_registration_status_changed', [$this, 'send_status_change_email'], 10, 3);
        
        // Add registration dashboard to user account
        add_action('woocommerce_account_registrations_endpoint', [$this, 'user_registrations_endpoint']);
        add_filter('woocommerce_account_menu_items', [$this, 'add_registrations_to_account_menu']);
        add_filter('woocommerce_get_endpoint_url', [$this, 'registrations_endpoint_url'], 10, 4);
        
        // Add registration column to admin list
        add_filter('manage_aqualuxe_registration_posts_columns', [$this, 'add_registration_columns']);
        add_action('manage_aqualuxe_registration_posts_custom_column', [$this, 'render_registration_columns'], 10, 2);
        
        // Add registration filters to admin list
        add_action('restrict_manage_posts', [$this, 'add_registration_filters']);
        add_filter('parse_query', [$this, 'filter_registrations_by_custom_filters']);
        
        // Add registration status counts to admin menu
        add_filter('views_edit-aqualuxe_registration', [$this, 'add_registration_status_counts']);
        
        // Add event calendar to admin dashboard
        add_action('wp_dashboard_setup', [$this, 'add_dashboard_widget']);
        
        // Add event schema markup
        add_action('wp_head', [$this, 'add_event_schema']);
        
        // Add event structured data
        add_filter('wpseo_schema_graph_pieces', [$this, 'add_event_structured_data'], 11, 2);
    }

    /**
     * Register module settings
     */
    private function register_module_settings() {
        $this->register_settings([
            [
                'option_name' => 'events_page',
                'args' => [
                    'type' => 'integer',
                    'default' => 0,
                    'sanitize_callback' => 'absint',
                ],
            ],
            [
                'option_name' => 'calendar_page',
                'args' => [
                    'type' => 'integer',
                    'default' => 0,
                    'sanitize_callback' => 'absint',
                ],
            ],
            [
                'option_name' => 'registration_page',
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
                'option_name' => 'attendee_email_notification',
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
            [
                'option_name' => 'google_maps_api_key',
                'args' => [
                    'type' => 'string',
                    'default' => '',
                    'sanitize_callback' => 'sanitize_text_field',
                ],
            ],
            [
                'option_name' => 'show_map',
                'args' => [
                    'type' => 'boolean',
                    'default' => true,
                    'sanitize_callback' => 'rest_sanitize_boolean',
                ],
            ],
            [
                'option_name' => 'show_organizer',
                'args' => [
                    'type' => 'boolean',
                    'default' => true,
                    'sanitize_callback' => 'rest_sanitize_boolean',
                ],
            ],
            [
                'option_name' => 'show_venue',
                'args' => [
                    'type' => 'boolean',
                    'default' => true,
                    'sanitize_callback' => 'rest_sanitize_boolean',
                ],
            ],
            [
                'option_name' => 'show_related_events',
                'args' => [
                    'type' => 'boolean',
                    'default' => true,
                    'sanitize_callback' => 'rest_sanitize_boolean',
                ],
            ],
            [
                'option_name' => 'show_sharing',
                'args' => [
                    'type' => 'boolean',
                    'default' => true,
                    'sanitize_callback' => 'rest_sanitize_boolean',
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
        // Event details meta box
        add_meta_box(
            'aqualuxe_event_details',
            __('Event Details', 'aqualuxe'),
            [$this, 'render_event_details_meta_box'],
            'aqualuxe_event',
            'normal',
            'high'
        );
        
        // Event venue meta box
        add_meta_box(
            'aqualuxe_event_venue',
            __('Event Venue', 'aqualuxe'),
            [$this, 'render_event_venue_meta_box'],
            'aqualuxe_event',
            'normal',
            'high'
        );
        
        // Event organizer meta box
        add_meta_box(
            'aqualuxe_event_organizer',
            __('Event Organizer', 'aqualuxe'),
            [$this, 'render_event_organizer_meta_box'],
            'aqualuxe_event',
            'normal',
            'high'
        );
        
        // Event tickets meta box
        add_meta_box(
            'aqualuxe_event_tickets',
            __('Event Tickets', 'aqualuxe'),
            [$this, 'render_event_tickets_meta_box'],
            'aqualuxe_event',
            'normal',
            'high'
        );
        
        // Event registrations meta box
        add_meta_box(
            'aqualuxe_event_registrations',
            __('Event Registrations', 'aqualuxe'),
            [$this, 'render_event_registrations_meta_box'],
            'aqualuxe_event',
            'normal',
            'default'
        );
        
        // Ticket details meta box
        add_meta_box(
            'aqualuxe_ticket_details',
            __('Ticket Details', 'aqualuxe'),
            [$this, 'render_ticket_details_meta_box'],
            'aqualuxe_ticket',
            'normal',
            'high'
        );
        
        // Registration details meta box
        add_meta_box(
            'aqualuxe_registration_details',
            __('Registration Details', 'aqualuxe'),
            [$this, 'render_registration_details_meta_box'],
            'aqualuxe_registration',
            'normal',
            'high'
        );
        
        // Attendee details meta box
        add_meta_box(
            'aqualuxe_attendee_details',
            __('Attendee Details', 'aqualuxe'),
            [$this, 'render_attendee_details_meta_box'],
            'aqualuxe_registration',
            'normal',
            'high'
        );
        
        // Payment details meta box
        add_meta_box(
            'aqualuxe_payment_details',
            __('Payment Details', 'aqualuxe'),
            [$this, 'render_payment_details_meta_box'],
            'aqualuxe_registration',
            'side',
            'high'
        );
    }

    /**
     * Render event details meta box
     *
     * @param \WP_Post $post
     */
    public function render_event_details_meta_box($post) {
        // Get event object
        $event = new Event($post->ID);
        
        // Get template
        $this->get_template_part('admin/event-details', null, [
            'event' => $event,
            'module' => $this,
        ]);
    }

    /**
     * Render event venue meta box
     *
     * @param \WP_Post $post
     */
    public function render_event_venue_meta_box($post) {
        // Get event object
        $event = new Event($post->ID);
        
        // Get template
        $this->get_template_part('admin/event-venue', null, [
            'event' => $event,
            'module' => $this,
        ]);
    }

    /**
     * Render event organizer meta box
     *
     * @param \WP_Post $post
     */
    public function render_event_organizer_meta_box($post) {
        // Get event object
        $event = new Event($post->ID);
        
        // Get template
        $this->get_template_part('admin/event-organizer', null, [
            'event' => $event,
            'module' => $this,
        ]);
    }

    /**
     * Render event tickets meta box
     *
     * @param \WP_Post $post
     */
    public function render_event_tickets_meta_box($post) {
        // Get event object
        $event = new Event($post->ID);
        
        // Get template
        $this->get_template_part('admin/event-tickets', null, [
            'event' => $event,
            'module' => $this,
        ]);
    }

    /**
     * Render event registrations meta box
     *
     * @param \WP_Post $post
     */
    public function render_event_registrations_meta_box($post) {
        // Get event object
        $event = new Event($post->ID);
        
        // Get template
        $this->get_template_part('admin/event-registrations', null, [
            'event' => $event,
            'module' => $this,
        ]);
    }

    /**
     * Render ticket details meta box
     *
     * @param \WP_Post $post
     */
    public function render_ticket_details_meta_box($post) {
        // Get ticket object
        $ticket = new Ticket($post->ID);
        
        // Get template
        $this->get_template_part('admin/ticket-details', null, [
            'ticket' => $ticket,
            'module' => $this,
        ]);
    }

    /**
     * Render registration details meta box
     *
     * @param \WP_Post $post
     */
    public function render_registration_details_meta_box($post) {
        // Get registration object
        $registration = new Registration($post->ID);
        
        // Get template
        $this->get_template_part('admin/registration-details', null, [
            'registration' => $registration,
            'module' => $this,
        ]);
    }

    /**
     * Render attendee details meta box
     *
     * @param \WP_Post $post
     */
    public function render_attendee_details_meta_box($post) {
        // Get registration object
        $registration = new Registration($post->ID);
        
        // Get template
        $this->get_template_part('admin/attendee-details', null, [
            'registration' => $registration,
            'module' => $this,
        ]);
    }

    /**
     * Render payment details meta box
     *
     * @param \WP_Post $post
     */
    public function render_payment_details_meta_box($post) {
        // Get registration object
        $registration = new Registration($post->ID);
        
        // Get template
        $this->get_template_part('admin/payment-details', null, [
            'registration' => $registration,
            'module' => $this,
        ]);
    }

    /**
     * Save event meta
     *
     * @param int $post_id
     */
    public function save_event_meta($post_id) {
        // Check if our nonce is set
        if (!isset($_POST['aqualuxe_event_nonce'])) {
            return;
        }
        
        // Verify that the nonce is valid
        if (!wp_verify_nonce($_POST['aqualuxe_event_nonce'], 'aqualuxe_event_meta')) {
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
        
        // Get event object
        $event = new Event($post_id);
        
        // Update event data
        if (isset($_POST['aqualuxe_event_data'])) {
            $event_data = $_POST['aqualuxe_event_data'];
            
            // Update event dates
            if (isset($event_data['start_date']) && isset($event_data['end_date'])) {
                $event->set_dates(
                    sanitize_text_field($event_data['start_date']),
                    sanitize_text_field($event_data['end_date'])
                );
            }
            
            // Update event time
            if (isset($event_data['start_time']) && isset($event_data['end_time'])) {
                $event->set_times(
                    sanitize_text_field($event_data['start_time']),
                    sanitize_text_field($event_data['end_time'])
                );
            }
            
            // Update event status
            if (isset($event_data['status'])) {
                $event->set_status(sanitize_key($event_data['status']));
            }
            
            // Update event capacity
            if (isset($event_data['capacity'])) {
                $event->set_capacity(absint($event_data['capacity']));
            }
            
            // Update event registration status
            if (isset($event_data['registration_status'])) {
                $event->set_registration_status(sanitize_key($event_data['registration_status']));
            }
            
            // Update event registration start date
            if (isset($event_data['registration_start_date'])) {
                $event->set_registration_start_date(sanitize_text_field($event_data['registration_start_date']));
            }
            
            // Update event registration end date
            if (isset($event_data['registration_end_date'])) {
                $event->set_registration_end_date(sanitize_text_field($event_data['registration_end_date']));
            }
            
            // Update event featured status
            if (isset($event_data['featured'])) {
                $event->set_featured(rest_sanitize_boolean($event_data['featured']));
            }
            
            // Update event cost
            if (isset($event_data['cost'])) {
                $event->set_cost(floatval($event_data['cost']));
            }
            
            // Update event cost description
            if (isset($event_data['cost_description'])) {
                $event->set_cost_description(sanitize_text_field($event_data['cost_description']));
            }
            
            // Update event currency
            if (isset($event_data['currency'])) {
                $event->set_currency(sanitize_text_field($event_data['currency']));
            }
            
            // Update event website
            if (isset($event_data['website'])) {
                $event->set_website(esc_url_raw($event_data['website']));
            }
            
            // Update event phone
            if (isset($event_data['phone'])) {
                $event->set_phone(sanitize_text_field($event_data['phone']));
            }
            
            // Update event email
            if (isset($event_data['email'])) {
                $event->set_email(sanitize_email($event_data['email']));
            }
        }
        
        // Update venue data
        if (isset($_POST['aqualuxe_venue_data'])) {
            $venue_data = $_POST['aqualuxe_venue_data'];
            
            $event->set_venue_data([
                'name' => isset($venue_data['name']) ? sanitize_text_field($venue_data['name']) : '',
                'address' => isset($venue_data['address']) ? sanitize_textarea_field($venue_data['address']) : '',
                'city' => isset($venue_data['city']) ? sanitize_text_field($venue_data['city']) : '',
                'state' => isset($venue_data['state']) ? sanitize_text_field($venue_data['state']) : '',
                'zip' => isset($venue_data['zip']) ? sanitize_text_field($venue_data['zip']) : '',
                'country' => isset($venue_data['country']) ? sanitize_text_field($venue_data['country']) : '',
                'latitude' => isset($venue_data['latitude']) ? floatval($venue_data['latitude']) : '',
                'longitude' => isset($venue_data['longitude']) ? floatval($venue_data['longitude']) : '',
                'website' => isset($venue_data['website']) ? esc_url_raw($venue_data['website']) : '',
                'phone' => isset($venue_data['phone']) ? sanitize_text_field($venue_data['phone']) : '',
            ]);
        }
        
        // Update organizer data
        if (isset($_POST['aqualuxe_organizer_data'])) {
            $organizer_data = $_POST['aqualuxe_organizer_data'];
            
            $event->set_organizer_data([
                'name' => isset($organizer_data['name']) ? sanitize_text_field($organizer_data['name']) : '',
                'description' => isset($organizer_data['description']) ? sanitize_textarea_field($organizer_data['description']) : '',
                'website' => isset($organizer_data['website']) ? esc_url_raw($organizer_data['website']) : '',
                'phone' => isset($organizer_data['phone']) ? sanitize_text_field($organizer_data['phone']) : '',
                'email' => isset($organizer_data['email']) ? sanitize_email($organizer_data['email']) : '',
            ]);
        }
        
        // Save event
        $event->save();
        
        // Save tickets
        if (isset($_POST['aqualuxe_tickets']) && is_array($_POST['aqualuxe_tickets'])) {
            $tickets = $_POST['aqualuxe_tickets'];
            
            foreach ($tickets as $ticket_data) {
                // Skip empty tickets
                if (empty($ticket_data['name'])) {
                    continue;
                }
                
                // Get ticket ID
                $ticket_id = isset($ticket_data['id']) ? absint($ticket_data['id']) : 0;
                
                // Create or update ticket
                $ticket = new Ticket($ticket_id);
                $ticket->set_event_id($post_id);
                $ticket->set_name(sanitize_text_field($ticket_data['name']));
                $ticket->set_description(sanitize_textarea_field($ticket_data['description'] ?? ''));
                $ticket->set_price(floatval($ticket_data['price'] ?? 0));
                $ticket->set_capacity(absint($ticket_data['capacity'] ?? 0));
                $ticket->set_start_date(sanitize_text_field($ticket_data['start_date'] ?? ''));
                $ticket->set_end_date(sanitize_text_field($ticket_data['end_date'] ?? ''));
                $ticket->set_status(sanitize_key($ticket_data['status'] ?? 'available'));
                
                // Save ticket
                $ticket->save();
            }
        }
    }

    /**
     * Save ticket meta
     *
     * @param int $post_id
     */
    public function save_ticket_meta($post_id) {
        // Check if our nonce is set
        if (!isset($_POST['aqualuxe_ticket_nonce'])) {
            return;
        }
        
        // Verify that the nonce is valid
        if (!wp_verify_nonce($_POST['aqualuxe_ticket_nonce'], 'aqualuxe_ticket_meta')) {
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
        
        // Get ticket object
        $ticket = new Ticket($post_id);
        
        // Update ticket data
        if (isset($_POST['aqualuxe_ticket_data'])) {
            $ticket_data = $_POST['aqualuxe_ticket_data'];
            
            // Update ticket event
            if (isset($ticket_data['event_id'])) {
                $ticket->set_event_id(absint($ticket_data['event_id']));
            }
            
            // Update ticket name
            if (isset($ticket_data['name'])) {
                $ticket->set_name(sanitize_text_field($ticket_data['name']));
            }
            
            // Update ticket description
            if (isset($ticket_data['description'])) {
                $ticket->set_description(sanitize_textarea_field($ticket_data['description']));
            }
            
            // Update ticket price
            if (isset($ticket_data['price'])) {
                $ticket->set_price(floatval($ticket_data['price']));
            }
            
            // Update ticket capacity
            if (isset($ticket_data['capacity'])) {
                $ticket->set_capacity(absint($ticket_data['capacity']));
            }
            
            // Update ticket dates
            if (isset($ticket_data['start_date']) && isset($ticket_data['end_date'])) {
                $ticket->set_start_date(sanitize_text_field($ticket_data['start_date']));
                $ticket->set_end_date(sanitize_text_field($ticket_data['end_date']));
            }
            
            // Update ticket status
            if (isset($ticket_data['status'])) {
                $ticket->set_status(sanitize_key($ticket_data['status']));
            }
        }
        
        // Save ticket
        $ticket->save();
    }

    /**
     * Save registration meta
     *
     * @param int $post_id
     */
    public function save_registration_meta($post_id) {
        // Check if our nonce is set
        if (!isset($_POST['aqualuxe_registration_nonce'])) {
            return;
        }
        
        // Verify that the nonce is valid
        if (!wp_verify_nonce($_POST['aqualuxe_registration_nonce'], 'aqualuxe_registration_meta')) {
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
        
        // Get registration object
        $registration = new Registration($post_id);
        
        // Update registration data
        if (isset($_POST['aqualuxe_registration_data'])) {
            $registration_data = $_POST['aqualuxe_registration_data'];
            
            // Update registration event
            if (isset($registration_data['event_id'])) {
                $registration->set_event_id(absint($registration_data['event_id']));
            }
            
            // Update registration ticket
            if (isset($registration_data['ticket_id'])) {
                $registration->set_ticket_id(absint($registration_data['ticket_id']));
            }
            
            // Update registration status
            if (isset($registration_data['status'])) {
                $registration->set_status(sanitize_key($registration_data['status']));
            }
            
            // Update registration quantity
            if (isset($registration_data['quantity'])) {
                $registration->set_quantity(absint($registration_data['quantity']));
            }
            
            // Update registration price
            if (isset($registration_data['price'])) {
                $registration->set_price(floatval($registration_data['price']));
            }
        }
        
        // Update attendee data
        if (isset($_POST['aqualuxe_attendee_data'])) {
            $attendee_data = $_POST['aqualuxe_attendee_data'];
            
            $registration->set_attendee_data([
                'name' => isset($attendee_data['name']) ? sanitize_text_field($attendee_data['name']) : '',
                'email' => isset($attendee_data['email']) ? sanitize_email($attendee_data['email']) : '',
                'phone' => isset($attendee_data['phone']) ? sanitize_text_field($attendee_data['phone']) : '',
                'address' => isset($attendee_data['address']) ? sanitize_textarea_field($attendee_data['address']) : '',
                'notes' => isset($attendee_data['notes']) ? sanitize_textarea_field($attendee_data['notes']) : '',
            ]);
        }
        
        // Update payment data
        if (isset($_POST['aqualuxe_payment_data'])) {
            $payment_data = $_POST['aqualuxe_payment_data'];
            
            $registration->set_payment_data([
                'method' => isset($payment_data['method']) ? sanitize_key($payment_data['method']) : '',
                'status' => isset($payment_data['status']) ? sanitize_key($payment_data['status']) : '',
                'transaction_id' => isset($payment_data['transaction_id']) ? sanitize_text_field($payment_data['transaction_id']) : '',
                'amount' => isset($payment_data['amount']) ? floatval($payment_data['amount']) : 0,
                'currency' => isset($payment_data['currency']) ? sanitize_text_field($payment_data['currency']) : '',
                'date' => isset($payment_data['date']) ? sanitize_text_field($payment_data['date']) : '',
            ]);
        }
        
        // Save registration
        $registration->save();
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Enqueue styles
        $this->enqueue_style('aqualuxe-events', 'assets/css/events.css');
        
        // Enqueue scripts
        $this->enqueue_script('aqualuxe-events', 'assets/js/events.js', ['jquery', 'jquery-ui-datepicker']);
        
        // Localize script
        wp_localize_script('aqualuxe-events', 'aqualuxeEvents', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-events-nonce'),
            'i18n' => [
                'selectDate' => __('Please select a date', 'aqualuxe'),
                'selectTicket' => __('Please select a ticket', 'aqualuxe'),
                'enterName' => __('Please enter your name', 'aqualuxe'),
                'enterEmail' => __('Please enter a valid email address', 'aqualuxe'),
                'enterPhone' => __('Please enter your phone number', 'aqualuxe'),
                'selectQuantity' => __('Please select a quantity', 'aqualuxe'),
                'registrationSuccess' => __('Your registration has been submitted successfully!', 'aqualuxe'),
                'registrationError' => __('There was an error submitting your registration. Please try again.', 'aqualuxe'),
                'processingPayment' => __('Processing payment...', 'aqualuxe'),
                'paymentSuccess' => __('Payment successful!', 'aqualuxe'),
                'paymentError' => __('There was an error processing your payment. Please try again.', 'aqualuxe'),
            ],
            'settings' => [
                'dateFormat' => $this->get_setting('date_format', 'F j, Y'),
                'timeFormat' => $this->get_setting('time_format', 'g:i a'),
                'firstDay' => $this->get_setting('calendar_first_day', 0),
                'enablePayments' => $this->get_setting('enable_payments', true),
                'requirePayment' => $this->get_setting('require_payment', true),
                'paymentMethods' => $this->get_setting('payment_methods', ['stripe', 'paypal']),
            ],
        ]);
        
        // Enqueue Google Maps API if needed
        if ($this->get_setting('show_map', true) && $this->get_setting('google_maps_api_key', '')) {
            wp_enqueue_script(
                'google-maps',
                'https://maps.googleapis.com/maps/api/js?key=' . $this->get_setting('google_maps_api_key', '') . '&libraries=places',
                [],
                null,
                true
            );
        }
    }

    /**
     * Enqueue admin scripts and styles
     *
     * @param string $hook
     */
    public function admin_scripts($hook) {
        // Only enqueue on event, ticket, and registration pages
        $screen = get_current_screen();
        
        if (!$screen || !in_array($screen->id, ['aqualuxe_event', 'aqualuxe_ticket', 'aqualuxe_registration'])) {
            return;
        }
        
        // Enqueue styles
        $this->enqueue_style('aqualuxe-events-admin', 'assets/css/events-admin.css');
        
        // Enqueue scripts
        $this->enqueue_script('aqualuxe-events-admin', 'assets/js/events-admin.js', ['jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable']);
        
        // Localize script
        wp_localize_script('aqualuxe-events-admin', 'aqualuxeEventsAdmin', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-events-admin-nonce'),
            'i18n' => [
                'confirmDelete' => __('Are you sure you want to delete this item?', 'aqualuxe'),
                'confirmCancel' => __('Are you sure you want to cancel this registration?', 'aqualuxe'),
                'confirmApprove' => __('Are you sure you want to approve this registration?', 'aqualuxe'),
                'confirmReject' => __('Are you sure you want to reject this registration?', 'aqualuxe'),
                'confirmComplete' => __('Are you sure you want to mark this registration as completed?', 'aqualuxe'),
                'confirmRefund' => __('Are you sure you want to refund this registration?', 'aqualuxe'),
                'addTicket' => __('Add Ticket', 'aqualuxe'),
                'removeTicket' => __('Remove', 'aqualuxe'),
            ],
            'settings' => [
                'dateFormat' => $this->get_setting('date_format', 'F j, Y'),
                'timeFormat' => $this->get_setting('time_format', 'g:i a'),
                'firstDay' => $this->get_setting('calendar_first_day', 0),
            ],
        ]);
        
        // Enqueue Google Maps API if needed
        if ($screen->id === 'aqualuxe_event' && $this->get_setting('google_maps_api_key', '')) {
            wp_enqueue_script(
                'google-maps',
                'https://maps.googleapis.com/maps/api/js?key=' . $this->get_setting('google_maps_api_key', '') . '&libraries=places',
                [],
                null,
                true
            );
        }
    }

    /**
     * Add event content
     *
     * @param string $content
     * @return string
     */
    public function add_event_content($content) {
        // Only add to event posts
        if (!is_singular('aqualuxe_event')) {
            return $content;
        }
        
        // Get event
        $event = new Event(get_the_ID());
        
        // Get event content
        ob_start();
        $this->get_template_part('event-details', null, [
            'event' => $event,
            'module' => $this,
        ]);
        $event_content = ob_get_clean();
        
        // Add event content to content
        $content .= $event_content;
        
        return $content;
    }

    /**
     * Register widgets
     */
    public function register_widgets() {
        register_widget('\AquaLuxe\Modules\Events\Widget');
    }

    /**
     * Process registration
     */
    public function process_registration() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-events-nonce')) {
            wp_send_json_error(['message' => __('Invalid nonce', 'aqualuxe')]);
        }
        
        // Get registration data
        $event_id = isset($_POST['event_id']) ? absint($_POST['event_id']) : 0;
        $ticket_id = isset($_POST['ticket_id']) ? absint($_POST['ticket_id']) : 0;
        $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;
        
        // Get attendee data
        $attendee_name = isset($_POST['attendee_name']) ? sanitize_text_field($_POST['attendee_name']) : '';
        $attendee_email = isset($_POST['attendee_email']) ? sanitize_email($_POST['attendee_email']) : '';
        $attendee_phone = isset($_POST['attendee_phone']) ? sanitize_text_field($_POST['attendee_phone']) : '';
        $attendee_address = isset($_POST['attendee_address']) ? sanitize_textarea_field($_POST['attendee_address']) : '';
        $attendee_notes = isset($_POST['attendee_notes']) ? sanitize_textarea_field($_POST['attendee_notes']) : '';
        
        // Validate registration data
        if (!$event_id || !$ticket_id) {
            wp_send_json_error(['message' => __('Invalid registration data', 'aqualuxe')]);
        }
        
        // Validate attendee data
        if (!$attendee_name || !$attendee_email) {
            wp_send_json_error(['message' => __('Invalid attendee data', 'aqualuxe')]);
        }
        
        // Check if event exists
        $event = new Event($event_id);
        
        if (!$event->exists()) {
            wp_send_json_error(['message' => __('Invalid event', 'aqualuxe')]);
        }
        
        // Check if ticket exists
        $ticket = new Ticket($ticket_id);
        
        if (!$ticket->exists() || $ticket->get_event_id() != $event_id) {
            wp_send_json_error(['message' => __('Invalid ticket', 'aqualuxe')]);
        }
        
        // Check if registration is open
        if (!$event->is_registration_open()) {
            wp_send_json_error(['message' => __('Registration is not open for this event', 'aqualuxe')]);
        }
        
        // Check if ticket is available
        if (!$ticket->is_available()) {
            wp_send_json_error(['message' => __('This ticket is not available', 'aqualuxe')]);
        }
        
        // Check if there is enough capacity
        if (!$ticket->has_capacity($quantity)) {
            wp_send_json_error(['message' => __('Not enough tickets available', 'aqualuxe')]);
        }
        
        // Create registration
        $registration = new Registration();
        $registration->set_event_id($event_id);
        $registration->set_ticket_id($ticket_id);
        $registration->set_quantity($quantity);
        $registration->set_attendee_data([
            'name' => $attendee_name,
            'email' => $attendee_email,
            'phone' => $attendee_phone,
            'address' => $attendee_address,
            'notes' => $attendee_notes,
        ]);
        
        // Set status to pending
        $registration->set_status('pending');
        
        // Calculate price
        $registration->set_price($ticket->get_price() * $quantity);
        
        // Save registration
        $registration_id = $registration->save();
        
        if (!$registration_id) {
            wp_send_json_error(['message' => __('Error creating registration', 'aqualuxe')]);
        }
        
        // Process payment if enabled
        $payment_required = $this->get_setting('require_payment', true);
        $payment_enabled = $this->get_setting('enable_payments', true);
        
        if ($payment_required && $payment_enabled && $ticket->get_price() > 0) {
            // Get payment data
            $payment_method = isset($_POST['payment_method']) ? sanitize_key($_POST['payment_method']) : '';
            $payment_token = isset($_POST['payment_token']) ? sanitize_text_field($_POST['payment_token']) : '';
            
            // Validate payment data
            if (!$payment_method || !$payment_token) {
                wp_send_json_error(['message' => __('Invalid payment data', 'aqualuxe')]);
            }
            
            // Process payment
            $payment = new Payment($registration);
            $payment_result = $payment->process($payment_method, $payment_token);
            
            if (!$payment_result['success']) {
                wp_send_json_error(['message' => $payment_result['message']]);
            }
        }
        
        // Send emails
        do_action('aqualuxe_registration_created', $registration_id);
        
        // Return success
        wp_send_json_success([
            'registration_id' => $registration_id,
            'message' => __('Registration created successfully', 'aqualuxe'),
            'redirect' => get_permalink($this->get_setting('confirmation_page', 0)) . '?registration_id=' . $registration_id,
        ]);
    }

    /**
     * Process payment
     */
    public function process_payment() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-events-nonce')) {
            wp_send_json_error(['message' => __('Invalid nonce', 'aqualuxe')]);
        }
        
        // Get payment data
        $registration_id = isset($_POST['registration_id']) ? absint($_POST['registration_id']) : 0;
        $payment_method = isset($_POST['payment_method']) ? sanitize_key($_POST['payment_method']) : '';
        $payment_token = isset($_POST['payment_token']) ? sanitize_text_field($_POST['payment_token']) : '';
        
        // Validate payment data
        if (!$registration_id || !$payment_method || !$payment_token) {
            wp_send_json_error(['message' => __('Invalid payment data', 'aqualuxe')]);
        }
        
        // Get registration
        $registration = new Registration($registration_id);
        
        if (!$registration->exists()) {
            wp_send_json_error(['message' => __('Invalid registration', 'aqualuxe')]);
        }
        
        // Process payment
        $payment = new Payment($registration);
        $payment_result = $payment->process($payment_method, $payment_token);
        
        if (!$payment_result['success']) {
            wp_send_json_error(['message' => $payment_result['message']]);
        }
        
        // Return success
        wp_send_json_success([
            'message' => __('Payment processed successfully', 'aqualuxe'),
            'redirect' => get_permalink($this->get_setting('confirmation_page', 0)) . '?registration_id=' . $registration_id,
        ]);
    }

    /**
     * Send registration emails
     *
     * @param int $registration_id
     */
    public function send_registration_emails($registration_id) {
        // Get registration
        $registration = new Registration($registration_id);
        
        if (!$registration->exists()) {
            return;
        }
        
        // Send emails
        $email = new Email($registration);
        
        // Send admin notification
        if ($this->get_setting('admin_email_notification', true)) {
            $email->send_admin_notification();
        }
        
        // Send attendee notification
        if ($this->get_setting('attendee_email_notification', true)) {
            $email->send_attendee_notification();
        }
    }

    /**
     * Send status change email
     *
     * @param int $registration_id
     * @param string $old_status
     * @param string $new_status
     */
    public function send_status_change_email($registration_id, $old_status, $new_status) {
        // Get registration
        $registration = new Registration($registration_id);
        
        if (!$registration->exists()) {
            return;
        }
        
        // Send status change email
        $email = new Email($registration);
        $email->send_status_change_notification($old_status, $new_status);
    }

    /**
     * User registrations endpoint
     */
    public function user_registrations_endpoint() {
        // Get user registrations
        $user_id = get_current_user_id();
        $registrations = $this->get_user_registrations($user_id);
        
        // Get template
        $this->get_template_part('account/registrations', null, [
            'registrations' => $registrations,
            'module' => $this,
        ]);
    }

    /**
     * Add registrations to account menu
     *
     * @param array $items
     * @return array
     */
    public function add_registrations_to_account_menu($items) {
        // Add registrations item
        $items['registrations'] = __('Event Registrations', 'aqualuxe');
        
        return $items;
    }

    /**
     * Registrations endpoint URL
     *
     * @param string $url
     * @param string $endpoint
     * @param string $value
     * @param string $permalink
     * @return string
     */
    public function registrations_endpoint_url($url, $endpoint, $value, $permalink) {
        if ($endpoint === 'registrations') {
            // Get registration page URL
            $registration_page_id = $this->get_setting('registration_page', 0);
            
            if ($registration_page_id) {
                $url = get_permalink($registration_page_id);
                
                if ($value) {
                    $url = add_query_arg('registration_id', $value, $url);
                }
            }
        }
        
        return $url;
    }

    /**
     * Add registration columns
     *
     * @param array $columns
     * @return array
     */
    public function add_registration_columns($columns) {
        $new_columns = [];
        
        // Add checkbox
        if (isset($columns['cb'])) {
            $new_columns['cb'] = $columns['cb'];
        }
        
        // Add custom columns
        $new_columns['title'] = __('Registration', 'aqualuxe');
        $new_columns['event'] = __('Event', 'aqualuxe');
        $new_columns['ticket'] = __('Ticket', 'aqualuxe');
        $new_columns['attendee'] = __('Attendee', 'aqualuxe');
        $new_columns['quantity'] = __('Quantity', 'aqualuxe');
        $new_columns['status'] = __('Status', 'aqualuxe');
        $new_columns['payment'] = __('Payment', 'aqualuxe');
        $new_columns['price'] = __('Price', 'aqualuxe');
        $new_columns['date'] = __('Date', 'aqualuxe');
        
        return $new_columns;
    }

    /**
     * Render registration columns
     *
     * @param string $column
     * @param int $post_id
     */
    public function render_registration_columns($column, $post_id) {
        // Get registration
        $registration = new Registration($post_id);
        
        if (!$registration->exists()) {
            return;
        }
        
        switch ($column) {
            case 'event':
                $event_id = $registration->get_event_id();
                $event_title = get_the_title($event_id);
                echo '<a href="' . esc_url(get_edit_post_link($event_id)) . '">' . esc_html($event_title) . '</a>';
                break;
                
            case 'ticket':
                $ticket_id = $registration->get_ticket_id();
                $ticket_title = get_the_title($ticket_id);
                echo '<a href="' . esc_url(get_edit_post_link($ticket_id)) . '">' . esc_html($ticket_title) . '</a>';
                break;
                
            case 'attendee':
                $attendee = $registration->get_attendee_data();
                echo esc_html($attendee['name']) . '<br>';
                echo '<a href="mailto:' . esc_attr($attendee['email']) . '">' . esc_html($attendee['email']) . '</a>';
                break;
                
            case 'quantity':
                echo esc_html($registration->get_quantity());
                break;
                
            case 'status':
                $status = $registration->get_status();
                echo '<span class="registration-status registration-status--' . esc_attr($status) . '">' . esc_html($this->get_status_label($status)) . '</span>';
                break;
                
            case 'payment':
                $payment = $registration->get_payment_data();
                
                if ($payment && isset($payment['status'])) {
                    echo '<span class="payment-status payment-status--' . esc_attr($payment['status']) . '">' . esc_html($this->get_payment_status_label($payment['status'])) . '</span>';
                } else {
                    echo '<span class="payment-status payment-status--none">' . esc_html__('None', 'aqualuxe') . '</span>';
                }
                break;
                
            case 'price':
                $price = $registration->get_price();
                echo esc_html($this->format_price($price));
                break;
        }
    }

    /**
     * Add registration filters
     *
     * @param string $post_type
     */
    public function add_registration_filters($post_type) {
        if ($post_type !== 'aqualuxe_registration') {
            return;
        }
        
        // Add status filter
        $statuses = $this->get_registration_statuses();
        $current_status = isset($_GET['registration_status']) ? sanitize_key($_GET['registration_status']) : '';
        
        echo '<select name="registration_status">';
        echo '<option value="">' . esc_html__('All Statuses', 'aqualuxe') . '</option>';
        
        foreach ($statuses as $status => $label) {
            echo '<option value="' . esc_attr($status) . '" ' . selected($current_status, $status, false) . '>' . esc_html($label) . '</option>';
        }
        
        echo '</select>';
        
        // Add event filter
        $events = get_posts([
            'post_type' => 'aqualuxe_event',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ]);
        
        $current_event = isset($_GET['event_id']) ? absint($_GET['event_id']) : 0;
        
        echo '<select name="event_id">';
        echo '<option value="">' . esc_html__('All Events', 'aqualuxe') . '</option>';
        
        foreach ($events as $event) {
            echo '<option value="' . esc_attr($event->ID) . '" ' . selected($current_event, $event->ID, false) . '>' . esc_html($event->post_title) . '</option>';
        }
        
        echo '</select>';
    }

    /**
     * Filter registrations by custom filters
     *
     * @param \WP_Query $query
     */
    public function filter_registrations_by_custom_filters($query) {
        global $pagenow;
        
        // Check if we're in the correct screen
        if (!is_admin() || $pagenow !== 'edit.php' || !isset($query->query['post_type']) || $query->query['post_type'] !== 'aqualuxe_registration') {
            return;
        }
        
        // Filter by status
        if (isset($_GET['registration_status']) && !empty($_GET['registration_status'])) {
            $status = sanitize_key($_GET['registration_status']);
            
            $query->query_vars['meta_query'][] = [
                'key' => '_aqualuxe_registration_status',
                'value' => $status,
                'compare' => '=',
            ];
        }
        
        // Filter by event
        if (isset($_GET['event_id']) && !empty($_GET['event_id'])) {
            $event_id = absint($_GET['event_id']);
            
            $query->query_vars['meta_query'][] = [
                'key' => '_aqualuxe_registration_event_id',
                'value' => $event_id,
                'compare' => '=',
            ];
        }
    }

    /**
     * Add registration status counts
     *
     * @param array $views
     * @return array
     */
    public function add_registration_status_counts($views) {
        global $wpdb;
        
        // Get registration statuses
        $statuses = $this->get_registration_statuses();
        
        // Get counts
        $counts = $wpdb->get_results("
            SELECT meta_value, COUNT(*) as count
            FROM $wpdb->postmeta
            WHERE meta_key = '_aqualuxe_registration_status'
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
                add_query_arg('registration_status', $status, admin_url('edit.php?post_type=aqualuxe_registration')),
                isset($_GET['registration_status']) && $_GET['registration_status'] === $status ? 'current' : '',
                $label,
                $count
            );
        }
        
        return $new_views;
    }

    /**
     * Add dashboard widget
     */
    public function add_dashboard_widget() {
        wp_add_dashboard_widget(
            'aqualuxe_event_calendar',
            __('Upcoming Events', 'aqualuxe'),
            [$this, 'render_dashboard_widget']
        );
    }

    /**
     * Render dashboard widget
     */
    public function render_dashboard_widget() {
        // Get upcoming events
        $events = get_posts([
            'post_type' => 'aqualuxe_event',
            'posts_per_page' => 5,
            'meta_key' => '_aqualuxe_event_start_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => [
                [
                    'key' => '_aqualuxe_event_start_date',
                    'value' => date('Y-m-d'),
                    'compare' => '>=',
                    'type' => 'DATE',
                ],
            ],
        ]);
        
        if (empty($events)) {
            echo '<p>' . esc_html__('No upcoming events.', 'aqualuxe') . '</p>';
            return;
        }
        
        echo '<ul class="aqualuxe-dashboard-events">';
        
        foreach ($events as $event_post) {
            $event = new Event($event_post->ID);
            
            echo '<li class="aqualuxe-dashboard-event">';
            echo '<a href="' . esc_url(get_edit_post_link($event->get_id())) . '" class="aqualuxe-dashboard-event__title">' . esc_html($event->get_title()) . '</a>';
            echo '<div class="aqualuxe-dashboard-event__date">' . esc_html($event->get_formatted_start_date()) . '</div>';
            echo '</li>';
        }
        
        echo '</ul>';
        
        echo '<p class="aqualuxe-dashboard-events__link"><a href="' . esc_url(admin_url('edit.php?post_type=aqualuxe_event')) . '">' . esc_html__('View all events', 'aqualuxe') . '</a></p>';
    }

    /**
     * Add event schema
     */
    public function add_event_schema() {
        // Only add to event posts
        if (!is_singular('aqualuxe_event')) {
            return;
        }
        
        // Get event
        $event = new Event(get_the_ID());
        
        // Get event data
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $event->get_title(),
            'description' => $event->get_description(),
            'startDate' => $event->get_start_date() . 'T' . $event->get_start_time(),
            'endDate' => $event->get_end_date() . 'T' . $event->get_end_time(),
            'image' => $event->get_image_url(),
            'url' => $event->get_permalink(),
        ];
        
        // Add venue
        $venue = $event->get_venue_data();
        
        if (!empty($venue['name'])) {
            $schema['location'] = [
                '@type' => 'Place',
                'name' => $venue['name'],
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $venue['address'] ?? '',
                    'addressLocality' => $venue['city'] ?? '',
                    'addressRegion' => $venue['state'] ?? '',
                    'postalCode' => $venue['zip'] ?? '',
                    'addressCountry' => $venue['country'] ?? '',
                ],
            ];
            
            // Add geo coordinates
            if (!empty($venue['latitude']) && !empty($venue['longitude'])) {
                $schema['location']['geo'] = [
                    '@type' => 'GeoCoordinates',
                    'latitude' => $venue['latitude'],
                    'longitude' => $venue['longitude'],
                ];
            }
        }
        
        // Add organizer
        $organizer = $event->get_organizer_data();
        
        if (!empty($organizer['name'])) {
            $schema['organizer'] = [
                '@type' => 'Organization',
                'name' => $organizer['name'],
                'description' => $organizer['description'] ?? '',
                'url' => $organizer['website'] ?? '',
                'email' => $organizer['email'] ?? '',
                'telephone' => $organizer['phone'] ?? '',
            ];
        }
        
        // Add offers
        $tickets = $event->get_tickets();
        
        if (!empty($tickets)) {
            $schema['offers'] = [];
            
            foreach ($tickets as $ticket) {
                $schema['offers'][] = [
                    '@type' => 'Offer',
                    'name' => $ticket->get_name(),
                    'price' => $ticket->get_price(),
                    'priceCurrency' => $event->get_currency(),
                    'availability' => $ticket->is_available() ? 'https://schema.org/InStock' : 'https://schema.org/SoldOut',
                    'validFrom' => $ticket->get_start_date(),
                    'validThrough' => $ticket->get_end_date(),
                    'url' => $event->get_permalink(),
                ];
            }
        }
        
        // Output schema
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
    }

    /**
     * Add event structured data
     *
     * @param array $pieces
     * @param object $context
     * @return array
     */
    public function add_event_structured_data($pieces, $context) {
        // Only add to event posts
        if (!is_singular('aqualuxe_event')) {
            return $pieces;
        }
        
        // Get event
        $event = new Event(get_the_ID());
        
        // Create event piece
        $pieces[] = new \AquaLuxe\Modules\Events\Structured_Data\Event($event);
        
        return $pieces;
    }

    /**
     * Get user registrations
     *
     * @param int $user_id
     * @return array
     */
    public function get_user_registrations($user_id) {
        $args = [
            'post_type' => 'aqualuxe_registration',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_aqualuxe_registration_attendee_email',
                    'value' => get_userdata($user_id)->user_email,
                    'compare' => '=',
                ],
            ],
        ];
        
        $query = new \WP_Query($args);
        $registrations = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $registrations[] = new Registration(get_the_ID());
            }
            wp_reset_postdata();
        }
        
        return $registrations;
    }

    /**
     * Get registration statuses
     *
     * @return array
     */
    public function get_registration_statuses() {
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
        $statuses = $this->get_registration_statuses();
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