<?php
/**
 * AquaLuxe Assets Management
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
 * Assets Management Class
 *
 * Handles all theme assets including scripts, styles, and fonts.
 *
 * @since 1.1.0
 */
class Assets {

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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
		add_action( 'wp_head', array( $this, 'preload_assets' ), 1 );
		add_action( 'wp_head', array( $this, 'add_critical_css' ), 1 );
		add_filter( 'script_loader_tag', array( $this, 'add_script_attributes' ), 10, 3 );
	}

	/**
	 * Enqueue styles.
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		// Register Google Fonts.
		wp_register_style(
			'aqualuxe-fonts',
			$this->get_google_fonts_url(),
			array(),
			AQUALUXE_VERSION
		);

		// Register main stylesheet.
		wp_register_style(
			'aqualuxe-style',
			get_stylesheet_uri(),
			array( 'aqualuxe-fonts' ),
			AQUALUXE_VERSION
		);

		// Register main CSS.
		wp_register_style(
			'aqualuxe-main',
			AQUALUXE_URI . '/assets/css/main.css',
			array( 'aqualuxe-style' ),
			AQUALUXE_VERSION
		);

		// Register dark mode styles.
		wp_register_style(
			'aqualuxe-dark-mode',
			AQUALUXE_URI . '/assets/css/dark-mode.css',
			array( 'aqualuxe-main' ),
			AQUALUXE_VERSION
		);

		// Register WooCommerce styles if WooCommerce is active.
		if ( class_exists( 'WooCommerce' ) ) {
			wp_register_style(
				'aqualuxe-woocommerce',
				AQUALUXE_URI . '/assets/css/woocommerce.css',
				array( 'aqualuxe-main' ),
				AQUALUXE_VERSION
			);
		}

		// Enqueue styles.
		wp_enqueue_style( 'aqualuxe-fonts' );
		wp_enqueue_style( 'aqualuxe-style' );
		wp_enqueue_style( 'aqualuxe-main' );
		wp_enqueue_style( 'aqualuxe-dark-mode' );

		// Enqueue WooCommerce styles if WooCommerce is active.
		if ( class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
			wp_enqueue_style( 'aqualuxe-woocommerce' );
		}

		// Add RTL support.
		wp_style_add_data( 'aqualuxe-style', 'rtl', 'replace' );
		wp_style_add_data( 'aqualuxe-main', 'rtl', 'replace' );

		// Add print styles.
		wp_enqueue_style(
			'aqualuxe-print',
			AQUALUXE_URI . '/assets/css/print.css',
			array( 'aqualuxe-style' ),
			AQUALUXE_VERSION,
			'print'
		);
	}

	/**
	 * Enqueue scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		// Register navigation script.
		wp_register_script(
			'aqualuxe-navigation',
			AQUALUXE_URI . '/assets/js/navigation.js',
			array(),
			AQUALUXE_VERSION,
			true
		);

		// Register dark mode script.
		wp_register_script(
			'aqualuxe-dark-mode',
			AQUALUXE_URI . '/assets/js/dark-mode.js',
			array(),
			AQUALUXE_VERSION,
			true
		);

		// Register custom scripts.
		wp_register_script(
			'aqualuxe-custom',
			AQUALUXE_URI . '/assets/js/custom.js',
			array( 'jquery' ),
			AQUALUXE_VERSION,
			true
		);
		
		// Register keyboard navigation script.
		wp_register_script(
			'aqualuxe-keyboard-navigation',
			AQUALUXE_URI . '/assets/js/keyboard-navigation.js',
			array( 'jquery' ),
			AQUALUXE_VERSION,
			true
		);
		
		// Register focus-visible polyfill.
		wp_register_script(
			'aqualuxe-focus-visible',
			AQUALUXE_URI . '/assets/js/focus-visible.min.js',
			array(),
			'5.2.0',
			true
		);
		
		// Register high contrast mode script.
		wp_register_script(
			'aqualuxe-high-contrast-mode',
			AQUALUXE_URI . '/assets/js/high-contrast-mode.js',
			array( 'jquery' ),
			AQUALUXE_VERSION,
			true
		);
		
		// Register screen reader announcements script.
		wp_register_script(
			'aqualuxe-screen-reader-announcements',
			AQUALUXE_URI . '/assets/js/screen-reader-announcements.js',
			array( 'jquery' ),
			AQUALUXE_VERSION,
			true
		);
		
		// Register WooCommerce accessibility script.
		if ( class_exists( 'WooCommerce' ) ) {
			wp_register_script(
				'aqualuxe-woocommerce-accessibility',
				AQUALUXE_URI . '/assets/js/woocommerce-accessibility.js',
				array( 'jquery' ),
				AQUALUXE_VERSION,
				true
			);
		}

		// Enqueue scripts.
		wp_enqueue_script( 'aqualuxe-navigation' );
		wp_enqueue_script( 'aqualuxe-dark-mode' );
		wp_enqueue_script( 'aqualuxe-custom' );
		wp_enqueue_script( 'aqualuxe-keyboard-navigation' );
		wp_enqueue_script( 'aqualuxe-focus-visible' );
		wp_enqueue_script( 'aqualuxe-high-contrast-mode' );
		wp_enqueue_script( 'aqualuxe-screen-reader-announcements' );
		
		// Enqueue WooCommerce accessibility script if WooCommerce is active.
		if ( class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
			wp_enqueue_script( 'aqualuxe-woocommerce-accessibility' );
		}

		// Localize scripts.
		wp_localize_script(
			'aqualuxe-custom',
			'aqualuxeSettings',
			array(
				'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
				'themeUri'   => AQUALUXE_URI,
				'darkMode'   => get_theme_mod( 'aqualuxe_dark_mode_default', false ),
				'i18n'       => array(
					'searchPlaceholder' => esc_html__( 'Search...', 'aqualuxe' ),
					'menuToggle'        => esc_html__( 'Menu', 'aqualuxe' ),
					'darkModeToggle'    => esc_html__( 'Toggle Dark Mode', 'aqualuxe' ),
					'highContrastToggle' => esc_html__( 'Toggle High Contrast Mode', 'aqualuxe' ),
				),
			)
		);

		// Add comment reply script if needed.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Enqueue editor assets.
	 *
	 * @return void
	 */
	public function enqueue_editor_assets() {
		// Enqueue editor styles.
		wp_enqueue_style(
			'aqualuxe-editor-style',
			AQUALUXE_URI . '/assets/css/editor.css',
			array(),
			AQUALUXE_VERSION
		);

		// Enqueue editor scripts.
		wp_enqueue_script(
			'aqualuxe-editor-script',
			AQUALUXE_URI . '/assets/js/editor.js',
			array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
			AQUALUXE_VERSION,
			true
		);
		
		// Enqueue accessibility checker script.
		wp_enqueue_script(
			'aqualuxe-accessibility-checker',
			AQUALUXE_URI . '/assets/js/accessibility-checker.js',
			array( 'jquery' ),
			AQUALUXE_VERSION,
			true
		);
	}

	/**
	 * Preload critical assets.
	 *
	 * @return void
	 */
	public function preload_assets() {
		?>
		<!-- Preload critical assets -->
		<link rel="preload" href="<?php echo esc_url( get_stylesheet_uri() ); ?>" as="style">
		<link rel="preload" href="<?php echo esc_url( AQUALUXE_URI . '/assets/css/main.css' ); ?>" as="style">
		<link rel="preload" href="<?php echo esc_url( AQUALUXE_URI . '/assets/js/navigation.js' ); ?>" as="script">
		<link rel="preload" href="<?php echo esc_url( AQUALUXE_URI . '/assets/js/dark-mode.js' ); ?>" as="script">
		<link rel="preload" href="<?php echo esc_url( AQUALUXE_URI . '/assets/js/keyboard-navigation.js' ); ?>" as="script">
		<link rel="preload" href="<?php echo esc_url( AQUALUXE_URI . '/assets/js/focus-visible.min.js' ); ?>" as="script">
		<?php
	}

	/**
	 * Add critical CSS inline.
	 *
	 * @return void
	 */
	public function add_critical_css() {
		// Get the appropriate critical CSS based on the current page.
		$css_file = $this->get_critical_css_file();
		
		if ( file_exists( $css_file ) ) {
			$css = file_get_contents( $css_file );
			echo '<style id="aqualuxe-critical-css">' . wp_strip_all_tags( $css ) . '</style>';
		} else {
			// Fallback critical CSS
			?>
			<!-- Critical CSS -->
			<style id="aqualuxe-critical-css">
				/* Base styles for immediate rendering */
				:root {
					--primary-color: #0077b6;
					--secondary-color: #00b4d8;
					--accent-color: #90e0ef;
					--text-color: #333333;
					--background-color: #ffffff;
					--light-background: #f8f9fa;
					--border-color: #e0e0e0;
					--font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
					--heading-font-family: 'Playfair Display', Georgia, serif;
				}
				
				body {
					margin: 0;
					padding: 0;
					font-family: var(--font-family);
					color: var(--text-color);
					background-color: var(--background-color);
					line-height: 1.6;
					font-size: 16px;
				}
				
				.site-header {
					padding: 1rem 0;
					background-color: var(--background-color);
					box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
					position: relative;
					z-index: 100;
				}
				
				.site-content {
					min-height: 50vh;
					padding: 2rem 0;
				}
				
				.site-footer {
					padding: 2rem 0;
					background-color: var(--light-background);
					border-top: 1px solid var(--border-color);
				}
				
				.dark-mode {
					--text-color: #f0f0f0;
					--background-color: #121212;
					--light-background: #1e1e1e;
					--border-color: #333333;
				}
				
				.dark-mode .site-header,
				.dark-mode .site-footer {
					background-color: var(--background-color);
					border-color: var(--border-color);
				}
				
				/* Skip link styles */
				.skip-link {
					position: absolute;
					top: -100px;
					left: 0;
					background: var(--primary-color);
					color: var(--light-text-color);
					padding: 10px;
					z-index: 100;
				}
				
				.skip-link:focus {
					top: 0;
				}
				
				/* Screen reader text */
				.screen-reader-text {
					border: 0;
					clip: rect(1px, 1px, 1px, 1px);
					clip-path: inset(50%);
					height: 1px;
					margin: -1px;
					overflow: hidden;
					padding: 0;
					position: absolute !important;
					width: 1px;
					word-wrap: normal !important;
				}
			</style>
			<?php
		}
	}

	/**
	 * Get the appropriate critical CSS file based on the current page.
	 *
	 * @return string Path to the critical CSS file.
	 */
	private function get_critical_css_file() {
		$device = wp_is_mobile() ? 'mobile' : 'desktop';
		$base_path = AQUALUXE_DIR . '/assets/css/critical/';
		
		if ( is_front_page() ) {
			return $base_path . "front-page.{$device}.css";
		} elseif ( is_singular( 'post' ) || is_page() ) {
			return $base_path . "blog.{$device}.css";
		} elseif ( is_archive() || is_search() || is_home() ) {
			return $base_path . "blog.{$device}.css";
		} elseif ( function_exists( 'is_product' ) && is_product() ) {
			return $base_path . "woocommerce-single.{$device}.css";
		} elseif ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
			return $base_path . "woocommerce.{$device}.css";
		}
		
		// Default critical CSS
		return $base_path . "front-page.{$device}.css";
	}

	/**
	 * Add async/defer attributes to scripts.
	 *
	 * @param string $tag    The script tag.
	 * @param string $handle The script handle.
	 * @param string $src    The script source.
	 * @return string
	 */
	public function add_script_attributes( $tag, $handle, $src ) {
		// Add async attribute to non-critical scripts.
		$async_scripts = array(
			'aqualuxe-custom',
			'aqualuxe-high-contrast-mode',
			'aqualuxe-screen-reader-announcements',
			'aqualuxe-woocommerce-accessibility',
		);

		// Add defer attribute to critical scripts.
		$defer_scripts = array(
			'aqualuxe-navigation',
			'aqualuxe-dark-mode',
			'aqualuxe-keyboard-navigation',
			'aqualuxe-focus-visible',
		);

		if ( in_array( $handle, $async_scripts, true ) ) {
			return str_replace( ' src', ' async src', $tag );
		}

		if ( in_array( $handle, $defer_scripts, true ) ) {
			return str_replace( ' src', ' defer src', $tag );
		}

		return $tag;
	}

	/**
	 * Get Google Fonts URL.
	 *
	 * @return string
	 */
	private function get_google_fonts_url() {
		$fonts_url = '';
		$fonts     = array();
		$subsets   = 'latin,latin-ext';

		/* translators: If there are characters in your language that are not supported by Poppins, translate this to 'off'. Do not translate into your own language. */
		if ( 'off' !== _x( 'on', 'Poppins font: on or off', 'aqualuxe' ) ) {
			$fonts[] = 'Poppins:wght@400;500;600;700';
		}

		/* translators: If there are characters in your language that are not supported by Playfair Display, translate this to 'off'. Do not translate into your own language. */
		if ( 'off' !== _x( 'on', 'Playfair Display font: on or off', 'aqualuxe' ) ) {
			$fonts[] = 'Playfair Display:wght@400;500;600;700';
		}

		if ( $fonts ) {
			$fonts_url = add_query_arg(
				array(
					'family'  => implode( '&family=', $fonts ),
					'display' => 'swap',
					'subset'  => $subsets,
				),
				'https://fonts.googleapis.com/css2'
			);
		}

		return apply_filters( 'aqualuxe_google_fonts_url', $fonts_url );
	}
}