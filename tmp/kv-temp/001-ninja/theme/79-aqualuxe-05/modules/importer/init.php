<?php
declare(strict_types=1);

namespace Aqualuxe\Modules\Importer;

// =========================
// Admin UI and AJAX wiring
// =========================

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
  $schedule = get_option('aqlx_demo_schedule', ['enabled'=>false,'recurrence'=>'']);
  ?>
  <div class="wrap">
    <h1><?php esc_html_e('AquaLuxe Demo Content Importer', 'aqualuxe'); ?></h1>
    <p><?php esc_html_e('Import realistic demo data, preview changes, export backups, or flush to a clean slate. Supports selective import, progress tracking, rollback on error, and scheduling.', 'aqualuxe'); ?></p>
    <div id="aqlx-importer" class="card" style="padding:16px;max-width:1000px;">
    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
      <label><?php esc_html_e('Include:', 'aqualuxe'); ?></label>
      <?php $entities = ['pages','posts','media','menus','widgets','products','services','events','users']; foreach ($entities as $e): ?>
      <label><input type="checkbox" class="aqlx-entity" value="<?php echo esc_attr($e); ?>" checked> <?php echo esc_html(ucfirst($e)); ?></label>
      <?php endforeach; ?>
    </div>
    <div style="margin-top:12px;display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
      <label><?php esc_html_e('Volume', 'aqualuxe'); ?></label>
      <input type="number" id="aqlx-volume" value="50" min="1" max="1000">
      <label><?php esc_html_e('Locale', 'aqualuxe'); ?></label>
      <input type="text" id="aqlx-locale" value="en">
      <label><input type="checkbox" id="aqlx-media" checked> <?php esc_html_e('Generate media (placeholders)', 'aqualuxe'); ?></label>
      <label><input type="checkbox" id="aqlx-rollback" checked> <?php esc_html_e('Rollback on error', 'aqualuxe'); ?></label>
    </div>
    <div style="margin-top:12px;display:flex;gap:8px;flex-wrap:wrap;">
      <button class="button button-primary" id="aqlx-start" data-nonce="<?php echo esc_attr($nonce); ?>"><?php esc_html_e('Start Import', 'aqualuxe'); ?></button>
      <button class="button" id="aqlx-preview"><?php esc_html_e('Preview', 'aqualuxe'); ?></button>
      <button class="button" id="aqlx-next" disabled><?php esc_html_e('Next Step', 'aqualuxe'); ?></button>
      <button class="button button-secondary" id="aqlx-export"><?php esc_html_e('Export Backup', 'aqualuxe'); ?></button>
      <span style="margin-left:auto"></span>
      <input type="text" id="aqlx-confirm" placeholder="Type FLUSH" style="width:120px;">
      <button class="button button-link-delete" id="aqlx-flush"><?php esc_html_e('Flush & Reset', 'aqualuxe'); ?></button>
    </div>
    <div style="margin-top:12px;">
      <progress id="aqlx-progress" value="0" max="100" style="width:100%;height:16px;"></progress>
      <div id="aqlx-status" style="margin-top:6px;font-family:monospace;"></div>
      <pre id="aqlx-log" style="margin-top:10px;max-height:300px;overflow:auto;background:#111;color:#0f0;padding:8px;font-family:monospace;"></pre>
    </div>
    <hr style="margin:16px 0;"/>
    <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
      <strong><?php esc_html_e('Schedule automated re-initializations', 'aqualuxe'); ?></strong>
      <label><input type="checkbox" id="aqlx-sched-enabled" <?php checked(!empty($schedule['enabled'])); ?>> <?php esc_html_e('Enable', 'aqualuxe'); ?></label>
      <select id="aqlx-sched-recur">
      <option value="">—</option>
      <option value="daily" <?php selected($schedule['recurrence'] ?? '', 'daily'); ?>>Daily</option>
      <option value="weekly" <?php selected($schedule['recurrence'] ?? '', 'weekly'); ?>>Weekly</option>
      <option value="monthly" <?php selected($schedule['recurrence'] ?? '', 'monthly'); ?>>Monthly</option>
      </select>
      <button class="button" id="aqlx-sched-save"><?php esc_html_e('Save Schedule', 'aqualuxe'); ?></button>
    </div>
    </div>
  </div>
  <script>
  (function(){
    const nonce = document.getElementById('aqlx-start').dataset.nonce;
    const logEl = document.getElementById('aqlx-log');
    const statusEl = document.getElementById('aqlx-status');
    const progEl = document.getElementById('aqlx-progress');
    const nextBtn = document.getElementById('aqlx-next');
    function log(msg){ logEl.textContent += (msg+'\n'); logEl.scrollTop = logEl.scrollHeight; }
    function selected(){return Array.from(document.querySelectorAll('.aqlx-entity:checked')).map(e=>e.value)}
    function formData(extra){
    return new URLSearchParams({
      _wpnonce: nonce,
      entities: selected().join(','),
      volume: document.getElementById('aqlx-volume').value,
      locale: document.getElementById('aqlx-locale').value,
      media: document.getElementById('aqlx-media').checked ? '1':'0',
      rollback: document.getElementById('aqlx-rollback').checked ? '1':'0',
      ...extra
    });
    }
    function ajax(action, extra){
    return fetch(ajaxurl, {method:'POST', credentials:'same-origin', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body: new URLSearchParams({action}).toString()+'&'+formData(extra).toString()}).then(r=>r.json());
    }
    function poll(){ ajax('aqlx_demo_status').then(r=>{ if(!r||!r.success) return; const d=r.data; statusEl.textContent = d.message||d.status; progEl.value = d.progress||0; nextBtn.disabled = !(d && d.status==='running'); if(d.log){ logEl.textContent=''; d.log.forEach(line=>log(line)); } }); }
    setInterval(poll, 1200);
    document.getElementById('aqlx-start').addEventListener('click', function(){ log('Starting job...'); ajax('aqlx_demo_start').then(r=>{ if(!r.success) log('ERR '+(r.data||'fail')); else log('Job started'); }).catch(e=>log('ERR '+e)); });
    nextBtn.addEventListener('click', function(){ ajax('aqlx_demo_next_step').then(r=>{ if(!r.success) log('ERR '+(r.data||'fail')); else log('Step ok'); }).catch(e=>log('ERR '+e)); });
    document.getElementById('aqlx-preview').addEventListener('click', function(){ ajax('aqlx_demo_preview').then(r=>{ if(r&&r.success){ log('--- Preview ---'); log(JSON.stringify(r.data.preview,null,2)); log('--- End Preview ---'); } }).catch(e=>log('ERR '+e)); });
    document.getElementById('aqlx-export').addEventListener('click', function(){ ajax('aqlx_demo_export').then(r=>{ if(r&&r.success){ log('Export: '+r.data.file); } }).catch(e=>log('ERR '+e)); });
    document.getElementById('aqlx-flush').addEventListener('click', function(){ const c=document.getElementById('aqlx-confirm').value.trim(); if(c!=='FLUSH'){ alert('Type FLUSH to confirm'); return; } ajax('aqlx_demo_flush', {confirm:c}).then(r=>{ if(r&&r.success){ log('Flushed.'); } else { log('Flush failed'); } }).catch(e=>log('ERR '+e)); });
    document.getElementById('aqlx-sched-save').addEventListener('click', function(){ ajax('aqlx_demo_schedule', {enabled: document.getElementById('aqlx-sched-enabled').checked?'1':'0', recurrence: document.getElementById('aqlx-sched-recur').value}).then(r=>{ if(r&&r.success){ log('Schedule saved'); } else { log('Schedule error'); } }); });
  })();
  </script>
  <?php
}

