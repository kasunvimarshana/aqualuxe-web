<?php
/**
 * Template part for displaying results in search pages
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('search-result'); ?>>
    
    <?php if (has_post_thumbnail()) : ?>
        <div class="entry-featured-image">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php the_post_thumbnail('aqualuxe-medium', array('class' => 'featured-image')); ?>
            </a>
        </div>
    <?php endif; ?>
    
    <div class="entry-content-wrapper">
        <header class="entry-header">
            <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>
            
            <?php if ('post' === get_post_type()) : ?>
                <div class="entry-meta">
                    <div class="entry-meta-item">
                        <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                            <?php echo esc_html(get_the_date()); ?>
                        </time>
                    </div>
                    
                    <div class="entry-meta-item">
                        <span class="byline">
                            <?php esc_html_e('By', 'aqualuxe'); ?> 
                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                <?php echo esc_html(get_the_author()); ?>
                            </a>
                        </span>
                    </div>
                    
                    <?php if (has_category()) : ?>
                        <div class="entry-meta-item">
                            <span class="cat-links">
                                <?php the_category(', '); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </header>
        
        <div class="entry-summary">
            <?php the_excerpt(); ?>
        </div>
        
        <div class="entry-footer">
            <a href="<?php the_permalink(); ?>" class="read-more">
                <?php esc_html_e('Read More', 'aqualuxe'); ?>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12,5 19,12 12,19"></polyline>
                </svg>
            </a>
        </div>
    </div>
    
</article>