<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();

$sidebar_position = aqualuxe_get_option('sidebar_position', 'right');
$has_sidebar = $sidebar_position !== 'none';
$blog_layout = aqualuxe_get_option('blog_layout', 'grid');
$columns = aqualuxe_get_option('archive_columns', '3');
?>

<div class="container">
    <div class="row <?php echo $has_sidebar ? 'has-sidebar' : 'no-sidebar'; ?> sidebar-<?php echo esc_attr($sidebar_position); ?>">
        <main id="primary" class="site-main">
            <?php if (have_posts()) : ?>

                <div class="aqualuxe-posts aqualuxe-posts-<?php echo esc_attr($blog_layout); ?> aqualuxe-posts-columns-<?php echo esc_attr($columns); ?>">
                    <?php
                    /* Start the Loop */
                    while (have_posts()) :
                        the_post();

                        /*
                         * Include the Post-Type-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                         */
                        get_template_part('template-parts/content', get_post_type());

                    endwhile;
                    ?>
                </div>

                <?php
                the_posts_pagination(array(
                    'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"></path></svg> <span class="screen-reader-text">' . __('Previous page', 'aqualuxe') . '</span>',
                    'next_text' => '<span class="screen-reader-text">' . __('Next page', 'aqualuxe') . '</span> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"></path></svg>',
                ));

            else :

                get_template_part('template-parts/content', 'none');

            endif;
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