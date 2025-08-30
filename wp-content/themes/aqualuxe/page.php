<?php
/**
 * The template for displaying all pages
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
                ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
                        <header class="page-header mb-8">
                            <h1 class="page-title text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">
                                <?php the_title(); ?>
                            </h1>
                            
                            <?php if ( has_excerpt() ) : ?>
                                <div class="page-excerpt text-xl text-gray-600 dark:text-gray-300 mt-4">
                                    <?php the_excerpt(); ?>
                                </div>
                            <?php endif; ?>
                        </header>
                        
                        <div class="page-content prose prose-lg max-w-none dark:prose-invert">
                            <?php
                            the_content();
                            
                            wp_link_pages(
                                array(
                                    'before' => '<div class="page-links mt-6">' . esc_html__( 'Pages:', 'aqualuxe' ),
                                    'after'  => '</div>',
                                    'link_before' => '<span class="page-number">',
                                    'link_after'  => '</span>',
                                )
                            );
                            ?>
                        </div>
                        
                        <?php if ( get_edit_post_link() ) : ?>
                            <footer class="page-footer mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                                <?php
                                edit_post_link(
                                    sprintf(
                                        wp_kses(
                                            /* translators: %s: Post title. Only visible to screen readers. */
                                            __( 'Edit <span class="sr-only">"%s"</span>', 'aqualuxe' ),
                                            array(
                                                'span' => array(
                                                    'class' => array(),
                                                ),
                                            )
                                        ),
                                        wp_kses_post( get_the_title() )
                                    ),
                                    '<div class="edit-link btn btn-outline btn-sm">',
                                    '</div>'
                                );
                                ?>
                            </footer>
                        <?php endif; ?>
                    </article>
                    
                    <?php
                    // If comments are open or there are comments, load the comments template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;
                    ?>
                <?php
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
