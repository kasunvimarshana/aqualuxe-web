<?php
/**
 * The template for displaying product quick view
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get product object
global $product;

if (!$product) {
    return;
}
?>

<div class="quick-view-content">
    <div class="quick-view-images">
        <?php
        // Display product gallery
        $attachment_ids = $product->get_gallery_image_ids();
        
        if ($attachment_ids) {
            echo '<div class="quick-view-gallery">';
            foreach ($attachment_ids as $attachment_id) {
                $image_url = wp_get_attachment_url($attachment_id);
                echo '<div class="quick-view-image"><img src="' . esc_url($image_url) . '" alt="' . esc_attr($product->get_name()) . '"></div>';
            }
            echo '</div>';
        } else {
            // Display featured image
            echo '<div class="quick-view-image">' . $product->get_image('woocommerce_single') . '</div>';
        }
        ?>
    </div>
    
    <div class="quick-view-summary">
        <h2 class="product_title entry-title"><?php echo esc_html($product->get_name()); ?></h2>
        
        <?php
        // Display product rating
        if (wc_review_ratings_enabled()) {
            $rating_count = $product->get_rating_count();
            $average      = $product->get_average_rating();
            
            echo wc_get_rating_html($average, $rating_count);
        }
        ?>
        
        <div class="price"><?php echo $product->get_price_html(); ?></div>
        
        <?php
        // Display product description
        if ($product->get_short_description()) {
            echo '<div class="product-description">' . $product->get_short_description() . '</div>';
        }
        ?>
        
        <div class="quick-view-add-to-cart">
            <?php
            // Display add to cart form
            if ($product->is_type('simple')) {
                woocommerce_simple_add_to_cart();
            } elseif ($product->is_type('variable')) {
                woocommerce_variable_add_to_cart();
            } elseif ($product->is_type('grouped')) {
                woocommerce_grouped_add_to_cart();
            } else {
                woocommerce_external_add_to_cart();
            }
            ?>
        </div>
        
        <div class="quick-view-meta">
            <?php
            // Display product meta
            $categories = wc_get_product_category_list($product->get_id());
            if ($categories) {
                echo '<div class="posted_in">' . _n('Category:', 'Categories:', count($categories), 'aqualuxe') . ' ' . $categories . '</div>';
            }
            
            $tags = wc_get_product_tag_list($product->get_id());
            if ($tags) {
                echo '<div class="tagged_as">' . _n('Tag:', 'Tags:', count($tags), 'aqualuxe') . ' ' . $tags . '</div>';
            }
            ?>
        </div>
        
        <a href="<?php echo esc_url($product->get_permalink()); ?>" class="quick-view-full-details"><?php esc_html_e('View Full Details', 'aqualuxe'); ?></a>
    </div>
</div>