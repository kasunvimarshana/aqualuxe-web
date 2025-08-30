<?php
/**
 * Template part for displaying page content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article id="page-<?php the_ID(); ?>" <?php post_class('page-content bg-white dark:bg-dark-light rounded-lg shadow-soft overflow-hidden mb-8'); ?>>
    <?php do_action( 'aqualuxe_page_top' ); ?>
    
    <div class="page-content-inner p-6">
        <?php if ( ! is_front_page() && ! get_post_meta( get_the_ID(), '_aqualuxe_hide_title', true ) ) : ?>
            <header class="page-header mb-6">
                <h1 class="page-title text-3xl md:text-4xl font-serif font-bold"><?php the_title(); ?></h1>
            </header>
        <?php endif; ?>
        
        <div class="page-body prose dark:prose-invert max-w-none">
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
</article>

<?php
// If comments are open or we have at least one comment, load up the comment template.
if ( comments_open() || get_comments_number() ) :
    comments_template();
endif;
?>