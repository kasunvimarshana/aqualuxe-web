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
        <?php if (has_nav_menu('primary')) {
          wp_nav_menu([
            'theme_location'=>'primary',
            'container'=>false,
            'menu_class'=>'flex gap-6',
          ]);
        } else {
          echo '<ul class="flex gap-6">';
          wp_list_pages(['title_li'=>'']);
          echo '</ul>';
        } ?>
      </nav>
    </div>
    <div class="flex items-center gap-4">
  <button id="ax-nav-toggle" class="md:hidden inline-flex items-center justify-center rounded border border-slate-300/70 px-3 py-2 text-sm hover:bg-slate-50 dark:border-slate-700/70 dark:hover:bg-slate-800" aria-label="Open navigation" aria-controls="ax-mobile-menu" aria-expanded="false">
        <span aria-hidden="true">☰</span>
      </button>
  <?php echo do_shortcode('[ax_language_switcher]'); ?>
  <?php if (shortcode_exists('ax_currency_switcher')) echo do_shortcode('[ax_currency_switcher]'); ?>
  <?php if (aqualuxe_is_woocommerce_active()): ?>
  <a href="<?php echo esc_url( function_exists('wc_get_cart_url') ? call_user_func('wc_get_cart_url') : home_url('/cart/') ); ?>" class="relative" aria-label="Cart">
        <span class="ax-cart-count absolute -top-2 -right-2 bg-cyan-600 text-white rounded-full text-xs px-1">0</span>
        <span class="inline-block">🛒</span>
      </a>
      <?php endif; ?>
    </div>
  </div>
  <!-- Mobile navigation -->
  <div id="ax-mobile-menu" class="md:hidden hidden ax-collapsed border-t border-slate-200/70 dark:border-slate-700/60 bg-white dark:bg-slate-900" aria-hidden="true" inert style="display:none">
    <div class="mx-auto px-4 <?php echo esc_attr(get_theme_mod('aqualuxe_container_width','max-w-7xl')); ?> py-3">
      <nav aria-label="Primary mobile">
        <?php if (has_nav_menu('primary')) {
          wp_nav_menu([
            'theme_location'=>'primary',
            'container'=>false,
            'menu_class'=>'flex flex-col gap-1',
          ]);
        } else {
          echo '<ul class="flex flex-col gap-1">';
          wp_list_pages(['title_li'=>'']);
          echo '</ul>';
        } ?>
      </nav>
    </div>
  </div>
</header>
<script>
// Accessible mobile nav toggle (no dependency)
(function(){
  var btn = document.getElementById('ax-nav-toggle');
  var menu = document.getElementById('ax-mobile-menu');
  if(!btn || !menu) return;
  var prefersReduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var closeTimer = null;
  function setBtn(open){
    if(open){ btn.setAttribute('aria-label','Close navigation'); btn.innerHTML = '<span aria-hidden="true">✕</span>'; }
    else { btn.setAttribute('aria-label','Open navigation'); btn.innerHTML = '<span aria-hidden="true">☰</span>'; }
  }
  function close(){
    if(closeTimer){ clearTimeout(closeTimer); closeTimer = null; }
    menu.classList.add('ax-anim');
    menu.classList.remove('ax-expanded');
    menu.classList.add('ax-collapsed');
    btn.setAttribute('aria-expanded','false');
    menu.setAttribute('aria-hidden','true');
    if('inert' in menu) menu.inert = true; else menu.setAttribute('inert','');
  // Hide immediately to avoid any lingering visibility
  menu.classList.add('hidden');
  menu.style.display = 'none';
    setBtn(false);
  }
  function open(){
    if(closeTimer){ clearTimeout(closeTimer); closeTimer = null; }
  menu.classList.remove('hidden');
  menu.style.display = 'block';
    menu.classList.add('ax-anim');
    // force reflow so transition applies when toggling classes
    void menu.offsetHeight;
    menu.classList.remove('ax-collapsed');
    menu.classList.add('ax-expanded');
    btn.setAttribute('aria-expanded','true');
    menu.setAttribute('aria-hidden','false');
    if('inert' in menu) menu.inert = false; else menu.removeAttribute('inert');
    setBtn(true);
  }
  btn.addEventListener('click', function(){
    var isOpen = btn.getAttribute('aria-expanded') === 'true';
    if(isOpen) { close(); }
    else { open(); }
  });
  // Close on link click
  menu.addEventListener('click', function(e){ if(e.target.closest('a')) close(); });
  // Close on Escape
  document.addEventListener('keydown', function(e){ if(e.key === 'Escape') { close(); btn.focus(); } });
  // Close when resizing to desktop
  window.addEventListener('resize', function(){ if(window.innerWidth >= 768) { close(); } });

  // Enhance mobile submenus with toggle buttons
  function setupSubmenus(){
    var items = menu.querySelectorAll('.menu-item-has-children');
    items.forEach(function(item){
      if(item.querySelector('.ax-sub-toggle')) return; // already set
      var link = item.querySelector(':scope > a');
      var sub = item.querySelector(':scope > .sub-menu');
      if(!sub) return;
      var t = document.createElement('button');
      t.type = 'button'; t.className = 'ax-sub-toggle'; t.setAttribute('aria-expanded','false'); t.setAttribute('aria-label','Expand submenu');
      t.innerHTML = '<span aria-hidden="true">▾</span>';
      item.classList.remove('ax-sub-open');
      link && link.after(t);
      t.addEventListener('click', function(){
        var open = item.classList.toggle('ax-sub-open');
        t.setAttribute('aria-expanded', open ? 'true':'false');
        t.setAttribute('aria-label', open ? 'Collapse submenu':'Expand submenu');
      });
    });
  }
  setupSubmenus();
})();
</script>
<main id="content" tabindex="-1">
