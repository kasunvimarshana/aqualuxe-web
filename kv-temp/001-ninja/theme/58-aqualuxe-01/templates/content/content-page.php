<?php
/**
 * Template part for displaying page content in page.php
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

// Page options
$show_featured_image = isset($options['show_featured_image']) ? $options['show_featured_image'] : true;
$featured_image_style = isset($options['featured_image_style']) ? $options['featured_image_style'] : 'large';

// Set featured image class
$featured_image_class = 'post-thumbnail post-thumbnail-' . $featured_image_style;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if ($featured_image_style !== 'background') : ?>
        <header class="entry-header">
            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
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
        the_content();

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

    <?php if (get_edit_post_link()) : ?>
        <footer class="entry-footer">
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
                '<div class="edit-link">',
                '</div>'
            );
            ?>
        </footer><!-- .entry-footer -->
    <?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->