<?php
/**
 * Services archive filters template part
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get service categories
$categories = get_terms([
    'taxonomy' => 'service_category',
    'hide_empty' => true,
]);

// Get service tags
$tags = get_terms([
    'taxonomy' => 'service_tag',
    'hide_empty' => true,
]);

// Get current filters
$current_category = isset($_GET['service_category']) ? sanitize_text_field($_GET['service_category']) : '';
$current_tag = isset($_GET['service_tag']) ? sanitize_text_field($_GET['service_tag']) : '';
$current_price_min = isset($_GET['price_min']) ? sanitize_text_field($_GET['price_min']) : '';
$current_price_max = isset($_GET['price_max']) ? sanitize_text_field($_GET['price_max']) : '';
$current_location = isset($_GET['location']) ? sanitize_text_field($_GET['location']) : '';
$current_duration = isset($_GET['duration']) ? sanitize_text_field($_GET['duration']) : '';
$current_orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';
$current_order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'DESC';

// Check if we have filters to show
$has_filters = (!empty($categories) && !is_wp_error($categories)) || (!empty($tags) && !is_wp_error($tags));

if ($has_filters) :
?>
<div class="aqualuxe-services-archive-filters">
    <form class="aqualuxe-services-archive-filters-form" method="get" action="<?php echo esc_url(get_post_type_archive_link('service')); ?>">
        <div class="aqualuxe-services-archive-filters-row">
            <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
                <div class="aqualuxe-services-archive-filters-item">
                    <label for="service-category"><?php esc_html_e('Category', 'aqualuxe'); ?></label>
                    <select id="service-category" name="service_category">
                        <option value=""><?php esc_html_e('All Categories', 'aqualuxe'); ?></option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo esc_attr($category->slug); ?>" <?php selected($current_category, $category->slug); ?>><?php echo esc_html($category->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <?php if (!empty($tags) && !is_wp_error($tags)) : ?>
                <div class="aqualuxe-services-archive-filters-item">
                    <label for="service-tag"><?php esc_html_e('Tag', 'aqualuxe'); ?></label>
                    <select id="service-tag" name="service_tag">
                        <option value=""><?php esc_html_e('All Tags', 'aqualuxe'); ?></option>
                        <?php foreach ($tags as $tag) : ?>
                            <option value="<?php echo esc_attr($tag->slug); ?>" <?php selected($current_tag, $tag->slug); ?>><?php echo esc_html($tag->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <div class="aqualuxe-services-archive-filters-item">
                <label for="service-price-min"><?php esc_html_e('Min Price', 'aqualuxe'); ?></label>
                <input type="number" id="service-price-min" name="price_min" value="<?php echo esc_attr($current_price_min); ?>" min="0" step="1" placeholder="<?php esc_attr_e('Min', 'aqualuxe'); ?>">
            </div>

            <div class="aqualuxe-services-archive-filters-item">
                <label for="service-price-max"><?php esc_html_e('Max Price', 'aqualuxe'); ?></label>
                <input type="number" id="service-price-max" name="price_max" value="<?php echo esc_attr($current_price_max); ?>" min="0" step="1" placeholder="<?php esc_attr_e('Max', 'aqualuxe'); ?>">
            </div>

            <div class="aqualuxe-services-archive-filters-item">
                <label for="service-location"><?php esc_html_e('Location', 'aqualuxe'); ?></label>
                <input type="text" id="service-location" name="location" value="<?php echo esc_attr($current_location); ?>" placeholder="<?php esc_attr_e('Any location', 'aqualuxe'); ?>">
            </div>

            <div class="aqualuxe-services-archive-filters-item">
                <label for="service-duration"><?php esc_html_e('Duration', 'aqualuxe'); ?></label>
                <input type="text" id="service-duration" name="duration" value="<?php echo esc_attr($current_duration); ?>" placeholder="<?php esc_attr_e('Any duration', 'aqualuxe'); ?>">
            </div>

            <div class="aqualuxe-services-archive-filters-item">
                <label for="service-orderby"><?php esc_html_e('Sort By', 'aqualuxe'); ?></label>
                <select id="service-orderby" name="orderby">
                    <option value="date" <?php selected($current_orderby, 'date'); ?>><?php esc_html_e('Date', 'aqualuxe'); ?></option>
                    <option value="title" <?php selected($current_orderby, 'title'); ?>><?php esc_html_e('Title', 'aqualuxe'); ?></option>
                    <option value="price" <?php selected($current_orderby, 'price'); ?>><?php esc_html_e('Price', 'aqualuxe'); ?></option>
                    <option value="menu_order" <?php selected($current_orderby, 'menu_order'); ?>><?php esc_html_e('Menu Order', 'aqualuxe'); ?></option>
                </select>
            </div>

            <div class="aqualuxe-services-archive-filters-item">
                <label for="service-order"><?php esc_html_e('Order', 'aqualuxe'); ?></label>
                <select id="service-order" name="order">
                    <option value="DESC" <?php selected($current_order, 'DESC'); ?>><?php esc_html_e('Descending', 'aqualuxe'); ?></option>
                    <option value="ASC" <?php selected($current_order, 'ASC'); ?>><?php esc_html_e('Ascending', 'aqualuxe'); ?></option>
                </select>
            </div>
        </div>

        <div class="aqualuxe-services-archive-filters-actions">
            <button type="submit" class="aqualuxe-services-archive-filters-submit"><?php esc_html_e('Filter', 'aqualuxe'); ?></button>
            <button type="button" class="aqualuxe-services-archive-filters-reset"><?php esc_html_e('Reset', 'aqualuxe'); ?></button>
        </div>
    </form>
</div>
<?php endif; ?>