<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

$show_author = aqualuxe_get_option('blog_show_author', true);
$show_date = aqualuxe_get_option('blog_show_date', true);
$read_more_text = aqualuxe_get_option('blog_read_more_text', __('Read More', 'aqualuxe'));
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('aqualuxe-search-item'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="aqualuxe-search-item-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium'); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="aqualuxe-search-item-content">
        <header class="aqualuxe-search-item-header">
            <?php the_title('<h2 class="aqualuxe-search-item-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>

            <div class="aqualuxe-search-item-meta">
                <?php if ($show_date) : ?>
                    <span class="aqualuxe-search-item-date">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"></path></svg>
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                    </span>
                <?php endif; ?>

                <?php if ($show_author) : ?>
                    <span class="aqualuxe-search-item-author">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo esc_html(get_the_author()); ?></a>
                    </span>
                <?php endif; ?>

                <?php if (get_post_type() === 'post') : ?>
                    <span class="aqualuxe-search-item-type">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"></path></svg>
                        <?php esc_html_e('Blog Post', 'aqualuxe'); ?>
                    </span>
                <?php elseif (get_post_type() === 'page') : ?>
                    <span class="aqualuxe-search-item-type">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"></path></svg>
                        <?php esc_html_e('Page', 'aqualuxe'); ?>
                    </span>
                <?php elseif (get_post_type() === 'product' && aqualuxe_is_woocommerce_active()) : ?>
                    <span class="aqualuxe-search-item-type">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"></path></svg>
                        <?php esc_html_e('Product', 'aqualuxe'); ?>
                    </span>
                <?php else : ?>
                    <span class="aqualuxe-search-item-type">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"></path></svg>
                        <?php echo esc_html(get_post_type_object(get_post_type())->labels->singular_name); ?>
                    </span>
                <?php endif; ?>
            </div>
        </header>

        <div class="aqualuxe-search-item-excerpt">
            <?php the_excerpt(); ?>
        </div>

        <div class="aqualuxe-search-item-footer">
            <a href="<?php the_permalink(); ?>" class="aqualuxe-search-item-read-more button button-text">
                <?php echo esc_html($read_more_text); ?>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"></path></svg>
            </a>
        </div>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->