<?php
/**
 * The main template file
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main" role="main" aria-label="<?php esc_attr_e('Main content', 'aqualuxe'); ?>">
    <div class="container mx-auto px-4">
        
        <?php if (have_posts()) : ?>
            
            <?php if (is_home() && !is_front_page()) : ?>
                <header class="page-header mb-8">
                    <h1 class="page-title text-4xl font-heading font-bold text-center mb-4">
                        <?php single_post_title(); ?>
                    </h1>
                </header>
            <?php endif; ?>
            
            <div class="posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <?php while (have_posts()) : the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card card-aqua'); ?>>
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>" aria-label="<?php esc_attr_e('Read more about', 'aqualuxe'); ?> <?php the_title_attribute(); ?>">
                                    <?php the_post_thumbnail('aqualuxe-featured', array(
                                        'class' => 'w-full h-48 object-cover',
                                        'loading' => 'lazy'
                                    )); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-content p-6">
                            
                            <header class="entry-header mb-4">
                                
                                <?php if (!is_singular()) : ?>
                                    <h2 class="entry-title text-xl font-heading font-semibold mb-2">
                                        <a href="<?php the_permalink(); ?>" class="text-ocean-700 hover:text-aqua-500 transition-colors">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>
                                <?php else : ?>
                                    <h1 class="entry-title text-2xl font-heading font-bold mb-4">
                                        <?php the_title(); ?>
                                    </h1>
                                <?php endif; ?>
                                
                                <div class="entry-meta text-sm text-gray-600 mb-3">
                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" class="published">
                                        <?php echo esc_html(get_the_date()); ?>
                                    </time>
                                    
                                    <?php if (get_the_author()) : ?>
                                        <span class="author-meta ml-2">
                                            <?php esc_html_e('by', 'aqualuxe'); ?>
                                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="author-link text-aqua-600 hover:text-aqua-800">
                                                <?php the_author(); ?>
                                            </a>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if (has_category()) : ?>
                                        <span class="category-meta ml-2">
                                            <?php esc_html_e('in', 'aqualuxe'); ?>
                                            <?php the_category(', '); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                            </header>
                            
                            <div class="entry-summary">
                                <?php
                                if (is_singular()) {
                                    the_content();
                                    
                                    wp_link_pages(array(
                                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                                        'after'  => '</div>',
                                    ));
                                } else {
                                    the_excerpt();
                                    ?>
                                    <a href="<?php the_permalink(); ?>" class="read-more-link inline-flex items-center text-aqua-600 hover:text-aqua-800 font-medium mt-3">
                                        <?php esc_html_e('Read more', 'aqualuxe'); ?>
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                    <?php
                                }
                                ?>
                            </div>
                            
                            <?php if (has_tag() && is_singular()) : ?>
                                <footer class="entry-footer mt-6 pt-4 border-t border-gray-200">
                                    <div class="tags">
                                        <?php the_tags('<span class="tags-label font-medium">' . esc_html__('Tags:', 'aqualuxe') . '</span> ', ', '); ?>
                                    </div>
                                </footer>
                            <?php endif; ?>
                            
                        </div>
                        
                    </article>
                    
                <?php endwhile; ?>
                
            </div>
            
            <?php
            // Pagination
            the_posts_pagination(array(
                'mid_size'  => 2,
                'prev_text' => __('&laquo; Previous', 'aqualuxe'),
                'next_text' => __('Next &raquo;', 'aqualuxe'),
                'class'     => 'pagination mt-12 flex justify-center'
            ));
            ?>
            
        <?php else : ?>
            
            <section class="no-results not-found text-center py-12">
                <header class="page-header mb-8">
                    <h1 class="page-title text-3xl font-heading font-bold mb-4">
                        <?php esc_html_e('Nothing here', 'aqualuxe'); ?>
                    </h1>
                </header>
                
                <div class="page-content max-w-2xl mx-auto">
                    <?php if (is_home() && current_user_can('publish_posts')) : ?>
                        
                        <p class="mb-6">
                            <?php
                            printf(
                                wp_kses(
                                    __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'aqualuxe'),
                                    array(
                                        'a' => array(
                                            'href' => array(),
                                        ),
                                    )
                                ),
                                esc_url(admin_url('post-new.php'))
                            );
                            ?>
                        </p>
                        
                    <?php elseif (is_search()) : ?>
                        
                        <p class="mb-6">
                            <?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe'); ?>
                        </p>
                        
                        <?php get_search_form(); ?>
                        
                    <?php else : ?>
                        
                        <p class="mb-6">
                            <?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aqualuxe'); ?>
                        </p>
                        
                        <?php get_search_form(); ?>
                        
                    <?php endif; ?>
                </div>
            </section>
            
        <?php endif; ?>
        
    </div>
</main>

<?php
get_sidebar();
get_footer();
?>