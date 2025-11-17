<?php
/**
 * Template functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Get template part
 *
 * @param string $slug The template slug.
 * @param string $name The template name.
 * @param array  $args The template arguments.
 */
function aqualuxe_get_template_part( $slug, $name = '', $args = [] ) {
    \AquaLuxe\Core\Helpers::get_template_part( $slug, $name, $args );
}

/**
 * Get template
 *
 * @param string $template_name The template name.
 * @param array  $args The template arguments.
 * @param string $template_path The template path.
 * @param string $default_path The default path.
 */
function aqualuxe_get_template( $template_name, $args = [], $template_path = '', $default_path = '' ) {
    \AquaLuxe\Core\Helpers::get_template( $template_name, $args, $template_path, $default_path );
}

/**
 * Locate template
 *
 * @param string $template_name The template name.
 * @param string $template_path The template path.
 * @param string $default_path The default path.
 * @return string The template path.
 */
function aqualuxe_locate_template( $template_name, $template_path = '', $default_path = '' ) {
    return \AquaLuxe\Core\Helpers::locate_template( $template_name, $template_path, $default_path );
}

/**
 * Get asset URL
 *
 * @param string $path The asset path.
 * @return string The asset URL.
 */
function aqualuxe_get_asset_url( $path ) {
    return \AquaLuxe\Core\Helpers::get_asset_url( $path );
}

/**
 * Get option
 *
 * @param string $option The option name.
 * @param mixed  $default The default value.
 * @return mixed The option value.
 */
function aqualuxe_get_option( $option, $default = false ) {
    return \AquaLuxe\Core\Helpers::get_option( $option, $default );
}

/**
 * Get post thumbnail
 *
 * @param int    $post_id The post ID.
 * @param string $size The thumbnail size.
 * @param array  $attr The thumbnail attributes.
 * @return string The post thumbnail.
 */
function aqualuxe_get_post_thumbnail( $post_id = null, $size = 'post-thumbnail', $attr = [] ) {
    return \AquaLuxe\Core\Helpers::get_post_thumbnail( $post_id, $size, $attr );
}

/**
 * Get post excerpt
 *
 * @param int    $post_id The post ID.
 * @param int    $length The excerpt length.
 * @param string $more The excerpt more.
 * @return string The post excerpt.
 */
function aqualuxe_get_post_excerpt( $post_id = null, $length = 55, $more = '&hellip;' ) {
    return \AquaLuxe\Core\Helpers::get_post_excerpt( $post_id, $length, $more );
}

/**
 * Get post categories
 *
 * @param int    $post_id The post ID.
 * @param string $separator The separator.
 * @return string The post categories.
 */
function aqualuxe_get_post_categories( $post_id = null, $separator = ', ' ) {
    return \AquaLuxe\Core\Helpers::get_post_categories( $post_id, $separator );
}

/**
 * Get post tags
 *
 * @param int    $post_id The post ID.
 * @param string $separator The separator.
 * @return string The post tags.
 */
function aqualuxe_get_post_tags( $post_id = null, $separator = ', ' ) {
    return \AquaLuxe\Core\Helpers::get_post_tags( $post_id, $separator );
}

/**
 * Get post author
 *
 * @param int $post_id The post ID.
 * @return string The post author.
 */
function aqualuxe_get_post_author( $post_id = null ) {
    return \AquaLuxe\Core\Helpers::get_post_author( $post_id );
}

/**
 * Get post date
 *
 * @param int    $post_id The post ID.
 * @param string $format The date format.
 * @return string The post date.
 */
function aqualuxe_get_post_date( $post_id = null, $format = '' ) {
    return \AquaLuxe\Core\Helpers::get_post_date( $post_id, $format );
}

/**
 * Get post comments
 *
 * @param int $post_id The post ID.
 * @return string The post comments.
 */
function aqualuxe_get_post_comments( $post_id = null ) {
    return \AquaLuxe\Core\Helpers::get_post_comments( $post_id );
}

/**
 * Get related posts
 *
 * @param int   $post_id The post ID.
 * @param int   $number The number of posts.
 * @param array $args The query arguments.
 * @return array The related posts.
 */
function aqualuxe_get_related_posts( $post_id = null, $number = 3, $args = [] ) {
    return \AquaLuxe\Core\Helpers::get_related_posts( $post_id, $number, $args );
}

/**
 * Get breadcrumbs
 *
 * @return string The breadcrumbs.
 */
function aqualuxe_get_breadcrumbs() {
    return \AquaLuxe\Core\Helpers::get_breadcrumbs();
}

/**
 * Get pagination
 *
 * @param array $args The pagination arguments.
 * @return string The pagination.
 */
function aqualuxe_get_pagination( $args = [] ) {
    return \AquaLuxe\Core\Helpers::get_pagination( $args );
}

/**
 * Get social links
 *
 * @return string The social links.
 */
function aqualuxe_get_social_links() {
    return \AquaLuxe\Core\Helpers::get_social_links();
}

/**
 * Get contact info
 *
 * @return string The contact info.
 */
function aqualuxe_get_contact_info() {
    return \AquaLuxe\Core\Helpers::get_contact_info();
}

/**
 * Get logo
 *
 * @param string $type The logo type.
 * @return string The logo.
 */
function aqualuxe_get_logo( $type = 'default' ) {
    return \AquaLuxe\Core\Helpers::get_logo( $type );
}

/**
 * Get menu
 *
 * @param string $location The menu location.
 * @param array  $args The menu arguments.
 * @return string The menu.
 */
