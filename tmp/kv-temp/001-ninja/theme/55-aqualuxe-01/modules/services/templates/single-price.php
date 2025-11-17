<?php
/**
 * Single service price template part
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get service price
$price = isset($args['price']) ? $args['price'] : '';
$sale_price = isset($args['sale_price']) ? $args['sale_price'] : '';
$price_type = isset($args['price_type']) ? $args['price_type'] : '';

// Check if we have a price
if ($price) :
?>
<div class="aqualuxe-single-service-meta-item aqualuxe-single-service-meta-price">
    <div class="aqualuxe-single-service-meta-label"><?php esc_html_e('Price', 'aqualuxe'); ?></div>
    <div class="aqualuxe-single-service-meta-value">
        <div class="aqualuxe-single-service-price">
            <?php if ($sale_price) : ?>
                <del><?php echo esc_html(aqualuxe_format_price($price)); ?></del>
                <ins><?php echo esc_html(aqualuxe_format_price($sale_price)); ?></ins>
            <?php else : ?>
                <?php echo esc_html(aqualuxe_format_price($price)); ?>
            <?php endif; ?>

            <?php if ($price_type) : ?>
                <span class="aqualuxe-single-service-price-type"><?php echo esc_html($price_type); ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>