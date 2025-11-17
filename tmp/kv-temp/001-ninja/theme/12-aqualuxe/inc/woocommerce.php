<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package AquaLuxe
 * @since 1.0.0
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
    // Add theme support for WooCommerce
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

    // Add theme support for product gallery features
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
    wp_enqueue_style( 'aqualuxe-woocommerce-style', get_template_directory_uri() . '/assets/css/woocommerce.css', array(), AQUALUXE_VERSION );

    // Add custom fonts for WooCommerce
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
    
    // Enqueue WooCommerce scripts
    wp_enqueue_script( 'aqualuxe-woocommerce-script', get_template_directory_uri() . '/assets/js/woocommerce.js', array( 'jquery' ), AQUALUXE_VERSION, true );
    
    // Localize script for AJAX functionality
    wp_localize_script(
        'aqualuxe-woocommerce-script',
        'aqualuxeWooCommerce',
        array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'aqualuxe-woocommerce-nonce' ),
            'i18n'    => array(
                'addToCart'      => esc_html__( 'Add to cart', 'aqualuxe' ),
                'addingToCart'   => esc_html__( 'Adding...', 'aqualuxe' ),
                'addedToCart'    => esc_html__( 'Added to cart', 'aqualuxe' ),
                'viewCart'       => esc_html__( 'View cart', 'aqualuxe' ),
                'errorMessage'   => esc_html__( 'Error occurred. Please try again.', 'aqualuxe' ),
                'quickViewTitle' => esc_html__( 'Quick View', 'aqualuxe' ),
                'addToWishlist'  => esc_html__( 'Add to wishlist', 'aqualuxe' ),
                'addedToWishlist' => esc_html__( 'Added to wishlist', 'aqualuxe' ),
                'compare'        => esc_html__( 'Compare', 'aqualuxe' ),
                'addedToCompare' => esc_html__( 'Added to compare', 'aqualuxe' ),
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

/**
 * Add custom WooCommerce wrapper.
 */
function aqualuxe_woocommerce_wrapper_before() {
    ?>
    <main id="primary" class="site-main">
        <div class="container">
            <div class="row">
                <?php if ( is_active_sidebar( 'shop-sidebar' ) && ! is_product() && get_theme_mod( 'aqualuxe_shop_sidebar', true ) ) : ?>
                    <div class="col-lg-9">
                <?php else : ?>
                    <div class="col-lg-12">
                <?php endif; ?>
    <?php
}
add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before' );

/**
 * Add custom WooCommerce wrapper end.
 */
function aqualuxe_woocommerce_wrapper_after() {
    ?>
                </div><!-- .col-lg-9/12 -->
                
                <?php if ( is_active_sidebar( 'shop-sidebar' ) && ! is_product() && get_theme_mod( 'aqualuxe_shop_sidebar', true ) ) : ?>
                    <div class="col-lg-3">
                        <aside id="secondary" class="shop-sidebar widget-area">
                            <?php dynamic_sidebar( 'shop-sidebar' ); ?>
                        </aside>
                    </div>
                <?php endif; ?>
            </div><!-- .row -->
        </div><!-- .container -->
    </main><!-- #primary -->
    <?php
}
add_action( 'woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after' );

/**
 * Remove sidebar from single product page
 */
function aqualuxe_remove_sidebar_product_pages() {
    if ( is_product() ) {
        remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
    }
}
add_action( 'wp', 'aqualuxe_remove_sidebar_product_pages' );

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
function aqualuxe_woocommerce_header_cart() {
    if ( is_cart() ) {
        $class = 'current-menu-item';
    } else {
        $class = '';
    }
    ?>
    <div class="site-header-cart">
        <div class="cart-contents-wrapper <?php echo esc_attr( $class ); ?>">
            <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
                <i class="fas fa-shopping-cart"></i>
                <span class="count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></span>
                <span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_total() ); ?></span>
            </a>
        </div>
        <div class="mini-cart-dropdown">
            <?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
        </div>
    </div>
    <?php
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
    return get_theme_mod( 'aqualuxe_shop_products_per_page', 12 );
}
add_filter( 'loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page' );

/**
 * Add custom badges to products
 */
function aqualuxe_product_badges() {
    global $product;

    // Sale badge is already handled by WooCommerce
    
    // New badge
    $newness_days = get_theme_mod( 'aqualuxe_new_badge_days', 7 );
    $created = strtotime( $product->get_date_created() );
    
    if ( ( time() - ( 60 * 60 * 24 * $newness_days ) ) < $created ) {
        echo '<span class="badge badge-new">' . esc_html__( 'New', 'aqualuxe' ) . '</span>';
    }
    
    // Featured badge
    if ( $product->is_featured() && get_theme_mod( 'aqualuxe_show_featured_badge', true ) ) {
        echo '<span class="badge badge-featured">' . esc_html__( 'Featured', 'aqualuxe' ) . '</span>';
    }
    
    // Out of stock badge
    if ( ! $product->is_in_stock() ) {
        echo '<span class="badge badge-out-of-stock">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</span>';
    }
}
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_product_badges', 20 );

/**
 * AJAX add to cart functionality
 */
