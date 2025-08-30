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
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>
	<a class="skip-link screen-reader-text" href="#site-navigation"><?php esc_html_e( 'Skip to navigation', 'aqualuxe' ); ?></a>
	<a class="skip-link screen-reader-text" href="#colophon"><?php esc_html_e( 'Skip to footer', 'aqualuxe' ); ?></a>

	<header id="masthead" class="site-header sticky top-0 z-50 bg-white dark:bg-dark-800 shadow-sm transition-colors duration-300" role="banner">
		<div class="container mx-auto px-4">
			<div class="flex items-center justify-between py-4">
				<div class="site-branding flex items-center">
					<?php
					if ( has_custom_logo() ) :
						the_custom_logo();
					else :
					?>
						<h1 class="site-title">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-xl sm:text-2xl font-serif font-bold text-dark-800 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
								<?php bloginfo( 'name' ); ?>
							</a>
						</h1>
					<?php endif; ?>
				</div><!-- .site-branding -->

				<nav id="site-navigation" class="main-navigation hidden lg:flex" role="navigation" aria-label="<?php esc_attr_e( 'Primary Navigation', 'aqualuxe' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'container'      => false,
							'menu_class'     => 'flex items-center space-x-6',
							'fallback_cb'    => false,
						)
					);
					?>
					
					<div class="flex items-center ml-6 space-x-4">
						<?php if ( function_exists( 'aqualuxe_language_switcher' ) ) : ?>
							<div class="language-switcher">
								<?php aqualuxe_language_switcher(); ?>
							</div>
						<?php endif; ?>
						
						<?php aqualuxe_dark_mode_toggle(); ?>
						
						<?php if ( class_exists( 'WooCommerce' ) ) : ?>
							<div class="header-cart relative">
								<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="text-dark-700 dark:text-white hover:text-primary-600 dark:hover:text-primary-400" aria-label="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
									<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
									</svg>
									<span class="cart-count absolute -top-2 -right-2 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" aria-hidden="true">
										<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
									</span>
									<span class="sr-only"><?php 
										/* translators: %d: number of items in cart */
										printf( esc_html__( '%d items in cart', 'aqualuxe' ), WC()->cart->get_cart_contents_count() ); 
									?></span>
								</a>
							</div>
							
							<div class="header-account">
								<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="text-dark-700 dark:text-white hover:text-primary-600 dark:hover:text-primary-400" aria-label="<?php esc_attr_e( 'My account', 'aqualuxe' ); ?>">
									<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
									</svg>
									<span class="sr-only"><?php esc_html_e( 'My account', 'aqualuxe' ); ?></span>
								</a>
							</div>
							
							<div class="header-search">
								<button id="search-toggle" class="text-dark-700 dark:text-white hover:text-primary-600 dark:hover:text-primary-400" aria-expanded="false" aria-controls="search-overlay" aria-label="<?php esc_attr_e( 'Open search', 'aqualuxe' ); ?>">
									<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
									</svg>
									<span class="sr-only"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
								</button>
							</div>
						<?php endif; ?>
					</div>
				</nav><!-- #site-navigation -->
				
				<div class="flex items-center space-x-4 lg:hidden">
					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<div class="header-cart relative">
							<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="text-dark-700 dark:text-white hover:text-primary-600 dark:hover:text-primary-400" aria-label="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
								</svg>
								<span class="cart-count absolute -top-2 -right-2 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" aria-hidden="true">
									<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
								</span>
							</a>
						</div>
					<?php endif; ?>
					
					<?php aqualuxe_dark_mode_toggle(); ?>
					
					<button id="mobile-menu-toggle" class="text-dark-700 dark:text-white" aria-controls="mobile-menu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle mobile menu', 'aqualuxe' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
						</svg>
						<span class="sr-only"><?php esc_html_e( 'Menu', 'aqualuxe' ); ?></span>
					</button>
				</div>
			</div>
		</div>
		
		<!-- Mobile Menu -->
		<div id="mobile-menu" class="lg:hidden bg-white dark:bg-dark-800 border-t border-gray-200 dark:border-dark-700 overflow-hidden max-h-0 transition-all duration-300" role="navigation" aria-label="<?php esc_attr_e( 'Mobile Navigation', 'aqualuxe' ); ?>">
			<div class="container mx-auto px-4 py-4">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'mobile',
						'menu_id'        => 'mobile-menu-list',
						'container'      => false,
						'menu_class'     => 'py-2 space-y-2',
						'fallback_cb'    => false,
					)
				);
				?>
				
				<div class="mt-6 pt-6 border-t border-gray-200 dark:border-dark-700">
					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<div class="flex flex-col space-y-4">
							<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="flex items-center text-dark-700 dark:text-white hover:text-primary-600 dark:hover:text-primary-400">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
								</svg>
								<?php esc_html_e( 'My Account', 'aqualuxe' ); ?>
							</a>
							
							<button id="search-toggle-mobile" class="flex items-center text-dark-700 dark:text-white hover:text-primary-600 dark:hover:text-primary-400" aria-expanded="false" aria-controls="search-overlay">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
								</svg>
								<?php esc_html_e( 'Search', 'aqualuxe' ); ?>
							</button>
							
							<?php if ( function_exists( 'aqualuxe_language_switcher' ) ) : ?>
								<div class="language-switcher-mobile mt-4">
									<?php aqualuxe_language_switcher(); ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		
		<!-- Search Overlay -->
		<div id="search-overlay" class="fixed inset-0 bg-dark-900 bg-opacity-80 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="search-title">
			<div class="container mx-auto px-4 h-full flex items-center justify-center">
				<div class="bg-white dark:bg-dark-800 p-4 sm:p-8 rounded-lg w-full max-w-2xl">
					<div class="flex justify-between items-center mb-6">
						<h2 id="search-title" class="text-xl sm:text-2xl font-serif"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></h2>
						<button id="search-close" class="text-dark-700 dark:text-white hover:text-primary-600 dark:hover:text-primary-400" aria-label="<?php esc_attr_e( 'Close search', 'aqualuxe' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
							</svg>
							<span class="sr-only"><?php esc_html_e( 'Close', 'aqualuxe' ); ?></span>
						</button>
					</div>
					<?php get_search_form(); ?>
				</div>
			</div>
		</div>
	</header><!-- #masthead -->