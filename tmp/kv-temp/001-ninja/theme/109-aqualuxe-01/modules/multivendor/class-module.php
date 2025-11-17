<?php
/**
 * Multivendor Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Multivendor;

use AquaLuxe\Core\Abstracts\Abstract_Module;

defined('ABSPATH') || exit;

/**
 * Multivendor Module Class
 */
class Module extends Abstract_Module {

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Multivendor';

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
        add_action('init', array($this, 'register_user_roles'));
        add_action('init', array($this, 'register_post_type'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_ajax_aqualuxe_vendor_application', array($this, 'handle_vendor_application'));
        add_action('wp_ajax_nopriv_aqualuxe_vendor_application', array($this, 'handle_vendor_application'));
        add_shortcode('aqualuxe_vendor_dashboard', array($this, 'vendor_dashboard_shortcode'));
        add_shortcode('aqualuxe_vendor_registration', array($this, 'vendor_registration_shortcode'));
        add_shortcode('aqualuxe_vendor_directory', array($this, 'vendor_directory_shortcode'));
        
        // WooCommerce integration
        if (class_exists('WooCommerce')) {
            add_action('woocommerce_product_options_general_product_data', array($this, 'add_vendor_fields'));
            add_action('woocommerce_process_product_meta', array($this, 'save_vendor_fields'));
            add_filter('woocommerce_get_shop_page_id', array($this, 'vendor_shop_page'));
        }
        
        // Create vendor tables
        add_action('init', array($this, 'create_vendor_tables'));
    }

    /**
     * Create vendor database tables
     */
    public function create_vendor_tables() {
        global $wpdb;

        $commissions_table = $wpdb->prefix . 'aqualuxe_vendor_commissions';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $commissions_table (
            id int(11) NOT NULL AUTO_INCREMENT,
            vendor_id int(11) NOT NULL,
            order_id int(11) NOT NULL,
            product_id int(11) NOT NULL,
            commission_amount decimal(10,2) NOT NULL,
            commission_rate decimal(5,2) NOT NULL,
            status varchar(20) DEFAULT 'pending',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            paid_at datetime DEFAULT NULL,
            PRIMARY KEY (id),
            KEY vendor_id (vendor_id),
            KEY order_id (order_id),
            KEY status (status)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Register vendor user roles
     */
    public function register_user_roles() {
        add_role('vendor', __('Vendor', 'aqualuxe'), array(
            'read' => true,
            'upload_files' => true,
            'edit_products' => true,
            'manage_vendor_shop' => true,
            'view_vendor_reports' => true,
        ));

        add_role('vendor_manager', __('Vendor Manager', 'aqualuxe'), array(
            'read' => true,
            'edit_posts' => true,
            'manage_vendors' => true,
            'view_vendor_analytics' => true,
        ));
    }

    /**
     * Register vendor post type
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => _x('Vendors', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Vendor', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Vendors', 'Admin Menu text', 'aqualuxe'),
            'add_new_item'          => __('Add New Vendor', 'aqualuxe'),
            'edit_item'             => __('Edit Vendor', 'aqualuxe'),
            'view_item'             => __('View Vendor', 'aqualuxe'),
            'all_items'             => __('All Vendors', 'aqualuxe'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'vendors'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 33,
            'menu_icon'          => 'dashicons-store',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        );

        register_post_type('aqualuxe_vendor', $args);
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'aqualuxe_vendor_details',
            __('Vendor Details', 'aqualuxe'),
            array($this, 'vendor_details_meta_box'),
            'aqualuxe_vendor',
            'normal',
            'high'
        );
    }

    /**
     * Vendor details meta box
     */
    public function vendor_details_meta_box($post) {
        wp_nonce_field('aqualuxe_vendor_nonce', 'aqualuxe_vendor_nonce');
        
        $contact_email = get_post_meta($post->ID, '_vendor_contact_email', true);
        $phone = get_post_meta($post->ID, '_vendor_phone', true);
        $address = get_post_meta($post->ID, '_vendor_address', true);
        $commission_rate = get_post_meta($post->ID, '_vendor_commission_rate', true);
        $status = get_post_meta($post->ID, '_vendor_status', true);
        $specialties = get_post_meta($post->ID, '_vendor_specialties', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="vendor_contact_email"><?php _e('Contact Email', 'aqualuxe'); ?></label></th>
                <td><input type="email" name="vendor_contact_email" id="vendor_contact_email" value="<?php echo esc_attr($contact_email); ?>" style="width: 100%;" /></td>
            </tr>
            <tr>
                <th><label for="vendor_phone"><?php _e('Phone', 'aqualuxe'); ?></label></th>
                <td><input type="tel" name="vendor_phone" id="vendor_phone" value="<?php echo esc_attr($phone); ?>" style="width: 100%;" /></td>
            </tr>
            <tr>
                <th><label for="vendor_address"><?php _e('Address', 'aqualuxe'); ?></label></th>
                <td><textarea name="vendor_address" id="vendor_address" rows="3" style="width: 100%;"><?php echo esc_textarea($address); ?></textarea></td>
            </tr>
            <tr>
                <th><label for="vendor_commission_rate"><?php _e('Commission Rate (%)', 'aqualuxe'); ?></label></th>
                <td><input type="number" name="vendor_commission_rate" id="vendor_commission_rate" value="<?php echo esc_attr($commission_rate); ?>" step="0.01" min="0" max="100" /></td>
            </tr>
            <tr>
                <th><label for="vendor_status"><?php _e('Status', 'aqualuxe'); ?></label></th>
                <td>
                    <select name="vendor_status" id="vendor_status">
                        <option value="active" <?php selected($status, 'active'); ?>><?php _e('Active', 'aqualuxe'); ?></option>
                        <option value="inactive" <?php selected($status, 'inactive'); ?>><?php _e('Inactive', 'aqualuxe'); ?></option>
                        <option value="pending" <?php selected($status, 'pending'); ?>><?php _e('Pending Approval', 'aqualuxe'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="vendor_specialties"><?php _e('Specialties', 'aqualuxe'); ?></label></th>
                <td><textarea name="vendor_specialties" id="vendor_specialties" rows="3" style="width: 100%;"><?php echo esc_textarea($specialties); ?></textarea></td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id) {
        if (!isset($_POST['aqualuxe_vendor_nonce']) || !wp_verify_nonce($_POST['aqualuxe_vendor_nonce'], 'aqualuxe_vendor_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = array(
            'vendor_contact_email',
            'vendor_phone',
            'vendor_address',
            'vendor_commission_rate',
            'vendor_status',
            'vendor_specialties'
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
        if (is_singular('aqualuxe_vendor') || is_post_type_archive('aqualuxe_vendor') || is_page()) {
            wp_enqueue_script('aqualuxe-multivendor', $this->get_url() . '/assets/multivendor.js', array('jquery'), '1.0.0', true);
            wp_enqueue_style('aqualuxe-multivendor', $this->get_url() . '/assets/multivendor.css', array(), '1.0.0');
            
            wp_localize_script('aqualuxe-multivendor', 'aqualuxe_multivendor', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_multivendor_nonce'),
                'is_vendor' => current_user_can('manage_vendor_shop'),
            ));
        }
    }

    /**
     * Add vendor fields to WooCommerce products
     */
    public function add_vendor_fields() {
        global $post;

        // Get vendors
        $vendors = get_posts(array(
            'post_type' => 'aqualuxe_vendor',
            'numberposts' => -1,
            'post_status' => 'publish'
        ));

        $vendor_options = array('' => __('Select Vendor', 'aqualuxe'));
        foreach ($vendors as $vendor) {
            $vendor_options[$vendor->ID] = $vendor->post_title;
        }

        woocommerce_wp_select(array(
            'id' => '_product_vendor',
            'label' => __('Vendor', 'aqualuxe'),
            'desc_tip' => true,
            'description' => __('Select the vendor for this product.', 'aqualuxe'),
            'options' => $vendor_options
        ));
    }

    /**
     * Save vendor fields
     */
    public function save_vendor_fields($post_id) {
        $vendor_id = isset($_POST['_product_vendor']) ? sanitize_text_field($_POST['_product_vendor']) : '';
        update_post_meta($post_id, '_product_vendor', $vendor_id);
    }

    /**
     * Handle vendor application
     */
    public function handle_vendor_application() {
        check_ajax_referer('aqualuxe_multivendor_nonce', 'nonce');

        $company_name = sanitize_text_field($_POST['company_name']);
        $contact_name = sanitize_text_field($_POST['contact_name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $address = sanitize_textarea_field($_POST['address']);
        $product_types = sanitize_textarea_field($_POST['product_types']);
        $experience = sanitize_textarea_field($_POST['experience']);

        if (!$company_name || !$contact_name || !$email) {
            wp_send_json_error('Please fill in all required fields');
        }

        // Create vendor profile
        $vendor_data = array(
            'post_type' => 'aqualuxe_vendor',
            'post_title' => $company_name,
            'post_content' => $experience,
            'post_status' => 'pending',
            'meta_input' => array(
                '_vendor_contact_name' => $contact_name,
                '_vendor_contact_email' => $email,
                '_vendor_phone' => $phone,
                '_vendor_address' => $address,
                '_vendor_product_types' => $product_types,
                '_vendor_status' => 'pending',
                '_vendor_application_date' => current_time('mysql')
            )
        );

        $vendor_id = wp_insert_post($vendor_data);

        if ($vendor_id) {
            wp_send_json_success('Application submitted successfully');
        } else {
            wp_send_json_error('Failed to submit application');
        }
    }

    /**
     * Vendor dashboard shortcode
     */
    public function vendor_dashboard_shortcode($atts) {
        if (!current_user_can('manage_vendor_shop')) {
            return '<p>' . __('Access denied. Please log in as a vendor.', 'aqualuxe') . '</p>';
        }

        ob_start();
        $this->load_template('vendor-dashboard', array('user_id' => get_current_user_id()));
        return ob_get_clean();
    }

    /**
     * Vendor registration shortcode
     */
    public function vendor_registration_shortcode($atts) {
        ob_start();
        $this->load_template('vendor-registration-form');
        return ob_get_clean();
    }

    /**
     * Vendor directory shortcode
     */
    public function vendor_directory_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 12,
            'status' => 'active'
        ), $atts);

        $args = array(
            'post_type' => 'aqualuxe_vendor',
            'posts_per_page' => intval($atts['limit']),
            'post_status' => 'publish'
        );

        if (!empty($atts['status'])) {
            $args['meta_query'][] = array(
                'key' => '_vendor_status',
                'value' => $atts['status'],
                'compare' => '='
            );
        }

        $vendors = new \WP_Query($args);
        
        ob_start();
        if ($vendors->have_posts()) {
            echo '<div class="aqualuxe-vendor-directory">';
            while ($vendors->have_posts()) {
                $vendors->the_post();
                $this->load_template('vendor-card', array('post_id' => get_the_ID()));
            }
            echo '</div>';
            wp_reset_postdata();
        }
        return ob_get_clean();
    }
}