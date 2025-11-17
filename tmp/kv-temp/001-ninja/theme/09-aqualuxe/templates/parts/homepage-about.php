<?php
/**
 * Homepage About Section
 *
 * @package AquaLuxe
 */

// Get section content from theme options or use default
$section_title = get_theme_mod('aqualuxe_about_title', __('About AquaLuxe', 'aqualuxe'));
$section_content = get_theme_mod('aqualuxe_about_content', __('AquaLuxe is a vertically integrated ornamental fish farming and aquatic lifestyle company. We serve both local and international markets across multiple segments, providing premium quality fish, plants, and aquarium solutions.', 'aqualuxe'));
$section_image = get_theme_mod('aqualuxe_about_image', get_template_directory_uri() . '/assets/images/about-default.jpg');
$button_text = get_theme_mod('aqualuxe_about_button_text', __('Learn More About Us', 'aqualuxe'));
$button_url = get_theme_mod('aqualuxe_about_button_url', get_permalink(get_page_by_path('about')));
$layout = get_theme_mod('aqualuxe_about_layout', 'image-left'); // image-left, image-right
$show_features = get_theme_mod('aqualuxe_about_show_features', true);

// Features
$features = array(
    array(
        'icon' => 'fa-fish',
        'title' => __('Premium Quality', 'aqualuxe'),
        'description' => __('Healthy, vibrant fish and plants sourced ethically.', 'aqualuxe'),
    ),
    array(
        'icon' => 'fa-globe-americas',
        'title' => __('Global Shipping', 'aqualuxe'),
        'description' => __('We export to customers worldwide with proper care.', 'aqualuxe'),
    ),
    array(
        'icon' => 'fa-certificate',
        'title' => __('Certified Experts', 'aqualuxe'),
        'description' => __('Our team includes aquaculture specialists and marine biologists.', 'aqualuxe'),
    ),
);

// Allow features to be filtered
$features = apply_filters('aqualuxe_about_features', $features);

// Set layout classes
$container_class = $layout === 'image-left' ? 'flex-row' : 'flex-row-reverse';
?>

<section class="about-section py-16 bg-gray-50">
    <div class="container">
        <div class="flex flex-col lg:<?php echo esc_attr($container_class); ?> items-center gap-12">
            <?php if ($section_image) : ?>
                <div class="about-image lg:w-1/2">
                    <img src="<?php echo esc_url($section_image); ?>" alt="<?php echo esc_attr($section_title); ?>" class="rounded-lg shadow-lg w-full h-auto">
                </div>
            <?php endif; ?>
            
            <div class="about-content lg:w-1/2">
                <?php if ($section_title) : ?>
                    <h2 class="section-title text-3xl md:text-4xl font-bold mb-6"><?php echo esc_html($section_title); ?></h2>
                <?php endif; ?>
                
                <?php if ($section_content) : ?>
                    <div class="section-content prose max-w-none mb-8">
                        <?php echo wp_kses_post(wpautop($section_content)); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($show_features && !empty($features)) : ?>
                    <div class="about-features grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <?php foreach ($features as $feature) : ?>
                            <div class="feature text-center">
                                <?php if (!empty($feature['icon'])) : ?>
                                    <div class="feature-icon text-4xl text-primary mb-3">
                                        <i class="fa <?php echo esc_attr($feature['icon']); ?>"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($feature['title'])) : ?>
                                    <h3 class="feature-title text-lg font-bold mb-2"><?php echo esc_html($feature['title']); ?></h3>
                                <?php endif; ?>
                                
                                <?php if (!empty($feature['description'])) : ?>
                                    <p class="feature-description text-gray-600"><?php echo esc_html($feature['description']); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($button_text && $button_url) : ?>
                    <a href="<?php echo esc_url($button_url); ?>" class="btn btn-primary">
                        <?php echo esc_html($button_text); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>