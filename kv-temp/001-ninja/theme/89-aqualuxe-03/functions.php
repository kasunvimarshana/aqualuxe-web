<?php
/**
 * Theme bootstrap for AquaLuxe
 *
 * @package AquaLuxe
 */

define( 'AQUALUXE_VERSION', '1.0.0' );

define( 'AQUALUXE_DIR', get_template_directory() );
define( 'AQUALUXE_URI', get_template_directory_uri() );

define( 'AQUALUXE_ASSETS_DIR', AQUALUXE_DIR . '/assets' );
define( 'AQUALUXE_ASSETS_URI', AQUALUXE_URI . '/assets' );

define( 'AQUALUXE_INC_DIR', AQUALUXE_DIR . '/inc' );

define( 'AQUALUXE_MODULES_DIR', AQUALUXE_DIR . '/modules' );

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Autoloader (PSR-4-like light autoload for theme namespaces).
spl_autoload_register( function ( $class ) {
	if ( 0 !== strpos( $class, 'AquaLuxe\\' ) ) {
		return;
	}
	$path = AQUALUXE_DIR . '/inc/' . strtolower( str_replace( [ 'AquaLuxe\\', '\\' ], [ '', '/' ], $class ) ) . '.php';
	if ( file_exists( $path ) ) {
		require_once $path;
	}
} );

// Load core.
require_once AQUALUXE_INC_DIR . '/core/helpers.php';
require_once AQUALUXE_INC_DIR . '/core/security.php';
require_once AQUALUXE_INC_DIR . '/core/hooks.php';
require_once AQUALUXE_INC_DIR . '/core/setup.php';
require_once AQUALUXE_INC_DIR . '/core/assets.php';
require_once AQUALUXE_INC_DIR . '/core/seo.php';
require_once AQUALUXE_INC_DIR . '/core/admin.php';
require_once AQUALUXE_INC_DIR . '/core/forms.php';
require_once AQUALUXE_INC_DIR . '/core/customizer.php';
require_once AQUALUXE_INC_DIR . '/core/cpts.php';
require_once AQUALUXE_INC_DIR . '/core/taxonomies.php';
require_once AQUALUXE_INC_DIR . '/core/shortcodes.php';
require_once AQUALUXE_INC_DIR . '/core/rest_api.php';
require_once AQUALUXE_INC_DIR . '/core/compat_woocommerce.php';

// Modules loader (toggleable via filter aqualuxe_enabled_modules).
add_action( 'after_setup_theme', function () {
	$modules_dir = AQUALUXE_MODULES_DIR;
	$default_modules = [
		'dark_mode',
		'multilingual',
		'importer',
		'wishlist',
		'quick_view',
		'advanced_filters',
		'multicurrency',
		'roles',
		'marketplace',
		'classifieds',
	];
	$opt = (array) get_option( 'aqualuxe_enabled_modules_option', [] );
	$modules = apply_filters( 'aqualuxe_enabled_modules', ! empty( $opt ) ? $opt : $default_modules );
	foreach ( $modules as $module ) {
		$bootstrap = trailingslashit( $modules_dir ) . $module . '/bootstrap.php';
		if ( file_exists( $bootstrap ) ) {
			require_once $bootstrap;
		}
	}
} );

// Admin-only quality gates: notify if assets not built.
if ( \function_exists('is_admin') ? (bool) \call_user_func('is_admin') : false ) {
	add_action( 'admin_notices', function () {
		$manifest = AQUALUXE_DIR . '/assets/dist/mix-manifest.json';
		if ( ! file_exists( $manifest ) ) {
			echo '<div class="notice notice-warning"><p>' . esc_html__( 'AquaLuxe assets not built. Run npm install && npm run build.', 'aqualuxe' ) . '</p></div>';
		}
	} );
}
