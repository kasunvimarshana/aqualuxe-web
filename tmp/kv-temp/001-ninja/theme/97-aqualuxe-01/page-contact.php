<?php
/**
 * The template for displaying the Contact page
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main py-12">
    <div class="container mx-auto px-4">
		<article id="post-<?php the_ID(); ?>" <?php post_class('prose dark:prose-invert max-w-none'); ?>>
			<header class="entry-header text-center mb-12">
				<?php the_title( '<h1 class="entry-title text-5xl font-bold">', '</h1>' ); ?>
			</header><!-- .entry-header -->

			<div class="entry-content">
                <div class="grid md:grid-cols-2 gap-12">
                    <div class="contact-details">
                        <h2 class="text-3xl font-bold mb-4"><?php esc_html_e( 'Get in Touch', 'aqualuxe' ); ?></h2>
                        <p class="text-lg mb-8"><?php esc_html_e( 'Have a question or want to work with us? Drop us a line.', 'aqualuxe' ); ?></p>
                        <ul class="space-y-4 text-lg">
                            <li class="flex items-center"><strong class="w-24"><?php esc_html_e( 'Email:', 'aqualuxe' ); ?></strong> <a href="mailto:contact@aqualuxe.com" class="text-blue-600 dark:text-blue-400 hover:underline">contact@aqualuxe.com</a></li>
                            <li class="flex items-center"><strong class="w-24"><?php esc_html_e( 'Phone:', 'aqualuxe' ); ?></strong> <span>+1 234 567 890</span></li>
                            <li class="flex items-center"><strong class="w-24"><?php esc_html_e( 'Address:', 'aqualuxe' ); ?></strong> <span>123 Aqua Street, Oceanview, 12345</span></li>
                        </ul>
                    </div>

                    <div class="contact-form bg-gray-100 dark:bg-gray-800 p-8 rounded-lg">
                        <h2 class="text-3xl font-bold mb-4"><?php esc_html_e( 'Send a Message', 'aqualuxe' ); ?></h2>
                        <?php
                        // A contact form plugin shortcode would go here, e.g.,
                        // echo do_shortcode('[contact-form-7 id="123" title="Contact form 1"]');
                        ?>
                        <p><em><?php esc_html_e( 'Contact form functionality will be available via a recommended plugin.', 'aqualuxe' ); ?></em></p>
                    </div>
                </div>

				<div class="google-map mt-16">
					<h2 class="text-3xl font-bold text-center mb-8"><?php esc_html_e( 'Our Location', 'aqualuxe' ); ?></h2>
					<div class="rounded-lg overflow-hidden shadow-lg">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.902348499584!2d79.8609336153639!3d6.90220399501298!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae259630b7d5a3d%3A0x2c582b73443849b3!2sColombo%2C%20Sri%20Lanka!5e0!3m2!1sen!2sus!4v1633382099411!5m2!1sen!2sus" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
					</div>
				</div>
			</div><!-- .entry-content -->

		</article><!-- #post-<?php the_ID(); ?> -->
    </div>
</main><!-- #main -->

<?php
get_footer();
