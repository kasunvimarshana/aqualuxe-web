<?php
/**
 * Single Product tabs
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $product_tabs ) ) : ?>

    <div class="woocommerce-tabs wc-tabs-wrapper">
        <div class="tabs-header">
            <ul class="tabs wc-tabs" role="tablist">
                <?php foreach ( $product_tabs as $key => $product_tab ) : ?>
                    <li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
                        <a href="#tab-<?php echo esc_attr( $key ); ?>">
                            <?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div class="tabs-content">
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
    </div>

<?php else : ?>
    <div class="woocommerce-tabs-alternative">
        <?php
        // If no tabs are set, display description directly
        global $product;
        
        if ( $product && $product->get_description() ) {
            echo '<div class="product-description">';
            echo '<h2>' . esc_html__( 'Description', 'aqualuxe' ) . '</h2>';
            echo '<div class="woocommerce-product-details__short-description">';
            echo wp_kses_post( $product->get_description() );
            echo '</div>';
            echo '</div>';
        }
        
        // Display additional information if available
        $attributes = $product->get_attributes();
        if ( ! empty( $attributes ) ) {
            echo '<div class="product-attributes">';
            echo '<h2>' . esc_html__( 'Additional information', 'aqualuxe' ) . '</h2>';
            wc_display_product_attributes( $product );
            echo '</div>';
        }
        ?>
    </div>
<?php endif; ?>

<script type="text/javascript">
    (function($) {
        'use strict';
        
        // Tabs functionality
        $('.wc-tabs li a').on('click', function(e) {
            e.preventDefault();
            
            const target = $(this).attr('href');
            
            // Update active tab
            $('.wc-tabs li').removeClass('active');
            $(this).parent().addClass('active');
            
            // Show target panel
            $('.woocommerce-Tabs-panel').hide();
            $(target).show();
        });
        
        // Set first tab as active by default
        $('.wc-tabs li:first').addClass('active');
        $('.woocommerce-Tabs-panel:first').show();
        
        // Accordion functionality for mobile
        $('.wc-tabs li a').on('click', function(e) {
            if (window.innerWidth < 768) {
                const $parent = $(this).parent();
                
                if ($parent.hasClass('active')) {
                    $parent.removeClass('active');
                    $($(this).attr('href')).hide();
                } else {
                    $('.wc-tabs li').removeClass('active');
                    $parent.addClass('active');
                    $('.woocommerce-Tabs-panel').hide();
                    $($(this).attr('href')).show();
                }
                
                e.preventDefault();
            }
        });
    })(jQuery);
</script>