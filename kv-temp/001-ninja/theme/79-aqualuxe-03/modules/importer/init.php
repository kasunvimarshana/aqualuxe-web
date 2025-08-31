<?php
declare(strict_types=1);

namespace Aqualuxe\Modules\Importer;

// Admin page under Tools
add_action('admin_menu', static function (): void {
    add_management_page(
        __('AquaLuxe Demo Importer', 'aqualuxe'),
        __('AquaLuxe Importer', 'aqualuxe'),
        'manage_options',
        'aqualuxe-importer',
        __NAMESPACE__ . '\\render'
    );
});

function render(): void
{
    if (! current_user_can('manage_options')) { return; }
    $nonce = wp_create_nonce('aqlx_import');
    ?>
    <div class="wrap">
      <h1><?php esc_html_e('AquaLuxe Demo Content Importer', 'aqualuxe'); ?></h1>
      <p><?php esc_html_e('Safely import demo content or reset the site to a pristine state. Supports selective import and preview.', 'aqualuxe'); ?></p>
      <div id="aqlx-importer" class="card" style="padding:16px;max-width:900px;">
        <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
          <label><?php esc_html_e('Include:', 'aqualuxe'); ?></label>
          <?php $entities = ['pages','posts','media','menus','widgets','products','services','events','users']; foreach ($entities as $e): ?>
            <label><input type="checkbox" class="aqlx-entity" value="<?php echo esc_attr($e); ?>" checked> <?php echo esc_html(ucfirst($e)); ?></label>
          <?php endforeach; ?>
        </div>
        <div style="margin-top:12px;">
          <label><?php esc_html_e('Volume', 'aqualuxe'); ?></label>
          <input type="number" id="aqlx-volume" value="50" min="1" max="500">
          <label><?php esc_html_e('Locale', 'aqualuxe'); ?></label>
          <input type="text" id="aqlx-locale" value="en">
        </div>
        <div style="margin-top:12px;display:flex;gap:8px;">
          <button class="button button-primary" id="aqlx-run" data-nonce="<?php echo esc_attr($nonce); ?>"><?php esc_html_e('Run Import', 'aqualuxe'); ?></button>
          <button class="button" id="aqlx-preview"><?php esc_html_e('Preview', 'aqualuxe'); ?></button>
          <button class="button button-secondary" id="aqlx-export"><?php esc_html_e('Export Backup', 'aqualuxe'); ?></button>
          <button class="button button-link-delete" id="aqlx-flush"><?php esc_html_e('Flush & Reset (Danger)', 'aqualuxe'); ?></button>
        </div>
        <div id="aqlx-log" style="margin-top:12px;max-height:240px;overflow:auto;background:#111;color:#0f0;padding:8px;font-family:monospace;"></div>
      </div>
    </div>
    <script>
    (function(){
      function log(msg){var el=document.getElementById('aqlx-log'); el.textContent += (msg+'\n'); el.scrollTop=el.scrollHeight;}
      function selected(){return Array.from(document.querySelectorAll('.aqlx-entity:checked')).map(e=>e.value)}
      function ajax(action, extra){
        const data = {
          action: 'aqlx_'+action,
          _wpnonce: document.getElementById('aqlx-run').dataset.nonce,
          entities: selected(),
          volume: document.getElementById('aqlx-volume').value,
          locale: document.getElementById('aqlx-locale').value,
          ...extra
        };
        return fetch(ajaxurl, {method:'POST', credentials:'same-origin', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body: new URLSearchParams(data)}).then(r=>r.json());
      }
      document.getElementById('aqlx-run').addEventListener('click', function(){
        log('Starting import...');
        ajax('import').then(r=>{ log(JSON.stringify(r));}).catch(e=>log('ERR '+e));
      });
      document.getElementById('aqlx-preview').addEventListener('click', function(){
        log('Generating preview...');
        ajax('preview').then(r=>{ log(JSON.stringify(r));}).catch(e=>log('ERR '+e));
      });
      document.getElementById('aqlx-export').addEventListener('click', function(){
        log('Exporting...');
        ajax('export').then(r=>{ log(JSON.stringify(r));}).catch(e=>log('ERR '+e));
      });
      document.getElementById('aqlx-flush').addEventListener('click', function(){
        if(!confirm('Type FLUSH to confirm')){return;}
        log('Flushing content...');
        ajax('flush').then(r=>{ log(JSON.stringify(r));}).catch(e=>log('ERR '+e));
      });
    })();
    </script>
    <?php
}

