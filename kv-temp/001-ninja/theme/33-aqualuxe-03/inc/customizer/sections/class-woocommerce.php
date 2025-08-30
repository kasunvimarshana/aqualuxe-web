<?php
/**
 * WooCommerce Customizer Section
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Customizer\Sections;

use AquaLuxe\Customizer\Controls\Radio_Image_Control;
use AquaLuxe\Customizer\Controls\Slider_Control;
use AquaLuxe\Customizer\Controls\Heading_Control;
use AquaLuxe\Customizer\Controls\Divider_Control;
use AquaLuxe\Customizer\Controls\Toggle_Control;
use AquaLuxe\Customizer\Controls\Sortable_Control;
use AquaLuxe\Customizer\Controls\Color_Alpha_Control;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce Customizer Section Class
 */
class WooCommerce {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Only load if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		add_action( 'customize_register', array( $this, 'register_settings' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'aqualuxe_customize_preview_js', array( $this, 'preview_js' ) );
	}

	/**
	 * Register customizer settings
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register_settings( $wp_customize ) {
		// Add WooCommerce section.
		$wp_customize->add_section(
			'aqualuxe_woocommerce',
			array(
				'title'    => esc_html__( 'WooCommerce', 'aqualuxe' ),
				'priority' => 80,
				'panel'    => 'aqualuxe_theme_options',
			)
		);

		// Shop Layout Settings.
		$wp_customize->add_setting(
			'aqualuxe_woocommerce_shop_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_woocommerce_shop_heading',
				array(
					'label'    => esc_html__( 'Shop Layout', 'aqualuxe' ),
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 10,
				)
			)
		);

		// Shop Layout.
		$wp_customize->add_setting(
			'aqualuxe_shop_layout',
			array(
				'default'           => 'grid',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			new Radio_Image_Control(
				$wp_customize,
				'aqualuxe_shop_layout',
				array(
					'label'    => esc_html__( 'Shop Layout', 'aqualuxe' ),
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 20,
					'choices'  => array(
						'grid'     => array(
							'label' => esc_html__( 'Grid', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/shop-grid.svg',
						),
						'list'     => array(
							'label' => esc_html__( 'List', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/shop-list.svg',
						),
						'masonry'  => array(
							'label' => esc_html__( 'Masonry', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/shop-masonry.svg',
						),
					),
				)
			)
		);

		// Shop Columns.
		$wp_customize->add_setting(
			'aqualuxe_shop_columns',
			array(
				'default'           => 4,
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_shop_columns',
				array(
					'label'       => esc_html__( 'Shop Columns', 'aqualuxe' ),
					'description' => esc_html__( 'Number of product columns on shop pages.', 'aqualuxe' ),
					'section'     => 'aqualuxe_woocommerce',
					'priority'    => 30,
					'input_attrs' => array(
						'min'  => 1,
						'max'  => 6,
						'step' => 1,
					),
				)
			)
		);

		// Products Per Page.
		$wp_customize->add_setting(
			'aqualuxe_products_per_page',
			array(
				'default'           => 12,
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_products_per_page',
				array(
					'label'       => esc_html__( 'Products Per Page', 'aqualuxe' ),
					'description' => esc_html__( 'Number of products to display per page.', 'aqualuxe' ),
					'section'     => 'aqualuxe_woocommerce',
					'priority'    => 40,
					'input_attrs' => array(
						'min'  => 1,
						'max'  => 48,
						'step' => 1,
					),
				)
			)
		);

		// Shop Sidebar Layout.
		$wp_customize->add_setting(
			'aqualuxe_shop_sidebar',
			array(
				'default'           => 'right-sidebar',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			new Radio_Image_Control(
				$wp_customize,
				'aqualuxe_shop_sidebar',
				array(
					'label'    => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 50,
					'choices'  => array(
						'right-sidebar' => array(
							'label' => esc_html__( 'Right Sidebar', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/sidebar-right.svg',
						),
						'left-sidebar'  => array(
							'label' => esc_html__( 'Left Sidebar', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/sidebar-left.svg',
						),
						'no-sidebar'    => array(
							'label' => esc_html__( 'No Sidebar', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/sidebar-none.svg',
						),
					),
				)
			)
		);

		// Single Product Sidebar Layout.
		$wp_customize->add_setting(
			'aqualuxe_product_sidebar',
			array(
				'default'           => 'no-sidebar',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			new Radio_Image_Control(
				$wp_customize,
				'aqualuxe_product_sidebar',
				array(
					'label'    => esc_html__( 'Product Sidebar', 'aqualuxe' ),
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 60,
					'choices'  => array(
						'right-sidebar' => array(
							'label' => esc_html__( 'Right Sidebar', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/sidebar-right.svg',
						),
						'left-sidebar'  => array(
							'label' => esc_html__( 'Left Sidebar', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/sidebar-left.svg',
						),
						'no-sidebar'    => array(
							'label' => esc_html__( 'No Sidebar', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/sidebar-none.svg',
						),
					),
				)
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_woocommerce_product_card_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_woocommerce_product_card_divider',
				array(
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 70,
				)
			)
		);

		// Product Card Settings.
		$wp_customize->add_setting(
			'aqualuxe_woocommerce_product_card_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_woocommerce_product_card_heading',
				array(
					'label'    => esc_html__( 'Product Card', 'aqualuxe' ),
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 80,
				)
			)
		);

		// Product Card Style.
		$wp_customize->add_setting(
			'aqualuxe_product_card_style',
			array(
				'default'           => 'standard',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			new Radio_Image_Control(
				$wp_customize,
				'aqualuxe_product_card_style',
				array(
					'label'    => esc_html__( 'Product Card Style', 'aqualuxe' ),
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 90,
					'choices'  => array(
						'standard' => array(
							'label' => esc_html__( 'Standard', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/product-card-standard.svg',
						),
						'boxed'    => array(
							'label' => esc_html__( 'Boxed', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/product-card-boxed.svg',
						),
						'minimal'  => array(
							'label' => esc_html__( 'Minimal', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/product-card-minimal.svg',
						),
					),
				)
			)
		);

		// Product Image Hover Effect.
		$wp_customize->add_setting(
			'aqualuxe_product_image_hover',
			array(
				'default'           => 'zoom',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_product_image_hover',
			array(
				'label'    => esc_html__( 'Image Hover Effect', 'aqualuxe' ),
				'section'  => 'aqualuxe_woocommerce',
				'type'     => 'select',
				'priority' => 100,
				'choices'  => array(
					'none'       => esc_html__( 'None', 'aqualuxe' ),
					'zoom'       => esc_html__( 'Zoom', 'aqualuxe' ),
					'fade'       => esc_html__( 'Fade', 'aqualuxe' ),
					'flip'       => esc_html__( 'Flip', 'aqualuxe' ),
					'gallery'    => esc_html__( 'Gallery', 'aqualuxe' ),
				),
			)
		);

		// Product Card Elements.
		$wp_customize->add_setting(
			'aqualuxe_product_card_elements',
			array(
				'default'           => array( 'title', 'rating', 'price', 'add-to-cart' ),
				'sanitize_callback' => array( $this, 'sanitize_multi_choices' ),
			)
		);

		$wp_customize->add_control(
			new Sortable_Control(
				$wp_customize,
				'aqualuxe_product_card_elements',
				array(
					'label'    => esc_html__( 'Product Card Elements', 'aqualuxe' ),
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 110,
					'choices'  => array(
						'title'        => esc_html__( 'Title', 'aqualuxe' ),
						'rating'       => esc_html__( 'Rating', 'aqualuxe' ),
						'price'        => esc_html__( 'Price', 'aqualuxe' ),
						'add-to-cart'  => esc_html__( 'Add to Cart', 'aqualuxe' ),
						'categories'   => esc_html__( 'Categories', 'aqualuxe' ),
						'excerpt'      => esc_html__( 'Short Description', 'aqualuxe' ),
					),
				)
			)
		);

		// Quick View.
		$wp_customize->add_setting(
			'aqualuxe_product_quick_view',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_product_quick_view',
				array(
					'label'       => esc_html__( 'Quick View', 'aqualuxe' ),
					'description' => esc_html__( 'Enable quick view feature for products.', 'aqualuxe' ),
					'section'     => 'aqualuxe_woocommerce',
					'priority'    => 120,
				)
			)
		);

		// Wishlist.
		$wp_customize->add_setting(
			'aqualuxe_product_wishlist',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_product_wishlist',
				array(
					'label'       => esc_html__( 'Wishlist', 'aqualuxe' ),
					'description' => esc_html__( 'Enable wishlist feature for products.', 'aqualuxe' ),
					'section'     => 'aqualuxe_woocommerce',
					'priority'    => 130,
				)
			)
		);

		// Compare.
		$wp_customize->add_setting(
			'aqualuxe_product_compare',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_product_compare',
				array(
					'label'       => esc_html__( 'Compare', 'aqualuxe' ),
					'description' => esc_html__( 'Enable compare feature for products.', 'aqualuxe' ),
					'section'     => 'aqualuxe_woocommerce',
					'priority'    => 140,
				)
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_woocommerce_single_product_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_woocommerce_single_product_divider',
				array(
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 150,
				)
			)
		);

		// Single Product Settings.
		$wp_customize->add_setting(
			'aqualuxe_woocommerce_single_product_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_woocommerce_single_product_heading',
				array(
					'label'    => esc_html__( 'Single Product', 'aqualuxe' ),
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 160,
				)
			)
		);

		// Product Layout.
		$wp_customize->add_setting(
			'aqualuxe_product_layout',
			array(
				'default'           => 'standard',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			new Radio_Image_Control(
				$wp_customize,
				'aqualuxe_product_layout',
				array(
					'label'    => esc_html__( 'Product Layout', 'aqualuxe' ),
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 170,
					'choices'  => array(
						'standard' => array(
							'label' => esc_html__( 'Standard', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/product-standard.svg',
						),
						'wide'     => array(
							'label' => esc_html__( 'Wide', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/product-wide.svg',
						),
						'gallery'  => array(
							'label' => esc_html__( 'Gallery', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/product-gallery.svg',
						),
						'sticky'   => array(
							'label' => esc_html__( 'Sticky', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/product-sticky.svg',
						),
					),
				)
			)
		);

		// Gallery Style.
		$wp_customize->add_setting(
			'aqualuxe_product_gallery_style',
			array(
				'default'           => 'horizontal',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_product_gallery_style',
			array(
				'label'    => esc_html__( 'Gallery Style', 'aqualuxe' ),
				'section'  => 'aqualuxe_woocommerce',
				'type'     => 'select',
				'priority' => 180,
				'choices'  => array(
					'horizontal' => esc_html__( 'Horizontal Thumbnails', 'aqualuxe' ),
					'vertical'   => esc_html__( 'Vertical Thumbnails', 'aqualuxe' ),
					'grid'       => esc_html__( 'Grid', 'aqualuxe' ),
					'slider'     => esc_html__( 'Slider Only', 'aqualuxe' ),
				),
			)
		);

		// Image Zoom.
		$wp_customize->add_setting(
			'aqualuxe_product_image_zoom',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_product_image_zoom',
				array(
					'label'       => esc_html__( 'Image Zoom', 'aqualuxe' ),
					'description' => esc_html__( 'Enable zoom feature for product images.', 'aqualuxe' ),
					'section'     => 'aqualuxe_woocommerce',
					'priority'    => 190,
				)
			)
		);

		// Image Lightbox.
		$wp_customize->add_setting(
			'aqualuxe_product_image_lightbox',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_product_image_lightbox',
				array(
					'label'       => esc_html__( 'Image Lightbox', 'aqualuxe' ),
					'description' => esc_html__( 'Enable lightbox feature for product images.', 'aqualuxe' ),
					'section'     => 'aqualuxe_woocommerce',
					'priority'    => 200,
				)
			)
		);

		// Product Sticky Add to Cart.
		$wp_customize->add_setting(
			'aqualuxe_product_sticky_add_to_cart',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_product_sticky_add_to_cart',
				array(
					'label'       => esc_html__( 'Sticky Add to Cart', 'aqualuxe' ),
					'description' => esc_html__( 'Show sticky add to cart bar when scrolling down product pages.', 'aqualuxe' ),
					'section'     => 'aqualuxe_woocommerce',
					'priority'    => 210,
				)
			)
		);

		// Product Tabs Style.
		$wp_customize->add_setting(
			'aqualuxe_product_tabs_style',
			array(
				'default'           => 'horizontal',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_product_tabs_style',
			array(
				'label'    => esc_html__( 'Product Tabs Style', 'aqualuxe' ),
				'section'  => 'aqualuxe_woocommerce',
				'type'     => 'select',
				'priority' => 220,
				'choices'  => array(
					'horizontal' => esc_html__( 'Horizontal Tabs', 'aqualuxe' ),
					'vertical'   => esc_html__( 'Vertical Tabs', 'aqualuxe' ),
					'accordion'  => esc_html__( 'Accordion', 'aqualuxe' ),
					'sections'   => esc_html__( 'Sections', 'aqualuxe' ),
				),
			)
		);

		// Related Products.
		$wp_customize->add_setting(
			'aqualuxe_product_related',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_product_related',
				array(
					'label'       => esc_html__( 'Related Products', 'aqualuxe' ),
					'description' => esc_html__( 'Show related products section.', 'aqualuxe' ),
					'section'     => 'aqualuxe_woocommerce',
					'priority'    => 230,
				)
			)
		);

		// Related Products Count.
		$wp_customize->add_setting(
			'aqualuxe_product_related_count',
			array(
				'default'           => 4,
				'sanitize_callback' => 'absint',
				'active_callback'   => array( $this, 'is_related_products_enabled' ),
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_product_related_count',
				array(
					'label'       => esc_html__( 'Related Products Count', 'aqualuxe' ),
					'description' => esc_html__( 'Number of related products to display.', 'aqualuxe' ),
					'section'     => 'aqualuxe_woocommerce',
					'priority'    => 240,
					'input_attrs' => array(
						'min'  => 1,
						'max'  => 12,
						'step' => 1,
					),
					'active_callback' => array( $this, 'is_related_products_enabled' ),
				)
			)
		);

		// Upsells.
		$wp_customize->add_setting(
			'aqualuxe_product_upsells',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_product_upsells',
				array(
					'label'       => esc_html__( 'Upsell Products', 'aqualuxe' ),
					'description' => esc_html__( 'Show upsell products section.', 'aqualuxe' ),
					'section'     => 'aqualuxe_woocommerce',
					'priority'    => 250,
				)
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_woocommerce_cart_checkout_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_woocommerce_cart_checkout_divider',
				array(
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 260,
				)
			)
		);

		// Cart & Checkout Settings.
		$wp_customize->add_setting(
			'aqualuxe_woocommerce_cart_checkout_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_woocommerce_cart_checkout_heading',
				array(
					'label'    => esc_html__( 'Cart & Checkout', 'aqualuxe' ),
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 270,
				)
			)
		);

		// Cart Layout.
		$wp_customize->add_setting(
			'aqualuxe_cart_layout',
			array(
				'default'           => 'standard',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_cart_layout',
			array(
				'label'    => esc_html__( 'Cart Layout', 'aqualuxe' ),
				'section'  => 'aqualuxe_woocommerce',
				'type'     => 'select',
				'priority' => 280,
				'choices'  => array(
					'standard' => esc_html__( 'Standard', 'aqualuxe' ),
					'modern'   => esc_html__( 'Modern', 'aqualuxe' ),
					'compact'  => esc_html__( 'Compact', 'aqualuxe' ),
				),
			)
		);

		// Checkout Layout.
		$wp_customize->add_setting(
			'aqualuxe_checkout_layout',
			array(
				'default'           => 'standard',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_checkout_layout',
			array(
				'label'    => esc_html__( 'Checkout Layout', 'aqualuxe' ),
				'section'  => 'aqualuxe_woocommerce',
				'type'     => 'select',
				'priority' => 290,
				'choices'  => array(
					'standard'   => esc_html__( 'Standard', 'aqualuxe' ),
					'modern'     => esc_html__( 'Modern', 'aqualuxe' ),
					'two-column' => esc_html__( 'Two Column', 'aqualuxe' ),
					'multistep'  => esc_html__( 'Multi-Step', 'aqualuxe' ),
				),
			)
		);

		// Cross-Sells.
		$wp_customize->add_setting(
			'aqualuxe_cart_cross_sells',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_cart_cross_sells',
				array(
					'label'       => esc_html__( 'Cross-Sell Products', 'aqualuxe' ),
					'description' => esc_html__( 'Show cross-sell products on cart page.', 'aqualuxe' ),
					'section'     => 'aqualuxe_woocommerce',
					'priority'    => 300,
				)
			)
		);

		// Mini Cart Style.
		$wp_customize->add_setting(
			'aqualuxe_mini_cart_style',
			array(
				'default'           => 'dropdown',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_mini_cart_style',
			array(
				'label'    => esc_html__( 'Mini Cart Style', 'aqualuxe' ),
				'section'  => 'aqualuxe_woocommerce',
				'type'     => 'select',
				'priority' => 310,
				'choices'  => array(
					'dropdown' => esc_html__( 'Dropdown', 'aqualuxe' ),
					'offcanvas' => esc_html__( 'Off-Canvas', 'aqualuxe' ),
					'popup'    => esc_html__( 'Popup', 'aqualuxe' ),
				),
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_woocommerce_colors_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_woocommerce_colors_divider',
				array(
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 320,
				)
			)
		);

		// WooCommerce Colors.
		$wp_customize->add_setting(
			'aqualuxe_woocommerce_colors_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_woocommerce_colors_heading',
				array(
					'label'    => esc_html__( 'WooCommerce Colors', 'aqualuxe' ),
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 330,
				)
			)
		);

		// Primary Color.
		$wp_customize->add_setting(
			'aqualuxe_woocommerce_primary_color',
			array(
				'default'           => '#0073aa',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_woocommerce_primary_color',
				array(
					'label'    => esc_html__( 'Primary Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 340,
				)
			)
		);

		// Secondary Color.
		$wp_customize->add_setting(
			'aqualuxe_woocommerce_secondary_color',
			array(
				'default'           => '#f7f7f7',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_woocommerce_secondary_color',
				array(
					'label'    => esc_html__( 'Secondary Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 350,
				)
			)
		);

		// Price Color.
		$wp_customize->add_setting(
			'aqualuxe_woocommerce_price_color',
			array(
				'default'           => '#77a464',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_woocommerce_price_color',
				array(
					'label'    => esc_html__( 'Price Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 360,
				)
			)
		);

		// Sale Badge Color.
		$wp_customize->add_setting(
			'aqualuxe_woocommerce_sale_color',
			array(
				'default'           => '#e2401c',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_woocommerce_sale_color',
				array(
					'label'    => esc_html__( 'Sale Badge Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 370,
				)
			)
		);

		// Star Rating Color.
		$wp_customize->add_setting(
			'aqualuxe_woocommerce_star_color',
			array(
				'default'           => '#ffb900',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_woocommerce_star_color',
				array(
					'label'    => esc_html__( 'Star Rating Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 380,
				)
			)
		);

		// Button Background.
		$wp_customize->add_setting(
			'aqualuxe_woocommerce_button_bg',
			array(
				'default'           => '#0073aa',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new Color_Alpha_Control(
				$wp_customize,
				'aqualuxe_woocommerce_button_bg',
				array(
					'label'    => esc_html__( 'Button Background', 'aqualuxe' ),
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 390,
				)
			)
		);

		// Button Text Color.
		$wp_customize->add_setting(
			'aqualuxe_woocommerce_button_text',
			array(
				'default'           => '#ffffff',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_woocommerce_button_text',
				array(
					'label'    => esc_html__( 'Button Text Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_woocommerce',
					'priority' => 400,
				)
			)
		);
	}

	/**
	 * Check if related products are enabled
	 *
	 * @return bool
	 */
	public function is_related_products_enabled() {
		return get_theme_mod( 'aqualuxe_product_related', true );
	}

