<?php
/**
 * WooCommerce Account Class
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
 * Account Class
 *
 * Handles account customization for WooCommerce.
 */
class Account {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Account page wrappers.
		add_action( 'woocommerce_before_account_navigation', array( $this, 'account_wrapper_start' ) );
		add_action( 'woocommerce_account_content', array( $this, 'account_content_wrapper_start' ), 5 );
		add_action( 'woocommerce_account_content', array( $this, 'account_content_wrapper_end' ), 999 );
		add_action( 'woocommerce_after_account_navigation', array( $this, 'account_wrapper_end' ) );
		
		// Account navigation.
		add_filter( 'woocommerce_account_menu_items', array( $this, 'customize_account_menu_items' ) );
		add_filter( 'woocommerce_account_menu_item_classes', array( $this, 'account_menu_item_classes' ), 10, 2 );
		add_action( 'woocommerce_before_account_navigation', array( $this, 'add_account_navigation_header' ) );
		
		// Dashboard customization.
		add_action( 'woocommerce_account_dashboard', array( $this, 'account_dashboard_content' ), 10 );
		
		// Orders customization.
		add_filter( 'woocommerce_my_account_my_orders_columns', array( $this, 'customize_orders_columns' ) );
		add_action( 'woocommerce_my_account_my_orders_column_order-status', array( $this, 'orders_status_column' ) );
		add_filter( 'woocommerce_my_account_my_orders_actions', array( $this, 'customize_order_actions' ), 10, 2 );
		
		// Downloads customization.
		add_filter( 'woocommerce_account_downloads_columns', array( $this, 'customize_downloads_columns' ) );
		
		// Addresses customization.
		add_filter( 'woocommerce_my_account_my_address_description', array( $this, 'customize_address_description' ) );
		add_filter( 'woocommerce_my_account_my_address_formatted_address', array( $this, 'customize_formatted_address' ), 10, 3 );
		add_action( 'woocommerce_before_edit_account_address_form', array( $this, 'before_edit_address_form' ) );
		
		// Account details customization.
		add_action( 'woocommerce_before_edit_account_form', array( $this, 'before_edit_account_form' ) );
		add_action( 'woocommerce_edit_account_form', array( $this, 'add_account_fields' ) );
		add_filter( 'woocommerce_save_account_details_required_fields', array( $this, 'customize_required_fields' ) );
		add_action( 'woocommerce_save_account_details', array( $this, 'save_account_fields' ) );
		
		// Login and registration forms.
		add_action( 'woocommerce_before_customer_login_form', array( $this, 'login_wrapper_start' ) );
		add_action( 'woocommerce_after_customer_login_form', array( $this, 'login_wrapper_end' ) );
		add_action( 'woocommerce_login_form_start', array( $this, 'login_form_start' ) );
		add_action( 'woocommerce_login_form_end', array( $this, 'login_form_end' ) );
		add_action( 'woocommerce_register_form_start', array( $this, 'register_form_start' ) );
		add_action( 'woocommerce_register_form_end', array( $this, 'register_form_end' ) );
		add_action( 'woocommerce_register_form', array( $this, 'add_registration_fields' ) );
		add_filter( 'woocommerce_registration_errors', array( $this, 'validate_registration_fields' ), 10, 3 );
		add_action( 'woocommerce_created_customer', array( $this, 'save_registration_fields' ) );
		
		// Social login.
		add_action( 'woocommerce_login_form_end', array( $this, 'add_social_login' ) );
		add_action( 'woocommerce_register_form_end', array( $this, 'add_social_login' ) );
		
