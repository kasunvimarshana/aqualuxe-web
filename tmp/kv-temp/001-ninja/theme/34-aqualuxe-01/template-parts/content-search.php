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

<article id="post-<?php the_ID(); ?>" <?php post_class('mb-8 pb-8 border-b border-gray-200'); ?>>
    <header class="entry-header mb-4">
        <?php the_title(sprintf('<h2 class="entry-title text-xl font-bold"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>

        <?php if ('post' === get_post_type()) : ?>
            <div class="entry-meta text-sm text-gray-600 mt-2">
                <?php
                aqualuxe_posted_on();
                aqualuxe_posted_by();
                ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php if (has_post_thumbnail()) : ?>
        <div class="entry-thumbnail mb-4">
            <a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded">
                <?php the_post_thumbnail('medium', ['class' => 'w-full h-auto hover:scale-105 transition-transform duration-300']); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="entry-summary prose max-w-none">
        <?php the_excerpt(); ?>
    </div><!-- .entry-summary -->

    <footer class="entry-footer mt-4">
        <a href="<?php the_permalink(); ?>" class="inline-block bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded transition-colors">
            <?php esc_html_e('Read More', 'aqualuxe'); ?>
        </a>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->