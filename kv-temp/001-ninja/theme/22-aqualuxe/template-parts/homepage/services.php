<?php
/**
 * Template part for displaying services section on the homepage
 *
 * @package AquaLuxe
 */

// Get section settings from theme options or use defaults
$section_title = aqualuxe_get_option( 'services_title', 'Our Services' );
$section_subtitle = aqualuxe_get_option( 'services_subtitle', 'Comprehensive aquatic solutions for all your needs' );
$services_count = aqualuxe_get_option( 'services_count', 3 );
$show_view_all = aqualuxe_get_option( 'services_show_view_all', true );
$view_all_text = aqualuxe_get_option( 'services_view_all_text', 'View All Services' );

// Check if we have a custom services page
$services_page_id = aqualuxe_get_option( 'services_page_id', 0 );
$view_all_url = $services_page_id ? get_permalink( $services_page_id ) : '#';

// Get services layout style
$services_layout = aqualuxe_get_option( 'services_layout', 'grid' );

// Set up the query arguments
$args = array(
    'post_type'      => 'service', // Assuming 'service' is the custom post type
    'posts_per_page' => $services_count,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
);

// Check if the custom post type exists
if ( ! post_type_exists( 'service' ) ) {
    // Fallback to regular posts with a specific category
    $services_category = aqualuxe_get_option( 'services_category', '' );
    
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $services_count,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    );
    
    if ( $services_category ) {
        $args['category_name'] = $services_category;
    }
}

// Run the query
$services = new WP_Query( $args );

// Only display the section if we have services
if ( ! $services->have_posts() ) {
    return;
}

// Set up grid columns class
$grid_columns_class = '';
switch ( $services_count ) {
    case 2:
        $grid_columns_class = 'grid-cols-1 md:grid-cols-2';
        break;
    case 3:
        $grid_columns_class = 'grid-cols-1 md:grid-cols-3';
        break;
    case 4:
        $grid_columns_class = 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4';
        break;
    case 6:
        $grid_columns_class = 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3';
        break;
    default:
        $grid_columns_class = 'grid-cols-1 md:grid-cols-3';
}

// Set up layout specific classes
$container_class = '';
$item_class = '';

switch ( $services_layout ) {
    case 'list':
        $container_class = 'space-y-8';
        $item_class = 'flex flex-col md:flex-row gap-6';
        break;
    case 'cards':
        $container_class = 'grid ' . $grid_columns_class . ' gap-6';
        $item_class = 'card p-6';
        break;
    case 'icons':
        $container_class = 'grid ' . $grid_columns_class . ' gap-6';
        $item_class = 'text-center p-6';
        break;
    case 'grid':
    default:
        $container_class = 'grid ' . $grid_columns_class . ' gap-6';
        $item_class = '';
}
?>

<section id="services" class="services py-16">
    <div class="container-fluid max-w-screen-xl mx-auto px-4">
        <div class="section-header text-center mb-12">
            <?php if ( $section_title ) : ?>
                <h2 class="section-title text-3xl md:text-4xl font-serif font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_subtitle ) : ?>
                <p class="section-subtitle text-lg text-gray-600 dark:text-gray-300"><?php echo esc_html( $section_subtitle ); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="services-container <?php echo esc_attr( $container_class ); ?>">
            <?php
            while ( $services->have_posts() ) :
                $services->the_post();
                
                // Get service icon if available (for custom post types)
                $service_icon = '';
                if ( function_exists( 'get_field' ) ) {
                    $service_icon = get_field( 'service_icon' );
                }
                
                // Default icon if none is set
                if ( empty( $service_icon ) ) {
                    $service_icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>';
                }
                ?>
                <div class="service-item <?php echo esc_attr( $item_class ); ?>">
                    <?php if ( $services_layout === 'list' ) : ?>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="service-image md:w-1/3">
                                <a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded-lg">
                                    <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-auto hover:scale-105 transition-transform duration-300' ) ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="service-content md:w-2/3">
                            <h3 class="service-title text-xl font-bold mb-3">
                                <a href="<?php the_permalink(); ?>" class="hover:text-primary-500 transition-colors duration-200">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            
                            <div class="service-excerpt text-gray-600 dark:text-gray-300 mb-4">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-500 hover:text-primary-600 transition-colors duration-200">
                                <?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    
                    <?php elseif ( $services_layout === 'cards' ) : ?>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="service-image mb-4">
                                <a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded-lg">
                                    <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-48 object-cover hover:scale-105 transition-transform duration-300' ) ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <h3 class="service-title text-xl font-bold mb-3">
                            <a href="<?php the_permalink(); ?>" class="hover:text-primary-500 transition-colors duration-200">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <div class="service-excerpt text-gray-600 dark:text-gray-300 mb-4">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-500 hover:text-primary-600 transition-colors duration-200">
                            <?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    
                    <?php elseif ( $services_layout === 'icons' ) : ?>
                        <div class="service-icon flex justify-center mb-4">
                            <?php echo $service_icon; ?>
                        </div>
                        
                        <h3 class="service-title text-xl font-bold mb-3">
                            <a href="<?php the_permalink(); ?>" class="hover:text-primary-500 transition-colors duration-200">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <div class="service-excerpt text-gray-600 dark:text-gray-300 mb-4">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-500 hover:text-primary-600 transition-colors duration-200">
                            <?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    
                    <?php else : /* Default grid layout */ ?>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="service-image mb-4">
                                <a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded-lg">
                                    <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-48 object-cover hover:scale-105 transition-transform duration-300' ) ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <h3 class="service-title text-xl font-bold mb-3">
                            <a href="<?php the_permalink(); ?>" class="hover:text-primary-500 transition-colors duration-200">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <div class="service-excerpt text-gray-600 dark:text-gray-300 mb-4">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-500 hover:text-primary-600 transition-colors duration-200">
                            <?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
        
        <?php if ( $show_view_all && $view_all_url !== '#' ) : ?>
            <div class="view-all text-center mt-12">
                <a href="<?php echo esc_url( $view_all_url ); ?>" class="btn-outline">
                    <?php echo esc_html( $view_all_text ); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>