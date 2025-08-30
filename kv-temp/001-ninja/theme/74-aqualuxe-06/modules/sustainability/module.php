<?php
namespace AquaLuxe\Modules\Sustainability;

\add_action('init', function(){
    \register_post_type('initiative', [
        'label' => \__('Initiatives', 'aqualuxe'),
        'public' => true,
        'menu_icon' => 'dashicons-leaf',
        'supports' => ['title','editor','thumbnail','excerpt'],
        'has_archive' => true,
        'show_in_rest' => true,
    ]);
});
