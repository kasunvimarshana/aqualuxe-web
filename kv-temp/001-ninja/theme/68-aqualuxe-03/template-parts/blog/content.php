<?php
/**
 * Blog post content template (default)
 * @package AquaLuxe
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('blog-post'); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large'); ?></a>
        </div>
    <?php endif; ?>
    <header class="entry-header">
        <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <div class="entry-meta">
            <span class="post-date"><?php echo get_the_date(); ?></span>
            <span class="post-author">By <?php the_author(); ?></span>
        </div>
    </header>
    <div class="entry-summary">
        <?php the_excerpt(); ?>
    </div>
    <footer class="entry-footer">
        <?php the_category(', '); ?>
    </footer>
</article>
