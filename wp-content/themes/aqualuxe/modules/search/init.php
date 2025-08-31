<?php
declare(strict_types=1);

namespace Aqualuxe\Modules\Search;

// Simple accessible search overlay with a shortcode trigger [aqlx_search_toggle]

add_shortcode('aqlx_search_toggle', function($atts){
  $label = esc_html__('Search', 'aqualuxe');
  return '<button type="button" class="text-sm px-3 py-1 rounded border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-primary/50" data-aqlx-search-toggle aria-haspopup="dialog" aria-expanded="false">' . $label . '</button>';
});

add_action('wp_footer', function(){
    ?>
    <div id="aqlx-search-overlay" class="hidden fixed inset-0 z-50" aria-hidden="true">
      <div class="absolute inset-0 bg-black/60" data-aqlx-search-close></div>
      <div role="dialog" aria-modal="true" aria-label="<?php echo esc_attr__('Search', 'aqualuxe'); ?>" class="relative max-w-2xl mx-auto mt-24 bg-white dark:bg-slate-900 rounded shadow-lg p-4">
        <button class="absolute top-2 right-2 p-2" aria-label="<?php echo esc_attr__('Close', 'aqualuxe'); ?>" data-aqlx-search-close>✕</button>
        <?php get_search_form(); ?>
      </div>
    </div>
    <script>
    (function(){
      var overlay=document.getElementById('aqlx-search-overlay'); if(!overlay) return;
      var panel=overlay.querySelector('[role="dialog"]');
      var lastFocus;
      function open(){ overlay.classList.remove('hidden'); overlay.setAttribute('aria-hidden','false'); lastFocus=document.activeElement; try{ panel.querySelector('input[type="search"]').focus(); }catch(_){}}
      function close(){ overlay.classList.add('hidden'); overlay.setAttribute('aria-hidden','true'); try{ if(lastFocus) lastFocus.focus(); }catch(_){} }
      document.addEventListener('click', function(e){
        var t=e.target;
        if(t.closest('[data-aqlx-search-toggle]')){ e.preventDefault(); open(); return; }
        if(t.closest('[data-aqlx-search-close]') || t===overlay){ e.preventDefault(); close(); return; }
      });
      document.addEventListener('keydown', function(e){
        if(e.key==='Escape' && !overlay.classList.contains('hidden')){ close(); }
        if(e.key==='/' && !e.ctrlKey && !e.metaKey && !e.altKey && e.target && (e.target.tagName!=='INPUT' && e.target.tagName!=='TEXTAREA')){ e.preventDefault(); open(); }
      });
    })();
    </script>
    <?php
});
