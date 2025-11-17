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
		self::set_theme_options();
		self::setup_menus();

		// Redirect after import.
		wp_redirect( admin_url( 'themes.php?page=aqualuxe-demo-import&imported=true' ) );
		exit;
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
	}

	/**
	 * Import content from WXR file.
	 */
	private static function import_content() {
		if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
			define( 'WP_LOAD_IMPORTERS', true );
		}

		// Load the importer file.
		require_once __DIR__ . '/wordpress-importer.php';

		// Need to call the init function to register the importer.
		wordpress_importer_init();

		$importer = new WP_Import();
		$file     = __DIR__ . '/demo-content.xml';

		// Set fetch attachments to true
		$importer->fetch_attachments = true;

		ob_start();
		$importer->import( $file );
		ob_end_clean();
	}

	/**
	 * Set theme options.
	 */
	private static function set_theme_options() {
		$homepage = get_page_by_title( 'Home' );
		$blogpage = get_page_by_title( 'Blog' );

		if ( $homepage ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $homepage->ID );
		}

		if ( $blogpage ) {
			update_option( 'page_for_posts', $blogpage->ID );
		}
	}

	/**
	 * Setup menus.
	 */
	private static function setup_menus() {
		$primary_menu_name = 'Primary Menu';
		$primary_menu = wp_get_nav_menu_object( $primary_menu_name );

		if ( ! $primary_menu ) {
			$primary_menu_id = wp_create_nav_menu( $primary_menu_name );

			// Add items to the menu.
			wp_update_nav_menu_item( $primary_menu_id, 0, array(
				'menu-item-title'  =>  __( 'Home', 'aqualuxe' ),
				'menu-item-object' => 'page',
				'menu-item-object-id' => get_page_by_title( 'Home' )->ID,
				'menu-item-type'   => 'post_type',
				'menu-item-status' => 'publish'
			) );

            wp_update_nav_menu_item( $primary_menu_id, 0, array(
				'menu-item-title'  =>  __( 'Blog', 'aqualuxe' ),
				'menu-item-object' => 'page',
				'menu-item-object-id' => get_page_by_title( 'Blog' )->ID,
				'menu-item-type'   => 'post_type',
				'menu-item-status' => 'publish'
			) );

			$locations = get_theme_mod( 'nav_menu_locations' );
			$locations['primary'] = $primary_menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}
}
