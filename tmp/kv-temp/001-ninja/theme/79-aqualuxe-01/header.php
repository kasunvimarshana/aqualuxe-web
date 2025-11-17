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
<header class="border-b border-slate-200/60 dark:border-slate-700/60">
  <div class="container max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
    <div class="flex items-center gap-6">
      <?php \Aqualuxe\Inc\site_branding(); ?>
      <button class="text-sm px-3 py-1 rounded border hover-lift" data-aqlx-toggle-dark><?php esc_html_e('Toggle Dark', 'aqualuxe'); ?></button>
    </div>
    <div class="hidden md:block">
      <?php \Aqualuxe\Inc\primary_menu(); ?>
    </div>
  </div>
</header>
<main class="min-h-screen">
