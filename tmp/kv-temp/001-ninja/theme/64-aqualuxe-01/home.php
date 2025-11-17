<?php
/**
 * The template for displaying the blog posts index page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
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
                    if ( get_option( 'page_for_posts' ) ) {
                        echo esc_html( get_the_title( get_option( 'page_for_posts' ) ) );
                    } else {
                        esc_html_e( 'Blog', 'aqualuxe' );
                    }
                    ?>
                </h1>
            </header><!-- .page-header -->

            <div class="<?php echo esc_attr( implode( ' ', $blog_container_classes ) ); ?>">
                <?php
                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();

                    /*
                     * Include the Post-Type-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Type name) and that will be used instead.
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