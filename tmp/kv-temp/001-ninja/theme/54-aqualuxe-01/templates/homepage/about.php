<?php
/**
 * Homepage About Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer settings
$section_title = get_theme_mod('aqualuxe_homepage_about_title', __('About AquaLuxe', 'aqualuxe'));
$section_content = get_theme_mod('aqualuxe_homepage_about_content', __('AquaLuxe is a premium aquatic products company dedicated to bringing elegance to aquatic life globally. With over 15 years of experience, we provide high-quality aquariums, accessories, and services for both enthusiasts and professionals.', 'aqualuxe'));
$section_image = get_theme_mod('aqualuxe_homepage_about_image', get_template_directory_uri() . '/assets/dist/images/about-default.jpg');
$section_button_text = get_theme_mod('aqualuxe_homepage_about_button_text', __('Learn More', 'aqualuxe'));
$section_button_url = get_theme_mod('aqualuxe_homepage_about_button_url', '#');
$section_layout = get_theme_mod('aqualuxe_homepage_about_layout', 'image-left'); // image-left, image-right
$show_section = get_theme_mod('aqualuxe_homepage_about_show', true);
$section_features = get_theme_mod('aqualuxe_homepage_about_features', [
    [
        'icon' => 'trophy',
        'title' => __('Premium Quality', 'aqualuxe'),
        'description' => __('All our products meet the highest industry standards', 'aqualuxe'),
    ],
    [
        'icon' => 'globe',
        'title' => __('Global Shipping', 'aqualuxe'),
        'description' => __('We deliver our products worldwide with care', 'aqualuxe'),
    ],
    [
        'icon' => 'headset',
        'title' => __('Expert Support', 'aqualuxe'),
        'description' => __('Our team of experts is always ready to help', 'aqualuxe'),
    ],
]);

// Exit if section is disabled
if (!$show_section) {
    return;
}

// Set layout classes
$container_class = $section_layout === 'image-left' ? 'flex-col lg:flex-row' : 'flex-col lg:flex-row-reverse';
?>

<section class="aqualuxe-about py-16 bg-white dark:bg-gray-800">
    <div class="container mx-auto px-4">
        <div class="flex <?php echo esc_attr($container_class); ?> items-center gap-12">
            <div class="lg:w-1/2">
                <div class="rounded-lg overflow-hidden shadow-lg">
                    <img src="<?php echo esc_url($section_image); ?>" alt="<?php echo esc_attr($section_title); ?>" class="w-full h-auto object-cover">
                </div>
            </div>
            
            <div class="lg:w-1/2">
                <?php if ($section_title) : ?>
                    <h2 class="text-3xl font-bold mb-6"><?php echo esc_html($section_title); ?></h2>
                <?php endif; ?>
                
                <?php if ($section_content) : ?>
                    <div class="prose prose-lg dark:prose-invert mb-8">
                        <?php echo wpautop(wp_kses_post($section_content)); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($section_features)) : ?>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <?php foreach ($section_features as $feature) : ?>
                            <div class="flex flex-col items-center text-center">
                                <?php if (!empty($feature['icon'])) : ?>
                                    <div class="text-primary-600 mb-3">
                                        <?php aqualuxe_icon($feature['icon'], ['class' => 'w-10 h-10']); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($feature['title'])) : ?>
                                    <h3 class="text-lg font-semibold mb-2"><?php echo esc_html($feature['title']); ?></h3>
                                <?php endif; ?>
                                
                                <?php if (!empty($feature['description'])) : ?>
                                    <p class="text-gray-600 dark:text-gray-400"><?php echo esc_html($feature['description']); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($section_button_text && $section_button_url) : ?>
                    <a href="<?php echo esc_url($section_button_url); ?>" class="btn btn-primary">
                        <?php echo esc_html($section_button_text); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>