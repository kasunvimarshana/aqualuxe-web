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
    add_theme_support(
        'woocommerce',
        array(
            'thumbnail_image_width' => 300,
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
    // Get the theme version
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // Check if we have a mix-manifest.json file
    $mix_manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $mix_manifest = file_exists( $mix_manifest_path ) ? json_decode( file_get_contents( $mix_manifest_path ), true ) : null;
    
    // WooCommerce CSS file
    if ( $mix_manifest && isset( $mix_manifest['/css/woocommerce.css'] ) ) {
        wp_enqueue_style( 'aqualuxe-woocommerce-style', get_template_directory_uri() . '/assets/dist' . $mix_manifest['/css/woocommerce.css'], array(), null );
    } else {
        wp_enqueue_style( 'aqualuxe-woocommerce-style', get_template_directory_uri() . '/assets/dist/css/woocommerce.css', array(), $theme_version );
    }
    
    // WooCommerce JavaScript file
    if ( $mix_manifest && isset( $mix_manifest['/js/woocommerce.js'] ) ) {
        wp_enqueue_script( 'aqualuxe-woocommerce-script', get_template_directory_uri() . '/assets/dist' . $mix_manifest['/js/woocommerce.js'], array( 'jquery' ), null, true );
    } else {
        wp_enqueue_script( 'aqualuxe-woocommerce-script', get_template_directory_uri() . '/assets/dist/js/woocommerce.js', array( 'jquery' ), $theme_version, true );
    }

    // Localize script
    wp_localize_script(
        'aqualuxe-woocommerce-script',
        'aqualuxeWooCommerce',
        array(
            'ajaxUrl'           => admin_url( 'admin-ajax.php' ),
            'nonce'            => wp_create_nonce( 'aqualuxe-woocommerce-nonce' ),
            'addToCartText'    => esc_html__( 'Add to cart', 'aqualuxe' ),
            'addingToCartText' => esc_html__( 'Adding...', 'aqualuxe' ),
            'addedToCartText'  => esc_html__( 'Added!', 'aqualuxe' ),
            'viewCartText'     => esc_html__( 'View cart', 'aqualuxe' ),
            'errorMessage'     => esc_html__( 'Something went wrong. Please try again.', 'aqualuxe' ),
            'i18n'             => array(
                'quickView'      => esc_html__( 'Quick View', 'aqualuxe' ),
                'addToWishlist'  => esc_html__( 'Add to Wishlist', 'aqualuxe' ),
                'removeFromWishlist' => esc_html__( 'Remove from Wishlist', 'aqualuxe' ),
                'addedToWishlist' => esc_html__( 'Added to Wishlist', 'aqualuxe' ),
                'removedFromWishlist' => esc_html__( 'Removed from Wishlist', 'aqualuxe' ),
                'viewWishlist'   => esc_html__( 'View Wishlist', 'aqualuxe' ),
                'compare'        => esc_html__( 'Compare', 'aqualuxe' ),
                'addToCompare'   => esc_html__( 'Add to Compare', 'aqualuxe' ),
                'removeFromCompare' => esc_html__( 'Remove from Compare', 'aqualuxe' ),
                'addedToCompare' => esc_html__( 'Added to Compare', 'aqualuxe' ),
                'removedFromCompare' => esc_html__( 'Removed from Compare', 'aqualuxe' ),
                'viewCompare'    => esc_html__( 'View Compare', 'aqualuxe' ),
            ),
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
        'posts_per_page' => 3,
        'columns'        => 3,
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
 * Add custom WooCommerce wrapper.
 */
function aqualuxe_woocommerce_wrapper_before() {
    ?>
    <main id="primary" class="site-main">
        <div class="container mx-auto px-4 py-8">
            <?php if ( aqualuxe_has_sidebar() && ( is_shop() || is_product_category() || is_product_tag() ) ) : ?>
                <div class="flex flex-wrap lg:flex-nowrap">
                    <?php if ( 'left-sidebar' === aqualuxe_get_page_layout() ) : ?>
                        <div class="w-full lg:w-1/4 lg:pr-8">
                            <?php get_sidebar( 'shop' ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="w-full <?php echo aqualuxe_has_sidebar() ? 'lg:w-3/4' : ''; ?>">
            <?php endif; ?>
    <?php
}
add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before' );

/**
 * Add custom WooCommerce wrapper end.
 */
function aqualuxe_woocommerce_wrapper_after() {
    ?>
            <?php if ( aqualuxe_has_sidebar() && ( is_shop() || is_product_category() || is_product_tag() ) ) : ?>
                    </div>
                    
                    <?php if ( 'right-sidebar' === aqualuxe_get_page_layout() ) : ?>
                        <div class="w-full lg:w-1/4 lg:pl-8 mt-8 lg:mt-0">
                            <?php get_sidebar( 'shop' ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
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
    <div class="site-header-cart">
        <div class="cart-contents <?php echo esc_attr( $class ); ?>">
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
                <span class="cart-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </span>
                <span class="cart-count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></span>
            </a>
        </div>
        <div class="cart-dropdown">
            <?php the_widget( 'WC_Widget_Cart', array( 'title' => '' ) ); ?>
        </div>
    </div>
    <?php
}

/**
 * Update cart count via AJAX.
 */
function aqualuxe_woocommerce_cart_fragments( $fragments ) {
    ob_start();
    ?>
    <span class="cart-count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></span>
    <?php
    $fragments['.cart-count'] = ob_get_clean();
    
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_fragments' );

/**
 * Add quick view button.
 */
function aqualuxe_woocommerce_quick_view_button() {
    global $product;
    
    if ( get_theme_mod( 'aqualuxe_quick_view', true ) ) {
        echo '<a href="#" class="quick-view-button button" data-product-id="' . esc_attr( $product->get_id() ) . '">' . esc_html__( 'Quick View', 'aqualuxe' ) . '</a>';
    }
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_quick_view_button', 15 );

/**
 * Add wishlist button.
 */
function aqualuxe_woocommerce_wishlist_button() {
    global $product;
    
    if ( get_theme_mod( 'aqualuxe_wishlist', true ) ) {
        echo '<a href="#" class="wishlist-button button" data-product-id="' . esc_attr( $product->get_id() ) . '">' . esc_html__( 'Add to Wishlist', 'aqualuxe' ) . '</a>';
    }
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_wishlist_button', 20 );

/**
 * Add quick view modal.
 */
function aqualuxe_woocommerce_quick_view_modal() {
    ?>
    <div id="quick-view-modal" class="quick-view-modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="quick-view-modal-title">
        <div class="quick-view-modal-content bg-white dark:bg-gray-800 w-full max-w-4xl mx-auto p-6 rounded-lg shadow-xl">
            <div class="quick-view-modal-header flex justify-between items-center mb-4">
                <h2 id="quick-view-modal-title" class="text-xl font-bold"><?php esc_html_e( 'Quick View', 'aqualuxe' ); ?></h2>
                <button id="quick-view-modal-close" class="quick-view-modal-close" aria-label="<?php esc_attr_e( 'Close quick view', 'aqualuxe' ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="quick-view-modal-body">
                <div class="quick-view-loading flex items-center justify-center py-12">
                    <svg class="animate-spin h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <div class="quick-view-content"></div>
            </div>
        </div>
    </div>
    <?php
}
add_action( 'wp_footer', 'aqualuxe_woocommerce_quick_view_modal' );

/**
 * AJAX quick view.
 */
function aqualuxe_woocommerce_ajax_quick_view() {
    if ( ! isset( $_POST['product_id'] ) || ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Invalid request.', 'aqualuxe' ) ) );
    }

    $product_id = absint( $_POST['product_id'] );
    $product = wc_get_product( $product_id );

    if ( ! $product ) {
        wp_send_json_error( array( 'message' => __( 'Product not found.', 'aqualuxe' ) ) );
    }

    ob_start();
    ?>
    <div class="quick-view-product">
        <div class="flex flex-col md:flex-row">
            <div class="quick-view-images w-full md:w-1/2 md:pr-6">
                <?php
                $image_id = $product->get_image_id();
                $gallery_image_ids = $product->get_gallery_image_ids();
                
                if ( $image_id ) {
                    echo wp_get_attachment_image( $image_id, 'medium_large', false, array( 'class' => 'quick-view-main-image w-full h-auto rounded-lg' ) );
                } else {
                    echo '<img src="' . esc_url( wc_placeholder_img_src() ) . '" alt="' . esc_attr__( 'Placeholder', 'aqualuxe' ) . '" class="quick-view-main-image w-full h-auto rounded-lg" />';
                }
                
                if ( ! empty( $gallery_image_ids ) ) {
                    echo '<div class="quick-view-gallery grid grid-cols-4 gap-2 mt-2">';
                    foreach ( $gallery_image_ids as $gallery_image_id ) {
                        echo wp_get_attachment_image( $gallery_image_id, 'thumbnail', false, array( 'class' => 'quick-view-gallery-image w-full h-auto rounded cursor-pointer' ) );
                    }
                    echo '</div>';
                }
                ?>
            </div>
            
            <div class="quick-view-summary w-full md:w-1/2 mt-6 md:mt-0">
                <h2 class="quick-view-title text-2xl font-bold mb-2"><?php echo esc_html( $product->get_name() ); ?></h2>
                
                <div class="quick-view-price text-xl font-bold mb-4">
                    <?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
                
                <div class="quick-view-rating mb-4">
                    <?php
                    if ( $product->get_average_rating() > 0 ) {
                        echo wc_get_rating_html( $product->get_average_rating() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo '<span class="rating-count text-sm text-gray-600 dark:text-gray-400 ml-2">';
                        echo esc_html( $product->get_review_count() ) . ' ' . esc_html__( 'reviews', 'aqualuxe' );
                        echo '</span>';
                    }
                    ?>
                </div>
                
                <div class="quick-view-description mb-6">
                    <?php echo wp_kses_post( $product->get_short_description() ); ?>
                </div>
                
                <div class="quick-view-add-to-cart">
                    <?php
                    if ( $product->is_in_stock() ) {
                        if ( $product->is_type( 'simple' ) ) {
                            echo '<form class="cart" action="' . esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ) . '" method="post" enctype="multipart/form-data">';
                            
                            if ( $product->is_purchasable() ) {
                                echo woocommerce_quantity_input(
                                    array(
                                        'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
                                        'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
                                        'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // phpcs:ignore WordPress.Security.NonceVerification.Missing
                                    ),
                                    $product,
                                    false
                                );
                                
                                echo '<button type="submit" name="add-to-cart" value="' . esc_attr( $product->get_id() ) . '" class="single_add_to_cart_button button alt">' . esc_html( $product->single_add_to_cart_text() ) . '</button>';
                            }
                            
                            echo '</form>';
                        } else {
                            echo '<a href="' . esc_url( $product->get_permalink() ) . '" class="button">' . esc_html__( 'View Product', 'aqualuxe' ) . '</a>';
                        }
                    } else {
                        echo '<p class="stock out-of-stock">' . esc_html__( 'Out of stock', 'aqualuxe' ) . '</p>';
                    }
                    ?>
                </div>
                
                <div class="quick-view-meta mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
                        <span class="sku_wrapper block mb-2"><?php esc_html_e( 'SKU:', 'aqualuxe' ); ?> <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? esc_html( $sku ) : esc_html__( 'N/A', 'aqualuxe' ); ?></span></span>
                    <?php endif; ?>
                    
                    <?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in block mb-2">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'aqualuxe' ) . ' ', '</span>' ); ?>
                    
                    <?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as block mb-2">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'aqualuxe' ) . ' ', '</span>' ); ?>
                </div>
                
                <div class="quick-view-actions mt-6">
                    <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="button view-product-button"><?php esc_html_e( 'View Full Details', 'aqualuxe' ); ?></a>
                </div>
            </div>
        </div>
    </div>
    <?php
    $output = ob_get_clean();
    
    wp_send_json_success( array( 'content' => $output ) );
}
add_action( 'wp_ajax_aqualuxe_quick_view', 'aqualuxe_woocommerce_ajax_quick_view' );
add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_woocommerce_ajax_quick_view' );

/**
 * AJAX add to cart.
 */
function aqualuxe_woocommerce_ajax_add_to_cart() {
    if ( ! isset( $_POST['product_id'] ) || ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Invalid request.', 'aqualuxe' ) ) );
    }

    $product_id = absint( $_POST['product_id'] );
    $quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;
    $variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
    $variation = isset( $_POST['variation'] ) ? (array) $_POST['variation'] : array();
    
    $cart_item_data = array();
    
    $product_status = get_post_status( $product_id );
    
    if ( 'publish' !== $product_status ) {
        wp_send_json_error( array( 'message' => __( 'Product is not available.', 'aqualuxe' ) ) );
    }
    
    $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variation, $cart_item_data );
    
    if ( ! $passed_validation ) {
        wp_send_json_error( array( 'message' => __( 'Product validation failed.', 'aqualuxe' ) ) );
    }
    
    $added = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation, $cart_item_data );
    
    if ( ! $added ) {
        wp_send_json_error( array( 'message' => __( 'Failed to add product to cart.', 'aqualuxe' ) ) );
    }
    
    $data = array(
        'message'     => __( 'Product added to cart.', 'aqualuxe' ),
        'cart_count'  => WC()->cart->get_cart_contents_count(),
        'cart_total'  => WC()->cart->get_cart_total(),
        'cart_url'    => wc_get_cart_url(),
        'checkout_url' => wc_get_checkout_url(),
    );
    
    wp_send_json_success( $data );
}
add_action( 'wp_ajax_aqualuxe_add_to_cart', 'aqualuxe_woocommerce_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_aqualuxe_add_to_cart', 'aqualuxe_woocommerce_ajax_add_to_cart' );

/**
 * AJAX wishlist.
 */
function aqualuxe_woocommerce_ajax_wishlist() {
    if ( ! isset( $_POST['product_id'] ) || ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Invalid request.', 'aqualuxe' ) ) );
    }

    $product_id = absint( $_POST['product_id'] );
    $action = isset( $_POST['action_type'] ) ? sanitize_text_field( wp_unslash( $_POST['action_type'] ) ) : 'add';
    
    // Get current wishlist
    $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : array();
    
    if ( ! is_array( $wishlist ) ) {
        $wishlist = array();
    }
    
    if ( 'add' === $action ) {
        if ( ! in_array( $product_id, $wishlist, true ) ) {
            $wishlist[] = $product_id;
            $message = __( 'Product added to wishlist.', 'aqualuxe' );
        } else {
            $message = __( 'Product already in wishlist.', 'aqualuxe' );
        }
    } else {
        $key = array_search( $product_id, $wishlist, true );
        if ( false !== $key ) {
            unset( $wishlist[ $key ] );
            $wishlist = array_values( $wishlist );
            $message = __( 'Product removed from wishlist.', 'aqualuxe' );
        } else {
            $message = __( 'Product not in wishlist.', 'aqualuxe' );
        }
    }
    
    // Save wishlist
    setcookie( 'aqualuxe_wishlist', wp_json_encode( $wishlist ), time() + 30 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
    
    $data = array(
        'message'     => $message,
        'wishlist'    => $wishlist,
        'count'       => count( $wishlist ),
    );
    
    wp_send_json_success( $data );
}
add_action( 'wp_ajax_aqualuxe_wishlist', 'aqualuxe_woocommerce_ajax_wishlist' );
add_action( 'wp_ajax_nopriv_aqualuxe_wishlist', 'aqualuxe_woocommerce_ajax_wishlist' );

/**
 * Add wishlist page.
 */
function aqualuxe_woocommerce_wishlist_page() {
    if ( ! get_theme_mod( 'aqualuxe_wishlist', true ) ) {
        return;
    }
    
    // Register endpoint
    add_rewrite_endpoint( 'wishlist', EP_ROOT | EP_PAGES );
    
    // Add endpoint
    function aqualuxe_woocommerce_add_wishlist_endpoint() {
        add_rewrite_endpoint( 'wishlist', EP_ROOT | EP_PAGES );
    }
    add_action( 'init', 'aqualuxe_woocommerce_add_wishlist_endpoint' );
    
    // Add query var
    function aqualuxe_woocommerce_wishlist_query_vars( $vars ) {
        $vars[] = 'wishlist';
        return $vars;
    }
    add_filter( 'query_vars', 'aqualuxe_woocommerce_wishlist_query_vars', 0 );
    
    // Add menu item
    function aqualuxe_woocommerce_wishlist_menu_item( $items ) {
        $items['wishlist'] = __( 'Wishlist', 'aqualuxe' );
        return $items;
    }
    add_filter( 'woocommerce_account_menu_items', 'aqualuxe_woocommerce_wishlist_menu_item' );
    
    // Add content
    function aqualuxe_woocommerce_wishlist_content() {
        $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : array();
        
        if ( ! is_array( $wishlist ) ) {
            $wishlist = array();
        }
        
        echo '<h2>' . esc_html__( 'Wishlist', 'aqualuxe' ) . '</h2>';
        
        if ( empty( $wishlist ) ) {
            echo '<p>' . esc_html__( 'Your wishlist is empty.', 'aqualuxe' ) . '</p>';
            echo '<p><a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" class="button">' . esc_html__( 'Go to Shop', 'aqualuxe' ) . '</a></p>';
            return;
        }
        
        echo '<table class="shop_table shop_table_responsive cart wishlist-table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th class="product-remove">&nbsp;</th>';
        echo '<th class="product-thumbnail">&nbsp;</th>';
        echo '<th class="product-name">' . esc_html__( 'Product', 'aqualuxe' ) . '</th>';
        echo '<th class="product-price">' . esc_html__( 'Price', 'aqualuxe' ) . '</th>';
        echo '<th class="product-stock">' . esc_html__( 'Stock', 'aqualuxe' ) . '</th>';
        echo '<th class="product-actions">&nbsp;</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        foreach ( $wishlist as $product_id ) {
            $product = wc_get_product( $product_id );
            
            if ( ! $product ) {
                continue;
            }
            
            echo '<tr>';
            echo '<td class="product-remove">';
            echo '<a href="#" class="remove wishlist-remove" data-product-id="' . esc_attr( $product_id ) . '">&times;</a>';
            echo '</td>';
            echo '<td class="product-thumbnail">';
            echo '<a href="' . esc_url( $product->get_permalink() ) . '">';
            echo $product->get_image( 'thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo '</a>';
            echo '</td>';
            echo '<td class="product-name">';
            echo '<a href="' . esc_url( $product->get_permalink() ) . '">' . esc_html( $product->get_name() ) . '</a>';
            echo '</td>';
            echo '<td class="product-price">';
            echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo '</td>';
            echo '<td class="product-stock">';
            echo $product->is_in_stock() ? '<span class="in-stock">' . esc_html__( 'In Stock', 'aqualuxe' ) . '</span>' : '<span class="out-of-stock">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</span>';
            echo '</td>';
            echo '<td class="product-actions">';
            if ( $product->is_in_stock() ) {
                echo '<a href="' . esc_url( $product->add_to_cart_url() ) . '" class="button add_to_cart_button">' . esc_html__( 'Add to Cart', 'aqualuxe' ) . '</a>';
            }
            echo '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
    }
    add_action( 'woocommerce_account_wishlist_endpoint', 'aqualuxe_woocommerce_wishlist_content' );
}
add_action( 'init', 'aqualuxe_woocommerce_wishlist_page' );

/**
 * Add multi-currency support.
 */
function aqualuxe_woocommerce_multi_currency() {
    // Check if WPML WooCommerce Multilingual is active
    if ( function_exists( 'wcml_is_multi_currency_on' ) && wcml_is_multi_currency_on() ) {
        return;
    }
    
    // Check if WooCommerce Currency Switcher is active
    if ( class_exists( 'WOOCS' ) ) {
        return;
    }
    
    // Add currency switcher
    function aqualuxe_woocommerce_currency_switcher() {
        $currencies = array(
            'USD' => array(
                'symbol' => '$',
                'name'   => __( 'US Dollar', 'aqualuxe' ),
                'rate'   => 1,
            ),
            'EUR' => array(
                'symbol' => '€',
                'name'   => __( 'Euro', 'aqualuxe' ),
                'rate'   => 0.85,
            ),
            'GBP' => array(
                'symbol' => '£',
                'name'   => __( 'British Pound', 'aqualuxe' ),
                'rate'   => 0.75,
            ),
        );
        
        $current_currency = isset( $_COOKIE['aqualuxe_currency'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_currency'] ) ) : get_woocommerce_currency();
        
        if ( ! array_key_exists( $current_currency, $currencies ) ) {
            $current_currency = get_woocommerce_currency();
        }
        
        echo '<div class="currency-switcher">';
        echo '<select class="currency-switcher-select">';
        
        foreach ( $currencies as $code => $currency ) {
            echo '<option value="' . esc_attr( $code ) . '" ' . selected( $current_currency, $code, false ) . '>';
            echo esc_html( $currency['symbol'] . ' ' . $code . ' - ' . $currency['name'] );
            echo '</option>';
        }
        
        echo '</select>';
        echo '</div>';
    }
    
    // Change currency
    function aqualuxe_woocommerce_change_currency( $currency ) {
        if ( isset( $_COOKIE['aqualuxe_currency'] ) ) {
            $currency = sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_currency'] ) );
        }
        
        return $currency;
    }
    add_filter( 'woocommerce_currency', 'aqualuxe_woocommerce_change_currency' );
    
    // Change currency rate
    function aqualuxe_woocommerce_change_currency_rate( $price ) {
        $currencies = array(
            'USD' => 1,
            'EUR' => 0.85,
            'GBP' => 0.75,
        );
        
        $default_currency = get_option( 'woocommerce_currency' );
        $current_currency = isset( $_COOKIE['aqualuxe_currency'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_currency'] ) ) : $default_currency;
        
        if ( ! array_key_exists( $current_currency, $currencies ) ) {
            $current_currency = $default_currency;
        }
        
        if ( $current_currency === $default_currency ) {
            return $price;
        }
        
        $rate = $currencies[ $current_currency ] / $currencies[ $default_currency ];
        
        return $price * $rate;
    }
    add_filter( 'raw_woocommerce_price', 'aqualuxe_woocommerce_change_currency_rate' );
    
    // AJAX change currency
    function aqualuxe_woocommerce_ajax_change_currency() {
        if ( ! isset( $_POST['currency'] ) || ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid request.', 'aqualuxe' ) ) );
        }

        $currency = sanitize_text_field( wp_unslash( $_POST['currency'] ) );
        
        setcookie( 'aqualuxe_currency', $currency, time() + 30 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
        
        wp_send_json_success( array( 'message' => __( 'Currency changed.', 'aqualuxe' ) ) );
    }
    add_action( 'wp_ajax_aqualuxe_change_currency', 'aqualuxe_woocommerce_ajax_change_currency' );
    add_action( 'wp_ajax_nopriv_aqualuxe_change_currency', 'aqualuxe_woocommerce_ajax_change_currency' );
}
add_action( 'init', 'aqualuxe_woocommerce_multi_currency' );

/**
 * Add multi-vendor support.
 */
function aqualuxe_woocommerce_multi_vendor() {
    // Check if WC Marketplace is active
    if ( class_exists( 'WCMp' ) ) {
        return;
    }
    
    // Check if WC Vendors is active
    if ( class_exists( 'WC_Vendors' ) ) {
        return;
    }
    
    // Check if Dokan is active
    if ( class_exists( 'WeDevs_Dokan' ) ) {
        return;
    }
    
    // Check if WCFM is active
    if ( class_exists( 'WCFM' ) ) {
        return;
    }
    
    // Add vendor field to product
    function aqualuxe_woocommerce_add_vendor_field() {
        woocommerce_wp_text_input(
            array(
                'id'          => '_vendor',
                'label'       => __( 'Vendor', 'aqualuxe' ),
                'placeholder' => __( 'Enter vendor name', 'aqualuxe' ),
                'desc_tip'    => true,
                'description' => __( 'Enter the vendor name for this product.', 'aqualuxe' ),
            )
        );
    }
    add_action( 'woocommerce_product_options_general_product_data', 'aqualuxe_woocommerce_add_vendor_field' );
    
    // Save vendor field
    function aqualuxe_woocommerce_save_vendor_field( $post_id ) {
        $vendor = isset( $_POST['_vendor'] ) ? sanitize_text_field( wp_unslash( $_POST['_vendor'] ) ) : '';
        update_post_meta( $post_id, '_vendor', $vendor );
    }
    add_action( 'woocommerce_process_product_meta', 'aqualuxe_woocommerce_save_vendor_field' );
    
    // Display vendor on product page
    function aqualuxe_woocommerce_display_vendor() {
        global $product;
        
        $vendor = get_post_meta( $product->get_id(), '_vendor', true );
        
        if ( ! empty( $vendor ) ) {
            echo '<div class="product-vendor">';
            echo '<span class="vendor-label">' . esc_html__( 'Vendor:', 'aqualuxe' ) . '</span> ';
            echo '<span class="vendor-name">' . esc_html( $vendor ) . '</span>';
            echo '</div>';
        }
    }
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_display_vendor', 25 );
}
add_action( 'init', 'aqualuxe_woocommerce_multi_vendor' );

/**
 * Add multi-tenant support.
 */
function aqualuxe_woocommerce_multi_tenant() {
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    // Add tenant field to product
    function aqualuxe_woocommerce_add_tenant_field() {
        woocommerce_wp_text_input(
            array(
                'id'          => '_tenant',
                'label'       => __( 'Tenant', 'aqualuxe' ),
                'placeholder' => __( 'Enter tenant name', 'aqualuxe' ),
                'desc_tip'    => true,
                'description' => __( 'Enter the tenant name for this product.', 'aqualuxe' ),
            )
        );
    }
    add_action( 'woocommerce_product_options_general_product_data', 'aqualuxe_woocommerce_add_tenant_field' );
    
    // Save tenant field
    function aqualuxe_woocommerce_save_tenant_field( $post_id ) {
        $tenant = isset( $_POST['_tenant'] ) ? sanitize_text_field( wp_unslash( $_POST['_tenant'] ) ) : '';
        update_post_meta( $post_id, '_tenant', $tenant );
    }
    add_action( 'woocommerce_process_product_meta', 'aqualuxe_woocommerce_save_tenant_field' );
    
    // Display tenant on product page
    function aqualuxe_woocommerce_display_tenant() {
        global $product;
        
        $tenant = get_post_meta( $product->get_id(), '_tenant', true );
        
        if ( ! empty( $tenant ) ) {
            echo '<div class="product-tenant">';
            echo '<span class="tenant-label">' . esc_html__( 'Tenant:', 'aqualuxe' ) . '</span> ';
            echo '<span class="tenant-name">' . esc_html( $tenant ) . '</span>';
            echo '</div>';
        }
    }
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_display_tenant', 26 );
}
add_action( 'init', 'aqualuxe_woocommerce_multi_tenant' );

/**
 * Add product custom tabs.
 */
function aqualuxe_woocommerce_product_tabs( $tabs ) {
    // Add specifications tab
    $tabs['specifications'] = array(
        'title'    => __( 'Specifications', 'aqualuxe' ),
        'priority' => 20,
        'callback' => 'aqualuxe_woocommerce_specifications_tab_content',
    );
    
    // Add shipping tab
    $tabs['shipping'] = array(
        'title'    => __( 'Shipping', 'aqualuxe' ),
        'priority' => 30,
        'callback' => 'aqualuxe_woocommerce_shipping_tab_content',
    );
    
    // Add care tab
    $tabs['care'] = array(
        'title'    => __( 'Care Guide', 'aqualuxe' ),
        'priority' => 40,
        'callback' => 'aqualuxe_woocommerce_care_tab_content',
    );
    
    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs' );

/**
 * Specifications tab content.
 */
function aqualuxe_woocommerce_specifications_tab_content() {
    global $product;
    
    $specifications = get_post_meta( $product->get_id(), '_specifications', true );
    
    if ( empty( $specifications ) ) {
        echo '<p>' . esc_html__( 'No specifications available for this product.', 'aqualuxe' ) . '</p>';
        return;
    }
    
    echo wp_kses_post( wpautop( $specifications ) );
}

/**
 * Shipping tab content.
 */
function aqualuxe_woocommerce_shipping_tab_content() {
    global $product;
    
    $shipping = get_post_meta( $product->get_id(), '_shipping_info', true );
    
    if ( empty( $shipping ) ) {
        echo '<p>' . esc_html__( 'Standard shipping information applies to this product.', 'aqualuxe' ) . '</p>';
        
        // Display default shipping information
        echo '<h3>' . esc_html__( 'Domestic Shipping', 'aqualuxe' ) . '</h3>';
        echo '<p>' . esc_html__( 'Free shipping on orders over $100. Standard shipping takes 3-5 business days.', 'aqualuxe' ) . '</p>';
        
        echo '<h3>' . esc_html__( 'International Shipping', 'aqualuxe' ) . '</h3>';
        echo '<p>' . esc_html__( 'International shipping available to most countries. Shipping times vary by destination.', 'aqualuxe' ) . '</p>';
        
        return;
    }
    
    echo wp_kses_post( wpautop( $shipping ) );
}

/**
 * Care tab content.
 */
function aqualuxe_woocommerce_care_tab_content() {
    global $product;
    
    $care = get_post_meta( $product->get_id(), '_care_guide', true );
    
    if ( empty( $care ) ) {
        echo '<p>' . esc_html__( 'No specific care guide available for this product.', 'aqualuxe' ) . '</p>';
        return;
    }
    
    echo wp_kses_post( wpautop( $care ) );
}

/**
 * Add product custom fields.
 */
function aqualuxe_woocommerce_product_custom_fields() {
    // Add specifications field
    woocommerce_wp_textarea_input(
        array(
            'id'          => '_specifications',
            'label'       => __( 'Specifications', 'aqualuxe' ),
            'placeholder' => __( 'Enter product specifications', 'aqualuxe' ),
            'desc_tip'    => true,
            'description' => __( 'Enter the specifications for this product.', 'aqualuxe' ),
        )
    );
    
    // Add shipping field
    woocommerce_wp_textarea_input(
        array(
            'id'          => '_shipping_info',
            'label'       => __( 'Shipping Information', 'aqualuxe' ),
            'placeholder' => __( 'Enter shipping information', 'aqualuxe' ),
            'desc_tip'    => true,
            'description' => __( 'Enter the shipping information for this product.', 'aqualuxe' ),
        )
    );
    
    // Add care field
    woocommerce_wp_textarea_input(
        array(
            'id'          => '_care_guide',
            'label'       => __( 'Care Guide', 'aqualuxe' ),
            'placeholder' => __( 'Enter care guide', 'aqualuxe' ),
            'desc_tip'    => true,
            'description' => __( 'Enter the care guide for this product.', 'aqualuxe' ),
        )
    );
}
add_action( 'woocommerce_product_options_general_product_data', 'aqualuxe_woocommerce_product_custom_fields' );

/**
 * Save product custom fields.
 */
function aqualuxe_woocommerce_save_product_custom_fields( $post_id ) {
    // Save specifications field
    $specifications = isset( $_POST['_specifications'] ) ? sanitize_textarea_field( wp_unslash( $_POST['_specifications'] ) ) : '';
    update_post_meta( $post_id, '_specifications', $specifications );
    
    // Save shipping field
    $shipping = isset( $_POST['_shipping_info'] ) ? sanitize_textarea_field( wp_unslash( $_POST['_shipping_info'] ) ) : '';
    update_post_meta( $post_id, '_shipping_info', $shipping );
    
    // Save care field
    $care = isset( $_POST['_care_guide'] ) ? sanitize_textarea_field( wp_unslash( $_POST['_care_guide'] ) ) : '';
    update_post_meta( $post_id, '_care_guide', $care );
}
add_action( 'woocommerce_process_product_meta', 'aqualuxe_woocommerce_save_product_custom_fields' );

/**
 * Add product custom taxonomies.
 */
function aqualuxe_woocommerce_product_taxonomies() {
    // Register brand taxonomy
    register_taxonomy(
        'product_brand',
        'product',
        array(
            'label'        => __( 'Brands', 'aqualuxe' ),
            'rewrite'      => array( 'slug' => 'brand' ),
            'hierarchical' => true,
        )
    );
    
    // Register origin taxonomy
    register_taxonomy(
        'product_origin',
        'product',
        array(
            'label'        => __( 'Origin', 'aqualuxe' ),
            'rewrite'      => array( 'slug' => 'origin' ),
            'hierarchical' => true,
        )
    );
    
    // Register difficulty taxonomy
    register_taxonomy(
        'product_difficulty',
        'product',
        array(
            'label'        => __( 'Care Difficulty', 'aqualuxe' ),
            'rewrite'      => array( 'slug' => 'difficulty' ),
            'hierarchical' => true,
        )
    );
}
add_action( 'init', 'aqualuxe_woocommerce_product_taxonomies' );

/**
 * Display product taxonomies on product page.
 */
function aqualuxe_woocommerce_display_product_taxonomies() {
    global $product;
    
    // Display brand
    $brands = get_the_terms( $product->get_id(), 'product_brand' );
    if ( $brands && ! is_wp_error( $brands ) ) {
        echo '<div class="product-brand">';
        echo '<span class="brand-label">' . esc_html__( 'Brand:', 'aqualuxe' ) . '</span> ';
        
        $brand_links = array();
        foreach ( $brands as $brand ) {
            $brand_links[] = '<a href="' . esc_url( get_term_link( $brand ) ) . '">' . esc_html( $brand->name ) . '</a>';
        }
        
        echo implode( ', ', $brand_links ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '</div>';
    }
    
    // Display origin
    $origins = get_the_terms( $product->get_id(), 'product_origin' );
    if ( $origins && ! is_wp_error( $origins ) ) {
        echo '<div class="product-origin">';
        echo '<span class="origin-label">' . esc_html__( 'Origin:', 'aqualuxe' ) . '</span> ';
        
        $origin_links = array();
        foreach ( $origins as $origin ) {
            $origin_links[] = '<a href="' . esc_url( get_term_link( $origin ) ) . '">' . esc_html( $origin->name ) . '</a>';
        }
        
        echo implode( ', ', $origin_links ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '</div>';
    }
    
    // Display difficulty
    $difficulties = get_the_terms( $product->get_id(), 'product_difficulty' );
    if ( $difficulties && ! is_wp_error( $difficulties ) ) {
        echo '<div class="product-difficulty">';
        echo '<span class="difficulty-label">' . esc_html__( 'Care Difficulty:', 'aqualuxe' ) . '</span> ';
        
        $difficulty_links = array();
        foreach ( $difficulties as $difficulty ) {
            $difficulty_links[] = '<a href="' . esc_url( get_term_link( $difficulty ) ) . '">' . esc_html( $difficulty->name ) . '</a>';
        }
        
        echo implode( ', ', $difficulty_links ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '</div>';
    }
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_display_product_taxonomies', 27 );

/**
 * Add product filter widgets.
 */
function aqualuxe_woocommerce_product_filter_widgets() {
    // Register brand filter widget
    register_widget( 'Aqualuxe_WC_Widget_Brand_Filter' );
    
    // Register origin filter widget
    register_widget( 'Aqualuxe_WC_Widget_Origin_Filter' );
    
    // Register difficulty filter widget
    register_widget( 'Aqualuxe_WC_Widget_Difficulty_Filter' );
}
add_action( 'widgets_init', 'aqualuxe_woocommerce_product_filter_widgets' );

/**
 * Brand filter widget.
 */
class Aqualuxe_WC_Widget_Brand_Filter extends WP_Widget {
    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_brand_filter',
            __( 'AquaLuxe Brand Filter', 'aqualuxe' ),
            array(
                'description' => __( 'Filter products by brand.', 'aqualuxe' ),
            )
        );
    }

    /**
     * Widget output.
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     */
    public function widget( $args, $instance ) {
        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }

        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Filter by Brand', 'aqualuxe' );
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        $brands = get_terms( array(
            'taxonomy'   => 'product_brand',
            'hide_empty' => true,
        ) );

        if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) {
            echo '<ul class="brand-filter">';
            foreach ( $brands as $brand ) {
                echo '<li><a href="' . esc_url( get_term_link( $brand ) ) . '">' . esc_html( $brand->name ) . '</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>' . esc_html__( 'No brands found.', 'aqualuxe' ) . '</p>';
        }

        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Widget form.
     *
     * @param array $instance Widget instance.
     * @return void
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Filter by Brand', 'aqualuxe' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    /**
     * Widget update.
     *
     * @param array $new_instance New widget instance.
     * @param array $old_instance Old widget instance.
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        return $instance;
    }
}

/**
 * Origin filter widget.
 */
class Aqualuxe_WC_Widget_Origin_Filter extends WP_Widget {
    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_origin_filter',
            __( 'AquaLuxe Origin Filter', 'aqualuxe' ),
            array(
                'description' => __( 'Filter products by origin.', 'aqualuxe' ),
            )
        );
    }

    /**
     * Widget output.
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     */
    public function widget( $args, $instance ) {
        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }

        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Filter by Origin', 'aqualuxe' );
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        $origins = get_terms( array(
            'taxonomy'   => 'product_origin',
            'hide_empty' => true,
        ) );

        if ( ! empty( $origins ) && ! is_wp_error( $origins ) ) {
            echo '<ul class="origin-filter">';
            foreach ( $origins as $origin ) {
                echo '<li><a href="' . esc_url( get_term_link( $origin ) ) . '">' . esc_html( $origin->name ) . '</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>' . esc_html__( 'No origins found.', 'aqualuxe' ) . '</p>';
        }

        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Widget form.
     *
     * @param array $instance Widget instance.
     * @return void
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Filter by Origin', 'aqualuxe' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    /**
     * Widget update.
     *
     * @param array $new_instance New widget instance.
     * @param array $old_instance Old widget instance.
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        return $instance;
    }
}

/**
 * Difficulty filter widget.
 */
class Aqualuxe_WC_Widget_Difficulty_Filter extends WP_Widget {
    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_difficulty_filter',
            __( 'AquaLuxe Difficulty Filter', 'aqualuxe' ),
            array(
                'description' => __( 'Filter products by care difficulty.', 'aqualuxe' ),
            )
        );
    }

    /**
     * Widget output.
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     */
    public function widget( $args, $instance ) {
        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }

        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Filter by Care Difficulty', 'aqualuxe' );
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        $difficulties = get_terms( array(
            'taxonomy'   => 'product_difficulty',
            'hide_empty' => true,
        ) );

        if ( ! empty( $difficulties ) && ! is_wp_error( $difficulties ) ) {
            echo '<ul class="difficulty-filter">';
            foreach ( $difficulties as $difficulty ) {
                echo '<li><a href="' . esc_url( get_term_link( $difficulty ) ) . '">' . esc_html( $difficulty->name ) . '</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>' . esc_html__( 'No difficulty levels found.', 'aqualuxe' ) . '</p>';
        }

        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Widget form.
     *
     * @param array $instance Widget instance.
     * @return void
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Filter by Care Difficulty', 'aqualuxe' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    /**
     * Widget update.
     *
     * @param array $new_instance New widget instance.
     * @param array $old_instance Old widget instance.
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        return $instance;
    }
}

/**
 * Add product custom meta box.
 */
function aqualuxe_woocommerce_product_meta_box() {
    add_meta_box(
        'aqualuxe_product_meta_box',
        __( 'AquaLuxe Product Options', 'aqualuxe' ),
        'aqualuxe_woocommerce_product_meta_box_content',
        'product',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'aqualuxe_woocommerce_product_meta_box' );

/**
 * Product meta box content.
 */
function aqualuxe_woocommerce_product_meta_box_content( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_product_meta_box', 'aqualuxe_product_meta_box_nonce' );
    
    // Get current values
    $featured = get_post_meta( $post->ID, '_aqualuxe_featured', true );
    $new = get_post_meta( $post->ID, '_aqualuxe_new', true );
    $bestseller = get_post_meta( $post->ID, '_aqualuxe_bestseller', true );
    $video_url = get_post_meta( $post->ID, '_aqualuxe_video_url', true );
    $size = get_post_meta( $post->ID, '_aqualuxe_size', true );
    $age = get_post_meta( $post->ID, '_aqualuxe_age', true );
    $tank_size = get_post_meta( $post->ID, '_aqualuxe_tank_size', true );
    $diet = get_post_meta( $post->ID, '_aqualuxe_diet', true );
    $temperature = get_post_meta( $post->ID, '_aqualuxe_temperature', true );
    $ph = get_post_meta( $post->ID, '_aqualuxe_ph', true );
    $hardness = get_post_meta( $post->ID, '_aqualuxe_hardness', true );
    
    // Output fields
    ?>
    <div class="aqualuxe-product-options">
        <div class="aqualuxe-product-options-section">
            <h3><?php esc_html_e( 'Product Badges', 'aqualuxe' ); ?></h3>
            
            <div class="aqualuxe-product-options-field">
                <label for="aqualuxe_featured">
                    <input type="checkbox" id="aqualuxe_featured" name="aqualuxe_featured" value="1" <?php checked( $featured, '1' ); ?>>
                    <?php esc_html_e( 'Featured Product', 'aqualuxe' ); ?>
                </label>
            </div>
            
            <div class="aqualuxe-product-options-field">
                <label for="aqualuxe_new">
                    <input type="checkbox" id="aqualuxe_new" name="aqualuxe_new" value="1" <?php checked( $new, '1' ); ?>>
                    <?php esc_html_e( 'New Arrival', 'aqualuxe' ); ?>
                </label>
            </div>
            
            <div class="aqualuxe-product-options-field">
                <label for="aqualuxe_bestseller">
                    <input type="checkbox" id="aqualuxe_bestseller" name="aqualuxe_bestseller" value="1" <?php checked( $bestseller, '1' ); ?>>
                    <?php esc_html_e( 'Bestseller', 'aqualuxe' ); ?>
                </label>
            </div>
        </div>
        
        <div class="aqualuxe-product-options-section">
            <h3><?php esc_html_e( 'Product Media', 'aqualuxe' ); ?></h3>
            
            <div class="aqualuxe-product-options-field">
                <label for="aqualuxe_video_url"><?php esc_html_e( 'Video URL', 'aqualuxe' ); ?></label>
                <input type="url" id="aqualuxe_video_url" name="aqualuxe_video_url" value="<?php echo esc_url( $video_url ); ?>" class="widefat">
                <p class="description"><?php esc_html_e( 'Enter a YouTube or Vimeo URL for the product video.', 'aqualuxe' ); ?></p>
            </div>
        </div>
        
        <div class="aqualuxe-product-options-section">
            <h3><?php esc_html_e( 'Aquatic Species Details', 'aqualuxe' ); ?></h3>
            
            <div class="aqualuxe-product-options-field">
                <label for="aqualuxe_size"><?php esc_html_e( 'Size', 'aqualuxe' ); ?></label>
                <input type="text" id="aqualuxe_size" name="aqualuxe_size" value="<?php echo esc_attr( $size ); ?>" class="widefat">
                <p class="description"><?php esc_html_e( 'Enter the size of the species (e.g., "2-3 inches").', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="aqualuxe-product-options-field">
                <label for="aqualuxe_age"><?php esc_html_e( 'Age', 'aqualuxe' ); ?></label>
                <input type="text" id="aqualuxe_age" name="aqualuxe_age" value="<?php echo esc_attr( $age ); ?>" class="widefat">
                <p class="description"><?php esc_html_e( 'Enter the age of the species (e.g., "Juvenile" or "Adult").', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="aqualuxe-product-options-field">
                <label for="aqualuxe_tank_size"><?php esc_html_e( 'Recommended Tank Size', 'aqualuxe' ); ?></label>
                <input type="text" id="aqualuxe_tank_size" name="aqualuxe_tank_size" value="<?php echo esc_attr( $tank_size ); ?>" class="widefat">
                <p class="description"><?php esc_html_e( 'Enter the recommended tank size (e.g., "20 gallons").', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="aqualuxe-product-options-field">
                <label for="aqualuxe_diet"><?php esc_html_e( 'Diet', 'aqualuxe' ); ?></label>
                <input type="text" id="aqualuxe_diet" name="aqualuxe_diet" value="<?php echo esc_attr( $diet ); ?>" class="widefat">
                <p class="description"><?php esc_html_e( 'Enter the diet of the species (e.g., "Omnivore").', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="aqualuxe-product-options-field">
                <label for="aqualuxe_temperature"><?php esc_html_e( 'Temperature Range', 'aqualuxe' ); ?></label>
                <input type="text" id="aqualuxe_temperature" name="aqualuxe_temperature" value="<?php echo esc_attr( $temperature ); ?>" class="widefat">
                <p class="description"><?php esc_html_e( 'Enter the temperature range (e.g., "72-78°F").', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="aqualuxe-product-options-field">
                <label for="aqualuxe_ph"><?php esc_html_e( 'pH Range', 'aqualuxe' ); ?></label>
                <input type="text" id="aqualuxe_ph" name="aqualuxe_ph" value="<?php echo esc_attr( $ph ); ?>" class="widefat">
                <p class="description"><?php esc_html_e( 'Enter the pH range (e.g., "6.5-7.5").', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="aqualuxe-product-options-field">
                <label for="aqualuxe_hardness"><?php esc_html_e( 'Water Hardness', 'aqualuxe' ); ?></label>
                <input type="text" id="aqualuxe_hardness" name="aqualuxe_hardness" value="<?php echo esc_attr( $hardness ); ?>" class="widefat">
                <p class="description"><?php esc_html_e( 'Enter the water hardness range (e.g., "5-15 dGH").', 'aqualuxe' ); ?></p>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Save product meta box.
 */
function aqualuxe_woocommerce_save_product_meta_box( $post_id ) {
    // Check if nonce is set
    if ( ! isset( $_POST['aqualuxe_product_meta_box_nonce'] ) ) {
        return;
    }
    
    // Verify nonce
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aqualuxe_product_meta_box_nonce'] ) ), 'aqualuxe_product_meta_box' ) ) {
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
    
    // Save fields
    $fields = array(
        'aqualuxe_featured'     => 'checkbox',
        'aqualuxe_new'          => 'checkbox',
        'aqualuxe_bestseller'   => 'checkbox',
        'aqualuxe_video_url'    => 'url',
        'aqualuxe_size'         => 'text',
        'aqualuxe_age'          => 'text',
        'aqualuxe_tank_size'    => 'text',
        'aqualuxe_diet'         => 'text',
        'aqualuxe_temperature'  => 'text',
        'aqualuxe_ph'           => 'text',
        'aqualuxe_hardness'     => 'text',
    );
    
    foreach ( $fields as $field => $type ) {
        $meta_key = '_' . $field;
        
        if ( 'checkbox' === $type ) {
            $value = isset( $_POST[ $field ] ) ? '1' : '';
        } elseif ( 'url' === $type ) {
            $value = isset( $_POST[ $field ] ) ? esc_url_raw( wp_unslash( $_POST[ $field ] ) ) : '';
        } else {
            $value = isset( $_POST[ $field ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) : '';
        }
        
        update_post_meta( $post_id, $meta_key, $value );
    }
}
add_action( 'save_post_product', 'aqualuxe_woocommerce_save_product_meta_box' );

/**
 * Display product badges.
 */
function aqualuxe_woocommerce_product_badges() {
    global $product;
    
    // Sale badge
    if ( $product->is_on_sale() ) {
        echo '<span class="badge sale-badge">' . esc_html__( 'Sale', 'aqualuxe' ) . '</span>';
    }
    
    // Featured badge
    if ( get_post_meta( $product->get_id(), '_aqualuxe_featured', true ) ) {
        echo '<span class="badge featured-badge">' . esc_html__( 'Featured', 'aqualuxe' ) . '</span>';
    }
    
    // New badge
    if ( get_post_meta( $product->get_id(), '_aqualuxe_new', true ) ) {
        echo '<span class="badge new-badge">' . esc_html__( 'New', 'aqualuxe' ) . '</span>';
    }
    
    // Bestseller badge
    if ( get_post_meta( $product->get_id(), '_aqualuxe_bestseller', true ) ) {
        echo '<span class="badge bestseller-badge">' . esc_html__( 'Bestseller', 'aqualuxe' ) . '</span>';
    }
    
    // Out of stock badge
    if ( ! $product->is_in_stock() ) {
        echo '<span class="badge out-of-stock-badge">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</span>';
    }
}
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_product_badges', 10 );
add_action( 'woocommerce_before_single_product_summary', 'aqualuxe_woocommerce_product_badges', 10 );

/**
 * Display product video.
 */
function aqualuxe_woocommerce_product_video() {
    global $product;
    
    $video_url = get_post_meta( $product->get_id(), '_aqualuxe_video_url', true );
    
    if ( empty( $video_url ) ) {
        return;
    }
    
    echo '<div class="product-video">';
    echo '<h3>' . esc_html__( 'Product Video', 'aqualuxe' ) . '</h3>';
    
    // YouTube
    if ( strpos( $video_url, 'youtube.com' ) !== false || strpos( $video_url, 'youtu.be' ) !== false ) {
        // Extract video ID
        if ( strpos( $video_url, 'youtube.com/watch?v=' ) !== false ) {
            $video_id = substr( $video_url, strpos( $video_url, 'watch?v=' ) + 8 );
        } elseif ( strpos( $video_url, 'youtu.be/' ) !== false ) {
            $video_id = substr( $video_url, strpos( $video_url, 'youtu.be/' ) + 9 );
        } else {
            $video_id = '';
        }
        
        if ( ! empty( $video_id ) ) {
            echo '<div class="video-container">';
            echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . esc_attr( $video_id ) . '" frameborder="0" allowfullscreen></iframe>';
            echo '</div>';
        }
    }
    
    // Vimeo
    elseif ( strpos( $video_url, 'vimeo.com' ) !== false ) {
        // Extract video ID
        $video_id = substr( $video_url, strpos( $video_url, 'vimeo.com/' ) + 10 );
        
        if ( ! empty( $video_id ) ) {
            echo '<div class="video-container">';
            echo '<iframe src="https://player.vimeo.com/video/' . esc_attr( $video_id ) . '" width="560" height="315" frameborder="0" allowfullscreen></iframe>';
            echo '</div>';
        }
    }
    
    // Other video URL
    else {
        echo '<div class="video-container">';
        echo '<a href="' . esc_url( $video_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Watch Video', 'aqualuxe' ) . '</a>';
        echo '</div>';
    }
    
    echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_video', 25 );

/**
 * Display product species details.
 */
function aqualuxe_woocommerce_product_species_details() {
    global $product;
    
    // Get species details
    $size = get_post_meta( $product->get_id(), '_aqualuxe_size', true );
    $age = get_post_meta( $product->get_id(), '_aqualuxe_age', true );
    $tank_size = get_post_meta( $product->get_id(), '_aqualuxe_tank_size', true );
    $diet = get_post_meta( $product->get_id(), '_aqualuxe_diet', true );
    $temperature = get_post_meta( $product->get_id(), '_aqualuxe_temperature', true );
    $ph = get_post_meta( $product->get_id(), '_aqualuxe_ph', true );
    $hardness = get_post_meta( $product->get_id(), '_aqualuxe_hardness', true );
    
    // Check if any details exist
    if ( empty( $size ) && empty( $age ) && empty( $tank_size ) && empty( $diet ) && empty( $temperature ) && empty( $ph ) && empty( $hardness ) ) {
        return;
    }
    
    echo '<div class="product-species-details">';
    echo '<h3>' . esc_html__( 'Species Details', 'aqualuxe' ) . '</h3>';
    
    echo '<table class="species-details-table">';
    
    if ( ! empty( $size ) ) {
        echo '<tr>';
        echo '<th>' . esc_html__( 'Size', 'aqualuxe' ) . '</th>';
        echo '<td>' . esc_html( $size ) . '</td>';
        echo '</tr>';
    }
    
    if ( ! empty( $age ) ) {
        echo '<tr>';
        echo '<th>' . esc_html__( 'Age', 'aqualuxe' ) . '</th>';
        echo '<td>' . esc_html( $age ) . '</td>';
        echo '</tr>';
    }
    
    if ( ! empty( $tank_size ) ) {
        echo '<tr>';
        echo '<th>' . esc_html__( 'Recommended Tank Size', 'aqualuxe' ) . '</th>';
        echo '<td>' . esc_html( $tank_size ) . '</td>';
        echo '</tr>';
    }
    
    if ( ! empty( $diet ) ) {
        echo '<tr>';
        echo '<th>' . esc_html__( 'Diet', 'aqualuxe' ) . '</th>';
        echo '<td>' . esc_html( $diet ) . '</td>';
        echo '</tr>';
    }
    
    if ( ! empty( $temperature ) ) {
        echo '<tr>';
        echo '<th>' . esc_html__( 'Temperature Range', 'aqualuxe' ) . '</th>';
        echo '<td>' . esc_html( $temperature ) . '</td>';
        echo '</tr>';
    }
    
    if ( ! empty( $ph ) ) {
        echo '<tr>';
        echo '<th>' . esc_html__( 'pH Range', 'aqualuxe' ) . '</th>';
        echo '<td>' . esc_html( $ph ) . '</td>';
        echo '</tr>';
    }
    
    if ( ! empty( $hardness ) ) {
        echo '<tr>';
        echo '<th>' . esc_html__( 'Water Hardness', 'aqualuxe' ) . '</th>';
        echo '<td>' . esc_html( $hardness ) . '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
    echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_species_details', 30 );

/**
 * Add product comparison feature.
 */
function aqualuxe_woocommerce_product_comparison() {
    // Add compare button
    function aqualuxe_woocommerce_compare_button() {
        global $product;
        
        echo '<a href="#" class="compare-button button" data-product-id="' . esc_attr( $product->get_id() ) . '">' . esc_html__( 'Compare', 'aqualuxe' ) . '</a>';
    }
    add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_compare_button', 25 );
    
    // Add compare modal
    function aqualuxe_woocommerce_compare_modal() {
        ?>
        <div id="compare-modal" class="compare-modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="compare-modal-title">
            <div class="compare-modal-content bg-white dark:bg-gray-800 w-full max-w-6xl mx-auto p-6 rounded-lg shadow-xl">
                <div class="compare-modal-header flex justify-between items-center mb-4">
                    <h2 id="compare-modal-title" class="text-xl font-bold"><?php esc_html_e( 'Product Comparison', 'aqualuxe' ); ?></h2>
                    <button id="compare-modal-close" class="compare-modal-close" aria-label="<?php esc_attr_e( 'Close comparison', 'aqualuxe' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="compare-modal-body">
                    <div class="compare-loading flex items-center justify-center py-12 hidden">
                        <svg class="animate-spin h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div class="compare-content">
                        <div class="compare-empty text-center py-12">
                            <p><?php esc_html_e( 'No products added to compare.', 'aqualuxe' ); ?></p>
                            <p><?php esc_html_e( 'Add products to compare by clicking the "Compare" button on product listings.', 'aqualuxe' ); ?></p>
                        </div>
                        <div class="compare-table-wrapper hidden">
                            <table class="compare-table w-full">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e( 'Product', 'aqualuxe' ); ?></th>
                                        <th class="compare-product-1"></th>
                                        <th class="compare-product-2"></th>
                                        <th class="compare-product-3"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th><?php esc_html_e( 'Image', 'aqualuxe' ); ?></th>
                                        <td class="compare-product-1-image"></td>
                                        <td class="compare-product-2-image"></td>
                                        <td class="compare-product-3-image"></td>
                                    </tr>
                                    <tr>
                                        <th><?php esc_html_e( 'Name', 'aqualuxe' ); ?></th>
                                        <td class="compare-product-1-name"></td>
                                        <td class="compare-product-2-name"></td>
                                        <td class="compare-product-3-name"></td>
                                    </tr>
                                    <tr>
                                        <th><?php esc_html_e( 'Price', 'aqualuxe' ); ?></th>
                                        <td class="compare-product-1-price"></td>
                                        <td class="compare-product-2-price"></td>
                                        <td class="compare-product-3-price"></td>
                                    </tr>
                                    <tr>
                                        <th><?php esc_html_e( 'Description', 'aqualuxe' ); ?></th>
                                        <td class="compare-product-1-description"></td>
                                        <td class="compare-product-2-description"></td>
                                        <td class="compare-product-3-description"></td>
                                    </tr>
                                    <tr>
                                        <th><?php esc_html_e( 'Size', 'aqualuxe' ); ?></th>
                                        <td class="compare-product-1-size"></td>
                                        <td class="compare-product-2-size"></td>
                                        <td class="compare-product-3-size"></td>
                                    </tr>
                                    <tr>
                                        <th><?php esc_html_e( 'Tank Size', 'aqualuxe' ); ?></th>
                                        <td class="compare-product-1-tank-size"></td>
                                        <td class="compare-product-2-tank-size"></td>
                                        <td class="compare-product-3-tank-size"></td>
                                    </tr>
                                    <tr>
                                        <th><?php esc_html_e( 'Diet', 'aqualuxe' ); ?></th>
                                        <td class="compare-product-1-diet"></td>
                                        <td class="compare-product-2-diet"></td>
                                        <td class="compare-product-3-diet"></td>
                                    </tr>
                                    <tr>
                                        <th><?php esc_html_e( 'Temperature', 'aqualuxe' ); ?></th>
                                        <td class="compare-product-1-temperature"></td>
                                        <td class="compare-product-2-temperature"></td>
                                        <td class="compare-product-3-temperature"></td>
                                    </tr>
                                    <tr>
                                        <th><?php esc_html_e( 'pH', 'aqualuxe' ); ?></th>
                                        <td class="compare-product-1-ph"></td>
                                        <td class="compare-product-2-ph"></td>
                                        <td class="compare-product-3-ph"></td>
                                    </tr>
                                    <tr>
                                        <th><?php esc_html_e( 'Actions', 'aqualuxe' ); ?></th>
                                        <td class="compare-product-1-actions"></td>
                                        <td class="compare-product-2-actions"></td>
                                        <td class="compare-product-3-actions"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    add_action( 'wp_footer', 'aqualuxe_woocommerce_compare_modal' );
    
    // AJAX compare
    function aqualuxe_woocommerce_ajax_compare() {
        if ( ! isset( $_POST['product_id'] ) || ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid request.', 'aqualuxe' ) ) );
        }

        $product_id = absint( $_POST['product_id'] );
        $action = isset( $_POST['action_type'] ) ? sanitize_text_field( wp_unslash( $_POST['action_type'] ) ) : 'add';
        
        // Get current compare list
        $compare = isset( $_COOKIE['aqualuxe_compare'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_compare'] ) ), true ) : array();
        
        if ( ! is_array( $compare ) ) {
            $compare = array();
        }
        
        if ( 'add' === $action ) {
            if ( ! in_array( $product_id, $compare, true ) ) {
                if ( count( $compare ) >= 3 ) {
                    wp_send_json_error( array( 'message' => __( 'You can compare up to 3 products.', 'aqualuxe' ) ) );
                }
                
                $compare[] = $product_id;
                $message = __( 'Product added to comparison.', 'aqualuxe' );
            } else {
                $message = __( 'Product already in comparison.', 'aqualuxe' );
            }
        } else {
            $key = array_search( $product_id, $compare, true );
            if ( false !== $key ) {
                unset( $compare[ $key ] );
                $compare = array_values( $compare );
                $message = __( 'Product removed from comparison.', 'aqualuxe' );
            } else {
                $message = __( 'Product not in comparison.', 'aqualuxe' );
            }
        }
        
        // Save compare list
        setcookie( 'aqualuxe_compare', wp_json_encode( $compare ), time() + 30 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
        
        // Get product data
        $products = array();
        foreach ( $compare as $id ) {
            $product = wc_get_product( $id );
            
            if ( ! $product ) {
                continue;
            }
            
            $products[] = array(
                'id'          => $product->get_id(),
                'name'        => $product->get_name(),
                'price'       => $product->get_price_html(),
                'description' => $product->get_short_description(),
                'image'       => wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'thumbnail' ),
                'url'         => get_permalink( $product->get_id() ),
                'size'        => get_post_meta( $product->get_id(), '_aqualuxe_size', true ),
                'tank_size'   => get_post_meta( $product->get_id(), '_aqualuxe_tank_size', true ),
                'diet'        => get_post_meta( $product->get_id(), '_aqualuxe_diet', true ),
                'temperature' => get_post_meta( $product->get_id(), '_aqualuxe_temperature', true ),
                'ph'          => get_post_meta( $product->get_id(), '_aqualuxe_ph', true ),
            );
        }
        
        $data = array(
            'message'  => $message,
            'compare'  => $compare,
            'products' => $products,
            'count'    => count( $compare ),
        );
        
        wp_send_json_success( $data );
    }
    add_action( 'wp_ajax_aqualuxe_compare', 'aqualuxe_woocommerce_ajax_compare' );
    add_action( 'wp_ajax_nopriv_aqualuxe_compare', 'aqualuxe_woocommerce_ajax_compare' );
    
    // Get compare products
    function aqualuxe_woocommerce_ajax_get_compare_products() {
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid request.', 'aqualuxe' ) ) );
        }

        // Get current compare list
        $compare = isset( $_COOKIE['aqualuxe_compare'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_compare'] ) ), true ) : array();
        
        if ( ! is_array( $compare ) ) {
            $compare = array();
        }
        
        // Get product data
        $products = array();
        foreach ( $compare as $id ) {
            $product = wc_get_product( $id );
            
            if ( ! $product ) {
                continue;
            }
            
            $products[] = array(
                'id'          => $product->get_id(),
                'name'        => $product->get_name(),
                'price'       => $product->get_price_html(),
                'description' => $product->get_short_description(),
                'image'       => wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'thumbnail' ),
                'url'         => get_permalink( $product->get_id() ),
                'size'        => get_post_meta( $product->get_id(), '_aqualuxe_size', true ),
                'tank_size'   => get_post_meta( $product->get_id(), '_aqualuxe_tank_size', true ),
                'diet'        => get_post_meta( $product->get_id(), '_aqualuxe_diet', true ),
                'temperature' => get_post_meta( $product->get_id(), '_aqualuxe_temperature', true ),
                'ph'          => get_post_meta( $product->get_id(), '_aqualuxe_ph', true ),
            );
        }
        
        $data = array(
            'compare'  => $compare,
            'products' => $products,
            'count'    => count( $compare ),
        );
        
        wp_send_json_success( $data );
    }
    add_action( 'wp_ajax_aqualuxe_get_compare_products', 'aqualuxe_woocommerce_ajax_get_compare_products' );
    add_action( 'wp_ajax_nopriv_aqualuxe_get_compare_products', 'aqualuxe_woocommerce_ajax_get_compare_products' );
}
add_action( 'init', 'aqualuxe_woocommerce_product_comparison' );