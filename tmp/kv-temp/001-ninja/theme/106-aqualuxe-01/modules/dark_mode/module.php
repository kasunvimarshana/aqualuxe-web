<?php
/**
 * Dark Mode Module.
 * @package AquaLuxe\Modules\Dark_Mode
 */

declare( strict_types=1 );

namespace AquaLuxe\Modules\Dark_Mode;

use AquaLuxe\Core\Base_Module;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Module extends Base_Module {
	public function id(): string { return 'dark_mode'; }

	public function register(): void {
		add_action( 'wp_body_open', [ $this, 'render_toggle' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
	}

	public function boot(): void {
	}

	public function enqueue(): void {
		$js = aqualuxe_asset( 'dark-mode.js' );
		if ( $js ) { wp_enqueue_script( 'aqualuxe-dark-mode', $js, [], null, true ); }
	}

	public function render_toggle(): void { ?>
		<button id="aqualuxe-dark-mode" class="aqlx-dark-toggle" aria-pressed="false" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'aqualuxe' ); ?>">
			<span class="label-light"><?php esc_html_e( 'Light', 'aqualuxe' ); ?></span>
			<span class="label-dark"><?php esc_html_e( 'Dark', 'aqualuxe' ); ?></span>
		</button>
	<?php }
}
