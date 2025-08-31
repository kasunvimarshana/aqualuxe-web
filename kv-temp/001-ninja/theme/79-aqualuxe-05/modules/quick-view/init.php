<?php
declare(strict_types=1);

namespace Aqualuxe\Modules\QuickView;

// Lightweight quick-view using WooCommerce product template parts, guarded for dual state.

add_action('wp_ajax_aqlx_quick_view', __NAMESPACE__ . '\\ajax_quick_view');
add_action('wp_ajax_nopriv_aqlx_quick_view', __NAMESPACE__ . '\\ajax_quick_view');
// Lightweight cart count endpoint for header badge refresh
add_action('wp_ajax_aqlx_cart_count', __NAMESPACE__ . '\\ajax_cart_count');
add_action('wp_ajax_nopriv_aqlx_cart_count', __NAMESPACE__ . '\\ajax_cart_count');

function ajax_quick_view(): void {
  check_ajax_referer('aqlx_qv');
    $pid = max(0, (int) ($_GET['product_id'] ?? $_POST['product_id'] ?? 0));
    if (!$pid) { wp_send_json_error(['message'=>'missing id'], 400); }
    if (!class_exists('WooCommerce')) { wp_send_json_error(['message'=>'woocommerce inactive'], 400); }
    $prev = get_post();
    $product = get_post($pid);
    if (!$product || 'product' !== $product->post_type) { wp_send_json_error(['message'=>'not a product'], 404); }
    setup_postdata($product);
    ob_start();
    ?>
  <div class="aqlx-qv p-4 bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100 max-w-3xl" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr(get_the_title($product)); ?>">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="qv-images">
          <?php if (function_exists('woocommerce_show_product_sale_flash')) { call_user_func('woocommerce_show_product_sale_flash'); }
          if (function_exists('woocommerce_show_product_images')) { call_user_func('woocommerce_show_product_images'); } ?>
        </div>
        <div class="qv-summary">
          <?php if (function_exists('woocommerce_template_single_title')) { call_user_func('woocommerce_template_single_title'); }
          if (function_exists('woocommerce_template_single_rating')) { call_user_func('woocommerce_template_single_rating'); }
          if (function_exists('woocommerce_template_single_price')) { call_user_func('woocommerce_template_single_price'); }
          if (function_exists('woocommerce_template_single_excerpt')) { call_user_func('woocommerce_template_single_excerpt'); }
          if (function_exists('woocommerce_template_single_add_to_cart')) { call_user_func('woocommerce_template_single_add_to_cart'); }
          if (function_exists('woocommerce_template_single_meta')) { call_user_func('woocommerce_template_single_meta'); } ?>
        </div>
      </div>
    </div>
    <?php
    $html = (string) ob_get_clean();
    if ($prev) { setup_postdata($prev); }
    wp_send_json_success(['html'=>$html]);
}

function ajax_cart_count(): void {
  if (!class_exists('WooCommerce') || !isset($GLOBALS['woocommerce']->cart)) {
    wp_send_json_success(['count' => 0]);
  }
  $count = (int) $GLOBALS['woocommerce']->cart->get_cart_contents_count();
  wp_send_json_success(['count' => $count]);
}

