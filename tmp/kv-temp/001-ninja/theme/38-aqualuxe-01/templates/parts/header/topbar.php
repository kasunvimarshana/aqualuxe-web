<?php
/**
 * Template part for displaying the header topbar
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Check if topbar should be displayed
$show_topbar = get_theme_mod( 'aqualuxe_show_topbar', true );

if ( ! $show_topbar ) {
	return;
}

// Get contact information
$contact_email = get_theme_mod( 'aqualuxe_contact_email', AQUALUXE_CONTACT_EMAIL );
$contact_phone = get_theme_mod( 'aqualuxe_contact_phone', AQUALUXE_CONTACT_PHONE );
?>

<div class="topbar">
	<div class="container">
		<div class="topbar-inner">
			<div class="topbar-contact">
				<?php if ( $contact_phone ) : ?>
					<div class="topbar-phone">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z" clip-rule="evenodd" /></svg>
						<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $contact_phone ) ); ?>"><?php echo esc_html( $contact_phone ); ?></a>
					</div>
				<?php endif; ?>

				<?php if ( $contact_email ) : ?>
					<div class="topbar-email">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" /><path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" /></svg>
						<a href="mailto:<?php echo esc_attr( $contact_email ); ?>"><?php echo esc_html( $contact_email ); ?></a>
					</div>
				<?php endif; ?>
			</div>

			<div class="topbar-extras">
				<?php if ( has_nav_menu( 'secondary' ) ) : ?>
					<nav class="secondary-navigation">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'secondary',
								'menu_id'        => 'secondary-menu',
								'depth'          => 1,
								'container'      => false,
								'menu_class'     => 'secondary-menu',
							)
						);
						?>
					</nav>
				<?php endif; ?>

				<?php if ( has_nav_menu( 'social' ) ) : ?>
					<nav class="social-navigation">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'social',
								'menu_id'        => 'social-menu',
								'depth'          => 1,
								'container'      => false,
								'menu_class'     => 'social-menu',
								'link_before'    => '<span class="screen-reader-text">',
								'link_after'     => '</span>',
							)
						);
						?>
					</nav>
				<?php else : ?>
					<div class="social-links">
						<?php aqualuxe_social_links(); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div><!-- .topbar -->