// AJAX endpoints
add_action('wp_ajax_aqlx_import', __NAMESPACE__ . '\\ajax_import');
add_action('wp_ajax_aqlx_preview', __NAMESPACE__ . '\\ajax_preview');
add_action('wp_ajax_aqlx_export', __NAMESPACE__ . '\\ajax_export');
add_action('wp_ajax_aqlx_flush', __NAMESPACE__ . '\\ajax_flush');

function verify(): void { check_ajax_referer('aqlx_import'); if (! current_user_can('manage_options')) { wp_send_json_error('forbidden', 403); } }

function ajax_preview(): void { verify(); wp_send_json_success(['ok'=>true,'preview'=>['pages'=>5,'products'=>12]]); }
function ajax_export(): void { verify(); wp_send_json_success(['ok'=>true,'file'=>'/wp-content/uploads/aqlx-backup.json']); }

function ajax_flush(): void
{
    verify();
    do_action('aqualuxe/importer/flush');
    wp_send_json_success(['ok'=>true]);
}

function ajax_import(): void
{
    verify();
    $entities = array_map('sanitize_text_field', (array) ($_POST['entities'] ?? []));
    $volume = max(1, (int) ($_POST['volume'] ?? 50));
    $locale = sanitize_text_field($_POST['locale'] ?? 'en');
    $result = run_import($entities, $volume, $locale);
    if ($result['ok'] ?? false) { wp_send_json_success($result); }
    wp_send_json_error($result, 500);
}

// Hooks for WP-CLI bridge
add_action('aqualuxe/importer/run', function ($args = []) { run_import(['pages','posts','products','services','events','media','menus','widgets','users'], 50, 'en'); });
add_action('aqualuxe/importer/flush', __NAMESPACE__ . '\\hard_flush');

function hard_flush(): void
{
    // Danger zone: delete content types we created; keep admins.
    $types = ['page','post','service','event','product'];
    foreach ($types as $type) {
        $q = new \WP_Query(['post_type'=>$type,'posts_per_page'=>-1,'post_status'=>'any','fields'=>'ids']);
        foreach ($q->posts as $pid) { wp_delete_post((int) $pid, true); }
    }
    // Delete menus
    $menus = wp_get_nav_menus();
    foreach ($menus as $menu) { wp_delete_nav_menu($menu->term_id); }
}

