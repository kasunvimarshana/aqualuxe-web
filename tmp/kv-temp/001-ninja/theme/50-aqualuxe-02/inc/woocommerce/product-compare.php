<?php
/**
 * AquaLuxe Product Comparison Functions
 *
 * Functions for product comparison feature
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add product comparison options to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_product_compare_customizer_options( $wp_customize ) {
    // Add Product Comparison section
    $wp_customize->add_section( 'aqualuxe_product_compare', array(
        'title'    => __( 'Product Comparison', 'aqualuxe' ),
        'priority' => 100,
        'panel'    => 'woocommerce',
    ) );

    // Enable Product Comparison
    $wp_customize->add_setting( 'aqualuxe_product_compare_enable', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'aqualuxe_product_compare_enable', array(
        'label'    => __( 'Enable Product Comparison', 'aqualuxe' ),
        'section'  => 'aqualuxe_product_compare',
        'type'     => 'checkbox',
    ) );

    // Compare Button Position
    $wp_customize->add_setting( 'aqualuxe_product_compare_button_position', array(
        'default'           => 'after_add_to_cart',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ) );

    $wp_customize->add_control( 'aqualuxe_product_compare_button_position', array(
        'label'    => __( 'Compare Button Position', 'aqualuxe' ),
        'section'  => 'aqualuxe_product_compare',
        'type'     => 'select',
        'choices'  => array(
            'after_add_to_cart'  => __( 'After Add to Cart Button', 'aqualuxe' ),
            'before_add_to_cart' => __( 'Before Add to Cart Button', 'aqualuxe' ),
            'after_title'        => __( 'After Product Title', 'aqualuxe' ),
            'after_price'        => __( 'After Product Price', 'aqualuxe' ),
        ),
    ) );

    // Compare Button Text
    $wp_customize->add_setting( 'aqualuxe_product_compare_button_text', array(
        'default'           => __( 'Add to Compare', 'aqualuxe' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'aqualuxe_product_compare_button_text', array(
        'label'    => __( 'Compare Button Text', 'aqualuxe' ),
        'section'  => 'aqualuxe_product_compare',
        'type'     => 'text',
    ) );

    // Compare Page
    $wp_customize->add_setting( 'aqualuxe_product_compare_page', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'aqualuxe_product_compare_page', array(
        'label'       => __( 'Compare Page', 'aqualuxe' ),
        'description' => __( 'Select a page to display product comparison. The [product_comparison] shortcode will be added to this page.', 'aqualuxe' ),
        'section'     => 'aqualuxe_product_compare',
        'type'        => 'dropdown-pages',
    ) );

    // Maximum Products to Compare
    $wp_customize->add_setting( 'aqualuxe_product_compare_max_products', array(
        'default'           => 4,
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'aqualuxe_product_compare_max_products', array(
        'label'       => __( 'Maximum Products to Compare', 'aqualuxe' ),
        'description' => __( 'Maximum number of products that can be added to comparison.', 'aqualuxe' ),
        'section'     => 'aqualuxe_product_compare',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 2,
            'max'  => 10,
            'step' => 1,
        ),
    ) );

    // Compare Bar Position
    $wp_customize->add_setting( 'aqualuxe_product_compare_bar_position', array(
        'default'           => 'bottom',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ) );

    $wp_customize->add_control( 'aqualuxe_product_compare_bar_position', array(
        'label'    => __( 'Compare Bar Position', 'aqualuxe' ),
        'section'  => 'aqualuxe_product_compare',
        'type'     => 'select',
        'choices'  => array(
            'bottom' => __( 'Bottom of the Screen', 'aqualuxe' ),
            'top'    => __( 'Top of the Screen', 'aqualuxe' ),
        ),
    ) );

    // Compare Fields
    $wp_customize->add_setting( 'aqualuxe_product_compare_fields', array(
        'default'           => array( 'price', 'rating', 'description', 'sku', 'stock', 'weight', 'dimensions', 'attributes' ),
        'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
    ) );

    $wp_customize->add_control( new Aqualuxe_Customize_Multicheck_Control( $wp_customize, 'aqualuxe_product_compare_fields', array(
        'label'    => __( 'Compare Fields', 'aqualuxe' ),
        'section'  => 'aqualuxe_product_compare',
        'choices'  => array(
            'price'       => __( 'Price', 'aqualuxe' ),
            'rating'      => __( 'Rating', 'aqualuxe' ),
            'description' => __( 'Description', 'aqualuxe' ),
            'sku'         => __( 'SKU', 'aqualuxe' ),
            'stock'       => __( 'Stock Status', 'aqualuxe' ),
            'weight'      => __( 'Weight', 'aqualuxe' ),
            'dimensions'  => __( 'Dimensions', 'aqualuxe' ),
            'attributes'  => __( 'Attributes', 'aqualuxe' ),
        ),
    ) ) );
}
add_action( 'customize_register', 'aqualuxe_product_compare_customizer_options' );

/**
 * Sanitize checkbox.
 *
 * @param bool $input Input value.
 * @return bool Sanitized value.
 */
