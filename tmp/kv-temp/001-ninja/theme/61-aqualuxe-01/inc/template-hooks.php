<?php
/**
 * Template hooks
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Header hooks
 */
add_action( 'aqualuxe_header', 'aqualuxe_header_top', 10 );
add_action( 'aqualuxe_header', 'aqualuxe_header_main', 20 );
add_action( 'aqualuxe_header', 'aqualuxe_header_bottom', 30 );

/**
 * Footer hooks
 */
add_action( 'aqualuxe_footer', 'aqualuxe_footer_widgets', 10 );
add_action( 'aqualuxe_footer', 'aqualuxe_footer_info', 20 );
add_action( 'aqualuxe_footer', 'aqualuxe_footer_bottom', 30 );

/**
 * Content hooks
 */
add_action( 'aqualuxe_content_before', 'aqualuxe_breadcrumbs', 10 );
add_action( 'aqualuxe_content_before', 'aqualuxe_page_header', 20 );
add_action( 'aqualuxe_content_after', 'aqualuxe_pagination', 10 );

/**
 * Sidebar hooks
 */
add_action( 'aqualuxe_sidebar', 'aqualuxe_get_sidebar', 10 );

/**
 * Post hooks
 */
add_action( 'aqualuxe_post_before', 'aqualuxe_post_thumbnail', 10 );
add_action( 'aqualuxe_post_header', 'aqualuxe_post_title', 10 );
add_action( 'aqualuxe_post_header', 'aqualuxe_post_meta', 20 );
add_action( 'aqualuxe_post_content', 'aqualuxe_post_content', 10 );
add_action( 'aqualuxe_post_footer', 'aqualuxe_post_tags', 10 );
add_action( 'aqualuxe_post_footer', 'aqualuxe_post_author', 20 );
add_action( 'aqualuxe_post_footer', 'aqualuxe_post_comments', 30 );
add_action( 'aqualuxe_post_after', 'aqualuxe_post_related', 10 );

/**
 * Page hooks
 */
add_action( 'aqualuxe_page_before', 'aqualuxe_page_thumbnail', 10 );
add_action( 'aqualuxe_page_header', 'aqualuxe_page_title', 10 );
add_action( 'aqualuxe_page_content', 'aqualuxe_page_content', 10 );
add_action( 'aqualuxe_page_footer', 'aqualuxe_page_comments', 10 );

/**
 * Comments hooks
 */
add_action( 'aqualuxe_comments_before', 'aqualuxe_comments_title', 10 );
add_action( 'aqualuxe_comments', 'aqualuxe_comments_list', 10 );
add_action( 'aqualuxe_comments', 'aqualuxe_comments_pagination', 20 );
add_action( 'aqualuxe_comments_after', 'aqualuxe_comments_form', 10 );

/**
 * WooCommerce hooks
 */
if ( aqualuxe_is_woocommerce_active() ) {
    add_action( 'aqualuxe_shop_before', 'aqualuxe_shop_header', 10 );
    add_action( 'aqualuxe_shop_before', 'aqualuxe_shop_filters', 20 );
    add_action( 'aqualuxe_shop', 'aqualuxe_shop_products', 10 );
    add_action( 'aqualuxe_shop_after', 'aqualuxe_shop_pagination', 10 );

    add_action( 'aqualuxe_product_before', 'aqualuxe_product_gallery', 10 );
    add_action( 'aqualuxe_product', 'aqualuxe_product_title', 10 );
    add_action( 'aqualuxe_product', 'aqualuxe_product_rating', 20 );
    add_action( 'aqualuxe_product', 'aqualuxe_product_price', 30 );
    add_action( 'aqualuxe_product', 'aqualuxe_product_excerpt', 40 );
    add_action( 'aqualuxe_product', 'aqualuxe_product_add_to_cart', 50 );
    add_action( 'aqualuxe_product', 'aqualuxe_product_meta', 60 );
    add_action( 'aqualuxe_product_after', 'aqualuxe_product_tabs', 10 );
    add_action( 'aqualuxe_product_after', 'aqualuxe_product_related', 20 );
    add_action( 'aqualuxe_product_after', 'aqualuxe_product_upsells', 30 );

    add_action( 'aqualuxe_cart_before', 'aqualuxe_cart_header', 10 );
    add_action( 'aqualuxe_cart', 'aqualuxe_cart_items', 10 );
    add_action( 'aqualuxe_cart', 'aqualuxe_cart_totals', 20 );
    add_action( 'aqualuxe_cart_after', 'aqualuxe_cart_cross_sells', 10 );

    add_action( 'aqualuxe_checkout_before', 'aqualuxe_checkout_header', 10 );
    add_action( 'aqualuxe_checkout', 'aqualuxe_checkout_form', 10 );
    add_action( 'aqualuxe_checkout_after', 'aqualuxe_checkout_order_review', 10 );
}

/**
 * Module hooks
 */
add_action( 'aqualuxe_before_module', 'aqualuxe_module_header', 10 );
add_action( 'aqualuxe_module', 'aqualuxe_module_content', 10 );
add_action( 'aqualuxe_after_module', 'aqualuxe_module_footer', 10 );

