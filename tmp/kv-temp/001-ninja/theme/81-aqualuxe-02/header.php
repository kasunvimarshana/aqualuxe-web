<?php if (!defined('ABSPATH')) exit; ?><!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php
  $canonical = '';
  if (function_exists('is_singular') && is_singular()) {
    $canonical = get_permalink();
  } else {
    global $wp; $req = (is_object($wp) && isset($wp->request)) ? $wp->request : '';
    $canonical = home_url('/' . ltrim($req, '/') . '/');
  }
?>
<link rel="canonical" href="<?php echo esc_url($canonical); ?>" />
<?php get_template_part('templates/parts','header-meta'); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class('bg-white text-slate-900 dark:bg-slate-900 dark:text-slate-100'); ?><?php echo aqualuxe_schema_attr('WebPage'); ?>>
<?php if (function_exists('wp_body_open')) { wp_body_open(); } ?>
<a class="skip-link sr-only focus:not-sr-only" href="#content"><?php esc_html_e('Skip to content','aqualuxe'); ?></a>
<header class="border-b border-slate-200/50 dark:border-slate-700/50">
  <div class="mx-auto px-4 <?php echo esc_attr(get_theme_mod('aqualuxe_container_width','max-w-7xl')); ?> flex items-center justify-between py-4">
    <div class="flex items-center gap-4">
      <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="flex items-center gap-2">
        <?php if (has_custom_logo()) { the_custom_logo(); } else { ?><span class="font-bold text-xl"><?php bloginfo('name'); ?></span><?php } ?>
      </a>
      <nav class="hidden md:block" aria-label="Primary">
        <?php wp_nav_menu(['theme_location'=>'primary','container'=>false,'menu_class'=>'flex gap-6']); ?>
      </nav>
    </div>
    <div class="flex items-center gap-4">
      <?php echo do_shortcode('[ax_language_switcher]'); ?>
  <?php if (aqualuxe_is_woocommerce_active()): ?>
  <a href="<?php echo esc_url( function_exists('wc_get_cart_url') ? call_user_func('wc_get_cart_url') : home_url('/cart/') ); ?>" class="relative" aria-label="Cart">
        <span class="ax-cart-count absolute -top-2 -right-2 bg-cyan-600 text-white rounded-full text-xs px-1">0</span>
        <span class="inline-block">🛒</span>
      </a>
      <?php endif; ?>
    </div>
  </div>
</header>
<main id="content" tabindex="-1">
