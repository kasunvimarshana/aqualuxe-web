<?php
/**
 * Advanced Wishlist and Product Comparison System
 *
 * @package AquaLuxe
 */

/**
 * Wishlist Functionality
 */
class AquaLuxe_Wishlist {

    public function __construct() {
        add_action('wp_ajax_aqualuxe_toggle_wishlist', array($this, 'ajax_toggle_wishlist'));
        add_action('wp_ajax_nopriv_aqualuxe_toggle_wishlist', array($this, 'ajax_toggle_wishlist'));
        add_action('wp_ajax_aqualuxe_get_wishlist', array($this, 'ajax_get_wishlist'));
        add_action('wp_ajax_nopriv_aqualuxe_get_wishlist', array($this, 'ajax_get_wishlist'));
    }

    /**
     * Get user's wishlist
     */
    public function get_wishlist($user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }

        if ($user_id) {
            // For logged-in users
            return get_user_meta($user_id, 'aqualuxe_wishlist', true) ?: array();
        } else {
            // For guest users, use session
            if (!session_id()) {
                session_start();
            }
            return $_SESSION['aqualuxe_wishlist'] ?? array();
        }
    }

    /**
     * Add product to wishlist
     */
    public function add_to_wishlist($product_id, $user_id = null) {
        $wishlist = $this->get_wishlist($user_id);

        if (!in_array($product_id, $wishlist)) {
            $wishlist[] = $product_id;
            $this->save_wishlist($wishlist, $user_id);
            return true;
        }

        return false;
    }

    /**
     * Remove product from wishlist
     */
    public function remove_from_wishlist($product_id, $user_id = null) {
        $wishlist = $this->get_wishlist($user_id);
        $key = array_search($product_id, $wishlist);

        if ($key !== false) {
            unset($wishlist[$key]);
            $wishlist = array_values($wishlist); // Re-index array
            $this->save_wishlist($wishlist, $user_id);
            return true;
        }

        return false;
    }

    /**
     * Check if product is in wishlist
     */
    public function is_in_wishlist($product_id, $user_id = null) {
        $wishlist = $this->get_wishlist($user_id);
        return in_array($product_id, $wishlist);
    }

    /**
     * Save wishlist
     */
    private function save_wishlist($wishlist, $user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }

        if ($user_id) {
            update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);
        } else {
            if (!session_id()) {
                session_start();
            }
            $_SESSION['aqualuxe_wishlist'] = $wishlist;
        }
    }

    /**
     * AJAX toggle wishlist
     */
    public function ajax_toggle_wishlist() {
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_wishlist_nonce')) {
            wp_send_json_error('Security check failed');
        }

        $product_id = intval($_POST['product_id']);

        if (!$product_id) {
            wp_send_json_error('Invalid product ID');
        }

        $is_in_wishlist = $this->is_in_wishlist($product_id);

        if ($is_in_wishlist) {
            $this->remove_from_wishlist($product_id);
            $action = 'removed';
            $message = 'Removed from wishlist';
        } else {
            $this->add_to_wishlist($product_id);
            $action = 'added';
            $message = 'Added to wishlist';
        }

        $wishlist_count = count($this->get_wishlist());

        wp_send_json_success(array(
            'action' => $action,
            'message' => $message,
            'count' => $wishlist_count,
            'in_wishlist' => !$is_in_wishlist
        ));
    }

    /**
     * AJAX get wishlist
     */
    public function ajax_get_wishlist() {
        if (!wp_verify_nonce($_GET['nonce'], 'aqualuxe_wishlist_nonce')) {
            wp_send_json_error('Security check failed');
        }

        $wishlist = $this->get_wishlist();
        $products = array();

        foreach ($wishlist as $product_id) {
            $product = wc_get_product($product_id);
            if ($product) {
                $products[] = array(
                    'id' => $product_id,
                    'title' => $product->get_name(),
                    'url' => $product->get_permalink(),
                    'price' => $product->get_price_html(),
                    'image' => wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail'),
                    'in_stock' => $product->is_in_stock(),
                    'rating' => $product->get_average_rating()
                );
            }
        }

        wp_send_json_success(array(
            'products' => $products,
            'count' => count($products)
        ));
    }
}

/**
 * Product Comparison Functionality
 */
class AquaLuxe_Product_Compare {

