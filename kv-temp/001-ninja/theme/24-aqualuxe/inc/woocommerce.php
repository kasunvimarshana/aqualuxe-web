<?php
/**
 * WooCommerce specific functions and customizations
 *
 * @package AquaLuxe
 */

/**
 * WooCommerce setup function.
 */
function aqualuxe_woocommerce_setup() {
    // Add theme support for WooCommerce features
    add_theme_support( 'woocommerce', array(
        'thumbnail_image_width' => 400,
        'single_image_width'    => 800,
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 1,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 6,
        ),
    ) );
    
    // Enable product gallery features
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_setup' );

/**
 * Disable the default WooCommerce stylesheet.
 */
function aqualuxe_dequeue_woocommerce_styles() {
    // If WooCommerce styles are disabled in the customizer
    if ( get_theme_mod( 'aqualuxe_disable_woocommerce_styles', false ) ) {
        wp_dequeue_style( 'woocommerce-general' );
        wp_dequeue_style( 'woocommerce-layout' );
        wp_dequeue_style( 'woocommerce-smallscreen' );
        wp_dequeue_style( 'woocommerce-inline' );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_dequeue_woocommerce_styles', 99 );

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function aqualuxe_related_products_args( $args ) {
    $defaults = array(
        'posts_per_page' => get_theme_mod( 'aqualuxe_related_products_count', 4 ),
        'columns'        => get_theme_mod( 'aqualuxe_products_per_row', 4 ),
    );

    $args = wp_parse_args( $defaults, $args );

    return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'aqualuxe_related_products_args' );

/**
 * Set number of products per row.
 */
function aqualuxe_loop_columns() {
    return get_theme_mod( 'aqualuxe_products_per_row', 3 );
}
add_filter( 'loop_shop_columns', 'aqualuxe_loop_columns' );

/**
 * Set number of products per page.
 */
function aqualuxe_products_per_page() {
    return get_theme_mod( 'aqualuxe_products_per_page', 12 );
}
add_filter( 'loop_shop_per_page', 'aqualuxe_products_per_page', 20 );

/**
 * Product gallery thumbnail columns.
 *
 * @return integer number of columns.
 */
function aqualuxe_thumbnail_columns() {
    return 5;
}
add_filter( 'woocommerce_product_thumbnails_columns', 'aqualuxe_thumbnail_columns' );

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
            <?php if ( is_product() ) : ?>
                <div class="product-container">
            <?php elseif ( is_shop() || is_product_category() || is_product_tag() ) : ?>
                <div class="shop-container flex flex-wrap">
                    <?php if ( is_active_sidebar( 'shop-sidebar' ) && get_theme_mod( 'aqualuxe_sidebar_position', 'right' ) !== 'none' ) : ?>
                        <div class="shop-content w-full lg:w-3/4 <?php echo get_theme_mod( 'aqualuxe_sidebar_position', 'right' ) === 'left' ? 'lg:order-2' : ''; ?>">
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
 * Add custom WooCommerce wrapper end.
 */
function aqualuxe_woocommerce_wrapper_after() {
    ?>
                <?php if ( is_shop() || is_product_category() || is_product_tag() ) : ?>
                    </div><!-- .shop-content -->
                    
                    <?php if ( is_active_sidebar( 'shop-sidebar' ) && get_theme_mod( 'aqualuxe_sidebar_position', 'right' ) !== 'none' ) : ?>
                        <aside class="shop-sidebar w-full lg:w-1/4 <?php echo get_theme_mod( 'aqualuxe_sidebar_position', 'right' ) === 'left' ? 'lg:order-1' : ''; ?> mt-8 lg:mt-0 <?php echo get_theme_mod( 'aqualuxe_sidebar_position', 'right' ) === 'left' ? 'lg:pr-8' : 'lg:pl-8'; ?>">
                            <?php dynamic_sidebar( 'shop-sidebar' ); ?>
                        </aside>
                    <?php endif; ?>
                <?php endif; ?>
                </div><!-- .shop-container/.product-container/.woocommerce-container -->
        </div><!-- .container -->
    </main><!-- #primary -->
    <?php
}
add_action( 'woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after' );

/**
 * Remove sidebar from single product page.
 */
function aqualuxe_remove_sidebar_product_pages() {
    if ( is_product() ) {
        remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
    }
}
add_action( 'wp', 'aqualuxe_remove_sidebar_product_pages' );

/**
 * Add breadcrumbs before shop loop.
 */
function aqualuxe_woocommerce_breadcrumbs() {
    if ( function_exists( 'aqualuxe_breadcrumbs' ) ) {
        aqualuxe_breadcrumbs();
    }
}
add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_breadcrumbs', 20 );

/**
 * Add result count and catalog ordering in a wrapper.
 */
function aqualuxe_woocommerce_before_shop_loop() {
    echo '<div class="shop-controls flex flex-wrap justify-between items-center mb-6">';
}
add_action( 'woocommerce_before_shop_loop', 'aqualuxe_woocommerce_before_shop_loop', 19 );

/**
 * Close the shop controls wrapper.
 */
function aqualuxe_woocommerce_before_shop_loop_end() {
    echo '</div><!-- .shop-controls -->';
}
add_action( 'woocommerce_before_shop_loop', 'aqualuxe_woocommerce_before_shop_loop_end', 31 );

/**
 * Add a container to the shop loop.
 */
function aqualuxe_woocommerce_product_loop_start( $html ) {
    $columns = get_theme_mod( 'aqualuxe_products_per_row', 3 );
    $column_classes = 'grid grid-cols-2 md:grid-cols-' . $columns . ' gap-6';
    
    return '<ul class="products ' . esc_attr( $column_classes ) . '">';
}
add_filter( 'woocommerce_product_loop_start', 'aqualuxe_woocommerce_product_loop_start' );

/**
 * Add wrapper to product in the loop.
 */
function aqualuxe_woocommerce_before_shop_loop_item() {
    echo '<div class="product-inner bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden">';
}
add_action( 'woocommerce_before_shop_loop_item', 'aqualuxe_woocommerce_before_shop_loop_item', 5 );

/**
 * Close wrapper for product in the loop.
 */
function aqualuxe_woocommerce_after_shop_loop_item() {
    echo '</div><!-- .product-inner -->';
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_after_shop_loop_item', 50 );

/**
 * Add wrapper for product image.
 */
function aqualuxe_woocommerce_before_shop_loop_item_title() {
    echo '<div class="product-image-wrapper relative overflow-hidden">';
    
    // Add quick view button if enabled
    if ( get_theme_mod( 'aqualuxe_enable_quick_view', true ) ) {
        echo '<div class="quick-view-button absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity bg-black bg-opacity-20">';
        echo '<a href="#" class="quick-view bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 hover:text-primary dark:hover:text-primary-light px-4 py-2 rounded-full text-sm font-medium transition-colors" data-product-id="' . esc_attr( get_the_ID() ) . '">' . esc_html__( 'Quick View', 'aqualuxe' ) . '</a>';
        echo '</div>';
    }
}
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_before_shop_loop_item_title', 5 );

/**
 * Close wrapper for product image.
 */
function aqualuxe_woocommerce_before_shop_loop_item_title_end() {
    echo '</div><!-- .product-image-wrapper -->';
    echo '<div class="product-details p-4">';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_before_shop_loop_item_title_end', 15 );

/**
 * Close product details wrapper.
 */
function aqualuxe_woocommerce_after_shop_loop_item_title() {
    echo '</div><!-- .product-details -->';
}
add_action( 'woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_after_shop_loop_item_title', 15 );

/**
 * Add wishlist button if enabled.
 */
function aqualuxe_add_wishlist_button() {
    if ( get_theme_mod( 'aqualuxe_enable_wishlist', true ) ) {
        global $product;
        
        echo '<div class="wishlist-button absolute top-2 right-2 z-10">';
        echo '<button type="button" class="add-to-wishlist bg-white dark:bg-gray-800 text-gray-400 hover:text-red-500 dark:text-gray-300 dark:hover:text-red-400 p-2 rounded-full transition-colors" data-product-id="' . esc_attr( $product->get_id() ) . '">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>';
        echo '</button>';
        echo '</div>';
    }
}
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_add_wishlist_button', 10 );

/**
 * Modify sale flash.
 */
function aqualuxe_sale_flash( $html, $post, $product ) {
    return '<span class="onsale absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded z-10">' . esc_html__( 'Sale', 'aqualuxe' ) . '</span>';
}
add_filter( 'woocommerce_sale_flash', 'aqualuxe_sale_flash', 10, 3 );

/**
 * Modify add to cart button.
 */
function aqualuxe_loop_add_to_cart_args( $args, $product ) {
    $args['class'] = str_replace( 'button', 'button bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md text-sm font-medium transition-colors', $args['class'] );
    
    return $args;
}
add_filter( 'woocommerce_loop_add_to_cart_args', 'aqualuxe_loop_add_to_cart_args', 10, 2 );

/**
 * Add wrapper for add to cart button.
 */
function aqualuxe_woocommerce_after_shop_loop_item_cart_wrapper() {
    echo '<div class="add-to-cart-wrapper mt-4">';
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_after_shop_loop_item_cart_wrapper', 9 );

/**
 * Close wrapper for add to cart button.
 */
function aqualuxe_woocommerce_after_shop_loop_item_cart_wrapper_end() {
    echo '</div><!-- .add-to-cart-wrapper -->';
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_after_shop_loop_item_cart_wrapper_end', 11 );

/**
 * Modify pagination.
 */
function aqualuxe_woocommerce_pagination() {
    echo '<div class="aqualuxe-pagination">';
    woocommerce_pagination();
    echo '</div>';
}
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
add_action( 'woocommerce_after_shop_loop', 'aqualuxe_woocommerce_pagination', 10 );

/**
 * Modify pagination args.
 */
function aqualuxe_woocommerce_pagination_args( $args ) {
    $args['prev_text'] = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>';
    $args['next_text'] = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>';
    
    return $args;
}
add_filter( 'woocommerce_pagination_args', 'aqualuxe_woocommerce_pagination_args' );

/**
 * Single product modifications
 */

/**
 * Modify single product image gallery.
 */
function aqualuxe_woocommerce_single_product_image_gallery_classes( $classes ) {
    $classes[] = 'product-gallery';
    
    return $classes;
}
add_filter( 'woocommerce_single_product_image_gallery_classes', 'aqualuxe_woocommerce_single_product_image_gallery_classes' );

/**
 * Add wrapper for single product summary.
 */
function aqualuxe_woocommerce_before_single_product_summary() {
    echo '<div class="product-details-wrapper grid grid-cols-1 md:grid-cols-2 gap-8">';
}
add_action( 'woocommerce_before_single_product_summary', 'aqualuxe_woocommerce_before_single_product_summary', 5 );

/**
 * Close wrapper for single product summary.
 */
function aqualuxe_woocommerce_after_single_product_summary() {
    echo '</div><!-- .product-details-wrapper -->';
}
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_after_single_product_summary', 5 );

/**
 * Add social sharing buttons to single product.
 */
function aqualuxe_woocommerce_share() {
    if ( function_exists( 'aqualuxe_social_share' ) ) {
        aqualuxe_social_share();
    }
}
add_action( 'woocommerce_share', 'aqualuxe_woocommerce_share' );

/**
 * Add tabs wrapper.
 */
function aqualuxe_woocommerce_output_product_data_tabs() {
    echo '<div class="product-tabs mt-12">';
    woocommerce_output_product_data_tabs();
    echo '</div><!-- .product-tabs -->';
}
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_output_product_data_tabs', 10 );

/**
 * Add related products wrapper.
 */
function aqualuxe_woocommerce_output_related_products() {
    echo '<div class="related-products mt-12">';
    woocommerce_output_related_products();
    echo '</div><!-- .related-products -->';
}
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_output_related_products', 20 );

/**
 * Add upsell products wrapper.
 */
function aqualuxe_woocommerce_output_upsells() {
    echo '<div class="upsell-products mt-12">';
    woocommerce_upsell_display();
    echo '</div><!-- .upsell-products -->';
}
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_output_upsells', 15 );

/**
 * Modify product tabs.
 */
function aqualuxe_woocommerce_product_tabs( $tabs ) {
    // Add custom tab if content exists
    if ( get_post_meta( get_the_ID(), '_aqualuxe_custom_tab_content', true ) ) {
        $tabs['custom_tab'] = array(
            'title'    => get_post_meta( get_the_ID(), '_aqualuxe_custom_tab_title', true ) ?: esc_html__( 'Additional Information', 'aqualuxe' ),
            'priority' => 25,
            'callback' => 'aqualuxe_custom_product_tab_content',
        );
    }
    
    // Add care instructions tab if content exists
    if ( get_post_meta( get_the_ID(), '_aqualuxe_care_instructions', true ) ) {
        $tabs['care_instructions'] = array(
            'title'    => esc_html__( 'Care Instructions', 'aqualuxe' ),
            'priority' => 30,
            'callback' => 'aqualuxe_care_instructions_tab_content',
        );
    }
    
    // Add shipping info tab if content exists
    if ( get_post_meta( get_the_ID(), '_aqualuxe_shipping_info', true ) ) {
        $tabs['shipping_info'] = array(
            'title'    => esc_html__( 'Shipping Information', 'aqualuxe' ),
            'priority' => 35,
            'callback' => 'aqualuxe_shipping_info_tab_content',
        );
    }
    
    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs' );

/**
 * Custom product tab content.
 */
function aqualuxe_custom_product_tab_content() {
    echo wp_kses_post( wpautop( get_post_meta( get_the_ID(), '_aqualuxe_custom_tab_content', true ) ) );
}

/**
 * Care instructions tab content.
 */
function aqualuxe_care_instructions_tab_content() {
    echo wp_kses_post( wpautop( get_post_meta( get_the_ID(), '_aqualuxe_care_instructions', true ) ) );
}

/**
 * Shipping info tab content.
 */
function aqualuxe_shipping_info_tab_content() {
    echo wp_kses_post( wpautop( get_post_meta( get_the_ID(), '_aqualuxe_shipping_info', true ) ) );
}

/**
 * Cart and checkout modifications
 */

/**
 * Add wrapper to cart page.
 */
function aqualuxe_woocommerce_before_cart() {
    echo '<div class="cart-wrapper">';
}
add_action( 'woocommerce_before_cart', 'aqualuxe_woocommerce_before_cart' );

/**
 * Close wrapper for cart page.
 */
function aqualuxe_woocommerce_after_cart() {
    echo '</div><!-- .cart-wrapper -->';
}
add_action( 'woocommerce_after_cart', 'aqualuxe_woocommerce_after_cart' );

/**
 * Add wrapper to checkout page.
 */
function aqualuxe_woocommerce_before_checkout_form() {
    echo '<div class="checkout-wrapper">';
}
add_action( 'woocommerce_before_checkout_form', 'aqualuxe_woocommerce_before_checkout_form', 5 );

/**
 * Close wrapper for checkout page.
 */
function aqualuxe_woocommerce_after_checkout_form() {
    echo '</div><!-- .checkout-wrapper -->';
}
add_action( 'woocommerce_after_checkout_form', 'aqualuxe_woocommerce_after_checkout_form', 50 );

/**
 * Add wrapper to account page.
 */
function aqualuxe_woocommerce_before_account_navigation() {
    echo '<div class="account-wrapper grid grid-cols-1 md:grid-cols-4 gap-8">';
    echo '<div class="account-navigation md:col-span-1">';
}
add_action( 'woocommerce_before_account_navigation', 'aqualuxe_woocommerce_before_account_navigation' );

/**
 * Close navigation wrapper and add content wrapper.
 */
function aqualuxe_woocommerce_after_account_navigation() {
    echo '</div><!-- .account-navigation -->';
    echo '<div class="account-content md:col-span-3">';
}
add_action( 'woocommerce_after_account_navigation', 'aqualuxe_woocommerce_after_account_navigation' );

/**
 * Close content wrapper and account wrapper.
 */
function aqualuxe_woocommerce_account_content_end() {
    echo '</div><!-- .account-content -->';
    echo '</div><!-- .account-wrapper -->';
}
add_action( 'woocommerce_account_content', 'aqualuxe_woocommerce_account_content_end', 99 );

/**
 * Quick View AJAX handler.
 */
function aqualuxe_quick_view_ajax() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-quick-view-nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }
    
    if ( ! isset( $_POST['product_id'] ) ) {
        wp_send_json_error( 'No product ID provided' );
    }
    
    $product_id = absint( $_POST['product_id'] );
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        wp_send_json_error( 'Product not found' );
    }
    
    ob_start();
    ?>
    <div class="quick-view-content grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="quick-view-images">
            <?php if ( $product->get_image_id() ) : ?>
                <img src="<?php echo esc_url( wp_get_attachment_image_url( $product->get_image_id(), 'medium' ) ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" class="w-full h-auto rounded-lg">
            <?php else : ?>
                <img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="<?php esc_attr_e( 'Placeholder', 'aqualuxe' ); ?>" class="w-full h-auto rounded-lg">
            <?php endif; ?>
        </div>
        
        <div class="quick-view-summary">
            <h2 class="product-title text-2xl font-bold mb-2"><?php echo esc_html( $product->get_name() ); ?></h2>
            
            <?php if ( $product->get_rating_count() > 0 ) : ?>
                <div class="product-rating flex items-center mb-3">
                    <?php echo wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ); ?>
                    <span class="text-sm text-gray-500 ml-2">(<?php echo esc_html( $product->get_rating_count() ); ?> <?php esc_html_e( 'reviews', 'aqualuxe' ); ?>)</span>
                </div>
            <?php endif; ?>
            
            <div class="product-price text-xl font-semibold mb-4">
                <?php echo wp_kses_post( $product->get_price_html() ); ?>
            </div>
            
            <div class="product-description mb-6">
                <?php echo wp_kses_post( wpautop( $product->get_short_description() ) ); ?>
            </div>
            
            <?php if ( $product->is_type( 'simple' ) ) : ?>
                <form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype="multipart/form-data">
                    <?php woocommerce_quantity_input( array(), $product ); ?>
                    <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-md font-medium transition-colors">
                        <?php echo esc_html( $product->single_add_to_cart_text() ); ?>
                    </button>
                </form>
            <?php elseif ( $product->is_type( 'variable' ) ) : ?>
                <div class="variations_form">
                    <?php woocommerce_variable_add_to_cart(); ?>
                </div>
            <?php else : ?>
                <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="button alt bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-md font-medium transition-colors">
                    <?php esc_html_e( 'View Product', 'aqualuxe' ); ?>
                </a>
            <?php endif; ?>
            
            <div class="product-meta mt-6">
                <?php if ( $product->get_sku() ) : ?>
                    <div class="product-sku text-sm text-gray-500 mb-1">
                        <span class="font-medium"><?php esc_html_e( 'SKU:', 'aqualuxe' ); ?></span> <?php echo esc_html( $product->get_sku() ); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( $product->get_category_ids() ) : ?>
                    <div class="product-categories text-sm text-gray-500 mb-1">
                        <span class="font-medium"><?php esc_html_e( 'Categories:', 'aqualuxe' ); ?></span> 
                        <?php echo wc_get_product_category_list( $product->get_id(), ', ' ); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( $product->get_tag_ids() ) : ?>
                    <div class="product-tags text-sm text-gray-500">
                        <span class="font-medium"><?php esc_html_e( 'Tags:', 'aqualuxe' ); ?></span> 
                        <?php echo wc_get_product_tag_list( $product->get_id(), ', ' ); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="product-actions mt-6">
                <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="view-details-link text-primary hover:text-primary-dark transition-colors">
                    <?php esc_html_e( 'View Full Details', 'aqualuxe' ); ?> →
                </a>
            </div>
        </div>
    </div>
    <?php
    
    $content = ob_get_clean();
    
    wp_send_json_success( array(
        'content' => $content,
    ) );
}
add_action( 'wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax' );

/**
 * Wishlist AJAX handler.
 */
function aqualuxe_wishlist_ajax() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-wishlist-nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }
    
    if ( ! isset( $_POST['product_id'] ) ) {
        wp_send_json_error( 'No product ID provided' );
    }
    
    $product_id = absint( $_POST['product_id'] );
    $action = isset( $_POST['action_type'] ) ? sanitize_text_field( wp_unslash( $_POST['action_type'] ) ) : 'add';
    
    // Get current wishlist
    $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : array();
    
    if ( ! is_array( $wishlist ) ) {
        $wishlist = array();
    }
    
    if ( $action === 'add' ) {
        if ( ! in_array( $product_id, $wishlist, true ) ) {
            $wishlist[] = $product_id;
        }
        $message = esc_html__( 'Product added to wishlist', 'aqualuxe' );
    } else {
        $wishlist = array_diff( $wishlist, array( $product_id ) );
        $message = esc_html__( 'Product removed from wishlist', 'aqualuxe' );
    }
    
    // Set cookie
    setcookie( 'aqualuxe_wishlist', json_encode( $wishlist ), time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
    
    wp_send_json_success( array(
        'message'  => $message,
        'wishlist' => $wishlist,
    ) );
}
add_action( 'wp_ajax_aqualuxe_wishlist', 'aqualuxe_wishlist_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_wishlist', 'aqualuxe_wishlist_ajax' );

/**
 * Add custom product meta boxes.
 */
function aqualuxe_add_product_meta_boxes() {
    add_meta_box(
        'aqualuxe_product_options',
        esc_html__( 'AquaLuxe Product Options', 'aqualuxe' ),
        'aqualuxe_product_options_callback',
        'product',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'aqualuxe_add_product_meta_boxes' );

/**
 * Product options meta box callback.
 */
function aqualuxe_product_options_callback( $post ) {
    wp_nonce_field( 'aqualuxe_product_options', 'aqualuxe_product_options_nonce' );
    
    $custom_tab_title = get_post_meta( $post->ID, '_aqualuxe_custom_tab_title', true );
    $custom_tab_content = get_post_meta( $post->ID, '_aqualuxe_custom_tab_content', true );
    $care_instructions = get_post_meta( $post->ID, '_aqualuxe_care_instructions', true );
    $shipping_info = get_post_meta( $post->ID, '_aqualuxe_shipping_info', true );
    ?>
    <div class="aqualuxe-product-options">
        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_custom_tab_title"><?php esc_html_e( 'Custom Tab Title', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe_custom_tab_title" name="aqualuxe_custom_tab_title" value="<?php echo esc_attr( $custom_tab_title ); ?>" class="widefat">
            <p class="description"><?php esc_html_e( 'Enter the title for the custom product tab.', 'aqualuxe' ); ?></p>
        </div>
        
        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_custom_tab_content"><?php esc_html_e( 'Custom Tab Content', 'aqualuxe' ); ?></label>
            <?php
            wp_editor(
                $custom_tab_content,
                'aqualuxe_custom_tab_content',
                array(
                    'textarea_name' => 'aqualuxe_custom_tab_content',
                    'textarea_rows' => 10,
                    'media_buttons' => true,
                )
            );
            ?>
            <p class="description"><?php esc_html_e( 'Enter the content for the custom product tab.', 'aqualuxe' ); ?></p>
        </div>
        
        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_care_instructions"><?php esc_html_e( 'Care Instructions', 'aqualuxe' ); ?></label>
            <?php
            wp_editor(
                $care_instructions,
                'aqualuxe_care_instructions',
                array(
                    'textarea_name' => 'aqualuxe_care_instructions',
                    'textarea_rows' => 10,
                    'media_buttons' => true,
                )
            );
            ?>
            <p class="description"><?php esc_html_e( 'Enter care instructions for this product.', 'aqualuxe' ); ?></p>
        </div>
        
        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_shipping_info"><?php esc_html_e( 'Shipping Information', 'aqualuxe' ); ?></label>
            <?php
            wp_editor(
                $shipping_info,
                'aqualuxe_shipping_info',
                array(
                    'textarea_name' => 'aqualuxe_shipping_info',
                    'textarea_rows' => 10,
                    'media_buttons' => true,
                )
            );
            ?>
            <p class="description"><?php esc_html_e( 'Enter shipping information for this product.', 'aqualuxe' ); ?></p>
        </div>
    </div>
    <style>
        .aqualuxe-meta-field {
            margin-bottom: 20px;
        }
        .aqualuxe-meta-field label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .aqualuxe-meta-field .description {
            font-style: italic;
            color: #666;
        }
    </style>
    <?php
}

/**
 * Save product meta box data.
 */
function aqualuxe_save_product_options( $post_id ) {
    if ( ! isset( $_POST['aqualuxe_product_options_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aqualuxe_product_options_nonce'] ) ), 'aqualuxe_product_options' ) ) {
        return;
    }
    
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    if ( isset( $_POST['aqualuxe_custom_tab_title'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_custom_tab_title', sanitize_text_field( wp_unslash( $_POST['aqualuxe_custom_tab_title'] ) ) );
    }
    
    if ( isset( $_POST['aqualuxe_custom_tab_content'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_custom_tab_content', wp_kses_post( wp_unslash( $_POST['aqualuxe_custom_tab_content'] ) ) );
    }
    
    if ( isset( $_POST['aqualuxe_care_instructions'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_care_instructions', wp_kses_post( wp_unslash( $_POST['aqualuxe_care_instructions'] ) ) );
    }
    
    if ( isset( $_POST['aqualuxe_shipping_info'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_shipping_info', wp_kses_post( wp_unslash( $_POST['aqualuxe_shipping_info'] ) ) );
    }
}
add_action( 'save_post_product', 'aqualuxe_save_product_options' );