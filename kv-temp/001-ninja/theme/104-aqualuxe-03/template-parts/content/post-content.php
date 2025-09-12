<?php
/**
 * Template part for displaying post content
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post-content mb-8 bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden'); ?> itemscope itemtype="https://schema.org/Article">
    
    <!-- Post Header -->
    <header class="post-header">
        <?php if (has_post_thumbnail()) : ?>
            <div class="post-thumbnail relative overflow-hidden">
                <a href="<?php the_permalink(); ?>" class="block">
                    <?php the_post_thumbnail('aqualuxe-featured', array(
                        'class' => 'w-full h-64 object-cover transition-transform duration-300 hover:scale-105',
                        'loading' => 'lazy',
                        'itemprop' => 'image'
                    )); ?>
                </a>
                
                <!-- Category Badge -->
                <?php if (has_category()) : ?>
                    <div class="absolute top-4 left-4">
                        <?php
                        $categories = get_the_category();
                        if (!empty($categories)) :
                            ?>
                            <span class="inline-block bg-primary-600 text-white text-xs font-medium px-3 py-1 rounded-full">
                                <?php echo esc_html($categories[0]->name); ?>
                            </span>
                            <?php
                        endif;
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="post-header-content p-6">
            <!-- Post Meta -->
            <div class="post-meta flex items-center text-sm text-gray-500 dark:text-gray-400 mb-3">
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished" class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <?php echo get_the_date(); ?>
                </time>
                
                <span class="mx-2">•</span>
                
                <span class="author flex items-center" itemprop="author" itemscope itemtype="https://schema.org/Person">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span itemprop="name"><?php echo get_the_author(); ?></span>
                </span>
                
                <?php if (comments_open() || get_comments_number()) : ?>
                    <span class="mx-2">•</span>
                    <a href="<?php comments_link(); ?>" class="flex items-center hover:text-primary-600 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <?php comments_number(
                            esc_html__('No Comments', 'aqualuxe'),
                            esc_html__('1 Comment', 'aqualuxe'),
                            esc_html__('% Comments', 'aqualuxe')
                        ); ?>
                    </a>
                <?php endif; ?>
                
                <!-- Reading Time -->
                <span class="mx-2">•</span>
                <span class="reading-time flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <?php echo aqualuxe_get_reading_time(); ?>
                </span>
            </div>
            
            <!-- Post Title -->
            <?php if (is_singular()) : ?>
                <h1 class="post-title text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-4 leading-tight" itemprop="headline">
                    <?php the_title(); ?>
                </h1>
            <?php else : ?>
                <h2 class="post-title text-xl lg:text-2xl font-bold mb-4 leading-tight">
                    <a href="<?php the_permalink(); ?>" class="text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200" itemprop="headline">
                        <?php the_title(); ?>
                    </a>
                </h2>
            <?php endif; ?>
        </div>
    </header>
    
    <!-- Post Content -->
    <div class="post-body px-6 pb-6" itemprop="articleBody">
        <?php if (is_singular()) : ?>
            <?php the_content(); ?>
            
            <?php
            wp_link_pages(array(
                'before' => '<div class="page-links mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg"><span class="font-medium text-gray-900 dark:text-white">' . esc_html__('Pages:', 'aqualuxe') . '</span>',
                'after'  => '</div>',
                'link_before' => '<span class="inline-block bg-white dark:bg-gray-600 text-gray-900 dark:text-white px-3 py-1 rounded ml-2 hover:bg-primary-600 hover:text-white transition-colors duration-200">',
                'link_after' => '</span>',
            ));
            ?>
        <?php else : ?>
            <?php 
            if (has_excerpt()) {
                the_excerpt();
            } else {
                echo '<p class="text-gray-600 dark:text-gray-300">' . wp_trim_words(get_the_content(), 30, '...') . '</p>';
            }
            ?>
            
            <div class="mt-4">
                <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium transition-colors duration-200">
                    <?php esc_html_e('Read More', 'aqualuxe'); ?>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Post Footer -->
    <?php if (is_singular()) : ?>
    <footer class="post-footer px-6 pb-6">
        <!-- Tags -->
        <?php if (has_tag()) : ?>
            <div class="post-tags mb-4">
                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <?php esc_html_e('Tags:', 'aqualuxe'); ?>
                </h4>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $tags = get_the_tags();
                    if (!empty($tags)) :
                        foreach ($tags as $tag) :
                            ?>
                            <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" 
                               class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm px-3 py-1 rounded-full hover:bg-primary-100 hover:text-primary-700 dark:hover:bg-primary-900 dark:hover:text-primary-300 transition-colors duration-200">
                                <?php echo esc_html($tag->name); ?>
                            </a>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Share Buttons -->
        <div class="post-share">
            <?php aqualuxe_get_template_part('components/social-share', null, array('post_id' => get_the_ID())); ?>
        </div>
    </footer>
    <?php endif; ?>
    
    <!-- Schema.org Metadata -->
    <meta itemprop="dateModified" content="<?php echo esc_attr(get_the_modified_date('c')); ?>">
    <meta itemprop="wordCount" content="<?php echo esc_attr(str_word_count(strip_tags(get_the_content()))); ?>">
    <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization" style="display: none;">
        <meta itemprop="name" content="<?php bloginfo('name'); ?>">
        <?php if (has_custom_logo()) : ?>
            <meta itemprop="logo" content="<?php echo esc_url(wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full')); ?>">
        <?php endif; ?>
    </div>
</article>