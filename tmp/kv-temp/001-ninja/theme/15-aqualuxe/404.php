<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package AquaLuxe
 */

get_header();
?>

<div class="container mx-auto px-4 py-16">
    <section class="error-404 not-found max-w-3xl mx-auto text-center">
        <header class="page-header mb-8">
            <h1 class="page-title text-4xl md:text-5xl font-bold mb-4"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'aqualuxe'); ?></h1>
            <div class="error-image my-8">
                <img src="<?php echo esc_url(get_theme_mod('aqualuxe_404_image', get_template_directory_uri() . '/assets/images/404-fish.svg')); ?>" alt="<?php esc_attr_e('404 Error', 'aqualuxe'); ?>" class="max-w-xs mx-auto">
            </div>
            <p class="text-lg text-gray-600 dark:text-gray-400"><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe'); ?></p>
        </header><!-- .page-header -->

        <div class="page-content">
            <div class="search-form-container max-w-md mx-auto mb-8">
                <?php get_search_form(); ?>
            </div>

            <div class="error-actions flex flex-wrap justify-center gap-4 mb-12">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-primary inline-block px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-md transition-colors">
                    <?php esc_html_e('Back to Home', 'aqualuxe'); ?>
                </a>
                
                <?php if (class_exists('WooCommerce')) : ?>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn-secondary inline-block px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white rounded-md transition-colors">
                    <?php esc_html_e('Browse Shop', 'aqualuxe'); ?>
                </a>
                <?php endif; ?>
            </div>

            <div class="error-suggestions grid md:grid-cols-2 gap-8 max-w-2xl mx-auto text-left">
                <div class="widget">
                    <h2 class="text-xl font-bold mb-4"><?php esc_html_e('Recent Posts', 'aqualuxe'); ?></h2>
                    <ul class="space-y-2">
                        <?php
                        $recent_posts = wp_get_recent_posts(array(
                            'numberposts' => 5,
                            'post_status' => 'publish'
                        ));
                        
                        foreach ($recent_posts as $post) :
                        ?>
                            <li>
                                <a href="<?php echo esc_url(get_permalink($post['ID'])); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                                    <?php echo esc_html($post['post_title']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="widget">
                    <h2 class="text-xl font-bold mb-4"><?php esc_html_e('Most Used Categories', 'aqualuxe'); ?></h2>
                    <ul class="space-y-2">
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
</div>

<?php
get_footer();