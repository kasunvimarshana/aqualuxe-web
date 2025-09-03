<?php
// Simple wishlist using cookies/localStorage fallback; WooCommerce-agnostic.

\add_action('wp', function () {
    if (!isset($_POST['aqualuxe_wishlist_action'])) {
        return;
    }
    check_admin_referer('aqualuxe_wishlist');
    $action = sanitize_text_field(wp_unslash($_POST['aqualuxe_wishlist_action'] ?? ''));
    $post_id = absint($_POST['post_id'] ?? 0);
    $list = isset($_COOKIE['aqualuxe_wishlist']) ? explode(',', sanitize_text_field(wp_unslash($_COOKIE['aqualuxe_wishlist']))) : [];
    $list = array_filter(array_map('absint', $list));
    if ($action === 'add' && $post_id) {
        $list[] = $post_id;
    } elseif ($action === 'remove' && $post_id) {
        $list = array_diff($list, [$post_id]);
    }
    $list = array_values(array_unique($list));
    setcookie('aqualuxe_wishlist', implode(',', $list), time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
});

\add_shortcode('aqualuxe_wishlist_button', function ($atts) {
    $atts = shortcode_atts(['id' => get_the_ID()], $atts);
    $id = absint($atts['id']);
    ob_start();
    ?>
    <form method="post" class="inline">
      <?php wp_nonce_field('aqualuxe_wishlist'); ?>
      <input type="hidden" name="post_id" value="<?php echo esc_attr($id); ?>">
      <button class="btn btn-secondary" name="aqualuxe_wishlist_action" value="add" aria-label="<?php esc_attr_e('Add to wishlist', 'aqualuxe'); ?>">❤</button>
    </form>
    <?php
    return (string) ob_get_clean();
});
