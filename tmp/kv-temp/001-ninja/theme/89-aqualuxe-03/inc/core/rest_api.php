<?php
/** REST API */
namespace AquaLuxe\Core;
if ( ! defined( 'ABSPATH' ) ) { exit; }

\add_action( 'rest_api_init', function () {
	\register_rest_route( 'aqualuxe/v1', '/prefs', [
		'methods'  => 'POST',
		'permission_callback' => '__return_true',
		'args'     => [ 'dark' => [ 'type' => 'boolean', 'required' => true ] ],
		'callback' => function ( $request ) {
			$dark = (bool) $request->get_param( 'dark' );
			\setcookie( 'alx_dark', $dark ? '1' : '0', time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
			return \rest_ensure_response( [ 'ok' => true ] );
		},
	] );
} );
