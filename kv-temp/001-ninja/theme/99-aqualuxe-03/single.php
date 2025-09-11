<?php
/**
 * Template for displaying single posts
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php while (have_posts()) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <?php if (has_post_thumbnail()) : ?>
                <div class="post-thumbnail">
                    <?php the_post_thumbnail('aqualuxe-featured'); ?>
                </div>
            <?php endif; ?>

            <header class="entry-header">
                <div class="container">
                    <?php
                    if (is_singular()) :
                        the_title('<h1 class="entry-title">', '</h1>');
                    else :
                        the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                    endif;

                    if ('post' === get_post_type()) :
                        ?>
                        <div class="entry-meta">
                            <?php
                            aqualuxe_posted_on();
                            aqualuxe_posted_by();
                            ?>
                        </div><!-- .entry-meta -->
                    <?php endif; ?>
                </div>
            </header><!-- .entry-header -->

            <div class="entry-content">
                <div class="container">
                    <?php
                    the_content(sprintf(
                        wp_kses(
                            /* translators: %s: Name of current post. Only visible to screen readers */
                            __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe'),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        wp_kses_post(get_the_title())
                    ));

                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>
            </div><!-- .entry-content -->

            <footer class="entry-footer">
                <div class="container">
                    <?php aqualuxe_entry_footer(); ?>
                </div>
            </footer><!-- .entry-footer -->

        </article><!-- #post-<?php the_ID(); ?> -->

        <?php
        the_post_navigation(array(
            'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
            'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
        ));

        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()) :
            ?>
            <div class="container">
                <?php comments_template(); ?>
            </div>
            <?php
        endif;
        ?>

    <?php endwhile; // End of the loop. ?>

</main><!-- #primary -->

<?php
get_sidebar();
get_footer();