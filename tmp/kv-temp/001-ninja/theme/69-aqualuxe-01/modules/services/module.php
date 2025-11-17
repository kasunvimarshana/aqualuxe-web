<?php
// Services: Booking, scheduling, service listing
defined('ABSPATH') || exit;
add_action('init', function() {
    register_post_type('aqualuxe_service', [
        'label' => __('Services', 'aqualuxe'),
        'public' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'custom-fields'],
        'show_in_rest' => true,
    ]);
});
// Add booking/scheduling logic, templates, etc.
