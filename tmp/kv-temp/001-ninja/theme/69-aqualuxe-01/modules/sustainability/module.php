<?php
// R&D/Sustainability: Content, reporting, showcase
defined('ABSPATH') || exit;
add_action('init', function() {
    register_post_type('aqualuxe_sustainability', [
        'label' => __('Sustainability', 'aqualuxe'),
        'public' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'custom-fields'],
        'show_in_rest' => true,
    ]);
});
// Add reporting, showcase logic, etc.
