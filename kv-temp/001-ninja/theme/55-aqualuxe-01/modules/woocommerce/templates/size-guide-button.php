<?php
/**
 * Size Guide Button
 *
 * @package AquaLuxe
 * @subpackage Modules/WooCommerce
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

global $product;

if (!$product || !$product->is_type('variable')) {
    return;
}

// Get module
$module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];

// Check if size guide is enabled
if (!$module->get_option('size_guide', true)) {
    return;
}

// Get product attributes
$attributes = $product->get_variation_attributes();

// Check if product has size attribute
$has_size_attribute = false;
$size_attribute_name = '';

foreach ($attributes as $attribute_name => $options) {
    $taxonomy = str_replace('pa_', '', $attribute_name);
    
    if (in_array($taxonomy, ['size', 'sizes', 'shoe-size', 'clothing-size'])) {
        $has_size_attribute = true;
        $size_attribute_name = wc_attribute_label($attribute_name);
        break;
    }
}

// Only show size guide button if product has size attribute
if (!$has_size_attribute) {
    return;
}
?>

<a href="#" class="size-guide-button" data-toggle="size-guide-modal" aria-label="<?php esc_attr_e('Size Guide', 'aqualuxe'); ?>">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M2 5l7-3 6 3 6.303-2.701a.5.5 0 0 1 .697.46V19l-7 3-6-3-6.303 2.701a.5.5 0 0 1-.697-.46V5zm14 14.395l4-1.714V5.033l-4 1.714v12.648zm-2-.131V6.736l-4-2v12.528l4 2zm-6-2.011V4.605L4 6.319v12.648l4-1.714z"/></svg>
    <span><?php esc_html_e('Size Guide', 'aqualuxe'); ?></span>
</a>