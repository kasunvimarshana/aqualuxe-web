<?php
defined('ABSPATH') || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
</head>
<body <?php body_class('antialiased bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100'); ?> itemscope itemtype="<?php echo esc_attr(aqualuxe_schema_type()); ?>">
<?php wp_body_open(); ?>
<?php aqualuxe_do_header_top(); ?>
<header class="border-b border-slate-200 dark:border-slate-800">
  <div class="<?php echo esc_attr(aqlx_container_class()); ?> py-4 flex items-center justify-between">
    <div class="site-branding flex items-center gap-3">
      <?php aqualuxe_the_logo(); ?>
      <span class="sr-only"><?php bloginfo('name'); ?></span>
    </div>
    <nav class="hidden md:block">
      <?php aqualuxe_primary_menu(); ?>
    </nav>
    <button id="aqlx-dark-toggle" class="ml-4 p-2 rounded hover:bg-slate-100 dark:hover:bg-slate-800" aria-label="Toggle dark mode">
      <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M21.64 13a9 9 0 11-10.63-10.63 1 1 0 00-.88 1.51A7 7 0 1020.13 13.9a1 1 0 001.51-.88z"/></svg>
    </button>
  </div>
</header>
<?php aqualuxe_do_header_bottom(); ?>
<main id="content" class="min-h-screen">
