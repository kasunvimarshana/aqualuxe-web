<?php
/**
 * Template Name: Services Page
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

				<!-- Services Section -->
				<section class="services-section">
					<div class="services-grid">
						<?php
						$services = get_theme_mod( 'services', array() );
						if ( ! empty( $services ) ) :
							foreach ( $services as $service ) :
								?>
								<div class="service-item">
									<div class="service-icon">
										<img src="<?php echo esc_url( $service['icon'] ); ?>" alt="<?php echo esc_attr( $service['title'] ); ?>">
									</div>
									<h3 class="service-title"><?php echo esc_html( $service['title'] ); ?></h3>
									<p class="service-description"><?php echo esc_html( $service['description'] ); ?></p>
								</div>
								<?php
							endforeach;
						endif;
						?>
					</div>
				</section>

				<!-- Breeding Programs -->
				<section class="breeding-programs">
					<h2><?php echo esc_html( get_theme_mod( 'breeding_title', __( 'Our Breeding Programs', 'aqualuxe' ) ) ); ?></h2>
					<div class="breeding-content">
						<?php echo wp_kses_post( get_theme_mod( 'breeding_content', '' ) ); ?>
					</div>
				</section>

				<!-- Consultation -->
				<section class="consultation">
					<h2><?php echo esc_html( get_theme_mod( 'consultation_title', __( 'Expert Consultation', 'aqualuxe' ) ) ); ?></h2>
					<div class="consultation-content">
						<?php echo wp_kses_post( get_theme_mod( 'consultation_content', '' ) ); ?>
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