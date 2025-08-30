<?php
/**
 * Demo Content Importer
 *
 * Handles the import of demo content for the AquaLuxe theme.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Demo Content Importer Class
 */
class AquaLuxe_Demo_Importer {

	/**
	 * Instance of this class.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Get an instance of this class.
	 *
	 * @since 1.0.0
	 * @return AquaLuxe_Demo_Importer
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Add the demo import page to the admin menu.
		add_action( 'admin_menu', array( $this, 'add_demo_import_page' ) );
		
		// Register the AJAX handler for the demo import.
		add_action( 'wp_ajax_aqualuxe_import_demo_content', array( $this, 'import_demo_content' ) );
		
		// Enqueue scripts and styles for the demo import page.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Add the demo import page to the admin menu.
	 *
	 * @since 1.0.0
	 */
	public function add_demo_import_page() {
		add_theme_page(
			__( 'Import Demo Content', 'aqualuxe' ),
			__( 'Import Demo Content', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-demo-import',
			array( $this, 'render_demo_import_page' )
		);
	}

	/**
	 * Render the demo import page.
	 *
	 * @since 1.0.0
	 */
	public function render_demo_import_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'AquaLuxe Demo Content Import', 'aqualuxe' ); ?></h1>
			
			<div class="aqualuxe-demo-import-container">
				<div class="aqualuxe-demo-import-header">
					<p><?php esc_html_e( 'Import the demo content to get started with your AquaLuxe theme. This will import pages, posts, products, categories, and other content to help you get started.', 'aqualuxe' ); ?></p>
					<p class="aqualuxe-demo-import-warning"><?php esc_html_e( 'Note: It is recommended to import demo content on a fresh WordPress installation to avoid conflicts with existing content.', 'aqualuxe' ); ?></p>
				</div>
				
				<div class="aqualuxe-demo-import-options">
					<h2><?php esc_html_e( 'Available Demo Content', 'aqualuxe' ); ?></h2>
					
					<div class="aqualuxe-demo-option">
						<div class="aqualuxe-demo-option-image">
							<img src="<?php echo esc_url( get_template_directory_uri() . '/screenshot.png' ); ?>" alt="<?php esc_attr_e( 'AquaLuxe Demo', 'aqualuxe' ); ?>">
						</div>
						<div class="aqualuxe-demo-option-content">
							<h3><?php esc_html_e( 'AquaLuxe Full Demo', 'aqualuxe' ); ?></h3>
							<p><?php esc_html_e( 'Import the complete AquaLuxe demo content including pages, posts, products, categories, and customizer settings.', 'aqualuxe' ); ?></p>
							<button id="aqualuxe-import-demo" class="button button-primary"><?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?></button>
							<div id="aqualuxe-import-progress" class="aqualuxe-import-progress" style="display: none;">
								<div class="aqualuxe-import-progress-bar"></div>
								<div class="aqualuxe-import-progress-text">0%</div>
							</div>
							<div id="aqualuxe-import-status" class="aqualuxe-import-status"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Enqueue scripts and styles for the demo import page.
	 *
	 * @since 1.0.0
	 * @param string $hook The current admin page.
	 */
	public function enqueue_scripts( $hook ) {
		if ( 'appearance_page_aqualuxe-demo-import' !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'aqualuxe-demo-import',
			get_template_directory_uri() . '/assets/css/admin/demo-import.css',
			array(),
			AQUALUXE_VERSION
		);

		wp_enqueue_script(
			'aqualuxe-demo-import',
			get_template_directory_uri() . '/assets/js/admin/demo-import.js',
			array( 'jquery' ),
			AQUALUXE_VERSION,
			true
		);

		wp_localize_script(
			'aqualuxe-demo-import',
			'aqualuxeImport',
			array(
				'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce( 'aqualuxe-import-demo-nonce' ),
				'importing' => __( 'Importing...', 'aqualuxe' ),
				'complete'  => __( 'Import Complete!', 'aqualuxe' ),
				'error'     => __( 'Import Error', 'aqualuxe' ),
			)
		);
	}