    public function __construct() {
        add_action('wp_ajax_aqualuxe_toggle_compare', array($this, 'ajax_toggle_compare'));
        add_action('wp_ajax_nopriv_aqualuxe_toggle_compare', array($this, 'ajax_toggle_compare'));
        add_action('wp_ajax_aqualuxe_get_comparison', array($this, 'ajax_get_comparison'));
        add_action('wp_ajax_nopriv_aqualuxe_get_comparison', array($this, 'ajax_get_comparison'));
    }

    /**
     * Get comparison list
     */
    public function get_compare_list() {
        if (!session_id()) {
            session_start();
        }
        return $_SESSION['aqualuxe_compare'] ?? array();
    }

    /**
     * Add product to comparison
     */
    public function add_to_compare($product_id) {
        $compare_list = $this->get_compare_list();

        // Limit to 4 products for comparison
        if (count($compare_list) >= 4) {
            return array('success' => false, 'message' => 'Maximum 4 products can be compared');
        }

        if (!in_array($product_id, $compare_list)) {
            $compare_list[] = $product_id;
            $this->save_compare_list($compare_list);
            return array('success' => true, 'message' => 'Added to comparison');
        }

        return array('success' => false, 'message' => 'Product already in comparison');
    }

    /**
     * Remove product from comparison
     */
    public function remove_from_compare($product_id) {
        $compare_list = $this->get_compare_list();
        $key = array_search($product_id, $compare_list);

        if ($key !== false) {
            unset($compare_list[$key]);
            $compare_list = array_values($compare_list);
            $this->save_compare_list($compare_list);
            return array('success' => true, 'message' => 'Removed from comparison');
        }

        return array('success' => false, 'message' => 'Product not in comparison');
    }

    /**
     * Check if product is in comparison
     */
    public function is_in_compare($product_id) {
        $compare_list = $this->get_compare_list();
        return in_array($product_id, $compare_list);
    }

    /**
     * Save comparison list
     */
    private function save_compare_list($compare_list) {
        if (!session_id()) {
            session_start();
        }
        $_SESSION['aqualuxe_compare'] = $compare_list;
    }

    /**
     * AJAX toggle comparison
     */
    public function ajax_toggle_compare() {
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_compare_nonce')) {
            wp_send_json_error('Security check failed');
        }

        $product_id = intval($_POST['product_id']);

        if (!$product_id) {
            wp_send_json_error('Invalid product ID');
        }

        if ($this->is_in_compare($product_id)) {
            $result = $this->remove_from_compare($product_id);
            $action = 'removed';
        } else {
            $result = $this->add_to_compare($product_id);
            $action = 'added';
        }

        if ($result['success']) {
            $compare_count = count($this->get_compare_list());
            wp_send_json_success(array(
                'action' => $action,
                'message' => $result['message'],
                'count' => $compare_count,
                'in_compare' => $action === 'added'
            ));
        } else {
            wp_send_json_error($result['message']);
        }
    }

    /**
     * AJAX get comparison data
     */
    public function ajax_get_comparison() {
        if (!wp_verify_nonce($_GET['nonce'], 'aqualuxe_compare_nonce')) {
            wp_send_json_error('Security check failed');
        }

        $compare_list = $this->get_compare_list();
        $products = array();

        foreach ($compare_list as $product_id) {
            $product = wc_get_product($product_id);
            if ($product) {
                // Get product attributes
                $attributes = array();
                foreach ($product->get_attributes() as $attribute) {
                    $attributes[] = array(
                        'name' => wc_attribute_label($attribute->get_name()),
                        'value' => $product->get_attribute($attribute->get_name())
                    );
                }

                $products[] = array(
                    'id' => $product_id,
                    'title' => $product->get_name(),
                    'url' => $product->get_permalink(),
                    'price' => $product->get_price_html(),
                    'regular_price' => $product->get_regular_price(),
                    'sale_price' => $product->get_sale_price(),
                    'image' => wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_single'),
                    'in_stock' => $product->is_in_stock(),
                    'stock_quantity' => $product->get_stock_quantity(),
                    'rating' => $product->get_average_rating(),
                    'review_count' => $product->get_review_count(),
                    'short_description' => $product->get_short_description(),
                    'weight' => $product->get_weight(),
                    'dimensions' => $product->get_dimensions(),
                    'categories' => wp_get_post_terms($product_id, 'product_cat', array('fields' => 'names')),
                    'attributes' => $attributes
                );
            }
        }

        wp_send_json_success(array(
            'products' => $products,
            'count' => count($products)
        ));
    }
}

