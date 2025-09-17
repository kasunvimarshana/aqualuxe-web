<?php
/**
 * Single Post Template
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container mx-auto px-4 py-8">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('max-w-4xl mx-auto'); ?>>
                
                <!-- Featured Image -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail mb-8">
                        <?php the_post_thumbnail('large', ['class' => 'w-full h-auto rounded-lg shadow-lg']); ?>
                    </div>
                <?php endif; ?>

                <!-- Post Header -->
                <header class="entry-header mb-8 text-center">
                    <?php the_title('<h1 class="entry-title text-4xl lg:text-5xl font-bold text-gray-900 mb-6">', '</h1>'); ?>
                    
                    <div class="entry-meta text-gray-600 space-y-2">
                        <div class="post-date">
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" class="published">
                                <?php echo esc_html(get_the_date()); ?>
                            </time>
                        </div>
                        
                        <div class="post-author">
                            <?php esc_html_e('by', 'aqualuxe'); ?> 
                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="author text-primary-600 hover:text-primary-700">
                                <?php echo esc_html(get_the_author()); ?>
                            </a>
                        </div>
                        
                        <?php if (has_category()) : ?>
                            <div class="post-categories">
                                <?php esc_html_e('in', 'aqualuxe'); ?> <?php the_category(', '); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (comments_open() || get_comments_number()) : ?>
                            <div class="post-comments">
                                <a href="#comments" class="text-primary-600 hover:text-primary-700">
                                    <?php 
                                    $comments_number = get_comments_number();
                                    if ($comments_number == 0) {
                                        esc_html_e('Leave a comment', 'aqualuxe');
                                    } elseif ($comments_number == 1) {
                                        esc_html_e('1 comment', 'aqualuxe');
                                    } else {
                                        printf(esc_html__('%s comments', 'aqualuxe'), number_format_i18n($comments_number));
                                    }
                                    ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </header>

                <!-- Post Content -->
                <div class="entry-content prose prose-lg max-w-none mb-8">
                    <?php
                    the_content(sprintf(
                        wp_kses(
                            __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe'),
                            ['span' => ['class' => []]]
                        ),
                        wp_kses_post(get_the_title())
                    ));

                    wp_link_pages([
                        'before' => '<div class="page-links mt-8 p-4 bg-gray-50 rounded-lg">' . esc_html__('Pages:', 'aqualuxe'),
                        'after'  => '</div>',
                        'link_before' => '<span class="inline-block mx-1 px-3 py-1 bg-primary-600 text-white rounded">',
                        'link_after'  => '</span>',
                    ]);
                    ?>
                </div>

                <!-- Post Tags -->
                <?php if (has_tag()) : ?>
                    <div class="entry-tags mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4"><?php esc_html_e('Tags', 'aqualuxe'); ?></h3>
                        <div class="flex flex-wrap gap-2">
                            <?php
                            $tags = get_the_tags();
                            foreach ($tags as $tag) :
                                ?>
                                <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" 
                                   class="inline-block px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full hover:bg-primary-100 hover:text-primary-700 transition-colors">
                                    #<?php echo esc_html($tag->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Post Navigation -->
                <div class="post-navigation flex justify-between items-center py-8 border-t border-b border-gray-200 mb-8">
                    <?php
                    $prev_post = get_previous_post();
                    $next_post = get_next_post();
                    ?>
                    
                    <div class="nav-previous">
                        <?php if ($prev_post) : ?>
                            <a href="<?php echo esc_url(get_permalink($prev_post)); ?>" class="flex items-center text-gray-600 hover:text-primary-600 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                <div>
                                    <div class="text-sm"><?php esc_html_e('Previous', 'aqualuxe'); ?></div>
                                    <div class="font-medium"><?php echo esc_html(wp_trim_words($prev_post->post_title, 6)); ?></div>
                                </div>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="nav-next">
                        <?php if ($next_post) : ?>
                            <a href="<?php echo esc_url(get_permalink($next_post)); ?>" class="flex items-center text-gray-600 hover:text-primary-600 transition-colors">
                                <div class="text-right">
                                    <div class="text-sm"><?php esc_html_e('Next', 'aqualuxe'); ?></div>
                                    <div class="font-medium"><?php echo esc_html(wp_trim_words($next_post->post_title, 6)); ?></div>
                                </div>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Author Bio -->
                <?php if (get_the_author_meta('description')) : ?>
                    <div class="author-bio bg-gray-50 rounded-lg p-6 mb-8">
                        <div class="flex items-start space-x-4">
                            <div class="author-avatar">
                                <?php echo get_avatar(get_the_author_meta('ID'), 80, '', '', ['class' => 'rounded-full']); ?>
                            </div>
                            <div class="author-info flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="hover:text-primary-600 transition-colors">
                                        <?php echo esc_html(get_the_author()); ?>
                                    </a>
                                </h3>
                                <p class="text-gray-600">
                                    <?php echo wp_kses_post(get_the_author_meta('description')); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Share Buttons -->
                <div class="social-share mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4"><?php esc_html_e('Share this post', 'aqualuxe'); ?></h3>
                    <div class="flex space-x-4">
                        <?php
                        $post_url = urlencode(get_permalink());
                        $post_title = urlencode(get_the_title());
                        ?>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>" 
                           target="_blank" 
                           class="flex items-center justify-center w-10 h-10 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>" 
                           target="_blank"
                           class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $post_url; ?>" 
                           target="_blank"
                           class="flex items-center justify-center w-10 h-10 bg-blue-700 text-white rounded-full hover:bg-blue-800 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </article>

            <!-- Comments -->
            <?php
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            ?>

        <?php endwhile; ?>

        <!-- Related Posts -->
        <?php
        $related_posts = new WP_Query([
            'post_type' => 'post',
            'posts_per_page' => 3,
            'post__not_in' => [get_the_ID()],
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => '_related_posts',
                    'value' => get_the_ID(),
                    'compare' => 'LIKE'
                ]
            ]
        ]);

        if (!$related_posts->have_posts()) {
            // Fallback to posts in same category
            $categories = wp_get_post_categories(get_the_ID());
            if (!empty($categories)) {
                $related_posts = new WP_Query([
                    'post_type' => 'post',
                    'posts_per_page' => 3,
                    'post__not_in' => [get_the_ID()],
                    'category__in' => $categories
                ]);
            }
        }

        if ($related_posts->have_posts()) :
            ?>
            <section class="related-posts mt-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-8"><?php esc_html_e('Related Posts', 'aqualuxe'); ?></h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                        <article class="related-post bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-thumbnail">
                                    <a href="<?php the_permalink(); ?>" class="block">
                                        <?php the_post_thumbnail('medium', ['class' => 'w-full h-48 object-cover']); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="post-content p-6">
                                <h3 class="text-lg font-semibold mb-3">
                                    <a href="<?php the_permalink(); ?>" class="text-gray-900 hover:text-primary-600 transition-colors">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                <p class="text-gray-600 text-sm mb-3">
                                    <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                </p>
                                <div class="post-meta text-xs text-gray-500">
                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                        <?php echo esc_html(get_the_date()); ?>
                                    </time>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            </section>
            <?php
            wp_reset_postdata();
        endif;
        ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();