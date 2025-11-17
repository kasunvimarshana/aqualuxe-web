<?php
/**
 * The sidebar containing the main widget area
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside id="secondary" class="widget-area sidebar" role="complementary">
    <div class="sidebar-inner space-y-8">
        <?php dynamic_sidebar('sidebar-1'); ?>
    </div>
</aside><!-- #secondary -->