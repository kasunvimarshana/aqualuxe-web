<?php
/**
 * Template part for displaying testimonials section on the homepage
 *
 * @package AquaLuxe
 */

// Get section settings from theme options or use defaults
$section_title = aqualuxe_get_option( 'testimonials_title', 'What Our Customers Say' );
$section_subtitle = aqualuxe_get_option( 'testimonials_subtitle', 'Hear from our satisfied customers around the world' );
$testimonials_count = aqualuxe_get_option( 'testimonials_count', 3 );
$testimonials_layout = aqualuxe_get_option( 'testimonials_layout', 'slider' );
$testimonials_bg = aqualuxe_get_option( 'testimonials_bg', 'color' );
$testimonials_bg_image = aqualuxe_get_option( 'testimonials_bg_image', '' );

// Set up the query arguments
$args = array(
    'post_type'      => 'testimonial',
    'posts_per_page' => $testimonials_count,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
);

// Check if the custom post type exists
if ( ! post_type_exists( 'testimonial' ) ) {
    // Fallback to regular posts with a specific category
    $testimonials_category = aqualuxe_get_option( 'testimonials_category', '' );
    
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $testimonials_count,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    );
    
    if ( $testimonials_category ) {
        $args['category_name'] = $testimonials_category;
    }
}

// Run the query
$testimonials = new WP_Query( $args );

// Only display the section if we have testimonials
if ( ! $testimonials->have_posts() ) {
    return;
}

// Set up section classes based on background type
$section_class = '';
$container_class = '';

switch ( $testimonials_bg ) {
    case 'image':
        if ( ! empty( $testimonials_bg_image ) ) {
            $section_class = 'bg-cover bg-center relative';
            $container_class = 'relative z-10';
            $overlay_class = 'absolute inset-0 bg-dark-500 bg-opacity-70';
            $text_class = 'text-white';
        } else {
            $section_class = 'bg-primary-500 text-white';
            $text_class = 'text-white';
        }
        break;
    case 'primary':
        $section_class = 'bg-primary-500 text-white';
        $text_class = 'text-white';
        break;
    case 'secondary':
        $section_class = 'bg-secondary-500 text-white';
        $text_class = 'text-white';
        break;
    case 'dark':
        $section_class = 'bg-dark-500 text-white';
        $text_class = 'text-white';
        break;
    case 'color':
    default:
        $section_class = 'bg-white dark:bg-dark-500';
        $text_class = '';
}

// Set up layout specific classes
$testimonials_container_class = '';
$testimonial_item_class = '';

switch ( $testimonials_layout ) {
    case 'grid':
        $testimonials_container_class = 'grid grid-cols-1 md:grid-cols-3 gap-6';
        $testimonial_item_class = 'testimonial-item';
        break;
    case 'masonry':
        $testimonials_container_class = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6';
        $testimonial_item_class = 'testimonial-item';
        break;
    case 'slider':
    default:
        $testimonials_container_class = 'aqualuxe-slider relative';
        $testimonial_item_class = 'aqualuxe-slide';
}

// Style for testimonial cards
$card_class = 'bg-white dark:bg-dark-400 rounded-lg shadow-lg p-6';
if ( $testimonials_bg === 'image' || $testimonials_bg === 'primary' || $testimonials_bg === 'secondary' || $testimonials_bg === 'dark' ) {
    $card_class = 'bg-white dark:bg-dark-400 text-dark-500 dark:text-white rounded-lg shadow-lg p-6';
}
?>

