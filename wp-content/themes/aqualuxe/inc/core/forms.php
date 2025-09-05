<?php
/** Form handlers (standard HTTP fallback) */
namespace AquaLuxe\Core;
if ( ! defined( 'ABSPATH' ) ) { exit; }

\add_action( 'template_redirect', function () {
	// Newsletter submission
	if ( isset( $_POST['alx_news_email'] ) && isset( $_POST['_alx_news'] ) && \wp_verify_nonce( $_POST['_alx_news'], 'alx_newsletter' ) ) {
		$email = sanitize_email( wp_unslash( $_POST['alx_news_email'] ) );
		if ( is_email( $email ) ) {
			\wp_mail( get_option( 'admin_email' ), 'Newsletter signup', 'New subscriber: ' . $email );
		}
		\wp_safe_redirect( add_query_arg( 'subscribed', '1', \wp_get_referer() ?: home_url( '/' ) ) );
		exit;
	}
	// Contact form
	if ( isset( $_POST['_alx_contact'] ) && \wp_verify_nonce( $_POST['_alx_contact'], 'alx_contact' ) ) {
		$name = sanitize_text_field( wp_unslash( $_POST['alx_name'] ?? '' ) );
		$email = sanitize_email( wp_unslash( $_POST['alx_email'] ?? '' ) );
		$msg = wp_kses_post( wp_unslash( $_POST['alx_message'] ?? '' ) );
		if ( $name && is_email( $email ) && $msg ) {
			\wp_mail( get_option( 'admin_email' ), 'Contact form', "From: $name <$email>\n\n$msg" );
		}
		\wp_safe_redirect( add_query_arg( 'contact', 'sent', \wp_get_referer() ?: home_url( '/' ) ) );
		exit;
	}
} );
