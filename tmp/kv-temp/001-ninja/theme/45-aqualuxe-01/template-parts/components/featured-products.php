<?php
/**
 * Template part for displaying featured products on the homepage
 *
 * @package AquaLuxe
 */

// Check if WooCommerce is active
if (!aqualuxe_is_woocommerce_active()) {
    return;
}

// Get featured products options from theme customizer
$show_featured_products = get_theme_mod('aqualuxe_show_featured_products', true);
$featured_products_title = get_theme_mod('aqualuxe_featured_products_title', __('Featured Products', 'aqualuxe'));
$featured_products_subtitle = get_theme_mod('aqualuxe_featured_products_subtitle', __('Our handpicked selection of premium aquatic products', 'aqualuxe'));
$featured_products_count = get_theme_mod('aqualuxe_featured_products_count', 4);
$featured_products_columns = get_theme_mod('aqualuxe_featured_products_columns', 4);
$featured_products_source = get_theme_mod('aqualuxe_featured_products_source', 'featured');
$featured_products_category = get_theme_mod('aqualuxe_featured_products_category', 0);
$featured_products_orderby = get_theme_mod('aqualuxe_featured_products_orderby', 'date');
$featured_products_order = get_theme_mod('aqualuxe_featured_products_order', 'desc');
$featured_products_button_text = get_theme_mod('aqualuxe_featured_products_button_text', __('View All Products', 'aqualuxe'));
$featured_products_button_url = get_theme_mod('aqualuxe_featured_products_button_url', get_permalink(wc_get_page_id('shop')));

// Check if featured products should be displayed
if (!$show_featured_products) {
    return;
}

// Set up query arguments
$args = array(
    'post_type'      => 'product',
    'posts_per_page' => $featured_products_count,
    'orderby'        => $featured_products_orderby,
    'order'          => $featured_products_order,
);

// Add source filter
if ($featured_products_source === 'featured') {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => 'featured',
            'operator' => 'IN',
        ),
    );
} elseif ($featured_products_source === 'sale') {
    $product_ids_on_sale = wc_get_product_ids_on_sale();
    $args['post__in'] = array_merge(array(0), $product_ids_on_sale);
} elseif ($featured_products_source === 'best_selling') {
    $args['meta_key'] = 'total_sales';
    $args['orderby'] = 'meta_value_num';
} elseif ($featured_products_source === 'top_rated') {
    $args['meta_key'] = '_wc_average_rating';
    $args['orderby'] = 'meta_value_num';
}

// Add category filter
if ($featured_products_category > 0) {
    $args['tax_query'][] = array(
        'taxonomy' => 'product_cat',
        'field'    => 'term_id',
        'terms'    => $featured_products_category,
    );
}

// Get products
$featured_products = new WP_Query($args);

// Check if we have products
if (!$featured_products->have_posts()) {
    return;
}
?>

<div class="featured-products-section section-padding">
    <div class="container">
        <div class="section-header text-center">
            <?php if (!empty($featured_products_title)) : ?>
                <h2 class="section-title"><?php echo esc_html($featured_products_title); ?></h2>
            <?php endif; ?>
            
            <?php if (!empty($featured_products_subtitle)) : ?>
                <div class="section-subtitle"><?php echo esc_html($featured_products_subtitle); ?></div>
            <?php endif; ?>
        </div>
        
        <div class="featured-products-wrapper">
            <div class="row">
                <?php
                // Set up column classes
                $column_class = 'col-lg-3 col-md-6';
                
                switch ($featured_products_columns) {
                    case 2:
                        $column_class = 'col-lg-6 col-md-6';
                        break;
                    case 3:
                        $column_class = 'col-lg-4 col-md-6';
                        break;
                    case 4:
                        $column_class = 'col-lg-3 col-md-6';
                        break;
                }
                
                // Loop through products
                while ($featured_products->have_posts()) :
                    $featured_products->the_post();
                    global $product;
                    ?>
                    <div class="<?php echo esc_attr($column_class); ?>">
                        <div class="product-card">
                            <div class="product-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php
                                    if (has_post_thumbnail()) {
                                        the_post_thumbnail('woocommerce_thumbnail', array('class' => 'img-fluid'));
                                    } else {
                                        echo wc_placeholder_img('woocommerce_thumbnail');
                                    }
                                    ?>
                                </a>
                                
                                <?php
                                // Sale badge
                                if ($product->is_on_sale()) {
                                    echo '<span class="product-badge sale">' . esc_html__('Sale', 'aqualuxe') . '</span>';
                                }
                                
                                // New badge (products less than 30 days old)
                                $days_since_publish = (time() - get_the_time('U')) / (60 * 60 * 24);
                                if ($days_since_publish < 30) {
                                    echo '<span class="product-badge new">' . esc_html__('New', 'aqualuxe') . '</span>';
                                }
                                
                                // Out of stock badge
                                if (!$product->is_in_stock()) {
                                    echo '<span class="product-badge out-of-stock">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
                                }
                                ?>
                                
                                <div class="product-actions">
                                    <?php
                                    // Quick view button
                                    if (get_theme_mod('aqualuxe_show_quick_view', true)) {
                                        echo '<a href="#" class="quick-view-btn" data-product-id="' . esc_attr($product->get_id()) . '" title="' . esc_attr__('Quick View', 'aqualuxe') . '"><i class="fas fa-eye"></i></a>';
                                    }
                                    
                                    // Wishlist button
                                    if (get_theme_mod('aqualuxe_show_wishlist', true) && function_exists('YITH_WCWL')) {
                                        echo do_shortcode('[yith_wcwl_add_to_wishlist]');
                                    }
                                    
                                    // Compare button
                                    if (get_theme_mod('aqualuxe_show_compare', true) && function_exists('YITH_WOOCOMPARE')) {
                                        echo do_shortcode('[yith_compare_button]');
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <div class="product-content">
                                <div class="product-categories">
                                    <?php echo wc_get_product_category_list($product->get_id(), ', '); ?>
                                </div>
                                
                                <h3 class="product-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                
                                <div class="product-price">
                                    <?php echo $product->get_price_html(); ?>
                                </div>
                                
                                <?php
                                // Rating
                                if ($product->get_rating_count() > 0) {
                                    echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count());
                                }
                                ?>
                                
                                <div class="product-add-to-cart">
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
                                        $product
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </div>
        
        <?php if (!empty($featured_products_button_text) && !empty($featured_products_button_url)) : ?>
            <div class="text-center mt-5">
                <a href="<?php echo esc_url($featured_products_button_url); ?>" class="btn btn-primary">
                    <?php echo esc_html($featured_products_button_text); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>