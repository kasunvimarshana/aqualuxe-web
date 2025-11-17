<?php
/**
 * WooCommerce Integration
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce setup function.
 *
 * @return void
 */
function aqualuxe_woocommerce_setup() {
    // Add theme support for WooCommerce.
    add_theme_support( 'woocommerce', array(
        'thumbnail_image_width' => 300,
        'single_image_width'    => 600,
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 1,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 1,
            'max_columns'     => 6,
        ),
    ) );

    // Add support for WooCommerce features.
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // Register WooCommerce sidebars.
    register_sidebar( array(
        'name'          => __( 'Shop Sidebar', 'aqualuxe' ),
        'id'            => 'sidebar-shop',
        'description'   => __( 'Add widgets here to appear in your shop sidebar.', 'aqualuxe' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => __( 'Product Sidebar', 'aqualuxe' ),
        'id'            => 'sidebar-product',
        'description'   => __( 'Add widgets here to appear in your product sidebar.', 'aqualuxe' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_setup' );

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function aqualuxe_woocommerce_scripts() {
    // Enqueue WooCommerce styles.
    wp_enqueue_style( 'aqualuxe-woocommerce-style', get_template_directory_uri() . '/assets/dist/css/woocommerce.css', array(), AQUALUXE_VERSION );

    // Enqueue WooCommerce scripts.
    wp_enqueue_script( 'aqualuxe-woocommerce-script', get_template_directory_uri() . '/assets/dist/js/woocommerce.js', array( 'jquery' ), AQUALUXE_VERSION, true );

    // Localize script.
    wp_localize_script( 'aqualuxe-woocommerce-script', 'woocommerce_params', array(
        'ajax_url'           => admin_url( 'admin-ajax.php' ),
        'add_to_cart_nonce'  => wp_create_nonce( 'aqualuxe-add-to-cart' ),
        'quick_view_nonce'   => wp_create_nonce( 'aqualuxe-quick-view' ),
        'wishlist_nonce'     => wp_create_nonce( 'aqualuxe-wishlist' ),
        'filter_nonce'       => wp_create_nonce( 'aqualuxe-filter' ),
        'is_cart'            => is_cart(),
        'is_checkout'        => is_checkout(),
        'is_product'         => is_product(),
        'is_shop'            => is_shop(),
        'cart_url'           => wc_get_cart_url(),
        'checkout_url'       => wc_get_checkout_url(),
        'shop_url'           => get_permalink( wc_get_page_id( 'shop' ) ),
        'currency_symbol'    => get_woocommerce_currency_symbol(),
        'currency_position'  => get_option( 'woocommerce_currency_pos' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts' );

/**
 * Disable the default WooCommerce stylesheet.
 *
 * @return void
 */
function aqualuxe_dequeue_woocommerce_styles() {
    // If AQUALUXE_USE_WC_STYLES is defined and true, keep the styles.
    if ( defined( 'AQUALUXE_USE_WC_STYLES' ) && AQUALUXE_USE_WC_STYLES ) {
        return;
    }

    // Dequeue WooCommerce styles.
    wp_dequeue_style( 'woocommerce-general' );
    wp_dequeue_style( 'woocommerce-layout' );
    wp_dequeue_style( 'woocommerce-smallscreen' );
    wp_dequeue_style( 'woocommerce_frontend_styles' );
    wp_dequeue_style( 'woocommerce_fancybox_styles' );
    wp_dequeue_style( 'woocommerce_chosen_styles' );
    wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_dequeue_woocommerce_styles', 99 );

/**
 * Related Products Args.
 *
 * @param array $args Related products args.
 * @return array
 */
function aqualuxe_related_products_args( $args ) {
    $defaults = array(
        'posts_per_page' => 4,
        'columns'        => 4,
    );

    $args = wp_parse_args( $defaults, $args );

    return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'aqualuxe_related_products_args' );

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
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
    <?php
}
add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before' );

/**
 * Add custom WooCommerce wrapper end.
 */
function aqualuxe_woocommerce_wrapper_after() {
    ?>
        </main><!-- #main -->
    </div><!-- #primary -->
    <?php
}
add_action( 'woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after' );

/**
 * Add custom WooCommerce sidebar.
 */
function aqualuxe_woocommerce_sidebar() {
    if ( is_product() ) {
        get_sidebar( 'product' );
    } else {
        get_sidebar( 'shop' );
    }
}
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
add_action( 'woocommerce_sidebar', 'aqualuxe_woocommerce_sidebar' );

/**
 * Modify number of products per row.
 *
 * @return int
 */
function aqualuxe_woocommerce_loop_columns() {
    return apply_filters( 'aqualuxe_woocommerce_loop_columns', 4 );
}
add_filter( 'loop_shop_columns', 'aqualuxe_woocommerce_loop_columns' );

/**
 * Modify number of products per page.
 *
 * @param int $products Number of products.
 * @return int
 */
function aqualuxe_woocommerce_products_per_page( $products ) {
    return apply_filters( 'aqualuxe_woocommerce_products_per_page', 12 );
}
add_filter( 'loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param array $classes CSS classes applied to the body tag.
 * @return array
 */
function aqualuxe_woocommerce_active_body_class( $classes ) {
    $classes[] = 'woocommerce-active';

    return $classes;
}
add_filter( 'body_class', 'aqualuxe_woocommerce_active_body_class' );

/**
 * Products per page dropdown.
 *
 * @return void
 */
function aqualuxe_woocommerce_products_per_page_dropdown() {
    $options = apply_filters( 'aqualuxe_woocommerce_products_per_page_options', array(
        12 => __( '12 per page', 'aqualuxe' ),
        24 => __( '24 per page', 'aqualuxe' ),
        36 => __( '36 per page', 'aqualuxe' ),
        48 => __( '48 per page', 'aqualuxe' ),
        -1 => __( 'All', 'aqualuxe' ),
    ) );

    $current = isset( $_GET['products-per-page'] ) ? absint( $_GET['products-per-page'] ) : 12;
    ?>
    <div class="products-per-page">
        <span><?php esc_html_e( 'Show:', 'aqualuxe' ); ?></span>
        <form class="woocommerce-products-per-page" method="get">
            <select name="products-per-page" class="products-per-page-select" onchange="this.form.submit()">
                <?php foreach ( $options as $value => $label ) : ?>
                    <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $current, $value ); ?>><?php echo esc_html( $label ); ?></option>
                <?php endforeach; ?>
            </select>
            <?php wc_query_string_form_fields( null, array( 'products-per-page', 'submit', 'paged', 'product-page' ) ); ?>
        </form>
    </div>
    <?php
}
add_action( 'woocommerce_before_shop_loop', 'aqualuxe_woocommerce_products_per_page_dropdown', 25 );

/**
 * Handle products per page.
 *
 * @param object $query Query object.
 * @return void
 */
function aqualuxe_woocommerce_products_per_page( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'product' ) ) {
        $products_per_page = isset( $_GET['products-per-page'] ) ? absint( $_GET['products-per-page'] ) : 12;
        $query->set( 'posts_per_page', $products_per_page );
    }
}
add_action( 'pre_get_posts', 'aqualuxe_woocommerce_products_per_page' );

/**
 * Add quick view button to product loop.
 *
 * @return void
 */
function aqualuxe_woocommerce_quick_view_button() {
    global $product;
    ?>
    <a href="#" class="button quick-view-button" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
        <?php esc_html_e( 'Quick View', 'aqualuxe' ); ?>
    </a>
    <?php
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_quick_view_button', 15 );

/**
 * Add wishlist button to product loop.
 *
 * @return void
 */
function aqualuxe_woocommerce_wishlist_button() {
    global $product;
    $product_id = $product->get_id();
    $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( stripslashes( $_COOKIE['aqualuxe_wishlist'] ), true ) : array();
    $in_wishlist = in_array( $product_id, $wishlist );
    ?>
    <a href="#" class="button add-to-wishlist <?php echo $in_wishlist ? 'in-wishlist' : ''; ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>" title="<?php echo $in_wishlist ? esc_attr__( 'Remove from Wishlist', 'aqualuxe' ) : esc_attr__( 'Add to Wishlist', 'aqualuxe' ); ?>">
        <span class="wishlist-icon"></span>
    </a>
    <?php
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_wishlist_button', 20 );

/**
 * AJAX add to cart.
 *
 * @return void
 */
function aqualuxe_ajax_add_to_cart() {
    check_ajax_referer( 'aqualuxe-add-to-cart', 'nonce' );

    if ( ! isset( $_POST['product_id'] ) ) {
        wp_send_json_error( array( 'message' => __( 'No product ID provided.', 'aqualuxe' ) ) );
    }

    $product_id = absint( $_POST['product_id'] );
    $quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;

    $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

    if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity ) ) {
        do_action( 'woocommerce_ajax_added_to_cart', $product_id );

        if ( get_option( 'woocommerce_cart_redirect_after_add' ) === 'yes' ) {
            wp_send_json_success( array(
                'redirect' => wc_get_cart_url(),
            ) );
        } else {
            ob_start();
            woocommerce_mini_cart();
            $mini_cart = ob_get_clean();

            wp_send_json_success( array(
                'message'   => __( 'Product added to cart.', 'aqualuxe' ),
                'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array(
                    'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
                ) ),
                'cart_hash' => WC()->cart->get_cart_hash(),
            ) );
        }
    } else {
        wp_send_json_error( array( 'message' => __( 'Error adding product to cart.', 'aqualuxe' ) ) );
    }

    wp_die();
}
add_action( 'wp_ajax_aqualuxe_ajax_add_to_cart', 'aqualuxe_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_aqualuxe_ajax_add_to_cart', 'aqualuxe_ajax_add_to_cart' );

/**
 * AJAX quick view.
 *
 * @return void
 */
function aqualuxe_quick_view() {
    check_ajax_referer( 'aqualuxe-quick-view', 'nonce' );

    if ( ! isset( $_POST['product_id'] ) ) {
        wp_send_json_error( array( 'message' => __( 'No product ID provided.', 'aqualuxe' ) ) );
    }

    $product_id = absint( $_POST['product_id'] );
    $product = wc_get_product( $product_id );

    if ( ! $product ) {
        wp_send_json_error( array( 'message' => __( 'Invalid product.', 'aqualuxe' ) ) );
    }

    ob_start();
    ?>
    <div class="quick-view-content">
        <div class="quick-view-image">
            <?php echo $product->get_image( 'medium_large' ); ?>
        </div>
        <div class="quick-view-details">
            <h2 class="product-title"><?php echo esc_html( $product->get_name() ); ?></h2>
            <div class="product-price"><?php echo $product->get_price_html(); ?></div>
            <div class="product-rating">
                <?php if ( $product->get_average_rating() ) : ?>
                    <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                    <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>#reviews" class="review-link">
                        (<?php printf( _n( '%s review', '%s reviews', $product->get_review_count(), 'aqualuxe' ), esc_html( $product->get_review_count() ) ); ?>)
                    </a>
                <?php endif; ?>
            </div>
            <div class="product-description">
                <?php echo wp_kses_post( $product->get_short_description() ); ?>
            </div>
            <?php if ( $product->is_in_stock() ) : ?>
                <div class="product-add-to-cart">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
            <?php else : ?>
                <div class="product-out-of-stock">
                    <?php esc_html_e( 'Out of stock', 'aqualuxe' ); ?>
                </div>
            <?php endif; ?>
            <div class="product-meta">
                <?php if ( wc_product_sku_enabled() && $product->get_sku() ) : ?>
                    <span class="sku-wrapper"><?php esc_html_e( 'SKU:', 'aqualuxe' ); ?> <span class="sku"><?php echo esc_html( $product->get_sku() ); ?></span></span>
                <?php endif; ?>
                <?php echo wc_get_product_category_list( $product_id, ', ', '<span class="posted-in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'aqualuxe' ) . ' ', '</span>' ); ?>
                <?php echo wc_get_product_tag_list( $product_id, ', ', '<span class="tagged-as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'aqualuxe' ) . ' ', '</span>' ); ?>
            </div>
            <div class="product-actions">
                <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="button view-product">
                    <?php esc_html_e( 'View Details', 'aqualuxe' ); ?>
                </a>
            </div>
        </div>
    </div>
    <?php
    $output = ob_get_clean();

    wp_send_json_success( $output );
    wp_die();
}
add_action( 'wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view' );
add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view' );

/**
 * AJAX add to wishlist.
 *
 * @return void
 */
function aqualuxe_add_to_wishlist() {
    check_ajax_referer( 'aqualuxe-wishlist', 'nonce' );

    if ( ! isset( $_POST['product_id'] ) ) {
        wp_send_json_error( array( 'message' => __( 'No product ID provided.', 'aqualuxe' ) ) );
    }

    $product_id = absint( $_POST['product_id'] );
    $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( stripslashes( $_COOKIE['aqualuxe_wishlist'] ), true ) : array();

    if ( ! in_array( $product_id, $wishlist ) ) {
        $wishlist[] = $product_id;
    }

    setcookie( 'aqualuxe_wishlist', json_encode( $wishlist ), time() + 30 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );

    wp_send_json_success( array(
        'message' => __( 'Product added to wishlist.', 'aqualuxe' ),
        'count'   => count( $wishlist ),
    ) );

    wp_die();
}
add_action( 'wp_ajax_aqualuxe_add_to_wishlist', 'aqualuxe_add_to_wishlist' );
add_action( 'wp_ajax_nopriv_aqualuxe_add_to_wishlist', 'aqualuxe_add_to_wishlist' );

/**
 * AJAX remove from wishlist.
 *
 * @return void
 */
function aqualuxe_remove_from_wishlist() {
    check_ajax_referer( 'aqualuxe-wishlist', 'nonce' );

    if ( ! isset( $_POST['product_id'] ) ) {
        wp_send_json_error( array( 'message' => __( 'No product ID provided.', 'aqualuxe' ) ) );
    }

    $product_id = absint( $_POST['product_id'] );
    $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( stripslashes( $_COOKIE['aqualuxe_wishlist'] ), true ) : array();

    if ( in_array( $product_id, $wishlist ) ) {
        $wishlist = array_diff( $wishlist, array( $product_id ) );
    }

    setcookie( 'aqualuxe_wishlist', json_encode( $wishlist ), time() + 30 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );

    wp_send_json_success( array(
        'message' => __( 'Product removed from wishlist.', 'aqualuxe' ),
        'count'   => count( $wishlist ),
    ) );

    wp_die();
}
add_action( 'wp_ajax_aqualuxe_remove_from_wishlist', 'aqualuxe_remove_from_wishlist' );
add_action( 'wp_ajax_nopriv_aqualuxe_remove_from_wishlist', 'aqualuxe_remove_from_wishlist' );

/**
 * AJAX filter products.
 *
 * @return void
 */
function aqualuxe_filter_products() {
    check_ajax_referer( 'aqualuxe-filter', 'nonce' );

    // Build query args from form data.
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => apply_filters( 'aqualuxe_woocommerce_products_per_page', 12 ),
    );

    // Price range.
    if ( isset( $_POST['min_price'] ) && isset( $_POST['max_price'] ) ) {
        $args['meta_query'][] = array(
            'key'     => '_price',
            'value'   => array( floatval( $_POST['min_price'] ), floatval( $_POST['max_price'] ) ),
            'type'    => 'NUMERIC',
            'compare' => 'BETWEEN',
        );
    }

    // Categories.
    if ( isset( $_POST['product_cat'] ) && ! empty( $_POST['product_cat'] ) ) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $_POST['product_cat'],
        );
    }

    // Tags.
    if ( isset( $_POST['product_tag'] ) && ! empty( $_POST['product_tag'] ) ) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_tag',
            'field'    => 'slug',
            'terms'    => $_POST['product_tag'],
        );
    }

    // Attributes.
    $attributes = wc_get_attribute_taxonomies();
    foreach ( $attributes as $attribute ) {
        $attribute_name = 'pa_' . $attribute->attribute_name;
        if ( isset( $_POST[ $attribute_name ] ) && ! empty( $_POST[ $attribute_name ] ) ) {
            $args['tax_query'][] = array(
                'taxonomy' => $attribute_name,
                'field'    => 'slug',
                'terms'    => $_POST[ $attribute_name ],
            );
        }
    }

    // Orderby.
    if ( isset( $_POST['orderby'] ) && ! empty( $_POST['orderby'] ) ) {
        switch ( $_POST['orderby'] ) {
            case 'price':
                $args['meta_key'] = '_price';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'ASC';
                break;
            case 'price-desc':
                $args['meta_key'] = '_price';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'DESC';
                break;
            case 'popularity':
                $args['meta_key'] = 'total_sales';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'DESC';
                break;
            case 'rating':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'DESC';
                break;
            case 'date':
                $args['orderby'] = 'date';
                $args['order']   = 'DESC';
                break;
            default:
                $args['orderby'] = 'menu_order title';
                $args['order']   = 'ASC';
                break;
        }
    }

    // Run the query.
    $query = new WP_Query( $args );

    ob_start();

    if ( $query->have_posts() ) {
        woocommerce_product_loop_start();

        while ( $query->have_posts() ) {
            $query->the_post();
            wc_get_template_part( 'content', 'product' );
        }

        woocommerce_product_loop_end();
        woocommerce_pagination();
    } else {
        echo '<p class="woocommerce-info">' . esc_html__( 'No products found.', 'aqualuxe' ) . '</p>';
    }

    wp_reset_postdata();

    $output = ob_get_clean();

    wp_send_json_success( $output );
    wp_die();
}
add_action( 'wp_ajax_aqualuxe_filter_products', 'aqualuxe_filter_products' );
add_action( 'wp_ajax_nopriv_aqualuxe_filter_products', 'aqualuxe_filter_products' );

/**
 * Add mini cart to header.
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
        <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
            <span class="cart-icon"></span>
            <span class="count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></span>
        </a>
        <div class="mini-cart">
            <?php the_widget( 'WC_Widget_Cart', array( 'title' => '' ) ); ?>
        </div>
    </div>
    <?php
}

/**
 * Update cart fragments.
 *
 * @param array $fragments Cart fragments.
 * @return array
 */
function aqualuxe_woocommerce_cart_fragments( $fragments ) {
    ob_start();
    ?>
    <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
        <span class="cart-icon"></span>
        <span class="count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></span>
    </a>
    <?php
    $fragments['a.cart-contents'] = ob_get_clean();

    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_fragments' );

/**
 * Add wishlist page.
 */
function aqualuxe_wishlist_page() {
    $wishlist_page = get_page_by_path( 'wishlist' );

    if ( ! $wishlist_page ) {
        $wishlist_page_id = wp_insert_post( array(
            'post_title'     => __( 'Wishlist', 'aqualuxe' ),
            'post_content'   => '[aqualuxe_wishlist]',
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'comment_status' => 'closed',
        ) );
    }
}
add_action( 'after_switch_theme', 'aqualuxe_wishlist_page' );

/**
 * Wishlist shortcode.
 *
 * @return string
 */
function aqualuxe_wishlist_shortcode() {
    $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( stripslashes( $_COOKIE['aqualuxe_wishlist'] ), true ) : array();

    ob_start();

    if ( empty( $wishlist ) ) {
        ?>
        <div class="woocommerce">
            <div class="woocommerce-info">
                <?php esc_html_e( 'Your wishlist is empty.', 'aqualuxe' ); ?>
                <a class="button" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>">
                    <?php esc_html_e( 'Go to shop', 'aqualuxe' ); ?>
                </a>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="woocommerce">
            <table class="shop_table wishlist_table">
                <thead>
                    <tr>
                        <th class="product-remove">&nbsp;</th>
                        <th class="product-thumbnail">&nbsp;</th>
                        <th class="product-name"><?php esc_html_e( 'Product', 'aqualuxe' ); ?></th>
                        <th class="product-price"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></th>
                        <th class="product-stock-status"><?php esc_html_e( 'Stock Status', 'aqualuxe' ); ?></th>
                        <th class="product-add-to-cart">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $wishlist as $product_id ) : ?>
                        <?php $product = wc_get_product( $product_id ); ?>
                        <?php if ( $product ) : ?>
                            <tr>
                                <td class="product-remove">
                                    <a href="#" class="remove remove-from-wishlist" data-product-id="<?php echo esc_attr( $product_id ); ?>">&times;</a>
                                </td>
                                <td class="product-thumbnail">
                                    <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
                                        <?php echo $product->get_image(); ?>
                                    </a>
                                </td>
                                <td class="product-name">
                                    <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
                                        <?php echo esc_html( $product->get_name() ); ?>
                                    </a>
                                </td>
                                <td class="product-price">
                                    <?php echo $product->get_price_html(); ?>
                                </td>
                                <td class="product-stock-status">
                                    <?php if ( $product->is_in_stock() ) : ?>
                                        <span class="in-stock"><?php esc_html_e( 'In stock', 'aqualuxe' ); ?></span>
                                    <?php else : ?>
                                        <span class="out-of-stock"><?php esc_html_e( 'Out of stock', 'aqualuxe' ); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="product-add-to-cart">
                                    <?php if ( $product->is_in_stock() ) : ?>
                                        <?php
                                        echo apply_filters( 'woocommerce_loop_add_to_cart_link',
                                            sprintf( '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                                                esc_url( $product->add_to_cart_url() ),
                                                esc_attr( 1 ),
                                                esc_attr( 'button add_to_cart_button' ),
                                                wc_implode_html_attributes( array(
                                                    'data-product_id'  => $product_id,
                                                    'data-product_sku' => $product->get_sku(),
                                                    'aria-label'       => $product->add_to_cart_description(),
                                                    'rel'              => 'nofollow',
                                                ) ),
                                                esc_html( $product->add_to_cart_text() )
                                            ),
                                            $product
                                        );
                                        ?>
                                    <?php else : ?>
                                        <a class="button" href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
                                            <?php esc_html_e( 'Read more', 'aqualuxe' ); ?>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    return ob_get_clean();
}
add_shortcode( 'aqualuxe_wishlist', 'aqualuxe_wishlist_shortcode' );

/**
 * Add quick view modal to footer.
 *
 * @return void
 */
function aqualuxe_quick_view_modal() {
    ?>
    <div id="quick-view-modal" class="modal">
        <div class="modal-overlay"></div>
        <div class="modal-container">
            <div class="modal-header">
                <button class="modal-close" aria-label="<?php esc_attr_e( 'Close modal', 'aqualuxe' ); ?>">&times;</button>
            </div>
            <div class="modal-content"></div>
        </div>
    </div>
    <?php
}
add_action( 'wp_footer', 'aqualuxe_quick_view_modal' );

/**
 * Check if WooCommerce is active.
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists( 'WooCommerce' );
}

/**
 * Load WooCommerce compatibility file if WooCommerce is active.
 *
 * @return void
 */
function aqualuxe_load_woocommerce_compatibility() {
    if ( aqualuxe_is_woocommerce_active() ) {
        // WooCommerce is active, load all WooCommerce-specific functionality.
        // This is already happening in this file.
    } else {
        // WooCommerce is not active, load fallback functionality.
        require_once get_template_directory() . '/inc/integrations/woocommerce-fallback.php';
    }
}
add_action( 'after_setup_theme', 'aqualuxe_load_woocommerce_compatibility' );