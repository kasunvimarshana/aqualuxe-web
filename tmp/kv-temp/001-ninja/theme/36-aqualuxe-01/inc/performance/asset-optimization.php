<?php
/**
 * Asset Optimization Functions
 *
 * Functions for optimizing asset loading in the theme.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add async/defer attributes to enqueued scripts where needed.
 *
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string Modified script tag.
 */
function aqualuxe_script_loader_tag( $tag, $handle ) {
	// Add script handles to the array below to add async/defer attributes.
	$async_scripts = array(
		'aqualuxe-navigation',
		'aqualuxe-skip-link-focus-fix',
		'aqualuxe-custom',
	);

	$defer_scripts = array(
		'aqualuxe-sliders',
		'aqualuxe-animations',
	);

	if ( in_array( $handle, $async_scripts, true ) ) {
		return str_replace( ' src', ' async src', $tag );
	}

	if ( in_array( $handle, $defer_scripts, true ) ) {
		return str_replace( ' src', ' defer src', $tag );
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_script_loader_tag', 10, 2 );

/**
 * Remove query strings from static resources.
 *
 * @param string $src The URL of the resource.
 * @return string Modified URL.
 */
function aqualuxe_remove_script_version( $src ) {
	// Don't remove query strings from admin scripts.
	if ( is_admin() ) {
		return $src;
	}

	// Don't remove query strings from WordPress core files.
	if ( strpos( $src, 'wp-includes' ) !== false || strpos( $src, 'wp-admin' ) !== false ) {
		return $src;
	}

	// Remove version query string from all other scripts and styles.
	$parts = explode( '?', $src );
	return $parts[0];
}
// Uncomment these lines if you want to remove query strings from static resources.
// add_filter( 'script_loader_src', 'aqualuxe_remove_script_version', 15 );
// add_filter( 'style_loader_src', 'aqualuxe_remove_script_version', 15 );

/**
 * Preload critical assets.
 */
function aqualuxe_preload_assets() {
	// Preload fonts.
	echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>';
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
	
	// Preload critical CSS.
	echo '<link rel="preload" href="' . esc_url( get_template_directory_uri() . '/assets/css/critical.css' ) . '" as="style">';
	
	// Preload logo image.
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if ( $custom_logo_id ) {
		$logo_image = wp_get_attachment_image_src( $custom_logo_id, 'full' );
		if ( $logo_image ) {
			echo '<link rel="preload" href="' . esc_url( $logo_image[0] ) . '" as="image">';
		}
	}
	
	// Preload hero image on homepage
	if ( is_front_page() && ! is_home() ) {
		$hero_image = get_theme_mod( 'aqualuxe_hero_image', get_template_directory_uri() . '/assets/images/hero-default.jpg' );
		if ( $hero_image ) {
			echo '<link rel="preload" href="' . esc_url( $hero_image ) . '" as="image">';
		}
	}
}
add_action( 'wp_head', 'aqualuxe_preload_assets', 1 );

/**
 * Disable emojis for better performance.
 */
function aqualuxe_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	
	// Remove the tinymce emoji plugin.
	add_filter( 'tiny_mce_plugins', 'aqualuxe_disable_emojis_tinymce' );
	
	// Remove emoji CDN hostname from DNS prefetching hints.
	add_filter( 'wp_resource_hints', 'aqualuxe_disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'aqualuxe_disable_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param array $plugins Array of TinyMCE plugins.
 * @return array Modified array of TinyMCE plugins.
 */
function aqualuxe_disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	}
	
	return array();
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @param array  $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array Modified URLs for resource hints.
 */
function aqualuxe_disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/13.0.1/svg/' );
		$urls = array_diff( $urls, array( $emoji_svg_url ) );
	}
	
	return $urls;
}

/**
 * Disable unnecessary WordPress features for better performance.
 */
