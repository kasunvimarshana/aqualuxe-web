<?php
/**
 * AquaLuxe Wholesale/B2B Module
 * Modular wholesale features
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class AquaLuxe_Wholesale {
    public static function init() {
        add_action( 'init', [ __CLASS__, 'register_wholesale_role' ] );
        add_action( 'init', [ __CLASS__, 'register_wholesale_fields' ] );
        add_action( 'woocommerce_product_options_pricing', [ __CLASS__, 'add_wholesale_price_field' ] );
        add_action( 'save_post_product', [ __CLASS__, 'save_wholesale_price' ] );
        add_filter( 'woocommerce_get_price_html', [ __CLASS__, 'display_wholesale_price' ], 10, 2 );
        add_action( 'pre_get_posts', [ __CLASS__, 'restrict_wholesale_products' ] );
    }

    public static function register_wholesale_role() {
        add_role( 'wholesale_customer', __( 'Wholesale Customer', 'aqualuxe' ), [ 'read' => true ] );
    }

    public static function register_wholesale_fields() {
        // Placeholder for registration fields (company, VAT, etc.)
    }

    public static function add_wholesale_price_field() {
        woocommerce_wp_text_input([
            'id' => '_wholesale_price',
            'label' => __( 'Wholesale Price', 'aqualuxe' ),
            'desc_tip' => true,
            'description' => __( 'Set a wholesale price for wholesale customers.', 'aqualuxe' ),
            'type' => 'number',
            'custom_attributes' => [ 'step' => '0.01', 'min' => '0' ]
        ]);
    }

    public static function save_wholesale_price( $post_id ) {
        if ( isset( $_POST['_wholesale_price'] ) ) {
            update_post_meta( $post_id, '_wholesale_price', wc_clean( $_POST['_wholesale_price'] ) );
        }
    }

    public static function display_wholesale_price( $price, $product ) {
        if ( current_user_can( 'wholesale_customer' ) ) {
            $wholesale = get_post_meta( $product->get_id(), '_wholesale_price', true );
            if ( $wholesale ) {
                return wc_price( $wholesale ) . ' <small>(' . __( 'Wholesale', 'aqualuxe' ) . ')</small>';
            }
        }
        return $price;
    }

    public static function restrict_wholesale_products( $query ) {
        if ( is_admin() || ! is_post_type_archive( 'product' ) ) return;
        if ( ! current_user_can( 'wholesale_customer' ) ) {
            $meta_query = $query->get( 'meta_query' );
            $meta_query[] = [
                'key' => '_wholesale_only',
                'compare' => 'NOT EXISTS',
            ];
            $query->set( 'meta_query', $meta_query );
        }
    }
}

AquaLuxe_Wholesale::init();
