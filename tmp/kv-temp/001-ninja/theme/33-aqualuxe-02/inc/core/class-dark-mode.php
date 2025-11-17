<?php
/**
 * Dark Mode Class
 *
 * @package AquaLuxe
 * @subpackage Core
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Dark Mode Class
 *
 * This class handles dark mode functionality.
 */
class Dark_Mode extends Service {

	/**
	 * Initialize the service
	 *
	 * @return void
	 */
	public function initialize() {
		$this->register_hooks();
	}

	/**
	 * Register hooks
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action( 'wp_head', array( $this, 'dark_mode_early_detection' ), 0 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_dark_mode_assets' ) );
		add_action( 'wp_ajax_aqualuxe_toggle_dark_mode', array( $this, 'toggle_dark_mode' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_toggle_dark_mode', array( $this, 'toggle_dark_mode' ) );
		add_filter( 'body_class', array( $this, 'add_dark_mode_class' ) );
	}

	/**
	 * Early detection of dark mode preference
	 *
	 * This function is executed very early in the page load process
	 * to prevent FOUC (Flash of Unstyled Content) when dark mode is enabled.
	 *
	 * @return void
	 */
	public function dark_mode_early_detection() {
		// Check if dark mode is enabled in the customizer.
		$dark_mode_enabled = $this->get_theme_option( 'aqualuxe_enable_dark_mode', true );
		
		// If dark mode is not enabled in the customizer, return early.
		if ( ! $dark_mode_enabled ) {
			return;
		}

		// Output the dark mode detection script.
		?>
		<script>
		// Function to get cookie value
		function getCookie(name) {
			const value = `; ${document.cookie}`;
			const parts = value.split(`; ${name}=`);
			if (parts.length === 2) return parts.pop().split(';').shift();
			return null;
		}

		// Function to check if dark mode should be enabled
		function shouldEnableDarkMode() {
			// Check if user has set a preference
			const userPreference = getCookie('aqualuxe_dark_mode');
			if (userPreference === 'true') return true;
			if (userPreference === 'false') return false;
			
			// Check theme default setting
			const defaultMode = '<?php echo esc_js( $this->get_theme_option( 'aqualuxe_dark_mode_default', 'auto' ) ); ?>';
			if (defaultMode === 'dark') return true;
			if (defaultMode === 'light') return false;
			
			// If set to auto, check system preference
			if (defaultMode === 'auto') {
				return window.matchMedia('(prefers-color-scheme: dark)').matches;
			}
			
			return false;
		}

		// Apply dark mode class if needed
		if (shouldEnableDarkMode()) {
			document.documentElement.classList.add('dark');
		} else {
			document.documentElement.classList.remove('dark');
		}
		</script>
		<?php
	}

	/**
	 * Enqueue dark mode assets
	 *
	 * @return void
	 */
	public function enqueue_dark_mode_assets() {
		// Check if dark mode is enabled in the customizer.
		$dark_mode_enabled = $this->get_theme_option( 'aqualuxe_enable_dark_mode', true );
		
		// If dark mode is not enabled in the customizer, return early.
		if ( ! $dark_mode_enabled ) {
			return;
		}

		// Enqueue dark mode script.
		wp_enqueue_script(
			'aqualuxe-dark-mode',
			AQUALUXE_ASSETS_URI . 'js/dark-mode.js',
			array( 'jquery' ),
			AQUALUXE_VERSION,
			true
		);

		// Localize the script with dark mode settings.
		wp_localize_script(
			'aqualuxe-dark-mode',
			'aqualuxeDarkMode',
			array(
				'ajaxurl'      => admin_url( 'admin-ajax.php' ),
				'nonce'        => wp_create_nonce( 'aqualuxe-dark-mode-nonce' ),
				'defaultMode'  => $this->get_theme_option( 'aqualuxe_dark_mode_default', 'auto' ),
				'cookieExpiry' => 30, // Cookie expiry in days.
			)
		);
	}

	/**
	 * Toggle dark mode via AJAX
	 *
	 * @return void
	 */
	public function toggle_dark_mode() {
		// Check nonce.
		if ( ! $this->verify_nonce( $_POST['nonce'], 'aqualuxe-dark-mode-nonce' ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ) );
		}

		// Get the dark mode state.
		$dark_mode = isset( $_POST['darkMode'] ) ? sanitize_text_field( $_POST['darkMode'] ) : 'false';

		// Send the response.
		wp_send_json_success( array( 'darkMode' => $dark_mode ) );
	}

	/**
	 * Add dark mode class to body
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	public function add_dark_mode_class( $classes ) {
		// Check if dark mode is enabled in the customizer.
		$dark_mode_enabled = $this->get_theme_option( 'aqualuxe_enable_dark_mode', true );
		
		// If dark mode is not enabled in the customizer, return the classes.
		if ( ! $dark_mode_enabled ) {
			return $classes;
		}

		// Check if the user has set a preference.
		if ( isset( $_COOKIE['aqualuxe_dark_mode'] ) ) {
			if ( 'true' === $_COOKIE['aqualuxe_dark_mode'] ) {
				$classes[] = 'dark-mode';
			}
			return $classes;
		}

		// Check the default mode.
		$default_mode = $this->get_theme_option( 'aqualuxe_dark_mode_default', 'auto' );
		
		if ( 'dark' === $default_mode ) {
			$classes[] = 'dark-mode';
		} elseif ( 'auto' === $default_mode ) {
			// We can't detect system preference on the server side,
			// so we'll rely on the early detection script to add the class.
		}

		return $classes;
	}

	/**
	 * Get dark mode class
	 *
	 * @return string
	 */
	public function get_dark_mode_class() {
		// Check if dark mode is enabled in the customizer.
		$dark_mode_enabled = $this->get_theme_option( 'aqualuxe_enable_dark_mode', true );
		
		// If dark mode is not enabled in the customizer, return empty string.
		if ( ! $dark_mode_enabled ) {
			return '';
		}

		// Check if the user has set a preference.
		if ( isset( $_COOKIE['aqualuxe_dark_mode'] ) ) {
			if ( 'true' === $_COOKIE['aqualuxe_dark_mode'] ) {
				return 'dark-mode';
			}
			return '';
		}

		// Check the default mode.
		$default_mode = $this->get_theme_option( 'aqualuxe_dark_mode_default', 'auto' );
		
		if ( 'dark' === $default_mode ) {
			return 'dark-mode';
		}

		return '';
	}

	/**
	 * Check if dark mode is active
	 *
	 * @return bool
	 */
	public function is_dark_mode_active() {
		// Check if dark mode is enabled in the customizer.
		$dark_mode_enabled = $this->get_theme_option( 'aqualuxe_enable_dark_mode', true );
		
		// If dark mode is not enabled in the customizer, return false.
		if ( ! $dark_mode_enabled ) {
			return false;
		}

		// Check if the user has set a preference.
		if ( isset( $_COOKIE['aqualuxe_dark_mode'] ) ) {
			return 'true' === $_COOKIE['aqualuxe_dark_mode'];
		}

		// Check the default mode.
		$default_mode = $this->get_theme_option( 'aqualuxe_dark_mode_default', 'auto' );
		
		if ( 'dark' === $default_mode ) {
			return true;
		}

		return false;
	}
}