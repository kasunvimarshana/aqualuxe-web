<?php
defined('ABSPATH') || exit;

add_action('init', function () {
    register_post_type('event', [
        'label' => __('Events', 'aqualuxe'),
        'public' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'events'],
        'show_in_rest' => true,
    ]);

    register_post_type('ticket', [
        'label' => __('Tickets', 'aqualuxe'),
        'public' => false,
        'show_ui' => true,
        'supports' => ['title', 'custom-fields'],
        'show_in_rest' => true,
    ]);
});
