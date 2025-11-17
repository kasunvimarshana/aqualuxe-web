<?php
/**
 * Services Module
 * 
 * Handles service management and booking functionality
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Services_Module {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', [$this, 'init']);
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_aqualuxe_book_service', [$this, 'ajax_book_service']);
        add_action('wp_ajax_nopriv_aqualuxe_book_service', [$this, 'ajax_book_service']);
        add_shortcode('aqualuxe_services', [$this, 'services_shortcode']);
        add_shortcode('aqualuxe_service_booking', [$this, 'booking_form_shortcode']);
    }
    
    /**
     * Initialize module
     */
    public function init() {
        $this->register_post_types();
        $this->register_taxonomies();
    }
    
    /**
     * Register custom post types
     */
    private function register_post_types() {
        // Service bookings
        register_post_type('aqualuxe_booking', [
            'labels' => [
                'name' => esc_html__('Service Bookings', 'aqualuxe'),
                'singular_name' => esc_html__('Booking', 'aqualuxe'),
                'add_new' => esc_html__('Add New Booking', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Booking', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Booking', 'aqualuxe'),
                'new_item' => esc_html__('New Booking', 'aqualuxe'),
                'view_item' => esc_html__('View Booking', 'aqualuxe'),
                'search_items' => esc_html__('Search Bookings', 'aqualuxe'),
                'not_found' => esc_html__('No bookings found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No bookings found in trash', 'aqualuxe'),
            ],
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => 'aqualuxe-services',
            'show_in_rest' => false,
            'query_var' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_icon' => 'dashicons-calendar-alt',
            'supports' => ['title', 'custom-fields'],
            'map_meta_cap' => true
        ]);
    }
    
    /**
     * Register taxonomies
     */
    private function register_taxonomies() {
        // Service types
        register_taxonomy('service_type', 'aqualuxe_service', [
            'labels' => [
                'name' => esc_html__('Service Types', 'aqualuxe'),
                'singular_name' => esc_html__('Service Type', 'aqualuxe'),
                'search_items' => esc_html__('Search Service Types', 'aqualuxe'),
                'all_items' => esc_html__('All Service Types', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Service Type', 'aqualuxe'),
                'update_item' => esc_html__('Update Service Type', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Service Type', 'aqualuxe'),
                'new_item_name' => esc_html__('New Service Type Name', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'service-type'],
        ]);
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            esc_html__('AquaLuxe Services', 'aqualuxe'),
            esc_html__('Services', 'aqualuxe'),
            'manage_options',
            'aqualuxe-services',
            [$this, 'admin_dashboard'],
            'dashicons-admin-tools',
            25
        );
        
        add_submenu_page(
            'aqualuxe-services',
            esc_html__('All Services', 'aqualuxe'),
            esc_html__('All Services', 'aqualuxe'),
            'manage_options',
            'edit.php?post_type=aqualuxe_service'
        );
        
        add_submenu_page(
            'aqualuxe-services',
            esc_html__('Service Categories', 'aqualuxe'),
            esc_html__('Categories', 'aqualuxe'),
            'manage_options',
            'edit-tags.php?taxonomy=service_category&post_type=aqualuxe_service'
        );
        
        add_submenu_page(
            'aqualuxe-services',
            esc_html__('Service Types', 'aqualuxe'),
            esc_html__('Service Types', 'aqualuxe'),
            'manage_options',
            'edit-tags.php?taxonomy=service_type&post_type=aqualuxe_service'
        );
    }
    
    /**
     * Admin dashboard
     */
    public function admin_dashboard() {
        $recent_bookings = $this->get_recent_bookings(10);
        $booking_stats = $this->get_booking_stats();
        
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Services Dashboard', 'aqualuxe'); ?></h1>
            
            <div class="dashboard-widgets-wrap">
                <div class="postbox-container" style="width: 50%;">
                    <div class="postbox">
                        <h2 class="hndle"><?php esc_html_e('Booking Statistics', 'aqualuxe'); ?></h2>
                        <div class="inside">
                            <table class="wp-list-table widefat fixed striped">
                                <tbody>
                                    <tr>
                                        <td><?php esc_html_e('Total Bookings', 'aqualuxe'); ?></td>
                                        <td><strong><?php echo esc_html($booking_stats['total']); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td><?php esc_html_e('Pending Bookings', 'aqualuxe'); ?></td>
                                        <td><strong><?php echo esc_html($booking_stats['pending']); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td><?php esc_html_e('Confirmed Bookings', 'aqualuxe'); ?></td>
                                        <td><strong><?php echo esc_html($booking_stats['confirmed']); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td><?php esc_html_e('Completed Bookings', 'aqualuxe'); ?></td>
                                        <td><strong><?php echo esc_html($booking_stats['completed']); ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="postbox-container" style="width: 50%;">
                    <div class="postbox">
                        <h2 class="hndle"><?php esc_html_e('Recent Bookings', 'aqualuxe'); ?></h2>
                        <div class="inside">
                            <?php if (!empty($recent_bookings)) : ?>
                                <table class="wp-list-table widefat fixed striped">
                                    <thead>
                                        <tr>
                                            <th><?php esc_html_e('Customer', 'aqualuxe'); ?></th>
                                            <th><?php esc_html_e('Service', 'aqualuxe'); ?></th>
                                            <th><?php esc_html_e('Date', 'aqualuxe'); ?></th>
                                            <th><?php esc_html_e('Status', 'aqualuxe'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_bookings as $booking) : ?>
                                            <tr>
                                                <td><?php echo esc_html($booking['customer_name']); ?></td>
                                                <td><?php echo esc_html($booking['service_name']); ?></td>
                                                <td><?php echo esc_html($booking['booking_date']); ?></td>
                                                <td>
                                                    <span class="booking-status status-<?php echo esc_attr($booking['status']); ?>">
                                                        <?php echo esc_html(ucfirst($booking['status'])); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <p><?php esc_html_e('No recent bookings found.', 'aqualuxe'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        if (is_singular('aqualuxe_service') || is_post_type_archive('aqualuxe_service')) {
            wp_enqueue_style(
                'aqualuxe-services',
                AQUALUXE_ASSETS_URI . '/css/modules/services.css',
                ['aqualuxe-style'],
                AQUALUXE_VERSION
            );
            
            wp_enqueue_script(
                'aqualuxe-services',
                AQUALUXE_ASSETS_URI . '/js/modules/services.js',
                ['jquery'],
                AQUALUXE_VERSION,
                true
            );
            
            wp_localize_script('aqualuxe-services', 'aqualuxe_services_vars', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_services_nonce'),
                'strings' => [
                    'booking_success' => esc_html__('Your booking has been submitted successfully!', 'aqualuxe'),
                    'booking_error' => esc_html__('There was an error submitting your booking. Please try again.', 'aqualuxe'),
                    'required_fields' => esc_html__('Please fill in all required fields.', 'aqualuxe'),
                ]
            ]);
        }
    }
    
    /**
     * Services shortcode
     */
    public function services_shortcode($atts) {
        $atts = shortcode_atts([
            'limit' => 6,
            'category' => '',
            'type' => '',
            'columns' => 3,
            'show_excerpt' => true,
            'show_price' => true,
            'show_booking' => true
        ], $atts, 'aqualuxe_services');
        
        $args = [
            'post_type' => 'aqualuxe_service',
            'posts_per_page' => intval($atts['limit']),
            'post_status' => 'publish'
        ];
        
        if ($atts['category']) {
            $args['tax_query'][] = [
                'taxonomy' => 'service_category',
                'field' => 'slug',
                'terms' => explode(',', $atts['category'])
            ];
        }
        
        if ($atts['type']) {
            $args['tax_query'][] = [
                'taxonomy' => 'service_type',
                'field' => 'slug',
                'terms' => explode(',', $atts['type'])
            ];
        }
        
        $services = new WP_Query($args);
        
        if (!$services->have_posts()) {
            return '<p>' . esc_html__('No services found.', 'aqualuxe') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="aqualuxe-services-grid grid grid-cols-1 md:grid-cols-<?php echo esc_attr($atts['columns']); ?> gap-8">
            <?php while ($services->have_posts()) : $services->the_post(); ?>
                <div class="service-card bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="service-image">
                            <?php the_post_thumbnail('aqualuxe-featured', ['class' => 'w-full h-48 object-cover']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="service-content p-6">
                        <h3 class="service-title text-xl font-semibold text-gray-900 dark:text-white mb-3">
                            <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 transition-colors">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <?php if ($atts['show_excerpt'] && has_excerpt()) : ?>
                            <div class="service-excerpt text-gray-600 dark:text-gray-400 mb-4">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($atts['show_price']) : ?>
                            <?php $price = get_post_meta(get_the_ID(), 'service_price', true); ?>
                            <?php if ($price) : ?>
                                <div class="service-price text-lg font-semibold text-primary-600 mb-4">
                                    <?php echo esc_html($price); ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <div class="service-actions flex gap-3">
                            <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary flex-1 text-center">
                                <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                            </a>
                            
                            <?php if ($atts['show_booking']) : ?>
                                <button class="btn btn-primary book-service-btn" 
                                        data-service-id="<?php echo get_the_ID(); ?>" 
                                        data-service-title="<?php echo esc_attr(get_the_title()); ?>">
                                    <?php esc_html_e('Book Now', 'aqualuxe'); ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        
        <?php if ($atts['show_booking']) : ?>
            <?php echo $this->get_booking_modal(); ?>
        <?php endif; ?>
        
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }
    
    /**
     * Booking form shortcode
     */
    public function booking_form_shortcode($atts) {
        $atts = shortcode_atts([
            'service_id' => '',
            'title' => 'Book a Service'
        ], $atts, 'aqualuxe_service_booking');
        
        ob_start();
        ?>
        <div class="service-booking-form">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                <?php echo esc_html($atts['title']); ?>
            </h3>
            
            <form class="booking-form space-y-6" method="post">
                <?php wp_nonce_field('aqualuxe_service_booking', 'booking_nonce'); ?>
                
                <?php if ($atts['service_id']) : ?>
                    <input type="hidden" name="service_id" value="<?php echo esc_attr($atts['service_id']); ?>">
                <?php else : ?>
                    <div class="form-group">
                        <label for="service_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Select Service *
                        </label>
                        <select id="service_id" name="service_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                            <option value="">Choose a service...</option>
                            <?php
                            $services = get_posts([
                                'post_type' => 'aqualuxe_service',
                                'posts_per_page' => -1,
                                'post_status' => 'publish'
                            ]);
                            foreach ($services as $service) :
                            ?>
                                <option value="<?php echo esc_attr($service->ID); ?>">
                                    <?php echo esc_html($service->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="customer_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Full Name *
                        </label>
                        <input type="text" id="customer_name" name="customer_name" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                    </div>
                    
                    <div class="form-group">
                        <label for="customer_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email Address *
                        </label>
                        <input type="email" id="customer_email" name="customer_email" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="customer_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Phone Number *
                        </label>
                        <input type="tel" id="customer_phone" name="customer_phone" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                    </div>
                    
                    <div class="form-group">
                        <label for="preferred_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Preferred Date *
                        </label>
                        <input type="date" id="preferred_date" name="preferred_date" required 
                               min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Additional Notes
                    </label>
                    <textarea id="message" name="message" rows="4" 
                              placeholder="Please provide any additional details about your requirements..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"></textarea>
                </div>
                
                <button type="submit" name="submit_booking" class="btn btn-primary w-full justify-center">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Submit Booking Request
                </button>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Get booking modal HTML
     */
    private function get_booking_modal() {
        ob_start();
        ?>
        <div id="booking-modal" class="modal hidden fixed inset-0 z-50 overflow-y-auto">
            <div class="modal-overlay absolute inset-0 bg-black bg-opacity-50"></div>
            <div class="modal-container relative bg-white dark:bg-gray-800 mx-auto my-8 max-w-2xl rounded-lg shadow-xl">
                <div class="modal-header flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="modal-title text-xl font-semibold text-gray-900 dark:text-white">
                        Book Service
                    </h3>
                    <button class="modal-close text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body p-6">
                    <?php echo $this->booking_form_shortcode(['title' => '']); ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * AJAX book service
     */
    public function ajax_book_service() {
        check_ajax_referer('aqualuxe_services_nonce', 'nonce');
        
        $service_id = intval($_POST['service_id']);
        $customer_name = sanitize_text_field($_POST['customer_name']);
        $customer_email = sanitize_email($_POST['customer_email']);
        $customer_phone = sanitize_text_field($_POST['customer_phone']);
        $preferred_date = sanitize_text_field($_POST['preferred_date']);
        $message = sanitize_textarea_field($_POST['message']);
        
        // Validate required fields
        if (!$service_id || !$customer_name || !$customer_email || !$customer_phone || !$preferred_date) {
            wp_send_json_error('Required fields are missing');
        }
        
        // Create booking
        $booking_id = wp_insert_post([
            'post_type' => 'aqualuxe_booking',
            'post_title' => sprintf('Booking for %s - %s', get_the_title($service_id), $customer_name),
            'post_status' => 'publish',
            'meta_input' => [
                'service_id' => $service_id,
                'customer_name' => $customer_name,
                'customer_email' => $customer_email,
                'customer_phone' => $customer_phone,
                'preferred_date' => $preferred_date,
                'message' => $message,
                'booking_status' => 'pending',
                'booking_date' => current_time('mysql')
            ]
        ]);
        
        if ($booking_id && !is_wp_error($booking_id)) {
            // Send confirmation email
            $this->send_booking_confirmation($booking_id);
            wp_send_json_success(['message' => 'Booking submitted successfully!']);
        } else {
            wp_send_json_error('Failed to create booking');
        }
    }
    
    /**
     * Send booking confirmation email
     */
    private function send_booking_confirmation($booking_id) {
        $booking = get_post($booking_id);
        $customer_email = get_post_meta($booking_id, 'customer_email', true);
        $customer_name = get_post_meta($booking_id, 'customer_name', true);
        $service_id = get_post_meta($booking_id, 'service_id', true);
        $service_title = get_the_title($service_id);
        
        $subject = 'Booking Confirmation - ' . $service_title;
        $message = "
            Dear {$customer_name},
            
            Thank you for booking our {$service_title} service.
            
            We have received your booking request and will contact you within 24 hours to confirm the details.
            
            Booking Reference: #{$booking_id}
            
            Best regards,
            AquaLuxe Team
        ";
        
        wp_mail($customer_email, $subject, $message);
        
        // Send notification to admin
        $admin_email = get_option('admin_email');
        $admin_subject = 'New Service Booking - ' . $service_title;
        $admin_message = "
            New service booking received:
            
            Service: {$service_title}
            Customer: {$customer_name}
            Email: {$customer_email}
            Booking ID: {$booking_id}
            
            Please review and confirm the booking in the admin panel.
        ";
        
        wp_mail($admin_email, $admin_subject, $admin_message);
    }
    
    /**
     * Get recent bookings
     */
    private function get_recent_bookings($limit = 10) {
        $bookings = get_posts([
            'post_type' => 'aqualuxe_booking',
            'posts_per_page' => $limit,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC'
        ]);
        
        $result = [];
        foreach ($bookings as $booking) {
            $service_id = get_post_meta($booking->ID, 'service_id', true);
            $result[] = [
                'id' => $booking->ID,
                'customer_name' => get_post_meta($booking->ID, 'customer_name', true),
                'service_name' => get_the_title($service_id),
                'booking_date' => get_post_meta($booking->ID, 'preferred_date', true),
                'status' => get_post_meta($booking->ID, 'booking_status', true)
            ];
        }
        
        return $result;
    }
    
    /**
     * Get booking statistics
     */
    private function get_booking_stats() {
        global $wpdb;
        
        $total = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(*) FROM {$wpdb->posts} 
            WHERE post_type = %s AND post_status = 'publish'
        ", 'aqualuxe_booking'));
        
        $pending = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(*) FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE p.post_type = %s AND p.post_status = 'publish'
            AND pm.meta_key = 'booking_status' AND pm.meta_value = 'pending'
        ", 'aqualuxe_booking'));
        
        $confirmed = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(*) FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE p.post_type = %s AND p.post_status = 'publish'
            AND pm.meta_key = 'booking_status' AND pm.meta_value = 'confirmed'
        ", 'aqualuxe_booking'));
        
        $completed = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(*) FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE p.post_type = %s AND p.post_status = 'publish'
            AND pm.meta_key = 'booking_status' AND pm.meta_value = 'completed'
        ", 'aqualuxe_booking'));
        
        return [
            'total' => (int) $total,
            'pending' => (int) $pending,
            'confirmed' => (int) $confirmed,
            'completed' => (int) $completed
        ];
    }
}