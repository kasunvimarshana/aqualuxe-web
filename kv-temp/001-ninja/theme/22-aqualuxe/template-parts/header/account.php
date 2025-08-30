<?php
/**
 * Template part for displaying the account icon
 *
 * @package AquaLuxe
 */

// Exit if WooCommerce is not active
if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}
?>

<div class="header-account relative">
	<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="account-link flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200" title="<?php esc_attr_e( 'My Account', 'aqualuxe' ); ?>">
		<span class="sr-only"><?php esc_html_e( 'My Account', 'aqualuxe' ); ?></span>
		<?php if ( is_user_logged_in() ) : ?>
			<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
			</svg>
		<?php else : ?>
			<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
			</svg>
		<?php endif; ?>
	</a>
	
	<?php if ( aqualuxe_get_option( 'enable_account_dropdown', true ) ) : ?>
		<div class="account-dropdown hidden absolute right-0 top-full mt-2 w-60 bg-white dark:bg-dark-400 rounded-lg shadow-lg z-50 p-4">
			<?php if ( is_user_logged_in() ) : ?>
				<?php $current_user = wp_get_current_user(); ?>
				<div class="account-user flex items-center mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
					<div class="account-avatar mr-3">
						<?php echo get_avatar( $current_user->ID, 40, '', '', array( 'class' => 'rounded-full' ) ); ?>
					</div>
					<div class="account-info">
						<p class="account-name font-medium"><?php echo esc_html( $current_user->display_name ); ?></p>
						<p class="account-email text-sm text-gray-600 dark:text-gray-400"><?php echo esc_html( $current_user->user_email ); ?></p>
					</div>
				</div>
				
				<ul class="account-links list-none p-0 m-0">
					<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
						<li class="account-link-item mb-2">
							<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="flex items-center text-gray-700 dark:text-gray-300 hover:text-primary-500 dark:hover:text-primary-400 transition-colors duration-200">
								<?php if ( $endpoint === 'dashboard' ) : ?>
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
									</svg>
								<?php elseif ( $endpoint === 'orders' ) : ?>
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
									</svg>
								<?php elseif ( $endpoint === 'downloads' ) : ?>
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
									</svg>
								<?php elseif ( $endpoint === 'edit-address' ) : ?>
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
									</svg>
								<?php elseif ( $endpoint === 'edit-account' ) : ?>
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
									</svg>
								<?php elseif ( $endpoint === 'customer-logout' ) : ?>
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
									</svg>
								<?php else : ?>
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
									</svg>
								<?php endif; ?>
								<?php echo esc_html( $label ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<div class="login-register space-y-4">
					<h3 class="text-lg font-medium mb-4"><?php esc_html_e( 'Login or Register', 'aqualuxe' ); ?></h3>
					
					<form class="woocommerce-form woocommerce-form-login login" method="post">
						<div class="form-row mb-4">
							<label for="username" class="form-label"><?php esc_html_e( 'Username or email', 'aqualuxe' ); ?></label>
							<input type="text" class="form-input" name="username" id="username" autocomplete="username" />
						</div>
						<div class="form-row mb-4">
							<label for="password" class="form-label"><?php esc_html_e( 'Password', 'aqualuxe' ); ?></label>
							<input class="form-input" type="password" name="password" id="password" autocomplete="current-password" />
						</div>
						
						<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
						<button type="submit" class="btn-primary w-full" name="login" value="<?php esc_attr_e( 'Log in', 'aqualuxe' ); ?>"><?php esc_html_e( 'Log in', 'aqualuxe' ); ?></button>
					</form>
					
					<div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
						<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="text-sm text-primary-500 hover:text-primary-600"><?php esc_html_e( 'Lost your password?', 'aqualuxe' ); ?></a>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="text-sm text-primary-500 hover:text-primary-600"><?php esc_html_e( 'Register', 'aqualuxe' ); ?></a>
					</div>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>