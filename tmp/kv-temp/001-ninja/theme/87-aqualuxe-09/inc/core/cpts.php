<?php
namespace AquaLuxe\Core;

class CPTs
{
    public static function register(): void
    {
        // Services
        \register_post_type('service', [
            'labels' => [
                'name' => \__('Services', 'aqualuxe'),
                'singular_name' => \__('Service', 'aqualuxe'),
            ],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-hammer',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
            'show_in_rest' => true,
        ]);

        \register_taxonomy('service_category', ['service'], [
            'labels' => [
                'name' => \__('Service Categories', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'show_in_rest' => true,
        ]);

        // Events
        \register_post_type('event', [
            'labels' => [
                'name' => \__('Events', 'aqualuxe'),
                'singular_name' => \__('Event', 'aqualuxe'),
            ],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-calendar-alt',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
            'show_in_rest' => true,
        ]);
        \register_taxonomy('event_type', ['event'], [
            'labels' => [
                'name' => \__('Event Types', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'show_in_rest' => true,
        ]);

        // Testimonials
        \register_post_type('testimonial', [
            'labels' => [
                'name' => \__('Testimonials', 'aqualuxe'),
                'singular_name' => \__('Testimonial', 'aqualuxe'),
            ],
            'public' => true,
            'has_archive' => false,
            'menu_icon' => 'dashicons-format-quote',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
            'show_in_rest' => true,
        ]);
    }
}
