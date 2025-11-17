<?php
/**
 * Template part for displaying posts
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
    <?php if (has_post_thumbnail()) : ?>
        <div class="entry-featured-image">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php the_post_thumbnail('aqualuxe-blog-large', array('class' => 'featured-image')); ?>
            </a>
        </div>
    <?php endif; ?>
    
    <header class="entry-header">
        <?php
        if (is_singular()) :
            the_title('<h1 class="entry-title">', '</h1>');
        else :
            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        endif;
        ?>
        
        <?php if ('post' === get_post_type()) : ?>
            <div class="entry-meta">
                <div class="entry-meta-item">
                    <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                        <?php echo esc_html(get_the_date()); ?>
                    </time>
                    <?php if (get_the_time('U') !== get_the_modified_time('U')) : ?>
                        <time class="updated" datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>">
                            <?php echo esc_html(get_the_modified_date()); ?>
                        </time>
                    <?php endif; ?>
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
                
                <?php if (comments_open() || get_comments_number()) : ?>
                    <div class="entry-meta-item">
                        <span class="comments-link">
                            <?php comments_popup_link(
                                esc_html__('Leave a comment', 'aqualuxe'),
                                esc_html__('1 Comment', 'aqualuxe'),
                                esc_html__('% Comments', 'aqualuxe')
                            ); ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </header>
    
    <div class="entry-content">
        <?php
        if (is_singular()) {
            the_content();
            
            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                'after'  => '</div>',
            ));
        } else {
            the_excerpt();
            
            echo '<div class="read-more-wrapper">';
            echo '<a href="' . esc_url(get_permalink()) . '" class="read-more">';
            echo esc_html__('Read More', 'aqualuxe');
            echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">';
            echo '<line x1="5" y1="12" x2="19" y2="12"></line>';
            echo '<polyline points="12,5 19,12 12,19"></polyline>';
            echo '</svg>';
            echo '</a>';
            echo '</div>';
        }
        ?>
    </div>
    
    <?php if (is_singular()) : ?>
        <footer class="entry-footer">
            <?php if (has_tag()) : ?>
                <div class="tags-links">
                    <span class="tags-title"><?php esc_html_e('Tags:', 'aqualuxe'); ?></span>
                    <?php the_tags('', ', ', ''); ?>
                </div>
            <?php endif; ?>
            
            <?php
            edit_post_link(
                sprintf(
                    wp_kses(
                        __('Edit <span class="screen-reader-text">%s</span>', 'aqualuxe'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    get_the_title()
                ),
                '<span class="edit-link">',
                '</span>'
            );
            ?>
        </footer>
    <?php endif; ?>
    
</article>