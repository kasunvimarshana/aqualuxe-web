<?php
defined('ABSPATH') || exit;

// Determine if prices should be hidden for current user (default: hide for guests)
if (!function_exists('aqlx_b2b_hide_prices')) {
    function aqlx_b2b_hide_prices(): bool
    {
        $default = (bool) get_theme_mod('aqlx_b2b_hide_guest_prices', true);
        $hide = $default && !is_user_logged_in();
        return (bool) apply_filters('aqlx_b2b_hide_prices', $hide);
    }
}

// Hide WooCommerce prices and purchasing if enabled
add_filter('woocommerce_get_price_html', function ($price, $product) {
    if (aqlx_b2b_hide_prices()) {
        return '';
    }
    return $price;
}, 20, 2);

add_filter('woocommerce_is_purchasable', function ($purchasable, $product) {
    if (aqlx_b2b_hide_prices()) return false;
    return $purchasable;
}, 20, 2);

// Add Request Quote button on single product and loop
function aqlx_b2b_quote_button($prod = null): void
{
    if (!aqlx_b2b_hide_prices()) return;
    if (!$prod) {
        global $product; // from Woo template context
        $prod = $product;
    }
    if (!is_object($prod)) return;
    echo '<button type="button" class="aqlx-quote-open ml-2 px-3 py-2 rounded border text-sm" data-product-id="' . esc_attr($prod->get_id()) . '" data-product-name="' . esc_attr($prod->get_name()) . '">' . esc_html__('Request Quote', 'aqualuxe') . '</button>';
}

add_action('woocommerce_after_add_to_cart_button', 'aqlx_b2b_quote_button');
add_action('woocommerce_after_shop_loop_item', 'aqlx_b2b_quote_button', 20);

// Show B2B notice on single product when prices hidden
add_action('woocommerce_single_product_summary', function () {
    if (!aqlx_b2b_hide_prices()) return;
    $msg = get_theme_mod('aqlx_b2b_notice', __('Please log in to view prices and request a quote.', 'aqualuxe'));
    echo '<p class="mt-3 text-sm text-slate-600 dark:text-slate-300">' . wp_kses_post($msg) . '</p>';
}, 6);

// Register Quote Request CPT
add_action('init', function () {
    register_post_type('quote_request', [
        'label' => __('Quote Requests', 'aqualuxe'),
        'public' => false,
        'show_ui' => true,
        'supports' => ['title', 'editor', 'custom-fields'],
        'menu_icon' => 'dashicons-media-spreadsheet',
    ]);
});

// Admin-post handler
add_action('admin_post_nopriv_aqlx_quote_submit', 'aqlx_handle_quote');
add_action('admin_post_aqlx_quote_submit', 'aqlx_handle_quote');
function aqlx_handle_quote(): void
{
    $redirect = isset($_SERVER['HTTP_REFERER']) ? esc_url_raw($_SERVER['HTTP_REFERER']) : home_url('/');
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'aqlx_quote')) {
        wp_safe_redirect(add_query_arg('error', rawurlencode(__('Invalid form token.', 'aqualuxe')), $redirect));
        exit;
    }
    // Honeypot
    if (!empty($_POST['company_site'])) {
        wp_safe_redirect($redirect);
        exit;
    }
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $company = isset($_POST['company']) ? sanitize_text_field(wp_unslash($_POST['company'])) : '';
    $qty = isset($_POST['quantity']) ? max(1, (int) wp_unslash($_POST['quantity'])) : 1;
    $notes = isset($_POST['notes']) ? wp_kses_post(wp_unslash($_POST['notes'])) : '';
    if (!$product_id || !$name || !$email || !is_email($email)) {
        wp_safe_redirect(add_query_arg('error', rawurlencode(__('Please complete required fields.', 'aqualuxe')), $redirect));
        exit;
    }
    $title = sprintf(__('Quote request: %1$s x%2$d', 'aqualuxe'), get_the_title($product_id), $qty);
    $content = wpautop(sprintf('<strong>Product:</strong> %1$s (#%2$d)<br><strong>Qty:</strong> %3$d<br><strong>Name:</strong> %4$s<br><strong>Email:</strong> %5$s<br><strong>Company:</strong> %6$s<br><strong>Notes:</strong><br>%7$s',
        esc_html(get_the_title($product_id)), $product_id, $qty, esc_html($name), esc_html($email), esc_html($company), wp_kses_post($notes)));
    $post_id = wp_insert_post([
        'post_type' => 'quote_request',
        'post_title' => $title,
        'post_content' => $content,
        'post_status' => 'publish',
        'meta_input' => [
            '_aqlx_quote_product' => $product_id,
            '_aqlx_quote_email' => $email,
            '_aqlx_quote_name' => $name,
            '_aqlx_quote_company' => $company,
            '_aqlx_quote_qty' => $qty,
        ],
    ]);
    $to = get_option('admin_email');
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>'
    ];
    wp_mail($to, __('New quote request', 'aqualuxe'), $content, $headers);
    wp_safe_redirect(add_query_arg('sent', '1', $redirect));
    exit;
}
