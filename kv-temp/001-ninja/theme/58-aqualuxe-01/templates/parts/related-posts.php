<?php
/**
 * Template part for displaying related posts
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

// Get related posts count from args or default to theme option
$args = wp_parse_args($args, array(
    'count' => isset($options['related_posts_count']) ? $options['related_posts_count'] : 3,
));

// Get current post ID and categories
$post_id = get_the_ID();
$categories = get_the_category($post_id);

// If no categories, return
if (empty($categories)) {
    return;
}

// Get category IDs
$category_ids = array();
foreach ($categories as $category) {
    $category_ids[] = $category->term_id;
}

// Query related posts
$related_query = new WP_Query(array(
    'category__in' => $category_ids,
    'post__not_in' => array($post_id),
    'posts_per_page' => $args['count'],
    'orderby' => 'rand',
));

// If no related posts, return
if (!$related_query->have_posts()) {
    return;
}
?>

<div class="related-posts">
    <h3 class="related-posts-title"><?php esc_html_e('Related Posts', 'aqualuxe'); ?></h3>
    
    <div class="related-posts-grid columns-<?php echo esc_attr($args['count']); ?>">
        <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
            <article class="related-post">
                <div class="related-post-inner">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="related-post-thumbnail">
                            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                <?php the_post_thumbnail('medium', array('class' => 'related-thumbnail-image')); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="related-post-content">
                        <header class="related-post-header">
                            <?php the_title('<h4 class="related-post-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h4>'); ?>
                            
                            <div class="related-post-meta">
                                <?php aqualuxe_posted_on(); ?>
                            </div>
                        </header>

                        <div class="related-post-excerpt">
                            <?php the_excerpt(); ?>
                        </div>

                        <div class="related-post-link">
                            <a href="<?php the_permalink(); ?>" class="read-more">
                                <?php esc_html_e('Read More', 'aqualuxe'); ?>
                                <span class="icon-arrow-right"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</div>

<?php
// Reset post data
wp_reset_postdata();