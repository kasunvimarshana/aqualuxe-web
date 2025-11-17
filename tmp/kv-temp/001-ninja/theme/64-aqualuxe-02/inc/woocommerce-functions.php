<?php
/**
 * AquaLuxe WooCommerce Functions
 *
 * Functions for WooCommerce integration.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Check if WooCommerce is active
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists( 'WooCommerce' );
}

/**
 * Get option with default
 *
 * @param string $option Option name
 * @param mixed  $default Default value
 * @return mixed
 */
function aqualuxe_get_option( $option, $default = false ) {
    $options = get_option( 'aqualuxe_options' );
    return isset( $options[ $option ] ) ? $options[ $option ] : $default;
}

/**
 * Get icon SVG
 *
 * @param string $icon Icon name
 * @return string
 */
function aqualuxe_get_icon( $icon ) {
    $icons = array(
        'cart' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>',
        'search' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
        'user' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
        'heart' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>',
        'eye' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>',
        'close' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>',
        'filter' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>',
    );

    return isset( $icons[ $icon ] ) ? $icons[ $icon ] : '';
}

/**
 * Display header cart
 */
function aqualuxe_header_cart() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }

    $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    ?>
    <div class="header-cart mini-cart">
        <button class="header-cart-button mini-cart-button" aria-expanded="false">
            <?php echo aqualuxe_get_icon( 'cart' ); ?>
            <span class="header-cart-count mini-cart-count"><?php echo esc_html( $cart_count ); ?></span>
            <span class="screen-reader-text"><?php esc_html_e( 'View your shopping cart', 'aqualuxe' ); ?></span>
        </button>
        <div class="header-cart-dropdown mini-cart-dropdown">
            <div class="widget_shopping_cart_content">
                <?php woocommerce_mini_cart(); ?>
            </div>
        </div>
    </div>
    <div class="mini-cart-overlay"></div>
    <?php
}

/**
 * Display off-canvas mini cart
 */
