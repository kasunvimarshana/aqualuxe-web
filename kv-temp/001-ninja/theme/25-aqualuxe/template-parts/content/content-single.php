<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header mb-8">
        <?php the_title('<h1 class="entry-title text-3xl lg:text-4xl font-bold">', '</h1>'); ?>
        
        <div class="entry-meta flex flex-wrap items-center gap-4 text-sm text-gray-600 mt-4">
            <?php
            aqualuxe_posted_on();
            aqualuxe_posted_by();
            
            // Estimated reading time
            if (function_exists('aqualuxe_reading_time')) {
                aqualuxe_reading_time();
            }
            ?>
        </div><!-- .entry-meta -->
    </header><!-- .entry-header -->

    <?php if (has_post_thumbnail() && !get_post_meta(get_the_ID(), '_aqualuxe_hide_featured_image', true)) : ?>
        <div class="post-thumbnail mb-8">
            <?php the_post_thumbnail('full', array('class' => 'w-full h-auto rounded')); ?>
            
            <?php if (get_post(get_post_thumbnail_id())->post_excerpt) : ?>
                <div class="post-thumbnail-caption text-sm text-gray-600 mt-2 italic">
                    <?php echo wp_kses_post(get_post(get_post_thumbnail_id())->post_excerpt); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="entry-content prose max-w-none">
        <?php
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
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer mt-8 pt-6 border-t border-gray-200">
        <?php aqualuxe_entry_footer(); ?>
        
        <?php if (function_exists('aqualuxe_social_sharing') && get_theme_mod('aqualuxe_enable_social_sharing', true)) : ?>
            <div class="social-sharing mt-6">
                <h3 class="text-lg font-bold mb-3"><?php esc_html_e('Share This Post', 'aqualuxe'); ?></h3>
                <?php aqualuxe_social_sharing(); ?>
            </div>
        <?php endif; ?>
        
        <?php
        // Author bio
        if (get_theme_mod('aqualuxe_show_author_bio', true) && get_the_author_meta('description')) :
            get_template_part('template-parts/content/author-bio');
        endif;
        ?>
        
        <?php
        // Related posts
        if (function_exists('aqualuxe_related_posts') && get_theme_mod('aqualuxe_show_related_posts', true)) :
            aqualuxe_related_posts();
        endif;
        ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->