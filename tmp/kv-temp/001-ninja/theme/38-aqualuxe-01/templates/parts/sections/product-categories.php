<?php
/**
 * Template part for displaying the product categories section
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

// Get product categories section settings from theme mods
$section_title = get_theme_mod( 'aqualuxe_product_categories_title', esc_html__( 'Shop by Category', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_product_categories_subtitle', esc_html__( 'Explore Our Collections', 'aqualuxe' ) );
$section_text = get_theme_mod( 'aqualuxe_product_categories_text', esc_html__( 'Browse our carefully curated categories of premium aquatic products, from rare fish species to high-end aquarium equipment.', 'aqualuxe' ) );
$categories_count = get_theme_mod( 'aqualuxe_product_categories_count', 4 );
$categories_columns = get_theme_mod( 'aqualuxe_product_categories_columns', 4 );

// Check if product categories section should be displayed
$show_product_categories = get_theme_mod( 'aqualuxe_show_product_categories', true );

if ( ! $show_product_categories ) {
	return;
}

// Get product categories
$args = array(
	'taxonomy'     => 'product_cat',
	'orderby'      => 'name',
	'order'        => 'ASC',
	'hide_empty'   => true,
	'number'       => $categories_count,
	'hierarchical' => false,
	'parent'       => 0,
);

$product_categories = get_terms( $args );

// Return if no categories found
if ( empty( $product_categories ) || is_wp_error( $product_categories ) ) {
	return;
}
?>

<section id="product-categories" class="product-categories-section">
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

		<div class="product-categories-grid columns-<?php echo esc_attr( $categories_columns ); ?>">
			<?php foreach ( $product_categories as $category ) : ?>
				<?php
				$category_link = get_term_link( $category, 'product_cat' );
				$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
				$image = $thumbnail_id ? wp_get_attachment_image_src( $thumbnail_id, 'aqualuxe-card' ) : '';
				$image_url = $image ? $image[0] : wc_placeholder_img_src( 'aqualuxe-card' );
				?>
				<div class="product-category-card">
					<a href="<?php echo esc_url( $category_link ); ?>" class="product-category-link">
						<div class="product-category-image" style="background-image: url('<?php echo esc_url( $image_url ); ?>');">
							<div class="product-category-overlay"></div>
						</div>
						<div class="product-category-content">
							<h3 class="product-category-title"><?php echo esc_html( $category->name ); ?></h3>
							<?php if ( $category->count > 0 ) : ?>
								<span class="product-category-count">
									<?php
									printf(
										/* translators: %d: number of products */
										_n( '%d product', '%d products', $category->count, 'aqualuxe' ),
										$category->count
									);
									?>
								</span>
							<?php endif; ?>
						</div>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section><!-- .product-categories-section -->