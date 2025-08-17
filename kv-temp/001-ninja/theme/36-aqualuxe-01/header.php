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
		<div class="container mx-auto px-4">
			<div class="flex items-center justify-between py-4">
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
						<span class="menu-toggle-icon"></span>
						<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'aqualuxe' ); ?></span>
					</button>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
							'container'      => false,
							'menu_class'     => 'primary-menu',
							'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
						)
					);
					?>
				</nav><!-- #site-navigation -->

				<div class="header-actions">
					<?php
					// Search toggle
					if ( get_theme_mod( 'aqualuxe_header_search', true ) ) :
						?>
						<button class="search-toggle" aria-expanded="false">
							<span class="screen-reader-text"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
							<i class="fas fa-search" aria-hidden="true"></i>
						</button>
					<?php endif; ?>

					<?php
					// WooCommerce cart
					if ( function_exists( 'aqualuxe_woocommerce_header_cart' ) ) {
						aqualuxe_woocommerce_header_cart();
					}
					?>

					<?php
					// Account link
					if ( get_theme_mod( 'aqualuxe_header_account', true ) && function_exists( 'aqualuxe_is_woocommerce_active' ) && aqualuxe_is_woocommerce_active() ) :
						?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="header-account">
							<span class="screen-reader-text"><?php esc_html_e( 'My Account', 'aqualuxe' ); ?></span>
							<i class="fas fa-user" aria-hidden="true"></i>
						</a>
					<?php endif; ?>
				</div><!-- .header-actions -->
			</div>

			<?php
			// Header search form
			if ( get_theme_mod( 'aqualuxe_header_search', true ) ) :
				?>
				<div class="header-search-form hidden">
					<?php get_search_form(); ?>
				</div>
			<?php endif; ?>
		</div>
	</header><!-- #masthead -->

	<?php
	// Hero section for homepage
	if ( is_front_page() && ! is_home() && get_theme_mod( 'aqualuxe_hero_enable', true ) ) :
		get_template_part( 'templates/components/hero' );
	endif;
	?>

	<div id="content" class="site-content">
		<div class="container mx-auto px-4">
			<?php
			// Breadcrumbs
			if ( ! is_front_page() && get_theme_mod( 'aqualuxe_breadcrumbs', true ) ) :
				if ( function_exists( 'aqualuxe_breadcrumbs' ) ) {
					aqualuxe_breadcrumbs();
				}
			endif;
			?>