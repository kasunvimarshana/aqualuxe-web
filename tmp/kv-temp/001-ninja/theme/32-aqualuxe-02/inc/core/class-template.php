<?php
/**
 * AquaLuxe Template Handler
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
 * Template Handler Class
 *
 * Provides template-related functionality and helpers.
 *
 * @since 1.1.0
 */
class Template {

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
		add_filter( 'template_include', array( $this, 'maybe_use_custom_template' ) );
		add_filter( 'body_class', array( $this, 'add_template_class' ) );
		add_action( 'aqualuxe_before_main_content', array( $this, 'add_breadcrumbs' ), 10 );
		add_action( 'aqualuxe_before_main_content', array( $this, 'add_page_header' ), 20 );
	}

	/**
	 * Check if we should use a custom template.
	 *
	 * @param string $template The template to include.
	 * @return string
	 */
	public function maybe_use_custom_template( $template ) {
		// Check for custom templates based on various conditions.
		if ( is_page() ) {
			$custom_template = $this->get_custom_page_template();
			if ( $custom_template ) {
				return $custom_template;
			}
		}

		return $template;
	}

	/**
	 * Get custom page template based on page attributes.
	 *
	 * @return string|false
	 */
	private function get_custom_page_template() {
		$page_id = get_queried_object_id();
		
		// Check for page template in page attributes.
		$template = get_page_template_slug( $page_id );
		if ( $template ) {
			$located = locate_template( $template );
			if ( $located ) {
				return $located;
			}
		}
		
		// Check for page-specific template.
		$page = get_post( $page_id );
		if ( $page ) {
			$page_slug = $page->post_name;
			$templates = array(
				"page-{$page_slug}.php",
				"templates/page-{$page_slug}.php",
			);
			
			$located = locate_template( $templates );
			if ( $located ) {
				return $located;
			}
		}
		
		return false;
	}

	/**
	 * Add template-specific class to body.
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	public function add_template_class( $classes ) {
		// Add template-specific classes.
		if ( is_page_template() ) {
			$template = get_page_template_slug();
			if ( $template ) {
				$template = str_replace( '.php', '', basename( $template ) );
				$classes[] = 'template-' . sanitize_html_class( $template );
			}
		}

		// Add layout class.
		$layout = $this->get_page_layout();
		if ( $layout ) {
			$classes[] = 'layout-' . sanitize_html_class( $layout );
		}

		return $classes;
	}

	/**
	 * Get page layout.
	 *
	 * @return string
	 */
	public function get_page_layout() {
		$layout = 'default';

		// Check for page-specific layout.
		if ( is_singular() ) {
			$post_layout = get_post_meta( get_the_ID(), '_aqualuxe_layout', true );
			if ( $post_layout ) {
				$layout = $post_layout;
			}
		}

		// Check for archive-specific layout.
		if ( is_archive() || is_home() || is_search() ) {
			$archive_layout = get_theme_mod( 'aqualuxe_archive_layout', 'default' );
			if ( $archive_layout ) {
				$layout = $archive_layout;
			}
		}

		// Check for WooCommerce-specific layout.
		if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
			$wc_layout = get_theme_mod( 'aqualuxe_woocommerce_layout', 'default' );
			if ( $wc_layout ) {
				$layout = $wc_layout;
			}
		}

		return apply_filters( 'aqualuxe_page_layout', $layout );
	}

	/**
	 * Add breadcrumbs.
	 *
	 * @return void
	 */
	public function add_breadcrumbs() {
		// Skip breadcrumbs on front page.
		if ( is_front_page() ) {
			return;
		}

		// Check if breadcrumbs are enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_breadcrumbs', true ) ) {
			return;
		}

		// Load breadcrumbs template.
		get_template_part( 'template-parts/components/breadcrumbs' );
	}

	/**
	 * Add page header.
	 *
	 * @return void
	 */
	public function add_page_header() {
		// Skip page header on front page.
		if ( is_front_page() ) {
			return;
		}

		// Check if page header is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_page_header', true ) ) {
			return;
		}

		// Load page header template.
		get_template_part( 'template-parts/components/page-header' );
	}

	/**
	 * Get template part with data.
	 *
	 * Enhanced version of get_template_part that allows passing data to the template.
	 *
	 * @param string $slug The slug name for the generic template.
	 * @param string $name The name of the specialized template.
	 * @param array  $args Optional. Additional arguments passed to the template.
	 * @return void
	 */
	public function get_template_part( $slug, $name = null, $args = array() ) {
		if ( $args && is_array( $args ) ) {
			extract( $args );
		}

		$templates = array();
		$name = (string) $name;
		
		if ( '' !== $name ) {
			$templates[] = "{$slug}-{$name}.php";
		}
		
		$templates[] = "{$slug}.php";
		
		locate_template( $templates, true, false );
	}

	/**
	 * Get template part with data and return as string.
	 *
	 * @param string $slug The slug name for the generic template.
	 * @param string $name The name of the specialized template.
	 * @param array  $args Optional. Additional arguments passed to the template.
	 * @return string
	 */
	public function get_template_part_html( $slug, $name = null, $args = array() ) {
		ob_start();
		$this->get_template_part( $slug, $name, $args );
		return ob_get_clean();
	}

	/**
	 * Check if sidebar should be displayed.
	 *
	 * @return bool
	 */
	public function has_sidebar() {
		// Don't show sidebar on full-width layout.
		$layout = $this->get_page_layout();
		if ( 'full-width' === $layout ) {
			return false;
		}

		// Don't show sidebar if it's not active.
		if ( ! is_active_sidebar( 'sidebar-1' ) ) {
			return false;
		}

		// Don't show sidebar on specific templates.
		if ( is_page_template( 'templates/full-width.php' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get sidebar position.
	 *
	 * @return string
	 */
	public function get_sidebar_position() {
		$position = get_theme_mod( 'aqualuxe_sidebar_position', 'right' );

		// Check for page-specific sidebar position.
		if ( is_singular() ) {
			$post_position = get_post_meta( get_the_ID(), '_aqualuxe_sidebar_position', true );
			if ( $post_position ) {
				$position = $post_position;
			}
		}

		return apply_filters( 'aqualuxe_sidebar_position', $position );
	}

	/**
	 * Get container class.
	 *
	 * @return string
	 */
	public function get_container_class() {
		$container = get_theme_mod( 'aqualuxe_container_type', 'container' );
		return apply_filters( 'aqualuxe_container_class', $container );
	}

	/**
	 * Get content class.
	 *
	 * @return string
	 */
	public function get_content_class() {
		$classes = array( 'site-content' );

		// Add layout-specific classes.
		$layout = $this->get_page_layout();
		$classes[] = 'layout-' . $layout;

		// Add sidebar-specific classes.
		if ( $this->has_sidebar() ) {
			$sidebar_position = $this->get_sidebar_position();
			$classes[] = 'has-sidebar';
			$classes[] = 'sidebar-' . $sidebar_position;
		} else {
			$classes[] = 'no-sidebar';
		}

		return implode( ' ', apply_filters( 'aqualuxe_content_class', $classes ) );
	}

	/**
	 * Get main content class.
	 *
	 * @return string
	 */
	public function get_main_content_class() {
		$classes = array( 'main-content' );

		// Add sidebar-specific classes.
		if ( $this->has_sidebar() ) {
			$classes[] = 'has-sidebar';
		} else {
			$classes[] = 'no-sidebar';
		}

		return implode( ' ', apply_filters( 'aqualuxe_main_content_class', $classes ) );
	}
}