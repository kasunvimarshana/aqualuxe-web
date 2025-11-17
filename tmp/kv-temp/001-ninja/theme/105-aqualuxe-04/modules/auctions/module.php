<?php
/**
 * Auctions/Trade-ins Module
 *
 * Handles auction and trade-in functionality for premium aquatic items
 *
 * @package AquaLuxe\Modules\Auctions
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class AquaLuxe_Auctions_Module
 *
 * Manages auctions and trade-in operations
 */
class AquaLuxe_Auctions_Module {
    
    /**
     * Single instance of the class
     *
     * @var AquaLuxe_Auctions_Module
     */
    private static $instance = null;

    /**
     * Module configuration
     *
     * @var array
     */
    private $config = array(
        'name'        => 'Auctions/Trade-ins',
        'version'     => '1.0.0',
        'description' => 'Auction and trade-in functionality for premium aquatic items',
        'enabled'     => true,
        'dependencies' => array(),
    );

    /**
     * Get instance
     *
     * @return AquaLuxe_Auctions_Module
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
        if ($this->config['enabled']) {
            $this->init_hooks();
        }
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_ajax_aqualuxe_auction_bid', array($this, 'handle_auction_bid'));
        add_action('wp_ajax_aqualuxe_trade_inquiry', array($this, 'handle_trade_inquiry'));
        add_action('wp_ajax_nopriv_aqualuxe_trade_inquiry', array($this, 'handle_trade_inquiry'));
        
        // Auction management
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_auction_meta'));
        
        // Cron jobs for auction management
        add_action('wp', array($this, 'schedule_auction_events'));
        add_action('aqualuxe_check_auction_endings', array($this, 'check_auction_endings'));
        add_action('aqualuxe_send_auction_reminders', array($this, 'send_auction_reminders'));
        
        // Frontend display
        add_filter('single_template', array($this, 'auction_single_template'));
        add_shortcode('auction_listing', array($this, 'render_auction_listing_shortcode'));
        add_shortcode('trade_form', array($this, 'render_trade_form_shortcode'));
        
        // User capabilities
        add_action('init', array($this, 'create_auction_user_roles'));
    }

    /**
     * Register custom post types
     */
    public function register_post_types() {
        // Auction Items
        register_post_type('aqualuxe_auction', array(
            'label'               => __('Auction Item', 'aqualuxe'),
            'description'         => __('Premium aquatic items for auction', 'aqualuxe'),
            'labels'              => aqualuxe_get_post_type_labels('Auction Item', 'Auction Items', 'Item Image'),
            'supports'            => array('title', 'editor', 'thumbnail', 'gallery', 'custom-fields', 'author'),
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest'        => true,
            'menu_icon'           => 'dashicons-hammer',
            'menu_position'       => 27,
            'rewrite'             => array('slug' => 'auctions', 'with_front' => false),
        ));

        // Trade-in Inquiries
        register_post_type('aqualuxe_trade', array(
            'label'               => __('Trade-in Inquiry', 'aqualuxe'),
            'description'         => __('Customer trade-in requests and valuations', 'aqualuxe'),
            'labels'              => aqualuxe_get_post_type_labels('Trade-in Inquiry', 'Trade-in Inquiries'),
            'supports'            => array('title', 'editor', 'custom-fields', 'author'),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => 'edit.php?post_type=aqualuxe_auction',
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capability_type'     => 'post',
            'show_in_rest'        => false,
            'menu_icon'           => 'dashicons-randomize',
        ));

        // Auction Bids
        register_post_type('aqualuxe_bid', array(
            'label'               => __('Auction Bid', 'aqualuxe'),
            'description'         => __('Bids placed on auction items', 'aqualuxe'),
            'labels'              => aqualuxe_get_post_type_labels('Auction Bid', 'Auction Bids'),
            'supports'            => array('custom-fields', 'author'),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => 'edit.php?post_type=aqualuxe_auction',
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capability_type'     => 'post',
            'show_in_rest'        => false,
            'menu_icon'           => 'dashicons-money',
        ));
    }

