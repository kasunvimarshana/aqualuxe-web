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

	<header id="masthead" class="site-header <?php echo esc_attr( aqualuxe_is_sticky_header() ? 'sticky-header' : '' ); ?>">
		<div class="<?php echo esc_attr( aqualuxe_get_container_class() ); ?>">
			<div class="header-inner">
				<?php aqualuxe_site_branding(); ?>

				<?php aqualuxe_primary_navigation(); ?>

				<?php aqualuxe_header_actions(); ?>
			</div><!-- .header-inner -->

			<?php aqualuxe_mobile_navigation(); ?>
		</div><!-- .container -->

		<?php aqualuxe_header_image(); ?>
	</header><!-- #masthead -->

	<?php if ( ! is_front_page() || ! is_home() ) : ?>
		<div class="page-header">
			<div class="<?php echo esc_attr( aqualuxe_get_container_class() ); ?>">
				<h1 class="page-title"><?php aqualuxe_page_title(); ?></h1>
				<?php if ( aqualuxe_get_page_subtitle() ) : ?>
					<div class="page-subtitle"><?php aqualuxe_page_subtitle(); ?></div>
				<?php endif; ?>
				<?php aqualuxe_breadcrumbs(); ?>
			</div>
		</div><!-- .page-header -->
	<?php endif; ?>

	<div id="content" class="site-content">
		<div class="<?php echo esc_attr( aqualuxe_get_container_class() ); ?>">
			<div class="<?php echo esc_attr( aqualuxe_get_content_class() ); ?>">