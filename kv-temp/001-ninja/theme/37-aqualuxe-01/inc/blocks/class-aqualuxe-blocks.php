<?php
/**
 * AquaLuxe Custom Blocks
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AquaLuxe_Blocks class
 */
class AquaLuxe_Blocks {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Register hooks.
		add_action( 'init', array( $this, 'register_block_category' ), 9 );
		add_action( 'init', array( $this, 'register_blocks' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_block_assets' ) );
	}

	/**
	 * Register custom block category
	 */
	public function register_block_category() {
		if ( version_compare( get_bloginfo( 'version' ), '5.8', '>=' ) ) {
			// WP 5.8+.
			add_filter(
				'block_categories_all',
				function( $categories ) {
					return array_merge(
						$categories,
						array(
							array(
								'slug'  => 'aqualuxe',
								'title' => __( 'AquaLuxe', 'aqualuxe' ),
								'icon'  => null,
							),
						)
					);
				}
			);
		} else {
			// Pre WP 5.8.
			add_filter(
				'block_categories',
				function( $categories ) {
					return array_merge(
						$categories,
						array(
							array(
								'slug'  => 'aqualuxe',
								'title' => __( 'AquaLuxe', 'aqualuxe' ),
								'icon'  => null,
							),
						)
					);
				}
			);
		}
	}

	/**
	 * Register custom blocks
	 */
	public function register_blocks() {
		// Check if Gutenberg is active.
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		// Register blocks.
		register_block_type(
			'aqualuxe/feature-box',
			array(
				'editor_script' => 'aqualuxe-editor-script',
				'editor_style'  => 'aqualuxe-editor-style',
				'style'         => 'aqualuxe-style',
			)
		);

		register_block_type(
			'aqualuxe/testimonial',
			array(
				'editor_script' => 'aqualuxe-editor-script',
				'editor_style'  => 'aqualuxe-editor-style',
				'style'         => 'aqualuxe-style',
			)
		);
	}

	/**
	 * Enqueue editor assets
	 */
	public function enqueue_editor_assets() {
		// Enqueue block editor script.
		wp_enqueue_script(
			'aqualuxe-editor-script',
			get_template_directory_uri() . '/assets/js/editor.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-components' ),
			filemtime( get_template_directory() . '/assets/js/editor.js' ),
			true
		);

		// Enqueue block editor styles.
		wp_enqueue_style(
			'aqualuxe-editor-style',
			get_template_directory_uri() . '/assets/css/editor.css',
			array( 'wp-edit-blocks' ),
			filemtime( get_template_directory() . '/assets/css/editor.css' )
		);

		// Add theme settings to editor.
		wp_localize_script(
			'aqualuxe-editor-script',
			'aqualuxeThemeSettings',
			array(
				'primaryColor'   => get_theme_mod( 'aqualuxe_primary_color', '#0073aa' ),
				'secondaryColor' => get_theme_mod( 'aqualuxe_secondary_color', '#23282d' ),
				'accentColor'    => get_theme_mod( 'aqualuxe_accent_color', '#00a0d2' ),
				'bodyFont'       => get_theme_mod( 'aqualuxe_body_font', 'Inter, system-ui, sans-serif' ),
				'headingFont'    => get_theme_mod( 'aqualuxe_heading_font', 'Merriweather, Georgia, serif' ),
			)
		);
	}

	/**
	 * Enqueue block assets for both frontend and backend
	 */
	public function enqueue_block_assets() {
		// Enqueue block styles.
		wp_enqueue_style(
			'aqualuxe-style',
			get_template_directory_uri() . '/assets/css/main.css',
			array(),
			filemtime( get_template_directory() . '/assets/css/main.css' )
		);

		// Add inline styles for custom blocks.
		$custom_css = $this->get_custom_block_styles();
		if ( ! empty( $custom_css ) ) {
			wp_add_inline_style( 'aqualuxe-style', $custom_css );
		}
	}

