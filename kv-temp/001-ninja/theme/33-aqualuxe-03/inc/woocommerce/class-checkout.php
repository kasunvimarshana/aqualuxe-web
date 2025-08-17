<?php
/**
 * WooCommerce Checkout Class
 *
 * @package AquaLuxe
 * @subpackage WooCommerce
 */

namespace AquaLuxe\WooCommerce;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Checkout Class
 *
 * Handles checkout customization for WooCommerce.
 */
class Checkout {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Checkout layout.
		add_action( 'woocommerce_before_checkout_form', array( $this, 'checkout_wrapper_start' ), 5 );
		add_action( 'woocommerce_after_checkout_form', array( $this, 'checkout_wrapper_end' ), 15 );
		
		// Checkout fields.
		add_filter( 'woocommerce_checkout_fields', array( $this, 'customize_checkout_fields' ) );
		add_filter( 'woocommerce_default_address_fields', array( $this, 'customize_default_address_fields' ) );
		
		// Checkout steps for multi-step checkout.
		add_action( 'woocommerce_before_checkout_form', array( $this, 'maybe_add_checkout_steps' ), 10 );
		
		// Order review modifications.
		add_action( 'woocommerce_checkout_order_review', array( $this, 'reorder_checkout_order_review' ), 1 );
		
		// Add trust badges.
		add_action( 'woocommerce_review_order_after_payment', array( $this, 'add_checkout_trust_badges' ) );
		
		// Add order notes placeholder.
		add_filter( 'woocommerce_checkout_fields', array( $this, 'customize_order_notes_placeholder' ) );
		
		// Add custom validation.
		add_action( 'woocommerce_after_checkout_validation', array( $this, 'custom_checkout_validation' ), 10, 2 );
		
		// Add field icons.
		add_filter( 'woocommerce_form_field_args', array( $this, 'add_field_icons' ), 10, 3 );
		
		// Thank you page customization.
		add_action( 'woocommerce_thankyou', array( $this, 'customize_thank_you_page' ) );
		
		// Add order details to thank you page.
		add_action( 'woocommerce_thankyou_order_details', array( $this, 'add_order_details_summary' ), 5 );
		
