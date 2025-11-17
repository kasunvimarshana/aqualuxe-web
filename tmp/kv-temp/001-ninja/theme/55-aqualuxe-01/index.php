<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
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
    <?php
    if (have_posts()) :
        ?>
        <header class="page-header">
            <?php
            if (is_home() && !is_front_page()) :
                ?>
                <h1 class="page-title"><?php single_post_title(); ?></h1>
                <?php
            else :
                ?>
                <h1 class="page-title"><?php esc_html_e('Latest Posts', 'aqualuxe'); ?></h1>
                <?php
            endif;
            ?>
        </header><!-- .page-header -->

        <div class="posts-grid">
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