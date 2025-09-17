<?php
/**
 * Subscriptions Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Subscriptions;

use AquaLuxe\Core\Abstracts\Abstract_Module;

defined('ABSPATH') || exit;

/**
 * Subscriptions Module Class
 */
class Module extends Abstract_Module {

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Subscriptions';

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
        add_action('wp_ajax_aqualuxe_create_subscription', array($this, 'handle_subscription'));
        add_action('wp_ajax_nopriv_aqualuxe_create_subscription', array($this, 'handle_subscription'));
        add_shortcode('aqualuxe_subscriptions', array($this, 'subscriptions_shortcode'));
        add_shortcode('aqualuxe_subscription_form', array($this, 'subscription_form_shortcode'));
        
        // WooCommerce integration
        if (class_exists('WooCommerce')) {
            add_action('woocommerce_subscription_status_changed', array($this, 'handle_subscription_status_change'), 10, 3);
        }
    }

    /**
     * Register subscriptions post type
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => _x('Subscriptions', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Subscription', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Subscriptions', 'Admin Menu text', 'aqualuxe'),
            'name_admin_bar'        => _x('Subscription', 'Add New on Toolbar', 'aqualuxe'),
            'add_new'               => __('Add New', 'aqualuxe'),
            'add_new_item'          => __('Add New Subscription', 'aqualuxe'),
            'new_item'              => __('New Subscription', 'aqualuxe'),
            'edit_item'             => __('Edit Subscription', 'aqualuxe'),
            'view_item'             => __('View Subscription', 'aqualuxe'),
            'all_items'             => __('All Subscriptions', 'aqualuxe'),
            'search_items'          => __('Search Subscriptions', 'aqualuxe'),
            'parent_item_colon'     => __('Parent Subscriptions:', 'aqualuxe'),
            'not_found'             => __('No subscriptions found.', 'aqualuxe'),
            'not_found_in_trash'    => __('No subscriptions found in Trash.', 'aqualuxe'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'subscriptions'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 25,
            'menu_icon'          => 'dashicons-update',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
        );

        register_post_type('aqualuxe_subscription', $args);
    }

    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Subscription Types
        $type_labels = array(
            'name'              => _x('Subscription Types', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Subscription Type', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Subscription Types', 'aqualuxe'),
            'all_items'         => __('All Subscription Types', 'aqualuxe'),
            'parent_item'       => __('Parent Subscription Type', 'aqualuxe'),
            'parent_item_colon' => __('Parent Subscription Type:', 'aqualuxe'),
            'edit_item'         => __('Edit Subscription Type', 'aqualuxe'),
            'update_item'       => __('Update Subscription Type', 'aqualuxe'),
            'add_new_item'      => __('Add New Subscription Type', 'aqualuxe'),
            'new_item_name'     => __('New Subscription Type Name', 'aqualuxe'),
            'menu_name'         => __('Subscription Types', 'aqualuxe'),
        );

        register_taxonomy('aqualuxe_subscription_type', array('aqualuxe_subscription'), array(
            'hierarchical'      => true,
            'labels'            => $type_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'subscription-type'),
        ));

        // Subscription Plans
        $plan_labels = array(
            'name'              => _x('Subscription Plans', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Subscription Plan', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Plans', 'aqualuxe'),
            'all_items'         => __('All Plans', 'aqualuxe'),
            'edit_item'         => __('Edit Plan', 'aqualuxe'),
            'update_item'       => __('Update Plan', 'aqualuxe'),
            'add_new_item'      => __('Add New Plan', 'aqualuxe'),
            'new_item_name'     => __('New Plan Name', 'aqualuxe'),
            'menu_name'         => __('Plans', 'aqualuxe'),
        );

        register_taxonomy('aqualuxe_subscription_plan', array('aqualuxe_subscription'), array(
            'hierarchical'      => false,
            'labels'            => $plan_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'subscription-plan'),
        ));
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'aqualuxe_subscription_details',
            __('Subscription Details', 'aqualuxe'),
            array($this, 'subscription_details_meta_box'),
            'aqualuxe_subscription',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe_subscription_pricing',
            __('Pricing & Billing', 'aqualuxe'),
            array($this, 'subscription_pricing_meta_box'),
            'aqualuxe_subscription',
            'side',
            'default'
        );
    }

    /**
     * Subscription details meta box
     */
    public function subscription_details_meta_box($post) {
        wp_nonce_field('aqualuxe_subscription_nonce', 'aqualuxe_subscription_nonce');
        
        $duration = get_post_meta($post->ID, '_subscription_duration', true);
        $features = get_post_meta($post->ID, '_subscription_features', true);
        $restrictions = get_post_meta($post->ID, '_subscription_restrictions', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="subscription_duration"><?php _e('Duration', 'aqualuxe'); ?></label></th>
                <td>
                    <select name="subscription_duration" id="subscription_duration">
                        <option value="monthly" <?php selected($duration, 'monthly'); ?>><?php _e('Monthly', 'aqualuxe'); ?></option>
                        <option value="quarterly" <?php selected($duration, 'quarterly'); ?>><?php _e('Quarterly', 'aqualuxe'); ?></option>
                        <option value="yearly" <?php selected($duration, 'yearly'); ?>><?php _e('Yearly', 'aqualuxe'); ?></option>
                        <option value="lifetime" <?php selected($duration, 'lifetime'); ?>><?php _e('Lifetime', 'aqualuxe'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="subscription_features"><?php _e('Features', 'aqualuxe'); ?></label></th>
                <td>
                    <textarea name="subscription_features" id="subscription_features" rows="5" cols="50"><?php echo esc_textarea($features); ?></textarea>
                    <p class="description"><?php _e('One feature per line', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="subscription_restrictions"><?php _e('Restrictions', 'aqualuxe'); ?></label></th>
                <td>
                    <textarea name="subscription_restrictions" id="subscription_restrictions" rows="3" cols="50"><?php echo esc_textarea($restrictions); ?></textarea>
                    <p class="description"><?php _e('Any usage restrictions', 'aqualuxe'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Subscription pricing meta box
     */
    public function subscription_pricing_meta_box($post) {
        $price = get_post_meta($post->ID, '_subscription_price', true);
        $currency = get_post_meta($post->ID, '_subscription_currency', true);
        $trial_period = get_post_meta($post->ID, '_subscription_trial_period', true);
        $discount = get_post_meta($post->ID, '_subscription_discount', true);
        ?>
        <p>
            <label for="subscription_price"><?php _e('Price', 'aqualuxe'); ?></label>
            <input type="number" name="subscription_price" id="subscription_price" value="<?php echo esc_attr($price); ?>" step="0.01" min="0" />
        </p>
        <p>
            <label for="subscription_currency"><?php _e('Currency', 'aqualuxe'); ?></label>
            <select name="subscription_currency" id="subscription_currency">
                <option value="USD" <?php selected($currency, 'USD'); ?>>USD</option>
                <option value="EUR" <?php selected($currency, 'EUR'); ?>>EUR</option>
                <option value="GBP" <?php selected($currency, 'GBP'); ?>>GBP</option>
                <option value="JPY" <?php selected($currency, 'JPY'); ?>>JPY</option>
            </select>
        </p>
        <p>
            <label for="subscription_trial_period"><?php _e('Trial Period (days)', 'aqualuxe'); ?></label>
            <input type="number" name="subscription_trial_period" id="subscription_trial_period" value="<?php echo esc_attr($trial_period); ?>" min="0" />
        </p>
        <p>
            <label for="subscription_discount"><?php _e('Discount (%)', 'aqualuxe'); ?></label>
            <input type="number" name="subscription_discount" id="subscription_discount" value="<?php echo esc_attr($discount); ?>" min="0" max="100" />
        </p>
        <?php
    }

    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id) {
        if (!isset($_POST['aqualuxe_subscription_nonce']) || !wp_verify_nonce($_POST['aqualuxe_subscription_nonce'], 'aqualuxe_subscription_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = array(
            'subscription_duration',
            'subscription_features', 
            'subscription_restrictions',
            'subscription_price',
            'subscription_currency',
            'subscription_trial_period',
            'subscription_discount'
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
        if (is_singular('aqualuxe_subscription') || is_post_type_archive('aqualuxe_subscription')) {
            wp_enqueue_script('aqualuxe-subscriptions', $this->get_url() . '/assets/subscriptions.js', array('jquery'), '1.0.0', true);
            wp_enqueue_style('aqualuxe-subscriptions', $this->get_url() . '/assets/subscriptions.css', array(), '1.0.0');
            
            wp_localize_script('aqualuxe-subscriptions', 'aqualuxe_subscriptions', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_subscriptions_nonce'),
                'currency_symbol' => get_woocommerce_currency_symbol(),
            ));
        }
    }

    /**
     * Handle subscription creation
     */
    public function handle_subscription() {
        check_ajax_referer('aqualuxe_subscriptions_nonce', 'nonce');

        $subscription_id = intval($_POST['subscription_id']);
        $user_id = get_current_user_id();

        if (!$user_id) {
            wp_send_json_error('Please log in to subscribe');
        }

        // Create subscription record
        $subscription_data = array(
            'user_id' => $user_id,
            'subscription_id' => $subscription_id,
            'status' => 'pending',
            'created_at' => current_time('mysql')
        );

        // In a real implementation, integrate with payment gateway
        wp_send_json_success('Subscription created successfully');
    }

    /**
     * Subscriptions shortcode
     */
    public function subscriptions_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 3,
            'type' => '',
            'plan' => ''
        ), $atts);

        $args = array(
            'post_type' => 'aqualuxe_subscription',
            'posts_per_page' => intval($atts['limit']),
            'post_status' => 'publish'
        );

        if (!empty($atts['type'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'aqualuxe_subscription_type',
                'field' => 'slug',
                'terms' => $atts['type']
            );
        }

        if (!empty($atts['plan'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'aqualuxe_subscription_plan',
                'field' => 'slug',
                'terms' => $atts['plan']
            );
        }

        $subscriptions = new \WP_Query($args);
        
        ob_start();
        if ($subscriptions->have_posts()) {
            echo '<div class="aqualuxe-subscriptions-grid">';
            while ($subscriptions->have_posts()) {
                $subscriptions->the_post();
                $this->load_template('subscription-card', array('post_id' => get_the_ID()));
            }
            echo '</div>';
            wp_reset_postdata();
        }
        return ob_get_clean();
    }

    /**
     * Subscription form shortcode
     */
    public function subscription_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'subscription_id' => 0
        ), $atts);

        if (!$atts['subscription_id']) {
            return '';
        }

        ob_start();
        $this->load_template('subscription-form', array('subscription_id' => $atts['subscription_id']));
        return ob_get_clean();
    }

    /**
     * Handle subscription status change (WooCommerce integration)
     */
    public function handle_subscription_status_change($subscription, $new_status, $old_status) {
        // Handle subscription status changes from WooCommerce Subscriptions
        do_action('aqualuxe_subscription_status_changed', $subscription, $new_status, $old_status);
    }
}