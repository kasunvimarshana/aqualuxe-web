<?php
/**
 * The template for displaying archive pages
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main" role="main" <?php echo aqualuxe_schema_markup(); ?>>
    <div class="container">
        <div class="content-area">
            
            <?php if (have_posts()) : ?>
                
                <header class="page-header">
                    <?php
                    the_archive_title('<h1 class="page-title">', '</h1>');
                    the_archive_description('<div class="archive-description">', '</div>');
                    ?>
                </header>
                
                <div class="posts-container">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('template-parts/content', get_post_type()); ?>
                    <?php endwhile; ?>
                </div>
                
                <?php
                the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => esc_html__('Previous', 'aqualuxe'),
                    'next_text' => esc_html__('Next', 'aqualuxe'),
                ));
                ?>
                
            <?php else : ?>
                <?php get_template_part('template-parts/content', 'none'); ?>
            <?php endif; ?>
            
        </div>
        
        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();