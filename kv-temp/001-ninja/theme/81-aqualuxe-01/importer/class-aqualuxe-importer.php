<?php
/**
 * AquaLuxe Demo Importer (secure, selective, rollback-aware)
 */
if (!defined('ABSPATH')) { exit; }

class AquaLuxe_Importer {
    public function __construct(){
        add_action('admin_menu', [$this,'menu']);
        add_action('admin_post_aqualuxe_import', [$this,'handle']);
        add_action('admin_post_aqualuxe_flush', [$this,'flush']);
    }

    public function menu(){
        add_submenu_page('aqualuxe', __('Importer','aqualuxe'), __('Importer','aqualuxe'), 'manage_options', 'aqualuxe-importer', [$this,'screen']);
    }

    public function screen(){
        if (!current_user_can('manage_options')) return;
        $nonce = wp_create_nonce('aqualuxe-import');
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('AquaLuxe Demo Importer','aqualuxe'); ?></h1>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="aqualuxe_import"/>
                <input type="hidden" name="_wpnonce" value="<?php echo esc_attr($nonce); ?>"/>
                <p><label><input type="checkbox" name="import_products" checked/> <?php esc_html_e('Products','aqualuxe'); ?></label></p>
                <p><label><input type="checkbox" name="import_pages" checked/> <?php esc_html_e('Pages','aqualuxe'); ?></label></p>
                <p><label><input type="checkbox" name="import_services" checked/> <?php esc_html_e('Services','aqualuxe'); ?></label></p>
                <p><label><input type="checkbox" name="import_events" checked/> <?php esc_html_e('Events','aqualuxe'); ?></label></p>
                <p><button class="button button-primary"><?php esc_html_e('Run Import','aqualuxe'); ?></button></p>
            </form>
            <hr>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" onsubmit="return confirm('This will permanently delete demo content. Continue?');">
                <input type="hidden" name="action" value="aqualuxe_flush"/>
                <?php wp_nonce_field('aqualuxe-flush'); ?>
                <p><button class="button button-secondary"><?php esc_html_e('Flush Demo Content','aqualuxe'); ?></button></p>
            </form>
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

    public function flush(){
        if (!current_user_can('manage_options')) wp_die('Forbidden');
        check_admin_referer('aqualuxe-flush');
        // Remove only our CPT content to be safe
        foreach (['service','event','product','product_variation'] as $pt) {
            $q = new WP_Query([ 'post_type'=>$pt, 'posts_per_page'=>-1, 'post_status'=>'any' ]);
            while ($q->have_posts()){ $q->the_post(); wp_delete_post(get_the_ID(), true); }
            wp_reset_postdata();
        }
        wp_safe_redirect(admin_url('admin.php?page=aqualuxe-importer&flushed=1'));
        exit;
    }

    private function import_pages(){
        $pages = [
            ['title'=>'Home','slug'=>'','template'=>'front-page.php','content'=>'[aqualuxe_home]'],
            ['title'=>'About','slug'=>'about','content'=>'<p>Our mission: Bringing elegance to aquatic life – globally.</p>'],
            ['title'=>'Services','slug'=>'services','content'=>'[aqualuxe_services]'],
            ['title'=>'Blog','slug'=>'blog','content'=>''],
            ['title'=>'Contact','slug'=>'contact','content'=>'[aqualuxe_contact]'],
            ['title'=>'FAQ','slug'=>'faq','content'=>'[aqualuxe_faq]'],
            ['title'=>'Privacy Policy','slug'=>'privacy-policy','content'=>'[aqualuxe_privacy]'],
            ['title'=>'Terms & Conditions','slug'=>'terms-conditions','content'=>'[aqualuxe_terms]'],
        ];
        $out = [];
        foreach ($pages as $p){
            $id = wp_insert_post([
                'post_type' => 'page',
                'post_title' => $p['title'],
                'post_name' => $p['slug'],
                'post_status' => 'publish',
                'post_content' => $p['content']
            ]);
            if (!is_wp_error($id) && !empty($p['template'])) update_post_meta($id, '_wp_page_template', $p['template']);
            $out[] = ['title'=>$p['title'],'id'=>$id];
        }
        // Set static front page
        $home = get_page_by_title('Home');
        if ($home) { update_option('show_on_front', 'page'); update_option('page_on_front', $home->ID); }
        return $out;
    }

    private function import_services(){
        $items = [
            ['Aquarium Design & Installation','Bespoke aquariums for luxury spaces.'],
            ['Maintenance Plans','Scheduled cleaning and water testing.'],
            ['Quarantine & Health Checks','Disease prevention for export and retail.'],
            ['Aquascaping','Design and planting for aquatic landscapes.']
        ];
        $out=[]; foreach ($items as $it){
            $out[] = wp_insert_post([
                'post_type'=>'service','post_title'=>$it[0],'post_content'=>"<p>{$it[1]}</p>", 'post_status'=>'publish'] );
        }
        return $out;
    }

    private function import_events(){
        $items = [
            ['Aquascaping Competition','2025-10-05'],
            ['Farm Tour','2025-11-12']
        ];
        $out=[]; foreach ($items as $it){
            $id = wp_insert_post(['post_type'=>'event','post_title'=>$it[0],'post_content'=>'<p>Join us.</p>','post_status'=>'publish']);
            if (!is_wp_error($id)) update_post_meta($id, 'event_date', $it[1]);
            $out[] = $id;
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
