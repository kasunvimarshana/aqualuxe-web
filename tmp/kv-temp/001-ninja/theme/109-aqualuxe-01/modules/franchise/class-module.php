<?php
/**
 * Franchise Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Franchise;

use AquaLuxe\Core\Abstracts\Abstract_Module;

defined('ABSPATH') || exit;

/**
 * Franchise Module Class
 */
class Module extends Abstract_Module {

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Franchise';

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
        add_action('init', array($this, 'register_user_roles'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_ajax_aqualuxe_franchise_inquiry', array($this, 'handle_franchise_inquiry'));
        add_action('wp_ajax_nopriv_aqualuxe_franchise_inquiry', array($this, 'handle_franchise_inquiry'));
        add_shortcode('aqualuxe_franchise_form', array($this, 'franchise_form_shortcode'));
        add_shortcode('aqualuxe_franchise_locations', array($this, 'franchise_locations_shortcode'));
    }

    /**
     * Register franchise user roles
     */
    public function register_user_roles() {
        add_role('franchise_partner', __('Franchise Partner', 'aqualuxe'), array(
            'read' => true,
            'manage_franchise_location' => true,
            'view_franchise_reports' => true,
        ));

        add_role('franchise_manager', __('Franchise Manager', 'aqualuxe'), array(
            'read' => true,
            'edit_posts' => true,
            'manage_franchise_network' => true,
            'view_franchise_analytics' => true,
        ));
    }

    /**
     * Register franchise post type
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => _x('Franchise Locations', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Franchise Location', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Franchise', 'Admin Menu text', 'aqualuxe'),
            'add_new_item'          => __('Add New Location', 'aqualuxe'),
            'edit_item'             => __('Edit Location', 'aqualuxe'),
            'view_item'             => __('View Location', 'aqualuxe'),
            'all_items'             => __('All Locations', 'aqualuxe'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'franchise-locations'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 29,
            'menu_icon'          => 'dashicons-store',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        );

        register_post_type('aqualuxe_franchise', $args);
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'aqualuxe_franchise_details',
            __('Franchise Details', 'aqualuxe'),
            array($this, 'franchise_details_meta_box'),
            'aqualuxe_franchise',
            'normal',
            'high'
        );
    }

    /**
     * Franchise details meta box
     */
    public function franchise_details_meta_box($post) {
        wp_nonce_field('aqualuxe_franchise_nonce', 'aqualuxe_franchise_nonce');
        
        $partner_name = get_post_meta($post->ID, '_franchise_partner_name', true);
        $contact_email = get_post_meta($post->ID, '_franchise_contact_email', true);
        $phone = get_post_meta($post->ID, '_franchise_phone', true);
        $address = get_post_meta($post->ID, '_franchise_address', true);
        $opening_date = get_post_meta($post->ID, '_franchise_opening_date', true);
        $territory = get_post_meta($post->ID, '_franchise_territory', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="franchise_partner_name"><?php _e('Partner Name', 'aqualuxe'); ?></label></th>
                <td><input type="text" name="franchise_partner_name" id="franchise_partner_name" value="<?php echo esc_attr($partner_name); ?>" style="width: 100%;" /></td>
            </tr>
            <tr>
                <th><label for="franchise_contact_email"><?php _e('Contact Email', 'aqualuxe'); ?></label></th>
                <td><input type="email" name="franchise_contact_email" id="franchise_contact_email" value="<?php echo esc_attr($contact_email); ?>" style="width: 100%;" /></td>
            </tr>
            <tr>
                <th><label for="franchise_phone"><?php _e('Phone', 'aqualuxe'); ?></label></th>
                <td><input type="tel" name="franchise_phone" id="franchise_phone" value="<?php echo esc_attr($phone); ?>" style="width: 100%;" /></td>
            </tr>
            <tr>
                <th><label for="franchise_address"><?php _e('Address', 'aqualuxe'); ?></label></th>
                <td><textarea name="franchise_address" id="franchise_address" rows="3" style="width: 100%;"><?php echo esc_textarea($address); ?></textarea></td>
            </tr>
            <tr>
                <th><label for="franchise_opening_date"><?php _e('Opening Date', 'aqualuxe'); ?></label></th>
                <td><input type="date" name="franchise_opening_date" id="franchise_opening_date" value="<?php echo esc_attr($opening_date); ?>" /></td>
            </tr>
            <tr>
                <th><label for="franchise_territory"><?php _e('Territory', 'aqualuxe'); ?></label></th>
                <td><input type="text" name="franchise_territory" id="franchise_territory" value="<?php echo esc_attr($territory); ?>" style="width: 100%;" /></td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id) {
        if (!isset($_POST['aqualuxe_franchise_nonce']) || !wp_verify_nonce($_POST['aqualuxe_franchise_nonce'], 'aqualuxe_franchise_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = array(
            'franchise_partner_name',
            'franchise_contact_email',
            'franchise_phone',
            'franchise_address',
            'franchise_opening_date',
            'franchise_territory'
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        if (is_singular('aqualuxe_franchise') || is_post_type_archive('aqualuxe_franchise') || is_page()) {
            wp_enqueue_script('aqualuxe-franchise', $this->get_url() . '/assets/franchise.js', array('jquery'), '1.0.0', true);
            wp_enqueue_style('aqualuxe-franchise', $this->get_url() . '/assets/franchise.css', array(), '1.0.0');
            
            wp_localize_script('aqualuxe-franchise', 'aqualuxe_franchise', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_franchise_nonce'),
            ));
        }
    }

    /**
     * Handle franchise inquiry
     */
    public function handle_franchise_inquiry() {
        check_ajax_referer('aqualuxe_franchise_nonce', 'nonce');

        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $location = sanitize_text_field($_POST['location']);
        $investment_capacity = sanitize_text_field($_POST['investment_capacity']);
        $experience = sanitize_textarea_field($_POST['experience']);

        if (!$name || !$email) {
            wp_send_json_error('Please fill in all required fields');
        }

        // Send inquiry email
        $admin_email = get_option('admin_email');
        $subject = __('New Franchise Inquiry', 'aqualuxe');
        $message = sprintf(
            __("New franchise inquiry received:\n\nName: %s\nEmail: %s\nPhone: %s\nLocation: %s\nInvestment Capacity: %s\nExperience: %s", 'aqualuxe'),
            $name, $email, $phone, $location, $investment_capacity, $experience
        );

        if (wp_mail($admin_email, $subject, $message)) {
            wp_send_json_success('Inquiry submitted successfully');
        } else {
            wp_send_json_error('Failed to submit inquiry');
        }
    }

    /**
     * Franchise form shortcode
     */
    public function franchise_form_shortcode($atts) {
        ob_start();
        $this->load_template('franchise-inquiry-form');
        return ob_get_clean();
    }

    /**
     * Franchise locations shortcode
     */
    public function franchise_locations_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 12,
            'territory' => ''
        ), $atts);

        $args = array(
            'post_type' => 'aqualuxe_franchise',
            'posts_per_page' => intval($atts['limit']),
            'post_status' => 'publish'
        );

        if (!empty($atts['territory'])) {
            $args['meta_query'][] = array(
                'key' => '_franchise_territory',
                'value' => $atts['territory'],
                'compare' => 'LIKE'
            );
        }

        $locations = new \WP_Query($args);
        
        ob_start();
        if ($locations->have_posts()) {
            echo '<div class="aqualuxe-franchise-locations">';
            while ($locations->have_posts()) {
                $locations->the_post();
                $this->load_template('franchise-location-card', array('post_id' => get_the_ID()));
            }
            echo '</div>';
            wp_reset_postdata();
        }
        return ob_get_clean();
    }
}