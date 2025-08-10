<?php
/**
 * Template part for displaying the testimonials section on the homepage
 *
 * @package AquaLuxe
 */

// Get customizer options
$testimonials_title = get_theme_mod( 'aqualuxe_testimonials_title', __( 'What Our Customers Say', 'aqualuxe' ) );
$testimonials_subtitle = get_theme_mod( 'aqualuxe_testimonials_subtitle', __( 'Read testimonials from our satisfied customers', 'aqualuxe' ) );
$testimonials_layout = get_theme_mod( 'aqualuxe_testimonials_layout', 'slider' );
$testimonials_background = get_theme_mod( 'aqualuxe_testimonials_background', 'light-gray' );

// Set background class based on setting
$bg_class = 'bg-white dark:bg-gray-900';
if ( $testimonials_background === 'light-gray' ) {
    $bg_class = 'bg-gray-50 dark:bg-gray-800';
} elseif ( $testimonials_background === 'dark' ) {
    $bg_class = 'bg-gray-900 text-white';
} elseif ( $testimonials_background === 'primary' ) {
    $bg_class = 'bg-blue-600 text-white';
}

// Check if we have testimonials custom post type
$has_testimonials_cpt = post_type_exists( 'testimonial' );

// If we have the CPT, get testimonials from it
if ( $has_testimonials_cpt ) {
    $testimonials_args = array(
        'post_type' => 'testimonial',
        'posts_per_page' => 6,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    
    $testimonials_query = new WP_Query( $testimonials_args );
    $testimonials = array();
    
    if ( $testimonials_query->have_posts() ) {
        while ( $testimonials_query->have_posts() ) {
            $testimonials_query->the_post();
            
            $testimonials[] = array(
                'content' => get_the_content(),
                'name' => get_the_title(),
                'position' => get_post_meta( get_the_ID(), '_testimonial_position', true ),
                'company' => get_post_meta( get_the_ID(), '_testimonial_company', true ),
                'rating' => get_post_meta( get_the_ID(), '_testimonial_rating', true ),
                'image' => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
            );
        }
        wp_reset_postdata();
    }
} else {
    // Default testimonials if no CPT or no testimonials found
    $testimonials = array(
        array(
            'content' => __( 'AquaLuxe has transformed our online presence. The theme is not only beautiful but also incredibly easy to customize. Our sales have increased by 30% since we launched our new website!', 'aqualuxe' ),
            'name' => __( 'Jane Smith', 'aqualuxe' ),
            'position' => __( 'CEO', 'aqualuxe' ),
            'company' => __( 'Oceanview Spa', 'aqualuxe' ),
            'rating' => 5,
            'image' => get_template_directory_uri() . '/assets/images/testimonials/testimonial-1.jpg',
        ),
        array(
            'content' => __( 'As a developer, I appreciate the clean code and thoughtful architecture of AquaLuxe. It\'s been a joy to work with and my clients are thrilled with the results. Highly recommended!', 'aqualuxe' ),
            'name' => __( 'Michael Johnson', 'aqualuxe' ),
            'position' => __( 'Web Developer', 'aqualuxe' ),
            'company' => __( 'DevStudio', 'aqualuxe' ),
            'rating' => 5,
            'image' => get_template_directory_uri() . '/assets/images/testimonials/testimonial-2.jpg',
        ),
        array(
            'content' => __( 'The WooCommerce integration is flawless. Setting up our online store was a breeze, and the checkout process is smooth and professional. Our customers love the shopping experience!', 'aqualuxe' ),
            'name' => __( 'Sarah Williams', 'aqualuxe' ),
            'position' => __( 'E-commerce Manager', 'aqualuxe' ),
            'company' => __( 'Aqua Boutique', 'aqualuxe' ),
            'rating' => 4,
            'image' => get_template_directory_uri() . '/assets/images/testimonials/testimonial-3.jpg',
        ),
    );
}

// Function to display star ratings
function aqualuxe_display_rating( $rating ) {
    $output = '<div class="flex gap-1 mb-3">';
    
    for ( $i = 1; $i <= 5; $i++ ) {
        if ( $i <= $rating ) {
            $output .= '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>';
        } else {
            $output .= '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300 dark:text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>';
        }
    }
    
    $output .= '</div>';
    return $output;
}

?>

<section id="testimonials" class="testimonials-section py-16 md:py-24 <?php echo esc_attr( $bg_class ); ?>">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12 md:mb-16">
            <?php if ( $testimonials_title ) : ?>
                <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html( $testimonials_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $testimonials_subtitle ) : ?>
                <p class="text-xl opacity-80 max-w-3xl mx-auto"><?php echo esc_html( $testimonials_subtitle ); ?></p>
            <?php endif; ?>
        </div>
        
        <?php if ( ! empty( $testimonials ) ) : ?>
            <?php if ( $testimonials_layout === 'slider' ) : ?>
                <div class="testimonials-slider relative" data-slider>
                    <div class="overflow-hidden">
                        <div class="flex transition-transform duration-300" data-slider-container>
                            <?php foreach ( $testimonials as $testimonial ) : ?>
                                <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-4" data-slider-item>
                                    <div class="testimonial-item h-full p-6 rounded-lg <?php echo $testimonials_background === 'dark' || $testimonials_background === 'primary' ? 'bg-white/10' : 'bg-white dark:bg-gray-800 shadow-md'; ?>">
                                        <?php if ( ! empty( $testimonial['rating'] ) ) : ?>
                                            <?php echo aqualuxe_display_rating( $testimonial['rating'] ); ?>
                                        <?php endif; ?>
                                        
                                        <?php if ( ! empty( $testimonial['content'] ) ) : ?>
                                            <div class="testimonial-content mb-6">
                                                <p class="italic"><?php echo esc_html( $testimonial['content'] ); ?></p>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="testimonial-author flex items-center">
                                            <?php if ( ! empty( $testimonial['image'] ) ) : ?>
                                                <div class="testimonial-image mr-4">
                                                    <img src="<?php echo esc_url( $testimonial['image'] ); ?>" alt="<?php echo esc_attr( $testimonial['name'] ); ?>" class="w-12 h-12 rounded-full object-cover" />
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="testimonial-info">
                                                <?php if ( ! empty( $testimonial['name'] ) ) : ?>
                                                    <h4 class="font-bold"><?php echo esc_html( $testimonial['name'] ); ?></h4>
                                                <?php endif; ?>
                                                
                                                <?php if ( ! empty( $testimonial['position'] ) || ! empty( $testimonial['company'] ) ) : ?>
                                                    <p class="text-sm opacity-80">
                                                        <?php 
                                                        if ( ! empty( $testimonial['position'] ) ) {
                                                            echo esc_html( $testimonial['position'] );
                                                            
                                                            if ( ! empty( $testimonial['company'] ) ) {
                                                                echo ', ';
                                                            }
                                                        }
                                                        
                                                        if ( ! empty( $testimonial['company'] ) ) {
                                                            echo esc_html( $testimonial['company'] );
                                                        }
                                                        ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="slider-controls flex justify-center mt-8 gap-2">
                        <button class="slider-prev p-2 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors" data-slider-prev>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button class="slider-next p-2 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors" data-slider-next>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            <?php else : ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ( $testimonials as $testimonial ) : ?>
                        <div class="testimonial-item h-full p-6 rounded-lg <?php echo $testimonials_background === 'dark' || $testimonials_background === 'primary' ? 'bg-white/10' : 'bg-white dark:bg-gray-800 shadow-md'; ?>">
                            <?php if ( ! empty( $testimonial['rating'] ) ) : ?>
                                <?php echo aqualuxe_display_rating( $testimonial['rating'] ); ?>
                            <?php endif; ?>
                            
                            <?php if ( ! empty( $testimonial['content'] ) ) : ?>
                                <div class="testimonial-content mb-6">
                                    <p class="italic"><?php echo esc_html( $testimonial['content'] ); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="testimonial-author flex items-center">
                                <?php if ( ! empty( $testimonial['image'] ) ) : ?>
                                    <div class="testimonial-image mr-4">
                                        <img src="<?php echo esc_url( $testimonial['image'] ); ?>" alt="<?php echo esc_attr( $testimonial['name'] ); ?>" class="w-12 h-12 rounded-full object-cover" />
                                    </div>
                                <?php endif; ?>
                                
                                <div class="testimonial-info">
                                    <?php if ( ! empty( $testimonial['name'] ) ) : ?>
                                        <h4 class="font-bold"><?php echo esc_html( $testimonial['name'] ); ?></h4>
                                    <?php endif; ?>
                                    
                                    <?php if ( ! empty( $testimonial['position'] ) || ! empty( $testimonial['company'] ) ) : ?>
                                        <p class="text-sm opacity-80">
                                            <?php 
                                            if ( ! empty( $testimonial['position'] ) ) {
                                                echo esc_html( $testimonial['position'] );
                                                
                                                if ( ! empty( $testimonial['company'] ) ) {
                                                    echo ', ';
                                                }
                                            }
                                            
                                            if ( ! empty( $testimonial['company'] ) ) {
                                                echo esc_html( $testimonial['company'] );
                                            }
                                            ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>