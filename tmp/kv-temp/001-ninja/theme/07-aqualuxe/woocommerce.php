<?php
/**
 * The template for displaying WooCommerce content
 *
 * This is the template that displays all WooCommerce pages by default.
 *
 * @link https://docs.woocommerce.com/document/template-structure/
 *
 * @package AquaLuxe
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main container mx-auto px-4 py-8">
			<?php
			/**
			 * Hook: woocommerce_before_main_content.
			 *
			 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
			 * @hooked woocommerce_breadcrumb - 20
			 */
			do_action( 'woocommerce_before_main_content' );
			?>

			<?php if ( is_shop() || is_product_category() || is_product_tag() ) : ?>
				<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
					<?php
					// Check if sidebar position is left
					$sidebar_position = get_theme_mod( 'aqualuxe_shop_sidebar_position', 'right' );
					if ( 'left' === $sidebar_position && is_active_sidebar( 'shop-sidebar' ) ) :
					?>
						<div class="lg:col-span-3">
							<?php get_sidebar( 'shop' ); ?>
						</div>
					<?php endif; ?>

					<div class="<?php echo is_active_sidebar( 'shop-sidebar' ) && 'none' !== $sidebar_position ? 'lg:col-span-9' : 'lg:col-span-12'; ?>">
						<?php woocommerce_content(); ?>
					</div>

					<?php
					// Check if sidebar position is right
					if ( 'right' === $sidebar_position && is_active_sidebar( 'shop-sidebar' ) ) :
					?>
						<div class="lg:col-span-3">
							<?php get_sidebar( 'shop' ); ?>
						</div>
					<?php endif; ?>
				</div>
			<?php else : ?>
				<?php woocommerce_content(); ?>
			<?php endif; ?>

			<?php
			/**
			 * Hook: woocommerce_after_main_content.
			 *
			 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			do_action( 'woocommerce_after_main_content' );
			?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();