// Front-end script to trigger quick-view from product loops
add_action('wp_footer', function(){
    if (!class_exists('WooCommerce')) return;
  $nonce = wp_create_nonce('aqlx_qv');
    ?>
    <script>
    (function(){
      var lastFocus;
      function focusTrap(container){
        var sel='a,button,input,select,textarea,[tabindex]:not([tabindex="-1"])';
        var nodes=container.querySelectorAll(sel); if(!nodes.length) return;
        var first=nodes[0], last=nodes[nodes.length-1];
        container.addEventListener('keydown', function(e){
          if(e.key==='Tab'){
            if(e.shiftKey && document.activeElement===first){ e.preventDefault(); last.focus(); }
            else if(!e.shiftKey && document.activeElement===last){ e.preventDefault(); first.focus(); }
          }
          if(e.key==='Escape'){ e.preventDefault(); close(container.parentElement); }
        });
        setTimeout(function(){ first.focus(); }, 0);
      }
      function close(overlay){ if(!overlay) return; overlay.remove(); if(lastFocus) try{ lastFocus.focus(); }catch(e){} }
      function openQV(html){
        var overlay=document.createElement('div');
        overlay.className='fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4';
        overlay.innerHTML='<div class="bg-white dark:bg-gray-900 rounded shadow-xl max-w-3xl w-full relative">\
        <button aria-label="Close" class="absolute top-2 right-2 p-2">✕</button>'+html+'</div>';
        document.body.appendChild(overlay);
        var panel=overlay.firstElementChild;
        focusTrap(panel);
        overlay.addEventListener('click', function(e){ if(e.target===overlay||e.target.closest('button[aria-label=\'Close\']')) close(overlay); });

        // Initialize Woo variation forms if jQuery plugin is present
        try {
          if (window.jQuery && jQuery.fn && typeof jQuery.fn.wc_variation_form === 'function') {
            jQuery(panel).find('.variations_form').each(function(){ jQuery(this).wc_variation_form(); });
          }
        } catch(_) {}

        // Intercept add-to-cart submits inside modal and use Woo AJAX endpoint
        panel.addEventListener('submit', function(e){
          var form = e.target.closest('form.cart');
          if(!form) return;
          e.preventDefault();
          var fd = new FormData(form);
          var ajaxUrl = (window.wc_add_to_cart_params && window.wc_add_to_cart_params.wc_ajax_url)
                        ? window.wc_add_to_cart_params.wc_ajax_url.replace("%%endpoint%%", "add_to_cart")
                        : (window.location.origin + '/?wc-ajax=add_to_cart');
          var btn = form.querySelector('[type="submit"]');
          if(btn){ btn.disabled = true; btn.dataset._label = btn.textContent; btn.textContent = (btn.getAttribute('data-loading')||'Adding…'); }
          fetch(ajaxUrl, {method:'POST', credentials:'same-origin', body: fd})
            .then(function(r){ return r.json().catch(function(){ return {}; }); })
            .then(function(res){
              if(res && res.error && res.product_url){ window.location = res.product_url; return; }
              // Apply Woo fragments if present
              if (res && res.fragments && typeof res.fragments === 'object') {
                Object.keys(res.fragments).forEach(function(sel){
                  try {
                    var html = res.fragments[sel];
                    document.querySelectorAll(sel).forEach(function(el){ el.outerHTML = html; });
                  } catch(_) {}
                });
              }
              // Refresh header cart count
              var countUrl=(window.ajaxurl||'/wp-admin/admin-ajax.php')+'?action=aqlx_cart_count';
              fetch(countUrl, {credentials:'same-origin'}).then(function(r){return r.json();}).then(function(cc){
                if(cc && cc.success){
                  var els=document.querySelectorAll('.mini-cart .count');
                  els.forEach(function(el){ el.textContent = String(cc.data.count||0); });
                }
              }).finally(function(){ if(btn){ btn.disabled = false; btn.textContent = btn.dataset._label || btn.textContent; }});
            })
            .catch(function(){ if(btn){ btn.disabled = false; btn.textContent = btn.dataset._label || btn.textContent; } });
        });
      }
      document.addEventListener('click', function(e){
        var q=e.target.closest('[data-aqlx-qv]'); if(!q) return;
        e.preventDefault();
        lastFocus = q;
        var id=q.getAttribute('data-aqlx-qv');
  var base=(window.ajaxurl||'/wp-admin/admin-ajax.php');
  var url = base + '?action=aqlx_quick_view&product_id=' + encodeURIComponent(id) + '&_wpnonce=<?php echo esc_js($nonce); ?>';
        fetch(url, {credentials:'same-origin'}).then(r=>r.json()).then(function(res){ if(res.success) openQV(res.data.html); });
      });
    })();
    </script>
    <?php
});

// Add quick-view triggers to product loops
add_action('woocommerce_after_shop_loop_item', function(){
    global $product; if (!$product) return; echo '<a href="#" class="aqlx-qv-btn text-sm" data-aqlx-qv="'.esc_attr((string) $product->get_id()).'">'.esc_html__('Quick view','aqualuxe').'</a>';
}, 15);
