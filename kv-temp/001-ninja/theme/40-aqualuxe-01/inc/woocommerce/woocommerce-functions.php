<?php
/**
 * WooCommerce specific functions
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Get WooCommerce version number
 *
 * @return string WooCommerce version number
 */
function aqualuxe_get_wc_version() {
    return defined( 'WC_VERSION' ) && WC_VERSION ? WC_VERSION : null;
}

/**
 * Check if WooCommerce version is greater than or equal to specified version
 *
 * @param string $version Version to check against
 * @return bool True if WooCommerce version is greater than or equal to specified version
 */
function aqualuxe_wc_version_gte( $version ) {
    $wc_version = aqualuxe_get_wc_version();
    return $wc_version && version_compare( $wc_version, $version, '>=' );
}

/**
 * Check if current page is a WooCommerce page
 *
 * @return bool True if current page is a WooCommerce page
 */
function aqualuxe_is_wc_page() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }

    return (
        is_woocommerce() ||
        is_shop() ||
        is_product_category() ||
        is_product_tag() ||
        is_product() ||
        is_cart() ||
        is_checkout() ||
        is_account_page()
    );
}

/**
 * Check if current page is a WooCommerce shop page
 *
 * @return bool True if current page is a WooCommerce shop page
 */
function aqualuxe_is_shop_page() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }

    return is_shop();
}

/**
 * Check if current page is a WooCommerce product page
 *
 * @return bool True if current page is a WooCommerce product page
 */
function aqualuxe_is_product_page() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }

    return is_product();
}

/**
 * Check if current page is a WooCommerce cart page
 *
 * @return bool True if current page is a WooCommerce cart page
 */
function aqualuxe_is_cart_page() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }

    return is_cart();
}

/**
 * Check if current page is a WooCommerce checkout page
 *
 * @return bool True if current page is a WooCommerce checkout page
 */
function aqualuxe_is_checkout_page() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }

    return is_checkout();
}

/**
 * Check if current page is a WooCommerce account page
 *
 * @return bool True if current page is a WooCommerce account page
 */
function aqualuxe_is_account_page() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }

    return is_account_page();
}

/**
 * Check if current page is a WooCommerce endpoint
 *
 * @param string $endpoint Endpoint to check
 * @return bool True if current page is a WooCommerce endpoint
 */
function aqualuxe_is_wc_endpoint( $endpoint ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }

    return is_wc_endpoint_url( $endpoint );
}

/**
 * Get product price HTML
 *
 * @param int|WC_Product $product Product ID or product object
 * @return string Product price HTML
 */
function aqualuxe_get_product_price( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return '';
    }

    return $product->get_price_html();
}

/**
 * Get product rating HTML
 *
 * @param int|WC_Product $product Product ID or product object
 * @return string Product rating HTML
 */
function aqualuxe_get_product_rating( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return '';
    }

    return wc_get_rating_html( $product->get_average_rating() );
}

/**
 * Get product categories
 *
 * @param int|WC_Product $product Product ID or product object
 * @return string Product categories HTML
 */
function aqualuxe_get_product_categories( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return '';
    }

    return wc_get_product_category_list( $product->get_id(), ', ', '<span class="product-categories">', '</span>' );
}

/**
 * Get product tags
 *
 * @param int|WC_Product $product Product ID or product object
 * @return string Product tags HTML
 */
function aqualuxe_get_product_tags( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return '';
    }

    return wc_get_product_tag_list( $product->get_id(), ', ', '<span class="product-tags">', '</span>' );
}

/**
 * Get product thumbnail
 *
 * @param int|WC_Product $product Product ID or product object
 * @param string $size Image size
 * @return string Product thumbnail HTML
 */
function aqualuxe_get_product_thumbnail( $product, $size = 'woocommerce_thumbnail' ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return '';
    }

    return $product->get_image( $size );
}

/**
 * Get product gallery images
 *
 * @param int|WC_Product $product Product ID or product object
 * @param string $size Image size
 * @return array Product gallery images
 */
function aqualuxe_get_product_gallery_images( $product, $size = 'woocommerce_single' ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return [];
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return [];
    }

    $attachment_ids = $product->get_gallery_image_ids();
    $images = [];

    foreach ( $attachment_ids as $attachment_id ) {
        $images[] = wp_get_attachment_image( $attachment_id, $size );
    }

    return $images;
}

