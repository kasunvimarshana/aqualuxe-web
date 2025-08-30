<?php
/**
 * Template part for displaying featured products on the front page
 *
 * @package AquaLuxe
 */

// Only proceed if WooCommerce is active
if ( ! aqualuxe_is_woocommerce_active() ) {
    return;
}

// Get section settings from customizer or default values
$section_title = get_theme_mod( 'aqualuxe_featured_products_title', __( 'Featured Products', 'aqualuxe' ) );
$section_description = get_theme_mod( 'aqualuxe_featured_products_description', __( 'Discover our collection of premium aquatic products handpicked for quality and rarity.', 'aqualuxe' ) );
$number_of_products = get_theme_mod( 'aqualuxe_featured_products_count', 4 );
$columns = get_theme_mod( 'aqualuxe_featured_products_columns', 4 );
$product_type = get_theme_mod( 'aqualuxe_featured_products_type', 'featured' );
$category_slug = get_theme_mod( 'aqualuxe_featured_products_category', '' );
$view_all_text = get_theme_mod( 'aqualuxe_featured_products_view_all_text', __( 'View All Products', 'aqualuxe' ) );
$view_all_url = get_theme_mod( 'aqualuxe_featured_products_view_all_url', wc_get_page_permalink( 'shop' ) );

// Section classes
$section_classes = array(
    'section',
    'featured-products-section',
);

// Determine which products to display
$args = array(
    'posts_per_page' => $number_of_products,
    'columns'        => $columns,
    'orderby'        => 'date',
    'order'          => 'desc',
);

switch ( $product_type ) {
    case 'featured':
        $args['featured'] = true;
        break;
    case 'sale':
        $args['on_sale'] = true;
        break;
    case 'best_selling':
        $args['best_selling'] = true;
        break;
    case 'top_rated':
        $args['top_rated'] = true;
        break;
    case 'newest':
        $args['orderby'] = 'date';
        $args['order'] = 'desc';
        break;
    case 'category':
        if ( ! empty( $category_slug ) ) {
            $args['category'] = $category_slug;
        }
        break;
}

// Check if we should show the section
$show_section = get_theme_mod( 'aqualuxe_show_featured_products', true );

if ( ! $show_section ) {
    return;
}
?>

<section id="featured-products" class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">
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
            <?php
            // Display products based on the selected type
            switch ( $product_type ) {
                case 'featured':
                    echo do_shortcode( '[featured_products per_page="' . esc_attr( $number_of_products ) . '" columns="' . esc_attr( $columns ) . '"]' );
                    break;
                case 'sale':
                    echo do_shortcode( '[sale_products per_page="' . esc_attr( $number_of_products ) . '" columns="' . esc_attr( $columns ) . '"]' );
                    break;
                case 'best_selling':
                    echo do_shortcode( '[best_selling_products per_page="' . esc_attr( $number_of_products ) . '" columns="' . esc_attr( $columns ) . '"]' );
                    break;
                case 'top_rated':
                    echo do_shortcode( '[top_rated_products per_page="' . esc_attr( $number_of_products ) . '" columns="' . esc_attr( $columns ) . '"]' );
                    break;
                case 'newest':
                    echo do_shortcode( '[products orderby="date" order="desc" per_page="' . esc_attr( $number_of_products ) . '" columns="' . esc_attr( $columns ) . '"]' );
                    break;
                case 'category':
                    if ( ! empty( $category_slug ) ) {
                        echo do_shortcode( '[product_category category="' . esc_attr( $category_slug ) . '" per_page="' . esc_attr( $number_of_products ) . '" columns="' . esc_attr( $columns ) . '"]' );
                    } else {
                        echo do_shortcode( '[products per_page="' . esc_attr( $number_of_products ) . '" columns="' . esc_attr( $columns ) . '"]' );
                    }
                    break;
                default:
                    echo do_shortcode( '[products per_page="' . esc_attr( $number_of_products ) . '" columns="' . esc_attr( $columns ) . '"]' );
                    break;
            }
            ?>
        </div>
        
        <?php if ( $view_all_text && $view_all_url ) : ?>
            <div class="section-footer">
                <a href="<?php echo esc_url( $view_all_url ); ?>" class="button"><?php echo esc_html( $view_all_text ); ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>