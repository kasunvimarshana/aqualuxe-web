<?php
namespace AQLX\DemoImporter;

if (!defined('ABSPATH')) { exit; }

class Rest {
    public static function init(): void {
        add_action('rest_api_init', [__CLASS__, 'register']);
    }

    public static function register(): void {
        $ns = 'aqlxdi/v1';
        register_rest_route($ns, '/start', [
            'methods' => 'POST',
            'callback' => [__CLASS__, 'start'],
            'permission_callback' => [__CLASS__, 'can_manage'],
        ]);
        register_rest_route($ns, '/step', [
            'methods' => 'POST',
            'callback' => [__CLASS__, 'step'],
            'permission_callback' => [__CLASS__, 'can_manage'],
        ]);
        register_rest_route($ns, '/state', [
            'methods' => 'GET',
            'callback' => [__CLASS__, 'state'],
            'permission_callback' => [__CLASS__, 'can_manage'],
        ]);
        register_rest_route($ns, '/pause', [
            'methods' => 'POST',
            'callback' => [__CLASS__, 'pause'],
            'permission_callback' => [__CLASS__, 'can_manage'],
        ]);
        register_rest_route($ns, '/resume', [
            'methods' => 'POST',
            'callback' => [__CLASS__, 'resume'],
            'permission_callback' => [__CLASS__, 'can_manage'],
        ]);
        register_rest_route($ns, '/cancel', [
            'methods' => 'POST',
            'callback' => [__CLASS__, 'cancel'],
            'permission_callback' => [__CLASS__, 'can_manage'],
        ]);
        register_rest_route($ns, '/preview', [
            'methods' => 'POST',
            'callback' => [__CLASS__, 'preview'],
            'permission_callback' => [__CLASS__, 'can_manage'],
        ]);
        register_rest_route($ns, '/flush', [
            'methods' => 'POST',
            'callback' => [__CLASS__, 'flush'],
            'permission_callback' => [__CLASS__, 'can_manage'],
        ]);
        register_rest_route($ns, '/export', [
            'methods' => 'POST',
            'callback' => [__CLASS__, 'export'],
            'permission_callback' => [__CLASS__, 'can_manage'],
        ]);
        register_rest_route($ns, '/schedule', [
            'methods' => 'POST',
            'callback' => [__CLASS__, 'schedule'],
            'permission_callback' => [__CLASS__, 'can_manage'],
        ]);
        register_rest_route($ns, '/schedule/clear', [
            'methods' => 'POST',
            'callback' => [__CLASS__, 'schedule_clear'],
            'permission_callback' => [__CLASS__, 'can_manage'],
        ]);
    }

    public static function can_manage(): bool { return current_user_can('manage_options'); }

    private static function &state_ref(): array {
        $s = get_option('aqlxdi_state', []);
        if (!is_array($s)) { $s = []; }
        return $s;
    }

    private static function persist(array $state): void { update_option('aqlxdi_state', $state, false); }

    private static function ensure_audit(array &$state): void {
        if (!isset($state['run_id'])) { $state['run_id'] = wp_generate_password(8, false, false); }
        if (empty($state['audit_file'])) {
            $upload = wp_upload_dir();
            $dir = trailingslashit($upload['basedir']) . 'aqlxdi';
            if (!is_dir($dir)) { wp_mkdir_p($dir); }
            $state['audit_file'] = $dir . '/run-' . $state['run_id'] . '.jsonl';
            $state['audit_url'] = trailingslashit($upload['baseurl']) . 'aqlxdi/run-' . $state['run_id'] . '.jsonl';
        }
    }

    private static function audit(array $state, string $event, array $payload = []): void {
        if (empty($state['audit_file'])) return;
        $row = [ 'ts' => gmdate('c'), 'run' => (string)($state['run_id'] ?? ''), 'event' => $event, 'payload' => $payload ];
        try { file_put_contents($state['audit_file'], wp_json_encode($row) . "\n", FILE_APPEND | LOCK_EX); } catch (\Throwable $e) {}
    }

