<?php
/**
 * Content template for posts
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card-hover mb-8'); ?> data-animate="fade">
    
    <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail mb-6">
            <a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded-lg">
                <?php
                the_post_thumbnail('aqualuxe-blog-medium', [
                    'class' => 'w-full h-64 object-cover transition-transform duration-300 hover:scale-105',
                    'alt'   => get_the_title(),
                ]);
                ?>
            </a>
        </div>
    <?php endif; ?>
    
    <div class="post-content p-6">
        
        <div class="post-meta flex items-center space-x-4 text-sm text-neutral-500 mb-4">
            <time datetime="<?php echo get_the_date('c'); ?>" class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <?php echo get_the_date(); ?>
            </time>
            
            <?php if (has_category()) : ?>
                <div class="categories flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <?php the_category(', '); ?>
                </div>
            <?php endif; ?>
            
            <div class="author flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <?php the_author(); ?>
            </div>
        </div>
        
        <header class="post-header mb-4">
            <h2 class="post-title text-2xl font-bold leading-tight">
                <a href="<?php the_permalink(); ?>" class="text-neutral-800 hover:text-primary-600 transition-colors dark:text-neutral-100 dark:hover:text-primary-400">
                    <?php the_title(); ?>
                </a>
            </h2>
        </header>
        
        <div class="post-excerpt text-neutral-600 dark:text-neutral-300 mb-6">
            <?php the_excerpt(); ?>
        </div>
        
        <footer class="post-footer flex items-center justify-between">
            <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm">
                <?php esc_html_e('Read More', 'aqualuxe'); ?>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
            
            <div class="post-actions flex items-center space-x-2">
                <?php if (comments_open() || get_comments_number()) : ?>
                    <a href="<?php comments_link(); ?>" class="text-neutral-500 hover:text-primary-600 flex items-center text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <?php
                        $comments_number = get_comments_number();
                        if ($comments_number == 0) {
                            esc_html_e('No Comments', 'aqualuxe');
                        } elseif ($comments_number == 1) {
                            esc_html_e('1 Comment', 'aqualuxe');
                        } else {
                            printf(esc_html__('%s Comments', 'aqualuxe'), $comments_number);
                        }
                        ?>
                    </a>
                <?php endif; ?>
                
                <button class="share-post text-neutral-500 hover:text-primary-600" data-share="<?php the_permalink(); ?>" title="<?php esc_attr_e('Share this post', 'aqualuxe'); ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                    </svg>
                </button>
            </div>
        </footer>
        
    </div>
    
</article>