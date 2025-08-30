<?php
/**
 * Enqueue scripts and styles
 *
 * @package AquaLuxe
 */

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
	// Enqueue styles.
	wp_enqueue_style( 'aqualuxe-style', aqualuxe_asset( 'css/app.css' ), array(), AQUALUXE_VERSION );
	
	// Add dark mode stylesheet.
	wp_enqueue_style( 'aqualuxe-dark-mode', aqualuxe_asset( 'css/dark-mode.css' ), array( 'aqualuxe-style' ), AQUALUXE_VERSION );
	
	// Add WooCommerce stylesheet if WooCommerce is active.
	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 'aqualuxe-woocommerce', aqualuxe_asset( 'css/woocommerce.css' ), array( 'aqualuxe-style' ), AQUALUXE_VERSION );
	}
	
	// Enqueue scripts.
	wp_enqueue_script( 'aqualuxe-script', aqualuxe_asset( 'js/app.js' ), array(), AQUALUXE_VERSION, true );
	
	// Add comment reply script if needed.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	// Add inline script with site data.
	wp_localize_script( 'aqualuxe-script', 'aqualuxeData', array(
		'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
		'homeUrl'    => home_url(),
		'themeUrl'   => get_template_directory_uri(),
		'nonce'      => wp_create_nonce( 'aqualuxe-nonce' ),
		'isRtl'      => is_rtl(),
		'language'   => get_locale(),
		'currency'   => get_woocommerce_currency(),
		'currencySymbol' => get_woocommerce_currency_symbol(),
		'strings'    => array(
			'addToCart'     => esc_html__( 'Add to cart', 'aqualuxe' ),
			'addingToCart'  => esc_html__( 'Adding...', 'aqualuxe' ),
			'addedToCart'   => esc_html__( 'Added to cart', 'aqualuxe' ),
			'viewCart'      => esc_html__( 'View cart', 'aqualuxe' ),
			'errorMessage'  => esc_html__( 'Something went wrong. Please try again.', 'aqualuxe' ),
			'searchResults' => esc_html__( 'Search results for:', 'aqualuxe' ),
			'noResults'     => esc_html__( 'No results found', 'aqualuxe' ),
			'loadMore'      => esc_html__( 'Load more', 'aqualuxe' ),
			'loading'       => esc_html__( 'Loading...', 'aqualuxe' ),
			'close'         => esc_html__( 'Close', 'aqualuxe' ),
		),
	) );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_scripts' );

/**
 * Enqueue admin scripts and styles.
 */
function aqualuxe_admin_scripts() {
	// Enqueue admin styles.
	wp_enqueue_style( 'aqualuxe-admin-style', aqualuxe_asset( 'css/admin.css' ), array(), AQUALUXE_VERSION );
	
	// Enqueue admin scripts.
	wp_enqueue_script( 'aqualuxe-admin-script', aqualuxe_asset( 'js/admin.js' ), array( 'jquery' ), AQUALUXE_VERSION, true );
	
	// Add inline script with admin data.
	wp_localize_script( 'aqualuxe-admin-script', 'aqualuxeAdminData', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'aqualuxe-admin-nonce' ),
		'strings' => array(
			'confirmDelete' => esc_html__( 'Are you sure you want to delete this item?', 'aqualuxe' ),
			'confirmReset'  => esc_html__( 'Are you sure you want to reset to default settings?', 'aqualuxe' ),
			'success'       => esc_html__( 'Success!', 'aqualuxe' ),
			'error'         => esc_html__( 'Error!', 'aqualuxe' ),
		),
	) );
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_admin_scripts' );

/**
 * Enqueue customizer scripts and styles.
 */
function aqualuxe_customizer_scripts() {
	// Enqueue customizer styles.
	wp_enqueue_style( 'aqualuxe-customizer-style', aqualuxe_asset( 'css/customizer.css' ), array(), AQUALUXE_VERSION );
	
	// Enqueue customizer scripts.
	wp_enqueue_script( 'aqualuxe-customizer-script', aqualuxe_asset( 'js/customizer.js' ), array( 'jquery', 'customize-preview' ), AQUALUXE_VERSION, true );
	
	// Add inline script with customizer data.
	wp_localize_script( 'aqualuxe-customizer-script', 'aqualuxeCustomizerData', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'aqualuxe-customizer-nonce' ),
	) );
}
add_action( 'customize_preview_init', 'aqualuxe_customizer_scripts' );

/**
 * Enqueue login scripts and styles.
 */
