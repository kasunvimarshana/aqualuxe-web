<?php
/**
 * Template Name: Contact Page
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
				<div class="contact-content">
					<div class="contact-info">
						<?php
						the_content();

						wp_link_pages(
							array(
								'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
								'after'  => '</div>',
							)
						);
						?>

						<!-- Contact Details -->
						<div class="contact-details">
							<h2><?php echo esc_html( get_theme_mod( 'contact_info_title', __( 'Contact Information', 'aqualuxe' ) ) ); ?></h2>
							<div class="contact-items">
								<div class="contact-item">
									<h3><?php echo esc_html__( 'Address', 'aqualuxe' ); ?></h3>
									<p><?php echo wp_kses_post( get_theme_mod( 'contact_address', '' ) ); ?></p>
								</div>
								<div class="contact-item">
									<h3><?php echo esc_html__( 'Phone', 'aqualuxe' ); ?></h3>
									<p><?php echo esc_html( get_theme_mod( 'contact_phone', '' ) ); ?></p>
								</div>
								<div class="contact-item">
									<h3><?php echo esc_html__( 'Email', 'aqualuxe' ); ?></h3>
									<p><?php echo esc_html( get_theme_mod( 'contact_email', '' ) ); ?></p>
								</div>
								<div class="contact-item">
									<h3><?php echo esc_html__( 'Hours', 'aqualuxe' ); ?></h3>
									<p><?php echo wp_kses_post( get_theme_mod( 'contact_hours', '' ) ); ?></p>
								</div>
							</div>
						</div>
					</div>

					<div class="contact-form">
						<h2><?php echo esc_html( get_theme_mod( 'contact_form_title', __( 'Send Us a Message', 'aqualuxe' ) ) ); ?></h2>
						<?php echo do_shortcode( get_theme_mod( 'contact_form_shortcode', '' ) ); ?>
					</div>
				</div>

				<!-- Google Maps -->
				<div class="google-map">
					<h2><?php echo esc_html( get_theme_mod( 'map_title', __( 'Find Us', 'aqualuxe' ) ) ); ?></h2>
					<div class="map-container">
						<?php echo wp_kses_post( get_theme_mod( 'google_map_embed', '' ) ); ?>
					</div>
				</div>
			</div><!-- .entry-content -->

			<footer class="entry-footer">
				<?php edit_post_link( __( 'Edit', 'aqualuxe' ), '<span class="edit-link">', '</span>' ); ?>
			</footer><!-- .entry-footer -->
		</article><!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; // End of the loop. ?>

</main><!-- #main -->

<?php
get_footer();