function aqualuxe_ajax_add_to_cart() {
    check_ajax_referer( 'aqualuxe-woocommerce-nonce', 'nonce' );
    
    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    $quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;
    $variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
    $variation = isset( $_POST['variation'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['variation'] ) ) : array();
    
    $cart_item_data = array();
    
    // Add custom data if needed
    if ( isset( $_POST['custom_data'] ) ) {
        $custom_data = json_decode( stripslashes( $_POST['custom_data'] ), true );
        if ( is_array( $custom_data ) ) {
            $cart_item_data = array_merge( $cart_item_data, $custom_data );
        }
    }
    
    // Add to cart
    if ( $variation_id > 0 ) {
        $product_status = get_post_status( $product_id );
        
        if ( WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation, $cart_item_data ) && 'publish' === $product_status ) {
            do_action( 'woocommerce_ajax_added_to_cart', $product_id );
            
            if ( get_option( 'woocommerce_cart_redirect_after_add' ) === 'yes' ) {
                wc_add_to_cart_message( array( $product_id => $quantity ), true );
            }
            
            WC_AJAX::get_refreshed_fragments();
        } else {
            $data = array(
                'error'       => true,
                'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
            );
            
            wp_send_json( $data );
        }
    } else {
        $product_status = get_post_status( $product_id );
        
        if ( WC()->cart->add_to_cart( $product_id, $quantity, 0, array(), $cart_item_data ) && 'publish' === $product_status ) {
            do_action( 'woocommerce_ajax_added_to_cart', $product_id );
            
            if ( get_option( 'woocommerce_cart_redirect_after_add' ) === 'yes' ) {
                wc_add_to_cart_message( array( $product_id => $quantity ), true );
            }
            
            WC_AJAX::get_refreshed_fragments();
        } else {
            $data = array(
                'error'       => true,
                'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
            );
            
            wp_send_json( $data );
        }
    }
    
    wp_die();
}
add_action( 'wp_ajax_aqualuxe_ajax_add_to_cart', 'aqualuxe_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_aqualuxe_ajax_add_to_cart', 'aqualuxe_ajax_add_to_cart' );

/**
 * Quick view functionality
 */
function aqualuxe_quick_view() {
    check_ajax_referer( 'aqualuxe-woocommerce-nonce', 'nonce' );
    
    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    
    if ( ! $product_id ) {
        wp_die();
    }
    
    global $post, $product;
    
    $post = get_post( $product_id );
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        wp_die();
    }
    
    setup_postdata( $post );
    
    ob_start();
    
    wc_get_template( 'content-quick-view.php' );
    
    $output = ob_get_clean();
    
    wp_reset_postdata();
    
    wp_send_json_success( $output );
    
    wp_die();
}
add_action( 'wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view' );
add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view' );

/**
 * Wishlist functionality
 */
