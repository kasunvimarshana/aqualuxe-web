<?php
/**
 * Custom Post Types Registration
 *
 * Optimized and DRY approach to registering custom post types
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Helper function to generate post type labels
 *
 * @param string $singular Singular name
 * @param string $plural Plural name
 * @param string $featured_image Custom featured image label (optional)
 * @return array Labels array
 */
function aqualuxe_get_post_type_labels($singular, $plural, $featured_image = 'Featured Image') {
    return array(
        'name'                  => _x($plural, 'Post Type General Name', 'aqualuxe'),
        'singular_name'         => _x($singular, 'Post Type Singular Name', 'aqualuxe'),
        'menu_name'             => __($plural, 'aqualuxe'),
        'name_admin_bar'        => __($singular, 'aqualuxe'),
        'archives'              => sprintf(__('%s Archives', 'aqualuxe'), $singular),
        'attributes'            => sprintf(__('%s Attributes', 'aqualuxe'), $singular),
        'parent_item_colon'     => sprintf(__('Parent %s:', 'aqualuxe'), $singular),
        'all_items'             => sprintf(__('All %s', 'aqualuxe'), $plural),
        'add_new_item'          => sprintf(__('Add New %s', 'aqualuxe'), $singular),
        'add_new'               => __('Add New', 'aqualuxe'),
        'new_item'              => sprintf(__('New %s', 'aqualuxe'), $singular),
        'edit_item'             => sprintf(__('Edit %s', 'aqualuxe'), $singular),
        'update_item'           => sprintf(__('Update %s', 'aqualuxe'), $singular),
        'view_item'             => sprintf(__('View %s', 'aqualuxe'), $singular),
        'view_items'            => sprintf(__('View %s', 'aqualuxe'), $plural),
        'search_items'          => sprintf(__('Search %s', 'aqualuxe'), $singular),
        'not_found'             => __('Not found', 'aqualuxe'),
        'not_found_in_trash'    => __('Not found in Trash', 'aqualuxe'),
        'featured_image'        => __($featured_image, 'aqualuxe'),
        'set_featured_image'    => sprintf(__('Set %s', 'aqualuxe'), strtolower($featured_image)),
        'remove_featured_image' => sprintf(__('Remove %s', 'aqualuxe'), strtolower($featured_image)),
        'use_featured_image'    => sprintf(__('Use as %s', 'aqualuxe'), strtolower($featured_image)),
        'insert_into_item'      => sprintf(__('Insert into %s', 'aqualuxe'), strtolower($singular)),
        'uploaded_to_this_item' => sprintf(__('Uploaded to this %s', 'aqualuxe'), strtolower($singular)),
        'items_list'            => sprintf(__('%s list', 'aqualuxe'), $plural),
        'items_list_navigation' => sprintf(__('%s list navigation', 'aqualuxe'), $plural),
        'filter_items_list'     => sprintf(__('Filter %s list', 'aqualuxe'), strtolower($plural)),
    );
}

/**
 * Helper function to register custom post types with defaults
 *
 * @param string $post_type Post type key
 * @param array $args Custom arguments
 * @return void
 */
function aqualuxe_register_post_type($post_type, $args = array()) {
    $defaults = array(
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'hierarchical'          => false,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields'),
        'rewrite'               => array('with_front' => false),
    );

    $args = wp_parse_args($args, $defaults);
    
    // Ensure rewrite slug is set if not provided
    if (is_array($args['rewrite']) && !isset($args['rewrite']['slug'])) {
        $args['rewrite']['slug'] = str_replace('aqualuxe_', '', $post_type);
    }

    register_post_type($post_type, $args);
}

/**
 * Register all custom post types
 */
function aqualuxe_register_custom_post_types() {
    // Services Post Type
    aqualuxe_register_post_type('aqualuxe_service', array(
        'label'                 => __('Service', 'aqualuxe'),
        'description'           => __('Aquatic services offered by AquaLuxe', 'aqualuxe'),
        'labels'                => aqualuxe_get_post_type_labels('Service', 'Services'),
        'taxonomies'            => array('service_category', 'service_tag'),
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-admin-tools',
        'rewrite'               => array('slug' => 'services', 'with_front' => false),
    ));

    // Events Post Type
    aqualuxe_register_post_type('aqualuxe_event', array(
        'label'                 => __('Event', 'aqualuxe'),
        'description'           => __('Aquatic events and experiences', 'aqualuxe'),
        'labels'                => aqualuxe_get_post_type_labels('Event', 'Events', 'Event Image'),
        'taxonomies'            => array('event_category', 'event_tag'),
        'menu_position'         => 21,
        'menu_icon'             => 'dashicons-calendar-alt',
        'rewrite'               => array('slug' => 'events', 'with_front' => false),
    ));

    // Bookings Post Type (Private)
    aqualuxe_register_post_type('aqualuxe_booking', array(
        'label'                 => __('Booking', 'aqualuxe'),
        'description'           => __('Service bookings and appointments', 'aqualuxe'),
        'labels'                => aqualuxe_get_post_type_labels('Booking', 'Bookings', 'Booking Image'),
        'supports'              => array('title', 'editor', 'custom-fields'),
        'menu_position'         => 22,
        'menu_icon'             => 'dashicons-clock',
        'public'                => false,
        'show_in_nav_menus'     => false,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'rewrite'               => false,
    ));

    // Testimonials Post Type
    aqualuxe_register_post_type('aqualuxe_testimonial', array(
        'label'                 => __('Testimonial', 'aqualuxe'),
        'description'           => __('Customer testimonials and reviews', 'aqualuxe'),
        'labels'                => aqualuxe_get_post_type_labels('Testimonial', 'Testimonials', 'Client Photo'),
        'supports'              => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'menu_position'         => 23,
        'menu_icon'             => 'dashicons-format-quote',
        'show_in_nav_menus'     => false,
        'rewrite'               => array('slug' => 'testimonials', 'with_front' => false),
    ));

    // Team Members Post Type
    aqualuxe_register_post_type('aqualuxe_team', array(
        'label'                 => __('Team Member', 'aqualuxe'),
        'description'           => __('Team members and staff', 'aqualuxe'),
        'labels'                => aqualuxe_get_post_type_labels('Team Member', 'Team Members', 'Member Photo'),
        'taxonomies'            => array('team_department'),
        'menu_position'         => 24,
        'menu_icon'             => 'dashicons-groups',
        'show_in_nav_menus'     => false,
        'rewrite'               => array('slug' => 'team', 'with_front' => false),
    ));

    // Projects Post Type
    aqualuxe_register_post_type('aqualuxe_project', array(
        'label'                 => __('Project', 'aqualuxe'),
        'description'           => __('Completed aquatic projects and installations', 'aqualuxe'),
        'labels'                => aqualuxe_get_post_type_labels('Project', 'Projects', 'Project Image'),
        'taxonomies'            => array('project_category', 'project_tag'),
        'menu_position'         => 25,
        'menu_icon'             => 'dashicons-portfolio',
        'rewrite'               => array('slug' => 'projects', 'with_front' => false),
    ));

    // FAQ Post Type
    aqualuxe_register_post_type('aqualuxe_faq', array(
        'label'                 => __('FAQ', 'aqualuxe'),
        'description'           => __('Frequently asked questions', 'aqualuxe'),
        'labels'                => aqualuxe_get_post_type_labels('FAQ', 'FAQs'),
        'supports'              => array('title', 'editor', 'custom-fields'),
        'taxonomies'            => array('faq_category'),
        'menu_position'         => 26,
        'menu_icon'             => 'dashicons-editor-help',
        'show_in_nav_menus'     => false,
        'rewrite'               => array('slug' => 'faq', 'with_front' => false),
    ));
}
add_action('init', 'aqualuxe_register_custom_post_types', 0);

