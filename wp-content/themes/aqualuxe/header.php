<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if (function_exists('AquaLuxe\\TemplateTags\\meta_og_tags')) { AquaLuxe\TemplateTags\meta_og_tags(); } ?>
    <?php wp_head(); ?>
    <script>
      // Respect user dark mode preference early
      (function(){
        try {
          var pref = localStorage.getItem('aqualuxe:theme');
          var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
          if (pref === 'dark' || (!pref && prefersDark)) {
            document.documentElement.classList.add('dark');
          }
        } catch (e) {}
      })();
    </script>
  </head>
  <body <?php body_class('antialiased bg-white text-slate-800 dark:bg-slate-950 dark:text-slate-100'); ?>>
    <a class="skip-link sr-only focus:not-sr-only" href="#primary"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>
    <header class="site-header sticky top-0 z-40 bg-white/80 backdrop-blur dark:bg-slate-950/80 border-b border-slate-200 dark:border-slate-800" role="banner">
      <div class="container mx-auto px-4 h-16 flex items-center justify-between">
        <div class="brand"><?php if (function_exists('AquaLuxe\\TemplateTags\\the_site_logo')) { AquaLuxe\TemplateTags\the_site_logo(); } ?></div>
        <nav class="primary-nav" aria-label="Primary">
          <?php wp_nav_menu(['theme_location' => 'primary', 'container' => false, 'menu_class' => 'flex gap-6']); ?>
        </nav>
        <button id="darkModeToggle" class="rounded p-2 text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white" aria-pressed="false" aria-label="Toggle dark mode">🌙</button>
      </div>
    </header>
