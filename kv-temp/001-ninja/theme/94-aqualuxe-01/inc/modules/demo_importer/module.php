<?php
namespace AquaLuxe\Modules\DemoImporter;

if (!defined('ABSPATH')) { exit; }

class DemoImporter {
    public static function init(): void {
        \add_action('admin_menu', [__CLASS__, 'menu']);
        \add_action('wp_ajax_aqualuxe_demo_import', [__CLASS__, 'ajax_import']);
        \add_action('wp_ajax_aqualuxe_demo_reset', [__CLASS__, 'ajax_reset']);
    }

    public static function menu(): void {
        \add_theme_page(
            __('AquaLuxe Demo', 'aqualuxe'),
            __('AquaLuxe Demo', 'aqualuxe'),
            'manage_options',
            'aqualuxe_demo',
            [__CLASS__, 'render_page']
        );
    }

    public static function render_page(): void {
    if (!\current_user_can('manage_options')) { return; }
        $nonce = \wp_create_nonce('aqualuxe_demo');
        echo '<div class="wrap"><h1>' . esc_html__('AquaLuxe Demo Importer', 'aqualuxe') . '</h1>';
        echo '<p>' . esc_html__('Import demo content or reset site to a clean state.', 'aqualuxe') . '</p>';
        echo '<div id="aqualuxe-demo-status" style="margin:1em 0;padding:1em;border:1px solid #ccd0d4;background:#fff"></div>';
        echo '<p><button class="button button-primary" id="al_import" data-nonce="' . esc_attr($nonce) . '">' . esc_html__('Run Demo Import', 'aqualuxe') . '</button> ';
        echo '<button class="button" id="al_reset" data-nonce="' . esc_attr($nonce) . '">' . esc_html__('Full Reset (Danger)', 'aqualuxe') . '</button></p>';
    echo '<script>jQuery(function($){var s=$("#aqualuxe-demo-status");function log(m){s.append($("<div/>").text(m));}$("#al_import").on("click",function(){var n=$(this).data("nonce");s.empty();log("Starting import...");$.post(ajaxurl,{action:"aqualuxe_demo_import",_wpnonce:n},function(r){s.append(r);});});$("#al_reset").on("click",function(){if(!confirm("This will delete content. Continue?"))return;var n=$(this).data("nonce");s.empty();log("Resetting...");$.post(ajaxurl,{action:"aqualuxe_demo_reset",_wpnonce:n},function(r){s.append(r);});});});</script>';
        echo '</div>';
    }

    public static function ajax_reset(): void {
        \check_ajax_referer('aqualuxe_demo');
    if (!\current_user_can('manage_options')) { \wp_send_json_error('permission'); }
        // Danger: For demo only. Remove content types created by importer.
        $post_types = ['page','post','product','service','event','testimonial'];
        foreach ($post_types as $pt) {
            $items = \get_posts(['post_type'=>$pt,'posts_per_page'=>-1,'post_status'=>'any']);
            foreach ($items as $it) { \wp_delete_post($it->ID, true); }
        }
        \wp_send_json_success('Reset complete.');
    }

    public static function ajax_import(): void {
        \check_ajax_referer('aqualuxe_demo');
    if (!\current_user_can('manage_options')) { \wp_send_json_error('permission'); }
        $output = [];
        $home = self::ensure_page('Home', 'front-page');
        $about = self::ensure_page('About', 'page-about');
        $services = self::ensure_page('Services', 'page-services');
        $blog = self::ensure_page('Blog', 'page-blog');
        $contact = self::ensure_page('Contact', 'page-contact');
        $faq = self::ensure_page('FAQ', 'page-faq');
        $legal = self::ensure_page('Privacy Policy', 'page-privacy');
        $output[] = 'Core pages created/ensured.';

        if (class_exists('WooCommerce')) {
            self::create_products();
            $output[] = 'Sample products created.';
        }

        // Set homepage
        \update_option('show_on_front', 'page');
        \update_option('page_on_front', $home);
        \update_option('page_for_posts', $blog);

        echo '<ul><li>' . implode('</li><li>', array_map('esc_html', $output)) . '</li></ul>';
        \wp_die();
    }

    private static function ensure_page(string $title, string $slug): int {
        $page = \get_page_by_path($slug);
        if ($page) { return (int)$page->ID; }
        $id = \wp_insert_post([
            'post_type' => 'page',
            'post_title' => $title,
            'post_name' => $slug,
            'post_status' => 'publish',
            'post_content' => self::page_content_template($slug),
        ]);
        return (int)$id;
    }

    private static function page_content_template(string $slug): string {
        switch ($slug) {
            case 'front-page':
                return "<!-- wp:paragraph --><p><strong>AquaLuxe</strong> — Bringing elegance to aquatic life – globally.</p><!-- /wp:paragraph -->";
            case 'page-about':
                return "<!-- wp:heading --><h2>About AquaLuxe</h2><!-- /wp:heading --><!-- wp:paragraph --><p>From local farm to international brand, committed to sustainability.</p><!-- /wp:paragraph -->";
            case 'page-services':
                return "<!-- wp:heading --><h2>Services</h2><!-- /wp:heading --><!-- wp:list --><ul><li>Design & Installation</li><li>Maintenance</li><li>Consultation</li></ul><!-- /wp:list -->";
            case 'page-contact':
                return "<!-- wp:heading --><h2>Contact Us</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Email: hello@example.com</p><!-- /wp:paragraph -->";
            case 'page-faq':
                return "<!-- wp:heading --><h2>FAQ</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Shipping, care, terms.</p><!-- /wp:paragraph -->";
            case 'page-privacy':
                return "<!-- wp:heading --><h2>Privacy Policy</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Your privacy matters.</p><!-- /wp:paragraph -->";
            default:
                return '';
        }
    }

    private static function create_products(): void {
        if (!class_exists('WC_Product')) { return; }
        $cats = ['Rare Fish','Aquatic Plants','Premium Equipment','Care Supplies'];
        $term_ids = [];
        foreach ($cats as $c) {
            $term = \term_exists($c, 'product_cat');
            $term_ids[] = $term ? (int)$term['term_id'] : (int) \wp_insert_term($c, 'product_cat')['term_id'];
        }
        for ($i=1; $i<=6; $i++) {
            $product = new \WC_Product_Simple();
            $product->set_name('Sample Product ' . $i);
            $product->set_regular_price((string)(10 + $i));
            $product->set_short_description('Elegant aquatic item ' . $i);
            $product->set_description('High-quality, ethically sourced, and carefully curated for luxury aquariums.');
            $product->set_category_ids([$term_ids[array_rand($term_ids)]]);
            $product->set_manage_stock(true);
            $product->set_stock_quantity(10 + $i);
            $product->save();
        }
    }
}
