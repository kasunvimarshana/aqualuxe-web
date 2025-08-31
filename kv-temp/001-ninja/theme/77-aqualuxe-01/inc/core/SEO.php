<?php
namespace AquaLuxe\Core;

if (!defined('ABSPATH')) exit;

class SEO {
    public static function boot(): void {
        add_action('wp_head', [__CLASS__, 'meta'], 2);
    }

    public static function meta(): void {
        // Basic Open Graph & schema.org Website.
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />';
        echo '<meta property="og:type" content="website" />';
        echo '<meta name="theme-color" content="#0ea5e9" />';
        // JSON-LD
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url('/'),
        ];
        echo '<script type="application/ld+json">' . wp_json_encode($data) . '</script>';
    }
}
