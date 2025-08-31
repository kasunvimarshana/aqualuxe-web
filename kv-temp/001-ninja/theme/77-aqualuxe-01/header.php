<?php
/** Header template */
?><!doctype html>
<html <?php language_attributes(); ?> class="scroll-smooth">
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php wp_head(); ?>
</head>
<body <?php body_class('bg-white text-slate-800 dark:bg-slate-950 dark:text-slate-100'); ?>>
<?php wp_body_open(); ?>
<a class="skip-link sr-only" href="#content"><?php esc_html_e('Skip to content','aqualuxe'); ?></a>
<header class="sticky top-0 z-30 backdrop-blur border-b border-slate-200/60 dark:border-slate-800/60 bg-white/70 dark:bg-slate-950/70">
  <div class="container mx-auto px-4 py-3 flex items-center gap-6">
    <?php aqualuxe_site_branding(); ?>
    <div class="ml-auto flex items-center gap-4">
      <?php aqualuxe_primary_nav(); ?>
      <?php if (aqualuxe_is_wc_active()): ?>
        <a href="<?php echo esc_url(function_exists('wc_get_cart_url') ? call_user_func('wc_get_cart_url') : home_url('/cart')); ?>" class="relative" aria-label="Cart">
          <span class="aqlx-cart-count absolute -top-2 -right-2 text-xs bg-sky-600 text-white rounded-full w-5 h-5 flex items-center justify-center">0</span>
          🛒
        </a>
      <?php endif; ?>
    </div>
  </div>
</header>
<main id="content">
<?php
/** Header template */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php wp_head(); ?>
</head>
<body <?php body_class('min-h-screen bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100'); ?>>
<?php wp_body_open(); ?>
<header class="aqlx-header sticky top-0 z-40 bg-white/90 dark:bg-slate-950/80 backdrop-blur border-b border-slate-200/60 dark:border-slate-800">
  <div class="container mx-auto px-4 py-3 flex items-center justify-between">
    <div class="flex items-center gap-4">
      <?php aqualuxe_site_branding(); ?>
    </div>
    <div class="hidden md:block">
      <?php aqualuxe_primary_nav(); ?>
    </div>
    <div class="flex items-center gap-4">
      <?php if (function_exists('wc_get_cart_url')): ?>
        <a class="relative" href="<?php echo esc_url(wc_get_cart_url()); ?>" aria-label="<?php esc_attr_e('Cart','aqualuxe'); ?>">
          <span class="aqlx-cart-count absolute -top-2 -right-3 bg-sky-600 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center">0</span>
          <svg width="24" height="24" fill="currentColor" aria-hidden="true"><path d="M7 4h-2l-1 2h2l3.6 7.59-1.35 2.45A1.99 1.99 0 0 0 9 19h10v-2H9.42a.25.25 0 0 1-.22-.37L9.9 14h7.45a2 2 0 0 0 1.8-1.1l3.58-6.49A1 1 0 0 0 22 5h-14z"/></svg>
        </a>
      <?php endif; ?>
    </div>
  </div>
</header>
<main id="content" class="grow">
