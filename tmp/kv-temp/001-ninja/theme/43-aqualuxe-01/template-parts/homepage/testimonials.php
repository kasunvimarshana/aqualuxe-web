<?php
/**
 * Template part for displaying the testimonials section on the homepage
 *
 * @package AquaLuxe
 */

// Get testimonials section settings from customizer or use defaults
$section_title = get_theme_mod( 'aqualuxe_testimonials_title', __( 'What Our Customers Say', 'aqualuxe' ) );
$section_description = get_theme_mod( 'aqualuxe_testimonials_description', __( 'Read what our satisfied customers have to say about their experience with AquaLuxe.', 'aqualuxe' ) );
$show_section = get_theme_mod( 'aqualuxe_testimonials_show', true );

// If section is disabled, return
if ( ! $show_section ) {
    return;
}

// Check if we have custom testimonials post type
$has_testimonials_cpt = post_type_exists( 'testimonial' );

// Define default testimonials if custom post type doesn't exist
$default_testimonials = array(
    array(
        'name' => __( 'John Smith', 'aqualuxe' ),
        'role' => __( 'Aquarium Enthusiast', 'aqualuxe' ),
        'content' => __( 'AquaLuxe has transformed my home with their stunning custom aquarium. Their attention to detail and knowledge of aquatic species is unmatched. The maintenance service keeps everything pristine with minimal effort on my part.', 'aqualuxe' ),
        'rating' => 5,
        'image' => get_template_directory_uri() . '/assets/dist/images/testimonial-1.jpg',
    ),
    array(
        'name' => __( 'Emily Johnson', 'aqualuxe' ),
        'role' => __( 'Restaurant Owner', 'aqualuxe' ),
        'content' => __( 'The custom reef tank that AquaLuxe designed for our restaurant has become a centerpiece that customers love. Their professional maintenance team ensures it always looks spectacular, and their staff is always responsive to our needs.', 'aqualuxe' ),
        'rating' => 5,
        'image' => get_template_directory_uri() . '/assets/dist/images/testimonial-2.jpg',
    ),
    array(
        'name' => __( 'Michael Chen', 'aqualuxe' ),
        'role' => __( 'Rare Fish Collector', 'aqualuxe' ),
        'content' => __( 'As a serious collector of rare species, I\'ve worked with many suppliers over the years. AquaLuxe stands out for their exceptional quarantine procedures and ability to source healthy, high-quality specimens that are difficult to find elsewhere.', 'aqualuxe' ),
        'rating' => 5,
        'image' => get_template_directory_uri() . '/assets/dist/images/testimonial-3.jpg',
    ),
);
?>

<section class="testimonials-section py-16">
    <div class="container mx-auto px-4">
        <div class="section-header text-center mb-12">
            <?php if ( $section_title ) : ?>
                <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_description ) : ?>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto"><?php echo esc_html( $section_description ); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="testimonials-slider">
            <div class="testimonials-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                if ( $has_testimonials_cpt ) {
                    // Get testimonials from custom post type
                    $args = array(
                        'post_type'      => 'testimonial',
                        'posts_per_page' => 3,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    );
                    
                    $testimonials_query = new WP_Query( $args );
                    
                    if ( $testimonials_query->have_posts() ) {
                        while ( $testimonials_query->have_posts() ) {
                            $testimonials_query->the_post();
                            
                            // Get testimonial meta
                            $name = get_the_title();
                            $role = get_post_meta( get_the_ID(), 'testimonial_role', true );
                            $rating = get_post_meta( get_the_ID(), 'testimonial_rating', true );
                            if ( empty( $rating ) ) {
                                $rating = 5;
                            }
                            ?>
                            <div class="testimonial-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 relative">
                                <div class="testimonial-quote absolute top-6 right-6 text-gray-200 dark:text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                                    </svg>
                                </div>
                                
                                <div class="testimonial-rating flex mb-4">
                                    <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                        <?php if ( $i <= $rating ) : ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        <?php else : ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300 dark:text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                
                                <div class="testimonial-content text-gray-600 dark:text-gray-400 mb-6">
                                    <?php the_content(); ?>
                                </div>
                                
                                <div class="testimonial-author flex items-center">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <div class="testimonial-image mr-4">
                                            <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-12 h-12 rounded-full object-cover' ) ); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="testimonial-info">
                                        <h4 class="testimonial-name font-bold"><?php echo esc_html( $name ); ?></h4>
                                        <?php if ( $role ) : ?>
                                            <p class="testimonial-role text-sm text-gray-500 dark:text-gray-400"><?php echo esc_html( $role ); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        wp_reset_postdata();
                    } else {
                        // Use default testimonials if no custom testimonials found
                        foreach ( $default_testimonials as $testimonial ) {
                            ?>
                            <div class="testimonial-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 relative">
                                <div class="testimonial-quote absolute top-6 right-6 text-gray-200 dark:text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                                    </svg>
                                </div>
                                
                                <div class="testimonial-rating flex mb-4">
                                    <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                        <?php if ( $i <= $testimonial['rating'] ) : ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        <?php else : ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300 dark:text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                
                                <div class="testimonial-content text-gray-600 dark:text-gray-400 mb-6">
                                    <p><?php echo esc_html( $testimonial['content'] ); ?></p>
                                </div>
                                
                                <div class="testimonial-author flex items-center">
                                    <?php if ( ! empty( $testimonial['image'] ) ) : ?>
                                        <div class="testimonial-image mr-4">
                                            <img src="<?php echo esc_url( $testimonial['image'] ); ?>" alt="<?php echo esc_attr( $testimonial['name'] ); ?>" class="w-12 h-12 rounded-full object-cover" />
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="testimonial-info">
                                        <h4 class="testimonial-name font-bold"><?php echo esc_html( $testimonial['name'] ); ?></h4>
                                        <?php if ( ! empty( $testimonial['role'] ) ) : ?>
                                            <p class="testimonial-role text-sm text-gray-500 dark:text-gray-400"><?php echo esc_html( $testimonial['role'] ); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                } else {
                    // Use default testimonials if custom post type doesn't exist
                    foreach ( $default_testimonials as $testimonial ) {
                        ?>
                        <div class="testimonial-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 relative">
                            <div class="testimonial-quote absolute top-6 right-6 text-gray-200 dark:text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                                </svg>
                            </div>
                            
                            <div class="testimonial-rating flex mb-4">
                                <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                    <?php if ( $i <= $testimonial['rating'] ) : ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    <?php else : ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300 dark:text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            
                            <div class="testimonial-content text-gray-600 dark:text-gray-400 mb-6">
                                <p><?php echo esc_html( $testimonial['content'] ); ?></p>
                            </div>
                            
                            <div class="testimonial-author flex items-center">
                                <?php if ( ! empty( $testimonial['image'] ) ) : ?>
                                    <div class="testimonial-image mr-4">
                                        <img src="<?php echo esc_url( $testimonial['image'] ); ?>" alt="<?php echo esc_attr( $testimonial['name'] ); ?>" class="w-12 h-12 rounded-full object-cover" />
                                    </div>
                                <?php endif; ?>
                                
                                <div class="testimonial-info">
                                    <h4 class="testimonial-name font-bold"><?php echo esc_html( $testimonial['name'] ); ?></h4>
                                    <?php if ( ! empty( $testimonial['role'] ) ) : ?>
                                        <p class="testimonial-role text-sm text-gray-500 dark:text-gray-400"><?php echo esc_html( $testimonial['role'] ); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>