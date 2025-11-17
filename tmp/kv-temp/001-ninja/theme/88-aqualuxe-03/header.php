<?php
/** Header template */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'bg-white text-slate-800 antialiased' ); ?>>
<a class="skip-link sr-only focus:not-sr-only" href="#content"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>
<header class="border-b">
    <div class="container mx-auto max-w-screen-xl px-4 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <?php if ( has_custom_logo() ) { the_custom_logo(); } else { ?>
                <a class="font-semibold text-xl" href="<?php echo esc_url( home_url('/') ); ?>">AquaLuxe</a>
            <?php } ?>
        </div>
        <nav aria-label="Primary" class="hidden md:block">
            <?php wp_nav_menu( [ 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'flex gap-6' ] ); ?>
        </nav>
    </div>
</header>
<main id="content" class="min-h-screen">
