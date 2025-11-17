<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main py-12">
    <div class="container-fluid">
        <div class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-2/3 lg:pr-8">
                <?php
                while (have_posts()) :
                    the_post();

                    // Breadcrumbs
                    if (function_exists('aqualuxe_breadcrumbs')) :
                        aqualuxe_breadcrumbs();
                    endif;
                    ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none overflow-hidden'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <?php the_post_thumbnail('aqualuxe-featured', ['class' => 'w-full h-auto']); ?>
                            </div>
                        <?php endif; ?>

                        <div class="entry-content p-6 md:p-8">
                            <header class="entry-header mb-6">
                                <?php the_title('<h1 class="entry-title text-3xl md:text-4xl font-bold mb-4">', '</h1>'); ?>
                                
                                <div class="entry-meta text-sm text-gray-600 dark:text-gray-400 flex flex-wrap items-center">
                                    <!-- Author -->
                                    <span class="post-author flex items-center mr-4 mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <?php
                                        printf(
                                            /* translators: %s: post author. */
                                            esc_html_x('By %s', 'post author', 'aqualuxe'),
                                            '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" class="text-primary hover:text-primary-dark transition-colors duration-300">' . esc_html(get_the_author()) . '</a>'
                                        );
                                        ?>
                                    </span>

                                    <!-- Date -->
                                    <span class="post-date flex items-center mr-4 mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <a href="<?php echo esc_url(get_permalink()); ?>" class="hover:text-primary transition-colors duration-300">
                                            <?php echo esc_html(get_the_date()); ?>
                                        </a>
                                    </span>

                                    <!-- Categories -->
                                    <?php if (has_category()) : ?>
                                        <span class="post-categories flex items-center mr-4 mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                            <?php the_category(', '); ?>
                                        </span>
                                    <?php endif; ?>

                                    <!-- Comments -->
                                    <?php if (comments_open()) : ?>
                                        <span class="post-comments flex items-center mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            <?php
                                            comments_popup_link(
                                                esc_html__('No Comments', 'aqualuxe'),
                                                esc_html__('1 Comment', 'aqualuxe'),
                                                esc_html__('% Comments', 'aqualuxe'),
                                                'hover:text-primary transition-colors duration-300'
                                            );
                                            ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </header>

                            <div class="prose prose-lg dark:prose-invert max-w-none">
                                <?php
                                the_content(
                                    sprintf(
                                        wp_kses(
                                            /* translators: %s: Name of current post. Only visible to screen readers */
                                            __('Continue reading<span class="sr-only"> "%s"</span>', 'aqualuxe'),
                                            array(
                                                'span' => array(
                                                    'class' => array(),
                                                ),
                                            )
                                        ),
                                        wp_kses_post(get_the_title())
                                    )
                                );

                                wp_link_pages(
                                    array(
                                        'before' => '<div class="page-links mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">' . esc_html__('Pages:', 'aqualuxe'),
                                        'after'  => '</div>',
                                    )
                                );
                                ?>
                            </div>

                            <footer class="entry-footer mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <?php if (has_tag()) : ?>
                                    <div class="post-tags flex flex-wrap items-center">
                                        <span class="mr-2 text-gray-600 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                        </span>
                                        <?php the_tags('', ', ', ''); ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Post Navigation -->
                                <div class="post-navigation mt-8">
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <div class="prev-post mb-4 sm:mb-0">
                                            <?php
                                            $prev_post = get_previous_post();
                                            if (!empty($prev_post)) :
                                                ?>
                                                <span class="block text-sm text-gray-600 dark:text-gray-400 mb-1"><?php esc_html_e('Previous Post', 'aqualuxe'); ?></span>
                                                <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" class="text-primary hover:text-primary-dark transition-colors duration-300 font-medium">
                                                    <?php echo esc_html(get_the_title($prev_post->ID)); ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>

                                        <div class="next-post text-right">
                                            <?php
                                            $next_post = get_next_post();
                                            if (!empty($next_post)) :
                                                ?>
                                                <span class="block text-sm text-gray-600 dark:text-gray-400 mb-1"><?php esc_html_e('Next Post', 'aqualuxe'); ?></span>
                                                <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="text-primary hover:text-primary-dark transition-colors duration-300 font-medium">
                                                    <?php echo esc_html(get_the_title($next_post->ID)); ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </footer>
                        </div>
                    </article><!-- #post-<?php the_ID(); ?> -->

                    <?php
                    // Author bio
                    if (get_theme_mod('aqualuxe_show_author_bio', true) && get_the_author_meta('description')) :
                        get_template_part('template-parts/biography');
                    endif;

                    // Related posts
                    if (get_theme_mod('aqualuxe_show_related_posts', true)) :
                        get_template_part('template-parts/related-posts');
                    endif;

                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;

                endwhile; // End of the loop.
                ?>
            </div>

            <div class="w-full lg:w-1/3 mt-8 lg:mt-0">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</main><!-- #main -->

<?php
get_footer();