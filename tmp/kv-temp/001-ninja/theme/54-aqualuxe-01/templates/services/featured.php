<?php
/**
 * Featured Service Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer options
$title = get_theme_mod('aqualuxe_services_featured_title', __('Premium Pool Installation', 'aqualuxe'));
$subtitle = get_theme_mod('aqualuxe_services_featured_subtitle', __('Our Signature Service', 'aqualuxe'));
$description = get_theme_mod('aqualuxe_services_featured_description', __('Our premium pool installation service combines expert craftsmanship with innovative design to create the perfect aquatic environment for your home or business.', 'aqualuxe'));
$image = get_theme_mod('aqualuxe_services_featured_image', get_template_directory_uri() . '/assets/images/services/featured-service.jpg');
$video_url = get_theme_mod('aqualuxe_services_featured_video', '');
$button_text = get_theme_mod('aqualuxe_services_featured_button_text', __('Request a Quote', 'aqualuxe'));
$button_url = get_theme_mod('aqualuxe_services_featured_button_url', '#quote');
$features = get_theme_mod('aqualuxe_services_featured_features', [
    [
        'icon' => 'check-circle',
        'text' => __('Professional design consultation', 'aqualuxe'),
    ],
    [
        'icon' => 'check-circle',
        'text' => __('Custom pool shapes and sizes', 'aqualuxe'),
    ],
    [
        'icon' => 'check-circle',
        'text' => __('Premium materials and finishes', 'aqualuxe'),
    ],
    [
        'icon' => 'check-circle',
        'text' => __('Advanced filtration systems', 'aqualuxe'),
    ],
    [
        'icon' => 'check-circle',
        'text' => __('Smart pool technology integration', 'aqualuxe'),
    ],
    [
        'icon' => 'check-circle',
        'text' => __('Comprehensive warranty coverage', 'aqualuxe'),
    ],
]);
$background_color = get_theme_mod('aqualuxe_services_featured_bg', 'bg-blue-50 dark:bg-blue-900');
?>

<section class="featured-service py-16 md:py-24 <?php echo esc_attr($background_color); ?>">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-center">
            <div class="w-full lg:w-1/2 mb-10 lg:mb-0">
                <div class="relative rounded-lg overflow-hidden shadow-xl">
                    <?php if ($video_url) : ?>
                        <div class="aspect-w-16 aspect-h-9">
                            <?php 
                            // Extract video ID if it's a YouTube URL
                            if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                                preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video_url, $matches);
                                $youtube_id = $matches[1] ?? '';
                                
                                if ($youtube_id) {
                                    echo '<iframe src="https://www.youtube.com/embed/' . esc_attr($youtube_id) . '?rel=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                                }
                            } else {
                                echo '<video controls class="w-full h-full object-cover"><source src="' . esc_url($video_url) . '" type="video/mp4">Your browser does not support the video tag.</video>';
                            }
                            ?>
                        </div>
                    <?php elseif ($image) : ?>
                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" class="w-full h-auto">
                        
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="play-button w-20 h-20 rounded-full bg-blue-600 bg-opacity-80 flex items-center justify-center cursor-pointer transition-transform duration-300 hover:transform hover:scale-110">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="w-full lg:w-1/2 lg:pl-12">
                <?php if ($subtitle) : ?>
                    <span class="inline-block px-3 py-1 mb-4 text-sm font-semibold tracking-wider uppercase rounded-full text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-800 bg-opacity-50">
                        <?php echo esc_html($subtitle); ?>
                    </span>
                <?php endif; ?>
                
                <?php if ($title) : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-900 dark:text-white">
                        <?php echo esc_html($title); ?>
                    </h2>
                <?php endif; ?>
                
                <?php if ($description) : ?>
                    <p class="text-lg mb-8 text-gray-700 dark:text-gray-300">
                        <?php echo esc_html($description); ?>
                    </p>
                <?php endif; ?>
                
                <?php if ($features && is_array($features)) : ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <?php foreach ($features as $feature) : ?>
                            <?php if (!empty($feature['text'])) : ?>
                                <div class="flex items-center">
                                    <?php if (!empty($feature['icon'])) : ?>
                                        <i class="fas fa-<?php echo esc_attr($feature['icon']); ?> text-blue-600 dark:text-blue-400 mr-2"></i>
                                    <?php endif; ?>
                                    <span class="text-gray-800 dark:text-gray-200"><?php echo esc_html($feature['text']); ?></span>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($button_text && $button_url) : ?>
                    <a href="<?php echo esc_url($button_url); ?>" class="btn btn-primary text-lg px-8 py-3">
                        <?php echo esc_html($button_text); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>