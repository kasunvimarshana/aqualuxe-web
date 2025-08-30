<?php
/**
 * Template part for displaying posts
 *
 * @package AquaLuxe
 * @version 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post-item card p-6 mb-8'); ?> itemscope itemtype="https://schema.org/BlogPosting">
    
    <?php if (has_post_thumbnail()): ?>
        <div class="post-thumbnail mb-6" itemprop="image">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail('aqualuxe-blog-thumb', [
                    'class' => 'w-full h-64 object-cover rounded-lg transition-transform duration-300 hover:scale-105',
                    'loading' => 'lazy',
                    'itemprop' => 'image'
                ]);
                ?>
            </a>
        </div>
    <?php endif; ?>
    
    <header class="entry-header mb-4">
        <?php
        if (is_singular()):
            the_title('<h1 class="entry-title text-3xl font-bold mb-4" itemprop="headline">', '</h1>');
        else:
            the_title('<h2 class="entry-title text-2xl font-bold mb-4" itemprop="headline"><a href="' . esc_url(get_permalink()) . '" class="hover:text-primary-600 transition-colors" rel="bookmark">', '</a></h2>');
        endif;
        
        if ('post' === get_post_type()):
            aqualuxe_post_meta();
        endif;
        ?>
    </header><!-- .entry-header -->
    
    <div class="entry-content prose prose-lg max-w-none" itemprop="articleBody">
        <?php
        if (is_singular() || is_search()):
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
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                'after'  => '</div>',
            ]);
        else:
            the_excerpt();
            ?>
            <div class="read-more mt-4">
                <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm">
                    <?php esc_html_e('Read More', 'aqualuxe'); ?>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            <?php
        endif;
        ?>
    </div><!-- .entry-content -->
    
    <?php if (is_singular()): ?>
        <!-- Post Tags -->
        <?php
        $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'aqualuxe'));
        if ($tags_list):
        ?>
            <div class="post-tags mt-6 pt-6 border-t border-gray-200 dark:border-dark-700">
                <h4 class="text-sm font-semibold mb-2"><?php esc_html_e('Tags:', 'aqualuxe'); ?></h4>
                <div class="tag-list">
                    <?php
                    $tags = get_the_tags();
                    if ($tags) {
                        foreach ($tags as $tag) {
                            echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="inline-block bg-gray-100 dark:bg-dark-700 text-gray-700 dark:text-gray-300 px-3 py-1 rounded-full text-sm mr-2 mb-2 hover:bg-primary-100 dark:hover:bg-primary-900 transition-colors">' . esc_html($tag->name) . '</a>';
                        }
                    }
                    ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Social Sharing -->
        <?php aqualuxe_social_sharing(); ?>
        
        <!-- Author Bio -->
        <?php if (get_the_author_meta('description')): ?>
            <div class="author-bio mt-8 p-6 bg-gray-50 dark:bg-dark-800 rounded-lg" itemscope itemtype="https://schema.org/Person">
                <div class="flex items-start space-x-4">
                    <div class="author-avatar flex-shrink-0">
                        <?php echo get_avatar(get_the_author_meta('ID'), 80, '', get_the_author(), ['class' => 'rounded-full', 'itemprop' => 'image']); ?>
                    </div>
                    <div class="author-info flex-1">
                        <h4 class="text-lg font-semibold mb-2" itemprop="name">
                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="hover:text-primary-600 transition-colors" itemprop="url">
                                <?php echo esc_html(get_the_author()); ?>
                            </a>
                        </h4>
                        <p class="text-gray-600 dark:text-gray-400" itemprop="description">
                            <?php echo esc_html(get_the_author_meta('description')); ?>
                        </p>
                        <div class="author-links mt-3 flex space-x-4">
                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="text-sm text-primary-600 hover:text-primary-700 transition-colors">
                                <?php esc_html_e('View all posts', 'aqualuxe'); ?>
                            </a>
                            <?php if (get_the_author_meta('user_url')): ?>
                                <a href="<?php echo esc_url(get_the_author_meta('user_url')); ?>" target="_blank" rel="noopener" class="text-sm text-primary-600 hover:text-primary-700 transition-colors" itemprop="url">
                                    <?php esc_html_e('Website', 'aqualuxe'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Related Posts -->
        <?php aqualuxe_related_posts(); ?>
        
    <?php endif; ?>
    
    <!-- Hidden structured data -->
    <div style="display: none;" itemprop="author" itemscope itemtype="https://schema.org/Person">
        <span itemprop="name"><?php echo esc_html(get_the_author()); ?></span>
    </div>
    <meta itemprop="datePublished" content="<?php echo esc_attr(get_the_date('c')); ?>">
    <meta itemprop="dateModified" content="<?php echo esc_attr(get_the_modified_date('c')); ?>">
    <div style="display: none;" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
        <span itemprop="name"><?php echo esc_html(get_bloginfo('name')); ?></span>
        <?php if (has_custom_logo()): ?>
            <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                <meta itemprop="url" content="<?php echo esc_url(wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full')); ?>">
            </div>
        <?php endif; ?>
    </div>
    
</article><!-- #post-<?php the_ID(); ?> -->