// =========================
// State and helpers
// =========================

const META_KEY = 'aqlx_demo';
const JOB_OPT = 'aqlx_demo_job';

function verify(): void { check_ajax_referer('aqlx_import'); if (! current_user_can('manage_options')) { wp_send_json_error('forbidden', 403); } }

function get_job(): array { return get_option(JOB_OPT, ['status'=>'idle','progress'=>0,'log'=>[]]); }
function save_job(array $job): void { update_option(JOB_OPT, $job, false); }
function reset_job(): void { delete_option(JOB_OPT); }

function log_msg(string $msg): void { $job = get_job(); $job['log'][] = '['.current_time('H:i:s').'] '.$msg; save_job($job); }

function words(): array {
  return [
    'adjectives' => ['Crystal','Azure','Sapphire','Emerald','Golden','Silent','Shimmering','Lively','Bright','Calm'],
    'nouns' => ['Reef','Lagoon','Stream','Cascade','Glow','Drift','Harbor','Coral','Tide','Fin'],
  ];
}

function make_title(): string { $w = words(); return $w['adjectives'][array_rand($w['adjectives'])] . ' ' . $w['nouns'][array_rand($w['nouns'])]; }

function create_placeholder_image(string $label = 'AquaLuxe'): int {
  $upl = wp_upload_dir();
  $path = trailingslashit($upl['path']).'aqlx-'.wp_generate_password(8,false).'.png';
  if (function_exists('imagecreatetruecolor')) {
    $im = imagecreatetruecolor(600, 400);
    $bg = imagecolorallocate($im, 20, 30, 46);
    $fg = imagecolorallocate($im, 236, 248, 255);
    imagefilledrectangle($im, 0, 0, 600, 400, $bg);
    imagestring($im, 5, 10, 10, $label, $fg);
    imagepng($im, $path);
    imagedestroy($im);
    $filetype = wp_check_filetype(basename($path), null);
    $attach_id = wp_insert_attachment([
      'post_mime_type' => $filetype['type'],
      'post_title' => sanitize_file_name(basename($path)),
      'post_content' => '',
      'post_status' => 'inherit',
      'meta_input' => [META_KEY => 1],
    ], $path);
    if (! is_wp_error($attach_id)) {
      require_once ABSPATH . 'wp-admin/includes/image.php';
      $attach_data = wp_generate_attachment_metadata($attach_id, $path);
      wp_update_attachment_metadata($attach_id, $attach_data);
      return (int) $attach_id;
    }
  }
  return 0;
}

