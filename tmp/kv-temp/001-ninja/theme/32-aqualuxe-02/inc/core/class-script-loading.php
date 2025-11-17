<?php
/**
 * Script Loading Handler
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.1.0
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Script Loading Class
 *
 * Handles the implementation of async and defer attributes for scripts to improve page load performance.
 *
 * @since 1.1.0
 */
class Script_Loading {

	/**
	 * Scripts to load with async attribute.
	 *
	 * @var array
	 */
	private $async_scripts = [];

	/**
	 * Scripts to load with defer attribute.
	 *
	 * @var array
	 */
	private $defer_scripts = [];

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		// Set default async and defer scripts.
		$this->set_default_scripts();
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		// Skip if script loading optimization is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_script_loading_optimization', true ) ) {
			return;
		}

		// Add async and defer attributes to scripts.
		add_filter( 'script_loader_tag', array( $this, 'add_async_defer_attributes' ), 10, 3 );

		// Add admin menu.
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Set default async and defer scripts.
	 *
	 * @return void
	 */
	private function set_default_scripts() {
		// Get async scripts from theme mod.
		$async_scripts = get_theme_mod( 'aqualuxe_async_scripts', [] );
		if ( empty( $async_scripts ) ) {
			$async_scripts = [];
		}

		// Get defer scripts from theme mod.
		$defer_scripts = get_theme_mod( 'aqualuxe_defer_scripts', [] );
		if ( empty( $defer_scripts ) ) {
			$defer_scripts = [];
		}

		// Add default async scripts.
		$default_async = [
			'google-analytics',
			'ga',
			'gtag',
			'gtm',
			'facebook-pixel',
			'fbevents',
			'twitter-widgets',
			'pinterest-pinit',
		];

		// Add default defer scripts.
		$default_defer = [
			'jquery-migrate',
			'comment-reply',
			'wp-embed',
			'wp-mediaelement',
		];

		// Merge default scripts with user-defined scripts.
		$this->async_scripts = array_merge( $default_async, $async_scripts );
		$this->defer_scripts = array_merge( $default_defer, $defer_scripts );
	}

	/**
	 * Add async and defer attributes to scripts.
	 *
	 * @param string $tag    Script HTML tag.
	 * @param string $handle Script handle.
	 * @param string $src    Script source.
	 * @return string Modified script HTML tag.
	 */
	public function add_async_defer_attributes( $tag, $handle, $src ) {
		// Skip if script already has async or defer attribute.
		if ( strpos( $tag, ' async' ) !== false || strpos( $tag, ' defer' ) !== false ) {
			return $tag;
		}

		// Add async attribute.
		if ( in_array( $handle, $this->async_scripts, true ) ) {
			$tag = str_replace( ' src', ' async src', $tag );
		}

		// Add defer attribute.
		if ( in_array( $handle, $this->defer_scripts, true ) ) {
			$tag = str_replace( ' src', ' defer src', $tag );
		}

		return $tag;
	}

	/**
	 * Add admin menu for script loading.
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		add_submenu_page(
			'themes.php',
			__( 'Script Loading', 'aqualuxe' ),
			__( 'Script Loading', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-script-loading',
			array( $this, 'admin_page' )
		);
	}

	/**
	 * Register settings for script loading.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting( 'aqualuxe_script_loading', 'aqualuxe_enable_script_loading_optimization' );
		register_setting( 'aqualuxe_script_loading', 'aqualuxe_async_scripts' );
		register_setting( 'aqualuxe_script_loading', 'aqualuxe_defer_scripts' );
	}

	/**
	 * Admin page for script loading.
	 *
	 * @return void
	 */
	public function admin_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Script Loading', 'aqualuxe' ); ?></h1>
			<p><?php esc_html_e( 'Optimize script loading by adding async and defer attributes to scripts. This can improve page load performance by allowing scripts to load asynchronously or after the page has loaded.', 'aqualuxe' ); ?></p>
			
			<form method="post" action="options.php">
				<?php settings_fields( 'aqualuxe_script_loading' ); ?>
				<?php do_settings_sections( 'aqualuxe_script_loading' ); ?>
				
				<h2><?php esc_html_e( 'Script Loading Settings', 'aqualuxe' ); ?></h2>
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'Enable Script Loading Optimization', 'aqualuxe' ); ?></th>
						<td>
							<input type="checkbox" name="aqualuxe_enable_script_loading_optimization" value="1" <?php checked( get_theme_mod( 'aqualuxe_enable_script_loading_optimization', true ) ); ?> />
							<p class="description"><?php esc_html_e( 'Enable script loading optimization to improve page load performance.', 'aqualuxe' ); ?></p>
						</td>
					</tr>
				</table>
				
				<h2><?php esc_html_e( 'Async Scripts', 'aqualuxe' ); ?></h2>
				<p><?php esc_html_e( 'Scripts with the async attribute will load asynchronously while the page is being parsed. They will execute as soon as they are downloaded, which may be before the page has finished parsing.', 'aqualuxe' ); ?></p>
				<p><?php esc_html_e( 'Use async for scripts that do not depend on other scripts and do not modify the DOM.', 'aqualuxe' ); ?></p>
				
				<div id="async-scripts">
					<?php
					$async_scripts = get_theme_mod( 'aqualuxe_async_scripts', [] );
					if ( ! empty( $async_scripts ) ) {
						foreach ( $async_scripts as $index => $handle ) {
							?>
							<div class="script-item">
								<input type="text" name="aqualuxe_async_scripts[]" value="<?php echo esc_attr( $handle ); ?>" class="regular-text" />
								<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
							</div>
							<?php
						}
					} else {
						?>
						<div class="script-item">
							<input type="text" name="aqualuxe_async_scripts[]" value="" class="regular-text" />
							<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
						</div>
						<?php
					}
					?>
				</div>
				<button type="button" class="button add-async-script"><?php esc_html_e( 'Add Async Script', 'aqualuxe' ); ?></button>
				
				<h2><?php esc_html_e( 'Defer Scripts', 'aqualuxe' ); ?></h2>
				<p><?php esc_html_e( 'Scripts with the defer attribute will load while the page is being parsed, but will only execute after the page has finished parsing. They will execute in the order they appear in the document.', 'aqualuxe' ); ?></p>
				<p><?php esc_html_e( 'Use defer for scripts that depend on the DOM being fully parsed or that depend on other scripts.', 'aqualuxe' ); ?></p>
				
				<div id="defer-scripts">
					<?php
					$defer_scripts = get_theme_mod( 'aqualuxe_defer_scripts', [] );
					if ( ! empty( $defer_scripts ) ) {
						foreach ( $defer_scripts as $index => $handle ) {
							?>
							<div class="script-item">
								<input type="text" name="aqualuxe_defer_scripts[]" value="<?php echo esc_attr( $handle ); ?>" class="regular-text" />
								<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
							</div>
							<?php
						}
					} else {
						?>
						<div class="script-item">
							<input type="text" name="aqualuxe_defer_scripts[]" value="" class="regular-text" />
							<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
						</div>
						<?php
					}
					?>
				</div>
				<button type="button" class="button add-defer-script"><?php esc_html_e( 'Add Defer Script', 'aqualuxe' ); ?></button>
				
				<?php submit_button(); ?>
			</form>
			
			<h2><?php esc_html_e( 'Default Scripts', 'aqualuxe' ); ?></h2>
			<p><?php esc_html_e( 'The following scripts are added by default:', 'aqualuxe' ); ?></p>
			
			<h3><?php esc_html_e( 'Default Async Scripts', 'aqualuxe' ); ?></h3>
			<ul>
				<li><code>google-analytics</code> - <?php esc_html_e( 'Google Analytics', 'aqualuxe' ); ?></li>
				<li><code>ga</code> - <?php esc_html_e( 'Google Analytics (alternative)', 'aqualuxe' ); ?></li>
				<li><code>gtag</code> - <?php esc_html_e( 'Google Tag Manager', 'aqualuxe' ); ?></li>
				<li><code>gtm</code> - <?php esc_html_e( 'Google Tag Manager (alternative)', 'aqualuxe' ); ?></li>
				<li><code>facebook-pixel</code> - <?php esc_html_e( 'Facebook Pixel', 'aqualuxe' ); ?></li>
				<li><code>fbevents</code> - <?php esc_html_e( 'Facebook Events', 'aqualuxe' ); ?></li>
				<li><code>twitter-widgets</code> - <?php esc_html_e( 'Twitter Widgets', 'aqualuxe' ); ?></li>
				<li><code>pinterest-pinit</code> - <?php esc_html_e( 'Pinterest Pin It', 'aqualuxe' ); ?></li>
			</ul>
			
			<h3><?php esc_html_e( 'Default Defer Scripts', 'aqualuxe' ); ?></h3>
			<ul>
				<li><code>jquery-migrate</code> - <?php esc_html_e( 'jQuery Migrate', 'aqualuxe' ); ?></li>
				<li><code>comment-reply</code> - <?php esc_html_e( 'WordPress Comment Reply', 'aqualuxe' ); ?></li>
				<li><code>wp-embed</code> - <?php esc_html_e( 'WordPress Embed', 'aqualuxe' ); ?></li>
				<li><code>wp-mediaelement</code> - <?php esc_html_e( 'WordPress Media Element', 'aqualuxe' ); ?></li>
			</ul>
			
			<script>
				jQuery(document).ready(function($) {
					// Add async script.
					$('.add-async-script').on('click', function() {
						var item = '<div class="script-item">' +
							'<input type="text" name="aqualuxe_async_scripts[]" value="" class="regular-text" />' +
							'<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>' +
							'</div>';
						$('#async-scripts').append(item);
					});
					
					// Add defer script.
					$('.add-defer-script').on('click', function() {
						var item = '<div class="script-item">' +
							'<input type="text" name="aqualuxe_defer_scripts[]" value="" class="regular-text" />' +
							'<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>' +
							'</div>';
						$('#defer-scripts').append(item);
					});
					
					// Remove item.
					$(document).on('click', '.remove-item', function() {
						$(this).parent().remove();
					});
				});
			</script>
			
			<style>
				.script-item {
					margin-bottom: 10px;
				}
				.script-item input {
					margin-right: 10px;
				}
			</style>
		</div>
		<?php
	}
}