<?php
namespace AquaLuxe\Modules\DemoImporter;

if (!defined('ABSPATH')) exit;

/**
 * AquaLuxe Demo Importer - Comprehensive, restartable importer with flush/reset, preview, export, and scheduling.
 */
class Module {
    private const STATE_OPTION = 'aqlx_demo_state';
    private const META_FLAG = '_aqlx_demo';
    private const ROLE_FLAG = 'aqlx_demo_role';
    private const CRON_HOOK = 'aqlx_demo_schedule_run';

    public static function boot(): void {
        add_action('init', [__CLASS__, 'register_types']);
        add_action('admin_menu', [__CLASS__, 'menu']);
        add_action('admin_post_aqlx_demo_start', [__CLASS__, 'start']);
        add_action('admin_post_aqlx_demo_export', [__CLASS__, 'export']);
        add_action('admin_post_aqlx_demo_flush', [__CLASS__, 'flush_action']);
        add_action('admin_post_aqlx_demo_schedule', [__CLASS__, 'schedule_action']);
        add_action('wp_ajax_aqlx_demo_next_step', [__CLASS__, 'next_step_ajax']);
        add_action('wp_ajax_aqlx_demo_status', [__CLASS__, 'status_ajax']);
    add_action('wp_ajax_aqlx_demo_preview', [__CLASS__, 'preview_ajax']);
        add_action(self::CRON_HOOK, [__CLASS__, 'cron_run']);
    }

    public static function register_types(): void {
        // Register a minimal Service CPT so importer can populate it.
        register_post_type('service', [
            'label' => __('Services','aqualuxe'),
            'public' => true,
            'show_in_rest' => true,
            'supports' => ['title','editor','thumbnail','excerpt'],
            'rewrite' => ['slug' => 'services'],
        ]);
        register_taxonomy('service_cat', ['service'], [
            'label' => __('Service Categories','aqualuxe'),
            'public' => true,
            'hierarchical' => true,
            'show_in_rest' => true,
        ]);
    }

    public static function menu(): void {
        add_theme_page(__('AquaLuxe Demo Import','aqualuxe'), __('Demo Import','aqualuxe'), 'manage_options', 'aqlx-demo-import', [__CLASS__, 'page']);
    }

