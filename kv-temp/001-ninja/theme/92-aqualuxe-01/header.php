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

<body <?php body_class('bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200'); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site min-h-screen flex flex-col">
	<header id="masthead" class="site-header bg-white dark:bg-gray-800 shadow-md sticky top-0 z-50">
		<div class="container mx-auto px-4 sm:px-6 lg:px-8">
			<div class="flex justify-between items-center py-4">
				<div class="site-branding">
					<?php
					if ( has_custom_logo() ) {
						the_custom_logo();
					} elseif ( is_front_page() && is_home() ) {
						?>
						<h1 class="site-title text-2xl font-bold"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"><?php bloginfo( 'name' ); ?></a></h1>
						<?php
					} else {
						?>
						<p class="site-title text-2xl font-bold"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"><?php bloginfo( 'name' ); ?></a></p>
						<?php
					}
					$aqualuxe_description = get_bloginfo( 'description', 'display' );
					if ( $aqualuxe_description || is_customize_preview() ) :
						?>
						<p class="site-description text-sm text-gray-500 dark:text-gray-400"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
					<?php endif; ?>
				</div><!-- .site-branding -->

				<nav id="site-navigation" class="main-navigation hidden md:block">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
							'menu_class'     => 'flex space-x-4',
							'container'      => false,
							'fallback_cb'    => false,
							'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
							'depth'          => 2,
						)
					);
					?>
				</nav><!-- #site-navigation -->

				<div class="flex items-center">
					<?php
					if ( class_exists( 'WooCommerce' ) ) : ?>
						<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="relative text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 mr-4">
							<svg class="w-6 h-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
							<?php if(WC()->cart) : ?>
								<span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
							<?php endif; ?>
						</a>
					<?php endif;
					
					// Dark mode toggle will be rendered via the dark-mode module
					do_action('aqualuxe_header_right');
					?>
					<button id="menu-toggle" class="md:hidden ml-4 text-gray-500 dark:text-gray-400 focus:outline-none">
						<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
					</button>
				</div>
			</div>
		</div>
		<div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'mobile-primary-menu',
					'container'      => false,
					'menu_class'     => 'px-2 pt-2 pb-3 space-y-1 sm:px-3',
				)
			);
			?>
		</div>
	</header><!-- #masthead -->
	<div id="content" class="site-content flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8">
