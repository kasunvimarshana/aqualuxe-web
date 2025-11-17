<?php
/** Dark mode module */
namespace AquaLuxe\Modules\Dark_Mode;
if ( ! defined( 'ABSPATH' ) ) { exit; }

\add_action( 'wp_head', function () {
	$dark = isset( $_COOKIE['alx_dark'] ) && '1' === $_COOKIE['alx_dark'];
	echo '<script id="alx-dark-ssr">(function(){var d=' . ( $dark ? 'true' : 'false' ) . ';if(d){document.documentElement.classList.add("theme-dark");}})();</script>';
}, 1 );
