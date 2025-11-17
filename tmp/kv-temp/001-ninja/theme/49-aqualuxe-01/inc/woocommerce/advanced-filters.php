<?php
/**
 * WooCommerce Advanced Filters functionality
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Advanced Filters class
 */
class AquaLuxe_Advanced_Filters {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Add AJAX filter handlers
		add_action( 'wp_ajax_aqualuxe_ajax_product_filter', array( $this, 'ajax_product_filter' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_ajax_product_filter', array( $this, 'ajax_product_filter' ) );

		// Add filter widgets
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );

		// Add filter scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Add filter container
		add_action( 'woocommerce_before_shop_loop', array( $this, 'filter_container' ), 15 );

		// Add filter button
		add_action( 'woocommerce_before_shop_loop', array( $this, 'filter_button' ), 15 );

		// Add active filters display
		add_action( 'woocommerce_before_shop_loop', array( $this, 'active_filters' ), 25 );

		// Add filter reset button
		add_action( 'woocommerce_before_shop_loop', array( $this, 'reset_button' ), 26 );

		// Add filter form
		add_action( 'wp_footer', array( $this, 'filter_form' ) );

		// Add price slider script
		add_action( 'wp_footer', array( $this, 'price_slider_script' ) );

		// Add custom query vars
		add_filter( 'query_vars', array( $this, 'add_query_vars' ) );

		// Modify product query
		add_action( 'woocommerce_product_query', array( $this, 'modify_product_query' ), 10, 2 );
	}

	/**
	 * Register filter widgets
	 */
	public function register_widgets() {
		register_widget( 'AquaLuxe_Filter_Price_Widget' );
		register_widget( 'AquaLuxe_Filter_Attribute_Widget' );
		register_widget( 'AquaLuxe_Filter_Category_Widget' );
		register_widget( 'AquaLuxe_Filter_Rating_Widget' );
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {
		if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
			return;
		}

		// Add filter script data
		$script_data = array(
			'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
			'nonce'         => wp_create_nonce( 'aqualuxe-filter-nonce' ),
			'shopUrl'       => wc_get_page_permalink( 'shop' ),
			'currentUrl'    => $this->get_current_page_url(),
			'loader'        => get_template_directory_uri() . '/assets/dist/images/loader.svg',
			'priceFormat'   => get_woocommerce_price_format(),
			'priceCurrency' => get_woocommerce_currency_symbol(),
			'i18n'          => array(
				'noProducts' => esc_html__( 'No products found', 'aqualuxe' ),
			),
		);

		wp_localize_script( 'aqualuxe-woocommerce', 'aqualuxeFilter', $script_data );
	}

	/**
	 * Add filter container
	 */
	public function filter_container() {
		if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
			return;
		}

		echo '<div class="aqualuxe-filter-container">';
		echo '<div class="aqualuxe-filter-wrapper">';
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Add filter button
	 */
	public function filter_button() {
		if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
			return;
		}

