<?php
/**
 * Template part for displaying the search form in the header
 *
 * @package AquaLuxe
 */

// Get search form settings from customizer or use defaults
$search_form_enable = get_theme_mod( 'aqualuxe_search_form_enable', true );
$search_form_placeholder = get_theme_mod( 'aqualuxe_search_form_placeholder', __( 'Search for products, species, or articles...', 'aqualuxe' ) );

// Only display the search form if it's enabled
if ( ! $search_form_enable ) {
	return;
}
?>

<div id="search-form" class="search-form-container hidden">
	<div class="container">
		<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<div class="search-form-inner">
				<input type="search" class="search-field" placeholder="<?php echo esc_attr( $search_form_placeholder ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
				
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<div class="search-categories">
						<select name="product_cat" id="product_cat">
							<option value=""><?php esc_html_e( 'All Categories', 'aqualuxe' ); ?></option>
							<?php
							$args = array(
								'taxonomy'     => 'product_cat',
								'orderby'      => 'name',
								'show_count'   => 0,
								'pad_counts'   => 0,
								'hierarchical' => 1,
								'title_li'     => '',
								'hide_empty'   => 1,
							);
							$all_categories = get_categories( $args );
							foreach ( $all_categories as $cat ) {
								echo '<option value="' . esc_attr( $cat->slug ) . '">' . esc_html( $cat->name ) . '</option>';
							}
							?>
						</select>
						<input type="hidden" name="post_type" value="product" />
					</div>
				<?php endif; ?>
				
				<button type="submit" class="search-submit">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
						<path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z" clip-rule="evenodd" />
					</svg>
					<span class="screen-reader-text"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
				</button>
			</div>
			
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<div class="search-tabs">
					<label class="search-tab">
						<input type="radio" name="post_type" value="product" checked="checked" />
						<span><?php esc_html_e( 'Products', 'aqualuxe' ); ?></span>
					</label>
					<label class="search-tab">
						<input type="radio" name="post_type" value="post" />
						<span><?php esc_html_e( 'Articles', 'aqualuxe' ); ?></span>
					</label>
					<label class="search-tab">
						<input type="radio" name="post_type" value="any" />
						<span><?php esc_html_e( 'All', 'aqualuxe' ); ?></span>
					</label>
				</div>
			<?php endif; ?>
			
			<?php if ( function_exists( 'aqualuxe_popular_searches' ) ) : ?>
				<div class="popular-searches">
					<span><?php esc_html_e( 'Popular:', 'aqualuxe' ); ?></span>
					<?php aqualuxe_popular_searches(); ?>
				</div>
			<?php endif; ?>
		</form>
	</div>
</div>