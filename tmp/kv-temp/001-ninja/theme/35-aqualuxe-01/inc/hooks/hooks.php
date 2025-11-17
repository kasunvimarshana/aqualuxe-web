<?php
/**
 * Theme hooks
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Header hooks.
 */

/**
 * Hook: aqualuxe_before_header.
 *
 * @hooked none
 */
function aqualuxe_before_header() {
	do_action( 'aqualuxe_before_header' );
}

/**
 * Hook: aqualuxe_header.
 *
 * @hooked aqualuxe_header_content - 10
 */
function aqualuxe_header() {
	do_action( 'aqualuxe_header' );
}

/**
 * Hook: aqualuxe_after_header.
 *
 * @hooked none
 */
function aqualuxe_after_header() {
	do_action( 'aqualuxe_after_header' );
}

/**
 * Hook: aqualuxe_header_logo.
 *
 * @hooked aqualuxe_header_logo_content - 10
 */
function aqualuxe_header_logo() {
	do_action( 'aqualuxe_header_logo' );
}

/**
 * Hook: aqualuxe_header_navigation.
 *
 * @hooked aqualuxe_header_navigation_content - 10
 */
function aqualuxe_header_navigation() {
	do_action( 'aqualuxe_header_navigation' );
}

/**
 * Hook: aqualuxe_header_icons.
 *
 * @hooked aqualuxe_header_search_icon - 10
 * @hooked aqualuxe_header_cart_icon - 20 (if WooCommerce is active)
 * @hooked aqualuxe_header_account_icon - 30 (if WooCommerce is active)
 * @hooked aqualuxe_header_wishlist_icon - 40 (if WooCommerce is active and wishlist is enabled)
 */
function aqualuxe_header_icons() {
	do_action( 'aqualuxe_header_icons' );
}

/**
 * Footer hooks.
 */

/**
 * Hook: aqualuxe_before_footer.
 *
 * @hooked none
 */
function aqualuxe_before_footer() {
	do_action( 'aqualuxe_before_footer' );
}

/**
 * Hook: aqualuxe_footer.
 *
 * @hooked aqualuxe_footer_content - 10
 */
function aqualuxe_footer() {
	do_action( 'aqualuxe_footer' );
}

/**
 * Hook: aqualuxe_after_footer.
 *
 * @hooked none
 */
function aqualuxe_after_footer() {
	do_action( 'aqualuxe_after_footer' );
}

/**
 * Hook: aqualuxe_footer_widgets.
 *
 * @hooked aqualuxe_footer_widgets_content - 10
 */
function aqualuxe_footer_widgets() {
	do_action( 'aqualuxe_footer_widgets' );
}

/**
 * Hook: aqualuxe_footer_copyright.
 *
 * @hooked aqualuxe_footer_copyright_content - 10
 */
function aqualuxe_footer_copyright() {
	do_action( 'aqualuxe_footer_copyright' );
}

/**
 * Hook: aqualuxe_footer_social.
 *
 * @hooked aqualuxe_footer_social_content - 10
 */
function aqualuxe_footer_social() {
	do_action( 'aqualuxe_footer_social' );
}

/**
 * Content hooks.
 */

/**
 * Hook: aqualuxe_before_main_content.
 *
 * @hooked aqualuxe_breadcrumbs - 10
 */
function aqualuxe_before_main_content() {
	do_action( 'aqualuxe_before_main_content' );
}

/**
 * Hook: aqualuxe_after_main_content.
 *
 * @hooked none
 */
function aqualuxe_after_main_content() {
	do_action( 'aqualuxe_after_main_content' );
}

/**
 * Hook: aqualuxe_before_post_content.
 *
 * @hooked aqualuxe_post_thumbnail - 10
 */
function aqualuxe_before_post_content() {
	do_action( 'aqualuxe_before_post_content' );
}

/**
 * Hook: aqualuxe_after_post_content.
 *
 * @hooked aqualuxe_post_tags - 10
 * @hooked aqualuxe_post_navigation - 20
 * @hooked aqualuxe_related_posts - 30
 */
function aqualuxe_after_post_content() {
	do_action( 'aqualuxe_after_post_content' );
}

/**
 * Hook: aqualuxe_post_meta.
 *
 * @hooked aqualuxe_post_date - 10
 * @hooked aqualuxe_post_author - 20
 * @hooked aqualuxe_post_categories - 30
 * @hooked aqualuxe_post_comments_count - 40
 */
function aqualuxe_post_meta() {
	do_action( 'aqualuxe_post_meta' );
}

/**
 * Hook: aqualuxe_post_author_bio.
 *
 * @hooked aqualuxe_post_author_bio_content - 10
 */
function aqualuxe_post_author_bio() {
	do_action( 'aqualuxe_post_author_bio' );
}

/**
 * Hook: aqualuxe_comments_before.
 *
 * @hooked none
 */
function aqualuxe_comments_before() {
	do_action( 'aqualuxe_comments_before' );
}

/**
 * Hook: aqualuxe_comments_after.
 *
 * @hooked none
 */
function aqualuxe_comments_after() {
	do_action( 'aqualuxe_comments_after' );
}

/**
 * Hook: aqualuxe_sidebar.
 *
 * @hooked aqualuxe_get_sidebar - 10
 */
function aqualuxe_sidebar() {
	do_action( 'aqualuxe_sidebar' );
}

/**
 * Hook: aqualuxe_before_sidebar.
 *
 * @hooked none
 */
function aqualuxe_before_sidebar() {
	do_action( 'aqualuxe_before_sidebar' );
}

/**
 * Hook: aqualuxe_after_sidebar.
 *
 * @hooked none
 */
function aqualuxe_after_sidebar() {
	do_action( 'aqualuxe_after_sidebar' );
}

/**
 * Page hooks.
 */

/**
 * Hook: aqualuxe_before_page_content.
 *
 * @hooked aqualuxe_page_thumbnail - 10
 */
function aqualuxe_before_page_content() {
	do_action( 'aqualuxe_before_page_content' );
}

/**
 * Hook: aqualuxe_after_page_content.
 *
 * @hooked none
 */
function aqualuxe_after_page_content() {
	do_action( 'aqualuxe_after_page_content' );
}

