<?php
/**
 * Auctions/Trade-ins Module
 * Handles auction items, bidding, timers, trade-in forms, and notifications.
 */

namespace AquaLuxe\Modules\Auctions;

if ( ! defined( 'ABSPATH' ) ) exit;

class Auctions {
    public function __construct() {
        add_action( 'init', [ $this, 'register_post_type' ] );
        add_action( 'add_meta_boxes', [ $this, 'add_auction_meta_boxes' ] );
        add_action( 'save_post', [ $this, 'save_auction_meta' ] );
        add_shortcode( 'aqualuxe_tradein_form', [ $this, 'tradein_form_shortcode' ] );
        add_action( 'init', [ $this, 'handle_tradein_form' ] );
        // TODO: Add bidding system, timer JS, and notifications
    }

    public function register_post_type() {
        $labels = [
            'name' => __( 'Auctions', 'aqualuxe' ),
            'singular_name' => __( 'Auction Item', 'aqualuxe' ),
            'add_new' => __( 'Add New', 'aqualuxe' ),
            'add_new_item' => __( 'Add New Auction Item', 'aqualuxe' ),
            'edit_item' => __( 'Edit Auction Item', 'aqualuxe' ),
            'new_item' => __( 'New Auction Item', 'aqualuxe' ),
            'view_item' => __( 'View Auction Item', 'aqualuxe' ),
            'search_items' => __( 'Search Auctions', 'aqualuxe' ),
            'not_found' => __( 'No auction items found', 'aqualuxe' ),
            'not_found_in_trash' => __( 'No auction items found in Trash', 'aqualuxe' ),
        ];
        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'rewrite' => [ 'slug' => 'auctions' ],
            'supports' => [ 'title', 'editor', 'thumbnail', 'custom-fields' ],
            'show_in_rest' => true,
        ];
        register_post_type( 'aqualuxe_auction', $args );
    }

    public function add_auction_meta_boxes() {
        add_meta_box(
            'auction_details',
            __( 'Auction Details', 'aqualuxe' ),
            [ $this, 'render_auction_meta_box' ],
            'aqualuxe_auction',
            'side',
            'default'
        );
    }

    public function render_auction_meta_box( $post ) {
        $start = get_post_meta( $post->ID, '_auction_start', true );
        $end = get_post_meta( $post->ID, '_auction_end', true );
        $min_bid = get_post_meta( $post->ID, '_auction_min_bid', true );
        echo '<label>Start Time: <input type="datetime-local" name="auction_start" value="' . esc_attr( $start ) . '" /></label><br />';
        echo '<label>End Time: <input type="datetime-local" name="auction_end" value="' . esc_attr( $end ) . '" /></label><br />';
        echo '<label>Min Bid: <input type="number" name="auction_min_bid" value="' . esc_attr( $min_bid ) . '" step="any" min="0" /></label>';
    }

    public function save_auction_meta( $post_id ) {
        if ( isset( $_POST['auction_start'] ) ) update_post_meta( $post_id, '_auction_start', sanitize_text_field( $_POST['auction_start'] ) );
        if ( isset( $_POST['auction_end'] ) ) update_post_meta( $post_id, '_auction_end', sanitize_text_field( $_POST['auction_end'] ) );
        if ( isset( $_POST['auction_min_bid'] ) ) update_post_meta( $post_id, '_auction_min_bid', floatval( $_POST['auction_min_bid'] ) );
    }

    public function tradein_form_shortcode() {
        if ( isset( $_GET['tradein_submitted'] ) ) {
            return '<div class="tradein-success">Thank you for your trade-in request! We will contact you soon.</div>';
        }
        ob_start();
        ?>
        <form method="post" class="tradein-form">
            <input type="text" name="tradein_name" placeholder="Your Name" required />
            <input type="email" name="tradein_email" placeholder="Your Email" required />
            <input type="text" name="tradein_item" placeholder="Item to Trade In" required />
            <textarea name="tradein_details" placeholder="Details" required></textarea>
            <input type="hidden" name="aqualuxe_tradein_nonce" value="<?php echo wp_create_nonce('aqualuxe_tradein'); ?>" />
            <button type="submit" name="submit_tradein">Submit</button>
        </form>
        <?php
        return ob_get_clean();
    }

    public function handle_tradein_form() {
        if ( isset( $_POST['submit_tradein'] ) && isset( $_POST['aqualuxe_tradein_nonce'] ) && wp_verify_nonce( $_POST['aqualuxe_tradein_nonce'], 'aqualuxe_tradein' ) ) {
            // For demo: send email to admin
            wp_mail( get_option( 'admin_email' ), 'New Trade-in Request',
                'Name: ' . sanitize_text_field( $_POST['tradein_name'] ) . "\n" .
                'Email: ' . sanitize_email( $_POST['tradein_email'] ) . "\n" .
                'Item: ' . sanitize_text_field( $_POST['tradein_item'] ) . "\n" .
                'Details: ' . sanitize_textarea_field( $_POST['tradein_details'] )
            );
            wp_redirect( add_query_arg( 'tradein_submitted', '1', wp_get_referer() ) );
            exit;
        }
    }
}

// Initialize the module
new Auctions();
