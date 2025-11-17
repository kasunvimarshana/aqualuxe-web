<?php
/**
 * Register Custom Post Types and Taxonomies via config arrays for modularity.
 */

namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class Content_Types_Service_Provider
{
    public function register(Container $c): void
    {
        add_action('init', [$this, 'register_content_types']);
    }

    public function boot(Container $c): void {}

    public function register_content_types(): void
    {
    $cpts = apply_filters('aqualuxe_cpts', [
            // Example: Classified listing
            'listing' => [
                'singular' => 'Listing',
                'plural'   => 'Listings',
                'args'     => [
                    'public' => true,
                    'has_archive' => true,
                    'show_in_rest' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
            'capability_type' => ['listing', 'listings'],
            'map_meta_cap' => true,
            'taxonomies' => ['vendor'],
                ],
            ],
        ]);

        foreach ($cpts as $type => $cfg) {
            $labels = [
                'name' => _x($cfg['plural'], 'post type general name', 'aqualuxe'),
                'singular_name' => _x($cfg['singular'], 'post type singular name', 'aqualuxe'),
            ];
            $args = array_merge([
                'labels' => $labels,
                'rewrite' => ['slug' => $type],
            ], $cfg['args'] ?? []);
            register_post_type($type, $args);
        }
    }
}
