<?php
/**
 * WooCommerce Class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

/**
 * WooCommerce Class
 * 
 * This class handles WooCommerce integration for the theme.
 */
class WooCommerce {
    /**
     * Instance of this class
     *
     * @var WooCommerce
     */
    private static $instance = null;

    /**
     * Get the singleton instance
     *
     * @return WooCommerce
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Theme support
        add_action( 'after_setup_theme', [ $this, 'setup_woocommerce_support' ] );
        
        // Enqueue scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        
        // Remove default WooCommerce styles
        add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
        
        // Unhook default WooCommerce wrappers
        remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
        remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
        
        // Hook our own wrappers
        add_action( 'woocommerce_before_main_content', [ $this, 'wrapper_start' ], 10 );
        add_action( 'woocommerce_after_main_content', [ $this, 'wrapper_end' ], 10 );
        
        // Products per page
        add_filter( 'loop_shop_per_page', [ $this, 'products_per_page' ] );
        
        // Product columns
        add_filter( 'loop_shop_columns', [ $this, 'product_columns' ] );
        
        // Related products
        add_filter( 'woocommerce_output_related_products_args', [ $this, 'related_products_args' ] );
        
        // Upsells
        add_filter( 'woocommerce_upsell_display_args', [ $this, 'upsell_products_args' ] );
        
        // Cross-sells
        add_filter( 'woocommerce_cross_sells_columns', [ $this, 'cross_sells_columns' ] );
        add_filter( 'woocommerce_cross_sells_total', [ $this, 'cross_sells_total' ] );
        
        // Product gallery
        add_action( 'after_setup_theme', [ $this, 'product_gallery_setup' ] );
        
        // Add to cart text
        add_filter( 'woocommerce_product_add_to_cart_text', [ $this, 'add_to_cart_text' ], 10, 2 );
        add_filter( 'woocommerce_product_single_add_to_cart_text', [ $this, 'single_add_to_cart_text' ], 10, 2 );
        
        // Cart fragments
        add_filter( 'woocommerce_add_to_cart_fragments', [ $this, 'cart_fragments' ] );
        
        // Mini cart
        add_action( 'aqualuxe_header_main', [ $this, 'mini_cart' ], 40 );
        
        // Quick view
        if ( get_theme_mod( 'aqualuxe_quick_view', true ) ) {
            add_action( 'woocommerce_after_shop_loop_item', [ $this, 'quick_view_button' ], 15 );
            add_action( 'wp_footer', [ $this, 'quick_view_modal' ] );
            add_action( 'wp_ajax_aqualuxe_quick_view', [ $this, 'quick_view_ajax' ] );
            add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', [ $this, 'quick_view_ajax' ] );
        }
        
        // Wishlist
        if ( get_theme_mod( 'aqualuxe_wishlist', true ) ) {
            add_action( 'woocommerce_after_shop_loop_item', [ $this, 'wishlist_button' ], 20 );
            add_action( 'woocommerce_single_product_summary', [ $this, 'wishlist_button' ], 35 );
            add_action( 'wp_ajax_aqualuxe_wishlist_add', [ $this, 'wishlist_add_ajax' ] );
            add_action( 'wp_ajax_nopriv_aqualuxe_wishlist_add', [ $this, 'wishlist_add_ajax' ] );
            add_action( 'wp_ajax_aqualuxe_wishlist_remove', [ $this, 'wishlist_remove_ajax' ] );
            add_action( 'wp_ajax_nopriv_aqualuxe_wishlist_remove', [ $this, 'wishlist_remove_ajax' ] );
        }
        
        // AJAX add to cart
        if ( get_theme_mod( 'aqualuxe_ajax_add_to_cart', true ) ) {
            add_action( 'wp_ajax_aqualuxe_add_to_cart', [ $this, 'add_to_cart_ajax' ] );
            add_action( 'wp_ajax_nopriv_aqualuxe_add_to_cart', [ $this, 'add_to_cart_ajax' ] );
        }
        
        // Sticky add to cart
        if ( get_theme_mod( 'aqualuxe_sticky_add_to_cart', true ) ) {
            add_action( 'woocommerce_after_single_product', [ $this, 'sticky_add_to_cart' ] );
        }
        
        // Product hover effect
        add_filter( 'woocommerce_post_class', [ $this, 'product_hover_effect_class' ], 10, 2 );
        
        // Sale badge style
        add_filter( 'woocommerce_sale_flash', [ $this, 'sale_badge' ], 10, 3 );
        
        // Checkout layout
        add_filter( 'body_class', [ $this, 'checkout_layout_class' ] );
        
        // Remove sidebar from checkout and cart
        add_action( 'wp', [ $this, 'remove_sidebar_from_checkout_cart' ] );
        
        // Add product categories and tags to single product
        add_action( 'woocommerce_single_product_summary', [ $this, 'product_categories_tags' ], 40 );
        
        // Add social sharing to single product
        add_action( 'woocommerce_share', [ $this, 'product_sharing' ] );
        
        // Add product tabs
        add_filter( 'woocommerce_product_tabs', [ $this, 'product_tabs' ] );
        
        // Add product meta
        add_action( 'woocommerce_single_product_summary', [ $this, 'product_meta' ], 39 );
        
        // Add product navigation
        add_action( 'woocommerce_before_single_product_summary', [ $this, 'product_navigation' ], 5 );
        
        // Add breadcrumbs
        add_action( 'woocommerce_before_main_content', [ $this, 'breadcrumbs' ], 20 );
        
        // Add shop header
        add_action( 'woocommerce_before_shop_loop', [ $this, 'shop_header' ], 5 );
        
        // Add shop filters
        add_action( 'woocommerce_before_shop_loop', [ $this, 'shop_filters' ], 15 );
        
        // Add shop sidebar toggle
        add_action( 'woocommerce_before_shop_loop', [ $this, 'shop_sidebar_toggle' ], 20 );
        
        // Add shop view switcher
        add_action( 'woocommerce_before_shop_loop', [ $this, 'shop_view_switcher' ], 25 );
        
        // Add shop ordering
        add_action( 'woocommerce_before_shop_loop', [ $this, 'shop_ordering' ], 30 );
        
        // Add shop result count
        add_action( 'woocommerce_before_shop_loop', [ $this, 'shop_result_count' ], 35 );
        
        // Add shop pagination
        add_action( 'woocommerce_after_shop_loop', [ $this, 'shop_pagination' ], 10 );
        
        // Add empty cart message
        add_action( 'woocommerce_cart_is_empty', [ $this, 'empty_cart_message' ] );
        
        // Add cart cross-sells
        add_action( 'woocommerce_cart_collaterals', [ $this, 'cart_cross_sells' ], 5 );
        
        // Add checkout coupon form
        add_action( 'woocommerce_before_checkout_form', [ $this, 'checkout_coupon_form' ], 10 );
        
        // Add checkout login form
        add_action( 'woocommerce_before_checkout_form', [ $this, 'checkout_login_form' ], 5 );
        
        // Add checkout order review
        add_action( 'woocommerce_checkout_order_review', [ $this, 'checkout_order_review' ], 10 );
        
        // Add checkout payment
        add_action( 'woocommerce_checkout_order_review', [ $this, 'checkout_payment' ], 20 );
        
        // Add checkout additional fields
        add_action( 'woocommerce_checkout_before_customer_details', [ $this, 'checkout_additional_fields' ], 10 );
        
        // Add checkout shipping methods
        add_action( 'woocommerce_checkout_shipping', [ $this, 'checkout_shipping_methods' ], 10 );
        
        // Add checkout billing fields
        add_action( 'woocommerce_checkout_billing', [ $this, 'checkout_billing_fields' ], 10 );
        
        // Add checkout shipping fields
        add_action( 'woocommerce_checkout_shipping', [ $this, 'checkout_shipping_fields' ], 10 );
        
        // Add checkout customer details
        add_action( 'woocommerce_checkout_before_order_review', [ $this, 'checkout_customer_details' ], 10 );
        
        // Add checkout order notes
        add_action( 'woocommerce_checkout_before_order_review', [ $this, 'checkout_order_notes' ], 20 );
        
        // Add checkout terms and conditions
        add_action( 'woocommerce_checkout_before_order_review', [ $this, 'checkout_terms_and_conditions' ], 30 );
        
        // Add checkout order summary
        add_action( 'woocommerce_checkout_before_order_review', [ $this, 'checkout_order_summary' ], 40 );
        
        // Add checkout payment methods
        add_action( 'woocommerce_checkout_before_order_review', [ $this, 'checkout_payment_methods' ], 50 );
        
        // Add checkout place order button
        add_action( 'woocommerce_checkout_before_order_review', [ $this, 'checkout_place_order_button' ], 60 );
        
        // Add checkout order received
        add_action( 'woocommerce_thankyou', [ $this, 'checkout_order_received' ], 10 );
        
        // Add checkout order details
        add_action( 'woocommerce_thankyou', [ $this, 'checkout_order_details' ], 20 );
        
        // Add checkout customer details
        add_action( 'woocommerce_thankyou', [ $this, 'checkout_customer_details' ], 30 );
        
        // Add checkout order again button
        add_action( 'woocommerce_thankyou', [ $this, 'checkout_order_again_button' ], 40 );
        
        // Add checkout order downloads
        add_action( 'woocommerce_thankyou', [ $this, 'checkout_order_downloads' ], 50 );
        
        // Add checkout order actions
        add_action( 'woocommerce_thankyou', [ $this, 'checkout_order_actions' ], 60 );
        
        // Add checkout order notes
        add_action( 'woocommerce_thankyou', [ $this, 'checkout_order_notes' ], 70 );
        
        // Add checkout order meta
        add_action( 'woocommerce_thankyou', [ $this, 'checkout_order_meta' ], 80 );
        
        // Add checkout order tracking
        add_action( 'woocommerce_thankyou', [ $this, 'checkout_order_tracking' ], 90 );
        
        // Add checkout order downloads
        add_action( 'woocommerce_thankyou', [ $this, 'checkout_order_downloads' ], 100 );
        
        // Add checkout order again button
        add_action( 'woocommerce_thankyou', [ $this, 'checkout_order_again_button' ], 110 );
        
        // Add checkout order actions
        add_action( 'woocommerce_thankyou', [ $this, 'checkout_order_actions' ], 120 );
        
        // Add checkout order notes
        add_action( 'woocommerce_thankyou', [ $this, 'checkout_order_notes' ], 130 );
        
        // Add checkout order meta
        add_action( 'woocommerce_thankyou', [ $this, 'checkout_order_meta' ], 140 );
        
        // Add checkout order tracking
        add_action( 'woocommerce_thankyou', [ $this, 'checkout_order_tracking' ], 150 );
    }

    /**
     * Setup WooCommerce support
     *
     * @return void
     */
    public function setup_woocommerce_support() {
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }

