<?php
/**
 * The template for displaying archive pages for the 'auction' CPT.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header(); ?>

<div id="primary" class="content-area py-12">
    <main id="main" class="site-main container mx-auto px-4">

        <header class="page-header mb-12 text-center">
            <h1 class="page-title text-4xl font-bold"><?php esc_html_e( 'Live Auctions', 'aqualuxe' ); ?></h1>
            <p class="text-lg text-gray-600 mt-2"><?php esc_html_e( 'Bid on exclusive and limited-edition AquaLuxe items.', 'aqualuxe' ); ?></p>
        </header><!-- .page-header -->

        <?php if ( have_posts() ) : ?>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();

                    get_template_part( 'template-parts/content', 'auction' );

                endwhile;
                ?>
            </div>

            <?php
            the_posts_navigation();

        else :

            get_template_part( 'template-parts/content', 'none' );

        endif;
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
