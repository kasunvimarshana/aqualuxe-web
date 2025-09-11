<?php if (! defined('ABSPATH')) { exit; } ?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class('min-h-screen bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100'); ?>>
<a class="skip-link sr-only focus:not-sr-only" href="#content"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>
<header class="border-b border-slate-200 dark:border-slate-800">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <?php if (has_custom_logo()) { the_custom_logo(); } else { ?>
                <a class="font-bold text-lg" href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
            <?php } ?>
        </div>
        <nav aria-label="Primary" class="hidden md:block">
            <?php wp_nav_menu(['theme_location'=>'primary','container'=>false,'menu_class'=>'flex gap-6']); ?>
        </nav>
        <div class="flex items-center gap-4">
            <button id="darkToggle" class="rounded px-3 py-2 bg-slate-100 dark:bg-slate-800" aria-pressed="false">
                <span class="sr-only"><?php esc_html_e('Toggle dark mode', 'aqualuxe'); ?></span>🌙
            </button>
            <?php if (function_exists('wc_get_cart_url')) : ?>
                <a class="relative" href="<?php echo esc_url(wc_get_cart_url()); ?>" aria-label="<?php esc_attr_e('Cart', 'aqualuxe'); ?>">🛒</a>
            <?php endif; ?>
            <button class="md:hidden" id="navToggle" aria-expanded="false" aria-controls="mobileNav">☰</button>
        </div>
    </div>
    <div id="mobileNav" hidden class="md:hidden border-t border-slate-200 dark:border-slate-800">
        <?php wp_nav_menu(['theme_location'=>'primary','container'=>false,'menu_class'=>'p-4 space-y-2']); ?>
    </div>
    
</header>
<main id="content" tabindex="-1" class="min-h-[60vh]">