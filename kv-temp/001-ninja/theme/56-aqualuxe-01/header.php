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
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

	<header id="masthead" class="site-header">
		<?php
		/**
		 * Hook: aqualuxe_header.
		 *
		 * @hooked aqualuxe_header_before - 5
		 * @hooked aqualuxe_header_top - 10
		 * @hooked aqualuxe_header_main - 20
		 * @hooked aqualuxe_header_bottom - 30
		 * @hooked aqualuxe_header_after - 35
		 */
		do_action( 'aqualuxe_header' );
		?>
	</header><!-- #masthead -->

	<div id="content" class="site-content">
		<?php
		/**
		 * Hook: aqualuxe_before_main_content.
		 */
		do_action( 'aqualuxe_before_main_content' );
		?>