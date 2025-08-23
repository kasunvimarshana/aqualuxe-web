<?php
// Franchise/Licensing: Inquiry forms, partner portal
defined('ABSPATH') || exit;
add_action('init', function() {
    register_post_type('aqualuxe_franchise', [
        'label' => __('Franchise Inquiries', 'aqualuxe'),
        'public' => false,
        'supports' => ['title', 'editor', 'custom-fields'],
        'show_in_menu' => true,
        'show_in_rest' => true,
    ]);
});
// Add partner portal logic, templates, etc.
