<?php
/** Demo Content Importer (admin UI + AJAX) */

if (is_admin()) {
    add_action('admin_menu', function(){
        add_theme_page(__('AquaLuxe Importer','aqualuxe'), __('AquaLuxe Importer','aqualuxe'), 'manage_options', 'aqualuxe-importer', 'aqualuxe_importer_page');
    });

    function aqualuxe_importer_page(){
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('AquaLuxe Demo Importer', 'aqualuxe'); ?></h1>
            <p><?php esc_html_e('Import demo content, or reset site to factory defaults. Use with caution.', 'aqualuxe'); ?></p>
            <div id="aqlx-importer" class="card" style="max-width:900px;padding:16px;">
                <label><input type="checkbox" id="flush" /> <?php esc_html_e('Full reset before import (flush all content)', 'aqualuxe'); ?></label>
                <div style="margin-top:8px;">
                    <label><?php esc_html_e('Volume', 'aqualuxe'); ?> <input id="volume" type="number" value="20" min="1" max="200" /></label>
                    <label style="margin-left:12px;">Locale <input id="locale" type="text" value="en_US" /></label>
                </div>
                <fieldset style="margin-top:8px;">
                    <legend><strong><?php esc_html_e('Entities to import','aqualuxe'); ?></strong></legend>
                    <label><input type="checkbox" id="imp_pages" checked /> Pages</label>
                    <label style="margin-left:8px;"><input type="checkbox" id="imp_products" checked /> Products</label>
                    <label style="margin-left:8px;"><input type="checkbox" id="imp_posts" checked /> Blog Posts</label>
                    <label style="margin-left:8px;"><input type="checkbox" id="imp_services" checked /> Services</label>
                    <label style="margin-left:8px;"><input type="checkbox" id="imp_events" checked /> Events</label>
                </fieldset>
                <fieldset style="margin-top:8px;">
                    <legend><strong><?php esc_html_e('Scheduling','aqualuxe'); ?></strong></legend>
                    <label><input type="checkbox" id="schedule_daily" /> <?php esc_html_e('Nightly re-initialization (flush + import)','aqualuxe'); ?></label>
                </fieldset>
                <div style="margin-top:12px;">
                    <button class="button button-primary" id="btnImport"><?php esc_html_e('Run Import', 'aqualuxe'); ?></button>
                    <button class="button" id="btnExport"><?php esc_html_e('Export Demo (JSON)', 'aqualuxe'); ?></button>
                </div>
                <progress id="progress" max="100" value="0" style="width:100%;margin-top:12px;"></progress>
                <pre id="log" style="max-height:300px;overflow:auto;background:#111;color:#0f0;padding:8px;"></pre>
            </div>
        </div>
        <script>
        (function(){
            const $=s=>document.querySelector(s);
            function log(m){ const el=$('#log'); el.textContent += m+'\n'; el.scrollTop=el.scrollHeight; }
            function run(action){
                const data = new FormData();
                data.append('action', action);
                data.append('nonce', '<?php echo esc_js(wp_create_nonce('aqualuxe')); ?>');
                data.append('flush', $('#flush').checked ? '1' : '0');
                data.append('volume', $('#volume').value);
                data.append('locale', $('#locale').value);
                fetch(ajaxurl, {method:'POST', body:data})
                    .then(r=>r.json()).then(j=>{
                        if(j.success){
                            const {progress, message} = j.data; $('#progress').value=progress; if(message) log(message);
                            if(progress<100){ run(action); } else { log('Done.'); }
                        } else { log('Error: '+ (j.data && j.data.message ? j.data.message : 'unknown')); }
                    }).catch(e=>log('Request failed:'+e));
            }
            $('#btnImport').addEventListener('click',()=>{ 
                $('#log').textContent=''; 
                const s = new FormData();
                s.append('action','aqualuxe_import_schedule');
                s.append('nonce','<?php echo esc_js(wp_create_nonce('aqualuxe')); ?>');
                s.append('schedule', $('#schedule_daily').checked ? '1':'0');
                fetch(ajaxurl,{method:'POST',body:s}).then(()=>run('aqualuxe_import_step'));
            });
            $('#btnExport').addEventListener('click',()=>{ window.location = '<?php echo esc_url(admin_url('admin-ajax.php?action=aqualuxe_export_demo&nonce=' . wp_create_nonce('aqualuxe'))); ?>'; });
        })();
        </script>
        <?php
    }

    // Stateful stepper stored in options for progress/rollback
    add_action('wp_ajax_aqualuxe_import_step', function(){
        check_ajax_referer('aqualuxe','nonce');
        $state = get_option('aqlx_import_state', ['step'=>0]);
        $flush = isset($_POST['flush']) && $_POST['flush'] === '1';
        $volume = max(1, min(200, intval($_POST['volume'] ?? 20)));
        $locale = sanitize_text_field($_POST['locale'] ?? 'en_US');
        $opts = [
            'pages' => !empty($_POST['imp_pages']) || true,
            'products' => !empty($_POST['imp_products']) || true,
            'posts' => !empty($_POST['imp_posts']) || true,
            'services' => !empty($_POST['imp_services']) || true,
            'events' => !empty($_POST['imp_events']) || true,
        ];
        try {
            switch ((int)$state['step']) {
                case 0:
                    if ($flush) aqualuxe_importer_flush();
                    $state['step']=1; update_option('aqlx_import_state', $state);
                    wp_send_json_success(['progress'=>10,'message'=>__('Reset complete.','aqualuxe')]);
                case 1:
                    if ($opts['pages']) { aqualuxe_seed_pages(); }
                    aqualuxe_seed_menus();
                    $state['step']=2; update_option('aqlx_import_state', $state);
                    wp_send_json_success(['progress'=>30,'message'=>__('Pages created.','aqualuxe')]);
                case 2:
                    if ($opts['products']) { aqualuxe_seed_product_categories(); aqualuxe_seed_products($volume); aqualuxe_seed_variable_products(3); }
                    $state['step']=3; update_option('aqlx_import_state', $state);
                    wp_send_json_success(['progress'=>60,'message'=>__('Products created.','aqualuxe')]);
                case 3:
                    if ($opts['posts']) { aqualuxe_seed_posts(10); }
                    if ($opts['services'] && post_type_exists('service')) { aqualuxe_seed_services(6); }
                    if ($opts['events'] && post_type_exists('event')) { aqualuxe_seed_events(4); }
                    $state['step']=4; update_option('aqlx_import_state', $state);
                    wp_send_json_success(['progress'=>80,'message'=>__('Blog posts created.','aqualuxe')]);
                case 4:
                    delete_option('aqlx_import_state');
                    wp_send_json_success(['progress'=>100,'message'=>__('Import finished.','aqualuxe')]);
            }
        } catch (Exception $e) {
            // rollback basic (could extend with transactions on custom tables)
            delete_option('aqlx_import_state');
            wp_send_json_error(['message'=>$e->getMessage()]);
        }
    });

    add_action('wp_ajax_aqualuxe_export_demo', function(){
        check_ajax_referer('aqualuxe','nonce');
        $data = [
            'pages' => get_pages(),
            'posts' => get_posts(['numberposts'=>-1]),
        ];
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename=aqualuxe-demo.json');
        echo wp_json_encode($data);
        wp_die();
    });

    add_action('wp_ajax_aqualuxe_import_schedule', function(){
        check_ajax_referer('aqualuxe','nonce');
        $enable = !empty($_POST['schedule']);
        if ($enable) {
            if (!wp_next_scheduled('aqlx_cron_reinit')) {
                wp_schedule_event(time()+3600, 'daily', 'aqlx_cron_reinit');
            }
        } else {
            $ts = wp_next_scheduled('aqlx_cron_reinit');
            if ($ts) wp_unschedule_event($ts, 'aqlx_cron_reinit');
        }
        wp_send_json_success(true);
    });
}

