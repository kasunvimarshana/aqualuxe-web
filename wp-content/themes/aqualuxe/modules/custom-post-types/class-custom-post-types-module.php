<?php
/**
 * Custom Post Types Module
 * 
 * Registers custom post types and taxonomies for AquaLuxe
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Custom_Post_Types_Module {
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
    }
    
    /**
     * Initialize module
     */
    private function init() {
        add_action('init', [$this, 'register_post_types']);
        add_action('init', [$this, 'register_taxonomies']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_boxes']);
    }
    
    /**
     * Register custom post types
     */
    public function register_post_types() {
        // Services Post Type
        register_post_type('aqualuxe_service', [
            'labels' => [
                'name' => esc_html__('Services', 'aqualuxe'),
                'singular_name' => esc_html__('Service', 'aqualuxe'),
                'add_new' => esc_html__('Add New Service', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Service', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Service', 'aqualuxe'),
                'new_item' => esc_html__('New Service', 'aqualuxe'),
                'view_item' => esc_html__('View Service', 'aqualuxe'),
                'search_items' => esc_html__('Search Services', 'aqualuxe'),
                'not_found' => esc_html__('No services found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No services found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-admin-tools',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'rewrite' => ['slug' => 'services'],
            'show_in_rest' => true,
        ]);
        
        // Testimonials Post Type
        register_post_type('aqualuxe_testimonial', [
            'labels' => [
                'name' => esc_html__('Testimonials', 'aqualuxe'),
                'singular_name' => esc_html__('Testimonial', 'aqualuxe'),
                'add_new' => esc_html__('Add New Testimonial', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Testimonial', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Testimonial', 'aqualuxe'),
                'new_item' => esc_html__('New Testimonial', 'aqualuxe'),
                'view_item' => esc_html__('View Testimonial', 'aqualuxe'),
                'search_items' => esc_html__('Search Testimonials', 'aqualuxe'),
                'not_found' => esc_html__('No testimonials found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No testimonials found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-format-quote',
            'supports' => ['title', 'editor', 'thumbnail'],
            'rewrite' => ['slug' => 'testimonials'],
            'show_in_rest' => true,
        ]);
        
        // Team Members Post Type
        register_post_type('aqualuxe_team', [
            'labels' => [
                'name' => esc_html__('Team Members', 'aqualuxe'),
                'singular_name' => esc_html__('Team Member', 'aqualuxe'),
                'add_new' => esc_html__('Add New Team Member', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Team Member', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Team Member', 'aqualuxe'),
                'new_item' => esc_html__('New Team Member', 'aqualuxe'),
                'view_item' => esc_html__('View Team Member', 'aqualuxe'),
                'search_items' => esc_html__('Search Team Members', 'aqualuxe'),
                'not_found' => esc_html__('No team members found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No team members found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-groups',
            'supports' => ['title', 'editor', 'thumbnail'],
            'rewrite' => ['slug' => 'team'],
            'show_in_rest' => true,
        ]);
        
        // FAQ Post Type
        register_post_type('aqualuxe_faq', [
            'labels' => [
                'name' => esc_html__('FAQs', 'aqualuxe'),
                'singular_name' => esc_html__('FAQ', 'aqualuxe'),
                'add_new' => esc_html__('Add New FAQ', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New FAQ', 'aqualuxe'),
                'edit_item' => esc_html__('Edit FAQ', 'aqualuxe'),
                'new_item' => esc_html__('New FAQ', 'aqualuxe'),
                'view_item' => esc_html__('View FAQ', 'aqualuxe'),
                'search_items' => esc_html__('Search FAQs', 'aqualuxe'),
                'not_found' => esc_html__('No FAQs found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No FAQs found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-editor-help',
            'supports' => ['title', 'editor', 'page-attributes'],
            'rewrite' => ['slug' => 'faq'],
            'show_in_rest' => true,
        ]);
    }
    
    /**
     * Register custom taxonomies
     */
    public function register_taxonomies() {
        // Service Categories
        register_taxonomy('service_category', 'aqualuxe_service', [
            'labels' => [
                'name' => esc_html__('Service Categories', 'aqualuxe'),
                'singular_name' => esc_html__('Service Category', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Service Category', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Service Category', 'aqualuxe'),
                'new_item_name' => esc_html__('New Service Category Name', 'aqualuxe'),
                'menu_name' => esc_html__('Categories', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'service-category'],
            'show_in_rest' => true,
        ]);
        
        // FAQ Categories
        register_taxonomy('faq_category', 'aqualuxe_faq', [
            'labels' => [
                'name' => esc_html__('FAQ Categories', 'aqualuxe'),
                'singular_name' => esc_html__('FAQ Category', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New FAQ Category', 'aqualuxe'),
                'edit_item' => esc_html__('Edit FAQ Category', 'aqualuxe'),
                'new_item_name' => esc_html__('New FAQ Category Name', 'aqualuxe'),
                'menu_name' => esc_html__('Categories', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'faq-category'],
            'show_in_rest' => true,
        ]);
    }
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        // Service meta box
        add_meta_box(
            'aqualuxe_service_details',
            esc_html__('Service Details', 'aqualuxe'),
            [$this, 'service_details_callback'],
            'aqualuxe_service',
            'normal',
            'high'
        );
        
        // Testimonial meta box
        add_meta_box(
            'aqualuxe_testimonial_details',
            esc_html__('Testimonial Details', 'aqualuxe'),
            [$this, 'testimonial_details_callback'],
            'aqualuxe_testimonial',
            'normal',
            'high'
        );
        
        // Team member meta box
        add_meta_box(
            'aqualuxe_team_details',
            esc_html__('Team Member Details', 'aqualuxe'),
            [$this, 'team_details_callback'],
            'aqualuxe_team',
            'normal',
            'high'
        );
    }
    
    /**
     * Service details meta box callback
     */
    public function service_details_callback($post) {
        wp_nonce_field('aqualuxe_service_details', 'aqualuxe_service_details_nonce');
        
        $price = get_post_meta($post->ID, '_service_price', true);
        $duration = get_post_meta($post->ID, '_service_duration', true);
        $icon = get_post_meta($post->ID, '_service_icon', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="service_price"><?php esc_html_e('Price', 'aqualuxe'); ?></label></th>
                <td><input type="text" id="service_price" name="service_price" value="<?php echo esc_attr($price); ?>" /></td>
            </tr>
            <tr>
                <th><label for="service_duration"><?php esc_html_e('Duration', 'aqualuxe'); ?></label></th>
                <td><input type="text" id="service_duration" name="service_duration" value="<?php echo esc_attr($duration); ?>" /></td>
            </tr>
            <tr>
                <th><label for="service_icon"><?php esc_html_e('Icon Class', 'aqualuxe'); ?></label></th>
                <td><input type="text" id="service_icon" name="service_icon" value="<?php echo esc_attr($icon); ?>" placeholder="fas fa-fish" /></td>
            </tr>
        </table>
        <?php
    }
    
    /**
     * Testimonial details meta box callback
     */
    public function testimonial_details_callback($post) {
        wp_nonce_field('aqualuxe_testimonial_details', 'aqualuxe_testimonial_details_nonce');
        
        $client_name = get_post_meta($post->ID, '_client_name', true);
        $client_title = get_post_meta($post->ID, '_client_title', true);
        $rating = get_post_meta($post->ID, '_rating', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="client_name"><?php esc_html_e('Client Name', 'aqualuxe'); ?></label></th>
                <td><input type="text" id="client_name" name="client_name" value="<?php echo esc_attr($client_name); ?>" /></td>
            </tr>
            <tr>
                <th><label for="client_title"><?php esc_html_e('Client Title/Position', 'aqualuxe'); ?></label></th>
                <td><input type="text" id="client_title" name="client_title" value="<?php echo esc_attr($client_title); ?>" /></td>
            </tr>
            <tr>
                <th><label for="rating"><?php esc_html_e('Rating', 'aqualuxe'); ?></label></th>
                <td>
                    <select id="rating" name="rating">
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <option value="<?php echo $i; ?>" <?php selected($rating, $i); ?>>
                                <?php echo sprintf(esc_html__('%d Stars', 'aqualuxe'), $i); ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }
    
    /**
     * Team details meta box callback
     */
    public function team_details_callback($post) {
        wp_nonce_field('aqualuxe_team_details', 'aqualuxe_team_details_nonce');
        
        $position = get_post_meta($post->ID, '_team_position', true);
        $email = get_post_meta($post->ID, '_team_email', true);
        $phone = get_post_meta($post->ID, '_team_phone', true);
        $social_links = get_post_meta($post->ID, '_team_social_links', true) ?: [];
        ?>
        <table class="form-table">
            <tr>
                <th><label for="team_position"><?php esc_html_e('Position', 'aqualuxe'); ?></label></th>
                <td><input type="text" id="team_position" name="team_position" value="<?php echo esc_attr($position); ?>" /></td>
            </tr>
            <tr>
                <th><label for="team_email"><?php esc_html_e('Email', 'aqualuxe'); ?></label></th>
                <td><input type="email" id="team_email" name="team_email" value="<?php echo esc_attr($email); ?>" /></td>
            </tr>
            <tr>
                <th><label for="team_phone"><?php esc_html_e('Phone', 'aqualuxe'); ?></label></th>
                <td><input type="text" id="team_phone" name="team_phone" value="<?php echo esc_attr($phone); ?>" /></td>
            </tr>
            <tr>
                <th><?php esc_html_e('Social Links', 'aqualuxe'); ?></th>
                <td>
                    <input type="text" name="team_social_facebook" placeholder="<?php esc_attr_e('Facebook URL', 'aqualuxe'); ?>" value="<?php echo esc_attr($social_links['facebook'] ?? ''); ?>" style="width: 100%; margin-bottom: 5px;" />
                    <input type="text" name="team_social_twitter" placeholder="<?php esc_attr_e('Twitter URL', 'aqualuxe'); ?>" value="<?php echo esc_attr($social_links['twitter'] ?? ''); ?>" style="width: 100%; margin-bottom: 5px;" />
                    <input type="text" name="team_social_linkedin" placeholder="<?php esc_attr_e('LinkedIn URL', 'aqualuxe'); ?>" value="<?php echo esc_attr($social_links['linkedin'] ?? ''); ?>" style="width: 100%;" />
                </td>
            </tr>
        </table>
        <?php
    }
    
    /**
     * Save meta box data
     */
    public function save_meta_boxes($post_id) {
        // Check if it's an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save service details
        if (isset($_POST['aqualuxe_service_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_service_details_nonce'], 'aqualuxe_service_details')) {
            if (isset($_POST['service_price'])) {
                update_post_meta($post_id, '_service_price', sanitize_text_field($_POST['service_price']));
            }
            if (isset($_POST['service_duration'])) {
                update_post_meta($post_id, '_service_duration', sanitize_text_field($_POST['service_duration']));
            }
            if (isset($_POST['service_icon'])) {
                update_post_meta($post_id, '_service_icon', sanitize_text_field($_POST['service_icon']));
            }
        }
        
        // Save testimonial details
        if (isset($_POST['aqualuxe_testimonial_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_testimonial_details_nonce'], 'aqualuxe_testimonial_details')) {
            if (isset($_POST['client_name'])) {
                update_post_meta($post_id, '_client_name', sanitize_text_field($_POST['client_name']));
            }
            if (isset($_POST['client_title'])) {
                update_post_meta($post_id, '_client_title', sanitize_text_field($_POST['client_title']));
            }
            if (isset($_POST['rating'])) {
                update_post_meta($post_id, '_rating', absint($_POST['rating']));
            }
        }
        
        // Save team details
        if (isset($_POST['aqualuxe_team_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_team_details_nonce'], 'aqualuxe_team_details')) {
            if (isset($_POST['team_position'])) {
                update_post_meta($post_id, '_team_position', sanitize_text_field($_POST['team_position']));
            }
            if (isset($_POST['team_email'])) {
                update_post_meta($post_id, '_team_email', sanitize_email($_POST['team_email']));
            }
            if (isset($_POST['team_phone'])) {
                update_post_meta($post_id, '_team_phone', sanitize_text_field($_POST['team_phone']));
            }
            
            // Save social links
            $social_links = [
                'facebook' => isset($_POST['team_social_facebook']) ? esc_url_raw($_POST['team_social_facebook']) : '',
                'twitter' => isset($_POST['team_social_twitter']) ? esc_url_raw($_POST['team_social_twitter']) : '',
                'linkedin' => isset($_POST['team_social_linkedin']) ? esc_url_raw($_POST['team_social_linkedin']) : '',
            ];
            update_post_meta($post_id, '_team_social_links', $social_links);
        }
    }
}