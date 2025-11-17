<?php
/**
 * Template part for displaying the featured products section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Check if WooCommerce is active
if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

// Get featured products section settings from theme mods
$section_title = get_theme_mod( 'aqualuxe_featured_products_title', esc_html__( 'Featured Products', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_featured_products_subtitle', esc_html__( 'Our Premium Selection', 'aqualuxe' ) );
$section_text = get_theme_mod( 'aqualuxe_featured_products_text', esc_html__( 'Discover our handpicked collection of premium aquatic products, rare fish species, and exclusive aquarium accessories.', 'aqualuxe' ) );
$products_count = get_theme_mod( 'aqualuxe_featured_products_count', 4 );
$products_columns = get_theme_mod( 'aqualuxe_featured_products_columns', 4 );
$show_view_all = get_theme_mod( 'aqualuxe_featured_products_show_view_all', true );
$view_all_text = get_theme_mod( 'aqualuxe_featured_products_view_all_text', esc_html__( 'View All Products', 'aqualuxe' ) );

// Check if featured products section should be displayed
$show_featured_products = get_theme_mod( 'aqualuxe_show_featured_products', true );

if ( ! $show_featured_products ) {
	return;
}
?>

<section id="featured-products" class="featured-products-section">
	<div class="container">
		<div class="section-header">
			<?php if ( $section_subtitle ) : ?>
				<div class="section-subtitle"><?php echo esc_html( $section_subtitle ); ?></div>
			<?php endif; ?>

			<?php if ( $section_title ) : ?>
				<h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
			<?php endif; ?>

			<?php if ( $section_text ) : ?>
				<div class="section-text"><?php echo wp_kses_post( $section_text ); ?></div>
			<?php endif; ?>
		</div>

		<div class="featured-products">
			<?php
			echo do_shortcode( '[products limit="' . esc_attr( $products_count ) . '" columns="' . esc_attr( $products_columns ) . '" visibility="featured"]' );
			?>
		</div>

		<?php if ( $show_view_all && $view_all_text ) : ?>
			<div class="section-footer">
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button button-secondary"><?php echo esc_html( $view_all_text ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section><!-- .featured-products-section -->