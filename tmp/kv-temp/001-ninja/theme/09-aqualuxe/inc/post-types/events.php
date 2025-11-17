<?php
/**
 * Events Custom Post Type
 *
 * @package AquaLuxe
 */

/**
 * Register the Events custom post type
 */
function aqualuxe_register_events_post_type() {
    $labels = array(
        'name'                  => _x('Events', 'Post type general name', 'aqualuxe'),
        'singular_name'         => _x('Event', 'Post type singular name', 'aqualuxe'),
        'menu_name'             => _x('Events', 'Admin Menu text', 'aqualuxe'),
        'name_admin_bar'        => _x('Event', 'Add New on Toolbar', 'aqualuxe'),
        'add_new'               => __('Add New', 'aqualuxe'),
        'add_new_item'          => __('Add New Event', 'aqualuxe'),
        'new_item'              => __('New Event', 'aqualuxe'),
        'edit_item'             => __('Edit Event', 'aqualuxe'),
        'view_item'             => __('View Event', 'aqualuxe'),
        'all_items'             => __('All Events', 'aqualuxe'),
        'search_items'          => __('Search Events', 'aqualuxe'),
        'parent_item_colon'     => __('Parent Events:', 'aqualuxe'),
        'not_found'             => __('No events found.', 'aqualuxe'),
        'not_found_in_trash'    => __('No events found in Trash.', 'aqualuxe'),
        'featured_image'        => _x('Event Cover Image', 'Overrides the "Featured Image" phrase', 'aqualuxe'),
        'set_featured_image'    => _x('Set cover image', 'Overrides the "Set featured image" phrase', 'aqualuxe'),
        'remove_featured_image' => _x('Remove cover image', 'Overrides the "Remove featured image" phrase', 'aqualuxe'),
        'use_featured_image'    => _x('Use as cover image', 'Overrides the "Use as featured image" phrase', 'aqualuxe'),
        'archives'              => _x('Event archives', 'The post type archive label used in nav menus', 'aqualuxe'),
        'insert_into_item'      => _x('Insert into event', 'Overrides the "Insert into post" phrase', 'aqualuxe'),
        'uploaded_to_this_item' => _x('Uploaded to this event', 'Overrides the "Uploaded to this post" phrase', 'aqualuxe'),
        'filter_items_list'     => _x('Filter events list', 'Screen reader text for the filter links', 'aqualuxe'),
        'items_list_navigation' => _x('Events list navigation', 'Screen reader text for the pagination', 'aqualuxe'),
        'items_list'            => _x('Events list', 'Screen reader text for the items list', 'aqualuxe'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'events'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 21,
        'menu_icon'          => 'dashicons-calendar-alt',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    );

    register_post_type('event', $args);

    // Register Event Category taxonomy
    $category_labels = array(
        'name'              => _x('Event Categories', 'taxonomy general name', 'aqualuxe'),
        'singular_name'     => _x('Event Category', 'taxonomy singular name', 'aqualuxe'),
        'search_items'      => __('Search Event Categories', 'aqualuxe'),
        'all_items'         => __('All Event Categories', 'aqualuxe'),
        'parent_item'       => __('Parent Event Category', 'aqualuxe'),
        'parent_item_colon' => __('Parent Event Category:', 'aqualuxe'),
        'edit_item'         => __('Edit Event Category', 'aqualuxe'),
        'update_item'       => __('Update Event Category', 'aqualuxe'),
        'add_new_item'      => __('Add New Event Category', 'aqualuxe'),
        'new_item_name'     => __('New Event Category Name', 'aqualuxe'),
        'menu_name'         => __('Categories', 'aqualuxe'),
    );

    $category_args = array(
        'hierarchical'      => true,
        'labels'            => $category_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'event-category'),
        'show_in_rest'      => true,
    );

    register_taxonomy('event_category', array('event'), $category_args);
}
add_action('init', 'aqualuxe_register_events_post_type');

/**
 * Add custom meta boxes for the Events post type
 */
function aqualuxe_add_event_meta_boxes() {
    add_meta_box(
        'event_details',
        __('Event Details', 'aqualuxe'),
        'aqualuxe_event_details_callback',
        'event',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_add_event_meta_boxes');

/**
 * Event details meta box callback
 */
function aqualuxe_event_details_callback($post) {
    wp_nonce_field('aqualuxe_event_details', 'aqualuxe_event_details_nonce');

    $start_date = get_post_meta($post->ID, '_event_start_date', true);
    $end_date = get_post_meta($post->ID, '_event_end_date', true);
    $time = get_post_meta($post->ID, '_event_time', true);
    $location = get_post_meta($post->ID, '_event_location', true);
    $address = get_post_meta($post->ID, '_event_address', true);
    $cost = get_post_meta($post->ID, '_event_cost', true);
    $registration_url = get_post_meta($post->ID, '_event_registration_url', true);
    ?>
    <p>
        <label for="event_start_date"><?php esc_html_e('Start Date', 'aqualuxe'); ?></label><br>
        <input type="date" id="event_start_date" name="event_start_date" value="<?php echo esc_attr($start_date); ?>" class="regular-text">
    </p>
    <p>
        <label for="event_end_date"><?php esc_html_e('End Date', 'aqualuxe'); ?></label><br>
        <input type="date" id="event_end_date" name="event_end_date" value="<?php echo esc_attr($end_date); ?>" class="regular-text">
        <span class="description"><?php esc_html_e('Leave blank for single-day events', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="event_time"><?php esc_html_e('Time', 'aqualuxe'); ?></label><br>
        <input type="text" id="event_time" name="event_time" value="<?php echo esc_attr($time); ?>" class="regular-text">
        <span class="description"><?php esc_html_e('e.g. 10:00 AM - 4:00 PM', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="event_location"><?php esc_html_e('Location Name', 'aqualuxe'); ?></label><br>
        <input type="text" id="event_location" name="event_location" value="<?php echo esc_attr($location); ?>" class="regular-text">
        <span class="description"><?php esc_html_e('e.g. AquaLuxe Showroom', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="event_address"><?php esc_html_e('Address', 'aqualuxe'); ?></label><br>
        <textarea id="event_address" name="event_address" rows="3" class="large-text"><?php echo esc_textarea($address); ?></textarea>
    </p>
    <p>
        <label for="event_cost"><?php esc_html_e('Cost', 'aqualuxe'); ?></label><br>
        <input type="text" id="event_cost" name="event_cost" value="<?php echo esc_attr($cost); ?>" class="regular-text">
        <span class="description"><?php esc_html_e('e.g. $10, Free, or Varies', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="event_registration_url"><?php esc_html_e('Registration URL', 'aqualuxe'); ?></label><br>
        <input type="url" id="event_registration_url" name="event_registration_url" value="<?php echo esc_url($registration_url); ?>" class="large-text">
    </p>
    <?php
}

/**
 * Save event details meta box data
 */
function aqualuxe_save_event_details($post_id) {
    // Check if our nonce is set
    if (!isset($_POST['aqualuxe_event_details_nonce'])) {
        return;
    }

    // Verify that the nonce is valid
    if (!wp_verify_nonce($_POST['aqualuxe_event_details_nonce'], 'aqualuxe_event_details')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions
    if (isset($_POST['post_type']) && 'event' == $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Save the event start date
    if (isset($_POST['event_start_date'])) {
        update_post_meta($post_id, '_event_start_date', sanitize_text_field($_POST['event_start_date']));
    }

    // Save the event end date
    if (isset($_POST['event_end_date'])) {
        update_post_meta($post_id, '_event_end_date', sanitize_text_field($_POST['event_end_date']));
    }

    // Save the event time
    if (isset($_POST['event_time'])) {
        update_post_meta($post_id, '_event_time', sanitize_text_field($_POST['event_time']));
    }

    // Save the event location
    if (isset($_POST['event_location'])) {
        update_post_meta($post_id, '_event_location', sanitize_text_field($_POST['event_location']));
    }

    // Save the event address
    if (isset($_POST['event_address'])) {
        update_post_meta($post_id, '_event_address', sanitize_textarea_field($_POST['event_address']));
    }

    // Save the event cost
    if (isset($_POST['event_cost'])) {
        update_post_meta($post_id, '_event_cost', sanitize_text_field($_POST['event_cost']));
    }

    // Save the event registration URL
    if (isset($_POST['event_registration_url'])) {
        update_post_meta($post_id, '_event_registration_url', esc_url_raw($_POST['event_registration_url']));
    }
}
add_action('save_post', 'aqualuxe_save_event_details');

/**
 * Add event details to the events template
 */
function aqualuxe_event_details() {
    if (is_singular('event')) {
        global $post;
        
        $start_date = get_post_meta($post->ID, '_event_start_date', true);
        $end_date = get_post_meta($post->ID, '_event_end_date', true);
        $time = get_post_meta($post->ID, '_event_time', true);
        $location = get_post_meta($post->ID, '_event_location', true);
        $address = get_post_meta($post->ID, '_event_address', true);
        $cost = get_post_meta($post->ID, '_event_cost', true);
        $registration_url = get_post_meta($post->ID, '_event_registration_url', true);
        
        if ($start_date) {
            echo '<div class="event-details">';
            
            echo '<div class="event-date">';
            echo '<span class="label">' . esc_html__('Date:', 'aqualuxe') . '</span> ';
            echo '<span class="value">';
            
            // Format the date
            $start_date_formatted = date_i18n(get_option('date_format'), strtotime($start_date));
            echo esc_html($start_date_formatted);
            
            // Add end date if it exists
            if ($end_date) {
                $end_date_formatted = date_i18n(get_option('date_format'), strtotime($end_date));
                echo ' - ' . esc_html($end_date_formatted);
            }
            
            echo '</span>';
            echo '</div>';
            
            if ($time) {
                echo '<div class="event-time">';
                echo '<span class="label">' . esc_html__('Time:', 'aqualuxe') . '</span> ';
                echo '<span class="value">' . esc_html($time) . '</span>';
                echo '</div>';
            }
            
            if ($location) {
                echo '<div class="event-location">';
                echo '<span class="label">' . esc_html__('Location:', 'aqualuxe') . '</span> ';
                echo '<span class="value">' . esc_html($location) . '</span>';
                echo '</div>';
            }
            
            if ($address) {
                echo '<div class="event-address">';
                echo '<span class="label">' . esc_html__('Address:', 'aqualuxe') . '</span> ';
                echo '<span class="value">' . nl2br(esc_html($address)) . '</span>';
                echo '</div>';
            }
            
            if ($cost) {
                echo '<div class="event-cost">';
                echo '<span class="label">' . esc_html__('Cost:', 'aqualuxe') . '</span> ';
                echo '<span class="value">' . esc_html($cost) . '</span>';
                echo '</div>';
            }
            
            if ($registration_url) {
                echo '<div class="event-registration">';
                echo '<a href="' . esc_url($registration_url) . '" class="button">' . esc_html__('Register Now', 'aqualuxe') . '</a>';
                echo '</div>';
            }
            
            echo '</div>';
        }
    }
}
add_action('aqualuxe_after_content', 'aqualuxe_event_details');

/**
 * Register events shortcode
 */
function aqualuxe_events_shortcode($atts) {
    $atts = shortcode_atts(array(
        'category' => '',
        'count' => 5,
        'upcoming' => 'yes',
    ), $atts);
    
    $args = array(
        'post_type' => 'event',
        'posts_per_page' => $atts['count'],
        'meta_key' => '_event_start_date',
        'orderby' => 'meta_value',
        'order' => 'ASC',
    );
    
    // Filter by category if specified
    if (!empty($atts['category'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'event_category',
                'field' => 'slug',
                'terms' => explode(',', $atts['category']),
            ),
        );
    }
    
    // Only show upcoming events if specified
    if ($atts['upcoming'] === 'yes') {
        $args['meta_query'] = array(
            array(
                'key' => '_event_start_date',
                'value' => date('Y-m-d'),
                'compare' => '>=',
                'type' => 'DATE',
            ),
        );
    }
    
    $events = new WP_Query($args);
    
    ob_start();
    
    if ($events->have_posts()) {
        echo '<div class="events-list">';
        
        while ($events->have_posts()) {
            $events->the_post();
            
            $start_date = get_post_meta(get_the_ID(), '_event_start_date', true);
            $location = get_post_meta(get_the_ID(), '_event_location', true);
            
            echo '<div class="event-item">';
            
            if (has_post_thumbnail()) {
                echo '<div class="event-image">';
                echo '<a href="' . esc_url(get_permalink()) . '">';
                the_post_thumbnail('medium');
                echo '</a>';
                echo '</div>';
            }
            
            echo '<div class="event-content">';
            
            if ($start_date) {
                $start_date_formatted = date_i18n(get_option('date_format'), strtotime($start_date));
                echo '<div class="event-date">' . esc_html($start_date_formatted) . '</div>';
            }
            
            echo '<h3 class="event-title"><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></h3>';
            
            if ($location) {
                echo '<div class="event-location">' . esc_html($location) . '</div>';
            }
            
            if (has_excerpt()) {
                echo '<div class="event-excerpt">' . get_the_excerpt() . '</div>';
            }
            
            echo '<a href="' . esc_url(get_permalink()) . '" class="event-link">' . esc_html__('Learn More', 'aqualuxe') . '</a>';
            
            echo '</div>'; // .event-content
            
            echo '</div>'; // .event-item
        }
        
        echo '</div>'; // .events-list
    } else {
        echo '<p>' . esc_html__('No events found.', 'aqualuxe') . '</p>';
    }
    
    wp_reset_postdata();
    
    return ob_get_clean();
}
add_shortcode('events', 'aqualuxe_events_shortcode');

/**
 * Add upcoming events widget
 */
class Aqualuxe_Upcoming_Events_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'aqualuxe_upcoming_events',
            __('AquaLuxe Upcoming Events', 'aqualuxe'),
            array('description' => __('Display a list of upcoming events', 'aqualuxe'))
        );
    }

    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Upcoming Events', 'aqualuxe');
        $count = !empty($instance['count']) ? absint($instance['count']) : 5;
        $category = !empty($instance['category']) ? $instance['category'] : '';
        
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);
        
        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        // Query upcoming events
        $events_args = array(
            'post_type' => 'event',
            'posts_per_page' => $count,
            'meta_key' => '_event_start_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => '_event_start_date',
                    'value' => date('Y-m-d'),
                    'compare' => '>=',
                    'type' => 'DATE',
                ),
            ),
        );
        
        if (!empty($category)) {
            $events_args['tax_query'] = array(
                array(
                    'taxonomy' => 'event_category',
                    'field' => 'slug',
                    'terms' => $category,
                ),
            );
        }
        
        $events = new WP_Query($events_args);
        
        if ($events->have_posts()) {
            echo '<ul class="upcoming-events-widget">';
            
            while ($events->have_posts()) {
                $events->the_post();
                
                $start_date = get_post_meta(get_the_ID(), '_event_start_date', true);
                
                echo '<li class="event-item">';
                
                if ($start_date) {
                    $start_date_formatted = date_i18n(get_option('date_format'), strtotime($start_date));
                    echo '<span class="event-date">' . esc_html($start_date_formatted) . '</span>';
                }
                
                echo '<a href="' . esc_url(get_permalink()) . '" class="event-title">' . get_the_title() . '</a>';
                
                echo '</li>';
            }
            
            echo '</ul>';
            
            echo '<p class="all-events-link"><a href="' . esc_url(get_post_type_archive_link('event')) . '">' . __('View All Events', 'aqualuxe') . '</a></p>';
        } else {
            echo '<p>' . esc_html__('No upcoming events.', 'aqualuxe') . '</p>';
        }
        
        wp_reset_postdata();
        
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Upcoming Events', 'aqualuxe');
        $count = !empty($instance['count']) ? absint($instance['count']) : 5;
        $category = !empty($instance['category']) ? $instance['category'] : '';
        
        // Get event categories
        $categories = get_terms(array(
            'taxonomy' => 'event_category',
            'hide_empty' => false,
        ));
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('count')); ?>"><?php esc_html_e('Number of events to show:', 'aqualuxe'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('count')); ?>" name="<?php echo esc_attr($this->get_field_name('count')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($count); ?>" size="3">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('category')); ?>"><?php esc_html_e('Category:', 'aqualuxe'); ?></label>
            <select id="<?php echo esc_attr($this->get_field_id('category')); ?>" name="<?php echo esc_attr($this->get_field_name('category')); ?>" class="widefat">
                <option value=""><?php esc_html_e('All Categories', 'aqualuxe'); ?></option>
                <?php foreach ($categories as $cat) : ?>
                    <option value="<?php echo esc_attr($cat->slug); ?>" <?php selected($category, $cat->slug); ?>><?php echo esc_html($cat->name); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['count'] = (!empty($new_instance['count'])) ? absint($new_instance['count']) : 5;
        $instance['category'] = (!empty($new_instance['category'])) ? sanitize_text_field($new_instance['category']) : '';
        
        return $instance;
    }
}

/**
 * Register the upcoming events widget
 */
function aqualuxe_register_upcoming_events_widget() {
    register_widget('Aqualuxe_Upcoming_Events_Widget');
}
add_action('widgets_init', 'aqualuxe_register_upcoming_events_widget');