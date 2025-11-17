<?php
/**
 * Template for displaying a single service summary.
 * Used in loops and shortcodes.
 */

$thumbnail_url = \has_post_thumbnail() ? \get_the_post_thumbnail_url(null, 'large') : 'https://via.placeholder.com/600x400';
?>
<div class="aqualuxe-service-summary bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transform hover:-translate-y-1 transition-transform duration-300">
    <a href="<?php \the_permalink(); ?>" class="block">
        <img src="<?php echo \esc_url($thumbnail_url); ?>" alt="<?php \the_title_attribute(); ?>" class="w-full h-56 object-cover">
    </a>
    <div class="p-6">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
            <a href="<?php \the_permalink(); ?>"><?php \the_title(); ?></a>
        </h3>
        <div class="text-gray-600 dark:text-gray-400">
            <?php \the_excerpt(); ?>
        </div>
        <a href="<?php \the_permalink(); ?>" class="inline-block mt-4 text-blue-600 dark:text-blue-400 font-semibold hover:underline">
            <?php \_e('Learn More &rarr;', 'aqualuxe'); ?>
        </a>
    </div>
</div>