function aqualuxe_add_to_wishlist() {
    check_ajax_referer( 'aqualuxe-woocommerce-nonce', 'nonce' );
    
    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    
    if ( ! $product_id ) {
        wp_send_json_error( array( 'message' => __( 'Invalid product ID', 'aqualuxe' ) ) );
    }
    
    $wishlist = array();
    
    if ( isset( $_COOKIE['aqualuxe_wishlist'] ) ) {
        $wishlist = json_decode( stripslashes( $_COOKIE['aqualuxe_wishlist'] ), true );
    }
    
    if ( ! in_array( $product_id, $wishlist ) ) {
        $wishlist[] = $product_id;
    }
    
    setcookie( 'aqualuxe_wishlist', json_encode( $wishlist ), time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
    
    wp_send_json_success( array( 'message' => __( 'Product added to wishlist', 'aqualuxe' ) ) );
    
    wp_die();
}
add_action( 'wp_ajax_aqualuxe_add_to_wishlist', 'aqualuxe_add_to_wishlist' );
add_action( 'wp_ajax_nopriv_aqualuxe_add_to_wishlist', 'aqualuxe_add_to_wishlist' );

/**
 * Remove from wishlist functionality
 */
function aqualuxe_remove_from_wishlist() {
    check_ajax_referer( 'aqualuxe-woocommerce-nonce', 'nonce' );
    
    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    
    if ( ! $product_id ) {
        wp_send_json_error( array( 'message' => __( 'Invalid product ID', 'aqualuxe' ) ) );
    }
    
    $wishlist = array();
    
    if ( isset( $_COOKIE['aqualuxe_wishlist'] ) ) {
        $wishlist = json_decode( stripslashes( $_COOKIE['aqualuxe_wishlist'] ), true );
    }
    
    $key = array_search( $product_id, $wishlist );
    
    if ( false !== $key ) {
        unset( $wishlist[ $key ] );
        $wishlist = array_values( $wishlist );
    }
    
    setcookie( 'aqualuxe_wishlist', json_encode( $wishlist ), time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
    
    wp_send_json_success( array( 'message' => __( 'Product removed from wishlist', 'aqualuxe' ) ) );
    
    wp_die();
}
add_action( 'wp_ajax_aqualuxe_remove_from_wishlist', 'aqualuxe_remove_from_wishlist' );
add_action( 'wp_ajax_nopriv_aqualuxe_remove_from_wishlist', 'aqualuxe_remove_from_wishlist' );

/**
 * Compare functionality
 */
function aqualuxe_add_to_compare() {
    check_ajax_referer( 'aqualuxe-woocommerce-nonce', 'nonce' );
    
    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    
    if ( ! $product_id ) {
        wp_send_json_error( array( 'message' => __( 'Invalid product ID', 'aqualuxe' ) ) );
    }
    
    $compare = array();
    
    if ( isset( $_COOKIE['aqualuxe_compare'] ) ) {
        $compare = json_decode( stripslashes( $_COOKIE['aqualuxe_compare'] ), true );
    }
    
    if ( ! in_array( $product_id, $compare ) ) {
        $compare[] = $product_id;
    }
    
    setcookie( 'aqualuxe_compare', json_encode( $compare ), time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
    
    wp_send_json_success( array( 'message' => __( 'Product added to compare', 'aqualuxe' ) ) );
    
    wp_die();
}
add_action( 'wp_ajax_aqualuxe_add_to_compare', 'aqualuxe_add_to_compare' );
add_action( 'wp_ajax_nopriv_aqualuxe_add_to_compare', 'aqualuxe_add_to_compare' );

/**
 * Remove from compare functionality
 */
function aqualuxe_remove_from_compare() {
    check_ajax_referer( 'aqualuxe-woocommerce-nonce', 'nonce' );
    
    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    
    if ( ! $product_id ) {
        wp_send_json_error( array( 'message' => __( 'Invalid product ID', 'aqualuxe' ) ) );
    }
    
    $compare = array();
    
    if ( isset( $_COOKIE['aqualuxe_compare'] ) ) {
        $compare = json_decode( stripslashes( $_COOKIE['aqualuxe_compare'] ), true );
    }
    
    $key = array_search( $product_id, $compare );
    
    if ( false !== $key ) {
        unset( $compare[ $key ] );
        $compare = array_values( $compare );
    }
    
    setcookie( 'aqualuxe_compare', json_encode( $compare ), time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
    
    wp_send_json_success( array( 'message' => __( 'Product removed from compare', 'aqualuxe' ) ) );
    
    wp_die();
}
add_action( 'wp_ajax_aqualuxe_remove_from_compare', 'aqualuxe_remove_from_compare' );
add_action( 'wp_ajax_nopriv_aqualuxe_remove_from_compare', 'aqualuxe_remove_from_compare' );

/**
 * Add custom fields to product
 */
function aqualuxe_add_custom_fields_to_product() {
    global $post;
    
    echo '<div class="options_group">';
    
    // Delivery time
    woocommerce_wp_text_input(
        array(
            'id'          => '_aqualuxe_delivery_time',
            'label'       => __( 'Delivery Time', 'aqualuxe' ),
            'placeholder' => __( 'e.g. 1-3 business days', 'aqualuxe' ),
            'desc_tip'    => true,
            'description' => __( 'Enter the estimated delivery time for this product.', 'aqualuxe' ),
        )
    );
    
    // Care instructions
    woocommerce_wp_textarea_input(
        array(
            'id'          => '_aqualuxe_care_instructions',
            'label'       => __( 'Care Instructions', 'aqualuxe' ),
            'placeholder' => __( 'Enter care instructions for this product', 'aqualuxe' ),
            'desc_tip'    => true,
            'description' => __( 'Enter care instructions for this product.', 'aqualuxe' ),
        )
    );
    
    // Water parameters
    woocommerce_wp_textarea_input(
        array(
            'id'          => '_aqualuxe_water_parameters',
            'label'       => __( 'Water Parameters', 'aqualuxe' ),
            'placeholder' => __( 'Enter recommended water parameters', 'aqualuxe' ),
            'desc_tip'    => true,
            'description' => __( 'Enter recommended water parameters for this fish or aquatic plant.', 'aqualuxe' ),
        )
    );
    
    // Size
    woocommerce_wp_text_input(
        array(
            'id'          => '_aqualuxe_size',
            'label'       => __( 'Size', 'aqualuxe' ),
            'placeholder' => __( 'e.g. 2-3 inches', 'aqualuxe' ),
            'desc_tip'    => true,
            'description' => __( 'Enter the size of this fish or aquatic plant.', 'aqualuxe' ),
        )
    );
    
    // Diet
    woocommerce_wp_text_input(
        array(
            'id'          => '_aqualuxe_diet',
            'label'       => __( 'Diet', 'aqualuxe' ),
            'placeholder' => __( 'e.g. Omnivore', 'aqualuxe' ),
            'desc_tip'    => true,
            'description' => __( 'Enter the diet of this fish.', 'aqualuxe' ),
        )
    );
    
    // Temperament
    woocommerce_wp_text_input(
        array(
            'id'          => '_aqualuxe_temperament',
            'label'       => __( 'Temperament', 'aqualuxe' ),
            'placeholder' => __( 'e.g. Peaceful', 'aqualuxe' ),
            'desc_tip'    => true,
            'description' => __( 'Enter the temperament of this fish.', 'aqualuxe' ),
        )
    );
    
    echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'aqualuxe_add_custom_fields_to_product' );

/**
 * Save custom fields
 */
function aqualuxe_save_custom_fields( $post_id ) {
    // Delivery time
    $delivery_time = isset( $_POST['_aqualuxe_delivery_time'] ) ? sanitize_text_field( $_POST['_aqualuxe_delivery_time'] ) : '';
    update_post_meta( $post_id, '_aqualuxe_delivery_time', $delivery_time );
    
    // Care instructions
    $care_instructions = isset( $_POST['_aqualuxe_care_instructions'] ) ? sanitize_textarea_field( $_POST['_aqualuxe_care_instructions'] ) : '';
    update_post_meta( $post_id, '_aqualuxe_care_instructions', $care_instructions );
    
    // Water parameters
    $water_parameters = isset( $_POST['_aqualuxe_water_parameters'] ) ? sanitize_textarea_field( $_POST['_aqualuxe_water_parameters'] ) : '';
    update_post_meta( $post_id, '_aqualuxe_water_parameters', $water_parameters );
    
    // Size
    $size = isset( $_POST['_aqualuxe_size'] ) ? sanitize_text_field( $_POST['_aqualuxe_size'] ) : '';
    update_post_meta( $post_id, '_aqualuxe_size', $size );
    
    // Diet
    $diet = isset( $_POST['_aqualuxe_diet'] ) ? sanitize_text_field( $_POST['_aqualuxe_diet'] ) : '';
    update_post_meta( $post_id, '_aqualuxe_diet', $diet );
    
    // Temperament
    $temperament = isset( $_POST['_aqualuxe_temperament'] ) ? sanitize_text_field( $_POST['_aqualuxe_temperament'] ) : '';
    update_post_meta( $post_id, '_aqualuxe_temperament', $temperament );
}
add_action( 'woocommerce_process_product_meta', 'aqualuxe_save_custom_fields' );

/**
 * Display custom fields on product page
 */
function aqualuxe_display_custom_fields() {
    global $product;
    
    $delivery_time = get_post_meta( $product->get_id(), '_aqualuxe_delivery_time', true );
    $care_instructions = get_post_meta( $product->get_id(), '_aqualuxe_care_instructions', true );
    $water_parameters = get_post_meta( $product->get_id(), '_aqualuxe_water_parameters', true );
    $size = get_post_meta( $product->get_id(), '_aqualuxe_size', true );
    $diet = get_post_meta( $product->get_id(), '_aqualuxe_diet', true );
    $temperament = get_post_meta( $product->get_id(), '_aqualuxe_temperament', true );
    
    if ( $delivery_time || $size || $diet || $temperament ) {
        echo '<div class="product-meta">';
        
        if ( $delivery_time ) {
            echo '<div class="product-meta-item delivery-time">';
            echo '<span class="label">' . esc_html__( 'Delivery Time:', 'aqualuxe' ) . '</span> ';
            echo '<span class="value">' . esc_html( $delivery_time ) . '</span>';
            echo '</div>';
        }
        
        if ( $size ) {
            echo '<div class="product-meta-item size">';
            echo '<span class="label">' . esc_html__( 'Size:', 'aqualuxe' ) . '</span> ';
            echo '<span class="value">' . esc_html( $size ) . '</span>';
            echo '</div>';
        }
        
        if ( $diet ) {
            echo '<div class="product-meta-item diet">';
            echo '<span class="label">' . esc_html__( 'Diet:', 'aqualuxe' ) . '</span> ';
            echo '<span class="value">' . esc_html( $diet ) . '</span>';
            echo '</div>';
        }
        
        if ( $temperament ) {
            echo '<div class="product-meta-item temperament">';
            echo '<span class="label">' . esc_html__( 'Temperament:', 'aqualuxe' ) . '</span> ';
            echo '<span class="value">' . esc_html( $temperament ) . '</span>';
            echo '</div>';
        }
        
        echo '</div>';
    }
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_display_custom_fields', 25 );

/**
 * Add custom product tabs
 */
function aqualuxe_custom_product_tabs( $tabs ) {
    global $product;
    
    $care_instructions = get_post_meta( $product->get_id(), '_aqualuxe_care_instructions', true );
    $water_parameters = get_post_meta( $product->get_id(), '_aqualuxe_water_parameters', true );
    
    if ( $care_instructions ) {
        $tabs['care_instructions'] = array(
            'title'    => __( 'Care Instructions', 'aqualuxe' ),
            'priority' => 30,
            'callback' => 'aqualuxe_care_instructions_tab_content',
        );
    }
    
    if ( $water_parameters ) {
        $tabs['water_parameters'] = array(
            'title'    => __( 'Water Parameters', 'aqualuxe' ),
            'priority' => 40,
            'callback' => 'aqualuxe_water_parameters_tab_content',
        );
    }
    
    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'aqualuxe_custom_product_tabs' );

/**
 * Care instructions tab content
 */
function aqualuxe_care_instructions_tab_content() {
    global $product;
    
    $care_instructions = get_post_meta( $product->get_id(), '_aqualuxe_care_instructions', true );
    
    if ( $care_instructions ) {
        echo '<h2>' . esc_html__( 'Care Instructions', 'aqualuxe' ) . '</h2>';
        echo '<div class="care-instructions">';
        echo wp_kses_post( wpautop( $care_instructions ) );
        echo '</div>';
    }
}

/**
 * Water parameters tab content
 */
function aqualuxe_water_parameters_tab_content() {
    global $product;
    
    $water_parameters = get_post_meta( $product->get_id(), '_aqualuxe_water_parameters', true );
    
    if ( $water_parameters ) {
        echo '<h2>' . esc_html__( 'Water Parameters', 'aqualuxe' ) . '</h2>';
        echo '<div class="water-parameters">';
        echo wp_kses_post( wpautop( $water_parameters ) );
        echo '</div>';
    }
}

/**
 * Modify checkout fields
 */
function aqualuxe_checkout_fields( $fields ) {
    // Make first and last name required
    $fields['billing']['billing_first_name']['required'] = true;
    $fields['billing']['billing_last_name']['required'] = true;
    
    // Add placeholder for first and last name
    $fields['billing']['billing_first_name']['placeholder'] = __( 'First Name', 'aqualuxe' );
    $fields['billing']['billing_last_name']['placeholder'] = __( 'Last Name', 'aqualuxe' );
    
    // Add placeholder for email
    $fields['billing']['billing_email']['placeholder'] = __( 'Email Address', 'aqualuxe' );
    
    // Add placeholder for phone
    $fields['billing']['billing_phone']['placeholder'] = __( 'Phone Number', 'aqualuxe' );
    
    // Add placeholder for company
    $fields['billing']['billing_company']['placeholder'] = __( 'Company Name (Optional)', 'aqualuxe' );
    
    // Add placeholder for address
    $fields['billing']['billing_address_1']['placeholder'] = __( 'Street Address', 'aqualuxe' );
    $fields['billing']['billing_address_2']['placeholder'] = __( 'Apartment, Suite, Unit, etc. (Optional)', 'aqualuxe' );
    $fields['billing']['billing_city']['placeholder'] = __( 'City', 'aqualuxe' );
    $fields['billing']['billing_postcode']['placeholder'] = __( 'Postcode / ZIP', 'aqualuxe' );
    
    // Do the same for shipping fields
    $fields['shipping']['shipping_first_name']['placeholder'] = __( 'First Name', 'aqualuxe' );
    $fields['shipping']['shipping_last_name']['placeholder'] = __( 'Last Name', 'aqualuxe' );
    $fields['shipping']['shipping_company']['placeholder'] = __( 'Company Name (Optional)', 'aqualuxe' );
    $fields['shipping']['shipping_address_1']['placeholder'] = __( 'Street Address', 'aqualuxe' );
    $fields['shipping']['shipping_address_2']['placeholder'] = __( 'Apartment, Suite, Unit, etc. (Optional)', 'aqualuxe' );
    $fields['shipping']['shipping_city']['placeholder'] = __( 'City', 'aqualuxe' );
    $fields['shipping']['shipping_postcode']['placeholder'] = __( 'Postcode / ZIP', 'aqualuxe' );
    
    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'aqualuxe_checkout_fields' );

/**
 * Add custom order meta
 */
function aqualuxe_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['billing_delivery_date'] ) ) {
        update_post_meta( $order_id, 'billing_delivery_date', sanitize_text_field( $_POST['billing_delivery_date'] ) );
    }
    
    if ( ! empty( $_POST['order_comments'] ) ) {
        update_post_meta( $order_id, 'order_comments', sanitize_textarea_field( $_POST['order_comments'] ) );
    }
}
add_action( 'woocommerce_checkout_update_order_meta', 'aqualuxe_checkout_field_update_order_meta' );

/**
 * Add custom order meta to admin order page
 */
function aqualuxe_admin_order_meta_general( $order ) {
    $delivery_date = get_post_meta( $order->get_id(), 'billing_delivery_date', true );
    
    if ( $delivery_date ) {
        echo '<p><strong>' . esc_html__( 'Delivery Date', 'aqualuxe' ) . ':</strong> ' . esc_html( $delivery_date ) . '</p>';
    }
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'aqualuxe_admin_order_meta_general', 10, 1 );

/**
 * Add custom order meta to emails
 */
function aqualuxe_email_order_meta_fields( $fields, $sent_to_admin, $order ) {
    $delivery_date = get_post_meta( $order->get_id(), 'billing_delivery_date', true );
    
    if ( $delivery_date ) {
        $fields['delivery_date'] = array(
            'label' => __( 'Delivery Date', 'aqualuxe' ),
            'value' => $delivery_date,
        );
    }
    
    return $fields;
}
add_filter( 'woocommerce_email_order_meta_fields', 'aqualuxe_email_order_meta_fields', 10, 3 );

/**
 * Add custom order meta to order received page
 */
function aqualuxe_order_received_meta( $order ) {
    $delivery_date = get_post_meta( $order->get_id(), 'billing_delivery_date', true );
    
    if ( $delivery_date ) {
        echo '<p><strong>' . esc_html__( 'Delivery Date', 'aqualuxe' ) . ':</strong> ' . esc_html( $delivery_date ) . '</p>';
    }
}
add_action( 'woocommerce_order_details_after_order_table', 'aqualuxe_order_received_meta', 10, 1 );

/**
 * Add custom product sorting options
 */
function aqualuxe_custom_woocommerce_catalog_orderby( $sortby ) {
    $sortby['price-asc'] = __( 'Price: Low to High', 'aqualuxe' );
    $sortby['price-desc'] = __( 'Price: High to Low', 'aqualuxe' );
    $sortby['date'] = __( 'Newest Arrivals', 'aqualuxe' );
    $sortby['rating'] = __( 'Average Rating', 'aqualuxe' );
    $sortby['popularity'] = __( 'Popularity', 'aqualuxe' );
    
    return $sortby;
}
add_filter( 'woocommerce_catalog_orderby', 'aqualuxe_custom_woocommerce_catalog_orderby', 20 );

/**
 * Add custom product sorting query
 */
function aqualuxe_custom_woocommerce_get_catalog_ordering_args( $args ) {
    if ( isset( $_GET['orderby'] ) ) {
        switch ( $_GET['orderby'] ) {
            case 'price-asc':
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'ASC';
                $args['meta_key'] = '_price';
                break;
            case 'price-desc':
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                $args['meta_key'] = '_price';
                break;
        }
    }
    
    return $args;
}
add_filter( 'woocommerce_get_catalog_ordering_args', 'aqualuxe_custom_woocommerce_get_catalog_ordering_args' );

/**
 * Add custom product filtering
 */
function aqualuxe_custom_woocommerce_layered_nav_term_html( $term_html, $term, $link, $count ) {
    return '<a rel="nofollow" href="' . esc_url( $link ) . '">' . esc_html( $term->name ) . '</a> <span class="count">(' . absint( $count ) . ')</span>';
}
add_filter( 'woocommerce_layered_nav_term_html', 'aqualuxe_custom_woocommerce_layered_nav_term_html', 10, 4 );

/**
 * Add custom product filtering by price
 */
function aqualuxe_custom_price_filter() {
    global $wp_the_query;
    
    if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
        return;
    }
    
    if ( ! $wp_the_query->post_count ) {
        return;
    }
    
    $min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '';
    $max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : '';
    
    echo '<div class="price-filter">';
    echo '<h4>' . esc_html__( 'Filter by Price', 'aqualuxe' ) . '</h4>';
    echo '<form method="get" action="">';
    echo '<div class="price-inputs">';
    echo '<input type="text" name="min_price" placeholder="' . esc_attr__( 'Min', 'aqualuxe' ) . '" value="' . esc_attr( $min_price ) . '">';
    echo '<span class="price-separator">-</span>';
    echo '<input type="text" name="max_price" placeholder="' . esc_attr__( 'Max', 'aqualuxe' ) . '" value="' . esc_attr( $max_price ) . '">';
    echo '</div>';
    echo '<button type="submit" class="button">' . esc_html__( 'Filter', 'aqualuxe' ) . '</button>';
    echo '</form>';
    echo '</div>';
}
add_action( 'woocommerce_before_shop_loop', 'aqualuxe_custom_price_filter', 30 );

/**
 * Add custom product filtering by price query
 */
function aqualuxe_custom_price_filter_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_post_type_archive( 'product' ) || is_tax( get_object_taxonomies( 'product' ) ) ) ) {
        if ( isset( $_GET['min_price'] ) && isset( $_GET['max_price'] ) && ! empty( $_GET['min_price'] ) && ! empty( $_GET['max_price'] ) ) {
            $meta_query = $query->get( 'meta_query' );
            
            if ( ! is_array( $meta_query ) ) {
                $meta_query = array();
            }
            
            $meta_query[] = array(
                'key'     => '_price',
                'value'   => array( floatval( $_GET['min_price'] ), floatval( $_GET['max_price'] ) ),
                'compare' => 'BETWEEN',
                'type'    => 'NUMERIC',
            );
            
            $query->set( 'meta_query', $meta_query );
        }
    }
}
add_action( 'pre_get_posts', 'aqualuxe_custom_price_filter_query' );

