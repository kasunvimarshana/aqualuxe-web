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

	<?php if ( aqualuxe_get_option( 'enable_preconnect', true ) ) : ?>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php endif; ?>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

	<?php 
	// Page loader
	if ( aqualuxe_get_option( 'enable_page_loader', true ) ) {
		aqualuxe_page_loader();
	}
	?>

	<header id="masthead" class="site-header <?php echo aqualuxe_get_option( 'sticky_header', true ) ? 'sticky-header' : ''; ?>">
		<?php 
		// Header top bar
		if ( aqualuxe_get_option( 'enable_top_bar', true ) ) {
			get_template_part( 'template-parts/header/header', 'top-bar' );
		}
		
		// Header main
		get_template_part( 'template-parts/header/header', 'main' );
		
		// Header bottom bar
		if ( aqualuxe_get_option( 'enable_bottom_bar', true ) ) {
			get_template_part( 'template-parts/header/header', 'bottom-bar' );
		}
		?>
	</header><!-- #masthead -->

	<?php 
	// Mobile menu
	get_template_part( 'template-parts/header/mobile', 'menu' );
	?>

	<div id="content" class="site-content">