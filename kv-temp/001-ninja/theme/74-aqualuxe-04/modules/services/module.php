<?php
namespace AquaLuxe\Modules\Services;

\add_action('init', function(){
    \register_post_type('service', [
        'label' => \__('Services', 'aqualuxe'),
        'public' => true,
        'menu_icon' => 'dashicons-hammer',
        'supports' => ['title','editor','thumbnail','excerpt'],
        'has_archive' => true,
        'show_in_rest' => true,
    ]);
});
