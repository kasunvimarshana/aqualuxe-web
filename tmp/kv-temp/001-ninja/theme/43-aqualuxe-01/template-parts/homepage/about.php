<?php
/**
 * Template part for displaying the about section on the homepage
 *
 * @package AquaLuxe
 */

// Get about section settings from customizer or use defaults
$section_title = get_theme_mod( 'aqualuxe_about_title', __( 'About AquaLuxe', 'aqualuxe' ) );
$section_description = get_theme_mod( 'aqualuxe_about_description', __( 'Bringing elegance to aquatic life – globally. We are passionate about providing the highest quality ornamental fish, aquatic plants, and custom aquarium solutions for enthusiasts and professionals.', 'aqualuxe' ) );
$section_content = get_theme_mod( 'aqualuxe_about_content', '' );
$section_image = get_theme_mod( 'aqualuxe_about_image', get_template_directory_uri() . '/assets/dist/images/about-default.jpg' );
$button_text = get_theme_mod( 'aqualuxe_about_button_text', __( 'Learn More', 'aqualuxe' ) );
$button_url = get_theme_mod( 'aqualuxe_about_button_url', '#' );
$show_section = get_theme_mod( 'aqualuxe_about_show', true );
$layout = get_theme_mod( 'aqualuxe_about_layout', 'image-left' );

// If section is disabled, return
if ( ! $show_section ) {
    return;
}

// Set layout classes
$image_order = ( 'image-right' === $layout ) ? 'lg:order-last' : '';
?>

<section class="about-section py-16">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="about-image w-full lg:w-1/2 <?php echo esc_attr( $image_order ); ?>">
                <img src="<?php echo esc_url( $section_image ); ?>" alt="<?php echo esc_attr( $section_title ); ?>" class="rounded-lg shadow-lg w-full h-auto object-cover" />
            </div>
            
            <div class="about-content w-full lg:w-1/2">
                <?php if ( $section_title ) : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
                <?php endif; ?>
                
                <?php if ( $section_description ) : ?>
                    <p class="text-xl text-gray-600 dark:text-gray-400 mb-6"><?php echo esc_html( $section_description ); ?></p>
                <?php endif; ?>
                
                <?php if ( $section_content ) : ?>
                    <div class="about-text prose max-w-none mb-8">
                        <?php echo wp_kses_post( wpautop( $section_content ) ); ?>
                    </div>
                <?php endif; ?>
                
                <div class="about-features grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="feature flex items-start">
                        <div class="feature-icon mr-4 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="feature-content">
                            <h3 class="text-lg font-bold mb-1"><?php esc_html_e( 'Premium Quality', 'aqualuxe' ); ?></h3>
                            <p class="text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Carefully selected and ethically sourced aquatic species.', 'aqualuxe' ); ?></p>
                        </div>
                    </div>
                    
                    <div class="feature flex items-start">
                        <div class="feature-icon mr-4 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="feature-content">
                            <h3 class="text-lg font-bold mb-1"><?php esc_html_e( 'Global Shipping', 'aqualuxe' ); ?></h3>
                            <p class="text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Safe and secure international delivery to your doorstep.', 'aqualuxe' ); ?></p>
                        </div>
                    </div>
                    
                    <div class="feature flex items-start">
                        <div class="feature-icon mr-4 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="feature-content">
                            <h3 class="text-lg font-bold mb-1"><?php esc_html_e( 'Expert Advice', 'aqualuxe' ); ?></h3>
                            <p class="text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Professional guidance for all your aquatic needs.', 'aqualuxe' ); ?></p>
                        </div>
                    </div>
                    
                    <div class="feature flex items-start">
                        <div class="feature-icon mr-4 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="feature-content">
                            <h3 class="text-lg font-bold mb-1"><?php esc_html_e( '24/7 Support', 'aqualuxe' ); ?></h3>
                            <p class="text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Round-the-clock assistance for all your queries.', 'aqualuxe' ); ?></p>
                        </div>
                    </div>
                </div>
                
                <?php if ( $button_text && $button_url ) : ?>
                    <a href="<?php echo esc_url( $button_url ); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <?php echo esc_html( $button_text ); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>