<?php
/**
 * Demo Importer
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * AquaLuxe Demo Importer Class
 */
class AquaLuxe_Demo_Importer {

	/**
	 * Demo data
	 *
	 * @var array
	 */
	private $demo_data = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		// Initialize demo data
		$this->init_demo_data();

		// Add action hooks
		add_action( 'admin_menu', array( $this, 'add_demo_import_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_aqualuxe_import_demo', array( $this, 'ajax_import_demo' ) );
	}

	/**
	 * Initialize demo data
	 */
	private function init_demo_data() {
		$this->demo_data = array(
			'main' => array(
				'name'        => esc_html__( 'Main Demo', 'aqualuxe' ),
				'description' => esc_html__( 'Complete demo with all pages, posts, products, and settings.', 'aqualuxe' ),
				'preview_url' => 'https://aqualuxe.ninjatech.ai/',
				'thumbnail'   => get_template_directory_uri() . '/assets/images/demos/main-demo.jpg',
				'import_file' => get_template_directory() . '/inc/demos/main-demo.xml',
				'widgets'     => get_template_directory() . '/inc/demos/main-demo-widgets.wie',
				'customizer'  => get_template_directory() . '/inc/demos/main-demo-customizer.dat',
			),
			'minimal' => array(
				'name'        => esc_html__( 'Minimal Demo', 'aqualuxe' ),
				'description' => esc_html__( 'A clean, minimalist version with essential pages and features.', 'aqualuxe' ),
				'preview_url' => 'https://aqualuxe.ninjatech.ai/minimal/',
				'thumbnail'   => get_template_directory_uri() . '/assets/images/demos/minimal-demo.jpg',
				'import_file' => get_template_directory() . '/inc/demos/minimal-demo.xml',
				'widgets'     => get_template_directory() . '/inc/demos/minimal-demo-widgets.wie',
				'customizer'  => get_template_directory() . '/inc/demos/minimal-demo-customizer.dat',
			),
			'shop' => array(
				'name'        => esc_html__( 'Shop Demo', 'aqualuxe' ),
				'description' => esc_html__( 'Focused on e-commerce with extensive product catalog and shop features.', 'aqualuxe' ),
				'preview_url' => 'https://aqualuxe.ninjatech.ai/shop/',
				'thumbnail'   => get_template_directory_uri() . '/assets/images/demos/shop-demo.jpg',
				'import_file' => get_template_directory() . '/inc/demos/shop-demo.xml',
				'widgets'     => get_template_directory() . '/inc/demos/shop-demo-widgets.wie',
				'customizer'  => get_template_directory() . '/inc/demos/shop-demo-customizer.dat',
			),
		);
	}

