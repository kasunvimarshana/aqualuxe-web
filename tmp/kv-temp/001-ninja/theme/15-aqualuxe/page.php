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
 */

get_header();

$sidebar_position = get_theme_mod('aqualuxe_page_sidebar_position', 'none');
$container_class = 'container mx-auto px-4 py-12';
$content_class = 'site-main';
$has_sidebar = is_active_sidebar('sidebar-page') && $sidebar_position !== 'none';

if ($has_sidebar) {
    $content_class .= ' lg:w-2/3';
    if ($sidebar_position === 'left') {
        $content_class .= ' lg:order-2';
    } else {
        $content_class .= ' lg:order-1';
    }
}
?>

<div class="<?php echo esc_attr($container_class); ?>">
    <div class="flex flex-wrap lg:flex-nowrap <?php echo $has_sidebar ? 'lg:space-x-8' : ''; ?>">
        <main id="primary" class="<?php echo esc_attr($content_class); ?>">
            <?php
            while (have_posts()) :
                the_post();

                get_template_part('templates/components/content', 'page');

                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;

            endwhile; // End of the loop.
            ?>
        </main><!-- #main -->

        <?php if ($has_sidebar) : ?>
            <aside id="secondary" class="widget-area lg:w-1/3 <?php echo $sidebar_position === 'left' ? 'lg:order-1' : 'lg:order-2'; ?> mt-8 lg:mt-0">
                <?php dynamic_sidebar('sidebar-page'); ?>
            </aside><!-- #secondary -->
        <?php endif; ?>
    </div>
</div>

<?php
get_footer();