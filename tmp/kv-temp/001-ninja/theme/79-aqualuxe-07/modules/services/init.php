<?php
declare(strict_types=1);

namespace Aqualuxe\Modules\Services;

if (! defined('ABSPATH')) { exit; }

add_action('init', static function (): void {
    $labels = [
        'name' => __('Services', 'aqualuxe'),
        'singular_name' => __('Service', 'aqualuxe'),
        'add_new' => __('Add New', 'aqualuxe'),
        'add_new_item' => __('Add New Service', 'aqualuxe'),
        'edit_item' => __('Edit Service', 'aqualuxe'),
        'new_item' => __('New Service', 'aqualuxe'),
        'view_item' => __('View Service', 'aqualuxe'),
        'search_items' => __('Search Services', 'aqualuxe'),
        'not_found' => __('No services found', 'aqualuxe'),
        'not_found_in_trash' => __('No services found in Trash', 'aqualuxe'),
        'all_items' => __('All Services', 'aqualuxe'),
        'menu_name' => __('Services', 'aqualuxe'),
    ];
    register_post_type('service', [
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'menu_position' => 22,
        'menu_icon' => 'dashicons-admin-tools',
        'rewrite' => ['slug' => 'services'],
        'supports' => ['title','editor','thumbnail','excerpt','revisions'],
    ]);
});

