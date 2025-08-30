<?php
/**
 * Template part for displaying the homepage about section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get section settings from theme options
$section_title = get_theme_mod('aqualuxe_about_title', __('About AquaLuxe', 'aqualuxe'));
$section_subtitle = get_theme_mod('aqualuxe_about_subtitle', __('Our Story', 'aqualuxe'));
$section_content = get_theme_mod('aqualuxe_about_content', __('AquaLuxe was founded with a passion for bringing the beauty and tranquility of aquatic life into homes and businesses worldwide. With over 20 years of experience in ornamental fish farming, we have developed sustainable practices that ensure the health of our fish and the environment.', 'aqualuxe'));
$section_image = get_theme_mod('aqualuxe_about_image', get_template_directory_uri() . '/assets/images/about-default.jpg');
$button_text = get_theme_mod('aqualuxe_about_button_text', __('Learn More About Us', 'aqualuxe'));
$button_url = get_theme_mod('aqualuxe_about_button_url', '#');
$section_layout = get_theme_mod('aqualuxe_about_layout', 'image-left');

// Set layout classes based on setting
$container_class = $section_layout === 'image-left' ? 'flex-row' : 'flex-row-reverse';
?>

<section class="about-section py-16">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:<?php echo esc_attr($container_class); ?> items-center gap-12">
            <div class="about-image w-full lg:w-1/2">
                <?php if ($section_image) : ?>
                    <img src="<?php echo esc_url($section_image); ?>" alt="<?php echo esc_attr($section_title); ?>" class="w-full h-auto rounded-lg shadow-lg">
                <?php endif; ?>
            </div>
            
            <div class="about-content w-full lg:w-1/2">
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
                    <div class="section-content prose max-w-none mb-8">
                        <?php echo wp_kses_post(wpautop($section_content)); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($button_text && $button_url) : ?>
                    <a href="<?php echo esc_url($button_url); ?>" class="button button-primary">
                        <?php echo esc_html($button_text); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>