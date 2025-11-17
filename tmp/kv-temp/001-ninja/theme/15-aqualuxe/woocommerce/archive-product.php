<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

get_header('shop');

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action('woocommerce_before_main_content');

// Get shop display options
$shop_layout = get_theme_mod('aqualuxe_shop_layout', 'grid');
$products_per_row = get_theme_mod('aqualuxe_products_per_row', 3);
$sidebar_position = get_theme_mod('aqualuxe_shop_sidebar_position', 'right');
$enable_filters = get_theme_mod('aqualuxe_enable_shop_filters', true);

// Set column classes based on sidebar and products per row
$column_classes = 'grid grid-cols-2';
if ($sidebar_position === 'none') {
    if ($products_per_row == 2) {
        $column_classes = 'grid grid-cols-2';
    } elseif ($products_per_row == 3) {
        $column_classes = 'grid grid-cols-2 md:grid-cols-3';
    } elseif ($products_per_row == 4) {
        $column_classes = 'grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4';
    }
} else {
    if ($products_per_row == 2) {
        $column_classes = 'grid grid-cols-2';
    } elseif ($products_per_row == 3) {
        $column_classes = 'grid grid-cols-2 lg:grid-cols-3';
    } elseif ($products_per_row == 4) {
        $column_classes = 'grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4';
    }
}
?>

<header class="woocommerce-products-header bg-gray-50 dark:bg-gray-800 py-8">
    <div class="container mx-auto px-4">
        <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
            <h1 class="woocommerce-products-header__title page-title text-3xl md:text-4xl font-bold mb-4"><?php woocommerce_page_title(); ?></h1>
        <?php endif; ?>

        <?php
        /**
         * Hook: woocommerce_archive_description.
         *
         * @hooked woocommerce_taxonomy_archive_description - 10
         * @hooked woocommerce_product_archive_description - 10
         */
        do_action('woocommerce_archive_description');
        ?>
    </div>