		// Add express checkout options.
		add_action( 'woocommerce_before_checkout_form', array( $this, 'add_express_checkout_options' ), 15 );
	}

	/**
	 * Checkout wrapper start
	 */
	public function checkout_wrapper_start() {
		$checkout_layout = get_theme_mod( 'aqualuxe_checkout_layout', 'standard' );
		echo '<div class="aqualuxe-checkout-wrapper checkout-layout-' . esc_attr( $checkout_layout ) . '">';
		
		// For two-column layout, add container divs.
		if ( 'two-column' === $checkout_layout ) {
			echo '<div class="aqualuxe-checkout-columns">';
			echo '<div class="aqualuxe-checkout-column-left">';
		}
		
		// For multi-step layout, add container for steps.
		if ( 'multistep' === $checkout_layout ) {
			echo '<div class="aqualuxe-checkout-steps-container">';
		}
	}

	/**
	 * Checkout wrapper end
	 */
	public function checkout_wrapper_end() {
		$checkout_layout = get_theme_mod( 'aqualuxe_checkout_layout', 'standard' );
		
		// Close containers for two-column layout.
		if ( 'two-column' === $checkout_layout ) {
			echo '</div><!-- .aqualuxe-checkout-column-left -->';
			echo '</div><!-- .aqualuxe-checkout-columns -->';
		}
		
		// Close container for multi-step layout.
		if ( 'multistep' === $checkout_layout ) {
			echo '</div><!-- .aqualuxe-checkout-steps-container -->';
		}
		
		echo '</div><!-- .aqualuxe-checkout-wrapper -->';
	}

	/**
	 * Customize checkout fields
	 *
	 * @param array $fields Checkout fields.
	 * @return array Modified checkout fields.
	 */
	public function customize_checkout_fields( $fields ) {
		// Add classes to all fields.
		foreach ( $fields as $section_key => $section ) {
			if ( is_array( $section ) ) {
				foreach ( $section as $key => $field ) {
					// Add custom class.
					$fields[ $section_key ][ $key ]['class'][] = 'aqualuxe-form-field';
					
					// Add placeholder if not set.
					if ( ! isset( $field['placeholder'] ) || empty( $field['placeholder'] ) ) {
						$fields[ $section_key ][ $key ]['placeholder'] = $field['label'];
					}
					
					// Add aria-label for accessibility.
					$fields[ $section_key ][ $key ]['custom_attributes']['aria-label'] = $field['label'];
				}
			}
		}
		
		// Make phone field optional.
		if ( isset( $fields['billing']['billing_phone'] ) ) {
			$fields['billing']['billing_phone']['required'] = false;
		}
		
		// Change order of email field.
		if ( isset( $fields['billing']['billing_email'] ) ) {
			$fields['billing']['billing_email']['priority'] = 5;
		}
		
		// Add custom field for delivery instructions.
		$fields['shipping']['shipping_delivery_instructions'] = array(
			'label'       => esc_html__( 'Delivery Instructions', 'aqualuxe' ),
			'placeholder' => esc_html__( 'Special notes for delivery (optional)', 'aqualuxe' ),
			'required'    => false,
			'class'       => array( 'form-row-wide', 'aqualuxe-form-field' ),
			'clear'       => true,
			'type'        => 'textarea',
			'priority'    => 120,
		);
		
		return $fields;
	}

	/**
	 * Customize default address fields
	 *
	 * @param array $fields Address fields.
	 * @return array Modified address fields.
	 */
	public function customize_default_address_fields( $fields ) {
		// Change some field labels.
		if ( isset( $fields['address_1'] ) ) {
			$fields['address_1']['label'] = esc_html__( 'Street Address', 'aqualuxe' );
		}
		
		if ( isset( $fields['address_2'] ) ) {
			$fields['address_2']['label'] = esc_html__( 'Apartment, Suite, etc.', 'aqualuxe' );
		}
		
		// Add placeholder to all fields.
		foreach ( $fields as $key => $field ) {
			if ( ! isset( $field['placeholder'] ) || empty( $field['placeholder'] ) ) {
				$fields[ $key ]['placeholder'] = $field['label'];
			}
		}
		
		return $fields;
	}

	/**
	 * Maybe add checkout steps
	 */
	public function maybe_add_checkout_steps() {
		$checkout_layout = get_theme_mod( 'aqualuxe_checkout_layout', 'standard' );
		
		// Only add steps for multi-step checkout.
		if ( 'multistep' === $checkout_layout ) {
			?>
			<div class="aqualuxe-checkout-steps">
				<div class="aqualuxe-checkout-step active" data-step="1">
					<span class="step-number">1</span>
					<span class="step-title"><?php esc_html_e( 'Customer Info', 'aqualuxe' ); ?></span>
				</div>
				<div class="aqualuxe-checkout-step" data-step="2">
					<span class="step-number">2</span>
					<span class="step-title"><?php esc_html_e( 'Shipping', 'aqualuxe' ); ?></span>
				</div>
				<div class="aqualuxe-checkout-step" data-step="3">
					<span class="step-number">3</span>
					<span class="step-title"><?php esc_html_e( 'Payment', 'aqualuxe' ); ?></span>
				</div>
				<div class="aqualuxe-checkout-step" data-step="4">
					<span class="step-number">4</span>
					<span class="step-title"><?php esc_html_e( 'Review', 'aqualuxe' ); ?></span>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Reorder checkout order review
	 */
	public function reorder_checkout_order_review() {
		// Remove default order review table and payment.
		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
		
		// Re-add them in custom order.
		add_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
		add_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
		
		// For two-column layout, move order review to right column.
		$checkout_layout = get_theme_mod( 'aqualuxe_checkout_layout', 'standard' );
		if ( 'two-column' === $checkout_layout ) {
			echo '</div><!-- .aqualuxe-checkout-column-left -->';
			echo '<div class="aqualuxe-checkout-column-right">';
			echo '<div class="aqualuxe-checkout-order-review-wrapper">';
			echo '<h3>' . esc_html__( 'Order Summary', 'aqualuxe' ) . '</h3>';
		}
	}

	/**
	 * Add checkout trust badges
	 */
	public function add_checkout_trust_badges() {
		?>
		<div class="aqualuxe-checkout-trust-badges">
			<h4><?php esc_html_e( 'Secure Checkout', 'aqualuxe' ); ?></h4>
			<div class="aqualuxe-trust-badges-icons">
				<span class="trust-badge"><i class="fas fa-lock"></i> <?php esc_html_e( 'SSL Secure', 'aqualuxe' ); ?></span>
				<span class="trust-badge"><i class="fas fa-shield-alt"></i> <?php esc_html_e( '100% Safe', 'aqualuxe' ); ?></span>
				<span class="trust-badge"><i class="fas fa-credit-card"></i> <?php esc_html_e( 'Payment Encrypted', 'aqualuxe' ); ?></span>
			</div>
			<div class="aqualuxe-payment-icons">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/payment-icons.png' ); ?>" alt="<?php esc_attr_e( 'Payment Methods', 'aqualuxe' ); ?>">
			</div>
		</div>
		<?php
		
		// Close containers for two-column layout.
		$checkout_layout = get_theme_mod( 'aqualuxe_checkout_layout', 'standard' );
		if ( 'two-column' === $checkout_layout ) {
			echo '</div><!-- .aqualuxe-checkout-order-review-wrapper -->';
			echo '</div><!-- .aqualuxe-checkout-column-right -->';
		}
	}

	/**
	 * Customize order notes placeholder
	 *
	 * @param array $fields Checkout fields.
	 * @return array Modified checkout fields.
	 */
	public function customize_order_notes_placeholder( $fields ) {
		if ( isset( $fields['order']['order_comments'] ) ) {
			$fields['order']['order_comments']['placeholder'] = esc_html__( 'Special notes about your order (optional)', 'aqualuxe' );
		}
		
		return $fields;
	}

	/**
	 * Custom checkout validation
	 *
	 * @param array $data Posted data.
	 * @param object $errors Validation errors.
	 */
	public function custom_checkout_validation( $data, $errors ) {
		// Example: Validate phone number format if provided.
		if ( ! empty( $data['billing_phone'] ) ) {
			// Simple validation - phone should be at least 10 digits.
			if ( strlen( preg_replace( '/[^0-9]/', '', $data['billing_phone'] ) ) < 10 ) {
				$errors->add( 'validation', esc_html__( 'Please enter a valid phone number with at least 10 digits.', 'aqualuxe' ) );
			}
		}
	}

	/**
	 * Add field icons
	 *
	 * @param array  $args Field arguments.
	 * @param string $key Field key.
	 * @param string $value Field value.
	 * @return array Modified field arguments.
	 */
	public function add_field_icons( $args, $key, $value ) {
		// Define icons for specific fields.
		$icons = array(
			'billing_email'    => 'fas fa-envelope',
			'billing_phone'    => 'fas fa-phone',
			'billing_address_1' => 'fas fa-map-marker-alt',
			'billing_city'     => 'fas fa-city',
			'billing_postcode' => 'fas fa-mail-bulk',
			'billing_country'  => 'fas fa-globe',
			'billing_state'    => 'fas fa-map',
		);
		
		// Add icon if defined.
		if ( isset( $icons[ $key ] ) ) {
			$icon = $icons[ $key ];
			$args['label'] = '<i class="' . esc_attr( $icon ) . '"></i> ' . $args['label'];
		}
		
		return $args;
	}

	/**
	 * Customize thank you page
	 *
	 * @param int $order_id Order ID.
	 */
	public function customize_thank_you_page( $order_id ) {
		if ( ! $order_id ) {
			return;
		}
		
		$order = wc_get_order( $order_id );
		
		// Add custom thank you message.
		?>
		<div class="aqualuxe-thank-you-message">
			<div class="aqualuxe-thank-you-icon">
				<i class="fas fa-check-circle"></i>
			</div>
			<h2><?php esc_html_e( 'Thank You for Your Order!', 'aqualuxe' ); ?></h2>
			<p><?php esc_html_e( 'Your order has been received and is now being processed.', 'aqualuxe' ); ?></p>
			
			<?php if ( $order->get_customer_note() ) : ?>
				<div class="aqualuxe-order-note">
					<strong><?php esc_html_e( 'Note:', 'aqualuxe' ); ?></strong>
					<?php echo wp_kses_post( nl2br( $order->get_customer_note() ) ); ?>
				</div>
			<?php endif; ?>
			
			<div class="aqualuxe-thank-you-actions">
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button continue-shopping">
					<?php esc_html_e( 'Continue Shopping', 'aqualuxe' ); ?>
				</a>
				
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" class="button view-orders">
						<?php esc_html_e( 'View Orders', 'aqualuxe' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Add order details summary
	 *
	 * @param object $order Order object.
	 */
	public function add_order_details_summary( $order ) {
		?>
		<div class="aqualuxe-order-details-summary">
			<h3><?php esc_html_e( 'Order Summary', 'aqualuxe' ); ?></h3>
			
			<div class="aqualuxe-order-summary-items">
				<?php
				// Get order items.
				$items = $order->get_items();
				$item_count = 0;
				
				foreach ( $items as $item ) {
					$product = $item->get_product();
					$item_count += $item->get_quantity();
					
					if ( ! $product ) {
						continue;
					}
					
					$image = $product->get_image( 'thumbnail' );
					?>
					<div class="aqualuxe-order-summary-item">
						<div class="aqualuxe-order-item-image">
							<?php echo wp_kses_post( $image ); ?>
							<span class="aqualuxe-order-item-quantity"><?php echo esc_html( $item->get_quantity() ); ?></span>
						</div>
						<div class="aqualuxe-order-item-details">
							<span class="aqualuxe-order-item-name"><?php echo esc_html( $item->get_name() ); ?></span>
							<span class="aqualuxe-order-item-price"><?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?></span>
						</div>
					</div>
					<?php
				}
				?>
			</div>
			
			<div class="aqualuxe-order-summary-totals">
				<div class="aqualuxe-order-summary-row">
					<span><?php esc_html_e( 'Items:', 'aqualuxe' ); ?></span>
					<span><?php echo esc_html( $item_count ); ?></span>
				</div>
				
				<div class="aqualuxe-order-summary-row">
					<span><?php esc_html_e( 'Subtotal:', 'aqualuxe' ); ?></span>
					<span><?php echo wp_kses_post( $order->get_subtotal_to_display() ); ?></span>
				</div>
				
				<?php if ( $order->get_shipping_total() > 0 ) : ?>
					<div class="aqualuxe-order-summary-row">
						<span><?php esc_html_e( 'Shipping:', 'aqualuxe' ); ?></span>
						<span><?php echo wp_kses_post( $order->get_shipping_to_display() ); ?></span>
					</div>
				<?php endif; ?>
				
				<?php if ( $order->get_total_discount() > 0 ) : ?>
					<div class="aqualuxe-order-summary-row discount">
						<span><?php esc_html_e( 'Discount:', 'aqualuxe' ); ?></span>
						<span>-<?php echo wp_kses_post( wc_price( $order->get_total_discount() ) ); ?></span>
					</div>
				<?php endif; ?>
				
				<?php if ( $order->get_total_tax() > 0 ) : ?>
					<div class="aqualuxe-order-summary-row">
						<span><?php esc_html_e( 'Tax:', 'aqualuxe' ); ?></span>
						<span><?php echo wp_kses_post( wc_price( $order->get_total_tax() ) ); ?></span>
					</div>
				<?php endif; ?>
				
				<div class="aqualuxe-order-summary-row total">
					<span><?php esc_html_e( 'Total:', 'aqualuxe' ); ?></span>
					<span><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></span>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Add express checkout options
	 */
	public function add_express_checkout_options() {
		// Only add if enabled in theme options.
		$show_express_checkout = true; // This could be a theme option.
		
		if ( $show_express_checkout ) {
			?>
			<div class="aqualuxe-express-checkout">
				<h3><?php esc_html_e( 'Express Checkout', 'aqualuxe' ); ?></h3>
				<div class="aqualuxe-express-checkout-buttons">
					<button type="button" class="aqualuxe-express-checkout-button paypal">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/paypal.svg' ); ?>" alt="<?php esc_attr_e( 'PayPal', 'aqualuxe' ); ?>">
					</button>
					<button type="button" class="aqualuxe-express-checkout-button apple-pay">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/apple-pay.svg' ); ?>" alt="<?php esc_attr_e( 'Apple Pay', 'aqualuxe' ); ?>">
					</button>
					<button type="button" class="aqualuxe-express-checkout-button google-pay">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/google-pay.svg' ); ?>" alt="<?php esc_attr_e( 'Google Pay', 'aqualuxe' ); ?>">
					</button>
				</div>
				<div class="aqualuxe-checkout-separator">
					<span><?php esc_html_e( 'OR', 'aqualuxe' ); ?></span>
				</div>
			</div>
			<?php
		}
	}
}

// Initialize the class.
new Checkout();