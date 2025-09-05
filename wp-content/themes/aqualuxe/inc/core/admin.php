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
		if ( $add_settings_error ) { \call_user_func( $add_settings_error, 'aqualuxe_settings', 'saved', __( 'Settings saved.', 'aqualuxe' ), 'updated' ); }
	}
	if ( $settings_errors ) { \call_user_func( $settings_errors, 'aqualuxe_settings' ); }

	$defaults = [ 'dark_mode','multilingual','importer','wishlist','quick_view','advanced_filters','multicurrency' ];
	$current  = (array) ( $get_option ? \call_user_func( $get_option, OPT_MODULES, $defaults ) : $defaults );
	$convert_enabled = (string) ( $get_option ? \call_user_func( $get_option, 'aqualuxe_currency_convert', '1' ) : '1' );
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
			</tbody></table>
			<?php $submit_btn = \function_exists('submit_button') ? 'submit_button' : null; if ($submit_btn) { \call_user_func($submit_btn); } ?>
		</form>
	</div>
	<?php
}
