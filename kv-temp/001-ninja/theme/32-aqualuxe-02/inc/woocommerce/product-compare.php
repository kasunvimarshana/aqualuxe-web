<?php
/**
 * AquaLuxe Product Comparison
 *
 * Adds product comparison functionality to WooCommerce
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
 * Product Comparison Class
 *
 * Handles product comparison functionality.
 *
 * @since 1.2.0
 */
class Product_Compare {

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
		// Check if product comparison is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_product_compare', true ) ) {
			return;
		}

		// Add compare button to product.
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'add_compare_button' ), 20 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'add_compare_button' ), 35 );

		// Add compare page template.
		add_filter( 'page_template', array( $this, 'register_compare_page_template' ) );
		add_filter( 'theme_page_templates', array( $this, 'add_compare_page_template' ) );

		// Add compare bar.
		add_action( 'wp_footer', array( $this, 'add_compare_bar' ) );

		// AJAX handlers.
		add_action( 'wp_ajax_aqualuxe_add_to_compare', array( $this, 'add_to_compare_ajax' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_add_to_compare', array( $this, 'add_to_compare_ajax' ) );
		add_action( 'wp_ajax_aqualuxe_remove_from_compare', array( $this, 'remove_from_compare_ajax' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_remove_from_compare', array( $this, 'remove_from_compare_ajax' ) );
		add_action( 'wp_ajax_aqualuxe_get_compare_table', array( $this, 'get_compare_table_ajax' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_get_compare_table', array( $this, 'get_compare_table_ajax' ) );

		// Enqueue scripts and styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Add shortcode.
		add_shortcode( 'aqualuxe_product_compare', array( $this, 'compare_shortcode' ) );
	}

	/**
	 * Add compare button to product.
	 *
	 * @return void
	 */
	public function add_compare_button() {
		global $product;

		// Check if product exists.
		if ( ! $product ) {
			return;
		}

		// Get product ID.
		$product_id = $product->get_id();

		// Check if product is in compare list.
		$compare_list = $this->get_compare_list();
		$in_compare = in_array( $product_id, $compare_list, true );

		// Get button text.
		$button_text = $in_compare ? __( 'Remove from Compare', 'aqualuxe' ) : __( 'Compare', 'aqualuxe' );

		// Get button class.
		$button_class = $in_compare ? 'aqualuxe-compare-button in-compare' : 'aqualuxe-compare-button';

		// Output button.
		echo '<div class="aqualuxe-compare-button-wrapper">';
		echo '<button type="button" class="' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '">';
		echo '<span class="compare-icon">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M15 3H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v2c0 1.1.9 2 2 2h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L9.83 23l6.59-6.59c.36-.36.58-.86.58-1.41V5c0-1.1-.9-2-2-2zm0 12l-4.34 4.34L12 14H3v-2l3-7h9v10zm4-12h4v12h-4z"/></svg>';
		echo '</span>';
		echo '<span class="compare-text">' . esc_html( $button_text ) . '</span>';
		echo '</button>';
		echo '</div>';
	}

	/**
	 * Register compare page template.
	 *
	 * @param string $page_template Page template.
	 * @return string
	 */
	public function register_compare_page_template( $page_template ) {
		if ( get_page_template_slug() === 'template-compare.php' ) {
			$page_template = AQUALUXE_DIR . '/template-parts/woocommerce/template-compare.php';
		}
		return $page_template;
	}

	/**
	 * Add compare page template.
	 *
	 * @param array $templates Page templates.
	 * @return array
	 */
	public function add_compare_page_template( $templates ) {
		$templates['template-compare.php'] = __( 'Product Comparison', 'aqualuxe' );
		return $templates;
	}

	/**
	 * Add compare bar.
	 *
	 * @return void
	 */
	public function add_compare_bar() {
		// Get compare list.
		$compare_list = $this->get_compare_list();

		// Skip if compare list is empty.
		if ( empty( $compare_list ) ) {
			return;
		}

		// Get compare page URL.
		$compare_page_id = get_theme_mod( 'aqualuxe_compare_page', 0 );
		$compare_page_url = $compare_page_id ? get_permalink( $compare_page_id ) : home_url( '/compare/' );

		// Get products.
		$products = array();
		foreach ( $compare_list as $product_id ) {
			$product = wc_get_product( $product_id );
			if ( $product ) {
				$products[] = $product;
			}
		}

		// Output compare bar.
		?>
		<div class="aqualuxe-compare-bar">
			<div class="aqualuxe-compare-bar-inner">
				<div class="aqualuxe-compare-bar-title">
					<?php echo esc_html__( 'Compare Products', 'aqualuxe' ); ?>
					<span class="aqualuxe-compare-count"><?php echo count( $products ); ?></span>
				</div>
				<div class="aqualuxe-compare-bar-products">
					<?php foreach ( $products as $product ) : ?>
						<div class="aqualuxe-compare-bar-product" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
							<div class="aqualuxe-compare-bar-product-image">
								<?php echo $product->get_image( 'thumbnail' ); ?>
							</div>
							<div class="aqualuxe-compare-bar-product-title">
								<?php echo esc_html( $product->get_name() ); ?>
							</div>
							<button type="button" class="aqualuxe-compare-bar-product-remove" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
								&times;
							</button>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="aqualuxe-compare-bar-actions">
					<a href="<?php echo esc_url( $compare_page_url ); ?>" class="button aqualuxe-compare-view-button">
						<?php echo esc_html__( 'Compare Now', 'aqualuxe' ); ?>
					</a>
					<button type="button" class="aqualuxe-compare-clear-button">
						<?php echo esc_html__( 'Clear All', 'aqualuxe' ); ?>
					</button>
				</div>
			</div>
			<button type="button" class="aqualuxe-compare-bar-toggle">
				<span class="compare-icon">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M15 3H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v2c0 1.1.9 2 2 2h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L9.83 23l6.59-6.59c.36-.36.58-.86.58-1.41V5c0-1.1-.9-2-2-2zm0 12l-4.34 4.34L12 14H3v-2l3-7h9v10zm4-12h4v12h-4z"/></svg>
				</span>
				<span class="compare-text">
					<?php echo esc_html__( 'Compare', 'aqualuxe' ); ?>
					<span class="aqualuxe-compare-count"><?php echo count( $products ); ?></span>
				</span>
			</button>
		</div>
		<?php
	}

	/**
	 * Add to compare AJAX handler.
	 *
	 * @return void
	 */
	public function add_to_compare_ajax() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-compare-nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ) );
		}

		// Check product ID.
		if ( ! isset( $_POST['product_id'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid product ID.', 'aqualuxe' ) ) );
		}

		// Get product ID.
		$product_id = absint( $_POST['product_id'] );

		// Get product.
		$product = wc_get_product( $product_id );

		// Check if product exists.
		if ( ! $product ) {
			wp_send_json_error( array( 'message' => __( 'Product not found.', 'aqualuxe' ) ) );
		}

		// Get compare list.
		$compare_list = $this->get_compare_list();

		// Check if product is already in compare list.
		if ( in_array( $product_id, $compare_list, true ) ) {
			wp_send_json_success(
				array(
					'message'    => __( 'Product is already in compare list.', 'aqualuxe' ),
					'in_compare' => true,
				)
			);
		}

		// Add product to compare list.
		$compare_list[] = $product_id;

		// Save compare list.
		$this->set_compare_list( $compare_list );

		// Get product data.
		$product_data = array(
			'id'    => $product->get_id(),
			'name'  => $product->get_name(),
			'image' => $product->get_image( 'thumbnail' ),
		);

		// Send response.
		wp_send_json_success(
			array(
				'message'      => __( 'Product added to compare list.', 'aqualuxe' ),
				'in_compare'   => true,
				'product'      => $product_data,
				'compare_list' => $compare_list,
			)
		);
	}

	/**
	 * Remove from compare AJAX handler.
	 *
	 * @return void
	 */
	public function remove_from_compare_ajax() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-compare-nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ) );
		}

		// Check product ID.
		if ( ! isset( $_POST['product_id'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid product ID.', 'aqualuxe' ) ) );
		}

		// Get product ID.
		$product_id = absint( $_POST['product_id'] );

		// Get compare list.
		$compare_list = $this->get_compare_list();

		// Check if product is in compare list.
		if ( ! in_array( $product_id, $compare_list, true ) ) {
			wp_send_json_success(
				array(
					'message'    => __( 'Product is not in compare list.', 'aqualuxe' ),
					'in_compare' => false,
				)
			);
		}

		// Remove product from compare list.
		$compare_list = array_diff( $compare_list, array( $product_id ) );

		// Save compare list.
		$this->set_compare_list( $compare_list );

		// Send response.
		wp_send_json_success(
			array(
				'message'      => __( 'Product removed from compare list.', 'aqualuxe' ),
				'in_compare'   => false,
				'compare_list' => $compare_list,
			)
		);
	}

	/**
	 * Get compare table AJAX handler.
	 *
	 * @return void
	 */
	public function get_compare_table_ajax() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-compare-nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ) );
		}

		// Get compare list.
		$compare_list = $this->get_compare_list();

		// Check if compare list is empty.
		if ( empty( $compare_list ) ) {
			wp_send_json_error( array( 'message' => __( 'Compare list is empty.', 'aqualuxe' ) ) );
		}

		// Get compare table.
		$compare_table = $this->get_compare_table( $compare_list );

		// Send response.
		wp_send_json_success(
			array(
				'compare_table' => $compare_table,
			)
		);
	}

	/**
	 * Get compare list.
	 *
	 * @return array
	 */
	public function get_compare_list() {
		// Check if user is logged in.
		if ( is_user_logged_in() ) {
			// Get compare list from user meta.
			$compare_list = get_user_meta( get_current_user_id(), 'aqualuxe_compare_list', true );

			// Check if compare list exists.
			if ( ! $compare_list ) {
				$compare_list = array();
			}
		} else {
			// Get compare list from cookie.
			$compare_list = isset( $_COOKIE['aqualuxe_compare_list'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_compare_list'] ) ), true ) : array();

			// Check if compare list exists.
			if ( ! $compare_list ) {
				$compare_list = array();
			}
		}

		return $compare_list;
	}

	/**
	 * Set compare list.
	 *
	 * @param array $compare_list Compare list.
	 * @return void
	 */
	public function set_compare_list( $compare_list ) {
		// Check if user is logged in.
		if ( is_user_logged_in() ) {
			// Set compare list to user meta.
			update_user_meta( get_current_user_id(), 'aqualuxe_compare_list', $compare_list );
		} else {
			// Set compare list to cookie.
			setcookie( 'aqualuxe_compare_list', wp_json_encode( $compare_list ), time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
		}
	}

	/**
	 * Get compare table.
	 *
	 * @param array $compare_list Compare list.
	 * @return string
	 */
	public function get_compare_table( $compare_list ) {
		// Get products.
		$products = array();
		foreach ( $compare_list as $product_id ) {
			$product = wc_get_product( $product_id );
			if ( $product ) {
				$products[] = $product;
			}
		}

		// Check if products exist.
		if ( empty( $products ) ) {
			return '<p>' . esc_html__( 'No products to compare.', 'aqualuxe' ) . '</p>';
		}

		// Get attributes to compare.
		$attributes = $this->get_compare_attributes( $products );

		// Start output buffer.
		ob_start();
		?>
		<div class="aqualuxe-compare-table-wrapper">
			<table class="aqualuxe-compare-table">
				<thead>
					<tr>
						<th class="aqualuxe-compare-table-heading"><?php echo esc_html__( 'Product', 'aqualuxe' ); ?></th>
						<?php foreach ( $products as $product ) : ?>
							<th class="aqualuxe-compare-table-product">
								<div class="aqualuxe-compare-table-product-remove">
									<button type="button" class="aqualuxe-compare-table-product-remove-button" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
										&times;
									</button>
								</div>
								<div class="aqualuxe-compare-table-product-image">
									<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
										<?php echo $product->get_image( 'thumbnail' ); ?>
									</a>
								</div>
								<div class="aqualuxe-compare-table-product-title">
									<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
										<?php echo esc_html( $product->get_name() ); ?>
									</a>
								</div>
								<div class="aqualuxe-compare-table-product-price">
									<?php echo $product->get_price_html(); ?>
								</div>
								<div class="aqualuxe-compare-table-product-rating">
									<?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
								</div>
								<div class="aqualuxe-compare-table-product-description">
									<?php echo wp_kses_post( $product->get_short_description() ); ?>
								</div>
								<div class="aqualuxe-compare-table-product-actions">
									<?php if ( $product->is_in_stock() ) : ?>
										<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="button aqualuxe-compare-table-product-add-to-cart">
											<?php echo esc_html( $product->add_to_cart_text() ); ?>
										</a>
									<?php else : ?>
										<span class="aqualuxe-compare-table-product-out-of-stock">
											<?php echo esc_html__( 'Out of Stock', 'aqualuxe' ); ?>
										</span>
									<?php endif; ?>
								</div>
							</th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $attributes as $attribute => $values ) : ?>
						<tr>
							<th class="aqualuxe-compare-table-attribute">
								<?php echo esc_html( wc_attribute_label( $attribute ) ); ?>
							</th>
							<?php foreach ( $products as $product ) : ?>
								<td class="aqualuxe-compare-table-value">
									<?php
									$product_id = $product->get_id();
									if ( isset( $values[ $product_id ] ) ) {
										echo wp_kses_post( $values[ $product_id ] );
									} else {
										echo '&mdash;';
									}
									?>
								</td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php

		// Get output buffer.
		return ob_get_clean();
	}

	/**
	 * Get compare attributes.
	 *
	 * @param array $products Products.
	 * @return array
	 */
	public function get_compare_attributes( $products ) {
		// Get attributes.
		$attributes = array();

		// Loop through products.
		foreach ( $products as $product ) {
			// Get product attributes.
			$product_attributes = $product->get_attributes();

			// Loop through product attributes.
			foreach ( $product_attributes as $attribute_name => $attribute ) {
				// Skip if attribute is not visible.
				if ( ! $attribute->get_visible() ) {
					continue;
				}

				// Get attribute values.
				$attribute_values = array();

				// Check if attribute is a taxonomy.
				if ( $attribute->is_taxonomy() ) {
					// Get attribute terms.
					$attribute_terms = $attribute->get_terms();

					// Loop through attribute terms.
					if ( $attribute_terms ) {
						foreach ( $attribute_terms as $attribute_term ) {
							$attribute_values[] = $attribute_term->name;
						}
					}
				} else {
					// Get attribute value.
					$attribute_values = $attribute->get_options();
				}

				// Add attribute values to attributes.
				if ( ! isset( $attributes[ $attribute_name ] ) ) {
					$attributes[ $attribute_name ] = array();
				}

				// Add attribute values to attributes.
				$attributes[ $attribute_name ][ $product->get_id() ] = implode( ', ', $attribute_values );
			}

			// Add weight.
			if ( $product->has_weight() ) {
				$attributes['weight'][ $product->get_id() ] = $product->get_weight() . ' ' . get_option( 'woocommerce_weight_unit' );
			}

			// Add dimensions.
			if ( $product->has_dimensions() ) {
				$attributes['dimensions'][ $product->get_id() ] = $product->get_dimensions();
			}

			// Add SKU.
			if ( $product->get_sku() ) {
				$attributes['sku'][ $product->get_id() ] = $product->get_sku();
			}

			// Add stock.
			$attributes['stock'][ $product->get_id() ] = $product->is_in_stock() ? __( 'In Stock', 'aqualuxe' ) : __( 'Out of Stock', 'aqualuxe' );
		}

		return $attributes;
	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		// Enqueue styles.
		wp_enqueue_style(
			'aqualuxe-product-compare',
			AQUALUXE_URI . '/assets/css/product-compare.css',
			array(),
			AQUALUXE_VERSION
		);

		// Enqueue scripts.
		wp_enqueue_script(
			'aqualuxe-product-compare',
			AQUALUXE_URI . '/assets/js/product-compare.js',
			array( 'jquery' ),
			AQUALUXE_VERSION,
			true
		);

		// Localize script.
		wp_localize_script(
			'aqualuxe-product-compare',
			'aqualuxeCompare',
			array(
				'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
				'nonce'          => wp_create_nonce( 'aqualuxe-compare-nonce' ),
				'compareList'    => $this->get_compare_list(),
				'maxCompare'     => get_theme_mod( 'aqualuxe_max_compare_products', 4 ),
				'comparePageUrl' => get_permalink( get_theme_mod( 'aqualuxe_compare_page', 0 ) ),
				'i18n'           => array(
					'addToCompare'     => __( 'Compare', 'aqualuxe' ),
					'removeFromCompare' => __( 'Remove from Compare', 'aqualuxe' ),
					'maxCompareReached' => __( 'You can compare up to %s products.', 'aqualuxe' ),
					'compareEmpty'      => __( 'No products to compare.', 'aqualuxe' ),
					'compareNow'        => __( 'Compare Now', 'aqualuxe' ),
					'clearAll'          => __( 'Clear All', 'aqualuxe' ),
				),
			)
		);
	}

	/**
	 * Compare shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function compare_shortcode( $atts ) {
		// Get compare list.
		$compare_list = $this->get_compare_list();

		// Check if compare list is empty.
		if ( empty( $compare_list ) ) {
			return '<p>' . esc_html__( 'No products to compare.', 'aqualuxe' ) . '</p>';
		}

		// Get compare table.
		$compare_table = $this->get_compare_table( $compare_list );

		return $compare_table;
	}
}