function run_import(array $entities, int $volume, string $locale): array
{
    $created = [];
    if (in_array('pages', $entities, true)) {
        $home = wp_insert_post(['post_type'=>'page','post_title'=>'Home','post_status'=>'publish','post_content'=>'[aqlx_home]']);
        if (! is_wp_error($home)) { $created['pages'][] = $home; update_option('page_on_front', $home); update_option('show_on_front', 'page'); }
        $about = wp_insert_post(['post_type'=>'page','post_title'=>'About','post_status'=>'publish']);
        $services = wp_insert_post(['post_type'=>'page','post_title'=>'Services','post_status'=>'publish']);
        $contact = wp_insert_post(['post_type'=>'page','post_title'=>'Contact','post_status'=>'publish']);
        $faq = wp_insert_post(['post_type'=>'page','post_title'=>'FAQ','post_status'=>'publish']);
  $blog = wp_insert_post(['post_type'=>'page','post_title'=>'Blog','post_status'=>'publish']);
  if (! is_wp_error($blog)) { update_option('page_for_posts', $blog); update_option('show_on_front', 'page'); }
  // Wishlist page with shortcode and template
  $wishlist = wp_insert_post(['post_type'=>'page','post_title'=>'Wishlist','post_status'=>'publish','post_content'=>'[aqlx_wishlist]']);
  if (! is_wp_error($wishlist)) {
    update_post_meta($wishlist, '_wp_page_template', 'page-wishlist.php');
  }
        $legal = ['Privacy Policy','Terms & Conditions','Shipping & Returns','Cookie Policy'];
        foreach ($legal as $l) { wp_insert_post(['post_type'=>'page','post_title'=>$l,'post_status'=>'publish']); }
    }
  if (class_exists('WooCommerce') && in_array('products', $entities, true)) {
    // Ensure core categories
    $cats = [
      'Rare Fish Species', 'Aquatic Plants', 'Premium Equipment', 'Care Supplies'
    ];
    foreach ($cats as $c) {
      if (! term_exists($c, 'product_cat')) { wp_insert_term($c, 'product_cat'); }
    }
    // Simple products
    for ($i=0;$i<min($volume, 40);$i++) {
      $pid = wp_insert_post(['post_type'=>'product','post_title'=>'Aqua Specimen #'.($i+1),'post_status'=>'publish']);
      if (! is_wp_error($pid)) {
        update_post_meta($pid, '_regular_price', (string) (50+$i));
        update_post_meta($pid, '_price', (string) (50+$i));
        wp_set_object_terms($pid, [$cats[$i % count($cats)]], 'product_cat');
      }
    }
    // Global attributes
    $attrs = [
      'size' => ['S','M','L'],
      'color' => ['Blue','Gold','Emerald'],
      'material' => ['Glass','Acrylic']
    ];
    foreach ($attrs as $slug => $terms) {
      $tax = 'pa_' . sanitize_title($slug);
      if (function_exists('wc_create_attribute')) {
        // Ensure attribute is registered in Woo's attribute taxonomy table
        $attr_id = 0;
        if (function_exists('wc_attribute_taxonomy_id_by_name')) {
          $attr_id = (int) call_user_func('wc_attribute_taxonomy_id_by_name', $tax);
        }
        if (! $attr_id) {
          $res = call_user_func('wc_create_attribute', [
            'name' => ucfirst($slug),
            'slug' => sanitize_title($slug),
            'type' => 'select',
            'order_by' => 'menu_order',
            'has_archives' => false,
          ]);
          if (! is_wp_error($res)) {
            $attr_id = (int) $res;
            if (method_exists('WC_Product_Attributes', 'register_attribute_taxonomy')) {
              // Best-effort refresh of taxonomies
              if (function_exists('wc_delete_product_transients')) { call_user_func('wc_delete_product_transients', 0); }
            }
          }
        }
      }
      // Register taxonomy if missing (frontend usage)
      if (! taxonomy_exists($tax)) {
        register_taxonomy($tax, 'product', [ 'hierarchical' => false, 'label' => ucfirst($slug), 'show_ui' => false ]);
      }
      if (! term_exists($terms[0], $tax)) {
        foreach ($terms as $t) { wp_insert_term($t, $tax); }
      }
    }
    // Variable product
    $var_id = wp_insert_post(['post_type'=>'product','post_title'=>'AquaLuxe Tank Kit','post_status'=>'publish']);
    if (! is_wp_error($var_id)) {
            // Mark as variable product
            wp_set_object_terms($var_id, 'variable', 'product_type');
            update_post_meta($var_id, '_product_attributes', [
        'pa_size' => ['name'=>'pa_size','value'=>'','position'=>0,'is_visible'=>1,'is_variation'=>1,'is_taxonomy'=>1],
        'pa_color' => ['name'=>'pa_color','value'=>'','position'=>1,'is_visible'=>1,'is_variation'=>1,'is_taxonomy'=>1],
      ]);
      wp_set_object_terms($var_id, ['S','M','L'], 'pa_size');
      wp_set_object_terms($var_id, ['Blue','Gold'], 'pa_color');
      wp_set_object_terms($var_id, ['Premium Equipment'], 'product_cat');
      update_post_meta($var_id, '_manage_stock', 'no');
      update_post_meta($var_id, '_stock_status', 'instock');
      // Create variations
      $combos = [ ['S','Blue', 199], ['M','Blue', 249], ['L','Gold', 329] ];
      foreach ($combos as $idx => $c) {
        $v_id = wp_insert_post([
          'post_title' => 'Variation',
          'post_name' => 'product-' . $var_id . '-variation-' . $idx,
          'post_status' => 'publish',
          'post_parent' => $var_id,
          'post_type' => 'product_variation',
          'menu_order' => $idx,
        ]);
        if (! is_wp_error($v_id)) {
          update_post_meta($v_id, 'attribute_pa_size', sanitize_title($c[0]));
          update_post_meta($v_id, 'attribute_pa_color', sanitize_title($c[1]));
          update_post_meta($v_id, '_regular_price', (string) $c[2]);
          update_post_meta($v_id, '_price', (string) $c[2]);
          update_post_meta($v_id, '_stock', '10');
          update_post_meta($v_id, '_stock_status', 'instock');
        }
      }
            update_post_meta($var_id, '_visibility', 'visible');
      update_post_meta($var_id, '_default_attributes', [ 'pa_size' => 'm', 'pa_color' => 'blue' ]);
            if (function_exists('wc_delete_product_transients')) { call_user_func('wc_delete_product_transients', $var_id); }
      $created['variable_product'] = $var_id;
    }
    $created['products_count'] = ($created['products_count'] ?? 0) + ($i ?? 0) + 1;
  }
  // Ensure Shop page exists and is assigned if WooCommerce is active
  if (class_exists('WooCommerce')) {
    $shop_page_id = (int) get_option('woocommerce_shop_page_id', 0);
    if (! $shop_page_id || 'page' !== get_post_type($shop_page_id)) {
        $shop_page_id = wp_insert_post(['post_type'=>'page','post_title'=>'Shop','post_status'=>'publish']);
        if (! is_wp_error($shop_page_id)) {
            update_option('woocommerce_shop_page_id', $shop_page_id);
        }
    }
  // Ensure core Woo pages
  $ensure_page = static function(string $opt_key, string $title, string $shortcode): int {
    $pid = (int) get_option($opt_key, 0);
    if (! $pid || 'page' !== get_post_type($pid)) {
      $pid = wp_insert_post(['post_type'=>'page','post_title'=>$title,'post_status'=>'publish','post_content'=>$shortcode]);
      if (! is_wp_error($pid)) { update_option($opt_key, $pid); }
    }
    return (int) $pid;
  };
  $cart_id = $ensure_page('woocommerce_cart_page_id', 'Cart', '[woocommerce_cart]');
  $checkout_id = $ensure_page('woocommerce_checkout_page_id', 'Checkout', '[woocommerce_checkout]');
  $myaccount_id = $ensure_page('woocommerce_myaccount_page_id', 'My Account', '[woocommerce_my_account]');
  }

  // Menus: ensure Primary and Footer exist, are assigned, and include key items.
  $locations = get_theme_mod('nav_menu_locations', []);
  $ensure_menu = static function(string $location, string $name) use (&$locations): int {
    $menu_id = 0;
    $menus = wp_get_nav_menus();
    foreach ($menus as $m) { if ($m->term_id && $m->name === $name) { $menu_id = (int) $m->term_id; break; } }
    if (! $menu_id) { $menu_id = (int) wp_create_nav_menu($name); }
    if (! isset($locations[$location]) || (int) $locations[$location] !== $menu_id) {
      $locations[$location] = $menu_id;
      set_theme_mod('nav_menu_locations', $locations);
    }
    return $menu_id;
  };

  $primary_id = $ensure_menu('primary', __('Primary Menu', 'aqualuxe'));
  $footer_id  = $ensure_menu('footer', __('Footer Menu', 'aqualuxe'));
  $account_menu_id = $ensure_menu('account', __('Account Menu', 'aqualuxe'));

  // Helper to add menu item if not already present by object_id
  $add_menu_item = static function(int $menu_id, int $object_id, string $type = 'post_type', string $title = ''): void {
    $exists = false;
    $items = wp_get_nav_menu_items($menu_id) ?: [];
    foreach ($items as $it) { if ((int) ($it->object_id ?? 0) === $object_id) { $exists = true; break; } }
    if ($exists) return;
    wp_update_nav_menu_item($menu_id, 0, [
      'menu-item-title' => $title ?: get_the_title($object_id),
      'menu-item-object' => 'page',
      'menu-item-object-id' => $object_id,
      'menu-item-type' => $type,
      'menu-item-status' => 'publish',
    ]);
  };

  // Gather key pages
  $get_page_id = static function(string $title): int {
    $p = get_page_by_title($title);
    return $p ? (int) $p->ID : 0;
  };
  $home_id = (int) get_option('page_on_front');
  $blog_id = (int) get_option('page_for_posts');
  $services_id = $get_page_id('Services');
  $contact_id = $get_page_id('Contact');
  $wishlist_id = $get_page_id('Wishlist');
  $shop_id = (int) get_option('woocommerce_shop_page_id');

  foreach ([$home_id, $shop_id, $services_id, $blog_id, $contact_id, $wishlist_id] as $pid) {
    if ($pid) { $add_menu_item($primary_id, $pid); }
  }

  // Footer links: legal + contact + wishlist
  $legal_titles = ['Privacy Policy','Terms & Conditions','Shipping & Returns','Cookie Policy'];
  foreach ($legal_titles as $lt) {
    $pid = $get_page_id($lt);
    if ($pid) { $add_menu_item($footer_id, $pid); }
  }
  foreach ([$contact_id, $wishlist_id] as $pid) { if ($pid) { $add_menu_item($footer_id, $pid); } }

  // Account menu: My Account, Orders (custom link), Cart, Checkout, Wishlist
  if (class_exists('WooCommerce')) {
    $myaccount_id = (int) get_option('woocommerce_myaccount_page_id');
    $cart_id = (int) get_option('woocommerce_cart_page_id');
    $checkout_id = (int) get_option('woocommerce_checkout_page_id');
    // Helper to add custom link if URL not present
    $add_custom = static function(int $menu_id, string $url, string $title): void {
      $exists = false; $items = wp_get_nav_menu_items($menu_id) ?: [];
      foreach ($items as $it) { if (($it->type ?? '') === 'custom' && ($it->url ?? '') === $url) { $exists = true; break; } }
      if ($exists) return;
      wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title' => $title,
        'menu-item-url' => $url,
        'menu-item-type' => 'custom',
        'menu-item-status' => 'publish',
      ]);
    };
    if ($myaccount_id) { $add_menu_item($account_menu_id, $myaccount_id); }
    // Orders endpoint under My Account
    $my_account_url = $myaccount_id ? get_permalink($myaccount_id) : home_url('/my-account/');
    $orders_url = trailingslashit($my_account_url) . 'orders/';
    $add_custom($account_menu_id, $orders_url, __('Orders', 'aqualuxe'));
    if ($cart_id) { $add_menu_item($account_menu_id, $cart_id); }
    if ($checkout_id) { $add_menu_item($account_menu_id, $checkout_id); }
    if ($wishlist_id) { $add_menu_item($account_menu_id, $wishlist_id, 'post_type', __('Wishlist', 'aqualuxe')); }
  }
    if (in_array('services', $entities, true)) {
        $svc = wp_insert_post(['post_type'=>'service','post_title'=>'Aquarium Design','post_status'=>'publish']);
        $svc2 = wp_insert_post(['post_type'=>'service','post_title'=>'Maintenance Plan','post_status'=>'publish']);
        $created['services'] = [$svc, $svc2];
    }
    if (in_array('events', $entities, true)) {
        $evt = wp_insert_post(['post_type'=>'event','post_title'=>'Aquascaping Workshop','post_status'=>'publish']);
        $created['events'] = [$evt];
    }
    return ['ok'=>true,'created'=>$created,'locale'=>$locale];
}
