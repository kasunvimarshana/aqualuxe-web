<?php
namespace AquaLuxe\Core;

class DemoImporter {
    public function __construct() {
        \add_action( 'wp_ajax_aqlx_import_demo', [ $this, 'import' ] );
        \add_action( 'wp_ajax_aqlx_flush_demo', [ $this, 'flush' ] );
    }

    public function import(): void {
        if ( \function_exists( 'check_ajax_referer' ) ) { \call_user_func( 'check_ajax_referer', 'wp_rest', 'nonce' ); }
        // Create core pages
        $home = Utils::ensure_page( __( 'Home', 'aqualuxe' ), 'home' );
        $about = Utils::ensure_page( __( 'About', 'aqualuxe' ), 'about' );
        $services = Utils::ensure_page( __( 'Services', 'aqualuxe' ), 'services' );
        $contact = Utils::ensure_page( __( 'Contact', 'aqualuxe' ), 'contact' );
        Utils::set_static_front_page( $home );

        // Menus
        Utils::ensure_menu( 'primary', 'Primary', [
            [ 'title' => __( 'Home', 'aqualuxe' ), 'url' => \home_url('/') ],
            [ 'title' => __( 'Shop', 'aqualuxe' ), 'url' => \home_url('/shop') ],
            [ 'title' => __( 'Services', 'aqualuxe' ), 'url' => \home_url('/services') ],
            [ 'title' => __( 'Blog', 'aqualuxe' ), 'url' => \home_url('/blog') ],
            [ 'title' => __( 'Contact', 'aqualuxe' ), 'url' => \home_url('/contact') ],
        ] );

        // CPT samples
        if ( function_exists('post_type_exists') && call_user_func('post_type_exists','aqlx_service') ) {
            call_user_func('wp_insert_post', [
                'post_type' => 'aqlx_service',
                'post_status' => 'publish',
                'post_title' => __( 'Aquarium Design', 'aqualuxe' ),
                'post_content' => __( 'Bespoke aquariums tailored to your space and vision.', 'aqualuxe' ),
                'meta_input' => [ 'aqlx_seed' => 1 ],
            ] );
        }

        // Optional WooCommerce seed
        if ( class_exists('WooCommerce') ) {
            $cat = Utils::ensure_product_cat( __( 'Rare Fish', 'aqualuxe' ), 'rare-fish' );
            Utils::create_simple_product( __( 'Blue Phantom Pleco', 'aqualuxe' ), 149.00, [ 'cats' => [ $cat ] ] );
        }

        if ( \function_exists( 'wp_send_json_success' ) ) { \call_user_func( 'wp_send_json_success', [ 'message' => 'Imported demo content', 'progress' => 100 ] ); }
    }

    public function flush(): void {
    if ( \function_exists( 'check_ajax_referer' ) ) { \call_user_func( 'check_ajax_referer', 'wp_rest', 'nonce' ); }
        Utils::remove_seed_content();
        if ( \function_exists( 'wp_send_json_success' ) ) { \call_user_func( 'wp_send_json_success', [ 'message' => 'Flushed demo content' ] ); }
    }
}
