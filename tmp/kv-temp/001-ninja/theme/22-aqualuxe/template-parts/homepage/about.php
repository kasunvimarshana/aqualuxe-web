<?php
/**
 * Template part for displaying the about section on the homepage
 *
 * @package AquaLuxe
 */

// Get section settings from theme options or use defaults
$section_title = aqualuxe_get_option( 'about_title', 'About AquaLuxe' );
$section_subtitle = aqualuxe_get_option( 'about_subtitle', 'Premium Ornamental Aquatic Solutions' );
$section_content = aqualuxe_get_option( 'about_content', '' );
$section_image = aqualuxe_get_option( 'about_image', '' );
$button_text = aqualuxe_get_option( 'about_button_text', 'Learn More' );
$button_url = aqualuxe_get_option( 'about_button_url', '#' );
$layout = aqualuxe_get_option( 'about_layout', 'image-left' );

// If no content is set, try to get it from the About page
if ( empty( $section_content ) ) {
    $about_page_id = aqualuxe_get_option( 'about_page_id', 0 );
    
    if ( $about_page_id ) {
        $about_page = get_post( $about_page_id );
        if ( $about_page ) {
            $section_content = $about_page->post_content;
            $button_url = get_permalink( $about_page_id );
            
            // If no image is set, try to get the featured image from the About page
            if ( empty( $section_image ) && has_post_thumbnail( $about_page_id ) ) {
                $section_image = get_the_post_thumbnail_url( $about_page_id, 'large' );
            }
        }
    }
}

// If still no content, use default
if ( empty( $section_content ) ) {
    $section_content = '
        <p class="mb-4">AquaLuxe is a premier provider of ornamental aquatic solutions, specializing in exotic fish, aquarium supplies, and professional aquascaping services. With over 15 years of experience in the industry, we have established ourselves as leaders in providing high-quality aquatic products and services.</p>
        
        <p class="mb-4">Our mission is to bring the beauty and tranquility of aquatic ecosystems into homes and businesses worldwide. We source our fish and plants from sustainable breeders and growers, ensuring both quality and environmental responsibility.</p>
        
        <p>Whether you\'re a beginner looking to set up your first aquarium or an experienced hobbyist seeking rare species, our team of experts is here to guide you through every step of your aquatic journey.</p>
    ';
}

// If no image is set, use default
if ( empty( $section_image ) ) {
    $section_image = get_template_directory_uri() . '/assets/images/about-default.jpg';
}

// Set up layout classes
$container_class = '';
$content_order = '';
$image_order = '';

if ( $layout === 'image-right' ) {
    $content_order = 'order-1';
    $image_order = 'order-2';
} else {
    $content_order = 'order-2 md:order-1';
    $image_order = 'order-1 md:order-2';
}

// Process the content
$section_content = wpautop( $section_content );
?>

<section id="about" class="about py-16 bg-white dark:bg-dark-500">
    <div class="container-fluid max-w-screen-xl mx-auto px-4">
        <div class="section-header text-center mb-12">
            <?php if ( $section_title ) : ?>
                <h2 class="section-title text-3xl md:text-4xl font-serif font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_subtitle ) : ?>
                <p class="section-subtitle text-lg text-gray-600 dark:text-gray-300"><?php echo esc_html( $section_subtitle ); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="about-container grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div class="about-content <?php echo esc_attr( $content_order ); ?>">
                <div class="prose dark:prose-invert max-w-none">
                    <?php echo wp_kses_post( $section_content ); ?>
                </div>
                
                <?php if ( $button_text && $button_url && $button_url !== '#' ) : ?>
                    <div class="about-action mt-8">
                        <a href="<?php echo esc_url( $button_url ); ?>" class="btn-primary">
                            <?php echo esc_html( $button_text ); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if ( aqualuxe_get_option( 'about_show_stats', true ) ) : ?>
                    <div class="about-stats grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <div class="stat-item text-center">
                            <div class="stat-value text-3xl font-bold text-primary-500 mb-1">15+</div>
                            <div class="stat-label text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Years Experience', 'aqualuxe' ); ?></div>
                        </div>
                        
                        <div class="stat-item text-center">
                            <div class="stat-value text-3xl font-bold text-primary-500 mb-1">500+</div>
                            <div class="stat-label text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Fish Species', 'aqualuxe' ); ?></div>
                        </div>
                        
                        <div class="stat-item text-center">
                            <div class="stat-value text-3xl font-bold text-primary-500 mb-1">10K+</div>
                            <div class="stat-label text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Happy Customers', 'aqualuxe' ); ?></div>
                        </div>
                        
                        <div class="stat-item text-center">
                            <div class="stat-value text-3xl font-bold text-primary-500 mb-1">20+</div>
                            <div class="stat-label text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Countries Served', 'aqualuxe' ); ?></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="about-image <?php echo esc_attr( $image_order ); ?>">
                <div class="rounded-lg overflow-hidden shadow-lg">
                    <img src="<?php echo esc_url( $section_image ); ?>" alt="<?php echo esc_attr( $section_title ); ?>" class="w-full h-auto">
                </div>
                
                <?php if ( aqualuxe_get_option( 'about_show_features', true ) ) : ?>
                    <div class="about-features grid grid-cols-2 gap-4 mt-6">
                        <div class="feature-item bg-gray-50 dark:bg-dark-400 p-4 rounded-lg">
                            <div class="feature-icon text-primary-500 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h4 class="feature-title font-bold mb-1"><?php esc_html_e( 'Quality Guarantee', 'aqualuxe' ); ?></h4>
                            <p class="feature-text text-sm text-gray-600 dark:text-gray-300"><?php esc_html_e( 'Premium fish and supplies with satisfaction guarantee', 'aqualuxe' ); ?></p>
                        </div>
                        
                        <div class="feature-item bg-gray-50 dark:bg-dark-400 p-4 rounded-lg">
                            <div class="feature-icon text-primary-500 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                </svg>
                            </div>
                            <h4 class="feature-title font-bold mb-1"><?php esc_html_e( 'Expert Advice', 'aqualuxe' ); ?></h4>
                            <p class="feature-text text-sm text-gray-600 dark:text-gray-300"><?php esc_html_e( 'Professional guidance from aquatic specialists', 'aqualuxe' ); ?></p>
                        </div>
                        
                        <div class="feature-item bg-gray-50 dark:bg-dark-400 p-4 rounded-lg">
                            <div class="feature-icon text-primary-500 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="feature-title font-bold mb-1"><?php esc_html_e( 'Fast Delivery', 'aqualuxe' ); ?></h4>
                            <p class="feature-text text-sm text-gray-600 dark:text-gray-300"><?php esc_html_e( 'Quick and safe shipping for all aquatic life', 'aqualuxe' ); ?></p>
                        </div>
                        
                        <div class="feature-item bg-gray-50 dark:bg-dark-400 p-4 rounded-lg">
                            <div class="feature-icon text-primary-500 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="feature-title font-bold mb-1"><?php esc_html_e( '24/7 Support', 'aqualuxe' ); ?></h4>
                            <p class="feature-text text-sm text-gray-600 dark:text-gray-300"><?php esc_html_e( 'Always available to answer your questions', 'aqualuxe' ); ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>