    /**
     * Register custom taxonomies
     */
    public function register_taxonomies() {
        // Auction Categories
        register_taxonomy('auction_category', array('aqualuxe_auction'), array(
            'labels' => array(
                'name'          => __('Auction Categories', 'aqualuxe'),
                'singular_name' => __('Auction Category', 'aqualuxe'),
                'menu_name'     => __('Categories', 'aqualuxe'),
            ),
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => true,
            'show_in_rest'      => true,
        ));

        // Auction Conditions
        register_taxonomy('auction_condition', array('aqualuxe_auction'), array(
            'labels' => array(
                'name'          => __('Item Conditions', 'aqualuxe'),
                'singular_name' => __('Condition', 'aqualuxe'),
                'menu_name'     => __('Conditions', 'aqualuxe'),
            ),
            'hierarchical'      => false,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => false,
            'show_tagcloud'     => false,
            'show_in_rest'      => true,
        ));

        // Trade-in Status
        register_taxonomy('trade_status', array('aqualuxe_trade'), array(
            'labels' => array(
                'name'          => __('Trade Status', 'aqualuxe'),
                'singular_name' => __('Status', 'aqualuxe'),
                'menu_name'     => __('Status', 'aqualuxe'),
            ),
            'hierarchical'      => false,
            'public'            => false,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => false,
            'show_tagcloud'     => false,
            'show_in_rest'      => false,
        ));
    }

    /**
     * Create auction user roles
     */
    public function create_auction_user_roles() {
        // Auctioneer role
        if (!get_role('auctioneer')) {
            add_role('auctioneer', __('Auctioneer', 'aqualuxe'), array(
                'read'                  => true,
                'edit_posts'            => true,
                'publish_posts'         => true,
                'manage_auctions'       => true,
                'approve_trade_ins'     => true,
                'view_auction_analytics' => true,
            ));
        }

        // Premium Bidder role
        if (!get_role('premium_bidder')) {
            add_role('premium_bidder', __('Premium Bidder', 'aqualuxe'), array(
                'read'                  => true,
                'place_auction_bids'    => true,
                'access_premium_auctions' => true,
                'early_auction_access'  => true,
            ));
        }
    }

    /**
     * Schedule auction events
     */
    public function schedule_auction_events() {
        if (!wp_next_scheduled('aqualuxe_check_auction_endings')) {
            wp_schedule_event(time(), 'hourly', 'aqualuxe_check_auction_endings');
        }
        
        if (!wp_next_scheduled('aqualuxe_send_auction_reminders')) {
            wp_schedule_event(time(), 'daily', 'aqualuxe_send_auction_reminders');
        }
    }

    /**
     * Check for ending auctions
     */
    public function check_auction_endings() {
        $ending_auctions = get_posts(array(
            'post_type'      => 'aqualuxe_auction',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => array(
                'relation' => 'AND',
                array(
                    'key'     => '_auction_end_date',
                    'value'   => current_time('mysql'),
                    'compare' => '<='
                ),
                array(
                    'key'     => '_auction_status',
                    'value'   => 'active',
                    'compare' => '='
                )
            )
        ));

        foreach ($ending_auctions as $auction) {
            $this->end_auction($auction->ID);
        }
    }

    /**
     * End an auction
     */
    private function end_auction($auction_id) {
        // Get winning bid
        $winning_bid = $this->get_winning_bid($auction_id);
        
        // Update auction status
        update_post_meta($auction_id, '_auction_status', 'ended');
        update_post_meta($auction_id, '_auction_end_time', current_time('mysql'));
        
        if ($winning_bid) {
            update_post_meta($auction_id, '_auction_winner', $winning_bid['bidder_id']);
            update_post_meta($auction_id, '_auction_winning_bid', $winning_bid['amount']);
            
            // Send notifications
            $this->send_auction_end_notifications($auction_id, $winning_bid);
        }
        
        // Update post status
        wp_update_post(array(
            'ID'          => $auction_id,
            'post_status' => 'private'
        ));
    }

