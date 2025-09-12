<?php
/**
 * Single Post Template
 * 
 * @package AquaLuxe
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container mx-auto px-4 py-8">
        
        <?php while (have_posts()) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('max-w-4xl mx-auto'); ?>>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="featured-image mb-8">
                        <?php the_post_thumbnail('aqualuxe-hero', [
                            'class' => 'w-full h-auto rounded-lg shadow-lg',
                            'alt' => get_the_title()
                        ]); ?>
                    </div>
                <?php endif; ?>
                
                <header class="entry-header mb-8">
                    <h1 class="entry-title text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        <?php the_title(); ?>
                    </h1>
                    
                    <div class="entry-meta flex flex-wrap items-center text-sm text-gray-600 dark:text-gray-400 space-x-4 mb-6">
                        <div class="meta-item flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            <time datetime="<?php echo get_the_date('c'); ?>" class="published">
                                <?php echo get_the_date(); ?>
                            </time>
                        </div>
                        
                        <div class="meta-item flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="author">
                                <?php esc_html_e('By', 'aqualuxe'); ?> 
                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="hover:text-primary-600">
                                    <?php the_author(); ?>
                                </a>
                            </span>
                        </div>
                        
                        <?php if (has_category()) : ?>
                            <div class="meta-item flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="categories">
                                    <?php the_category(', '); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="meta-item flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="reading-time">
                                <?php echo esc_html(aqualuxe_get_reading_time()); ?>
                            </span>
                        </div>
                    </div>
                    
                    <?php aqualuxe_get_template_part('breadcrumbs'); ?>
                </header>
                
                <div class="entry-content prose prose-lg dark:prose-invert max-w-none mb-8">
                    <?php
                    the_content();
                    
                    wp_link_pages([
                        'before' => '<div class="page-links mt-8 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg"><span class="page-links-title font-semibold text-gray-900 dark:text-white">' . esc_html__('Pages:', 'aqualuxe') . '</span>',
                        'after' => '</div>',
                        'link_before' => '<span class="page-link">',
                        'link_after' => '</span>',
                    ]);
                    ?>
                </div>
                
                <footer class="entry-footer">
                    <?php if (has_tag()) : ?>
                        <div class="entry-tags mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                                <?php esc_html_e('Tags:', 'aqualuxe'); ?>
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <?php
                                $tags = get_the_tags();
                                if ($tags) {
                                    foreach ($tags as $tag) {
                                        echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="tag inline-block px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-full text-sm hover:bg-primary-100 hover:text-primary-700 transition-colors">' . esc_html($tag->name) . '</a>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="entry-share bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <?php esc_html_e('Share this post:', 'aqualuxe'); ?>
                        </h3>
                        <div class="flex space-x-4">
                            <?php
                            $post_url = urlencode(get_permalink());
                            $post_title = urlencode(get_the_title());
                            ?>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>" target="_blank" class="share-button facebook" rel="noopener">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>" target="_blank" class="share-button twitter" rel="noopener">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $post_url; ?>" target="_blank" class="share-button linkedin" rel="noopener">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://pinterest.com/pin/create/button/?url=<?php echo $post_url; ?>&description=<?php echo $post_title; ?>" target="_blank" class="share-button pinterest" rel="noopener">
                                <i class="fab fa-pinterest-p"></i>
                            </a>
                        </div>
                    </div>
                </footer>
                
            </article>
            
            <?php
            // Author bio
            if (get_the_author_meta('description')) :
                aqualuxe_get_template_part('author-bio');
            endif;
            
            // Navigation between posts
            the_post_navigation([
                'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
            ]);
            
            // Related posts
            aqualuxe_get_template_part('related-posts');
            
            // Comments
            if (comments_open() || get_comments_number()) :
                echo '<div class="comments-section mt-12 pt-12 border-t border-gray-200 dark:border-gray-700">';
                comments_template();
                echo '</div>';
            endif;
            ?>
            
        <?php endwhile; ?>
        
    </div>
</main>

<?php get_footer();

