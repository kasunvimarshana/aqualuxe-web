<?php
/**
 * WooCommerce Specific Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WooCommerce Customizations Class
 */
class AquaLuxe_WooCommerce {
    
    public function __construct() {
        // Remove default WooCommerce styles
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        
        // Customize product loop
        add_filter('loop_shop_columns', array($this, 'loop_columns'));
        add_filter('woocommerce_output_related_products_args', array($this, 'related_products_args'));
        
        // Add custom product badges
        add_action('woocommerce_before_shop_loop_item_title', array($this, 'add_product_badges'), 5);
        
        // Customize add to cart button
        add_filter('woocommerce_product_add_to_cart_text', array($this, 'custom_add_to_cart_text'));
        
        // Add quick view button
        add_action('woocommerce_after_shop_loop_item', array($this, 'add_quick_view_button'), 15);
        
        // Customize checkout fields
        add_filter('woocommerce_checkout_fields', array($this, 'customize_checkout_fields'));
        
        // Add custom product tabs
        add_filter('woocommerce_product_tabs', array($this, 'custom_product_tabs'));
        
        // Customize cart page
        add_action('woocommerce_cart_collaterals', array($this, 'add_cart_recommendations'), 25);
    }
    
    /**
     * Set number of products per row
     */
    public function loop_columns() {
        return get_theme_mod('aqualuxe_product_columns', 3);
    }
    
    /**
     * Customize related products
     */
    public function related_products_args($args) {
        $args['posts_per_page'] = 4;
        $args['columns'] = 4;
        return $args;
    }
    
    /**
     * Add product badges
     */
    public function add_product_badges() {
        global $product;
        
        echo '<div class="product-badges">';
        
        if ($product->is_on_sale()) {
            $percentage = '';
            if ($product->get_regular_price() && $product->get_sale_price()) {
                $percentage = round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100);
                $percentage = '-' . $percentage . '%';
            }
            echo '<span class="badge sale-badge">' . __('Sale', 'aqualuxe') . ' ' . $percentage . '</span>';
        }
        
        if ($product->is_featured()) {
            echo '<span class="badge featured-badge">' . __('Featured', 'aqualuxe') . '</span>';
        }
        
        if (!$product->is_in_stock()) {
            echo '<span class="badge out-of-stock-badge">' . __('Out of Stock', 'aqualuxe') . '</span>';
        }
        
        echo '</div>';
    }
    
    /**
     * Custom add to cart button text
     */
    public function custom_add_to_cart_text($text) {
        global $product;
        
        switch ($product->get_type()) {
            case 'external':
                return $product->get_button_text() ? $product->get_button_text() : __('Buy Now', 'aqualuxe');
            case 'grouped':
                return __('View Products', 'aqualuxe');
            case 'simple':
                return $product->is_purchasable() && $product->is_in_stock() ? __('Add to Cart', 'aqualuxe') : __('Read More', 'aqualuxe');
            case 'variable':
                return __('Select Options', 'aqualuxe');
            default:
                return $text;
        }
    }
    
    /**
     * Add quick view button
     */
    public function add_quick_view_button() {
        global $product;
        
        echo '<button class="quick-view-btn" data-product-id="' . $product->get_id() . '">';
        echo __('Quick View', 'aqualuxe');
        echo '</button>';
    }
    
    /**
     * Customize checkout fields
     */
    public function customize_checkout_fields($fields) {
        // Reorder fields
        $fields['billing']['billing_first_name']['priority'] = 10;
        $fields['billing']['billing_last_name']['priority'] = 20;
        $fields['billing']['billing_email']['priority'] = 30;
        $fields['billing']['billing_phone']['priority'] = 40;
        
        // Add custom field
        $fields['billing']['billing_fish_experience'] = array(
            'label' => __('Fish Keeping Experience', 'aqualuxe'),
            'placeholder' => __('Beginner, Intermediate, Advanced', 'aqualuxe'),
            'required' => false,
            'class' => array('form-row-wide'),
            'priority' => 50,
        );
        
        return $fields;
    }
    
    /**
     * Add custom product tabs
     */
    public function custom_product_tabs($tabs) {
        // Add care instructions tab
        $tabs['care_instructions'] = array(
            'title' => __('Care Instructions', 'aqualuxe'),
            'priority' => 25,
            'callback' => array($this, 'care_instructions_tab_content'),
        );
        
        // Add compatibility tab
        $tabs['compatibility'] = array(
            'title' => __('Tank Compatibility', 'aqualuxe'),
            'priority' => 30,
            'callback' => array($this, 'compatibility_tab_content'),
        );
        
        return $tabs;
    }
    
    /**
     * Care instructions tab content
     */
    public function care_instructions_tab_content() {
        global $product;
        
        $care_instructions = get_post_meta($product->get_id(), '_care_instructions', true);
        
        if ($care_instructions) {
            echo '<div class="care-instructions">';
            echo wp_kses_post($care_instructions);
            echo '</div>';
        } else {
            echo '<p>' . __('Care instructions will be provided with your purchase.', 'aqualuxe') . '</p>';
        }
    }
    
    /**
     * Compatibility tab content
     */
    public function compatibility_tab_content() {
        global $product;
        
        $compatibility = get_post_meta($product->get_id(), '_tank_compatibility', true);
        
        if ($compatibility) {
            echo '<div class="tank-compatibility">';
            echo wp_kses_post($compatibility);
            echo '</div>';
        } else {
            echo '<p>' . __('Please contact us for compatibility information.', 'aqualuxe') . '</p>';
        }
    }
    
    /**
     * Add cart recommendations
     */
    public function add_cart_recommendations() {
        echo '<div class="cart-recommendations">';
        echo '<h3>' . __('You might also like', 'aqualuxe') . '</h3>';
        
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 4,
            'orderby' => 'rand',
            'meta_query' => array(
                array(
                    'key' => '_featured',
                    'value' => 'yes',
                ),
            ),
        );
        
        $products = new WP_Query($args);
        
        if ($products->have_posts()) {
            echo '<ul class="recommended-products">';
            while ($products->have_posts()) {
                $products->the_post();
                wc_get_template_part('content', 'product');
            }
            echo '</ul>';
        }
        
        wp_reset_postdata();
        echo '</div>';
    }
}

