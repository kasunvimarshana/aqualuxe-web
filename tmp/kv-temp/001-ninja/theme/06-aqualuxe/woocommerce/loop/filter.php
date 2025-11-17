<?php
/**
 * Product filter template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/filter.php.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get current category.
$current_category = get_queried_object();
$category_id = is_a( $current_category, 'WP_Term' ) ? $current_category->term_id : 0;

// Get all product categories.
$product_categories = get_terms( array(
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'parent'     => 0,
) );

// Get all product tags.
$product_tags = get_terms( array(
    'taxonomy'   => 'product_tag',
    'hide_empty' => true,
) );

// Get min and max prices.
$min_price = isset( $_GET['min_price'] ) ? floatval( $_GET['min_price'] ) : 0;
$max_price = isset( $_GET['max_price'] ) ? floatval( $_GET['max_price'] ) : 0;

if ( $min_price === 0 && $max_price === 0 ) {
    global $wpdb;
    
    $min_price_query = "SELECT MIN(meta_value + 0) FROM {$wpdb->postmeta} WHERE meta_key = '_price'";
    $max_price_query = "SELECT MAX(meta_value + 0) FROM {$wpdb->postmeta} WHERE meta_key = '_price'";
    
    $min_price = $wpdb->get_var( $min_price_query );
    $max_price = $wpdb->get_var( $max_price_query );
    
    if ( $min_price === null ) {
        $min_price = 0;
    }
    
    if ( $max_price === null ) {
        $max_price = 1000;
    }
}

// Get current min and max prices.
$current_min_price = isset( $_GET['min_price'] ) ? floatval( $_GET['min_price'] ) : $min_price;
$current_max_price = isset( $_GET['max_price'] ) ? floatval( $_GET['max_price'] ) : $max_price;

// Get current filter values.
$current_categories = isset( $_GET['product_cat'] ) ? explode( ',', $_GET['product_cat'] ) : array();
$current_tags = isset( $_GET['product_tag'] ) ? explode( ',', $_GET['product_tag'] ) : array();
$current_attributes = array();

// Get product attributes.
$attribute_taxonomies = wc_get_attribute_taxonomies();
?>

<div class="aqualuxe-product-filter">
    <button class="filter-toggle-button">
        <i class="fa fa-filter"></i> <?php esc_html_e( 'Filter Products', 'aqualuxe' ); ?>
    </button>
    
    <?php if ( get_theme_mod( 'aqualuxe_product_view_switcher', true ) ) : ?>
        <div class="aqualuxe-product-view-switcher">
            <button class="grid-view active" data-view="grid">
                <i class="fa fa-th"></i>
            </button>
            <button class="list-view" data-view="list">
                <i class="fa fa-list"></i>
            </button>
        </div>
    <?php endif; ?>
    
    <div class="filter-content">
        <div class="filter-header">
            <h3><?php esc_html_e( 'Filter Products', 'aqualuxe' ); ?></h3>
            <button class="filter-close">&times;</button>
        </div>
        
        <form id="product-filter-form" action="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" method="get">
            <?php if ( ! empty( $product_categories ) ) : ?>
                <div class="filter-section">
                    <h4><?php esc_html_e( 'Categories', 'aqualuxe' ); ?></h4>
                    <ul>
                        <?php foreach ( $product_categories as $category ) : ?>
                            <li>
                                <label>
                                    <input type="checkbox" name="product_cat[]" value="<?php echo esc_attr( $category->slug ); ?>" <?php checked( in_array( $category->slug, $current_categories ) ); ?>>
                                    <?php echo esc_html( $category->name ); ?>
                                    <span class="count">(<?php echo esc_html( $category->count ); ?>)</span>
                                </label>
                                
                                <?php
                                // Get subcategories.
                                $subcategories = get_terms( array(
                                    'taxonomy'   => 'product_cat',
                                    'hide_empty' => true,
                                    'parent'     => $category->term_id,
                                ) );
                                
                                if ( ! empty( $subcategories ) ) :
                                ?>
                                    <ul class="subcategories">
                                        <?php foreach ( $subcategories as $subcategory ) : ?>
                                            <li>
                                                <label>
                                                    <input type="checkbox" name="product_cat[]" value="<?php echo esc_attr( $subcategory->slug ); ?>" <?php checked( in_array( $subcategory->slug, $current_categories ) ); ?>>
                                                    <?php echo esc_html( $subcategory->name ); ?>
                                                    <span class="count">(<?php echo esc_html( $subcategory->count ); ?>)</span>
                                                </label>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if ( ! empty( $product_tags ) ) : ?>
                <div class="filter-section">
                    <h4><?php esc_html_e( 'Tags', 'aqualuxe' ); ?></h4>
                    <ul>
                        <?php foreach ( $product_tags as $tag ) : ?>
                            <li>
                                <label>
                                    <input type="checkbox" name="product_tag[]" value="<?php echo esc_attr( $tag->slug ); ?>" <?php checked( in_array( $tag->slug, $current_tags ) ); ?>>
                                    <?php echo esc_html( $tag->name ); ?>
                                    <span class="count">(<?php echo esc_html( $tag->count ); ?>)</span>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if ( ! empty( $attribute_taxonomies ) ) : ?>
                <?php foreach ( $attribute_taxonomies as $attribute ) : ?>
                    <?php
                    $attribute_name = wc_attribute_taxonomy_name( $attribute->attribute_name );
                    $attribute_terms = get_terms( array(
                        'taxonomy'   => $attribute_name,
                        'hide_empty' => true,
                    ) );
                    
                    if ( ! empty( $attribute_terms ) ) :
                        $current_attribute_values = isset( $_GET[ 'filter_' . $attribute->attribute_name ] ) ? explode( ',', $_GET[ 'filter_' . $attribute->attribute_name ] ) : array();
                    ?>
                        <div class="filter-section">
                            <h4><?php echo esc_html( $attribute->attribute_label ); ?></h4>
                            <ul>
                                <?php foreach ( $attribute_terms as $term ) : ?>
                                    <li>
                                        <label>
                                            <input type="checkbox" name="filter_<?php echo esc_attr( $attribute->attribute_name ); ?>[]" value="<?php echo esc_attr( $term->slug ); ?>" <?php checked( in_array( $term->slug, $current_attribute_values ) ); ?>>
                                            <?php echo esc_html( $term->name ); ?>
                                            <span class="count">(<?php echo esc_html( $term->count ); ?>)</span>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <div class="filter-section">
                <h4><?php esc_html_e( 'Price Range', 'aqualuxe' ); ?></h4>
                <div class="price-slider-wrapper">
                    <div class="price-slider" data-min="<?php echo esc_attr( $min_price ); ?>" data-max="<?php echo esc_attr( $max_price ); ?>" data-min-price="<?php echo esc_attr( $current_min_price ); ?>" data-max-price="<?php echo esc_attr( $current_max_price ); ?>"></div>
                    <div class="price-slider-values">
                        <span class="min-value"><?php echo esc_html( wc_price( $current_min_price ) ); ?></span>
                        <span class="max-value"><?php echo esc_html( wc_price( $current_max_price ) ); ?></span>
                    </div>
                    <input type="hidden" name="min_price" id="min_price" value="<?php echo esc_attr( $current_min_price ); ?>">
                    <input type="hidden" name="max_price" id="max_price" value="<?php echo esc_attr( $current_max_price ); ?>">
                </div>
            </div>
            
            <div class="filter-footer">
                <button type="submit" class="filter-apply"><?php esc_html_e( 'Apply Filter', 'aqualuxe' ); ?></button>
                <button type="button" class="filter-reset"><?php esc_html_e( 'Reset', 'aqualuxe' ); ?></button>
            </div>
        </form>
    </div>
</div>