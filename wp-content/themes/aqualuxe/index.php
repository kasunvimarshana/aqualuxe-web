<?php
/**
 * The main template file
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php if (have_posts()) : ?>

        <?php if (is_home() && !is_front_page()) : ?>
            <header class="page-header">
                <div class="container">
                    <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                </div>
            </header>
        <?php endif; ?>

        <div class="container">
            <div class="content-area">
                <div class="posts-container">
                    <?php
                    /* Start the Loop */
                    while (have_posts()) :
                        the_post();

                        /*
                         * Include the Post-Type-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                         */
                        get_template_part('templates/content', get_post_type());

                    endwhile;
                    ?>
                </div>

                <?php
                // Pagination
                the_posts_navigation(array(
                    'prev_text' => esc_html__('Older posts', 'aqualuxe'),
                    'next_text' => esc_html__('Newer posts', 'aqualuxe'),
                ));
                ?>
            </div>

            <?php get_sidebar(); ?>
        </div>

    <?php else : ?>

        <div class="container">
            <div class="content-area">
                <?php get_template_part('templates/content', 'none'); ?>
            </div>
        </div>

    <?php endif; ?>

</main><!-- #primary -->

<?php
get_footer();