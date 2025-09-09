<?php
/**
 * AquaLuxe Demo Importer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AquaLuxe_Demo_Importer class.
 */
class AquaLuxe_Demo_Importer {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'handle_import' ) );
	}

	/**
	 * Add admin menu.
	 */
	public function add_admin_menu() {
		add_theme_page(
			esc_html__( 'Demo Importer', 'aqualuxe' ),
			esc_html__( 'Demo Importer', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-demo-importer',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Render page.
	 */
	public function render_page() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<p><?php esc_html_e( 'Import the demo content to get started with the theme.', 'aqualuxe' ); ?></p>
			<form method="post">
				<?php wp_nonce_field( 'aqualuxe_import_demo_nonce', 'aqualuxe_demo_importer_nonce' ); ?>
				<p>
					<button type="submit" name="aqualuxe_import_demo" class="button button-primary"><?php esc_html_e( 'Import Demo Data', 'aqualuxe' ); ?></button>
				</p>
				<p>
					<label>
						<input type="checkbox" name="aqualuxe_flush_data" value="1">
						<?php esc_html_e( 'Flush existing data before importing. This will delete all posts, pages, products, etc.', 'aqualuxe' ); ?>
					</label>
				</p>
			</form>
		</div>
		<?php
	}

	/**
	 * Handle import.
	 */
	public function handle_import() {
		if ( ! isset( $_POST['aqualuxe_import_demo'] ) || ! isset( $_POST['aqualuxe_demo_importer_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_key( $_POST['aqualuxe_demo_importer_nonce'] ), 'aqualuxe_import_demo_nonce' ) ) {
			wp_die( 'Nonce verification failed.' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'You do not have permission to import demo data.' );
		}

		if ( isset( $_POST['aqualuxe_flush_data'] ) && '1' === $_POST['aqualuxe_flush_data'] ) {
			$this->flush_data();
		}

		$this->import_content();

		add_action( 'admin_notices', array( $this, 'import_success_notice' ) );
	}

	/**
	 * Flush data.
	 */
	private function flush_data() {
		// Delete all posts, pages, products, etc.
		$post_types = get_post_types( array( 'public' => true ), 'names' );
		unset( $post_types['attachment'] );

		foreach ( $post_types as $post_type ) {
			$posts = get_posts(
				array(
					'post_type'      => $post_type,
					'posts_per_page' => -1,
					'fields'         => 'ids',
				)
			);

			foreach ( $posts as $post_id ) {
				wp_delete_post( $post_id, true );
			}
		}
	}

	/**
	 * Import content.
	 */
	private function import_content() {
		$file_path = get_template_directory() . '/modules/demo-importer/data/demo-content.json';
		if ( ! file_exists( $file_path ) ) {
			return;
		}

		$data = json_decode( file_get_contents( $file_path ), true );

		$this->import_items( $data, 'posts', 'post' );
		$this->import_items( $data, 'pages', 'page' );
		if ( class_exists( 'WooCommerce' ) ) {
			$this->import_items( $data, 'products', 'product' );
		}
		$this->setup_menus( $data );
		$this->setup_reading_options( $data );
	}

	/**
	 * Import items.
	 *
	 * @param array  $data The demo data.
	 * @param string $key The key for the items to import (e.g., 'posts', 'pages').
	 * @param string $post_type The post type.
	 */
	private function import_items( $data, $key, $post_type ) {
		if ( empty( $data[ $key ] ) ) {
			return;
		}

		foreach ( $data[ $key ] as $item ) {
			$post_data = array(
				'post_title'   => $item['title'],
				'post_content' => $item['content'],
				'post_status'  => 'publish',
				'post_author'  => 1,
				'post_type'    => $post_type,
			);

			$post_id = wp_insert_post( $post_data );

			if ( 'product' === $post_type && isset( $item['price'] ) ) {
				update_post_meta( $post_id, '_price', $item['price'] );
				update_post_meta( $post_id, '_regular_price', $item['price'] );
			}
		}
	}

	/**
	 * Setup menus.
	 *
	 * @param array $data The demo data.
	 */
	private function setup_menus( $data ) {
		if ( empty( $data['menu'] ) ) {
			return;
		}

		$menu_name     = $data['menu']['name'];
		$menu_location = $data['menu']['location'];
		$menu_exists   = wp_get_nav_menu_object( $menu_name );

		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu( $menu_name );

			foreach ( $data['menu']['items'] as $item ) {
				$page = get_page_by_title( $item['title'] );
				if ( $page ) {
					wp_update_nav_menu_item(
						$menu_id,
						0,
						array(
							'menu-item-title'     => $item['title'],
							'menu-item-object'    => 'page',
							'menu-item-object-id' => $page->ID,
							'menu-item-type'      => 'post_type',
							'menu-item-status'    => 'publish',
						)
					);
				}
			}

			$locations = get_theme_mod( 'nav_menu_locations' );
			$locations[ $menu_location ] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}

	/**
	 * Setup reading options.
	 *
	 * @param array $data The demo data.
	 */
	private function setup_reading_options( $data ) {
		if ( empty( $data['reading_options'] ) ) {
			return;
		}

		$options = $data['reading_options'];

		if ( 'page' === $options['show_on_front'] ) {
			$front_page = get_page_by_title( $options['page_on_front'] );
			$posts_page = get_page_by_title( $options['page_for_posts'] );

			if ( $front_page && $posts_page ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $front_page->ID );
				update_option( 'page_for_posts', $posts_page->ID );
			}
		}
	}

	/**
	 * Import success notice.
	 */
	public function import_success_notice() {
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php esc_html_e( 'Demo data imported successfully.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}
}