function aqualuxe_sanitize_checkbox( $input ) {
    return ( isset( $input ) && true == $input ) ? true : false;
}

/**
 * Sanitize select.
 *
 * @param string $input Input value.
 * @param WP_Customize_Setting $setting Setting object.
 * @return string Sanitized value.
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    $input = sanitize_key( $input );
    $choices = $setting->manager->get_control( $setting->id )->choices;
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize multi-select.
 *
 * @param array $input Input value.
 * @return array Sanitized value.
 */
function aqualuxe_sanitize_multi_select( $input ) {
    if ( ! is_array( $input ) ) {
        return array();
    }

    $valid_keys = array( 'price', 'rating', 'description', 'sku', 'stock', 'weight', 'dimensions', 'attributes' );
    $result = array();

    foreach ( $input as $key ) {
        if ( in_array( $key, $valid_keys ) ) {
            $result[] = $key;
        }
    }

    return $result;
}

/**
 * Multi-check Customizer Control.
 */
if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Aqualuxe_Customize_Multicheck_Control' ) ) {
    class Aqualuxe_Customize_Multicheck_Control extends WP_Customize_Control {
        /**
         * The type of customize control being rendered.
         *
         * @var string
         */
        public $type = 'multicheck';

        /**
         * Render the control's content.
         */
        public function render_content() {
            if ( empty( $this->choices ) ) {
                return;
            }

            if ( ! empty( $this->label ) ) {
                echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
            }

            if ( ! empty( $this->description ) ) {
                echo '<span class="description customize-control-description">' . esc_html( $this->description ) . '</span>';
            }

            $multi_values = ! is_array( $this->value() ) ? explode( ',', $this->value() ) : $this->value();
            ?>
            <ul>
                <?php foreach ( $this->choices as $value => $label ) : ?>
                    <li>
                        <label>
                            <input type="checkbox" value="<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $multi_values ) ); ?> />
                            <?php echo esc_html( $label ); ?>
                        </label>
                    </li>
                <?php endforeach; ?>
            </ul>
            <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( implode( ',', $multi_values ) ); ?>" />
            <script>
            jQuery(document).ready(function($) {
                "use strict";
                var control = $( '#<?php echo esc_js( $this->id ); ?>' );
                var checkboxes = control.find( 'input[type="checkbox"]' );
                var values = control.find( 'input[type="hidden"]' ).val().split( ',' );

                checkboxes.on( 'change', function() {
                    var values = [];
                    checkboxes.each( function() {
                        if ( $(this).is( ':checked' ) ) {
                            values.push( $(this).val() );
                        }
                    });
                    control.find( 'input[type="hidden"]' ).val( values.join( ',' ) ).trigger( 'change' );
                });
            });
            </script>
            <?php
        }
    }
}

/**
 * Add compare button to product.
 */
function aqualuxe_add_compare_button() {
    // Check if product comparison is enabled
    if ( ! get_theme_mod( 'aqualuxe_product_compare_enable', true ) ) {
        return;
    }

    global $product;
    if ( ! $product ) {
        return;
    }

    // Get button position
    $position = get_theme_mod( 'aqualuxe_product_compare_button_position', 'after_add_to_cart' );
    $hook = '';

    switch ( $position ) {
        case 'after_add_to_cart':
            $hook = 'woocommerce_after_add_to_cart_button';
            break;
        case 'before_add_to_cart':
            $hook = 'woocommerce_before_add_to_cart_button';
            break;
        case 'after_title':
            $hook = 'woocommerce_single_product_summary';
            $priority = 6; // After title (5)
            break;
        case 'after_price':
            $hook = 'woocommerce_single_product_summary';
            $priority = 11; // After price (10)
            break;
    }

    if ( ! empty( $hook ) ) {
        if ( isset( $priority ) ) {
            add_action( $hook, 'aqualuxe_compare_button_html', $priority );
        } else {
            add_action( $hook, 'aqualuxe_compare_button_html' );
        }
    }
}
add_action( 'template_redirect', 'aqualuxe_add_compare_button' );

/**
 * Add compare button to product loop.
 */
function aqualuxe_add_compare_button_to_loop() {
    // Check if product comparison is enabled
    if ( ! get_theme_mod( 'aqualuxe_product_compare_enable', true ) ) {
        return;
    }

    add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_compare_button_html', 15 );
}
add_action( 'template_redirect', 'aqualuxe_add_compare_button_to_loop' );

/**
 * Compare button HTML.
 */
function aqualuxe_compare_button_html() {
    global $product;
    if ( ! $product ) {
        return;
    }

    $button_text = get_theme_mod( 'aqualuxe_product_compare_button_text', __( 'Add to Compare', 'aqualuxe' ) );
    $product_id = $product->get_id();
    ?>
    <button type="button" class="aqualuxe-compare-button button" data-product-id="<?php echo esc_attr( $product_id ); ?>">
        <i class="fas fa-exchange-alt"></i> <?php echo esc_html( $button_text ); ?>
    </button>
    <?php
}

/**
 * Register product comparison shortcode.
 */
function aqualuxe_product_comparison_shortcode( $atts ) {
    // Check if product comparison is enabled
    if ( ! get_theme_mod( 'aqualuxe_product_compare_enable', true ) ) {
        return '';
    }

    $atts = shortcode_atts( array(
        'products' => '',
    ), $atts, 'product_comparison' );

    ob_start();

    // Get products from URL if not specified in shortcode
    $product_ids = array();
    if ( ! empty( $atts['products'] ) ) {
        $product_ids = explode( ',', $atts['products'] );
    } elseif ( isset( $_GET['compare'] ) ) {
        $product_ids = explode( ',', sanitize_text_field( $_GET['compare'] ) );
    }

    // Validate product IDs
    $valid_product_ids = array();
    foreach ( $product_ids as $product_id ) {
        $product_id = absint( $product_id );
        $product = wc_get_product( $product_id );
        if ( $product && $product->is_visible() ) {
            $valid_product_ids[] = $product_id;
        }
    }

    // Get compare fields
    $fields = get_theme_mod( 'aqualuxe_product_compare_fields', array( 'price', 'rating', 'description', 'sku', 'stock', 'weight', 'dimensions', 'attributes' ) );
    if ( ! is_array( $fields ) ) {
        $fields = explode( ',', $fields );
    }

    // Display comparison table
    if ( ! empty( $valid_product_ids ) ) {
        ?>
        <div class="aqualuxe-product-comparison">
            <table class="aqualuxe-compare-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Product', 'aqualuxe' ); ?></th>
                        <?php foreach ( $valid_product_ids as $product_id ) : ?>
                            <?php $product = wc_get_product( $product_id ); ?>
                            <th>
                                <div class="aqualuxe-compare-product-header">
                                    <?php if ( has_post_thumbnail( $product_id ) ) : ?>
                                        <div class="aqualuxe-compare-product-image">
                                            <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
                                                <?php echo $product->get_image( 'thumbnail' ); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="aqualuxe-compare-product-title">
                                        <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
                                            <?php echo esc_html( $product->get_name() ); ?>
                                        </a>
                                    </h3>
                                    <a href="#" class="aqualuxe-compare-remove" data-product-id="<?php echo esc_attr( $product_id ); ?>">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( in_array( 'price', $fields ) ) : ?>
                        <tr>
                            <td class="aqualuxe-compare-label"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></td>
                            <?php foreach ( $valid_product_ids as $product_id ) : ?>
                                <?php $product = wc_get_product( $product_id ); ?>
                                <td class="aqualuxe-compare-value"><?php echo $product->get_price_html(); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endif; ?>

                    <?php if ( in_array( 'rating', $fields ) ) : ?>
                        <tr>
                            <td class="aqualuxe-compare-label"><?php esc_html_e( 'Rating', 'aqualuxe' ); ?></td>
                            <?php foreach ( $valid_product_ids as $product_id ) : ?>
                                <?php $product = wc_get_product( $product_id ); ?>
                                <td class="aqualuxe-compare-value">
                                    <?php if ( $product->get_rating_count() > 0 ) : ?>
                                        <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                                        <span class="aqualuxe-compare-rating-count">
                                            <?php printf( _n( '(%s review)', '(%s reviews)', $product->get_rating_count(), 'aqualuxe' ), $product->get_rating_count() ); ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="aqualuxe-compare-no-rating"><?php esc_html_e( 'No reviews', 'aqualuxe' ); ?></span>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endif; ?>

                    <?php if ( in_array( 'description', $fields ) ) : ?>
                        <tr>
                            <td class="aqualuxe-compare-label"><?php esc_html_e( 'Description', 'aqualuxe' ); ?></td>
                            <?php foreach ( $valid_product_ids as $product_id ) : ?>
                                <?php $product = wc_get_product( $product_id ); ?>
                                <td class="aqualuxe-compare-value">
                                    <?php echo wp_kses_post( $product->get_short_description() ); ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endif; ?>

                    <?php if ( in_array( 'sku', $fields ) ) : ?>
                        <tr>
                            <td class="aqualuxe-compare-label"><?php esc_html_e( 'SKU', 'aqualuxe' ); ?></td>
                            <?php foreach ( $valid_product_ids as $product_id ) : ?>
                                <?php $product = wc_get_product( $product_id ); ?>
                                <td class="aqualuxe-compare-value">
                                    <?php echo $product->get_sku() ? esc_html( $product->get_sku() ) : '<span class="aqualuxe-compare-na">' . esc_html__( 'N/A', 'aqualuxe' ) . '</span>'; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endif; ?>

                    <?php if ( in_array( 'stock', $fields ) ) : ?>
                        <tr>
                            <td class="aqualuxe-compare-label"><?php esc_html_e( 'Stock Status', 'aqualuxe' ); ?></td>
                            <?php foreach ( $valid_product_ids as $product_id ) : ?>
                                <?php $product = wc_get_product( $product_id ); ?>
                                <td class="aqualuxe-compare-value">
                                    <?php echo $product->is_in_stock() ? '<span class="aqualuxe-compare-instock">' . esc_html__( 'In Stock', 'aqualuxe' ) . '</span>' : '<span class="aqualuxe-compare-outofstock">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</span>'; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endif; ?>

                    <?php if ( in_array( 'weight', $fields ) ) : ?>
                        <tr>
                            <td class="aqualuxe-compare-label"><?php esc_html_e( 'Weight', 'aqualuxe' ); ?></td>
                            <?php foreach ( $valid_product_ids as $product_id ) : ?>
                                <?php $product = wc_get_product( $product_id ); ?>
                                <td class="aqualuxe-compare-value">
                                    <?php echo $product->get_weight() ? esc_html( $product->get_weight() ) . ' ' . esc_html( get_option( 'woocommerce_weight_unit' ) ) : '<span class="aqualuxe-compare-na">' . esc_html__( 'N/A', 'aqualuxe' ) . '</span>'; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endif; ?>

                    <?php if ( in_array( 'dimensions', $fields ) ) : ?>
                        <tr>
                            <td class="aqualuxe-compare-label"><?php esc_html_e( 'Dimensions', 'aqualuxe' ); ?></td>
                            <?php foreach ( $valid_product_ids as $product_id ) : ?>
                                <?php $product = wc_get_product( $product_id ); ?>
                                <td class="aqualuxe-compare-value">
                                    <?php
                                    $dimensions = array(
                                        'length' => $product->get_length(),
                                        'width'  => $product->get_width(),
                                        'height' => $product->get_height(),
                                    );

                                    if ( array_filter( $dimensions ) ) {
                                        echo esc_html( wc_format_dimensions( $dimensions ) );
                                    } else {
                                        echo '<span class="aqualuxe-compare-na">' . esc_html__( 'N/A', 'aqualuxe' ) . '</span>';
                                    }
                                    ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endif; ?>

                    <?php if ( in_array( 'attributes', $fields ) ) : ?>
                        <?php
                        // Get all attributes from all products
                        $all_attributes = array();
                        foreach ( $valid_product_ids as $product_id ) {
                            $product = wc_get_product( $product_id );
                            $attributes = $product->get_attributes();
                            
                            foreach ( $attributes as $attribute ) {
                                if ( $attribute->is_taxonomy() ) {
                                    $attribute_taxonomy = $attribute->get_taxonomy_object();
                                    $attribute_name = $attribute_taxonomy->attribute_label;
                                } else {
                                    $attribute_name = $attribute->get_name();
                                }
                                
                                if ( ! isset( $all_attributes[ $attribute_name ] ) ) {
                                    $all_attributes[ $attribute_name ] = $attribute;
                                }
                            }
                        }

                        // Display attributes
                        foreach ( $all_attributes as $attribute_name => $attribute ) :
                        ?>
                            <tr>
                                <td class="aqualuxe-compare-label"><?php echo esc_html( $attribute_name ); ?></td>
                                <?php foreach ( $valid_product_ids as $product_id ) : ?>
                                    <?php
                                    $product = wc_get_product( $product_id );
                                    $attributes = $product->get_attributes();
                                    $attribute_value = '';
                                    
                                    foreach ( $attributes as $prod_attribute ) {
                                        if ( $prod_attribute->is_taxonomy() ) {
                                            $attribute_taxonomy = $prod_attribute->get_taxonomy_object();
                                            $current_attribute_name = $attribute_taxonomy->attribute_label;
                                        } else {
                                            $current_attribute_name = $prod_attribute->get_name();
                                        }
                                        
                                        if ( $current_attribute_name === $attribute_name ) {
                                            if ( $prod_attribute->is_taxonomy() ) {
                                                $values = wc_get_product_terms( $product_id, $prod_attribute->get_name(), array( 'fields' => 'names' ) );
                                                $attribute_value = apply_filters( 'woocommerce_attribute', wptexturize( implode( ', ', $values ) ), $prod_attribute, $values );
                                            } else {
                                                $attribute_value = apply_filters( 'woocommerce_attribute', wptexturize( $prod_attribute->get_options() ), $prod_attribute, $prod_attribute->get_options() );
                                            }
                                            break;
                                        }
                                    }
                                    ?>
                                    <td class="aqualuxe-compare-value">
                                        <?php echo $attribute_value ? wp_kses_post( $attribute_value ) : '<span class="aqualuxe-compare-na">' . esc_html__( 'N/A', 'aqualuxe' ) . '</span>'; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <tr>
                        <td class="aqualuxe-compare-label"><?php esc_html_e( 'Actions', 'aqualuxe' ); ?></td>
                        <?php foreach ( $valid_product_ids as $product_id ) : ?>
                            <?php $product = wc_get_product( $product_id ); ?>
                            <td class="aqualuxe-compare-value aqualuxe-compare-actions">
                                <?php if ( $product->is_in_stock() ) : ?>
                                    <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="button add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>">
                                        <?php echo esc_html( $product->add_to_cart_text() ); ?>
                                    </a>
                                <?php else : ?>
                                    <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="button">
                                        <?php esc_html_e( 'View Product', 'aqualuxe' ); ?>
                                    </a>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
    } else {
        ?>
        <div class="aqualuxe-product-comparison-empty">
            <p><?php esc_html_e( 'No products added to comparison.', 'aqualuxe' ); ?></p>
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="button">
                <?php esc_html_e( 'Browse Products', 'aqualuxe' ); ?>
            </a>
        </div>
        <?php
    }

    return ob_get_clean();
}
add_shortcode( 'product_comparison', 'aqualuxe_product_comparison_shortcode' );

