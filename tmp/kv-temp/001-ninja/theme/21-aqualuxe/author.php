<?php
/**
 * The template for displaying author pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();

$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
?>

<main id="primary" class="site-main">

    <header class="author-header bg-primary-600 dark:bg-primary-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-center">
                <div class="author-avatar mb-6 md:mb-0 md:mr-8">
                    <?php echo get_avatar($curauth->ID, 120, '', '', array('class' => 'rounded-full border-4 border-white')); ?>
                </div>
                <div class="author-info">
                    <h1 class="author-title text-3xl md:text-4xl font-display font-bold mb-2">
                        <?php echo esc_html($curauth->display_name); ?>
                    </h1>
                    <?php if ($curauth->description) : ?>
                        <div class="author-bio text-white/80 max-w-2xl mb-4">
                            <?php echo wpautop($curauth->description); ?>
                        </div>
                    <?php endif; ?>
                    <div class="author-meta flex flex-wrap items-center text-sm text-white/80">
                        <?php if ($curauth->user_url) : ?>
                            <a href="<?php echo esc_url($curauth->user_url); ?>" class="flex items-center mr-6 hover:text-white transition-colors" target="_blank" rel="noopener noreferrer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                </svg>
                                <?php echo esc_html__('Website', 'aqualuxe'); ?>
                            </a>
                        <?php endif; ?>
                        <span class="flex items-center mr-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <?php
                            printf(
                                /* translators: %s: number of posts */
                                _n(
                                    '%s post',
                                    '%s posts',
                                    count_user_posts($curauth->ID),
                                    'aqualuxe'
                                ),
                                number_format_i18n(count_user_posts($curauth->ID))
                            );
                            ?>
                        </span>
                        <?php if ($curauth->user_email && current_user_can('manage_options')) : ?>
                            <a href="mailto:<?php echo esc_attr($curauth->user_email); ?>" class="flex items-center hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                                <?php echo esc_html__('Email', 'aqualuxe'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header><!-- .author-header -->

    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 <?php echo is_active_sidebar('sidebar-1') ? 'lg:grid-cols-3 xl:grid-cols-4 gap-8' : ''; ?>">
            <div class="<?php echo is_active_sidebar('sidebar-1') ? 'lg:col-span-2 xl:col-span-3' : ''; ?>">
                <?php if (have_posts()) : ?>
                    <header class="archive-header mb-8">
                        <h2 class="archive-title text-2xl font-bold text-dark-800 dark:text-white">
                            <?php
                            /* translators: %s: author name */
                            printf(esc_html__('Posts by %s', 'aqualuxe'), esc_html($curauth->display_name));
                            ?>
                        </h2>
                    </header><!-- .archive-header -->

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <?php
                        /* Start the Loop */
                        while (have_posts()) :
                            the_post();

                            /*
                             * Include the Post-Type-specific template for the content.
                             * If you want to override this in a child theme, then include a file
                             * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                             */
                            get_template_part('templates/parts/content', get_post_type());

                        endwhile;
                        ?>
                    </div>

                    <div class="pagination-container mt-12">
                        <?php
                        the_posts_pagination(array(
                            'mid_size'  => 2,
                            'prev_text' => sprintf(
                                '<span class="nav-prev-text">%s</span> %s',
                                __('Previous', 'aqualuxe'),
                                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block w-4 h-4"><path d="M15 18l-6-6 6-6"/></svg>'
                            ),
                            'next_text' => sprintf(
                                '<span class="nav-next-text">%s</span> %s',
                                __('Next', 'aqualuxe'),
                                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block w-4 h-4"><path d="M9 18l6-6-6-6"/></svg>'
                            ),
                        ));
                        ?>
                    </div>

                <?php else : ?>

                    <div class="no-results bg-white dark:bg-dark-700 rounded-xl shadow-soft p-8 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-primary-500 dark:text-primary-400 mx-auto mb-6">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        
                        <h2 class="text-2xl font-bold text-dark-800 dark:text-white mb-4">
                            <?php esc_html_e('No Posts Found', 'aqualuxe'); ?>
                        </h2>
                        
                        <div class="text-dark-600 dark:text-dark-200 mb-6">
                            <p><?php esc_html_e('It seems this author has not published any posts yet.', 'aqualuxe'); ?></p>
                        </div>
                        
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                            <?php esc_html_e('Back to Homepage', 'aqualuxe'); ?>
                        </a>
                    </div>

                <?php endif; ?>
            </div>

            <?php if (is_active_sidebar('sidebar-1')) : ?>
                <div class="sidebar-container">
                    <?php get_sidebar(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</main><!-- #main -->

<?php
get_footer();