		echo '<button class="aqualuxe-filter-button" aria-expanded="false" aria-controls="aqualuxe-filter-form">';
		echo '<svg class="icon icon-filter" aria-hidden="true" focusable="false"><use xlink:href="#icon-filter"></use></svg>';
		echo '<span>' . esc_html__( 'Filter', 'aqualuxe' ) . '</span>';
		echo '</button>';
	}

	/**
	 * Add active filters display
	 */
	public function active_filters() {
		if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
			return;
		}

		$active_filters = $this->get_active_filters();

		if ( empty( $active_filters ) ) {
			return;
		}

		echo '<div class="aqualuxe-active-filters">';
		echo '<span class="active-filters-title">' . esc_html__( 'Active Filters:', 'aqualuxe' ) . '</span>';
		echo '<ul class="active-filters-list">';

		foreach ( $active_filters as $filter_type => $filter_values ) {
			foreach ( $filter_values as $filter_key => $filter_value ) {
				echo '<li class="active-filter-item">';
				echo '<a href="#" class="remove-filter" data-filter-type="' . esc_attr( $filter_type ) . '" data-filter-key="' . esc_attr( $filter_key ) . '">';
				echo esc_html( $filter_value );
				echo '<svg class="icon icon-close" aria-hidden="true" focusable="false"><use xlink:href="#icon-close"></use></svg>';
				echo '</a>';
				echo '</li>';
			}
		}

		echo '</ul>';
		echo '</div>';
	}

	/**
	 * Add filter reset button
	 */
	public function reset_button() {
		if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
			return;
		}

		$active_filters = $this->get_active_filters();

		if ( empty( $active_filters ) ) {
			return;
		}

		echo '<a href="' . esc_url( $this->get_reset_url() ) . '" class="aqualuxe-filter-reset">';
		echo esc_html__( 'Reset Filters', 'aqualuxe' );
		echo '</a>';
	}

	/**
	 * Add filter form
	 */
	public function filter_form() {
		if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
			return;
		}

		echo '<div id="aqualuxe-filter-form" class="aqualuxe-filter-form">';
		echo '<div class="aqualuxe-filter-form-header">';
		echo '<h3>' . esc_html__( 'Filter Products', 'aqualuxe' ) . '</h3>';
		echo '<button class="close-filter-form" aria-label="' . esc_attr__( 'Close', 'aqualuxe' ) . '">';
		echo '<svg class="icon icon-close" aria-hidden="true" focusable="false"><use xlink:href="#icon-close"></use></svg>';
		echo '</button>';
		echo '</div>';

		echo '<div class="aqualuxe-filter-form-content">';

		// Price filter
		$this->price_filter();

		// Category filter
		$this->category_filter();

		// Attribute filters
		$this->attribute_filters();

		// Rating filter
		$this->rating_filter();

		echo '</div>';

		echo '<div class="aqualuxe-filter-form-footer">';
		echo '<button class="apply-filters">' . esc_html__( 'Apply Filters', 'aqualuxe' ) . '</button>';
		echo '<button class="reset-filters">' . esc_html__( 'Reset', 'aqualuxe' ) . '</button>';
		echo '</div>';
		echo '</div>';

		// Add overlay
		echo '<div class="aqualuxe-filter-overlay"></div>';
	}

	/**
	 * Price filter
	 */
	public function price_filter() {
		// Get min and max prices
		$min_price = isset( $_GET['min_price'] ) ? floatval( $_GET['min_price'] ) : '';
		$max_price = isset( $_GET['max_price'] ) ? floatval( $_GET['max_price'] ) : '';

		// Get price range
		$price_range = $this->get_price_range();

		echo '<div class="aqualuxe-filter-section price-filter">';
		echo '<h4>' . esc_html__( 'Price', 'aqualuxe' ) . '</h4>';
		echo '<div class="price-slider-container">';
		echo '<div class="price-slider"></div>';
		echo '<div class="price-slider-values">';
		echo '<span class="price-slider-min">' . wc_price( $price_range['min'] ) . '</span>';
		echo '<span class="price-slider-max">' . wc_price( $price_range['max'] ) . '</span>';
		echo '</div>';
		echo '<div class="price-slider-inputs">';
		echo '<div class="price-slider-input">';
		echo '<label for="min-price">' . esc_html__( 'Min', 'aqualuxe' ) . '</label>';
		echo '<input type="number" id="min-price" name="min_price" value="' . esc_attr( $min_price ) . '" min="' . esc_attr( $price_range['min'] ) . '" max="' . esc_attr( $price_range['max'] ) . '" step="1" />';
		echo '</div>';
		echo '<div class="price-slider-input">';
		echo '<label for="max-price">' . esc_html__( 'Max', 'aqualuxe' ) . '</label>';
		echo '<input type="number" id="max-price" name="max_price" value="' . esc_attr( $max_price ) . '" min="' . esc_attr( $price_range['min'] ) . '" max="' . esc_attr( $price_range['max'] ) . '" step="1" />';
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Category filter
	 */
	public function category_filter() {
		$product_categories = get_terms( array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
		) );

		if ( empty( $product_categories ) || is_wp_error( $product_categories ) ) {
			return;
		}

		$current_category = get_queried_object();
		$current_category_id = $current_category && isset( $current_category->term_id ) ? $current_category->term_id : 0;

		echo '<div class="aqualuxe-filter-section category-filter">';
		echo '<h4>' . esc_html__( 'Categories', 'aqualuxe' ) . '</h4>';
		echo '<ul class="category-filter-list">';

		foreach ( $product_categories as $category ) {
			// Skip current category
			if ( $category->term_id === $current_category_id ) {
				continue;
			}

			// Get product count
			$product_count = $category->count;

			// Check if category is selected
			$selected = isset( $_GET['product_cat'] ) && $_GET['product_cat'] === $category->slug;

			echo '<li class="category-filter-item' . ( $selected ? ' selected' : '' ) . '">';
			echo '<label>';
			echo '<input type="checkbox" name="product_cat" value="' . esc_attr( $category->slug ) . '" ' . checked( $selected, true, false ) . ' />';
			echo esc_html( $category->name );
			echo '<span class="count">(' . esc_html( $product_count ) . ')</span>';
			echo '</label>';
			echo '</li>';
		}

		echo '</ul>';
		echo '</div>';
	}

	/**
	 * Attribute filters
	 */
	public function attribute_filters() {
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		if ( empty( $attribute_taxonomies ) ) {
			return;
		}

		foreach ( $attribute_taxonomies as $attribute ) {
			$taxonomy = wc_attribute_taxonomy_name( $attribute->attribute_name );
			$terms = get_terms( array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => true,
			) );

			if ( empty( $terms ) || is_wp_error( $terms ) ) {
				continue;
			}

			$filter_name = 'filter_' . $attribute->attribute_name;
			$selected_values = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( $_GET[ $filter_name ] ) ) : array();

			echo '<div class="aqualuxe-filter-section attribute-filter attribute-' . esc_attr( $attribute->attribute_name ) . '">';
			echo '<h4>' . esc_html( $attribute->attribute_label ) . '</h4>';
			echo '<ul class="attribute-filter-list">';

			foreach ( $terms as $term ) {
				// Check if term is selected
				$selected = in_array( $term->slug, $selected_values );

				echo '<li class="attribute-filter-item' . ( $selected ? ' selected' : '' ) . '">';
				echo '<label>';
				echo '<input type="checkbox" name="' . esc_attr( $filter_name ) . '" value="' . esc_attr( $term->slug ) . '" ' . checked( $selected, true, false ) . ' />';
				echo esc_html( $term->name );
				echo '<span class="count">(' . esc_html( $term->count ) . ')</span>';
				echo '</label>';
				echo '</li>';
			}

			echo '</ul>';
			echo '</div>';
		}
	}

	/**
	 * Rating filter
	 */
	public function rating_filter() {
		if ( ! wc_review_ratings_enabled() ) {
			return;
		}

		$rating_filter = isset( $_GET['rating_filter'] ) ? array_filter( array_map( 'absint', explode( ',', $_GET['rating_filter'] ) ) ) : array();

		echo '<div class="aqualuxe-filter-section rating-filter">';
		echo '<h4>' . esc_html__( 'Rating', 'aqualuxe' ) . '</h4>';
		echo '<ul class="rating-filter-list">';

		for ( $rating = 5; $rating >= 1; $rating-- ) {
			// Check if rating is selected
			$selected = in_array( $rating, $rating_filter );

			echo '<li class="rating-filter-item' . ( $selected ? ' selected' : '' ) . '">';
			echo '<label>';
			echo '<input type="checkbox" name="rating_filter" value="' . esc_attr( $rating ) . '" ' . checked( $selected, true, false ) . ' />';
			
			// Display stars
			for ( $i = 1; $i <= 5; $i++ ) {
				if ( $i <= $rating ) {
					echo '<svg class="icon icon-star" aria-hidden="true" focusable="false"><use xlink:href="#icon-star"></use></svg>';
				} else {
					echo '<svg class="icon icon-star-empty" aria-hidden="true" focusable="false"><use xlink:href="#icon-star-empty"></use></svg>';
				}
			}
			
			echo '<span class="rating-text">' . sprintf( esc_html__( '%d star and up', 'aqualuxe' ), $rating ) . '</span>';
			echo '</label>';
			echo '</li>';
		}

		echo '</ul>';
		echo '</div>';
	}

	/**
	 * Price slider script
	 */
	public function price_slider_script() {
		if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
			return;
		}

		// Get price range
		$price_range = $this->get_price_range();

		// Get min and max prices
		$min_price = isset( $_GET['min_price'] ) ? floatval( $_GET['min_price'] ) : $price_range['min'];
		$max_price = isset( $_GET['max_price'] ) ? floatval( $_GET['max_price'] ) : $price_range['max'];

		?>
		<script>
			(function($) {
				$(document).ready(function() {
					// Price slider
					var $slider = $('.price-slider');
					var $minInput = $('#min-price');
					var $maxInput = $('#max-price');
					var minPrice = <?php echo esc_js( $price_range['min'] ); ?>;
					var maxPrice = <?php echo esc_js( $price_range['max'] ); ?>;
					var currentMinPrice = <?php echo esc_js( $min_price ); ?>;
					var currentMaxPrice = <?php echo esc_js( $max_price ); ?>;

					// Initialize slider
					$slider.slider({
						range: true,
						min: minPrice,
						max: maxPrice,
						values: [currentMinPrice, currentMaxPrice],
						slide: function(event, ui) {
							$minInput.val(ui.values[0]);
							$maxInput.val(ui.values[1]);
						}
					});

					// Update slider when inputs change
					$minInput.on('change', function() {
						var value = parseInt($(this).val());
						
						if (isNaN(value)) {
							value = minPrice;
						}
						
						if (value < minPrice) {
							value = minPrice;
						}
						
						if (value > $maxInput.val()) {
							value = $maxInput.val();
						}
						
						$(this).val(value);
						$slider.slider('values', 0, value);
					});

					$maxInput.on('change', function() {
						var value = parseInt($(this).val());
						
						if (isNaN(value)) {
							value = maxPrice;
						}
						
						if (value > maxPrice) {
							value = maxPrice;
						}
						
						if (value < $minInput.val()) {
							value = $minInput.val();
						}
						
						$(this).val(value);
						$slider.slider('values', 1, value);
					});
				});
			})(jQuery);
		</script>
		<?php
	}

	/**
	 * AJAX product filter
	 */
	public function ajax_product_filter() {
		// Check nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'aqualuxe-filter-nonce' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce', 'aqualuxe' ) ) );
		}

		// Get filter data
		$filter_data = isset( $_POST['filter_data'] ) ? $_POST['filter_data'] : array();

		// Build query args
		$query_args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => apply_filters( 'loop_shop_per_page', wc_get_default_products_per_row() * wc_get_default_product_rows_per_page() ),
		);

		// Add tax query
		$tax_query = array();

		// Add meta query
		$meta_query = array();

		// Process filter data
		if ( ! empty( $filter_data ) ) {
			// Price filter
			if ( isset( $filter_data['min_price'] ) && isset( $filter_data['max_price'] ) ) {
				$meta_query[] = array(
					'key'     => '_price',
					'value'   => array( floatval( $filter_data['min_price'] ), floatval( $filter_data['max_price'] ) ),
					'type'    => 'numeric',
					'compare' => 'BETWEEN',
				);
			}

			// Category filter
			if ( isset( $filter_data['product_cat'] ) && ! empty( $filter_data['product_cat'] ) ) {
				$tax_query[] = array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $filter_data['product_cat'],
				);
			}

			// Attribute filters
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ( ! empty( $attribute_taxonomies ) ) {
				foreach ( $attribute_taxonomies as $attribute ) {
					$filter_name = 'filter_' . $attribute->attribute_name;
					
					if ( isset( $filter_data[ $filter_name ] ) && ! empty( $filter_data[ $filter_name ] ) ) {
						$tax_query[] = array(
							'taxonomy' => wc_attribute_taxonomy_name( $attribute->attribute_name ),
							'field'    => 'slug',
							'terms'    => explode( ',', $filter_data[ $filter_name ] ),
							'operator' => 'IN',
						);
					}
				}
			}

			// Rating filter
			if ( isset( $filter_data['rating_filter'] ) && ! empty( $filter_data['rating_filter'] ) ) {
				$rating_filter = explode( ',', $filter_data['rating_filter'] );
				
				if ( ! empty( $rating_filter ) ) {
					$meta_query[] = array(
						'key'     => '_wc_average_rating',
						'value'   => array( min( $rating_filter ), 5 ),
						'type'    => 'DECIMAL',
						'compare' => 'BETWEEN',
					);
				}
			}
		}

		// Add tax query to query args
		if ( ! empty( $tax_query ) ) {
			$query_args['tax_query'] = array_merge( array( 'relation' => 'AND' ), $tax_query );
		}

		// Add meta query to query args
		if ( ! empty( $meta_query ) ) {
			$query_args['meta_query'] = array_merge( array( 'relation' => 'AND' ), $meta_query );
		}

		// Run query
		$query = new WP_Query( $query_args );

		// Start output buffering
		ob_start();

		if ( $query->have_posts() ) {
			woocommerce_product_loop_start();

			while ( $query->have_posts() ) {
				$query->the_post();
				wc_get_template_part( 'content', 'product' );
			}

			woocommerce_product_loop_end();
			woocommerce_pagination();
		} else {
			echo '<p class="woocommerce-info">' . esc_html__( 'No products found', 'aqualuxe' ) . '</p>';
		}

		wp_reset_postdata();

		// Get the buffered content
		$html = ob_get_clean();

		// Build filter URL
		$filter_url = $this->build_filter_url( $filter_data );

		wp_send_json_success( array(
			'html'       => $html,
			'filter_url' => $filter_url,
			'count'      => $query->found_posts,
		) );
	}

	/**
	 * Add custom query vars
	 *
	 * @param array $vars Query vars
	 * @return array
	 */
	public function add_query_vars( $vars ) {
		$vars[] = 'min_price';
		$vars[] = 'max_price';
		$vars[] = 'rating_filter';

		// Add attribute filter vars
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		if ( ! empty( $attribute_taxonomies ) ) {
			foreach ( $attribute_taxonomies as $attribute ) {
				$vars[] = 'filter_' . $attribute->attribute_name;
			}
		}

		return $vars;
	}

	/**
	 * Modify product query
	 *
	 * @param WP_Query $query Query object
	 * @param WC_Query $wc_query WC_Query object
	 */
	public function modify_product_query( $query, $wc_query ) {
		if ( ! $query->is_main_query() || ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
			return;
		}

		// Get tax query
		$tax_query = $query->get( 'tax_query' );
		
		if ( ! is_array( $tax_query ) ) {
			$tax_query = array();
		}

		// Get meta query
		$meta_query = $query->get( 'meta_query' );
		
		if ( ! is_array( $meta_query ) ) {
			$meta_query = array();
		}

		// Price filter
		if ( isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) ) {
			$price_range = $this->get_price_range();
			$min_price = isset( $_GET['min_price'] ) ? floatval( $_GET['min_price'] ) : $price_range['min'];
			$max_price = isset( $_GET['max_price'] ) ? floatval( $_GET['max_price'] ) : $price_range['max'];

			$meta_query[] = array(
				'key'     => '_price',
				'value'   => array( $min_price, $max_price ),
				'type'    => 'numeric',
				'compare' => 'BETWEEN',
			);
		}

		// Attribute filters
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		if ( ! empty( $attribute_taxonomies ) ) {
			foreach ( $attribute_taxonomies as $attribute ) {
				$filter_name = 'filter_' . $attribute->attribute_name;
				
				if ( isset( $_GET[ $filter_name ] ) && ! empty( $_GET[ $filter_name ] ) ) {
					$tax_query[] = array(
						'taxonomy' => wc_attribute_taxonomy_name( $attribute->attribute_name ),
						'field'    => 'slug',
						'terms'    => explode( ',', wc_clean( $_GET[ $filter_name ] ) ),
						'operator' => 'IN',
					);
				}
			}
		}

		// Rating filter
		if ( isset( $_GET['rating_filter'] ) && ! empty( $_GET['rating_filter'] ) ) {
			$rating_filter = array_filter( array_map( 'absint', explode( ',', $_GET['rating_filter'] ) ) );
			
			if ( ! empty( $rating_filter ) ) {
				$meta_query[] = array(
					'key'     => '_wc_average_rating',
					'value'   => array( min( $rating_filter ), 5 ),
					'type'    => 'DECIMAL',
					'compare' => 'BETWEEN',
				);
			}
		}

		// Set tax query
		if ( ! empty( $tax_query ) ) {
			$query->set( 'tax_query', array_merge( array( 'relation' => 'AND' ), $tax_query ) );
		}

		// Set meta query
		if ( ! empty( $meta_query ) ) {
			$query->set( 'meta_query', array_merge( array( 'relation' => 'AND' ), $meta_query ) );
		}
	}

	/**
	 * Get price range
	 *
	 * @return array
	 */
	public function get_price_range() {
		global $wpdb;

		$min_price = floor( $wpdb->get_var( "SELECT MIN(meta_value + 0) FROM {$wpdb->postmeta} WHERE meta_key = '_price' AND meta_value > 0" ) );
		$max_price = ceil( $wpdb->get_var( "SELECT MAX(meta_value + 0) FROM {$wpdb->postmeta} WHERE meta_key = '_price'" ) );

		return array(
			'min' => $min_price ? $min_price : 0,
			'max' => $max_price ? $max_price : 1000,
		);
	}

	/**
	 * Get active filters
	 *
	 * @return array
	 */
	public function get_active_filters() {
		$active_filters = array();

		// Price filter
		if ( isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) ) {
			$price_range = $this->get_price_range();
			$min_price = isset( $_GET['min_price'] ) ? floatval( $_GET['min_price'] ) : $price_range['min'];
			$max_price = isset( $_GET['max_price'] ) ? floatval( $_GET['max_price'] ) : $price_range['max'];

			if ( $min_price > $price_range['min'] || $max_price < $price_range['max'] ) {
				$active_filters['price'] = array(
					'price' => sprintf( __( 'Price: %s - %s', 'aqualuxe' ), wc_price( $min_price ), wc_price( $max_price ) ),
				);
			}
		}

		// Category filter
		if ( isset( $_GET['product_cat'] ) && ! empty( $_GET['product_cat'] ) ) {
			$category_slug = wc_clean( $_GET['product_cat'] );
			$category = get_term_by( 'slug', $category_slug, 'product_cat' );

			if ( $category ) {
				$active_filters['product_cat'] = array(
					$category_slug => sprintf( __( 'Category: %s', 'aqualuxe' ), $category->name ),
				);
			}
		}

		// Attribute filters
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		if ( ! empty( $attribute_taxonomies ) ) {
			foreach ( $attribute_taxonomies as $attribute ) {
				$filter_name = 'filter_' . $attribute->attribute_name;
				
				if ( isset( $_GET[ $filter_name ] ) && ! empty( $_GET[ $filter_name ] ) ) {
					$term_slugs = explode( ',', wc_clean( $_GET[ $filter_name ] ) );
					$taxonomy = wc_attribute_taxonomy_name( $attribute->attribute_name );

					foreach ( $term_slugs as $term_slug ) {
						$term = get_term_by( 'slug', $term_slug, $taxonomy );

						if ( $term ) {
							$active_filters[ $filter_name ][ $term_slug ] = sprintf( __( '%s: %s', 'aqualuxe' ), $attribute->attribute_label, $term->name );
						}
					}
				}
			}
		}

		// Rating filter
		if ( isset( $_GET['rating_filter'] ) && ! empty( $_GET['rating_filter'] ) ) {
			$rating_filter = array_filter( array_map( 'absint', explode( ',', $_GET['rating_filter'] ) ) );
			
			if ( ! empty( $rating_filter ) ) {
				foreach ( $rating_filter as $rating ) {
					$active_filters['rating_filter'][ $rating ] = sprintf( __( 'Rating: %d stars and up', 'aqualuxe' ), $rating );
				}
			}
		}

		return $active_filters;
	}

	/**
	 * Get current page URL
	 *
	 * @return string
	 */
	public function get_current_page_url() {
		global $wp;
		return home_url( $wp->request );
	}

	/**
	 * Get reset URL
	 *
	 * @return string
	 */
	public function get_reset_url() {
		global $wp;
		
		if ( is_shop() ) {
			return wc_get_page_permalink( 'shop' );
		} elseif ( is_product_category() || is_product_tag() ) {
			return get_term_link( get_queried_object() );
		}
		
		return home_url( $wp->request );
	}

	/**
	 * Build filter URL
	 *
	 * @param array $filter_data Filter data
	 * @return string
	 */
	public function build_filter_url( $filter_data ) {
		$base_url = $this->get_current_page_url();
		$query_args = array();

		// Add filter data to query args
		foreach ( $filter_data as $key => $value ) {
			if ( ! empty( $value ) ) {
				$query_args[ $key ] = $value;
			}
		}

		// Build URL
		if ( ! empty( $query_args ) ) {
			$url = add_query_arg( $query_args, $base_url );
		} else {
			$url = $base_url;
		}

		return $url;
	}
}

