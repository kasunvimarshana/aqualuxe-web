<?php
/**
 * Services Custom Post Type
 *
 * @package AquaLuxe
 */

/**
 * Register the Services custom post type
 */
function aqualuxe_register_services_post_type() {
    $labels = array(
        'name'                  => _x('Services', 'Post type general name', 'aqualuxe'),
        'singular_name'         => _x('Service', 'Post type singular name', 'aqualuxe'),
        'menu_name'             => _x('Services', 'Admin Menu text', 'aqualuxe'),
        'name_admin_bar'        => _x('Service', 'Add New on Toolbar', 'aqualuxe'),
        'add_new'               => __('Add New', 'aqualuxe'),
        'add_new_item'          => __('Add New Service', 'aqualuxe'),
        'new_item'              => __('New Service', 'aqualuxe'),
        'edit_item'             => __('Edit Service', 'aqualuxe'),
        'view_item'             => __('View Service', 'aqualuxe'),
        'all_items'             => __('All Services', 'aqualuxe'),
        'search_items'          => __('Search Services', 'aqualuxe'),
        'parent_item_colon'     => __('Parent Services:', 'aqualuxe'),
        'not_found'             => __('No services found.', 'aqualuxe'),
        'not_found_in_trash'    => __('No services found in Trash.', 'aqualuxe'),
        'featured_image'        => _x('Service Cover Image', 'Overrides the "Featured Image" phrase', 'aqualuxe'),
        'set_featured_image'    => _x('Set cover image', 'Overrides the "Set featured image" phrase', 'aqualuxe'),
        'remove_featured_image' => _x('Remove cover image', 'Overrides the "Remove featured image" phrase', 'aqualuxe'),
        'use_featured_image'    => _x('Use as cover image', 'Overrides the "Use as featured image" phrase', 'aqualuxe'),
        'archives'              => _x('Service archives', 'The post type archive label used in nav menus', 'aqualuxe'),
        'insert_into_item'      => _x('Insert into service', 'Overrides the "Insert into post" phrase', 'aqualuxe'),
        'uploaded_to_this_item' => _x('Uploaded to this service', 'Overrides the "Uploaded to this post" phrase', 'aqualuxe'),
        'filter_items_list'     => _x('Filter services list', 'Screen reader text for the filter links', 'aqualuxe'),
        'items_list_navigation' => _x('Services list navigation', 'Screen reader text for the pagination', 'aqualuxe'),
        'items_list'            => _x('Services list', 'Screen reader text for the items list', 'aqualuxe'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'services'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-hammer',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    );

    register_post_type('service', $args);

    // Register Service Category taxonomy
    $category_labels = array(
        'name'              => _x('Service Categories', 'taxonomy general name', 'aqualuxe'),
        'singular_name'     => _x('Service Category', 'taxonomy singular name', 'aqualuxe'),
        'search_items'      => __('Search Service Categories', 'aqualuxe'),
        'all_items'         => __('All Service Categories', 'aqualuxe'),
        'parent_item'       => __('Parent Service Category', 'aqualuxe'),
        'parent_item_colon' => __('Parent Service Category:', 'aqualuxe'),
        'edit_item'         => __('Edit Service Category', 'aqualuxe'),
        'update_item'       => __('Update Service Category', 'aqualuxe'),
        'add_new_item'      => __('Add New Service Category', 'aqualuxe'),
        'new_item_name'     => __('New Service Category Name', 'aqualuxe'),
        'menu_name'         => __('Categories', 'aqualuxe'),
    );

    $category_args = array(
        'hierarchical'      => true,
        'labels'            => $category_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'service-category'),
        'show_in_rest'      => true,
    );

    register_taxonomy('service_category', array('service'), $category_args);
}
add_action('init', 'aqualuxe_register_services_post_type');

/**
 * Add custom meta boxes for the Services post type
 */
function aqualuxe_add_service_meta_boxes() {
    add_meta_box(
        'service_details',
        __('Service Details', 'aqualuxe'),
        'aqualuxe_service_details_callback',
        'service',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_add_service_meta_boxes');

/**
 * Service details meta box callback
 */
function aqualuxe_service_details_callback($post) {
    wp_nonce_field('aqualuxe_service_details', 'aqualuxe_service_details_nonce');

    $price = get_post_meta($post->ID, '_service_price', true);
    $duration = get_post_meta($post->ID, '_service_duration', true);
    $icon = get_post_meta($post->ID, '_service_icon', true);
    $booking_url = get_post_meta($post->ID, '_service_booking_url', true);
    ?>
    <p>
        <label for="service_price"><?php esc_html_e('Price', 'aqualuxe'); ?></label><br>
        <input type="text" id="service_price" name="service_price" value="<?php echo esc_attr($price); ?>" class="regular-text">
        <span class="description"><?php esc_html_e('Enter the price for this service (e.g. $99, $50-$100, or "Contact for pricing")', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="service_duration"><?php esc_html_e('Duration', 'aqualuxe'); ?></label><br>
        <input type="text" id="service_duration" name="service_duration" value="<?php echo esc_attr($duration); ?>" class="regular-text">
        <span class="description"><?php esc_html_e('Enter the duration for this service (e.g. 1 hour, 30 minutes, 2-3 days)', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="service_icon"><?php esc_html_e('Icon', 'aqualuxe'); ?></label><br>
        <input type="text" id="service_icon" name="service_icon" value="<?php echo esc_attr($icon); ?>" class="regular-text">
        <span class="description"><?php esc_html_e('Enter a FontAwesome icon class (e.g. fa-fish)', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="service_booking_url"><?php esc_html_e('Booking URL', 'aqualuxe'); ?></label><br>
        <input type="url" id="service_booking_url" name="service_booking_url" value="<?php echo esc_url($booking_url); ?>" class="regular-text">
        <span class="description"><?php esc_html_e('Enter a URL for booking this service (optional)', 'aqualuxe'); ?></span>
    </p>
    <?php
}

/**
 * Save service details meta box data
 */
function aqualuxe_save_service_details($post_id) {
    // Check if our nonce is set
    if (!isset($_POST['aqualuxe_service_details_nonce'])) {
        return;
    }

    // Verify that the nonce is valid
    if (!wp_verify_nonce($_POST['aqualuxe_service_details_nonce'], 'aqualuxe_service_details')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions
    if (isset($_POST['post_type']) && 'service' == $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Save the service price
    if (isset($_POST['service_price'])) {
        update_post_meta($post_id, '_service_price', sanitize_text_field($_POST['service_price']));
    }

    // Save the service duration
    if (isset($_POST['service_duration'])) {
        update_post_meta($post_id, '_service_duration', sanitize_text_field($_POST['service_duration']));
    }

    // Save the service icon
    if (isset($_POST['service_icon'])) {
        update_post_meta($post_id, '_service_icon', sanitize_text_field($_POST['service_icon']));
    }

    // Save the service booking URL
    if (isset($_POST['service_booking_url'])) {
        update_post_meta($post_id, '_service_booking_url', esc_url_raw($_POST['service_booking_url']));
    }
}
add_action('save_post', 'aqualuxe_save_service_details');

/**
 * Add service details to the services template
 */
function aqualuxe_service_details() {
    if (is_singular('service')) {
        global $post;
        
        $price = get_post_meta($post->ID, '_service_price', true);
        $duration = get_post_meta($post->ID, '_service_duration', true);
        $booking_url = get_post_meta($post->ID, '_service_booking_url', true);
        
        if ($price || $duration || $booking_url) {
            echo '<div class="service-details">';
            
            if ($price) {
                echo '<div class="service-price">';
                echo '<span class="label">' . esc_html__('Price:', 'aqualuxe') . '</span> ';
                echo '<span class="value">' . esc_html($price) . '</span>';
                echo '</div>';
            }
            
            if ($duration) {
                echo '<div class="service-duration">';
                echo '<span class="label">' . esc_html__('Duration:', 'aqualuxe') . '</span> ';
                echo '<span class="value">' . esc_html($duration) . '</span>';
                echo '</div>';
            }
            
            if ($booking_url) {
                echo '<div class="service-booking">';
                echo '<a href="' . esc_url($booking_url) . '" class="button">' . esc_html__('Book Now', 'aqualuxe') . '</a>';
                echo '</div>';
            }
            
            echo '</div>';
        }
    }
}
add_action('aqualuxe_after_content', 'aqualuxe_service_details');

/**
 * Register service shortcode
 */
function aqualuxe_services_shortcode($atts) {
    $atts = shortcode_atts(array(
        'category' => '',
        'columns' => 3,
        'count' => -1,
    ), $atts);
    
    $args = array(
        'post_type' => 'service',
        'posts_per_page' => $atts['count'],
    );
    
    if (!empty($atts['category'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'service_category',
                'field' => 'slug',
                'terms' => explode(',', $atts['category']),
            ),
        );
    }
    
    $services = new WP_Query($args);
    
    ob_start();
    
    if ($services->have_posts()) {
        echo '<div class="services-grid columns-' . absint($atts['columns']) . '">';
        
        while ($services->have_posts()) {
            $services->the_post();
            
            $price = get_post_meta(get_the_ID(), '_service_price', true);
            $icon = get_post_meta(get_the_ID(), '_service_icon', true);
            
            echo '<div class="service-item">';
            
            if ($icon) {
                echo '<div class="service-icon"><i class="fa ' . esc_attr($icon) . '"></i></div>';
            }
            
            echo '<h3 class="service-title"><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></h3>';
            
            if (has_excerpt()) {
                echo '<div class="service-excerpt">' . get_the_excerpt() . '</div>';
            }
            
            if ($price) {
                echo '<div class="service-price">' . esc_html($price) . '</div>';
            }
            
            echo '<a href="' . esc_url(get_permalink()) . '" class="service-link">' . esc_html__('Learn More', 'aqualuxe') . '</a>';
            
            echo '</div>';
        }
        
        echo '</div>';
    }
    
    wp_reset_postdata();
    
    return ob_get_clean();
}
add_shortcode('services', 'aqualuxe_services_shortcode');