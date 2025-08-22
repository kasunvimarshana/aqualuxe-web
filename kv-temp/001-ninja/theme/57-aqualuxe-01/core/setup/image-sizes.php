<?php
/**
 * Register custom image sizes
 *
 * @package AquaLuxe
 */

/**
 * Register custom image sizes
 */
function aqualuxe_register_image_sizes() {
    // Hero image size
    add_image_size('aqualuxe-hero', 1920, 800, true);
    
    // Featured image size
    add_image_size('aqualuxe-featured', 800, 600, true);
    
    // Blog thumbnail size
    add_image_size('aqualuxe-blog-thumbnail', 400, 300, true);
    
    // Product gallery thumbnail size
    add_image_size('aqualuxe-product-gallery', 600, 600, true);
    
    // Team member image size
    add_image_size('aqualuxe-team', 300, 300, true);
    
    // Testimonial image size
    add_image_size('aqualuxe-testimonial', 150, 150, true);
    
    // Service image size
    add_image_size('aqualuxe-service', 500, 350, true);
    
    // Partner/Brand logo size
    add_image_size('aqualuxe-partner', 200, 100, false);
}
add_action('after_setup_theme', 'aqualuxe_register_image_sizes');

/**
 * Add custom image sizes to media library dropdown
 *
 * @param array $sizes Array of image sizes.
 * @return array Modified array of image sizes.
 */
function aqualuxe_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'aqualuxe-hero' => esc_html__('Hero Image', 'aqualuxe'),
        'aqualuxe-featured' => esc_html__('Featured Image', 'aqualuxe'),
        'aqualuxe-blog-thumbnail' => esc_html__('Blog Thumbnail', 'aqualuxe'),
        'aqualuxe-product-gallery' => esc_html__('Product Gallery', 'aqualuxe'),
        'aqualuxe-team' => esc_html__('Team Member', 'aqualuxe'),
        'aqualuxe-testimonial' => esc_html__('Testimonial', 'aqualuxe'),
        'aqualuxe-service' => esc_html__('Service', 'aqualuxe'),
        'aqualuxe-partner' => esc_html__('Partner/Brand Logo', 'aqualuxe'),
    ));
}
add_filter('image_size_names_choose', 'aqualuxe_custom_image_sizes');