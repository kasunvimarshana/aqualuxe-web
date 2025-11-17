<!DOCTYPE html>
<html <?php if (function_exists('language_attributes')) { call_user_func('language_attributes'); } else { echo 'lang="en"'; } ?>>
<head>
  <meta charset="<?php echo function_exists('get_bloginfo') ? (function_exists('esc_attr') ? call_user_func('esc_attr', call_user_func('get_bloginfo','charset')) : call_user_func('get_bloginfo','charset')) : 'utf-8'; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php if (function_exists('AquaLuxe\\TemplateTags\\meta_og_tags')) { AquaLuxe\TemplateTags\meta_og_tags(); } ?>
  <?php if (function_exists('AquaLuxe\\TemplateTags\\inline_critical_css')) { AquaLuxe\TemplateTags\inline_critical_css(); } ?>
  <?php if (function_exists('wp_head')) { call_user_func('wp_head'); } ?>
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
  <body <?php if (function_exists('body_class')) { call_user_func('body_class','antialiased bg-white text-slate-800 dark:bg-slate-950 dark:text-slate-100'); } else { echo 'class="antialiased bg-white text-slate-800 dark:bg-slate-950 dark:text-slate-100"'; } ?>>
    <?php if (function_exists('wp_body_open')) { wp_body_open(); } ?>
  <a class="skip-link sr-only focus:not-sr-only" href="#primary"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','Skip to content','aqualuxe') : 'Skip to content'; ?></a>
    <header class="site-header sticky top-0 z-40 bg-white/80 backdrop-blur dark:bg-slate-950/80 border-b border-slate-200 dark:border-slate-800" role="banner">
      <div class="container mx-auto px-4 h-16 flex items-center justify-between">
        <div class="brand"><?php if (function_exists('AquaLuxe\\TemplateTags\\the_site_logo')) { AquaLuxe\TemplateTags\the_site_logo(); } ?></div>
        <button id="mobileMenuToggle" class="md:hidden rounded p-2" aria-expanded="false" aria-controls="mobileMenu" aria-label="Toggle menu">☰</button>
        <nav class="primary-nav hidden md:flex items-center gap-4" aria-label="Primary">
          <?php if (function_exists('wp_nav_menu')) { call_user_func('wp_nav_menu', ['theme_location' => 'primary', 'container' => false, 'menu_class' => 'flex gap-6']); } ?>
        </nav>
        <div class="flex items-center gap-4">
          <?php
            $langOut = '';
            if (function_exists('shortcode_exists') && call_user_func('shortcode_exists','aqualuxe_language_switcher')) {
              $langOut = function_exists('do_shortcode') ? call_user_func('do_shortcode','[aqualuxe_language_switcher]') : '';
            }
            $currOut = '';
            if (function_exists('shortcode_exists') && call_user_func('shortcode_exists','aqualuxe_currency_switcher')) {
              $currOut = function_exists('do_shortcode') ? call_user_func('do_shortcode','[aqualuxe_currency_switcher]') : '';
            }
            echo $langOut . $currOut;
          ?>
        </div>
        <button id="darkModeToggle" class="rounded p-2 text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white" aria-pressed="false" aria-label="Toggle dark mode">🌙</button>
      </div>
    </header>
    <div id="mobileMenu" class="md:hidden fixed inset-x-0 top-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-lg" hidden>
      <nav aria-label="Mobile Primary" class="container mx-auto px-4 py-4">
        <?php if (function_exists('wp_nav_menu')) { call_user_func('wp_nav_menu', ['theme_location' => 'primary', 'container' => false, 'menu_class' => 'flex flex-col gap-3']); } ?>
      </nav>
    </div>
    <script>
      (function(){
        var btn = document.getElementById('darkModeToggle');
        if (!btn) return;
        function setPressed(on) { try { btn.setAttribute('aria-pressed', on ? 'true' : 'false'); } catch(e){} }
        function hasDark(){ return document.documentElement.classList.contains('dark'); }
        function persist(on){ try { localStorage.setItem('aqualuxe:theme', on ? 'dark' : 'light'); } catch(e){} }
        btn.addEventListener('click', function(){
          var on = !hasDark();
          document.documentElement.classList.toggle('dark', on);
          setPressed(on);
          persist(on);
        });
        // Initialize pressed state
        setPressed(hasDark());
      })();
    </script>
