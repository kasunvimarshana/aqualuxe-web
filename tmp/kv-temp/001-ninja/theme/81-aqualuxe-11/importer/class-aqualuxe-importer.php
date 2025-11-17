<?php
/**
 * AquaLuxe Demo Importer (secure, selective, rollback-aware)
 */
if (!defined('ABSPATH')) { exit; }

class AquaLuxe_Importer {
    const DEMO_META = '_aqualuxe_demo';
    const LOG_DIR = 'aqualuxe-importer/logs';
    const MENU_PRIMARY = 'Primary';
    const MENU_FOOTER = 'Footer';
    public function __construct(){
        add_action('admin_menu', [$this,'menu']);
        add_action('admin_post_aqualuxe_import', [$this,'handle']);
        add_action('admin_post_aqualuxe_flush', [$this,'flush']);
        // AJAX endpoints
        add_action('wp_ajax_aqualuxe_import_preview', [$this,'ajax_preview']);
        add_action('wp_ajax_aqualuxe_import_step', [$this,'ajax_step']);
        add_action('wp_ajax_aqualuxe_flush', [$this,'ajax_flush']);
        add_action('wp_ajax_aqualuxe_export', [$this,'ajax_export']);
        // Cron for scheduled re-initialization
        add_action('aqualuxe_importer_cron', [$this,'cron_runner']);
    }

    // Utilities for logging
    private function upload_dir_path($sub=''){
        $u = wp_upload_dir();
        $path = trailingslashit($u['basedir']) . ltrim($sub, '/');
        if (!file_exists($path)) wp_mkdir_p($path);
        return trailingslashit($path);
    }

    private function ensure_log_file(){
        $dir = $this->upload_dir_path(self::LOG_DIR);
        $file = get_option('aqualuxe_import_lastlog');
        if (!$file || !file_exists($file)){
            $file = $dir . 'import-' . gmdate('Ymd-His') . '.json';
            update_option('aqualuxe_import_lastlog', $file, false);
            file_put_contents($file, '[]');
        }
        return $file;
    }

