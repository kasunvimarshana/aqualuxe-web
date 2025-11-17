<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main col-md-9">

    <?php if (have_posts()) : ?>

        <header class="page-header">
            <h1 class="page-title">
                <?php
                /* translators: %s: search query. */
                printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
                ?>
            </h1>
        </header><!-- .page-header -->

        <?php
        /* Start the Loop */
        while (have_posts()) :
            the_post();

            /**
             * Run the loop for the search to output the results.
             * If you want to overwrite this in a child theme then include a file
             * called content-search.php and that will be used instead.
             */
            get_template_part('template-parts/content', 'search');

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