// Provide a monthly schedule if not present
add_filter('cron_schedules', static function(array $schedules): array {
  if (! isset($schedules['monthly'])) {
    $schedules['monthly'] = ['interval' => 30 * DAY_IN_SECONDS, 'display' => __('Once Monthly', 'aqualuxe')];
  }
  return $schedules;
});

// =========================
// AJAX endpoints (step-based)
// =========================

add_action('wp_ajax_aqlx_demo_preview', __NAMESPACE__ . '\\ajax_demo_preview');
add_action('wp_ajax_aqlx_demo_export', __NAMESPACE__ . '\\ajax_demo_export');
add_action('wp_ajax_aqlx_demo_flush', __NAMESPACE__ . '\\ajax_demo_flush');
add_action('wp_ajax_aqlx_demo_start', __NAMESPACE__ . '\\ajax_demo_start');
add_action('wp_ajax_aqlx_demo_next_step', __NAMESPACE__ . '\\ajax_demo_next_step');
add_action('wp_ajax_aqlx_demo_status', __NAMESPACE__ . '\\ajax_demo_status');
add_action('wp_ajax_aqlx_demo_schedule', __NAMESPACE__ . '\\ajax_demo_schedule');

// Back-compat endpoints
add_action('wp_ajax_aqlx_import', __NAMESPACE__ . '\\ajax_import_compat');
add_action('wp_ajax_aqlx_preview', __NAMESPACE__ . '\\ajax_demo_preview');
add_action('wp_ajax_aqlx_export', __NAMESPACE__ . '\\ajax_demo_export');
add_action('wp_ajax_aqlx_flush', __NAMESPACE__ . '\\ajax_demo_flush');

function parse_params(): array {
  $entities = array_filter(array_map('sanitize_text_field', explode(',', (string) ($_POST['entities'] ?? ''))));
  if (! $entities) { $entities = ['pages','posts','media','menus','widgets','products','services','events','users']; }
  return [
    'entities' => $entities,
    'volume' => max(1, (int) ($_POST['volume'] ?? 50)),
    'locale' => sanitize_text_field($_POST['locale'] ?? 'en'),
    'media' => !empty($_POST['media']),
    'rollback' => !empty($_POST['rollback']),
  ];
}

function ajax_demo_preview(): void { verify(); $p = parse_params(); $preview = [ 'pages'=>6, 'posts'=>min(10, $p['volume']), 'products'=>class_exists('WooCommerce')? min(40,$p['volume']):0, 'users'=>min(5, (int) ceil($p['volume']/10)) ]; wp_send_json_success(['preview'=>$preview]); }

