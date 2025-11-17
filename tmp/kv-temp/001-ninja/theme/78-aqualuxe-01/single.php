<?php
/**
 * Template for displaying single posts
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<div class="<?php echo esc_attr(aqualuxe_get_container_classes()); ?>">
    <div class="flex flex-wrap -mx-4">
        <div class="<?php echo esc_attr(aqualuxe_get_content_classes()); ?>">
            
            <?php echo aqualuxe_get_breadcrumbs(); ?>
            
            <?php while (have_posts()) : the_post(); ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
                    
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail mb-6">
                            <?php the_post_thumbnail('large', ['class' => 'w-full h-96 object-cover rounded-lg']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <header class="entry-header mb-6">
                        <?php aqualuxe_post_categories(); ?>
                        
                        <h1 class="entry-title text-4xl font-bold mb-4"><?php the_title(); ?></h1>
                        
                        <?php aqualuxe_post_meta(); ?>
                    </header>
                    
                    <div class="entry-content prose prose-lg max-w-none">
                        <?php
                        the_content();
                        
                        wp_link_pages([
                            'before' => '<div class="page-links">',
                            'after'  => '</div>',
                        ]);
                        ?>
                    </div>
                    
                    <footer class="entry-footer mt-8">
                        <?php aqualuxe_post_tags(); ?>
                        <?php aqualuxe_social_share(); ?>
                    </footer>
                    
                </article>
                
                <?php aqualuxe_author_bio(); ?>
                
                <?php aqualuxe_related_posts(); ?>
                
                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) {
                    comments_template();
                }
                ?>
                
            <?php endwhile; ?>
            
        </div>

        <?php if (aqualuxe_has_sidebar()) : ?>
            <aside class="<?php echo esc_attr(aqualuxe_get_sidebar_classes()); ?>">
                <?php dynamic_sidebar('sidebar-1'); ?>
            </aside>
        <?php endif; ?>
        
    </div>
</div>

<?php
get_footer();
