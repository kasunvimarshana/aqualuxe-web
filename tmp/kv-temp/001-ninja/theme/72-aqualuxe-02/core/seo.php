<?php
namespace AquaLuxe\Core;

class SEO {
    public static function init(): void {
        add_action('wp_head', [__CLASS__, 'opengraph'], 5);
        add_action('wp_head', [__CLASS__, 'jsonld'], 5);
    }

    public static function opengraph(): void {
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '"/>';
        echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '"/>';
        if (is_singular()) {
            global $post; $title = get_the_title($post); $desc = get_the_excerpt($post);
            echo '<meta property="og:type" content="article"/>';
            echo '<meta property="og:title" content="' . esc_attr($title) . '"/>';
            echo '<meta property="og:description" content="' . esc_attr(wp_strip_all_tags($desc)) . '"/>';
            echo '<meta property="og:url" content="' . esc_url(get_permalink($post)) . '"/>';
        } else {
            echo '<meta property="og:type" content="website"/>';
            echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '"/>';
            echo '<meta property="og:description" content="' . esc_attr(get_bloginfo('description')) . '"/>';
            echo '<meta property="og:url" content="' . esc_url(home_url('/')) . '"/>';
        }
    }

    public static function jsonld(): void {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url('/'),
            'logo' => get_site_icon_url() ?: '',
        ];
        echo '<script type="application/ld+json">' . wp_json_encode($data) . '</script>';
    }
}
