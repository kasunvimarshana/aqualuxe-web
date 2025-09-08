<?php
/**
 * Single Post Template
 *
 * Displays individual blog posts with full content, meta information,
 * navigation, and comments. Implements semantic HTML5, accessibility
 * features, and schema.org markup.
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<main id="main" class="site-main" role="main" aria-label="<?php esc_attr_e('Main content', 'aqualuxe'); ?>">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('single-post max-w-4xl mx-auto px-4 py-8'); ?> itemscope itemtype="https://schema.org/Article">
            
            <!-- Post Header -->
            <header class="post-header mb-8">
                
                <!-- Breadcrumbs -->
                <nav class="breadcrumbs mb-6" aria-label="<?php esc_attr_e('Breadcrumb navigation', 'aqualuxe'); ?>">
                    <ol class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400" itemscope itemtype="https://schema.org/BreadcrumbList">
                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200" itemprop="item">
                                <span itemprop="name"><?php esc_html_e('Home', 'aqualuxe'); ?></span>
                            </a>
                            <meta itemprop="position" content="1">
                        </li>
                        <li class="before:content-['›'] before:mx-2 before:text-gray-400" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200" itemprop="item">
                                <span itemprop="name"><?php esc_html_e('Blog', 'aqualuxe'); ?></span>
                            </a>
                            <meta itemprop="position" content="2">
                        </li>
                        <li class="before:content-['›'] before:mx-2 before:text-gray-400" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <span class="text-gray-900 dark:text-white" itemprop="name"><?php the_title(); ?></span>
                            <meta itemprop="position" content="3">
                        </li>
                    </ol>
                </nav>
                
                <!-- Post Title -->
                <h1 class="post-title text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-6 leading-tight" itemprop="headline">
                    <?php the_title(); ?>
                </h1>
                
                <!-- Post Meta -->
                <div class="post-meta flex flex-wrap items-center gap-6 text-sm text-gray-600 dark:text-gray-400 mb-6">
                    
                    <!-- Author -->
                    <div class="author-meta flex items-center" itemprop="author" itemscope itemtype="https://schema.org/Person">
                        <?php echo get_avatar(get_the_author_meta('ID'), 32, '', '', ['class' => 'rounded-full mr-2']); ?>
                        <span>
                            <?php esc_html_e('By', 'aqualuxe'); ?> 
                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="font-medium hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200" itemprop="name">
                                <?php the_author(); ?>
                            </a>
                        </span>
                    </div>
                    
                    <!-- Published Date -->
                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" class="published-date flex items-center" itemprop="datePublished">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="sr-only"><?php esc_html_e('Published on', 'aqualuxe'); ?></span>
                        <?php echo esc_html(get_the_date()); ?>
                    </time>
                    
                    <!-- Modified Date (if different) -->
                    <?php if (get_the_modified_date() !== get_the_date()) : ?>
                        <time datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>" class="modified-date flex items-center" itemprop="dateModified">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span class="sr-only"><?php esc_html_e('Last updated on', 'aqualuxe'); ?></span>
                            <?php printf(esc_html__('Updated %s', 'aqualuxe'), get_the_modified_date()); ?>
                        </time>
                    <?php endif; ?>
                    
                    <!-- Reading Time -->
                    <div class="reading-time flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <?php 
                        $word_count = str_word_count(strip_tags(get_the_content()));
                        $reading_time = ceil($word_count / 200);
                        printf(esc_html(_n('%d minute read', '%d minutes read', $reading_time, 'aqualuxe')), $reading_time);
                        ?>
                    </div>
                    
                    <!-- Categories -->
                    <?php if (has_category()) : ?>
                        <div class="categories flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <?php the_category(', '); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Comments Count -->
                    <?php if (comments_open() || get_comments_number()) : ?>
                        <div class="comments-count flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <a href="#comments" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                                <?php comments_number(__('No Comments', 'aqualuxe'), __('1 Comment', 'aqualuxe'), __('% Comments', 'aqualuxe')); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                </div>
                
                <!-- Featured Image -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail mb-8">
                        <figure class="relative overflow-hidden rounded-lg">
                            <?php 
                            the_post_thumbnail('aqualuxe-hero', [
                                'class' => 'w-full h-auto object-cover',
                                'alt' => get_the_title(),
                                'itemprop' => 'image'
                            ]); 
                            ?>
                            <?php if (get_post_thumbnail_caption()) : ?>
                                <figcaption class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-4 text-sm">
                                    <?php echo wp_kses_post(get_post_thumbnail_caption()); ?>
                                </figcaption>
                            <?php endif; ?>
                        </figure>
                    </div>
                <?php endif; ?>
                
            </header>
            
            <!-- Post Content -->
            <div class="post-content prose prose-lg max-w-none dark:prose-invert prose-blue" itemprop="articleBody">
                <?php
                the_content();
                
                wp_link_pages([
                    'before' => '<div class="page-links mt-8 p-4 bg-gray-100 dark:bg-gray-800 rounded-lg"><span class="page-links-title font-semibold block mb-2">' . __('Pages:', 'aqualuxe') . '</span>',
                    'after' => '</div>',
                    'link_before' => '<span class="page-number inline-block px-3 py-1 mr-2 mb-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded hover:bg-blue-600 hover:text-white transition-colors duration-200">',
                    'link_after' => '</span>',
                    'next_or_number' => 'number',
                    'separator' => ''
                ]);
                ?>
            </div>
            
            <!-- Post Footer -->
            <footer class="post-footer mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                
                <!-- Tags -->
                <?php if (has_tag()) : ?>
                    <div class="post-tags mb-6">
                        <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">
                            <?php esc_html_e('Tags:', 'aqualuxe'); ?>
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <?php the_tags('', '', ''); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Author Bio -->
                <?php if (get_the_author_meta('description')) : ?>
                    <div class="author-bio bg-gray-50 dark:bg-gray-800 rounded-lg p-6 mb-8" itemprop="author" itemscope itemtype="https://schema.org/Person">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <?php echo get_avatar(get_the_author_meta('ID'), 80, '', '', ['class' => 'rounded-full']); ?>
                            </div>
                            <div class="flex-grow">
                                <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white" itemprop="name">
                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                                        <?php the_author(); ?>
                                    </a>
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-3" itemprop="description">
                                    <?php echo wp_kses_post(get_the_author_meta('description')); ?>
                                </p>
                                <div class="author-links flex space-x-4">
                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium transition-colors duration-200">
                                        <?php esc_html_e('View all posts', 'aqualuxe'); ?>
                                    </a>
                                    <?php if (get_the_author_meta('url')) : ?>
                                        <a href="<?php echo esc_url(get_the_author_meta('url')); ?>" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium transition-colors duration-200" target="_blank" rel="noopener" itemprop="url">
                                            <?php esc_html_e('Website', 'aqualuxe'); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Share Buttons -->
                <div class="social-share mb-8">
                    <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">
                        <?php esc_html_e('Share this post:', 'aqualuxe'); ?>
                    </h3>
                    <div class="flex space-x-3">
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200 text-sm font-medium" aria-label="<?php esc_attr_e('Share on Facebook', 'aqualuxe'); ?>">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <?php esc_html_e('Facebook', 'aqualuxe'); ?>
                        </a>
                        
                        <!-- Twitter -->
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener" class="inline-flex items-center px-4 py-2 bg-blue-400 text-white rounded-md hover:bg-blue-500 transition-colors duration-200 text-sm font-medium" aria-label="<?php esc_attr_e('Share on Twitter', 'aqualuxe'); ?>">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                            <?php esc_html_e('Twitter', 'aqualuxe'); ?>
                        </a>
                        
                        <!-- LinkedIn -->
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="inline-flex items-center px-4 py-2 bg-blue-700 text-white rounded-md hover:bg-blue-800 transition-colors duration-200 text-sm font-medium" aria-label="<?php esc_attr_e('Share on LinkedIn', 'aqualuxe'); ?>">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                            <?php esc_html_e('LinkedIn', 'aqualuxe'); ?>
                        </a>
                        
                        <!-- Copy Link -->
                        <button class="copy-link inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors duration-200 text-sm font-medium" data-url="<?php echo esc_url(get_permalink()); ?>" aria-label="<?php esc_attr_e('Copy link', 'aqualuxe'); ?>">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <?php esc_html_e('Copy Link', 'aqualuxe'); ?>
                        </button>
                    </div>
                </div>
                
            </footer>
            
            <!-- Schema.org JSON-LD -->
            <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Article",
                "headline": "<?php echo esc_js(get_the_title()); ?>",
                "description": "<?php echo esc_js(get_the_excerpt()); ?>",
                "image": "<?php echo esc_js(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>",
                "author": {
                    "@type": "Person",
                    "name": "<?php echo esc_js(get_the_author()); ?>",
                    "url": "<?php echo esc_js(get_author_posts_url(get_the_author_meta('ID'))); ?>"
                },
                "publisher": {
                    "@type": "Organization",
                    "name": "<?php echo esc_js(get_bloginfo('name')); ?>",
                    "url": "<?php echo esc_js(home_url()); ?>"
                },
                "datePublished": "<?php echo esc_js(get_the_date('c')); ?>",
                "dateModified": "<?php echo esc_js(get_the_modified_date('c')); ?>",
                "mainEntityOfPage": {
                    "@type": "WebPage",
                    "@id": "<?php echo esc_js(get_permalink()); ?>"
                }
            }
            </script>
            
        </article>
        
        <!-- Post Navigation -->
        <nav class="post-navigation max-w-4xl mx-auto px-4 py-8" aria-label="<?php esc_attr_e('Post navigation', 'aqualuxe'); ?>">
            <div class="flex justify-between items-center">
                
                <?php
                $prev_post = get_previous_post();
                $next_post = get_next_post();
                ?>
                
                <!-- Previous Post -->
                <?php if ($prev_post) : ?>
                    <div class="nav-previous flex-1 mr-4">
                        <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" class="group block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                <span class="text-sm text-gray-500 dark:text-gray-400 uppercase tracking-wide font-medium">
                                    <?php esc_html_e('Previous Post', 'aqualuxe'); ?>
                                </span>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                <?php echo esc_html(get_the_title($prev_post->ID)); ?>
                            </h4>
                        </a>
                    </div>
                <?php endif; ?>
                
                <!-- Next Post -->
                <?php if ($next_post) : ?>
                    <div class="nav-next flex-1 ml-4">
                        <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="group block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border border-gray-200 dark:border-gray-700 text-right">
                            <div class="flex items-center justify-end mb-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400 uppercase tracking-wide font-medium">
                                    <?php esc_html_e('Next Post', 'aqualuxe'); ?>
                                </span>
                                <svg class="w-5 h-5 ml-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                <?php echo esc_html(get_the_title($next_post->ID)); ?>
                            </h4>
                        </a>
                    </div>
                <?php endif; ?>
                
            </div>
        </nav>
        
        <!-- Related Posts -->
        <?php
        $related_posts = get_posts([
            'post_type' => 'post',
            'numberposts' => 3,
            'post__not_in' => [get_the_ID()],
            'category__in' => wp_get_post_categories(get_the_ID()),
            'orderby' => 'rand'
        ]);
        
        if ($related_posts) : ?>
            <section class="related-posts max-w-6xl mx-auto px-4 py-8" aria-labelledby="related-posts-heading">
                <h2 id="related-posts-heading" class="text-2xl font-bold text-gray-900 dark:text-white mb-8 text-center">
                    <?php esc_html_e('Related Posts', 'aqualuxe'); ?>
                </h2>
                
                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <?php foreach ($related_posts as $related_post) : ?>
                        <article class="related-post bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            
                            <?php if (has_post_thumbnail($related_post->ID)) : ?>
                                <div class="post-thumbnail">
                                    <a href="<?php echo esc_url(get_permalink($related_post->ID)); ?>">
                                        <?php echo get_the_post_thumbnail($related_post->ID, 'aqualuxe-gallery', ['class' => 'w-full h-48 object-cover']); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="post-content p-6">
                                <h3 class="post-title text-lg font-semibold mb-2">
                                    <a href="<?php echo esc_url(get_permalink($related_post->ID)); ?>" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                                        <?php echo esc_html(get_the_title($related_post->ID)); ?>
                                    </a>
                                </h3>
                                
                                <div class="post-meta text-sm text-gray-600 dark:text-gray-400 mb-3">
                                    <time datetime="<?php echo esc_attr(get_the_date('c', $related_post->ID)); ?>">
                                        <?php echo esc_html(get_the_date('', $related_post->ID)); ?>
                                    </time>
                                </div>
                                
                                <div class="post-excerpt text-gray-700 dark:text-gray-300">
                                    <?php echo wp_trim_words(get_the_excerpt($related_post->ID), 20); ?>
                                </div>
                            </div>
                            
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
        
        <!-- Comments Section -->
        <?php
        if (comments_open() || get_comments_number()) {
            comments_template();
        }
        ?>
        
    <?php endwhile; ?>
    
</main>

<?php
get_sidebar();
get_footer();
