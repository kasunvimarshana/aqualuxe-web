<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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
$post_layout = isset($options['post_layout']) ? $options['post_layout'] : 'right-sidebar';
$show_featured_image = isset($options['show_featured_image']) ? $options['show_featured_image'] : true;
$featured_image_style = isset($options['featured_image_style']) ? $options['featured_image_style'] : 'large';
$show_post_meta = isset($options['show_post_meta']) ? $options['show_post_meta'] : true;
$show_author_bio = isset($options['show_author_bio']) ? $options['show_author_bio'] : true;
$show_related_posts = isset($options['show_related_posts']) ? $options['show_related_posts'] : true;
$related_posts_count = isset($options['related_posts_count']) ? $options['related_posts_count'] : 3;
$show_post_nav = isset($options['show_post_nav']) ? $options['show_post_nav'] : true;
$post_nav_style = isset($options['post_nav_style']) ? $options['post_nav_style'] : 'simple';
$show_comments = isset($options['show_comments']) ? $options['show_comments'] : true;
$enable_social_sharing = isset($options['enable_social_sharing']) ? $options['enable_social_sharing'] : true;
$social_sharing_position = isset($options['social_sharing_position']) ? $options['social_sharing_position'] : 'after_content';

// Set layout classes
$content_class = 'content-area';
$sidebar_class = 'sidebar-area';

if ($post_layout === 'left-sidebar') {
    $content_class .= ' has-left-sidebar';
    $sidebar_class .= ' left-sidebar';
} elseif ($post_layout === 'right-sidebar') {
    $content_class .= ' has-right-sidebar';
    $sidebar_class .= ' right-sidebar';
} elseif ($post_layout === 'no-sidebar') {
    $content_class .= ' no-sidebar';
} elseif ($post_layout === 'full-width') {
    $content_class .= ' full-width';
}

// Set featured image class
$featured_image_class = 'post-thumbnail post-thumbnail-' . $featured_image_style;

get_header();
?>

<?php if ($show_featured_image && $featured_image_style === 'background' && has_post_thumbnail()) : ?>
    <div class="featured-image-background" style="background-image: url(<?php the_post_thumbnail_url('full'); ?>);">
        <div class="container">
            <div class="featured-image-content">
                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                <?php if ($show_post_meta) : ?>
                    <div class="entry-meta">
                        <?php aqualuxe_posted_on(); ?>
                        <?php aqualuxe_posted_by(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="container">
    <div class="content-sidebar-wrap">
        <main id="primary" class="<?php echo esc_attr($content_class); ?>">
            <?php
            while (have_posts()) :
                the_post();

                // Display social sharing before content if enabled
                if ($enable_social_sharing && ($social_sharing_position === 'before_content' || $social_sharing_position === 'both')) {
                    if (function_exists('aqualuxe_social_sharing')) {
                        aqualuxe_social_sharing();
                    }
                }

                get_template_part('templates/content/content', 'single');

                // Display social sharing after content if enabled
                if ($enable_social_sharing && ($social_sharing_position === 'after_content' || $social_sharing_position === 'both')) {
                    if (function_exists('aqualuxe_social_sharing')) {
                        aqualuxe_social_sharing();
                    }
                }

                // Display author bio if enabled
                if ($show_author_bio) {
                    get_template_part('templates/parts/author', 'bio');
                }

                // Display related posts if enabled
                if ($show_related_posts) {
                    get_template_part('templates/parts/related', 'posts', array(
                        'count' => $related_posts_count,
                    ));
                }

                // Display post navigation if enabled
                if ($show_post_nav) {
                    get_template_part('templates/parts/post', 'navigation', array(
                        'style' => $post_nav_style,
                    ));
                }

                // If comments are open or we have at least one comment, load up the comment template.
                if ($show_comments && (comments_open() || get_comments_number())) {
                    comments_template();
                }
            endwhile; // End of the loop.
            ?>
        </main><!-- #primary -->

        <?php if ($post_layout === 'left-sidebar' || $post_layout === 'right-sidebar') : ?>
            <?php get_sidebar(); ?>
        <?php endif; ?>
    </div><!-- .content-sidebar-wrap -->
</div><!-- .container -->

<?php if ($enable_social_sharing && $social_sharing_position === 'floating' && function_exists('aqualuxe_social_sharing_floating')) : ?>
    <?php aqualuxe_social_sharing_floating(); ?>
<?php endif; ?>

<?php
get_footer();