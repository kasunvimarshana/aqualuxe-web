<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header mb-8">
        <?php the_title('<h1 class="entry-title text-3xl md:text-4xl lg:text-5xl font-bold mb-6">', '</h1>'); ?>
        
        <div class="entry-meta flex flex-wrap items-center text-sm text-gray-600 dark:text-gray-400 mb-6">
            <span class="post-date mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <?php echo get_the_date(); ?>
            </span>
            
            <span class="post-author mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                    <?php the_author(); ?>
                </a>
            </span>
            
            <?php if (has_category()) : ?>
            <span class="post-categories mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <?php the_category(', '); ?>
            </span>
            <?php endif; ?>
            
            <?php if (comments_open() || get_comments_number()) : ?>
            <span class="post-comments ml-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
                <a href="#comments" class="hover:text-primary-600 dark:hover:text-primary-400">
                    <?php comments_number(__('No Comments', 'aqualuxe'), __('1 Comment', 'aqualuxe'), __('% Comments', 'aqualuxe')); ?>
                </a>
            </span>
            <?php endif; ?>
        </div><!-- .entry-meta -->
        
        <?php if (has_post_thumbnail()) : ?>
            <div class="post-thumbnail mb-8">
                <?php the_post_thumbnail('aqualuxe-featured', array('class' => 'w-full h-auto rounded-lg shadow-lg')); ?>
                <?php if (get_the_post_thumbnail_caption()) : ?>
                    <figcaption class="mt-2 text-sm text-center text-gray-500 dark:text-gray-400">
                        <?php echo wp_kses_post(get_the_post_thumbnail_caption()); ?>
                    </figcaption>
                <?php endif; ?>
            </div><!-- .post-thumbnail -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <div class="entry-content prose dark:prose-invert lg:prose-lg max-w-none">
        <?php
        the_content();

        wp_link_pages(
            array(
                'before' => '<div class="page-links mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">' . esc_html__('Pages:', 'aqualuxe'),
                'after'  => '</div>',
            )
        );
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
        <?php if (has_tag()) : ?>
            <div class="post-tags mb-6">
                <h4 class="text-lg font-bold mb-2"><?php esc_html_e('Tags:', 'aqualuxe'); ?></h4>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $tags_list = get_the_tag_list('', '');
                    if ($tags_list) {
                        $tags = get_the_tags();
                        foreach ($tags as $tag) {
                            echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="inline-block px-3 py-1 bg-gray-100 dark:bg-gray-700 hover:bg-primary-100 dark:hover:bg-primary-900 rounded-full text-sm">' . esc_html($tag->name) . '</a>';
                        }
                    }
                    ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_show_author_box', true)) : ?>
            <div class="author-box bg-gray-50 dark:bg-gray-800 p-6 rounded-lg flex flex-wrap md:flex-nowrap gap-6 items-center">
                <div class="author-avatar">
                    <?php echo get_avatar(get_the_author_meta('ID'), 96, '', '', array('class' => 'rounded-full')); ?>
                </div>
                <div class="author-info">
                    <h4 class="author-name text-xl font-bold mb-2">
                        <?php echo esc_html(get_the_author_meta('display_name')); ?>
                    </h4>
                    <?php if (get_the_author_meta('description')) : ?>
                        <p class="author-bio text-gray-600 dark:text-gray-300 mb-4">
                            <?php echo wp_kses_post(get_the_author_meta('description')); ?>
                        </p>
                    <?php endif; ?>
                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:underline">
                        <?php esc_html_e('View all posts by', 'aqualuxe'); ?> <?php the_author(); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_show_post_sharing', true)) : ?>
            <div class="post-sharing mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-bold mb-4"><?php esc_html_e('Share This Post:', 'aqualuxe'); ?></h4>
                <div class="flex flex-wrap gap-2">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-2 bg-[#3b5998] hover:bg-[#2d4373] text-white rounded-md transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                        </svg>
                        <?php esc_html_e('Facebook', 'aqualuxe'); ?>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode(get_the_title()); ?>&url=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-2 bg-[#1da1f2] hover:bg-[#0c85d0] text-white rounded-md transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                        <?php esc_html_e('Twitter', 'aqualuxe'); ?>
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-2 bg-[#0077b5] hover:bg-[#005582] text-white rounded-md transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>
                        </svg>
                        <?php esc_html_e('LinkedIn', 'aqualuxe'); ?>
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_permalink()); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <?php esc_html_e('Email', 'aqualuxe'); ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->