/**
 * Add custom product filtering by rating
 */
function aqualuxe_custom_rating_filter() {
    global $wp_the_query;
    
    if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
        return;
    }
    
    if ( ! $wp_the_query->post_count ) {
        return;
    }
    
    $rating_filter = isset( $_GET['rating_filter'] ) ? absint( $_GET['rating_filter'] ) : 0;
    
    echo '<div class="rating-filter">';
    echo '<h4>' . esc_html__( 'Filter by Rating', 'aqualuxe' ) . '</h4>';
    echo '<ul>';
    
    for ( $rating = 5; $rating >= 1; $rating-- ) {
        $link = add_query_arg( 'rating_filter', $rating );
        $class = $rating_filter === $rating ? 'active' : '';
        
        echo '<li class="' . esc_attr( $class ) . '">';
        echo '<a href="' . esc_url( $link ) . '">';
        
        for ( $i = 1; $i <= 5; $i++ ) {
            if ( $i <= $rating ) {
                echo '<i class="fas fa-star"></i>';
            } else {
                echo '<i class="far fa-star"></i>';
            }
        }
        
        echo '</a>';
        echo '</li>';
    }
    
    if ( $rating_filter ) {
        $link = remove_query_arg( 'rating_filter' );
        echo '<li class="clear-filter"><a href="' . esc_url( $link ) . '">' . esc_html__( 'Clear', 'aqualuxe' ) . '</a></li>';
    }
    
    echo '</ul>';
    echo '</div>';
}
add_action( 'woocommerce_before_shop_loop', 'aqualuxe_custom_rating_filter', 35 );

