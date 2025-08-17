<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

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
	
	// Add a class if WooCommerce is active.
	if ( class_exists( 'WooCommerce' ) ) {
		$classes[] = 'woocommerce-active';
	} else {
		$classes[] = 'woocommerce-inactive';
	}
	
	// Add a class for the dark mode.
	if ( isset( $_COOKIE['darkMode'] ) && 'true' === $_COOKIE['darkMode'] ) {
		$classes[] = 'dark-mode';
	}
	
	// Add a class for the current language.
	if ( isset( $_COOKIE['aqualuxe_language'] ) ) {
		$classes[] = 'lang-' . sanitize_html_class( $_COOKIE['aqualuxe_language'] );
	}
	
	// Add a class for the current currency.
	if ( isset( $_COOKIE['aqualuxe_currency'] ) ) {
		$classes[] = 'currency-' . sanitize_html_class( $_COOKIE['aqualuxe_currency'] );
	}
	
	// Add a class for the header layout.
	$header_layout = get_theme_mod( 'aqualuxe_header_layout', 'default' );
	$classes[] = 'header-layout-' . sanitize_html_class( $header_layout );
	
	// Add a class for the footer layout.
	$footer_layout = get_theme_mod( 'aqualuxe_footer_layout', 'default' );
	$classes[] = 'footer-layout-' . sanitize_html_class( $footer_layout );
	
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
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function aqualuxe_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'aqualuxe-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'aqualuxe_resource_hints', 10, 2 );

/**
 * Add custom image sizes.
 */
function aqualuxe_add_image_sizes() {
	add_image_size( 'aqualuxe-featured', 1200, 675, true );
	add_image_size( 'aqualuxe-blog', 800, 450, true );
	add_image_size( 'aqualuxe-product', 600, 600, true );
	add_image_size( 'aqualuxe-thumbnail', 300, 300, true );
}
add_action( 'after_setup_theme', 'aqualuxe_add_image_sizes' );

/**
 * Add custom image sizes to the media library.
 *
 * @param array $sizes Image sizes.
 * @return array
 */
function aqualuxe_custom_image_sizes( $sizes ) {
	return array_merge( $sizes, array(
		'aqualuxe-featured'  => __( 'Featured Image', 'aqualuxe' ),
		'aqualuxe-blog'      => __( 'Blog Image', 'aqualuxe' ),
		'aqualuxe-product'   => __( 'Product Image', 'aqualuxe' ),
		'aqualuxe-thumbnail' => __( 'Thumbnail', 'aqualuxe' ),
	) );
}
add_filter( 'image_size_names_choose', 'aqualuxe_custom_image_sizes' );

/**
 * Modify the excerpt length.
 *
 * @param int $length Excerpt length.
 * @return int
 */
function aqualuxe_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length', 999 );

/**
 * Modify the excerpt more string.
 *
 * @param string $more The excerpt more string.
 * @return string
 */
function aqualuxe_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Add schema markup to the body element.
 *
 * @param array $attr Array of attributes.
 * @return array
 */
function aqualuxe_body_schema( $attr ) {
	$schema = 'https://schema.org/';
	
	// Check what type of page we are on.
	if ( is_home() || is_archive() || is_attachment() || is_tax() || is_single() ) {
		$type = 'Blog';
	} elseif ( is_search() ) {
		$type = 'SearchResultsPage';
	} elseif ( is_front_page() ) {
		$type = 'WebPage';
	} else {
		$type = 'WebPage';
	}
	
	// Apply the schema.
	$attr['itemscope'] = '';
	$attr['itemtype']  = $schema . $type;
	
	return $attr;
}
add_filter( 'aqualuxe_body_attributes', 'aqualuxe_body_schema' );

/**
 * Add schema markup to the article element.
 *
 * @param array $attr Array of attributes.
 * @return array
 */