    public static function page(): void {
        if (!current_user_can('manage_options')) return;
        $state = self::get_state();
        $running = ($state['status'] ?? 'idle') === 'running';
        $percent = (int)($state['progress'] ?? 0);
        echo '<div class="wrap"><h1>' . esc_html__('AquaLuxe Demo Importer','aqualuxe') . '</h1>';
        echo '<p>' . esc_html__('Import complete demo data, or flush and re-initialize. Supports selective import, preview, export, and scheduling.','aqualuxe') . '</p>';

        // Controls
        echo '<h2 class="title">' . esc_html__('Importer','aqualuxe') . '</h2>';
        echo '<form id="aqlx-demo-form" method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        wp_nonce_field('aqlx_demo_start');
        echo '<input type="hidden" name="action" value="aqlx_demo_start" />';
        echo '<fieldset><legend>' . esc_html__('Select what to import:','aqualuxe') . '</legend>';
        $checks = [
            'media' => 'Media library',
            'pages' => 'Pages',
            'posts' => 'Posts',
            'services' => 'Services (CPT)',
            'tax' => 'Taxonomies',
            'users' => 'Users & Roles',
            'menus' => 'Menus',
            'widgets' => 'Widgets',
            'woo_simple' => 'WooCommerce Products (Simple)',
            'woo_variable' => 'WooCommerce Products (Variable)',
            'settings' => 'Site Settings (homepage, reading)',
        ];
        foreach ($checks as $k=>$label) {
            echo '<label style="display:block;margin:6px 0"><input type="checkbox" name="cfg['.esc_attr($k).']" value="1" checked> ' . esc_html__($label,'aqualuxe') . '</label>';
        }
        echo '<label style="display:block;margin:6px 0">' . esc_html__('Counts (approximate per type):','aqualuxe') . ' <input type="number" name="cfg[count]" value="12" min="1" max="200" style="width:80px" /></label>';
    echo '<p><button class="button button-primary" '.($running?'disabled':'').'>' . esc_html($running?__('Import Running…','aqualuxe'):__('Start Import','aqualuxe')) . '</button> <button id="aqlx-demo-preview" type="button" class="button">' . esc_html__('Preview Plan','aqualuxe') . '</button></p>';
        echo '</fieldset>';
        echo '</form>';

        echo '<div id="aqlx-demo-progress" style="margin:10px 0;' . ($running?'':'display:none;') . '"><progress max="100" value="'.$percent.'"></progress> <span>'.esc_html($percent)."%".'</span><div id="aqlx-demo-log" style="max-height:180px;overflow:auto;border:1px solid #ccd0d4;padding:6px;margin-top:6px;background:#fff"></div></div>';

        // Flush / reset section
        echo '<hr/><h2>' . esc_html__('Flush / Reset','aqualuxe') . '</h2>';
        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        wp_nonce_field('aqlx_demo_flush');
        echo '<input type="hidden" name="action" value="aqlx_demo_flush" />';
        echo '<p><label><input type="checkbox" name="demo_only" value="1" checked> ' . esc_html__('Delete only data created by the importer (recommended).','aqualuxe') . '</label></p>';
        echo '<p><label>' . esc_html__('Type RESET ALL to fully wipe site content (dangerous):','aqualuxe') . ' <input type="text" name="confirm" placeholder="RESET ALL" /></label></p>';
        echo '<p><button class="button button-secondary">' . esc_html__('Run Flush','aqualuxe') . '</button></p>';
        echo '</form>';

        // Export
        echo '<hr/><h2>' . esc_html__('Export Demo Data','aqualuxe') . '</h2>';
        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        wp_nonce_field('aqlx_demo_export');
        echo '<input type="hidden" name="action" value="aqlx_demo_export" />';
        echo '<p><button class="button">' . esc_html__('Export JSON','aqualuxe') . '</button></p>';
        echo '</form>';

        // Schedule
        echo '<hr/><h2>' . esc_html__('Schedule Automated Re-initializations','aqualuxe') . '</h2>';
        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        wp_nonce_field('aqlx_demo_schedule');
        echo '<input type="hidden" name="action" value="aqlx_demo_schedule" />';
        echo '<label>' . esc_html__('Recurrence','aqualuxe') . ': <select name="recurrence"><option value="hourly">'.esc_html__('Hourly','aqualuxe').'</option><option value="twicedaily">'.esc_html__('Twice Daily','aqualuxe').'</option><option value="daily">'.esc_html__('Daily','aqualuxe').'</option><option value="weekly">'.esc_html__('Weekly','aqualuxe').'</option></select></label> ';
        echo '<button class="button">' . esc_html__('Schedule','aqualuxe') . '</button>';
        echo '</form>';

      // Admin inline JS for progress control
      $ajax = admin_url('admin-ajax.php');
      $nonce = wp_create_nonce('aqlx_demo_ajax');
      $ajax_js = wp_json_encode($ajax);
      $nonce_js = wp_json_encode($nonce);
      $msg_start = esc_js(__('Import started…','aqualuxe'));
      $msg_done = esc_js(__('Import complete.','aqualuxe'));
      $auto = $running ? 'poll(); setTimeout(step, 100);' : '';
      $js = "(function(){\n" .
              "const form=document.getElementById('aqlx-demo-form');\n" .
              "const submitUrl=form?form.getAttribute('action'):'';\n" .
          "const prog=document.getElementById('aqlx-demo-progress');\n" .
          "const log=document.getElementById('aqlx-demo-log');\n" .
          "function append(msg){if(!log)return;var p=document.createElement('div');p.textContent=msg;log.appendChild(p);log.scrollTop=log.scrollHeight;}\n" .
          "const previewBtn=document.getElementById('aqlx-demo-preview');\n" .
              "async function poll(){try{const r=await fetch(".$ajax_js."+'?action=aqlx_demo_status&nonce='+".$nonce_js.",{credentials:'same-origin'});const d=await r.json();if(d&&d.success){if(prog){prog.style.display='';prog.querySelector('progress').value=d.data.progress||0;prog.querySelector('span').textContent=(d.data.progress||0)+'%';}if(d.data.message)append(d.data.message);if(d.data.status==='running'){setTimeout(poll,1000);} }}catch(e){}}\n" .
              "if(form){form.addEventListener('submit',async function(e){e.preventDefault();const fd=new FormData(form);try{const r=await fetch(submitUrl,{method:'POST',body:fd,credentials:'same-origin'});const d=await r.json();if(d&&d.success){append('".$msg_start."');poll();step();}}catch(err){append('Error: '+err.message);}});}\n" .
          "if(previewBtn&&form){previewBtn.addEventListener('click',async function(){const fd=new FormData(form);fd.set('action','aqlx_demo_preview');fd.append('nonce',".$nonce_js.");try{const r=await fetch(".$ajax_js.",{method:'POST',body:fd,credentials:'same-origin'});const d=await r.json();if(d&&d.success){append('--- Preview ---');append(d.data.summary);if(d.data.samples){d.data.samples.forEach(s=>append('• '+s));}append('--- End Preview ---');}else{append('Preview failed');}}catch(e){append('Preview error: '+e.message);}});}\n" .
          "async function step(){try{const r=await fetch(".$ajax_js."+'?action=aqlx_demo_next_step&nonce='+".$nonce_js.",{method:'POST',credentials:'same-origin'});const d=await r.json();if(d&&d.success){if(d.data.done){append('".$msg_done."');poll();return;}append(d.data.message||'step ok');poll();setTimeout(step,100);}}catch(e){append('Error: '+e.message);}}\n" .
          $auto .
          "})();";
      echo '<script>' . $js . '</script>';

        echo '</div>';
    }

