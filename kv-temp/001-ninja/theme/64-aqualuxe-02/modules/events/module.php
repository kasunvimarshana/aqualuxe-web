<?php
/**
 * Events Calendar Module
 *
 * @package AquaLuxe
 * @subpackage Events
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe_Events_Calendar class.
 */
class AquaLuxe_Events_Calendar {

    /**
     * Module version.
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * The single instance of the class.
     *
     * @var AquaLuxe_Events_Calendar
     */
    protected static $_instance = null;

    /**
     * Main AquaLuxe_Events_Calendar Instance.
     *
     * Ensures only one instance of AquaLuxe_Events_Calendar is loaded or can be loaded.
     *
     * @return AquaLuxe_Events_Calendar - Main instance.
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
        
        do_action('aqualuxe_events_loaded');
    }

    /**
     * Define Events Calendar Constants.
     */
    private function define_constants() {
        $this->define('AQUALUXE_EVENTS_ABSPATH', dirname(__FILE__) . '/');
        $this->define('AQUALUXE_EVENTS_PLUGIN_URL', $this->plugin_url());
        $this->define('AQUALUXE_EVENTS_VERSION', $this->version);
    }

    /**
     * Define constant if not already set.
     *
     * @param string $name  Constant name.
     * @param string $value Constant value.
     */
    private function define($name, $value) {
        if (!defined($name)) {
            define($name, $value);
        }
    }

    /**
     * Get the plugin url.
     *
     * @return string
     */
    public function plugin_url() {
        return untrailingslashit(plugins_url('/', __FILE__));
    }

    /**
     * Include required core files.
     */
    public function includes() {
        // Core classes
        include_once AQUALUXE_EVENTS_ABSPATH . 'inc/class-aqualuxe-event.php';
        include_once AQUALUXE_EVENTS_ABSPATH . 'inc/class-aqualuxe-event-category.php';
        include_once AQUALUXE_EVENTS_ABSPATH . 'inc/class-aqualuxe-event-ticket.php';
        include_once AQUALUXE_EVENTS_ABSPATH . 'inc/class-aqualuxe-event-calendar.php';
        
        // Functions
        include_once AQUALUXE_EVENTS_ABSPATH . 'inc/functions-events.php';
        include_once AQUALUXE_EVENTS_ABSPATH . 'inc/shortcodes.php';
        
        // Admin
        if (is_admin()) {
            include_once AQUALUXE_EVENTS_ABSPATH . 'inc/admin/class-aqualuxe-events-admin.php';
        }
    }

    /**
     * Initialize hooks.
     */
    private function init_hooks() {
        // Register scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
        
        // Register post types and taxonomies
        add_action('init', array($this, 'register_post_types'), 5);
        add_action('init', array($this, 'register_taxonomies'), 5);
        
        // Register shortcodes
        add_action('init', array($this, 'register_shortcodes'));
        
        // Add rewrite rules
        add_action('init', array($this, 'add_rewrite_rules'));
    }

    /**
     * Register scripts and styles.
     */
    public function register_scripts() {
        // Register styles
        wp_register_style(
            'aqualuxe-events',
            AQUALUXE_EVENTS_PLUGIN_URL . '/assets/css/events.css',
            array(),
            AQUALUXE_EVENTS_VERSION
        );

        // Register scripts
        wp_register_script(
            'aqualuxe-events',
            AQUALUXE_EVENTS_PLUGIN_URL . '/assets/js/events.js',
            array('jquery'),
            AQUALUXE_EVENTS_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script(
            'aqualuxe-events',
            'aqualuxe_events_params',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe-events-nonce'),
            )
        );
    }

