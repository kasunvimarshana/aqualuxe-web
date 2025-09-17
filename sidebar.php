<?php
/**
 * Sidebar Template
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!is_active_sidebar('sidebar-1')) {
    return;
}

?>
<aside id="secondary" class="widget-area sidebar lg:w-80 lg:pl-8" role="complementary">
    <div class="sidebar-content space-y-6">
        <?php dynamic_sidebar('sidebar-1'); ?>
    </div>
</aside>