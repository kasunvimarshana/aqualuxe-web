<?php
// Basic SEO helpers and schema hooks
\add_action('wp_head', function(){
    echo '<meta name="format-detection" content="telephone=no">' . "\n";
    // JSON-LD basic Organization schema
    $name = esc_js(get_bloginfo('name'));
    $url = esc_url(home_url('/'));
    $logo = esc_url(get_site_icon_url());
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
