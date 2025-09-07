<?php
/**
 * Front Page: Call to Action Section
 *
 * @package AquaLuxe
 */
?>
<section class="bg-primary text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php esc_html_e( 'Join the AquaLuxe Community', 'aqualuxe' ); ?></h2>
        <p class="text-lg mb-8 max-w-2xl mx-auto"><?php esc_html_e( 'Sign up for our newsletter to receive exclusive offers, design inspiration, and the latest news on sustainable luxury.', 'aqualuxe' ); ?></p>
        
        <!-- Newsletter Form (replace with actual form shortcode or markup) -->
        <div class="newsletter-form max-w-md mx-auto">
            <form action="#" method="post" class="flex">
                <input type="email" name="email" placeholder="<?php esc_attr_e( 'Your email address', 'aqualuxe' ); ?>" class="w-full px-4 py-3 rounded-l-full text-gray-800 focus:outline-none" required>
                <button type="submit" class="bg-secondary hover:bg-secondary-dark text-white font-bold py-3 px-6 rounded-r-full transition duration-300">
                    <?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?>
                </button>
            </form>
        </div>
    </div>
</section>
