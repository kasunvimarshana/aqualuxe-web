<?php
/**
 * Caching Functions
 *
 * Functions for implementing caching strategies to improve performance.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add browser caching headers.
 */
function aqualuxe_browser_caching_headers() {
	// Check if browser caching is enabled in customizer.
	if ( ! get_theme_mod( 'aqualuxe_browser_caching', true ) ) {
		return;
	}

	// Only add headers for static resources.
	$uri = $_SERVER['REQUEST_URI'];
	$file_extension = pathinfo( $uri, PATHINFO_EXTENSION );

	// List of static file extensions.
	$static_extensions = array(
		'css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg',
		'ico', 'woff', 'woff2', 'ttf', 'eot', 'otf', 'mp4', 'webm',
		'pdf', 'zip', 'txt'
	);

	if ( in_array( $file_extension, $static_extensions, true ) ) {
		// Set cache control headers.
		$cache_time = 31536000; // 1 year in seconds.

		// Set different cache times based on file type.
		switch ( $file_extension ) {
			case 'css':
			case 'js':
				$cache_time = 2592000; // 30 days.
				break;
			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'gif':
			case 'webp':
			case 'svg':
			case 'ico':
				$cache_time = 31536000; // 1 year.
				break;
			case 'woff':
			case 'woff2':
			case 'ttf':
			case 'eot':
			case 'otf':
				$cache_time = 31536000; // 1 year.
				break;
			default:
				$cache_time = 2592000; // 30 days.
				break;
		}

		// Set cache control headers.
		header( 'Cache-Control: public, max-age=' . $cache_time );
		header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $cache_time ) . ' GMT' );
	}
}
add_action( 'init', 'aqualuxe_browser_caching_headers' );

/**
 * Add browser caching rules to .htaccess file.
 */
function aqualuxe_add_browser_caching_htaccess() {
	// Check if browser caching is enabled in customizer.
	if ( ! get_theme_mod( 'aqualuxe_browser_caching', true ) ) {
		return;
	}

	// Check if .htaccess file exists and is writable.
	$htaccess_file = get_home_path() . '.htaccess';
	if ( ! file_exists( $htaccess_file ) || ! is_writable( $htaccess_file ) ) {
		return;
	}

	// Get current .htaccess content.
	$htaccess_content = file_get_contents( $htaccess_file );

	// Check if browser caching rules already exist.
	if ( strpos( $htaccess_content, '# BEGIN AquaLuxe Browser Caching' ) !== false ) {
		return;
	}

	// Browser caching rules.
	$browser_caching_rules = "
# BEGIN AquaLuxe Browser Caching
<IfModule mod_expires.c>
	ExpiresActive On
	ExpiresByType image/jpg &quot;access plus 1 year&quot;
	ExpiresByType image/jpeg &quot;access plus 1 year&quot;
	ExpiresByType image/gif &quot;access plus 1 year&quot;
	ExpiresByType image/png &quot;access plus 1 year&quot;
	ExpiresByType image/webp &quot;access plus 1 year&quot;
	ExpiresByType image/svg+xml &quot;access plus 1 year&quot;
	ExpiresByType image/x-icon &quot;access plus 1 year&quot;
	ExpiresByType video/mp4 &quot;access plus 1 year&quot;
	ExpiresByType video/webm &quot;access plus 1 year&quot;
	ExpiresByType text/css &quot;access plus 1 month&quot;
	ExpiresByType text/javascript &quot;access plus 1 month&quot;
	ExpiresByType application/javascript &quot;access plus 1 month&quot;
	ExpiresByType application/pdf &quot;access plus 1 month&quot;
	ExpiresByType application/x-font-woff &quot;access plus 1 year&quot;
	ExpiresByType application/font-woff2 &quot;access plus 1 year&quot;
	ExpiresByType font/woff &quot;access plus 1 year&quot;
	ExpiresByType font/woff2 &quot;access plus 1 year&quot;
	ExpiresDefault &quot;access plus 2 days&quot;
</IfModule>

<IfModule mod_deflate.c>
	AddOutputFilterByType DEFLATE text/plain
	AddOutputFilterByType DEFLATE text/html
	AddOutputFilterByType DEFLATE text/xml
	AddOutputFilterByType DEFLATE text/css
	AddOutputFilterByType DEFLATE text/javascript
	AddOutputFilterByType DEFLATE application/xml
	AddOutputFilterByType DEFLATE application/xhtml+xml
	AddOutputFilterByType DEFLATE application/rss+xml
	AddOutputFilterByType DEFLATE application/javascript
	AddOutputFilterByType DEFLATE application/x-javascript
	AddOutputFilterByType DEFLATE application/json
	AddOutputFilterByType DEFLATE application/x-font-ttf
	AddOutputFilterByType DEFLATE application/x-font-otf
	AddOutputFilterByType DEFLATE font/opentype
	AddOutputFilterByType DEFLATE font/ttf
	AddOutputFilterByType DEFLATE font/eot
	AddOutputFilterByType DEFLATE font/otf
</IfModule>

<IfModule mod_headers.c>
	<FilesMatch &quot;\.(jpg|jpeg|png|gif|webp|svg|ico|mp4|webm|css|js|woff|woff2|ttf|eot|otf)$&quot;>
		Header set Cache-Control &quot;public, max-age=31536000&quot;
	</FilesMatch>
	<FilesMatch &quot;\.(css|js)$&quot;>
		Header set Cache-Control &quot;public, max-age=2592000&quot;
	</FilesMatch>
</IfModule>
# END AquaLuxe Browser Caching
";

	// Add browser caching rules to .htaccess.
	$htaccess_content = $browser_caching_rules . $htaccess_content;
	file_put_contents( $htaccess_file, $htaccess_content );
}
add_action( 'admin_init', 'aqualuxe_add_browser_caching_htaccess' );

