<?php
/**
 * Services Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Services;

use AquaLuxe\Core\Abstracts\Abstract_Module;

defined('ABSPATH') || exit;

/**
 * Services Module Class
 */
class Module extends Abstract_Module {

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Services';

    /**
     * Instance
     *
     * @var Module
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Module
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize module
     */
    public function init() {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_ajax_aqualuxe_book_service', array($this, 'handle_booking'));
        add_action('wp_ajax_nopriv_aqualuxe_book_service', array($this, 'handle_booking'));
        add_shortcode('aqualuxe_services', array($this, 'services_shortcode'));
        add_shortcode('aqualuxe_service_booking', array($this, 'booking_form_shortcode'));
    }

    /**
     * Register services post type
     */
    public function register_post_type() {
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
            'featured_image'        => _x('Service Image', 'Overrides the "Featured Image" phrase', 'aqualuxe'),
            'set_featured_image'    => _x('Set service image', 'Overrides the "Set featured image" phrase', 'aqualuxe'),
            'remove_featured_image' => _x('Remove service image', 'Overrides the "Remove featured image" phrase', 'aqualuxe'),
            'use_featured_image'    => _x('Use as service image', 'Overrides the "Use as featured image" phrase', 'aqualuxe'),
            'archives'              => _x('Service archives', 'The post type archive label', 'aqualuxe'),
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
            'show_in_rest'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'services'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-hammer',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
        );