function aqualuxe_get_menu( $location, $args = [] ) {
    return \AquaLuxe\Core\Helpers::get_menu( $location, $args );
}

/**
 * Get sidebar
 *
 * @param string $sidebar The sidebar ID.
 * @return string The sidebar.
 */
function aqualuxe_get_sidebar( $sidebar = 'sidebar-1' ) {
    return \AquaLuxe\Core\Helpers::get_sidebar( $sidebar );
}

/**
 * Get page title
 *
 * @return string The page title.
 */
function aqualuxe_get_page_title() {
    return \AquaLuxe\Core\Helpers::get_page_title();
}

/**
 * Get page description
 *
 * @return string The page description.
 */
function aqualuxe_get_page_description() {
    return \AquaLuxe\Core\Helpers::get_page_description();
}

/**
 * Is WooCommerce active
 *
 * @return bool Whether WooCommerce is active.
 */
function aqualuxe_is_woocommerce_active() {
    return \AquaLuxe\Core\Helpers::is_woocommerce_active();
}

/**
 * Is EDD active
 *
 * @return bool Whether EDD is active.
 */
function aqualuxe_is_edd_active() {
    return \AquaLuxe\Core\Helpers::is_edd_active();
}

/**
 * Is WPML active
 *
 * @return bool Whether WPML is active.
 */
function aqualuxe_is_wpml_active() {
    return \AquaLuxe\Core\Helpers::is_wpml_active();
}

/**
 * Is Polylang active
 *
 * @return bool Whether Polylang is active.
 */
function aqualuxe_is_polylang_active() {
    return \AquaLuxe\Core\Helpers::is_polylang_active();
}

/**
 * Is RTL
 *
 * @return bool Whether the site is RTL.
 */
function aqualuxe_is_rtl() {
    return \AquaLuxe\Core\Helpers::is_rtl();
}

/**
 * Is dark mode
 *
 * @return bool Whether dark mode is enabled.
 */
function aqualuxe_is_dark_mode() {
    return \AquaLuxe\Core\Helpers::is_dark_mode();
}

/**
 * Get current language
 *
 * @return string The current language.
 */
function aqualuxe_get_current_language() {
    return \AquaLuxe\Core\Helpers::get_current_language();
}

/**
 * Get language switcher
 *
 * @return string The language switcher.
 */
function aqualuxe_get_language_switcher() {
    return \AquaLuxe\Core\Helpers::get_language_switcher();
}

/**
 * Get currency switcher
 *
 * @return string The currency switcher.
 */
function aqualuxe_get_currency_switcher() {
    return \AquaLuxe\Core\Helpers::get_currency_switcher();
}

/**
 * Get mini cart
 *
 * @return string The mini cart.
 */
function aqualuxe_get_mini_cart() {
    return \AquaLuxe\Core\Helpers::get_mini_cart();
}

/**
 * Get cart count
 *
 * @return int The cart count.
 */
function aqualuxe_get_cart_count() {
    return \AquaLuxe\Core\Helpers::get_cart_count();
}

/**
 * Get cart total
 *
 * @return string The cart total.
 */
function aqualuxe_get_cart_total() {
    return \AquaLuxe\Core\Helpers::get_cart_total();
}

/**
 * Get product price
 *
 * @param int $product_id The product ID.
 * @return string The product price.
 */
function aqualuxe_get_product_price( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_price( $product_id );
}

/**
 * Get product rating
 *
 * @param int $product_id The product ID.
 * @return string The product rating.
 */
function aqualuxe_get_product_rating( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_rating( $product_id );
}

/**
 * Get product categories
 *
 * @param int    $product_id The product ID.
 * @param string $separator The separator.
 * @return string The product categories.
 */
function aqualuxe_get_product_categories( $product_id, $separator = ', ' ) {
    return \AquaLuxe\Core\Helpers::get_product_categories( $product_id, $separator );
}

/**
 * Get product tags
 *
 * @param int    $product_id The product ID.
 * @param string $separator The separator.
 * @return string The product tags.
 */
function aqualuxe_get_product_tags( $product_id, $separator = ', ' ) {
    return \AquaLuxe\Core\Helpers::get_product_tags( $product_id, $separator );
}

/**
 * Get related products
 *
 * @param int   $product_id The product ID.
 * @param int   $limit The limit.
 * @param array $args The arguments.
 * @return array The related products.
 */
function aqualuxe_get_related_products( $product_id, $limit = 3, $args = [] ) {
    return \AquaLuxe\Core\Helpers::get_related_products( $product_id, $limit, $args );
}

/**
 * Get upsell products
 *
 * @param int   $product_id The product ID.
 * @param int   $limit The limit.
 * @param array $args The arguments.
 * @return array The upsell products.
 */
function aqualuxe_get_upsell_products( $product_id, $limit = 3, $args = [] ) {
    return \AquaLuxe\Core\Helpers::get_upsell_products( $product_id, $limit, $args );
}

/**
 * Get cross-sell products
 *
 * @param int   $product_id The product ID.
 * @param int   $limit The limit.
 * @param array $args The arguments.
 * @return array The cross-sell products.
 */
function aqualuxe_get_cross_sell_products( $product_id, $limit = 3, $args = [] ) {
    return \AquaLuxe\Core\Helpers::get_cross_sell_products( $product_id, $limit, $args );
}

/**
 * Get product gallery
 *
 * @param int $product_id The product ID.
 * @return array The product gallery.
 */
