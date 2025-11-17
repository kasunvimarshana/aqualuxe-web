<?php
/**
 * Main Theme Class
 *
 * @package AquaLuxe
 * @subpackage Core
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Theme Class
 *
 * This class is responsible for initializing the theme.
 */
class Theme {

	/**
	 * Service container
	 *
	 * @var array
	 */
	private $services = array();

	/**
	 * Initialize the theme
	 *
	 * @return void
	 */
	public function initialize() {
		$this->load_dependencies();
		$this->register_services();
		$this->initialize_services();
	}

	/**
	 * Load dependencies
	 *
	 * @return void
	 */
	private function load_dependencies() {
		// Load core classes.
		require_once AQUALUXE_INC_DIR . 'core/class-service.php';
		require_once AQUALUXE_INC_DIR . 'core/class-assets.php';
		require_once AQUALUXE_INC_DIR . 'core/class-template.php';
		require_once AQUALUXE_INC_DIR . 'core/class-accessibility.php';
		require_once AQUALUXE_INC_DIR . 'core/class-dark-mode.php';
		
		// Load customizer classes.
		require_once AQUALUXE_INC_DIR . 'customizer/class-customizer.php';
		
		// Load WooCommerce classes if WooCommerce is active.
		if ( class_exists( 'WooCommerce' ) ) {
			require_once AQUALUXE_INC_DIR . 'woocommerce/class-woocommerce.php';
			require_once AQUALUXE_INC_DIR . 'woocommerce/class-product.php';
			require_once AQUALUXE_INC_DIR . 'woocommerce/class-cart.php';
			require_once AQUALUXE_INC_DIR . 'woocommerce/class-checkout.php';
			require_once AQUALUXE_INC_DIR . 'woocommerce/class-account.php';
			require_once AQUALUXE_INC_DIR . 'woocommerce/class-wishlist.php';
			require_once AQUALUXE_INC_DIR . 'woocommerce/class-quick-view.php';
		}
	}

	/**
	 * Register services
	 *
	 * @return void
	 */
	private function register_services() {
		// Register core services.
		$this->services['assets'] = new Assets();
		$this->services['template'] = new Template();
		$this->services['accessibility'] = new Accessibility();
		$this->services['dark_mode'] = new Dark_Mode();
		
		// Register customizer services.
		$this->services['customizer'] = new \AquaLuxe\Customizer\Customizer();
		
		// Register WooCommerce services if WooCommerce is active.
		if ( class_exists( 'WooCommerce' ) ) {
			$this->services['woocommerce'] = new \AquaLuxe\WooCommerce\WooCommerce();
			$this->services['product'] = new \AquaLuxe\WooCommerce\Product();
			$this->services['cart'] = new \AquaLuxe\WooCommerce\Cart();
			$this->services['checkout'] = new \AquaLuxe\WooCommerce\Checkout();
			$this->services['account'] = new \AquaLuxe\WooCommerce\Account();
			$this->services['wishlist'] = new \AquaLuxe\WooCommerce\Wishlist();
			$this->services['quick_view'] = new \AquaLuxe\WooCommerce\Quick_View();
		}
	}

	/**
	 * Initialize services
	 *
	 * @return void
	 */
	private function initialize_services() {
		foreach ( $this->services as $service ) {
			if ( method_exists( $service, 'initialize' ) ) {
				$service->initialize();
			}
		}
	}

	/**
	 * Get a service
	 *
	 * @param string $service_name Service name.
	 * @return mixed
	 */
	public function get_service( $service_name ) {
		if ( isset( $this->services[ $service_name ] ) ) {
			return $this->services[ $service_name ];
		}
		return null;
	}
}