<?php
/**
 * The template for displaying the contact page.
 *
 * Template Name: Contact Page
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();
			the_title( '<h1 class="entry-title text-center text-4xl font-bold my-8">', '</h1>' );
			the_content();
		endwhile;
		?>

        <div class="container mx-auto py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="contact-form">
                    <h2 class="text-2xl font-bold mb-4"><?php esc_html_e( 'Send us a message', 'aqualuxe' ); ?></h2>
                    <!-- Placeholder for a contact form plugin shortcode -->
                    <?php echo do_shortcode('[contact-form-7 id="1234" title="Contact form 1"]'); ?>
                </div>
                <div class="contact-info">
                    <h2 class="text-2xl font-bold mb-4"><?php esc_html_e( 'Contact Information', 'aqualuxe' ); ?></h2>
                    <p class="mb-2"><strong><?php esc_html_e( 'Address:', 'aqualuxe' ); ?></strong> 123 Aqua St, Ocean City, 12345</p>
                    <p class="mb-2"><strong><?php esc_html_e( 'Phone:', 'aqualuxe' ); ?></strong> (123) 456-7890</p>
                    <p class="mb-2"><strong><?php esc_html_e( 'Email:', 'aqualuxe' ); ?></strong> contact@aqualuxe.com</p>
                    <div class="google-map mt-8">
                        <!-- Placeholder for Google Maps embed -->
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.019394095416!2d144.9537353153165!3d-37.8162797420219!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0xf577d70f5d1b4a0!2sFederation%20Square!5e0!3m2!1sen!2sau!4v16221811 Federation%20Square" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>

	</main><!-- #main -->

<?php
get_footer();
