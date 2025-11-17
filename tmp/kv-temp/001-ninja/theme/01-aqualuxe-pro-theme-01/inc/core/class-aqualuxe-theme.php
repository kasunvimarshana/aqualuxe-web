<?php
/**
 * The file that defines the core theme class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://aqualuxe.pro
 * @since      1.0.0
 *
 * @package    AquaLuxe
 * @subpackage AquaLuxe/inc/core
 */

/**
 * The core theme class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this theme as well as the current
 * version of the theme.
 *
 * @since      1.0.0
 * @package    AquaLuxe
 * @subpackage AquaLuxe/inc/core
 * @author     Your Name <email@example.com>
 */
class AquaLuxe_Theme {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the theme.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      AquaLuxe_Loader    $loader    Maintains and registers all hooks for the theme.
	 */
	protected $loader;

	/**
	 * The unique identifier of this theme.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $theme_name    The string used to uniquely identify this theme.
	 */
	protected $theme_name;

	/**
	 * The current version of the theme.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the theme.
	 */
	protected $version;

	/**
	 * Define the core functionality of the theme.
	 *
	 * Set the theme name and the theme version that can be used throughout the theme.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'AQUALUXE_VERSION' ) ) {
			$this->version = AQUALUXE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->theme_name = 'aqualuxe';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this theme.
	 *
	 * Include the following files that make up the theme:
	 *
	 * - AquaLuxe_Loader. Orchestrates the hooks of the theme.
	 * - AquaLuxe_i18n. Defines internationalization functionality.
	 * - AquaLuxe_Admin. Defines all hooks for the admin area.
	 * - AquaLuxe_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core theme.
		 */
		require_once AQUALUXE_THEME_DIR . '/inc/core/class-aqualuxe-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the theme.
		 */
		require_once AQUALUXE_THEME_DIR . '/inc/core/class-aqualuxe-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once AQUALUXE_THEME_DIR . '/inc/admin/class-aqualuxe-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once AQUALUXE_THEME_DIR . '/inc/public/class-aqualuxe-public.php';
        
        /**
         * The class responsible for theme setup and support.
         */
        require_once AQUALUXE_THEME_DIR . '/inc/core/class-aqualuxe-setup.php';

        /**
         * The class responsible for assets management.
         */
        require_once AQUALUXE_THEME_DIR . '/inc/core/class-aqualuxe-assets.php';

        /**
         * The class responsible for template tags.
         */
        require_once AQUALUXE_THEME_DIR . '/inc/template-tags.php';

        /**
         * The class responsible for template functions.
         */
        require_once AQUALUXE_THEME_DIR . '/inc/template-functions.php';

        /**
         * The class responsible for dark mode.
         */
        require_once AQUALUXE_THEME_DIR . '/inc/modules/dark-mode/dark-mode.php';

        /**
         * The class responsible for multilingual.
         */
        require_once AQUALUXE_THEME_DIR . '/inc/modules/multilingual/multilingual.php';

        /**
         * The class responsible for demo importer.
         */
        require_once AQUALUXE_THEME_DIR . '/inc/modules/demo-importer/demo-importer.php';

		$this->loader = new AquaLuxe_Loader();

	}

	/**
	 * Define the locale for this theme for internationalization.
	 *
	 * Uses the AquaLuxe_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new AquaLuxe_i18n();

		$this->loader->add_action( 'after_setup_theme', $plugin_i18n, 'load_theme_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the theme.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new AquaLuxe_Admin( $this->get_theme_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the theme.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new AquaLuxe_Public( $this->get_theme_name(), $this->get_version() );
        $setup = new AquaLuxe_Setup( $this->get_theme_name(), $this->get_version() );
        $assets = new AquaLuxe_Assets( $this->get_theme_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $assets, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $assets, 'enqueue_scripts' );
        $this->loader->add_action( 'after_setup_theme', $setup, 'theme_setup' );
        $this->loader->add_action( 'widgets_init', $setup, 'widgets_init' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the theme used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the theme.
	 */
	public function get_theme_name() {
		return $this->theme_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the theme.
	 *
	 * @since     1.0.0
	 * @return    AquaLuxe_Loader    Orchestrates the hooks of the theme.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the theme.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the theme.
	 */
	public function get_version() {
		return $this->version;
	}

}
