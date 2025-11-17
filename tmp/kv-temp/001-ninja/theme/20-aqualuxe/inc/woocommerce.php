<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package AquaLuxe
 */

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
 *
 * @return void
 */
function aqualuxe_woocommerce_setup() {
    // Add theme support for WooCommerce features
    add_theme_support(
        'woocommerce',
        array(
            'thumbnail_image_width' => 400,
            'single_image_width'    => 800,
            'product_grid'          => array(
                'default_rows'    => 3,
                'min_rows'        => 1,
                'default_columns' => 4,
                'min_columns'     => 2,
                'max_columns'     => 6,
            ),
        )
    );

    // Enable product gallery features based on theme customizer settings
    if ( get_theme_mod( 'aqualuxe_product_gallery_zoom', true ) ) {
        add_theme_support( 'wc-product-gallery-zoom' );
    }
    
    if ( get_theme_mod( 'aqualuxe_product_gallery_lightbox', true ) ) {
        add_theme_support( 'wc-product-gallery-lightbox' );
    }
    
    if ( get_theme_mod( 'aqualuxe_product_gallery_slider', true ) ) {
        add_theme_support( 'wc-product-gallery-slider' );
    }
}
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_setup' );

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function aqualuxe_woocommerce_scripts() {
    // WooCommerce styles
    wp_enqueue_style(
        'aqualuxe-woocommerce-style',
        AQUALUXE_URI . 'assets/css/woocommerce.css',
        array('woocommerce-general'),
        AQUALUXE_VERSION
    );

    // WooCommerce scripts
    wp_enqueue_script(
        'aqualuxe-woocommerce-script',
        AQUALUXE_URI . 'assets/js/woocommerce.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );

    // Quick view script
    if ( get_theme_mod( 'aqualuxe_enable_quick_view', true ) ) {
        wp_enqueue_script(
            'aqualuxe-quick-view',
            AQUALUXE_URI . 'assets/js/quick-view.js',
            array('jquery', 'wc-add-to-cart-variation'),
            AQUALUXE_VERSION,
            true
        );
    }

    // Wishlist script
    if ( get_theme_mod( 'aqualuxe_enable_wishlist', true ) ) {
        wp_enqueue_script(
            'aqualuxe-wishlist',
            AQUALUXE_URI . 'assets/js/wishlist.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
    }

    // Localize scripts
    wp_localize_script(
        'aqualuxe-woocommerce-script',
        'aqualuxeWooCommerce',
        array(
            'ajaxurl'             => admin_url( 'admin-ajax.php' ),
            'wc_ajax_url'         => WC_AJAX::get_endpoint( '%%endpoint%%' ),
            'i18n_no_matching'    => esc_html__( 'No matches found', 'aqualuxe' ),
            'i18n_added_to_cart'  => esc_html__( 'Added to cart', 'aqualuxe' ),
            'i18n_add_to_wishlist' => esc_html__( 'Add to wishlist', 'aqualuxe' ),
            'i18n_browse_wishlist' => esc_html__( 'Browse wishlist', 'aqualuxe' ),
            'i18n_already_in_wishlist' => esc_html__( 'The product is already in your wishlist', 'aqualuxe' ),
        )
    );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts' );

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param array $classes CSS classes applied to the body tag.
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
        'posts_per_page' => get_theme_mod( 'aqualuxe_related_products_count', 4 ),
        'columns'        => get_theme_mod( 'aqualuxe_shop_columns', 3 ),
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

/**
 * Before Content.
 *
 * Wraps all WooCommerce content in wrappers which match the theme markup.
 *
 * @return void
 */
function aqualuxe_woocommerce_wrapper_before() {
    ?>
    <main id="primary" class="site-main">
        <div class="container mx-auto px-4 py-8">
            <?php if ( is_product() ) : ?>
                <div class="product-container">
            <?php elseif ( is_shop() || is_product_category() || is_product_tag() ) : ?>
                <div class="shop-container flex flex-wrap">
                    <?php if ( is_active_sidebar( 'shop-sidebar' ) && get_theme_mod( 'aqualuxe_content_layout', 'right-sidebar' ) !== 'no-sidebar' ) : ?>
                        <div class="shop-content w-full lg:w-3/4 <?php echo get_theme_mod( 'aqualuxe_content_layout', 'right-sidebar' ) === 'left-sidebar' ? 'lg:order-2' : ''; ?>">
                    <?php else : ?>
                        <div class="shop-content w-full">
                    <?php endif; ?>
            <?php else : ?>
                <div class="woocommerce-container">
            <?php endif; ?>
    <?php
}
add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before' );

/**
 * After Content.
 *
 * Closes the wrapping divs.
 *
 * @return void
 */
function aqualuxe_woocommerce_wrapper_after() {
    ?>
                </div><!-- .shop-content or .product-container or .woocommerce-container -->
                
                <?php if ( ( is_shop() || is_product_category() || is_product_tag() ) && is_active_sidebar( 'shop-sidebar' ) && get_theme_mod( 'aqualuxe_content_layout', 'right-sidebar' ) !== 'no-sidebar' ) : ?>
                    <aside class="shop-sidebar w-full lg:w-1/4 mt-8 lg:mt-0 <?php echo get_theme_mod( 'aqualuxe_content_layout', 'right-sidebar' ) === 'left-sidebar' ? 'lg:order-1 lg:pr-8' : 'lg:pl-8'; ?>">
                        <?php dynamic_sidebar( 'shop-sidebar' ); ?>
                    </aside>
                <?php endif; ?>
                
                <?php if ( is_shop() || is_product_category() || is_product_tag() ) : ?>
                    </div><!-- .shop-container -->
                <?php endif; ?>
        </div><!-- .container -->
    </main><!-- #main -->
    <?php
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
        <a class="cart-contents flex items-center text-gray-500 hover:text-primary transition-colors duration-200" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <span class="cart-count ml-1 text-sm font-medium"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
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
        <div class="site-header-cart relative group">
            <div class="<?php echo esc_attr( $class ); ?>">
                <?php aqualuxe_woocommerce_cart_link(); ?>
            </div>
            <div class="cart-dropdown hidden absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg z-50 group-hover:block">
                <?php
                $instance = array(
                    'title' => '',
                );

                the_widget( 'WC_Widget_Cart', $instance );
                ?>
            </div>
        </div>
        <?php
    }
}

/**
 * Change number of products per row
 */
function aqualuxe_woocommerce_loop_columns() {
    return get_theme_mod( 'aqualuxe_shop_columns', 3 );
}
add_filter( 'loop_shop_columns', 'aqualuxe_woocommerce_loop_columns' );

/**
 * Change number of products per page
 */
function aqualuxe_woocommerce_products_per_page() {
    return get_theme_mod( 'aqualuxe_products_per_page', 12 );
}
add_filter( 'loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page' );

/**
 * Add custom classes to products in the loop
 */
function aqualuxe_woocommerce_product_loop_classes( $classes ) {
    $classes[] = 'product-card';
    $classes[] = 'bg-white';
    $classes[] = 'rounded-lg';
    $classes[] = 'shadow-sm';
    $classes[] = 'hover:shadow-md';
    $classes[] = 'transition-shadow';
    $classes[] = 'duration-300';
    $classes[] = 'overflow-hidden';
    
    return $classes;
}
add_filter( 'woocommerce_post_class', 'aqualuxe_woocommerce_product_loop_classes', 10, 1 );

/**
 * Modify breadcrumb defaults
 */
function aqualuxe_woocommerce_breadcrumb_defaults( $args ) {
    $args['delimiter']   = '<span class="mx-2 text-gray-400">/</span>';
    $args['wrap_before'] = '<nav class="woocommerce-breadcrumb text-sm py-2" itemprop="breadcrumb">';
    $args['wrap_after']  = '</nav>';
    
    return $args;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumb_defaults' );

/**
 * Add quick view button to product loops
 */
function aqualuxe_add_quick_view_button() {
    if ( get_theme_mod( 'aqualuxe_enable_quick_view', true ) ) {
        global $product;
        
        echo '<div class="quick-view-wrapper absolute top-2 right-2">';
        echo '<button class="quick-view-button bg-white bg-opacity-90 hover:bg-opacity-100 text-gray-700 p-2 rounded-full shadow-sm hover:shadow transition-all duration-200" data-product-id="' . esc_attr( $product->get_id() ) . '" aria-label="' . esc_attr__( 'Quick view', 'aqualuxe' ) . '">';
        echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>';
        echo '</button>';
        echo '</div>';
    }
}
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_add_quick_view_button', 15 );

/**
 * Add wishlist button to product loops
 */
function aqualuxe_add_wishlist_button() {
    if ( get_theme_mod( 'aqualuxe_enable_wishlist', true ) ) {
        global $product;
        
        echo '<div class="wishlist-wrapper absolute top-2 left-2">';
        echo '<button class="wishlist-button bg-white bg-opacity-90 hover:bg-opacity-100 text-gray-700 p-2 rounded-full shadow-sm hover:shadow transition-all duration-200" data-product-id="' . esc_attr( $product->get_id() ) . '" aria-label="' . esc_attr__( 'Add to wishlist', 'aqualuxe' ) . '">';
        echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>';
        echo '</button>';
        echo '</div>';
    }
}
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_add_wishlist_button', 15 );

/**
 * Quick view AJAX handler
 */
function aqualuxe_quick_view_ajax() {
    if ( ! isset( $_POST['product_id'] ) ) {
        wp_die();
    }

    $product_id = absint( $_POST['product_id'] );
    
    // Set the main WP query for the product
    wp( array(
        'p'         => $product_id,
        'post_type' => 'product',
    ) );

    ob_start();
    
    // Load content template
    wc_get_template( 'content-quick-view.php' );
    
    $output = ob_get_clean();
    
    wp_send_json_success( $output );
    
    wp_die();
}
add_action( 'wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax' );

/**
 * Wishlist AJAX handler
 */
function aqualuxe_wishlist_ajax() {
    // Check for nonce security
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_wishlist' ) ) {
        wp_send_json_error( 'Invalid security token' );
    }

    if ( ! isset( $_POST['product_id'] ) ) {
        wp_send_json_error( 'No product specified' );
    }

    $product_id = absint( $_POST['product_id'] );
    $user_id = get_current_user_id();
    
    // If user is not logged in, use cookies
    if ( $user_id === 0 ) {
        $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( stripslashes( $_COOKIE['aqualuxe_wishlist'] ), true ) : array();
        
        if ( in_array( $product_id, $wishlist ) ) {
            // Remove from wishlist
            $wishlist = array_diff( $wishlist, array( $product_id ) );
            $action = 'removed';
        } else {
            // Add to wishlist
            $wishlist[] = $product_id;
            $action = 'added';
        }
        
        setcookie( 'aqualuxe_wishlist', json_encode( $wishlist ), time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
    } else {
        // Use user meta for logged in users
        $wishlist = get_user_meta( $user_id, 'aqualuxe_wishlist', true );
        
        if ( ! is_array( $wishlist ) ) {
            $wishlist = array();
        }
        
        if ( in_array( $product_id, $wishlist ) ) {
            // Remove from wishlist
            $wishlist = array_diff( $wishlist, array( $product_id ) );
            $action = 'removed';
        } else {
            // Add to wishlist
            $wishlist[] = $product_id;
            $action = 'added';
        }
        
        update_user_meta( $user_id, 'aqualuxe_wishlist', $wishlist );
    }
    
    wp_send_json_success( array(
        'action'     => $action,
        'product_id' => $product_id,
    ) );
}
add_action( 'wp_ajax_aqualuxe_wishlist', 'aqualuxe_wishlist_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_wishlist', 'aqualuxe_wishlist_ajax' );

/**
 * Create wishlist page on theme activation
 */
function aqualuxe_create_wishlist_page() {
    // Check if page already exists
    $wishlist_page = get_page_by_path( 'wishlist' );
    
    if ( ! $wishlist_page ) {
        $page_data = array(
            'post_title'    => 'Wishlist',
            'post_content'  => '[aqualuxe_wishlist]',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => 'wishlist',
        );
        
        wp_insert_post( $page_data );
    }
}
add_action( 'after_switch_theme', 'aqualuxe_create_wishlist_page' );

/**
 * Wishlist shortcode
 */
function aqualuxe_wishlist_shortcode() {
    ob_start();
    
    $user_id = get_current_user_id();
    
    if ( $user_id === 0 ) {
        // Get wishlist from cookies for non-logged in users
        $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( stripslashes( $_COOKIE['aqualuxe_wishlist'] ), true ) : array();
    } else {
        // Get wishlist from user meta for logged in users
        $wishlist = get_user_meta( $user_id, 'aqualuxe_wishlist', true );
        
        if ( ! is_array( $wishlist ) ) {
            $wishlist = array();
        }
    }
    
    // Remove any invalid product IDs
    $wishlist = array_filter( $wishlist, function( $product_id ) {
        return wc_get_product( $product_id ) !== false;
    } );
    
    if ( empty( $wishlist ) ) {
        echo '<div class="woocommerce-info">' . esc_html__( 'Your wishlist is empty.', 'aqualuxe' ) . '</div>';
        echo '<p><a class="button" href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '">' . esc_html__( 'Browse products', 'aqualuxe' ) . '</a></p>';
    } else {
        echo '<div class="wishlist-products grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">';
        
        foreach ( $wishlist as $product_id ) {
            $product = wc_get_product( $product_id );
            
            if ( ! $product ) {
                continue;
            }
            
            echo '<div class="wishlist-product bg-white rounded-lg shadow-sm overflow-hidden">';
            echo '<div class="wishlist-product-image relative">';
            echo '<a href="' . esc_url( $product->get_permalink() ) . '">';
            echo $product->get_image( 'woocommerce_thumbnail', array( 'class' => 'w-full h-auto' ) );
            echo '</a>';
            echo '<button class="remove-from-wishlist absolute top-2 right-2 bg-white bg-opacity-90 hover:bg-opacity-100 text-gray-700 p-2 rounded-full shadow-sm hover:shadow transition-all duration-200" data-product-id="' . esc_attr( $product_id ) . '">';
            echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            echo '</button>';
            echo '</div>';
            echo '<div class="wishlist-product-content p-4">';
            echo '<h3 class="wishlist-product-title text-lg font-medium mb-2"><a href="' . esc_url( $product->get_permalink() ) . '" class="text-gray-900 hover:text-primary">' . esc_html( $product->get_name() ) . '</a></h3>';
            echo '<div class="wishlist-product-price mb-4">' . $product->get_price_html() . '</div>';
            echo '<div class="wishlist-product-actions">';
            echo '<a href="' . esc_url( $product->add_to_cart_url() ) . '" class="button add_to_cart_button ajax_add_to_cart bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-md inline-block transition-colors duration-200" data-product_id="' . esc_attr( $product_id ) . '">' . esc_html__( 'Add to cart', 'aqualuxe' ) . '</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
    }
    
    return ob_get_clean();
}
add_shortcode( 'aqualuxe_wishlist', 'aqualuxe_wishlist_shortcode' );

/**
 * Add custom image sizes for WooCommerce
 */
function aqualuxe_add_woocommerce_image_sizes() {
    add_image_size( 'aqualuxe-product-thumbnail', 400, 400, true );
    add_image_size( 'aqualuxe-product-gallery', 800, 800, true );
}
add_action( 'after_setup_theme', 'aqualuxe_add_woocommerce_image_sizes' );

/**
 * Modify sale flash
 */
function aqualuxe_sale_flash( $html, $post, $product ) {
    return '<span class="onsale absolute top-4 left-4 bg-red-500 text-white text-xs font-bold uppercase py-1 px-2 rounded-sm z-10">' . esc_html__( 'Sale', 'aqualuxe' ) . '</span>';
}
add_filter( 'woocommerce_sale_flash', 'aqualuxe_sale_flash', 10, 3 );

/**
 * Add custom badges to products
 */
function aqualuxe_product_badges() {
    global $product;
    
    // New product badge (products added in the last 7 days)
    $days_as_new = 7;
    $created_date = strtotime( $product->get_date_created() );
    
    if ( ( time() - ( 60 * 60 * 24 * $days_as_new ) ) < $created_date ) {
        echo '<span class="badge-new absolute top-4 left-4 bg-green-500 text-white text-xs font-bold uppercase py-1 px-2 rounded-sm z-10">' . esc_html__( 'New', 'aqualuxe' ) . '</span>';
    }
    
    // Featured product badge
    if ( $product->is_featured() ) {
        echo '<span class="badge-featured absolute top-4 left-4 bg-purple-500 text-white text-xs font-bold uppercase py-1 px-2 rounded-sm z-10">' . esc_html__( 'Featured', 'aqualuxe' ) . '</span>';
    }
    
    // Out of stock badge
    if ( ! $product->is_in_stock() ) {
        echo '<span class="badge-out-of-stock absolute top-4 left-4 bg-gray-500 text-white text-xs font-bold uppercase py-1 px-2 rounded-sm z-10">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</span>';
    }
}
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_product_badges', 10 );

/**
 * Modify product title in loops
 */
function aqualuxe_woocommerce_template_loop_product_title() {
    echo '<h2 class="woocommerce-loop-product__title text-gray-900 font-medium text-lg mb-2">' . get_the_title() . '</h2>';
}
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_title', 10 );

/**
 * Modify add to cart button in loops
 */
function aqualuxe_woocommerce_loop_add_to_cart_link( $html, $product, $args ) {
    $html = sprintf(
        '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
        esc_url( $product->add_to_cart_url() ),
        esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
        esc_attr( isset( $args['class'] ) ? $args['class'] . ' button-primary w-full text-center py-2 px-4 rounded-md transition-colors duration-200' : 'button-primary w-full text-center py-2 px-4 rounded-md transition-colors duration-200' ),
        isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
        esc_html( $product->add_to_cart_text() )
    );

    return $html;
}
add_filter( 'woocommerce_loop_add_to_cart_link', 'aqualuxe_woocommerce_loop_add_to_cart_link', 10, 3 );

/**
 * Add product category description to category archives
 */
function aqualuxe_add_category_description() {
    if ( is_product_category() ) {
        $category = get_queried_object();
        $description = $category->description;
        
        if ( $description ) {
            echo '<div class="category-description mb-8 prose max-w-none">' . wc_format_content( $description ) . '</div>';
        }
    }
}
add_action( 'woocommerce_archive_description', 'aqualuxe_add_category_description', 10 );

/**
 * Modify number of products shown per row on mobile
 */
function aqualuxe_loop_shop_columns_mobile( $classes ) {
    if ( is_product_category() || is_shop() ) {
        $classes[] = 'mobile-columns-2';
    }
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_loop_shop_columns_mobile' );

/**
 * Add custom tabs to single product page
 */
function aqualuxe_custom_product_tabs( $tabs ) {
    // Shipping tab
    $tabs['shipping'] = array(
        'title'    => __( 'Shipping & Returns', 'aqualuxe' ),
        'priority' => 30,
        'callback' => 'aqualuxe_shipping_tab_content',
    );
    
    // Care tab for specific product categories
    global $product;
    
    if ( $product && has_term( array( 'fish', 'plants', 'aquariums' ), 'product_cat', $product->get_id() ) ) {
        $tabs['care'] = array(
            'title'    => __( 'Care Guide', 'aqualuxe' ),
            'priority' => 40,
            'callback' => 'aqualuxe_care_tab_content',
        );
    }
    
    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'aqualuxe_custom_product_tabs' );

/**
 * Shipping tab content
 */
function aqualuxe_shipping_tab_content() {
    // Get shipping content from theme mod or use default
    $shipping_content = get_theme_mod( 'aqualuxe_shipping_content', '' );
    
    if ( empty( $shipping_content ) ) {
        // Default content
        echo '<h3>' . esc_html__( 'Shipping Information', 'aqualuxe' ) . '</h3>';
        echo '<p>' . esc_html__( 'We ship worldwide with special care for live aquatic species. Shipping times and methods vary based on your location and the products ordered.', 'aqualuxe' ) . '</p>';
        
        echo '<h4>' . esc_html__( 'Domestic Shipping', 'aqualuxe' ) . '</h4>';
        echo '<ul>';
        echo '<li>' . esc_html__( 'Standard Shipping: 3-5 business days', 'aqualuxe' ) . '</li>';
        echo '<li>' . esc_html__( 'Express Shipping: 1-2 business days', 'aqualuxe' ) . '</li>';
        echo '<li>' . esc_html__( 'Free shipping on orders over $100', 'aqualuxe' ) . '</li>';
        echo '</ul>';
        
        echo '<h4>' . esc_html__( 'International Shipping', 'aqualuxe' ) . '</h4>';
        echo '<ul>';
        echo '<li>' . esc_html__( 'Standard International: 7-14 business days', 'aqualuxe' ) . '</li>';
        echo '<li>' . esc_html__( 'Express International: 3-5 business days', 'aqualuxe' ) . '</li>';
        echo '<li>' . esc_html__( 'Import duties and taxes may apply', 'aqualuxe' ) . '</li>';
        echo '</ul>';
        
        echo '<h3>' . esc_html__( 'Returns & Exchanges', 'aqualuxe' ) . '</h3>';
        echo '<p>' . esc_html__( 'We want you to be completely satisfied with your purchase. If you are not satisfied, please contact us within 14 days of receiving your order.', 'aqualuxe' ) . '</p>';
        
        echo '<h4>' . esc_html__( 'Non-Living Products', 'aqualuxe' ) . '</h4>';
        echo '<ul>';
        echo '<li>' . esc_html__( 'Return within 30 days for a full refund or exchange', 'aqualuxe' ) . '</li>';
        echo '<li>' . esc_html__( 'Items must be unused and in original packaging', 'aqualuxe' ) . '</li>';
        echo '<li>' . esc_html__( 'Customer is responsible for return shipping costs', 'aqualuxe' ) . '</li>';
        echo '</ul>';
        
        echo '<h4>' . esc_html__( 'Living Products (Fish, Plants, etc.)', 'aqualuxe' ) . '</h4>';
        echo '<ul>';
        echo '<li>' . esc_html__( 'DOA (Dead on Arrival) guarantee for 24 hours', 'aqualuxe' ) . '</li>';
        echo '<li>' . esc_html__( 'Photographic evidence required within 2 hours of delivery', 'aqualuxe' ) . '</li>';
        echo '<li>' . esc_html__( 'Replacements or store credit offered for DOA items', 'aqualuxe' ) . '</li>';
        echo '</ul>';
    } else {
        echo wp_kses_post( $shipping_content );
    }
}

/**
 * Care tab content
 */
function aqualuxe_care_tab_content() {
    global $product;
    
    // Get product category
    $categories = get_the_terms( $product->get_id(), 'product_cat' );
    
    if ( ! $categories ) {
        return;
    }
    
    $category_slugs = wp_list_pluck( $categories, 'slug' );
    
    // Check for fish products
    if ( in_array( 'fish', $category_slugs ) ) {
        // Get fish care guide from product meta or use default
        $care_guide = get_post_meta( $product->get_id(), '_fish_care_guide', true );
        
        if ( empty( $care_guide ) ) {
            // Default fish care guide
            echo '<h3>' . esc_html__( 'Fish Care Guide', 'aqualuxe' ) . '</h3>';
            echo '<p>' . esc_html__( 'Proper care is essential for the health and longevity of your aquatic pets. Here are some general guidelines for caring for your new fish:', 'aqualuxe' ) . '</p>';
            
            echo '<h4>' . esc_html__( 'Water Parameters', 'aqualuxe' ) . '</h4>';
            echo '<ul>';
            echo '<li>' . esc_html__( 'Temperature: 72-78°F (22-26°C)', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'pH: 6.8-7.5', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Ammonia: 0 ppm', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Nitrite: 0 ppm', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Nitrate: <20 ppm', 'aqualuxe' ) . '</li>';
            echo '</ul>';
            
            echo '<h4>' . esc_html__( 'Feeding', 'aqualuxe' ) . '</h4>';
            echo '<ul>';
            echo '<li>' . esc_html__( 'Feed 1-2 times daily', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Only feed what can be consumed in 2-3 minutes', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Vary diet for optimal health', 'aqualuxe' ) . '</li>';
            echo '</ul>';
            
            echo '<h4>' . esc_html__( 'Maintenance', 'aqualuxe' ) . '</h4>';
            echo '<ul>';
            echo '<li>' . esc_html__( 'Perform 25% water changes weekly', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Clean filter media monthly (in tank water)', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Test water parameters weekly', 'aqualuxe' ) . '</li>';
            echo '</ul>';
        } else {
            echo wp_kses_post( $care_guide );
        }
    }
    
    // Check for plant products
    if ( in_array( 'plants', $category_slugs ) ) {
        // Get plant care guide from product meta or use default
        $care_guide = get_post_meta( $product->get_id(), '_plant_care_guide', true );
        
        if ( empty( $care_guide ) ) {
            // Default plant care guide
            echo '<h3>' . esc_html__( 'Plant Care Guide', 'aqualuxe' ) . '</h3>';
            echo '<p>' . esc_html__( 'Aquatic plants require proper care to thrive in your aquarium. Here are some general guidelines for caring for your new plants:', 'aqualuxe' ) . '</p>';
            
            echo '<h4>' . esc_html__( 'Lighting', 'aqualuxe' ) . '</h4>';
            echo '<ul>';
            echo '<li>' . esc_html__( 'Low light: 10-30 PAR', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Medium light: 30-50 PAR', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'High light: 50+ PAR', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Photoperiod: 8-10 hours daily', 'aqualuxe' ) . '</li>';
            echo '</ul>';
            
            echo '<h4>' . esc_html__( 'Nutrients', 'aqualuxe' ) . '</h4>';
            echo '<ul>';
            echo '<li>' . esc_html__( 'Use nutrient-rich substrate', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Consider liquid fertilizers', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'CO2 supplementation for high-tech setups', 'aqualuxe' ) . '</li>';
            echo '</ul>';
            
            echo '<h4>' . esc_html__( 'Maintenance', 'aqualuxe' ) . '</h4>';
            echo '<ul>';
            echo '<li>' . esc_html__( 'Trim regularly to promote growth', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Remove dead or yellowing leaves', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Monitor for algae growth', 'aqualuxe' ) . '</li>';
            echo '</ul>';
        } else {
            echo wp_kses_post( $care_guide );
        }
    }
    
    // Check for aquarium products
    if ( in_array( 'aquariums', $category_slugs ) ) {
        // Get aquarium setup guide from product meta or use default
        $setup_guide = get_post_meta( $product->get_id(), '_aquarium_setup_guide', true );
        
        if ( empty( $setup_guide ) ) {
            // Default aquarium setup guide
            echo '<h3>' . esc_html__( 'Aquarium Setup Guide', 'aqualuxe' ) . '</h3>';
            echo '<p>' . esc_html__( 'Setting up your aquarium properly is crucial for a successful and thriving aquatic environment. Here are some general guidelines for setting up your new aquarium:', 'aqualuxe' ) . '</p>';
            
            echo '<h4>' . esc_html__( 'Initial Setup', 'aqualuxe' ) . '</h4>';
            echo '<ol>';
            echo '<li>' . esc_html__( 'Rinse all gravel and decorations thoroughly', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Place the aquarium on a sturdy, level surface', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Add substrate (1-2 inches for fish-only, 2-3 inches for planted)', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Install equipment (filter, heater, lighting)', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Add decorations and plants', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Fill with dechlorinated water', 'aqualuxe' ) . '</li>';
            echo '</ol>';
            
            echo '<h4>' . esc_html__( 'Cycling Process', 'aqualuxe' ) . '</h4>';
            echo '<ol>';
            echo '<li>' . esc_html__( 'Start the filter and heater', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Add a source of ammonia (fish food or pure ammonia)', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Test water parameters regularly', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Wait for ammonia and nitrite to drop to 0 ppm', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Perform a water change before adding fish', 'aqualuxe' ) . '</li>';
            echo '</ol>';
            
            echo '<h4>' . esc_html__( 'Ongoing Maintenance', 'aqualuxe' ) . '</h4>';
            echo '<ul>';
            echo '<li>' . esc_html__( 'Regular water changes (25% weekly)', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Clean filter media monthly', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Test water parameters weekly', 'aqualuxe' ) . '</li>';
            echo '<li>' . esc_html__( 'Vacuum substrate during water changes', 'aqualuxe' ) . '</li>';
            echo '</ul>';
        } else {
            echo wp_kses_post( $setup_guide );
        }
    }
}

/**
 * Add product specifications table to single product page
 */
function aqualuxe_product_specifications() {
    global $product;
    
    // Get product specifications from product meta
    $specifications = get_post_meta( $product->get_id(), '_product_specifications', true );
    
    if ( ! empty( $specifications ) && is_array( $specifications ) ) {
        echo '<div class="product-specifications mt-8">';
        echo '<h3 class="text-xl font-medium mb-4">' . esc_html__( 'Product Specifications', 'aqualuxe' ) . '</h3>';
        echo '<div class="overflow-x-auto">';
        echo '<table class="w-full border-collapse">';
        echo '<tbody>';
        
        foreach ( $specifications as $spec ) {
            if ( ! empty( $spec['label'] ) && ! empty( $spec['value'] ) ) {
                echo '<tr class="border-b border-gray-200">';
                echo '<th class="py-3 px-4 text-left text-gray-600 w-1/3">' . esc_html( $spec['label'] ) . '</th>';
                echo '<td class="py-3 px-4 text-gray-900">' . wp_kses_post( $spec['value'] ) . '</td>';
                echo '</tr>';
            }
        }
        
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '</div>';
    }
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_product_specifications', 40 );

/**
 * Add product meta fields to admin
 */
function aqualuxe_product_meta_fields() {
    add_meta_box(
        'aqualuxe_product_specifications',
        __( 'Product Specifications', 'aqualuxe' ),
        'aqualuxe_product_specifications_callback',
        'product',
        'normal',
        'default'
    );
    
    add_meta_box(
        'aqualuxe_product_care_guides',
        __( 'Care Guides', 'aqualuxe' ),
        'aqualuxe_product_care_guides_callback',
        'product',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'aqualuxe_product_meta_fields' );

/**
 * Product specifications meta box callback
 */
function aqualuxe_product_specifications_callback( $post ) {
    wp_nonce_field( 'aqualuxe_product_specifications', 'aqualuxe_product_specifications_nonce' );
    
    $specifications = get_post_meta( $post->ID, '_product_specifications', true );
    
    if ( empty( $specifications ) || ! is_array( $specifications ) ) {
        $specifications = array(
            array( 'label' => '', 'value' => '' ),
        );
    }
    
    echo '<div id="product_specifications">';
    echo '<p>' . esc_html__( 'Add product specifications that will be displayed in a table on the product page.', 'aqualuxe' ) . '</p>';
    
    foreach ( $specifications as $index => $spec ) {
        echo '<div class="specification-row" style="display: flex; margin-bottom: 10px;">';
        echo '<input type="text" name="specification_label[]" value="' . esc_attr( $spec['label'] ) . '" placeholder="' . esc_attr__( 'Label', 'aqualuxe' ) . '" style="width: 30%; margin-right: 10px;" />';
        echo '<input type="text" name="specification_value[]" value="' . esc_attr( $spec['value'] ) . '" placeholder="' . esc_attr__( 'Value', 'aqualuxe' ) . '" style="width: 60%;" />';
        echo '<button type="button" class="button remove-specification" style="margin-left: 10px;">-</button>';
        echo '</div>';
    }
    
    echo '<button type="button" class="button add-specification">' . esc_html__( 'Add Specification', 'aqualuxe' ) . '</button>';
    echo '</div>';
    
    // Add JavaScript for adding/removing specification rows
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Add specification row
            $('.add-specification').on('click', function() {
                var row = '<div class="specification-row" style="display: flex; margin-bottom: 10px;">';
                row += '<input type="text" name="specification_label[]" value="" placeholder="<?php echo esc_attr__( 'Label', 'aqualuxe' ); ?>" style="width: 30%; margin-right: 10px;" />';
                row += '<input type="text" name="specification_value[]" value="" placeholder="<?php echo esc_attr__( 'Value', 'aqualuxe' ); ?>" style="width: 60%;" />';
                row += '<button type="button" class="button remove-specification" style="margin-left: 10px;">-</button>';
                row += '</div>';
                
                $('#product_specifications').append(row);
            });
            
            // Remove specification row
            $('#product_specifications').on('click', '.remove-specification', function() {
                $(this).closest('.specification-row').remove();
            });
        });
    </script>
    <?php
}

/**
 * Product care guides meta box callback
 */
function aqualuxe_product_care_guides_callback( $post ) {
    wp_nonce_field( 'aqualuxe_product_care_guides', 'aqualuxe_product_care_guides_nonce' );
    
    $fish_care_guide = get_post_meta( $post->ID, '_fish_care_guide', true );
    $plant_care_guide = get_post_meta( $post->ID, '_plant_care_guide', true );
    $aquarium_setup_guide = get_post_meta( $post->ID, '_aquarium_setup_guide', true );
    
    echo '<div id="product_care_guides">';
    
    // Fish care guide
    echo '<div class="care-guide-section">';
    echo '<h4>' . esc_html__( 'Fish Care Guide', 'aqualuxe' ) . '</h4>';
    echo '<p>' . esc_html__( 'This will be displayed in the Care Guide tab for fish products.', 'aqualuxe' ) . '</p>';
    wp_editor( $fish_care_guide, 'fish_care_guide', array(
        'textarea_name' => 'fish_care_guide',
        'textarea_rows' => 10,
        'media_buttons' => true,
    ) );
    echo '</div>';
    
    // Plant care guide
    echo '<div class="care-guide-section" style="margin-top: 20px;">';
    echo '<h4>' . esc_html__( 'Plant Care Guide', 'aqualuxe' ) . '</h4>';
    echo '<p>' . esc_html__( 'This will be displayed in the Care Guide tab for plant products.', 'aqualuxe' ) . '</p>';
    wp_editor( $plant_care_guide, 'plant_care_guide', array(
        'textarea_name' => 'plant_care_guide',
        'textarea_rows' => 10,
        'media_buttons' => true,
    ) );
    echo '</div>';
    
    // Aquarium setup guide
    echo '<div class="care-guide-section" style="margin-top: 20px;">';
    echo '<h4>' . esc_html__( 'Aquarium Setup Guide', 'aqualuxe' ) . '</h4>';
    echo '<p>' . esc_html__( 'This will be displayed in the Care Guide tab for aquarium products.', 'aqualuxe' ) . '</p>';
    wp_editor( $aquarium_setup_guide, 'aquarium_setup_guide', array(
        'textarea_name' => 'aquarium_setup_guide',
        'textarea_rows' => 10,
        'media_buttons' => true,
    ) );
    echo '</div>';
    
    echo '</div>';
}

/**
 * Save product meta fields
 */
function aqualuxe_save_product_meta_fields( $post_id ) {
    // Check if our nonce is set
    if ( ! isset( $_POST['aqualuxe_product_specifications_nonce'] ) ) {
        return;
    }
    
    // Verify that the nonce is valid
    if ( ! wp_verify_nonce( $_POST['aqualuxe_product_specifications_nonce'], 'aqualuxe_product_specifications' ) ) {
        return;
    }
    
    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    // Check the user's permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    // Save product specifications
    if ( isset( $_POST['specification_label'] ) && isset( $_POST['specification_value'] ) ) {
        $labels = $_POST['specification_label'];
        $values = $_POST['specification_value'];
        $specifications = array();
        
        for ( $i = 0; $i < count( $labels ); $i++ ) {
            if ( ! empty( $labels[ $i ] ) || ! empty( $values[ $i ] ) ) {
                $specifications[] = array(
                    'label' => sanitize_text_field( $labels[ $i ] ),
                    'value' => sanitize_text_field( $values[ $i ] ),
                );
            }
        }
        
        update_post_meta( $post_id, '_product_specifications', $specifications );
    }
    
    // Save care guides
    if ( isset( $_POST['fish_care_guide'] ) ) {
        update_post_meta( $post_id, '_fish_care_guide', wp_kses_post( $_POST['fish_care_guide'] ) );
    }
    
    if ( isset( $_POST['plant_care_guide'] ) ) {
        update_post_meta( $post_id, '_plant_care_guide', wp_kses_post( $_POST['plant_care_guide'] ) );
    }
    
    if ( isset( $_POST['aquarium_setup_guide'] ) ) {
        update_post_meta( $post_id, '_aquarium_setup_guide', wp_kses_post( $_POST['aquarium_setup_guide'] ) );
    }
}
add_action( 'save_post_product', 'aqualuxe_save_product_meta_fields' );

/**
 * Add custom product taxonomies
 */
function aqualuxe_register_product_taxonomies() {
    // Brand taxonomy
    register_taxonomy(
        'product_brand',
        'product',
        array(
            'label'        => __( 'Brands', 'aqualuxe' ),
            'rewrite'      => array( 'slug' => 'brand' ),
            'hierarchical' => true,
            'show_in_rest' => true,
        )
    );
    
    // Origin taxonomy (for fish and plants)
    register_taxonomy(
        'product_origin',
        'product',
        array(
            'label'        => __( 'Origin', 'aqualuxe' ),
            'rewrite'      => array( 'slug' => 'origin' ),
            'hierarchical' => false,
            'show_in_rest' => true,
        )
    );
    
    // Difficulty level taxonomy (for fish and plants)
    register_taxonomy(
        'product_difficulty',
        'product',
        array(
            'label'        => __( 'Difficulty Level', 'aqualuxe' ),
            'rewrite'      => array( 'slug' => 'difficulty' ),
            'hierarchical' => true,
            'show_in_rest' => true,
        )
    );
}
add_action( 'init', 'aqualuxe_register_product_taxonomies' );

/**
 * Add filter widgets for custom taxonomies
 */
function aqualuxe_add_product_filter_widgets() {
    // Brand filter widget
    register_widget( 'Aqualuxe_Brand_Filter_Widget' );
    
    // Origin filter widget
    register_widget( 'Aqualuxe_Origin_Filter_Widget' );
    
    // Difficulty filter widget
    register_widget( 'Aqualuxe_Difficulty_Filter_Widget' );
}
add_action( 'widgets_init', 'aqualuxe_add_product_filter_widgets' );

/**
 * Brand filter widget class
 */
class Aqualuxe_Brand_Filter_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'aqualuxe_brand_filter',
            __( 'AquaLuxe Brand Filter', 'aqualuxe' ),
            array( 'description' => __( 'Filter products by brand', 'aqualuxe' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }
        
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Brands', 'aqualuxe' );
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
        
        $brands = get_terms( array(
            'taxonomy'   => 'product_brand',
            'hide_empty' => true,
        ) );
        
        if ( empty( $brands ) || is_wp_error( $brands ) ) {
            return;
        }
        
        echo $args['before_widget'];
        
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        echo '<ul class="brand-filter">';
        
        foreach ( $brands as $brand ) {
            $active = '';
            
            if ( isset( $_GET['brand'] ) && $_GET['brand'] === $brand->slug ) {
                $active = ' class="active"';
            }
            
            $url = add_query_arg( 'brand', $brand->slug, get_pagenum_link( 1, false ) );
            
            echo '<li' . $active . '><a href="' . esc_url( $url ) . '">' . esc_html( $brand->name ) . ' <span class="count">(' . esc_html( $brand->count ) . ')</span></a></li>';
        }
        
        // Add "Clear" link if a brand is selected
        if ( isset( $_GET['brand'] ) ) {
            $clear_url = remove_query_arg( 'brand', get_pagenum_link( 1, false ) );
            echo '<li class="clear-filter"><a href="' . esc_url( $clear_url ) . '">' . esc_html__( 'Clear', 'aqualuxe' ) . '</a></li>';
        }
        
        echo '</ul>';
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Brands', 'aqualuxe' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        
        return $instance;
    }
}

/**
 * Origin filter widget class
 */
class Aqualuxe_Origin_Filter_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'aqualuxe_origin_filter',
            __( 'AquaLuxe Origin Filter', 'aqualuxe' ),
            array( 'description' => __( 'Filter products by origin', 'aqualuxe' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }
        
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Origin', 'aqualuxe' );
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
        
        $origins = get_terms( array(
            'taxonomy'   => 'product_origin',
            'hide_empty' => true,
        ) );
        
        if ( empty( $origins ) || is_wp_error( $origins ) ) {
            return;
        }
        
        echo $args['before_widget'];
        
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        echo '<ul class="origin-filter">';
        
        foreach ( $origins as $origin ) {
            $active = '';
            
            if ( isset( $_GET['origin'] ) && $_GET['origin'] === $origin->slug ) {
                $active = ' class="active"';
            }
            
            $url = add_query_arg( 'origin', $origin->slug, get_pagenum_link( 1, false ) );
            
            echo '<li' . $active . '><a href="' . esc_url( $url ) . '">' . esc_html( $origin->name ) . ' <span class="count">(' . esc_html( $origin->count ) . ')</span></a></li>';
        }
        
        // Add "Clear" link if an origin is selected
        if ( isset( $_GET['origin'] ) ) {
            $clear_url = remove_query_arg( 'origin', get_pagenum_link( 1, false ) );
            echo '<li class="clear-filter"><a href="' . esc_url( $clear_url ) . '">' . esc_html__( 'Clear', 'aqualuxe' ) . '</a></li>';
        }
        
        echo '</ul>';
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Origin', 'aqualuxe' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        
        return $instance;
    }
}

/**
 * Difficulty filter widget class
 */
class Aqualuxe_Difficulty_Filter_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'aqualuxe_difficulty_filter',
            __( 'AquaLuxe Difficulty Filter', 'aqualuxe' ),
            array( 'description' => __( 'Filter products by difficulty level', 'aqualuxe' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }
        
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Difficulty Level', 'aqualuxe' );
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
        
        $difficulties = get_terms( array(
            'taxonomy'   => 'product_difficulty',
            'hide_empty' => true,
        ) );
        
        if ( empty( $difficulties ) || is_wp_error( $difficulties ) ) {
            return;
        }
        
        echo $args['before_widget'];
        
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        echo '<ul class="difficulty-filter">';
        
        foreach ( $difficulties as $difficulty ) {
            $active = '';
            
            if ( isset( $_GET['difficulty'] ) && $_GET['difficulty'] === $difficulty->slug ) {
                $active = ' class="active"';
            }
            
            $url = add_query_arg( 'difficulty', $difficulty->slug, get_pagenum_link( 1, false ) );
            
            echo '<li' . $active . '><a href="' . esc_url( $url ) . '">' . esc_html( $difficulty->name ) . ' <span class="count">(' . esc_html( $difficulty->count ) . ')</span></a></li>';
        }
        
        // Add "Clear" link if a difficulty is selected
        if ( isset( $_GET['difficulty'] ) ) {
            $clear_url = remove_query_arg( 'difficulty', get_pagenum_link( 1, false ) );
            echo '<li class="clear-filter"><a href="' . esc_url( $clear_url ) . '">' . esc_html__( 'Clear', 'aqualuxe' ) . '</a></li>';
        }
        
        echo '</ul>';
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Difficulty Level', 'aqualuxe' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        
        return $instance;
    }
}

/**
 * Filter products by custom taxonomies
 */
function aqualuxe_filter_products_by_taxonomies( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_shop() || is_product_category() || is_product_tag() ) ) {
        $tax_query = array();
        
        // Filter by brand
        if ( isset( $_GET['brand'] ) && ! empty( $_GET['brand'] ) ) {
            $tax_query[] = array(
                'taxonomy' => 'product_brand',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( $_GET['brand'] ),
            );
        }
        
        // Filter by origin
        if ( isset( $_GET['origin'] ) && ! empty( $_GET['origin'] ) ) {
            $tax_query[] = array(
                'taxonomy' => 'product_origin',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( $_GET['origin'] ),
            );
        }
        
        // Filter by difficulty
        if ( isset( $_GET['difficulty'] ) && ! empty( $_GET['difficulty'] ) ) {
            $tax_query[] = array(
                'taxonomy' => 'product_difficulty',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( $_GET['difficulty'] ),
            );
        }
        
        if ( ! empty( $tax_query ) ) {
            $query->set( 'tax_query', array_merge( $query->get( 'tax_query' ), $tax_query ) );
        }
    }
}
add_action( 'pre_get_posts', 'aqualuxe_filter_products_by_taxonomies' );

/**
 * Add custom sorting options
 */
function aqualuxe_custom_woocommerce_catalog_orderby( $options ) {
    $options['rating_desc'] = __( 'Sort by rating: high to low', 'aqualuxe' );
    $options['rating_asc'] = __( 'Sort by rating: low to high', 'aqualuxe' );
    
    return $options;
}
add_filter( 'woocommerce_catalog_orderby', 'aqualuxe_custom_woocommerce_catalog_orderby' );

/**
 * Add custom sorting to query
 */
function aqualuxe_custom_woocommerce_get_catalog_ordering_args( $args ) {
    if ( isset( $_GET['orderby'] ) ) {
        if ( 'rating_desc' === $_GET['orderby'] ) {
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            $args['meta_key'] = '_wc_average_rating';
        } elseif ( 'rating_asc' === $_GET['orderby'] ) {
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
            $args['meta_key'] = '_wc_average_rating';
        }
    }
    
    return $args;
}
add_filter( 'woocommerce_get_catalog_ordering_args', 'aqualuxe_custom_woocommerce_get_catalog_ordering_args' );

/**
 * Add multi-currency support
 */
function aqualuxe_multi_currency_setup() {
    // Check if a currency plugin is active
    if ( class_exists( 'WCML_Multi_Currency' ) || class_exists( 'WC_Currency_Switcher' ) ) {
        return;
    }
    
    // Simple currency switcher implementation
    if ( ! session_id() ) {
        session_start();
    }
    
    // Set default currency
    if ( ! isset( $_SESSION['aqualuxe_currency'] ) ) {
        $_SESSION['aqualuxe_currency'] = get_woocommerce_currency();
    }
    
    // Handle currency switch
    if ( isset( $_GET['currency'] ) ) {
        $currency = sanitize_text_field( $_GET['currency'] );
        $allowed_currencies = aqualuxe_get_currencies();
        
        if ( array_key_exists( $currency, $allowed_currencies ) ) {
            $_SESSION['aqualuxe_currency'] = $currency;
        }
    }
}
add_action( 'init', 'aqualuxe_multi_currency_setup' );

/**
 * Get available currencies
 */
function aqualuxe_get_currencies() {
    return array(
        'USD' => array(
            'symbol'    => '$',
            'name'      => __( 'US Dollar', 'aqualuxe' ),
            'rate'      => 1,
        ),
        'EUR' => array(
            'symbol'    => '€',
            'name'      => __( 'Euro', 'aqualuxe' ),
            'rate'      => 0.85,
        ),
        'GBP' => array(
            'symbol'    => '£',
            'name'      => __( 'British Pound', 'aqualuxe' ),
            'rate'      => 0.75,
        ),
        'CAD' => array(
            'symbol'    => 'C$',
            'name'      => __( 'Canadian Dollar', 'aqualuxe' ),
            'rate'      => 1.25,
        ),
        'AUD' => array(
            'symbol'    => 'A$',
            'name'      => __( 'Australian Dollar', 'aqualuxe' ),
            'rate'      => 1.35,
        ),
    );
}

/**
 * Change WooCommerce currency
 */
function aqualuxe_change_woocommerce_currency( $currency ) {
    if ( isset( $_SESSION['aqualuxe_currency'] ) ) {
        return $_SESSION['aqualuxe_currency'];
    }
    
    return $currency;
}
add_filter( 'woocommerce_currency', 'aqualuxe_change_woocommerce_currency' );

/**
 * Adjust product prices based on currency
 */
function aqualuxe_adjust_product_price( $price, $product ) {
    if ( ! isset( $_SESSION['aqualuxe_currency'] ) ) {
        return $price;
    }
    
    $currencies = aqualuxe_get_currencies();
    $current_currency = $_SESSION['aqualuxe_currency'];
    $default_currency = get_option( 'woocommerce_currency' );
    
    if ( $current_currency === $default_currency ) {
        return $price;
    }
    
    if ( isset( $currencies[ $current_currency ] ) ) {
        $price = $price * $currencies[ $current_currency ]['rate'];
    }
    
    return $price;
}
add_filter( 'woocommerce_product_get_price', 'aqualuxe_adjust_product_price', 10, 2 );
add_filter( 'woocommerce_product_get_regular_price', 'aqualuxe_adjust_product_price', 10, 2 );
add_filter( 'woocommerce_product_get_sale_price', 'aqualuxe_adjust_product_price', 10, 2 );
add_filter( 'woocommerce_product_variation_get_price', 'aqualuxe_adjust_product_price', 10, 2 );
add_filter( 'woocommerce_product_variation_get_regular_price', 'aqualuxe_adjust_product_price', 10, 2 );
add_filter( 'woocommerce_product_variation_get_sale_price', 'aqualuxe_adjust_product_price', 10, 2 );

/**
 * Display currency switcher
 */
function aqualuxe_currency_switcher() {
    $currencies = aqualuxe_get_currencies();
    $current_currency = isset( $_SESSION['aqualuxe_currency'] ) ? $_SESSION['aqualuxe_currency'] : get_woocommerce_currency();
    
    echo '<div class="currency-switcher relative inline-block text-left">';
    echo '<div>';
    echo '<button type="button" class="currency-switcher-button inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" id="currency-menu-button" aria-expanded="false" aria-haspopup="true">';
    echo esc_html( $currencies[ $current_currency ]['symbol'] . ' ' . $current_currency );
    echo '<svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">';
    echo '<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />';
    echo '</svg>';
    echo '</button>';
    echo '</div>';
    
    echo '<div class="currency-switcher-dropdown hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="currency-menu-button" tabindex="-1">';
    echo '<div class="py-1" role="none">';
    
    foreach ( $currencies as $code => $currency ) {
        $active_class = $code === $current_currency ? 'bg-gray-100 text-gray-900' : 'text-gray-700';
        $url = add_query_arg( 'currency', $code );
        echo '<a href="' . esc_url( $url ) . '" class="' . esc_attr( $active_class ) . ' block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">' . esc_html( $currency['symbol'] . ' ' . $code . ' - ' . $currency['name'] ) . '</a>';
    }
    
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

/**
 * Add currency switcher to header
 */
function aqualuxe_add_currency_switcher_to_header() {
    aqualuxe_currency_switcher();
}
add_action( 'aqualuxe_header_tools', 'aqualuxe_add_currency_switcher_to_header', 20 );

/**
 * Add custom product data tabs
 */
function aqualuxe_product_data_tabs( $tabs ) {
    // Add specifications tab
    $tabs['specifications'] = array(
        'label'    => __( 'Specifications', 'aqualuxe' ),
        'target'   => 'product_specifications',
        'class'    => array(),
        'priority' => 25,
    );
    
    // Add care guides tab
    $tabs['care_guides'] = array(
        'label'    => __( 'Care Guides', 'aqualuxe' ),
        'target'   => 'product_care_guides',
        'class'    => array(),
        'priority' => 30,
    );
    
    return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'aqualuxe_product_data_tabs' );

/**
 * Add custom product data panels
 */
function aqualuxe_product_data_panels() {
    global $post;
    
    // Specifications panel
    echo '<div id="product_specifications" class="panel woocommerce_options_panel">';
    echo '<div class="options_group">';
    echo '<p class="form-field">';
    echo '<label>' . esc_html__( 'Product Specifications', 'aqualuxe' ) . '</label>';
    echo '<span class="description">' . esc_html__( 'Add product specifications in the "Product Specifications" meta box below.', 'aqualuxe' ) . '</span>';
    echo '</p>';
    echo '</div>';
    echo '</div>';
    
    // Care guides panel
    echo '<div id="product_care_guides" class="panel woocommerce_options_panel">';
    echo '<div class="options_group">';
    echo '<p class="form-field">';
    echo '<label>' . esc_html__( 'Care Guides', 'aqualuxe' ) . '</label>';
    echo '<span class="description">' . esc_html__( 'Add care guides in the "Care Guides" meta box below.', 'aqualuxe' ) . '</span>';
    echo '</p>';
    echo '</div>';
    echo '</div>';
}
add_action( 'woocommerce_product_data_panels', 'aqualuxe_product_data_panels' );

/**
 * Add custom fields to product general tab
 */
function aqualuxe_product_custom_fields() {
    global $post;
    
    // Scientific name field (for fish and plants)
    woocommerce_wp_text_input(
        array(
            'id'          => '_scientific_name',
            'label'       => __( 'Scientific Name', 'aqualuxe' ),
            'desc_tip'    => true,
            'description' => __( 'Enter the scientific name for this species.', 'aqualuxe' ),
        )
    );
    
    // Adult size field (for fish)
    woocommerce_wp_text_input(
        array(
            'id'          => '_adult_size',
            'label'       => __( 'Adult Size', 'aqualuxe' ),
            'desc_tip'    => true,
            'description' => __( 'Enter the adult size (e.g., "3 inches").', 'aqualuxe' ),
        )
    );
    
    // Tank size field (for fish)
    woocommerce_wp_text_input(
        array(
            'id'          => '_tank_size',
            'label'       => __( 'Minimum Tank Size', 'aqualuxe' ),
            'desc_tip'    => true,
            'description' => __( 'Enter the minimum tank size (e.g., "20 gallons").', 'aqualuxe' ),
        )
    );
    
    // Temperature range field (for fish and plants)
    woocommerce_wp_text_input(
        array(
            'id'          => '_temperature_range',
            'label'       => __( 'Temperature Range', 'aqualuxe' ),
            'desc_tip'    => true,
            'description' => __( 'Enter the temperature range (e.g., "72-78°F").', 'aqualuxe' ),
        )
    );
    
    // pH range field (for fish and plants)
    woocommerce_wp_text_input(
        array(
            'id'          => '_ph_range',
            'label'       => __( 'pH Range', 'aqualuxe' ),
            'desc_tip'    => true,
            'description' => __( 'Enter the pH range (e.g., "6.5-7.5").', 'aqualuxe' ),
        )
    );
}
add_action( 'woocommerce_product_options_general_product_data', 'aqualuxe_product_custom_fields' );

/**
 * Save custom fields
 */
function aqualuxe_save_product_custom_fields( $post_id ) {
    // Scientific name
    if ( isset( $_POST['_scientific_name'] ) ) {
        update_post_meta( $post_id, '_scientific_name', sanitize_text_field( $_POST['_scientific_name'] ) );
    }
    
    // Adult size
    if ( isset( $_POST['_adult_size'] ) ) {
        update_post_meta( $post_id, '_adult_size', sanitize_text_field( $_POST['_adult_size'] ) );
    }
    
    // Tank size
    if ( isset( $_POST['_tank_size'] ) ) {
        update_post_meta( $post_id, '_tank_size', sanitize_text_field( $_POST['_tank_size'] ) );
    }
    
    // Temperature range
    if ( isset( $_POST['_temperature_range'] ) ) {
        update_post_meta( $post_id, '_temperature_range', sanitize_text_field( $_POST['_temperature_range'] ) );
    }
    
    // pH range
    if ( isset( $_POST['_ph_range'] ) ) {
        update_post_meta( $post_id, '_ph_range', sanitize_text_field( $_POST['_ph_range'] ) );
    }
}
add_action( 'woocommerce_process_product_meta', 'aqualuxe_save_product_custom_fields' );

/**
 * Display custom fields on product page
 */
function aqualuxe_display_product_custom_fields() {
    global $product;
    
    $scientific_name = get_post_meta( $product->get_id(), '_scientific_name', true );
    $adult_size = get_post_meta( $product->get_id(), '_adult_size', true );
    $tank_size = get_post_meta( $product->get_id(), '_tank_size', true );
    $temperature_range = get_post_meta( $product->get_id(), '_temperature_range', true );
    $ph_range = get_post_meta( $product->get_id(), '_ph_range', true );
    
    if ( $scientific_name || $adult_size || $tank_size || $temperature_range || $ph_range ) {
        echo '<div class="product-custom-fields mt-4 mb-6">';
        
        if ( $scientific_name ) {
            echo '<div class="custom-field scientific-name mb-2">';
            echo '<span class="label font-medium text-gray-700">' . esc_html__( 'Scientific Name:', 'aqualuxe' ) . '</span> ';
            echo '<span class="value italic">' . esc_html( $scientific_name ) . '</span>';
            echo '</div>';
        }
        
        if ( $adult_size ) {
            echo '<div class="custom-field adult-size mb-2">';
            echo '<span class="label font-medium text-gray-700">' . esc_html__( 'Adult Size:', 'aqualuxe' ) . '</span> ';
            echo '<span class="value">' . esc_html( $adult_size ) . '</span>';
            echo '</div>';
        }
        
        if ( $tank_size ) {
            echo '<div class="custom-field tank-size mb-2">';
            echo '<span class="label font-medium text-gray-700">' . esc_html__( 'Minimum Tank Size:', 'aqualuxe' ) . '</span> ';
            echo '<span class="value">' . esc_html( $tank_size ) . '</span>';
            echo '</div>';
        }
        
        if ( $temperature_range ) {
            echo '<div class="custom-field temperature-range mb-2">';
            echo '<span class="label font-medium text-gray-700">' . esc_html__( 'Temperature Range:', 'aqualuxe' ) . '</span> ';
            echo '<span class="value">' . esc_html( $temperature_range ) . '</span>';
            echo '</div>';
        }
        
        if ( $ph_range ) {
            echo '<div class="custom-field ph-range mb-2">';
            echo '<span class="label font-medium text-gray-700">' . esc_html__( 'pH Range:', 'aqualuxe' ) . '</span> ';
            echo '<span class="value">' . esc_html( $ph_range ) . '</span>';
            echo '</div>';
        }
        
        echo '</div>';
    }
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_display_product_custom_fields', 25 );