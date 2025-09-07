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

use AquaLuxe\Core\Custom_Post_Types;
use AquaLuxe\Core\Custom_Taxonomies;
use AquaLuxe\Core\Theme_Scripts;
use AquaLuxe\Core\Theme_Setup;
use AquaLuxe\Core\Wholesale;
use AquaLuxe\Core\WooCommerce;

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
