<?php
/**
 * Template part for displaying single posts
 *
 * @package AquaLuxe
 * @version 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?> itemscope itemtype="https://schema.org/BlogPosting">
    
    <!-- Featured Image -->
    <?php if (has_post_thumbnail()): ?>
        <div class="post-featured-image mb-8" itemprop="image">
            <div class="relative rounded-2xl overflow-hidden">
                <?php
                the_post_thumbnail('aqualuxe-hero', [
                    'class' => 'w-full h-96 object-cover',
                    'loading' => 'eager',
                    'itemprop' => 'image'
                ]);
                ?>
                
                <!-- Image Overlay with Post Meta -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent">
                    <div class="absolute bottom-6 left-6 right-6">
                        <div class="flex flex-wrap items-center gap-4 text-white">
                            <!-- Categories -->
                            <?php
                            $categories = get_the_category();
                            if ($categories):
                            ?>
                                <div class="categories">
                                    <?php foreach (array_slice($categories, 0, 2) as $category): ?>
                                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                                           class="inline-block bg-primary-600 text-white px-3 py-1 rounded-full text-sm mr-2 hover:bg-primary-700 transition-colors">
                                            <?php echo esc_html($category->name); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Reading Time -->
                            <div class="reading-time flex items-center text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <?php echo aqualuxe_get_reading_time(); ?> <?php esc_html_e('min read', 'aqualuxe'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Post Header -->
    <header class="entry-header mb-8">
        
        <!-- Title -->
        <h1 class="entry-title text-4xl md:text-5xl font-bold mb-6 leading-tight" itemprop="headline">
            <?php the_title(); ?>
        </h1>
        
        <!-- Post Meta -->
        <div class="entry-meta flex flex-wrap items-center gap-6 text-gray-600 dark:text-gray-400 mb-6">
            
            <!-- Author -->
            <div class="author-meta flex items-center" itemprop="author" itemscope itemtype="https://schema.org/Person">
                <div class="author-avatar mr-3">
                    <?php echo get_avatar(get_the_author_meta('ID'), 48, '', get_the_author(), ['class' => 'rounded-full', 'itemprop' => 'image']); ?>
                </div>
                <div>
                    <div class="author-name font-medium">
                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" 
                           class="hover:text-primary-600 transition-colors" itemprop="name">
                            <?php echo esc_html(get_the_author()); ?>
                        </a>
                    </div>
                    <div class="author-role text-sm">
                        <?php echo esc_html(get_the_author_meta('description') ? wp_trim_words(get_the_author_meta('description'), 8) : __('Author', 'aqualuxe')); ?>
                    </div>
                </div>
            </div>
            
            <!-- Date -->
            <div class="date-meta flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                    <?php echo esc_html(get_the_date()); ?>
                </time>
                <?php if (get_the_modified_date() !== get_the_date()): ?>
                    <span class="mx-2">•</span>
                    <span class="text-sm">
                        <?php esc_html_e('Updated', 'aqualuxe'); ?>
                        <time datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>" itemprop="dateModified">
                            <?php echo esc_html(get_the_modified_date()); ?>
                        </time>
                    </span>
                <?php endif; ?>
            </div>
            
            <!-- Comments Count -->
            <?php if (comments_open() || get_comments_number()): ?>
                <div class="comments-meta flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <a href="#comments" class="hover:text-primary-600 transition-colors">
                        <?php
                        $comments_number = get_comments_number();
                        if ($comments_number == 0) {
                            esc_html_e('No Comments', 'aqualuxe');
                        } else {
                            printf(
                                esc_html(_n('%s Comment', '%s Comments', $comments_number, 'aqualuxe')),
                                number_format_i18n($comments_number)
                            );
                        }
                        ?>
                    </a>
                </div>
            <?php endif; ?>
            
            <!-- Post Views -->
            <?php $post_views = get_post_meta(get_the_ID(), 'post_views_count', true); ?>
            <?php if ($post_views): ?>
                <div class="views-meta flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <?php echo number_format_i18n($post_views); ?> <?php esc_html_e('views', 'aqualuxe'); ?>
                </div>
            <?php endif; ?>
            
        </div>
        
        <!-- Post Excerpt/Summary -->
        <?php if (has_excerpt()): ?>
            <div class="entry-summary text-xl text-gray-600 dark:text-gray-400 leading-relaxed mb-8 p-6 bg-gray-50 dark:bg-dark-800 rounded-lg border-l-4 border-primary-500" itemprop="description">
                <?php the_excerpt(); ?>
            </div>
        <?php endif; ?>
        
    </header><!-- .entry-header -->
    
    <!-- Post Content -->
    <div class="entry-content prose prose-lg max-w-none" itemprop="articleBody">
        <?php
        the_content(sprintf(
            wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                __('Continue reading<span class="sr-only"> "%s"</span>', 'aqualuxe'),
                [
                    'span' => [
                        'class' => [],
                    ],
                ]
            ),
            get_the_title()
        ));
        
        wp_link_pages([
            'before' => '<div class="page-links mt-8 p-4 bg-gray-50 dark:bg-dark-800 rounded-lg"><span class="page-links-title font-semibold mr-4">' . esc_html__('Pages:', 'aqualuxe') . '</span>',
            'after'  => '</div>',
            'link_before' => '<span class="page-number">',
            'link_after' => '</span>',
        ]);
        ?>
    </div><!-- .entry-content -->
    
    <!-- Post Footer -->
    <footer class="entry-footer mt-12 pt-8 border-t border-gray-200 dark:border-dark-700">
        
        <!-- Tags -->
        <?php
        $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'aqualuxe'));
        if ($tags_list):
        ?>
            <div class="post-tags mb-6">
                <h4 class="text-lg font-semibold mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <?php esc_html_e('Tags', 'aqualuxe'); ?>
                </h4>
                <div class="tag-list flex flex-wrap gap-2">
                    <?php
                    $tags = get_the_tags();
                    if ($tags) {
                        foreach ($tags as $tag) {
                            echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="inline-block bg-gray-100 dark:bg-dark-700 text-gray-700 dark:text-gray-300 px-3 py-2 rounded-full text-sm hover:bg-primary-100 dark:hover:bg-primary-900 transition-colors">' . esc_html($tag->name) . '</a>';
                        }
                    }
                    ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Social Sharing -->
        <div class="social-sharing mb-8">
            <h4 class="text-lg font-semibold mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                </svg>
                <?php esc_html_e('Share this post', 'aqualuxe'); ?>
            </h4>
            <?php aqualuxe_social_sharing(); ?>
        </div>
        
        <!-- Edit Link -->
        <?php if (get_edit_post_link()): ?>
            <div class="edit-link">
                <?php
                edit_post_link(
                    sprintf(
                        wp_kses(
                            /* translators: %s: Name of current post. Only visible to screen readers */
                            __('Edit <span class="sr-only">"%s"</span>', 'aqualuxe'),
                            [
                                'span' => [
                                    'class' => [],
                                ],
                            ]
                        ),
                        get_the_title()
                    ),
                    '<div class="edit-post-link"><span class="btn btn-outline btn-sm">',
                    '</span></div>'
                );
                ?>
            </div>
        <?php endif; ?>
        
    </footer><!-- .entry-footer -->
    
    <!-- Hidden structured data -->
    <div style="display: none;">
        <span itemprop="author" itemscope itemtype="https://schema.org/Person">
            <span itemprop="name"><?php echo esc_html(get_the_author()); ?></span>
        </span>
        <meta itemprop="datePublished" content="<?php echo esc_attr(get_the_date('c')); ?>">
        <meta itemprop="dateModified" content="<?php echo esc_attr(get_the_modified_date('c')); ?>">
        <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
            <span itemprop="name"><?php echo esc_html(get_bloginfo('name')); ?></span>
            <?php if (has_custom_logo()): ?>
                <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                    <meta itemprop="url" content="<?php echo esc_url(wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full')); ?>">
                </div>
            <?php endif; ?>
        </div>
        <meta itemprop="mainEntityOfPage" content="<?php echo esc_url(get_permalink()); ?>">
    </div>
    
</article><!-- #post-<?php the_ID(); ?> -->
