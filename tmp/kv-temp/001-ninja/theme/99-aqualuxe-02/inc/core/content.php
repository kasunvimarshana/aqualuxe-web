<?php
namespace AquaLuxe\Core;

// Load stubs for static analysis when WordPress isn't bootstrapped.
if (! function_exists('add_action')) { require __DIR__ . '/../compat/wp_stubs.php'; }
/**
 * Registers custom post types and taxonomies.
 */
class Content
{
    public function register(): void
    {
        \register_post_type('service', [
            'labels' => [
                'name'          => \__('Services', 'aqualuxe'),
                'singular_name' => \__('Service', 'aqualuxe'),
            ],
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-admin-tools',
            'supports'     => ['title','editor','thumbnail','excerpt','revisions'],
            'show_in_rest' => true,
            'rewrite'      => ['slug' => 'services'],
        ]);

        \register_taxonomy('service_category', 'service', [
            'labels' => [
                'name'          => \__('Service Categories', 'aqualuxe'),
                'singular_name' => \__('Service Category', 'aqualuxe'),
            ],
            'public'       => true,
            'hierarchical' => true,
            'show_in_rest' => true,
            'rewrite'      => ['slug' => 'service-category'],
        ]);

        \register_post_type('event', [
            'labels' => [
                'name'          => \__('Events', 'aqualuxe'),
                'singular_name' => \__('Event', 'aqualuxe'),
            ],
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-calendar-alt',
            'supports'     => ['title','editor','thumbnail','excerpt','revisions'],
            'show_in_rest' => true,
            'rewrite'      => ['slug' => 'events'],
        ]);

        \register_post_type('testimonial', [
            'labels' => [
                'name'          => \__('Testimonials', 'aqualuxe'),
                'singular_name' => \__('Testimonial', 'aqualuxe'),
            ],
            'public'       => true,
            'has_archive'  => false,
            'menu_icon'    => 'dashicons-format-quote',
            'supports'     => ['title','editor','thumbnail','revisions'],
            'show_in_rest' => true,
            'rewrite'      => ['slug' => 'testimonials'],
        ]);
    }
}
