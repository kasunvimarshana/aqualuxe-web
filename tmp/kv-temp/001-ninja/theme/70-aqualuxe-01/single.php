<?php get_header(); ?>

<main class="single-page">
    
    <article id="post-<?php the_ID(); ?>" <?php post_class('single-post py-16 lg:py-24'); ?>>
        
        <!-- Article Header -->
        <header class="article-header mb-12">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center">
                    
                    <!-- Category/Type -->
                    <?php if (get_post_type() === 'post') : ?>
                        <div class="post-categories mb-4" data-aos="fade-up">
                            <?php
                            $categories = get_the_category();
                            if ($categories) :
                                foreach ($categories as $category) :
                                    ?>
                                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                                       class="category-tag inline-block bg-primary-100 text-primary-600 px-4 py-2 rounded-full text-sm font-medium mr-2 mb-2 hover:bg-primary-200 transition-colors">
                                        <?php echo esc_html($category->name); ?>
                                    </a>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Title -->
                    <h1 class="post-title text-4xl lg:text-6xl font-bold font-secondary text-gray-900 mb-6" data-aos="fade-up">
                        <?php the_title(); ?>
                    </h1>
                    
                    <!-- Meta Information -->
                    <div class="post-meta flex flex-wrap items-center justify-center gap-6 text-gray-600" data-aos="fade-up" data-aos-delay="200">
                        
                        <?php if (get_post_type() === 'post') : ?>
                            <!-- Author -->
                            <div class="meta-item flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                                <span>
                                    <?php esc_html_e('By', 'aqualuxe'); ?> 
                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" 
                                       class="text-primary-600 hover:text-primary-700 font-medium">
                                        <?php the_author(); ?>
                                    </a>
                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Date -->
                        <div class="meta-item flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                <?php echo esc_html(get_the_date()); ?>
                            </time>
                        </div>
                        
                        <!-- Reading Time -->
                        <?php if (get_post_type() === 'post') : ?>
                            <div class="meta-item flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <span>
                                    <?php echo aqualuxe_reading_time(); ?> <?php esc_html_e('min read', 'aqualuxe'); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Comments Count -->
                        <?php if (comments_open() || get_comments_number()) : ?>
                            <div class="meta-item flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="#comments" class="text-primary-600 hover:text-primary-700">
                                    <?php comments_number('0 Comments', '1 Comment', '% Comments'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                    
                </div>
            </div>
        </header>

        <!-- Featured Image -->
        <?php if (has_post_thumbnail()) : ?>
            <div class="featured-image mb-12" data-aos="fade-up">
                <div class="container mx-auto px-4">
                    <div class="max-w-5xl mx-auto">
                        <div class="image-container relative rounded-2xl overflow-hidden shadow-lg">
                            <?php the_post_thumbnail('large', array(
                                'class' => 'w-full h-auto object-cover',
                                'alt' => get_the_title()
                            )); ?>
                            <div class="image-overlay absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Article Content -->
        <div class="article-content">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    
                    <div class="prose prose-lg prose-gray max-w-none" data-aos="fade-up">
                        <?php
                        the_content();
                        
                        wp_link_pages(array(
                            'before' => '<div class="page-links mt-8 p-6 bg-gray-50 rounded-lg">',
                            'after' => '</div>',
                            'link_before' => '<span class="page-number inline-block bg-primary-600 text-white px-3 py-1 rounded mr-2">',
                            'link_after' => '</span>',
                        ));
                        ?>
                    </div>
                    
                    <!-- Tags -->
                    <?php if (get_post_type() === 'post' && has_tag()) : ?>
                        <div class="post-tags mt-12 pt-8 border-t border-gray-200" data-aos="fade-up">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <?php esc_html_e('Tags:', 'aqualuxe'); ?>
                            </h3>
                            <div class="tags-list flex flex-wrap gap-2">
                                <?php
                                $tags = get_the_tags();
                                if ($tags) :
                                    foreach ($tags as $tag) :
                                        ?>
                                        <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" 
                                           class="tag inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm hover:bg-primary-100 hover:text-primary-700 transition-colors">
                                            #<?php echo esc_html($tag->name); ?>
                                        </a>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Social Sharing -->
                    <div class="social-sharing mt-12 pt-8 border-t border-gray-200" data-aos="fade-up">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <?php esc_html_e('Share this:', 'aqualuxe'); ?>
                        </h3>
                        <div class="sharing-buttons flex flex-wrap gap-4">
                            <?php
                            $post_url = urlencode(get_permalink());
                            $post_title = urlencode(get_the_title());
                            ?>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>" 
                               target="_blank" 
                               class="share-button bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <span>Facebook</span>
                            </a>
                            
                            <a href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>" 
                               target="_blank" 
                               class="share-button bg-blue-400 text-white px-4 py-2 rounded-lg hover:bg-blue-500 transition-colors flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                                <span>Twitter</span>
                            </a>
                            
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $post_url; ?>" 
                               target="_blank" 
                               class="share-button bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition-colors flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                                <span>LinkedIn</span>
                            </a>
                            
                            <button onclick="navigator.share ? navigator.share({title: '<?php echo esc_js(get_the_title()); ?>', url: '<?php echo esc_js(get_permalink()); ?>'}) : alert('Sharing not supported')" 
                                    class="share-button bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92c0-1.61-1.31-2.92-2.92-2.92z"/>
                                </svg>
                                <span>Share</span>
                            </button>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

    </article>

    <!-- Related Posts -->
    <?php if (get_post_type() === 'post') : ?>
        <section class="related-posts py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    
                    <h2 class="text-3xl lg:text-4xl font-bold font-secondary text-gray-900 mb-12 text-center" data-aos="fade-up">
                        <?php esc_html_e('Related Articles', 'aqualuxe'); ?>
                    </h2>
                    
                    <?php
                    $related_posts = aqualuxe_get_related_posts(get_the_ID(), 3);
                    if ($related_posts->have_posts()) :
                        ?>
                        <div class="related-posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <?php
                            $delay = 0;
                            while ($related_posts->have_posts()) : $related_posts->the_post();
                                ?>
                                <article class="related-post" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                                    <a href="<?php the_permalink(); ?>" class="block group">
                                        <div class="post-card bg-white rounded-2xl overflow-hidden shadow-soft hover:shadow-lg transition-all duration-300 transform group-hover:-translate-y-1">
                                            
                                            <?php if (has_post_thumbnail()) : ?>
                                                <div class="post-image relative h-48 overflow-hidden">
                                                    <?php the_post_thumbnail('medium', array(
                                                        'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300'
                                                    )); ?>
                                                    <div class="image-overlay absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="post-content p-6">
                                                <div class="post-categories mb-3">
                                                    <?php
                                                    $categories = get_the_category();
                                                    if ($categories) :
                                                        $category = $categories[0];
                                                        ?>
                                                        <span class="category-tag text-xs font-medium text-primary-600 bg-primary-100 px-2 py-1 rounded-full">
                                                            <?php echo esc_html($category->name); ?>
                                                        </span>
                                                        <?php
                                                    endif;
                                                    ?>
                                                </div>
                                                
                                                <h3 class="post-title text-lg font-semibold text-gray-900 mb-3 group-hover:text-primary-600 transition-colors line-clamp-2">
                                                    <?php the_title(); ?>
                                                </h3>
                                                
                                                <p class="post-excerpt text-gray-600 text-sm line-clamp-3 mb-4">
                                                    <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                                </p>
                                                
                                                <div class="post-meta flex items-center text-xs text-gray-500">
                                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                        <?php echo esc_html(get_the_date()); ?>
                                                    </time>
                                                    <span class="mx-2">•</span>
                                                    <span><?php echo aqualuxe_reading_time(); ?> min read</span>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </a>
                                </article>
                                <?php
                                $delay += 100;
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </div>
                        <?php
                    endif;
                    ?>
                    
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Author Bio (for posts) -->
    <?php if (get_post_type() === 'post' && get_the_author_meta('description')) : ?>
        <section class="author-bio py-16">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    
                    <div class="author-card bg-white p-8 rounded-2xl shadow-soft" data-aos="fade-up">
                        <div class="author-content flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-6">
                            
                            <div class="author-avatar flex-shrink-0">
                                <?php echo get_avatar(get_the_author_meta('ID'), 80, '', get_the_author(), array(
                                    'class' => 'w-20 h-20 rounded-full border-4 border-primary-100'
                                )); ?>
                            </div>
                            
                            <div class="author-info flex-1">
                                <h3 class="author-name text-xl font-semibold text-gray-900 mb-2">
                                    <?php esc_html_e('About', 'aqualuxe'); ?> <?php the_author(); ?>
                                </h3>
                                <p class="author-description text-gray-600 mb-4">
                                    <?php echo wp_kses_post(get_the_author_meta('description')); ?>
                                </p>
                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" 
                                   class="view-posts-link text-primary-600 hover:text-primary-700 font-medium text-sm">
                                    <?php esc_html_e('View all posts by', 'aqualuxe'); ?> <?php the_author(); ?> →
                                </a>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Comments Section -->
    <?php if (comments_open() || get_comments_number()) : ?>
        <section class="comments-section py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto" data-aos="fade-up">
                    <?php comments_template(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
