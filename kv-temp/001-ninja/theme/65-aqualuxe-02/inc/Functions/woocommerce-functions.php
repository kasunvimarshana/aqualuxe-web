<?php
/**
 * WooCommerce specific functions
 *
 * @package AquaLuxe
 */

/**
 * Check if WooCommerce is active
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists( 'WooCommerce' );
}

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
 *
 * @return void
 */
function aqualuxe_woocommerce_setup() {
    add_theme_support(
        'woocommerce',
        array(
            'thumbnail_image_width' => 400,
            'single_image_width'    => 800,
            'product_grid'          => array(
                'default_rows'    => 3,
                'min_rows'        => 1,
                'default_columns' => 4,
                'min_columns'     => 1,
                'max_columns'     => 6,
            ),
        )
    );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_setup' );

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function aqualuxe_woocommerce_scripts() {
    wp_enqueue_style( 'aqualuxe-woocommerce-style', get_template_directory_uri() . '/assets/dist/css/woocommerce.css', array(), AQUALUXE_VERSION );

    $font_path   = WC()->plugin_url() . '/assets/fonts/';
    $inline_font = '@font-face {
            font-family: "star";
            src: url("' . $font_path . 'star.eot");
            src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
                url("' . $font_path . 'star.woff") format("woff"),
                url("' . $font_path . 'star.ttf") format("truetype"),
                url("' . $font_path . 'star.svg#star") format("svg");
            font-weight: normal;
            font-style: normal;
        }';

    wp_add_inline_style( 'aqualuxe-woocommerce-style', $inline_font );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts' );

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function aqualuxe_woocommerce_active_body_class( $classes ) {
    $classes[] = 'woocommerce-active';

    return $classes;
}
add_filter( 'body_class', 'aqualuxe_woocommerce_active_body_class' );

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function aqualuxe_woocommerce_related_products_args( $args ) {
    $defaults = array(
        'posts_per_page' => 4,
        'columns'        => 4,
    );

    $args = wp_parse_args( $defaults, $args );

    return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args' );

/**
 * Remove default WooCommerce wrapper.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'aqualuxe_woocommerce_wrapper_before' ) ) {
    /**
     * Before Content.
     *
     * Wraps all WooCommerce content in wrappers which match the theme markup.
     *
     * @return void
     */
    function aqualuxe_woocommerce_wrapper_before() {
        ?>
        <div class="container mx-auto px-4 py-8">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full <?php echo is_active_sidebar( 'shop-sidebar' ) ? 'lg:w-3/4' : ''; ?> px-4">
                    <div id="primary" class="content-area">
                        <main id="main" class="site-main" role="main">
        <?php
    }
}
add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before' );

if ( ! function_exists( 'aqualuxe_woocommerce_wrapper_after' ) ) {
    /**
     * After Content.
     *
     * Closes the wrapping divs.
     *
     * @return void
     */
    function aqualuxe_woocommerce_wrapper_after() {
        ?>
                        </main><!-- #main -->
                    </div><!-- #primary -->
                </div><!-- .w-full -->

                <?php if ( is_active_sidebar( 'shop-sidebar' ) ) : ?>
                    <div class="w-full lg:w-1/4 px-4">
                        <?php get_sidebar( 'shop' ); ?>
                    </div><!-- .w-full -->
                <?php endif; ?>
            </div><!-- .flex -->
        </div><!-- .container -->
        <?php
    }
}
add_action( 'woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after' );

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
 * <?php
 * if ( function_exists( 'aqualuxe_woocommerce_header_cart' ) ) {
 *     aqualuxe_woocommerce_header_cart();
 * }
 * ?>
 */

if ( ! function_exists( 'aqualuxe_woocommerce_cart_link_fragment' ) ) {
    /**
     * Cart Fragments.
     *
     * Ensure cart contents update when products are added to the cart via AJAX.
     *
     * @param array $fragments Fragments to refresh via AJAX.
     * @return array Fragments to refresh via AJAX.
     */
    function aqualuxe_woocommerce_cart_link_fragment( $fragments ) {
        ob_start();
        aqualuxe_woocommerce_cart_link();
        $fragments['a.cart-contents'] = ob_get_clean();

        return $fragments;
    }
}
add_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_link_fragment' );

if ( ! function_exists( 'aqualuxe_woocommerce_cart_link' ) ) {
    /**
     * Cart Link.
     *
     * Displayed a link to the cart including the number of items present and the cart total.
     *
     * @return void
     */
    function aqualuxe_woocommerce_cart_link() {
        ?>
        <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
            <span class="cart-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M4 16V4H2V2h3a1 1 0 0 1 1 1v12h12.438l2-8H8V5h13.72a1 1 0 0 1 .97 1.243l-2.5 10a1 1 0 0 1-.97.757H5a1 1 0 0 1-1-1zm2 7a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm12 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg>
            </span>
            <span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
        </a>
        <?php
    }
}

if ( ! function_exists( 'aqualuxe_woocommerce_header_cart' ) ) {
    /**
     * Display Header Cart.
     *
     * @return void
     */
    function aqualuxe_woocommerce_header_cart() {
        if ( is_cart() ) {
            $class = 'current-menu-item';
        } else {
            $class = '';
        }
        ?>
        <div class="header-cart relative">
            <?php aqualuxe_woocommerce_cart_link(); ?>
            <div class="header-cart-dropdown hidden absolute right-0 top-full z-50 w-80 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-4">
                <div class="widget_shopping_cart_content">
                    <?php woocommerce_mini_cart(); ?>
                </div>
            </div>
        </div>
        <?php
    }
}

/**
 * Customize WooCommerce product columns
 *
 * @param int $columns Number of columns.
 * @return int
 */
function aqualuxe_woocommerce_loop_columns( $columns ) {
    return 4;
}
add_filter( 'loop_shop_columns', 'aqualuxe_woocommerce_loop_columns' );

/**
 * Customize WooCommerce products per page
 *
 * @param int $products_per_page Number of products per page.
 * @return int
 */
function aqualuxe_woocommerce_products_per_page( $products_per_page ) {
    return 12;
}
add_filter( 'loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page' );

/**
 * Customize WooCommerce breadcrumb
 *
 * @param array $defaults Breadcrumb defaults.
 * @return array
 */
function aqualuxe_woocommerce_breadcrumb_defaults( $defaults ) {
    $defaults['delimiter']   = '<span class="breadcrumb-separator mx-2">/</span>';
    $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb text-sm mb-6" itemprop="breadcrumb">';
    $defaults['wrap_after']  = '</nav>';
    $defaults['home']        = esc_html__( 'Home', 'aqualuxe' );
    return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumb_defaults' );

/**
 * Customize WooCommerce pagination
 *
 * @param array $args Pagination args.
 * @return array
 */
function aqualuxe_woocommerce_pagination_args( $args ) {
    $args['prev_text'] = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M10.828 12l4.95 4.95-1.414 1.414L8 12l6.364-6.364 1.414 1.414z"/></svg>' . esc_html__( 'Previous', 'aqualuxe' );
    $args['next_text'] = esc_html__( 'Next', 'aqualuxe' ) . '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/></svg>';
    return $args;
}
add_filter( 'woocommerce_pagination_args', 'aqualuxe_woocommerce_pagination_args' );

/**
 * Add wishlist functionality
 */
function aqualuxe_add_to_wishlist() {
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
    
    // Get user ID
    $user_id = get_current_user_id();
    
    if ( $user_id ) {
        // Get user wishlist
        $wishlist = get_user_meta( $user_id, 'aqualuxe_wishlist', true );
        
        if ( ! is_array( $wishlist ) ) {
            $wishlist = array();
        }
        
        // Add product to wishlist
        if ( ! in_array( $product_id, $wishlist, true ) ) {
            $wishlist[] = $product_id;
        }
        
        // Update user wishlist
        update_user_meta( $user_id, 'aqualuxe_wishlist', $wishlist );
    } else {
        // Get session wishlist
        $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : array();
        
        if ( ! is_array( $wishlist ) ) {
            $wishlist = array();
        }
        
        // Add product to wishlist
        if ( ! in_array( $product_id, $wishlist, true ) ) {
            $wishlist[] = $product_id;
        }
        
        // Update session wishlist
        setcookie( 'aqualuxe_wishlist', wp_json_encode( $wishlist ), time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
    }
    
    wp_send_json_success( array(
        'product_id' => $product_id,
        'wishlist'   => $wishlist,
    ) );
}
add_action( 'wp_ajax_aqualuxe_add_to_wishlist', 'aqualuxe_add_to_wishlist' );
add_action( 'wp_ajax_nopriv_aqualuxe_add_to_wishlist', 'aqualuxe_add_to_wishlist' );

/**
 * Remove from wishlist functionality
 */
function aqualuxe_remove_from_wishlist() {
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
    
    // Get user ID
    $user_id = get_current_user_id();
    
    if ( $user_id ) {
        // Get user wishlist
        $wishlist = get_user_meta( $user_id, 'aqualuxe_wishlist', true );
        
        if ( ! is_array( $wishlist ) ) {
            $wishlist = array();
        }
        
        // Remove product from wishlist
        $key = array_search( $product_id, $wishlist, true );
        
        if ( false !== $key ) {
            unset( $wishlist[ $key ] );
        }
        
        // Update user wishlist
        update_user_meta( $user_id, 'aqualuxe_wishlist', $wishlist );
    } else {
        // Get session wishlist
        $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : array();
        
        if ( ! is_array( $wishlist ) ) {
            $wishlist = array();
        }
        
        // Remove product from wishlist
        $key = array_search( $product_id, $wishlist, true );
        
        if ( false !== $key ) {
            unset( $wishlist[ $key ] );
        }
        
        // Update session wishlist
        setcookie( 'aqualuxe_wishlist', wp_json_encode( $wishlist ), time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
    }
    
    wp_send_json_success( array(
        'product_id' => $product_id,
        'wishlist'   => $wishlist,
    ) );
}
add_action( 'wp_ajax_aqualuxe_remove_from_wishlist', 'aqualuxe_remove_from_wishlist' );
add_action( 'wp_ajax_nopriv_aqualuxe_remove_from_wishlist', 'aqualuxe_remove_from_wishlist' );

/**
 * Get wishlist
 *
 * @return array
 */
function aqualuxe_get_wishlist() {
    // Get user ID
    $user_id = get_current_user_id();
    
    if ( $user_id ) {
        // Get user wishlist
        $wishlist = get_user_meta( $user_id, 'aqualuxe_wishlist', true );
    } else {
        // Get session wishlist
        $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : array();
    }
    
    if ( ! is_array( $wishlist ) ) {
        $wishlist = array();
    }
    
    return $wishlist;
}

/**
 * Check if product is in wishlist
 *
 * @param int $product_id Product ID.
 * @return bool
 */
function aqualuxe_is_product_in_wishlist( $product_id ) {
    $wishlist = aqualuxe_get_wishlist();
    
    return in_array( $product_id, $wishlist, true );
}

/**
 * Add wishlist button to product loop
 */
function aqualuxe_add_wishlist_button() {
    global $product;
    
    $product_id = $product->get_id();
    $in_wishlist = aqualuxe_is_product_in_wishlist( $product_id );
    
    if ( $in_wishlist ) {
        $button_class = 'remove-from-wishlist';
        $button_text  = esc_html__( 'Remove from Wishlist', 'aqualuxe' );
    } else {
        $button_class = 'add-to-wishlist';
        $button_text  = esc_html__( 'Add to Wishlist', 'aqualuxe' );
    }
    
    echo '<a href="#" class="button wishlist-button ' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '">' . esc_html( $button_text ) . '</a>';
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_add_wishlist_button', 15 );

/**
 * Add quick view button to product loop
 */
function aqualuxe_add_quick_view_button() {
    global $product;
    
    $product_id = $product->get_id();
    
    echo '<a href="#" class="button quick-view-button" data-product-id="' . esc_attr( $product_id ) . '">' . esc_html__( 'Quick View', 'aqualuxe' ) . '</a>';
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_add_quick_view_button', 20 );

/**
 * Quick view AJAX handler
 */
function aqualuxe_quick_view() {
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
    include get_template_directory() . '/templates/woocommerce/quick-view.php';
    $html = ob_get_clean();
    
    wp_send_json_success( array(
        'html' => $html,
    ) );
}
add_action( 'wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view' );
add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view' );

/**
 * Register WooCommerce sidebars
 */
function aqualuxe_woocommerce_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
        'id'            => 'shop-sidebar',
        'description'   => esc_html__( 'Add widgets here to appear in your shop sidebar.', 'aqualuxe' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s mb-8">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title text-xl font-bold mb-4">',
        'after_title'   => '</h2>',
    ) );
    
    register_sidebar( array(
        'name'          => esc_html__( 'Product Sidebar', 'aqualuxe' ),
        'id'            => 'product-sidebar',
        'description'   => esc_html__( 'Add widgets here to appear in your product sidebar.', 'aqualuxe' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s mb-8">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title text-xl font-bold mb-4">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'aqualuxe_woocommerce_widgets_init' );

/**
 * Add custom product tabs
 *
 * @param array $tabs Product tabs.
 * @return array
 */
function aqualuxe_woocommerce_product_tabs( $tabs ) {
    // Add shipping tab
    $tabs['shipping'] = array(
        'title'    => esc_html__( 'Shipping & Returns', 'aqualuxe' ),
        'priority' => 30,
        'callback' => 'aqualuxe_woocommerce_shipping_tab_content',
    );
    
    // Add custom tab
    $tabs['custom'] = array(
        'title'    => esc_html__( 'Care Instructions', 'aqualuxe' ),
        'priority' => 40,
        'callback' => 'aqualuxe_woocommerce_custom_tab_content',
    );
    
    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs' );

/**
 * Shipping tab content
 */
function aqualuxe_woocommerce_shipping_tab_content() {
    // Get shipping content from product meta
    $shipping_content = get_post_meta( get_the_ID(), '_shipping_content', true );
    
    if ( $shipping_content ) {
        echo wp_kses_post( wpautop( $shipping_content ) );
    } else {
        // Default shipping content
        ?>
        <h3><?php esc_html_e( 'Shipping Information', 'aqualuxe' ); ?></h3>
        <p><?php esc_html_e( 'We ship worldwide using premium carriers to ensure the safe arrival of your aquatic life and products.', 'aqualuxe' ); ?></p>
        
        <ul>
            <li><?php esc_html_e( 'Domestic shipping: 1-3 business days', 'aqualuxe' ); ?></li>
            <li><?php esc_html_e( 'International shipping: 3-7 business days', 'aqualuxe' ); ?></li>
            <li><?php esc_html_e( 'Live fish and plants are shipped with special insulated packaging', 'aqualuxe' ); ?></li>
            <li><?php esc_html_e( 'Free shipping on orders over $150', 'aqualuxe' ); ?></li>
        </ul>
        
        <h3><?php esc_html_e( 'Returns Policy', 'aqualuxe' ); ?></h3>
        <p><?php esc_html_e( 'We want you to be completely satisfied with your purchase.', 'aqualuxe' ); ?></p>
        
        <ul>
            <li><?php esc_html_e( 'Equipment and supplies: 30-day return policy', 'aqualuxe' ); ?></li>
            <li><?php esc_html_e( 'Live fish and plants: 7-day guarantee (with proper acclimation documentation)', 'aqualuxe' ); ?></li>
            <li><?php esc_html_e( 'Contact our customer service team for return authorization', 'aqualuxe' ); ?></li>
        </ul>
        <?php
    }
}

/**
 * Custom tab content
 */
function aqualuxe_woocommerce_custom_tab_content() {
    // Get custom content from product meta
    $custom_content = get_post_meta( get_the_ID(), '_custom_content', true );
    
    if ( $custom_content ) {
        echo wp_kses_post( wpautop( $custom_content ) );
    } else {
        // Default custom content
        ?>
        <h3><?php esc_html_e( 'Care Instructions', 'aqualuxe' ); ?></h3>
        <p><?php esc_html_e( 'To ensure the health and longevity of your aquatic life:', 'aqualuxe' ); ?></p>
        
        <ul>
            <li><?php esc_html_e( 'Maintain proper water parameters (pH, temperature, hardness)', 'aqualuxe' ); ?></li>
            <li><?php esc_html_e( 'Perform regular water changes (20-30% weekly)', 'aqualuxe' ); ?></li>
            <li><?php esc_html_e( 'Use appropriate filtration and water conditioning', 'aqualuxe' ); ?></li>
            <li><?php esc_html_e( 'Feed high-quality food appropriate for your species', 'aqualuxe' ); ?></li>
            <li><?php esc_html_e( 'Monitor tank mates for compatibility', 'aqualuxe' ); ?></li>
        </ul>
        
        <p><?php esc_html_e( 'For specific care requirements for this species, please refer to our care guides or contact our expert team.', 'aqualuxe' ); ?></p>
        <?php
    }
}

/**
 * Add product meta boxes
 */
function aqualuxe_woocommerce_add_product_meta_boxes() {
    add_meta_box(
        'aqualuxe_product_shipping',
        esc_html__( 'Shipping & Returns', 'aqualuxe' ),
        'aqualuxe_woocommerce_shipping_meta_box',
        'product',
        'normal',
        'default'
    );
    
    add_meta_box(
        'aqualuxe_product_custom',
        esc_html__( 'Care Instructions', 'aqualuxe' ),
        'aqualuxe_woocommerce_custom_meta_box',
        'product',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'aqualuxe_woocommerce_add_product_meta_boxes' );

/**
 * Shipping meta box
 *
 * @param WP_Post $post Post object.
 */
function aqualuxe_woocommerce_shipping_meta_box( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_woocommerce_shipping_meta_box', 'aqualuxe_woocommerce_shipping_meta_box_nonce' );
    
    // Get shipping content
    $shipping_content = get_post_meta( $post->ID, '_shipping_content', true );
    
    // Output field
    ?>
    <p>
        <label for="aqualuxe_shipping_content"><?php esc_html_e( 'Shipping & Returns Content', 'aqualuxe' ); ?></label>
        <?php
        wp_editor(
            $shipping_content,
            'aqualuxe_shipping_content',
            array(
                'textarea_name' => 'aqualuxe_shipping_content',
                'textarea_rows' => 10,
                'media_buttons' => true,
                'teeny'         => true,
            )
        );
        ?>
    </p>
    <?php
}

/**
 * Custom meta box
 *
 * @param WP_Post $post Post object.
 */
function aqualuxe_woocommerce_custom_meta_box( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_woocommerce_custom_meta_box', 'aqualuxe_woocommerce_custom_meta_box_nonce' );
    
    // Get custom content
    $custom_content = get_post_meta( $post->ID, '_custom_content', true );
    
    // Output field
    ?>
    <p>
        <label for="aqualuxe_custom_content"><?php esc_html_e( 'Care Instructions Content', 'aqualuxe' ); ?></label>
        <?php
        wp_editor(
            $custom_content,
            'aqualuxe_custom_content',
            array(
                'textarea_name' => 'aqualuxe_custom_content',
                'textarea_rows' => 10,
                'media_buttons' => true,
                'teeny'         => true,
            )
        );
        ?>
    </p>
    <?php
}

/**
 * Save product meta boxes
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_woocommerce_save_product_meta_boxes( $post_id ) {
    // Check if nonce is set
    if ( ! isset( $_POST['aqualuxe_woocommerce_shipping_meta_box_nonce'] ) || ! isset( $_POST['aqualuxe_woocommerce_custom_meta_box_nonce'] ) ) {
        return;
    }
    
    // Verify nonce
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aqualuxe_woocommerce_shipping_meta_box_nonce'] ) ), 'aqualuxe_woocommerce_shipping_meta_box' ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aqualuxe_woocommerce_custom_meta_box_nonce'] ) ), 'aqualuxe_woocommerce_custom_meta_box' ) ) {
        return;
    }
    
    // Check if autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    // Save shipping content
    if ( isset( $_POST['aqualuxe_shipping_content'] ) ) {
        update_post_meta( $post_id, '_shipping_content', wp_kses_post( wp_unslash( $_POST['aqualuxe_shipping_content'] ) ) );
    }
    
    // Save custom content
    if ( isset( $_POST['aqualuxe_custom_content'] ) ) {
        update_post_meta( $post_id, '_custom_content', wp_kses_post( wp_unslash( $_POST['aqualuxe_custom_content'] ) ) );
    }
}
add_action( 'save_post', 'aqualuxe_woocommerce_save_product_meta_boxes' );

/**
 * Add product specifications
 */
function aqualuxe_woocommerce_product_specifications() {
    global $product;
    
    // Get product attributes
    $attributes = $product->get_attributes();
    
    if ( ! $attributes ) {
        return;
    }
    
    ?>
    <div class="product-specifications mt-8">
        <h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Product Specifications', 'aqualuxe' ); ?></h3>
        
        <table class="w-full border-collapse">
            <tbody>
                <?php foreach ( $attributes as $attribute ) : ?>
                    <?php if ( $attribute->get_visible() ) : ?>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 px-4 text-left font-medium"><?php echo wc_attribute_label( $attribute->get_name() ); ?></th>
                            <td class="py-2 px-4">
                                <?php
                                if ( $attribute->is_taxonomy() ) {
                                    $values = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
                                    echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );
                                } else {
                                    $values = $attribute->get_options();
                                    echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_specifications', 25 );

/**
 * Add product social sharing
 */
function aqualuxe_woocommerce_product_sharing() {
    global $product;
    
    $product_url  = get_permalink();
    $product_title = get_the_title();
    $product_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
    $product_image_url = $product_image ? $product_image[0] : '';
    
    ?>
    <div class="product-sharing mt-8">
        <h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Share This Product', 'aqualuxe' ); ?></h3>
        
        <div class="flex space-x-4">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( $product_url ); ?>" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"/><path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5z"/></svg>
                <span class="sr-only"><?php esc_html_e( 'Share on Facebook', 'aqualuxe' ); ?></span>
            </a>
            
            <a href="https://twitter.com/intent/tweet?text=<?php echo esc_attr( $product_title ); ?>&url=<?php echo esc_url( $product_url ); ?>" target="_blank" rel="noopener noreferrer" class="text-blue-400 hover:text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"/><path d="M22.162 5.656a8.384 8.384 0 0 1-2.402.658A4.196 4.196 0 0 0 21.6 4c-.82.488-1.719.83-2.656 1.015a4.182 4.182 0 0 0-7.126 3.814 11.874 11.874 0 0 1-8.62-4.37 4.168 4.168 0 0 0-.566 2.103c0 1.45.738 2.731 1.86 3.481a4.168 4.168 0 0 1-1.894-.523v.052a4.185 4.185 0 0 0 3.355 4.101 4.21 4.21 0 0 1-1.89.072A4.185 4.185 0 0 0 7.97 16.65a8.394 8.394 0 0 1-6.191 1.732 11.83 11.83 0 0 0 6.41 1.88c7.693 0 11.9-6.373 11.9-11.9 0-.18-.005-.362-.013-.54a8.496 8.496 0 0 0 2.087-2.165z"/></svg>
                <span class="sr-only"><?php esc_html_e( 'Share on Twitter', 'aqualuxe' ); ?></span>
            </a>
            
            <a href="https://pinterest.com/pin/create/button/?url=<?php echo esc_url( $product_url ); ?>&media=<?php echo esc_url( $product_image_url ); ?>&description=<?php echo esc_attr( $product_title ); ?>" target="_blank" rel="noopener noreferrer" class="text-red-600 hover:text-red-800">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.37 2.094A10.003 10.003 0 0 0 8.002 21.17a7.757 7.757 0 0 1 .163-2.293c.185-.839 1.296-5.463 1.296-5.463a3.739 3.739 0 0 1-.324-1.577c0-1.485.857-2.593 1.923-2.593a1.334 1.334 0 0 1 1.342 1.508c0 .9-.578 2.262-.88 3.54a1.544 1.544 0 0 0 1.575 1.923c1.898 0 3.17-2.431 3.17-5.301 0-2.2-1.457-3.848-4.143-3.848a4.746 4.746 0 0 0-4.93 4.794 2.96 2.96 0 0 0 .648 1.97.48.48 0 0 1 .162.554c-.46.184-.162.623-.208.784a.354.354 0 0 1-.51.254c-1.384-.554-2.036-2.077-2.036-3.816 0-2.847 2.384-6.255 7.154-6.255 3.796 0 6.32 2.777 6.32 5.747 0 3.909-2.177 6.848-5.394 6.848a2.861 2.861 0 0 1-2.454-1.246s-.578 2.316-.692 2.754a8.026 8.026 0 0 1-1.019 2.131c.923.28 1.882.42 2.846.416a9.988 9.988 0 0 0 9.996-10.003 10.002 10.002 0 0 0-8.635-9.903z"/></svg>
                <span class="sr-only"><?php esc_html_e( 'Share on Pinterest', 'aqualuxe' ); ?></span>
            </a>
            
            <a href="mailto:?subject=<?php echo esc_attr( $product_title ); ?>&body=<?php echo esc_url( $product_url ); ?>" class="text-gray-600 hover:text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 3h18a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zm17 4.238l-7.928 7.1L4 7.216V19h16V7.238zM4.511 5l7.55 6.662L19.502 5H4.511z"/></svg>
                <span class="sr-only"><?php esc_html_e( 'Share via Email', 'aqualuxe' ); ?></span>
            </a>
        </div>
    </div>
    <?php
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_sharing', 50 );

/**
 * Add recently viewed products
 */
function aqualuxe_woocommerce_recently_viewed_products() {
    if ( ! is_singular( 'product' ) ) {
        return;
    }
    
    // Get current product ID
    $current_product_id = get_the_ID();
    
    // Get cookie
    $viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', sanitize_text_field( wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) ) : array();
    
    // Remove current product
    $viewed_products = array_diff( $viewed_products, array( $current_product_id ) );
    
    // If no viewed products, return
    if ( empty( $viewed_products ) ) {
        return;
    }
    
    // Get products
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 4,
        'post__in'       => $viewed_products,
        'orderby'        => 'post__in',
        'post_status'    => 'publish',
    );
    
    $products = new WP_Query( $args );
    
    if ( ! $products->have_posts() ) {
        return;
    }
    
    ?>
    <section class="recently-viewed-products py-12 border-t border-gray-200 dark:border-gray-700">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-8"><?php esc_html_e( 'Recently Viewed Products', 'aqualuxe' ); ?></h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php while ( $products->have_posts() ) : $products->the_post(); ?>
                    <?php wc_get_template_part( 'content', 'product' ); ?>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php
    
    wp_reset_postdata();
}
add_action( 'woocommerce_after_single_product', 'aqualuxe_woocommerce_recently_viewed_products', 20 );

/**
 * Track recently viewed products
 */
function aqualuxe_woocommerce_track_product_view() {
    if ( ! is_singular( 'product' ) ) {
        return;
    }
    
    global $post;
    
    if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) ) {
        $viewed_products = array();
    } else {
        $viewed_products = (array) explode( '|', sanitize_text_field( wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) );
    }
    
    // Remove current product
    $viewed_products = array_diff( $viewed_products, array( $post->ID ) );
    
    // Add current product
    $viewed_products[] = $post->ID;
    
    // Limit to 15 products
    if ( count( $viewed_products ) > 15 ) {
        array_shift( $viewed_products );
    }
    
    // Store in cookie
    wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
}
add_action( 'template_redirect', 'aqualuxe_woocommerce_track_product_view', 20 );

/**
 * Add product inquiry form
 */
function aqualuxe_woocommerce_product_inquiry_form() {
    global $product;
    
    ?>
    <div class="product-inquiry-form mt-8">
        <h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Product Inquiry', 'aqualuxe' ); ?></h3>
        
        <form class="inquiry-form bg-gray-50 dark:bg-gray-800 p-6 rounded-lg">
            <input type="hidden" name="product_id" value="<?php echo esc_attr( $product->get_id() ); ?>">
            <input type="hidden" name="product_name" value="<?php echo esc_attr( $product->get_name() ); ?>">
            
            <div class="mb-4">
                <label for="inquiry_name" class="block mb-2"><?php esc_html_e( 'Name', 'aqualuxe' ); ?> <span class="text-red-600">*</span></label>
                <input type="text" id="inquiry_name" name="inquiry_name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700" required>
            </div>
            
            <div class="mb-4">
                <label for="inquiry_email" class="block mb-2"><?php esc_html_e( 'Email', 'aqualuxe' ); ?> <span class="text-red-600">*</span></label>
                <input type="email" id="inquiry_email" name="inquiry_email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700" required>
            </div>
            
            <div class="mb-4">
                <label for="inquiry_message" class="block mb-2"><?php esc_html_e( 'Message', 'aqualuxe' ); ?> <span class="text-red-600">*</span></label>
                <textarea id="inquiry_message" name="inquiry_message" rows="4" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700" required></textarea>
            </div>
            
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="inquiry_terms" class="mr-2" required>
                    <span><?php esc_html_e( 'I agree to the terms and conditions', 'aqualuxe' ); ?> <span class="text-red-600">*</span></span>
                </label>
            </div>
            
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors"><?php esc_html_e( 'Send Inquiry', 'aqualuxe' ); ?></button>
        </form>
    </div>
    <?php
}
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_inquiry_form', 15 );

/**
 * Add product inquiry AJAX handler
 */
function aqualuxe_woocommerce_product_inquiry_ajax() {
    // Check nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }
    
    // Check required fields
    if ( ! isset( $_POST['product_id'] ) || ! isset( $_POST['product_name'] ) || ! isset( $_POST['inquiry_name'] ) || ! isset( $_POST['inquiry_email'] ) || ! isset( $_POST['inquiry_message'] ) ) {
        wp_send_json_error( 'Missing required fields' );
    }
    
    // Get form data
    $product_id   = absint( $_POST['product_id'] );
    $product_name = sanitize_text_field( wp_unslash( $_POST['product_name'] ) );
    $name         = sanitize_text_field( wp_unslash( $_POST['inquiry_name'] ) );
    $email        = sanitize_email( wp_unslash( $_POST['inquiry_email'] ) );
    $message      = sanitize_textarea_field( wp_unslash( $_POST['inquiry_message'] ) );
    
    // Send email
    $to      = get_option( 'admin_email' );
    $subject = sprintf( esc_html__( 'Product Inquiry: %s', 'aqualuxe' ), $product_name );
    $body    = sprintf(
        esc_html__( 'Name: %1$s
Email: %2$s
Product: %3$s (ID: %4$s)
Message: %5$s', 'aqualuxe' ),
        $name,
        $email,
        $product_name,
        $product_id,
        $message
    );
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $name . ' <' . $email . '>',
        'Reply-To: ' . $email,
    );
    
    $sent = wp_mail( $to, $subject, $body, $headers );
    
    if ( $sent ) {
        wp_send_json_success( array(
            'message' => esc_html__( 'Your inquiry has been sent successfully. We will get back to you soon.', 'aqualuxe' ),
        ) );
    } else {
        wp_send_json_error( esc_html__( 'Failed to send inquiry. Please try again later.', 'aqualuxe' ) );
    }
}
add_action( 'wp_ajax_aqualuxe_product_inquiry', 'aqualuxe_woocommerce_product_inquiry_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_product_inquiry', 'aqualuxe_woocommerce_product_inquiry_ajax' );