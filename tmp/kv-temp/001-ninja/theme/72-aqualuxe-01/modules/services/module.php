<?php
namespace AquaLuxe\Modules\Services;

class Module {
    public static function init(): void {
        add_action('init', [__CLASS__, 'register_cpt']);
    }
    public static function register_cpt(): void {
        register_post_type('service', [
            'label' => __('Services','aqualuxe'),
            'public'=> true,
            'menu_icon' => 'dashicons-hammer',
            'supports' => ['title','editor','thumbnail','excerpt'],
            'has_archive' => true,
            'show_in_rest' => true,
        ]);
    }
}
