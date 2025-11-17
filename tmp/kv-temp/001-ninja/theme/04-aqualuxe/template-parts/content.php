<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('aqualuxe-post'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('large'); ?>
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

        if ('post' === get_post_type()) :
            ?>
            <div class="entry-meta">
                <span class="posted-on">
                    <i class="fas fa-calendar-alt"></i>
                    <?php echo esc_html(get_the_date()); ?>
                </span>
                
                <span class="posted-by">
                    <i class="fas fa-user"></i>
                    <?php the_author_posts_link(); ?>
                </span>
                
                <?php if (has_category()) : ?>
                    <span class="post-categories">
                        <i class="fas fa-folder"></i>
                        <?php the_category(', '); ?>
                    </span>
                <?php endif; ?>
                
                <?php if (comments_open() || get_comments_number()) : ?>
                    <span class="comments-link">
                        <i class="fas fa-comments"></i>
                        <?php comments_popup_link(); ?>
                    </span>
                <?php endif; ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php if (is_singular()) : ?>
        <div class="entry-content">
            <?php
            the_content(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post(get_the_title())
                )
            );

            wp_link_pages(
                array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                    'after'  => '</div>',
                )
            );
            ?>
        </div><!-- .entry-content -->
    <?php else : ?>
        <div class="entry-summary">
            <?php the_excerpt(); ?>
            <a href="<?php the_permalink(); ?>" class="read-more-link">
                <?php esc_html_e('Read More', 'aqualuxe'); ?>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div><!-- .entry-summary -->
    <?php endif; ?>

    <footer class="entry-footer">
        <?php if (has_tag()) : ?>
            <div class="post-tags">
                <i class="fas fa-tags"></i>
                <?php the_tags('', ', ', ''); ?>
            </div>
        <?php endif; ?>
        
        <?php
        edit_post_link(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Edit <span class="screen-reader-text">%s</span>', 'aqualuxe'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post(get_the_title())
            ),
            '<span class="edit-link">',
            '</span>'
        );
        ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->