<?php
/**
 * Template part for displaying posts in an archive
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get archive layout setting
$archive_layout = aqualuxe_get_archive_layout();
$post_classes = array('archive-post', 'archive-layout-' . $archive_layout);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($post_classes); ?>>
    <?php if ($archive_layout === 'grid' || $archive_layout === 'masonry') : ?>
        <div class="archive-post-inner">
    <?php endif; ?>
    
    <?php if (has_post_thumbnail() && aqualuxe_show_post_thumbnail()) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php the_post_thumbnail('aqualuxe-archive-' . $archive_layout, array('class' => 'archive-thumbnail')); ?>
            </a>
        </div>
    <?php endif; ?>

    <header class="entry-header">
        <?php
        the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');

        if ('post' === get_post_type()) :
            ?>
            <div class="entry-meta">
                <?php
                aqualuxe_posted_on();
                aqualuxe_posted_by();
                ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <div class="entry-content">
        <?php
        if (aqualuxe_get_excerpt_type() === 'full') :
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
        else :
            the_excerpt();
            echo '<div class="read-more-link"><a href="' . esc_url(get_permalink()) . '">' . esc_html__('Read More', 'aqualuxe') . '</a></div>';
        endif;
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php aqualuxe_entry_footer(); ?>
    </footer><!-- .entry-footer -->
    
    <?php if ($archive_layout === 'grid' || $archive_layout === 'masonry') : ?>
        </div><!-- .archive-post-inner -->
    <?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->