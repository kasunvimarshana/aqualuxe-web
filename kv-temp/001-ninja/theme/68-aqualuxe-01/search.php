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

$layout = \AquaLuxe\Helpers\Utils::get_theme_layout();
$sidebar_position = strpos( $layout, 'left' ) !== false ? 'left' : 'right';
$container_class = 'container';

if ( $layout === 'full-width' ) {
    $container_class .= ' full-width';
}
?>

<div class="<?php echo esc_attr( $container_class ); ?>">
    <div class="row">
        <?php if ( $layout !== 'full-width' && $sidebar_position === 'left' ) : ?>
            <div class="sidebar-container">
                <?php get_sidebar(); ?>
            </div>
        <?php endif; ?>

        <div id="primary" class="content-area">
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

                <?php
                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();

                    /**
                     * Run the loop for the search to output the results.
                     * If you want to overload this in a child theme then include a file
                     * called content-search.php and that will be used instead.
                     */
                    get_template_part( 'templates/parts/content/content', 'search' );

                endwhile;

                the_posts_navigation();

            else :

                get_template_part( 'templates/parts/content/content', 'none' );

            endif;
            ?>

            </main><!-- #main -->
        </div><!-- #primary -->

        <?php if ( $layout !== 'full-width' && $sidebar_position === 'right' ) : ?>
            <div class="sidebar-container">
                <?php get_sidebar(); ?>
            </div>
        <?php endif; ?>
    </div><!-- .row -->
</div><!-- .container -->

<?php
get_footer();