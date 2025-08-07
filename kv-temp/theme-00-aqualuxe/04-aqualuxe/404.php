<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <section class="error-404 not-found">
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'aqualuxe'); ?></h1>
                </header><!-- .page-header -->

                <div class="page-content">
                    <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'aqualuxe'); ?></p>

                    <?php
                    get_search_form();
                    
                    // Display recently published posts
                    $recent_posts = get_posts(array(
                        'numberposts' => 5,
                        'post_status' => 'publish',
                    ));
                    
                    if ($recent_posts) :
                        ?>
                        <div class="widget widget_recent_entries">
                            <h2 class="widget-title"><?php esc_html_e('Recent Posts', 'aqualuxe'); ?></h2>
                            <ul>
                                <?php
                                foreach ($recent_posts as $post) :
                                    setup_postdata($post);
                                    ?>
                                    <li>
                                        <a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
                                    </li>
                                    <?php
                                endforeach;
                                wp_reset_postdata();
                                ?>
                            </ul>
                        </div>
                        <?php
                    endif;
                    ?>
                </div><!-- .page-content -->
            </section><!-- .error-404 -->

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();