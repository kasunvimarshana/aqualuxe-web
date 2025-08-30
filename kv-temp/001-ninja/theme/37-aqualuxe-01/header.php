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
<html <?php language_attributes(); ?> class="no-js <?php echo esc_attr( get_theme_mod( 'dark_mode_default', 'system' ) ); ?>-mode">
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

	<header id="masthead" class="site-header" role="banner">
		<div class="container">
			<div class="site-header-inner">
				<div class="site-branding">
					<?php
					if ( has_custom_logo() ) :
						the_custom_logo();
					else :
						?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php
						$aqualuxe_description = get_bloginfo( 'description', 'display' );
						if ( $aqualuxe_description || is_customize_preview() ) :
							?>
							<p class="site-description"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
						<?php endif; ?>
					<?php endif; ?>
				</div><!-- .site-branding -->

				<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Navigation', 'aqualuxe' ); ?>">
					<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
						<span class="menu-toggle-icon">
							<span class="menu-toggle-bar"></span>
							<span class="menu-toggle-bar"></span>
							<span class="menu-toggle-bar"></span>
						</span>
						<span class="menu-toggle-text"><?php esc_html_e( 'Menu', 'aqualuxe' ); ?></span>
					</button>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'container'      => false,
							'menu_class'     => 'primary-menu',
							'fallback_cb'    => false,
						)
					);
					?>
				</nav><!-- #site-navigation -->

				<div class="site-header-actions">
					<?php if ( get_theme_mod( 'enable_dark_mode', true ) ) : ?>
						<div class="dark-mode-toggle">
							<button type="button" class="dark-mode-toggle-button" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'aqualuxe' ); ?>">
								<span class="dark-mode-toggle-icon dark-mode-toggle-icon-light">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 18a6 6 0 1 1 0-12 6 6 0 0 1 0 12zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM11 1h2v3h-2V1zm0 19h2v3h-2v-3zM3.515 4.929l1.414-1.414L7.05 5.636 5.636 7.05 3.515 4.93zM16.95 18.364l1.414-1.414 2.121 2.121-1.414 1.414-2.121-2.121zm2.121-14.85l1.414 1.415-2.121 2.121-1.414-1.414 2.121-2.121zM5.636 16.95l1.414 1.414-2.121 2.121-1.414-1.414 2.121-2.121zM23 11v2h-3v-2h3zM4 11v2H1v-2h3z"/></svg>
								</span>
								<span class="dark-mode-toggle-icon dark-mode-toggle-icon-dark">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M10 7a7 7 0 0 0 12 4.9v.1c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2h.1A6.979 6.979 0 0 0 10 7zm-6 5a8 8 0 0 0 15.062 3.762A9 9 0 0 1 8.238 4.938 7.999 7.999 0 0 0 4 12z"/></svg>
								</span>
							</button>
						</div>
					<?php endif; ?>

					<div class="search-toggle">
						<button type="button" class="search-toggle-button" aria-label="<?php esc_attr_e( 'Toggle search', 'aqualuxe' ); ?>" aria-expanded="false" aria-controls="search-modal">
							<span class="search-toggle-icon">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M18.031 16.617l4.283 4.282-1.415 1.415-4.282-4.283A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9 9 4.032 9 9a8.96 8.96 0 0 1-1.969 5.617zm-2.006-.742A6.977 6.977 0 0 0 18 11c0-3.868-3.133-7-7-7-3.868 0-7 3.132-7 7 0 3.867 3.132 7 7 7a6.977 6.977 0 0 0 4.875-1.975l.15-.15z"/></svg>
							</span>
						</button>
					</div>

					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<div class="cart-toggle">
							<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-toggle-button" aria-label="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
								<span class="cart-toggle-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M4 16V4H2V2h3a1 1 0 0 1 1 1v12h12.438l2-8H8V5h13.72a1 1 0 0 1 .97 1.243l-2.5 10a1 1 0 0 1-.97.757H5a1 1 0 0 1-1-1zm2 7a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm12 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg>
								</span>
								<span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
							</a>
						</div>
					<?php elseif ( get_page_by_path( 'cart' ) ) : ?>
						<div class="cart-toggle">
							<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'cart' ) ) ); ?>" class="cart-toggle-button" aria-label="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
								<span class="cart-toggle-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M4 16V4H2V2h3a1 1 0 0 1 1 1v12h12.438l2-8H8V5h13.72a1 1 0 0 1 .97 1.243l-2.5 10a1 1 0 0 1-.97.757H5a1 1 0 0 1-1-1zm2 7a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm12 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg>
								</span>
							</a>
						</div>
					<?php endif; ?>

					<?php if ( class_exists( 'WooCommerce' ) || get_page_by_path( 'my-account' ) ) : ?>
						<div class="account-toggle">
							<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_account_endpoint_url( 'dashboard' ) : get_permalink( get_page_by_path( 'my-account' ) ) ); ?>" class="account-toggle-button" aria-label="<?php esc_attr_e( 'My account', 'aqualuxe' ); ?>">
								<span class="account-toggle-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M4 22a8 8 0 1 1 16 0h-2a6 6 0 1 0-12 0H4zm8-9c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/></svg>
								</span>
							</a>
						</div>
					<?php endif; ?>
				</div><!-- .site-header-actions -->
			</div><!-- .site-header-inner -->
		</div><!-- .container -->
	</header><!-- #masthead -->

	<?php do_action( 'aqualuxe_after_header' ); ?>

	<div id="content" class="site-content">
		<div class="container">
			<div class="content-wrapper">
				<?php do_action( 'aqualuxe_before_content' ); ?>