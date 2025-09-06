<?php
/** Admin settings for AquaLuxe */
namespace AquaLuxe\Core;
if ( ! defined( 'ABSPATH' ) ) { exit; }

const OPT_MODULES = 'aqualuxe_enabled_modules_option';

\add_action( 'admin_menu', function () {
	\add_theme_page( __( 'AquaLuxe Settings', 'aqualuxe' ), __( 'AquaLuxe Settings', 'aqualuxe' ), 'manage_options', 'aqualuxe_settings', __NAMESPACE__ . '\\render' );
} );

function render() {
	// Resolve WP admin helpers safely
	$verify_nonce = \function_exists('wp_verify_nonce') ? 'wp_verify_nonce' : null;
	$sanitize_text = \function_exists('sanitize_text_field') ? 'sanitize_text_field' : null;
	$update_option = \function_exists('update_option') ? 'update_option' : null;
	$add_settings_error = \function_exists('add_settings_error') ? 'add_settings_error' : null;
	$settings_errors = \function_exists('settings_errors') ? 'settings_errors' : null;
	$get_option = \function_exists('get_option') ? 'get_option' : null;

	// Save
	if ( isset( $_POST['_alx_nonce'] ) && ( $verify_nonce ? (bool) \call_user_func( $verify_nonce, $_POST['_alx_nonce'], 'alx_settings' ) : true ) ) {
		$raw_modules = isset( $_POST['modules'] ) && is_array( $_POST['modules'] ) ? (array) $_POST['modules'] : [];
		$modules = [];
		foreach ( $raw_modules as $m ) { $modules[] = $sanitize_text ? (string) \call_user_func( $sanitize_text, $m ) : (string) $m; }
		if ( $update_option ) { \call_user_func( $update_option, OPT_MODULES, array_values( array_unique( $modules ) ) ); }
		$convert = isset( $_POST['currency_convert'] ) ? '1' : '0';
		if ( $update_option ) { \call_user_func( $update_option, 'aqualuxe_currency_convert', $convert ); }
		// Multicurrency advanced config (textarea line format KEY|VALUE)
		$parse_lines = function( $field ) use ( $sanitize_text ) : array {
			$out = [];
			if ( ! isset( $_POST[ $field ] ) ) { return $out; }
			$raw = (string) $_POST[ $field ];
			$lines = preg_split( "/\r?\n/", $raw );
			foreach ( $lines as $line ) {
				$line = trim( (string) $line );
				if ( $line === '' ) { continue; }
				$parts = explode( '|', $line, 2 );
				$k = isset($parts[0]) ? strtoupper( trim( (string) $parts[0] ) ) : '';
				$v = isset($parts[1]) ? trim( (string) $parts[1] ) : '';
				if ( $k === '' ) { continue; }
				if ( $sanitize_text ) { $k = (string) \call_user_func( $sanitize_text, $k ); $v = (string) \call_user_func( $sanitize_text, $v ); }
				$out[ $k ] = $v;
			}
			return $out;
		};
		if ( $update_option ) {
			\call_user_func( $update_option, 'aqualuxe_currency_allowed_map', $parse_lines('currency_allowed_map') );
			\call_user_func( $update_option, 'aqualuxe_currency_rates_map', $parse_lines('currency_rates_map') );
			\call_user_func( $update_option, 'aqualuxe_currency_decimals_map', $parse_lines('currency_decimals_map') );
			\call_user_func( $update_option, 'aqualuxe_currency_position_map', $parse_lines('currency_position_map') );
		}
		if ( $add_settings_error ) { \call_user_func( $add_settings_error, 'aqualuxe_settings', 'saved', __( 'Settings saved.', 'aqualuxe' ), 'updated' ); }
	}
	if ( $settings_errors ) { \call_user_func( $settings_errors, 'aqualuxe_settings' ); }

	$defaults = [ 'dark_mode','multilingual','importer','wishlist','quick_view','advanced_filters','multicurrency','roles','marketplace','classifieds' ];
	$current  = (array) ( $get_option ? \call_user_func( $get_option, OPT_MODULES, $defaults ) : $defaults );
	$convert_enabled = (string) ( $get_option ? \call_user_func( $get_option, 'aqualuxe_currency_convert', '1' ) : '1' );
	$allowed_map_val = $get_option ? (array) \call_user_func( $get_option, 'aqualuxe_currency_allowed_map', [] ) : [];
	$rates_map_val = $get_option ? (array) \call_user_func( $get_option, 'aqualuxe_currency_rates_map', [] ) : [];
	$decimals_map_val = $get_option ? (array) \call_user_func( $get_option, 'aqualuxe_currency_decimals_map', [] ) : [];
	$position_map_val = $get_option ? (array) \call_user_func( $get_option, 'aqualuxe_currency_position_map', [] ) : [];
	$to_lines = function(array $map, $value_cast = null): string {
		$out = [];
		foreach ($map as $k=>$v) { $out[] = strtoupper((string)$k) . '|' . ( $value_cast ? $value_cast($v) : (string)$v ); }
		return implode("\n", $out);
	};
	$allowed_lines = $to_lines($allowed_map_val);
	$rates_lines = $to_lines($rates_map_val, function($v){ return (string) (float) $v; });
	$decimals_lines = $to_lines($decimals_map_val, function($v){ return (string) (int) $v; });
	$position_lines = $to_lines($position_map_val);
	$all      = $defaults; ?>
	<div class="wrap">
		<h1><?php \esc_html_e( 'AquaLuxe Settings', 'aqualuxe' ); ?></h1>
		<form method="post">
			<?php $nonce_field = \function_exists('wp_nonce_field') ? 'wp_nonce_field' : null; if ($nonce_field) { \call_user_func($nonce_field, 'alx_settings', '_alx_nonce'); } ?>
			<table class="form-table" role="presentation"><tbody>
				<tr><th><?php \esc_html_e( 'Enabled Modules', 'aqualuxe' ); ?></th>
				<td>
					<?php foreach ( $all as $m ) : ?>
						<label style="display:block;margin-bottom:6px"><input type="checkbox" name="modules[]" value="<?php echo \esc_attr( $m ); ?>" <?php echo ( in_array( $m, $current, true ) ? 'checked' : '' ); ?> /> <?php echo \esc_html( ucwords( str_replace( '_',' ', $m ) ) ); ?></label>
					<?php endforeach; ?>
				</td></tr>
				<tr><th><?php \esc_html_e( 'Multicurrency', 'aqualuxe' ); ?></th>
				<td>
					<label><input type="checkbox" name="currency_convert" value="1" <?php echo ( $convert_enabled === '1' ? 'checked' : '' ); ?> /> <?php \esc_html_e( 'Convert prices in catalog (display-only). Cart and checkout remain in store currency.', 'aqualuxe' ); ?></label>
				</td></tr>
				<?php
				// Info box: base currency and sample conversions
				$woo_base = $get_option ? (string) \call_user_func($get_option, 'woocommerce_currency', 'USD') : 'USD';
				// Build allowed currencies list from option (admin-defined) if any
				$allowed_info = $allowed_map_val;
				if (empty($allowed_info)) {
					// try to pull from module runtime if available
					if (\function_exists('AquaLuxe\\Modules\\Multicurrency\\currencies')) {
						$allowed_info = (array) \call_user_func('AquaLuxe\\Modules\\Multicurrency\\currencies');
					}
				}
				$rates_info = $rates_map_val;
				if (empty($rates_info) && \function_exists('AquaLuxe\\Modules\\Multicurrency\\rates')) {
					$rates_info = (array) \call_user_func('AquaLuxe\\Modules\\Multicurrency\\rates');
				}
				?>
				<tr><th><?php \esc_html_e( 'Preview', 'aqualuxe' ); ?></th>
				<td>
					<p><?php echo \esc_html( sprintf( /* translators: %s is currency code */ __( 'WooCommerce base currency: %s', 'aqualuxe' ), strtoupper($woo_base) ) ); ?></p>
					<?php if (!empty($allowed_info)) : ?>
					<table class="widefat" style="max-width:600px"><thead><tr>
						<th><?php \esc_html_e('Code','aqualuxe'); ?></th>
						<th><?php \esc_html_e('Symbol','aqualuxe'); ?></th>
						<th><?php \esc_html_e('Sample (100 base)','aqualuxe'); ?></th>
					</tr></thead><tbody>
					<?php foreach ($allowed_info as $code => $sym) : $code = strtoupper((string)$code); $sym = (string)$sym; $rate = isset($rates_info[$code]) && is_numeric($rates_info[$code]) ? (float) $rates_info[$code] : null; $sample = $rate !== null ? $rate * 100.0 : null; ?>
					<tr>
						<td><?php echo \esc_html($code); ?></td>
						<td><?php echo \esc_html($sym); ?></td>
						<td><?php echo $sample !== null ? \esc_html( (string) $sample ) : '<em>'.\esc_html__('n/a','aqualuxe').'</em>'; ?></td>
					</tr>
					<?php endforeach; ?>
					</tbody></table>
					<?php else : ?>
					<p class="description"><?php \esc_html_e('Define Allowed currencies to see a preview.','aqualuxe'); ?></p>
					<?php endif; ?>
				</td></tr>
				<tr><th><?php \esc_html_e( 'Allowed currencies (CODE|SYMBOL per line)', 'aqualuxe' ); ?></th>
				<td>
					<?php $esc_textarea = \function_exists('esc_textarea') ? 'esc_textarea' : null; $allowed_out = $esc_textarea ? \call_user_func($esc_textarea, $allowed_lines) : \htmlspecialchars($allowed_lines, ENT_QUOTES, 'UTF-8'); ?>
					<textarea name="currency_allowed_map" rows="5" cols="50" class="large-text code" placeholder="USD|$\nEUR|€\nGBP|£\nJPY|¥\nLKR|රු"><?php echo $allowed_out; ?></textarea>
					<p class="description"><?php \esc_html_e( 'Define available currencies. Leave blank to use defaults/filters.', 'aqualuxe' ); ?></p>
				</td></tr>
				<tr><th><?php \esc_html_e( 'Conversion rates (CODE|RATE per line)', 'aqualuxe' ); ?></th>
				<td>
					<?php $rates_out = $esc_textarea ? \call_user_func($esc_textarea, $rates_lines) : \htmlspecialchars($rates_lines, ENT_QUOTES, 'UTF-8'); ?>
					<textarea name="currency_rates_map" rows="5" cols="50" class="large-text code" placeholder="USD|1\nEUR|0.92\nGBP|0.79\nJPY|144\nLKR|300"><?php echo $rates_out; ?></textarea>
					<p class="description"><?php \esc_html_e( 'Rates are relative to your WooCommerce store base currency (1 base = RATE of code).', 'aqualuxe' ); ?></p>
				</td></tr>
				<tr><th><?php \esc_html_e( 'Decimals (CODE|DECIMALS per line)', 'aqualuxe' ); ?></th>
				<td>
					<?php $decimals_out = $esc_textarea ? \call_user_func($esc_textarea, $decimals_lines) : \htmlspecialchars($decimals_lines, ENT_QUOTES, 'UTF-8'); ?>
					<textarea name="currency_decimals_map" rows="3" cols="50" class="large-text code" placeholder="JPY|0\nKWD|3"><?php echo $decimals_out; ?></textarea>
					<p class="description"><?php \esc_html_e( 'Override decimal places per currency. Leave blank to use Woo default.', 'aqualuxe' ); ?></p>
				</td></tr>
				<tr><th><?php \esc_html_e( 'Position (CODE|left|right|left_space|right_space per line)', 'aqualuxe' ); ?></th>
				<td>
					<?php $position_out = $esc_textarea ? \call_user_func($esc_textarea, $position_lines) : \htmlspecialchars($position_lines, ENT_QUOTES, 'UTF-8'); ?>
					<textarea name="currency_position_map" rows="3" cols="50" class="large-text code" placeholder="USD|left\nEUR|right_space"><?php echo $position_out; ?></textarea>
					<p class="description"><?php \esc_html_e( 'Choose symbol position per currency.', 'aqualuxe' ); ?></p>
				</td></tr>
			</tbody></table>
			<?php $submit_btn = \function_exists('submit_button') ? 'submit_button' : null; if ($submit_btn) { \call_user_func($submit_btn); } ?>
		</form>
	</div>
	<?php
}