    /**
     * Enqueue scripts
     *
     * @return void
     */
    public function enqueue_scripts() {
        // Enqueue WooCommerce styles
        wp_enqueue_style(
            'aqualuxe-woocommerce',
            AQUALUXE_ASSETS_URI . 'css/woocommerce.css',
            [],
            AQUALUXE_VERSION
        );
        
        // Enqueue WooCommerce scripts
        wp_enqueue_script(
            'aqualuxe-woocommerce',
            AQUALUXE_ASSETS_URI . 'js/woocommerce.js',
            [ 'jquery' ],
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script(
            'aqualuxe-woocommerce',
            'aqualuxeWooCommerce',
            [
                'ajaxUrl'           => admin_url( 'admin-ajax.php' ),
                'nonce'             => wp_create_nonce( 'aqualuxe-woocommerce' ),
                'addToCartNonce'    => wp_create_nonce( 'aqualuxe-add-to-cart' ),
                'wishlistNonce'     => wp_create_nonce( 'aqualuxe-wishlist' ),
                'quickViewNonce'    => wp_create_nonce( 'aqualuxe-quick-view' ),
                'isCart'            => is_cart(),
                'isCheckout'        => is_checkout(),
                'isProduct'         => is_product(),
                'isShop'            => is_shop(),
                'ajaxAddToCart'     => get_theme_mod( 'aqualuxe_ajax_add_to_cart', true ),
                'quickView'         => get_theme_mod( 'aqualuxe_quick_view', true ),
                'wishlist'          => get_theme_mod( 'aqualuxe_wishlist', true ),
                'stickyAddToCart'   => get_theme_mod( 'aqualuxe_sticky_add_to_cart', true ),
                'i18n'              => [
                    'addToCart'     => esc_html__( 'Add to Cart', 'aqualuxe' ),
                    'adding'        => esc_html__( 'Adding...', 'aqualuxe' ),
                    'added'         => esc_html__( 'Added!', 'aqualuxe' ),
                    'viewCart'      => esc_html__( 'View Cart', 'aqualuxe' ),
                    'addToWishlist' => esc_html__( 'Add to Wishlist', 'aqualuxe' ),
                    'removeFromWishlist' => esc_html__( 'Remove from Wishlist', 'aqualuxe' ),
                    'adding'        => esc_html__( 'Adding...', 'aqualuxe' ),
                    'removing'      => esc_html__( 'Removing...', 'aqualuxe' ),
                    'added'         => esc_html__( 'Added!', 'aqualuxe' ),
                    'removed'       => esc_html__( 'Removed!', 'aqualuxe' ),
                    'viewWishlist'  => esc_html__( 'View Wishlist', 'aqualuxe' ),
                    'quickView'     => esc_html__( 'Quick View', 'aqualuxe' ),
                    'loading'       => esc_html__( 'Loading...', 'aqualuxe' ),
                    'error'         => esc_html__( 'Error', 'aqualuxe' ),
                    'close'         => esc_html__( 'Close', 'aqualuxe' ),
                ],
            ]
        );
    }

    /**
     * Wrapper start
     *
     * @return void
     */
    public function wrapper_start() {
        echo '<div id="primary" class="content-area">';
        echo '<main id="main" class="site-main" role="main">';
    }

    /**
     * Wrapper end
     *
     * @return void
     */
    public function wrapper_end() {
        echo '</main><!-- #main -->';
        echo '</div><!-- #primary -->';
    }

    /**
     * Products per page
     *
     * @return int
     */
    public function products_per_page() {
        return get_theme_mod( 'aqualuxe_products_per_page', 12 );
    }

    /**
     * Product columns
     *
     * @return int
     */
    public function product_columns() {
        return get_theme_mod( 'aqualuxe_product_columns', 4 );
    }

    /**
     * Related products args
     *
     * @param array $args Related products args.
     * @return array
     */
    public function related_products_args( $args ) {
        $args['posts_per_page'] = get_theme_mod( 'aqualuxe_related_products_count', 4 );
        $args['columns'] = get_theme_mod( 'aqualuxe_product_columns', 4 );
        
        return $args;
    }

    /**
     * Upsell products args
     *
     * @param array $args Upsell products args.
     * @return array
     */
    public function upsell_products_args( $args ) {
        $args['posts_per_page'] = get_theme_mod( 'aqualuxe_related_products_count', 4 );
        $args['columns'] = get_theme_mod( 'aqualuxe_product_columns', 4 );
        
        return $args;
    }

    /**
     * Cross-sells columns
     *
     * @return int
     */
    public function cross_sells_columns() {
        return get_theme_mod( 'aqualuxe_product_columns', 4 );
    }

    /**
     * Cross-sells total
     *
     * @return int
     */
    public function cross_sells_total() {
        return get_theme_mod( 'aqualuxe_related_products_count', 4 );
    }

    /**
     * Product gallery setup
     *
     * @return void
     */
    public function product_gallery_setup() {
        // Product gallery zoom
        if ( ! get_theme_mod( 'aqualuxe_product_gallery_zoom', true ) ) {
            remove_theme_support( 'wc-product-gallery-zoom' );
        }
        
        // Product gallery lightbox
        if ( ! get_theme_mod( 'aqualuxe_product_gallery_lightbox', true ) ) {
            remove_theme_support( 'wc-product-gallery-lightbox' );
        }
        
        // Product gallery slider
        if ( ! get_theme_mod( 'aqualuxe_product_gallery_slider', true ) ) {
            remove_theme_support( 'wc-product-gallery-slider' );
        }
    }

    /**
     * Add to cart text
     *
     * @param string $text Add to cart text.
     * @param object $product Product object.
     * @return string
     */
    public function add_to_cart_text( $text, $product ) {
        if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) {
            $text = esc_html__( 'Add to Cart', 'aqualuxe' );
        } elseif ( $product->is_type( 'variable' ) ) {
            $text = esc_html__( 'Select Options', 'aqualuxe' );
        } elseif ( $product->is_type( 'grouped' ) ) {
            $text = esc_html__( 'View Products', 'aqualuxe' );
        } elseif ( $product->is_type( 'external' ) ) {
            $text = esc_html__( 'Buy Now', 'aqualuxe' );
        }
        
        return $text;
    }