// Initialize advanced filters
new AquaLuxe_Advanced_Filters();

/**
 * Filter Price Widget
 */
class AquaLuxe_Filter_Price_Widget extends WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct(
			'aqualuxe_filter_price',
			esc_html__( 'AquaLuxe: Filter by Price', 'aqualuxe' ),
			array(
				'description' => esc_html__( 'Filter products by price range.', 'aqualuxe' ),
				'classname'   => 'aqualuxe-filter-price',
			)
		);
	}

	/**
	 * Widget output
	 *
	 * @param array $args Widget arguments
	 * @param array $instance Widget instance
	 */
	public function widget( $args, $instance ) {
		if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
			return;
		}

		$title = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : esc_html__( 'Filter by Price', 'aqualuxe' );

		// Get price range
		$price_range = $this->get_price_range();

		// Get min and max prices
		$min_price = isset( $_GET['min_price'] ) ? floatval( $_GET['min_price'] ) : $price_range['min'];
		$max_price = isset( $_GET['max_price'] ) ? floatval( $_GET['max_price'] ) : $price_range['max'];

		echo $args['before_widget'];
		
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo '<div class="price-filter-widget">';
		echo '<div class="price-slider-container">';
		echo '<div class="price-slider"></div>';
		echo '<div class="price-slider-values">';
		echo '<span class="price-slider-min">' . wc_price( $price_range['min'] ) . '</span>';
		echo '<span class="price-slider-max">' . wc_price( $price_range['max'] ) . '</span>';
		echo '</div>';
		echo '<div class="price-slider-inputs">';
		echo '<div class="price-slider-input">';
		echo '<label for="widget-min-price">' . esc_html__( 'Min', 'aqualuxe' ) . '</label>';
		echo '<input type="number" id="widget-min-price" name="min_price" value="' . esc_attr( $min_price ) . '" min="' . esc_attr( $price_range['min'] ) . '" max="' . esc_attr( $price_range['max'] ) . '" step="1" />';
		echo '</div>';
		echo '<div class="price-slider-input">';
		echo '<label for="widget-max-price">' . esc_html__( 'Max', 'aqualuxe' ) . '</label>';
		echo '<input type="number" id="widget-max-price" name="max_price" value="' . esc_attr( $max_price ) . '" min="' . esc_attr( $price_range['min'] ) . '" max="' . esc_attr( $price_range['max'] ) . '" step="1" />';
		echo '</div>';
		echo '</div>';
		echo '<button class="apply-price-filter">' . esc_html__( 'Apply', 'aqualuxe' ) . '</button>';
		echo '</div>';
		echo '</div>';

		echo $args['after_widget'];

		// Add price slider script
		$this->price_slider_script( $price_range, $min_price, $max_price );
	}

	/**
	 * Widget form
	 *
	 * @param array $instance Widget instance
	 * @return void
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Filter by Price', 'aqualuxe' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	/**
	 * Update widget instance
	 *
	 * @param array $new_instance New instance
	 * @param array $old_instance Old instance
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';

		return $instance;
	}

	/**
	 * Get price range
	 *
	 * @return array
	 */
	public function get_price_range() {
		global $wpdb;

		$min_price = floor( $wpdb->get_var( "SELECT MIN(meta_value + 0) FROM {$wpdb->postmeta} WHERE meta_key = '_price' AND meta_value > 0" ) );
		$max_price = ceil( $wpdb->get_var( "SELECT MAX(meta_value + 0) FROM {$wpdb->postmeta} WHERE meta_key = '_price'" ) );

		return array(
			'min' => $min_price ? $min_price : 0,
			'max' => $max_price ? $max_price : 1000,
		);
	}

	/**
	 * Price slider script
	 *
	 * @param array $price_range Price range
	 * @param float $min_price Min price
	 * @param float $max_price Max price
	 */
	public function price_slider_script( $price_range, $min_price, $max_price ) {
		?>
		<script>
			(function($) {
				$(document).ready(function() {
					// Price slider
					var $slider = $('.price-filter-widget .price-slider');
					var $minInput = $('#widget-min-price');
					var $maxInput = $('#widget-max-price');
					var minPrice = <?php echo esc_js( $price_range['min'] ); ?>;
					var maxPrice = <?php echo esc_js( $price_range['max'] ); ?>;
					var currentMinPrice = <?php echo esc_js( $min_price ); ?>;
					var currentMaxPrice = <?php echo esc_js( $max_price ); ?>;

					// Initialize slider
					$slider.slider({
						range: true,
						min: minPrice,
						max: maxPrice,
						values: [currentMinPrice, currentMaxPrice],
						slide: function(event, ui) {
							$minInput.val(ui.values[0]);
							$maxInput.val(ui.values[1]);
						}
					});

					// Update slider when inputs change
					$minInput.on('change', function() {
						var value = parseInt($(this).val());
						
						if (isNaN(value)) {
							value = minPrice;
						}
						
						if (value < minPrice) {
							value = minPrice;
						}
						
						if (value > $maxInput.val()) {
							value = $maxInput.val();
						}
						
						$(this).val(value);
						$slider.slider('values', 0, value);
					});

					$maxInput.on('change', function() {
						var value = parseInt($(this).val());
						
						if (isNaN(value)) {
							value = maxPrice;
						}
						
						if (value > maxPrice) {
							value = maxPrice;
						}
						
						if (value < $minInput.val()) {
							value = $minInput.val();
						}
						
						$(this).val(value);
						$slider.slider('values', 1, value);
					});

					// Apply price filter
					$('.apply-price-filter').on('click', function(e) {
						e.preventDefault();
						
						var minPrice = $minInput.val();
						var maxPrice = $maxInput.val();
						var currentUrl = window.location.href;
						var url = new URL(currentUrl);
						
						// Set min price
						if (minPrice) {
							url.searchParams.set('min_price', minPrice);
						} else {
							url.searchParams.delete('min_price');
						}
						
						// Set max price
						if (maxPrice) {
							url.searchParams.set('max_price', maxPrice);
						} else {
							url.searchParams.delete('max_price');
						}
						
						// Redirect
						window.location.href = url.toString();
					});
				});
			})(jQuery);
		</script>
		<?php
	}
}

