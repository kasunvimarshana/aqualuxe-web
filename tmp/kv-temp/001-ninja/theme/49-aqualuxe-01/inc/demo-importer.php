<?php
/**
 * Demo content importer for AquaLuxe theme
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Setup demo content importer
 */
function aqualuxe_demo_importer_setup() {
	// Check if One Click Demo Import plugin is active
	if ( class_exists( 'OCDI_Plugin' ) ) {
		add_filter( 'pt-ocdi/import_files', 'aqualuxe_demo_import_files' );
		add_action( 'pt-ocdi/after_import', 'aqualuxe_after_import_setup' );
		add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );
	}
}
add_action( 'after_setup_theme', 'aqualuxe_demo_importer_setup' );

/**
 * Define demo import files
 *
 * @return array Demo import files
 */
function aqualuxe_demo_import_files() {
	return array(
		array(
			'import_file_name'           => 'AquaLuxe Demo',
			'categories'                 => array( 'E-commerce', 'Business' ),
			'local_import_file'          => trailingslashit( get_template_directory() ) . 'inc/demo-importer/data/main/content.xml',
			'local_import_widget_file'   => trailingslashit( get_template_directory() ) . 'inc/demo-importer/data/main/widgets.wie',
			'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'inc/demo-importer/data/main/customizer.dat',
			'import_preview_image_url'   => trailingslashit( get_template_directory_uri() ) . 'inc/demo-importer/data/main/preview.jpg',
			'import_notice'              => __( 'After importing this demo, you will need to setup the slider separately.', 'aqualuxe' ),
			'preview_url'                => 'https://aqualuxe.com/demo/',
		),
		array(
			'import_file_name'           => 'AquaLuxe Minimal',
			'categories'                 => array( 'E-commerce', 'Minimal' ),
			'local_import_file'          => trailingslashit( get_template_directory() ) . 'inc/demo-importer/data/minimal/content.xml',
			'local_import_widget_file'   => trailingslashit( get_template_directory() ) . 'inc/demo-importer/data/minimal/widgets.wie',
			'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'inc/demo-importer/data/minimal/customizer.dat',
			'import_preview_image_url'   => trailingslashit( get_template_directory_uri() ) . 'inc/demo-importer/data/minimal/preview.jpg',
			'import_notice'              => __( 'This is a minimal version with fewer demo products.', 'aqualuxe' ),
			'preview_url'                => 'https://aqualuxe.com/demo-minimal/',
		),
		array(
			'import_file_name'           => 'AquaLuxe Fashion',
			'categories'                 => array( 'E-commerce', 'Fashion' ),
			'local_import_file'          => trailingslashit( get_template_directory() ) . 'inc/demo-importer/data/fashion/content.xml',
			'local_import_widget_file'   => trailingslashit( get_template_directory() ) . 'inc/demo-importer/data/fashion/widgets.wie',
			'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'inc/demo-importer/data/fashion/customizer.dat',
			'import_preview_image_url'   => trailingslashit( get_template_directory_uri() ) . 'inc/demo-importer/data/fashion/preview.jpg',
			'import_notice'              => __( 'This demo is optimized for fashion and clothing stores.', 'aqualuxe' ),
			'preview_url'                => 'https://aqualuxe.com/demo-fashion/',
		),
		array(
			'import_file_name'           => 'AquaLuxe Furniture',
			'categories'                 => array( 'E-commerce', 'Furniture' ),
			'local_import_file'          => trailingslashit( get_template_directory() ) . 'inc/demo-importer/data/furniture/content.xml',
			'local_import_widget_file'   => trailingslashit( get_template_directory() ) . 'inc/demo-importer/data/furniture/widgets.wie',
			'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'inc/demo-importer/data/furniture/customizer.dat',
			'import_preview_image_url'   => trailingslashit( get_template_directory_uri() ) . 'inc/demo-importer/data/furniture/preview.jpg',
			'import_notice'              => __( 'This demo is optimized for furniture and home decor stores.', 'aqualuxe' ),
			'preview_url'                => 'https://aqualuxe.com/demo-furniture/',
		),
		array(
			'import_file_name'           => 'AquaLuxe Jewelry',
			'categories'                 => array( 'E-commerce', 'Jewelry' ),
			'local_import_file'          => trailingslashit( get_template_directory() ) . 'inc/demo-importer/data/jewelry/content.xml',
			'local_import_widget_file'   => trailingslashit( get_template_directory() ) . 'inc/demo-importer/data/jewelry/widgets.wie',
			'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'inc/demo-importer/data/jewelry/customizer.dat',
			'import_preview_image_url'   => trailingslashit( get_template_directory_uri() ) . 'inc/demo-importer/data/jewelry/preview.jpg',
			'import_notice'              => __( 'This demo is optimized for jewelry and accessories stores.', 'aqualuxe' ),
			'preview_url'                => 'https://aqualuxe.com/demo-jewelry/',
		),
	);
}