/**
 * Flush rewrite rules on theme activation
 */
function aqualuxe_flush_rewrite_rules() {
    aqualuxe_register_custom_post_types();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'aqualuxe_flush_rewrite_rules');

/**
 * Add custom columns to post type admin lists
 */
function aqualuxe_add_custom_columns($columns) {
    global $typenow;
    
    switch ($typenow) {
        case 'aqualuxe_service':
            $columns['service_category'] = __('Category', 'aqualuxe');
            $columns['service_price'] = __('Price', 'aqualuxe');
            break;
            
        case 'aqualuxe_event':
            $columns['event_date'] = __('Event Date', 'aqualuxe');
            $columns['event_location'] = __('Location', 'aqualuxe');
            break;
            
        case 'aqualuxe_testimonial':
            $columns['client_name'] = __('Client', 'aqualuxe');
            $columns['rating'] = __('Rating', 'aqualuxe');
            break;
            
        case 'aqualuxe_team':
            $columns['position'] = __('Position', 'aqualuxe');
            $columns['department'] = __('Department', 'aqualuxe');
            break;
    }
    
    return $columns;
}
add_filter('manage_posts_columns', 'aqualuxe_add_custom_columns');

/**
 * Populate custom columns with data
 */
function aqualuxe_custom_column_data($column, $post_id) {
    switch ($column) {
        case 'service_category':
            $terms = get_the_terms($post_id, 'service_category');
            if ($terms && !is_wp_error($terms)) {
                echo esc_html(implode(', ', wp_list_pluck($terms, 'name')));
            }
            break;
            
        case 'service_price':
            $price = get_post_meta($post_id, '_service_price', true);
            echo $price ? esc_html($price) : '—';
            break;
            
        case 'event_date':
            $date = get_post_meta($post_id, '_event_date', true);
            echo $date ? esc_html(date_i18n(get_option('date_format'), strtotime($date))) : '—';
            break;
            
        case 'event_location':
            $location = get_post_meta($post_id, '_event_location', true);
            echo $location ? esc_html($location) : '—';
            break;
            
        case 'client_name':
            $name = get_post_meta($post_id, '_client_name', true);
            echo $name ? esc_html($name) : '—';
            break;
            
        case 'rating':
            $rating = get_post_meta($post_id, '_rating', true);
            if ($rating) {
                echo str_repeat('★', intval($rating)) . str_repeat('☆', 5 - intval($rating));
            } else {
                echo '—';
            }
            break;
            
        case 'position':
            $position = get_post_meta($post_id, '_position', true);
            echo $position ? esc_html($position) : '—';
            break;
            
        case 'department':
            $terms = get_the_terms($post_id, 'team_department');
            if ($terms && !is_wp_error($terms)) {
                echo esc_html(implode(', ', wp_list_pluck($terms, 'name')));
            }
            break;
    }
}
add_action('manage_posts_custom_column', 'aqualuxe_custom_column_data', 10, 2);

/**
 * Make custom columns sortable
 */
function aqualuxe_sortable_columns($columns) {
    global $typenow;
    
    switch ($typenow) {
        case 'aqualuxe_event':
            $columns['event_date'] = 'event_date';
            break;
            
        case 'aqualuxe_testimonial':
            $columns['rating'] = 'rating';
            break;
    }
    
    return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'aqualuxe_sortable_columns');

/**
 * Handle custom column sorting
 */
function aqualuxe_custom_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    
    $orderby = $query->get('orderby');
    
    if ($orderby === 'event_date') {
        $query->set('meta_key', '_event_date');
        $query->set('orderby', 'meta_value');
    } elseif ($orderby === 'rating') {
        $query->set('meta_key', '_rating');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'aqualuxe_custom_orderby');