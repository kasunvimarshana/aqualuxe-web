<?php
/** Custom Post Types and Taxonomies */
if (!defined('ABSPATH')) { exit; }

add_action('init', function(){
    // Services
    register_post_type('service', [
        'label' => __('Services', 'aqualuxe'),
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-hammer',
        'supports' => ['title','editor','thumbnail','excerpt','revisions','custom-fields'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'services'],
    ]);

    // Events
    register_post_type('event', [
        'label' => __('Events', 'aqualuxe'),
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => ['title','editor','thumbnail','excerpt','revisions','custom-fields'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'events'],
    ]);

    // Taxonomies
    register_taxonomy('service_type', 'service', [
        'label' => __('Service Types', 'aqualuxe'),
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'service-type'],
    ]);

    register_taxonomy('event_type', 'event', [
        'label' => __('Event Types', 'aqualuxe'),
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'event-type'],
    ]);
});

// Meta fields (simple example)
add_action('init', function(){
    register_post_meta('event', 'event_date', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback' => function(){ return current_user_can('edit_posts'); },
    ]);
});
