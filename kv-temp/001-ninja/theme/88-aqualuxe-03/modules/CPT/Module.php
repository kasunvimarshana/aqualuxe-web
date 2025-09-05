<?php
namespace AquaLuxe\Modules\CPT;

use AquaLuxe\Core\Contracts\Module as ModuleContract;

class Module implements ModuleContract {
    public function boot(): void {
        \add_action( 'init', [ $this, 'register_cpts' ] );
        \add_action( 'init', [ $this, 'register_taxes' ] );
    }

    public function register_cpts(): void {
        // Services CPT
        \call_user_func( 'register_post_type', 'aqlx_service', [
            'label' => __( 'Services', 'aqualuxe' ),
            'public' => true,
            'menu_icon' => 'dashicons-admin-tools',
            'supports' => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
            'show_in_rest' => true,
            'has_archive' => true,
            'rewrite' => [ 'slug' => 'services' ],
        ] );

        // Events CPT
        \call_user_func( 'register_post_type', 'aqlx_event', [
            'label' => __( 'Events', 'aqualuxe' ),
            'public' => true,
            'menu_icon' => 'dashicons-calendar',
            'supports' => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
            'show_in_rest' => true,
            'has_archive' => true,
            'rewrite' => [ 'slug' => 'events' ],
        ] );
    }

    public function register_taxes(): void {
        \call_user_func( 'register_taxonomy', 'aqlx_service_type', [ 'aqlx_service' ], [
            'label' => __( 'Service Types', 'aqualuxe' ),
            'public' => true,
            'hierarchical' => true,
            'show_in_rest' => true,
        ] );
        \call_user_func( 'register_taxonomy', 'aqlx_event_type', [ 'aqlx_event' ], [
            'label' => __( 'Event Types', 'aqualuxe' ),
            'public' => true,
            'hierarchical' => true,
            'show_in_rest' => true,
        ] );
    }
}
