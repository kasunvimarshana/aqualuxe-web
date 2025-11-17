<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
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
$page_layout = isset($options['page_layout']) ? $options['page_layout'] : 'right-sidebar';
$show_featured_image = isset($options['show_featured_image']) ? $options['show_featured_image'] : true;
$featured_image_style = isset($options['featured_image_style']) ? $options['featured_image_style'] : 'large';
$show_comments = isset($options['show_comments']) ? $options['show_comments'] : true;

// Check for page-specific layout override
$page_specific_layout = get_post_meta(get_the_ID(), 'aqualuxe_page_layout', true);
if (!empty($page_specific_layout) && $page_specific_layout !== 'default') {
    $page_layout = $page_specific_layout;
}

// Set layout classes
$content_class = 'content-area';
$sidebar_class = 'sidebar-area';

if ($page_layout === 'left-sidebar') {
    $content_class .= ' has-left-sidebar';
    $sidebar_class .= ' left-sidebar';
} elseif ($page_layout === 'right-sidebar') {
    $content_class .= ' has-right-sidebar';
    $sidebar_class .= ' right-sidebar';
} elseif ($page_layout === 'no-sidebar') {
    $content_class .= ' no-sidebar';
} elseif ($page_layout === 'full-width') {
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

                get_template_part('templates/content/content', 'page');

                // If comments are open or we have at least one comment, load up the comment template.
                if ($show_comments && (comments_open() || get_comments_number())) {
                    comments_template();
                }
            endwhile; // End of the loop.
            ?>
        </main><!-- #primary -->

        <?php if ($page_layout === 'left-sidebar' || $page_layout === 'right-sidebar') : ?>
            <?php get_sidebar('page'); ?>
        <?php endif; ?>
    </div><!-- .content-sidebar-wrap -->
</div><!-- .container -->

<?php
get_footer();