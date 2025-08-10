<?php
/**
 * AquaLuxe International Shipping Optimization
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * International Shipping Class
 */
class AquaLuxe_International_Shipping {
	/**
	 * Constructor
	 */
	public function __construct() {
		// Check if international shipping is enabled
		if ( ! get_theme_mod( 'aqualuxe_international_shipping', false ) ) {
			return;
		}

		// Optimize shipping rates
		add_filter( 'woocommerce_package_rates', array( $this, 'optimize_shipping_rates' ), 10, 2 );
		
		// Add shipping notices
		add_action( 'woocommerce_review_order_before_shipping', array( $this, 'add_shipping_notice' ) );
		add_action( 'woocommerce_before_shipping_calculator', array( $this, 'add_shipping_notice' ) );
		add_action( 'woocommerce_cart_totals_before_shipping', array( $this, 'add_shipping_notice' ) );
		
		// Add shipping information to order emails
		add_action( 'woocommerce_email_before_order_table', array( $this, 'add_shipping_info_to_email' ), 10, 4 );
		
		// Add shipping information to order details
		add_action( 'woocommerce_order_details_after_order_table', array( $this, 'add_shipping_info_to_order' ) );
		
		// Add shipping zone management to admin
		add_action( 'admin_menu', array( $this, 'add_shipping_zones_menu' ) );
		
		// Add shipping zone management AJAX handlers
		add_action( 'wp_ajax_aqualuxe_save_shipping_zone', array( $this, 'ajax_save_shipping_zone' ) );
		
		// Add shipping zone management scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		
		// Add shipping zone management styles
		add_action( 'admin_head', array( $this, 'add_admin_styles' ) );
	}

	/**
	 * Optimize shipping rates
	 *
	 * @param array $rates Shipping rates.
	 * @param array $package Shipping package.
	 * @return array
	 */
	public function optimize_shipping_rates( $rates, $package ) {
		// Get customer country
		$customer_country = $package['destination']['country'];
		
		// Get store country
		$store_country = WC()->countries->get_base_country();
		
		// Check if international shipping
		if ( $customer_country !== $store_country ) {
			// Get international shipping zones
			$shipping_zones = $this->get_shipping_zones();
			
			// Find the zone for the customer country
			$customer_zone = 'rest_of_world';
			
			foreach ( $shipping_zones as $zone_id => $zone_data ) {
				if ( in_array( $customer_country, $zone_data['countries'], true ) ) {
					$customer_zone = $zone_id;
					break;
				}
			}
			
			// Apply zone-specific rates
			foreach ( $rates as $rate_id => $rate ) {
				// Get rate adjustment from theme mod
				$adjustment = get_theme_mod( 'aqualuxe_shipping_rate_' . $customer_zone, $shipping_zones[ $customer_zone ]['rate_adjustment'] );
				
				// Apply adjustment
				$rates[ $rate_id ]->cost *= $adjustment;
				
				// Update label to indicate international shipping
				$rates[ $rate_id ]->label = sprintf(
					/* translators: %s: shipping method label */
					esc_html__( 'International: %s', 'aqualuxe' ),
					$rates[ $rate_id ]->label
				);
				
				// Add meta data for estimated delivery time
				$delivery_time = get_theme_mod( 'aqualuxe_shipping_time_' . $customer_zone, $shipping_zones[ $customer_zone ]['delivery_time'] );
				$rates[ $rate_id ]->add_meta_data( 'delivery_time', $delivery_time );
				
				// Add meta data for shipping zone
				$rates[ $rate_id ]->add_meta_data( 'shipping_zone', $shipping_zones[ $customer_zone ]['name'] );
			}
		}
		
		return $rates;
	}