/**
 * Add compare bar to footer.
 */
function aqualuxe_add_compare_bar() {
    // Check if product comparison is enabled
    if ( ! get_theme_mod( 'aqualuxe_product_compare_enable', true ) ) {
        return;
    }

    // Get compare page
    $compare_page_id = get_theme_mod( 'aqualuxe_product_compare_page', 0 );
    if ( ! $compare_page_id ) {
        return;
    }

    $compare_page_url = get_permalink( $compare_page_id );
    $max_products = get_theme_mod( 'aqualuxe_product_compare_max_products', 4 );
    $bar_position = get_theme_mod( 'aqualuxe_product_compare_bar_position', 'bottom' );
    ?>
    <div id="aqualuxe-compare-bar" class="aqualuxe-compare-bar aqualuxe-compare-bar-<?php echo esc_attr( $bar_position ); ?>" style="display: none;">
        <div class="aqualuxe-compare-bar-inner">
            <div class="aqualuxe-compare-bar-title">
                <?php esc_html_e( 'Compare Products', 'aqualuxe' ); ?>
                <span class="aqualuxe-compare-count">0</span>
            </div>
            <div class="aqualuxe-compare-bar-items"></div>
            <div class="aqualuxe-compare-bar-actions">
                <a href="<?php echo esc_url( $compare_page_url ); ?>" class="aqualuxe-compare-view-button button">
                    <?php esc_html_e( 'Compare Now', 'aqualuxe' ); ?>
                </a>
                <button type="button" class="aqualuxe-compare-clear-button">
                    <?php esc_html_e( 'Clear All', 'aqualuxe' ); ?>
                </button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var compareBar = document.getElementById('aqualuxe-compare-bar');
        var compareBarItems = document.querySelector('.aqualuxe-compare-bar-items');
        var compareCount = document.querySelector('.aqualuxe-compare-count');
        var compareViewButton = document.querySelector('.aqualuxe-compare-view-button');
        var compareClearButton = document.querySelector('.aqualuxe-compare-clear-button');
        var compareButtons = document.querySelectorAll('.aqualuxe-compare-button');
        var compareRemoveButtons = document.querySelectorAll('.aqualuxe-compare-remove');
        var maxProducts = <?php echo esc_js( $max_products ); ?>;
        var comparePageUrl = '<?php echo esc_js( $compare_page_url ); ?>';
        var products = [];

        // Load products from localStorage
        if (localStorage.getItem('aqualuxe_compare_products')) {
            products = JSON.parse(localStorage.getItem('aqualuxe_compare_products'));
            updateCompareBar();
        }

        // Add product to comparison
        document.body.addEventListener('click', function(e) {
            if (e.target.classList.contains('aqualuxe-compare-button') || e.target.closest('.aqualuxe-compare-button')) {
                var button = e.target.classList.contains('aqualuxe-compare-button') ? e.target : e.target.closest('.aqualuxe-compare-button');
                var productId = button.getAttribute('data-product-id');
                
                if (products.indexOf(productId) === -1) {
                    if (products.length >= maxProducts) {
                        alert('<?php echo esc_js( sprintf( __( 'You can compare up to %d products.', 'aqualuxe' ), $max_products ) ); ?>');
                        return;
                    }
                    
                    products.push(productId);
                    localStorage.setItem('aqualuxe_compare_products', JSON.stringify(products));
                    updateCompareBar();
                    
                    button.classList.add('added');
                    button.innerHTML = '<i class="fas fa-check"></i> <?php echo esc_js( __( 'Added to Compare', 'aqualuxe' ) ); ?>';
                    
                    setTimeout(function() {
                        button.classList.remove('added');
                        button.innerHTML = '<i class="fas fa-exchange-alt"></i> <?php echo esc_js( get_theme_mod( 'aqualuxe_product_compare_button_text', __( 'Add to Compare', 'aqualuxe' ) ) ); ?>';
                    }, 2000);
                } else {
                    // Remove product if already in comparison
                    var index = products.indexOf(productId);
                    if (index !== -1) {
                        products.splice(index, 1);
                        localStorage.setItem('aqualuxe_compare_products', JSON.stringify(products));
                        updateCompareBar();
                        
                        button.classList.remove('added');
                        button.innerHTML = '<i class="fas fa-exchange-alt"></i> <?php echo esc_js( get_theme_mod( 'aqualuxe_product_compare_button_text', __( 'Add to Compare', 'aqualuxe' ) ) ); ?>';
                    }
                }
            }
        });

        // Remove product from comparison
        document.body.addEventListener('click', function(e) {
            if (e.target.classList.contains('aqualuxe-compare-remove') || e.target.closest('.aqualuxe-compare-remove')) {
                var button = e.target.classList.contains('aqualuxe-compare-remove') ? e.target : e.target.closest('.aqualuxe-compare-remove');
                var productId = button.getAttribute('data-product-id');
                
                var index = products.indexOf(productId);
                if (index !== -1) {
                    products.splice(index, 1);
                    localStorage.setItem('aqualuxe_compare_products', JSON.stringify(products));
                    
                    // If on compare page, reload the page
                    if (window.location.href.indexOf('compare=') !== -1) {
                        window.location.href = comparePageUrl + '?compare=' + products.join(',');
                    } else {
                        updateCompareBar();
                    }
                }
            }
        });

        // Clear all products
        compareClearButton.addEventListener('click', function() {
            products = [];
            localStorage.setItem('aqualuxe_compare_products', JSON.stringify(products));
            updateCompareBar();
        });

        // Update compare bar
        function updateCompareBar() {
            if (products.length > 0) {
                compareBar.style.display = 'block';
                compareCount.textContent = products.length;
                compareViewButton.href = comparePageUrl + '?compare=' + products.join(',');
                
                // Update compare bar items
                compareBarItems.innerHTML = '';
                
                // Fetch product data
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        
                        if (response.success) {
                            var productData = response.data;
                            
                            for (var i = 0; i < productData.length; i++) {
                                var product = productData[i];
                                var item = document.createElement('div');
                                item.className = 'aqualuxe-compare-bar-item';
                                item.innerHTML = '<div class="aqualuxe-compare-bar-item-image">' + product.image + '</div>' +
                                                '<div class="aqualuxe-compare-bar-item-title">' + product.title + '</div>' +
                                                '<button type="button" class="aqualuxe-compare-bar-item-remove" data-product-id="' + product.id + '">&times;</button>';
                                compareBarItems.appendChild(item);
                            }
                            
                            // Add remove event listeners
                            var removeButtons = document.querySelectorAll('.aqualuxe-compare-bar-item-remove');
                            for (var i = 0; i < removeButtons.length; i++) {
                                removeButtons[i].addEventListener('click', function() {
                                    var productId = this.getAttribute('data-product-id');
                                    var index = products.indexOf(productId);
                                    if (index !== -1) {
                                        products.splice(index, 1);
                                        localStorage.setItem('aqualuxe_compare_products', JSON.stringify(products));
                                        updateCompareBar();
                                    }
                                });
                            }
                        }
                    }
                };
                xhr.send('action=aqualuxe_get_compare_products&products=' + products.join(',') + '&nonce=<?php echo wp_create_nonce( 'aqualuxe_compare_nonce' ); ?>');
            } else {
                compareBar.style.display = 'none';
            }
        }
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'aqualuxe_add_compare_bar' );

