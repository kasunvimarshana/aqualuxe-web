<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define theme constants.
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_DIR', trailingslashit( get_template_directory() ) );
define( 'AQUALUXE_URI', trailingslashit( get_template_directory_uri() ) );
define( 'AQUALUXE_ASSETS_URI', AQUALUXE_URI . 'assets/dist/' );
define( 'AQUALUXE_INC_DIR', AQUALUXE_DIR . 'inc/' );
define( 'AQUALUXE_TEMPLATES_DIR', AQUALUXE_DIR . 'templates/' );

/**
 * AquaLuxe only works in WordPress 5.9 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '5.9', '<' ) ) {
	require AQUALUXE_INC_DIR . 'core/back-compat.php';
	return;
}

/**
 * Include required files.
 */

// Core functionality.
require_once AQUALUXE_INC_DIR . 'core/template-functions.php';
require_once AQUALUXE_INC_DIR . 'core/template-tags.php';

// Setup.
require_once AQUALUXE_INC_DIR . 'setup/theme-setup.php';
require_once AQUALUXE_INC_DIR . 'setup/menus.php';
require_once AQUALUXE_INC_DIR . 'setup/sidebars.php';
require_once AQUALUXE_INC_DIR . 'setup/image-sizes.php';

// Assets.
require_once AQUALUXE_INC_DIR . 'assets/enqueue.php';
require_once AQUALUXE_INC_DIR . 'assets/icons.php';
require_once AQUALUXE_INC_DIR . 'assets/enqueue-accessibility.php';

// Security.
require_once AQUALUXE_INC_DIR . 'security/hardening.php';

// SEO.
require_once AQUALUXE_INC_DIR . 'seo/schema.php';
require_once AQUALUXE_INC_DIR . 'seo/meta-tags.php';

// Accessibility.
require_once AQUALUXE_INC_DIR . 'accessibility/accessibility-functions.php';
require_once AQUALUXE_INC_DIR . 'accessibility/skip-links.php';
require_once AQUALUXE_INC_DIR . 'accessibility/accessibility.php';
require_once AQUALUXE_INC_DIR . 'accessibility.php';

// Customizer.
require_once AQUALUXE_INC_DIR . 'customizer/register.php';
require_once AQUALUXE_INC_DIR . 'customizer/output.php';

// Hooks.
require_once AQUALUXE_INC_DIR . 'hooks/hooks.php';
require_once AQUALUXE_INC_DIR . 'hooks/filters.php';

// Utils.
require_once AQUALUXE_INC_DIR . 'utils/template-tags.php';
require_once AQUALUXE_INC_DIR . 'utils/helpers.php';

// Performance.
require_once AQUALUXE_INC_DIR . 'performance/asset-optimization.php';
require_once AQUALUXE_INC_DIR . 'performance/lazy-loading.php';
require_once AQUALUXE_INC_DIR . 'performance/image-optimization.php';
require_once AQUALUXE_INC_DIR . 'performance/responsive-images.php';
require_once AQUALUXE_INC_DIR . 'performance/caching.php';

/**
 * WooCommerce integration.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require_once AQUALUXE_INC_DIR . 'integrations/woocommerce/setup.php';
	require_once AQUALUXE_INC_DIR . 'integrations/woocommerce/hooks.php';
	require_once AQUALUXE_INC_DIR . 'integrations/woocommerce/template-functions.php';
	require_once AQUALUXE_INC_DIR . 'integrations/woocommerce/template-hooks.php';
} else {
	// Load fallbacks when WooCommerce is not active.
	require_once AQUALUXE_INC_DIR . 'integrations/woocommerce/fallbacks.php';
}

/**
 * Load theme updater if available.
 */
if ( file_exists( AQUALUXE_INC_DIR . 'core/updater.php' ) ) {
	require_once AQUALUXE_INC_DIR . 'core/updater.php';
}

/**
 * Load demo content importer if available.
 */
if ( is_admin() && file_exists( AQUALUXE_INC_DIR . 'core/demo-importer.php' ) ) {
	require_once AQUALUXE_INC_DIR . 'core/demo-importer.php';
}

/**
 * Add body classes for accessibility features.
 *
 * @param array $classes Array of body classes.
 * @return array Modified array of body classes.
 */
function aqualuxe_accessibility_body_classes( $classes ) {
	// Add class for keyboard users.
	$classes[] = 'no-js';
	
	// Add class for reduced motion if enabled.
	if ( get_theme_mod( 'aqualuxe_reduced_motion', false ) ) {
		$classes[] = 'reduced-motion';
	}
	
	// Add class for high contrast if enabled.
	if ( get_theme_mod( 'aqualuxe_high_contrast', false ) ) {
		$classes[] = 'high-contrast';
	}
	
	return $classes;
}
add_filter( 'body_class', 'aqualuxe_accessibility_body_classes' );

/**
 * Add script to detect JavaScript and keyboard users.
 */
function aqualuxe_accessibility_head_scripts() {
	?>
	<script>
		// Remove no-js class
		document.documentElement.className = document.documentElement.className.replace('no-js', 'js');
		
		// Detect keyboard users
		function handleFirstTab(e) {
			if (e.keyCode === 9) {
				document.body.classList.add('keyboard-user');
				window.removeEventListener('keydown', handleFirstTab);
			}
		}
		
		window.addEventListener('keydown', handleFirstTab);
		
		// Detect mouse users
		document.body.addEventListener('mousedown', function() {
			document.body.classList.remove('keyboard-user');
		});
	</script>
	<?php
}
add_action( 'wp_head', 'aqualuxe_accessibility_head_scripts', 0 );