    // Start import: initialize state and steps
    public static function start(): void {
        if (!current_user_can('manage_options')) wp_send_json_error(['message'=>'forbidden'], 403);
        check_admin_referer('aqlx_demo_start');
        $cfg = isset($_POST['cfg']) && is_array($_POST['cfg']) ? array_map('sanitize_text_field', wp_unslash($_POST['cfg'])) : [];
        $count = max(1, min(200, (int)($cfg['count'] ?? 12)));
        $sections = [
            'media' => !empty($cfg['media']),
            'pages' => !empty($cfg['pages']),
            'posts' => !empty($cfg['posts']),
            'services' => !empty($cfg['services']),
            'tax' => !empty($cfg['tax']),
            'users' => !empty($cfg['users']),
            'menus' => !empty($cfg['menus']),
            'widgets' => !empty($cfg['widgets']),
            'woo_simple' => !empty($cfg['woo_simple']),
            'woo_variable' => !empty($cfg['woo_variable']),
            'settings' => !empty($cfg['settings']),
        ];
        $steps = [];
        if ($sections['media']) $steps[] = ['action'=>'create_media'];
        if ($sections['tax']) $steps[] = ['action'=>'create_tax'];
        if ($sections['users']) $steps[] = ['action'=>'create_users'];
        if ($sections['pages']) $steps[] = ['action'=>'create_pages'];
        if ($sections['posts']) $steps[] = ['action'=>'create_posts'];
        if ($sections['services']) $steps[] = ['action'=>'create_services'];
        if ($sections['woo_simple']) $steps[] = ['action'=>'create_products_simple'];
        if ($sections['woo_variable']) $steps[] = ['action'=>'create_products_variable'];
        if ($sections['menus']) $steps[] = ['action'=>'create_menus'];
        if ($sections['widgets']) $steps[] = ['action'=>'create_widgets'];
        if ($sections['settings']) $steps[] = ['action'=>'apply_settings'];

        $state = [
            'status' => 'running',
            'progress' => 0,
            'steps' => $steps,
            'index' => 0,
            'count' => $count,
            'sections' => $sections,
            'created' => [ 'posts'=>[], 'terms'=>[], 'media'=>[], 'users'=>[], 'menus'=>[], 'widgets'=>[] ],
            'errors' => [],
            'started_at' => time(),
        ];
        update_option(self::STATE_OPTION, $state, false);
        wp_send_json_success(['status'=>'running']);
    }

    public static function status_ajax(): void {
        if (!current_user_can('manage_options')) wp_send_json_error(['message'=>'forbidden'], 403);
        check_ajax_referer('aqlx_demo_ajax','nonce');
        $s = self::get_state();
        wp_send_json_success(['status'=>$s['status'] ?? 'idle', 'progress'=>(int)($s['progress'] ?? 0), 'message'=>$s['last_message'] ?? '']);
    }

    public static function next_step_ajax(): void {
        if (!current_user_can('manage_options')) wp_send_json_error(['message'=>'forbidden'], 403);
        check_ajax_referer('aqlx_demo_ajax','nonce');
        $state = self::get_state();
        if (($state['status'] ?? '') !== 'running') wp_send_json_success(['done'=>true]);
        $idx = (int)$state['index'];
        $steps = $state['steps'] ?? [];
        if (!isset($steps[$idx])) {
            $state['status'] = 'done';
            $state['progress'] = 100;
            $state['last_message'] = __('All steps complete','aqualuxe');
            update_option(self::STATE_OPTION, $state, false);
            wp_send_json_success(['done'=>true, 'message'=>$state['last_message']]);
        }
        $msg = self::run_step($state, $steps[$idx]['action']);
        update_option(self::STATE_OPTION, $state, false);
        wp_send_json_success(['done'=>($state['status']!=='running'), 'message'=>$msg]);
    }

