<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">

    <section class="error-404 not-found">
        <div class="container">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'aqualuxe'); ?></h1>
            </header><!-- .page-header -->

            <div class="page-content">
                <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe'); ?></p>

                <?php get_search_form(); ?>

                <div class="error-404-widgets">
                    <div class="widget-area-404">
                        <?php
                        the_widget('WP_Widget_Recent_Posts', array(
                            'title' => esc_html__('Recent Posts', 'aqualuxe'),
                            'number' => 5,
                        ));
                        ?>
                    </div>

                    <div class="widget-area-404">
                        <?php
                        // Only show the widget if site has multiple categories.
                        if (aqualuxe_categorized_blog()) :
                            ?>
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
                            <?php
                        endif;
                        ?>
                    </div>

                    <?php if (aqualuxe_is_woocommerce_active()) : ?>
                        <div class="widget-area-404">
                            <div class="widget">
                                <h2 class="widget-title"><?php esc_html_e('Popular Products', 'aqualuxe'); ?></h2>
                                <?php
                                $args = array(
                                    'post_type' => 'product',
                                    'posts_per_page' => 4,
                                    'meta_key' => 'total_sales',
                                    'orderby' => 'meta_value_num',
                                    'order' => 'DESC',
                                );
                                
                                $popular_products = new WP_Query($args);
                                
                                if ($popular_products->have_posts()) :
                                    echo '<ul>';
                                    while ($popular_products->have_posts()) :
                                        $popular_products->the_post();
                                        echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                                    endwhile;
                                    echo '</ul>';
                                    wp_reset_postdata();
                                endif;
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="widget-area-404">
                        <?php
                        /* translators: %1$s: smiley */
                        $archive_content = '<p>' . sprintf(esc_html__('Try looking in the monthly archives. %1$s', 'aqualuxe'), convert_smilies(':)')) . '</p>';
                        the_widget('WP_Widget_Archives', array(
                            'title' => esc_html__('Archives', 'aqualuxe'),
                        ), array(
                            'after_widget' => '</section>' . $archive_content,
                        ));
                        ?>
                    </div>

                    <div class="widget-area-404">
                        <?php
                        the_widget('WP_Widget_Tag_Cloud', array(
                            'title' => esc_html__('Tags', 'aqualuxe'),
                        ));
                        ?>
                    </div>
                </div><!-- .error-404-widgets -->

            </div><!-- .page-content -->
        </div>
    </section><!-- .error-404 -->

</main><!-- #primary -->

<?php
get_footer();