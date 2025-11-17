<?php
/**
 * The template for displaying the front page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        // Check if we're showing the static front page
        if ( get_option( 'show_on_front' ) === 'page' && get_option( 'page_on_front' ) ) {
            // Display the front page content
            while ( have_posts() ) {
                the_post();
                get_template_part( 'templates/content/content', 'page' );
            }
        } else {
            // Display the blog posts
            if ( have_posts() ) {
                echo '<div class="posts-grid">';
                
                while ( have_posts() ) {
                    the_post();
                    get_template_part( 'templates/content/content', 'archive' );
                }
                
                echo '</div>';
                
                aqualuxe_pagination();
            } else {
                get_template_part( 'templates/content/content', 'none' );
            }
        }
        ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();