        register_post_type('aqualuxe_service', $args);
    }

    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Service Categories
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
            'menu_name'         => __('Service Categories', 'aqualuxe'),
        );

        $category_args = array(
            'hierarchical'      => true,
            'labels'            => $category_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'service-category'),
        );

        register_taxonomy('aqualuxe_service_category', array('aqualuxe_service'), $category_args);

        // Service Tags
        $tag_labels = array(
            'name'                       => _x('Service Tags', 'taxonomy general name', 'aqualuxe'),
            'singular_name'              => _x('Service Tag', 'taxonomy singular name', 'aqualuxe'),
            'search_items'               => __('Search Service Tags', 'aqualuxe'),
            'popular_items'              => __('Popular Service Tags', 'aqualuxe'),
            'all_items'                  => __('All Service Tags', 'aqualuxe'),
            'edit_item'                  => __('Edit Service Tag', 'aqualuxe'),
            'update_item'                => __('Update Service Tag', 'aqualuxe'),
            'add_new_item'               => __('Add New Service Tag', 'aqualuxe'),
            'new_item_name'              => __('New Service Tag Name', 'aqualuxe'),
            'separate_items_with_commas' => __('Separate service tags with commas', 'aqualuxe'),
            'add_or_remove_items'        => __('Add or remove service tags', 'aqualuxe'),
            'choose_from_most_used'      => __('Choose from the most used service tags', 'aqualuxe'),
            'not_found'                  => __('No service tags found.', 'aqualuxe'),
            'menu_name'                  => __('Service Tags', 'aqualuxe'),
        );

        $tag_args = array(
            'hierarchical'          => false,
            'labels'                => $tag_labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'show_in_rest'          => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array('slug' => 'service-tag'),
        );

        register_taxonomy('aqualuxe_service_tag', array('aqualuxe_service'), $tag_args);
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'aqualuxe_service_details',
            __('Service Details', 'aqualuxe'),
            array($this, 'service_details_meta_box'),
            'aqualuxe_service',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe_service_pricing',
            __('Service Pricing', 'aqualuxe'),
            array($this, 'service_pricing_meta_box'),
            'aqualuxe_service',
            'side',
            'high'
        );
    }

    /**
     * Service details meta box
     *
     * @param WP_Post $post Post object
     */
    public function service_details_meta_box($post) {
        wp_nonce_field('aqualuxe_service_details_nonce', 'aqualuxe_service_details_nonce_field');

        $duration = get_post_meta($post->ID, '_aqualuxe_service_duration', true);
        $requirements = get_post_meta($post->ID, '_aqualuxe_service_requirements', true);
        $includes = get_post_meta($post->ID, '_aqualuxe_service_includes', true);
        $location = get_post_meta($post->ID, '_aqualuxe_service_location', true);
        $booking_enabled = get_post_meta($post->ID, '_aqualuxe_service_booking_enabled', true);
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="aqualuxe_service_duration"><?php _e('Duration', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="text" id="aqualuxe_service_duration" name="aqualuxe_service_duration" value="<?php echo esc_attr($duration); ?>" class="regular-text" />
                    <p class="description"><?php _e('e.g., "2 hours", "Half day", "Full day"', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="aqualuxe_service_requirements"><?php _e('Requirements', 'aqualuxe'); ?></label></th>
                <td>
                    <textarea id="aqualuxe_service_requirements" name="aqualuxe_service_requirements" rows="4" class="large-text"><?php echo esc_textarea($requirements); ?></textarea>
                    <p class="description"><?php _e('What the client needs to prepare or bring', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="aqualuxe_service_includes"><?php _e('What\'s Included', 'aqualuxe'); ?></label></th>
                <td>
                    <textarea id="aqualuxe_service_includes" name="aqualuxe_service_includes" rows="4" class="large-text"><?php echo esc_textarea($includes); ?></textarea>
                    <p class="description"><?php _e('What services, materials, or equipment are included', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="aqualuxe_service_location"><?php _e('Service Location', 'aqualuxe'); ?></label></th>
                <td>
                    <select id="aqualuxe_service_location" name="aqualuxe_service_location">
                        <option value="on_site" <?php selected($location, 'on_site'); ?>><?php _e('On-site (at client location)', 'aqualuxe'); ?></option>
                        <option value="in_store" <?php selected($location, 'in_store'); ?>><?php _e('In-store', 'aqualuxe'); ?></option>
                        <option value="both" <?php selected($location, 'both'); ?>><?php _e('Both options available', 'aqualuxe'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="aqualuxe_service_booking_enabled"><?php _e('Enable Online Booking', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="checkbox" id="aqualuxe_service_booking_enabled" name="aqualuxe_service_booking_enabled" value="1" <?php checked($booking_enabled, '1'); ?> />
                    <p class="description"><?php _e('Allow customers to book this service online', 'aqualuxe'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Service pricing meta box
     *
     * @param WP_Post $post Post object
     */
    public function service_pricing_meta_box($post) {
        wp_nonce_field('aqualuxe_service_pricing_nonce', 'aqualuxe_service_pricing_nonce_field');

        $price = get_post_meta($post->ID, '_aqualuxe_service_price', true);
        $price_type = get_post_meta($post->ID, '_aqualuxe_service_price_type', true);
        $price_note = get_post_meta($post->ID, '_aqualuxe_service_price_note', true);
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="aqualuxe_service_price"><?php _e('Price', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="number" id="aqualuxe_service_price" name="aqualuxe_service_price" value="<?php echo esc_attr($price); ?>" step="0.01" min="0" />
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="aqualuxe_service_price_type"><?php _e('Price Type', 'aqualuxe'); ?></label></th>
                <td>
                    <select id="aqualuxe_service_price_type" name="aqualuxe_service_price_type">
                        <option value="fixed" <?php selected($price_type, 'fixed'); ?>><?php _e('Fixed Price', 'aqualuxe'); ?></option>
                        <option value="hourly" <?php selected($price_type, 'hourly'); ?>><?php _e('Per Hour', 'aqualuxe'); ?></option>
                        <option value="consultation" <?php selected($price_type, 'consultation'); ?>><?php _e('Consultation Required', 'aqualuxe'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="aqualuxe_service_price_note"><?php _e('Price Note', 'aqualuxe'); ?></label></th>
                <td>
                    <textarea id="aqualuxe_service_price_note" name="aqualuxe_service_price_note" rows="3" class="widefat"><?php echo esc_textarea($price_note); ?></textarea>
                    <p class="description"><?php _e('Additional pricing information or conditions', 'aqualuxe'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save meta boxes
     *
     * @param int $post_id Post ID
     */
    public function save_meta_boxes($post_id) {
        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Verify nonces
        if (!isset($_POST['aqualuxe_service_details_nonce_field']) ||
            !wp_verify_nonce($_POST['aqualuxe_service_details_nonce_field'], 'aqualuxe_service_details_nonce')) {
            return;
        }

        if (!isset($_POST['aqualuxe_service_pricing_nonce_field']) ||
            !wp_verify_nonce($_POST['aqualuxe_service_pricing_nonce_field'], 'aqualuxe_service_pricing_nonce')) {
            return;
        }

        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save service details
        $fields = array(
            'aqualuxe_service_duration' => '_aqualuxe_service_duration',
            'aqualuxe_service_requirements' => '_aqualuxe_service_requirements',
            'aqualuxe_service_includes' => '_aqualuxe_service_includes',
            'aqualuxe_service_location' => '_aqualuxe_service_location',
            'aqualuxe_service_booking_enabled' => '_aqualuxe_service_booking_enabled',
            'aqualuxe_service_price' => '_aqualuxe_service_price',
            'aqualuxe_service_price_type' => '_aqualuxe_service_price_type',
            'aqualuxe_service_price_note' => '_aqualuxe_service_price_note',
        );

        foreach ($fields as $field => $meta_key) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
            }
        }
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        if (is_singular('aqualuxe_service') || is_post_type_archive('aqualuxe_service')) {
            wp_enqueue_style('aqualuxe-services', $this->get_url() . '/assets/services.css', array(), '1.0.0');
            wp_enqueue_script('aqualuxe-services', $this->get_url() . '/assets/services.js', array('jquery'), '1.0.0', true);

            wp_localize_script('aqualuxe-services', 'aqualuxe_services', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_services_nonce'),
            ));
        }
    }

    /**
     * Handle service booking
     */
    public function handle_booking() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_services_nonce')) {
            wp_die('Security check failed');
        }

        $service_id = intval($_POST['service_id']);
        $customer_name = sanitize_text_field($_POST['customer_name']);
        $customer_email = sanitize_email($_POST['customer_email']);
        $customer_phone = sanitize_text_field($_POST['customer_phone']);
        $preferred_date = sanitize_text_field($_POST['preferred_date']);
        $preferred_time = sanitize_text_field($_POST['preferred_time']);
        $message = sanitize_textarea_field($_POST['message']);

        // Validate required fields
        if (empty($service_id) || empty($customer_name) || empty($customer_email)) {
            wp_send_json_error('Required fields missing');
        }

        // Create booking entry
        $booking_data = array(
            'post_title' => sprintf(__('Service Booking: %s', 'aqualuxe'), get_the_title($service_id)),
            'post_type' => 'aqualuxe_booking',
            'post_status' => 'publish',
            'meta_input' => array(
                '_booking_service_id' => $service_id,
                '_booking_customer_name' => $customer_name,
                '_booking_customer_email' => $customer_email,
                '_booking_customer_phone' => $customer_phone,
                '_booking_preferred_date' => $preferred_date,
                '_booking_preferred_time' => $preferred_time,
                '_booking_message' => $message,
                '_booking_status' => 'pending',
                '_booking_type' => 'service',
            ),
        );

        $booking_id = wp_insert_post($booking_data);

        if ($booking_id) {
            // Send confirmation emails
            $this->send_booking_emails($booking_id);

            wp_send_json_success(array(
                'message' => __('Booking request submitted successfully! We will contact you soon.', 'aqualuxe'),
                'booking_id' => $booking_id,
            ));
        } else {
            wp_send_json_error('Failed to create booking');
        }
    }

    /**
     * Send booking confirmation emails
     *
     * @param int $booking_id Booking ID
     */
    private function send_booking_emails($booking_id) {
        $service_id = get_post_meta($booking_id, '_booking_service_id', true);
        $customer_email = get_post_meta($booking_id, '_booking_customer_email', true);
        $customer_name = get_post_meta($booking_id, '_booking_customer_name', true);

        // Email to customer
        $subject = sprintf(__('Service Booking Confirmation - %s', 'aqualuxe'), get_bloginfo('name'));
        $message = sprintf(
            __('Dear %s,\n\nThank you for your service booking request for "%s".\n\nWe will review your request and contact you within 24 hours to confirm the appointment.\n\nBest regards,\n%s Team', 'aqualuxe'),
            $customer_name,
            get_the_title($service_id),
            get_bloginfo('name')
        );

        wp_mail($customer_email, $subject, $message);

        // Email to admin
        $admin_email = get_option('admin_email');
        $admin_subject = sprintf(__('New Service Booking Request - %s', 'aqualuxe'), get_the_title($service_id));
        $admin_message = sprintf(
            __('New service booking request received:\n\nService: %s\nCustomer: %s\nEmail: %s\nPhone: %s\nPreferred Date: %s\nPreferred Time: %s\n\nPlease log in to the admin area to review and confirm this booking.', 'aqualuxe'),
            get_the_title($service_id),
            $customer_name,
            $customer_email,
            get_post_meta($booking_id, '_booking_customer_phone', true),
            get_post_meta($booking_id, '_booking_preferred_date', true),
            get_post_meta($booking_id, '_booking_preferred_time', true)
        );

        wp_mail($admin_email, $admin_subject, $admin_message);
    }

    /**
     * Services listing shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string
     */
    public function services_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 6,
            'category' => '',
            'columns' => 3,
        ), $atts);

        $args = array(
            'post_type' => 'aqualuxe_service',
            'posts_per_page' => intval($atts['limit']),
            'post_status' => 'publish',
        );

        if (!empty($atts['category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'aqualuxe_service_category',
                    'field' => 'slug',
                    'terms' => $atts['category'],
                ),
            );
        }

        $services = new \WP_Query($args);

        if (!$services->have_posts()) {
            return '<p>' . __('No services found.', 'aqualuxe') . '</p>';
        }

        $columns_class = 'grid-cols-' . intval($atts['columns']);

        ob_start();
        ?>
        <div class="aqualuxe-services-grid grid <?php echo esc_attr($columns_class); ?> gap-6">
            <?php while ($services->have_posts()) : $services->the_post(); ?>
                <div class="aqualuxe-service-card bg-white rounded-lg shadow-md overflow-hidden">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="service-image">
                            <?php the_post_thumbnail('medium', array('class' => 'w-full h-48 object-cover')); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="service-content p-6">
                        <h3 class="service-title text-xl font-semibold mb-3">
                            <a href="<?php the_permalink(); ?>" class="text-gray-900 hover:text-primary-600">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <div class="service-excerpt text-gray-600 mb-4">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <div class="service-meta flex justify-between items-center">
                            <div class="service-price">
                                <?php
                                $price = get_post_meta(get_the_ID(), '_aqualuxe_service_price', true);
                                $price_type = get_post_meta(get_the_ID(), '_aqualuxe_service_price_type', true);
                                
                                if ($price && $price_type !== 'consultation') {
                                    echo '<span class="text-primary-600 font-semibold">$' . esc_html($price);
                                    if ($price_type === 'hourly') {
                                        echo '/hr';
                                    }
                                    echo '</span>';
                                } else {
                                    echo '<span class="text-gray-500">' . __('Contact for pricing', 'aqualuxe') . '</span>';
                                }
                                ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm">
                                <?php _e('Learn More', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <?php
        wp_reset_postdata();
        
        return ob_get_clean();
    }

    /**
     * Service booking form shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string
     */
    public function booking_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'service_id' => get_the_ID(),
        ), $atts);

        $service_id = intval($atts['service_id']);
        
        if (!$service_id || get_post_type($service_id) !== 'aqualuxe_service') {
            return '<p>' . __('Invalid service.', 'aqualuxe') . '</p>';
        }

        $booking_enabled = get_post_meta($service_id, '_aqualuxe_service_booking_enabled', true);
        
        if (!$booking_enabled) {
            return '<p>' . __('Online booking is not available for this service. Please contact us directly.', 'aqualuxe') . '</p>';
        }

        ob_start();
        ?>
        <form id="aqualuxe-service-booking-form" class="space-y-4">
            <?php wp_nonce_field('aqualuxe_services_nonce', 'booking_nonce'); ?>
            <input type="hidden" name="service_id" value="<?php echo esc_attr($service_id); ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700">
                        <?php _e('Full Name', 'aqualuxe'); ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="customer_name" name="customer_name" required 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                </div>
                
                <div>
                    <label for="customer_email" class="block text-sm font-medium text-gray-700">
                        <?php _e('Email Address', 'aqualuxe'); ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="customer_email" name="customer_email" required 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                </div>
            </div>
            
            <div>
                <label for="customer_phone" class="block text-sm font-medium text-gray-700">
                    <?php _e('Phone Number', 'aqualuxe'); ?>
                </label>
                <input type="tel" id="customer_phone" name="customer_phone" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="preferred_date" class="block text-sm font-medium text-gray-700">
                        <?php _e('Preferred Date', 'aqualuxe'); ?>
                    </label>
                    <input type="date" id="preferred_date" name="preferred_date" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                </div>
                
                <div>
                    <label for="preferred_time" class="block text-sm font-medium text-gray-700">
                        <?php _e('Preferred Time', 'aqualuxe'); ?>
                    </label>
                    <select id="preferred_time" name="preferred_time" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value=""><?php _e('Select time', 'aqualuxe'); ?></option>
                        <option value="morning"><?php _e('Morning (9 AM - 12 PM)', 'aqualuxe'); ?></option>
                        <option value="afternoon"><?php _e('Afternoon (12 PM - 5 PM)', 'aqualuxe'); ?></option>
                        <option value="evening"><?php _e('Evening (5 PM - 8 PM)', 'aqualuxe'); ?></option>
                    </select>
                </div>
            </div>
            
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700">
                    <?php _e('Additional Information', 'aqualuxe'); ?>
                </label>
                <textarea id="message" name="message" rows="4" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" 
                          placeholder="<?php _e('Please provide any additional details about your requirements...', 'aqualuxe'); ?>"></textarea>
            </div>
            
            <div>
                <button type="submit" class="w-full btn btn-primary">
                    <?php _e('Submit Booking Request', 'aqualuxe'); ?>
                </button>
            </div>
        </form>
        
        <div id="booking-response" class="mt-4 hidden"></div>
        <?php
        
        return ob_get_clean();
    }
}