	/**
	 * Sanitize choices
	 *
	 * @param string $input Value to sanitize.
	 * @param object $setting Setting object.
	 * @return string Sanitized value.
	 */
	public function sanitize_choices( $input, $setting ) {
		// Get the list of possible choices.
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// Return input if valid or return default.
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}

	/**
	 * Sanitize multi choices
	 *
	 * @param array $input Value to sanitize.
	 * @return array Sanitized value.
	 */
	public function sanitize_multi_choices( $input ) {
		if ( ! is_array( $input ) ) {
			return array();
		}

		$valid_input = array();
		foreach ( $input as $value ) {
			if ( ! empty( $value ) ) {
				$valid_input[] = sanitize_text_field( $value );
			}
		}

		return $valid_input;
	}

	/**
	 * Enqueue frontend styles
	 */
	public function enqueue_styles() {
		// Get WooCommerce settings.
		$primary_color   = get_theme_mod( 'aqualuxe_woocommerce_primary_color', '#0073aa' );
		$secondary_color = get_theme_mod( 'aqualuxe_woocommerce_secondary_color', '#f7f7f7' );
		$price_color     = get_theme_mod( 'aqualuxe_woocommerce_price_color', '#77a464' );
		$sale_color      = get_theme_mod( 'aqualuxe_woocommerce_sale_color', '#e2401c' );
		$star_color      = get_theme_mod( 'aqualuxe_woocommerce_star_color', '#ffb900' );
		$button_bg       = get_theme_mod( 'aqualuxe_woocommerce_button_bg', '#0073aa' );
		$button_text     = get_theme_mod( 'aqualuxe_woocommerce_button_text', '#ffffff' );
		$shop_columns    = get_theme_mod( 'aqualuxe_shop_columns', 4 );
		$shop_layout     = get_theme_mod( 'aqualuxe_shop_layout', 'grid' );
		$card_style      = get_theme_mod( 'aqualuxe_product_card_style', 'standard' );

		// Generate inline styles.
		$css = '';

		// Primary color.
		$css .= '.woocommerce .woocommerce-info, .woocommerce .woocommerce-message {';
		$css .= 'border-top-color: ' . esc_attr( $primary_color ) . ';';
		$css .= '}';

		$css .= '.woocommerce .woocommerce-info::before, .woocommerce .woocommerce-message::before {';
		$css .= 'color: ' . esc_attr( $primary_color ) . ';';
		$css .= '}';

		// Price color.
		$css .= '.woocommerce div.product p.price, .woocommerce div.product span.price, .woocommerce ul.products li.product .price {';
		$css .= 'color: ' . esc_attr( $price_color ) . ';';
		$css .= '}';

		// Sale badge.
		$css .= '.woocommerce span.onsale {';
		$css .= 'background-color: ' . esc_attr( $sale_color ) . ';';
		$css .= 'color: #ffffff;';
		$css .= '}';

		// Star rating.
		$css .= '.woocommerce .star-rating span::before {';
		$css .= 'color: ' . esc_attr( $star_color ) . ';';
		$css .= '}';

		// Button styles.
		$css .= '.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt {';
		$css .= 'background-color: ' . esc_attr( $button_bg ) . ';';
		$css .= 'color: ' . esc_attr( $button_text ) . ';';
		$css .= '}';

		$css .= '.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover {';
		$css .= 'background-color: ' . esc_attr( $this->adjust_brightness( $button_bg, -15 ) ) . ';';
		$css .= 'color: ' . esc_attr( $button_text ) . ';';
		$css .= '}';

		// Shop columns.
		$css .= '.woocommerce ul.products {';
		$css .= 'display: grid;';
		$css .= 'grid-template-columns: repeat(' . absint( $shop_columns ) . ', 1fr);';
		$css .= 'grid-gap: 30px;';
		$css .= '}';

		// Responsive columns.
		$css .= '@media (max-width: 992px) {';
		$css .= '.woocommerce ul.products {';
		$css .= 'grid-template-columns: repeat(3, 1fr);';
		$css .= '}';
		$css .= '}';

		$css .= '@media (max-width: 768px) {';
		$css .= '.woocommerce ul.products {';
		$css .= 'grid-template-columns: repeat(2, 1fr);';
		$css .= '}';
		$css .= '}';

		$css .= '@media (max-width: 480px) {';
		$css .= '.woocommerce ul.products {';
		$css .= 'grid-template-columns: 1fr;';
		$css .= '}';
		$css .= '}';

		// List layout.
		if ( 'list' === $shop_layout ) {
			$css .= '.woocommerce ul.products li.product {';
			$css .= 'display: flex;';
			$css .= 'flex-direction: row;';
			$css .= 'align-items: center;';
			$css .= '}';

			$css .= '.woocommerce ul.products li.product .woocommerce-loop-product__link {';
			$css .= 'flex: 0 0 30%;';
			$css .= 'margin-right: 30px;';
			$css .= '}';

			$css .= '.woocommerce ul.products li.product .product-content {';
			$css .= 'flex: 1;';
			$css .= '}';

			// Responsive styles.
			$css .= '@media (max-width: 768px) {';
			$css .= '.woocommerce ul.products li.product {';
			$css .= 'flex-direction: column;';
			$css .= '}';
			$css .= '.woocommerce ul.products li.product .woocommerce-loop-product__link {';
			$css .= 'flex: 0 0 100%;';
			$css .= 'margin-right: 0;';
			$css .= 'margin-bottom: 20px;';
			$css .= '}';
			$css .= '}';
		}

		// Product card styles.
		if ( 'boxed' === $card_style ) {
			$css .= '.woocommerce ul.products li.product {';
			$css .= 'border: 1px solid #eee;';
			$css .= 'border-radius: 5px;';
			$css .= 'padding: 20px;';
			$css .= 'transition: box-shadow 0.3s ease;';
			$css .= '}';

			$css .= '.woocommerce ul.products li.product:hover {';
			$css .= 'box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);';
			$css .= '}';
		} elseif ( 'minimal' === $card_style ) {
			$css .= '.woocommerce ul.products li.product {';
			$css .= 'text-align: center;';
			$css .= '}';

			$css .= '.woocommerce ul.products li.product .button {';
			$css .= 'opacity: 0;';
			$css .= 'transform: translateY(10px);';
			$css .= 'transition: all 0.3s ease;';
			$css .= '}';

			$css .= '.woocommerce ul.products li.product:hover .button {';
			$css .= 'opacity: 1;';
			$css .= 'transform: translateY(0);';
			$css .= '}';
		}

		// Add inline style.
		if ( ! empty( $css ) ) {
			wp_add_inline_style( 'aqualuxe-woocommerce-style', $css );
		}
	}

