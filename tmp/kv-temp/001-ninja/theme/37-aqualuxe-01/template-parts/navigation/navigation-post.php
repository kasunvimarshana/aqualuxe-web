<?php
/**
 * Template part for displaying post navigation
 *
 * @package AquaLuxe
 */

$prev_post = get_previous_post();
$next_post = get_next_post();

// Only show navigation if we have either a previous or next post
if (!$prev_post && !$next_post) {
    return;
}
?>

<nav class="post-navigation mt-8 pt-6 border-t border-secondary-200 dark:border-secondary-700" role="navigation" aria-label="<?php esc_attr_e('Post Navigation', 'aqualuxe'); ?>">
    <h2 class="sr-only"><?php esc_html_e('Post navigation', 'aqualuxe'); ?></h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php if ($prev_post) : ?>
            <div class="post-navigation-prev">
                <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" class="group block bg-white dark:bg-secondary-800 rounded-lg shadow-sm hover:shadow-md p-4 transition-shadow">
                    <span class="text-sm text-secondary-500 dark:text-secondary-400 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <?php esc_html_e('Previous Post', 'aqualuxe'); ?>
                    </span>
                    <span class="block mt-1 font-medium text-secondary-900 dark:text-white group-hover:text-primary-500 dark:group-hover:text-primary-400 transition-colors">
                        <?php echo esc_html(get_the_title($prev_post->ID)); ?>
                    </span>
                </a>
            </div>
        <?php endif; ?>
        
        <?php if ($next_post) : ?>
            <div class="post-navigation-next <?php echo (!$prev_post) ? 'md:col-start-2' : ''; ?>">
                <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="group block bg-white dark:bg-secondary-800 rounded-lg shadow-sm hover:shadow-md p-4 transition-shadow text-right">
                    <span class="text-sm text-secondary-500 dark:text-secondary-400 flex items-center justify-end">
                        <?php esc_html_e('Next Post', 'aqualuxe'); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                    <span class="block mt-1 font-medium text-secondary-900 dark:text-white group-hover:text-primary-500 dark:group-hover:text-primary-400 transition-colors">
                        <?php echo esc_html(get_the_title($next_post->ID)); ?>
                    </span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</nav>