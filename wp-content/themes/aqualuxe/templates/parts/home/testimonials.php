<?php
/**
 * Testimonials Section for Home Page
 *
 * @package AquaLuxe
 */

// Query for testimonials
$testimonials_query = new WP_Query([
    'post_type'      => 'testimonial',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC'
]);
?>

<section class="testimonials-section py-20 bg-gradient-to-r from-blue-50 to-cyan-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">What Our Customers Say</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Discover why aquarium enthusiasts and businesses worldwide trust AquaLuxe for their aquatic needs.
            </p>
        </div>

        <?php if ($testimonials_query->have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php while ($testimonials_query->have_posts()) : $testimonials_query->the_post(); ?>
                    <div class="testimonial-card bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <!-- Quote Icon -->
                        <div class="mb-6">
                            <svg class="w-12 h-12 text-cyan-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-10zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/>
                            </svg>
                        </div>

                        <!-- Testimonial Content -->
                        <div class="mb-6">
                            <p class="text-gray-700 text-lg leading-relaxed italic">
                                "<?php echo wp_trim_words(get_the_content(), 25, '..."'); ?>"
                            </p>
                        </div>

                        <!-- Author Info -->
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('thumbnail', [
                                        'class' => 'w-12 h-12 rounded-full object-cover'
                                    ]); ?>
                                <?php else : ?>
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold text-gray-800"><?php the_title(); ?></h4>
                                <?php
                                $company = get_post_meta(get_the_ID(), 'testimonial_company', true);
                                if (!empty($company)) :
                                ?>
                                    <p class="text-gray-600 text-sm"><?php echo esc_html($company); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Stars Rating -->
                        <div class="flex mt-4">
                            <?php for ($i = 0; $i < 5; $i++) : ?>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="text-center mt-12">
                <a href="<?php echo get_post_type_archive_link('testimonial'); ?>" class="btn-primary bg-cyan-600 hover:bg-cyan-700 text-white px-8 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                    Read More Reviews
                </a>
            </div>
        <?php else : ?>
            <div class="text-center py-12">
                <div class="bg-white p-8 rounded-xl shadow-lg max-w-md mx-auto">
                    <svg class="w-16 h-16 text-cyan-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-gray-600 text-lg">Customer testimonials coming soon!</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php wp_reset_postdata(); ?>