	/**
	 * Adjust color brightness
	 *
	 * @param string $hex_color Hex color code.
	 * @param int    $percent Percentage to adjust.
	 * @return string Adjusted hex color.
	 */
	public function adjust_brightness( $hex_color, $percent ) {
		// Remove # if present.
		$hex_color = ltrim( $hex_color, '#' );

		// Convert to RGB.
		$r = hexdec( substr( $hex_color, 0, 2 ) );
		$g = hexdec( substr( $hex_color, 2, 2 ) );
		$b = hexdec( substr( $hex_color, 4, 2 ) );

		// Adjust brightness.
		$r = max( 0, min( 255, $r + $percent ) );
		$g = max( 0, min( 255, $g + $percent ) );
		$b = max( 0, min( 255, $b + $percent ) );

		// Convert back to hex.
		return '#' . sprintf( '%02x%02x%02x', $r, $g, $b );
	}

	/**
	 * Add preview JS
	 */
	public function preview_js() {
		wp_enqueue_script(
			'aqualuxe-woocommerce-preview',
			get_template_directory_uri() . '/assets/js/admin/customizer-woocommerce-preview.js',
			array( 'customize-preview', 'jquery' ),
			AQUALUXE_VERSION,
			true
		);
	}
}

// Initialize the class.
new WooCommerce();