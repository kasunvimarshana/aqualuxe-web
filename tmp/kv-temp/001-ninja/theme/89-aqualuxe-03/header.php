<?php
/**
 * Header template
 * @package AquaLuxe
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="theme-color" content="#0ea5e9" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<a class="alx-skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>
<?php do_action( 'aqualuxe_before_header' ); ?>
<header id="masthead" class="site-header">
	<div class="alx-container flex items-center justify-between py-3">
		<div class="site-branding">
			<?php if ( has_custom_logo() ) { the_custom_logo(); } else { ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-xl font-bold" aria-label="<?php bloginfo( 'name' ); ?>">
					<?php bloginfo( 'name' ); ?>
				</a>
			<?php } ?>
		</div>
		<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary', 'aqualuxe' ); ?>">
			<?php
			wp_nav_menu( [
				'theme_location' => 'primary',
				'menu_class'     => 'alx-menu flex gap-4',
				'container'      => false,
				'fallback_cb'    => '__return_empty_string',
			] );
			?>
		</nav>
		<button id="alx-dark-toggle" class="px-3 py-1 rounded border" aria-pressed="false" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'aqualuxe' ); ?>">🌗</button>
	</div>
</header>
<?php do_action( 'aqualuxe_after_header' ); ?>
<main id="main" class="site-main" tabindex="-1">
<?php do_action( 'aqualuxe_before_main' ); ?>