function ajax_demo_export(): void {
  verify();
  $upl = wp_upload_dir();
  $file = trailingslashit($upl['path']) . 'aqlx-backup-' . date('Ymd-His') . '.json';
  $query = new \WP_Query(['post_type'=>['post','page','product','service','event'],'meta_key'=>META_KEY,'meta_value'=>1,'posts_per_page'=>-1,'post_status'=>'any']);
  $out = [];
  foreach ($query->posts as $p) { $out[] = ['ID'=>$p->ID,'post_type'=>$p->post_type,'post_title'=>$p->post_title,'post_content'=>$p->post_content]; }
  file_put_contents($file, wp_json_encode(['exported'=>count($out),'items'=>$out], JSON_PRETTY_PRINT));
  wp_send_json_success(['file'=> str_replace($upl['basedir'], $upl['baseurl'], $file)]);
}

function ajax_demo_flush(): void
{
  verify();
  $confirm = sanitize_text_field($_POST['confirm'] ?? '');
  if ($confirm !== 'FLUSH') { wp_send_json_error('confirm'); }
  hard_flush();
  reset_job();
  wp_send_json_success(['ok'=>true]);
}

function ajax_demo_start(): void {
  verify();
  $p = parse_params();
  $job = [
    'status' => 'running',
    'progress' => 0,
    'params' => $p,
    'created' => ['posts'=>[], 'menus'=>[], 'users'=>[], 'terms'=>[], 'media'=>[]],
    'step' => 0,
    'total_steps' => 10,
    'log' => [],
  ];
  save_job($job);
  update_option('aqlx_demo_last_params', $p, false);
  log_msg('Job started.');
  wp_send_json_success(['ok'=>true]);
}

function ajax_demo_next_step(): void { verify(); $res = run_next_step(); if ($res['done']) { wp_send_json_success(['done'=>true]); } else { wp_send_json_success(['step'=>$res['step']]); } }
function ajax_demo_status(): void { verify(); wp_send_json_success(get_job()); }

function ajax_demo_schedule(): void {
  verify();
  $enabled = !empty($_POST['enabled']);
  $recurrence = sanitize_text_field($_POST['recurrence'] ?? '');
  update_option('aqlx_demo_schedule', ['enabled'=>$enabled,'recurrence'=>$recurrence], false);
  $hook = 'aqlx_demo_scheduled_reset';
  wp_clear_scheduled_hook($hook);
  if ($enabled && in_array($recurrence, ['daily','weekly','monthly'], true)) {
    $interval = $recurrence==='daily' ? DAY_IN_SECONDS : ($recurrence==='weekly' ? WEEK_IN_SECONDS : 30 * DAY_IN_SECONDS);
    wp_schedule_event(time()+$interval, $recurrence==='monthly' ? 'monthly' : $recurrence, $hook);
  }
  wp_send_json_success(['ok'=>true]);
}

// Compatibility: run full import in one go
function ajax_import_compat(): void { verify(); $p = parse_params(); $jobStart = ['status'=>'running','progress'=>0,'params'=>$p,'created'=>['posts'=>[], 'menus'=>[], 'users'=>[], 'terms'=>[], 'media'=>[]],'step'=>0,'total_steps'=>10,'log'=>[]]; save_job($jobStart); while (! run_next_step()['done']){} $job = get_job(); wp_send_json_success(['ok'=>true,'created'=>$job['created']]); }

// =========================
// Step engine
// =========================

function run_next_step(): array {
  $job = get_job();
  if (($job['status'] ?? '') !== 'running') { return ['done'=>true,'step'=>$job['step'] ?? 0]; }
  $step = (int) ($job['step'] ?? 0);
  $params = $job['params'] ?? [];
  try {
    switch ($step) {
      case 0: ensure_roles(); ensure_cpts(); log_msg('Environment ensured.'); break;
      case 1: if (in_array('users', $params['entities'], true)) create_users($job, $params); break;
      case 2: if ($params['media']) create_media($job, $params); break;
      case 3: if (in_array('pages', $params['entities'], true)) create_pages($job, $params); break;
      case 4: if (in_array('posts', $params['entities'], true)) create_posts($job, $params); break;
      case 5: if (class_exists('WooCommerce') && in_array('products', $params['entities'], true)) create_products($job, $params); break;
      case 6: if (in_array('menus', $params['entities'], true)) setup_menus($job, $params); break;
      case 7: if (in_array('widgets', $params['entities'], true)) setup_widgets($job, $params); break;
      case 8: if (in_array('services', $params['entities'], true) || in_array('events', $params['entities'], true)) create_services_events($job, $params); break;
      case 9: finalize_site($job, $params); log_msg('Finalize done.'); break;
    }
    $step++;
    $job['step'] = $step;
    $job['progress'] = (int) floor(($step / max(1, (int) $job['total_steps'])) * 100);
    save_job($job);
    if ($step >= (int) $job['total_steps']) { $job['status'] = 'done'; $job['progress'] = 100; save_job($job); log_msg('Job complete.'); return ['done'=>true, 'step'=>$step]; }
    return ['done'=>false,'step'=>$step];
  } catch (\Throwable $e) {
    log_msg('Error: '.$e->getMessage());
    if (!empty($params['rollback'])) { rollback_created($job); log_msg('Rolled back changes.'); }
    $job['status'] = 'error'; save_job($job); return ['done'=>true,'step'=>$step];
  }
}

