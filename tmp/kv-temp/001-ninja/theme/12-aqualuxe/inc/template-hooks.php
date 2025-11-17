<?php
/**
 * Template hooks for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Header hooks
 */
// Add top bar
add_action( 'aqualuxe_before_header', 'aqualuxe_top_bar', 10 );

// Add header cart fragments
add_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_cart_fragments', 10, 1 );

// Add header search
add_action( 'aqualuxe_header_right', 'aqualuxe_header_search', 10 );

// Add header cart
add_action( 'aqualuxe_header_right', 'aqualuxe_header_cart', 20 );

// Add header account
add_action( 'aqualuxe_header_right', 'aqualuxe_header_account', 30 );

/**
 * Footer hooks
 */
// Add footer widgets
add_action( 'aqualuxe_footer', 'aqualuxe_footer_widgets', 10 );

// Add footer newsletter
add_action( 'aqualuxe_footer', 'aqualuxe_footer_newsletter', 20 );

// Add footer bottom
add_action( 'aqualuxe_footer', 'aqualuxe_footer_bottom', 30 );

/**
 * Homepage hooks
 */
// Add homepage hero section
add_action( 'aqualuxe_homepage', 'aqualuxe_homepage_hero', 10 );

// Add homepage featured products
add_action( 'aqualuxe_homepage', 'aqualuxe_homepage_featured_products', 20 );

// Add homepage categories
add_action( 'aqualuxe_homepage', 'aqualuxe_homepage_categories', 30 );

// Add homepage about section
add_action( 'aqualuxe_homepage', 'aqualuxe_homepage_about', 40 );

// Add homepage testimonials
add_action( 'aqualuxe_homepage', 'aqualuxe_homepage_testimonials', 50 );

// Add homepage latest posts
add_action( 'aqualuxe_homepage', 'aqualuxe_homepage_latest_posts', 60 );

// Add homepage newsletter
add_action( 'aqualuxe_homepage', 'aqualuxe_homepage_newsletter', 70 );

/**
 * WooCommerce hooks
 */
// Add product quick view
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_product_quick_view', 15 );

// Add product wishlist button
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_product_wishlist_button', 20 );

// Add product compare button
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_product_compare_button', 25 );

// Add product sale flash
add_filter( 'woocommerce_sale_flash', 'aqualuxe_product_sale_flash', 10, 3 );

// Add product gallery zoom, lightbox, and slider support
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_gallery_support' );

// Add related products
add_filter( 'woocommerce_related_products_args', 'aqualuxe_related_products_args', 20 );

// Add upsell products
add_filter( 'woocommerce_upsell_display_args', 'aqualuxe_upsell_products_args', 20 );

// Add cross-sell products
add_filter( 'woocommerce_cross_sells_columns', 'aqualuxe_cross_sell_columns', 20 );

// Add product tabs
add_filter( 'woocommerce_product_tabs', 'aqualuxe_product_tabs', 10 );

// Add AJAX add to cart
add_action( 'wp_ajax_aqualuxe_ajax_add_to_cart', 'aqualuxe_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_aqualuxe_ajax_add_to_cart', 'aqualuxe_ajax_add_to_cart' );

/**
 * Blog hooks
 */
// Add blog featured image
add_action( 'aqualuxe_before_post_content', 'aqualuxe_post_thumbnail', 10 );

// Add blog meta
add_action( 'aqualuxe_post_meta', 'aqualuxe_post_meta', 10 );

// Add blog author box
add_action( 'aqualuxe_after_single_post_content', 'aqualuxe_author_box', 10 );

// Add blog related posts
add_action( 'aqualuxe_after_single_post_content', 'aqualuxe_related_posts', 20 );

// Add blog post navigation
add_action( 'aqualuxe_after_single_post_content', 'aqualuxe_post_navigation', 30 );

/**
 * Top bar
 */
function aqualuxe_top_bar() {
    if ( ! get_theme_mod( 'aqualuxe_show_top_bar', true ) ) {
        return;
    }
    
    get_template_part( 'template-parts/header/top-bar' );
}

/**
 * Header cart fragments
 */
function aqualuxe_cart_fragments( $fragments ) {
    ob_start();
    ?>
    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-contents" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
        <i class="fas fa-shopping-cart"></i>
        <span class="count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></span>
    </a>
    <?php
    $fragments['a.cart-contents'] = ob_get_clean();
    
    return $fragments;
}

/**
 * Header search
 */
function aqualuxe_header_search() {
    if ( ! get_theme_mod( 'aqualuxe_show_header_search', true ) ) {
        return;
    }
    
    get_template_part( 'template-parts/header/search' );
}

/**
 * Header cart
 */
function aqualuxe_header_cart() {
    if ( ! class_exists( 'WooCommerce' ) || ! get_theme_mod( 'aqualuxe_show_header_cart', true ) ) {
        return;
    }
    
    get_template_part( 'template-parts/header/cart' );
}