    public static function start(\WP_REST_Request $req) {
        check_ajax_referer('wp_rest');
        $p = $req->get_json_params();
        $entities = array_map('sanitize_key', (array)($p['entities'] ?? []));
        $state = [
            'run_id' => wp_generate_password(8, false, false),
            'steps' => self::build_steps($entities),
            'index' => 0,
            'volume' => max(1, absint($p['volume'] ?? 10)),
            'policy' => in_array($p['policy'] ?? 'skip', ['skip','merge','overwrite'], true) ? $p['policy'] : 'skip',
            'locale' => sanitize_text_field($p['locale'] ?? 'en_US'),
            'provider' => sanitize_key($p['provider'] ?? 'wikimedia'),
            'paused' => false,
            'created' => [ 'posts' => [], 'terms' => [], 'users' => [], 'roles' => [], 'widgets' => [] ],
            'options_backup' => [],
            'step_state' => [],
        ];
        self::ensure_audit($state); self::audit($state, 'start', ['entities' => $entities]);
        self::persist($state);
        return new \WP_REST_Response(['ok' => true, 'state' => self::public_state($state)], 200);
    }

    private static function build_steps(array $entities): array {
        $map = [
            'pages','posts','cpts','products','media','menus','widgets','options','users','roles'
        ];
        if (!$entities) return $map; // default all
        return array_values(array_intersect($map, $entities));
    }

    private static function public_state(array $state): array {
        $steps = (array)($state['steps'] ?? []);
        $i = (int)($state['index'] ?? 0);
        $count = max(1, count($steps));
        $partial = (float)($state['partial'] ?? 0.0);
        $pct = min(100, max(0, (int) floor(((min($i, $count) + $partial) / $count) * 100)));
        return [
            'progress' => $pct,
            'paused' => (bool)($state['paused'] ?? false),
            'done' => $i >= count($steps),
            'audit_url' => (string)($state['audit_url'] ?? ''),
            'step' => $steps[$i] ?? null,
        ];
    }

    public static function state(\WP_REST_Request $req) { $s = self::state_ref(); return self::public_state($s); }

    public static function pause(\WP_REST_Request $req) { $s = self::state_ref(); $s['paused'] = true; self::persist($s); return self::public_state($s); }
    public static function resume(\WP_REST_Request $req) { $s = self::state_ref(); $s['paused'] = false; self::persist($s); return self::public_state($s); }

    public static function cancel(\WP_REST_Request $req) { $s = self::state_ref(); self::rollback($s); $s = []; self::persist($s); return ['ok'=>true]; }

    public static function step(\WP_REST_Request $req) {
        check_ajax_referer('wp_rest');
        $s = self::state_ref();
        if (!empty($s['paused'])) { return self::public_state($s); }
        $steps = (array)($s['steps'] ?? []); $i = (int)($s['index'] ?? 0);
        if ($i >= count($steps)) { return self::public_state($s); }
        $current = (string)$steps[$i];
        $log = []; $done = true; $partial = 0.0;
        try {
            switch ($current) {
                case 'pages': [ $log, $done, $partial ] = self::proc_pages($s); break;
                case 'posts': [ $log, $done, $partial ] = self::proc_posts($s); break;
                case 'cpts': [ $log, $done, $partial ] = self::proc_cpts($s); break;
                case 'products': [ $log, $done, $partial ] = self::proc_products($s); break;
                case 'media': [ $log, $done, $partial ] = self::proc_media($s); break;
                case 'menus': [ $log, $done, $partial ] = self::proc_menus($s); break;
                case 'widgets': [ $log, $done, $partial ] = self::proc_widgets($s); break;
                case 'options': [ $log, $done, $partial ] = self::proc_options($s); break;
                case 'users': [ $log, $done, $partial ] = self::proc_users($s); break;
                case 'roles': [ $log, $done, $partial ] = self::proc_roles($s); break;
            }
            self::audit($s, 'tick', ['step' => $current, 'log' => $log]);
        } catch (\Throwable $e) {
            self::audit($s, 'error', ['step' => $current, 'message' => $e->getMessage()]);
            // rollback on error
            self::rollback($s);
            $s['error'] = true; self::persist($s);
            return new \WP_REST_Response(['error' => $e->getMessage()], 500);
        }
        $s['partial'] = $partial;
        if ($done) { $s['index'] = $i + 1; $s['partial'] = 0.0; }
        if ($s['index'] >= count($steps)) { self::audit($s, 'done'); }
        self::persist($s);
        return self::public_state($s);
    }

