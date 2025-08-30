<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class('antialiased'); ?>>
<a class="skip-link" href="#primary"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>
<header class="header-shadow sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur">
  <div class="container mx-auto px-4 py-3 flex items-center justify-between">
    <div class="flex items-center gap-4">
      <button data-aqlx-toggle-dark class="text-xl" aria-label="Toggle dark mode">🌙</button>
      <div class="site-brand"><?php \AquaLuxe\Core\Template_Tags::site_brand(); ?></div>
    </div>
    <nav class="hidden md:block" aria-label="Primary">
      <?php wp_nav_menu(['theme_location'=>'primary','container'=>false,'menu_class'=>'flex gap-6']); ?>
    </nav>
    <div class="flex items-center gap-3">
      <a class="btn-outline" href="<?php echo esc_url(home_url('/contact')); ?>"><?php esc_html_e('Contact', 'aqualuxe'); ?></a>
      <?php if (class_exists('WooCommerce')): ?>
        <a class="btn-primary" href="<?php echo esc_url(wc_get_cart_url()); ?>" aria-label="Cart">🛒</a>
      <?php endif; ?>
    </div>
  </div>
</header>
