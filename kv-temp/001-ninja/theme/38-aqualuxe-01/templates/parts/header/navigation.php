<?php
/**
 * Template part for displaying the header navigation
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get header style
$header_style = get_theme_mod( 'aqualuxe_header_style', 'standard' );
$sticky_header = get_theme_mod( 'aqualuxe_sticky_header', true );

// Set header classes
$header_classes = array( 'site-header', 'header-style-' . $header_style );
if ( $sticky_header ) {
	$header_classes[] = 'sticky-header';
}
if ( is_front_page() && 'transparent' === $header_style ) {
	$header_classes[] = 'transparent-header';
}
?>

<header id="masthead" class="<?php echo esc_attr( implode( ' ', $header_classes ) ); ?>">
	<?php get_template_part( 'templates/parts/header/topbar' ); ?>

	<div class="main-header">
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

				<nav id="site-navigation" class="main-navigation">
					<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
						<span class="menu-toggle-icon">
							<span class="toggle-line"></span>
							<span class="toggle-line"></span>
							<span class="toggle-line"></span>
						</span>
						<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'aqualuxe' ); ?></span>
					</button>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'container'      => false,
							'menu_class'     => 'primary-menu',
							'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
						)
					);
					?>
				</nav><!-- #site-navigation -->

				<div class="header-actions">
					<?php if ( get_theme_mod( 'aqualuxe_header_search', true ) ) : ?>
						<button id="search-toggle" class="search-toggle" aria-expanded="false" aria-controls="search-container">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z" clip-rule="evenodd" /></svg>
							<span class="screen-reader-text"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
						</button>
					<?php endif; ?>

					<?php if ( get_theme_mod( 'aqualuxe_enable_dark_mode', true ) ) : ?>
						<button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'aqualuxe' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="dark-mode-icon"><path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z" clip-rule="evenodd" /></svg>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="light-mode-icon"><path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z" /></svg>
						</button>
					<?php endif; ?>

					<?php if ( class_exists( 'WooCommerce' ) && get_theme_mod( 'aqualuxe_header_cart', true ) ) : ?>
						<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-toggle">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z" /></svg>
							<span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
							<span class="screen-reader-text"><?php esc_html_e( 'View your shopping cart', 'aqualuxe' ); ?></span>
						</a>
					<?php endif; ?>
				</div><!-- .header-actions -->
			</div><!-- .site-header-inner -->
		</div><!-- .container -->
	</div><!-- .main-header -->

	<?php if ( get_theme_mod( 'aqualuxe_header_search', true ) ) : ?>
		<div id="search-container" class="search-container" aria-hidden="true">
			<div class="container">
				<div class="search-container-inner">
					<?php get_search_form(); ?>
					<button id="search-close" class="search-close" aria-label="<?php esc_attr_e( 'Close search', 'aqualuxe' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg>
					</button>
				</div>
			</div>
		</div>
	<?php endif; ?>
</header><!-- #masthead -->