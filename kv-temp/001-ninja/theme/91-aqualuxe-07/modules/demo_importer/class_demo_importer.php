<?php
/**
 * AquaLuxe Demo Importer Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

namespace AquaLuxe\Modules\Demo_Importer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Demo_Importer
 *
 * Main class for the Demo Importer module.
 */
class Demo_Importer {

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Provides access to a single instance of a module using the singleton pattern.
	 *
	 * @return object
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Include required files.
	 */
	private function includes() {
		require_once dirname( __FILE__ ) . '/includes/class_importer_ui.php';
		require_once dirname( __FILE__ ) . '/includes/class_content_importer.php';
		require_once dirname( __FILE__ ) . '/includes/class_widget_importer.php';
		require_once dirname( __FILE__ ) . '/includes/class_customizer_importer.php';
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	}

	/**
	 * Initialize hooks.
	 */
	private function init_hooks() {
		add_action( 'admin_menu', array( Importer_UI::class, 'add_admin_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_aqualuxe_import_demo_data', array( $this, 'ajax_import_demo_data' ) );
		add_action( 'wp_ajax_aqualuxe_flush_data', array( $this, 'ajax_flush_data' ) );
	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @param string $hook The current admin page.
	 */
	public function enqueue_scripts( $hook ) {
		if ( 'appearance_page_aqualuxe-demo-importer' !== $hook ) {
			return;
		}
		wp_enqueue_style( 'aqualuxe-importer', get_template_directory_uri() . '/modules/demo_importer/assets/css/importer.css', array(), AQUALUXE_VERSION );
		wp_enqueue_script( 'aqualuxe-importer', get_template_directory_uri() . '/modules/demo_importer/assets/js/importer.js', array( 'jquery' ), AQUALUXE_VERSION, true );
		wp_localize_script(
			'aqualuxe-importer',
			'aqualuxe_importer_params',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'aqualuxe_importer_nonce' ),
				'texts'    => array(
					'importing' => esc_html__( 'Importing...', 'aqualuxe' ),
					'flushing'  => esc_html__( 'Flushing data...', 'aqualuxe' ),
					'success'   => esc_html__( 'Demo content imported successfully!', 'aqualuxe' ),
					'flush_success' => esc_html__( 'All data has been flushed.', 'aqualuxe' ),
					'error'     => esc_html__( 'An error occurred. Please check the console for details.', 'aqualuxe' ),
					'confirm_flush' => esc_html__( 'Are you sure you want to flush all data? This cannot be undone.', 'aqualuxe' ),
				),
			)
		);
	}

	/**
	 * AJAX handler for importing demo data.
	 */
	public function ajax_import_demo_data() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_importer_nonce' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		$importer            = new Content_Importer();
		$widget_importer     = new Widget_Importer();
		$customizer_importer = new Customizer_Importer();

		$result = array();

		// Import content.
		ob_start();
		$importer->import( $this->get_data_file_path( 'content.xml' ) );
		$result['content'] = ob_get_clean();

		// Import widgets.
		$result['widgets'] = $widget_importer->import( $this->get_data_file_path( 'widgets.json' ) );

		// Import customizer settings.
		$result['customizer'] = $customizer_importer->import( $this->get_data_file_path( 'customizer.json' ) );

		// Set up menus, front page, etc.
		$this->setup_site();

		wp_send_json_success( $result );
	}

	/**
	 * AJAX handler for flushing data.
	 */
	public function ajax_flush_data() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_importer_nonce' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		// Implement the flush logic here.
		// This is a destructive action and should be handled with care.
		$this->flush_database();

		wp_send_json_success( 'Data flushed.' );
	}

	/**
	 * Get path to data file.
	 *
	 * @param string $file Filename.
	 * @return string Path to data file.
	 */
	private function get_data_file_path( $file ) {
		return dirname( __FILE__ ) . '/data/' . $file;
	}

	/**
	 * Setup site after import.
	 */
	private function setup_site() {
		// Assign menus to locations.
		$main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
		if ( $main_menu ) {
			set_theme_mod( 'nav_menu_locations', array( 'primary' => $main_menu->term_id ) );
		}

		// Assign front page and posts page.
		$front_page = get_page_by_title( 'Home' );
		if ( $front_page ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front_page->ID );
		}

		$blog_page = get_page_by_title( 'Blog' );
		if ( $blog_page ) {
			update_option( 'page_for_posts', $blog_page->ID );
		}
	}

	/**
	 * Flush the database.
	 * Deletes posts, pages, custom post types, terms, etc.
	 */
	private function flush_database() {
		global $wpdb;

		// Delete all posts, pages, and custom post types.
		$wpdb->query( "TRUNCATE TABLE {$wpdb->posts}" );
		$wpdb->query( "TRUNCATE TABLE {$wpdb->postmeta}" );
		$wpdb->query( "TRUNCATE TABLE {$wpdb->comments}" );
		$wpdb->query( "TRUNCATE TABLE {$wpdb->commentmeta}" );
		$wpdb->query( "TRUNCATE TABLE {$wpdb->terms}" );
		$wpdb->query( "TRUNCATE TABLE {$wpdb->term_taxonomy}" );
		$wpdb->query( "TRUNCATE TABLE {$wpdb->term_relationships}" );
		$wpdb->query( "TRUNCATE TABLE {$wpdb->termmeta}" );
		$wpdb->query( "TRUNCATE TABLE {$wpdb->links}" );

		// Re-install default terms
		$admin_user = get_user_by( 'login', 'admin' );
		if ( $admin_user ) {
			wp_install_defaults( $admin_user->ID );
		} else {
			// Fallback to the first user if admin doesn't exist
			$users = get_users( array( 'number' => 1, 'role' => 'administrator' ) );
			if ( ! empty( $users ) ) {
				wp_install_defaults( $users[0]->ID );
			}
		}
	}
}

Demo_Importer::get_instance();
