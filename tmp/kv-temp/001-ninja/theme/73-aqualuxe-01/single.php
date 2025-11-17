<?php
/**
 * Single post template
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            
            <?php
            while (have_posts()):
                the_post();
                
                // Breadcrumbs
                aqualuxe_breadcrumbs();
                ?>
                
                <div class="grid lg:grid-cols-3 gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-2">
                        <?php get_template_part('templates/content/content', 'single'); ?>
                        
                        <!-- Post Navigation -->
                        <nav class="post-navigation mt-12 pt-8 border-t border-gray-200 dark:border-dark-700" aria-label="<?php esc_attr_e('Post navigation', 'aqualuxe'); ?>">
                            <div class="grid md:grid-cols-2 gap-6">
                                <?php
                                $prev_post = get_previous_post();
                                $next_post = get_next_post();
                                
                                if ($prev_post):
                                ?>
                                    <div class="prev-post">
                                        <a href="<?php echo esc_url(get_permalink($prev_post)); ?>" class="group block p-4 rounded-lg border border-gray-200 dark:border-dark-700 hover:border-primary-300 dark:hover:border-primary-600 transition-colors">
                                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                </svg>
                                                <?php esc_html_e('Previous Post', 'aqualuxe'); ?>
                                            </div>
                                            <h3 class="font-semibold group-hover:text-primary-600 transition-colors">
                                                <?php echo esc_html(get_the_title($prev_post)); ?>
                                            </h3>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($next_post): ?>
                                    <div class="next-post">
                                        <a href="<?php echo esc_url(get_permalink($next_post)); ?>" class="group block p-4 rounded-lg border border-gray-200 dark:border-dark-700 hover:border-primary-300 dark:hover:border-primary-600 transition-colors text-right">
                                            <div class="flex items-center justify-end text-sm text-gray-500 dark:text-gray-400 mb-2">
                                                <?php esc_html_e('Next Post', 'aqualuxe'); ?>
                                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </div>
                                            <h3 class="font-semibold group-hover:text-primary-600 transition-colors">
                                                <?php echo esc_html(get_the_title($next_post)); ?>
                                            </h3>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </nav>
                        
                        <!-- Comments -->
                        <?php
                        if (comments_open() || get_comments_number()):
                            comments_template();
                        endif;
                        ?>
                    </div>
                    
                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <?php get_sidebar(); ?>
                    </div>
                </div>
                
                <?php
            endwhile;
            ?>
            
        </div>
    </div>
</main>

<?php
get_footer();
?>
