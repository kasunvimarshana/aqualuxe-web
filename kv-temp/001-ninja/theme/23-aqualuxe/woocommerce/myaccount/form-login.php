<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 7.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

<div class="u-columns flex flex-wrap -mx-4" id="customer_login">

	<div class="u-column1 w-full md:w-1/2 px-4 mb-8 md:mb-0">

<?php endif; ?>

		<div class="login-form-container bg-white p-6 rounded-lg shadow-sm">
			<h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Login', 'aqualuxe' ); ?></h2>

			<form class="woocommerce-form woocommerce-form-login login" method="post">

				<?php do_action( 'woocommerce_login_form_start' ); ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
					<label for="username" class="block text-sm font-medium text-gray-700 mb-1"><?php esc_html_e( 'Username or email address', 'aqualuxe' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
				</p>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
					<label for="password" class="block text-sm font-medium text-gray-700 mb-1"><?php esc_html_e( 'Password', 'aqualuxe' ); ?>&nbsp;<span class="required">*</span></label>
					<input class="woocommerce-Input woocommerce-Input--text input-text w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" type="password" name="password" id="password" autocomplete="current-password" />
				</p>

				<?php do_action( 'woocommerce_login_form' ); ?>

				<div class="flex items-center justify-between mb-6">
					<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme flex items-center">
						<input class="woocommerce-form__input woocommerce-form__input-checkbox mr-2" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'aqualuxe' ); ?></span>
					</label>
					<p class="woocommerce-LostPassword lost_password">
						<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="text-primary hover:text-primary-dark text-sm"><?php esc_html_e( 'Lost your password?', 'aqualuxe' ); ?></a>
					</p>
				</div>
				
				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<button type="submit" class="woocommerce-button button woocommerce-form-login__submit w-full py-3 px-4 bg-primary hover:bg-primary-dark text-white font-medium rounded-md transition-colors duration-200" name="login" value="<?php esc_attr_e( 'Log in', 'aqualuxe' ); ?>"><?php esc_html_e( 'Log in', 'aqualuxe' ); ?></button>

				<?php do_action( 'woocommerce_login_form_end' ); ?>

			</form>
		</div>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

	</div>

	<div class="u-column2 w-full md:w-1/2 px-4">

		<div class="register-form-container bg-white p-6 rounded-lg shadow-sm">
			<h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Register', 'aqualuxe' ); ?></h2>

			<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

				<?php do_action( 'woocommerce_register_form_start' ); ?>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
						<label for="reg_username" class="block text-sm font-medium text-gray-700 mb-1"><?php esc_html_e( 'Username', 'aqualuxe' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
					</p>

				<?php endif; ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
					<label for="reg_email" class="block text-sm font-medium text-gray-700 mb-1"><?php esc_html_e( 'Email address', 'aqualuxe' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="email" class="woocommerce-Input woocommerce-Input--text input-text w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" />
				</p>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
						<label for="reg_password" class="block text-sm font-medium text-gray-700 mb-1"><?php esc_html_e( 'Password', 'aqualuxe' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="password" class="woocommerce-Input woocommerce-Input--text input-text w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" name="password" id="reg_password" autocomplete="new-password" />
					</p>

				<?php else : ?>

					<p class="mb-4 text-sm text-gray-600"><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'aqualuxe' ); ?></p>

				<?php endif; ?>

				<?php do_action( 'woocommerce_register_form' ); ?>

				<div class="woocommerce-privacy-policy-text mb-6">
					<p class="text-sm text-gray-600"><?php wc_privacy_policy_text( 'registration' ); ?></p>
				</div>

				<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
				<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit w-full py-3 px-4 bg-primary hover:bg-primary-dark text-white font-medium rounded-md transition-colors duration-200" name="register" value="<?php esc_attr_e( 'Register', 'aqualuxe' ); ?>"><?php esc_html_e( 'Register', 'aqualuxe' ); ?></button>

				<?php do_action( 'woocommerce_register_form_end' ); ?>

			</form>
		</div>

	</div>

</div>

<?php endif; ?>

<div class="login-benefits mt-8 py-8 bg-gray-50 rounded-lg">
	<div class="container mx-auto px-4">
		<h3 class="text-xl font-bold text-center mb-6"><?php esc_html_e( 'Benefits of Creating an Account', 'aqualuxe' ); ?></h3>
		
		<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
			<div class="benefit-item text-center p-4">
				<div class="icon-wrapper mb-4 inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-light text-primary">
					<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
					</svg>
				</div>
				<h4 class="text-lg font-medium mb-2"><?php esc_html_e( 'Personalized Experience', 'aqualuxe' ); ?></h4>
				<p class="text-gray-600"><?php esc_html_e( 'Get product recommendations based on your aquarium setup and preferences.', 'aqualuxe' ); ?></p>
			</div>
			
			<div class="benefit-item text-center p-4">
				<div class="icon-wrapper mb-4 inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-light text-primary">
					<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
					</svg>
				</div>
				<h4 class="text-lg font-medium mb-2"><?php esc_html_e( 'Faster Checkout', 'aqualuxe' ); ?></h4>
				<p class="text-gray-600"><?php esc_html_e( 'Save your shipping and payment information for a quicker checkout process.', 'aqualuxe' ); ?></p>
			</div>
			
			<div class="benefit-item text-center p-4">
				<div class="icon-wrapper mb-4 inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-light text-primary">
					<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
					</svg>
				</div>
				<h4 class="text-lg font-medium mb-2"><?php esc_html_e( 'Order History', 'aqualuxe' ); ?></h4>
				<p class="text-gray-600"><?php esc_html_e( 'Track your orders and easily reorder your favorite aquarium supplies.', 'aqualuxe' ); ?></p>
			</div>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>