new AquaLuxe_WooCommerce();

/**
 * Add custom product meta fields
 */
function aqualuxe_add_product_meta_fields() {
    add_meta_box(
        'aqualuxe_product_meta',
        __('Fish Details', 'aqualuxe'),
        'aqualuxe_product_meta_callback',
        'product',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_add_product_meta_fields');

/**
 * Product meta fields callback
 */
function aqualuxe_product_meta_callback($post) {
    wp_nonce_field('aqualuxe_product_meta_nonce', 'aqualuxe_product_meta_nonce');
    
    $care_instructions = get_post_meta($post->ID, '_care_instructions', true);
    $tank_compatibility = get_post_meta($post->ID, '_tank_compatibility', true);
    $water_parameters = get_post_meta($post->ID, '_water_parameters', true);
    $adult_size = get_post_meta($post->ID, '_adult_size', true);
    $temperament = get_post_meta($post->ID, '_temperament', true);
    
    echo '<table class="form-table">';
    
    echo '<tr>';
    echo '<th><label for="care_instructions">' . __('Care Instructions', 'aqualuxe') . '</label></th>';
    echo '<td>';
    wp_editor($care_instructions, 'care_instructions', array(
        'textarea_name' => 'care_instructions',
        'media_buttons' => false,
        'textarea_rows' => 5,
    ));
    echo '</td>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<th><label for="tank_compatibility">' . __('Tank Compatibility', 'aqualuxe') . '</label></th>';
    echo '<td>';
    wp_editor($tank_compatibility, 'tank_compatibility', array(
        'textarea_name' => 'tank_compatibility',
        'media_buttons' => false,
        'textarea_rows' => 5,
    ));
    echo '</td>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<th><label for="water_parameters">' . __('Water Parameters', 'aqualuxe') . '</label></th>';
    echo '<td><input type="text" id="water_parameters" name="water_parameters" value="' . esc_attr($water_parameters) . '" class="regular-text" /></td>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<th><label for="adult_size">' . __('Adult Size', 'aqualuxe') . '</label></th>';
    echo '<td><input type="text" id="adult_size" name="adult_size" value="' . esc_attr($adult_size) . '" class="regular-text" /></td>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<th><label for="temperament">' . __('Temperament', 'aqualuxe') . '</label></th>';
    echo '<td>';
    echo '<select id="temperament" name="temperament">';
    echo '<option value="">' . __('Select Temperament', 'aqualuxe') . '</option>';
    echo '<option value="peaceful"' . selected($temperament, 'peaceful', false) . '>' . __('Peaceful', 'aqualuxe') . '</option>';
    echo '<option value="semi-aggressive"' . selected($temperament, 'semi-aggressive', false) . '>' . __('Semi-Aggressive', 'aqualuxe') . '</option>';
    echo '<option value="aggressive"' . selected($temperament, 'aggressive', false) . '>' . __('Aggressive', 'aqualuxe') . '</option>';
    echo '</select>';
    echo '</td>';
    echo '</tr>';
    
    echo '</table>';
}

/**
 * Save product meta fields
 */
function aqualuxe_save_product_meta($post_id) {
    if (!isset($_POST['aqualuxe_product_meta_nonce']) || !wp_verify_nonce($_POST['aqualuxe_product_meta_nonce'], 'aqualuxe_product_meta_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $fields = array('care_instructions', 'tank_compatibility', 'water_parameters', 'adult_size', 'temperament');
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'aqualuxe_save_product_meta');
