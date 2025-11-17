<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main" role="main" <?php echo aqualuxe_schema_markup(); ?>>
    <div class="container">
        <div class="content-area">
            
            <?php while (have_posts()) : the_post(); ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    
                    <header class="entry-header">
                        <?php the_title('<h1 class="entry-title" itemprop="name">', '</h1>'); ?>
                    </header>
                    
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="entry-featured-image">
                            <?php the_post_thumbnail('aqualuxe-hero', array('class' => 'featured-image')); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="entry-content" itemprop="text">
                        <?php
                        the_content();
                        
                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                            'after'  => '</div>',
                        ));
                        ?>
                    </div>
                    
                    <?php if (get_edit_post_link()) : ?>
                        <footer class="entry-footer">
                            <?php
                            edit_post_link(
                                sprintf(
                                    wp_kses(
                                        __('Edit <span class="screen-reader-text">%s</span>', 'aqualuxe'),
                                        array(
                                            'span' => array(
                                                'class' => array(),
                                            ),
                                        )
                                    ),
                                    get_the_title()
                                ),
                                '<span class="edit-link">',
                                '</span>'
                            );
                            ?>
                        </footer>
                    <?php endif; ?>
                    
                </article>
                
                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>
                
            <?php endwhile; ?>
            
        </div>
        
        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();