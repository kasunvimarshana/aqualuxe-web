<?php
/**
 * Wishlist Template
 *
 * This template displays the user's wishlist.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// Get wishlist items
$wishlist_items = array();
if (function_exists('aqualuxe_get_wishlist_items')) {
    $wishlist_items = aqualuxe_get_wishlist_items();
}

// Get wishlist page URL
$wishlist_page_id = get_option('aqualuxe_wishlist_page_id');
$wishlist_url = $wishlist_page_id ? get_permalink($wishlist_page_id) : home_url('/');

// Check if wishlist is empty
$is_empty = empty($wishlist_items);

get_header();
?>

<div class="wishlist-container container mx-auto px-4 py-8">
    <header class="wishlist-header mb-8">
        <h1 class="wishlist-title text-3xl font-bold"><?php esc_html_e('My Wishlist', 'aqualuxe'); ?></h1>
    </header>

    <?php if ($is_empty) : ?>
        <div class="wishlist-empty bg-white p-8 rounded-lg shadow-sm text-center">
            <div class="wishlist-empty-icon mb-4">
                <?php aqualuxe_svg_icon('heart', array('class' => 'w-16 h-16 text-gray-300 mx-auto')); ?>
            </div>
            <p class="wishlist-empty-message text-lg mb-6"><?php esc_html_e('Your wishlist is currently empty.', 'aqualuxe'); ?></p>
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-colors">
                <?php esc_html_e('Continue Shopping', 'aqualuxe'); ?>
            </a>
        </div>
    <?php else : ?>
        <div class="wishlist-content">
            <div class="wishlist-actions mb-6 flex justify-between items-center">
                <div class="wishlist-count">
                    <?php
                    printf(
                        /* translators: %d: number of items in wishlist */
                        _n('%d item in your wishlist', '%d items in your wishlist', count($wishlist_items), 'aqualuxe'),
                        count($wishlist_items)
                    );
                    ?>
                </div>
                <div class="wishlist-buttons">
                    <?php if (count($wishlist_items) > 1) : ?>
                        <button class="add-all-to-cart-btn bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md transition-colors duration-200 mr-2">
                            <?php esc_html_e('Add All to Cart', 'aqualuxe'); ?>
                        </button>
                    <?php endif; ?>
                    <button class="clear-wishlist-btn text-gray-600 hover:text-red-600 px-4 py-2 transition-colors duration-200">
                        <?php esc_html_e('Clear Wishlist', 'aqualuxe'); ?>
                    </button>
                </div>
            </div>

            <div class="wishlist-items grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($wishlist_items as $item) : ?>
                    <?php
                    $product_id = $item['product_id'];
                    $product = wc_get_product($product_id);
                    
                    if (!$product || !$product->is_visible()) {
                        continue;
                    }
                    
                    $product_permalink = $product->get_permalink();
                    $thumbnail = $product->get_image('woocommerce_thumbnail');
                    $price_html = $product->get_price_html();
                    $add_to_cart_url = $product->add_to_cart_url();
                    $add_to_cart_text = $product->add_to_cart_text();
                    $date_added = isset($item['date_added']) ? $item['date_added'] : '';
                    ?>
                    <div class="wishlist-item bg-white rounded-lg shadow-sm overflow-hidden transition-shadow hover:shadow-md">
                        <div class="wishlist-item-remove absolute top-2 right-2 z-10">
                            <button class="remove-from-wishlist-btn inline-flex items-center justify-center w-8 h-8 rounded-full bg-white text-gray-700 hover:text-red-600 shadow-sm transition-colors duration-200" data-product-id="<?php echo esc_attr($product_id); ?>" aria-label="<?php esc_attr_e('Remove from wishlist', 'aqualuxe'); ?>">
                                <?php aqualuxe_svg_icon('close', array('class' => 'w-4 h-4')); ?>
                            </button>
                        </div>

                        <div class="wishlist-item-thumbnail relative overflow-hidden">
                            <a href="<?php echo esc_url($product_permalink); ?>" class="block">
                                <?php echo $thumbnail; ?>
                            </a>
                            
                            <?php if ($product->is_on_sale()) : ?>
                                <span class="onsale absolute top-2 left-2 bg-primary-600 text-white text-xs font-bold px-2 py-1 rounded-md z-10">
                                    <?php esc_html_e('Sale', 'aqualuxe'); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="wishlist-item-details p-4">
                            <h3 class="wishlist-item-title text-base font-bold mb-2">
                                <a href="<?php echo esc_url($product_permalink); ?>" class="hover:text-primary-600 transition-colors">
                                    <?php echo esc_html($product->get_name()); ?>
                                </a>
                            </h3>

                            <div class="wishlist-item-price mb-3">
                                <?php echo $price_html; ?>
                            </div>

                            <?php if ($product->get_stock_status() === 'outofstock') : ?>
                                <div class="wishlist-item-stock text-red-600 text-sm mb-3">
                                    <?php esc_html_e('Out of stock', 'aqualuxe'); ?>
                                </div>
                            <?php elseif ($product->get_stock_status() === 'onbackorder') : ?>
                                <div class="wishlist-item-stock text-orange-600 text-sm mb-3">
                                    <?php esc_html_e('Available on backorder', 'aqualuxe'); ?>
                                </div>
                            <?php else : ?>
                                <div class="wishlist-item-stock text-green-600 text-sm mb-3">
                                    <?php esc_html_e('In stock', 'aqualuxe'); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($date_added) : ?>
                                <div class="wishlist-item-date text-xs text-gray-500 mb-3">
                                    <?php
                                    printf(
                                        /* translators: %s: date added to wishlist */
                                        esc_html__('Added on %s', 'aqualuxe'),
                                        date_i18n(get_option('date_format'), strtotime($date_added))
                                    );
                                    ?>
                                </div>
                            <?php endif; ?>

                            <div class="wishlist-item-actions">
                                <?php if ($product->is_in_stock()) : ?>
                                    <a href="<?php echo esc_url($add_to_cart_url); ?>" data-product_id="<?php echo esc_attr($product_id); ?>" data-quantity="1" class="add-to-cart-btn button add_to_cart_button ajax_add_to_cart w-full text-center">
                                        <?php echo esc_html($add_to_cart_text); ?>
                                    </a>
                                <?php else : ?>
                                    <a href="<?php echo esc_url($product_permalink); ?>" class="view-product-btn w-full text-center">
                                        <?php esc_html_e('View Product', 'aqualuxe'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php
    // Display recommended products if wishlist is empty or has few items
    if ($is_empty || count($wishlist_items) < 4) :
        $args = array(
            'posts_per_page' => 4,
            'columns'        => 4,
            'orderby'        => 'rand',
            'order'          => 'desc',
        );
    ?>
        <div class="recommended-products mt-12 pt-8 border-t border-gray-200">
            <h2 class="text-2xl font-bold mb-6"><?php esc_html_e('Recommended Products', 'aqualuxe'); ?></h2>
            <?php
            if (function_exists('aqualuxe_recommended_products')) {
                aqualuxe_recommended_products($args);
            } else {
                echo do_shortcode('[products limit="4" columns="4" orderby="popularity" order="desc"]');
            }
            ?>
        </div>
    <?php endif; ?>
</div>

<?php
get_footer();