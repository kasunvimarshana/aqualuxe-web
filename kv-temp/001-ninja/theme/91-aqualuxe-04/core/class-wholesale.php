<?php
/**
 * Wholesale functionality.
 *
 * @package AquaLuxe
 * @since   1.1.0
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Wholesale
 */
class Wholesale {

	/**
	 * Wholesale constructor.
	 */
	public function __construct() {
		add_action( 'admin_post_nopriv_wholesale_application', [ $this, 'handle_application' ] );
		add_action( 'admin_post_wholesale_application', [ $this, 'handle_application' ] );
	}

	/**
	 * Add wholesale-related user roles and capabilities.
	 * This should be run once on theme activation.
	 */
	public static function add_roles(): void {
		// Add Wholesale Customer role
		add_role(
			'wholesale_customer',
			__( 'Wholesale Customer', 'aqualuxe' ),
			[
				'read' => true,
				// Add other capabilities as needed, e.g., for specific plugin access
			]
		);

		// Add capabilities to Administrator to manage wholesale accounts
		$admin_role = get_role( 'administrator' );
		if ( $admin_role ) {
			$admin_role->add_cap( 'edit_wholesale_account' );
			$admin_role->add_cap( 'read_wholesale_account' );
			$admin_role->add_cap( 'delete_wholesale_account' );
			$admin_role->add_cap( 'edit_wholesale_accounts' );
			$admin_role->add_cap( 'edit_others_wholesale_accounts' );
			$admin_role->add_cap( 'publish_wholesale_accounts' );
			$admin_role->add_cap( 'read_private_wholesale_accounts' );
			$admin_role->add_cap( 'delete_wholesale_accounts' );
			$admin_role->add_cap( 'delete_private_wholesale_accounts' );
			$admin_role->add_cap( 'delete_published_wholesale_accounts' );
			$admin_role->add_cap( 'delete_others_wholesale_accounts' );
			$admin_role->add_cap( 'edit_private_wholesale_accounts' );
			$admin_role->add_cap( 'edit_published_wholesale_accounts' );
		}
	}

	/**
	 * Handle the wholesale application form submission.
	 */
	public function handle_application(): void {
		// Verify nonce
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'aqualuxe_wholesale_application' ) ) {
			wp_die( __( 'Security check failed. Please try again.', 'aqualuxe' ) );
		}

		// Basic validation
		$required_fields = [ 'company_name', 'contact_name', 'user_email' ];
		foreach ( $required_fields as $field ) {
			if ( empty( $_POST[ $field ] ) ) {
				wp_redirect( add_query_arg( 'status', 'error', wp_get_referer() ) );
				exit;
			}
		}

		$email = sanitize_email( $_POST['user_email'] );
		if ( ! is_email( $email ) || email_exists( $email ) || username_exists( $email ) ) {
			wp_redirect( add_query_arg( 'status', 'email_exists', wp_get_referer() ) );
			exit;
		}

		// Create user
		$password = wp_generate_password();
		$user_id  = wp_create_user( $email, $password, $email );

		if ( is_wp_error( $user_id ) ) {
			wp_redirect( add_query_arg( 'status', 'user_error', wp_get_referer() ) );
			exit;
		}

		// Assign wholesale role
		$user = new \WP_User( $user_id );
		$user->set_role( 'wholesale_customer' );

		// Create wholesale account post
		$account_post = [
			'post_title'   => sanitize_text_field( $_POST['company_name'] ),
			'post_status'  => 'publish',
			'post_author'  => $user_id,
			'post_type'    => 'wholesale_account',
			'post_content' => sprintf(
				"Contact Name: %s\nEmail: %s\nWebsite: %s",
				sanitize_text_field( $_POST['contact_name'] ),
				$email,
				esc_url_raw( $_POST['business_website'] ?? '' )
			),
		];
		$post_id      = wp_insert_post( $account_post );

		if ( $post_id ) {
			// Set initial status
			wp_set_object_terms( $post_id, 'pending-review', 'account_status' );
			// Link user to the account post
			update_post_meta( $post_id, '_user_id', $user_id );
			update_user_meta( $user_id, '_wholesale_account_post_id', $post_id );
		}

		// Send notifications
		wp_new_user_notification( $user_id, null, 'both' );

		// Notify admin
		$admin_email = get_option( 'admin_email' );
		$subject     = __( 'New Wholesale Account Application', 'aqualuxe' );
		$message     = sprintf(
			__( 'A new wholesale application has been submitted by %s (%s).', 'aqualuxe' ),
			sanitize_text_field( $_POST['company_name'] ),
			$email
		);
		$message    .= "\n\n" . admin_url( 'post.php?post=' . $post_id . '&action=edit' );
		wp_mail( $admin_email, $subject, $message );

		// Redirect to a success page
		wp_redirect( add_query_arg( 'status', 'success', wp_get_referer() ) );
		exit;
	}
}
