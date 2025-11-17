<?php
/**
 * Shop Topbar Template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/shop-topbar.php.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// Get current view mode (grid or list)
$view_mode = isset( $_COOKIE['aqualuxe_product_view'] ) ? sanitize_text_field( $_COOKIE['aqualuxe_product_view'] ) : 'grid';

// Get current orderby value
$orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'menu_order';

// Get catalog orderby options
$catalog_orderby_options = apply_filters(
    'woocommerce_catalog_orderby',
    array(
        'menu_order' => __( 'Default sorting', 'aqualuxe' ),
        'popularity' => __( 'Sort by popularity', 'aqualuxe' ),
        'rating'     => __( 'Sort by average rating', 'aqualuxe' ),
        'date'       => __( 'Sort by latest', 'aqualuxe' ),
        'price'      => __( 'Sort by price: low to high', 'aqualuxe' ),
        'price-desc' => __( 'Sort by price: high to low', 'aqualuxe' ),
    )
);
?>

<div class="shop-topbar flex flex-wrap items-center justify-between mb-6 bg-white dark:bg-dark-800 rounded-lg shadow-sm p-4">
    <div class="shop-topbar-left flex items-center">
        <?php
        // Display result count
        woocommerce_result_count();
        ?>
    </div>
    
    <div class="shop-topbar-right flex items-center space-x-4">
        <div class="view-switcher hidden md:flex items-center space-x-2">
            <span class="text-dark-500 dark:text-dark-400 text-sm"><?php esc_html_e( 'View:', 'aqualuxe' ); ?></span>
            
            <a href="#" class="view-switcher-btn grid-view <?php echo $view_mode === 'grid' ? 'active text-primary-600 dark:text-primary-400' : 'text-dark-500 dark:text-dark-400 hover:text-dark-700 dark:hover:text-dark-200'; ?>" data-view="grid" aria-label="<?php esc_attr_e( 'Grid view', 'aqualuxe' ); ?>">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
            </a>
            
            <a href="#" class="view-switcher-btn list-view <?php echo $view_mode === 'list' ? 'active text-primary-600 dark:text-primary-400' : 'text-dark-500 dark:text-dark-400 hover:text-dark-700 dark:hover:text-dark-200'; ?>" data-view="list" aria-label="<?php esc_attr_e( 'List view', 'aqualuxe' ); ?>">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
            </a>
        </div>
        
        <div class="ordering">
            <form class="woocommerce-ordering" method="get">
                <div class="flex items-center">
                    <label for="orderby" class="mr-2 text-dark-500 dark:text-dark-400 text-sm hidden md:inline-block"><?php esc_html_e( 'Sort by:', 'aqualuxe' ); ?></label>
                    
                    <select name="orderby" id="orderby" class="orderby form-select text-sm" aria-label="<?php esc_attr_e( 'Shop order', 'aqualuxe' ); ?>">
                        <?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
                            <option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                    
                    <input type="hidden" name="paged" value="1" />
                    
                    <?php wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page' ) ); ?>
                </div>
            </form>
        </div>
    </div>
</div>