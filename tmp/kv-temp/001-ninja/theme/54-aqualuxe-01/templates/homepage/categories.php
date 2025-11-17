<?php
/**
 * Homepage Categories Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Check if WooCommerce is active
if (!aqualuxe_is_woocommerce_active()) {
    return;
}

// Get customizer settings
$section_title = get_theme_mod('aqualuxe_homepage_categories_title', __('Shop by Category', 'aqualuxe'));
$section_subtitle = get_theme_mod('aqualuxe_homepage_categories_subtitle', __('Explore our wide range of aquatic products', 'aqualuxe'));
$number_of_categories = get_theme_mod('aqualuxe_homepage_categories_number', 6);
$columns = get_theme_mod('aqualuxe_homepage_categories_columns', 3);
$show_count = get_theme_mod('aqualuxe_homepage_categories_show_count', true);
$show_section = get_theme_mod('aqualuxe_homepage_categories_show', true);
$category_ids = get_theme_mod('aqualuxe_homepage_categories_ids', '');

// Exit if section is disabled
if (!$show_section) {
    return;
}

// Set up query args
$args = array(
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'number' => $number_of_categories,
    'orderby' => 'menu_order',
);

// If specific categories are selected
if (!empty($category_ids)) {
    $category_ids = explode(',', $category_ids);
    $args['include'] = array_map('trim', $category_ids);
    $args['orderby'] = 'include';
}

// Get categories
$categories = get_terms($args);

// Exit if no categories
if (empty($categories) || is_wp_error($categories)) {
    return;
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
        $column_class = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3';
        break;
    case 4:
        $column_class = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4';
        break;
    case 6:
        $column_class = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6';
        break;
}
?>

<section class="aqualuxe-categories py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <?php if ($section_title) : ?>
                <h2 class="text-3xl font-bold mb-4"><?php echo esc_html($section_title); ?></h2>
            <?php endif; ?>
            
            <?php if ($section_subtitle) : ?>
                <p class="text-lg text-gray-600 dark:text-gray-400"><?php echo esc_html($section_subtitle); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="grid <?php echo esc_attr($column_class); ?> gap-6">
            <?php foreach ($categories as $category) : ?>
                <?php
                $category_link = get_term_link($category, 'product_cat');
                $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                $image = $thumbnail_id ? wp_get_attachment_image_src($thumbnail_id, 'woocommerce_thumbnail') : '';
                $image_url = $image ? $image[0] : wc_placeholder_img_src();
                ?>
                <a href="<?php echo esc_url($category_link); ?>" class="aqualuxe-category-card group">
                    <div class="relative rounded-lg overflow-hidden shadow-md transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="aspect-w-1 aspect-h-1">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($category->name); ?>" class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-6">
                            <div class="text-white">
                                <h3 class="text-xl font-bold mb-1"><?php echo esc_html($category->name); ?></h3>
                                <?php if ($show_count) : ?>
                                    <p class="text-sm opacity-80"><?php echo sprintf(_n('%s product', '%s products', $category->count, 'aqualuxe'), number_format_i18n($category->count)); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>