// Initialize classes
$aqualuxe_wishlist = new AquaLuxe_Wishlist();
$aqualuxe_compare = new AquaLuxe_Product_Compare();

/**
 * Wishlist Button HTML
 */
function aqualuxe_wishlist_button($product_id = null, $echo = true) {
    global $aqualuxe_wishlist;

    if (!$product_id) {
        global $product;
        $product_id = $product->get_id();
    }

    $is_in_wishlist = $aqualuxe_wishlist->is_in_wishlist($product_id);

    ob_start();
    ?>
    <button class="wishlist-btn <?php echo $is_in_wishlist ? 'active' : ''; ?> p-2 rounded-lg border border-gray-300 hover:border-red-300 hover:bg-red-50 transition-all duration-200"
            data-product-id="<?php echo $product_id; ?>"
            title="<?php echo $is_in_wishlist ? 'Remove from wishlist' : 'Add to wishlist'; ?>">
        <svg class="w-5 h-5 <?php echo $is_in_wishlist ? 'text-red-500 fill-current' : 'text-gray-500'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
    </button>
    <?php

    $output = ob_get_clean();

    if ($echo) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Compare Button HTML
 */
function aqualuxe_compare_button($product_id = null, $echo = true) {
    global $aqualuxe_compare;

    if (!$product_id) {
        global $product;
        $product_id = $product->get_id();
    }

    $is_in_compare = $aqualuxe_compare->is_in_compare($product_id);

    ob_start();
    ?>
    <button class="compare-btn <?php echo $is_in_compare ? 'active' : ''; ?> p-2 rounded-lg border border-gray-300 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200"
            data-product-id="<?php echo $product_id; ?>"
            title="<?php echo $is_in_compare ? 'Remove from comparison' : 'Add to comparison'; ?>">
        <svg class="w-5 h-5 <?php echo $is_in_compare ? 'text-blue-500' : 'text-gray-500'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
    </button>
    <?php

    $output = ob_get_clean();

    if ($echo) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Wishlist and Compare Floating Widget
 */
function aqualuxe_wishlist_compare_widget() {
    global $aqualuxe_wishlist, $aqualuxe_compare;

    $wishlist_count = count($aqualuxe_wishlist->get_wishlist());
    $compare_count = count($aqualuxe_compare->get_compare_list());
    ?>

    <div class="wishlist-compare-widget fixed right-4 bottom-20 z-40 space-y-2" id="wishlist-compare-widget">
        <!-- Wishlist Widget -->
        <div class="wishlist-widget bg-white border border-gray-200 rounded-lg shadow-lg">
            <button class="wishlist-toggle p-3 flex items-center space-x-2 hover:bg-gray-50 transition-colors duration-200 w-full" id="wishlist-toggle">
                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium text-gray-700">Wishlist</span>
                <span class="wishlist-count bg-red-500 text-white text-xs rounded-full px-2 py-1 <?php echo $wishlist_count > 0 ? '' : 'hidden'; ?>">
                    <?php echo $wishlist_count; ?>
                </span>
            </button>
        </div>

        <!-- Compare Widget -->
        <div class="compare-widget bg-white border border-gray-200 rounded-lg shadow-lg">
            <button class="compare-toggle p-3 flex items-center space-x-2 hover:bg-gray-50 transition-colors duration-200 w-full" id="compare-toggle">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span class="text-sm font-medium text-gray-700">Compare</span>
                <span class="compare-count bg-blue-500 text-white text-xs rounded-full px-2 py-1 <?php echo $compare_count > 0 ? '' : 'hidden'; ?>">
                    <?php echo $compare_count; ?>
                </span>
            </button>
        </div>
    </div>

    <!-- Wishlist Modal -->
    <div class="wishlist-modal fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center overlay-hidden" id="wishlist-modal">
        <div class="modal-content bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-96 overflow-hidden">
            <div class="modal-header flex items-center justify-between p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">My Wishlist</h3>
                <button class="close-modal text-gray-400 hover:text-gray-600" data-modal="wishlist-modal">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body p-4 max-h-80 overflow-y-auto" id="wishlist-content">
                <!-- Content loaded via JavaScript -->
            </div>
        </div>
    </div>

    <!-- Compare Modal -->
    <div class="compare-modal fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center overlay-hidden" id="compare-modal">
        <div class="modal-content bg-white rounded-lg shadow-xl max-w-6xl w-full mx-4 max-h-96 overflow-hidden">
            <div class="modal-header flex items-center justify-between p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Product Comparison</h3>
                <button class="close-modal text-gray-400 hover:text-gray-600" data-modal="compare-modal">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body p-4 max-h-80 overflow-y-auto" id="compare-content">
                <!-- Content loaded via JavaScript -->
            </div>
        </div>
    </div>

    <style>
    .wishlist-btn.active svg,
    .compare-btn.active svg {
        transform: scale(1.1);
    }

    .wishlist-btn.active {
        border-color: #ef4444;
        background-color: #fef2f2;
    }

    .compare-btn.active {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }

    .wishlist-compare-widget {
        animation: slideInRight 0.3s ease-out;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .modal-content {
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            transform: scale(0.9);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Wishlist functionality
        document.addEventListener('click', function(e) {
            if (e.target.closest('.wishlist-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.wishlist-btn');
                const productId = btn.dataset.productId;
                toggleWishlist(productId, btn);
            }

            if (e.target.closest('.compare-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.compare-btn');
                const productId = btn.dataset.productId;
                toggleCompare(productId, btn);
            }

            if (e.target.closest('.close-modal')) {
                const modalId = e.target.closest('.close-modal').dataset.modal;
                document.getElementById(modalId).classList.add('overlay-hidden');
            }
        });

        // Modal toggles
        document.getElementById('wishlist-toggle')?.addEventListener('click', function() {
            loadWishlist();
            document.getElementById('wishlist-modal').classList.remove('overlay-hidden');
        });

        document.getElementById('compare-toggle')?.addEventListener('click', function() {
            loadComparison();
            document.getElementById('compare-modal').classList.remove('overlay-hidden');
        });

        function toggleWishlist(productId, btn) {
            const formData = new FormData();
            formData.append('action', 'aqualuxe_toggle_wishlist');
            formData.append('product_id', productId);
            formData.append('nonce', aqualuxe_wishlist.nonce);

            fetch(aqualuxe_wishlist.ajax_url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateWishlistButton(btn, data.data.in_wishlist);
                    updateWishlistCount(data.data.count);
                    showNotification(data.data.message, data.data.action === 'added' ? 'success' : 'info');
                }
            })
            .catch(error => console.error('Wishlist error:', error));
        }

        function toggleCompare(productId, btn) {
            const formData = new FormData();
            formData.append('action', 'aqualuxe_toggle_compare');
            formData.append('product_id', productId);
            formData.append('nonce', aqualuxe_compare.nonce);

            fetch(aqualuxe_compare.ajax_url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCompareButton(btn, data.data.in_compare);
                    updateCompareCount(data.data.count);
                    showNotification(data.data.message, data.data.action === 'added' ? 'success' : 'info');
                } else {
                    showNotification(data.data, 'error');
                }
            })
            .catch(error => console.error('Compare error:', error));
        }

        function updateWishlistButton(btn, inWishlist) {
            const svg = btn.querySelector('svg');
            if (inWishlist) {
                btn.classList.add('active');
                svg.classList.add('text-red-500', 'fill-current');
                svg.classList.remove('text-gray-500');
            } else {
                btn.classList.remove('active');
                svg.classList.remove('text-red-500', 'fill-current');
                svg.classList.add('text-gray-500');
            }
        }

        function updateCompareButton(btn, inCompare) {
            const svg = btn.querySelector('svg');
            if (inCompare) {
                btn.classList.add('active');
                svg.classList.add('text-blue-500');
                svg.classList.remove('text-gray-500');
            } else {
                btn.classList.remove('active');
                svg.classList.remove('text-blue-500');
                svg.classList.add('text-gray-500');
            }
        }

        function updateWishlistCount(count) {
            const badge = document.querySelector('.wishlist-count');
            if (badge) {
                badge.textContent = count;
                badge.classList.toggle('hidden', count === 0);
            }
        }

        function updateCompareCount(count) {
            const badge = document.querySelector('.compare-count');
            if (badge) {
                badge.textContent = count;
                badge.classList.toggle('hidden', count === 0);
            }
        }

        function loadWishlist() {
            const content = document.getElementById('wishlist-content');
            content.innerHTML = '<div class="text-center py-4">Loading...</div>';

            fetch(`${aqualuxe_wishlist.ajax_url}?action=aqualuxe_get_wishlist&nonce=${aqualuxe_wishlist.nonce}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayWishlistContent(data.data.products);
                }
            })
            .catch(error => {
                content.innerHTML = '<div class="text-center py-4 text-red-500">Error loading wishlist</div>';
                console.error('Wishlist load error:', error);
            });
        }

        function loadComparison() {
            const content = document.getElementById('compare-content');
            content.innerHTML = '<div class="text-center py-4">Loading...</div>';

            fetch(`${aqualuxe_compare.ajax_url}?action=aqualuxe_get_comparison&nonce=${aqualuxe_compare.nonce}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayComparisonContent(data.data.products);
                }
            })
            .catch(error => {
                content.innerHTML = '<div class="text-center py-4 text-red-500">Error loading comparison</div>';
                console.error('Comparison load error:', error);
            });
        }

        function displayWishlistContent(products) {
            const content = document.getElementById('wishlist-content');

            if (products.length === 0) {
                content.innerHTML = '<div class="text-center py-8 text-gray-500">Your wishlist is empty</div>';
                return;
            }

            let html = '<div class="space-y-4">';
            products.forEach(product => {
                html += `
                    <div class="flex items-center space-x-4 p-3 border border-gray-200 rounded-lg">
                        <img src="${product.image}" alt="${product.title}" class="w-16 h-16 object-cover rounded">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">${product.title}</h4>
                            <p class="text-cyan-600 font-semibold">${product.price}</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="${product.url}" class="px-3 py-1 text-sm bg-cyan-600 text-white rounded hover:bg-cyan-700">View</a>
                            <button class="wishlist-btn active px-3 py-1 text-sm bg-red-100 text-red-600 rounded hover:bg-red-200" data-product-id="${product.id}">Remove</button>
                        </div>
                    </div>
                `;
            });
            html += '</div>';

            content.innerHTML = html;
        }

        function displayComparisonContent(products) {
            const content = document.getElementById('compare-content');

            if (products.length === 0) {
                content.innerHTML = '<div class="text-center py-8 text-gray-500">No products to compare</div>';
                return;
            }

            let html = '<div class="overflow-x-auto"><table class="w-full border-collapse">';

            // Header row with product images and names
            html += '<tr class="border-b">';
            html += '<td class="p-3 font-medium">Product</td>';
            products.forEach(product => {
                html += `
                    <td class="p-3 text-center">
                        <img src="${product.image}" alt="${product.title}" class="w-20 h-20 object-cover rounded mx-auto mb-2">
                        <h4 class="font-medium text-sm">${product.title}</h4>
                        <button class="compare-btn active mt-2 text-xs text-red-600 hover:text-red-800" data-product-id="${product.id}">Remove</button>
                    </td>
                `;
            });
            html += '</tr>';

            // Price row
            html += '<tr class="border-b bg-gray-50"><td class="p-3 font-medium">Price</td>';
            products.forEach(product => {
                html += `<td class="p-3 text-center font-semibold text-cyan-600">${product.price}</td>`;
            });
            html += '</tr>';

            // Rating row
            html += '<tr class="border-b"><td class="p-3 font-medium">Rating</td>';
            products.forEach(product => {
                html += `<td class="p-3 text-center">${product.rating ? '⭐'.repeat(Math.floor(product.rating)) + ` (${product.review_count})` : 'No rating'}</td>`;
            });
            html += '</tr>';

            html += '</table></div>';
            content.innerHTML = html;
        }

        function showNotification(message, type = 'info') {
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                info: 'bg-blue-500'
            };

            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-4 py-2 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
            notification.textContent = message;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    });
    </script>
    <?php
}

/**
 * Enqueue wishlist and compare scripts
 */
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_wishlist_compare_scripts');

function aqualuxe_enqueue_wishlist_compare_scripts() {
    wp_localize_script('aqualuxe-main', 'aqualuxe_wishlist', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe_wishlist_nonce')
    ));

    wp_localize_script('aqualuxe-main', 'aqualuxe_compare', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe_compare_nonce')
    ));
}
?>
