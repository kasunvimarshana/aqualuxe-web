<?php
/**
 * About Page Mission & Vision Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer settings
$section_title = get_theme_mod('aqualuxe_about_mission_title', __('Our Mission & Vision', 'aqualuxe'));
$mission_title = get_theme_mod('aqualuxe_about_mission_subtitle', __('Our Mission', 'aqualuxe'));
$mission_content = get_theme_mod('aqualuxe_about_mission_content', __('To provide exceptional aquatic products and services that inspire and enable our customers to create and maintain beautiful, healthy aquatic environments while promoting sustainable practices and conservation efforts.', 'aqualuxe'));
$vision_title = get_theme_mod('aqualuxe_about_vision_subtitle', __('Our Vision', 'aqualuxe'));
$vision_content = get_theme_mod('aqualuxe_about_vision_content', __('To be the global leader in innovative aquatic solutions, recognized for our commitment to quality, sustainability, and customer satisfaction, while fostering a deeper appreciation for aquatic life and ecosystems worldwide.', 'aqualuxe'));
$mission_image = get_theme_mod('aqualuxe_about_mission_image', get_template_directory_uri() . '/assets/dist/images/mission.jpg');
$vision_image = get_theme_mod('aqualuxe_about_vision_image', get_template_directory_uri() . '/assets/dist/images/vision.jpg');
$show_section = get_theme_mod('aqualuxe_about_mission_show', true);

// Exit if section is disabled
if (!$show_section) {
    return;
}
?>

<section class="aqualuxe-mission-vision py-16">
    <div class="container mx-auto px-4">
        <?php if ($section_title) : ?>
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold"><?php echo esc_html($section_title); ?></h2>
            </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div class="mission">
                <?php if ($mission_image) : ?>
                    <div class="mb-6 rounded-lg overflow-hidden shadow-lg">
                        <img src="<?php echo esc_url($mission_image); ?>" alt="<?php echo esc_attr($mission_title); ?>" class="w-full h-auto">
                    </div>
                <?php endif; ?>
                
                <div>
                    <?php if ($mission_title) : ?>
                        <h3 class="text-2xl font-bold mb-4 text-primary-600"><?php echo esc_html($mission_title); ?></h3>
                    <?php endif; ?>
                    
                    <?php if ($mission_content) : ?>
                        <div class="prose prose-lg dark:prose-invert">
                            <?php echo wpautop(wp_kses_post($mission_content)); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="vision">
                <?php if ($vision_image) : ?>
                    <div class="mb-6 rounded-lg overflow-hidden shadow-lg">
                        <img src="<?php echo esc_url($vision_image); ?>" alt="<?php echo esc_attr($vision_title); ?>" class="w-full h-auto">
                    </div>
                <?php endif; ?>
                
                <div>
                    <?php if ($vision_title) : ?>
                        <h3 class="text-2xl font-bold mb-4 text-primary-600"><?php echo esc_html($vision_title); ?></h3>
                    <?php endif; ?>
                    
                    <?php if ($vision_content) : ?>
                        <div class="prose prose-lg dark:prose-invert">
                            <?php echo wpautop(wp_kses_post($vision_content)); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>