/**
 * Add custom product filtering by rating query
 */
function aqualuxe_custom_rating_filter_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_post_type_archive( 'product' ) || is_tax( get_object_taxonomies( 'product' ) ) ) ) {
        if ( isset( $_GET['rating_filter'] ) && ! empty( $_GET['rating_filter'] ) ) {
            $meta_query = $query->get( 'meta_query' );
            
            if ( ! is_array( $meta_query ) ) {
                $meta_query = array();
            }
            
            $meta_query[] = array(
                'key'     => '_wc_average_rating',
                'value'   => array( absint( $_GET['rating_filter'] ) - 0.5, 5 ),
                'compare' => 'BETWEEN',
                'type'    => 'DECIMAL',
            );
            
            $query->set( 'meta_query', $meta_query );
        }
    }
}
add_action( 'pre_get_posts', 'aqualuxe_custom_rating_filter_query' );

/**
 * Add custom product filtering by availability
 */
function aqualuxe_custom_availability_filter() {
    global $wp_the_query;
    
    if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
        return;
    }
    
    if ( ! $wp_the_query->post_count ) {
        return;
    }
    
    $availability_filter = isset( $_GET['availability'] ) ? esc_attr( $_GET['availability'] ) : '';
    
    echo '<div class="availability-filter">';
    echo '<h4>' . esc_html__( 'Filter by Availability', 'aqualuxe' ) . '</h4>';
    echo '<ul>';
    
    $in_stock_class = $availability_filter === 'in_stock' ? 'active' : '';
    $out_of_stock_class = $availability_filter === 'out_of_stock' ? 'active' : '';
    
    echo '<li class="' . esc_attr( $in_stock_class ) . '">';
    echo '<a href="' . esc_url( add_query_arg( 'availability', 'in_stock' ) ) . '">' . esc_html__( 'In Stock', 'aqualuxe' ) . '</a>';
    echo '</li>';
    
    echo '<li class="' . esc_attr( $out_of_stock_class ) . '">';
    echo '<a href="' . esc_url( add_query_arg( 'availability', 'out_of_stock' ) ) . '">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</a>';
    echo '</li>';
    
    if ( $availability_filter ) {
        echo '<li class="clear-filter"><a href="' . esc_url( remove_query_arg( 'availability' ) ) . '">' . esc_html__( 'Clear', 'aqualuxe' ) . '</a></li>';
    }
    
    echo '</ul>';
    echo '</div>';
}
add_action( 'woocommerce_before_shop_loop', 'aqualuxe_custom_availability_filter', 40 );

