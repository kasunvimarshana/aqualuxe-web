<?php use function AquaLuxe\attr; ?>
<!doctype html>
<html <?php language_attributes(); ?> class="scroll-smooth">
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class('bg-white text-gray-900 dark:bg-luxe dark:text-gray-100'); ?> itemscope itemtype="https://schema.org/WebPage">
<?php wp_body_open(); ?>
<a class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50 bg-white text-black dark:bg-gray-900 dark:text-white px-3 py-2 rounded" href="#content"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>
<header class="navbar">
  <div class="container mx-auto px-4 flex items-center justify-between h-16">
    <div class="flex items-center gap-3">
      <button class="md:hidden" aria-controls="primary-menu" aria-expanded="false" data-nav-toggle>
        <span class="sr-only"><?php esc_html_e('Toggle navigation', 'aqualuxe'); ?></span>
        <svg width="24" height="24" fill="currentColor" aria-hidden="true"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
      </button>
      <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center gap-2">
        <?php if (has_custom_logo()) { the_custom_logo(); } else { ?>
          <span class="font-semibold text-lg">AquaLuxe</span>
        <?php } ?>
      </a>
    </div>
  <nav id="primary-menu" class="hidden md:block" aria-label="Primary">
      <?php
        wp_nav_menu([
          'theme_location' => 'primary',
          'container' => false,
          'menu_class' => 'flex gap-6',
          'fallback_cb' => function(){ echo '<ul class="flex gap-6"><li><a href="' . esc_url(home_url('/')) . '">Home</a></li></ul>'; },
        ]);
      ?>
    </nav>
    <div class="flex items-center gap-3">
      <?php do_action('aqualuxe/header/actions'); ?>
      <button class="rounded-md border border-gray-300/50 px-2 py-1" data-theme-toggle aria-label="Toggle dark mode">
        <svg class="block dark:hidden" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M9 1v2M9 15v2M1 9h2M15 9h2M3.5 3.5l1.5 1.5M13 13l1.5 1.5M13 5l1.5-1.5M5 13L3.5 14.5"/></svg>
        <svg class="hidden dark:block" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M12.5 9.5a5.5 5.5 0 01-5.5 5.5 5.5 5.5 0 005.5-11a5.5 5.5 0 010 5.5z"/></svg>
      </button>
      <?php if (class_exists('WooCommerce')): ?>
        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="relative" aria-label="View cart">
          <svg width="22" height="22" fill="currentColor" aria-hidden="true"><path d="M6 6h12l-1.5 8h-9z"/></svg>
          <span class="sr-only"><?php esc_html_e('Cart', 'aqualuxe'); ?></span>
        </a>
      <?php endif; ?>
    </div>
  </div>
</header>
<?php do_action('aqualuxe/after_header'); ?>
