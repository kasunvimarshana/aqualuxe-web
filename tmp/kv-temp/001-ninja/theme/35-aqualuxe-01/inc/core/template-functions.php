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

	// Add class for the blog layout.
	if ( is_home() || is_archive() || is_search() ) {
		$blog_layout = get_theme_mod( 'aqualuxe_blog_layout', 'grid' );
		$classes[] = 'blog-layout-' . $blog_layout;
	}

	// Add class for the blog sidebar.
	if ( is_home() || is_archive() || is_search() || is_singular( 'post' ) ) {
		$blog_sidebar = get_theme_mod( 'aqualuxe_blog_sidebar', 'right' );
		$classes[] = 'blog-sidebar-' . $blog_sidebar;
	}

	// Add class for the shop sidebar.
	if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_category() || is_product_tag() || is_product() ) ) {
		$shop_sidebar = get_theme_mod( 'aqualuxe_shop_sidebar', 'right' );
		$classes[] = 'shop-sidebar-' . $shop_sidebar;
	}

	// Add class for the header layout.
	$header_layout = get_theme_mod( 'aqualuxe_header_layout', 'default' );
	$classes[] = 'header-layout-' . $header_layout;

	// Add class for the sticky header.
	if ( get_theme_mod( 'aqualuxe_sticky_header', true ) ) {
		$classes[] = 'has-sticky-header';
	}

	// Add class for the footer layout.
	$footer_layout = get_theme_mod( 'aqualuxe_footer_layout', '4-columns' );
	$classes[] = 'footer-layout-' . $footer_layout;

	// Add class for WooCommerce.
	if ( class_exists( 'WooCommerce' ) ) {
		$classes[] = 'has-woocommerce';
	} else {
		$classes[] = 'no-woocommerce';
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
 * Get HTML class for the html element.
 */
function aqualuxe_get_html_class() {
	$classes = array();

	// Add class for the default color scheme.
	$default_color_scheme = get_theme_mod( 'aqualuxe_default_color_scheme', 'light' );
	$classes[] = 'color-scheme-' . $default_color_scheme;

	// Add class for the dark mode toggle.
	if ( get_theme_mod( 'aqualuxe_enable_dark_mode', true ) ) {
		$classes[] = 'has-dark-mode-toggle';
	}

	return implode( ' ', $classes );
}

/**
 * Register widget areas.
 */
function aqualuxe_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'aqualuxe' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'aqualuxe' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	// Register WooCommerce sidebar.
	if ( class_exists( 'WooCommerce' ) ) {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
				'id'            => 'shop',
				'description'   => esc_html__( 'Add widgets here to appear in your shop sidebar.', 'aqualuxe' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}

	// Register footer widget areas.
	$footer_layout = get_theme_mod( 'aqualuxe_footer_layout', '4-columns' );
	$footer_columns = 4;

	if ( '3-columns' === $footer_layout ) {
		$footer_columns = 3;
	} elseif ( '2-columns' === $footer_layout ) {
		$footer_columns = 2;
	} elseif ( '1-column' === $footer_layout ) {
		$footer_columns = 1;
	}

	for ( $i = 1; $i <= $footer_columns; $i++ ) {
		register_sidebar(
			array(
				'name'          => sprintf( esc_html__( 'Footer %d', 'aqualuxe' ), $i ),
				'id'            => 'footer-' . $i,
				'description'   => sprintf( esc_html__( 'Add widgets here to appear in footer column %d.', 'aqualuxe' ), $i ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'aqualuxe_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
	// Enqueue Google Fonts.
	$body_font_family = get_theme_mod( 'aqualuxe_body_font_family', 'Inter, sans-serif' );
	$heading_font_family = get_theme_mod( 'aqualuxe_heading_font_family', 'Playfair Display, serif' );

	$google_fonts = array();

	// Add body font.
	if ( 'Inter, sans-serif' === $body_font_family ) {
		$google_fonts[] = 'Inter:wght@400;500;600;700';
	} elseif ( 'Roboto, sans-serif' === $body_font_family ) {
		$google_fonts[] = 'Roboto:wght@400;500;700';
	} elseif ( 'Open Sans, sans-serif' === $body_font_family ) {
		$google_fonts[] = 'Open+Sans:wght@400;600;700';
	} elseif ( 'Lato, sans-serif' === $body_font_family ) {
		$google_fonts[] = 'Lato:wght@400;700';
	} elseif ( 'Montserrat, sans-serif' === $body_font_family ) {
		$google_fonts[] = 'Montserrat:wght@400;500;600;700';
	} elseif ( 'Poppins, sans-serif' === $body_font_family ) {
		$google_fonts[] = 'Poppins:wght@400;500;600;700';
	} elseif ( 'Nunito, sans-serif' === $body_font_family ) {
		$google_fonts[] = 'Nunito:wght@400;600;700';
	} elseif ( 'Raleway, sans-serif' === $body_font_family ) {
		$google_fonts[] = 'Raleway:wght@400;500;600;700';
	} elseif ( 'PT Serif, serif' === $body_font_family ) {
		$google_fonts[] = 'PT+Serif:wght@400;700';
	} elseif ( 'Merriweather, serif' === $body_font_family ) {
		$google_fonts[] = 'Merriweather:wght@400;700';
	}

	// Add heading font.
	if ( 'Playfair Display, serif' === $heading_font_family ) {
		$google_fonts[] = 'Playfair+Display:wght@400;500;600;700';
	} elseif ( 'Inter, sans-serif' === $heading_font_family ) {
		$google_fonts[] = 'Inter:wght@400;500;600;700';
	} elseif ( 'Roboto, sans-serif' === $heading_font_family ) {
		$google_fonts[] = 'Roboto:wght@400;500;700';
	} elseif ( 'Open Sans, sans-serif' === $heading_font_family ) {
		$google_fonts[] = 'Open+Sans:wght@400;600;700';
	} elseif ( 'Lato, sans-serif' === $heading_font_family ) {
		$google_fonts[] = 'Lato:wght@400;700';
	} elseif ( 'Montserrat, sans-serif' === $heading_font_family ) {
		$google_fonts[] = 'Montserrat:wght@400;500;600;700';
	} elseif ( 'Poppins, sans-serif' === $heading_font_family ) {
		$google_fonts[] = 'Poppins:wght@400;500;600;700';
	} elseif ( 'Nunito, sans-serif' === $heading_font_family ) {
		$google_fonts[] = 'Nunito:wght@400;600;700';
	} elseif ( 'Raleway, sans-serif' === $heading_font_family ) {
		$google_fonts[] = 'Raleway:wght@400;500;600;700';
	} elseif ( 'PT Serif, serif' === $heading_font_family ) {
		$google_fonts[] = 'PT+Serif:wght@400;700';
	} elseif ( 'Merriweather, serif' === $heading_font_family ) {
		$google_fonts[] = 'Merriweather:wght@400;700';
	}

	// Remove duplicates.
	$google_fonts = array_unique( $google_fonts );

	// Enqueue Google Fonts.
	if ( ! empty( $google_fonts ) ) {
		$google_fonts_url = 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', $google_fonts ) . '&display=swap';
		wp_enqueue_style( 'aqualuxe-fonts', $google_fonts_url, array(), AQUALUXE_VERSION );
	}
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_scripts' );

/**
 * Implement the Custom Header feature.
 */
function aqualuxe_custom_header_setup() {
	add_theme_support(
		'custom-header',
		apply_filters(
			'aqualuxe_custom_header_args',
			array(
				'default-image'      => '',
				'default-text-color' => '000000',
				'width'              => 1600,
				'height'             => 500,
				'flex-height'        => true,
				'wp-head-callback'   => 'aqualuxe_header_style',
			)
		)
	);
}
add_action( 'after_setup_theme', 'aqualuxe_custom_header_setup' );

/**
 * Styles the header image and text displayed on the blog.
 *
 * @see aqualuxe_custom_header_setup().
 */
function aqualuxe_header_style() {
	$header_text_color = get_header_textcolor();

	/*
	 * If no custom options for text are set, let's bail.
	 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
	 */
	if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
		return;
	}

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
	// Has the text been hidden?
	if ( ! display_header_text() ) :
		?>
		.site-title,
		.site-description {
			position: absolute;
			clip: rect(1px, 1px, 1px, 1px);
			}
		<?php
		// If the user has set a custom color for the text use that.
	else :
		?>
		.site-title a,
		.site-description {
			color: #<?php echo esc_attr( $header_text_color ); ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
if (! function_exists('aqualuxe_content_width')) :
    function aqualuxe_content_width()
    {
        $GLOBALS['content_width'] = apply_filters('aqualuxe_content_width', 1200);
    }
endif;
add_action( 'after_setup_theme', 'aqualuxe_content_width', 0 );

/**
 * Adds custom classes to the array of post classes.
 *
 * @param array $classes Classes for the post element.
 * @return array
 */
function aqualuxe_post_classes( $classes ) {
	// Add class for the blog layout.
	if ( is_home() || is_archive() || is_search() ) {
		$blog_layout = get_theme_mod( 'aqualuxe_blog_layout', 'grid' );
		$classes[] = 'post-layout-' . $blog_layout;
	}

	return $classes;
}
add_filter( 'post_class', 'aqualuxe_post_classes' );

/**
 * Modify the excerpt length.
 *
 * @param int $length Excerpt length.
 * @return int Modified excerpt length.
 */
function aqualuxe_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length' );

/**
 * Modify the excerpt more string.
 *
 * @param string $more The excerpt more string.
 * @return string Modified excerpt more string.
 */
function aqualuxe_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Register action hooks for template parts.
 */
function aqualuxe_register_template_hooks() {
	// Archive header.
	add_action( 'aqualuxe_archive_header', 'aqualuxe_archive_header', 10 );

	// Search header.
	add_action( 'aqualuxe_search_header', 'aqualuxe_search_header', 10 );

	// Page header.
	add_action( 'aqualuxe_page_header', 'aqualuxe_page_header', 10 );

	// Post header.
	add_action( 'aqualuxe_post_header', 'aqualuxe_post_header', 10 );

	// Pagination.
	add_action( 'aqualuxe_after_archive_content', 'aqualuxe_pagination', 10 );
	add_action( 'aqualuxe_after_search_content', 'aqualuxe_pagination', 10 );

	// No posts found.
	add_action( 'aqualuxe_no_posts_found', 'aqualuxe_no_posts_found', 10 );
	add_action( 'aqualuxe_no_search_results', 'aqualuxe_no_posts_found', 10 );

	// 404 content.
	add_action( 'aqualuxe_404_content', 'aqualuxe_404_content', 10 );

	// Breadcrumbs.
	add_action( 'aqualuxe_before_main_content', 'aqualuxe_breadcrumbs', 10 );

	// Post content.
	add_action( 'aqualuxe_before_post_content', 'aqualuxe_post_thumbnail', 10 );
	add_action( 'aqualuxe_after_post_content', 'aqualuxe_post_tags', 10 );
	add_action( 'aqualuxe_after_post_content', 'aqualuxe_post_navigation', 20 );
	add_action( 'aqualuxe_after_post_content', 'aqualuxe_related_posts', 30 );

	// Page content.
	add_action( 'aqualuxe_before_page_content', 'aqualuxe_post_thumbnail', 10 );
}
add_action( 'after_setup_theme', 'aqualuxe_register_template_hooks' );