    public static function preview(\WP_REST_Request $req) { $p = $req->get_json_params(); return ['ok'=>true, 'entities' => array_map('sanitize_key', (array)($p['entities'] ?? []))]; }
    public static function export(\WP_REST_Request $req) {
        // Create a simple export bundle (JSON files zipped) in uploads/aqlxdi
        $upload = wp_upload_dir(); $dir = trailingslashit($upload['basedir']) . 'aqlxdi'; if (!is_dir($dir)) { wp_mkdir_p($dir); }
        $run = wp_generate_password(6, false, false); $work = $dir . '/export-' . $run; wp_mkdir_p($work);
        // Export pages, posts, products (if WC), menus
        $data = [];
        $data['pages'] = get_posts(['post_type'=>'page','posts_per_page'=>-1,'post_status'=>'any']);
        $data['posts'] = get_posts(['post_type'=>'post','posts_per_page'=>-1,'post_status'=>'any']);
        if (class_exists('WooCommerce')) { $data['products'] = get_posts(['post_type'=>'product','posts_per_page'=>-1,'post_status'=>'any']); }
        // Save JSON
        foreach ($data as $key => $items) { file_put_contents($work . '/' . $key . '.json', wp_json_encode($items, JSON_PRETTY_PRINT)); }
        // Zip
        $zipPath = $dir . '/export-' . $run . '.zip';
        if (class_exists('ZipArchive')) { $zip = new \ZipArchive(); if ($zip->open($zipPath, \ZipArchive::CREATE) === true) { foreach (glob($work . '/*.json') as $file) { $zip->addFile($file, basename($file)); } $zip->close(); } }
        // Cleanup tmp json files
        foreach (glob($work . '/*.json') as $file) { @unlink($file); } @rmdir($work);
        return [ 'ok' => true, 'url' => trailingslashit($upload['baseurl']) . 'aqlxdi/' . basename($zipPath) ];
    }

    public static function flush(\WP_REST_Request $req) { $s = self::state_ref(); self::secure_flush($s); self::persist([]); return ['ok'=>true]; }

    // Processors (minimal viable, with batching)
    private static function proc_pages(array &$s): array {
        $vol = max(1, (int)($s['volume'] ?? 5));
        $policy = (string)($s['policy'] ?? 'skip');
        $defaults = [ 'items' => [ ['title'=>'Home','slug'=>'home'], ['title'=>'About','slug'=>'about'], ['title'=>'Shop','slug'=>'shop'], ['title'=>'Blog','slug'=>'blog'], ['title'=>'Contact','slug'=>'contact'] ], 'index'=>0 ];
        $ps = &self::ensure_step($s, 'pages', $defaults);
        $log = []; $i = (int)$ps['index']; $processed = 0;
        while ($i < count($ps['items']) && $processed < $vol) {
            $it = $ps['items'][$i]; $slug = sanitize_title($it['slug']); $title = sanitize_text_field($it['title']);
            $existing = get_page_by_path($slug);
            if (!$existing) {
                $pid = wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_name'=>$slug,'post_title'=>$title,'post_content'=>wp_kses_post('<p>Demo: ' . $title . '</p>')]);
                if ($pid) { self::track($s, 'posts', (int)$pid); $log[] = 'Created page: ' . $title; }
            } else {
                if ($policy === 'overwrite') { wp_update_post(['ID'=>$existing->ID,'post_status'=>'publish','post_content'=>wp_kses_post('<p>Demo: ' . $title . '</p>')]); $log[]='Updated page: ' . $title; }
                elseif ($policy === 'merge') { /* no-op minimal merge */ $log[]='Ensured page exists: ' . $title; }
                else { $log[] = 'Skipped existing page: ' . $title; }
            }
            $i++; $processed++;
        }
        $ps['index'] = $i; $s['step_state']['pages'] = $ps;
        // set home/blog if available
        if (!($ps['home_set'] ?? false)) {
            $home = get_page_by_path('home'); $blog = get_page_by_path('blog');
            if ($home && $blog) {
                self::backup_option($s, 'show_on_front'); self::backup_option($s, 'page_on_front'); self::backup_option($s, 'page_for_posts');
                update_option('show_on_front', 'page'); update_option('page_on_front', $home->ID); update_option('page_for_posts', $blog->ID);
                $ps['home_set'] = true; $s['step_state']['pages'] = $ps; $log[] = 'Configured front/posts pages';
            }
        }
        return [$log, $i >= count($ps['items']), min(1.0, max(0.0, (count($ps['items']) ? $i / count($ps['items']) : 1.0)))];
    }

