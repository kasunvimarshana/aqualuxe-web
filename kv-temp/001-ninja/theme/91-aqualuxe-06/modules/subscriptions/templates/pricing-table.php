<?php
/**
 * Subscription Pricing Table Template
 *
 * This template can be included via the [aqualuxe_subscription_pricing] shortcode.
 *
 * @package AquaLuxe
 */

$args = array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'product_type',
            'field'    => 'slug',
            'terms'    => array('subscription', 'variable-subscription'),
        ),
    ),
);
$subscriptions_query = new WP_Query( $args );
?>

<div class="subscription-pricing-table container mx-auto py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php if ( $subscriptions_query->have_posts() ) : ?>
            <?php while ( $subscriptions_query->have_posts() ) : $subscriptions_query->the_post(); ?>
                <?php global $product; ?>
                <div class="pricing-plan bg-white rounded-lg shadow-md p-8 text-center">
                    <h3 class="text-2xl font-bold mb-4"><?php the_title(); ?></h3>
                    <div class="price text-4xl font-bold mb-4">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    <div class="plan-description mb-6">
                        <?php the_excerpt(); ?>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <?php esc_html_e( 'Sign Up Now', 'aqualuxe' ); ?>
                    </a>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <p><?php esc_html_e( 'No subscription plans found.', 'aqualuxe' ); ?></p>
        <?php endif; ?>
    </div>
</div>
