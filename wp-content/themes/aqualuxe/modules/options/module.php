<?php
/**
 * Aqualuxe Options Module
 *
 * @package Aqualuxe
 * @subpackage Modules/Options
 */

namespace Aqualuxe\Modules\Options;

use Aqualuxe\Core\Module as BaseModule;
use Aqualuxe\Core\Contracts\Module as ModuleContract;

/**
 * Class Module.
 */
class Module extends BaseModule implements ModuleContract {

	/**
	 * Constructor.
	 *
	 * @param string $path The path to the module.
	 * @param array  $info The module information.
	 */
	public function __construct( string $path, array $info ) {
		parent::__construct( $path, $info );

		$this->load_dependencies();
	}

	/**
	 * Load module dependencies.
	 */
	private function load_dependencies(): void {
		require_once $this->path . 'inc/customizer.php';
		require_once $this->path . 'inc/output.php';
	}
}
