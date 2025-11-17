<?php
/**
 * AquaLuxe AJAX Functions
 *
 * @package AquaLuxe
 */

/**
 * AJAX add to cart functionality
 */
function aqualuxe_ajax_add_to_cart() {
	// Verify nonce
	if ( ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_nonce' ) ) {
		wp_send_json_error( array( 'message' => 'Security check failed' ) );
	}
	
	// Get product ID and quantity
	$product_id = intval( $_POST['product_id'] );
	$quantity = intval( $_POST['quantity'] );
	
	if ( ! $product_id || ! $quantity ) {
		wp_send_json_error( array( 'message' => 'Invalid product or quantity' ) );
	}
	
	// Add to cart
	$added = WC()->cart->add_to_cart( $product_id, $quantity );
	
	if ( $added ) {
		wp_send_json_success( array(
			'message' => 'Product added to cart successfully!',
			'count' => WC()->cart->get_cart_contents_count()
		) );
	} else {
		wp_send_json_error( array( 'message' => 'Could not add product to cart' ) );
	}
}
add_action( 'wp_ajax_aqualuxe_add_to_cart', 'aqualuxe_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_aqualuxe_add_to_cart', 'aqualuxe_ajax_add_to_cart' );

/**
 * AJAX quick view functionality
 */
function aqualuxe_ajax_quick_view() {
	// Verify nonce
	if ( ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_nonce' ) ) {
		wp_send_json_error( array( 'message' => 'Security check failed' ) );
	}
	
	// Get product ID
	$product_id = intval( $_POST['product_id'] );
	
	if ( ! $product_id ) {
		wp_send_json_error( array( 'message' => 'Invalid product' ) );
	}
	
	// Get product
	$product = wc_get_product( $product_id );
	
	if ( ! $product ) {
		wp_send_json_error( array( 'message' => 'Product not found' ) );
	}
	
	// Generate quick view HTML
	ob_start();
	?>
	<div class="quick-view-product">
		<div class="quick-view-images">
			<?php echo $product->get_image( 'large' ); ?>
		</div>
		<div class="quick-view-content">
			<h2><?php echo esc_html( $product->get_name() ); ?></h2>
			<div class="price"><?php echo $product->get_price_html(); ?></div>
			<div class="description"><?php echo $product->get_short_description(); ?></div>
			<?php if ( $product->is_type( 'simple' ) ) : ?>
				<form class="cart" method="post" enctype="multipart/form-data">
					<?php woocommerce_quantity_input( array(
						'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
						'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
						'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : $product->get_min_purchase_quantity(),
					) ); ?>
					<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt">
						<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
					</button>
				</form>
			<?php endif; ?>
			<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="view-details">View Full Details</a>
		</div>
	</div>
	<?php
	$html = ob_get_clean();
	
	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_aqualuxe_quick_view', 'aqualuxe_ajax_quick_view' );
add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_ajax_quick_view' );

/**
 * AJAX update cart count
 */
function aqualuxe_ajax_update_cart_count() {
	if ( ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_nonce' ) ) {
		wp_send_json_error();
	}
	
	wp_send_json_success( array(
		'count' => WC()->cart->get_cart_contents_count()
	) );
}
add_action( 'wp_ajax_aqualuxe_update_cart_count', 'aqualuxe_ajax_update_cart_count' );
add_action( 'wp_ajax_nopriv_aqualuxe_update_cart_count', 'aqualuxe_ajax_update_cart_count' );

/**
 * AJAX search products
 */
function aqualuxe_ajax_search_products() {
	if ( ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_nonce' ) ) {
		wp_send_json_error();
	}
	
	$search_term = sanitize_text_field( $_POST['search_term'] );
	
	if ( empty( $search_term ) ) {
		wp_send_json_error( array( 'message' => 'Search term is required' ) );
	}
	
	// Search products
	$products = wc_get_products( array(
		'limit' => 10,
		'status' => 'publish',
		'search' => $search_term,
	) );
	
	if ( empty( $products ) ) {
		wp_send_json_success( array( 'html' => '<p>No products found</p>' ) );
	}
	
	// Generate search results HTML
	ob_start();
	echo '<ul class="search-results">';
	foreach ( $products as $product ) {
		echo '<li>';
		echo '<a href="' . get_permalink( $product->get_id() ) . '">';
		echo $product->get_image( 'thumbnail' );
		echo '<span class="product-title">' . $product->get_name() . '</span>';
		echo '<span class="product-price">' . $product->get_price_html() . '</span>';
		echo '</a>';
		echo '</li>';
	}
	echo '</ul>';
	$html = ob_get_clean();
	
	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_aqualuxe_search_products', 'aqualuxe_ajax_search_products' );
add_action( 'wp_ajax_nopriv_aqualuxe_search_products', 'aqualuxe_ajax_search_products' );

/**
 * AJAX load more products
 */
function aqualuxe_ajax_load_more_products() {
	if ( ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_nonce' ) ) {
		wp_send_json_error();
	}
	
	$page = intval( $_POST['page'] );
	$posts_per_page = intval( $_POST['posts_per_page'] );
	
	if ( ! $page || ! $posts_per_page ) {
		wp_send_json_error();
	}
	
	// Get products
	$products = wc_get_products( array(
		'limit' => $posts_per_page,
		'offset' => ( $page - 1 ) * $posts_per_page,
		'status' => 'publish',
	) );
	
	if ( empty( $products ) ) {
		wp_send_json_success( array( 'html' => '', 'has_more' => false ) );
	}
	
	// Generate products HTML
	ob_start();
	foreach ( $products as $product ) {
		wc_get_template_part( 'content', 'product' );
	}
	$html = ob_get_clean();
	
	// Check if there are more products
	$total_products = wc_get_products( array( 'return' => 'ids', 'limit' => -1 ) );
	$has_more = count( $total_products ) > $page * $posts_per_page;
	
	wp_send_json_success( array(
		'html' => $html,
		'has_more' => $has_more
	) );
}
add_action( 'wp_ajax_aqualuxe_load_more_products', 'aqualuxe_ajax_load_more_products' );
add_action( 'wp_ajax_nopriv_aqualuxe_load_more_products', 'aqualuxe_ajax_load_more_products' );

/**
 * Add AJAX URL to frontend
 */
function aqualuxe_ajax_url() {
	wp_localize_script( 'aqualuxe-scripts', 'aqualuxe_ajax', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'aqualuxe_nonce' )
	) );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_ajax_url' );