<?php
/**
 * The shop sidebar template
 *
 * @package AquaLuxe
 */

if (!is_active_sidebar('sidebar-shop')) {
    // Fallback to default sidebar if shop sidebar is not active
    get_template_part('template-parts/sidebar/sidebar', 'default');
    return;
}
?>

<div class="sidebar sidebar-shop">
    <?php dynamic_sidebar('sidebar-shop'); ?>
</div>