<?php
namespace AquaLuxe\Admin;

use AquaLuxe\Core\Modules;

final class Settings {
	public static function init(): void {
		\add_action( 'admin_menu', [ __CLASS__, 'menu' ] );
		\add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
	}

	public static function menu(): void {
		\add_menu_page(
			'AquaLuxe', 'AquaLuxe', 'manage_options', 'aqualuxe', [ __CLASS__, 'render' ], 'dashicons-water', 59
		);
	}

	public static function register_settings(): void {
		\register_setting( 'aqualuxe', 'aqualuxe_modules', [ 'type' => 'array', 'sanitize_callback' => [ __CLASS__, 'sanitize_modules' ], 'default' => [] ] );
	}

	public static function sanitize_modules( $value ): array {
		$keys = [ 'dark_mode', 'multilingual', 'woocommerce', 'importer' ];
		$san  = [];
		foreach ( $keys as $k ) {
			$san[ $k ] = ! empty( $value[ $k ] );
		}
		return $san;
	}

	public static function render(): void {
		$mods = Modules::get_modules();
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'AquaLuxe Settings', 'aqualuxe' ); ?></h1>
			<form method="post" action="options.php">
				<?php \settings_fields( 'aqualuxe' ); ?>
				<h2 class="title"><?php esc_html_e( 'Modules', 'aqualuxe' ); ?></h2>
				<table class="form-table" role="presentation">
					<tbody>
						<?php foreach ( [ 'dark_mode' => 'Dark Mode', 'multilingual' => 'Multilingual', 'woocommerce' => 'WooCommerce', 'importer' => 'Demo Importer' ] as $key => $label ) : ?>
						<tr>
							<th scope="row"><?php echo esc_html( $label ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="aqualuxe_modules[<?php echo esc_attr( $key ); ?>]" value="1" <?php checked( ! empty( $mods[ $key ] ) ); ?> />
									<?php esc_html_e( 'Enabled', 'aqualuxe' ); ?>
								</label>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php \submit_button(); ?>
			</form>
		</div>
		<?php
	}
}

// Initialize settings page.
Settings::init();
