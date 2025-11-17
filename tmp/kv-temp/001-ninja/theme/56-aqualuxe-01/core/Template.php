<?php
/**
 * AquaLuxe Theme Template
 *
 * This file contains the Template class for the AquaLuxe theme.
 *
 * @package AquaLuxe\Core
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Template class.
 */
class Template {
	/**
	 * Constructor.
	 */
	public function __construct() {
		// Register hooks.
		add_filter( 'template_include', [ $this, 'template_include' ] );
		add_filter( 'body_class', [ $this, 'body_classes' ] );
		add_filter( 'post_class', [ $this, 'post_classes' ] );
		add_action( 'wp_head', [ $this, 'add_meta_tags' ] );
		add_filter( 'get_custom_logo', [ $this, 'custom_logo_html' ] );
		add_filter( 'excerpt_length', [ $this, 'excerpt_length' ] );
		add_filter( 'excerpt_more', [ $this, 'excerpt_more' ] );
		add_filter( 'nav_menu_css_class', [ $this, 'nav_menu_css_class' ], 10, 4 );
		add_filter( 'nav_menu_link_attributes', [ $this, 'nav_menu_link_attributes' ], 10, 4 );
	}

	/**
	 * Filter the template include.
	 *
	 * @param string $template The template path.
	 * @return string The modified template path.
	 */
	public function template_include( $template ) {
		// Check if we're using a custom template.
		$custom_template = $this->get_custom_template();
		if ( $custom_template ) {
			return $custom_template;
		}

		return $template;
	}

	/**
	 * Get custom template.
	 *
	 * @return string|bool The custom template path or false.
	 */
	private function get_custom_template() {
		// Check if we're using a custom page template.
		if ( is_page() ) {
			$page_template = get_page_template_slug();
			if ( $page_template ) {
				$template_path = AQUALUXE_TEMPLATES_DIR . 'pages/' . $page_template;
				if ( file_exists( $template_path ) ) {
					return $template_path;
				}
			}
		}

		// Check for custom post type templates.
		if ( is_singular() && ! is_singular( 'post' ) && ! is_singular( 'page' ) ) {
			$post_type = get_post_type();
			$template_path = AQUALUXE_TEMPLATES_DIR . 'singular/' . $post_type . '.php';
			if ( file_exists( $template_path ) ) {
				return $template_path;
			}
		}

		// Check for custom archive templates.
		if ( is_archive() && ! is_category() && ! is_tag() && ! is_author() && ! is_date() ) {
			$post_type = get_post_type();
			$template_path = AQUALUXE_TEMPLATES_DIR . 'archive/' . $post_type . '.php';
			if ( file_exists( $template_path ) ) {
				return $template_path;
			}
		}

		return false;
	}

	/**
	 * Add custom body classes.
	 *
	 * @param array $classes The body classes.
	 * @return array The modified body classes.
	 */
	public function body_classes( $classes ) {
		// Add a class for the page layout.
		$layout = $this->get_page_layout();
		$classes[] = 'layout-' . $layout;

		// Add a class for the header style.
		$header_style = get_theme_mod( 'aqualuxe_header_style', 'default' );
		$classes[] = 'header-style-' . $header_style;

		// Add a class for the footer style.
		$footer_style = get_theme_mod( 'aqualuxe_footer_style', 'default' );
		$classes[] = 'footer-style-' . $footer_style;

		// Add a class for the color scheme.
		$color_scheme = get_theme_mod( 'aqualuxe_color_scheme', 'default' );
		$classes[] = 'color-scheme-' . $color_scheme;

		// Add a class for the typography.
		$typography = get_theme_mod( 'aqualuxe_typography', 'default' );
		$classes[] = 'typography-' . $typography;

		// Add a class for the container width.
		$container_width = get_theme_mod( 'aqualuxe_container_width', 'default' );
		$classes[] = 'container-width-' . $container_width;

		// Add a class for the sidebar position.
		$sidebar_position = get_theme_mod( 'aqualuxe_sidebar_position', 'right' );
		$classes[] = 'sidebar-' . $sidebar_position;

		// Add a class if the page has a featured image.
		if ( is_singular() && has_post_thumbnail() ) {
			$classes[] = 'has-featured-image';
		}

		// Add a class if the page has a sidebar.
		if ( $this->has_sidebar() ) {
			$classes[] = 'has-sidebar';
		} else {
			$classes[] = 'no-sidebar';
		}

		// Add a class for the current template.
		$template = get_page_template_slug();
		if ( $template ) {
			$classes[] = 'template-' . sanitize_html_class( str_replace( '.php', '', $template ) );
		}

		return $classes;
	}

