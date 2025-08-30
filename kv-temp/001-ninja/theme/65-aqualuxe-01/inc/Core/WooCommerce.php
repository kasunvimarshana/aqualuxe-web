<?php
/**
 * WooCommerce Class
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core;

/**
 * WooCommerce Class
 * 
 * Handles WooCommerce integration
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
        // Register hooks
        $this->register_hooks();
    }

    /**
     * Register hooks
     */
    private function register_hooks() {
        // Setup
        add_action( 'after_setup_theme', [ $this, 'setup' ] );
        add_filter( 'body_class', [ $this, 'body_classes' ] );
        
        // Assets
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        
        // Layout
        add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
        add_action( 'woocommerce_before_main_content', [ $this, 'wrapper_before' ] );
        add_action( 'woocommerce_after_main_content', [ $this, 'wrapper_after' ] );
        add_action( 'woocommerce_sidebar', [ $this, 'sidebar' ] );
        
        // Shop
        add_filter( 'woocommerce_product_loop_start', [ $this, 'product_loop_start' ] );
        add_filter( 'woocommerce_product_loop_end', [ $this, 'product_loop_end' ] );
        add_filter( 'woocommerce_shop_loop_item_title', [ $this, 'shop_loop_item_title' ] );
        add_filter( 'woocommerce_after_shop_loop_item_title', [ $this, 'after_shop_loop_item_title' ] );
        add_filter( 'woocommerce_after_shop_loop_item', [ $this, 'after_shop_loop_item' ] );
        add_filter( 'woocommerce_product_loop_title_classes', [ $this, 'product_loop_title_classes' ] );
        add_filter( 'woocommerce_pagination_args', [ $this, 'pagination_args' ] );
        
        // Product
        add_filter( 'woocommerce_output_related_products_args', [ $this, 'related_products_args' ] );
        add_filter( 'woocommerce_product_thumbnails_columns', [ $this, 'thumbnail_columns' ] );
        add_filter( 'woocommerce_product_review_comment_form_args', [ $this, 'review_comment_form_args' ] );
        
        // Cart
        add_filter( 'woocommerce_cross_sells_columns', [ $this, 'cross_sells_columns' ] );
        add_filter( 'woocommerce_cross_sells_total', [ $this, 'cross_sells_total' ] );
        add_filter( 'woocommerce_cart_item_thumbnail', [ $this, 'cart_item_thumbnail' ], 10, 3 );
        add_filter( 'woocommerce_cart_item_name', [ $this, 'cart_item_name' ], 10, 3 );
        add_filter( 'woocommerce_cart_item_price', [ $this, 'cart_item_price' ], 10, 3 );
        add_filter( 'woocommerce_cart_item_subtotal', [ $this, 'cart_item_subtotal' ], 10, 3 );
        
        // Checkout
        add_filter( 'woocommerce_checkout_fields', [ $this, 'checkout_fields' ] );
        add_filter( 'woocommerce_checkout_posted_data', [ $this, 'checkout_posted_data' ] );
        add_filter( 'woocommerce_countries_allowed_countries', [ $this, 'allowed_countries' ] );
        add_filter( 'woocommerce_default_address_fields', [ $this, 'default_address_fields' ] );
        
        // Account
        add_filter( 'woocommerce_my_account_my_orders_columns', [ $this, 'my_orders_columns' ] );
        add_filter( 'woocommerce_account_menu_items', [ $this, 'account_menu_items' ] );
        add_filter( 'woocommerce_get_endpoint_url', [ $this, 'get_endpoint_url' ], 10, 4 );
        
        // AJAX
        add_action( 'wp_ajax_aqualuxe_add_to_cart', [ $this, 'ajax_add_to_cart' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_add_to_cart', [ $this, 'ajax_add_to_cart' ] );
        add_action( 'wp_ajax_aqualuxe_update_cart', [ $this, 'ajax_update_cart' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_update_cart', [ $this, 'ajax_update_cart' ] );
        add_action( 'wp_ajax_aqualuxe_remove_from_cart', [ $this, 'ajax_remove_from_cart' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_remove_from_cart', [ $this, 'ajax_remove_from_cart' ] );
        add_action( 'wp_ajax_aqualuxe_add_to_wishlist', [ $this, 'ajax_add_to_wishlist' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_add_to_wishlist', [ $this, 'ajax_add_to_wishlist' ] );
        add_action( 'wp_ajax_aqualuxe_remove_from_wishlist', [ $this, 'ajax_remove_from_wishlist' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_remove_from_wishlist', [ $this, 'ajax_remove_from_wishlist' ] );
        add_action( 'wp_ajax_aqualuxe_quick_view', [ $this, 'ajax_quick_view' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', [ $this, 'ajax_quick_view' ] );
        
        // Admin
        add_action( 'admin_menu', [ $this, 'add_woocommerce_menu' ] );
        add_action( 'admin_init', [ $this, 'register_woocommerce_settings' ] );
    }

    /**
     * Setup
     */
    public function setup() {
        // Add theme support for WooCommerce
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }

    /**
     * Body classes
     *
     * @param array $classes Body classes
     * @return array
     */
    public function body_classes( $classes ) {
        // Add WooCommerce class
        $classes[] = 'woocommerce-active';
        
        // Add shop class
        if ( is_shop() ) {
            $classes[] = 'woocommerce-shop';
        }
        
        // Add product class
        if ( is_product() ) {
            $classes[] = 'woocommerce-product';
        }
        
        // Add cart class
        if ( is_cart() ) {
            $classes[] = 'woocommerce-cart';
        }
        
        // Add checkout class
        if ( is_checkout() ) {
            $classes[] = 'woocommerce-checkout';
        }
        
        // Add account class
        if ( is_account_page() ) {
            $classes[] = 'woocommerce-account';
        }
        
        return $classes;
    }

    /**
     * Enqueue styles
     */
    public function enqueue_styles() {
        // WooCommerce styles
        wp_enqueue_style(
            'aqualuxe-woocommerce',
            AQUALUXE_ASSETS_URI . 'css/woocommerce.css',
            [],
            AQUALUXE_VERSION
        );
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        // WooCommerce scripts
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
            'aqualuxeWoocommerce',
            [
                'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
                'nonce'         => wp_create_nonce( 'aqualuxe-woocommerce-nonce' ),
                'cartUrl'       => wc_get_cart_url(),
                'checkoutUrl'   => wc_get_checkout_url(),
                'isCart'        => is_cart(),
                'isCheckout'    => is_checkout(),
                'isAccount'     => is_account_page(),
                'isProduct'     => is_product(),
                'isShop'        => is_shop(),
                'cartTotal'     => WC()->cart->get_cart_contents_count(),
                'cartSubtotal'  => WC()->cart->get_cart_subtotal(),
                'i18n'          => [
                    'addToCart'     => esc_html__( 'Add to Cart', 'aqualuxe' ),
                    'addingToCart'  => esc_html__( 'Adding...', 'aqualuxe' ),
                    'addedToCart'   => esc_html__( 'Added to Cart', 'aqualuxe' ),
                    'viewCart'      => esc_html__( 'View Cart', 'aqualuxe' ),
                    'checkout'      => esc_html__( 'Checkout', 'aqualuxe' ),
                    'continue'      => esc_html__( 'Continue Shopping', 'aqualuxe' ),
                    'error'         => esc_html__( 'Error', 'aqualuxe' ),
                    'success'       => esc_html__( 'Success', 'aqualuxe' ),
                    'warning'       => esc_html__( 'Warning', 'aqualuxe' ),
                    'info'          => esc_html__( 'Info', 'aqualuxe' ),
                    'close'         => esc_html__( 'Close', 'aqualuxe' ),
                    'quickView'     => esc_html__( 'Quick View', 'aqualuxe' ),
                    'addToWishlist' => esc_html__( 'Add to Wishlist', 'aqualuxe' ),
                    'removeFromWishlist' => esc_html__( 'Remove from Wishlist', 'aqualuxe' ),
                    'addedToWishlist' => esc_html__( 'Added to Wishlist', 'aqualuxe' ),
                    'removedFromWishlist' => esc_html__( 'Removed from Wishlist', 'aqualuxe' ),
                    'viewWishlist'  => esc_html__( 'View Wishlist', 'aqualuxe' ),
                    'compare'       => esc_html__( 'Compare', 'aqualuxe' ),
                    'addToCompare'  => esc_html__( 'Add to Compare', 'aqualuxe' ),
                    'removeFromCompare' => esc_html__( 'Remove from Compare', 'aqualuxe' ),
                    'addedToCompare' => esc_html__( 'Added to Compare', 'aqualuxe' ),
                    'removedFromCompare' => esc_html__( 'Removed from Compare', 'aqualuxe' ),
                    'viewCompare'   => esc_html__( 'View Compare', 'aqualuxe' ),
                ],
            ]
        );
    }

    /**
     * Wrapper before
     */
    public function wrapper_before() {
        ?>
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
        <?php
    }

    /**
     * Wrapper after
     */
    public function wrapper_after() {
        ?>
            </main><!-- #main -->
        </div><!-- #primary -->
        <?php
    }

    /**
     * Sidebar
     */
    public function sidebar() {
        get_sidebar( 'shop' );
    }

    /**
     * Product loop start
     *
     * @param string $html Loop start HTML
     * @return string
     */
    public function product_loop_start( $html ) {
        return '<ul class="products columns-' . esc_attr( wc_get_loop_prop( 'columns' ) ) . '">';
    }

    /**
     * Product loop end
     *
     * @param string $html Loop end HTML
     * @return string
     */
    public function product_loop_end( $html ) {
        return '</ul>';
    }

    /**
     * Shop loop item title
     */
    public function shop_loop_item_title() {
        echo '<h2 class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">' . get_the_title() . '</h2>';
    }

    /**
     * After shop loop item title
     */
    public function after_shop_loop_item_title() {
        // Price
        woocommerce_template_loop_price();
        
        // Rating
        woocommerce_template_loop_rating();
    }

    /**
     * After shop loop item
     */
    public function after_shop_loop_item() {
        // Add to cart
        woocommerce_template_loop_add_to_cart();
        
        // Quick view
        $this->quick_view_button();
        
        // Wishlist
        $this->wishlist_button();
    }

    /**
     * Product loop title classes
     *
     * @param string $classes Title classes
     * @return string
     */
    public function product_loop_title_classes( $classes ) {
        return $classes . ' product-title';
    }

    /**
     * Pagination args
     *
     * @param array $args Pagination args
     * @return array
     */
    public function pagination_args( $args ) {
        $args['prev_text'] = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M10.828 12l4.95 4.95-1.414 1.414L8 12l6.364-6.364 1.414 1.414z"/></svg>' . esc_html__( 'Previous', 'aqualuxe' );
        $args['next_text'] = esc_html__( 'Next', 'aqualuxe' ) . '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/></svg>';
        
        return $args;
    }

    /**
     * Related products args
     *
     * @param array $args Related products args
     * @return array
     */
    public function related_products_args( $args ) {
        $args['posts_per_page'] = 4;
        $args['columns'] = 4;
        
        return $args;
    }

    /**
     * Thumbnail columns
     *
     * @param int $columns Thumbnail columns
     * @return int
     */
    public function thumbnail_columns( $columns ) {
        return 4;
    }

    /**
     * Review comment form args
     *
     * @param array $args Comment form args
     * @return array
     */
    public function review_comment_form_args( $args ) {
        $args['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'aqualuxe' ) . ( wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '' ) . '</label><select name="rating" id="rating" required>
            <option value="">' . esc_html__( 'Rate&hellip;', 'aqualuxe' ) . '</option>
            <option value="5">' . esc_html__( 'Perfect', 'aqualuxe' ) . '</option>
            <option value="4">' . esc_html__( 'Good', 'aqualuxe' ) . '</option>
            <option value="3">' . esc_html__( 'Average', 'aqualuxe' ) . '</option>
            <option value="2">' . esc_html__( 'Not that bad', 'aqualuxe' ) . '</option>
            <option value="1">' . esc_html__( 'Very poor', 'aqualuxe' ) . '</option>
        </select></div>';
        
        $args['comment_field'] .= '<div class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'aqualuxe' ) . '&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></div>';
        
        return $args;
    }

    /**
     * Cross sells columns
     *
     * @param int $columns Cross sells columns
     * @return int
     */
    public function cross_sells_columns( $columns ) {
        return 4;
    }

    /**
     * Cross sells total
     *
     * @param int $total Cross sells total
     * @return int
     */
    public function cross_sells_total( $total ) {
        return 4;
    }

    /**
     * Cart item thumbnail
     *
     * @param string $thumbnail Thumbnail HTML
     * @param array  $cart_item Cart item
     * @param string $cart_item_key Cart item key
     * @return string
     */
    public function cart_item_thumbnail( $thumbnail, $cart_item, $cart_item_key ) {
        return $thumbnail;
    }

    /**
     * Cart item name
     *
     * @param string $name Name HTML
     * @param array  $cart_item Cart item
     * @param string $cart_item_key Cart item key
     * @return string
     */
    public function cart_item_name( $name, $cart_item, $cart_item_key ) {
        return $name;
    }

    /**
     * Cart item price
     *
     * @param string $price Price HTML
     * @param array  $cart_item Cart item
     * @param string $cart_item_key Cart item key
     * @return string
     */
    public function cart_item_price( $price, $cart_item, $cart_item_key ) {
        return $price;
    }

    /**
     * Cart item subtotal
     *
     * @param string $subtotal Subtotal HTML
     * @param array  $cart_item Cart item
     * @param string $cart_item_key Cart item key
     * @return string
     */
    public function cart_item_subtotal( $subtotal, $cart_item, $cart_item_key ) {
        return $subtotal;
    }

    /**
     * Checkout fields
     *
     * @param array $fields Checkout fields
     * @return array
     */
    public function checkout_fields( $fields ) {
        return $fields;
    }

    /**
     * Checkout posted data
     *
     * @param array $data Posted data
     * @return array
     */
    public function checkout_posted_data( $data ) {
        return $data;
    }

    /**
     * Allowed countries
     *
     * @param array $countries Allowed countries
     * @return array
     */
    public function allowed_countries( $countries ) {
        return $countries;
    }

    /**
     * Default address fields
     *
     * @param array $fields Address fields
     * @return array
     */
    public function default_address_fields( $fields ) {
        return $fields;
    }

    /**
     * My orders columns
     *
     * @param array $columns My orders columns
     * @return array
     */
    public function my_orders_columns( $columns ) {
        return $columns;
    }

    /**
     * Account menu items
     *
     * @param array $items Account menu items
     * @return array
     */
    public function account_menu_items( $items ) {
        return $items;
    }

    /**
     * Get endpoint URL
     *
     * @param string $url Endpoint URL
     * @param string $endpoint Endpoint
     * @param string $value Value
     * @param string $permalink Permalink
     * @return string
     */
    public function get_endpoint_url( $url, $endpoint, $value, $permalink ) {
        return $url;
    }

    /**
     * AJAX add to cart
     */
    public function ajax_add_to_cart() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        
        // Check product ID
        if ( ! isset( $_POST['product_id'] ) ) {
            wp_send_json_error( 'Invalid product ID' );
        }
        
        // Get product ID
        $product_id = absint( $_POST['product_id'] );
        
        // Get quantity
        $quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;
        
        // Get variation ID
        $variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
        
        // Get variation
        $variation = isset( $_POST['variation'] ) ? (array) $_POST['variation'] : [];
        
        // Add to cart
        $added = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
        
        if ( $added ) {
            // Get cart fragments
            ob_start();
            woocommerce_mini_cart();
            $mini_cart = ob_get_clean();
            
            // Get cart count
            $cart_count = WC()->cart->get_cart_contents_count();
            
            // Get cart subtotal
            $cart_subtotal = WC()->cart->get_cart_subtotal();
            
            // Send success
            wp_send_json_success( [
                'fragments' => apply_filters(
                    'woocommerce_add_to_cart_fragments',
                    [
                        '.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
                    ]
                ),
                'cart_hash' => WC()->cart->get_cart_hash(),
                'cart_count' => $cart_count,
                'cart_subtotal' => $cart_subtotal,
            ] );
        } else {
            wp_send_json_error( 'Failed to add to cart' );
        }
    }

    /**
     * AJAX update cart
     */
    public function ajax_update_cart() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        
        // Check cart item key
        if ( ! isset( $_POST['cart_item_key'] ) ) {
            wp_send_json_error( 'Invalid cart item key' );
        }
        
        // Get cart item key
        $cart_item_key = sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) );
        
        // Get quantity
        $quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;
        
        // Update cart
        $updated = WC()->cart->set_quantity( $cart_item_key, $quantity );
        
        if ( $updated ) {
            // Get cart fragments
            ob_start();
            woocommerce_mini_cart();
            $mini_cart = ob_get_clean();
            
            // Get cart count
            $cart_count = WC()->cart->get_cart_contents_count();
            
            // Get cart subtotal
            $cart_subtotal = WC()->cart->get_cart_subtotal();
            
            // Send success
            wp_send_json_success( [
                'fragments' => apply_filters(
                    'woocommerce_add_to_cart_fragments',
                    [
                        '.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
                    ]
                ),
                'cart_hash' => WC()->cart->get_cart_hash(),
                'cart_count' => $cart_count,
                'cart_subtotal' => $cart_subtotal,
            ] );
        } else {
            wp_send_json_error( 'Failed to update cart' );
        }
    }

    /**
     * AJAX remove from cart
     */
    public function ajax_remove_from_cart() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        
        // Check cart item key
        if ( ! isset( $_POST['cart_item_key'] ) ) {
            wp_send_json_error( 'Invalid cart item key' );
        }
        
        // Get cart item key
        $cart_item_key = sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) );
        
        // Remove from cart
        $removed = WC()->cart->remove_cart_item( $cart_item_key );
        
        if ( $removed ) {
            // Get cart fragments
            ob_start();
            woocommerce_mini_cart();
            $mini_cart = ob_get_clean();
            
            // Get cart count
            $cart_count = WC()->cart->get_cart_contents_count();
            
            // Get cart subtotal
            $cart_subtotal = WC()->cart->get_cart_subtotal();
            
            // Send success
            wp_send_json_success( [
                'fragments' => apply_filters(
                    'woocommerce_add_to_cart_fragments',
                    [
                        '.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
                    ]
                ),
                'cart_hash' => WC()->cart->get_cart_hash(),
                'cart_count' => $cart_count,
                'cart_subtotal' => $cart_subtotal,
            ] );
        } else {
            wp_send_json_error( 'Failed to remove from cart' );
        }
    }

    /**
     * AJAX add to wishlist
     */
    public function ajax_add_to_wishlist() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        
        // Check product ID
        if ( ! isset( $_POST['product_id'] ) ) {
            wp_send_json_error( 'Invalid product ID' );
        }
        
        // Get product ID
        $product_id = absint( $_POST['product_id'] );
        
        // Get wishlist
        $wishlist = $this->get_wishlist();
        
        // Add to wishlist
        if ( ! in_array( $product_id, $wishlist, true ) ) {
            $wishlist[] = $product_id;
        }
        
        // Update wishlist
        $this->update_wishlist( $wishlist );
        
        // Send success
        wp_send_json_success( [
            'wishlist' => $wishlist,
            'count' => count( $wishlist ),
        ] );
    }

    /**
     * AJAX remove from wishlist
     */
    public function ajax_remove_from_wishlist() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        
        // Check product ID
        if ( ! isset( $_POST['product_id'] ) ) {
            wp_send_json_error( 'Invalid product ID' );
        }
        
        // Get product ID
        $product_id = absint( $_POST['product_id'] );
        
        // Get wishlist
        $wishlist = $this->get_wishlist();
        
        // Remove from wishlist
        $key = array_search( $product_id, $wishlist, true );
        
        if ( false !== $key ) {
            unset( $wishlist[ $key ] );
        }
        
        // Update wishlist
        $this->update_wishlist( $wishlist );
        
        // Send success
        wp_send_json_success( [
            'wishlist' => $wishlist,
            'count' => count( $wishlist ),
        ] );
    }

    /**
     * AJAX quick view
     */
    public function ajax_quick_view() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        
        // Check product ID
        if ( ! isset( $_POST['product_id'] ) ) {
            wp_send_json_error( 'Invalid product ID' );
        }
        
        // Get product ID
        $product_id = absint( $_POST['product_id'] );
        
        // Get product
        $product = wc_get_product( $product_id );
        
        if ( ! $product ) {
            wp_send_json_error( 'Invalid product' );
        }
        
        // Get quick view template
        ob_start();
        Template::get_template_part( 'templates/woocommerce/quick-view', null, [
            'product' => $product,
        ] );
        $html = ob_get_clean();
        
        // Send success
        wp_send_json_success( [
            'html' => $html,
        ] );
    }

    /**
     * Add WooCommerce menu
     */
    public function add_woocommerce_menu() {
        add_submenu_page(
            'aqualuxe',
            esc_html__( 'WooCommerce', 'aqualuxe' ),
            esc_html__( 'WooCommerce', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-woocommerce',
            [ $this, 'woocommerce_settings_page' ]
        );
    }

    /**
     * Register WooCommerce settings
     */
    public function register_woocommerce_settings() {
        register_setting(
            'aqualuxe_woocommerce_settings',
            'aqualuxe_woocommerce_settings',
            [
                'sanitize_callback' => [ $this, 'sanitize_woocommerce_settings' ],
            ]
        );
    }

    /**
     * Sanitize WooCommerce settings
     *
     * @param array $settings WooCommerce settings
     * @return array
     */
    public function sanitize_woocommerce_settings( $settings ) {
        // Sanitize WooCommerce settings
        return $settings;
    }

    /**
     * WooCommerce settings page
     */
    public function woocommerce_settings_page() {
        // WooCommerce settings page content
        include AQUALUXE_TEMPLATES_DIR . 'admin/woocommerce-settings.php';
    }

    /**
     * Quick view button
     */
    public function quick_view_button() {
        // Get WooCommerce options
        $woocommerce_options = get_option( 'aqualuxe_woocommerce_options', [] );
        
        // Check if quick view is enabled
        if ( ! isset( $woocommerce_options['enable_quick_view'] ) || ! $woocommerce_options['enable_quick_view'] ) {
            return;
        }
        
        // Get product ID
        $product_id = get_the_ID();
        
        // Quick view button
        echo '<a href="#" class="button quick-view-button" data-product-id="' . esc_attr( $product_id ) . '">' . esc_html__( 'Quick View', 'aqualuxe' ) . '</a>';
    }

    /**
     * Wishlist button
     */
    public function wishlist_button() {
        // Get WooCommerce options
        $woocommerce_options = get_option( 'aqualuxe_woocommerce_options', [] );
        
        // Check if wishlist is enabled
        if ( ! isset( $woocommerce_options['enable_wishlist'] ) || ! $woocommerce_options['enable_wishlist'] ) {
            return;
        }
        
        // Get product ID
        $product_id = get_the_ID();
        
        // Get wishlist
        $wishlist = $this->get_wishlist();
        
        // Check if product is in wishlist
        $in_wishlist = in_array( $product_id, $wishlist, true );
        
        // Wishlist button
        if ( $in_wishlist ) {
            echo '<a href="#" class="button wishlist-button remove-from-wishlist" data-product-id="' . esc_attr( $product_id ) . '">' . esc_html__( 'Remove from Wishlist', 'aqualuxe' ) . '</a>';
        } else {
            echo '<a href="#" class="button wishlist-button add-to-wishlist" data-product-id="' . esc_attr( $product_id ) . '">' . esc_html__( 'Add to Wishlist', 'aqualuxe' ) . '</a>';
        }
    }

    /**
     * Get wishlist
     *
     * @return array
     */
    public function get_wishlist() {
        // Get user ID
        $user_id = get_current_user_id();
        
        // Get wishlist
        if ( $user_id ) {
            // Get user wishlist
            $wishlist = get_user_meta( $user_id, 'aqualuxe_wishlist', true );
        } else {
            // Get session wishlist
            $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : [];
        }
        
        // Ensure wishlist is an array
        if ( ! is_array( $wishlist ) ) {
            $wishlist = [];
        }
        
        return $wishlist;
    }

    /**
     * Update wishlist
     *
     * @param array $wishlist Wishlist
     */
    public function update_wishlist( $wishlist ) {
        // Get user ID
        $user_id = get_current_user_id();
        
        // Update wishlist
        if ( $user_id ) {
            // Update user wishlist
            update_user_meta( $user_id, 'aqualuxe_wishlist', $wishlist );
        } else {
            // Update session wishlist
            setcookie( 'aqualuxe_wishlist', wp_json_encode( $wishlist ), time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
        }
    }
}