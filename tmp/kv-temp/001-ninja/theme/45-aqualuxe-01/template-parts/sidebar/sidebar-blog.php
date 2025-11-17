<?php
/**
 * The blog sidebar template
 *
 * @package AquaLuxe
 */

if (!is_active_sidebar('sidebar-blog')) {
    // Fallback to default sidebar if blog sidebar is not active
    get_template_part('template-parts/sidebar/sidebar', 'default');
    return;
}
?>

<div class="sidebar sidebar-blog">
    <?php dynamic_sidebar('sidebar-blog'); ?>
</div>