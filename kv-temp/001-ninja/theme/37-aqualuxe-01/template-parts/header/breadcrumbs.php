<?php
/**
 * Template part for displaying breadcrumbs
 *
 * @package AquaLuxe
 */

// Check if we should display breadcrumbs
if (!function_exists('aqualuxe_breadcrumbs') || !get_theme_mod('aqualuxe_enable_breadcrumbs', true)) {
    return;
}
?>

<div class="breadcrumbs-wrapper bg-secondary-50 dark:bg-secondary-800 py-2">
    <div class="container mx-auto px-4">
        <nav class="breadcrumbs text-sm" aria-label="<?php esc_attr_e('Breadcrumbs', 'aqualuxe'); ?>">
            <?php aqualuxe_breadcrumbs(); ?>
        </nav>
    </div>
</div>