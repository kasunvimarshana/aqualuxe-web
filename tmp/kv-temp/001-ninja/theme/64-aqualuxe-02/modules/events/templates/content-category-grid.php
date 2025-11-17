<?php
/**
 * Category Grid Item Template
 *
 * @package AquaLuxe
 * @subpackage Events
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$category_color = $category->get_color();
$category_image = $category->get_image_url('medium');
$category_count = $category->get_count();
?>

<div class="aqualuxe-event-category-grid-item">
    <div class="aqualuxe-event-category-grid-inner" style="border-color: <?php echo esc_attr($category_color); ?>;">
        <?php if ($category_image) : ?>
            <div class="aqualuxe-event-category-grid-image">
                <a href="<?php echo esc_url($category->get_permalink()); ?>">
                    <img src="<?php echo esc_url($category_image); ?>" alt="<?php echo esc_attr($category->get_name()); ?>" />
                </a>
            </div>
        <?php endif; ?>
        
        <div class="aqualuxe-event-category-grid-content">
            <h3 class="aqualuxe-event-category-grid-title">
                <a href="<?php echo esc_url($category->get_permalink()); ?>">
                    <?php echo esc_html($category->get_name()); ?>
                </a>
            </h3>
            
            <div class="aqualuxe-event-category-grid-count" style="color: <?php echo esc_attr($category_color); ?>;">
                <?php echo esc_html(sprintf(
                    _n('%d Event', '%d Events', $category_count, 'aqualuxe'),
                    $category_count
                )); ?>
            </div>
            
            <div class="aqualuxe-event-category-grid-actions">
                <a href="<?php echo esc_url($category->get_permalink()); ?>" class="aqualuxe-button aqualuxe-button-outline" style="color: <?php echo esc_attr($category_color); ?>; border-color: <?php echo esc_attr($category_color); ?>;">
                    <?php echo esc_html__('View Events', 'aqualuxe'); ?>
                </a>
            </div>
        </div>
    </div>
</div>