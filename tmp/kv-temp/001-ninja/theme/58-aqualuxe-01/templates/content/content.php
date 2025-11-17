<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get theme options
$options = get_option('aqualuxe_options', array());

// Post options
$archive_display = isset($options['archive_display']) ? $options['archive_display'] : 'grid';
$show_featured_image = isset($options['show_featured_image']) ? $options['show_featured_image'] : true;
$show_post_meta = isset($options['show_post_meta']) ? $options['show_post_meta'] : true;
$excerpt_length = isset($options['excerpt_length']) ? $options['excerpt_length'] : 55;
$read_more_text = isset($options['read_more_text']) ? $options['read_more_text'] : __('Read More', 'aqualuxe');

// Set post classes
$post_classes = array('post-item');
$post_classes[] = 'post-' . $archive_display . '-item';

// Get post meta elements
$post_meta_elements = isset($options['post_meta_elements']) ? $options['post_meta_elements'] : array('date', 'author', 'categories', 'comments');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($post_classes); ?>>
    <div class="post-inner">
        <?php if ($show_featured_image && has_post_thumbnail()) : ?>
            <div class="post-thumbnail">
                <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                    <?php
                    if ($archive_display === 'grid' || $archive_display === 'masonry') {
                        the_post_thumbnail('medium_large', array('class' => 'post-thumbnail-image'));
                    } else {
                        the_post_thumbnail('large', array('class' => 'post-thumbnail-image'));
                    }
                    ?>
                </a>
                <?php if (is_sticky()) : ?>
                    <span class="sticky-post"><?php esc_html_e('Featured', 'aqualuxe'); ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="post-content">
            <header class="entry-header">
                <?php
                if (is_singular()) :
                    the_title('<h1 class="entry-title">', '</h1>');
                else :
                    the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                endif;

                if ($show_post_meta) :
                ?>
                    <div class="entry-meta">
                        <?php
                        if (in_array('date', $post_meta_elements)) {
                            aqualuxe_posted_on();
                        }
                        
                        if (in_array('author', $post_meta_elements)) {
                            aqualuxe_posted_by();
                        }
                        
                        if (in_array('categories', $post_meta_elements) && has_category()) {
                            echo '<span class="post-categories">';
                            echo '<span class="meta-icon"><span class="icon-folder"></span></span>';
                            the_category(', ');
                            echo '</span>';
                        }
                        
                        if (in_array('comments', $post_meta_elements) && comments_open()) {
                            echo '<span class="post-comments">';
                            echo '<span class="meta-icon"><span class="icon-comment"></span></span>';
                            comments_popup_link(
                                __('No Comments', 'aqualuxe'),
                                __('1 Comment', 'aqualuxe'),
                                __('% Comments', 'aqualuxe')
                            );
                            echo '</span>';
                        }
                        
                        if (in_array('read_time', $post_meta_elements) && function_exists('aqualuxe_reading_time')) {
                            aqualuxe_reading_time();
                        }
                        ?>
                    </div><!-- .entry-meta -->
                <?php endif; ?>
            </header><!-- .entry-header -->

            <div class="entry-content">
                <?php
                if (is_singular()) {
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
                } else {
                    the_excerpt();
                    echo '<div class="read-more-link">';
                    echo '<a href="' . esc_url(get_permalink()) . '" class="read-more">' . esc_html($read_more_text) . ' <span class="icon-arrow-right"></span></a>';
                    echo '</div>';
                }
                ?>
            </div><!-- .entry-content -->

            <footer class="entry-footer">
                <?php if (in_array('tags', $post_meta_elements) && has_tag()) : ?>
                    <div class="post-tags">
                        <span class="meta-icon"><span class="icon-tag"></span></span>
                        <?php the_tags('', ', ', ''); ?>
                    </div>
                <?php endif; ?>
            </footer><!-- .entry-footer -->
        </div><!-- .post-content -->
    </div><!-- .post-inner -->
</article><!-- #post-<?php the_ID(); ?> -->