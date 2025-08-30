<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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

                get_template_part('template-parts/content', 'single');

                do_action('aqualuxe_single_post');

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