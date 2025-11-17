<?php
// Trade-ins/Auctions: Submission forms, moderation, auction logic
defined('ABSPATH') || exit;
add_action('init', function() {
    register_post_type('aqualuxe_tradein', [
        'label' => __('Trade-ins', 'aqualuxe'),
        'public' => true,
        'supports' => ['title', 'editor', 'custom-fields'],
        'show_in_rest' => true,
    ]);
});
// Add moderation, auction logic, etc.
