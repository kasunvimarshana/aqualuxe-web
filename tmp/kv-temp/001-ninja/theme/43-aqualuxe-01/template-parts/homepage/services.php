<?php
/**
 * Template part for displaying the services section on the homepage
 *
 * @package AquaLuxe
 */

// Get services section settings from customizer or use defaults
$section_title = get_theme_mod( 'aqualuxe_services_title', __( 'Our Services', 'aqualuxe' ) );
$section_description = get_theme_mod( 'aqualuxe_services_description', __( 'We offer a comprehensive range of professional aquatic services to meet all your needs.', 'aqualuxe' ) );
$button_text = get_theme_mod( 'aqualuxe_services_button_text', __( 'View All Services', 'aqualuxe' ) );
$button_url = get_theme_mod( 'aqualuxe_services_button_url', '#' );
$show_section = get_theme_mod( 'aqualuxe_services_show', true );

// If section is disabled, return
if ( ! $show_section ) {
    return;
}

// Check if we have custom services post type
$has_services_cpt = post_type_exists( 'service' );

// Define default services if custom post type doesn't exist
$default_services = array(
    array(
        'title' => __( 'Aquarium Design', 'aqualuxe' ),
        'description' => __( 'Custom aquarium design and installation for homes, offices, and commercial spaces.', 'aqualuxe' ),
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>',
        'link' => '#',
    ),
    array(
        'title' => __( 'Maintenance Services', 'aqualuxe' ),
        'description' => __( 'Regular maintenance, cleaning, and water testing to keep your aquarium in optimal condition.', 'aqualuxe' ),
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
        'link' => '#',
    ),
    array(
        'title' => __( 'Quarantine & Health Check', 'aqualuxe' ),
        'description' => __( 'Professional quarantine services and health checks for new fish and existing aquarium inhabitants.', 'aqualuxe' ),
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>',
        'link' => '#',
    ),
    array(
        'title' => __( 'Breeding Programs', 'aqualuxe' ),
        'description' => __( 'Specialized breeding programs for rare and exotic fish species with expert guidance.', 'aqualuxe' ),
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>',
        'link' => '#',
    ),
    array(
        'title' => __( 'Consultation Services', 'aqualuxe' ),
        'description' => __( 'Expert consultation for aquarium setup, fish selection, and aquascaping design.', 'aqualuxe' ),
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>',
        'link' => '#',
    ),
    array(
        'title' => __( 'Event Rentals', 'aqualuxe' ),
        'description' => __( 'Temporary aquarium setups for events, exhibitions, and special occasions.', 'aqualuxe' ),
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
        'link' => '#',
    ),
);
?>

<section class="services-section py-16 bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4">
        <div class="section-header text-center mb-12">
            <?php if ( $section_title ) : ?>
                <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_description ) : ?>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto"><?php echo esc_html( $section_description ); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="services-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            if ( $has_services_cpt ) {
                // Get services from custom post type
                $args = array(
                    'post_type'      => 'service',
                    'posts_per_page' => 6,
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                );
                
                $services_query = new WP_Query( $args );
                
                if ( $services_query->have_posts() ) {
                    while ( $services_query->have_posts() ) {
                        $services_query->the_post();
                        
                        // Get service icon
                        $service_icon = get_post_meta( get_the_ID(), 'service_icon', true );
                        if ( empty( $service_icon ) ) {
                            $service_icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>';
                        }
                        ?>
                        <div class="service-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-transform hover:shadow-lg hover:-translate-y-1">
                            <div class="service-icon text-primary mb-4">
                                <?php echo $service_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </div>
                            
                            <h3 class="service-title text-xl font-bold mb-3"><?php the_title(); ?></h3>
                            
                            <div class="service-description text-gray-600 dark:text-gray-400 mb-4">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" class="service-link text-primary hover:text-primary-dark font-medium flex items-center">
                                <?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                        <?php
                    }
                    wp_reset_postdata();
                } else {
                    // Use default services if no custom services found
                    foreach ( $default_services as $service ) {
                        ?>
                        <div class="service-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-transform hover:shadow-lg hover:-translate-y-1">
                            <div class="service-icon text-primary mb-4">
                                <?php echo $service['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </div>
                            
                            <h3 class="service-title text-xl font-bold mb-3"><?php echo esc_html( $service['title'] ); ?></h3>
                            
                            <div class="service-description text-gray-600 dark:text-gray-400 mb-4">
                                <p><?php echo esc_html( $service['description'] ); ?></p>
                            </div>
                            
                            <a href="<?php echo esc_url( $service['link'] ); ?>" class="service-link text-primary hover:text-primary-dark font-medium flex items-center">
                                <?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                        <?php
                    }
                }
            } else {
                // Use default services if custom post type doesn't exist
                foreach ( $default_services as $service ) {
                    ?>
                    <div class="service-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-transform hover:shadow-lg hover:-translate-y-1">
                        <div class="service-icon text-primary mb-4">
                            <?php echo $service['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </div>
                        
                        <h3 class="service-title text-xl font-bold mb-3"><?php echo esc_html( $service['title'] ); ?></h3>
                        
                        <div class="service-description text-gray-600 dark:text-gray-400 mb-4">
                            <p><?php echo esc_html( $service['description'] ); ?></p>
                        </div>
                        
                        <a href="<?php echo esc_url( $service['link'] ); ?>" class="service-link text-primary hover:text-primary-dark font-medium flex items-center">
                            <?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        
        <?php if ( $button_text && $button_url ) : ?>
            <div class="view-all text-center mt-12">
                <a href="<?php echo esc_url( $button_url ); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <?php echo esc_html( $button_text ); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>