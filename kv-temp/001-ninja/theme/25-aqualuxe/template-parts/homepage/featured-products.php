<?php
/**
 * Template part for displaying the homepage featured products section
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
$section_title = get_theme_mod('aqualuxe_featured_products_title', __('Featured Products', 'aqualuxe'));
$section_subtitle = get_theme_mod('aqualuxe_featured_products_subtitle', __('Our Premium Selection', 'aqualuxe'));
$section_description = get_theme_mod('aqualuxe_featured_products_description', __('Discover our handpicked selection of premium ornamental fish and aquatic products.', 'aqualuxe'));
$products_count = get_theme_mod('aqualuxe_featured_products_count', 4);
$products_columns = get_theme_mod('aqualuxe_featured_products_columns', 4);
$view_all_text = get_theme_mod('aqualuxe_featured_products_view_all', __('View All Products', 'aqualuxe'));
$featured_category = get_theme_mod('aqualuxe_featured_products_category', '');
$section_style = get_theme_mod('aqualuxe_featured_products_style', 'grid');

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

<section class="featured-products-section py-16 bg-gray-50">
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
        
        <div class="featured-products">
            <?php
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => $products_count,
                'post_status'    => 'publish',
            );
            
            // Add category filter if set
            if ($featured_category) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'term_id',
                        'terms'    => $featured_category,
                    ),
                );
            } else {
                // If no category is selected, show featured products
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'name',
                        'terms'    => 'featured',
                    ),
                );
            }
            
            $featured_query = new WP_Query($args);
            
            if ($featured_query->have_posts()) :
                
                if ($section_style === 'carousel') :
                    // Carousel layout
                    ?>
                    <div class="featured-products-carousel swiper-container">
                        <div class="swiper-wrapper">
                            <?php
                            while ($featured_query->have_posts()) :
                                $featured_query->the_post();
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
                        while ($featured_query->have_posts()) :
                            $featured_query->the_post();
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