<?php
/**
 * Template part for displaying page content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
    
    <?php if (!is_front_page()) : ?>
        <header class="entry-header mb-8">
            <?php the_title('<h1 class="entry-title text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-secondary-100">', '</h1>'); ?>
        </header>
    <?php endif; ?>
    
    <?php if (has_post_thumbnail() && !is_front_page()) : ?>
        <div class="entry-thumbnail mb-8">
            <?php
            the_post_thumbnail('aqualuxe-featured-large', array(
                'class' => 'w-full h-auto rounded-lg shadow-md',
                'alt' => get_the_title()
            ));
            ?>
        </div>
    <?php endif; ?>
    
    <div class="entry-content">
        <?php
        the_content();
        
        wp_link_pages(array(
            'before' => '<div class="page-links mt-8 flex flex-wrap items-center gap-2"><span class="page-links-title font-medium text-secondary-900 dark:text-secondary-100 mr-4">' . __('Pages:', 'aqualuxe') . '</span>',
            'after'  => '</div>',
            'link_before' => '<span class="page-number">',
            'link_after'  => '</span>',
        ));
        ?>
    </div>
    
    <?php if (get_edit_post_link()) : ?>
        <footer class="entry-footer mt-8 pt-6 border-t border-secondary-200 dark:border-secondary-700">
            <?php
            edit_post_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __('Edit <span class="screen-reader-text">%s</span>', 'aqualuxe'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post(get_the_title())
                ),
                '<div class="edit-link text-sm">',
                '</div>'
            );
            ?>
        </footer>
    <?php endif; ?>
    
</article><!-- #post-<?php the_ID(); ?> -->