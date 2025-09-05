<?php
// Basic SEO helpers and schema hooks
\add_action('wp_head', function(){
    echo '<meta name="format-detection" content="telephone=no">' . "\n";
    // Canonical
    if (function_exists('wp_get_canonical_url')) {
        $canonical = wp_get_canonical_url();
        if ($canonical) { echo '<link rel="canonical" href="' . esc_url($canonical) . '" />' . "\n"; }
    }
    // Meta description (fallback)
    if (is_singular()) {
        $desc = get_the_excerpt();
    } else {
        $desc = get_bloginfo('description');
    }
    if ($desc) {
        $desc = wp_strip_all_tags((string) $desc);
        $desc = mb_substr($desc, 0, 160);
        echo '<meta name="description" content="' . esc_attr($desc) . '">' . "\n";
    }
    // JSON-LD Organization schema
    $name = get_bloginfo('name');
    $url = home_url('/');
    $logo = get_site_icon_url();
    echo '<script type="application/ld+json">' . wp_json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $name,
        'url' => $url,
        'logo' => $logo,
    ]) . '</script>' . "\n";
});
\add_filter('language_attributes', function($atts){
    // Ensure proper dir attr is present for accessibility
    if (is_rtl()) { $atts .= ' dir="rtl"'; }
    return $atts;
});

// Product schema when WooCommerce is active
if (class_exists('WooCommerce')) {
    add_action('wp_head', function(){
        if (!is_product()) return;
        global $product; if (!$product) return;
        $imageUrls = [];
        if (method_exists($product, 'get_gallery_image_ids')) {
            $ids = (array) $product->get_gallery_image_ids();
            foreach ($ids as $id) {
                $u = wp_get_attachment_url((int) $id);
                if ($u) { $imageUrls[] = esc_url_raw($u); }
            }
        }
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => wp_strip_all_tags($product->get_name()),
            'image' => $imageUrls,
            'description' => wp_strip_all_tags($product->get_short_description() ?: $product->get_description()),
            'sku' => method_exists($product, 'get_sku') ? $product->get_sku() : '',
            'offers' => [
                '@type' => 'Offer',
                'priceCurrency' => apply_filters('aqualuxe/currency/current', get_woocommerce_currency()),
                'price' => (string) (method_exists($product, 'get_price') ? $product->get_price() : ''),
                'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => get_permalink($product->get_id()),
            ],
        ];
        echo '<script type="application/ld+json">' . wp_json_encode($data) . '</script>' . "\n";
    });
}
