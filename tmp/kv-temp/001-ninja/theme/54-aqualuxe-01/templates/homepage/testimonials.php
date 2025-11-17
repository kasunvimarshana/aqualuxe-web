<?php
/**
 * Homepage Testimonials Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer settings
$section_title = get_theme_mod('aqualuxe_homepage_testimonials_title', __('What Our Customers Say', 'aqualuxe'));
$section_subtitle = get_theme_mod('aqualuxe_homepage_testimonials_subtitle', __('Hear from our satisfied customers around the world', 'aqualuxe'));
$show_section = get_theme_mod('aqualuxe_homepage_testimonials_show', true);
$background_image = get_theme_mod('aqualuxe_homepage_testimonials_bg', get_template_directory_uri() . '/assets/dist/images/testimonials-bg.jpg');
$overlay_opacity = get_theme_mod('aqualuxe_homepage_testimonials_overlay', 0.7);

// Get testimonials from customizer
$testimonials = get_theme_mod('aqualuxe_homepage_testimonials', [
    [
        'content' => __('AquaLuxe transformed my living room with a stunning custom aquarium. Their attention to detail and customer service is unmatched. I couldn\'t be happier with the result!', 'aqualuxe'),
        'name' => __('Sarah Johnson', 'aqualuxe'),
        'position' => __('Home Aquarium Enthusiast', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/dist/images/testimonial-1.jpg',
        'rating' => 5,
    ],
    [
        'content' => __('As a restaurant owner, I wanted a unique centerpiece for my seafood establishment. AquaLuxe designed and installed a magnificent reef tank that has become the talk of the town. Their maintenance service is also excellent.', 'aqualuxe'),
        'name' => __('Michael Chen', 'aqualuxe'),
        'position' => __('Restaurant Owner', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/dist/images/testimonial-2.jpg',
        'rating' => 5,
    ],
    [
        'content' => __('I\'ve been in the aquarium hobby for over 20 years, and AquaLuxe products are simply the best I\'ve used. Their filtration systems are top-notch, and their customer support team is always helpful and knowledgeable.', 'aqualuxe'),
        'name' => __('Emily Rodriguez', 'aqualuxe'),
        'position' => __('Professional Aquarist', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/dist/images/testimonial-3.jpg',
        'rating' => 5,
    ],
]);

// Exit if section is disabled
if (!$show_section) {
    return;
}

// Set background style
$bg_style = '';
if ($background_image) {
    $bg_style = 'background-image: url(' . esc_url($background_image) . ');';
}

// Set overlay style
$overlay_style = '';
if ($overlay_opacity) {
    $overlay_style = 'background-color: rgba(0, 0, 0, ' . esc_attr($overlay_opacity) . ');';
}
?>

<section class="aqualuxe-testimonials py-16 bg-cover bg-center relative" style="<?php echo esc_attr($bg_style); ?>">
    <div class="absolute inset-0" style="<?php echo esc_attr($overlay_style); ?>"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center mb-12 text-white">
            <?php if ($section_title) : ?>
                <h2 class="text-3xl font-bold mb-4"><?php echo esc_html($section_title); ?></h2>
            <?php endif; ?>
            
            <?php if ($section_subtitle) : ?>
                <p class="text-lg opacity-90"><?php echo esc_html($section_subtitle); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="testimonials-slider">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php foreach ($testimonials as $testimonial) : ?>
                        <div class="swiper-slide">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mx-2">
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
    </div>
</section>

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