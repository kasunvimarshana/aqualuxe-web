<?php
/**
 * WooCommerce integration class
 *
 * @package AquaLuxe
 * @subpackage WooCommerce
 * @since 1.0.0
 */

namespace AquaLuxe\WooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * WooCommerce class
 */
class WooCommerce {
    /**
     * Constructor
     */
    public function __construct() {
        // Register hooks
        add_action( 'after_setup_theme', [ $this, 'setup' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_filter( 'body_class', [ $this, 'body_classes' ] );
        add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
        add_filter( 'woocommerce_output_related_products_args', [ $this, 'related_products_args' ] );
        add_filter( 'woocommerce_product_thumbnails_columns', [ $this, 'thumbnail_columns' ] );
        add_filter( 'woocommerce_breadcrumb_defaults', [ $this, 'breadcrumb_defaults' ] );
        add_filter( 'loop_shop_per_page', [ $this, 'products_per_page' ] );
        add_filter( 'woocommerce_product_description_heading', '__return_false' );
        add_filter( 'woocommerce_product_additional_information_heading', '__return_false' );
        add_filter( 'woocommerce_pagination_args', [ $this, 'pagination_args' ] );
        add_filter( 'woocommerce_cross_sells_columns', [ $this, 'cross_sells_columns' ] );
        add_filter( 'woocommerce_cross_sells_total', [ $this, 'cross_sells_total' ] );
        add_filter( 'woocommerce_upsells_columns', [ $this, 'upsells_columns' ] );
        add_filter( 'woocommerce_related_products_columns', [ $this, 'related_products_columns' ] );
        add_filter( 'woocommerce_checkout_fields', [ $this, 'checkout_fields' ] );
        add_filter( 'woocommerce_form_field_args', [ $this, 'form_field_args' ], 10, 3 );
        add_filter( 'woocommerce_get_image_size_gallery_thumbnail', [ $this, 'gallery_thumbnail_size' ] );
        add_filter( 'woocommerce_get_image_size_single', [ $this, 'single_image_size' ] );
        add_filter( 'woocommerce_get_image_size_thumbnail', [ $this, 'thumbnail_size' ] );
        add_filter( 'woocommerce_add_to_cart_fragments', [ $this, 'cart_fragments' ] );
        add_filter( 'woocommerce_sale_flash', [ $this, 'sale_flash' ], 10, 3 );
        add_filter( 'woocommerce_product_tabs', [ $this, 'product_tabs' ] );
        add_filter( 'woocommerce_review_gravatar_size', [ $this, 'review_gravatar_size' ] );
        add_filter( 'woocommerce_gallery_thumbnail_size', [ $this, 'gallery_thumbnail_size' ] );
        add_filter( 'woocommerce_gallery_image_size', [ $this, 'gallery_image_size' ] );
        add_filter( 'woocommerce_gallery_full_size', [ $this, 'gallery_full_size' ] );
        add_filter( 'woocommerce_get_script_data', [ $this, 'get_script_data' ], 10, 2 );
        add_filter( 'woocommerce_cart_item_thumbnail', [ $this, 'cart_item_thumbnail' ], 10, 3 );
        add_filter( 'woocommerce_cart_item_name', [ $this, 'cart_item_name' ], 10, 3 );
        add_filter( 'woocommerce_cart_item_price', [ $this, 'cart_item_price' ], 10, 3 );
        add_filter( 'woocommerce_cart_item_quantity', [ $this, 'cart_item_quantity' ], 10, 3 );
        add_filter( 'woocommerce_cart_item_subtotal', [ $this, 'cart_item_subtotal' ], 10, 3 );
        add_filter( 'woocommerce_cart_item_remove_link', [ $this, 'cart_item_remove_link' ], 10, 2 );
        add_filter( 'woocommerce_cart_totals_order_total_html', [ $this, 'cart_totals_order_total_html' ] );
        add_filter( 'woocommerce_cart_totals_taxes_total_html', [ $this, 'cart_totals_taxes_total_html' ] );
        add_filter( 'woocommerce_cart_totals_coupon_html', [ $this, 'cart_totals_coupon_html' ], 10, 3 );
        add_filter( 'woocommerce_cart_totals_fee_html', [ $this, 'cart_totals_fee_html' ], 10, 2 );
        add_filter( 'woocommerce_cart_totals_shipping_html', [ $this, 'cart_totals_shipping_html' ] );
        add_filter( 'woocommerce_cart_totals_taxes_html', [ $this, 'cart_totals_taxes_html' ] );
        add_filter( 'woocommerce_cart_totals_subtotal_html', [ $this, 'cart_totals_subtotal_html' ] );
        add_filter( 'woocommerce_cart_totals_discount_html', [ $this, 'cart_totals_discount_html' ] );
        add_filter( 'woocommerce_cart_totals_voucher_html', [ $this, 'cart_totals_voucher_html' ], 10, 2 );
        add_filter( 'woocommerce_cart_totals_gift_card_html', [ $this, 'cart_totals_gift_card_html' ], 10, 2 );
        add_filter( 'woocommerce_cart_totals_order_total_html', [ $this, 'cart_totals_order_total_html' ] );
        add_filter( 'woocommerce_checkout_coupon_message', [ $this, 'checkout_coupon_message' ] );
        add_filter( 'woocommerce_checkout_login_message', [ $this, 'checkout_login_message' ] );
        add_filter( 'woocommerce_checkout_must_be_logged_in_message', [ $this, 'checkout_must_be_logged_in_message' ] );
        add_filter( 'woocommerce_checkout_order_review_title', [ $this, 'checkout_order_review_title' ] );
        add_filter( 'woocommerce_checkout_payment_title', [ $this, 'checkout_payment_title' ] );
        add_filter( 'woocommerce_checkout_terms_and_conditions_title', [ $this, 'checkout_terms_and_conditions_title' ] );
        add_filter( 'woocommerce_checkout_terms_and_conditions_page_content', [ $this, 'checkout_terms_and_conditions_page_content' ] );
        add_filter( 'woocommerce_checkout_order_button_text', [ $this, 'checkout_order_button_text' ] );
        add_filter( 'woocommerce_checkout_place_order_text', [ $this, 'checkout_place_order_text' ] );
        add_filter( 'woocommerce_checkout_pay_order_button_text', [ $this, 'checkout_pay_order_button_text' ] );
        add_filter( 'woocommerce_checkout_pay_order_button_html', [ $this, 'checkout_pay_order_button_html' ] );
        add_filter( 'woocommerce_checkout_pay_order_button_attributes', [ $this, 'checkout_pay_order_button_attributes' ] );
        add_filter( 'woocommerce_checkout_update_order_review_fragments', [ $this, 'checkout_update_order_review_fragments' ] );
        add_filter( 'woocommerce_checkout_update_order_meta', [ $this, 'checkout_update_order_meta' ] );
        add_filter( 'woocommerce_checkout_update_user_meta', [ $this, 'checkout_update_user_meta' ] );
        add_filter( 'woocommerce_checkout_get_value', [ $this, 'checkout_get_value' ], 10, 2 );
        add_filter( 'woocommerce_checkout_posted_data', [ $this, 'checkout_posted_data' ] );
        add_filter( 'woocommerce_checkout_process', [ $this, 'checkout_process' ] );
        add_filter( 'woocommerce_checkout_create_order', [ $this, 'checkout_create_order' ] );
        add_filter( 'woocommerce_checkout_create_order_line_item', [ $this, 'checkout_create_order_line_item' ], 10, 4 );
        add_filter( 'woocommerce_checkout_create_order_shipping_item', [ $this, 'checkout_create_order_shipping_item' ], 10, 4 );
        add_filter( 'woocommerce_checkout_create_order_fee_item', [ $this, 'checkout_create_order_fee_item' ], 10, 4 );
        add_filter( 'woocommerce_checkout_create_order_coupon_item', [ $this, 'checkout_create_order_coupon_item' ], 10, 4 );
        add_filter( 'woocommerce_checkout_create_order_tax_item', [ $this, 'checkout_create_order_tax_item' ], 10, 4 );
        add_filter( 'woocommerce_checkout_order_processed', [ $this, 'checkout_order_processed' ], 10, 3 );
        add_filter( 'woocommerce_checkout_no_payment_needed_redirect', [ $this, 'checkout_no_payment_needed_redirect' ] );
        add_filter( 'woocommerce_checkout_update_customer', [ $this, 'checkout_update_customer' ] );
        add_filter( 'woocommerce_checkout_customer_id', [ $this, 'checkout_customer_id' ] );
        add_filter( 'woocommerce_checkout_get_gateway_supports', [ $this, 'checkout_get_gateway_supports' ], 10, 2 );
        add_filter( 'woocommerce_checkout_get_formatted_cart_item_data', [ $this, 'checkout_get_formatted_cart_item_data' ], 10, 2 );
        add_filter( 'woocommerce_checkout_get_terms_and_conditions_checkbox_text', [ $this, 'checkout_get_terms_and_conditions_checkbox_text' ] );
        add_filter( 'woocommerce_checkout_get_value', [ $this, 'checkout_get_value' ], 10, 2 );
        add_filter( 'woocommerce_checkout_get_value_order_number', [ $this, 'checkout_get_value_order_number' ] );
        add_filter( 'woocommerce_checkout_get_value_order_date', [ $this, 'checkout_get_value_order_date' ] );
        add_filter( 'woocommerce_checkout_get_value_order_status', [ $this, 'checkout_get_value_order_status' ] );
        add_filter( 'woocommerce_checkout_get_value_payment_method', [ $this, 'checkout_get_value_payment_method' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_method', [ $this, 'checkout_get_value_shipping_method' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_first_name', [ $this, 'checkout_get_value_billing_first_name' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_last_name', [ $this, 'checkout_get_value_billing_last_name' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_company', [ $this, 'checkout_get_value_billing_company' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_address_1', [ $this, 'checkout_get_value_billing_address_1' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_address_2', [ $this, 'checkout_get_value_billing_address_2' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_city', [ $this, 'checkout_get_value_billing_city' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_state', [ $this, 'checkout_get_value_billing_state' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_postcode', [ $this, 'checkout_get_value_billing_postcode' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_country', [ $this, 'checkout_get_value_billing_country' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_email', [ $this, 'checkout_get_value_billing_email' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_phone', [ $this, 'checkout_get_value_billing_phone' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_first_name', [ $this, 'checkout_get_value_shipping_first_name' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_last_name', [ $this, 'checkout_get_value_shipping_last_name' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_company', [ $this, 'checkout_get_value_shipping_company' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_address_1', [ $this, 'checkout_get_value_shipping_address_1' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_address_2', [ $this, 'checkout_get_value_shipping_address_2' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_city', [ $this, 'checkout_get_value_shipping_city' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_state', [ $this, 'checkout_get_value_shipping_state' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_postcode', [ $this, 'checkout_get_value_shipping_postcode' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_country', [ $this, 'checkout_get_value_shipping_country' ] );
        add_filter( 'woocommerce_checkout_get_value_account_username', [ $this, 'checkout_get_value_account_username' ] );
        add_filter( 'woocommerce_checkout_get_value_account_password', [ $this, 'checkout_get_value_account_password' ] );
        add_filter( 'woocommerce_checkout_get_value_account_password-2', [ $this, 'checkout_get_value_account_password_2' ] );
        add_filter( 'woocommerce_checkout_get_value_order_comments', [ $this, 'checkout_get_value_order_comments' ] );
        add_filter( 'woocommerce_checkout_get_value_terms', [ $this, 'checkout_get_value_terms' ] );
        add_filter( 'woocommerce_checkout_get_value_createaccount', [ $this, 'checkout_get_value_createaccount' ] );
        add_filter( 'woocommerce_checkout_get_value_ship_to_different_address', [ $this, 'checkout_get_value_ship_to_different_address' ] );
        add_filter( 'woocommerce_checkout_get_value_payment_method', [ $this, 'checkout_get_value_payment_method' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_method', [ $this, 'checkout_get_value_shipping_method' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_first_name', [ $this, 'checkout_get_value_billing_first_name' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_last_name', [ $this, 'checkout_get_value_billing_last_name' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_company', [ $this, 'checkout_get_value_billing_company' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_address_1', [ $this, 'checkout_get_value_billing_address_1' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_address_2', [ $this, 'checkout_get_value_billing_address_2' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_city', [ $this, 'checkout_get_value_billing_city' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_state', [ $this, 'checkout_get_value_billing_state' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_postcode', [ $this, 'checkout_get_value_billing_postcode' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_country', [ $this, 'checkout_get_value_billing_country' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_email', [ $this, 'checkout_get_value_billing_email' ] );
        add_filter( 'woocommerce_checkout_get_value_billing_phone', [ $this, 'checkout_get_value_billing_phone' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_first_name', [ $this, 'checkout_get_value_shipping_first_name' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_last_name', [ $this, 'checkout_get_value_shipping_last_name' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_company', [ $this, 'checkout_get_value_shipping_company' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_address_1', [ $this, 'checkout_get_value_shipping_address_1' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_address_2', [ $this, 'checkout_get_value_shipping_address_2' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_city', [ $this, 'checkout_get_value_shipping_city' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_state', [ $this, 'checkout_get_value_shipping_state' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_postcode', [ $this, 'checkout_get_value_shipping_postcode' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_country', [ $this, 'checkout_get_value_shipping_country' ] );
        add_filter( 'woocommerce_checkout_get_value_account_username', [ $this, 'checkout_get_value_account_username' ] );
        add_filter( 'woocommerce_checkout_get_value_account_password', [ $this, 'checkout_get_value_account_password' ] );
        add_filter( 'woocommerce_checkout_get_value_account_password-2', [ $this, 'checkout_get_value_account_password_2' ] );
        add_filter( 'woocommerce_checkout_get_value_order_comments', [ $this, 'checkout_get_value_order_comments' ] );
        add_filter( 'woocommerce_checkout_get_value_terms', [ $this, 'checkout_get_value_terms' ] );
        add_filter( 'woocommerce_checkout_get_value_createaccount', [ $this, 'checkout_get_value_createaccount' ] );
        add_filter( 'woocommerce_checkout_get_value_ship_to_different_address', [ $this, 'checkout_get_value_ship_to_different_address' ] );
        add_filter( 'woocommerce_checkout_get_value_payment_method', [ $this, 'checkout_get_value_payment_method' ] );
        add_filter( 'woocommerce_checkout_get_value_shipping_method', [ $this, 'checkout_get_value_shipping_method' ] );
    }

    /**
     * Setup WooCommerce
     */
    public function setup() {
        // Add theme support for WooCommerce
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        // Get the asset manifest
        $manifest_path = AQUALUXE_ASSETS_DIR . 'mix-manifest.json';
        $manifest = file_exists( $manifest_path ) ? json_decode( file_get_contents( $manifest_path ), true ) : [];

        // Helper function to get versioned asset URL
        $get_asset = function( $path ) use ( $manifest ) {
            $versioned_path = isset( $manifest[ $path ] ) ? $manifest[ $path ] : $path;
            return AQUALUXE_ASSETS_URI . ltrim( $versioned_path, '/' );
        };

        // Enqueue WooCommerce styles
        wp_enqueue_style(
            'aqualuxe-woocommerce',
            $get_asset( '/css/woocommerce.css' ),
            [ 'aqualuxe-main' ],
            AQUALUXE_VERSION
        );

        // Enqueue WooCommerce scripts
        wp_enqueue_script(
            'aqualuxe-woocommerce',
            $get_asset( '/js/modules/woocommerce.js' ),
            [ 'jquery', 'aqualuxe-main' ],
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Add body classes
     *
     * @param array $classes The body classes.
     * @return array The modified body classes.
     */
    public function body_classes( $classes ) {
        // Add a class for WooCommerce
        $classes[] = 'woocommerce-active';

        // Add a class for the shop layout
        if ( is_shop() || is_product_category() || is_product_tag() ) {
            $shop_layout = get_theme_mod( 'aqualuxe_shop_layout', 'grid' );
            $classes[] = 'shop-layout-' . sanitize_html_class( $shop_layout );
        }

        return $classes;
    }

    /**
     * Related products args
     *
     * @param array $args The related products args.
     * @return array The modified related products args.
     */
    public function related_products_args( $args ) {
        $args['posts_per_page'] = 4;
        $args['columns'] = 4;

        return $args;
    }

    /**
     * Thumbnail columns
     *
     * @param int $columns The thumbnail columns.
     * @return int The modified thumbnail columns.
     */
    public function thumbnail_columns( $columns ) {
        return 4;
    }

    /**
     * Breadcrumb defaults
     *
     * @param array $defaults The breadcrumb defaults.
     * @return array The modified breadcrumb defaults.
     */
    public function breadcrumb_defaults( $defaults ) {
        $defaults['delimiter'] = '<span class="breadcrumb-separator">/</span>';
        $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '">';
        $defaults['wrap_after'] = '</nav>';
        $defaults['before'] = '<span class="breadcrumb-item">';
        $defaults['after'] = '</span>';
        $defaults['home'] = esc_html__( 'Home', 'aqualuxe' );

        return $defaults;
    }

    /**
     * Products per page
     *
     * @param int $products The products per page.
     * @return int The modified products per page.
     */
    public function products_per_page( $products ) {
        return get_theme_mod( 'aqualuxe_shop_products_per_page', 12 );
    }

    /**
     * Pagination args
     *
     * @param array $args The pagination args.
     * @return array The modified pagination args.
     */
    public function pagination_args( $args ) {
        $args['prev_text'] = '&larr;';
        $args['next_text'] = '&rarr;';
        $args['end_size'] = 3;
        $args['mid_size'] = 3;

        return $args;
    }

    /**
     * Cross sells columns
     *
     * @param int $columns The cross sells columns.
     * @return int The modified cross sells columns.
     */
    public function cross_sells_columns( $columns ) {
        return 2;
    }

    /**
     * Cross sells total
     *
     * @param int $total The cross sells total.
     * @return int The modified cross sells total.
     */
    public function cross_sells_total( $total ) {
        return 2;
    }

    /**
     * Upsells columns
     *
     * @param int $columns The upsells columns.
     * @return int The modified upsells columns.
     */
    public function upsells_columns( $columns ) {
        return 4;
    }

    /**
     * Related products columns
     *
     * @param int $columns The related products columns.
     * @return int The modified related products columns.
     */
    public function related_products_columns( $columns ) {
        return 4;
    }

    /**
     * Checkout fields
     *
     * @param array $fields The checkout fields.
     * @return array The modified checkout fields.
     */
    public function checkout_fields( $fields ) {
        // Add a class to the form fields
        foreach ( $fields as $section => $section_fields ) {
            foreach ( $section_fields as $key => $field ) {
                $fields[ $section ][ $key ]['class'][] = 'form-row-' . $key;
            }
        }

        // Remove order notes if disabled
        if ( ! get_theme_mod( 'aqualuxe_checkout_order_notes', true ) ) {
            unset( $fields['order']['order_comments'] );
        }

        return $fields;
    }

    /**
     * Form field args
     *
     * @param array  $args The form field args.
     * @param string $key The form field key.
     * @param string $value The form field value.
     * @return array The modified form field args.
     */
    public function form_field_args( $args, $key, $value ) {
        // Add a class to the form field
        $args['class'][] = 'form-row-' . $key;

        // Add a class to the input
        $args['input_class'][] = 'input-text';

        // Add a class to the label
        $args['label_class'][] = 'form-row-label';

        return $args;
    }

    /**
     * Gallery thumbnail size
     *
     * @param array $size The gallery thumbnail size.
     * @return array The modified gallery thumbnail size.
     */
    public function gallery_thumbnail_size( $size ) {
        $size['width'] = 100;
        $size['height'] = 100;
        $size['crop'] = 1;

        return $size;
    }

    /**
     * Single image size
     *
     * @param array $size The single image size.
     * @return array The modified single image size.
     */
    public function single_image_size( $size ) {
        $size['width'] = 600;
        $size['height'] = 600;
        $size['crop'] = 1;

        return $size;
    }

    /**
     * Thumbnail size
     *
     * @param array $size The thumbnail size.
     * @return array The modified thumbnail size.
     */
    public function thumbnail_size( $size ) {
        $size['width'] = 300;
        $size['height'] = 300;
        $size['crop'] = 1;

        return $size;
    }

    /**
     * Cart fragments
     *
     * @param array $fragments The cart fragments.
     * @return array The modified cart fragments.
     */
    public function cart_fragments( $fragments ) {
        // Get the cart count
        $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;

        // Add the cart count to the fragments
        $fragments['.cart-count'] = '<span class="cart-count">' . esc_html( $cart_count ) . '</span>';

        // Get the cart total
        $cart_total = WC()->cart ? WC()->cart->get_cart_total() : '';

        // Add the cart total to the fragments
        $fragments['.cart-total'] = '<span class="cart-total">' . $cart_total . '</span>';

        return $fragments;
    }

    /**
     * Sale flash
     *
     * @param string $html The sale flash HTML.
     * @param object $post The post object.
     * @param object $product The product object.
     * @return string The modified sale flash HTML.
     */
    public function sale_flash( $html, $post, $product ) {
        return '<span class="onsale">' . esc_html__( 'Sale!', 'aqualuxe' ) . '</span>';
    }

    /**
     * Product tabs
     *
     * @param array $tabs The product tabs.
     * @return array The modified product tabs.
     */
    public function product_tabs( $tabs ) {
        return $tabs;
    }

    /**
     * Review gravatar size
     *
     * @param int $size The review gravatar size.
     * @return int The modified review gravatar size.
     */
    public function review_gravatar_size( $size ) {
        return 60;
    }

    /**
     * Gallery thumbnail size
     *
     * @param array $size The gallery thumbnail size.
     * @return array The modified gallery thumbnail size.
     */
    public function gallery_thumbnail_size_filter( $size ) {
        $size = [
            'width'  => 100,
            'height' => 100,
            'crop'   => 1,
        ];

        return $size;
    }

    /**
     * Gallery image size
     *
     * @param string $size The gallery image size.
     * @return string The modified gallery image size.
     */
    public function gallery_image_size( $size ) {
        return 'woocommerce_single';
    }

    /**
     * Gallery full size
     *
     * @param string $size The gallery full size.
     * @return string The modified gallery full size.
     */
    public function gallery_full_size( $size ) {
        return 'full';
    }

    /**
     * Get script data
     *
     * @param array  $params The script data.
     * @param string $handle The script handle.
     * @return array The modified script data.
     */
    public function get_script_data( $params, $handle ) {
        return $params;
    }

    /**
     * Cart item thumbnail
     *
     * @param string $thumbnail The cart item thumbnail.
     * @param array  $cart_item The cart item.
     * @param string $cart_item_key The cart item key.
     * @return string The modified cart item thumbnail.
     */
    public function cart_item_thumbnail( $thumbnail, $cart_item, $cart_item_key ) {
        return $thumbnail;
    }

    /**
     * Cart item name
     *
     * @param string $name The cart item name.
     * @param array  $cart_item The cart item.
     * @param string $cart_item_key The cart item key.
     * @return string The modified cart item name.
     */
    public function cart_item_name( $name, $cart_item, $cart_item_key ) {
        return $name;
    }

    /**
     * Cart item price
     *
     * @param string $price The cart item price.
     * @param array  $cart_item The cart item.
     * @param string $cart_item_key The cart item key.
     * @return string The modified cart item price.
     */
    public function cart_item_price( $price, $cart_item, $cart_item_key ) {
        return $price;
    }

    /**
     * Cart item quantity
     *
     * @param string $quantity The cart item quantity.
     * @param string $cart_item_key The cart item key.
     * @param array  $cart_item The cart item.
     * @return string The modified cart item quantity.
     */
    public function cart_item_quantity( $quantity, $cart_item_key, $cart_item ) {
        return $quantity;
    }

    /**
     * Cart item subtotal
     *
     * @param string $subtotal The cart item subtotal.
     * @param array  $cart_item The cart item.
     * @param string $cart_item_key The cart item key.
     * @return string The modified cart item subtotal.
     */
    public function cart_item_subtotal( $subtotal, $cart_item, $cart_item_key ) {
        return $subtotal;
    }

    /**
     * Cart item remove link
     *
     * @param string $link The cart item remove link.
     * @param string $cart_item_key The cart item key.
     * @return string The modified cart item remove link.
     */
    public function cart_item_remove_link( $link, $cart_item_key ) {
        return $link;
    }

    /**
     * Cart totals order total HTML
     *
     * @param string $html The cart totals order total HTML.
     * @return string The modified cart totals order total HTML.
     */
    public function cart_totals_order_total_html( $html ) {
        return $html;
    }

    /**
     * Cart totals taxes total HTML
     *
     * @param string $html The cart totals taxes total HTML.
     * @return string The modified cart totals taxes total HTML.
     */
    public function cart_totals_taxes_total_html( $html ) {
        return $html;
    }

    /**
     * Cart totals coupon HTML
     *
     * @param string $coupon_html The cart totals coupon HTML.
     * @param object $coupon The coupon object.
     * @param string $discount_amount_html The discount amount HTML.
     * @return string The modified cart totals coupon HTML.
     */
    public function cart_totals_coupon_html( $coupon_html, $coupon, $discount_amount_html ) {
        return $coupon_html;
    }

    /**
     * Cart totals fee HTML
     *
     * @param string $fee_html The cart totals fee HTML.
     * @param object $fee The fee object.
     * @return string The modified cart totals fee HTML.
     */
    public function cart_totals_fee_html( $fee_html, $fee ) {
        return $fee_html;
    }

    /**
     * Cart totals shipping HTML
     *
     * @param string $html The cart totals shipping HTML.
     * @return string The modified cart totals shipping HTML.
     */
    public function cart_totals_shipping_html( $html ) {
        return $html;
    }

    /**
     * Cart totals taxes HTML
     *
     * @param string $html The cart totals taxes HTML.
     * @return string The modified cart totals taxes HTML.
     */
    public function cart_totals_taxes_html( $html ) {
        return $html;
    }

    /**
     * Cart totals subtotal HTML
     *
     * @param string $html The cart totals subtotal HTML.
     * @return string The modified cart totals subtotal HTML.
     */
    public function cart_totals_subtotal_html( $html ) {
        return $html;
    }

    /**
     * Cart totals discount HTML
     *
     * @param string $html The cart totals discount HTML.
     * @return string The modified cart totals discount HTML.
     */
    public function cart_totals_discount_html( $html ) {
        return $html;
    }

    /**
     * Cart totals voucher HTML
     *
     * @param string $html The cart totals voucher HTML.
     * @param object $voucher The voucher object.
     * @return string The modified cart totals voucher HTML.
     */
    public function cart_totals_voucher_html( $html, $voucher ) {
        return $html;
    }

    /**
     * Cart totals gift card HTML
     *
     * @param string $html The cart totals gift card HTML.
     * @param object $gift_card The gift card object.
     * @return string The modified cart totals gift card HTML.
     */
    public function cart_totals_gift_card_html( $html, $gift_card ) {
        return $html;
    }

    /**
     * Checkout coupon message
     *
     * @param string $message The checkout coupon message.
     * @return string The modified checkout coupon message.
     */
    public function checkout_coupon_message( $message ) {
        return $message;
    }

    /**
     * Checkout login message
     *
     * @param string $message The checkout login message.
     * @return string The modified checkout login message.
     */
    public function checkout_login_message( $message ) {
        return $message;
    }

    /**
     * Checkout must be logged in message
     *
     * @param string $message The checkout must be logged in message.
     * @return string The modified checkout must be logged in message.
     */
    public function checkout_must_be_logged_in_message( $message ) {
        return $message;
    }

    /**
     * Checkout order review title
     *
     * @param string $title The checkout order review title.
     * @return string The modified checkout order review title.
     */
    public function checkout_order_review_title( $title ) {
        return $title;
    }

    /**
     * Checkout payment title
     *
     * @param string $title The checkout payment title.
     * @return string The modified checkout payment title.
     */
    public function checkout_payment_title( $title ) {
        return $title;
    }

    /**
     * Checkout terms and conditions title
     *
     * @param string $title The checkout terms and conditions title.
     * @return string The modified checkout terms and conditions title.
     */
    public function checkout_terms_and_conditions_title( $title ) {
        return $title;
    }

    /**
     * Checkout terms and conditions page content
     *
     * @param string $content The checkout terms and conditions page content.
     * @return string The modified checkout terms and conditions page content.
     */
    public function checkout_terms_and_conditions_page_content( $content ) {
        return $content;
    }

    /**
     * Checkout order button text
     *
     * @param string $text The checkout order button text.
     * @return string The modified checkout order button text.
     */
    public function checkout_order_button_text( $text ) {
        return $text;
    }

    /**
     * Checkout place order text
     *
     * @param string $text The checkout place order text.
     * @return string The modified checkout place order text.
     */
    public function checkout_place_order_text( $text ) {
        return $text;
    }

    /**
     * Checkout pay order button text
     *
     * @param string $text The checkout pay order button text.
     * @return string The modified checkout pay order button text.
     */
    public function checkout_pay_order_button_text( $text ) {
        return $text;
    }

    /**
     * Checkout pay order button HTML
     *
     * @param string $html The checkout pay order button HTML.
     * @return string The modified checkout pay order button HTML.
     */
    public function checkout_pay_order_button_html( $html ) {
        return $html;
    }

    /**
     * Checkout pay order button attributes
     *
     * @param array $attributes The checkout pay order button attributes.
     * @return array The modified checkout pay order button attributes.
     */
    public function checkout_pay_order_button_attributes( $attributes ) {
        return $attributes;
    }

    /**
     * Checkout update order review fragments
     *
     * @param array $fragments The checkout update order review fragments.
     * @return array The modified checkout update order review fragments.
     */
    public function checkout_update_order_review_fragments( $fragments ) {
        return $fragments;
    }

    /**
     * Checkout update order meta
     *
     * @param int $order_id The order ID.
     */
    public function checkout_update_order_meta( $order_id ) {
        // Do nothing
    }

    /**
     * Checkout update user meta
     *
     * @param int $customer_id The customer ID.
     */
    public function checkout_update_user_meta( $customer_id ) {
        // Do nothing
    }

    /**
     * Checkout get value
     *
     * @param mixed  $value The checkout value.
     * @param string $input The checkout input.
     * @return mixed The modified checkout value.
     */
    public function checkout_get_value( $value, $input ) {
        return $value;
    }

    /**
     * Checkout posted data
     *
     * @param array $data The checkout posted data.
     * @return array The modified checkout posted data.
     */
    public function checkout_posted_data( $data ) {
        return $data;
    }

    /**
     * Checkout process
     */
    public function checkout_process() {
        // Do nothing
    }

    /**
     * Checkout create order
     *
     * @param object $order The order object.
     */
    public function checkout_create_order( $order ) {
        // Do nothing
    }

    /**
     * Checkout create order line item
     *
     * @param object $item The line item object.
     * @param string $cart_item_key The cart item key.
     * @param array  $values The cart item values.
     * @param object $order The order object.
     */
    public function checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
        // Do nothing
    }

    /**
     * Checkout create order shipping item
     *
     * @param object $item The shipping item object.
     * @param string $package_key The package key.
     * @param array  $package The package.
     * @param object $order The order object.
     */
    public function checkout_create_order_shipping_item( $item, $package_key, $package, $order ) {
        // Do nothing
    }

    /**
     * Checkout create order fee item
     *
     * @param object $item The fee item object.
     * @param string $fee_key The fee key.
     * @param object $fee The fee object.
     * @param object $order The order object.
     */
    public function checkout_create_order_fee_item( $item, $fee_key, $fee, $order ) {
        // Do nothing
    }

    /**
     * Checkout create order coupon item
     *
     * @param object $item The coupon item object.
     * @param string $code The coupon code.
     * @param object $coupon The coupon object.
     * @param object $order The order object.
     */
    public function checkout_create_order_coupon_item( $item, $code, $coupon, $order ) {
        // Do nothing
    }

    /**
     * Checkout create order tax item
     *
     * @param object $item The tax item object.
     * @param string $tax_rate_id The tax rate ID.
     * @param object $tax_rate The tax rate object.
     * @param object $order The order object.
     */
    public function checkout_create_order_tax_item( $item, $tax_rate_id, $tax_rate, $order ) {
        // Do nothing
    }

    /**
     * Checkout order processed
     *
     * @param int    $order_id The order ID.
     * @param array  $posted_data The posted data.
     * @param object $order The order object.
     */
    public function checkout_order_processed( $order_id, $posted_data, $order ) {
        // Do nothing
    }

    /**
     * Checkout no payment needed redirect
     *
     * @param string $redirect The redirect URL.
     * @return string The modified redirect URL.
     */
    public function checkout_no_payment_needed_redirect( $redirect ) {
        return $redirect;
    }

    /**
     * Checkout update customer
     *
     * @param object $customer The customer object.
     * @return object The modified customer object.
     */
    public function checkout_update_customer( $customer ) {
        return $customer;
    }

    /**
     * Checkout customer ID
     *
     * @param int $customer_id The customer ID.
     * @return int The modified customer ID.
     */
    public function checkout_customer_id( $customer_id ) {
        return $customer_id;
    }

    /**
     * Checkout get gateway supports
     *
     * @param array  $supports The gateway supports.
     * @param string $feature The feature.
     * @return array The modified gateway supports.
     */
    public function checkout_get_gateway_supports( $supports, $feature ) {
        return $supports;
    }

    /**
     * Checkout get formatted cart item data
     *
     * @param array $data The formatted cart item data.
     * @param array $cart_item The cart item.
     * @return array The modified formatted cart item data.
     */
    public function checkout_get_formatted_cart_item_data( $data, $cart_item ) {
        return $data;
    }

    /**
     * Checkout get terms and conditions checkbox text
     *
     * @param string $text The terms and conditions checkbox text.
     * @return string The modified terms and conditions checkbox text.
     */
    public function checkout_get_terms_and_conditions_checkbox_text( $text ) {
        return $text;
    }

    /**
     * Checkout get value order number
     *
     * @param string $value The order number.
     * @return string The modified order number.
     */
    public function checkout_get_value_order_number( $value ) {
        return $value;
    }

    /**
     * Checkout get value order date
     *
     * @param string $value The order date.
     * @return string The modified order date.
     */
    public function checkout_get_value_order_date( $value ) {
        return $value;
    }

    /**
     * Checkout get value order status
     *
     * @param string $value The order status.
     * @return string The modified order status.
     */
    public function checkout_get_value_order_status( $value ) {
        return $value;
    }

    /**
     * Checkout get value payment method
     *
     * @param string $value The payment method.
     * @return string The modified payment method.
     */
    public function checkout_get_value_payment_method( $value ) {
        return $value;
    }

    /**
     * Checkout get value shipping method
     *
     * @param string $value The shipping method.
     * @return string The modified shipping method.
     */
    public function checkout_get_value_shipping_method( $value ) {
        return $value;
    }

    /**
     * Checkout get value billing first name
     *
     * @param string $value The billing first name.
     * @return string The modified billing first name.
     */
    public function checkout_get_value_billing_first_name( $value ) {
        return $value;
    }

    /**
     * Checkout get value billing last name
     *
     * @param string $value The billing last name.
     * @return string The modified billing last name.
     */
    public function checkout_get_value_billing_last_name( $value ) {
        return $value;
    }

    /**
     * Checkout get value billing company
     *
     * @param string $value The billing company.
     * @return string The modified billing company.
     */
    public function checkout_get_value_billing_company( $value ) {
        return $value;
    }

    /**
     * Checkout get value billing address 1
     *
     * @param string $value The billing address 1.
     * @return string The modified billing address 1.
     */
    public function checkout_get_value_billing_address_1( $value ) {
        return $value;
    }

    /**
     * Checkout get value billing address 2
     *
     * @param string $value The billing address 2.
     * @return string The modified billing address 2.
     */
    public function checkout_get_value_billing_address_2( $value ) {
        return $value;
    }

    /**
     * Checkout get value billing city
     *
     * @param string $value The billing city.
     * @return string The modified billing city.
     */
    public function checkout_get_value_billing_city( $value ) {
        return $value;
    }

    /**
     * Checkout get value billing state
     *
     * @param string $value The billing state.
     * @return string The modified billing state.
     */
    public function checkout_get_value_billing_state( $value ) {
        return $value;
    }

    /**
     * Checkout get value billing postcode
     *
     * @param string $value The billing postcode.
     * @return string The modified billing postcode.
     */
    public function checkout_get_value_billing_postcode( $value ) {
        return $value;
    }

    /**
     * Checkout get value billing country
     *
     * @param string $value The billing country.
     * @return string The modified billing country.
     */
    public function checkout_get_value_billing_country( $value ) {
        return $value;
    }

    /**
     * Checkout get value billing email
     *
     * @param string $value The billing email.
     * @return string The modified billing email.
     */
    public function checkout_get_value_billing_email( $value ) {
        return $value;
    }

    /**
     * Checkout get value billing phone
     *
     * @param string $value The billing phone.
     * @return string The modified billing phone.
     */
    public function checkout_get_value_billing_phone( $value ) {
        return $value;
    }

    /**
     * Checkout get value shipping first name
     *
     * @param string $value The shipping first name.
     * @return string The modified shipping first name.
     */
    public function checkout_get_value_shipping_first_name( $value ) {
        return $value;
    }

    /**
     * Checkout get value shipping last name
     *
     * @param string $value The shipping last name.
     * @return string The modified shipping last name.
     */
    public function checkout_get_value_shipping_last_name( $value ) {
        return $value;
    }

    /**
     * Checkout get value shipping company
     *
     * @param string $value The shipping company.
     * @return string The modified shipping company.
     */
    public function checkout_get_value_shipping_company( $value ) {
        return $value;
    }

    /**
     * Checkout get value shipping address 1
     *
     * @param string $value The shipping address 1.
     * @return string The modified shipping address 1.
     */
    public function checkout_get_value_shipping_address_1( $value ) {
        return $value;
    }

    /**
     * Checkout get value shipping address 2
     *
     * @param string $value The shipping address 2.
     * @return string The modified shipping address 2.
     */
    public function checkout_get_value_shipping_address_2( $value ) {
        return $value;
    }

    /**
     * Checkout get value shipping city
     *
     * @param string $value The shipping city.
     * @return string The modified shipping city.
     */
    public function checkout_get_value_shipping_city( $value ) {
        return $value;
    }

    /**
     * Checkout get value shipping state
     *
     * @param string $value The shipping state.
     * @return string The modified shipping state.
     */
    public function checkout_get_value_shipping_state( $value ) {
        return $value;
    }

    /**
     * Checkout get value shipping postcode
     *
     * @param string $value The shipping postcode.
     * @return string The modified shipping postcode.
     */
    public function checkout_get_value_shipping_postcode( $value ) {
        return $value;
    }

    /**
     * Checkout get value shipping country
     *
     * @param string $value The shipping country.
     * @return string The modified shipping country.
     */
    public function checkout_get_value_shipping_country( $value ) {
        return $value;
    }

    /**
     * Checkout get value account username
     *
     * @param string $value The account username.
     * @return string The modified account username.
     */
    public function checkout_get_value_account_username( $value ) {
        return $value;
    }

    /**
     * Checkout get value account password
     *
     * @param string $value The account password.
     * @return string The modified account password.
     */
    public function checkout_get_value_account_password( $value ) {
        return $value;
    }

    /**
     * Checkout get value account password 2
     *
     * @param string $value The account password 2.
     * @return string The modified account password 2.
     */
    public function checkout_get_value_account_password_2( $value ) {
        return $value;
    }

    /**
     * Checkout get value order comments
     *
     * @param string $value The order comments.
     * @return string The modified order comments.
     */
    public function checkout_get_value_order_comments( $value ) {
        return $value;
    }

    /**
     * Checkout get value terms
     *
     * @param string $value The terms.
     * @return string The modified terms.
     */
    public function checkout_get_value_terms( $value ) {
        return $value;
    }

    /**
     * Checkout get value createaccount
     *
     * @param string $value The createaccount.
     * @return string The modified createaccount.
     */
    public function checkout_get_value_createaccount( $value ) {
        return $value;
    }

    /**
     * Checkout get value ship to different address
     *
     * @param string $value The ship to different address.
     * @return string The modified ship to different address.
     */
    public function checkout_get_value_ship_to_different_address( $value ) {
        return $value;
    }
}