    /**
     * Get winning bid for an auction
     */
    private function get_winning_bid($auction_id) {
        $bids = get_posts(array(
            'post_type'      => 'aqualuxe_bid',
            'posts_per_page' => 1,
            'meta_query'     => array(
                array(
                    'key'   => '_bid_auction_id',
                    'value' => $auction_id
                )
            ),
            'meta_key'       => '_bid_amount',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC'
        ));

        if (!empty($bids)) {
            $bid = $bids[0];
            return array(
                'bid_id'    => $bid->ID,
                'amount'    => get_post_meta($bid->ID, '_bid_amount', true),
                'bidder_id' => $bid->post_author,
                'bid_time'  => $bid->post_date
            );
        }

        return false;
    }

    /**
     * Handle AJAX auction bid
     */
    public function handle_auction_bid() {
        // Check if user is logged in
        if (!is_user_logged_in()) {
            wp_send_json_error(__('Please log in to place bids', 'aqualuxe'));
        }

        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_auction_nonce')) {
            wp_send_json_error(__('Security check failed', 'aqualuxe'));
        }

        $auction_id = intval($_POST['auction_id'] ?? 0);
        $bid_amount = floatval($_POST['bid_amount'] ?? 0);
        $user_id = get_current_user_id();

        if (!$auction_id || !$bid_amount) {
            wp_send_json_error(__('Invalid bid data', 'aqualuxe'));
        }

        // Validate auction
        $auction = get_post($auction_id);
        if (!$auction || $auction->post_type !== 'aqualuxe_auction') {
            wp_send_json_error(__('Invalid auction', 'aqualuxe'));
        }

        // Check auction status
        $auction_status = get_post_meta($auction_id, '_auction_status', true);
        if ($auction_status !== 'active') {
            wp_send_json_error(__('This auction is not active', 'aqualuxe'));
        }

        // Check auction end time
        $end_date = get_post_meta($auction_id, '_auction_end_date', true);
        if (strtotime($end_date) <= current_time('timestamp')) {
            wp_send_json_error(__('This auction has ended', 'aqualuxe'));
        }

        // Validate bid amount
        $starting_bid = floatval(get_post_meta($auction_id, '_auction_starting_bid', true));
        $current_bid = $this->get_current_highest_bid($auction_id);
        $min_increment = floatval(get_post_meta($auction_id, '_auction_bid_increment', true) ?: 1);

        $minimum_bid = $current_bid ? $current_bid + $min_increment : $starting_bid;

        if ($bid_amount < $minimum_bid) {
            wp_send_json_error(sprintf(__('Minimum bid is %s', 'aqualuxe'), wc_price($minimum_bid)));
        }

        // Create bid
        $bid_id = wp_insert_post(array(
            'post_title'  => sprintf(__('Bid on %s', 'aqualuxe'), $auction->post_title),
            'post_type'   => 'aqualuxe_bid',
            'post_status' => 'publish',
            'post_author' => $user_id,
            'meta_input'  => array(
                '_bid_auction_id' => $auction_id,
                '_bid_amount'     => $bid_amount,
                '_bid_time'       => current_time('mysql'),
                '_bid_ip_address' => $this->get_client_ip(),
            ),
        ));

