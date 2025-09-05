<?php
/**
 * Quick View partial (dual-state: WooCommerce product or regular post)
 *
 * Expected context from REST:
 *  - $context = [ 'type' => 'product', 'product' => WC_Product ]
 *  or
 *  - $context = [ 'type' => 'post', 'post' => WP_Post ]
 */

if (!isset($context) || !is_array($context)) {
    $msg = 'Nothing to preview.';
    if (function_exists('esc_html__')) { $msg = call_user_func('esc_html__', 'Nothing to preview.', 'aqualuxe'); }
    echo '<div class="p-6">' . $msg . '</div>';
    return;
}

$type = (string) ($context['type'] ?? '');

if ($type === 'product' && class_exists('WooCommerce')) {
    /** @var WC_Product $product */
    $product = $context['product'] ?? null;
    if (!$product) {
        $msg = 'Product unavailable.';
        if (function_exists('esc_html__')) { $msg = call_user_func('esc_html__', 'Product unavailable.', 'aqualuxe'); }
        echo '<div class="p-6">' . $msg . '</div>';
        return;
    }
    $id = method_exists($product, 'get_id') ? (int) $product->get_id() : 0;
    $name = method_exists($product, 'get_name') ? $product->get_name() : '';
    $name = function_exists('esc_html') ? call_user_func('esc_html', $name) : $name;
    $permalink = function_exists('get_permalink') ? call_user_func('get_permalink', $id) : '#';
    $permalink = function_exists('esc_url') ? call_user_func('esc_url', $permalink) : $permalink;
    $price_html = method_exists($product, 'get_price_html') ? $product->get_price_html() : '';
    $short = method_exists($product, 'get_short_description') ? $product->get_short_description() : '';
    if (!$short && method_exists($product, 'get_description')) { $short = $product->get_description(); }
    $short = function_exists('wp_kses_post') ? call_user_func('wp_kses_post', $short) : htmlspecialchars((string) $short);
    $img = method_exists($product, 'get_image') ? $product->get_image('large', [ 'class' => 'w-full h-auto rounded' ]) : '';
    $img = is_string($img) ? $img : '';
    $price_html = is_string($price_html) ? $price_html : '';

    // Add to cart fallback link (no JS required)
    $atc_url = method_exists($product, 'add_to_cart_url') ? $product->add_to_cart_url() : $permalink;
    $atc_text = method_exists($product, 'add_to_cart_text') ? $product->add_to_cart_text() : (function_exists('esc_html__') ? call_user_func('esc_html__', 'Add to cart', 'aqualuxe') : 'Add to cart');
    $atc_url = function_exists('esc_url') ? call_user_func('esc_url', $atc_url) : $atc_url;
    $atc_text = function_exists('esc_html') ? call_user_func('esc_html', $atc_text) : $atc_text;

    echo '<div class="grid gap-6 md:grid-cols-2">';
    echo '<div class="qv-media">' . $img . '</div>';
    echo '<div class="qv-body">';
    echo '<h3 class="text-xl font-semibold mb-2">' . $name . '</h3>';
    echo '<div class="text-lg mb-3">' . $price_html . '</div>';
    echo '<div class="prose dark:prose-invert mb-4">' . $short . '</div>';
    echo '<div class="flex items-center gap-3">';
    echo '<a class="btn btn-primary" href="' . $atc_url . '">' . $atc_text . '</a>';
    $view_text = function_exists('esc_html__') ? call_user_func('esc_html__', 'View details', 'aqualuxe') : 'View details';
    echo '<a class="btn btn-secondary" href="' . $permalink . '">' . $view_text . '</a>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    return;
}

// Fallback: render a post/page preview
/** @var WP_Post|null $post */
$post = $context['post'] ?? null;
if (!$post) {
    $msg = 'Preview unavailable.';
    if (function_exists('esc_html__')) { $msg = call_user_func('esc_html__', 'Preview unavailable.', 'aqualuxe'); }
    echo '<div class="p-6">' . $msg . '</div>';
    return;
}
$title = function_exists('get_the_title') ? call_user_func('get_the_title', $post) : ($post->post_title ?? '');
$title = function_exists('esc_html') ? call_user_func('esc_html', $title) : $title;
$permalink = function_exists('get_permalink') ? call_user_func('get_permalink', $post) : '#';
$permalink = function_exists('esc_url') ? call_user_func('esc_url', $permalink) : $permalink;
$thumb = function_exists('get_the_post_thumbnail') ? call_user_func('get_the_post_thumbnail', $post, 'large', [ 'class' => 'w-full h-auto rounded' ]) : '';
$excerpt = function_exists('get_the_excerpt') ? call_user_func('get_the_excerpt', $post) : '';
if (!$excerpt && function_exists('wp_strip_all_tags') && function_exists('wp_trim_words')) {
    $stripped = call_user_func('wp_strip_all_tags', (string) ($post->post_content ?? ''));
    $excerpt = call_user_func('wp_trim_words', $stripped, 36);
}
$excerpt = function_exists('wp_kses_post') ? call_user_func('wp_kses_post', $excerpt) : htmlspecialchars((string) $excerpt);

echo '<div class="grid gap-6 md:grid-cols-2">';
echo '<div class="qv-media">' . $thumb . '</div>';
echo '<div class="qv-body">';
echo '<h3 class="text-xl font-semibold mb-2">' . $title . '</h3>';
echo '<div class="prose dark:prose-invert mb-4">' . $excerpt . '</div>';
$read_text = function_exists('esc_html__') ? call_user_func('esc_html__', 'Read more', 'aqualuxe') : 'Read more';
echo '<a class="btn btn-secondary" href="' . $permalink . '">' . $read_text . '</a>';
echo '</div>';
echo '</div>';
