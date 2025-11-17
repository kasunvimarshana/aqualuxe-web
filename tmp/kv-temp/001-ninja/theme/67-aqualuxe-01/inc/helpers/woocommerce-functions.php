<?php
/**
 * WooCommerce Functions
 *
 * @package AquaLuxe
 * @subpackage Helpers
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Check if WooCommerce is active
 *
 * @return boolean
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists( 'WooCommerce' );
}

/**
 * Check if we're on a WooCommerce page
 *
 * @return boolean
 */
function aqualuxe_is_woocommerce_page() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
}

/**
 * Get products per page
 *
 * @return int
 */
function aqualuxe_get_products_per_page() {
    return get_theme_mod( 'aqualuxe_products_per_page', 12 );
}

/**
 * Get product columns
 *
 * @return int
 */
function aqualuxe_get_product_columns() {
    return get_theme_mod( 'aqualuxe_product_columns', 4 );
}

/**
 * Get shop layout
 *
 * @return string
 */
function aqualuxe_get_shop_layout() {
    // Check if we have a cookie set for the view
    if ( isset( $_COOKIE['aqualuxe_shop_view'] ) ) {
        return sanitize_text_field( $_COOKIE['aqualuxe_shop_view'] );
    }
    
    // Use default shop layout
    return get_theme_mod( 'aqualuxe_shop_layout', 'grid' );
}

/**
 * Get shop sidebar position
 *
 * @return string
 */
function aqualuxe_get_shop_sidebar_position() {
    return get_theme_mod( 'aqualuxe_shop_sidebar_position', 'left' );
}

/**
 * Get related products count
 *
 * @return int
 */
function aqualuxe_get_related_products_count() {
    return get_theme_mod( 'aqualuxe_related_products_count', 4 );
}

/**
 * Get cross-sells count
 *
 * @return int
 */
function aqualuxe_get_cross_sells_count() {
    return get_theme_mod( 'aqualuxe_related_products_count', 4 );
}

/**
 * Get product hover effect
 *
 * @return string
 */
function aqualuxe_get_product_hover_effect() {
    return get_theme_mod( 'aqualuxe_product_hover_effect', 'zoom' );
}

/**
 * Get sale badge style
 *
 * @return string
 */
function aqualuxe_get_sale_badge_style() {
    return get_theme_mod( 'aqualuxe_sale_badge_style', 'circle' );
}

/**
 * Get checkout layout
 *
 * @return string
 */
function aqualuxe_get_checkout_layout() {
    return get_theme_mod( 'aqualuxe_checkout_layout', 'default' );
}

/**
 * Check if quick view is enabled
 *
 * @return boolean
 */
function aqualuxe_is_quick_view_enabled() {
    return get_theme_mod( 'aqualuxe_quick_view', true );
}

/**
 * Check if wishlist is enabled
 *
 * @return boolean
 */
function aqualuxe_is_wishlist_enabled() {
    return get_theme_mod( 'aqualuxe_wishlist', true );
}

/**
 * Check if AJAX add to cart is enabled
 *
 * @return boolean
 */
function aqualuxe_is_ajax_add_to_cart_enabled() {
    return get_theme_mod( 'aqualuxe_ajax_add_to_cart', true );
}

/**
 * Check if sticky add to cart is enabled
 *
 * @return boolean
 */
function aqualuxe_is_sticky_add_to_cart_enabled() {
    return get_theme_mod( 'aqualuxe_sticky_add_to_cart', true );
}

/**
 * Check if product gallery zoom is enabled
 *
 * @return boolean
 */
function aqualuxe_is_product_gallery_zoom_enabled() {
    return get_theme_mod( 'aqualuxe_product_gallery_zoom', true );
}

/**
 * Check if product gallery lightbox is enabled
 *
 * @return boolean
 */
function aqualuxe_is_product_gallery_lightbox_enabled() {
    return get_theme_mod( 'aqualuxe_product_gallery_lightbox', true );
}

/**
 * Check if product gallery slider is enabled
 *
 * @return boolean
 */
function aqualuxe_is_product_gallery_slider_enabled() {
    return get_theme_mod( 'aqualuxe_product_gallery_slider', true );
}

