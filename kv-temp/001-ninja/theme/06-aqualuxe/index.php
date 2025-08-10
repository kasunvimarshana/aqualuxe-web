<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
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
    if ( have_posts() ) :

        if ( is_home() && ! is_front_page() ) :
            ?>
            <header class="page-header">
                <h1 class="page-title"><?php single_post_title(); ?></h1>
            </header>
            <?php
        endif;

        /* Start the Loop */
        while ( have_posts() ) :
            the_post();

            /*
             * Include the Post-Type-specific template for the content.
             * If you want to override this in a child theme, then include a file
             * called content-___.php (where ___ is the Post Type name) and that will be used instead.
             */
            get_template_part( 'templates/content', get_post_type() );

        endwhile;

        aqualuxe_pagination();

    else :

        get_template_part( 'templates/content', 'none' );

    endif;
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