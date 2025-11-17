<?php
/**
 * Demo Content Importer
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles demo content import.
 */
class AquaLuxe_Demo_Importer {

	/**
	 * Register hooks.
	 */
	public function register() {
		add_action( 'admin_menu', array( $this, 'add_importer_page' ) );
		add_action( 'admin_init', array( $this, 'handle_import' ) );
	}

	/**
	 * Add importer page to the admin menu.
	 */
	public function add_importer_page() {
		add_theme_page(
			__( 'Import Demo Data', 'aqualuxe' ),
			__( 'Import Demo Data', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-demo-importer',
			array( $this, 'render_importer_page' )
		);
	}

	/**
	 * Render the importer page.
	 */
	public function render_importer_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Import AquaLuxe Demo Data', 'aqualuxe' ); ?></h1>
			<p><?php esc_html_e( 'Importing demo data will provide you with a starting point for your site. It is recommended to do this on a fresh WordPress installation.', 'aqualuxe' ); ?></p>
			<p><?php esc_html_e( 'Click the button below to import the demo content.', 'aqualuxe' ); ?></p>
			<form method="post">
				<?php wp_nonce_field( 'aqualuxe_import_demo_data', 'aqualuxe_import_nonce' ); ?>
				<p>
					<label>
						<input type="checkbox" name="flush_data" value="1">
						<?php esc_html_e( 'Flush all existing data before importing? This will delete all posts, pages, products, etc. Use with caution!', 'aqualuxe' ); ?>
					</label>
				</p>
				<p class="submit">
					<input type="submit" name="import_demo_data" class="button button-primary" value="<?php esc_attr_e( 'Import Demo Data', 'aqualuxe' ); ?>">
				</p>
			</form>
		</div>
		<?php
	}

	/**
	 * Handle the import process.
	 */
	public function handle_import() {
		if ( ! isset( $_POST['import_demo_data'] ) || ! isset( $_POST['aqualuxe_import_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['aqualuxe_import_nonce'], 'aqualuxe_import_demo_data' ) ) {
			wp_die( 'Nonce verification failed!' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'You do not have permission to import demo data.' );
		}

		if ( isset( $_POST['flush_data'] ) && '1' === $_POST['flush_data'] ) {
			$this->flush_database();
		}

		if ( ! defined( 'WP_LOAD_IMPORTER' ) ) {
			define( 'WP_LOAD_IMPORTER', true );
		}

		require_once ABSPATH . 'wp-admin/includes/import.php';

		$importer_error = false;

		if ( ! class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			if ( file_exists( $class_wp_importer ) ) {
				require $class_wp_importer;
			} else {
				$importer_error = true;
			}
		}

		if ( ! class_exists( 'WP_Import' ) ) {
			$class_wp_import = get_template_directory() . '/inc/wordpress-importer/wordpress-importer.php';
			if ( file_exists( $class_wp_import ) ) {
				require $class_wp_import;
			} else {
				$importer_error = true;
			}
		}

		if ( $importer_error ) {
			wp_die( 'Error on import: The WordPress Importer plugin is not available.' );
		} else {
			$importer = new WP_Import();
			$importer->fetch_attachments = true;
			$this->import_content( $importer );
		}

		// Set up front page and blog page
		$this->setup_pages();

		// Set up navigation menus
		$this->setup_menus();

		// Redirect to a success page
		wp_redirect( admin_url( 'themes.php?page=aqualuxe-demo-importer&import=success' ) );
		exit;
	}

	/**
	 * Import content from WXR file.
	 *
	 * @param WP_Import $importer The importer object.
	 */
	private function import_content( $importer ) {
		$import_file = get_template_directory() . '/demo-content/data/content.xml';
		if ( file_exists( $import_file ) ) {
			ob_start();
			$importer->import( $import_file );
			ob_end_clean();
		}
	}

	/**
	 * Flush the database.
	 */
	private function flush_database() {
		global $wpdb;
		$tables_to_flush = array(
			$wpdb->posts,
			$wpdb->postmeta,
			$wpdb->comments,
			$wpdb->commentmeta,
			$wpdb->terms,
			$wpdb->term_taxonomy,
			$wpdb->term_relationships,
			$wpdb->termmeta,
		);

		foreach ( $tables_to_flush as $table ) {
			$wpdb->query( "TRUNCATE TABLE $table" );
		}
	}

	/**
	 * Set up front page and blog page.
	 */
	private function setup_pages() {
		$front_page = get_page_by_title( 'Home' );
		$blog_page  = get_page_by_title( 'Blog' );

		if ( $front_page ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front_page->ID );
		}

		if ( $blog_page ) {
			update_option( 'page_for_posts', $blog_page->ID );
		}
	}

	/**
	 * Set up navigation menus.
	 */
	private function setup_menus() {
		$primary_menu = get_term_by( 'name', 'Primary Menu', 'nav_menu' );
		$footer_menu  = get_term_by( 'name', 'Footer Menu', 'nav_menu' );

		if ( $primary_menu || $footer_menu ) {
			$locations = get_theme_mod( 'nav_menu_locations' );
			if ( $primary_menu ) {
				$locations['primary'] = $primary_menu->term_id;
			}
			if ( $footer_menu ) {
				$locations['footer'] = $footer_menu->term_id;
			}
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}
}

$demo_importer = new AquaLuxe_Demo_Importer();
$demo_importer->register();
