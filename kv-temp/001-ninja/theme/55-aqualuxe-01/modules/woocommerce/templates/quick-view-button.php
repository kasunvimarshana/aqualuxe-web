<?php
/**
 * Quick View Button
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

// Check if quick view is enabled
if (!$module->get_option('quick_view', true)) {
    return;
}
?>

<a href="#" class="quick-view-button" data-product-id="<?php echo esc_attr($product->get_id()); ?>" aria-label="<?php esc_attr_e('Quick View', 'aqualuxe'); ?>">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 3c5.392 0 9.878 3.88 10.819 9-.94 5.12-5.427 9-10.819 9-5.392 0-9.878-3.88-10.819-9C2.121 6.88 6.608 3 12 3zm0 16a9.005 9.005 0 0 0 8.777-7 9.005 9.005 0 0 0-17.554 0A9.005 9.005 0 0 0 12 19zm0-2.5a4.5 4.5 0 1 1 0-9 4.5 4.5 0 0 1 0 9zm0-2a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/></svg>
    <span class="screen-reader-text"><?php esc_html_e('Quick View', 'aqualuxe'); ?></span>
</a>