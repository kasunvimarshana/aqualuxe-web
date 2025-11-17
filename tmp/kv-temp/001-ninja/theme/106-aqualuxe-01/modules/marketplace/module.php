<?php
/** Marketplace (multivendor stub) Module */

declare( strict_types=1 );

namespace AquaLuxe\Modules\Marketplace;

use AquaLuxe\Core\Base_Module;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Module extends Base_Module {
	public function id(): string { return 'marketplace'; }
	public function enabled(): bool { return true; }

	public function register(): void {
		// Placeholder for vendor role & capabilities.
		add_action( 'init', [ $this, 'register_roles' ] );
	}
	public function boot(): void {}

	public function register_roles(): void {
		add_role( 'aqlx_vendor', __( 'Vendor', 'aqualuxe' ), [ 'read' => true, 'upload_files' => true ] );
	}
}
