<?php
/**
 * Archive Product Header
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product/header.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// Get archive layout from theme customizer
$archive_layout = get_theme_mod( 'aqualuxe_archive_layout', 'standard' );
$show_breadcrumbs = get_theme_mod( 'aqualuxe_archive_breadcrumbs', true );
$show_title = get_theme_mod( 'aqualuxe_archive_title', true );
$show_description = get_theme_mod( 'aqualuxe_archive_description', true );
$show_featured_image = get_theme_mod( 'aqualuxe_archive_featured_image', true );
$show_subcategories = get_theme_mod( 'aqualuxe_archive_subcategories', true );

// Get current category data if applicable
$category = get_queried_object();
$category_image_id = 0;
$category_description = '';

if ( $category && is_a( $category, 'WP_Term' ) && 'product_cat' === $category->taxonomy ) {
    $category_image_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
    $category_description = $category->description;
}
?>

<div class="aqualuxe-archive-header aqualuxe-archive-layout-<?php echo esc_attr( $archive_layout ); ?>">
    <?php if ( $show_breadcrumbs ) : ?>
        <div class="aqualuxe-archive-breadcrumbs">
            <?php woocommerce_breadcrumb(); ?>
        </div>
    <?php endif; ?>

    <?php if ( $show_title && apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
        <h1 class="aqualuxe-archive-title"><?php woocommerce_page_title(); ?></h1>
    <?php endif; ?>

    <?php if ( $show_featured_image && $category_image_id ) : ?>
        <div class="aqualuxe-archive-featured-image">
            <?php echo wp_get_attachment_image( $category_image_id, 'full' ); ?>
        </div>
    <?php endif; ?>

    <?php if ( $show_description && $category_description ) : ?>
        <div class="aqualuxe-archive-description">
            <?php echo wp_kses_post( wpautop( $category_description ) ); ?>
        </div>
    <?php endif; ?>

    <?php if ( $show_subcategories && is_product_category() ) : ?>
        <?php
        $subcategories = get_terms( array(
            'taxonomy'     => 'product_cat',
            'hide_empty'   => true,
            'parent'       => $category->term_id,
        ) );

        if ( ! empty( $subcategories ) && ! is_wp_error( $subcategories ) ) :
        ?>
            <div class="aqualuxe-archive-subcategories">
                <h2><?php esc_html_e( 'Subcategories', 'aqualuxe' ); ?></h2>
                <ul class="aqualuxe-subcategories-list">
                    <?php foreach ( $subcategories as $subcategory ) : ?>
                        <?php
                        $subcategory_image_id = get_term_meta( $subcategory->term_id, 'thumbnail_id', true );
                        $subcategory_image = $subcategory_image_id ? wp_get_attachment_image_url( $subcategory_image_id, 'thumbnail' ) : wc_placeholder_img_src();
                        ?>
                        <li class="aqualuxe-subcategory-item">
                            <a href="<?php echo esc_url( get_term_link( $subcategory ) ); ?>" class="aqualuxe-subcategory-link">
                                <div class="aqualuxe-subcategory-image">
                                    <img src="<?php echo esc_url( $subcategory_image ); ?>" alt="<?php echo esc_attr( $subcategory->name ); ?>" />
                                </div>
                                <h3 class="aqualuxe-subcategory-name"><?php echo esc_html( $subcategory->name ); ?></h3>
                                <span class="aqualuxe-subcategory-count">
                                    <?php echo esc_html( sprintf( _n( '%s product', '%s products', $subcategory->count, 'aqualuxe' ), $subcategory->count ) ); ?>
                                </span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>