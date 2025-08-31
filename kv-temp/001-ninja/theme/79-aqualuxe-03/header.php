<?php
/** Header */
?><!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
  <?php wp_head(); ?>
</head>
<body <?php body_class('bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100'); ?>>
<?php wp_body_open(); ?>
<a href="#main-content" class="skip-link"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>
<header class="border-b border-slate-200/60 dark:border-slate-700/60">
  <div class="container max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
    <div class="flex items-center gap-6">
      <?php \Aqualuxe\Inc\site_branding(); ?>
      <button class="text-sm px-3 py-1 rounded border hover-lift" data-aqlx-toggle-dark aria-label="<?php echo esc_attr__('Toggle dark mode','aqualuxe'); ?>"><?php esc_html_e('Toggle Dark', 'aqualuxe'); ?></button>
    </div>
    <div class="hidden md:flex items-center gap-6">
      <?php \Aqualuxe\Inc\primary_menu(); ?>
      <?php if (has_nav_menu('account')): ?>
        <nav class="text-sm">
          <?php wp_nav_menu(['theme_location'=>'account','container'=>false,'menu_class'=>'flex items-center gap-4 text-sm','depth'=>1]); ?>
        </nav>
      <?php elseif (class_exists('WooCommerce')): ?>
        <?php $acct = (int) get_option('woocommerce_myaccount_page_id'); if ($acct): ?>
          <nav class="text-sm"><a href="<?php echo esc_url(get_permalink($acct)); ?>"><?php esc_html_e('My Account','aqualuxe'); ?></a></nav>
        <?php endif; ?>
      <?php endif; ?>
      <div class="flex items-center gap-3">
        <?php echo do_shortcode('[aqlx_search_toggle]'); ?>
        <?php echo do_shortcode('[aqlx_currency_switcher]'); ?>
  <span class="text-sm">❤ <span class="aqlx-wishlist-count" aria-live="polite" aria-atomic="true" aria-label="<?php echo esc_attr__('Wishlist items count','aqualuxe'); ?>"><?php echo do_shortcode('[aqlx_wishlist_count]'); ?></span></span>
        <?php
          $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : null;
          $count = 0;
          if (isset($GLOBALS['woocommerce']) && is_object($GLOBALS['woocommerce']) && isset($GLOBALS['woocommerce']->cart)) {
              $count = (int) $GLOBALS['woocommerce']->cart->get_cart_contents_count();
          }
          if ($cart_url): ?>
            <a class="mini-cart text-sm" href="<?php echo esc_url($cart_url); ?>" aria-label="<?php echo esc_attr__('Cart','aqualuxe'); ?>"><span class="count" aria-live="polite" aria-atomic="true"><?php echo esc_html((string) $count); ?></span></a>
        <?php endif; ?>
      </div>
    </div>
    <div class="md:hidden">
      <button id="aqlx-mobile-toggle" class="px-3 py-2 border rounded" aria-expanded="false" aria-controls="aqlx-mobile-menu" aria-label="<?php echo esc_attr__('Toggle navigation','aqualuxe'); ?>">☰</button>
    </div>
  </div>
  <div id="aqlx-mobile-menu" class="md:hidden hidden border-t border-slate-200/60 dark:border-slate-700/60">
    <div class="px-4 py-3 space-y-4">
      <nav>
        <?php \Aqualuxe\Inc\primary_menu(); ?>
      </nav>
      <?php if (has_nav_menu('account')): ?>
        <nav>
          <?php wp_nav_menu(['theme_location'=>'account','container'=>false,'menu_class'=>'flex flex-col gap-2 text-sm','depth'=>1]); ?>
        </nav>
      <?php elseif (class_exists('WooCommerce')): ?>
        <?php $acct = (int) get_option('woocommerce_myaccount_page_id'); if ($acct): ?>
          <nav class="text-sm"><a href="<?php echo esc_url(get_permalink($acct)); ?>"><?php esc_html_e('My Account','aqualuxe'); ?></a></nav>
        <?php endif; ?>
      <?php endif; ?>
      <div class="flex items-center gap-4">
        <?php echo do_shortcode('[aqlx_search_toggle]'); ?>
        <?php echo do_shortcode('[aqlx_currency_switcher]'); ?>
  <span class="text-sm">❤ <span class="aqlx-wishlist-count" aria-live="polite" aria-atomic="true"><?php echo do_shortcode('[aqlx_wishlist_count]'); ?></span></span>
        <?php
          $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : null;
          $count = 0;
          if (isset($GLOBALS['woocommerce']) && is_object($GLOBALS['woocommerce']) && isset($GLOBALS['woocommerce']->cart)) {
              $count = (int) $GLOBALS['woocommerce']->cart->get_cart_contents_count();
          }
          if ($cart_url): ?>
            <a class="mini-cart text-sm" href="<?php echo esc_url($cart_url); ?>"><span class="count" aria-live="polite" aria-atomic="true"><?php echo esc_html((string) $count); ?></span></a>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <script>
  (function(){
    var b=document.getElementById('aqlx-mobile-toggle');
    var m=document.getElementById('aqlx-mobile-menu');
    if(!b||!m) return;
    b.addEventListener('click', function(){
      var open = m.classList.contains('hidden');
      m.classList.toggle('hidden');
      b.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  })();
  </script>
</header>
<main id="main-content" class="min-h-screen">
