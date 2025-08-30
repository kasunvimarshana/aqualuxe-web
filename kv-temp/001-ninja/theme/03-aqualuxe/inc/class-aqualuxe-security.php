<?php
/**
 * AquaLuxe Security Implementation
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Security Implementation Class
 */
class AquaLuxe_Security {
	/**
	 * Constructor
	 */
	public function __construct() {
		// Add security headers
		add_action( 'send_headers', array( $this, 'add_security_headers' ) );
		
		// Disable XML-RPC
		add_filter( 'xmlrpc_enabled', '__return_false' );
		
		// Remove WordPress version
		add_filter( 'the_generator', '__return_empty_string' );
		
		// Remove WordPress version from scripts and styles
		add_filter( 'style_loader_src', array( $this, 'remove_wp_version' ), 9999 );
		add_filter( 'script_loader_src', array( $this, 'remove_wp_version' ), 9999 );
		
		// Disable user enumeration
		add_action( 'init', array( $this, 'disable_user_enumeration' ) );
		
		// Secure login page
		add_filter( 'login_errors', array( $this, 'custom_login_error' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'remove_login_shake' ) );
		
		// Secure cookies
		add_filter( 'secure_signon_cookie', array( $this, 'secure_cookie' ) );
		add_filter( 'secure_auth_cookie', array( $this, 'secure_cookie' ) );
		add_filter( 'secure_logged_in_cookie', array( $this, 'secure_cookie' ) );
		
		// Add security to AJAX requests
		add_action( 'wp_ajax_nopriv_aqualuxe_ajax', array( $this, 'verify_ajax_request' ) );
		add_action( 'wp_ajax_aqualuxe_ajax', array( $this, 'verify_ajax_request' ) );
		
		// Add security to forms
		add_action( 'wp_head', array( $this, 'add_nonce_to_forms' ) );
		add_action( 'wp_footer', array( $this, 'add_nonce_script' ) );
		
		// Add security to contact forms
		add_filter( 'wpcf7_form_elements', array( $this, 'add_honeypot_to_cf7' ) );
		
		// Add security to comments
		add_filter( 'preprocess_comment', array( $this, 'verify_comment_form' ) );
		
		// Add security to search
		add_filter( 'get_search_form', array( $this, 'add_nonce_to_search_form' ) );
		
		// Add security to WooCommerce
		if ( class_exists( 'WooCommerce' ) ) {
			add_action( 'woocommerce_before_add_to_cart_form', array( $this, 'add_nonce_to_add_to_cart' ) );
			add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'verify_add_to_cart' ), 10, 3 );
		}
	}

	/**
	 * Add security headers
	 */
	public function add_security_headers() {
		// X-Content-Type-Options
		header( 'X-Content-Type-Options: nosniff' );
		
		// X-Frame-Options
		header( 'X-Frame-Options: SAMEORIGIN' );
		
		// X-XSS-Protection
		header( 'X-XSS-Protection: 1; mode=block' );
		
		// Referrer-Policy
		header( 'Referrer-Policy: strict-origin-when-cross-origin' );
		
		// Content-Security-Policy
		$csp = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google-analytics.com https://www.googletagmanager.com https://fonts.googleapis.com https://fonts.gstatic.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; img-src 'self' data: https://www.google-analytics.com https://www.googletagmanager.com; font-src 'self' https://fonts.gstatic.com; connect-src 'self' https://www.google-analytics.com https://www.googletagmanager.com; frame-src 'self'; object-src 'none'; media-src 'self'; worker-src 'self'; manifest-src 'self'; base-uri 'self'; form-action 'self';";
		
		// Only add CSP in production
		if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
			header( 'Content-Security-Policy: ' . $csp );
		}
	}

	/**
	 * Remove WordPress version from scripts and styles
	 *
	 * @param string $src Source URL.
	 * @return string
	 */
	public function remove_wp_version( $src ) {
		if ( strpos( $src, 'ver=' ) ) {
			$src = remove_query_arg( 'ver', $src );
		}
		
		return $src;
	}

	/**
	 * Disable user enumeration
	 */
	public function disable_user_enumeration() {
		if ( is_admin() ) {
			return;
		}
		
		// Block user enumeration
		if ( isset( $_GET['author'] ) && ! current_user_can( 'edit_others_posts' ) ) {
			wp_redirect( home_url(), 301 );
			exit;
		}
	}

	/**
	 * Custom login error message
	 *
	 * @param string $error Error message.
	 * @return string
	 */
	public function custom_login_error( $error ) {
		return esc_html__( 'Invalid login credentials. Please try again.', 'aqualuxe' );
	}

	/**
	 * Remove login shake
	 */
	public function remove_login_shake() {
		remove_action( 'login_head', 'wp_shake_js', 12 );
	}

	/**
	 * Secure cookie
	 *
	 * @param bool $secure Whether the cookie should be secure.
	 * @return bool
	 */
	public function secure_cookie( $secure ) {
		// Force secure cookies if HTTPS is enabled
		if ( is_ssl() ) {
			return true;
		}
		
		return $secure;
	}

	/**
	 * Verify AJAX request
	 */
	public function verify_ajax_request() {
		// Check nonce
		if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'aqualuxe-ajax-nonce' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Security check failed', 'aqualuxe' ) ) );
		}
		
		// Check action
		if ( ! isset( $_REQUEST['action_type'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid action', 'aqualuxe' ) ) );
		}
		
		// Process action
		$action_type = sanitize_text_field( wp_unslash( $_REQUEST['action_type'] ) );
		
		switch ( $action_type ) {
			case 'contact_form':
				$this->process_contact_form();
				break;
			case 'newsletter_signup':
				$this->process_newsletter_signup();
				break;
			default:
				wp_send_json_error( array( 'message' => esc_html__( 'Invalid action', 'aqualuxe' ) ) );
				break;
		}
	}

	/**
	 * Process contact form
	 */
	private function process_contact_form() {
		// Check required fields
		$required_fields = array( 'name', 'email', 'message' );
		
		foreach ( $required_fields as $field ) {
			if ( ! isset( $_POST[ $field ] ) || empty( $_POST[ $field ] ) ) {
				wp_send_json_error( array( 'message' => esc_html__( 'Please fill in all required fields', 'aqualuxe' ) ) );
			}
		}
		
		// Sanitize input
		$name = sanitize_text_field( wp_unslash( $_POST['name'] ) );
		$email = sanitize_email( wp_unslash( $_POST['email'] ) );
		$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ) );
		
		// Validate email
		if ( ! is_email( $email ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Please enter a valid email address', 'aqualuxe' ) ) );
		}
		
		// Check honeypot
		if ( isset( $_POST['website'] ) && ! empty( $_POST['website'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Form submission failed', 'aqualuxe' ) ) );
		}
		
		// Send email
		$to = get_option( 'admin_email' );
		$subject = sprintf( esc_html__( 'Contact Form: %s', 'aqualuxe' ), get_bloginfo( 'name' ) );
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		
		$body = '<p><strong>' . esc_html__( 'Name', 'aqualuxe' ) . ':</strong> ' . esc_html( $name ) . '</p>';
		$body .= '<p><strong>' . esc_html__( 'Email', 'aqualuxe' ) . ':</strong> ' . esc_html( $email ) . '</p>';
		$body .= '<p><strong>' . esc_html__( 'Message', 'aqualuxe' ) . ':</strong></p>';
		$body .= '<p>' . nl2br( esc_html( $message ) ) . '</p>';
		
		$result = wp_mail( $to, $subject, $body, $headers );
		
		if ( $result ) {
			wp_send_json_success( array( 'message' => esc_html__( 'Thank you for your message. We will get back to you soon.', 'aqualuxe' ) ) );
		} else {
			wp_send_json_error( array( 'message' => esc_html__( 'Failed to send message. Please try again later.', 'aqualuxe' ) ) );
		}
	}

	/**
	 * Process newsletter signup
	 */
	private function process_newsletter_signup() {
		// Check required fields
		if ( ! isset( $_POST['email'] ) || empty( $_POST['email'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Please enter your email address', 'aqualuxe' ) ) );
		}
		
		// Sanitize input
		$email = sanitize_email( wp_unslash( $_POST['email'] ) );
		
		// Validate email
		if ( ! is_email( $email ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Please enter a valid email address', 'aqualuxe' ) ) );
		}
		
		// Check honeypot
		if ( isset( $_POST['website'] ) && ! empty( $_POST['website'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Form submission failed', 'aqualuxe' ) ) );
		}
		
		// Store email in database or send to newsletter service
		// This is just a placeholder - implement your own newsletter signup logic
		
		wp_send_json_success( array( 'message' => esc_html__( 'Thank you for signing up for our newsletter!', 'aqualuxe' ) ) );
	}

	/**
	 * Add nonce to forms
	 */
	public function add_nonce_to_forms() {
		// Add nonce field to all forms
		echo '<script>
			document.addEventListener("DOMContentLoaded", function() {
				var forms = document.querySelectorAll("form:not(.no-nonce)");
				for (var i = 0; i < forms.length; i++) {
					var nonceField = document.createElement("input");
					nonceField.type = "hidden";
					nonceField.name = "aqualuxe_nonce";
					nonceField.value = "' . esc_js( wp_create_nonce( 'aqualuxe-form-nonce' ) ) . '";
					forms[i].appendChild(nonceField);
				}
			});
		</script>';
	}

	/**
	 * Add nonce script
	 */
	public function add_nonce_script() {
		// Add nonce for AJAX requests
		echo '<script>
			var aqualuxeAjaxNonce = "' . esc_js( wp_create_nonce( 'aqualuxe-ajax-nonce' ) ) . '";
		</script>';
	}

	/**
	 * Add honeypot to Contact Form 7
	 *
	 * @param string $form Form HTML.
	 * @return string
	 */
	public function add_honeypot_to_cf7( $form ) {
		// Add honeypot field
		$honeypot = '<div class="honeypot-field" style="display:none !important;">';
		$honeypot .= '<input type="text" name="website" value="" autocomplete="off">';
		$honeypot .= '</div>';
		
		return $form . $honeypot;
	}

	/**
	 * Verify comment form
	 *
	 * @param array $comment_data Comment data.
	 * @return array
	 */
	public function verify_comment_form( $comment_data ) {
		// Skip for logged in users
		if ( is_user_logged_in() ) {
			return $comment_data;
		}
		
		// Check nonce
		if ( ! isset( $_POST['aqualuxe_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aqualuxe_nonce'] ) ), 'aqualuxe-form-nonce' ) ) {
			wp_die( esc_html__( 'Security check failed. Please try again.', 'aqualuxe' ), esc_html__( 'Comment Submission Failed', 'aqualuxe' ), array( 'back_link' => true ) );
		}
		
		return $comment_data;
	}

	/**
	 * Add nonce to search form
	 *
	 * @param string $form Search form HTML.
	 * @return string
	 */
	public function add_nonce_to_search_form( $form ) {
		// Add nonce field
		$nonce_field = wp_nonce_field( 'aqualuxe-search-nonce', 'aqualuxe_search_nonce', false, false );
		
		return str_replace( '</form>', $nonce_field . '</form>', $form );
	}

	/**
	 * Add nonce to add to cart form
	 */
	public function add_nonce_to_add_to_cart() {
		// Add nonce field
		wp_nonce_field( 'aqualuxe-add-to-cart-nonce', 'aqualuxe_add_to_cart_nonce' );
	}

	/**
	 * Verify add to cart
	 *
	 * @param bool $valid Whether the add to cart is valid.
	 * @param int $product_id Product ID.
	 * @param int $quantity Quantity.
	 * @return bool
	 */
	public function verify_add_to_cart( $valid, $product_id, $quantity ) {
		// Skip for AJAX requests (handled by WooCommerce)
		if ( wp_doing_ajax() ) {
			return $valid;
		}
		
		// Check nonce
		if ( ! isset( $_POST['aqualuxe_add_to_cart_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aqualuxe_add_to_cart_nonce'] ) ), 'aqualuxe-add-to-cart-nonce' ) ) {
			wc_add_notice( esc_html__( 'Security check failed. Please try again.', 'aqualuxe' ), 'error' );
			return false;
		}
		
		return $valid;
	}

	/**
	 * Sanitize input
	 *
	 * @param mixed $input Input to sanitize.
	 * @param string $type Type of input.
	 * @return mixed
	 */
	public static function sanitize_input( $input, $type = 'text' ) {
		switch ( $type ) {
			case 'text':
				return sanitize_text_field( $input );
			case 'email':
				return sanitize_email( $input );
			case 'url':
				return esc_url_raw( $input );
			case 'textarea':
				return sanitize_textarea_field( $input );
			case 'html':
				return wp_kses_post( $input );
			case 'int':
				return intval( $input );
			case 'float':
				return floatval( $input );
			case 'bool':
				return (bool) $input;
			case 'array':
				if ( ! is_array( $input ) ) {
					return array();
				}
				
				$sanitized = array();
				
				foreach ( $input as $key => $value ) {
					if ( is_array( $value ) ) {
						$sanitized[ sanitize_text_field( $key ) ] = self::sanitize_input( $value, 'array' );
					} else {
						$sanitized[ sanitize_text_field( $key ) ] = sanitize_text_field( $value );
					}
				}
				
				return $sanitized;
			default:
				return sanitize_text_field( $input );
		}
	}

	/**
	 * Escape output
	 *
	 * @param mixed $output Output to escape.
	 * @param string $type Type of output.
	 * @return mixed
	 */
	public static function escape_output( $output, $type = 'html' ) {
		switch ( $type ) {
			case 'html':
				return wp_kses_post( $output );
			case 'attr':
				return esc_attr( $output );
			case 'url':
				return esc_url( $output );
			case 'js':
				return esc_js( $output );
			case 'textarea':
				return esc_textarea( $output );
			case 'json':
				return esc_html( wp_json_encode( $output ) );
			default:
				return esc_html( $output );
		}
	}

	/**
	 * Verify nonce
	 *
	 * @param string $nonce Nonce.
	 * @param string $action Nonce action.
	 * @return bool
	 */
	public static function verify_nonce( $nonce, $action ) {
		return wp_verify_nonce( $nonce, $action );
	}
}

// Initialize the class
new AquaLuxe_Security();