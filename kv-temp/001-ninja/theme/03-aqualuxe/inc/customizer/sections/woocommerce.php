<?php
/**
 * AquaLuxe WooCommerce Customizer Settings
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce Customizer Settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_woocommerce_customizer_settings( $wp_customize ) {
	// Add WooCommerce section
	$wp_customize->add_section(
		'aqualuxe_woocommerce',
		array(
			'title'    => esc_html__( 'WooCommerce', 'aqualuxe' ),
			'priority' => 30,
		)
	);

	// Shop Layout
	$wp_customize->add_setting(
		'aqualuxe_shop_layout',
		array(
			'default'           => 'right-sidebar',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_shop_layout',
		array(
			'label'    => esc_html__( 'Shop Layout', 'aqualuxe' ),
			'section'  => 'aqualuxe_woocommerce',
			'type'     => 'select',
			'choices'  => array(
				'right-sidebar' => esc_html__( 'Right Sidebar', 'aqualuxe' ),
				'left-sidebar'  => esc_html__( 'Left Sidebar', 'aqualuxe' ),
				'full-width'    => esc_html__( 'Full Width', 'aqualuxe' ),
			),
			'priority' => 10,
		)
	);

	// Shop Columns
	$wp_customize->add_setting(
		'aqualuxe_shop_columns',
		array(
			'default'           => 4,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_shop_columns',
		array(
			'label'    => esc_html__( 'Shop Columns', 'aqualuxe' ),
			'section'  => 'aqualuxe_woocommerce',
			'type'     => 'number',
			'input_attrs' => array(
				'min'  => 2,
				'max'  => 6,
				'step' => 1,
			),
			'priority' => 20,
		)
	);

	// Products Per Page
	$wp_customize->add_setting(
		'aqualuxe_shop_products_per_page',
		array(
			'default'           => 12,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_shop_products_per_page',
		array(
			'label'    => esc_html__( 'Products Per Page', 'aqualuxe' ),
			'section'  => 'aqualuxe_woocommerce',
			'type'     => 'number',
			'input_attrs' => array(
				'min'  => 4,
				'max'  => 48,
				'step' => 4,
			),
			'priority' => 30,
		)
	);

	// Related Products Count
	$wp_customize->add_setting(
		'aqualuxe_related_products_count',
		array(
			'default'           => 4,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_related_products_count',
		array(
			'label'    => esc_html__( 'Related Products Count', 'aqualuxe' ),
			'section'  => 'aqualuxe_woocommerce',
			'type'     => 'number',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 12,
				'step' => 1,
			),
			'priority' => 40,
		)
	);

	// Related Products Columns
	$wp_customize->add_setting(
		'aqualuxe_related_products_columns',
		array(
			'default'           => 4,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_related_products_columns',
		array(
			'label'    => esc_html__( 'Related Products Columns', 'aqualuxe' ),
			'section'  => 'aqualuxe_woocommerce',
			'type'     => 'number',
			'input_attrs' => array(
				'min'  => 2,
				'max'  => 6,
				'step' => 1,
			),
			'priority' => 50,
		)
	);

	// Upsell Products Count
	$wp_customize->add_setting(
		'aqualuxe_upsell_products_count',
		array(
			'default'           => 4,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_upsell_products_count',
		array(
			'label'    => esc_html__( 'Upsell Products Count', 'aqualuxe' ),
			'section'  => 'aqualuxe_woocommerce',
			'type'     => 'number',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 12,
				'step' => 1,
			),
			'priority' => 60,
		)
	);

	// Upsell Products Columns
	$wp_customize->add_setting(
		'aqualuxe_upsell_products_columns',
		array(
			'default'           => 4,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_upsell_products_columns',
		array(
			'label'    => esc_html__( 'Upsell Products Columns', 'aqualuxe' ),
			'section'  => 'aqualuxe_woocommerce',
			'type'     => 'number',
			'input_attrs' => array(
				'min'  => 2,
				'max'  => 6,
				'step' => 1,
			),
			'priority' => 70,
		)
	);

	// Quick View
	$wp_customize->add_setting(
		'aqualuxe_quick_view',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_quick_view',
		array(
			'label'    => esc_html__( 'Enable Quick View', 'aqualuxe' ),
			'section'  => 'aqualuxe_woocommerce',
			'type'     => 'checkbox',
			'priority' => 80,
		)
	);

	// Wishlist
	$wp_customize->add_setting(
		'aqualuxe_wishlist',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_wishlist',
		array(
			'label'    => esc_html__( 'Enable Wishlist', 'aqualuxe' ),
			'section'  => 'aqualuxe_woocommerce',
			'type'     => 'checkbox',
			'priority' => 90,
		)
	);

	// AJAX Add to Cart
	$wp_customize->add_setting(
		'aqualuxe_ajax_add_to_cart',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_ajax_add_to_cart',
		array(
			'label'    => esc_html__( 'Enable AJAX Add to Cart', 'aqualuxe' ),
			'section'  => 'aqualuxe_woocommerce',
			'type'     => 'checkbox',
			'priority' => 100,
		)
	);

	// Product Filter
	$wp_customize->add_setting(
		'aqualuxe_product_filter',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_product_filter',
		array(
			'label'    => esc_html__( 'Enable Product Filter', 'aqualuxe' ),
			'section'  => 'aqualuxe_woocommerce',
			'type'     => 'checkbox',
			'priority' => 110,
		)
	);

	// Product Care Tab
	$wp_customize->add_setting(
		'aqualuxe_product_care_tab',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_product_care_tab',
		array(
			'label'    => esc_html__( 'Enable Care Instructions Tab', 'aqualuxe' ),
			'section'  => 'aqualuxe_woocommerce',
			'type'     => 'checkbox',
			'priority' => 120,
		)
	);

	// Product Shipping Tab
	$wp_customize->add_setting(
		'aqualuxe_product_shipping_tab',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_product_shipping_tab',
		array(
			'label'    => esc_html__( 'Enable Shipping & Returns Tab', 'aqualuxe' ),
			'section'  => 'aqualuxe_woocommerce',
			'type'     => 'checkbox',
			'priority' => 130,
		)
	);

	// Default Care Instructions
	$wp_customize->add_setting(
		'aqualuxe_default_care_instructions',
		array(
			'default'           => esc_html__( 'Care instructions for this product.', 'aqualuxe' ),
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_default_care_instructions',
		array(
			'label'    => esc_html__( 'Default Care Instructions', 'aqualuxe' ),
			'section'  => 'aqualuxe_woocommerce',
			'type'     => 'textarea',
			'priority' => 140,
		)
	);

	// Default Shipping Info
	$wp_customize->add_setting(
		'aqualuxe_default_shipping_info',
		array(
			'default'           => esc_html__( 'Shipping and returns information for this product.', 'aqualuxe' ),
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_default_shipping_info',
		array(
			'label'    => esc_html__( 'Default Shipping & Returns Info', 'aqualuxe' ),
			'section'  => 'aqualuxe_woocommerce',
			'type'     => 'textarea',
			'priority' => 150,
		)
	);

	// Multi-Currency Support
	$wp_customize->add_setting(
		'aqualuxe_multi_currency',
		array(
			'default'           => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_multi_currency',
		array(
			'label'    => esc_html__( 'Enable Multi-Currency Support', 'aqualuxe' ),
			'section'  => 'aqualuxe_woocommerce',
			'type'     => 'checkbox',
			'priority' => 160,
		)
	);

	// Enabled Currencies
	$wp_customize->add_setting(
		'aqualuxe_enabled_currencies',
		array(
			'default'           => array( 'USD', 'EUR', 'GBP' ),
			'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
		)
	);

	$wp_customize->add_control(
		new Aqualuxe_Customize_Control_Multi_Select(
			$wp_customize,
			'aqualuxe_enabled_currencies',
			array(
				'label'    => esc_html__( 'Enabled Currencies', 'aqualuxe' ),
				'section'  => 'aqualuxe_woocommerce',
				'choices'  => aqualuxe_get_currencies_for_customizer(),
				'priority' => 170,
				'active_callback' => function() {
					return get_theme_mod( 'aqualuxe_multi_currency', false );
				},
			)
		)
	);

	// Currency Exchange Rates
	$currencies = array( 'USD', 'EUR', 'GBP', 'CAD', 'AUD', 'JPY' );
	$priority = 180;

	foreach ( $currencies as $currency ) {
		$wp_customize->add_setting(
			'aqualuxe_exchange_rate_' . $currency,
			array(
				'default'           => 1,
				'sanitize_callback' => 'aqualuxe_sanitize_float',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_exchange_rate_' . $currency,
			array(
				'label'    => sprintf( esc_html__( '%s Exchange Rate', 'aqualuxe' ), $currency ),
				'section'  => 'aqualuxe_woocommerce',
				'type'     => 'number',
				'input_attrs' => array(
					'min'  => 0.01,
					'step' => 0.01,
				),
				'priority' => $priority,
				'active_callback' => function() use ( $currency ) {
					$enabled_currencies = get_theme_mod( 'aqualuxe_enabled_currencies', array( 'USD', 'EUR', 'GBP' ) );
					return get_theme_mod( 'aqualuxe_multi_currency', false ) && in_array( $currency, $enabled_currencies, true );
				},
			)
		);

		$priority += 10;
	}

	// International Shipping
	$wp_customize->add_setting(
		'aqualuxe_international_shipping',
		array(
			'default'           => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_international_shipping',
		array(
			'label'    => esc_html__( 'Enable International Shipping Optimization', 'aqualuxe' ),
			'section'  => 'aqualuxe_woocommerce',
			'type'     => 'checkbox',
			'priority' => 250,
		)
	);

	// Shipping Zones
	$shipping_zones = array(
		'europe'        => esc_html__( 'Europe', 'aqualuxe' ),
		'north_america' => esc_html__( 'North America', 'aqualuxe' ),
		'asia_pacific'  => esc_html__( 'Asia Pacific', 'aqualuxe' ),
		'rest_of_world' => esc_html__( 'Rest of World', 'aqualuxe' ),
	);

	$priority = 260;

	foreach ( $shipping_zones as $zone_id => $zone_name ) {
		$wp_customize->add_setting(
			'aqualuxe_shipping_rate_' . $zone_id,
			array(
				'default'           => $zone_id === 'europe' ? 1.0 : ( $zone_id === 'north_america' ? 1.2 : ( $zone_id === 'asia_pacific' ? 1.5 : 2.0 ) ),
				'sanitize_callback' => 'aqualuxe_sanitize_float',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_shipping_rate_' . $zone_id,
			array(
				'label'    => sprintf( esc_html__( '%s Shipping Rate Adjustment', 'aqualuxe' ), $zone_name ),
				'section'  => 'aqualuxe_woocommerce',
				'type'     => 'number',
				'input_attrs' => array(
					'min'  => 0.1,
					'step' => 0.1,
				),
				'priority' => $priority,
				'active_callback' => function() {
					return get_theme_mod( 'aqualuxe_international_shipping', false );
				},
			)
		);

		$wp_customize->add_setting(
			'aqualuxe_shipping_time_' . $zone_id,
			array(
				'default'           => $zone_id === 'europe' ? esc_html__( '5-7 business days', 'aqualuxe' ) : ( $zone_id === 'north_america' ? esc_html__( '7-10 business days', 'aqualuxe' ) : ( $zone_id === 'asia_pacific' ? esc_html__( '10-14 business days', 'aqualuxe' ) : esc_html__( '14-21 business days', 'aqualuxe' ) ) ),
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_shipping_time_' . $zone_id,
			array(
				'label'    => sprintf( esc_html__( '%s Estimated Delivery Time', 'aqualuxe' ), $zone_name ),
				'section'  => 'aqualuxe_woocommerce',
				'type'     => 'text',
				'priority' => $priority + 1,
				'active_callback' => function() {
					return get_theme_mod( 'aqualuxe_international_shipping', false );
				},
			)
		);

		$priority += 20;
	}
}
add_action( 'customize_register', 'aqualuxe_woocommerce_customizer_settings' );

/**
 * Get currencies for customizer
 *
 * @return array
 */