/**
 * Get product short description
 *
 * @param int|WC_Product $product Product ID or product object
 * @return string Product short description
 */
function aqualuxe_get_product_short_description( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return '';
    }

    return $product->get_short_description();
}

/**
 * Get product description
 *
 * @param int|WC_Product $product Product ID or product object
 * @return string Product description
 */
function aqualuxe_get_product_description( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return '';
    }

    return $product->get_description();
}

/**
 * Get product attributes
 *
 * @param int|WC_Product $product Product ID or product object
 * @return array Product attributes
 */
function aqualuxe_get_product_attributes( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return [];
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return [];
    }

    return $product->get_attributes();
}

/**
 * Get product SKU
 *
 * @param int|WC_Product $product Product ID or product object
 * @return string Product SKU
 */
function aqualuxe_get_product_sku( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return '';
    }

    return $product->get_sku();
}

/**
 * Get product stock status
 *
 * @param int|WC_Product $product Product ID or product object
 * @return string Product stock status
 */
function aqualuxe_get_product_stock_status( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return '';
    }

    return $product->get_stock_status();
}

/**
 * Get product stock quantity
 *
 * @param int|WC_Product $product Product ID or product object
 * @return int|null Product stock quantity
 */
function aqualuxe_get_product_stock_quantity( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return null;
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return null;
    }

    return $product->get_stock_quantity();
}

/**
 * Get product type
 *
 * @param int|WC_Product $product Product ID or product object
 * @return string Product type
 */
function aqualuxe_get_product_type( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return '';
    }

    return $product->get_type();
}

/**
 * Check if product is in stock
 *
 * @param int|WC_Product $product Product ID or product object
 * @return bool True if product is in stock
 */
function aqualuxe_is_product_in_stock( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return false;
    }

    return $product->is_in_stock();
}

/**
 * Check if product is on sale
 *
 * @param int|WC_Product $product Product ID or product object
 * @return bool True if product is on sale
 */
function aqualuxe_is_product_on_sale( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return false;
    }

    return $product->is_on_sale();
}

/**
 * Check if product is featured
 *
 * @param int|WC_Product $product Product ID or product object
 * @return bool True if product is featured
 */
function aqualuxe_is_product_featured( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return false;
    }

    return $product->is_featured();
}

/**
 * Check if product is visible
 *
 * @param int|WC_Product $product Product ID or product object
 * @return bool True if product is visible
 */
function aqualuxe_is_product_visible( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return false;
    }

    return $product->is_visible();
}

/**
 * Check if product is purchasable
 *
 * @param int|WC_Product $product Product ID or product object
 * @return bool True if product is purchasable
 */
function aqualuxe_is_product_purchasable( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return false;
    }

    return $product->is_purchasable();
}

/**
 * Get product add to cart URL
 *
 * @param int|WC_Product $product Product ID or product object
 * @return string Product add to cart URL
 */
function aqualuxe_get_product_add_to_cart_url( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return '';
    }

    return $product->add_to_cart_url();
}

/**
 * Get product add to cart text
 *
 * @param int|WC_Product $product Product ID or product object
 * @return string Product add to cart text
 */
function aqualuxe_get_product_add_to_cart_text( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return '';
    }

    return $product->add_to_cart_text();
}

/**
 * Get product permalink
 *
 * @param int|WC_Product $product Product ID or product object
 * @return string Product permalink
 */
function aqualuxe_get_product_permalink( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return '';
    }

    return $product->get_permalink();
}

/**
 * Get product reviews URL
 *
 * @param int|WC_Product $product Product ID or product object
 * @return string Product reviews URL
 */
function aqualuxe_get_product_reviews_url( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return '';
    }

    return get_permalink( $product->get_id() ) . '#reviews';
}

/**
 * Get product dimensions
 *
 * @param int|WC_Product $product Product ID or product object
 * @return string Product dimensions
 */
function aqualuxe_get_product_dimensions( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return '';
    }

    return wc_format_dimensions( $product->get_dimensions( false ) );
}

/**
 * Get product weight
 *
 * @param int|WC_Product $product Product ID or product object
 * @return string Product weight
 */
function aqualuxe_get_product_weight( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return '';
    }

    return wc_format_weight( $product->get_weight() );
}