/**
 * Check if related products are enabled
 *
 * @return boolean
 */
function aqualuxe_is_related_products_enabled() {
    return get_theme_mod( 'aqualuxe_related_products', true );
}

/**
 * Check if upsells are enabled
 *
 * @return boolean
 */
function aqualuxe_is_upsells_enabled() {
    return get_theme_mod( 'aqualuxe_upsells', true );
}

/**
 * Check if cross-sells are enabled
 *
 * @return boolean
 */
function aqualuxe_is_cross_sells_enabled() {
    return get_theme_mod( 'aqualuxe_cross_sells', true );
}

/**
 * Get wishlist
 *
 * @return array
 */
function aqualuxe_get_wishlist() {
    // Get wishlist from cookie
    $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( stripslashes( $_COOKIE['aqualuxe_wishlist'] ), true ) : [];
    
    // Get wishlist from user meta
    if ( is_user_logged_in() ) {
        $user_wishlist = get_user_meta( get_current_user_id(), 'aqualuxe_wishlist', true );
        
        if ( $user_wishlist ) {
            $wishlist = array_unique( array_merge( $wishlist, $user_wishlist ) );
        }
    }
    
    return $wishlist;
}

/**
 * Check if product is in wishlist
 *
 * @param int $product_id Product ID.
 * @return boolean
 */
function aqualuxe_is_product_in_wishlist( $product_id ) {
    $wishlist = aqualuxe_get_wishlist();
    
    return in_array( $product_id, $wishlist );
}

/**
 * Add product to wishlist
 *
 * @param int $product_id Product ID.
 * @return boolean
 */
