<?php
/**
 * The sidebar containing the main widget area
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

if (!is_active_sidebar('aqualuxe-sidebar')) {
    return;
}
?>

<aside id="secondary" class="widget-area sidebar" role="complementary" aria-label="<?php _e('Sidebar', AquaLuxeTheme::TEXT_DOMAIN); ?>">
    <div class="sidebar-inner">
        <?php dynamic_sidebar('aqualuxe-sidebar'); ?>
    </div>
</aside>
