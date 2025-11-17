<?php
/**
 * Single Testimonial Template
 */
if ( ! defined( 'ABSPATH' ) ) exit;

global $post;

get_header();
?>
<main id="main" class="site-main testimonial-single">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </header>
        <div class="entry-content">
            <?php the_content(); ?>
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="testimonial-image">
                    <?php the_post_thumbnail( 'medium' ); ?>
                </div>
            <?php endif; ?>
            <div class="testimonial-rating">
                <?php echo get_post_meta( $post->ID, 'testimonial_rating', true ); ?>
            </div>
        </div>
    </article>
</main>
<?php get_footer(); ?>
