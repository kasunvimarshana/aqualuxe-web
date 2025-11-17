<?php
/**
 * Demo Content Importer.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles the demo content import process.
 */
class AquaLuxe_Demo_Importer {

	/**
	 * Initialize the demo importer.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_admin_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'handle_import' ) );
	}

	/**
	 * Add admin menu page for the importer.
	 */
	public static function add_admin_menu() {
		add_theme_page(
			__( 'AquaLuxe Demo Import', 'aqualuxe' ),
			__( 'Demo Import', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-demo-import',
			array( __CLASS__, 'render_importer_page' )
		);
	}

	/**
	 * Render the importer page.
	 */
	public static function render_importer_page() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'AquaLuxe Demo Content Importer', 'aqualuxe' ); ?></h1>
			<p><?php echo esc_html__( 'Click the button below to import the demo content. This will install pages, posts, products, and configure theme settings.', 'aqualuxe' ); ?></p>
			<p><strong><?php echo esc_html__( 'Important: It is recommended to run this on a fresh WordPress installation.', 'aqualuxe' ); ?></strong></p>
			<form method="post">
				<?php wp_nonce_field( 'aqualuxe_demo_import_nonce', 'aqualuxe_demo_import_nonce' ); ?>
				<p>
					<label>
						<input type="checkbox" name="flush_data" value="1">
						<?php echo esc_html__( 'Flush all existing data before importing. This will delete all posts, pages, products, etc. Use with caution!', 'aqualuxe' ); ?>
					</label>
				</p>
				<p class="submit">
					<input type="submit" name="aqualuxe_import_demo" class="button button-primary" value="<?php echo esc_attr__( 'Import Demo Content', 'aqualuxe' ); ?>" />
				</p>
			</form>
		</div>
		<?php
	}

	/**
	 * Handle the import process.
	 */
	public static function handle_import() {
		if ( ! isset( $_POST['aqualuxe_import_demo'] ) || ! isset( $_POST['aqualuxe_demo_import_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['aqualuxe_demo_import_nonce'], 'aqualuxe_demo_import_nonce' ) ) {
			wp_die( 'Nonce verification failed!' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'You do not have permission to do this.' );
		}

		if ( isset( $_POST['flush_data'] ) && '1' === $_POST['flush_data'] ) {
			self::flush_database();
		}

		self::import_content();

		// Redirect after import.
		wp_redirect( admin_url( 'themes.php?page=aqualuxe-demo-import&imported=true' ) );
		exit;
	}

	/**
	 * Import content from XML file.
	 */
	private static function import_content() {
		// Load Importer API
		if ( ! class_exists( 'WP_Importer' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-importer.php';
		}

		if ( ! class_exists( 'WP_Import' ) ) {
			require_once dirname( __FILE__ ) . '/class-wp-import.php';
		}

		$importer = new WP_Import();
		$file     = dirname( __FILE__ ) . '/demo-files/demo-content.xml';

		$importer->fetch_attachments = true;
		ob_start();
		$importer->import( $file );
		ob_end_clean();

		// After import, set theme options and menus
		self::set_theme_options();
		self::setup_menus();
	}

	/**
	 * Flush the database of content.
	 */
	private static function flush_database() {
		global $wpdb;
		$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}posts" );
		$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}postmeta" );
		$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}comments" );
		$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}commentmeta" );
		$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}terms" );
		$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}term_taxonomy" );
		$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}term_relationships" );
		$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}term_taxonomy" );
	}

	/**
	 * Set theme options and widgets.
	 */
	private static function set_theme_options() {
		$home_page = get_page_by_title( 'Home' );
		if ( $home_page ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $home_page->ID );
		}

		$blog_page = get_page_by_title( 'Blog' );
		if ( $blog_page ) {
			update_option( 'page_for_posts', $blog_page->ID );
		}
	}

	/**
	 * Set up menus.
	 */
	private static function setup_menus() {
		$main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
		if ( $main_menu ) {
			$locations = get_theme_mod( 'nav_menu_locations' );
			$locations['primary'] = $main_menu->term_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}
}

AquaLuxe_Demo_Importer::init();
