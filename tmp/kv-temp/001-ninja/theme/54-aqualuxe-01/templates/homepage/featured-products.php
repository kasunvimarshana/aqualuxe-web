<?php
/**
 * Homepage Featured Products Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Check if WooCommerce is active
if (!aqualuxe_is_woocommerce_active()) {
    return;
}

// Get customizer settings
$section_title = get_theme_mod('aqualuxe_homepage_featured_products_title', __('Featured Products', 'aqualuxe'));
$section_subtitle = get_theme_mod('aqualuxe_homepage_featured_products_subtitle', __('Discover our premium selection of aquatic products', 'aqualuxe'));
$number_of_products = get_theme_mod('aqualuxe_homepage_featured_products_number', 4);
$columns = get_theme_mod('aqualuxe_homepage_featured_products_columns', 4);
$product_type = get_theme_mod('aqualuxe_homepage_featured_products_type', 'featured'); // featured, best_selling, newest, sale
$view_all_text = get_theme_mod('aqualuxe_homepage_featured_products_view_all', __('View All Products', 'aqualuxe'));
$view_all_url = get_theme_mod('aqualuxe_homepage_featured_products_view_all_url', wc_get_page_permalink('shop'));
$show_section = get_theme_mod('aqualuxe_homepage_featured_products_show', true);

// Exit if section is disabled
if (!$show_section) {
    return;
}

// Set up query args based on product type
$args = array(
    'post_type' => 'product',
    'posts_per_page' => $number_of_products,
    'post_status' => 'publish',
);

switch ($product_type) {
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
    case 'newest':
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
        break;
    case 'sale':
        $args['meta_query'] = array(
            'relation' => 'OR',
            array(
                'key' => '_sale_price',
                'value' => 0,
                'compare' => '>',
                'type' => 'NUMERIC',
            ),
            array(
                'key' => '_min_variation_sale_price',
                'value' => 0,
                'compare' => '>',
                'type' => 'NUMERIC',
            ),
        );
        break;
}

// Get products
$products = new WP_Query($args);

// Exit if no products
if (!$products->have_posts()) {
    return;
}

// Set column class
$column_class = '';
switch ($columns) {
    case 1:
        $column_class = 'grid-cols-1';
        break;
    case 2:
        $column_class = 'grid-cols-1 md:grid-cols-2';
        break;
    case 3:
        $column_class = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3';
        break;
    case 4:
    default:
        $column_class = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4';
        break;
}
?>

<section class="aqualuxe-featured-products py-16 bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <?php if ($section_title) : ?>
                <h2 class="text-3xl font-bold mb-4"><?php echo esc_html($section_title); ?></h2>
            <?php endif; ?>
            
            <?php if ($section_subtitle) : ?>
                <p class="text-lg text-gray-600 dark:text-gray-400"><?php echo esc_html($section_subtitle); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="grid <?php echo esc_attr($column_class); ?> gap-6">
            <?php
            while ($products->have_posts()) : $products->the_post();
                global $product;
                ?>
                <div class="aqualuxe-product-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                    <a href="<?php the_permalink(); ?>" class="block relative">
                        <div class="aspect-w-1 aspect-h-1 w-full">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('woocommerce_thumbnail', ['class' => 'object-cover w-full h-full']); ?>
                            <?php else : ?>
                                <img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" alt="<?php esc_attr_e('Product Image', 'aqualuxe'); ?>" class="object-cover w-full h-full">
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($product->is_on_sale()) : ?>
                            <span class="absolute top-4 right-4 bg-primary-600 text-white text-sm font-bold px-3 py-1 rounded-full">
                                <?php esc_html_e('Sale', 'aqualuxe'); ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2">
                            <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 transition-colors">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <div class="flex items-center justify-between">
                            <div class="price text-lg font-bold text-primary-600">
                                <?php echo $product->get_price_html(); ?>
                            </div>
                            
                            <div class="rating">
                                <?php if ($product->get_average_rating()) : ?>
                                    <div class="star-rating" title="<?php printf(esc_attr__('Rated %s out of 5', 'aqualuxe'), $product->get_average_rating()); ?>">
                                        <span style="width:<?php echo esc_attr(($product->get_average_rating() / 5) * 100); ?>%">
                                            <strong class="rating"><?php echo esc_html($product->get_average_rating()); ?></strong> <?php esc_html_e('out of 5', 'aqualuxe'); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="mt-4 flex items-center justify-between">
                            <?php if ($product->is_in_stock()) : ?>
                                <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" data-quantity="1" class="btn btn-sm btn-primary add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr($product->get_id()); ?>" data-product_sku="<?php echo esc_attr($product->get_sku()); ?>">
                                    <?php esc_html_e('Add to Cart', 'aqualuxe'); ?>
                                </a>
                            <?php else : ?>
                                <span class="btn btn-sm btn-disabled">
                                    <?php esc_html_e('Out of Stock', 'aqualuxe'); ?>
                                </span>
                            <?php endif; ?>
                            
                            <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline">
                                <?php esc_html_e('Details', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
        
        <?php if ($view_all_text && $view_all_url) : ?>
            <div class="text-center mt-12">
                <a href="<?php echo esc_url($view_all_url); ?>" class="btn btn-primary">
                    <?php echo esc_html($view_all_text); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>