function aqualuxe_get_currencies_for_customizer() {
	$currencies = array();
	$wc_currencies = get_woocommerce_currencies();

	foreach ( $wc_currencies as $code => $name ) {
		$currencies[ $code ] = $name . ' (' . get_woocommerce_currency_symbol( $code ) . ')';
	}

	return $currencies;
}

/**
 * Sanitize multi select
 *
 * @param mixed $input Input value.
 * @return array
 */
function aqualuxe_sanitize_multi_select( $input ) {
	if ( ! is_array( $input ) ) {
		return array();
	}

	return array_map( 'sanitize_text_field', $input );
}

/**
 * Sanitize float
 *
 * @param mixed $input Input value.
 * @return float
 */
function aqualuxe_sanitize_float( $input ) {
	return floatval( $input );
}

/**
 * Multi Select Customizer Control
 */
if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Aqualuxe_Customize_Control_Multi_Select' ) ) {
	/**
	 * Multi Select Customizer Control
	 */
	class Aqualuxe_Customize_Control_Multi_Select extends WP_Customize_Control {
		/**
		 * Control type
		 *
		 * @var string
		 */
		public $type = 'multi-select';

		/**
		 * Render content
		 */
		public function render_content() {
			if ( empty( $this->choices ) ) {
				return;
			}

			if ( ! empty( $this->label ) ) {
				echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
			}

			if ( ! empty( $this->description ) ) {
				echo '<span class="description customize-control-description">' . esc_html( $this->description ) . '</span>';
			}

			$values = $this->value();

			if ( ! is_array( $values ) ) {
				$values = array();
			}

			?>
			<select multiple="multiple" <?php $this->link(); ?>>
				<?php foreach ( $this->choices as $value => $label ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( in_array( $value, $values, true ), true ); ?>>
						<?php echo esc_html( $label ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<script>
				jQuery( document ).ready( function() {
					jQuery( '.customize-control-multi-select select' ).chosen( {
						width: '100%',
						search_contains: true
					} );
				} );
			</script>
			<?php
		}
	}
}