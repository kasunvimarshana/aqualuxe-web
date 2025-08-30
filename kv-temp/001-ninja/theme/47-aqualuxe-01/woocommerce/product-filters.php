<?php
/**
 * Product Filters Template
 *
 * This template displays the advanced product filters for the shop page.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// Get current category
$current_category = get_queried_object();
$category_id = is_tax('product_cat') ? $current_category->term_id : 0;

// Get all product categories
$product_categories = get_terms(array(
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'parent' => 0,
));

// Get all product attributes
$attribute_taxonomies = wc_get_attribute_taxonomies();

// Get price range
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 0;

// Get product price range
$price_range = aqualuxe_get_product_price_range();
$min_price_range = $price_range['min'];
$max_price_range = $price_range['max'];

if ($min_price === 0) {
    $min_price = $min_price_range;
}

if ($max_price === 0) {
    $max_price = $max_price_range;
}
?>

<div class="product-filters">
    <div class="filter-header flex justify-between items-center mb-4 lg:hidden">
        <h3 class="text-lg font-bold"><?php esc_html_e('Filters', 'aqualuxe'); ?></h3>
        <button class="filter-close">
            <?php aqualuxe_svg_icon('close', array('class' => 'w-5 h-5')); ?>
        </button>
    </div>

    <form method="get" action="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">
        <?php if ($category_id) : ?>
            <input type="hidden" name="product_cat" value="<?php echo esc_attr($current_category->slug); ?>">
        <?php endif; ?>

        <?php
        // Keep query string parameters
        foreach ($_GET as $key => $value) {
            if (!in_array($key, array('min_price', 'max_price', 'filter_category', 'filter_attribute', 'filter_rating', 'filter_stock', 'filter_featured', 'filter_on_sale', 'orderby', 'paged'))) {
                if (is_array($value)) {
                    foreach ($value as $val) {
                        echo '<input type="hidden" name="' . esc_attr($key) . '[]" value="' . esc_attr($val) . '">';
                    }
                } else {
                    echo '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '">';
                }
            }
        }
        ?>

        <!-- Categories Filter -->
        <?php if (!empty($product_categories) && !$category_id) : ?>
            <div class="filter-section mb-6">
                <h4 class="filter-title text-base font-medium mb-3"><?php esc_html_e('Categories', 'aqualuxe'); ?></h4>
                <div class="filter-content">
                    <ul class="filter-list space-y-2">
                        <?php foreach ($product_categories as $category) : ?>
                            <li class="filter-item">
                                <label class="flex items-center">
                                    <input type="checkbox" name="filter_category[]" value="<?php echo esc_attr($category->slug); ?>" <?php checked(isset($_GET['filter_category']) && in_array($category->slug, (array)$_GET['filter_category'])); ?> class="mr-2">
                                    <span><?php echo esc_html($category->name); ?></span>
                                    <span class="ml-auto text-xs text-gray-500">(<?php echo esc_html($category->count); ?>)</span>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <!-- Price Range Filter -->
        <div class="filter-section mb-6">
            <h4 class="filter-title text-base font-medium mb-3"><?php esc_html_e('Price Range', 'aqualuxe'); ?></h4>
            <div class="filter-content">
                <div class="price-slider-container">
                    <div class="price-inputs flex justify-between mb-2">
                        <div class="min-price">
                            <span class="currency-symbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
                            <input type="number" name="min_price" id="min_price" value="<?php echo esc_attr($min_price); ?>" min="<?php echo esc_attr($min_price_range); ?>" max="<?php echo esc_attr($max_price_range); ?>" class="w-16 px-1 py-1 border border-gray-300 rounded text-sm">
                        </div>
                        <div class="max-price">
                            <span class="currency-symbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
                            <input type="number" name="max_price" id="max_price" value="<?php echo esc_attr($max_price); ?>" min="<?php echo esc_attr($min_price_range); ?>" max="<?php echo esc_attr($max_price_range); ?>" class="w-16 px-1 py-1 border border-gray-300 rounded text-sm">
                        </div>
                    </div>
                    <div class="price-slider" data-min="<?php echo esc_attr($min_price_range); ?>" data-max="<?php echo esc_attr($max_price_range); ?>" data-current-min="<?php echo esc_attr($min_price); ?>" data-current-max="<?php echo esc_attr($max_price); ?>"></div>
                </div>
            </div>
        </div>

        <!-- Attributes Filter -->
        <?php if (!empty($attribute_taxonomies)) : ?>
            <?php foreach ($attribute_taxonomies as $attribute) : ?>
                <?php
                $attribute_name = 'pa_' . $attribute->attribute_name;
                $attribute_terms = get_terms(array(
                    'taxonomy' => $attribute_name,
                    'hide_empty' => true,
                ));

                if (empty($attribute_terms)) {
                    continue;
                }
                ?>
                <div class="filter-section mb-6">
                    <h4 class="filter-title text-base font-medium mb-3"><?php echo esc_html($attribute->attribute_label); ?></h4>
                    <div class="filter-content">
                        <ul class="filter-list space-y-2">
                            <?php foreach ($attribute_terms as $term) : ?>
                                <li class="filter-item">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="filter_attribute[<?php echo esc_attr($attribute_name); ?>][]" value="<?php echo esc_attr($term->slug); ?>" <?php checked(isset($_GET['filter_attribute'][$attribute_name]) && in_array($term->slug, (array)$_GET['filter_attribute'][$attribute_name])); ?> class="mr-2">
                                        <span><?php echo esc_html($term->name); ?></span>
                                        <span class="ml-auto text-xs text-gray-500">(<?php echo esc_html($term->count); ?>)</span>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Rating Filter -->
        <div class="filter-section mb-6">
            <h4 class="filter-title text-base font-medium mb-3"><?php esc_html_e('Rating', 'aqualuxe'); ?></h4>
            <div class="filter-content">
                <ul class="filter-list space-y-2">
                    <?php for ($i = 5; $i >= 1; $i--) : ?>
                        <li class="filter-item">
                            <label class="flex items-center">
                                <input type="radio" name="filter_rating" value="<?php echo esc_attr($i); ?>" <?php checked(isset($_GET['filter_rating']) && $_GET['filter_rating'] == $i); ?> class="mr-2">
                                <div class="star-rating" role="img" aria-label="<?php echo sprintf(esc_html__('Rated %s out of 5', 'aqualuxe'), $i); ?>">
                                    <span style="width: <?php echo esc_attr($i / 5 * 100); ?>%;">
                                        <?php echo sprintf(esc_html__('Rated %s out of 5', 'aqualuxe'), $i); ?>
                                    </span>
                                </div>
                                <span class="ml-1"><?php echo sprintf(esc_html__('& Up', 'aqualuxe')); ?></span>
                            </label>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>
        </div>

        <!-- Availability Filter -->
        <div class="filter-section mb-6">
            <h4 class="filter-title text-base font-medium mb-3"><?php esc_html_e('Availability', 'aqualuxe'); ?></h4>
            <div class="filter-content">
                <ul class="filter-list space-y-2">
                    <li class="filter-item">
                        <label class="flex items-center">
                            <input type="checkbox" name="filter_stock" value="in_stock" <?php checked(isset($_GET['filter_stock']) && $_GET['filter_stock'] == 'in_stock'); ?> class="mr-2">
                            <span><?php esc_html_e('In Stock', 'aqualuxe'); ?></span>
                        </label>
                    </li>
                    <li class="filter-item">
                        <label class="flex items-center">
                            <input type="checkbox" name="filter_stock" value="on_backorder" <?php checked(isset($_GET['filter_stock']) && $_GET['filter_stock'] == 'on_backorder'); ?> class="mr-2">
                            <span><?php esc_html_e('On Backorder', 'aqualuxe'); ?></span>
                        </label>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Special Products Filter -->
        <div class="filter-section mb-6">
            <h4 class="filter-title text-base font-medium mb-3"><?php esc_html_e('Product Status', 'aqualuxe'); ?></h4>
            <div class="filter-content">
                <ul class="filter-list space-y-2">
                    <li class="filter-item">
                        <label class="flex items-center">
                            <input type="checkbox" name="filter_featured" value="1" <?php checked(isset($_GET['filter_featured']) && $_GET['filter_featured'] == '1'); ?> class="mr-2">
                            <span><?php esc_html_e('Featured Products', 'aqualuxe'); ?></span>
                        </label>
                    </li>
                    <li class="filter-item">
                        <label class="flex items-center">
                            <input type="checkbox" name="filter_on_sale" value="1" <?php checked(isset($_GET['filter_on_sale']) && $_GET['filter_on_sale'] == '1'); ?> class="mr-2">
                            <span><?php esc_html_e('On Sale', 'aqualuxe'); ?></span>
                        </label>
                    </li>
                </ul>
            </div>
        </div>

        <div class="filter-actions flex justify-between">
            <button type="submit" class="filter-apply-btn bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md transition-colors duration-200"><?php esc_html_e('Apply Filters', 'aqualuxe'); ?></button>
            <a href="<?php echo esc_url(remove_query_arg(array('min_price', 'max_price', 'filter_category', 'filter_attribute', 'filter_rating', 'filter_stock', 'filter_featured', 'filter_on_sale'))); ?>" class="filter-reset-btn text-gray-600 hover:text-primary-600 px-4 py-2"><?php esc_html_e('Reset', 'aqualuxe'); ?></a>
        </div>
    </form>
</div>