/**
 * Get product shipping class
 *
 * @param int|WC_Product $product Product ID or product object
 * @return string Product shipping class
 */
function aqualuxe_get_product_shipping_class( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return '';
    }

    return $product->get_shipping_class();
}

/**
 * Get product reviews count
 *
 * @param int|WC_Product $product Product ID or product object
 * @return int Product reviews count
 */
function aqualuxe_get_product_review_count( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return 0;
    }

    return $product->get_review_count();
}

/**
 * Get product rating count
 *
 * @param int|WC_Product $product Product ID or product object
 * @return int Product rating count
 */
function aqualuxe_get_product_rating_count( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return 0;
    }

    return $product->get_rating_count();
}

/**
 * Get product average rating
 *
 * @param int|WC_Product $product Product ID or product object
 * @return float Product average rating
 */
function aqualuxe_get_product_average_rating( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return 0;
    }

    return $product->get_average_rating();
}

/**
 * Get product related products
 *
 * @param int|WC_Product $product Product ID or product object
 * @param int $limit Number of related products to get
 * @return array Related products
 */
function aqualuxe_get_product_related_products( $product, $limit = 4 ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return [];
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return [];
    }

    $related_products = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), $limit ) ), 'wc_products_array_filter_visible' );

    return $related_products;
}

/**
 * Get product upsells
 *
 * @param int|WC_Product $product Product ID or product object
 * @return array Upsell products
 */
function aqualuxe_get_product_upsells( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return [];
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return [];
    }

    $upsell_ids = $product->get_upsell_ids();
    $upsells = array_filter( array_map( 'wc_get_product', $upsell_ids ), 'wc_products_array_filter_visible' );

    return $upsells;
}

/**
 * Get product cross-sells
 *
 * @param int|WC_Product $product Product ID or product object
 * @return array Cross-sell products
 */
function aqualuxe_get_product_cross_sells( $product ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return [];
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return [];
    }

    $cross_sell_ids = $product->get_cross_sell_ids();
    $cross_sells = array_filter( array_map( 'wc_get_product', $cross_sell_ids ), 'wc_products_array_filter_visible' );

    return $cross_sells;
}

/**
 * Get featured products
 *
 * @param int $limit Number of products to get
 * @return array Featured products
 */
function aqualuxe_get_featured_products( $limit = 4 ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return [];
    }

    $args = array(
        'featured' => true,
        'limit' => $limit,
        'orderby' => 'date',
        'order' => 'DESC',
    );

    return wc_get_products( $args );
}

/**
 * Get best selling products
 *
 * @param int $limit Number of products to get
 * @return array Best selling products
 */
function aqualuxe_get_best_selling_products( $limit = 4 ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return [];
    }

    $args = array(
        'limit' => $limit,
        'orderby' => 'popularity',
        'order' => 'DESC',
    );

    return wc_get_products( $args );
}

/**
 * Get top rated products
 *
 * @param int $limit Number of products to get
 * @return array Top rated products
 */
function aqualuxe_get_top_rated_products( $limit = 4 ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return [];
    }

    $args = array(
        'limit' => $limit,
        'orderby' => 'rating',
        'order' => 'DESC',
    );

    return wc_get_products( $args );
}

/**
 * Get sale products
 *
 * @param int $limit Number of products to get
 * @return array Sale products
 */
function aqualuxe_get_sale_products( $limit = 4 ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return [];
    }

    $args = array(
        'limit' => $limit,
        'orderby' => 'date',
        'order' => 'DESC',
        'on_sale' => true,
    );

    return wc_get_products( $args );
}

/**
 * Get recent products
 *
 * @param int $limit Number of products to get
 * @return array Recent products
 */
function aqualuxe_get_recent_products( $limit = 4 ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return [];
    }

    $args = array(
        'limit' => $limit,
        'orderby' => 'date',
        'order' => 'DESC',
    );

    return wc_get_products( $args );
}

/**
 * Get products by category
 *
 * @param string $category Category slug
 * @param int $limit Number of products to get
 * @return array Products
 */
function aqualuxe_get_products_by_category( $category, $limit = 4 ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return [];
    }

    $args = array(
        'limit' => $limit,
        'orderby' => 'date',
        'order' => 'DESC',
        'category' => array( $category ),
    );

    return wc_get_products( $args );
}