function aqualuxe_get_product_gallery( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_gallery( $product_id );
}

/**
 * Get product attributes
 *
 * @param int $product_id The product ID.
 * @return array The product attributes.
 */
function aqualuxe_get_product_attributes( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_attributes( $product_id );
}

/**
 * Get product variations
 *
 * @param int $product_id The product ID.
 * @return array The product variations.
 */
function aqualuxe_get_product_variations( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_variations( $product_id );
}

/**
 * Get product stock status
 *
 * @param int $product_id The product ID.
 * @return string The product stock status.
 */
function aqualuxe_get_product_stock_status( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_stock_status( $product_id );
}

/**
 * Get product dimensions
 *
 * @param int $product_id The product ID.
 * @return string The product dimensions.
 */
function aqualuxe_get_product_dimensions( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_dimensions( $product_id );
}

/**
 * Get product weight
 *
 * @param int $product_id The product ID.
 * @return string The product weight.
 */
function aqualuxe_get_product_weight( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_weight( $product_id );
}

/**
 * Get product SKU
 *
 * @param int $product_id The product ID.
 * @return string The product SKU.
 */
function aqualuxe_get_product_sku( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_sku( $product_id );
}

/**
 * Get product reviews
 *
 * @param int $product_id The product ID.
 * @return array The product reviews.
 */
function aqualuxe_get_product_reviews( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_reviews( $product_id );
}

/**
 * Get product review count
 *
 * @param int $product_id The product ID.
 * @return int The product review count.
 */
function aqualuxe_get_product_review_count( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_review_count( $product_id );
}

/**
 * Get product average rating
 *
 * @param int $product_id The product ID.
 * @return float The product average rating.
 */
function aqualuxe_get_product_average_rating( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_average_rating( $product_id );
}

/**
 * Get product rating count
 *
 * @param int $product_id The product ID.
 * @return int The product rating count.
 */
function aqualuxe_get_product_rating_count( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_rating_count( $product_id );
}

/**
 * Get product rating counts
 *
 * @param int $product_id The product ID.
 * @return array The product rating counts.
 */
function aqualuxe_get_product_rating_counts( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_rating_counts( $product_id );
}

/**
 * Get product add to cart URL
 *
 * @param int $product_id The product ID.
 * @return string The product add to cart URL.
 */
function aqualuxe_get_product_add_to_cart_url( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_add_to_cart_url( $product_id );
}

/**
 * Get product add to cart text
 *
 * @param int $product_id The product ID.
 * @return string The product add to cart text.
 */
function aqualuxe_get_product_add_to_cart_text( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_add_to_cart_text( $product_id );
}

/**
 * Get product permalink
 *
 * @param int $product_id The product ID.
 * @return string The product permalink.
 */
function aqualuxe_get_product_permalink( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_permalink( $product_id );
}

/**
 * Get product thumbnail
 *
 * @param int    $product_id The product ID.
 * @param string $size The thumbnail size.
 * @return string The product thumbnail.
 */
function aqualuxe_get_product_thumbnail( $product_id, $size = 'woocommerce_thumbnail' ) {
    return \AquaLuxe\Core\Helpers::get_product_thumbnail( $product_id, $size );
}

/**
 * Get product short description
 *
 * @param int $product_id The product ID.
 * @return string The product short description.
 */
function aqualuxe_get_product_short_description( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_short_description( $product_id );
}

/**
 * Get product description
 *
 * @param int $product_id The product ID.
 * @return string The product description.
 */
function aqualuxe_get_product_description( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_description( $product_id );
}

/**
 * Get product type
 *
 * @param int $product_id The product ID.
 * @return string The product type.
 */
function aqualuxe_get_product_type( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_type( $product_id );
}

/**
 * Get product price
 *
 * @param int $product_id The product ID.
 * @return float The product price.
 */
function aqualuxe_get_product_price_raw( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_price_raw( $product_id );
}

/**
 * Get product regular price
 *
 * @param int $product_id The product ID.
 * @return float The product regular price.
 */
function aqualuxe_get_product_regular_price( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_regular_price( $product_id );
}

/**
 * Get product sale price
 *
 * @param int $product_id The product ID.
 * @return float The product sale price.
 */
function aqualuxe_get_product_sale_price( $product_id ) {
    return \AquaLuxe\Core\Helpers::get_product_sale_price( $product_id );
}

/**
 * Is product on sale
 *
 * @param int $product_id The product ID.
 * @return bool Whether the product is on sale.
 */
function aqualuxe_is_product_on_sale( $product_id ) {
    return \AquaLuxe\Core\Helpers::is_product_on_sale( $product_id );
}

/**
 * Is product in stock
 *
 * @param int $product_id The product ID.
 * @return bool Whether the product is in stock.
 */
function aqualuxe_is_product_in_stock( $product_id ) {
    return \AquaLuxe\Core\Helpers::is_product_in_stock( $product_id );
}

/**
 * Is product purchasable
 *
 * @param int $product_id The product ID.
 * @return bool Whether the product is purchasable.
 */
function aqualuxe_is_product_purchasable( $product_id ) {
    return \AquaLuxe\Core\Helpers::is_product_purchasable( $product_id );
}

/**
 * Is product downloadable
 *
 * @param int $product_id The product ID.
 * @return bool Whether the product is downloadable.
 */
function aqualuxe_is_product_downloadable( $product_id ) {
    return \AquaLuxe\Core\Helpers::is_product_downloadable( $product_id );
}

