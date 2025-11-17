<?php
/**
 * AquaLuxe Theme Setup
 *
 * @package AquaLuxe
 * @since 1.1.0
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme Setup Class
 *
 * Handles theme setup, features, and initialization.
 *
 * @since 1.1.0
 */
class Setup {

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		// No initialization needed.
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'after_setup_theme', array( $this, 'content_width' ), 0 );
		add_filter( 'body_class', array( $this, 'body_classes' ) );
		add_filter( 'excerpt_more', array( $this, 'excerpt_more' ) );
		add_filter( 'excerpt_length', array( $this, 'excerpt_length' ) );
		add_filter( 'wp_resource_hints', array( $this, 'resource_hints' ), 10, 2 );
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @return void
	 */
	public function setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain( 'aqualuxe', AQUALUXE_DIR . '/languages' );

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

		// Set default thumbnail size
		set_post_thumbnail_size( 1200, 9999 );

		// Add custom image sizes
		add_image_size( 'aqualuxe-featured', 1600, 900, true );
		add_image_size( 'aqualuxe-product', 600, 600, true );
		add_image_size( 'aqualuxe-thumbnail', 300, 300, true );
		add_image_size( 'aqualuxe-medium', 800, 600, true );

		// Register navigation menus
		register_nav_menus(
			array(
				'primary'   => esc_html__( 'Primary Menu', 'aqualuxe' ),
				'secondary' => esc_html__( 'Secondary Menu', 'aqualuxe' ),
				'footer'    => esc_html__( 'Footer Menu', 'aqualuxe' ),
				'social'    => esc_html__( 'Social Menu', 'aqualuxe' ),
				'mobile'    => esc_html__( 'Mobile Menu', 'aqualuxe' ),
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
				'navigation-widgets',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'assets/css/editor-style.css' );

		// Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );

		// Add support for custom line height controls.
		add_theme_support( 'custom-line-height' );

		// Add support for experimental link color control.
		add_theme_support( 'experimental-link-color' );

		// Add support for custom units.
		add_theme_support( 'custom-units' );

		// Add support for custom spacing.
		add_theme_support( 'custom-spacing' );

		// Add support for block styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for custom logo.
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		// Add support for custom header.
		add_theme_support(
			'custom-header',
			array(
				'default-image'      => '',
				'default-text-color' => '000000',
				'width'              => 1600,
				'height'             => 500,
				'flex-width'         => true,
				'flex-height'        => true,
				'wp-head-callback'   => array( $this, 'header_style' ),
			)
		);

		// Add support for custom background.
		add_theme_support(
			'custom-background',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		);

		// Add support for post formats.
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);
	}

	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width
	 */
	public function content_width() {
		$GLOBALS['content_width'] = apply_filters( 'aqualuxe_content_width', 1200 );
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
		if ( get_theme_mod( 'aqualuxe_dark_mode_default', false ) ) {
			$classes[] = 'dark-mode';
		}

		return $classes;
	}

	/**
	 * Replaces "[...]" (appended to automatically generated excerpts) with ... and a more link.
	 *
	 * @param string $more The excerpt "more" string.
	 * @return string
	 */
	public function excerpt_more( $more ) {
		if ( is_admin() ) {
			return $more;
		}

		$more_text = get_theme_mod( 'aqualuxe_excerpt_more_text', esc_html__( 'Continue reading', 'aqualuxe' ) );
		$more      = sprintf(
			'&hellip; <p class="more-link-container"><a href="%1$s" class="more-link">%2$s <span class="screen-reader-text">%3$s</span></a></p>',
			esc_url( get_permalink( get_the_ID() ) ),
			esc_html( $more_text ),
			esc_html( get_the_title( get_the_ID() ) )
		);

		return apply_filters( 'aqualuxe_excerpt_more', $more );
	}

	/**
	 * Filters the excerpt length.
	 *
	 * @param int $length Excerpt length.
	 * @return int
	 */
	public function excerpt_length( $length ) {
		if ( is_admin() ) {
			return $length;
		}

		return get_theme_mod( 'aqualuxe_excerpt_length', 55 );
	}

	/**
	 * Add preconnect for Google Fonts.
	 *
	 * @param array  $urls          URLs to print for resource hints.
	 * @param string $relation_type The relation type the URLs are printed.
	 * @return array URLs to print for resource hints.
	 */
	public function resource_hints( $urls, $relation_type ) {
		if ( 'preconnect' === $relation_type ) {
			$urls[] = array(
				'href' => 'https://fonts.gstatic.com',
				'crossorigin',
			);
		}

		return $urls;
	}

	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @return void
	 */
	public function header_style() {
		$header_text_color = get_header_textcolor();

		// If no custom options for text are set, let's bail.
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
}