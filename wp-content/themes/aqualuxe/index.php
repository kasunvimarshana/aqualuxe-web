<?php
/**
 * The main template file
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container mx-auto px-4 py-8">
        
        <?php if (have_posts()) : ?>
            
            <?php if (is_home() && !is_front_page()) : ?>
                <header class="page-header mb-8">
                    <h1 class="page-title text-3xl font-bold text-primary-800 dark:text-primary-200">
                        <?php single_post_title(); ?>
                    </h1>
                </header>
            <?php endif; ?>
            
            <div class="grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <div class="posts-grid grid gap-6 md:grid-cols-2">
                        <?php while (have_posts()) : the_post(); ?>
                            <?php get_template_part('templates/partials/content', get_post_type()); ?>
                        <?php endwhile; ?>
                    </div>
                    
                    <?php
                    // Pagination
                    the_posts_pagination([
                        'mid_size'  => 2,
                        'prev_text' => esc_html__('← Previous', 'aqualuxe'),
                        'next_text' => esc_html__('Next →', 'aqualuxe'),
                        'class'     => 'pagination mt-8'
                    ]);
                    ?>
                </div>
                
                <aside class="sidebar">
                    <?php get_sidebar(); ?>
                </aside>
            </div>
            
        <?php else : ?>
            
            <?php get_template_part('templates/partials/content', 'none'); ?>
            
        <?php endif; ?>
        
    </div>
</main>

<?php get_footer(); ?>