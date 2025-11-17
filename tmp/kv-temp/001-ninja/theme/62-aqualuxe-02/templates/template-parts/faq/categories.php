<?php
/**
 * FAQ Page Categories Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get categories settings from customizer or ACF
$section_title = get_theme_mod( 'aqualuxe_faq_categories_title', __( 'FAQ Categories', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_faq_categories_subtitle', __( 'Browse frequently asked questions by category', 'aqualuxe' ) );
$show_categories = get_theme_mod( 'aqualuxe_faq_show_categories', true );
$columns = get_theme_mod( 'aqualuxe_faq_categories_columns', 4 );
$layout_style = get_theme_mod( 'aqualuxe_faq_categories_layout', 'grid' );

// Skip if categories are disabled
if ( ! $show_categories ) {
    return;
}

// Default categories if not set in customizer or if custom post type is not available
$default_categories = array(
    array(
        'title' => __( 'General Questions', 'aqualuxe' ),
        'icon'  => 'question-circle',
        'count' => '5',
        'slug'  => 'general',
    ),
    array(
        'title' => __( 'Products', 'aqualuxe' ),
        'icon'  => 'box',
        'count' => '8',
        'slug'  => 'products',
    ),
    array(
        'title' => __( 'Shipping & Delivery', 'aqualuxe' ),
        'icon'  => 'truck',
        'count' => '6',
        'slug'  => 'shipping',
    ),
    array(
        'title' => __( 'Returns & Refunds', 'aqualuxe' ),
        'icon'  => 'undo',
        'count' => '4',
        'slug'  => 'returns',
    ),
    array(
        'title' => __( 'Account & Orders', 'aqualuxe' ),
        'icon'  => 'user',
        'count' => '7',
        'slug'  => 'account',
    ),
    array(
        'title' => __( 'Technical Support', 'aqualuxe' ),
        'icon'  => 'wrench',
        'count' => '9',
        'slug'  => 'support',
    ),
);

// Get categories from taxonomy if available, otherwise use defaults
$categories = array();

// Check if FAQ custom post type and taxonomy exist
if ( taxonomy_exists( 'faq_category' ) ) {
    $terms = get_terms( array(
        'taxonomy'   => 'faq_category',
        'hide_empty' => false,
    ) );
    
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        foreach ( $terms as $term ) {
            $icon = get_term_meta( $term->term_id, 'icon', true );
            $categories[] = array(
                'title' => $term->name,
                'icon'  => $icon ? $icon : 'question-circle',
                'count' => $term->count,
                'slug'  => $term->slug,
                'term'  => $term,
            );
        }
    }
}

// Use default categories if none found
if ( empty( $categories ) ) {
    $categories = $default_categories;
}

// Categories grid classes
$grid_classes = array( 'faq-categories', 'layout-' . $layout_style, 'columns-' . $columns );
?>

<section class="faq-categories-section section">
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
            <?php foreach ( $categories as $category ) : ?>
                <?php
                // Determine URL
                if ( isset( $category['term'] ) ) {
                    $url = get_term_link( $category['term'] );
                } else {
                    $url = '#' . $category['slug'];
                }
                ?>
                <div class="category-item">
                    <a href="<?php echo esc_url( $url ); ?>" class="category-link">
                        <div class="category-icon">
                            <i class="icon-<?php echo esc_attr( $category['icon'] ); ?>"></i>
                        </div>
                        
                        <h3 class="category-title"><?php echo esc_html( $category['title'] ); ?></h3>
                        
                        <div class="category-count">
                            <?php
                            printf(
                                /* translators: %s: Number of FAQs */
                                _n( '%s Question', '%s Questions', $category['count'], 'aqualuxe' ),
                                number_format_i18n( $category['count'] )
                            );
                            ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>