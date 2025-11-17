<?php
/**
 * Template part for displaying the newsletter section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get newsletter section settings from theme mods
$section_title = get_theme_mod( 'aqualuxe_newsletter_title', esc_html__( 'Subscribe to Our Newsletter', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_newsletter_subtitle', esc_html__( 'Stay Updated', 'aqualuxe' ) );
$section_text = get_theme_mod( 'aqualuxe_newsletter_text', esc_html__( 'Join our mailing list to receive the latest updates, exclusive offers, and expert tips on aquatic care.', 'aqualuxe' ) );
$background_image = get_theme_mod( 'aqualuxe_newsletter_background', '' );
$form_shortcode = get_theme_mod( 'aqualuxe_newsletter_form_shortcode', '' );

// Check if newsletter section should be displayed
$show_newsletter = get_theme_mod( 'aqualuxe_show_newsletter', true );

if ( ! $show_newsletter ) {
	return;
}

// Set default background image if none is set
if ( empty( $background_image ) ) {
	$background_image = AQUALUXE_ASSETS_URI . 'images/newsletter-background.jpg';
}
?>

<section id="newsletter" class="newsletter-section" style="background-image: url('<?php echo esc_url( $background_image ); ?>');">
	<div class="newsletter-overlay"></div>
	<div class="container">
		<div class="newsletter-inner">
			<div class="newsletter-content">
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

			<div class="newsletter-form">
				<?php if ( $form_shortcode ) : ?>
					<?php echo do_shortcode( $form_shortcode ); ?>
				<?php else : ?>
					<form class="newsletter-subscribe-form" action="#" method="post">
						<div class="form-group">
							<input type="email" name="email" placeholder="<?php esc_attr_e( 'Your Email Address', 'aqualuxe' ); ?>" required>
							<button type="submit" class="button button-primary"><?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?></button>
						</div>
						<div class="form-privacy">
							<label>
								<input type="checkbox" name="privacy" required>
								<span><?php esc_html_e( 'I agree to the privacy policy', 'aqualuxe' ); ?></span>
							</label>
						</div>
					</form>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section><!-- .newsletter-section -->