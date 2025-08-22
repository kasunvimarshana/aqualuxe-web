<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
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

    <section class="error-404 not-found">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'aqualuxe'); ?></h1>
        </header><!-- .page-header -->

        <div class="page-content">
            <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe'); ?></p>

            <?php get_search_form(); ?>

            <div class="error-404-widgets">
                <div class="widget widget_recent_entries">
                    <h2 class="widget-title"><?php esc_html_e('Recent Posts', 'aqualuxe'); ?></h2>
                    <ul>
                        <?php
                        wp_get_archives(array(
                            'type'    => 'postbypost',
                            'limit'   => 5,
                        ));
                        ?>
                    </ul>
                </div>

                <div class="widget widget_categories">
                    <h2 class="widget-title"><?php esc_html_e('Most Used Categories', 'aqualuxe'); ?></h2>
                    <ul>
                        <?php
                        wp_list_categories(array(
                            'orderby'    => 'count',
                            'order'      => 'DESC',
                            'show_count' => 1,
                            'title_li'   => '',
                            'number'     => 5,
                        ));
                        ?>
                    </ul>
                </div>
            </div>
        </div><!-- .page-content -->
    </section><!-- .error-404 -->

</main><!-- #primary -->

<?php
get_footer();