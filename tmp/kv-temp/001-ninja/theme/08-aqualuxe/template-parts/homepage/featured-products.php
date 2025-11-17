<?php
/**
 * Template part for displaying featured products on the homepage
 *
 * @package AquaLuxe
 */

// Check if WooCommerce is active
if (!class_exists('WooCommerce')) {
    return;
}

// Get featured products settings from customizer
$featured_products_title = get_theme_mod('aqualuxe_featured_products_title', __('Featured Products', 'aqualuxe'));
$featured_products_description = get_theme_mod('aqualuxe_featured_products_description', __('Explore our selection of premium aquatic life and supplies', 'aqualuxe'));
$featured_products_count = get_theme_mod('aqualuxe_featured_products_count', 4);
$featured_products_columns = get_theme_mod('aqualuxe_featured_products_columns', 4);
$featured_products_enable = get_theme_mod('aqualuxe_featured_products_enable', true);
$view_all_text = get_theme_mod('aqualuxe_featured_products_view_all_text', __('View All Products', 'aqualuxe'));

// Exit if featured products section is disabled
if (!$featured_products_enable) {
    return;
}

// Get shop page URL
$shop_page_url = get_permalink(wc_get_page_id('shop'));
?>

<section class="featured-products">
    <div class="container">
        <div class="section-header">
            <?php if ($featured_products_title) : ?>
                <h2 class="section-title"><?php echo esc_html($featured_products_title); ?></h2>
            <?php endif; ?>
            
            <?php if ($featured_products_description) : ?>
                <p class="section-description"><?php echo esc_html($featured_products_description); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="products-wrapper">
            <?php
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => $featured_products_count,
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'name',
                        'terms'    => 'featured',
                    ),
                ),
            );
            
            $featured_query = new WP_Query($args);
            
            if ($featured_query->have_posts()) :
                echo '<div class="products-grid columns-' . esc_attr($featured_products_columns) . '">';
                
                while ($featured_query->have_posts()) : $featured_query->the_post();
                    wc_get_template_part('content', 'product');
                endwhile;
                
                echo '</div>';
                
                wp_reset_postdata();
            else :
                echo '<p class="no-products">' . esc_html__('No featured products found.', 'aqualuxe') . '</p>';
            endif;
            ?>
        </div>
        
        <?php if ($shop_page_url && $view_all_text) : ?>
            <div class="view-all-wrapper">
                <a href="<?php echo esc_url($shop_page_url); ?>" class="btn btn-primary"><?php echo esc_html($view_all_text); ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>