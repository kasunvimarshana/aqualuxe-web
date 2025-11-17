<?php
/**
 * The sidebar containing the main widget area
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

if (!is_active_sidebar('sidebar-primary')) {
    return;
}
?>

<aside id="secondary" class="widget-area sidebar" role="complementary">
    <div class="sidebar-content">
        <?php dynamic_sidebar('sidebar-primary'); ?>
    </div>
</aside><!-- #secondary -->