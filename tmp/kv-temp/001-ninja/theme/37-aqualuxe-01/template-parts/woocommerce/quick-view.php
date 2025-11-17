<?php
/**
 * Template part for displaying the quick view modal
 *
 * @package AquaLuxe
 */

defined('ABSPATH') || exit;

if (!class_exists('WooCommerce')) {
    return;
}

// Get product ID from query var
$product_id = get_query_var('quick_view_product_id', 0);

if (!$product_id) {
    return;
}

// Get product
$product = wc_get_product($product_id);

if (!$product) {
    return;
}

// Set the global product variable
global $post, $product;
$post = get_post($product_id);
setup_postdata($post);
?>

<div id="quick-view-modal" class="quick-view-modal fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-75">
    <div class="quick-view-content relative bg-white dark:bg-secondary-900 rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <button type="button" class="quick-view-close absolute top-4 right-4 text-secondary-500 hover:text-secondary-700 dark:text-secondary-400 dark:hover:text-white z-10">
            <span class="sr-only"><?php esc_html_e('Close', 'aqualuxe'); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="quick-view-inner p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="product-images">
                    <?php
                    // Show sale flash
                    woocommerce_show_product_sale_flash();
                    
                    // Show product image
                    if (has_post_thumbnail()) {
                        echo '<div class="quick-view-image mb-4">';
                        echo get_the_post_thumbnail($product_id, 'woocommerce_single', array('class' => 'w-full h-auto rounded-lg'));
                        echo '</div>';
                    }
                    
                    // Show product gallery
                    $attachment_ids = $product->get_gallery_image_ids();
                    if ($attachment_ids) {
                        echo '<div class="quick-view-gallery grid grid-cols-4 gap-2">';
                        foreach ($attachment_ids as $attachment_id) {
                            echo '<div class="quick-view-gallery-item">';
                            echo wp_get_attachment_image($attachment_id, 'thumbnail', false, array('class' => 'w-full h-auto rounded cursor-pointer'));
                            echo '</div>';
                        }
                        echo '</div>';
                    }
                    ?>
                </div>

                <div class="product-details">
                    <h2 class="product-title text-2xl font-bold mb-2"><?php the_title(); ?></h2>
                    
                    <?php
                    // Show rating
                    if (function_exists('woocommerce_template_loop_rating')) {
                        woocommerce_template_loop_rating();
                    }
                    
                    // Show price
                    if (function_exists('woocommerce_template_single_price')) {
                        woocommerce_template_single_price();
                    }
                    
                    // Show short description
                    if ($product->get_short_description()) {
                        echo '<div class="product-short-description my-4">';
                        echo wp_kses_post($product->get_short_description());
                        echo '</div>';
                    }
                    
                    // Show add to cart form
                    if (function_exists('woocommerce_template_single_add_to_cart')) {
                        woocommerce_template_single_add_to_cart();
                    }
                    
                    // Show meta
                    if (function_exists('woocommerce_template_single_meta')) {
                        woocommerce_template_single_meta();
                    }
                    ?>
                    
                    <div class="quick-view-actions mt-6 pt-6 border-t border-secondary-200 dark:border-secondary-700">
                        <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-500 hover:text-primary-700 font-medium">
                            <?php esc_html_e('View Full Details', 'aqualuxe'); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Quick view gallery functionality
    document.addEventListener('DOMContentLoaded', function() {
        const galleryItems = document.querySelectorAll('.quick-view-gallery-item img');
        const mainImage = document.querySelector('.quick-view-image img');
        
        if (galleryItems.length && mainImage) {
            galleryItems.forEach(item => {
                item.addEventListener('click', function() {
                    mainImage.src = this.src.replace('-thumbnail', '');
                    mainImage.srcset = '';
                });
            });
        }
        
        // Close modal on button click
        const closeButton = document.querySelector('.quick-view-close');
        if (closeButton) {
            closeButton.addEventListener('click', function() {
                const modal = document.getElementById('quick-view-modal');
                if (modal) {
                    modal.remove();
                }
            });
        }
        
        // Close modal on outside click
        const modal = document.getElementById('quick-view-modal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.remove();
                }
            });
        }
        
        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('quick-view-modal');
                if (modal) {
                    modal.remove();
                }
            }
        });
    });
</script>

<?php
wp_reset_postdata();