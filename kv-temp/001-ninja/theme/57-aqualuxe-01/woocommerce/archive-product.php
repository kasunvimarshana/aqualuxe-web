<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<header class="woocommerce-products-header">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
	<?php endif; ?>

	<?php
	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );
	?>
</header>

<?php
// Display category thumbnails if available
if ( is_product_category() ) {
	$category = get_queried_object();
	$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
	
	if ( $thumbnail_id ) {
		$image = wp_get_attachment_image_src( $thumbnail_id, 'full' );
		if ( $image ) {
			echo '<div class="category-banner">';
			echo '<img src="' . esc_url( $image[0] ) . '" alt="' . esc_attr( $category->name ) . '" />';
			echo '</div>';
		}
	}
}
?>

<div class="aqualuxe-shop-controls">
	<div class="aqualuxe-shop-filters">
		<?php
		/**
		 * Hook: aqualuxe_before_shop_loop_filters.
		 *
		 * @hooked aqualuxe_product_filter_button - 10
		 */
		do_action( 'aqualuxe_before_shop_loop_filters' );
		?>
	</div>

	<div class="aqualuxe-shop-sorting">
		<?php
		/**
		 * Hook: aqualuxe_before_shop_loop_sorting.
		 *
		 * @hooked aqualuxe_product_view_switcher - 10
		 */
		do_action( 'aqualuxe_before_shop_loop_sorting' );
		?>
		
		<?php
		/**
		 * Hook: woocommerce_before_shop_loop.
		 *
		 * @hooked woocommerce_output_all_notices - 10
		 * @hooked woocommerce_result_count - 20
		 * @hooked woocommerce_catalog_ordering - 30
		 */
		do_action( 'woocommerce_before_shop_loop' );
		?>
	</div>
</div>

<?php
if ( woocommerce_product_loop() ) {
	?>
	<div class="aqualuxe-shop-layout">
		<div class="aqualuxe-shop-sidebar">
			<?php
			/**
			 * Hook: aqualuxe_shop_sidebar.
			 *
			 * @hooked aqualuxe_shop_filters - 10
			 */
			do_action( 'aqualuxe_shop_sidebar' );
			?>
		</div>

		<div class="aqualuxe-shop-products">
			<?php
			woocommerce_product_loop_start();

			if ( wc_get_loop_prop( 'total' ) ) {
				while ( have_posts() ) {
					the_post();

					/**
					 * Hook: woocommerce_shop_loop.
					 */
					do_action( 'woocommerce_shop_loop' );

					wc_get_template_part( 'content', 'product' );
				}
			}

			woocommerce_product_loop_end();

			/**
			 * Hook: woocommerce_after_shop_loop.
			 *
			 * @hooked woocommerce_pagination - 10
			 */
			do_action( 'woocommerce_after_shop_loop' );
			?>
		</div>
	</div>
	<?php
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

// Featured categories section
if ( is_shop() ) {
	?>
	<section class="featured-categories">
		<div class="container">
			<h2><?php esc_html_e( 'Shop by Category', 'aqualuxe' ); ?></h2>
			
			<div class="category-grid">
				<?php
				$categories = get_terms( array(
					'taxonomy'   => 'product_cat',
					'hide_empty' => true,
					'parent'     => 0,
					'number'     => 4,
				) );

				if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
					foreach ( $categories as $category ) {
						$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
						$image = $thumbnail_id ? wp_get_attachment_image_src( $thumbnail_id, 'medium' ) : '';
						?>
						<div class="category-item">
							<a href="<?php echo esc_url( get_term_link( $category ) ); ?>">
								<?php if ( $image ) : ?>
									<img src="<?php echo esc_url( $image[0] ); ?>" alt="<?php echo esc_attr( $category->name ); ?>" />
								<?php endif; ?>
								<h3><?php echo esc_html( $category->name ); ?></h3>
							</a>
						</div>
						<?php
					}
				}
				?>
			</div>
		</div>
	</section>
	<?php
}

get_footer( 'shop' );