<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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

	// Add a class if the site is using a sidebar.
	$content_layout = aqualuxe_get_content_layout();
	$classes[] = 'layout-' . $content_layout;

	// Add a class for the site layout.
	$site_layout = get_theme_mod( 'aqualuxe_site_layout', 'wide' );
	$classes[] = 'site-layout-' . $site_layout;

	// Add a class if dark mode is enabled.
	if ( aqualuxe_is_dark_mode_enabled() ) {
		$classes[] = 'dark-mode';
	}

	// Add a class if WooCommerce is active.
	if ( class_exists( 'WooCommerce' ) ) {
		$classes[] = 'woocommerce-active';
	}

	// Add a class for the header layout.
	$header_layout = get_theme_mod( 'aqualuxe_header_layout', 'default' );
	$classes[] = 'header-layout-' . $header_layout;

	// Add a class for the footer layout.
	$footer_layout = get_theme_mod( 'aqualuxe_footer_layout', 'default' );
	$classes[] = 'footer-layout-' . $footer_layout;

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
 * Get content layout.
 *
 * @return string
 */
function aqualuxe_get_content_layout() {
	$default_layout = get_theme_mod( 'aqualuxe_content_layout', 'right-sidebar' );

	// Check if we're on a single post.
	if ( is_single() ) {
		$layout = get_theme_mod( 'aqualuxe_single_post_layout', $default_layout );
	} elseif ( is_page() ) {
		// Check if we're on a page.
		$layout = get_theme_mod( 'aqualuxe_page_layout', 'no-sidebar' );
	} elseif ( is_archive() || is_home() || is_search() ) {
		// Check if we're on an archive page.
		$layout = get_theme_mod( 'aqualuxe_archive_layout', $default_layout );
	} else {
		// Default layout.
		$layout = $default_layout;
	}

	// Check if WooCommerce is active and we're on a WooCommerce page.
	if ( class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			$layout = get_theme_mod( 'aqualuxe_shop_layout', $default_layout );
		} elseif ( is_product() ) {
			$layout = get_theme_mod( 'aqualuxe_product_layout', $default_layout );
		}
	}

	return $layout;
}

/**
 * Check if dark mode is enabled.
 *
 * @return bool
 */
function aqualuxe_is_dark_mode_enabled() {
	// Check if dark mode is enabled in the customizer.
	$dark_mode_enabled = get_theme_mod( 'aqualuxe_enable_dark_mode', true );
	
	// If dark mode is not enabled in the customizer, return false.
	if ( ! $dark_mode_enabled ) {
		return false;
	}

	// Check if the user has set a preference.
	if ( isset( $_COOKIE['aqualuxe_dark_mode'] ) ) {
		return 'true' === $_COOKIE['aqualuxe_dark_mode'];
	}

	// Check the default mode.
	$default_mode = get_theme_mod( 'aqualuxe_dark_mode_default', 'auto' );
	
	if ( 'dark' === $default_mode ) {
		return true;
	} elseif ( 'auto' === $default_mode ) {
		// We can't detect system preference on the server side,
		// so we'll rely on the early detection script to add the class.
	}

	return false;
}

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
 * Add custom image sizes attribute to enhance responsive image functionality.
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return array
 */
