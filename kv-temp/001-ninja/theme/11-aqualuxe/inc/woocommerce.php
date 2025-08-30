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
    
    // Product gallery features
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
    
    // Font Awesome for WooCommerce
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4' );
    
    // WooCommerce custom scripts
    wp_enqueue_script( 'aqualuxe-woocommerce', get_template_directory_uri() . '/assets/js/woocommerce.js', array( 'jquery' ), AQUALUXE_VERSION, true );
    
    // Localize script for AJAX functionality
    wp_localize_script(
        'aqualuxe-woocommerce',
        'aqualuxe_wc_params',
        array(
            'ajax_url'        => admin_url( 'admin-ajax.php' ),
            'wc_ajax_url'     => WC_AJAX::get_endpoint( '%%endpoint%%' ),
            'cart_url'        => wc_get_cart_url(),
            'checkout_url'    => wc_get_checkout_url(),
            'is_cart'         => is_cart(),
            'is_checkout'     => is_checkout(),
            'is_product'      => is_product(),
            'is_shop'         => is_shop(),
            'cart_redirect_after_add' => get_option( 'woocommerce_cart_redirect_after_add' ),
            'i18n_view_cart'  => esc_html__( 'View Cart', 'aqualuxe' ),
            'i18n_checkout'   => esc_html__( 'Checkout', 'aqualuxe' ),
            'i18n_add_to_cart' => esc_html__( 'Add to Cart', 'aqualuxe' ),
            'i18n_added_to_cart' => esc_html__( 'Added to Cart', 'aqualuxe' ),
            'i18n_loading'    => esc_html__( 'Loading...', 'aqualuxe' ),
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
        <main id="primary" class="site-main">
            <div class="container">
                <div class="row">
                    <?php if ( is_active_sidebar( 'shop-sidebar' ) && ( is_shop() || is_product_category() || is_product_tag() ) && ! is_product() ) : ?>
                        <div class="col-lg-9">
                    <?php else : ?>
                        <div class="col-lg-12">
                    <?php endif; ?>
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
                    </div>
                    
                    <?php if ( is_active_sidebar( 'shop-sidebar' ) && ( is_shop() || is_product_category() || is_product_tag() ) && ! is_product() ) : ?>
                        <div class="col-lg-3">
                            <aside id="shop-sidebar" class="widget-area shop-sidebar">
                                <?php dynamic_sidebar( 'shop-sidebar' ); ?>
                            </aside>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main><!-- #main -->
        <?php
    }
}
add_action( 'woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after' );

/**
 * Remove WooCommerce sidebar
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Customize WooCommerce product columns
 */
function aqualuxe_woocommerce_loop_columns() {
    return 3;
}
add_filter( 'loop_shop_columns', 'aqualuxe_woocommerce_loop_columns' );

/**
 * Customize WooCommerce products per page
 */
function aqualuxe_woocommerce_products_per_page() {
    return 12;
}
add_filter( 'loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page' );

/**
 * Customize WooCommerce breadcrumb
 */
