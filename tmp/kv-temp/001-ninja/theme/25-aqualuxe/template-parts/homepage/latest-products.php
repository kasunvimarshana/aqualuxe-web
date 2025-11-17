<?php
/**
 * Template part for displaying the homepage latest products section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Check if WooCommerce is active
if (!class_exists('WooCommerce')) {
    return;
}

// Get section settings from theme options
$section_title = get_theme_mod('aqualuxe_latest_products_title', __('Latest Products', 'aqualuxe'));
$section_subtitle = get_theme_mod('aqualuxe_latest_products_subtitle', __('New Arrivals', 'aqualuxe'));
$section_description = get_theme_mod('aqualuxe_latest_products_description', __('Discover our newest additions to the AquaLuxe collection.', 'aqualuxe'));
$products_count = get_theme_mod('aqualuxe_latest_products_count', 4);
$products_columns = get_theme_mod('aqualuxe_latest_products_columns', 4);
$view_all_text = get_theme_mod('aqualuxe_latest_products_view_all', __('View All Products', 'aqualuxe'));
$section_background = get_theme_mod('aqualuxe_latest_products_background', 'gray');
$section_style = get_theme_mod('aqualuxe_latest_products_style', 'grid');

// Set background class based on setting
$bg_class = $section_background === 'gray' ? 'bg-gray-50' : 'bg-white';

// Set columns class based on setting
switch ($products_columns) {
    case 2:
        $columns_class = 'grid-cols-1 md:grid-cols-2';
        break;
    case 3:
        $columns_class = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3';
        break;
    case 5:
        $columns_class = 'grid-cols-1 md:grid-cols-3 lg:grid-cols-5';
        break;
    case 4:
    default:
        $columns_class = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4';
        break;
}
?>

<section class="latest-products-section py-16 <?php echo esc_attr($bg_class); ?>">
    <div class="container mx-auto px-4">
        <div class="section-header text-center mb-12">
            <?php if ($section_subtitle) : ?>
                <div class="section-subtitle text-primary text-lg mb-2">
                    <?php echo esc_html($section_subtitle); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($section_title) : ?>
                <h2 class="section-title text-3xl md:text-4xl font-bold mb-4">
                    <?php echo esc_html($section_title); ?>
                </h2>
            <?php endif; ?>
            
            <?php if ($section_description) : ?>
                <div class="section-description max-w-3xl mx-auto text-gray-600">
                    <?php echo wp_kses_post($section_description); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="latest-products">
            <?php
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => $products_count,
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC',
            );
            
            $latest_query = new WP_Query($args);
            
            if ($latest_query->have_posts()) :
                
                if ($section_style === 'carousel') :
                    // Carousel layout
                    ?>
                    <div class="latest-products-carousel swiper-container">
                        <div class="swiper-wrapper">
                            <?php
                            while ($latest_query->have_posts()) :
                                $latest_query->the_post();
                                global $product;
                                ?>
                                <div class="swiper-slide">
                                    <div class="product-card">
                                        <?php
                                        // Product template
                                        wc_get_template_part('content', 'product');
                                        ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        
                        <div class="swiper-pagination mt-6"></div>
                        
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                <?php else : ?>
                    <!-- Grid layout -->
                    <div class="grid <?php echo esc_attr($columns_class); ?> gap-6">
                        <?php
                        while ($latest_query->have_posts()) :
                            $latest_query->the_post();
                            global $product;
                            ?>
                            <div class="product-card">
                                <?php
                                // Product template
                                wc_get_template_part('content', 'product');
                                ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($view_all_text) : ?>
                    <div class="view-all text-center mt-12">
                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="button button-primary">
                            <?php echo esc_html($view_all_text); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php
                wp_reset_postdata();
            else :
                ?>
                <div class="no-products text-center py-8">
                    <p><?php esc_html_e('No products found.', 'aqualuxe'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>