<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main col-md-9">

    <?php if (have_posts()) : ?>

        <header class="page-header">
            <?php
            the_archive_title('<h1 class="page-title">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
            ?>
        </header><!-- .page-header -->

        <?php
        /* Start the Loop */
        while (have_posts()) :
            the_post();

            /*
             * Include the Post-Type-specific template for the content.
             * If you want to override this in a child theme, then include a file
             * called content-___.php (where ___ is the Post Type name) and that will be used instead.
             */
            get_template_part('template-parts/content', get_post_type());

        endwhile;

        the_posts_pagination(array(
            'prev_text' => '<i class="fas fa-chevron-left"></i> ' . esc_html__('Previous', 'aqualuxe'),
            'next_text' => esc_html__('Next', 'aqualuxe') . ' <i class="fas fa-chevron-right"></i>',
        ));

    else :

        get_template_part('template-parts/content', 'none');

    endif;
    ?>

</main><!-- #primary -->

<?php
/**
 * Functions hooked into aqualuxe_sidebar action
 *
 * @hooked aqualuxe_get_sidebar - 10
 */
do_action('aqualuxe_sidebar');

get_footer();