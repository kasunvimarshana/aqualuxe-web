<?php
/**
 * AquaLuxe WooCommerce Checkout Class
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
 * WooCommerce Checkout Class
 *
 * Handles WooCommerce checkout customizations and enhancements.
 *
 * @since 1.1.0
 */
class Checkout {

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

		// Checkout fields.
		add_filter( 'woocommerce_checkout_fields', array( $this, 'checkout_fields' ) );
		add_filter( 'woocommerce_default_address_fields', array( $this, 'default_address_fields' ) );
		add_filter( 'woocommerce_billing_fields', array( $this, 'billing_fields' ) );
		add_filter( 'woocommerce_shipping_fields', array( $this, 'shipping_fields' ) );
		add_filter( 'woocommerce_checkout_get_value', array( $this, 'checkout_get_value' ), 10, 2 );
		
		// Checkout layout.
		add_action( 'woocommerce_before_checkout_form', array( $this, 'checkout_wrapper_open' ), 5 );
		add_action( 'woocommerce_after_checkout_form', array( $this, 'checkout_wrapper_close' ), 15 );
		add_action( 'woocommerce_checkout_before_customer_details', array( $this, 'customer_details_wrapper_open' ), 5 );
		add_action( 'woocommerce_checkout_after_customer_details', array( $this, 'customer_details_wrapper_close' ), 15 );
		add_action( 'woocommerce_checkout_before_order_review_heading', array( $this, 'order_review_wrapper_open' ), 5 );
		add_action( 'woocommerce_checkout_after_order_review', array( $this, 'order_review_wrapper_close' ), 15 );
		
		// Checkout steps.
		add_action( 'woocommerce_before_checkout_form', array( $this, 'checkout_steps' ), 10 );
		
		// Checkout notices.
		add_action( 'woocommerce_before_checkout_form', array( $this, 'checkout_notices' ), 15 );
		
		// Checkout coupon.
		add_action( 'woocommerce_before_checkout_form', array( $this, 'checkout_coupon_form' ), 20 );
		
		// Checkout login.
		add_action( 'woocommerce_before_checkout_form', array( $this, 'checkout_login_form' ), 25 );
		
		// Checkout order summary.
		add_action( 'woocommerce_checkout_before_order_review_heading', array( $this, 'order_summary' ), 10 );
		
		// Checkout payment methods.
		add_filter( 'woocommerce_gateway_icon', array( $this, 'gateway_icon' ), 10, 2 );
		add_filter( 'woocommerce_gateway_title', array( $this, 'gateway_title' ), 10, 2 );
		add_filter( 'woocommerce_gateway_description', array( $this, 'gateway_description' ), 10, 2 );
		
		// Checkout validation.
		add_action( 'woocommerce_after_checkout_validation', array( $this, 'checkout_validation' ), 10, 2 );
		
		// Checkout processing.
		add_action( 'woocommerce_checkout_process', array( $this, 'checkout_process' ) );
		
		// Checkout order processed.
		add_action( 'woocommerce_checkout_order_processed', array( $this, 'checkout_order_processed' ), 10, 3 );
		
