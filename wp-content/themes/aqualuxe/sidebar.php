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

<aside id="secondary" class="widget-area sidebar-primary" role="complementary" aria-label="<?php esc_attr_e('Primary sidebar', 'aqualuxe'); ?>">
    <div class="sidebar-inner">
        <?php dynamic_sidebar('sidebar-primary'); ?>
    </div>
</aside>