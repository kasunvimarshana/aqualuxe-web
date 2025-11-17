<?php
/**
 * Downloads
 *
 * Shows downloads on the account page.
 *
 * This template overrides /woocommerce/templates/myaccount/downloads.php
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$downloads     = WC()->customer->get_downloadable_products();
$has_downloads = (bool) $downloads;

do_action( 'woocommerce_before_account_downloads', $has_downloads ); ?>

<?php if ( $has_downloads ) : ?>

	<?php do_action( 'woocommerce_before_available_downloads' ); ?>

	<div class="woocommerce-downloads-header">
		<h2><?php esc_html_e( 'Available downloads', 'woocommerce' ); ?></h2>
		<p><?php esc_html_e( 'Access your purchased digital products here.', 'aqualuxe' ); ?></p>
	</div>

	<div class="woocommerce-table-wrapper">
		<table class="woocommerce-table woocommerce-table--downloads shop_table shop_table_responsive woocommerce-table--downloads">
			<thead>
				<tr>
					<th class="download-product"><span class="nobr"><?php esc_html_e( 'Product', 'woocommerce' ); ?></span></th>
					<th class="download-remaining"><span class="nobr"><?php esc_html_e( 'Downloads remaining', 'woocommerce' ); ?></span></th>
					<th class="download-expires"><span class="nobr"><?php esc_html_e( 'Expires', 'woocommerce' ); ?></span></th>
					<th class="download-file"><span class="nobr"><?php esc_html_e( 'Download', 'woocommerce' ); ?></span></th>
				</tr>
			</thead>

			<tbody>
				<?php foreach ( $downloads as $download ) : ?>
					<tr>
						<td class="download-product" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
							<a href="<?php echo esc_url( get_permalink( $download['product_id'] ) ); ?>">
								<?php echo esc_html( $download['product_name'] ); ?>
							</a>
						</td>
						<td class="download-remaining" data-title="<?php esc_attr_e( 'Downloads remaining', 'woocommerce' ); ?>">
							<?php
							if ( is_numeric( $download['downloads_remaining'] ) ) {
								echo esc_html( $download['downloads_remaining'] );
							} else {
								esc_html_e( 'Unlimited', 'woocommerce' );
							}
							?>
						</td>
						<td class="download-expires" data-title="<?php esc_attr_e( 'Expires', 'woocommerce' ); ?>">
							<?php
							if ( ! empty( $download['access_expires'] ) ) {
								echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $download['access_expires'] ) ) );
							} else {
								esc_html_e( 'Never', 'woocommerce' );
							}
							?>
						</td>
						<td class="download-file" data-title="<?php esc_attr_e( 'Download', 'woocommerce' ); ?>">
							<a href="<?php echo esc_url( $download['download_url'] ); ?>" class="woocommerce-button button download-button">
								<i class="fas fa-download"></i> <?php esc_html_e( 'Download', 'woocommerce' ); ?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<?php do_action( 'woocommerce_after_available_downloads' ); ?>

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<div class="woocommerce-empty-state">
			<div class="woocommerce-empty-state__icon">
				<i class="fas fa-download"></i>
			</div>
			<h3 class="woocommerce-empty-state__title"><?php esc_html_e( 'No downloads available yet.', 'woocommerce' ); ?></h3>
			<p class="woocommerce-empty-state__description"><?php esc_html_e( 'When you purchase downloadable items, they will appear here.', 'aqualuxe' ); ?></p>
			<a class="woocommerce-button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
				<?php esc_html_e( 'Browse products', 'woocommerce' ); ?>
			</a>
		</div>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_downloads', $has_downloads ); ?>