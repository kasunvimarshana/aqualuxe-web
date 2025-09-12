<?php
/**
 * No content template
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */
?>

<section class="no-content text-center py-16">
    <div class="max-w-md mx-auto">
        
        <div class="mb-8">
            <svg class="w-24 h-24 mx-auto text-neutral-300 dark:text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0118 12a8 8 0 01-2.238 5.541c-.054-.035-.115-.068-.18-.101a4.916 4.916 0 00-1.061-.46A6.99 6.99 0 0016 12a7 7 0 10-7 7c.386 0 .765-.025 1.137-.073a4.916 4.916 0 00.46 1.061c.033.065.066.126.101.18A7.962 7.962 0 0112 18a8 8 0 110-8z"></path>
            </svg>
        </div>
        
        <header class="mb-6">
            <h1 class="text-3xl font-bold text-neutral-800 dark:text-neutral-100 mb-4">
                <?php if (is_home() && current_user_can('publish_posts')) : ?>
                    <?php esc_html_e('Ready to publish your first post?', 'aqualuxe'); ?>
                <?php elseif (is_search()) : ?>
                    <?php
                    /* translators: %s: search query */
                    printf(esc_html__('Nothing found for "%s"', 'aqualuxe'), '<span class="text-primary-600">' . get_search_query() . '</span>');
                    ?>
                <?php else : ?>
                    <?php esc_html_e('Nothing here', 'aqualuxe'); ?>
                <?php endif; ?>
            </h1>
            
            <div class="text-neutral-600 dark:text-neutral-300">
                <?php if (is_home() && current_user_can('publish_posts')) : ?>
                    <p class="mb-4">
                        <?php esc_html_e('Get started by creating your first post. You can always edit or delete it later.', 'aqualuxe'); ?>
                    </p>
                    <a href="<?php echo esc_url(admin_url('post-new.php')); ?>" class="btn btn-primary">
                        <?php esc_html_e('Create First Post', 'aqualuxe'); ?>
                    </a>
                <?php elseif (is_search()) : ?>
                    <p class="mb-6">
                        <?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe'); ?>
                    </p>
                    
                    <div class="search-form max-w-sm mx-auto">
                        <?php get_search_form(); ?>
                    </div>
                    
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4"><?php esc_html_e('Suggestions:', 'aqualuxe'); ?></h3>
                        <ul class="text-left space-y-2">
                            <li><?php esc_html_e('Make sure all words are spelled correctly', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Try different keywords', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Try more general keywords', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Try fewer keywords', 'aqualuxe'); ?></li>
                        </ul>
                    </div>
                    
                <?php else : ?>
                    <p class="mb-6">
                        <?php esc_html_e('It looks like nothing was found at this location. Maybe try a search or one of the links below?', 'aqualuxe'); ?>
                    </p>
                    
                    <div class="actions space-y-4">
                        <div>
                            <?php get_search_form(); ?>
                        </div>
                        
                        <div>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                                <?php esc_html_e('Back to Home', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </header>
        
        <?php if (!is_search() && !is_home()) : ?>
            <div class="popular-content mt-12">
                <h3 class="text-xl font-semibold mb-6"><?php esc_html_e('Popular Content', 'aqualuxe'); ?></h3>
                
                <?php
                $popular_posts = new WP_Query([
                    'post_type'      => 'post',
                    'posts_per_page' => 3,
                    'meta_key'       => 'post_views_count',
                    'orderby'        => 'meta_value_num',
                    'order'          => 'DESC',
                ]);
                
                if ($popular_posts->have_posts()) :
                ?>
                    <div class="space-y-4">
                        <?php while ($popular_posts->have_posts()) : $popular_posts->the_post(); ?>
                            <article class="text-left">
                                <h4 class="font-medium">
                                    <a href="<?php the_permalink(); ?>" class="text-primary-600 hover:text-primary-700">
                                        <?php the_title(); ?>
                                    </a>
                                </h4>
                                <div class="text-sm text-neutral-500 mt-1">
                                    <?php echo get_the_date(); ?>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                <?php
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        <?php endif; ?>
        
    </div>
</section>