	/**
	 * Import demo content via AJAX.
	 *
	 * @since 1.0.0
	 */
	public function import_demo_content() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-import-demo-nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid nonce. Please refresh the page and try again.', 'aqualuxe' ) ) );
		}

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to import content.', 'aqualuxe' ) ) );
		}

		// Make sure WordPress Importer is available.
		if ( ! class_exists( 'WP_Importer' ) ) {
			require ABSPATH . '/wp-admin/includes/class-wp-importer.php';
		}

		// Make sure WordPress Import is available.
		if ( ! class_exists( 'WP_Import' ) ) {
			require get_template_directory() . '/inc/importers/wordpress-importer.php';
		}

		// Import XML content.
		$importer = new WP_Import();
		$importer->fetch_attachments = true;

		// Path to the demo content XML file.
		$xml_file = get_template_directory() . '/demo-content/demo-content.xml';

		// Check if the file exists.
		if ( ! file_exists( $xml_file ) ) {
			wp_send_json_error( array( 'message' => __( 'Demo content file not found.', 'aqualuxe' ) ) );
		}

		// Import the content.
		ob_start();
		$importer->import( $xml_file );
		ob_end_clean();

		// Import customizer settings.
		$this->import_customizer_settings();

		// Import widgets.
		$this->import_widgets();

		// Set up menus.
		$this->setup_menus();

		// Set up homepage and blog page.
		$this->setup_pages();

		// Set up WooCommerce pages if WooCommerce is active.
		if ( class_exists( 'WooCommerce' ) ) {
			$this->setup_woocommerce();
		}

		// Send success response.
		wp_send_json_success( array( 'message' => __( 'Demo content imported successfully!', 'aqualuxe' ) ) );
	}

	/**
	 * Import customizer settings.
	 *
	 * @since 1.0.0
	 */
	private function import_customizer_settings() {
		// Path to the customizer settings JSON file.
		$customizer_file = get_template_directory() . '/demo-content/customizer.json';

		// Check if the file exists.
		if ( ! file_exists( $customizer_file ) ) {
			return;
		}

		// Get the customizer settings from the JSON file.
		$customizer_data = json_decode( file_get_contents( $customizer_file ), true );

		// If the data is valid, import the settings.
		if ( $customizer_data && is_array( $customizer_data ) ) {
			foreach ( $customizer_data as $key => $value ) {
				set_theme_mod( $key, $value );
			}
		}
	}

	/**
	 * Import widgets.
	 *
	 * @since 1.0.0
	 */
	private function import_widgets() {
		// Path to the widgets JSON file.
		$widgets_file = get_template_directory() . '/demo-content/widgets.json';

		// Check if the file exists.
		if ( ! file_exists( $widgets_file ) ) {
			return;
		}

		// Get the widgets data from the JSON file.
		$widgets_data = json_decode( file_get_contents( $widgets_file ), true );

		// If the data is valid, import the widgets.
		if ( $widgets_data && is_array( $widgets_data ) ) {
			// Get all available widgets.
			$available_widgets = $this->get_available_widgets();

			// Get all existing widget instances.
			$widget_instances = array();
			foreach ( $available_widgets as $widget_data ) {
				$widget_instances[ $widget_data['id_base'] ] = get_option( 'widget_' . $widget_data['id_base'] );
			}

			// Loop through widgets data.
			foreach ( $widgets_data as $sidebar_id => $widgets ) {
				// Skip inactive widgets sidebar.
				if ( 'wp_inactive_widgets' === $sidebar_id ) {
					continue;
				}

				// Check if the sidebar is available on this site.
				if ( ! is_registered_sidebar( $sidebar_id ) ) {
					continue;
				}

				// Loop through widgets in this sidebar.
				foreach ( $widgets as $widget_instance_id => $widget ) {
					// Get id_base (e.g., 'text') from widget instance ID.
					$id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );

					// Skip if widget is not available.
					if ( ! isset( $available_widgets[ $id_base ] ) ) {
						continue;
					}

					// Add widget instance.
					$widget_instances[ $id_base ][ $widget_instance_id ] = $widget;
				}
			}

			// Update widget options.
			foreach ( $widget_instances as $id_base => $instances ) {
				update_option( 'widget_' . $id_base, $instances );
			}

			// Update sidebars widgets.
			$sidebars_widgets = array();
			foreach ( $widgets_data as $sidebar_id => $widgets ) {
				$sidebars_widgets[ $sidebar_id ] = array_keys( $widgets );
			}

			// Add inactive widgets sidebar.
			$sidebars_widgets['wp_inactive_widgets'] = array();

			// Update sidebars widgets.
			update_option( 'sidebars_widgets', $sidebars_widgets );
		}
	}

	/**
	 * Get available widgets.
	 *
	 * @since 1.0.0
	 * @return array Available widgets.
	 */
	private function get_available_widgets() {
		global $wp_registered_widget_controls;

		$widget_controls = $wp_registered_widget_controls;
		$available_widgets = array();

		foreach ( $widget_controls as $widget ) {
			if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[ $widget['id_base'] ] ) ) {
				$available_widgets[ $widget['id_base'] ]['id_base'] = $widget['id_base'];
				$available_widgets[ $widget['id_base'] ]['name'] = $widget['name'];
			}
		}

		return $available_widgets;
	}

	/**
	 * Set up menus.
	 *
	 * @since 1.0.0
	 */
	private function setup_menus() {
		// Create Primary Menu.
		$primary_menu_name = 'Primary Menu';
		$primary_menu_exists = wp_get_nav_menu_object( $primary_menu_name );

		if ( ! $primary_menu_exists ) {
			$primary_menu_id = wp_create_nav_menu( $primary_menu_name );

			// Set up menu items.
			$menu_items = array(
				'Home'     => '/',
				'Shop'     => '/shop',
				'About'    => '/about',
				'Services' => '/services',
				'Blog'     => '/blog',
				'Contact'  => '/contact',
			);

			foreach ( $menu_items as $title => $url ) {
				wp_update_nav_menu_item(
					$primary_menu_id,
					0,
					array(
						'menu-item-title'  => $title,
						'menu-item-url'    => home_url( $url ),
						'menu-item-status' => 'publish',
					)
				);
			}

			// Assign menu to primary location.
			$locations = get_theme_mod( 'nav_menu_locations' );
			$locations['primary'] = $primary_menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}

		// Create Footer Menu.
		$footer_menu_name = 'Footer Menu';
		$footer_menu_exists = wp_get_nav_menu_object( $footer_menu_name );

		if ( ! $footer_menu_exists ) {
			$footer_menu_id = wp_create_nav_menu( $footer_menu_name );

			// Set up menu items.
			$menu_items = array(
				'Home'           => '/',
				'About'          => '/about',
				'Services'       => '/services',
				'Contact'        => '/contact',
				'Privacy Policy' => '/privacy-policy',
				'Terms of Service' => '/terms-of-service',
			);

			foreach ( $menu_items as $title => $url ) {
				wp_update_nav_menu_item(
					$footer_menu_id,
					0,
					array(
						'menu-item-title'  => $title,
						'menu-item-url'    => home_url( $url ),
						'menu-item-status' => 'publish',
					)
				);
			}

			// Assign menu to footer location.
			$locations = get_theme_mod( 'nav_menu_locations' );
			$locations['footer'] = $footer_menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}

	/**
	 * Set up homepage and blog page.
	 *
	 * @since 1.0.0
	 */
	private function setup_pages() {
		// Set homepage.
		$home = get_page_by_path( 'home' );
		if ( $home ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $home->ID );
		}

		// Create blog page if it doesn't exist.
		$blog = get_page_by_path( 'blog' );
		if ( ! $blog ) {
			$blog_id = wp_insert_post(
				array(
					'post_title'     => 'Blog',
					'post_content'   => '',
					'post_status'    => 'publish',
					'post_type'      => 'page',
					'comment_status' => 'closed',
				)
			);
		} else {
			$blog_id = $blog->ID;
		}

		// Set blog page.
		update_option( 'page_for_posts', $blog_id );
	}

	/**
	 * Set up WooCommerce pages.
	 *
	 * @since 1.0.0
	 */
	private function setup_woocommerce() {
		// Run WooCommerce installation if needed.
		if ( class_exists( 'WooCommerce' ) && ! get_option( 'woocommerce_db_version' ) ) {
			WC_Install::install();
		}

		// Flush rewrite rules.
		flush_rewrite_rules();
	}
}

// Initialize the demo importer.
AquaLuxe_Demo_Importer::get_instance();