function aqualuxe_importer_flush(){
    // Danger: delete content (keep users)
    $post_types = get_post_types(['public'=>true], 'names');
    $all = get_posts(['post_type'=>$post_types, 'numberposts'=>-1, 'post_status'=>'any']);
    foreach ($all as $p) { wp_delete_post($p->ID, true); }
    // Terms
    $taxes = get_taxonomies([], 'names');
    foreach ($taxes as $tax) {
        $terms = get_terms(['taxonomy'=>$tax,'hide_empty'=>false]);
        if (!is_wp_error($terms)){
            foreach ($terms as $t){ wp_delete_term($t->term_id, $tax); }
        }
    }
}

function aqualuxe_seed_pages(){
    $pages = [
        'Home' => 'front-page',
        'Shop' => 'shop',
        'About' => 'page',
        'Services' => 'page',
        'Blog' => 'page',
        'Contact' => 'page',
        'FAQ' => 'page',
        'Privacy Policy' => 'page',
        'Terms & Conditions' => 'page',
        'Shipping & Returns' => 'page',
        'Cookie Policy' => 'page',
    ];
    foreach ($pages as $title=>$tpl){
        if (get_page_by_title($title)) continue;
        $id = wp_insert_post([
            'post_title'=>$title,
            'post_type'=>'page',
            'post_status'=>'publish',
            'post_content'=>sprintf(__('Demo content for %s.','aqualuxe'), $title)
        ]);
        if ($title==='Home'){ update_option('show_on_front','page'); update_option('page_on_front',$id); }
    }
}

