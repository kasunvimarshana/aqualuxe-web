<?php
/**
 * Template part for displaying posts in single.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-post-content'); ?>>
    <?php
    // Featured Image
    if (has_post_thumbnail() && get_theme_mod('aqualuxe_show_single_featured_image', true)) {
        ?>
        <div class="entry-thumbnail">
            <?php the_post_thumbnail('full', array('class' => 'img-fluid')); ?>
        </div><!-- .entry-thumbnail -->
        <?php
    }
    ?>

    <header class="entry-header">
        <?php
        if ('post' === get_post_type()) {
            ?>
            <div class="entry-meta">
                <?php
                aqualuxe_posted_on();
                aqualuxe_posted_by();
                ?>
            </div><!-- .entry-meta -->
            <?php
        }
        
        if (get_theme_mod('aqualuxe_show_single_title', true)) {
            the_title('<h1 class="entry-title">', '</h1>');
        }
        ?>
    </header><!-- .entry-header -->

    <div class="entry-content">
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

    <footer class="entry-footer">
        <?php
        // Display categories and tags
        if (get_theme_mod('aqualuxe_show_single_categories', true) || get_theme_mod('aqualuxe_show_single_tags', true)) {
            ?>
            <div class="entry-taxonomy">
                <?php
                // Categories
                if (get_theme_mod('aqualuxe_show_single_categories', true)) {
                    $categories_list = get_the_category_list(esc_html__(', ', 'aqualuxe'));
                    if ($categories_list) {
                        ?>
                        <div class="entry-categories">
                            <span class="cat-links">
                                <i class="fas fa-folder"></i>
                                <?php echo wp_kses_post($categories_list); ?>
                            </span>
                        </div>
                        <?php
                    }
                }
                
                // Tags
                if (get_theme_mod('aqualuxe_show_single_tags', true)) {
                    $tags_list = get_the_tag_list('', esc_html__(', ', 'aqualuxe'));
                    if ($tags_list) {
                        ?>
                        <div class="entry-tags">
                            <span class="tag-links">
                                <i class="fas fa-tags"></i>
                                <?php echo wp_kses_post($tags_list); ?>
                            </span>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <?php
        }
        
        // Social Sharing
        if (get_theme_mod('aqualuxe_show_single_sharing', true)) {
            ?>
            <div class="entry-sharing">
                <h4><?php echo esc_html__('Share This Post', 'aqualuxe'); ?></h4>
                <?php aqualuxe_social_sharing(); ?>
            </div>
            <?php
        }
        
        // Edit Post Link
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
            '<div class="edit-link">',
            '</div>'
        );
        ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->