    private static function run_step(array &$state, string $action): string {
        $ok = true; $msg = '';
        // Performance hints during batch ops
        if (function_exists('wp_defer_term_counting')) wp_defer_term_counting(true);
        if (function_exists('wp_defer_comment_counting')) wp_defer_comment_counting(true);
        if (function_exists('wp_suspend_cache_invalidation')) wp_suspend_cache_invalidation(true);
        try {
            switch ($action) {
                case 'create_media': $msg = self::step_create_media($state); break;
                case 'create_tax': $msg = self::step_create_tax($state); break;
                case 'create_users': $msg = self::step_create_users($state); break;
                case 'create_pages': $msg = self::step_create_pages($state); break;
                case 'create_posts': $msg = self::step_create_posts($state); break;
                case 'create_services': $msg = self::step_create_services($state); break;
                case 'create_products_simple': $msg = self::step_create_products_simple($state); break;
                case 'create_products_variable': $msg = self::step_create_products_variable($state); break;
                case 'create_menus': $msg = self::step_create_menus($state); break;
                case 'create_widgets': $msg = self::step_create_widgets($state); break;
                case 'apply_settings': $msg = self::step_apply_settings($state); break;
                default: $msg = __('Unknown step','aqualuxe');
            }
        } catch (\Throwable $e) {
            $ok = false; $msg = 'Error: ' . $e->getMessage();
            $state['errors'][] = $msg;
            self::rollback($state);
            $state['status'] = 'error';
        }
        if (function_exists('wp_defer_term_counting')) wp_defer_term_counting(false);
        if (function_exists('wp_defer_comment_counting')) wp_defer_comment_counting(false);
        if (function_exists('wp_suspend_cache_invalidation')) wp_suspend_cache_invalidation(false);
        if ($ok) {
            $state['index'] = ((int)$state['index']) + 1;
            $total = max(1, count($state['steps'] ?? []));
            $state['progress'] = min(100, (int)floor(($state['index'] / $total) * 100));
            $state['last_message'] = $msg;
        }
        return $msg;
    }

    public static function export(): void {
        if (!current_user_can('manage_options')) wp_die('Forbidden', 403);
        check_admin_referer('aqlx_demo_export');
        $data = self::collect_demo_data();
        $json = wp_json_encode($data, JSON_PRETTY_PRINT);
        nocache_headers();
        header('Content-Type: application/json; charset=' . get_option('blog_charset'));
        header('Content-Disposition: attachment; filename=aqualuxe-demo-export.json');
        echo $json; exit;
    }

    public static function flush_action(): void {
        if (!current_user_can('manage_options')) wp_die('Forbidden', 403);
        check_admin_referer('aqlx_demo_flush');
        $demo_only = !empty($_POST['demo_only']);
        $confirm = isset($_POST['confirm']) ? sanitize_text_field(wp_unslash($_POST['confirm'])) : '';
        $full_reset = ($confirm === 'RESET ALL');
        self::flush($demo_only, $full_reset);
        wp_safe_redirect(admin_url('themes.php?page=aqlx-demo-import&flushed=1'));
        exit;
    }

    public static function schedule_action(): void {
        if (!current_user_can('manage_options')) wp_die('Forbidden', 403);
        check_admin_referer('aqlx_demo_schedule');
        $recurrence = isset($_POST['recurrence']) ? sanitize_text_field(wp_unslash($_POST['recurrence'])) : 'daily';
        if (!in_array($recurrence, ['hourly','twicedaily','daily','weekly'], true)) $recurrence = 'daily';
        wp_clear_scheduled_hook(self::CRON_HOOK);
        wp_schedule_event(time() + 60, $recurrence, self::CRON_HOOK);
        wp_safe_redirect(admin_url('themes.php?page=aqlx-demo-import&scheduled=1'));
        exit;
    }

    public static function cron_run(): void {
        // Safe re-initialization: flush demo-only and re-import with defaults.
        self::flush(true, false);
        $default_cfg = [ 'count'=>12, 'media'=>1,'pages'=>1,'posts'=>1,'services'=>1,'tax'=>1,'users'=>1,'menus'=>1,'widgets'=>1,'woo_simple'=>1,'woo_variable'=>0,'settings'=>1 ];
        // Initialize state (no AJAX/nonces in cron)
        $steps = [ ['action'=>'create_media'],['action'=>'create_tax'],['action'=>'create_users'],['action'=>'create_pages'],['action'=>'create_posts'],['action'=>'create_services'],['action'=>'create_products_simple'],['action'=>'create_menus'],['action'=>'create_widgets'],['action'=>'apply_settings'] ];
        $state = [ 'status'=>'running','progress'=>0,'steps'=>$steps,'index'=>0,'count'=>(int)$default_cfg['count'],'sections'=>$default_cfg,'created'=>['posts'=>[],'terms'=>[],'media'=>[],'users'=>[],'menus'=>[],'widgets'=>[]] ];
        update_option(self::STATE_OPTION, $state, false);
        foreach ($steps as $st) {
            if (($state['status'] ?? '') !== 'running') break;
            self::run_step($state, $st['action']);
            update_option(self::STATE_OPTION, $state, false);
        }
    }

    // --- Step implementations ---
    private static function step_create_media(array &$state): string {
        $count = (int)$state['count'];
        $created = 0;
        for ($i=0; $i<$count; $i++) {
            $attach_id = self::create_placeholder_attachment('aqlx-demo-'.$i.'.png');
            if ($attach_id) { $state['created']['media'][] = $attach_id; update_post_meta($attach_id, self::META_FLAG, 1); $created++; }
        }
        return sprintf(__('Created %d media items','aqualuxe'), $created);
    }

