<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package AquaLuxe
 */

get_header();

$sidebar_position = get_theme_mod( 'aqualuxe_search_sidebar', 'right' );
$container_class = 'container mx-auto px-4';
$content_class = 'site-content py-12';

if ( $sidebar_position === 'none' ) {
    $content_class .= ' max-w-4xl mx-auto';
} else {
    $content_class .= ' grid grid-cols-1 lg:grid-cols-12 gap-8';
}
?>

<main id="primary" class="<?php echo esc_attr( $container_class ); ?>">
    <div class="<?php echo esc_attr( $content_class ); ?>">
        <?php if ( $sidebar_position === 'left' ) : ?>
            <aside id="secondary" class="widget-area lg:col-span-3">
                <?php get_sidebar(); ?>
            </aside>
        <?php endif; ?>

        <div class="content-area <?php echo $sidebar_position === 'none' ? '' : 'lg:col-span-9'; ?>">
            <?php if ( have_posts() ) : ?>

                <header class="page-header mb-8">
                    <h1 class="page-title text-3xl font-bold text-gray-900 dark:text-gray-100">
                        <?php
                        /* translators: %s: search query. */
                        printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span class="text-blue-600 dark:text-blue-400">' . get_search_query() . '</span>' );
                        ?>
                    </h1>
                    <div class="search-result-count text-gray-600 dark:text-gray-400 mt-2">
                        <?php
                        printf(
                            esc_html( _n( '%d result found', '%d results found', $wp_query->found_posts, 'aqualuxe' ) ),
                            $wp_query->found_posts
                        );
                        ?>
                    </div>
                </header><!-- .page-header -->

                <div class="search-form-container mb-8">
                    <?php get_search_form(); ?>
                </div>

                <div class="search-results">
                    <?php
                    /* Start the Loop */
                    while ( have_posts() ) :
                        the_post();

                        /**
                         * Run the loop for the search to output the results.
                         * If you want to overload this in a child theme then include a file
                         * called content-search.php and that will be used instead.
                         */
                        get_template_part( 'template-parts/content/content', 'search' );

                    endwhile;

                    // Pagination
                    aqualuxe_pagination();
                    ?>
                </div>

            <?php else : ?>

                <?php get_template_part( 'template-parts/content/content', 'none' ); ?>

            <?php endif; ?>
        </div>

        <?php if ( $sidebar_position === 'right' ) : ?>
            <aside id="secondary" class="widget-area lg:col-span-3">
                <?php get_sidebar(); ?>
            </aside>
        <?php endif; ?>
    </div>
</main><!-- #main -->

<?php
get_footer();