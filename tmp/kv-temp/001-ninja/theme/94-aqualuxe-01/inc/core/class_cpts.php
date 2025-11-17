<?php
namespace AquaLuxe\Core;

if (!defined('ABSPATH')) { exit; }

class CPTs {
    public static function init(): void {
        \add_action('init', [__CLASS__, 'register']);
    }

    public static function register(): void {
        // Services
        \register_post_type('service', [
            'label' => __('Services', AQUALUXE_TEXT_DOMAIN),
            'public' => true,
            'menu_icon' => 'dashicons-hammer',
            'supports' => ['title','editor','thumbnail','excerpt','custom-fields'],
            'has_archive' => true,
            'rewrite' => ['slug' => 'services'],
            'show_in_rest' => true,
        ]);
        \register_taxonomy('service_type', 'service', [
            'label' => __('Service Types', AQUALUXE_TEXT_DOMAIN),
            'public' => true,
            'hierarchical' => true,
            'show_in_rest' => true,
        ]);

        // Events
        \register_post_type('event', [
            'label' => __('Events', AQUALUXE_TEXT_DOMAIN),
            'public' => true,
            'menu_icon' => 'dashicons-calendar-alt',
            'supports' => ['title','editor','thumbnail','excerpt','custom-fields'],
            'has_archive' => true,
            'rewrite' => ['slug' => 'events'],
            'show_in_rest' => true,
        ]);
        \register_taxonomy('event_type', 'event', [
            'label' => __('Event Types', AQUALUXE_TEXT_DOMAIN),
            'public' => true,
            'hierarchical' => true,
            'show_in_rest' => true,
        ]);

        // Testimonials
        \register_post_type('testimonial', [
            'label' => __('Testimonials', AQUALUXE_TEXT_DOMAIN),
            'public' => true,
            'menu_icon' => 'dashicons-format-quote',
            'supports' => ['title','editor','thumbnail','excerpt'],
            'has_archive' => true,
            'rewrite' => ['slug' => 'testimonials'],
            'show_in_rest' => true,
        ]);
    }
}
