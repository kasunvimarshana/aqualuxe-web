<?php
/**
 * Template part for displaying the FAQ page categories section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get page ID
$page_id = get_the_ID();

// Get section settings from page meta or theme options
$section_title = get_post_meta($page_id, '_aqualuxe_faq_categories_title', true);
if (!$section_title) {
    $section_title = get_theme_mod('aqualuxe_faq_categories_title', __('FAQ Categories', 'aqualuxe'));
}

$section_subtitle = get_post_meta($page_id, '_aqualuxe_faq_categories_subtitle', true);
if (!$section_subtitle) {
    $section_subtitle = get_theme_mod('aqualuxe_faq_categories_subtitle', __('Browse by Topic', 'aqualuxe'));
}

$section_description = get_post_meta($page_id, '_aqualuxe_faq_categories_description', true);
if (!$section_description) {
    $section_description = get_theme_mod('aqualuxe_faq_categories_description', __('Select a category to find answers to your specific questions.', 'aqualuxe'));
}

$section_background = get_post_meta($page_id, '_aqualuxe_faq_categories_background', true);
if ($section_background === '') {
    $section_background = get_theme_mod('aqualuxe_faq_categories_background', 'white');
}

// Set background class based on setting
$bg_class = $section_background === 'gray' ? 'bg-gray-50' : 'bg-white';

// Get FAQ categories
$faq_categories = get_terms(array(
    'taxonomy'   => 'faq_category',
    'hide_empty' => true,
));

// Define default icons for categories
$default_icons = array(
    'general'      => 'information-circle',
    'products'     => 'shopping-bag',
    'services'     => 'briefcase',
    'shipping'     => 'truck',
    'returns'      => 'refresh',
    'payment'      => 'credit-card',
    'account'      => 'user',
    'technical'    => 'cog',
    'maintenance'  => 'wrench',
    'warranty'     => 'shield-check',
    'installation' => 'home',
    'default'      => 'question-mark-circle',
);
?>

<section class="faq-categories-section py-16 <?php echo esc_attr($bg_class); ?>">
    <div class="container mx-auto px-4">
        <?php if ($section_title || $section_subtitle || $section_description) : ?>
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
        <?php endif; ?>
        
        <?php if (!empty($faq_categories)) : ?>
            <div class="faq-categories-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php
                foreach ($faq_categories as $category) :
                    // Get category icon if available (using ACF or custom field)
                    $category_icon = '';
                    if (function_exists('get_field')) {
                        $category_icon = get_field('category_icon', 'faq_category_' . $category->term_id);
                    }
                    
                    // Fallback to default icon based on slug
                    if (!$category_icon) {
                        $slug = $category->slug;
                        $category_icon = isset($default_icons[$slug]) ? $default_icons[$slug] : $default_icons['default'];
                    }
                    
                    // Get category count
                    $category_count = $category->count;
                    ?>
                    
                    <a href="#category-<?php echo esc_attr($category->slug); ?>" class="faq-category-card bg-white rounded-lg shadow-md p-6 text-center transition-transform hover:transform hover:scale-105">
                        <div class="category-icon text-primary text-4xl mb-4">
                            <?php if ($category_icon) : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <?php
                                    switch ($category_icon) {
                                        case 'information-circle':
                                            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
                                            break;
                                        case 'shopping-bag':
                                            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />';
                                            break;
                                        case 'briefcase':
                                            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />';
                                            break;
                                        case 'truck':
                                            echo '<path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />';
                                            break;
                                        case 'refresh':
                                            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />';
                                            break;
                                        case 'credit-card':
                                            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />';
                                            break;
                                        case 'user':
                                            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />';
                                            break;
                                        case 'cog':
                                            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
                                            break;
                                        case 'wrench':
                                            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V5.6a.6.6 0 01.6-.6H17M9 11.4a.6.6 0 01-.6.6H3m11.1-4.8l2.1-2.1m-4.8 9.6l-2.1 2.1m10.8-2.1a5.4 5.4 0 11-10.8 0 5.4 5.4 0 0110.8 0z" />';
                                            break;
                                        case 'shield-check':
                                            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />';
                                            break;
                                        case 'home':
                                            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />';
                                            break;
                                        default:
                                            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
                                    }
                                    ?>
                                </svg>
                            <?php endif; ?>
                        </div>
                        
                        <h3 class="category-name text-xl font-bold mb-2">
                            <?php echo esc_html($category->name); ?>
                        </h3>
                        
                        <div class="category-count text-primary font-medium">
                            <?php
                            printf(
                                /* translators: %s: number of FAQs */
                                _n('%s Question', '%s Questions', $category_count, 'aqualuxe'),
                                number_format_i18n($category_count)
                            );
                            ?>
                        </div>
                    </a>
                    
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="no-categories text-center py-8">
                <p><?php esc_html_e('No FAQ categories found.', 'aqualuxe'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>