<?php
/**
 * Template part for displaying posts
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('entry mb-8 lg:mb-12'); ?>>
    
    <?php if (has_post_thumbnail()) : ?>
        <div class="entry-thumbnail mb-6">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail('aqualuxe-featured-large', array(
                    'class' => 'w-full h-auto rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200',
                    'alt' => get_the_title()
                ));
                ?>
            </a>
        </div>
    <?php endif; ?>
    
    <header class="entry-header mb-4">
        <?php
        if (is_singular()) :
            the_title('<h1 class="entry-title text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-secondary-100">', '</h1>');
        else :
            the_title('<h2 class="entry-title text-xl lg:text-2xl font-bold text-secondary-900 dark:text-secondary-100 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        endif;
        ?>
        
        <?php if ('post' === get_post_type()) : ?>
            <div class="entry-meta flex flex-wrap items-center gap-4 mt-3 text-sm text-secondary-600 dark:text-secondary-400">
                <?php
                aqualuxe_posted_on();
                aqualuxe_posted_by();
                ?>
                
                <?php if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) : ?>
                    <span class="comments-link">
                        <?php
                        comments_popup_link(
                            sprintf(
                                wp_kses(
                                    /* translators: %s: post title */
                                    __('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe'),
                                    array(
                                        'span' => array(
                                            'class' => array(),
                                        ),
                                    )
                                ),
                                wp_kses_post(get_the_title())
                            )
                        );
                        ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </header>
    
    <div class="entry-content">
        <?php
        if (is_singular()) :
            the_content(sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. */
                    __('Continue reading %s <span class="meta-nav">&rarr;</span>', 'aqualuxe'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                the_title('"', '"', false)
            ));
            
            wp_link_pages(array(
                'before' => '<div class="page-links mt-8 flex flex-wrap items-center gap-2"><span class="page-links-title font-medium text-secondary-900 dark:text-secondary-100 mr-4">' . __('Pages:', 'aqualuxe') . '</span>',
                'after'  => '</div>',
                'link_before' => '<span class="page-number">',
                'link_after'  => '</span>',
            ));
        else :
            the_excerpt();
        endif;
        ?>
    </div>
    
    <?php if (!is_singular()) : ?>
        <div class="entry-footer mt-6">
            <a href="<?php the_permalink(); ?>" class="read-more inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors duration-200">
                <?php esc_html_e('Read More', 'aqualuxe'); ?>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    <?php endif; ?>
    
    <?php if (is_singular() && get_edit_post_link()) : ?>
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