/**
 * Header account
 */
function aqualuxe_header_account() {
    if ( ! class_exists( 'WooCommerce' ) || ! get_theme_mod( 'aqualuxe_show_header_account', true ) ) {
        return;
    }
    
    get_template_part( 'template-parts/header/account' );
}

/**
 * Footer widgets
 */
function aqualuxe_footer_widgets() {
    if ( ! is_active_sidebar( 'footer-1' ) && ! is_active_sidebar( 'footer-2' ) && ! is_active_sidebar( 'footer-3' ) && ! is_active_sidebar( 'footer-4' ) ) {
        return;
    }
    
    get_template_part( 'template-parts/footer/widgets' );
}

/**
 * Footer newsletter
 */
function aqualuxe_footer_newsletter() {
    if ( ! get_theme_mod( 'aqualuxe_show_footer_newsletter', true ) ) {
        return;
    }
    
    get_template_part( 'template-parts/footer/newsletter' );
}

/**
 * Footer bottom
 */
function aqualuxe_footer_bottom() {
    get_template_part( 'template-parts/footer/bottom' );
}

/**
 * Homepage hero section
 */
function aqualuxe_homepage_hero() {
    if ( ! get_theme_mod( 'aqualuxe_show_homepage_hero', true ) ) {
        return;
    }
    
    get_template_part( 'template-parts/homepage/hero' );
}

/**
 * Homepage featured products
 */
function aqualuxe_homepage_featured_products() {
    if ( ! class_exists( 'WooCommerce' ) || ! get_theme_mod( 'aqualuxe_show_homepage_featured_products', true ) ) {
        return;
    }
    
    get_template_part( 'template-parts/homepage/featured-products' );
}

/**
 * Homepage categories
 */
function aqualuxe_homepage_categories() {
    if ( ! class_exists( 'WooCommerce' ) || ! get_theme_mod( 'aqualuxe_show_homepage_categories', true ) ) {
        return;
    }
    
    get_template_part( 'template-parts/homepage/categories' );
}

/**
 * Homepage about section
 */
function aqualuxe_homepage_about() {
    if ( ! get_theme_mod( 'aqualuxe_show_homepage_about', true ) ) {
        return;
    }
    
    get_template_part( 'template-parts/homepage/about' );
}

/**
 * Homepage testimonials
 */
function aqualuxe_homepage_testimonials() {
    if ( ! get_theme_mod( 'aqualuxe_show_homepage_testimonials', true ) ) {
        return;
    }
    
    get_template_part( 'template-parts/homepage/testimonials' );
}

/**
 * Homepage latest posts
 */
function aqualuxe_homepage_latest_posts() {
    if ( ! get_theme_mod( 'aqualuxe_show_homepage_latest_posts', true ) ) {
        return;
    }
    
    get_template_part( 'template-parts/homepage/latest-posts' );
}

/**
 * Homepage newsletter
 */
function aqualuxe_homepage_newsletter() {
    if ( ! get_theme_mod( 'aqualuxe_show_homepage_newsletter', true ) ) {
        return;
    }
    
    get_template_part( 'template-parts/homepage/newsletter' );
}

/**
 * Product quick view
 */
function aqualuxe_product_quick_view() {
    if ( ! get_theme_mod( 'aqualuxe_show_product_quick_view', true ) ) {
        return;
    }
    
    global $product;
    
    echo '<a href="#" class="quick-view-button" data-product-id="' . esc_attr( $product->get_id() ) . '">' . esc_html__( 'Quick View', 'aqualuxe' ) . '</a>';
}

/**
 * Product wishlist button
 */
function aqualuxe_product_wishlist_button() {
    if ( ! get_theme_mod( 'aqualuxe_show_product_wishlist', true ) ) {
        return;
    }
    
    global $product;
    
    echo '<a href="#" class="wishlist-button" data-product-id="' . esc_attr( $product->get_id() ) . '"><i class="far fa-heart"></i></a>';
}

/**
 * Product compare button
 */
function aqualuxe_product_compare_button() {
    if ( ! get_theme_mod( 'aqualuxe_show_product_compare', true ) ) {
        return;
    }
    
    global $product;
    
    echo '<a href="#" class="compare-button" data-product-id="' . esc_attr( $product->get_id() ) . '"><i class="fas fa-exchange-alt"></i></a>';
}

/**
 * Product sale flash
 */
function aqualuxe_product_sale_flash( $html, $post, $product ) {
    if ( ! $product->is_on_sale() ) {
        return $html;
    }
    
    $sale_style = get_theme_mod( 'aqualuxe_sale_badge_style', 'percentage' );
    
    if ( 'percentage' === $sale_style && $product->get_type() === 'simple' ) {
        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();
        
        if ( $regular_price && $sale_price ) {
            $percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
            return '<span class="onsale">-' . esc_html( $percentage ) . '%</span>';
        }
    }
    
    return '<span class="onsale">' . esc_html__( 'Sale!', 'aqualuxe' ) . '</span>';
}