    /**
     * Single add to cart text
     *
     * @param string $text Add to cart text.
     * @param object $product Product object.
     * @return string
     */
    public function single_add_to_cart_text( $text, $product ) {
        if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) {
            $text = esc_html__( 'Add to Cart', 'aqualuxe' );
        } elseif ( $product->is_type( 'variable' ) ) {
            $text = esc_html__( 'Add to Cart', 'aqualuxe' );
        } elseif ( $product->is_type( 'grouped' ) ) {
            $text = esc_html__( 'Add to Cart', 'aqualuxe' );
        } elseif ( $product->is_type( 'external' ) ) {
            $text = esc_html__( 'Buy Now', 'aqualuxe' );
        }
        
        return $text;
    }

    /**
     * Cart fragments
     *
     * @param array $fragments Cart fragments.
     * @return array
     */
    public function cart_fragments( $fragments ) {
        // Mini cart
        ob_start();
        $this->mini_cart();
        $fragments['.mini-cart'] = ob_get_clean();
        
        // Cart count
        ob_start();
        ?>
        <span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
        <?php
        $fragments['.cart-count'] = ob_get_clean();
        
        return $fragments;
    }

    /**
     * Mini cart
     *
     * @return void
     */
    public function mini_cart() {
        // Check if mini cart is enabled
        if ( ! get_theme_mod( 'aqualuxe_header_cart', true ) ) {
            return;
        }
        
        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }
        
        // Get cart count
        $cart_count = WC()->cart->get_cart_contents_count();
        
        // Cart classes
        $cart_classes = [
            'mini-cart',
            $cart_count > 0 ? 'has-items' : 'no-items',
        ];
        
        // Output mini cart
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $cart_classes ) ); ?>">
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="mini-cart-toggle">
                <span class="mini-cart-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    <span class="cart-count"><?php echo esc_html( $cart_count ); ?></span>
                </span>
                <span class="mini-cart-total"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
            </a>
            <div class="mini-cart-dropdown">
                <div class="mini-cart-header">
                    <h3><?php esc_html_e( 'Shopping Cart', 'aqualuxe' ); ?></h3>
                    <button class="mini-cart-close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="mini-cart-content">
                    <?php woocommerce_mini_cart(); ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Quick view button
     *
     * @return void
     */
    public function quick_view_button() {
        global $product;
        
        // Check if quick view is enabled
        if ( ! get_theme_mod( 'aqualuxe_quick_view', true ) ) {
            return;
        }
        
        // Output quick view button
        ?>
        <a href="#" class="quick-view-button" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            <span><?php esc_html_e( 'Quick View', 'aqualuxe' ); ?></span>
        </a>
        <?php
    }

    /**
     * Quick view modal
     *
     * @return void
     */
    public function quick_view_modal() {
        // Check if quick view is enabled
        if ( ! get_theme_mod( 'aqualuxe_quick_view', true ) ) {
            return;
        }
        
        // Output quick view modal
        ?>
        <div id="quick-view-modal" class="quick-view-modal">
            <div class="quick-view-modal-content">
                <button class="quick-view-close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
                <div class="quick-view-content"></div>
            </div>
        </div>
        <?php
    }

    /**
     * Quick view AJAX
     *
     * @return void
     */
    public function quick_view_ajax() {
        // Check nonce
        if ( ! check_ajax_referer( 'aqualuxe-quick-view', 'nonce', false ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid nonce', 'aqualuxe' ) ] );
        }
        
        // Get product ID
        $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
        
        if ( ! $product_id ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid product ID', 'aqualuxe' ) ] );
        }
        
        // Get product
        $product = wc_get_product( $product_id );
        
        if ( ! $product ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid product', 'aqualuxe' ) ] );
        }
        
        // Output product
        ob_start();
        ?>
        <div class="quick-view-product">
            <div class="quick-view-product-image">
                <?php echo wp_kses_post( $product->get_image( 'large' ) ); ?>
            </div>
            <div class="quick-view-product-summary">
                <h2 class="quick-view-product-title"><?php echo esc_html( $product->get_name() ); ?></h2>
                <div class="quick-view-product-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
                <div class="quick-view-product-rating">
                    <?php if ( $product->get_average_rating() ) : ?>
                        <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                        <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>#reviews" class="quick-view-product-review-link">
                            <?php printf( _n( '%s review', '%s reviews', $product->get_review_count(), 'aqualuxe' ), esc_html( $product->get_review_count() ) ); ?>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="quick-view-product-description">
                    <?php echo wp_kses_post( $product->get_short_description() ); ?>
                </div>
                <div class="quick-view-product-add-to-cart">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
                <div class="quick-view-product-meta">
                    <?php if ( wc_product_sku_enabled() && $product->get_sku() ) : ?>
                        <span class="quick-view-product-sku">
                            <?php esc_html_e( 'SKU:', 'aqualuxe' ); ?> <?php echo esc_html( $product->get_sku() ); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ( $product->get_category_ids() ) : ?>
                        <span class="quick-view-product-categories">
                            <?php echo wp_kses_post( wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'aqualuxe' ) . ' ', '</span>' ) ); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ( $product->get_tag_ids() ) : ?>
                        <span class="quick-view-product-tags">
                            <?php echo wp_kses_post( wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'aqualuxe' ) . ' ', '</span>' ) ); ?>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="quick-view-product-actions">
                    <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="button quick-view-product-view-details">
                        <?php esc_html_e( 'View Details', 'aqualuxe' ); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
        $output = ob_get_clean();
        
        wp_send_json_success( [ 'html' => $output ] );
    }

    /**
     * Wishlist button
     *
     * @return void
     */
    public function wishlist_button() {
        global $product;
        
        // Check if wishlist is enabled
        if ( ! get_theme_mod( 'aqualuxe_wishlist', true ) ) {
            return;
        }
        
        // Get wishlist
        $wishlist = $this->get_wishlist();
        
        // Check if product is in wishlist
        $in_wishlist = in_array( $product->get_id(), $wishlist );
        
        // Output wishlist button
        ?>
        <a href="#" class="wishlist-button <?php echo $in_wishlist ? 'in-wishlist' : ''; ?>" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="<?php echo $in_wishlist ? 'currentColor' : 'none'; ?>" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
            <span><?php echo $in_wishlist ? esc_html__( 'Remove from Wishlist', 'aqualuxe' ) : esc_html__( 'Add to Wishlist', 'aqualuxe' ); ?></span>
        </a>
        <?php
    }

    /**
     * Wishlist add AJAX
     *
     * @return void
     */
    public function wishlist_add_ajax() {
        // Check nonce
        if ( ! check_ajax_referer( 'aqualuxe-wishlist', 'nonce', false ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid nonce', 'aqualuxe' ) ] );
        }
        
        // Get product ID
        $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
        
        if ( ! $product_id ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid product ID', 'aqualuxe' ) ] );
        }
        
        // Get wishlist
        $wishlist = $this->get_wishlist();
        
        // Add product to wishlist
        if ( ! in_array( $product_id, $wishlist ) ) {
            $wishlist[] = $product_id;
        }
        
        // Save wishlist
        $this->save_wishlist( $wishlist );
        
        wp_send_json_success( [
            'message' => esc_html__( 'Product added to wishlist', 'aqualuxe' ),
            'wishlist' => $wishlist,
        ] );
    }

    /**
     * Wishlist remove AJAX
     *
     * @return void
     */
    public function wishlist_remove_ajax() {
        // Check nonce
        if ( ! check_ajax_referer( 'aqualuxe-wishlist', 'nonce', false ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid nonce', 'aqualuxe' ) ] );
        }
        
        // Get product ID
        $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
        
        if ( ! $product_id ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid product ID', 'aqualuxe' ) ] );
        }
        
        // Get wishlist
        $wishlist = $this->get_wishlist();
        
        // Remove product from wishlist
        $wishlist = array_diff( $wishlist, [ $product_id ] );
        
        // Save wishlist
        $this->save_wishlist( $wishlist );
        
        wp_send_json_success( [
            'message' => esc_html__( 'Product removed from wishlist', 'aqualuxe' ),
            'wishlist' => $wishlist,
        ] );
    }

    /**
     * Get wishlist
     *
     * @return array
     */
    public function get_wishlist() {
        // Get wishlist from cookie
        $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( stripslashes( $_COOKIE['aqualuxe_wishlist'] ), true ) : [];
        
        // Get wishlist from user meta
        if ( is_user_logged_in() ) {
            $user_wishlist = get_user_meta( get_current_user_id(), 'aqualuxe_wishlist', true );
            
            if ( $user_wishlist ) {
                $wishlist = array_unique( array_merge( $wishlist, $user_wishlist ) );
            }
        }
        
        return $wishlist;
    }

    /**
     * Save wishlist
     *
     * @param array $wishlist Wishlist.
     * @return void
     */
    public function save_wishlist( $wishlist ) {
        // Save wishlist to cookie
        setcookie( 'aqualuxe_wishlist', json_encode( $wishlist ), time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
        
        // Save wishlist to user meta
        if ( is_user_logged_in() ) {
            update_user_meta( get_current_user_id(), 'aqualuxe_wishlist', $wishlist );
        }
    }

    /**
     * Add to cart AJAX
     *
     * @return void
     */
    public function add_to_cart_ajax() {
        // Check nonce
        if ( ! check_ajax_referer( 'aqualuxe-add-to-cart', 'nonce', false ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid nonce', 'aqualuxe' ) ] );
        }
        
        // Get product ID
        $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
        
        if ( ! $product_id ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid product ID', 'aqualuxe' ) ] );
        }
        
        // Get quantity
        $quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;
        
        // Get variation ID
        $variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
        
        // Get variation
        $variation = isset( $_POST['variation'] ) ? $_POST['variation'] : [];
        
        // Add to cart
        $cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
        
        if ( ! $cart_item_key ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Error adding product to cart', 'aqualuxe' ) ] );
        }
        
        // Get cart fragments
        $fragments = $this->cart_fragments( [] );
        
        wp_send_json_success( [
            'message' => esc_html__( 'Product added to cart', 'aqualuxe' ),
            'fragments' => $fragments,
        ] );
    }

    /**
     * Sticky add to cart
     *
     * @return void
     */
    public function sticky_add_to_cart() {
        global $product;
        
        // Check if sticky add to cart is enabled
        if ( ! get_theme_mod( 'aqualuxe_sticky_add_to_cart', true ) ) {
            return;
        }
        
        // Check if product is purchasable
        if ( ! $product->is_purchasable() ) {
            return;
        }
        
        // Output sticky add to cart
        ?>
        <div class="sticky-add-to-cart">
            <div class="container">
                <div class="sticky-add-to-cart-content">
                    <div class="sticky-add-to-cart-image">
                        <?php echo wp_kses_post( $product->get_image( 'thumbnail' ) ); ?>
                    </div>
                    <div class="sticky-add-to-cart-summary">
                        <h4 class="sticky-add-to-cart-title"><?php echo esc_html( $product->get_name() ); ?></h4>
                        <div class="sticky-add-to-cart-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
                    </div>
                    <div class="sticky-add-to-cart-actions">
                        <?php woocommerce_template_single_add_to_cart(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Product hover effect class
     *
     * @param array  $classes Product classes.
     * @param object $product Product object.
     * @return array
     */
    public function product_hover_effect_class( $classes, $product ) {
        // Get hover effect
        $hover_effect = get_theme_mod( 'aqualuxe_product_hover_effect', 'zoom' );
        
        // Add hover effect class
        $classes[] = 'hover-effect-' . $hover_effect;
        
        return $classes;
    }

    /**
     * Sale badge
     *
     * @param string $html Sale badge HTML.
     * @param object $post Post object.
     * @param object $product Product object.
     * @return string
     */
    public function sale_badge( $html, $post, $product ) {
        // Get sale badge style
        $style = get_theme_mod( 'aqualuxe_sale_badge_style', 'circle' );
        
        // Sale text
        $text = esc_html__( 'Sale!', 'aqualuxe' );
        
        // If percentage style, calculate discount
        if ( $style === 'percentage' ) {
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
            
            if ( $regular_price && $sale_price ) {
                $discount = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                $text = sprintf( esc_html__( '%s%%', 'aqualuxe' ), $discount );
            }
        }
        
        // Output sale badge
        return '<span class="onsale sale-style-' . esc_attr( $style ) . '">' . $text . '</span>';
    }

    /**
     * Checkout layout class
     *
     * @param array $classes Body classes.
     * @return array
     */
    public function checkout_layout_class( $classes ) {
        // Check if checkout page
        if ( is_checkout() ) {
            // Get checkout layout
            $layout = get_theme_mod( 'aqualuxe_checkout_layout', 'default' );
            
            // Add checkout layout class
            $classes[] = 'checkout-layout-' . $layout;
        }
        
        return $classes;
    }

    /**
     * Remove sidebar from checkout and cart
     *
     * @return void
     */
    public function remove_sidebar_from_checkout_cart() {
        // Check if checkout or cart page
        if ( is_checkout() || is_cart() ) {
            // Remove sidebar
            remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
        }
    }

    /**
     * Product categories and tags
     *
     * @return void
     */
    public function product_categories_tags() {
        global $product;
        
        // Output categories
        if ( $product->get_category_ids() ) {
            echo wp_kses_post( wc_get_product_category_list( $product->get_id(), ', ', '<div class="product-categories">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'aqualuxe' ) . ' ', '</div>' ) );
        }
        
        // Output tags
        if ( $product->get_tag_ids() ) {
            echo wp_kses_post( wc_get_product_tag_list( $product->get_id(), ', ', '<div class="product-tags">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'aqualuxe' ) . ' ', '</div>' ) );
        }
    }

    /**
     * Product sharing
     *
     * @return void
     */
    public function product_sharing() {
        global $product;
        
        // Get product URL
        $product_url = get_permalink( $product->get_id() );
        
        // Get product title
        $product_title = $product->get_name();
        
        // Output sharing links
        ?>
        <div class="product-sharing">
            <h4><?php esc_html_e( 'Share:', 'aqualuxe' ); ?></h4>
            <ul class="social-links">
                <li>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( $product_url ); ?>" target="_blank" rel="noopener noreferrer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                        <span class="screen-reader-text"><?php esc_html_e( 'Share on Facebook', 'aqualuxe' ); ?></span>
                    </a>
                </li>
                <li>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo esc_attr( $product_title ); ?>&url=<?php echo esc_url( $product_url ); ?>" target="_blank" rel="noopener noreferrer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                        <span class="screen-reader-text"><?php esc_html_e( 'Share on Twitter', 'aqualuxe' ); ?></span>
                    </a>
                </li>
                <li>
                    <a href="https://pinterest.com/pin/create/button/?url=<?php echo esc_url( $product_url ); ?>&media=<?php echo esc_url( wp_get_attachment_url( $product->get_image_id() ) ); ?>&description=<?php echo esc_attr( $product_title ); ?>" target="_blank" rel="noopener noreferrer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 12a4 4 0 1 0 8 0 4 4 0 0 0-8 0z"></path><path d="M12 2v2"></path><path d="M12 20v2"></path><path d="M4.93 4.93l1.41 1.41"></path><path d="M17.66 17.66l1.41 1.41"></path><path d="M2 12h2"></path><path d="M20 12h2"></path><path d="M6.34 17.66l-1.41 1.41"></path><path d="M19.07 4.93l-1.41 1.41"></path></svg>
                        <span class="screen-reader-text"><?php esc_html_e( 'Share on Pinterest', 'aqualuxe' ); ?></span>
                    </a>
                </li>
                <li>
                    <a href="mailto:?subject=<?php echo esc_attr( $product_title ); ?>&body=<?php echo esc_url( $product_url ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <span class="screen-reader-text"><?php esc_html_e( 'Share via Email', 'aqualuxe' ); ?></span>
                    </a>
                </li>
            </ul>
        </div>
        <?php
    }

    /**
     * Product tabs
     *
     * @param array $tabs Product tabs.
     * @return array
     */
    public function product_tabs( $tabs ) {
        // Add custom tabs
        $tabs['shipping'] = [
            'title'    => esc_html__( 'Shipping & Returns', 'aqualuxe' ),
            'priority' => 30,
            'callback' => [ $this, 'shipping_tab' ],
        ];
        
        $tabs['care'] = [
            'title'    => esc_html__( 'Care Instructions', 'aqualuxe' ),
            'priority' => 40,
            'callback' => [ $this, 'care_tab' ],
        ];
        
        return $tabs;
    }

    /**
     * Shipping tab
     *
     * @return void
     */
    public function shipping_tab() {
        // Get shipping content
        $shipping_content = get_theme_mod( 'aqualuxe_shipping_content', '' );
        
        if ( empty( $shipping_content ) ) {
            $shipping_content = '<h3>' . esc_html__( 'Shipping Information', 'aqualuxe' ) . '</h3>';
            $shipping_content .= '<p>' . esc_html__( 'We ship worldwide via premium carriers. All orders are processed within 1-2 business days. Shipping times vary based on location:', 'aqualuxe' ) . '</p>';
            $shipping_content .= '<ul>';
            $shipping_content .= '<li>' . esc_html__( 'Domestic: 2-5 business days', 'aqualuxe' ) . '</li>';
            $shipping_content .= '<li>' . esc_html__( 'International: 7-14 business days', 'aqualuxe' ) . '</li>';
            $shipping_content .= '</ul>';
            
            $shipping_content .= '<h3>' . esc_html__( 'Returns & Exchanges', 'aqualuxe' ) . '</h3>';
            $shipping_content .= '<p>' . esc_html__( 'We offer a 30-day return policy for most items. To be eligible for a return, your item must be unused and in the same condition that you received it. It must also be in the original packaging.', 'aqualuxe' ) . '</p>';
            $shipping_content .= '<p>' . esc_html__( 'Live animals, plants, and special order items cannot be returned. Please contact us for specific shipping requirements for live specimens.', 'aqualuxe' ) . '</p>';
        }
        
        echo wp_kses_post( wpautop( $shipping_content ) );
    }

    /**
     * Care tab
     *
     * @return void
     */
    public function care_tab() {
        global $product;
        
        // Get care instructions
        $care_instructions = get_post_meta( $product->get_id(), '_care_instructions', true );
        
        if ( empty( $care_instructions ) ) {
            $care_instructions = '<h3>' . esc_html__( 'General Care Guidelines', 'aqualuxe' ) . '</h3>';
            $care_instructions .= '<p>' . esc_html__( 'Proper care is essential for the health and longevity of aquatic life. Here are some general guidelines:', 'aqualuxe' ) . '</p>';
            $care_instructions .= '<ul>';
            $care_instructions .= '<li>' . esc_html__( 'Maintain consistent water parameters appropriate for your species', 'aqualuxe' ) . '</li>';
            $care_instructions .= '<li>' . esc_html__( 'Perform regular water changes (typically 25% every 2-4 weeks)', 'aqualuxe' ) . '</li>';
            $care_instructions .= '<li>' . esc_html__( 'Test water regularly for ammonia, nitrite, nitrate, pH, and temperature', 'aqualuxe' ) . '</li>';
            $care_instructions .= '<li>' . esc_html__( 'Feed appropriate foods in moderate amounts', 'aqualuxe' ) . '</li>';
            $care_instructions .= '<li>' . esc_html__( 'Ensure proper filtration and aeration', 'aqualuxe' ) . '</li>';
            $care_instructions .= '</ul>';
            
            $care_instructions .= '<p>' . esc_html__( 'For species-specific care requirements, please consult our care guides or contact our support team.', 'aqualuxe' ) . '</p>';
        }
        
        echo wp_kses_post( wpautop( $care_instructions ) );
    }

    /**
     * Product meta
     *
     * @return void
     */
    public function product_meta() {
        global $product;
        
        // Get product meta
        $sku = $product->get_sku();
        $stock_status = $product->get_stock_status();
        $stock_quantity = $product->get_stock_quantity();
        
        // Output product meta
        ?>
        <div class="product-meta">
            <?php if ( $sku ) : ?>
                <div class="product-sku">
                    <span class="label"><?php esc_html_e( 'SKU:', 'aqualuxe' ); ?></span>
                    <span class="value"><?php echo esc_html( $sku ); ?></span>
                </div>
            <?php endif; ?>
            
            <div class="product-stock">
                <span class="label"><?php esc_html_e( 'Availability:', 'aqualuxe' ); ?></span>
                <span class="value stock-status-<?php echo esc_attr( $stock_status ); ?>">
                    <?php
                    if ( $stock_status === 'instock' ) {
                        if ( $stock_quantity ) {
                            echo sprintf( esc_html__( 'In Stock (%s available)', 'aqualuxe' ), $stock_quantity );
                        } else {
                            esc_html_e( 'In Stock', 'aqualuxe' );
                        }
                    } elseif ( $stock_status === 'outofstock' ) {
                        esc_html_e( 'Out of Stock', 'aqualuxe' );
                    } elseif ( $stock_status === 'onbackorder' ) {
                        esc_html_e( 'On Backorder', 'aqualuxe' );
                    }
                    ?>
                </span>
            </div>
        </div>
        <?php
    }

    /**
     * Product navigation
     *
     * @return void
     */
    public function product_navigation() {
        // Get previous and next product
        $previous = get_previous_post( true, '', 'product_cat' );
        $next = get_next_post( true, '', 'product_cat' );
        
        // Output product navigation
        ?>
        <div class="product-navigation">
            <?php if ( $previous ) : ?>
                <a href="<?php echo esc_url( get_permalink( $previous->ID ) ); ?>" class="previous-product">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    <span class="screen-reader-text"><?php esc_html_e( 'Previous Product', 'aqualuxe' ); ?></span>
                </a>
            <?php endif; ?>
            
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="back-to-shop">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                <span class="screen-reader-text"><?php esc_html_e( 'Back to Shop', 'aqualuxe' ); ?></span>
            </a>
            
            <?php if ( $next ) : ?>
                <a href="<?php echo esc_url( get_permalink( $next->ID ) ); ?>" class="next-product">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    <span class="screen-reader-text"><?php esc_html_e( 'Next Product', 'aqualuxe' ); ?></span>
                </a>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Breadcrumbs
     *
     * @return void
     */
    public function breadcrumbs() {
        // Check if breadcrumbs are enabled
        if ( ! get_theme_mod( 'aqualuxe_breadcrumbs', true ) ) {
            return;
        }
        
        // Output breadcrumbs
        woocommerce_breadcrumb();
    }

    /**
     * Shop header
     *
     * @return void
     */
    public function shop_header() {
        // Check if shop header is enabled
        if ( ! get_theme_mod( 'aqualuxe_shop_header', true ) ) {
            return;
        }
        
        // Get shop title
        $shop_title = get_theme_mod( 'aqualuxe_shop_title', '' );
        
        if ( empty( $shop_title ) ) {
            if ( is_shop() ) {
                $shop_title = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : esc_html__( 'Shop', 'aqualuxe' );
            } elseif ( is_product_category() ) {
                $shop_title = single_term_title( '', false );
            } elseif ( is_product_tag() ) {
                $shop_title = single_term_title( '', false );
            } elseif ( is_search() ) {
                $shop_title = sprintf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), get_search_query() );
            }
        }
        
        // Get shop description
        $shop_description = get_theme_mod( 'aqualuxe_shop_description', '' );
        
        if ( empty( $shop_description ) ) {
            if ( is_product_category() ) {
                $shop_description = term_description();
            } elseif ( is_product_tag() ) {
                $shop_description = term_description();
            }
        }
        
        // Output shop header
        ?>
        <div class="shop-header">
            <h1 class="shop-title"><?php echo esc_html( $shop_title ); ?></h1>
            
            <?php if ( ! empty( $shop_description ) ) : ?>
                <div class="shop-description">
                    <?php echo wp_kses_post( $shop_description ); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Shop filters
     *
     * @return void
     */
    public function shop_filters() {
        // Check if shop filters are enabled
        if ( ! get_theme_mod( 'aqualuxe_shop_filters', true ) ) {
            return;
        }
        
        // Output shop filters
        ?>
        <div class="shop-filters">
            <?php the_widget( 'WC_Widget_Price_Filter', [ 'title' => esc_html__( 'Filter by Price', 'aqualuxe' ) ] ); ?>
            <?php the_widget( 'WC_Widget_Product_Categories', [ 'title' => esc_html__( 'Filter by Category', 'aqualuxe' ) ] ); ?>
            <?php the_widget( 'WC_Widget_Rating_Filter', [ 'title' => esc_html__( 'Filter by Rating', 'aqualuxe' ) ] ); ?>
        </div>
        <?php
    }

    /**
     * Shop sidebar toggle
     *
     * @return void
     */
    public function shop_sidebar_toggle() {
        // Check if shop sidebar toggle is enabled
        if ( ! get_theme_mod( 'aqualuxe_shop_sidebar_toggle', true ) ) {
            return;
        }
        
        // Output shop sidebar toggle
        ?>
        <button class="shop-sidebar-toggle">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>
            <span><?php esc_html_e( 'Filters', 'aqualuxe' ); ?></span>
        </button>
        <?php
    }

    /**
     * Shop view switcher
     *
     * @return void
     */
    public function shop_view_switcher() {
        // Check if shop view switcher is enabled
        if ( ! get_theme_mod( 'aqualuxe_shop_view_switcher', true ) ) {
            return;
        }
        
        // Get current view
        $current_view = isset( $_COOKIE['aqualuxe_shop_view'] ) ? $_COOKIE['aqualuxe_shop_view'] : get_theme_mod( 'aqualuxe_shop_layout', 'grid' );
        
        // Output shop view switcher
        ?>
        <div class="shop-view-switcher">
            <button class="view-grid <?php echo $current_view === 'grid' ? 'active' : ''; ?>" data-view="grid">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                <span class="screen-reader-text"><?php esc_html_e( 'Grid View', 'aqualuxe' ); ?></span>
            </button>
            <button class="view-list <?php echo $current_view === 'list' ? 'active' : ''; ?>" data-view="list">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                <span class="screen-reader-text"><?php esc_html_e( 'List View', 'aqualuxe' ); ?></span>
            </button>
        </div>
        <?php
    }

    /**
     * Shop ordering
     *
     * @return void
     */
    public function shop_ordering() {
        // Check if shop ordering is enabled
        if ( ! get_theme_mod( 'aqualuxe_shop_ordering', true ) ) {
            return;
        }
        
        // Output shop ordering
        woocommerce_catalog_ordering();
    }

    /**
     * Shop result count
     *
     * @return void
     */
    public function shop_result_count() {
        // Check if shop result count is enabled
        if ( ! get_theme_mod( 'aqualuxe_shop_result_count', true ) ) {
            return;
        }
        
        // Output shop result count
        woocommerce_result_count();
    }

    /**
     * Shop pagination
     *
     * @return void
     */
    public function shop_pagination() {
        // Check if shop pagination is enabled
        if ( ! get_theme_mod( 'aqualuxe_shop_pagination', true ) ) {
            return;
        }
        
        // Output shop pagination
        woocommerce_pagination();
    }

    /**
     * Empty cart message
     *
     * @return void
     */
    public function empty_cart_message() {
        // Output empty cart message
        ?>
        <div class="empty-cart-message">
            <div class="empty-cart-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            </div>
            <h2><?php esc_html_e( 'Your Cart is Empty', 'aqualuxe' ); ?></h2>
            <p><?php esc_html_e( 'Looks like you haven\'t added anything to your cart yet.', 'aqualuxe' ); ?></p>
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="button"><?php esc_html_e( 'Continue Shopping', 'aqualuxe' ); ?></a>
        </div>
        <?php
    }

    /**
     * Cart cross-sells
     *
     * @return void
     */
    public function cart_cross_sells() {
        // Check if cross-sells are enabled
        if ( ! get_theme_mod( 'aqualuxe_cross_sells', true ) ) {
            return;
        }
        
        // Output cross-sells
        woocommerce_cross_sell_display();
    }

    /**
     * Checkout coupon form
     *
     * @return void
     */
    public function checkout_coupon_form() {
        // Check if coupons are enabled
        if ( ! wc_coupons_enabled() ) {
            return;
        }
        
        // Output coupon form
        woocommerce_checkout_coupon_form();
    }

    /**
     * Checkout login form
     *
     * @return void
     */
    public function checkout_login_form() {
        // Check if checkout registration is enabled
        if ( ! get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
            return;
        }
        
        // Output login form
        woocommerce_checkout_login_form();
    }

    /**
     * Checkout order review
     *
     * @return void
     */
    public function checkout_order_review() {
        // Output order review
        woocommerce_order_review();
    }

    /**
     * Checkout payment
     *
     * @return void
     */
    public function checkout_payment() {
        // Output payment
        woocommerce_checkout_payment();
    }

    /**
     * Checkout additional fields
     *
     * @return void
     */
    public function checkout_additional_fields() {
        // Output additional fields
        do_action( 'woocommerce_before_checkout_billing_form', WC()->checkout() );
    }

    /**
     * Checkout shipping methods
     *
     * @return void
     */
    public function checkout_shipping_methods() {
        // Output shipping methods
        woocommerce_checkout_shipping();
    }

    /**
     * Checkout billing fields
     *
     * @return void
     */
    public function checkout_billing_fields() {
        // Output billing fields
        woocommerce_checkout_billing();
    }

    /**
     * Checkout shipping fields
     *
     * @return void
     */
    public function checkout_shipping_fields() {
        // Output shipping fields
        woocommerce_checkout_shipping();
    }

    /**
     * Checkout customer details
     *
     * @param int $order_id Order ID.
     * @return void
     */
    public function checkout_customer_details( $order_id ) {
        // Output customer details
        woocommerce_order_details_table( $order_id );
    }

    /**
     * Checkout order notes
     *
     * @param int $order_id Order ID.
     * @return void
     */
    public function checkout_order_notes( $order_id ) {
        // Get order
        $order = wc_get_order( $order_id );
        
        // Get order notes
        $order_notes = $order->get_customer_note();
        
        // Output order notes
        if ( ! empty( $order_notes ) ) {
            ?>
            <div class="order-notes">
                <h3><?php esc_html_e( 'Order Notes', 'aqualuxe' ); ?></h3>
                <p><?php echo wp_kses_post( $order_notes ); ?></p>
            </div>
            <?php
        }
    }

    /**
     * Checkout terms and conditions
     *
     * @return void
     */
    public function checkout_terms_and_conditions() {
        // Output terms and conditions
        woocommerce_checkout_terms_and_conditions();
    }

    /**
     * Checkout order summary
     *
     * @return void
     */
    public function checkout_order_summary() {
        // Output order summary
        woocommerce_order_review();
    }

    /**
     * Checkout payment methods
     *
     * @return void
     */
    public function checkout_payment_methods() {
        // Output payment methods
        woocommerce_checkout_payment();
    }

    /**
     * Checkout place order button
     *
     * @return void
     */
    public function checkout_place_order_button() {
        // Output place order button
        ?>
        <div class="place-order">
            <button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="<?php esc_attr_e( 'Place Order', 'aqualuxe' ); ?>" data-value="<?php esc_attr_e( 'Place Order', 'aqualuxe' ); ?>"><?php esc_html_e( 'Place Order', 'aqualuxe' ); ?></button>
        </div>
        <?php
    }

    /**
     * Checkout order received
     *
     * @param int $order_id Order ID.
     * @return void
     */
    public function checkout_order_received( $order_id ) {
        // Get order
        $order = wc_get_order( $order_id );
        
        // Output order received
        ?>
        <div class="order-received">
            <div class="order-received-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <h2><?php esc_html_e( 'Thank You!', 'aqualuxe' ); ?></h2>
            <p><?php esc_html_e( 'Your order has been received.', 'aqualuxe' ); ?></p>
            <ul class="order-details">
                <li class="order-number">
                    <span class="label"><?php esc_html_e( 'Order Number:', 'aqualuxe' ); ?></span>
                    <span class="value"><?php echo esc_html( $order->get_order_number() ); ?></span>
                </li>
                <li class="order-date">
                    <span class="label"><?php esc_html_e( 'Date:', 'aqualuxe' ); ?></span>
                    <span class="value"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></span>
                </li>
                <li class="order-total">
                    <span class="label"><?php esc_html_e( 'Total:', 'aqualuxe' ); ?></span>
                    <span class="value"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></span>
                </li>
                <li class="order-payment-method">
                    <span class="label"><?php esc_html_e( 'Payment Method:', 'aqualuxe' ); ?></span>
                    <span class="value"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></span>
                </li>
            </ul>
        </div>
        <?php
    }

    /**
     * Checkout order details
     *
     * @param int $order_id Order ID.
     * @return void
     */
    public function checkout_order_details( $order_id ) {
        // Output order details
        woocommerce_order_details_table( $order_id );
    }

    /**
     * Checkout order again button
     *
     * @param int $order_id Order ID.
     * @return void
     */
    public function checkout_order_again_button( $order_id ) {
        // Output order again button
        woocommerce_order_again_button( $order_id );
    }

    /**
     * Checkout order downloads
     *
     * @param int $order_id Order ID.
     * @return void
     */
    public function checkout_order_downloads( $order_id ) {
        // Get order
        $order = wc_get_order( $order_id );
        
        // Check if order has downloadable items
        if ( $order->has_downloadable_item() && $order->is_download_permitted() ) {
            // Output order downloads
            woocommerce_order_downloads_table( $order_id );
        }
    }

    /**
     * Checkout order actions
     *
     * @param int $order_id Order ID.
     * @return void
     */
    public function checkout_order_actions( $order_id ) {
        // Output order actions
        ?>
        <div class="order-actions">
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="button"><?php esc_html_e( 'Continue Shopping', 'aqualuxe' ); ?></a>
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>" class="button"><?php esc_html_e( 'My Account', 'aqualuxe' ); ?></a>
        </div>
        <?php
    }

    /**
     * Checkout order meta
     *
     * @param int $order_id Order ID.
     * @return void
     */
    public function checkout_order_meta( $order_id ) {
        // Get order
        $order = wc_get_order( $order_id );
        
        // Get order meta
        $order_meta = $order->get_meta_data();
        
        // Output order meta
        if ( ! empty( $order_meta ) ) {
            ?>
            <div class="order-meta">
                <h3><?php esc_html_e( 'Order Meta', 'aqualuxe' ); ?></h3>
                <ul>
                    <?php foreach ( $order_meta as $meta ) : ?>
                        <li>
                            <span class="label"><?php echo esc_html( $meta->key ); ?>:</span>
                            <span class="value"><?php echo esc_html( $meta->value ); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php
        }
    }

    /**
     * Checkout order tracking
     *
     * @param int $order_id Order ID.
     * @return void
     */
    public function checkout_order_tracking( $order_id ) {
        // Get order
        $order = wc_get_order( $order_id );
        
        // Get tracking number
        $tracking_number = $order->get_meta( '_tracking_number' );
        
        // Get tracking provider
        $tracking_provider = $order->get_meta( '_tracking_provider' );
        
        // Output tracking information
        if ( ! empty( $tracking_number ) && ! empty( $tracking_provider ) ) {
            ?>
            <div class="order-tracking">
                <h3><?php esc_html_e( 'Tracking Information', 'aqualuxe' ); ?></h3>
                <p>
                    <span class="label"><?php esc_html_e( 'Tracking Number:', 'aqualuxe' ); ?></span>
                    <span class="value"><?php echo esc_html( $tracking_number ); ?></span>
                </p>
                <p>
                    <span class="label"><?php esc_html_e( 'Tracking Provider:', 'aqualuxe' ); ?></span>
                    <span class="value"><?php echo esc_html( $tracking_provider ); ?></span>
                </p>
            </div>
            <?php
        }
    }
}