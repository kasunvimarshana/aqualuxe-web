<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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

    <?php if ( have_posts() ) : ?>

        <header class="page-header">
            <h1 class="page-title">
                <?php
                /* translators: %s: search query. */
                printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span>' . get_search_query() . '</span>' );
                ?>
            </h1>
        </header><!-- .page-header -->

        <div class="search-results-wrapper">
            <?php
            /* Start the Loop */
            while ( have_posts() ) :
                the_post();

                /**
                 * Run the loop for the search to output the results.
                 * If you want to overload this in a child theme then include a file
                 * called content-search.php and that will be used instead.
                 */
                get_template_part( 'templates/content', 'search' );

            endwhile;
            ?>
        </div><!-- .search-results-wrapper -->

        <?php
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