	/**
	 * Add shipping notice
	 */
	public function add_shipping_notice() {
		// Get customer country
		$customer_country = WC()->customer->get_shipping_country();
		
		// Get store country
		$store_country = WC()->countries->get_base_country();
		
		// Check if international shipping
		if ( $customer_country !== $store_country ) {
			// Get international shipping zones
			$shipping_zones = $this->get_shipping_zones();
			
			// Find the zone for the customer country
			$customer_zone = 'rest_of_world';
			
			foreach ( $shipping_zones as $zone_id => $zone_data ) {
				if ( in_array( $customer_country, $zone_data['countries'], true ) ) {
					$customer_zone = $zone_id;
					break;
				}
			}
			
			// Display notice
			echo '<div class="aqualuxe-shipping-notice">';
			echo '<p>' . esc_html__( 'International shipping rates apply. Please allow additional time for delivery.', 'aqualuxe' ) . '</p>';
			
			// Display estimated delivery time
			$delivery_time = get_theme_mod( 'aqualuxe_shipping_time_' . $customer_zone, $shipping_zones[ $customer_zone ]['delivery_time'] );
			
			echo '<p>' . esc_html__( 'Estimated delivery time: ', 'aqualuxe' ) . esc_html( $delivery_time ) . '</p>';
			
			// Display shipping zone
			echo '<p>' . esc_html__( 'Shipping zone: ', 'aqualuxe' ) . esc_html( $shipping_zones[ $customer_zone ]['name'] ) . '</p>';
			
			echo '</div>';
			
			// Add styles
			?>
			<style>
				.aqualuxe-shipping-notice {
					background-color: #f8f8f8;
					border: 1px solid #ddd;
					border-radius: 4px;
					padding: 15px;
					margin-bottom: 20px;
				}
				
				.aqualuxe-shipping-notice p {
					margin: 0 0 10px;
				}
				
				.aqualuxe-shipping-notice p:last-child {
					margin-bottom: 0;
				}
			</style>
			<?php
		}
	}

	/**
	 * Add shipping information to email
	 *
	 * @param WC_Order $order Order object.
	 * @param bool $sent_to_admin Whether the email is sent to admin.
	 * @param bool $plain_text Whether the email is plain text.
	 * @param WC_Email $email Email object.
	 */
	public function add_shipping_info_to_email( $order, $sent_to_admin, $plain_text, $email ) {
		// Skip if not customer email
		if ( $sent_to_admin ) {
			return;
		}
		
		// Skip if not shipping email
		if ( ! in_array( $email->id, array( 'customer_completed_order', 'customer_processing_order', 'customer_on_hold_order' ), true ) ) {
			return;
		}
		
		// Get customer country
		$customer_country = $order->get_shipping_country();
		
		// Get store country
		$store_country = WC()->countries->get_base_country();
		
		// Check if international shipping
		if ( $customer_country !== $store_country ) {
			// Get international shipping zones
			$shipping_zones = $this->get_shipping_zones();
			
			// Find the zone for the customer country
			$customer_zone = 'rest_of_world';
			
			foreach ( $shipping_zones as $zone_id => $zone_data ) {
				if ( in_array( $customer_country, $zone_data['countries'], true ) ) {
					$customer_zone = $zone_id;
					break;
				}
			}
			
			// Display shipping information
			if ( $plain_text ) {
				echo "\n\n" . esc_html__( 'International Shipping Information', 'aqualuxe' ) . "\n";
				echo esc_html__( 'Shipping Zone: ', 'aqualuxe' ) . esc_html( $shipping_zones[ $customer_zone ]['name'] ) . "\n";
				echo esc_html__( 'Estimated Delivery Time: ', 'aqualuxe' ) . esc_html( get_theme_mod( 'aqualuxe_shipping_time_' . $customer_zone, $shipping_zones[ $customer_zone ]['delivery_time'] ) ) . "\n";
			} else {
				?>
				<h2><?php esc_html_e( 'International Shipping Information', 'aqualuxe' ); ?></h2>
				<p><strong><?php esc_html_e( 'Shipping Zone:', 'aqualuxe' ); ?></strong> <?php echo esc_html( $shipping_zones[ $customer_zone ]['name'] ); ?></p>
				<p><strong><?php esc_html_e( 'Estimated Delivery Time:', 'aqualuxe' ); ?></strong> <?php echo esc_html( get_theme_mod( 'aqualuxe_shipping_time_' . $customer_zone, $shipping_zones[ $customer_zone ]['delivery_time'] ) ); ?></p>
				<?php
			}
		}
	}

