<?php
/**
 * Template part for displaying posts in grid layout
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('grid-item'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail mb-4">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php the_post_thumbnail('medium_large', array('class' => 'w-full h-64 object-cover rounded')); ?>
            </a>
        </div>
    <?php endif; ?>

    <header class="entry-header mb-3">
        <?php
        the_title('<h2 class="entry-title text-xl font-bold"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');

        if ('post' === get_post_type()) :
            ?>
            <div class="entry-meta text-sm text-gray-600 mt-2">
                <?php
                aqualuxe_posted_on();
                aqualuxe_posted_by();
                ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <div class="entry-content prose max-w-none">
        <?php
        the_excerpt();
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer mt-4">
        <a href="<?php the_permalink(); ?>" class="text-primary hover:text-primary-dark font-medium inline-flex items-center">
            <?php esc_html_e('Read More', 'aqualuxe'); ?>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </a>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->