/**
 * Header top
 */
function aqualuxe_header_top() {
    if ( get_theme_mod( 'aqualuxe_topbar_enable', true ) ) {
        aqualuxe_get_template_part( 'templates/header/topbar' );
    }
}

/**
 * Header main
 */
function aqualuxe_header_main() {
    $header_layout = get_theme_mod( 'aqualuxe_header_layout', 'default' );
    aqualuxe_get_template_part( 'templates/header/main', $header_layout );
}

/**
 * Header bottom
 */
function aqualuxe_header_bottom() {
    aqualuxe_get_template_part( 'templates/header/bottom' );
}

/**
 * Footer widgets
 */
function aqualuxe_footer_widgets() {
    $footer_widgets_columns = get_theme_mod( 'aqualuxe_footer_widgets_columns', 4 );
    aqualuxe_get_template_part( 'templates/footer/widgets', $footer_widgets_columns );
}

/**
 * Footer info
 */
function aqualuxe_footer_info() {
    aqualuxe_get_template_part( 'templates/footer/info' );
}

/**
 * Footer bottom
 */
function aqualuxe_footer_bottom() {
    aqualuxe_get_template_part( 'templates/footer/bottom' );
}

/**
 * Breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    if ( ! is_front_page() ) {
        echo aqualuxe_get_breadcrumbs();
    }
}

/**
 * Page header
 */
function aqualuxe_page_header() {
    if ( ! is_front_page() ) {
        aqualuxe_get_template_part( 'templates/page/header' );
    }
}

/**
 * Pagination
 */
function aqualuxe_pagination() {
    if ( is_singular() ) {
        return;
    }

    global $wp_query;
    $args = [
        'total'     => $wp_query->max_num_pages,
        'current'   => max( 1, get_query_var( 'paged' ) ),
        'prev_text' => '&larr;',
        'next_text' => '&rarr;',
    ];

    echo aqualuxe_get_pagination( $args );
}

/**
 * Get sidebar
 */
function aqualuxe_get_sidebar() {
    get_sidebar();
}

/**
 * Post thumbnail
 */
function aqualuxe_post_thumbnail() {
    if ( has_post_thumbnail() && get_theme_mod( 'aqualuxe_blog_single_featured_image', true ) ) {
        echo aqualuxe_get_post_thumbnail();
    }
}

/**
 * Post title
 */
function aqualuxe_post_title() {
    the_title( '<h1 class="entry-title">', '</h1>' );
}

/**
 * Post meta
 */
function aqualuxe_post_meta() {
    aqualuxe_get_template_part( 'templates/post/meta' );
}

/**
 * Post content
 */
function aqualuxe_post_content() {
    the_content();
}

/**
 * Post tags
 */
function aqualuxe_post_tags() {
    if ( get_theme_mod( 'aqualuxe_blog_single_tags', true ) ) {
        echo aqualuxe_get_post_tags();
    }
}

/**
 * Post author
 */
function aqualuxe_post_author() {
    if ( get_theme_mod( 'aqualuxe_blog_single_author', true ) ) {
        aqualuxe_get_template_part( 'templates/post/author' );
    }
}

/**
 * Post comments
 */
function aqualuxe_post_comments() {
    if ( get_theme_mod( 'aqualuxe_blog_single_comments', true ) ) {
        comments_template();
    }
}

/**
 * Post related
 */
function aqualuxe_post_related() {
    if ( get_theme_mod( 'aqualuxe_blog_single_related_posts', true ) ) {
        aqualuxe_get_template_part( 'templates/post/related' );
    }
}

/**
 * Page thumbnail
 */
function aqualuxe_page_thumbnail() {
    if ( has_post_thumbnail() ) {
        echo aqualuxe_get_post_thumbnail();
    }
}

/**
 * Page title
 */
function aqualuxe_page_title() {
    the_title( '<h1 class="entry-title">', '</h1>' );
}

/**
 * Page content
 */
function aqualuxe_page_content() {
    the_content();
}

/**
 * Page comments
 */
function aqualuxe_page_comments() {
    if ( comments_open() || get_comments_number() ) {
        comments_template();
    }
}

/**
 * Comments title
 */
function aqualuxe_comments_title() {
    echo '<h2 class="comments-title">';
    printf(
        /* translators: 1: number of comments */
        esc_html( _n( '%1$s Comment', '%1$s Comments', get_comments_number(), 'aqualuxe' ) ),
        esc_html( number_format_i18n( get_comments_number() ) )
    );
    echo '</h2>';
}

/**
 * Comments list
 */
function aqualuxe_comments_list() {
    wp_list_comments(
        [
            'style'       => 'ol',
            'short_ping'  => true,
            'avatar_size' => 60,
        ]
    );
}

/**
 * Comments pagination
 */
function aqualuxe_comments_pagination() {
    the_comments_pagination(
        [
            'prev_text' => '&larr;',
            'next_text' => '&rarr;',
        ]
    );
}

/**
 * Comments form
 */
function aqualuxe_comments_form() {
    comment_form();
}

/**
 * Shop header
 */
function aqualuxe_shop_header() {
    if ( aqualuxe_is_woocommerce_active() ) {
        aqualuxe_get_template_part( 'templates/woocommerce/shop/header' );
    }
}

/**
 * Shop filters
 */
function aqualuxe_shop_filters() {
    if ( aqualuxe_is_woocommerce_active() ) {
        aqualuxe_get_template_part( 'templates/woocommerce/shop/filters' );
    }
}

/**
 * Shop products
 */
function aqualuxe_shop_products() {
    if ( aqualuxe_is_woocommerce_active() ) {
        woocommerce_content();
    }
}

/**
 * Shop pagination
 */
function aqualuxe_shop_pagination() {
    if ( aqualuxe_is_woocommerce_active() ) {
        aqualuxe_get_template_part( 'templates/woocommerce/shop/pagination' );
    }
}

/**
 * Product gallery
 */
function aqualuxe_product_gallery() {
    if ( aqualuxe_is_woocommerce_active() ) {
        woocommerce_show_product_images();
    }
}

/**
 * Product title
 */
function aqualuxe_product_title() {
    if ( aqualuxe_is_woocommerce_active() ) {
        woocommerce_template_single_title();
    }
}

/**
 * Product rating
 */
function aqualuxe_product_rating() {
    if ( aqualuxe_is_woocommerce_active() ) {
        woocommerce_template_single_rating();
    }
}

/**
 * Product price
 */
function aqualuxe_product_price() {
    if ( aqualuxe_is_woocommerce_active() ) {
        woocommerce_template_single_price();
    }
}

/**
 * Product excerpt
 */
function aqualuxe_product_excerpt() {
    if ( aqualuxe_is_woocommerce_active() ) {
        woocommerce_template_single_excerpt();
    }
}

/**
 * Product add to cart
 */
function aqualuxe_product_add_to_cart() {
    if ( aqualuxe_is_woocommerce_active() ) {
        woocommerce_template_single_add_to_cart();
    }
}

/**
 * Product meta
 */
function aqualuxe_product_meta() {
    if ( aqualuxe_is_woocommerce_active() ) {
        woocommerce_template_single_meta();
    }
}

/**
 * Product tabs
 */
function aqualuxe_product_tabs() {
    if ( aqualuxe_is_woocommerce_active() ) {
        woocommerce_output_product_data_tabs();
    }
}

/**
 * Product related
 */
function aqualuxe_product_related() {
    if ( aqualuxe_is_woocommerce_active() && get_theme_mod( 'aqualuxe_product_related_products', true ) ) {
        woocommerce_output_related_products();
    }
}

/**
 * Product upsells
 */
function aqualuxe_product_upsells() {
    if ( aqualuxe_is_woocommerce_active() && get_theme_mod( 'aqualuxe_product_upsells', true ) ) {
        woocommerce_upsell_display();
    }
}

/**
 * Cart header
 */
function aqualuxe_cart_header() {
    if ( aqualuxe_is_woocommerce_active() ) {
        aqualuxe_get_template_part( 'templates/woocommerce/cart/header' );
    }
}

/**
 * Cart items
 */
function aqualuxe_cart_items() {
    if ( aqualuxe_is_woocommerce_active() ) {
        woocommerce_cart_form();
    }
}

/**
 * Cart totals
 */
function aqualuxe_cart_totals() {
    if ( aqualuxe_is_woocommerce_active() ) {
        woocommerce_cart_totals();
    }
}

/**
 * Cart cross sells
 */
function aqualuxe_cart_cross_sells() {
    if ( aqualuxe_is_woocommerce_active() && get_theme_mod( 'aqualuxe_cart_cross_sells', true ) ) {
        woocommerce_cross_sell_display();
    }
}

/**
 * Checkout header
 */
function aqualuxe_checkout_header() {
    if ( aqualuxe_is_woocommerce_active() ) {
        aqualuxe_get_template_part( 'templates/woocommerce/checkout/header' );
    }
}

/**
 * Checkout form
 */
function aqualuxe_checkout_form() {
    if ( aqualuxe_is_woocommerce_active() ) {
        woocommerce_checkout_form();
    }
}

/**
 * Checkout order review
 */
function aqualuxe_checkout_order_review() {
    if ( aqualuxe_is_woocommerce_active() ) {
        woocommerce_order_review();
    }
}

/**
 * Module header
 *
 * @param string $module The module.
 */
function aqualuxe_module_header( $module ) {
    aqualuxe_get_template_part( 'templates/modules/' . $module . '/header' );
}

/**
 * Module content
 *
 * @param string $module The module.
 */
function aqualuxe_module_content( $module ) {
    aqualuxe_get_template_part( 'templates/modules/' . $module . '/content' );
}

/**
 * Module footer
 *
 * @param string $module The module.
 */
function aqualuxe_module_footer( $module ) {
    aqualuxe_get_template_part( 'templates/modules/' . $module . '/footer' );
}