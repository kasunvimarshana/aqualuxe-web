<?php
/**
 * The template for displaying all single posts
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<div class="content-wrapper">
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <main class="main-content lg:col-span-2">
                <?php
                while ( have_posts() ) :
                    the_post();
                    
                    get_template_part( 'template-parts/content', get_post_format() );
                    
                endwhile; // End of the loop.
                ?>
            </main>
            
            <!-- Sidebar -->
            <aside class="sidebar lg:col-span-1">
                <?php get_sidebar(); ?>
            </aside>
        </div>
    </div>
</div>

<?php
get_footer();
