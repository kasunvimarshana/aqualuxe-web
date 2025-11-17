<?php
/**
 * Template part for displaying the footer copyright
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get copyright text from theme mod
$copyright_text = get_theme_mod( 'aqualuxe_copyright_text', sprintf( esc_html__( '© %s AquaLuxe. All Rights Reserved.', 'aqualuxe' ), date( 'Y' ) ) );

// Replace {year} placeholder with current year
$copyright_text = str_replace( '{year}', date( 'Y' ), $copyright_text );
?>

<div class="site-info">
	<div class="container">
		<div class="site-info-inner">
			<div class="copyright">
				<?php echo wp_kses_post( $copyright_text ); ?>
			</div>

			<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<nav class="footer-navigation">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'menu_id'        => 'footer-menu',
							'depth'          => 1,
							'container'      => false,
							'menu_class'     => 'footer-menu',
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
				<?php aqualuxe_social_links(); ?>
			<?php endif; ?>
		</div>
	</div>
</div><!-- .site-info -->