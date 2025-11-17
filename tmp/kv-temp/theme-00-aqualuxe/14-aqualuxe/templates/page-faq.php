<?php
/**
 * Template Name: FAQ Page
 *
 * @package AquaLuxe
 */

get_header(); ?>

<main id="primary" class="site-main">

	<?php
	while ( have_posts() ) :
		the_post();
		?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<?php
				the_content();

				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
						'after'  => '</div>',
					)
				);
				?>

				<!-- FAQ Section -->
				<section class="faq-section">
					<div class="faq-accordion">
						<?php
						$faq_items = get_theme_mod( 'faq_items', array() );
						if ( ! empty( $faq_items ) ) :
							foreach ( $faq_items as $index => $faq ) :
								?>
								<div class="faq-item">
									<div class="faq-question">
										<h3><?php echo esc_html( $faq['question'] ); ?></h3>
										<span class="faq-toggle">+</span>
									</div>
									<div class="faq-answer">
										<p><?php echo wp_kses_post( $faq['answer'] ); ?></p>
									</div>
								</div>
								<?php
							endforeach;
						endif;
						?>
					</div>
				</section>

				<!-- Shipping Information -->
				<section class="shipping-info">
					<h2><?php echo esc_html( get_theme_mod( 'shipping_title', __( 'Shipping Information', 'aqualuxe' ) ) ); ?></h2>
					<div class="shipping-content">
						<?php echo wp_kses_post( get_theme_mod( 'shipping_content', '' ) ); ?>
					</div>
				</section>

				<!-- Fish Care -->
				<section class="fish-care">
					<h2><?php echo esc_html( get_theme_mod( 'care_title', __( 'Fish Care Guidelines', 'aqualuxe' ) ) ); ?></h2>
					<div class="care-content">
						<?php echo wp_kses_post( get_theme_mod( 'care_content', '' ) ); ?>
					</div>
				</section>

				<!-- Purchasing -->
				<section class="purchasing">
					<h2><?php echo esc_html( get_theme_mod( 'purchasing_title', __( 'Purchasing Information', 'aqualuxe' ) ) ); ?></h2>
					<div class="purchasing-content">
						<?php echo wp_kses_post( get_theme_mod( 'purchasing_content', '' ) ); ?>
					</div>
				</section>
			</div><!-- .entry-content -->

			<footer class="entry-footer">
				<?php edit_post_link( __( 'Edit', 'aqualuxe' ), '<span class="edit-link">', '</span>' ); ?>
			</footer><!-- .entry-footer -->
		</article><!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; // End of the loop. ?>

</main><!-- #main -->

<?php
get_footer();