/**
 * Hook: aqualuxe_page_header.
 *
 * @hooked aqualuxe_page_header_content - 10
 */
function aqualuxe_page_header() {
	do_action( 'aqualuxe_page_header' );
}

/**
 * Archive hooks.
 */

/**
 * Hook: aqualuxe_archive_header.
 *
 * @hooked aqualuxe_archive_header_content - 10
 */
function aqualuxe_archive_header() {
	do_action( 'aqualuxe_archive_header' );
}

/**
 * Hook: aqualuxe_before_archive_content.
 *
 * @hooked none
 */
function aqualuxe_before_archive_content() {
	do_action( 'aqualuxe_before_archive_content' );
}

/**
 * Hook: aqualuxe_after_archive_content.
 *
 * @hooked aqualuxe_pagination - 10
 */
function aqualuxe_after_archive_content() {
	do_action( 'aqualuxe_after_archive_content' );
}

/**
 * Hook: aqualuxe_no_posts_found.
 *
 * @hooked aqualuxe_no_posts_found_content - 10
 */
function aqualuxe_no_posts_found() {
	do_action( 'aqualuxe_no_posts_found' );
}

/**
 * Search hooks.
 */

/**
 * Hook: aqualuxe_search_header.
 *
 * @hooked aqualuxe_search_header_content - 10
 */
function aqualuxe_search_header() {
	do_action( 'aqualuxe_search_header' );
}

/**
 * Hook: aqualuxe_before_search_content.
 *
 * @hooked none
 */
function aqualuxe_before_search_content() {
	do_action( 'aqualuxe_before_search_content' );
}

/**
 * Hook: aqualuxe_after_search_content.
 *
 * @hooked aqualuxe_pagination - 10
 */
function aqualuxe_after_search_content() {
	do_action( 'aqualuxe_after_search_content' );
}

/**
 * Hook: aqualuxe_no_search_results.
 *
 * @hooked aqualuxe_no_search_results_content - 10
 */
function aqualuxe_no_search_results() {
	do_action( 'aqualuxe_no_search_results' );
}

/**
 * 404 hooks.
 */

/**
 * Hook: aqualuxe_404_header.
 *
 * @hooked aqualuxe_404_header_content - 10
 */
function aqualuxe_404_header() {
	do_action( 'aqualuxe_404_header' );
}

/**
 * Hook: aqualuxe_404_content.
 *
 * @hooked aqualuxe_404_content_output - 10
 */
function aqualuxe_404_content() {
	do_action( 'aqualuxe_404_content' );
}

/**
 * WooCommerce hooks.
 */

/**
 * Hook: aqualuxe_before_shop_content.
 *
 * @hooked none
 */
function aqualuxe_before_shop_content() {
	do_action( 'aqualuxe_before_shop_content' );
}

/**
 * Hook: aqualuxe_after_shop_content.
 *
 * @hooked none
 */
function aqualuxe_after_shop_content() {
	do_action( 'aqualuxe_after_shop_content' );
}

/**
 * Hook: aqualuxe_before_product_content.
 *
 * @hooked none
 */
function aqualuxe_before_product_content() {
	do_action( 'aqualuxe_before_product_content' );
}

/**
 * Hook: aqualuxe_after_product_content.
 *
 * @hooked none
 */
function aqualuxe_after_product_content() {
	do_action( 'aqualuxe_after_product_content' );
}

/**
 * Hook: aqualuxe_product_quick_view.
 *
 * @hooked aqualuxe_product_quick_view_content - 10
 */
function aqualuxe_product_quick_view() {
	do_action( 'aqualuxe_product_quick_view' );
}

/**
 * Hook: aqualuxe_before_cart_content.
 *
 * @hooked none
 */
function aqualuxe_before_cart_content() {
	do_action( 'aqualuxe_before_cart_content' );
}

/**
 * Hook: aqualuxe_after_cart_content.
 *
 * @hooked none
 */
function aqualuxe_after_cart_content() {
	do_action( 'aqualuxe_after_cart_content' );
}

/**
 * Hook: aqualuxe_before_checkout_content.
 *
 * @hooked none
 */
function aqualuxe_before_checkout_content() {
	do_action( 'aqualuxe_before_checkout_content' );
}

/**
 * Hook: aqualuxe_after_checkout_content.
 *
 * @hooked none
 */
function aqualuxe_after_checkout_content() {
	do_action( 'aqualuxe_after_checkout_content' );
}

/**
 * Hook: aqualuxe_before_account_content.
 *
 * @hooked none
 */
function aqualuxe_before_account_content() {
	do_action( 'aqualuxe_before_account_content' );
}

/**
 * Hook: aqualuxe_after_account_content.
 *
 * @hooked none
 */
function aqualuxe_after_account_content() {
	do_action( 'aqualuxe_after_account_content' );
}

/**
 * Hook: aqualuxe_woocommerce_fallback.
 *
 * @hooked aqualuxe_woocommerce_fallback_content - 10
 */
function aqualuxe_woocommerce_fallback() {
	do_action( 'aqualuxe_woocommerce_fallback' );
}

/**
 * Customizer hooks.
 */

/**
 * Hook: aqualuxe_customize_register.
 *
 * @hooked none
 */
function aqualuxe_customize_register_hook( $wp_customize ) {
	do_action( 'aqualuxe_customize_register', $wp_customize );
}
add_action( 'customize_register', 'aqualuxe_customize_register_hook' );

/**
 * Hook: aqualuxe_customize_preview_init.
 *
 * @hooked none
 */
function aqualuxe_customize_preview_init_hook() {
	do_action( 'aqualuxe_customize_preview_init' );
}
add_action( 'customize_preview_init', 'aqualuxe_customize_preview_init_hook' );

/**
 * Hook: aqualuxe_customize_controls_enqueue_scripts.
 *
 * @hooked none
 */
function aqualuxe_customize_controls_enqueue_scripts_hook() {
	do_action( 'aqualuxe_customize_controls_enqueue_scripts' );
}
add_action( 'customize_controls_enqueue_scripts', 'aqualuxe_customize_controls_enqueue_scripts_hook' );

/**
 * Filters.
 */

