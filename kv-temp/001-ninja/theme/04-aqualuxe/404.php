<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main col-md-12">

    <section class="error-404 not-found">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'aqualuxe'); ?></h1>
        </header><!-- .page-header -->

        <div class="page-content">
            <div class="error-404-content">
                <div class="error-404-image">
                    <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . '/images/404.svg'); ?>" alt="<?php esc_attr_e('404 Not Found', 'aqualuxe'); ?>">
                </div>
                
                <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe'); ?></p>

                <?php get_search_form(); ?>

                <div class="error-404-actions">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="button"><?php esc_html_e('Back to Homepage', 'aqualuxe'); ?></a>
                    
                    <?php if (class_exists('WooCommerce')) : ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="button button-secondary"><?php esc_html_e('Browse Shop', 'aqualuxe'); ?></a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="error-404-widgets">
                <div class="row">
                    <div class="col-md-4">
                        <div class="widget widget_categories">
                            <h2 class="widget-title"><?php esc_html_e('Most Used Categories', 'aqualuxe'); ?></h2>
                            <ul>
                                <?php
                                wp_list_categories(array(
                                    'orderby'    => 'count',
                                    'order'      => 'DESC',
                                    'show_count' => 1,
                                    'title_li'   => '',
                                    'number'     => 10,
                                ));
                                ?>
                            </ul>
                        </div><!-- .widget -->
                    </div>
                    
                    <div class="col-md-4">
                        <?php
                        /* translators: %1$s: smiley */
                        $aqualuxe_archive_content = '<p>' . sprintf(esc_html__('Try looking in the monthly archives. %1$s', 'aqualuxe'), convert_smilies(':)')) . '</p>';
                        the_widget('WP_Widget_Archives', 'dropdown=1', array(
                            'after_title' => '</h2>' . $aqualuxe_archive_content,
                        ));
                        ?>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="widget widget_recent_entries">
                            <h2 class="widget-title"><?php esc_html_e('Recent Posts', 'aqualuxe'); ?></h2>
                            <ul>
                                <?php
                                $recent_posts = wp_get_recent_posts(array(
                                    'numberposts' => 5,
                                    'post_status' => 'publish',
                                ));
                                
                                foreach ($recent_posts as $post) {
                                    echo '<li><a href="' . esc_url(get_permalink($post['ID'])) . '">' . esc_html($post['post_title']) . '</a></li>';
                                }
                                
                                wp_reset_postdata();
                                ?>
                            </ul>
                        </div><!-- .widget -->
                    </div>
                </div>
            </div>
        </div><!-- .page-content -->
    </section><!-- .error-404 -->

</main><!-- #primary -->

<?php
get_footer();