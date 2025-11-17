<?php
/**
 * Mini Cart Widget for AquaLuxe theme
 *
 * @package AquaLuxe
 */

if (!class_exists('AquaLuxe_Mini_Cart_Widget')) {
    /**
     * Mini Cart Widget Class
     */
    class AquaLuxe_Mini_Cart_Widget extends WP_Widget {
        /**
         * Cache instance
         *
         * @var array
         */
        protected $cache = array();

        /**
         * Cache expiration time in seconds
         *
         * @var int
         */
        protected $cache_time = 300; // 5 minutes

        /**
         * Constructor
         */
        public function __construct() {
            parent::__construct(
                'aqualuxe_mini_cart',
                __('AquaLuxe Mini Cart', 'aqualuxe'),
                array(
                    'description' => __('Displays a mini shopping cart with AJAX functionality.', 'aqualuxe'),
                    'classname'   => 'aqualuxe-mini-cart-widget',
                )
            );

            // Clear cache when cart is updated
            add_action('woocommerce_add_to_cart', array($this, 'flush_widget_cache'));
            add_action('woocommerce_remove_cart_item', array($this, 'flush_widget_cache'));
            add_action('woocommerce_cart_item_restored', array($this, 'flush_widget_cache'));
            add_action('woocommerce_cart_item_removed', array($this, 'flush_widget_cache'));
            add_action('woocommerce_cart_item_set_quantity', array($this, 'flush_widget_cache'));
            add_action('woocommerce_after_cart_item_quantity_update', array($this, 'flush_widget_cache'));
            add_action('woocommerce_calculate_totals', array($this, 'flush_widget_cache'));
            add_action('woocommerce_applied_coupon', array($this, 'flush_widget_cache'));
            add_action('woocommerce_removed_coupon', array($this, 'flush_widget_cache'));
            
            // Add AJAX functionality
            add_action('wp_ajax_aqualuxe_update_mini_cart', array($this, 'ajax_update_mini_cart'));
            add_action('wp_ajax_nopriv_aqualuxe_update_mini_cart', array($this, 'ajax_update_mini_cart'));
            
            // Enqueue scripts
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        }

        /**
         * Enqueue scripts and styles
         */
        public function enqueue_scripts() {
            wp_enqueue_style(
                'aqualuxe-mini-cart-style',
                AQUALUXE_URI . '/assets/css/widgets/mini-cart.css',
                array(),
                AQUALUXE_VERSION
            );

            wp_enqueue_script(
                'aqualuxe-mini-cart-script',
                AQUALUXE_URI . '/assets/js/widgets/mini-cart.js',
                array('jquery'),
                AQUALUXE_VERSION,
                true
            );

            wp_localize_script(
                'aqualuxe-mini-cart-script',
                'aqualuxeMiniCart',
                array(
                    'ajaxUrl' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('aqualuxe-mini-cart-nonce'),
                    'i18n' => array(
                        'addingToCart' => __('Adding...', 'aqualuxe'),
                        'addedToCart' => __('Added!', 'aqualuxe'),
                        'removingItem' => __('Removing...', 'aqualuxe'),
                        'updatingCart' => __('Updating...', 'aqualuxe'),
                        'cartUpdated' => __('Cart updated!', 'aqualuxe'),
                        'errorOccurred' => __('An error occurred. Please try again.', 'aqualuxe'),
                    ),
                )
            );
        }

        /**
         * Widget Front End
         *
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget($args, $instance) {
            // Check if WooCommerce is active
            if (!class_exists('WooCommerce')) {
                return;
            }

            // Get cached widget
            $cache_key = 'aqualuxe_mini_cart_' . md5(serialize($args) . serialize($instance));
            $cached_widget = $this->get_cached_widget($cache_key);

            if ($cached_widget) {
                echo $cached_widget;
                return;
            }

            // Start output buffering for caching
            ob_start();

            $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : __('Shopping Cart', 'aqualuxe');
            $show_count = isset($instance['show_count']) ? (bool) $instance['show_count'] : true;
            $show_total = isset($instance['show_total']) ? (bool) $instance['show_total'] : true;
            $show_empty_cart = isset($instance['show_empty_cart']) ? (bool) $instance['show_empty_cart'] : true;
            $cart_style = !empty($instance['cart_style']) ? esc_attr($instance['cart_style']) : 'dropdown';
            $show_cart_icon = isset($instance['show_cart_icon']) ? (bool) $instance['show_cart_icon'] : true;
            $show_checkout_button = isset($instance['show_checkout_button']) ? (bool) $instance['show_checkout_button'] : true;
            $show_view_cart_button = isset($instance['show_view_cart_button']) ? (bool) $instance['show_view_cart_button'] : true;
            $show_product_image = isset($instance['show_product_image']) ? (bool) $instance['show_product_image'] : true;
            $show_product_price = isset($instance['show_product_price']) ? (bool) $instance['show_product_price'] : true;
            $show_product_quantity = isset($instance['show_product_quantity']) ? (bool) $instance['show_product_quantity'] : true;
            $show_product_variations = isset($instance['show_product_variations']) ? (bool) $instance['show_product_variations'] : true;
            $max_products = !empty($instance['max_products']) ? absint($instance['max_products']) : 5;
            $empty_cart_message = !empty($instance['empty_cart_message']) ? esc_html($instance['empty_cart_message']) : __('Your cart is empty.', 'aqualuxe');
            $cart_icon = !empty($instance['cart_icon']) ? esc_attr($instance['cart_icon']) : 'default';

            // Get cart contents
            $cart_contents = WC()->cart->get_cart();
            $cart_count = WC()->cart->get_cart_contents_count();
            $cart_total = WC()->cart->get_cart_total();
            $cart_is_empty = WC()->cart->is_empty();

            // Widget classes
            $widget_classes = array(
                'aqualuxe-mini-cart',
                'cart-style-' . $cart_style,
                'cart-icon-' . $cart_icon,
            );

            if ($cart_is_empty) {
                $widget_classes[] = 'cart-empty';
            }

            echo $args['before_widget'];

            if ($title) {
                echo $args['before_title'] . $title . $args['after_title'];
            }
            ?>
            <div class="<?php echo esc_attr(implode(' ', $widget_classes)); ?>" data-cart-style="<?php echo esc_attr($cart_style); ?>">
                <div class="aqualuxe-mini-cart-header">
                    <?php if ($show_cart_icon) : ?>
                        <div class="aqualuxe-mini-cart-icon">
                            <?php if ($cart_icon === 'default') : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                            <?php elseif ($cart_icon === 'bag') : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M18 6h-2c0-2.21-1.79-4-4-4S8 3.79 8 6H6c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-8 0c0-1.1.9-2 2-2s2 .9 2 2h-4zm6 13H8c-.55 0-1-.45-1-1s.45-1 1-1h8c.55 0 1 .45 1 1s-.45 1-1 1zm0-4H8c-.55 0-1-.45-1-1s.45-1 1-1h8c.55 0 1 .45 1 1s-.45 1-1 1z"/></svg>
                            <?php elseif ($cart_icon === 'basket') : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M17.21 9l-4.38-6.56c-.19-.28-.51-.42-.83-.42-.32 0-.64.14-.83.43L6.79 9H2c-.55 0-1 .45-1 1 0 .09.01.18.04.27l2.54 9.27c.23.84 1 1.46 1.92 1.46h13c.92 0 1.69-.62 1.93-1.46l2.54-9.27L23 10c0-.55-.45-1-1-1h-4.79zM9 9l3-4.4L15 9H9zm3 8c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/></svg>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($show_count) : ?>
                        <div class="aqualuxe-mini-cart-count">
                            <span class="count"><?php echo esc_html($cart_count); ?></span>
                            <span class="count-text"><?php echo esc_html(_n('item', 'items', $cart_count, 'aqualuxe')); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($show_total) : ?>
                        <div class="aqualuxe-mini-cart-total">
                            <?php echo $cart_total; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($cart_style === 'dropdown') : ?>
                        <div class="aqualuxe-mini-cart-toggle">
                            <span class="toggle-icon"></span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="aqualuxe-mini-cart-content">
                    <?php if ($cart_is_empty && $show_empty_cart) : ?>
                        <div class="aqualuxe-mini-cart-empty">
                            <p><?php echo esc_html($empty_cart_message); ?></p>
                            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="button aqualuxe-mini-cart-shop-button"><?php esc_html_e('Shop Now', 'aqualuxe'); ?></a>
                        </div>
                    <?php else : ?>
                        <div class="aqualuxe-mini-cart-items">
                            <?php 
                            $item_count = 0;
                            foreach ($cart_contents as $cart_item_key => $cart_item) {
                                if ($max_products > 0 && $item_count >= $max_products) {
                                    break;
                                }
                                
                                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                                
                                if ($_product && $_product->exists() && $cart_item['quantity'] > 0) {
                                    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                                    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                                    $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                                    $product_price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                                    $product_subtotal = apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
                                    ?>
                                    <div class="aqualuxe-mini-cart-item" data-item-key="<?php echo esc_attr($cart_item_key); ?>">
                                        <?php if ($show_product_image) : ?>
                                            <div class="aqualuxe-mini-cart-item-image">
                                                <?php if ($product_permalink) : ?>
                                                    <a href="<?php echo esc_url($product_permalink); ?>">
                                                        <?php echo $thumbnail; ?>
                                                    </a>
                                                <?php else : ?>
                                                    <?php echo $thumbnail; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="aqualuxe-mini-cart-item-details">
                                            <div class="aqualuxe-mini-cart-item-name">
                                                <?php if ($product_permalink) : ?>
                                                    <a href="<?php echo esc_url($product_permalink); ?>">
                                                        <?php echo esc_html($product_name); ?>
                                                    </a>
                                                <?php else : ?>
                                                    <?php echo esc_html($product_name); ?>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <?php if ($show_product_variations && !empty($cart_item['variation_id'])) : ?>
                                                <div class="aqualuxe-mini-cart-item-variations">
                                                    <?php 
                                                    echo wc_get_formatted_cart_item_data($cart_item);
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="aqualuxe-mini-cart-item-quantity-price">
                                                <?php if ($show_product_quantity) : ?>
                                                    <div class="aqualuxe-mini-cart-item-quantity">
                                                        <span class="quantity-label"><?php esc_html_e('Qty:', 'aqualuxe'); ?></span>
                                                        <div class="quantity-controls">
                                                            <button type="button" class="quantity-decrease" data-item-key="<?php echo esc_attr($cart_item_key); ?>" data-quantity="<?php echo esc_attr($cart_item['quantity'] - 1); ?>">-</button>
                                                            <span class="quantity"><?php echo esc_html($cart_item['quantity']); ?></span>
                                                            <button type="button" class="quantity-increase" data-item-key="<?php echo esc_attr($cart_item_key); ?>" data-quantity="<?php echo esc_attr($cart_item['quantity'] + 1); ?>">+</button>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if ($show_product_price) : ?>
                                                    <div class="aqualuxe-mini-cart-item-price">
                                                        <?php echo $product_price; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <div class="aqualuxe-mini-cart-item-remove">
                                            <a href="#" class="remove-item" aria-label="<?php esc_attr_e('Remove this item', 'aqualuxe'); ?>" data-item-key="<?php echo esc_attr($cart_item_key); ?>">×</a>
                                        </div>
                                    </div>
                                    <?php
                                    $item_count++;
                                }
                            }
                            
                            // Show "more items" message if needed
                            if ($max_products > 0 && count($cart_contents) > $max_products) {
                                $remaining_items = count($cart_contents) - $max_products;
                                echo '<div class="aqualuxe-mini-cart-more-items">';
                                echo sprintf(
                                    _n(
                                        'And %d more item...',
                                        'And %d more items...',
                                        $remaining_items,
                                        'aqualuxe'
                                    ),
                                    $remaining_items
                                );
                                echo '</div>';
                            }
                            ?>
                        </div>
                        
                        <div class="aqualuxe-mini-cart-subtotal">
                            <span class="subtotal-label"><?php esc_html_e('Subtotal:', 'aqualuxe'); ?></span>
                            <span class="subtotal-amount"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
                        </div>
                        
                        <div class="aqualuxe-mini-cart-actions">
                            <?php if ($show_view_cart_button) : ?>
                                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="button aqualuxe-mini-cart-view-cart"><?php esc_html_e('View Cart', 'aqualuxe'); ?></a>
                            <?php endif; ?>
                            
                            <?php if ($show_checkout_button) : ?>
                                <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="button aqualuxe-mini-cart-checkout"><?php esc_html_e('Checkout', 'aqualuxe'); ?></a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            echo $args['after_widget'];

            // Get the buffered content and cache it
            $widget_content = ob_get_clean();
            $this->cache_widget($cache_key, $widget_content);
            
            echo $widget_content;
        }

        /**
         * Widget Backend
         *
         * @param array $instance Previously saved values from database.
         */
        public function form($instance) {
            $title = !empty($instance['title']) ? $instance['title'] : __('Shopping Cart', 'aqualuxe');
            $show_count = isset($instance['show_count']) ? (bool) $instance['show_count'] : true;
            $show_total = isset($instance['show_total']) ? (bool) $instance['show_total'] : true;
            $show_empty_cart = isset($instance['show_empty_cart']) ? (bool) $instance['show_empty_cart'] : true;
            $cart_style = !empty($instance['cart_style']) ? $instance['cart_style'] : 'dropdown';
            $show_cart_icon = isset($instance['show_cart_icon']) ? (bool) $instance['show_cart_icon'] : true;
            $show_checkout_button = isset($instance['show_checkout_button']) ? (bool) $instance['show_checkout_button'] : true;
            $show_view_cart_button = isset($instance['show_view_cart_button']) ? (bool) $instance['show_view_cart_button'] : true;
            $show_product_image = isset($instance['show_product_image']) ? (bool) $instance['show_product_image'] : true;
            $show_product_price = isset($instance['show_product_price']) ? (bool) $instance['show_product_price'] : true;
            $show_product_quantity = isset($instance['show_product_quantity']) ? (bool) $instance['show_product_quantity'] : true;
            $show_product_variations = isset($instance['show_product_variations']) ? (bool) $instance['show_product_variations'] : true;
            $max_products = !empty($instance['max_products']) ? absint($instance['max_products']) : 5;
            $empty_cart_message = !empty($instance['empty_cart_message']) ? $instance['empty_cart_message'] : __('Your cart is empty.', 'aqualuxe');
            $cart_icon = !empty($instance['cart_icon']) ? $instance['cart_icon'] : 'default';
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('cart_style')); ?>"><?php esc_html_e('Cart Style:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('cart_style')); ?>" name="<?php echo esc_attr($this->get_field_name('cart_style')); ?>">
                    <option value="dropdown" <?php selected($cart_style, 'dropdown'); ?>><?php esc_html_e('Dropdown', 'aqualuxe'); ?></option>
                    <option value="sidebar" <?php selected($cart_style, 'sidebar'); ?>><?php esc_html_e('Sidebar', 'aqualuxe'); ?></option>
                    <option value="popup" <?php selected($cart_style, 'popup'); ?>><?php esc_html_e('Popup', 'aqualuxe'); ?></option>
                </select>
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('cart_icon')); ?>"><?php esc_html_e('Cart Icon:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('cart_icon')); ?>" name="<?php echo esc_attr($this->get_field_name('cart_icon')); ?>">
                    <option value="default" <?php selected($cart_icon, 'default'); ?>><?php esc_html_e('Default Cart', 'aqualuxe'); ?></option>
                    <option value="bag" <?php selected($cart_icon, 'bag'); ?>><?php esc_html_e('Shopping Bag', 'aqualuxe'); ?></option>
                    <option value="basket" <?php selected($cart_icon, 'basket'); ?>><?php esc_html_e('Shopping Basket', 'aqualuxe'); ?></option>
                </select>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_cart_icon); ?> id="<?php echo esc_attr($this->get_field_id('show_cart_icon')); ?>" name="<?php echo esc_attr($this->get_field_name('show_cart_icon')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_cart_icon')); ?>"><?php esc_html_e('Show cart icon?', 'aqualuxe'); ?></label>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_count); ?> id="<?php echo esc_attr($this->get_field_id('show_count')); ?>" name="<?php echo esc_attr($this->get_field_name('show_count')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_count')); ?>"><?php esc_html_e('Show item count?', 'aqualuxe'); ?></label>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_total); ?> id="<?php echo esc_attr($this->get_field_id('show_total')); ?>" name="<?php echo esc_attr($this->get_field_name('show_total')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_total')); ?>"><?php esc_html_e('Show cart total?', 'aqualuxe'); ?></label>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_empty_cart); ?> id="<?php echo esc_attr($this->get_field_id('show_empty_cart')); ?>" name="<?php echo esc_attr($this->get_field_name('show_empty_cart')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_empty_cart')); ?>"><?php esc_html_e('Show empty cart message?', 'aqualuxe'); ?></label>
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('empty_cart_message')); ?>"><?php esc_html_e('Empty Cart Message:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('empty_cart_message')); ?>" name="<?php echo esc_attr($this->get_field_name('empty_cart_message')); ?>" type="text" value="<?php echo esc_attr($empty_cart_message); ?>">
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('max_products')); ?>"><?php esc_html_e('Maximum Products to Show:', 'aqualuxe'); ?></label>
                <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('max_products')); ?>" name="<?php echo esc_attr($this->get_field_name('max_products')); ?>" type="number" step="1" min="0" value="<?php echo esc_attr($max_products); ?>" size="3">
                <span class="description"><?php esc_html_e('(0 = show all)', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_product_image); ?> id="<?php echo esc_attr($this->get_field_id('show_product_image')); ?>" name="<?php echo esc_attr($this->get_field_name('show_product_image')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_product_image')); ?>"><?php esc_html_e('Show product images?', 'aqualuxe'); ?></label>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_product_price); ?> id="<?php echo esc_attr($this->get_field_id('show_product_price')); ?>" name="<?php echo esc_attr($this->get_field_name('show_product_price')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_product_price')); ?>"><?php esc_html_e('Show product prices?', 'aqualuxe'); ?></label>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_product_quantity); ?> id="<?php echo esc_attr($this->get_field_id('show_product_quantity')); ?>" name="<?php echo esc_attr($this->get_field_name('show_product_quantity')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_product_quantity')); ?>"><?php esc_html_e('Show quantity controls?', 'aqualuxe'); ?></label>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_product_variations); ?> id="<?php echo esc_attr($this->get_field_id('show_product_variations')); ?>" name="<?php echo esc_attr($this->get_field_name('show_product_variations')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_product_variations')); ?>"><?php esc_html_e('Show product variations?', 'aqualuxe'); ?></label>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_view_cart_button); ?> id="<?php echo esc_attr($this->get_field_id('show_view_cart_button')); ?>" name="<?php echo esc_attr($this->get_field_name('show_view_cart_button')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_view_cart_button')); ?>"><?php esc_html_e('Show "View Cart" button?', 'aqualuxe'); ?></label>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_checkout_button); ?> id="<?php echo esc_attr($this->get_field_id('show_checkout_button')); ?>" name="<?php echo esc_attr($this->get_field_name('show_checkout_button')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_checkout_button')); ?>"><?php esc_html_e('Show "Checkout" button?', 'aqualuxe'); ?></label>
            </p>
            <?php
        }

        /**
         * Sanitize widget form values as they are saved.
         *
         * @param array $new_instance Values just sent to be saved.
         * @param array $old_instance Previously saved values from database.
         *
         * @return array Updated safe values to be saved.
         */
        public function update($new_instance, $old_instance) {
            $instance = array();
            $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
            $instance['show_count'] = isset($new_instance['show_count']) ? (bool) $new_instance['show_count'] : false;
            $instance['show_total'] = isset($new_instance['show_total']) ? (bool) $new_instance['show_total'] : false;
            $instance['show_empty_cart'] = isset($new_instance['show_empty_cart']) ? (bool) $new_instance['show_empty_cart'] : false;
            $instance['cart_style'] = (!empty($new_instance['cart_style'])) ? sanitize_text_field($new_instance['cart_style']) : 'dropdown';
            $instance['show_cart_icon'] = isset($new_instance['show_cart_icon']) ? (bool) $new_instance['show_cart_icon'] : false;
            $instance['show_checkout_button'] = isset($new_instance['show_checkout_button']) ? (bool) $new_instance['show_checkout_button'] : false;
            $instance['show_view_cart_button'] = isset($new_instance['show_view_cart_button']) ? (bool) $new_instance['show_view_cart_button'] : false;
            $instance['show_product_image'] = isset($new_instance['show_product_image']) ? (bool) $new_instance['show_product_image'] : false;
            $instance['show_product_price'] = isset($new_instance['show_product_price']) ? (bool) $new_instance['show_product_price'] : false;
            $instance['show_product_quantity'] = isset($new_instance['show_product_quantity']) ? (bool) $new_instance['show_product_quantity'] : false;
            $instance['show_product_variations'] = isset($new_instance['show_product_variations']) ? (bool) $new_instance['show_product_variations'] : false;
            $instance['max_products'] = (!empty($new_instance['max_products'])) ? absint($new_instance['max_products']) : 5;
            $instance['empty_cart_message'] = (!empty($new_instance['empty_cart_message'])) ? sanitize_text_field($new_instance['empty_cart_message']) : __('Your cart is empty.', 'aqualuxe');
            $instance['cart_icon'] = (!empty($new_instance['cart_icon'])) ? sanitize_text_field($new_instance['cart_icon']) : 'default';

            // Clear the widget cache
            $this->flush_widget_cache();

            return $instance;
        }

        /**
         * AJAX handler for updating mini cart
         */
        public function ajax_update_mini_cart() {
            // Check nonce
            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-mini-cart-nonce')) {
                wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
            }

            // Check action
            if (!isset($_POST['action_type'])) {
                wp_send_json_error(array('message' => __('No action specified.', 'aqualuxe')));
            }

            $action_type = sanitize_text_field($_POST['action_type']);
            $item_key = isset($_POST['item_key']) ? sanitize_text_field($_POST['item_key']) : '';
            $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 0;

            // Perform the requested action
            switch ($action_type) {
                case 'remove':
                    if (!empty($item_key)) {
                        WC()->cart->remove_cart_item($item_key);
                    }
                    break;

                case 'update':
                    if (!empty($item_key) && $quantity >= 0) {
                        if ($quantity === 0) {
                            WC()->cart->remove_cart_item($item_key);
                        } else {
                            WC()->cart->set_quantity($item_key, $quantity);
                        }
                    }
                    break;

                default:
                    wp_send_json_error(array('message' => __('Invalid action.', 'aqualuxe')));
                    break;
            }

            // Get updated cart fragments
            $fragments = array(
                'cart_count' => WC()->cart->get_cart_contents_count(),
                'cart_total' => WC()->cart->get_cart_total(),
                'is_empty' => WC()->cart->is_empty(),
            );

            // Clear the widget cache
            $this->flush_widget_cache();

            wp_send_json_success($fragments);
        }

        /**
         * Get cached widget
         *
         * @param string $cache_key Cache key
         * @return string|false Cached widget or false if not cached
         */
        protected function get_cached_widget($cache_key) {
            // Check if we have a cached version
            $cached = get_transient($cache_key);
            
            if ($cached) {
                return $cached;
            }
            
            return false;
        }

        /**
         * Cache widget
         *
         * @param string $cache_key Cache key
         * @param string $content Widget content to cache
         */
        protected function cache_widget($cache_key, $content) {
            // Cache the widget for the specified time
            set_transient($cache_key, $content, $this->cache_time);
        }

        /**
         * Flush widget cache
         */
        public function flush_widget_cache() {
            // Get all transients
            global $wpdb;
            
            $transients = $wpdb->get_col(
                "SELECT option_name FROM $wpdb->options 
                WHERE option_name LIKE '_transient_aqualuxe_mini_cart_%'"
            );
            
            // Delete all our transients
            foreach ($transients as $transient) {
                $transient_name = str_replace('_transient_', '', $transient);
                delete_transient($transient_name);
            }
        }
    }
}

/**
 * Register the Mini Cart Widget
 */
function aqualuxe_register_mini_cart_widget() {
    // Only register if WooCommerce is active
    if (class_exists('WooCommerce')) {
        register_widget('AquaLuxe_Mini_Cart_Widget');
    }
}
add_action('widgets_init', 'aqualuxe_register_mini_cart_widget');