function aqualuxe_article_schema( $attr ) {
	$schema = 'https://schema.org/';
	
	if ( is_single() ) {
		$type = 'BlogPosting';
	} else {
		$type = 'Article';
	}
	
	$attr['itemscope'] = '';
	$attr['itemtype']  = $schema . $type;
	
	return $attr;
}
add_filter( 'aqualuxe_article_attributes', 'aqualuxe_article_schema' );

/**
 * Add Open Graph meta tags.
 */
function aqualuxe_add_opengraph_tags() {
	global $post;
	
	if ( is_single() || is_page() ) {
		if ( has_post_thumbnail( $post->ID ) ) {
			$img_src = get_the_post_thumbnail_url( $post->ID, 'large' );
		} else {
			$img_src = get_theme_mod( 'aqualuxe_default_opengraph_image', get_template_directory_uri() . '/assets/dist/images/default-opengraph.jpg' );
		}
		
		$excerpt = $post->post_excerpt;
		if ( empty( $excerpt ) ) {
			$excerpt = wp_trim_words( $post->post_content, 55, '...' );
		}
		
		?>
		<meta property="og:title" content="<?php echo esc_attr( get_the_title() ); ?>" />
		<meta property="og:description" content="<?php echo esc_attr( $excerpt ); ?>" />
		<meta property="og:type" content="<?php echo is_single() ? 'article' : 'website'; ?>" />
		<meta property="og:url" content="<?php echo esc_url( get_permalink() ); ?>" />
		<meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
		<meta property="og:image" content="<?php echo esc_url( $img_src ); ?>" />
		
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:title" content="<?php echo esc_attr( get_the_title() ); ?>" />
		<meta name="twitter:description" content="<?php echo esc_attr( $excerpt ); ?>" />
		<meta name="twitter:image" content="<?php echo esc_url( $img_src ); ?>" />
		<?php
	}
}
add_action( 'wp_head', 'aqualuxe_add_opengraph_tags' );

/**
 * Add custom attributes to the body element.
 */
function aqualuxe_body_attributes() {
	$attributes = array();
	$attributes = apply_filters( 'aqualuxe_body_attributes', $attributes );
	
	$output = '';
	foreach ( $attributes as $name => $value ) {
		if ( $value ) {
			$output .= sprintf( ' %s="%s"', esc_attr( $name ), esc_attr( $value ) );
		} else {
			$output .= sprintf( ' %s', esc_attr( $name ) );
		}
	}
	
	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Add custom attributes to the article element.
 */
function aqualuxe_article_attributes() {
	$attributes = array();
	$attributes = apply_filters( 'aqualuxe_article_attributes', $attributes );
	
	$output = '';
	foreach ( $attributes as $name => $value ) {
		if ( $value ) {
			$output .= sprintf( ' %s="%s"', esc_attr( $name ), esc_attr( $value ) );
		} else {
			$output .= sprintf( ' %s', esc_attr( $name ) );
		}
	}
	
	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Add async/defer attributes to enqueued scripts.
 *
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string
 */
function aqualuxe_script_loader_tag( $tag, $handle ) {
	$scripts_to_defer = array( 'aqualuxe-navigation', 'aqualuxe-skip-link-focus-fix' );
	$scripts_to_async = array( 'aqualuxe-google-maps' );
	
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
 * Add custom classes to the navigation menus.
 *
 * @param array $classes Array of classes.
 * @param object $item    Menu item object.
 * @param object $args    Menu arguments.
 * @return array
 */
function aqualuxe_nav_menu_css_class( $classes, $item, $args ) {
	if ( 'primary' === $args->theme_location ) {
		$classes[] = 'nav-item';
		
		if ( in_array( 'menu-item-has-children', $classes, true ) ) {
			$classes[] = 'has-dropdown';
		}
	}
	
	return $classes;
}
add_filter( 'nav_menu_css_class', 'aqualuxe_nav_menu_css_class', 10, 3 );

/**
 * Add custom classes to the navigation menu links.
 *
 * @param array $atts Array of attributes.
 * @param object $item Menu item object.
 * @param object $args Menu arguments.
 * @return array
 */
function aqualuxe_nav_menu_link_attributes( $atts, $item, $args ) {
	if ( 'primary' === $args->theme_location ) {
		$atts['class'] = 'nav-link';
		
		if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
			$atts['class'] .= ' dropdown-toggle';
			$atts['aria-expanded'] = 'false';
			$atts['aria-haspopup'] = 'true';
		}
	}
	
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'aqualuxe_nav_menu_link_attributes', 10, 3 );

/**
 * Add custom classes to the navigation menu items.
 *
 * @param string $item_output The menu item's HTML output.
 * @param object $item        Menu item data object.
 * @param int    $depth       Depth of menu item.
 * @param object $args        Menu arguments.
 * @return string
 */
function aqualuxe_nav_menu_item_output( $item_output, $item, $depth, $args ) {
	if ( 'primary' === $args->theme_location && in_array( 'menu-item-has-children', $item->classes, true ) ) {
		$item_output .= '<button class="dropdown-toggle-icon" aria-label="' . esc_attr__( 'Toggle dropdown menu', 'aqualuxe' ) . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg></button>';
	}
	
	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'aqualuxe_nav_menu_item_output', 10, 4 );

/**
 * Add SVG support to media uploader.
 *
 * @param array $mimes Allowed mime types.
 * @return array
 */
function aqualuxe_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'aqualuxe_mime_types' );

/**
 * Fix SVG dimensions in media uploader.
 *
 * @param array $response Response data.
 * @param object $attachment Attachment object.
 * @param array $meta Attachment meta.
 * @return array
 */
function aqualuxe_fix_svg_size_attributes( $response, $attachment, $meta ) {
	if ( $response['mime'] === 'image/svg+xml' && empty( $response['sizes'] ) ) {
		$svg_path = get_attached_file( $attachment->ID );
		
		if ( ! file_exists( $svg_path ) ) {
			return $response;
		}
		
		$svg = simplexml_load_file( $svg_path );
		$attributes = $svg->attributes();
		$viewbox = explode( ' ', $attributes->viewBox );
		
		$response['sizes'] = array(
			'full' => array(
				'url'         => $response['url'],
				'width'       => isset( $attributes->width ) ? (int) $attributes->width : (count( $viewbox ) === 4 ? (int) $viewbox[2] : null),
				'height'      => isset( $attributes->height ) ? (int) $attributes->height : (count( $viewbox ) === 4 ? (int) $viewbox[3] : null),
				'orientation' => 'portrait',
			),
		);
	}
	
	return $response;
}
add_filter( 'wp_prepare_attachment_for_js', 'aqualuxe_fix_svg_size_attributes', 10, 3 );

/**
 * Add responsive container to embeds.
 *
 * @param string $html Embed HTML.
 * @return string
 */
function aqualuxe_responsive_embeds( $html ) {
	return '<div class="responsive-embed">' . $html . '</div>';
}
add_filter( 'embed_oembed_html', 'aqualuxe_responsive_embeds', 10, 1 );

/**
 * Add custom styles to the WordPress login page.
 */
function aqualuxe_login_styles() {
	wp_enqueue_style( 'aqualuxe-login', aqualuxe_asset( 'css/login.css' ), array(), AQUALUXE_VERSION );
}
add_action( 'login_enqueue_scripts', 'aqualuxe_login_styles' );

/**
 * Customize the login logo URL.
 *
 * @return string
 */
function aqualuxe_login_logo_url() {
	return home_url( '/' );
}
add_filter( 'login_headerurl', 'aqualuxe_login_logo_url' );

/**
 * Customize the login logo title.
 *
 * @return string
 */
function aqualuxe_login_logo_title() {
	return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'aqualuxe_login_logo_title' );

/**
 * Add custom dashboard widget.
 */
function aqualuxe_add_dashboard_widget() {
	wp_add_dashboard_widget(
		'aqualuxe_dashboard_widget',
		__( 'AquaLuxe Theme', 'aqualuxe' ),
		'aqualuxe_dashboard_widget_content'
	);
}
add_action( 'wp_dashboard_setup', 'aqualuxe_add_dashboard_widget' );

/**
 * Dashboard widget content.
 */
function aqualuxe_dashboard_widget_content() {
	?>
	<div class="aqualuxe-dashboard-widget">
		<div class="aqualuxe-dashboard-widget-header">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/logo.png' ); ?>" alt="<?php esc_attr_e( 'AquaLuxe', 'aqualuxe' ); ?>">
		</div>
		
		<div class="aqualuxe-dashboard-widget-content">
			<p><?php esc_html_e( 'Welcome to AquaLuxe! Here are some links to help you get started:', 'aqualuxe' ); ?></p>
			
			<ul>
				<li><a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Customize Theme', 'aqualuxe' ); ?></a></li>
				<li><a href="<?php echo esc_url( admin_url( 'themes.php?page=aqualuxe-demo-importer' ) ); ?>"><?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?></a></li>
				<li><a href="<?php echo esc_url( admin_url( 'themes.php?page=aqualuxe-documentation' ) ); ?>"><?php esc_html_e( 'Theme Documentation', 'aqualuxe' ); ?></a></li>
			</ul>
		</div>
	</div>
	<?php
}

/**
 * Add custom admin notice.
 */
function aqualuxe_admin_notice() {
	$screen = get_current_screen();
	
	if ( $screen->id === 'themes' || $screen->id === 'dashboard' ) {
		$dismissed = get_user_meta( get_current_user_id(), 'aqualuxe_notice_dismissed', true );
		
		if ( ! $dismissed ) {
			?>
			<div class="notice notice-info is-dismissible aqualuxe-notice">
				<h3><?php esc_html_e( 'Welcome to AquaLuxe!', 'aqualuxe' ); ?></h3>
				<p><?php esc_html_e( 'Thank you for choosing AquaLuxe. To get started, we recommend importing the demo content and customizing the theme.', 'aqualuxe' ); ?></p>
				<p>
					<a href="<?php echo esc_url( admin_url( 'themes.php?page=aqualuxe-demo-importer' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?></a>
					<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button"><?php esc_html_e( 'Customize Theme', 'aqualuxe' ); ?></a>
					<a href="<?php echo esc_url( admin_url( 'themes.php?page=aqualuxe-documentation' ) ); ?>" class="button"><?php esc_html_e( 'View Documentation', 'aqualuxe' ); ?></a>
				</p>
			</div>
			<script>
				jQuery(document).on('click', '.aqualuxe-notice .notice-dismiss', function() {
					jQuery.ajax({
						url: ajaxurl,
						data: {
							action: 'aqualuxe_dismiss_notice'
						}
					});
				});
			</script>
			<?php
		}
	}
}
add_action( 'admin_notices', 'aqualuxe_admin_notice' );

/**
 * Dismiss admin notice.
 */
function aqualuxe_dismiss_notice() {
	update_user_meta( get_current_user_id(), 'aqualuxe_notice_dismissed', true );
	wp_die();
}
add_action( 'wp_ajax_aqualuxe_dismiss_notice', 'aqualuxe_dismiss_notice' );

/**
 * Add custom admin footer text.
 *
 * @param string $text Footer text.
 * @return string
 */
function aqualuxe_admin_footer_text( $text ) {
	$screen = get_current_screen();
	
	if ( $screen && ( $screen->id === 'themes' || $screen->id === 'dashboard' ) ) {
		$text = sprintf(
			/* translators: %s: Theme name */
			__( 'Thank you for creating with <a href="%1$s" target="_blank">WordPress</a> and <a href="%2$s" target="_blank">%3$s</a>.', 'aqualuxe' ),
			'https://wordpress.org/',
			'https://aqualuxe.com/',
			'AquaLuxe'
		);
	}
	
	return $text;
}
add_filter( 'admin_footer_text', 'aqualuxe_admin_footer_text' );

/**
 * Add custom admin body classes.
 *
 * @param string $classes Classes for the body element.
 * @return string
 */
function aqualuxe_admin_body_class( $classes ) {
	$screen = get_current_screen();
	
	if ( $screen && ( $screen->id === 'themes' || $screen->id === 'dashboard' ) ) {
		$classes .= ' aqualuxe-admin';
	}
	
	return $classes;
}
add_filter( 'admin_body_class', 'aqualuxe_admin_body_class' );

/**
 * Add custom admin styles.
 */
function aqualuxe_admin_styles() {
	wp_enqueue_style( 'aqualuxe-admin', aqualuxe_asset( 'css/admin.css' ), array(), AQUALUXE_VERSION );
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_admin_styles' );

/**
 * Add custom admin scripts.
 */
function aqualuxe_admin_scripts() {
	wp_enqueue_script( 'aqualuxe-admin', aqualuxe_asset( 'js/admin.js' ), array( 'jquery' ), AQUALUXE_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_admin_scripts' );

/**
 * Add custom favicon.
 */
function aqualuxe_favicon() {
	$favicon = get_theme_mod( 'aqualuxe_favicon', get_template_directory_uri() . '/assets/dist/images/favicon.ico' );
	
	if ( $favicon ) {
		echo '<link rel="shortcut icon" href="' . esc_url( $favicon ) . '" />';
	}
}
add_action( 'wp_head', 'aqualuxe_favicon' );
add_action( 'admin_head', 'aqualuxe_favicon' );
add_action( 'login_head', 'aqualuxe_favicon' );

/**
 * Add custom meta tags.
 */
function aqualuxe_meta_tags() {
	?>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="format-detection" content="telephone=no">
	<meta name="theme-color" content="<?php echo esc_attr( get_theme_mod( 'aqualuxe_theme_color', '#0077B6' ) ); ?>">
	<?php
}
add_action( 'wp_head', 'aqualuxe_meta_tags', 0 );

/**
 * Add custom DNS prefetch.
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
 * Add custom preconnect.
 */
function aqualuxe_preconnect() {
	?>
	<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php
}
add_action( 'wp_head', 'aqualuxe_preconnect', 0 );

/**
 * Add custom fonts.
 */
function aqualuxe_fonts() {
	?>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&display=swap">
	<?php
}
add_action( 'wp_head', 'aqualuxe_fonts', 0 );

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
 * Add custom body attributes.
 */
function aqualuxe_add_body_attributes() {
	$attributes = array();
	
	// Add data attributes for JavaScript.
	$attributes['data-site-url'] = get_site_url();
	$attributes['data-theme-url'] = get_template_directory_uri();
	
	// Add RTL attribute.
	if ( is_rtl() ) {
		$attributes['dir'] = 'rtl';
	}
	
	// Apply filters.
	$attributes = apply_filters( 'aqualuxe_body_attributes', $attributes );
	
	// Output attributes.
	foreach ( $attributes as $name => $value ) {
		echo ' ' . esc_attr( $name ) . '="' . esc_attr( $value ) . '"';
	}
}
add_action( 'aqualuxe_body_attributes', 'aqualuxe_add_body_attributes' );

/**
 * Add custom HTML classes.
 */
function aqualuxe_html_classes() {
	$classes = array();
	
	// Add dark mode class.
	if ( isset( $_COOKIE['darkMode'] ) && 'true' === $_COOKIE['darkMode'] ) {
		$classes[] = 'dark-mode';
	}
	
	// Add RTL class.
	if ( is_rtl() ) {
		$classes[] = 'rtl';
	}
	
	// Apply filters.
	$classes = apply_filters( 'aqualuxe_html_classes', $classes );
	
	// Output classes.
	echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
}
add_action( 'aqualuxe_html_classes', 'aqualuxe_html_classes' );

/**
 * Add custom article attributes.
 */
function aqualuxe_add_article_attributes() {
	$attributes = array();
	
	// Add data attributes for JavaScript.
	$attributes['data-post-id'] = get_the_ID();
	
	// Apply filters.
	$attributes = apply_filters( 'aqualuxe_article_attributes', $attributes );
	
	// Output attributes.
	foreach ( $attributes as $name => $value ) {
		echo ' ' . esc_attr( $name ) . '="' . esc_attr( $value ) . '"';
	}
}
add_action( 'aqualuxe_article_attributes', 'aqualuxe_add_article_attributes' );

/**
 * Add custom article classes.
 */
function aqualuxe_article_classes() {
	$classes = array();
	
	// Add post format class.
	$format = get_post_format();
	if ( $format ) {
		$classes[] = 'format-' . $format;
	}
	
	// Add has-post-thumbnail class.
	if ( has_post_thumbnail() ) {
		$classes[] = 'has-post-thumbnail';
	}
	
	// Apply filters.
	$classes = apply_filters( 'aqualuxe_article_classes', $classes );
	
	// Output classes.
	echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
}
add_action( 'aqualuxe_article_classes', 'aqualuxe_article_classes' );

/**
 * Add custom image attributes.
 *
 * @param array $attr Image attributes.
 * @return array
 */
function aqualuxe_image_attributes( $attr ) {
	// Add loading attribute.
	$attr['loading'] = 'lazy';
	
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_image_attributes' );

/**
 * Add custom image sizes to the media library.
 *
 * @param array $sizes Image sizes.
 * @return array
 */
function aqualuxe_image_size_names_choose( $sizes ) {
	return array_merge( $sizes, array(
		'aqualuxe-featured' => __( 'Featured Image', 'aqualuxe' ),
		'aqualuxe-blog' => __( 'Blog Image', 'aqualuxe' ),
		'aqualuxe-product' => __( 'Product Image', 'aqualuxe' ),
		'aqualuxe-thumbnail' => __( 'Thumbnail', 'aqualuxe' ),
	) );
}
add_filter( 'image_size_names_choose', 'aqualuxe_image_size_names_choose' );

/**
 * Add custom image sizes.
 */
function aqualuxe_image_sizes() {
	add_image_size( 'aqualuxe-featured', 1200, 675, true );
	add_image_size( 'aqualuxe-blog', 800, 450, true );
	add_image_size( 'aqualuxe-product', 600, 600, true );
	add_image_size( 'aqualuxe-thumbnail', 300, 300, true );
}
add_action( 'after_setup_theme', 'aqualuxe_image_sizes' );

/**
 * Add custom image sizes to the media library.
 *
 * @param array $sizes Image sizes.
 * @return array
 */
function aqualuxe_custom_image_sizes( $sizes ) {
	return array_merge( $sizes, array(
		'aqualuxe-featured' => __( 'Featured Image', 'aqualuxe' ),
		'aqualuxe-blog' => __( 'Blog Image', 'aqualuxe' ),
		'aqualuxe-product' => __( 'Product Image', 'aqualuxe' ),
		'aqualuxe-thumbnail' => __( 'Thumbnail', 'aqualuxe' ),
	) );
}
add_filter( 'image_size_names_choose', 'aqualuxe_custom_image_sizes' );

/**
 * Add custom favicon.
 */
function aqualuxe_site_icon() {
	if ( ! has_site_icon() && ! is_customize_preview() ) {
		$favicon = get_theme_mod( 'aqualuxe_favicon', get_template_directory_uri() . '/assets/dist/images/favicon.ico' );
		
		if ( $favicon ) {
			echo '<link rel="shortcut icon" href="' . esc_url( $favicon ) . '" />';
		}
	}
}
add_action( 'wp_head', 'aqualuxe_site_icon', 99 );