<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
    return;
}

// Check if we should display the sidebar
$sidebar_position = aqualuxe_get_option( 'sidebar_position', 'right' );

if ( $sidebar_position === 'none' ) {
    return;
}

// WooCommerce specific sidebars
if ( aqualuxe_is_woocommerce_active() ) {
    if ( is_shop() || is_product_category() || is_product_tag() ) {
        if ( ! aqualuxe_get_option( 'enable_shop_sidebar', true ) ) {
            return;
        }
        
        if ( is_active_sidebar( 'shop-sidebar' ) ) {
            ?>
            <aside id="secondary" class="widget-area shop-sidebar" <?php aqualuxe_attr( 'sidebar' ); ?>>
                <?php
                /**
                 * Hook: aqualuxe_before_sidebar
                 */
                aqualuxe_do_before_sidebar();
                ?>

                <?php
                /**
                 * Hook: aqualuxe_sidebar_top
                 */
                aqualuxe_do_sidebar_top();
                ?>

                <?php dynamic_sidebar( 'shop-sidebar' ); ?>

                <?php
                /**
                 * Hook: aqualuxe_sidebar_bottom
                 */
                aqualuxe_do_sidebar_bottom();
                ?>

                <?php
                /**
                 * Hook: aqualuxe_after_sidebar
                 */
                aqualuxe_do_after_sidebar();
                ?>
            </aside><!-- #secondary -->
            <?php
            return;
        }
    }
    
    if ( is_product() ) {
        if ( is_active_sidebar( 'product-sidebar' ) ) {
            ?>
            <aside id="secondary" class="widget-area product-sidebar" <?php aqualuxe_attr( 'sidebar' ); ?>>
                <?php
                /**
                 * Hook: aqualuxe_before_sidebar
                 */
                aqualuxe_do_before_sidebar();
                ?>

                <?php
                /**
                 * Hook: aqualuxe_sidebar_top
                 */
                aqualuxe_do_sidebar_top();
                ?>

                <?php dynamic_sidebar( 'product-sidebar' ); ?>

                <?php
                /**
                 * Hook: aqualuxe_sidebar_bottom
                 */
                aqualuxe_do_sidebar_bottom();
                ?>

                <?php
                /**
                 * Hook: aqualuxe_after_sidebar
                 */
                aqualuxe_do_after_sidebar();
                ?>
            </aside><!-- #secondary -->
            <?php
            return;
        }
    }
}

// Default sidebar
?>
<aside id="secondary" class="widget-area" <?php aqualuxe_attr( 'sidebar' ); ?>>
    <?php
    /**
     * Hook: aqualuxe_before_sidebar
     */
    aqualuxe_do_before_sidebar();
    ?>

    <?php
    /**
     * Hook: aqualuxe_sidebar_top
     */
    aqualuxe_do_sidebar_top();
    ?>

    <?php dynamic_sidebar( 'sidebar-1' ); ?>

    <?php
    /**
     * Hook: aqualuxe_sidebar_bottom
     */
    aqualuxe_do_sidebar_bottom();
    ?>

    <?php
    /**
     * Hook: aqualuxe_after_sidebar
     */
    aqualuxe_do_after_sidebar();
    ?>
</aside><!-- #secondary -->