function aqualuxe_seed_product_categories(){
    if (!taxonomy_exists('product_cat')) return;
    $cats = ['Rare Fish','Aquatic Plants','Premium Equipment','Care Supplies'];
    foreach ($cats as $c){ if (!term_exists($c,'product_cat')) wp_insert_term($c,'product_cat'); }
}

function aqualuxe_seed_products($count=20){
    if (!post_type_exists('product')) return; // Graceful if Woo not active
    $cat_ids = [];
    if (taxonomy_exists('product_cat')){
        foreach (['Rare Fish','Aquatic Plants','Premium Equipment','Care Supplies'] as $c){
            $term = term_exists($c,'product_cat'); if ($term) $cat_ids[] = intval($term['term_id']);
        }
    }
    for ($i=0;$i<$count;$i++){
        $title = 'Demo Product ' . wp_generate_password(4,false);
        $id = wp_insert_post([
            'post_title'=>$title,
            'post_type'=>'product',
            'post_status'=>'publish',
            'post_content'=>'A premium aquatic product crafted for elegance and performance.'
        ]);
        if (is_wp_error($id)) continue;
        add_post_meta($id, '_regular_price', rand(10,200));
        add_post_meta($id, '_price', get_post_meta($id,'_regular_price',true));
        add_post_meta($id, '_stock', rand(1,50));
        add_post_meta($id, '_manage_stock', 'yes');
        if (!empty($cat_ids)) wp_set_object_terms($id, $cat_ids, 'product_cat');
    }
}

