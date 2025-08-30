<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e('Nothing Found', 'aqualuxe'); ?></h1>
    </header><!-- .page-header -->

    <div class="page-content">
        <?php
        if (is_home() && current_user_can('publish_posts')) :
            printf(
                '<p>' . wp_kses(
                    /* translators: 1: link to WP admin new post page. */
                    __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'aqualuxe'),
                    array(
                        'a' => array(
                            'href' => array(),
                        ),
                    )
                ) . '</p>',
                esc_url(admin_url('post-new.php'))
            );

        elseif (is_search()) :
            ?>
            <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe'); ?></p>
            <div class="search-form-container">
                <?php get_search_form(); ?>
            </div>

            <?php if (function_exists('aqualuxe_popular_searches')) : ?>
                <div class="popular-searches">
                    <h3><?php esc_html_e('Popular Searches', 'aqualuxe'); ?></h3>
                    <?php aqualuxe_popular_searches(); ?>
                </div>
            <?php endif; ?>

        <?php elseif (is_archive()) : ?>
            <p><?php esc_html_e('It seems we can\'t find what you\'re looking for in this archive. Perhaps searching can help.', 'aqualuxe'); ?></p>
            <div class="search-form-container">
                <?php get_search_form(); ?>
            </div>

        <?php else : ?>
            <p><?php esc_html_e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'aqualuxe'); ?></p>
            <div class="search-form-container">
                <?php get_search_form(); ?>
            </div>

            <div class="no-results-suggestions">
                <h3><?php esc_html_e('You might also be interested in:', 'aqualuxe'); ?></h3>
                
                <div class="suggestion-columns">
                    <div class="suggestion-column">
                        <h4><?php esc_html_e('Recent Posts', 'aqualuxe'); ?></h4>
                        <ul>
                            <?php
                            $recent_posts = wp_get_recent_posts(array(
                                'numberposts' => 5,
                                'post_status' => 'publish',
                            ));
                            
                            foreach ($recent_posts as $post) {
                                echo '<li><a href="' . esc_url(get_permalink($post['ID'])) . '">' . $post['post_title'] . '</a></li>';
                            }
                            wp_reset_postdata();
                            ?>
                        </ul>
                    </div>

                    <div class="suggestion-column">
                        <h4><?php esc_html_e('Popular Categories', 'aqualuxe'); ?></h4>
                        <ul>
                            <?php
                            $categories = get_categories(array(
                                'orderby' => 'count',
                                'order' => 'DESC',
                                'number' => 5,
                            ));
                            
                            foreach ($categories as $category) {
                                echo '<li><a href="' . esc_url(get_category_link($category->term_id)) . '">' . $category->name . '</a></li>';
                            }
                            ?>
                        </ul>
                    </div>

                    <?php if (class_exists('WooCommerce')) : ?>
                        <div class="suggestion-column">
                            <h4><?php esc_html_e('Popular Products', 'aqualuxe'); ?></h4>
                            <ul>
                                <?php
                                $popular_products = new WP_Query(array(
                                    'post_type' => 'product',
                                    'posts_per_page' => 5,
                                    'meta_key' => 'total_sales',
                                    'orderby' => 'meta_value_num',
                                    'order' => 'DESC',
                                ));

                                if ($popular_products->have_posts()) :
                                    while ($popular_products->have_posts()) : $popular_products->the_post();
                                        echo '<li><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></li>';
                                    endwhile;
                                    wp_reset_postdata();
                                endif;
                                ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div><!-- .page-content -->
</section><!-- .no-results -->