	/**
	 * Add shipping information to order
	 *
	 * @param WC_Order $order Order object.
	 */
	public function add_shipping_info_to_order( $order ) {
		// Get customer country
		$customer_country = $order->get_shipping_country();
		
		// Get store country
		$store_country = WC()->countries->get_base_country();
		
		// Check if international shipping
		if ( $customer_country !== $store_country ) {
			// Get international shipping zones
			$shipping_zones = $this->get_shipping_zones();
			
			// Find the zone for the customer country
			$customer_zone = 'rest_of_world';
			
			foreach ( $shipping_zones as $zone_id => $zone_data ) {
				if ( in_array( $customer_country, $zone_data['countries'], true ) ) {
					$customer_zone = $zone_id;
					break;
				}
			}
			
			// Display shipping information
			?>
			<h2><?php esc_html_e( 'International Shipping Information', 'aqualuxe' ); ?></h2>
			<table class="woocommerce-table woocommerce-table--shipping-info">
				<tbody>
					<tr>
						<th><?php esc_html_e( 'Shipping Zone:', 'aqualuxe' ); ?></th>
						<td><?php echo esc_html( $shipping_zones[ $customer_zone ]['name'] ); ?></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Estimated Delivery Time:', 'aqualuxe' ); ?></th>
						<td><?php echo esc_html( get_theme_mod( 'aqualuxe_shipping_time_' . $customer_zone, $shipping_zones[ $customer_zone ]['delivery_time'] ) ); ?></td>
					</tr>
				</tbody>
			</table>
			<?php
		}
	}

	/**
	 * Get shipping zones
	 *
	 * @return array
	 */
	public function get_shipping_zones() {
		return array(
			'europe' => array(
				'name'           => esc_html__( 'Europe', 'aqualuxe' ),
				'countries'      => WC()->countries->get_european_union_countries(),
				'rate_adjustment' => get_theme_mod( 'aqualuxe_shipping_rate_europe', 1.0 ),
				'delivery_time'   => get_theme_mod( 'aqualuxe_shipping_time_europe', esc_html__( '5-7 business days', 'aqualuxe' ) ),
			),
			'north_america' => array(
				'name'           => esc_html__( 'North America', 'aqualuxe' ),
				'countries'      => array( 'US', 'CA', 'MX' ),
				'rate_adjustment' => get_theme_mod( 'aqualuxe_shipping_rate_north_america', 1.2 ),
				'delivery_time'   => get_theme_mod( 'aqualuxe_shipping_time_north_america', esc_html__( '7-10 business days', 'aqualuxe' ) ),
			),
			'asia_pacific' => array(
				'name'           => esc_html__( 'Asia Pacific', 'aqualuxe' ),
				'countries'      => array( 'AU', 'NZ', 'JP', 'SG', 'KR', 'CN', 'HK', 'TW', 'IN', 'MY', 'TH', 'VN', 'ID', 'PH' ),
				'rate_adjustment' => get_theme_mod( 'aqualuxe_shipping_rate_asia_pacific', 1.5 ),
				'delivery_time'   => get_theme_mod( 'aqualuxe_shipping_time_asia_pacific', esc_html__( '10-14 business days', 'aqualuxe' ) ),
			),
			'rest_of_world' => array(
				'name'           => esc_html__( 'Rest of World', 'aqualuxe' ),
				'countries'      => array(),
				'rate_adjustment' => get_theme_mod( 'aqualuxe_shipping_rate_rest_of_world', 2.0 ),
				'delivery_time'   => get_theme_mod( 'aqualuxe_shipping_time_rest_of_world', esc_html__( '14-21 business days', 'aqualuxe' ) ),
			),
		);
	}

	/**
	 * Add shipping zones menu
	 */
	public function add_shipping_zones_menu() {
		add_submenu_page(
			'woocommerce',
			esc_html__( 'International Shipping Zones', 'aqualuxe' ),
			esc_html__( 'International Shipping', 'aqualuxe' ),
			'manage_woocommerce',
			'aqualuxe-shipping-zones',
			array( $this, 'shipping_zones_page' )
		);
	}

	/**
	 * Shipping zones page
	 */
	public function shipping_zones_page() {
		// Get shipping zones
		$shipping_zones = $this->get_shipping_zones();
		
		// Get all countries
		$countries = WC()->countries->get_countries();
		
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'International Shipping Zones', 'aqualuxe' ); ?></h1>
			
			<p><?php esc_html_e( 'Configure international shipping zones for your store. Each zone can have different shipping rates and delivery times.', 'aqualuxe' ); ?></p>
			
			<div class="aqualuxe-shipping-zones-wrapper">
				<div class="aqualuxe-shipping-zones-notice"></div>
				
