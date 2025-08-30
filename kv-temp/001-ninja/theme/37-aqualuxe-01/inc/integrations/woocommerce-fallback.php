<?php
/**
 * WooCommerce fallback functionality for AquaLuxe theme
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AquaLuxe WooCommerce Fallback Class
 */
class AquaLuxe_WooCommerce_Fallback {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Handle WooCommerce pages when WooCommerce is not active.
		add_action( 'template_redirect', array( $this, 'handle_woocommerce_pages' ) );
		
		// Add fallback content for shop pages.
		add_filter( 'the_content', array( $this, 'fallback_content' ) );
		
		// Register shop menu item filter.
		add_filter( 'wp_nav_menu_objects', array( $this, 'filter_shop_menu_item' ), 10, 2 );
		
		// Add body class.
		add_filter( 'body_class', array( $this, 'add_body_class' ) );
	}

	/**
	 * Handle WooCommerce pages when WooCommerce is not active
	 */
	public function handle_woocommerce_pages() {
		// Check if we're on a WooCommerce page.
		if ( is_page( 'shop' ) || is_page( 'cart' ) || is_page( 'checkout' ) || is_page( 'my-account' ) ) {
			// Do nothing, let the fallback content filter handle it.
		}
	}

	/**
	 * Add fallback content for WooCommerce pages
	 *
	 * @param string $content The content.
	 * @return string
	 */
	public function fallback_content( $content ) {
		// Only modify content on WooCommerce pages.
		if ( ! is_page( array( 'shop', 'cart', 'checkout', 'my-account' ) ) ) {
			return $content;
		}

		// Get the page slug.
		$slug = get_post_field( 'post_name', get_the_ID() );

		// Generate fallback content based on the page.
		$fallback_content = $this->get_fallback_content( $slug );

		// Append fallback content to the existing content.
		return $content . $fallback_content;
	}

	/**
	 * Get fallback content for WooCommerce pages
	 *
	 * @param string $page_slug The page slug.
	 * @return string
	 */
	public function get_fallback_content( $page_slug ) {
		ob_start();

		echo '<div class="woocommerce-fallback">';

		switch ( $page_slug ) {
			case 'shop':
				$this->get_shop_fallback();
				break;
			case 'cart':
				$this->get_cart_fallback();
				break;
			case 'checkout':
				$this->get_checkout_fallback();
				break;
			case 'my-account':
				$this->get_account_fallback();
				break;
			default:
				$this->get_generic_fallback();
				break;
		}

		echo '</div>';

		return ob_get_clean();
	}

	/**
	 * Get shop fallback content
	 */
	public function get_shop_fallback() {
		?>
		<div class="woocommerce-fallback-message">
			<h2><?php esc_html_e( 'Shop Coming Soon', 'aqualuxe' ); ?></h2>
			<p><?php esc_html_e( 'Our online shop is currently being set up. Please check back soon to browse our premium aquatic products.', 'aqualuxe' ); ?></p>
			<p><?php esc_html_e( 'In the meantime, feel free to contact us directly for product inquiries.', 'aqualuxe' ); ?></p>
		</div>

		<div class="woocommerce-fallback-categories">
			<h3><?php esc_html_e( 'Product Categories', 'aqualuxe' ); ?></h3>
			<div class="woocommerce-fallback-category-grid">
				<div class="woocommerce-fallback-category">
					<div class="woocommerce-fallback-category-image">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/placeholder-fish.jpg' ); ?>" alt="<?php esc_attr_e( 'Rare Fish', 'aqualuxe' ); ?>">
					</div>
					<h4 class="woocommerce-fallback-category-title"><?php esc_html_e( 'Rare Fish', 'aqualuxe' ); ?></h4>
					<p class="woocommerce-fallback-category-description"><?php esc_html_e( 'Exclusive and exotic fish species from around the world.', 'aqualuxe' ); ?></p>
				</div>
				
				<div class="woocommerce-fallback-category">
					<div class="woocommerce-fallback-category-image">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/placeholder-plants.jpg' ); ?>" alt="<?php esc_attr_e( 'Aquatic Plants', 'aqualuxe' ); ?>">
					</div>
					<h4 class="woocommerce-fallback-category-title"><?php esc_html_e( 'Aquatic Plants', 'aqualuxe' ); ?></h4>
					<p class="woocommerce-fallback-category-description"><?php esc_html_e( 'Beautiful and healthy plants for your aquarium.', 'aqualuxe' ); ?></p>
				</div>
				
				<div class="woocommerce-fallback-category">
					<div class="woocommerce-fallback-category-image">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/placeholder-equipment.jpg' ); ?>" alt="<?php esc_attr_e( 'Premium Equipment', 'aqualuxe' ); ?>">
					</div>
					<h4 class="woocommerce-fallback-category-title"><?php esc_html_e( 'Premium Equipment', 'aqualuxe' ); ?></h4>
					<p class="woocommerce-fallback-category-description"><?php esc_html_e( 'High-quality equipment for optimal aquarium maintenance.', 'aqualuxe' ); ?></p>
				</div>
				
				<div class="woocommerce-fallback-category">
					<div class="woocommerce-fallback-category-image">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/placeholder-supplies.jpg' ); ?>" alt="<?php esc_attr_e( 'Care Supplies', 'aqualuxe' ); ?>">
					</div>
					<h4 class="woocommerce-fallback-category-title"><?php esc_html_e( 'Care Supplies', 'aqualuxe' ); ?></h4>
					<p class="woocommerce-fallback-category-description"><?php esc_html_e( 'Essential supplies for the health and wellbeing of your aquatic life.', 'aqualuxe' ); ?></p>
				</div>
			</div>
		</div>

		<div class="woocommerce-fallback-cta">
			<h3><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></h3>
			<p><?php esc_html_e( 'Interested in our products? Get in touch with us for more information.', 'aqualuxe' ); ?></p>
			<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="button button-primary"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></a>
		</div>

		<?php if ( current_user_can( 'activate_plugins' ) ) : ?>
			<div class="woocommerce-fallback-admin-notice">
				<p><?php esc_html_e( 'Admin Notice: WooCommerce is not currently active. Please install and activate WooCommerce to enable the shop functionality.', 'aqualuxe' ); ?></p>
				<a href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Go to Plugins', 'aqualuxe' ); ?></a>
			</div>
		<?php endif; ?>
		<?php
	}

	/**
	 * Get cart fallback content
	 */
	public function get_cart_fallback() {
		?>
		<div class="woocommerce-fallback-message">
			<h2><?php esc_html_e( 'Shopping Cart', 'aqualuxe' ); ?></h2>
			<p><?php esc_html_e( 'Our online shopping cart is currently being set up. Please check back soon to purchase our premium aquatic products.', 'aqualuxe' ); ?></p>
			<p><?php esc_html_e( 'In the meantime, feel free to contact us directly for product inquiries.', 'aqualuxe' ); ?></p>
			<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="button button-primary"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></a>
			<a href="<?php echo esc_url( home_url() ); ?>" class="button button-secondary"><?php esc_html_e( 'Return to Home', 'aqualuxe' ); ?></a>
		</div>

		<?php if ( current_user_can( 'activate_plugins' ) ) : ?>
			<div class="woocommerce-fallback-admin-notice">
				<p><?php esc_html_e( 'Admin Notice: WooCommerce is not currently active. Please install and activate WooCommerce to enable the cart functionality.', 'aqualuxe' ); ?></p>
				<a href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Go to Plugins', 'aqualuxe' ); ?></a>
			</div>
		<?php endif; ?>
		<?php
	}

	/**
	 * Get checkout fallback content
	 */
	public function get_checkout_fallback() {
		?>
		<div class="woocommerce-fallback-message">
			<h2><?php esc_html_e( 'Checkout', 'aqualuxe' ); ?></h2>
			<p><?php esc_html_e( 'Our online checkout is currently being set up. Please check back soon to purchase our premium aquatic products.', 'aqualuxe' ); ?></p>
			<p><?php esc_html_e( 'In the meantime, feel free to contact us directly for product inquiries.', 'aqualuxe' ); ?></p>
			<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="button button-primary"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></a>
			<a href="<?php echo esc_url( home_url() ); ?>" class="button button-secondary"><?php esc_html_e( 'Return to Home', 'aqualuxe' ); ?></a>
		</div>

		<?php if ( current_user_can( 'activate_plugins' ) ) : ?>
			<div class="woocommerce-fallback-admin-notice">
				<p><?php esc_html_e( 'Admin Notice: WooCommerce is not currently active. Please install and activate WooCommerce to enable the checkout functionality.', 'aqualuxe' ); ?></p>
				<a href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Go to Plugins', 'aqualuxe' ); ?></a>
			</div>
		<?php endif; ?>
		<?php
	}

	/**
	 * Get account fallback content
	 */
	public function get_account_fallback() {
		?>
		<div class="woocommerce-fallback-message">
			<h2><?php esc_html_e( 'My Account', 'aqualuxe' ); ?></h2>
			<p><?php esc_html_e( 'Our customer account system is currently being set up. Please check back soon to access your account.', 'aqualuxe' ); ?></p>
			<p><?php esc_html_e( 'In the meantime, feel free to contact us directly for any inquiries.', 'aqualuxe' ); ?></p>
			<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="button button-primary"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></a>
			<a href="<?php echo esc_url( home_url() ); ?>" class="button button-secondary"><?php esc_html_e( 'Return to Home', 'aqualuxe' ); ?></a>
		</div>

		<?php if ( current_user_can( 'activate_plugins' ) ) : ?>
			<div class="woocommerce-fallback-admin-notice">
				<p><?php esc_html_e( 'Admin Notice: WooCommerce is not currently active. Please install and activate WooCommerce to enable the account functionality.', 'aqualuxe' ); ?></p>
				<a href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Go to Plugins', 'aqualuxe' ); ?></a>
			</div>
		<?php endif; ?>
		<?php
	}

	/**
	 * Get generic fallback content
	 */
	public function get_generic_fallback() {
		?>
		<div class="woocommerce-fallback-message">
			<h2><?php esc_html_e( 'Shop Coming Soon', 'aqualuxe' ); ?></h2>
			<p><?php esc_html_e( 'Our online shop is currently being set up. Please check back soon to browse our premium aquatic products.', 'aqualuxe' ); ?></p>
			<p><?php esc_html_e( 'In the meantime, feel free to contact us directly for product inquiries.', 'aqualuxe' ); ?></p>
			<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="button button-primary"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></a>
			<a href="<?php echo esc_url( home_url() ); ?>" class="button button-secondary"><?php esc_html_e( 'Return to Home', 'aqualuxe' ); ?></a>
		</div>

		<?php if ( current_user_can( 'activate_plugins' ) ) : ?>
			<div class="woocommerce-fallback-admin-notice">
				<p><?php esc_html_e( 'Admin Notice: WooCommerce is not currently active. Please install and activate WooCommerce to enable the shop functionality.', 'aqualuxe' ); ?></p>
				<a href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Go to Plugins', 'aqualuxe' ); ?></a>
			</div>
		<?php endif; ?>
		<?php
	}

	/**
	 * Filter shop menu item
	 *
	 * @param array    $menu_items Menu items.
	 * @param stdClass $args Menu arguments.
	 * @return array
	 */
	public function filter_shop_menu_item( $menu_items, $args ) {
		foreach ( $menu_items as $key => $menu_item ) {
			// Check if this is a WooCommerce endpoint.
			if ( strpos( $menu_item->url, 'cart' ) !== false || 
				 strpos( $menu_item->url, 'checkout' ) !== false || 
				 strpos( $menu_item->url, 'my-account' ) !== false || 
				 strpos( $menu_item->url, 'shop' ) !== false ) {
				
				// Add a CSS class to indicate this is a WooCommerce link.
				$menu_item->classes[] = 'woocommerce-link';
				$menu_item->classes[] = 'woocommerce-inactive';
			}
		}

		return $menu_items;
	}

	/**
	 * Add body class
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	public function add_body_class( $classes ) {
		$classes[] = 'woocommerce-inactive';
		return $classes;
	}

	/**
	 * Check if a page is a WooCommerce page
	 *
	 * @return bool
	 */
	public static function is_woocommerce_page() {
		return is_page( array( 'shop', 'cart', 'checkout', 'my-account' ) );
	}

	/**
	 * Get shop URL
	 *
	 * @return string
	 */
	public static function get_shop_url() {
		$shop_page = get_page_by_path( 'shop' );
		
		if ( $shop_page ) {
			return get_permalink( $shop_page->ID );
		}
		
		return home_url();
	}

	/**
	 * Get cart URL
	 *
	 * @return string
	 */
	public static function get_cart_url() {
		$cart_page = get_page_by_path( 'cart' );
		
		if ( $cart_page ) {
			return get_permalink( $cart_page->ID );
		}
		
		return home_url();
	}

	/**
	 * Get checkout URL
	 *
	 * @return string
	 */
	public static function get_checkout_url() {
		$checkout_page = get_page_by_path( 'checkout' );
		
		if ( $checkout_page ) {
			return get_permalink( $checkout_page->ID );
		}
		
		return home_url();
	}

	/**
	 * Get account URL
	 *
	 * @return string
	 */
	public static function get_account_url() {
		$account_page = get_page_by_path( 'my-account' );
		
		if ( $account_page ) {
			return get_permalink( $account_page->ID );
		}
		
		return home_url();
	}
}

// Initialize WooCommerce fallback.
new AquaLuxe_WooCommerce_Fallback();