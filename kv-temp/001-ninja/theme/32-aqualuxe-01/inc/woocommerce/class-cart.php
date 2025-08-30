<?php
/**
 * AquaLuxe WooCommerce Cart Class
 *
 * @package AquaLuxe
 * @subpackage WooCommerce
 * @since 1.1.0
 */

namespace AquaLuxe\WooCommerce;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce Cart Class
 *
 * Handles WooCommerce cart customizations and enhancements.
 *
 * @since 1.1.0
 */
class Cart {

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
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Cart fragments.
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_link_fragment' ) );
		
		// Mini cart.
		add_action( 'aqualuxe_header_cart', array( $this, 'header_cart' ) );
		add_action( 'wp_footer', array( $this, 'mini_cart_drawer' ) );
		
		// Cart page.
		add_action( 'woocommerce_before_cart', array( $this, 'cart_wrapper_open' ), 5 );
		add_action( 'woocommerce_after_cart', array( $this, 'cart_wrapper_close' ), 15 );
		add_action( 'woocommerce_before_cart_table', array( $this, 'cart_table_wrapper_open' ), 5 );
		add_action( 'woocommerce_after_cart_table', array( $this, 'cart_table_wrapper_close' ), 15 );
		add_action( 'woocommerce_before_cart_collaterals', array( $this, 'cart_collaterals_wrapper_open' ), 5 );
		add_action( 'woocommerce_after_cart_collaterals', array( $this, 'cart_collaterals_wrapper_close' ), 15 );
		add_action( 'woocommerce_cart_collaterals', array( $this, 'cart_cross_sells' ), 5 );
		
		// Cart functionality.
		add_filter( 'woocommerce_cart_item_name', array( $this, 'cart_item_name' ), 10, 3 );
		add_filter( 'woocommerce_cart_item_thumbnail', array( $this, 'cart_item_thumbnail' ), 10, 3 );
		add_filter( 'woocommerce_cart_item_price', array( $this, 'cart_item_price' ), 10, 3 );
		add_filter( 'woocommerce_cart_item_subtotal', array( $this, 'cart_item_subtotal' ), 10, 3 );
		add_filter( 'woocommerce_cart_item_remove_link', array( $this, 'cart_item_remove_link' ), 10, 2 );
		add_filter( 'woocommerce_cart_item_quantity', array( $this, 'cart_item_quantity' ), 10, 3 );
		add_filter( 'woocommerce_cart_totals_order_total_html', array( $this, 'cart_totals_order_total_html' ), 10 );
		add_filter( 'woocommerce_cart_totals_taxes_total_html', array( $this, 'cart_totals_taxes_total_html' ), 10 );
		add_filter( 'woocommerce_cart_totals_coupon_html', array( $this, 'cart_totals_coupon_html' ), 10, 3 );
		add_filter( 'woocommerce_cart_totals_fee_html', array( $this, 'cart_totals_fee_html' ), 10, 2 );
		add_filter( 'woocommerce_cart_totals_shipping_method_label', array( $this, 'cart_totals_shipping_method_label' ), 10, 2 );
		
