<?php

/**
 * AquaLuxe Quick View
 *
 * @package AquaLuxe
 */

if (! defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Quick_View
{

    public function __construct()
    {
        add_action('wp_ajax_aqualuxe_quick_view', [$this, 'render_quick_view']);
        add_action('wp_ajax_nopriv_aqualuxe_quick_view', [$this, 'render_quick_view']);
    }

    public function render_quick_view()
    {
        if (! isset($_POST['product_id'])) {
            wp_send_json_error();
        }

        $product_id = intval($_POST['product_id']);
        $product    = wc_get_product($product_id);

        if (! $product) {
            wp_send_json_error();
        }

        ob_start();
?>
        <div class="quick-view-content">
            <button class="quick-view-close">&times;</button>
            <div class="qv-product-title"><?php echo esc_html($product->get_name()); ?></div>
            <div class="qv-product-image"><?php echo $product->get_image(); ?></div>
            <div class="qv-product-price"><?php echo $product->get_price_html(); ?></div>
            <div class="qv-product-add"><?php woocommerce_template_single_add_to_cart(); ?></div>
        </div>
<?php
        wp_send_json_success(ob_get_clean());
    }
}
