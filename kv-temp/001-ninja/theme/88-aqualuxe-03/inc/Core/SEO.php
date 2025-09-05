<?php
namespace AquaLuxe\Core;

class Seo {
    public function __construct() {
        \add_action( 'wp_head', [ $this, 'meta' ], 5 );
    }

    public function meta(): void {
    $title = function_exists( 'wp_get_document_title' ) ? call_user_func( 'wp_get_document_title' ) : ( function_exists( 'get_bloginfo' ) ? call_user_func( 'get_bloginfo', 'name' ) : 'AquaLuxe' );
    $desc  = function_exists( 'get_bloginfo' ) ? call_user_func( 'get_bloginfo', 'description' ) : '';
        $url   = function_exists( 'home_url' ) ? call_user_func( 'home_url' ) : '';
    $site  = function_exists( 'get_bloginfo' ) ? call_user_func( 'get_bloginfo', 'name' ) : 'AquaLuxe';
    echo '\n<meta property="og:title" content="' . esc_attr( $title ) . '" />';
    echo '\n<meta property="og:site_name" content="' . esc_attr( $site ) . '" />';
        echo '\n<meta property="og:url" content="' . esc_url( $url ) . '" />';
        echo '\n<meta name="description" content="' . esc_attr( $desc ) . '" />';
        echo '\n<script type="application/ld+json">' . wp_json_encode( [
            '@context' => 'https://schema.org',
            '@type'    => 'Organization',
            'name'     => $site,
            'url'      => $url,
            'description' => $desc,
        ] ) . '</script>';
    }
}
