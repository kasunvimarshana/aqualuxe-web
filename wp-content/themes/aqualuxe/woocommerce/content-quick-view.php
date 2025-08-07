<?php
/**
 * The template for displaying product quick view
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

global $product;

// Get product from ajax request
if (wp_doing_ajax()) {
    $product_id = intval($_POST['product_id']);
    $product = wc_get_product($product_id);
}

if (!$product) {
    return;
}

?>

<div class="aqualuxe-quick-view">
  <div class="quick-view-content">
    <div class="quick-view-images">
      <?php
      // Display product image
      echo $product->get_image('large');
      
      // Display product gallery
      $attachment_ids = $product->get_gallery_image_ids();
      if ($attachment_ids) {
        echo '<div class="quick-view-gallery">';
        foreach ($attachment_ids as $attachment_id) {
          echo wp_get_attachment_image($attachment_id, 'thumbnail');
        }
        echo '</div>';
      }
      ?>
    </div>
    
    <div class="quick-view-info">
      <h2 class="product-title"><?php echo esc_html($product->get_name()); ?></h2>
      
      <div class="product-price">
        <?php echo $product->get_price_html(); ?>
      </div>
      
      <div class="product-description">
        <?php echo wp_kses_post($product->get_short_description()); ?>
      </div>
      
      <?php
      // Display variation options for variable products
      if ($product->is_type('variable')) {
        echo '<div class="product-variations">';
        woocommerce_variable_add_to_cart();
        echo '</div>';
      }
      
      // Display product meta
      if ($product->get_sku() || $product->get_categories()) {
        echo '<div class="product-meta">';
        
        if ($product->get_sku()) {
          echo '<div class="product-sku">';
          echo '<span class="meta-label">' . esc_html__('SKU:', 'aqualuxe') . '</span>';
          echo '<span class="meta-value">' . esc_html($product->get_sku()) . '</span>';
          echo '</div>';
        }
        
        if ($product->get_categories()) {
          echo '<div class="product-categories">';
          echo '<span class="meta-label">' . esc_html__('Categories:', 'aqualuxe') . '</span>';
          echo '<span class="meta-value">' . wp_kses_post(get_the_term_list($product->get_id(), 'product_cat', '', ', ', '')) . '</span>';
          echo '</div>';
        }
        
        echo '</div>';
      }
      ?>
      
      <div class="quick-view-actions">
        <?php
        // Display add to cart button (only for non-variable products)
        if (!$product->is_type('variable')) {
          woocommerce_template_single_add_to_cart();
        }
        ?>
        
        <a href="<?php echo esc_url($product->get_permalink()); ?>" class="button view-details">
          <?php esc_html_e('View Full Details', 'aqualuxe'); ?>
        </a>
      </div>
    </div>
  </div>
</div>