function aqualuxe_add_to_wishlist( $product_id ) {
    // Get wishlist
    $wishlist = aqualuxe_get_wishlist();
    
    // Add product to wishlist
    if ( ! in_array( $product_id, $wishlist ) ) {
        $wishlist[] = $product_id;
    }
    
    // Save wishlist to cookie
    setcookie( 'aqualuxe_wishlist', json_encode( $wishlist ), time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
    
    // Save wishlist to user meta
    if ( is_user_logged_in() ) {
        update_user_meta( get_current_user_id(), 'aqualuxe_wishlist', $wishlist );
    }
    
    return true;
}

/**
 * Remove product from wishlist
 *
 * @param int $product_id Product ID.
 * @return boolean
 */
function aqualuxe_remove_from_wishlist( $product_id ) {
    // Get wishlist
    $wishlist = aqualuxe_get_wishlist();
    
    // Remove product from wishlist
    $wishlist = array_diff( $wishlist, [ $product_id ] );
    
    // Save wishlist to cookie
    setcookie( 'aqualuxe_wishlist', json_encode( $wishlist ), time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
    
    // Save wishlist to user meta
    if ( is_user_logged_in() ) {
        update_user_meta( get_current_user_id(), 'aqualuxe_wishlist', $wishlist );
    }
    
    return true;
}

/**
 * Get product price HTML with currency
 *
 * @param object $product Product object.
 * @return string
 */
function aqualuxe_get_product_price_html( $product ) {
    if ( ! $product ) {
        return '';
    }
    
    return $product->get_price_html();
}

/**
 * Get formatted sale price
 *
 * @param object $product Product object.
 * @return string
 */
function aqualuxe_get_sale_price_html( $product ) {
    if ( ! $product || ! $product->is_on_sale() ) {
        return '';
    }
    
    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();
    
    if ( $regular_price && $sale_price ) {
        $discount = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
        
        return sprintf( esc_html__( '%s%% Off', 'aqualuxe' ), $discount );
    }
    
    return '';
}

/**
 * Get product rating HTML
 *
 * @param object $product Product object.
 * @return string
 */
function aqualuxe_get_product_rating_html( $product ) {
    if ( ! $product || ! $product->get_average_rating() ) {
        return '';
    }
    
    return wc_get_rating_html( $product->get_average_rating() );
}

/**
 * Get product categories HTML
 *
 * @param object $product Product object.
 * @return string
 */
function aqualuxe_get_product_categories_html( $product ) {
    if ( ! $product || ! $product->get_category_ids() ) {
        return '';
    }
    
    return wc_get_product_category_list( $product->get_id(), ', ', '<div class="product-categories">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'aqualuxe' ) . ' ', '</div>' );
}

/**
 * Get product tags HTML
 *
 * @param object $product Product object.
 * @return string
 */
function aqualuxe_get_product_tags_html( $product ) {
    if ( ! $product || ! $product->get_tag_ids() ) {
        return '';
    }
    
    return wc_get_product_tag_list( $product->get_id(), ', ', '<div class="product-tags">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'aqualuxe' ) . ' ', '</div>' );
}

/**
 * Get product stock status HTML
 *
 * @param object $product Product object.
 * @return string
 */
function aqualuxe_get_product_stock_status_html( $product ) {
    if ( ! $product ) {
        return '';
    }
    
    $stock_status = $product->get_stock_status();
    $stock_quantity = $product->get_stock_quantity();
    
    $html = '<div class="product-stock-status stock-status-' . esc_attr( $stock_status ) . '">';
    
    if ( $stock_status === 'instock' ) {
        if ( $stock_quantity ) {
            $html .= sprintf( esc_html__( 'In Stock (%s available)', 'aqualuxe' ), $stock_quantity );
        } else {
            $html .= esc_html__( 'In Stock', 'aqualuxe' );
        }
    } elseif ( $stock_status === 'outofstock' ) {
        $html .= esc_html__( 'Out of Stock', 'aqualuxe' );
    } elseif ( $stock_status === 'onbackorder' ) {
        $html .= esc_html__( 'On Backorder', 'aqualuxe' );
    }
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Get product meta HTML
 *
 * @param object $product Product object.
 * @return string
 */
function aqualuxe_get_product_meta_html( $product ) {
    if ( ! $product ) {
        return '';
    }
    
    $sku = $product->get_sku();
    $stock_status = $product->get_stock_status();
    $stock_quantity = $product->get_stock_quantity();
    
    $html = '<div class="product-meta">';
    
    if ( $sku ) {
        $html .= '<div class="product-sku">';
        $html .= '<span class="label">' . esc_html__( 'SKU:', 'aqualuxe' ) . '</span>';
        $html .= '<span class="value">' . esc_html( $sku ) . '</span>';
        $html .= '</div>';
    }
    
    $html .= '<div class="product-stock">';
    $html .= '<span class="label">' . esc_html__( 'Availability:', 'aqualuxe' ) . '</span>';
    $html .= '<span class="value stock-status-' . esc_attr( $stock_status ) . '">';
    
    if ( $stock_status === 'instock' ) {
        if ( $stock_quantity ) {
            $html .= sprintf( esc_html__( 'In Stock (%s available)', 'aqualuxe' ), $stock_quantity );
        } else {
            $html .= esc_html__( 'In Stock', 'aqualuxe' );
        }
    } elseif ( $stock_status === 'outofstock' ) {
        $html .= esc_html__( 'Out of Stock', 'aqualuxe' );
    } elseif ( $stock_status === 'onbackorder' ) {
        $html .= esc_html__( 'On Backorder', 'aqualuxe' );
    }
    
    $html .= '</span>';
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Get product sharing HTML
 *
 * @param object $product Product object.
 * @return string
 */
function aqualuxe_get_product_sharing_html( $product ) {
    if ( ! $product ) {
        return '';
    }
    
    // Get product URL
    $product_url = get_permalink( $product->get_id() );
    
    // Get product title
    $product_title = $product->get_name();
    
    $html = '<div class="product-sharing">';
    $html .= '<h4>' . esc_html__( 'Share:', 'aqualuxe' ) . '</h4>';
    $html .= '<ul class="social-links">';
    
    // Facebook
    $html .= '<li>';
    $html .= '<a href="https://www.facebook.com/sharer/sharer.php?u=' . esc_url( $product_url ) . '" target="_blank" rel="noopener noreferrer">';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>';
    $html .= '<span class="screen-reader-text">' . esc_html__( 'Share on Facebook', 'aqualuxe' ) . '</span>';
    $html .= '</a>';
    $html .= '</li>';
    
    // Twitter
    $html .= '<li>';
    $html .= '<a href="https://twitter.com/intent/tweet?text=' . esc_attr( $product_title ) . '&url=' . esc_url( $product_url ) . '" target="_blank" rel="noopener noreferrer">';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>';
    $html .= '<span class="screen-reader-text">' . esc_html__( 'Share on Twitter', 'aqualuxe' ) . '</span>';
    $html .= '</a>';
    $html .= '</li>';
    
    // Pinterest
    $html .= '<li>';
    $html .= '<a href="https://pinterest.com/pin/create/button/?url=' . esc_url( $product_url ) . '&media=' . esc_url( wp_get_attachment_url( $product->get_image_id() ) ) . '&description=' . esc_attr( $product_title ) . '" target="_blank" rel="noopener noreferrer">';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 12a4 4 0 1 0 8 0 4 4 0 0 0-8 0z"></path><path d="M12 2v2"></path><path d="M12 20v2"></path><path d="M4.93 4.93l1.41 1.41"></path><path d="M17.66 17.66l1.41 1.41"></path><path d="M2 12h2"></path><path d="M20 12h2"></path><path d="M6.34 17.66l-1.41 1.41"></path><path d="M19.07 4.93l-1.41 1.41"></path></svg>';
    $html .= '<span class="screen-reader-text">' . esc_html__( 'Share on Pinterest', 'aqualuxe' ) . '</span>';
    $html .= '</a>';
    $html .= '</li>';
    
    // Email
    $html .= '<li>';
    $html .= '<a href="mailto:?subject=' . esc_attr( $product_title ) . '&body=' . esc_url( $product_url ) . '">';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>';
    $html .= '<span class="screen-reader-text">' . esc_html__( 'Share via Email', 'aqualuxe' ) . '</span>';
    $html .= '</a>';
    $html .= '</li>';
    
    $html .= '</ul>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Get product tabs HTML
 *
 * @param object $product Product object.
 * @return string
 */
function aqualuxe_get_product_tabs_html( $product ) {
    if ( ! $product ) {
        return '';
    }
    
    ob_start();
    woocommerce_output_product_data_tabs();
    return ob_get_clean();
}

/**
 * Get product navigation HTML
 *
 * @param object $product Product object.
 * @return string
 */
function aqualuxe_get_product_navigation_html( $product ) {
    if ( ! $product ) {
        return '';
    }
    
    // Get previous and next product
    $previous = get_previous_post( true, '', 'product_cat' );
    $next = get_next_post( true, '', 'product_cat' );
    
    $html = '<div class="product-navigation">';
    
    if ( $previous ) {
        $html .= '<a href="' . esc_url( get_permalink( $previous->ID ) ) . '" class="previous-product">';
        $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>';
        $html .= '<span class="screen-reader-text">' . esc_html__( 'Previous Product', 'aqualuxe' ) . '</span>';
        $html .= '</a>';
    }
    
    $html .= '<a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" class="back-to-shop">';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>';
    $html .= '<span class="screen-reader-text">' . esc_html__( 'Back to Shop', 'aqualuxe' ) . '</span>';
    $html .= '</a>';
    
    if ( $next ) {
        $html .= '<a href="' . esc_url( get_permalink( $next->ID ) ) . '" class="next-product">';
        $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>';
        $html .= '<span class="screen-reader-text">' . esc_html__( 'Next Product', 'aqualuxe' ) . '</span>';
        $html .= '</a>';
    }
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Get product breadcrumbs HTML
 *
 * @return string
 */
function aqualuxe_get_product_breadcrumbs_html() {
    ob_start();
    woocommerce_breadcrumb();
    return ob_get_clean();
}

/**
 * Get shop header HTML
 *
 * @return string
 */
function aqualuxe_get_shop_header_html() {
    // Get shop title
    $shop_title = get_theme_mod( 'aqualuxe_shop_title', '' );
    
    if ( empty( $shop_title ) ) {
        if ( is_shop() ) {
            $shop_title = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : esc_html__( 'Shop', 'aqualuxe' );
        } elseif ( is_product_category() ) {
            $shop_title = single_term_title( '', false );
        } elseif ( is_product_tag() ) {
            $shop_title = single_term_title( '', false );
        } elseif ( is_search() ) {
            $shop_title = sprintf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), get_search_query() );
        }
    }
    
    // Get shop description
    $shop_description = get_theme_mod( 'aqualuxe_shop_description', '' );
    
    if ( empty( $shop_description ) ) {
        if ( is_product_category() ) {
            $shop_description = term_description();
        } elseif ( is_product_tag() ) {
            $shop_description = term_description();
        }
    }
    
    $html = '<div class="shop-header">';
    $html .= '<h1 class="shop-title">' . esc_html( $shop_title ) . '</h1>';
    
    if ( ! empty( $shop_description ) ) {
        $html .= '<div class="shop-description">' . wp_kses_post( $shop_description ) . '</div>';
    }
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Get shop filters HTML
 *
 * @return string
 */
function aqualuxe_get_shop_filters_html() {
    ob_start();
    
    echo '<div class="shop-filters">';
    the_widget( 'WC_Widget_Price_Filter', [ 'title' => esc_html__( 'Filter by Price', 'aqualuxe' ) ] );
    the_widget( 'WC_Widget_Product_Categories', [ 'title' => esc_html__( 'Filter by Category', 'aqualuxe' ) ] );
    the_widget( 'WC_Widget_Rating_Filter', [ 'title' => esc_html__( 'Filter by Rating', 'aqualuxe' ) ] );
    echo '</div>';
    
    return ob_get_clean();
}

/**
 * Get shop sidebar toggle HTML
 *
 * @return string
 */
function aqualuxe_get_shop_sidebar_toggle_html() {
    $html = '<button class="shop-sidebar-toggle">';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>';
    $html .= '<span>' . esc_html__( 'Filters', 'aqualuxe' ) . '</span>';
    $html .= '</button>';
    
    return $html;
}

/**
 * Get shop view switcher HTML
 *
 * @return string
 */
function aqualuxe_get_shop_view_switcher_html() {
    // Get current view
    $current_view = isset( $_COOKIE['aqualuxe_shop_view'] ) ? $_COOKIE['aqualuxe_shop_view'] : get_theme_mod( 'aqualuxe_shop_layout', 'grid' );
    
    $html = '<div class="shop-view-switcher">';
    
    $html .= '<button class="view-grid ' . ( $current_view === 'grid' ? 'active' : '' ) . '" data-view="grid">';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>';
    $html .= '<span class="screen-reader-text">' . esc_html__( 'Grid View', 'aqualuxe' ) . '</span>';
    $html .= '</button>';
    
    $html .= '<button class="view-list ' . ( $current_view === 'list' ? 'active' : '' ) . '" data-view="list">';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>';
    $html .= '<span class="screen-reader-text">' . esc_html__( 'List View', 'aqualuxe' ) . '</span>';
    $html .= '</button>';
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Get shop ordering HTML
 *
 * @return string
 */
function aqualuxe_get_shop_ordering_html() {
    ob_start();
    woocommerce_catalog_ordering();
    return ob_get_clean();
}

/**
 * Get shop result count HTML
 *
 * @return string
 */
function aqualuxe_get_shop_result_count_html() {
    ob_start();
    woocommerce_result_count();
    return ob_get_clean();
}

/**
 * Get shop pagination HTML
 *
 * @return string
 */
function aqualuxe_get_shop_pagination_html() {
    ob_start();
    woocommerce_pagination();
    return ob_get_clean();
}

/**
 * Get empty cart message HTML
 *
 * @return string
 */
function aqualuxe_get_empty_cart_message_html() {
    $html = '<div class="empty-cart-message">';
    $html .= '<div class="empty-cart-icon">';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>';
    $html .= '</div>';
    $html .= '<h2>' . esc_html__( 'Your Cart is Empty', 'aqualuxe' ) . '</h2>';
    $html .= '<p>' . esc_html__( 'Looks like you haven\'t added anything to your cart yet.', 'aqualuxe' ) . '</p>';
    $html .= '<a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" class="button">' . esc_html__( 'Continue Shopping', 'aqualuxe' ) . '</a>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Get cart cross-sells HTML
 *
 * @return string
 */
function aqualuxe_get_cart_cross_sells_html() {
    ob_start();
    woocommerce_cross_sell_display();
    return ob_get_clean();
}

/**
 * Get checkout coupon form HTML
 *
 * @return string
 */
function aqualuxe_get_checkout_coupon_form_html() {
    ob_start();
    woocommerce_checkout_coupon_form();
    return ob_get_clean();
}

/**
 * Get checkout login form HTML
 *
 * @return string
 */
function aqualuxe_get_checkout_login_form_html() {
    ob_start();
    woocommerce_checkout_login_form();
    return ob_get_clean();
}

/**
 * Get checkout order review HTML
 *
 * @return string
 */
function aqualuxe_get_checkout_order_review_html() {
    ob_start();
    woocommerce_order_review();
    return ob_get_clean();
}

/**
 * Get checkout payment HTML
 *
 * @return string
 */
function aqualuxe_get_checkout_payment_html() {
    ob_start();
    woocommerce_checkout_payment();
    return ob_get_clean();
}

/**
 * Get checkout order received HTML
 *
 * @param int $order_id Order ID.
 * @return string
 */
function aqualuxe_get_checkout_order_received_html( $order_id ) {
    // Get order
    $order = wc_get_order( $order_id );
    
    if ( ! $order ) {
        return '';
    }
    
    $html = '<div class="order-received">';
    $html .= '<div class="order-received-icon">';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>';
    $html .= '</div>';
    $html .= '<h2>' . esc_html__( 'Thank You!', 'aqualuxe' ) . '</h2>';
    $html .= '<p>' . esc_html__( 'Your order has been received.', 'aqualuxe' ) . '</p>';
    $html .= '<ul class="order-details">';
    $html .= '<li class="order-number">';
    $html .= '<span class="label">' . esc_html__( 'Order Number:', 'aqualuxe' ) . '</span>';
    $html .= '<span class="value">' . esc_html( $order->get_order_number() ) . '</span>';
    $html .= '</li>';
    $html .= '<li class="order-date">';
    $html .= '<span class="label">' . esc_html__( 'Date:', 'aqualuxe' ) . '</span>';
    $html .= '<span class="value">' . esc_html( wc_format_datetime( $order->get_date_created() ) ) . '</span>';
    $html .= '</li>';
    $html .= '<li class="order-total">';
    $html .= '<span class="label">' . esc_html__( 'Total:', 'aqualuxe' ) . '</span>';
    $html .= '<span class="value">' . wp_kses_post( $order->get_formatted_order_total() ) . '</span>';
    $html .= '</li>';
    $html .= '<li class="order-payment-method">';
    $html .= '<span class="label">' . esc_html__( 'Payment Method:', 'aqualuxe' ) . '</span>';
    $html .= '<span class="value">' . wp_kses_post( $order->get_payment_method_title() ) . '</span>';
    $html .= '</li>';
    $html .= '</ul>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Get checkout order details HTML
 *
 * @param int $order_id Order ID.
 * @return string
 */
function aqualuxe_get_checkout_order_details_html( $order_id ) {
    ob_start();
    woocommerce_order_details_table( $order_id );
    return ob_get_clean();
}

/**
 * Get checkout order again button HTML
 *
 * @param int $order_id Order ID.
 * @return string
 */
function aqualuxe_get_checkout_order_again_button_html( $order_id ) {
    ob_start();
    woocommerce_order_again_button( $order_id );
    return ob_get_clean();
}

/**
 * Get checkout order downloads HTML
 *
 * @param int $order_id Order ID.
 * @return string
 */
function aqualuxe_get_checkout_order_downloads_html( $order_id ) {
    ob_start();
    woocommerce_order_downloads_table( $order_id );
    return ob_get_clean();
}

/**
 * Get checkout order actions HTML
 *
 * @param int $order_id Order ID.
 * @return string
 */
function aqualuxe_get_checkout_order_actions_html( $order_id ) {
    $html = '<div class="order-actions">';
    $html .= '<a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" class="button">' . esc_html__( 'Continue Shopping', 'aqualuxe' ) . '</a>';
    $html .= '<a href="' . esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ) . '" class="button">' . esc_html__( 'My Account', 'aqualuxe' ) . '</a>';
    $html .= '</div>';
    
    return $html;
}