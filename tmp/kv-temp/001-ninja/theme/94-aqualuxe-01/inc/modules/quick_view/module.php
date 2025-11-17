<?php
namespace AquaLuxe\Modules\QuickView;

if (!defined('ABSPATH')) { exit; }

class QuickView {
    public static function init(): void {
        if (!class_exists('WooCommerce')) { return; }
        \add_action('wp_ajax_al_quick_view', [__CLASS__, 'render']);
        \add_action('wp_ajax_nopriv_al_quick_view', [__CLASS__, 'render']);
    }

    public static function render(): void {
    $pid = isset($_GET['product_id']) ? \absint($_GET['product_id']) : 0;
    if ($pid < 1) { \wp_die(''); }
    $post = \get_post($pid);
    if (!$post || $post->post_type !== 'product') { \wp_die(''); }
    \setup_postdata($post);
    \wc_get_template_part('content', 'single-product');
    \wp_reset_postdata();
    \wp_die();
    }
}
