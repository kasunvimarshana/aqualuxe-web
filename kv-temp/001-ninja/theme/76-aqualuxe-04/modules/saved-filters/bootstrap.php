<?php
defined('ABSPATH') || exit;

// CPT to store saved filters per user
add_action('init', function () {
    register_post_type('saved_filter', [
        'label' => __('Saved Filters', 'aqualuxe'),
        'public' => false,
        'show_ui' => true,
        'supports' => ['title', 'custom-fields'],
        'show_in_menu' => 'edit.php?post_type=product',
        'menu_icon' => 'dashicons-filter',
    ]);
});

// UI: Save current filters button above shop loop
add_action('woocommerce_before_shop_loop', function () {
    if (!is_user_logged_in()) return;
    if (!(function_exists('is_shop') && (is_shop() || is_product_taxonomy()))) return;
    // Capture the current URL (path + query) for saving
    $scheme = is_ssl() ? 'https' : 'http';
    $host   = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : parse_url(home_url('/'), PHP_URL_HOST);
    $uri    = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    $referer = esc_url_raw($scheme . '://' . $host . $uri);
    echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" class="mb-4 flex items-center gap-2">';
    wp_nonce_field('aqlx_save_filters');
    echo '<input type="hidden" name="action" value="aqlx_save_filters" />';
    echo '<input type="hidden" name="referer" value="' . esc_attr($referer) . '" />';
    echo '<input name="label" type="text" class="border rounded px-2 py-1 text-sm" placeholder="' . esc_attr__('Name this filter set (optional)', 'aqualuxe') . '" />';
    echo '<button class="px-3 py-2 rounded bg-slate-200 dark:bg-slate-800 text-sm" type="submit">' . esc_html__('Save filters', 'aqualuxe') . '</button>';
    echo '</form>';
}, 8);

// Shortcode: list saved filters for current user
add_shortcode('aqlx_saved_filters', function ($atts) {
    if (!is_user_logged_in()) return '';
    $uid = get_current_user_id();
    $posts = get_posts([
        'post_type' => 'saved_filter',
        'post_status' => 'publish',
        'author' => $uid,
        'numberposts' => 50,
        'orderby' => 'date',
        'order' => 'DESC',
    ]);
    if (!$posts) return '';
    $shop = function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('shop')) : home_url('/');
    ob_start();
    echo '<ul class="space-y-2">';
    foreach ($posts as $p) {
        $q = get_post_meta($p->ID, '_aqlx_query', true);
        $apply = $shop . ($q ? ('?' . ltrim($q, '?')) : '');
        echo '<li class="flex items-center gap-2">';
        echo '<a class="text-sky-700 underline" href="' . esc_url($apply) . '">' . esc_html(get_the_title($p)) . '</a>';
        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" onsubmit="return confirm(\'' . esc_attr__('Delete this saved filter?', 'aqualuxe') . '\');">';
        wp_nonce_field('aqlx_delete_filter');
        echo '<input type="hidden" name="action" value="aqlx_delete_filter" />';
        echo '<input type="hidden" name="id" value="' . esc_attr($p->ID) . '" />';
        echo '<button class="text-sm text-red-600 hover:underline" type="submit">' . esc_html__('Delete', 'aqualuxe') . '</button>';
        echo '</form>';
        echo '</li>';
    }
    echo '</ul>';
    return ob_get_clean();
});

// Handlers
add_action('admin_post_aqlx_save_filters', 'aqlx_handle_save_filters');
add_action('admin_post_nopriv_aqlx_save_filters', 'aqlx_handle_save_filters');
function aqlx_handle_save_filters(): void
{
    if (!is_user_logged_in()) wp_die(__('You must be logged in.', 'aqualuxe'));
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'aqlx_save_filters')) wp_die(__('Invalid token', 'aqualuxe'));
    $referer = isset($_POST['referer']) ? esc_url_raw(wp_unslash($_POST['referer'])) : '';
    if (!$referer) $referer = home_url(add_query_arg(null, null));
    $parts = wp_parse_url($referer);
    $query = isset($parts['query']) ? $parts['query'] : '';
    // Remove paged for portability
    if ($query) {
        parse_str($query, $args);
        unset($args['paged']);
        $query = http_build_query($args);
    }
    $label = isset($_POST['label']) && $_POST['label'] !== '' ? sanitize_text_field(wp_unslash($_POST['label'])) : sprintf(__('Filters %s', 'aqualuxe'), date_i18n(get_option('date_format')));
    $pid = wp_insert_post([
        'post_type' => 'saved_filter',
        'post_status' => 'publish',
        'post_title' => $label,
        'post_author' => get_current_user_id(),
        'meta_input' => ['_aqlx_query' => $query],
    ]);
    $back = isset($_SERVER['HTTP_REFERER']) ? esc_url_raw($_SERVER['HTTP_REFERER']) : home_url('/');
    wp_safe_redirect(add_query_arg('sent', '1', $back));
    exit;
}

add_action('admin_post_aqlx_delete_filter', 'aqlx_handle_delete_filter');
add_action('admin_post_nopriv_aqlx_delete_filter', 'aqlx_handle_delete_filter');
function aqlx_handle_delete_filter(): void
{
    if (!is_user_logged_in()) wp_die(__('You must be logged in.', 'aqualuxe'));
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'aqlx_delete_filter')) wp_die(__('Invalid token', 'aqualuxe'));
    $id = isset($_POST['id']) ? absint($_POST['id']) : 0;
    $post = $id ? get_post($id) : null;
    if ($post && (int) $post->post_author === get_current_user_id() && $post->post_type === 'saved_filter') {
        wp_delete_post($id, true);
    }
    $back = isset($_SERVER['HTTP_REFERER']) ? esc_url_raw($_SERVER['HTTP_REFERER']) : home_url('/');
    wp_safe_redirect(add_query_arg('sent', '1', $back));
    exit;
}
