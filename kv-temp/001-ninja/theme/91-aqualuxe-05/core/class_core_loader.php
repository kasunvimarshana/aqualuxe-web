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
use AquaLuxe\Modules\Ui_Ux\Ui_Ux;
use AquaLuxe\Core\Theme_Scripts;
use AquaLuxe\Core\Theme_Setup;
use AquaLuxe\Modules\Wholesale\Wholesale;
use AquaLuxe\Modules\WooCommerce\WooCommerce;

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
	];

	/**
	 * Core_Loader constructor.
	 */
	public function __construct() {
		$this->load_classes();
		if ( \class_exists( 'WooCommerce' ) ) {
			new WooCommerce();
		}
	}

	/**
	 * Instantiate the necessary classes.
	 */
	private function load_classes(): void {
		foreach ( $this->classes as $class ) {
			$instance = new $class();
		}
	}
}
