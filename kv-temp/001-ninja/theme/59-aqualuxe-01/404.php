<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <section class="error-404 not-found">
            <div class="error-404__content">
                <header class="error-404__header">
                    <h1 class="error-404__title"><?php esc_html_e('404', 'aqualuxe'); ?></h1>
                    <h2 class="error-404__subtitle"><?php esc_html_e('Page Not Found', 'aqualuxe'); ?></h2>
                </header>

                <div class="error-404__text">
                    <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'aqualuxe'); ?></p>
                    
                    <?php get_search_form(); ?>
                </div>

                <div class="error-404__actions">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="button button-primary">
                        <?php esc_html_e('Back to Home', 'aqualuxe'); ?>
                    </a>
                </div>
            </div>

            <div class="error-404__suggestions">
                <div class="error-404__recent-posts">
                    <h3><?php esc_html_e('Recent Posts', 'aqualuxe'); ?></h3>
                    <ul>
                        <?php
                        $recent_posts = wp_get_recent_posts([
                            'numberposts' => 5,
                            'post_status' => 'publish',
                        ]);
                        
                        foreach ($recent_posts as $post) {
                            echo '<li><a href="' . esc_url(get_permalink($post['ID'])) . '">' . esc_html($post['post_title']) . '</a></li>';
                        }
                        wp_reset_postdata();
                        ?>
                    </ul>
                </div>

                <div class="error-404__categories">
                    <h3><?php esc_html_e('Most Used Categories', 'aqualuxe'); ?></h3>
                    <ul>
                        <?php
                        wp_list_categories([
                            'orderby'    => 'count',
                            'order'      => 'DESC',
                            'show_count' => 1,
                            'title_li'   => '',
                            'number'     => 5,
                        ]);
                        ?>
                    </ul>
                </div>
            </div>
        </section>
    </div>
</main><!-- #primary -->

<?php
get_footer();