function aqualuxe_login_scripts() {
	// Enqueue login styles.
	wp_enqueue_style( 'aqualuxe-login-style', aqualuxe_asset( 'css/login.css' ), array(), AQUALUXE_VERSION );
	
	// Enqueue login scripts.
	wp_enqueue_script( 'aqualuxe-login-script', aqualuxe_asset( 'js/login.js' ), array( 'jquery' ), AQUALUXE_VERSION, true );
}
add_action( 'login_enqueue_scripts', 'aqualuxe_login_scripts' );

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function aqualuxe_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.googleapis.com',
			'crossorigin',
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
 * Add async/defer attributes to enqueued scripts.
 *
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string
 */
function aqualuxe_script_loader_tag( $tag, $handle ) {
	$scripts_to_defer = array( 'aqualuxe-script' );
	$scripts_to_async = array();
	
	if ( in_array( $handle, $scripts_to_defer, true ) ) {
		return str_replace( ' src', ' defer src', $tag );
	}
	
	if ( in_array( $handle, $scripts_to_async, true ) ) {
		return str_replace( ' src', ' async src', $tag );
	}
	
	return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_script_loader_tag', 10, 2 );

/**
 * Add preload for critical assets.
 */
function aqualuxe_preload_assets() {
	?>
	<link rel="preload" href="<?php echo esc_url( aqualuxe_asset( 'fonts/montserrat-v15-latin-regular.woff2' ) ); ?>" as="font" type="font/woff2" crossorigin>
	<link rel="preload" href="<?php echo esc_url( aqualuxe_asset( 'fonts/playfair-display-v22-latin-700.woff2' ) ); ?>" as="font" type="font/woff2" crossorigin>
	<?php
}
add_action( 'wp_head', 'aqualuxe_preload_assets', 1 );

/**
 * Add custom inline CSS.
 */
function aqualuxe_inline_css() {
	$primary_color = get_theme_mod( 'aqualuxe_primary_color', '#0077B6' );
	$secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#00B4D8' );
	$accent_color = get_theme_mod( 'aqualuxe_accent_color', '#90E0EF' );
	$luxe_color = get_theme_mod( 'aqualuxe_luxe_color', '#CAB79F' );
	$dark_color = get_theme_mod( 'aqualuxe_dark_color', '#023E8A' );
	
	$css = ':root {
		--color-primary: ' . $primary_color . ';
		--color-secondary: ' . $secondary_color . ';
		--color-accent: ' . $accent_color . ';
		--color-luxe: ' . $luxe_color . ';
		--color-dark: ' . $dark_color . ';
	}';
	
	wp_add_inline_style( 'aqualuxe-style', $css );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_inline_css' );

/**
 * Add custom inline JavaScript.
 */
function aqualuxe_inline_js() {
	$js = 'window.aqualuxe = window.aqualuxe || {};';
	
	wp_add_inline_script( 'aqualuxe-script', $js, 'before' );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_inline_js' );

/**
 * Enqueue block editor assets.
 */
function aqualuxe_block_editor_assets() {
	// Enqueue block editor styles.
	wp_enqueue_style( 'aqualuxe-editor-style', aqualuxe_asset( 'css/editor-style.css' ), array(), AQUALUXE_VERSION );
}
add_action( 'enqueue_block_editor_assets', 'aqualuxe_block_editor_assets' );

/**
 * Enqueue block assets.
 */
function aqualuxe_block_assets() {
	// Enqueue block styles.
	wp_enqueue_style( 'aqualuxe-blocks-style', aqualuxe_asset( 'css/blocks.css' ), array(), AQUALUXE_VERSION );
}
add_action( 'enqueue_block_assets', 'aqualuxe_block_assets' );

/**
 * Enqueue scripts for the customizer controls.
 */
function aqualuxe_customize_controls_enqueue_scripts() {
	wp_enqueue_script( 'aqualuxe-customize-controls', aqualuxe_asset( 'js/customize-controls.js' ), array( 'customize-controls' ), AQUALUXE_VERSION, true );
}
add_action( 'customize_controls_enqueue_scripts', 'aqualuxe_customize_controls_enqueue_scripts' );

/**
 * Enqueue styles for the customizer controls.
 */
function aqualuxe_customize_controls_enqueue_styles() {
	wp_enqueue_style( 'aqualuxe-customize-controls', aqualuxe_asset( 'css/customize-controls.css' ), array(), AQUALUXE_VERSION );
}
add_action( 'customize_controls_enqueue_scripts', 'aqualuxe_customize_controls_enqueue_styles' );

/**
 * Enqueue scripts for the customizer preview.
 */
function aqualuxe_customize_preview_init() {
	wp_enqueue_script( 'aqualuxe-customize-preview', aqualuxe_asset( 'js/customize-preview.js' ), array( 'customize-preview' ), AQUALUXE_VERSION, true );
}
add_action( 'customize_preview_init', 'aqualuxe_customize_preview_init' );

/**
 * Enqueue styles for the customizer preview.
 */
function aqualuxe_customize_preview_enqueue_styles() {
	wp_enqueue_style( 'aqualuxe-customize-preview', aqualuxe_asset( 'css/customize-preview.css' ), array(), AQUALUXE_VERSION );
}
add_action( 'customize_preview_init', 'aqualuxe_customize_preview_enqueue_styles' );

/**
 * Enqueue scripts for the block editor.
 */
function aqualuxe_block_editor_scripts() {
	wp_enqueue_script( 'aqualuxe-editor', aqualuxe_asset( 'js/editor.js' ), array( 'wp-blocks', 'wp-dom' ), AQUALUXE_VERSION, true );
}
add_action( 'enqueue_block_editor_assets', 'aqualuxe_block_editor_scripts' );

/**
 * Enqueue styles for the block editor.
 */
function aqualuxe_block_editor_styles() {
	wp_enqueue_style( 'aqualuxe-editor-style', aqualuxe_asset( 'css/editor-style.css' ), array(), AQUALUXE_VERSION );
	
	// Add inline styles for the editor.
	$primary_color = get_theme_mod( 'aqualuxe_primary_color', '#0077B6' );
	$secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#00B4D8' );
	$accent_color = get_theme_mod( 'aqualuxe_accent_color', '#90E0EF' );
	$luxe_color = get_theme_mod( 'aqualuxe_luxe_color', '#CAB79F' );
	$dark_color = get_theme_mod( 'aqualuxe_dark_color', '#023E8A' );
	
	$css = ':root {
		--color-primary: ' . $primary_color . ';
		--color-secondary: ' . $secondary_color . ';
		--color-accent: ' . $accent_color . ';
		--color-luxe: ' . $luxe_color . ';
		--color-dark: ' . $dark_color . ';
	}';
	
	wp_add_inline_style( 'aqualuxe-editor-style', $css );
}
add_action( 'enqueue_block_editor_assets', 'aqualuxe_block_editor_styles' );

/**
 * Enqueue styles for the login page.
 */
function aqualuxe_login_enqueue_styles() {
	wp_enqueue_style( 'aqualuxe-login', aqualuxe_asset( 'css/login.css' ), array(), AQUALUXE_VERSION );
	
	// Add inline styles for the login page.
	$primary_color = get_theme_mod( 'aqualuxe_primary_color', '#0077B6' );
	
	$css = ':root {
		--color-primary: ' . $primary_color . ';
	}';
	
	wp_add_inline_style( 'aqualuxe-login', $css );
}
add_action( 'login_enqueue_scripts', 'aqualuxe_login_enqueue_styles' );

