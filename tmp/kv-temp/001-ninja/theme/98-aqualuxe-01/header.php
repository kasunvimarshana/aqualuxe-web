<?php
/**
 * Header template
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class('bg-slate-50 text-slate-900 dark:bg-slate-900 dark:text-slate-100'); ?>>
<a class="skip-link sr-only focus:not-sr-only" href="#content"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>
<header class="border-b border-slate-200/60 dark:border-slate-700/60">
	<div class="container mx-auto flex items-center justify-between px-4 py-3" role="banner">
		<div class="flex items-center gap-3">
			<?php if ( has_custom_logo() ) { the_custom_logo(); } else { ?>
				<a href="<?php echo esc_url( home_url('/') ); ?>" class="text-lg font-bold"><?php bloginfo('name'); ?></a>
			<?php } ?>
		</div>
		<nav class="hidden md:block" aria-label="Primary Navigation">
			<?php wp_nav_menu( [ 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'flex gap-6', 'fallback_cb' => '__return_false' ] ); ?>
		</nav>
		<button id="alx-dark-toggle" class="ml-4 rounded p-2 focus:outline-none focus-visible:ring" aria-pressed="false" aria-label="Toggle dark mode">🌙</button>
	</div>
</header>
<main id="content" tabindex="-1">
