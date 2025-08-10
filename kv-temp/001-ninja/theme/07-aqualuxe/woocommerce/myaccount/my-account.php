<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * My Account navigation.
 *
 * @since 2.6.0
 */
?>

<div class="account-wrapper grid grid-cols-1 lg:grid-cols-12 gap-8">
	<div class="account-sidebar lg:col-span-3">
		<div class="bg-white dark:bg-dark-800 rounded-lg shadow-medium overflow-hidden">
			<?php if ( get_theme_mod( 'aqualuxe_enable_account_welcome', true ) ) : ?>
				<div class="account-welcome p-6 bg-primary-600 dark:bg-primary-700 text-white">
					<div class="flex items-center mb-3">
						<div class="account-avatar mr-3">
							<?php
							$current_user = wp_get_current_user();
							$user_id = $current_user->ID;
							
							// Get user avatar
							$avatar = get_avatar( $user_id, 48, '', '', array( 'class' => 'rounded-full' ) );
							if ( $avatar ) {
								echo $avatar;
							} else {
								// Fallback avatar
								echo '<div class="w-12 h-12 rounded-full bg-primary-700 dark:bg-primary-800 flex items-center justify-center text-white text-xl font-bold">';
								echo esc_html( substr( $current_user->display_name, 0, 1 ) );
								echo '</div>';
							}
							?>
						</div>
						<div class="account-user">
							<h3 class="text-lg font-medium">
								<?php
								/* translators: %s: Display name */
								printf( esc_html__( 'Hello, %s', 'aqualuxe' ), esc_html( $current_user->display_name ) );
								?>
							</h3>
							<p class="text-sm text-white/80">
								<?php echo esc_html( $current_user->user_email ); ?>
							</p>
						</div>
					</div>
					
					<?php if ( get_theme_mod( 'aqualuxe_enable_account_stats', true ) ) : ?>
						<div class="account-stats grid grid-cols-2 gap-2 mt-4 pt-4 border-t border-white/20">
							<div class="stat p-3 bg-white/10 rounded-lg text-center">
								<span class="text-xl font-bold block">
									<?php
									$customer_orders = wc_get_orders( array(
										'customer_id' => $user_id,
										'limit'       => -1,
										'status'      => array( 'completed' ),
									) );
									echo count( $customer_orders );
									?>
								</span>
								<span class="text-xs text-white/80"><?php esc_html_e( 'Orders', 'aqualuxe' ); ?></span>
							</div>
							
							<?php if ( class_exists( 'WC_Wishlist_Plugin' ) || get_theme_mod( 'aqualuxe_enable_wishlist', true ) ) : ?>
								<div class="stat p-3 bg-white/10 rounded-lg text-center">
									<span class="text-xl font-bold block">
										<?php
										// Get wishlist count - this is a placeholder as actual implementation depends on the wishlist plugin
										$wishlist_count = 0;
										if ( class_exists( 'WC_Wishlist_Plugin' ) ) {
											// Implementation for specific wishlist plugin
											// $wishlist_count = ...;
										} else {
											// Custom wishlist implementation
											$wishlist = get_user_meta( $user_id, 'aqualuxe_wishlist', true );
											$wishlist_count = is_array( $wishlist ) ? count( $wishlist ) : 0;
										}
										echo $wishlist_count;
										?>
									</span>
									<span class="text-xs text-white/80"><?php esc_html_e( 'Wishlist', 'aqualuxe' ); ?></span>
								</div>
							<?php else : ?>
								<div class="stat p-3 bg-white/10 rounded-lg text-center">
									<span class="text-xl font-bold block">
										<?php
										$customer_reviews = get_comments( array(
											'user_id' => $user_id,
											'post_type' => 'product',
											'status' => 'approve',
											'count' => true,
										) );
										echo $customer_reviews;
										?>
									</span>
									<span class="text-xs text-white/80"><?php esc_html_e( 'Reviews', 'aqualuxe' ); ?></span>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<nav class="woocommerce-MyAccount-navigation p-4">
				<ul class="space-y-1">
					<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
						<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?> rounded-lg overflow-hidden">
							<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-dark-700 transition-colors duration-300 <?php echo is_wc_endpoint_url( $endpoint ) ? 'bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300 font-medium' : 'text-gray-700 dark:text-gray-300'; ?>">
								<?php
								// Add icons to navigation items
								$icon = '';
								switch ( $endpoint ) {
									case 'dashboard':
										$icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>';
										break;
									case 'orders':
										$icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>';
										break;
									case 'downloads':
										$icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>';
										break;
									case 'edit-address':
										$icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>';
										break;
									case 'edit-account':
										$icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>';
										break;
									case 'customer-logout':
										$icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>';
										break;
									default:
										$icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>';
								}
								?>
								<div class="flex items-center">
									<?php echo $icon; ?>
									<?php echo esc_html( $label ); ?>
								</div>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>
			
			<?php if ( get_theme_mod( 'aqualuxe_enable_account_help', true ) ) : ?>
				<div class="account-help p-4 mt-2 border-t border-gray-200 dark:border-dark-700">
					<h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2"><?php esc_html_e( 'Need Help?', 'aqualuxe' ); ?></h3>
					<ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
						<li>
							<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="flex items-center hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
								</svg>
								<?php esc_html_e( 'Contact Support', 'aqualuxe' ); ?>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'faq' ) ) ); ?>" class="flex items-center hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
								</svg>
								<?php esc_html_e( 'FAQ', 'aqualuxe' ); ?>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="flex items-center hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
								</svg>
								<?php esc_html_e( 'Continue Shopping', 'aqualuxe' ); ?>
							</a>
						</li>
					</ul>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="account-content lg:col-span-9">
		<div class="bg-white dark:bg-dark-800 rounded-lg shadow-medium overflow-hidden p-6">
			<div class="woocommerce-MyAccount-content">
				<?php
				/**
				 * My Account content.
				 *
				 * @since 2.6.0
				 */
				do_action( 'woocommerce_account_content' );
				?>
			</div>
		</div>
	</div>
