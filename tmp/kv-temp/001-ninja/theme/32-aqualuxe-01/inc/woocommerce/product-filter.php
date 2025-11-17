<?php
/**
 * AquaLuxe Product Filter
 *
 * AJAX-powered product filtering for WooCommerce
 *
 * @package AquaLuxe
 * @since 1.2.0
 */

namespace AquaLuxe\WooCommerce;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Product Filter Class
 *
 * Handles AJAX product filtering functionality.
 *
 * @since 1.2.0
 */
class Product_Filter {

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Register hooks.
		$this->register_hooks();
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		// Enqueue scripts and styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Add filter widget area.
		add_action( 'widgets_init', array( $this, 'register_filter_sidebar' ) );

		// Add filter template.
		add_action( 'woocommerce_before_shop_loop', array( $this, 'render_filter_sidebar' ), 20 );
		add_action( 'woocommerce_before_shop_loop', array( $this, 'render_active_filters' ), 25 );

		// AJAX handlers.
		add_action( 'wp_ajax_aqualuxe_filter_products', array( $this, 'ajax_filter_products' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_filter_products', array( $this, 'ajax_filter_products' ) );

		// Add filter button.
		add_action( 'woocommerce_before_shop_loop', array( $this, 'render_filter_button' ), 15 );

		// Add body class.
		add_filter( 'body_class', array( $this, 'add_body_class' ) );
	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		// Only enqueue on shop pages.
		if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
			return;
		}

		// Enqueue jQuery UI.
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_style( 'jquery-ui-style', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );

		// Enqueue filter script.
		wp_enqueue_script(
			'aqualuxe-product-filter',
			AQUALUXE_URI . '/assets/js/product-filter.js',
			array( 'jquery', 'jquery-ui-slider' ),
			AQUALUXE_VERSION,
			true
		);

		// Enqueue filter style.
		wp_enqueue_style(
			'aqualuxe-product-filter',
			AQUALUXE_URI . '/assets/css/product-filter.css',
			array(),
			AQUALUXE_VERSION
		);

		// Localize script.
		wp_localize_script(
			'aqualuxe-product-filter',
			'aqualuxeFilter',
			array(
				'ajax_url'     => admin_url( 'admin-ajax.php' ),
				'nonce'        => wp_create_nonce( 'aqualuxe-filter-nonce' ),
				'ajax_enabled' => get_theme_mod( 'aqualuxe_enable_ajax_filter', '1' ),
				'currency'     => get_woocommerce_currency_symbol(),
				'i18n'         => array(
					'loading'      => __( 'Loading products...', 'aqualuxe' ),
					'no_products'  => __( 'No products found.', 'aqualuxe' ),
					'show_filters' => __( 'Show Filters', 'aqualuxe' ),
					'hide_filters' => __( 'Hide Filters', 'aqualuxe' ),
					'filter'       => __( 'Filter', 'aqualuxe' ),
					'clear'        => __( 'Clear', 'aqualuxe' ),
					'apply'        => __( 'Apply Filters', 'aqualuxe' ),
					'reset'        => __( 'Reset Filters', 'aqualuxe' ),
					'price'        => __( 'Price', 'aqualuxe' ),
					'min'          => __( 'Min', 'aqualuxe' ),
					'max'          => __( 'Max', 'aqualuxe' ),
				),
			)
		);
	}

	/**
	 * Register filter sidebar.
	 *
	 * @return void
	 */
	public function register_filter_sidebar() {
		register_sidebar(
			array(
				'name'          => __( 'Product Filter Sidebar', 'aqualuxe' ),
				'id'            => 'product-filter-sidebar',
				'description'   => __( 'Add widgets here to appear in the product filter sidebar.', 'aqualuxe' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}

	/**
	 * Render filter sidebar.
	 *
	 * @return void
	 */
	public function render_filter_sidebar() {
		// Only show on shop pages.
		if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
			return;
		}

		// Check if filter is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_product_filter', true ) ) {
			return;
		}

		// Get filter style.
		$filter_style = get_theme_mod( 'aqualuxe_product_filter_style', 'sidebar' );

		// Load filter template.
		get_template_part( 'template-parts/woocommerce/filter', $filter_style );
	}

	/**
	 * Render active filters.
	 *
	 * @return void
	 */
	public function render_active_filters() {
		// Only show on shop pages.
		if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
			return;
		}

		// Check if filter is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_product_filter', true ) ) {
			return;
		}

