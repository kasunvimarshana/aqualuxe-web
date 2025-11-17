<?php
/**
 * AquaLuxe WooCommerce International Shipping Class
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
 * WooCommerce International Shipping Class
 *
 * Handles WooCommerce international shipping functionality.
 *
 * @since 1.1.0
 */
class InternationalShipping {

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

		// Check if international shipping is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_international_shipping', false ) ) {
			return;
		}

		// Shipping hooks.
		add_filter( 'woocommerce_package_rates', array( $this, 'filter_shipping_methods' ), 10, 2 );
		add_filter( 'woocommerce_shipping_methods', array( $this, 'add_shipping_methods' ) );
		add_filter( 'woocommerce_shipping_calculator_enable_country', '__return_true' );
		
		// Checkout hooks.
		add_filter( 'woocommerce_checkout_fields', array( $this, 'checkout_fields' ) );
		add_filter( 'woocommerce_countries_allowed_countries', array( $this, 'allowed_countries' ) );
		add_filter( 'woocommerce_countries_shipping_countries', array( $this, 'shipping_countries' ) );
		add_filter( 'woocommerce_countries_shipping_country_states', array( $this, 'shipping_country_states' ) );
		
		// Cart hooks.
		add_action( 'woocommerce_cart_calculate_fees', array( $this, 'add_international_fees' ) );
		add_action( 'woocommerce_review_order_before_shipping', array( $this, 'shipping_notice' ) );
		
		// Order hooks.
		add_action( 'woocommerce_checkout_create_order', array( $this, 'update_order_meta' ), 10, 2 );
		add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, 'display_admin_order_meta' ), 10, 1 );
		
		// Email hooks.
		add_action( 'woocommerce_email_after_order_table', array( $this, 'email_shipping_details' ), 10, 4 );
		
		// Account hooks.
		add_filter( 'woocommerce_my_account_my_orders_columns', array( $this, 'orders_columns' ) );
		add_action( 'woocommerce_my_account_my_orders_column_shipping-country', array( $this, 'orders_shipping_country_column' ), 10, 1 );
		
		// Product hooks.
		add_action( 'woocommerce_product_options_shipping', array( $this, 'product_shipping_options' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_shipping_options' ) );
		
		// Admin hooks.
		add_filter( 'woocommerce_get_sections_shipping', array( $this, 'add_shipping_section' ) );
		add_filter( 'woocommerce_get_settings_shipping', array( $this, 'shipping_settings' ), 10, 2 );
	}

	/**
	 * Filter shipping methods.
	 *
	 * @param array $rates Shipping rates.
	 * @param array $package Shipping package.
	 * @return array
	 */
	public function filter_shipping_methods( $rates, $package ) {
		// Get customer country.
		$customer_country = $package['destination']['country'];
		
		// Get base country.
		$base_country = WC()->countries->get_base_country();
		
		// Check if international shipping.
		if ( $customer_country !== $base_country ) {
			// Get international shipping methods.
			$international_methods = get_theme_mod( 'aqualuxe_international_shipping_methods', array() );
			
			if ( ! empty( $international_methods ) ) {
				// Filter rates.
				foreach ( $rates as $rate_id => $rate ) {
					// Check if method is allowed.
					if ( ! in_array( $rate->method_id, $international_methods, true ) ) {
						unset( $rates[ $rate_id ] );
					}
				}
			}
		}
		
		return $rates;
	}

	/**
	 * Add shipping methods.
	 *
	 * @param array $methods Shipping methods.
	 * @return array
	 */
	public function add_shipping_methods( $methods ) {
		// Check if custom international shipping method is enabled.
		if ( get_theme_mod( 'aqualuxe_international_shipping_custom_method', false ) ) {
			// Add custom international shipping method.
			$methods['aqualuxe_international_shipping'] = 'AquaLuxe\\WooCommerce\\Shipping\\International_Shipping_Method';
			
			// Include custom shipping method class.
			include_once get_template_directory() . '/inc/woocommerce/shipping/class-international-shipping-method.php';
		}
		
		return $methods;
	}

	/**
	 * Checkout fields.
	 *
	 * @param array $fields Checkout fields.
	 * @return array
	 */
	public function checkout_fields( $fields ) {
		// Check if international shipping fields are enabled.
		if ( get_theme_mod( 'aqualuxe_international_shipping_fields', false ) ) {
			// Add customs declaration field.
			$fields['shipping']['shipping_customs_declaration'] = array(
				'type'        => 'select',
				'label'       => esc_html__( 'Customs Declaration', 'aqualuxe' ),
				'placeholder' => esc_html__( 'Select customs declaration', 'aqualuxe' ),
				'required'    => false,
				'class'       => array( 'form-row-wide' ),
				'clear'       => true,
				'options'     => array(
					''        => esc_html__( 'Select customs declaration', 'aqualuxe' ),
					'gift'    => esc_html__( 'Gift', 'aqualuxe' ),
					'sample'  => esc_html__( 'Commercial Sample', 'aqualuxe' ),
					'return'  => esc_html__( 'Returned Goods', 'aqualuxe' ),
					'other'   => esc_html__( 'Other', 'aqualuxe' ),
				),
				'priority'    => 100,
			);
			
			// Add customs value field.
			$fields['shipping']['shipping_customs_value'] = array(
				'type'        => 'text',
				'label'       => esc_html__( 'Customs Value', 'aqualuxe' ),
				'placeholder' => esc_html__( 'Enter customs value', 'aqualuxe' ),
				'required'    => false,
				'class'       => array( 'form-row-wide' ),
				'clear'       => true,
				'priority'    => 110,
			);
			
			// Add customs description field.
			$fields['shipping']['shipping_customs_description'] = array(
				'type'        => 'textarea',
				'label'       => esc_html__( 'Customs Description', 'aqualuxe' ),
				'placeholder' => esc_html__( 'Enter customs description', 'aqualuxe' ),
				'required'    => false,
				'class'       => array( 'form-row-wide' ),
				'clear'       => true,
				'priority'    => 120,
			);
		}
		
		return $fields;
	}

	/**
	 * Allowed countries.
	 *
	 * @param array $countries Countries.
	 * @return array
	 */
	public function allowed_countries( $countries ) {
		// Get allowed countries.
		$allowed_countries = get_theme_mod( 'aqualuxe_international_shipping_countries', array() );
		
		if ( ! empty( $allowed_countries ) ) {
			// Filter countries.
			$filtered_countries = array();
			
			foreach ( $allowed_countries as $country_code ) {
				if ( isset( $countries[ $country_code ] ) ) {
					$filtered_countries[ $country_code ] = $countries[ $country_code ];
				}
			}
			
			return $filtered_countries;
		}
		
		return $countries;
	}

	/**
	 * Shipping countries.
	 *
	 * @param array $countries Countries.
	 * @return array
	 */
	public function shipping_countries( $countries ) {
		// Get allowed countries.
		$allowed_countries = get_theme_mod( 'aqualuxe_international_shipping_countries', array() );
		
		if ( ! empty( $allowed_countries ) ) {
			// Filter countries.
			$filtered_countries = array();
			
			foreach ( $allowed_countries as $country_code ) {
				if ( isset( $countries[ $country_code ] ) ) {
					$filtered_countries[ $country_code ] = $countries[ $country_code ];
				}
			}
			
			return $filtered_countries;
		}
		
		return $countries;
	}

	/**
	 * Shipping country states.
	 *
	 * @param array $states States.
	 * @return array
	 */
	public function shipping_country_states( $states ) {
		// Get allowed countries.
		$allowed_countries = get_theme_mod( 'aqualuxe_international_shipping_countries', array() );
		
		if ( ! empty( $allowed_countries ) ) {
			// Filter states.
			$filtered_states = array();
			
			foreach ( $allowed_countries as $country_code ) {
				if ( isset( $states[ $country_code ] ) ) {
					$filtered_states[ $country_code ] = $states[ $country_code ];
				}
			}
			
			return $filtered_states;
		}
		
		return $states;
	}

	/**
	 * Add international fees.
	 *
	 * @param WC_Cart $cart Cart object.
	 * @return void
	 */
	public function add_international_fees( $cart ) {
		// Check if cart is available.
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		
		// Get customer country.
		$customer_country = WC()->customer->get_shipping_country();
		
		// Get base country.
		$base_country = WC()->countries->get_base_country();
		
		// Check if international shipping.
		if ( $customer_country !== $base_country ) {
			// Get international fees.
			$international_fees = get_theme_mod( 'aqualuxe_international_shipping_fees', array() );
			
			if ( ! empty( $international_fees ) ) {
				// Add fees.
				foreach ( $international_fees as $fee ) {
					// Check if fee applies to country.
					if ( empty( $fee['countries'] ) || in_array( $customer_country, $fee['countries'], true ) ) {
						// Add fee.
						$cart->add_fee( $fee['name'], $fee['amount'], $fee['taxable'], $fee['tax_class'] );
					}
				}
			}
		}
	}

	/**
	 * Shipping notice.
	 *
	 * @return void
	 */
	public function shipping_notice() {
		// Get customer country.
		$customer_country = WC()->customer->get_shipping_country();
		
		// Get base country.
		$base_country = WC()->countries->get_base_country();
		
		// Check if international shipping.
		if ( $customer_country !== $base_country ) {
			// Get international shipping notice.
			$notice = get_theme_mod( 'aqualuxe_international_shipping_notice', '' );
			
			if ( ! empty( $notice ) ) {
				// Output notice.
				echo '<div class="international-shipping-notice">';
				echo wp_kses_post( wpautop( $notice ) );
				echo '</div>';
			}
		}
	}

	/**
	 * Update order meta.
	 *
	 * @param WC_Order $order Order object.
	 * @param array    $data  Order data.
	 * @return void
	 */
	public function update_order_meta( $order, $data ) {
		// Check if international shipping fields are enabled.
		if ( get_theme_mod( 'aqualuxe_international_shipping_fields', false ) ) {
			// Save customs declaration.
			if ( isset( $data['shipping_customs_declaration'] ) ) {
				$order->update_meta_data( '_shipping_customs_declaration', sanitize_text_field( $data['shipping_customs_declaration'] ) );
			}
			
			// Save customs value.
			if ( isset( $data['shipping_customs_value'] ) ) {
				$order->update_meta_data( '_shipping_customs_value', sanitize_text_field( $data['shipping_customs_value'] ) );
			}
			
			// Save customs description.
			if ( isset( $data['shipping_customs_description'] ) ) {
				$order->update_meta_data( '_shipping_customs_description', sanitize_textarea_field( $data['shipping_customs_description'] ) );
			}
		}
	}

	/**
	 * Display admin order meta.
	 *
	 * @param WC_Order $order Order object.
	 * @return void
	 */
	public function display_admin_order_meta( $order ) {
		// Check if international shipping fields are enabled.
		if ( get_theme_mod( 'aqualuxe_international_shipping_fields', false ) ) {
			// Get customs declaration.
			$customs_declaration = $order->get_meta( '_shipping_customs_declaration' );
			
			// Get customs value.
			$customs_value = $order->get_meta( '_shipping_customs_value' );
			
			// Get customs description.
			$customs_description = $order->get_meta( '_shipping_customs_description' );
			
			// Output customs information.
			if ( ! empty( $customs_declaration ) || ! empty( $customs_value ) || ! empty( $customs_description ) ) {
				echo '<h3>' . esc_html__( 'Customs Information', 'aqualuxe' ) . '</h3>';
				echo '<p>';
				
				if ( ! empty( $customs_declaration ) ) {
					echo '<strong>' . esc_html__( 'Customs Declaration:', 'aqualuxe' ) . '</strong> ' . esc_html( $customs_declaration ) . '<br>';
				}
				
				if ( ! empty( $customs_value ) ) {
					echo '<strong>' . esc_html__( 'Customs Value:', 'aqualuxe' ) . '</strong> ' . esc_html( $customs_value ) . '<br>';
				}
				
				if ( ! empty( $customs_description ) ) {
					echo '<strong>' . esc_html__( 'Customs Description:', 'aqualuxe' ) . '</strong> ' . esc_html( $customs_description ) . '<br>';
				}
				
				echo '</p>';
			}
		}
	}

	/**
	 * Email shipping details.
	 *
	 * @param WC_Order $order Order object.
	 * @param bool     $sent_to_admin Whether the email is sent to admin.
	 * @param bool     $plain_text Whether the email is plain text.
	 * @param WC_Email $email Email object.
	 * @return void
	 */
	public function email_shipping_details( $order, $sent_to_admin, $plain_text, $email ) {
		// Check if international shipping fields are enabled.
		if ( get_theme_mod( 'aqualuxe_international_shipping_fields', false ) ) {
			// Get customs declaration.
			$customs_declaration = $order->get_meta( '_shipping_customs_declaration' );
			
			// Get customs value.
			$customs_value = $order->get_meta( '_shipping_customs_value' );
			
			// Get customs description.
			$customs_description = $order->get_meta( '_shipping_customs_description' );
			
			// Output customs information.
			if ( ! empty( $customs_declaration ) || ! empty( $customs_value ) || ! empty( $customs_description ) ) {
				if ( $plain_text ) {
					echo "\n\n" . esc_html__( 'Customs Information', 'aqualuxe' ) . "\n";
					
					if ( ! empty( $customs_declaration ) ) {
						echo esc_html__( 'Customs Declaration:', 'aqualuxe' ) . ' ' . esc_html( $customs_declaration ) . "\n";
					}
					
					if ( ! empty( $customs_value ) ) {
						echo esc_html__( 'Customs Value:', 'aqualuxe' ) . ' ' . esc_html( $customs_value ) . "\n";
					}
					
					if ( ! empty( $customs_description ) ) {
						echo esc_html__( 'Customs Description:', 'aqualuxe' ) . ' ' . esc_html( $customs_description ) . "\n";
					}
				} else {
					echo '<h2>' . esc_html__( 'Customs Information', 'aqualuxe' ) . '</h2>';
					echo '<p>';
					
					if ( ! empty( $customs_declaration ) ) {
						echo '<strong>' . esc_html__( 'Customs Declaration:', 'aqualuxe' ) . '</strong> ' . esc_html( $customs_declaration ) . '<br>';
					}
					
					if ( ! empty( $customs_value ) ) {
						echo '<strong>' . esc_html__( 'Customs Value:', 'aqualuxe' ) . '</strong> ' . esc_html( $customs_value ) . '<br>';
					}
					
					if ( ! empty( $customs_description ) ) {
						echo '<strong>' . esc_html__( 'Customs Description:', 'aqualuxe' ) . '</strong> ' . esc_html( $customs_description ) . '<br>';
					}
					
					echo '</p>';
				}
			}
		}
	}

	/**
	 * Orders columns.
	 *
	 * @param array $columns Columns.
	 * @return array
	 */
	public function orders_columns( $columns ) {
		// Check if shipping country column is enabled.
		if ( get_theme_mod( 'aqualuxe_international_shipping_orders_column', false ) ) {
			// Add shipping country column.
			$new_columns = array();
			
			foreach ( $columns as $key => $column ) {
				$new_columns[ $key ] = $column;
				
				if ( 'order-status' === $key ) {
					$new_columns['shipping-country'] = esc_html__( 'Shipping Country', 'aqualuxe' );
				}
			}
			
			return $new_columns;
		}
		
		return $columns;
	}

	/**
	 * Orders shipping country column.
	 *
	 * @param WC_Order $order Order object.
	 * @return void
	 */
	public function orders_shipping_country_column( $order ) {
		// Get shipping country.
		$shipping_country = $order->get_shipping_country();
		
		// Get country name.
		$countries = WC()->countries->get_countries();
		$country_name = isset( $countries[ $shipping_country ] ) ? $countries[ $shipping_country ] : $shipping_country;
		
		// Output country name.
		echo esc_html( $country_name );
	}

	/**
	 * Product shipping options.
	 *
	 * @return void
	 */
	public function product_shipping_options() {
		// Check if product shipping options are enabled.
		if ( get_theme_mod( 'aqualuxe_international_shipping_product_options', false ) ) {
			// Add international shipping options.
			woocommerce_wp_checkbox(
				array(
					'id'          => '_international_shipping',
					'label'       => esc_html__( 'International Shipping', 'aqualuxe' ),
					'description' => esc_html__( 'Enable international shipping for this product.', 'aqualuxe' ),
				)
			);
			
			// Add restricted countries.
			woocommerce_wp_select(
				array(
					'id'          => '_restricted_countries',
					'label'       => esc_html__( 'Restricted Countries', 'aqualuxe' ),
					'description' => esc_html__( 'Select countries where this product cannot be shipped.', 'aqualuxe' ),
					'options'     => WC()->countries->get_countries(),
					'custom_attributes' => array(
						'multiple' => 'multiple',
					),
					'class'       => 'wc-enhanced-select',
				)
			);
			
			// Add customs information.
			woocommerce_wp_text_input(
				array(
					'id'          => '_customs_hs_code',
					'label'       => esc_html__( 'HS Tariff Code', 'aqualuxe' ),
					'description' => esc_html__( 'Harmonized System tariff code for customs.', 'aqualuxe' ),
				)
			);
			
			woocommerce_wp_select(
				array(
					'id'          => '_customs_origin_country',
					'label'       => esc_html__( 'Country of Origin', 'aqualuxe' ),
					'description' => esc_html__( 'Country where the product was manufactured.', 'aqualuxe' ),
					'options'     => WC()->countries->get_countries(),
					'class'       => 'wc-enhanced-select',
				)
			);
		}
	}

	/**
	 * Save product shipping options.
	 *
	 * @param int $post_id Product ID.
	 * @return void
	 */
	public function save_product_shipping_options( $post_id ) {
		// Check if product shipping options are enabled.
		if ( get_theme_mod( 'aqualuxe_international_shipping_product_options', false ) ) {
			// Save international shipping.
			$international_shipping = isset( $_POST['_international_shipping'] ) ? 'yes' : 'no';
			update_post_meta( $post_id, '_international_shipping', $international_shipping );
			
			// Save restricted countries.
			$restricted_countries = isset( $_POST['_restricted_countries'] ) ? wc_clean( $_POST['_restricted_countries'] ) : array();
			update_post_meta( $post_id, '_restricted_countries', $restricted_countries );
			
			// Save customs HS code.
			$customs_hs_code = isset( $_POST['_customs_hs_code'] ) ? wc_clean( $_POST['_customs_hs_code'] ) : '';
			update_post_meta( $post_id, '_customs_hs_code', $customs_hs_code );
			
			// Save customs origin country.
			$customs_origin_country = isset( $_POST['_customs_origin_country'] ) ? wc_clean( $_POST['_customs_origin_country'] ) : '';
			update_post_meta( $post_id, '_customs_origin_country', $customs_origin_country );
		}
	}

	/**
	 * Add shipping section.
	 *
	 * @param array $sections Sections.
	 * @return array
	 */
	public function add_shipping_section( $sections ) {
		// Add international shipping section.
		$sections['international_shipping'] = esc_html__( 'International Shipping', 'aqualuxe' );
		
		return $sections;
	}

	/**
	 * Shipping settings.
	 *
	 * @param array  $settings Settings.
	 * @param string $current_section Current section.
	 * @return array
	 */
	public function shipping_settings( $settings, $current_section ) {
		// Check if international shipping section.
		if ( 'international_shipping' === $current_section ) {
			// Create settings.
			$settings = array(
				array(
					'title' => esc_html__( 'International Shipping Settings', 'aqualuxe' ),
					'type'  => 'title',
					'id'    => 'international_shipping_settings',
				),
				array(
					'title'   => esc_html__( 'Enable International Shipping', 'aqualuxe' ),
					'desc'    => esc_html__( 'Enable international shipping for your store.', 'aqualuxe' ),
					'id'      => 'aqualuxe_enable_international_shipping',
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'title'   => esc_html__( 'Allowed Countries', 'aqualuxe' ),
					'desc'    => esc_html__( 'Select countries where you want to ship products.', 'aqualuxe' ),
					'id'      => 'aqualuxe_international_shipping_countries',
					'default' => array(),
					'type'    => 'multi_select_countries',
				),
				array(
					'title'   => esc_html__( 'Shipping Methods', 'aqualuxe' ),
					'desc'    => esc_html__( 'Select shipping methods available for international shipping.', 'aqualuxe' ),
					'id'      => 'aqualuxe_international_shipping_methods',
					'default' => array(),
					'type'    => 'multiselect',
					'options' => $this->get_shipping_methods(),
					'class'   => 'wc-enhanced-select',
				),
				array(
					'title'   => esc_html__( 'Custom Shipping Method', 'aqualuxe' ),
					'desc'    => esc_html__( 'Enable custom international shipping method.', 'aqualuxe' ),
					'id'      => 'aqualuxe_international_shipping_custom_method',
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'title'   => esc_html__( 'International Shipping Notice', 'aqualuxe' ),
					'desc'    => esc_html__( 'Add a notice for international shipping on the checkout page.', 'aqualuxe' ),
					'id'      => 'aqualuxe_international_shipping_notice',
					'default' => '',
					'type'    => 'textarea',
					'css'     => 'min-width: 400px; min-height: 100px;',
				),
				array(
					'title'   => esc_html__( 'Custom Fields', 'aqualuxe' ),
					'desc'    => esc_html__( 'Enable custom fields for international shipping.', 'aqualuxe' ),
					'id'      => 'aqualuxe_international_shipping_fields',
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'title'   => esc_html__( 'Product Options', 'aqualuxe' ),
					'desc'    => esc_html__( 'Enable international shipping options for products.', 'aqualuxe' ),
					'id'      => 'aqualuxe_international_shipping_product_options',
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'title'   => esc_html__( 'Orders Column', 'aqualuxe' ),
					'desc'    => esc_html__( 'Add shipping country column to orders table.', 'aqualuxe' ),
					'id'      => 'aqualuxe_international_shipping_orders_column',
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'international_shipping_settings',
				),
			);
		}
		
		return $settings;
	}

	/**
	 * Get shipping methods.
	 *
	 * @return array
	 */
	private function get_shipping_methods() {
		// Get shipping methods.
		$shipping_methods = WC()->shipping()->get_shipping_methods();
		
		// Format methods.
		$methods = array();
		
		foreach ( $shipping_methods as $method ) {
			$methods[ $method->id ] = $method->method_title;
		}
		
		return $methods;
	}
}