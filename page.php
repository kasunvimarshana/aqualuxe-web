<?php
/**
 * Page Template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-8">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
                <header class="entry-header mb-8">
                    <?php if (!is_front_page()) : ?>
                        <?php aqualuxe_breadcrumbs(); ?>
                    <?php endif; ?>
                    
                    <?php if (!is_front_page()) : ?>
                        <h1 class="entry-title text-4xl font-bold text-gray-900 mt-6"><?php the_title(); ?></h1>
                    <?php endif; ?>
                </header>

                <?php if (has_post_thumbnail() && !is_front_page()) : ?>
                    <div class="entry-thumbnail mb-8">
                        <?php the_post_thumbnail('full', array('class' => 'w-full h-64 md:h-96 object-cover rounded-lg shadow-lg')); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content prose prose-lg max-w-none">
                    <?php
                    the_content();

                    wp_link_pages(
                        array(
                            'before' => '<div class="page-links flex items-center space-x-2 mt-8 pt-8 border-t border-gray-200"><span class="page-links-title font-medium text-gray-900">' . __('Pages:', 'aqualuxe') . '</span>',
                            'after'  => '</div>',
                            'link_before' => '<span class="page-link">',
                            'link_after' => '</span>',
                        )
                    );
                    ?>
                </div>

                <?php if (get_edit_post_link()) : ?>
                    <footer class="entry-footer mt-8 pt-8 border-t border-gray-200">
                        <?php
                        edit_post_link(
                            sprintf(
                                wp_kses(
                                    /* translators: %s: Post title. Only visible to screen readers. */
                                    __('Edit <span class="screen-reader-text">%s</span>', 'aqualuxe'),
                                    array(
                                        'span' => array(
                                            'class' => array(),
                                        ),
                                    )
                                ),
                                wp_kses_post(get_the_title())
                            ),
                            '<span class="edit-link btn btn-outline-primary btn-sm">',
                            '</span>'
                        );
                        ?>
                    </footer>
                <?php endif; ?>
            </article>

            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            ?>

        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>