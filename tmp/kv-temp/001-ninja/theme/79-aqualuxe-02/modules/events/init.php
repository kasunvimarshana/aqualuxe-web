<?php
declare(strict_types=1);

// Register Events CPT
add_action('init', static function (): void {
    register_post_type('event', [
        'label' => __('Events', 'aqualuxe'),
        'public' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => ['title','editor','thumbnail','excerpt'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'events'],
        'show_in_rest' => true,
    ]);
});
