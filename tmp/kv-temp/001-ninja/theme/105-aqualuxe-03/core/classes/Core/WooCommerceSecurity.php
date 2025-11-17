<?php
/**
 * Enhanced Security Functions for WooCommerce
 *
 * Additional security measures for WooCommerce integration
 *
 * @package AquaLuxe\Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class WooCommerceSecurity
 */
class WooCommerceSecurity {
    
    /**
     * Single instance of the class
     *
     * @var WooCommerceSecurity
     */
    private static $instance = null;

    /**
     * Rate limiting data
     *
     * @var array
     */
    private $rate_limits = array();

    /**
     * Get instance
     *
     * @return WooCommerceSecurity
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
        $this->init_security_hooks();
    }

    /**
     * Initialize security hooks
     */
    private function init_security_hooks() {
        // AJAX security
        add_action('wp_ajax_aqualuxe_add_to_cart', array($this, 'secure_ajax_add_to_cart'));
        add_action('wp_ajax_nopriv_aqualuxe_add_to_cart', array($this, 'secure_ajax_add_to_cart'));
        add_action('wp_ajax_aqualuxe_quick_view', array($this, 'secure_ajax_quick_view'));
        add_action('wp_ajax_nopriv_aqualuxe_quick_view', array($this, 'secure_ajax_quick_view'));
        add_action('wp_ajax_aqualuxe_wishlist_toggle', array($this, 'secure_ajax_wishlist_toggle'));
        add_action('wp_ajax_aqualuxe_track_order', array($this, 'secure_ajax_track_order'));
        add_action('wp_ajax_nopriv_aqualuxe_track_order', array($this, 'secure_ajax_track_order'));
        
        // Form security
        add_action('woocommerce_checkout_process', array($this, 'validate_checkout_security'));
        add_action('woocommerce_cart_actions', array($this, 'validate_cart_security'));
        
        // Rate limiting
        add_action('init', array($this, 'init_rate_limiting'));
        
        // Input sanitization
        add_filter('woocommerce_checkout_posted_data', array($this, 'sanitize_checkout_data'));
        
        // Headers security
        add_action('send_headers', array($this, 'send_security_headers'));
        
        // Anti-spam measures
        add_action('woocommerce_review_order_before_submit', array($this, 'add_honeypot_field'));
        add_action('woocommerce_checkout_process', array($this, 'check_honeypot_field'));
    }

    /**
     * Secure AJAX add to cart
     */
    public function secure_ajax_add_to_cart() {
        // Verify nonce
        if (!$this->verify_ajax_nonce('aqualuxe_ajax_nonce')) {
            wp_send_json_error(__('Security check failed.', 'aqualuxe'));
        }

        // Rate limiting
        if (!$this->check_rate_limit('add_to_cart', 10, 60)) {
            wp_send_json_error(__('Too many requests. Please try again later.', 'aqualuxe'));
        }

        // Sanitize input
        $product_id = $this->sanitize_product_id($_POST['product_id'] ?? 0);
        $quantity = $this->sanitize_quantity($_POST['quantity'] ?? 1);
        $variation_id = $this->sanitize_product_id($_POST['variation_id'] ?? 0);

        if (!$product_id) {
            wp_send_json_error(__('Invalid product.', 'aqualuxe'));
        }

        // Verify product exists and is purchasable
        $product = wc_get_product($product_id);
        if (!$product || !$product->is_purchasable()) {
            wp_send_json_error(__('Product is not available for purchase.', 'aqualuxe'));
        }

        // Check stock
        if (!$product->has_enough_stock($quantity)) {
            wp_send_json_error(__('Insufficient stock.', 'aqualuxe'));
        }

        // Process add to cart
        if (class_exists('WooCommerce')) {
            $result = WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
            
            if ($result) {
                wp_send_json_success(array(
                    'message' => __('Product added to cart successfully.', 'aqualuxe'),
                    'cart_count' => WC()->cart->get_cart_contents_count(),
                    'cart_total' => WC()->cart->get_cart_total(),
                    'fragments' => apply_filters('woocommerce_add_to_cart_fragments', array())
                ));
            }
        }

        wp_send_json_error(__('Failed to add product to cart.', 'aqualuxe'));
    }