function ensure_roles(): void { add_role('support_agent', __('Support Agent','aqualuxe'), ['read'=>true]); }
function ensure_cpts(): void { /* Assume CPTs registered elsewhere via modules */ }

function create_users(array &$job, array $p): void {
  $count = min(5, (int) ceil($p['volume']/10));
  for ($i=0;$i<$count;$i++) {
    $uname = 'demo_user_'.wp_generate_password(6,false);
    $uid = username_exists($uname) ? 0 : wp_insert_user(['user_login'=>$uname,'user_pass'=>wp_generate_password(12,false),'role'=> ($i%2? 'customer':'support_agent'),'display_name'=>make_title()]);
    if (! is_wp_error($uid) && $uid) { $job['created']['users'][] = (int) $uid; }
  }
}

function create_media(array &$job, array $p): void {
  $count = min(10, (int) ceil($p['volume']/5));
  for ($i=0;$i<$count;$i++) { $aid = create_placeholder_image('AquaLuxe '.($i+1)); if ($aid) { $job['created']['media'][] = $aid; } }
}

function upsert_page(string $title, string $content = ''): int { $p = get_page_by_title($title); if ($p) return (int) $p->ID; return (int) wp_insert_post(['post_type'=>'page','post_title'=>$title,'post_status'=>'publish','post_content'=>$content,'meta_input'=>[META_KEY=>1]]); }

function create_pages(array &$job, array $p): void {
  $home = upsert_page('Home', '[aqlx_home]'); if ($home) { update_option('page_on_front', $home); update_option('show_on_front', 'page'); $job['created']['posts'][] = $home; }
  $blog = upsert_page('Blog'); if ($blog) { update_option('page_for_posts', $blog); }
  foreach (['About','Services','Contact','FAQ','Wishlist'] as $t) { $pid = upsert_page($t, $t==='Wishlist' ? '[aqlx_wishlist]':''); if ($t==='Wishlist' && $pid) update_post_meta($pid, '_wp_page_template', 'page-wishlist.php'); if ($pid) $job['created']['posts'][] = $pid; }
  foreach (['Privacy Policy','Terms & Conditions','Shipping & Returns','Cookie Policy'] as $t) { $pid = upsert_page($t); if ($pid) $job['created']['posts'][] = $pid; }
}

function create_posts(array &$job, array $p): void {
  $count = min(10, $p['volume']);
  for ($i=0;$i<$count;$i++) { $title = make_title().' Journal'; $exists = get_page_by_title($title, OBJECT, 'post'); if ($exists) continue; $pid = wp_insert_post(['post_type'=>'post','post_title'=>$title,'post_status'=>'publish','post_content'=>wpautop('Demo post content for '.$title),'meta_input'=>[META_KEY=>1]]); if (! is_wp_error($pid)) { $job['created']['posts'][] = (int) $pid; } }
}

