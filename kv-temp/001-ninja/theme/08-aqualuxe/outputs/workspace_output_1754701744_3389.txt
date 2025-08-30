<?php
/**
 * AquaLuxe Theme Performance Optimizations
 *
 * Handles various performance optimizations for the theme.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Remove unnecessary WordPress features to improve performance.
 */
function aqualuxe_disable_emojis() {
	// Check if emoji disabling is enabled in theme options.
	$disable_emojis = get_theme_mod( 'aqualuxe_disable_emojis', true );
	
	if ( ! $disable_emojis ) {
		return;
	}
	
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	
	// Remove the tinymce emoji plugin.
	add_filter( 'tiny_mce_plugins', 'aqualuxe_disable_emojis_tinymce' );
	
	// Remove emoji DNS prefetch.
	add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'aqualuxe_disable_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param array $plugins Array of TinyMCE plugins.
 * @return array Difference between the two arrays.
 */
function aqualuxe_disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	}
	
	return array();
}

/**
 * Disable WordPress embeds.
 */
function aqualuxe_disable_embeds() {
	// Check if embed disabling is enabled in theme options.
	$disable_embeds = get_theme_mod( 'aqualuxe_disable_embeds', true );
	
	if ( ! $disable_embeds ) {
		return;
	}
	
	// Remove the embed script.
	wp_deregister_script( 'wp-embed' );
	
	// Remove the REST API endpoint.
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );
	
	// Turn off oEmbed auto discovery.
	add_filter( 'embed_oembed_discover', '__return_false' );
	
	// Don't filter oEmbed results.
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
	
	// Remove oEmbed discovery links.
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	
	// Remove oEmbed-specific JavaScript from the front-end and back-end.
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	
	// Remove all embeds rewrite rules.
	add_filter( 'rewrite_rules_array', 'aqualuxe_disable_embeds_rewrites' );
}
add_action( 'init', 'aqualuxe_disable_embeds', 9999 );

/**
 * Remove all rewrite rules related to embeds.
 *
 * @param array $rules WordPress rewrite rules.
 * @return array Rewrite rules without embeds rules.
 */
function aqualuxe_disable_embeds_rewrites( $rules ) {
	foreach ( $rules as $rule => $rewrite ) {
		if ( false !== strpos( $rewrite, 'embed=true' ) ) {
			unset( $rules[ $rule ] );
		}
	}
	
	return $rules;
}

/**
 * Remove query strings from static resources.
 */