    private static function step_create_tax(array &$state): string {
        $terms = ['Freshwater','Saltwater','Coral','Equipment','Food'];
        foreach ($terms as $t) {
            $term = wp_insert_term($t, 'category');
            if (!is_wp_error($term)) { $state['created']['terms'][] = (int)$term['term_id']; add_term_meta($term['term_id'], self::META_FLAG, 1, true); }
        }
        // Service categories
        foreach (['Installation','Maintenance','Consulting'] as $t) {
            $term = wp_insert_term($t, 'service_cat');
            if (!is_wp_error($term)) { $state['created']['terms'][] = (int)$term['term_id']; add_term_meta($term['term_id'], self::META_FLAG, 1, true); }
        }
        return __('Created taxonomies','aqualuxe');
    }

    private static function step_create_users(array &$state): string {
        // Create a demo customer and a demo shop manager+ role
        add_role('shop_manager_plus', __('Shop Manager Plus','aqualuxe'), get_role('shop_manager') ? get_role('shop_manager')->capabilities : ['read'=>true]);
        $role = get_role('shop_manager_plus');
        if ($role) $role->add_cap('manage_woocommerce');
        $u1 = username_exists('demo_customer') ?: wp_create_user('demo_customer', wp_generate_password(12, true), 'demo_customer@example.com');
        if (!is_wp_error($u1)) { $u = get_user_by('id', $u1); $u->set_role('customer'); update_user_meta($u1, self::META_FLAG, 1); $state['created']['users'][] = (int)$u1; }
        $u2 = username_exists('demo_manager') ?: wp_create_user('demo_manager', wp_generate_password(12, true), 'demo_manager@example.com');
        if (!is_wp_error($u2)) { $u = get_user_by('id', $u2); $u->set_role('shop_manager_plus'); update_user_meta($u2, self::META_FLAG, 1); $state['created']['users'][] = (int)$u2; }
        return __('Created demo users/roles','aqualuxe');
    }

    private static function step_create_pages(array &$state): string {
        $pages = [
            'Home' => [ 'slug' => 'home', 'content' => "[aqlx_services]\n\n[aqlx_events]" ],
            'About' => [ 'slug' => 'about', 'content' => '<h2>Our Story</h2><p>From a local farm to an international brand.</p><h3>Sustainability</h3><p>Ethical sourcing and eco-initiatives.</p>' ],
            'Services' => [ 'slug' => 'services', 'content' => '[aqlx_services]' ],
            'Blog' => [ 'slug' => 'blog', 'content' => '' ],
            'Contact' => [ 'slug' => 'contact', 'content' => '<div class="grid md:grid-cols-2 gap-6"><form class="p-4 border rounded"><label class="block mb-2">Name<input class="mt-1 w-full border rounded px-3 py-2" required></label><label class="block mb-2">Email<input type="email" class="mt-1 w-full border rounded px-3 py-2" required></label><label class="block mb-2">Message<textarea class="mt-1 w-full border rounded px-3 py-2" rows="5"></textarea></label><button class="mt-2 px-4 py-2 bg-sky-600 text-white rounded">Send</button></form><div><div class="aspect-video bg-slate-200 dark:bg-slate-800 flex items-center justify-center">Map</div></div></div>' ],
            'FAQ' => [ 'slug' => 'faq', 'content' => '<h2>FAQ</h2><p>Shipping, care, purchasing, export/import processes.</p>' ],
            'Privacy Policy' => [ 'slug' => 'privacy-policy', 'content' => '<h2>Privacy Policy</h2><p>Placeholder content.</p>' ],
            'Terms & Conditions' => [ 'slug' => 'terms', 'content' => '<h2>Terms & Conditions</h2><p>Placeholder content.</p>' ],
            'Shipping & Returns' => [ 'slug' => 'shipping-returns', 'content' => '<h2>Shipping & Returns</h2><p>Placeholder content.</p>' ],
            'Cookie Policy' => [ 'slug' => 'cookies', 'content' => '<h2>Cookie Policy</h2><p>Placeholder content.</p>' ],
            'Wholesale' => [ 'slug' => 'wholesale', 'content' => '[aqlx_wholesale_app]' ],
            'Auctions' => [ 'slug' => 'auctions', 'content' => '[aqlx_auctions]' ],
            'Franchise' => [ 'slug' => 'franchise', 'content' => '[aqlx_franchise]' ],
            'R&D & Sustainability' => [ 'slug' => 'rnd', 'content' => '[aqlx_rnd]' ],
            'Affiliate' => [ 'slug' => 'affiliate', 'content' => '[aqlx_affiliate]' ],
        ];
        foreach ($pages as $title => $data) {
            $slug = $data['slug'];
            $page = get_page_by_path($slug);
            if ($page) { continue; }
            $id = wp_insert_post([
                'post_title' => $title,
                'post_name' => $slug,
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_content' => $data['content'] ?? '',
                'meta_input' => [ self::META_FLAG => 1 ],
            ]);
            if ($id && !is_wp_error($id)) $state['created']['posts'][] = (int)$id;
        }
        return __('Created pages','aqualuxe');
    }