/**
 * Setup after demo import
 *
 * @param array $selected_import Selected import data
 */
function aqualuxe_after_import_setup( $selected_import ) {
	// Set menu locations
	$main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
	$footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );
	
	set_theme_mod( 'nav_menu_locations', array(
		'primary' => $main_menu ? $main_menu->term_id : 0,
		'footer'  => $footer_menu ? $footer_menu->term_id : 0,
	) );
	
	// Set front page and blog page
	$front_page = get_page_by_title( 'Home' );
	$blog_page = get_page_by_title( 'Blog' );
	
	if ( $front_page ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page->ID );
	}
	
	if ( $blog_page ) {
		update_option( 'page_for_posts', $blog_page->ID );
	}
	
	// Set WooCommerce pages
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
		aqualuxe_update_product_attributes();
	}
	
	// Regenerate CSS files
	if ( class_exists( 'Elementor\Plugin' ) ) {
		Elementor\Plugin::$instance->files_manager->clear_cache();
	}
}

/**
 * Update product attributes
 */
function aqualuxe_update_product_attributes() {
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
 * Add admin notice if One Click Demo Import plugin is not active
 */
function aqualuxe_demo_importer_admin_notice() {
	if ( ! class_exists( 'OCDI_Plugin' ) ) {
		?>
		<div class="notice notice-info is-dismissible">
			<p><?php esc_html_e( 'To import demo content for AquaLuxe theme, please install and activate the One Click Demo Import plugin.', 'aqualuxe' ); ?></p>
			<p>
				<?php
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
					?>
					<a href="<?php echo esc_url( $url ); ?>" class="button button-primary"><?php esc_html_e( 'Install and Activate', 'aqualuxe' ); ?></a>
					<?php
				}
				?>
			</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'aqualuxe_demo_importer_admin_notice' );

/**
 * Create demo content directory structure
 */
function aqualuxe_create_demo_content_directory() {
	$demo_dir = get_template_directory() . '/inc/demo-importer/data';
	
	// Create directories if they don't exist
	if ( ! file_exists( $demo_dir ) ) {
		wp_mkdir_p( $demo_dir );
	}
	
	// Create subdirectories for each demo type
	$demo_types = array( 'main', 'minimal', 'fashion', 'furniture', 'jewelry' );
	
	foreach ( $demo_types as $demo_type ) {
		$type_dir = $demo_dir . '/' . $demo_type;
		
		if ( ! file_exists( $type_dir ) ) {
			wp_mkdir_p( $type_dir );
		}
	}
}
add_action( 'after_switch_theme', 'aqualuxe_create_demo_content_directory' );

/**
 * Add demo content menu to admin
 */
function aqualuxe_demo_content_menu() {
	add_theme_page(
		__( 'Import Demo Content', 'aqualuxe' ),
		__( 'Import Demo Content', 'aqualuxe' ),
		'manage_options',
		'aqualuxe-demo-content',
		'aqualuxe_demo_content_page'
	);
}
add_action( 'admin_menu', 'aqualuxe_demo_content_menu' );

/**
 * Demo content page
 */
function aqualuxe_demo_content_page() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Import AquaLuxe Demo Content', 'aqualuxe' ); ?></h1>
		
		<?php if ( class_exists( 'OCDI_Plugin' ) ) : ?>
			<p><?php esc_html_e( 'Import the demo content to get started with a pre-designed website.', 'aqualuxe' ); ?></p>
			<p>
				<a href="<?php echo esc_url( admin_url( 'themes.php?page=pt-one-click-demo-import' ) ); ?>" class="button button-primary">
					<?php esc_html_e( 'Go to Demo Import', 'aqualuxe' ); ?>
				</a>
			</p>
		<?php else : ?>
			<div class="notice notice-error">
				<p><?php esc_html_e( 'The One Click Demo Import plugin is required to import demo content.', 'aqualuxe' ); ?></p>
				<?php if ( current_user_can( 'install_plugins' ) ) : ?>
					<p>
						<?php
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
						?>
						<a href="<?php echo esc_url( $url ); ?>" class="button button-primary"><?php esc_html_e( 'Install and Activate', 'aqualuxe' ); ?></a>
					</p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		
		<div class="aqualuxe-demo-content-info">
			<h2><?php esc_html_e( 'Available Demo Content', 'aqualuxe' ); ?></h2>
			
			<div class="aqualuxe-demo-content-grid">
				<div class="aqualuxe-demo-content-item">
					<h3><?php esc_html_e( 'AquaLuxe Demo', 'aqualuxe' ); ?></h3>
					<p><?php esc_html_e( 'Complete demo with all pages, products, and features.', 'aqualuxe' ); ?></p>
				</div>
				
				<div class="aqualuxe-demo-content-item">
					<h3><?php esc_html_e( 'AquaLuxe Minimal', 'aqualuxe' ); ?></h3>
					<p><?php esc_html_e( 'Minimal version with fewer demo products.', 'aqualuxe' ); ?></p>
				</div>
				
				<div class="aqualuxe-demo-content-item">
					<h3><?php esc_html_e( 'AquaLuxe Fashion', 'aqualuxe' ); ?></h3>
					<p><?php esc_html_e( 'Specialized demo for fashion and clothing stores.', 'aqualuxe' ); ?></p>
				</div>
				
				<div class="aqualuxe-demo-content-item">
					<h3><?php esc_html_e( 'AquaLuxe Furniture', 'aqualuxe' ); ?></h3>
					<p><?php esc_html_e( 'Specialized demo for furniture and home decor stores.', 'aqualuxe' ); ?></p>
				</div>
				
				<div class="aqualuxe-demo-content-item">
					<h3><?php esc_html_e( 'AquaLuxe Jewelry', 'aqualuxe' ); ?></h3>
					<p><?php esc_html_e( 'Specialized demo for jewelry and accessories stores.', 'aqualuxe' ); ?></p>
				</div>
			</div>
			
			<h2><?php esc_html_e( 'What Will Be Imported', 'aqualuxe' ); ?></h2>
			<ul>
				<li><?php esc_html_e( 'Pages (Home, About, Services, Blog, Contact, FAQ, etc.)', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'Products (if WooCommerce is active)', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'Product categories and attributes', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'Blog posts and categories', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'Menus and widgets', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'Theme customizer settings', 'aqualuxe' ); ?></li>
			</ul>
			
			<h2><?php esc_html_e( 'Important Notes', 'aqualuxe' ); ?></h2>
			<ul>
				<li><?php esc_html_e( 'The import process may take several minutes.', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'Images will be replaced with placeholder images due to copyright restrictions.', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'It is recommended to import demo content on a fresh WordPress installation.', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'The import will not delete any existing content, but it may modify some settings.', 'aqualuxe' ); ?></li>
			</ul>
		</div>
	</div>
	
	<style>
		.aqualuxe-demo-content-grid {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
			gap: 20px;
			margin: 20px 0;
		}
		
		.aqualuxe-demo-content-item {
			border: 1px solid #ddd;
			padding: 20px;
			border-radius: 5px;
			background: #fff;
		}
		
		.aqualuxe-demo-content-info {
			margin-top: 30px;
		}
	</style>
	<?php
}

// Include advanced demo importer classes
require_once get_template_directory() . '/inc/demo-importer/class-aqualuxe-demo-importer.php';