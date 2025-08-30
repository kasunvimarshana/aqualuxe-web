<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation bg-white dark:bg-dark-800 rounded-lg shadow-sm p-6 mb-8">
	<div class="account-user mb-6 pb-6 border-b border-gray-200 dark:border-dark-700">
		<div class="flex items-center">
			<div class="account-avatar mr-4">
				<?php echo get_avatar( $current_user->ID, 60, '', '', array( 'class' => 'rounded-full' ) ); ?>
			</div>
			<div class="account-user-info">
				<h3 class="account-user-name text-lg font-medium text-dark-900 dark:text-white">
					<?php echo esc_html( $current_user->display_name ); ?>
				</h3>
				<p class="account-user-email text-sm text-dark-500 dark:text-dark-400">
					<?php echo esc_html( $current_user->user_email ); ?>
				</p>
			</div>
		</div>
	</div>

	<ul class="space-y-1">
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : 
			$icon = '';
			
			switch ( $endpoint ) {
				case 'dashboard':
					$icon = '<svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>';
					break;
				case 'orders':
					$icon = '<svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>';
					break;
				case 'downloads':
					$icon = '<svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>';
					break;
				case 'edit-address':
					$icon = '<svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>';
					break;
				case 'edit-account':
					$icon = '<svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>';
					break;
				case 'customer-logout':
					$icon = '<svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>';
					break;
				default:
					$icon = '<svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>';
					break;
			}
			
			$is_active = is_wc_endpoint_url( $endpoint );
			$item_classes = 'flex items-center py-2 px-3 rounded-md transition-colors ';
			$item_classes .= $is_active 
				? 'bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200 font-medium' 
				: 'text-dark-700 dark:text-dark-300 hover:bg-gray-100 dark:hover:bg-dark-700';
		?>
			<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--<?php echo esc_attr( $endpoint ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="<?php echo esc_attr( $item_classes ); ?>">
					<?php echo $icon; ?>
					<?php echo esc_html( $label ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	
	<?php if ( function_exists( 'YITH_WCWL' ) ) : ?>
		<div class="account-wishlist mt-6 pt-6 border-t border-gray-200 dark:border-dark-700">
			<a href="<?php echo esc_url( YITH_WCWL()->get_wishlist_url() ); ?>" class="flex items-center py-2 px-3 rounded-md text-dark-700 dark:text-dark-300 hover:bg-gray-100 dark:hover:bg-dark-700 transition-colors">
				<svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
				</svg>
				<?php esc_html_e( 'My Wishlist', 'aqualuxe' ); ?>
			</a>
		</div>
	<?php endif; ?>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>