/**
 * WooCommerce gallery support
 */
function aqualuxe_woocommerce_gallery_support() {
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}

/**
 * Related products args
 */
function aqualuxe_related_products_args( $args ) {
    $columns = get_theme_mod( 'aqualuxe_related_products_columns', 4 );
    $count = get_theme_mod( 'aqualuxe_related_products_count', 4 );
    
    $args['columns'] = $columns;
    $args['posts_per_page'] = $count;
    
    return $args;
}

/**
 * Upsell products args
 */
function aqualuxe_upsell_products_args( $args ) {
    $columns = get_theme_mod( 'aqualuxe_upsell_products_columns', 4 );
    $count = get_theme_mod( 'aqualuxe_upsell_products_count', 4 );
    
    $args['columns'] = $columns;
    $args['posts_per_page'] = $count;
    
    return $args;
}

/**
 * Cross-sell columns
 */
function aqualuxe_cross_sell_columns( $columns ) {
    return get_theme_mod( 'aqualuxe_cross_sell_columns', 2 );
}

/**
 * Product tabs
 */
function aqualuxe_product_tabs( $tabs ) {
    // Add custom tab if enabled
    if ( get_theme_mod( 'aqualuxe_show_custom_tab', false ) ) {
        $tabs['custom_tab'] = array(
            'title'    => get_theme_mod( 'aqualuxe_custom_tab_title', __( 'Custom Tab', 'aqualuxe' ) ),
            'priority' => 30,
            'callback' => 'aqualuxe_custom_tab_content',
        );
    }
    
    // Remove tabs if disabled
    if ( ! get_theme_mod( 'aqualuxe_show_description_tab', true ) && isset( $tabs['description'] ) ) {
        unset( $tabs['description'] );
    }
    
    if ( ! get_theme_mod( 'aqualuxe_show_additional_information_tab', true ) && isset( $tabs['additional_information'] ) ) {
        unset( $tabs['additional_information'] );
    }
    
    if ( ! get_theme_mod( 'aqualuxe_show_reviews_tab', true ) && isset( $tabs['reviews'] ) ) {
        unset( $tabs['reviews'] );
    }
    
    return $tabs;
}

/**
 * Custom tab content
 */
function aqualuxe_custom_tab_content() {
    echo wp_kses_post( get_theme_mod( 'aqualuxe_custom_tab_content', '' ) );
}

/**
 * AJAX add to cart
 */
function aqualuxe_ajax_add_to_cart() {
    check_ajax_referer( 'aqualuxe-nonce', 'nonce' );
    
    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    $quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;
    $variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
    $variation = isset( $_POST['variation'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['variation'] ) ) : array();
    
    if ( $variation_id > 0 ) {
        WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
    } else {
        WC()->cart->add_to_cart( $product_id, $quantity );
    }
    
    $cart_count = WC()->cart->get_cart_contents_count();
    $cart_total = WC()->cart->get_cart_total();
    
    wp_send_json(
        array(
            'success' => true,
            'message' => esc_html__( 'Product added to cart successfully!', 'aqualuxe' ),
            'cart_count' => $cart_count,
            'cart_total' => $cart_total,
        )
    );
}

/**
 * Blog post thumbnail
 */
function aqualuxe_post_thumbnail() {
    if ( ! has_post_thumbnail() ) {
        return;
    }
    
    if ( is_singular() ) :
        ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail( 'aqualuxe-featured', array( 'class' => 'img-fluid' ) ); ?>
        </div>
        <?php
    else :
        ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'aqualuxe-blog-thumbnail', array( 'class' => 'img-fluid' ) ); ?>
            </a>
        </div>
        <?php
    endif;
}

/**
 * Blog post meta
 */
function aqualuxe_post_meta() {
    if ( 'post' !== get_post_type() ) {
        return;
    }
    
    echo '<div class="entry-meta">';
    aqualuxe_posted_on();
    aqualuxe_posted_by();
    echo '</div>';
}

/**
 * Blog author box
 */
function aqualuxe_author_box() {
    if ( ! is_singular( 'post' ) || ! get_theme_mod( 'aqualuxe_show_author_box', true ) ) {
        return;
    }
    
    get_template_part( 'template-parts/content/author-box' );
}

/**
 * Blog related posts
 */
function aqualuxe_related_posts() {
    if ( ! is_singular( 'post' ) || ! get_theme_mod( 'aqualuxe_show_related_posts', true ) ) {
        return;
    }
    
    get_template_part( 'template-parts/content/related-posts' );
}

/**
 * Blog post navigation
 */
function aqualuxe_post_navigation() {
    if ( ! is_singular( 'post' ) || ! get_theme_mod( 'aqualuxe_show_post_navigation', true ) ) {
        return;
    }
    
    the_post_navigation(
        array(
            'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous Post', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
            'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next Post', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
        )
    );
}