<?php
/**
 * The header for our theme
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

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

	<header id="masthead" class="site-header bg-white dark:bg-gray-900 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 transition-colors duration-300">
		<div class="container mx-auto px-4">
			<!-- Top Bar -->
			<?php if ( aqualuxe_get_option( 'show_top_bar', true ) ) : ?>
			<div class="top-bar bg-primary-50 dark:bg-gray-800 border-b border-primary-100 dark:border-gray-700 py-2">
				<div class="flex items-center justify-between text-sm">
					<div class="flex items-center space-x-6 text-gray-600 dark:text-gray-400">
						<?php $phone = aqualuxe_get_option( 'contact_phone' ); ?>
						<?php if ( $phone ) : ?>
						<a href="tel:<?php echo esc_attr( $phone ); ?>" class="flex items-center space-x-2 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
							<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
								<path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
							</svg>
							<span><?php echo esc_html( $phone ); ?></span>
						</a>
						<?php endif; ?>
						
						<?php $email = aqualuxe_get_option( 'contact_email' ); ?>
						<?php if ( $email ) : ?>
						<a href="mailto:<?php echo esc_attr( $email ); ?>" class="flex items-center space-x-2 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
							<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
								<path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
								<path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
							</svg>
							<span><?php echo esc_html( $email ); ?></span>
						</a>
						<?php endif; ?>
					</div>
					
					<div class="flex items-center space-x-4">
						<?php echo aqualuxe_social_icons(); ?>
						<?php echo aqualuxe_dark_mode_toggle(); ?>
						
						<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
						<!-- Currency Switcher -->
						<div class="currency-switcher" x-data="{ open: false }">
							<button @click="open = !open" class="flex items-center space-x-1 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
								<span><?php echo get_woocommerce_currency_symbol(); ?></span>
								<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
									<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
								</svg>
							</button>
						</div>
						
						<!-- Language Switcher -->
						<div class="language-switcher" x-data="{ open: false }">
							<button @click="open = !open" class="flex items-center space-x-1 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
								<span><?php echo strtoupper( substr( get_locale(), 0, 2 ) ); ?></span>
								<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
									<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
								</svg>
							</button>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<!-- Main Header -->
			<div class="main-header py-4">
				<div class="flex items-center justify-between">
					<!-- Logo -->
					<div class="site-branding">
						<?php if ( has_custom_logo() ) : ?>
							<?php the_custom_logo(); ?>
						<?php else : ?>
							<h1 class="site-title text-2xl font-bold text-gray-900 dark:text-white">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
									<?php bloginfo( 'name' ); ?>
								</a>
							</h1>
							<?php $description = get_bloginfo( 'description', 'display' ); ?>
							<?php if ( $description || is_customize_preview() ) : ?>
								<p class="site-description text-sm text-gray-600 dark:text-gray-400 mt-1"><?php echo $description; ?></p>
							<?php endif; ?>
						<?php endif; ?>
					</div>

					<!-- Main Navigation -->
					<nav id="site-navigation" class="main-navigation hidden lg:block" aria-label="<?php esc_attr_e( 'Main navigation', 'aqualuxe' ); ?>">
						<?php
						wp_nav_menu( array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'menu_class'     => 'menu flex items-center space-x-8',
							'container'      => false,
							'fallback_cb'    => false,
						) );
						?>
					</nav>

					<!-- Header Actions -->
					<div class="header-actions flex items-center space-x-4">
						<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
						<!-- Search -->
						<button type="button" class="search-toggle p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200" aria-label="<?php esc_attr_e( 'Toggle search', 'aqualuxe' ); ?>">
							<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
							</svg>
						</button>

						<!-- Account -->
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="account-link p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200" aria-label="<?php esc_attr_e( 'My Account', 'aqualuxe' ); ?>">
							<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
							</svg>
						</a>

						<!-- Wishlist -->
						<button type="button" class="wishlist-toggle p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200 relative" aria-label="<?php esc_attr_e( 'Wishlist', 'aqualuxe' ); ?>">
							<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
							</svg>
							<span class="wishlist-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
						</button>

						<!-- Cart -->
						<div class="cart-wrapper">
							<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-toggle flex items-center space-x-2 p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
								<div class="relative">
									<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 2.5M7 13l2.5 2.5m6-7a2 2 0 100-4 2 2 0 000 4z"></path>
									</svg>
									<span class="cart-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
								</div>
								<div class="cart-total hidden sm:block">
									<span class="text-sm font-medium"><?php echo WC()->cart->get_cart_total(); ?></span>
								</div>
							</a>
						</div>
						<?php endif; ?>

						<!-- Mobile Menu Toggle -->
						<button type="button" class="mobile-menu-toggle lg:hidden p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200" aria-label="<?php esc_attr_e( 'Toggle mobile menu', 'aqualuxe' ); ?>">
							<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
							</svg>
						</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Search Overlay -->
		<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
		<div class="search-overlay fixed inset-0 bg-black bg-opacity-50 z-50 hidden" x-show="searchOpen" x-transition>
			<div class="search-popup bg-white dark:bg-gray-900 max-w-2xl mx-auto mt-20 rounded-lg shadow-xl">
				<div class="p-6">
					<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-form">
						<div class="relative">
							<input type="search" name="s" placeholder="<?php esc_attr_e( 'Search products...', 'aqualuxe' ); ?>" class="w-full pl-4 pr-12 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:text-white">
							<input type="hidden" name="post_type" value="product">
							<button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-primary-600 transition-colors">
								<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
								</svg>
							</button>
						</div>
					</form>
					<button type="button" class="search-close absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
						<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
						</svg>
					</button>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<!-- Mobile Menu -->
		<div class="mobile-menu lg:hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 hidden" x-show="mobileMenuOpen" x-transition>
			<div class="p-4">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'mobile',
					'menu_id'        => 'mobile-menu',
					'menu_class'     => 'mobile-menu-list space-y-2',
					'container'      => false,
					'fallback_cb'    => 'aqualuxe_mobile_menu_fallback',
				) );
				?>
			</div>
		</div>
	</header><!-- #masthead -->