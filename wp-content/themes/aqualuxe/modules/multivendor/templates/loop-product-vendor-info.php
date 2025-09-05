<?php
/**
 * Template for displaying vendor info on the shop loop.
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
<div class="aqualuxe-loop-vendor-info text-sm text-gray-500 dark:text-gray-400 mt-1">
    <span><?php \_e('Sold by:', 'aqualuxe'); ?></span>
    <a href="<?php echo \esc_url($vendor->url); ?>" class="font-medium hover:underline"><?php echo \esc_html($vendor->name); ?></a>
</div>