/**
 * Enqueue scripts for the login page.
 */
function aqualuxe_login_enqueue_scripts() {
	wp_enqueue_script( 'aqualuxe-login', aqualuxe_asset( 'js/login.js' ), array( 'jquery' ), AQUALUXE_VERSION, true );
}
add_action( 'login_enqueue_scripts', 'aqualuxe_login_enqueue_scripts' );

/**
 * Dequeue unnecessary scripts and styles.
 */
function aqualuxe_dequeue_scripts() {
	// Dequeue emoji script and styles.
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	
	// Dequeue wp-embed script.
	wp_deregister_script( 'wp-embed' );
	
	// Dequeue jQuery Migrate in frontend.
	if ( ! is_admin() && ! is_customize_preview() ) {
		wp_deregister_script( 'jquery-migrate' );
	}
	
	// Dequeue WooCommerce styles and replace with our own.
	if ( class_exists( 'WooCommerce' ) ) {
		wp_dequeue_style( 'woocommerce-general' );
		wp_dequeue_style( 'woocommerce-layout' );
		wp_dequeue_style( 'woocommerce-smallscreen' );
	}
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_dequeue_scripts', 999 );

/**
 * Remove unnecessary meta tags.
 */
function aqualuxe_remove_meta_tags() {
	// Remove WP version.
	remove_action( 'wp_head', 'wp_generator' );
	
	// Remove wlwmanifest link.
	remove_action( 'wp_head', 'wlwmanifest_link' );
	
	// Remove RSD link.
	remove_action( 'wp_head', 'rsd_link' );
	
	// Remove shortlink.
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	
	// Remove feed links.
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	
	// Remove REST API link.
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
}
add_action( 'init', 'aqualuxe_remove_meta_tags' );

/**
 * Add defer attribute to scripts.
 *
 * @param string $tag    Script tag.
 * @param string $handle Script handle.
 * @return string
 */
function aqualuxe_defer_scripts( $tag, $handle ) {
	// Add defer attribute to these scripts.
	$scripts_to_defer = array( 'aqualuxe-script' );
	
	// Don't defer scripts in admin or login pages.
	if ( is_admin() || $GLOBALS['pagenow'] === 'wp-login.php' ) {
		return $tag;
	}
	
	// Add defer attribute.
	if ( in_array( $handle, $scripts_to_defer, true ) ) {
		return str_replace( ' src', ' defer src', $tag );
	}
	
	return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_defer_scripts', 10, 2 );

/**
 * Add async attribute to scripts.
 *
 * @param string $tag    Script tag.
 * @param string $handle Script handle.
 * @return string
 */
function aqualuxe_async_scripts( $tag, $handle ) {
	// Add async attribute to these scripts.
	$scripts_to_async = array();
	
	// Don't async scripts in admin or login pages.
	if ( is_admin() || $GLOBALS['pagenow'] === 'wp-login.php' ) {
		return $tag;
	}
	
	// Add async attribute.
	if ( in_array( $handle, $scripts_to_async, true ) ) {
		return str_replace( ' src', ' async src', $tag );
	}
	
	return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_async_scripts', 10, 2 );

/**
 * Add preload attribute to styles.
 *
 * @param string $html   Style tag.
 * @param string $handle Style handle.
 * @return string
 */
function aqualuxe_preload_styles( $html, $handle ) {
	// Add preload attribute to these styles.
	$styles_to_preload = array( 'aqualuxe-style' );
	
	// Don't preload styles in admin or login pages.
	if ( is_admin() || $GLOBALS['pagenow'] === 'wp-login.php' ) {
		return $html;
	}
	
	// Add preload attribute.
	if ( in_array( $handle, $styles_to_preload, true ) ) {
		$html = str_replace( "rel='stylesheet'", "rel='preload' as='style' onload=&quot;this.onload=null;this.rel='stylesheet'&quot;", $html );
		$html .= "<noscript><link rel='stylesheet' href='" . esc_url( aqualuxe_asset( 'css/app.css' ) ) . "'></noscript>";
	}
	
	return $html;
}
add_filter( 'style_loader_tag', 'aqualuxe_preload_styles', 10, 2 );

/**
 * Add DNS prefetch for external resources.
 */
function aqualuxe_dns_prefetch() {
	?>
	<link rel="dns-prefetch" href="//fonts.googleapis.com">
	<link rel="dns-prefetch" href="//fonts.gstatic.com">
	<link rel="dns-prefetch" href="//ajax.googleapis.com">
	<link rel="dns-prefetch" href="//s.w.org">
	<?php
}
add_action( 'wp_head', 'aqualuxe_dns_prefetch', 0 );

/**
 * Add preconnect for external resources.
 */
function aqualuxe_preconnect() {
	?>
	<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php
}
add_action( 'wp_head', 'aqualuxe_preconnect', 0 );

/**
 * Add theme color meta tag.
 */
function aqualuxe_theme_color() {
	$theme_color = get_theme_mod( 'aqualuxe_theme_color', '#0077B6' );
	?>
	<meta name="theme-color" content="<?php echo esc_attr( $theme_color ); ?>">
	<?php
}
add_action( 'wp_head', 'aqualuxe_theme_color', 0 );

/**
 * Add viewport meta tag.
 */
function aqualuxe_viewport() {
	?>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?php
}
add_action( 'wp_head', 'aqualuxe_viewport', 0 );

/**
 * Add format detection meta tag.
 */
function aqualuxe_format_detection() {
	?>
	<meta name="format-detection" content="telephone=no">
	<?php
}
add_action( 'wp_head', 'aqualuxe_format_detection', 0 );

/**
 * Add Google Fonts.
 */
function aqualuxe_google_fonts() {
	?>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&display=swap">
	<?php
}
add_action( 'wp_head', 'aqualuxe_google_fonts', 0 );