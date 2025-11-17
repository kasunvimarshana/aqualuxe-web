<?php
/**
 * Homepage Partners Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer settings
$section_title = get_theme_mod('aqualuxe_homepage_partners_title', __('Our Partners', 'aqualuxe'));
$section_subtitle = get_theme_mod('aqualuxe_homepage_partners_subtitle', __('Trusted by leading brands and organizations', 'aqualuxe'));
$show_section = get_theme_mod('aqualuxe_homepage_partners_show', true);

// Get partners from customizer
$partners = get_theme_mod('aqualuxe_homepage_partners', [
    [
        'name' => 'AquaTech',
        'logo' => get_template_directory_uri() . '/assets/dist/images/partners/partner-1.png',
        'url' => '#',
    ],
    [
        'name' => 'Marine World',
        'logo' => get_template_directory_uri() . '/assets/dist/images/partners/partner-2.png',
        'url' => '#',
    ],
    [
        'name' => 'Ocean Labs',
        'logo' => get_template_directory_uri() . '/assets/dist/images/partners/partner-3.png',
        'url' => '#',
    ],
    [
        'name' => 'Reef Systems',
        'logo' => get_template_directory_uri() . '/assets/dist/images/partners/partner-4.png',
        'url' => '#',
    ],
    [
        'name' => 'Aqua Research Institute',
        'logo' => get_template_directory_uri() . '/assets/dist/images/partners/partner-5.png',
        'url' => '#',
    ],
    [
        'name' => 'Global Marine Conservation',
        'logo' => get_template_directory_uri() . '/assets/dist/images/partners/partner-6.png',
        'url' => '#',
    ],
]);

// Exit if section is disabled
if (!$show_section) {
    return;
}

// Exit if no partners
if (empty($partners)) {
    return;
}
?>

<section class="aqualuxe-partners py-16 bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <?php if ($section_title) : ?>
                <h2 class="text-3xl font-bold mb-4"><?php echo esc_html($section_title); ?></h2>
            <?php endif; ?>
            
            <?php if ($section_subtitle) : ?>
                <p class="text-lg text-gray-600 dark:text-gray-400"><?php echo esc_html($section_subtitle); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
            <?php foreach ($partners as $partner) : ?>
                <?php if (!empty($partner['logo'])) : ?>
                    <div class="flex items-center justify-center">
                        <?php if (!empty($partner['url'])) : ?>
                            <a href="<?php echo esc_url($partner['url']); ?>" class="transition-opacity hover:opacity-80" target="_blank" rel="noopener">
                                <img src="<?php echo esc_url($partner['logo']); ?>" alt="<?php echo esc_attr($partner['name']); ?>" class="max-h-16 max-w-full grayscale hover:grayscale-0 transition-all duration-300">
                            </a>
                        <?php else : ?>
                            <img src="<?php echo esc_url($partner['logo']); ?>" alt="<?php echo esc_attr($partner['name']); ?>" class="max-h-16 max-w-full grayscale">
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>