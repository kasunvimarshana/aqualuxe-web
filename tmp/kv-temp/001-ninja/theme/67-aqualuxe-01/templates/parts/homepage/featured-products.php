<?php
/**
 * Template part for displaying homepage featured products section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Check if WooCommerce is active
if ( ! class_exists( 'WooCommerce' ) ) {
    return;
}

// Get featured products settings
$title = get_theme_mod( 'aqualuxe_homepage_featured_products_title', __( 'Featured Products', 'aqualuxe' ) );
$subtitle = get_theme_mod( 'aqualuxe_homepage_featured_products_subtitle', __( 'Our Premium Selection', 'aqualuxe' ) );
$count = get_theme_mod( 'aqualuxe_homepage_featured_products_count', 8 );
$columns = get_theme_mod( 'aqualuxe_homepage_featured_products_columns', 4 );
$type = get_theme_mod( 'aqualuxe_homepage_featured_products_type', 'featured' );

// Query arguments
$args = array(
    'post_type'           => 'product',
    'post_status'         => 'publish',
    'posts_per_page'      => $count,
    'orderby'             => 'date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => true,
);

// Modify query based on type
switch ( $type ) {
    case 'featured':
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
                'operator' => 'IN',
            ),
        );
        break;
    
    case 'bestsellers':
        $args['meta_key'] = 'total_sales';
        $args['orderby'] = 'meta_value_num';
        break;
    
    case 'new':
        $args['orderby'] = 'date';
        break;
    
    case 'sale':
        $args['meta_query'] = array(
            array(
                'key'     => '_sale_price',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'NUMERIC',
            ),
        );
        break;
}

// Get products
$products = new WP_Query( $args );

// Check if products exist
if ( $products->have_posts() ) :
?>

<section class="homepage-featured-products">
    <div class="<?php echo esc_attr( aqualuxe_get_container_class() ); ?>">
        <div class="section-header">
            <?php if ( ! empty( $subtitle ) ) : ?>
                <div class="section-subtitle"><?php echo esc_html( $subtitle ); ?></div>
            <?php endif; ?>
            
            <?php if ( ! empty( $title ) ) : ?>
                <h2 class="section-title"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>
        </div>
        
        <div class="products columns-<?php echo esc_attr( $columns ); ?>">
            <?php
            while ( $products->have_posts() ) :
                $products->the_post();
                wc_get_template_part( 'content', 'product' );
            endwhile;
            
            wp_reset_postdata();
            ?>
        </div>
        
        <div class="section-footer">
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button"><?php esc_html_e( 'View All Products', 'aqualuxe' ); ?></a>
        </div>
    </div>
</section>

<?php
endif;