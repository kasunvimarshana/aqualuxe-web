<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$post_classes = ['post-card'];
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($post_classes); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="post-card__thumbnail">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php the_post_thumbnail('medium_large', ['class' => 'post-card__image']); ?>
            </a>
            
            <?php if (is_sticky() && is_home() && !is_paged()) : ?>
                <span class="post-card__sticky">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                        <path fill-rule="evenodd" d="M6.32 2.577a49.255 49.255 0 0111.36 0c1.497.174 2.57 1.46 2.57 2.93V21a.75.75 0 01-1.085.67L12 18.089l-7.165 3.583A.75.75 0 013.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93z" clip-rule="evenodd" />
                    </svg>
                    <span class="screen-reader-text"><?php esc_html_e('Featured post', 'aqualuxe'); ?></span>
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="post-card__content">
        <header class="post-card__header">
            <?php
            if (is_singular()) :
                the_title('<h1 class="post-card__title">', '</h1>');
            else :
                the_title('<h2 class="post-card__title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
            endif;

            if ('post' === get_post_type()) :
                ?>
                <div class="post-card__meta">
                    <?php
                    aqualuxe_posted_on();
                    aqualuxe_posted_by();
                    ?>
                </div>
            <?php endif; ?>
        </header>

        <div class="post-card__excerpt">
            <?php the_excerpt(); ?>
        </div>

        <footer class="post-card__footer">
            <?php aqualuxe_entry_footer(); ?>
            
            <a href="<?php the_permalink(); ?>" class="post-card__read-more">
                <?php esc_html_e('Read more', 'aqualuxe'); ?>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                    <path fill-rule="evenodd" d="M12.97 3.97a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 11-1.06-1.06l6.22-6.22H3a.75.75 0 010-1.5h16.19l-6.22-6.22a.75.75 0 010-1.06z" clip-rule="evenodd" />
                </svg>
            </a>
        </footer>
    </div>
</article>