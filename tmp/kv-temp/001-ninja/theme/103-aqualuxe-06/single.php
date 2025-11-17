<?php
/**
 * Single post template
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container mx-auto px-4 py-8">
        
        <?php while (have_posts()) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-post max-w-4xl mx-auto'); ?>>
                
                <?php if (function_exists('aqualuxe_get_breadcrumb')) : ?>
                    <div class="breadcrumb-wrapper mb-6">
                        <?php echo aqualuxe_get_breadcrumb(); ?>
                    </div>
                <?php endif; ?>
                
                <header class="post-header mb-8 text-center">
                    <h1 class="post-title text-4xl lg:text-5xl font-bold text-neutral-800 dark:text-neutral-100 mb-4">
                        <?php the_title(); ?>
                    </h1>
                    
                    <div class="post-meta flex items-center justify-center space-x-6 text-neutral-600 dark:text-neutral-300">
                        <time datetime="<?php echo get_the_date('c'); ?>" class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <?php echo get_the_date(); ?>
                        </time>
                        
                        <div class="author flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="hover:text-primary-600 transition-colors">
                                <?php the_author(); ?>
                            </a>
                        </div>
                        
                        <?php if (function_exists('aqualuxe_get_reading_time')) : ?>
                            <div class="reading-time flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <?php echo aqualuxe_get_reading_time(); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (has_category()) : ?>
                            <div class="categories flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <?php the_category(', '); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </header>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail mb-8">
                        <figure class="featured-image aspect-video overflow-hidden rounded-lg bg-neutral-100 dark:bg-neutral-700">
                            <?php
                            the_post_thumbnail('aqualuxe-blog-large', [
                                'class' => 'w-full h-full object-cover',
                                'alt'   => get_the_title(),
                            ]);
                            ?>
                            <?php if (get_the_post_thumbnail_caption()) : ?>
                                <figcaption class="text-sm text-neutral-600 dark:text-neutral-300 mt-2 text-center italic">
                                    <?php echo get_the_post_thumbnail_caption(); ?>
                                </figcaption>
                            <?php endif; ?>
                        </figure>
                    </div>
                <?php endif; ?>
                
                <div class="post-content prose prose-lg max-w-none dark:prose-invert mx-auto">
                    <?php the_content(); ?>
                    
                    <?php
                    wp_link_pages([
                        'before' => '<div class="page-links text-center mt-8"><span class="page-links-title">' . esc_html__('Pages:', 'aqualuxe') . '</span>',
                        'after'  => '</div>',
                        'link_before' => '<span class="page-number">',
                        'link_after'  => '</span>',
                    ]);
                    ?>
                </div>
                
                <?php if (has_tag()) : ?>
                    <footer class="post-footer mt-12 pt-8 border-t border-neutral-200 dark:border-neutral-700">
                        <div class="post-tags">
                            <h3 class="text-lg font-semibold mb-4"><?php esc_html_e('Tags', 'aqualuxe'); ?></h3>
                            <div class="tag-list flex flex-wrap gap-2">
                                <?php
                                $tags = get_the_tags();
                                if ($tags) {
                                    foreach ($tags as $tag) {
                                        echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="tag inline-block px-3 py-1 text-sm bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 rounded-full hover:bg-primary-100 dark:hover:bg-primary-900 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">';
                                        echo esc_html($tag->name);
                                        echo '</a>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        
                        <?php if (function_exists('aqualuxe_get_social_share')) : ?>
                            <div class="social-share-wrapper mt-8">
                                <?php echo aqualuxe_get_social_share(); ?>
                            </div>
                        <?php endif; ?>
                    </footer>
                <?php endif; ?>
                
            </article>
            
            <?php if (function_exists('aqualuxe_get_post_navigation')) : ?>
                <?php echo aqualuxe_get_post_navigation(); ?>
            <?php endif; ?>
            
            <?php if (function_exists('aqualuxe_get_related_posts')) : ?>
                <?php echo aqualuxe_get_related_posts(); ?>
            <?php endif; ?>
            
            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            ?>
            
        <?php endwhile; ?>
        
    </div>
</main>

<?php get_footer(); ?>