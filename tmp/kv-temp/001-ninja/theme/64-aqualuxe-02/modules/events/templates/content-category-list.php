<?php
/**
 * Category List Item Template
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

<div class="aqualuxe-event-category-list-item">
    <div class="aqualuxe-event-category-list-inner" style="border-color: <?php echo esc_attr($category_color); ?>;">
        <?php if ($category_image) : ?>
            <div class="aqualuxe-event-category-list-image">
                <a href="<?php echo esc_url($category->get_permalink()); ?>">
                    <img src="<?php echo esc_url($category_image); ?>" alt="<?php echo esc_attr($category->get_name()); ?>" />
                </a>
            </div>
        <?php endif; ?>
        
        <div class="aqualuxe-event-category-list-content">
            <h3 class="aqualuxe-event-category-list-title">
                <a href="<?php echo esc_url($category->get_permalink()); ?>">
                    <?php echo esc_html($category->get_name()); ?>
                </a>
            </h3>
            
            <div class="aqualuxe-event-category-list-count">
                <?php echo esc_html(sprintf(
                    _n('%d Event', '%d Events', $category_count, 'aqualuxe'),
                    $category_count
                )); ?>
            </div>
            
            <?php if ($category->get_description()) : ?>
                <div class="aqualuxe-event-category-list-description">
                    <?php echo wp_trim_words($category->get_description(), 20); ?>
                </div>
            <?php endif; ?>
            
            <div class="aqualuxe-event-category-list-actions">
                <a href="<?php echo esc_url($category->get_permalink()); ?>" class="aqualuxe-button" style="background-color: <?php echo esc_attr($category_color); ?>;">
                    <?php echo esc_html__('View Events', 'aqualuxe'); ?>
                </a>
            </div>
        </div>
    </div>
</div>