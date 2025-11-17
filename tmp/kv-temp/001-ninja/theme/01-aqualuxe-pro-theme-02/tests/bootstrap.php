<?php
/**
 * PHPUnit bootstrap file.
 *
 * @package AquaLuxe
 */

// First, we need to load the composer autoloader.
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// Set the path to the WordPress tests checkout.
$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
    $_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
    echo "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL;
    exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the theme being tested.
 */
function _manually_load_theme() {
    define( 'AQUALUXE_THEME_DIR', dirname( __DIR__ ) );
    define( 'AQUALUXE_THEME_URI', '' );
    require_once dirname( __DIR__ ) . '/functions.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_theme' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
