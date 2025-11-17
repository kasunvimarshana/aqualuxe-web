<?php
/**
 * Page template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="main" class="site-main flex-1" role="main">
    <div class="container mx-auto px-4 py-8 lg:py-12">
        
        <?php while (have_posts()) : the_post(); ?>
            
            <div class="content-area">
                <div class="max-w-4xl mx-auto">
                    <?php get_template_part('template-parts/content/content', 'page'); ?>
                    
                    <!-- Comments -->
                    <?php
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>
                </div>
            </div>
            
        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer();