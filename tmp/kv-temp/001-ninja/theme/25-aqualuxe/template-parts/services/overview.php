<?php
/**
 * Template part for displaying the services page overview section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get page ID
$page_id = get_the_ID();

// Get section settings from page meta or theme options
$section_title = get_post_meta($page_id, '_aqualuxe_services_overview_title', true);
if (!$section_title) {
    $section_title = get_theme_mod('aqualuxe_services_overview_title', __('How We Can Help You', 'aqualuxe'));
}

$section_subtitle = get_post_meta($page_id, '_aqualuxe_services_overview_subtitle', true);
if (!$section_subtitle) {
    $section_subtitle = get_theme_mod('aqualuxe_services_overview_subtitle', __('Service Overview', 'aqualuxe'));
}

$section_content = get_post_meta($page_id, '_aqualuxe_services_overview_content', true);
if (!$section_content) {
    $section_content = get_theme_mod('aqualuxe_services_overview_content', '
        <p>At AquaLuxe, we offer a comprehensive range of services designed to meet the needs of ornamental fish enthusiasts, collectors, and businesses alike. Our team of experienced professionals is dedicated to providing exceptional service and expertise in all aspects of aquatic care.</p>
        
        <p>From custom aquarium design and installation to specialized breeding programs and maintenance services, we have the knowledge and resources to help you create and maintain a thriving aquatic environment.</p>
        
        <p>Our services are tailored to meet your specific needs, whether you\'re a hobbyist looking to enhance your home aquarium or a business seeking to create a stunning aquatic display for your customers.</p>
    ');
}

$section_image = get_post_meta($page_id, '_aqualuxe_services_overview_image', true);
if (!$section_image) {
    $section_image = get_theme_mod('aqualuxe_services_overview_image', get_template_directory_uri() . '/assets/images/services-overview.jpg');
}

$section_layout = get_post_meta($page_id, '_aqualuxe_services_overview_layout', true);
if (!$section_layout) {
    $section_layout = get_theme_mod('aqualuxe_services_overview_layout', 'image-right');
}

// Set layout classes based on setting
$container_class = $section_layout === 'image-right' ? 'flex-row' : 'flex-row-reverse';
?>

<section class="services-overview-section py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:<?php echo esc_attr($container_class); ?> items-center gap-12">
            <div class="overview-content w-full lg:w-1/2">
                <?php if ($section_subtitle) : ?>
                    <div class="section-subtitle text-primary text-lg mb-2">
                        <?php echo esc_html($section_subtitle); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($section_title) : ?>
                    <h2 class="section-title text-3xl md:text-4xl font-bold mb-6">
                        <?php echo esc_html($section_title); ?>
                    </h2>
                <?php endif; ?>
                
                <?php if ($section_content) : ?>
                    <div class="section-content prose max-w-none">
                        <?php echo wp_kses_post(wpautop($section_content)); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="overview-image w-full lg:w-1/2">
                <?php if ($section_image) : ?>
                    <img src="<?php echo esc_url($section_image); ?>" alt="<?php echo esc_attr($section_title); ?>" class="w-full h-auto rounded-lg shadow-lg">
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>