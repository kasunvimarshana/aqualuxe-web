<?php
/**
 * Template for displaying a single sustainability initiative summary.
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('group bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-lg'); ?>>
    <div class="relative">
        <a href="<?php the_permalink(); ?>">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large', ['class' => 'w-full h-56 object-cover transition-transform duration-500 group-hover:scale-105']); ?>
            <?php else : ?>
                <div class="w-full h-56 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <span class="text-gray-400"><?php _e('Image not available', 'aqualuxe'); ?></span>
                </div>
            <?php endif; ?>
        </a>
        <div class="absolute bottom-0 left-0 bg-gradient-to-t from-black/70 to-transparent w-full p-4">
            <?php the_title(sprintf('<h2 class="text-xl font-bold text-white leading-tight"><a href="%s" class="hover:underline">', esc_url(get_permalink())), '</a></h2>'); ?>
        </div>
    </div>
    <div class="p-6">
        <div class="entry-summary text-gray-600 dark:text-gray-400 mb-4">
            <?php the_excerpt(); ?>
        </div>
        <a href="<?php the_permalink(); ?>" class="font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
            <?php _e('Read More', 'aqualuxe'); ?>
        </a>
    </div>
</article>
