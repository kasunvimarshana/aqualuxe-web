<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="main" class="site-main flex-1" role="main">
    <div class="container mx-auto px-4 py-8 lg:py-12">
        
        <?php if (have_posts()) : ?>
            
            <!-- Archive header -->
            <?php if (is_home() && !is_front_page()) : ?>
                <header class="archive-header mb-8 lg:mb-12">
                    <h1 class="archive-title text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-secondary-100">
                        <?php single_post_title(); ?>
                    </h1>
                    <?php
                    $description = get_the_archive_description();
                    if ($description) :
                    ?>
                        <div class="archive-description mt-4 text-lg text-secondary-600 dark:text-secondary-400">
                            <?php echo wp_kses_post($description); ?>
                        </div>
                    <?php endif; ?>
                </header>
            <?php endif; ?>
            
            <div class="content-area">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
                    
                    <!-- Main content -->
                    <div class="main-content lg:col-span-2">
                        
                        <?php if (is_home() || is_archive() || is_search()) : ?>
                            <!-- Posts grid -->
                            <div class="posts-grid grid gap-8">
                                <?php
                                while (have_posts()) :
                                    the_post();
                                    get_template_part('template-parts/content/content', get_post_type());
                                endwhile;
                                ?>
                            </div>
                        <?php else : ?>
                            <!-- Single post/page content -->
                            <?php
                            while (have_posts()) :
                                the_post();
                                get_template_part('template-parts/content/content', get_post_type());
                                
                                // Comments for single posts/pages
                                if ((comments_open() || get_comments_number()) && !post_password_required()) :
                                    comments_template();
                                endif;
                            endwhile;
                            ?>
                        <?php endif; ?>
                        
                        <!-- Pagination -->
                        <?php if (is_home() || is_archive() || is_search()) : ?>
                            <nav class="pagination-nav mt-12" role="navigation" aria-label="<?php esc_attr_e('Posts navigation', 'aqualuxe'); ?>">
                                <?php
                                the_posts_pagination(array(
                                    'mid_size'  => 2,
                                    'prev_text' => '<span class="screen-reader-text">' . __('Previous page', 'aqualuxe') . '</span><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>',
                                    'next_text' => '<span class="screen-reader-text">' . __('Next page', 'aqualuxe') . '</span><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>',
                                    'class'     => 'pagination',
                                ));
                                ?>
                            </nav>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Sidebar -->
                    <aside class="sidebar lg:col-span-1" role="complementary">
                        <?php get_sidebar(); ?>
                    </aside>
                </div>
            </div>
            
        <?php else : ?>
            
            <!-- No content found -->
            <div class="no-content text-center py-12 lg:py-16">
                <div class="max-w-md mx-auto">
                    <?php if (is_search()) : ?>
                        <h1 class="text-2xl lg:text-3xl font-bold text-secondary-900 dark:text-secondary-100 mb-4">
                            <?php esc_html_e('Nothing found', 'aqualuxe'); ?>
                        </h1>
                        <p class="text-lg text-secondary-600 dark:text-secondary-400 mb-6">
                            <?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe'); ?>
                        </p>
                        <?php get_search_form(); ?>
                    <?php else : ?>
                        <h1 class="text-2xl lg:text-3xl font-bold text-secondary-900 dark:text-secondary-100 mb-4">
                            <?php esc_html_e('Nothing here', 'aqualuxe'); ?>
                        </h1>
                        <p class="text-lg text-secondary-600 dark:text-secondary-400 mb-6">
                            <?php esc_html_e("It looks like nothing was found at this location. Maybe try one of the links below or a search?", 'aqualuxe'); ?>
                        </p>
                        <div class="space-y-4">
                            <?php get_search_form(); ?>
                            <div>
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-primary">
                                    <?php esc_html_e('Go to Homepage', 'aqualuxe'); ?>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();