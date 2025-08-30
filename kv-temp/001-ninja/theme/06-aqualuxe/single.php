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

$sidebar_position = get_theme_mod( 'aqualuxe_content_layout', 'right-sidebar' );
$has_sidebar = ( $sidebar_position !== 'no-sidebar' ) && is_active_sidebar( 'sidebar-1' );
$content_class = $has_sidebar ? 'col-lg-8' : 'col-lg-12';

// Add 'order-first' class if sidebar is on the right
if ( $has_sidebar && $sidebar_position === 'left-sidebar' ) {
    $content_class .= ' order-lg-2';
}
?>

<div id="primary" class="content-area <?php echo esc_attr( $content_class ); ?>">
    <main id="main" class="site-main">

    <?php
    while ( have_posts() ) :
        the_post();

        get_template_part( 'templates/content', 'single' );

        // If comments are open or we have at least one comment, load up the comment template.
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;

        // Previous/next post navigation.
        the_post_navigation(
            array(
                'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
            )
        );

    endwhile; // End of the loop.
    ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
if ( $has_sidebar ) :
    $sidebar_class = 'col-lg-4';
    if ( $sidebar_position === 'left-sidebar' ) {
        $sidebar_class .= ' order-lg-1';
    }
    ?>
    <aside id="secondary" class="widget-area <?php echo esc_attr( $sidebar_class ); ?>">
        <?php dynamic_sidebar( 'sidebar-1' ); ?>
    </aside><!-- #secondary -->
    <?php
endif;

get_footer();