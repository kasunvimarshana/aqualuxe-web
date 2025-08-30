<?php
/**
 * Product Actions
 *
 * @package AquaLuxe
 * @subpackage Modules/WooCommerce
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

global $product;

if (!$product) {
    return;
}

// Get module
$module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
?>

<div class="product-action-buttons">
    <?php
    // Add to cart button
    woocommerce_template_loop_add_to_cart();
    
    // Quick view button
    if ($module->get_option('quick_view', true)) {
        ?>
        <a href="#" class="quick-view-button" data-product-id="<?php echo esc_attr($product->get_id()); ?>" aria-label="<?php esc_attr_e('Quick View', 'aqualuxe'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 3c5.392 0 9.878 3.88 10.819 9-.94 5.12-5.427 9-10.819 9-5.392 0-9.878-3.88-10.819-9C2.121 6.88 6.608 3 12 3zm0 16a9.005 9.005 0 0 0 8.777-7 9.005 9.005 0 0 0-17.554 0A9.005 9.005 0 0 0 12 19zm0-2.5a4.5 4.5 0 1 1 0-9 4.5 4.5 0 0 1 0 9zm0-2a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/></svg>
            <span class="screen-reader-text"><?php esc_html_e('Quick View', 'aqualuxe'); ?></span>
        </a>
        <?php
    }
    
    // Wishlist button
    if ($module->get_option('wishlist', true)) {
        // Get wishlist
        $wishlist = $module->get_wishlist();
        
        // Check if product is in wishlist
        $in_wishlist = in_array($product->get_id(), $wishlist);
        ?>
        <a href="#" class="wishlist-button <?php echo $in_wishlist ? 'in-wishlist' : ''; ?>" data-product-id="<?php echo esc_attr($product->get_id()); ?>" aria-label="<?php esc_attr_e('Add to Wishlist', 'aqualuxe'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12.001 4.529c2.349-2.109 5.979-2.039 8.242.228 2.262 2.268 2.34 5.88.236 8.236l-8.48 8.492-8.478-8.492c-2.104-2.356-2.025-5.974.236-8.236 2.265-2.264 5.888-2.34 8.244-.228z"/></svg>
            <span class="screen-reader-text"><?php esc_html_e('Add to Wishlist', 'aqualuxe'); ?></span>
        </a>
        <?php
    }
    
    // Compare button
    if ($module->get_option('compare', true)) {
        // Get compare
        $compare = $module->get_compare();
        
        // Check if product is in compare
        $in_compare = in_array($product->get_id(), $compare);
        ?>
        <a href="#" class="compare-button <?php echo $in_compare ? 'in-compare' : ''; ?>" data-product-id="<?php echo esc_attr($product->get_id()); ?>" aria-label="<?php esc_attr_e('Add to Compare', 'aqualuxe'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M16 16v-4l5 5-5 5v-4H4v-2h12zM8 2v3.999L20 6v2H8v4L3 7l5-5z"/></svg>
            <span class="screen-reader-text"><?php esc_html_e('Add to Compare', 'aqualuxe'); ?></span>
        </a>
        <?php
    }
    ?>
</div>