/**
 * Add custom product filtering by availability query
 */
function aqualuxe_custom_availability_filter_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_post_type_archive( 'product' ) || is_tax( get_object_taxonomies( 'product' ) ) ) ) {
        if ( isset( $_GET['availability'] ) && ! empty( $_GET['availability'] ) ) {
            $meta_query = $query->get( 'meta_query' );
            
            if ( ! is_array( $meta_query ) ) {
                $meta_query = array();
            }
            
            if ( $_GET['availability'] === 'in_stock' ) {
                $meta_query[] = array(
                    'key'     => '_stock_status',
                    'value'   => 'instock',
                    'compare' => '=',
                );
            } elseif ( $_GET['availability'] === 'out_of_stock' ) {
                $meta_query[] = array(
                    'key'     => '_stock_status',
                    'value'   => 'outofstock',
                    'compare' => '=',
                );
            }
            
            $query->set( 'meta_query', $meta_query );
        }
    }
}
add_action( 'pre_get_posts', 'aqualuxe_custom_availability_filter_query' );

/**
 * Add custom product filtering by featured
 */
function aqualuxe_custom_featured_filter() {
    global $wp_the_query;
    
    if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
        return;
    }
    
    if ( ! $wp_the_query->post_count ) {
        return;
    }
    
    $featured_filter = isset( $_GET['featured'] ) && $_GET['featured'] === '1' ? true : false;
    
    echo '<div class="featured-filter">';
    echo '<h4>' . esc_html__( 'Filter by Featured', 'aqualuxe' ) . '</h4>';
    echo '<ul>';
    
    $featured_class = $featured_filter ? 'active' : '';
    
    echo '<li class="' . esc_attr( $featured_class ) . '">';
    echo '<a href="' . esc_url( add_query_arg( 'featured', '1' ) ) . '">' . esc_html__( 'Featured Products', 'aqualuxe' ) . '</a>';
    echo '</li>';
    
    if ( $featured_filter ) {
        echo '<li class="clear-filter"><a href="' . esc_url( remove_query_arg( 'featured' ) ) . '">' . esc_html__( 'Clear', 'aqualuxe' ) . '</a></li>';
    }
    
    echo '</ul>';
    echo '</div>';
}
add_action( 'woocommerce_before_shop_loop', 'aqualuxe_custom_featured_filter', 45 );

