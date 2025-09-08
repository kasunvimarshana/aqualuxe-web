<?php
/**
 * Front Page: Brand Story Section
 *
 * @package AquaLuxe
 */
?>
<section class="py-20 bg-white">
    <div class="container mx-auto px-4 flex flex-wrap items-center">
        <div class="w-full md:w-1/2 px-4 mb-8 md:mb-0">
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/src/images/brand-story.svg' ); ?>" alt="<?php esc_attr_e( 'Our Brand Story', 'aqualuxe' ); ?>" class="rounded-lg shadow-lg">
        </div>
        <div class="w-full md:w-1/2 px-4">
            <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php esc_html_e( 'The AquaLuxe Difference', 'aqualuxe' ); ?></h2>
            <p class="text-gray-700 leading-relaxed mb-6">
                <?php esc_html_e( 'Founded on the principles of innovation and elegance, AquaLuxe is dedicated to transforming everyday water experiences into moments of pure luxury. Our commitment to sustainable practices and cutting-edge technology ensures that every product is not only beautiful but also responsible.', 'aqualuxe' ); ?>
            </p>
            <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'about-us' ) ) ); ?>" class="text-primary hover:text-primary-dark font-bold">
                <?php esc_html_e( 'Learn More About Us', 'aqualuxe' ); ?> &rarr;
            </a>
        </div>
    </div>
</section>
