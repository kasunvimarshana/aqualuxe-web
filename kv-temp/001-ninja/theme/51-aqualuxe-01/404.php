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

<div class="container">
    <div class="row no-sidebar">
        <main id="primary" class="site-main">
            <section class="error-404 not-found">
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'aqualuxe'); ?></h1>
                </header><!-- .page-header -->

                <div class="page-content">
                    <div class="error-404-content">
                        <div class="error-404-image">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="128" height="128"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path></svg>
                        </div>
                        
                        <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe'); ?></p>

                        <?php get_search_form(); ?>

                        <div class="error-404-actions">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="button button-primary"><?php esc_html_e('Back to Homepage', 'aqualuxe'); ?></a>
                            
                            <?php if (aqualuxe_is_woocommerce_active()) : ?>
                                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="button"><?php esc_html_e('Browse Shop', 'aqualuxe'); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="error-404-widgets">
                        <div class="error-404-widget">
                            <h2><?php esc_html_e('Most Used Categories', 'aqualuxe'); ?></h2>
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
                        </div>

                        <?php if (aqualuxe_is_woocommerce_active()) : ?>
                            <div class="error-404-widget">
                                <h2><?php esc_html_e('Popular Product Categories', 'aqualuxe'); ?></h2>
                                <ul>
                                    <?php
                                    $product_categories = get_terms(array(
                                        'taxonomy' => 'product_cat',
                                        'orderby' => 'count',
                                        'order' => 'DESC',
                                        'number' => 10,
                                        'hide_empty' => true,
                                    ));

                                    if (!empty($product_categories) && !is_wp_error($product_categories)) {
                                        foreach ($product_categories as $category) {
                                            echo '<li><a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a></li>';
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <div class="error-404-widget">
                            <h2><?php esc_html_e('Recent Posts', 'aqualuxe'); ?></h2>
                            <ul>
                                <?php
                                $recent_posts = wp_get_recent_posts(array(
                                    'numberposts' => 10,
                                    'post_status' => 'publish',
                                ));
                                
                                foreach ($recent_posts as $post) {
                                    echo '<li><a href="' . esc_url(get_permalink($post['ID'])) . '">' . esc_html($post['post_title']) . '</a></li>';
                                }
                                
                                wp_reset_postdata();
                                ?>
                            </ul>
                        </div>
                    </div>
                </div><!-- .page-content -->
            </section><!-- .error-404 -->
        </main><!-- #main -->
    </div>
</div>

<?php
get_footer();