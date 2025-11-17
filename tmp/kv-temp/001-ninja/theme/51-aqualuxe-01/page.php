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

get_header();

$sidebar_position = aqualuxe_get_option('sidebar_position', 'right');
$has_sidebar = $sidebar_position !== 'none';
?>

<div class="container">
    <div class="row <?php echo $has_sidebar ? 'has-sidebar' : 'no-sidebar'; ?> sidebar-<?php echo esc_attr($sidebar_position); ?>">
        <main id="primary" class="site-main">
            <?php
            while (have_posts()) :
                the_post();

                get_template_part('template-parts/content', 'page');

                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;

            endwhile; // End of the loop.
            ?>
        </main><!-- #main -->

        <?php if ($has_sidebar) : ?>
            <aside id="secondary" class="widget-area">
                <?php do_action('aqualuxe_sidebar'); ?>
            </aside><!-- #secondary -->
        <?php endif; ?>
    </div>
</div>

<?php
get_footer();