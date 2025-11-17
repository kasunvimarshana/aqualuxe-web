<?php
/**
 * AquaLuxe Demo Content Importer
 *
 * @package AquaLuxe
 * @subpackage Demo
 * @since 1.1.0
 */

namespace AquaLuxe\Demo;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Demo Importer Class
 *
 * Handles the import of demo content for the theme.
 *
 * @since 1.1.0
 */
class Importer {

	/**
	 * Demo importer instance
	 *
	 * @var object
	 */
	private $demo_importer;

	/**
	 * Constructor
	 */
	public function __construct() {
		// We don't load the original demo importer class here to avoid conflicts
		// Instead, we'll load it on demand when methods are called
		
		// Initialize hooks
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'wp_ajax_aqualuxe_import_demo_data', array( $this, 'import_demo_data' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Get demo importer instance
	 *
	 * @return object Demo importer instance
	 */
	private function get_demo_importer() {
		if ( ! $this->demo_importer ) {
			// Include the original demo importer class only if it hasn't been loaded yet
			if ( ! class_exists( 'AquaLuxe_Demo_Importer' ) ) {
				require_once AQUALUXE_DIR . '/inc/demo-importer/class-aqualuxe-demo-importer.php';
			}
			
			// Create an instance of the original demo importer
			$this->demo_importer = new \AquaLuxe_Demo_Importer();
		}
		
		return $this->demo_importer;
	}

	/**
	 * Add admin menu item
	 */
	public function add_admin_menu() {
		add_theme_page(
			__( 'Import Demo Data', 'aqualuxe' ),
			__( 'Import Demo Data', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-demo-import',
			array( $this, 'demo_import_page' )
		);
	}

	/**
	 * Demo import page
	 */
	public function demo_import_page() {
		$this->get_demo_importer()->demo_import_page();
	}

	/**
	 * Import demo data
	 */
	public function import_demo_data() {
		$this->get_demo_importer()->import_demo_data();
	}

	/**
	 * Enqueue scripts
	 *
	 * @param string $hook Hook suffix for the current admin page.
	 */
	public function enqueue_scripts( $hook ) {
		if ( 'appearance_page_aqualuxe-demo-import' !== $hook ) {
			return;
		}

		$this->get_demo_importer()->enqueue_scripts($hook);
	}

	/**
	 * Get demo data
	 *
	 * @return array Demo data.
	 */
	public function get_demo_data() {
		return $this->get_demo_importer()->get_demo_data();
	}

	/**
	 * Get demo import status
	 *
	 * @return array Import status.
	 */
	public function get_import_status() {
		return $this->get_demo_importer()->get_import_status();
	}

	/**
	 * Set import status
	 *
	 * @param string $status Import status.
	 * @param string $message Import message.
	 */
	public function set_import_status( $status, $message ) {
		$this->get_demo_importer()->set_import_status( $status, $message );
	}

	/**
	 * Import XML file
	 *
	 * @param string $file File path.
	 */
	public function import_xml( $file ) {
		$this->get_demo_importer()->import_xml( $file );
	}

	/**
	 * Import customizer settings
	 *
	 * @param string $file File path.
	 */
	public function import_customizer( $file ) {
		$this->get_demo_importer()->import_customizer( $file );
	}

	/**
	 * Import widgets
	 *
	 * @param string $file File path.
	 */
	public function import_widgets( $file ) {
		$this->get_demo_importer()->import_widgets( $file );
	}

	/**
	 * Import options
	 *
	 * @param string $file File path.
	 */
	public function import_options( $file ) {
		$this->get_demo_importer()->import_options( $file );
	}

	/**
	 * Setup menus
	 */
	public function setup_menus() {
		$this->get_demo_importer()->setup_menus();
	}

	/**
	 * Setup homepage
	 */
	public function setup_homepage() {
		$this->get_demo_importer()->setup_homepage();
	}

	/**
	 * Setup theme options
	 */
	public function setup_theme_options() {
		$this->get_demo_importer()->setup_theme_options();
	}
}