<?php
/**
 * Template for displaying a single classified ad summary.
 *
 * This template is loaded by the [aqualuxe_recent_ads] shortcode.
 * It assumes it's being used inside The Loop.
 */

$post_id = \get_the_ID();
// WP Adverts stores price in a meta field
$price = \get_post_meta($post_id, 'adverts_price', true);
$thumbnail_url = \get_the_post_thumbnail_url($post_id, 'medium') ?: 'https://via.placeholder.com/300';

?>
<div class="aqualuxe-ad-summary border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 dark:border-gray-700">
    <a href="<?php \the_permalink(); ?>">
        <img src="<?php echo \esc_url($thumbnail_url); ?>" alt="<?php \the_title_attribute(); ?>" class="w-full h-48 object-cover">
    </a>
    <div class="p-4">
        <h3 class="font-bold text-lg truncate">
            <a href="<?php \the_permalink(); ?>"><?php \the_title(); ?></a>
        </h3>
        
        <?php if ($price > 0): ?>
            <div class="text-xl font-semibold text-blue-600 dark:text-blue-400 mt-2">
                <?php echo \esc_html(adverts_price($price)); ?>
            </div>
        <?php else: ?>
            <div class="text-lg font-semibold text-gray-500 mt-2">
                <?php \_e('Contact for price', 'aqualuxe'); ?>
            </div>
        <?php endif; ?>

        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">
            <span class="aqualuxe-ad-location">
                <?php 
                // Example of getting a custom taxonomy term
                $location = \get_post_meta($post_id, 'adverts_location', true);
                if ($location) {
                    echo \esc_html($location);
                }
                ?>
            </span>
        </div>
    </div>
</div>
