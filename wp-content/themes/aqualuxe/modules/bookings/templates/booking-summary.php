<?php
/**
 * Te<div class="aqualuxe-booking-summary mb-4 p-4 border rounded-lg bg-gray-50 dark:bg-gray-800">
    <h3 class="text-lg font-semibold mb-2"><?php \_e('Booking Details', 'aqualuxe'); ?></h3>
    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400">
        <li>
            <strong><?php \_e('Duration:', 'aqualuxe'); ?></strong>
            <?php echo sprintf('%d %s', \esc_html($details['duration']), \esc_html($details['duration_unit'])); ?>
        </li>
        <?php if ($details['requires_confirmation']) : ?>
            <li><?php \_e('Requires confirmation by an admin.', 'aqualuxe'); ?></li>
        <?php endif; ?>
        <?php if ($details['has_persons']) : ?>
            <li><?php \_e('Pricing is per person.', 'aqualuxe'); ?></li>
        <?php endif; ?>
    </ul>
</div>laying booking summary info.
 *
 * @var array $args {
 *     An array of arguments.
 *     @type \WC_Product $product The product object.
 *     @type array|null  $details The booking details.
 * }
 */

if (empty($args['details'])) {
    return;
}

$details = $args['details'];
?>
<div class="aqualuxe-booking-summary mb-4 p-4 border rounded-lg bg-gray-50 dark:bg-gray-800">
    <h3 class="text-lg font-semibold mb-2"><?php \_e('Booking Details', 'aqualuxe'); ?></h3>
    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400">
        <li>
            <strong><?php \_e('Duration:', 'aqualuxe'); ?></strong>
            <?php echo sprintf('%d %s', esc_html($details['duration']), esc_html($details['duration_unit'])); ?>
        </li>
        <?php if ($details['requires_confirmation']) : ?>
            <li><?php \_e('Requires confirmation by an admin.', 'aqualuxe'); ?></li>
        <?php endif; ?>
        <?php if ($details['has_persons']) : ?>
            <li><?php \_e('Pricing is per person.', 'aqualuxe'); ?></li>
        <?php endif; ?>
    </ul>
</div>