/**
 * Filter Attribute Widget
 */
class AquaLuxe_Filter_Attribute_Widget extends WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct(
			'aqualuxe_filter_attribute',
			esc_html__( 'AquaLuxe: Filter by Attribute', 'aqualuxe' ),
			array(
				'description' => esc_html__( 'Filter products by attribute.', 'aqualuxe' ),
				'classname'   => 'aqualuxe-filter-attribute',
			)
		);
	}

	/**
	 * Widget output
	 *
	 * @param array $args Widget arguments
	 * @param array $instance Widget instance
	 */
	public function widget( $args, $instance ) {
		if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
			return;
		}

		$attribute = ! empty( $instance['attribute'] ) ? $instance['attribute'] : '';
		
		if ( empty( $attribute ) ) {
			return;
		}

		$attribute_taxonomy = wc_get_attribute( $attribute );
		
		if ( ! $attribute_taxonomy ) {
			return;
		}

		$title = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : $attribute_taxonomy->name;
		$display_type = ! empty( $instance['display_type'] ) ? $instance['display_type'] : 'list';
		$query_type = ! empty( $instance['query_type'] ) ? $instance['query_type'] : 'and';

		$taxonomy = wc_attribute_taxonomy_name( $attribute_taxonomy->slug );
		$terms = get_terms( array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
		) );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return;
		}

		$filter_name = 'filter_' . $attribute_taxonomy->slug;
		$current_values = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( $_GET[ $filter_name ] ) ) : array();

		echo $args['before_widget'];
		
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo '<div class="attribute-filter-widget attribute-' . esc_attr( $attribute_taxonomy->slug ) . '">';
		
		if ( 'list' === $display_type ) {
			echo '<ul class="attribute-filter-list">';
			
			foreach ( $terms as $term ) {
				$selected = in_array( $term->slug, $current_values );
				$filter_link = $this->get_filter_link( $filter_name, $term->slug, $current_values, $query_type );
				
				echo '<li class="attribute-filter-item' . ( $selected ? ' selected' : '' ) . '">';
				echo '<a href="' . esc_url( $filter_link ) . '">';
				echo '<span class="filter-checkbox' . ( $selected ? ' checked' : '' ) . '"></span>';
				echo esc_html( $term->name );
				echo '<span class="count">(' . esc_html( $term->count ) . ')</span>';
				echo '</a>';
				echo '</li>';
			}
			
			echo '</ul>';
		} elseif ( 'dropdown' === $display_type ) {
			echo '<select class="attribute-filter-dropdown" data-filter-name="' . esc_attr( $filter_name ) . '" data-query-type="' . esc_attr( $query_type ) . '">';
			echo '<option value="">' . esc_html__( 'Any', 'aqualuxe' ) . '</option>';
			
			foreach ( $terms as $term ) {
				$selected = in_array( $term->slug, $current_values );
				echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( $selected, true, false ) . '>' . esc_html( $term->name ) . ' (' . esc_html( $term->count ) . ')</option>';
			}
			
			echo '</select>';
		} elseif ( 'color' === $display_type ) {
			echo '<ul class="attribute-filter-colors">';
			
			foreach ( $terms as $term ) {
				$selected = in_array( $term->slug, $current_values );
				$filter_link = $this->get_filter_link( $filter_name, $term->slug, $current_values, $query_type );
				$color = get_term_meta( $term->term_id, 'color', true );
				
				if ( ! $color ) {
					$color = '#eeeeee';
				}
				
				echo '<li class="attribute-filter-color' . ( $selected ? ' selected' : '' ) . '">';
				echo '<a href="' . esc_url( $filter_link ) . '" title="' . esc_attr( $term->name ) . '">';
				echo '<span class="color-swatch" style="background-color: ' . esc_attr( $color ) . ';"></span>';
				echo '</a>';
				echo '</li>';
			}
			
			echo '</ul>';
		}
		
		echo '</div>';

		echo $args['after_widget'];

		// Add dropdown script
		if ( 'dropdown' === $display_type ) {
			$this->dropdown_script();
		}
	}

	/**
	 * Widget form
	 *
	 * @param array $instance Widget instance
	 * @return void
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$attribute = ! empty( $instance['attribute'] ) ? $instance['attribute'] : '';
		$display_type = ! empty( $instance['display_type'] ) ? $instance['display_type'] : 'list';
		$query_type = ! empty( $instance['query_type'] ) ? $instance['query_type'] : 'and';

		// Get attribute taxonomies
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'attribute' ) ); ?>"><?php esc_html_e( 'Attribute:', 'aqualuxe' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'attribute' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'attribute' ) ); ?>">
				<option value=""><?php esc_html_e( 'Select an attribute', 'aqualuxe' ); ?></option>
				<?php foreach ( $attribute_taxonomies as $tax ) : ?>
					<option value="<?php echo esc_attr( $tax->id ); ?>" <?php selected( $attribute, $tax->id ); ?>><?php echo esc_html( $tax->name ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'display_type' ) ); ?>"><?php esc_html_e( 'Display Type:', 'aqualuxe' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'display_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_type' ) ); ?>">
				<option value="list" <?php selected( $display_type, 'list' ); ?>><?php esc_html_e( 'List', 'aqualuxe' ); ?></option>
				<option value="dropdown" <?php selected( $display_type, 'dropdown' ); ?>><?php esc_html_e( 'Dropdown', 'aqualuxe' ); ?></option>
				<option value="color" <?php selected( $display_type, 'color' ); ?>><?php esc_html_e( 'Color', 'aqualuxe' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'query_type' ) ); ?>"><?php esc_html_e( 'Query Type:', 'aqualuxe' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'query_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'query_type' ) ); ?>">
				<option value="and" <?php selected( $query_type, 'and' ); ?>><?php esc_html_e( 'AND', 'aqualuxe' ); ?></option>
				<option value="or" <?php selected( $query_type, 'or' ); ?>><?php esc_html_e( 'OR', 'aqualuxe' ); ?></option>
			</select>
		</p>
		<?php
	}

	/**
	 * Update widget instance
	 *
	 * @param array $new_instance New instance
	 * @param array $old_instance Old instance
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['attribute'] = ! empty( $new_instance['attribute'] ) ? absint( $new_instance['attribute'] ) : '';
		$instance['display_type'] = ! empty( $new_instance['display_type'] ) ? sanitize_text_field( $new_instance['display_type'] ) : 'list';
		$instance['query_type'] = ! empty( $new_instance['query_type'] ) ? sanitize_text_field( $new_instance['query_type'] ) : 'and';

		return $instance;
	}

	/**
	 * Get filter link
	 *
	 * @param string $filter_name Filter name
	 * @param string $term_slug Term slug
	 * @param array $current_values Current values
	 * @param string $query_type Query type
	 * @return string
	 */
	public function get_filter_link( $filter_name, $term_slug, $current_values, $query_type ) {
		$current_url = $this->get_current_page_url();
		$url = new URL( $current_url );

		if ( in_array( $term_slug, $current_values ) ) {
			// Remove term
			$new_values = array_diff( $current_values, array( $term_slug ) );
			
			if ( empty( $new_values ) ) {
				$url->searchParams->delete( $filter_name );
			} else {
				$url->searchParams->set( $filter_name, implode( ',', $new_values ) );
			}
		} else {
			// Add term
			if ( 'or' === $query_type ) {
				$new_values = array_merge( $current_values, array( $term_slug ) );
				$url->searchParams->set( $filter_name, implode( ',', $new_values ) );
			} else {
				$url->searchParams->set( $filter_name, $term_slug );
			}
		}

		return $url->toString();
	}

	/**
	 * Get current page URL
	 *
	 * @return string
	 */
	public function get_current_page_url() {
		global $wp;
		return home_url( $wp->request );
	}

	/**
	 * Dropdown script
	 */
	public function dropdown_script() {
		?>
		<script>
			(function($) {
				$(document).ready(function() {
					$('.attribute-filter-dropdown').on('change', function() {
						var filterName = $(this).data('filter-name');
						var queryType = $(this).data('query-type');
						var value = $(this).val();
						var currentUrl = window.location.href;
						var url = new URL(currentUrl);
						
						if (value) {
							url.searchParams.set(filterName, value);
						} else {
							url.searchParams.delete(filterName);
						}
						
						window.location.href = url.toString();
					});
				});
			})(jQuery);
		</script>
		<?php
	}
}