function create_products(array &$job, array $p): void {
  // Categories
  $cats = ['Rare Fish Species','Aquatic Plants','Premium Equipment','Care Supplies'];
  foreach ($cats as $c) { if (! term_exists($c, 'product_cat')) { $term = wp_insert_term($c, 'product_cat'); if (! is_wp_error($term)) { add_term_meta($term['term_id'], META_KEY, 1, true); $job['created']['terms'][] = ['tax'=>'product_cat','id'=>(int) $term['term_id']]; } } }
  // Simple products
  $count = min(40, $p['volume']);
  for ($i=0;$i<$count;$i++) {
    $title = 'Aqua Specimen #'.($i+1);
    $dup = get_page_by_title($title, OBJECT, 'product'); if ($dup) continue;
    $pid = wp_insert_post(['post_type'=>'product','post_title'=>$title,'post_status'=>'publish','meta_input'=>[META_KEY=>1]]);
    if (! is_wp_error($pid)) {
      update_post_meta($pid, '_regular_price', (string) (50+$i));
      update_post_meta($pid, '_price', (string) (50+$i));
      wp_set_object_terms($pid, [$cats[$i % count($cats)]], 'product_cat');
      $job['created']['posts'][] = (int) $pid;
    }
  }
  // Attributes
  $attrs = [ 'size' => ['S','M','L'], 'color' => ['Blue','Gold','Emerald'] ];
  foreach ($attrs as $slug => $terms) {
    $tax = 'pa_' . sanitize_title($slug);
    if (function_exists('wc_create_attribute')) {
      $attr_id = function_exists('wc_attribute_taxonomy_id_by_name') ? (int) call_user_func('wc_attribute_taxonomy_id_by_name', $tax) : 0;
      if (! $attr_id) { $res = call_user_func('wc_create_attribute', ['name'=>ucfirst($slug),'slug'=>sanitize_title($slug),'type'=>'select','order_by'=>'menu_order','has_archives'=>false]); if (! is_wp_error($res)) { $attr_id = (int) $res; } }
    }
    if (! taxonomy_exists($tax)) { register_taxonomy($tax, 'product', ['hierarchical'=>false,'label'=>ucfirst($slug),'show_ui'=>false]); }
    if (! term_exists($terms[0], $tax)) { foreach ($terms as $t) { $term = wp_insert_term($t, $tax); if (! is_wp_error($term)) { add_term_meta($term['term_id'], META_KEY, 1, true); $job['created']['terms'][] = ['tax'=>$tax,'id'=>(int) $term['term_id']]; } } }
  }
  // Variable product
  $var_title = 'AquaLuxe Tank Kit'; $dup = get_page_by_title($var_title, OBJECT, 'product');
  $var_id = $dup ? (int) $dup->ID : (int) wp_insert_post(['post_type'=>'product','post_title'=>$var_title,'post_status'=>'publish','meta_input'=>[META_KEY=>1]]);
  if ($var_id) {
    wp_set_object_terms($var_id, 'variable', 'product_type');
    update_post_meta($var_id, '_product_attributes', [ 'pa_size' => ['name'=>'pa_size','value'=>'','position'=>0,'is_visible'=>1,'is_variation'=>1,'is_taxonomy'=>1], 'pa_color' => ['name'=>'pa_color','value'=>'','position'=>1,'is_visible'=>1,'is_variation'=>1,'is_taxonomy'=>1], ]);
    wp_set_object_terms($var_id, ['S','M','L'], 'pa_size');
    wp_set_object_terms($var_id, ['Blue','Gold'], 'pa_color');
    $combos = [ ['S','Blue', 199], ['M','Blue', 249], ['L','Gold', 329] ];
    foreach ($combos as $idx => $c) {
      $v = wp_insert_post(['post_title'=>'Variation','post_name'=>'product-'.$var_id.'-variation-'.$idx,'post_status'=>'publish','post_parent'=>$var_id,'post_type'=>'product_variation','menu_order'=>$idx,'meta_input'=>[META_KEY=>1]]);
      if (! is_wp_error($v)) { update_post_meta($v, 'attribute_pa_size', sanitize_title($c[0])); update_post_meta($v, 'attribute_pa_color', sanitize_title($c[1])); update_post_meta($v, '_regular_price', (string) $c[2]); update_post_meta($v, '_price', (string) $c[2]); update_post_meta($v, '_stock', '10'); update_post_meta($v, '_stock_status', 'instock'); $job['created']['posts'][] = (int) $v; }
    }
    $job['created']['posts'][] = $var_id;
  }
  // Woo core pages
  $ensure = static function(string $opt_key, string $title, string $shortcode) use (&$job): int { $pid = (int) get_option($opt_key, 0); if (! $pid || 'page' !== get_post_type($pid)) { $pid = upsert_page($title, $shortcode); if ($pid) update_option($opt_key, $pid); } if ($pid) $job['created']['posts'][] = $pid; return $pid; };
  $ensure('woocommerce_shop_page_id','Shop','');
  $ensure('woocommerce_cart_page_id','Cart','[woocommerce_cart]');
  $ensure('woocommerce_checkout_page_id','Checkout','[woocommerce_checkout]');
  $ensure('woocommerce_myaccount_page_id','My Account','[woocommerce_my_account]');
}