    /**
     * Register post types.
     */
    public function register_post_types() {
        if (!is_blog_installed() || post_type_exists('aqualuxe_event')) {
            return;
        }

        // Event post type
        register_post_type(
            'aqualuxe_event',
            array(
                'labels' => array(
                    'name' => __('Events', 'aqualuxe'),
                    'singular_name' => __('Event', 'aqualuxe'),
                    'menu_name' => __('Events', 'aqualuxe'),
                    'add_new' => __('Add New', 'aqualuxe'),
                    'add_new_item' => __('Add New Event', 'aqualuxe'),
                    'edit' => __('Edit', 'aqualuxe'),
                    'edit_item' => __('Edit Event', 'aqualuxe'),
                    'new_item' => __('New Event', 'aqualuxe'),
                    'view' => __('View Event', 'aqualuxe'),
                    'view_item' => __('View Event', 'aqualuxe'),
                    'search_items' => __('Search Events', 'aqualuxe'),
                    'not_found' => __('No events found', 'aqualuxe'),
                    'not_found_in_trash' => __('No events found in trash', 'aqualuxe'),
                    'parent' => __('Parent Event', 'aqualuxe'),
                ),
                'description' => __('This is where you can add new events to your calendar.', 'aqualuxe'),
                'public' => true,
                'show_ui' => true,
                'capability_type' => 'post',
                'map_meta_cap' => true,
                'publicly_queryable' => true,
                'exclude_from_search' => false,
                'hierarchical' => false,
                'rewrite' => array('slug' => 'event', 'with_front' => false),
                'query_var' => true,
                'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'comments'),
                'has_archive' => true,
                'show_in_nav_menus' => true,
                'menu_icon' => 'dashicons-calendar-alt',
            )
        );

        // Ticket post type
        register_post_type(
            'aqualuxe_ticket',
            array(
                'labels' => array(
                    'name' => __('Tickets', 'aqualuxe'),
                    'singular_name' => __('Ticket', 'aqualuxe'),
                    'menu_name' => __('Tickets', 'aqualuxe'),
                    'add_new' => __('Add New', 'aqualuxe'),
                    'add_new_item' => __('Add New Ticket', 'aqualuxe'),
                    'edit' => __('Edit', 'aqualuxe'),
                    'edit_item' => __('Edit Ticket', 'aqualuxe'),
                    'new_item' => __('New Ticket', 'aqualuxe'),
                    'view' => __('View Ticket', 'aqualuxe'),
                    'view_item' => __('View Ticket', 'aqualuxe'),
                    'search_items' => __('Search Tickets', 'aqualuxe'),
                    'not_found' => __('No tickets found', 'aqualuxe'),
                    'not_found_in_trash' => __('No tickets found in trash', 'aqualuxe'),
                    'parent' => __('Parent Ticket', 'aqualuxe'),
                ),
                'description' => __('This is where you can add tickets for your events.', 'aqualuxe'),
                'public' => false,
                'show_ui' => true,
                'capability_type' => 'post',
                'map_meta_cap' => true,
                'publicly_queryable' => false,
                'exclude_from_search' => true,
                'hierarchical' => false,
                'rewrite' => false,
                'query_var' => false,
                'supports' => array('title', 'custom-fields'),
                'has_archive' => false,
                'show_in_nav_menus' => false,
                'show_in_menu' => 'edit.php?post_type=aqualuxe_event',
            )
        );
    }

    /**
     * Register taxonomies.
     */
    public function register_taxonomies() {
        if (!is_blog_installed()) {
            return;
        }

        // Event Category taxonomy
        if (!taxonomy_exists('aqualuxe_event_category')) {
            register_taxonomy(
                'aqualuxe_event_category',
                'aqualuxe_event',
                array(
                    'labels' => array(
                        'name' => __('Event Categories', 'aqualuxe'),
                        'singular_name' => __('Event Category', 'aqualuxe'),
                        'menu_name' => __('Categories', 'aqualuxe'),
                        'search_items' => __('Search Event Categories', 'aqualuxe'),
                        'all_items' => __('All Event Categories', 'aqualuxe'),
                        'parent_item' => __('Parent Event Category', 'aqualuxe'),
                        'parent_item_colon' => __('Parent Event Category:', 'aqualuxe'),
                        'edit_item' => __('Edit Event Category', 'aqualuxe'),
                        'update_item' => __('Update Event Category', 'aqualuxe'),
                        'add_new_item' => __('Add New Event Category', 'aqualuxe'),
                        'new_item_name' => __('New Event Category Name', 'aqualuxe'),
                    ),
                    'hierarchical' => true,
                    'show_ui' => true,
                    'show_admin_column' => true,
                    'query_var' => true,
                    'rewrite' => array('slug' => 'event-category'),
                )
            );
        }

        // Event Tag taxonomy
        if (!taxonomy_exists('aqualuxe_event_tag')) {
            register_taxonomy(
                'aqualuxe_event_tag',
                'aqualuxe_event',
                array(
                    'labels' => array(
                        'name' => __('Event Tags', 'aqualuxe'),
                        'singular_name' => __('Event Tag', 'aqualuxe'),
                        'menu_name' => __('Tags', 'aqualuxe'),
                        'search_items' => __('Search Event Tags', 'aqualuxe'),
                        'all_items' => __('All Event Tags', 'aqualuxe'),
                        'edit_item' => __('Edit Event Tag', 'aqualuxe'),
                        'update_item' => __('Update Event Tag', 'aqualuxe'),
                        'add_new_item' => __('Add New Event Tag', 'aqualuxe'),
                        'new_item_name' => __('New Event Tag Name', 'aqualuxe'),
                    ),
                    'hierarchical' => false,
                    'show_ui' => true,
                    'show_admin_column' => true,
                    'query_var' => true,
                    'rewrite' => array('slug' => 'event-tag'),
                )
            );
        }
    }

    /**
     * Register shortcodes.
     */
    public function register_shortcodes() {
        add_shortcode('aqualuxe_events_calendar', 'aqualuxe_events_calendar_shortcode');
        add_shortcode('aqualuxe_events_list', 'aqualuxe_events_list_shortcode');
        add_shortcode('aqualuxe_event_tickets', 'aqualuxe_event_tickets_shortcode');
    }

    /**
     * Add rewrite rules.
     */
    public function add_rewrite_rules() {
        add_rewrite_rule(
            'events/calendar/([^/]+)/?$',
            'index.php?pagename=events&calendar_view=$matches[1]',
            'top'
        );
        
        add_rewrite_tag('%calendar_view%', '([^&]+)');
    }
}

// Initialize the Events Calendar module
function AquaLuxe_Events() {
    return AquaLuxe_Events_Calendar::instance();
}

// Global for backwards compatibility
$GLOBALS['aqualuxe_events'] = AquaLuxe_Events();