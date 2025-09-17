<?php
/**
 * Auctions Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Auctions;

use AquaLuxe\Core\Abstracts\Abstract_Module;

defined('ABSPATH') || exit;

/**
 * Auctions Module Class
 */
class Module extends Abstract_Module {

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Auctions';

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
        add_action('wp_ajax_aqualuxe_place_bid', array($this, 'handle_bid'));
        add_action('wp_ajax_nopriv_aqualuxe_place_bid', array($this, 'handle_bid'));
        add_shortcode('aqualuxe_auctions', array($this, 'auctions_shortcode'));
        
        // Cron job for auction end notifications
        add_action('aqualuxe_check_auction_end', array($this, 'check_auction_end'));
        if (!wp_next_scheduled('aqualuxe_check_auction_end')) {
            wp_schedule_event(time(), 'hourly', 'aqualuxe_check_auction_end');
        }
    }

    /**
     * Register auctions post type
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => _x('Auctions', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Auction', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Auctions', 'Admin Menu text', 'aqualuxe'),
            'add_new_item'          => __('Add New Auction', 'aqualuxe'),
            'edit_item'             => __('Edit Auction', 'aqualuxe'),
            'view_item'             => __('View Auction', 'aqualuxe'),
            'all_items'             => __('All Auctions', 'aqualuxe'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'auctions'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 27,
            'menu_icon'          => 'dashicons-hammer',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        );

        register_post_type('aqualuxe_auction', $args);
    }

    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Auction Categories
        register_taxonomy('aqualuxe_auction_category', array('aqualuxe_auction'), array(
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'auction-category'),
        ));
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'aqualuxe_auction_details',
            __('Auction Details', 'aqualuxe'),
            array($this, 'auction_details_meta_box'),
            'aqualuxe_auction',
            'normal',
            'high'
        );
    }

    /**
     * Auction details meta box
     */
    public function auction_details_meta_box($post) {
        wp_nonce_field('aqualuxe_auction_nonce', 'aqualuxe_auction_nonce');
        
        $starting_bid = get_post_meta($post->ID, '_auction_starting_bid', true);
        $current_bid = get_post_meta($post->ID, '_auction_current_bid', true);
        $reserve_price = get_post_meta($post->ID, '_auction_reserve_price', true);
        $start_date = get_post_meta($post->ID, '_auction_start_date', true);
        $end_date = get_post_meta($post->ID, '_auction_end_date', true);
        $bid_increment = get_post_meta($post->ID, '_auction_bid_increment', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="auction_starting_bid"><?php _e('Starting Bid', 'aqualuxe'); ?></label></th>
                <td><input type="number" name="auction_starting_bid" id="auction_starting_bid" value="<?php echo esc_attr($starting_bid); ?>" step="0.01" min="0" /></td>
            </tr>
            <tr>
                <th><label for="auction_current_bid"><?php _e('Current Bid', 'aqualuxe'); ?></label></th>
                <td><input type="number" name="auction_current_bid" id="auction_current_bid" value="<?php echo esc_attr($current_bid); ?>" step="0.01" readonly /></td>
            </tr>
            <tr>
                <th><label for="auction_reserve_price"><?php _e('Reserve Price', 'aqualuxe'); ?></label></th>
                <td><input type="number" name="auction_reserve_price" id="auction_reserve_price" value="<?php echo esc_attr($reserve_price); ?>" step="0.01" /></td>
            </tr>
            <tr>
                <th><label for="auction_start_date"><?php _e('Start Date', 'aqualuxe'); ?></label></th>
                <td><input type="datetime-local" name="auction_start_date" id="auction_start_date" value="<?php echo esc_attr($start_date); ?>" /></td>
            </tr>
            <tr>
                <th><label for="auction_end_date"><?php _e('End Date', 'aqualuxe'); ?></label></th>
                <td><input type="datetime-local" name="auction_end_date" id="auction_end_date" value="<?php echo esc_attr($end_date); ?>" /></td>
            </tr>
            <tr>
                <th><label for="auction_bid_increment"><?php _e('Bid Increment', 'aqualuxe'); ?></label></th>
                <td><input type="number" name="auction_bid_increment" id="auction_bid_increment" value="<?php echo esc_attr($bid_increment); ?>" step="0.01" /></td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id) {
        if (!isset($_POST['aqualuxe_auction_nonce']) || !wp_verify_nonce($_POST['aqualuxe_auction_nonce'], 'aqualuxe_auction_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = array(
            'auction_starting_bid',
            'auction_current_bid',
            'auction_reserve_price',
            'auction_start_date',
            'auction_end_date',
            'auction_bid_increment'
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
        if (is_singular('aqualuxe_auction') || is_post_type_archive('aqualuxe_auction')) {
            wp_enqueue_script('aqualuxe-auctions', $this->get_url() . '/assets/auctions.js', array('jquery'), '1.0.0', true);
            wp_enqueue_style('aqualuxe-auctions', $this->get_url() . '/assets/auctions.css', array(), '1.0.0');
            
            wp_localize_script('aqualuxe-auctions', 'aqualuxe_auctions', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_auctions_nonce'),
                'currency_symbol' => get_woocommerce_currency_symbol(),
            ));
        }
    }

    /**
     * Handle bid placement
     */
    public function handle_bid() {
        check_ajax_referer('aqualuxe_auctions_nonce', 'nonce');

        $auction_id = intval($_POST['auction_id']);
        $bid_amount = floatval($_POST['bid_amount']);
        $user_id = get_current_user_id();

        if (!$user_id) {
            wp_send_json_error('Please log in to place a bid');
        }

        // Validate bid
        $current_bid = get_post_meta($auction_id, '_auction_current_bid', true);
        $bid_increment = get_post_meta($auction_id, '_auction_bid_increment', true);
        $minimum_bid = $current_bid + $bid_increment;

        if ($bid_amount < $minimum_bid) {
            wp_send_json_error("Minimum bid is {$minimum_bid}");
        }

        // Update current bid
        update_post_meta($auction_id, '_auction_current_bid', $bid_amount);
        update_post_meta($auction_id, '_auction_highest_bidder', $user_id);

        wp_send_json_success('Bid placed successfully');
    }

    /**
     * Check for auction endings
     */
    public function check_auction_end() {
        $args = array(
            'post_type' => 'aqualuxe_auction',
            'meta_query' => array(
                array(
                    'key' => '_auction_end_date',
                    'value' => current_time('mysql'),
                    'compare' => '<='
                )
            ),
            'post_status' => 'publish'
        );

        $ended_auctions = get_posts($args);

        foreach ($ended_auctions as $auction) {
            // Update auction status
            update_post_meta($auction->ID, '_auction_status', 'ended');
            
            // Notify winner
            $winner_id = get_post_meta($auction->ID, '_auction_highest_bidder', true);
            if ($winner_id) {
                $this->notify_auction_winner($auction->ID, $winner_id);
            }
        }
    }

    /**
     * Notify auction winner
     */
    private function notify_auction_winner($auction_id, $winner_id) {
        $winner = get_user_by('id', $winner_id);
        $auction = get_post($auction_id);
        $winning_bid = get_post_meta($auction_id, '_auction_current_bid', true);

        $subject = sprintf(__('Congratulations! You won the auction for %s', 'aqualuxe'), $auction->post_title);
        $message = sprintf(
            __('Dear %s,\n\nCongratulations! You have won the auction for "%s" with a bid of %s.\n\nPlease contact us to arrange payment and delivery.', 'aqualuxe'),
            $winner->display_name,
            $auction->post_title,
            $winning_bid
        );

        wp_mail($winner->user_email, $subject, $message);
    }

    /**
     * Auctions shortcode
     */
    public function auctions_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 6,
            'category' => '',
            'status' => 'active'
        ), $atts);

        $args = array(
            'post_type' => 'aqualuxe_auction',
            'posts_per_page' => intval($atts['limit']),
            'post_status' => 'publish'
        );

        if (!empty($atts['category'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'aqualuxe_auction_category',
                'field' => 'slug',
                'terms' => $atts['category']
            );
        }

        $auctions = new \WP_Query($args);
        
        ob_start();
        if ($auctions->have_posts()) {
            echo '<div class="aqualuxe-auctions-grid">';
            while ($auctions->have_posts()) {
                $auctions->the_post();
                $this->load_template('auction-card', array('post_id' => get_the_ID()));
            }
            echo '</div>';
            wp_reset_postdata();
        }
        return ob_get_clean();
    }
}