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

<body <?php body_class('bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 antialiased'); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site flex flex-col min-h-screen">
	<a class="skip-link sr-only focus:not-sr-only" href="#primary"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

	<?php
	// Top Bar
	if ( get_theme_mod( 'aqualuxe_enable_top_bar', true ) ) :
	?>
	<div id="top-bar" class="bg-primary-900 text-white py-2 text-sm">
		<div class="container mx-auto px-4 flex flex-wrap justify-between items-center">
			<div class="top-bar-left flex items-center space-x-4">
				<?php if ( get_theme_mod( 'aqualuxe_contact_phone' ) ) : ?>
				<div class="flex items-center">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
					</svg>
					<span><?php echo esc_html( get_theme_mod( 'aqualuxe_contact_phone' ) ); ?></span>
				</div>
				<?php endif; ?>
				
				<?php if ( get_theme_mod( 'aqualuxe_contact_email' ) ) : ?>
				<div class="flex items-center">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
					</svg>
					<a href="mailto:<?php echo esc_attr( get_theme_mod( 'aqualuxe_contact_email' ) ); ?>" class="hover:text-primary-200">
						<?php echo esc_html( get_theme_mod( 'aqualuxe_contact_email' ) ); ?>
					</a>
				</div>
				<?php endif; ?>
			</div>
			
			<div class="top-bar-right flex items-center space-x-4">
				<?php
				// Top Bar Menu
				if ( has_nav_menu( 'top-bar' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'top-bar',
							'menu_id'        => 'top-bar-menu',
							'container'      => false,
							'depth'          => 1,
							'menu_class'     => 'flex space-x-4',
							'link_before'    => '<span>',
							'link_after'     => '</span>',
							'fallback_cb'    => false,
						)
					);
				}
				?>
				
				<?php if ( get_theme_mod( 'aqualuxe_enable_social_icons', true ) ) : ?>
				<div class="social-icons flex items-center space-x-2">
					<?php if ( get_theme_mod( 'aqualuxe_social_facebook' ) ) : ?>
					<a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_social_facebook' ) ); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-primary-200">
						<span class="sr-only"><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></span>
						<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
							<path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
						</svg>
					</a>
					<?php endif; ?>
					
					<?php if ( get_theme_mod( 'aqualuxe_social_instagram' ) ) : ?>
					<a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_social_instagram' ) ); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-primary-200">
						<span class="sr-only"><?php esc_html_e( 'Instagram', 'aqualuxe' ); ?></span>
						<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
							<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
						</svg>
					</a>
					<?php endif; ?>
					
					<?php if ( get_theme_mod( 'aqualuxe_social_twitter' ) ) : ?>
					<a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_social_twitter' ) ); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-primary-200">
						<span class="sr-only"><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></span>
						<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
							<path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
						</svg>
					</a>
					<?php endif; ?>
					
					<?php if ( get_theme_mod( 'aqualuxe_social_youtube' ) ) : ?>
					<a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_social_youtube' ) ); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-primary-200">
						<span class="sr-only"><?php esc_html_e( 'YouTube', 'aqualuxe' ); ?></span>
						<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
							<path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
						</svg>
					</a>
					<?php endif; ?>
				</div>
				<?php endif; ?>
				
				<?php if ( get_theme_mod( 'aqualuxe_enable_dark_mode_toggle', true ) ) : ?>
				<button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="<?php esc_attr_e( 'Toggle Dark Mode', 'aqualuxe' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 dark-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
					</svg>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 light-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
					</svg>
				</button>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<header id="masthead" class="site-header bg-white dark:bg-gray-800 shadow-md sticky top-0 z-50">
		<div class="container mx-auto px-4">
			<div class="flex justify-between items-center py-4">
				<div class="site-branding flex items-center">
					<?php
					if ( has_custom_logo() ) :
						the_custom_logo();
					else :
					?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-2xl font-bold text-primary-600 dark:text-primary-400">
							<?php bloginfo( 'name' ); ?>
						</a>
						<?php
						$aqualuxe_description = get_bloginfo( 'description', 'display' );
						if ( $aqualuxe_description || is_customize_preview() ) :
						?>
							<p class="site-description ml-4 text-sm text-gray-600 dark:text-gray-400"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
						<?php endif; ?>
					<?php endif; ?>
				</div><!-- .site-branding -->

				<nav id="site-navigation" class="main-navigation hidden lg:block">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'container'      => false,
							'menu_class'     => 'flex space-x-6',
							'fallback_cb'    => false,
							'walker'         => new AquaLuxe_Walker_Nav_Menu(),
						)
					);
					?>
				</nav><!-- #site-navigation -->

				<div class="header-actions flex items-center space-x-4">
					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<div class="header-search relative" x-data="{ isOpen: false }">
						<button @click="isOpen = !isOpen" class="flex items-center text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 focus:outline-none">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
							</svg>
							<span class="sr-only"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
						</button>
						<div x-show="isOpen" @click.away="isOpen = false" class="absolute right-0 mt-2 w-72 bg-white dark:bg-gray-800 rounded-md shadow-lg p-4">
							<?php get_product_search_form(); ?>
						</div>
					</div>

					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="flex items-center text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
						</svg>
						<span class="sr-only"><?php esc_html_e( 'My Account', 'aqualuxe' ); ?></span>
					</a>

					<div class="header-cart relative" x-data="{ isOpen: false }">
						<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="flex items-center text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400" @mouseover="isOpen = true">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
							</svg>
							<span class="ml-1 text-sm font-medium">
								<?php echo WC()->cart->get_cart_contents_count(); ?>
							</span>
						</a>
						<div x-show="isOpen" @click.away="isOpen = false" @mouseleave="isOpen = false" class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-md shadow-lg p-4">
							<div class="widget_shopping_cart_content">
								<?php woocommerce_mini_cart(); ?>
							</div>
						</div>
					</div>
					<?php endif; ?>

					<button id="mobile-menu-toggle" class="lg:hidden flex items-center text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 focus:outline-none" aria-controls="mobile-menu" aria-expanded="false">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
						</svg>
						<span class="sr-only"><?php esc_html_e( 'Menu', 'aqualuxe' ); ?></span>
					</button>
				</div>
			</div>
		</div>

		<div id="mobile-menu" class="lg:hidden hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
			<div class="container mx-auto px-4 py-4">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'mobile',
						'menu_id'        => 'mobile-menu',
						'container'      => false,
						'menu_class'     => 'mobile-menu',
						'fallback_cb'    => false,
					)
				);
				?>
			</div>
		</div>
	</header><!-- #masthead -->

	<div id="content" class="site-content flex-grow">