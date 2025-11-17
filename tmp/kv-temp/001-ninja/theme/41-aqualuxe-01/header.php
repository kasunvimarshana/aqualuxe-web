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
<html <?php language_attributes(); ?> class="<?php echo aqualuxe_get_dark_mode_class(); ?>">
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

	<header id="masthead" class="site-header bg-white dark:bg-dark-900 shadow-sm transition-all duration-300">
		<div class="container mx-auto px-4">
			<div class="flex items-center justify-between py-4">
				<div class="flex items-center">
					<?php
					if ( has_custom_logo() ) :
						the_custom_logo();
					else :
					?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-2xl font-serif font-bold text-primary-600 dark:text-primary-400">
							<?php bloginfo( 'name' ); ?>
						</a>
					<?php endif; ?>
				</div>

				<nav id="site-navigation" class="main-navigation hidden lg:block">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
							'container'      => false,
							'menu_class'     => 'flex space-x-8',
							'fallback_cb'    => false,
						)
					);
					?>
				</nav><!-- #site-navigation -->

				<div class="flex items-center space-x-4">
					<?php get_template_part( 'template-parts/header/dark-mode-toggle' ); ?>
					
					<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
						<?php get_template_part( 'template-parts/header/cart-icon' ); ?>
						<?php get_template_part( 'template-parts/header/account-icon' ); ?>
					<?php endif; ?>
					
					<?php if ( aqualuxe_is_multilingual_active() ) : ?>
						<?php get_template_part( 'template-parts/header/language-switcher' ); ?>
					<?php endif; ?>
					
					<button id="mobile-menu-toggle" class="lg:hidden text-dark-900 dark:text-white" aria-controls="mobile-menu" aria-expanded="false">
						<span class="sr-only"><?php esc_html_e( 'Open main menu', 'aqualuxe' ); ?></span>
						<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
						</svg>
					</button>
				</div>
			</div>
		</div>
		
		<!-- Mobile menu, show/hide based on menu state. -->
		<div id="mobile-menu" class="lg:hidden hidden bg-white dark:bg-dark-900 shadow-lg absolute top-full left-0 right-0 z-50">
			<div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'mobile-menu-items',
						'container'      => false,
						'menu_class'     => 'mobile-menu-items',
						'fallback_cb'    => false,
						'depth'          => 1,
					)
				);
				?>
			</div>
		</div>
	</header><!-- #masthead -->

	<div id="content" class="site-content flex-grow">