/**
 * Implement object caching for database queries.
 *
 * @param string $query SQL query.
 * @return string SQL query.
 */
function aqualuxe_object_caching( $query ) {
	// Check if object caching is enabled in customizer.
	if ( ! get_theme_mod( 'aqualuxe_object_caching', true ) ) {
		return $query;
	}

	// Skip if query is empty.
	if ( empty( $query ) ) {
		return $query;
	}

	// Skip if query is for admin or ajax.
	if ( is_admin() || wp_doing_ajax() ) {
		return $query;
	}

	// Skip if query is for update or insert.
	if ( strpos( $query, 'UPDATE' ) === 0 || strpos( $query, 'INSERT' ) === 0 || strpos( $query, 'DELETE' ) === 0 ) {
		return $query;
	}

	// Generate cache key.
	$cache_key = 'aqualuxe_query_' . md5( $query );

	// Check if query result is cached.
	$cached_result = wp_cache_get( $cache_key, 'aqualuxe_queries' );

	if ( false !== $cached_result ) {
		// Return cached result.
		return $cached_result;
	}

	// Cache query result.
	wp_cache_set( $cache_key, $query, 'aqualuxe_queries', 3600 ); // Cache for 1 hour.

	return $query;
}
add_filter( 'query', 'aqualuxe_object_caching' );

/**
 * Implement transient caching for expensive operations.
 *
 * @param string $key Cache key.
 * @param callable $callback Callback function to generate data.
 * @param int $expiration Expiration time in seconds.
 * @return mixed Cached data.
 */
function aqualuxe_transient_cache( $key, $callback, $expiration = 3600 ) {
	// Check if transient caching is enabled in customizer.
	if ( ! get_theme_mod( 'aqualuxe_transient_caching', true ) ) {
		return call_user_func( $callback );
	}

	// Generate cache key.
	$cache_key = 'aqualuxe_' . md5( $key );

	// Check if data is cached.
	$cached_data = get_transient( $cache_key );

	if ( false !== $cached_data ) {
		// Return cached data.
		return $cached_data;
	}

	// Generate data.
	$data = call_user_func( $callback );

	// Cache data.
	set_transient( $cache_key, $data, $expiration );

	return $data;
}

/**
 * Clear transient cache.
 *
 * @param string $key Cache key.
 */
function aqualuxe_clear_transient_cache( $key ) {
	// Generate cache key.
	$cache_key = 'aqualuxe_' . md5( $key );

	// Delete transient.
	delete_transient( $cache_key );
}

/**
 * Clear all transient caches.
 */
function aqualuxe_clear_all_transient_caches() {
	global $wpdb;

	// Delete all transients.
	$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '%_transient_aqualuxe_%'" );
	$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '%_transient_timeout_aqualuxe_%'" );
}

