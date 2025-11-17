<?php
defined('ABSPATH') || exit;

// Register Trade-In CPT
add_action('init', function () {
    register_post_type('trade_in', [
        'label' => __('Trade-ins', 'aqualuxe'),
        'public' => false,
        'show_ui' => true,
        'supports' => ['title', 'editor', 'custom-fields'],
        'menu_icon' => 'dashicons-randomize',
    ]);
});

// Front: button after add to cart
add_action('woocommerce_after_add_to_cart_button', function () {
    global $product;
    if (!is_object($product)) return;
    echo '<button type="button" class="aqlx-tradein-open ml-2 px-3 py-2 rounded border text-sm" data-product-id="' . esc_attr($product->get_id()) . '" data-product-name="' . esc_attr($product->get_name()) . '">' . esc_html__('Request Trade-in', 'aqualuxe') . '</button>';
});

// Admin-post handler for trade-in submissions
add_action('admin_post_nopriv_aqlx_tradein_submit', 'aqlx_handle_tradein');
add_action('admin_post_aqlx_tradein_submit', 'aqlx_handle_tradein');
function aqlx_handle_tradein(): void {
    $redirect = isset($_SERVER['HTTP_REFERER']) ? esc_url_raw($_SERVER['HTTP_REFERER']) : home_url('/');
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'aqlx_tradein')) {
        wp_safe_redirect(add_query_arg('error', rawurlencode(__('Invalid form token.', 'aqualuxe')), $redirect));
        exit;
    }
    // Honeypot
    if (!empty($_POST['website'])) {
        wp_safe_redirect($redirect);
        exit;
    }
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $details = isset($_POST['details']) ? wp_kses_post(wp_unslash($_POST['details'])) : '';
    if (!$product_id || !$name || !$email || !is_email($email)) {
        wp_safe_redirect(add_query_arg('error', rawurlencode(__('Please complete required fields.', 'aqualuxe')), $redirect));
        exit;
    }
    $title = sprintf(__('Trade-in request: %1$s (%2$d)', 'aqualuxe'), get_the_title($product_id), $product_id);
    $content = wpautop(sprintf('<strong>Product:</strong> %1$s (#%2$d)<br><strong>Name:</strong> %3$s<br><strong>Email:</strong> %4$s<br><strong>Details:</strong><br>%5$s',
        esc_html(get_the_title($product_id)), $product_id, esc_html($name), esc_html($email), wp_kses_post($details)));
    $post_id = wp_insert_post([
        'post_type' => 'trade_in',
        'post_title' => $title,
        'post_content' => $content,
        'post_status' => 'publish',
        'meta_input' => [
            '_aqlx_tradein_product' => $product_id,
            '_aqlx_tradein_email' => $email,
            '_aqlx_tradein_name' => $name,
        ],
    ]);
    $to = get_option('admin_email');
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>'
    ];
    wp_mail($to, __('New trade-in request', 'aqualuxe'), $content, $headers);
    wp_safe_redirect(add_query_arg('sent', '1', $redirect));
    exit;
}