    private static function proc_posts(array &$s): array {
        $vol = max(1, (int)($s['volume'] ?? 5)); $pp = &self::ensure_step($s, 'posts', ['total'=>6,'index'=>0]); $log = []; $processed = 0;
        // Ensure categories
        foreach (['Guides','News','Aquascaping'] as $cat) { if (!term_exists($cat, 'category')) { $t = wp_insert_term($cat, 'category'); if (!is_wp_error($t)) { self::track($s, 'terms', (int)($t['term_id'] ?? $t)); } } }
        while ($pp['index'] < $pp['total'] && $processed < $vol) {
            $i = $pp['index'] + 1; $title = 'AquaLuxe Journal #' . $i; $existing = post_exists($title, '', '', 'post');
            if (!$existing) { $pid = wp_insert_post(['post_type'=>'post','post_status'=>'publish','post_title'=>$title,'post_content'=>wp_kses_post('<p>Insights on aquatic luxury #' . $i . '</p>')]); if ($pid) { self::track($s, 'posts', (int)$pid); $log[]='Created post #' . $i; $cats = get_terms(['taxonomy'=>'category','hide_empty'=>false]); if (!is_wp_error($cats) && $cats) { wp_set_post_terms($pid, [(int)$cats[array_rand($cats)]->term_id], 'category', false); } } }
            else { $log[]='Skipped post #' . $i; }
            $pp['index']++; $processed++;
        }
        $s['step_state']['posts'] = $pp; $partial = min(1.0, max(0.0, ($pp['total'] ? $pp['index']/$pp['total'] : 1.0))); return [$log, $pp['index'] >= $pp['total'], $partial];
    }

    private static function proc_cpts(array &$s): array {
        $vol = max(1, (int)($s['volume'] ?? 5)); $ps = &self::ensure_step($s, 'cpts', ['svc_total'=>4,'svc_idx'=>0]); $log = []; $processed = 0;
        while ($ps['svc_idx'] < $ps['svc_total'] && $processed < $vol) { $idx = $ps['svc_idx'] + 1; $title = 'Service ' . $idx; $existing = post_exists($title, '', '', 'service'); if (!$existing) { $pid = wp_insert_post(['post_type'=>'service','post_status'=>'publish','post_title'=>$title,'post_content'=>'Service #' . $idx]); if ($pid) { self::track($s, 'posts', (int)$pid); $log[]='Created service ' . $idx; } } else { $log[]='Skipped service ' . $idx; } $ps['svc_idx']++; $processed++; }
        $s['step_state']['cpts'] = $ps; $done = $ps['svc_idx'] >= $ps['svc_total']; $partial = min(1.0, max(0.0, ($ps['svc_total'] ? $ps['svc_idx']/$ps['svc_total'] : 1.0))); return [$log, $done, $partial];
    }

