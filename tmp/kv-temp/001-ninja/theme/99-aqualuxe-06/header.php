<?php
/**
 * The header for AquaLuxe theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

<div id="page" class="site">
	<header id="masthead" class="site-header" role="banner">
		<div class="container">
			<div class="site-header__inner">
				
				<?php
				/**
				 * Hook: aqualuxe_header_start
				 */
				do_action( 'aqualuxe_header_start' );
				?>

				<div class="site-branding">
					<?php
					the_custom_logo();
					if ( is_front_page() && is_home() ) :
						?>
						<h1 class="site-title">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<?php bloginfo( 'name' ); ?>
							</a>
						</h1>
						<?php
					else :
						?>
						<p class="site-title">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<?php bloginfo( 'name' ); ?>
							</a>
						</p>
						<?php
					endif;
					$aqualuxe_description = get_bloginfo( 'description', 'display' );
					if ( $aqualuxe_description || is_customize_preview() ) :
						?>
						<p class="site-description"><?php echo esc_html( $aqualuxe_description ); ?></p>
					<?php endif; ?>
				</div><!-- .site-branding -->

				<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Main Navigation', 'aqualuxe' ); ?>">
					<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" data-mobile-menu-toggle>
						<?php echo aqualuxe_get_svg_icon( 'menu' ); ?>
						<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'aqualuxe' ); ?></span>
					</button>
					
					<div class="main-navigation__menu" data-mobile-menu>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'primary',
								'menu_id'        => 'primary-menu',
								'menu_class'     => 'primary-menu',
								'container'      => false,
								'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
							)
						);
						?>
					</div>
				</nav><!-- #site-navigation -->

				<div class="site-header__actions">
					<?php
					/**
					 * Hook: aqualuxe_header_actions
					 *
					 * @hooked aqualuxe_header_search - 10
					 * @hooked aqualuxe_header_cart - 20
					 * @hooked aqualuxe_header_account - 30
					 * @hooked aqualuxe_header_dark_mode_toggle - 40
					 */
					do_action( 'aqualuxe_header_actions' );
					?>
				</div>

				<?php
				/**
				 * Hook: aqualuxe_header_end
				 */
				do_action( 'aqualuxe_header_end' );
				?>

			</div><!-- .site-header__inner -->
		</div><!-- .container -->
	</header><!-- #masthead -->

	<?php
	/**
	 * Hook: aqualuxe_after_header
	 *
	 * @hooked aqualuxe_output_page_hero - 10
	 */
	do_action( 'aqualuxe_after_header' );
	?>

	<div id="content" class="site-content">
		<div class="container">
			<?php
			/**
			 * Hook: aqualuxe_content_start
			 */
			do_action( 'aqualuxe_content_start' );
			?>