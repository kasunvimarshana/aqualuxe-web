<?php
/**
 * The main template file
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<div class="<?php echo esc_attr(aqualuxe_get_container_classes()); ?>">
    <div class="flex flex-wrap -mx-4">
        <div class="<?php echo esc_attr(aqualuxe_get_content_classes()); ?>">
            
            <?php echo aqualuxe_get_breadcrumbs(); ?>
            
            <?php if (have_posts()) : ?>
                
                <?php if (is_home() && !is_front_page()) : ?>
                    <header class="page-header mb-8">
                        <h1 class="page-title text-3xl font-bold"><?php single_post_title(); ?></h1>
                    </header>
                <?php endif; ?>

                <div class="posts-container">
                    <?php while (have_posts()) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('post-item mb-8 pb-8 border-b border-gray-200 last:border-b-0'); ?>>
                            
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-thumbnail mb-4">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('large', ['class' => 'w-full h-64 object-cover rounded-lg']); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <header class="entry-header mb-4">
                                <?php aqualuxe_post_categories(); ?>
                                
                                <h2 class="entry-title text-2xl font-bold mb-2">
                                    <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                                
                                <?php aqualuxe_post_meta(); ?>
                            </header>
                            
                            <div class="entry-content prose max-w-none">
                                <?php
                                if (is_singular()) {
                                    the_content();
                                    
                                    wp_link_pages([
                                        'before' => '<div class="page-links">',
                                        'after'  => '</div>',
                                    ]);
                                } else {
                                    the_excerpt();
                                }
                                ?>
                            </div>
                            
                            <?php if (is_singular()) : ?>
                                <footer class="entry-footer mt-6">
                                    <?php aqualuxe_post_tags(); ?>
                                    <?php aqualuxe_social_share(); ?>
                                </footer>
                            <?php else : ?>
                                <footer class="entry-footer mt-4">
                                    <a href="<?php the_permalink(); ?>" class="read-more inline-block bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark transition-colors">
                                        <?php esc_html_e('Read More', 'aqualuxe'); ?>
                                    </a>
                                </footer>
                            <?php endif; ?>
                            
                        </article>
                    <?php endwhile; ?>
                </div>

                <?php
                if (!is_singular()) {
                    aqualuxe_pagination();
                }
                ?>
                
                <?php
                if (is_singular()) {
                    aqualuxe_author_bio();
                    aqualuxe_related_posts();
                    
                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) {
                        comments_template();
                    }
                }
                ?>

            <?php else : ?>
                
                <div class="no-posts-found text-center py-12">
                    <h1 class="text-2xl font-bold mb-4"><?php esc_html_e('Nothing here', 'aqualuxe'); ?></h1>
                    <p class="text-gray-600 mb-6"><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'aqualuxe'); ?></p>
                    <?php aqualuxe_search_form(); ?>
                </div>

            <?php endif; ?>
            
        </div>

        <?php if (aqualuxe_has_sidebar()) : ?>
            <aside class="<?php echo esc_attr(aqualuxe_get_sidebar_classes()); ?>">
                <?php
                if (aqualuxe_is_woocommerce_active() && (is_shop() || is_product_category() || is_product_tag())) {
                    dynamic_sidebar('sidebar-shop');
                } else {
                    dynamic_sidebar('sidebar-1');
                }
                ?>
            </aside>
        <?php endif; ?>
        
    </div>
</div>

<?php
get_footer();
