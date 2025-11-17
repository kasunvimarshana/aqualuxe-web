<?php
namespace AquaLuxe\Modules\Importer;

class Module
{
    public function boot(): void
    {
        \add_action('wp_ajax_aqlx_import', [$this, 'handle_import']);
        \add_action('admin_enqueue_scripts', [$this, 'admin_assets']);
    }

    public function admin_assets(): void
    {
        // Inline minimal JS for importer page (progress, logging)
        $screen = function_exists('get_current_screen') ? \get_current_screen() : null;
        if ($screen && isset($screen->id) && str_contains((string)$screen->id, 'aqualuxe_page_aqualuxe-importer')) {
            \wp_add_inline_script('aqualuxe-app', "(function(){const f=document.getElementById('aqlx-import-form');if(!f)return;const p=document.getElementById('aqlx-progress');const log=document.getElementById('aqlx-log');f.addEventListener('submit',function(e){e.preventDefault();p.value=0;log.textContent='';const fd=new FormData(f);fd.append('action','aqlx_import');fetch(ajaxurl,{method:'POST',credentials:'same-origin',body:fd}).then(r=>r.json()).then(d=>{p.value=100;log.textContent=(d&&d.message)||'Done';}).catch(err=>{log.textContent='Error: '+err;});});})();");
        }
    }

    public function handle_import(): void
    {
        if (! \current_user_can('manage_options')) { \wp_send_json_error(['message'=>'Unauthorized'], 403); }
        if (! \check_admin_referer('aqlx_import')) { \wp_send_json_error(['message'=>'Invalid nonce'], 400); }

        $scope = isset($_POST['scope']) ? sanitize_text_field(wp_unslash($_POST['scope'])) : 'all';
        $reset = ! empty($_POST['reset']);

        // Optionally flush (dangerous): remove content created by previous imports (demo flag meta).
        if ($reset) {
            $this->reset_demo_content();
        }

        $this->import_core_pages();
        if ($scope === 'all' || $scope === 'content') {
            $this->import_blog();
        }
        if ($scope === 'all' || $scope === 'products') {
            if (class_exists('WooCommerce')) {
                $this->import_products();
            }
        }

        \wp_send_json_success(['message' => 'Demo content imported']);
    }

    private function reset_demo_content(): void
    {
        // Remove posts flagged with meta _aqlx_demo = 1
        $ids = \get_posts(['post_type'=>['page','post','product','service','event','testimonial'],'meta_key'=>'_aqlx_demo','meta_value'=>1,'posts_per_page'=>-1,'fields'=>'ids']);
        foreach ($ids as $id) { \wp_delete_post($id, true); }
    }

    private function import_core_pages(): void
    {
        $pages = [
            'Home' => 'home',
            'About' => 'about',
            'Services' => 'services',
            'Blog' => 'blog',
            'Contact' => 'contact',
            'FAQ' => 'faq',
            'Privacy Policy' => 'privacy-policy',
            'Terms & Conditions' => 'terms',
            'Shipping & Returns' => 'shipping-returns',
            'Cookie Policy' => 'cookies',
        ];
        foreach ($pages as $title => $slug) {
            $id = \wp_insert_post([
                'post_type'   => 'page',
                'post_title'  => $title,
                'post_name'   => $slug,
                'post_status' => 'publish',
                'meta_input'  => ['_aqlx_demo' => 1],
                'post_content'=> '<!-- wp:paragraph --><p>Demo content for ' . esc_html($title) . '</p><!-- /wp:paragraph -->',
            ]);
        }
    }

    private function import_blog(): void
    {
        for ($i=1; $i<=6; $i++) {
            \wp_insert_post([
                'post_type'   => 'post',
                'post_title'  => 'Aquatic Insights #' . $i,
                'post_status' => 'publish',
                'meta_input'  => ['_aqlx_demo' => 1],
                'post_content'=> '<p>Exploring the elegance of aquatic life. Entry #' . $i . '</p>',
            ]);
        }
    }

    private function import_products(): void
    {
        // Minimal product examples; full variable products omitted for brevity.
        if (! class_exists('WC_Product_Simple')) { return; }
        $titles = ['Rare Betta', 'Premium Koi', 'Aquascape LED Light'];
        foreach ($titles as $title) {
            $id = \wp_insert_post([
                'post_type'   => 'product',
                'post_title'  => $title,
                'post_status' => 'publish',
                'meta_input'  => ['_aqlx_demo' => 1],
            ]);
            if ($id && ! is_wp_error($id)) {
                \wp_set_object_terms($id, 'simple', 'product_type');
                \update_post_meta($id, '_price', '99.00');
                \update_post_meta($id, '_regular_price', '99.00');
                \update_post_meta($id, '_stock_status', 'instock');
            }
        }
    }
}
