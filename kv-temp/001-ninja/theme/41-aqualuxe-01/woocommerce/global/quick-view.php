<?php
/**
 * Quick View Template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quick-view.php.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// This template is loaded via AJAX, so we need to get the product
global $product;

if ( ! $product ) {
    return;
}
?>
<div id="quick-view-modal" class="quick-view-modal">
    <div class="quick-view-container bg-white dark:bg-dark-800 rounded-lg shadow-lg max-w-4xl mx-auto">
        <div class="quick-view-header flex justify-between items-center p-4 border-b border-gray-200 dark:border-dark-700">
            <h2 class="text-xl font-serif font-bold text-dark-900 dark:text-white"><?php echo esc_html( $product->get_name() ); ?></h2>
            <button class="quick-view-close text-dark-500 dark:text-dark-400 hover:text-dark-700 dark:hover:text-dark-200 focus:outline-none" aria-label="<?php esc_attr_e( 'Close quick view', 'aqualuxe' ); ?>">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div class="quick-view-content p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="quick-view-images">
                    <?php
                    // Main product image
                    if ( has_post_thumbnail() ) {
                        echo '<div class="quick-view-main-image mb-4">';
                        echo woocommerce_get_product_thumbnail( 'large' );
                        echo '</div>';
                    }
                    
                    // Gallery images
                    $attachment_ids = $product->get_gallery_image_ids();
                    
                    if ( $attachment_ids && count( $attachment_ids ) > 0 ) {
                        echo '<div class="quick-view-gallery grid grid-cols-4 gap-2">';
                        
                        // Add featured image to gallery
                        if ( has_post_thumbnail() ) {
                            echo '<div class="quick-view-gallery-item cursor-pointer border-2 border-primary-500 rounded overflow-hidden">';
                            echo woocommerce_get_product_thumbnail( 'thumbnail' );
                            echo '</div>';
                        }
                        
                        // Add gallery images
                        foreach ( $attachment_ids as $attachment_id ) {
                            echo '<div class="quick-view-gallery-item cursor-pointer border-2 border-transparent hover:border-primary-500 rounded overflow-hidden">';
                            echo wp_get_attachment_image( $attachment_id, 'thumbnail' );
                            echo '</div>';
                        }
                        
                        echo '</div>';
                    }
                    ?>
                </div>
                
                <div class="quick-view-details">
                    <?php if ( $product->is_on_sale() ) : ?>
                        <div class="quick-view-sale-flash mb-4">
                            <span class="bg-accent-500 text-white text-xs px-2 py-1 rounded-full">
                                <?php esc_html_e( 'Sale!', 'aqualuxe' ); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="quick-view-price mb-4">
                        <span class="text-2xl font-bold text-dark-900 dark:text-white">
                            <?php echo $product->get_price_html(); ?>
                        </span>
                    </div>
                    
                    <div class="quick-view-rating mb-4">
                        <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                    </div>
                    
                    <div class="quick-view-description mb-6">
                        <?php echo apply_filters( 'woocommerce_short_description', $product->get_short_description() ); ?>
                    </div>
                    
                    <div class="quick-view-add-to-cart mb-6">
                        <?php
                        // Output the add to cart button
                        woocommerce_template_single_add_to_cart();
                        ?>
                    </div>
                    
                    <div class="quick-view-meta text-sm text-dark-500 dark:text-dark-400">
                        <?php if ( wc_product_sku_enabled() && $product->get_sku() ) : ?>
                            <div class="quick-view-sku mb-2">
                                <span class="font-medium"><?php esc_html_e( 'SKU:', 'aqualuxe' ); ?></span> 
                                <span><?php echo esc_html( $product->get_sku() ); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( $product->get_category_ids() ) : ?>
                            <div class="quick-view-categories mb-2">
                                <span class="font-medium"><?php esc_html_e( 'Categories:', 'aqualuxe' ); ?></span> 
                                <?php echo wc_get_product_category_list( $product->get_id(), ', ' ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( $product->get_tag_ids() ) : ?>
                            <div class="quick-view-tags">
                                <span class="font-medium"><?php esc_html_e( 'Tags:', 'aqualuxe' ); ?></span> 
                                <?php echo wc_get_product_tag_list( $product->get_id(), ', ' ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="quick-view-footer p-4 border-t border-gray-200 dark:border-dark-700 flex justify-between items-center">
            <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:underline">
                <?php esc_html_e( 'View full details', 'aqualuxe' ); ?>
                <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </a>
            
            <?php if ( function_exists( 'YITH_WCWL' ) ) : ?>
                <div class="wishlist-button">
                    <?php echo do_shortcode( '[yith_wcwl_add_to_wishlist product_id="' . $product->get_id() . '"]' ); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(function($) {
        // Quick view gallery functionality
        $('.quick-view-gallery-item').on('click', function() {
            var imgSrc = $(this).find('img').attr('src');
            var imgSrcset = $(this).find('img').attr('srcset');
            var imgAlt = $(this).find('img').attr('alt');
            
            // Update main image
            $('.quick-view-main-image img').attr('src', imgSrc);
            if (imgSrcset) {
                $('.quick-view-main-image img').attr('srcset', imgSrcset);
            }
            if (imgAlt) {
                $('.quick-view-main-image img').attr('alt', imgAlt);
            }
            
            // Update active state
            $('.quick-view-gallery-item').removeClass('border-primary-500').addClass('border-transparent');
            $(this).removeClass('border-transparent').addClass('border-primary-500');
        });
        
        // Close quick view
        $('.quick-view-close').on('click', function() {
            // This will be handled by the main quick view script
            $(document).trigger('aqualuxe_close_quick_view');
        });
    });
</script>