function aqualuxe_woocommerce_breadcrumb_defaults( $args ) {
    $args['delimiter'] = '<span class="breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>';
    $args['wrap_before'] = '<nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '">';
    $args['wrap_after'] = '</nav>';
    $args['before'] = '';
    $args['after'] = '';
    $args['home'] = _x( 'Home', 'breadcrumb', 'aqualuxe' );
    
    return $args;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumb_defaults' );

/**
 * Customize WooCommerce product title
 */
function aqualuxe_woocommerce_product_loop_title_classes() {
    return 'woocommerce-loop-product__title product-title';
}
add_filter( 'woocommerce_product_loop_title_classes', 'aqualuxe_woocommerce_product_loop_title_classes' );

/**
 * Customize WooCommerce add to cart button
 */
function aqualuxe_woocommerce_loop_add_to_cart_args( $args, $product ) {
    $args['class'] = implode( ' ', array_filter( array(
        'button',
        'product_type_' . $product->get_type(),
        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
        $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
        'aqualuxe-add-to-cart',
    ) ) );
    
    return $args;
}
add_filter( 'woocommerce_loop_add_to_cart_args', 'aqualuxe_woocommerce_loop_add_to_cart_args', 10, 2 );

/**
 * Add wrapper to product thumbnail
 */
function aqualuxe_woocommerce_before_shop_loop_item_title() {
    echo '<div class="product-thumbnail-wrapper">';
    
    // Quick view button
    echo '<div class="product-actions">';
    echo '<a href="#" class="quick-view-button" data-product-id="' . esc_attr( get_the_ID() ) . '" data-toggle="tooltip" title="' . esc_attr__( 'Quick View', 'aqualuxe' ) . '"><i class="fas fa-eye"></i></a>';
    
    // Wishlist button if YITH WooCommerce Wishlist is active
    if ( defined( 'YITH_WCWL' ) ) {
        echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
    }
    
    // Compare button if YITH WooCommerce Compare is active
    if ( defined( 'YITH_WOOCOMPARE' ) ) {
        echo do_shortcode( '[yith_compare_button]' );
    }
    
    echo '</div>';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_before_shop_loop_item_title', 10 );

/**
 * Close wrapper after product thumbnail
 */
function aqualuxe_woocommerce_after_shop_loop_item_title() {
    echo '</div><!-- .product-thumbnail-wrapper -->';
}
add_action( 'woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_after_shop_loop_item_title', 5 );

/**
 * Add product category to shop loop
 */
function aqualuxe_woocommerce_shop_loop_item_category() {
    global $product;
    
    $categories = wc_get_product_category_list( $product->get_id(), ', ', '<span class="product-category">', '</span>' );
    
    if ( $categories ) {
        echo $categories; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
add_action( 'woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_shop_loop_item_category', 5 );

/**
 * Add sale flash with percentage
 */
function aqualuxe_woocommerce_sale_flash( $html, $post, $product ) {
    if ( $product->is_on_sale() ) {
        if ( $product->is_type( 'variable' ) ) {
            $percentages = array();
            
            $prices = $product->get_variation_prices();
            
            foreach ( $prices['price'] as $key => $price ) {
                if ( $prices['regular_price'][ $key ] !== $price ) {
                    $percentages[] = round( 100 - ( floatval( $prices['sale_price'][ $key ] ) / floatval( $prices['regular_price'][ $key ] ) * 100 ) );
                }
            }
            
            if ( ! empty( $percentages ) ) {
                $percentage = max( $percentages );
            } else {
                $percentage = 0;
            }
        } else {
            $regular_price = (float) $product->get_regular_price();
            $sale_price = (float) $product->get_sale_price();
            
            if ( $regular_price > 0 ) {
                $percentage = round( 100 - ( $sale_price / $regular_price * 100 ) );
            } else {
                $percentage = 0;
            }
        }
        
        if ( $percentage > 0 ) {
            $html = '<span class="onsale">-' . esc_html( $percentage ) . '%</span>';
        }
    }
    
    return $html;
}
add_filter( 'woocommerce_sale_flash', 'aqualuxe_woocommerce_sale_flash', 10, 3 );

/**
 * AJAX Quick View
 */
function aqualuxe_ajax_quick_view() {
    if ( ! isset( $_POST['product_id'] ) ) {
        wp_send_json_error( 'Invalid product ID' );
    }
    
    $product_id = absint( $_POST['product_id'] );
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        wp_send_json_error( 'Product not found' );
    }
    
    ob_start();
    ?>
    <div class="quick-view-content">
        <div class="row">
            <div class="col-md-6">
                <div class="quick-view-images">
                    <?php
                    if ( $product->get_image_id() ) {
                        echo wp_get_attachment_image( $product->get_image_id(), 'woocommerce_single', false, array( 'class' => 'img-fluid' ) );
                    } else {
                        echo wc_placeholder_img( 'woocommerce_single' );
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="quick-view-summary">
                    <h2 class="product_title"><?php echo esc_html( $product->get_name() ); ?></h2>
                    
                    <div class="price">
                        <?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </div>
                    
                    <div class="woocommerce-product-rating">
                        <?php
                        if ( $product->get_rating_count() > 0 ) {
                            wc_get_template( 'single-product/rating.php' );
                        } else {
                            echo '<div class="star-rating">';
                            echo '<span style="width:0%">0%</span>';
                            echo '</div>';
                            echo '<span class="rating-count">' . esc_html__( 'No Reviews', 'aqualuxe' ) . '</span>';
                        }
                        ?>
                    </div>
                    
                    <div class="woocommerce-product-details__short-description">
                        <?php echo apply_filters( 'woocommerce_short_description', $product->get_short_description() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </div>
                    
                    <?php
                    if ( $product->is_in_stock() ) {
                        woocommerce_template_single_add_to_cart();
                    } else {
                        echo '<div class="out-of-stock">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</div>';
                    }
                    ?>
                    
                    <div class="quick-view-meta">
                        <?php if ( wc_product_sku_enabled() && $product->get_sku() ) : ?>
                            <span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'aqualuxe' ); ?> <span class="sku"><?php echo esc_html( $product->get_sku() ); ?></span></span>
                        <?php endif; ?>
                        
                        <?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'aqualuxe' ) . ' ', '</span>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        
                        <?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'aqualuxe' ) . ' ', '</span>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </div>
                    
                    <div class="quick-view-actions">
                        <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="button view-details"><?php esc_html_e( 'View Details', 'aqualuxe' ); ?></a>
                        
                        <?php if ( defined( 'YITH_WCWL' ) ) : ?>
                            <?php echo do_shortcode( '[yith_wcwl_add_to_wishlist]' ); ?>
                        <?php endif; ?>
                        
                        <?php if ( defined( 'YITH_WOOCOMPARE' ) ) : ?>
                            <?php echo do_shortcode( '[yith_compare_button]' ); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    
    $output = ob_get_clean();
    
    wp_send_json_success( $output );
}
add_action( 'wp_ajax_aqualuxe_quick_view', 'aqualuxe_ajax_quick_view' );
add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_ajax_quick_view' );

/**
 * AJAX add to cart
 */
function aqualuxe_ajax_add_to_cart_response() {
    ob_start();
    woocommerce_mini_cart();
    $mini_cart = ob_get_clean();
    
    $data = array(
        'fragments' => apply_filters(
            'woocommerce_add_to_cart_fragments',
            array(
                'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
                '.cart-count' => '<span class="cart-count">' . WC()->cart->get_cart_contents_count() . '</span>',
            )
        ),
        'cart_hash' => WC()->cart->get_cart_hash(),
    );
    
    wp_send_json( $data );
}
add_action( 'wp_ajax_aqualuxe_add_to_cart', 'aqualuxe_ajax_add_to_cart_response' );
add_action( 'wp_ajax_nopriv_aqualuxe_add_to_cart', 'aqualuxe_ajax_add_to_cart_response' );

/**
 * Add product tabs
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
    // Get shipping content from theme options or use default
    $shipping_content = get_theme_mod( 'aqualuxe_shipping_tab_content', '' );
    
    if ( empty( $shipping_content ) ) {
        // Default content
        ?>
        <h3><?php esc_html_e( 'Shipping Information', 'aqualuxe' ); ?></h3>
        <p><?php esc_html_e( 'We ship our aquatic products worldwide using specialized shipping methods to ensure the health and safety of all fish and aquatic life.', 'aqualuxe' ); ?></p>
        
        <ul>
            <li><?php esc_html_e( 'Domestic Shipping: 1-2 business days', 'aqualuxe' ); ?></li>
            <li><?php esc_html_e( 'International Shipping: 3-5 business days', 'aqualuxe' ); ?></li>
            <li><?php esc_html_e( 'All live fish are shipped with heat packs or cooling packs depending on the season', 'aqualuxe' ); ?></li>
            <li><?php esc_html_e( 'We guarantee live arrival for all our aquatic life products', 'aqualuxe' ); ?></li>
        </ul>
        
        <h3><?php esc_html_e( 'Returns Policy', 'aqualuxe' ); ?></h3>
        <p><?php esc_html_e( 'If your fish arrives dead or severely ill, please contact us within 2 hours of delivery with photos for a replacement or refund.', 'aqualuxe' ); ?></p>
        
        <ul>
            <li><?php esc_html_e( 'Non-living products can be returned within 30 days', 'aqualuxe' ); ?></li>
            <li><?php esc_html_e( 'Living products are covered by our live arrival guarantee', 'aqualuxe' ); ?></li>
            <li><?php esc_html_e( 'Custom orders cannot be returned', 'aqualuxe' ); ?></li>
        </ul>
        <?php
    } else {
        echo wp_kses_post( wpautop( $shipping_content ) );
    }
}

/**
 * Custom tab content
 */
function aqualuxe_woocommerce_custom_tab_content() {
    global $product;
    
    // Get custom tab content from product meta or use default
    $custom_tab_content = get_post_meta( $product->get_id(), '_aqualuxe_custom_tab', true );
    
    if ( empty( $custom_tab_content ) ) {
        // Default content
        ?>
        <h3><?php esc_html_e( 'Care Instructions', 'aqualuxe' ); ?></h3>
        <p><?php esc_html_e( 'To ensure the health and longevity of your aquatic pets, please follow these care instructions:', 'aqualuxe' ); ?></p>
        
        <ul>
            <li><?php esc_html_e( 'Maintain proper water parameters suitable for this species', 'aqualuxe' ); ?></li>
            <li><?php esc_html_e( 'Perform regular water changes (20-30% weekly)', 'aqualuxe' ); ?></li>
            <li><?php esc_html_e( 'Use appropriate filtration systems', 'aqualuxe' ); ?></li>
            <li><?php esc_html_e( 'Feed high-quality food appropriate for this species', 'aqualuxe' ); ?></li>
            <li><?php esc_html_e( 'Monitor water temperature and quality regularly', 'aqualuxe' ); ?></li>
        </ul>
        <?php
    } else {
        echo wp_kses_post( wpautop( $custom_tab_content ) );
    }
}

/**
 * Add product meta fields
 */
function aqualuxe_woocommerce_product_custom_fields() {
    global $woocommerce, $post;
    
    echo '<div class="options_group">';
    
    // Custom Tab Content
    woocommerce_wp_textarea_input(
        array(
            'id'          => '_aqualuxe_custom_tab',
            'label'       => esc_html__( 'Care Instructions Tab Content', 'aqualuxe' ),
            'desc_tip'    => true,
            'description' => esc_html__( 'Enter content for the Care Instructions tab. Leave blank to use default content.', 'aqualuxe' ),
        )
    );
    
    // Featured Badge
    woocommerce_wp_checkbox(
        array(
            'id'          => '_aqualuxe_featured_badge',
            'label'       => esc_html__( 'Show Featured Badge', 'aqualuxe' ),
            'description' => esc_html__( 'Check this to show a featured badge on the product.', 'aqualuxe' ),
        )
    );
    
    // New Badge
    woocommerce_wp_checkbox(
        array(
            'id'          => '_aqualuxe_new_badge',
            'label'       => esc_html__( 'Show New Badge', 'aqualuxe' ),
            'description' => esc_html__( 'Check this to show a new badge on the product.', 'aqualuxe' ),
        )
    );
    
    // Badge Text
    woocommerce_wp_text_input(
        array(
            'id'          => '_aqualuxe_badge_text',
            'label'       => esc_html__( 'Custom Badge Text', 'aqualuxe' ),
            'description' => esc_html__( 'Enter text for a custom badge. Leave blank for no custom badge.', 'aqualuxe' ),
        )
    );
    
    echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'aqualuxe_woocommerce_product_custom_fields' );

/**
 * Save product meta fields
 */
function aqualuxe_woocommerce_product_custom_fields_save( $post_id ) {
    // Custom Tab Content
    if ( isset( $_POST['_aqualuxe_custom_tab'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_custom_tab', wp_kses_post( $_POST['_aqualuxe_custom_tab'] ) );
    }
    
    // Featured Badge
    $featured_badge = isset( $_POST['_aqualuxe_featured_badge'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_aqualuxe_featured_badge', $featured_badge );
    
    // New Badge
    $new_badge = isset( $_POST['_aqualuxe_new_badge'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_aqualuxe_new_badge', $new_badge );
    
    // Badge Text
    if ( isset( $_POST['_aqualuxe_badge_text'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_badge_text', sanitize_text_field( $_POST['_aqualuxe_badge_text'] ) );
    }
}
add_action( 'woocommerce_process_product_meta', 'aqualuxe_woocommerce_product_custom_fields_save' );

/**
 * Display product badges
 */
function aqualuxe_woocommerce_show_product_badges() {
    global $product;
    
    // Featured Badge
    if ( 'yes' === get_post_meta( $product->get_id(), '_aqualuxe_featured_badge', true ) ) {
        echo '<span class="product-badge featured-badge">' . esc_html__( 'Featured', 'aqualuxe' ) . '</span>';
    }
    
    // New Badge
    if ( 'yes' === get_post_meta( $product->get_id(), '_aqualuxe_new_badge', true ) ) {
        echo '<span class="product-badge new-badge">' . esc_html__( 'New', 'aqualuxe' ) . '</span>';
    }
    
    // Custom Badge
    $badge_text = get_post_meta( $product->get_id(), '_aqualuxe_badge_text', true );
    if ( ! empty( $badge_text ) ) {
        echo '<span class="product-badge custom-badge">' . esc_html( $badge_text ) . '</span>';
    }
}
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_show_product_badges', 9 );

/**
 * Add product countdown timer
 */
function aqualuxe_woocommerce_countdown_timer() {
    global $product;
    
    // Check if product is on sale and has sale end date
    if ( $product->is_on_sale() ) {
        $sale_end = get_post_meta( $product->get_id(), '_sale_price_dates_to', true );
        
        if ( $sale_end ) {
            $sale_end_date = date( 'Y-m-d H:i:s', $sale_end );
            ?>
            <div class="product-countdown" data-end-date="<?php echo esc_attr( $sale_end_date ); ?>">
                <div class="countdown-label"><?php esc_html_e( 'Offer ends in:', 'aqualuxe' ); ?></div>
                <div class="countdown-timer">
                    <div class="countdown-item">
                        <span class="days">00</span>
                        <span class="countdown-label"><?php esc_html_e( 'Days', 'aqualuxe' ); ?></span>
                    </div>
                    <div class="countdown-item">
                        <span class="hours">00</span>
                        <span class="countdown-label"><?php esc_html_e( 'Hours', 'aqualuxe' ); ?></span>
                    </div>
                    <div class="countdown-item">
                        <span class="minutes">00</span>
                        <span class="countdown-label"><?php esc_html_e( 'Mins', 'aqualuxe' ); ?></span>
                    </div>
                    <div class="countdown-item">
                        <span class="seconds">00</span>
                        <span class="countdown-label"><?php esc_html_e( 'Secs', 'aqualuxe' ); ?></span>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_countdown_timer', 15 );

/**
 * Add product stock progress bar
 */
function aqualuxe_woocommerce_stock_progress_bar() {
    global $product;
    
    // Check if product is in stock and has stock quantity
    if ( $product->is_in_stock() && ! $product->is_type( 'variable' ) && $product->managing_stock() ) {
        $stock_quantity = $product->get_stock_quantity();
        $total_stock = get_post_meta( $product->get_id(), '_aqualuxe_total_stock', true );
        
        if ( ! $total_stock ) {
            $total_stock = $stock_quantity + 10; // Default if total stock is not set
            update_post_meta( $product->get_id(), '_aqualuxe_total_stock', $total_stock );
        }
        
        $percentage = ( $stock_quantity / $total_stock ) * 100;
        ?>
        <div class="stock-progress-wrapper">
            <div class="stock-progress-info">
                <span class="stock-progress-label"><?php esc_html_e( 'Available:', 'aqualuxe' ); ?></span>
                <span class="stock-progress-value"><?php echo esc_html( $stock_quantity ); ?>/<?php echo esc_html( $total_stock ); ?></span>
            </div>
            <div class="stock-progress-bar">
                <div class="stock-progress-fill" style="width: <?php echo esc_attr( $percentage ); ?>%"></div>
            </div>
        </div>
        <?php
    }
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_stock_progress_bar', 25 );

/**
 * Add total stock field to product inventory settings
 */
function aqualuxe_woocommerce_product_inventory_fields() {
    global $post;
    
    echo '<div class="options_group">';
    
    woocommerce_wp_text_input(
        array(
            'id'          => '_aqualuxe_total_stock',
            'label'       => esc_html__( 'Total Stock', 'aqualuxe' ),
            'desc_tip'    => true,
            'description' => esc_html__( 'Enter the total stock quantity for the progress bar display.', 'aqualuxe' ),
            'type'        => 'number',
            'custom_attributes' => array(
                'step' => '1',
                'min'  => '0',
            ),
        )
    );
    
    echo '</div>';
}
add_action( 'woocommerce_product_options_inventory_product_data', 'aqualuxe_woocommerce_product_inventory_fields' );

/**
 * Save total stock field
 */
function aqualuxe_woocommerce_product_inventory_fields_save( $post_id ) {
    if ( isset( $_POST['_aqualuxe_total_stock'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_total_stock', absint( $_POST['_aqualuxe_total_stock'] ) );
    }
}
add_action( 'woocommerce_process_product_meta', 'aqualuxe_woocommerce_product_inventory_fields_save' );

/**
 * Add estimated delivery date
 */
function aqualuxe_woocommerce_estimated_delivery() {
    // Get delivery days from theme options or use default
    $min_days = get_theme_mod( 'aqualuxe_delivery_min_days', 3 );
    $max_days = get_theme_mod( 'aqualuxe_delivery_max_days', 7 );
    
    $min_date = date_i18n( get_option( 'date_format' ), strtotime( "+{$min_days} days" ) );
    $max_date = date_i18n( get_option( 'date_format' ), strtotime( "+{$max_days} days" ) );
    
    echo '<div class="estimated-delivery">';
    echo '<i class="fas fa-shipping-fast"></i> ';
    printf(
        /* translators: %1$s: minimum delivery date, %2$s: maximum delivery date */
        esc_html__( 'Estimated delivery: %1$s - %2$s', 'aqualuxe' ),
        '<strong>' . esc_html( $min_date ) . '</strong>',
        '<strong>' . esc_html( $max_date ) . '</strong>'
    );
    echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_estimated_delivery', 30 );

/**
 * Add product guarantee
 */
function aqualuxe_woocommerce_product_guarantee() {
    // Get guarantee text from theme options or use default
    $guarantee_text = get_theme_mod( 'aqualuxe_product_guarantee', '' );
    
    if ( empty( $guarantee_text ) ) {
        $guarantee_text = esc_html__( 'Live Arrival Guarantee: We guarantee that your fish will arrive alive and healthy.', 'aqualuxe' );
    }
    
    echo '<div class="product-guarantee">';
    echo '<i class="fas fa-shield-alt"></i> ';
    echo wp_kses_post( $guarantee_text );
    echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_guarantee', 35 );

/**
 * Add product trust badges
 */
function aqualuxe_woocommerce_trust_badges() {
    echo '<div class="trust-badges">';
    echo '<div class="trust-badges-title">' . esc_html__( 'Guaranteed Safe Checkout', 'aqualuxe' ) . '</div>';
    echo '<div class="trust-badges-icons">';
    
    // Get enabled payment methods from theme options
    $payment_methods = array(
        'visa'       => esc_html__( 'Visa', 'aqualuxe' ),
        'mastercard' => esc_html__( 'Mastercard', 'aqualuxe' ),
        'amex'       => esc_html__( 'American Express', 'aqualuxe' ),
        'discover'   => esc_html__( 'Discover', 'aqualuxe' ),
        'paypal'     => esc_html__( 'PayPal', 'aqualuxe' ),
        'apple-pay'  => esc_html__( 'Apple Pay', 'aqualuxe' ),
        'google-pay' => esc_html__( 'Google Pay', 'aqualuxe' ),
    );
    
    foreach ( $payment_methods as $method => $label ) {
        if ( get_theme_mod( 'aqualuxe_payment_' . $method, true ) ) {
            echo '<span class="payment-icon payment-' . esc_attr( $method ) . '" aria-label="' . esc_attr( $label ) . '"></span>';
        }
    }
    
    echo '</div>';
    echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_trust_badges', 40 );

/**
 * Add product social sharing
 */
function aqualuxe_woocommerce_social_share() {
    global $product;
    
    $product_url = urlencode( get_permalink( $product->get_id() ) );
    $product_title = urlencode( get_the_title( $product->get_id() ) );
    $product_image = urlencode( wp_get_attachment_url( $product->get_image_id() ) );
    
    echo '<div class="product-social-share">';
    echo '<span class="share-title">' . esc_html__( 'Share:', 'aqualuxe' ) . '</span>';
    echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . esc_attr( $product_url ) . '" target="_blank" rel="noopener noreferrer" class="share-facebook" title="' . esc_attr__( 'Share on Facebook', 'aqualuxe' ) . '"><i class="fab fa-facebook-f"></i></a>';
    echo '<a href="https://twitter.com/intent/tweet?url=' . esc_attr( $product_url ) . '&text=' . esc_attr( $product_title ) . '" target="_blank" rel="noopener noreferrer" class="share-twitter" title="' . esc_attr__( 'Share on Twitter', 'aqualuxe' ) . '"><i class="fab fa-twitter"></i></a>';
    echo '<a href="https://pinterest.com/pin/create/button/?url=' . esc_attr( $product_url ) . '&media=' . esc_attr( $product_image ) . '&description=' . esc_attr( $product_title ) . '" target="_blank" rel="noopener noreferrer" class="share-pinterest" title="' . esc_attr__( 'Pin on Pinterest', 'aqualuxe' ) . '"><i class="fab fa-pinterest-p"></i></a>';
    echo '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . esc_attr( $product_url ) . '&title=' . esc_attr( $product_title ) . '" target="_blank" rel="noopener noreferrer" class="share-linkedin" title="' . esc_attr__( 'Share on LinkedIn', 'aqualuxe' ) . '"><i class="fab fa-linkedin-in"></i></a>';
    echo '<a href="mailto:?subject=' . esc_attr( $product_title ) . '&body=' . esc_attr__( 'Check out this product: ', 'aqualuxe' ) . esc_attr( $product_url ) . '" class="share-email" title="' . esc_attr__( 'Share via Email', 'aqualuxe' ) . '"><i class="fas fa-envelope"></i></a>';
    echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_social_share', 50 );

/**
 * Add recently viewed products
 */
function aqualuxe_woocommerce_recently_viewed_products() {
    if ( ! is_product() ) {
        return;
    }
    
    // Get current product ID
    $current_product_id = get_the_ID();
    
    // Get cookie of recently viewed products
    $viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
    $viewed_products = array_filter( array_map( 'absint', $viewed_products ) );
    
    // Remove current product from the list
    $viewed_products = array_diff( $viewed_products, array( $current_product_id ) );
    
    if ( empty( $viewed_products ) ) {
        return;
    }
    
    $args = array(
        'posts_per_page' => 4,
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'post__in'       => $viewed_products,
        'orderby'        => 'post__in',
    );
    
    $products = new WP_Query( $args );
    
    if ( $products->have_posts() ) {
        ?>
        <section class="recently-viewed-products">
            <div class="container">
                <h2 class="section-title"><?php esc_html_e( 'Recently Viewed Products', 'aqualuxe' ); ?></h2>
                <div class="products row">
                    <?php while ( $products->have_posts() ) : $products->the_post(); ?>
                        <div class="col-md-3">
                            <?php wc_get_template_part( 'content', 'product' ); ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php
    }
    
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
        $viewed_products = (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] );
    }
    
    // Remove current product from the list
    $viewed_products = array_diff( $viewed_products, array( $post->ID ) );
    
    // Add current product to the start of the list
    array_unshift( $viewed_products, $post->ID );
    
    // Limit to 15 products
    if ( count( $viewed_products ) > 15 ) {
        $viewed_products = array_slice( $viewed_products, 0, 15 );
    }
    
    // Store in cookie
    wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
}
add_action( 'template_redirect', 'aqualuxe_woocommerce_track_product_view', 20 );

/**
 * Add product specifications tab
 */
function aqualuxe_woocommerce_product_specifications_tab( $tabs ) {
    global $product;
    
    // Check if product has specifications
    $specifications = get_post_meta( $product->get_id(), '_aqualuxe_specifications', true );
    
    if ( ! empty( $specifications ) || is_array( $specifications ) ) {
        $tabs['specifications'] = array(
            'title'    => esc_html__( 'Specifications', 'aqualuxe' ),
            'priority' => 20,
            'callback' => 'aqualuxe_woocommerce_product_specifications_tab_content',
        );
    }
    
    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'aqualuxe_woocommerce_product_specifications_tab' );

/**
 * Product specifications tab content
 */
function aqualuxe_woocommerce_product_specifications_tab_content() {
    global $product;
    
    $specifications = get_post_meta( $product->get_id(), '_aqualuxe_specifications', true );
    
    if ( ! empty( $specifications ) && is_array( $specifications ) ) {
        echo '<div class="product-specifications">';
        echo '<table class="specifications-table">';
        
        foreach ( $specifications as $spec ) {
            if ( ! empty( $spec['name'] ) && ! empty( $spec['value'] ) ) {
                echo '<tr>';
                echo '<th>' . esc_html( $spec['name'] ) . '</th>';
                echo '<td>' . wp_kses_post( $spec['value'] ) . '</td>';
                echo '</tr>';
            }
        }
        
        echo '</table>';
        echo '</div>';
    }
}

/**
 * Add product specifications fields
 */
function aqualuxe_woocommerce_product_specifications_fields() {
    global $woocommerce, $post;
    
    echo '<div class="options_group">';
    echo '<div class="form-field product_specifications_field">';
    echo '<label>' . esc_html__( 'Product Specifications', 'aqualuxe' ) . '</label>';
    echo '<div id="product_specifications">';
    
    $specifications = get_post_meta( $post->ID, '_aqualuxe_specifications', true );
    
    if ( ! empty( $specifications ) && is_array( $specifications ) ) {
        foreach ( $specifications as $index => $spec ) {
            echo '<div class="specification">';
            echo '<input type="text" name="specification_name[' . esc_attr( $index ) . ']" value="' . esc_attr( $spec['name'] ) . '" placeholder="' . esc_attr__( 'Name', 'aqualuxe' ) . '" />';
            echo '<input type="text" name="specification_value[' . esc_attr( $index ) . ']" value="' . esc_attr( $spec['value'] ) . '" placeholder="' . esc_attr__( 'Value', 'aqualuxe' ) . '" />';
            echo '<button type="button" class="button remove_specification">' . esc_html__( 'Remove', 'aqualuxe' ) . '</button>';
            echo '</div>';
        }
    }
    
    echo '</div>';
    echo '<button type="button" class="button add_specification">' . esc_html__( 'Add Specification', 'aqualuxe' ) . '</button>';
    echo '</div>';
    echo '</div>';
    
    // Add JavaScript for adding/removing specifications
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var index = <?php echo ! empty( $specifications ) ? count( $specifications ) : 0; ?>;
            
            $('.add_specification').on('click', function() {
                var html = '<div class="specification">';
                html += '<input type="text" name="specification_name[' + index + ']" placeholder="<?php echo esc_attr__( 'Name', 'aqualuxe' ); ?>" />';
                html += '<input type="text" name="specification_value[' + index + ']" placeholder="<?php echo esc_attr__( 'Value', 'aqualuxe' ); ?>" />';
                html += '<button type="button" class="button remove_specification"><?php echo esc_html__( 'Remove', 'aqualuxe' ); ?></button>';
                html += '</div>';
                
                $('#product_specifications').append(html);
                index++;
            });
            
            $(document).on('click', '.remove_specification', function() {
                $(this).parent('.specification').remove();
            });
        });
    </script>
    <style type="text/css">
        .specification {
            margin-bottom: 10px;
        }
        .specification input {
            width: 40%;
            margin-right: 10px;
        }
        .add_specification {
            margin-top: 10px !important;
        }
    </style>
    <?php
}
add_action( 'woocommerce_product_options_advanced', 'aqualuxe_woocommerce_product_specifications_fields' );

/**
 * Save product specifications fields
 */
function aqualuxe_woocommerce_product_specifications_fields_save( $post_id ) {
    $specifications = array();
    
    if ( isset( $_POST['specification_name'] ) && isset( $_POST['specification_value'] ) ) {
        $names = $_POST['specification_name'];
        $values = $_POST['specification_value'];
        
        foreach ( $names as $index => $name ) {
            if ( ! empty( $name ) && ! empty( $values[ $index ] ) ) {
                $specifications[] = array(
                    'name'  => sanitize_text_field( $name ),
                    'value' => wp_kses_post( $values[ $index ] ),
                );
            }
        }
    }
    
    update_post_meta( $post_id, '_aqualuxe_specifications', $specifications );
}
add_action( 'woocommerce_process_product_meta', 'aqualuxe_woocommerce_product_specifications_fields_save' );

/**
 * Add size guide button
 */
function aqualuxe_woocommerce_size_guide_button() {
    global $product;
    
    // Check if product has size guide
    $size_guide = get_post_meta( $product->get_id(), '_aqualuxe_size_guide', true );
    
    if ( ! empty( $size_guide ) ) {
        echo '<div class="size-guide-wrapper">';
        echo '<a href="#" class="size-guide-button" data-toggle="modal" data-target="#size-guide-modal">';
        echo '<i class="fas fa-ruler"></i> ' . esc_html__( 'Size Guide', 'aqualuxe' );
        echo '</a>';
        echo '</div>';
        
        // Size Guide Modal
        ?>
        <div id="size-guide-modal" class="aqualuxe-modal">
            <div class="aqualuxe-modal-content">
                <span class="aqualuxe-modal-close">&times;</span>
                <div class="aqualuxe-modal-body">
                    <h3><?php esc_html_e( 'Size Guide', 'aqualuxe' ); ?></h3>
                    <?php echo wp_kses_post( wpautop( $size_guide ) ); ?>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action( 'woocommerce_before_add_to_cart_form', 'aqualuxe_woocommerce_size_guide_button' );

/**
 * Add size guide field
 */
function aqualuxe_woocommerce_size_guide_field() {
    global $woocommerce, $post;
    
    echo '<div class="options_group">';
    
    woocommerce_wp_textarea_input(
        array(
            'id'          => '_aqualuxe_size_guide',
            'label'       => esc_html__( 'Size Guide', 'aqualuxe' ),
            'desc_tip'    => true,
            'description' => esc_html__( 'Enter size guide content. HTML is allowed. Leave blank to hide size guide button.', 'aqualuxe' ),
        )
    );
    
    echo '</div>';
}
add_action( 'woocommerce_product_options_advanced', 'aqualuxe_woocommerce_size_guide_field' );

/**
 * Save size guide field
 */
function aqualuxe_woocommerce_size_guide_field_save( $post_id ) {
    if ( isset( $_POST['_aqualuxe_size_guide'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_size_guide', wp_kses_post( $_POST['_aqualuxe_size_guide'] ) );
    }
}
add_action( 'woocommerce_process_product_meta', 'aqualuxe_woocommerce_size_guide_field_save' );

/**
 * Add product inquiry form
 */
function aqualuxe_woocommerce_product_inquiry_form() {
    global $product;
    
    // Check if product inquiry is enabled
    $enable_inquiry = get_theme_mod( 'aqualuxe_enable_product_inquiry', true );
    
    if ( $enable_inquiry ) {
        echo '<div class="product-inquiry-wrapper">';
        echo '<button class="product-inquiry-button" data-toggle="modal" data-target="#product-inquiry-modal">';
        echo '<i class="fas fa-question-circle"></i> ' . esc_html__( 'Ask About This Product', 'aqualuxe' );
        echo '</button>';
        echo '</div>';
        
        // Inquiry Form Modal
        ?>
        <div id="product-inquiry-modal" class="aqualuxe-modal">
            <div class="aqualuxe-modal-content">
                <span class="aqualuxe-modal-close">&times;</span>
                <div class="aqualuxe-modal-body">
                    <h3><?php esc_html_e( 'Product Inquiry', 'aqualuxe' ); ?></h3>
                    <p><?php esc_html_e( 'Have a question about this product? Fill out the form below and we\'ll get back to you as soon as possible.', 'aqualuxe' ); ?></p>
                    
                    <form class="product-inquiry-form">
                        <input type="hidden" name="product_id" value="<?php echo esc_attr( $product->get_id() ); ?>">
                        <input type="hidden" name="product_name" value="<?php echo esc_attr( $product->get_name() ); ?>">
                        
                        <div class="form-group">
                            <label for="inquiry-name"><?php esc_html_e( 'Your Name', 'aqualuxe' ); ?> <span class="required">*</span></label>
                            <input type="text" id="inquiry-name" name="name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="inquiry-email"><?php esc_html_e( 'Your Email', 'aqualuxe' ); ?> <span class="required">*</span></label>
                            <input type="email" id="inquiry-email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="inquiry-phone"><?php esc_html_e( 'Your Phone', 'aqualuxe' ); ?></label>
                            <input type="tel" id="inquiry-phone" name="phone" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="inquiry-message"><?php esc_html_e( 'Your Question', 'aqualuxe' ); ?> <span class="required">*</span></label>
                            <textarea id="inquiry-message" name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="button inquiry-submit"><?php esc_html_e( 'Send Inquiry', 'aqualuxe' ); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_inquiry_form', 60 );

/**
 * Process product inquiry form
 */
function aqualuxe_woocommerce_process_product_inquiry() {
    check_ajax_referer( 'aqualuxe-nonce', 'nonce' );
    
    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    $product_name = isset( $_POST['product_name'] ) ? sanitize_text_field( $_POST['product_name'] ) : '';
    $name = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
    $email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
    $phone = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';
    
    if ( empty( $product_id ) || empty( $name ) || empty( $email ) || empty( $message ) ) {
        wp_send_json_error( esc_html__( 'Please fill in all required fields.', 'aqualuxe' ) );
    }
    
    $to = get_option( 'admin_email' );
    $subject = sprintf( esc_html__( 'Product Inquiry: %s', 'aqualuxe' ), $product_name );
    
    $body = sprintf( esc_html__( 'Product: %s (ID: %d)', 'aqualuxe' ), $product_name, $product_id ) . "\n\n";
    $body .= sprintf( esc_html__( 'Name: %s', 'aqualuxe' ), $name ) . "\n";
    $body .= sprintf( esc_html__( 'Email: %s', 'aqualuxe' ), $email ) . "\n";
    
    if ( ! empty( $phone ) ) {
        $body .= sprintf( esc_html__( 'Phone: %s', 'aqualuxe' ), $phone ) . "\n";
    }
    
    $body .= sprintf( esc_html__( 'Message: %s', 'aqualuxe' ), $message ) . "\n";
    
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    );
    
    $sent = wp_mail( $to, $subject, $body, $headers );
    
    if ( $sent ) {
        wp_send_json_success( esc_html__( 'Your inquiry has been sent successfully. We will get back to you soon.', 'aqualuxe' ) );
    } else {
        wp_send_json_error( esc_html__( 'There was an error sending your inquiry. Please try again later.', 'aqualuxe' ) );
    }
}
add_action( 'wp_ajax_aqualuxe_product_inquiry', 'aqualuxe_woocommerce_process_product_inquiry' );
add_action( 'wp_ajax_nopriv_aqualuxe_product_inquiry', 'aqualuxe_woocommerce_process_product_inquiry' );

/**
 * Add product video
 */
function aqualuxe_woocommerce_product_video() {
    global $product;
    
    $video_url = get_post_meta( $product->get_id(), '_aqualuxe_product_video', true );
    
    if ( ! empty( $video_url ) ) {
        echo '<div class="product-video-wrapper">';
        echo '<h3>' . esc_html__( 'Product Video', 'aqualuxe' ) . '</h3>';
        
        // Check if it's a YouTube or Vimeo URL
        if ( strpos( $video_url, 'youtube.com' ) !== false || strpos( $video_url, 'youtu.be' ) !== false ) {
            // Extract YouTube video ID
            preg_match( '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video_url, $matches );
            
            if ( ! empty( $matches[1] ) ) {
                echo '<div class="product-video youtube">';
                echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . esc_attr( $matches[1] ) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                echo '</div>';
            }
        } elseif ( strpos( $video_url, 'vimeo.com' ) !== false ) {
            // Extract Vimeo video ID
            preg_match( '/vimeo\.com\/(?:video\/)?([0-9]+)/', $video_url, $matches );
            
            if ( ! empty( $matches[1] ) ) {
                echo '<div class="product-video vimeo">';
                echo '<iframe src="https://player.vimeo.com/video/' . esc_attr( $matches[1] ) . '" width="560" height="315" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
                echo '</div>';
            }
        } else {
            // Self-hosted video
            echo '<div class="product-video self-hosted">';
            echo do_shortcode( '[video src="' . esc_url( $video_url ) . '" width="560" height="315"]' );
            echo '</div>';
        }
        
        echo '</div>';
    }
}
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_video', 15 );

/**
 * Add product video field
 */
function aqualuxe_woocommerce_product_video_field() {
    global $woocommerce, $post;
    
    echo '<div class="options_group">';
    
    woocommerce_wp_text_input(
        array(
            'id'          => '_aqualuxe_product_video',
            'label'       => esc_html__( 'Product Video URL', 'aqualuxe' ),
            'desc_tip'    => true,
            'description' => esc_html__( 'Enter YouTube, Vimeo, or self-hosted video URL.', 'aqualuxe' ),
            'type'        => 'url',
        )
    );
    
    echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'aqualuxe_woocommerce_product_video_field' );

/**
 * Save product video field
 */
function aqualuxe_woocommerce_product_video_field_save( $post_id ) {
    if ( isset( $_POST['_aqualuxe_product_video'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_product_video', esc_url_raw( $_POST['_aqualuxe_product_video'] ) );
    }
}
add_action( 'woocommerce_process_product_meta', 'aqualuxe_woocommerce_product_video_field_save' );

/**
 * Add product 360 view
 */
function aqualuxe_woocommerce_product_360_view() {
    global $product;
    
    $product_360_images = get_post_meta( $product->get_id(), '_aqualuxe_product_360_images', true );
    
    if ( ! empty( $product_360_images ) && is_array( $product_360_images ) ) {
        echo '<div class="product-360-view-wrapper">';
        echo '<a href="#" class="product-360-view-button" data-toggle="modal" data-target="#product-360-modal">';
        echo '<i class="fas fa-sync-alt"></i> ' . esc_html__( '360° View', 'aqualuxe' );
        echo '</a>';
        echo '</div>';
        
        // 360 View Modal
        ?>
        <div id="product-360-modal" class="aqualuxe-modal">
            <div class="aqualuxe-modal-content aqualuxe-modal-large">
                <span class="aqualuxe-modal-close">&times;</span>
                <div class="aqualuxe-modal-body">
                    <h3><?php esc_html_e( '360° Product View', 'aqualuxe' ); ?></h3>
                    <div class="product-360-view" data-images="<?php echo esc_attr( implode( ',', $product_360_images ) ); ?>">
                        <div class="spinner">
                            <span class="spinner-inner"></span>
                        </div>
                        <div class="product-360-view-container">
                            <img src="<?php echo esc_url( wp_get_attachment_url( $product_360_images[0] ) ); ?>" class="product-360-image" alt="<?php echo esc_attr( $product->get_name() ); ?>">
                        </div>
                        <div class="product-360-view-controls">
                            <a href="#" class="product-360-prev"><i class="fas fa-chevron-left"></i></a>
                            <a href="#" class="product-360-next"><i class="fas fa-chevron-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action( 'woocommerce_before_single_product_summary', 'aqualuxe_woocommerce_product_360_view', 20 );

/**
 * Add product 360 view field
 */
function aqualuxe_woocommerce_product_360_view_field() {
    global $woocommerce, $post;
    
    echo '<div class="options_group">';
    
    ?>
    <div class="form-field product_360_images_field">
        <label><?php esc_html_e( 'Product 360° View Images', 'aqualuxe' ); ?></label>
        <div id="product_360_images_container">
            <ul class="product_360_images">
                <?php
                $product_360_images = get_post_meta( $post->ID, '_aqualuxe_product_360_images', true );
                
                if ( ! empty( $product_360_images ) && is_array( $product_360_images ) ) {
                    foreach ( $product_360_images as $attachment_id ) {
                        $attachment = wp_get_attachment_image( $attachment_id, 'thumbnail' );
                        
                        if ( ! empty( $attachment ) ) {
                            echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">';
                            echo $attachment; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            echo '<ul class="actions"><li><a href="#" class="delete" title="' . esc_attr__( 'Delete image', 'aqualuxe' ) . '">' . esc_html__( 'Delete', 'aqualuxe' ) . '</a></li></ul>';
                            echo '</li>';
                        }
                    }
                }
                ?>
            </ul>
            <input type="hidden" id="product_360_images" name="product_360_images" value="<?php echo esc_attr( implode( ',', ! empty( $product_360_images ) ? $product_360_images : array() ) ); ?>">
            <button type="button" class="button add_product_360_images"><?php esc_html_e( 'Add 360° View Images', 'aqualuxe' ); ?></button>
        </div>
    </div>
    <?php
    
    // Add JavaScript for adding/removing 360 images
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Product 360 images
            var product_360_frame;
            var product_360_images_container = $('#product_360_images_container');
            var product_360_images_list = product_360_images_container.find('ul.product_360_images');
            
            $('.add_product_360_images').on('click', function(event) {
                event.preventDefault();
                
                if (product_360_frame) {
                    product_360_frame.open();
                    return;
                }
                
                product_360_frame = wp.media({
                    title: '<?php echo esc_js( __( 'Add 360° View Images', 'aqualuxe' ) ); ?>',
                    button: {
                        text: '<?php echo esc_js( __( 'Add to gallery', 'aqualuxe' ) ); ?>'
                    },
                    multiple: true
                });
                
                product_360_frame.on('select', function() {
                    var attachments = product_360_frame.state().get('selection').toJSON();
                    var attachment_ids = $('#product_360_images').val();
                    
                    attachment_ids = attachment_ids ? attachment_ids.split(',') : [];
                    
                    $.each(attachments, function(i, attachment) {
                        if ($.inArray(attachment.id.toString(), attachment_ids) === -1) {
                            attachment_ids.push(attachment.id.toString());
                            
                            product_360_images_list.append(
                                '<li class="image" data-attachment_id="' + attachment.id + '">' +
                                '<img src="' + attachment.sizes.thumbnail.url + '" alt="" />' +
                                '<ul class="actions">' +
                                '<li><a href="#" class="delete" title="<?php echo esc_js( __( 'Delete image', 'aqualuxe' ) ); ?>"><?php echo esc_js( __( 'Delete', 'aqualuxe' ) ); ?></a></li>' +
                                '</ul>' +
                                '</li>'
                            );
                        }
                    });
                    
                    $('#product_360_images').val(attachment_ids.join(','));
                });
                
                product_360_frame.open();
            });
            
            // Remove image
            product_360_images_container.on('click', 'a.delete', function(event) {
                event.preventDefault();
                
                var image = $(this).closest('li.image');
                var attachment_id = image.data('attachment_id');
                var attachment_ids = $('#product_360_images').val().split(',');
                
                // Remove the attachment ID from the array
                attachment_ids = attachment_ids.filter(function(value) {
                    return value != attachment_id;
                });
                
                $('#product_360_images').val(attachment_ids.join(','));
                image.remove();
            });
            
            // Make the product 360 images sortable
            product_360_images_list.sortable({
                items: 'li.image',
                cursor: 'move',
                scrollSensitivity: 40,
                forcePlaceholderSize: true,
                forceHelperSize: false,
                helper: 'clone',
                opacity: 0.65,
                placeholder: 'wc-metabox-sortable-placeholder',
                start: function(event, ui) {
                    ui.item.css('background-color', '#f6f6f6');
                },
                stop: function(event, ui) {
                    ui.item.removeAttr('style');
                },
                update: function() {
                    var attachment_ids = [];
                    
                    product_360_images_list.find('li.image').each(function() {
                        attachment_ids.push($(this).data('attachment_id'));
                    });
                    
                    $('#product_360_images').val(attachment_ids.join(','));
                }
            });
        });
    </script>
    <style type="text/css">
        ul.product_360_images {
            margin: 0;
            padding: 0;
        }
        ul.product_360_images li.image {
            width: 80px;
            height: 80px;
            float: left;
            margin: 0 10px 10px 0;
            position: relative;
            cursor: move;
        }
        ul.product_360_images li.image img {
            width: 100%;
            height: auto;
        }
        ul.product_360_images li.image ul.actions {
            position: absolute;
            top: 0;
            right: 0;
            display: none;
        }
        ul.product_360_images li.image:hover ul.actions {
            display: block;
        }
        ul.product_360_images li.image ul.actions li {
            float: right;
            margin: 0;
        }
        ul.product_360_images li.image ul.actions li a {
            display: block;
            text-indent: -9999px;
            position: relative;
            height: 16px;
            width: 16px;
            font-size: 0;
            background: #000;
            border-radius: 50%;
            color: #fff;
            opacity: 0.75;
        }
        ul.product_360_images li.image ul.actions li a:hover {
            opacity: 1;
        }
        ul.product_360_images li.image ul.actions li a.delete:before {
            content: "\f335";
            font-family: Dashicons;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            text-indent: 0;
            font-size: 16px;
            line-height: 16px;
            text-align: center;
        }
    </style>
    <?php
    
    echo '</div>';
}
add_action( 'woocommerce_product_options_advanced', 'aqualuxe_woocommerce_product_360_view_field' );

/**
 * Save product 360 view field
 */
function aqualuxe_woocommerce_product_360_view_field_save( $post_id ) {
    if ( isset( $_POST['product_360_images'] ) ) {
        $attachment_ids = sanitize_text_field( $_POST['product_360_images'] );
        $attachment_ids = ! empty( $attachment_ids ) ? explode( ',', $attachment_ids ) : array();
        
        update_post_meta( $post_id, '_aqualuxe_product_360_images', $attachment_ids );
    }
}
add_action( 'woocommerce_process_product_meta', 'aqualuxe_woocommerce_product_360_view_field_save' );

/**
 * Add product FAQ
 */
function aqualuxe_woocommerce_product_faq() {
    global $product;
    
    $faqs = get_post_meta( $product->get_id(), '_aqualuxe_product_faqs', true );
    
    if ( ! empty( $faqs ) && is_array( $faqs ) ) {
        echo '<div class="product-faq-wrapper">';
        echo '<h3>' . esc_html__( 'Frequently Asked Questions', 'aqualuxe' ) . '</h3>';
        echo '<div class="product-faq-list">';
        
        foreach ( $faqs as $index => $faq ) {
            if ( ! empty( $faq['question'] ) && ! empty( $faq['answer'] ) ) {
                echo '<div class="product-faq-item">';
                echo '<div class="product-faq-question">';
                echo '<h4>' . esc_html( $faq['question'] ) . '</h4>';
                echo '<span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>';
                echo '</div>';
                echo '<div class="product-faq-answer">';
                echo wp_kses_post( wpautop( $faq['answer'] ) );
                echo '</div>';
                echo '</div>';
            }
        }
        
        echo '</div>';
        echo '</div>';
    }
}
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_faq', 25 );

/**
 * Add product FAQ fields
 */
function aqualuxe_woocommerce_product_faq_fields() {
    global $woocommerce, $post;
    
    echo '<div class="options_group">';
    echo '<div class="form-field product_faqs_field">';
    echo '<label>' . esc_html__( 'Product FAQs', 'aqualuxe' ) . '</label>';
    echo '<div id="product_faqs">';
    
    $faqs = get_post_meta( $post->ID, '_aqualuxe_product_faqs', true );
    
    if ( ! empty( $faqs ) && is_array( $faqs ) ) {
        foreach ( $faqs as $index => $faq ) {
            echo '<div class="faq">';
            echo '<p>';
            echo '<label>' . esc_html__( 'Question', 'aqualuxe' ) . '</label>';
            echo '<input type="text" name="faq_question[' . esc_attr( $index ) . ']" value="' . esc_attr( $faq['question'] ) . '" class="widefat" />';
            echo '</p>';
            echo '<p>';
            echo '<label>' . esc_html__( 'Answer', 'aqualuxe' ) . '</label>';
            echo '<textarea name="faq_answer[' . esc_attr( $index ) . ']" rows="3" class="widefat">' . esc_textarea( $faq['answer'] ) . '</textarea>';
            echo '</p>';
            echo '<p><button type="button" class="button remove_faq">' . esc_html__( 'Remove FAQ', 'aqualuxe' ) . '</button></p>';
            echo '</div>';
        }
    }
    
    echo '</div>';
    echo '<button type="button" class="button add_faq">' . esc_html__( 'Add FAQ', 'aqualuxe' ) . '</button>';
    echo '</div>';
    echo '</div>';
    
    // Add JavaScript for adding/removing FAQs
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var index = <?php echo ! empty( $faqs ) ? count( $faqs ) : 0; ?>;
            
            $('.add_faq').on('click', function() {
                var html = '<div class="faq">';
                html += '<p>';
                html += '<label><?php echo esc_js( __( 'Question', 'aqualuxe' ) ); ?></label>';
                html += '<input type="text" name="faq_question[' + index + ']" class="widefat" />';
                html += '</p>';
                html += '<p>';
                html += '<label><?php echo esc_js( __( 'Answer', 'aqualuxe' ) ); ?></label>';
                html += '<textarea name="faq_answer[' + index + ']" rows="3" class="widefat"></textarea>';
                html += '</p>';
                html += '<p><button type="button" class="button remove_faq"><?php echo esc_js( __( 'Remove FAQ', 'aqualuxe' ) ); ?></button></p>';
                html += '</div>';
                
                $('#product_faqs').append(html);
                index++;
            });
            
            $(document).on('click', '.remove_faq', function() {
                $(this).closest('.faq').remove();
            });
        });
    </script>
    <style type="text/css">
        .faq {
            background: #f9f9f9;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #e5e5e5;
        }
        .faq p {
            margin-top: 0;
        }
        .add_faq {
            margin-top: 10px !important;
        }
    </style>
    <?php
}
add_action( 'woocommerce_product_options_advanced', 'aqualuxe_woocommerce_product_faq_fields' );

/**
 * Save product FAQ fields
 */
function aqualuxe_woocommerce_product_faq_fields_save( $post_id ) {
    $faqs = array();
    
    if ( isset( $_POST['faq_question'] ) && isset( $_POST['faq_answer'] ) ) {
        $questions = $_POST['faq_question'];
        $answers = $_POST['faq_answer'];
        
        foreach ( $questions as $index => $question ) {
            if ( ! empty( $question ) && ! empty( $answers[ $index ] ) ) {
                $faqs[] = array(
                    'question' => sanitize_text_field( $question ),
                    'answer'   => wp_kses_post( $answers[ $index ] ),
                );
            }
        }
    }
    
    update_post_meta( $post_id, '_aqualuxe_product_faqs', $faqs );
}
add_action( 'woocommerce_process_product_meta', 'aqualuxe_woocommerce_product_faq_fields_save' );

/**
 * Add product shipping information
 */
function aqualuxe_woocommerce_product_shipping_info() {
    global $product;
    
    // Get shipping info from theme options or use default
    $shipping_info = get_theme_mod( 'aqualuxe_product_shipping_info', '' );
    
    if ( empty( $shipping_info ) ) {
        $shipping_info = esc_html__( 'Free shipping on orders over $100. Expedited shipping options available at checkout.', 'aqualuxe' );
    }
    
    echo '<div class="product-shipping-info">';
    echo '<i class="fas fa-shipping-fast"></i> ';
    echo wp_kses_post( $shipping_info );
    echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_shipping_info', 31 );

/**
 * Add product return policy
 */
function aqualuxe_woocommerce_product_return_policy() {
    global $product;
    
    // Get return policy from theme options or use default
    $return_policy = get_theme_mod( 'aqualuxe_product_return_policy', '' );
    
    if ( empty( $return_policy ) ) {
        $return_policy = esc_html__( '30-day return policy for non-living products. Live arrival guarantee for all fish.', 'aqualuxe' );
    }
    
    echo '<div class="product-return-policy">';
    echo '<i class="fas fa-undo-alt"></i> ';
    echo wp_kses_post( $return_policy );
    echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_return_policy', 32 );

/**
 * Add product secure payment
 */
function aqualuxe_woocommerce_product_secure_payment() {
    global $product;
    
    // Get secure payment text from theme options or use default
    $secure_payment = get_theme_mod( 'aqualuxe_product_secure_payment', '' );
    
    if ( empty( $secure_payment ) ) {
        $secure_payment = esc_html__( 'Secure payment processing. Your payment information is never stored.', 'aqualuxe' );
    }
    
    echo '<div class="product-secure-payment">';
    echo '<i class="fas fa-lock"></i> ';
    echo wp_kses_post( $secure_payment );
    echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_secure_payment', 33 );

/**
 * Add product custom tabs
 */
function aqualuxe_woocommerce_custom_product_tabs( $tabs ) {
    global $product;
    
    // Get custom tabs from product meta
    $custom_tabs = get_post_meta( $product->get_id(), '_aqualuxe_custom_tabs', true );
    
    if ( ! empty( $custom_tabs ) && is_array( $custom_tabs ) ) {
        foreach ( $custom_tabs as $index => $tab ) {
            if ( ! empty( $tab['title'] ) && ! empty( $tab['content'] ) ) {
                $tabs[ 'custom_tab_' . $index ] = array(
                    'title'    => $tab['title'],
                    'priority' => 50 + $index,
                    'callback' => function() use ( $tab ) {
                        echo '<h2>' . esc_html( $tab['title'] ) . '</h2>';
                        echo wp_kses_post( wpautop( $tab['content'] ) );
                    },
                );
            }
        }
    }
    
    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'aqualuxe_woocommerce_custom_product_tabs' );

/**
 * Add custom product tabs fields
 */
function aqualuxe_woocommerce_custom_product_tabs_fields() {
    global $woocommerce, $post;
    
    echo '<div class="options_group">';
    echo '<div class="form-field product_custom_tabs_field">';
    echo '<label>' . esc_html__( 'Custom Product Tabs', 'aqualuxe' ) . '</label>';
    echo '<div id="product_custom_tabs">';
    
    $custom_tabs = get_post_meta( $post->ID, '_aqualuxe_custom_tabs', true );
    
    if ( ! empty( $custom_tabs ) && is_array( $custom_tabs ) ) {
        foreach ( $custom_tabs as $index => $tab ) {
            echo '<div class="custom-tab">';
            echo '<p>';
            echo '<label>' . esc_html__( 'Tab Title', 'aqualuxe' ) . '</label>';
            echo '<input type="text" name="custom_tab_title[' . esc_attr( $index ) . ']" value="' . esc_attr( $tab['title'] ) . '" class="widefat" />';
            echo '</p>';
            echo '<p>';
            echo '<label>' . esc_html__( 'Tab Content', 'aqualuxe' ) . '</label>';
            echo '<textarea name="custom_tab_content[' . esc_attr( $index ) . ']" rows="5" class="widefat">' . esc_textarea( $tab['content'] ) . '</textarea>';
            echo '</p>';
            echo '<p><button type="button" class="button remove_custom_tab">' . esc_html__( 'Remove Tab', 'aqualuxe' ) . '</button></p>';
            echo '</div>';
        }
    }
    
    echo '</div>';
    echo '<button type="button" class="button add_custom_tab">' . esc_html__( 'Add Custom Tab', 'aqualuxe' ) . '</button>';
    echo '</div>';
    echo '</div>';
    
    // Add JavaScript for adding/removing custom tabs
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var index = <?php echo ! empty( $custom_tabs ) ? count( $custom_tabs ) : 0; ?>;
            
            $('.add_custom_tab').on('click', function() {
                var html = '<div class="custom-tab">';
                html += '<p>';
                html += '<label><?php echo esc_js( __( 'Tab Title', 'aqualuxe' ) ); ?></label>';
                html += '<input type="text" name="custom_tab_title[' + index + ']" class="widefat" />';
                html += '</p>';
                html += '<p>';
                html += '<label><?php echo esc_js( __( 'Tab Content', 'aqualuxe' ) ); ?></label>';
                html += '<textarea name="custom_tab_content[' + index + ']" rows="5" class="widefat"></textarea>';
                html += '</p>';
                html += '<p><button type="button" class="button remove_custom_tab"><?php echo esc_js( __( 'Remove Tab', 'aqualuxe' ) ); ?></button></p>';
                html += '</div>';
                
                $('#product_custom_tabs').append(html);
                index++;
            });
            
            $(document).on('click', '.remove_custom_tab', function() {
                $(this).closest('.custom-tab').remove();
            });
        });
    </script>
    <style type="text/css">
        .custom-tab {
            background: #f9f9f9;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #e5e5e5;
        }
        .custom-tab p {
            margin-top: 0;
        }
        .add_custom_tab {
            margin-top: 10px !important;
        }
    </style>
    <?php
}
add_action( 'woocommerce_product_options_advanced', 'aqualuxe_woocommerce_custom_product_tabs_fields' );

/**
 * Save custom product tabs fields
 */
function aqualuxe_woocommerce_custom_product_tabs_fields_save( $post_id ) {
    $custom_tabs = array();
    
    if ( isset( $_POST['custom_tab_title'] ) && isset( $_POST['custom_tab_content'] ) ) {
        $titles = $_POST['custom_tab_title'];
        $contents = $_POST['custom_tab_content'];
        
        foreach ( $titles as $index => $title ) {
            if ( ! empty( $title ) && ! empty( $contents[ $index ] ) ) {
                $custom_tabs[] = array(
                    'title'   => sanitize_text_field( $title ),
                    'content' => wp_kses_post( $contents[ $index ] ),
                );
            }
        }
    }
    
    update_post_meta( $post_id, '_aqualuxe_custom_tabs', $custom_tabs );
}
add_action( 'woocommerce_process_product_meta', 'aqualuxe_woocommerce_custom_product_tabs_fields_save' );