		// Get active filters.
		$active_filters = $this->get_active_filters();

		// If no active filters, return.
		if ( empty( $active_filters ) ) {
			echo '<div class="aqualuxe-active-filters" style="display: none;"></div>';
			return;
		}

		// Start output.
		echo '<div class="aqualuxe-active-filters">';

		// Loop through active filters.
		foreach ( $active_filters as $filter => $values ) {
			if ( 'price' === $filter ) {
				$min = isset( $values['min'] ) ? $values['min'] : '';
				$max = isset( $values['max'] ) ? $values['max'] : '';

				echo '<span class="aqualuxe-active-filter">';
				echo '<span class="aqualuxe-active-filter-name">' . esc_html__( 'Price:', 'aqualuxe' ) . '</span> ';
				echo '<span class="aqualuxe-active-filter-value">' . esc_html( wc_price( $min ) ) . ' - ' . esc_html( wc_price( $max ) ) . '</span>';
				echo '<a href="#" class="aqualuxe-active-filter-remove" data-filter="price"><i class="fas fa-times"></i></a>';
				echo '</span>';
			} elseif ( 'rating' === $filter ) {
				echo '<span class="aqualuxe-active-filter">';
				echo '<span class="aqualuxe-active-filter-name">' . esc_html__( 'Rating:', 'aqualuxe' ) . '</span> ';
				echo '<span class="aqualuxe-active-filter-value">' . esc_html( $values ) . '+ ' . esc_html__( 'stars', 'aqualuxe' ) . '</span>';
				echo '<a href="#" class="aqualuxe-active-filter-remove" data-filter="rating"><i class="fas fa-times"></i></a>';
				echo '</span>';
			} else {
				$filter_name = str_replace( array( '_', '-' ), ' ', $filter );
				$filter_name = ucwords( $filter_name );

				if ( is_array( $values ) ) {
					foreach ( $values as $value ) {
						$label = $this->get_filter_label( $filter, $value );

						echo '<span class="aqualuxe-active-filter">';
						echo '<span class="aqualuxe-active-filter-name">' . esc_html( $filter_name ) . ':</span> ';
						echo '<span class="aqualuxe-active-filter-value">' . esc_html( $label ) . '</span>';
						echo '<a href="#" class="aqualuxe-active-filter-remove" data-filter="' . esc_attr( $filter ) . '" data-value="' . esc_attr( $value ) . '"><i class="fas fa-times"></i></a>';
						echo '</span>';
					}
				} else {
					$label = $this->get_filter_label( $filter, $values );

					echo '<span class="aqualuxe-active-filter">';
					echo '<span class="aqualuxe-active-filter-name">' . esc_html( $filter_name ) . ':</span> ';
					echo '<span class="aqualuxe-active-filter-value">' . esc_html( $label ) . '</span>';
					echo '<a href="#" class="aqualuxe-active-filter-remove" data-filter="' . esc_attr( $filter ) . '" data-value="' . esc_attr( $values ) . '"><i class="fas fa-times"></i></a>';
					echo '</span>';
				}
			}
		}

		// Add clear all button.
		echo '<a href="#" class="aqualuxe-active-filter-clear">' . esc_html__( 'Clear All', 'aqualuxe' ) . '</a>';

