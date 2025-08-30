<?php
/**
 * Product Filter Template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/product-filter.php.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// Get all product categories
$product_categories = get_terms( array(
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'parent'     => 0,
) );

// Get all product tags
$product_tags = get_terms( array(
    'taxonomy'   => 'product_tag',
    'hide_empty' => true,
) );

// Get all product attributes
$attribute_taxonomies = wc_get_attribute_taxonomies();

// Get min and max prices
$min_price = isset( $_GET['min_price'] ) ? floatval( $_GET['min_price'] ) : '';
$max_price = isset( $_GET['max_price'] ) ? floatval( $_GET['max_price'] ) : '';

// Get current filter values
$current_cat = isset( $_GET['product_cat'] ) ? sanitize_text_field( $_GET['product_cat'] ) : '';
$current_tag = isset( $_GET['product_tag'] ) ? sanitize_text_field( $_GET['product_tag'] ) : '';
$current_attrs = array();

if ( $attribute_taxonomies ) {
    foreach ( $attribute_taxonomies as $tax ) {
        $taxonomy = wc_attribute_taxonomy_name( $tax->attribute_name );
        $filter_name = 'filter_' . $tax->attribute_name;
        
        if ( isset( $_GET[ $filter_name ] ) ) {
            $current_attrs[ $taxonomy ] = explode( ',', sanitize_text_field( $_GET[ $filter_name ] ) );
        }
    }
}

// Get current stock status
$current_stock = isset( $_GET['stock_status'] ) ? sanitize_text_field( $_GET['stock_status'] ) : '';

// Get current featured status
$current_featured = isset( $_GET['featured'] ) ? sanitize_text_field( $_GET['featured'] ) : '';

// Get current on sale status
$current_on_sale = isset( $_GET['on_sale'] ) ? sanitize_text_field( $_GET['on_sale'] ) : '';

// Get current rating filter
$current_rating = isset( $_GET['rating_filter'] ) ? array_map( 'absint', explode( ',', $_GET['rating_filter'] ) ) : array();
?>

<div class="aqualuxe-filter-wrapper">
    <form class="aqualuxe-filter-form" method="get" action="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
        <?php if ( $product_categories && count( $product_categories ) > 0 ) : ?>
            <div class="filter-section mb-6">
                <h3 class="filter-title text-lg font-medium text-dark-900 dark:text-white mb-3"><?php esc_html_e( 'Categories', 'aqualuxe' ); ?></h3>
                
                <div class="filter-content">
                    <ul class="filter-list space-y-2">
                        <?php foreach ( $product_categories as $category ) : ?>
                            <li class="filter-item">
                                <label class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        name="product_cat[]" 
                                        value="<?php echo esc_attr( $category->slug ); ?>" 
                                        <?php checked( in_array( $category->slug, (array) $current_cat ) ); ?>
                                        class="form-checkbox h-4 w-4 text-primary-600 rounded border-gray-300 dark:border-dark-600 focus:ring-primary-500"
                                    >
                                    <span class="ml-2 text-dark-700 dark:text-dark-300">
                                        <?php echo esc_html( $category->name ); ?>
                                        <span class="text-dark-400 dark:text-dark-500 text-sm">(<?php echo esc_html( $category->count ); ?>)</span>
                                    </span>
                                </label>
                                
                                <?php
                                // Get child categories
                                $child_categories = get_terms( array(
                                    'taxonomy'   => 'product_cat',
                                    'hide_empty' => true,
                                    'parent'     => $category->term_id,
                                ) );
                                
                                if ( $child_categories ) :
                                ?>
                                    <ul class="filter-children pl-6 mt-2 space-y-2">
                                        <?php foreach ( $child_categories as $child ) : ?>
                                            <li class="filter-item">
                                                <label class="flex items-center">
                                                    <input 
                                                        type="checkbox" 
                                                        name="product_cat[]" 
                                                        value="<?php echo esc_attr( $child->slug ); ?>" 
                                                        <?php checked( in_array( $child->slug, (array) $current_cat ) ); ?>
                                                        class="form-checkbox h-4 w-4 text-primary-600 rounded border-gray-300 dark:border-dark-600 focus:ring-primary-500"
                                                    >
                                                    <span class="ml-2 text-dark-700 dark:text-dark-300">
                                                        <?php echo esc_html( $child->name ); ?>
                                                        <span class="text-dark-400 dark:text-dark-500 text-sm">(<?php echo esc_html( $child->count ); ?>)</span>
                                                    </span>
                                                </label>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ( $attribute_taxonomies ) : ?>
            <?php foreach ( $attribute_taxonomies as $tax ) : ?>
                <?php
                $taxonomy = wc_attribute_taxonomy_name( $tax->attribute_name );
                $filter_name = 'filter_' . $tax->attribute_name;
                
                $terms = get_terms( array(
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => true,
                ) );
                
                if ( ! $terms ) {
                    continue;
                }
                ?>
                <div class="filter-section mb-6">
                    <h3 class="filter-title text-lg font-medium text-dark-900 dark:text-white mb-3"><?php echo esc_html( $tax->attribute_label ); ?></h3>
                    
                    <div class="filter-content">
                        <ul class="filter-list space-y-2">
                            <?php foreach ( $terms as $term ) : ?>
                                <li class="filter-item">
                                    <label class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            name="<?php echo esc_attr( $filter_name ); ?>[]" 
                                            value="<?php echo esc_attr( $term->slug ); ?>" 
                                            <?php checked( isset( $current_attrs[ $taxonomy ] ) && in_array( $term->slug, $current_attrs[ $taxonomy ] ) ); ?>
                                            class="form-checkbox h-4 w-4 text-primary-600 rounded border-gray-300 dark:border-dark-600 focus:ring-primary-500"
                                        >
                                        <span class="ml-2 text-dark-700 dark:text-dark-300">
                                            <?php echo esc_html( $term->name ); ?>
                                            <span class="text-dark-400 dark:text-dark-500 text-sm">(<?php echo esc_html( $term->count ); ?>)</span>
                                        </span>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <?php if ( $product_tags && count( $product_tags ) > 0 ) : ?>
            <div class="filter-section mb-6">
                <h3 class="filter-title text-lg font-medium text-dark-900 dark:text-white mb-3"><?php esc_html_e( 'Tags', 'aqualuxe' ); ?></h3>
                
                <div class="filter-content">
                    <div class="filter-tags flex flex-wrap gap-2">
                        <?php foreach ( $product_tags as $tag ) : ?>
                            <label class="filter-tag-item inline-flex items-center px-3 py-1 rounded-full bg-gray-100 dark:bg-dark-700 text-dark-700 dark:text-dark-300 text-sm cursor-pointer hover:bg-gray-200 dark:hover:bg-dark-600 transition-colors <?php echo in_array( $tag->slug, (array) $current_tag ) ? 'bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200' : ''; ?>">
                                <input 
                                    type="checkbox" 
                                    name="product_tag[]" 
                                    value="<?php echo esc_attr( $tag->slug ); ?>" 
                                    <?php checked( in_array( $tag->slug, (array) $current_tag ) ); ?>
                                    class="sr-only"
                                >
                                <?php echo esc_html( $tag->name ); ?>
                                <span class="text-dark-400 dark:text-dark-500 ml-1">(<?php echo esc_html( $tag->count ); ?>)</span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="filter-section mb-6">
            <h3 class="filter-title text-lg font-medium text-dark-900 dark:text-white mb-3"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></h3>
            
            <div class="filter-content">
                <?php wc_get_template( 'content-widget-price-filter.php' ); ?>
            </div>
        </div>
        
        <div class="filter-section mb-6">
            <h3 class="filter-title text-lg font-medium text-dark-900 dark:text-white mb-3"><?php esc_html_e( 'Product Status', 'aqualuxe' ); ?></h3>
            
            <div class="filter-content">
                <ul class="filter-list space-y-2">
                    <li class="filter-item">
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="stock_status" 
                                value="instock" 
                                <?php checked( $current_stock === 'instock' ); ?>
                                class="form-checkbox h-4 w-4 text-primary-600 rounded border-gray-300 dark:border-dark-600 focus:ring-primary-500"
                            >
                            <span class="ml-2 text-dark-700 dark:text-dark-300">
                                <?php esc_html_e( 'In Stock', 'aqualuxe' ); ?>
                            </span>
                        </label>
                    </li>
                    <li class="filter-item">
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="on_sale" 
                                value="1" 
                                <?php checked( $current_on_sale === '1' ); ?>
                                class="form-checkbox h-4 w-4 text-primary-600 rounded border-gray-300 dark:border-dark-600 focus:ring-primary-500"
                            >
                            <span class="ml-2 text-dark-700 dark:text-dark-300">
                                <?php esc_html_e( 'On Sale', 'aqualuxe' ); ?>
                            </span>
                        </label>
                    </li>
                    <li class="filter-item">
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="featured" 
                                value="1" 
                                <?php checked( $current_featured === '1' ); ?>
                                class="form-checkbox h-4 w-4 text-primary-600 rounded border-gray-300 dark:border-dark-600 focus:ring-primary-500"
                            >
                            <span class="ml-2 text-dark-700 dark:text-dark-300">
                                <?php esc_html_e( 'Featured', 'aqualuxe' ); ?>
                            </span>
                        </label>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="filter-section mb-6">
            <h3 class="filter-title text-lg font-medium text-dark-900 dark:text-white mb-3"><?php esc_html_e( 'Rating', 'aqualuxe' ); ?></h3>
            
            <div class="filter-content">
                <ul class="filter-list space-y-2">
                    <?php for ( $rating = 5; $rating >= 1; $rating-- ) : ?>
                        <li class="filter-item">
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    name="rating_filter[]" 
                                    value="<?php echo esc_attr( $rating ); ?>" 
                                    <?php checked( in_array( $rating, $current_rating ) ); ?>
                                    class="form-checkbox h-4 w-4 text-primary-600 rounded border-gray-300 dark:border-dark-600 focus:ring-primary-500"
                                >
                                <span class="ml-2 text-dark-700 dark:text-dark-300 flex items-center">
                                    <?php
                                    for ( $i = 1; $i <= 5; $i++ ) {
                                        if ( $i <= $rating ) {
                                            echo '<svg class="w-4 h-4 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
                                        } else {
                                            echo '<svg class="w-4 h-4 text-gray-300 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
                                        }
                                    }
                                    ?>
                                    <span class="ml-1"><?php echo esc_html__( 'and up', 'aqualuxe' ); ?></span>
                                </span>
                            </label>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>
        </div>
        
        <div class="filter-actions">
            <button type="submit" class="btn btn-primary w-full">
                <?php esc_html_e( 'Apply Filters', 'aqualuxe' ); ?>
            </button>
            
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-outline w-full mt-3">
                <?php esc_html_e( 'Reset Filters', 'aqualuxe' ); ?>
            </a>
        </div>
    </form>
</div>