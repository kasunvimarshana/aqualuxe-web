<?php
/**
 * The main template file
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main" role="main">
    <div class="container mx-auto px-4 py-8">
        <?php if (have_posts()) : ?>
            
            <?php if (is_home() && !is_front_page()) : ?>
                <header class="page-header mb-8">
                    <h1 class="page-title text-3xl font-bold text-primary-900 dark:text-primary-100">
                        <?php single_post_title(); ?>
                    </h1>
                </header>
            <?php endif; ?>

            <div class="posts-container grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300'); ?>>
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                    <?php 
                                    the_post_thumbnail('aqualuxe-featured', array(
                                        'class' => 'w-full h-48 object-cover',
                                        'alt' => the_title_attribute(array('echo' => false))
                                    )); 
                                    ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="post-content p-6">
                            <header class="entry-header mb-4">
                                <?php
                                if (is_singular()) :
                                    the_title('<h1 class="entry-title text-2xl font-bold text-gray-900 dark:text-white">', '</h1>');
                                else :
                                    the_title('<h2 class="entry-title text-xl font-bold text-gray-900 dark:text-white mb-2"><a href="' . esc_url(get_permalink()) . '" rel="bookmark" class="hover:text-primary-600 transition-colors">', '</a></h2>');
                                endif;

                                if ('post' === get_post_type()) :
                                ?>
                                    <div class="entry-meta text-sm text-gray-600 dark:text-gray-400">
                                        <?php
                                        aqualuxe_posted_on();
                                        aqualuxe_posted_by();
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </header>

                            <div class="entry-content text-gray-700 dark:text-gray-300">
                                <?php
                                if (is_singular()) :
                                    the_content(sprintf(
                                        wp_kses(
                                            __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe'),
                                            array(
                                                'span' => array(
                                                    'class' => array(),
                                                ),
                                            )
                                        ),
                                        get_the_title()
                                    ));

                                    wp_link_pages(array(
                                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                                        'after'  => '</div>',
                                    ));
                                else :
                                    the_excerpt();
                                ?>
                                    <a href="<?php the_permalink(); ?>" class="read-more inline-block mt-3 text-primary-600 hover:text-primary-800 font-medium transition-colors">
                                        <?php esc_html_e('Read More', 'aqualuxe'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <?php if (!is_singular() && 'post' === get_post_type()) : ?>
                                <footer class="entry-footer mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                                    <?php aqualuxe_entry_footer(); ?>
                                </footer>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php
            // Pagination
            the_posts_navigation(array(
                'prev_text' => esc_html__('Older posts', 'aqualuxe'),
                'next_text' => esc_html__('Newer posts', 'aqualuxe'),
            ));
            ?>

        <?php else : ?>
            
            <section class="no-results not-found text-center py-16">
                <header class="page-header mb-8">
                    <h1 class="page-title text-3xl font-bold text-gray-900 dark:text-white">
                        <?php esc_html_e('Nothing here', 'aqualuxe'); ?>
                    </h1>
                </header>

                <div class="page-content">
                    <?php if (is_home() && current_user_can('publish_posts')) : ?>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            <?php
                            printf(
                                wp_kses(
                                    __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'aqualuxe'),
                                    array(
                                        'a' => array(
                                            'href' => array(),
                                        ),
                                    )
                                ),
                                esc_url(admin_url('post-new.php'))
                            );
                            ?>
                        </p>
                    <?php elseif (is_search()) : ?>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            <?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe'); ?>
                        </p>
                        <?php get_search_form(); ?>
                    <?php else : ?>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            <?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aqualuxe'); ?>
                        </p>
                        <?php get_search_form(); ?>
                    <?php endif; ?>
                </div>
            </section>

        <?php endif; ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();