		// Lost password form.
		add_action( 'woocommerce_before_lost_password_form', array( $this, 'lost_password_wrapper_start' ) );
		add_action( 'woocommerce_after_lost_password_form', array( $this, 'lost_password_wrapper_end' ) );
	}

	/**
	 * Account wrapper start
	 */
	public function account_wrapper_start() {
		echo '<div class="aqualuxe-account-wrapper">';
	}

	/**
	 * Account content wrapper start
	 */
	public function account_content_wrapper_start() {
		echo '<div class="aqualuxe-account-content-wrapper">';
	}

	/**
	 * Account content wrapper end
	 */
	public function account_content_wrapper_end() {
		echo '</div><!-- .aqualuxe-account-content-wrapper -->';
	}

	/**
	 * Account wrapper end
	 */
	public function account_wrapper_end() {
		echo '</div><!-- .aqualuxe-account-wrapper -->';
	}

	/**
	 * Customize account menu items
	 *
	 * @param array $items Menu items.
	 * @return array Modified menu items.
	 */
	public function customize_account_menu_items( $items ) {
		// Rename "Dashboard" to "Account Overview".
		$items['dashboard'] = esc_html__( 'Account Overview', 'aqualuxe' );
		
		// Add "Wishlist" item.
		$items['wishlist'] = esc_html__( 'Wishlist', 'aqualuxe' );
		
		// Reorder items.
		$new_items = array();
		
		// Define the order of items.
		$order = array(
			'dashboard',
			'orders',
			'downloads',
			'wishlist',
			'edit-address',
			'edit-account',
			'customer-logout',
		);
		
		// Add items in the specified order.
		foreach ( $order as $key ) {
			if ( isset( $items[ $key ] ) ) {
				$new_items[ $key ] = $items[ $key ];
			}
		}
		
		// Add any remaining items.
		foreach ( $items as $key => $value ) {
			if ( ! isset( $new_items[ $key ] ) ) {
				$new_items[ $key ] = $value;
			}
		}
		
		return $new_items;
	}

	/**
	 * Account menu item classes
	 *
	 * @param array  $classes Item classes.
	 * @param string $endpoint Endpoint.
	 * @return array Modified item classes.
	 */
	public function account_menu_item_classes( $classes, $endpoint ) {
		// Add icon classes based on endpoint.
		$icons = array(
			'dashboard'      => 'fas fa-tachometer-alt',
			'orders'         => 'fas fa-shopping-bag',
			'downloads'      => 'fas fa-download',
			'wishlist'       => 'far fa-heart',
			'edit-address'   => 'fas fa-map-marker-alt',
			'edit-account'   => 'fas fa-user-edit',
			'customer-logout' => 'fas fa-sign-out-alt',
		);
		
		if ( isset( $icons[ $endpoint ] ) ) {
			$classes[] = 'aqualuxe-menu-item-has-icon';
			$classes[] = 'aqualuxe-menu-item-icon-' . $endpoint;
		}
		
		return $classes;
	}

	/**
	 * Add account navigation header
	 */
	public function add_account_navigation_header() {
		$current_user = wp_get_current_user();
		
		if ( $current_user->ID > 0 ) {
			?>
			<div class="aqualuxe-account-user">
				<div class="aqualuxe-account-avatar">
					<?php echo get_avatar( $current_user->ID, 60 ); ?>
				</div>
				<div class="aqualuxe-account-user-info">
					<h3 class="aqualuxe-account-username">
						<?php echo esc_html( $current_user->display_name ); ?>
					</h3>
					<span class="aqualuxe-account-email">
						<?php echo esc_html( $current_user->user_email ); ?>
					</span>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Account dashboard content
	 */
	public function account_dashboard_content() {
		$current_user = wp_get_current_user();
		
		// Get customer orders.
		$customer_orders = wc_get_orders( array(
			'customer' => $current_user->ID,
			'limit'    => 5,
		) );
		
		// Get customer downloads.
		$customer_downloads = wc_get_customer_available_downloads( $current_user->ID );
		
		?>
		<div class="aqualuxe-dashboard-welcome">
			<h2>
				<?php
				/* translators: %s: customer first name */
				printf( esc_html__( 'Welcome back, %s!', 'aqualuxe' ), esc_html( $current_user->first_name ) );
				?>
			</h2>
			<p><?php esc_html_e( 'From your account dashboard you can view your recent orders, manage your shipping and billing addresses, and edit your password and account details.', 'aqualuxe' ); ?></p>
		</div>
		
		<div class="aqualuxe-dashboard-cards">
			<div class="aqualuxe-dashboard-card">
				<div class="aqualuxe-dashboard-card-header">
					<h3><?php esc_html_e( 'Recent Orders', 'aqualuxe' ); ?></h3>
					<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" class="aqualuxe-dashboard-card-link">
						<?php esc_html_e( 'View All', 'aqualuxe' ); ?>
					</a>
				</div>
				<div class="aqualuxe-dashboard-card-content">
					<?php if ( ! empty( $customer_orders ) ) : ?>
						<ul class="aqualuxe-dashboard-orders">
							<?php foreach ( $customer_orders as $customer_order ) : ?>
								<li class="aqualuxe-dashboard-order">
									<div class="aqualuxe-dashboard-order-number">
										<a href="<?php echo esc_url( $customer_order->get_view_order_url() ); ?>">
											<?php echo esc_html( $customer_order->get_order_number() ); ?>
										</a>
									</div>
									<div class="aqualuxe-dashboard-order-date">
										<?php echo esc_html( wc_format_datetime( $customer_order->get_date_created() ) ); ?>
									</div>
									<div class="aqualuxe-dashboard-order-status">
										<?php echo wc_get_order_status_name( $customer_order->get_status() ); ?>
									</div>
									<div class="aqualuxe-dashboard-order-total">
										<?php echo wp_kses_post( $customer_order->get_formatted_order_total() ); ?>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php else : ?>
						<p class="aqualuxe-dashboard-no-orders">
							<?php esc_html_e( 'No order has been made yet.', 'aqualuxe' ); ?>
							<a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
								<?php esc_html_e( 'Browse products', 'aqualuxe' ); ?>
							</a>
						</p>
					<?php endif; ?>
				</div>
			</div>
			
			<div class="aqualuxe-dashboard-card">
				<div class="aqualuxe-dashboard-card-header">
					<h3><?php esc_html_e( 'Account Details', 'aqualuxe' ); ?></h3>
					<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-account' ) ); ?>" class="aqualuxe-dashboard-card-link">
						<?php esc_html_e( 'Edit', 'aqualuxe' ); ?>
					</a>
				</div>
				<div class="aqualuxe-dashboard-card-content">
					<div class="aqualuxe-dashboard-account-details">
						<p>
							<?php echo esc_html( $current_user->first_name . ' ' . $current_user->last_name ); ?><br>
							<?php echo esc_html( $current_user->user_email ); ?>
						</p>
					</div>
				</div>
			</div>
			
			<div class="aqualuxe-dashboard-card">
				<div class="aqualuxe-dashboard-card-header">
					<h3><?php esc_html_e( 'Shipping Address', 'aqualuxe' ); ?></h3>
					<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) . 'shipping' ); ?>" class="aqualuxe-dashboard-card-link">
						<?php esc_html_e( 'Edit', 'aqualuxe' ); ?>
					</a>
				</div>
				<div class="aqualuxe-dashboard-card-content">
					<div class="aqualuxe-dashboard-address">
						<?php
						$shipping_address = wc_get_account_formatted_address( 'shipping' );
						if ( ! empty( $shipping_address ) ) {
							echo wp_kses_post( $shipping_address );
						} else {
							esc_html_e( 'You have not set up a shipping address yet.', 'aqualuxe' );
						}
						?>
					</div>
				</div>
			</div>
			
			<div class="aqualuxe-dashboard-card">
				<div class="aqualuxe-dashboard-card-header">
					<h3><?php esc_html_e( 'Billing Address', 'aqualuxe' ); ?></h3>
					<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) . 'billing' ); ?>" class="aqualuxe-dashboard-card-link">
						<?php esc_html_e( 'Edit', 'aqualuxe' ); ?>
					</a>
				</div>
				<div class="aqualuxe-dashboard-card-content">
					<div class="aqualuxe-dashboard-address">
						<?php
						$billing_address = wc_get_account_formatted_address( 'billing' );
						if ( ! empty( $billing_address ) ) {
							echo wp_kses_post( $billing_address );
						} else {
							esc_html_e( 'You have not set up a billing address yet.', 'aqualuxe' );
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Customize orders columns
	 *
	 * @param array $columns Order columns.
	 * @return array Modified order columns.
	 */
	public function customize_orders_columns( $columns ) {
		// Rename "Order" to "Order Number".
		$columns['order-number'] = esc_html__( 'Order Number', 'aqualuxe' );
		
		// Rename "Status" to "Order Status".
		$columns['order-status'] = esc_html__( 'Order Status', 'aqualuxe' );
		
		// Add "Items" column.
		$new_columns = array();
		
		foreach ( $columns as $key => $value ) {
			$new_columns[ $key ] = $value;
			
			// Add "Items" column after "Order" column.
			if ( 'order-number' === $key ) {
				$new_columns['order-items'] = esc_html__( 'Items', 'aqualuxe' );
			}
		}
		
		return $new_columns;
	}

	/**
	 * Orders status column
	 *
	 * @param object $order Order object.
	 */
	public function orders_status_column( $order ) {
		$status = $order->get_status();
		$status_name = wc_get_order_status_name( $status );
		
		echo '<span class="aqualuxe-order-status status-' . esc_attr( $status ) . '">' . esc_html( $status_name ) . '</span>';
	}

	/**
	 * Customize order actions
	 *
	 * @param array  $actions Order actions.
	 * @param object $order Order object.
	 * @return array Modified order actions.
	 */
	public function customize_order_actions( $actions, $order ) {
		// Add "Track" action for processing and completed orders.
		if ( $order->has_status( array( 'processing', 'completed' ) ) ) {
			$actions['track'] = array(
				'url'  => '#', // This would be a tracking URL in a real implementation.
				'name' => esc_html__( 'Track', 'aqualuxe' ),
			);
		}
		
		// Add "Reorder" action for completed orders.
		if ( $order->has_status( 'completed' ) ) {
			$actions['reorder'] = array(
				'url'  => wp_nonce_url( add_query_arg( 'order_again', $order->get_id(), wc_get_cart_url() ), 'woocommerce-order_again' ),
				'name' => esc_html__( 'Reorder', 'aqualuxe' ),
			);
		}
		
		return $actions;
	}

	/**
	 * Customize downloads columns
	 *
	 * @param array $columns Download columns.
	 * @return array Modified download columns.
	 */
	public function customize_downloads_columns( $columns ) {
		// Add "Size" column.
		$columns['download-size'] = esc_html__( 'Size', 'aqualuxe' );
		
		return $columns;
	}

	/**
	 * Customize address description
	 *
	 * @param string $description Address description.
	 * @return string Modified address description.
	 */
	public function customize_address_description( $description ) {
		return esc_html__( 'The following addresses will be used on your orders and for shipping and billing purposes.', 'aqualuxe' );
	}

	/**
	 * Customize formatted address
	 *
	 * @param array  $address Formatted address.
	 * @param string $customer_id Customer ID.
	 * @param string $type Address type.
	 * @return array Modified formatted address.
	 */
	public function customize_formatted_address( $address, $customer_id, $type ) {
		// Add a phone number to the formatted address if available.
		if ( 'billing' === $type ) {
			$phone = get_user_meta( $customer_id, 'billing_phone', true );
			if ( $phone ) {
				$address['phone'] = $phone;
			}
		}
		
		return $address;
	}

	/**
	 * Before edit address form
	 */
	public function before_edit_address_form() {
		echo '<div class="aqualuxe-edit-address-intro">';
		echo '<p>' . esc_html__( 'The following addresses will be used on your orders. You can edit them anytime.', 'aqualuxe' ) . '</p>';
		echo '</div>';
	}

	/**
	 * Before edit account form
	 */
	public function before_edit_account_form() {
		echo '<div class="aqualuxe-edit-account-intro">';
		echo '<p>' . esc_html__( 'Edit your account details below or change your password.', 'aqualuxe' ) . '</p>';
		echo '</div>';
	}

	/**
	 * Add account fields
	 */
	public function add_account_fields() {
		$user_id = get_current_user_id();
		$phone = get_user_meta( $user_id, 'billing_phone', true );
		$birth_date = get_user_meta( $user_id, 'birth_date', true );
		
		?>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="billing_phone"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></label>
			<input type="tel" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_phone" id="billing_phone" value="<?php echo esc_attr( $phone ); ?>" />
		</p>
		
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="birth_date"><?php esc_html_e( 'Birth Date', 'aqualuxe' ); ?></label>
			<input type="date" class="woocommerce-Input woocommerce-Input--text input-text" name="birth_date" id="birth_date" value="<?php echo esc_attr( $birth_date ); ?>" />
		</p>
		
		<fieldset class="aqualuxe-account-preferences">
			<legend><?php esc_html_e( 'Communication Preferences', 'aqualuxe' ); ?></legend>
			
			<p class="woocommerce-form-row form-row">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
					<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox" name="marketing_opt_in" id="marketing_opt_in" value="1" <?php checked( get_user_meta( $user_id, 'marketing_opt_in', true ), '1' ); ?> />
					<span><?php esc_html_e( 'I want to receive marketing communications about products, services and promotions.', 'aqualuxe' ); ?></span>
				</label>
			</p>
		</fieldset>
		<?php
	}

	/**
	 * Customize required fields
	 *
	 * @param array $fields Required fields.
	 * @return array Modified required fields.
	 */
	public function customize_required_fields( $fields ) {
		// Make last name required.
		$fields['account_last_name'] = esc_html__( 'Last name', 'aqualuxe' );
		
		return $fields;
	}

	/**
	 * Save account fields
	 *
	 * @param int $user_id User ID.
	 */
	public function save_account_fields( $user_id ) {
		// Save phone number.
		if ( isset( $_POST['billing_phone'] ) ) {
			update_user_meta( $user_id, 'billing_phone', sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) );
		}
		
		// Save birth date.
		if ( isset( $_POST['birth_date'] ) ) {
			update_user_meta( $user_id, 'birth_date', sanitize_text_field( wp_unslash( $_POST['birth_date'] ) ) );
		}
		
		// Save marketing preference.
		$marketing_opt_in = isset( $_POST['marketing_opt_in'] ) ? '1' : '0';
		update_user_meta( $user_id, 'marketing_opt_in', $marketing_opt_in );
	}

	/**
	 * Login wrapper start
	 */
	public function login_wrapper_start() {
		echo '<div class="aqualuxe-login-register-wrapper">';
	}

	/**
	 * Login wrapper end
	 */
	public function login_wrapper_end() {
		echo '</div><!-- .aqualuxe-login-register-wrapper -->';
	}

	/**
	 * Login form start
	 */
	public function login_form_start() {
		echo '<div class="aqualuxe-login-form-wrapper">';
		echo '<h3>' . esc_html__( 'Login to Your Account', 'aqualuxe' ) . '</h3>';
	}

	/**
	 * Login form end
	 */
	public function login_form_end() {
		echo '</div><!-- .aqualuxe-login-form-wrapper -->';
	}

	/**
	 * Register form start
	 */
	public function register_form_start() {
		echo '<div class="aqualuxe-register-form-wrapper">';
		echo '<h3>' . esc_html__( 'Create an Account', 'aqualuxe' ) . '</h3>';
		echo '<p>' . esc_html__( 'Register to enjoy a personalized shopping experience, order tracking, and faster checkout.', 'aqualuxe' ) . '</p>';
	}

	/**
	 * Register form end
	 */
	public function register_form_end() {
		echo '</div><!-- .aqualuxe-register-form-wrapper -->';
	}

	/**
	 * Add registration fields
	 */
	public function add_registration_fields() {
		?>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="reg_first_name"><?php esc_html_e( 'First name', 'aqualuxe' ); ?> <span class="required">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="first_name" id="reg_first_name" value="<?php echo ( ! empty( $_POST['first_name'] ) ) ? esc_attr( wp_unslash( $_POST['first_name'] ) ) : ''; ?>" required />
		</p>
		
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="reg_last_name"><?php esc_html_e( 'Last name', 'aqualuxe' ); ?> <span class="required">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="last_name" id="reg_last_name" value="<?php echo ( ! empty( $_POST['last_name'] ) ) ? esc_attr( wp_unslash( $_POST['last_name'] ) ) : ''; ?>" required />
		</p>
		
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="reg_billing_phone"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></label>
			<input type="tel" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_phone" id="reg_billing_phone" value="<?php echo ( ! empty( $_POST['billing_phone'] ) ) ? esc_attr( wp_unslash( $_POST['billing_phone'] ) ) : ''; ?>" />
		</p>
		
		<div class="woocommerce-privacy-policy-text">
			<p><?php esc_html_e( 'Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our privacy policy.', 'aqualuxe' ); ?></p>
		</div>
		
		<p class="woocommerce-form-row form-row">
			<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
				<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox" name="marketing_opt_in" id="marketing_opt_in" value="1" />
				<span><?php esc_html_e( 'I want to receive marketing communications about products, services and promotions.', 'aqualuxe' ); ?></span>
			</label>
		</p>
		<?php
	}

	/**
	 * Validate registration fields
	 *
	 * @param object $errors Validation errors.
	 * @param string $username Username.
	 * @param string $email Email.
	 * @return object Modified validation errors.
	 */
	public function validate_registration_fields( $errors, $username, $email ) {
		if ( empty( $_POST['first_name'] ) ) {
			$errors->add( 'first_name_error', esc_html__( 'First name is required.', 'aqualuxe' ) );
		}
		
		if ( empty( $_POST['last_name'] ) ) {
			$errors->add( 'last_name_error', esc_html__( 'Last name is required.', 'aqualuxe' ) );
		}
		
		return $errors;
	}

	/**
	 * Save registration fields
	 *
	 * @param int $customer_id Customer ID.
	 */
	public function save_registration_fields( $customer_id ) {
		if ( isset( $_POST['first_name'] ) ) {
			update_user_meta( $customer_id, 'first_name', sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) );
			update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) );
			update_user_meta( $customer_id, 'shipping_first_name', sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) );
		}
		
		if ( isset( $_POST['last_name'] ) ) {
			update_user_meta( $customer_id, 'last_name', sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) );
			update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) );
			update_user_meta( $customer_id, 'shipping_last_name', sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) );
		}
		
		if ( isset( $_POST['billing_phone'] ) ) {
			update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) );
		}
		
		// Save marketing preference.
		$marketing_opt_in = isset( $_POST['marketing_opt_in'] ) ? '1' : '0';
		update_user_meta( $customer_id, 'marketing_opt_in', $marketing_opt_in );
	}

	/**
	 * Add social login
	 */
	public function add_social_login() {
		// This would be integrated with actual social login providers in a real implementation.
		?>
		<div class="aqualuxe-social-login">
			<div class="aqualuxe-social-login-divider">
				<span><?php esc_html_e( 'Or continue with', 'aqualuxe' ); ?></span>
			</div>
			<div class="aqualuxe-social-login-buttons">
				<a href="#" class="aqualuxe-social-login-button facebook">
					<i class="fab fa-facebook-f"></i>
					<span><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></span>
				</a>
				<a href="#" class="aqualuxe-social-login-button google">
					<i class="fab fa-google"></i>
					<span><?php esc_html_e( 'Google', 'aqualuxe' ); ?></span>
				</a>
				<a href="#" class="aqualuxe-social-login-button apple">
					<i class="fab fa-apple"></i>
					<span><?php esc_html_e( 'Apple', 'aqualuxe' ); ?></span>
				</a>
			</div>
		</div>
		<?php
	}

	/**
	 * Lost password wrapper start
	 */
	public function lost_password_wrapper_start() {
		echo '<div class="aqualuxe-lost-password-wrapper">';
		echo '<h2>' . esc_html__( 'Reset Password', 'aqualuxe' ) . '</h2>';
		echo '<p>' . esc_html__( 'Enter your email address and we\'ll send you a link to reset your password.', 'aqualuxe' ) . '</p>';
	}

	/**
	 * Lost password wrapper end
	 */
	public function lost_password_wrapper_end() {
		echo '<div class="aqualuxe-lost-password-actions">';
		echo '<a href="' . esc_url( wc_get_page_permalink( 'myaccount' ) ) . '" class="button">';
		echo esc_html__( 'Back to Login', 'aqualuxe' );
		echo '</a>';
		echo '</div>';
		echo '</div><!-- .aqualuxe-lost-password-wrapper -->';
	}
}

// Initialize the class.
new Account();