</div>

<style>
	/* My Account custom styles */
	.woocommerce-MyAccount-content h2,
	.woocommerce-MyAccount-content h3 {
		font-size: 1.25rem;
		font-weight: 600;
		margin-bottom: 1rem;
		color: #1f2937;
	}
	
	.dark .woocommerce-MyAccount-content h2,
	.dark .woocommerce-MyAccount-content h3 {
		color: #f3f4f6;
	}
	
	.woocommerce-MyAccount-content p {
		margin-bottom: 1rem;
	}
	
	.woocommerce-MyAccount-content .woocommerce-message,
	.woocommerce-MyAccount-content .woocommerce-info,
	.woocommerce-MyAccount-content .woocommerce-error {
		margin-bottom: 1.5rem;
	}
	
	/* Dashboard styles */
	.woocommerce-MyAccount-content .woocommerce-message {
		background-color: #f0fdf4;
		color: #166534;
		border-left: 4px solid #16a34a;
		padding: 1rem;
		border-radius: 0.375rem;
	}
	
	.dark .woocommerce-MyAccount-content .woocommerce-message {
		background-color: rgba(22, 101, 52, 0.2);
		color: #4ade80;
	}
	
	/* Orders styles */
	.woocommerce-orders-table,
	.woocommerce-table--order-details {
		width: 100%;
		border-collapse: collapse;
	}
	
	.woocommerce-orders-table th,
	.woocommerce-table--order-details th {
		padding: 0.75rem;
		text-align: left;
		font-weight: 500;
		border-bottom: 1px solid #e5e7eb;
	}
	
	.dark .woocommerce-orders-table th,
	.dark .woocommerce-table--order-details th {
		border-color: #374151;
	}
	
	.woocommerce-orders-table td,
	.woocommerce-table--order-details td {
		padding: 0.75rem;
		border-bottom: 1px solid #e5e7eb;
	}
	
	.dark .woocommerce-orders-table td,
	.dark .woocommerce-table--order-details td {
		border-color: #374151;
	}
	
	.woocommerce-orders-table .woocommerce-orders-table__cell-order-actions a,
	.woocommerce-button {
		display: inline-block;
		padding: 0.5rem 1rem;
		background-color: #0ea5e9;
		color: white;
		border-radius: 0.375rem;
		font-weight: 500;
		text-decoration: none;
		transition: background-color 0.3s;
	}
	
	.woocommerce-orders-table .woocommerce-orders-table__cell-order-actions a:hover,
	.woocommerce-button:hover {
		background-color: #0284c7;
		color: white;
	}
	
	/* Address styles */
	.woocommerce-Address {
		margin-bottom: 1.5rem;
	}
	
	.woocommerce-Address-title {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 0.5rem;
	}
	
	.woocommerce-Address-title h3 {
		margin-bottom: 0;
	}
	
	.woocommerce-Address address {
		font-style: normal;
		line-height: 1.5;
	}
	
	/* Form styles */
	.woocommerce-EditAccountForm,
	.woocommerce-address-fields {
		max-width: 36rem;
	}
	
	.woocommerce-EditAccountForm fieldset {
		margin: 1.5rem 0;
		padding: 1rem;
		border: 1px solid #e5e7eb;
		border-radius: 0.375rem;
	}
	
	.dark .woocommerce-EditAccountForm fieldset {
		border-color: #374151;
	}
	
	.woocommerce-EditAccountForm legend {
		padding: 0 0.5rem;
		font-weight: 500;
	}
	
	.woocommerce-EditAccountForm .form-row,
	.woocommerce-address-fields .form-row {
		margin-bottom: 1rem;
	}
	
	.woocommerce-EditAccountForm .form-row label,
	.woocommerce-address-fields .form-row label {
		display: block;
		margin-bottom: 0.5rem;
		font-size: 0.875rem;
		font-weight: 500;
	}
	
	.woocommerce-EditAccountForm .form-row .input-text,
	.woocommerce-address-fields .form-row .input-text,
	.woocommerce-EditAccountForm .form-row select,
	.woocommerce-address-fields .form-row select {
		width: 100%;
		padding: 0.75rem 1rem;
		border: 1px solid #d1d5db;
		border-radius: 0.375rem;
		background-color: #ffffff;
		color: #1f2937;
		transition: all 0.3s ease;
	}
	
	.dark .woocommerce-EditAccountForm .form-row .input-text,
	.dark .woocommerce-address-fields .form-row .input-text,
	.dark .woocommerce-EditAccountForm .form-row select,
	.dark .woocommerce-address-fields .form-row select {
		background-color: #1f2937;
		border-color: #374151;
		color: #e5e7eb;
	}
	
	.woocommerce-EditAccountForm .form-row .input-text:focus,
	.woocommerce-address-fields .form-row .input-text:focus,
	.woocommerce-EditAccountForm .form-row select:focus,
	.woocommerce-address-fields .form-row select:focus {
		border-color: #0ea5e9;
		outline: none;
		box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.2);
	}
	
	.woocommerce-EditAccountForm .form-row-first,
	.woocommerce-address-fields .form-row-first {
		float: left;
		width: 48%;
		clear: both;
	}
	
	.woocommerce-EditAccountForm .form-row-last,
	.woocommerce-address-fields .form-row-last {
		float: right;
		width: 48%;
	}
	
	.woocommerce-EditAccountForm .form-row-wide,
	.woocommerce-address-fields .form-row-wide {
		clear: both;
		width: 100%;
	}
	
	.woocommerce-EditAccountForm button,
	.woocommerce-address-fields button {
		margin-top: 1rem;
	}
	
	/* Downloads styles */
	.woocommerce-MyAccount-downloads-title {
		margin-bottom: 1rem;
	}
	
	.woocommerce-table--downloads {
		width: 100%;
		border-collapse: collapse;
	}
	
	.woocommerce-table--downloads th {
		padding: 0.75rem;
		text-align: left;
		font-weight: 500;
		border-bottom: 1px solid #e5e7eb;
	}
	
	.dark .woocommerce-table--downloads th {
		border-color: #374151;
	}
	
	.woocommerce-table--downloads td {
		padding: 0.75rem;
		border-bottom: 1px solid #e5e7eb;
	}
	
	.dark .woocommerce-table--downloads td {
		border-color: #374151;
	}
	
	.woocommerce-table--downloads .download-file a {
		display: inline-block;
		padding: 0.5rem 1rem;
		background-color: #0ea5e9;
		color: white;
		border-radius: 0.375rem;
		font-weight: 500;
		text-decoration: none;
		transition: background-color 0.3s;
	}
	
	.woocommerce-table--downloads .download-file a:hover {
		background-color: #0284c7;
		color: white;
	}
	
	/* Mobile styles */
	@media (max-width: 767px) {
		.woocommerce-EditAccountForm .form-row-first,
		.woocommerce-EditAccountForm .form-row-last,
		.woocommerce-address-fields .form-row-first,
		.woocommerce-address-fields .form-row-last {
			float: none;
			width: 100%;
		}
		
		.woocommerce-orders-table,
		.woocommerce-table--order-details,
		.woocommerce-table--downloads {
			display: block;
			overflow-x: auto;
		}
	}
</style>