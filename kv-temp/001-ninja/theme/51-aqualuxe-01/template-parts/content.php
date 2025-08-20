<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

$show_author = aqualuxe_get_option('blog_show_author', true);
$show_date = aqualuxe_get_option('blog_show_date', true);
$show_category = aqualuxe_get_option('blog_show_category', true);
$show_comments = aqualuxe_get_option('blog_show_comments', true);
$read_more_text = aqualuxe_get_option('blog_read_more_text', __('Read More', 'aqualuxe'));
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('aqualuxe-post'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="aqualuxe-post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium_large'); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="aqualuxe-post-content">
        <header class="aqualuxe-post-header">
            <?php if ($show_category && has_category()) : ?>
                <div class="aqualuxe-post-categories">
                    <?php
                    $categories = get_the_category();
                    if (!empty($categories)) {
                        echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
                    }
                    ?>
                </div>
            <?php endif; ?>

            <?php the_title('<h2 class="aqualuxe-post-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>

            <div class="aqualuxe-post-meta">
                <?php if ($show_date) : ?>
                    <span class="aqualuxe-post-date">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"></path></svg>
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                    </span>
                <?php endif; ?>

                <?php if ($show_author) : ?>
                    <span class="aqualuxe-post-author">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo esc_html(get_the_author()); ?></a>
                    </span>
                <?php endif; ?>

                <?php if ($show_comments) : ?>
                    <span class="aqualuxe-post-comments">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M21.99 4c0-1.1-.89-2-1.99-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4-.01-18zM18 14H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"></path></svg>
                        <?php comments_popup_link('0', '1', '%', 'aqualuxe-post-comments-link'); ?>
                    </span>
                <?php endif; ?>
            </div>
        </header>

        <div class="aqualuxe-post-excerpt">
            <?php the_excerpt(); ?>
        </div>

        <div class="aqualuxe-post-footer">
            <a href="<?php the_permalink(); ?>" class="aqualuxe-post-read-more button button-text">
                <?php echo esc_html($read_more_text); ?>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"></path></svg>
            </a>
        </div>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->