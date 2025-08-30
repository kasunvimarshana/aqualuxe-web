<?php
/**
 * Template part for displaying the top bar in the header
 *
 * @package AquaLuxe
 */

// Get top bar settings from customizer or use defaults
$top_bar_enable = get_theme_mod( 'aqualuxe_top_bar_enable', true );
$top_bar_text = get_theme_mod( 'aqualuxe_top_bar_text', __( 'Free shipping on orders over $100', 'aqualuxe' ) );
$top_bar_phone = get_theme_mod( 'aqualuxe_top_bar_phone', '+1 (555) 123-4567' );
$top_bar_email = get_theme_mod( 'aqualuxe_top_bar_email', 'info@aqualuxe.com' );
$top_bar_hours = get_theme_mod( 'aqualuxe_top_bar_hours', __( 'Mon-Fri: 9AM-5PM', 'aqualuxe' ) );

// Only display the top bar if it's enabled
if ( ! $top_bar_enable ) {
	return;
}
?>

<div class="top-bar">
	<div class="container">
		<div class="top-bar-inner">
			<div class="top-bar-left">
				<?php if ( $top_bar_text ) : ?>
					<div class="top-bar-text">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
							<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
						</svg>
						<span><?php echo esc_html( $top_bar_text ); ?></span>
					</div>
				<?php endif; ?>
			</div>
			
			<div class="top-bar-right">
				<div class="top-bar-contact">
					<?php if ( $top_bar_phone ) : ?>
						<div class="top-bar-phone">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
								<path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z" clip-rule="evenodd" />
							</svg>
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $top_bar_phone ) ); ?>"><?php echo esc_html( $top_bar_phone ); ?></a>
						</div>
					<?php endif; ?>
					
					<?php if ( $top_bar_email ) : ?>
						<div class="top-bar-email">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
								<path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" />
								<path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" />
							</svg>
							<a href="mailto:<?php echo esc_attr( $top_bar_email ); ?>"><?php echo esc_html( $top_bar_email ); ?></a>
						</div>
					<?php endif; ?>
					
					<?php if ( $top_bar_hours ) : ?>
						<div class="top-bar-hours">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
								<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
							</svg>
							<span><?php echo esc_html( $top_bar_hours ); ?></span>
						</div>
					<?php endif; ?>
				</div>
				
				<?php if ( function_exists( 'aqualuxe_social_icons' ) ) : ?>
					<div class="top-bar-social">
						<?php aqualuxe_social_icons(); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>