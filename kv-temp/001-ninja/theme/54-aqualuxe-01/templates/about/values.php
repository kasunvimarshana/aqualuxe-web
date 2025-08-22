<?php
/**
 * About Page Values Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer settings
$section_title = get_theme_mod('aqualuxe_about_values_title', __('Our Core Values', 'aqualuxe'));
$section_subtitle = get_theme_mod('aqualuxe_about_values_subtitle', __('The principles that guide everything we do', 'aqualuxe'));
$show_section = get_theme_mod('aqualuxe_about_values_show', true);
$columns = get_theme_mod('aqualuxe_about_values_columns', 3);
$background_color = get_theme_mod('aqualuxe_about_values_bg', 'light'); // light, dark, primary, gradient

// Get values from customizer
$values = get_theme_mod('aqualuxe_about_values', [
    [
        'icon' => 'quality',
        'title' => __('Excellence', 'aqualuxe'),
        'description' => __('We are committed to delivering the highest quality products and services, exceeding customer expectations in every interaction.', 'aqualuxe'),
    ],
    [
        'icon' => 'innovation',
        'title' => __('Innovation', 'aqualuxe'),
        'description' => __('We continuously seek new and better ways to enhance aquatic environments, embracing creativity and cutting-edge technology.', 'aqualuxe'),
    ],
    [
        'icon' => 'sustainability',
        'title' => __('Sustainability', 'aqualuxe'),
        'description' => __('We are dedicated to environmental responsibility, promoting sustainable practices in all aspects of our business.', 'aqualuxe'),
    ],
    [
        'icon' => 'integrity',
        'title' => __('Integrity', 'aqualuxe'),
        'description' => __('We conduct our business with honesty, transparency, and ethical standards that build trust with our customers and partners.', 'aqualuxe'),
    ],
    [
        'icon' => 'passion',
        'title' => __('Passion', 'aqualuxe'),
        'description' => __('We are driven by our love for aquatic life and environments, bringing enthusiasm and dedication to everything we do.', 'aqualuxe'),
    ],
    [
        'icon' => 'community',
        'title' => __('Community', 'aqualuxe'),
        'description' => __('We value our global community of customers, employees, and partners, fostering relationships built on respect and collaboration.', 'aqualuxe'),
    ],
]);

// Exit if section is disabled
if (!$show_section) {
    return;
}

// Set background class
$bg_class = '';
switch ($background_color) {
    case 'dark':
        $bg_class = 'bg-gray-900 text-white';
        break;
    case 'primary':
        $bg_class = 'bg-primary-600 text-white';
        break;
    case 'gradient':
        $bg_class = 'bg-gradient-to-br from-primary-600 to-secondary-600 text-white';
        break;
    case 'light':
    default:
        $bg_class = 'bg-gray-50 dark:bg-gray-900';
        break;
}

// Set column class
$column_class = '';
switch ($columns) {
    case 1:
        $column_class = 'grid-cols-1';
        break;
    case 2:
        $column_class = 'grid-cols-1 md:grid-cols-2';
        break;
    case 3:
        $column_class = 'grid-cols-1 md:grid-cols-3';
        break;
    case 4:
        $column_class = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4';
        break;
    case 6:
        $column_class = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6';
        break;
}

/**
 * Helper function to display value icons
 */
function aqualuxe_value_icon($icon, $class = 'h-12 w-12') {
    switch ($icon) {
        case 'quality':
            return '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>';
        case 'innovation':
            return '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>';
        case 'sustainability':
            return '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>';
        case 'integrity':
            return '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>';
        case 'passion':
            return '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>';
        case 'community':
            return '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>';
        default:
            return '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>';
    }
}
?>

<section class="aqualuxe-values py-16 <?php echo esc_attr($bg_class); ?>">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <?php if ($section_title) : ?>
                <h2 class="text-3xl font-bold mb-4"><?php echo esc_html($section_title); ?></h2>
            <?php endif; ?>
            
            <?php if ($section_subtitle) : ?>
                <p class="text-lg <?php echo $background_color === 'light' ? 'text-gray-600 dark:text-gray-400' : 'text-gray-200'; ?>"><?php echo esc_html($section_subtitle); ?></p>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($values)) : ?>
            <div class="grid <?php echo esc_attr($column_class); ?> gap-8">
                <?php foreach ($values as $value) : ?>
                    <div class="value-card <?php echo $background_color === 'light' ? 'bg-white dark:bg-gray-800' : 'bg-white/10'; ?> rounded-lg p-6 shadow-md transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                        <?php if (!empty($value['icon'])) : ?>
                            <div class="text-primary-600 mb-4">
                                <?php echo aqualuxe_value_icon($value['icon']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($value['title'])) : ?>
                            <h3 class="text-xl font-bold mb-3"><?php echo esc_html($value['title']); ?></h3>
                        <?php endif; ?>
                        
                        <?php if (!empty($value['description'])) : ?>
                            <p class="<?php echo $background_color === 'light' ? 'text-gray-600 dark:text-gray-300' : 'text-gray-200'; ?>"><?php echo esc_html($value['description']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>