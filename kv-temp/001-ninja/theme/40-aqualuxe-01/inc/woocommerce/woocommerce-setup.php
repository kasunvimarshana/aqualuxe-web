<?php
/**
 * WooCommerce setup functions
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * WooCommerce setup function.
 *
 * @return void
 */
function aqualuxe_woocommerce_setup() {
    // Add theme support for WooCommerce
    add_theme_support( 'woocommerce', array(
        'thumbnail_image_width' => 400,
        'single_image_width'    => 800,
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 1,
            'max_rows'        => 6,
            'default_columns' => 4,
            'min_columns'     => 1,
            'max_columns'     => 6,
        ),
    ) );

    // Add support for WooCommerce features
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // Register WooCommerce sidebars
    register_sidebar(
        array(
            'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
            'id'            => 'shop-sidebar',
            'description'   => esc_html__( 'Add widgets here to appear in your shop sidebar.', 'aqualuxe' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    // Define image sizes for products
    add_image_size( 'aqualuxe-product-thumbnail', 400, 400, true );
    add_image_size( 'aqualuxe-product-gallery', 800, 800, true );
    add_image_size( 'aqualuxe-product-single', 1200, 1200, false );
}
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_setup' );

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
            <?php if ( aqualuxe_display_sidebar() && is_active_sidebar( 'shop-sidebar' ) ) : ?>
                <div class="row has-sidebar">
                    <div class="col-content">
            <?php else : ?>
                <div class="row">
                    <div class="col-full">
            <?php endif; ?>
    <?php
}
add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before' );

/**
 * Add custom WooCommerce wrapper end.
 */
function aqualuxe_woocommerce_wrapper_after() {
    ?>
                    </div><!-- .col-content or .col-full -->
                    
                    <?php if ( aqualuxe_display_sidebar() && is_active_sidebar( 'shop-sidebar' ) ) : ?>
                        <div class="col-sidebar">
                            <?php get_sidebar( 'shop' ); ?>
                        </div>
                    <?php endif; ?>
                </div><!-- .row -->
            </div><!-- .container -->
        </main><!-- #primary -->
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
        <div class="cart-contents-wrapper <?php echo esc_attr( $class ); ?>">
            <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
                <span class="cart-contents-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M4 16V4H2V2h3a1 1 0 0 1 1 1v12h12.438l2-8H8V5h13.72a1 1 0 0 1 .97 1.243l-2.5 10a1 1 0 0 1-.97.757H5a1 1 0 0 1-1-1zm2 7a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm12 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg>
                </span>
                <span class="count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></span>
                <span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
            </a>
        </div>
        <div class="cart-dropdown">
            <?php the_widget( 'WC_Widget_Cart', array( 'title' => '' ) ); ?>
        </div>
    </div>
    <?php
}

/**
 * Display Header Cart Icon.
 *
 * @return void
 */
function aqualuxe_woocommerce_header_cart_icon() {
    ?>
    <div class="site-header-cart-icon">
        <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
            <span class="cart-contents-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M4 16V4H2V2h3a1 1 0 0 1 1 1v12h12.438l2-8H8V5h13.72a1 1 0 0 1 .97 1.243l-2.5 10a1 1 0 0 1-.97.757H5a1 1 0 0 1-1-1zm2 7a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm12 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg>
            </span>
            <span class="count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></span>
        </a>
    </div>
    <?php
}

/**
 * Ensure cart contents update when products are added to the cart via AJAX.
 *
 * @param array $fragments Fragments to refresh via AJAX.
 * @return array Fragments to refresh via AJAX.
 */
function aqualuxe_woocommerce_cart_link_fragment( $fragments ) {
    ob_start();
    aqualuxe_woocommerce_header_cart_icon();
    $fragments['div.site-header-cart-icon'] = ob_get_clean();

    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_link_fragment' );

/**
 * Change number of products per row.
 *
 * @return int Number of products per row.
 */
function aqualuxe_woocommerce_loop_columns() {
    return 4;
}
add_filter( 'loop_shop_columns', 'aqualuxe_woocommerce_loop_columns' );

/**
 * Change number of products per page.
 *
 * @param int $products_per_page Number of products per page.
 * @return int Number of products per page.
 */
function aqualuxe_woocommerce_products_per_page( $products_per_page ) {
    return 12;
}
add_filter( 'loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page' );

/**
 * Add product short description to product loop.
 */
function aqualuxe_woocommerce_after_shop_loop_item_title() {
    global $product;

    if ( $product && $product->get_short_description() ) {
        echo '<div class="product-short-description">' . wp_kses_post( $product->get_short_description() ) . '</div>';
    }
}
add_action( 'woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_after_shop_loop_item_title', 5 );

/**
 * Change the breadcrumb separator.
 *
 * @param array $defaults Breadcrumb defaults.
 * @return array Breadcrumb defaults.
 */
function aqualuxe_woocommerce_breadcrumb_defaults( $defaults ) {
    $defaults['delimiter'] = '<span class="breadcrumb-separator">/</span>';
    $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '">';
    $defaults['wrap_after'] = '</nav>';
    return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumb_defaults' );

/**
 * Add 'product-category-{slug}' class to product categories.
 *
 * @param array $classes Array of classes.
 * @param string $class Class name.
 * @param int $category_id Category ID.
 * @return array Array of classes.
 */
function aqualuxe_woocommerce_product_cat_class( $classes, $class, $category ) {
    $classes[] = 'product-category-' . $category->slug;
    return $classes;
}
add_filter( 'product_cat_class', 'aqualuxe_woocommerce_product_cat_class', 10, 3 );

/**
 * Add 'product-tag-{slug}' class to product tags.
 *
 * @param array $classes Array of classes.
 * @param string $class Class name.
 * @param int $tag_id Tag ID.
 * @return array Array of classes.
 */
function aqualuxe_woocommerce_product_tag_class( $classes, $class, $tag ) {
    $classes[] = 'product-tag-' . $tag->slug;
    return $classes;
}
add_filter( 'product_tag_class', 'aqualuxe_woocommerce_product_tag_class', 10, 3 );

/**
 * Add 'product-type-{type}' class to products.
 *
 * @param array $classes Array of classes.
 * @param object $product Product object.
 * @return array Array of classes.
 */
function aqualuxe_woocommerce_post_class( $classes, $product ) {
    if ( $product ) {
        $classes[] = 'product-type-' . $product->get_type();
    }
    return $classes;
}
add_filter( 'woocommerce_post_class', 'aqualuxe_woocommerce_post_class', 10, 2 );

/**
 * Add support for multi-currency.
 */
function aqualuxe_woocommerce_multi_currency_setup() {
    // Check for WOOCS (WooCommerce Currency Switcher)
    if ( class_exists( 'WOOCS' ) ) {
        // Add support for WOOCS
    }

    // Check for WCML (WooCommerce Multilingual)
    if ( class_exists( 'woocommerce_wpml' ) ) {
        // Add support for WCML
    }
}
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_multi_currency_setup' );

/**
 * Add support for multi-language.
 */
function aqualuxe_woocommerce_multi_language_setup() {
    // Check for WPML
    if ( class_exists( 'SitePress' ) ) {
        // Add support for WPML
    }

    // Check for Polylang
    if ( class_exists( 'Polylang' ) ) {
        // Add support for Polylang
    }
}
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_multi_language_setup' );

/**
 * Add support for multi-vendor.
 */
function aqualuxe_woocommerce_multi_vendor_setup() {
    // Check for WC Vendors
    if ( class_exists( 'WC_Vendors' ) ) {
        // Add support for WC Vendors
    }

    // Check for WC Marketplace
    if ( class_exists( 'WCMp' ) ) {
        // Add support for WC Marketplace
    }

    // Check for Dokan
    if ( class_exists( 'WeDevs_Dokan' ) ) {
        // Add support for Dokan
    }

    // Check for WCFM
    if ( class_exists( 'WCFM' ) ) {
        // Add support for WCFM
    }
}
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_multi_vendor_setup' );

/**
 * Add support for multi-tenant.
 */
function aqualuxe_woocommerce_multi_tenant_setup() {
    // Check for WordPress Multisite
    if ( is_multisite() ) {
        // Add support for WordPress Multisite
    }
}
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_multi_tenant_setup' );