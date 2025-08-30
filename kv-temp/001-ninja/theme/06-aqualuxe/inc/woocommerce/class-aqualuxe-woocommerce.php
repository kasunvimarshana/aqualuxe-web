<?php
/**
 * AquaLuxe WooCommerce Integration
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * WooCommerce Integration Class
 */
class AquaLuxe_WooCommerce {
    /**
     * Constructor
     */
    public function __construct() {
        // Include required files
        $this->includes();
        
        // Setup theme for WooCommerce
        add_action( 'after_setup_theme', array( $this, 'setup' ) );
        
        // Enqueue scripts and styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        
        // Remove default WooCommerce styles
        add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
        
        // Modify WooCommerce templates
        add_filter( 'woocommerce_locate_template', array( $this, 'locate_template' ), 10, 3 );
        
        // Modify WooCommerce wrapper
        remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
        remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
        add_action( 'woocommerce_before_main_content', array( $this, 'wrapper_start' ), 10 );
        add_action( 'woocommerce_after_main_content', array( $this, 'wrapper_end' ), 10 );
        
        // Modify product loop
        add_action( 'woocommerce_before_shop_loop_item', array( $this, 'product_wrapper_start' ), 5 );
        add_action( 'woocommerce_after_shop_loop_item', array( $this, 'product_wrapper_end' ), 15 );
        add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_image_wrapper_start' ), 5 );
        add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_image_wrapper_end' ), 15 );
        
        // Add product hover effects
        add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_hover_elements' ), 10 );
        
        // Modify product title
        remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
        add_action( 'woocommerce_shop_loop_item_title', array( $this, 'template_loop_product_title' ), 10 );
        
        // Add quick view
        if ( get_theme_mod( 'aqualuxe_quick_view', true ) ) {
            add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'quick_view_button' ), 12 );
            add_action( 'wp_ajax_aqualuxe_quick_view', array( $this, 'quick_view_ajax' ) );
            add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', array( $this, 'quick_view_ajax' ) );
            add_action( 'wp_footer', array( $this, 'quick_view_modal' ) );
        }
        
        // Add wishlist
        if ( get_theme_mod( 'aqualuxe_wishlist', true ) ) {
            add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'wishlist_button' ), 11 );
            add_action( 'wp_ajax_aqualuxe_wishlist_add', array( $this, 'wishlist_add_ajax' ) );
            add_action( 'wp_ajax_nopriv_aqualuxe_wishlist_add', array( $this, 'wishlist_add_ajax' ) );
            add_action( 'wp_ajax_aqualuxe_wishlist_remove', array( $this, 'wishlist_remove_ajax' ) );
            add_action( 'wp_ajax_nopriv_aqualuxe_wishlist_remove', array( $this, 'wishlist_remove_ajax' ) );
        }
        
        // AJAX add to cart
        if ( get_theme_mod( 'aqualuxe_ajax_add_to_cart', true ) ) {
            add_action( 'wp_ajax_aqualuxe_add_to_cart', array( $this, 'add_to_cart_ajax' ) );
            add_action( 'wp_ajax_nopriv_aqualuxe_add_to_cart', array( $this, 'add_to_cart_ajax' ) );
        }
        
        // Modify number of products per row
        add_filter( 'loop_shop_columns', array( $this, 'loop_columns' ) );
        
        // Modify number of products per page
        add_filter( 'loop_shop_per_page', array( $this, 'products_per_page' ) );
        
        // Add product filters
        add_action( 'woocommerce_before_shop_loop', array( $this, 'product_filter' ), 20 );
        
        // Modify single product layout
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
        add_action( 'woocommerce_single_product_summary', array( $this, 'template_single_title' ), 5 );
        
        // Add product tabs
        add_filter( 'woocommerce_product_tabs', array( $this, 'product_tabs' ) );
        
        // Add related products
        add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_args' ) );
        
        // Add upsells
        add_filter( 'woocommerce_upsell_display_args', array( $this, 'upsell_products_args' ) );
        
        // Modify cart layout
        add_action( 'woocommerce_before_cart', array( $this, 'cart_wrapper_start' ) );
        add_action( 'woocommerce_after_cart', array( $this, 'cart_wrapper_end' ) );
        
        // Modify checkout layout
        add_action( 'woocommerce_checkout_before_customer_details', array( $this, 'checkout_wrapper_start' ) );
        add_action( 'woocommerce_checkout_after_customer_details', array( $this, 'checkout_customer_details_end' ) );
        add_action( 'woocommerce_checkout_after_order_review', array( $this, 'checkout_wrapper_end' ) );
        
        // Add multi-currency support
        if ( get_theme_mod( 'aqualuxe_multi_currency', false ) ) {
            add_action( 'wp_head', array( $this, 'multi_currency_css' ) );
            add_action( 'woocommerce_before_shop_loop', array( $this, 'currency_switcher' ), 15 );
            add_action( 'woocommerce_before_single_product', array( $this, 'currency_switcher' ), 5 );
            add_filter( 'woocommerce_currency', array( $this, 'change_currency' ) );
            add_filter( 'woocommerce_currency_symbol', array( $this, 'change_currency_symbol' ), 10, 2 );
        }
        
        // Add international shipping optimization
        if ( get_theme_mod( 'aqualuxe_international_shipping', false ) ) {
            add_filter( 'woocommerce_package_rates', array( $this, 'international_shipping_rates' ), 10, 2 );
            add_action( 'woocommerce_review_order_before_shipping', array( $this, 'add_shipping_notice' ) );
        }
    }

    /**
     * Include required files
     */
    private function includes() {
        // Include template functions
        require_once AQUALUXE_DIR . 'inc/woocommerce/woocommerce-template-functions.php';
        
        // Include template hooks
        require_once AQUALUXE_DIR . 'inc/woocommerce/woocommerce-template-hooks.php';
    }

    /**
     * Setup theme for WooCommerce
     */
    public function setup() {
        // Add WooCommerce theme support
        add_theme_support( 'woocommerce', array(
            'thumbnail_image_width'         => 400,
            'single_image_width'            => 800,
            'product_grid'                  => array(
                'default_rows'    => 3,
                'min_rows'        => 2,
                'max_rows'        => 8,
                'default_columns' => 4,
                'min_columns'     => 2,
                'max_columns'     => 6,
            ),
        ) );
        
        // Add gallery zoom, lightbox, and slider support
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Enqueue WooCommerce styles
        wp_enqueue_style(
            'aqualuxe-woocommerce',
            AQUALUXE_URI . 'assets/css/woocommerce.css',
            array(),
            AQUALUXE_VERSION
        );
        
        // Enqueue WooCommerce scripts
        wp_enqueue_script(
            'aqualuxe-woocommerce',
            AQUALUXE_URI . 'assets/js/woocommerce.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script(
            'aqualuxe-woocommerce',
            'aqualuxeWooCommerce',
            array(
                'ajaxUrl'           => admin_url( 'admin-ajax.php' ),
                'nonce'             => wp_create_nonce( 'aqualuxe-woocommerce-nonce' ),
                'quickViewEnabled'  => get_theme_mod( 'aqualuxe_quick_view', true ),
                'wishlistEnabled'   => get_theme_mod( 'aqualuxe_wishlist', true ),
                'ajaxCartEnabled'   => get_theme_mod( 'aqualuxe_ajax_add_to_cart', true ),
                'i18n'              => array(
                    'addToCart'     => esc_html__( 'Add to Cart', 'aqualuxe' ),
                    'addingToCart'  => esc_html__( 'Adding...', 'aqualuxe' ),
                    'addedToCart'   => esc_html__( 'Added to Cart', 'aqualuxe' ),
                    'viewCart'      => esc_html__( 'View Cart', 'aqualuxe' ),
                    'quickView'     => esc_html__( 'Quick View', 'aqualuxe' ),
                    'loading'       => esc_html__( 'Loading...', 'aqualuxe' ),
                    'addToWishlist' => esc_html__( 'Add to Wishlist', 'aqualuxe' ),
                    'inWishlist'    => esc_html__( 'In Wishlist', 'aqualuxe' ),
                ),
            )
        );
    }

    /**
     * Locate WooCommerce template files
     *
     * @param string $template Template file.
     * @param string $template_name Template name.
     * @param string $template_path Template path.
     * @return string
     */
    public function locate_template( $template, $template_name, $template_path ) {
        // Check if the template exists in the theme
        $theme_template = locate_template( array(
            trailingslashit( 'woocommerce' ) . $template_name,
        ) );
        
        // Return the theme template if it exists
        if ( $theme_template ) {
            return $theme_template;
        }
        
        // Return the default template
        return $template;
    }

    /**
     * WooCommerce wrapper start
     */
    public function wrapper_start() {
        echo '<div id="primary" class="content-area">';
        echo '<main id="main" class="site-main" role="main">';
        echo '<div class="woocommerce-content">';
    }

    /**
     * WooCommerce wrapper end
     */
    public function wrapper_end() {
        echo '</div><!-- .woocommerce-content -->';
        echo '</main><!-- #main -->';
        echo '</div><!-- #primary -->';
    }

    /**
     * Product wrapper start
     */
    public function product_wrapper_start() {
        echo '<div class="product-inner">';
    }

    /**
     * Product wrapper end
     */
    public function product_wrapper_end() {
        echo '</div><!-- .product-inner -->';
    }

    /**
     * Product image wrapper start
     */
    public function product_image_wrapper_start() {
        echo '<div class="product-image-wrapper">';
    }

    /**
     * Product image wrapper end
     */
    public function product_image_wrapper_end() {
        echo '</div><!-- .product-image-wrapper -->';
    }

    /**
     * Product hover elements
     */
    public function product_hover_elements() {
        echo '<div class="product-hover-overlay">';
        echo '<div class="product-hover-buttons">';
        
        // Add to cart button will be added by WooCommerce
        
        echo '</div><!-- .product-hover-buttons -->';
        echo '</div><!-- .product-hover-overlay -->';
    }

    /**
     * Template loop product title
     */
    public function template_loop_product_title() {
        echo '<h2 class="woocommerce-loop-product__title"><a href="' . esc_url( get_the_permalink() ) . '">' . get_the_title() . '</a></h2>';
    }

    /**
     * Quick view button
     */
    public function quick_view_button() {
        echo '<a href="#" class="aqualuxe-quick-view" data-product-id="' . esc_attr( get_the_ID() ) . '">' . esc_html__( 'Quick View', 'aqualuxe' ) . '</a>';
    }

    /**
     * Quick view AJAX handler
     */
    public function quick_view_ajax() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce', 'aqualuxe' ) ) );
        }
        
        // Get product ID
        $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
        
        if ( ! $product_id ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid product ID', 'aqualuxe' ) ) );
        }
        
        // Get product
        $product = wc_get_product( $product_id );
        
        if ( ! $product ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Product not found', 'aqualuxe' ) ) );
        }
        
        // Get quick view template
        ob_start();
        include AQUALUXE_DIR . 'woocommerce/quick-view.php';
        $html = ob_get_clean();
        
        wp_send_json_success( array( 'html' => $html ) );
    }

    /**
     * Quick view modal
     */
    public function quick_view_modal() {
        ?>
        <div id="aqualuxe-quick-view-modal" class="aqualuxe-quick-view-modal">
            <div class="aqualuxe-quick-view-overlay"></div>
            <div class="aqualuxe-quick-view-wrapper">
                <div class="aqualuxe-quick-view-close">&times;</div>
                <div class="aqualuxe-quick-view-content">
                    <div class="aqualuxe-quick-view-loader">
                        <div class="aqualuxe-quick-view-loader-spinner"></div>
                    </div>
                    <div class="aqualuxe-quick-view-inner"></div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Wishlist button
     */
    public function wishlist_button() {
        $product_id = get_the_ID();
        $in_wishlist = $this->is_in_wishlist( $product_id );
        $class = $in_wishlist ? 'aqualuxe-wishlist-button in-wishlist' : 'aqualuxe-wishlist-button';
        $text = $in_wishlist ? esc_html__( 'In Wishlist', 'aqualuxe' ) : esc_html__( 'Add to Wishlist', 'aqualuxe' );
        
        echo '<a href="#" class="' . esc_attr( $class ) . '" data-product-id="' . esc_attr( $product_id ) . '">' . esc_html( $text ) . '</a>';
    }

    /**
     * Check if product is in wishlist
     *
     * @param int $product_id Product ID.
     * @return bool
     */
    private function is_in_wishlist( $product_id ) {
        $wishlist = $this->get_wishlist();
        
        return in_array( $product_id, $wishlist, true );
    }

    /**
     * Get wishlist
     *
     * @return array
     */
    private function get_wishlist() {
        $wishlist = array();
        
        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            $wishlist = get_user_meta( $user_id, 'aqualuxe_wishlist', true );
            
            if ( ! is_array( $wishlist ) ) {
                $wishlist = array();
            }
        } else {
            $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : array();
            
            if ( ! is_array( $wishlist ) ) {
                $wishlist = array();
            }
        }
        
        return $wishlist;
    }

    /**
     * Wishlist add AJAX handler
     */
    public function wishlist_add_ajax() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce', 'aqualuxe' ) ) );
        }
        
        // Get product ID
        $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
        
        if ( ! $product_id ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid product ID', 'aqualuxe' ) ) );
        }
        
        // Get wishlist
        $wishlist = $this->get_wishlist();
        
        // Add product to wishlist
        if ( ! in_array( $product_id, $wishlist, true ) ) {
            $wishlist[] = $product_id;
        }
        
        // Save wishlist
        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            update_user_meta( $user_id, 'aqualuxe_wishlist', $wishlist );
        } else {
            setcookie( 'aqualuxe_wishlist', json_encode( $wishlist ), time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
        }
        
        wp_send_json_success( array( 'message' => esc_html__( 'Product added to wishlist', 'aqualuxe' ) ) );
    }

    /**
     * Wishlist remove AJAX handler
     */
    public function wishlist_remove_ajax() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce', 'aqualuxe' ) ) );
        }
        
        // Get product ID
        $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
        
        if ( ! $product_id ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid product ID', 'aqualuxe' ) ) );
        }
        
        // Get wishlist
        $wishlist = $this->get_wishlist();
        
        // Remove product from wishlist
        $wishlist = array_diff( $wishlist, array( $product_id ) );
        
        // Save wishlist
        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            update_user_meta( $user_id, 'aqualuxe_wishlist', $wishlist );
        } else {
            setcookie( 'aqualuxe_wishlist', json_encode( $wishlist ), time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
        }
        
        wp_send_json_success( array( 'message' => esc_html__( 'Product removed from wishlist', 'aqualuxe' ) ) );
    }

    /**
     * AJAX add to cart handler
     */
    public function add_to_cart_ajax() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce', 'aqualuxe' ) ) );
        }
        
        // Get product ID
        $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
        
        if ( ! $product_id ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid product ID', 'aqualuxe' ) ) );
        }
        
        // Get quantity
        $quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;
        
        // Get variation ID
        $variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
        
        // Get variation attributes
        $variation = array();
        
        if ( isset( $_POST['variation'] ) && is_array( $_POST['variation'] ) ) {
            foreach ( $_POST['variation'] as $key => $value ) {
                $variation[ sanitize_text_field( $key ) ] = sanitize_text_field( $value );
            }
        }
        
        // Add to cart
        $cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
        
        if ( $cart_item_key ) {
            WC_AJAX::get_refreshed_fragments();
        } else {
            wp_send_json_error( array( 'message' => esc_html__( 'Error adding product to cart', 'aqualuxe' ) ) );
        }
    }

    /**
     * Set number of products per row
     *
     * @return int
     */
    public function loop_columns() {
        return get_theme_mod( 'aqualuxe_shop_columns', 4 );
    }

    /**
     * Set number of products per page
     *
     * @return int
     */
    public function products_per_page() {
        return get_theme_mod( 'aqualuxe_shop_products_per_page', 12 );
    }

    /**
     * Add product filter
     */
    public function product_filter() {
        if ( get_theme_mod( 'aqualuxe_product_filter', true ) ) {
            wc_get_template( 'loop/filter.php' );
        }
    }

    /**
     * Template single product title
     */
    public function template_single_title() {
        echo '<h1 class="product_title entry-title">' . get_the_title() . '</h1>';
        
        // Add product subtitle if available
        $subtitle = get_post_meta( get_the_ID(), '_aqualuxe_product_subtitle', true );
        
        if ( $subtitle ) {
            echo '<h2 class="product-subtitle">' . esc_html( $subtitle ) . '</h2>';
        }
    }

    /**
     * Modify product tabs
     *
     * @param array $tabs Product tabs.
     * @return array
     */
    public function product_tabs( $tabs ) {
        // Add custom tabs
        if ( get_theme_mod( 'aqualuxe_product_care_tab', true ) ) {
            $tabs['care'] = array(
                'title'    => esc_html__( 'Care Instructions', 'aqualuxe' ),
                'priority' => 30,
                'callback' => array( $this, 'care_tab_content' ),
            );
        }
        
        if ( get_theme_mod( 'aqualuxe_product_shipping_tab', true ) ) {
            $tabs['shipping'] = array(
                'title'    => esc_html__( 'Shipping & Returns', 'aqualuxe' ),
                'priority' => 40,
                'callback' => array( $this, 'shipping_tab_content' ),
            );
        }
        
        return $tabs;
    }

    /**
     * Care tab content
     */
    public function care_tab_content() {
        // Get care instructions
        $care_instructions = get_post_meta( get_the_ID(), '_aqualuxe_care_instructions', true );
        
        if ( $care_instructions ) {
            echo wp_kses_post( wpautop( $care_instructions ) );
        } else {
            echo wp_kses_post( wpautop( get_theme_mod( 'aqualuxe_default_care_instructions', 'Care instructions for this product.' ) ) );
        }
    }

    /**
     * Shipping tab content
     */
    public function shipping_tab_content() {
        // Get shipping information
        $shipping_info = get_post_meta( get_the_ID(), '_aqualuxe_shipping_info', true );
        
        if ( $shipping_info ) {
            echo wp_kses_post( wpautop( $shipping_info ) );
        } else {
            echo wp_kses_post( wpautop( get_theme_mod( 'aqualuxe_default_shipping_info', 'Shipping and returns information for this product.' ) ) );
        }
    }

    /**
     * Modify related products args
     *
     * @param array $args Related products args.
     * @return array
     */
    public function related_products_args( $args ) {
        $args['posts_per_page'] = get_theme_mod( 'aqualuxe_related_products_count', 4 );
        $args['columns'] = get_theme_mod( 'aqualuxe_related_products_columns', 4 );
        
        return $args;
    }

    /**
     * Modify upsell products args
     *
     * @param array $args Upsell products args.
     * @return array
     */
    public function upsell_products_args( $args ) {
        $args['posts_per_page'] = get_theme_mod( 'aqualuxe_upsell_products_count', 4 );
        $args['columns'] = get_theme_mod( 'aqualuxe_upsell_products_columns', 4 );
        
        return $args;
    }

    /**
     * Cart wrapper start
     */
    public function cart_wrapper_start() {
        echo '<div class="aqualuxe-cart-wrapper">';
    }

    /**
     * Cart wrapper end
     */
    public function cart_wrapper_end() {
        echo '</div><!-- .aqualuxe-cart-wrapper -->';
    }

    /**
     * Checkout wrapper start
     */
    public function checkout_wrapper_start() {
        echo '<div class="aqualuxe-checkout-wrapper">';
        echo '<div class="aqualuxe-checkout-customer-details">';
    }

    /**
     * Checkout customer details end
     */
    public function checkout_customer_details_end() {
        echo '</div><!-- .aqualuxe-checkout-customer-details -->';
        echo '<div class="aqualuxe-checkout-order-review">';
    }

    /**
     * Checkout wrapper end
     */
    public function checkout_wrapper_end() {
        echo '</div><!-- .aqualuxe-checkout-order-review -->';
        echo '</div><!-- .aqualuxe-checkout-wrapper -->';
    }

    /**
     * Add multi-currency CSS
     */
    public function multi_currency_css() {
        if ( get_theme_mod( 'aqualuxe_multi_currency', false ) ) {
            ?>
            <style>
                .aqualuxe-currency-switcher {
                    margin-bottom: 20px;
                }
                
                .aqualuxe-currency-switcher select {
                    padding: 5px 10px;
                    border: 1px solid #ddd;
                    border-radius: 3px;
                }
            </style>
            <?php
        }
    }

    /**
     * Add currency switcher
     */
    public function currency_switcher() {
        if ( get_theme_mod( 'aqualuxe_multi_currency', false ) ) {
            $currencies = $this->get_available_currencies();
            $current_currency = isset( $_COOKIE['aqualuxe_currency'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_currency'] ) ) : get_option( 'woocommerce_currency' );
            
            ?>
            <div class="aqualuxe-currency-switcher">
                <select name="aqualuxe-currency" id="aqualuxe-currency">
                    <?php foreach ( $currencies as $code => $name ) : ?>
                        <option value="<?php echo esc_attr( $code ); ?>" <?php selected( $current_currency, $code ); ?>>
                            <?php echo esc_html( $name . ' (' . get_woocommerce_currency_symbol( $code ) . ')' ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php
        }
    }

    /**
     * Get available currencies
     *
     * @return array
     */
    private function get_available_currencies() {
        $currencies = array();
        $wc_currencies = get_woocommerce_currencies();
        
        // Get enabled currencies from theme mod
        $enabled_currencies = get_theme_mod( 'aqualuxe_enabled_currencies', array( 'USD', 'EUR', 'GBP' ) );
        
        if ( ! is_array( $enabled_currencies ) ) {
            $enabled_currencies = array( 'USD', 'EUR', 'GBP' );
        }
        
        foreach ( $enabled_currencies as $currency ) {
            if ( isset( $wc_currencies[ $currency ] ) ) {
                $currencies[ $currency ] = $wc_currencies[ $currency ];
            }
        }
        
        return $currencies;
    }

    /**
     * Change currency
     *
     * @param string $currency Currency code.
     * @return string
     */
    public function change_currency( $currency ) {
        if ( get_theme_mod( 'aqualuxe_multi_currency', false ) && isset( $_COOKIE['aqualuxe_currency'] ) ) {
            $currency = sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_currency'] ) );
        }
        
        return $currency;
    }

    /**
     * Change currency symbol
     *
     * @param string $symbol Currency symbol.
     * @param string $currency Currency code.
     * @return string
     */
    public function change_currency_symbol( $symbol, $currency ) {
        if ( get_theme_mod( 'aqualuxe_multi_currency', false ) && isset( $_COOKIE['aqualuxe_currency'] ) ) {
            $currency = sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_currency'] ) );
            $symbol = get_woocommerce_currency_symbol( $currency );
        }
        
        return $symbol;
    }

    /**
     * Optimize international shipping rates
     *
     * @param array $rates Shipping rates.
     * @param array $package Shipping package.
     * @return array
     */
    public function international_shipping_rates( $rates, $package ) {
        if ( ! get_theme_mod( 'aqualuxe_international_shipping', false ) ) {
            return $rates;
        }
        
        // Get customer country
        $customer_country = $package['destination']['country'];
        
        // Get store country
        $store_country = WC()->countries->get_base_country();
        
        // Check if international shipping
        if ( $customer_country !== $store_country ) {
            // Get international shipping zones
            $shipping_zones = $this->get_international_shipping_zones();
            
            // Find the zone for the customer country
            $customer_zone = 'rest_of_world';
            
            foreach ( $shipping_zones as $zone_id => $zone_data ) {
                if ( in_array( $customer_country, $zone_data['countries'], true ) ) {
                    $customer_zone = $zone_id;
                    break;
                }
            }
            
            // Apply zone-specific rates
            foreach ( $rates as $rate_id => $rate ) {
                if ( isset( $shipping_zones[ $customer_zone ]['rate_adjustment'] ) ) {
                    $adjustment = $shipping_zones[ $customer_zone ]['rate_adjustment'];
                    $rates[ $rate_id ]->cost *= $adjustment;
                }
            }
        }
        
        return $rates;
    }

    /**
     * Get international shipping zones
     *
     * @return array
     */
    private function get_international_shipping_zones() {
        return array(
            'europe' => array(
                'name'           => esc_html__( 'Europe', 'aqualuxe' ),
                'countries'      => WC()->countries->get_european_union_countries(),
                'rate_adjustment' => 1.0,
            ),
            'north_america' => array(
                'name'           => esc_html__( 'North America', 'aqualuxe' ),
                'countries'      => array( 'US', 'CA', 'MX' ),
                'rate_adjustment' => 1.2,
            ),
            'asia_pacific' => array(
                'name'           => esc_html__( 'Asia Pacific', 'aqualuxe' ),
                'countries'      => array( 'AU', 'NZ', 'JP', 'SG', 'KR', 'CN', 'HK', 'TW' ),
                'rate_adjustment' => 1.5,
            ),
            'rest_of_world' => array(
                'name'           => esc_html__( 'Rest of World', 'aqualuxe' ),
                'countries'      => array(),
                'rate_adjustment' => 2.0,
            ),
        );
    }

    /**
     * Add shipping notice
     */
    public function add_shipping_notice() {
        if ( ! get_theme_mod( 'aqualuxe_international_shipping', false ) ) {
            return;
        }
        
        // Get customer country
        $customer_country = WC()->customer->get_shipping_country();
        
        // Get store country
        $store_country = WC()->countries->get_base_country();
        
        // Check if international shipping
        if ( $customer_country !== $store_country ) {
            // Get international shipping zones
            $shipping_zones = $this->get_international_shipping_zones();
            
            // Find the zone for the customer country
            $customer_zone = 'rest_of_world';
            
            foreach ( $shipping_zones as $zone_id => $zone_data ) {
                if ( in_array( $customer_country, $zone_data['countries'], true ) ) {
                    $customer_zone = $zone_id;
                    break;
                }
            }
            
            // Display notice
            echo '<div class="aqualuxe-shipping-notice">';
            echo '<p>' . esc_html__( 'International shipping rates apply. Please allow additional time for delivery.', 'aqualuxe' ) . '</p>';
            
            // Display estimated delivery time
            $delivery_times = array(
                'europe'        => esc_html__( '5-7 business days', 'aqualuxe' ),
                'north_america' => esc_html__( '7-10 business days', 'aqualuxe' ),
                'asia_pacific'  => esc_html__( '10-14 business days', 'aqualuxe' ),
                'rest_of_world' => esc_html__( '14-21 business days', 'aqualuxe' ),
            );
            
            if ( isset( $delivery_times[ $customer_zone ] ) ) {
                echo '<p>' . esc_html__( 'Estimated delivery time: ', 'aqualuxe' ) . esc_html( $delivery_times[ $customer_zone ] ) . '</p>';
            }
            
            echo '</div>';
        }
    }
}

// Initialize the class
new AquaLuxe_WooCommerce();