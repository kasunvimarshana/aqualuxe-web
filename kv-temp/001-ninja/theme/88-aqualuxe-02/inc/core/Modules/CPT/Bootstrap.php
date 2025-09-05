<?php

declare(strict_types=1);

namespace AquaLuxe\Core\Modules\CPT;

use AquaLuxe\Core\Helpers;

class Bootstrap
{
    public static function init(): void
    {
        Helpers::wp('add_action', ['init', [self::class, 'register_types']]);
        Helpers::wp('add_shortcode', ['aqualuxe_services', [self::class, 'shortcode_services']]);
    }

    public static function register_types(): void
    {
        // Services CPT
        Helpers::wp('register_post_type', ['service', [
            'labels' => [
                'name' => __('Services', 'aqualuxe'),
                'singular_name' => __('Service', 'aqualuxe'),
            ],
            'public' => true,
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-hammer',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
            'has_archive' => true,
            'rewrite' => ['slug' => 'services'],
        ]]);

        // Events CPT
        Helpers::wp('register_post_type', ['event', [
            'labels' => [
                'name' => __('Events', 'aqualuxe'),
                'singular_name' => __('Event', 'aqualuxe'),
            ],
            'public' => true,
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-calendar',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
            'has_archive' => true,
            'rewrite' => ['slug' => 'events'],
        ]]);

        // Service Category taxonomy
        Helpers::wp('register_taxonomy', ['service_cat', ['service'], [
            'labels' => [
                'name' => __('Service Categories', 'aqualuxe'),
                'singular_name' => __('Service Category', 'aqualuxe'),
            ],
            'public' => true,
            'hierarchical' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'service-category'],
        ]]);
    }

    public static function shortcode_services($atts = []): string
    {
        $atts = Helpers::wp('shortcode_atts', [['limit' => 6], $atts, 'aqualuxe_services']) ?? ['limit' => 6];
        $q = new \WP_Query(['post_type' => 'service', 'posts_per_page' => (int) ($atts['limit'] ?? 6)]);
        ob_start();
        echo '<div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">';
        if ($q->have_posts()) {
            while ($q->have_posts()) { $q->the_post();
                $permalink = Helpers::wp('get_permalink', [get_the_ID()]) ?? '';
                $title = Helpers::wp('get_the_title') ?? '';
                $excerpt = Helpers::wp('get_the_excerpt') ?? '';
                echo '<article class="rounded border p-4">';
                echo '<h3 class="text-lg font-semibold"><a href="' . esc_url((string) $permalink) . '">' . esc_html((string) $title) . '</a></h3>';
                echo '<div class="prose max-w-none">' . wp_kses_post((string) $excerpt) . '</div>';
                echo '</article>';
            }
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__('No services found.', 'aqualuxe') . '</p>';
        }
        echo '</div>';
        return (string) ob_get_clean();
    }
}
