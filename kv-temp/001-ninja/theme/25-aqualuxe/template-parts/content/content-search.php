<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('mb-8 pb-8 border-b border-gray-200 last:border-0'); ?>>
    <header class="entry-header mb-3">
        <?php the_title(sprintf('<h2 class="entry-title text-xl font-bold"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>

        <?php if ('post' === get_post_type()) : ?>
            <div class="entry-meta text-sm text-gray-600 mt-2">
                <?php
                aqualuxe_posted_on();
                aqualuxe_posted_by();
                ?>
            </div><!-- .entry-meta -->
        <?php elseif (get_post_type() !== 'page') : ?>
            <div class="entry-meta text-sm text-gray-600 mt-2">
                <span class="post-type">
                    <?php echo esc_html(get_post_type_object(get_post_type())->labels->singular_name); ?>
                </span>
            </div>
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail mb-4">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php the_post_thumbnail('medium', array('class' => 'w-full h-auto rounded')); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="entry-summary prose max-w-none">
        <?php the_excerpt(); ?>
    </div><!-- .entry-summary -->

    <footer class="entry-footer mt-3">
        <a href="<?php the_permalink(); ?>" class="text-primary hover:text-primary-dark font-medium inline-flex items-center">
            <?php esc_html_e('Read More', 'aqualuxe'); ?>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </a>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->