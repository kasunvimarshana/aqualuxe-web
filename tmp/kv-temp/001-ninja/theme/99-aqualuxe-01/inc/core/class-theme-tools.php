<?php
/**
 * Theme Tools.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles theme tools and utilities.
 */
class AquaLuxe_Theme_Tools {

	/**
	 * Initialize theme tools.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_admin_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'handle_page_creation' ) );
	}

	/**
	 * Add admin menu page for theme tools.
	 */
	public static function add_admin_menu() {
		add_theme_page(
			__( 'AquaLuxe Setup', 'aqualuxe' ),
			__( 'AquaLuxe Setup', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-setup',
			array( __CLASS__, 'render_setup_page' )
		);
	}

	/**
	 * Render the setup page.
	 */
	public static function render_setup_page() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'AquaLuxe Theme Setup', 'aqualuxe' ); ?></h1>
			<?php
            if ( isset( $_GET['pages_created'] ) && $_GET['pages_created'] ) {
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'WooCommerce pages have been created successfully.', 'aqualuxe' ) . '</p></div>';
            }
            ?>
			<h2><?php echo esc_html__( 'Create WooCommerce Pages', 'aqualuxe' ); ?></h2>
			<p><?php echo esc_html__( 'If your WooCommerce pages (Shop, Cart, Checkout, My Account) are missing, you can create them by clicking the button below.', 'aqualuxe' ); ?></p>
			<form method="post">
				<?php wp_nonce_field( 'aqualuxe_create_pages_nonce', 'aqualuxe_create_pages_nonce' ); ?>
				<p class="submit">
					<input type="submit" name="aqualuxe_create_pages" class="button button-primary" value="<?php echo esc_attr__( 'Create Pages', 'aqualuxe' ); ?>" />
				</p>
			</form>
		</div>
		<?php
	}

	/**
	 * Handle the WooCommerce page creation process.
	 */
	public static function handle_page_creation() {
		if ( ! isset( $_POST['aqualuxe_create_pages'] ) || ! isset( $_POST['aqualuxe_create_pages_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['aqualuxe_create_pages_nonce'], 'aqualuxe_create_pages_nonce' ) ) {
			wp_die( 'Nonce verification failed!' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'You do not have permission to do this.' );
		}

		self::create_woocommerce_pages();

		// Redirect after creation.
		wp_redirect( admin_url( 'themes.php?page=aqualuxe-setup&pages_created=true' ) );
		exit;
	}

    /**
     * Create WooCommerce pages.
     */
    private static function create_woocommerce_pages() {
        $pages = array(
            'shop' => array(
                'name'    => _x( 'shop', 'Page slug', 'aqualuxe' ),
                'title'   => _x( 'Shop', 'Page title', 'aqualuxe' ),
                'content' => '',
            ),
            'cart' => array(
                'name'    => _x( 'cart', 'Page slug', 'aqualuxe' ),
                'title'   => _x( 'Cart', 'Page title', 'aqualuxe' ),
                'content' => '[woocommerce_cart]',
            ),
            'checkout' => array(
                'name'    => _x( 'checkout', 'Page slug', 'aqualuxe' ),
                'title'   => _x( 'Checkout', 'Page title', 'aqualuxe' ),
                'content' => '[woocommerce_checkout]',
            ),
            'myaccount' => array(
                'name'    => _x( 'my-account', 'Page slug', 'aqualuxe' ),
                'title'   => _x( 'My Account', 'Page title', 'aqualuxe' ),
                'content' => '[woocommerce_my_account]',
            ),
        );

        foreach ( $pages as $key => $page ) {
            $option_name = "woocommerce_{$key}_page_id";
            $page_id = get_option( $option_name );

            if ( ! empty( $page_id ) && get_post( $page_id ) ) {
                continue; // Page already exists.
            }

            $page_data = array(
                'post_status'    => 'publish',
                'post_type'      => 'page',
                'post_author'    => 1,
                'post_name'      => $page['name'],
                'post_title'     => $page['title'],
                'post_content'   => $page['content'],
                'comment_status' => 'closed',
            );

            $new_page_id = wp_insert_post( $page_data, false );

            if ( $new_page_id ) {
                update_option( $option_name, $new_page_id );
            }
        }
    }
}
