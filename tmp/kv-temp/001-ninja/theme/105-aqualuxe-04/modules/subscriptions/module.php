<?php
/**
 * Subscriptions Module
 *
 * Handles subscription and membership functionality with WooCommerce integration
 *
 * @package AquaLuxe\Modules\Subscriptions
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class AquaLuxe_Subscriptions_Module
 *
 * Manages subscriptions and memberships
 */
class AquaLuxe_Subscriptions_Module {
    
    /**
     * Single instance of the class
     *
     * @var AquaLuxe_Subscriptions_Module
     */
    private static $instance = null;

    /**
     * Module configuration
     *
     * @var array
     */
    private $config = array(
        'enabled' => true,
        'membership_tiers' => array(
            'basic' => array(
                'name' => 'Basic Aquarist',
                'price' => 9.99,
                'features' => array('monthly_newsletter', 'basic_support', 'community_access')
            ),
            'premium' => array(
                'name' => 'Premium Aquarist',
                'price' => 19.99,
                'features' => array('priority_support', 'exclusive_content', 'monthly_consultation', 'expert_webinars')
            ),
            'professional' => array(
                'name' => 'Professional Aquarist',
                'price' => 49.99,
                'features' => array('dedicated_support', 'custom_solutions', 'weekly_consultation', 'trade_discounts')
            )
        ),
        'subscription_periods' => array('monthly', 'quarterly', 'yearly'),
        'free_trial_days' => 14
    );

    /**
     * Get instance
     *
     * @return AquaLuxe_Subscriptions_Module
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
        add_action('init', array($this, 'register_post_types'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_aqualuxe_subscribe', array($this, 'handle_subscription'));
        add_action('wp_ajax_nopriv_aqualuxe_subscribe', array($this, 'handle_subscription'));
        add_filter('the_content', array($this, 'filter_content_by_membership'));
        
        // Shortcodes
        add_shortcode('aqualuxe_subscription_form', array($this, 'subscription_form_shortcode'));
        add_shortcode('aqualuxe_membership_plans', array($this, 'membership_plans_shortcode'));
    }

    /**
     * Register custom post types
     */
    public function register_post_types() {
        register_post_type('aqualuxe_subscription', array(
            'labels' => array(
                'name' => __('Subscriptions', 'aqualuxe'),
                'singular_name' => __('Subscription', 'aqualuxe'),
                'menu_name' => __('Subscriptions', 'aqualuxe')
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'capability_type' => 'post',
            'supports' => array('title', 'custom-fields'),
            'menu_icon' => 'dashicons-update'
        ));
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'aqualuxe-subscriptions',
            AQUALUXE_ASSETS_URI . '/js/modules/subscriptions.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );

        wp_localize_script('aqualuxe-subscriptions', 'aqualuxe_subscriptions', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_subscriptions_nonce'),
            'membership_tiers' => $this->config['membership_tiers']
        ));
    }

    /**
     * Handle subscription AJAX
     */
    public function handle_subscription() {
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_subscriptions_nonce')) {
            wp_send_json_error(__('Security check failed.', 'aqualuxe'));
        }

        $plan = sanitize_text_field($_POST['plan'] ?? '');
        $email = sanitize_email($_POST['email'] ?? '');

        if (empty($plan) || empty($email)) {
            wp_send_json_error(__('Please fill in all required fields.', 'aqualuxe'));
        }

        $subscription_id = $this->create_subscription($email, $plan);
        
