<?php
namespace AquaLuxe\Modules\Events;

\add_action('init', function(){
    \register_post_type('event', [
        'label' => \__('Events', 'aqualuxe'),
        'public' => true,
        'menu_icon' => 'dashicons-calendar',
        'supports' => ['title','editor','thumbnail','excerpt'],
        'has_archive' => true,
        'show_in_rest' => true,
    ]);
});

// Simple ticket registration placeholder (AJAX)
\add_action('wp_ajax_aqualuxe_register_event', __NAMESPACE__.'\register_event');
\add_action('wp_ajax_nopriv_aqualuxe_register_event', __NAMESPACE__.'\register_event');
function register_event(){
    \check_ajax_referer('aqualuxe_event');
    $name = \sanitize_text_field($_POST['name'] ?? '');
    $email = \sanitize_email($_POST['email'] ?? '');
    if (!$name || !$email) \wp_send_json_error(['message' => \__('Invalid data', 'aqualuxe')]);
    \wp_send_json_success(['message' => \__('Registered', 'aqualuxe')]);
}
