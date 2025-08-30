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

<body <?php body_class(); ?> <?php aqualuxe_body_schema(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="container mx-auto px-4">
			<div class="site-header-inner flex items-center justify-between py-4">
				<div class="site-branding">
					<?php aqualuxe_site_logo(); ?>
				</div><!-- .site-branding -->

				<nav id="site-navigation" class="main-navigation">
					<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
						<span class="menu-toggle-icon"></span>
						<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'aqualuxe' ); ?></span>
					</button>
					<?php aqualuxe_primary_navigation(); ?>
				</nav><!-- #site-navigation -->

				<div class="site-header-actions flex items-center">
					<?php aqualuxe_search_form(); ?>
					
					<?php aqualuxe_dark_mode_toggle(); ?>
					
					<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
						<?php aqualuxe_cart_icon(); ?>
						<?php aqualuxe_account_icon(); ?>
					<?php endif; ?>
				</div><!-- .site-header-actions -->
			</div><!-- .site-header-inner -->
		</div><!-- .container -->
	</header><!-- #masthead -->

	<?php if ( aqualuxe_is_woocommerce_active() && is_woocommerce() ) : ?>
		<div class="woocommerce-header">
			<div class="container mx-auto px-4">
				<?php aqualuxe_breadcrumbs(); ?>
				
				<?php if ( is_shop() || is_product_category() || is_product_tag() ) : ?>
					<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
				<?php endif; ?>
				
				<?php if ( is_shop() ) : ?>
					<?php aqualuxe_shop_navigation(); ?>
				<?php endif; ?>
			</div><!-- .container -->
		</div><!-- .woocommerce-header -->
	<?php endif; ?>

	<div id="content" class="site-content <?php echo aqualuxe_get_content_class(); ?>">
		<div class="container mx-auto px-4">