    private static function proc_products(array &$s): array {
        if (!class_exists('WooCommerce')) { return [['WooCommerce not active; skipping'], true, 1.0]; }
        $vol = max(1, (int)($s['volume'] ?? 5)); $policy = (string)($s['policy'] ?? 'skip');
        $ps = &self::ensure_step($s, 'products', ['simple_total'=>6,'simple_idx'=>0,'variable_done'=>false,'terms_seeded'=>false]);
        $log = [];
        if (!$ps['terms_seeded']) {
            foreach (['Rare Fish','Aquatic Plants','Premium Equipment','Care Supplies'] as $c) { $term = term_exists($c, 'product_cat'); if (!$term) { $term = wp_insert_term($c, 'product_cat'); if (!is_wp_error($term)) { self::track($s,'terms',(int)($term['term_id'] ?? $term)); $log[]='Created cat: ' . $c; } } }
            foreach (['Exotic','Quarantined','Aquascape','Rare'] as $t) { $term = term_exists($t, 'product_tag'); if (!$term) { $term = wp_insert_term($t, 'product_tag'); if (!is_wp_error($term)) { self::track($s,'terms',(int)($term['term_id'] ?? $term)); $log[]='Created tag: ' . $t; } } }
            $ps['terms_seeded'] = true; $s['step_state']['products'] = $ps;
        }
        $processed = 0; $cats = get_terms(['taxonomy'=>'product_cat','hide_empty'=>false]); $cat_ids = []; if (!is_wp_error($cats)) { foreach ($cats as $t) { $cat_ids[] = (int)$t->term_id; } }
        $tags = get_terms(['taxonomy'=>'product_tag','hide_empty'=>false]); $tag_ids = []; if (!is_wp_error($tags)) { foreach ($tags as $t) { $tag_ids[] = (int)$t->term_id; } }
        while ($ps['simple_idx'] < $ps['simple_total'] && $processed < $vol) {
            $i = $ps['simple_idx'] + 1; $name = 'AquaLuxe Specimen ' . $i; $existing = get_page_by_title($name, OBJECT, 'product');
            if (!$existing) {
                $p = new \WC_Product_Simple(); $p->set_name($name); $p->set_status('publish'); $p->set_regular_price((string)(50 + 10*$i)); $p->set_manage_stock(true); $p->set_stock_quantity(5+$i); $p->set_catalog_visibility('visible'); $pid = $p->save(); if ($pid) { self::track($s,'posts',(int)$pid); $log[]='Created product: ' . $name; }
                if ($pid && $cat_ids) { wp_set_object_terms($pid, [$cat_ids[$i % max(1,count($cat_ids))]], 'product_cat'); }
                if ($pid && $tag_ids) { wp_set_object_terms($pid, [$tag_ids[$i % max(1,count($tag_ids))]], 'product_tag', true); }
            } else {
                if ($policy === 'overwrite') { $p = wc_get_product($existing->ID); if ($p) { $p->set_regular_price((string)(50 + 10*$i)); $p->save(); $log[]='Updated product: ' . $name; } }
                elseif ($policy === 'merge') { /* ensure something lightweight if needed */ }
                else { $log[]='Skipped product: ' . $name; }
            }
            $ps['simple_idx']++; $processed++;
        }
        if (!$ps['variable_done'] && $processed < $vol) {
            $name = 'AquaLuxe Exhibit Tank'; $existing = get_page_by_title($name, OBJECT, 'product');
            if (!$existing) {
                foreach (['pa_size'=>'Size','pa_color'=>'Color','pa_material'=>'Material'] as $tax => $label) { if (!taxonomy_exists($tax)) { register_taxonomy($tax, 'product', ['label'=>$label,'hierarchical'=>false,'public'=>false,'show_ui'=>false]); } }
                $vp = new \WC_Product_Variable(); $vp->set_name($name); $vp->set_status('publish'); $vp->set_catalog_visibility('visible'); $vid = $vp->save(); if ($vid) { self::track($s,'posts',(int)$vid); $log[]='Created variable: ' . $name; }
                if ($vid) {
                    wp_set_object_terms($vid, ['small','medium','large'], 'pa_size'); wp_set_object_terms($vid, ['blue','gold'], 'pa_color'); wp_set_object_terms($vid, ['glass','acrylic'], 'pa_material');
                    $attrs = []; foreach ([ 'pa_size' => ['small','medium','large'], 'pa_color' => ['blue','gold'], 'pa_material' => ['glass','acrylic'] ] as $tname=>$opts) { $tax = new \WC_Product_Attribute(); $tax->set_id(0); $tax->set_name($tname); $tax->set_options($opts); $tax->set_visible(true); $tax->set_variation(true); $attrs[]=$tax; }
                    $vp->set_attributes($attrs); $vp->save();
                    foreach ([ ['size'=>'small','color'=>'blue','material'=>'glass','price'=>199,'stock'=>7], ['size'=>'medium','color'=>'blue','material'=>'glass','price'=>299,'stock'=>5], ['size'=>'large','color'=>'gold','material'=>'acrylic','price'=>499,'stock'=>3] ] as $v) { $var = new \WC_Product_Variation(); $var->set_parent_id($vid); $var->set_attributes([ 'pa_size'=>$v['size'], 'pa_color'=>$v['color'], 'pa_material'=>$v['material'] ]); $var->set_status('publish'); $var->set_regular_price((string)$v['price']); $var->set_manage_stock(true); $var->set_stock_quantity((int)$v['stock']); $var->save(); }
                }
            } else { if ($policy === 'overwrite') { $p = wc_get_product($existing->ID); if ($p) { $p->save(); $log[]='Verified variable: ' . $name; } } else { $log[]='Skipped variable: exists'; } }
            $ps['variable_done'] = true; $processed++;
        }
        $s['step_state']['products'] = $ps; $total = (int)$ps['simple_total'] + 1; $doneUnits = (int)$ps['simple_idx'] + (int)($ps['variable_done'] ? 1 : 0); $partial = min(1.0, max(0.0, $doneUnits / max(1,$total)));
        return [$log, $doneUnits >= $total, $partial];
    }

