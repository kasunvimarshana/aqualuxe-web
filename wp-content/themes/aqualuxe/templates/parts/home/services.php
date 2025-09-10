<?php
/**
 * Services Section for Home Page
 *
 * @package AquaLuxe
 */

// Query for services
$services_query = new WP_Query([
    'post_type'      => 'service',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC'
]);
?>

<section class="services-section py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Our Premium Services</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                From custom aquarium design to expert maintenance, we provide comprehensive aquatic solutions for homes and businesses.
            </p>
        </div>

        <?php if ($services_query->have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php while ($services_query->have_posts()) : $services_query->the_post(); ?>
                    <div class="service-card bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="service-image h-48 overflow-hidden">
                                <?php the_post_thumbnail('medium_large', ['class' => 'w-full h-full object-cover transition-transform duration-300 hover:scale-110']); ?>
                            </div>
                        <?php else : ?>
                            <div class="service-image h-48 bg-gradient-to-br from-blue-100 to-cyan-100 flex items-center justify-center">
                                <svg class="w-16 h-16 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.78 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5z"></path>
                                </svg>
                            </div>
                        <?php endif; ?>

                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-3"><?php the_title(); ?></h3>
                            <div class="text-gray-600 mb-4 line-clamp-3">
                                <?php echo wp_trim_words(get_the_content(), 20, '...'); ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-cyan-600 hover:text-cyan-700 font-medium transition-colors duration-200">
                                Learn More
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="text-center mt-12">
                <a href="<?php echo get_post_type_archive_link('service'); ?>" class="btn-primary bg-cyan-600 hover:bg-cyan-700 text-white px-8 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                    View All Services
                </a>
            </div>
        <?php else : ?>
            <div class="text-center py-12">
                <p class="text-gray-600 text-lg">No services available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php wp_reset_postdata(); ?>

<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
