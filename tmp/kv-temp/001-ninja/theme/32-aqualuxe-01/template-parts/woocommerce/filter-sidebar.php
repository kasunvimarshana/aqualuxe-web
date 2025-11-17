<?php
/**
 * Template part for displaying the product filter sidebar
 *
 * @package AquaLuxe
 * @since 1.2.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get price range.
$min_price = isset( $_GET['min_price'] ) ? floatval( $_GET['min_price'] ) : '';
$max_price = isset( $_GET['max_price'] ) ? floatval( $_GET['max_price'] ) : '';

// Get min and max prices from products.
$price_range = $this->get_product_price_range();
$min_price_default = $price_range['min'];
$max_price_default = $price_range['max'];

// Get current rating filter.
$current_rating = isset( $_GET['rating'] ) ? absint( $_GET['rating'] ) : 0;
?>

<div class="aqualuxe-filter-overlay"></div>

<div class="aqualuxe-filter-sidebar">
    <div class="aqualuxe-filter-header">
        <h3><?php esc_html_e( 'Filter Products', 'aqualuxe' ); ?></h3>
        <button class="aqualuxe-filter-close">&times;</button>
    </div>

    <div class="aqualuxe-filter-content">
        <form id="aqualuxe-filter-form" method="get" action="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
            <?php if ( is_active_sidebar( 'product-filter-sidebar' ) ) : ?>
                <?php dynamic_sidebar( 'product-filter-sidebar' ); ?>
            <?php else : ?>
                <!-- Price Filter -->
                <div class="widget widget_price_filter">
                    <h4 class="widget-title"><?php esc_html_e( 'Filter by price', 'aqualuxe' ); ?></h4>
                    
                    <div class="price-slider-wrapper">
                        <div id="price-slider" 
                             data-min="<?php echo esc_attr( $min_price_default ); ?>" 
                             data-max="<?php echo esc_attr( $max_price_default ); ?>" 
                             data-current-min="<?php echo esc_attr( $min_price ?: $min_price_default ); ?>" 
                             data-current-max="<?php echo esc_attr( $max_price ?: $max_price_default ); ?>">
                        </div>
                        
                        <div class="price-inputs">
                            <div class="price-input min-price">
                                <label for="price-min"><?php esc_html_e( 'Min', 'aqualuxe' ); ?></label>
                                <input type="number" id="price-min" name="min_price" value="<?php echo esc_attr( $min_price ); ?>" placeholder="<?php echo esc_attr( $min_price_default ); ?>" min="<?php echo esc_attr( $min_price_default ); ?>" max="<?php echo esc_attr( $max_price_default ); ?>" step="1">
                            </div>
                            
                            <div class="price-input max-price">
                                <label for="price-max"><?php esc_html_e( 'Max', 'aqualuxe' ); ?></label>
                                <input type="number" id="price-max" name="max_price" value="<?php echo esc_attr( $max_price ); ?>" placeholder="<?php echo esc_attr( $max_price_default ); ?>" min="<?php echo esc_attr( $min_price_default ); ?>" max="<?php echo esc_attr( $max_price_default ); ?>" step="1">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rating Filter -->
                <div class="widget widget_rating_filter">
                    <h4 class="widget-title"><?php esc_html_e( 'Filter by rating', 'aqualuxe' ); ?></h4>
                    
                    <ul>
                        <?php for ( $rating = 5; $rating >= 1; $rating-- ) : ?>
                            <li>
                                <label class="rating-filter-label">
                                    <input type="radio" name="rating" value="<?php echo esc_attr( $rating ); ?>" <?php checked( $current_rating, $rating ); ?> data-instant-filter="true">
                                    <span class="star-rating">
                                        <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                            <span class="star <?php echo ( $i <= $rating ) ? 'filled' : ''; ?>">★</span>
                                        <?php endfor; ?>
                                    </span>
                                    <span class="rating-text"><?php echo sprintf( esc_html__( '%d star and up', 'aqualuxe' ), $rating ); ?></span>
                                </label>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </div>

                <!-- Category Filter -->
                <?php
                $product_categories = get_terms( array(
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => true,
                    'parent'     => 0,
                ) );

                if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) :
                ?>
                    <div class="widget widget_product_categories">
                        <h4 class="widget-title"><?php esc_html_e( 'Product categories', 'aqualuxe' ); ?></h4>
                        
                        <ul>
                            <?php foreach ( $product_categories as $category ) : ?>
                                <li>
                                    <label>
                                        <input type="checkbox" name="product_cat[]" value="<?php echo esc_attr( $category->slug ); ?>" <?php checked( in_array( $category->slug, (array) isset( $_GET['product_cat'] ) ? $_GET['product_cat'] : array() ) ); ?>>
                                        <?php echo esc_html( $category->name ); ?>
                                        <span class="count">(<?php echo esc_html( $category->count ); ?>)</span>
                                    </label>
                                    
                                    <?php
                                    $child_categories = get_terms( array(
                                        'taxonomy'   => 'product_cat',
                                        'hide_empty' => true,
                                        'parent'     => $category->term_id,
                                    ) );

                                    if ( ! empty( $child_categories ) && ! is_wp_error( $child_categories ) ) :
                                    ?>
                                        <ul class="children">
                                            <?php foreach ( $child_categories as $child_category ) : ?>
                                                <li>
                                                    <label>
                                                        <input type="checkbox" name="product_cat[]" value="<?php echo esc_attr( $child_category->slug ); ?>" <?php checked( in_array( $child_category->slug, (array) isset( $_GET['product_cat'] ) ? $_GET['product_cat'] : array() ) ); ?>>
                                                        <?php echo esc_html( $child_category->name ); ?>
                                                        <span class="count">(<?php echo esc_html( $child_category->count ); ?>)</span>
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

                <!-- Product Attributes Filter -->
                <?php
                $attribute_taxonomies = wc_get_attribute_taxonomies();
                
                if ( ! empty( $attribute_taxonomies ) ) :
                    foreach ( $attribute_taxonomies as $attribute ) :
                        $taxonomy = 'pa_' . $attribute->attribute_name;
                        $terms = get_terms( array(
                            'taxonomy'   => $taxonomy,
                            'hide_empty' => true,
                        ) );

                        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
                        ?>
                            <div class="widget widget_layered_nav">
                                <h4 class="widget-title"><?php echo esc_html( $attribute->attribute_label ); ?></h4>
                                
                                <ul>
                                    <?php foreach ( $terms as $term ) : ?>
                                        <li>
                                            <label>
                                                <input type="checkbox" name="<?php echo esc_attr( $taxonomy ); ?>[]" value="<?php echo esc_attr( $term->slug ); ?>" <?php checked( in_array( $term->slug, (array) isset( $_GET[ $taxonomy ] ) ? $_GET[ $taxonomy ] : array() ) ); ?>>
                                                <?php echo esc_html( $term->name ); ?>
                                                <span class="count">(<?php echo esc_html( $term->count ); ?>)</span>
                                            </label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php
                        endif;
                    endforeach;
                endif;
                ?>
            <?php endif; ?>

            <!-- Filter Actions -->
            <div class="aqualuxe-filter-actions">
                <button type="button" class="aqualuxe-filter-reset button"><?php esc_html_e( 'Reset Filters', 'aqualuxe' ); ?></button>
                <button type="button" class="aqualuxe-filter-apply button button-primary"><?php esc_html_e( 'Apply Filters', 'aqualuxe' ); ?></button>
            </div>
        </form>
    </div>
</div>