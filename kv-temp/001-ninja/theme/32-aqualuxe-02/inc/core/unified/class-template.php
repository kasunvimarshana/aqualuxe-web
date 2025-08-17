<?php
/**
 * AquaLuxe Template Class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.2.0
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Template Class
 *
 * This class handles all template-related functionality for the theme.
 * It provides a unified system for managing body classes, template parts, and other template-related features.
 *
 * @since 1.2.0
 */
class Template {

	/**
	 * The single instance of this class.
	 *
	 * @var Template
	 */
	private static $instance = null;

	/**
	 * Registered body classes.
	 *
	 * @var array
	 */
	private $body_classes = array();

	/**
	 * Constructor.
	 */
	private function __construct() {
		// Add hooks.
		add_filter( 'body_class', array( $this, 'body_classes' ) );
		add_action( 'wp_head', array( $this, 'pingback_header' ) );
		add_filter( 'excerpt_more', array( $this, 'excerpt_more' ) );
		add_filter( 'excerpt_length', array( $this, 'excerpt_length' ) );
		add_filter( 'get_custom_logo', array( $this, 'custom_logo_class' ) );
		
		// Register default body classes
		$this->register_body_class( 'is_singular', 'singular' );
		$this->register_body_class( 'is_home', 'home' );
		$this->register_body_class( 'is_archive', 'archive' );
		$this->register_body_class( 'is_search', 'search' );
		$this->register_body_class( 'is_404', 'error-404' );
		
		// Register dark mode body class
		$this->register_body_class(
			function() {
				return get_theme_mod( 'aqualuxe_dark_mode_default', false );
			},
			'dark-mode'
		);
		
		// Register layout body class
		$this->register_body_class(
			function() {
				$layout = get_theme_mod( 'aqualuxe_layout', 'right-sidebar' );
				return true;
			},
			function() {
				$layout = get_theme_mod( 'aqualuxe_layout', 'right-sidebar' );
				return 'layout-' . $layout;
			}
		);
	}

	/**
	 * Get the single instance of this class.
	 *
	 * @return Template
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register a body class.
	 *
	 * @param mixed $condition Condition callback or string.
	 * @param mixed $class     Class name or callback.
	 * @return void
	 */
	public function register_body_class( $condition, $class ) {
		$this->body_classes[] = array(
			'condition' => $condition,
			'class'     => $class,
		);
	}

	/**
	 * Add body classes.
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	public function body_classes( $classes ) {
		// Add registered body classes.
		foreach ( $this->body_classes as $body_class ) {
			$condition = $body_class['condition'];
			$class     = $body_class['class'];

			// Check if condition is a callback.
			if ( is_callable( $condition ) ) {
				$condition_met = call_user_func( $condition );
			} else {
				// Assume it's a function name.
				$condition_met = function_exists( $condition ) ? call_user_func( $condition ) : false;
			}

			// Add class if condition is met.
			if ( $condition_met ) {
				// Check if class is a callback.
				if ( is_callable( $class ) ) {
					$class_name = call_user_func( $class );
					if ( is_array( $class_name ) ) {
						$classes = array_merge( $classes, $class_name );
					} else {
						$classes[] = $class_name;
					}
				} else {
					// Assume it's a string.
					$classes[] = $class;
				}
			}
		}

		return $classes;
	}

	/**
	 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
	 *
	 * @return void
	 */
	public function pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}

	/**
	 * Change the excerpt more string.
	 *
	 * @param string $more The current more string.
	 * @return string
	 */
	public function excerpt_more( $more ) {
		if ( is_admin() ) {
			return $more;
		}

		return '&hellip; <a class="read-more" href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'Read More', 'aqualuxe' ) . '</a>';
	}

	/**
	 * Change the excerpt length.
	 *
	 * @param int $length The current excerpt length.
	 * @return int
	 */
	public function excerpt_length( $length ) {
		if ( is_admin() ) {
			return $length;
		}

		return get_theme_mod( 'aqualuxe_excerpt_length', 55 );
	}

	/**
	 * Add custom class to the custom logo.
	 *
	 * @param string $html The custom logo HTML.
	 * @return string
	 */
	public function custom_logo_class( $html ) {
		$html = str_replace( 'custom-logo-link', 'custom-logo-link site-logo', $html );
		return $html;
	}

	/**
	 * Get template part with data.
	 *
	 * @param string $slug The slug name for the generic template.
	 * @param string $name The name of the specialized template.
	 * @param array  $args Additional arguments passed to the template.
	 * @return void
	 */
	public function get_template_part( $slug, $name = null, $args = array() ) {
		if ( $args && is_array( $args ) ) {
			extract( $args ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		}

		$templates = array();
		$name      = (string) $name;
		if ( '' !== $name ) {
			$templates[] = "{$slug}-{$name}.php";
		}

		$templates[] = "{$slug}.php";

		locate_template( $templates, true, false, $args );
	}

	/**
	 * Get the post thumbnail with responsive srcset.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $size    Image size.
	 * @param array  $attr    Image attributes.
	 * @return string
	 */
	public function get_responsive_post_thumbnail( $post_id = null, $size = 'post-thumbnail', $attr = array() ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		if ( ! has_post_thumbnail( $post_id ) ) {
			return '';
		}

		$default_attr = array(
			'class' => 'responsive-image',
			'sizes' => '(max-width: 768px) 100vw, 768px',
		);

		$attr = wp_parse_args( $attr, $default_attr );

		return get_the_post_thumbnail( $post_id, $size, $attr );
	}

	/**
	 * Get the post thumbnail with lazy loading.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $size    Image size.
	 * @param array  $attr    Image attributes.
	 * @return string
	 */
	public function get_lazy_post_thumbnail( $post_id = null, $size = 'post-thumbnail', $attr = array() ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		if ( ! has_post_thumbnail( $post_id ) ) {
			return '';
		}

		$default_attr = array(
			'class'    => 'lazy-image',
			'loading'  => 'lazy',
			'decoding' => 'async',
		);

		$attr = wp_parse_args( $attr, $default_attr );

		return get_the_post_thumbnail( $post_id, $size, $attr );
	}
}