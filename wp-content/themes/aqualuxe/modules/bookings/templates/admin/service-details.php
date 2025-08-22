<?php
/**
 * Admin Service Details Meta Box Template
 *
 * @package AquaLuxe
 * @subpackage Modules/Bookings
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Get service data
$service_data = isset($service_data) ? $service_data : [];
?>

<div class="aqualuxe-service-details">
    <div class="aqualuxe-service-details__field">
        <label for="aqualuxe-service-price"><?php esc_html_e('Price', 'aqualuxe'); ?></label>
        <input type="number" id="aqualuxe-service-price" name="aqualuxe_service_price" value="<?php echo esc_attr(isset($service_data['price']) ? $service_data['price'] : '0'); ?>" step="0.01" min="0">
        <p class="description"><?php esc_html_e('The price of the service.', 'aqualuxe'); ?></p>
    </div>
    
    <div class="aqualuxe-service-details__field">
        <label for="aqualuxe-service-duration"><?php esc_html_e('Duration', 'aqualuxe'); ?></label>
        <input type="number" id="aqualuxe-service-duration" name="aqualuxe_service_duration" value="<?php echo esc_attr(isset($service_data['duration']) ? $service_data['duration'] : '60'); ?>" step="5" min="5">
        <p class="description"><?php esc_html_e('The duration of the service in minutes.', 'aqualuxe'); ?></p>
    </div>
    
    <div class="aqualuxe-service-details__field">
        <label for="aqualuxe-service-capacity"><?php esc_html_e('Capacity', 'aqualuxe'); ?></label>
        <input type="number" id="aqualuxe-service-capacity" name="aqualuxe_service_capacity" value="<?php echo esc_attr(isset($service_data['capacity']) ? $service_data['capacity'] : '1'); ?>" min="1">
        <p class="description"><?php esc_html_e('The maximum number of bookings that can be made for this service at the same time.', 'aqualuxe'); ?></p>
    </div>
    
    <div class="aqualuxe-service-details__field">
        <label for="aqualuxe-service-buffer-time"><?php esc_html_e('Buffer Time', 'aqualuxe'); ?></label>
        <input type="number" id="aqualuxe-service-buffer-time" name="aqualuxe_service_buffer_time" value="<?php echo esc_attr(isset($service_data['buffer_time']) ? $service_data['buffer_time'] : '0'); ?>" step="5" min="0">
        <p class="description"><?php esc_html_e('The buffer time between bookings in minutes.', 'aqualuxe'); ?></p>
    </div>
    
    <div class="aqualuxe-service-details__field">
        <label for="aqualuxe-service-wc-product"><?php esc_html_e('WooCommerce Product', 'aqualuxe'); ?></label>
        <select id="aqualuxe-service-wc-product" name="aqualuxe_service_wc_product">
            <option value=""><?php esc_html_e('None', 'aqualuxe'); ?></option>
            <?php
            // Get WooCommerce products
            $products = wc_get_products([
                'limit' => -1,
                'orderby' => 'title',
                'order' => 'ASC',
                'return' => 'ids',
            ]);
            
            foreach ($products as $product_id) {
                $product = wc_get_product($product_id);
                echo '<option value="' . esc_attr($product_id) . '" ' . selected(isset($service_data['wc_product_id']) && $service_data['wc_product_id'] == $product_id, true, false) . '>' . esc_html($product->get_name()) . '</option>';
            }
            ?>
        </select>
        <p class="description"><?php esc_html_e('Link this service to a WooCommerce product for payment processing.', 'aqualuxe'); ?></p>
    </div>
    
    <div class="aqualuxe-service-details__field">
        <label for="aqualuxe-service-featured"><?php esc_html_e('Featured Service', 'aqualuxe'); ?></label>
        <input type="checkbox" id="aqualuxe-service-featured" name="aqualuxe_service_featured" value="1" <?php checked(isset($service_data['featured']) && $service_data['featured']); ?>>
        <p class="description"><?php esc_html_e('Mark this service as featured.', 'aqualuxe'); ?></p>
    </div>
</div>