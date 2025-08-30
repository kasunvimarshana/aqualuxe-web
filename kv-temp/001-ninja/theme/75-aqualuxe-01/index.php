<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<div class="content-wrapper">
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <main class="main-content lg:col-span-2">
                <?php if ( have_posts() ) : ?>
                    
                    <?php if ( is_home() && ! is_front_page() ) : ?>
                        <header class="page-header mb-8">
                            <h1 class="page-title text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">
                                <?php single_post_title(); ?>
                            </h1>
                        </header>
                    <?php endif; ?>
                    
                    <div class="posts-grid space-y-8">
                        <?php
                        $post_count = 0;
                        while ( have_posts() ) :
                            the_post();
                            $post_count++;
                            
                            // Featured post layout for first post on home page
                            if ( is_home() && $post_count === 1 ) :
                                get_template_part( 'template-parts/content', 'featured' );
                            else :
                                get_template_part( 'template-parts/content', get_post_format() );
                            endif;
                            
                        endwhile; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination-wrapper mt-12">
                        <?php aqualuxe_pagination(); ?>
                    </div>
                    
                <?php else : ?>
                    
                    <?php get_template_part( 'template-parts/content', 'none' ); ?>
                    
                <?php endif; ?>
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
