<?php
/**
 * Template part for displaying the newsletter section
 *
 * @package AquaLuxe
 */

// Check if newsletter should be displayed
if ( ! get_theme_mod( 'aqualuxe_display_newsletter', true ) ) {
	return;
}

// Get newsletter content
$newsletter_title = aqualuxe_translate_theme_mod( 'aqualuxe_newsletter_title', __( 'Subscribe to Our Newsletter', 'aqualuxe' ) );
$newsletter_text = aqualuxe_translate_theme_mod( 'aqualuxe_newsletter_text', __( 'Stay updated with our latest products and news.', 'aqualuxe' ) );
$newsletter_form = get_theme_mod( 'aqualuxe_newsletter_form', '' );
?>

<div class="newsletter-section">
	<div class="container">
		<div class="newsletter-content">
			<?php if ( $newsletter_title ) : ?>
				<h3 class="newsletter-title"><?php echo esc_html( $newsletter_title ); ?></h3>
			<?php endif; ?>

			<?php if ( $newsletter_text ) : ?>
				<p class="newsletter-text"><?php echo esc_html( $newsletter_text ); ?></p>
			<?php endif; ?>
		</div>

		<div class="newsletter-form">
			<?php
			if ( $newsletter_form ) {
				echo do_shortcode( $newsletter_form );
			} else {
				// Default form
				?>
				<form action="#" method="post" class="newsletter-default-form">
					<div class="form-group">
						<input type="email" name="email" placeholder="<?php esc_attr_e( 'Your email address', 'aqualuxe' ); ?>" required>
						<button type="submit"><?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?></button>
					</div>
					<p class="newsletter-privacy"><?php esc_html_e( 'By subscribing, you agree to our Privacy Policy.', 'aqualuxe' ); ?></p>
				</form>
				<?php
			}
			?>
		</div>
	</div>
</div>