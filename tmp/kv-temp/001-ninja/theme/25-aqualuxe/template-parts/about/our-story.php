<?php
/**
 * Template part for displaying the about page "Our Story" section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get page ID
$page_id = get_the_ID();

// Get section settings from page meta or theme options
$section_title = get_post_meta($page_id, '_aqualuxe_about_story_title', true);
if (!$section_title) {
    $section_title = get_theme_mod('aqualuxe_about_story_title', __('Our Story', 'aqualuxe'));
}

$section_subtitle = get_post_meta($page_id, '_aqualuxe_about_story_subtitle', true);
if (!$section_subtitle) {
    $section_subtitle = get_theme_mod('aqualuxe_about_story_subtitle', __('The AquaLuxe Journey', 'aqualuxe'));
}

$section_content = get_post_meta($page_id, '_aqualuxe_about_story_content', true);
if (!$section_content) {
    $section_content = get_theme_mod('aqualuxe_about_story_content', '
        <p>AquaLuxe was founded in 2010 by marine biologist Dr. Sarah Chen and aquaculture specialist Michael Rodriguez, who shared a passion for ornamental fish and a vision for sustainable aquaculture practices.</p>
        
        <p>What began as a small breeding facility with just five species has grown into a global leader in ornamental fish farming, with over 200 species and varieties cultivated across our eco-friendly facilities.</p>
        
        <p>Our journey has been guided by our commitment to three core principles: exceptional quality, sustainable practices, and innovation in aquaculture. These principles have helped us establish AquaLuxe as a trusted name among hobbyists, collectors, and commercial partners worldwide.</p>
        
        <p>Today, AquaLuxe operates state-of-the-art breeding facilities in three countries, employs over 120 dedicated professionals, and exports to more than 40 countries. Despite our growth, we remain committed to the hands-on approach and attention to detail that defined our early days.</p>
    ');
}

$section_image = get_post_meta($page_id, '_aqualuxe_about_story_image', true);
if (!$section_image) {
    $section_image = get_theme_mod('aqualuxe_about_story_image', get_template_directory_uri() . '/assets/images/about-story.jpg');
}

$section_layout = get_post_meta($page_id, '_aqualuxe_about_story_layout', true);
if (!$section_layout) {
    $section_layout = get_theme_mod('aqualuxe_about_story_layout', 'image-right');
}

// Set layout classes based on setting
$container_class = $section_layout === 'image-right' ? 'flex-row' : 'flex-row-reverse';
?>

<section class="about-story-section py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:<?php echo esc_attr($container_class); ?> items-center gap-12">
            <div class="story-content w-full lg:w-1/2">
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
            
            <div class="story-image w-full lg:w-1/2">
                <?php if ($section_image) : ?>
                    <img src="<?php echo esc_url($section_image); ?>" alt="<?php echo esc_attr($section_title); ?>" class="w-full h-auto rounded-lg shadow-lg">
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>