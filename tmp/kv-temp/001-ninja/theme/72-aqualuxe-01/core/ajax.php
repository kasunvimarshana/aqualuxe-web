<?php
namespace AquaLuxe\Core;

class Ajax {
    public static function init(): void {
        add_action('wp_ajax_aqlx_quick_view', [__CLASS__, 'quick_view']);
        add_action('wp_ajax_nopriv_aqlx_quick_view', [__CLASS__, 'quick_view']);
    }

    public static function quick_view(): void {
        if (!class_exists('WooCommerce')) { wp_send_json_error('no_woo'); }
        $pid = isset($_GET['pid']) ? absint($_GET['pid']) : 0;
        if (!$pid) { wp_send_json_error('no_pid'); }
        $post = get_post($pid);
        if (!$post || $post->post_type !== 'product') { wp_send_json_error('invalid'); }
        setup_postdata($post);
        ob_start();
        echo '<div class="grid md:grid-cols-2 gap-6">';
        if (has_post_thumbnail($pid)) echo get_the_post_thumbnail($pid, 'large', ['class'=>'rounded-lg w-full h-auto','loading'=>'eager']);
        echo '<div>';
        echo '<h2 class="text-2xl font-semibold mb-2">' . esc_html(get_the_title($pid)) . '</h2>';
        echo '<div class="mb-4">' . wc_get_product($pid)->get_price_html() . '</div>';
        echo apply_filters('the_excerpt', get_the_excerpt($pid));
        echo do_shortcode('[add_to_cart id="' . $pid . '"]');
        echo '</div></div>';
        wp_send_json_success(ob_get_clean());
    }
}