	/**
	 * Add custom post classes.
	 *
	 * @param array $classes The post classes.
	 * @return array The modified post classes.
	 */
	public function post_classes( $classes ) {
		// Add a class for the post format.
		$post_format = get_post_format() ?: 'standard';
		$classes[] = 'format-' . $post_format;

		// Add a class if the post has a featured image.
		if ( has_post_thumbnail() ) {
			$classes[] = 'has-thumbnail';
		} else {
			$classes[] = 'no-thumbnail';
		}

		// Add a class for the post layout.
		$post_layout = get_post_meta( get_the_ID(), 'aqualuxe_post_layout', true ) ?: 'default';
		$classes[] = 'layout-' . $post_layout;

		return $classes;
	}

	/**
	 * Add meta tags to the head.
	 */
	public function add_meta_tags() {
		// Add viewport meta tag.
		echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">';

		// Add theme color meta tag.
		$primary_color = get_theme_mod( 'aqualuxe_primary_color', '#0072B5' );
		echo '<meta name="theme-color" content="' . esc_attr( $primary_color ) . '">';

		// Add Open Graph meta tags.
		if ( is_singular() ) {
			echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '">';
			echo '<meta property="og:type" content="article">';
			echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '">';
			
			if ( has_post_thumbnail() ) {
				$thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
				echo '<meta property="og:image" content="' . esc_url( $thumbnail_url ) . '">';
			}
			
			echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">';
			
			$excerpt = get_the_excerpt();
			if ( ! empty( $excerpt ) ) {
				echo '<meta property="og:description" content="' . esc_attr( $excerpt ) . '">';
			}
		} else {
			echo '<meta property="og:title" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">';
			echo '<meta property="og:type" content="website">';
			echo '<meta property="og:url" content="' . esc_url( home_url( '/' ) ) . '">';
			
			$custom_logo_id = get_theme_mod( 'custom_logo' );
			if ( $custom_logo_id ) {
				$logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
				if ( $logo_url ) {
					echo '<meta property="og:image" content="' . esc_url( $logo_url ) . '">';
				}
			}
			
			echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">';
			echo '<meta property="og:description" content="' . esc_attr( get_bloginfo( 'description' ) ) . '">';
		}

		// Add Twitter Card meta tags.
		echo '<meta name="twitter:card" content="summary_large_image">';
		echo '<meta name="twitter:title" content="' . esc_attr( is_singular() ? get_the_title() : get_bloginfo( 'name' ) ) . '">';
		
		if ( is_singular() && has_post_thumbnail() ) {
			$thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
			echo '<meta name="twitter:image" content="' . esc_url( $thumbnail_url ) . '">';
		} elseif ( $custom_logo_id = get_theme_mod( 'custom_logo' ) ) {
			$logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
			if ( $logo_url ) {
				echo '<meta name="twitter:image" content="' . esc_url( $logo_url ) . '">';
			}
		}
		
		echo '<meta name="twitter:description" content="' . esc_attr( is_singular() ? get_the_excerpt() : get_bloginfo( 'description' ) ) . '">';
	}

	/**
	 * Customize the logo HTML.
	 *
	 * @param string $html The logo HTML.
	 * @return string The modified logo HTML.
	 */
	public function custom_logo_html( $html ) {
		// Add custom classes to the logo.
		$html = str_replace( 'custom-logo-link', 'custom-logo-link site-logo', $html );
		$html = str_replace( 'custom-logo', 'custom-logo site-logo-img', $html );

		// Add schema.org markup.
		$html = str_replace( '<a', '<a itemprop="url"', $html );
		$html = str_replace( '<img', '<img itemprop="logo"', $html );

		return $html;
	}

	/**
	 * Customize the excerpt length.
	 *
	 * @param int $length The excerpt length.
	 * @return int The modified excerpt length.
	 */
	public function excerpt_length( $length ) {
		// Get the excerpt length from the theme options.
		$excerpt_length = get_theme_mod( 'aqualuxe_excerpt_length', 55 );

		return $excerpt_length;
	}

	/**
	 * Customize the excerpt more text.
	 *
	 * @param string $more The excerpt more text.
	 * @return string The modified excerpt more text.
	 */
	public function excerpt_more( $more ) {
		// Get the excerpt more text from the theme options.
		$excerpt_more = get_theme_mod( 'aqualuxe_excerpt_more', '&hellip;' );

		return $excerpt_more;
	}

