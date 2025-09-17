<?php
/**
 * Custom Post Types
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Custom Post Types
 */
function aqualuxe_register_post_types()
{
    // Services Post Type
    register_post_type('aqualuxe_service', [
        'labels' => [
            'name' => esc_html_x('Services', 'Post type general name', 'aqualuxe'),
            'singular_name' => esc_html_x('Service', 'Post type singular name', 'aqualuxe'),
            'menu_name' => esc_html_x('Services', 'Admin Menu text', 'aqualuxe'),
            'add_new' => esc_html__('Add New', 'aqualuxe'),
            'add_new_item' => esc_html__('Add New Service', 'aqualuxe'),
            'new_item' => esc_html__('New Service', 'aqualuxe'),
            'edit_item' => esc_html__('Edit Service', 'aqualuxe'),
            'view_item' => esc_html__('View Service', 'aqualuxe'),
            'all_items' => esc_html__('All Services', 'aqualuxe'),
            'search_items' => esc_html__('Search Services', 'aqualuxe'),
            'not_found' => esc_html__('No services found.', 'aqualuxe'),
            'not_found_in_trash' => esc_html__('No services found in Trash.', 'aqualuxe'),
        ],
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
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'template' => [
            ['core/paragraph', ['placeholder' => 'Describe this service...']],
        ],
    ]);

    // Portfolio/Projects Post Type
    register_post_type('aqualuxe_project', [
        'labels' => [
            'name' => esc_html_x('Projects', 'Post type general name', 'aqualuxe'),
            'singular_name' => esc_html_x('Project', 'Post type singular name', 'aqualuxe'),
            'menu_name' => esc_html_x('Projects', 'Admin Menu text', 'aqualuxe'),
            'add_new' => esc_html__('Add New', 'aqualuxe'),
            'add_new_item' => esc_html__('Add New Project', 'aqualuxe'),
            'new_item' => esc_html__('New Project', 'aqualuxe'),
            'edit_item' => esc_html__('Edit Project', 'aqualuxe'),
            'view_item' => esc_html__('View Project', 'aqualuxe'),
            'all_items' => esc_html__('All Projects', 'aqualuxe'),
            'search_items' => esc_html__('Search Projects', 'aqualuxe'),
            'not_found' => esc_html__('No projects found.', 'aqualuxe'),
            'not_found_in_trash' => esc_html__('No projects found in Trash.', 'aqualuxe'),
        ],
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'projects'],
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 21,
        'menu_icon' => 'dashicons-portfolio',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
    ]);

    // Testimonials Post Type
    register_post_type('aqualuxe_testimonial', [
        'labels' => [
            'name' => esc_html_x('Testimonials', 'Post type general name', 'aqualuxe'),
            'singular_name' => esc_html_x('Testimonial', 'Post type singular name', 'aqualuxe'),
            'menu_name' => esc_html_x('Testimonials', 'Admin Menu text', 'aqualuxe'),
            'add_new' => esc_html__('Add New', 'aqualuxe'),
            'add_new_item' => esc_html__('Add New Testimonial', 'aqualuxe'),
            'new_item' => esc_html__('New Testimonial', 'aqualuxe'),
            'edit_item' => esc_html__('Edit Testimonial', 'aqualuxe'),
            'view_item' => esc_html__('View Testimonial', 'aqualuxe'),
            'all_items' => esc_html__('All Testimonials', 'aqualuxe'),
            'search_items' => esc_html__('Search Testimonials', 'aqualuxe'),
            'not_found' => esc_html__('No testimonials found.', 'aqualuxe'),
            'not_found_in_trash' => esc_html__('No testimonials found in Trash.', 'aqualuxe'),
        ],
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'testimonials'],
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => 22,
        'menu_icon' => 'dashicons-format-quote',
        'supports' => ['title', 'editor', 'thumbnail', 'custom-fields'],
    ]);

    // Events Post Type
    register_post_type('aqualuxe_event', [
        'labels' => [
            'name' => esc_html_x('Events', 'Post type general name', 'aqualuxe'),
            'singular_name' => esc_html_x('Event', 'Post type singular name', 'aqualuxe'),
            'menu_name' => esc_html_x('Events', 'Admin Menu text', 'aqualuxe'),
            'add_new' => esc_html__('Add New', 'aqualuxe'),
            'add_new_item' => esc_html__('Add New Event', 'aqualuxe'),
            'new_item' => esc_html__('New Event', 'aqualuxe'),
            'edit_item' => esc_html__('Edit Event', 'aqualuxe'),
            'view_item' => esc_html__('View Event', 'aqualuxe'),
            'all_items' => esc_html__('All Events', 'aqualuxe'),
            'search_items' => esc_html__('Search Events', 'aqualuxe'),
            'not_found' => esc_html__('No events found.', 'aqualuxe'),
            'not_found_in_trash' => esc_html__('No events found in Trash.', 'aqualuxe'),
        ],
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
        'menu_position' => 23,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
    ]);

    // FAQ Post Type
    register_post_type('aqualuxe_faq', [
        'labels' => [
            'name' => esc_html_x('FAQs', 'Post type general name', 'aqualuxe'),
            'singular_name' => esc_html_x('FAQ', 'Post type singular name', 'aqualuxe'),
            'menu_name' => esc_html_x('FAQs', 'Admin Menu text', 'aqualuxe'),
            'add_new' => esc_html__('Add New', 'aqualuxe'),
            'add_new_item' => esc_html__('Add New FAQ', 'aqualuxe'),
            'new_item' => esc_html__('New FAQ', 'aqualuxe'),
            'edit_item' => esc_html__('Edit FAQ', 'aqualuxe'),
            'view_item' => esc_html__('View FAQ', 'aqualuxe'),
            'all_items' => esc_html__('All FAQs', 'aqualuxe'),
            'search_items' => esc_html__('Search FAQs', 'aqualuxe'),
            'not_found' => esc_html__('No FAQs found.', 'aqualuxe'),
            'not_found_in_trash' => esc_html__('No FAQs found in Trash.', 'aqualuxe'),
        ],
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'faq'],
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 24,
        'menu_icon' => 'dashicons-editor-help',
        'supports' => ['title', 'editor', 'custom-fields'],
    ]);

    // Team Members Post Type
    register_post_type('aqualuxe_team', [
        'labels' => [
            'name' => esc_html_x('Team Members', 'Post type general name', 'aqualuxe'),
            'singular_name' => esc_html_x('Team Member', 'Post type singular name', 'aqualuxe'),
            'menu_name' => esc_html_x('Team', 'Admin Menu text', 'aqualuxe'),
            'add_new' => esc_html__('Add New', 'aqualuxe'),
            'add_new_item' => esc_html__('Add New Team Member', 'aqualuxe'),
            'new_item' => esc_html__('New Team Member', 'aqualuxe'),
            'edit_item' => esc_html__('Edit Team Member', 'aqualuxe'),
            'view_item' => esc_html__('View Team Member', 'aqualuxe'),
            'all_items' => esc_html__('All Team Members', 'aqualuxe'),
            'search_items' => esc_html__('Search Team Members', 'aqualuxe'),
            'not_found' => esc_html__('No team members found.', 'aqualuxe'),
            'not_found_in_trash' => esc_html__('No team members found in Trash.', 'aqualuxe'),
        ],
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
        'menu_position' => 25,
        'menu_icon' => 'dashicons-groups',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
    ]);

    // Bookings Post Type (for scheduling)
    register_post_type('aqualuxe_booking', [
        'labels' => [
            'name' => esc_html_x('Bookings', 'Post type general name', 'aqualuxe'),
            'singular_name' => esc_html_x('Booking', 'Post type singular name', 'aqualuxe'),
            'menu_name' => esc_html_x('Bookings', 'Admin Menu text', 'aqualuxe'),
            'add_new' => esc_html__('Add New', 'aqualuxe'),
            'add_new_item' => esc_html__('Add New Booking', 'aqualuxe'),
            'new_item' => esc_html__('New Booking', 'aqualuxe'),
            'edit_item' => esc_html__('Edit Booking', 'aqualuxe'),
            'view_item' => esc_html__('View Booking', 'aqualuxe'),
            'all_items' => esc_html__('All Bookings', 'aqualuxe'),
            'search_items' => esc_html__('Search Bookings', 'aqualuxe'),
            'not_found' => esc_html__('No bookings found.', 'aqualuxe'),
            'not_found_in_trash' => esc_html__('No bookings found in Trash.', 'aqualuxe'),
        ],
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => false,
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => 26,
        'menu_icon' => 'dashicons-calendar',
        'supports' => ['title', 'custom-fields'],
        'capabilities' => [
            'create_posts' => 'manage_bookings',
            'edit_posts' => 'manage_bookings',
            'edit_others_posts' => 'manage_bookings',
            'publish_posts' => 'manage_bookings',
            'read_private_posts' => 'manage_bookings',
            'delete_posts' => 'manage_bookings',
            'delete_private_posts' => 'manage_bookings',
            'delete_published_posts' => 'manage_bookings',
            'delete_others_posts' => 'manage_bookings',
            'edit_private_posts' => 'manage_bookings',
            'edit_published_posts' => 'manage_bookings',
        ],
    ]);
}
add_action('init', 'aqualuxe_register_post_types');

