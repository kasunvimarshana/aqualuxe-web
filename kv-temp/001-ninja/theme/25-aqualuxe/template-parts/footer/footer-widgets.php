<?php
/**
 * Template part for displaying footer widgets
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

if (!is_active_sidebar('footer-1') && !is_active_sidebar('footer-2') && !is_active_sidebar('footer-3') && !is_active_sidebar('footer-4')) {
    return;
}

// Get the footer layout from theme options
$footer_layout = get_theme_mod('aqualuxe_footer_layout', '4-columns');

// Determine the grid class based on the layout
switch ($footer_layout) {
    case '1-column':
        $grid_class = 'grid-cols-1';
        break;
    case '2-columns':
        $grid_class = 'grid-cols-1 md:grid-cols-2';
        break;
    case '3-columns':
        $grid_class = 'grid-cols-1 md:grid-cols-3';
        break;
    case '4-columns':
    default:
        $grid_class = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4';
        break;
}
?>

<div class="footer-widgets py-12">
    <div class="container mx-auto px-4">
        <div class="grid <?php echo esc_attr($grid_class); ?> gap-8">
            <?php if (is_active_sidebar('footer-1')) : ?>
                <div class="footer-widget-1">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
            <?php endif; ?>
            
            <?php if (is_active_sidebar('footer-2')) : ?>
                <div class="footer-widget-2">
                    <?php dynamic_sidebar('footer-2'); ?>
                </div>
            <?php endif; ?>
            
            <?php if (is_active_sidebar('footer-3')) : ?>
                <div class="footer-widget-3">
                    <?php dynamic_sidebar('footer-3'); ?>
                </div>
            <?php endif; ?>
            
            <?php if (is_active_sidebar('footer-4')) : ?>
                <div class="footer-widget-4">
                    <?php dynamic_sidebar('footer-4'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>