/**
 * Add custom product filtering by featured query
 */
function aqualuxe_custom_featured_filter_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_post_type_archive( 'product' ) || is_tax( get_object_taxonomies( 'product' ) ) ) ) {
        if ( isset( $_GET['featured'] ) && $_GET['featured'] === '1' ) {
            $tax_query = $query->get( 'tax_query' );
            
            if ( ! is_array( $tax_query ) ) {
                $tax_query = array();
            }
            
            $tax_query[] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
                'operator' => 'IN',
            );
            
            $query->set( 'tax_query', $tax_query );
        }
    }
}
add_action( 'pre_get_posts', 'aqualuxe_custom_featured_filter_query' );

/**
 * Add custom product filtering by on sale
 */
function aqualuxe_custom_sale_filter() {
    global $wp_the_query;
    
    if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
        return;
    }
    
    if ( ! $wp_the_query->post_count ) {
        return;
    }
    
    $sale_filter = isset( $_GET['on_sale'] ) && $_GET['on_sale'] === '1' ? true : false;
    
    echo '<div class="sale-filter">';
    echo '<h4>' . esc_html__( 'Filter by Sale', 'aqualuxe' ) . '</h4>';
    echo '<ul>';
    
    $sale_class = $sale_filter ? 'active' : '';
    
    echo '<li class="' . esc_attr( $sale_class ) . '">';
    echo '<a href="' . esc_url( add_query_arg( 'on_sale', '1' ) ) . '">' . esc_html__( 'On Sale Products', 'aqualuxe' ) . '</a>';
    echo '</li>';
    
    if ( $sale_filter ) {
        echo '<li class="clear-filter"><a href="' . esc_url( remove_query_arg( 'on_sale' ) ) . '">' . esc_html__( 'Clear', 'aqualuxe' ) . '</a></li>';
    }
    
    echo '</ul>';
    echo '</div>';
}
add_action( 'woocommerce_before_shop_loop', 'aqualuxe_custom_sale_filter', 50 );

