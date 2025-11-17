<?php
/**
 * About Page History Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer settings
$section_title = get_theme_mod('aqualuxe_about_history_title', __('Our History', 'aqualuxe'));
$section_subtitle = get_theme_mod('aqualuxe_about_history_subtitle', __('The journey of AquaLuxe from a small local shop to a global brand', 'aqualuxe'));
$section_content = get_theme_mod('aqualuxe_about_history_content', __('AquaLuxe was founded in 2010 with a vision to bring elegance and innovation to aquatic environments. What started as a small local shop has grown into a global brand known for quality and excellence.', 'aqualuxe'));
$show_section = get_theme_mod('aqualuxe_about_history_show', true);

// Get timeline from customizer
$timeline = get_theme_mod('aqualuxe_about_history_timeline', [
    [
        'year' => '2010',
        'title' => __('Foundation', 'aqualuxe'),
        'description' => __('AquaLuxe was founded by marine biologist Dr. James Anderson in San Francisco, California.', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/dist/images/history/2010.jpg',
    ],
    [
        'year' => '2013',
        'title' => __('First International Store', 'aqualuxe'),
        'description' => __('Opened our first international store in London, UK, marking the beginning of our global expansion.', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/dist/images/history/2013.jpg',
    ],
    [
        'year' => '2015',
        'title' => __('Launch of Premium Line', 'aqualuxe'),
        'description' => __('Introduced our premium line of custom aquariums, setting new standards in the industry.', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/dist/images/history/2015.jpg',
    ],
    [
        'year' => '2018',
        'title' => __('Conservation Initiative', 'aqualuxe'),
        'description' => __('Launched the AquaLuxe Conservation Initiative, dedicating 5% of profits to marine conservation efforts.', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/dist/images/history/2018.jpg',
    ],
    [
        'year' => '2020',
        'title' => __('10 Year Anniversary', 'aqualuxe'),
        'description' => __('Celebrated our 10-year anniversary with the opening of our 50th store worldwide.', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/dist/images/history/2020.jpg',
    ],
    [
        'year' => '2023',
        'title' => __('Digital Transformation', 'aqualuxe'),
        'description' => __('Launched our innovative mobile app and AI-powered aquarium monitoring system.', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/dist/images/history/2023.jpg',
    ],
]);

// Exit if section is disabled
if (!$show_section) {
    return;
}
?>

<section class="aqualuxe-history py-16 bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <?php if ($section_title) : ?>
                <h2 class="text-3xl font-bold mb-4"><?php echo esc_html($section_title); ?></h2>
            <?php endif; ?>
            
            <?php if ($section_subtitle) : ?>
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-6"><?php echo esc_html($section_subtitle); ?></p>
            <?php endif; ?>
            
            <?php if ($section_content) : ?>
                <div class="max-w-3xl mx-auto prose prose-lg dark:prose-invert">
                    <?php echo wpautop(wp_kses_post($section_content)); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($timeline)) : ?>
            <div class="timeline relative">
                <!-- Timeline center line -->
                <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-primary-600"></div>
                
                <?php foreach ($timeline as $index => $item) : ?>
                    <?php 
                    // Alternate left and right for desktop
                    $is_left = $index % 2 === 0;
                    $desktop_classes = $is_left ? 'md:flex-row' : 'md:flex-row-reverse';
                    ?>
                    
                    <div class="timeline-item mb-12 last:mb-0">
                        <div class="flex flex-col <?php echo esc_attr($desktop_classes); ?> items-center">
                            <!-- Year marker (visible on mobile) -->
                            <div class="md:hidden w-12 h-12 rounded-full bg-primary-600 text-white flex items-center justify-center font-bold text-lg mb-4">
                                <?php echo esc_html($item['year']); ?>
                            </div>
                            
                            <!-- Content -->
                            <div class="md:w-5/12 mb-6 md:mb-0 <?php echo $is_left ? 'md:pr-12' : 'md:pl-12'; ?>">
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                    <?php if (!empty($item['year'])) : ?>
                                        <div class="text-primary-600 font-bold text-2xl mb-2"><?php echo esc_html($item['year']); ?></div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($item['title'])) : ?>
                                        <h3 class="text-xl font-bold mb-3"><?php echo esc_html($item['title']); ?></h3>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($item['description'])) : ?>
                                        <p class="text-gray-600 dark:text-gray-300"><?php echo esc_html($item['description']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Year marker (visible on desktop) -->
                            <div class="hidden md:flex w-12 h-12 rounded-full bg-primary-600 text-white items-center justify-center font-bold text-lg z-10">
                                <?php echo esc_html($item['year']); ?>
                            </div>
                            
                            <!-- Image -->
                            <div class="md:w-5/12 <?php echo $is_left ? 'md:pl-12' : 'md:pr-12'; ?>">
                                <?php if (!empty($item['image'])) : ?>
                                    <div class="rounded-lg overflow-hidden shadow-md">
                                        <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['title']); ?>" class="w-full h-auto">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>