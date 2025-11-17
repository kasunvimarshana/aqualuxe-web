<?php
// Auctions: Custom post type, bidding logic, WC integration
defined('ABSPATH') || exit;
add_action('init', function() {
    register_post_type('aqualuxe_auction', [
        'label' => __('Auctions', 'aqualuxe'),
        'public' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'custom-fields'],
        'show_in_rest' => true,
    ]);
});
// Add bidding logic, templates, etc.
