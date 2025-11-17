<?php
/**
 * Template part for displaying the about page "Mission & Vision" section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get page ID
$page_id = get_the_ID();

// Get section settings from page meta or theme options
$section_title = get_post_meta($page_id, '_aqualuxe_about_mission_title', true);
if (!$section_title) {
    $section_title = get_theme_mod('aqualuxe_about_mission_title', __('Our Mission & Vision', 'aqualuxe'));
}

$section_subtitle = get_post_meta($page_id, '_aqualuxe_about_mission_subtitle', true);
if (!$section_subtitle) {
    $section_subtitle = get_theme_mod('aqualuxe_about_mission_subtitle', __('What Drives Us', 'aqualuxe'));
}

$section_description = get_post_meta($page_id, '_aqualuxe_about_mission_description', true);
if (!$section_description) {
    $section_description = get_theme_mod('aqualuxe_about_mission_description', __('Our guiding principles that shape everything we do at AquaLuxe.', 'aqualuxe'));
}

$mission_title = get_post_meta($page_id, '_aqualuxe_about_mission_mission_title', true);
if (!$mission_title) {
    $mission_title = get_theme_mod('aqualuxe_about_mission_mission_title', __('Our Mission', 'aqualuxe'));
}

$mission_content = get_post_meta($page_id, '_aqualuxe_about_mission_mission_content', true);
if (!$mission_content) {
    $mission_content = get_theme_mod('aqualuxe_about_mission_mission_content', __('To provide the highest quality ornamental fish through sustainable farming practices while promoting responsible aquarium keeping and conservation of aquatic ecosystems.', 'aqualuxe'));
}

$vision_title = get_post_meta($page_id, '_aqualuxe_about_mission_vision_title', true);
if (!$vision_title) {
    $vision_title = get_theme_mod('aqualuxe_about_mission_vision_title', __('Our Vision', 'aqualuxe'));
}

$vision_content = get_post_meta($page_id, '_aqualuxe_about_mission_vision_content', true);
if (!$vision_content) {
    $vision_content = get_theme_mod('aqualuxe_about_mission_vision_content', __('To be the global leader in sustainable ornamental fish farming, setting industry standards for quality, ethics, and environmental stewardship.', 'aqualuxe'));
}

$values_title = get_post_meta($page_id, '_aqualuxe_about_mission_values_title', true);
if (!$values_title) {
    $values_title = get_theme_mod('aqualuxe_about_mission_values_title', __('Our Values', 'aqualuxe'));
}

$values_content = get_post_meta($page_id, '_aqualuxe_about_mission_values_content', true);
if (!$values_content) {
    $values_content = get_theme_mod('aqualuxe_about_mission_values_content', '
        <ul>
            <li><strong>Excellence:</strong> Maintaining the highest standards in all aspects of our operations.</li>
            <li><strong>Sustainability:</strong> Minimizing environmental impact while maximizing resource efficiency.</li>
            <li><strong>Innovation:</strong> Continuously improving our breeding techniques and farming practices.</li>
            <li><strong>Integrity:</strong> Operating with honesty, transparency, and ethical business practices.</li>
            <li><strong>Education:</strong> Sharing knowledge and promoting responsible aquarium keeping.</li>
        </ul>
    ');
}

$section_background = get_post_meta($page_id, '_aqualuxe_about_mission_background', true);
if ($section_background === '') {
    $section_background = get_theme_mod('aqualuxe_about_mission_background', 'gray');
}

// Set background class based on setting
$bg_class = $section_background === 'gray' ? 'bg-gray-50' : 'bg-white';
?>

<section class="mission-vision-section py-16 <?php echo esc_attr($bg_class); ?>">
    <div class="container mx-auto px-4">
        <div class="section-header text-center mb-12">
            <?php if ($section_subtitle) : ?>
                <div class="section-subtitle text-primary text-lg mb-2">
                    <?php echo esc_html($section_subtitle); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($section_title) : ?>
                <h2 class="section-title text-3xl md:text-4xl font-bold mb-4">
                    <?php echo esc_html($section_title); ?>
                </h2>
            <?php endif; ?>
            
            <?php if ($section_description) : ?>
                <div class="section-description max-w-3xl mx-auto text-gray-600">
                    <?php echo wp_kses_post($section_description); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="mission-vision-grid grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="mission-card bg-white rounded-lg shadow-md p-8 transition-transform hover:transform hover:scale-105">
                <div class="card-icon text-primary mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                
                <?php if ($mission_title) : ?>
                    <h3 class="card-title text-2xl font-bold mb-4">
                        <?php echo esc_html($mission_title); ?>
                    </h3>
                <?php endif; ?>
                
                <?php if ($mission_content) : ?>
                    <div class="card-content text-gray-600">
                        <?php echo wp_kses_post(wpautop($mission_content)); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="vision-card bg-white rounded-lg shadow-md p-8 transition-transform hover:transform hover:scale-105">
                <div class="card-icon text-primary mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                
                <?php if ($vision_title) : ?>
                    <h3 class="card-title text-2xl font-bold mb-4">
                        <?php echo esc_html($vision_title); ?>
                    </h3>
                <?php endif; ?>
                
                <?php if ($vision_content) : ?>
                    <div class="card-content text-gray-600">
                        <?php echo wp_kses_post(wpautop($vision_content)); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="values-card bg-white rounded-lg shadow-md p-8 transition-transform hover:transform hover:scale-105">
                <div class="card-icon text-primary mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                
                <?php if ($values_title) : ?>
                    <h3 class="card-title text-2xl font-bold mb-4">
                        <?php echo esc_html($values_title); ?>
                    </h3>
                <?php endif; ?>
                
                <?php if ($values_content) : ?>
                    <div class="card-content text-gray-600 prose">
                        <?php echo wp_kses_post($values_content); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>