    /**
     * Secure AJAX quick view
     */
    public function secure_ajax_quick_view() {
        // Verify nonce
        if (!$this->verify_ajax_nonce('aqualuxe_ajax_nonce')) {
            wp_send_json_error(__('Security check failed.', 'aqualuxe'));
        }

        // Rate limiting
        if (!$this->check_rate_limit('quick_view', 20, 60)) {
            wp_send_json_error(__('Too many requests. Please try again later.', 'aqualuxe'));
        }

        $product_id = $this->sanitize_product_id($_POST['product_id'] ?? 0);

        if (!$product_id) {
            wp_send_json_error(__('Invalid product.', 'aqualuxe'));
        }

        $product = wc_get_product($product_id);
        if (!$product || !$product->is_visible()) {
            wp_send_json_error(__('Product not found.', 'aqualuxe'));
        }

        // Get quick view content
        ob_start();
        $this->render_quick_view_content($product);
        $content = ob_get_clean();

        wp_send_json_success(array(
            'content' => $content,
            'title' => esc_html($product->get_name())
        ));
    }

    /**
     * Secure AJAX wishlist toggle
     */
    public function secure_ajax_wishlist_toggle() {
        // Check if user is logged in
        if (!is_user_logged_in()) {
            wp_send_json_error(__('You must be logged in to use the wishlist.', 'aqualuxe'));
        }

        // Verify nonce
        if (!$this->verify_ajax_nonce('aqualuxe_ajax_nonce')) {
            wp_send_json_error(__('Security check failed.', 'aqualuxe'));
        }

        // Rate limiting
        if (!$this->check_rate_limit('wishlist', 30, 60)) {
            wp_send_json_error(__('Too many requests. Please try again later.', 'aqualuxe'));
        }

        $product_id = $this->sanitize_product_id($_POST['product_id'] ?? 0);
        $action = sanitize_text_field($_POST['wishlist_action'] ?? 'add');

        if (!$product_id) {
            wp_send_json_error(__('Invalid product.', 'aqualuxe'));
        }

        // Verify product exists
        $product = wc_get_product($product_id);
        if (!$product) {
            wp_send_json_error(__('Product not found.', 'aqualuxe'));
        }

        // Process wishlist action
        $user_id = get_current_user_id();
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        $wishlist = is_array($wishlist) ? $wishlist : array();

        if ($action === 'add' && !in_array($product_id, $wishlist)) {
            $wishlist[] = $product_id;
            $message = __('Product added to wishlist.', 'aqualuxe');
        } elseif ($action === 'remove' && in_array($product_id, $wishlist)) {
            $wishlist = array_diff($wishlist, array($product_id));
            $message = __('Product removed from wishlist.', 'aqualuxe');
        } else {
            wp_send_json_error(__('Invalid action.', 'aqualuxe'));
        }

        update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);

