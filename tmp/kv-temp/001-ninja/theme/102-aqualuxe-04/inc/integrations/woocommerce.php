<?php
/**
 * WooCommerce Integration
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce Integration Class
 */
class AquaLuxe_WooCommerce {
    
    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;
    
    /**
     * Initialize the integration.
     */
    public function __construct() {
        add_action( 'after_setup_theme', array( $this, 'setup' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_filter( 'woocommerce_enqueue_styles', array( $this, 'dequeue_styles' ) );
        add_action( 'wp_footer', array( $this, 'cart_icon' ) );
        
        // Hooks
        $this->add_hooks();
    }
    
    /**
     * Return an instance of this class.
     *
     * @return object A single instance of this class.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * WooCommerce setup.
     */
    public function setup() {
        // Add WooCommerce support
        add_theme_support( 'woocommerce', array(
            'thumbnail_image_width' => 300,
            'single_image_width'    => 600,
            'product_grid'          => array(
                'default_rows'    => 4,
                'min_rows'        => 2,
                'max_rows'        => 8,
                'default_columns' => 4,
                'min_columns'     => 2,
                'max_columns'     => 6,
            ),
        ) );
        
        // Add support for WooCommerce gallery features
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }
    
    /**
     * Enqueue WooCommerce scripts and styles.
     */
    public function enqueue_scripts() {
        // Get mix manifest for cache busting
        $mix_manifest = $this->get_mix_manifest();
        
        wp_enqueue_script(
            'aqualuxe-woocommerce',
            $this->get_asset_url( 'js/modules/woocommerce.js', $mix_manifest ),
            array( 'jquery', 'wc-add-to-cart-variation' ),
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script( 'aqualuxe-woocommerce', 'aqualuxe_woocommerce', array(
            'ajax_url'           => admin_url( 'admin-ajax.php' ),
            'nonce'              => wp_create_nonce( 'aqualuxe_woocommerce_nonce' ),
            'cart_url'           => wc_get_cart_url(),
            'checkout_url'       => wc_get_checkout_url(),
            'currency_symbol'    => get_woocommerce_currency_symbol(),
            'thousand_separator' => wc_get_price_thousand_separator(),
            'decimal_separator'  => wc_get_price_decimal_separator(),
            'decimals'           => wc_get_price_decimals(),
            'strings'            => array(
                'loading'            => esc_html__( 'Loading...', 'aqualuxe' ),
                'added_to_cart'      => esc_html__( 'Added to cart!', 'aqualuxe' ),
                'add_to_cart_error'  => esc_html__( 'Error adding to cart. Please try again.', 'aqualuxe' ),
                'quick_view'         => esc_html__( 'Quick View', 'aqualuxe' ),
                'close'              => esc_html__( 'Close', 'aqualuxe' ),
                'select_options'     => esc_html__( 'Select options', 'aqualuxe' ),
                'view_cart'          => esc_html__( 'View Cart', 'aqualuxe' ),
                'compare'            => esc_html__( 'Compare', 'aqualuxe' ),
                'wishlist'           => esc_html__( 'Wishlist', 'aqualuxe' ),
            ),
        ) );
    }
    
    /**
     * Dequeue WooCommerce default styles.
     *
     * @param array $styles WooCommerce styles.
     * @return array
     */
    public function dequeue_styles( $styles ) {
        // Remove default WooCommerce styles to use our custom ones
        unset( $styles['woocommerce-general'] );
        unset( $styles['woocommerce-layout'] );
        unset( $styles['woocommerce-smallscreen'] );
        
        return $styles;
    }
    
    /**
     * Add WooCommerce hooks.
     */
    private function add_hooks() {
        // Remove default WooCommerce wrappers
        remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
        remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
        
        // Add custom wrappers
        add_action( 'woocommerce_before_main_content', array( $this, 'wrapper_start' ), 10 );
        add_action( 'woocommerce_after_main_content', array( $this, 'wrapper_end' ), 10 );
        
        // Customize product loop
        remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
        remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
        
        add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_loop_start' ), 5 );
        add_action( 'woocommerce_shop_loop_item_title', array( $this, 'product_loop_title' ), 10 );
        add_action( 'woocommerce_after_shop_loop_item', array( $this, 'product_loop_end' ), 25 );
        
        // Add quick view
        add_action( 'woocommerce_after_shop_loop_item', array( $this, 'quick_view_button' ), 15 );
        
        // Customize single product
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
        
        add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_title' ), 5 );
        add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_rating' ), 10 );
        add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_price' ), 15 );
        
        // Customize cart
        add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_link_fragment' ) );
        
        // AJAX handlers
        add_action( 'wp_ajax_aqualuxe_quick_view', array( $this, 'ajax_quick_view' ) );
        add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', array( $this, 'ajax_quick_view' ) );
    }
    
    /**
     * Content wrapper start.
     */
    public function wrapper_start() {
        echo '<div id="primary" class="site-main">';
        echo '<div class="container mx-auto px-4">';
    }
    
    /**
     * Content wrapper end.
     */
    public function wrapper_end() {
        echo '</div>';
        echo '</div>';
    }
    
    /**
     * Product loop start.
     */
    public function product_loop_start() {
        echo '<div class="product-loop-wrapper">';
        echo '<a href="' . esc_url( get_permalink() ) . '" class="product-link">';
    }
    
    /**
     * Product loop title.
     */
    public function product_loop_title() {
        echo '<h3 class="product-title text-lg font-semibold text-gray-900 dark:text-white hover:text-aqua-600 dark:hover:text-aqua-400 transition-colors">' . get_the_title() . '</h3>';
    }
    
    /**
     * Product loop end.
     */
    public function product_loop_end() {
        echo '</a>';
        echo '</div>';
    }
    
    /**
     * Quick view button.
     */
    public function quick_view_button() {
        global $product;
        
        echo '<button class="quick-view-btn btn btn-outline btn-sm mt-2" data-product-id="' . esc_attr( $product->get_id() ) . '">';
        echo esc_html__( 'Quick View', 'aqualuxe' );
        echo '</button>';
    }
    
    /**
     * Single product title.
     */
    public function single_product_title() {
        echo '<h1 class="product-title text-3xl font-bold text-gray-900 dark:text-white mb-4">' . get_the_title() . '</h1>';
    }
    
    /**
     * Single product rating.
     */
    public function single_product_rating() {
        global $product;
        
        if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
            return;
        }
        
        $rating_count = $product->get_rating_count();
        $review_count = $product->get_review_count();
        $average      = $product->get_average_rating();
        
        if ( $rating_count > 0 ) {
            echo '<div class="woocommerce-product-rating mb-4">';
            echo wc_get_rating_html( $average, $rating_count );
            if ( comments_open() ) {
                echo '<a href="#reviews" class="woocommerce-review-link ml-2 text-sm text-gray-600 hover:text-aqua-600" rel="nofollow">(' . sprintf( _n( '%s customer review', '%s customer reviews', $review_count, 'aqualuxe' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ) . ')</a>';
            }
            echo '</div>';
        }
    }
    
    /**
     * Single product price.
     */
    public function single_product_price() {
        global $product;
        
        echo '<p class="price text-2xl font-bold text-aqua-600 mb-6">' . $product->get_price_html() . '</p>';
    }
    
    /**
     * Cart icon for header.
     */
    public function cart_icon() {
        if ( ! is_admin() && ! is_cart() ) {
            $cart_count = WC()->cart->get_cart_contents_count();
            ?>
            <div id="cart-icon-wrapper" class="cart-icon-wrapper fixed top-20 right-6 z-40">
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-icon bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl p-3 rounded-full transition-all duration-300 border border-gray-200 dark:border-gray-700 relative">
                    <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m2.6 8L6 5H3m4 8v6a2 2 0 002 2h8a2 2 0 002-2v-6m-10 4h10"></path>
                    </svg>
                    <span class="cart-count <?php echo $cart_count > 0 ? '' : 'hidden'; ?> absolute -top-2 -right-2 bg-aqua-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"><?php echo esc_html( $cart_count ); ?></span>
                </a>
            </div>
            <?php
        }
    }
    
    /**
     * AJAX quick view handler.
     */
    public function ajax_quick_view() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_woocommerce_nonce' ) ) {
            wp_die( esc_html__( 'Security check failed.', 'aqualuxe' ) );
        }
        
        $product_id = intval( $_POST['product_id'] );
        $product = wc_get_product( $product_id );
        
        if ( ! $product ) {
            wp_send_json_error( array(
                'message' => esc_html__( 'Product not found.', 'aqualuxe' ),
            ) );
        }
        
        // Set global product
        global $woocommerce, $product;
        
        ob_start();
        wc_get_template_part( 'content', 'quick-view' );
        $html = ob_get_clean();
        
        wp_send_json_success( array(
            'html' => $html,
        ) );
    }
    
    /**
     * Cart link fragment for AJAX updates.
     *
     * @param array $fragments Fragments.
     * @return array
     */
    public function cart_link_fragment( $fragments ) {
        $cart_count = WC()->cart->get_cart_contents_count();
        
        $fragments['.cart-count'] = '<span class="cart-count ' . ( $cart_count > 0 ? '' : 'hidden' ) . ' absolute -top-2 -right-2 bg-aqua-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">' . esc_html( $cart_count ) . '</span>';
        
        return $fragments;
    }
    
    /**
     * Get mix manifest for asset versioning.
     *
     * @return array
     */
    private function get_mix_manifest() {
        $manifest_path = AQUALUXE_ASSETS_DIR . '/dist/mix-manifest.json';
        
        if ( file_exists( $manifest_path ) ) {
            return json_decode( file_get_contents( $manifest_path ), true );
        }
        
        return array();
    }
    
    /**
     * Get asset URL with proper versioning.
     *
     * @param string $asset
     * @param array  $manifest
     * @return string
     */
    private function get_asset_url( $asset, $manifest = array() ) {
        $asset_path = '/' . $asset;
        
        if ( isset( $manifest[ $asset_path ] ) ) {
            return AQUALUXE_ASSETS_URI . '/dist' . $manifest[ $asset_path ];
        }
        
        return AQUALUXE_ASSETS_URI . '/dist' . $asset_path;
    }
}

// Initialize WooCommerce integration
AquaLuxe_WooCommerce::get_instance();

/**
 * Helper function to get cart icon.
 */
function aqualuxe_cart_icon() {
    return AquaLuxe_WooCommerce::get_instance()->cart_icon();
}