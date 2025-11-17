<?php
declare(strict_types=1);

// Register Services CPT
add_action('init', static function (): void {
    register_post_type('service', [
        'label' => __('Services', 'aqualuxe'),
        'public' => true,
        'menu_icon' => 'dashicons-hammer',
        'supports' => ['title','editor','thumbnail','excerpt'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'services'],
        'show_in_rest' => true,
    ]);
});
