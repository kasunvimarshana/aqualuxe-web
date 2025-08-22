<?php
/**
 * Template part for displaying posts in single view
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$show_featured_image = get_theme_mod('single_post_featured_image', true);
$featured_image_style = get_theme_mod('single_post_featured_image_style', 'standard');
$show_author_box = get_theme_mod('single_post_author_box', true);
$show_related_posts = get_theme_mod('single_post_related_posts', true);
$show_post_navigation = get_theme_mod('single_post_navigation', true);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
    <header class="single-post__header">
        <?php
        if ('post' === get_post_type()) :
            ?>
            <div class="single-post__meta">
                <?php
                aqualuxe_posted_on();
                aqualuxe_posted_by();
                ?>
            </div>
        <?php endif; ?>

        <?php the_title('<h1 class="single-post__title">', '</h1>'); ?>

        <?php if (has_excerpt()) : ?>
            <div class="single-post__excerpt">
                <?php the_excerpt(); ?>
            </div>
        <?php endif; ?>
    </header>

    <?php if ($show_featured_image && has_post_thumbnail()) : ?>
        <div class="single-post__thumbnail single-post__thumbnail--<?php echo esc_attr($featured_image_style); ?>">
            <?php the_post_thumbnail('full', ['class' => 'single-post__image']); ?>
            
            <?php if (get_the_post_thumbnail_caption()) : ?>
                <figcaption class="single-post__thumbnail-caption">
                    <?php the_post_thumbnail_caption(); ?>
                </figcaption>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="single-post__content">
        <?php
        the_content(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe'),
                    [
                        'span' => [
                            'class' => [],
                        ],
                    ]
                ),
                wp_kses_post(get_the_title())
            )
        );

        wp_link_pages([
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
            'after'  => '</div>',
        ]);
        ?>
    </div>

    <footer class="single-post__footer">
        <?php aqualuxe_entry_footer(); ?>
    </footer>

    <?php if ($show_author_box && 'post' === get_post_type()) : ?>
        <div class="single-post__author">
            <div class="author-box">
                <div class="author-box__avatar">
                    <?php echo get_avatar(get_the_author_meta('ID'), 100); ?>
                </div>
                <div class="author-box__content">
                    <h3 class="author-box__name">
                        <?php echo esc_html(get_the_author_meta('display_name')); ?>
                    </h3>
                    <?php if (get_the_author_meta('description')) : ?>
                        <div class="author-box__bio">
                            <?php echo wp_kses_post(get_the_author_meta('description')); ?>
                        </div>
                    <?php endif; ?>
                    <div class="author-box__links">
                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="author-box__link">
                            <?php
                            /* translators: %s: Author name */
                            printf(esc_html__('View all posts by %s', 'aqualuxe'), get_the_author());
                            ?>
                        </a>
                        <?php if (get_the_author_meta('url')) : ?>
                            <a href="<?php echo esc_url(get_the_author_meta('url')); ?>" class="author-box__link" target="_blank" rel="noopener noreferrer">
                                <?php esc_html_e('Website', 'aqualuxe'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php
    // Related posts
    if ($show_related_posts && 'post' === get_post_type()) :
        aqualuxe_related_posts();
    endif;
    ?>
</article>