		// AJAX cart.
		add_action( 'wp_ajax_aqualuxe_add_to_cart', array( $this, 'add_to_cart_ajax' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_add_to_cart', array( $this, 'add_to_cart_ajax' ) );
		add_action( 'wp_ajax_aqualuxe_update_cart', array( $this, 'update_cart_ajax' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_update_cart', array( $this, 'update_cart_ajax' ) );
		add_action( 'wp_ajax_aqualuxe_remove_from_cart', array( $this, 'remove_from_cart_ajax' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_remove_from_cart', array( $this, 'remove_from_cart_ajax' ) );
		add_action( 'wp_ajax_aqualuxe_apply_coupon', array( $this, 'apply_coupon_ajax' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_apply_coupon', array( $this, 'apply_coupon_ajax' ) );
		add_action( 'wp_ajax_aqualuxe_remove_coupon', array( $this, 'remove_coupon_ajax' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_remove_coupon', array( $this, 'remove_coupon_ajax' ) );
		
		// Cross-sells.
		add_filter( 'woocommerce_cross_sells_columns', array( $this, 'cross_sells_columns' ), 10 );
		add_filter( 'woocommerce_cross_sells_total', array( $this, 'cross_sells_total' ), 10 );
	}

	/**
	 * Cart fragments.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array
	 */
	public function cart_link_fragment( $fragments ) {
		ob_start();
		$this->cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();
		
		ob_start();
		$this->cart_count();
		$fragments['span.cart-count'] = ob_get_clean();
		
		ob_start();
		$this->mini_cart();
		$fragments['div.mini-cart-content'] = ob_get_clean();
		
		return $fragments;
	}

	/**
	 * Cart link.
	 *
	 * @return void
	 */
	public function cart_link() {
		// Get cart icon.
		$cart_icon = get_theme_mod( 'aqualuxe_cart_icon', 'shopping-cart' );
		
		// Get cart style.
		$cart_style = get_theme_mod( 'aqualuxe_cart_style', 'icon-count' );
		
		?>
		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
			<span class="cart-icon"><i class="fas fa-<?php echo esc_attr( $cart_icon ); ?>"></i></span>
			<?php $this->cart_count(); ?>
			<?php if ( 'icon-count-total' === $cart_style ) : ?>
				<span class="cart-total"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
			<?php endif; ?>
		</a>
		<?php
	}

	/**
	 * Cart count.
	 *
	 * @return void
	 */
	public function cart_count() {
		$item_count = WC()->cart->get_cart_contents_count();
		?>
		<span class="cart-count">
			<?php
			/* translators: %d: number of items in cart */
			echo wp_kses_data( sprintf( _n( '%d item', '%d items', $item_count, 'aqualuxe' ), $item_count ) );
			?>
		</span>
		<?php
	}

	/**
	 * Mini cart.
	 *
	 * @return void
	 */
	public function mini_cart() {
		if ( ! function_exists( 'woocommerce_mini_cart' ) ) {
			return;
		}
		?>
		<div class="mini-cart-content">
			<?php woocommerce_mini_cart(); ?>
		</div>
		<?php
	}

	/**
	 * Header cart.
	 *
	 * @return void
	 */
	public function header_cart() {
		if ( ! function_exists( 'woocommerce_mini_cart' ) ) {
			return;
		}
		
		// Get cart style.
		$cart_style = get_theme_mod( 'aqualuxe_mini_cart_style', 'dropdown' );
		
		if ( 'dropdown' === $cart_style ) {
			?>
			<div class="site-header-cart">
				<div class="cart-header">
					<?php $this->cart_link(); ?>
				</div>
				<div class="cart-dropdown">
					<?php $this->mini_cart(); ?>
				</div>
			</div>
			<?php
		} else {
			?>
			<div class="site-header-cart">
				<div class="cart-header">
					<?php $this->cart_link(); ?>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Mini cart drawer.
	 *
	 * @return void
	 */
	public function mini_cart_drawer() {
		// Get cart style.
		$cart_style = get_theme_mod( 'aqualuxe_mini_cart_style', 'dropdown' );
		
		if ( 'drawer' !== $cart_style ) {
			return;
		}
		
		if ( ! function_exists( 'woocommerce_mini_cart' ) ) {
			return;
		}
		?>
		<div id="mini-cart-drawer" class="mini-cart-drawer">
			<div class="mini-cart-drawer-content">
				<div class="mini-cart-header">
					<h2><?php esc_html_e( 'Shopping Cart', 'aqualuxe' ); ?></h2>
					<button class="mini-cart-close">&times;</button>
				</div>
				<?php $this->mini_cart(); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Cart wrapper open.
	 *
	 * @return void
	 */
	public function cart_wrapper_open() {
		// Get cart layout.
		$cart_layout = get_theme_mod( 'aqualuxe_cart_layout', 'standard' );
		
		echo '<div class="cart-wrapper layout-' . esc_attr( $cart_layout ) . '">';
	}

	/**
	 * Cart wrapper close.
	 *
	 * @return void
	 */
	public function cart_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Cart table wrapper open.
	 *
	 * @return void
	 */
	public function cart_table_wrapper_open() {
		echo '<div class="cart-table-wrapper">';
	}

	/**
	 * Cart table wrapper close.
	 *
	 * @return void
	 */
	public function cart_table_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Cart collaterals wrapper open.
	 *
	 * @return void
	 */
	public function cart_collaterals_wrapper_open() {
		echo '<div class="cart-collaterals-wrapper">';
	}

	/**
	 * Cart collaterals wrapper close.
	 *
	 * @return void
	 */
	public function cart_collaterals_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Cart cross sells.
	 *
	 * @return void
	 */
	public function cart_cross_sells() {
		// Check if cross-sells are enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_cross_sells', true ) ) {
			return;
		}
		
		// Get cross-sells position.
		$cross_sells_position = get_theme_mod( 'aqualuxe_cross_sells_position', 'after' );
		
		if ( 'after' === $cross_sells_position ) {
			// Remove default cross-sells.
			remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
			
			// Add cross-sells after cart totals.
			add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display', 10 );
		}
	}

	/**
	 * Cart item name.
	 *
	 * @param string $name   Name.
	 * @param array  $cart_item Cart item.
	 * @param string $cart_item_key Cart item key.
	 * @return string
	 */
	public function cart_item_name( $name, $cart_item, $cart_item_key ) {
		// Check if product link should be removed.
		if ( ! get_theme_mod( 'aqualuxe_cart_product_link', true ) ) {
			return wp_strip_all_tags( $name );
		}
		
		return $name;
	}

	/**
	 * Cart item thumbnail.
	 *
	 * @param string $thumbnail Thumbnail.
	 * @param array  $cart_item Cart item.
	 * @param string $cart_item_key Cart item key.
	 * @return string
	 */
	public function cart_item_thumbnail( $thumbnail, $cart_item, $cart_item_key ) {
		// Check if product thumbnail should be removed.
		if ( ! get_theme_mod( 'aqualuxe_cart_product_thumbnail', true ) ) {
			return '';
		}
		
		// Check if product link should be removed.
		if ( ! get_theme_mod( 'aqualuxe_cart_product_link', true ) ) {
			return wp_strip_all_tags( $thumbnail );
		}
		
		return $thumbnail;
	}

	/**
	 * Cart item price.
	 *
	 * @param string $price  Price.
	 * @param array  $cart_item Cart item.
	 * @param string $cart_item_key Cart item key.
	 * @return string
	 */
	public function cart_item_price( $price, $cart_item, $cart_item_key ) {
		// Check if product price should be removed.
		if ( ! get_theme_mod( 'aqualuxe_cart_product_price', true ) ) {
			return '';
		}
		
		return $price;
	}

	/**
	 * Cart item subtotal.
	 *
	 * @param string $subtotal Subtotal.
	 * @param array  $cart_item Cart item.
	 * @param string $cart_item_key Cart item key.
	 * @return string
	 */
	public function cart_item_subtotal( $subtotal, $cart_item, $cart_item_key ) {
		// Check if product subtotal should be removed.
		if ( ! get_theme_mod( 'aqualuxe_cart_product_subtotal', true ) ) {
			return '';
		}
		
		return $subtotal;
	}

	/**
	 * Cart item remove link.
	 *
	 * @param string $link    Remove link.
	 * @param string $cart_item_key Cart item key.
	 * @return string
	 */
	public function cart_item_remove_link( $link, $cart_item_key ) {
		// Check if remove link should be customized.
		if ( get_theme_mod( 'aqualuxe_cart_remove_link_icon', true ) ) {
			// Get remove icon.
			$remove_icon = get_theme_mod( 'aqualuxe_cart_remove_icon', 'times' );
			
			$link = str_replace( '&times;', '<i class="fas fa-' . esc_attr( $remove_icon ) . '"></i>', $link );
		}
		
		return $link;
	}

	/**
	 * Cart item quantity.
	 *
	 * @param string $quantity Quantity.
	 * @param string $cart_item_key Cart item key.
	 * @param array  $cart_item Cart item.
	 * @return string
	 */
	public function cart_item_quantity( $quantity, $cart_item_key, $cart_item ) {
		// Check if quantity input should be customized.
		if ( get_theme_mod( 'aqualuxe_cart_quantity_style', 'input' ) === 'buttons' ) {
			// Get product.
			$product = $cart_item['data'];
			
			// Get quantity.
			$cart_item_quantity = $cart_item['quantity'];
			
			// Get min and max quantity.
			$min_value = 0;
			$max_value = $product->get_max_purchase_quantity();
			
			// Output quantity.
			ob_start();
			?>
			<div class="quantity-buttons">
				<button type="button" class="minus" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>" <?php echo $cart_item_quantity <= $min_value ? 'disabled' : ''; ?>>-</button>
				<input type="number" class="input-text qty text" name="cart[<?php echo esc_attr( $cart_item_key ); ?>][qty]" value="<?php echo esc_attr( $cart_item_quantity ); ?>" min="<?php echo esc_attr( $min_value ); ?>" max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>" step="1" inputmode="numeric" />
				<button type="button" class="plus" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>" <?php echo $cart_item_quantity >= $max_value && 0 < $max_value ? 'disabled' : ''; ?>>+</button>
			</div>
			<?php
			$quantity = ob_get_clean();
		}
		
		return $quantity;
	}

	/**
	 * Cart totals order total HTML.
	 *
	 * @param string $html HTML.
	 * @return string
	 */
	public function cart_totals_order_total_html( $html ) {
		// Check if order total should be customized.
		if ( get_theme_mod( 'aqualuxe_cart_total_style', 'default' ) === 'large' ) {
			$html = '<strong>' . $html . '</strong>';
		}
		
		return $html;
	}

	/**
	 * Cart totals taxes total HTML.
	 *
	 * @param string $html HTML.
	 * @return string
	 */
	public function cart_totals_taxes_total_html( $html ) {
		// Check if taxes should be customized.
		if ( get_theme_mod( 'aqualuxe_cart_taxes_style', 'default' ) === 'info' ) {
			$html .= ' <i class="fas fa-info-circle" title="' . esc_attr__( 'Tax information', 'aqualuxe' ) . '"></i>';
		}
		
		return $html;
	}

	/**
	 * Cart totals coupon HTML.
	 *
	 * @param string $coupon_html Coupon HTML.
	 * @param object $coupon      Coupon object.
	 * @param string $discount_amount_html Discount amount HTML.
	 * @return string
	 */
	public function cart_totals_coupon_html( $coupon_html, $coupon, $discount_amount_html ) {
		// Check if coupon should be customized.
		if ( get_theme_mod( 'aqualuxe_cart_coupon_style', 'default' ) === 'badge' ) {
			$coupon_html = '<span class="coupon-badge">' . esc_html( $coupon->get_code() ) . '</span> ' . $discount_amount_html;
		}
		
		return $coupon_html;
	}

	/**
	 * Cart totals fee HTML.
	 *
	 * @param string $fee_html Fee HTML.
	 * @param object $fee      Fee object.
	 * @return string
	 */
	public function cart_totals_fee_html( $fee_html, $fee ) {
		// Check if fee should be customized.
		if ( get_theme_mod( 'aqualuxe_cart_fee_style', 'default' ) === 'highlight' ) {
			$fee_html = '<span class="fee-highlight">' . $fee_html . '</span>';
		}
		
		return $fee_html;
	}

	/**
	 * Cart totals shipping method label.
	 *
	 * @param string $label  Label.
	 * @param object $method Method object.
	 * @return string
	 */
	public function cart_totals_shipping_method_label( $label, $method ) {
		// Check if shipping method should be customized.
		if ( get_theme_mod( 'aqualuxe_cart_shipping_style', 'default' ) === 'icon' ) {
			// Get shipping icon.
			$shipping_icon = get_theme_mod( 'aqualuxe_cart_shipping_icon', 'truck' );
			
			$label = '<i class="fas fa-' . esc_attr( $shipping_icon ) . '"></i> ' . $label;
		}
		
		return $label;
	}

	/**
	 * Add to cart AJAX.
	 *
	 * @return void
	 */
	public function add_to_cart_ajax() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-woocommerce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ) );
		}
		
		// Check product ID.
		if ( ! isset( $_POST['product_id'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid product ID.', 'aqualuxe' ) ) );
		}
		
		// Get product.
		$product_id = absint( $_POST['product_id'] );
		$product    = wc_get_product( $product_id );
		
		if ( ! $product ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Product not found.', 'aqualuxe' ) ) );
		}
		
		// Get quantity.
		$quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;
		
		// Get variation ID.
		$variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
		
		// Get variation attributes.
		$variation = array();
		
		if ( $variation_id ) {
			foreach ( $_POST as $key => $value ) {
				if ( strpos( $key, 'attribute_' ) === 0 ) {
					$variation[ sanitize_title( wp_unslash( $key ) ) ] = wp_unslash( $value );
				}
			}
		}
		
		// Add to cart.
		$cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
		
		if ( ! $cart_item_key ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Failed to add product to cart.', 'aqualuxe' ) ) );
		}
		
		// Get cart fragments.
		$fragments = $this->cart_link_fragment( array() );
		
		wp_send_json_success(
			array(
				'message'   => esc_html__( 'Product added to cart.', 'aqualuxe' ),
				'fragments' => $fragments,
			)
		);
	}

	/**
	 * Update cart AJAX.
	 *
	 * @return void
	 */
	public function update_cart_ajax() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-woocommerce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ) );
		}
		
		// Check cart item key.
		if ( ! isset( $_POST['cart_item_key'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid cart item key.', 'aqualuxe' ) ) );
		}
		
		// Get cart item key.
		$cart_item_key = sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) );
		
		// Check quantity.
		if ( ! isset( $_POST['quantity'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid quantity.', 'aqualuxe' ) ) );
		}
		
		// Get quantity.
		$quantity = absint( $_POST['quantity'] );
		
		// Update cart.
		WC()->cart->set_quantity( $cart_item_key, $quantity );
		
		// Get cart fragments.
		$fragments = $this->cart_link_fragment( array() );
		
		wp_send_json_success(
			array(
				'message'   => esc_html__( 'Cart updated.', 'aqualuxe' ),
				'fragments' => $fragments,
			)
		);
	}

	/**
	 * Remove from cart AJAX.
	 *
	 * @return void
	 */
	public function remove_from_cart_ajax() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-woocommerce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ) );
		}
		
		// Check cart item key.
		if ( ! isset( $_POST['cart_item_key'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid cart item key.', 'aqualuxe' ) ) );
		}
		
		// Get cart item key.
		$cart_item_key = sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) );
		
		// Remove from cart.
		WC()->cart->remove_cart_item( $cart_item_key );
		
		// Get cart fragments.
		$fragments = $this->cart_link_fragment( array() );
		
		wp_send_json_success(
			array(
				'message'   => esc_html__( 'Product removed from cart.', 'aqualuxe' ),
				'fragments' => $fragments,
			)
		);
	}

	/**
	 * Apply coupon AJAX.
	 *
	 * @return void
	 */
	public function apply_coupon_ajax() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-woocommerce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ) );
		}
		
		// Check coupon code.
		if ( ! isset( $_POST['coupon_code'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid coupon code.', 'aqualuxe' ) ) );
		}
		
		// Get coupon code.
		$coupon_code = sanitize_text_field( wp_unslash( $_POST['coupon_code'] ) );
		
		// Apply coupon.
		$result = WC()->cart->apply_coupon( $coupon_code );
		
		if ( ! $result ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Failed to apply coupon.', 'aqualuxe' ) ) );
		}
		
		// Get cart fragments.
		$fragments = $this->cart_link_fragment( array() );
		
		wp_send_json_success(
			array(
				'message'   => esc_html__( 'Coupon applied.', 'aqualuxe' ),
				'fragments' => $fragments,
			)
		);
	}

	/**
	 * Remove coupon AJAX.
	 *
	 * @return void
	 */
	public function remove_coupon_ajax() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-woocommerce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ) );
		}
		
		// Check coupon code.
		if ( ! isset( $_POST['coupon_code'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid coupon code.', 'aqualuxe' ) ) );
		}
		
		// Get coupon code.
		$coupon_code = sanitize_text_field( wp_unslash( $_POST['coupon_code'] ) );
		
		// Remove coupon.
		$result = WC()->cart->remove_coupon( $coupon_code );
		
		if ( ! $result ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Failed to remove coupon.', 'aqualuxe' ) ) );
		}
		
		// Get cart fragments.
		$fragments = $this->cart_link_fragment( array() );
		
		wp_send_json_success(
			array(
				'message'   => esc_html__( 'Coupon removed.', 'aqualuxe' ),
				'fragments' => $fragments,
			)
		);
	}

	/**
	 * Cross sells columns.
	 *
	 * @param int $columns Columns.
	 * @return int
	 */
	public function cross_sells_columns( $columns ) {
		// Get cross-sells columns.
		$cross_sells_columns = get_theme_mod( 'aqualuxe_cross_sells_columns', 4 );
		
		return $cross_sells_columns;
	}

	/**
	 * Cross sells total.
	 *
	 * @param int $total Total.
	 * @return int
	 */
	public function cross_sells_total( $total ) {
		// Get cross-sells count.
		$cross_sells_count = get_theme_mod( 'aqualuxe_cross_sells_count', 4 );
		
		return $cross_sells_count;
	}
}