function aqualuxe_remove_script_version( $src ) {
	// Check if query string removal is enabled in theme options.
	$remove_query_strings = get_theme_mod( 'aqualuxe_remove_query_strings', true );
	
	if ( ! $remove_query_strings ) {
		return $src;
	}
	
	if ( strpos( $src, '?ver=' ) ) {
		$src = remove_query_arg( 'ver', $src );
	}
	
	return $src;
}
add_filter( 'script_loader_src', 'aqualuxe_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'aqualuxe_remove_script_version', 15, 1 );

/**
 * Disable XML-RPC.
 */
function aqualuxe_disable_xmlrpc() {
	// Check if XML-RPC disabling is enabled in theme options.
	$disable_xmlrpc = get_theme_mod( 'aqualuxe_disable_xmlrpc', true );
	
	if ( ! $disable_xmlrpc ) {
		return;
	}
	
	// Disable XML-RPC methods that require authentication.
	add_filter( 'xmlrpc_enabled', '__return_false' );
	
	// Remove RSD link from head.
	remove_action( 'wp_head', 'rsd_link' );
}
add_action( 'init', 'aqualuxe_disable_xmlrpc' );

/**
 * Remove unnecessary meta tags from the head.
 */
function aqualuxe_remove_header_meta() {
	// Check if meta tag removal is enabled in theme options.
	$remove_header_meta = get_theme_mod( 'aqualuxe_remove_header_meta', true );
	
	if ( ! $remove_header_meta ) {
		return;
	}
	
	// Remove WordPress version.
	remove_action( 'wp_head', 'wp_generator' );
	
	// Remove wlwmanifest link.
	remove_action( 'wp_head', 'wlwmanifest_link' );
	
	// Remove shortlink.
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	
	// Remove feed links.
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	
	// Remove REST API link.
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
}
add_action( 'init', 'aqualuxe_remove_header_meta' );

/**
 * Disable self pingbacks.
 *
 * @param array $links The pingback links.
 * @return array Filtered pingback links.
 */
function aqualuxe_disable_self_pingbacks( $links ) {
	// Check if self pingback disabling is enabled in theme options.
	$disable_self_pingbacks = get_theme_mod( 'aqualuxe_disable_self_pingbacks', true );
	
	if ( ! $disable_self_pingbacks ) {
		return $links;
	}
	
	foreach ( $links as $l => $link ) {
		if ( 0 === strpos( $link, get_option( 'home' ) ) ) {
			unset( $links[ $l ] );
		}
	}
	
	return $links;
}
add_filter( 'pre_ping', 'aqualuxe_disable_self_pingbacks' );

/**
 * Optimize WooCommerce scripts.
 */
function aqualuxe_optimize_woocommerce_scripts() {
	// Check if WooCommerce script optimization is enabled in theme options.
	$optimize_woocommerce = get_theme_mod( 'aqualuxe_optimize_woocommerce', true );
	
	if ( ! $optimize_woocommerce || ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	// Remove WooCommerce scripts and styles from non-WooCommerce pages.
	if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {
		wp_dequeue_style( 'woocommerce-general' );
		wp_dequeue_style( 'woocommerce-layout' );
		wp_dequeue_style( 'woocommerce-smallscreen' );
		wp_dequeue_style( 'woocommerce_frontend_styles' );
		wp_dequeue_style( 'woocommerce_fancybox_styles' );
		wp_dequeue_style( 'woocommerce_chosen_styles' );
		wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
		wp_dequeue_script( 'wc_price_slider' );
		wp_dequeue_script( 'wc-single-product' );
		wp_dequeue_script( 'wc-add-to-cart' );
		wp_dequeue_script( 'wc-cart-fragments' );
		wp_dequeue_script( 'wc-checkout' );
		wp_dequeue_script( 'wc-add-to-cart-variation' );
		wp_dequeue_script( 'wc-single-product' );
		wp_dequeue_script( 'wc-cart' );
		wp_dequeue_script( 'wc-chosen' );
		wp_dequeue_script( 'woocommerce' );
		wp_dequeue_script( 'prettyPhoto' );
		wp_dequeue_script( 'prettyPhoto-init' );
		wp_dequeue_script( 'jquery-blockui' );
		wp_dequeue_script( 'jquery-placeholder' );
		wp_dequeue_script( 'fancybox' );
		wp_dequeue_script( 'jqueryui' );
	}
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_optimize_woocommerce_scripts', 99 );

/**
 * Optimize CSS delivery.
 */
function aqualuxe_optimize_css_delivery() {
	// Check if critical CSS is enabled in theme options.
	$enable_critical_css = get_theme_mod( 'aqualuxe_enable_critical_css', false );
	
	if ( ! $enable_critical_css ) {
		return;
	}
	
	// Get the critical CSS.
	$critical_css = get_theme_mod( 'aqualuxe_critical_css', '' );
	
	if ( empty( $critical_css ) ) {
		return;
	}
	
	// Output the critical CSS inline.
	echo '<style id="aqualuxe-critical-css">' . wp_strip_all_tags( $critical_css ) . '</style>';
	
	// Load the rest of the CSS asynchronously.
	add_filter( 'style_loader_tag', 'aqualuxe_async_css', 10, 4 );
}
add_action( 'wp_head', 'aqualuxe_optimize_css_delivery', 1 );

/**
 * Load CSS asynchronously.
 *
 * @param string $html The link tag for the enqueued style.
 * @param string $handle The style's registered handle.
 * @param string $href The stylesheet's source URL.
 * @param string $media The stylesheet's media attribute.
 * @return string The modified link tag.
 */
function aqualuxe_async_css( $html, $handle, $href, $media ) {
	// Don't async load critical CSS.
	if ( 'aqualuxe-critical-css' === $handle ) {
		return $html;
	}
	
	// Don't async load admin styles.
	if ( is_admin() ) {
		return $html;
	}
	
	// Don't async load print styles.
	if ( 'print' === $media ) {
		return $html;
	}
	
	// Create the async CSS HTML.
	$html = '<link rel="preload" id="' . esc_attr( $handle ) . '-css" href="' . esc_url( $href ) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
	$html .= '<noscript><link rel="stylesheet" id="' . esc_attr( $handle ) . '-css" href="' . esc_url( $href ) . '"></noscript>';
	
	return $html;
}

/**
 * Add preconnect for external resources.
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array URLs to print for resource hints.
 */
function aqualuxe_resource_hints( $urls, $relation_type ) {
	// Check if resource hints are enabled in theme options.
	$enable_resource_hints = get_theme_mod( 'aqualuxe_enable_resource_hints', true );
	
	if ( ! $enable_resource_hints ) {
		return $urls;
	}
	
	if ( 'preconnect' === $relation_type ) {
		// Add Google Fonts domain.
		$urls[] = array(
			'href' => 'https://fonts.googleapis.com',
		);
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
		
		// Add Google Analytics domain.
		if ( get_theme_mod( 'aqualuxe_google_analytics_id', '' ) ) {
			$urls[] = array(
				'href' => 'https://www.google-analytics.com',
			);
		}
	}
	
	return $urls;
}
add_filter( 'wp_resource_hints', 'aqualuxe_resource_hints', 10, 2 );

/**
 * Lazy load images.
 *
 * @param string $content The content to be filtered.
 * @return string The filtered content.
 */
function aqualuxe_lazy_load_images( $content ) {
	// Check if lazy loading is enabled in theme options.
	$enable_lazy_loading = get_theme_mod( 'aqualuxe_enable_lazy_loading', true );
	
	if ( ! $enable_lazy_loading ) {
		return $content;
	}
	
	// Don't lazy load if the content already contains lazy loading attributes.
	if ( strpos( $content, 'loading="lazy"' ) !== false ) {
		return $content;
	}
	
	// Don't lazy load in admin or feeds.
	if ( is_admin() || is_feed() ) {
		return $content;
	}
	
	// Replace image tags with lazy loading attributes.
	$content = preg_replace_callback( '/<img([^>]+)>/i', 'aqualuxe_lazy_load_image_callback', $content );
	
	return $content;
}
add_filter( 'the_content', 'aqualuxe_lazy_load_images', 99 );
add_filter( 'post_thumbnail_html', 'aqualuxe_lazy_load_images', 99 );
add_filter( 'get_avatar', 'aqualuxe_lazy_load_images', 99 );

/**
 * Callback function for lazy loading images.
 *
 * @param array $matches The regex matches.
 * @return string The modified image tag.
 */
function aqualuxe_lazy_load_image_callback( $matches ) {
	$image_tag = $matches[0];
	$image_attributes = $matches[1];
	
	// Don't lazy load images that already have loading attribute.
	if ( strpos( $image_attributes, 'loading=' ) !== false ) {
		return $image_tag;
	}
	
	// Don't lazy load images with the no-lazy class.
	if ( strpos( $image_attributes, 'class="no-lazy' ) !== false || strpos( $image_attributes, 'class=".*?no-lazy' ) !== false ) {
		return $image_tag;
	}
	
	// Add the loading attribute.
	$image_tag = str_replace( '<img', '<img loading="lazy"', $image_tag );
	
	return $image_tag;
}

/**
 * Defer parsing of JavaScript.
 *
 * @param string $tag The script tag.
 * @param string $handle The script handle.
 * @return string The modified script tag.
 */
function aqualuxe_defer_parsing_of_js( $tag, $handle ) {
	// Check if JavaScript deferring is enabled in theme options.
	$defer_js = get_theme_mod( 'aqualuxe_defer_js', true );
	
	if ( ! $defer_js ) {
		return $tag;
	}
	
	// Don't defer inline scripts.
	if ( strpos( $tag, 'src=' ) === false ) {
		return $tag;
	}
	
	// Don't defer scripts that already have defer or async attribute.
	if ( strpos( $tag, 'defer' ) !== false || strpos( $tag, 'async' ) !== false ) {
		return $tag;
	}
	
	// Don't defer specific scripts.
	$scripts_to_exclude = array( 'jquery', 'jquery-core', 'jquery-migrate' );
	
	if ( in_array( $handle, $scripts_to_exclude, true ) ) {
		return $tag;
	}
	
	// Add the defer attribute.
	$tag = str_replace( ' src', ' defer src', $tag );
	
	return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_defer_parsing_of_js', 10, 2 );

/**
 * Add browser caching headers.
 */
function aqualuxe_add_browser_caching_headers() {
	// Check if browser caching is enabled in theme options.
	$enable_browser_caching = get_theme_mod( 'aqualuxe_enable_browser_caching', false );
	
	if ( ! $enable_browser_caching ) {
		return;
	}
	
	// Check if we can modify headers.
	if ( ! function_exists( 'header_remove' ) || ! function_exists( 'header' ) ) {
		return;
	}
	
	// Get the file extension.
	$file_extension = pathinfo( $_SERVER['REQUEST_URI'], PATHINFO_EXTENSION );
	
	// Set cache time based on file type.
	$cache_time = 0;
	
	switch ( $file_extension ) {
		case 'css':
		case 'js':
		case 'jpg':
		case 'jpeg':
		case 'png':
		case 'gif':
		case 'webp':
		case 'svg':
		case 'ico':
		case 'woff':
		case 'woff2':
		case 'ttf':
		case 'eot':
			$cache_time = 31536000; // 1 year.
			break;
		default:
			$cache_time = 86400; // 1 day.
			break;
	}
	
	// Don't cache if cache time is 0.
	if ( 0 === $cache_time ) {
		return;
	}
	
	// Remove existing cache headers.
	header_remove( 'Pragma' );
	header_remove( 'Expires' );
	header_remove( 'Cache-Control' );
	
	// Add cache headers.
	header( 'Cache-Control: public, max-age=' . $cache_time );
	header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $cache_time ) . ' GMT' );
}
add_action( 'send_headers', 'aqualuxe_add_browser_caching_headers' );

/**
 * Add DNS prefetch for external resources.
 */
function aqualuxe_dns_prefetch() {
	// Check if DNS prefetch is enabled in theme options.
	$enable_dns_prefetch = get_theme_mod( 'aqualuxe_enable_dns_prefetch', true );
	
	if ( ! $enable_dns_prefetch ) {
		return;
	}
	
	// Add DNS prefetch for Google Fonts.
	echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
	echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
	
	// Add DNS prefetch for Google Analytics.
	if ( get_theme_mod( 'aqualuxe_google_analytics_id', '' ) ) {
		echo '<link rel="dns-prefetch" href="//www.google-analytics.com">' . "\n";
	}
	
	// Add DNS prefetch for Gravatar.
	if ( is_singular() && comments_open() ) {
		echo '<link rel="dns-prefetch" href="//secure.gravatar.com">' . "\n";
	}
}
add_action( 'wp_head', 'aqualuxe_dns_prefetch', 0 );

/**
 * Minify HTML output.
 *
 * @param string $buffer The HTML output.
 * @return string The minified HTML output.
 */
function aqualuxe_minify_html( $buffer ) {
	// Check if HTML minification is enabled in theme options.
	$minify_html = get_theme_mod( 'aqualuxe_minify_html', false );
	
	if ( ! $minify_html ) {
		return $buffer;
	}
	
	// Don't minify in admin or if the page is an XML page.
	if ( is_admin() || strpos( $buffer, '<?xml' ) !== false ) {
		return $buffer;
	}
	
	// Remove comments (except IE conditional comments).
	$buffer = preg_replace( '/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $buffer );
	
	// Remove whitespace.
	$buffer = preg_replace( '/\s+/', ' ', $buffer );
	$buffer = preg_replace( '/\s*(<\/?(div|p|h[1-6]|section|article|aside|header|footer|nav|main|figure|figcaption|picture|source|img|a|span|strong|em|b|i|u|s|small|big|code|pre|blockquote|q|cite|ul|ol|li|dl|dt|dd|table|thead|tbody|tfoot|tr|th|td|form|input|select|option|textarea|button|label|fieldset|legend)[^>]*>)\s*/i', '$1', $buffer );
	$buffer = preg_replace( '/\s*(<br\s*\/?>)\s*/i', '$1', $buffer );
	$buffer = preg_replace( '/\s*(<hr\s*\/?>)\s*/i', '$1', $buffer );
	
	return $buffer;
}

/**
 * Start output buffering for HTML minification.
 */
function aqualuxe_start_html_minification() {
	// Check if HTML minification is enabled in theme options.
	$minify_html = get_theme_mod( 'aqualuxe_minify_html', false );
	
	if ( ! $minify_html ) {
		return;
	}
	
	// Don't minify in admin or if the page is an XML page.
	if ( is_admin() ) {
		return;
	}
	
	ob_start( 'aqualuxe_minify_html' );
}
add_action( 'template_redirect', 'aqualuxe_start_html_minification', 1 );

/**
 * Add performance settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_performance_customizer( $wp_customize ) {
	// Add performance section.
	$wp_customize->add_section( 'aqualuxe_performance', array(
		'title'    => __( 'Performance', 'aqualuxe' ),
		'priority' => 200,
	) );
	
	// Disable emojis.
	$wp_customize->add_setting( 'aqualuxe_disable_emojis', array(
		'default'           => true,
		'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
	) );
	
	$wp_customize->add_control( 'aqualuxe_disable_emojis', array(
		'label'    => __( 'Disable Emojis', 'aqualuxe' ),
		'section'  => 'aqualuxe_performance',
		'type'     => 'checkbox',
		'priority' => 10,
	) );
	
	// Disable embeds.
	$wp_customize->add_setting( 'aqualuxe_disable_embeds', array(
		'default'           => true,
		'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
	) );
	
	$wp_customize->add_control( 'aqualuxe_disable_embeds', array(
		'label'    => __( 'Disable Embeds', 'aqualuxe' ),
		'section'  => 'aqualuxe_performance',
		'type'     => 'checkbox',
		'priority' => 20,
	) );
	
	// Remove query strings.
	$wp_customize->add_setting( 'aqualuxe_remove_query_strings', array(
		'default'           => true,
		'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
	) );
	
	$wp_customize->add_control( 'aqualuxe_remove_query_strings', array(
		'label'    => __( 'Remove Query Strings', 'aqualuxe' ),
		'section'  => 'aqualuxe_performance',
		'type'     => 'checkbox',
		'priority' => 30,
	) );
	
	// Disable XML-RPC.
	$wp_customize->add_setting( 'aqualuxe_disable_xmlrpc', array(
		'default'           => true,
		'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
	) );
	
	$wp_customize->add_control( 'aqualuxe_disable_xmlrpc', array(
		'label'    => __( 'Disable XML-RPC', 'aqualuxe' ),
		'section'  => 'aqualuxe_performance',
		'type'     => 'checkbox',
		'priority' => 40,
	) );
	
	// Remove header meta.
	$wp_customize->add_setting( 'aqualuxe_remove_header_meta', array(
		'default'           => true,
		'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
	) );
	
	$wp_customize->add_control( 'aqualuxe_remove_header_meta', array(
		'label'    => __( 'Remove Header Meta', 'aqualuxe' ),
		'section'  => 'aqualuxe_performance',
		'type'     => 'checkbox',
		'priority' => 50,
	) );
	
	// Disable self pingbacks.
	$wp_customize->add_setting( 'aqualuxe_disable_self_pingbacks', array(
		'default'           => true,
		'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
	) );
	
	$wp_customize->add_control( 'aqualuxe_disable_self_pingbacks', array(
		'label'    => __( 'Disable Self Pingbacks', 'aqualuxe' ),
		'section'  => 'aqualuxe_performance',
		'type'     => 'checkbox',
		'priority' => 60,
	) );
	
	// Optimize WooCommerce scripts.
	$wp_customize->add_setting( 'aqualuxe_optimize_woocommerce', array(
		'default'           => true,
		'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
	) );
	
	$wp_customize->add_control( 'aqualuxe_optimize_woocommerce', array(
		'label'    => __( 'Optimize WooCommerce Scripts', 'aqualuxe' ),
		'section'  => 'aqualuxe_performance',
		'type'     => 'checkbox',
		'priority' => 70,
	) );
	
	// Enable critical CSS.
	$wp_customize->add_setting( 'aqualuxe_enable_critical_css', array(
		'default'           => false,
		'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
	) );
	
	$wp_customize->add_control( 'aqualuxe_enable_critical_css', array(
		'label'    => __( 'Enable Critical CSS', 'aqualuxe' ),
		'section'  => 'aqualuxe_performance',
		'type'     => 'checkbox',
		'priority' => 80,
	) );
	
	// Critical CSS.
	$wp_customize->add_setting( 'aqualuxe_critical_css', array(
		'default'           => '',
		'sanitize_callback' => 'wp_strip_all_tags',
	) );
	
	$wp_customize->add_control( 'aqualuxe_critical_css', array(
		'label'       => __( 'Critical CSS', 'aqualuxe' ),
		'description' => __( 'Add critical CSS that will be loaded inline in the head.', 'aqualuxe' ),
		'section'     => 'aqualuxe_performance',
		'type'        => 'textarea',
		'priority'    => 90,
	) );
	
	// Enable resource hints.
	$wp_customize->add_setting( 'aqualuxe_enable_resource_hints', array(
		'default'           => true,
		'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
	) );
	
	$wp_customize->add_control( 'aqualuxe_enable_resource_hints', array(
		'label'    => __( 'Enable Resource Hints', 'aqualuxe' ),
		'section'  => 'aqualuxe_performance',
		'type'     => 'checkbox',
		'priority' => 100,
	) );
	
	// Enable lazy loading.
	$wp_customize->add_setting( 'aqualuxe_enable_lazy_loading', array(
		'default'           => true,
		'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
	) );
	
	$wp_customize->add_control( 'aqualuxe_enable_lazy_loading', array(
		'label'    => __( 'Enable Lazy Loading', 'aqualuxe' ),
		'section'  => 'aqualuxe_performance',
		'type'     => 'checkbox',
		'priority' => 110,
	) );
	
	// Defer JavaScript.
	$wp_customize->add_setting( 'aqualuxe_defer_js', array(
		'default'           => true,
		'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
	) );
	
	$wp_customize->add_control( 'aqualuxe_defer_js', array(
		'label'    => __( 'Defer JavaScript', 'aqualuxe' ),
		'section'  => 'aqualuxe_performance',
		'type'     => 'checkbox',
		'priority' => 120,
	) );
	
	// Enable browser caching.
	$wp_customize->add_setting( 'aqualuxe_enable_browser_caching', array(
		'default'           => false,
		'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
	) );
	
	$wp_customize->add_control( 'aqualuxe_enable_browser_caching', array(
		'label'       => __( 'Enable Browser Caching', 'aqualuxe' ),
		'description' => __( 'This may not work on all servers. Use with caution.', 'aqualuxe' ),
		'section'     => 'aqualuxe_performance',
		'type'        => 'checkbox',
		'priority'    => 130,
	) );
	
	// Enable DNS prefetch.
	$wp_customize->add_setting( 'aqualuxe_enable_dns_prefetch', array(
		'default'           => true,
		'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
	) );
	
	$wp_customize->add_control( 'aqualuxe_enable_dns_prefetch', array(
		'label'    => __( 'Enable DNS Prefetch', 'aqualuxe' ),
		'section'  => 'aqualuxe_performance',
		'type'     => 'checkbox',
		'priority' => 140,
	) );
	
	// Minify HTML.
	$wp_customize->add_setting( 'aqualuxe_minify_html', array(
		'default'           => false,
		'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
	) );
	
	$wp_customize->add_control( 'aqualuxe_minify_html', array(
		'label'       => __( 'Minify HTML', 'aqualuxe' ),
		'description' => __( 'This may cause issues with some plugins. Use with caution.', 'aqualuxe' ),
		'section'     => 'aqualuxe_performance',
		'type'        => 'checkbox',
		'priority'    => 150,
	) );
}
add_action( 'customize_register', 'aqualuxe_performance_customizer' );

/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function aqualuxe_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true === $checked ) ? true : false );
}