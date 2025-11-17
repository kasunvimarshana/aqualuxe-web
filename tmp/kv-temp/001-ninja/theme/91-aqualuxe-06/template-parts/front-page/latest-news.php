<?php
/**
 * Front Page: Latest News Section
 *
 * @package AquaLuxe
 */

$latest_posts_query = new WP_Query( array(
    'post_type' => 'post',
    'posts_per_page' => 3,
    'ignore_sticky_posts' => 1,
) );
?>
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12"><?php esc_html_e( 'From Our Journal', 'aqualuxe' ); ?></h2>
        <?php if ( $latest_posts_query->have_posts() ) : ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php while ( $latest_posts_query->have_posts() ) : $latest_posts_query->the_post(); ?>
                    <?php get_template_part( 'template-parts/content', get_post_format() ); ?>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <p class="text-center"><?php esc_html_e( 'No recent news found.', 'aqualuxe' ); ?></p>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
        <div class="text-center mt-12">
            <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="text-primary hover:text-primary-dark font-bold">
                <?php esc_html_e( 'Visit Our Blog', 'aqualuxe' ); ?> &rarr;
            </a>
        </div>
    </div>
</section>
