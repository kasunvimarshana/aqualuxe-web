<?php
/**
 * About Page Testimonials Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer settings
$section_title = get_theme_mod('aqualuxe_about_testimonials_title', __('What Our Clients Say', 'aqualuxe'));
$section_subtitle = get_theme_mod('aqualuxe_about_testimonials_subtitle', __('Testimonials from our valued customers', 'aqualuxe'));
$show_section = get_theme_mod('aqualuxe_about_testimonials_show', true);
$layout = get_theme_mod('aqualuxe_about_testimonials_layout', 'slider'); // slider, grid, masonry

// Get testimonials from customizer
$testimonials = get_theme_mod('aqualuxe_about_testimonials', [
    [
        'content' => __('AquaLuxe transformed our office lobby with a stunning custom aquarium that has become the centerpiece of our corporate headquarters. Their team was professional, knowledgeable, and delivered beyond our expectations.', 'aqualuxe'),
        'name' => __('Robert Johnson', 'aqualuxe'),
        'position' => __('CEO, TechVision Inc.', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/dist/images/testimonials/testimonial-1.jpg',
        'rating' => 5,
    ],
    [
        'content' => __('As a marine biology enthusiast, I\'ve purchased equipment from many suppliers, but AquaLuxe stands out for their exceptional quality and customer service. Their team truly understands the needs of both beginners and experts.', 'aqualuxe'),
        'name' => __('Emma Thompson', 'aqualuxe'),
        'position' => __('Marine Biologist', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/dist/images/testimonials/testimonial-2.jpg',
        'rating' => 5,
    ],
    [
        'content' => __('The custom reef tank that AquaLuxe designed for my restaurant has not only enhanced the dining experience but has become a talking point among our customers. Their maintenance service is also top-notch, ensuring our aquarium always looks spectacular.', 'aqualuxe'),
        'name' => __('David Chen', 'aqualuxe'),
        'position' => __('Owner, Ocean Breeze Restaurant', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/dist/images/testimonials/testimonial-3.jpg',
        'rating' => 5,
    ],
    [
        'content' => __('I\'ve been in the aquarium hobby for over 15 years, and AquaLuxe products are simply the best I\'ve used. Their filtration systems are top-notch, and their customer support team is always helpful and knowledgeable.', 'aqualuxe'),
        'name' => __('Sophia Rodriguez', 'aqualuxe'),
        'position' => __('Aquarium Hobbyist', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/dist/images/testimonials/testimonial-4.jpg',
        'rating' => 5,
    ],
    [
        'content' => __('AquaLuxe\'s aquascaping service transformed my ordinary tank into a breathtaking underwater landscape. Their attention to detail and artistic vision is unmatched in the industry.', 'aqualuxe'),
        'name' => __('Michael Taylor', 'aqualuxe'),
        'position' => __('Interior Designer', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/dist/images/testimonials/testimonial-5.jpg',
        'rating' => 5,
    ],
    [
        'content' => __('Our pediatric dental office wanted something special to help our young patients feel at ease. AquaLuxe designed a child-friendly aquarium that has been a huge hit! Kids now look forward to their dental visits.', 'aqualuxe'),
        'name' => __('Dr. Lisa Williams', 'aqualuxe'),
        'position' => __('Pediatric Dentist', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/dist/images/testimonials/testimonial-6.jpg',
        'rating' => 5,
    ],
]);

// Exit if section is disabled
if (!$show_section) {
    return;
}
?>

<section class="aqualuxe-testimonials py-16 bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <?php if ($section_title) : ?>
                <h2 class="text-3xl font-bold mb-4"><?php echo esc_html($section_title); ?></h2>
            <?php endif; ?>
            
            <?php if ($section_subtitle) : ?>
                <p class="text-lg text-gray-600 dark:text-gray-400"><?php echo esc_html($section_subtitle); ?></p>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($testimonials)) : ?>
            <?php if ($layout === 'slider') : ?>
                <!-- Slider Layout -->
                <div class="testimonials-slider">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <?php foreach ($testimonials as $testimonial) : ?>
                                <div class="swiper-slide">
                                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mx-2 h-full">
                                        <?php if (!empty($testimonial['rating'])) : ?>
                                            <div class="flex text-yellow-400 mb-4">
                                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                    <?php if ($i <= $testimonial['rating']) : ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    <?php else : ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                        </svg>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($testimonial['content'])) : ?>
                                            <div class="mb-6">
                                                <svg class="h-8 w-8 text-primary-600 mb-2 opacity-50" fill="currentColor" viewBox="0 0 32 32" aria-hidden="true">
                                                    <path d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z" />
                                                </svg>
                                                <p class="text-gray-600 dark:text-gray-300 text-lg"><?php echo esc_html($testimonial['content']); ?></p>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="flex items-center">
                                            <?php if (!empty($testimonial['image'])) : ?>
                                                <div class="flex-shrink-0 mr-4">
                                                    <img class="h-12 w-12 rounded-full object-cover" src="<?php echo esc_url($testimonial['image']); ?>" alt="<?php echo esc_attr($testimonial['name']); ?>">
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div>
                                                <?php if (!empty($testimonial['name'])) : ?>
                                                    <h4 class="text-lg font-semibold"><?php echo esc_html($testimonial['name']); ?></h4>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($testimonial['position'])) : ?>
                                                    <p class="text-gray-500 dark:text-gray-400"><?php echo esc_html($testimonial['position']); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="swiper-pagination mt-8"></div>
                        
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>
                
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        new Swiper('.testimonials-slider .swiper-container', {
                            slidesPerView: 1,
                            spaceBetween: 30,
                            loop: true,
                            autoplay: {
                                delay: 5000,
                                disableOnInteraction: false,
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
                            }
                        });
                    });
                </script>
            <?php elseif ($layout === 'grid') : ?>
                <!-- Grid Layout -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($testimonials as $testimonial) : ?>
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 h-full">
                            <?php if (!empty($testimonial['rating'])) : ?>
                                <div class="flex text-yellow-400 mb-4">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <?php if ($i <= $testimonial['rating']) : ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        <?php else : ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($testimonial['content'])) : ?>
                                <div class="mb-6">
                                    <svg class="h-8 w-8 text-primary-600 mb-2 opacity-50" fill="currentColor" viewBox="0 0 32 32" aria-hidden="true">
                                        <path d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z" />
                                    </svg>
                                    <p class="text-gray-600 dark:text-gray-300"><?php echo esc_html($testimonial['content']); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex items-center mt-auto">
                                <?php if (!empty($testimonial['image'])) : ?>
                                    <div class="flex-shrink-0 mr-4">
                                        <img class="h-12 w-12 rounded-full object-cover" src="<?php echo esc_url($testimonial['image']); ?>" alt="<?php echo esc_attr($testimonial['name']); ?>">
                                    </div>
                                <?php endif; ?>
                                
                                <div>
                                    <?php if (!empty($testimonial['name'])) : ?>
                                        <h4 class="text-lg font-semibold"><?php echo esc_html($testimonial['name']); ?></h4>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($testimonial['position'])) : ?>
                                        <p class="text-gray-500 dark:text-gray-400"><?php echo esc_html($testimonial['position']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <!-- Masonry Layout -->
                <div class="masonry-grid">
                    <?php foreach ($testimonials as $testimonial) : ?>
                        <div class="masonry-item mb-6">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 h-full">
                                <?php if (!empty($testimonial['rating'])) : ?>
                                    <div class="flex text-yellow-400 mb-4">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <?php if ($i <= $testimonial['rating']) : ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            <?php else : ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                </svg>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($testimonial['content'])) : ?>
                                    <div class="mb-6">
                                        <svg class="h-8 w-8 text-primary-600 mb-2 opacity-50" fill="currentColor" viewBox="0 0 32 32" aria-hidden="true">
                                            <path d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z" />
                                        </svg>
                                        <p class="text-gray-600 dark:text-gray-300"><?php echo esc_html($testimonial['content']); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="flex items-center">
                                    <?php if (!empty($testimonial['image'])) : ?>
                                        <div class="flex-shrink-0 mr-4">
                                            <img class="h-12 w-12 rounded-full object-cover" src="<?php echo esc_url($testimonial['image']); ?>" alt="<?php echo esc_attr($testimonial['name']); ?>">
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div>
                                        <?php if (!empty($testimonial['name'])) : ?>
                                            <h4 class="text-lg font-semibold"><?php echo esc_html($testimonial['name']); ?></h4>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($testimonial['position'])) : ?>
                                            <p class="text-gray-500 dark:text-gray-400"><?php echo esc_html($testimonial['position']); ?></p>
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
                        column-gap: 1.5rem;
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
                        break-inside: avoid;
                        display: inline-block;
                        width: 100%;
                    }
                </style>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>