/**
 * Get products by tag
 *
 * @param string $tag Tag slug
 * @param int $limit Number of products to get
 * @return array Products
 */
function aqualuxe_get_products_by_tag( $tag, $limit = 4 ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return [];
    }

    $args = array(
        'limit' => $limit,
        'orderby' => 'date',
        'order' => 'DESC',
        'tag' => array( $tag ),
    );

    return wc_get_products( $args );
}

/**
 * Get products by attribute
 *
 * @param string $attribute Attribute slug
 * @param string $value Attribute value
 * @param int $limit Number of products to get
 * @return array Products
 */
function aqualuxe_get_products_by_attribute( $attribute, $value, $limit = 4 ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return [];
    }

    $args = array(
        'limit' => $limit,
        'orderby' => 'date',
        'order' => 'DESC',
        'attribute' => $attribute,
        'attribute_term' => $value,
    );

    return wc_get_products( $args );
}

/**
 * Get product categories
 *
 * @param array $args Query arguments
 * @return array Product categories
 */
function aqualuxe_get_product_categories( $args = [] ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return [];
    }

    $defaults = array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'orderby' => 'name',
        'order' => 'ASC',
    );

    $args = wp_parse_args( $args, $defaults );

    return get_terms( $args );
}

/**
 * Get product tags
 *
 * @param array $args Query arguments
 * @return array Product tags
 */
function aqualuxe_get_product_tags( $args = [] ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return [];
    }

    $defaults = array(
        'taxonomy' => 'product_tag',
        'hide_empty' => true,
        'orderby' => 'name',
        'order' => 'ASC',
    );

    $args = wp_parse_args( $args, $defaults );

    return get_terms( $args );
}

/**
 * Get product attributes
 *
 * @return array Product attributes
 */
function aqualuxe_get_product_attributes_list() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return [];
    }

    $attributes = wc_get_attribute_taxonomies();
    $attribute_list = [];

    foreach ( $attributes as $attribute ) {
        $attribute_list[ $attribute->attribute_name ] = $attribute->attribute_label;
    }

    return $attribute_list;
}

/**
 * Get product attribute terms
 *
 * @param string $attribute Attribute slug
 * @param array $args Query arguments
 * @return array Product attribute terms
 */
function aqualuxe_get_product_attribute_terms( $attribute, $args = [] ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return [];
    }

    $defaults = array(
        'taxonomy' => 'pa_' . $attribute,
        'hide_empty' => true,
        'orderby' => 'name',
        'order' => 'ASC',
    );

    $args = wp_parse_args( $args, $defaults );

    return get_terms( $args );
}

/**
 * Get cart URL
 *
 * @return string Cart URL
 */
function aqualuxe_get_cart_url() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    return wc_get_cart_url();
}

/**
 * Get checkout URL
 *
 * @return string Checkout URL
 */
function aqualuxe_get_checkout_url() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    return wc_get_checkout_url();
}

/**
 * Get account URL
 *
 * @return string Account URL
 */
function aqualuxe_get_account_url() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    return wc_get_account_endpoint_url( 'dashboard' );
}

/**
 * Get shop URL
 *
 * @return string Shop URL
 */
function aqualuxe_get_shop_url() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    return get_permalink( wc_get_page_id( 'shop' ) );
}

/**
 * Get cart contents count
 *
 * @return int Cart contents count
 */
function aqualuxe_get_cart_contents_count() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }

    return WC()->cart->get_cart_contents_count();
}

/**
 * Get cart subtotal
 *
 * @return string Cart subtotal
 */
function aqualuxe_get_cart_subtotal() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    return WC()->cart->get_cart_subtotal();
}

/**
 * Get cart total
 *
 * @return string Cart total
 */
function aqualuxe_get_cart_total() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    return WC()->cart->get_total();
}

/**
 * Get cart contents
 *
 * @return array Cart contents
 */
function aqualuxe_get_cart_contents() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return [];
    }

    return WC()->cart->get_cart();
}

/**
 * Check if cart is empty
 *
 * @return bool True if cart is empty
 */
function aqualuxe_is_cart_empty() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return true;
    }

    return WC()->cart->is_empty();
}

/**
 * Get currency symbol
 *
 * @return string Currency symbol
 */
function aqualuxe_get_currency_symbol() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    return get_woocommerce_currency_symbol();
}

