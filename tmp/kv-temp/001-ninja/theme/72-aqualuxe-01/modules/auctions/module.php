<?php
namespace AquaLuxe\Modules\Auctions;

class Module {
    public static function init(): void {
        add_action('init', [__CLASS__, 'register_cpt']);
    }
    public static function register_cpt(): void {
        register_post_type('auction', [
            'label' => __('Auctions','aqualuxe'),
            'public'=> true,
            'menu_icon' => 'dashicons-hammer',
            'supports' => ['title','editor','thumbnail','excerpt','comments'],
            'has_archive' => true,
            'show_in_rest' => true,
        ]);
    }
}