<section id="testimonials" class="testimonials py-16 <?php echo esc_attr( $section_class ); ?>" <?php if ( $testimonials_bg === 'image' && ! empty( $testimonials_bg_image ) ) : ?>style="background-image: url('<?php echo esc_url( $testimonials_bg_image ); ?>');"<?php endif; ?>>
    <?php if ( $testimonials_bg === 'image' && ! empty( $testimonials_bg_image ) ) : ?>
        <div class="<?php echo esc_attr( $overlay_class ); ?>"></div>
    <?php endif; ?>
    
    <div class="container-fluid max-w-screen-xl mx-auto px-4 <?php echo esc_attr( $container_class ); ?>">
        <div class="section-header text-center mb-12">
            <?php if ( $section_title ) : ?>
                <h2 class="section-title text-3xl md:text-4xl font-serif font-bold mb-4 <?php echo esc_attr( $text_class ); ?>"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_subtitle ) : ?>
                <p class="section-subtitle text-lg <?php echo esc_attr( $text_class ? $text_class : 'text-gray-600 dark:text-gray-300' ); ?>"><?php echo esc_html( $section_subtitle ); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="testimonials-container <?php echo esc_attr( $testimonials_container_class ); ?>">
            <?php
            while ( $testimonials->have_posts() ) :
                $testimonials->the_post();
                
                // Get testimonial details
                $testimonial_content = get_the_content();
                $testimonial_author = get_the_title();
                
                // Get testimonial meta data (for custom post types)
                $testimonial_position = '';
                $testimonial_company = '';
                $testimonial_rating = 5; // Default rating
                
                if ( function_exists( 'get_field' ) ) {
                    $testimonial_position = get_field( 'position' );
                    $testimonial_company = get_field( 'company' );
                    $testimonial_rating = get_field( 'rating' ) ? get_field( 'rating' ) : 5;
                }
                ?>
                <div class="<?php echo esc_attr( $testimonial_item_class ); ?>">
                    <div class="<?php echo esc_attr( $card_class ); ?>">
                        <?php if ( $testimonial_rating ) : ?>
                            <div class="testimonial-rating flex text-yellow-400 mb-4">
                                <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                    <?php if ( $i <= $testimonial_rating ) : ?>
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
                        
                        <div class="testimonial-content mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary-500 mb-2 opacity-50" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                            </svg>
                            <div class="prose dark:prose-invert max-w-none">
                                <?php echo wpautop( $testimonial_content ); ?>
                            </div>
                        </div>
                        
                        <div class="testimonial-author flex items-center">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="testimonial-avatar mr-4">
                                    <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-12 h-12 rounded-full object-cover' ) ); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="testimonial-info">
                                <h4 class="testimonial-name font-bold"><?php echo esc_html( $testimonial_author ); ?></h4>
                                <?php if ( $testimonial_position || $testimonial_company ) : ?>
                                    <p class="testimonial-meta text-sm text-gray-600 dark:text-gray-400">
                                        <?php 
                                        if ( $testimonial_position && $testimonial_company ) {
                                            echo esc_html( $testimonial_position ) . ', ' . esc_html( $testimonial_company );
                                        } elseif ( $testimonial_position ) {
                                            echo esc_html( $testimonial_position );
                                        } elseif ( $testimonial_company ) {
                                            echo esc_html( $testimonial_company );
                                        }
                                        ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
        
        <?php if ( $testimonials_layout === 'slider' ) : ?>
            <div class="slider-controls flex justify-center mt-8 space-x-4">
                <button class="aqualuxe-slider-prev w-12 h-12 rounded-full bg-white dark:bg-dark-400 shadow-md flex items-center justify-center text-gray-700 dark:text-gray-300 hover:text-primary-500 dark:hover:text-primary-400 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span class="sr-only"><?php esc_html_e( 'Previous', 'aqualuxe' ); ?></span>
                </button>
                
                <button class="aqualuxe-slider-next w-12 h-12 rounded-full bg-white dark:bg-dark-400 shadow-md flex items-center justify-center text-gray-700 dark:text-gray-300 hover:text-primary-500 dark:hover:text-primary-400 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="sr-only"><?php esc_html_e( 'Next', 'aqualuxe' ); ?></span>
                </button>
            </div>
            
            <div class="slider-dots flex justify-center mt-4 space-x-2">
                <?php for ( $i = 0; $i < min( $testimonials->post_count, $testimonials_count ); $i++ ) : ?>
                    <button class="aqualuxe-slider-dot w-3 h-3 rounded-full bg-gray-300 dark:bg-gray-600 focus:outline-none <?php echo $i === 0 ? 'bg-primary-500' : ''; ?>"></button>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</section>