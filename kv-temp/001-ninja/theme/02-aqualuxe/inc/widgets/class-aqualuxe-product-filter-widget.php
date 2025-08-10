<?php
/**
 * AquaLuxe Product Filter Widget
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AquaLuxe Product Filter Widget Class
 */
class AquaLuxe_Product_Filter_Widget extends WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct(
			'aqualuxe_product_filter',
			esc_html__( 'AquaLuxe Product Filter', 'aqualuxe' ),
			array(
				'description' => esc_html__( 'Advanced product filter for WooCommerce products.', 'aqualuxe' ),
				'classname'   => 'aqualuxe-product-filter-widget',
			)
		);
	}

	/**
	 * Widget output
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
			return;
		}

		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Filter Products', 'aqualuxe' );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$show_categories = isset( $instance['show_categories'] ) ? (bool) $instance['show_categories'] : true;
		$show_price = isset( $instance['show_price'] ) ? (bool) $instance['show_price'] : true;
		$show_attributes = isset( $instance['show_attributes'] ) ? (bool) $instance['show_attributes'] : true;
		$show_stock = isset( $instance['show_stock'] ) ? (bool) $instance['show_stock'] : true;
		$show_rating = isset( $instance['show_rating'] ) ? (bool) $instance['show_rating'] : true;
		$show_reset = isset( $instance['show_reset'] ) ? (bool) $instance['show_reset'] : true;

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		// Get current filter values
		$current_min_price = isset( $_GET['min_price'] ) ? floatval( $_GET['min_price'] ) : '';
		$current_max_price = isset( $_GET['max_price'] ) ? floatval( $_GET['max_price'] ) : '';
		$current_rating = isset( $_GET['rating_filter'] ) ? absint( $_GET['rating_filter'] ) : '';
		$current_stock = isset( $_GET['stock_status'] ) ? sanitize_text_field( $_GET['stock_status'] ) : '';
		
		// Get current category
		$current_cat = get_queried_object();
		$current_cat_id = is_a( $current_cat, 'WP_Term' ) && 'product_cat' === $current_cat->taxonomy ? $current_cat->term_id : 0;
		
		// Form action URL
		$form_action = wc_get_page_permalink( 'shop' );
		if ( is_product_category() ) {
			$form_action = get_term_link( $current_cat_id, 'product_cat' );
		} elseif ( is_product_tag() ) {
			$form_action = get_term_link( $current_cat, 'product_tag' );
		}
		?>

		<div class="aqualuxe-product-filter">
			<form method="get" action="<?php echo esc_url( $form_action ); ?>">
				<?php if ( $show_categories && ! is_product_category() ) : ?>
					<div class="filter-section filter-categories">
						<h4><?php esc_html_e( 'Categories', 'aqualuxe' ); ?></h4>
						<div class="filter-content">
							<ul class="filter-categories-list">
								<?php
								$product_categories = get_terms( array(
									'taxonomy'   => 'product_cat',
									'hide_empty' => true,
									'parent'     => 0,
								) );

								if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
									foreach ( $product_categories as $category ) {
										$category_link = get_term_link( $category, 'product_cat' );
										$count = $category->count;
										?>
										<li>
											<a href="<?php echo esc_url( $category_link ); ?>" <?php echo $current_cat_id === $category->term_id ? 'class="active"' : ''; ?>>
												<?php echo esc_html( $category->name ); ?>
												<span class="count">(<?php echo esc_html( $count ); ?>)</span>
											</a>
											
											<?php
											// Display child categories
											$child_categories = get_terms( array(
												'taxonomy'   => 'product_cat',
												'hide_empty' => true,
												'parent'     => $category->term_id,
											) );

											if ( ! empty( $child_categories ) && ! is_wp_error( $child_categories ) ) {
												echo '<ul class="children">';
												foreach ( $child_categories as $child_category ) {
													$child_link = get_term_link( $child_category, 'product_cat' );
													$child_count = $child_category->count;
													?>
													<li>
														<a href="<?php echo esc_url( $child_link ); ?>" <?php echo $current_cat_id === $child_category->term_id ? 'class="active"' : ''; ?>>
															<?php echo esc_html( $child_category->name ); ?>
															<span class="count">(<?php echo esc_html( $child_count ); ?>)</span>
														</a>
													</li>
													<?php
												}
												echo '</ul>';
											}
											?>
										</li>
										<?php
									}
								}
								?>
							</ul>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( $show_price ) : ?>
					<div class="filter-section filter-price">
						<h4><?php esc_html_e( 'Price Range', 'aqualuxe' ); ?></h4>
						<div class="filter-content">
							<div class="price-slider-wrapper">
								<div class="price-slider"></div>
								<div class="price-slider-amount">
									<div class="price-inputs">
										<input type="text" id="min_price" name="min_price" value="<?php echo esc_attr( $current_min_price ); ?>" data-min="0" placeholder="<?php esc_attr_e( 'Min price', 'aqualuxe' ); ?>" />
										<span class="price-separator">-</span>
										<input type="text" id="max_price" name="max_price" value="<?php echo esc_attr( $current_max_price ); ?>" data-max="1000" placeholder="<?php esc_attr_e( 'Max price', 'aqualuxe' ); ?>" />
									</div>
									<button type="submit" class="button"><?php esc_html_e( 'Filter', 'aqualuxe' ); ?></button>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( $show_attributes ) : ?>
					<?php
					// Get filterable attributes
					$attribute_taxonomies = wc_get_attribute_taxonomies();
					
					if ( ! empty( $attribute_taxonomies ) ) {
						foreach ( $attribute_taxonomies as $attribute ) {
							$attribute_name = wc_attribute_taxonomy_name( $attribute->attribute_name );
							$current_attribute_values = isset( $_GET[ 'filter_' . $attribute->attribute_name ] ) ? explode( ',', wc_clean( $_GET[ 'filter_' . $attribute->attribute_name ] ) ) : array();
							
							// Get terms for this attribute
							$terms = get_terms( array(
								'taxonomy'   => $attribute_name,
								'hide_empty' => true,
							) );
							
							if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
								?>
								<div class="filter-section filter-attribute filter-<?php echo esc_attr( $attribute->attribute_name ); ?>">
									<h4><?php echo esc_html( $attribute->attribute_label ); ?></h4>
									<div class="filter-content">
										<ul class="filter-attribute-list">
											<?php foreach ( $terms as $term ) : ?>
												<li>
													<label>
														<input type="checkbox" name="filter_<?php echo esc_attr( $attribute->attribute_name ); ?>[]" value="<?php echo esc_attr( $term->slug ); ?>" <?php checked( in_array( $term->slug, $current_attribute_values, true ) ); ?> />
														<?php echo esc_html( $term->name ); ?>
														<span class="count">(<?php echo esc_html( $term->count ); ?>)</span>
													</label>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								</div>
								<?php
							}
						}
					}
					?>
				<?php endif; ?>

				<?php if ( $show_stock ) : ?>
					<div class="filter-section filter-stock">
						<h4><?php esc_html_e( 'Stock Status', 'aqualuxe' ); ?></h4>
						<div class="filter-content">
							<ul class="filter-stock-list">
								<li>
									<label>
										<input type="radio" name="stock_status" value="instock" <?php checked( $current_stock, 'instock' ); ?> />
										<?php esc_html_e( 'In Stock', 'aqualuxe' ); ?>
									</label>
								</li>
								<li>
									<label>
										<input type="radio" name="stock_status" value="outofstock" <?php checked( $current_stock, 'outofstock' ); ?> />
										<?php esc_html_e( 'Out of Stock', 'aqualuxe' ); ?>
									</label>
								</li>
								<li>
									<label>
										<input type="radio" name="stock_status" value="onbackorder" <?php checked( $current_stock, 'onbackorder' ); ?> />
										<?php esc_html_e( 'On Backorder', 'aqualuxe' ); ?>
									</label>
								</li>
								<li>
									<label>
										<input type="radio" name="stock_status" value="" <?php checked( $current_stock, '' ); ?> />
										<?php esc_html_e( 'All', 'aqualuxe' ); ?>
									</label>
								</li>
							</ul>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( $show_rating ) : ?>
					<div class="filter-section filter-rating">
						<h4><?php esc_html_e( 'Rating', 'aqualuxe' ); ?></h4>
						<div class="filter-content">
							<ul class="filter-rating-list">
								<?php for ( $rating = 5; $rating >= 1; $rating-- ) : ?>
									<li>
										<label>
											<input type="radio" name="rating_filter" value="<?php echo esc_attr( $rating ); ?>" <?php checked( $current_rating, $rating ); ?> />
											<span class="star-rating">
												<?php
												for ( $i = 1; $i <= 5; $i++ ) {
													echo '<span class="star ' . ( $i <= $rating ? 'filled' : 'empty' ) . '"></span>';
												}
												?>
											</span>
											<?php if ( $rating > 1 ) : ?>
												<span class="rating-text"><?php printf( esc_html__( '%d stars & up', 'aqualuxe' ), $rating ); ?></span>
											<?php else : ?>
												<span class="rating-text"><?php printf( esc_html__( '%d star & up', 'aqualuxe' ), $rating ); ?></span>
											<?php endif; ?>
										</label>
									</li>
								<?php endfor; ?>
								<li>
									<label>
										<input type="radio" name="rating_filter" value="" <?php checked( $current_rating, '' ); ?> />
										<?php esc_html_e( 'All ratings', 'aqualuxe' ); ?>
									</label>
								</li>
							</ul>
						</div>
					</div>
				<?php endif; ?>

				<?php
				// Keep query string parameters
				foreach ( $_GET as $key => $value ) {
					if ( in_array( $key, array( 'min_price', 'max_price', 'rating_filter', 'stock_status' ), true ) ) {
						continue;
					}
					
					if ( is_array( $value ) ) {
						foreach ( $value as $inner_value ) {
							echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $inner_value ) . '" />';
						}
					} else {
						echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" />';
					}
				}
				?>

				<div class="filter-actions">
					<button type="submit" class="button filter-button"><?php esc_html_e( 'Apply Filters', 'aqualuxe' ); ?></button>
					
					<?php if ( $show_reset ) : ?>
						<a href="<?php echo esc_url( $form_action ); ?>" class="reset-filters"><?php esc_html_e( 'Reset Filters', 'aqualuxe' ); ?></a>
					<?php endif; ?>
				</div>
			</form>
		</div>

		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Widget settings form
	 *
	 * @param array $instance Widget instance.
	 */
	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Filter Products', 'aqualuxe' );
		$show_categories = isset( $instance['show_categories'] ) ? (bool) $instance['show_categories'] : true;
		$show_price = isset( $instance['show_price'] ) ? (bool) $instance['show_price'] : true;
		$show_attributes = isset( $instance['show_attributes'] ) ? (bool) $instance['show_attributes'] : true;
		$show_stock = isset( $instance['show_stock'] ) ? (bool) $instance['show_stock'] : true;
		$show_rating = isset( $instance['show_rating'] ) ? (bool) $instance['show_rating'] : true;
		$show_reset = isset( $instance['show_reset'] ) ? (bool) $instance['show_reset'] : true;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_categories ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_categories' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_categories' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_categories' ) ); ?>"><?php esc_html_e( 'Show categories filter', 'aqualuxe' ); ?></label>
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_price ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_price' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_price' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_price' ) ); ?>"><?php esc_html_e( 'Show price filter', 'aqualuxe' ); ?></label>
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_attributes ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_attributes' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_attributes' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_attributes' ) ); ?>"><?php esc_html_e( 'Show attributes filter', 'aqualuxe' ); ?></label>
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_stock ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_stock' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_stock' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_stock' ) ); ?>"><?php esc_html_e( 'Show stock status filter', 'aqualuxe' ); ?></label>
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_rating ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_rating' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>"><?php esc_html_e( 'Show rating filter', 'aqualuxe' ); ?></label>
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_reset ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_reset' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_reset' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_reset' ) ); ?>"><?php esc_html_e( 'Show reset button', 'aqualuxe' ); ?></label>
		</p>
		<?php
	}

	/**
	 * Update widget settings
	 *
	 * @param array $new_instance New widget instance.
	 * @param array $old_instance Old widget instance.
	 * @return array Updated widget instance.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['show_categories'] = isset( $new_instance['show_categories'] ) ? (bool) $new_instance['show_categories'] : false;
		$instance['show_price'] = isset( $new_instance['show_price'] ) ? (bool) $new_instance['show_price'] : false;
		$instance['show_attributes'] = isset( $new_instance['show_attributes'] ) ? (bool) $new_instance['show_attributes'] : false;
		$instance['show_stock'] = isset( $new_instance['show_stock'] ) ? (bool) $new_instance['show_stock'] : false;
		$instance['show_rating'] = isset( $new_instance['show_rating'] ) ? (bool) $new_instance['show_rating'] : false;
		$instance['show_reset'] = isset( $new_instance['show_reset'] ) ? (bool) $new_instance['show_reset'] : false;

		return $instance;
	}
}

/**
 * Register AquaLuxe Product Filter Widget
 */
function aqualuxe_register_product_filter_widget() {
	register_widget( 'AquaLuxe_Product_Filter_Widget' );
}
add_action( 'widgets_init', 'aqualuxe_register_product_filter_widget' );