/**
 * Filter Category Widget
 */
class AquaLuxe_Filter_Category_Widget extends WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct(
			'aqualuxe_filter_category',
			esc_html__( 'AquaLuxe: Filter by Category', 'aqualuxe' ),
			array(
				'description' => esc_html__( 'Filter products by category.', 'aqualuxe' ),
				'classname'   => 'aqualuxe-filter-category',
			)
		);
	}

	/**
	 * Widget output
	 *
	 * @param array $args Widget arguments
	 * @param array $instance Widget instance
	 */
	public function widget( $args, $instance ) {
		if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
			return;
		}

		$title = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : esc_html__( 'Product Categories', 'aqualuxe' );
		$hierarchical = ! empty( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : true;
		$show_count = ! empty( $instance['show_count'] ) ? (bool) $instance['show_count'] : true;
		$hide_empty = ! empty( $instance['hide_empty'] ) ? (bool) $instance['hide_empty'] : true;

		$product_categories = get_terms( array(
			'taxonomy'     => 'product_cat',
			'hide_empty'   => $hide_empty,
			'hierarchical' => $hierarchical,
		) );

		if ( empty( $product_categories ) || is_wp_error( $product_categories ) ) {
			return;
		}

		$current_category = get_queried_object();
		$current_category_id = $current_category && isset( $current_category->term_id ) ? $current_category->term_id : 0;
		$current_category_slug = isset( $_GET['product_cat'] ) ? wc_clean( $_GET['product_cat'] ) : '';

		echo $args['before_widget'];
		
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo '<ul class="product-categories">';
		
		foreach ( $product_categories as $category ) {
			// Skip current category if we're on a category page
			if ( $category->term_id === $current_category_id ) {
				continue;
			}

			$selected = $category->slug === $current_category_slug;
			$category_link = $this->get_category_link( $category->slug, $current_category_slug );
			
			echo '<li class="cat-item cat-item-' . esc_attr( $category->term_id ) . ( $selected ? ' current-cat' : '' ) . '">';
			echo '<a href="' . esc_url( $category_link ) . '">';
			echo esc_html( $category->name );
			
			if ( $show_count ) {
				echo ' <span class="count">(' . esc_html( $category->count ) . ')</span>';
			}
			
			echo '</a>';
			
			// Display child categories if hierarchical
			if ( $hierarchical ) {
				$child_categories = get_terms( array(
					'taxonomy'     => 'product_cat',
					'hide_empty'   => $hide_empty,
					'hierarchical' => true,
					'parent'       => $category->term_id,
				) );
				
				if ( ! empty( $child_categories ) && ! is_wp_error( $child_categories ) ) {
					echo '<ul class="children">';
					
					foreach ( $child_categories as $child_category ) {
						$child_selected = $child_category->slug === $current_category_slug;
						$child_category_link = $this->get_category_link( $child_category->slug, $current_category_slug );
						
						echo '<li class="cat-item cat-item-' . esc_attr( $child_category->term_id ) . ( $child_selected ? ' current-cat' : '' ) . '">';
						echo '<a href="' . esc_url( $child_category_link ) . '">';
						echo esc_html( $child_category->name );
						
						if ( $show_count ) {
							echo ' <span class="count">(' . esc_html( $child_category->count ) . ')</span>';
						}
						
						echo '</a>';
						echo '</li>';
					}
					
					echo '</ul>';
				}
			}
			
			echo '</li>';
		}
		
		echo '</ul>';

		echo $args['after_widget'];
	}

	/**
	 * Widget form
	 *
	 * @param array $instance Widget instance
	 * @return void
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Product Categories', 'aqualuxe' );
		$hierarchical = ! empty( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : true;
		$show_count = ! empty( $instance['show_count'] ) ? (bool) $instance['show_count'] : true;
		$hide_empty = ! empty( $instance['hide_empty'] ) ? (bool) $instance['hide_empty'] : true;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hierarchical' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hierarchical' ) ); ?>" <?php checked( $hierarchical ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'hierarchical' ) ); ?>"><?php esc_html_e( 'Show hierarchy', 'aqualuxe' ); ?></label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_count' ) ); ?>" <?php checked( $show_count ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>"><?php esc_html_e( 'Show product counts', 'aqualuxe' ); ?></label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hide_empty' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hide_empty' ) ); ?>" <?php checked( $hide_empty ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'hide_empty' ) ); ?>"><?php esc_html_e( 'Hide empty categories', 'aqualuxe' ); ?></label>
		</p>
		<?php
	}

	/**
	 * Update widget instance
	 *
	 * @param array $new_instance New instance
	 * @param array $old_instance Old instance
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['hierarchical'] = ! empty( $new_instance['hierarchical'] ) ? (bool) $new_instance['hierarchical'] : false;
		$instance['show_count'] = ! empty( $new_instance['show_count'] ) ? (bool) $new_instance['show_count'] : false;
		$instance['hide_empty'] = ! empty( $new_instance['hide_empty'] ) ? (bool) $new_instance['hide_empty'] : false;

		return $instance;
	}

	/**
	 * Get category link
	 *
	 * @param string $category_slug Category slug
	 * @param string $current_category_slug Current category slug
	 * @return string
	 */
	public function get_category_link( $category_slug, $current_category_slug ) {
		if ( $category_slug === $current_category_slug ) {
			// Remove category filter
			$current_url = $this->get_current_page_url();
			$url = new URL( $current_url );
			$url->searchParams->delete( 'product_cat' );
			return $url->toString();
		} else {
			// Add category filter
			$current_url = $this->get_current_page_url();
			$url = new URL( $current_url );
			$url->searchParams->set( 'product_cat', $category_slug );
			return $url->toString();
		}
	}

	/**
	 * Get current page URL
	 *
	 * @return string
	 */
	public function get_current_page_url() {
		global $wp;
		return home_url( $wp->request );
	}
}

