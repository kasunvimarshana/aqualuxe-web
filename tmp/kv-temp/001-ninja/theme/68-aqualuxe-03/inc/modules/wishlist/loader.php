<?php
/**
 * Wishlist Module Loader
 *
 * @package AquaLuxe\Modules\Wishlist
 */
namespace AquaLuxe\Modules\Wishlist;

if ( ! defined( 'ABSPATH' ) ) exit;

class Loader {
    public static function init() {
    add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
    add_action( 'wp_ajax_aqualuxe_wishlist_toggle', [ __CLASS__, 'ajax_toggle' ] );
    add_action( 'wp_ajax_nopriv_aqualuxe_wishlist_toggle', [ __CLASS__, 'ajax_toggle' ] );
    add_action( 'wp_ajax_aqualuxe_wishlist_count', [ __CLASS__, 'ajax_count' ] );
    add_action( 'wp_ajax_nopriv_aqualuxe_wishlist_count', [ __CLASS__, 'ajax_count' ] );
    add_shortcode( 'aqualuxe_wishlist_button', [ __CLASS__, 'wishlist_button_shortcode' ] );
    public static function ajax_count() {
        $count = 0;
        if ( is_user_logged_in() ) {
            $wishlist = (array) get_user_meta( get_current_user_id(), '_aqualuxe_wishlist', true );
            $count = count( $wishlist );
        }
        wp_send_json_success( [ 'count' => $count ] );
    }
    }

    public static function enqueue_assets() {
        wp_enqueue_script(
            'aqualuxe-wishlist',
            AQUALUXE_URI . 'assets/dist/js/wishlist.js',
            [ 'jquery' ],
            AQUALUXE_VERSION,
            true
        );
        wp_localize_script( 'aqualuxe-wishlist', 'aqualuxe_wishlist', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'aqualuxe_wishlist' ),
        ] );
        wp_enqueue_style(
            'aqualuxe-wishlist',
            AQUALUXE_URI . 'assets/dist/css/wishlist.css',
            [],
            AQUALUXE_VERSION
        );
    }

    public static function ajax_toggle() {
        check_ajax_referer( 'aqualuxe_wishlist', 'nonce' );
        $product_id = absint( $_POST['product_id'] ?? 0 );
        if ( ! $product_id ) {
            wp_send_json_error( [ 'error' => 'Invalid product.' ] );
        }
        $wishlist = (array) get_user_meta( get_current_user_id(), '_aqualuxe_wishlist', true );
        if ( in_array( $product_id, $wishlist ) ) {
            $wishlist = array_diff( $wishlist, [ $product_id ] );
            $action = 'removed';
        } else {
            $wishlist[] = $product_id;
            $action = 'added';
        }
        update_user_meta( get_current_user_id(), '_aqualuxe_wishlist', $wishlist );
        wp_send_json_success( [ 'action' => $action, 'wishlist' => $wishlist ] );
    }

    public static function wishlist_button_shortcode( $atts ) {
        $atts = shortcode_atts( [ 'product_id' => 0 ], $atts );
        $product_id = absint( $atts['product_id'] );
        if ( ! $product_id ) return '';
        $wishlist = (array) get_user_meta( get_current_user_id(), '_aqualuxe_wishlist', true );
        $in_wishlist = in_array( $product_id, $wishlist );
        ob_start();
        ?>
        <button class="aqualuxe-wishlist-btn<?php echo $in_wishlist ? ' in-wishlist' : ''; ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>">
            <span class="icon">&#9825;</span>
            <span class="text"><?php echo $in_wishlist ? __( 'Remove from Wishlist', 'aqualuxe' ) : __( 'Add to Wishlist', 'aqualuxe' ); ?></span>
        </button>
        <?php
        return ob_get_clean();
    }
}

Loader::init();