/**
 * AJAX handler for getting product data.
 */
function aqualuxe_get_compare_products_ajax() {
    // Check nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_compare_nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed.', 'aqualuxe' ) ) );
    }

    // Check products
    if ( ! isset( $_POST['products'] ) || empty( $_POST['products'] ) ) {
        wp_send_json_error( array( 'message' => __( 'No products specified.', 'aqualuxe' ) ) );
    }

    $product_ids = explode( ',', sanitize_text_field( $_POST['products'] ) );
    $product_data = array();

    foreach ( $product_ids as $product_id ) {
        $product = wc_get_product( absint( $product_id ) );
        if ( $product && $product->is_visible() ) {
            $product_data[] = array(
                'id'    => $product_id,
                'title' => $product->get_name(),
                'image' => $product->get_image( 'thumbnail' ),
                'url'   => get_permalink( $product_id ),
            );
        }
    }

    wp_send_json_success( $product_data );
}
add_action( 'wp_ajax_aqualuxe_get_compare_products', 'aqualuxe_get_compare_products_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_get_compare_products', 'aqualuxe_get_compare_products_ajax' );

/**
 * Add compare page to theme setup.
 */
function aqualuxe_create_compare_page() {
    // Check if compare page is already set
    $compare_page_id = get_theme_mod( 'aqualuxe_product_compare_page', 0 );
    if ( $compare_page_id && get_post( $compare_page_id ) ) {
        return;
    }

    // Create compare page
    $page_id = wp_insert_post( array(
        'post_title'     => __( 'Product Comparison', 'aqualuxe' ),
        'post_content'   => '<!-- wp:shortcode -->[product_comparison]<!-- /wp:shortcode -->',
        'post_status'    => 'publish',
        'post_type'      => 'page',
        'comment_status' => 'closed',
    ) );

    if ( $page_id ) {
        // Set compare page in theme mods
        set_theme_mod( 'aqualuxe_product_compare_page', $page_id );
    }
}
add_action( 'after_switch_theme', 'aqualuxe_create_compare_page' );

