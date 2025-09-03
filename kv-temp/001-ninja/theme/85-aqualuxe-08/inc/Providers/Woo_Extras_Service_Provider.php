<?php
namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class Woo_Extras_Service_Provider
{
    public function register(Container $c): void
    {
        if (!\class_exists('WooCommerce')) { return; }

        // Loop UI buttons
        if (\function_exists('add_action')) { \call_user_func('add_action', 'woocommerce_after_shop_loop_item', [$this, 'render_loop_actions'], 20); }

        // AJAX: Quick View
        if (\function_exists('add_action')) {
            \call_user_func('add_action', 'wp_ajax_aqlx_quick_view', [$this, 'ajax_quick_view']);
            \call_user_func('add_action', 'wp_ajax_nopriv_aqlx_quick_view', [$this, 'ajax_quick_view']);
        }

        // AJAX: Wishlist toggle (logged-in only; guests use localStorage)
        if (\function_exists('add_action')) { \call_user_func('add_action', 'wp_ajax_aqlx_toggle_wishlist', [$this, 'ajax_toggle_wishlist']); }
    }

    public function boot(Container $c): void {}

    public function render_loop_actions(): void
    {
        global $product;
        if (!$product || !\is_object($product)) { return; }
        $pid = (int) $product->get_id();
        $esc_attr = function($v){ return \function_exists('esc_attr') ? \call_user_func('esc_attr', $v) : $v; };
        $esc_html__ = function($t){ return \function_exists('esc_html__') ? \call_user_func('esc_html__', $t, 'aqualuxe') : $t; };
        echo '<div class="aqlx-loop-actions">'
            . '<button type="button" class="aqlx-btn aqlx-btn--ghost aqlx-quick-view" data-product="' . $esc_attr($pid) . '" aria-haspopup="dialog">' . $esc_html__('Quick View') . '</button>'
            . '<button type="button" class="aqlx-btn aqlx-btn--outline aqlx-wishlist-toggle" data-product="' . $esc_attr($pid) . '">' . $esc_html__('Wishlist') . '</button>'
            . '</div>';
    }

    public function ajax_quick_view(): void
    {
        if (\function_exists('check_ajax_referer')) { \call_user_func('check_ajax_referer', 'aqlx_ajax', 'nonce'); }
        $pid = isset($_REQUEST['product']) ? (int) $_REQUEST['product'] : 0;
        if ($pid <= 0) {
            if (\function_exists('wp_send_json_error')) { \call_user_func('wp_send_json_error', ['message' => 'Invalid product'], 400); } else { echo json_encode(['success'=>false]); }
            exit;
        }
        $post = \function_exists('get_post') ? \call_user_func('get_post', $pid) : null;
        if (!$post || $post->post_type !== 'product') {
            if (\function_exists('wp_send_json_error')) { \call_user_func('wp_send_json_error', ['message' => 'Not found'], 404); } else { echo json_encode(['success'=>false]); }
            exit;
        }

        if (\function_exists('setup_postdata')) { \call_user_func('setup_postdata', $post); }
        ob_start();
        $template = \function_exists('locate_template') ? \call_user_func('locate_template', 'templates/woocommerce/quick-view.php') : '';
        if ($template) {
            include $template;
        } else {
            $title = \function_exists('get_the_title') ? \call_user_func('get_the_title', $pid) : '';
            $esc_html = \function_exists('esc_html') ? 'esc_html' : null;
            echo '<div class="aqlx-quick-view-fallback">' . ($esc_html ? \call_user_func($esc_html, $title) : $title) . '</div>';
        }
        $html = ob_get_clean();
        if (\function_exists('wp_reset_postdata')) { \call_user_func('wp_reset_postdata'); }
        if (\function_exists('wp_send_json_success')) { \call_user_func('wp_send_json_success', ['html' => $html]); } else { echo json_encode(['success'=>true,'data'=>['html'=>$html]]); }
    }

    public function ajax_toggle_wishlist(): void
    {
        if (\function_exists('check_ajax_referer')) { \call_user_func('check_ajax_referer', 'aqlx_ajax', 'nonce'); }
        if (!\function_exists('is_user_logged_in') || !\call_user_func('is_user_logged_in')) {
            if (\function_exists('wp_send_json_error')) { \call_user_func('wp_send_json_error', ['message' => 'Auth required'], 401); } else { echo json_encode(['success'=>false]); }
            exit;
        }
        $pid = isset($_POST['product']) ? (int) $_POST['product'] : 0;
        if ($pid <= 0) {
            if (\function_exists('wp_send_json_error')) { \call_user_func('wp_send_json_error', ['message' => 'Invalid product'], 400); } else { echo json_encode(['success'=>false]); }
            exit;
        }
        $user_id = \function_exists('get_current_user_id') ? \call_user_func('get_current_user_id') : 0;
        $list = \function_exists('get_user_meta') ? (array) \call_user_func('get_user_meta', $user_id, '_aqlx_wishlist', true) : [];
        $list = array_map('intval', $list);
        if (in_array($pid, $list, true)) {
            $list = array_values(array_diff($list, [$pid]));
            $state = 'removed';
        } else {
            $list[] = $pid;
            $list = array_values(array_unique($list));
            $state = 'added';
        }
        if (\function_exists('update_user_meta')) { \call_user_func('update_user_meta', $user_id, '_aqlx_wishlist', $list); }
        if (\function_exists('wp_send_json_success')) { \call_user_func('wp_send_json_success', ['state' => $state, 'count' => count($list)]); } else { echo json_encode(['success'=>true]); }
    }
}
