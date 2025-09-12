<?php
/**
 * The sidebar template
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

if (!is_active_sidebar('sidebar-primary')) {
    return;
}
?>

<aside id="secondary" class="sidebar widget-area" role="complementary" aria-label="<?php esc_attr_e('Primary Sidebar', 'aqualuxe'); ?>">
    <div class="sidebar-inner">
        <?php dynamic_sidebar('sidebar-primary'); ?>
    </div>
</aside>