        wp_send_json_success(array(
            'message' => $message,
            'wishlist_count' => count($wishlist),
            'in_wishlist' => in_array($product_id, $wishlist)
        ));
    }

    /**
     * Secure AJAX order tracking
     */
    public function secure_ajax_track_order() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['track_order_nonce'] ?? '', 'aqualuxe_track_order')) {
            wp_send_json_error(__('Security check failed.', 'aqualuxe'));
        }

        // Rate limiting
        if (!$this->check_rate_limit('track_order', 5, 300)) {
            wp_send_json_error(__('Too many tracking requests. Please try again in 5 minutes.', 'aqualuxe'));
        }

        $order_number = sanitize_text_field($_POST['order_number'] ?? '');
        $order_email = sanitize_email($_POST['order_email'] ?? '');

        if (!$order_number || !$order_email) {
            wp_send_json_error(__('Please provide both order number and email address.', 'aqualuxe'));
        }

        // Find order
        $order = $this->find_order_by_number_and_email($order_number, $order_email);

        if (!$order) {
            wp_send_json_error(__('Order not found. Please check your details and try again.', 'aqualuxe'));
        }

        // Generate tracking HTML
        $html = $this->generate_tracking_html($order);

        wp_send_json_success(array(
            'html' => $html,
            'order_id' => $order->get_id()
        ));
    }

    /**
     * Validate checkout security
     */
    public function validate_checkout_security() {
        // Check honeypot
        $this->check_honeypot_field();

        // Rate limiting for checkout attempts
        if (!$this->check_rate_limit('checkout', 3, 300)) {
            wc_add_notice(__('Too many checkout attempts. Please try again in 5 minutes.', 'aqualuxe'), 'error');
            return;
        }

        // Validate billing email format
        $billing_email = $_POST['billing_email'] ?? '';
        if ($billing_email && !is_email($billing_email)) {
            wc_add_notice(__('Please enter a valid email address.', 'aqualuxe'), 'error');
        }

        // Check for suspicious patterns
        $this->check_suspicious_checkout_data($_POST);
    }

    /**
     * Validate cart security
     */
    public function validate_cart_security() {
        // Verify cart nonce
        if (!wp_verify_nonce($_POST['woocommerce-cart-nonce'] ?? '', 'woocommerce-cart')) {
            wc_add_notice(__('Security check failed. Please refresh the page and try again.', 'aqualuxe'), 'error');
            return;
        }

        // Rate limiting
        if (!$this->check_rate_limit('cart_update', 10, 60)) {
            wc_add_notice(__('Too many cart updates. Please try again later.', 'aqualuxe'), 'error');
            return;
        }
    }

    /**
     * Sanitize checkout data
     */
    public function sanitize_checkout_data($data) {
        $sanitized = array();

        foreach ($data as $key => $value) {
            switch ($key) {
                case 'billing_email':
                case 'shipping_email':
                    $sanitized[$key] = sanitize_email($value);
                    break;
                    
                case 'billing_phone':
                case 'shipping_phone':
                    $sanitized[$key] = preg_replace('/[^0-9+\-\(\)\s]/', '', $value);
                    break;
                    
                case 'order_comments':
                    $sanitized[$key] = wp_kses_post($value);
                    break;
                    
                default:
                    $sanitized[$key] = sanitize_text_field($value);
                    break;
            }
        }

        return $sanitized;
    }

    /**
     * Send security headers
     */
    public function send_security_headers() {
        if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
            header('X-Content-Type-Options: nosniff');
            header('X-Frame-Options: SAMEORIGIN');
            header('X-XSS-Protection: 1; mode=block');
            header('Referrer-Policy: strict-origin-when-cross-origin');
            
            // Add CSP for checkout pages
            if (is_checkout()) {
                header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline';");
            }
        }
    }

    /**
     * Add honeypot field
     */
    public function add_honeypot_field() {
        echo '<input type="text" name="aqualuxe_honeypot" value="" style="position:absolute;left:-9999px;" tabindex="-1" autocomplete="off">';
        echo '<input type="hidden" name="aqualuxe_timestamp" value="' . esc_attr(time()) . '">';
    }

    /**
     * Check honeypot field
     */
    public function check_honeypot_field() {
        // Check honeypot
        if (!empty($_POST['aqualuxe_honeypot'])) {
            wc_add_notice(__('Spam detected. Please try again.', 'aqualuxe'), 'error');
            return false;
        }

        // Check form submission speed (should take at least 3 seconds)
        $timestamp = intval($_POST['aqualuxe_timestamp'] ?? 0);
        if ($timestamp && (time() - $timestamp) < 3) {
            wc_add_notice(__('Form submitted too quickly. Please try again.', 'aqualuxe'), 'error');
            return false;
        }

        return true;
    }

    /**
     * Initialize rate limiting
     */
    public function init_rate_limiting() {
        // Clean up old rate limit data
        $this->cleanup_rate_limits();
    }

    /**
     * Check rate limit
     */
    private function check_rate_limit($action, $limit, $window) {
        $ip = $this->get_client_ip();
        $key = $action . '_' . $ip;
        $now = time();

        if (!isset($this->rate_limits[$key])) {
            $this->rate_limits[$key] = array();
        }

        // Remove old entries
        $this->rate_limits[$key] = array_filter($this->rate_limits[$key], function($timestamp) use ($now, $window) {
            return ($now - $timestamp) < $window;
        });

        // Check if limit exceeded
        if (count($this->rate_limits[$key]) >= $limit) {
            return false;
        }

        // Add current request
        $this->rate_limits[$key][] = $now;
        return true;
    }

    /**
     * Verify AJAX nonce
     */
    private function verify_ajax_nonce($nonce_name) {
        return wp_verify_nonce($_POST['nonce'] ?? '', $nonce_name);
    }

    /**
     * Sanitize product ID
     */
    private function sanitize_product_id($product_id) {
        return absint($product_id);
    }

    /**
     * Sanitize quantity
     */
    private function sanitize_quantity($quantity) {
        return max(1, absint($quantity));
    }

    /**
     * Get client IP address
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

        return $_SERVER['REMOTE_ADDR'] ?? '';
    }

    /**
     * Check for suspicious checkout data
     */
    private function check_suspicious_checkout_data($data) {
        $suspicious_patterns = array(
            '/\b(test|example|fake|spam)\b/i',
            '/(.)\1{4,}/', // Repeated characters
            '/[^\w\s@.-]/', // Suspicious characters in names
        );

        $fields_to_check = array('billing_first_name', 'billing_last_name', 'billing_company');

        foreach ($fields_to_check as $field) {
            if (isset($data[$field])) {
                foreach ($suspicious_patterns as $pattern) {
                    if (preg_match($pattern, $data[$field])) {
                        wc_add_notice(__('Suspicious data detected. Please check your information.', 'aqualuxe'), 'error');
                        return;
                    }
                }
            }
        }
    }

    /**
     * Find order by number and email
     */
    private function find_order_by_number_and_email($order_number, $email) {
        $orders = wc_get_orders(array(
            'meta_key' => '_order_number',
            'meta_value' => $order_number,
            'meta_compare' => '=',
            'limit' => 1
        ));

        if (empty($orders)) {
            // Try without meta key (for standard order numbers)
            $orders = wc_get_orders(array(
                'number' => $order_number,
                'limit' => 1
            ));
        }

        foreach ($orders as $order) {
            if (strtolower($order->get_billing_email()) === strtolower($email)) {
                return $order;
            }
        }

        return false;
    }

    /**
     * Generate tracking HTML
     */
    private function generate_tracking_html($order) {
        ob_start();
        ?>
        <div class="order-tracking-results bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="order-header flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    <?php printf(esc_html__('Order #%s', 'aqualuxe'), $order->get_order_number()); ?>
                </h3>
                <span class="status-badge px-3 py-1 rounded-full text-sm font-medium <?php echo esc_attr($this->get_status_badge_class($order->get_status())); ?>">
                    <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>
                </span>
            </div>

            <div class="order-details grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white mb-2"><?php esc_html_e('Order Date', 'aqualuxe'); ?></h4>
                    <p class="text-gray-600 dark:text-gray-400"><?php echo esc_html($order->get_date_created()->date_i18n(get_option('date_format'))); ?></p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white mb-2"><?php esc_html_e('Total', 'aqualuxe'); ?></h4>
                    <p class="text-gray-600 dark:text-gray-400"><?php echo wp_kses_post($order->get_formatted_order_total()); ?></p>
                </div>
            </div>

            <div class="tracking-timeline">
                <?php $this->render_tracking_timeline($order); ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Get status badge class
     */
    private function get_status_badge_class($status) {
        switch ($status) {
            case 'processing':
                return 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100';
            case 'completed':
                return 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100';
            case 'on-hold':
                return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100';
            case 'cancelled':
                return 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100';
            default:
                return 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';
        }
    }

    /**
     * Render tracking timeline
     */
    private function render_tracking_timeline($order) {
        $statuses = array(
            'pending' => __('Order Received', 'aqualuxe'),
            'processing' => __('Processing', 'aqualuxe'),
            'shipped' => __('Shipped', 'aqualuxe'),
            'completed' => __('Delivered', 'aqualuxe')
        );

        $current_status = $order->get_status();
        $status_keys = array_keys($statuses);
        $current_index = array_search($current_status, $status_keys);

        echo '<div class="timeline space-y-4">';
        foreach ($statuses as $status => $label) {
            $index = array_search($status, $status_keys);
            $is_completed = $index <= $current_index || $current_status === 'completed';
            $is_current = $status === $current_status;

            echo '<div class="timeline-item flex items-center">';
            echo '<div class="timeline-marker w-6 h-6 rounded-full flex items-center justify-center ' . ($is_completed ? 'bg-green-600 text-white' : 'bg-gray-300 text-gray-600') . '">';
            if ($is_completed) {
                echo '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
            } else {
                echo ($index + 1);
            }
            echo '</div>';
            echo '<div class="timeline-content ml-4">';
            echo '<h5 class="font-medium ' . ($is_current ? 'text-primary-600' : 'text-gray-900 dark:text-white') . '">' . esc_html($label) . '</h5>';
            if ($is_current) {
                echo '<p class="text-sm text-primary-600">' . esc_html__('Current status', 'aqualuxe') . '</p>';
            }
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }

    /**
     * Render quick view content
     */
    private function render_quick_view_content($product) {
        // Use the existing quick view content from WooCommerceCompat class
        $wc_compat = \AquaLuxe\Core\WooCommerceCompat::get_instance();
        if (method_exists($wc_compat, 'render_quick_view_content')) {
            $reflection = new \ReflectionClass($wc_compat);
            $method = $reflection->getMethod('render_quick_view_content');
            $method->setAccessible(true);
            $method->invoke($wc_compat, $product);
        }
    }

    /**
     * Cleanup old rate limits
     */
    private function cleanup_rate_limits() {
        $now = time();
        foreach ($this->rate_limits as $key => $timestamps) {
            $this->rate_limits[$key] = array_filter($timestamps, function($timestamp) use ($now) {
                return ($now - $timestamp) < 3600; // Keep last hour
            });
            
            if (empty($this->rate_limits[$key])) {
                unset($this->rate_limits[$key]);
            }
        }
    }
}