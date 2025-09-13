<?php
/**
 * Affiliate Module
 *
 * Handles affiliate marketing and referral system
 *
 * @package AquaLuxe\Modules\Affiliate
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class AquaLuxe_Affiliate_Module
 *
 * Manages affiliate and referral functionality
 */
class AquaLuxe_Affiliate_Module {
    
    /**
     * Single instance of the class
     *
     * @var AquaLuxe_Affiliate_Module
     */
    private static $instance = null;

    /**
     * Module configuration
     *
     * @var array
     */
    private $config = array(
        'enabled' => true,
        'commission_rates' => array(
            'default' => 5.0,
            'tier_1' => 7.5,  // $1000+ sales
            'tier_2' => 10.0, // $5000+ sales
            'tier_3' => 12.5  // $10000+ sales
        ),
        'cookie_duration' => 30, // days
        'minimum_payout' => 50.00,
        'payout_schedule' => 'monthly' // weekly, monthly, quarterly
    );

    /**
     * Get instance
     *
     * @return AquaLuxe_Affiliate_Module
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
        add_action('init', array($this, 'handle_referral_tracking'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_aqualuxe_register_affiliate', array($this, 'handle_affiliate_registration'));
        add_action('wp_ajax_nopriv_aqualuxe_register_affiliate', array($this, 'handle_affiliate_registration'));
        add_action('wp_ajax_aqualuxe_generate_referral_link', array($this, 'generate_referral_link'));
        
        // WooCommerce integration
        if (class_exists('WooCommerce')) {
            add_action('woocommerce_thankyou', array($this, 'track_affiliate_sale'));
            add_action('woocommerce_order_status_completed', array($this, 'process_affiliate_commission'));
        }

        // Shortcodes
        add_shortcode('aqualuxe_affiliate_dashboard', array($this, 'affiliate_dashboard_shortcode'));
        add_shortcode('aqualuxe_affiliate_registration', array($this, 'affiliate_registration_shortcode'));
        add_shortcode('aqualuxe_referral_link_generator', array($this, 'referral_link_generator_shortcode'));

        // User profile fields
        add_action('show_user_profile', array($this, 'add_affiliate_fields'));
        add_action('edit_user_profile', array($this, 'add_affiliate_fields'));
        add_action('personal_options_update', array($this, 'save_affiliate_fields'));
        add_action('edit_user_profile_update', array($this, 'save_affiliate_fields'));
    }

    /**
     * Register custom post types
     */
    public function register_post_types() {
        // Affiliate registrations
        register_post_type('aqualuxe_affiliate', array(
            'labels' => array(
                'name' => __('Affiliates', 'aqualuxe'),
                'singular_name' => __('Affiliate', 'aqualuxe'),
                'menu_name' => __('Affiliates', 'aqualuxe')
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'capability_type' => 'post',
            'supports' => array('title', 'custom-fields'),
            'menu_icon' => 'dashicons-groups'
        ));

        // Referrals tracking
        register_post_type('aqualuxe_referral', array(
            'labels' => array(
                'name' => __('Referrals', 'aqualuxe'),
                'singular_name' => __('Referral', 'aqualuxe'),
                'menu_name' => __('Referrals', 'aqualuxe')
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=aqualuxe_affiliate',
            'capability_type' => 'post',
            'supports' => array('title', 'custom-fields'),
            'menu_icon' => 'dashicons-networking'
        ));

        // Commission tracking
        register_post_type('aqualuxe_commission', array(
            'labels' => array(
                'name' => __('Commissions', 'aqualuxe'),
                'singular_name' => __('Commission', 'aqualuxe'),
                'menu_name' => __('Commissions', 'aqualuxe')
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=aqualuxe_affiliate',
            'capability_type' => 'post',
            'supports' => array('title', 'custom-fields'),
            'menu_icon' => 'dashicons-money-alt'
        ));
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'aqualuxe-affiliate',
            AQUALUXE_ASSETS_URI . '/js/modules/affiliate.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );

        wp_localize_script('aqualuxe-affiliate', 'aqualuxe_affiliate', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_affiliate_nonce'),
            'commission_rates' => $this->config['commission_rates'],
            'messages' => array(
                'registering' => __('Registering as affiliate...', 'aqualuxe'),
                'success' => __('Successfully registered as affiliate!', 'aqualuxe'),
                'error' => __('Registration failed. Please try again.', 'aqualuxe'),
                'generating_link' => __('Generating referral link...', 'aqualuxe'),
                'link_generated' => __('Referral link generated!', 'aqualuxe')
            )
        ));
    }

    /**
     * Handle referral tracking
     */
    public function handle_referral_tracking() {
        if (isset($_GET['ref']) && !is_admin()) {
            $affiliate_code = sanitize_text_field($_GET['ref']);
            
            // Set referral cookie
            setcookie(
                'aqualuxe_referral',
                $affiliate_code,
                time() + ($this->config['cookie_duration'] * DAY_IN_SECONDS),
                '/'
            );

            // Track the referral
            $this->track_referral($affiliate_code);
        }
    }

    /**
     * Track referral visit
     *
     * @param string $affiliate_code
     */
    private function track_referral($affiliate_code) {
        $affiliate = $this->get_affiliate_by_code($affiliate_code);
        
        if (!$affiliate) {
            return;
        }

        // Create referral record
        wp_insert_post(array(
            'post_type' => 'aqualuxe_referral',
            'post_title' => sprintf(__('Referral visit for %s', 'aqualuxe'), $affiliate_code),
            'post_status' => 'publish',
            'meta_input' => array(
                '_affiliate_id' => $affiliate->ID,
                '_affiliate_code' => $affiliate_code,
                '_visitor_ip' => $this->get_client_ip(),
                '_visit_date' => current_time('mysql'),
                '_referring_url' => $_SERVER['HTTP_REFERER'] ?? '',
                '_landing_page' => $_SERVER['REQUEST_URI'] ?? ''
            )
        ));
    }

    /**
     * Handle affiliate registration
     */
    public function handle_affiliate_registration() {
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_affiliate_nonce')) {
            wp_send_json_error(__('Security check failed.', 'aqualuxe'));
        }

        $name = sanitize_text_field($_POST['name'] ?? '');
        $email = sanitize_email($_POST['email'] ?? '');
        $website = esc_url_raw($_POST['website'] ?? '');
        $promotion_method = sanitize_textarea_field($_POST['promotion_method'] ?? '');

        if (empty($name) || empty($email)) {
            wp_send_json_error(__('Please fill in all required fields.', 'aqualuxe'));
        }

        // Check if already registered
        if ($this->email_already_registered($email)) {
            wp_send_json_error(__('This email is already registered as an affiliate.', 'aqualuxe'));
        }

        $affiliate_id = $this->create_affiliate($name, $email, $website, $promotion_method);
        
        if ($affiliate_id) {
            wp_send_json_success(array(
                'message' => __('Affiliate registration successful!', 'aqualuxe'),
                'affiliate_id' => $affiliate_id
            ));
        } else {
            wp_send_json_error(__('Failed to register affiliate.', 'aqualuxe'));
        }
    }

    /**
     * Create affiliate
     *
     * @param string $name
     * @param string $email
     * @param string $website
     * @param string $promotion_method
     * @return int|false
     */
    private function create_affiliate($name, $email, $website, $promotion_method) {
        $affiliate_code = $this->generate_affiliate_code($email);
        
        $affiliate_id = wp_insert_post(array(
            'post_type' => 'aqualuxe_affiliate',
            'post_title' => sprintf(__('Affiliate: %s', 'aqualuxe'), $name),
            'post_status' => 'pending', // Requires approval
            'meta_input' => array(
                '_affiliate_name' => $name,
                '_affiliate_email' => $email,
                '_affiliate_website' => $website,
                '_affiliate_code' => $affiliate_code,
                '_promotion_method' => $promotion_method,
                '_registration_date' => current_time('mysql'),
                '_commission_rate' => $this->config['commission_rates']['default'],
                '_total_sales' => 0,
                '_total_commission' => 0,
                '_status' => 'pending'
            )
        ));

        if ($affiliate_id && !is_wp_error($affiliate_id)) {
            // Send notification email to admin
            $this->send_affiliate_registration_notification($affiliate_id);
            
            // Send welcome email to affiliate
            $this->send_affiliate_welcome_email($email, $name, $affiliate_code);
            
            return $affiliate_id;
        }

        return false;
    }

    /**
     * Generate affiliate code
     *
     * @param string $email
     * @return string
     */
    private function generate_affiliate_code($email) {
        $base = substr(md5($email), 0, 8);
        $counter = 1;
        $code = strtoupper($base);
        
        // Ensure uniqueness
        while ($this->affiliate_code_exists($code)) {
            $code = strtoupper($base . $counter);
            $counter++;
        }
        
        return $code;
    }

    /**
     * Check if affiliate code exists
     *
     * @param string $code
     * @return bool
     */
    private function affiliate_code_exists($code) {
        $affiliates = get_posts(array(
            'post_type' => 'aqualuxe_affiliate',
            'meta_query' => array(
                array(
                    'key' => '_affiliate_code',
                    'value' => $code,
                    'compare' => '='
                )
            ),
            'posts_per_page' => 1
        ));

        return !empty($affiliates);
    }

    /**
     * Track affiliate sale
     *
     * @param int $order_id
     */
    public function track_affiliate_sale($order_id) {
        if (!isset($_COOKIE['aqualuxe_referral'])) {
            return;
        }

        $affiliate_code = sanitize_text_field($_COOKIE['aqualuxe_referral']);
        $affiliate = $this->get_affiliate_by_code($affiliate_code);
        
        if (!$affiliate) {
            return;
        }

        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }

        // Create sale record
        wp_insert_post(array(
            'post_type' => 'aqualuxe_commission',
            'post_title' => sprintf(__('Sale #%s for %s', 'aqualuxe'), $order_id, $affiliate_code),
            'post_status' => 'pending',
            'meta_input' => array(
                '_affiliate_id' => $affiliate->ID,
                '_order_id' => $order_id,
                '_sale_amount' => $order->get_total(),
                '_commission_rate' => get_post_meta($affiliate->ID, '_commission_rate', true),
                '_commission_amount' => $this->calculate_commission($order->get_total(), $affiliate->ID),
                '_sale_date' => current_time('mysql'),
                '_status' => 'pending'
            )
        ));
    }