function aqualuxe_disable_unnecessary_features() {
	// Disable XML-RPC.
	add_filter( 'xmlrpc_enabled', '__return_false' );
	
	// Remove RSD link.
	remove_action( 'wp_head', 'rsd_link' );
	
	// Remove Windows Live Writer manifest link.
	remove_action( 'wp_head', 'wlwmanifest_link' );
	
	// Remove WordPress version.
	remove_action( 'wp_head', 'wp_generator' );
	
	// Remove shortlink.
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	
	// Remove REST API link.
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	
	// Remove oEmbed links.
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
}
add_action( 'init', 'aqualuxe_disable_unnecessary_features' );

/**
 * Optimize Google Fonts loading.
 *
 * @param string $html The link tag for the enqueued style.
 * @param string $handle The style's registered handle.
 * @return string Modified link tag.
 */
function aqualuxe_optimize_google_fonts( $html, $handle ) {
	if ( strpos( $handle, 'google-fonts' ) !== false ) {
		// Add preconnect for Google Fonts.
		$html = '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . $html;
		
		// Add font-display swap to Google Fonts.
		$html = str_replace( "rel='stylesheet'", "rel='stylesheet' media='print' onload='this.media=&quot;all&quot;'", $html );
		
		// Add noscript fallback.
		$html .= "<noscript>" . str_replace( "media='print' onload='this.media=&quot;all&quot;'", '', $html ) . "</noscript>";
	}
	
	return $html;
}
add_filter( 'style_loader_tag', 'aqualuxe_optimize_google_fonts', 10, 2 );

/**
 * Add resource hints for better performance.
 *
 * @param array  $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array Modified URLs for resource hints.
 */
function aqualuxe_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		// Add preconnect for Google Fonts.
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
		
		// Add preconnect for other external resources if needed.
		// $urls[] = array(
		// 	'href' => 'https://example.com',
		// 	'crossorigin',
		// );
	}
	
	return $urls;
}
add_filter( 'wp_resource_hints', 'aqualuxe_resource_hints', 10, 2 );

/**
 * Implement lazy loading for images.
 *
 * @param array $attr Array of attribute values for the image markup.
 * @param WP_Post $attachment Image attachment post.
 * @param string|array $size Requested size.
 * @return array Modified array of attribute values.
 */
function aqualuxe_lazy_load_image_attributes( $attr, $attachment, $size ) {
	// Skip if admin or feed.
	if ( is_admin() || is_feed() ) {
		return $attr;
	}
	
	// Skip if AMP page.
	if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
		return $attr;
	}
	
	// Add loading="lazy" attribute for images.
	$attr['loading'] = 'lazy';
	
	// Add decoding="async" attribute for images.
	$attr['decoding'] = 'async';
	
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_lazy_load_image_attributes', 10, 3 );

/**
 * Add browser caching headers.
 */
function aqualuxe_add_browser_caching_headers() {
	// Check if .htaccess file exists and is writable.
	$htaccess_file = get_home_path() . '.htaccess';
	
	if ( file_exists( $htaccess_file ) && is_writable( $htaccess_file ) ) {
		// Get current .htaccess content.
		$htaccess_content = file_get_contents( $htaccess_file );
		
		// Check if browser caching rules already exist.
		if ( strpos( $htaccess_content, '# BEGIN Browser Caching' ) === false ) {
			// Browser caching rules.
			$browser_caching_rules = "
# BEGIN Browser Caching
<IfModule mod_expires.c>
ExpiresActive On
ExpiresByType image/jpg &quot;access plus 1 year&quot;
ExpiresByType image/jpeg &quot;access plus 1 year&quot;
ExpiresByType image/gif &quot;access plus 1 year&quot;
ExpiresByType image/png &quot;access plus 1 year&quot;
ExpiresByType image/svg+xml &quot;access plus 1 year&quot;
ExpiresByType image/webp &quot;access plus 1 year&quot;
ExpiresByType text/css &quot;access plus 1 month&quot;
ExpiresByType text/html &quot;access plus 1 month&quot;
ExpiresByType application/pdf &quot;access plus 1 month&quot;
ExpiresByType application/javascript &quot;access plus 1 month&quot;
ExpiresByType application/x-javascript &quot;access plus 1 month&quot;
ExpiresByType application/x-shockwave-flash &quot;access plus 1 month&quot;
ExpiresByType image/x-icon &quot;access plus 1 year&quot;
ExpiresDefault &quot;access plus 1 month&quot;
</IfModule>
# END Browser Caching
";
			
			// Add browser caching rules to .htaccess.
			$htaccess_content = $browser_caching_rules . $htaccess_content;
			
			// Write to .htaccess file.
			file_put_contents( $htaccess_file, $htaccess_content );
		}
	}
}
// Uncomment this line if you want to add browser caching headers.
// add_action( 'admin_init', 'aqualuxe_add_browser_caching_headers' );

