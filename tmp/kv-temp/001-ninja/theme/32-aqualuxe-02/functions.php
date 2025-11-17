<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 * @since 1.4.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define theme constants.
define( 'AQUALUXE_VERSION', '1.4.0' );
define( 'AQUALUXE_DIR', get_template_directory() );
define( 'AQUALUXE_URI', get_template_directory_uri() );

// Include early loading files
require_once AQUALUXE_DIR . '/inc/core/dark-mode-early.php';

/**
 * Initialize the theme.
 *
 * Load the autoloader and initialize the theme.
 */
function aqualuxe_initialize() {
	// Load the autoloader.
	require_once AQUALUXE_DIR . '/inc/core/class-aqualuxe-autoloader.php';
	
	// Load the theme class.
	require_once AQUALUXE_DIR . '/inc/core/class-aqualuxe-theme.php';
	
	// Initialize the theme.
	$theme = \AquaLuxe\Core\Theme::get_instance();
	$theme->initialize();
}
add_action( 'after_setup_theme', 'aqualuxe_initialize', 0 );

/**
 * Compatibility functions.
 *
 * Functions that ensure backward compatibility with older versions of WordPress.
 */
require_once AQUALUXE_DIR . '/inc/core/compatibility.php';

/**
 * Include the Walker Nav Menu class.
 */
require_once AQUALUXE_DIR . '/inc/core/walker-nav-menu.php';

/**
 * Include helper functions.
 */
require_once AQUALUXE_DIR . '/inc/helpers/sanitize.php';
require_once AQUALUXE_DIR . '/inc/helpers/helpers.php';
require_once AQUALUXE_DIR . '/inc/helpers/markup.php';
require_once AQUALUXE_DIR . '/inc/helpers/asset-loader.php';

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function aqualuxe_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Add a class if dark mode is enabled by default.
	if ( get_theme_mod( 'aqualuxe_dark_mode_default', false ) ) {
		$classes[] = 'dark-mode';
	}

	return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function aqualuxe_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'aqualuxe_pingback_header' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function aqualuxe_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'aqualuxe_content_width', 1200 );
}
add_action( 'after_setup_theme', 'aqualuxe_content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
	wp_enqueue_style( 'aqualuxe-style', get_stylesheet_uri(), array(), AQUALUXE_VERSION );
	wp_style_add_data( 'aqualuxe-style', 'rtl', 'replace' );

	wp_enqueue_script( 'aqualuxe-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), AQUALUXE_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_scripts' );

/**
 * Add the theme page.
 */
function aqualuxe_add_theme_page() {
	add_theme_page(
		esc_html__( 'AquaLuxe Theme', 'aqualuxe' ),
		esc_html__( 'AquaLuxe Theme', 'aqualuxe' ),
		'manage_options',
		'aqualuxe-theme',
		'aqualuxe_theme_page'
	);
}
add_action( 'admin_menu', 'aqualuxe_add_theme_page' );

/**
 * Display the theme page.
 */
function aqualuxe_theme_page() {
	$theme = wp_get_theme();
	?>
	<div class="wrap">
		<h1><?php echo esc_html( $theme->display( 'Name' ) ); ?></h1>
		<div class="theme-description"><?php echo esc_html( $theme->display( 'Description' ) ); ?></div>
		<hr>
		<div class="theme-details">
			<p><?php echo esc_html( sprintf( __( 'Version: %s', 'aqualuxe' ), $theme->display( 'Version' ) ) ); ?></p>
			<p><?php echo esc_html( sprintf( __( 'Author: %s', 'aqualuxe' ), $theme->display( 'Author' ) ) ); ?></p>
			<p><?php echo esc_html( sprintf( __( 'Theme URI: %s', 'aqualuxe' ), $theme->display( 'ThemeURI' ) ) ); ?></p>
		</div>
		<hr>
		<div class="theme-documentation">
			<h2><?php esc_html_e( 'Documentation', 'aqualuxe' ); ?></h2>
			<p><?php esc_html_e( 'For theme documentation, please refer to the documentation folder included with the theme.', 'aqualuxe' ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}