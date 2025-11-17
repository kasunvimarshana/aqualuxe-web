<?php
/**
 * The template for displaying search forms
 *
 * @package AquaLuxe
 */

$aqualuxe_unique_id = wp_unique_id( 'search-form-' );
$aqualuxe_aria_label = ! empty( $args['aria_label'] ) ? 'aria-label="' . esc_attr( $args['aria_label'] ) . '"' : '';
?>

<form role="search" <?php echo $aqualuxe_aria_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above. ?> method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="relative">
		<label for="<?php echo esc_attr( $aqualuxe_unique_id ); ?>" class="sr-only">
			<?php esc_html_e( 'Search for:', 'aqualuxe' ); ?>
		</label>
		<div class="flex">
			<input type="search" id="<?php echo esc_attr( $aqualuxe_unique_id ); ?>" class="search-field w-full px-4 py-3 border border-gray-300 dark:border-dark-600 rounded-l-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-dark-700 dark:text-white" placeholder="<?php echo esc_attr_x( 'Search&hellip;', 'placeholder', 'aqualuxe' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
			<button type="submit" class="search-submit bg-primary-600 hover:bg-primary-700 text-white px-4 py-0 rounded-r-md transition-colors duration-200 flex items-center justify-center" aria-label="<?php esc_attr_e( 'Search', 'aqualuxe' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
				</svg>
				<span class="sr-only"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
			</button>
		</div>
		
		<?php if ( class_exists( 'WooCommerce' ) && isset( $args['show_product_cat'] ) && $args['show_product_cat'] ) : ?>
			<div class="search-categories mt-3">
				<label for="product-cat" class="sr-only"><?php esc_html_e( 'Product Category', 'aqualuxe' ); ?></label>
				<select name="product_cat" id="product-cat" class="w-full px-4 py-2 border border-gray-300 dark:border-dark-600 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-dark-700 dark:text-white">
					<option value=""><?php esc_html_e( 'All Categories', 'aqualuxe' ); ?></option>
					<?php
					$product_categories = get_terms( 'product_cat', array( 'hide_empty' => true ) );
					if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
						foreach ( $product_categories as $category ) {
							echo '<option value="' . esc_attr( $category->slug ) . '">' . esc_html( $category->name ) . '</option>';
						}
					}
					?>
				</select>
				<input type="hidden" name="post_type" value="product" />
			</div>
		<?php endif; ?>
		
		<?php if ( isset( $args['show_post_type'] ) && $args['show_post_type'] ) : ?>
			<div class="search-post-types mt-3">
				<label for="post-type" class="sr-only"><?php esc_html_e( 'Content Type', 'aqualuxe' ); ?></label>
				<select name="post_type" id="post-type" class="w-full px-4 py-2 border border-gray-300 dark:border-dark-600 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-dark-700 dark:text-white">
					<option value="any"><?php esc_html_e( 'All Content', 'aqualuxe' ); ?></option>
					<option value="post"><?php esc_html_e( 'Posts', 'aqualuxe' ); ?></option>
					<option value="page"><?php esc_html_e( 'Pages', 'aqualuxe' ); ?></option>
					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<option value="product"><?php esc_html_e( 'Products', 'aqualuxe' ); ?></option>
					<?php endif; ?>
				</select>
			</div>
		<?php endif; ?>
	</div>
</form>