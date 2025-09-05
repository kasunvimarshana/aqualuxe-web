<?php
/**
 * Template for displaying subscription details on the single product page.
 *
 * @var array $args {
 *     An array of arguments.
 *     @type \WC_Product $product The product object.
 *     @type array|null  $details The subscription details.
 * }
 */

if (empty($args['details'])) {
    return;
}

$product = $args['product'];
$details = $args['details'];
?>
<div class="aqualuxe-subscription-details mt-4">
    <?php if (!$details['is_variable']) : ?>
        <div class="subscription-price-summary p-4 border rounded-lg bg-gray-50 dark:bg-gray-800">
            <h3 class="text-lg font-semibold mb-2"><?php \_e('Subscription Summary', 'aqualuxe'); ?></h3>
            <div class="price-html">
                <?php echo $details['price_string']; ?>
            </div>
        </div>
    <?php else: ?>
         <div class="subscription-price-summary p-4 border rounded-lg bg-gray-50 dark:bg-gray-800">
            <h3 class="text-lg font-semibold mb-2"><?php \_e('Subscription Options', 'aqualuxe'); ?></h3>
            <p class="text-gray-600 dark:text-gray-400"><?php \_e('Please select an option to see the final price.', 'aqualuxe'); ?></p>
        </div>
    <?php endif; ?>
</div>
