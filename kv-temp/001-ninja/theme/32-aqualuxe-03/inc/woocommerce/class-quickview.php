<?php
/**
 * AquaLuxe WooCommerce QuickView Class
 *
 * @package AquaLuxe
 * @subpackage WooCommerce
 * @since 1.1.0
 */

namespace AquaLuxe\WooCommerce;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce QuickView Class
 *
 * Handles WooCommerce quick view functionality.
 *
 * @since 1.1.0
 */
class QuickView {

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Check if quick view is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_quick_view', true ) ) {
			return;
		}

		// Quick view button.
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'quick_view_button' ), 10 );
		
		// Quick view modal.
		add_action( 'wp_footer', array( $this, 'quick_view_modal' ) );
		
		// Quick view AJAX.
		add_action( 'wp_ajax_aqualuxe_quick_view', array( $this, 'quick_view_ajax' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', array( $this, 'quick_view_ajax' ) );
		
		// Quick view template.
		add_filter( 'woocommerce_locate_template', array( $this, 'quick_view_template' ), 10, 3 );
	}

	/**
	 * Quick view button.
	 *
	 * @return void
	 */
	public function quick_view_button() {
		global $product;
		
		// Check if quick view is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_quick_view', true ) ) {
			return;
		}
		
		// Get button style.
		$button_style = get_theme_mod( 'aqualuxe_quick_view_button_style', 'icon-text' );
		
		// Get button icon.
		$button_icon = get_theme_mod( 'aqualuxe_quick_view_button_icon', 'eye' );
		
		// Get button text.
		$button_text = get_theme_mod( 'aqualuxe_quick_view_button_text', __( 'Quick View', 'aqualuxe' ) );
		
		// Output button.
		echo '<div class="quick-view-button">';
		echo '<a href="#" class="button quick-view" data-product-id="' . esc_attr( $product->get_id() ) . '">';
		
		if ( 'icon-text' === $button_style || 'icon-only' === $button_style ) {
			echo '<i class="fas fa-' . esc_attr( $button_icon ) . '"></i>';
		}
		
		if ( 'icon-text' === $button_style || 'text-only' === $button_style ) {
			echo '<span>' . esc_html( $button_text ) . '</span>';
		}
		
		echo '</a>';
		echo '</div>';
	}

	/**
	 * Quick view modal.
	 *
	 * @return void
	 */
	public function quick_view_modal() {
		// Check if quick view is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_quick_view', true ) ) {
			return;
		}
		
		// Get modal style.
		$modal_style = get_theme_mod( 'aqualuxe_quick_view_modal_style', 'standard' );
		
		// Output modal.
		echo '<div id="quick-view-modal" class="quick-view-modal style-' . esc_attr( $modal_style ) . '">';
		echo '<div class="quick-view-modal-content">';
		echo '<span class="quick-view-close">&times;</span>';
		echo '<div class="quick-view-content"></div>';
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Quick view AJAX.
	 *
	 * @return void
	 */
	public function quick_view_ajax() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-woocommerce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ) );
		}
		
		// Check product ID.
		if ( ! isset( $_POST['product_id'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid product ID.', 'aqualuxe' ) ) );
		}
		
		// Get product.
		$product_id = absint( $_POST['product_id'] );
		$product    = wc_get_product( $product_id );
		
		if ( ! $product ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Product not found.', 'aqualuxe' ) ) );
		}
		
		// Get quick view content.
		ob_start();
		include get_template_directory() . '/woocommerce/quick-view-content.php';
		$content = ob_get_clean();
		
		wp_send_json_success( array( 'content' => $content ) );
	}

	/**
	 * Quick view template.
	 *
	 * @param string $template      Template.
	 * @param string $template_name Template name.
	 * @param string $template_path Template path.
	 * @return string
	 */
	public function quick_view_template( $template, $template_name, $template_path ) {
		// Check if quick view template.
		if ( 'quick-view-content.php' === $template_name ) {
			// Get theme template path.
			$theme_template = get_template_directory() . '/woocommerce/' . $template_name;
			
			// Return theme template if it exists.
			if ( file_exists( $theme_template ) ) {
				return $theme_template;
			}
			
			// Return plugin template.
			return plugin_dir_path( __FILE__ ) . 'templates/' . $template_name;
		}
		
		return $template;
	}
}