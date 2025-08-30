<?php
/**
 * AquaLuxe SEO Functions
 *
 * @package AquaLuxe
 */

/**
 * Add meta tags for SEO
 */
function aqualuxe_add_meta_tags() {
	// Add viewport meta tag
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
	
	// Add Open Graph meta tags
	aqualuxe_add_open_graph_tags();
	
	// Add Twitter Card meta tags
	aqualuxe_add_twitter_cards();
	
	// Add schema markup
	aqualuxe_add_schema_markup();
}
add_action( 'wp_head', 'aqualuxe_add_meta_tags' );

/**
 * Add Open Graph meta tags
 */
function aqualuxe_add_open_graph_tags() {
	// Get current page type
	if ( is_front_page() || is_home() ) {
		$type = 'website';
	} elseif ( is_single() || is_page() ) {
		$type = 'article';
	} else {
		$type = 'website';
	}
	
	// Output Open Graph tags
	echo '<meta property="og:type" content="' . esc_attr( $type ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( wp_get_document_title() ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( home_url( $_SERVER['REQUEST_URI'] ) ) . '">' . "\n";
	
	if ( is_single() || is_page() ) {
		global $post;
		if ( has_post_thumbnail( $post->ID ) ) {
			$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			echo '<meta property="og:image" content="' . esc_url( $thumbnail[0] ) . '">' . "\n";
		}
		
		if ( ! empty( $post->post_excerpt ) ) {
			$description = wp_strip_all_tags( $post->post_excerpt );
		} else {
			$description = wp_trim_words( wp_strip_all_tags( $post->post_content ), 30 );
		}
		echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	} else {
		echo '<meta property="og:description" content="' . esc_attr( get_bloginfo( 'description' ) ) . '">' . "\n";
	}
	
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
}

/**
 * Add Twitter Card meta tags
 */
function aqualuxe_add_twitter_cards() {
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( wp_get_document_title() ) . '">' . "\n";
	
	if ( is_single() || is_page() ) {
		global $post;
		if ( ! empty( $post->post_excerpt ) ) {
			$description = wp_strip_all_tags( $post->post_excerpt );
		} else {
			$description = wp_trim_words( wp_strip_all_tags( $post->post_content ), 30 );
		}
		echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
		
		if ( has_post_thumbnail( $post->ID ) ) {
			$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			echo '<meta name="twitter:image" content="' . esc_url( $thumbnail[0] ) . '">' . "\n";
		}
	} else {
		echo '<meta name="twitter:description" content="' . esc_attr( get_bloginfo( 'description' ) ) . '">' . "\n";
	}
	
	$twitter_site = get_option( 'aqualuxe_twitter_handle', '' );
	if ( ! empty( $twitter_site ) ) {
		echo '<meta name="twitter:site" content="@' . esc_attr( $twitter_site ) . '">' . "\n";
	}
}

/**
 * Add schema markup
 */
function aqualuxe_add_schema_markup() {
	// Website schema
	if ( is_front_page() || is_home() ) {
		echo '<script type="application/ld+json">' . "\n";
		echo '{' . "\n";
		echo '  "@context": "https://schema.org",' . "\n";
		echo '  "@type": "WebSite",' . "\n";
		echo '  "name": "' . esc_js( get_bloginfo( 'name' ) ) . '",' . "\n";
		echo '  "url": "' . esc_url( home_url() ) . '"' . "\n";
		echo '}' . "\n";
		echo '</script>' . "\n";
	}
	
	// Organization schema
	echo '<script type="application/ld+json">' . "\n";
	echo '{' . "\n";
	echo '  "@context": "https://schema.org",' . "\n";
	echo '  "@type": "Organization",' . "\n";
	echo '  "name": "' . esc_js( get_bloginfo( 'name' ) ) . '",' . "\n";
	echo '  "url": "' . esc_url( home_url() ) . '",' . "\n";
	echo '  "logo": "' . esc_url( get_theme_mod( 'aqualuxe_logo', '' ) ) . '"' . "\n";
	echo '}' . "\n";
	echo '</script>' . "\n";
	
	// Product schema for WooCommerce products
	if ( is_product() && class_exists( 'WooCommerce' ) ) {
		global $product;
		if ( $product ) {
			echo '<script type="application/ld+json">' . "\n";
			echo '{' . "\n";
			echo '  "@context": "https://schema.org",' . "\n";
			echo '  "@type": "Product",' . "\n";
			echo '  "name": "' . esc_js( $product->get_name() ) . '",' . "\n";
			echo '  "description": "' . esc_js( $product->get_short_description() ) . '",' . "\n";
			echo '  "image": "' . esc_url( wp_get_attachment_url( $product->get_image_id() ) ) . '",' . "\n";
			echo '  "offers": {' . "\n";
			echo '    "@type": "Offer",' . "\n";
			echo '    "price": "' . esc_js( $product->get_price() ) . '",' . "\n";
			echo '    "priceCurrency": "' . esc_js( get_woocommerce_currency() ) . '",' . "\n";
			echo '    "availability": "https://schema.org/' . ( $product->is_in_stock() ? 'InStock' : 'OutOfStock' ) . '"' . "\n";
			echo '  }' . "\n";
			echo '}' . "\n";
			echo '</script>' . "\n";
		}
	}
}

/**
 * Add performance optimizations
 */
function aqualuxe_performance_optimizations() {
	// Add preload for critical resources
	if ( is_front_page() ) {
		$logo = get_theme_mod( 'aqualuxe_logo', '' );
		if ( ! empty( $logo ) ) {
			echo '<link rel="preload" href="' . esc_url( $logo ) . '" as="image">' . "\n";
		}
	}
}
add_action( 'wp_head', 'aqualuxe_performance_optimizations', 1 );

/**
 * Add lazy loading to images
 */
function aqualuxe_add_lazy_loading( $content ) {
	if ( is_admin() ) {
		return $content;
	}
	
	// Add loading="lazy" attribute to images
	return preg_replace( '/<img(.*?)(loading="lazy")?(.*?)>/i', '<img$1 loading="lazy" $3>', $content );
}
add_filter( 'the_content', 'aqualuxe_add_lazy_loading' );
add_filter( 'post_thumbnail_html', 'aqualuxe_add_lazy_loading' );
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'aqualuxe_add_lazy_loading' );

/**
 * Minify HTML output
 */
function aqualuxe_minify_html( $html ) {
	if ( is_admin() || is_feed() || defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return $html;
	}
	
	// Remove HTML comments
	$html = preg_replace( '/<!--(.|s)*?-->/', '', $html );
	
	// Remove whitespace
	$search = array(
		'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
		'/[^\S ]+\</s',  // strip whitespaces before tags, except space
		'/(\s)+/s'       // shorten multiple whitespace sequences
	);
	
	$replace = array(
		'>',
		'<',
		'\\1'
	);
	
	$html = preg_replace( $search, $replace, $html );
	
	return $html;
}
// Uncomment the line below to enable HTML minification
// add_filter( 'wp_footer', 'aqualuxe_minify_html_output' );

/**
 * Minify HTML output buffer
 */
function aqualuxe_minify_html_output() {
	ob_start( 'aqualuxe_minify_html' );
}
// Uncomment the line below to enable HTML minification
// add_action( 'template_redirect', 'aqualuxe_minify_html_output' );