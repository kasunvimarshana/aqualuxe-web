<?php
namespace AquaLuxe\Core;

if (!defined('ABSPATH')) { exit; }

class SEO {
    public static function init(): void {
        \add_action('wp_head', [__CLASS__, 'meta'], 1);
        \add_action('wp_head', [__CLASS__, 'json_ld'], 5);
    }

    public static function meta(): void {
        echo '<meta name="theme-color" content="' . esc_attr(\get_theme_mod('aqualuxe_primary_color', '#00a3b4')) . '" />';
        // Basic Open Graph
        echo '<meta property="og:site_name" content="' . esc_attr(\get_bloginfo('name')) . '" />';
        echo '<meta property="og:type" content="website" />';
    $url = \home_url(\add_query_arg([], \home_url('/')));
    echo '<meta property="og:url" content="' . esc_url($url) . '" />';
        if (\is_singular()) {
            echo '<meta property="og:title" content="' . esc_attr(\get_the_title()) . '" />';
            echo '<meta property="og:description" content="' . esc_attr(\wp_strip_all_tags(\get_the_excerpt())) . '" />';
        } else {
            echo '<meta property="og:title" content="' . esc_attr(\get_bloginfo('name')) . '" />';
            echo '<meta property="og:description" content="' . esc_attr(\get_bloginfo('description')) . '" />';
        }
    }

    public static function json_ld(): void {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => \get_bloginfo('name'),
            'url' => \home_url('/'),
            'logo' => \get_site_icon_url() ?: '',
            'sameAs' => [],
        ];
        echo '<script type="application/ld+json">' . \wp_json_encode($data) . '</script>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
