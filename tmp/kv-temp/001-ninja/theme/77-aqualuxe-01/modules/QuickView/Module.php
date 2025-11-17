<?php
namespace AquaLuxe\Modules\QuickView;

if (!defined('ABSPATH')) exit;

class Module {
    public static function boot(): void {
        add_action('wp_footer', [__CLASS__, 'modal']);
        add_action('wp_ajax_aqlx_quick_view', [__CLASS__, 'ajax']);
        add_action('wp_ajax_nopriv_aqlx_quick_view', [__CLASS__, 'ajax']);
        if (class_exists('WooCommerce')) {
            add_action('woocommerce_after_shop_loop_item', [__CLASS__, 'button_for_loop'], 20);
        }
    }

    public static function modal(): void {
        echo '<div id="aqlx-qv" class="fixed inset-0 hidden items-center justify-center bg-black/50 p-4"><div class="bg-white dark:bg-slate-900 max-w-3xl w-full rounded shadow p-4"><button class="float-right" data-qv-close>&times;</button><div id="aqlx-qv-content"></div></div></div>';
        wp_add_inline_script('aqualuxe-app', "document.addEventListener('click',function(e){var b=e.target.closest('[data-qv]');if(b){e.preventDefault();var id=b.getAttribute('data-qv');var fd=new FormData();fd.append('action','aqlx_quick_view');fd.append('nonce',AquaLuxe.nonce);fd.append('id',id);fetch(AquaLuxe.ajaxUrl,{method:'POST',body:fd}).then(r=>r.text()).then(html=>{document.getElementById('aqlx-qv-content').innerHTML=html;document.getElementById('aqlx-qv').classList.remove('hidden');});}if(e.target.hasAttribute('data-qv-close')||e.target.id==='aqlx-qv'){document.getElementById('aqlx-qv').classList.add('hidden');}});");
    }

    public static function ajax(): void {
        check_ajax_referer('aqualuxe-nonce', 'nonce');
        $id = isset($_POST['id']) ? absint($_POST['id']) : 0;
        if (!$id) wp_die('');
    if (function_exists('wc_get_template_part')) {
            // Render product summary template.
            $post = get_post($id);
            if ($post && $post->post_type === 'product') {
                setup_postdata($post);
        call_user_func('wc_get_template_part', 'content', 'single-product');
                wp_reset_postdata();
            }
        } else {
            echo '<div class="p-4">' . esc_html(get_the_title($id)) . '</div>';
        }
        wp_die();
    }

    public static function button_for_loop(): void {
        global $product;
        if (!$product) return;
        echo '<button class="mt-2 px-3 py-1 border rounded" data-qv="' . (int)$product->get_id() . '">' . esc_html__('Quick View','aqualuxe') . '</button>';
    }
}
