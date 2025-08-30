<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get tabs settings from theme customizer
$tabs_style = get_theme_mod( 'aqualuxe_product_tabs_style', 'horizontal' );
$tabs_position = get_theme_mod( 'aqualuxe_product_tabs_position', 'after' );
$custom_tabs = get_theme_mod( 'aqualuxe_product_custom_tabs', array() );

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

// Add custom tabs from theme customizer
if ( ! empty( $custom_tabs ) && is_array( $custom_tabs ) ) {
    foreach ( $custom_tabs as $index => $custom_tab ) {
        if ( ! empty( $custom_tab['title'] ) && ! empty( $custom_tab['content'] ) ) {
            $tab_id = 'custom_tab_' . $index;
            $product_tabs[ $tab_id ] = array(
                'title'    => $custom_tab['title'],
                'priority' => 50 + $index,
                'callback' => function() use ( $custom_tab ) {
                    echo wp_kses_post( wpautop( do_shortcode( $custom_tab['content'] ) ) );
                },
            );
        }
    }
}

if ( ! empty( $product_tabs ) ) : ?>

<div class="aqualuxe-product-tabs aqualuxe-tabs-style-<?php echo esc_attr( $tabs_style ); ?>">
    <?php if ( 'accordion' === $tabs_style ) : ?>
        <div class="aqualuxe-accordion-tabs">
            <?php foreach ( $product_tabs as $key => $product_tab ) : ?>
                <div class="aqualuxe-accordion-tab">
                    <div class="aqualuxe-accordion-header" id="tab-title-<?php echo esc_attr( $key ); ?>">
                        <button class="aqualuxe-accordion-trigger" aria-expanded="false" aria-controls="tab-content-<?php echo esc_attr( $key ); ?>">
                            <?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
                            <svg class="aqualuxe-accordion-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </button>
                    </div>
                    <div class="aqualuxe-accordion-content" id="tab-content-<?php echo esc_attr( $key ); ?>" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>" hidden>
                        <?php
                        if ( isset( $product_tab['callback'] ) ) {
                            call_user_func( $product_tab['callback'], $key, $product_tab );
                        }
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="woocommerce-tabs wc-tabs-wrapper">
            <ul class="tabs wc-tabs" role="tablist">
                <?php foreach ( $product_tabs as $key => $product_tab ) : ?>
                    <li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
                        <a href="#tab-<?php echo esc_attr( $key ); ?>">
                            <?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php foreach ( $product_tabs as $key => $product_tab ) : ?>
                <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
                    <?php
                    if ( isset( $product_tab['callback'] ) ) {
                        call_user_func( $product_tab['callback'], $key, $product_tab );
                    }
                    ?>
                </div>
            <?php endforeach; ?>

            <?php do_action( 'woocommerce_product_after_tabs' ); ?>
        </div>
    <?php endif; ?>
</div>

<?php endif; ?>