/**
 * Add custom product filtering by on sale query
 */
function aqualuxe_custom_sale_filter_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_post_type_archive( 'product' ) || is_tax( get_object_taxonomies( 'product' ) ) ) ) {
        if ( isset( $_GET['on_sale'] ) && $_GET['on_sale'] === '1' ) {
            $meta_query = $query->get( 'meta_query' );
            
            if ( ! is_array( $meta_query ) ) {
                $meta_query = array();
            }
            
            $meta_query[] = array(
                'relation' => 'OR',
                array(
                    'key'     => '_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'NUMERIC',
                ),
                array(
                    'key'     => '_min_variation_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'NUMERIC',
                ),
            );
            
            $query->set( 'meta_query', $meta_query );
        }
    }
}
add_action( 'pre_get_posts', 'aqualuxe_custom_sale_filter_query' );

/**
 * Add custom product filtering reset button
 */
function aqualuxe_custom_filter_reset() {
    global $wp_the_query;
    
    if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
        return;
    }
    
    if ( ! $wp_the_query->post_count ) {
        return;
    }
    
    $has_filters = false;
    
    if ( isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) || isset( $_GET['rating_filter'] ) || isset( $_GET['availability'] ) || isset( $_GET['featured'] ) || isset( $_GET['on_sale'] ) ) {
        $has_filters = true;
    }
    
    if ( $has_filters ) {
        echo '<div class="filter-reset">';
        echo '<a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" class="button">' . esc_html__( 'Reset Filters', 'aqualuxe' ) . '</a>';
        echo '</div>';
    }
}
add_action( 'woocommerce_before_shop_loop', 'aqualuxe_custom_filter_reset', 55 );

/**
 * Add custom product filtering active filters
 */
function aqualuxe_custom_active_filters() {
    global $wp_the_query;
    
    if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
        return;
    }
    
    if ( ! $wp_the_query->post_count ) {
        return;
    }
    
    $has_filters = false;
    $active_filters = array();
    
    if ( isset( $_GET['min_price'] ) && isset( $_GET['max_price'] ) && ! empty( $_GET['min_price'] ) && ! empty( $_GET['max_price'] ) ) {
        $has_filters = true;
        $active_filters[] = array(
            'name' => sprintf( __( 'Price: %s - %s', 'aqualuxe' ), wc_price( $_GET['min_price'] ), wc_price( $_GET['max_price'] ) ),
            'link' => remove_query_arg( array( 'min_price', 'max_price' ) ),
        );
    }
    
    if ( isset( $_GET['rating_filter'] ) && ! empty( $_GET['rating_filter'] ) ) {
        $has_filters = true;
        $active_filters[] = array(
            'name' => sprintf( __( 'Rating: %s and up', 'aqualuxe' ), $_GET['rating_filter'] ),
            'link' => remove_query_arg( 'rating_filter' ),
        );
    }
    
    if ( isset( $_GET['availability'] ) && ! empty( $_GET['availability'] ) ) {
        $has_filters = true;
        
        if ( $_GET['availability'] === 'in_stock' ) {
            $active_filters[] = array(
                'name' => __( 'In Stock', 'aqualuxe' ),
                'link' => remove_query_arg( 'availability' ),
            );
        } elseif ( $_GET['availability'] === 'out_of_stock' ) {
            $active_filters[] = array(
                'name' => __( 'Out of Stock', 'aqualuxe' ),
                'link' => remove_query_arg( 'availability' ),
            );
        }
    }
    
    if ( isset( $_GET['featured'] ) && $_GET['featured'] === '1' ) {
        $has_filters = true;
        $active_filters[] = array(
            'name' => __( 'Featured', 'aqualuxe' ),
            'link' => remove_query_arg( 'featured' ),
        );
    }
    
    if ( isset( $_GET['on_sale'] ) && $_GET['on_sale'] === '1' ) {
        $has_filters = true;
        $active_filters[] = array(
            'name' => __( 'On Sale', 'aqualuxe' ),
            'link' => remove_query_arg( 'on_sale' ),
        );
    }
    
    if ( $has_filters ) {
        echo '<div class="active-filters">';
        echo '<h4>' . esc_html__( 'Active Filters', 'aqualuxe' ) . '</h4>';
        echo '<ul>';
        
        foreach ( $active_filters as $filter ) {
            echo '<li>';
            echo '<a href="' . esc_url( $filter['link'] ) . '" class="remove-filter">' . esc_html( $filter['name'] ) . ' <i class="fas fa-times"></i></a>';
            echo '</li>';
        }
        
        echo '</ul>';
        echo '</div>';
    }
}
add_action( 'woocommerce_before_shop_loop', 'aqualuxe_custom_active_filters', 25 );