function aqualuxe_image_sizes_attr( $attr, $attachment, $size ) {
	// Add custom image sizes attribute.
	if ( is_array( $size ) && isset( $attr['class'] ) && strpos( $attr['class'], 'custom-size' ) !== false ) {
		$attr['sizes'] = '(max-width: 576px) 100vw, (max-width: 768px) 50vw, (max-width: 992px) 30vw, 25vw';
	}

	// Add loading attribute for better performance.
	if ( ! isset( $attr['loading'] ) ) {
		$attr['loading'] = 'lazy';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_image_sizes_attr', 10, 3 );

/**
 * Filter the excerpt length.
 *
 * @param int $length Excerpt length.
 * @return int
 */
function aqualuxe_excerpt_length( $length ) {
	return get_theme_mod( 'aqualuxe_blog_excerpt_length', 25 );
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length' );

/**
 * Filter the excerpt "read more" string.
 *
 * @param string $more "Read more" excerpt string.
 * @return string
 */
function aqualuxe_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Filter the archive title.
 *
 * @param string $title Archive title.
 * @return string
 */
function aqualuxe_archive_title( $title ) {
	if ( is_category() ) {
		$title = single_cat_title( '', false );
	} elseif ( is_tag() ) {
		$title = single_tag_title( '', false );
	} elseif ( is_author() ) {
		$title = get_the_author();
	} elseif ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );
	} elseif ( is_tax() ) {
		$title = single_term_title( '', false );
	}

	return $title;
}
add_filter( 'get_the_archive_title', 'aqualuxe_archive_title' );

/**
 * Filter the navigation markup template.
 *
 * @param string $template Navigation template.
 * @return string
 */
function aqualuxe_navigation_markup( $template ) {
	$template = '
	<nav class="navigation %1$s" role="navigation" aria-label="%4$s">
		<h2 class="screen-reader-text">%2$s</h2>
		<div class="nav-links">%3$s</div>
	</nav>';

	return $template;
}
add_filter( 'navigation_markup_template', 'aqualuxe_navigation_markup' );

/**
 * Filter the nav menu arguments.
 *
 * @param array $args Nav menu arguments.
 * @return array
 */
function aqualuxe_nav_menu_args( $args ) {
	// Add custom classes to menu items.
	if ( 'primary' === $args['theme_location'] ) {
		$args['menu_class'] = 'primary-menu flex items-center space-x-6';
	} elseif ( 'mobile' === $args['theme_location'] ) {
		$args['menu_class'] = 'mobile-menu py-2 space-y-2';
	} elseif ( 'footer' === $args['theme_location'] ) {
		$args['menu_class'] = 'footer-menu flex flex-wrap space-x-4';
	}

	return $args;
}
add_filter( 'wp_nav_menu_args', 'aqualuxe_nav_menu_args' );

/**
 * Filter the widget title.
 *
 * @param string $title Widget title.
 * @return string
 */
function aqualuxe_widget_title( $title ) {
	if ( ! empty( $title ) ) {
		return $title;
	}
	return $title;
}
add_filter( 'widget_title', 'aqualuxe_widget_title' );

/**
 * Register custom image sizes.
 */
function aqualuxe_register_image_sizes() {
	// Add custom image sizes.
	add_image_size( 'aqualuxe-featured', 1200, 600, true );
	add_image_size( 'aqualuxe-blog', 800, 450, true );
	add_image_size( 'aqualuxe-blog-grid', 600, 400, true );
	add_image_size( 'aqualuxe-blog-list', 400, 300, true );
	add_image_size( 'aqualuxe-product-thumbnail', 300, 300, true );
	add_image_size( 'aqualuxe-product-gallery', 800, 800, true );
}
add_action( 'after_setup_theme', 'aqualuxe_register_image_sizes' );

/**
 * Add custom image sizes to the media library.
 *
 * @param array $sizes Image sizes.
 * @return array
 */
function aqualuxe_custom_image_sizes( $sizes ) {
	return array_merge(
		$sizes,
		array(
			'aqualuxe-featured'        => __( 'Featured Image', 'aqualuxe' ),
			'aqualuxe-blog'            => __( 'Blog Image', 'aqualuxe' ),
			'aqualuxe-blog-grid'       => __( 'Blog Grid Image', 'aqualuxe' ),
			'aqualuxe-blog-list'       => __( 'Blog List Image', 'aqualuxe' ),
			'aqualuxe-product-thumbnail' => __( 'Product Thumbnail', 'aqualuxe' ),
			'aqualuxe-product-gallery' => __( 'Product Gallery', 'aqualuxe' ),
		)
	);
}
add_filter( 'image_size_names_choose', 'aqualuxe_custom_image_sizes' );

/**
 * Add custom classes to the array of post classes.
 *
 * @param array $classes Classes for the post element.
 * @return array
 */
function aqualuxe_post_classes( $classes ) {
	// Add a class for the post layout.
	$blog_layout = get_theme_mod( 'aqualuxe_blog_layout', 'grid' );
	$classes[] = 'post-layout-' . $blog_layout;

	return $classes;
}
add_filter( 'post_class', 'aqualuxe_post_classes' );

/**
 * Add custom classes to the array of comment classes.
 *
 * @param array $classes Classes for the comment element.
 * @return array
 */
function aqualuxe_comment_classes( $classes ) {
	// Add a class for the comment type.
	if ( get_comment_type() === 'comment' ) {
		$classes[] = 'comment-type-comment';
	} elseif ( get_comment_type() === 'pingback' ) {
		$classes[] = 'comment-type-pingback';
	} elseif ( get_comment_type() === 'trackback' ) {
		$classes[] = 'comment-type-trackback';
	}

	return $classes;
}
add_filter( 'comment_class', 'aqualuxe_comment_classes' );

/**
 * Add custom classes to the array of menu item classes.
 *
 * @param array $classes Classes for the menu item.
 * @param object $item Menu item.
 * @param object $args Menu arguments.
 * @return array
 */
function aqualuxe_menu_item_classes( $classes, $item, $args ) {
	// Add a class for the menu item depth.
	$classes[] = 'menu-item-depth-' . $item->menu_item_parent;

	// Add a class for the menu item type.
	if ( $item->type === 'post_type' ) {
		$classes[] = 'menu-item-type-post';
	} elseif ( $item->type === 'taxonomy' ) {
		$classes[] = 'menu-item-type-taxonomy';
	} elseif ( $item->type === 'custom' ) {
		$classes[] = 'menu-item-type-custom';
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'aqualuxe_menu_item_classes', 10, 3 );

/**
 * Add custom attributes to the array of menu item attributes.
 *
 * @param array $atts Menu item attributes.
 * @param object $item Menu item.
 * @param object $args Menu arguments.
 * @param int $depth Menu depth.
 * @return array
 */
function aqualuxe_menu_item_attributes( $atts, $item, $args, $depth ) {
	// Add ARIA attributes to menu items with children.
	if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
		$atts['aria-haspopup'] = 'true';
		$atts['aria-expanded'] = 'false';
	}

	// Add ARIA current attribute to active menu items.
	if ( in_array( 'current-menu-item', $item->classes, true ) ) {
		$atts['aria-current'] = 'page';
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'aqualuxe_menu_item_attributes', 10, 4 );

/**
 * Add custom attributes to the array of attachment image attributes.
 *
 * @param array $attr Image attributes.
 * @param object $attachment Attachment object.
 * @param string|array $size Image size.
 * @return array
 */
function aqualuxe_attachment_image_attributes( $attr, $attachment, $size ) {
	// Add alt text to images.
	if ( empty( $attr['alt'] ) ) {
		$attr['alt'] = get_the_title( $attachment->ID );
	}

	// Add loading attribute for better performance.
	if ( ! isset( $attr['loading'] ) ) {
		$attr['loading'] = 'lazy';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_attachment_image_attributes', 10, 3 );

/**
 * Add ARIA landmarks to content.
 *
 * @param string $content Post content.
 * @return string
 */
function aqualuxe_add_aria_landmarks( $content ) {
	// Add ARIA landmarks to headings.
	$content = preg_replace_callback(
		'/<h([1-6])(.*?)>(.*?)<\/h\1>/',
		function( $matches ) {
			$level = $matches[1];
			$attrs = $matches[2];
			$text = $matches[3];
			
			// Add ID attribute if not present.
			if ( ! strpos( $attrs, 'id=' ) ) {
				$id = sanitize_title( $text );
				$attrs .= ' id="' . $id . '"';
			}
			
			return '<h' . $level . $attrs . '>' . $text . '</h' . $level . '>';
		},
		$content
	);

	// Add ARIA landmarks to tables.
	$content = preg_replace_callback(
		'/<table(.*?)>(.*?)<\/table>/s',
		function( $matches ) {
			$attrs = $matches[1];
			$table_content = $matches[2];
			
			// Add role attribute if not present.
			if ( ! strpos( $attrs, 'role=' ) ) {
				$attrs .= ' role="table"';
			}
			
			// Add aria-label if not present.
			if ( ! strpos( $attrs, 'aria-label=' ) && ! strpos( $attrs, 'aria-labelledby=' ) ) {
				$attrs .= ' aria-label="' . esc_attr__( 'Table', 'aqualuxe' ) . '"';
			}
			
			return '<table' . $attrs . '>' . $table_content . '</table>';
		},
		$content
	);

	// Add ARIA landmarks to forms.
	$content = preg_replace_callback(
		'/<form(.*?)>(.*?)<\/form>/s',
		function( $matches ) {
			$attrs = $matches[1];
			$form_content = $matches[2];
			
			// Add role attribute if not present.
			if ( ! strpos( $attrs, 'role=' ) ) {
				$attrs .= ' role="form"';
			}
			
			return '<form' . $attrs . '>' . $form_content . '</form>';
		},
		$content
	);

	return $content;
}
add_filter( 'the_content', 'aqualuxe_add_aria_landmarks' );

/**
 * Add custom attributes to the array of comment form defaults.
 *
 * @param array $defaults Comment form defaults.
 * @return array
 */
function aqualuxe_comment_form_defaults( $defaults ) {
	// Add ARIA attributes to comment form.
	$defaults['title_reply_before'] = '<h3 id="reply-title" class="comment-reply-title">';
	$defaults['title_reply_after'] = '</h3>';
	$defaults['comment_notes_before'] = '<p class="comment-notes"><span id="email-notes">' . esc_html__( 'Your email address will not be published.', 'aqualuxe' ) . '</span> ' . esc_html__( 'Required fields are marked', 'aqualuxe' ) . ' <span class="required">*</span></p>';
	
	return $defaults;
}
add_filter( 'comment_form_defaults', 'aqualuxe_comment_form_defaults' );

/**
 * Add custom attributes to the comment reply link.
 *
 * @param string $link Comment reply link.
 * @return string
 */
function aqualuxe_comment_reply_link( $link ) {
	// Add ARIA attributes to comment reply link.
	$link = str_replace( 'class="comment-reply-link"', 'class="comment-reply-link" role="button"', $link );
	
	return $link;
}
add_filter( 'comment_reply_link', 'aqualuxe_comment_reply_link' );

/**
 * Add custom attributes to the comment author link.
 *
 * @param string $link Comment author link.
 * @return string
 */
function aqualuxe_comment_author_link( $link ) {
	// Add ARIA attributes to comment author link.
	$link = str_replace( '<a', '<a rel="external nofollow" class="comment-author-link"', $link );
	
	return $link;
}
add_filter( 'get_comment_author_link', 'aqualuxe_comment_author_link' );

/**
 * Add ARIA current attribute to navigation menu.
 *
 * @param string $nav_menu Navigation menu HTML.
 * @param object $args Menu arguments.
 * @return string
 */
function aqualuxe_nav_menu_aria_current( $nav_menu, $args ) {
	// Add ARIA current attribute to active menu items.
	$nav_menu = preg_replace( '/(current_page_item|current-menu-item)[^<]*<a /', '$1$2 aria-current="page"><a ', $nav_menu );
	
	return $nav_menu;
}
add_filter( 'wp_nav_menu', 'aqualuxe_nav_menu_aria_current', 10, 2 );

/**
 * Add ARIA attributes to posts navigation links.
 *
 * @param string $attributes Link attributes.
 * @return string
 */
function aqualuxe_posts_link_attributes( $attributes ) {
	// Add ARIA attributes to posts navigation links.
	$attributes .= ' aria-label="' . esc_attr__( 'Posts', 'aqualuxe' ) . '"';
	
	return $attributes;
}
add_filter( 'next_posts_link_attributes', 'aqualuxe_posts_link_attributes' );
add_filter( 'previous_posts_link_attributes', 'aqualuxe_posts_link_attributes' );

/**
 * Get screen reader text.
 *
 * @param string $text Text to be hidden visually but available to screen readers.
 * @return string
 */
function aqualuxe_screen_reader_text( $text ) {
	return '<span class="screen-reader-text">' . $text . '</span>';
}