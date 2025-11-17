<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

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
<div class="product-container">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>

		<?php wc_get_template_part( 'content', 'single-product' ); ?>

	<?php endwhile; // end of the loop. ?>
</div>

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

// Display recently viewed products if enabled
if ( get_theme_mod( 'aqualuxe_enable_recently_viewed', true ) ) :
	// Get recently viewed product cookies data
	$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) : array();
	$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

	if ( ! empty( $viewed_products ) ) :
		$recently_viewed_title = get_theme_mod( 'aqualuxe_recently_viewed_title', esc_html__( 'Recently Viewed Products', 'aqualuxe' ) );
		$recently_viewed_count = get_theme_mod( 'aqualuxe_recently_viewed_count', 4 );
		
		// Remove current product ID from the array
		$current_product_id = get_the_ID();
		$viewed_products = array_diff( $viewed_products, array( $current_product_id ) );
		
		if ( ! empty( $viewed_products ) ) :
			$args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => $recently_viewed_count,
				'post__in'            => $viewed_products,
				'orderby'             => 'post__in',
			);
			
			$recently_viewed_query = new WP_Query( $args );
			
			if ( $recently_viewed_query->have_posts() ) :
			?>
				<div class="recently-viewed-products container mx-auto px-4 mt-12 pt-8 border-t border-gray-200 dark:border-dark-700">
					<h2 class="text-2xl font-serif font-bold text-gray-900 dark:text-gray-100 mb-6">
						<?php echo esc_html( $recently_viewed_title ); ?>
					</h2>
					
					<div class="recently-viewed-grid grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
						<?php
						while ( $recently_viewed_query->have_posts() ) :
							$recently_viewed_query->the_post();
							wc_get_template_part( 'content', 'product' );
						endwhile;
						
						wp_reset_postdata();
						?>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>

<?php
/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );