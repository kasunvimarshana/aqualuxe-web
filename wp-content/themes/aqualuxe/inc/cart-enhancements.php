<?php
/**
 * Enhanced Cart Functionality and AJAX Updates
 *
 * @package AquaLuxe
 */

// Security check
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enhanced Cart Widget with Live Updates
 */
function aqualuxe_enhanced_cart_widget() {
    $cart_count = WC()->cart->get_cart_contents_count();
    $cart_total = WC()->cart->get_cart_total();
    $cart_url = wc_get_cart_url();
    ?>

    <div class="cart-widget relative" id="enhanced-cart-widget">
        <!-- Cart Toggle Button -->
        <button class="cart-toggle-btn relative p-2 text-gray-700 hover:text-gray-900 transition-colors duration-200"
                id="cart-toggle"
                aria-label="Shopping Cart">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9m-9 0v-2a2 2 0 012-2h5a2 2 0 012 2v2m-7-6V9a2 2 0 012-2h2a2 2 0 012 2v2" />
            </svg>

            <!-- Cart Count Badge -->
            <span class="cart-count absolute -top-1 -right-1 bg-cyan-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center <?php echo $cart_count > 0 ? '' : 'hidden'; ?>"
                  id="cart-count-badge">
                <?php echo $cart_count; ?>
            </span>
        </button>

        <!-- Cart Dropdown -->
        <div class="cart-dropdown absolute right-0 top-full mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-xl z-50 hidden" id="cart-dropdown">
            <div class="cart-header p-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Shopping Cart</h3>
                    <span class="text-sm text-gray-500" id="cart-total-display"><?php echo $cart_total; ?></span>
                </div>
            </div>

            <div class="cart-items max-h-64 overflow-y-auto" id="cart-items-container">
                <?php if (WC()->cart->get_cart_contents_count() > 0): ?>
                    <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item): ?>
                        <?php
                        $product = $cart_item['data'];
                        $product_id = $cart_item['product_id'];
                        $quantity = $cart_item['quantity'];
                        ?>
                        <div class="cart-item flex items-center p-4 border-b border-gray-50" data-key="<?php echo $cart_item_key; ?>">
                            <div class="item-image flex-shrink-0 mr-3">
                                <img src="<?php echo wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail'); ?>"
                                     alt="<?php echo $product->get_name(); ?>"
                                     class="w-12 h-12 object-cover rounded">
                            </div>
                            <div class="item-details flex-1">
                                <h4 class="text-sm font-medium text-gray-900 line-clamp-1">
                                    <?php echo $product->get_name(); ?>
                                </h4>
                                <div class="flex items-center justify-between mt-1">
                                    <div class="quantity-controls flex items-center space-x-1">
                                        <button class="quantity-btn decrease text-gray-400 hover:text-gray-600 w-6 h-6 flex items-center justify-center border border-gray-200 rounded"
                                                data-key="<?php echo $cart_item_key; ?>"
                                                data-action="decrease">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <span class="quantity-display text-sm text-gray-700 px-2"><?php echo $quantity; ?></span>
                                        <button class="quantity-btn increase text-gray-400 hover:text-gray-600 w-6 h-6 flex items-center justify-center border border-gray-200 rounded"
                                                data-key="<?php echo $cart_item_key; ?>"
                                                data-action="increase">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="item-price text-sm font-medium text-cyan-600">
                                        <?php echo WC()->cart->get_product_subtotal($product, $quantity); ?>
                                    </div>
                                </div>
                            </div>
                            <button class="remove-item ml-2 text-gray-400 hover:text-red-500 transition-colors duration-200"
                                    data-key="<?php echo $cart_item_key; ?>"
                                    aria-label="Remove item">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-cart text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9m-9 0v-2a2 2 0 012-2h5a2 2 0 012 2v2m-7-6V9a2 2 0 012-2h2a2 2 0 012 2v2" />
                        </svg>
                        <p class="text-sm">Your cart is empty</p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (WC()->cart->get_cart_contents_count() > 0): ?>
                <div class="cart-footer p-4 border-t border-gray-100">
                    <div class="flex space-x-2">
                        <a href="<?php echo wc_get_cart_url(); ?>"
                           class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200 text-center">
                            View Cart
                        </a>
                        <a href="<?php echo wc_get_checkout_url(); ?>"
                           class="flex-1 px-4 py-2 text-sm font-medium text-white bg-cyan-600 rounded-lg hover:bg-cyan-700 transition-colors duration-200 text-center">
                            Checkout
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .cart-dropdown {
        animation: slideDown 0.2s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .cart-item {
        transition: all 0.2s ease;
    }

    .cart-item:hover {
        background-color: #f9fafb;
    }

    .quantity-btn:hover {
        background-color: #f3f4f6;
    }

    .quantity-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Loading states */
    .cart-item.updating {
        opacity: 0.6;
        pointer-events: none;
    }

    .cart-widget.loading .cart-toggle-btn {
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    /* Mobile optimizations */
    @media (max-width: 640px) {
        .cart-dropdown {
            width: 95vw;
            right: -200px;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const cartToggle = document.getElementById('cart-toggle');
        const cartDropdown = document.getElementById('cart-dropdown');
        const cartWidget = document.getElementById('enhanced-cart-widget');

        // Toggle cart dropdown
        cartToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            cartDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!cartWidget.contains(e.target)) {
                cartDropdown.classList.add('hidden');
            }
        });

        // Quantity update handlers
        document.addEventListener('click', function(e) {
            if (e.target.closest('.quantity-btn')) {
                e.preventDefault();
                const button = e.target.closest('.quantity-btn');
                const key = button.dataset.key;
                const action = button.dataset.action;

                updateCartQuantity(key, action);
            }

            if (e.target.closest('.remove-item')) {
                e.preventDefault();
                const button = e.target.closest('.remove-item');
                const key = button.dataset.key;

                removeCartItem(key);
            }
        });

        // AJAX cart update functions
        function updateCartQuantity(cartItemKey, action) {
            const cartItem = document.querySelector(`[data-key="${cartItemKey}"]`);
            cartItem.classList.add('updating');
            cartWidget.classList.add('loading');

            const formData = new FormData();
            formData.append('action', 'aqualuxe_update_cart_quantity');
            formData.append('cart_item_key', cartItemKey);
            formData.append('quantity_action', action);
            formData.append('nonce', aqualuxe_cart.nonce);

            fetch(aqualuxe_cart.ajax_url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartDisplay(data.data);
                } else {
                    console.error('Cart update failed:', data.data);
                }
            })
            .catch(error => {
                console.error('Cart update error:', error);
            })
            .finally(() => {
                cartItem.classList.remove('updating');
                cartWidget.classList.remove('loading');
            });
        }

        function removeCartItem(cartItemKey) {
            const cartItem = document.querySelector(`[data-key="${cartItemKey}"]`);
            cartItem.classList.add('updating');
            cartWidget.classList.add('loading');

            const formData = new FormData();
            formData.append('action', 'aqualuxe_remove_cart_item');
            formData.append('cart_item_key', cartItemKey);
            formData.append('nonce', aqualuxe_cart.nonce);

            fetch(aqualuxe_cart.ajax_url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartDisplay(data.data);
                } else {
                    console.error('Cart item removal failed:', data.data);
                }
            })
            .catch(error => {
                console.error('Cart removal error:', error);
            })
            .finally(() => {
                cartWidget.classList.remove('loading');
            });
        }

        function updateCartDisplay(cartData) {
            // Update cart count
            const countBadge = document.getElementById('cart-count-badge');
            const totalDisplay = document.getElementById('cart-total-display');
            const itemsContainer = document.getElementById('cart-items-container');

            if (cartData.count > 0) {
                countBadge.textContent = cartData.count;
                countBadge.classList.remove('hidden');
            } else {
                countBadge.classList.add('hidden');
            }

            totalDisplay.textContent = cartData.total;
            itemsContainer.innerHTML = cartData.fragments['cart-items'];

            // Show notification
            showCartNotification(cartData.message || 'Cart updated');
        }

        function showCartNotification(message) {
            // Create or update notification
            let notification = document.getElementById('cart-notification');
            if (!notification) {
                notification = document.createElement('div');
                notification.id = 'cart-notification';
                notification.className = 'fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
                document.body.appendChild(notification);
            }

            notification.textContent = message;
            notification.classList.remove('translate-x-full');

            // Auto hide after 3 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
            }, 3000);
        }
    });
    </script>
    <?php
}

/**
 * AJAX handler for updating cart quantity
 */
add_action('wp_ajax_aqualuxe_update_cart_quantity', 'aqualuxe_ajax_update_cart_quantity');
add_action('wp_ajax_nopriv_aqualuxe_update_cart_quantity', 'aqualuxe_ajax_update_cart_quantity');

function aqualuxe_ajax_update_cart_quantity() {
    // Security check
    if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_cart_nonce')) {
        wp_die('Security check failed');
    }

    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
    $action = sanitize_text_field($_POST['quantity_action']);

    if (!$cart_item_key || !$action) {
        wp_send_json_error('Invalid parameters');
    }

    $cart = WC()->cart->get_cart();

    if (!isset($cart[$cart_item_key])) {
        wp_send_json_error('Cart item not found');
    }

    $current_quantity = $cart[$cart_item_key]['quantity'];
    $new_quantity = ($action === 'increase') ? $current_quantity + 1 : max(0, $current_quantity - 1);

    if ($new_quantity === 0) {
        WC()->cart->remove_cart_item($cart_item_key);
        $message = 'Item removed from cart';
    } else {
        WC()->cart->set_quantity($cart_item_key, $new_quantity);
        $message = 'Cart updated';
    }

    // Prepare response data
    $response_data = array(
        'count' => WC()->cart->get_cart_contents_count(),
        'total' => WC()->cart->get_cart_total(),
        'message' => $message,
        'fragments' => array(
            'cart-items' => aqualuxe_get_cart_items_html()
        )
    );

    wp_send_json_success($response_data);
}

/**
 * AJAX handler for removing cart item
 */
add_action('wp_ajax_aqualuxe_remove_cart_item', 'aqualuxe_ajax_remove_cart_item');
add_action('wp_ajax_nopriv_aqualuxe_remove_cart_item', 'aqualuxe_ajax_remove_cart_item');

function aqualuxe_ajax_remove_cart_item() {
    // Security check
    if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_cart_nonce')) {
        wp_die('Security check failed');
    }

    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);

    if (!$cart_item_key) {
        wp_send_json_error('Invalid cart item key');
    }

    WC()->cart->remove_cart_item($cart_item_key);

    // Prepare response data
    $response_data = array(
        'count' => WC()->cart->get_cart_contents_count(),
        'total' => WC()->cart->get_cart_total(),
        'message' => 'Item removed from cart',
        'fragments' => array(
            'cart-items' => aqualuxe_get_cart_items_html()
        )
    );

    wp_send_json_success($response_data);
}

/**
 * Helper function to get cart items HTML
 */
function aqualuxe_get_cart_items_html() {
    ob_start();

    if (WC()->cart->get_cart_contents_count() > 0) {
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $product = $cart_item['data'];
            $product_id = $cart_item['product_id'];
            $quantity = $cart_item['quantity'];
            ?>
            <div class="cart-item flex items-center p-4 border-b border-gray-50" data-key="<?php echo $cart_item_key; ?>">
                <div class="item-image flex-shrink-0 mr-3">
                    <img src="<?php echo wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail'); ?>"
                         alt="<?php echo $product->get_name(); ?>"
                         class="w-12 h-12 object-cover rounded">
                </div>
                <div class="item-details flex-1">
                    <h4 class="text-sm font-medium text-gray-900 line-clamp-1">
                        <?php echo $product->get_name(); ?>
                    </h4>
                    <div class="flex items-center justify-between mt-1">
                        <div class="quantity-controls flex items-center space-x-1">
                            <button class="quantity-btn decrease text-gray-400 hover:text-gray-600 w-6 h-6 flex items-center justify-center border border-gray-200 rounded"
                                    data-key="<?php echo $cart_item_key; ?>"
                                    data-action="decrease">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                            </button>
                            <span class="quantity-display text-sm text-gray-700 px-2"><?php echo $quantity; ?></span>
                            <button class="quantity-btn increase text-gray-400 hover:text-gray-600 w-6 h-6 flex items-center justify-center border border-gray-200 rounded"
                                    data-key="<?php echo $cart_item_key; ?>"
                                    data-action="increase">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </button>
                        </div>
                        <div class="item-price text-sm font-medium text-cyan-600">
                            <?php echo WC()->cart->get_product_subtotal($product, $quantity); ?>
                        </div>
                    </div>
                </div>
                <button class="remove-item ml-2 text-gray-400 hover:text-red-500 transition-colors duration-200"
                        data-key="<?php echo $cart_item_key; ?>"
                        aria-label="Remove item">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
            <?php
        }
    } else {
        ?>
        <div class="empty-cart text-center py-8 text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9m-9 0v-2a2 2 0 012-2h5a2 2 0 012 2v2m-7-6V9a2 2 0 012-2h2a2 2 0 012 2v2" />
            </svg>
            <p class="text-sm">Your cart is empty</p>
        </div>
        <?php
    }

    return ob_get_clean();
}

/**
 * Enqueue cart scripts and styles
 */
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_cart_scripts');

function aqualuxe_enqueue_cart_scripts() {
    wp_localize_script('aqualuxe-main', 'aqualuxe_cart', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe_cart_nonce'),
        'messages' => array(
            'added_to_cart' => __('Added to cart', 'aqualuxe'),
            'removed_from_cart' => __('Removed from cart', 'aqualuxe'),
            'cart_updated' => __('Cart updated', 'aqualuxe'),
            'error' => __('An error occurred', 'aqualuxe')
        )
    ));
}
?>
