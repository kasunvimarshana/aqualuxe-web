<?php
/**
 * Template for displaying vendor info on the single product page.
 *
 * @var array $args {
 *     An array of arguments.
 *     @type stdClass $vendor The vendor object.
 * }
 */

if (empty($args['vendor'])) {
    return;
}

$vendor = $args['vendor'];
?>
<div class="aqualuxe-vendor-info mt-4 p-4 border rounded-lg">
    <h3 class="text-lg font-semibold mb-2"><?php \_e('Sold By:', 'aqualuxe'); ?></h3>
    <div class="flex items-center">
        <?php if (!empty($vendor->logo)) : ?>
            <a href="<?php echo \esc_url($vendor->url); ?>" class="mr-4">
                <img src="<?php echo \esc_url($vendor->logo); ?>" alt="<?php echo \esc_attr($vendor->name); ?>" class="w-16 h-16 rounded-full object-cover">
            </a>
        <?php endif; ?>
        <div>
            <a href="<?php echo \esc_url($vendor->url); ?>" class="text-xl font-bold text-gray-800 dark:text-gray-200 hover:underline">
                <?php echo \esc_html($vendor->name); ?>
            </a>
            <?php // Placeholder for vendor rating, if available from the plugin ?>
        </div>
    </div>
</div>