    private static function proc_media(array &$s): array {
        $vol = max(1, (int)($s['volume'] ?? 5)); $prov = (string)($s['provider'] ?? 'wikimedia');
        $ms = &self::ensure_step($s, 'media', ['index'=>0, 'total'=>10]); $log = []; $processed = 0;
        while ($ms['index'] < $ms['total'] && $processed < $vol) {
            $i = $ms['index'] + 1; $query = $i % 2 ? 'aquarium' : 'coral reef';
            $asset = Providers\MediaProvider::fetch($prov, $query); // ['url','source','license']
            if (!empty($asset['url'])) { $att = self::sideload((string)$asset['url'], 'demo-media-' . $i . '.jpg'); if ($att) { if (!empty($asset['source'])) { update_post_meta($att, '_aqlxdi_source', esc_url_raw($asset['source'])); } if (!empty($asset['license'])) { update_post_meta($att, '_aqlxdi_license', sanitize_text_field($asset['license'])); } self::track($s, 'posts', (int)$att); $log[] = 'Imported media #' . $i; } }
            $ms['index']++; $processed++;
        }
        $s['step_state']['media'] = $ms; $partial = min(1.0, max(0.0, ($ms['total'] ? $ms['index']/$ms['total'] : 1.0))); return [$log, $ms['index'] >= $ms['total'], $partial];
    }

    private static function proc_menus(array &$s): array {
        // Create a Primary menu and assign Home/Shop/Contact when present
        $menu = wp_get_nav_menu_object('Primary'); $created = false;
        if (!$menu) { $mid = wp_create_nav_menu('Primary'); $menu = $mid ? wp_get_nav_menu_object($mid) : null; $created = (bool)$menu; }
        if ($menu) {
            $home = get_page_by_path('home'); $shop = function_exists('wc_get_page_id') ? get_post(wc_get_page_id('shop')) : null; $contact = get_page_by_path('contact');
            foreach ([ ['Home',$home], ['Shop',$shop], ['Contact',$contact] ] as $pair) {
                [$title, $p] = $pair; if ($p && $p instanceof \WP_Post) { wp_update_nav_menu_item($menu->term_id, 0, [ 'menu-item-title'=>$title, 'menu-item-object'=>'page', 'menu-item-object-id'=>$p->ID, 'menu-item-type'=>'post_type', 'menu-item-status'=>'publish' ]); }
            }
            set_theme_mod('nav_menu_locations', array_merge((array)get_theme_mod('nav_menu_locations'), ['primary' => (int)$menu->term_id]));
        }
        return [[ $created ? 'Created Primary menu' : 'Updated Primary menu' ], true, 1.0];
    }

    private static function proc_widgets(array &$s): array {
        self::backup_option($s, 'sidebars_widgets'); self::backup_option($s, 'widget_text');
        $sidebars = get_option('sidebars_widgets', []); $widgets = get_option('widget_text', []); if (!is_array($widgets)) $widgets = [];
        $newIdx = 1; while (isset($widgets[$newIdx])) { $newIdx++; }
        $widgets[$newIdx] = [ 'title' => 'AquaLuxe', 'text' => '<p>Bringing elegance to aquatic life – globally.</p>' ]; update_option('widget_text', $widgets);
        if (!isset($sidebars['footer-1'])) { $sidebars['footer-1'] = []; }
        $sidebars['footer-1'][] = 'text-' . $newIdx; update_option('sidebars_widgets', $sidebars); self::track($s, 'widgets', 'text-' . $newIdx);
        return [['Added footer widget'], true, 1.0];
    }

    private static function proc_options(array &$s): array {
        self::backup_option($s, 'blogdescription'); self::backup_option($s, 'aqlx_locale_hint');
        if (!get_option('blogdescription')) { update_option('blogdescription', 'Bringing elegance to aquatic life – globally.'); }
        $locale = (string)($s['locale'] ?? 'en_US'); update_option('aqlx_locale_hint', $locale);
        return [['Set options'], true, 1.0];
    }