    private static function step_create_posts(array &$state): string {
        $count = (int)max(3, $state['count']);
        for ($i=1; $i<=$count; $i++) {
            $id = wp_insert_post([
                'post_title' => 'Aqua Blog ' . $i,
                'post_status' => 'publish',
                'post_type' => 'post',
                'post_excerpt' => 'Quick insights about aquaculture #' . $i,
                'post_content' => self::lorem(5),
                'meta_input' => [ self::META_FLAG => 1 ],
            ]);
            if ($id && !is_wp_error($id)) {
                $state['created']['posts'][] = (int)$id;
                $img = self::create_placeholder_attachment('post-'.$i.'.png');
                if ($img) set_post_thumbnail($id, $img);
                $cats = get_terms(['taxonomy'=>'category', 'fields'=>'ids', 'hide_empty'=>false]);
                if ($cats && !is_wp_error($cats)) wp_set_post_terms($id, array_slice($cats, 0, rand(1, min(3, count($cats)))), 'category');
            }
        }
        return sprintf(__('Created %d posts','aqualuxe'), $count);
    }

    private static function step_create_services(array &$state): string {
        $count = (int)max(3, $state['count']);
        for ($i=1; $i<=$count; $i++) {
            $id = wp_insert_post([
                'post_title' => 'Service ' . $i,
                'post_status' => 'publish',
                'post_type' => 'service',
                'post_content' => self::lorem(4),
                'meta_input' => [ self::META_FLAG => 1 ],
            ]);
            if ($id && !is_wp_error($id)) {
                $state['created']['posts'][] = (int)$id;
                $img = self::create_placeholder_attachment('service-'.$i.'.png');
                if ($img) set_post_thumbnail($id, $img);
                $terms = get_terms(['taxonomy'=>'service_cat','fields'=>'ids','hide_empty'=>false]);
                if ($terms && !is_wp_error($terms)) wp_set_post_terms($id, array_slice($terms, 0, rand(1, min(2, count($terms)))), 'service_cat');
            }
        }
        return sprintf(__('Created %d services','aqualuxe'), $count);
    }

    private static function step_create_products_simple(array &$state): string {
        if (!class_exists('WooCommerce')) return __('Skipped (WooCommerce inactive)','aqualuxe');
        $count = (int)max(4, $state['count']);
        $created = 0;
        for ($i=1; $i<=$count; $i++) {
            $id = wp_insert_post([
                'post_title' => 'Fish ' . $i,
                'post_status' => 'publish',
                'post_type' => 'product',
                'meta_input' => [ self::META_FLAG => 1, '_price' => rand(10,200), '_regular_price'=> rand(10,200) ],
            ]);
            if ($id && !is_wp_error($id)) { $state['created']['posts'][] = (int)$id; $created++; $img = self::create_placeholder_attachment('product-'.$i.'.png'); if ($img) set_post_thumbnail($id, $img); }
        }
        return sprintf(__('Created %d simple products','aqualuxe'), $created);
    }

    private static function step_create_products_variable(array &$state): string {
        if (!class_exists('WooCommerce')) return __('Skipped (WooCommerce inactive)','aqualuxe');
        $sizes = ['S','M','L']; $colors=['Red','Blue','Green'];
        $n = (int)max(2, ceil($state['count']/3));
        $created = 0;
        for ($k=1; $k<=$n; $k++) {
            $product_id = wp_insert_post([
                'post_title' => 'Variant Fish ' . $k,
                'post_status' => 'publish',
                'post_type' => 'product',
                'meta_input' => [ self::META_FLAG => 1 ],
            ]);
            if (is_wp_error($product_id) || !$product_id) continue;
            $state['created']['posts'][] = (int)$product_id;
            update_post_meta($product_id, '_visibility', 'visible');
            wp_set_object_terms($product_id, 'variable', 'product_type');

            // Attributes (not global)
            $attr_size = [ 'name' => 'pa_size', 'value' => implode(' | ', $sizes), 'position'=>0, 'is_visible'=>1, 'is_variation'=>1, 'is_taxonomy'=>0 ];
            $attr_color = [ 'name' => 'pa_color', 'value' => implode(' | ', $colors), 'position'=>1, 'is_visible'=>1, 'is_variation'=>1, 'is_taxonomy'=>0 ];
            update_post_meta($product_id, '_product_attributes', [ 'size' => $attr_size, 'color' => $attr_color ]);

            // Variations
            foreach ($sizes as $s) {
                foreach ($colors as $c) {
                    $variation_id = wp_insert_post([
                        'post_title' => 'Variation',
                        'post_name' => 'variation-' . $product_id . '-' . sanitize_title($s.'-'.$c),
                        'post_status' => 'publish',
                        'post_parent' => $product_id,
                        'post_type' => 'product_variation',
                        'meta_input' => [ self::META_FLAG => 1 ],
                    ]);
                    if ($variation_id) {
                        update_post_meta($variation_id, 'attribute_size', $s);
                        update_post_meta($variation_id, 'attribute_color', $c);
                        $price = rand(10,200);
                        update_post_meta($variation_id, '_regular_price', $price);
                        update_post_meta($variation_id, '_price', $price);
                    }
                }
            }
            $img = self::create_placeholder_attachment('product-var-'.$k.'.png');
            if ($img) set_post_thumbnail($product_id, $img);
            $created++;
        }
        return sprintf(__('Created %d variable products','aqualuxe'), $created);
    }

