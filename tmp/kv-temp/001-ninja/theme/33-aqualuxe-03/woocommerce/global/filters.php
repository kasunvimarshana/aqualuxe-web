<?php
/**
 * Product Filters
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/filters.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// Get filter settings from theme customizer
$filter_layout = get_theme_mod( 'aqualuxe_filter_layout', 'sidebar' );
$show_price_filter = get_theme_mod( 'aqualuxe_filter_price', true );
$show_attribute_filter = get_theme_mod( 'aqualuxe_filter_attributes', true );
$show_rating_filter = get_theme_mod( 'aqualuxe_filter_rating', true );
$show_active_filters = get_theme_mod( 'aqualuxe_filter_active', true );
$show_category_filter = get_theme_mod( 'aqualuxe_filter_categories', true );
$show_tag_filter = get_theme_mod( 'aqualuxe_filter_tags', true );
$show_filter_button = get_theme_mod( 'aqualuxe_filter_button', true );
?>

<div class="aqualuxe-product-filters aqualuxe-filter-layout-<?php echo esc_attr( $filter_layout ); ?>">
    <?php if ( 'offcanvas' === $filter_layout && $show_filter_button ) : ?>
        <button class="aqualuxe-filter-toggle">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>
            <?php esc_html_e( 'Filter', 'aqualuxe' ); ?>
        </button>
    <?php endif; ?>

    <div class="aqualuxe-filter-container">
        <?php if ( 'offcanvas' === $filter_layout ) : ?>
            <div class="aqualuxe-filter-header">
                <h3><?php esc_html_e( 'Filter Products', 'aqualuxe' ); ?></h3>
                <button class="aqualuxe-filter-close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    <span class="screen-reader-text"><?php esc_html_e( 'Close filters', 'aqualuxe' ); ?></span>
                </button>
            </div>
        <?php endif; ?>

        <?php if ( $show_active_filters ) : ?>
            <div class="aqualuxe-filter-section aqualuxe-active-filters">
                <h4><?php esc_html_e( 'Active Filters', 'aqualuxe' ); ?></h4>
                <?php the_widget( 'WC_Widget_Layered_Nav_Filters' ); ?>
            </div>
        <?php endif; ?>

        <?php if ( $show_category_filter ) : ?>
            <div class="aqualuxe-filter-section aqualuxe-category-filter">
                <h4><?php esc_html_e( 'Product Categories', 'aqualuxe' ); ?></h4>
                <?php
                the_widget( 'WC_Widget_Product_Categories', array(
                    'title' => '',
                    'hierarchical' => 1,
                    'show_children_only' => 0,
                    'hide_empty' => 1,
                    'max_depth' => 3,
                ) );
                ?>
            </div>
        <?php endif; ?>

        <?php if ( $show_price_filter ) : ?>
            <div class="aqualuxe-filter-section aqualuxe-price-filter">
                <h4><?php esc_html_e( 'Filter by Price', 'aqualuxe' ); ?></h4>
                <?php the_widget( 'WC_Widget_Price_Filter', array( 'title' => '' ) ); ?>
            </div>
        <?php endif; ?>

        <?php if ( $show_attribute_filter ) : ?>
            <?php
            // Get filterable attributes
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            if ( ! empty( $attribute_taxonomies ) ) {
                foreach ( $attribute_taxonomies as $attribute ) {
                    $attribute_name = wc_attribute_taxonomy_name( $attribute->attribute_name );
                    $terms = get_terms( array(
                        'taxonomy' => $attribute_name,
                        'hide_empty' => true,
                    ) );

                    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                        ?>
                        <div class="aqualuxe-filter-section aqualuxe-attribute-filter">
                            <h4><?php echo esc_html( $attribute->attribute_label ); ?></h4>
                            <?php
                            the_widget( 'WC_Widget_Layered_Nav', array(
                                'title' => '',
                                'attribute' => $attribute->attribute_name,
                                'display_type' => 'list',
                                'query_type' => 'and',
                            ) );
                            ?>
                        </div>
                        <?php
                    }
                }
            }
            ?>
        <?php endif; ?>

        <?php if ( $show_rating_filter ) : ?>
            <div class="aqualuxe-filter-section aqualuxe-rating-filter">
                <h4><?php esc_html_e( 'Filter by Rating', 'aqualuxe' ); ?></h4>
                <?php the_widget( 'WC_Widget_Rating_Filter', array( 'title' => '' ) ); ?>
            </div>
        <?php endif; ?>

        <?php if ( $show_tag_filter ) : ?>
            <div class="aqualuxe-filter-section aqualuxe-tag-filter">
                <h4><?php esc_html_e( 'Product Tags', 'aqualuxe' ); ?></h4>
                <?php
                the_widget( 'WC_Widget_Product_Tag_Cloud', array(
                    'title' => '',
                ) );
                ?>
            </div>
        <?php endif; ?>

        <?php if ( 'offcanvas' === $filter_layout ) : ?>
            <div class="aqualuxe-filter-actions">
                <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="aqualuxe-reset-filters">
                    <?php esc_html_e( 'Reset Filters', 'aqualuxe' ); ?>
                </a>
                <button class="aqualuxe-apply-filters">
                    <?php esc_html_e( 'Apply Filters', 'aqualuxe' ); ?>
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>