/**
 * Implement fragment caching for template parts.
 *
 * @param string $key Cache key.
 * @param callable $callback Callback function to generate content.
 * @param int $expiration Expiration time in seconds.
 */
function aqualuxe_fragment_cache( $key, $callback, $expiration = 3600 ) {
	// Check if fragment caching is enabled in customizer.
	if ( ! get_theme_mod( 'aqualuxe_fragment_caching', true ) ) {
		call_user_func( $callback );
		return;
	}

	// Generate cache key.
	$cache_key = 'aqualuxe_fragment_' . md5( $key );

	// Check if content is cached.
	$cached_content = get_transient( $cache_key );

	if ( false !== $cached_content ) {
		// Output cached content.
		echo $cached_content;
		return;
	}

	// Start output buffering.
	ob_start();

	// Generate content.
	call_user_func( $callback );

	// Get content.
	$content = ob_get_clean();

	// Cache content.
	set_transient( $cache_key, $content, $expiration );

	// Output content.
	echo $content;
}

/**
 * Clear fragment cache.
 *
 * @param string $key Cache key.
 */
function aqualuxe_clear_fragment_cache( $key ) {
	// Generate cache key.
	$cache_key = 'aqualuxe_fragment_' . md5( $key );

	// Delete transient.
	delete_transient( $cache_key );
}

/**
 * Clear all fragment caches.
 */
function aqualuxe_clear_all_fragment_caches() {
	global $wpdb;

	// Delete all fragment transients.
	$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '%_transient_aqualuxe_fragment_%'" );
	$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '%_transient_timeout_aqualuxe_fragment_%'" );
}

/**
 * Implement page caching.
 */
function aqualuxe_page_caching() {
	// Check if page caching is enabled in customizer.
	if ( ! get_theme_mod( 'aqualuxe_page_caching', false ) ) {
		return;
	}

	// Skip if user is logged in.
	if ( is_user_logged_in() ) {
		return;
	}

	// Skip if admin or ajax.
	if ( is_admin() || wp_doing_ajax() ) {
		return;
	}

	// Skip if POST request.
	if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
		return;
	}

	// Skip if query parameters.
	if ( ! empty( $_GET ) ) {
		return;
	}

	// Generate cache key.
	$cache_key = 'aqualuxe_page_' . md5( $_SERVER['REQUEST_URI'] );

	// Check if page is cached.
	$cached_page = get_transient( $cache_key );

	if ( false !== $cached_page ) {
		// Output cached page.
		echo $cached_page;
		exit;
	}

	// Start output buffering.
	ob_start();
}
add_action( 'template_redirect', 'aqualuxe_page_caching', 1 );

/**
 * Save cached page.
 */
function aqualuxe_save_cached_page() {
	// Check if page caching is enabled in customizer.
	if ( ! get_theme_mod( 'aqualuxe_page_caching', false ) ) {
		return;
	}

	// Skip if user is logged in.
	if ( is_user_logged_in() ) {
		return;
	}

	// Skip if admin or ajax.
	if ( is_admin() || wp_doing_ajax() ) {
		return;
	}

	// Skip if POST request.
	if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
		return;
	}

	// Skip if query parameters.
	if ( ! empty( $_GET ) ) {
		return;
	}

	// Generate cache key.
	$cache_key = 'aqualuxe_page_' . md5( $_SERVER['REQUEST_URI'] );

	// Get page content.
	$page_content = ob_get_contents();

	// Cache page content.
	set_transient( $cache_key, $page_content, 3600 ); // Cache for 1 hour.
}
add_action( 'shutdown', 'aqualuxe_save_cached_page', 0 );

/**
 * Clear page cache.
 */
function aqualuxe_clear_page_cache() {
	global $wpdb;

	// Delete all page transients.
	$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '%_transient_aqualuxe_page_%'" );
	$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '%_transient_timeout_aqualuxe_page_%'" );
}

/**
 * Clear page cache on post update.
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_clear_page_cache_on_update( $post_id ) {
	// Skip if autosave.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Skip if revision.
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	// Clear page cache.
	aqualuxe_clear_page_cache();
}
add_action( 'save_post', 'aqualuxe_clear_page_cache_on_update' );
add_action( 'deleted_post', 'aqualuxe_clear_page_cache_on_update' );
add_action( 'trashed_post', 'aqualuxe_clear_page_cache_on_update' );
add_action( 'untrashed_post', 'aqualuxe_clear_page_cache_on_update' );

/**
 * Clear page cache on comment update.
 *
 * @param int $comment_id Comment ID.
 */
