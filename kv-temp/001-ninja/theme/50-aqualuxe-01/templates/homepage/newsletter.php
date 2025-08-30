<?php
/**
 * Template part for displaying the newsletter section on the homepage
 *
 * @package AquaLuxe
 */

// Get newsletter section settings from customizer or use defaults
$newsletter_title = get_theme_mod( 'aqualuxe_newsletter_title', __( 'Subscribe to Our Newsletter', 'aqualuxe' ) );
$newsletter_subtitle = get_theme_mod( 'aqualuxe_newsletter_subtitle', __( 'Stay updated with our latest arrivals, exclusive offers, and expert aquatic tips', 'aqualuxe' ) );
$newsletter_background = get_theme_mod( 'aqualuxe_newsletter_background', get_template_directory_uri() . '/assets/dist/images/newsletter-background.jpg' );
$newsletter_form_shortcode = get_theme_mod( 'aqualuxe_newsletter_form_shortcode', '' );
?>

<section id="newsletter" class="newsletter-section" style="background-image: url('<?php echo esc_url( $newsletter_background ); ?>');">
	<div class="newsletter-overlay"></div>
	
	<div class="container">
		<div class="newsletter-content">
			<div class="newsletter-text">
				<?php if ( $newsletter_title ) : ?>
					<h2 class="newsletter-title"><?php echo esc_html( $newsletter_title ); ?></h2>
				<?php endif; ?>
				
				<?php if ( $newsletter_subtitle ) : ?>
					<p class="newsletter-subtitle"><?php echo esc_html( $newsletter_subtitle ); ?></p>
				<?php endif; ?>
			</div>
			
			<div class="newsletter-form">
				<?php if ( $newsletter_form_shortcode ) : ?>
					<?php echo do_shortcode( $newsletter_form_shortcode ); ?>
				<?php else : ?>
					<form class="newsletter-subscribe-form" action="#" method="post">
						<div class="form-group">
							<input type="email" name="email" placeholder="<?php esc_attr_e( 'Your email address', 'aqualuxe' ); ?>" required>
							<button type="submit" class="btn btn-primary"><?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?></button>
						</div>
						<div class="form-consent">
							<label>
								<input type="checkbox" name="consent" required>
								<span><?php esc_html_e( 'I agree to receive emails from AquaLuxe and can unsubscribe at any time.', 'aqualuxe' ); ?></span>
							</label>
						</div>
					</form>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>