    private static function step_create_menus(array &$state): string {
    $existing = wp_get_nav_menu_object('Primary');
    $menu_id = $existing ? (int)$existing->term_id : (int)wp_create_nav_menu('Primary');
        if (!is_wp_error($menu_id)) {
            $home = get_page_by_path('home');
            if ($home) wp_update_nav_menu_item($menu_id, 0, [ 'menu-item-type'=>'post_type', 'menu-item-object'=>'page', 'menu-item-object-id'=>$home->ID, 'menu-item-status'=>'publish' ]);
            $shop = function_exists('wc_get_page_id') ? call_user_func('wc_get_page_id','shop') : 0;
            if ($shop && $shop>0) wp_update_nav_menu_item($menu_id, 0, [ 'menu-item-type'=>'post_type', 'menu-item-object'=>'page', 'menu-item-object-id'=>$shop, 'menu-item-status'=>'publish' ]);
            set_theme_mod('nav_menu_locations', array_merge((array)get_theme_mod('nav_menu_locations', []), [ 'primary' => (int)$menu_id ]));
            $state['created']['menus'][] = (int)$menu_id;
        }
        return __('Created primary menu','aqualuxe');
    }

    private static function step_create_widgets(array &$state): string {
        $sidebars = wp_get_sidebars_widgets();
        $widgets = get_option('widget_search', []);
        $next = 2; // 1 reserved for core sometimes
        $widgets[$next] = [ 'title' => __('Search','aqualuxe') ];
        update_option('widget_search', $widgets);
        $sidebars['sidebar-1'] = isset($sidebars['sidebar-1']) && is_array($sidebars['sidebar-1']) ? $sidebars['sidebar-1'] : [];
        array_unshift($sidebars['sidebar-1'], 'search-' . $next);
        wp_set_sidebars_widgets($sidebars);
        $state['created']['widgets'][] = 'search-' . $next;
        return __('Added widgets','aqualuxe');
    }