/**
 * Optimize CSS delivery.
 */
function aqualuxe_optimize_css_delivery() {
	// Get critical CSS file.
	$critical_css_file = get_template_directory() . '/assets/css/critical.css';
	
	if ( file_exists( $critical_css_file ) ) {
		// Get critical CSS content.
		$critical_css = file_get_contents( $critical_css_file );
		
		// Print critical CSS inline.
		echo '<style id="aqualuxe-critical-css">' . $critical_css . '</style>';
	}
}
add_action( 'wp_head', 'aqualuxe_optimize_css_delivery', 1 );

/**
 * Add responsive image support.
 */
function aqualuxe_responsive_image_sizes() {
	// Set default image sizes.
	update_option( 'thumbnail_size_w', 150 );
	update_option( 'thumbnail_size_h', 150 );
	update_option( 'thumbnail_crop', 1 );
	update_option( 'medium_size_w', 300 );
	update_option( 'medium_size_h', 300 );
	update_option( 'medium_crop', 0 );
	update_option( 'large_size_w', 1024 );
	update_option( 'large_size_h', 1024 );
	update_option( 'large_crop', 0 );
	
	// Add custom image sizes.
	add_image_size( 'aqualuxe-featured', 1200, 675, true );
	add_image_size( 'aqualuxe-card', 600, 400, true );
	add_image_size( 'aqualuxe-hero', 1920, 1080, true );
}
add_action( 'after_setup_theme', 'aqualuxe_responsive_image_sizes' );

/**
 * Add custom image sizes to media library.
 *
 * @param array $sizes Array of image sizes.
 * @return array Modified array of image sizes.
 */
function aqualuxe_custom_image_sizes( $sizes ) {
	return array_merge( $sizes, array(
		'aqualuxe-featured' => __( 'Featured Image', 'aqualuxe' ),
		'aqualuxe-card' => __( 'Card Image', 'aqualuxe' ),
		'aqualuxe-hero' => __( 'Hero Image', 'aqualuxe' ),
	) );
}
add_filter( 'image_size_names_choose', 'aqualuxe_custom_image_sizes' );

/**
 * Add responsive image attributes.
 *
 * @param array $attr Array of attribute values for the image markup.
 * @param WP_Post $attachment Image attachment post.
 * @param string|array $size Requested size.
 * @return array Modified array of attribute values.
 */