    private function append_log($file, $entry){
        $entry['time'] = time();
        $json = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        if (!is_array($json)) $json = [];
        $json[] = $entry;
        file_put_contents($file, wp_json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
    }

    public function menu(){
        add_submenu_page('aqualuxe', __('Importer','aqualuxe'), __('Importer','aqualuxe'), 'manage_options', 'aqualuxe-importer', [$this,'screen']);
    }

    public function screen(){
        if (!current_user_can('manage_options')) return;
        $nonce = wp_create_nonce('aqualuxe-import');
        $lastlog = get_option('aqualuxe_import_lastlog');
        ?>
        <div class="wrap" id="aqualuxe-importer">
            <h1><?php esc_html_e('AquaLuxe Demo Importer','aqualuxe'); ?></h1>
            <div class="ax-config">
                <h2><?php esc_html_e('Configuration','aqualuxe'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th><?php esc_html_e('Entities','aqualuxe'); ?></th>
                        <td>
                            <label><input type="checkbox" class="ax-entity" value="pages" checked> <?php esc_html_e('Pages','aqualuxe'); ?></label>
                            <label><input type="checkbox" class="ax-entity" value="posts" checked> <?php esc_html_e('Posts','aqualuxe'); ?></label>
                            <label><input type="checkbox" class="ax-entity" value="services" checked> <?php esc_html_e('Services','aqualuxe'); ?></label>
                            <label><input type="checkbox" class="ax-entity" value="events" checked> <?php esc_html_e('Events','aqualuxe'); ?></label>
                            <label><input type="checkbox" class="ax-entity" value="products" checked> <?php esc_html_e('Products','aqualuxe'); ?></label>
                            <label><input type="checkbox" class="ax-entity" value="media" checked> <?php esc_html_e('Media','aqualuxe'); ?></label>
                            <label><input type="checkbox" class="ax-entity" value="menus" checked> <?php esc_html_e('Menus','aqualuxe'); ?></label>
                            <label><input type="checkbox" class="ax-entity" value="roles" checked> <?php esc_html_e('Roles/Users','aqualuxe'); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Volumes','aqualuxe'); ?></th>
                        <td>
                            <label><?php esc_html_e('Posts','aqualuxe'); ?> <input type="number" class="ax-count" data-key="posts" value="6" min="0" max="100"></label>
                            <label><?php esc_html_e('Products','aqualuxe'); ?> <input type="number" class="ax-count" data-key="products" value="12" min="0" max="200"></label>
                            <label><?php esc_html_e('Media','aqualuxe'); ?> <input type="number" class="ax-count" data-key="media" value="10" min="0" max="200"></label>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Options','aqualuxe'); ?></th>
                        <td>
                            <label><?php esc_html_e('Random Seed','aqualuxe'); ?> <input type="number" id="ax-seed" value="42"></label>
                            <label><?php esc_html_e('Locale','aqualuxe'); ?>
                                <select id="ax-locale">
                                    <option value="en_US">English (US)</option>
                                    <option value="en_GB">English (UK)</option>
                                </select>
                            </label>
                            <label><?php esc_html_e('Conflict Policy','aqualuxe'); ?>
                                <select id="ax-conflict">
                                    <option value="skip"><?php esc_html_e('Skip','aqualuxe'); ?></option>
                                    <option value="overwrite"><?php esc_html_e('Overwrite','aqualuxe'); ?></option>
                                    <option value="merge"><?php esc_html_e('Merge','aqualuxe'); ?></option>
                                </select>
                            </label>
                            <label><input type="checkbox" id="ax-variations" checked> <?php esc_html_e('Include Variable Products','aqualuxe'); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Scheduling','aqualuxe'); ?></th>
                        <td>
                            <label><input type="checkbox" id="ax-schedule"> <?php esc_html_e('Enable re-initialization','aqualuxe'); ?></label>
                            <select id="ax-schedule-interval">
                                <option value="daily"><?php esc_html_e('Daily','aqualuxe'); ?></option>
                                <option value="twicedaily"><?php esc_html_e('Twice Daily','aqualuxe'); ?></option>
                                <option value="hourly"><?php esc_html_e('Hourly','aqualuxe'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
                <p>
                    <button class="button" id="ax-preview"><?php esc_html_e('Preview','aqualuxe'); ?></button>
                    <button class="button button-primary" id="ax-run"><?php esc_html_e('Run Import','aqualuxe'); ?></button>
                    <button class="button" id="ax-export"><?php esc_html_e('Export JSON','aqualuxe'); ?></button>
                </p>
                <div id="ax-progress" style="max-width:600px;background:#f0f0f1;border:1px solid #dcdcde;height:20px;border-radius:4px;overflow:hidden;">
                    <div id="ax-bar" style="width:0%;height:100%;background:#0ea5e9;"></div>
                </div>
                <pre id="ax-log" style="max-height:240px;overflow:auto;background:#111;color:#9ef;padding:12px;"></pre>
                <?php if ($lastlog && file_exists($lastlog)) : ?>
                    <p><a href="<?php echo esc_url( content_url(str_replace( WP_CONTENT_DIR . '/', '', $lastlog )) ); ?>" target="_blank">Last Log</a></p>
                <?php endif; ?>
            </div>
            <hr>
            <div class="ax-flush">
                <h2><?php esc_html_e('Flush / Reset','aqualuxe'); ?></h2>
                <p>
                    <label><input type="checkbox" class="ax-flush-part" value="content" checked> <?php esc_html_e('Content (posts, pages, CPTs, products, media)','aqualuxe'); ?></label>
                    <label><input type="checkbox" class="ax-flush-part" value="menus" checked> <?php esc_html_e('Menus','aqualuxe'); ?></label>
                    <label><input type="checkbox" class="ax-flush-part" value="roles" checked> <?php esc_html_e('Roles/Users','aqualuxe'); ?></label>
                    <label><input type="checkbox" class="ax-flush-part" value="settings" checked> <?php esc_html_e('Settings','aqualuxe'); ?></label>
                </p>
                <p><button class="button button-secondary" id="ax-flush-btn"><?php esc_html_e('Flush Now','aqualuxe'); ?></button></p>
            </div>
            <input type="hidden" id="ax-nonce" value="<?php echo esc_attr($nonce); ?>"/>
        </div>
        <?php
    }

    public function handle(){
        if (!current_user_can('manage_options')) wp_die('Forbidden');
        check_admin_referer('aqualuxe-import');
        $report = [];
        if (isset($_POST['import_pages'])) $report['pages'] = $this->import_pages();
        if (isset($_POST['import_services'])) $report['services'] = $this->import_services();
        if (isset($_POST['import_events'])) $report['events'] = $this->import_events();
        if (isset($_POST['import_products']) && class_exists('WooCommerce')) $report['products'] = $this->import_products();
        set_transient('aqualuxe_import_report', $report, 60);
        wp_safe_redirect(admin_url('admin.php?page=aqualuxe-importer&done=1'));
        exit;
    }

    // New AJAX endpoints
    public function ajax_preview(){
        if (!current_user_can('manage_options')) wp_send_json_error('forbidden', 403);
        check_ajax_referer('aqualuxe-import','nonce');
        $counts_raw = $_POST['counts'] ?? '{}';
        $counts = is_string($counts_raw) ? json_decode(stripslashes($counts_raw), true) : (array) $counts_raw;
        $preview = [
            'pages' => 14,
            'posts' => isset($counts['posts']) ? (int)$counts['posts'] : 6,
            'services' => 4,
            'events' => 2,
            'products' => isset($counts['products']) ? (int)$counts['products'] : 12,
            'media' => isset($counts['media']) ? (int)$counts['media'] : 10,
            'roles' => 2,
            'menus' => 2,
        ];
        wp_send_json_success(['preview'=>$preview]);
    }

    public function ajax_step(){
        if (!current_user_can('manage_options')) wp_send_json_error('forbidden', 403);
        check_ajax_referer('aqualuxe-import','nonce');
    $step = sanitize_text_field($_POST['step'] ?? '');
    $opts_raw = $_POST['opts'] ?? '{}';
    $opts = is_string($opts_raw) ? json_decode(stripslashes($opts_raw), true) : (array) $opts_raw;
    if (!is_array($opts)) $opts = [];
    if (isset($opts['conflict'])) update_option('aqualuxe_import_conflict', sanitize_text_field($opts['conflict']), false);
    if (isset($opts['seed'])) update_option('aqualuxe_import_seed', (string) $opts['seed'], false);
    if (isset($opts['locale'])) update_option('aqualuxe_import_locale', sanitize_text_field($opts['locale']), false);
    $result = [];
    $log = $this->ensure_log_file();
        try {
            switch ($step) {
                case 'roles': $result = $this->create_roles_and_users(); break;
                case 'taxonomies': $result = $this->import_taxonomies(); break;
                case 'media': $result = $this->import_media_seed( (int)($opts['media'] ?? 10) ); break;
                case 'pages': $result = $this->import_pages(); break;
                case 'posts': $result = $this->import_posts( (int)($opts['posts'] ?? 6) ); break;
                case 'services': $result = $this->import_services(); break;
                case 'events': $result = $this->import_events(); break;
                case 'products_simple': $result = $this->import_products_simple( (int)($opts['products'] ?? 12) ); break;
                case 'products_variable': $result = $this->import_products_variable(); break;
                case 'menus': $result = $this->create_menus(); break;
                case 'settings': $result = $this->finalize_settings(); break;
                case 'schedule': $result = $this->maybe_schedule($opts); break;
                default: wp_send_json_error(['message'=>'Unknown step']);
            }
            $this->append_log($log, ['step'=>$step,'ok'=>true,'result'=>$result]);
            wp_send_json_success(['result'=>$result]);
        } catch (Throwable $e) {
            $this->append_log($log, ['step'=>$step,'ok'=>false,'error'=>$e->getMessage()]);
            wp_send_json_error(['message'=>$e->getMessage()]);
        }
    }

    public function ajax_flush(){
        if (!current_user_can('manage_options')) wp_send_json_error('forbidden', 403);
        check_ajax_referer('aqualuxe-import','nonce');
    $parts_raw = $_POST['parts'] ?? '[]';
    $parts = is_string($parts_raw) ? json_decode(stripslashes($parts_raw), true) : (array) $parts_raw;
    if (!is_array($parts)) $parts = [];
        $res = $this->flush_parts($parts);
        wp_send_json_success(['flushed'=>$res]);
    }

    public function ajax_export(){
        if (!current_user_can('manage_options')) wp_send_json_error('forbidden', 403);
        check_ajax_referer('aqualuxe-import','nonce');
        $file = $this->export_json();
        wp_send_json_success(['file'=>$file]);
    }

    public function cron_runner(){
        $enabled = get_option('aqualuxe_import_schedule_enabled');
        if ($enabled !== '1') return;
        $this->flush_parts(['content','menus']);
        $this->import_taxonomies();
        $this->import_pages();
        $this->import_services();
        $this->import_events();
        if (class_exists('WooCommerce')) { $this->import_products_simple(8); $this->import_products_variable(); }
        $this->create_menus();
        $this->finalize_settings();
    }

    // ==== New methods used by AJAX steps ====
    private function import_posts($count = 6){
        $out = [];
        $conflict = get_option('aqualuxe_import_conflict','skip');
        $seed = intval(get_option('aqualuxe_import_seed', 42));
        srand($seed);
        for ($i=1; $i<=$count; $i++){
            $title = 'AquaLuxe Journal ' . $i;
            $exists = get_page_by_title($title, OBJECT, 'post');
            if ($exists) {
                if ($conflict === 'overwrite') {
                    wp_update_post(['ID'=>$exists->ID,'post_content'=>'<p>Exploring aquascaping trends and care techniques.</p>']);
                }
                $out[] = $exists->ID; continue;
            }
            $id = wp_insert_post(['post_type'=>'post','post_title'=>$title,'post_status'=>'publish','post_content'=>'<p>Exploring aquascaping trends and care techniques.</p>']);
            if (!is_wp_error($id)) { add_post_meta($id, self::DEMO_META, 1, true); $out[] = $id; }
        }
        return $out;
    }

    private function import_taxonomies(){
        $out = [];
        if (taxonomy_exists('product_cat')){
            $roots = ['Rare Fish','Aquatic Plants','Premium Equipment','Care Supplies'];
            foreach ($roots as $cat){ wp_insert_term($cat, 'product_cat'); $out[] = $cat; }
            $rare = get_term_by('name','Rare Fish','product_cat');
            $equip = get_term_by('name','Premium Equipment','product_cat');
            if ($rare) wp_insert_term('Koi', 'product_cat', ['parent' => $rare->term_id ]);
            if ($equip) wp_insert_term('LED Lighting', 'product_cat', ['parent' => $equip->term_id ]);
        }
        if (taxonomy_exists('service_type')){
            foreach (['Design','Maintenance','Health','Scaping'] as $t){ if (!term_exists($t,'service_type')) wp_insert_term($t, 'service_type'); $out[] = $t; }
        }
        if (taxonomy_exists('event_type')){
            foreach (['Competition','Tour','Workshop'] as $t){ if (!term_exists($t,'event_type')) wp_insert_term($t, 'event_type'); $out[] = $t; }
        }
        return $out;
    }

    private function import_media_seed($count = 10){
        $urls = [
            'https://cdn.pixabay.com/photo/2017/08/07/20/20/fish-2608006_1280.jpg',
            'https://cdn.pixabay.com/photo/2016/11/22/20/17/fish-1850136_1280.jpg',
            'https://cdn.pixabay.com/photo/2019/08/26/18/18/aquarium-4431323_1280.jpg',
            'https://cdn.pixabay.com/photo/2015/09/18/19/35/aquarium-948396_1280.jpg'
        ];
        $out=[]; $i=0;
        $seed = intval(get_option('aqualuxe_import_seed', 42));
        srand($seed);
        while (count($out) < $count){
            $u = $urls[$i % count($urls)]; $i++;
            $hash = md5($u);
            $existing = get_posts(['post_type'=>'attachment','posts_per_page'=>1,'fields'=>'ids','meta_key'=>'aqualuxe_source_hash','meta_value'=>$hash]);
            if ($existing) { $out[] = (int) $existing[0]; continue; }
            $att_id = $this->sideload_image($u, 'Aquarium Scene');
            if ($att_id) { update_post_meta($att_id, self::DEMO_META, 1); $out[] = $att_id; }
            if ($i > $count*3) break;
        }
        return $out;
    }

    private function sideload_image($url, $alt=''){
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        $id = media_sideload_image($url, 0, $alt, 'id');
        if (is_wp_error($id)) return 0;
    if ($alt) update_post_meta($id, '_wp_attachment_image_alt', sanitize_text_field($alt));
    update_post_meta($id, 'aqualuxe_credit_source', 'Pixabay');
    update_post_meta($id, 'aqualuxe_credit_url', esc_url_raw($url));
    update_post_meta($id, 'aqualuxe_source_hash', md5($url));
    add_post_meta($id, self::DEMO_META, 1, true);
        return (int) $id;
    }

    private function import_products_simple($count = 12){
        if (!function_exists('wc_get_product')) return [];
        $cats = ['Rare Fish','Aquatic Plants','Premium Equipment','Care Supplies'];
        foreach ($cats as $cat){ if (!term_exists($cat,'product_cat')) wp_insert_term($cat, 'product_cat'); }
        $names = ['Imperial Koi','Blue Rili Shrimp Pack','Sapphire LED Lighting','Blackwater Conditioner','Aquarium Heater','Plant Fertilizer'];
        $out = [];
        $seed = intval(get_option('aqualuxe_import_seed', 42));
        srand($seed);
        for ($i=0; $i<$count; $i++){
            $name = $names[$i % count($names)] . ' #' . ($i+1);
            $existing = get_page_by_title($name, OBJECT, 'product');
            if ($existing) { $post_id = $existing->ID; }
            else { $post_id = wp_insert_post(['post_type'=>'product','post_title'=>$name,'post_status'=>'publish','post_content'=>'<p>Premium quality product.</p>']); }
            if (is_wp_error($post_id)) continue;
            add_post_meta($post_id, self::DEMO_META, 1, true);
            $cat = $cats[$i % count($cats)];
            wp_set_object_terms($post_id, $cat, 'product_cat');
            $product = call_user_func('wc_get_product', $post_id);
            if ($product) {
                $price = number_format( rand(1000, 50000) / 100, 2, '.', '' );
                $product->set_regular_price($price);
                $product->set_manage_stock(true);
                $product->set_stock_quantity(rand(5,30));
                $product->save();
            }
            $out[] = $post_id;
        }
        return $out;
    }

    private function import_products_variable(){
        if (!function_exists('wc_get_product')) return [];
        if (!class_exists('WooCommerce')) return [];
        // Ensure attributes
        $attributes = [ 'pa_size' => ['S','M','L'], 'pa_color' => ['Red','Blue','Green'], 'pa_material' => ['Glass','Acrylic'] ];
        foreach ($attributes as $tax => $terms){
            if (!taxonomy_exists($tax)) {
                register_taxonomy($tax, 'product', [ 'hierarchical'=>false, 'label'=>ucfirst(str_replace('pa_','',$tax)), 'show_ui'=>true, 'query_var'=>true, 'rewrite'=>['slug'=>$tax] ]);
            }
            foreach ($terms as $t){ if (!term_exists($t, $tax)) wp_insert_term($t, $tax); }
        }
        // Create variable product
    $existing = get_page_by_title('AquaLuxe Tank Set', OBJECT, 'product');
    $pid = $existing ? (int) $existing->ID : (int) wp_insert_post(['post_type'=>'product','post_title'=>'AquaLuxe Tank Set','post_status'=>'publish','post_content'=>'<p>Premium, customizable aquarium tank set.</p>']);
        if (is_wp_error($pid)) return [];
        add_post_meta($pid, self::DEMO_META, 1, true);
        $classVar = class_exists('WC_Product_Variable') ? 'WC_Product_Variable' : '';
        $classAttr = class_exists('WC_Product_Attribute') ? 'WC_Product_Attribute' : '';
        $classVariation = class_exists('WC_Product_Variation') ? 'WC_Product_Variation' : '';
        if (!$classVar || !$classAttr || !$classVariation) return [];
        $product = new $classVar($pid);
        $atts = [];
        foreach ($attributes as $tax => $terms){
            $taxonomy = $tax;
            $att = new $classAttr();
            $att->set_id(0);
            $att->set_name($taxonomy);
            $att->set_options( array_map(function($t) use ($taxonomy){ $term = get_term_by('name',$t,$taxonomy); return $term ? (int) $term->term_id : 0; }, $terms) );
            $att->set_visible(true);
            $att->set_variation(true);
            $atts[] = $att;
        }
        $product->set_attributes($atts);
        $product->save();
        $created = [];
        foreach ($attributes['pa_size'] as $sz){
            foreach ($attributes['pa_color'] as $cl){
                $existing_var = get_posts([
                    'post_type'=>'product_variation', 'posts_per_page'=>1, 'fields'=>'ids', 'post_parent'=>$pid,
                    'meta_query'=>[
                        ['key'=>'attribute_pa_size','value'=>sanitize_title($sz)],
                        ['key'=>'attribute_pa_color','value'=>sanitize_title($cl)]
                    ]
                ]);
                if ($existing_var) { $variation_id = (int) $existing_var[0]; }
                else {
                    $variation = new $classVariation();
                    $variation->set_parent_id($pid);
                    $variation->set_attributes([
                        'pa_size' => sanitize_title($sz),
                        'pa_color' => sanitize_title($cl),
                    ]);
                    $variation->set_regular_price( number_format(rand(15000,30000)/100,2,'.','') );
                    $variation->set_manage_stock(true);
                    $variation->set_stock_quantity(rand(1,10));
                    $variation_id = $variation->save();
                }
                if ($variation_id) { add_post_meta($variation_id, self::DEMO_META, 1, true); $created[] = $variation_id; }
            }
        }
        return array_merge([$pid], $created);
    }

    private function create_menus(){
        $menu = wp_get_nav_menu_object(self::MENU_PRIMARY);
        $menu_id = $menu ? (int) $menu->term_id : (int) wp_create_nav_menu(self::MENU_PRIMARY);
        if (!is_wp_error($menu_id)) {
            $existing_items = wp_get_nav_menu_items($menu_id);
            $existing_object_ids = [];
            if (is_array($existing_items)) foreach ($existing_items as $mi) { if ($mi->object_id) $existing_object_ids[(int)$mi->object_id]=true; }
            $add = function($title) use ($menu_id, $existing_object_ids) {
                $p = get_page_by_title($title);
                if (!$p) return 0;
                if (isset($existing_object_ids[(int)$p->ID])) return 0;
                return wp_update_nav_menu_item( $menu_id, 0, [ 'menu-item-title' => $title, 'menu-item-object-id'=>$p->ID, 'menu-item-object'=>'page', 'menu-item-type'=>'post_type', 'menu-item-status'=>'publish' ] );
            };
            $order = ['Home','Services','Wholesale & B2B','Buy, Sell & Trade','Export','Events & Experiences','Learning Hub','Blog','About Us','Contact'];
            if (class_exists('WooCommerce')) { array_splice($order, 1, 0, 'Shop'); }
            foreach ($order as $t) { $add($t); }
            $locations = get_theme_mod('nav_menu_locations'); if (!is_array($locations)) $locations = [];
            $locations['primary'] = $menu_id; set_theme_mod('nav_menu_locations', $locations);
        }
        $footer = wp_get_nav_menu_object(self::MENU_FOOTER);
        $footer_id = $footer ? (int)$footer->term_id : (int) wp_create_nav_menu(self::MENU_FOOTER);
        if (!is_wp_error($footer_id)){
            $addf = function($title) use ($footer_id){ $p = get_page_by_title($title); return $p ? wp_update_nav_menu_item($footer_id,0,[ 'menu-item-title'=>$title,'menu-item-object-id'=>$p->ID,'menu-item-object'=>'page','menu-item-type'=>'post_type','menu-item-status'=>'publish']) : 0; };
            foreach (['FAQ','Privacy Policy','Terms & Conditions','Contact'] as $t) { $addf($t); }
            $locations = get_theme_mod('nav_menu_locations'); if (!is_array($locations)) $locations = [];
            $locations['footer'] = $footer_id; set_theme_mod('nav_menu_locations', $locations);
        }
        return ['primary'=>$menu_id ?? 0, 'footer'=>$footer_id ?? 0];
    }

    private function create_roles_and_users(){
        add_role('wholesale_partner', __('Wholesale Partner','aqualuxe'), ['read'=>true]);
        add_role('events_manager', __('Events Manager','aqualuxe'), ['read'=>true,'edit_posts'=>true,'publish_posts'=>true]);
        $users = [];
        if (!username_exists('partner')){
            $uid = wp_create_user('partner','demo123','partner@example.com');
            if (!is_wp_error($uid)) { $u = new WP_User($uid); $u->set_role('wholesale_partner'); add_user_meta($uid, self::DEMO_META, 1, true); $users[]=$uid; }
        }
        if (!username_exists('events')){
            $uid = wp_create_user('events','demo123','events@example.com');
            if (!is_wp_error($uid)) { $u = new WP_User($uid); $u->set_role('events_manager'); add_user_meta($uid, self::DEMO_META, 1, true); $users[]=$uid; }
        }
        return ['roles'=>['wholesale_partner','events_manager'],'users'=>$users];
    }

    private function finalize_settings(){
        return ['done'=>true];
    }

    private function maybe_schedule($opts){
        $enable = isset($opts['schedule']) && $opts['schedule'] === '1';
        $interval = isset($opts['interval']) ? sanitize_text_field($opts['interval']) : 'daily';
        update_option('aqualuxe_import_schedule_enabled', $enable ? '1' : '0');
        update_option('aqualuxe_import_schedule_interval', $interval);
        if ($enable){
            if (!wp_next_scheduled('aqualuxe_importer_cron')) wp_schedule_event(time()+60, $interval, 'aqualuxe_importer_cron');
        } else {
            $ts = wp_next_scheduled('aqualuxe_importer_cron'); if ($ts) wp_unschedule_event($ts, 'aqualuxe_importer_cron');
        }
        return ['enabled'=>$enable,'interval'=>$interval];
    }

    private function flush_parts($parts){
        $res = [];
        if (in_array('content',$parts,true)){
            $res['content'] = 0;
            $q = new WP_Query([ 'post_type'=>['post','page','service','event','product','product_variation','attachment'], 'posts_per_page'=>-1, 'post_status'=>'any', 'meta_key'=>self::DEMO_META, 'meta_value'=>1 ]);
            while ($q->have_posts()){ $q->the_post(); wp_delete_post(get_the_ID(), true); $res['content']++; }
            wp_reset_postdata();
        }
        if (in_array('menus',$parts,true)){
            foreach ([self::MENU_PRIMARY,self::MENU_FOOTER] as $m){ $menu = wp_get_nav_menu_object($m); if ($menu) wp_delete_nav_menu($menu); }
            $res['menus']=true;
        }
        if (in_array('roles',$parts,true)){
            foreach (['wholesale_partner','events_manager'] as $role){ remove_role($role); }
            $q = get_users(['meta_key'=>self::DEMO_META,'meta_value'=>1,'fields'=>'ID']);
            foreach ($q as $uid){ wp_delete_user($uid); }
            $res['roles']=true;
        }
        if (in_array('settings',$parts,true)){
            delete_option('aqualuxe_import_schedule_enabled');
            delete_option('aqualuxe_import_schedule_interval');
            $res['settings']=true;
        }
        return $res;
    }

    private function export_json(){
        $data = [ 'pages'=>[], 'posts'=>[], 'services'=>[], 'events'=>[], 'products'=>[], 'media'=>[] ];
        $q = new WP_Query([ 'post_type'=>['post','page','service','event','product','product_variation','attachment'], 'posts_per_page'=>-1, 'post_status'=>'any', 'meta_key'=>self::DEMO_META, 'meta_value'=>1 ]);
        while ($q->have_posts()){
            $q->the_post();
            $data[get_post_type()][] = [ 'ID'=>get_the_ID(), 'title'=>get_the_title(), 'type'=>get_post_type(), 'meta'=>get_post_meta(get_the_ID()) ];
        }
        wp_reset_postdata();
        $upload = wp_upload_dir();
        $dir = trailingslashit($upload['basedir']) . 'aqualuxe-importer/exports/';
        if (!file_exists($dir)) wp_mkdir_p($dir);
        $file = $dir . 'export-' . gmdate('Ymd-His') . '.json';
        file_put_contents($file, wp_json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
        return $file;
    }

    public function flush(){
        if (!current_user_can('manage_options')) wp_die('Forbidden');
        check_admin_referer('aqualuxe-flush');
    // Delegate to selective flush using demo meta markers
    $this->flush_parts(['content','menus','roles','settings']);
        wp_safe_redirect(admin_url('admin.php?page=aqualuxe-importer&flushed=1'));
        exit;
    }

    private function import_pages(){
        $pages = [
            ['title'=>'Home','slug'=>'','template'=>'front-page.php','content'=>'[aqualuxe_home]'],
            ['title'=>'About Us','slug'=>'about','content'=>'<p>Our mission: Bringing elegance to aquatic life – globally.</p>'],
            ['title'=>'Shop','slug'=>'shop','content'=>''],
            ['title'=>'Wholesale & B2B','slug'=>'wholesale','content'=>'[aqualuxe_wholesale]'],
            ['title'=>'Buy, Sell & Trade','slug'=>'trade','content'=>'[aqualuxe_trade]'],
            ['title'=>'Services','slug'=>'services','content'=>'[aqualuxe_services]'],
            ['title'=>'Export','slug'=>'export','content'=>'[aqualuxe_export]'],
            ['title'=>'Events & Experiences','slug'=>'events','content'=>'[aqualuxe_events_hub]'],
            ['title'=>'Learning Hub','slug'=>'learning-hub','content'=>'[aqualuxe_learning_hub]'],
            ['title'=>'Blog','slug'=>'blog','content'=>''],
            ['title'=>'Contact','slug'=>'contact','content'=>'[aqualuxe_contact]'],
            ['title'=>'FAQ','slug'=>'faq','content'=>'[aqualuxe_faq]'],
            ['title'=>'Privacy Policy','slug'=>'privacy-policy','content'=>'[aqualuxe_privacy]'],
            ['title'=>'Terms & Conditions','slug'=>'terms-conditions','content'=>'[aqualuxe_terms]'],
        ];
        $out = [];
        $conflict = get_option('aqualuxe_import_conflict','skip');
        $created_ids = [];
        foreach ($pages as $p){
            $existing = !empty($p['slug']) ? get_page_by_path($p['slug']) : get_page_by_title($p['title']);
            if ($existing && !is_wp_error($existing)) {
                $id = $existing->ID;
                if ($conflict === 'overwrite') {
                    wp_update_post(['ID'=>$id,'post_content'=>$p['content']]);
                    if (!empty($p['template'])) update_post_meta($id, '_wp_page_template', $p['template']);
                }
            } else {
                $id = wp_insert_post([
                    'post_type' => 'page',
                    'post_title' => $p['title'],
                    'post_name' => $p['slug'],
                    'post_status' => 'publish',
                    'post_content' => $p['content']
                ]);
                if (!is_wp_error($id)) { add_post_meta($id, self::DEMO_META, 1, true); $created_ids[] = $id; }
            }
            if (!is_wp_error($id) && !empty($p['template'])) {
                $current_tpl = get_post_meta($id, '_wp_page_template', true);
                if (empty($current_tpl) || $current_tpl === 'default') { update_post_meta($id, '_wp_page_template', $p['template']); }
            }
            $out[] = ['title'=>$p['title'],'id'=>$id];
            // If Woo is active, map Shop page
            if (class_exists('WooCommerce') && strtolower($p['title']) === 'shop' && !is_wp_error($id)) {
                $shop_set = (int) get_option('woocommerce_shop_page_id');
                if (!$shop_set) { update_option('woocommerce_shop_page_id', $id); }
            }
        }
    // Set static front page
        $home = get_page_by_title('Home');
        if ($home) { update_option('show_on_front', 'page'); update_option('page_on_front', $home->ID); }

    // Set posts page to Blog if present
    $blog = get_page_by_title('Blog');
    if ($blog && (int) get_option('page_for_posts') !== (int) $blog->ID) { update_option('page_for_posts', $blog->ID); }

        return $out;
    }

    private function import_services(){
        $items = [
            ['Aquarium Design & Installation','Bespoke aquariums for luxury spaces.'],
            ['Maintenance Plans','Scheduled cleaning and water testing.'],
            ['Quarantine & Health Checks','Disease prevention for export and retail.'],
            ['Aquascaping','Design and planting for aquatic landscapes.']
        ];
        $out=[]; $conflict = get_option('aqualuxe_import_conflict','skip');
        foreach ($items as $it){
            $exists = get_page_by_title($it[0], OBJECT, 'service');
            if ($exists) { if ($conflict==='overwrite') wp_update_post(['ID'=>$exists->ID,'post_content'=>"<p>{$it[1]}</p>"]); $out[] = $exists->ID; continue; }
            $sid = wp_insert_post([
                'post_type'=>'service','post_title'=>$it[0],'post_content'=>"<p>{$it[1]}</p>", 'post_status'=>'publish'] );
            if (!is_wp_error($sid)) { add_post_meta($sid, self::DEMO_META, 1, true); $out[] = $sid; }
        }
        return $out;
    }

    private function import_events(){
        $items = [
            ['Aquascaping Competition','2025-10-05'],
            ['Farm Tour','2025-11-12']
        ];
        $out=[]; $conflict = get_option('aqualuxe_import_conflict','skip');
        foreach ($items as $it){
            $exists = get_page_by_title($it[0], OBJECT, 'event');
            if ($exists) { if ($conflict==='overwrite') { wp_update_post(['ID'=>$exists->ID,'post_content'=>'<p>Join us.</p>']); update_post_meta($exists->ID,'event_date',$it[1]); } $out[] = $exists->ID; continue; }
            $eid = wp_insert_post(['post_type'=>'event','post_title'=>$it[0],'post_content'=>'<p>Join us.</p>','post_status'=>'publish']);
            if (!is_wp_error($eid)) update_post_meta($eid, 'event_date', $it[1]);
            if (!is_wp_error($eid)) add_post_meta($eid, self::DEMO_META, 1, true);
            $out[] = $eid;
        }
        return $out;
    }

    private function import_products(){
    if (!function_exists('wc_get_product')) return [];
        $cats = ['Rare Fish','Aquatic Plants','Premium Equipment','Care Supplies'];
        foreach ($cats as $cat){
            wp_insert_term($cat, 'product_cat');
        }
        $items = [
            ['Imperial Koi','Rare Fish', 499.00],
            ['Blue Rili Shrimp Pack','Aquatic Plants', 29.00],
            ['Sapphire LED Lighting','Premium Equipment', 199.00],
            ['Blackwater Conditioner','Care Supplies', 14.50]
        ];
        $out=[]; foreach ($items as $it){
            $post_id = wp_insert_post(['post_type'=>'product','post_title'=>$it[0],'post_status'=>'publish','post_content'=>'<p>Premium quality product.</p>']);
            if (is_wp_error($post_id)) continue;
            wp_set_object_terms($post_id, $it[1], 'product_cat');
            if (function_exists('wc_get_product')) {
                $product = call_user_func('wc_get_product', $post_id);
                if ($product) {
                    $product->set_regular_price($it[2]);
                    $product->set_manage_stock(true);
                    $product->set_stock_quantity(10);
                    $product->save();
                }
            }
            $out[] = $post_id;
        }
        return $out;
    }
}

new AquaLuxe_Importer();
