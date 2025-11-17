<?php
/**
 * Post Type Manager
 *
 * Handles registration and management of custom post types
 *
 * @package AquaLuxe\Inc\PostTypes
 * @version 1.0.0
 */

namespace AquaLuxe\Inc\PostTypes;

/**
 * Post Type Manager Class
 */
class PostTypeManager {
    
    /**
     * Registered post types
     *
     * @var array
     */
    private $post_types = [];
    
    /**
     * Initialize post type manager
     */
    public function __construct() {
        add_action('init', [$this, 'register_post_types']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_boxes']);
    }
    
    /**
     * Register all custom post types
     *
     * @return void
     */
    public function register_post_types(): void {
        $this->register_services_post_type();
        $this->register_portfolio_post_type();
        $this->register_testimonials_post_type();
        $this->register_team_post_type();
        $this->register_events_post_type();
        $this->register_bookings_post_type();
        $this->register_subscriptions_post_type();
        $this->register_locations_post_type();
    }
    
    /**
     * Register Services post type
     *
     * @return void
     */
    private function register_services_post_type(): void {
        $labels = [
            'name' => __('Services', 'aqualuxe'),
            'singular_name' => __('Service', 'aqualuxe'),
            'menu_name' => __('Services', 'aqualuxe'),
            'name_admin_bar' => __('Service', 'aqualuxe'),
            'add_new' => __('Add New', 'aqualuxe'),
            'add_new_item' => __('Add New Service', 'aqualuxe'),
            'new_item' => __('New Service', 'aqualuxe'),
            'edit_item' => __('Edit Service', 'aqualuxe'),
            'view_item' => __('View Service', 'aqualuxe'),
            'all_items' => __('All Services', 'aqualuxe'),
            'search_items' => __('Search Services', 'aqualuxe'),
            'parent_item_colon' => __('Parent Services:', 'aqualuxe'),
            'not_found' => __('No services found.', 'aqualuxe'),
            'not_found_in_trash' => __('No services found in Trash.', 'aqualuxe'),
        ];
        
        $args = [
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'services'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 20,
            'menu_icon' => 'dashicons-admin-tools',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'],
        ];
        
        register_post_type('aqualuxe_service', $args);
        $this->post_types['aqualuxe_service'] = $args;
    }
    
    /**
     * Register Portfolio post type
     *
     * @return void
     */
    private function register_portfolio_post_type(): void {
        $labels = [
            'name' => __('Portfolio', 'aqualuxe'),
            'singular_name' => __('Portfolio Item', 'aqualuxe'),
            'menu_name' => __('Portfolio', 'aqualuxe'),
            'name_admin_bar' => __('Portfolio Item', 'aqualuxe'),
            'add_new' => __('Add New', 'aqualuxe'),
            'add_new_item' => __('Add New Portfolio Item', 'aqualuxe'),
            'new_item' => __('New Portfolio Item', 'aqualuxe'),
            'edit_item' => __('Edit Portfolio Item', 'aqualuxe'),
            'view_item' => __('View Portfolio Item', 'aqualuxe'),
            'all_items' => __('All Portfolio', 'aqualuxe'),
            'search_items' => __('Search Portfolio', 'aqualuxe'),
            'not_found' => __('No portfolio items found.', 'aqualuxe'),
            'not_found_in_trash' => __('No portfolio items found in Trash.', 'aqualuxe'),
        ];
        
        $args = [
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'portfolio'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 21,
            'menu_icon' => 'dashicons-portfolio',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        ];
        
        register_post_type('aqualuxe_portfolio', $args);
        $this->post_types['aqualuxe_portfolio'] = $args;
    }
    
    /**
     * Register Testimonials post type
     *
     * @return void
     */
    private function register_testimonials_post_type(): void {
        $labels = [
            'name' => __('Testimonials', 'aqualuxe'),
            'singular_name' => __('Testimonial', 'aqualuxe'),
            'menu_name' => __('Testimonials', 'aqualuxe'),
            'name_admin_bar' => __('Testimonial', 'aqualuxe'),
            'add_new' => __('Add New', 'aqualuxe'),
            'add_new_item' => __('Add New Testimonial', 'aqualuxe'),
            'new_item' => __('New Testimonial', 'aqualuxe'),
            'edit_item' => __('Edit Testimonial', 'aqualuxe'),
            'view_item' => __('View Testimonial', 'aqualuxe'),
            'all_items' => __('All Testimonials', 'aqualuxe'),
            'search_items' => __('Search Testimonials', 'aqualuxe'),
            'not_found' => __('No testimonials found.', 'aqualuxe'),
            'not_found_in_trash' => __('No testimonials found in Trash.', 'aqualuxe'),
        ];
        
        $args = [
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'query_var' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 22,
            'menu_icon' => 'dashicons-format-quote',
            'supports' => ['title', 'editor', 'thumbnail', 'custom-fields'],
        ];
        
        register_post_type('aqualuxe_testimonial', $args);
        $this->post_types['aqualuxe_testimonial'] = $args;
    }
    
    /**
     * Register Team post type
     *
     * @return void
     */
    private function register_team_post_type(): void {
        $labels = [
            'name' => __('Team Members', 'aqualuxe'),
            'singular_name' => __('Team Member', 'aqualuxe'),
            'menu_name' => __('Team', 'aqualuxe'),
            'name_admin_bar' => __('Team Member', 'aqualuxe'),
            'add_new' => __('Add New', 'aqualuxe'),
            'add_new_item' => __('Add New Team Member', 'aqualuxe'),
            'new_item' => __('New Team Member', 'aqualuxe'),
            'edit_item' => __('Edit Team Member', 'aqualuxe'),
            'view_item' => __('View Team Member', 'aqualuxe'),
            'all_items' => __('All Team Members', 'aqualuxe'),
            'search_items' => __('Search Team Members', 'aqualuxe'),
            'not_found' => __('No team members found.', 'aqualuxe'),
            'not_found_in_trash' => __('No team members found in Trash.', 'aqualuxe'),
        ];
        
        $args = [
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'team'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 23,
            'menu_icon' => 'dashicons-groups',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        ];
        
        register_post_type('aqualuxe_team', $args);
        $this->post_types['aqualuxe_team'] = $args;
    }
    
    /**
     * Register Events post type
     *
     * @return void
     */
    private function register_events_post_type(): void {
        $labels = [
            'name' => __('Events', 'aqualuxe'),
            'singular_name' => __('Event', 'aqualuxe'),
            'menu_name' => __('Events', 'aqualuxe'),
            'name_admin_bar' => __('Event', 'aqualuxe'),
            'add_new' => __('Add New', 'aqualuxe'),
            'add_new_item' => __('Add New Event', 'aqualuxe'),
            'new_item' => __('New Event', 'aqualuxe'),
            'edit_item' => __('Edit Event', 'aqualuxe'),
            'view_item' => __('View Event', 'aqualuxe'),
            'all_items' => __('All Events', 'aqualuxe'),
            'search_items' => __('Search Events', 'aqualuxe'),
            'not_found' => __('No events found.', 'aqualuxe'),
            'not_found_in_trash' => __('No events found in Trash.', 'aqualuxe'),
        ];
        
        $args = [
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'events'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 24,
            'menu_icon' => 'dashicons-calendar-alt',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        ];
        
        register_post_type('aqualuxe_event', $args);
        $this->post_types['aqualuxe_event'] = $args;
    }
    
    /**
     * Register Bookings post type
     *
     * @return void
     */
    private function register_bookings_post_type(): void {
        $labels = [
            'name' => __('Bookings', 'aqualuxe'),
            'singular_name' => __('Booking', 'aqualuxe'),
            'menu_name' => __('Bookings', 'aqualuxe'),
            'name_admin_bar' => __('Booking', 'aqualuxe'),
            'add_new' => __('Add New', 'aqualuxe'),
            'add_new_item' => __('Add New Booking', 'aqualuxe'),
            'new_item' => __('New Booking', 'aqualuxe'),
            'edit_item' => __('Edit Booking', 'aqualuxe'),
            'view_item' => __('View Booking', 'aqualuxe'),
            'all_items' => __('All Bookings', 'aqualuxe'),
            'search_items' => __('Search Bookings', 'aqualuxe'),
            'not_found' => __('No bookings found.', 'aqualuxe'),
            'not_found_in_trash' => __('No bookings found in Trash.', 'aqualuxe'),
        ];
        
        $args = [
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => false,
            'query_var' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 25,
            'menu_icon' => 'dashicons-clipboard',
            'supports' => ['title', 'custom-fields'],
            'capabilities' => [
                'create_posts' => 'manage_options',
            ],
            'map_meta_cap' => true,
        ];
        
        register_post_type('aqualuxe_booking', $args);
        $this->post_types['aqualuxe_booking'] = $args;
    }
    
    /**
     * Register Subscriptions post type
     *
     * @return void
     */
    private function register_subscriptions_post_type(): void {
        $labels = [
            'name' => __('Subscriptions', 'aqualuxe'),
            'singular_name' => __('Subscription', 'aqualuxe'),
            'menu_name' => __('Subscriptions', 'aqualuxe'),
            'name_admin_bar' => __('Subscription', 'aqualuxe'),
            'add_new' => __('Add New', 'aqualuxe'),
            'add_new_item' => __('Add New Subscription', 'aqualuxe'),
            'new_item' => __('New Subscription', 'aqualuxe'),
            'edit_item' => __('Edit Subscription', 'aqualuxe'),
            'view_item' => __('View Subscription', 'aqualuxe'),
            'all_items' => __('All Subscriptions', 'aqualuxe'),
            'search_items' => __('Search Subscriptions', 'aqualuxe'),
            'not_found' => __('No subscriptions found.', 'aqualuxe'),
            'not_found_in_trash' => __('No subscriptions found in Trash.', 'aqualuxe'),
        ];
        
        $args = [
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => false,
            'query_var' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 26,
            'menu_icon' => 'dashicons-update',
            'supports' => ['title', 'custom-fields'],
            'capabilities' => [
                'create_posts' => 'manage_options',
            ],
            'map_meta_cap' => true,
        ];
        
        register_post_type('aqualuxe_subscription', $args);
        $this->post_types['aqualuxe_subscription'] = $args;
    }
    
    /**
     * Register Locations post type
     *
     * @return void
     */
    private function register_locations_post_type(): void {
        $labels = [
            'name' => __('Locations', 'aqualuxe'),
            'singular_name' => __('Location', 'aqualuxe'),
            'menu_name' => __('Locations', 'aqualuxe'),
            'name_admin_bar' => __('Location', 'aqualuxe'),
            'add_new' => __('Add New', 'aqualuxe'),
            'add_new_item' => __('Add New Location', 'aqualuxe'),
            'new_item' => __('New Location', 'aqualuxe'),
            'edit_item' => __('Edit Location', 'aqualuxe'),
            'view_item' => __('View Location', 'aqualuxe'),
            'all_items' => __('All Locations', 'aqualuxe'),
            'search_items' => __('Search Locations', 'aqualuxe'),
            'not_found' => __('No locations found.', 'aqualuxe'),
            'not_found_in_trash' => __('No locations found in Trash.', 'aqualuxe'),
        ];
        
        $args = [
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'locations'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 27,
            'menu_icon' => 'dashicons-location',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        ];
        
        register_post_type('aqualuxe_location', $args);
        $this->post_types['aqualuxe_location'] = $args;
    }
    
    /**
     * Add meta boxes
     *
     * @return void
     */
    public function add_meta_boxes(): void {
        // Service meta boxes
        add_meta_box(
            'aqualuxe_service_details',
            __('Service Details', 'aqualuxe'),
            [$this, 'render_service_meta_box'],
            'aqualuxe_service',
            'normal',
            'high'
        );
        
        // Portfolio meta boxes
        add_meta_box(
            'aqualuxe_portfolio_details',
            __('Portfolio Details', 'aqualuxe'),
            [$this, 'render_portfolio_meta_box'],
            'aqualuxe_portfolio',
            'normal',
            'high'
        );
        
        // Testimonial meta boxes
        add_meta_box(
            'aqualuxe_testimonial_details',
            __('Testimonial Details', 'aqualuxe'),
            [$this, 'render_testimonial_meta_box'],
            'aqualuxe_testimonial',
            'normal',
            'high'
        );
        
        // Team meta boxes
        add_meta_box(
            'aqualuxe_team_details',
            __('Team Member Details', 'aqualuxe'),
            [$this, 'render_team_meta_box'],
            'aqualuxe_team',
            'normal',
            'high'
        );
        
        // Event meta boxes
        add_meta_box(
            'aqualuxe_event_details',
            __('Event Details', 'aqualuxe'),
            [$this, 'render_event_meta_box'],
            'aqualuxe_event',
            'normal',
            'high'
        );
        
        // Location meta boxes
        add_meta_box(
            'aqualuxe_location_details',
            __('Location Details', 'aqualuxe'),
            [$this, 'render_location_meta_box'],
            'aqualuxe_location',
            'normal',
            'high'
        );
    }
    
    /**
     * Render service meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_service_meta_box($post): void {
        wp_nonce_field('aqualuxe_service_meta', 'aqualuxe_service_meta_nonce');
        
        $price = get_post_meta($post->ID, '_service_price', true);
        $duration = get_post_meta($post->ID, '_service_duration', true);
        $featured = get_post_meta($post->ID, '_service_featured', true);
        
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th><label for="service_price">' . __('Price', 'aqualuxe') . '</label></th>';
        echo '<td><input type="text" id="service_price" name="service_price" value="' . esc_attr($price) . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="service_duration">' . __('Duration', 'aqualuxe') . '</label></th>';
        echo '<td><input type="text" id="service_duration" name="service_duration" value="' . esc_attr($duration) . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="service_featured">' . __('Featured Service', 'aqualuxe') . '</label></th>';
        echo '<td><input type="checkbox" id="service_featured" name="service_featured" value="1" ' . checked($featured, 1, false) . ' /></td>';
        echo '</tr>';
        echo '</table>';
    }
    
    /**
     * Render portfolio meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_portfolio_meta_box($post): void {
        wp_nonce_field('aqualuxe_portfolio_meta', 'aqualuxe_portfolio_meta_nonce');
        
        $client = get_post_meta($post->ID, '_portfolio_client', true);
        $project_url = get_post_meta($post->ID, '_portfolio_url', true);
        $completion_date = get_post_meta($post->ID, '_portfolio_completion_date', true);
        
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th><label for="portfolio_client">' . __('Client', 'aqualuxe') . '</label></th>';
        echo '<td><input type="text" id="portfolio_client" name="portfolio_client" value="' . esc_attr($client) . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="portfolio_url">' . __('Project URL', 'aqualuxe') . '</label></th>';
        echo '<td><input type="url" id="portfolio_url" name="portfolio_url" value="' . esc_attr($project_url) . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="portfolio_completion_date">' . __('Completion Date', 'aqualuxe') . '</label></th>';
        echo '<td><input type="date" id="portfolio_completion_date" name="portfolio_completion_date" value="' . esc_attr($completion_date) . '" /></td>';
        echo '</tr>';
        echo '</table>';
    }
    
    /**
     * Render testimonial meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_testimonial_meta_box($post): void {
        wp_nonce_field('aqualuxe_testimonial_meta', 'aqualuxe_testimonial_meta_nonce');
        
        $client_name = get_post_meta($post->ID, '_testimonial_client_name', true);
        $client_position = get_post_meta($post->ID, '_testimonial_client_position', true);
        $client_company = get_post_meta($post->ID, '_testimonial_client_company', true);
        $rating = get_post_meta($post->ID, '_testimonial_rating', true);
        
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th><label for="testimonial_client_name">' . __('Client Name', 'aqualuxe') . '</label></th>';
        echo '<td><input type="text" id="testimonial_client_name" name="testimonial_client_name" value="' . esc_attr($client_name) . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="testimonial_client_position">' . __('Client Position', 'aqualuxe') . '</label></th>';
        echo '<td><input type="text" id="testimonial_client_position" name="testimonial_client_position" value="' . esc_attr($client_position) . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="testimonial_client_company">' . __('Client Company', 'aqualuxe') . '</label></th>';
        echo '<td><input type="text" id="testimonial_client_company" name="testimonial_client_company" value="' . esc_attr($client_company) . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="testimonial_rating">' . __('Rating (1-5)', 'aqualuxe') . '</label></th>';
        echo '<td><select id="testimonial_rating" name="testimonial_rating">';
        for ($i = 1; $i <= 5; $i++) {
            echo '<option value="' . $i . '" ' . selected($rating, $i, false) . '>' . $i . ' Star' . ($i > 1 ? 's' : '') . '</option>';
        }
        echo '</select></td>';
        echo '</tr>';
        echo '</table>';
    }
    
    /**
     * Render team meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_team_meta_box($post): void {
        wp_nonce_field('aqualuxe_team_meta', 'aqualuxe_team_meta_nonce');
        
        $position = get_post_meta($post->ID, '_team_position', true);
        $email = get_post_meta($post->ID, '_team_email', true);
        $phone = get_post_meta($post->ID, '_team_phone', true);
        $social_links = get_post_meta($post->ID, '_team_social_links', true) ?: [];
        
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th><label for="team_position">' . __('Position', 'aqualuxe') . '</label></th>';
        echo '<td><input type="text" id="team_position" name="team_position" value="' . esc_attr($position) . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="team_email">' . __('Email', 'aqualuxe') . '</label></th>';
        echo '<td><input type="email" id="team_email" name="team_email" value="' . esc_attr($email) . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="team_phone">' . __('Phone', 'aqualuxe') . '</label></th>';
        echo '<td><input type="tel" id="team_phone" name="team_phone" value="' . esc_attr($phone) . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>' . __('Social Links', 'aqualuxe') . '</th>';
        echo '<td>';
        echo '<label>LinkedIn: <input type="url" name="team_social_linkedin" value="' . esc_attr($social_links['linkedin'] ?? '') . '" /></label><br>';
        echo '<label>Twitter: <input type="url" name="team_social_twitter" value="' . esc_attr($social_links['twitter'] ?? '') . '" /></label><br>';
        echo '<label>Facebook: <input type="url" name="team_social_facebook" value="' . esc_attr($social_links['facebook'] ?? '') . '" /></label>';
        echo '</td>';
        echo '</tr>';
        echo '</table>';
    }
    
    /**
     * Render event meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_event_meta_box($post): void {
        wp_nonce_field('aqualuxe_event_meta', 'aqualuxe_event_meta_nonce');
        
        $start_date = get_post_meta($post->ID, '_event_start_date', true);
        $end_date = get_post_meta($post->ID, '_event_end_date', true);
        $location = get_post_meta($post->ID, '_event_location', true);
        $price = get_post_meta($post->ID, '_event_price', true);
        $max_attendees = get_post_meta($post->ID, '_event_max_attendees', true);
        
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th><label for="event_start_date">' . __('Start Date', 'aqualuxe') . '</label></th>';
        echo '<td><input type="datetime-local" id="event_start_date" name="event_start_date" value="' . esc_attr($start_date) . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="event_end_date">' . __('End Date', 'aqualuxe') . '</label></th>';
        echo '<td><input type="datetime-local" id="event_end_date" name="event_end_date" value="' . esc_attr($end_date) . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="event_location">' . __('Location', 'aqualuxe') . '</label></th>';
        echo '<td><input type="text" id="event_location" name="event_location" value="' . esc_attr($location) . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="event_price">' . __('Price', 'aqualuxe') . '</label></th>';
        echo '<td><input type="text" id="event_price" name="event_price" value="' . esc_attr($price) . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="event_max_attendees">' . __('Max Attendees', 'aqualuxe') . '</label></th>';
        echo '<td><input type="number" id="event_max_attendees" name="event_max_attendees" value="' . esc_attr($max_attendees) . '" /></td>';
        echo '</tr>';
        echo '</table>';
    }
    
    /**
     * Render location meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_location_meta_box($post): void {
        wp_nonce_field('aqualuxe_location_meta', 'aqualuxe_location_meta_nonce');
        
        $address = get_post_meta($post->ID, '_location_address', true);
        $latitude = get_post_meta($post->ID, '_location_latitude', true);
        $longitude = get_post_meta($post->ID, '_location_longitude', true);
        $phone = get_post_meta($post->ID, '_location_phone', true);
        $hours = get_post_meta($post->ID, '_location_hours', true);
        
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th><label for="location_address">' . __('Address', 'aqualuxe') . '</label></th>';
        echo '<td><textarea id="location_address" name="location_address" rows="3" cols="50">' . esc_textarea($address) . '</textarea></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="location_latitude">' . __('Latitude', 'aqualuxe') . '</label></th>';
        echo '<td><input type="text" id="location_latitude" name="location_latitude" value="' . esc_attr($latitude) . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="location_longitude">' . __('Longitude', 'aqualuxe') . '</label></th>';
        echo '<td><input type="text" id="location_longitude" name="location_longitude" value="' . esc_attr($longitude) . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="location_phone">' . __('Phone', 'aqualuxe') . '</label></th>';
        echo '<td><input type="tel" id="location_phone" name="location_phone" value="' . esc_attr($phone) . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="location_hours">' . __('Operating Hours', 'aqualuxe') . '</label></th>';
        echo '<td><textarea id="location_hours" name="location_hours" rows="5" cols="50">' . esc_textarea($hours) . '</textarea></td>';
        echo '</tr>';
        echo '</table>';
    }
    
    /**
     * Save meta box data
     *
     * @param int $post_id
     * @return void
     */
    public function save_meta_boxes($post_id): void {
        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        $post_type = get_post_type($post_id);
        
        switch ($post_type) {
            case 'aqualuxe_service':
                $this->save_service_meta($post_id);
                break;
            case 'aqualuxe_portfolio':
                $this->save_portfolio_meta($post_id);
                break;
            case 'aqualuxe_testimonial':
                $this->save_testimonial_meta($post_id);
                break;
            case 'aqualuxe_team':
                $this->save_team_meta($post_id);
                break;
            case 'aqualuxe_event':
                $this->save_event_meta($post_id);
                break;
            case 'aqualuxe_location':
                $this->save_location_meta($post_id);
                break;
        }
    }
    
    /**
     * Save service meta data
     *
     * @param int $post_id
     * @return void
     */
    private function save_service_meta($post_id): void {
        if (!wp_verify_nonce($_POST['aqualuxe_service_meta_nonce'] ?? '', 'aqualuxe_service_meta')) {
            return;
        }
        
        $fields = ['service_price', 'service_duration', 'service_featured'];
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = $field === 'service_featured' ? 1 : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_' . $field, $value);
            } else {
                delete_post_meta($post_id, '_' . $field);
            }
        }
    }
    
    /**
     * Save portfolio meta data
     *
     * @param int $post_id
     * @return void
     */
    private function save_portfolio_meta($post_id): void {
        if (!wp_verify_nonce($_POST['aqualuxe_portfolio_meta_nonce'] ?? '', 'aqualuxe_portfolio_meta')) {
            return;
        }
        
        $fields = ['portfolio_client', 'portfolio_url', 'portfolio_completion_date'];
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = $field === 'portfolio_url' ? esc_url_raw($_POST[$field]) : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_' . $field, $value);
            }
        }
    }
    
    /**
     * Save testimonial meta data
     *
     * @param int $post_id
     * @return void
     */
    private function save_testimonial_meta($post_id): void {
        if (!wp_verify_nonce($_POST['aqualuxe_testimonial_meta_nonce'] ?? '', 'aqualuxe_testimonial_meta')) {
            return;
        }
        
        $fields = ['testimonial_client_name', 'testimonial_client_position', 'testimonial_client_company', 'testimonial_rating'];
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = $field === 'testimonial_rating' ? absint($_POST[$field]) : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_' . $field, $value);
            }
        }
    }
    
    /**
     * Save team meta data
     *
     * @param int $post_id
     * @return void
     */
    private function save_team_meta($post_id): void {
        if (!wp_verify_nonce($_POST['aqualuxe_team_meta_nonce'] ?? '', 'aqualuxe_team_meta')) {
            return;
        }
        
        $fields = ['team_position', 'team_email', 'team_phone'];
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = $field === 'team_email' ? sanitize_email($_POST[$field]) : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_' . $field, $value);
            }
        }
        
        // Save social links
        $social_links = [
            'linkedin' => esc_url_raw($_POST['team_social_linkedin'] ?? ''),
            'twitter' => esc_url_raw($_POST['team_social_twitter'] ?? ''),
            'facebook' => esc_url_raw($_POST['team_social_facebook'] ?? ''),
        ];
        
        update_post_meta($post_id, '_team_social_links', array_filter($social_links));
    }
    
    /**
     * Save event meta data
     *
     * @param int $post_id
     * @return void
     */
    private function save_event_meta($post_id): void {
        if (!wp_verify_nonce($_POST['aqualuxe_event_meta_nonce'] ?? '', 'aqualuxe_event_meta')) {
            return;
        }
        
        $fields = [
            'event_start_date',
            'event_end_date',
            'event_location',
            'event_price',
            'event_max_attendees'
        ];
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = $field === 'event_max_attendees' ? absint($_POST[$field]) : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_' . $field, $value);
            }
        }
    }
    
    /**
     * Save location meta data
     *
     * @param int $post_id
     * @return void
     */
    private function save_location_meta($post_id): void {
        if (!wp_verify_nonce($_POST['aqualuxe_location_meta_nonce'] ?? '', 'aqualuxe_location_meta')) {
            return;
        }
        
        $fields = [
            'location_address',
            'location_latitude',
            'location_longitude',
            'location_phone',
            'location_hours'
        ];
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = in_array($field, ['location_address', 'location_hours']) 
                    ? sanitize_textarea_field($_POST[$field]) 
                    : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_' . $field, $value);
            }
        }
    }
    
    /**
     * Get registered post types
     *
     * @return array
     */
    public function get_post_types(): array {
        return $this->post_types;
    }
}