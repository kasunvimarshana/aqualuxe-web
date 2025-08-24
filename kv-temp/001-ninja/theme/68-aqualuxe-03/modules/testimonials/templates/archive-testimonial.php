<?php
/**
 * Testimonials Archive Template
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>
<main id="main" class="site-main testimonials-archive">
    <header class="page-header">
        <h1 class="page-title"><?php _e( 'Testimonials', 'aqualuxe' ); ?></h1>
    </header>
    <div class="testimonials-grid">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'testimonial-item' ); ?>>
                <div class="testimonial-content">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'thumbnail' ); ?>
                    <?php endif; ?>
                    <h2 class="testimonial-title"><?php the_title(); ?></h2>
                    <div class="testimonial-excerpt"><?php the_excerpt(); ?></div>
                    <div class="testimonial-rating">
                        <?php echo get_post_meta( get_the_ID(), 'testimonial_rating', true ); ?>
                    </div>
                </div>
            </article>
        <?php endwhile; else: ?>
            <p><?php _e( 'No testimonials found.', 'aqualuxe' ); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
