<?php
/**
 * The core loader for the AquaLuxe theme.
 *
 * This class is responsible for loading all the core files.
 *
 * @package AquaLuxe
 * @since   1.1.0
 */

namespace AquaLuxe\Core;

use AquaLuxe\Modules\Custom_Post_Types\Custom_Post_Types;
use AquaLuxe\Modules\Custom_Taxonomies\Custom_Taxonomies;
use AquaLuxe\Modules\Dark_Mode\Dark_Mode;
use AquaLuxe\Modules\Demo_Importer\Demo_Importer;
use AquaLuxe\Modules\Ui_Ux\Ui_Ux;
use AquaLuxe\Core\Theme_Scripts;
use AquaLuxe\Core\Theme_Setup;
use AquaLuxe\Modules\Wholesale\Wholesale;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Core_Loader
 */
class Core_Loader {

	/**
	 * The array of classes to instantiate.
	 *
	 * @var array
	 */
	private array $classes = [
		Theme_Setup::class,
		Theme_Scripts::class,
		Custom_Post_Types::class,
		Custom_Taxonomies::class,
		Wholesale::class,
		Ui_Ux::class,
		Dark_Mode::class,
		Demo_Importer::class,
	];

	/**
	 * Core_Loader constructor.
	 */
	public function __construct() {
		$this->load_classes();
		$this->load_conditional_modules();
	}

	/**
	 * Load conditional modules based on plugin availability
	 */
	private function load_conditional_modules(): void {
		// Load WooCommerce module if WooCommerce plugin is active
		if ( \class_exists( 'WooCommerce' ) ) {
			try {
				$woocommerce_class = '\AquaLuxe\Modules\WooCommerce\WooCommerce';
				if ( class_exists( $woocommerce_class ) ) {
					new $woocommerce_class();
				}
			} catch ( \Exception $e ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					error_log( 'AquaLuxe WooCommerce Module Error: ' . $e->getMessage() );
				}
			}
		}
	}

	/**
	 * Instantiate the necessary classes.
	 */
	private function load_classes(): void {
		foreach ( $this->classes as $class ) {
			try {
				if ( ! class_exists( $class ) ) {
					if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
						error_log( 'AquaLuxe Core Loader: Class not found - ' . $class );
					}
					continue;
				}
				
				if ( method_exists( $class, 'get_instance' ) ) {
					$instance = call_user_func( [ $class, 'get_instance' ] );
				} elseif ( method_exists( $class, 'instance' ) ) {
					$instance = call_user_func( [ $class, 'instance' ] );
				} else {
					$instance = new $class();
				}
			} catch ( \Exception $e ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					error_log( 'AquaLuxe Core Loader Error loading ' . $class . ': ' . $e->getMessage() );
				}
			}
		}
	}
}
