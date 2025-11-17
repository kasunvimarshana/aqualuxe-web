<?php
/** Enhanced SEO: canonical, Open Graph, Twitter cards, and JSON-LD (guarded) */

add_action('wp_head', function(){
    // Basic fields
    $title = wp_get_document_title();
    $site_name = get_bloginfo('name');
    $desc = get_bloginfo('description');
    if (is_singular()) {
        if (has_excerpt()) {
            $desc = get_the_excerpt();
        } else {
            $content = get_post_field('post_content', get_the_ID());
            $desc = wp_trim_words(wp_strip_all_tags($content), 30, '…');
        }
    }
    $url = is_singular() ? get_permalink() : home_url(add_query_arg([]));
    $img = '';
    if (is_singular() && has_post_thumbnail()) {
        $img = wp_get_attachment_image_url(get_post_thumbnail_id(), 'large');
    } elseif (function_exists('has_site_icon') && has_site_icon()) {
        $img = get_site_icon_url(512);
    }

    // Canonical and robots helpers
    echo "\n<link rel=\"canonical\" href=\"" . esc_url($url) . "\" />";
    if (is_search() || is_404()) {
        echo "\n<meta name=\"robots\" content=\"noindex,follow\" />";
    }

    // Open Graph
    $og_type = 'website';
    if (is_singular('product')) $og_type = 'product';
    elseif (is_singular('post')) $og_type = 'article';
    elseif (!is_front_page()) $og_type = 'webpage';

    $og = [
        'og:title' => $title,
        'og:description' => wp_strip_all_tags($desc),
        'og:url' => $url,
        'og:site_name' => $site_name,
        'og:type' => $og_type,
    ];
    if ($img) $og['og:image'] = $img;
    $og = apply_filters('aqualuxe/seo/og', $og);
    foreach ($og as $k=>$v) {
        echo "\n<meta property=\"" . esc_attr($k) . "\" content=\"" . esc_attr($v) . "\" />";
    }

    // Twitter
    echo "\n<meta name=\"twitter:card\" content=\"summary_large_image\" />";
    echo "\n<meta name=\"twitter:title\" content=\"" . esc_attr($title) . "\" />";
    if ($desc) echo "\n<meta name=\"twitter:description\" content=\"" . esc_attr(wp_strip_all_tags($desc)) . "\" />";
    if ($img) echo "\n<meta name=\"twitter:image\" content=\"" . esc_url($img) . "\" />";

    // JSON-LD
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => (is_singular('product') ? 'Product' : (is_front_page() ? 'WebSite' : 'WebPage')),
        'name' => $title,
        'url' => $url,
        'description' => wp_strip_all_tags($desc),
    ];

    // Product enrichment (guarded)
    if (is_singular('product')) {
        try {
            if (function_exists('wc_get_product')) {
                $product = call_user_func('wc_get_product', get_the_ID());
                if ($product) {
                    $schema['image'] = $img ? [$img] : [];
                    if (method_exists($product, 'get_sku')) $schema['sku'] = (string) $product->get_sku();
                    $offers = [
                        '@type' => 'Offer',
                        'url' => $url,
                        'price' => method_exists($product, 'get_price') ? (string) $product->get_price() : null,
                        'priceCurrency' => function_exists('get_woocommerce_currency') ? call_user_func('get_woocommerce_currency') : null,
                        'availability' => (method_exists($product, 'is_in_stock') && $product->is_in_stock()) ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                    ];
                    // Clean nulls
                    $offers = array_filter($offers, function($v){ return $v !== null && $v !== ''; });
                    $schema['offers'] = $offers;
                }
            }
        } catch (\Throwable $e) { /* ignore */ }
    }

    $schema = apply_filters('aqualuxe/seo/schema', $schema);
    echo "\n<script type=\"application/ld+json\">" . wp_json_encode($schema) . "</script>\n";
}, 5);