    private static function proc_users(array &$s): array {
        $vol = max(1, (int)($s['volume'] ?? 5)); $us = &self::ensure_step($s, 'users', ['total'=>5,'index'=>0]); $log = []; $processed = 0;
        while ($us['index'] < $us['total'] && $processed < $vol) {
            $i = $us['index'] + 1; $email = 'customer' . $i . '@example.com'; $username = 'customer' . $i;
            if (!email_exists($email) && !username_exists($username)) { $uid = wp_insert_user(['user_login'=>$username,'user_email'=>$email,'user_pass'=>wp_generate_password(12,true),'display_name'=>'Customer ' . $i,'role'=>class_exists('WooCommerce')?'customer':'subscriber']); if (!is_wp_error($uid)) { self::track($s, 'users', (int)$uid); $log[]='Created user: ' . $username; } } else { $log[]='Skipped existing user: ' . $username; }
            $us['index']++; $processed++;
        }
        $s['step_state']['users'] = $us; $partial = min(1.0, max(0.0, ($us['total'] ? $us['index']/$us['total'] : 1.0))); return [$log, $us['index'] >= $us['total'], $partial];
    }

    private static function proc_roles(array &$s): array { if (!get_role('partner')) { add_role('partner','Partner',['read'=>true]); self::track($s,'roles','partner'); return [['Created role: partner'], true, 1.0]; } return [['Role exists: partner'], true, 1.0]; }

    // Helpers
    private static function &ensure_step(array &$s, string $key, array $defaults = []): array { if (!isset($s['step_state']) || !is_array($s['step_state'])) $s['step_state'] = []; if (!isset($s['step_state'][$key]) || !is_array($s['step_state'][$key])) $s['step_state'][$key] = $defaults; return $s['step_state'][$key]; }

    private static function track(array &$s, string $bucket, $id): void { if (!isset($s['created'][$bucket])) $s['created'][$bucket] = []; if (!in_array($id, $s['created'][$bucket], true)) { $s['created'][$bucket][] = $id; } }

    private static function backup_option(array &$s, string $key): void { if (!isset($s['options_backup'][$key])) { $s['options_backup'][$key] = get_option($key, null); } }

    private static function sideload(string $url, string $filename): int {
        require_once ABSPATH . 'wp-admin/includes/file.php'; require_once ABSPATH . 'wp-admin/includes/media.php'; require_once ABSPATH . 'wp-admin/includes/image.php';
        // Download to temp and sideload (no hotlinking) for licensing safety
        $tmp = download_url($url, 30);
        if (is_wp_error($tmp)) { return 0; }
        $file = [ 'name' => $filename, 'tmp_name' => $tmp ]; $id = media_handle_sideload($file, 0);
        if (is_wp_error($id)) { @unlink($tmp); return 0; }
        return (int)$id;
    }

    private static function rollback(array &$s): void {
        // Delete posts (attachments, products, pages, etc.) we created
        foreach (array_reverse((array)($s['created']['posts'] ?? [])) as $pid) { if ($pid && get_post($pid)) { wp_delete_post((int)$pid, true); } }
        // Remove terms we created
        foreach (array_reverse((array)($s['created']['terms'] ?? [])) as $tid) { $t = get_term((int)$tid); if ($t && !is_wp_error($t) && $t->term_id) { wp_delete_term((int)$t->term_id, $t->taxonomy); } }
        // Delete users
        foreach (array_reverse((array)($s['created']['users'] ?? [])) as $uid) { if ($uid && get_user_by('id', $uid)) { require_once ABSPATH . 'wp-admin/includes/user.php'; wp_delete_user((int)$uid); } }
        // Remove roles
        foreach (array_reverse((array)($s['created']['roles'] ?? [])) as $role) { if (get_role($role)) { remove_role($role); } }
        // Remove widgets from sidebars
        $widgets = (array)($s['created']['widgets'] ?? []); if ($widgets) { $sidebars = get_option('sidebars_widgets', []); foreach ($sidebars as $sb => $items) { if (!is_array($items)) continue; $sidebars[$sb] = array_values(array_filter($items, function($wid) use ($widgets){ return !in_array($wid, $widgets, true); })); } update_option('sidebars_widgets', $sidebars); }
        // Restore options backup
        foreach ((array)($s['options_backup'] ?? []) as $k => $v) { update_option($k, $v); }
    }

    private static function secure_flush(array &$s): void {
        // wipe site content cautiously (excluding users/admins)
        $post_types = get_post_types(['public'=>true], 'names'); foreach ($post_types as $pt) { $q = new \WP_Query(['post_type'=>$pt,'posts_per_page'=>-1,'post_status'=>'any','fields'=>'ids']); foreach ($q->posts as $pid) { wp_delete_post((int)$pid, true); } }
        // terms
        foreach (get_taxonomies([], 'objects') as $tax) { $terms = get_terms(['taxonomy'=>$tax->name,'hide_empty'=>false]); if (is_wp_error($terms)) continue; foreach ($terms as $t) { if ($t->term_id) { wp_delete_term((int)$t->term_id, $tax->name); } } }
        // widgets/menus reset
        update_option('sidebars_widgets', []);
        // options reset (selective)
        foreach (['show_on_front','page_on_front','page_for_posts','blogdescription','aqlx_locale_hint'] as $k) { delete_option($k); }
        // clear state
        $s = [];
    }

