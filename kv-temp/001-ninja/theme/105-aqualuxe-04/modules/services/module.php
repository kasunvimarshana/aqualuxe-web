<?php
/**
 * Services Module
 *
 * Handles professional services functionality
 *
 * @package AquaLuxe\Modules\Services
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class AquaLuxe_Services_Module
 *
 * Manages professional services
 */
class AquaLuxe_Services_Module {
    
    /**
     * Single instance of the class
     *
     * @var AquaLuxe_Services_Module
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return AquaLuxe_Services_Module
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_aqualuxe_service_inquiry', array($this, 'handle_service_inquiry'));
        add_action('wp_ajax_nopriv_aqualuxe_service_inquiry', array($this, 'handle_service_inquiry'));
        add_filter('template_include', array($this, 'template_include'));
        add_shortcode('aqualuxe_services', array($this, 'services_shortcode'));
        add_shortcode('aqualuxe_service_form', array($this, 'service_form_shortcode'));
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
            'featured_image'        => _x('Service Featured Image', 'Overrides the "Featured Image" phrase', 'aqualuxe'),
            'set_featured_image'    => _x('Set featured image', 'Overrides the "Set featured image" phrase', 'aqualuxe'),
            'remove_featured_image' => _x('Remove featured image', 'Overrides the "Remove featured image" phrase', 'aqualuxe'),
            'use_featured_image'    => _x('Use as featured image', 'Overrides the "Use as featured image" phrase', 'aqualuxe'),
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
            'menu_position'      => 25,
            'menu_icon'          => 'dashicons-admin-tools',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
        );

        register_post_type('aqualuxe_service', $args);
    }

    /**
     * Register service taxonomies
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
            'menu_name'         => __('Categories', 'aqualuxe'),
        );

        register_taxonomy('service_category', array('aqualuxe_service'), array(
            'hierarchical'      => true,
            'labels'            => $category_labels,
            'show_ui'           => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'service-category'),
        ));

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
            'menu_name'                  => __('Tags', 'aqualuxe'),
        );

        register_taxonomy('service_tag', array('aqualuxe_service'), array(
            'hierarchical'      => false,
            'labels'            => $tag_labels,
            'show_ui'           => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'service-tag'),
        ));
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'aqualuxe_service_details',
            __('Service Details', 'aqualuxe'),
            array($this, 'render_service_details_meta_box'),
            'aqualuxe_service',
            'normal',
            'high'
        );
    }

    /**
     * Render service details meta box
     *
     * @param WP_Post $post Current post object
     */
    public function render_service_details_meta_box($post) {
        wp_nonce_field('aqualuxe_service_details_nonce', 'aqualuxe_service_details_nonce');

        $price = get_post_meta($post->ID, '_service_price', true);
        $duration = get_post_meta($post->ID, '_service_duration', true);
        $location = get_post_meta($post->ID, '_service_location', true);
        $features = get_post_meta($post->ID, '_service_features', true);
        $booking_enabled = get_post_meta($post->ID, '_service_booking_enabled', true);
        $booking_url = get_post_meta($post->ID, '_service_booking_url', true);
        $price_type = get_post_meta($post->ID, '_service_price_type', true);
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="service_price"><?php esc_html_e('Price', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="text" id="service_price" name="service_price" value="<?php echo esc_attr($price); ?>" class="regular-text" placeholder="<?php esc_attr_e('e.g., $50 - $200', 'aqualuxe'); ?>">
                        <select name="service_price_type" style="width: auto;">
                            <option value="fixed" <?php selected($price_type, 'fixed'); ?>><?php esc_html_e('Fixed', 'aqualuxe'); ?></option>
                            <option value="range" <?php selected($price_type, 'range'); ?>><?php esc_html_e('Range', 'aqualuxe'); ?></option>
                            <option value="hourly" <?php selected($price_type, 'hourly'); ?>><?php esc_html_e('Per Hour', 'aqualuxe'); ?></option>
                            <option value="quote" <?php selected($price_type, 'quote'); ?>><?php esc_html_e('Quote', 'aqualuxe'); ?></option>
                        </select>
                    </div>
                    <p class="description"><?php esc_html_e('Enter the service price or price range.', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="service_duration"><?php esc_html_e('Duration', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="text" id="service_duration" name="service_duration" value="<?php echo esc_attr($duration); ?>" class="regular-text" placeholder="<?php esc_attr_e('e.g., 2 hours, 1-3 days', 'aqualuxe'); ?>">
                    <p class="description"><?php esc_html_e('How long does this service typically take?', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="service_location"><?php esc_html_e('Location/Availability', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="text" id="service_location" name="service_location" value="<?php echo esc_attr($location); ?>" class="regular-text" placeholder="<?php esc_attr_e('e.g., On-site, Remote, Studio', 'aqualuxe'); ?>">
                    <p class="description"><?php esc_html_e('Where is this service provided?', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="service_features"><?php esc_html_e('Features/Includes', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <textarea id="service_features" name="service_features" rows="4" class="large-text" placeholder="<?php esc_attr_e('Enter each feature on a new line', 'aqualuxe'); ?>"><?php echo esc_textarea($features); ?></textarea>
                    <p class="description"><?php esc_html_e('List what\'s included in this service (one per line).', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <?php esc_html_e('Booking', 'aqualuxe'); ?>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="service_booking_enabled" value="1" <?php checked($booking_enabled, '1'); ?>>
                        <?php esc_html_e('Enable online booking for this service', 'aqualuxe'); ?>
                    </label>
                    <br><br>
                    <label for="service_booking_url"><?php esc_html_e('Custom booking URL (optional):', 'aqualuxe'); ?></label><br>
                    <input type="url" id="service_booking_url" name="service_booking_url" value="<?php echo esc_url($booking_url); ?>" class="regular-text" placeholder="https://">
                    <p class="description"><?php esc_html_e('If you have an external booking system, enter the URL here.', 'aqualuxe'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save meta box data
     *
     * @param int $post_id Post ID
     */
    public function save_meta_boxes($post_id) {
        // Verify nonce
        if (!isset($_POST['aqualuxe_service_details_nonce']) || !wp_verify_nonce($_POST['aqualuxe_service_details_nonce'], 'aqualuxe_service_details_nonce')) {
            return;
        }

        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save fields
        $fields = array(
            'service_price' => '_service_price',
            'service_duration' => '_service_duration',
            'service_location' => '_service_location',
            'service_features' => '_service_features',
            'service_booking_url' => '_service_booking_url',
            'service_price_type' => '_service_price_type',
        );

        foreach ($fields as $field => $meta_key) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
            }
        }

        // Handle checkbox
        $booking_enabled = isset($_POST['service_booking_enabled']) ? '1' : '0';
        update_post_meta($post_id, '_service_booking_enabled', $booking_enabled);
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        if (is_singular('aqualuxe_service') || is_post_type_archive('aqualuxe_service')) {
            wp_enqueue_script(
                'aqualuxe-services',
                AQUALUXE_ASSETS_URI . '/js/modules/services.js',
                array('jquery'),
                AQUALUXE_VERSION,
                true
            );

            wp_localize_script('aqualuxe-services', 'aqualuxeServices', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_services'),
                'strings' => array(
                    'inquiry_sent' => __('Your inquiry has been sent successfully!', 'aqualuxe'),
                    'inquiry_error' => __('There was an error sending your inquiry. Please try again.', 'aqualuxe'),
                    'required_fields' => __('Please fill in all required fields.', 'aqualuxe'),
                ),
            ));
        }
    }

    /**
     * Handle service inquiry
     */
    public function handle_service_inquiry() {
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_services')) {
            wp_die('Security check failed');
        }

        $service_id = intval($_POST['service_id']);
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $message = sanitize_textarea_field($_POST['message']);
        $preferred_date = sanitize_text_field($_POST['preferred_date']);

        // Validate required fields
        if (empty($name) || empty($email) || empty($message)) {
            wp_send_json_error(__('Please fill in all required fields.', 'aqualuxe'));
        }

        if (!is_email($email)) {
            wp_send_json_error(__('Please enter a valid email address.', 'aqualuxe'));
        }

        // Get service details
        $service = get_post($service_id);
        if (!$service || $service->post_type !== 'aqualuxe_service') {
            wp_send_json_error(__('Invalid service selected.', 'aqualuxe'));
        }

        // Prepare email
        $admin_email = get_option('admin_email');
        $subject = sprintf(__('Service Inquiry: %s', 'aqualuxe'), $service->post_title);
        
        $email_body = sprintf(
            __("New service inquiry received:\n\nService: %s\nName: %s\nEmail: %s\nPhone: %s\nPreferred Date: %s\n\nMessage:\n%s\n\nService URL: %s", 'aqualuxe'),
            $service->post_title,
            $name,
            $email,
            $phone,
            $preferred_date,
            $message,
            get_permalink($service_id)
        );

        $headers = array('Content-Type: text/html; charset=UTF-8');

        // Send email
        $sent = wp_mail($admin_email, $subject, $email_body, $headers);

        if ($sent) {
            // Send confirmation email to customer
            $customer_subject = sprintf(__('Service Inquiry Confirmation - %s', 'aqualuxe'), get_bloginfo('name'));
            $customer_body = sprintf(
                __("Thank you for your inquiry about our %s service.\n\nWe will review your request and get back to you within 24 hours.\n\nBest regards,\n%s Team", 'aqualuxe'),
                $service->post_title,
                get_bloginfo('name')
            );
            
            wp_mail($email, $customer_subject, $customer_body, $headers);

            wp_send_json_success(__('Your inquiry has been sent successfully! We will contact you soon.', 'aqualuxe'));
        } else {
            wp_send_json_error(__('There was an error sending your inquiry. Please try again.', 'aqualuxe'));
        }
    }

    /**
     * Template include
     *
     * @param string $template Template path
     * @return string
     */
    public function template_include($template) {
        if (is_singular('aqualuxe_service')) {
            $theme_template = locate_template('single-aqualuxe-service.php');
            if ($theme_template) {
                return $theme_template;
            }
            
            // Use default template if theme doesn't have one
            return AQUALUXE_THEME_DIR . '/templates/single-service.php';
        }

        if (is_post_type_archive('aqualuxe_service')) {
            $theme_template = locate_template('archive-aqualuxe_service.php');
            if ($theme_template) {
                return $theme_template;
            }
            
            return AQUALUXE_THEME_DIR . '/templates/archive-services.php';
        }

        return $template;
    }

    /**
     * Services shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string
     */
    public function services_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 6,
            'category' => '',
            'layout' => 'grid',
            'show_excerpt' => 'true',
            'show_price' => 'true',
            'show_features' => 'false',
        ), $atts, 'aqualuxe_services');

        $args = array(
            'post_type' => 'aqualuxe_service',
            'posts_per_page' => intval($atts['limit']),
            'post_status' => 'publish',
        );

        if (!empty($atts['category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'service_category',
                    'field'    => 'slug',
                    'terms'    => $atts['category'],
                ),
            );
        }

        $services = new WP_Query($args);

        if (!$services->have_posts()) {
            return '<p>' . __('No services found.', 'aqualuxe') . '</p>';
        }

        ob_start();
        ?>
        <div class="aqualuxe-services-grid <?php echo esc_attr($atts['layout']); ?>">
            <?php while ($services->have_posts()): $services->the_post(); ?>
                <div class="service-item">
                    <?php if (has_post_thumbnail()): ?>
                        <div class="service-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('aqualuxe-medium'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="service-content">
                        <h3 class="service-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        
                        <?php if ($atts['show_excerpt'] === 'true'): ?>
                            <div class="service-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($atts['show_price'] === 'true'): ?>
                            <?php $price = get_post_meta(get_the_ID(), '_service_price', true); ?>
                            <?php if ($price): ?>
                                <div class="service-price">
                                    <span class="price-label"><?php esc_html_e('Price:', 'aqualuxe'); ?></span>
                                    <span class="price-value"><?php echo esc_html($price); ?></span>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <div class="service-actions">
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                                <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Service form shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string
     */
    public function service_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'service_id' => get_the_ID(),
            'title' => __('Request This Service', 'aqualuxe'),
        ), $atts, 'aqualuxe_service_form');

        $service_id = intval($atts['service_id']);
        
        if (!$service_id || get_post_type($service_id) !== 'aqualuxe_service') {
            return '<p>' . __('Invalid service.', 'aqualuxe') . '</p>';
        }

        ob_start();
        ?>
        <div class="aqualuxe-service-form">
            <h3><?php echo esc_html($atts['title']); ?></h3>
            
            <form id="service-inquiry-form" class="service-inquiry-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="inquiry-name"><?php esc_html_e('Name', 'aqualuxe'); ?> <span class="required">*</span></label>
                        <input type="text" id="inquiry-name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="inquiry-email"><?php esc_html_e('Email', 'aqualuxe'); ?> <span class="required">*</span></label>
                        <input type="email" id="inquiry-email" name="email" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="inquiry-phone"><?php esc_html_e('Phone', 'aqualuxe'); ?></label>
                        <input type="tel" id="inquiry-phone" name="phone">
                    </div>
                    
                    <div class="form-group">
                        <label for="inquiry-date"><?php esc_html_e('Preferred Date', 'aqualuxe'); ?></label>
                        <input type="date" id="inquiry-date" name="preferred_date">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="inquiry-message"><?php esc_html_e('Message', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <textarea id="inquiry-message" name="message" rows="4" required placeholder="<?php esc_attr_e('Please describe your needs and any specific requirements...', 'aqualuxe'); ?>"></textarea>
                </div>
                
                <input type="hidden" name="service_id" value="<?php echo esc_attr($service_id); ?>">
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?php esc_html_e('Send Inquiry', 'aqualuxe'); ?>
                    </button>
                </div>
                
                <div class="form-messages"></div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Initialize the services module
AquaLuxe_Services_Module::get_instance();