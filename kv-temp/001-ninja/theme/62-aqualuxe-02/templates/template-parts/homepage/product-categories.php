<?php
/**
 * Homepage Product Categories Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Skip if WooCommerce is not active
if ( ! class_exists( 'WooCommerce' ) ) {
    return;
}

// Get product categories settings from customizer or ACF
$section_title = get_theme_mod( 'aqualuxe_product_categories_title', __( 'Shop by Category', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_product_categories_subtitle', __( 'Browse our wide range of product categories', 'aqualuxe' ) );
$categories_count = get_theme_mod( 'aqualuxe_product_categories_count', 6 );
$columns = get_theme_mod( 'aqualuxe_product_categories_columns', 3 );
$hide_empty = get_theme_mod( 'aqualuxe_product_categories_hide_empty', true );
$show_count = get_theme_mod( 'aqualuxe_product_categories_show_count', true );
$layout_style = get_theme_mod( 'aqualuxe_product_categories_layout', 'grid' );

// Get categories
$args = array(
    'taxonomy'   => 'product_cat',
    'orderby'    => 'name',
    'order'      => 'ASC',
    'hide_empty' => $hide_empty,
    'number'     => $categories_count,
    'parent'     => 0, // Only top-level categories
);

$product_categories = get_terms( $args );

// Skip if no categories
if ( empty( $product_categories ) || is_wp_error( $product_categories ) ) {
    return;
}

// Category section classes
$section_classes = array( 'product-categories-section', 'section' );
$grid_classes = array( 'categories-grid', 'layout-' . $layout_style, 'columns-' . $columns );
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">
    <div class="container">
        <div class="section-header text-center">
            <?php if ( $section_title ) : ?>
                <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_subtitle ) : ?>
                <div class="section-subtitle">
                    <p><?php echo wp_kses_post( $section_subtitle ); ?></p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="<?php echo esc_attr( implode( ' ', $grid_classes ) ); ?>">
            <?php foreach ( $product_categories as $category ) : ?>
                <?php
                $category_link = get_term_link( $category, 'product_cat' );
                $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
                $image = wp_get_attachment_image_src( $thumbnail_id, 'woocommerce_thumbnail' );
                $image_url = $image ? $image[0] : wc_placeholder_img_src();
                ?>
                
                <div class="category-item">
                    <a href="<?php echo esc_url( $category_link ); ?>" class="category-link">
                        <div class="category-image">
                            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $category->name ); ?>">
                        </div>
                        
                        <div class="category-content">
                            <h3 class="category-title"><?php echo esc_html( $category->name ); ?></h3>
                            
                            <?php if ( $show_count ) : ?>
                                <span class="category-count">
                                    <?php echo esc_html( sprintf( _n( '%s product', '%s products', $category->count, 'aqualuxe' ), $category->count ) ); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>