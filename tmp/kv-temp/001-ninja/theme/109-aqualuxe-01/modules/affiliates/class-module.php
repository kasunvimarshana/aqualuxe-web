<?php
/**
 * Affiliates Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Affiliates;

use AquaLuxe\Core\Abstracts\Abstract_Module;

defined('ABSPATH') || exit;

/**
 * Affiliates Module Class
 */
class Module extends Abstract_Module {

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Affiliates';

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
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_ajax_aqualuxe_affiliate_registration', array($this, 'handle_affiliate_registration'));
        add_action('wp_ajax_nopriv_aqualuxe_affiliate_registration', array($this, 'handle_affiliate_registration'));
        add_action('template_redirect', array($this, 'track_affiliate_visits'));
        add_shortcode('aqualuxe_affiliate_dashboard', array($this, 'affiliate_dashboard_shortcode'));
        add_shortcode('aqualuxe_affiliate_registration', array($this, 'affiliate_registration_shortcode'));
        
        // WooCommerce integration
        if (class_exists('WooCommerce')) {
            add_action('woocommerce_order_status_completed', array($this, 'process_affiliate_commission'));
        }
        
        // Create affiliate tables
        add_action('init', array($this, 'create_affiliate_tables'));
    }

    /**
     * Create affiliate database tables
     */
    public function create_affiliate_tables() {
        global $wpdb;

        // Affiliate links table
        $links_table = $wpdb->prefix . 'aqualuxe_affiliate_links';
        $visits_table = $wpdb->prefix . 'aqualuxe_affiliate_visits';
        $commissions_table = $wpdb->prefix . 'aqualuxe_affiliate_commissions';

        $charset_collate = $wpdb->get_charset_collate();

        $sql_links = "CREATE TABLE $links_table (
            id int(11) NOT NULL AUTO_INCREMENT,
            affiliate_id int(11) NOT NULL,
            link_code varchar(50) UNIQUE,
            target_url text NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY affiliate_id (affiliate_id),
            KEY link_code (link_code)
        ) $charset_collate;";

        $sql_visits = "CREATE TABLE $visits_table (
            id int(11) NOT NULL AUTO_INCREMENT,
            affiliate_id int(11) NOT NULL,
            link_code varchar(50),
            visitor_ip varchar(45),
            referrer text,
            user_agent text,
            converted tinyint(1) DEFAULT 0,
            order_id int(11) DEFAULT NULL,
            visit_date datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY affiliate_id (affiliate_id),
            KEY link_code (link_code),
            KEY visit_date (visit_date)
        ) $charset_collate;";

        $sql_commissions = "CREATE TABLE $commissions_table (
            id int(11) NOT NULL AUTO_INCREMENT,
            affiliate_id int(11) NOT NULL,
            order_id int(11) NOT NULL,
            commission_amount decimal(10,2) NOT NULL,
            commission_rate decimal(5,2) NOT NULL,
            status varchar(20) DEFAULT 'pending',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            paid_at datetime DEFAULT NULL,
            PRIMARY KEY (id),
            KEY affiliate_id (affiliate_id),
            KEY order_id (order_id),
            KEY status (status)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_links);
        dbDelta($sql_visits);
        dbDelta($sql_commissions);
    }

    /**
     * Register affiliate user roles
     */
    public function register_user_roles() {
        add_role('affiliate_partner', __('Affiliate Partner', 'aqualuxe'), array(
            'read' => true,
            'view_affiliate_dashboard' => true,
            'generate_affiliate_links' => true,
        ));
    }

    /**
     * Register affiliate post type for applications
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => _x('Affiliate Applications', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Application', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Affiliates', 'Admin Menu text', 'aqualuxe'),
            'add_new_item'          => __('Add New Application', 'aqualuxe'),
            'edit_item'             => __('Edit Application', 'aqualuxe'),
            'view_item'             => __('View Application', 'aqualuxe'),
            'all_items'             => __('All Applications', 'aqualuxe'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 32,
            'menu_icon'          => 'dashicons-groups',
            'supports'           => array('title', 'custom-fields'),
        );

        register_post_type('aqualuxe_affiliate_app', $args);
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        if (is_page() || is_user_logged_in()) {
            wp_enqueue_script('aqualuxe-affiliates', $this->get_url() . '/assets/affiliates.js', array('jquery'), '1.0.0', true);
            wp_enqueue_style('aqualuxe-affiliates', $this->get_url() . '/assets/affiliates.css', array(), '1.0.0');
            
            wp_localize_script('aqualuxe-affiliates', 'aqualuxe_affiliates', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_affiliates_nonce'),
                'is_affiliate' => current_user_can('view_affiliate_dashboard'),
            ));
        }
    }

    /**
     * Track affiliate visits
     */
    public function track_affiliate_visits() {
        if (isset($_GET['ref']) && !empty($_GET['ref'])) {
            $affiliate_code = sanitize_text_field($_GET['ref']);
            
            // Store affiliate code in session/cookie
            if (!session_id()) {
                session_start();
            }
            $_SESSION['affiliate_code'] = $affiliate_code;
            setcookie('aqualuxe_affiliate', $affiliate_code, time() + (30 * 24 * 60 * 60), '/'); // 30 days

            // Track visit
            global $wpdb;
            $visits_table = $wpdb->prefix . 'aqualuxe_affiliate_visits';
            
            $affiliate_id = $this->get_affiliate_by_code($affiliate_code);
            
            if ($affiliate_id) {
                $wpdb->insert(
                    $visits_table,
                    array(
                        'affiliate_id' => $affiliate_id,
                        'link_code' => $affiliate_code,
                        'visitor_ip' => $_SERVER['REMOTE_ADDR'],
                        'referrer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
                        'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                    ),
                    array('%d', '%s', '%s', '%s', '%s')
                );
            }
        }
    }

    /**
     * Get affiliate ID by code
     */
    private function get_affiliate_by_code($code) {
        global $wpdb;
        $links_table = $wpdb->prefix . 'aqualuxe_affiliate_links';
        
        return $wpdb->get_var($wpdb->prepare(
            "SELECT affiliate_id FROM $links_table WHERE link_code = %s",
            $code
        ));
    }

    /**
     * Process affiliate commission
     */
    public function process_affiliate_commission($order_id) {
        // Check if order came from affiliate
        $affiliate_code = '';
        
        if (!session_id()) {
            session_start();
        }
        
        if (isset($_SESSION['affiliate_code'])) {
            $affiliate_code = $_SESSION['affiliate_code'];
        } elseif (isset($_COOKIE['aqualuxe_affiliate'])) {
            $affiliate_code = $_COOKIE['aqualuxe_affiliate'];
        }

        if (!$affiliate_code) {
            return;
        }

        $affiliate_id = $this->get_affiliate_by_code($affiliate_code);
        if (!$affiliate_id) {
            return;
        }

        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }

        $commission_rate = get_option('aqualuxe_affiliate_commission_rate', 5); // Default 5%
        $order_total = $order->get_total();
        $commission_amount = ($order_total * $commission_rate) / 100;

        // Save commission
        global $wpdb;
        $commissions_table = $wpdb->prefix . 'aqualuxe_affiliate_commissions';
        
        $wpdb->insert(
            $commissions_table,
            array(
                'affiliate_id' => $affiliate_id,
                'order_id' => $order_id,
                'commission_amount' => $commission_amount,
                'commission_rate' => $commission_rate,
                'status' => 'pending',
            ),
            array('%d', '%d', '%f', '%f', '%s')
        );

        // Mark visit as converted
        $visits_table = $wpdb->prefix . 'aqualuxe_affiliate_visits';
        $wpdb->update(
            $visits_table,
            array('converted' => 1, 'order_id' => $order_id),
            array('affiliate_id' => $affiliate_id, 'link_code' => $affiliate_code),
            array('%d', '%d'),
            array('%d', '%s')
        );

        // Clear affiliate tracking
        unset($_SESSION['affiliate_code']);
        setcookie('aqualuxe_affiliate', '', time() - 3600, '/');
    }

    /**
     * Handle affiliate registration
     */
    public function handle_affiliate_registration() {
        check_ajax_referer('aqualuxe_affiliates_nonce', 'nonce');

        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $website = sanitize_url($_POST['website']);
        $promotion_method = sanitize_textarea_field($_POST['promotion_method']);

        if (!$name || !$email) {
            wp_send_json_error('Please fill in all required fields');
        }

        // Create application
        $application_data = array(
            'post_type' => 'aqualuxe_affiliate_app',
            'post_title' => $name . ' - ' . $email,
            'post_status' => 'pending',
            'meta_input' => array(
                '_applicant_name' => $name,
                '_applicant_email' => $email,
                '_applicant_website' => $website,
                '_promotion_method' => $promotion_method,
                '_application_date' => current_time('mysql')
            )
        );

        $application_id = wp_insert_post($application_data);

        if ($application_id) {
            wp_send_json_success('Application submitted successfully');
        } else {
            wp_send_json_error('Failed to submit application');
        }
    }

    /**
     * Affiliate dashboard shortcode
     */
    public function affiliate_dashboard_shortcode($atts) {
        if (!current_user_can('view_affiliate_dashboard')) {
            return '<p>' . __('Access denied. Please log in as an affiliate partner.', 'aqualuxe') . '</p>';
        }

        ob_start();
        $this->load_template('affiliate-dashboard', array('user_id' => get_current_user_id()));
        return ob_get_clean();
    }

    /**
     * Affiliate registration shortcode
     */
    public function affiliate_registration_shortcode($atts) {
        ob_start();
        $this->load_template('affiliate-registration-form');
        return ob_get_clean();
    }
}