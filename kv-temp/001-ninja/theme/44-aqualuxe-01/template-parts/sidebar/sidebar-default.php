<?php
/**
 * The default sidebar template
 *
 * @package AquaLuxe
 */

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<div class="sidebar sidebar-default">
    <?php dynamic_sidebar('sidebar-1'); ?>
</div>