<?php
declare(strict_types=1);

// Placeholder for auctions/trade-ins integration. Provides a CPT for auction listings.
add_action('init', static function (): void {
    register_post_type('auction', [
        'label' => __('Auctions', 'aqualuxe'),
        'public' => true,
        'menu_icon' => 'dashicons-hammer',
        'supports' => ['title','editor','thumbnail','excerpt','comments'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'auctions'],
        'show_in_rest' => true,
    ]);
});