    /**
     * Calculate commission
     *
     * @param float $sale_amount
     * @param int $affiliate_id
     * @return float
     */
    private function calculate_commission($sale_amount, $affiliate_id) {
        $commission_rate = get_post_meta($affiliate_id, '_commission_rate', true);
        return ($sale_amount * $commission_rate) / 100;
    }

    /**
     * Get affiliate by code
     *
     * @param string $code
     * @return WP_Post|null
     */
    private function get_affiliate_by_code($code) {
        $affiliates = get_posts(array(
            'post_type' => 'aqualuxe_affiliate',
            'meta_query' => array(
                array(
                    'key' => '_affiliate_code',
                    'value' => $code,
                    'compare' => '='
                ),
                array(
                    'key' => '_status',
                    'value' => 'approved',
                    'compare' => '='
                )
            ),
            'posts_per_page' => 1
        ));

        return !empty($affiliates) ? $affiliates[0] : null;
    }

    /**
     * Get client IP
     *
     * @return string
     */
    private function get_client_ip() {
        $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Check if email already registered
     *
     * @param string $email
     * @return bool
     */
    private function email_already_registered($email) {
        $affiliates = get_posts(array(
            'post_type' => 'aqualuxe_affiliate',
            'meta_query' => array(
                array(
                    'key' => '_affiliate_email',
                    'value' => $email,
                    'compare' => '='
                )
            ),
            'posts_per_page' => 1
        ));

        return !empty($affiliates);
    }

    /**
     * Send affiliate registration notification
     *
     * @param int $affiliate_id
     */
    private function send_affiliate_registration_notification($affiliate_id) {
        $admin_email = get_option('admin_email');
        $affiliate_name = get_post_meta($affiliate_id, '_affiliate_name', true);
        
        $subject = sprintf(__('New Affiliate Registration: %s', 'aqualuxe'), $affiliate_name);
        $message = sprintf(
            __('A new affiliate has registered and is pending approval. Please review the application in the WordPress admin.', 'aqualuxe')
        );

        wp_mail($admin_email, $subject, $message);
    }

    /**
     * Send affiliate welcome email
     *
     * @param string $email
     * @param string $name
     * @param string $code
     */
    private function send_affiliate_welcome_email($email, $name, $code) {
        $subject = __('Welcome to AquaLuxe Affiliate Program', 'aqualuxe');
        $message = sprintf(
            __('Hi %s,\n\nThank you for joining the AquaLuxe affiliate program! Your affiliate code is: %s\n\nYour application is currently under review and you will be notified once approved.\n\nBest regards,\nAquaLuxe Team', 'aqualuxe'),
            $name,
            $code
        );

        wp_mail($email, $subject, $message);
    }

    /**
     * Affiliate dashboard shortcode
     */
    public function affiliate_dashboard_shortcode($atts = array()) {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please log in to access your affiliate dashboard.', 'aqualuxe') . '</p>';
        }

        $user_email = wp_get_current_user()->user_email;
        $affiliate = $this->get_affiliate_by_email($user_email);

        if (!$affiliate) {
            return '<p>' . __('You are not registered as an affiliate.', 'aqualuxe') . ' <a href="#register">' . __('Register now', 'aqualuxe') . '</a></p>';
        }

        ob_start();
        ?>
        <div class="affiliate-dashboard">
            <h3><?php _e('Affiliate Dashboard', 'aqualuxe'); ?></h3>
            
            <div class="affiliate-stats grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="stat-card bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h4><?php _e('Total Sales', 'aqualuxe'); ?></h4>
                    <div class="stat-value">$<?php echo number_format(get_post_meta($affiliate->ID, '_total_sales', true), 2); ?></div>
                </div>
                <div class="stat-card bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h4><?php _e('Total Commission', 'aqualuxe'); ?></h4>
                    <div class="stat-value">$<?php echo number_format(get_post_meta($affiliate->ID, '_total_commission', true), 2); ?></div>
                </div>
                <div class="stat-card bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h4><?php _e('Commission Rate', 'aqualuxe'); ?></h4>
                    <div class="stat-value"><?php echo get_post_meta($affiliate->ID, '_commission_rate', true); ?>%</div>
                </div>
            </div>

            <div class="referral-link-section mb-8">
                <h4><?php _e('Your Referral Link', 'aqualuxe'); ?></h4>
                <div class="referral-link-container">
                    <input type="text" 
                           class="referral-link form-control" 
                           value="<?php echo home_url('/?ref=' . get_post_meta($affiliate->ID, '_affiliate_code', true)); ?>" 
                           readonly>
                    <button class="copy-link-btn btn btn-secondary"><?php _e('Copy', 'aqualuxe'); ?></button>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Affiliate registration shortcode
     */
    public function affiliate_registration_shortcode($atts = array()) {
        ob_start();
        ?>
        <form id="affiliate-registration-form" class="affiliate-registration-form">
            <div class="form-group mb-4">
                <label for="affiliate-name"><?php _e('Full Name', 'aqualuxe'); ?> *</label>
                <input type="text" id="affiliate-name" name="name" class="form-control" required>
            </div>
            
            <div class="form-group mb-4">
                <label for="affiliate-email"><?php _e('Email Address', 'aqualuxe'); ?> *</label>
                <input type="email" id="affiliate-email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group mb-4">
                <label for="affiliate-website"><?php _e('Website/Blog URL', 'aqualuxe'); ?></label>
                <input type="url" id="affiliate-website" name="website" class="form-control">
            </div>
            
            <div class="form-group mb-4">
                <label for="promotion-method"><?php _e('How do you plan to promote our products?', 'aqualuxe'); ?></label>
                <textarea id="promotion-method" name="promotion_method" class="form-control" rows="4"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                <?php _e('Register as Affiliate', 'aqualuxe'); ?>
            </button>
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Get affiliate by email
     *
     * @param string $email
     * @return WP_Post|null
     */
    private function get_affiliate_by_email($email) {
        $affiliates = get_posts(array(
            'post_type' => 'aqualuxe_affiliate',
            'meta_query' => array(
                array(
                    'key' => '_affiliate_email',
                    'value' => $email,
                    'compare' => '='
                )
            ),
            'posts_per_page' => 1
        ));

        return !empty($affiliates) ? $affiliates[0] : null;
    }

    /**
     * Add affiliate fields to user profile
     */
    public function add_affiliate_fields($user) {
        $affiliate = $this->get_affiliate_by_email($user->user_email);
        ?>
        <h3><?php _e('Affiliate Information', 'aqualuxe'); ?></h3>
        <table class="form-table">
            <tr>
                <th><label><?php _e('Affiliate Status', 'aqualuxe'); ?></label></th>
                <td>
                    <?php if ($affiliate): ?>
                        <strong><?php echo ucfirst(get_post_meta($affiliate->ID, '_status', true)); ?></strong>
                        <?php if (get_post_meta($affiliate->ID, '_status', true) === 'approved'): ?>
                            <br><small><?php _e('Affiliate Code:', 'aqualuxe'); ?> <?php echo get_post_meta($affiliate->ID, '_affiliate_code', true); ?></small>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php _e('Not an affiliate', 'aqualuxe'); ?>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save affiliate fields (placeholder for future admin functionality)
     */
    public function save_affiliate_fields($user_id) {
        // This can be extended for admin management of affiliate status
    }
}

// Initialize module
AquaLuxe_Affiliate_Module::get_instance();