/**
 * Get currency code
 *
 * @return string Currency code
 */
function aqualuxe_get_currency_code() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    return get_woocommerce_currency();
}

/**
 * Format price
 *
 * @param float $price Price to format
 * @return string Formatted price
 */
function aqualuxe_format_price( $price ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    return wc_price( $price );
}

/**
 * Format sale price
 *
 * @param float $regular_price Regular price
 * @param float $sale_price Sale price
 * @return string Formatted sale price
 */
function aqualuxe_format_sale_price( $regular_price, $sale_price ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }

    return wc_format_sale_price( $regular_price, $sale_price );
}

/**
 * Get product fallback content
 *
 * @return string Product fallback content
 */
function aqualuxe_get_product_fallback_content() {
    ob_start();
    ?>
    <div class="woocommerce-inactive-notice">
        <p><?php esc_html_e( 'WooCommerce is not active. Please install and activate WooCommerce to enable shop functionality.', 'aqualuxe' ); ?></p>
        <p><a href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>" class="button"><?php esc_html_e( 'Go to Plugins', 'aqualuxe' ); ?></a></p>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Get shop fallback content
 *
 * @return string Shop fallback content
 */
function aqualuxe_get_shop_fallback_content() {
    ob_start();
    ?>
    <div class="woocommerce-inactive-notice">
        <h2><?php esc_html_e( 'Shop Coming Soon', 'aqualuxe' ); ?></h2>
        <p><?php esc_html_e( 'Our shop is currently being set up. Please check back later.', 'aqualuxe' ); ?></p>
        <p><?php esc_html_e( 'In the meantime, you can browse our other content:', 'aqualuxe' ); ?></p>
        <ul>
            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aqualuxe' ); ?></a></li>
            <li><a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"><?php esc_html_e( 'Blog', 'aqualuxe' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About Us', 'aqualuxe' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></a></li>
        </ul>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Get cart fallback content
 *
 * @return string Cart fallback content
 */
function aqualuxe_get_cart_fallback_content() {
    ob_start();
    ?>
    <div class="woocommerce-inactive-notice">
        <h2><?php esc_html_e( 'Shopping Cart Coming Soon', 'aqualuxe' ); ?></h2>
        <p><?php esc_html_e( 'Our shopping cart is currently being set up. Please check back later.', 'aqualuxe' ); ?></p>
        <p><?php esc_html_e( 'In the meantime, you can browse our other content:', 'aqualuxe' ); ?></p>
        <ul>
            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aqualuxe' ); ?></a></li>
            <li><a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"><?php esc_html_e( 'Blog', 'aqualuxe' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About Us', 'aqualuxe' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></a></li>
        </ul>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Get checkout fallback content
 *
 * @return string Checkout fallback content
 */
function aqualuxe_get_checkout_fallback_content() {
    ob_start();
    ?>
    <div class="woocommerce-inactive-notice">
        <h2><?php esc_html_e( 'Checkout Coming Soon', 'aqualuxe' ); ?></h2>
        <p><?php esc_html_e( 'Our checkout is currently being set up. Please check back later.', 'aqualuxe' ); ?></p>
        <p><?php esc_html_e( 'In the meantime, you can browse our other content:', 'aqualuxe' ); ?></p>
        <ul>
            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aqualuxe' ); ?></a></li>
            <li><a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"><?php esc_html_e( 'Blog', 'aqualuxe' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About Us', 'aqualuxe' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></a></li>
        </ul>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Get account fallback content
 *
 * @return string Account fallback content
 */
function aqualuxe_get_account_fallback_content() {
    ob_start();
    ?>
    <div class="woocommerce-inactive-notice">
        <h2><?php esc_html_e( 'Account Coming Soon', 'aqualuxe' ); ?></h2>
        <p><?php esc_html_e( 'Our account system is currently being set up. Please check back later.', 'aqualuxe' ); ?></p>
        <p><?php esc_html_e( 'In the meantime, you can browse our other content:', 'aqualuxe' ); ?></p>
        <ul>
            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aqualuxe' ); ?></a></li>
            <li><a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"><?php esc_html_e( 'Blog', 'aqualuxe' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About Us', 'aqualuxe' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></a></li>
        </ul>
    </div>
    <?php
    return ob_get_clean();
}