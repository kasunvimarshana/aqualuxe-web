<?php
/**
 * Front Page: Testimonials Section
 *
 * @package AquaLuxe
 */

// In a real theme, this would likely be a custom post type or pulled from a plugin.
$testimonials = array(
    array(
        'quote' => __( 'The attention to detail is unmatched. My AquaLuxe faucet is the centerpiece of my new kitchen.', 'aqualuxe' ),
        'author' => __( 'Alex Johnson', 'aqualuxe' ),
        'location' => __( 'New York, NY', 'aqualuxe' ),
    ),
    array(
        'quote' => __( 'I was impressed by the sustainable mission and even more impressed by the product quality.', 'aqualuxe' ),
        'author' => __( 'Samantha Bee', 'aqualuxe' ),
        'location' => __( 'San Francisco, CA', 'aqualuxe' ),
    ),
    array(
        'quote' => __( 'Installation was a breeze, and the customer service was incredibly helpful. Highly recommend!', 'aqualuxe' ),
        'author' => __( 'David Chen', 'aqualuxe' ),
        'location' => __( 'Austin, TX', 'aqualuxe' ),
    ),
);
?>
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12"><?php esc_html_e( 'What Our Customers Say', 'aqualuxe' ); ?></h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ( $testimonials as $testimonial ) : ?>
                <div class="testimonial-card bg-gray-50 p-8 rounded-lg shadow-md">
                    <p class="text-gray-700 italic mb-4">"<?php echo esc_html( $testimonial['quote'] ); ?>"</p>
                    <p class="text-right font-bold text-primary">- <?php echo esc_html( $testimonial['author'] ); ?></p>
                    <p class="text-right text-sm text-gray-500"><?php echo esc_html( $testimonial['location'] ); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
