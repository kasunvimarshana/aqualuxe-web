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

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="top-bar bg-primary-900 text-white py-2">
			<div class="container mx-auto px-4 flex justify-between items-center">
				<div class="top-bar-left flex items-center space-x-4">
					<?php if ( is_active_sidebar( 'top-bar-left' ) ) : ?>
						<?php dynamic_sidebar( 'top-bar-left' ); ?>
					<?php else : ?>
						<div class="text-sm">
							<span class="mr-4">
								<i class="fas fa-phone-alt mr-1"></i> <?php echo esc_html( get_theme_mod( 'aqualuxe_phone_number', '+1 (555) 123-4567' ) ); ?>
							</span>
							<span>
								<i class="fas fa-envelope mr-1"></i> <?php echo esc_html( get_theme_mod( 'aqualuxe_email', 'info@aqualuxe.com' ) ); ?>
							</span>
						</div>
					<?php endif; ?>
				</div>
				<div class="top-bar-right flex items-center space-x-4">
					<?php if ( is_active_sidebar( 'top-bar-right' ) ) : ?>
						<?php dynamic_sidebar( 'top-bar-right' ); ?>
					<?php else : ?>
						<?php if ( has_nav_menu( 'top-bar' ) ) : ?>
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'top-bar',
									'menu_id'        => 'top-bar-menu',
									'container'      => false,
									'menu_class'     => 'flex space-x-4 text-sm',
									'depth'          => 1,
									'fallback_cb'    => false,
								)
							);
							?>
						<?php endif; ?>
						
						<?php 
						// Add action hook for top bar right content
						do_action( 'aqualuxe_top_bar_right' ); 
						?>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="main-header py-4">
			<div class="container mx-auto px-4">
				<div class="flex justify-between items-center">
					<div class="site-branding flex items-center">
						<?php
						if ( has_custom_logo() ) :
							the_custom_logo();
						else :
							?>
							<h1 class="site-title">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-primary-800 hover:text-primary-600 transition-colors text-2xl font-bold">
									<?php bloginfo( 'name' ); ?>
								</a>
							</h1>
							<?php
							$aqualuxe_description = get_bloginfo( 'description', 'display' );
							if ( $aqualuxe_description || is_customize_preview() ) :
								?>
								<p class="site-description ml-4 text-gray-600 text-sm"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
							<?php endif; ?>
						<?php endif; ?>
					</div><!-- .site-branding -->

					<div class="header-actions flex items-center space-x-6">
						<?php 
						// Add action hook for header actions
						do_action( 'aqualuxe_header_actions' ); 
						?>

						<?php if ( class_exists( 'WooCommerce' ) ) : ?>
							<div class="header-search relative">
								<button id="header-search-toggle" class="text-gray-700 hover:text-primary-600 transition-colors" aria-label="<?php esc_attr_e( 'Toggle Search', 'aqualuxe' ); ?>">
									<i class="fas fa-search text-xl"></i>
								</button>
								<div id="header-search-form" class="absolute right-0 top-full mt-2 w-80 bg-white shadow-lg rounded-md p-4 hidden z-50">
									<?php get_product_search_form(); ?>
								</div>
							</div>

							<div class="header-account">
								<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="text-gray-700 hover:text-primary-600 transition-colors">
									<i class="fas fa-user text-xl"></i>
								</a>
							</div>

							<div class="header-wishlist">
								<?php if ( function_exists( 'YITH_WCWL' ) ) : ?>
									<a href="<?php echo esc_url( YITH_WCWL()->get_wishlist_url() ); ?>" class="text-gray-700 hover:text-primary-600 transition-colors">
										<i class="fas fa-heart text-xl"></i>
										<span class="wishlist-count inline-flex items-center justify-center w-5 h-5 text-xs bg-primary-600 text-white rounded-full -mt-2 -ml-1">
											<?php echo esc_html( yith_wcwl_count_all_products() ); ?>
										</span>
									</a>
								<?php endif; ?>
							</div>

							<div class="header-cart relative">
								<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="text-gray-700 hover:text-primary-600 transition-colors">
									<i class="fas fa-shopping-bag text-xl"></i>
									<span class="cart-count inline-flex items-center justify-center w-5 h-5 text-xs bg-primary-600 text-white rounded-full -mt-2 -ml-1">
										<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
									</span>
								</a>
								<div class="mini-cart absolute right-0 top-full mt-2 w-80 bg-white shadow-lg rounded-md p-4 hidden z-50">
									<div class="widget_shopping_cart_content">
										<?php woocommerce_mini_cart(); ?>
									</div>
								</div>
							</div>
						<?php endif; ?>

						<div class="mobile-menu-toggle lg:hidden">
							<button id="mobile-menu-toggle" class="text-gray-700 hover:text-primary-600 transition-colors" aria-label="<?php esc_attr_e( 'Toggle Menu', 'aqualuxe' ); ?>">
								<i class="fas fa-bars text-2xl"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<nav id="site-navigation" class="main-navigation bg-primary-800 text-white py-3 hidden lg:block">
			<div class="container mx-auto px-4">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'container'      => false,
						'menu_class'     => 'flex space-x-6',
						'fallback_cb'    => false,
					)
				);
				?>
			</div>
		</nav><!-- #site-navigation -->

		<div id="mobile-menu" class="mobile-menu fixed inset-0 bg-white z-50 transform translate-x-full transition-transform duration-300 ease-in-out lg:hidden">
			<div class="mobile-menu-header flex justify-between items-center p-4 border-b">
				<div class="site-branding">
					<?php
					if ( has_custom_logo() ) :
						the_custom_logo();
					else :
						?>
						<h1 class="site-title">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-primary-800 hover:text-primary-600 transition-colors text-xl font-bold">
								<?php bloginfo( 'name' ); ?>
							</a>
						</h1>
					<?php endif; ?>
				</div>
				<button id="mobile-menu-close" class="text-gray-700 hover:text-primary-600 transition-colors" aria-label="<?php esc_attr_e( 'Close Menu', 'aqualuxe' ); ?>">
					<i class="fas fa-times text-2xl"></i>
				</button>
			</div>
			<div class="mobile-menu-content p-4 overflow-y-auto h-full">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'mobile',
						'menu_id'        => 'mobile-menu-nav',
						'container'      => false,
						'menu_class'     => 'mobile-menu-nav',
						'fallback_cb'    => false,
					)
				);
				?>
				
				<?php if ( has_nav_menu( 'top-bar' ) ) : ?>
					<div class="mobile-top-menu mt-6 pt-6 border-t">
						<h3 class="text-sm font-bold text-gray-500 mb-2"><?php esc_html_e( 'Quick Links', 'aqualuxe' ); ?></h3>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'top-bar',
								'menu_id'        => 'mobile-top-menu',
								'container'      => false,
								'menu_class'     => 'mobile-top-menu-nav',
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
						?>
					</div>
				<?php endif; ?>
				
				<div class="mobile-contact-info mt-6 pt-6 border-t">
					<h3 class="text-sm font-bold text-gray-500 mb-2"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></h3>
					<div class="flex flex-col space-y-2">
						<a href="tel:<?php echo esc_attr( get_theme_mod( 'aqualuxe_phone_number', '+1 (555) 123-4567' ) ); ?>" class="flex items-center text-gray-700">
							<i class="fas fa-phone-alt mr-2 text-primary-600"></i>
							<span><?php echo esc_html( get_theme_mod( 'aqualuxe_phone_number', '+1 (555) 123-4567' ) ); ?></span>
						</a>
						<a href="mailto:<?php echo esc_attr( get_theme_mod( 'aqualuxe_email', 'info@aqualuxe.com' ) ); ?>" class="flex items-center text-gray-700">
							<i class="fas fa-envelope mr-2 text-primary-600"></i>
							<span><?php echo esc_html( get_theme_mod( 'aqualuxe_email', 'info@aqualuxe.com' ) ); ?></span>
						</a>
						<div class="flex items-center text-gray-700">
							<i class="fas fa-map-marker-alt mr-2 text-primary-600"></i>
							<span><?php echo esc_html( get_theme_mod( 'aqualuxe_address', '123 Water Street, Oceanview, CA 90210' ) ); ?></span>
						</div>
					</div>
				</div>
				
				<div class="mobile-social-links mt-6 pt-6 border-t">
					<h3 class="text-sm font-bold text-gray-500 mb-2"><?php esc_html_e( 'Follow Us', 'aqualuxe' ); ?></h3>
					<div class="flex space-x-4">
						<?php if ( get_theme_mod( 'aqualuxe_facebook' ) ) : ?>
							<a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_facebook' ) ); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-700 hover:text-primary-600 transition-colors">
								<i class="fab fa-facebook-f text-xl"></i>
							</a>
						<?php endif; ?>
						
						<?php if ( get_theme_mod( 'aqualuxe_twitter' ) ) : ?>
							<a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_twitter' ) ); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-700 hover:text-primary-600 transition-colors">
								<i class="fab fa-twitter text-xl"></i>
							</a>
						<?php endif; ?>
						
						<?php if ( get_theme_mod( 'aqualuxe_instagram' ) ) : ?>
							<a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_instagram' ) ); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-700 hover:text-primary-600 transition-colors">
								<i class="fab fa-instagram text-xl"></i>
							</a>
						<?php endif; ?>
						
						<?php if ( get_theme_mod( 'aqualuxe_pinterest' ) ) : ?>
							<a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_pinterest' ) ); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-700 hover:text-primary-600 transition-colors">
								<i class="fab fa-pinterest-p text-xl"></i>
							</a>
						<?php endif; ?>
						
						<?php if ( get_theme_mod( 'aqualuxe_youtube' ) ) : ?>
							<a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_youtube' ) ); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-700 hover:text-primary-600 transition-colors">
								<i class="fab fa-youtube text-xl"></i>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</header><!-- #masthead -->

	<div id="content" class="site-content">