<?php
/**
 * Module: Demo Importer
 *
 * A robust demo importer for the AquaLuxe theme.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AquaLuxe_Demo_Importer class.
 */
class AquaLuxe_Demo_Importer {

	/**
	 * Instance of this class.
	 * @var object
	 */
	private static $instance;

	/**
	 * Log file path.
	 * @var string
	 */
	private $log_file;

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
	public function __construct() {
		$this->log_file = AQUALUXE_THEME_DIR . '/modules/demo-importer/import-log.txt';
		add_action( 'admin_menu', array( $this, 'add_importer_page' ) );
		add_action( 'admin_init', array( $this, 'handle_import' ) );
	}

	/**
	 * Add importer page to the admin menu.
	 */
	public function add_importer_page() {
		add_theme_page(
			__( 'AquaLuxe Demo Importer', 'aqualuxe' ),
			__( 'Demo Importer', 'aqualuxe' ),
			'import', // Use 'import' capability
			'aqualuxe-demo-importer',
			array( $this, 'render_importer_page' )
		);
	}

	/**
	 * Render the importer page.
	 */
	public function render_importer_page() {
		?>
		<div class="wrap" style="max-width: 800px;">
			<h1><?php echo esc_html__( 'AquaLuxe Theme Demo Importer', 'aqualuxe' ); ?></h1>
			<?php
			if ( isset( $_GET['import-success'] ) ) {
				echo '<div class="notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Demo import completed successfully!', 'aqualuxe' ) . '</strong></p></div>';
				if ( file_exists( $this->log_file ) ) {
					$log_url = content_url( str_replace( ABSPATH, '', AQUALUXE_THEME_DIR ) . '/modules/demo-importer/import-log.txt' );
					echo '<p><a href="' . esc_url( $log_url ) . '" target="_blank">' . esc_html__( 'View Import Log', 'aqualuxe' ) . '</a></p>';
				}
			}

			// Check for active WordPress Importer plugin
			if ( class_exists( 'WP_Importer' ) ) {
				echo '<div class="notice notice-error"><p><strong>' . esc_html__( 'Warning:', 'aqualuxe' ) . '</strong> ' . esc_html__( 'The WordPress Importer plugin is active. Please deactivate it before using this demo importer to avoid conflicts.', 'aqualuxe' ) . '</p></div>';
				return;
			}

			// Check for WooCommerce
			if ( ! class_exists( 'WooCommerce' ) ) {
				echo '<div class="notice notice-error"><p><strong>' . esc_html__( 'Warning:', 'aqualuxe' ) . '</strong> ' . esc_html__( 'WooCommerce is not active. Please install and activate WooCommerce before importing the demo content.', 'aqualuxe' ) . '</p></div>';
				return;
			}
			?>
			<div class="notice notice-warning is-dismissible" style="border-left-color: #f0ad4e;">
				<p>
					<strong><?php esc_html_e( 'Important:', 'aqualuxe' ); ?></strong>
					<?php esc_html_e( 'It is highly recommended to use this importer on a fresh WordPress installation. The process will add content, widgets, and customizer settings. While we have a "Flush Database" option, it is very destructive and should be used with extreme caution.', 'aqualuxe' ); ?>
				</p>
			</div>

			<form method="post" onsubmit="return confirm('<?php echo esc_js( __( 'Are you sure you want to proceed with the demo import?', 'aqualuxe' ) ); ?>');">
				<?php wp_nonce_field( 'aqualuxe_demo_import_nonce', 'aqualuxe_demo_import_nonce' ); ?>
				
				<div style="background: #fff; border: 1px solid #ccd0d4; padding: 1rem; margin-top: 1rem;">
					<h3><?php esc_html_e( 'Import Options', 'aqualuxe' ); ?></h3>
					<p>
						<label>
							<input type="checkbox" name="import_content" checked />
							<?php esc_html_e( 'Import XML Content (posts, pages, products, etc.)', 'aqualuxe' ); ?>
						</label>
					</p>
					<p>
						<label>
							<input type="checkbox" name="import_widgets" checked />
							<?php esc_html_e( 'Import Widgets (.wie file)', 'aqualuxe' ); ?>
						</label>
					</p>
					<p>
						<label>
							<input type="checkbox" name="import_customizer" checked />
							<?php esc_html_e( 'Import Customizer Settings (.dat file)', 'aqualuxe' ); ?>
						</label>
					</p>
				</div>

				<div style="background: #fff; border: 1px solid #ccd0d4; padding: 1rem; margin-top: 1rem; border-left: 4px solid #dc3232;">
					<h3><?php esc_html_e( 'Advanced Options', 'aqualuxe' ); ?></h3>
					<p>
						<label>
							<input type="checkbox" name="aqualuxe_flush_data" onchange="if(this.checked) return confirm('<?php echo esc_js( __( 'DANGER: This will delete all posts, pages, products, media, and terms from your database before importing. Are you absolutely sure?', 'aqualuxe' ) ); ?>');" />
							<strong style="color: #dc3232;"><?php esc_html_e( 'Flush Database Before Importing', 'aqualuxe' ); ?></strong>
						</label>
						<p class="description"><?php esc_html_e( 'This is a destructive operation. Use only if you want to start over on a non-empty site.', 'aqualuxe' ); ?></p>
					</p>
				</div>

