<?php
// Events: Events calendar, ticketing, templates
defined('ABSPATH') || exit;
add_action('init', function() {
    register_post_type('aqualuxe_event', [
        'label' => __('Events', 'aqualuxe'),
        'public' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'custom-fields'],
        'show_in_rest' => true,
    ]);
});
// Add ticketing logic, templates, etc.
