<?php
/**
 * Template Class
 *
 * @package AquaLuxe
 * @subpackage Core
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Template Class
 *
 * This class handles template-related functionality.
 */
class Template extends Service {

	/**
	 * Initialize the service
	 *
	 * @return void
	 */
	public function initialize() {
		$this->register_hooks();
	}

	/**
	 * Register hooks
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_filter( 'body_class', array( $this, 'add_body_classes' ) );
		add_filter( 'post_class', array( $this, 'add_post_classes' ) );
		add_filter( 'excerpt_length', array( $this, 'custom_excerpt_length' ) );
		add_filter( 'excerpt_more', array( $this, 'custom_excerpt_more' ) );
		add_filter( 'get_the_archive_title', array( $this, 'custom_archive_title' ) );
		add_filter( 'navigation_markup_template', array( $this, 'custom_navigation_markup' ) );
		add_filter( 'wp_nav_menu_args', array( $this, 'modify_nav_menu_args' ) );
		add_filter( 'widget_title', array( $this, 'modify_widget_title' ) );
		add_action( 'after_setup_theme', array( $this, 'register_image_sizes' ) );
	}

	/**
	 * Add custom body classes
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	public function add_body_classes( $classes ) {
		// Add a class if the site is using a sidebar.
		$content_layout = $this->get_content_layout();
		$classes[] = 'layout-' . $content_layout;

		// Add a class for the site layout.
		$site_layout = $this->get_theme_option( 'aqualuxe_site_layout', 'wide' );
		$classes[] = 'site-layout-' . $site_layout;

		// Add a class if dark mode is enabled.
		if ( $this->is_dark_mode_enabled() ) {
			$classes[] = 'dark-mode';
		}

		// Add a class if WooCommerce is active.
		if ( $this->is_woocommerce_active() ) {
			$classes[] = 'woocommerce-active';
		}

		// Add a class for the header layout.
		$header_layout = $this->get_theme_option( 'aqualuxe_header_layout', 'default' );
		$classes[] = 'header-layout-' . $header_layout;

		// Add a class for the footer layout.
		$footer_layout = $this->get_theme_option( 'aqualuxe_footer_layout', 'default' );
		$classes[] = 'footer-layout-' . $footer_layout;

		return $classes;
	}

	/**
	 * Add custom post classes
	 *
	 * @param array $classes Post classes.
	 * @return array
	 */
	public function add_post_classes( $classes ) {
		// Add a class for the post layout.
		$blog_layout = $this->get_theme_option( 'aqualuxe_blog_layout', 'grid' );
		$classes[] = 'post-layout-' . $blog_layout;

		return $classes;
	}

	/**
	 * Custom excerpt length
	 *
	 * @param int $length Excerpt length.
	 * @return int
	 */
	public function custom_excerpt_length( $length ) {
		return $this->get_theme_option( 'aqualuxe_blog_excerpt_length', 25 );
	}

	/**
	 * Custom excerpt more
	 *
	 * @param string $more Excerpt more.
	 * @return string
	 */
	public function custom_excerpt_more( $more ) {
		return '&hellip;';
	}