function aqualuxe_responsive_image_attributes( $attr, $attachment, $size ) {
	// Add loading="lazy" attribute for images.
	$attr['loading'] = 'lazy';
	
	// Add decoding="async" attribute for images.
	$attr['decoding'] = 'async';
	
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_responsive_image_attributes', 10, 3 );

/**
 * Minify HTML output.
 *
 * @param string $buffer HTML output.
 * @return string Minified HTML output.
 */
function aqualuxe_minify_html( $buffer ) {
	// Skip if admin or feed.
	if ( is_admin() || is_feed() ) {
		return $buffer;
	}
	
	// Skip if buffer is empty.
	if ( empty( $buffer ) ) {
		return $buffer;
	}
	
	// Remove comments (except IE conditional comments).
	$buffer = preg_replace( '/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $buffer );
	
	// Remove whitespace.
	$buffer = preg_replace( '/\s+/', ' ', $buffer );
	
	// Remove whitespace between HTML tags.
	$buffer = preg_replace( '/>\s+</', '><', $buffer );
	
	// Remove whitespace at the beginning and end of the buffer.
	$buffer = trim( $buffer );
	
	return $buffer;
}
// Uncomment this line if you want to minify HTML output.
// add_action( 'template_redirect', function() { ob_start( 'aqualuxe_minify_html' ); } );

/**
 * Optimize web fonts loading.
 */
function aqualuxe_optimize_web_fonts() {
	// Add font-display: swap to all @font-face rules.
	?>
	<script>
	(function() {
		// Check if FontFace is supported.
		if ('FontFace' in window) {
			// Get all stylesheets.
			var stylesheets = document.styleSheets;
			
			// Loop through stylesheets.
			for (var i = 0; i < stylesheets.length; i++) {
				try {
					// Get stylesheet rules.
					var rules = stylesheets[i].cssRules || stylesheets[i].rules;
					
					// Loop through rules.
					for (var j = 0; j < rules.length; j++) {
						// Check if rule is @font-face.
						if (rules[j].type === CSSRule.FONT_FACE_RULE) {
							// Add font-display: swap.
							rules[j].style.fontDisplay = 'swap';
						}
					}
				} catch (e) {
					// Skip if can't access stylesheet.
					continue;
				}
			}
		}
	})();
	</script>
	<?php
}
add_action( 'wp_head', 'aqualuxe_optimize_web_fonts', 1 );

/**
 * Register performance settings in the customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_performance_customizer( $wp_customize ) {
	// Add performance section.
	$wp_customize->add_section(
		'aqualuxe_performance',
		array(
			'title'    => __( 'Performance', 'aqualuxe' ),
			'priority' => 200,
		)
	);
	
	// Add disable emojis setting.
	$wp_customize->add_setting(
		'aqualuxe_disable_emojis',
		array(
			'default'           => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_disable_emojis',
		array(
			'label'   => __( 'Disable WordPress emojis', 'aqualuxe' ),
			'section' => 'aqualuxe_performance',
			'type'    => 'checkbox',
		)
	);
	
	// Add disable embeds setting.
	$wp_customize->add_setting(
		'aqualuxe_disable_embeds',
		array(
			'default'           => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_disable_embeds',
		array(
			'label'   => __( 'Disable WordPress embeds', 'aqualuxe' ),
			'section' => 'aqualuxe_performance',
			'type'    => 'checkbox',
		)
	);
	
	// Add inline critical CSS setting.
	$wp_customize->add_setting(
		'aqualuxe_inline_critical_css',
		array(
			'default'           => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_inline_critical_css',
		array(
			'label'   => __( 'Inline critical CSS', 'aqualuxe' ),
			'section' => 'aqualuxe_performance',
			'type'    => 'checkbox',
		)
	);
	
	// Add preload assets setting.
	$wp_customize->add_setting(
		'aqualuxe_preload_assets',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_preload_assets',
		array(
			'label'   => __( 'Preload critical assets', 'aqualuxe' ),
			'section' => 'aqualuxe_performance',
			'type'    => 'checkbox',
		)
	);
	
	// Add use Google Fonts setting.
	$wp_customize->add_setting(
		'aqualuxe_use_google_fonts',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_use_google_fonts',
		array(
			'label'   => __( 'Use Google Fonts', 'aqualuxe' ),
			'section' => 'aqualuxe_performance',
			'type'    => 'checkbox',
		)
	);
	
	// Add use Font Awesome CDN setting.
	$wp_customize->add_setting(
		'aqualuxe_use_fontawesome_cdn',
		array(
			'default'           => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_use_fontawesome_cdn',
		array(
			'label'   => __( 'Use Font Awesome CDN', 'aqualuxe' ),
			'section' => 'aqualuxe_performance',
			'type'    => 'checkbox',
		)
	);
	
	// Add Google Analytics ID setting.
	$wp_customize->add_setting(
		'aqualuxe_google_analytics_id',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_google_analytics_id',
		array(
			'label'   => __( 'Google Analytics ID', 'aqualuxe' ),
			'section' => 'aqualuxe_performance',
			'type'    => 'text',
		)
	);
}
add_action( 'customize_register', 'aqualuxe_performance_customizer' );

/**
 * Sanitize checkbox values.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function aqualuxe_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true === $checked ) ? true : false );
}