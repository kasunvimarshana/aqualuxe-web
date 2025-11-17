<?php
/**
 * AquaLuxe Theme Customizer - WooCommerce Settings
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Customizer\Sections;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer WooCommerce Settings
 */
class WooCommerce {

	/**
	 * Constructor
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function __construct( $wp_customize ) {
		$this->register_settings( $wp_customize );
	}

	/**
	 * Register customizer settings
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register_settings( $wp_customize ) {
		// Only add these settings if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Add WooCommerce section.
		$wp_customize->add_section(
			'aqualuxe_woocommerce',
			array(
				'title'    => esc_html__( 'WooCommerce Settings', 'aqualuxe' ),
				'priority' => 50,
				'panel'    => 'aqualuxe_theme_options',
			)
		);

		// Shop Layout.
		$wp_customize->add_setting(
			'aqualuxe_shop_layout',
			array(
				'default'           => 'grid',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_shop_layout',
			array(
				'label'    => esc_html__( 'Shop Layout', 'aqualuxe' ),
				'section'  => 'aqualuxe_woocommerce',
				'type'     => 'select',
				'choices'  => array(
					'grid'    => esc_html__( 'Grid', 'aqualuxe' ),
					'list'    => esc_html__( 'List', 'aqualuxe' ),
					'masonry' => esc_html__( 'Masonry', 'aqualuxe' ),
				),
				'priority' => 10,
			)
		);

		// Products Per Row.
		$wp_customize->add_setting(
			'aqualuxe_products_per_row',
			array(
				'default'           => '3',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_products_per_row',
			array(
				'label'       => esc_html__( 'Products Per Row', 'aqualuxe' ),
				'description' => esc_html__( 'For Grid and Masonry layouts', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 6,
					'step' => 1,
				),
				'priority'    => 20,
				'active_callback' => function() {
					return get_theme_mod( 'aqualuxe_shop_layout', 'grid' ) !== 'list';
				},
			)
		);

		// Products Per Page.
		$wp_customize->add_setting(
			'aqualuxe_products_per_page',
			array(
				'default'           => '12',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_products_per_page',
			array(
				'label'       => esc_html__( 'Products Per Page', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 100,
					'step' => 1,
				),
				'priority'    => 30,
			)
		);

		// Product Hover Effect.
		$wp_customize->add_setting(
			'aqualuxe_product_hover_effect',
			array(
				'default'           => 'zoom',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_product_hover_effect',
			array(
				'label'    => esc_html__( 'Product Hover Effect', 'aqualuxe' ),
				'section'  => 'aqualuxe_woocommerce',
				'type'     => 'select',
				'choices'  => array(
					'none'       => esc_html__( 'None', 'aqualuxe' ),
					'zoom'       => esc_html__( 'Zoom', 'aqualuxe' ),
					'fade'       => esc_html__( 'Fade', 'aqualuxe' ),
					'slide'      => esc_html__( 'Slide', 'aqualuxe' ),
					'flip'       => esc_html__( 'Flip', 'aqualuxe' ),
					'alternate'  => esc_html__( 'Alternate Image', 'aqualuxe' ),
				),
				'priority' => 40,
			)
		);

		// Quick View.
		$wp_customize->add_setting(
			'aqualuxe_enable_quick_view',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_enable_quick_view',
			array(
				'label'    => esc_html__( 'Enable Quick View', 'aqualuxe' ),
				'section'  => 'aqualuxe_woocommerce',
				'type'     => 'checkbox',
				'priority' => 50,
			)
		);

		// Wishlist.
		$wp_customize->add_setting(
			'aqualuxe_enable_wishlist',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_enable_wishlist',
			array(
				'label'    => esc_html__( 'Enable Wishlist', 'aqualuxe' ),
				'section'  => 'aqualuxe_woocommerce',
				'type'     => 'checkbox',
				'priority' => 60,
			)
		);

		// Product Compare.
		$wp_customize->add_setting(
			'aqualuxe_enable_compare',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_enable_compare',
			array(
				'label'    => esc_html__( 'Enable Product Compare', 'aqualuxe' ),
				'section'  => 'aqualuxe_woocommerce',
				'type'     => 'checkbox',
				'priority' => 70,
			)
		);

		// AJAX Add to Cart.
		$wp_customize->add_setting(
			'aqualuxe_enable_ajax_add_to_cart',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_enable_ajax_add_to_cart',
			array(
				'label'    => esc_html__( 'Enable AJAX Add to Cart', 'aqualuxe' ),
				'section'  => 'aqualuxe_woocommerce',
				'type'     => 'checkbox',
				'priority' => 80,
			)
		);

		// Product Gallery Zoom.
		$wp_customize->add_setting(
			'aqualuxe_enable_gallery_zoom',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_enable_gallery_zoom',
			array(
				'label'    => esc_html__( 'Enable Product Gallery Zoom', 'aqualuxe' ),
				'section'  => 'aqualuxe_woocommerce',
				'type'     => 'checkbox',
				'priority' => 90,
			)
		);

		// Product Gallery Lightbox.
		$wp_customize->add_setting(
			'aqualuxe_enable_gallery_lightbox',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_enable_gallery_lightbox',
			array(
				'label'    => esc_html__( 'Enable Product Gallery Lightbox', 'aqualuxe' ),
				'section'  => 'aqualuxe_woocommerce',
				'type'     => 'checkbox',
				'priority' => 100,
			)
		);

		// Product Gallery Slider.
		$wp_customize->add_setting(
			'aqualuxe_enable_gallery_slider',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_enable_gallery_slider',
			array(
				'label'    => esc_html__( 'Enable Product Gallery Slider', 'aqualuxe' ),
				'section'  => 'aqualuxe_woocommerce',
				'type'     => 'checkbox',
				'priority' => 110,
			)
		);
	}
}