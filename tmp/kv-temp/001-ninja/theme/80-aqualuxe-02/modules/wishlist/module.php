<?php
/** Minimal wishlist module (cookie-based) */

add_action('wp_ajax_aqualuxe_wishlist_toggle', 'aqualuxe_wishlist_toggle');
add_action('wp_ajax_nopriv_aqualuxe_wishlist_toggle', 'aqualuxe_wishlist_toggle');

function aqualuxe_wishlist_toggle(){
    check_ajax_referer('aqualuxe', 'nonce');
    $id = absint($_POST['product_id'] ?? 0);
    if (!$id) wp_send_json_error('invalid');
    $list = isset($_COOKIE['aqualuxe_wishlist']) ? array_map('absint', array_filter(explode(',', $_COOKIE['aqualuxe_wishlist']))) : [];
    if (in_array($id, $list, true)) { $list = array_values(array_diff($list, [$id])); } else { $list[] = $id; }
    setcookie('aqualuxe_wishlist', implode(',', $list), time()+YEAR_IN_SECONDS, COOKIEPATH ?: '/');
    wp_send_json_success(['count'=>count($list), 'items'=>$list]);
}

add_shortcode('aqualuxe_wishlist', function(){
    $list = isset($_COOKIE['aqualuxe_wishlist']) ? array_map('absint', array_filter(explode(',', $_COOKIE['aqualuxe_wishlist']))) : [];
    if (empty($list)) return '<p>' . esc_html__('Wishlist is empty.', 'aqualuxe') . '</p>';
    $out = '<ul class="grid grid-cols-2 md:grid-cols-4 gap-4">';
    foreach ($list as $id) {
        $out .= '<li><a href="' . esc_url(get_permalink($id)) . '">' . get_the_post_thumbnail($id, 'medium', ['class'=>'rounded']) . '<br/>' . esc_html(get_the_title($id)) . '</a></li>';
    }
    return $out . '</ul>';
});
