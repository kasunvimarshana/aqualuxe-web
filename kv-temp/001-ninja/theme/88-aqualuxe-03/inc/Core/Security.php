<?php
namespace AquaLuxe\Core;

class Security {
    public function __construct() {
        add_action( 'init', [ $this, 'register_nonces' ] );
        add_filter( 'comment_text', 'wp_kses_post' );
    }

    public function register_nonces(): void {
        // Reserved for nonce action registrations.
    }
}
