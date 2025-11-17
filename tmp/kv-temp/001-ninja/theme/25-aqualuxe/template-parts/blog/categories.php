<?php
/**
 * Template part for displaying the blog page categories section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get page ID
$page_id = get_the_ID();

// Get section settings from page meta or theme options
$section_title = get_post_meta($page_id, '_aqualuxe_blog_categories_title', true);
if (!$section_title) {
    $section_title = get_theme_mod('aqualuxe_blog_categories_title', __('Browse by Category', 'aqualuxe'));
}

$section_subtitle = get_post_meta($page_id, '_aqualuxe_blog_categories_subtitle', true);
if (!$section_subtitle) {
    $section_subtitle = get_theme_mod('aqualuxe_blog_categories_subtitle', __('Topics', 'aqualuxe'));
}

$section_description = get_post_meta($page_id, '_aqualuxe_blog_categories_description', true);
if (!$section_description) {
    $section_description = get_theme_mod('aqualuxe_blog_categories_description', __('Explore our articles by topic to find the information you need.', 'aqualuxe'));
}

$excluded_categories = get_post_meta($page_id, '_aqualuxe_blog_categories_excluded', true);
if (!$excluded_categories) {
    $excluded_categories = get_theme_mod('aqualuxe_blog_categories_excluded', '');
}

$section_background = get_post_meta($page_id, '_aqualuxe_blog_categories_background', true);
if ($section_background === '') {
    $section_background = get_theme_mod('aqualuxe_blog_categories_background', 'gray');
}

// Set background class based on setting
$bg_class = $section_background === 'gray' ? 'bg-gray-50' : 'bg-white';

// Get categories
$categories_args = array(
    'orderby'    => 'count',
    'order'      => 'DESC',
    'hide_empty' => true,
);

if ($excluded_categories) {
    $categories_args['exclude'] = explode(',', $excluded_categories);
}

$categories = get_categories($categories_args);
?>

<section class="blog-categories-section py-16 <?php echo esc_attr($bg_class); ?>">
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
        
        <?php if (!empty($categories)) : ?>
            <div class="categories-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                foreach ($categories as $category) :
                    // Get category image if available (using ACF or custom field)
                    $category_image = '';
                    if (function_exists('get_field')) {
                        $category_image = get_field('category_image', 'category_' . $category->term_id);
                    }
                    
                    // Fallback to default image if no category image
                    if (!$category_image) {
                        $category_image = get_template_directory_uri() . '/assets/images/category-default.jpg';
                    }
                    ?>
                    
                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="category-card bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:transform hover:scale-105">
                        <div class="category-image relative h-48">
                            <img src="<?php echo esc_url($category_image); ?>" alt="<?php echo esc_attr($category->name); ?>" class="w-full h-full object-cover">
                            <div class="category-overlay absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                                <h3 class="category-name text-white text-2xl font-bold"><?php echo esc_html($category->name); ?></h3>
                            </div>
                        </div>
                        <div class="category-content p-6">
                            <div class="category-count text-primary font-medium mb-2">
                                <?php
                                printf(
                                    /* translators: %s: number of posts */
                                    _n('%s Article', '%s Articles', $category->count, 'aqualuxe'),
                                    number_format_i18n($category->count)
                                );
                                ?>
                            </div>
                            <div class="category-description text-gray-600">
                                <?php
                                if ($category->description) {
                                    echo wp_kses_post(wp_trim_words($category->description, 15));
                                } else {
                                    /* translators: %s: category name */
                                    printf(esc_html__('Browse all articles in the %s category.', 'aqualuxe'), esc_html($category->name));
                                }
                                ?>
                            </div>
                        </div>
                    </a>
                    
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="no-categories text-center py-8">
                <p><?php esc_html_e('No categories found.', 'aqualuxe'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>