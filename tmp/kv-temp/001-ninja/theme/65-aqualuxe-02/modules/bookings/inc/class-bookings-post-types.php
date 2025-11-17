<?php
/**
 * Bookings Post Types
 *
 * Registers post types and taxonomies for the bookings module.
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Bookings Post Types Class
 */
class AquaLuxe_Bookings_Post_Types {
    /**
     * Constructor
     */
    public function __construct() {
        // Register post types and taxonomies
        add_action('init', array($this, 'register_post_types'), 5);
        add_action('init', array($this, 'register_taxonomies'), 5);
        
        // Register custom statuses
        add_action('init', array($this, 'register_post_statuses'), 10);
        
        // Add custom columns to admin list tables
        add_filter('manage_bookable_service_posts_columns', array($this, 'bookable_service_columns'));
        add_action('manage_bookable_service_posts_custom_column', array($this, 'bookable_service_custom_column'), 10, 2);
        
        add_filter('manage_booking_posts_columns', array($this, 'booking_columns'));
        add_action('manage_booking_posts_custom_column', array($this, 'booking_custom_column'), 10, 2);
        
        // Add sortable columns
        add_filter('manage_edit-booking_sortable_columns', array($this, 'booking_sortable_columns'));
        
        // Add filters to admin list tables
        add_action('restrict_manage_posts', array($this, 'add_list_table_filters'));
        add_filter('parse_query', array($this, 'filter_bookings_by_customer'));
        
        // Set custom menu icons
        add_action('admin_head', array($this, 'menu_icons_css'));
    }

