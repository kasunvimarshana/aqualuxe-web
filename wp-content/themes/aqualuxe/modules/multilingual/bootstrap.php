<?php
/** Multilingual readiness */
namespace AquaLuxe\Modules\Multilingual;
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Integrate with Polylang if available; graceful fallback otherwise.
\add_action( 'init', function () {
	if ( function_exists( 'pll_register_string' ) ) {
		\pll_register_string( 'tagline', 'Bringing elegance to aquatic life – globally.', 'AquaLuxe' );
	}
} );