    private static function step_apply_settings(array &$state): string {
        $home = get_page_by_path('home');
        $blog = get_page_by_path('blog');
        if ($home && $blog) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $home->ID);
            update_option('page_for_posts', $blog->ID);
        }
        return __('Applied site settings','aqualuxe');
    }

    private static function rollback(array $state): void {
        // Best effort: delete last created entities
        foreach (['posts','media'] as $k) {
            $ids = array_splice($state['created'][$k] ?? [], -5); // last few
            foreach ($ids as $id) { wp_delete_post((int)$id, true); }
        }
    }

    private static function flush(bool $demo_only, bool $full_reset): void {
        if ($full_reset) {
            // Dangerous: wipe most content types except admin user and theme options
            $ptypes = get_post_types(['public'=>true], 'names');
            foreach ($ptypes as $pt) {
                $q = new \WP_Query(['post_type'=>$pt,'posts_per_page'=>-1,'post_status'=>'any','fields'=>'ids','no_found_rows'=>true]);
                foreach ($q->posts as $pid) { wp_delete_post((int)$pid, true); }
            }
            // Terms
            foreach (get_taxonomies([], 'names') as $tx) {
                $terms = get_terms(['taxonomy'=>$tx,'hide_empty'=>false]);
                if (is_wp_error($terms)) continue;
                foreach ($terms as $t) { wp_delete_term($t->term_id, $tx); }
            }
            // Menus
            foreach (wp_get_nav_menus() as $m) { wp_delete_nav_menu($m); }
            // Widgets
            wp_set_sidebars_widgets([]);
        } else {
            // Remove only demo-flagged items
            $q = new \WP_Query(['meta_key'=>self::META_FLAG,'meta_value'=>1,'post_type'=>'any','post_status'=>'any','posts_per_page'=>-1,'fields'=>'ids','no_found_rows'=>true]);
            foreach ($q->posts as $pid) { wp_delete_post((int)$pid, true); }
            // Attachments flagged
            $q2 = new \WP_Query(['meta_key'=>self::META_FLAG,'meta_value'=>1,'post_type'=>'attachment','post_status'=>'any','posts_per_page'=>-1,'fields'=>'ids','no_found_rows'=>true]);
            foreach ($q2->posts as $pid) { wp_delete_attachment((int)$pid, true); }
            // Terms flagged
            foreach (get_taxonomies([], 'names') as $tx) {
                $terms = get_terms(['taxonomy'=>$tx,'meta_key'=>self::META_FLAG,'meta_value'=>1,'hide_empty'=>false]);
                if (!is_wp_error($terms)) foreach ($terms as $t) { wp_delete_term($t->term_id, $tx); }
            }
            // Users flagged
            $users = get_users(['meta_key'=>self::META_FLAG,'meta_value'=>1,'fields'=>'ids']);
            foreach ($users as $uid) { wp_delete_user((int)$uid); }
        }
        // Remove custom roles and clear schedule
        remove_role('shop_manager_plus');
        wp_clear_scheduled_hook(self::CRON_HOOK);
        // Clear state
        delete_option(self::STATE_OPTION);
    }

    public static function preview_ajax(): void {
        if (!current_user_can('manage_options')) wp_send_json_error(['message'=>'forbidden'], 403);
        check_ajax_referer('aqlx_demo_ajax','nonce');
        // Build a simple summary
        $summary = __('Preview of planned operations:','aqualuxe');
        $samples = [
            'Pages: Home, About, Services, Blog, Contact…',
            'Posts: Aqua Blog 1…N',
            'Services: Service 1…N with categories',
            'Woo: Fish 1…N (simple), Variable Fish with sizes/colors',
            'Menus: Primary (Home, Shop if available)',
            'Widgets: Search in sidebar-1',
        ];
        wp_send_json_success(['summary'=>$summary, 'samples'=>$samples]);
    }

    private static function collect_demo_data(): array {
        $data = [ 'posts'=>[], 'terms'=>[], 'media'=>[], 'users'=>[], 'menus'=>[] ];
        $q = new \WP_Query(['meta_key'=>self::META_FLAG,'meta_value'=>1,'post_type'=>'any','post_status'=>'any','posts_per_page'=>-1,'no_found_rows'=>true]);
        foreach ($q->posts as $p) {
            $data['posts'][] = [ 'ID'=>$p->ID, 'type'=>$p->post_type, 'title'=>$p->post_title, 'slug'=>$p->post_name ];
        }
        foreach (get_taxonomies([], 'names') as $tx) {
            $terms = get_terms(['taxonomy'=>$tx,'meta_key'=>self::META_FLAG,'meta_value'=>1,'hide_empty'=>false]);
            if (is_wp_error($terms)) continue;
            foreach ($terms as $t) $data['terms'][] = [ 'id'=>$t->term_id, 'taxonomy'=>$tx, 'name'=>$t->name, 'slug'=>$t->slug ];
        }
        $users = get_users(['meta_key'=>self::META_FLAG,'meta_value'=>1]);
        foreach ($users as $u) { $data['users'][] = [ 'ID'=>$u->ID, 'login'=>$u->user_login, 'role'=>implode(',', $u->roles) ]; }
        // Media
        $q2 = new \WP_Query(['meta_key'=>self::META_FLAG,'meta_value'=>1,'post_type'=>'attachment','post_status'=>'any','posts_per_page'=>-1,'no_found_rows'=>true]);
        foreach ($q2->posts as $m) { $data['media'][] = [ 'ID'=>$m->ID, 'file'=>get_attached_file($m->ID), 'url'=>wp_get_attachment_url($m->ID) ]; }
        // Menus
        foreach (wp_get_nav_menus() as $m) { $data['menus'][] = [ 'term_id'=>$m->term_id, 'name'=>$m->name, 'count'=>$m->count ]; }
        return $data;
    }

    private static function create_placeholder_attachment(string $filename): int {
        $upload = wp_upload_dir();
        $path = trailingslashit($upload['path']);
        if (!wp_mkdir_p($path)) return 0;
        $file = $path . $filename;
        if (!file_exists($file)) file_put_contents($file, base64_decode(self::PNG_1x1));
        $wp_filetype = wp_check_filetype(basename($file), null);
        $attachment = [ 'post_mime_type'=>$wp_filetype['type'], 'post_title'=>sanitize_file_name($filename), 'post_content'=>'', 'post_status'=>'inherit', 'meta_input'=>[ self::META_FLAG => 1 ] ];
        $attach_id = wp_insert_attachment($attachment, $file);
        if (!function_exists('wp_generate_attachment_metadata')) require_once ABSPATH . 'wp-admin/includes/image.php';
        $attach_data = wp_generate_attachment_metadata($attach_id, $file);
        wp_update_attachment_metadata($attach_id, $attach_data);
        return (int)$attach_id;
    }

    private static function lorem(int $paras = 3): string {
        $p = [];
        for ($i=0; $i<$paras; $i++) {
            $p[] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
        }
        return '<p>' . implode('</p><p>', $p) . '</p>';
    }

    private const PNG_1x1 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMB/YEQ1jEAAAAASUVORK5CYII=';
    private static function get_state(): array { return get_option(self::STATE_OPTION, []); }
}
