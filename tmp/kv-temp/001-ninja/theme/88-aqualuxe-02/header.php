<?php
/**
 * Theme header
 * @package aqualuxe
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class('min-h-screen bg-white text-slate-800 dark:bg-slate-900 dark:text-slate-100'); ?> itemscope itemtype="https://schema.org/WebPage">
<a class="skip-link sr-only focus:not-sr-only" href="#content"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>

<header class="border-b border-slate-200/60 dark:border-slate-700/60" role="banner" aria-label="Site header">
  <div class="container mx-auto px-4 py-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
      <?php if (function_exists('the_custom_logo') && has_custom_logo()) { the_custom_logo(); } else { ?>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="font-semibold text-xl">
          <?php bloginfo('name'); ?>
        </a>
      <?php } ?>
    </div>
    <nav class="hidden md:block" role="navigation" aria-label="Primary">
      <?php wp_nav_menu([
        'theme_location' => 'primary',
        'menu_class' => 'flex gap-6',
        'container' => false,
        'fallback_cb' => false,
      ]); ?>
    </nav>
    <div class="flex items-center gap-3">
      <form method="get" action="<?php echo esc_url(home_url('/')); ?>" role="search" class="hidden md:block">
        <label for="alx-search" class="sr-only"><?php esc_html_e('Search', 'aqualuxe'); ?></label>
        <input id="alx-search" name="s" type="search" class="border rounded px-3 py-1.5 bg-white/80 dark:bg-slate-800/60" placeholder="<?php esc_attr_e('Search...', 'aqualuxe'); ?>" />
      </form>
      <a href="<?php echo esc_url( wp_nonce_url(add_query_arg('alx_dark_toggle', '1'), 'alx_dark_toggle') ); ?>" class="text-sm underline decoration-dotted" aria-pressed="false" id="alx-dark-toggle">
        <?php esc_html_e('Toggle dark mode', 'aqualuxe'); ?>
      </a>
      <?php if (function_exists('is_woocommerce') && function_exists('wc_get_cart_url')): ?>
        <a class="relative" href="<?php echo esc_url(wc_get_cart_url()); ?>" aria-label="<?php esc_attr_e('Cart', 'aqualuxe'); ?>">
          <span class="inline-block">🛒</span>
        </a>
      <?php endif; ?>
    </div>
  </div>
</header>

<main id="content" class="min-h-[60vh]" tabindex="-1">
