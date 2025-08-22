<?php
/**
 * The sidebar containing the services widget area
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Check if services sidebar is active
if (!is_active_sidebar('services-sidebar')) {
    return;
}
?>

<aside id="secondary" class="widget-area aqualuxe-sidebar aqualuxe-services-sidebar">
    <?php dynamic_sidebar('services-sidebar'); ?>
</aside>