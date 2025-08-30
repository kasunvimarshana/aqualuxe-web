<?php
/**
 * Template part for displaying author bio
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

$author_id = get_the_author_meta('ID');
$author_avatar = get_avatar($author_id, 96, '', '', array('class' => 'rounded-full'));
$author_name = get_the_author_meta('display_name');
$author_bio = get_the_author_meta('description');
$author_posts_url = get_author_posts_url($author_id);
$author_website = get_the_author_meta('user_url');
?>

<div class="author-bio mt-12 p-6 md:p-8 bg-white dark:bg-dark-700 rounded-xl shadow-soft">
    <div class="flex flex-col md:flex-row md:items-center">
        <?php if ($author_avatar) : ?>
            <div class="author-avatar mb-4 md:mb-0 md:mr-6">
                <?php echo $author_avatar; ?>
            </div>
        <?php endif; ?>
        
        <div class="author-info flex-grow">
            <h2 class="author-title text-xl font-bold text-dark-800 dark:text-white mb-2">
                <?php echo esc_html__('About', 'aqualuxe'); ?> <?php echo esc_html($author_name); ?>
            </h2>
            
            <?php if ($author_bio) : ?>
                <div class="author-description text-dark-600 dark:text-dark-200 mb-4">
                    <?php echo wpautop($author_bio); ?>
                </div>
            <?php endif; ?>
            
            <div class="author-links flex flex-wrap gap-4">
                <a href="<?php echo esc_url($author_posts_url); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <?php 
                    printf(
                        /* translators: %s: author name */
                        esc_html__('View all posts by %s', 'aqualuxe'),
                        esc_html($author_name)
                    ); 
                    ?>
                </a>
                
                <?php if ($author_website) : ?>
                    <a href="<?php echo esc_url($author_website); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors" target="_blank" rel="noopener noreferrer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                        </svg>
                        <?php echo esc_html__('Website', 'aqualuxe'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>