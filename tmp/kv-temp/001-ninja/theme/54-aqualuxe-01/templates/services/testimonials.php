<?php
/**
 * Services Testimonials Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer options
$title = get_theme_mod('aqualuxe_services_testimonials_title', __('Client Testimonials', 'aqualuxe'));
$subtitle = get_theme_mod('aqualuxe_services_testimonials_subtitle', __('What Our Clients Say', 'aqualuxe'));
$description = get_theme_mod('aqualuxe_services_testimonials_description', __('Hear from our satisfied clients about their experience with our services.', 'aqualuxe'));
$layout = get_theme_mod('aqualuxe_services_testimonials_layout', 'slider'); // slider, grid, or masonry
$show_images = get_theme_mod('aqualuxe_services_testimonials_show_images', true);
$show_ratings = get_theme_mod('aqualuxe_services_testimonials_show_ratings', true);
$background_color = get_theme_mod('aqualuxe_services_testimonials_bg', 'bg-gray-50 dark:bg-gray-900');

// Get testimonials
// In a real implementation, this would likely use a custom post type for testimonials
// For this template, we'll use sample data
$testimonials = [
    [
        'name' => 'John Smith',
        'position' => 'Homeowner',
        'content' => 'AquaLuxe transformed our backyard with a stunning pool installation. Their attention to detail and professionalism exceeded our expectations. The team was courteous, punctual, and kept us informed throughout the entire process.',
        'image' => get_template_directory_uri() . '/assets/images/testimonials/testimonial-1.jpg',
        'rating' => 5,
    ],
    [
        'name' => 'Sarah Johnson',
        'position' => 'Resort Manager',
        'content' => 'We hired AquaLuxe for our resort pool renovation and couldn\'t be happier with the results. Their team worked efficiently to minimize disruption to our guests, and the final product is absolutely beautiful. Their ongoing maintenance service is excellent as well.',
        'image' => get_template_directory_uri() . '/assets/images/testimonials/testimonial-2.jpg',
        'rating' => 5,
    ],
    [
        'name' => 'Michael Chen',
        'position' => 'Property Developer',
        'content' => 'As a property developer, I\'ve worked with many pool contractors, but AquaLuxe stands out for their quality and reliability. They\'ve completed multiple projects for us on time and on budget, with exceptional craftsmanship.',
        'image' => get_template_directory_uri() . '/assets/images/testimonials/testimonial-3.jpg',
        'rating' => 4,
    ],
    [
        'name' => 'Emily Rodriguez',
        'position' => 'Spa Owner',
        'content' => 'AquaLuxe\'s water treatment services have been a game-changer for our spa. Their expertise in maintaining perfect water chemistry has improved our customer experience and reduced our maintenance costs. Highly recommended!',
        'image' => get_template_directory_uri() . '/assets/images/testimonials/testimonial-4.jpg',
        'rating' => 5,
    ],
    [
        'name' => 'David Wilson',
        'position' => 'Homeowner',
        'content' => 'The renovation AquaLuxe did on our outdated pool completely transformed our backyard. They suggested modern features we hadn\'t even considered, and the result is a beautiful, low-maintenance pool that our family enjoys year-round.',
        'image' => get_template_directory_uri() . '/assets/images/testimonials/testimonial-5.jpg',
        'rating' => 5,
    ],
    [
        'name' => 'Lisa Thompson',
        'position' => 'Hotel Manager',
        'content' => 'AquaLuxe provides regular maintenance for our hotel pools, and their service is exceptional. They\'re proactive, thorough, and always ensure our pools are in perfect condition for our guests. Their team is knowledgeable and professional.',
        'image' => get_template_directory_uri() . '/assets/images/testimonials/testimonial-6.jpg',
        'rating' => 4,
    ],
];
?>

<section class="services-testimonials py-16 md:py-24 <?php echo esc_attr($background_color); ?>">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <?php if ($subtitle) : ?>
                <span class="inline-block px-3 py-1 mb-4 text-sm font-semibold tracking-wider uppercase rounded-full text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900 bg-opacity-50">
                    <?php echo esc_html($subtitle); ?>
                </span>
            <?php endif; ?>
            
            <?php if ($title) : ?>
                <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-900 dark:text-white">
                    <?php echo esc_html($title); ?>
                </h2>
            <?php endif; ?>
            
            <?php if ($description) : ?>
                <p class="text-lg text-gray-700 dark:text-gray-300">
                    <?php echo esc_html($description); ?>
                </p>
            <?php endif; ?>
        </div>
        
        <?php if ($layout === 'slider') : ?>
            <div class="testimonials-slider relative">
                <!-- Slider container -->
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php foreach ($testimonials as $testimonial) : ?>
                            <div class="swiper-slide">
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 h-full">
                                    <?php if ($show_ratings && !empty($testimonial['rating'])) : ?>
                                        <div class="flex mb-4">
                                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                <svg class="w-5 h-5 <?php echo $i <= $testimonial['rating'] ? 'text-yellow-500' : 'text-gray-300 dark:text-gray-600'; ?>" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            <?php endfor; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <blockquote class="text-gray-700 dark:text-gray-300 mb-6 italic">
                                        "<?php echo esc_html($testimonial['content']); ?>"
                                    </blockquote>
                                    
                                    <div class="flex items-center">
                                        <?php if ($show_images && !empty($testimonial['image'])) : ?>
                                            <div class="flex-shrink-0 mr-4">
                                                <img class="h-12 w-12 rounded-full object-cover" src="<?php echo esc_url($testimonial['image']); ?>" alt="<?php echo esc_attr($testimonial['name']); ?>">
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                <?php echo esc_html($testimonial['name']); ?>
                                            </div>
                                            <?php if (!empty($testimonial['position'])) : ?>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                                    <?php echo esc_html($testimonial['position']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Navigation buttons -->
                <div class="swiper-button-prev absolute top-1/2 left-0 transform -translate-y-1/2 -translate-x-1/2 bg-white dark:bg-gray-800 rounded-full shadow-lg w-12 h-12 flex items-center justify-center z-10">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </div>
                
                <div class="swiper-button-next absolute top-1/2 right-0 transform -translate-y-1/2 translate-x-1/2 bg-white dark:bg-gray-800 rounded-full shadow-lg w-12 h-12 flex items-center justify-center z-10">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                
                <!-- Pagination -->
                <div class="swiper-pagination mt-8"></div>
            </div>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    new Swiper('.testimonials-slider .swiper-container', {
                        slidesPerView: 1,
                        spaceBetween: 30,
                        loop: true,
                        autoplay: {
                            delay: 5000,
                        },
                        pagination: {
                            el: '.testimonials-slider .swiper-pagination',
                            clickable: true,
                        },
                        navigation: {
                            nextEl: '.testimonials-slider .swiper-button-next',
                            prevEl: '.testimonials-slider .swiper-button-prev',
                        },
                        breakpoints: {
                            640: {
                                slidesPerView: 1,
                            },
                            768: {
                                slidesPerView: 2,
                            },
                            1024: {
                                slidesPerView: 3,
                            },
                        },
                    });
                });
            </script>
        <?php elseif ($layout === 'grid') : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($testimonials as $testimonial) : ?>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 h-full">
                        <?php if ($show_ratings && !empty($testimonial['rating'])) : ?>
                            <div class="flex mb-4">
                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                    <svg class="w-5 h-5 <?php echo $i <= $testimonial['rating'] ? 'text-yellow-500' : 'text-gray-300 dark:text-gray-600'; ?>" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                <?php endfor; ?>
                            </div>
                        <?php endif; ?>
                        
                        <blockquote class="text-gray-700 dark:text-gray-300 mb-6 italic">
                            "<?php echo esc_html($testimonial['content']); ?>"
                        </blockquote>
                        
                        <div class="flex items-center">
                            <?php if ($show_images && !empty($testimonial['image'])) : ?>
                                <div class="flex-shrink-0 mr-4">
                                    <img class="h-12 w-12 rounded-full object-cover" src="<?php echo esc_url($testimonial['image']); ?>" alt="<?php echo esc_attr($testimonial['name']); ?>">
                                </div>
                            <?php endif; ?>
                            
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">
                                    <?php echo esc_html($testimonial['name']); ?>
                                </div>
                                <?php if (!empty($testimonial['position'])) : ?>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        <?php echo esc_html($testimonial['position']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif ($layout === 'masonry') : ?>
            <div class="masonry-grid">
                <?php foreach ($testimonials as $testimonial) : ?>
                    <div class="masonry-item mb-8">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 h-full">
                            <?php if ($show_ratings && !empty($testimonial['rating'])) : ?>
                                <div class="flex mb-4">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <svg class="w-5 h-5 <?php echo $i <= $testimonial['rating'] ? 'text-yellow-500' : 'text-gray-300 dark:text-gray-600'; ?>" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    <?php endfor; ?>
                                </div>
                            <?php endif; ?>
                            
                            <blockquote class="text-gray-700 dark:text-gray-300 mb-6 italic">
                                "<?php echo esc_html($testimonial['content']); ?>"
                            </blockquote>
                            
                            <div class="flex items-center">
                                <?php if ($show_images && !empty($testimonial['image'])) : ?>
                                    <div class="flex-shrink-0 mr-4">
                                        <img class="h-12 w-12 rounded-full object-cover" src="<?php echo esc_url($testimonial['image']); ?>" alt="<?php echo esc_attr($testimonial['name']); ?>">
                                    </div>
                                <?php endif; ?>
                                
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        <?php echo esc_html($testimonial['name']); ?>
                                    </div>
                                    <?php if (!empty($testimonial['position'])) : ?>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            <?php echo esc_html($testimonial['position']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <style>
                .masonry-grid {
                    column-count: 1;
                    column-gap: 2rem;
                }
                
                @media (min-width: 640px) {
                    .masonry-grid {
                        column-count: 2;
                    }
                }
                
                @media (min-width: 1024px) {
                    .masonry-grid {
                        column-count: 3;
                    }
                }
                
                .masonry-item {
                    display: inline-block;
                    width: 100%;
                }
            </style>
        <?php endif; ?>
    </div>
</section>