		// Checkout thank you.
		add_action( 'woocommerce_thankyou', array( $this, 'thankyou' ), 10 );
		add_action( 'woocommerce_before_thankyou', array( $this, 'before_thankyou' ), 10 );
		add_action( 'woocommerce_after_thankyou', array( $this, 'after_thankyou' ), 10 );
	}

	/**
	 * Checkout fields.
	 *
	 * @param array $fields Checkout fields.
	 * @return array
	 */
	public function checkout_fields( $fields ) {
		// Add placeholder to fields.
		foreach ( $fields as $section => $section_fields ) {
			foreach ( $section_fields as $key => $field ) {
				if ( ! isset( $field['placeholder'] ) && isset( $field['label'] ) ) {
					$fields[ $section ][ $key ]['placeholder'] = $field['label'];
				}
			}
		}
		
		// Make company field optional.
		if ( isset( $fields['billing']['billing_company'] ) ) {
			$fields['billing']['billing_company']['required'] = false;
		}
		
		if ( isset( $fields['shipping']['shipping_company'] ) ) {
			$fields['shipping']['shipping_company']['required'] = false;
		}
		
		// Add custom fields.
		if ( get_theme_mod( 'aqualuxe_checkout_order_notes', true ) ) {
			$fields['order']['order_comments']['placeholder'] = esc_html__( 'Notes about your order, e.g. special notes for delivery.', 'aqualuxe' );
		} else {
			unset( $fields['order']['order_comments'] );
		}
		
		// Add delivery date field.
		if ( get_theme_mod( 'aqualuxe_checkout_delivery_date', false ) ) {
			$fields['billing']['billing_delivery_date'] = array(
				'type'        => 'date',
				'label'       => esc_html__( 'Delivery Date', 'aqualuxe' ),
				'placeholder' => esc_html__( 'Select a delivery date', 'aqualuxe' ),
				'required'    => get_theme_mod( 'aqualuxe_checkout_delivery_date_required', false ),
				'class'       => array( 'form-row-wide' ),
				'clear'       => true,
				'priority'    => 100,
			);
		}
		
		// Add delivery time field.
		if ( get_theme_mod( 'aqualuxe_checkout_delivery_time', false ) ) {
			$fields['billing']['billing_delivery_time'] = array(
				'type'        => 'select',
				'label'       => esc_html__( 'Delivery Time', 'aqualuxe' ),
				'placeholder' => esc_html__( 'Select a delivery time', 'aqualuxe' ),
				'required'    => get_theme_mod( 'aqualuxe_checkout_delivery_time_required', false ),
				'class'       => array( 'form-row-wide' ),
				'clear'       => true,
				'options'     => array(
					''           => esc_html__( 'Select a delivery time', 'aqualuxe' ),
					'morning'    => esc_html__( 'Morning (8:00 AM - 12:00 PM)', 'aqualuxe' ),
					'afternoon'  => esc_html__( 'Afternoon (12:00 PM - 4:00 PM)', 'aqualuxe' ),
					'evening'    => esc_html__( 'Evening (4:00 PM - 8:00 PM)', 'aqualuxe' ),
				),
				'priority'    => 110,
			);
		}
		
		// Add gift message field.
		if ( get_theme_mod( 'aqualuxe_checkout_gift_message', false ) ) {
			$fields['order']['order_gift_message'] = array(
				'type'        => 'textarea',
				'label'       => esc_html__( 'Gift Message', 'aqualuxe' ),
				'placeholder' => esc_html__( 'Add a gift message', 'aqualuxe' ),
				'required'    => false,
				'class'       => array( 'form-row-wide' ),
				'clear'       => true,
				'priority'    => 120,
			);
		}
		
		return $fields;
	}

	/**
	 * Default address fields.
	 *
	 * @param array $fields Address fields.
	 * @return array
	 */
	public function default_address_fields( $fields ) {
		// Check if phone field should be required.
		if ( isset( $fields['phone'] ) && ! get_theme_mod( 'aqualuxe_checkout_phone_required', true ) ) {
			$fields['phone']['required'] = false;
		}
		
		// Check if company field should be shown.
		if ( isset( $fields['company'] ) && ! get_theme_mod( 'aqualuxe_checkout_company_field', true ) ) {
			unset( $fields['company'] );
		}
		
		// Check if address 2 field should be shown.
		if ( isset( $fields['address_2'] ) && ! get_theme_mod( 'aqualuxe_checkout_address_2_field', true ) ) {
			unset( $fields['address_2'] );
		}
		
		return $fields;
	}

	/**
	 * Billing fields.
	 *
	 * @param array $fields Billing fields.
	 * @return array
	 */
	public function billing_fields( $fields ) {
		// Check if phone field should be required.
		if ( isset( $fields['billing_phone'] ) && ! get_theme_mod( 'aqualuxe_checkout_phone_required', true ) ) {
			$fields['billing_phone']['required'] = false;
		}
		
		// Check if company field should be shown.
		if ( isset( $fields['billing_company'] ) && ! get_theme_mod( 'aqualuxe_checkout_company_field', true ) ) {
			unset( $fields['billing_company'] );
		}
		
		// Check if address 2 field should be shown.
		if ( isset( $fields['billing_address_2'] ) && ! get_theme_mod( 'aqualuxe_checkout_address_2_field', true ) ) {
			unset( $fields['billing_address_2'] );
		}
		
		return $fields;
	}

	/**
	 * Shipping fields.
	 *
	 * @param array $fields Shipping fields.
	 * @return array
	 */
	public function shipping_fields( $fields ) {
		// Check if company field should be shown.
		if ( isset( $fields['shipping_company'] ) && ! get_theme_mod( 'aqualuxe_checkout_company_field', true ) ) {
			unset( $fields['shipping_company'] );
		}
		
		// Check if address 2 field should be shown.
		if ( isset( $fields['shipping_address_2'] ) && ! get_theme_mod( 'aqualuxe_checkout_address_2_field', true ) ) {
			unset( $fields['shipping_address_2'] );
		}
		
		return $fields;
	}

	/**
	 * Checkout get value.
	 *
	 * @param mixed  $value Value.
	 * @param string $input Input.
	 * @return mixed
	 */
	public function checkout_get_value( $value, $input ) {
		// Check if delivery date field should be prefilled.
		if ( 'billing_delivery_date' === $input && get_theme_mod( 'aqualuxe_checkout_delivery_date_prefill', false ) ) {
			// Get minimum delivery days.
			$min_days = get_theme_mod( 'aqualuxe_checkout_delivery_date_min_days', 1 );
			
			// Get current date.
			$current_date = date( 'Y-m-d', strtotime( '+' . $min_days . ' days' ) );
			
			return $current_date;
		}
		
		return $value;
	}

	/**
	 * Checkout wrapper open.
	 *
	 * @return void
	 */
	public function checkout_wrapper_open() {
		// Get checkout layout.
		$checkout_layout = get_theme_mod( 'aqualuxe_checkout_layout', 'standard' );
		
		echo '<div class="checkout-wrapper layout-' . esc_attr( $checkout_layout ) . '">';
	}

	/**
	 * Checkout wrapper close.
	 *
	 * @return void
	 */
	public function checkout_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Customer details wrapper open.
	 *
	 * @return void
	 */
	public function customer_details_wrapper_open() {
		echo '<div class="customer-details-wrapper">';
	}

	/**
	 * Customer details wrapper close.
	 *
	 * @return void
	 */
	public function customer_details_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Order review wrapper open.
	 *
	 * @return void
	 */
	public function order_review_wrapper_open() {
		echo '<div class="order-review-wrapper">';
	}

	/**
	 * Order review wrapper close.
	 *
	 * @return void
	 */
	public function order_review_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Checkout steps.
	 *
	 * @return void
	 */
	public function checkout_steps() {
		// Check if checkout steps are enabled.
		if ( ! get_theme_mod( 'aqualuxe_checkout_steps', false ) ) {
			return;
		}
		
		// Get checkout steps.
		$steps = array(
			'cart'     => array(
				'title' => esc_html__( 'Cart', 'aqualuxe' ),
				'url'   => wc_get_cart_url(),
				'icon'  => 'shopping-cart',
			),
			'checkout' => array(
				'title' => esc_html__( 'Checkout', 'aqualuxe' ),
				'url'   => wc_get_checkout_url(),
				'icon'  => 'credit-card',
			),
			'order'    => array(
				'title' => esc_html__( 'Order Complete', 'aqualuxe' ),
				'url'   => '',
				'icon'  => 'check-circle',
			),
		);
		
		// Get current step.
		$current_step = 'checkout';
		
		// Output steps.
		echo '<div class="checkout-steps">';
		echo '<ul class="steps">';
		
		foreach ( $steps as $step => $data ) {
			$class = $step === $current_step ? 'active' : '';
			$class .= array_search( $step, array_keys( $steps ) ) < array_search( $current_step, array_keys( $steps ) ) ? ' completed' : '';
			
			echo '<li class="step ' . esc_attr( $class ) . '">';
			
			if ( ! empty( $data['url'] ) ) {
				echo '<a href="' . esc_url( $data['url'] ) . '">';
			} else {
				echo '<span>';
			}
			
			echo '<span class="step-icon"><i class="fas fa-' . esc_attr( $data['icon'] ) . '"></i></span>';
			echo '<span class="step-title">' . esc_html( $data['title'] ) . '</span>';
			
			if ( ! empty( $data['url'] ) ) {
				echo '</a>';
			} else {
				echo '</span>';
			}
			
			echo '</li>';
		}
		
		echo '</ul>';
		echo '</div>';
	}

	/**
	 * Checkout notices.
	 *
	 * @return void
	 */
	public function checkout_notices() {
		// Check if checkout notices are enabled.
		if ( ! get_theme_mod( 'aqualuxe_checkout_notices', true ) ) {
			return;
		}
		
		// Get notices.
		$notices = array();
		
		// Add secure checkout notice.
		if ( get_theme_mod( 'aqualuxe_checkout_secure_notice', true ) ) {
			$notices[] = array(
				'type'    => 'info',
				'message' => esc_html__( 'Your payment information is processed securely.', 'aqualuxe' ),
				'icon'    => 'lock',
			);
		}
		
		// Add free shipping notice.
		if ( get_theme_mod( 'aqualuxe_checkout_free_shipping_notice', false ) ) {
			// Get free shipping threshold.
			$threshold = get_theme_mod( 'aqualuxe_checkout_free_shipping_threshold', 100 );
			
			// Get cart total.
			$cart_total = WC()->cart->get_subtotal();
			
			// Check if cart total is less than threshold.
			if ( $cart_total < $threshold ) {
				// Calculate remaining amount.
				$remaining = $threshold - $cart_total;
				
				$notices[] = array(
					'type'    => 'info',
					'message' => sprintf(
						/* translators: %s: remaining amount */
						esc_html__( 'Add %s more to your cart to get free shipping!', 'aqualuxe' ),
						wc_price( $remaining )
					),
					'icon'    => 'truck',
				);
			} else {
				$notices[] = array(
					'type'    => 'success',
					'message' => esc_html__( 'Congratulations! You\'ve qualified for free shipping!', 'aqualuxe' ),
					'icon'    => 'truck',
				);
			}
		}
		
		// Add custom notice.
		$custom_notice = get_theme_mod( 'aqualuxe_checkout_custom_notice', '' );
		
		if ( ! empty( $custom_notice ) ) {
			$notices[] = array(
				'type'    => 'info',
				'message' => $custom_notice,
				'icon'    => 'info-circle',
			);
		}
		
		// Output notices.
		if ( ! empty( $notices ) ) {
			echo '<div class="checkout-notices">';
			
			foreach ( $notices as $notice ) {
				echo '<div class="checkout-notice notice-' . esc_attr( $notice['type'] ) . '">';
				echo '<span class="notice-icon"><i class="fas fa-' . esc_attr( $notice['icon'] ) . '"></i></span>';
				echo '<span class="notice-message">' . wp_kses_post( $notice['message'] ) . '</span>';
				echo '</div>';
			}
			
			echo '</div>';
		}
	}

	/**
	 * Checkout coupon form.
	 *
	 * @return void
	 */
	public function checkout_coupon_form() {
		// Check if coupons are enabled.
		if ( ! wc_coupons_enabled() ) {
			return;
		}
		
		// Check if coupon form should be shown.
		if ( ! get_theme_mod( 'aqualuxe_checkout_coupon_form', true ) ) {
			return;
		}
		
		// Get coupon form style.
		$coupon_form_style = get_theme_mod( 'aqualuxe_checkout_coupon_form_style', 'collapsible' );
		
		if ( 'inline' === $coupon_form_style ) {
			// Remove default coupon form.
			remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
			
			// Add inline coupon form.
			add_action( 'woocommerce_checkout_before_customer_details', array( $this, 'inline_coupon_form' ), 10 );
		}
	}

	/**
	 * Inline coupon form.
	 *
	 * @return void
	 */
	public function inline_coupon_form() {
		// Check if coupons are enabled.
		if ( ! wc_coupons_enabled() ) {
			return;
		}
		
		// Output coupon form.
		echo '<div class="checkout-coupon-form">';
		echo '<div class="coupon">';
		echo '<label for="coupon_code">' . esc_html__( 'Coupon code', 'aqualuxe' ) . '</label>';
		echo '<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="' . esc_attr__( 'Enter coupon code', 'aqualuxe' ) . '" />';
		echo '<button type="button" class="button" name="apply_coupon" value="' . esc_attr__( 'Apply coupon', 'aqualuxe' ) . '">' . esc_html__( 'Apply coupon', 'aqualuxe' ) . '</button>';
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Checkout login form.
	 *
	 * @return void
	 */
	public function checkout_login_form() {
		// Check if login form should be shown.
		if ( ! get_theme_mod( 'aqualuxe_checkout_login_form', true ) ) {
			return;
		}
		
		// Get login form style.
		$login_form_style = get_theme_mod( 'aqualuxe_checkout_login_form_style', 'collapsible' );
		
		if ( 'inline' === $login_form_style ) {
			// Remove default login form.
			remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
			
			// Add inline login form.
			add_action( 'woocommerce_checkout_before_customer_details', array( $this, 'inline_login_form' ), 5 );
		}
	}

	/**
	 * Inline login form.
	 *
	 * @return void
	 */
	public function inline_login_form() {
		// Check if user is logged in.
		if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
			return;
		}
		
		// Output login form.
		echo '<div class="checkout-login-form">';
		echo '<div class="login">';
		echo '<h3>' . esc_html__( 'Returning customer?', 'aqualuxe' ) . '</h3>';
		echo '<p>' . esc_html__( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'aqualuxe' ) . '</p>';
		
		woocommerce_login_form(
			array(
				'message'  => esc_html__( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'aqualuxe' ),
				'redirect' => wc_get_checkout_url(),
				'hidden'   => false,
			)
		);
		
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Order summary.
	 *
	 * @return void
	 */
	public function order_summary() {
		// Check if order summary should be shown.
		if ( ! get_theme_mod( 'aqualuxe_checkout_order_summary', true ) ) {
			return;
		}
		
		// Get order summary style.
		$order_summary_style = get_theme_mod( 'aqualuxe_checkout_order_summary_style', 'standard' );
		
		if ( 'compact' === $order_summary_style ) {
			// Output compact order summary.
			echo '<div class="order-summary compact">';
			echo '<h3>' . esc_html__( 'Order Summary', 'aqualuxe' ) . '</h3>';
			
			// Get cart items.
			$cart_items = WC()->cart->get_cart();
			
			// Get cart item count.
			$cart_item_count = count( $cart_items );
			
			// Output cart item count.
			echo '<div class="order-summary-item-count">';
			echo '<span class="item-count">' . esc_html( sprintf( _n( '%d item', '%d items', $cart_item_count, 'aqualuxe' ), $cart_item_count ) ) . '</span>';
			echo '<a href="#" class="toggle-order-summary">' . esc_html__( 'Show details', 'aqualuxe' ) . '</a>';
			echo '</div>';
			
			// Output cart items.
			echo '<div class="order-summary-items" style="display: none;">';
			
			foreach ( $cart_items as $cart_item_key => $cart_item ) {
				$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				
				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					echo '<div class="order-summary-item">';
					
					// Output product thumbnail.
					echo '<div class="order-summary-item-thumbnail">';
					echo apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'thumbnail' ), $cart_item, $cart_item_key );
					echo '</div>';
					
					// Output product name.
					echo '<div class="order-summary-item-name">';
					echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;';
					echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key );
					echo '</div>';
					
					// Output product price.
					echo '<div class="order-summary-item-price">';
					echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
					echo '</div>';
					
					echo '</div>';
				}
			}
			
			echo '</div>';
			
			// Output cart totals.
			echo '<div class="order-summary-totals">';
			
			// Output subtotal.
			echo '<div class="order-summary-subtotal">';
			echo '<span class="label">' . esc_html__( 'Subtotal', 'aqualuxe' ) . '</span>';
			echo '<span class="value">' . WC()->cart->get_cart_subtotal() . '</span>';
			echo '</div>';
			
			// Output shipping.
			if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) {
				echo '<div class="order-summary-shipping">';
				echo '<span class="label">' . esc_html__( 'Shipping', 'aqualuxe' ) . '</span>';
				echo '<span class="value">' . WC()->cart->get_cart_shipping_total() . '</span>';
				echo '</div>';
			}
			
			// Output taxes.
			if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
				$taxable_address = WC()->customer->get_taxable_address();
				$estimated_text  = '';
				
				if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
					/* translators: %s location. */
					$estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'aqualuxe' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
				}
				
				if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
					foreach ( WC()->cart->get_tax_totals() as $code => $tax ) {
						echo '<div class="order-summary-tax">';
						echo '<span class="label">' . esc_html( $tax->label ) . $estimated_text . '</span>';
						echo '<span class="value">' . wp_kses_post( $tax->formatted_amount ) . '</span>';
						echo '</div>';
					}
				} else {
					echo '<div class="order-summary-tax">';
					echo '<span class="label">' . esc_html( WC()->countries->tax_or_vat() ) . $estimated_text . '</span>';
					echo '<span class="value">' . wc_price( WC()->cart->get_taxes_total() ) . '</span>';
					echo '</div>';
				}
			}
			
			// Output total.
			echo '<div class="order-summary-total">';
			echo '<span class="label">' . esc_html__( 'Total', 'aqualuxe' ) . '</span>';
			echo '<span class="value">' . WC()->cart->get_total() . '</span>';
			echo '</div>';
			
			echo '</div>';
			
			echo '</div>';
		}
	}

	/**
	 * Gateway icon.
	 *
	 * @param string $icon Icon.
	 * @param string $id   Gateway ID.
	 * @return string
	 */
	public function gateway_icon( $icon, $id ) {
		// Check if payment icons should be shown.
		if ( ! get_theme_mod( 'aqualuxe_checkout_payment_icons', true ) ) {
			return '';
		}
		
		// Check if payment icons should be customized.
		if ( get_theme_mod( 'aqualuxe_checkout_payment_icons_style', 'default' ) === 'custom' ) {
			// Get custom icons.
			$custom_icons = get_theme_mod( 'aqualuxe_checkout_payment_icons_custom', array() );
			
			if ( ! empty( $custom_icons ) && isset( $custom_icons[ $id ] ) ) {
				$icon = '<img src="' . esc_url( $custom_icons[ $id ] ) . '" alt="' . esc_attr( $id ) . '" />';
			}
		}
		
		return $icon;
	}

	/**
	 * Gateway title.
	 *
	 * @param string $title Title.
	 * @param string $id    Gateway ID.
	 * @return string
	 */
	public function gateway_title( $title, $id ) {
		// Check if payment titles should be customized.
		if ( get_theme_mod( 'aqualuxe_checkout_payment_titles_style', 'default' ) === 'custom' ) {
			// Get custom titles.
			$custom_titles = get_theme_mod( 'aqualuxe_checkout_payment_titles_custom', array() );
			
			if ( ! empty( $custom_titles ) && isset( $custom_titles[ $id ] ) ) {
				$title = $custom_titles[ $id ];
			}
		}
		
		return $title;
	}

	/**
	 * Gateway description.
	 *
	 * @param string $description Description.
	 * @param string $id          Gateway ID.
	 * @return string
	 */
	public function gateway_description( $description, $id ) {
		// Check if payment descriptions should be customized.
		if ( get_theme_mod( 'aqualuxe_checkout_payment_descriptions_style', 'default' ) === 'custom' ) {
			// Get custom descriptions.
			$custom_descriptions = get_theme_mod( 'aqualuxe_checkout_payment_descriptions_custom', array() );
			
			if ( ! empty( $custom_descriptions ) && isset( $custom_descriptions[ $id ] ) ) {
				$description = $custom_descriptions[ $id ];
			}
		}
		
		return $description;
	}

	/**
	 * Checkout validation.
	 *
	 * @param array    $data   Checkout data.
	 * @param WP_Error $errors Errors.
	 * @return void
	 */
	public function checkout_validation( $data, $errors ) {
		// Check if delivery date is required.
		if ( get_theme_mod( 'aqualuxe_checkout_delivery_date', false ) && get_theme_mod( 'aqualuxe_checkout_delivery_date_required', false ) ) {
			if ( empty( $data['billing_delivery_date'] ) ) {
				$errors->add( 'validation', esc_html__( 'Please select a delivery date.', 'aqualuxe' ) );
			}
		}
		
		// Check if delivery time is required.
		if ( get_theme_mod( 'aqualuxe_checkout_delivery_time', false ) && get_theme_mod( 'aqualuxe_checkout_delivery_time_required', false ) ) {
			if ( empty( $data['billing_delivery_time'] ) ) {
				$errors->add( 'validation', esc_html__( 'Please select a delivery time.', 'aqualuxe' ) );
			}
		}
	}

	/**
	 * Checkout process.
	 *
	 * @return void
	 */
	public function checkout_process() {
		// Check if delivery date is required.
		if ( get_theme_mod( 'aqualuxe_checkout_delivery_date', false ) && get_theme_mod( 'aqualuxe_checkout_delivery_date_required', false ) ) {
			if ( empty( $_POST['billing_delivery_date'] ) ) {
				wc_add_notice( esc_html__( 'Please select a delivery date.', 'aqualuxe' ), 'error' );
			}
		}
		
		// Check if delivery time is required.
		if ( get_theme_mod( 'aqualuxe_checkout_delivery_time', false ) && get_theme_mod( 'aqualuxe_checkout_delivery_time_required', false ) ) {
			if ( empty( $_POST['billing_delivery_time'] ) ) {
				wc_add_notice( esc_html__( 'Please select a delivery time.', 'aqualuxe' ), 'error' );
			}
		}
	}

	/**
	 * Checkout order processed.
	 *
	 * @param int      $order_id Order ID.
	 * @param array    $posted_data Posted data.
	 * @param WC_Order $order Order object.
	 * @return void
	 */
	public function checkout_order_processed( $order_id, $posted_data, $order ) {
		// Save delivery date.
		if ( get_theme_mod( 'aqualuxe_checkout_delivery_date', false ) && ! empty( $posted_data['billing_delivery_date'] ) ) {
			$order->update_meta_data( '_billing_delivery_date', sanitize_text_field( $posted_data['billing_delivery_date'] ) );
		}
		
		// Save delivery time.
		if ( get_theme_mod( 'aqualuxe_checkout_delivery_time', false ) && ! empty( $posted_data['billing_delivery_time'] ) ) {
			$order->update_meta_data( '_billing_delivery_time', sanitize_text_field( $posted_data['billing_delivery_time'] ) );
		}
		
		// Save gift message.
		if ( get_theme_mod( 'aqualuxe_checkout_gift_message', false ) && ! empty( $posted_data['order_gift_message'] ) ) {
			$order->update_meta_data( '_order_gift_message', sanitize_textarea_field( $posted_data['order_gift_message'] ) );
		}
		
		// Save order.
		$order->save();
	}

	/**
	 * Thank you page.
	 *
	 * @param int $order_id Order ID.
	 * @return void
	 */
	public function thankyou( $order_id ) {
		// Check if order exists.
		if ( ! $order_id ) {
			return;
		}
		
		// Get order.
		$order = wc_get_order( $order_id );
		
		// Check if order exists.
		if ( ! $order ) {
			return;
		}
		
		// Check if thank you page should be customized.
		if ( ! get_theme_mod( 'aqualuxe_checkout_thankyou_custom', false ) ) {
			return;
		}
		
		// Get thank you page style.
		$thankyou_style = get_theme_mod( 'aqualuxe_checkout_thankyou_style', 'standard' );
		
		if ( 'enhanced' === $thankyou_style ) {
			// Remove default thank you page.
			remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
			
			// Add enhanced thank you page.
			add_action( 'woocommerce_thankyou', array( $this, 'enhanced_thankyou' ), 10 );
		}
	}

	/**
	 * Enhanced thank you page.
	 *
	 * @param int $order_id Order ID.
	 * @return void
	 */
	public function enhanced_thankyou( $order_id ) {
		// Check if order exists.
		if ( ! $order_id ) {
			return;
		}
		
		// Get order.
		$order = wc_get_order( $order_id );
		
		// Check if order exists.
		if ( ! $order ) {
			return;
		}
		
		// Get thank you page template.
		get_template_part( 'template-parts/woocommerce/thankyou', 'enhanced', array( 'order' => $order ) );
	}

	/**
	 * Before thank you page.
	 *
	 * @param int $order_id Order ID.
	 * @return void
	 */
	public function before_thankyou( $order_id ) {
		// Check if order exists.
		if ( ! $order_id ) {
			return;
		}
		
		// Get order.
		$order = wc_get_order( $order_id );
		
		// Check if order exists.
		if ( ! $order ) {
			return;
		}
		
		// Check if thank you page should be customized.
		if ( ! get_theme_mod( 'aqualuxe_checkout_thankyou_custom', false ) ) {
			return;
		}
		
		// Get thank you page style.
		$thankyou_style = get_theme_mod( 'aqualuxe_checkout_thankyou_style', 'standard' );
		
		if ( 'enhanced' === $thankyou_style ) {
			// Output thank you page wrapper.
			echo '<div class="thankyou-wrapper enhanced">';
		}
	}

	/**
	 * After thank you page.
	 *
	 * @param int $order_id Order ID.
	 * @return void
	 */
	public function after_thankyou( $order_id ) {
		// Check if order exists.
		if ( ! $order_id ) {
			return;
		}
		
		// Get order.
		$order = wc_get_order( $order_id );
		
		// Check if order exists.
		if ( ! $order ) {
			return;
		}
		
		// Check if thank you page should be customized.
		if ( ! get_theme_mod( 'aqualuxe_checkout_thankyou_custom', false ) ) {
			return;
		}
		
		// Get thank you page style.
		$thankyou_style = get_theme_mod( 'aqualuxe_checkout_thankyou_style', 'standard' );
		
		if ( 'enhanced' === $thankyou_style ) {
			// Close thank you page wrapper.
			echo '</div>';
		}
	}
}