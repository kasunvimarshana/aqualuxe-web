<?php
/**
 * The assets functionality of the theme.
 *
 * @link       https://aqualuxe.pro
 * @since      1.0.0
 *
 * @package    AquaLuxe
 * @subpackage AquaLuxe/inc/core
 */

/**
 * The assets functionality of the theme.
 *
 * @package    AquaLuxe
 * @subpackage AquaLuxe/inc/core
 * @author     Your Name <email@example.com>
 */
class AquaLuxe_Assets {

	/**
	 * The ID of this theme.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $theme_name    The ID of this theme.
	 */
	private $theme_name;

	/**
	 * The version of this theme.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this theme.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $theme_name       The name of the theme.
	 * @param      string    $version    The version of this theme.
	 */
	public function __construct( $theme_name, $version ) {

		$this->theme_name = $theme_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
        $manifest = $this->get_manifest();
        $css_files = [
            'css/app.css',
        ];

        foreach ($css_files as $file) {
            $handle = $this->theme_name . '-' . str_replace('.css', '', basename($file));
            if (isset($manifest['/' . $file])) {
                wp_enqueue_style( $handle, AQUALUXE_THEME_URI . '/assets/dist' . $manifest['/' . $file], array(), null, 'all' );
            } else {
                wp_enqueue_style( $handle, AQUALUXE_THEME_URI . '/assets/dist/' . $file, array(), $this->version, 'all' );
            }
        }

        $this->add_inline_styles();
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
        $manifest = $this->get_manifest();
        $js_files = [
            'js/app.js',
            'js/dark-mode.js',
        ];

        foreach ($js_files as $file) {
            $handle = $this->theme_name . '-' . str_replace('.js', '', basename($file));
            if (isset($manifest['/' . $file])) {
                wp_enqueue_script( $handle, AQUALUXE_THEME_URI . '/assets/dist' . $manifest['/' . $file], array( 'jquery' ), null, true );
            } else {
                wp_enqueue_script( $handle, AQUALUXE_THEME_URI . '/assets/dist/' . $file, array( 'jquery' ), $this->version, true );
            }
        }
	}

    /**
     * Add inline styles for customizer settings.
     *
     * @since 1.0.0
     */
    public function add_inline_styles() {
        $custom_css = "
            :root {
                --aqualuxe-primary-color: " . get_theme_mod('aqualuxe_primary_color', '#0073aa') . ";
                --aqualuxe-secondary-color: " . get_theme_mod('aqualuxe_secondary_color', '#2271b1') . ";
            }
            body {
                font-family: " . get_theme_mod('aqualuxe_body_font', 'sans-serif') . ";
            }
            h1, h2, h3, h4, h5, h6 {
                font-family: " . get_theme_mod('aqualuxe_heading_font', 'sans-serif') . ";
            }
        ";
        wp_add_inline_style( $this->theme_name . '-app', $custom_css );
    }

    /**
     * Get the manifest file.
     *
     * @since 1.0.0
     * @return array
     */
    private function get_manifest() {
        $manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
        if ( file_exists( $manifest_path ) ) {
            return json_decode( file_get_contents( $manifest_path ), true );
        }
        return [];
    }
}
