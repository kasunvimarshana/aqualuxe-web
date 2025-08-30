<?php
/**
 * Template part for displaying product categories on the front page
 *
 * @package AquaLuxe
 */

// Only proceed if WooCommerce is active
if ( ! aqualuxe_is_woocommerce_active() ) {
    return;
}

// Get section settings from customizer or default values
$section_title = get_theme_mod( 'aqualuxe_product_categories_title', __( 'Shop by Category', 'aqualuxe' ) );
$section_description = get_theme_mod( 'aqualuxe_product_categories_description', __( 'Browse our extensive collection of premium aquatic products by category.', 'aqualuxe' ) );
$number_of_categories = get_theme_mod( 'aqualuxe_product_categories_count', 4 );
$columns = get_theme_mod( 'aqualuxe_product_categories_columns', 4 );
$hide_empty = get_theme_mod( 'aqualuxe_product_categories_hide_empty', true );
$show_count = get_theme_mod( 'aqualuxe_product_categories_show_count', true );
$category_ids = get_theme_mod( 'aqualuxe_product_categories_ids', '' );
$layout_style = get_theme_mod( 'aqualuxe_product_categories_layout', 'grid' );

// Section classes
$section_classes = array(
    'section',
    'product-categories-section',
    'product-categories-layout-' . $layout_style,
);

// Check if we should show the section
$show_section = get_theme_mod( 'aqualuxe_show_product_categories', true );

if ( ! $show_section ) {
    return;
}

// Prepare category arguments
$category_args = array(
    'taxonomy'     => 'product_cat',
    'orderby'      => 'name',
    'order'        => 'ASC',
    'hide_empty'   => $hide_empty,
    'number'       => $number_of_categories,
    'hierarchical' => 1,
    'parent'       => 0,
);

// If specific categories are selected
if ( ! empty( $category_ids ) ) {
    $category_ids = explode( ',', $category_ids );
    $category_args['include'] = $category_ids;
    $category_args['orderby'] = 'include';
    unset( $category_args['number'] );
}

// Get categories
$product_categories = get_terms( $category_args );

// Exit if no categories found
if ( empty( $product_categories ) || is_wp_error( $product_categories ) ) {
    return;
}
?>

<section id="product-categories" class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">
    <div class="container">
        <div class="section-header">
            <?php if ( $section_title ) : ?>
                <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_description ) : ?>
                <div class="section-description"><?php echo wp_kses_post( $section_description ); ?></div>
            <?php endif; ?>
        </div>
        
        <div class="section-content">
            <div class="product-categories-grid grid-cols-<?php echo esc_attr( $columns ); ?>">
                <?php foreach ( $product_categories as $category ) : ?>
                    <?php
                    $category_link = get_term_link( $category, 'product_cat' );
                    $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
                    $image = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'medium_large' ) : wc_placeholder_img_src();
                    ?>
                    <div class="product-category-item">
                        <a href="<?php echo esc_url( $category_link ); ?>" class="product-category-link">
                            <div class="product-category-image" style="background-image: url('<?php echo esc_url( $image ); ?>');">
                                <div class="product-category-overlay"></div>
                                <div class="product-category-content">
                                    <h3 class="product-category-title">
                                        <?php echo esc_html( $category->name ); ?>
                                        <?php if ( $show_count ) : ?>
                                            <span class="product-category-count">(<?php echo esc_html( $category->count ); ?>)</span>
                                        <?php endif; ?>
                                    </h3>
                                    
                                    <?php if ( $category->description ) : ?>
                                        <div class="product-category-description">
                                            <?php echo wp_kses_post( $category->description ); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <span class="product-category-button button button-outline"><?php esc_html_e( 'Shop Now', 'aqualuxe' ); ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>