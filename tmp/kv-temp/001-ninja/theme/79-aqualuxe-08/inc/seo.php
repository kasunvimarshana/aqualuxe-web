<?php
declare(strict_types=1);

// Basic SEO meta and Open Graph tags
add_action('wp_head', static function (): void {
    if (is_admin()) { return; }
    $title = wp_get_document_title();
    $desc = get_bloginfo('description');
    if (is_singular() && has_excerpt()) { $desc = wp_strip_all_tags(get_the_excerpt()); }
    $url = home_url(add_query_arg([]));
    echo "\n<meta name=\"description\" content=\"" . esc_attr($desc) . "\">";
    echo "\n<meta property=\"og:title\" content=\"" . esc_attr($title) . "\">";
    echo "\n<meta property=\"og:description\" content=\"" . esc_attr($desc) . "\">";
    echo "\n<meta property=\"og:url\" content=\"" . esc_url($url) . "\">";
    echo "\n<meta property=\"og:type\" content=\"" . (is_singular() ? 'article' : 'website') . "\">";
    if (has_post_thumbnail()) {
        $img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
    if ($img) { echo "\n<meta property=\"og:image\" content=\"" . esc_url($img[0]) . "\">"; }
    }
    // Organization schema
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'url' => home_url('/'),
    ];
    echo "\n<script type=\"application/ld+json\">" . wp_json_encode($schema) . "</script>\n";
});