function ensure_menu_location(string $location, string $name): int {
  $locations = get_theme_mod('nav_menu_locations', []);
  $menu_id = 0; foreach (wp_get_nav_menus() as $m) { if ($m->name === $name) { $menu_id = (int) $m->term_id; break; } }
  if (! $menu_id) { $menu_id = (int) wp_create_nav_menu($name); add_term_meta($menu_id, META_KEY, 1, true); }
  if (! isset($locations[$location]) || (int) $locations[$location] !== $menu_id) { $locations[$location] = $menu_id; set_theme_mod('nav_menu_locations', $locations); }
  return $menu_id;
}

function setup_menus(array &$job, array $p): void {
  $primary = ensure_menu_location('primary', __('Primary Menu','aqualuxe'));
  $footer  = ensure_menu_location('footer', __('Footer Menu','aqualuxe'));
  $account = ensure_menu_location('account', __('Account Menu','aqualuxe'));
  foreach ([ $primary, $footer, $account ] as $id) { if ($id) $job['created']['menus'][] = $id; }
  $add_item = static function(int $menu_id, int $object_id, string $type='post_type', string $title=''): void { $exists=false; foreach (wp_get_nav_menu_items($menu_id) ?: [] as $it){ if ((int)($it->object_id??0)===$object_id){$exists=true;break;} } if ($exists) return; wp_update_nav_menu_item($menu_id, 0, ['menu-item-title'=>$title?:get_the_title($object_id),'menu-item-object'=>'page','menu-item-object-id'=>$object_id,'menu-item-type'=>$type,'menu-item-status'=>'publish']); };
  $ids = [ (int) get_option('page_on_front'), (int) get_option('woocommerce_shop_page_id'), (int) get_option('page_for_posts'), (int) (get_page_by_title('Services')->ID ?? 0), (int) (get_page_by_title('Contact')->ID ?? 0), (int) (get_page_by_title('Wishlist')->ID ?? 0) ];
  foreach (array_filter($ids) as $pid) { $add_item($primary, $pid); }
  foreach (['Privacy Policy','Terms & Conditions','Shipping & Returns','Cookie Policy'] as $lt) { $p = get_page_by_title($lt); if ($p) $add_item($footer, (int) $p->ID); }
  if (class_exists('WooCommerce')) { $myacc = (int) get_option('woocommerce_myaccount_page_id'); if ($myacc) $add_item($account, $myacc); $cart = (int) get_option('woocommerce_cart_page_id'); if ($cart) $add_item($account, $cart); $checkout = (int) get_option('woocommerce_checkout_page_id'); if ($checkout) $add_item($account, $checkout); }
}

function setup_widgets(array &$job, array $p): void {
  $opt_key = 'widget_text';
  $widgets = get_option($opt_key, []);
  if (! is_array($widgets)) { $widgets = []; }
  $instance = ['title'=>'About AquaLuxe','text'=>'Demo widget content.','filter'=>false,'aqlx_demo'=>1];
  $nextIndex = 2;
  foreach ($widgets as $k=>$v) { if (is_numeric($k)) { $nextIndex = max($nextIndex, ((int) $k)+1); } }
  $widgets[$nextIndex] = $instance;
  $widgets['_multiwidget'] = 1;
  update_option($opt_key, $widgets);
  $sidebars = wp_get_sidebars_widgets();
  $targetSidebar = 'sidebar-1';
  if (empty($sidebars[$targetSidebar])) { foreach ($sidebars as $k=>$v){ if ($k !== 'wp_inactive_widgets') { $targetSidebar = $k; break; } } }
  $sidebars[$targetSidebar][] = 'text-' . $nextIndex;
  wp_set_sidebars_widgets($sidebars);
}