	/**
	 * Get custom block styles based on theme settings
	 *
	 * @return string Custom CSS.
	 */
	private function get_custom_block_styles() {
		$primary_color   = get_theme_mod( 'aqualuxe_primary_color', '#0073aa' );
		$secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#23282d' );
		$accent_color    = get_theme_mod( 'aqualuxe_accent_color', '#00a0d2' );
		$body_font       = get_theme_mod( 'aqualuxe_body_font', 'Inter, system-ui, sans-serif' );
		$heading_font    = get_theme_mod( 'aqualuxe_heading_font', 'Merriweather, Georgia, serif' );

		$custom_css = "
			/* Custom Block Styles */
			.aqualuxe-feature-box {
				font-family: {$body_font};
			}
			
			.aqualuxe-feature-box h3 {
				font-family: {$heading_font};
			}
			
			.aqualuxe-testimonial {
				font-family: {$body_font};
			}
			
			.aqualuxe-testimonial .testimonial-author {
				font-family: {$heading_font};
			}
			
			/* Block Style Variations */
			.is-style-primary .wp-block-button__link {
				background-color: {$primary_color};
				color: #ffffff;
			}
			
			.is-style-secondary .wp-block-button__link {
				background-color: {$secondary_color};
				color: #ffffff;
			}
			
			.is-style-outline .wp-block-button__link {
				background-color: transparent;
				border: 2px solid {$primary_color};
				color: {$primary_color};
			}
			
			.is-style-link .wp-block-button__link {
				background-color: transparent;
				border: none;
				color: {$primary_color};
				padding-left: 0;
				padding-right: 0;
				text-decoration: underline;
			}
			
			.is-style-underline.wp-block-heading::after {
				content: '';
				display: block;
				width: 50px;
				height: 3px;
				background-color: {$accent_color};
				margin-top: 0.5rem;
			}
			
			.is-style-fancy.wp-block-heading {
				position: relative;
				padding-left: 1rem;
			}
			
			.is-style-fancy.wp-block-heading::before {
				content: '';
				position: absolute;
				left: 0;
				top: 0.25em;
				bottom: 0.25em;
				width: 4px;
				background-color: {$accent_color};
			}
			
			.is-style-rounded.wp-block-image img {
				border-radius: 8px;
			}
			
			.is-style-shadow.wp-block-image img {
				box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
			}
			
			.is-style-card.wp-block-group {
				background-color: #ffffff;
				border-radius: 8px;
				box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
				padding: 2rem;
			}
			
			.is-style-glass.wp-block-group {
				background-color: rgba(255, 255, 255, 0.7);
				backdrop-filter: blur(10px);
				border-radius: 8px;
				padding: 2rem;
			}
			
			.is-style-fancy.wp-block-quote {
				border-left: 4px solid {$accent_color};
				padding-left: 2rem;
				font-style: italic;
			}
			
			.is-style-wide.wp-block-separator {
				height: 3px;
				background-color: {$primary_color};
			}
			
			.is-style-decorative.wp-block-separator {
				height: 1px;
				background-image: linear-gradient(to right, rgba(0, 0, 0, 0), {$accent_color}, rgba(0, 0, 0, 0));
			}
			
			/* Custom Formats */
			.text-highlight {
				background-color: rgba(" . $this->hex_to_rgb( $accent_color ) . ", 0.2);
				padding: 0.2em 0.4em;
				border-radius: 0.2em;
			}
			
			.small-caps {
				font-variant: small-caps;
				letter-spacing: 0.05em;
			}
			
			.drop-cap:first-letter {
				float: left;
				font-family: {$heading_font};
				font-size: 3.5em;
				line-height: 0.8;
				margin-right: 0.1em;
				color: {$primary_color};
			}
		";

		return $custom_css;
	}

	/**
	 * Convert hex color to RGB
	 *
	 * @param string $hex Hex color code.
	 * @return string RGB color values.
	 */
	private function hex_to_rgb( $hex ) {
		$hex = str_replace( '#', '', $hex );

		if ( strlen( $hex ) === 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}

		return $r . ', ' . $g . ', ' . $b;
	}
}

// Initialize the class.
new AquaLuxe_Blocks();