<?php
/**
 * Template part for displaying the header main
 *
 * @package AquaLuxe
 */

// Get header style.
$header_style = get_theme_mod( 'aqualuxe_header_style', 'default' );

// Get sticky header.
$sticky_header = get_theme_mod( 'aqualuxe_sticky_header', true );
$sticky_class = $sticky_header ? 'sticky-header' : '';
?>

<div class="header-main bg-white dark:bg-dark-800 py-4 <?php echo esc_attr( $sticky_class ); ?>">
	<div class="container mx-auto px-4">
		<?php if ( 'centered' === $header_style ) : ?>
			<div class="flex flex-col items-center">
				<div class="site-branding mb-4">
					<?php
					/**
					 * Hook: aqualuxe_header_logo.
					 *
					 * @hooked aqualuxe_site_branding - 10
					 */
					do_action( 'aqualuxe_header_logo' );
					?>
				</div>
				<div class="header-navigation-wrap flex justify-between items-center w-full">
					<div class="header-navigation flex-grow">
						<?php
						/**
						 * Hook: aqualuxe_header_navigation.
						 *
						 * @hooked aqualuxe_primary_navigation - 10
						 */
						do_action( 'aqualuxe_header_navigation' );
						?>
					</div>
					<div class="header-actions flex items-center space-x-2">
						<?php
						/**
						 * Hook: aqualuxe_header_actions.
						 *
						 * @hooked aqualuxe_header_search - 10
						 * @hooked aqualuxe_header_cart - 20
						 * @hooked aqualuxe_header_wishlist - 30
						 * @hooked aqualuxe_dark_mode_toggle - 40
						 */
						do_action( 'aqualuxe_header_actions' );
						?>
					</div>
				</div>
			</div>
		<?php elseif ( 'split' === $header_style ) : ?>
			<div class="flex items-center justify-between">
				<div class="header-navigation flex-grow">
					<?php
					/**
					 * Hook: aqualuxe_header_navigation.
					 *
					 * @hooked aqualuxe_primary_navigation - 10
					 */
					do_action( 'aqualuxe_header_navigation' );
					?>
				</div>
				<div class="site-branding mx-4">
					<?php
					/**
					 * Hook: aqualuxe_header_logo.
					 *
					 * @hooked aqualuxe_site_branding - 10
					 */
					do_action( 'aqualuxe_header_logo' );
					?>
				</div>
				<div class="header-actions flex items-center space-x-2">
					<?php
					/**
					 * Hook: aqualuxe_header_actions.
					 *
					 * @hooked aqualuxe_header_search - 10
					 * @hooked aqualuxe_header_cart - 20
					 * @hooked aqualuxe_header_wishlist - 30
					 * @hooked aqualuxe_dark_mode_toggle - 40
					 */
					do_action( 'aqualuxe_header_actions' );
					?>
				</div>
			</div>
		<?php elseif ( 'minimal' === $header_style ) : ?>
			<div class="flex items-center justify-between">
				<div class="site-branding">
					<?php
					/**
					 * Hook: aqualuxe_header_logo.
					 *
					 * @hooked aqualuxe_site_branding - 10
					 */
					do_action( 'aqualuxe_header_logo' );
					?>
				</div>
				<div class="header-actions flex items-center space-x-2">
					<?php
					/**
					 * Hook: aqualuxe_header_actions.
					 *
					 * @hooked aqualuxe_header_search - 10
					 * @hooked aqualuxe_header_cart - 20
					 * @hooked aqualuxe_header_wishlist - 30
					 * @hooked aqualuxe_dark_mode_toggle - 40
					 */
					do_action( 'aqualuxe_header_actions' );
					?>
				</div>
			</div>
			<div class="header-navigation-wrap mt-4">
				<?php
				/**
				 * Hook: aqualuxe_header_navigation.
				 *
				 * @hooked aqualuxe_primary_navigation - 10
				 */
				do_action( 'aqualuxe_header_navigation' );
				?>
			</div>
		<?php else : // Default header style. ?>
			<div class="flex items-center justify-between">
				<div class="site-branding">
					<?php
					/**
					 * Hook: aqualuxe_header_logo.
					 *
					 * @hooked aqualuxe_site_branding - 10
					 */
					do_action( 'aqualuxe_header_logo' );
					?>
				</div>
				<div class="header-navigation-wrap flex items-center">
					<div class="header-navigation mr-4">
						<?php
						/**
						 * Hook: aqualuxe_header_navigation.
						 *
						 * @hooked aqualuxe_primary_navigation - 10
						 */
						do_action( 'aqualuxe_header_navigation' );
						?>
					</div>
					<div class="header-actions flex items-center space-x-2">
						<?php
						/**
						 * Hook: aqualuxe_header_actions.
						 *
						 * @hooked aqualuxe_header_search - 10
						 * @hooked aqualuxe_header_cart - 20
						 * @hooked aqualuxe_header_wishlist - 30
						 * @hooked aqualuxe_dark_mode_toggle - 40
						 */
						do_action( 'aqualuxe_header_actions' );
						?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>