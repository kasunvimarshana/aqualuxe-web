<?php
namespace AquaLuxe\Modules\Wishlist;

const COOKIE = 'aqualuxe_wishlist';

function get_list(): array {
    if (\is_user_logged_in()) {
        $list = (array) \get_user_meta(\get_current_user_id(), '_aqualuxe_wishlist', true);
    } else {
        $list = json_decode(strval($_COOKIE[COOKIE] ?? '[]'), true) ?: [];
    }
    return array_values(array_unique(array_filter(array_map('intval', $list))));
}

function set_list(array $list): void {
    $list = array_values(array_unique(array_filter(array_map('intval', $list))));
    if (\is_user_logged_in()) {
        \update_user_meta(\get_current_user_id(), '_aqualuxe_wishlist', $list);
    } else {
        if (!headers_sent()) setcookie(COOKIE, wp_json_encode($list), time()+30*\DAY_IN_SECONDS, \COOKIEPATH, \COOKIE_DOMAIN);
    }
}

\add_action('wp_ajax_aqualuxe_wishlist_toggle', __NAMESPACE__.'\ajax_toggle');
\add_action('wp_ajax_nopriv_aqualuxe_wishlist_toggle', __NAMESPACE__.'\ajax_toggle');
function ajax_toggle(){
    \check_ajax_referer('aqualuxe_wishlist');
    $id = (int) ($_POST['id'] ?? 0);
    if (!$id) \wp_send_json_error(['message' => \__('Invalid ID', 'aqualuxe')]);
    $list = get_list();
    $has = in_array($id, $list, true);
    if ($has) { $list = array_values(array_diff($list, [$id])); } else { $list[] = $id; }
    set_list($list);
    \wp_send_json_success(['count' => count($list), 'in_list' => !$has]);
}

\add_shortcode('aqualuxe_wishlist', function(){
    if (!\class_exists('WooCommerce')) return '<p>'.\esc_html__('Wishlist requires WooCommerce.', 'aqualuxe').'</p>';
    $ids = get_list();
    if (!$ids) return '<p>'.\esc_html__('Your wishlist is empty.', 'aqualuxe').'</p>';
    return \do_shortcode('[products ids="'.implode(',', $ids).'" columns="4"]');
});
