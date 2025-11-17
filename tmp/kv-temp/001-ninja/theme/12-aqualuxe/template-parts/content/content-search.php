<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('search-result-item'); ?>>
    <div class="search-result-inner">
        <?php if (has_post_thumbnail()) : ?>
            <div class="search-thumbnail">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('thumbnail', array('class' => 'img-fluid')); ?>
                </a>
            </div>
        <?php endif; ?>
        
        <div class="search-content">
            <header class="entry-header">
                <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
                
                <?php if ('post' === get_post_type()) : ?>
                    <div class="entry-meta">
                        <span class="post-date">
                            <i class="far fa-calendar-alt"></i>
                            <?php echo get_the_date(); ?>
                        </span>
                        
                        <span class="post-type">
                            <i class="far fa-file-alt"></i>
                            <?php echo get_post_type_object(get_post_type())->labels->singular_name; ?>
                        </span>
                    </div>
                <?php elseif (get_post_type() !== 'page') : ?>
                    <div class="entry-meta">
                        <span class="post-type">
                            <i class="far fa-file-alt"></i>
                            <?php echo get_post_type_object(get_post_type())->labels->singular_name; ?>
                        </span>
                    </div>
                <?php endif; ?>
            </header>
            
            <div class="entry-summary">
                <?php the_excerpt(); ?>
            </div>
            
            <footer class="entry-footer">
                <a href="<?php the_permalink(); ?>" class="read-more">
                    <?php esc_html_e('Read More', 'aqualuxe'); ?> <i class="fas fa-long-arrow-alt-right"></i>
                </a>
            </footer>
        </div>
    </div>
</article>