        if ($bid_id && !is_wp_error($bid_id)) {
            // Send notifications
            $this->send_bid_notifications($auction_id, $bid_id, $bid_amount);
            
            wp_send_json_success(array(
                'message'     => __('Bid placed successfully!', 'aqualuxe'),
                'bid_id'      => $bid_id,
                'new_current' => wc_price($bid_amount),
                'next_min'    => wc_price($bid_amount + $min_increment),
            ));
        } else {
            wp_send_json_error(__('Failed to place bid. Please try again.', 'aqualuxe'));
        }
    }

    /**
     * Handle AJAX trade inquiry
     */
    public function handle_trade_inquiry() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_trade_nonce')) {
            wp_send_json_error(__('Security check failed', 'aqualuxe'));
        }

        $item_description = wp_kses_post($_POST['item_description'] ?? '');
        $contact_name = sanitize_text_field($_POST['contact_name'] ?? '');
        $contact_email = sanitize_email($_POST['contact_email'] ?? '');
        $contact_phone = sanitize_text_field($_POST['contact_phone'] ?? '');
        $item_condition = sanitize_text_field($_POST['item_condition'] ?? '');
        $estimated_value = sanitize_text_field($_POST['estimated_value'] ?? '');

        if (empty($item_description) || empty($contact_name) || empty($contact_email)) {
            wp_send_json_error(__('Please fill in all required fields', 'aqualuxe'));
        }

        if (!is_email($contact_email)) {
            wp_send_json_error(__('Please enter a valid email address', 'aqualuxe'));
        }

        // Create trade inquiry
        $trade_id = wp_insert_post(array(
            'post_title'   => sprintf(__('Trade Inquiry - %s', 'aqualuxe'), $contact_name),
            'post_content' => $item_description,
            'post_status'  => 'pending',
            'post_type'    => 'aqualuxe_trade',
            'meta_input'   => array(
                '_trade_contact_name'     => $contact_name,
                '_trade_contact_email'    => $contact_email,
                '_trade_contact_phone'    => $contact_phone,
                '_trade_item_condition'   => $item_condition,
                '_trade_estimated_value'  => $estimated_value,
                '_trade_inquiry_date'     => current_time('mysql'),
                '_trade_status'           => 'pending',
                '_trade_ip_address'       => $this->get_client_ip(),
            ),
        ));

        if ($trade_id && !is_wp_error($trade_id)) {
            // Set trade status taxonomy
            wp_set_object_terms($trade_id, 'pending', 'trade_status');
            
            // Send notifications
            $this->send_trade_inquiry_notifications($trade_id);
            
            wp_send_json_success(array(
                'message' => __('Trade inquiry submitted successfully! We will evaluate your item and contact you within 3-5 business days.', 'aqualuxe'),
                'trade_id' => $trade_id,
            ));
        } else {
            wp_send_json_error(__('Failed to submit inquiry. Please try again.', 'aqualuxe'));
        }
    }

    /**
     * Get current highest bid for auction
     */
    private function get_current_highest_bid($auction_id) {
        $highest_bid = get_posts(array(
            'post_type'      => 'aqualuxe_bid',
            'posts_per_page' => 1,
            'meta_query'     => array(
                array(
                    'key'   => '_bid_auction_id',
                    'value' => $auction_id
                )
            ),
            'meta_key'       => '_bid_amount',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC'
        ));

        if (!empty($highest_bid)) {
            return floatval(get_post_meta($highest_bid[0]->ID, '_bid_amount', true));
        }

        return 0;
    }

    /**
     * Render auction listing shortcode
     */
    public function render_auction_listing_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit'    => 12,
            'category' => '',
            'status'   => 'active',
            'orderby'  => 'date',
            'order'    => 'DESC',
        ), $atts);

        $args = array(
            'post_type'      => 'aqualuxe_auction',
            'posts_per_page' => intval($atts['limit']),
            'post_status'    => 'publish',
            'orderby'        => $atts['orderby'],
            'order'          => $atts['order'],
            'meta_query'     => array()
        );

        if ($atts['status']) {
            $args['meta_query'][] = array(
                'key'   => '_auction_status',
                'value' => $atts['status']
            );
        }

        if ($atts['category']) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'auction_category',
                    'field'    => 'slug',
                    'terms'    => $atts['category']
                )
            );
        }

        $auctions = get_posts($args);

        ob_start();
        ?>
        <div class="aqualuxe-auction-listing">
            <?php if ($auctions): ?>
                <div class="auction-grid">
                    <?php foreach ($auctions as $auction): ?>
                        <?php $this->render_auction_card($auction); ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="no-auctions"><?php esc_html_e('No active auctions found.', 'aqualuxe'); ?></p>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render auction card
     */
    private function render_auction_card($auction) {
        $auction_id = $auction->ID;
        $starting_bid = get_post_meta($auction_id, '_auction_starting_bid', true);
        $current_bid = $this->get_current_highest_bid($auction_id);
        $end_date = get_post_meta($auction_id, '_auction_end_date', true);
        $status = get_post_meta($auction_id, '_auction_status', true);
        
        ?>
        <div class="auction-card" data-auction-id="<?php echo esc_attr($auction_id); ?>">
            <div class="auction-image">
                <?php if (has_post_thumbnail($auction_id)): ?>
                    <?php echo get_the_post_thumbnail($auction_id, 'aqualuxe-thumbnail'); ?>
                <?php else: ?>
                    <div class="no-image">No Image</div>
                <?php endif; ?>
                
                <div class="auction-status status-<?php echo esc_attr($status); ?>">
                    <?php echo esc_html(ucfirst($status)); ?>
                </div>
            </div>
            
            <div class="auction-details">
                <h3 class="auction-title">
                    <a href="<?php echo esc_url(get_permalink($auction_id)); ?>">
                        <?php echo esc_html($auction->post_title); ?>
                    </a>
                </h3>
                
                <div class="auction-pricing">
                    <div class="current-bid">
                        <span class="label"><?php esc_html_e('Current Bid:', 'aqualuxe'); ?></span>
                        <span class="amount"><?php echo wc_price($current_bid ?: $starting_bid); ?></span>
                    </div>
                </div>
                
                <div class="auction-timing">
                    <div class="end-time" data-end-date="<?php echo esc_attr($end_date); ?>">
                        <?php if ($status === 'active'): ?>
                            <span class="label"><?php esc_html_e('Ends:', 'aqualuxe'); ?></span>
                            <span class="countdown"><?php echo esc_html(human_time_diff(strtotime($end_date), current_time('timestamp'))); ?></span>
                        <?php else: ?>
                            <span class="ended"><?php esc_html_e('Auction Ended', 'aqualuxe'); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="auction-actions">
                    <a href="<?php echo esc_url(get_permalink($auction_id)); ?>" class="btn btn-primary">
                        <?php esc_html_e('View Details', 'aqualuxe'); ?>
                    </a>
                    
                    <?php if ($status === 'active' && is_user_logged_in()): ?>
                        <button class="btn btn-secondary quick-bid-btn" data-auction-id="<?php echo esc_attr($auction_id); ?>">
                            <?php esc_html_e('Quick Bid', 'aqualuxe'); ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render trade form shortcode
     */
    public function render_trade_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'title' => __('Trade-in Your Items', 'aqualuxe'),
        ), $atts);

        ob_start();
        ?>
        <div class="aqualuxe-trade-form-wrapper">
            <form id="trade-inquiry-form" class="aqualuxe-form trade-form">
                <h3><?php echo esc_html($atts['title']); ?></h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="contact_name"><?php esc_html_e('Your Name *', 'aqualuxe'); ?></label>
                        <input type="text" id="contact_name" name="contact_name" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_email"><?php esc_html_e('Email Address *', 'aqualuxe'); ?></label>
                        <input type="email" id="contact_email" name="contact_email" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="contact_phone"><?php esc_html_e('Phone Number', 'aqualuxe'); ?></label>
                        <input type="tel" id="contact_phone" name="contact_phone">
                    </div>
                    <div class="form-group">
                        <label for="item_condition"><?php esc_html_e('Item Condition', 'aqualuxe'); ?></label>
                        <select id="item_condition" name="item_condition">
                            <option value=""><?php esc_html_e('Select Condition', 'aqualuxe'); ?></option>
                            <option value="excellent"><?php esc_html_e('Excellent', 'aqualuxe'); ?></option>
                            <option value="good"><?php esc_html_e('Good', 'aqualuxe'); ?></option>
                            <option value="fair"><?php esc_html_e('Fair', 'aqualuxe'); ?></option>
                            <option value="poor"><?php esc_html_e('Poor', 'aqualuxe'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="estimated_value"><?php esc_html_e('Estimated Value', 'aqualuxe'); ?></label>
                    <input type="text" id="estimated_value" name="estimated_value" placeholder="$0.00">
                </div>

                <div class="form-group">
                    <label for="item_description"><?php esc_html_e('Item Description *', 'aqualuxe'); ?></label>
                    <textarea id="item_description" name="item_description" rows="5" required placeholder="<?php esc_attr_e('Please describe your item in detail including species, size, age, equipment specifications, etc.', 'aqualuxe'); ?>"></textarea>
                </div>

                <div class="form-group form-submit">
                    <button type="submit" class="btn btn-primary"><?php esc_html_e('Submit Trade Inquiry', 'aqualuxe'); ?></button>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Send bid notifications
     */
    private function send_bid_notifications($auction_id, $bid_id, $bid_amount) {
        $auction = get_post($auction_id);
        $bidder = get_user_by('id', get_post_field('post_author', $bid_id));
        
        // Email to admin
        $admin_email = get_option('admin_email');
        $admin_subject = sprintf(__('New Bid on %s - %s', 'aqualuxe'), $auction->post_title, wc_price($bid_amount));
        $admin_message = sprintf(
            __('A new bid has been placed.\n\nAuction: %s\nBidder: %s (%s)\nBid Amount: %s\nBid ID: %d', 'aqualuxe'),
            $auction->post_title,
            $bidder->display_name,
            $bidder->user_email,
            wc_price($bid_amount),
            $bid_id
        );
        
        wp_mail($admin_email, $admin_subject, $admin_message);
    }

    /**
     * Send trade inquiry notifications
     */
    private function send_trade_inquiry_notifications($trade_id) {
        $contact_name = get_post_meta($trade_id, '_trade_contact_name', true);
        $contact_email = get_post_meta($trade_id, '_trade_contact_email', true);
        
        // Email to admin
        $admin_email = get_option('admin_email');
        $admin_subject = sprintf(__('New Trade Inquiry - %s', 'aqualuxe'), $contact_name);
        $admin_message = sprintf(
            __('A new trade inquiry has been submitted.\n\nContact: %s\nEmail: %s\nTrade ID: %d\n\nReview the inquiry in the admin panel.', 'aqualuxe'),
            $contact_name,
            $contact_email,
            $trade_id
        );
        
        wp_mail($admin_email, $admin_subject, $admin_message);
        
        // Email to inquirer
        $user_subject = __('Trade Inquiry Received - AquaLuxe', 'aqualuxe');
        $user_message = sprintf(
            __('Thank you for your trade inquiry.\n\nWe have received your request and will evaluate your item within 3-5 business days.\n\nReference ID: %d\n\nBest regards,\nAquaLuxe Team', 'aqualuxe'),
            $trade_id
        );
        
        wp_mail($contact_email, $user_subject, $user_message);
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
        
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }

    /**
     * Enqueue module assets
     */
    public function enqueue_assets() {
        if ($this->should_enqueue_assets()) {
            wp_enqueue_script(
                'aqualuxe-auctions',
                AQUALUXE_ASSETS_URI . '/js/modules/auctions.js',
                array('jquery', 'aqualuxe-app'),
                AQUALUXE_VERSION,
                true
            );

            wp_localize_script('aqualuxe-auctions', 'aqualuxeAuctions', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'bid_nonce'   => wp_create_nonce('aqualuxe_auction_nonce'),
                'trade_nonce' => wp_create_nonce('aqualuxe_trade_nonce'),
                'strings' => array(
                    'place_bid'    => __('Place Bid', 'aqualuxe'),
                    'submit_trade' => __('Submit Trade Inquiry', 'aqualuxe'),
                    'processing'   => __('Processing...', 'aqualuxe'),
                    'error'        => __('An error occurred. Please try again.', 'aqualuxe'),
                    'login_required' => __('Please log in to place bids', 'aqualuxe'),
                ),
            ));
        }
    }

    /**
     * Check if assets should be enqueued
     */
    private function should_enqueue_assets() {
        return is_singular('aqualuxe_auction') || 
               is_post_type_archive('aqualuxe_auction') ||
               is_page('auctions') ||
               is_page('trade-ins') ||
               has_shortcode(get_post()->post_content ?? '', 'auction_listing') ||
               has_shortcode(get_post()->post_content ?? '', 'trade_form');
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=aqualuxe_auction',
            __('Auction Settings', 'aqualuxe'),
            __('Settings', 'aqualuxe'),
            'manage_options',
            'aqualuxe-auction-settings',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'aqualuxe_auction_details',
            __('Auction Details', 'aqualuxe'),
            array($this, 'render_auction_meta_box'),
            'aqualuxe_auction',
            'normal',
            'high'
        );
    }

    /**
     * Render auction meta box
     */
    public function render_auction_meta_box($post) {
        wp_nonce_field('aqualuxe_auction_meta', 'aqualuxe_auction_nonce');
        
        $starting_bid = get_post_meta($post->ID, '_auction_starting_bid', true);
        $bid_increment = get_post_meta($post->ID, '_auction_bid_increment', true);
        $start_date = get_post_meta($post->ID, '_auction_start_date', true);
        $end_date = get_post_meta($post->ID, '_auction_end_date', true);
        $status = get_post_meta($post->ID, '_auction_status', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="auction_starting_bid"><?php esc_html_e('Starting Bid', 'aqualuxe'); ?></label></th>
                <td><input type="number" id="auction_starting_bid" name="auction_starting_bid" value="<?php echo esc_attr($starting_bid); ?>" step="0.01" min="0" /></td>
            </tr>
            <tr>
                <th><label for="auction_bid_increment"><?php esc_html_e('Bid Increment', 'aqualuxe'); ?></label></th>
                <td><input type="number" id="auction_bid_increment" name="auction_bid_increment" value="<?php echo esc_attr($bid_increment ?: 1); ?>" step="0.01" min="0.01" /></td>
            </tr>
            <tr>
                <th><label for="auction_start_date"><?php esc_html_e('Start Date', 'aqualuxe'); ?></label></th>
                <td><input type="datetime-local" id="auction_start_date" name="auction_start_date" value="<?php echo esc_attr(date('Y-m-d\TH:i', strtotime($start_date ?: 'now'))); ?>" /></td>
            </tr>
            <tr>
                <th><label for="auction_end_date"><?php esc_html_e('End Date', 'aqualuxe'); ?></label></th>
                <td><input type="datetime-local" id="auction_end_date" name="auction_end_date" value="<?php echo esc_attr(date('Y-m-d\TH:i', strtotime($end_date ?: '+7 days'))); ?>" /></td>
            </tr>
            <tr>
                <th><label for="auction_status"><?php esc_html_e('Status', 'aqualuxe'); ?></label></th>
                <td>
                    <select id="auction_status" name="auction_status">
                        <option value="draft" <?php selected($status, 'draft'); ?>><?php esc_html_e('Draft', 'aqualuxe'); ?></option>
                        <option value="scheduled" <?php selected($status, 'scheduled'); ?>><?php esc_html_e('Scheduled', 'aqualuxe'); ?></option>
                        <option value="active" <?php selected($status, 'active'); ?>><?php esc_html_e('Active', 'aqualuxe'); ?></option>
                        <option value="ended" <?php selected($status, 'ended'); ?>><?php esc_html_e('Ended', 'aqualuxe'); ?></option>
                        <option value="cancelled" <?php selected($status, 'cancelled'); ?>><?php esc_html_e('Cancelled', 'aqualuxe'); ?></option>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save auction meta
     */
    public function save_auction_meta($post_id) {
        // Check if our nonce is set and verify it
        if (!isset($_POST['aqualuxe_auction_nonce']) || !wp_verify_nonce($_POST['aqualuxe_auction_nonce'], 'aqualuxe_auction_meta')) {
            return;
        }

        // Check if user has permission to edit
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Don't save on autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (get_post_type($post_id) === 'aqualuxe_auction') {
            $fields = array('auction_starting_bid', 'auction_bid_increment', 'auction_start_date', 'auction_end_date', 'auction_status');
            
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
                }
            }
        }
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Auction Settings', 'aqualuxe'); ?></h1>
            <p><?php esc_html_e('Auction module settings will be available here.', 'aqualuxe'); ?></p>
        </div>
        <?php
    }
}

// Initialize the module
AquaLuxe_Auctions_Module::get_instance();