<?php
/**
 * Demo Content Importer Module
 *
 * Comprehensive demo content importer with flush mechanism
 *
 * @package AquaLuxe
 * @since 2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Demo Importer Module Class
 */
class AquaLuxe_Demo_Importer_Module {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'wp_ajax_aqualuxe_import_demo', array( $this, 'handle_import_ajax' ) );
		add_action( 'wp_ajax_aqualuxe_flush_demo', array( $this, 'handle_flush_ajax' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Add admin menu
	 */
	public function add_admin_menu() {
		add_theme_page(
			__( 'Demo Import', 'aqualuxe' ),
			__( 'Demo Import', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-demo-import',
			array( $this, 'admin_page' )
		);
	}

	/**
	 * Enqueue admin scripts
	 */
	public function enqueue_scripts( $hook ) {
		if ( $hook !== 'appearance_page_aqualuxe-demo-import' ) {
			return;
		}

		wp_enqueue_script( 'aqualuxe-demo-importer', get_template_directory_uri() . '/assets/dist/js/admin.js', array( 'jquery' ), AQUALUXE_VERSION, true );
		wp_localize_script( 'aqualuxe-demo-importer', 'aqualuxeDemoImporter', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'aqualuxe_demo_importer' ),
			'strings' => array(
				'importing'    => __( 'Importing demo content...', 'aqualuxe' ),
				'flushing'     => __( 'Flushing existing content...', 'aqualuxe' ),
				'success'      => __( 'Demo content imported successfully!', 'aqualuxe' ),
				'error'        => __( 'An error occurred during import.', 'aqualuxe' ),
				'confirmFlush' => __( 'Are you sure you want to delete all existing content? This cannot be undone.', 'aqualuxe' ),
			),
		) );
	}

	/**
	 * Admin page
	 */
	public function admin_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'AquaLuxe Demo Content Importer', 'aqualuxe' ); ?></h1>
			
			<div class="aqualuxe-demo-importer">
				<div class="demo-options">
					<h2><?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?></h2>
					<p><?php esc_html_e( 'This will import sample content including posts, pages, products, services, and media to help you get started with your AquaLuxe website.', 'aqualuxe' ); ?></p>
					
					<div class="import-options">
						<label>
							<input type="checkbox" name="import_content" value="1" checked>
							<?php esc_html_e( 'Import Posts & Pages', 'aqualuxe' ); ?>
						</label>
						<br>
						
						<label>
							<input type="checkbox" name="import_products" value="1" checked>
							<?php esc_html_e( 'Import WooCommerce Products', 'aqualuxe' ); ?>
						</label>
						<br>
						
						<label>
							<input type="checkbox" name="import_services" value="1" checked>
							<?php esc_html_e( 'Import Services', 'aqualuxe' ); ?>
						</label>
						<br>
						
						<label>
							<input type="checkbox" name="import_team" value="1" checked>
							<?php esc_html_e( 'Import Team Members', 'aqualuxe' ); ?>
						</label>
						<br>
						
						<label>
							<input type="checkbox" name="import_testimonials" value="1" checked>
							<?php esc_html_e( 'Import Testimonials', 'aqualuxe' ); ?>
						</label>
						<br>
						
						<label>
							<input type="checkbox" name="import_media" value="1" checked>
							<?php esc_html_e( 'Import Media Files', 'aqualuxe' ); ?>
						</label>
						<br>
						
						<label>
							<input type="checkbox" name="import_menus" value="1" checked>
							<?php esc_html_e( 'Setup Navigation Menus', 'aqualuxe' ); ?>
						</label>
						<br>
						
						<label>
							<input type="checkbox" name="import_customizer" value="1" checked>
							<?php esc_html_e( 'Import Theme Settings', 'aqualuxe' ); ?>
						</label>
					</div>
					
					<p class="submit">
						<button type="button" class="button button-primary" id="import-demo">
							<?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?>
						</button>
					</p>
				</div>

				<div class="flush-options">
					<h2><?php esc_html_e( 'Reset Content', 'aqualuxe' ); ?></h2>
					<p><?php esc_html_e( 'Use this to completely reset your site and remove all content. This action cannot be undone.', 'aqualuxe' ); ?></p>
					
					<p class="submit">
						<button type="button" class="button button-secondary" id="flush-content">
							<?php esc_html_e( 'Reset All Content', 'aqualuxe' ); ?>
						</button>
					</p>
				</div>

				<div class="import-progress" style="display: none;">
					<h3><?php esc_html_e( 'Import Progress', 'aqualuxe' ); ?></h3>
					<div class="progress-bar">
						<div class="progress-fill" style="width: 0%;"></div>
					</div>
					<div class="progress-text"></div>
					<div class="import-log"></div>
				</div>
			</div>
		</div>

		<script>
		jQuery(document).ready(function($) {
			$('#import-demo').on('click', function() {
				var button = $(this);
				var options = {};
				
				// Collect import options
				$('.import-options input[type="checkbox"]:checked').each(function() {
					options[$(this).attr('name')] = 1;
				});
				
				button.prop('disabled', true);
				$('.import-progress').show();
				$('.progress-text').text(aqualuxeDemoImporter.strings.importing);
				
				$.ajax({
					url: aqualuxeDemoImporter.ajaxUrl,
					type: 'POST',
					data: {
						action: 'aqualuxe_import_demo',
						nonce: aqualuxeDemoImporter.nonce,
						options: options
					},
					success: function(response) {
						if (response.success) {
							$('.progress-fill').css('width', '100%');
							$('.progress-text').text(aqualuxeDemoImporter.strings.success);
							$('.import-log').html('<p style="color: green;">' + response.data.message + '</p>');
						} else {
							$('.progress-text').text(aqualuxeDemoImporter.strings.error);
							$('.import-log').html('<p style="color: red;">' + response.data.message + '</p>');
						}
					},
					error: function() {
						$('.progress-text').text(aqualuxeDemoImporter.strings.error);
						$('.import-log').html('<p style="color: red;">AJAX request failed.</p>');
					},
					complete: function() {
						button.prop('disabled', false);
					}
				});
			});
			
			$('#flush-content').on('click', function() {
				if (!confirm(aqualuxeDemoImporter.strings.confirmFlush)) {
					return;
				}
				
				var button = $(this);
				button.prop('disabled', true);
				$('.import-progress').show();
				$('.progress-text').text(aqualuxeDemoImporter.strings.flushing);
				
				$.ajax({
					url: aqualuxeDemoImporter.ajaxUrl,
					type: 'POST',
					data: {
						action: 'aqualuxe_flush_demo',
						nonce: aqualuxeDemoImporter.nonce
					},
					success: function(response) {
						if (response.success) {
							$('.progress-fill').css('width', '100%');
							$('.progress-text').text('Content flushed successfully!');
							$('.import-log').html('<p style="color: green;">' + response.data.message + '</p>');
						} else {
							$('.progress-text').text('Error flushing content.');
							$('.import-log').html('<p style="color: red;">' + response.data.message + '</p>');
						}
					},
					complete: function() {
						button.prop('disabled', false);
					}
				});
			});
		});
		</script>
		<?php
	}

	/**
	 * Handle import AJAX request
	 */
	public function handle_import_ajax() {
		// Verify nonce
		if ( ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_demo_importer' ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'aqualuxe' ) ) );
		}

		// Check permissions
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'aqualuxe' ) ) );
		}

		$options = $_POST['options'];
		$imported = array();

		try {
			// Import content based on selected options
			if ( isset( $options['import_content'] ) ) {
				$this->import_sample_content();
				$imported[] = 'content';
			}

			if ( isset( $options['import_products'] ) && class_exists( 'WooCommerce' ) ) {
				$this->import_sample_products();
				$imported[] = 'products';
			}

			if ( isset( $options['import_services'] ) ) {
				$this->import_sample_services();
				$imported[] = 'services';
			}

			if ( isset( $options['import_team'] ) ) {
				$this->import_sample_team();
				$imported[] = 'team';
			}

			if ( isset( $options['import_testimonials'] ) ) {
				$this->import_sample_testimonials();
				$imported[] = 'testimonials';
			}

			if ( isset( $options['import_menus'] ) ) {
				$this->setup_menus();
				$imported[] = 'menus';
			}

			if ( isset( $options['import_customizer'] ) ) {
				$this->import_theme_settings();
				$imported[] = 'settings';
			}

			wp_send_json_success( array(
				'message' => sprintf( 
					__( 'Successfully imported: %s', 'aqualuxe' ), 
					implode( ', ', $imported ) 
				)
			) );

		} catch ( Exception $e ) {
			wp_send_json_error( array( 'message' => $e->getMessage() ) );
		}
	}

	/**
	 * Handle flush AJAX request
	 */
	public function handle_flush_ajax() {
		// Verify nonce
		if ( ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_demo_importer' ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'aqualuxe' ) ) );
		}

		// Check permissions
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'aqualuxe' ) ) );
		}

		try {
			$this->flush_all_content();
			wp_send_json_success( array( 'message' => __( 'All content has been flushed successfully.', 'aqualuxe' ) ) );
		} catch ( Exception $e ) {
			wp_send_json_error( array( 'message' => $e->getMessage() ) );
		}
	}

	/**
	 * Import sample content
	 */
	private function import_sample_content() {
		// Create sample pages
		$pages = array(
			array(
				'title' => 'Home',
				'content' => $this->get_home_page_content(),
				'template' => 'page-home.php'
			),
			array(
				'title' => 'About Us',
				'content' => $this->get_about_page_content(),
			),
			array(
				'title' => 'Services',
				'content' => $this->get_services_page_content(),
			),
			array(
				'title' => 'Contact',
				'content' => $this->get_contact_page_content(),
			),
		);

		foreach ( $pages as $page_data ) {
			$existing = get_page_by_title( $page_data['title'] );
			if ( ! $existing ) {
				$page_id = wp_insert_post( array(
					'post_title'   => $page_data['title'],
					'post_content' => $page_data['content'],
					'post_status'  => 'publish',
					'post_type'    => 'page',
				) );

				if ( isset( $page_data['template'] ) ) {
					update_post_meta( $page_id, '_wp_page_template', $page_data['template'] );
				}

				// Set front page
				if ( $page_data['title'] === 'Home' ) {
					update_option( 'show_on_front', 'page' );
					update_option( 'page_on_front', $page_id );
				}
			}
		}

		// Create sample posts
		$posts = array(
			array(
				'title' => 'Setting Up Your First Aquarium',
				'content' => $this->get_sample_post_content( 'aquarium-setup' ),
			),
			array(
				'title' => 'Best Fish for Beginners',
				'content' => $this->get_sample_post_content( 'beginner-fish' ),
			),
			array(
				'title' => 'Aquascaping Tips and Tricks',
				'content' => $this->get_sample_post_content( 'aquascaping' ),
			),
		);

		foreach ( $posts as $post_data ) {
			$existing = get_page_by_title( $post_data['title'], OBJECT, 'post' );
			if ( ! $existing ) {
				wp_insert_post( array(
					'post_title'   => $post_data['title'],
					'post_content' => $post_data['content'],
					'post_status'  => 'publish',
					'post_type'    => 'post',
					'post_category' => array( 1 ), // Uncategorized
				) );
			}
		}
	}

	/**
	 * Import sample products (WooCommerce)
	 */
	private function import_sample_products() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$products = array(
			array(
				'name' => 'Betta Fish - Premium Quality',
				'price' => '25.00',
				'description' => 'Beautiful Betta fish, perfect for small aquariums. Available in various colors.',
				'category' => 'Fish',
			),
			array(
				'name' => 'Aquatic Plant Bundle',
				'price' => '35.00',
				'description' => 'Collection of easy-care aquatic plants for natural aquarium setup.',
				'category' => 'Plants',
			),
			array(
				'name' => 'Premium Fish Food',
				'price' => '15.00',
				'description' => 'High-quality fish food suitable for all tropical fish species.',
				'category' => 'Supplies',
			),
		);

		foreach ( $products as $product_data ) {
			$existing = get_page_by_title( $product_data['name'], OBJECT, 'product' );
			if ( ! $existing ) {
				$product = new WC_Product_Simple();
				$product->set_name( $product_data['name'] );
				$product->set_regular_price( $product_data['price'] );
				$product->set_description( $product_data['description'] );
				$product->set_status( 'publish' );
				$product->save();
			}
		}
	}

	/**
	 * Import sample services
	 */
	private function import_sample_services() {
		$services = array(
			array(
				'title' => 'Aquarium Design & Installation',
				'content' => 'Professional aquarium design and installation services for homes and businesses.',
			),
			array(
				'title' => 'Maintenance Services',
				'content' => 'Regular aquarium maintenance to keep your aquatic environment healthy.',
			),
			array(
				'title' => 'Fish Health Consultation',
				'content' => 'Expert consultation on fish health and aquarium water quality.',
			),
		);

		foreach ( $services as $service_data ) {
			$existing = get_page_by_title( $service_data['title'], OBJECT, 'aqualuxe_service' );
			if ( ! $existing ) {
				wp_insert_post( array(
					'post_title'   => $service_data['title'],
					'post_content' => $service_data['content'],
					'post_status'  => 'publish',
					'post_type'    => 'aqualuxe_service',
				) );
			}
		}
	}

	/**
	 * Import sample team members
	 */
	private function import_sample_team() {
		$team_members = array(
			array(
				'title' => 'John Smith',
				'content' => 'Founder and Lead Aquarist with over 15 years of experience in marine biology.',
			),
			array(
				'title' => 'Sarah Johnson',
				'content' => 'Aquascaping Specialist and Designer, creating beautiful underwater landscapes.',
			),
		);

		foreach ( $team_members as $member_data ) {
			$existing = get_page_by_title( $member_data['title'], OBJECT, 'aqualuxe_team' );
			if ( ! $existing ) {
				wp_insert_post( array(
					'post_title'   => $member_data['title'],
					'post_content' => $member_data['content'],
					'post_status'  => 'publish',
					'post_type'    => 'aqualuxe_team',
				) );
			}
		}
	}

	/**
	 * Import sample testimonials
	 */
	private function import_sample_testimonials() {
		$testimonials = array(
			array(
				'title' => 'Amazing Service',
				'content' => '"AquaLuxe transformed my office with a beautiful aquarium. The fish are healthy and the design is stunning!" - Mike Davis',
			),
			array(
				'title' => 'Professional Team',
				'content' => '"The team at AquaLuxe knows their stuff. Great advice and excellent customer service." - Lisa Chen',
			),
		);

		foreach ( $testimonials as $testimonial_data ) {
			$existing = get_page_by_title( $testimonial_data['title'], OBJECT, 'aqualuxe_testimonial' );
			if ( ! $existing ) {
				wp_insert_post( array(
					'post_title'   => $testimonial_data['title'],
					'post_content' => $testimonial_data['content'],
					'post_status'  => 'publish',
					'post_type'    => 'aqualuxe_testimonial',
				) );
			}
		}
	}

	/**
	 * Setup navigation menus
	 */
	private function setup_menus() {
		// Create primary menu
		$menu_name = 'Primary Navigation';
		$menu_exists = wp_get_nav_menu_object( $menu_name );

		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu( $menu_name );

			// Add menu items
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'   => 'Home',
				'menu-item-classes' => 'home',
				'menu-item-url'     => home_url( '/' ),
				'menu-item-status'  => 'publish'
			) );

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'   => 'About',
				'menu-item-object-id' => get_page_by_title( 'About Us' )->ID ?? 0,
				'menu-item-object'  => 'page',
				'menu-item-type'    => 'post_type',
				'menu-item-status'  => 'publish'
			) );

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'   => 'Services',
				'menu-item-object-id' => get_page_by_title( 'Services' )->ID ?? 0,
				'menu-item-object'  => 'page',
				'menu-item-type'    => 'post_type',
				'menu-item-status'  => 'publish'
			) );

			if ( class_exists( 'WooCommerce' ) ) {
				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title'   => 'Shop',
					'menu-item-url'     => get_permalink( wc_get_page_id( 'shop' ) ),
					'menu-item-status'  => 'publish'
				) );
			}

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'   => 'Contact',
				'menu-item-object-id' => get_page_by_title( 'Contact' )->ID ?? 0,
				'menu-item-object'  => 'page',
				'menu-item-type'    => 'post_type',
				'menu-item-status'  => 'publish'
			) );

			// Set menu to primary location
			set_theme_mod( 'nav_menu_locations', array( 'primary' => $menu_id ) );
		}
	}

	/**
	 * Import theme settings
	 */
	private function import_theme_settings() {
		// Set default theme modifications
		$theme_mods = array(
			'aqualuxe_primary_color' => '#3b82f6',
			'aqualuxe_secondary_color' => '#14b8a6',
			'aqualuxe_accent_color' => '#f59e0b',
			'aqualuxe_dark_mode' => 'auto',
			'aqualuxe_show_search' => true,
			'aqualuxe_show_cart' => true,
			'aqualuxe_show_account' => true,
			'aqualuxe_show_dark_mode_toggle' => true,
			'aqualuxe_footer_text' => '© ' . date( 'Y' ) . ' AquaLuxe. Bringing elegance to aquatic life – globally.',
		);

		foreach ( $theme_mods as $key => $value ) {
			set_theme_mod( $key, $value );
		}
	}

	/**
	 * Flush all content
	 */
	private function flush_all_content() {
		global $wpdb;

		// Delete all posts (including custom post types)
		$posts = get_posts( array(
			'post_type' => 'any',
			'numberposts' => -1,
			'post_status' => 'any',
		) );

		foreach ( $posts as $post ) {
			wp_delete_post( $post->ID, true );
		}

		// Delete all terms
		$taxonomies = get_taxonomies();
		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) );
			foreach ( $terms as $term ) {
				wp_delete_term( $term->term_id, $taxonomy );
			}
		}

		// Delete all comments
		$comments = get_comments( array( 'number' => 0 ) );
		foreach ( $comments as $comment ) {
			wp_delete_comment( $comment->comment_ID, true );
		}

		// Delete all menus
		$menus = wp_get_nav_menus();
		foreach ( $menus as $menu ) {
			wp_delete_nav_menu( $menu->term_id );
		}

		// Reset theme modifications
		remove_theme_mods();

		// Reset WordPress options
		update_option( 'show_on_front', 'posts' );
		delete_option( 'page_on_front' );
		delete_option( 'page_for_posts' );
	}

	/**
	 * Get sample page content
	 */
	private function get_home_page_content() {
		return '<!-- wp:heading {"level":1} -->
<h1>Welcome to AquaLuxe</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Bringing elegance to aquatic life – globally. Discover our premium fish, aquatic plants, and professional aquarium services.</p>
<!-- /wp:paragraph -->';
	}

	private function get_about_page_content() {
		return '<!-- wp:heading {"level":1} -->
<h1>About AquaLuxe</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>AquaLuxe is your premier destination for luxury aquatic solutions. We specialize in creating elegant underwater environments that bring the beauty of aquatic life into your home or office.</p>
<!-- /wp:paragraph -->';
	}

	private function get_services_page_content() {
		return '<!-- wp:heading {"level":1} -->
<h1>Our Services</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We offer comprehensive aquatic services from design to maintenance, ensuring your aquatic environment thrives.</p>
<!-- /wp:paragraph -->';
	}

	private function get_contact_page_content() {
		return '<!-- wp:heading {"level":1} -->
<h1>Contact Us</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Get in touch with our team of aquatic experts. We\'re here to help you create the perfect aquatic environment.</p>
<!-- /wp:paragraph -->';
	}

	private function get_sample_post_content( $type ) {
		$content_map = array(
			'aquarium-setup' => 'Setting up your first aquarium can be exciting but overwhelming. Here are the essential steps to get you started with a healthy aquatic environment.',
			'beginner-fish' => 'Choosing the right fish for beginners is crucial for a successful aquarium experience. Here are our top recommendations for hardy, beautiful fish.',
			'aquascaping' => 'Aquascaping is the art of arranging aquatic plants, rocks, and driftwood in an aesthetically pleasing manner. Learn the fundamentals of creating stunning underwater landscapes.',
		);

		return $content_map[ $type ] ?? 'Sample content for AquaLuxe blog post.';
	}
}

// Initialize the module
new AquaLuxe_Demo_Importer_Module();