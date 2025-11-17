/* AquaLuxe main JS - progressive enhancement only */
(function(){
  function q(sel, ctx){ return (ctx||document).querySelector(sel); }
  function on(el, ev, cb){ if(el) el.addEventListener(ev, cb); }
  function qa(sel, ctx){ return Array.prototype.slice.call((ctx||document).querySelectorAll(sel)); }
  function ajax(url, data, cb){
    var xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
    xhr.onreadystatechange = function(){ if(xhr.readyState===4){ cb(xhr.status, xhr.responseText); } };
    var body = Object.keys(data).map(function(k){return encodeURIComponent(k)+'='+encodeURIComponent(data[k]);}).join('&');
    xhr.send(body);
  }

  // Mobile menu toggle
  on(q('#mobile_menu_toggle'), 'click', function(){
    var m = q('#mobile_menu');
    var expanded = this.getAttribute('aria-expanded') === 'true';
    this.setAttribute('aria-expanded', (!expanded).toString());
    if (m) m.classList.toggle('hidden');
  });

  // Dark mode toggle
  on(q('#dark_mode_toggle'), 'click', function(){
    var html = document.documentElement;
    var nowDark = !html.classList.contains('dark');
    if (nowDark) html.classList.add('dark'); else html.classList.remove('dark');
    try { localStorage.setItem('al_dark', nowDark ? '1' : '0'); } catch(e){}
    this.setAttribute('aria-pressed', nowDark.toString());
  });

  // Quick view
  qa('.al_quick_view').forEach(function(btn){
    on(btn, 'click', function(){
      var id = this.getAttribute('data-product-id');
      var url = (window.AquaLuxe && AquaLuxe.ajaxUrl) ? AquaLuxe.ajaxUrl : '/wp-admin/admin-ajax.php';
      var modal = q('#al_qv_modal');
      if (!modal) {
        modal = document.createElement('div');
        modal.id='al_qv_modal';
        modal.setAttribute('role','dialog');
        modal.setAttribute('aria-modal','true');
        modal.className='fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 hidden';
        modal.innerHTML = '<div class="bg-white dark:bg-slate-900 rounded-lg shadow-xl max-w-3xl w-full overflow-auto max-h-[90vh]">\
          <div class="p-2 text-right"><button id="al_qv_close" class="px-3 py-1">&times;</button></div>\
          <div class="p-4" id="al_qv_content">Loading...</div></div>';
        document.body.appendChild(modal);
        on(q('#al_qv_close', modal), 'click', function(){ modal.classList.add('hidden'); });
      }
      modal.classList.remove('hidden');
      q('#al_qv_content').textContent='Loading...';
      var src='?action=al_quick_view&product_id='+encodeURIComponent(id);
      // GET via iframe to keep it simple and avoid CORS/headers issues
      var frame = document.createElement('iframe');
      frame.src = url + src;
      frame.className='w-full h-[70vh]';
      var content = q('#al_qv_content');
      content.innerHTML='';
      content.appendChild(frame);
    });
  });

  // Wishlist toggle
  qa('.al_wishlist_toggle').forEach(function(btn){
    on(btn, 'click', function(){
      var id = this.getAttribute('data-product-id');
      var url = (window.AquaLuxe && AquaLuxe.ajaxUrl) ? AquaLuxe.ajaxUrl : '/wp-admin/admin-ajax.php';
      ajax(url, {action:'al_toggle_wishlist', nonce:(window.AquaLuxe?AquaLuxe.nonce:''), product_id:id}, function(st, res){
        // Optimistic UI
      });
    });
  });
})();