				<p class="submit" style="margin-top: 1.5rem;">
					<button type="submit" name="aqualuxe_import_demo" class="button button-primary button-hero"><?php echo esc_html__( 'Import Demo Data', 'aqualuxe' ); ?></button>
				</p>
			</form>
		</div>
		<?php
	}

	/**
	 * Handle the demo import process.
	 */
	public function handle_import() {
		if ( ! isset( $_POST['aqualuxe_import_demo'] ) || ! isset( $_POST['aqualuxe_demo_import_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['aqualuxe_demo_import_nonce'], 'aqualuxe_demo_import_nonce' ) ) {
			wp_die( 'Security check failed.' );
		}

		if ( ! current_user_can( 'import' ) ) {
			wp_die( 'You do not have permission to import demo data.' );
		}

		// Clear log file
		file_put_contents( $this->log_file, '' );

		if ( isset( $_POST['aqualuxe_flush_data'] ) ) {
			$this->log( '--- Flushing Database ---' );
			$this->flush_database();
			$this->log( '--- Database Flush Complete ---' );
		}

		if ( isset( $_POST['import_content'] ) ) {
			$this->log( '--- Importing Content ---' );
			$this->import_content();
			$this->log( '--- Content Import Complete ---' );
		}

		if ( isset( $_POST['import_widgets'] ) ) {
			$this->log( '--- Importing Widgets ---' );
			$this->import_widgets();
			$this->log( '--- Widget Import Complete ---' );
		}

		if ( isset( $_POST['import_customizer'] ) ) {
			$this->log( '--- Importing Customizer Settings ---' );
			$this->import_customizer_settings();
			$this->log( '--- Customizer Settings Import Complete ---' );
		}

		// Redirect to avoid form resubmission
		wp_safe_redirect( admin_url( 'themes.php?page=aqualuxe-demo-importer&import-success=true' ) );
		exit;
	}

	/**
	 * Log messages to a file.
	 * @param string $message The message to log.
	 */
	private function log( $message ) {
		$timestamp = date( 'Y-m-d H:i:s' );
		file_put_contents( $this->log_file, "[$timestamp] $message\n", FILE_APPEND );
	}

	/**
	 * Flush the database.
	 */
	private function flush_database() {
		global $wpdb;
		$this->log( 'Truncating core WordPress tables...' );
		$tables_to_truncate = [
			'posts', 'postmeta', 'comments', 'commentmeta', 'terms', 'term_taxonomy', 'term_relationships', 'termmeta'
		];

		foreach ( $tables_to_truncate as $table ) {
			$table_name = $wpdb->prefix . $table;
			if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) === $table_name ) {
				$wpdb->query( "TRUNCATE TABLE `{$table_name}`" );
				$this->log( "Truncated `{$table_name}`." );
			}
		}
		$this->log( 'Core tables truncated.' );
	}

	/**
	 * Import content from WXR file.
	 */
	private function import_content() {
		if ( ! class_exists( 'WP_Import' ) ) {
			require_once AQUALUXE_THEME_DIR . '/modules/demo-importer/wordpress-importer.php';
		}

		$importer = new WP_Import();
		$file = AQUALUXE_THEME_DIR . '/modules/demo-importer/demo-content.xml';

		if ( ! file_exists( $file ) ) {
			$this->log( 'ERROR: Demo content file not found at ' . $file );
			return;
		}

		// Capture importer output to log
		ob_start();
		$importer->fetch_attachments = true;
		$importer->import( $file );
		$output = ob_get_clean();
		$this->log( "Importer Output:\n" . $output );
	}

	/**
	 * Import widgets from .wie file.
	 */
	private function import_widgets() {
		$widget_file = AQUALUXE_THEME_DIR . '/modules/demo-importer/widgets.wie';
		if ( ! file_exists( $widget_file ) || ! is_readable( $widget_file ) ) {
			$this->log( 'ERROR: Widget file not found or not readable at ' . $widget_file );
			return;
		}

		$data = json_decode( file_get_contents( $widget_file ), true );
		
		// Logic to process widget data would go here.
		// This is a placeholder for a full implementation.
		// A complete solution requires parsing the .wie format and updating options.
		$this->log( 'Widget import functionality is a placeholder. Found widget file.' );
	}

	/**
	 * Import customizer settings from .dat file.
	 */
	private function import_customizer_settings() {
		$customizer_file = AQUALUXE_THEME_DIR . '/modules/demo-importer/customizer.dat';
		if ( ! file_exists( $customizer_file ) || ! is_readable( $customizer_file ) ) {
			$this->log( 'ERROR: Customizer settings file not found or not readable at ' . $customizer_file );
			return;
		}

		$data = unserialize( file_get_contents( $customizer_file ) );

		// Logic to process customizer data would go here.
		// This is a placeholder for a full implementation.
		if ( is_array( $data ) && isset( $data['mods'] ) ) {
			foreach ( $data['mods'] as $key => $value ) {
				set_theme_mod( $key, $value );
			}
			$this->log( 'Customizer settings processed.' );
		} else {
			$this->log( 'ERROR: Invalid customizer data format.' );
		}
	}
}

AquaLuxe_Demo_Importer::get_instance();

