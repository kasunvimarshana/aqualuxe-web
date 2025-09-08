<?php if (!defined('ABSPATH')) { exit; } ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php wp_head(); ?>
</head>
<body <?php body_class('bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100'); ?> itemscope itemtype="https://schema.org/WebPage">
<a class="skip_link" href="#main"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>
<header class="site_header sticky top-0 z-50 backdrop-blur bg-white/80 dark:bg-slate-900/70 border-b border-slate-200 dark:border-slate-800" role="banner">
  <div class="container mx-auto px-4 h-14 flex items-center justify-between">
    <div class="flex items-center gap-3">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="logo font-bold text-lg" aria-label="<?php bloginfo('name'); ?>">
        <?php if (has_custom_logo()) { the_custom_logo(); } else { bloginfo('name'); } ?>
      </a>
      <span class="sr-only"><?php bloginfo('description'); ?></span>
    </div>
    <nav class="primary_nav hidden md:block" aria-label="Primary">
      <?php wp_nav_menu(['theme_location' => 'primary','container' => false,'menu_class' => 'flex items-center gap-6']); ?>
    </nav>
    <div class="flex items-center gap-2">
      <button id="dark_mode_toggle" class="rounded p-2 hover:bg-slate-100 dark:hover:bg-slate-800" aria-pressed="false" aria-label="Toggle dark mode">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
      </button>
      <button class="md:hidden" id="mobile_menu_toggle" aria-expanded="false" aria-controls="mobile_menu" aria-label="Toggle menu">
        <span class="block w-6 h-0.5 bg-current mb-1"></span>
        <span class="block w-6 h-0.5 bg-current mb-1"></span>
        <span class="block w-6 h-0.5 bg-current"></span>
      </button>
    </div>
  </div>
  <nav id="mobile_menu" class="md:hidden hidden border-t border-slate-200 dark:border-slate-800" aria-label="Mobile">
    <?php wp_nav_menu(['theme_location' => 'primary','container' => false,'menu_class' => 'flex flex-col p-4 gap-3']); ?>
  </nav>
</header>
<div class="site_wrapper">
