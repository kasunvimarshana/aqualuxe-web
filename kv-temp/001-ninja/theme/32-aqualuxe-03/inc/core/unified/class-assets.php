<?php
/**
 * AquaLuxe Assets Class
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
 * Assets Class
 *
 * This class handles all asset loading for the theme.
 * It provides a unified system for registering and enqueuing scripts and styles.
 *
 * @since 1.2.0
 */
class Assets {

	/**
	 * The single instance of this class.
	 *
	 * @var Assets
	 */
	private static $instance = null;

	/**
	 * Registered scripts.
	 *
	 * @var array
	 */
	private $scripts = array();

	/**
	 * Registered styles.
	 *
	 * @var array
	 */
	private $styles = array();

	/**
	 * Script localizations.
	 *
	 * @var array
	 */
	private $localizations = array();

	/**
	 * Constructor.
	 */
	private function __construct() {
		// Add hooks.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'block_editor_assets' ) );
		add_action( 'wp_head', array( $this, 'add_preload_tags' ) );
	}

	/**
	 * Get the single instance of this class.
	 *
	 * @return Assets
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register a script.
	 *
	 * @param string  $handle    Script handle.
	 * @param string  $src       Script source.
	 * @param array   $deps      Script dependencies.
	 * @param string  $ver       Script version.
	 * @param boolean $in_footer Whether to enqueue in footer.
	 * @return void
	 */
	public function register_script( $handle, $src, $deps = array(), $ver = false, $in_footer = true ) {
		$this->scripts[ $handle ] = array(
			'src'       => $src,
			'deps'      => $deps,
			'ver'       => $ver ? $ver : AQUALUXE_VERSION,
			'in_footer' => $in_footer,
		);
	}

	/**
	 * Register a style.
	 *
	 * @param string $handle Script handle.
	 * @param string $src    Script source.
	 * @param array  $deps   Script dependencies.
	 * @param string $ver    Script version.
	 * @param string $media  Media.
	 * @return void
	 */
	public function register_style( $handle, $src, $deps = array(), $ver = false, $media = 'all' ) {
		$this->styles[ $handle ] = array(
			'src'   => $src,
			'deps'  => $deps,
			'ver'   => $ver ? $ver : AQUALUXE_VERSION,
			'media' => $media,
		);
	}

	/**
	 * Add script localization.
	 *
	 * @param string $handle Script handle.
	 * @param string $name   Object name.
	 * @param array  $data   Data to localize.
	 * @return void
	 */
	public function add_localization( $handle, $name, $data ) {
		$this->localizations[ $handle ][] = array(
			'name' => $name,
			'data' => $data,
		);
	}

	/**
	 * Enqueue scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		// Register and enqueue core scripts.
		wp_register_script(
			'aqualuxe-navigation',
			AQUALUXE_URI . '/assets/js/navigation.js',
			array(),
			AQUALUXE_VERSION,
			true
		);

		wp_register_script(
			'aqualuxe-skip-link-focus-fix',
			AQUALUXE_URI . '/assets/js/skip-link-focus-fix.js',
			array(),
			AQUALUXE_VERSION,
			true
		);

		wp_enqueue_script( 'aqualuxe-navigation' );
		wp_enqueue_script( 'aqualuxe-skip-link-focus-fix' );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Register and enqueue custom scripts.
		wp_register_script(
			'aqualuxe-scripts',
			AQUALUXE_URI . '/assets/js/scripts.js',
			array( 'jquery' ),
			AQUALUXE_VERSION,
			true
		);

		wp_localize_script(
			'aqualuxe-scripts',
			'aqualuxeSettings',
			array(
				'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
				'nonce'          => wp_create_nonce( 'aqualuxe-nonce' ),
				'darkModeToggle' => get_theme_mod( 'aqualuxe_enable_dark_mode', true ),
				'isRTL'          => is_rtl(),
			)
		);

		wp_enqueue_script( 'aqualuxe-scripts' );

		// Enqueue registered scripts.
		foreach ( $this->scripts as $handle => $script ) {
			wp_register_script(
				$handle,
				$script['src'],
				$script['deps'],
				$script['ver'],
				$script['in_footer']
			);

			wp_enqueue_script( $handle );

			// Add localizations if any.
			if ( isset( $this->localizations[ $handle ] ) ) {
				foreach ( $this->localizations[ $handle ] as $localization ) {
					wp_localize_script(
						$handle,
						$localization['name'],
						$localization['data']
					);
				}
			}
		}
	}

	/**
	 * Enqueue styles.
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		// Register and enqueue core styles.
		wp_register_style(
			'aqualuxe-style',
			get_stylesheet_uri(),
			array(),
			AQUALUXE_VERSION
		);

		wp_register_style(
			'aqualuxe-main',
			AQUALUXE_URI . '/assets/css/main.css',
			array(),
			AQUALUXE_VERSION
		);

		wp_enqueue_style( 'aqualuxe-style' );
		wp_enqueue_style( 'aqualuxe-main' );

		// Add RTL support.
		wp_style_add_data( 'aqualuxe-style', 'rtl', 'replace' );
		wp_style_add_data( 'aqualuxe-main', 'rtl', 'replace' );

		// Enqueue registered styles.
		foreach ( $this->styles as $handle => $style ) {
			wp_register_style(
				$handle,
				$style['src'],
				$style['deps'],
				$style['ver'],
				$style['media']
			);

			wp_enqueue_style( $handle );
		}
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		wp_register_script(
			'aqualuxe-admin',
			AQUALUXE_URI . '/assets/js/admin.js',
			array( 'jquery' ),
			AQUALUXE_VERSION,
			true
		);

		wp_localize_script(
			'aqualuxe-admin',
			'aqualuxeAdmin',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'aqualuxe-admin-nonce' ),
			)
		);

		wp_enqueue_script( 'aqualuxe-admin' );
	}

	/**
	 * Enqueue admin styles.
	 *
	 * @return void
	 */
	public function admin_enqueue_styles() {
		wp_register_style(
			'aqualuxe-admin',
			AQUALUXE_URI . '/assets/css/admin.css',
			array(),
			AQUALUXE_VERSION
		);

		wp_enqueue_style( 'aqualuxe-admin' );
	}

	/**
	 * Enqueue block editor assets.
	 *
	 * @return void
	 */
	public function block_editor_assets() {
		wp_register_script(
			'aqualuxe-editor',
			AQUALUXE_URI . '/assets/js/editor.js',
			array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
			AQUALUXE_VERSION,
			true
		);

		wp_register_style(
			'aqualuxe-editor-style',
			AQUALUXE_URI . '/assets/css/editor-style.css',
			array(),
			AQUALUXE_VERSION
		);

		wp_enqueue_script( 'aqualuxe-editor' );
		wp_enqueue_style( 'aqualuxe-editor-style' );
	}

	/**
	 * Add preload tags for critical resources.
	 *
	 * @return void
	 */
	public function add_preload_tags() {
		echo '<link rel="preload" href="' . esc_url( AQUALUXE_URI . '/assets/fonts/main-font.woff2' ) . '" as="font" type="font/woff2" crossorigin>';
		echo '<link rel="preload" href="' . esc_url( AQUALUXE_URI . '/assets/css/critical.css' ) . '" as="style">';
		
		// Add critical CSS inline.
		$critical_css_file = AQUALUXE_DIR . '/assets/css/critical.css';
		if ( file_exists( $critical_css_file ) ) {
			echo '<style id="aqualuxe-critical-css">';
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			echo file_get_contents( $critical_css_file );
			echo '</style>';
		}
	}
}