<?php
/**
 * AquaLuxe Theme Scripts
 *
 * Handles the enqueuing of theme scripts and styles.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
	// Get theme version.
	$theme_version = wp_get_theme()->get( 'Version' );
	$dev_mode = defined( 'WP_DEBUG' ) && WP_DEBUG;
	
	// Register and enqueue styles.
	wp_enqueue_style( 'aqualuxe-style', get_stylesheet_uri(), array(), $theme_version );
	wp_enqueue_style( 'aqualuxe-main', get_template_directory_uri() . '/assets/css/style.css', array(), $theme_version );
	
	// Add Google Fonts.
	wp_enqueue_style( 'aqualuxe-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Merriweather:wght@400;700&family=Montserrat:wght@400;500;600;700&display=swap', array(), null );
	
	// Register and enqueue scripts.
	wp_enqueue_script( 'aqualuxe-navigation', get_template_directory_uri() . '/assets/js/dist/navigation.min.js', array(), $theme_version, true );
	wp_enqueue_script( 'aqualuxe-lazy-loading', get_template_directory_uri() . '/assets/js/dist/lazy-loading.min.js', array(), $theme_version, true );
	wp_enqueue_script( 'aqualuxe-dark-mode', get_template_directory_uri() . '/assets/js/dist/dark-mode.min.js', array(), $theme_version, true );
	wp_enqueue_script( 'aqualuxe-main', get_template_directory_uri() . '/assets/js/dist/main.min.js', array( 'jquery' ), $theme_version, true );
	
	// Conditionally load scripts.
	if ( is_front_page() || is_home() ) {
		wp_enqueue_script( 'aqualuxe-homepage', get_template_directory_uri() . '/assets/js/dist/homepage.min.js', array( 'jquery' ), $theme_version, true );
	}
	
	// WooCommerce specific scripts.
	if ( class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
		wp_enqueue_script( 'aqualuxe-woocommerce', get_template_directory_uri() . '/assets/js/dist/woocommerce.min.js', array( 'jquery' ), $theme_version, true );
	}
	
	// Add comment-reply script if needed.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	// Localize script with theme data.
	wp_localize_script( 'aqualuxe-main', 'aqualuxeData', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'themeUrl' => get_template_directory_uri(),
		'homeUrl' => home_url(),
		'isLoggedIn' => is_user_logged_in(),
		'isMobile' => wp_is_mobile(),
		'isWooCommerce' => class_exists( 'WooCommerce' ),
		'nonce' => wp_create_nonce( 'aqualuxe-nonce' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_scripts' );

/**
 * Enqueue admin scripts and styles.
 */
function aqualuxe_admin_scripts() {
	$theme_version = wp_get_theme()->get( 'Version' );
	
	// Admin styles.
	wp_enqueue_style( 'aqualuxe-admin-style', get_template_directory_uri() . '/assets/css/admin.css', array(), $theme_version );
	
	// Admin scripts.
	wp_enqueue_script( 'aqualuxe-admin-script', get_template_directory_uri() . '/assets/js/admin.js', array( 'jquery' ), $theme_version, true );
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_admin_scripts' );

/**
 * Add preload for critical assets.
 */
function aqualuxe_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		// Add Google Fonts domain.
		$urls[] = array(
			'href' => 'https://fonts.googleapis.com',
		);
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}
	
	return $urls;
}
add_filter( 'wp_resource_hints', 'aqualuxe_resource_hints', 10, 2 );

/**
 * Add async/defer attributes to scripts.
 */
function aqualuxe_script_loader_tag( $tag, $handle, $src ) {
	// Add async attribute to non-critical scripts.
	$async_scripts = array( 'aqualuxe-lazy-loading' );
	
	if ( in_array( $handle, $async_scripts, true ) ) {
		return str_replace( ' src', ' async src', $tag );
	}
	
	// Add defer attribute to specific scripts.
	$defer_scripts = array( 'aqualuxe-dark-mode' );
	
	if ( in_array( $handle, $defer_scripts, true ) ) {
		return str_replace( ' src', ' defer src', $tag );
	}
	
	return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_script_loader_tag', 10, 3 );

/**
 * Add preload for critical CSS.
 */
function aqualuxe_preload_styles() {
	?>
	<link rel="preload" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/style.css' ); ?>" as="style">
	<link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" as="style">
	<?php
}
add_action( 'wp_head', 'aqualuxe_preload_styles', 1 );

/**
 * Add theme color meta tag for mobile browsers.
 */
function aqualuxe_theme_color() {
	// Get theme color from customizer.
	$theme_color = get_theme_mod( 'aqualuxe_primary_color', '#0077B6' );
	
	echo '<meta name="theme-color" content="' . esc_attr( $theme_color ) . '">';
}
add_action( 'wp_head', 'aqualuxe_theme_color', 1 );