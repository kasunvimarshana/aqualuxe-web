<?php
/**
 * AquaLuxe WooCommerce Compatibility
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * AquaLuxe WooCommerce Class
 */
class AquaLuxe_WooCommerce {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_WooCommerce
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return AquaLuxe_WooCommerce
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Check if WooCommerce is active
        if ( ! aqualuxe_is_woocommerce_active() ) {
            return;
        }
        
        // Setup WooCommerce
        $this->setup();
        
        // Add theme supports
        add_action( 'after_setup_theme', [ $this, 'add_theme_supports' ] );
        
        // Enqueue scripts and styles
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        
        // Remove default WooCommerce styles
        add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
        
        // Modify WooCommerce templates
        add_filter( 'woocommerce_locate_template', [ $this, 'locate_template' ], 10, 3 );
        
        // Modify WooCommerce layout
        add_filter( 'body_class', [ $this, 'body_classes' ] );
        add_filter( 'woocommerce_product_loop_start', [ $this, 'product_loop_start' ], 10, 1 );
        add_filter( 'woocommerce_product_loop_end', [ $this, 'product_loop_end' ], 10, 1 );
        
        // Modify WooCommerce content
        add_filter( 'woocommerce_add_to_cart_fragments', [ $this, 'cart_fragments' ] );
        add_filter( 'woocommerce_output_related_products_args', [ $this, 'related_products_args' ] );
        add_filter( 'woocommerce_cross_sells_columns', [ $this, 'cross_sells_columns' ] );
        add_filter( 'woocommerce_cross_sells_total', [ $this, 'cross_sells_total' ] );
        
        // Modify WooCommerce shop
        add_filter( 'loop_shop_per_page', [ $this, 'products_per_page' ] );
        add_filter( 'loop_shop_columns', [ $this, 'loop_columns' ] );
        
        // Modify WooCommerce checkout
        add_filter( 'woocommerce_checkout_fields', [ $this, 'checkout_fields' ] );
        
        // Add custom hooks
        add_action( 'woocommerce_before_shop_loop', [ $this, 'before_shop_loop' ], 10 );
        add_action( 'woocommerce_after_shop_loop', [ $this, 'after_shop_loop' ], 10 );
        add_action( 'woocommerce_before_single_product', [ $this, 'before_single_product' ], 10 );
        add_action( 'woocommerce_after_single_product', [ $this, 'after_single_product' ], 10 );
        add_action( 'woocommerce_before_cart', [ $this, 'before_cart' ], 10 );
        add_action( 'woocommerce_after_cart', [ $this, 'after_cart' ], 10 );
        add_action( 'woocommerce_before_checkout_form', [ $this, 'before_checkout_form' ], 10 );
        add_action( 'woocommerce_after_checkout_form', [ $this, 'after_checkout_form' ], 10 );
        add_action( 'woocommerce_account_navigation', [ $this, 'account_navigation' ], 10 );
        
        // Add quick view
        if ( aqualuxe_get_option( 'enable_product_quick_view', true ) ) {
            add_action( 'woocommerce_after_shop_loop_item', [ $this, 'quick_view_button' ], 15 );
            add_action( 'wp_footer', [ $this, 'quick_view_modal' ] );
            add_action( 'wp_ajax_aqualuxe_quick_view', [ $this, 'quick_view_ajax' ] );
            add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', [ $this, 'quick_view_ajax' ] );
        }
        
        // Add wishlist
        if ( aqualuxe_get_option( 'enable_product_wishlist', true ) ) {
            add_action( 'woocommerce_after_shop_loop_item', [ $this, 'wishlist_button' ], 20 );
            add_action( 'woocommerce_single_product_summary', [ $this, 'wishlist_button' ], 35 );
            add_action( 'wp_ajax_aqualuxe_wishlist', [ $this, 'wishlist_ajax' ] );
            add_action( 'wp_ajax_nopriv_aqualuxe_wishlist', [ $this, 'wishlist_ajax' ] );
        }
        
        // Add sticky add to cart
        if ( aqualuxe_get_option( 'enable_product_sticky_add_to_cart', true ) ) {
            add_action( 'woocommerce_after_single_product', [ $this, 'sticky_add_to_cart' ] );
        }
        
