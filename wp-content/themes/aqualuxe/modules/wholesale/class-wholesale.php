<?php
/**
 * Wholesale/B2B Module
 * Handles wholesale user roles, pricing, registration, and product visibility.
 */

namespace AquaLuxe\Modules\Wholesale;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wholesale {
    public function __construct() {
        add_action( 'init', [ $this, 'register_wholesale_role' ] );
        add_action( 'woocommerce_product_options_pricing', [ $this, 'add_wholesale_price_field' ] );
        add_action( 'woocommerce_process_product_meta', [ $this, 'save_wholesale_price_field' ] );
        add_filter( 'woocommerce_get_price', [ $this, 'get_wholesale_price' ], 10, 2 );
        add_shortcode( 'aqualuxe_wholesale_registration', [ $this, 'registration_form_shortcode' ] );
        add_action( 'init', [ $this, 'handle_registration_form' ] );
        add_action( 'pre_get_posts', [ $this, 'restrict_wholesale_products' ] );
    }

    public function register_wholesale_role() {
        add_role( 'wholesale_customer', __( 'Wholesale Customer', 'aqualuxe' ), [
            'read' => true,
            'edit_posts' => false,
            'wholesale_customer' => true
        ] );
    }

    public function add_wholesale_price_field() {
        global $post;
        woocommerce_wp_text_input([
            'id' => '_wholesale_price',
            'label' => __( 'Wholesale Price', 'aqualuxe' ),
            'desc_tip' => true,
            'description' => __( 'Set a wholesale price for wholesale customers.', 'aqualuxe' ),
            'type' => 'number',
            'custom_attributes' => [ 'step' => 'any', 'min' => '0' ]
        ]);
    }

    public function save_wholesale_price_field( $post_id ) {
        if ( isset( $_POST['_wholesale_price'] ) ) {
            update_post_meta( $post_id, '_wholesale_price', wc_clean( $_POST['_wholesale_price'] ) );
        }
    }

    public function get_wholesale_price( $price, $product ) {
        if ( current_user_can( 'wholesale_customer' ) ) {
            $wholesale_price = get_post_meta( $product->get_id(), '_wholesale_price', true );
            if ( $wholesale_price !== '' ) {
                return $wholesale_price;
            }
        }
        return $price;
    }

    public function registration_form_shortcode() {
        if ( isset( $_GET['wholesale_registered'] ) ) {
            return '<div class="wholesale-success">Thank you for registering! Your account will be reviewed.</div>';
        }
        ob_start();
        ?>
        <form method="post" class="wholesale-registration-form">
            <input type="text" name="wholesale_name" placeholder="Business Name" required />
            <input type="email" name="wholesale_email" placeholder="Email" required />
            <input type="text" name="wholesale_phone" placeholder="Phone" />
            <input type="text" name="wholesale_vat" placeholder="VAT/Tax ID" />
            <input type="hidden" name="aqualuxe_wholesale_nonce" value="<?php echo wp_create_nonce('aqualuxe_wholesale'); ?>" />
            <button type="submit" name="submit_wholesale">Register</button>
        </form>
        <?php
        return ob_get_clean();
    }

    public function handle_registration_form() {
        if ( isset( $_POST['submit_wholesale'] ) && isset( $_POST['aqualuxe_wholesale_nonce'] ) && wp_verify_nonce( $_POST['aqualuxe_wholesale_nonce'], 'aqualuxe_wholesale' ) ) {
            $user_id = wp_create_user( sanitize_email( $_POST['wholesale_email'] ), wp_generate_password(), sanitize_email( $_POST['wholesale_email'] ) );
            if ( $user_id ) {
                wp_update_user([
                    'ID' => $user_id,
                    'display_name' => sanitize_text_field( $_POST['wholesale_name'] ),
                ]);
                $user = new \WP_User( $user_id );
                $user->add_role( 'wholesale_customer' );
                update_user_meta( $user_id, 'wholesale_phone', sanitize_text_field( $_POST['wholesale_phone'] ) );
                update_user_meta( $user_id, 'wholesale_vat', sanitize_text_field( $_POST['wholesale_vat'] ) );
                wp_redirect( add_query_arg( 'wholesale_registered', '1', wp_get_referer() ) );
                exit;
            }
        }
    }

    public function restrict_wholesale_products( $query ) {
        if ( is_admin() || ! $query->is_main_query() ) return;
    if ( ( function_exists( 'is_shop' ) && is_shop() ) || ( function_exists( 'is_product_category' ) && is_product_category() ) ) {
            if ( current_user_can( 'wholesale_customer' ) ) {
                // Show all products
                return;
            } else {
                // Hide wholesale-only products (marked by meta)
                $meta_query = $query->get( 'meta_query', [] );
                $meta_query[] = [
                    'key' => '_wholesale_only',
                    'compare' => 'NOT EXISTS',
                ];
                $query->set( 'meta_query', $meta_query );
            }
        }
    }
}

// Initialize the module
new Wholesale();
