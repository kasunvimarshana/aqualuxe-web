<?php
/**
 * Template part for displaying single post content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-post bg-white dark:bg-dark-light rounded-lg shadow-soft overflow-hidden mb-8'); ?>>
    <?php do_action( 'aqualuxe_single_post_top' ); ?>
    
    <div class="post-content p-6">
        <header class="post-header mb-6">
            <h1 class="post-title text-3xl md:text-4xl font-serif font-bold mb-4"><?php the_title(); ?></h1>
            <?php aqualuxe_post_meta(); ?>
        </header>
        
        <div class="post-body prose dark:prose-invert max-w-none mb-8">
            <?php the_content(); ?>
            
            <?php
            wp_link_pages(
                array(
                    'before' => '<div class="page-links mt-4 p-4 bg-light-dark dark:bg-dark rounded">' . esc_html__( 'Pages:', 'aqualuxe' ),
                    'after'  => '</div>',
                )
            );
            ?>
        </div>
    </div>
    
    <div class="post-footer p-6 bg-light-dark dark:bg-dark border-t border-gray-200 dark:border-gray-700">
        <?php do_action( 'aqualuxe_single_post_bottom' ); ?>
    </div>
</article>

<?php
// If comments are open or we have at least one comment, load up the comment template.
if ( comments_open() || get_comments_number() ) :
    comments_template();
endif;
?>