    /**
     * Register post types
     */
    public function register_post_types() {
        if (!is_blog_installed() || post_type_exists('bookable_service')) {
            return;
        }

        /**
         * Register Bookable Service post type
         */
        $labels = array(
            'name'                  => _x('Bookable Services', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Bookable Service', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Bookable Services', 'Admin Menu text', 'aqualuxe'),
            'name_admin_bar'        => _x('Bookable Service', 'Add New on Toolbar', 'aqualuxe'),
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
            'featured_image'        => _x('Service Image', 'Overrides the "Featured Image" phrase', 'aqualuxe'),
            'set_featured_image'    => _x('Set service image', 'Overrides the "Set featured image" phrase', 'aqualuxe'),
            'remove_featured_image' => _x('Remove service image', 'Overrides the "Remove featured image" phrase', 'aqualuxe'),
            'use_featured_image'    => _x('Use as service image', 'Overrides the "Use as featured image" phrase', 'aqualuxe'),
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
            'show_in_menu'       => 'aqualuxe-bookings',
            'query_var'          => true,
            'rewrite'            => array('slug' => 'bookable-service'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'show_in_rest'       => true,
            'menu_icon'          => 'dashicons-calendar-alt',
        );

        register_post_type('bookable_service', $args);

        /**
         * Register Booking post type
         */
        $labels = array(
            'name'                  => _x('Bookings', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Booking', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Bookings', 'Admin Menu text', 'aqualuxe'),
            'name_admin_bar'        => _x('Booking', 'Add New on Toolbar', 'aqualuxe'),
            'add_new'               => __('Add New', 'aqualuxe'),
            'add_new_item'          => __('Add New Booking', 'aqualuxe'),
            'new_item'              => __('New Booking', 'aqualuxe'),
            'edit_item'             => __('Edit Booking', 'aqualuxe'),
            'view_item'             => __('View Booking', 'aqualuxe'),
            'all_items'             => __('All Bookings', 'aqualuxe'),
            'search_items'          => __('Search Bookings', 'aqualuxe'),
            'parent_item_colon'     => __('Parent Bookings:', 'aqualuxe'),
            'not_found'             => __('No bookings found.', 'aqualuxe'),
            'not_found_in_trash'    => __('No bookings found in Trash.', 'aqualuxe'),
            'archives'              => _x('Booking archives', 'The post type archive label used in nav menus', 'aqualuxe'),
            'filter_items_list'     => _x('Filter bookings list', 'Screen reader text for the filter links', 'aqualuxe'),
            'items_list_navigation' => _x('Bookings list navigation', 'Screen reader text for the pagination', 'aqualuxe'),
            'items_list'            => _x('Bookings list', 'Screen reader text for the items list', 'aqualuxe'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => 'aqualuxe-bookings',
            'query_var'          => false,
            'rewrite'            => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array('title', 'custom-fields'),
            'show_in_rest'       => false,
        );

        register_post_type('booking', $args);
    }

    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        if (!is_blog_installed() || taxonomy_exists('service_category')) {
            return;
        }

        /**
         * Register Service Category taxonomy
         */
        $labels = array(
            'name'                       => _x('Service Categories', 'Taxonomy general name', 'aqualuxe'),
            'singular_name'              => _x('Service Category', 'Taxonomy singular name', 'aqualuxe'),
            'search_items'               => __('Search Service Categories', 'aqualuxe'),
            'popular_items'              => __('Popular Service Categories', 'aqualuxe'),
            'all_items'                  => __('All Service Categories', 'aqualuxe'),
            'parent_item'                => __('Parent Service Category', 'aqualuxe'),
            'parent_item_colon'          => __('Parent Service Category:', 'aqualuxe'),
            'edit_item'                  => __('Edit Service Category', 'aqualuxe'),
            'update_item'                => __('Update Service Category', 'aqualuxe'),
            'add_new_item'               => __('Add New Service Category', 'aqualuxe'),
            'new_item_name'              => __('New Service Category Name', 'aqualuxe'),
            'separate_items_with_commas' => __('Separate service categories with commas', 'aqualuxe'),
            'add_or_remove_items'        => __('Add or remove service categories', 'aqualuxe'),
            'choose_from_most_used'      => __('Choose from the most used service categories', 'aqualuxe'),
            'not_found'                  => __('No service categories found.', 'aqualuxe'),
            'menu_name'                  => __('Categories', 'aqualuxe'),
            'back_to_items'              => __('← Back to service categories', 'aqualuxe'),
        );

        $args = array(
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
            'rewrite'               => array('slug' => 'service-category'),
            'show_in_rest'          => true,
        );

        register_taxonomy('service_category', array('bookable_service'), $args);

        /**
         * Register Service Tag taxonomy
         */
        $labels = array(
            'name'                       => _x('Service Tags', 'Taxonomy general name', 'aqualuxe'),
            'singular_name'              => _x('Service Tag', 'Taxonomy singular name', 'aqualuxe'),
            'search_items'               => __('Search Service Tags', 'aqualuxe'),
            'popular_items'              => __('Popular Service Tags', 'aqualuxe'),
            'all_items'                  => __('All Service Tags', 'aqualuxe'),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => __('Edit Service Tag', 'aqualuxe'),
            'update_item'                => __('Update Service Tag', 'aqualuxe'),
            'add_new_item'               => __('Add New Service Tag', 'aqualuxe'),
            'new_item_name'              => __('New Service Tag Name', 'aqualuxe'),
            'separate_items_with_commas' => __('Separate service tags with commas', 'aqualuxe'),
            'add_or_remove_items'        => __('Add or remove service tags', 'aqualuxe'),
            'choose_from_most_used'      => __('Choose from the most used service tags', 'aqualuxe'),
            'not_found'                  => __('No service tags found.', 'aqualuxe'),
            'menu_name'                  => __('Tags', 'aqualuxe'),
            'back_to_items'              => __('← Back to service tags', 'aqualuxe'),
        );

        $args = array(
            'hierarchical'          => false,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
            'rewrite'               => array('slug' => 'service-tag'),
            'show_in_rest'          => true,
        );

        register_taxonomy('service_tag', array('bookable_service'), $args);
    }

    /**
     * Register post statuses
     */
    public function register_post_statuses() {
        register_post_status('aqualuxe-pending', array(
            'label'                     => _x('Pending', 'Booking status', 'aqualuxe'),
            'public'                    => false,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop('Pending <span class="count">(%s)</span>', 'Pending <span class="count">(%s)</span>', 'aqualuxe'),
        ));

        register_post_status('aqualuxe-confirmed', array(
            'label'                     => _x('Confirmed', 'Booking status', 'aqualuxe'),
            'public'                    => false,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop('Confirmed <span class="count">(%s)</span>', 'Confirmed <span class="count">(%s)</span>', 'aqualuxe'),
        ));

        register_post_status('aqualuxe-completed', array(
            'label'                     => _x('Completed', 'Booking status', 'aqualuxe'),
            'public'                    => false,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop('Completed <span class="count">(%s)</span>', 'Completed <span class="count">(%s)</span>', 'aqualuxe'),
        ));

        register_post_status('aqualuxe-cancelled', array(
            'label'                     => _x('Cancelled', 'Booking status', 'aqualuxe'),
            'public'                    => false,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop('Cancelled <span class="count">(%s)</span>', 'Cancelled <span class="count">(%s)</span>', 'aqualuxe'),
        ));
    }

    /**
     * Define custom columns for bookable service post type
     *
     * @param array $columns Existing columns
     * @return array Modified columns
     */
    public function bookable_service_columns($columns) {
        $columns = array(
            'cb' => $columns['cb'],
            'title' => __('Service', 'aqualuxe'),
            'service_image' => __('Image', 'aqualuxe'),
            'service_price' => __('Price', 'aqualuxe'),
            'service_duration' => __('Duration', 'aqualuxe'),
            'service_capacity' => __('Capacity', 'aqualuxe'),
            'service_category' => __('Categories', 'aqualuxe'),
            'service_tag' => __('Tags', 'aqualuxe'),
            'date' => $columns['date'],
        );

        return $columns;
    }

    /**
     * Output custom column data for bookable service post type
     *
     * @param string $column Column name
     * @param int $post_id Post ID
     */
    public function bookable_service_custom_column($column, $post_id) {
        switch ($column) {
            case 'service_image':
                if (has_post_thumbnail($post_id)) {
                    echo '<img src="' . esc_url(get_the_post_thumbnail_url($post_id, 'thumbnail')) . '" alt="" style="width:50px;height:50px;object-fit:cover;" />';
                } else {
                    echo '<div style="width:50px;height:50px;background:#f0f0f0;display:flex;align-items:center;justify-content:center;"><span class="dashicons dashicons-format-image"></span></div>';
                }
                break;

            case 'service_price':
                $price = get_post_meta($post_id, '_service_price', true);
                if (!empty($price)) {
                    if (function_exists('wc_price')) {
                        echo wc_price($price);
                    } else {
                        echo '$' . number_format($price, 2);
                    }
                } else {
                    echo '—';
                }
                break;

            case 'service_duration':
                $duration = get_post_meta($post_id, '_service_duration', true);
                if (!empty($duration)) {
                    echo esc_html($this->format_duration($duration));
                } else {
                    echo '—';
                }
                break;

            case 'service_capacity':
                $capacity = get_post_meta($post_id, '_service_capacity', true);
                if (!empty($capacity)) {
                    echo esc_html($capacity);
                } else {
                    echo '—';
                }
                break;

            case 'service_category':
                $terms = get_the_terms($post_id, 'service_category');
                if (!empty($terms) && !is_wp_error($terms)) {
                    $term_links = array();
                    foreach ($terms as $term) {
                        $term_links[] = '<a href="' . esc_url(admin_url('edit.php?post_type=bookable_service&service_category=' . $term->slug)) . '">' . esc_html($term->name) . '</a>';
                    }
                    echo implode(', ', $term_links);
                } else {
                    echo '—';
                }
                break;

            case 'service_tag':
                $terms = get_the_terms($post_id, 'service_tag');
                if (!empty($terms) && !is_wp_error($terms)) {
                    $term_links = array();
                    foreach ($terms as $term) {
                        $term_links[] = '<a href="' . esc_url(admin_url('edit.php?post_type=bookable_service&service_tag=' . $term->slug)) . '">' . esc_html($term->name) . '</a>';
                    }
                    echo implode(', ', $term_links);
                } else {
                    echo '—';
                }
                break;
        }
    }

    /**
     * Define custom columns for booking post type
     *
     * @param array $columns Existing columns
     * @return array Modified columns
     */
    public function booking_columns($columns) {
        $columns = array(
            'cb' => $columns['cb'],
            'booking_id' => __('Booking ID', 'aqualuxe'),
            'booking_service' => __('Service', 'aqualuxe'),
            'booking_customer' => __('Customer', 'aqualuxe'),
            'booking_date' => __('Booking Date', 'aqualuxe'),
            'booking_time' => __('Time', 'aqualuxe'),
            'booking_status' => __('Status', 'aqualuxe'),
            'booking_total' => __('Total', 'aqualuxe'),
        );

        return $columns;
    }

    /**
     * Output custom column data for booking post type
     *
     * @param string $column Column name
     * @param int $post_id Post ID
     */
    public function booking_custom_column($column, $post_id) {
        switch ($column) {
            case 'booking_id':
                $booking_id = get_post_meta($post_id, '_booking_id', true);
                echo '<a href="' . esc_url(admin_url('post.php?post=' . $post_id . '&action=edit')) . '">' . esc_html($booking_id) . '</a>';
                break;

            case 'booking_service':
                $service_id = get_post_meta($post_id, '_service_id', true);
                if (!empty($service_id)) {
                    echo '<a href="' . esc_url(admin_url('post.php?post=' . $service_id . '&action=edit')) . '">' . esc_html(get_the_title($service_id)) . '</a>';
                } else {
                    echo '—';
                }
                break;

            case 'booking_customer':
                $customer_id = get_post_meta($post_id, '_customer_id', true);
                $customer_name = get_post_meta($post_id, '_customer_name', true);
                $customer_email = get_post_meta($post_id, '_customer_email', true);
                
                if (!empty($customer_id) && $customer_id > 0) {
                    $user = get_user_by('id', $customer_id);
                    if ($user) {
                        echo '<a href="' . esc_url(admin_url('user-edit.php?user_id=' . $customer_id)) . '">' . esc_html($user->display_name) . '</a>';
                    } else {
                        echo esc_html($customer_name);
                    }
                } elseif (!empty($customer_name)) {
                    echo esc_html($customer_name);
                    if (!empty($customer_email)) {
                        echo '<br><a href="mailto:' . esc_attr($customer_email) . '">' . esc_html($customer_email) . '</a>';
                    }
                } else {
                    echo '—';
                }
                break;

            case 'booking_date':
                $start_date = get_post_meta($post_id, '_booking_start', true);
                if (!empty($start_date)) {
                    echo esc_html(date_i18n(get_option('date_format'), strtotime($start_date)));
                } else {
                    echo '—';
                }
                break;

            case 'booking_time':
                $start_date = get_post_meta($post_id, '_booking_start', true);
                $end_date = get_post_meta($post_id, '_booking_end', true);
                
                if (!empty($start_date) && !empty($end_date)) {
                    $time_format = get_option('time_format');
                    echo esc_html(date_i18n($time_format, strtotime($start_date))) . ' - ' . esc_html(date_i18n($time_format, strtotime($end_date)));
                } else {
                    echo '—';
                }
                break;

            case 'booking_status':
                $status = get_post_status($post_id);
                $status_label = '';
                
                switch ($status) {
                    case 'aqualuxe-pending':
                        $status_label = '<span class="booking-status booking-status-pending">' . __('Pending', 'aqualuxe') . '</span>';
                        break;
                    case 'aqualuxe-confirmed':
                        $status_label = '<span class="booking-status booking-status-confirmed">' . __('Confirmed', 'aqualuxe') . '</span>';
                        break;
                    case 'aqualuxe-completed':
                        $status_label = '<span class="booking-status booking-status-completed">' . __('Completed', 'aqualuxe') . '</span>';
                        break;
                    case 'aqualuxe-cancelled':
                        $status_label = '<span class="booking-status booking-status-cancelled">' . __('Cancelled', 'aqualuxe') . '</span>';
                        break;
                    default:
                        $status_label = '<span class="booking-status">' . ucfirst($status) . '</span>';
                        break;
                }
                
                echo $status_label;
                break;

            case 'booking_total':
                $total = get_post_meta($post_id, '_booking_total', true);
                if (!empty($total)) {
                    if (function_exists('wc_price')) {
                        echo wc_price($total);
                    } else {
                        echo '$' . number_format($total, 2);
                    }
                } else {
                    echo '—';
                }
                break;
        }
    }

    /**
     * Define sortable columns for booking post type
     *
     * @param array $columns Existing sortable columns
     * @return array Modified sortable columns
     */
    public function booking_sortable_columns($columns) {
        $columns['booking_id'] = 'booking_id';
        $columns['booking_service'] = 'booking_service';
        $columns['booking_customer'] = 'booking_customer';
        $columns['booking_date'] = 'booking_date';
        $columns['booking_status'] = 'booking_status';
        $columns['booking_total'] = 'booking_total';
        
        return $columns;
    }

    /**
     * Add filters to admin list tables
     *
     * @param string $post_type Current post type
     */
    public function add_list_table_filters($post_type) {
        if ('booking' !== $post_type) {
            return;
        }

        // Filter by service
        $service_id = isset($_GET['booking_service']) ? absint($_GET['booking_service']) : 0;
        $services = get_posts(array(
            'post_type' => 'bookable_service',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ));

        echo '<select name="booking_service">';
        echo '<option value="">' . esc_html__('All Services', 'aqualuxe') . '</option>';
        
        foreach ($services as $service) {
            echo '<option value="' . esc_attr($service->ID) . '" ' . selected($service_id, $service->ID, false) . '>' . esc_html($service->post_title) . '</option>';
        }
        
        echo '</select>';

        // Filter by status
        $status = isset($_GET['booking_status']) ? sanitize_text_field($_GET['booking_status']) : '';
        $statuses = array(
            'aqualuxe-pending' => __('Pending', 'aqualuxe'),
            'aqualuxe-confirmed' => __('Confirmed', 'aqualuxe'),
            'aqualuxe-completed' => __('Completed', 'aqualuxe'),
            'aqualuxe-cancelled' => __('Cancelled', 'aqualuxe'),
        );

        echo '<select name="booking_status">';
        echo '<option value="">' . esc_html__('All Statuses', 'aqualuxe') . '</option>';
        
        foreach ($statuses as $status_value => $status_label) {
            echo '<option value="' . esc_attr($status_value) . '" ' . selected($status, $status_value, false) . '>' . esc_html($status_label) . '</option>';
        }
        
        echo '</select>';

        // Filter by date
        $date = isset($_GET['booking_date']) ? sanitize_text_field($_GET['booking_date']) : '';
        echo '<input type="text" name="booking_date" placeholder="' . esc_attr__('Filter by date', 'aqualuxe') . '" value="' . esc_attr($date) . '" class="date-picker" />';
    }

    /**
     * Filter bookings by customer
     *
     * @param WP_Query $query Current query
     * @return WP_Query Modified query
     */
    public function filter_bookings_by_customer($query) {
        global $pagenow, $typenow;

        if ('edit.php' !== $pagenow || 'booking' !== $typenow || !$query->is_main_query()) {
            return $query;
        }

        // Filter by service
        if (!empty($_GET['booking_service'])) {
            $query->query_vars['meta_query'][] = array(
                'key' => '_service_id',
                'value' => absint($_GET['booking_service']),
            );
        }

        // Filter by status
        if (!empty($_GET['booking_status'])) {
            $query->query_vars['post_status'] = sanitize_text_field($_GET['booking_status']);
        }

        // Filter by date
        if (!empty($_GET['booking_date'])) {
            $date = sanitize_text_field($_GET['booking_date']);
            $query->query_vars['meta_query'][] = array(
                'key' => '_booking_start',
                'value' => array(
                    date('Y-m-d 00:00:00', strtotime($date)),
                    date('Y-m-d 23:59:59', strtotime($date)),
                ),
                'compare' => 'BETWEEN',
                'type' => 'DATETIME',
            );
        }

        return $query;
    }

    /**
     * Add custom menu icons CSS
     */
    public function menu_icons_css() {
        echo '<style>
            #adminmenu .menu-icon-booking div.wp-menu-image:before {
                content: "\f508";
            }
            .booking-status {
                display: inline-block;
                padding: 3px 8px;
                border-radius: 3px;
                font-size: 12px;
                font-weight: 600;
            }
            .booking-status-pending {
                background-color: #f8dda7;
                color: #94660c;
            }
            .booking-status-confirmed {
                background-color: #c6e1c6;
                color: #5b841b;
            }
            .booking-status-completed {
                background-color: #c8d7e1;
                color: #2e4453;
            }
            .booking-status-cancelled {
                background-color: #eba3a3;
                color: #761919;
            }
        </style>';
    }

    /**
     * Format duration in minutes to human-readable format
     *
     * @param int $minutes Duration in minutes
     * @return string Formatted duration
     */
    private function format_duration($minutes) {
        if ($minutes < 60) {
            return sprintf(_n('%d minute', '%d minutes', $minutes, 'aqualuxe'), $minutes);
        } else {
            $hours = floor($minutes / 60);
            $mins = $minutes % 60;
            
            if ($mins === 0) {
                return sprintf(_n('%d hour', '%d hours', $hours, 'aqualuxe'), $hours);
            } else {
                return sprintf(__('%d hour %d minutes', 'aqualuxe'), $hours, $mins);
            }
        }
    }
}