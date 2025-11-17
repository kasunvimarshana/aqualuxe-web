<?php
/**
 * Homepage Featured Products Section
 *
 * @package AquaLuxe
 */

// Check if WooCommerce is active
if (!class_exists('WooCommerce')) {
    return;
}

// Get section content from theme options or use default
$section_title = get_theme_mod('aqualuxe_featured_products_title', __('Featured Products', 'aqualuxe'));
$section_description = get_theme_mod('aqualuxe_featured_products_description', __('Discover our premium selection of ornamental fish, aquatic plants, and aquarium supplies.', 'aqualuxe'));
$products_count = get_theme_mod('aqualuxe_featured_products_count', 4);
$products_columns = get_theme_mod('aqualuxe_featured_products_columns', 4);
$featured_type = get_theme_mod('aqualuxe_featured_products_type', 'featured'); // featured, best_selling, new, sale

// Set up query args based on featured type
$args = array(
    'post_type' => 'product',
    'posts_per_page' => $products_count,
    'post_status' => 'publish',
);

switch ($featured_type) {
    case 'featured':
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_visibility',
                'field' => 'name',
                'terms' => 'featured',
                'operator' => 'IN',
            ),
        );
        break;
    case 'best_selling':
        $args['meta_key'] = 'total_sales';
        $args['orderby'] = 'meta_value_num';
        break;
    case 'new':
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
        break;
    case 'sale':
        $args['meta_query'] = array(
            'relation' => 'OR',
            array( // Simple products
                'key' => '_sale_price',
                'value' => 0,
                'compare' => '>',
                'type' => 'numeric'
            ),
            array( // Variable products
                'key' => '_min_variation_sale_price',
                'value' => 0,
                'compare' => '>',
                'type' => 'numeric'
            )
        );
        break;
}

// Get products
$products = new WP_Query($args);

// Only show section if products exist
if ($products->have_posts()) :
?>

<section class="featured-products-section py-16 bg-gray-50">
    <div class="container">
        <?php if ($section_title || $section_description) : ?>
            <div class="section-header text-center mb-12">
                <?php if ($section_title) : ?>
                    <h2 class="section-title text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($section_title); ?></h2>
                <?php endif; ?>
                
                <?php if ($section_description) : ?>
                    <p class="section-description text-lg text-gray-600 max-w-3xl mx-auto"><?php echo esc_html($section_description); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="woocommerce">
            <ul class="products columns-<?php echo esc_attr($products_columns); ?>">
                <?php
                while ($products->have_posts()) :
                    $products->the_post();
                    global $product;
                    ?>
                    <li <?php wc_product_class('', $product); ?>>
                        <a href="<?php the_permalink(); ?>" class="block">
                            <div class="product-image relative overflow-hidden">
                                <?php
                                if ($product->is_on_sale()) {
                                    echo '<span class="onsale">' . esc_html__('Sale!', 'aqualuxe') . '</span>';
                                }
                                
                                echo woocommerce_get_product_thumbnail('woocommerce_thumbnail');
                                ?>
                            </div>
                            
                            <h3 class="woocommerce-loop-product__title"><?php the_title(); ?></h3>
                            
                            <?php
                            echo wc_get_rating_html($product->get_average_rating());
                            echo '<span class="price">' . $product->get_price_html() . '</span>';
                            ?>
                        </a>
                        
                        <?php
                        echo apply_filters(
                            'woocommerce_loop_add_to_cart_link',
                            sprintf(
                                '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                                esc_url($product->add_to_cart_url()),
                                esc_attr(isset($args['quantity']) ? $args['quantity'] : 1),
                                esc_attr(isset($args['class']) ? $args['class'] : 'button'),
                                isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '',
                                esc_html($product->add_to_cart_text())
                            ),
                            $product,
                            $args
                        );
                        
                        // Quick view button
                        if (get_theme_mod('aqualuxe_quick_view', true)) {
                            echo '<a href="#" class="aqualuxe-quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Quick View', 'aqualuxe') . '</a>';
                        }
                        
                        // Wishlist button
                        if (get_theme_mod('aqualuxe_wishlist', true)) {
                            echo '<a href="#" class="aqualuxe-wishlist-button" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Add to Wishlist', 'aqualuxe') . '</a>';
                        }
                        ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
        
        <div class="text-center mt-10">
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary"><?php esc_html_e('View All Products', 'aqualuxe'); ?></a>
        </div>
    </div>
</section>

<?php
wp_reset_postdata();
endif;