function aqualuxe_off_canvas_mini_cart() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    ?>
    <div class="off-canvas-mini-cart">
        <div class="off-canvas-mini-cart-header">
            <h3><?php esc_html_e( 'Your Cart', 'aqualuxe' ); ?></h3>
            <button class="off-canvas-mini-cart-close" aria-label="<?php esc_attr_e( 'Close cart', 'aqualuxe' ); ?>">
                <?php echo aqualuxe_get_icon( 'close' ); ?>
            </button>
        </div>
        <div class="off-canvas-mini-cart-content">
            <div class="off-canvas-mini-cart-items">
                <div class="widget_shopping_cart_content">
                    <?php woocommerce_mini_cart(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Get wishlist
 *
 * @return array
 */
function aqualuxe_get_wishlist() {
    // Get wishlist from cookie
    $wishlist = [];
    
    if ( isset( $_COOKIE['aqualuxe_wishlist'] ) ) {
        $wishlist = explode( ',', sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ) );
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
            aqualuxe_save_wishlist( $wishlist );
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
function aqualuxe_save_wishlist( $wishlist ) {
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
 * Display wishlist button
 */
function aqualuxe_wishlist_button() {
    if ( ! aqualuxe_is_woocommerce_active() || ! aqualuxe_get_option( 'enable_product_wishlist', true ) ) {
        return;
    }

    global $product;
    
    if ( ! $product ) {
        return;
    }
    
    // Get wishlist
    $wishlist = aqualuxe_get_wishlist();
    
    // Check if product is in wishlist
    $in_wishlist = in_array( $product->get_id(), $wishlist );
    
    // Output button
    echo '<button class="wishlist-button' . ( $in_wishlist ? ' in-wishlist' : '' ) . '" data-product-id="' . esc_attr( $product->get_id() ) . '">';
    echo aqualuxe_get_icon( 'heart' );
    echo '<span>' . ( $in_wishlist ? esc_html__( 'Remove from Wishlist', 'aqualuxe' ) : esc_html__( 'Add to Wishlist', 'aqualuxe' ) ) . '</span>';
    echo '</button>';
}

/**
 * Display quick view button
 */
function aqualuxe_quick_view_button() {
    if ( ! aqualuxe_is_woocommerce_active() || ! aqualuxe_get_option( 'enable_product_quick_view', true ) ) {
        return;
    }

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
 * Display quick view modal
 */
function aqualuxe_quick_view_modal() {
    if ( ! aqualuxe_is_woocommerce_active() || ! aqualuxe_get_option( 'enable_product_quick_view', true ) ) {
        return;
    }
    ?>
    <div id="quick-view-modal" class="quick-view-modal" aria-hidden="true">
        <div class="quick-view-modal-backdrop"></div>
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
 * Display shop filters
 */
function aqualuxe_shop_filters() {
    if ( ! aqualuxe_is_woocommerce_active() || ! aqualuxe_get_option( 'enable_shop_filters', true ) ) {
        return;
    }

    // Check if we have active filters
    $has_filters = is_active_sidebar( 'shop-sidebar' );
    
    if ( ! $has_filters ) {
        return;
    }
    
    // Get current filters
    $current_filters = [];
    
    if ( isset( $_GET['filter_price'] ) ) {
        $current_filters['price'] = sanitize_text_field( wp_unslash( $_GET['filter_price'] ) );
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
        
        <div class="shop-filters-content hidden">
            <?php dynamic_sidebar( 'shop-sidebar' ); ?>
        </div>
    </div>
    <?php
}

/**
 * Display sticky add to cart
 */
function aqualuxe_sticky_add_to_cart() {
    if ( ! aqualuxe_is_woocommerce_active() || ! aqualuxe_get_option( 'enable_product_sticky_add_to_cart', true ) ) {
        return;
    }

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

/**
 * Add WooCommerce hooks
 */
function aqualuxe_add_woocommerce_hooks() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }

    // Add theme supports
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // Product actions
    add_action( 'aqualuxe_product_actions', 'aqualuxe_quick_view_button', 10 );
    add_action( 'aqualuxe_product_actions', 'aqualuxe_wishlist_button', 20 );

    // Shop hooks
    add_action( 'aqualuxe_shop_top_bar', 'aqualuxe_shop_filters', 10 );
    add_action( 'aqualuxe_shop_sidebar', 'woocommerce_get_sidebar', 10 );

    // Product hooks
    add_action( 'aqualuxe_product_sidebar', 'dynamic_sidebar', 10, 1 );
    add_action( 'aqualuxe_after_product', 'aqualuxe_sticky_add_to_cart', 10 );

    // Quick view
    add_action( 'wp_footer', 'aqualuxe_quick_view_modal' );

    // Mini cart
    add_action( 'aqualuxe_header_icons', 'aqualuxe_header_cart', 10 );
    add_action( 'wp_footer', 'aqualuxe_off_canvas_mini_cart' );

    // AJAX handlers
    add_action( 'wp_ajax_aqualuxe_quick_view', 'aqualuxe_ajax_quick_view' );
    add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_ajax_quick_view' );
    add_action( 'wp_ajax_aqualuxe_wishlist', 'aqualuxe_ajax_wishlist' );
    add_action( 'wp_ajax_nopriv_aqualuxe_wishlist', 'aqualuxe_ajax_wishlist' );
    add_action( 'wp_ajax_aqualuxe_ajax_add_to_cart', 'aqualuxe_ajax_add_to_cart' );
    add_action( 'wp_ajax_nopriv_aqualuxe_ajax_add_to_cart', 'aqualuxe_ajax_add_to_cart' );
}
add_action( 'after_setup_theme', 'aqualuxe_add_woocommerce_hooks' );

/**
 * AJAX quick view
 */
function aqualuxe_ajax_quick_view() {
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
 * AJAX wishlist
 */
function aqualuxe_ajax_wishlist() {
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
    $wishlist = aqualuxe_get_wishlist();
    
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
    aqualuxe_save_wishlist( $wishlist );
    
    wp_send_json_success( [
        'message' => $message,
        'in_wishlist' => ! $in_wishlist,
        'button_text' => $button_text,
        'wishlist_count' => count( $wishlist ),
    ] );
}

/**
 * AJAX add to cart
 */
function aqualuxe_ajax_add_to_cart() {
    // Check nonce
    if ( ! check_ajax_referer( 'aqualuxe-woocommerce', 'nonce', false ) ) {
        wp_send_json_error( [ 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ] );
    }
    
    $product_id = isset( $_POST['add-to-cart'] ) ? absint( $_POST['add-to-cart'] ) : 0;
    $quantity = isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : 1;
    $variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
    $variations = [];
    
    // Get variations if any
    foreach ( $_POST as $key => $value ) {
        if ( strpos( $key, 'attribute_' ) === 0 ) {
            $variations[ sanitize_title( wp_unslash( $key ) ) ] = wp_unslash( $value );
        }
    }
    
    // Add to cart
    $cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations );
    
    if ( $cart_item_key ) {
        // Get mini cart fragments
        $fragments = [];
        
        ob_start();
        woocommerce_mini_cart();
        $fragments['div.widget_shopping_cart_content'] = ob_get_clean();
        
        // Get cart count
        $cart_count = WC()->cart->get_cart_contents_count();
        $fragments['.header-cart-count'] = '<span class="header-cart-count">' . esc_html( $cart_count ) . '</span>';
        
        wp_send_json_success( [
            'message' => esc_html__( 'Product added to cart.', 'aqualuxe' ),
            'fragments' => $fragments,
            'cart_hash' => WC()->cart->get_cart_hash(),
        ] );
    } else {
        wp_send_json_error( [
            'message' => esc_html__( 'Error adding product to cart.', 'aqualuxe' ),
        ] );
    }
}

/**
 * Add WooCommerce endpoints
 */
function aqualuxe_add_woocommerce_endpoints() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }

    // Add wishlist endpoint
    if ( aqualuxe_get_option( 'enable_product_wishlist', true ) ) {
        add_rewrite_endpoint( 'wishlist', EP_ROOT | EP_PAGES );
    }
}
add_action( 'init', 'aqualuxe_add_woocommerce_endpoints' );

/**
 * Add wishlist endpoint content
 */
function aqualuxe_wishlist_endpoint_content() {
    if ( ! aqualuxe_is_woocommerce_active() || ! aqualuxe_get_option( 'enable_product_wishlist', true ) ) {
        return;
    }

    // Get wishlist
    $wishlist = aqualuxe_get_wishlist();
    
    // Get wishlist products
    $products = [];
    
    if ( ! empty( $wishlist ) ) {
        $products = wc_get_products( [
            'include' => $wishlist,
            'limit' => -1,
        ] );
    }
    
    // Output wishlist
    wc_get_template(
        'myaccount/wishlist.php',
        [
            'wishlist' => $wishlist,
            'products' => $products,
        ],
        '',
        AQUALUXE_DIR . 'woocommerce/'
    );
}
add_action( 'woocommerce_account_wishlist_endpoint', 'aqualuxe_wishlist_endpoint_content' );

/**
 * Add wishlist to account menu
 *
 * @param array $items Menu items
 * @return array
 */
function aqualuxe_add_wishlist_to_account_menu( $items ) {
    if ( ! aqualuxe_is_woocommerce_active() || ! aqualuxe_get_option( 'enable_product_wishlist', true ) ) {
        return $items;
    }

    // Add wishlist after dashboard
    $new_items = [];
    
    foreach ( $items as $key => $item ) {
        $new_items[ $key ] = $item;
        
        if ( $key === 'dashboard' ) {
            $new_items['wishlist'] = esc_html__( 'Wishlist', 'aqualuxe' );
        }
    }
    
    return $new_items;
}
add_filter( 'woocommerce_account_menu_items', 'aqualuxe_add_wishlist_to_account_menu' );

/**
 * Register WooCommerce scripts
 */
function aqualuxe_register_woocommerce_scripts() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }

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
            'ajaxAddToCart' => aqualuxe_get_option( 'enable_ajax_add_to_cart', true ),
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
add_action( 'wp_enqueue_scripts', 'aqualuxe_register_woocommerce_scripts' );