/**
 * Filter: aqualuxe_body_classes.
 *
 * @param array $classes Body classes.
 * @return array Modified body classes.
 */
function aqualuxe_body_classes_filter( $classes ) {
	return apply_filters( 'aqualuxe_body_classes', $classes );
}
add_filter( 'body_class', 'aqualuxe_body_classes_filter' );

/**
 * Filter: aqualuxe_post_classes.
 *
 * @param array $classes Post classes.
 * @return array Modified post classes.
 */
function aqualuxe_post_classes_filter( $classes ) {
	return apply_filters( 'aqualuxe_post_classes', $classes );
}
add_filter( 'post_class', 'aqualuxe_post_classes_filter' );

/**
 * Filter: aqualuxe_excerpt_length.
 *
 * @param int $length Excerpt length.
 * @return int Modified excerpt length.
 */
function aqualuxe_excerpt_length_filter( $length ) {
	return apply_filters( 'aqualuxe_excerpt_length', $length );
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length_filter' );

/**
 * Filter: aqualuxe_excerpt_more.
 *
 * @param string $more Excerpt more.
 * @return string Modified excerpt more.
 */
function aqualuxe_excerpt_more_filter( $more ) {
	return apply_filters( 'aqualuxe_excerpt_more', $more );
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more_filter' );

/**
 * Filter: aqualuxe_comment_form_defaults.
 *
 * @param array $defaults Comment form defaults.
 * @return array Modified comment form defaults.
 */
function aqualuxe_comment_form_defaults_filter( $defaults ) {
	return apply_filters( 'aqualuxe_comment_form_defaults', $defaults );
}
add_filter( 'comment_form_defaults', 'aqualuxe_comment_form_defaults_filter' );

/**
 * Filter: aqualuxe_related_posts_args.
 *
 * @param array $args Related posts args.
 * @return array Modified related posts args.
 */
function aqualuxe_related_posts_args_filter( $args ) {
	return apply_filters( 'aqualuxe_related_posts_args', $args );
}

/**
 * Filter: aqualuxe_pagination_args.
 *
 * @param array $args Pagination args.
 * @return array Modified pagination args.
 */
function aqualuxe_pagination_args_filter( $args ) {
	return apply_filters( 'aqualuxe_pagination_args', $args );
}

/**
 * Filter: aqualuxe_sidebar_id.
 *
 * @param string $sidebar_id Sidebar ID.
 * @return string Modified sidebar ID.
 */
function aqualuxe_sidebar_id_filter( $sidebar_id ) {
	return apply_filters( 'aqualuxe_sidebar_id', $sidebar_id );
}

/**
 * Filter: aqualuxe_footer_widgets_columns.
 *
 * @param int $columns Footer widgets columns.
 * @return int Modified footer widgets columns.
 */
function aqualuxe_footer_widgets_columns_filter( $columns ) {
	return apply_filters( 'aqualuxe_footer_widgets_columns', $columns );
}

/**
 * Filter: aqualuxe_footer_copyright_text.
 *
 * @param string $text Footer copyright text.
 * @return string Modified footer copyright text.
 */
function aqualuxe_footer_copyright_text_filter( $text ) {
	return apply_filters( 'aqualuxe_footer_copyright_text', $text );
}

/**
 * WooCommerce filters.
 */

/**
 * Filter: aqualuxe_woocommerce_products_per_page.
 *
 * @param int $products_per_page Products per page.
 * @return int Modified products per page.
 */
function aqualuxe_woocommerce_products_per_page_filter( $products_per_page ) {
	return apply_filters( 'aqualuxe_woocommerce_products_per_page', $products_per_page );
}

/**
 * Filter: aqualuxe_woocommerce_loop_columns.
 *
 * @param int $columns Loop columns.
 * @return int Modified loop columns.
 */
function aqualuxe_woocommerce_loop_columns_filter( $columns ) {
	return apply_filters( 'aqualuxe_woocommerce_loop_columns', $columns );
}

/**
 * Filter: aqualuxe_woocommerce_related_products_args.
 *
 * @param array $args Related products args.
 * @return array Modified related products args.
 */
function aqualuxe_woocommerce_related_products_args_filter( $args ) {
	return apply_filters( 'aqualuxe_woocommerce_related_products_args', $args );
}

/**
 * Filter: aqualuxe_woocommerce_fallback_message.
 *
 * @param string $message Fallback message.
 * @return string Modified fallback message.
 */
function aqualuxe_woocommerce_fallback_message_filter( $message ) {
	return apply_filters( 'aqualuxe_woocommerce_fallback_message', $message );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs.
 *
 * @param array $tabs Product tabs.
 * @return array Modified product tabs.
 */
function aqualuxe_woocommerce_product_tabs_filter( $tabs ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs', $tabs );
}

/**
 * Filter: aqualuxe_woocommerce_cart_item_name.
 *
 * @param string $name Cart item name.
 * @param array  $cart_item Cart item.
 * @param string $cart_item_key Cart item key.
 * @return string Modified cart item name.
 */
function aqualuxe_woocommerce_cart_item_name_filter( $name, $cart_item, $cart_item_key ) {
	return apply_filters( 'aqualuxe_woocommerce_cart_item_name', $name, $cart_item, $cart_item_key );
}

/**
 * Filter: aqualuxe_woocommerce_cart_item_thumbnail.
 *
 * @param string $thumbnail Cart item thumbnail.
 * @param array  $cart_item Cart item.
 * @param string $cart_item_key Cart item key.
 * @return string Modified cart item thumbnail.
 */
function aqualuxe_woocommerce_cart_item_thumbnail_filter( $thumbnail, $cart_item, $cart_item_key ) {
	return apply_filters( 'aqualuxe_woocommerce_cart_item_thumbnail', $thumbnail, $cart_item, $cart_item_key );
}

/**
 * Filter: aqualuxe_woocommerce_cart_item_price.
 *
 * @param string $price Cart item price.
 * @param array  $cart_item Cart item.
 * @param string $cart_item_key Cart item key.
 * @return string Modified cart item price.
 */
function aqualuxe_woocommerce_cart_item_price_filter( $price, $cart_item, $cart_item_key ) {
	return apply_filters( 'aqualuxe_woocommerce_cart_item_price', $price, $cart_item, $cart_item_key );
}

/**
 * Filter: aqualuxe_woocommerce_cart_item_quantity.
 *
 * @param string $quantity Cart item quantity.
 * @param string $cart_item_key Cart item key.
 * @param array  $cart_item Cart item.
 * @return string Modified cart item quantity.
 */
function aqualuxe_woocommerce_cart_item_quantity_filter( $quantity, $cart_item_key, $cart_item ) {
	return apply_filters( 'aqualuxe_woocommerce_cart_item_quantity', $quantity, $cart_item_key, $cart_item );
}

/**
 * Filter: aqualuxe_woocommerce_cart_item_subtotal.
 *
 * @param string $subtotal Cart item subtotal.
 * @param array  $cart_item Cart item.
 * @param string $cart_item_key Cart item key.
 * @return string Modified cart item subtotal.
 */
function aqualuxe_woocommerce_cart_item_subtotal_filter( $subtotal, $cart_item, $cart_item_key ) {
	return apply_filters( 'aqualuxe_woocommerce_cart_item_subtotal', $subtotal, $cart_item, $cart_item_key );
}

/**
 * Filter: aqualuxe_woocommerce_cart_totals_order_total_html.
 *
 * @param string $value Order total HTML.
 * @return string Modified order total HTML.
 */
function aqualuxe_woocommerce_cart_totals_order_total_html_filter( $value ) {
	return apply_filters( 'aqualuxe_woocommerce_cart_totals_order_total_html', $value );
}

/**
 * Filter: aqualuxe_woocommerce_checkout_fields.
 *
 * @param array $fields Checkout fields.
 * @return array Modified checkout fields.
 */
function aqualuxe_woocommerce_checkout_fields_filter( $fields ) {
	return apply_filters( 'aqualuxe_woocommerce_checkout_fields', $fields );
}

/**
 * Filter: aqualuxe_woocommerce_account_menu_items.
 *
 * @param array $items Account menu items.
 * @return array Modified account menu items.
 */
function aqualuxe_woocommerce_account_menu_items_filter( $items ) {
	return apply_filters( 'aqualuxe_woocommerce_account_menu_items', $items );
}

/**
 * Filter: aqualuxe_woocommerce_product_review_comment_form_args.
 *
 * @param array $comment_form Comment form args.
 * @return array Modified comment form args.
 */
function aqualuxe_woocommerce_product_review_comment_form_args_filter( $comment_form ) {
	return apply_filters( 'aqualuxe_woocommerce_product_review_comment_form_args', $comment_form );
}

/**
 * Filter: aqualuxe_woocommerce_add_to_cart_fragments.
 *
 * @param array $fragments Cart fragments.
 * @return array Modified cart fragments.
 */
function aqualuxe_woocommerce_add_to_cart_fragments_filter( $fragments ) {
	return apply_filters( 'aqualuxe_woocommerce_add_to_cart_fragments', $fragments );
}

/**
 * Filter: aqualuxe_woocommerce_loop_add_to_cart_link.
 *
 * @param string $html Add to cart link HTML.
 * @param object $product Product object.
 * @param array  $args Args.
 * @return string Modified add to cart link HTML.
 */
function aqualuxe_woocommerce_loop_add_to_cart_link_filter( $html, $product, $args ) {
	return apply_filters( 'aqualuxe_woocommerce_loop_add_to_cart_link', $html, $product, $args );
}

/**
 * Filter: aqualuxe_woocommerce_product_get_rating_html.
 *
 * @param string $html Rating HTML.
 * @param float  $rating Rating.
 * @param int    $count Count.
 * @return string Modified rating HTML.
 */
function aqualuxe_woocommerce_product_get_rating_html_filter( $html, $rating, $count ) {
	return apply_filters( 'aqualuxe_woocommerce_product_get_rating_html', $html, $rating, $count );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_wrapper_class.
 *
 * @param string $class Wrapper class.
 * @return string Modified wrapper class.
 */
function aqualuxe_woocommerce_product_tabs_wrapper_class_filter( $class ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_wrapper_class', $class );
}

/**
 * Filter: aqualuxe_woocommerce_breadcrumb_defaults.
 *
 * @param array $defaults Breadcrumb defaults.
 * @return array Modified breadcrumb defaults.
 */
function aqualuxe_woocommerce_breadcrumb_defaults_filter( $defaults ) {
	return apply_filters( 'aqualuxe_woocommerce_breadcrumb_defaults', $defaults );
}

/**
 * Filter: aqualuxe_woocommerce_breadcrumb_home_url.
 *
 * @param string $url Home URL.
 * @return string Modified home URL.
 */
function aqualuxe_woocommerce_breadcrumb_home_url_filter( $url ) {
	return apply_filters( 'aqualuxe_woocommerce_breadcrumb_home_url', $url );
}

/**
 * Filter: aqualuxe_woocommerce_placeholder_img_src.
 *
 * @param string $src Placeholder image source.
 * @return string Modified placeholder image source.
 */
function aqualuxe_woocommerce_placeholder_img_src_filter( $src ) {
	return apply_filters( 'aqualuxe_woocommerce_placeholder_img_src', $src );
}

/**
 * Filter: aqualuxe_woocommerce_placeholder_img.
 *
 * @param string $image Placeholder image HTML.
 * @param string $size Image size.
 * @param array  $dimensions Image dimensions.
 * @return string Modified placeholder image HTML.
 */
function aqualuxe_woocommerce_placeholder_img_filter( $image, $size, $dimensions ) {
	return apply_filters( 'aqualuxe_woocommerce_placeholder_img', $image, $size, $dimensions );
}

/**
 * Filter: aqualuxe_woocommerce_sale_flash.
 *
 * @param string $html Sale flash HTML.
 * @param object $post Post object.
 * @param object $product Product object.
 * @return string Modified sale flash HTML.
 */
function aqualuxe_woocommerce_sale_flash_filter( $html, $post, $product ) {
	return apply_filters( 'aqualuxe_woocommerce_sale_flash', $html, $post, $product );
}

/**
 * Filter: aqualuxe_woocommerce_product_thumbnails_columns.
 *
 * @param int $columns Thumbnails columns.
 * @return int Modified thumbnails columns.
 */
function aqualuxe_woocommerce_product_thumbnails_columns_filter( $columns ) {
	return apply_filters( 'aqualuxe_woocommerce_product_thumbnails_columns', $columns );
}

/**
 * Filter: aqualuxe_woocommerce_output_related_products_args.
 *
 * @param array $args Related products args.
 * @return array Modified related products args.
 */
function aqualuxe_woocommerce_output_related_products_args_filter( $args ) {
	return apply_filters( 'aqualuxe_woocommerce_output_related_products_args', $args );
}

/**
 * Filter: aqualuxe_woocommerce_pagination_args.
 *
 * @param array $args Pagination args.
 * @return array Modified pagination args.
 */
function aqualuxe_woocommerce_pagination_args_filter( $args ) {
	return apply_filters( 'aqualuxe_woocommerce_pagination_args', $args );
}

/**
 * Filter: aqualuxe_woocommerce_get_image_size_gallery_thumbnail.
 *
 * @param array $size Gallery thumbnail size.
 * @return array Modified gallery thumbnail size.
 */
function aqualuxe_woocommerce_get_image_size_gallery_thumbnail_filter( $size ) {
	return apply_filters( 'aqualuxe_woocommerce_get_image_size_gallery_thumbnail', $size );
}

/**
 * Filter: aqualuxe_woocommerce_get_image_size_thumbnail.
 *
 * @param array $size Thumbnail size.
 * @return array Modified thumbnail size.
 */
function aqualuxe_woocommerce_get_image_size_thumbnail_filter( $size ) {
	return apply_filters( 'aqualuxe_woocommerce_get_image_size_thumbnail', $size );
}

/**
 * Filter: aqualuxe_woocommerce_get_image_size_single.
 *
 * @param array $size Single size.
 * @return array Modified single size.
 */
function aqualuxe_woocommerce_get_image_size_single_filter( $size ) {
	return apply_filters( 'aqualuxe_woocommerce_get_image_size_single', $size );
}

/**
 * Filter: aqualuxe_woocommerce_cross_sells_columns.
 *
 * @param int $columns Cross sells columns.
 * @return int Modified cross sells columns.
 */
function aqualuxe_woocommerce_cross_sells_columns_filter( $columns ) {
	return apply_filters( 'aqualuxe_woocommerce_cross_sells_columns', $columns );
}

/**
 * Filter: aqualuxe_woocommerce_cross_sells_total.
 *
 * @param int $total Cross sells total.
 * @return int Modified cross sells total.
 */
function aqualuxe_woocommerce_cross_sells_total_filter( $total ) {
	return apply_filters( 'aqualuxe_woocommerce_cross_sells_total', $total );
}

/**
 * Filter: aqualuxe_woocommerce_upsells_columns.
 *
 * @param int $columns Upsells columns.
 * @return int Modified upsells columns.
 */
function aqualuxe_woocommerce_upsells_columns_filter( $columns ) {
	return apply_filters( 'aqualuxe_woocommerce_upsells_columns', $columns );
}

/**
 * Filter: aqualuxe_woocommerce_upsells_total.
 *
 * @param int $total Upsells total.
 * @return int Modified upsells total.
 */
function aqualuxe_woocommerce_upsells_total_filter( $total ) {
	return apply_filters( 'aqualuxe_woocommerce_upsells_total', $total );
}

/**
 * Filter: aqualuxe_woocommerce_related_products_columns.
 *
 * @param int $columns Related products columns.
 * @return int Modified related products columns.
 */
function aqualuxe_woocommerce_related_products_columns_filter( $columns ) {
	return apply_filters( 'aqualuxe_woocommerce_related_products_columns', $columns );
}

/**
 * Filter: aqualuxe_woocommerce_related_products_total.
 *
 * @param int $total Related products total.
 * @return int Modified related products total.
 */
function aqualuxe_woocommerce_related_products_total_filter( $total ) {
	return apply_filters( 'aqualuxe_woocommerce_related_products_total', $total );
}

/**
 * Filter: aqualuxe_woocommerce_product_description_heading.
 *
 * @param string $heading Description heading.
 * @return string Modified description heading.
 */
function aqualuxe_woocommerce_product_description_heading_filter( $heading ) {
	return apply_filters( 'aqualuxe_woocommerce_product_description_heading', $heading );
}

/**
 * Filter: aqualuxe_woocommerce_product_additional_information_heading.
 *
 * @param string $heading Additional information heading.
 * @return string Modified additional information heading.
 */
function aqualuxe_woocommerce_product_additional_information_heading_filter( $heading ) {
	return apply_filters( 'aqualuxe_woocommerce_product_additional_information_heading', $heading );
}

/**
 * Filter: aqualuxe_woocommerce_product_reviews_heading.
 *
 * @param string $heading Reviews heading.
 * @return string Modified reviews heading.
 */
function aqualuxe_woocommerce_product_reviews_heading_filter( $heading ) {
	return apply_filters( 'aqualuxe_woocommerce_product_reviews_heading', $heading );
}

/**
 * Filter: aqualuxe_woocommerce_product_tab_title.
 *
 * @param string $title Tab title.
 * @param string $key Tab key.
 * @return string Modified tab title.
 */
function aqualuxe_woocommerce_product_tab_title_filter( $title, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tab_title', $title, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tab_content.
 *
 * @param string $content Tab content.
 * @param string $key Tab key.
 * @return string Modified tab content.
 */
function aqualuxe_woocommerce_product_tab_content_filter( $content, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tab_content', $content, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tab_priority.
 *
 * @param int    $priority Tab priority.
 * @param string $key Tab key.
 * @return int Modified tab priority.
 */
function aqualuxe_woocommerce_product_tab_priority_filter( $priority, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tab_priority', $priority, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tab_enabled.
 *
 * @param bool   $enabled Tab enabled.
 * @param string $key Tab key.
 * @return bool Modified tab enabled.
 */
function aqualuxe_woocommerce_product_tab_enabled_filter( $enabled, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tab_enabled', $enabled, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tab_callback.
 *
 * @param callable $callback Tab callback.
 * @param string   $key Tab key.
 * @return callable Modified tab callback.
 */
function aqualuxe_woocommerce_product_tab_callback_filter( $callback, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tab_callback', $callback, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tab_args.
 *
 * @param array  $args Tab args.
 * @param string $key Tab key.
 * @return array Modified tab args.
 */
function aqualuxe_woocommerce_product_tab_args_filter( $args, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tab_args', $args, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_wrapper_id.
 *
 * @param string $id Wrapper ID.
 * @return string Modified wrapper ID.
 */
function aqualuxe_woocommerce_product_tabs_wrapper_id_filter( $id ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_wrapper_id', $id );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_class.
 *
 * @param string $class List class.
 * @return string Modified list class.
 */
function aqualuxe_woocommerce_product_tabs_list_class_filter( $class ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_class', $class );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_id.
 *
 * @param string $id List ID.
 * @return string Modified list ID.
 */
function aqualuxe_woocommerce_product_tabs_list_id_filter( $id ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_id', $id );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_item_class.
 *
 * @param string $class List item class.
 * @param string $key Tab key.
 * @return string Modified list item class.
 */
function aqualuxe_woocommerce_product_tabs_list_item_class_filter( $class, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_item_class', $class, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_item_id.
 *
 * @param string $id List item ID.
 * @param string $key Tab key.
 * @return string Modified list item ID.
 */
function aqualuxe_woocommerce_product_tabs_list_item_id_filter( $id, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_item_id', $id, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_panel_class.
 *
 * @param string $class Panel class.
 * @param string $key Tab key.
 * @return string Modified panel class.
 */
function aqualuxe_woocommerce_product_tabs_panel_class_filter( $class, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_panel_class', $class, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_panel_id.
 *
 * @param string $id Panel ID.
 * @param string $key Tab key.
 * @return string Modified panel ID.
 */
function aqualuxe_woocommerce_product_tabs_panel_id_filter( $id, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_panel_id', $id, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_panel_heading_class.
 *
 * @param string $class Panel heading class.
 * @param string $key Tab key.
 * @return string Modified panel heading class.
 */
function aqualuxe_woocommerce_product_tabs_panel_heading_class_filter( $class, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_panel_heading_class', $class, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_panel_heading_id.
 *
 * @param string $id Panel heading ID.
 * @param string $key Tab key.
 * @return string Modified panel heading ID.
 */
function aqualuxe_woocommerce_product_tabs_panel_heading_id_filter( $id, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_panel_heading_id', $id, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_panel_content_class.
 *
 * @param string $class Panel content class.
 * @param string $key Tab key.
 * @return string Modified panel content class.
 */
function aqualuxe_woocommerce_product_tabs_panel_content_class_filter( $class, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_panel_content_class', $class, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_panel_content_id.
 *
 * @param string $id Panel content ID.
 * @param string $key Tab key.
 * @return string Modified panel content ID.
 */
function aqualuxe_woocommerce_product_tabs_panel_content_id_filter( $id, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_panel_content_id', $id, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_panel_content.
 *
 * @param string $content Panel content.
 * @param string $key Tab key.
 * @return string Modified panel content.
 */
function aqualuxe_woocommerce_product_tabs_panel_content_filter( $content, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_panel_content', $content, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_panel_heading.
 *
 * @param string $heading Panel heading.
 * @param string $key Tab key.
 * @return string Modified panel heading.
 */
function aqualuxe_woocommerce_product_tabs_panel_heading_filter( $heading, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_panel_heading', $heading, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_panel_heading_tag.
 *
 * @param string $tag Panel heading tag.
 * @param string $key Tab key.
 * @return string Modified panel heading tag.
 */
function aqualuxe_woocommerce_product_tabs_panel_heading_tag_filter( $tag, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_panel_heading_tag', $tag, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_panel_heading_attributes.
 *
 * @param array  $attributes Panel heading attributes.
 * @param string $key Tab key.
 * @return array Modified panel heading attributes.
 */
function aqualuxe_woocommerce_product_tabs_panel_heading_attributes_filter( $attributes, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_panel_heading_attributes', $attributes, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_panel_content_attributes.
 *
 * @param array  $attributes Panel content attributes.
 * @param string $key Tab key.
 * @return array Modified panel content attributes.
 */
function aqualuxe_woocommerce_product_tabs_panel_content_attributes_filter( $attributes, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_panel_content_attributes', $attributes, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_panel_attributes.
 *
 * @param array  $attributes Panel attributes.
 * @param string $key Tab key.
 * @return array Modified panel attributes.
 */
function aqualuxe_woocommerce_product_tabs_panel_attributes_filter( $attributes, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_panel_attributes', $attributes, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_item_attributes.
 *
 * @param array  $attributes List item attributes.
 * @param string $key Tab key.
 * @return array Modified list item attributes.
 */
function aqualuxe_woocommerce_product_tabs_list_item_attributes_filter( $attributes, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_item_attributes', $attributes, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_attributes.
 *
 * @param array $attributes List attributes.
 * @return array Modified list attributes.
 */
function aqualuxe_woocommerce_product_tabs_list_attributes_filter( $attributes ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_attributes', $attributes );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_wrapper_attributes.
 *
 * @param array $attributes Wrapper attributes.
 * @return array Modified wrapper attributes.
 */
function aqualuxe_woocommerce_product_tabs_wrapper_attributes_filter( $attributes ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_wrapper_attributes', $attributes );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_item_link_class.
 *
 * @param string $class Link class.
 * @param string $key Tab key.
 * @return string Modified link class.
 */
function aqualuxe_woocommerce_product_tabs_list_item_link_class_filter( $class, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_item_link_class', $class, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_item_link_id.
 *
 * @param string $id Link ID.
 * @param string $key Tab key.
 * @return string Modified link ID.
 */
function aqualuxe_woocommerce_product_tabs_list_item_link_id_filter( $id, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_item_link_id', $id, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_item_link_attributes.
 *
 * @param array  $attributes Link attributes.
 * @param string $key Tab key.
 * @return array Modified link attributes.
 */
function aqualuxe_woocommerce_product_tabs_list_item_link_attributes_filter( $attributes, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_item_link_attributes', $attributes, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_item_link_text.
 *
 * @param string $text Link text.
 * @param string $key Tab key.
 * @return string Modified link text.
 */
function aqualuxe_woocommerce_product_tabs_list_item_link_text_filter( $text, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_item_link_text', $text, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_item_link_href.
 *
 * @param string $href Link href.
 * @param string $key Tab key.
 * @return string Modified link href.
 */
function aqualuxe_woocommerce_product_tabs_list_item_link_href_filter( $href, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_item_link_href', $href, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_item_link_target.
 *
 * @param string $target Link target.
 * @param string $key Tab key.
 * @return string Modified link target.
 */
function aqualuxe_woocommerce_product_tabs_list_item_link_target_filter( $target, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_item_link_target', $target, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_item_link_rel.
 *
 * @param string $rel Link rel.
 * @param string $key Tab key.
 * @return string Modified link rel.
 */
function aqualuxe_woocommerce_product_tabs_list_item_link_rel_filter( $rel, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_item_link_rel', $rel, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_item_link_title.
 *
 * @param string $title Link title.
 * @param string $key Tab key.
 * @return string Modified link title.
 */
function aqualuxe_woocommerce_product_tabs_list_item_link_title_filter( $title, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_item_link_title', $title, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_item_link_aria_controls.
 *
 * @param string $aria_controls Link aria-controls.
 * @param string $key Tab key.
 * @return string Modified link aria-controls.
 */
function aqualuxe_woocommerce_product_tabs_list_item_link_aria_controls_filter( $aria_controls, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_item_link_aria_controls', $aria_controls, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_item_link_aria_selected.
 *
 * @param string $aria_selected Link aria-selected.
 * @param string $key Tab key.
 * @return string Modified link aria-selected.
 */
function aqualuxe_woocommerce_product_tabs_list_item_link_aria_selected_filter( $aria_selected, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_item_link_aria_selected', $aria_selected, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_item_link_role.
 *
 * @param string $role Link role.
 * @param string $key Tab key.
 * @return string Modified link role.
 */
function aqualuxe_woocommerce_product_tabs_list_item_link_role_filter( $role, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_item_link_role', $role, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_item_link_tabindex.
 *
 * @param string $tabindex Link tabindex.
 * @param string $key Tab key.
 * @return string Modified link tabindex.
 */
function aqualuxe_woocommerce_product_tabs_list_item_link_tabindex_filter( $tabindex, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_item_link_tabindex', $tabindex, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_item_link_data.
 *
 * @param array  $data Link data.
 * @param string $key Tab key.
 * @return array Modified link data.
 */
function aqualuxe_woocommerce_product_tabs_list_item_link_data_filter( $data, $key ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_item_link_data', $data, $key );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_item_link.
 *
 * @param string $link Link HTML.
 * @param string $key Tab key.
 * @param array  $tab Tab data.
 * @return string Modified link HTML.
 */
function aqualuxe_woocommerce_product_tabs_list_item_link_filter( $link, $key, $tab ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_item_link', $link, $key, $tab );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list_item.
 *
 * @param string $item List item HTML.
 * @param string $key Tab key.
 * @param array  $tab Tab data.
 * @return string Modified list item HTML.
 */
function aqualuxe_woocommerce_product_tabs_list_item_filter( $item, $key, $tab ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list_item', $item, $key, $tab );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_list.
 *
 * @param string $list List HTML.
 * @param array  $tabs Tabs data.
 * @return string Modified list HTML.
 */
function aqualuxe_woocommerce_product_tabs_list_filter( $list, $tabs ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_list', $list, $tabs );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_panel.
 *
 * @param string $panel Panel HTML.
 * @param string $key Tab key.
 * @param array  $tab Tab data.
 * @return string Modified panel HTML.
 */
function aqualuxe_woocommerce_product_tabs_panel_filter( $panel, $key, $tab ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_panel', $panel, $key, $tab );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_panels.
 *
 * @param string $panels Panels HTML.
 * @param array  $tabs Tabs data.
 * @return string Modified panels HTML.
 */
function aqualuxe_woocommerce_product_tabs_panels_filter( $panels, $tabs ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_panels', $panels, $tabs );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_wrapper.
 *
 * @param string $wrapper Wrapper HTML.
 * @param array  $tabs Tabs data.
 * @return string Modified wrapper HTML.
 */
function aqualuxe_woocommerce_product_tabs_wrapper_filter( $wrapper, $tabs ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_wrapper', $wrapper, $tabs );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_output.
 *
 * @param string $output Output HTML.
 * @param array  $tabs Tabs data.
 * @return string Modified output HTML.
 */
function aqualuxe_woocommerce_product_tabs_output_filter( $output, $tabs ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_output', $output, $tabs );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_style.
 *
 * @param string $style Style.
 * @return string Modified style.
 */
function aqualuxe_woocommerce_product_tabs_style_filter( $style ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_style', $style );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_script.
 *
 * @param string $script Script.
 * @return string Modified script.
 */
function aqualuxe_woocommerce_product_tabs_script_filter( $script ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_script', $script );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_data.
 *
 * @param array $data Data.
 * @return array Modified data.
 */
function aqualuxe_woocommerce_product_tabs_data_filter( $data ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_data', $data );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_options.
 *
 * @param array $options Options.
 * @return array Modified options.
 */
function aqualuxe_woocommerce_product_tabs_options_filter( $options ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_options', $options );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_settings.
 *
 * @param array $settings Settings.
 * @return array Modified settings.
 */
function aqualuxe_woocommerce_product_tabs_settings_filter( $settings ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_settings', $settings );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_config.
 *
 * @param array $config Config.
 * @return array Modified config.
 */
function aqualuxe_woocommerce_product_tabs_config_filter( $config ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_config', $config );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_i18n.
 *
 * @param array $i18n I18n.
 * @return array Modified i18n.
 */
function aqualuxe_woocommerce_product_tabs_i18n_filter( $i18n ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_i18n', $i18n );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_defaults.
 *
 * @param array $defaults Defaults.
 * @return array Modified defaults.
 */
function aqualuxe_woocommerce_product_tabs_defaults_filter( $defaults ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_defaults', $defaults );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_styles.
 *
 * @param array $styles Styles.
 * @return array Modified styles.
 */
function aqualuxe_woocommerce_product_tabs_styles_filter( $styles ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_styles', $styles );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_scripts.
 *
 * @param array $scripts Scripts.
 * @return array Modified scripts.
 */
function aqualuxe_woocommerce_product_tabs_scripts_filter( $scripts ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_scripts', $scripts );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_templates.
 *
 * @param array $templates Templates.
 * @return array Modified templates.
 */
function aqualuxe_woocommerce_product_tabs_templates_filter( $templates ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_templates', $templates );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template.
 *
 * @param string $template Template.
 * @param string $name Template name.
 * @return string Modified template.
 */
function aqualuxe_woocommerce_product_tabs_template_filter( $template, $name ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template', $template, $name );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_args.
 *
 * @param array  $args Template args.
 * @param string $name Template name.
 * @return array Modified template args.
 */
function aqualuxe_woocommerce_product_tabs_template_args_filter( $args, $name ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_args', $args, $name );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_path.
 *
 * @param string $path Template path.
 * @param string $name Template name.
 * @return string Modified template path.
 */
function aqualuxe_woocommerce_product_tabs_template_path_filter( $path, $name ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_path', $path, $name );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_html.
 *
 * @param string $html Template HTML.
 * @param string $name Template name.
 * @param array  $args Template args.
 * @return string Modified template HTML.
 */
function aqualuxe_woocommerce_product_tabs_template_html_filter( $html, $name, $args ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_html', $html, $name, $args );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_include.
 *
 * @param string $template Template.
 * @param string $name Template name.
 * @return string Modified template.
 */
function aqualuxe_woocommerce_product_tabs_template_include_filter( $template, $name ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_include', $template, $name );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_locate.
 *
 * @param string $template Template.
 * @param string $name Template name.
 * @return string Modified template.
 */
function aqualuxe_woocommerce_product_tabs_template_locate_filter( $template, $name ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_locate', $template, $name );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_content.
 *
 * @param string $content Template content.
 * @param string $name Template name.
 * @param array  $args Template args.
 * @return string Modified template content.
 */
function aqualuxe_woocommerce_product_tabs_template_content_filter( $content, $name, $args ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_content', $content, $name, $args );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_data.
 *
 * @param array  $data Template data.
 * @param string $name Template name.
 * @return array Modified template data.
 */
function aqualuxe_woocommerce_product_tabs_template_data_filter( $data, $name ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_data', $data, $name );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_vars.
 *
 * @param array  $vars Template vars.
 * @param string $name Template name.
 * @return array Modified template vars.
 */
function aqualuxe_woocommerce_product_tabs_template_vars_filter( $vars, $name ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_vars', $vars, $name );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_context.
 *
 * @param array  $context Template context.
 * @param string $name Template name.
 * @return array Modified template context.
 */
function aqualuxe_woocommerce_product_tabs_template_context_filter( $context, $name ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_context', $context, $name );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_engine.
 *
 * @param string $engine Template engine.
 * @param string $name Template name.
 * @return string Modified template engine.
 */
function aqualuxe_woocommerce_product_tabs_template_engine_filter( $engine, $name ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_engine', $engine, $name );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_engine_args.
 *
 * @param array  $args Template engine args.
 * @param string $name Template name.
 * @return array Modified template engine args.
 */
function aqualuxe_woocommerce_product_tabs_template_engine_args_filter( $args, $name ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_engine_args', $args, $name );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_engine_context.
 *
 * @param array  $context Template engine context.
 * @param string $name Template name.
 * @return array Modified template engine context.
 */
function aqualuxe_woocommerce_product_tabs_template_engine_context_filter( $context, $name ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_engine_context', $context, $name );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_engine_data.
 *
 * @param array  $data Template engine data.
 * @param string $name Template name.
 * @return array Modified template engine data.
 */
function aqualuxe_woocommerce_product_tabs_template_engine_data_filter( $data, $name ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_engine_data', $data, $name );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_engine_vars.
 *
 * @param array  $vars Template engine vars.
 * @param string $name Template name.
 * @return array Modified template engine vars.
 */
function aqualuxe_woocommerce_product_tabs_template_engine_vars_filter( $vars, $name ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_engine_vars', $vars, $name );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_engine_output.
 *
 * @param string $output Template engine output.
 * @param string $name Template name.
 * @return string Modified template engine output.
 */
function aqualuxe_woocommerce_product_tabs_template_engine_output_filter( $output, $name ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_engine_output', $output, $name );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_engine_render.
 *
 * @param string $render Template engine render.
 * @param string $name Template name.
 * @param array  $args Template args.
 * @return string Modified template engine render.
 */
function aqualuxe_woocommerce_product_tabs_template_engine_render_filter( $render, $name, $args ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_engine_render', $render, $name, $args );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_engine_render_args.
 *
 * @param array  $args Template engine render args.
 * @param string $name Template name.
 * @return array Modified template engine render args.
 */
function aqualuxe_woocommerce_product_tabs_template_engine_render_args_filter( $args, $name ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_engine_render_args', $args, $name );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_engine_render_context.
 *
 * @param array  $context Template engine render context.
 * @param string $name Template name.
 * @return array Modified template engine render context.
 */
function aqualuxe_woocommerce_product_tabs_template_engine_render_context_filter( $context, $name ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_engine_render_context', $context, $name );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_engine_render_data.
 *
 * @param array  $data Template engine render data.
 * @param string $name Template name.
 * @return array Modified template engine render data.
 */
function aqualuxe_woocommerce_product_tabs_template_engine_render_data_filter( $data, $name ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_engine_render_data', $data, $name );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_engine_render_vars.
 *
 * @param array  $vars Template engine render vars.
 * @param string $name Template name.
 * @return array Modified template engine render vars.
 */
function aqualuxe_woocommerce_product_tabs_template_engine_render_vars_filter( $vars, $name ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_engine_render_vars', $vars, $name );
}

/**
 * Filter: aqualuxe_woocommerce_product_tabs_template_engine_render_output.
 *
 * @param string $output Template engine render output.
 * @param string $name Template name.
 * @return string Modified template engine render output.
 */
function aqualuxe_woocommerce_product_tabs_template_engine_render_output_filter( $output, $name ) {
	return apply_filters( 'aqualuxe_woocommerce_product_tabs_template_engine_render_output', $output, $name );
}