	/**
	 * Add demo import page
	 */
	public function add_demo_import_page() {
		add_submenu_page(
			'themes.php',
			esc_html__( 'AquaLuxe Demo Import', 'aqualuxe' ),
			esc_html__( 'AquaLuxe Demo Import', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-demo-import',
			array( $this, 'demo_import_page' )
		);
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts( $hook ) {
		if ( 'appearance_page_aqualuxe-demo-import' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'aqualuxe-demo-importer', get_template_directory_uri() . '/assets/css/demo-importer.css', array(), AQUALUXE_VERSION );
		wp_enqueue_script( 'aqualuxe-demo-importer', get_template_directory_uri() . '/assets/js/demo-importer.js', array( 'jquery' ), AQUALUXE_VERSION, true );

		wp_localize_script(
			'aqualuxe-demo-importer',
			'aqualuxeDemoImporter',
			array(
				'ajaxUrl'           => admin_url( 'admin-ajax.php' ),
				'nonce'             => wp_create_nonce( 'aqualuxe-demo-import-nonce' ),
				'importing'         => esc_html__( 'Importing...', 'aqualuxe' ),
				'confirmImport'     => esc_html__( 'Are you sure you want to import this demo? This will overwrite your current settings.', 'aqualuxe' ),
				'importSuccess'     => esc_html__( 'Import completed successfully!', 'aqualuxe' ),
				'importError'       => esc_html__( 'An error occurred during import. Please try again.', 'aqualuxe' ),
				'pluginsRequired'   => esc_html__( 'The following plugins are required for this demo:', 'aqualuxe' ),
				'installPlugins'    => esc_html__( 'Install & Activate Plugins', 'aqualuxe' ),
				'installingPlugins' => esc_html__( 'Installing plugins...', 'aqualuxe' ),
				'pluginsInstalled'  => esc_html__( 'All required plugins installed!', 'aqualuxe' ),
			)
		);
	}

	/**
	 * Demo import page
	 */
	public function demo_import_page() {
		?>
		<div class="wrap aqualuxe-demo-importer-wrap">
			<h1><?php esc_html_e( 'AquaLuxe Demo Import', 'aqualuxe' ); ?></h1>
			
			<div class="aqualuxe-demo-importer-intro">
				<p><?php esc_html_e( 'Import demo content to get started quickly with your site. Choose from the available demo options below.', 'aqualuxe' ); ?></p>
				<div class="aqualuxe-demo-importer-notice">
					<p><strong><?php esc_html_e( 'Important:', 'aqualuxe' ); ?></strong> <?php esc_html_e( 'The import process will overwrite your current theme settings. It is recommended to use this on a fresh WordPress installation.', 'aqualuxe' ); ?></p>
				</div>
			</div>
			
			<div class="aqualuxe-demo-importer-demos">
				<?php foreach ( $this->demo_data as $demo_id => $demo ) : ?>
					<div class="aqualuxe-demo-importer-item" data-demo-id="<?php echo esc_attr( $demo_id ); ?>">
						<div class="aqualuxe-demo-importer-item-preview">
							<img src="<?php echo esc_url( $demo['thumbnail'] ); ?>" alt="<?php echo esc_attr( $demo['name'] ); ?>">
							<div class="aqualuxe-demo-importer-item-actions">
								<a href="<?php echo esc_url( $demo['preview_url'] ); ?>" class="button button-secondary" target="_blank"><?php esc_html_e( 'Preview', 'aqualuxe' ); ?></a>
								<button class="button button-primary aqualuxe-import-demo"><?php esc_html_e( 'Import', 'aqualuxe' ); ?></button>
							</div>
						</div>
						<div class="aqualuxe-demo-importer-item-info">
							<h3><?php echo esc_html( $demo['name'] ); ?></h3>
							<p><?php echo esc_html( $demo['description'] ); ?></p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			
			<div class="aqualuxe-demo-importer-modal">
				<div class="aqualuxe-demo-importer-modal-content">
					<span class="aqualuxe-demo-importer-modal-close">&times;</span>
					<h2 class="aqualuxe-demo-importer-modal-title"><?php esc_html_e( 'Importing Demo', 'aqualuxe' ); ?></h2>
					
					<div class="aqualuxe-demo-importer-modal-plugins">
						<h3><?php esc_html_e( 'Required Plugins', 'aqualuxe' ); ?></h3>
						<ul class="aqualuxe-demo-importer-plugin-list"></ul>
						<button class="button button-primary aqualuxe-install-plugins"><?php esc_html_e( 'Install & Activate Plugins', 'aqualuxe' ); ?></button>
					</div>
					
					<div class="aqualuxe-demo-importer-modal-progress">
						<h3><?php esc_html_e( 'Import Progress', 'aqualuxe' ); ?></h3>
						<div class="aqualuxe-demo-importer-progress-bar">
							<div class="aqualuxe-demo-importer-progress-bar-inner"></div>
						</div>
						<div class="aqualuxe-demo-importer-progress-status">
							<span class="aqualuxe-demo-importer-progress-percentage">0%</span>
							<span class="aqualuxe-demo-importer-progress-step"><?php esc_html_e( 'Preparing import...', 'aqualuxe' ); ?></span>
						</div>
					</div>
					
					<div class="aqualuxe-demo-importer-modal-complete">
						<div class="aqualuxe-demo-importer-modal-complete-success">
							<span class="dashicons dashicons-yes-alt"></span>
							<h3><?php esc_html_e( 'Import Complete!', 'aqualuxe' ); ?></h3>
							<p><?php esc_html_e( 'The demo content has been successfully imported.', 'aqualuxe' ); ?></p>
							<div class="aqualuxe-demo-importer-modal-complete-actions">
								<a href="<?php echo esc_url( home_url() ); ?>" class="button button-primary" target="_blank"><?php esc_html_e( 'View Site', 'aqualuxe' ); ?></a>
								<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Customize', 'aqualuxe' ); ?></a>
							</div>
						</div>
						<div class="aqualuxe-demo-importer-modal-complete-error">
							<span class="dashicons dashicons-warning"></span>
							<h3><?php esc_html_e( 'Import Failed', 'aqualuxe' ); ?></h3>
							<p class="aqualuxe-demo-importer-error-message"></p>
							<div class="aqualuxe-demo-importer-modal-complete-actions">
								<button class="button button-primary aqualuxe-retry-import"><?php esc_html_e( 'Retry', 'aqualuxe' ); ?></button>
								<button class="button button-secondary aqualuxe-demo-importer-modal-close-btn"><?php esc_html_e( 'Close', 'aqualuxe' ); ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * AJAX import demo
	 */
	public function ajax_import_demo() {
		// Check nonce
		if ( ! check_ajax_referer( 'aqualuxe-demo-import-nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce. Please refresh the page and try again.', 'aqualuxe' ) ) );
		}

		// Check if user has the required capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'You do not have permission to perform this action.', 'aqualuxe' ) ) );
		}

		// Get demo ID
		$demo_id = isset( $_POST['demo_id'] ) ? sanitize_text_field( wp_unslash( $_POST['demo_id'] ) ) : '';

		// Check if demo exists
		if ( ! isset( $this->demo_data[ $demo_id ] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Demo not found.', 'aqualuxe' ) ) );
		}

		// Get demo data
		$demo = $this->demo_data[ $demo_id ];

		// Get import step
		$step = isset( $_POST['step'] ) ? sanitize_text_field( wp_unslash( $_POST['step'] ) ) : 'check_plugins';

		// Process import step
		switch ( $step ) {
			case 'check_plugins':
				$this->check_required_plugins( $demo_id );
				break;

			case 'install_plugins':
				$this->install_plugins( $demo_id );
				break;

			case 'import_content':
				$this->import_content( $demo_id );
				break;

			case 'import_widgets':
				$this->import_widgets( $demo_id );
				break;

			case 'import_customizer':
				$this->import_customizer( $demo_id );
				break;

			case 'setup_pages':
				$this->setup_pages( $demo_id );
				break;

			default:
				wp_send_json_error( array( 'message' => esc_html__( 'Invalid import step.', 'aqualuxe' ) ) );
				break;
		}
	}

	/**
	 * Check required plugins
	 *
	 * @param string $demo_id Demo ID.
	 */
	private function check_required_plugins( $demo_id ) {
		// Define required plugins for each demo
		$required_plugins = $this->get_required_plugins( $demo_id );

		// Check plugin status
		$plugins_status = array();
		foreach ( $required_plugins as $plugin ) {
			$plugins_status[] = array(
				'name'        => $plugin['name'],
				'slug'        => $plugin['slug'],
				'is_active'   => $this->is_plugin_active( $plugin['slug'] ),
				'is_installed' => $this->is_plugin_installed( $plugin['slug'] ),
			);
		}

		wp_send_json_success(
			array(
				'plugins' => $plugins_status,
				'next_step' => 'install_plugins',
			)
		);
	}

	/**
	 * Install plugins
	 *
	 * @param string $demo_id Demo ID.
	 */
	private function install_plugins( $demo_id ) {
		// Define required plugins for each demo
		$required_plugins = $this->get_required_plugins( $demo_id );

		// Install and activate plugins
		$installed_plugins = array();
		foreach ( $required_plugins as $plugin ) {
			if ( ! $this->is_plugin_installed( $plugin['slug'] ) ) {
				// Install plugin
				$install_status = $this->install_plugin( $plugin['slug'] );
				if ( is_wp_error( $install_status ) ) {
					wp_send_json_error(
						array(
							'message' => sprintf(
								/* translators: %1$s: Plugin name, %2$s: Error message */
								esc_html__( 'Error installing %1$s: %2$s', 'aqualuxe' ),
								$plugin['name'],
								$install_status->get_error_message()
							),
						)
					);
				}
			}

			if ( ! $this->is_plugin_active( $plugin['slug'] ) ) {
				// Activate plugin
				$activate_status = $this->activate_plugin( $plugin['slug'] );
				if ( is_wp_error( $activate_status ) ) {
					wp_send_json_error(
						array(
							'message' => sprintf(
								/* translators: %1$s: Plugin name, %2$s: Error message */
								esc_html__( 'Error activating %1$s: %2$s', 'aqualuxe' ),
								$plugin['name'],
								$activate_status->get_error_message()
							),
						)
					);
				}
			}

			$installed_plugins[] = $plugin['name'];
		}

		wp_send_json_success(
			array(
				'installed_plugins' => $installed_plugins,
				'next_step' => 'import_content',
			)
		);
	}

	/**
	 * Import content
	 *
	 * @param string $demo_id Demo ID.
	 */
	private function import_content( $demo_id ) {
		// Get demo data
		$demo = $this->demo_data[ $demo_id ];

		// Check if import file exists
		if ( ! file_exists( $demo['import_file'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Import file not found.', 'aqualuxe' ) ) );
		}

		// Include WordPress importer
		if ( ! class_exists( 'WP_Importer' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-importer.php';
		}

		if ( ! class_exists( 'WP_Import' ) ) {
			require_once get_template_directory() . '/inc/admin/wordpress-importer/wordpress-importer.php';
		}

		// Import content
		$importer = new WP_Import();
		$importer->fetch_attachments = true;

		ob_start();
		$importer->import( $demo['import_file'] );
		ob_end_clean();

		wp_send_json_success(
			array(
				'next_step' => 'import_widgets',
			)
		);
	}

	/**
	 * Import widgets
	 *
	 * @param string $demo_id Demo ID.
	 */
	private function import_widgets( $demo_id ) {
		// Get demo data
		$demo = $this->demo_data[ $demo_id ];

		// Check if widgets file exists
		if ( ! file_exists( $demo['widgets'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Widgets file not found.', 'aqualuxe' ) ) );
		}

		// Include widget importer
		require_once get_template_directory() . '/inc/admin/widget-importer/widget-importer.php';

		// Import widgets
		$widgets_import = AquaLuxe_Widget_Importer::import( $demo['widgets'] );

		if ( is_wp_error( $widgets_import ) ) {
			wp_send_json_error( array( 'message' => $widgets_import->get_error_message() ) );
		}

		wp_send_json_success(
			array(
				'next_step' => 'import_customizer',
			)
		);
	}

	/**
	 * Import customizer
	 *
	 * @param string $demo_id Demo ID.
	 */
	private function import_customizer( $demo_id ) {
		// Get demo data
		$demo = $this->demo_data[ $demo_id ];

		// Check if customizer file exists
		if ( ! file_exists( $demo['customizer'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Customizer file not found.', 'aqualuxe' ) ) );
		}

		// Include customizer importer
		require_once get_template_directory() . '/inc/admin/customizer-importer/customizer-importer.php';

		// Import customizer
		$customizer_import = AquaLuxe_Customizer_Importer::import( $demo['customizer'] );

		if ( is_wp_error( $customizer_import ) ) {
			wp_send_json_error( array( 'message' => $customizer_import->get_error_message() ) );
		}

		wp_send_json_success(
			array(
				'next_step' => 'setup_pages',
			)
		);
	}

	/**
	 * Setup pages
	 *
	 * @param string $demo_id Demo ID.
	 */
	private function setup_pages( $demo_id ) {
		// Set up front page and blog page
		$front_page = get_page_by_title( 'Home' );
		$blog_page = get_page_by_title( 'Blog' );

		if ( $front_page ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front_page->ID );
		}

		if ( $blog_page ) {
			update_option( 'page_for_posts', $blog_page->ID );
		}

		// Set up WooCommerce pages if WooCommerce is active
		if ( class_exists( 'WooCommerce' ) ) {
			// Get WooCommerce pages
			$shop_page = get_page_by_title( 'Shop' );
			$cart_page = get_page_by_title( 'Cart' );
			$checkout_page = get_page_by_title( 'Checkout' );
			$account_page = get_page_by_title( 'My Account' );

			// Set WooCommerce pages
			if ( $shop_page ) {
				update_option( 'woocommerce_shop_page_id', $shop_page->ID );
			}

			if ( $cart_page ) {
				update_option( 'woocommerce_cart_page_id', $cart_page->ID );
			}

			if ( $checkout_page ) {
				update_option( 'woocommerce_checkout_page_id', $checkout_page->ID );
			}

			if ( $account_page ) {
				update_option( 'woocommerce_myaccount_page_id', $account_page->ID );
			}

			// Flush rewrite rules
			flush_rewrite_rules();
		}

		// Update permalink structure
		update_option( 'permalink_structure', '/%postname%/' );

		// Clear any cached data
		wp_cache_flush();

		wp_send_json_success(
			array(
				'message' => esc_html__( 'Demo import completed successfully!', 'aqualuxe' ),
				'redirect' => admin_url( 'themes.php?page=aqualuxe-theme&tab=demo&demo_imported=1' ),
			)
		);
	}

	/**
	 * Get required plugins for a demo
	 *
	 * @param string $demo_id Demo ID.
	 * @return array
	 */
	private function get_required_plugins( $demo_id ) {
		$plugins = array(
			'main' => array(
				array(
					'name' => 'WooCommerce',
					'slug' => 'woocommerce',
					'required' => true,
				),
				array(
					'name' => 'Elementor Page Builder',
					'slug' => 'elementor',
					'required' => true,
				),
				array(
					'name' => 'Contact Form 7',
					'slug' => 'contact-form-7',
					'required' => true,
				),
			),
			'minimal' => array(
				array(
					'name' => 'Elementor Page Builder',
					'slug' => 'elementor',
					'required' => true,
				),
				array(
					'name' => 'Contact Form 7',
					'slug' => 'contact-form-7',
					'required' => true,
				),
			),
			'shop' => array(
				array(
					'name' => 'WooCommerce',
					'slug' => 'woocommerce',
					'required' => true,
				),
				array(
					'name' => 'Elementor Page Builder',
					'slug' => 'elementor',
					'required' => true,
				),
				array(
					'name' => 'WooCommerce Product Filter',
					'slug' => 'woocommerce-product-filter',
					'required' => true,
				),
			),
		);

		return isset( $plugins[ $demo_id ] ) ? $plugins[ $demo_id ] : array();
	}

	/**
	 * Check if a plugin is installed
	 *
	 * @param string $slug Plugin slug.
	 * @return bool
	 */
	private function is_plugin_installed( $slug ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$all_plugins = get_plugins();

		foreach ( $all_plugins as $plugin_path => $plugin_data ) {
			$plugin_file = basename( $plugin_path );
			$plugin_dir  = dirname( $plugin_path );

			if ( $plugin_dir === $slug || strpos( $plugin_file, $slug ) === 0 ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if a plugin is active
	 *
	 * @param string $slug Plugin slug.
	 * @return bool
	 */
	private function is_plugin_active( $slug ) {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$all_plugins = get_plugins();

		foreach ( $all_plugins as $plugin_path => $plugin_data ) {
			$plugin_file = basename( $plugin_path );
			$plugin_dir  = dirname( $plugin_path );

			if ( ( $plugin_dir === $slug || strpos( $plugin_file, $slug ) === 0 ) && is_plugin_active( $plugin_path ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Install a plugin
	 *
	 * @param string $slug Plugin slug.
	 * @return bool|WP_Error
	 */
	private function install_plugin( $slug ) {
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$api = plugins_api(
			'plugin_information',
			array(
				'slug'   => $slug,
				'fields' => array(
					'short_description' => false,
					'sections'          => false,
					'requires'          => false,
					'rating'            => false,
					'ratings'           => false,
					'downloaded'        => false,
					'last_updated'      => false,
					'added'             => false,
					'tags'              => false,
					'compatibility'     => false,
					'homepage'          => false,
					'donate_link'       => false,
				),
			)
		);

		if ( is_wp_error( $api ) ) {
			return $api;
		}

		$upgrader = new Plugin_Upgrader( new WP_Ajax_Upgrader_Skin() );
		$result   = $upgrader->install( $api->download_link );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return true;
	}

	/**
	 * Activate a plugin
	 *
	 * @param string $slug Plugin slug.
	 * @return bool|WP_Error
	 */
	private function activate_plugin( $slug ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$all_plugins = get_plugins();
		$plugin_path = '';

		foreach ( $all_plugins as $path => $plugin_data ) {
			$plugin_file = basename( $path );
			$plugin_dir  = dirname( $path );

			if ( $plugin_dir === $slug || strpos( $plugin_file, $slug ) === 0 ) {
				$plugin_path = $path;
				break;
			}
		}

		if ( empty( $plugin_path ) ) {
			return new WP_Error( 'plugin_not_found', esc_html__( 'Plugin not found.', 'aqualuxe' ) );
		}

		$result = activate_plugin( $plugin_path );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return true;
	}
}

// Initialize the demo importer
new AquaLuxe_Demo_Importer();