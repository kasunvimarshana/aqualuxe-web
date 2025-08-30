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
 * @version 1.0.0
 */

get_header(); ?>

<div class="main-content">
    <div class="container mx-auto px-4 py-8">
        
        <?php if (have_posts()): ?>
            
            <?php if (is_home() && !is_front_page()): ?>
                <header class="page-header mb-8">
                    <h1 class="page-title text-3xl font-bold"><?php single_post_title(); ?></h1>
                </header>
            <?php endif; ?>
            
            <div class="posts-grid grid lg:grid-cols-3 gap-8">
                <main class="main-column lg:col-span-2">
                    
                    <?php if (is_home() || is_archive()): ?>
                        <div class="posts-list space-y-8">
                    <?php else: ?>
                        <div class="posts-list">
                    <?php endif; ?>
                    
                    <?php
                    // Start the Loop
                    while (have_posts()):
                        the_post();
                        
                        /*
                         * Include the Post-Type-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                         */
                        get_template_part('templates/content/content', get_post_type());
                        
                    endwhile;
                    ?>
                    
                    </div>
                    
                    <?php
                    // Pagination
                    aqualuxe_pagination();
                    ?>
                    
                </main>
                
                <?php get_sidebar(); ?>
            </div>
            
        <?php else: ?>
            
            <?php get_template_part('templates/content/content', 'none'); ?>
            
        <?php endif; ?>
        
    </div>
</div>

<?php
get_footer();