    // Scheduling
    public static function schedule(\WP_REST_Request $req) {
        check_ajax_referer('wp_rest');
        $p = $req->get_json_params();
        $when = time() + 60; // first run in 1 minute
        $recurrence = 'daily';
        $job = [
            'entities' => array_map('sanitize_key', (array)($p['entities'] ?? [])),
            'volume' => max(1, absint($p['volume'] ?? 10)),
            'policy' => in_array($p['policy'] ?? 'skip', ['skip','merge','overwrite'], true) ? $p['policy'] : 'skip',
            'locale' => sanitize_text_field($p['locale'] ?? 'en_US'),
            'provider' => sanitize_key($p['provider'] ?? 'wikimedia'),
        ];
        update_option('aqlxdi_job', $job, false);
        if (!wp_next_scheduled('aqlxdi_cron_run')) { wp_schedule_event($when, $recurrence, 'aqlxdi_cron_run'); }
        add_action('aqlxdi_cron_run', [__CLASS__, 'run_job']);
        return ['ok'=>true];
    }

    public static function schedule_clear(\WP_REST_Request $req) {
        $ts = wp_next_scheduled('aqlxdi_cron_run'); if ($ts) { wp_unschedule_event($ts, 'aqlxdi_cron_run'); }
        delete_option('aqlxdi_job'); return ['ok'=>true];
    }

    public static function run_job(): void {
        $job = get_option('aqlxdi_job'); if (!$job) return;
        // Start a fresh run
        $state = [
            'run_id' => wp_generate_password(8, false, false),
            'steps' => self::build_steps((array)($job['entities'] ?? [])),
            'index' => 0,
            'volume' => max(1, (int)($job['volume'] ?? 10)),
            'policy' => (string)($job['policy'] ?? 'skip'),
            'locale' => (string)($job['locale'] ?? 'en_US'),
            'provider' => (string)($job['provider'] ?? 'wikimedia'),
            'paused' => false,
            'created' => [ 'posts' => [], 'terms' => [], 'users' => [], 'roles' => [], 'widgets' => [] ],
            'options_backup' => [], 'step_state' => [],
        ];
        self::ensure_audit($state); self::audit($state, 'cron-start');
        // Run up to N cycles per cron (to avoid timeouts)
        $cycles = 0; while ($cycles < 20 && ($state['index'] ?? 0) < count((array)$state['steps'])) { $cycles++; try { $dummy = new \WP_REST_Request(); $dummy->set_body_params([]); // not used
                // Run one step
                $steps = (array)$state['steps']; $i = (int)($state['index'] ?? 0); $current = (string)$steps[$i]; $log = []; $done = true; $partial = 0.0;
                switch ($current) {
                    case 'pages': [ $log, $done, $partial ] = self::proc_pages($state); break;
                    case 'posts': [ $log, $done, $partial ] = self::proc_posts($state); break;
                    case 'cpts': [ $log, $done, $partial ] = self::proc_cpts($state); break;
                    case 'products': [ $log, $done, $partial ] = self::proc_products($state); break;
                    case 'media': [ $log, $done, $partial ] = self::proc_media($state); break;
                    case 'menus': [ $log, $done, $partial ] = self::proc_menus($state); break;
                    case 'widgets': [ $log, $done, $partial ] = self::proc_widgets($state); break;
                    case 'options': [ $log, $done, $partial ] = self::proc_options($state); break;
                    case 'users': [ $log, $done, $partial ] = self::proc_users($state); break;
                    case 'roles': [ $log, $done, $partial ] = self::proc_roles($state); break;
                }
                self::audit($state, 'cron-tick', ['step'=>$current,'log'=>$log]); $state['partial'] = $partial; if ($done) { $state['index'] = $i + 1; $state['partial'] = 0.0; }
            } catch (\Throwable $e) { self::audit($state, 'cron-error', ['message'=>$e->getMessage()]); self::rollback($state); break; }
        }
        if (($state['index'] ?? 0) >= count((array)$state['steps'])) { self::audit($state, 'cron-done'); }
    }
}