function aqualuxe_clear_page_cache_on_comment_update( $comment_id ) {
	// Clear page cache.
	aqualuxe_clear_page_cache();
}
add_action( 'wp_insert_comment', 'aqualuxe_clear_page_cache_on_comment_update' );
add_action( 'edit_comment', 'aqualuxe_clear_page_cache_on_comment_update' );
add_action( 'delete_comment', 'aqualuxe_clear_page_cache_on_comment_update' );
add_action( 'trash_comment', 'aqualuxe_clear_page_cache_on_comment_update' );
add_action( 'untrash_comment', 'aqualuxe_clear_page_cache_on_comment_update' );

/**
 * Add caching settings to the customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_caching_customizer_settings( $wp_customize ) {
	// Add caching section.
	$wp_customize->add_section(
		'aqualuxe_caching',
		array(
			'title'    => __( 'Caching', 'aqualuxe' ),
			'priority' => 120,
			'panel'    => 'aqualuxe_performance',
		)
	);

	// Add browser caching setting.
	$wp_customize->add_setting(
		'aqualuxe_browser_caching',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_browser_caching',
		array(
			'label'       => __( 'Enable Browser Caching', 'aqualuxe' ),
			'description' => __( 'Add browser caching headers to improve performance.', 'aqualuxe' ),
			'section'     => 'aqualuxe_caching',
			'type'        => 'checkbox',
		)
	);

	// Add object caching setting.
	$wp_customize->add_setting(
		'aqualuxe_object_caching',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_object_caching',
		array(
			'label'       => __( 'Enable Object Caching', 'aqualuxe' ),
			'description' => __( 'Cache database queries to improve performance.', 'aqualuxe' ),
			'section'     => 'aqualuxe_caching',
			'type'        => 'checkbox',
		)
	);

	// Add transient caching setting.
	$wp_customize->add_setting(
		'aqualuxe_transient_caching',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_transient_caching',
		array(
			'label'       => __( 'Enable Transient Caching', 'aqualuxe' ),
			'description' => __( 'Cache expensive operations to improve performance.', 'aqualuxe' ),
			'section'     => 'aqualuxe_caching',
			'type'        => 'checkbox',
		)
	);

	// Add fragment caching setting.
	$wp_customize->add_setting(
		'aqualuxe_fragment_caching',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_fragment_caching',
		array(
			'label'       => __( 'Enable Fragment Caching', 'aqualuxe' ),
			'description' => __( 'Cache template parts to improve performance.', 'aqualuxe' ),
			'section'     => 'aqualuxe_caching',
			'type'        => 'checkbox',
		)
	);

	// Add page caching setting.
	$wp_customize->add_setting(
		'aqualuxe_page_caching',
		array(
			'default'           => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_page_caching',
		array(
			'label'       => __( 'Enable Page Caching', 'aqualuxe' ),
			'description' => __( 'Cache entire pages to improve performance. Not recommended if you use dynamic content.', 'aqualuxe' ),
			'section'     => 'aqualuxe_caching',
			'type'        => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'aqualuxe_caching_customizer_settings' );

/**
 * Sanitize checkbox values.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function aqualuxe_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true === $checked ) ? true : false );
}

/**
 * Add cache control panel to the admin bar.
 *
 * @param WP_Admin_Bar $wp_admin_bar Admin bar object.
 */
