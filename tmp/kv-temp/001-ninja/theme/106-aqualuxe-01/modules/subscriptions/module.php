<?php
/** Subscriptions stub Module */

declare( strict_types=1 );

namespace AquaLuxe\Modules\Subscriptions;

use AquaLuxe\Core\Base_Module;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Module extends Base_Module {
	public function id(): string { return 'subscriptions'; }
	public function enabled(): bool { return true; }
	public function register(): void { /* Future: integrate w/ WC Subscriptions if present */ }
	public function boot(): void {}
}
