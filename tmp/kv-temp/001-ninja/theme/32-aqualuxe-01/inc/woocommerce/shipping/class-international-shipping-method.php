<?php
/**
 * AquaLuxe International Shipping Method
 *
 * @package AquaLuxe
 * @subpackage WooCommerce
 * @since 1.1.0
 */

namespace AquaLuxe\WooCommerce\Shipping;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * International Shipping Method Class
 *
 * Provides a custom shipping method for international shipping.
 *
 * @since 1.1.0
 */
class International_Shipping_Method extends \WC_Shipping_Method {

	/**
	 * Constructor.
	 *
	 * @param int $instance_id Instance ID.
	 */
	public function __construct( $instance_id = 0 ) {
		$this->id                 = 'aqualuxe_international_shipping';
		$this->instance_id        = absint( $instance_id );
		$this->method_title       = esc_html__( 'AquaLuxe International Shipping', 'aqualuxe' );
		$this->method_description = esc_html__( 'Custom international shipping method for AquaLuxe theme.', 'aqualuxe' );
		$this->supports           = array(
			'shipping-zones',
			'instance-settings',
			'instance-settings-modal',
		);
		
		$this->init();
	}

	/**
	 * Initialize settings.
	 *
	 * @return void
	 */
	public function init() {
		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();
		
		// Define user set variables.
		$this->title            = $this->get_option( 'title' );
		$this->tax_status       = $this->get_option( 'tax_status' );
		$this->cost             = $this->get_option( 'cost' );
		$this->type             = $this->get_option( 'type', 'class' );
		$this->min_amount       = $this->get_option( 'min_amount', 0 );
		$this->requires         = $this->get_option( 'requires' );
		$this->handling_fee     = $this->get_option( 'handling_fee' );
		$this->weight_based     = $this->get_option( 'weight_based' );
		$this->weight_unit_cost = $this->get_option( 'weight_unit_cost' );
		
		// Save settings in admin.
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * Initialize form fields.
	 *
	 * @return void
	 */
	public function init_form_fields() {
		$this->instance_form_fields = array(
			'title'            => array(
				'title'       => esc_html__( 'Method Title', 'aqualuxe' ),
				'type'        => 'text',
				'description' => esc_html__( 'This controls the title which the user sees during checkout.', 'aqualuxe' ),
				'default'     => esc_html__( 'International Shipping', 'aqualuxe' ),
				'desc_tip'    => true,
			),
			'tax_status'       => array(
				'title'       => esc_html__( 'Tax Status', 'aqualuxe' ),
				'type'        => 'select',
				'description' => esc_html__( 'Set whether or not the shipping cost is taxable.', 'aqualuxe' ),
				'default'     => 'taxable',
				'options'     => array(
					'taxable' => esc_html__( 'Taxable', 'aqualuxe' ),
					'none'    => esc_html__( 'None', 'aqualuxe' ),
				),
				'desc_tip'    => true,
			),
			'cost'             => array(
				'title'       => esc_html__( 'Cost', 'aqualuxe' ),
				'type'        => 'text',
				'description' => esc_html__( 'Enter a cost (excl. tax) or sum, e.g. 10.00 * [qty].', 'aqualuxe' ),
				'default'     => '0',
				'desc_tip'    => true,
				'placeholder' => wc_format_localized_price( 0 ),
			),
			'type'             => array(
				'title'       => esc_html__( 'Calculation Type', 'aqualuxe' ),
				'type'        => 'select',
				'description' => esc_html__( 'Select how to calculate shipping cost.', 'aqualuxe' ),
				'default'     => 'class',
				'options'     => array(
					'class' => esc_html__( 'Per class: Charge shipping for each shipping class individually', 'aqualuxe' ),
					'order' => esc_html__( 'Per order: Charge shipping for the most expensive shipping class', 'aqualuxe' ),
				),
				'desc_tip'    => true,
			),
			'handling_fee'     => array(
				'title'       => esc_html__( 'Handling Fee', 'aqualuxe' ),
				'type'        => 'text',
				'description' => esc_html__( 'Enter a handling fee (excl. tax).', 'aqualuxe' ),
				'default'     => '0',
				'desc_tip'    => true,
				'placeholder' => wc_format_localized_price( 0 ),
			),
			'requires'         => array(
				'title'       => esc_html__( 'Requires', 'aqualuxe' ),
				'type'        => 'select',
				'description' => esc_html__( 'Select when this shipping method should be available.', 'aqualuxe' ),
				'default'     => '',
				'options'     => array(
					''           => esc_html__( 'N/A', 'aqualuxe' ),
					'coupon'     => esc_html__( 'A valid free shipping coupon', 'aqualuxe' ),
					'min_amount' => esc_html__( 'A minimum order amount', 'aqualuxe' ),
					'either'     => esc_html__( 'A minimum order amount OR a coupon', 'aqualuxe' ),
					'both'       => esc_html__( 'A minimum order amount AND a coupon', 'aqualuxe' ),
				),
				'desc_tip'    => true,
			),
			'min_amount'       => array(
				'title'       => esc_html__( 'Minimum Order Amount', 'aqualuxe' ),
				'type'        => 'price',
				'description' => esc_html__( 'Minimum order amount for this shipping method to be available.', 'aqualuxe' ),
				'default'     => '0',
				'desc_tip'    => true,
				'placeholder' => wc_format_localized_price( 0 ),
			),
			'weight_based'     => array(
				'title'       => esc_html__( 'Weight Based Pricing', 'aqualuxe' ),
				'type'        => 'checkbox',
				'description' => esc_html__( 'Enable weight based pricing.', 'aqualuxe' ),
				'default'     => 'no',
				'desc_tip'    => true,
			),
			'weight_unit_cost' => array(
				'title'       => esc_html__( 'Cost per Weight Unit', 'aqualuxe' ),
				'type'        => 'text',
				'description' => esc_html__( 'Enter a cost per weight unit (excl. tax).', 'aqualuxe' ),
				'default'     => '0',
				'desc_tip'    => true,
				'placeholder' => wc_format_localized_price( 0 ),
			),
		);
	}

	/**
	 * Calculate shipping.
	 *
	 * @param array $package Package data.
	 * @return void
	 */
	public function calculate_shipping( $package = array() ) {
		// Check if valid for shipping.
		if ( ! $this->is_available( $package ) ) {
			return;
		}
		
		// Calculate shipping cost.
		$cost = $this->calculate_shipping_cost( $package );
		
		// Register the rate.
		$this->add_rate(
			array(
				'id'        => $this->get_rate_id(),
				'label'     => $this->title,
				'cost'      => $cost,
				'package'   => $package,
				'meta_data' => array(
					'international' => true,
				),
			)
		);
	}

	/**
	 * Calculate shipping cost.
	 *
	 * @param array $package Package data.
	 * @return float
	 */
	private function calculate_shipping_cost( $package ) {
		// Get base cost.
		$cost = $this->get_option( 'cost' );
		
		// Check if weight based pricing is enabled.
		if ( 'yes' === $this->get_option( 'weight_based' ) ) {
			// Get weight unit cost.
			$weight_unit_cost = $this->get_option( 'weight_unit_cost' );
			
			// Get package weight.
			$weight = 0;
			
			foreach ( $package['contents'] as $item_id => $values ) {
				if ( $values['data']->needs_shipping() ) {
					$weight += $values['data']->get_weight() * $values['quantity'];
				}
			}
			
			// Calculate weight based cost.
			$weight_cost = $weight * $weight_unit_cost;
			
			// Add weight based cost to base cost.
			$cost += $weight_cost;
		}
		
		// Check calculation type.
		if ( 'class' === $this->get_option( 'type' ) ) {
			// Calculate cost per shipping class.
			$shipping_classes = WC()->shipping->get_shipping_classes();
			
			if ( ! empty( $shipping_classes ) ) {
				$found_shipping_classes = $this->find_shipping_classes( $package );
				$highest_class_cost     = 0;
				
				foreach ( $found_shipping_classes as $shipping_class => $products ) {
					// Get shipping class cost.
					$shipping_class_cost = $this->get_option( 'class_cost_' . $shipping_class, 0 );
					
					// Calculate shipping class cost.
					if ( $shipping_class_cost > $highest_class_cost ) {
						$highest_class_cost = $shipping_class_cost;
					}
				}
				
				// Add shipping class cost to base cost.
				$cost += $highest_class_cost;
			}
		}
		
		// Add handling fee.
		$cost += $this->get_option( 'handling_fee' );
		
		// Apply filters.
		$cost = apply_filters( 'aqualuxe_international_shipping_cost', $cost, $package, $this );
		
		return $cost;
	}

	/**
	 * Find shipping classes for products in the package.
	 *
	 * @param array $package Package data.
	 * @return array
	 */
	private function find_shipping_classes( $package ) {
		$found_shipping_classes = array();
		
		foreach ( $package['contents'] as $item_id => $values ) {
			if ( $values['data']->needs_shipping() ) {
				$shipping_class = $values['data']->get_shipping_class();
				
				if ( ! isset( $found_shipping_classes[ $shipping_class ] ) ) {
					$found_shipping_classes[ $shipping_class ] = array();
				}
				
				$found_shipping_classes[ $shipping_class ][ $item_id ] = $values;
			}
		}
		
		return $found_shipping_classes;
	}

	/**
	 * Check if shipping method is available.
	 *
	 * @param array $package Package data.
	 * @return bool
	 */
	public function is_available( $package ) {
		// Check if shipping is needed.
		if ( empty( $package['contents'] ) ) {
			return false;
		}
		
		// Check if international shipping is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_international_shipping', false ) ) {
			return false;
		}
		
		// Get customer country.
		$customer_country = $package['destination']['country'];
		
		// Get base country.
		$base_country = WC()->countries->get_base_country();
		
		// Check if international shipping.
		if ( $customer_country === $base_country ) {
			return false;
		}
		
		// Get allowed countries.
		$allowed_countries = get_theme_mod( 'aqualuxe_international_shipping_countries', array() );
		
		// Check if country is allowed.
		if ( ! empty( $allowed_countries ) && ! in_array( $customer_country, $allowed_countries, true ) ) {
			return false;
		}
		
		// Check if products are available for international shipping.
		foreach ( $package['contents'] as $item_id => $values ) {
			$product = $values['data'];
			
			// Check if product needs shipping.
			if ( ! $product->needs_shipping() ) {
				continue;
			}
			
			// Check if product is available for international shipping.
			$international_shipping = get_post_meta( $product->get_id(), '_international_shipping', true );
			
			if ( 'no' === $international_shipping ) {
				return false;
			}
			
			// Check if product is restricted for this country.
			$restricted_countries = get_post_meta( $product->get_id(), '_restricted_countries', true );
			
			if ( ! empty( $restricted_countries ) && in_array( $customer_country, $restricted_countries, true ) ) {
				return false;
			}
		}
		
		// Check minimum order amount.
		if ( in_array( $this->requires, array( 'min_amount', 'either', 'both' ), true ) ) {
			$min_amount = $this->min_amount;
			
			if ( $min_amount && WC()->cart->get_displayed_subtotal() < $min_amount ) {
				// Check if coupon is required.
				if ( in_array( $this->requires, array( 'either', 'both' ), true ) ) {
					// Check if coupon is applied.
					if ( 'both' === $this->requires || ! $this->has_coupon() ) {
						return false;
					}
				} else {
					return false;
				}
			}
		}
		
		// Check if coupon is required.
		if ( in_array( $this->requires, array( 'coupon', 'either', 'both' ), true ) ) {
			// Check if coupon is applied.
			if ( 'both' === $this->requires ) {
				if ( ! $this->has_coupon() || WC()->cart->get_displayed_subtotal() < $this->min_amount ) {
					return false;
				}
			} elseif ( 'either' === $this->requires ) {
				if ( ! $this->has_coupon() && WC()->cart->get_displayed_subtotal() < $this->min_amount ) {
					return false;
				}
			} elseif ( ! $this->has_coupon() ) {
				return false;
			}
		}
		
		return apply_filters( 'aqualuxe_international_shipping_is_available', true, $package, $this );
	}

	/**
	 * Check if a free shipping coupon is applied.
	 *
	 * @return bool
	 */
	private function has_coupon() {
		$has_coupon = false;
		
		if ( ! WC()->cart->is_empty() ) {
			$coupons = WC()->cart->get_coupons();
			
			if ( $coupons ) {
				foreach ( $coupons as $code => $coupon ) {
					if ( $coupon->is_valid() && $coupon->get_free_shipping() ) {
						$has_coupon = true;
						break;
					}
				}
			}
		}
		
		return $has_coupon;
	}
}