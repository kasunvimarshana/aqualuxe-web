<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header('shop');

// Get product display options
$layout_style = get_theme_mod('aqualuxe_product_layout', 'standard');
$enable_sticky_info = get_theme_mod('aqualuxe_enable_sticky_product_info', true);
$enable_image_zoom = get_theme_mod('aqualuxe_enable_product_image_zoom', true);
$enable_image_lightbox = get_theme_mod('aqualuxe_enable_product_image_lightbox', true);
$enable_related_products = get_theme_mod('aqualuxe_enable_related_products', true);
$enable_upsells = get_theme_mod('aqualuxe_enable_upsells', true);
$enable_recently_viewed = get_theme_mod('aqualuxe_enable_recently_viewed', true);
$sidebar_position = get_theme_mod('aqualuxe_product_sidebar_position', 'none');

// Set layout classes
$container_class = 'container mx-auto px-4 py-8';
$content_class = 'product-content';
$has_sidebar = is_active_sidebar('sidebar-product') && $sidebar_position !== 'none';

if ($has_sidebar) {
    $content_class .= ' lg:w-3/4';
    if ($sidebar_position === 'left') {
        $content_class .= ' lg:order-2';
    } else {
        $content_class .= ' lg:order-1';
    }
}

// Set product info classes
$product_info_class = $enable_sticky_info ? 'lg:sticky lg:top-24' : '';
?>

<div class="<?php echo esc_attr($container_class); ?>">
    <?php
    /**
     * woocommerce_before_main_content hook.
     *
     * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
     * @hooked woocommerce_breadcrumb - 20
     */
    do_action('woocommerce_before_main_content');
    ?>

    <div class="flex flex-wrap lg:flex-nowrap <?php echo $has_sidebar ? 'lg:space-x-8' : ''; ?>">
        <?php if ($has_sidebar && $sidebar_position === 'left') : ?>
            <aside id="secondary" class="product-sidebar w-full lg:w-1/4 mb-8 lg:mb-0">
                <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
                    <?php dynamic_sidebar('sidebar-product'); ?>
                </div>
            </aside>
        <?php endif; ?>

        <div class="<?php echo esc_attr($content_class); ?>">
            <?php while (have_posts()) : ?>
                <?php the_post(); ?>

                <?php wc_get_template_part('content', 'single-product'); ?>

            <?php endwhile; // end of the loop. ?>
        </div>

        <?php if ($has_sidebar && $sidebar_position === 'right') : ?>
            <aside id="secondary" class="product-sidebar w-full lg:w-1/4 mt-8 lg:mt-0">
                <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
                    <?php dynamic_sidebar('sidebar-product'); ?>
                </div>
            </aside>
        <?php endif; ?>
    </div>

    <?php
    /**
     * woocommerce_after_main_content hook.
     *
     * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
     */
    do_action('woocommerce_after_main_content');
    ?>

    <?php
    // Related Products
    if ($enable_related_products) {
        woocommerce_output_related_products();
    }

    // Recently Viewed Products
    if ($enable_recently_viewed) {
        // Get recently viewed product cookies data
        $viewed_products = !empty($_COOKIE['woocommerce_recently_viewed']) ? (array) explode('|', $_COOKIE['woocommerce_recently_viewed']) : array();
        $viewed_products = array_filter(array_map('absint', $viewed_products));

        if (!empty($viewed_products)) {
            $viewed_products = array_slice($viewed_products, 0, 4);
            $args = array(
                'posts_per_page' => 4,
                'no_found_rows'  => 1,
                'post_status'    => 'publish',
                'post_type'      => 'product',
                'post__in'       => $viewed_products,
                'orderby'        => 'post__in',
            );

            $products = new WP_Query($args);

            if ($products->have_posts()) {
                ?>
                <section class="recently-viewed-products py-12 bg-gray-50 dark:bg-gray-800">
                    <div class="container mx-auto px-4">
                        <h2 class="text-2xl md:text-3xl font-bold mb-8"><?php esc_html_e('Recently Viewed', 'aqualuxe'); ?></h2>
                        
                        <ul class="products grid grid-cols-2 md:grid-cols-4 gap-6">
                            <?php while ($products->have_posts()) : $products->the_post(); ?>
                                <?php wc_get_template_part('content', 'product'); ?>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </section>
                <?php
                wp_reset_postdata();
            }
        }
    }
    ?>
</div>

<?php
get_footer('shop');