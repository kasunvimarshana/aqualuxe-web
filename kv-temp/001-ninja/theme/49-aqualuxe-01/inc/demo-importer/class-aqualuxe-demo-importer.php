<?php
/**
 * AquaLuxe Demo Importer Class
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AquaLuxe Demo Importer Class
 */
class AquaLuxe_Demo_Importer {

	/**
	 * Demo data directory
	 *
	 * @var string
	 */
	private $data_dir;

	/**
	 * Demo data URL
	 *
	 * @var string
	 */
	private $data_url;

	/**
	 * Demo types
	 *
	 * @var array
	 */
	private $demo_types = array(
		'main'     => 'Main Demo',
		'minimal'  => 'Minimal Demo',
		'fashion'  => 'Fashion Demo',
		'furniture' => 'Furniture Demo',
		'jewelry'  => 'Jewelry Demo',
	);

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->data_dir = get_template_directory() . '/inc/demo-importer/data/';
		$this->data_url = get_template_directory_uri() . '/inc/demo-importer/data/';

		// Add hooks
		add_filter( 'pt-ocdi/import_files', array( $this, 'import_files' ) );
		add_action( 'pt-ocdi/after_import', array( $this, 'after_import_setup' ) );
		add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'wp_ajax_aqualuxe_generate_mock_data', array( $this, 'ajax_generate_mock_data' ) );
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts( $hook ) {
		if ( 'appearance_page_aqualuxe-demo-importer' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'aqualuxe-demo-importer', get_template_directory_uri() . '/inc/demo-importer/assets/css/demo-importer.css', array(), AQUALUXE_VERSION );
		wp_enqueue_script( 'aqualuxe-demo-importer', get_template_directory_uri() . '/inc/demo-importer/assets/js/demo-importer.js', array( 'jquery' ), AQUALUXE_VERSION, true );
		
		wp_localize_script( 'aqualuxe-demo-importer', 'aqualuxeDemoImporter', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'aqualuxe-demo-importer' ),
			'generating' => __( 'Generating...', 'aqualuxe' ),
			'generated'  => __( 'Generated!', 'aqualuxe' ),
			'error'      => __( 'Error!', 'aqualuxe' ),
		) );
	}

	/**
	 * Add menu
	 */
	public function add_menu() {
		add_theme_page(
			__( 'Demo Importer', 'aqualuxe' ),
			__( 'Demo Importer', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-demo-importer',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Render page
	 */
	public function render_page() {
		?>
		<div class="wrap aqualuxe-demo-importer">
			<h1><?php esc_html_e( 'AquaLuxe Demo Importer', 'aqualuxe' ); ?></h1>
			
			<div class="aqualuxe-demo-importer-intro">
				<p><?php esc_html_e( 'Import demo content to get started with a pre-designed website. Choose from multiple demo types below.', 'aqualuxe' ); ?></p>
				
				<?php if ( ! class_exists( 'OCDI_Plugin' ) ) : ?>
					<div class="notice notice-error inline">
						<p>
							<?php 
							esc_html_e( 'The One Click Demo Import plugin is required to import demo content.', 'aqualuxe' );
							
							if ( current_user_can( 'install_plugins' ) ) {
								$url = wp_nonce_url(
									add_query_arg(
										array(
											'action' => 'install-plugin',
											'plugin' => 'one-click-demo-import',
										),
										admin_url( 'update.php' )
									),
									'install-plugin_one-click-demo-import'
								);
								echo ' <a href="' . esc_url( $url ) . '" class="button button-primary">' . esc_html__( 'Install and Activate', 'aqualuxe' ) . '</a>';
							}
							?>
						</p>
					</div>
				<?php endif; ?>
			</div>
			
			<div class="aqualuxe-demo-importer-grid">
				<?php foreach ( $this->demo_types as $demo_type => $demo_name ) : ?>
					<div class="aqualuxe-demo-item">
						<div class="aqualuxe-demo-item-image">
							<img src="<?php echo esc_url( $this->data_url . $demo_type . '/preview.jpg' ); ?>" alt="<?php echo esc_attr( $demo_name ); ?>">
						</div>
						<div class="aqualuxe-demo-item-content">
							<h3><?php echo esc_html( $demo_name ); ?></h3>
							<p><?php echo esc_html( $this->get_demo_description( $demo_type ) ); ?></p>
							<div class="aqualuxe-demo-item-actions">
								<a href="<?php echo esc_url( admin_url( 'themes.php?page=pt-one-click-demo-import' ) ); ?>" class="button button-primary <?php echo ! class_exists( 'OCDI_Plugin' ) ? 'disabled' : ''; ?>">
									<?php esc_html_e( 'Import Demo', 'aqualuxe' ); ?>
								</a>
								<a href="<?php echo esc_url( $this->get_demo_preview_url( $demo_type ) ); ?>" class="button" target="_blank">
									<?php esc_html_e( 'Preview', 'aqualuxe' ); ?>
								</a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			
			<div class="aqualuxe-demo-importer-tools">
				<h2><?php esc_html_e( 'Demo Importer Tools', 'aqualuxe' ); ?></h2>
				
				<div class="aqualuxe-demo-tool-card">
					<h3><?php esc_html_e( 'Generate Mock Data', 'aqualuxe' ); ?></h3>
					<p><?php esc_html_e( 'Generate mock data for testing purposes. This will create sample pages, posts, products, and other content.', 'aqualuxe' ); ?></p>
					<button id="aqualuxe-generate-mock-data" class="button button-primary">
						<?php esc_html_e( 'Generate Mock Data', 'aqualuxe' ); ?>
					</button>
					<div id="aqualuxe-mock-data-result" class="aqualuxe-mock-data-result"></div>
				</div>
				
				<div class="aqualuxe-demo-tool-card">
					<h3><?php esc_html_e( 'Reset Demo Content', 'aqualuxe' ); ?></h3>
					<p><?php esc_html_e( 'Reset all imported demo content. This will remove all pages, posts, products, and other content created by the demo importer.', 'aqualuxe' ); ?></p>
					<button id="aqualuxe-reset-demo-content" class="button button-secondary">
						<?php esc_html_e( 'Reset Demo Content', 'aqualuxe' ); ?>
					</button>
				</div>
			</div>
			
			<div class="aqualuxe-demo-importer-faq">
				<h2><?php esc_html_e( 'Frequently Asked Questions', 'aqualuxe' ); ?></h2>
				
				<div class="aqualuxe-demo-faq-item">
					<h3><?php esc_html_e( 'What will be imported?', 'aqualuxe' ); ?></h3>
					<div class="aqualuxe-demo-faq-content">
						<p><?php esc_html_e( 'The demo import will include:', 'aqualuxe' ); ?></p>
						<ul>
							<li><?php esc_html_e( 'Pages (Home, About, Services, Blog, Contact, FAQ, etc.)', 'aqualuxe' ); ?></li>
							<li><?php esc_html_e( 'Products (if WooCommerce is active)', 'aqualuxe' ); ?></li>
							<li><?php esc_html_e( 'Product categories and attributes', 'aqualuxe' ); ?></li>
							<li><?php esc_html_e( 'Blog posts and categories', 'aqualuxe' ); ?></li>
							<li><?php esc_html_e( 'Menus and widgets', 'aqualuxe' ); ?></li>
							<li><?php esc_html_e( 'Theme customizer settings', 'aqualuxe' ); ?></li>
						</ul>
					</div>
				</div>
				
				<div class="aqualuxe-demo-faq-item">
					<h3><?php esc_html_e( 'Will the import delete my existing content?', 'aqualuxe' ); ?></h3>
					<div class="aqualuxe-demo-faq-content">
						<p><?php esc_html_e( 'No, the import will not delete your existing content. However, it may modify some settings like the homepage, menus, and widgets.', 'aqualuxe' ); ?></p>
					</div>
				</div>
				
				<div class="aqualuxe-demo-faq-item">
					<h3><?php esc_html_e( 'How long does the import process take?', 'aqualuxe' ); ?></h3>
					<div class="aqualuxe-demo-faq-content">
						<p><?php esc_html_e( 'The import process typically takes 2-5 minutes, depending on your server speed and the demo type you choose.', 'aqualuxe' ); ?></p>
					</div>
				</div>
				
				<div class="aqualuxe-demo-faq-item">
					<h3><?php esc_html_e( 'Will the imported site look exactly like the demo?', 'aqualuxe' ); ?></h3>
					<div class="aqualuxe-demo-faq-content">
						<p><?php esc_html_e( 'The imported site will have the same structure and settings as the demo, but some images will be replaced with placeholder images due to copyright restrictions.', 'aqualuxe' ); ?></p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get demo description
	 *
	 * @param string $demo_type Demo type
	 * @return string Demo description
	 */
	private function get_demo_description( $demo_type ) {
		$descriptions = array(
			'main'      => __( 'Complete demo with all pages, products, and features.', 'aqualuxe' ),
			'minimal'   => __( 'Minimal version with fewer demo products and simplified design.', 'aqualuxe' ),
			'fashion'   => __( 'Specialized demo for fashion and clothing stores.', 'aqualuxe' ),
			'furniture' => __( 'Specialized demo for furniture and home decor stores.', 'aqualuxe' ),
			'jewelry'   => __( 'Specialized demo for jewelry and accessories stores.', 'aqualuxe' ),
		);

		return isset( $descriptions[ $demo_type ] ) ? $descriptions[ $demo_type ] : '';
	}

	/**
	 * Get demo preview URL
	 *
	 * @param string $demo_type Demo type
	 * @return string Demo preview URL
	 */
	private function get_demo_preview_url( $demo_type ) {
		$preview_urls = array(
			'main'      => 'https://aqualuxe.com/demo/',
			'minimal'   => 'https://aqualuxe.com/demo-minimal/',
			'fashion'   => 'https://aqualuxe.com/demo-fashion/',
			'furniture' => 'https://aqualuxe.com/demo-furniture/',
			'jewelry'   => 'https://aqualuxe.com/demo-jewelry/',
		);

		return isset( $preview_urls[ $demo_type ] ) ? $preview_urls[ $demo_type ] : '#';
	}

	/**
	 * Define demo import files
	 *
	 * @return array Demo import files
	 */
	public function import_files() {
		$import_files = array();

		foreach ( $this->demo_types as $demo_type => $demo_name ) {
			$import_files[] = array(
				'import_file_name'           => $demo_name,
				'categories'                 => array( 'E-commerce', ucfirst( $demo_type ) ),
				'local_import_file'          => $this->data_dir . $demo_type . '/content.xml',
				'local_import_widget_file'   => $this->data_dir . $demo_type . '/widgets.wie',
				'local_import_customizer_file' => $this->data_dir . $demo_type . '/customizer.dat',
				'import_preview_image_url'   => $this->data_url . $demo_type . '/preview.jpg',
				'import_notice'              => $this->get_import_notice( $demo_type ),
				'preview_url'                => $this->get_demo_preview_url( $demo_type ),
			);
		}

		return $import_files;
	}

	/**
	 * Get import notice
	 *
	 * @param string $demo_type Demo type
	 * @return string Import notice
	 */
	private function get_import_notice( $demo_type ) {
		$notices = array(
			'main'      => __( 'After importing this demo, you will need to setup the slider separately.', 'aqualuxe' ),
			'minimal'   => __( 'This is a minimal version with fewer demo products.', 'aqualuxe' ),
			'fashion'   => __( 'This demo is optimized for fashion and clothing stores.', 'aqualuxe' ),
			'furniture' => __( 'This demo is optimized for furniture and home decor stores.', 'aqualuxe' ),
			'jewelry'   => __( 'This demo is optimized for jewelry and accessories stores.', 'aqualuxe' ),
		);

		return isset( $notices[ $demo_type ] ) ? $notices[ $demo_type ] : '';
	}

	/**
	 * Setup after demo import
	 *
	 * @param array $selected_import Selected import data
	 */
	public function after_import_setup( $selected_import ) {
		// Set menu locations
		$this->setup_menus( $selected_import );
		
		// Set front page and blog page
		$this->setup_pages( $selected_import );
		
		// Set WooCommerce pages
		$this->setup_woocommerce( $selected_import );
		
		// Setup widgets
		$this->setup_widgets( $selected_import );
		
		// Regenerate CSS files
		$this->regenerate_css( $selected_import );
	}

	/**
	 * Setup menus
	 *
	 * @param array $selected_import Selected import data
	 */
	private function setup_menus( $selected_import ) {
		$main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
		$footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );
		
		set_theme_mod( 'nav_menu_locations', array(
			'primary' => $main_menu ? $main_menu->term_id : 0,
			'footer'  => $footer_menu ? $footer_menu->term_id : 0,
		) );
	}

	/**
	 * Setup pages
	 *
	 * @param array $selected_import Selected import data
	 */
	private function setup_pages( $selected_import ) {
		$front_page = get_page_by_title( 'Home' );
		$blog_page = get_page_by_title( 'Blog' );
		
		if ( $front_page ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front_page->ID );
		}
		
		if ( $blog_page ) {
			update_option( 'page_for_posts', $blog_page->ID );
		}
	}

	/**
	 * Setup WooCommerce
	 *
	 * @param array $selected_import Selected import data
	 */
	private function setup_woocommerce( $selected_import ) {
		if ( class_exists( 'WooCommerce' ) ) {
			$shop_page = get_page_by_title( 'Shop' );
			$cart_page = get_page_by_title( 'Cart' );
			$checkout_page = get_page_by_title( 'Checkout' );
			$account_page = get_page_by_title( 'My Account' );
			
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
			
			// Update product attributes
			$this->update_product_attributes();
		}
	}

	/**
	 * Setup widgets
	 *
	 * @param array $selected_import Selected import data
	 */
	private function setup_widgets( $selected_import ) {
		// Additional widget setup if needed
	}

	/**
	 * Regenerate CSS
	 *
	 * @param array $selected_import Selected import data
	 */
	private function regenerate_css( $selected_import ) {
		if ( class_exists( 'Elementor\Plugin' ) ) {
			Elementor\Plugin::$instance->files_manager->clear_cache();
		}
	}

	/**
	 * Update product attributes
	 */
	private function update_product_attributes() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Define attributes
		$attributes = array(
			'color' => array(
				'name'         => 'Color',
				'slug'         => 'color',
				'type'         => 'select',
				'order_by'     => 'menu_order',
				'has_archives' => false,
				'terms'        => array(
					'Black',
					'Blue',
					'Green',
					'Red',
					'Yellow',
					'White',
					'Gold',
					'Silver',
					'Purple',
					'Orange',
					'Pink',
					'Brown',
				),
			),
			'size' => array(
				'name'         => 'Size',
				'slug'         => 'size',
				'type'         => 'select',
				'order_by'     => 'menu_order',
				'has_archives' => false,
				'terms'        => array(
					'Small',
					'Medium',
					'Large',
					'Extra Large',
					'XXL',
					'One Size',
					'36',
					'38',
					'40',
					'42',
					'44',
					'46',
				),
			),
			'material' => array(
				'name'         => 'Material',
				'slug'         => 'material',
				'type'         => 'select',
				'order_by'     => 'menu_order',
				'has_archives' => false,
				'terms'        => array(
					'Glass',
					'Plastic',
					'Acrylic',
					'Metal',
					'Wood',
					'Cotton',
					'Polyester',
					'Leather',
					'Silk',
					'Wool',
					'Linen',
					'Ceramic',
				),
			),
			'style' => array(
				'name'         => 'Style',
				'slug'         => 'style',
				'type'         => 'select',
				'order_by'     => 'menu_order',
				'has_archives' => false,
				'terms'        => array(
					'Casual',
					'Formal',
					'Sports',
					'Vintage',
					'Modern',
					'Classic',
					'Bohemian',
					'Minimalist',
					'Elegant',
					'Retro',
					'Contemporary',
					'Traditional',
				),
			),
		);
		
		// Create attributes
		foreach ( $attributes as $attribute_slug => $attribute_data ) {
			$attribute_id = wc_attribute_taxonomy_id_by_name( $attribute_slug );
			
			if ( ! $attribute_id ) {
				$attribute_id = wc_create_attribute( array(
					'name'         => $attribute_data['name'],
					'slug'         => $attribute_data['slug'],
					'type'         => $attribute_data['type'],
					'order_by'     => $attribute_data['order_by'],
					'has_archives' => $attribute_data['has_archives'],
				) );
			}
			
			// Register the taxonomy
			$taxonomy_name = wc_attribute_taxonomy_name( $attribute_slug );
			
			// Create terms
			foreach ( $attribute_data['terms'] as $term ) {
				if ( ! term_exists( $term, $taxonomy_name ) ) {
					wp_insert_term( $term, $taxonomy_name );
				}
			}
		}
		
		// Clear transients
		delete_transient( 'wc_attribute_taxonomies' );
	}

	/**
	 * AJAX generate mock data
	 */
	public function ajax_generate_mock_data() {
		// Check nonce
		if ( ! check_ajax_referer( 'aqualuxe-demo-importer', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ) );
		}

		// Check permissions
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to do this.', 'aqualuxe' ) ) );
		}

		// Include mock data generator
		require_once get_template_directory() . '/inc/demo-importer/class-aqualuxe-mock-data-generator.php';
		$generator = new AquaLuxe_Mock_Data_Generator();
		
		// Generate mock data
		$result = $generator->generate();

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success( array(
			'message' => __( 'Mock data generated successfully!', 'aqualuxe' ),
			'stats'   => $result,
		) );
	}
}

// Initialize the demo importer
new AquaLuxe_Demo_Importer();