<?php
/**
 * Active Filters Template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/active-filters.php.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// Get current filter values
$current_filters = array();

// Categories
if ( isset( $_GET['product_cat'] ) && ! empty( $_GET['product_cat'] ) ) {
    $categories = is_array( $_GET['product_cat'] ) ? $_GET['product_cat'] : array( $_GET['product_cat'] );
    
    foreach ( $categories as $category_slug ) {
        $term = get_term_by( 'slug', $category_slug, 'product_cat' );
        
        if ( $term ) {
            $current_filters[] = array(
                'type'  => 'product_cat[]',
                'value' => $category_slug,
                'label' => $term->name,
            );
        }
    }
}

// Tags
if ( isset( $_GET['product_tag'] ) && ! empty( $_GET['product_tag'] ) ) {
    $tags = is_array( $_GET['product_tag'] ) ? $_GET['product_tag'] : array( $_GET['product_tag'] );
    
    foreach ( $tags as $tag_slug ) {
        $term = get_term_by( 'slug', $tag_slug, 'product_tag' );
        
        if ( $term ) {
            $current_filters[] = array(
                'type'  => 'product_tag[]',
                'value' => $tag_slug,
                'label' => $term->name,
            );
        }
    }
}

// Attributes
$attribute_taxonomies = wc_get_attribute_taxonomies();

if ( $attribute_taxonomies ) {
    foreach ( $attribute_taxonomies as $tax ) {
        $taxonomy = wc_attribute_taxonomy_name( $tax->attribute_name );
        $filter_name = 'filter_' . $tax->attribute_name;
        
        if ( isset( $_GET[ $filter_name ] ) && ! empty( $_GET[ $filter_name ] ) ) {
            $attr_terms = explode( ',', $_GET[ $filter_name ] );
            
            foreach ( $attr_terms as $attr_term ) {
                $term = get_term_by( 'slug', $attr_term, $taxonomy );
                
                if ( $term ) {
                    $current_filters[] = array(
                        'type'  => $filter_name . '[]',
                        'value' => $attr_term,
                        'label' => $term->name,
                    );
                }
            }
        }
    }
}

// Price range
if ( isset( $_GET['min_price'] ) && isset( $_GET['max_price'] ) ) {
    $min_price = floatval( $_GET['min_price'] );
    $max_price = floatval( $_GET['max_price'] );
    
    // Get min and max prices from WooCommerce
    $min_price_default = isset( $GLOBALS['woocommerce_price_filter_min_price'] ) ? $GLOBALS['woocommerce_price_filter_min_price'] : 0;
    $max_price_default = isset( $GLOBALS['woocommerce_price_filter_max_price'] ) ? $GLOBALS['woocommerce_price_filter_max_price'] : PHP_INT_MAX;
    
    if ( $min_price !== $min_price_default || $max_price !== $max_price_default ) {
        $current_filters[] = array(
            'type'  => 'price',
            'value' => '',
            'label' => sprintf(
                /* translators: %1$s: minimum price, %2$s: maximum price */
                __( 'Price: %1$s - %2$s', 'aqualuxe' ),
                wc_price( $min_price ),
                wc_price( $max_price )
            ),
        );
    }
}

// Stock status
if ( isset( $_GET['stock_status'] ) && $_GET['stock_status'] === 'instock' ) {
    $current_filters[] = array(
        'type'  => 'stock_status',
        'value' => 'instock',
        'label' => __( 'In Stock', 'aqualuxe' ),
    );
}

// On sale
if ( isset( $_GET['on_sale'] ) && $_GET['on_sale'] === '1' ) {
    $current_filters[] = array(
        'type'  => 'on_sale',
        'value' => '1',
        'label' => __( 'On Sale', 'aqualuxe' ),
    );
}

// Featured
if ( isset( $_GET['featured'] ) && $_GET['featured'] === '1' ) {
    $current_filters[] = array(
        'type'  => 'featured',
        'value' => '1',
        'label' => __( 'Featured', 'aqualuxe' ),
    );
}

// Rating filter
if ( isset( $_GET['rating_filter'] ) && ! empty( $_GET['rating_filter'] ) ) {
    $ratings = is_array( $_GET['rating_filter'] ) ? $_GET['rating_filter'] : explode( ',', $_GET['rating_filter'] );
    
    foreach ( $ratings as $rating ) {
        $current_filters[] = array(
            'type'  => 'rating_filter[]',
            'value' => $rating,
            'label' => sprintf(
                /* translators: %s: rating */
                __( 'Rated %s and up', 'aqualuxe' ),
                $rating
            ),
        );
    }
}

// Orderby
if ( isset( $_GET['orderby'] ) && $_GET['orderby'] !== 'menu_order' ) {
    $orderby = $_GET['orderby'];
    $orderby_options = array(
        'popularity' => __( 'Popularity', 'aqualuxe' ),
        'rating'     => __( 'Rating', 'aqualuxe' ),
        'date'       => __( 'Newest', 'aqualuxe' ),
        'price'      => __( 'Price: Low to High', 'aqualuxe' ),
        'price-desc' => __( 'Price: High to Low', 'aqualuxe' ),
    );
    
    if ( isset( $orderby_options[ $orderby ] ) ) {
        $current_filters[] = array(
            'type'  => 'orderby',
            'value' => $orderby,
            'label' => sprintf(
                /* translators: %s: orderby option */
                __( 'Sort by: %s', 'aqualuxe' ),
                $orderby_options[ $orderby ]
            ),
        );
    }
}

// Display active filters
if ( ! empty( $current_filters ) ) :
?>
<div class="active-filters bg-white dark:bg-dark-800 rounded-lg shadow-sm p-4 mb-6">
    <div class="active-filters-inner flex flex-wrap items-center gap-2">
        <span class="active-filters-title text-dark-700 dark:text-dark-300 mr-2">
            <?php esc_html_e( 'Active Filters:', 'aqualuxe' ); ?>
        </span>
        
        <?php foreach ( $current_filters as $filter ) : ?>
            <span class="active-filter-item inline-flex items-center bg-gray-100 dark:bg-dark-700 text-dark-700 dark:text-dark-300 rounded-full px-3 py-1 text-sm">
                <span class="filter-label"><?php echo esc_html( $filter['label'] ); ?></span>
                <a href="#" class="remove ml-2 text-dark-500 dark:text-dark-400 hover:text-dark-700 dark:hover:text-dark-200" data-filter-type="<?php echo esc_attr( $filter['type'] ); ?>" data-filter-value="<?php echo esc_attr( $filter['value'] ); ?>" aria-label="<?php esc_attr_e( 'Remove filter', 'aqualuxe' ); ?>">
                    &times;
                </a>
            </span>
        <?php endforeach; ?>
        
        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="reset-filters ml-auto text-primary-600 dark:text-primary-400 hover:underline text-sm">
            <?php esc_html_e( 'Reset All', 'aqualuxe' ); ?>
        </a>
    </div>
</div>
<?php endif; ?>