/**
 * Add compare styles.
 */
function aqualuxe_add_compare_styles() {
    // Check if product comparison is enabled
    if ( ! get_theme_mod( 'aqualuxe_product_compare_enable', true ) ) {
        return;
    }

    // Get bar position
    $bar_position = get_theme_mod( 'aqualuxe_product_compare_bar_position', 'bottom' );
    ?>
    <style>
        /* Compare Button */
        .aqualuxe-compare-button {
            margin-top: 10px;
            background-color: #f5f5f5;
            color: #333;
            border: 1px solid #ddd;
        }

        .aqualuxe-compare-button:hover {
            background-color: #e9e9e9;
        }

        .aqualuxe-compare-button.added {
            background-color: #4CAF50;
            color: #fff;
            border-color: #4CAF50;
        }

        /* Compare Bar */
        .aqualuxe-compare-bar {
            position: fixed;
            left: 0;
            right: 0;
            z-index: 999;
            background-color: #fff;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
            display: flex;
            align-items: center;
        }

        .aqualuxe-compare-bar-bottom {
            bottom: 0;
        }

        .aqualuxe-compare-bar-top {
            top: 0;
        }

        .aqualuxe-compare-bar-inner {
            display: flex;
            align-items: center;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .aqualuxe-compare-bar-title {
            font-weight: bold;
            margin-right: 20px;
            white-space: nowrap;
        }

        .aqualuxe-compare-count {
            display: inline-block;
            background-color: #0073aa;
            color: #fff;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            text-align: center;
            line-height: 20px;
            font-size: 12px;
            margin-left: 5px;
        }

        .aqualuxe-compare-bar-items {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            flex: 1;
            margin: 0 20px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }

        .aqualuxe-compare-bar-items::-webkit-scrollbar {
            height: 6px;
        }

        .aqualuxe-compare-bar-items::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .aqualuxe-compare-bar-items::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        .aqualuxe-compare-bar-item {
            flex: 0 0 auto;
            width: 100px;
            margin-right: 10px;
            position: relative;
            border: 1px solid #eee;
            border-radius: 4px;
            padding: 5px;
            background-color: #f9f9f9;
        }

        .aqualuxe-compare-bar-item-image {
            width: 100%;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .aqualuxe-compare-bar-item-image img {
            max-width: 100%;
            max-height: 100%;
        }

        .aqualuxe-compare-bar-item-title {
            font-size: 12px;
            text-align: center;
            margin-top: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .aqualuxe-compare-bar-item-remove {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #ff5252;
            color: #fff;
            border: none;
            font-size: 14px;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .aqualuxe-compare-bar-actions {
            display: flex;
            align-items: center;
            white-space: nowrap;
        }

        .aqualuxe-compare-view-button {
            margin-right: 10px;
        }

        .aqualuxe-compare-clear-button {
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            text-decoration: underline;
            padding: 0;
        }

        .aqualuxe-compare-clear-button:hover {
            color: #333;
        }

        /* Compare Table */
        .aqualuxe-product-comparison {
            margin: 30px 0;
        }

        .aqualuxe-compare-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #eee;
        }

        .aqualuxe-compare-table th,
        .aqualuxe-compare-table td {
            padding: 15px;
            border: 1px solid #eee;
            text-align: center;
            vertical-align: middle;
        }

        .aqualuxe-compare-table th {
            background-color: #f9f9f9;
        }

        .aqualuxe-compare-label {
            font-weight: bold;
            text-align: left !important;
            background-color: #f9f9f9;
        }

        .aqualuxe-compare-product-header {
            position: relative;
            padding-bottom: 10px;
        }

        .aqualuxe-compare-product-image {
            margin-bottom: 10px;
        }

        .aqualuxe-compare-product-title {
            font-size: 16px;
            margin: 0;
        }

        .aqualuxe-compare-remove {
            position: absolute;
            top: 0;
            right: 0;
            color: #999;
            text-decoration: none;
        }

        .aqualuxe-compare-remove:hover {
            color: #ff5252;
        }

        .aqualuxe-compare-na {
            color: #999;
            font-style: italic;
        }

        .aqualuxe-compare-instock {
            color: #4CAF50;
            font-weight: bold;
        }

        .aqualuxe-compare-outofstock {
            color: #ff5252;
            font-weight: bold;
        }

        .aqualuxe-compare-actions {
            padding-top: 20px;
        }

        .aqualuxe-product-comparison-empty {
            text-align: center;
            padding: 50px 0;
            background-color: #f9f9f9;
            border: 1px solid #eee;
            border-radius: 4px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .aqualuxe-compare-bar-inner {
                flex-wrap: wrap;
            }

            .aqualuxe-compare-bar-title {
                width: 100%;
                margin-bottom: 10px;
            }

            .aqualuxe-compare-bar-actions {
                width: 100%;
                margin-top: 10px;
                justify-content: space-between;
            }

            .aqualuxe-compare-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }

        /* Dark Mode */
        .dark-mode .aqualuxe-compare-button {
            background-color: #333;
            color: #fff;
            border-color: #444;
        }

        .dark-mode .aqualuxe-compare-button:hover {
            background-color: #444;
        }

        .dark-mode .aqualuxe-compare-bar {
            background-color: #222;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.3);
        }

        .dark-mode .aqualuxe-compare-bar-item {
            border-color: #444;
            background-color: #333;
        }

        .dark-mode .aqualuxe-compare-clear-button {
            color: #aaa;
        }

        .dark-mode .aqualuxe-compare-clear-button:hover {
            color: #fff;
        }

        .dark-mode .aqualuxe-compare-table {
            border-color: #444;
        }

        .dark-mode .aqualuxe-compare-table th,
        .dark-mode .aqualuxe-compare-table td {
            border-color: #444;
        }

        .dark-mode .aqualuxe-compare-table th,
        .dark-mode .aqualuxe-compare-label {
            background-color: #333;
        }

        .dark-mode .aqualuxe-product-comparison-empty {
            background-color: #333;
            border-color: #444;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'aqualuxe_add_compare_styles' );