        if ($subscription_id) {
            wp_send_json_success(array(
                'message' => __('Subscription created successfully!', 'aqualuxe'),
                'subscription_id' => $subscription_id
            ));
        } else {
            wp_send_json_error(__('Failed to create subscription.', 'aqualuxe'));
        }
    }

    /**
     * Create subscription
     *
     * @param string $email
     * @param string $plan
     * @return int|false
     */
    private function create_subscription($email, $plan) {
        return wp_insert_post(array(
            'post_type' => 'aqualuxe_subscription',
            'post_title' => sprintf(__('Subscription for %s', 'aqualuxe'), $email),
            'post_status' => 'publish',
            'meta_input' => array(
                '_subscriber_email' => $email,
                '_subscription_plan' => $plan,
                '_subscription_status' => 'active',
                '_created_date' => current_time('mysql')
            )
        ));
    }

    /**
     * Membership plans shortcode
     */
    public function membership_plans_shortcode($atts = array()) {
        $atts = shortcode_atts(array(
            'columns' => 3
        ), $atts);

        ob_start();
        ?>
        <div class="aqualuxe-membership-plans">
            <div class="grid grid-cols-1 md:grid-cols-<?php echo esc_attr($atts['columns']); ?> gap-8">
                <?php foreach ($this->config['membership_tiers'] as $tier_id => $tier): ?>
                    <div class="membership-plan bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-bold mb-4"><?php echo esc_html($tier['name']); ?></h3>
                        <div class="price text-3xl font-bold text-primary-600 mb-4">
                            $<?php echo esc_html($tier['price']); ?>/month
                        </div>
                        <ul class="features mb-6">
                            <?php foreach ($tier['features'] as $feature): ?>
                                <li class="mb-2">✓ <?php echo esc_html(str_replace('_', ' ', ucwords($feature, '_'))); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button class="subscription-btn btn btn-primary w-full" data-plan="<?php echo esc_attr($tier_id); ?>">
                            <?php _e('Subscribe Now', 'aqualuxe'); ?>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Subscription form shortcode
     */
    public function subscription_form_shortcode($atts = array()) {
        $atts = shortcode_atts(array(
            'plan' => ''
        ), $atts);

        ob_start();
        ?>
        <form id="aqualuxe-subscription-form" class="subscription-form">
            <div class="form-group mb-4">
                <label for="subscriber-email"><?php _e('Email Address', 'aqualuxe'); ?></label>
                <input type="email" id="subscriber-email" name="email" class="form-control" required>
            </div>
            
            <?php if (empty($atts['plan'])): ?>
            <div class="form-group mb-4">
                <label for="subscription-plan"><?php _e('Choose Plan', 'aqualuxe'); ?></label>
                <select id="subscription-plan" name="plan" class="form-control" required>
                    <option value=""><?php _e('Select a plan', 'aqualuxe'); ?></option>
                    <?php foreach ($this->config['membership_tiers'] as $tier_id => $tier): ?>
                        <option value="<?php echo esc_attr($tier_id); ?>">
                            <?php echo esc_html($tier['name']); ?> - $<?php echo esc_html($tier['price']); ?>/month
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php else: ?>
            <input type="hidden" name="plan" value="<?php echo esc_attr($atts['plan']); ?>">
            <?php endif; ?>

            <button type="submit" class="btn btn-primary">
                <?php _e('Subscribe Now', 'aqualuxe'); ?>
            </button>
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Filter content by membership
     */
    public function filter_content_by_membership($content) {
        if (!is_singular() || is_admin()) {
            return $content;
        }

        $post_id = get_the_ID();
        $required_plan = get_post_meta($post_id, '_required_subscription_plan', true);

        if (empty($required_plan)) {
            return $content;
        }

        if (!$this->user_has_subscription($required_plan)) {
            $message = sprintf(
                __('This content requires a %s subscription. <a href="%s">Subscribe now</a> to access premium content.', 'aqualuxe'),
                $this->config['membership_tiers'][$required_plan]['name'] ?? $required_plan,
                home_url('/subscriptions/')
            );

            return '<div class="subscription-required alert alert-info">' . $message . '</div>';
        }

        return $content;
    }

    /**
     * Check if user has subscription
     *
     * @param string $plan
     * @return bool
     */
    private function user_has_subscription($plan = '') {
        if (!is_user_logged_in()) {
            return false;
        }

        $user_id = get_current_user_id();
        $user_email = get_user_by('ID', $user_id)->user_email;

        $subscriptions = get_posts(array(
            'post_type' => 'aqualuxe_subscription',
            'meta_query' => array(
                array(
                    'key' => '_subscriber_email',
                    'value' => $user_email,
                    'compare' => '='
                ),
                array(
                    'key' => '_subscription_status',
                    'value' => 'active',
                    'compare' => '='
                )
            ),
            'posts_per_page' => 1
        ));

        return !empty($subscriptions);
    }
}

// Initialize module
AquaLuxe_Subscriptions_Module::get_instance();