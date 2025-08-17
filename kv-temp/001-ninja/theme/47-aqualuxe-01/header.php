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

	<?php do_action( 'aqualuxe_before_header' ); ?>

	<?php if ( aqualuxe_is_top_bar_enabled() ) : ?>
		<div id="top-bar" class="top-bar bg-primary-900 text-white py-2">
			<div class="container mx-auto px-4">
				<div class="flex justify-between items-center">
					<div class="top-bar-left flex items-center space-x-4">
						<?php if ( aqualuxe_get_theme_option( 'contact_phone' ) ) : ?>
							<div class="top-bar-phone">
								<a href="tel:<?php echo esc_attr( aqualuxe_get_theme_option( 'contact_phone' ) ); ?>" class="flex items-center text-sm">
									<?php aqualuxe_svg_icon( 'phone', array( 'class' => 'w-4 h-4 mr-1' ) ); ?>
									<?php echo esc_html( aqualuxe_get_theme_option( 'contact_phone' ) ); ?>
								</a>
							</div>
						<?php endif; ?>

						<?php if ( aqualuxe_get_theme_option( 'contact_email' ) ) : ?>
							<div class="top-bar-email">
								<a href="mailto:<?php echo esc_attr( aqualuxe_get_theme_option( 'contact_email' ) ); ?>" class="flex items-center text-sm">
									<?php aqualuxe_svg_icon( 'mail', array( 'class' => 'w-4 h-4 mr-1' ) ); ?>
									<?php echo esc_html( aqualuxe_get_theme_option( 'contact_email' ) ); ?>
								</a>
							</div>
						<?php endif; ?>
					</div>

					<div class="top-bar-right flex items-center space-x-4">
						<?php if ( aqualuxe_is_multilingual_enabled() ) : ?>
							<div class="language-switcher">
								<?php aqualuxe_language_switcher(); ?>
							</div>
						<?php endif; ?>

						<?php if ( aqualuxe_is_multi_currency_enabled() && aqualuxe_is_woocommerce_active() ) : ?>
							<div class="currency-switcher">
								<?php aqualuxe_currency_switcher(); ?>
							</div>
						<?php endif; ?>

						<?php if ( has_nav_menu( 'top-bar' ) ) : ?>
							<nav id="top-bar-navigation" class="top-bar-navigation">
								<?php
								wp_nav_menu(
									array(
										'theme_location' => 'top-bar',
										'menu_id'        => 'top-bar-menu',
										'container'      => false,
										'menu_class'     => 'flex items-center space-x-4 text-sm',
										'depth'          => 1,
										'fallback_cb'    => false,
									)
								);
								?>
							</nav>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<header id="masthead" class="site-header bg-white shadow-sm">
		<div class="container mx-auto px-4">
			<div class="flex justify-between items-center py-4">
				<div class="site-branding flex items-center">
					<?php
					if ( has_custom_logo() ) :
						the_custom_logo();
					else :
					?>
						<h1 class="site-title">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
						</h1>
					<?php endif; ?>
				</div><!-- .site-branding -->

				<nav id="site-navigation" class="main-navigation hidden lg:block">
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
				</nav><!-- #site-navigation -->

				<div class="header-actions flex items-center space-x-4">
					<?php if ( aqualuxe_is_dark_mode_enabled() ) : ?>
						<button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'aqualuxe' ); ?>">
							<?php aqualuxe_svg_icon( 'moon', array( 'class' => 'w-5 h-5 dark-icon' ) ); ?>
							<?php aqualuxe_svg_icon( 'sun', array( 'class' => 'w-5 h-5 light-icon' ) ); ?>
						</button>
					<?php endif; ?>

					<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
						<div class="header-search relative">
							<button id="search-toggle" class="search-toggle" aria-label="<?php esc_attr_e( 'Toggle search', 'aqualuxe' ); ?>">
								<?php aqualuxe_svg_icon( 'search', array( 'class' => 'w-5 h-5' ) ); ?>
							</button>
							<div id="header-search-form" class="header-search-form hidden absolute right-0 top-full mt-2 w-72 bg-white shadow-lg rounded-lg p-4 z-50">
								<?php get_product_search_form(); ?>
							</div>
						</div>

						<?php if ( function_exists( 'aqualuxe_header_account' ) ) : ?>
							<?php aqualuxe_header_account(); ?>
						<?php endif; ?>

						<?php if ( function_exists( 'aqualuxe_header_wishlist' ) ) : ?>
							<?php aqualuxe_header_wishlist(); ?>
						<?php endif; ?>

						<?php if ( function_exists( 'aqualuxe_header_cart' ) ) : ?>
							<?php aqualuxe_header_cart(); ?>
						<?php endif; ?>
					<?php endif; ?>

					<button id="mobile-menu-toggle" class="mobile-menu-toggle lg:hidden" aria-label="<?php esc_attr_e( 'Toggle mobile menu', 'aqualuxe' ); ?>">
						<?php aqualuxe_svg_icon( 'menu', array( 'class' => 'w-6 h-6' ) ); ?>
					</button>
				</div><!-- .header-actions -->
			</div>
		</div>

		<div id="mobile-menu" class="mobile-menu hidden lg:hidden">
			<div class="container mx-auto px-4 py-4">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'mobile',
						'menu_id'        => 'mobile-menu',
						'container'      => false,
						'menu_class'     => 'mobile-menu-items',
						'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
					)
				);
				?>
			</div>
		</div>
	</header><!-- #masthead -->

	<?php do_action( 'aqualuxe_after_header' ); ?>

	<?php if ( function_exists( 'aqualuxe_breadcrumbs' ) && ! is_front_page() ) : ?>
		<div class="breadcrumbs-container bg-gray-100 py-2">
			<div class="container mx-auto px-4">
				<?php aqualuxe_breadcrumbs(); ?>
			</div>
		</div>
	<?php endif; ?>

	<div id="content" class="site-content">
		<div class="container mx-auto px-4 py-8">
			<?php do_action( 'aqualuxe_before_main_content' ); ?>