function aqualuxe_cache_control_admin_bar( $wp_admin_bar ) {
	// Skip if user can't manage options.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Add cache control menu.
	$wp_admin_bar->add_menu(
		array(
			'id'    => 'aqualuxe-cache',
			'title' => __( 'Cache Control', 'aqualuxe' ),
			'href'  => '#',
		)
	);

	// Add clear all caches submenu.
	$wp_admin_bar->add_menu(
		array(
			'parent' => 'aqualuxe-cache',
			'id'     => 'aqualuxe-cache-clear-all',
			'title'  => __( 'Clear All Caches', 'aqualuxe' ),
			'href'   => wp_nonce_url( admin_url( 'admin-post.php?action=aqualuxe_clear_all_caches' ), 'aqualuxe_clear_all_caches' ),
		)
	);

	// Add clear page cache submenu.
	$wp_admin_bar->add_menu(
		array(
			'parent' => 'aqualuxe-cache',
			'id'     => 'aqualuxe-cache-clear-page',
			'title'  => __( 'Clear Page Cache', 'aqualuxe' ),
			'href'   => wp_nonce_url( admin_url( 'admin-post.php?action=aqualuxe_clear_page_cache' ), 'aqualuxe_clear_page_cache' ),
		)
	);

	// Add clear fragment cache submenu.
	$wp_admin_bar->add_menu(
		array(
			'parent' => 'aqualuxe-cache',
			'id'     => 'aqualuxe-cache-clear-fragment',
			'title'  => __( 'Clear Fragment Cache', 'aqualuxe' ),
			'href'   => wp_nonce_url( admin_url( 'admin-post.php?action=aqualuxe_clear_fragment_cache' ), 'aqualuxe_clear_fragment_cache' ),
		)
	);

	// Add clear transient cache submenu.
	$wp_admin_bar->add_menu(
		array(
			'parent' => 'aqualuxe-cache',
			'id'     => 'aqualuxe-cache-clear-transient',
			'title'  => __( 'Clear Transient Cache', 'aqualuxe' ),
			'href'   => wp_nonce_url( admin_url( 'admin-post.php?action=aqualuxe_clear_transient_cache' ), 'aqualuxe_clear_transient_cache' ),
		)
	);
}
add_action( 'admin_bar_menu', 'aqualuxe_cache_control_admin_bar', 100 );

/**
 * Handle clear all caches action.
 */
function aqualuxe_handle_clear_all_caches() {
	// Check nonce.
	if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'aqualuxe_clear_all_caches' ) ) {
		wp_die( __( 'Security check failed.', 'aqualuxe' ) );
	}

	// Check user capability.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have permission to clear caches.', 'aqualuxe' ) );
	}

	// Clear all caches.
	aqualuxe_clear_page_cache();
	aqualuxe_clear_all_fragment_caches();
	aqualuxe_clear_all_transient_caches();

	// Redirect back.
	wp_safe_redirect( wp_get_referer() );
	exit;
}
add_action( 'admin_post_aqualuxe_clear_all_caches', 'aqualuxe_handle_clear_all_caches' );

/**
 * Handle clear page cache action.
 */
function aqualuxe_handle_clear_page_cache() {
	// Check nonce.
	if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'aqualuxe_clear_page_cache' ) ) {
		wp_die( __( 'Security check failed.', 'aqualuxe' ) );
	}

	// Check user capability.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have permission to clear caches.', 'aqualuxe' ) );
	}

	// Clear page cache.
	aqualuxe_clear_page_cache();

	// Redirect back.
	wp_safe_redirect( wp_get_referer() );
	exit;
}
add_action( 'admin_post_aqualuxe_clear_page_cache', 'aqualuxe_handle_clear_page_cache' );

/**
 * Handle clear fragment cache action.
 */
function aqualuxe_handle_clear_fragment_cache() {
	// Check nonce.
	if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'aqualuxe_clear_fragment_cache' ) ) {
		wp_die( __( 'Security check failed.', 'aqualuxe' ) );
	}

	// Check user capability.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have permission to clear caches.', 'aqualuxe' ) );
	}

	// Clear fragment cache.
	aqualuxe_clear_all_fragment_caches();

	// Redirect back.
	wp_safe_redirect( wp_get_referer() );
	exit;
}
add_action( 'admin_post_aqualuxe_clear_fragment_cache', 'aqualuxe_handle_clear_fragment_cache' );

/**
 * Handle clear transient cache action.
 */
function aqualuxe_handle_clear_transient_cache() {
	// Check nonce.
	if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'aqualuxe_clear_transient_cache' ) ) {
		wp_die( __( 'Security check failed.', 'aqualuxe' ) );
	}

	// Check user capability.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have permission to clear caches.', 'aqualuxe' ) );
	}

	// Clear transient cache.
	aqualuxe_clear_all_transient_caches();

	// Redirect back.
	wp_safe_redirect( wp_get_referer() );
	exit;
}
add_action( 'admin_post_aqualuxe_clear_transient_cache', 'aqualuxe_handle_clear_transient_cache' );