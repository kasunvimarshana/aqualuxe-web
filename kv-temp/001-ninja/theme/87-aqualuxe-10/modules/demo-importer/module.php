<?php
/**
 * AquaLuxe Demo Importer Module
 *
 * @package Aqualuxe
 * @subpackage Modules/DemoImporter
 */

namespace Aqualuxe\Modules\DemoImporter;

use Aqualuxe\Core\Module as BaseModule;
use Aqualuxe\Core\Contracts\Module as ModuleContract;

class Module extends BaseModule implements ModuleContract {
	public function __construct( string $path, array $info ) {
		parent::__construct( $path, $info );
		$this->load_dependencies();
	}

	private function load_dependencies(): void {
		require_once $this->path . 'inc/importer.php';
	}
}
