<?php
/**
 * Template for displaying a single franchise opportunity summary.
 */

$post_id = get_the_ID();
$meta = get_post_meta($post_id);

$location = $meta['franchise_location'][0] ?? 'N/A';
$investment = $meta['franchise_investment'][0] ?? 'Contact for details';

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300'); ?>>
    <div class="p-6">
        <header class="entry-header mb-4">
            <?php the_title(sprintf('<h2 class="text-2xl font-bold text-gray-900 dark:text-white"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
        </header>

        <div class="entry-summary text-gray-600 dark:text-gray-400 mb-4">
            <?php the_excerpt(); ?>
        </div>

        <footer class="entry-footer">
            <div class="flex justify-between items-center">
                <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">
                    <span class="dashicons dashicons-location-alt mr-1"></span>
                    <?php echo esc_html($location); ?>
                </span>
                <span class="text-sm font-bold text-green-600 dark:text-green-400">
                    <?php echo esc_html($investment); ?>
                </span>
            </div>
            <a href="<?php the_permalink(); ?>" class="aqualuxe-btn-primary w-full text-center mt-4">
                <?php _e('View Opportunity', 'aqualuxe'); ?>
            </a>
        </footer>
    </div>
</article>
