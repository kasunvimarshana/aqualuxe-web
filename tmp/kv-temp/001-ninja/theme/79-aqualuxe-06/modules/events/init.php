<?php
declare(strict_types=1);

namespace Aqualuxe\Modules\Events;

if (! defined('ABSPATH')) { exit; }

add_action('init', static function (): void {
    $labels = [
        'name' => __('Events', 'aqualuxe'),
        'singular_name' => __('Event', 'aqualuxe'),
        'add_new' => __('Add New', 'aqualuxe'),
        'add_new_item' => __('Add New Event', 'aqualuxe'),
        'edit_item' => __('Edit Event', 'aqualuxe'),
        'new_item' => __('New Event', 'aqualuxe'),
        'view_item' => __('View Event', 'aqualuxe'),
        'search_items' => __('Search Events', 'aqualuxe'),
        'not_found' => __('No events found', 'aqualuxe'),
        'not_found_in_trash' => __('No events found in Trash', 'aqualuxe'),
        'all_items' => __('All Events', 'aqualuxe'),
        'menu_name' => __('Events', 'aqualuxe'),
    ];
    register_post_type('event', [
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'menu_position' => 23,
        'menu_icon' => 'dashicons-calendar-alt',
        'rewrite' => ['slug' => 'events'],
        'supports' => ['title','editor','thumbnail','excerpt','revisions'],
    ]);
});
