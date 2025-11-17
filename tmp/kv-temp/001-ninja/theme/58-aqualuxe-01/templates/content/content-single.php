<?php
/**
 * Template part for displaying single posts
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
$show_featured_image = isset($options['show_featured_image']) ? $options['show_featured_image'] : true;
$featured_image_style = isset($options['featured_image_style']) ? $options['featured_image_style'] : 'large';
$show_post_meta = isset($options['show_post_meta']) ? $options['show_post_meta'] : true;

// Get post meta elements
$post_meta_elements = isset($options['post_meta_elements']) ? $options['post_meta_elements'] : array('date', 'author', 'categories', 'comments');

// Set featured image class
$featured_image_class = 'post-thumbnail post-thumbnail-' . $featured_image_style;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if ($featured_image_style !== 'background') : ?>
        <header class="entry-header">
            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>

            <?php if ($show_post_meta) : ?>
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
    <?php endif; ?>

    <?php if ($show_featured_image && has_post_thumbnail() && $featured_image_style !== 'background') : ?>
        <div class="<?php echo esc_attr($featured_image_class); ?>">
            <?php 
            if ($featured_image_style === 'banner') {
                echo '<div class="banner-overlay"></div>';
            }
            the_post_thumbnail('full', array('class' => 'post-thumbnail-image')); 
            ?>
        </div>
    <?php endif; ?>

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
                'link_before' => '<span class="page-number">',
                'link_after'  => '</span>',
            )
        );
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php if (in_array('tags', $post_meta_elements) && has_tag()) : ?>
            <div class="post-tags">
                <span class="tags-title"><?php esc_html_e('Tags:', 'aqualuxe'); ?></span>
                <span class="meta-icon"><span class="icon-tag"></span></span>
                <?php the_tags('', ', ', ''); ?>
            </div>
        <?php endif; ?>

        <?php
        // Edit post link
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