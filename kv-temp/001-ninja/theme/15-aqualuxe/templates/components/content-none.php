<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<section class="no-results not-found bg-white dark:bg-gray-700 p-8 rounded-lg shadow-md">
    <header class="page-header mb-6">
        <h1 class="page-title text-2xl md:text-3xl font-bold"><?php esc_html_e('Nothing Found', 'aqualuxe'); ?></h1>
    </header><!-- .page-header -->

    <div class="page-content prose dark:prose-invert max-w-none">
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
            
            <div class="mt-6">
                <?php get_search_form(); ?>
            </div>

            <div class="mt-8">
                <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Try These Popular Topics', 'aqualuxe'); ?></h3>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $categories = get_categories(array(
                        'orderby' => 'count',
                        'order' => 'DESC',
                        'number' => 6,
                    ));
                    
                    foreach ($categories as $category) {
                        echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="px-4 py-2 bg-gray-100 dark:bg-gray-600 hover:bg-primary-100 dark:hover:bg-primary-900 rounded-full">' . esc_html($category->name) . '</a>';
                    }
                    ?>
                </div>
            </div>

            <?php if (class_exists('WooCommerce')) : ?>
            <div class="mt-8">
                <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Popular Products', 'aqualuxe'); ?></h3>
                <?php
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 3,
                    'meta_key' => 'total_sales',
                    'orderby' => 'meta_value_num',
                );
                $popular_products = new WP_Query($args);
                
                if ($popular_products->have_posts()) :
                ?>
                    <div class="grid md:grid-cols-3 gap-6">
                        <?php while ($popular_products->have_posts()) : $popular_products->the_post(); ?>
                            <div class="product-item bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>" class="block mb-3">
                                        <?php the_post_thumbnail('thumbnail', array('class' => 'w-full h-auto rounded')); ?>
                                    </a>
                                <?php endif; ?>
                                <h4 class="font-medium mb-2">
                                    <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                                        <?php the_title(); ?>
                                    </a>
                                </h4>
                                <?php if (function_exists('wc_get_product')) : 
                                    $product = wc_get_product(get_the_ID());
                                    if ($product) :
                                ?>
                                    <span class="text-primary-600 dark:text-primary-400 font-medium">
                                        <?php echo wp_kses_post($product->get_price_html()); ?>
                                    </span>
                                <?php 
                                    endif;
                                endif; 
                                ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php
                    wp_reset_postdata();
                endif;
                ?>
            </div>
            <?php endif; ?>

        <?php else : ?>

            <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aqualuxe'); ?></p>
            
            <div class="mt-6">
                <?php get_search_form(); ?>
            </div>

            <div class="mt-8">
                <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Recent Posts', 'aqualuxe'); ?></h3>
                <?php
                $recent_posts = wp_get_recent_posts(array(
                    'numberposts' => 5,
                    'post_status' => 'publish'
                ));
                
                if (!empty($recent_posts)) :
                ?>
                    <ul class="list-disc pl-5 space-y-2">
                        <?php foreach ($recent_posts as $post) : ?>
                            <li>
                                <a href="<?php echo esc_url(get_permalink($post['ID'])); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                                    <?php echo esc_html($post['post_title']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php
                    wp_reset_postdata();
                endif;
                ?>
            </div>

        <?php endif; ?>
    </div><!-- .page-content -->
</section><!-- .no-results -->