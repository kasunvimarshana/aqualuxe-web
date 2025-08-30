<?php
/**
 * Theme Setup Class
 *
 * Handles the initial setup of the theme, including registering menus,
 * setting up theme support features, and configuring content width.
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core;

use AquaLuxe\Core\Service;

/**
 * Setup class.
 */
class Setup extends Service {

	/**
	 * Register service features.
	 */
	public function register() {
		add_action( 'after_setup_theme', array( $this, 'setup_theme' ) );
		add_action( 'after_setup_theme', array( $this, 'content_width' ), 0 );
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
		add_filter( 'excerpt_more', array( $this, 'custom_excerpt_more' ) );
		add_filter( 'excerpt_length', array( $this, 'custom_excerpt_length' ) );
		add_filter( 'body_class', array( $this, 'body_classes' ) );
		add_filter( 'post_class', array( $this, 'post_classes' ) );
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	public function setup_theme() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain( 'aqualuxe', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// Custom image sizes
		add_image_size( 'aqualuxe-featured', 1200, 675, true );
		add_image_size( 'aqualuxe-card', 600, 400, true );
		add_image_size( 'aqualuxe-thumbnail', 300, 300, true );

		// Register navigation menus
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'aqualuxe' ),
				'mobile'  => esc_html__( 'Mobile Menu', 'aqualuxe' ),
				'footer'  => esc_html__( 'Footer Menu', 'aqualuxe' ),
				'legal'   => esc_html__( 'Legal Menu', 'aqualuxe' ),
				'social'  => esc_html__( 'Social Menu', 'aqualuxe' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'aqualuxe_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for custom color palette.
		add_theme_support(
			'editor-color-palette',
			array(
				array(
					'name'  => esc_html__( 'Primary', 'aqualuxe' ),
					'slug'  => 'primary',
					'color' => get_theme_mod( 'aqualuxe_primary_color', '#0073aa' ),
				),
				array(
					'name'  => esc_html__( 'Secondary', 'aqualuxe' ),
					'slug'  => 'secondary',
					'color' => get_theme_mod( 'aqualuxe_secondary_color', '#005177' ),
				),
				array(
					'name'  => esc_html__( 'Dark', 'aqualuxe' ),
					'slug'  => 'dark',
					'color' => get_theme_mod( 'aqualuxe_dark_color', '#111827' ),
				),
				array(
					'name'  => esc_html__( 'Light', 'aqualuxe' ),
					'slug'  => 'light',
					'color' => get_theme_mod( 'aqualuxe_light_color', '#f9fafb' ),
				),
				array(
					'name'  => esc_html__( 'White', 'aqualuxe' ),
					'slug'  => 'white',
					'color' => '#ffffff',
				),
				array(
					'name'  => esc_html__( 'Black', 'aqualuxe' ),
					'slug'  => 'black',
					'color' => '#000000',
				),
			)
		);

		// Add support for custom font sizes.
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name' => esc_html__( 'Small', 'aqualuxe' ),
					'size' => 14,
					'slug' => 'small',
				),
				array(
					'name' => esc_html__( 'Normal', 'aqualuxe' ),
					'size' => 16,
					'slug' => 'normal',
				),
				array(
					'name' => esc_html__( 'Medium', 'aqualuxe' ),
					'size' => 20,
					'slug' => 'medium',
				),
				array(
					'name' => esc_html__( 'Large', 'aqualuxe' ),
					'size' => 24,
					'slug' => 'large',
				),
				array(
					'name' => esc_html__( 'Extra Large', 'aqualuxe' ),
					'size' => 36,
					'slug' => 'extra-large',
				),
				array(
					'name' => esc_html__( 'Huge', 'aqualuxe' ),
					'size' => 48,
					'slug' => 'huge',
				),
			)
		);

		// Add support for custom line heights.
		add_theme_support( 'custom-line-height' );

		// Add support for custom spacing.
		add_theme_support( 'custom-spacing' );

		// Add support for custom units.
		add_theme_support( 'custom-units', 'px', 'em', 'rem', 'vh', 'vw', '%' );

		// Add support for experimental link color control.
		add_theme_support( 'experimental-link-color' );

		// Add support for post formats.
		add_theme_support(
			'post-formats',
			array(
				'gallery',
				'image',
				'video',
				'audio',
				'quote',
				'link',
			)
		);

		// Add WooCommerce support.
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width
	 */
	public function content_width() {
		// This variable is intended to be overruled from themes.
		// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
		$GLOBALS['content_width'] = apply_filters( 'aqualuxe_content_width', 1200 );
	}

	/**
	 * Register widget areas.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 */
	public function register_sidebars() {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Sidebar', 'aqualuxe' ),
				'id'            => 'sidebar-1',
				'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'aqualuxe' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s mb-8">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title text-xl font-serif font-bold mb-4 text-dark-800 dark:text-white">',
				'after_title'   => '</h2>',
			)
		);

		// Footer widget areas
		$footer_widget_areas = 4;
		for ( $i = 1; $i <= $footer_widget_areas; $i++ ) {
			register_sidebar(
				array(
					'name'          => sprintf( esc_html__( 'Footer %d', 'aqualuxe' ), $i ),
					'id'            => 'footer-' . $i,
					'description'   => sprintf( esc_html__( 'Add widgets here to appear in footer area %d.', 'aqualuxe' ), $i ),
					'before_widget' => '<div id="%1$s" class="widget %2$s mb-6">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="widget-title text-lg font-bold mb-4 text-white">',
					'after_title'   => '</h3>',
				)
			);
		}

		// WooCommerce shop sidebar
		if ( class_exists( 'WooCommerce' ) ) {
			register_sidebar(
				array(
					'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
					'id'            => 'shop-sidebar',
					'description'   => esc_html__( 'Add widgets here to appear in your shop sidebar.', 'aqualuxe' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s mb-8">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title text-xl font-serif font-bold mb-4 text-dark-800 dark:text-white">',
					'after_title'   => '</h2>',
				)
			);
		}
	}

	/**
	 * Customize the excerpt "read more" string.
	 *
	 * @param string $more "Read more" excerpt string.
	 * @return string Modified "read more" excerpt string.
	 */
	public function custom_excerpt_more( $more ) {
		return '&hellip;';
	}

	/**
	 * Filter the excerpt length to 25 words.
	 *
	 * @param int $length Excerpt length.
	 * @return int Modified excerpt length.
	 */
	public function custom_excerpt_length( $length ) {
		return get_theme_mod( 'aqualuxe_excerpt_length', 25 );
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	public function body_classes( $classes ) {
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		// Adds a class if sidebar is active.
		if ( is_active_sidebar( 'sidebar-1' ) ) {
			$classes[] = 'has-sidebar';
		}

		// Add class for the color scheme.
		$color_scheme = get_theme_mod( 'aqualuxe_color_scheme', 'default' );
		$classes[]    = 'color-scheme-' . esc_attr( $color_scheme );

		// Add class for the font scheme.
		$font_scheme = get_theme_mod( 'aqualuxe_font_scheme', 'default' );
		$classes[]   = 'font-scheme-' . esc_attr( $font_scheme );

		// Add class for the container width.
		$container_width = get_theme_mod( 'aqualuxe_container_width', 'default' );
		$classes[]       = 'container-' . esc_attr( $container_width );

		// Add class for the header layout.
		$header_layout = get_theme_mod( 'aqualuxe_header_layout', 'default' );
		$classes[]     = 'header-layout-' . esc_attr( $header_layout );

		// Add class for the footer layout.
		$footer_layout = get_theme_mod( 'aqualuxe_footer_layout', 'default' );
		$classes[]     = 'footer-layout-' . esc_attr( $footer_layout );

		return $classes;
	}

	/**
	 * Adds custom classes to the array of post classes.
	 *
	 * @param array $classes Classes for the post element.
	 * @return array
	 */
	public function post_classes( $classes ) {
		// Add a class for featured posts.
		if ( is_sticky() ) {
			$classes[] = 'is-featured';
		}

		return $classes;
	}
}