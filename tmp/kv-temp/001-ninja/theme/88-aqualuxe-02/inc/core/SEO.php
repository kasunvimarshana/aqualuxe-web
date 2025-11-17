<?php

declare(strict_types=1);

namespace AquaLuxe\Core;

class SEO
{
    public static function init(): void
    {
        Helpers::wp('add_action', ['wp_head', [self::class, 'meta'], 1]);
    }

    public static function meta(): void
    {
    $siteName = Helpers::wp('get_bloginfo', ['name']) ?? '';
    $desc = Helpers::wp('get_bloginfo', ['description']) ?? '';
    $url = home_url('/');
        echo "\n<!-- AquaLuxe SEO -->\n";
        echo '<meta name="description" content="' . esc_attr((string) $desc) . '">';
        echo '\n<meta property="og:site_name" content="' . esc_attr((string) $siteName) . '">';
        echo '\n<meta property="og:type" content="website">';
        echo '\n<meta property="og:url" content="' . esc_url((string) $url) . '">';
    $title = Helpers::wp('wp_get_document_title') ?? '';
    echo '\n<meta property="og:title" content="' . esc_attr((string) $title) . '">';
        echo '\n<meta property="og:description" content="' . esc_attr((string) $desc) . '">';
        echo "\n<script type=\"application/ld+json\">" . wp_json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => (string) $siteName,
            'url' => (string) $url,
        ]) . '</script>';
        echo "\n<!-- /AquaLuxe SEO -->\n";
    }
}
