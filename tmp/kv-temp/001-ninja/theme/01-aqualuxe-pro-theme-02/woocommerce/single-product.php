<?php
/**
 * The template for displaying all single products
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     AquaLuxe
 * @since       1.0.0
 */

get_header( 'shop' ); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
				/**
				 * woocommerce_before_main_content hook.
				 *
				 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
				 * @hooked woocommerce_breadcrumb - 20
				 */
				do_action( 'woocommerce_before_main_content' );
			?>

				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>

					<?php wc_get_template_part( 'content', 'single-product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php
				/**
				 * woocommerce_after_main_content hook.
				 *
				 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
				 */
				do_action( 'woocommerce_after_main_content' );
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer( 'shop' );

/* Omit closing PHP tag to avoid "headers already sent" issues. */
