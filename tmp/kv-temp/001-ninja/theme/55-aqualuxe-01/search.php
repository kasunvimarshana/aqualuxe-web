<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main">

    <?php if (have_posts()) : ?>

        <header class="page-header">
            <h1 class="page-title">
                <?php
                /* translators: %s: search query. */
                printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
                ?>
            </h1>
        </header><!-- .page-header -->

        <div class="posts-grid">
            <?php
            /* Start the Loop */
            while (have_posts()) :
                the_post();

                /**
                 * Run the loop for the search to output the results.
                 * If you want to overload this in a child theme then include a file
                 * called content-search.php and that will be used instead.
                 */
                get_template_part('templates/content', 'search');

            endwhile;
            ?>
        </div><!-- .posts-grid -->

        <?php
        the_posts_pagination(array(
            'prev_text' => '<span class="screen-reader-text">' . esc_html__('Previous page', 'aqualuxe') . '</span>',
            'next_text' => '<span class="screen-reader-text">' . esc_html__('Next page', 'aqualuxe') . '</span>',
            'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__('Page', 'aqualuxe') . ' </span>',
        ));

    else :

        get_template_part('templates/content', 'none');

    endif;
    ?>

</main><!-- #primary -->

<?php
get_sidebar();
get_footer();