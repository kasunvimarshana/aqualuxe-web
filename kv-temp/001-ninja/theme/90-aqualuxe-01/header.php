<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class('bg-white text-gray-800'); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site min-h-screen flex flex-col">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

	<header id="masthead" class="site-header bg-gray-900 text-white shadow-md">
		<div class="container mx-auto px-4">
			<div class="flex justify-between items-center py-4">
				<div class="site-branding">
					<?php
					if ( has_custom_logo() ) {
						the_custom_logo();
					}
					if ( is_front_page() && is_home() ) :
						?>
						<h1 class="site-title text-2xl font-bold"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php
					else :
						?>
						<p class="site-title text-2xl font-bold"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
						<?php
					endif;
					?>
				</div><!-- .site-branding -->

				<nav id="site-navigation" class="main-navigation hidden md:block">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
							'menu_class'     => 'flex space-x-4',
							'container'      => false,
						)
					);
					?>
				</nav><!-- #site-navigation -->

				<div class="md:hidden">
					<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
						<span class="dashicons dashicons-menu"></span>
					</button>
				</div>
			</div>
		</div>
	</header><!-- #masthead -->
	<div id="content" class="site-content flex-grow">