function aqualuxe_seed_variable_products($count=3){
    if (!post_type_exists('product') || !taxonomy_exists('product_type')) return;
    // Ensure variable type exists
    for ($i=0;$i<$count;$i++){
        $id = wp_insert_post([
            'post_title'=>'Variable Product ' . wp_generate_password(4,false),
            'post_type'=>'product', 'post_status'=>'publish'
        ]);
        if (is_wp_error($id)) continue;
        // Mark as variable
        wp_set_object_terms($id, 'variable', 'product_type');
        // Attributes taxonomies may not exist; use custom attributes if missing
        $attrs = [
            'size' => ['S','M','L'],
            'color' => ['Blue','Gold']
        ];
        $product_attributes = [];
        foreach ($attrs as $name=>$values){
            $tax = taxonomy_exists('pa_'.$name);
            $options = [];
            foreach ($values as $v){
                if ($tax){
                    if (!term_exists($v, 'pa_'.$name)) wp_insert_term($v, 'pa_'.$name);
                    $options[] = sanitize_title($v);
                } else {
                    $options[] = $v;
                }
            }
            $product_attributes['pa_'.$name] = [
                'name' => $tax ? 'pa_'.$name : $name,
                'value' => $tax ? implode('|', $options) : implode(' | ', $options),
                'is_visible' => 1,
                'is_variation' => 1,
                'is_taxonomy' => $tax ? 1 : 0,
            ];
        }
        update_post_meta($id, '_product_attributes', $product_attributes);
        // Create variations
        $pairs = [ ['S','Blue'], ['M','Gold'] ];
        foreach ($pairs as [$size,$color]){
            $var_id = wp_insert_post(['post_title'=>'Variation', 'post_status'=>'publish', 'post_type'=>'product_variation', 'post_parent'=>$id]);
            if (is_wp_error($var_id)) continue;
            update_post_meta($var_id, 'attribute_pa_size', sanitize_title($size));
            update_post_meta($var_id, 'attribute_pa_color', sanitize_title($color));
            update_post_meta($var_id, '_regular_price', rand(20,250));
            update_post_meta($var_id, '_price', get_post_meta($var_id,'_regular_price',true));
            update_post_meta($var_id, '_stock', rand(1,25));
        }
    }
}

function aqualuxe_seed_posts($count=10){
    for ($i=0;$i<$count;$i++){
        wp_insert_post([
            'post_title' => 'AquaLuxe Blog ' . ($i+1),
            'post_type' => 'post',
            'post_status' => 'publish',
            'post_content' => 'Elegance meets aquatic life. This is sample content to demonstrate typography and layout.'
        ]);
    }
}

function aqualuxe_seed_services($count=6){
    for ($i=0;$i<$count;$i++){
        wp_insert_post([
            'post_title' => 'Service ' . ($i+1),
            'post_type' => 'service',
            'post_status' => 'publish',
            'post_content' => 'Premium aquarium design and maintenance service tailored to your needs.'
        ]);
    }
}

function aqualuxe_seed_events($count=4){
    for ($i=0;$i<$count;$i++){
        wp_insert_post([
            'post_title' => 'Event ' . ($i+1),
            'post_type' => 'event',
            'post_status' => 'publish',
            'post_content' => 'Join our aquatic experiences and workshops.'
        ]);
    }
}

function aqualuxe_seed_menus(){
    $menu_name = 'Primary';
    $menu_id = wp_create_nav_menu($menu_name);
    if (!is_wp_error($menu_id)){
        $home = get_page_by_title('Home');
        $shop = get_page_by_title('Shop');
        $about = get_page_by_title('About');
        $services = get_page_by_title('Services');
        $contact = get_page_by_title('Contact');
        foreach ([$home,$shop,$about,$services,$contact] as $p){ if ($p) wp_update_nav_menu_item($menu_id, 0, ['menu-item-title'=>$p->post_title, 'menu-item-object'=>'page','menu-item-object-id'=>$p->ID,'menu-item-type'=>'post_type','menu-item-status'=>'publish']); }
        $locations = get_theme_mod('nav_menu_locations'); if(!is_array($locations)) $locations=[]; $locations['primary']=$menu_id; set_theme_mod('nav_menu_locations', $locations);
    }
}

// Nightly cron hook
add_action('aqlx_cron_reinit', function(){
    aqualuxe_importer_flush();
    aqualuxe_seed_pages();
    aqualuxe_seed_menus();
    aqualuxe_seed_product_categories();
    aqualuxe_seed_products(10);
    aqualuxe_seed_variable_products(2);
    aqualuxe_seed_posts(5);
    if (post_type_exists('service')) aqualuxe_seed_services(3);
    if (post_type_exists('event')) aqualuxe_seed_events(2);
});
