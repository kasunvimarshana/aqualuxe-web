<?php
/**
 * Auctions Module
 * 
 * Handles auction functionality including trade-ins and bidding system
 * 
 * @package AquaLuxe
 * @subpackage Modules
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Auctions Module Class
 */
class AquaLuxe_Auctions_Module {
    
    /**
     * Module configuration
     */
    private $config = [
        'enabled' => true,
        'auction_duration_days' => 7,
        'min_bid_increment' => 1.00,
        'trade_in_enabled' => true,
        'auto_extend_minutes' => 5,
        'max_auction_images' => 10
    ];
    
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
        if (!$this->is_enabled()) {
            return;
        }
        
        $this->setup_hooks();
        $this->register_post_types();
        $this->register_taxonomies();
    }
    
    /**
     * Check if module is enabled
     */
    private function is_enabled() {
        return $this->config['enabled'] && apply_filters('aqualuxe_auctions_enabled', true);
    }
    
    /**
     * Setup hooks
     */
    private function setup_hooks() {
        add_action('init', [$this, 'register_post_types']);
        add_action('init', [$this, 'register_taxonomies']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_place_bid', [$this, 'ajax_place_bid']);
        add_action('wp_ajax_nopriv_place_bid', [$this, 'ajax_place_bid']);
        add_action('wp_ajax_submit_trade_in', [$this, 'ajax_submit_trade_in']);
        add_action('wp_ajax_nopriv_submit_trade_in', [$this, 'ajax_submit_trade_in']);
        add_action('aqualuxe_auction_ended', [$this, 'process_auction_end'], 10, 1);
        add_filter('aqualuxe_dashboard_modules', [$this, 'add_dashboard_widget']);
        
        // Cron hooks
        add_action('aqualuxe_check_auction_status', [$this, 'check_auction_status']);
        if (!wp_next_scheduled('aqualuxe_check_auction_status')) {
            wp_schedule_event(time(), 'hourly', 'aqualuxe_check_auction_status');
        }
    }
    
    /**
     * Register auction post type
     */
    public function register_post_types() {
        // Auction post type
        register_post_type('auction', [
            'labels' => [
                'name' => esc_html__('Auctions', 'aqualuxe'),
                'singular_name' => esc_html__('Auction', 'aqualuxe'),
                'add_new' => esc_html__('Add New Auction', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Auction', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Auction', 'aqualuxe'),
                'new_item' => esc_html__('New Auction', 'aqualuxe'),
                'view_item' => esc_html__('View Auction', 'aqualuxe'),
                'search_items' => esc_html__('Search Auctions', 'aqualuxe'),
                'not_found' => esc_html__('No auctions found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No auctions found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'auctions'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 25,
            'menu_icon' => 'dashicons-hammer',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'show_in_rest' => true,
        ]);
        
        // Trade-in post type
        register_post_type('trade_in', [
            'labels' => [
                'name' => esc_html__('Trade-ins', 'aqualuxe'),
                'singular_name' => esc_html__('Trade-in', 'aqualuxe'),
                'add_new' => esc_html__('Add New Trade-in', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Trade-in', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Trade-in', 'aqualuxe'),
                'new_item' => esc_html__('New Trade-in', 'aqualuxe'),
                'view_item' => esc_html__('View Trade-in', 'aqualuxe'),
                'search_items' => esc_html__('Search Trade-ins', 'aqualuxe'),
                'not_found' => esc_html__('No trade-ins found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No trade-ins found in trash', 'aqualuxe'),
            ],
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 26,
            'menu_icon' => 'dashicons-admin-generic',
            'supports' => ['title', 'editor', 'thumbnail', 'custom-fields'],
            'show_in_rest' => false,
        ]);
    }
    
    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Auction categories
        register_taxonomy('auction_category', 'auction', [
            'labels' => [
                'name' => esc_html__('Auction Categories', 'aqualuxe'),
                'singular_name' => esc_html__('Auction Category', 'aqualuxe'),
                'search_items' => esc_html__('Search Auction Categories', 'aqualuxe'),
                'all_items' => esc_html__('All Auction Categories', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Auction Category', 'aqualuxe'),
                'update_item' => esc_html__('Update Auction Category', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Auction Category', 'aqualuxe'),
                'new_item_name' => esc_html__('New Auction Category Name', 'aqualuxe'),
                'menu_name' => esc_html__('Categories', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'auction-category'],
            'show_in_rest' => true,
        ]);
        
        // Auction status taxonomy
        register_taxonomy('auction_status', 'auction', [
            'labels' => [
                'name' => esc_html__('Auction Status', 'aqualuxe'),
                'singular_name' => esc_html__('Status', 'aqualuxe'),
            ],
            'hierarchical' => false,
            'public' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => false,
            'show_in_rest' => false,
        ]);
    }
    
    /**
     * Enqueue module assets
     */
    public function enqueue_assets() {
        if (is_singular('auction') || is_post_type_archive('auction')) {
            wp_enqueue_script(
                'aqualuxe-auctions',
                aqualuxe_asset('js/modules/auctions.js'),
                ['jquery'],
                AQUALUXE_VERSION,
                true
            );
            
            wp_localize_script('aqualuxe-auctions', 'aqualuxeAuctions', [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_auctions'),
                'messages' => [
                    'bidPlaced' => esc_html__('Bid placed successfully!', 'aqualuxe'),
                    'bidError' => esc_html__('Error placing bid. Please try again.', 'aqualuxe'),
                    'minBidError' => esc_html__('Bid must be higher than current bid.', 'aqualuxe'),
                    'auctionEnded' => esc_html__('This auction has ended.', 'aqualuxe'),
                ],
            ]);
        }
    }
    
    /**
     * AJAX handler for placing bids
     */
    public function ajax_place_bid() {
        check_ajax_referer('aqualuxe_auctions', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(['message' => esc_html__('You must be logged in to place a bid.', 'aqualuxe')]);
        }
        
        $auction_id = intval($_POST['auction_id']);
        $bid_amount = floatval($_POST['bid_amount']);
        $user_id = get_current_user_id();
        
        // Validate auction
        if (!$this->is_auction_active($auction_id)) {
            wp_send_json_error(['message' => esc_html__('This auction is not active.', 'aqualuxe')]);
        }
        
        // Validate bid amount
        $current_bid = $this->get_current_bid($auction_id);
        $min_bid = $current_bid + $this->config['min_bid_increment'];
        
        if ($bid_amount < $min_bid) {
            wp_send_json_error([
                'message' => sprintf(
                    esc_html__('Minimum bid is %s', 'aqualuxe'),
                    function_exists('wc_price') ? wc_price($min_bid) : '$' . $min_bid
                )
            ]);
        }
        
        // Place bid
        $bid_id = $this->place_bid($auction_id, $user_id, $bid_amount);
        
        if ($bid_id) {
            // Check if auction should be extended
            $this->maybe_extend_auction($auction_id);
            
            wp_send_json_success([
                'message' => esc_html__('Bid placed successfully!', 'aqualuxe'),
                'new_bid' => function_exists('wc_price') ? wc_price($bid_amount) : '$' . $bid_amount,
                'bid_count' => $this->get_bid_count($auction_id),
            ]);
        } else {
            wp_send_json_error(['message' => esc_html__('Error placing bid. Please try again.', 'aqualuxe')]);
        }
    }
    
    /**
     * AJAX handler for trade-in submissions
     */
    public function ajax_submit_trade_in() {
        check_ajax_referer('aqualuxe_auctions', 'nonce');
        
        $data = wp_unslash($_POST);
        
        // Sanitize input
        $trade_in_data = [
            'item_name' => sanitize_text_field($data['item_name']),
            'item_description' => sanitize_textarea_field($data['item_description']),
            'estimated_value' => floatval($data['estimated_value']),
            'condition' => sanitize_text_field($data['condition']),
            'customer_name' => sanitize_text_field($data['customer_name']),
            'customer_email' => sanitize_email($data['customer_email']),
            'customer_phone' => sanitize_text_field($data['customer_phone']),
        ];
        
        // Create trade-in post
        $trade_in_id = wp_insert_post([
            'post_title' => $trade_in_data['item_name'],
            'post_content' => $trade_in_data['item_description'],
            'post_status' => 'pending',
            'post_type' => 'trade_in',
            'meta_input' => [
                'estimated_value' => $trade_in_data['estimated_value'],
                'condition' => $trade_in_data['condition'],
                'customer_name' => $trade_in_data['customer_name'],
                'customer_email' => $trade_in_data['customer_email'],
                'customer_phone' => $trade_in_data['customer_phone'],
                'submission_date' => current_time('mysql'),
                'status' => 'pending_review',
            ],
        ]);
        
        if ($trade_in_id) {
            // Send notification email
            $this->send_trade_in_notification($trade_in_id);
            
            wp_send_json_success([
                'message' => esc_html__('Trade-in request submitted successfully! We will contact you within 24 hours.', 'aqualuxe'),
                'trade_in_id' => $trade_in_id,
            ]);
        } else {
            wp_send_json_error(['message' => esc_html__('Error submitting trade-in request. Please try again.', 'aqualuxe')]);
        }
    }
    
    /**
     * Check if auction is active
     */
    private function is_auction_active($auction_id) {
        $end_date = get_post_meta($auction_id, 'auction_end_date', true);
        return !empty($end_date) && strtotime($end_date) > current_time('timestamp');
    }
    
    /**
     * Get current highest bid
     */
    private function get_current_bid($auction_id) {
        global $wpdb;
        
        $bid = $wpdb->get_var($wpdb->prepare(
            "SELECT MAX(bid_amount) FROM {$wpdb->prefix}auction_bids WHERE auction_id = %d",
            $auction_id
        ));
        
        return $bid ? floatval($bid) : floatval(get_post_meta($auction_id, 'starting_bid', true));
    }
    
    /**
     * Place a bid
     */
    private function place_bid($auction_id, $user_id, $bid_amount) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'auction_bids';
        
        return $wpdb->insert(
            $table_name,
            [
                'auction_id' => $auction_id,
                'user_id' => $user_id,
                'bid_amount' => $bid_amount,
                'bid_date' => current_time('mysql'),
            ],
            ['%d', '%d', '%f', '%s']
        );
    }
    
    /**
     * Get bid count for auction
     */
    private function get_bid_count($auction_id) {
        global $wpdb;
        
        return $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}auction_bids WHERE auction_id = %d",
            $auction_id
        ));
    }
    
    /**
     * Maybe extend auction if bid placed in final minutes
     */
    private function maybe_extend_auction($auction_id) {
        $end_date = get_post_meta($auction_id, 'auction_end_date', true);
        $extend_minutes = $this->config['auto_extend_minutes'];
        
        if ($end_date && (strtotime($end_date) - current_time('timestamp')) < ($extend_minutes * 60)) {
            $new_end_date = date('Y-m-d H:i:s', current_time('timestamp') + ($extend_minutes * 60));
            update_post_meta($auction_id, 'auction_end_date', $new_end_date);
        }
    }
    
    /**
     * Check auction status (cron job)
     */
    public function check_auction_status() {
        $active_auctions = get_posts([
            'post_type' => 'auction',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => 'auction_end_date',
                    'value' => current_time('mysql'),
                    'compare' => '<',
                    'type' => 'DATETIME',
                ],
            ],
        ]);
        
        foreach ($active_auctions as $auction) {
            $this->process_auction_end($auction->ID);
        }
    }
    
    /**
     * Process auction end
     */
    public function process_auction_end($auction_id) {
        // Get winning bid
        $winning_bid = $this->get_winning_bid($auction_id);
        
        if ($winning_bid) {
            // Update auction meta
            update_post_meta($auction_id, 'auction_status', 'completed');
            update_post_meta($auction_id, 'winning_bid_id', $winning_bid->id);
            update_post_meta($auction_id, 'winning_bid_amount', $winning_bid->bid_amount);
            update_post_meta($auction_id, 'winning_user_id', $winning_bid->user_id);
            
            // Send notification emails
            $this->send_auction_end_notifications($auction_id, $winning_bid);
        } else {
            // No bids received
            update_post_meta($auction_id, 'auction_status', 'ended_no_bids');
        }
        
        do_action('aqualuxe_auction_processed', $auction_id, $winning_bid);
    }
    
    /**
     * Get winning bid
     */
    private function get_winning_bid($auction_id) {
        global $wpdb;
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}auction_bids 
             WHERE auction_id = %d 
             ORDER BY bid_amount DESC, bid_date ASC 
             LIMIT 1",
            $auction_id
        ));
    }
    
    /**
     * Send auction end notifications
     */
    private function send_auction_end_notifications($auction_id, $winning_bid) {
        $auction = get_post($auction_id);
        $winner = get_user_by('ID', $winning_bid->user_id);
        
        // Send winner notification
        $subject = sprintf(esc_html__('Congratulations! You won the auction for %s', 'aqualuxe'), $auction->post_title);
        $message = sprintf(
            esc_html__('Congratulations! You have won the auction for "%s" with a bid of %s. We will contact you shortly with payment instructions.', 'aqualuxe'),
            $auction->post_title,
            function_exists('wc_price') ? wc_price($winning_bid->bid_amount) : '$' . $winning_bid->bid_amount
        );
        
        wp_mail($winner->user_email, $subject, $message);
        
        // Send admin notification
        $admin_email = get_option('admin_email');
        $admin_subject = sprintf(esc_html__('Auction ended: %s', 'aqualuxe'), $auction->post_title);
        $admin_message = sprintf(
            esc_html__('The auction for "%s" has ended. Winning bid: %s by %s (%s)', 'aqualuxe'),
            $auction->post_title,
            function_exists('wc_price') ? wc_price($winning_bid->bid_amount) : '$' . $winning_bid->bid_amount,
            $winner->display_name,
            $winner->user_email
        );
        
        wp_mail($admin_email, $admin_subject, $admin_message);
    }
    
    /**
     * Send trade-in notification
     */
    private function send_trade_in_notification($trade_in_id) {
        $trade_in = get_post($trade_in_id);
        $customer_email = get_post_meta($trade_in_id, 'customer_email', true);
        $admin_email = get_option('admin_email');
        
        // Send customer confirmation
        $subject = esc_html__('Trade-in Request Received', 'aqualuxe');
        $message = sprintf(
            esc_html__('Thank you for your trade-in request for "%s". We will review your submission and contact you within 24 hours with our evaluation.', 'aqualuxe'),
            $trade_in->post_title
        );
        
        wp_mail($customer_email, $subject, $message);
        
        // Send admin notification
        $admin_subject = sprintf(esc_html__('New Trade-in Request: %s', 'aqualuxe'), $trade_in->post_title);
        $admin_message = sprintf(
            esc_html__('A new trade-in request has been submitted for "%s". Please review it in the admin dashboard.', 'aqualuxe'),
            $trade_in->post_title
        );
        
        wp_mail($admin_email, $admin_subject, $admin_message);
    }
    
    /**
     * Add dashboard widget
     */
    public function add_dashboard_widget($widgets) {
        $widgets['auctions'] = [
            'title' => esc_html__('Auctions & Trade-ins', 'aqualuxe'),
            'callback' => [$this, 'render_dashboard_widget'],
            'priority' => 30,
        ];
        
        return $widgets;
    }
    
    /**
     * Render dashboard widget
     */
    public function render_dashboard_widget() {
        $active_auctions = wp_count_posts('auction');
        $pending_trades = get_posts([
            'post_type' => 'trade_in',
            'post_status' => 'pending',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ]);
        
        echo '<div class="aqualuxe-dashboard-widget">';
        echo '<div class="stats-row">';
        echo '<div class="stat-item">';
        echo '<span class="stat-number">' . intval($active_auctions->publish) . '</span>';
        echo '<span class="stat-label">' . esc_html__('Active Auctions', 'aqualuxe') . '</span>';
        echo '</div>';
        echo '<div class="stat-item">';
        echo '<span class="stat-number">' . count($pending_trades) . '</span>';
        echo '<span class="stat-label">' . esc_html__('Pending Trade-ins', 'aqualuxe') . '</span>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    
    /**
     * Get module configuration
     */
    public function get_config() {
        return $this->config;
    }
    
    /**
     * Update module configuration
     */
    public function update_config($config) {
        $this->config = array_merge($this->config, $config);
        update_option('aqualuxe_auctions_config', $this->config);
    }
}