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

// Get blog layout
$blog_layout = aqualuxe_get_option( 'blog_layout', 'grid' );

// Get blog columns
$blog_columns = aqualuxe_get_option( 'blog_columns', '3' );

// Blog container classes
$blog_container_classes = [
    'blog-container',
    'blog-layout-' . $blog_layout,
    'blog-columns-' . $blog_columns,
];
?>

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

            <div class="search-form-container">
                <?php get_search_form(); ?>
            </div>

            <div class="<?php echo esc_attr( implode( ' ', $blog_container_classes ) ); ?>">
                <?php
                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();

                    /**
                     * Run the loop for the search to output the results.
                     * If you want to overload this in a child theme then include a file
                     * called content-search.php and that will be used instead.
                     */
                    get_template_part( 'templates/content/content', 'archive' );

                endwhile;
                ?>
            </div>

            <?php
            aqualuxe_pagination();

        else :

            get_template_part( 'templates/content/content', 'none' );

        endif;
        ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();