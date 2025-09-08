<?php
/**
 * Module: Mega Menu
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AquaLuxe_Mega_Menu class.
 */
class AquaLuxe_Mega_Menu {

	/**
	 * Instance of this class.
	 * @var object
	 */
	private static $instance;

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
		require_once AQUALUXE_THEME_DIR . '/modules/mega-menu/class-aqualuxe-mega-menu-walker.php';
		add_filter( 'wp_nav_menu_args', array( $this, 'nav_menu_args' ) );
		add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'add_custom_fields' ), 10, 2 );
		add_action( 'wp_update_nav_menu_item', array( $this, 'update_custom_fields' ), 10, 2 );
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'edit_nav_menu_walker' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Use our custom walker for the primary menu.
	 *
	 * @param array $args The nav menu arguments.
	 * @return array
	 */
	public function nav_menu_args( $args ) {
		if ( 'menu-1' === $args['theme_location'] ) {
			$args['walker'] = new AquaLuxe_Mega_Menu_Walker();
		}
		return $args;
	}

	/**
	 * Use a custom walker in the admin menu editor to show field values.
	 *
	 * @param string $walker The walker class name.
	 * @param int    $menu_id The menu ID.
	 * @return string
	 */
	public function edit_nav_menu_walker( $walker, $menu_id ) {
		require_once AQUALUXE_THEME_DIR . '/modules/mega-menu/class-aqualuxe-mega-menu-edit-walker.php';
		return 'AquaLuxe_Mega_Menu_Edit_Walker';
	}

	/**
	 * Add custom fields to the menu item editor.
	 *
	 * @param int    $item_id The menu item ID.
	 * @param object $item The menu item object.
	 */
	public function add_custom_fields( $item_id, $item ) {
		wp_nonce_field( 'aqualuxe_mega_menu_nonce', '_aqualuxe_mega_menu_nonce_name' );
		$mega_menu_enabled = get_post_meta( $item_id, '_menu_item_aqualuxe_mega_menu', true );
		?>
		<p class="field-mega-menu description description-wide">
			<label for="edit-menu-item-aqualuxe-mega-menu-<?php echo esc_attr($item_id); ?>">
				<input type="checkbox" id="edit-menu-item-aqualuxe-mega-menu-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-aqualuxe-mega-menu" name="menu-item-aqualuxe-mega-menu[<?php echo esc_attr($item_id); ?>]" value="1" <?php checked( $mega_menu_enabled, '1' ); ?> />
				<?php esc_html_e( 'Enable Mega Menu for this item', 'aqualuxe' ); ?>
			</label>
		</p>
		<?php
	}

	/**
	 * Save the custom fields.
	 *
	 * @param int   $menu_id         The menu ID.
	 * @param int   $menu_item_db_id The menu item ID.
	 */
	public function update_custom_fields( $menu_id, $menu_item_db_id ) {
		// Verify nonce.
		if ( ! isset( $_POST['_aqualuxe_mega_menu_nonce_name'] ) || ! wp_verify_nonce( $_POST['_aqualuxe_mega_menu_nonce_name'], 'aqualuxe_mega_menu_nonce' ) ) {
			return;
		}

		if ( isset( $_POST['menu-item-aqualuxe-mega-menu'][ $menu_item_db_id ] ) ) {
			update_post_meta( $menu_item_db_id, '_menu_item_aqualuxe_mega_menu', '1' );
		} else {
			delete_post_meta( $menu_item_db_id, '_menu_item_aqualuxe_mega_menu' );
		}
	}

	/**
	 * Enqueue assets for the mega menu.
	 */
	public function enqueue_assets() {
		$manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
		if ( file_exists( $manifest_path ) ) {
			$manifest = json_decode( file_get_contents( $manifest_path ), true );
			if ( isset( $manifest['/css/mega-menu.css'] ) ) {
				wp_enqueue_style( 'aqualuxe-mega-menu', AQUALUXE_THEME_URI . '/assets/dist' . $manifest['/css/mega-menu.css'], array(), null );
			}
			if ( isset( $manifest['/js/mega-menu.js'] ) ) {
				wp_enqueue_script( 'aqualuxe-mega-menu', AQUALUXE_THEME_URI . '/assets/dist' . $manifest['/js/mega-menu.js'], array('jquery'), null, true );
			}
		}
	}
}

AquaLuxe_Mega_Menu::get_instance();
