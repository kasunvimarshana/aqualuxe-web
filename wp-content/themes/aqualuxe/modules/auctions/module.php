<?php
namespace AquaLuxe\Modules\Auctions;

add_action('init', function(){
    register_post_type('auction', [
        'label' => __('Auctions', 'aqualuxe'),
        'public' => true,
        'menu_icon' => 'dashicons-hammer',
        'supports' => ['title','editor','thumbnail','comments'],
        'has_archive' => true,
        'show_in_rest' => true,
    ]);
});