	/**
	 * Custom archive title
	 *
	 * @param string $title Archive title.
	 * @return string
	 */
	public function custom_archive_title( $title ) {
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

	/**
	 * Custom navigation markup
	 *
	 * @param string $template Navigation template.
	 * @return string
	 */
	public function custom_navigation_markup( $template ) {
		$template = '
		<nav class="navigation %1$s" role="navigation" aria-label="%4$s">
			<h2 class="screen-reader-text">%2$s</h2>
			<div class="nav-links">%3$s</div>
		</nav>';

		return $template;
	}

	/**
	 * Modify nav menu arguments
	 *
	 * @param array $args Nav menu arguments.
	 * @return array
	 */
	public function modify_nav_menu_args( $args ) {
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

	/**
	 * Modify widget title
	 *
	 * @param string $title Widget title.
	 * @return string
	 */
	public function modify_widget_title( $title ) {
		if ( ! empty( $title ) ) {
			return $title;
		}
		return $title;
	}

	/**
	 * Register image sizes
	 *
	 * @return void
	 */
	public function register_image_sizes() {
		// Add custom image sizes.
		add_image_size( 'aqualuxe-featured', 1200, 600, true );
		add_image_size( 'aqualuxe-blog', 800, 450, true );
		add_image_size( 'aqualuxe-blog-grid', 600, 400, true );
		add_image_size( 'aqualuxe-blog-list', 400, 300, true );
		add_image_size( 'aqualuxe-product-thumbnail', 300, 300, true );
		add_image_size( 'aqualuxe-product-gallery', 800, 800, true );
	}

	/**
	 * Get content layout
	 *
	 * @return string
	 */
	public function get_content_layout() {
		$default_layout = $this->get_theme_option( 'aqualuxe_content_layout', 'right-sidebar' );

		// Check if we're on a single post.
		if ( is_single() ) {
			$layout = $this->get_theme_option( 'aqualuxe_single_post_layout', $default_layout );
		} elseif ( is_page() ) {
			// Check if we're on a page.
			$layout = $this->get_theme_option( 'aqualuxe_page_layout', 'no-sidebar' );
		} elseif ( is_archive() || is_home() || is_search() ) {
			// Check if we're on an archive page.
			$layout = $this->get_theme_option( 'aqualuxe_archive_layout', $default_layout );
		} else {
			// Default layout.
			$layout = $default_layout;
		}

		// Check if WooCommerce is active and we're on a WooCommerce page.
		if ( $this->is_woocommerce_active() && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
			if ( is_shop() || is_product_category() || is_product_tag() ) {
				$layout = $this->get_theme_option( 'aqualuxe_shop_layout', $default_layout );
			} elseif ( is_product() ) {
				$layout = $this->get_theme_option( 'aqualuxe_product_layout', $default_layout );
			}
		}

		return $layout;
	}

	/**
	 * Check if dark mode is enabled
	 *
	 * @return bool
	 */
	public function is_dark_mode_enabled() {
		// Check if dark mode is enabled in the customizer.
		$dark_mode_enabled = $this->get_theme_option( 'aqualuxe_enable_dark_mode', true );
		
		// If dark mode is not enabled in the customizer, return false.
		if ( ! $dark_mode_enabled ) {
			return false;
		}

		// Check if the user has set a preference.
		if ( isset( $_COOKIE['aqualuxe_dark_mode'] ) ) {
			return 'true' === $_COOKIE['aqualuxe_dark_mode'];
		}

		// Check if the user's system prefers dark mode.
		$dark_mode_default = $this->get_theme_option( 'aqualuxe_dark_mode_default', 'auto' );
		
		if ( 'dark' === $dark_mode_default ) {
			return true;
		} elseif ( 'light' === $dark_mode_default ) {
			return false;
		}

		// Default to auto (system preference).
		return false;
	}

	/**
	 * Get post thumbnail with responsive srcset
	 *
	 * @param int    $post_id Post ID.
	 * @param string $size Image size.
	 * @param array  $attr Image attributes.
	 * @return string
	 */
	public function get_responsive_post_thumbnail( $post_id = null, $size = 'post-thumbnail', $attr = array() ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		if ( ! has_post_thumbnail( $post_id ) ) {
			return '';
		}

		$thumbnail_id = get_post_thumbnail_id( $post_id );
		$image_meta = wp_get_attachment_metadata( $thumbnail_id );

		if ( ! $image_meta ) {
			return '';
		}

		// Set default attributes.
		$default_attr = array(
			'class' => 'responsive-image',
			'loading' => 'lazy',
		);

		$attr = wp_parse_args( $attr, $default_attr );

		// Get the image.
		$image = wp_get_attachment_image( $thumbnail_id, $size, false, $attr );

		return $image;
	}

	/**
	 * Get breadcrumbs
	 *
	 * @return string
	 */
	public function get_breadcrumbs() {
		if ( ! $this->get_theme_option( 'aqualuxe_enable_breadcrumbs', true ) ) {
			return '';
		}

		$breadcrumbs = array();
		$breadcrumbs[] = array(
			'title' => __( 'Home', 'aqualuxe' ),
			'url' => home_url( '/' ),
		);

		if ( is_category() || is_single() ) {
			$category = get_the_category();
			if ( ! empty( $category ) ) {
				$breadcrumbs[] = array(
					'title' => $category[0]->name,
					'url' => get_category_link( $category[0]->term_id ),
				);
			}
		}

		if ( is_single() ) {
			$breadcrumbs[] = array(
				'title' => get_the_title(),
				'url' => '',
			);
		}

		if ( is_page() ) {
			$breadcrumbs[] = array(
				'title' => get_the_title(),
				'url' => '',
			);
		}

		if ( is_tag() ) {
			$breadcrumbs[] = array(
				'title' => single_tag_title( '', false ),
				'url' => '',
			);
		}

		if ( is_author() ) {
			$breadcrumbs[] = array(
				'title' => get_the_author(),
				'url' => '',
			);
		}

		if ( is_year() ) {
			$breadcrumbs[] = array(
				'title' => get_the_time( 'Y' ),
				'url' => '',
			);
		}

		if ( is_month() ) {
			$breadcrumbs[] = array(
				'title' => get_the_time( 'F Y' ),
				'url' => '',
			);
		}

		if ( is_day() ) {
			$breadcrumbs[] = array(
				'title' => get_the_time( 'F j, Y' ),
				'url' => '',
			);
		}

		if ( is_search() ) {
			$breadcrumbs[] = array(
				'title' => __( 'Search Results', 'aqualuxe' ),
				'url' => '',
			);
		}

		if ( is_404() ) {
			$breadcrumbs[] = array(
				'title' => __( '404', 'aqualuxe' ),
				'url' => '',
			);
		}

		// Check if WooCommerce is active and we're on a WooCommerce page.
		if ( $this->is_woocommerce_active() && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
			// Remove all breadcrumbs and let WooCommerce handle it.
			return '';
		}

		// Build the breadcrumbs HTML.
		$html = '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'aqualuxe' ) . '">';
		$html .= '<ol class="breadcrumbs-list flex flex-wrap items-center">';

		foreach ( $breadcrumbs as $index => $breadcrumb ) {
			$is_last = $index === count( $breadcrumbs ) - 1;

			$html .= '<li class="breadcrumbs-item flex items-center">';

			if ( ! empty( $breadcrumb['url'] ) ) {
				$html .= '<a href="' . esc_url( $breadcrumb['url'] ) . '" class="breadcrumbs-link">' . esc_html( $breadcrumb['title'] ) . '</a>';
			} else {
				$html .= '<span class="breadcrumbs-text">' . esc_html( $breadcrumb['title'] ) . '</span>';
			}

			if ( ! $is_last ) {
				$html .= '<span class="breadcrumbs-separator mx-2" aria-hidden="true">/</span>';
			}

			$html .= '</li>';
		}

		$html .= '</ol>';
		$html .= '</nav>';

		return $html;
	}
}