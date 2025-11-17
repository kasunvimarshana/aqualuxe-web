<?php
/**
 * Template for displaying event summary info.
 *
 * @var array $args {
 *     An array of arguments.
 *     @type array|null  $details The event details.
 * }
 */

if (empty($args['details'])) {
    return;
}

$details = $args['details'];
$start_datetime = new \DateTime($details['start_date']);
?>
<div class="aqualuxe-event-summary mb-6 p-6 border rounded-lg bg-gray-50 dark:bg-gray-800">
    <h2 class="text-2xl font-bold mb-4"><?php \_e('Event Details', 'aqualuxe'); ?></h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h3 class="text-md font-semibold text-gray-500 dark:text-gray-400"><?php \_e('Date & Time', 'aqualuxe'); ?></h3>
            <p class="text-lg">
                <?php echo $start_datetime->format('F j, Y'); ?>
                <?php if (!$details['is_all_day']) : ?>
                    at <?php echo $start_datetime->format('g:i A'); ?>
                <?php endif; ?>
            </p>
        </div>
        <?php if (!empty($details['venue'])) : ?>
            <div>
                <h3 class="text-md font-semibold text-gray-500 dark:text-gray-400"><?php \_e('Venue', 'aqualuxe'); ?></h3>
                <p class="text-lg"><?php echo \esc_html($details['venue']); ?></p>
            </div>
        <?php endif; ?>
        <?php if (!empty($details['cost'])) : ?>
            <div>
                <h3 class="text-md font-semibold text-gray-500 dark:text-gray-400"><?php \_e('Cost', 'aqualuxe'); ?></h3>
                <p class="text-lg"><?php echo \wp_kses_post($details['cost']); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
