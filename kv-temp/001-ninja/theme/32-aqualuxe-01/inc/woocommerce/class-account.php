<?php
/**
 * AquaLuxe WooCommerce Account Class
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
 * WooCommerce Account Class
 *
 * Handles WooCommerce account customizations and enhancements.
 *
 * @since 1.1.0
 */
class Account {

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

		// Account menu.
		add_filter( 'woocommerce_account_menu_items', array( $this, 'account_menu_items' ), 10 );
		add_filter( 'woocommerce_account_menu_item_classes', array( $this, 'account_menu_item_classes' ), 10, 2 );
		
		// Account endpoints.
		add_action( 'init', array( $this, 'add_endpoints' ) );
		add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );
		
		// Account dashboard.
		add_action( 'woocommerce_account_dashboard', array( $this, 'account_dashboard' ), 10 );
		
		// Account orders.
		add_filter( 'woocommerce_my_account_my_orders_columns', array( $this, 'orders_columns' ), 10 );
		add_action( 'woocommerce_my_account_my_orders_column_order-actions', array( $this, 'orders_actions_column' ), 10 );
		
		// Account downloads.
		add_filter( 'woocommerce_account_downloads_columns', array( $this, 'downloads_columns' ), 10 );
		
		// Account addresses.
		add_filter( 'woocommerce_my_account_my_address_description', array( $this, 'address_description' ), 10 );
		add_filter( 'woocommerce_my_account_get_addresses', array( $this, 'get_addresses' ), 10, 1 );
		add_filter( 'woocommerce_address_to_edit', array( $this, 'address_to_edit' ), 10, 2 );
		
		// Account payment methods.
		add_filter( 'woocommerce_payment_methods_list_item', array( $this, 'payment_methods_list_item' ), 10, 2 );
		add_filter( 'woocommerce_payment_methods_list_item_actions', array( $this, 'payment_methods_list_item_actions' ), 10, 2 );
		
		// Account wishlist.
		add_action( 'init', array( $this, 'add_wishlist_endpoint' ) );
		add_filter( 'query_vars', array( $this, 'add_wishlist_query_var' ), 0 );
		add_action( 'woocommerce_account_wishlist_endpoint', array( $this, 'wishlist_content' ) );
		
		// Account layout.
		add_action( 'woocommerce_before_account_navigation', array( $this, 'account_navigation_wrapper_open' ), 5 );
		add_action( 'woocommerce_after_account_navigation', array( $this, 'account_navigation_wrapper_close' ), 15 );
		add_action( 'woocommerce_account_navigation', array( $this, 'account_user_info' ), 5 );
		add_action( 'woocommerce_account_content', array( $this, 'account_content_wrapper_open' ), 5 );
		add_action( 'woocommerce_account_content', array( $this, 'account_content_wrapper_close' ), 15 );
		
		// Account login/register.
		add_action( 'woocommerce_before_customer_login_form', array( $this, 'login_register_wrapper_open' ), 5 );
		add_action( 'woocommerce_after_customer_login_form', array( $this, 'login_register_wrapper_close' ), 15 );
		add_action( 'woocommerce_login_form_start', array( $this, 'login_form_start' ), 10 );
		add_action( 'woocommerce_login_form_end', array( $this, 'login_form_end' ), 10 );
		add_action( 'woocommerce_register_form_start', array( $this, 'register_form_start' ), 10 );
		add_action( 'woocommerce_register_form_end', array( $this, 'register_form_end' ), 10 );
		add_filter( 'woocommerce_registration_errors', array( $this, 'registration_errors' ), 10, 3 );
		
		// Account social login.
		add_action( 'woocommerce_login_form', array( $this, 'social_login' ), 10 );
		add_action( 'woocommerce_register_form', array( $this, 'social_login' ), 10 );
		
		// Account lost password.
		add_action( 'woocommerce_before_lost_password_form', array( $this, 'lost_password_wrapper_open' ), 5 );
		add_action( 'woocommerce_after_lost_password_form', array( $this, 'lost_password_wrapper_close' ), 15 );
		
		// Account reset password.
		add_action( 'woocommerce_before_reset_password_form', array( $this, 'reset_password_wrapper_open' ), 5 );
		add_action( 'woocommerce_after_reset_password_form', array( $this, 'reset_password_wrapper_close' ), 15 );
	}

	/**
	 * Account menu items.
	 *
	 * @param array $items Menu items.
	 * @return array
	 */
	public function account_menu_items( $items ) {
		// Reorder items.
		$ordered_items = array();
		
		// Dashboard.
		if ( isset( $items['dashboard'] ) ) {
			$ordered_items['dashboard'] = $items['dashboard'];
		}
		
		// Orders.
		if ( isset( $items['orders'] ) ) {
			$ordered_items['orders'] = $items['orders'];
		}
		
		// Downloads.
		if ( isset( $items['downloads'] ) ) {
			$ordered_items['downloads'] = $items['downloads'];
		}
		
		// Addresses.
		if ( isset( $items['edit-address'] ) ) {
			$ordered_items['edit-address'] = $items['edit-address'];
		}
		
		// Payment methods.
		if ( isset( $items['payment-methods'] ) ) {
			$ordered_items['payment-methods'] = $items['payment-methods'];
		}
		
		// Wishlist.
		if ( get_theme_mod( 'aqualuxe_enable_wishlist', true ) ) {
			$ordered_items['wishlist'] = esc_html__( 'Wishlist', 'aqualuxe' );
		}
		
		// Account details.
		if ( isset( $items['edit-account'] ) ) {
			$ordered_items['edit-account'] = $items['edit-account'];
		}
		
		// Logout.
		if ( isset( $items['customer-logout'] ) ) {
			$ordered_items['customer-logout'] = $items['customer-logout'];
		}
		
		return $ordered_items;
	}

	/**
	 * Account menu item classes.
	 *
	 * @param array  $classes Classes.
	 * @param string $endpoint Endpoint.
	 * @return array
	 */
	public function account_menu_item_classes( $classes, $endpoint ) {
		// Get menu item icons.
		$icons = array(
			'dashboard'       => 'tachometer-alt',
			'orders'          => 'shopping-bag',
			'downloads'       => 'download',
			'edit-address'    => 'map-marker-alt',
			'payment-methods' => 'credit-card',
			'wishlist'        => 'heart',
			'edit-account'    => 'user',
			'customer-logout' => 'sign-out-alt',
		);
		
		// Check if menu item icons are enabled.
		if ( get_theme_mod( 'aqualuxe_account_menu_icons', true ) ) {
			// Add icon class.
			$classes[] = 'has-icon';
			
			// Add icon data attribute.
			if ( isset( $icons[ $endpoint ] ) ) {
				$classes[] = 'icon-' . $icons[ $endpoint ];
			}
		}
		
		return $classes;
	}

	/**
	 * Add endpoints.
	 *
	 * @return void
	 */
	public function add_endpoints() {
		// Add custom endpoints.
		add_rewrite_endpoint( 'wishlist', EP_ROOT | EP_PAGES );
	}

	/**
	 * Add query vars.
	 *
	 * @param array $vars Query vars.
	 * @return array
	 */
	public function add_query_vars( $vars ) {
		// Add custom query vars.
		$vars[] = 'wishlist';
		
		return $vars;
	}

	/**
	 * Account dashboard.
	 *
	 * @return void
	 */
	public function account_dashboard() {
		// Check if dashboard should be customized.
		if ( ! get_theme_mod( 'aqualuxe_account_dashboard_custom', false ) ) {
			return;
		}
		
		// Get dashboard style.
		$dashboard_style = get_theme_mod( 'aqualuxe_account_dashboard_style', 'standard' );
		
		if ( 'enhanced' === $dashboard_style ) {
			// Get dashboard template.
			get_template_part( 'template-parts/woocommerce/account', 'dashboard' );
		}
	}

	/**
	 * Orders columns.
	 *
	 * @param array $columns Columns.
	 * @return array
	 */
	public function orders_columns( $columns ) {
		// Check if orders columns should be customized.
		if ( ! get_theme_mod( 'aqualuxe_account_orders_custom', false ) ) {
			return $columns;
		}
		
		// Get orders columns.
		$custom_columns = get_theme_mod( 'aqualuxe_account_orders_columns', array() );
		
		if ( ! empty( $custom_columns ) ) {
			// Reset columns.
			$columns = array();
			
			// Add custom columns.
			foreach ( $custom_columns as $key => $column ) {
				$columns[ $key ] = $column['title'];
			}
		}
		
		return $columns;
	}

	/**
	 * Orders actions column.
	 *
	 * @param WC_Order $order Order object.
	 * @return void
	 */
	public function orders_actions_column( $order ) {
		// Check if orders actions should be customized.
		if ( ! get_theme_mod( 'aqualuxe_account_orders_actions_custom', false ) ) {
			return;
		}
		
		// Get orders actions.
		$actions = array(
			'view'   => array(
				'url'  => $order->get_view_order_url(),
				'name' => esc_html__( 'View', 'aqualuxe' ),
				'icon' => 'eye',
			),
			'pay'    => array(
				'url'  => $order->get_checkout_payment_url(),
				'name' => esc_html__( 'Pay', 'aqualuxe' ),
				'icon' => 'credit-card',
			),
			'cancel' => array(
				'url'  => $order->get_cancel_order_url( wc_get_page_permalink( 'myaccount' ) ),
				'name' => esc_html__( 'Cancel', 'aqualuxe' ),
				'icon' => 'times',
			),
		);
		
		// Check if order needs payment.
		if ( ! $order->needs_payment() ) {
			unset( $actions['pay'] );
		}
		
		// Check if order can be cancelled.
		if ( ! $order->current_user_can( 'cancel_order' ) ) {
			unset( $actions['cancel'] );
		}
		
		// Output actions.
		echo '<div class="order-actions">';
		
		foreach ( $actions as $key => $action ) {
			echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . esc_attr( $key ) . '">';
			echo '<i class="fas fa-' . esc_attr( $action['icon'] ) . '"></i>';
			echo '<span>' . esc_html( $action['name'] ) . '</span>';
			echo '</a>';
		}
		
		echo '</div>';
	}

	/**
	 * Downloads columns.
	 *
	 * @param array $columns Columns.
	 * @return array
	 */
	public function downloads_columns( $columns ) {
		// Check if downloads columns should be customized.
		if ( ! get_theme_mod( 'aqualuxe_account_downloads_custom', false ) ) {
			return $columns;
		}
		
		// Get downloads columns.
		$custom_columns = get_theme_mod( 'aqualuxe_account_downloads_columns', array() );
		
		if ( ! empty( $custom_columns ) ) {
			// Reset columns.
			$columns = array();
			
			// Add custom columns.
			foreach ( $custom_columns as $key => $column ) {
				$columns[ $key ] = $column['title'];
			}
		}
		
		return $columns;
	}

	/**
	 * Address description.
	 *
	 * @param string $description Description.
	 * @return string
	 */
	public function address_description( $description ) {
		// Check if address description should be customized.
		if ( ! get_theme_mod( 'aqualuxe_account_address_custom', false ) ) {
			return $description;
		}
		
		// Get address description.
		$custom_description = get_theme_mod( 'aqualuxe_account_address_description', '' );
		
		if ( ! empty( $custom_description ) ) {
			$description = $custom_description;
		}
		
		return $description;
	}

	/**
	 * Get addresses.
	 *
	 * @param array $addresses Addresses.
	 * @return array
	 */
	public function get_addresses( $addresses ) {
		// Check if addresses should be customized.
		if ( ! get_theme_mod( 'aqualuxe_account_address_custom', false ) ) {
			return $addresses;
		}
		
		// Get address labels.
		$billing_label = get_theme_mod( 'aqualuxe_account_address_billing_label', '' );
		$shipping_label = get_theme_mod( 'aqualuxe_account_address_shipping_label', '' );
		
		if ( ! empty( $billing_label ) ) {
			$addresses['billing'] = $billing_label;
		}
		
		if ( ! empty( $shipping_label ) ) {
			$addresses['shipping'] = $shipping_label;
		}
		
		return $addresses;
	}

	/**
	 * Address to edit.
	 *
	 * @param array  $address Address.
	 * @param string $load_address Address type.
	 * @return array
	 */
	public function address_to_edit( $address, $load_address ) {
		// Check if address fields should be customized.
		if ( ! get_theme_mod( 'aqualuxe_account_address_custom', false ) ) {
			return $address;
		}
		
		// Get address fields.
		$custom_fields = get_theme_mod( 'aqualuxe_account_address_fields', array() );
		
		if ( ! empty( $custom_fields ) ) {
			// Customize fields.
			foreach ( $custom_fields as $key => $field ) {
				if ( isset( $address[ $load_address . '_' . $key ] ) ) {
					$address[ $load_address . '_' . $key ]['label'] = $field['label'];
					$address[ $load_address . '_' . $key ]['placeholder'] = $field['placeholder'];
					$address[ $load_address . '_' . $key ]['required'] = $field['required'];
				}
			}
		}
		
		return $address;
	}

	/**
	 * Payment methods list item.
	 *
	 * @param array    $item Payment method.
	 * @param WC_Payment_Token $payment_token Payment token.
	 * @return array
	 */
	public function payment_methods_list_item( $item, $payment_token ) {
		// Check if payment methods should be customized.
		if ( ! get_theme_mod( 'aqualuxe_account_payment_methods_custom', false ) ) {
			return $item;
		}
		
		// Get payment method style.
		$payment_method_style = get_theme_mod( 'aqualuxe_account_payment_methods_style', 'standard' );
		
		if ( 'enhanced' === $payment_method_style ) {
			// Add card type.
			if ( 'WC_Payment_Token_CC' === get_class( $payment_token ) ) {
				$item['card_type'] = $payment_token->get_card_type();
			}
			
			// Add expiry date.
			if ( 'WC_Payment_Token_CC' === get_class( $payment_token ) ) {
				$item['expiry'] = $payment_token->get_expiry_month() . '/' . substr( $payment_token->get_expiry_year(), -2 );
			}
		}
		
		return $item;
	}

	/**
	 * Payment methods list item actions.
	 *
	 * @param array    $actions Actions.
	 * @param WC_Payment_Token $payment_token Payment token.
	 * @return array
	 */
	public function payment_methods_list_item_actions( $actions, $payment_token ) {
		// Check if payment methods should be customized.
		if ( ! get_theme_mod( 'aqualuxe_account_payment_methods_custom', false ) ) {
			return $actions;
		}
		
		// Get payment method style.
		$payment_method_style = get_theme_mod( 'aqualuxe_account_payment_methods_style', 'standard' );
		
		if ( 'enhanced' === $payment_method_style ) {
			// Add icons to actions.
			if ( isset( $actions['delete'] ) ) {
				$actions['delete'] = str_replace( '>', '><i class="fas fa-trash-alt"></i> ', $actions['delete'] );
			}
			
			if ( isset( $actions['default'] ) ) {
				$actions['default'] = str_replace( '>', '><i class="fas fa-check"></i> ', $actions['default'] );
			}
		}
		
		return $actions;
	}

	/**
	 * Add wishlist endpoint.
	 *
	 * @return void
	 */
	public function add_wishlist_endpoint() {
		// Check if wishlist is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_wishlist', true ) ) {
			return;
		}
		
		// Add wishlist endpoint.
		add_rewrite_endpoint( 'wishlist', EP_ROOT | EP_PAGES );
	}

	/**
	 * Add wishlist query var.
	 *
	 * @param array $vars Query vars.
	 * @return array
	 */
	public function add_wishlist_query_var( $vars ) {
		// Check if wishlist is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_wishlist', true ) ) {
			return $vars;
		}
		
		// Add wishlist query var.
		$vars[] = 'wishlist';
		
		return $vars;
	}

	/**
	 * Wishlist content.
	 *
	 * @return void
	 */
	public function wishlist_content() {
		// Check if wishlist is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_wishlist', true ) ) {
			return;
		}
		
		// Get wishlist template.
		wc_get_template( 'wishlist.php' );
	}

	/**
	 * Account navigation wrapper open.
	 *
	 * @return void
	 */
	public function account_navigation_wrapper_open() {
		// Get account layout.
		$account_layout = get_theme_mod( 'aqualuxe_account_layout', 'standard' );
		
		echo '<div class="account-navigation-wrapper layout-' . esc_attr( $account_layout ) . '">';
	}

	/**
	 * Account navigation wrapper close.
	 *
	 * @return void
	 */
	public function account_navigation_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Account user info.
	 *
	 * @return void
	 */
	public function account_user_info() {
		// Check if user info should be shown.
		if ( ! get_theme_mod( 'aqualuxe_account_user_info', true ) ) {
			return;
		}
		
		// Get current user.
		$current_user = wp_get_current_user();
		
		// Output user info.
		echo '<div class="account-user-info">';
		
		// Output avatar.
		echo '<div class="account-user-avatar">';
		echo get_avatar( $current_user->ID, 96 );
		echo '</div>';
		
		// Output name.
		echo '<div class="account-user-name">';
		echo '<h2>' . esc_html( $current_user->display_name ) . '</h2>';
		echo '</div>';
		
		// Output email.
		echo '<div class="account-user-email">';
		echo '<p>' . esc_html( $current_user->user_email ) . '</p>';
		echo '</div>';
		
		echo '</div>';
	}

	/**
	 * Account content wrapper open.
	 *
	 * @return void
	 */
	public function account_content_wrapper_open() {
		echo '<div class="account-content-wrapper">';
	}

	/**
	 * Account content wrapper close.
	 *
	 * @return void
	 */
	public function account_content_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Login register wrapper open.
	 *
	 * @return void
	 */
	public function login_register_wrapper_open() {
		// Get login register layout.
		$login_register_layout = get_theme_mod( 'aqualuxe_account_login_register_layout', 'standard' );
		
		echo '<div class="login-register-wrapper layout-' . esc_attr( $login_register_layout ) . '">';
	}

	/**
	 * Login register wrapper close.
	 *
	 * @return void
	 */
	public function login_register_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Login form start.
	 *
	 * @return void
	 */
	public function login_form_start() {
		// Check if login form should be customized.
		if ( ! get_theme_mod( 'aqualuxe_account_login_custom', false ) ) {
			return;
		}
		
		// Get login form style.
		$login_form_style = get_theme_mod( 'aqualuxe_account_login_style', 'standard' );
		
		if ( 'enhanced' === $login_form_style ) {
			// Output login form wrapper.
			echo '<div class="login-form-wrapper enhanced">';
		}
	}

	/**
	 * Login form end.
	 *
	 * @return void
	 */
	public function login_form_end() {
		// Check if login form should be customized.
		if ( ! get_theme_mod( 'aqualuxe_account_login_custom', false ) ) {
			return;
		}
		
		// Get login form style.
		$login_form_style = get_theme_mod( 'aqualuxe_account_login_style', 'standard' );
		
		if ( 'enhanced' === $login_form_style ) {
			// Close login form wrapper.
			echo '</div>';
		}
	}

	/**
	 * Register form start.
	 *
	 * @return void
	 */
	public function register_form_start() {
		// Check if register form should be customized.
		if ( ! get_theme_mod( 'aqualuxe_account_register_custom', false ) ) {
			return;
		}
		
		// Get register form style.
		$register_form_style = get_theme_mod( 'aqualuxe_account_register_style', 'standard' );
		
		if ( 'enhanced' === $register_form_style ) {
			// Output register form wrapper.
			echo '<div class="register-form-wrapper enhanced">';
		}
	}

	/**
	 * Register form end.
	 *
	 * @return void
	 */
	public function register_form_end() {
		// Check if register form should be customized.
		if ( ! get_theme_mod( 'aqualuxe_account_register_custom', false ) ) {
			return;
		}
		
		// Get register form style.
		$register_form_style = get_theme_mod( 'aqualuxe_account_register_style', 'standard' );
		
		if ( 'enhanced' === $register_form_style ) {
			// Close register form wrapper.
			echo '</div>';
		}
	}

	/**
	 * Registration errors.
	 *
	 * @param WP_Error $errors Errors.
	 * @param string   $username Username.
	 * @param string   $email Email.
	 * @return WP_Error
	 */
	public function registration_errors( $errors, $username, $email ) {
		// Check if password strength should be enforced.
		if ( get_theme_mod( 'aqualuxe_account_password_strength', true ) ) {
			// Check if password is set.
			if ( isset( $_POST['password'] ) ) {
				// Get password.
				$password = sanitize_text_field( wp_unslash( $_POST['password'] ) );
				
				// Check password strength.
				$password_strength = $this->get_password_strength( $password );
				
				// Get minimum password strength.
				$min_strength = get_theme_mod( 'aqualuxe_account_password_strength_min', 3 );
				
				// Check if password is strong enough.
				if ( $password_strength < $min_strength ) {
					$errors->add( 'password_strength', esc_html__( 'Password is not strong enough. Please use a stronger password.', 'aqualuxe' ) );
				}
			}
		}
		
		return $errors;
	}

	/**
	 * Get password strength.
	 *
	 * @param string $password Password.
	 * @return int
	 */
	private function get_password_strength( $password ) {
		// Check password length.
		if ( strlen( $password ) < 8 ) {
			return 1;
		}
		
		// Check for uppercase letters.
		if ( ! preg_match( '/[A-Z]/', $password ) ) {
			return 2;
		}
		
		// Check for lowercase letters.
		if ( ! preg_match( '/[a-z]/', $password ) ) {
			return 2;
		}
		
		// Check for numbers.
		if ( ! preg_match( '/[0-9]/', $password ) ) {
			return 3;
		}
		
		// Check for special characters.
		if ( ! preg_match( '/[^a-zA-Z0-9]/', $password ) ) {
			return 3;
		}
		
		return 4;
	}

	/**
	 * Social login.
	 *
	 * @return void
	 */
	public function social_login() {
		// Check if social login is enabled.
		if ( ! get_theme_mod( 'aqualuxe_account_social_login', false ) ) {
			return;
		}
		
		// Get social login providers.
		$providers = get_theme_mod( 'aqualuxe_account_social_login_providers', array() );
		
		if ( empty( $providers ) ) {
			return;
		}
		
		// Output social login.
		echo '<div class="social-login">';
		echo '<div class="social-login-divider"><span>' . esc_html__( 'Or login with', 'aqualuxe' ) . '</span></div>';
		echo '<div class="social-login-buttons">';
		
		foreach ( $providers as $provider => $enabled ) {
			if ( $enabled ) {
				echo '<a href="#" class="social-login-button ' . esc_attr( $provider ) . '">';
				echo '<i class="fab fa-' . esc_attr( $provider ) . '"></i>';
				echo '<span>' . esc_html( ucfirst( $provider ) ) . '</span>';
				echo '</a>';
			}
		}
		
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Lost password wrapper open.
	 *
	 * @return void
	 */
	public function lost_password_wrapper_open() {
		// Get lost password layout.
		$lost_password_layout = get_theme_mod( 'aqualuxe_account_lost_password_layout', 'standard' );
		
		echo '<div class="lost-password-wrapper layout-' . esc_attr( $lost_password_layout ) . '">';
	}

	/**
	 * Lost password wrapper close.
	 *
	 * @return void
	 */
	public function lost_password_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Reset password wrapper open.
	 *
	 * @return void
	 */
	public function reset_password_wrapper_open() {
		// Get reset password layout.
		$reset_password_layout = get_theme_mod( 'aqualuxe_account_reset_password_layout', 'standard' );
		
		echo '<div class="reset-password-wrapper layout-' . esc_attr( $reset_password_layout ) . '">';
	}

	/**
	 * Reset password wrapper close.
	 *
	 * @return void
	 */
	public function reset_password_wrapper_close() {
		echo '</div>';
	}
}