		// End output.
		echo '</div>';
	}

	/**
	 * Get active filters.
	 *
	 * @return array
	 */
	public function get_active_filters() {
		$active_filters = array();

		// Get query vars.
		$query_vars = $_GET;

		// Remove pagination and ordering.
		unset( $query_vars['paged'] );
		unset( $query_vars['orderby'] );
		unset( $query_vars['order'] );

		// Loop through query vars.
		foreach ( $query_vars as $key => $value ) {
			// Skip empty values.
			if ( empty( $value ) ) {
				continue;
			}

			// Handle price.
			if ( 'min_price' === $key || 'max_price' === $key ) {
				$price_key = str_replace( '_price', '', $key );
				$active_filters['price'][ $price_key ] = $value;
				continue;
			}

			// Handle arrays.
			if ( is_array( $value ) ) {
				$active_filters[ $key ] = $value;
				continue;
			}

			// Handle other filters.
			$active_filters[ $key ] = $value;
		}

		return $active_filters;
	}

	/**
	 * Get filter label.
	 *
	 * @param string $filter Filter key.
	 * @param string $value  Filter value.
	 * @return string
	 */
	public function get_filter_label( $filter, $value ) {
		// Handle taxonomies.
		if ( taxonomy_exists( $filter ) ) {
			$term = get_term_by( 'slug', $value, $filter );
			if ( $term ) {
				return $term->name;
			}
		}

		// Handle attributes.
		if ( 0 === strpos( $filter, 'pa_' ) ) {
			$taxonomy = $filter;
			$term     = get_term_by( 'slug', $value, $taxonomy );
			if ( $term ) {
				return $term->name;
			}
		}

		// Fallback to value.
		return $value;
	}

	/**
	 * Render filter button.
	 *
	 * @return void
	 */
	public function render_filter_button() {
		// Only show on shop pages.
		if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
			return;
		}

		// Check if filter is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_product_filter', true ) ) {
			return;
		}

		// Get filter style.
		$filter_style = get_theme_mod( 'aqualuxe_product_filter_style', 'sidebar' );

		// Only show button for sidebar style.
		if ( 'sidebar' !== $filter_style ) {
			return;
		}

		echo '<button class="aqualuxe-filter-button button">';
		echo '<i class="fas fa-filter"></i> ';
		echo esc_html__( 'Filter Products', 'aqualuxe' );
		echo '</button>';
	}

	/**
	 * Add body class.
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	public function add_body_class( $classes ) {
		// Only add on shop pages.
		if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
			return $classes;
		}

		// Check if filter is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_product_filter', true ) ) {
			return $classes;
		}

		// Get filter style.
		$filter_style = get_theme_mod( 'aqualuxe_product_filter_style', 'sidebar' );

		// Add filter style class.
		$classes[] = 'aqualuxe-filter-style-' . $filter_style;

		return $classes;
	}

	/**
	 * AJAX filter products.
	 *
	 * @return void
	 */
	public function ajax_filter_products() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-filter-nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ) );
		}

		// Get filters.
		$filters = isset( $_POST['filters'] ) ? $_POST['filters'] : array();

		// Build query args.
		$args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => get_option( 'posts_per_page' ),
		);

		// Handle pagination.
		if ( isset( $filters['page'] ) ) {
			$args['paged'] = absint( $filters['page'] );
		}

		// Handle orderby.
		if ( isset( $filters['orderby'] ) ) {
			$orderby = $filters['orderby'];

			switch ( $orderby ) {
				case 'price':
					$args['meta_key'] = '_price';
					$args['orderby']  = 'meta_value_num';
					$args['order']    = 'ASC';
					break;
				case 'price-desc':
					$args['meta_key'] = '_price';
					$args['orderby']  = 'meta_value_num';
					$args['order']    = 'DESC';
					break;
				case 'popularity':
					$args['meta_key'] = 'total_sales';
					$args['orderby']  = 'meta_value_num';
					$args['order']    = 'DESC';
					break;
				case 'rating':
					$args['meta_key'] = '_wc_average_rating';
					$args['orderby']  = 'meta_value_num';
					$args['order']    = 'DESC';
					break;
				case 'date':
					$args['orderby'] = 'date';
					$args['order']   = 'DESC';
					break;
				default:
					$args['orderby'] = 'menu_order title';
					$args['order']   = 'ASC';
					break;
			}
		}

		// Handle price filter.
		if ( isset( $filters['price'] ) && is_array( $filters['price'] ) ) {
			$price_filter = array();

			if ( isset( $filters['price']['min'] ) && '' !== $filters['price']['min'] ) {
				$price_filter['min_price'] = floatval( $filters['price']['min'] );
			}

			if ( isset( $filters['price']['max'] ) && '' !== $filters['price']['max'] ) {
				$price_filter['max_price'] = floatval( $filters['price']['max'] );
			}

			if ( ! empty( $price_filter ) ) {
				$args['meta_query'] = array( $price_filter );
			}
		}

		// Handle rating filter.
		if ( isset( $filters['rating'] ) && '' !== $filters['rating'] ) {
			$rating = absint( $filters['rating'] );

			if ( ! isset( $args['meta_query'] ) ) {
				$args['meta_query'] = array();
			}

			$args['meta_query'][] = array(
				'key'     => '_wc_average_rating',
				'value'   => $rating,
				'compare' => '>=',
				'type'    => 'DECIMAL',
			);
		}

		// Handle taxonomy filters.
		$tax_query = array();

		foreach ( $filters as $key => $value ) {
			// Skip non-taxonomy filters.
			if ( in_array( $key, array( 'page', 'orderby', 'price', 'rating' ), true ) ) {
				continue;
			}

			// Check if taxonomy exists.
			if ( taxonomy_exists( $key ) ) {
				$terms = is_array( $value ) ? $value : array( $value );

				$tax_query[] = array(
					'taxonomy' => $key,
					'field'    => 'slug',
					'terms'    => $terms,
					'operator' => 'IN',
				);
			}
		}

		// Add tax query to args.
		if ( ! empty( $tax_query ) ) {
			$args['tax_query'] = $tax_query;
		}

		// Run query.
		$query = new \WP_Query( $args );

		// Start output buffer.
		ob_start();

		if ( $query->have_posts() ) {
			// Loop through products.
			woocommerce_product_loop_start();

			while ( $query->have_posts() ) {
				$query->the_post();
				wc_get_template_part( 'content', 'product' );
			}

			woocommerce_product_loop_end();

			// Pagination.
			$pagination = paginate_links(
				array(
					'base'      => esc_url_raw( str_replace( 999999999, '%#%', get_pagenum_link( 999999999, false ) ) ),
					'format'    => '',
					'add_args'  => false,
					'current'   => max( 1, $query->get( 'paged' ) ),
					'total'     => $query->max_num_pages,
					'prev_text' => '&larr;',
					'next_text' => '&rarr;',
					'type'      => 'list',
					'end_size'  => 3,
					'mid_size'  => 3,
				)
			);

			// Result count.
			$count = wc_get_loop_prop( 'total' );
			$per_page = $query->get( 'posts_per_page' );
			$current = $query->get( 'paged' ) ? $query->get( 'paged' ) : 1;
			$first = ( $per_page * $current ) - $per_page + 1;
			$last = min( $count, $per_page * $current );

			$result_count = sprintf(
				_n( 'Showing the single result', 'Showing %1$d&ndash;%2$d of %3$d results', $count, 'aqualuxe' ),
				$first,
				$last,
				$count
			);

			if ( 1 === $count ) {
				$result_count = __( 'Showing the single result', 'aqualuxe' );
			} elseif ( $count <= $per_page || -1 === $per_page ) {
				$result_count = sprintf( _n( 'Showing all %d result', 'Showing all %d results', $count, 'aqualuxe' ), $count );
			}

			// Send response.
			wp_send_json_success(
				array(
					'products'   => ob_get_clean(),
					'pagination' => $pagination,
					'count'      => $result_count,
				)
			);
		} else {
			// No products found.
			echo '<p class="woocommerce-info">' . esc_html__( 'No products found.', 'aqualuxe' ) . '</p>';

			// Send response.
			wp_send_json_success(
				array(
					'products'   => ob_get_clean(),
					'pagination' => '',
					'count'      => __( 'No products found', 'aqualuxe' ),
				)
			);
		}
	}
}