				<div class="aqualuxe-shipping-zones-tabs">
					<ul class="aqualuxe-shipping-zones-tab-nav">
						<?php foreach ( $shipping_zones as $zone_id => $zone_data ) : ?>
							<li>
								<a href="#zone-<?php echo esc_attr( $zone_id ); ?>" class="<?php echo $zone_id === 'europe' ? 'active' : ''; ?>">
									<?php echo esc_html( $zone_data['name'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
					
					<div class="aqualuxe-shipping-zones-tab-content">
						<?php foreach ( $shipping_zones as $zone_id => $zone_data ) : ?>
							<div id="zone-<?php echo esc_attr( $zone_id ); ?>" class="aqualuxe-shipping-zone-tab <?php echo $zone_id === 'europe' ? 'active' : ''; ?>">
								<form class="aqualuxe-shipping-zone-form" data-zone="<?php echo esc_attr( $zone_id ); ?>">
									<table class="form-table">
										<tbody>
											<tr>
												<th scope="row">
													<label for="<?php echo esc_attr( $zone_id ); ?>-name"><?php esc_html_e( 'Zone Name', 'aqualuxe' ); ?></label>
												</th>
												<td>
													<input type="text" id="<?php echo esc_attr( $zone_id ); ?>-name" name="name" value="<?php echo esc_attr( $zone_data['name'] ); ?>" class="regular-text">
												</td>
											</tr>
											<tr>
												<th scope="row">
													<label for="<?php echo esc_attr( $zone_id ); ?>-rate"><?php esc_html_e( 'Rate Adjustment', 'aqualuxe' ); ?></label>
												</th>
												<td>
													<input type="number" id="<?php echo esc_attr( $zone_id ); ?>-rate" name="rate_adjustment" value="<?php echo esc_attr( $zone_data['rate_adjustment'] ); ?>" class="small-text" min="0.1" step="0.1">
													<p class="description"><?php esc_html_e( 'Multiply the base shipping rate by this value for this zone.', 'aqualuxe' ); ?></p>
												</td>
											</tr>
											<tr>
												<th scope="row">
													<label for="<?php echo esc_attr( $zone_id ); ?>-time"><?php esc_html_e( 'Delivery Time', 'aqualuxe' ); ?></label>
												</th>
												<td>
													<input type="text" id="<?php echo esc_attr( $zone_id ); ?>-time" name="delivery_time" value="<?php echo esc_attr( $zone_data['delivery_time'] ); ?>" class="regular-text">
													<p class="description"><?php esc_html_e( 'Estimated delivery time for this zone.', 'aqualuxe' ); ?></p>
												</td>
											</tr>
											<?php if ( $zone_id !== 'rest_of_world' ) : ?>
												<tr>
													<th scope="row">
														<label for="<?php echo esc_attr( $zone_id ); ?>-countries"><?php esc_html_e( 'Countries', 'aqualuxe' ); ?></label>
													</th>
													<td>
														<select id="<?php echo esc_attr( $zone_id ); ?>-countries" name="countries[]" multiple="multiple" class="aqualuxe-shipping-zone-countries">
															<?php foreach ( $countries as $code => $name ) : ?>
																<option value="<?php echo esc_attr( $code ); ?>" <?php selected( in_array( $code, $zone_data['countries'], true ), true ); ?>>
																	<?php echo esc_html( $name ); ?>
																</option>
															<?php endforeach; ?>
														</select>
														<p class="description"><?php esc_html_e( 'Select countries for this zone.', 'aqualuxe' ); ?></p>
													</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
									
									<p class="submit">
										<button type="submit" class="button button-primary"><?php esc_html_e( 'Save Zone', 'aqualuxe' ); ?></button>
										<span class="spinner"></span>
									</p>
								</form>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * AJAX save shipping zone
	 */
	public function ajax_save_shipping_zone() {
		// Check nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-shipping-zone-nonce' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce', 'aqualuxe' ) ) );
		}
		
		// Check permissions
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'You do not have permission to do this', 'aqualuxe' ) ) );
		}
		
		// Get zone
		$zone = isset( $_POST['zone'] ) ? sanitize_text_field( wp_unslash( $_POST['zone'] ) ) : '';
		
		if ( ! $zone ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid zone', 'aqualuxe' ) ) );
		}
		
		// Get zone data
		$name = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
		$rate_adjustment = isset( $_POST['rate_adjustment'] ) ? (float) $_POST['rate_adjustment'] : 1.0;
		$delivery_time = isset( $_POST['delivery_time'] ) ? sanitize_text_field( wp_unslash( $_POST['delivery_time'] ) ) : '';
		$countries = isset( $_POST['countries'] ) && is_array( $_POST['countries'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['countries'] ) ) : array();
		
		// Save zone data
		set_theme_mod( 'aqualuxe_shipping_rate_' . $zone, $rate_adjustment );
		set_theme_mod( 'aqualuxe_shipping_time_' . $zone, $delivery_time );
		
		// Save countries for zone
		if ( $zone !== 'rest_of_world' ) {
			update_option( 'aqualuxe_shipping_countries_' . $zone, $countries );
		}
		
		wp_send_json_success( array( 'message' => esc_html__( 'Zone saved successfully', 'aqualuxe' ) ) );
	}

	/**
	 * Enqueue admin scripts
	 *
	 * @param string $hook Hook suffix.
	 */
	public function enqueue_admin_scripts( $hook ) {
		// Only enqueue on shipping zones page
		if ( 'woocommerce_page_aqualuxe-shipping-zones' !== $hook ) {
			return;
		}
		
		// Enqueue Select2
		wp_enqueue_style( 'select2', WC()->plugin_url() . '/assets/css/select2.css', array(), WC_VERSION );
		wp_enqueue_script( 'select2', WC()->plugin_url() . '/assets/js/select2/select2.full.min.js', array( 'jquery' ), WC_VERSION, true );
		
		// Enqueue shipping zones script
		wp_enqueue_script(
			'aqualuxe-shipping-zones',
			AQUALUXE_URI . 'assets/js/admin/shipping-zones.js',
			array( 'jquery', 'select2' ),
			AQUALUXE_VERSION,
			true
		);
		
		// Localize script
		wp_localize_script(
			'aqualuxe-shipping-zones',
			'aqualuxeShippingZones',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'aqualuxe-shipping-zone-nonce' ),
				'i18n'    => array(
					'saving'     => esc_html__( 'Saving...', 'aqualuxe' ),
					'saveError'  => esc_html__( 'Error saving zone', 'aqualuxe' ),
					'saveSuccess' => esc_html__( 'Zone saved successfully', 'aqualuxe' ),
				),
			)
		);
	}

	/**
	 * Add admin styles
	 */
	public function add_admin_styles() {
		// Only add styles on shipping zones page
		$screen = get_current_screen();
		
		if ( ! $screen || 'woocommerce_page_aqualuxe-shipping-zones' !== $screen->id ) {
			return;
		}
		
		?>
		<style>
			.aqualuxe-shipping-zones-wrapper {
				margin-top: 20px;
			}
			
			.aqualuxe-shipping-zones-notice {
				margin-bottom: 20px;
			}
			
			.aqualuxe-shipping-zones-tab-nav {
				display: flex;
				margin: 0;
				padding: 0;
				list-style: none;
				border-bottom: 1px solid #ccc;
			}
			
			.aqualuxe-shipping-zones-tab-nav li {
				margin: 0;
			}
			
			.aqualuxe-shipping-zones-tab-nav a {
				display: block;
				padding: 10px 15px;
				text-decoration: none;
				color: #555;
				font-weight: 500;
				border: 1px solid transparent;
				border-bottom: none;
				margin-bottom: -1px;
			}
			
			.aqualuxe-shipping-zones-tab-nav a.active {
				border-color: #ccc;
				border-bottom-color: #f1f1f1;
				background-color: #f1f1f1;
				color: #000;
			}
			
			.aqualuxe-shipping-zone-tab {
				display: none;
				padding: 20px;
				border: 1px solid #ccc;
				border-top: none;
				background-color: #f1f1f1;
			}
			
			.aqualuxe-shipping-zone-tab.active {
				display: block;
			}
			
			.aqualuxe-shipping-zone-form .spinner {
				float: none;
				margin-top: 0;
				margin-left: 10px;
			}
			
			.aqualuxe-shipping-zone-countries {
				width: 400px;
				max-width: 100%;
			}
		</style>
		<?php
	}
}

// Initialize the class
new AquaLuxe_International_Shipping();