/**
 * Flush rewrite rules on theme activation
 */
function aqualuxe_flush_rewrite_rules()
{
    aqualuxe_register_post_types();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'aqualuxe_flush_rewrite_rules');

/**
 * Add custom capabilities to administrator role
 */
function aqualuxe_add_custom_capabilities()
{
    $role = get_role('administrator');
    if ($role) {
        $role->add_cap('manage_bookings');
    }
}
add_action('admin_init', 'aqualuxe_add_custom_capabilities');

/**
 * Customize post type columns
 */
function aqualuxe_customize_post_columns($columns)
{
    $post_type = get_current_screen()->post_type;
    
    switch ($post_type) {
        case 'aqualuxe_service':
            $columns['service_price'] = esc_html__('Price', 'aqualuxe');
            break;
        case 'aqualuxe_event':
            $columns['event_date'] = esc_html__('Event Date', 'aqualuxe');
            break;
        case 'aqualuxe_booking':
            $columns['booking_date'] = esc_html__('Booking Date', 'aqualuxe');
            $columns['booking_status'] = esc_html__('Status', 'aqualuxe');
            break;
        case 'aqualuxe_team':
            $columns['team_position'] = esc_html__('Position', 'aqualuxe');
            break;
    }
    
    return $columns;
}
add_filter('manage_posts_columns', 'aqualuxe_customize_post_columns');

