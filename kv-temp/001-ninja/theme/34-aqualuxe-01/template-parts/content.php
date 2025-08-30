<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('mb-12'); ?>>
    <header class="entry-header mb-4">
        <?php
        if (is_singular()) :
            the_title('<h1 class="entry-title text-3xl font-bold">', '</h1>');
        else :
            the_title('<h2 class="entry-title text-2xl font-bold"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        endif;

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

    <?php if (has_post_thumbnail() && !is_singular()) : ?>
        <div class="entry-thumbnail mb-4">
            <a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded">
                <?php the_post_thumbnail('large', ['class' => 'w-full h-auto hover:scale-105 transition-transform duration-300']); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="entry-content prose max-w-none">
        <?php
        if (is_singular()) :
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

            wp_link_pages(
                array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                    'after'  => '</div>',
                )
            );
        else :
            the_excerpt();
        ?>
            <div class="mt-4">
                <a href="<?php the_permalink(); ?>" class="inline-block bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded transition-colors">
                    <?php esc_html_e('Read More', 'aqualuxe'); ?>
                </a>
            </div>
        <?php
        endif;
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer mt-4 text-sm text-gray-600">
        <?php aqualuxe_entry_footer(); ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->