        // Modify product tabs
        if ( ! aqualuxe_get_option( 'enable_product_tabs', true ) ) {
            add_filter( 'woocommerce_product_tabs', '__return_empty_array' );
        }
        
        // Modify related products
        if ( ! aqualuxe_get_option( 'enable_product_related', true ) ) {
            remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
        }
        
        // Modify cross-sells
        if ( ! aqualuxe_get_option( 'enable_cart_cross_sells', true ) ) {
            remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
        }
        
        // Modify checkout
        if ( ! aqualuxe_get_option( 'enable_checkout_coupon', true ) ) {
            remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
        }
        
        if ( ! aqualuxe_get_option( 'enable_checkout_login', true ) ) {
            remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
        }
    }

    /**
     * Setup WooCommerce
     */
    public function setup() {
        // Register WooCommerce sidebars
        add_action( 'widgets_init', [ $this, 'register_sidebars' ] );
        
        // Register WooCommerce menus
        add_action( 'init', [ $this, 'register_menus' ] );
        
        // Register WooCommerce image sizes
        add_action( 'init', [ $this, 'register_image_sizes' ] );
    }

    /**
     * Add theme supports
     */
    public function add_theme_supports() {
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }

    /**
     * Register WooCommerce sidebars
     */
    public function register_sidebars() {
        register_sidebar(
            [
                'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
                'id'            => 'shop-sidebar',
                'description'   => esc_html__( 'Add widgets here to appear in your shop sidebar.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ]
        );
        
        register_sidebar(
            [
                'name'          => esc_html__( 'Product Sidebar', 'aqualuxe' ),
                'id'            => 'product-sidebar',
                'description'   => esc_html__( 'Add widgets here to appear in your product sidebar.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ]
        );
    }

    /**
     * Register WooCommerce menus
     */
    public function register_menus() {
        register_nav_menus(
            [
                'shop-categories' => esc_html__( 'Shop Categories Menu', 'aqualuxe' ),
            ]
        );
    }

    /**
     * Register WooCommerce image sizes
     */
    public function register_image_sizes() {
        add_image_size( 'aqualuxe-product-thumbnail', 300, 300, true );
        add_image_size( 'aqualuxe-product-gallery', 600, 600, true );
    }

    /**
     * Enqueue WooCommerce assets
     */
    public function enqueue_assets() {
        // Get the asset manager instance
        $assets = AquaLuxe_Assets::instance();
        
        // Enqueue WooCommerce stylesheet
        $assets->enqueue_style( 'aqualuxe-woocommerce', 'css/woocommerce.css' );
        
        // Enqueue WooCommerce script
        $assets->enqueue_script( 'aqualuxe-woocommerce', 'js/woocommerce.js', [ 'jquery' ], true );
        
        // Localize script
        wp_localize_script(
            'aqualuxe-woocommerce',
            'aqualuxeWooCommerce',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'aqualuxe-woocommerce' ),
                'quickView' => aqualuxe_get_option( 'enable_product_quick_view', true ),
                'wishlist' => aqualuxe_get_option( 'enable_product_wishlist', true ),
                'stickyAddToCart' => aqualuxe_get_option( 'enable_product_sticky_add_to_cart', true ),
                'i18n' => [
                    'quickView' => esc_html__( 'Quick View', 'aqualuxe' ),
                    'addToWishlist' => esc_html__( 'Add to Wishlist', 'aqualuxe' ),
                    'removeFromWishlist' => esc_html__( 'Remove from Wishlist', 'aqualuxe' ),
                    'addToCart' => esc_html__( 'Add to Cart', 'aqualuxe' ),
                    'viewCart' => esc_html__( 'View Cart', 'aqualuxe' ),
                    'checkout' => esc_html__( 'Checkout', 'aqualuxe' ),
                ],
            ]
        );
    }

    /**
     * Locate WooCommerce template
     *
     * @param string $template Template path
     * @param string $template_name Template name
     * @param string $template_path Template path
     * @return string Template path
     */
    public function locate_template( $template, $template_name, $template_path ) {
        // Get theme template path
        $theme_template = AQUALUXE_DIR . 'woocommerce/' . $template_name;
        
        // Return theme template if exists
        if ( file_exists( $theme_template ) ) {
            return $theme_template;
        }
        
        // Return original template
        return $template;
    }

    /**
     * Add WooCommerce body classes
     *
     * @param array $classes Body classes
     * @return array Modified body classes
     */
    public function body_classes( $classes ) {
        // Add shop layout class
        if ( is_shop() || is_product_category() || is_product_tag() ) {
            $classes[] = 'shop-layout-' . aqualuxe_get_option( 'shop_layout', 'grid' );
        }
        
        // Add product gallery layout class
        if ( is_product() ) {
            $classes[] = 'product-gallery-' . aqualuxe_get_option( 'product_gallery_layout', 'horizontal' );
        }
        
        // Add shop sidebar class
        if ( is_shop() || is_product_category() || is_product_tag() ) {
            if ( aqualuxe_get_option( 'enable_shop_sidebar', true ) ) {
                $classes[] = 'has-shop-sidebar';
            } else {
                $classes[] = 'no-shop-sidebar';
            }
        }
        
        // Add product sidebar class
        if ( is_product() ) {
            if ( is_active_sidebar( 'product-sidebar' ) ) {
                $classes[] = 'has-product-sidebar';
            } else {
                $classes[] = 'no-product-sidebar';
            }
        }
        
        // Add account sidebar class
        if ( is_account_page() ) {
            if ( aqualuxe_get_option( 'enable_account_sidebar', true ) ) {
                $classes[] = 'has-account-sidebar';
            } else {
                $classes[] = 'no-account-sidebar';
            }
        }
        
        return $classes;
    }

    /**
     * Modify product loop start
     *
     * @param string $html Loop start HTML
     * @return string Modified loop start HTML
     */
    public function product_loop_start( $html ) {
        // Get shop columns
        $columns = $this->loop_columns();
        
        // Get shop layout
        $layout = aqualuxe_get_option( 'shop_layout', 'grid' );
        
        // Create new HTML
        $html = '<ul class="products columns-' . esc_attr( $columns ) . ' layout-' . esc_attr( $layout ) . '">';
        
        return $html;
    }

    /**
     * Modify product loop end
     *
     * @param string $html Loop end HTML
     * @return string Modified loop end HTML
     */
    public function product_loop_end( $html ) {
        return '</ul>';
    }

    /**
     * Add cart fragments
     *
     * @param array $fragments Cart fragments
     * @return array Modified cart fragments
     */
    public function cart_fragments( $fragments ) {
        // Get cart count
        $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
        
        // Add header cart fragment
        ob_start();
        aqualuxe_header_cart();
        $fragments['.header-cart'] = ob_get_clean();
        
        // Add cart count fragment
        $fragments['.header-cart-count'] = '<span class="header-cart-count">' . esc_html( $cart_count ) . '</span>';
        
        return $fragments;
    }

    /**
     * Modify related products arguments
     *
     * @param array $args Related products arguments
     * @return array Modified related products arguments
     */
    public function related_products_args( $args ) {
        $args['posts_per_page'] = 4;
        $args['columns'] = 4;
        
        return $args;
    }

    /**
     * Modify cross-sells columns
     *
     * @param int $columns Cross-sells columns
     * @return int Modified cross-sells columns
     */
    public function cross_sells_columns( $columns ) {
        return 2;
    }

    /**
     * Modify cross-sells total
     *
     * @param int $total Cross-sells total
     * @return int Modified cross-sells total
     */
    public function cross_sells_total( $total ) {
        return 2;
    }

    /**
     * Modify products per page
     *
     * @param int $products Products per page
     * @return int Modified products per page
     */
    public function products_per_page( $products ) {
        return aqualuxe_get_option( 'shop_products_per_page', 12 );
    }

    /**
     * Modify loop columns
     *
     * @param int $columns Loop columns
     * @return int Modified loop columns
     */
    public function loop_columns( $columns ) {
        return aqualuxe_get_option( 'shop_columns', 4 );
    }

    /**
     * Modify checkout fields
     *
     * @param array $fields Checkout fields
     * @return array Modified checkout fields
     */
    public function checkout_fields( $fields ) {
        // Remove order notes if disabled
        if ( ! aqualuxe_get_option( 'enable_checkout_order_notes', true ) ) {
            unset( $fields['order']['order_comments'] );
        }
        
        return $fields;
    }

    /**
     * Before shop loop
     */
    public function before_shop_loop() {
        do_action( 'aqualuxe_before_shop' );
        do_action( 'aqualuxe_shop_top' );
        
        // Add shop filters
        if ( aqualuxe_get_option( 'enable_shop_filters', true ) ) {
            $this->shop_filters();
        }
    }

    /**
     * After shop loop
     */
    public function after_shop_loop() {
        do_action( 'aqualuxe_shop_bottom' );
        do_action( 'aqualuxe_after_shop' );
    }

    /**
     * Before single product
     */
    public function before_single_product() {
        do_action( 'aqualuxe_before_product' );
        do_action( 'aqualuxe_product_top' );
    }

    /**
     * After single product
     */
    public function after_single_product() {
        do_action( 'aqualuxe_product_bottom' );
        do_action( 'aqualuxe_after_product' );
    }

    /**
     * Before cart
     */
    public function before_cart() {
        do_action( 'aqualuxe_before_cart' );
        do_action( 'aqualuxe_cart_top' );
    }

    /**
     * After cart
     */
    public function after_cart() {
        do_action( 'aqualuxe_cart_bottom' );
        do_action( 'aqualuxe_after_cart' );
    }

    /**
     * Before checkout form
     */
    public function before_checkout_form() {
        do_action( 'aqualuxe_before_checkout' );
        do_action( 'aqualuxe_checkout_top' );
    }

    /**
     * After checkout form
     */
    public function after_checkout_form() {
        do_action( 'aqualuxe_checkout_bottom' );
        do_action( 'aqualuxe_after_checkout' );
    }

    /**
     * Account navigation
     */
    public function account_navigation() {
        do_action( 'aqualuxe_before_account' );
        do_action( 'aqualuxe_account_top' );
    }

    /**
     * Shop filters
     */
    public function shop_filters() {
        // Check if we have active filters
        $has_filters = is_active_sidebar( 'shop-sidebar' );
        
        if ( ! $has_filters ) {
            return;
        }
        
        // Get current filters
        $current_filters = [];
        
        if ( isset( $_GET['filter_price'] ) ) {
            $current_filters['price'] = sanitize_text_field( $_GET['filter_price'] );
        }
        
        if ( isset( $_GET['filter_rating'] ) ) {
            $current_filters['rating'] = absint( $_GET['filter_rating'] );
        }
        
        // Output filters
        ?>
        <div class="shop-filters">
            <button class="shop-filters-toggle" aria-expanded="false">
                <?php echo esc_html__( 'Filter Products', 'aqualuxe' ); ?>
                <?php echo aqualuxe_get_icon( 'filter' ); ?>
            </button>
            
            <div class="shop-filters-content">
                <?php dynamic_sidebar( 'shop-sidebar' ); ?>
            </div>
        </div>
        <?php
    }

    /**
     * Quick view button
     */
    public function quick_view_button() {
        global $product;
        
        if ( ! $product ) {
            return;
        }
        
        echo '<button class="quick-view-button" data-product-id="' . esc_attr( $product->get_id() ) . '">';
        echo aqualuxe_get_icon( 'eye' );
        echo '<span>' . esc_html__( 'Quick View', 'aqualuxe' ) . '</span>';
        echo '</button>';
    }

    /**
     * Quick view modal
     */
    public function quick_view_modal() {
        ?>
        <div id="quick-view-modal" class="quick-view-modal" aria-hidden="true">
            <div class="quick-view-modal-inner">
                <button class="quick-view-modal-close" aria-label="<?php echo esc_attr__( 'Close quick view', 'aqualuxe' ); ?>">
                    <?php echo aqualuxe_get_icon( 'close' ); ?>
                </button>
                <div class="quick-view-modal-content"></div>
            </div>
        </div>
        <?php
    }

    /**
     * Quick view AJAX
     */
    public function quick_view_ajax() {
        // Check nonce
        if ( ! check_ajax_referer( 'aqualuxe-woocommerce', 'nonce', false ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ] );
        }
        
        // Get product ID
        $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
        
        if ( ! $product_id ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid product ID.', 'aqualuxe' ) ] );
        }
        
        // Get product
        $product = wc_get_product( $product_id );
        
        if ( ! $product ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Product not found.', 'aqualuxe' ) ] );
        }
        
        // Output product
        ob_start();
        ?>
        <div class="quick-view-product">
            <div class="quick-view-product-image">
                <?php echo $product->get_image( 'large' ); ?>
            </div>
            <div class="quick-view-product-summary">
                <h2 class="quick-view-product-title"><?php echo esc_html( $product->get_name() ); ?></h2>
                <div class="quick-view-product-price"><?php echo $product->get_price_html(); ?></div>
                <div class="quick-view-product-rating">
                    <?php if ( $product->get_average_rating() ) : ?>
                        <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                        <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>#reviews" class="quick-view-product-review-link">
                            <?php echo sprintf( _n( '%s review', '%s reviews', $product->get_review_count(), 'aqualuxe' ), esc_html( $product->get_review_count() ) ); ?>
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
                            <?php echo esc_html__( 'SKU:', 'aqualuxe' ); ?> <?php echo esc_html( $product->get_sku() ); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ( $product->get_category_ids() ) : ?>
                        <span class="quick-view-product-categories">
                            <?php echo esc_html__( 'Categories:', 'aqualuxe' ); ?> <?php echo wc_get_product_category_list( $product_id ); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ( $product->get_tag_ids() ) : ?>
                        <span class="quick-view-product-tags">
                            <?php echo esc_html__( 'Tags:', 'aqualuxe' ); ?> <?php echo wc_get_product_tag_list( $product_id ); ?>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="quick-view-product-actions">
                    <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="button quick-view-product-details">
                        <?php echo esc_html__( 'View Details', 'aqualuxe' ); ?>
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
     */
    public function wishlist_button() {
        global $product;
        
        if ( ! $product ) {
            return;
        }
        
        // Get wishlist
        $wishlist = $this->get_wishlist();
        
        // Check if product is in wishlist
        $in_wishlist = in_array( $product->get_id(), $wishlist );
        
        // Output button
        echo '<button class="wishlist-button' . ( $in_wishlist ? ' in-wishlist' : '' ) . '" data-product-id="' . esc_attr( $product->get_id() ) . '">';
        echo aqualuxe_get_icon( 'heart' );
        echo '<span>' . ( $in_wishlist ? esc_html__( 'Remove from Wishlist', 'aqualuxe' ) : esc_html__( 'Add to Wishlist', 'aqualuxe' ) ) . '</span>';
        echo '</button>';
    }

    /**
     * Wishlist AJAX
     */
    public function wishlist_ajax() {
        // Check nonce
        if ( ! check_ajax_referer( 'aqualuxe-woocommerce', 'nonce', false ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ] );
        }
        
        // Get product ID
        $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
        
        if ( ! $product_id ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid product ID.', 'aqualuxe' ) ] );
        }
        
        // Get wishlist
        $wishlist = $this->get_wishlist();
        
        // Check if product is in wishlist
        $in_wishlist = in_array( $product_id, $wishlist );
        
        if ( $in_wishlist ) {
            // Remove from wishlist
            $wishlist = array_diff( $wishlist, [ $product_id ] );
            $message = esc_html__( 'Product removed from wishlist.', 'aqualuxe' );
            $button_text = esc_html__( 'Add to Wishlist', 'aqualuxe' );
        } else {
            // Add to wishlist
            $wishlist[] = $product_id;
            $message = esc_html__( 'Product added to wishlist.', 'aqualuxe' );
            $button_text = esc_html__( 'Remove from Wishlist', 'aqualuxe' );
        }
        
        // Save wishlist
        $this->save_wishlist( $wishlist );
        
        wp_send_json_success( [
            'message' => $message,
            'in_wishlist' => ! $in_wishlist,
            'button_text' => $button_text,
            'wishlist_count' => count( $wishlist ),
        ] );
    }

    /**
     * Get wishlist
     *
     * @return array Wishlist
     */
    public function get_wishlist() {
        // Get wishlist from cookie
        $wishlist = [];
        
        if ( isset( $_COOKIE['aqualuxe_wishlist'] ) ) {
            $wishlist = explode( ',', sanitize_text_field( $_COOKIE['aqualuxe_wishlist'] ) );
            $wishlist = array_map( 'absint', $wishlist );
            $wishlist = array_filter( $wishlist );
        }
        
        // Get wishlist from user meta if logged in
        if ( is_user_logged_in() ) {
            $user_wishlist = get_user_meta( get_current_user_id(), 'aqualuxe_wishlist', true );
            
            if ( $user_wishlist ) {
                $user_wishlist = array_map( 'absint', $user_wishlist );
                $user_wishlist = array_filter( $user_wishlist );
                
                // Merge with cookie wishlist
                $wishlist = array_unique( array_merge( $wishlist, $user_wishlist ) );
                
                // Save merged wishlist to user meta
                update_user_meta( get_current_user_id(), 'aqualuxe_wishlist', $wishlist );
                
                // Save merged wishlist to cookie
                $this->save_wishlist( $wishlist );
            } elseif ( ! empty( $wishlist ) ) {
                // Save cookie wishlist to user meta
                update_user_meta( get_current_user_id(), 'aqualuxe_wishlist', $wishlist );
            }
        }
        
        return $wishlist;
    }

    /**
     * Save wishlist
     *
     * @param array $wishlist Wishlist
     */
    public function save_wishlist( $wishlist ) {
        // Save wishlist to cookie
        $wishlist = array_map( 'absint', $wishlist );
        $wishlist = array_filter( $wishlist );
        $wishlist = array_unique( $wishlist );
        
        setcookie( 'aqualuxe_wishlist', implode( ',', $wishlist ), time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
        
        // Save wishlist to user meta if logged in
        if ( is_user_logged_in() ) {
            update_user_meta( get_current_user_id(), 'aqualuxe_wishlist', $wishlist );
        }
    }

    /**
     * Sticky add to cart
     */
    public function sticky_add_to_cart() {
        global $product;
        
        if ( ! $product || ! is_product() ) {
            return;
        }
        
        ?>
        <div class="sticky-add-to-cart">
            <div class="container">
                <div class="sticky-add-to-cart-inner">
                    <div class="sticky-add-to-cart-image">
                        <?php echo $product->get_image( 'thumbnail' ); ?>
                    </div>
                    <div class="sticky-add-to-cart-content">
                        <h3 class="sticky-add-to-cart-title"><?php echo esc_html( $product->get_name() ); ?></h3>
                        <div class="sticky-add-to-cart-price"><?php echo $product->get_price_html(); ?></div>
                    </div>
                    <div class="sticky-add-to-cart-actions">
                        <?php
                        if ( $product->is_type( 'simple' ) ) {
                            echo '<form class="cart" action="' . esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ) . '" method="post" enctype="multipart/form-data">';
                            echo '<input type="hidden" name="add-to-cart" value="' . esc_attr( $product->get_id() ) . '" />';
                            
                            if ( $product->is_purchasable() && $product->is_in_stock() ) {
                                echo '<div class="quantity">';
                                echo '<input type="number" name="quantity" value="1" min="1" max="' . esc_attr( $product->get_max_purchase_quantity() ) . '" />';
                                echo '</div>';
                                echo '<button type="submit" class="button">' . esc_html__( 'Add to Cart', 'aqualuxe' ) . '</button>';
                            } else {
                                echo '<button type="button" class="button disabled">' . esc_html__( 'Read More', 'aqualuxe' ) . '</button>';
                            }
                            
                            echo '</form>';
                        } else {
                            echo '<a href="#product-' . esc_attr( $product->get_id() ) . '" class="button">' . esc_html__( 'Select Options', 'aqualuxe' ) . '</a>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

// Initialize WooCommerce compatibility
AquaLuxe_WooCommerce::instance();