/**
 * Populate custom columns
 */
function aqualuxe_custom_column_content($column, $post_id)
{
    switch ($column) {
        case 'service_price':
            $price = get_post_meta($post_id, '_aqualuxe_service_price', true);
            echo $price ? esc_html($price) : '—';
            break;
        case 'event_date':
            $date = get_post_meta($post_id, '_aqualuxe_event_date', true);
            echo $date ? esc_html(date('M j, Y', strtotime($date))) : '—';
            break;
        case 'booking_date':
            $date = get_post_meta($post_id, '_aqualuxe_booking_date', true);
            echo $date ? esc_html(date('M j, Y g:i A', strtotime($date))) : '—';
            break;
        case 'booking_status':
            $status = get_post_meta($post_id, '_aqualuxe_booking_status', true);
            $statuses = [
                'pending' => ['label' => esc_html__('Pending', 'aqualuxe'), 'color' => '#f59e0b'],
                'confirmed' => ['label' => esc_html__('Confirmed', 'aqualuxe'), 'color' => '#10b981'],
                'completed' => ['label' => esc_html__('Completed', 'aqualuxe'), 'color' => '#6b7280'],
                'cancelled' => ['label' => esc_html__('Cancelled', 'aqualuxe'), 'color' => '#ef4444'],
            ];
            
            if (isset($statuses[$status])) {
                echo '<span style="color: ' . esc_attr($statuses[$status]['color']) . '; font-weight: 600;">' . 
                     esc_html($statuses[$status]['label']) . '</span>';
            } else {
                echo '—';
            }
            break;
        case 'team_position':
            $position = get_post_meta($post_id, '_aqualuxe_team_position', true);
            echo $position ? esc_html($position) : '—';
            break;
    }
}
add_action('manage_posts_custom_column', 'aqualuxe_custom_column_content', 10, 2);