<?php
/** Basic SEO: Open Graph and JSON-LD schema */

add_action('wp_head', function(){
    $title = wp_get_document_title();
    $desc = get_bloginfo('description');
    if (is_singular() && has_excerpt()) { $desc = get_the_excerpt(); }
    $url = esc_url(home_url(add_query_arg([])));
    $img = '';
    if (is_singular() && has_post_thumbnail()) {
        $img = wp_get_attachment_image_url(get_post_thumbnail_id(), 'large');
    }
    echo '\n<meta property="og:title" content="' . esc_attr($title) . '" />';
    echo '\n<meta property="og:description" content="' . esc_attr(wp_strip_all_tags($desc)) . '" />';
    echo '\n<meta property="og:url" content="' . esc_url($url) . '" />';
    if ($img) echo '\n<meta property="og:image" content="' . esc_url($img) . '" />';
    echo '\n<meta name="twitter:card" content="summary_large_image" />';

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => is_singular('product') ? 'Product' : (is_front_page() ? 'WebSite' : 'WebPage'),
        'name' => $title,
        'url' => $url,
        'description' => wp_strip_all_tags($desc),
    ];
    echo "\n<script type=\"application/ld+json\">" . wp_json_encode($schema) . "</script>\n";
}, 5);
