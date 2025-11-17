<?php
/** Header template */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js" >
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>
<header class="site-header" role="banner">
	<div class="wrap flex items-center justify-between p-4">
		<div class="logo">
			<?php if ( has_custom_logo() ) { the_custom_logo(); } else { ?>
			<a href="<?php echo esc_url( home_url('/') ); ?>" class="text-xl font-bold"><?php bloginfo('name'); ?></a>
			<?php } ?>
		</div>
		<nav class="primary-nav" aria-label="<?php esc_attr_e( 'Primary Menu', 'aqualuxe' ); ?>">
			<?php wp_nav_menu( [ 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => '__return_empty_string' ] ); ?>
		</nav>
	</div>
</header>
<main id="main" class="site-main" tabindex="-1">
