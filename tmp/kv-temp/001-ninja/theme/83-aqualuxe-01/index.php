<?php
/**
 * The main template file
 * 
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * 
 * @package KV_Enterprise
 * @version 1.0.0
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php if (have_posts()) : ?>
                    
                    <?php if (is_home() && !is_front_page()) : ?>
                        <header class="page-header">
                            <h1 class="page-title"><?php single_post_title(); ?></h1>
                        </header>
                    <?php endif; ?>

                    <?php
                    // Start the Loop
                    while (have_posts()) :
                        the_post();
                        
                        /*
                         * Include the Post-Type-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                         */
                        get_template_part('templates/content', get_post_type());
                        
                    endwhile;
                    
                    // Previous/next page navigation
                    the_posts_navigation(array(
                        'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', KV_THEME_TEXTDOMAIN) . '</span> <span class="nav-title">%title</span>',
                        'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', KV_THEME_TEXTDOMAIN) . '</span> <span class="nav-title">%title</span>',
                    ));
                    
                else :
                    
                    get_template_part('templates/content', 'none');
                    
                endif;
                ?>
            </div>
            
            <div class="col-lg-4">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
