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

<div class="account-layout grid grid-cols-1 lg:grid-cols-12 gap-8">
	<div class="account-sidebar lg:col-span-3">
		<div class="account-user mb-6 p-6 bg-white dark:bg-dark-800 rounded-lg shadow-soft text-center">
			<?php
			$current_user = wp_get_current_user();
			
			// Get user avatar
			$avatar = get_avatar( $current_user->user_email, 80, '', '', array( 'class' => 'rounded-full mx-auto mb-4' ) );
			if ( $avatar ) {
				echo $avatar;
			}
			?>
			
			<h2 class="account-username text-xl font-bold mb-1">
				<?php echo esc_html( $current_user->display_name ); ?>
			</h2>
			
			<p class="account-email text-sm text-dark-500 dark:text-dark-400 mb-4">
				<?php echo esc_html( $current_user->user_email ); ?>
			</p>
			
			<a href="<?php echo esc_url( wc_logout_url() ); ?>" class="btn-outline text-sm w-full">
				<?php esc_html_e( 'Log out', 'aqualuxe' ); ?>
			</a>
		</div>
		
		<div class="account-navigation bg-white dark:bg-dark-800 rounded-lg shadow-soft overflow-hidden">
			<?php do_action( 'woocommerce_account_navigation' ); ?>
		</div>
		
		<?php
		// Display customer stats if available
		$customer_id = get_current_user_id();
		$customer = new WC_Customer( $customer_id );
		
		if ( $customer ) {
			$order_count = $customer->get_order_count();
			$total_spent = $customer->get_total_spent();
			
			if ( $order_count > 0 ) :
			?>
			<div class="account-stats mt-6 p-6 bg-white dark:bg-dark-800 rounded-lg shadow-soft">
				<h3 class="text-lg font-bold mb-4"><?php esc_html_e( 'Your Stats', 'aqualuxe' ); ?></h3>
				
				<div class="stats-grid grid grid-cols-2 gap-4">
					<div class="stat-item p-4 bg-primary-50 dark:bg-primary-900/30 rounded-lg text-center">
						<div class="stat-value text-2xl font-bold text-primary-600 dark:text-primary-400">
							<?php echo esc_html( $order_count ); ?>
						</div>
						<div class="stat-label text-sm">
							<?php echo esc_html( _n( 'Order', 'Orders', $order_count, 'aqualuxe' ) ); ?>
						</div>
					</div>
					
					<div class="stat-item p-4 bg-secondary-50 dark:bg-secondary-900/30 rounded-lg text-center">
						<div class="stat-value text-2xl font-bold text-secondary-600 dark:text-secondary-400">
							<?php echo wp_kses_post( wc_price( $total_spent ) ); ?>
						</div>
						<div class="stat-label text-sm">
							<?php esc_html_e( 'Spent', 'aqualuxe' ); ?>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>
			
			<?php
			// Display loyalty points if WooCommerce Points and Rewards is active
			if ( class_exists( 'WC_Points_Rewards' ) ) {
				$points_balance = WC_Points_Rewards_Manager::get_users_points( $customer_id );
				$points_label = get_option( 'wc_points_rewards_points_label' );
				$points_label = $points_label ? $points_label : esc_html__( 'Points', 'aqualuxe' );
				
				if ( $points_balance > 0 ) :
				?>
				<div class="account-loyalty mt-6 p-6 bg-white dark:bg-dark-800 rounded-lg shadow-soft">
					<h3 class="text-lg font-bold mb-4"><?php esc_html_e( 'Loyalty Program', 'aqualuxe' ); ?></h3>
					
					<div class="loyalty-points p-4 bg-accent-50 dark:bg-accent-900/30 rounded-lg text-center">
						<div class="points-value text-2xl font-bold text-accent-600 dark:text-accent-400">
							<?php echo esc_html( $points_balance ); ?>
						</div>
						<div class="points-label text-sm">
							<?php echo esc_html( $points_label ); ?>
						</div>
					</div>
				</div>
				<?php endif; ?>
			<?php } ?>
		<?php } ?>
	</div>
	
	<div class="account-content lg:col-span-9">
		<div class="woocommerce-MyAccount-content bg-white dark:bg-dark-800 rounded-lg shadow-soft p-6">
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