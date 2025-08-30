<?php
/**
 * Template part for displaying the homepage testimonials section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get section settings from theme options
$section_title = get_theme_mod('aqualuxe_testimonials_title', __('What Our Clients Say', 'aqualuxe'));
$section_subtitle = get_theme_mod('aqualuxe_testimonials_subtitle', __('Testimonials', 'aqualuxe'));
$section_description = get_theme_mod('aqualuxe_testimonials_description', __('Hear from our satisfied customers about their experience with AquaLuxe.', 'aqualuxe'));
$testimonials_count = get_theme_mod('aqualuxe_testimonials_count', 6);
$section_style = get_theme_mod('aqualuxe_testimonials_style', 'carousel');
$section_background = get_theme_mod('aqualuxe_testimonials_background', 'image');
$background_image = get_theme_mod('aqualuxe_testimonials_bg_image', get_template_directory_uri() . '/assets/images/testimonials-bg.jpg');

// Set background class based on setting
$bg_class = '';
$overlay_class = '';

if ($section_background === 'image' && $background_image) {
    $bg_class = 'bg-cover bg-center relative';
    $overlay_class = 'after:absolute after:inset-0 after:bg-primary after:bg-opacity-80 after:z-0';
} elseif ($section_background === 'primary') {
    $bg_class = 'bg-primary';
} else {
    $bg_class = 'bg-gray-50';
}

// Set text color class based on background
$text_color_class = ($section_background === 'image' || $section_background === 'primary') ? 'text-white' : '';
?>

<section class="testimonials-section py-16 <?php echo esc_attr($bg_class); ?> <?php echo esc_attr($overlay_class); ?>" <?php echo ($section_background === 'image' && $background_image) ? 'style="background-image: url(' . esc_url($background_image) . ');"' : ''; ?>>
    <div class="container mx-auto px-4 relative z-10">
        <div class="section-header text-center mb-12 <?php echo esc_attr($text_color_class); ?>">
            <?php if ($section_subtitle) : ?>
                <div class="section-subtitle <?php echo ($section_background === 'image' || $section_background === 'primary') ? 'text-white text-opacity-90' : 'text-primary'; ?> text-lg mb-2">
                    <?php echo esc_html($section_subtitle); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($section_title) : ?>
                <h2 class="section-title text-3xl md:text-4xl font-bold mb-4">
                    <?php echo esc_html($section_title); ?>
                </h2>
            <?php endif; ?>
            
            <?php if ($section_description) : ?>
                <div class="section-description max-w-3xl mx-auto <?php echo ($section_background === 'image' || $section_background === 'primary') ? 'text-white text-opacity-90' : 'text-gray-600'; ?>">
                    <?php echo wp_kses_post($section_description); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php
        // Query testimonials
        $args = array(
            'post_type'      => 'testimonial',
            'posts_per_page' => $testimonials_count,
            'post_status'    => 'publish',
        );
        
        $testimonials_query = new WP_Query($args);
        
        if ($testimonials_query->have_posts()) :
            
            if ($section_style === 'carousel') :
                // Carousel layout
                ?>
                <div class="testimonials-carousel swiper-container">
                    <div class="swiper-wrapper">
                        <?php
                        while ($testimonials_query->have_posts()) :
                            $testimonials_query->the_post();
                            
                            // Get testimonial meta
                            $client_name = get_post_meta(get_the_ID(), '_aqualuxe_testimonial_client_name', true);
                            $client_position = get_post_meta(get_the_ID(), '_aqualuxe_testimonial_client_position', true);
                            $client_company = get_post_meta(get_the_ID(), '_aqualuxe_testimonial_client_company', true);
                            $rating = get_post_meta(get_the_ID(), '_aqualuxe_testimonial_rating', true);
                            ?>
                            
                            <div class="swiper-slide">
                                <div class="testimonial-card bg-white rounded-lg shadow-md p-8 text-center">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="client-image mx-auto mb-4">
                                            <?php the_post_thumbnail('thumbnail', array('class' => 'w-20 h-20 rounded-full object-cover mx-auto')); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($rating) : ?>
                                        <div class="testimonial-rating flex justify-center mb-4">
                                            <?php
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $rating) {
                                                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>';
                                                } else {
                                                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="testimonial-content text-gray-700 mb-6">
                                        <svg class="w-8 h-8 text-primary opacity-20 mb-2 mx-auto" fill="currentColor" viewBox="0 0 32 32" aria-hidden="true">
                                            <path d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z" />
                                        </svg>
                                        <?php the_content(); ?>
                                    </div>
                                    
                                    <div class="client-info">
                                        <?php if ($client_name) : ?>
                                            <h4 class="client-name text-lg font-bold">
                                                <?php echo esc_html($client_name); ?>
                                            </h4>
                                        <?php endif; ?>
                                        
                                        <?php if ($client_position || $client_company) : ?>
                                            <div class="client-position text-gray-600">
                                                <?php
                                                if ($client_position && $client_company) {
                                                    echo esc_html($client_position) . ', ' . esc_html($client_company);
                                                } elseif ($client_position) {
                                                    echo esc_html($client_position);
                                                } elseif ($client_company) {
                                                    echo esc_html($client_company);
                                                }
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                        <?php endwhile; ?>
                    </div>
                    
                    <div class="swiper-pagination mt-8"></div>
                </div>
            <?php else : ?>
                <!-- Grid layout -->
                <div class="testimonials-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    while ($testimonials_query->have_posts()) :
                        $testimonials_query->the_post();
                        
                        // Get testimonial meta
                        $client_name = get_post_meta(get_the_ID(), '_aqualuxe_testimonial_client_name', true);
                        $client_position = get_post_meta(get_the_ID(), '_aqualuxe_testimonial_client_position', true);
                        $client_company = get_post_meta(get_the_ID(), '_aqualuxe_testimonial_client_company', true);
                        $rating = get_post_meta(get_the_ID(), '_aqualuxe_testimonial_rating', true);
                        ?>
                        
                        <div class="testimonial-card bg-white rounded-lg shadow-md p-6">
                            <div class="flex items-start mb-4">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="client-image mr-4">
                                        <?php the_post_thumbnail('thumbnail', array('class' => 'w-16 h-16 rounded-full object-cover')); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="client-info">
                                    <?php if ($client_name) : ?>
                                        <h4 class="client-name text-lg font-bold">
                                            <?php echo esc_html($client_name); ?>
                                        </h4>
                                    <?php endif; ?>
                                    
                                    <?php if ($client_position || $client_company) : ?>
                                        <div class="client-position text-gray-600 text-sm">
                                            <?php
                                            if ($client_position && $client_company) {
                                                echo esc_html($client_position) . ', ' . esc_html($client_company);
                                            } elseif ($client_position) {
                                                echo esc_html($client_position);
                                            } elseif ($client_company) {
                                                echo esc_html($client_company);
                                            }
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <?php if ($rating) : ?>
                                <div class="testimonial-rating flex mb-4">
                                    <?php
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $rating) {
                                            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>';
                                        } else {
                                            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>';
                                        }
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="testimonial-content text-gray-700">
                                <?php the_content(); ?>
                            </div>
                        </div>
                        
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
            
            <?php
            wp_reset_postdata();
        else :
            ?>
            <div class="no-testimonials text-center py-8 <?php echo esc_attr($text_color_class); ?>">
                <p><?php esc_html_e('No testimonials found.', 'aqualuxe'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>