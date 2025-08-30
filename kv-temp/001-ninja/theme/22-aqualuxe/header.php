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
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site flex flex-col min-h-screen">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

	<header id="masthead" class="site-header bg-white dark:bg-dark-500 shadow-sm transition-all duration-300 sticky top-0 z-40">
		<div class="container-fluid max-w-screen-xl mx-auto">
			<?php get_template_part( 'template-parts/header/top-bar' ); ?>
			
			<div class="site-branding-navigation flex items-center justify-between py-4">
				<div class="site-branding flex items-center">
					<?php
					if ( has_custom_logo() ) :
						the_custom_logo();
					else :
					?>
						<h1 class="site-title">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-dark-500 dark:text-white hover:text-primary-500 dark:hover:text-primary-400 text-2xl font-bold no-underline">
								<?php bloginfo( 'name' ); ?>
							</a>
						</h1>
					<?php endif; ?>
				</div><!-- .site-branding -->

				<nav id="site-navigation" class="main-navigation hidden md:block">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'container'      => false,
							'menu_class'     => 'flex space-x-1',
							'fallback_cb'    => false,
						)
					);
					?>
				</nav><!-- #site-navigation -->

				<div class="header-actions flex items-center space-x-4">
					<?php get_template_part( 'template-parts/header/search-toggle' ); ?>
					
					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<?php get_template_part( 'template-parts/header/cart' ); ?>
						<?php get_template_part( 'template-parts/header/account' ); ?>
					<?php endif; ?>
					
					<?php get_template_part( 'template-parts/header/dark-mode-toggle' ); ?>
					
					<button id="mobile-menu-toggle" class="mobile-menu-toggle md:hidden" aria-controls="mobile-menu-container" aria-expanded="false">
						<span class="sr-only"><?php esc_html_e( 'Menu', 'aqualuxe' ); ?></span>
						<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
						</svg>
					</button>
				</div>
			</div>
		</div>
	</header><!-- #masthead -->

	<?php get_template_part( 'template-parts/header/mobile-menu' ); ?>
	<?php get_template_part( 'template-parts/header/search-modal' ); ?>

	<div id="content" class="site-content flex-grow">