function create_services_events(array &$job, array $p): void {
  if (in_array('services', $p['entities'], true)) { foreach (['Aquarium Design','Maintenance Plan'] as $t){ $pid = wp_insert_post(['post_type'=>'service','post_title'=>$t,'post_status'=>'publish','meta_input'=>[META_KEY=>1]]); if (! is_wp_error($pid)) $job['created']['posts'][] = (int) $pid; } }
  if (in_array('events', $p['entities'], true)) { $pid = wp_insert_post(['post_type'=>'event','post_title'=>'Aquascaping Workshop','post_status'=>'publish','meta_input'=>[META_KEY=>1]]); if (! is_wp_error($pid)) $job['created']['posts'][] = (int) $pid; }
}

function finalize_site(array &$job, array $p): void { /* reserved for cache flush etc. */ }

function rollback_created(array $job): void {
  foreach (($job['created']['posts'] ?? []) as $pid) { wp_delete_post((int) $pid, true); }
  foreach (($job['created']['menus'] ?? []) as $mid) { wp_delete_nav_menu((int) $mid); }
  foreach (($job['created']['users'] ?? []) as $uid) { if ($uid && get_userdata($uid)) { wp_delete_user((int) $uid); } }
  foreach (($job['created']['terms'] ?? []) as $t) { if (is_array($t) && isset($t['id'],$t['tax'])) { wp_delete_term((int) $t['id'], $t['tax']); } }
  foreach (($job['created']['media'] ?? []) as $aid) { wp_delete_attachment((int) $aid, true); }
}

// =========================
// Scheduled task and hard flush
// =========================

add_action('aqlx_demo_scheduled_reset', static function(): void {
  hard_flush();
  $p = get_option('aqlx_demo_last_params', ['entities'=>['pages','posts','products','services','events','media','menus','widgets','users'],'volume'=>50,'locale'=>'en','media'=>true,'rollback'=>false]);
  $jobStart = ['status'=>'running','progress'=>0,'params'=>$p,'created'=>['posts'=>[], 'menus'=>[], 'users'=>[], 'terms'=>[], 'media'=>[]],'step'=>0,'total_steps'=>10,'log'=>[]];
  save_job($jobStart);
  while (! run_next_step()['done']){}
});

add_action('aqualuxe/importer/run', function ($args = []) { $p = ['entities'=>['pages','posts','products','services','events','media','menus','widgets','users'],'volume'=>50,'locale'=>'en','media'=>true,'rollback'=>false]; $jobStart = ['status'=>'running','progress'=>0,'params'=>$p,'created'=>['posts'=>[], 'menus'=>[], 'users'=>[], 'terms'=>[], 'media'=>[]],'step'=>0,'total_steps'=>10,'log'=>[]]; save_job($jobStart); while (! run_next_step()['done']){} });
add_action('aqualuxe/importer/flush', __NAMESPACE__ . '\\hard_flush');

function hard_flush(): void
{
  // Delete posts flagged as demo (including product variations)
  $q = new \WP_Query(['post_type'=>['post','page','product','service','event','product_variation'],'posts_per_page'=>-1,'post_status'=>'any','meta_key'=>META_KEY,'meta_value'=>1,'fields'=>'ids']);
  foreach ($q->posts as $pid) { wp_delete_post((int) $pid, true); }
  // Delete menus flagged as demo
  foreach (wp_get_nav_menus() as $menu) { if (get_term_meta($menu->term_id, META_KEY, true)) { wp_delete_nav_menu($menu->term_id); } }
  // Delete demo media
  $mq = new \WP_Query(['post_type'=>'attachment','posts_per_page'=>-1,'post_status'=>'any','meta_key'=>META_KEY,'meta_value'=>1,'fields'=>'ids']);
  foreach ($mq->posts as $aid) { wp_delete_attachment((int) $aid, true); }
  // Remove widgets we added
  $widgets = get_option('widget_text', []); if (is_array($widgets)) { foreach ($widgets as $key=>$cfg){ if (!empty($cfg['aqlx_demo'])) unset($widgets[$key]); } update_option('widget_text', $widgets); }
  // Reset menu locations if their menus are gone
  $locations = get_theme_mod('nav_menu_locations', []); foreach ($locations as $loc=>$mid){ if (! get_term($mid, 'nav_menu')) { unset($locations[$loc]); } } set_theme_mod('nav_menu_locations', $locations);
  // Optionally remove users with our marker role
  $u = get_users(['role__in'=>['support_agent']]); foreach ($u as $user) { wp_delete_user($user->ID); }
}
