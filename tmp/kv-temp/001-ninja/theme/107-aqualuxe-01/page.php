<?php
/**
 * Template for displaying pages
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container mx-auto px-4 py-8">
        
        <?php while (have_posts()) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-page'); ?>>
                
                <?php if (has_post_thumbnail() && !is_front_page()) : ?>
                    <div class="page-thumbnail mb-8">
                        <?php the_post_thumbnail('aqualuxe-hero', ['class' => 'w-full h-64 md:h-96 object-cover rounded-lg']); ?>
                    </div>
                <?php endif; ?>
                
                <header class="page-header mb-8">
                    <h1 class="page-title text-4xl font-bold text-gray-900 dark:text-white">
                        <?php the_title(); ?>
                    </h1>
                </header>
                
                <div class="page-content prose max-w-none">
                    <?php the_content(); ?>
                    
                    <?php
                    wp_link_pages([
                        'before' => '<div class="page-links">' . __('Pages:', 'aqualuxe'),
                        'after'  => '</div>',
                    ]);
                    ?>
                </div>
                
            </article>
            
        <?php endwhile; ?>
        
        <?php
        // Comments for pages if enabled
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
        ?>
        
    </div>
</main>

<?php get_footer();