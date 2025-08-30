<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('search-result'); ?>>
    <header class="search-result__header">
        <?php the_title(sprintf('<h2 class="search-result__title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>

        <?php if ('post' === get_post_type()) : ?>
            <div class="search-result__meta">
                <?php
                aqualuxe_posted_on();
                aqualuxe_posted_by();
                ?>
            </div>
        <?php endif; ?>
    </header>

    <?php if (has_post_thumbnail()) : ?>
        <div class="search-result__thumbnail">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php the_post_thumbnail('thumbnail', ['class' => 'search-result__image']); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="search-result__content">
        <?php the_excerpt(); ?>
    </div>

    <footer class="search-result__footer">
        <?php
        $post_type = get_post_type();
        $post_type_obj = get_post_type_object($post_type);
        ?>
        <span class="search-result__type">
            <?php echo esc_html($post_type_obj->labels->singular_name); ?>
        </span>

        <?php if ('post' === $post_type) : ?>
            <?php aqualuxe_entry_footer(); ?>
        <?php endif; ?>

        <a href="<?php the_permalink(); ?>" class="search-result__read-more">
            <?php esc_html_e('Read more', 'aqualuxe'); ?>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                <path fill-rule="evenodd" d="M12.97 3.97a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 11-1.06-1.06l6.22-6.22H3a.75.75 0 010-1.5h16.19l-6.22-6.22a.75.75 0 010-1.06z" clip-rule="evenodd" />
            </svg>
        </a>
    </footer>
</article>