</header>

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-wrap lg:flex-nowrap <?php echo ($sidebar_position !== 'none') ? 'lg:space-x-8' : ''; ?>">
        <?php if ($sidebar_position === 'left') : ?>
            <div class="w-full lg:w-1/4 mb-8 lg:mb-0">
                <?php if ($enable_filters) : ?>
                    <div class="shop-filters bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md mb-6">
                        <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Filter Products', 'aqualuxe'); ?></h3>
                        <?php dynamic_sidebar('sidebar-shop-filters'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="shop-sidebar bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
                    <?php dynamic_sidebar('sidebar-shop'); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="w-full <?php echo ($sidebar_position !== 'none') ? 'lg:w-3/4' : ''; ?>">
            <?php
            if (woocommerce_product_loop()) {
                /**
                 * Hook: woocommerce_before_shop_loop.
                 *
                 * @hooked woocommerce_output_all_notices - 10
                 * @hooked woocommerce_result_count - 20
                 * @hooked woocommerce_catalog_ordering - 30
                 */
                ?>
                <div class="shop-controls flex flex-wrap justify-between items-center mb-6 bg-white dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                    <div class="shop-result-count mb-4 md:mb-0">
                        <?php woocommerce_result_count(); ?>
                    </div>
                    <div class="shop-ordering flex items-center space-x-4">
                        <div class="view-switcher hidden md:flex space-x-2">
                            <button class="view-grid p-2 rounded-md <?php echo ($shop_layout === 'grid') ? 'bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200' : 'bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300'; ?>" data-view="grid" aria-label="<?php esc_attr_e('Grid view', 'aqualuxe'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </button>
                            <button class="view-list p-2 rounded-md <?php echo ($shop_layout === 'list') ? 'bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200' : 'bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300'; ?>" data-view="list" aria-label="<?php esc_attr_e('List view', 'aqualuxe'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                        <?php woocommerce_catalog_ordering(); ?>
                    </div>
                </div>
                <?php
                do_action('woocommerce_before_shop_loop');

                woocommerce_product_loop_start();

                if (wc_get_loop_prop('total')) {
                    while (have_posts()) {
                        the_post();

                        /**
                         * Hook: woocommerce_shop_loop.
                         */
                        do_action('woocommerce_shop_loop');

                        wc_get_template_part('content', 'product');
                    }
                }

                woocommerce_product_loop_end();

                /**
                 * Hook: woocommerce_after_shop_loop.
                 *
                 * @hooked woocommerce_pagination - 10
                 */
                do_action('woocommerce_after_shop_loop');
            } else {
                /**
                 * Hook: woocommerce_no_products_found.
                 *
                 * @hooked wc_no_products_found - 10
                 */
                do_action('woocommerce_no_products_found');
            }
            ?>
        </div>

        <?php if ($sidebar_position === 'right') : ?>
            <div class="w-full lg:w-1/4 mt-8 lg:mt-0">
                <?php if ($enable_filters) : ?>
                    <div class="shop-filters bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md mb-6">
                        <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Filter Products', 'aqualuxe'); ?></h3>
                        <?php dynamic_sidebar('sidebar-shop-filters'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="shop-sidebar bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
                    <?php dynamic_sidebar('sidebar-shop'); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
// We're handling the sidebar manually above, so we don't need this hook
// do_action('woocommerce_sidebar');

get_footer('shop');
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View switcher functionality
    const gridButton = document.querySelector('.view-grid');
    const listButton = document.querySelector('.view-list');
    const productsContainer = document.querySelector('.products');
    
    if (gridButton && listButton && productsContainer) {
        gridButton.addEventListener('click', function() {
            // Add grid classes
            productsContainer.classList.add('grid');
            productsContainer.classList.add('grid-cols-2');
            productsContainer.classList.add('md:grid-cols-3');
            
            // Remove list classes
            productsContainer.classList.remove('list-view');
            
            // Update active state
            gridButton.classList.add('bg-primary-100', 'dark:bg-primary-900', 'text-primary-800', 'dark:text-primary-200');
            gridButton.classList.remove('bg-gray-100', 'dark:bg-gray-600', 'text-gray-700', 'dark:text-gray-300');
            
            listButton.classList.remove('bg-primary-100', 'dark:bg-primary-900', 'text-primary-800', 'dark:text-primary-200');
            listButton.classList.add('bg-gray-100', 'dark:bg-gray-600', 'text-gray-700', 'dark:text-gray-300');
            
            // Store preference in localStorage
            localStorage.setItem('aqualuxe_shop_view', 'grid');
            
            // Update product items
            document.querySelectorAll('.product').forEach(function(product) {
                product.classList.remove('list-item');
            });
        });
        
        listButton.addEventListener('click', function() {
            // Add list classes
            productsContainer.classList.add('list-view');
            
            // Remove grid classes
            productsContainer.classList.remove('grid');
            productsContainer.classList.remove('grid-cols-2');
            productsContainer.classList.remove('md:grid-cols-3');
            
            // Update active state
            listButton.classList.add('bg-primary-100', 'dark:bg-primary-900', 'text-primary-800', 'dark:text-primary-200');
            listButton.classList.remove('bg-gray-100', 'dark:bg-gray-600', 'text-gray-700', 'dark:text-gray-300');
            
            gridButton.classList.remove('bg-primary-100', 'dark:bg-primary-900', 'text-primary-800', 'dark:text-primary-200');
            gridButton.classList.add('bg-gray-100', 'dark:bg-gray-600', 'text-gray-700', 'dark:text-gray-300');
            
            // Store preference in localStorage
            localStorage.setItem('aqualuxe_shop_view', 'list');
            
            // Update product items
            document.querySelectorAll('.product').forEach(function(product) {
                product.classList.add('list-item');
            });
        });
        
        // Check localStorage for saved preference
        const savedView = localStorage.getItem('aqualuxe_shop_view');
        if (savedView === 'list') {
            listButton.click();
        }
    }
});
</script>