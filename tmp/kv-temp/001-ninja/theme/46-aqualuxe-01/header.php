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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$header_layout = get_theme_mod( 'aqualuxe_header_layout', 'standard' );
?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<?php aqualuxe_before_html(); ?>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site flex flex-col min-h-screen">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

	<?php aqualuxe_before_header(); ?>

	<?php get_template_part( 'template-parts/header/top-bar' ); ?>

	<header id="masthead" class="site-header">
		<?php 
		// Load the appropriate header layout
		get_template_part( 'template-parts/header/layout', $header_layout );
		
		// Load the mobile menu
		get_template_part( 'template-parts/navigation/mobile-menu' );
		?>
	</header><!-- #masthead -->

	<?php aqualuxe_after_header(); ?>

	<?php get_template_part( 'template-parts/header/breadcrumbs' ); ?>

	<?php aqualuxe_before_content(); ?>

	<div id="content" class="site-content">