/**
 * Is product virtual
 *
 * @param int $product_id The product ID.
 * @return bool Whether the product is virtual.
 */
function aqualuxe_is_product_virtual( $product_id ) {
    return \AquaLuxe\Core\Helpers::is_product_virtual( $product_id );
}

/**
 * Is product featured
 *
 * @param int $product_id The product ID.
 * @return bool Whether the product is featured.
 */
function aqualuxe_is_product_featured( $product_id ) {
    return \AquaLuxe\Core\Helpers::is_product_featured( $product_id );
}

/**
 * Is product visible
 *
 * @param int $product_id The product ID.
 * @return bool Whether the product is visible.
 */
function aqualuxe_is_product_visible( $product_id ) {
    return \AquaLuxe\Core\Helpers::is_product_visible( $product_id );
}

/**
 * Do hook
 *
 * @param string $hook_name The hook name.
 * @param mixed  ...$args The hook arguments.
 */
function aqualuxe_do_hook( $hook_name, ...$args ) {
    \AquaLuxe\Core\Hooks::do_hook( $hook_name, ...$args );
}

/**
 * Apply hook filter
 *
 * @param string $hook_name The hook name.
 * @param mixed  $value The value to filter.
 * @param mixed  ...$args The hook arguments.
 * @return mixed The filtered value.
 */
function aqualuxe_apply_hook_filter( $hook_name, $value, ...$args ) {
    return \AquaLuxe\Core\Hooks::apply_hook_filter( $hook_name, $value, ...$args );
}

/**
 * Get hooks
 *
 * @return array The hooks.
 */
function aqualuxe_get_hooks() {
    return \AquaLuxe\Core\Hooks::get_hooks();
}

/**
 * Get hook
 *
 * @param string $hook_name The hook name.
 * @return array|false The hook or false if not found.
 */
function aqualuxe_get_hook( $hook_name ) {
    return \AquaLuxe\Core\Hooks::get_hook( $hook_name );
}

/**
 * Hook exists
 *
 * @param string $hook_name The hook name.
 * @return bool Whether the hook exists.
 */
function aqualuxe_hook_exists( $hook_name ) {
    return \AquaLuxe\Core\Hooks::hook_exists( $hook_name );
}

/**
 * Register hook
 *
 * @param string $hook_name The hook name.
 * @param array  $args The hook arguments.
 */
function aqualuxe_register_hook( $hook_name, $args ) {
    \AquaLuxe\Core\Hooks::register_hook( $hook_name, $args );
}

/**
 * Get active modules
 *
 * @return array The active modules.
 */
function aqualuxe_get_active_modules() {
    $module_loader = new \AquaLuxe\Core\Module_Loader();
    return $module_loader->get_active_modules();
}

/**
 * Get module
 *
 * @param string $module_id The module ID.
 * @return object|false The module instance or false if not found.
 */
function aqualuxe_get_module( $module_id ) {
    $module_loader = new \AquaLuxe\Core\Module_Loader();
    return $module_loader->get_module( $module_id );
}

/**
 * Is module active
 *
 * @param string $module_id The module ID.
 * @return bool Whether the module is active.
 */
function aqualuxe_is_module_active( $module_id ) {
    $module_loader = new \AquaLuxe\Core\Module_Loader();
    return $module_loader->is_module_active( $module_id );
}

/**
 * Is module required
 *
 * @param string $module_id The module ID.
 * @return bool Whether the module is required.
 */
function aqualuxe_is_module_required( $module_id ) {
    $module_loader = new \AquaLuxe\Core\Module_Loader();
    return $module_loader->is_module_required( $module_id );
}

/**
 * Get available modules
 *
 * @return array The available modules.
 */
function aqualuxe_get_available_modules() {
    $module_loader = new \AquaLuxe\Core\Module_Loader();
    return $module_loader->get_available_modules();
}

/**
 * Body classes
 *
 * @param array $classes The body classes.
 * @return array The modified body classes.
 */
function aqualuxe_body_classes( $classes ) {
    // Add a class if there is a custom header
    if ( has_custom_header() ) {
        $classes[] = 'has-custom-header';
    }

    // Add a class if there is a custom background
    if ( get_background_image() ) {
        $classes[] = 'has-custom-background';
    }

    // Add a class if the sidebar is active
    if ( is_active_sidebar( 'sidebar-1' ) && ! is_page_template( 'templates/full-width.php' ) ) {
        $classes[] = 'has-sidebar';
    } else {
        $classes[] = 'no-sidebar';
    }

    // Add a class for the layout
    $layout = get_theme_mod( 'aqualuxe_layout', 'right-sidebar' );
    $classes[] = 'layout-' . sanitize_html_class( $layout );

    // Add a class for the header layout
    $header_layout = get_theme_mod( 'aqualuxe_header_layout', 'default' );
    $classes[] = 'header-' . sanitize_html_class( $header_layout );

    // Add a class for the footer layout
    $footer_layout = get_theme_mod( 'aqualuxe_footer_layout', 'default' );
    $classes[] = 'footer-' . sanitize_html_class( $footer_layout );

    // Add a class for dark mode
    if ( aqualuxe_is_dark_mode() ) {
        $classes[] = 'dark-mode';
    }

    // Add a class for RTL
    if ( is_rtl() ) {
        $classes[] = 'rtl';
    }

    // Add a class for WooCommerce
    if ( aqualuxe_is_woocommerce_active() ) {
        $classes[] = 'woocommerce-active';

        // Add a class for the shop layout
        if ( is_shop() || is_product_category() || is_product_tag() ) {
            $shop_layout = get_theme_mod( 'aqualuxe_shop_layout', 'grid' );
            $classes[] = 'shop-layout-' . sanitize_html_class( $shop_layout );
        }
    }

    return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );

/**
 * Pingback header
 */
function aqualuxe_pingback_header() {
    if ( is_singular() && pings_open() ) {
        printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
    }
}
add_action( 'wp_head', 'aqualuxe_pingback_header' );

/**
 * Excerpt length
 *
 * @param int $length The excerpt length.
 * @return int The modified excerpt length.
 */
function aqualuxe_excerpt_length( $length ) {
    return get_theme_mod( 'aqualuxe_blog_excerpt_length', 30 );
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length' );

/**
 * Excerpt more
 *
 * @param string $more The excerpt more.
 * @return string The modified excerpt more.
 */
function aqualuxe_excerpt_more( $more ) {
    return '&hellip;';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Content width
 */
function aqualuxe_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'aqualuxe_content_width', 1200 );
}
add_action( 'template_redirect', 'aqualuxe_content_width' );

/**
 * Add async/defer attributes to scripts
 *
 * @param string $tag The script tag.
 * @param string $handle The script handle.
 * @param string $src The script source.
 * @return string The modified script tag.
 */
function aqualuxe_script_attributes( $tag, $handle, $src ) {
    // Add async attribute to specific scripts
    $async_scripts = apply_filters(
        'aqualuxe_async_scripts',
        [
            'aqualuxe-dark-mode',
            'aqualuxe-multilingual',
        ]
    );

    if ( in_array( $handle, $async_scripts, true ) ) {
        return str_replace( ' src', ' async src', $tag );
    }

    // Add defer attribute to specific scripts
    $defer_scripts = apply_filters(
        'aqualuxe_defer_scripts',
        [
            'aqualuxe-main',
            'aqualuxe-woocommerce',
        ]
    );

    if ( in_array( $handle, $defer_scripts, true ) ) {
        return str_replace( ' src', ' defer src', $tag );
    }

    return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_script_attributes', 10, 3 );

/**
 * Add preload to fonts
 *
 * @param string $html The HTML.
 * @param string $handle The style handle.
 * @param string $href The style href.
 * @param string $media The style media.
 * @return string The modified HTML.
 */
function aqualuxe_preload_fonts( $html, $handle, $href, $media ) {
    if ( get_theme_mod( 'aqualuxe_preload_fonts', false ) ) {
        $preload_fonts = apply_filters(
            'aqualuxe_preload_fonts',
            [
                'aqualuxe-fonts',
            ]
        );

        if ( in_array( $handle, $preload_fonts, true ) ) {
            $html = '<link rel="preload" as="style" href="' . esc_url( $href ) . '" media="' . esc_attr( $media ) . '" onload="this.onload=null;this.rel=\'stylesheet\'">' . $html;
        }
    }

    return $html;
}
add_filter( 'style_loader_tag', 'aqualuxe_preload_fonts', 10, 4 );

/**
 * Add lazy loading to images
 *
 * @param string $content The content.
 * @return string The modified content.
 */
function aqualuxe_lazy_load_images( $content ) {
    if ( get_theme_mod( 'aqualuxe_lazy_load_images', true ) ) {
        $content = preg_replace( '/<img(.*?)src="(.*?)"(.*?)>/i', '<img$1src="$2"$3 loading="lazy">', $content );
    }

    return $content;
}
add_filter( 'the_content', 'aqualuxe_lazy_load_images' );

/**
 * Add schema markup
 *
 * @param array $attributes The attributes.
 * @return array The modified attributes.
 */
function aqualuxe_schema_markup( $attributes ) {
    if ( is_singular( 'post' ) ) {
        $attributes['itemscope'] = '';
        $attributes['itemtype'] = 'https://schema.org/BlogPosting';
    } elseif ( is_page() ) {
        $attributes['itemscope'] = '';
        $attributes['itemtype'] = 'https://schema.org/WebPage';
    } elseif ( is_search() ) {
        $attributes['itemscope'] = '';
        $attributes['itemtype'] = 'https://schema.org/SearchResultsPage';
    } elseif ( is_archive() ) {
        $attributes['itemscope'] = '';
        $attributes['itemtype'] = 'https://schema.org/CollectionPage';
    } elseif ( aqualuxe_is_woocommerce_active() && is_product() ) {
        $attributes['itemscope'] = '';
        $attributes['itemtype'] = 'https://schema.org/Product';
    } elseif ( aqualuxe_is_woocommerce_active() && is_shop() ) {
        $attributes['itemscope'] = '';
        $attributes['itemtype'] = 'https://schema.org/Store';
    }

    return $attributes;
}
add_filter( 'aqualuxe_html_attributes', 'aqualuxe_schema_markup' );

/**
 * Add Open Graph meta tags
 */
function aqualuxe_open_graph_meta_tags() {
    global $post;

    if ( is_singular() && $post ) {
        $title = get_the_title();
        $description = get_the_excerpt();
        $url = get_permalink();
        $type = is_singular( 'post' ) ? 'article' : 'website';
        $image = has_post_thumbnail() ? get_the_post_thumbnail_url( $post, 'large' ) : '';

        echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
        echo '<meta property="og:type" content="' . esc_attr( $type ) . '" />' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";

        if ( $image ) {
            echo '<meta property="og:image" content="' . esc_url( $image ) . '" />' . "\n";
        }
    }
}
add_action( 'wp_head', 'aqualuxe_open_graph_meta_tags' );

/**
 * Add Twitter Card meta tags
 */
function aqualuxe_twitter_card_meta_tags() {
    global $post;

    if ( is_singular() && $post ) {
        $title = get_the_title();
        $description = get_the_excerpt();
        $image = has_post_thumbnail() ? get_the_post_thumbnail_url( $post, 'large' ) : '';

        echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '" />' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '" />' . "\n";

        if ( $image ) {
            echo '<meta name="twitter:image" content="' . esc_url( $image ) . '" />' . "\n";
        }
    }
}
add_action( 'wp_head', 'aqualuxe_twitter_card_meta_tags' );

/**
 * Add custom header scripts
 */
function aqualuxe_header_scripts() {
    $header_scripts = get_theme_mod( 'aqualuxe_header_scripts', '' );

    if ( $header_scripts ) {
        echo $header_scripts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
add_action( 'wp_head', 'aqualuxe_header_scripts' );

/**
 * Add custom footer scripts
 */
function aqualuxe_footer_scripts() {
    $footer_scripts = get_theme_mod( 'aqualuxe_footer_scripts', '' );

    if ( $footer_scripts ) {
        echo $footer_scripts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
add_action( 'wp_footer', 'aqualuxe_footer_scripts' );

/**
 * Add custom CSS
 */
function aqualuxe_custom_css() {
    $custom_css = get_theme_mod( 'aqualuxe_custom_css', '' );

    if ( $custom_css ) {
        echo '<style id="aqualuxe-custom-css">' . wp_strip_all_tags( $custom_css ) . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
add_action( 'wp_head', 'aqualuxe_custom_css' );

/**
 * Minify HTML
 *
 * @param string $html The HTML.
 * @return string The minified HTML.
 */
function aqualuxe_minify_html( $html ) {
    if ( get_theme_mod( 'aqualuxe_minify_html', false ) ) {
        $search = [
            '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
            '/[^\S ]+\</s',     // strip whitespaces before tags, except space
            '/(\s)+/s',         // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/', // remove HTML comments
        ];

        $replace = [
            '>',
            '<',
            '\\1',
            '',
        ];

        $html = preg_replace( $search, $replace, $html );
    }

    return $html;
}
add_filter( 'final_output', 'aqualuxe_minify_html' );

/**
 * Add defer to JavaScript files
 *
 * @param string $tag The script tag.
 * @param string $handle The script handle.
 * @param string $src The script source.
 * @return string The modified script tag.
 */
function aqualuxe_defer_js( $tag, $handle, $src ) {
    if ( get_theme_mod( 'aqualuxe_defer_js', false ) ) {
        if ( strpos( $handle, 'aqualuxe-' ) !== false && strpos( $tag, 'defer' ) === false ) {
            return str_replace( ' src', ' defer src', $tag );
        }
    }

    return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_defer_js', 10, 3 );

/**
 * Add HTML attributes
 *
 * @param array $attributes The attributes.
 * @return array The modified attributes.
 */
function aqualuxe_html_attributes( $attributes ) {
    $attributes['lang'] = get_bloginfo( 'language' );
    $attributes['dir'] = is_rtl() ? 'rtl' : 'ltr';

    if ( aqualuxe_is_dark_mode() ) {
        $attributes['class'] = isset( $attributes['class'] ) ? $attributes['class'] . ' dark-mode' : 'dark-mode';
    }

    return $attributes;
}
add_filter( 'aqualuxe_html_attributes', 'aqualuxe_html_attributes' );

/**
 * Get HTML attributes
 *
 * @return string The HTML attributes.
 */
function aqualuxe_get_html_attributes() {
    $attributes = apply_filters( 'aqualuxe_html_attributes', [] );
    $output = '';

    foreach ( $attributes as $key => $value ) {
        $output .= ' ' . $key . '="' . esc_attr( $value ) . '"';
    }

    return $output;
}

/**
 * Get body attributes
 *
 * @return string The body attributes.
 */
function aqualuxe_get_body_attributes() {
    $attributes = apply_filters( 'aqualuxe_body_attributes', [] );
    $output = '';

    foreach ( $attributes as $key => $value ) {
        $output .= ' ' . $key . '="' . esc_attr( $value ) . '"';
    }

    return $output;
}

/**
 * Add body attributes
 *
 * @param array $attributes The attributes.
 * @return array The modified attributes.
 */
function aqualuxe_body_attributes( $attributes ) {
    if ( aqualuxe_is_dark_mode() ) {
        $attributes['class'] = isset( $attributes['class'] ) ? $attributes['class'] . ' dark-mode' : 'dark-mode';
    }

    return $attributes;
}
add_filter( 'aqualuxe_body_attributes', 'aqualuxe_body_attributes' );

/**
 * Add custom classes to the array of body classes
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function aqualuxe_body_classes_filter( $classes ) {
    // Add a class if there is a custom header
    if ( has_custom_header() ) {
        $classes[] = 'has-custom-header';
    }

    // Add a class if there is a custom background
    if ( get_background_image() ) {
        $classes[] = 'has-custom-background';
    }

    // Add a class if the sidebar is active
    if ( is_active_sidebar( 'sidebar-1' ) && ! is_page_template( 'templates/full-width.php' ) ) {
        $classes[] = 'has-sidebar';
    } else {
        $classes[] = 'no-sidebar';
    }

    // Add a class for the layout
    $layout = get_theme_mod( 'aqualuxe_layout', 'right-sidebar' );
    $classes[] = 'layout-' . sanitize_html_class( $layout );

    // Add a class for the header layout
    $header_layout = get_theme_mod( 'aqualuxe_header_layout', 'default' );
    $classes[] = 'header-' . sanitize_html_class( $header_layout );

    // Add a class for the footer layout
    $footer_layout = get_theme_mod( 'aqualuxe_footer_layout', 'default' );
    $classes[] = 'footer-' . sanitize_html_class( $footer_layout );

    // Add a class for dark mode
    if ( aqualuxe_is_dark_mode() ) {
        $classes[] = 'dark-mode';
    }

    // Add a class for RTL
    if ( is_rtl() ) {
        $classes[] = 'rtl';
    }

    // Add a class for WooCommerce
    if ( aqualuxe_is_woocommerce_active() ) {
        $classes[] = 'woocommerce-active';

        // Add a class for the shop layout
        if ( is_shop() || is_product_category() || is_product_tag() ) {
            $shop_layout = get_theme_mod( 'aqualuxe_shop_layout', 'grid' );
            $classes[] = 'shop-layout-' . sanitize_html_class( $shop_layout );
        }
    }

    return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes_filter' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments
 */
function aqualuxe_pingback_header_filter() {
    if ( is_singular() && pings_open() ) {
        printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
    }
}
add_action( 'wp_head', 'aqualuxe_pingback_header_filter' );

/**
 * Filter the excerpt length
 *
 * @param int $length Excerpt length.
 * @return int Modified excerpt length.
 */
function aqualuxe_excerpt_length_filter( $length ) {
    return get_theme_mod( 'aqualuxe_blog_excerpt_length', 30 );
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length_filter' );

/**
 * Filter the excerpt "read more" string
 *
 * @param string $more "Read more" excerpt string.
 * @return string Modified "read more" excerpt string.
 */
function aqualuxe_excerpt_more_filter( $more ) {
    return '&hellip;';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more_filter' );

/**
 * Set the content width in pixels
 */
function aqualuxe_content_width_filter() {
    $GLOBALS['content_width'] = apply_filters( 'aqualuxe_content_width', 1200 );
}
add_action( 'template_redirect', 'aqualuxe_content_width_filter' );

/**
 * Add async/defer attributes to scripts
 *
 * @param string $tag The script tag.
 * @param string $handle The script handle.
 * @param string $src The script source.
 * @return string The modified script tag.
 */
function aqualuxe_script_attributes_filter( $tag, $handle, $src ) {
    // Add async attribute to specific scripts
    $async_scripts = apply_filters(
        'aqualuxe_async_scripts',
        [
            'aqualuxe-dark-mode',
            'aqualuxe-multilingual',
        ]
    );

    if ( in_array( $handle, $async_scripts, true ) ) {
        return str_replace( ' src', ' async src', $tag );
    }

    // Add defer attribute to specific scripts
    $defer_scripts = apply_filters(
        'aqualuxe_defer_scripts',
        [
            'aqualuxe-main',
            'aqualuxe-woocommerce',
        ]
    );

    if ( in_array( $handle, $defer_scripts, true ) ) {
        return str_replace( ' src', ' defer src', $tag );
    }

    return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_script_attributes_filter', 10, 3 );

/**
 * Add preload to fonts
 *
 * @param string $html The HTML.
 * @param string $handle The style handle.
 * @param string $href The style href.
 * @param string $media The style media.
 * @return string The modified HTML.
 */
function aqualuxe_preload_fonts_filter( $html, $handle, $href, $media ) {
    if ( get_theme_mod( 'aqualuxe_preload_fonts', false ) ) {
        $preload_fonts = apply_filters(
            'aqualuxe_preload_fonts',
            [
                'aqualuxe-fonts',
            ]
        );

        if ( in_array( $handle, $preload_fonts, true ) ) {
            $html = '<link rel="preload" as="style" href="' . esc_url( $href ) . '" media="' . esc_attr( $media ) . '" onload="this.onload=null;this.rel=\'stylesheet\'">' . $html;
        }
    }

    return $html;
}
add_filter( 'style_loader_tag', 'aqualuxe_preload_fonts_filter', 10, 4 );

/**
 * Add lazy loading to images
 *
 * @param string $content The content.
 * @return string The modified content.
 */
function aqualuxe_lazy_load_images_filter( $content ) {
    if ( get_theme_mod( 'aqualuxe_lazy_load_images', true ) ) {
        $content = preg_replace( '/<img(.*?)src="(.*?)"(.*?)>/i', '<img$1src="$2"$3 loading="lazy">', $content );
    }

    return $content;
}
add_filter( 'the_content', 'aqualuxe_lazy_load_images_filter' );

/**
 * Add schema markup
 *
 * @param array $attributes The attributes.
 * @return array The modified attributes.
 */
function aqualuxe_schema_markup_filter( $attributes ) {
    if ( is_singular( 'post' ) ) {
        $attributes['itemscope'] = '';
        $attributes['itemtype'] = 'https://schema.org/BlogPosting';
    } elseif ( is_page() ) {
        $attributes['itemscope'] = '';
        $attributes['itemtype'] = 'https://schema.org/WebPage';
    } elseif ( is_search() ) {
        $attributes['itemscope'] = '';
        $attributes['itemtype'] = 'https://schema.org/SearchResultsPage';
    } elseif ( is_archive() ) {
        $attributes['itemscope'] = '';
        $attributes['itemtype'] = 'https://schema.org/CollectionPage';
    } elseif ( aqualuxe_is_woocommerce_active() && is_product() ) {
        $attributes['itemscope'] = '';
        $attributes['itemtype'] = 'https://schema.org/Product';
    } elseif ( aqualuxe_is_woocommerce_active() && is_shop() ) {
        $attributes['itemscope'] = '';
        $attributes['itemtype'] = 'https://schema.org/Store';
    }

    return $attributes;
}
add_filter( 'aqualuxe_html_attributes', 'aqualuxe_schema_markup_filter' );

/**
 * Add Open Graph meta tags
 */
function aqualuxe_open_graph_meta_tags_filter() {
    global $post;

    if ( is_singular() && $post ) {
        $title = get_the_title();
        $description = get_the_excerpt();
        $url = get_permalink();
        $type = is_singular( 'post' ) ? 'article' : 'website';
        $image = has_post_thumbnail() ? get_the_post_thumbnail_url( $post, 'large' ) : '';

        echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
        echo '<meta property="og:type" content="' . esc_attr( $type ) . '" />' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";

        if ( $image ) {
            echo '<meta property="og:image" content="' . esc_url( $image ) . '" />' . "\n";
        }
    }
}
add_action( 'wp_head', 'aqualuxe_open_graph_meta_tags_filter' );

/**
 * Add Twitter Card meta tags
 */
function aqualuxe_twitter_card_meta_tags_filter() {
    global $post;

    if ( is_singular() && $post ) {
        $title = get_the_title();
        $description = get_the_excerpt();
        $image = has_post_thumbnail() ? get_the_post_thumbnail_url( $post, 'large' ) : '';

        echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '" />' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '" />' . "\n";

        if ( $image ) {
            echo '<meta name="twitter:image" content="' . esc_url( $image ) . '" />' . "\n";
        }
    }
}
add_action( 'wp_head', 'aqualuxe_twitter_card_meta_tags_filter' );

/**
 * Add custom header scripts
 */
function aqualuxe_header_scripts_filter() {
    $header_scripts = get_theme_mod( 'aqualuxe_header_scripts', '' );

    if ( $header_scripts ) {
        echo $header_scripts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
add_action( 'wp_head', 'aqualuxe_header_scripts_filter' );

/**
 * Add custom footer scripts
 */
function aqualuxe_footer_scripts_filter() {
    $footer_scripts = get_theme_mod( 'aqualuxe_footer_scripts', '' );

    if ( $footer_scripts ) {
        echo $footer_scripts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
add_action( 'wp_footer', 'aqualuxe_footer_scripts_filter' );

/**
 * Add custom CSS
 */
function aqualuxe_custom_css_filter() {
    $custom_css = get_theme_mod( 'aqualuxe_custom_css', '' );

    if ( $custom_css ) {
        echo '<style id="aqualuxe-custom-css">' . wp_strip_all_tags( $custom_css ) . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
add_action( 'wp_head', 'aqualuxe_custom_css_filter' );

/**
 * Minify HTML
 *
 * @param string $html The HTML.
 * @return string The minified HTML.
 */
function aqualuxe_minify_html_filter( $html ) {
    if ( get_theme_mod( 'aqualuxe_minify_html', false ) ) {
        $search = [
            '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
            '/[^\S ]+\</s',     // strip whitespaces before tags, except space
            '/(\s)+/s',         // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/', // remove HTML comments
        ];

        $replace = [
            '>',
            '<',
            '\\1',
            '',
        ];

        $html = preg_replace( $search, $replace, $html );
    }

    return $html;
}
add_filter( 'final_output', 'aqualuxe_minify_html_filter' );

/**
 * Add defer to JavaScript files
 *
 * @param string $tag The script tag.
 * @param string $handle The script handle.
 * @param string $src The script source.
 * @return string The modified script tag.
 */
function aqualuxe_defer_js_filter( $tag, $handle, $src ) {
    if ( get_theme_mod( 'aqualuxe_defer_js', false ) ) {
        if ( strpos( $handle, 'aqualuxe-' ) !== false && strpos( $tag, 'defer' ) === false ) {
            return str_replace( ' src', ' defer src', $tag );
        }
    }

    return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_defer_js_filter', 10, 3 );

/**
 * Add HTML attributes
 *
 * @param array $attributes The attributes.
 * @return array The modified attributes.
 */
function aqualuxe_html_attributes_filter( $attributes ) {
    $attributes['lang'] = get_bloginfo( 'language' );
    $attributes['dir'] = is_rtl() ? 'rtl' : 'ltr';

    if ( aqualuxe_is_dark_mode() ) {
        $attributes['class'] = isset( $attributes['class'] ) ? $attributes['class'] . ' dark-mode' : 'dark-mode';
    }

    return $attributes;
}
add_filter( 'aqualuxe_html_attributes', 'aqualuxe_html_attributes_filter' );

/**
 * Add body attributes
 *
 * @param array $attributes The attributes.
 * @return array The modified attributes.
 */
function aqualuxe_body_attributes_filter( $attributes ) {
    if ( aqualuxe_is_dark_mode() ) {
        $attributes['class'] = isset( $attributes['class'] ) ? $attributes['class'] . ' dark-mode' : 'dark-mode';
    }

    return $attributes;
}
add_filter( 'aqualuxe_body_attributes', 'aqualuxe_body_attributes_filter' );