	/**
	 * Add custom CSS classes to menu items.
	 *
	 * @param array    $classes The menu item classes.
	 * @param WP_Post  $item    The menu item.
	 * @param stdClass $args    The menu args.
	 * @param int      $depth   The menu depth.
	 * @return array The modified menu item classes.
	 */
	public function nav_menu_css_class( $classes, $item, $args, $depth ) {
		// Add custom classes based on menu location.
		if ( 'primary' === $args->theme_location ) {
			$classes[] = 'primary-menu-item';
			
			if ( 0 === $depth ) {
				$classes[] = 'primary-menu-item-level-0';
			}
		} elseif ( 'secondary' === $args->theme_location ) {
			$classes[] = 'secondary-menu-item';
			
			if ( 0 === $depth ) {
				$classes[] = 'secondary-menu-item-level-0';
			}
		} elseif ( 'footer' === $args->theme_location ) {
			$classes[] = 'footer-menu-item';
			
			if ( 0 === $depth ) {
				$classes[] = 'footer-menu-item-level-0';
			}
		} elseif ( 'mobile' === $args->theme_location ) {
			$classes[] = 'mobile-menu-item';
			
			if ( 0 === $depth ) {
				$classes[] = 'mobile-menu-item-level-0';
			}
		}

		// Add custom classes for menu items with children.
		if ( in_array( 'menu-item-has-children', $classes, true ) ) {
			$classes[] = 'has-dropdown';
		}

		return $classes;
	}

	/**
	 * Add custom attributes to menu links.
	 *
	 * @param array    $atts  The menu link attributes.
	 * @param WP_Post  $item  The menu item.
	 * @param stdClass $args  The menu args.
	 * @param int      $depth The menu depth.
	 * @return array The modified menu link attributes.
	 */
	public function nav_menu_link_attributes( $atts, $item, $args, $depth ) {
		// Add custom attributes based on menu location.
		if ( 'primary' === $args->theme_location ) {
			$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' primary-menu-link' : 'primary-menu-link';
		} elseif ( 'secondary' === $args->theme_location ) {
			$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' secondary-menu-link' : 'secondary-menu-link';
		} elseif ( 'footer' === $args->theme_location ) {
			$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' footer-menu-link' : 'footer-menu-link';
		} elseif ( 'mobile' === $args->theme_location ) {
			$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' mobile-menu-link' : 'mobile-menu-link';
		}

		// Add custom attributes for menu items with children.
		if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
			$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' dropdown-toggle' : 'dropdown-toggle';
			$atts['aria-haspopup'] = 'true';
			$atts['aria-expanded'] = 'false';
		}

		return $atts;
	}

	/**
	 * Get the page layout.
	 *
	 * @return string The page layout.
	 */
	public function get_page_layout() {
		// Check if we have a custom layout for the current page.
		if ( is_singular() ) {
			$layout = get_post_meta( get_the_ID(), 'aqualuxe_page_layout', true );
			if ( $layout ) {
				return $layout;
			}
		}

		// Get the default layout from the theme options.
		$layout = get_theme_mod( 'aqualuxe_layout', 'right-sidebar' );

		// Override layout for specific templates.
		if ( is_front_page() ) {
			$layout = get_theme_mod( 'aqualuxe_front_page_layout', 'full-width' );
		} elseif ( is_home() ) {
			$layout = get_theme_mod( 'aqualuxe_blog_layout', 'right-sidebar' );
		} elseif ( is_archive() ) {
			$layout = get_theme_mod( 'aqualuxe_archive_layout', 'right-sidebar' );
		} elseif ( is_search() ) {
			$layout = get_theme_mod( 'aqualuxe_search_layout', 'right-sidebar' );
		} elseif ( is_404() ) {
			$layout = 'full-width';
		}

		// Override layout for WooCommerce templates.
		if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
			if ( is_shop() ) {
				$layout = get_theme_mod( 'aqualuxe_shop_layout', 'left-sidebar' );
			} elseif ( is_product() ) {
				$layout = get_theme_mod( 'aqualuxe_product_layout', 'full-width' );
			} elseif ( is_product_category() || is_product_tag() ) {
				$layout = get_theme_mod( 'aqualuxe_product_archive_layout', 'left-sidebar' );
			}
		}

		return $layout;
	}

	/**
	 * Check if the current page has a sidebar.
	 *
	 * @return bool Whether the current page has a sidebar.
	 */
	public function has_sidebar() {
		$layout = $this->get_page_layout();
		return 'full-width' !== $layout && 'no-sidebar' !== $layout;
	}
}