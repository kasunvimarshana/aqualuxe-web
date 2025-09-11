<?php
/**
 * Single post template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="main" class="site-main flex-1" role="main">
    <div class="container mx-auto px-4 py-8 lg:py-12">
        
        <?php while (have_posts()) : the_post(); ?>
            
            <div class="content-area">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
                    
                    <!-- Main content -->
                    <div class="main-content lg:col-span-2">
                        <?php get_template_part('template-parts/content/content', get_post_type()); ?>
                        
                        <!-- Post navigation -->
                        <?php
                        $prev_post = get_previous_post();
                        $next_post = get_next_post();
                        
                        if ($prev_post || $next_post) :
                        ?>
                            <nav class="post-navigation mt-12 pt-8 border-t border-secondary-200 dark:border-secondary-700" role="navigation" aria-label="<?php esc_attr_e('Post navigation', 'aqualuxe'); ?>">
                                <div class="nav-links grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <?php if ($prev_post) : ?>
                                        <div class="nav-previous">
                                            <a href="<?php echo esc_url(get_permalink($prev_post)); ?>" class="nav-link group flex items-center p-4 bg-secondary-50 dark:bg-secondary-800 rounded-lg hover:bg-secondary-100 dark:hover:bg-secondary-700 transition-colors duration-200">
                                                <div class="nav-icon mr-4 text-primary-600 dark:text-primary-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                    </svg>
                                                </div>
                                                <div class="nav-content">
                                                    <div class="nav-subtitle text-sm text-secondary-600 dark:text-secondary-400">
                                                        <?php esc_html_e('Previous Post', 'aqualuxe'); ?>
                                                    </div>
                                                    <div class="nav-title font-medium text-secondary-900 dark:text-secondary-100 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200">
                                                        <?php echo esc_html(get_the_title($prev_post)); ?>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($next_post) : ?>
                                        <div class="nav-next <?php echo !$prev_post ? 'md:col-start-2' : ''; ?>">
                                            <a href="<?php echo esc_url(get_permalink($next_post)); ?>" class="nav-link group flex items-center p-4 bg-secondary-50 dark:bg-secondary-800 rounded-lg hover:bg-secondary-100 dark:hover:bg-secondary-700 transition-colors duration-200">
                                                <div class="nav-content text-right flex-1">
                                                    <div class="nav-subtitle text-sm text-secondary-600 dark:text-secondary-400">
                                                        <?php esc_html_e('Next Post', 'aqualuxe'); ?>
                                                    </div>
                                                    <div class="nav-title font-medium text-secondary-900 dark:text-secondary-100 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200">
                                                        <?php echo esc_html(get_the_title($next_post)); ?>
                                                    </div>
                                                </div>
                                                <div class="nav-icon ml-4 text-primary-600 dark:text-primary-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </nav>
                        <?php endif; ?>
                        
                        <!-- Comments -->
                        <?php
                        if (comments_open() || get_comments_number()) :
                            comments_template();
                        endif;
                        ?>
                    </div>
                    
                    <!-- Sidebar -->
                    <aside class="sidebar lg:col-span-1" role="complementary">
                        <?php get_sidebar(); ?>
                    </aside>
                </div>
            </div>
            
        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer();