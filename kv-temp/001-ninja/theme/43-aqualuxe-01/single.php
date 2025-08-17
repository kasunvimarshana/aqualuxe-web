<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-wrap lg:flex-nowrap">
            <div class="w-full lg:w-2/3 lg:pr-8">
                <?php
                while ( have_posts() ) :
                    the_post();

                    get_template_part( 'template-parts/content/content', get_post_type() );

                    // Post navigation
                    the_post_navigation(
                        array(
                            'prev_text' => '<div class="post-nav-label">' . esc_html__( 'Previous Post', 'aqualuxe' ) . '</div><span class="post-nav-title">%title</span>',
                            'next_text' => '<div class="post-nav-label">' . esc_html__( 'Next Post', 'aqualuxe' ) . '</div><span class="post-nav-title">%title</span>',
                        )
                    );

                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;

                endwhile; // End of the loop.
                ?>
            </div>
            
            <div class="w-full lg:w-1/3 mt-8 lg:mt-0">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</main><!-- #main -->

<?php
get_footer();