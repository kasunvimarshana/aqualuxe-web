<?php
/**
 * Template part for displaying post navigation
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get navigation style from args or default to theme option
$options = get_option('aqualuxe_options', array());
$args = wp_parse_args($args, array(
    'style' => isset($options['post_nav_style']) ? $options['post_nav_style'] : 'simple',
));

// Set navigation class based on style
$nav_class = 'post-navigation post-navigation-' . $args['style'];

// Get previous and next posts
$prev_post = get_previous_post();
$next_post = get_next_post();

// If no previous or next post, return
if (!$prev_post && !$next_post) {
    return;
}
?>

<nav class="<?php echo esc_attr($nav_class); ?>">
    <h2 class="screen-reader-text"><?php esc_html_e('Post navigation', 'aqualuxe'); ?></h2>
    
    <div class="nav-links">
        <?php if ($prev_post) : ?>
            <div class="nav-previous">
                <?php if ($args['style'] === 'with_image' || $args['style'] === 'fancy') : ?>
                    <?php if (has_post_thumbnail($prev_post->ID)) : ?>
                        <div class="post-nav-thumbnail">
                            <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" rel="prev">
                                <?php echo get_the_post_thumbnail($prev_post->ID, 'thumbnail', array('class' => 'nav-thumbnail-image')); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <div class="post-nav-content">
                    <span class="nav-subtitle">
                        <span class="icon-arrow-left"></span>
                        <?php esc_html_e('Previous Post', 'aqualuxe'); ?>
                    </span>
                    
                    <?php if ($args['style'] === 'with_title' || $args['style'] === 'fancy') : ?>
                        <span class="nav-title"><?php echo esc_html(get_the_title($prev_post->ID)); ?></span>
                    <?php endif; ?>
                </div>
                
                <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" rel="prev" class="nav-link-overlay">
                    <span class="screen-reader-text">
                        <?php
                        /* translators: %s: Previous post title */
                        printf(esc_html__('Previous Post: %s', 'aqualuxe'), esc_html(get_the_title($prev_post->ID)));
                        ?>
                    </span>
                </a>
            </div>
        <?php endif; ?>

        <?php if ($next_post) : ?>
            <div class="nav-next">
                <?php if ($args['style'] === 'with_image' || $args['style'] === 'fancy') : ?>
                    <?php if (has_post_thumbnail($next_post->ID)) : ?>
                        <div class="post-nav-thumbnail">
                            <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" rel="next">
                                <?php echo get_the_post_thumbnail($next_post->ID, 'thumbnail', array('class' => 'nav-thumbnail-image')); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <div class="post-nav-content">
                    <span class="nav-subtitle">
                        <?php esc_html_e('Next Post', 'aqualuxe'); ?>
                        <span class="icon-arrow-right"></span>
                    </span>
                    
                    <?php if ($args['style'] === 'with_title' || $args['style'] === 'fancy') : ?>
                        <span class="nav-title"><?php echo esc_html(get_the_title($next_post->ID)); ?></span>
                    <?php endif; ?>
                </div>
                
                <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" rel="next" class="nav-link-overlay">
                    <span class="screen-reader-text">
                        <?php
                        /* translators: %s: Next post title */
                        printf(esc_html__('Next Post: %s', 'aqualuxe'), esc_html(get_the_title($next_post->ID)));
                        ?>
                    </span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</nav>