/**
 * Filter Rating Widget
 */
class AquaLuxe_Filter_Rating_Widget extends WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct(
			'aqualuxe_filter_rating',
			esc_html__( 'AquaLuxe: Filter by Rating', 'aqualuxe' ),
			array(
				'description' => esc_html__( 'Filter products by rating.', 'aqualuxe' ),
				'classname'   => 'aqualuxe-filter-rating',
			)
		);
	}

	/**
	 * Widget output
	 *
	 * @param array $args Widget arguments
	 * @param array $instance Widget instance
	 */
	public function widget( $args, $instance ) {
		if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
			return;
		}

		if ( ! wc_review_ratings_enabled() ) {
			return;
		}

		$title = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : esc_html__( 'Average Rating', 'aqualuxe' );
		$rating_filter = isset( $_GET['rating_filter'] ) ? array_filter( array_map( 'absint', explode( ',', $_GET['rating_filter'] ) ) ) : array();

		echo $args['before_widget'];
		
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo '<ul class="rating-filter-list">';
		
		for ( $rating = 5; $rating >= 1; $rating-- ) {
			$selected = in_array( $rating, $rating_filter );
			$filter_link = $this->get_rating_filter_link( $rating, $rating_filter );
			
			echo '<li class="rating-filter-item' . ( $selected ? ' selected' : '' ) . '">';
			echo '<a href="' . esc_url( $filter_link ) . '">';
			
			// Display stars
			for ( $i = 1; $i <= 5; $i++ ) {
				if ( $i <= $rating ) {
					echo '<svg class="icon icon-star" aria-hidden="true" focusable="false"><use xlink:href="#icon-star"></use></svg>';
				} else {
					echo '<svg class="icon icon-star-empty" aria-hidden="true" focusable="false"><use xlink:href="#icon-star-empty"></use></svg>';
				}
			}
			
			echo '<span class="rating-text">' . sprintf( esc_html__( '%d star and up', 'aqualuxe' ), $rating ) . '</span>';
			echo '</a>';
			echo '</li>';
		}
		
		echo '</ul>';

		echo $args['after_widget'];
	}

	/**
	 * Widget form
	 *
	 * @param array $instance Widget instance
	 * @return void
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Average Rating', 'aqualuxe' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	/**
	 * Update widget instance
	 *
	 * @param array $new_instance New instance
	 * @param array $old_instance Old instance
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';

		return $instance;
	}

	/**
	 * Get rating filter link
	 *
	 * @param int $rating Rating
	 * @param array $current_ratings Current ratings
	 * @return string
	 */
	public function get_rating_filter_link( $rating, $current_ratings ) {
		$current_url = $this->get_current_page_url();
		$url = new URL( $current_url );

		if ( in_array( $rating, $current_ratings ) ) {
			// Remove rating
			$new_ratings = array_diff( $current_ratings, array( $rating ) );
			
			if ( empty( $new_ratings ) ) {
				$url->searchParams->delete( 'rating_filter' );
			} else {
				$url->searchParams->set( 'rating_filter', implode( ',', $new_ratings ) );
			}
		} else {
			// Add rating
			$new_ratings = array_merge( $current_ratings, array( $rating ) );
			$url->searchParams->set( 'rating_filter', implode( ',', $new_ratings ) );
		}

		return $url->toString();
	}

	/**
	 * Get current page URL
	 *
	 * @return string
	 */
	public function get_current_page_url() {
		global $wp;
		return home_url( $wp->request );
	}
}