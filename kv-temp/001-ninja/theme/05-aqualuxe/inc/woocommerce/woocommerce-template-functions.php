<?php
/**
 * AquaLuxe WooCommerce Template Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Display product categories
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_categories' ) ) {
    function aqualuxe_woocommerce_product_categories() {
        global $product;
        
        if ( ! $product ) {
            return;
        }
        
        $categories = wc_get_product_category_list( $product->get_id(), ', ', '<div class="product-categories">', '</div>' );
        
        if ( $categories ) {
            echo wp_kses_post( $categories );
        }
    }
}

/**
 * Display product tags
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_tags' ) ) {
    function aqualuxe_woocommerce_product_tags() {
        global $product;
        
        if ( ! $product ) {
            return;
        }
        
        $tags = wc_get_product_tag_list( $product->get_id(), ', ', '<div class="product-tags">', '</div>' );
        
        if ( $tags ) {
            echo wp_kses_post( $tags );
        }
    }
}

/**
 * Display product rating
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_rating' ) ) {
    function aqualuxe_woocommerce_product_rating() {
        global $product;
        
        if ( ! $product || ! wc_review_ratings_enabled() ) {
            return;
        }
        
        $rating_count = $product->get_rating_count();
        $review_count = $product->get_review_count();
        $average      = $product->get_average_rating();
        
        if ( $rating_count > 0 ) {
            ?>
            <div class="product-rating">
                <?php echo wc_get_rating_html( $average, $rating_count ); ?>
                <?php if ( $review_count > 0 && comments_open() ) : ?>
                    <a href="#reviews" class="woocommerce-review-link" rel="nofollow">
                        (<?php printf( _n( '%s review', '%s reviews', $review_count, 'aqualuxe' ), esc_html( $review_count ) ); ?>)
                    </a>
                <?php endif; ?>
            </div>
            <?php
        } else {
            ?>
            <div class="product-rating">
                <div class="star-rating">
                    <span style="width:0%"></span>
                </div>
                <?php if ( comments_open() ) : ?>
                    <a href="#reviews" class="woocommerce-review-link" rel="nofollow">
                        (<?php esc_html_e( 'No reviews yet', 'aqualuxe' ); ?>)
                    </a>
                <?php endif; ?>
            </div>
            <?php
        }
    }
}

/**
 * Display product price
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_price' ) ) {
    function aqualuxe_woocommerce_product_price() {
        global $product;
        
        if ( ! $product ) {
            return;
        }
        
        ?>
        <div class="product-price">
            <?php echo wp_kses_post( $product->get_price_html() ); ?>
        </div>
        <?php
    }
}

/**
 * Display product stock status
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_stock' ) ) {
    function aqualuxe_woocommerce_product_stock() {
        global $product;
        
        if ( ! $product ) {
            return;
        }
        
        $availability = $product->get_availability();
        $stock_status = $availability['class'] ?? '';
        $stock_label  = $availability['availability'] ?? '';
        
        if ( $stock_label ) {
            ?>
            <div class="product-stock <?php echo esc_attr( $stock_status ); ?>">
                <?php echo wp_kses_post( $stock_label ); ?>
            </div>
            <?php
        }
    }
}

/**
 * Display product short description
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_short_description' ) ) {
    function aqualuxe_woocommerce_product_short_description() {
        global $product;
        
        if ( ! $product ) {
            return;
        }
        
        $short_description = apply_filters( 'woocommerce_short_description', $product->get_short_description() );
        
        if ( $short_description ) {
            ?>
            <div class="product-short-description">
                <?php echo wp_kses_post( $short_description ); ?>
            </div>
            <?php
        }
    }
}

/**
 * Display product meta
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_meta' ) ) {
    function aqualuxe_woocommerce_product_meta() {
        global $product;
        
        if ( ! $product ) {
            return;
        }
        
        ?>
        <div class="product-meta">
            <?php do_action( 'woocommerce_product_meta_start' ); ?>
            
            <?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
                <span class="sku_wrapper">
                    <?php esc_html_e( 'SKU:', 'aqualuxe' ); ?>
                    <span class="sku">
                        <?php echo ( $sku = $product->get_sku() ) ? esc_html( $sku ) : esc_html__( 'N/A', 'aqualuxe' ); ?>
                    </span>
                </span>
            <?php endif; ?>
            
            <?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'aqualuxe' ) . ' ', '</span>' ); ?>
            
            <?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'aqualuxe' ) . ' ', '</span>' ); ?>
            
            <?php do_action( 'woocommerce_product_meta_end' ); ?>
        </div>
        <?php
    }
}

/**
 * Display product sharing
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_sharing' ) ) {
    function aqualuxe_woocommerce_product_sharing() {
        global $product;
        
        if ( ! $product ) {
            return;
        }
        
        if ( ! get_theme_mod( 'aqualuxe_product_sharing', true ) ) {
            return;
        }
        
        $product_url  = get_permalink();
        $product_title = get_the_title();
        
        ?>
        <div class="product-sharing">
            <h4><?php esc_html_e( 'Share this product', 'aqualuxe' ); ?></h4>
            <ul class="social-sharing">
                <li>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( $product_url ); ?>" target="_blank" rel="noopener noreferrer">
                        <i class="fa fa-facebook"></i>
                        <span class="screen-reader-text"><?php esc_html_e( 'Share on Facebook', 'aqualuxe' ); ?></span>
                    </a>
                </li>
                <li>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo esc_url( $product_url ); ?>&text=<?php echo esc_attr( $product_title ); ?>" target="_blank" rel="noopener noreferrer">
                        <i class="fa fa-twitter"></i>
                        <span class="screen-reader-text"><?php esc_html_e( 'Share on Twitter', 'aqualuxe' ); ?></span>
                    </a>
                </li>
                <li>
                    <a href="https://pinterest.com/pin/create/button/?url=<?php echo esc_url( $product_url ); ?>&media=<?php echo esc_url( get_the_post_thumbnail_url( $product->get_id(), 'full' ) ); ?>&description=<?php echo esc_attr( $product_title ); ?>" target="_blank" rel="noopener noreferrer">
                        <i class="fa fa-pinterest"></i>
                        <span class="screen-reader-text"><?php esc_html_e( 'Share on Pinterest', 'aqualuxe' ); ?></span>
                    </a>
                </li>
                <li>
                    <a href="mailto:?subject=<?php echo esc_attr( $product_title ); ?>&body=<?php echo esc_url( $product_url ); ?>">
                        <i class="fa fa-envelope"></i>
                        <span class="screen-reader-text"><?php esc_html_e( 'Share via Email', 'aqualuxe' ); ?></span>
                    </a>
                </li>
            </ul>
        </div>
        <?php
    }
}

/**
 * Display product additional information
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_additional_information' ) ) {
    function aqualuxe_woocommerce_product_additional_information() {
        global $product;
        
        if ( ! $product ) {
            return;
        }
        
        $attributes = $product->get_attributes();
        
        if ( ! $attributes ) {
            return;
        }
        
        ?>
        <div class="product-additional-information">
            <h4><?php esc_html_e( 'Additional Information', 'aqualuxe' ); ?></h4>
            <table class="shop_attributes">
                <?php foreach ( $attributes as $attribute ) : ?>
                    <tr>
                        <th><?php echo wc_attribute_label( $attribute->get_name() ); ?></th>
                        <td><?php
                            if ( $attribute->is_taxonomy() ) {
                                $values = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
                                echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );
                            } else {
                                $values = $attribute->get_options();
                                echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );
                            }
                        ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php
    }
}

/**
 * Display product specifications
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_specifications' ) ) {
    function aqualuxe_woocommerce_product_specifications() {
        global $product;
        
        if ( ! $product ) {
            return;
        }
        
        $specifications = get_post_meta( $product->get_id(), '_aqualuxe_product_specifications', true );
        
        if ( ! $specifications ) {
            return;
        }
        
        ?>
        <div class="product-specifications">
            <h4><?php esc_html_e( 'Specifications', 'aqualuxe' ); ?></h4>
            <?php echo wp_kses_post( wpautop( $specifications ) ); ?>
        </div>
        <?php
    }
}

/**
 * Display product features
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_features' ) ) {
    function aqualuxe_woocommerce_product_features() {
        global $product;
        
        if ( ! $product ) {
            return;
        }
        
        $features = get_post_meta( $product->get_id(), '_aqualuxe_product_features', true );
        
        if ( ! $features ) {
            return;
        }
        
        ?>
        <div class="product-features">
            <h4><?php esc_html_e( 'Key Features', 'aqualuxe' ); ?></h4>
            <?php echo wp_kses_post( wpautop( $features ) ); ?>
        </div>
        <?php
    }
}

/**
 * Display product dimensions
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_dimensions' ) ) {
    function aqualuxe_woocommerce_product_dimensions() {
        global $product;
        
        if ( ! $product || ! $product->has_dimensions() ) {
            return;
        }
        
        ?>
        <div class="product-dimensions">
            <h4><?php esc_html_e( 'Dimensions', 'aqualuxe' ); ?></h4>
            <p>
                <?php echo esc_html( wc_format_dimensions( $product->get_dimensions( false ) ) ); ?>
            </p>
        </div>
        <?php
    }
}

/**
 * Display product weight
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_weight' ) ) {
    function aqualuxe_woocommerce_product_weight() {
        global $product;
        
        if ( ! $product || ! $product->has_weight() ) {
            return;
        }
        
        ?>
        <div class="product-weight">
            <h4><?php esc_html_e( 'Weight', 'aqualuxe' ); ?></h4>
            <p>
                <?php echo esc_html( wc_format_weight( $product->get_weight() ) ); ?>
            </p>
        </div>
        <?php
    }
}

/**
 * Display product shipping information
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_shipping_info' ) ) {
    function aqualuxe_woocommerce_product_shipping_info() {
        global $product;
        
        if ( ! $product ) {
            return;
        }
        
        $shipping_info = get_post_meta( $product->get_id(), '_aqualuxe_shipping_info', true );
        
        if ( ! $shipping_info ) {
            $shipping_info = get_theme_mod( 'aqualuxe_default_shipping_info', '' );
        }
        
        if ( ! $shipping_info ) {
            return;
        }
        
        ?>
        <div class="product-shipping-info">
            <h4><?php esc_html_e( 'Shipping Information', 'aqualuxe' ); ?></h4>
            <?php echo wp_kses_post( wpautop( $shipping_info ) ); ?>
        </div>
        <?php
    }
}

/**
 * Display product care instructions
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_care_instructions' ) ) {
    function aqualuxe_woocommerce_product_care_instructions() {
        global $product;
        
        if ( ! $product ) {
            return;
        }
        
        $care_instructions = get_post_meta( $product->get_id(), '_aqualuxe_care_instructions', true );
        
        if ( ! $care_instructions ) {
            $care_instructions = get_theme_mod( 'aqualuxe_default_care_instructions', '' );
        }
        
        if ( ! $care_instructions ) {
            return;
        }
        
        ?>
        <div class="product-care-instructions">
            <h4><?php esc_html_e( 'Care Instructions', 'aqualuxe' ); ?></h4>
            <?php echo wp_kses_post( wpautop( $care_instructions ) ); ?>
        </div>
        <?php
    }
}

/**
 * Display product guarantee
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_guarantee' ) ) {
    function aqualuxe_woocommerce_product_guarantee() {
        global $product;
        
        if ( ! $product ) {
            return;
        }
        
        $guarantee = get_post_meta( $product->get_id(), '_aqualuxe_product_guarantee', true );
        
        if ( ! $guarantee ) {
            $guarantee = get_theme_mod( 'aqualuxe_default_guarantee', '' );
        }
        
        if ( ! $guarantee ) {
            return;
        }
        
        ?>
        <div class="product-guarantee">
            <h4><?php esc_html_e( 'Guarantee', 'aqualuxe' ); ?></h4>
            <?php echo wp_kses_post( wpautop( $guarantee ) ); ?>
        </div>
        <?php
    }
}

/**
 * Display product filter
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_filter' ) ) {
    function aqualuxe_woocommerce_product_filter() {
        if ( ! get_theme_mod( 'aqualuxe_product_filter', true ) ) {
            return;
        }
        
        ?>
        <div class="aqualuxe-product-filter">
            <div class="filter-toggle">
                <button class="filter-toggle-button">
                    <i class="fa fa-filter"></i>
                    <?php esc_html_e( 'Filter', 'aqualuxe' ); ?>
                </button>
            </div>
            
            <div class="filter-content">
                <div class="filter-header">
                    <h3><?php esc_html_e( 'Filter Products', 'aqualuxe' ); ?></h3>
                    <button class="filter-close">
                        <i class="fa fa-times"></i>
                        <span class="screen-reader-text"><?php esc_html_e( 'Close', 'aqualuxe' ); ?></span>
                    </button>
                </div>
                
                <div class="filter-body">
                    <?php
                    // Categories
                    $product_categories = get_terms( array(
                        'taxonomy'   => 'product_cat',
                        'hide_empty' => true,
                        'parent'     => 0,
                    ) );
                    
                    if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
                        ?>
                        <div class="filter-section filter-categories">
                            <h4><?php esc_html_e( 'Categories', 'aqualuxe' ); ?></h4>
                            <ul>
                                <?php foreach ( $product_categories as $category ) : ?>
                                    <li>
                                        <label>
                                            <input type="checkbox" name="product_cat[]" value="<?php echo esc_attr( $category->slug ); ?>">
                                            <?php echo esc_html( $category->name ); ?>
                                        </label>
                                        
                                        <?php
                                        // Get subcategories
                                        $subcategories = get_terms( array(
                                            'taxonomy'   => 'product_cat',
                                            'hide_empty' => true,
                                            'parent'     => $category->term_id,
                                        ) );
                                        
                                        if ( ! empty( $subcategories ) && ! is_wp_error( $subcategories ) ) {
                                            ?>
                                            <ul class="subcategories">
                                                <?php foreach ( $subcategories as $subcategory ) : ?>
                                                    <li>
                                                        <label>
                                                            <input type="checkbox" name="product_cat[]" value="<?php echo esc_attr( $subcategory->slug ); ?>">
                                                            <?php echo esc_html( $subcategory->name ); ?>
                                                        </label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                            <?php
                                        }
                                        ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php
                    }
                    
                    // Price range
                    ?>
                    <div class="filter-section filter-price">
                        <h4><?php esc_html_e( 'Price Range', 'aqualuxe' ); ?></h4>
                        <div class="price-slider-wrapper">
                            <div class="price-slider"></div>
                            <div class="price-slider-values">
                                <span class="price-slider-min"></span>
                                <span class="price-slider-max"></span>
                            </div>
                            <input type="hidden" name="min_price" value="">
                            <input type="hidden" name="max_price" value="">
                        </div>
                    </div>
                    
                    <?php
                    // Attributes
                    $attribute_taxonomies = wc_get_attribute_taxonomies();
                    
                    if ( ! empty( $attribute_taxonomies ) ) {
                        foreach ( $attribute_taxonomies as $attribute ) {
                            $taxonomy = 'pa_' . $attribute->attribute_name;
                            $terms = get_terms( array(
                                'taxonomy'   => $taxonomy,
                                'hide_empty' => true,
                            ) );
                            
                            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                                ?>
                                <div class="filter-section filter-attribute filter-<?php echo esc_attr( $taxonomy ); ?>">
                                    <h4><?php echo esc_html( $attribute->attribute_label ); ?></h4>
                                    <ul>
                                        <?php foreach ( $terms as $term ) : ?>
                                            <li>
                                                <label>
                                                    <input type="checkbox" name="<?php echo esc_attr( $taxonomy ); ?>[]" value="<?php echo esc_attr( $term->slug ); ?>">
                                                    <?php echo esc_html( $term->name ); ?>
                                                </label>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <?php
                            }
                        }
                    }
                    ?>
                </div>
                
                <div class="filter-footer">
                    <button class="filter-apply"><?php esc_html_e( 'Apply Filter', 'aqualuxe' ); ?></button>
                    <button class="filter-reset"><?php esc_html_e( 'Reset', 'aqualuxe' ); ?></button>
                </div>
            </div>
        </div>
        <?php
    }
}

/**
 * Display product sorting
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_sorting' ) ) {
    function aqualuxe_woocommerce_product_sorting() {
        if ( ! get_theme_mod( 'aqualuxe_product_sorting', true ) ) {
            return;
        }
        
        ?>
        <div class="aqualuxe-product-sorting">
            <form class="woocommerce-ordering" method="get">
                <select name="orderby" class="orderby" aria-label="<?php esc_attr_e( 'Shop order', 'aqualuxe' ); ?>">
                    <?php foreach ( wc_get_catalog_ordering_options() as $id => $name ) : ?>
                        <option value="<?php echo esc_attr( $id ); ?>" <?php selected( $id, wc_get_loop_prop( 'orderby' ) ); ?>>
                            <?php echo esc_html( $name ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="paged" value="1" />
                <?php wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page' ) ); ?>
            </form>
        </div>
        <?php
    }
}

/**
 * Display product view switcher
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_view_switcher' ) ) {
    function aqualuxe_woocommerce_product_view_switcher() {
        if ( ! get_theme_mod( 'aqualuxe_product_view_switcher', true ) ) {
            return;
        }
        
        $current_view = isset( $_COOKIE['aqualuxe_product_view'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_product_view'] ) ) : 'grid';
        
        ?>
        <div class="aqualuxe-product-view-switcher">
            <button class="view-grid <?php echo $current_view === 'grid' ? 'active' : ''; ?>" data-view="grid">
                <i class="fa fa-th"></i>
                <span class="screen-reader-text"><?php esc_html_e( 'Grid View', 'aqualuxe' ); ?></span>
            </button>
            <button class="view-list <?php echo $current_view === 'list' ? 'active' : ''; ?>" data-view="list">
                <i class="fa fa-list"></i>
                <span class="screen-reader-text"><?php esc_html_e( 'List View', 'aqualuxe' ); ?></span>
            </button>
        </div>
        <?php
    }
}

/**
 * Display product results count
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_results_count' ) ) {
    function aqualuxe_woocommerce_product_results_count() {
        if ( ! woocommerce_products_will_display() ) {
            return;
        }
        
        ?>
        <div class="aqualuxe-product-results-count">
            <?php woocommerce_result_count(); ?>
        </div>
        <?php
    }
}

/**
 * Display product pagination
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_pagination' ) ) {
    function aqualuxe_woocommerce_product_pagination() {
        if ( ! woocommerce_products_will_display() ) {
            return;
        }
        
        ?>
        <div class="aqualuxe-product-pagination">
            <?php woocommerce_pagination(); ?>
        </div>
        <?php
    }
}

/**
 * Display product breadcrumb
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_breadcrumb' ) ) {
    function aqualuxe_woocommerce_product_breadcrumb() {
        if ( ! get_theme_mod( 'aqualuxe_product_breadcrumb', true ) ) {
            return;
        }
        
        ?>
        <div class="aqualuxe-product-breadcrumb">
            <?php woocommerce_breadcrumb(); ?>
        </div>
        <?php
    }
}

/**
 * Display product search
 */
if ( ! function_exists( 'aqualuxe_woocommerce_product_search' ) ) {
    function aqualuxe_woocommerce_product_search() {
        if ( ! get_theme_mod( 'aqualuxe_product_search', true ) ) {
            return;
        }
        
        ?>
        <div class="aqualuxe-product-search">
            <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <label class="screen-reader-text" for="woocommerce-product-search-field">
                    <?php esc_html_e( 'Search for:', 'aqualuxe' ); ?>
                </label>
                <input type="search" id="woocommerce-product-search-field" class="search-field" placeholder="<?php esc_attr_e( 'Search products&hellip;', 'aqualuxe' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                <button type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'aqualuxe' ); ?>">
                    <i class="fa fa-search"></i>
                    <span class="screen-reader-text"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
                </button>
                <input type="hidden" name="post_type" value="product" />
            </form>
        </div>
        <?php
    }
}

/**
 * Display mini cart
 */
if ( ! function_exists( 'aqualuxe_woocommerce_mini_cart' ) ) {
    function aqualuxe_woocommerce_mini_cart() {
        if ( ! get_theme_mod( 'aqualuxe_mini_cart', true ) ) {
            return;
        }
        
        ?>
        <div class="aqualuxe-mini-cart">
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="mini-cart-toggle">
                <i class="fa fa-shopping-cart"></i>
                <span class="mini-cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
            </a>
            
            <div class="mini-cart-content">
                <div class="mini-cart-header">
                    <h4><?php esc_html_e( 'Shopping Cart', 'aqualuxe' ); ?></h4>
                    <button class="mini-cart-close">
                        <i class="fa fa-times"></i>
                        <span class="screen-reader-text"><?php esc_html_e( 'Close', 'aqualuxe' ); ?></span>
                    </button>
                </div>
                
                <div class="widget_shopping_cart_content">
                    <?php woocommerce_mini_cart(); ?>
                </div>
            </div>
        </div>
        <?php
    }
}

/**
 * Display account menu
 */
if ( ! function_exists( 'aqualuxe_woocommerce_account_menu' ) ) {
    function aqualuxe_woocommerce_account_menu() {
        if ( ! get_theme_mod( 'aqualuxe_account_menu', true ) ) {
            return;
        }
        
        ?>
        <div class="aqualuxe-account-menu">
            <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>" class="account-toggle">
                <i class="fa fa-user"></i>
                <span class="screen-reader-text"><?php esc_html_e( 'My Account', 'aqualuxe' ); ?></span>
            </a>
            
            <div class="account-content">
                <?php if ( is_user_logged_in() ) : ?>
                    <ul class="account-links">
                        <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
                            <li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                                <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>">
                                    <?php echo esc_html( $label ); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <div class="account-login">
                        <h4><?php esc_html_e( 'Login', 'aqualuxe' ); ?></h4>
                        <form class="woocommerce-form woocommerce-form-login login" method="post">
                            <?php do_action( 'woocommerce_login_form_start' ); ?>
                            
                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="username"><?php esc_html_e( 'Username or email address', 'aqualuxe' ); ?> <span class="required">*</span></label>
                                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" />
                            </p>
                            
                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="password"><?php esc_html_e( 'Password', 'aqualuxe' ); ?> <span class="required">*</span></label>
                                <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
                            </p>
                            
                            <?php do_action( 'woocommerce_login_form' ); ?>
                            
                            <p class="form-row">
                                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                                    <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
                                    <span><?php esc_html_e( 'Remember me', 'aqualuxe' ); ?></span>
                                </label>
                                <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                                <button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'Log in', 'aqualuxe' ); ?>"><?php esc_html_e( 'Log in', 'aqualuxe' ); ?></button>
                            </p>
                            
                            <p class="woocommerce-LostPassword lost_password">
                                <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'aqualuxe' ); ?></a>
                            </p>
                            
                            <?php do_action( 'woocommerce_login_form_end' ); ?>
                        </form>
                        
                        <p class="account-register">
                            <?php esc_html_e( 'Don\'t have an account?', 'aqualuxe' ); ?>
                            <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'Register', 'aqualuxe' ); ?></a>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}

/**
 * Display wishlist menu
 */
if ( ! function_exists( 'aqualuxe_woocommerce_wishlist_menu' ) ) {
    function aqualuxe_woocommerce_wishlist_menu() {
        if ( ! get_theme_mod( 'aqualuxe_wishlist', true ) ) {
            return;
        }
        
        ?>
        <div class="aqualuxe-wishlist-menu">
            <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'wishlist' ) ); ?>" class="wishlist-toggle">
                <i class="fa fa-heart"></i>
                <span class="screen-reader-text"><?php esc_html_e( 'Wishlist', 'aqualuxe' ); ?></span>
            </a>
        </div>
        <?php
    }
}

/**
 * Display currency switcher
 */
if ( ! function_exists( 'aqualuxe_woocommerce_currency_switcher' ) ) {
    function aqualuxe_woocommerce_currency_switcher() {
        if ( ! get_theme_mod( 'aqualuxe_multi_currency', false ) ) {
            return;
        }
        
        $currencies = aqualuxe_get_available_currencies();
        $current_currency = isset( $_COOKIE['aqualuxe_currency'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_currency'] ) ) : get_option( 'woocommerce_currency' );
        
        ?>
        <div class="aqualuxe-currency-switcher">
            <select name="aqualuxe-currency" id="aqualuxe-currency">
                <?php foreach ( $currencies as $code => $name ) : ?>
                    <option value="<?php echo esc_attr( $code ); ?>" <?php selected( $current_currency, $code ); ?>>
                        <?php echo esc_html( $name . ' (' . get_woocommerce_currency_symbol( $code ) . ')' ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }
}

/**
 * Get available currencies
 *
 * @return array
 */
if ( ! function_exists( 'aqualuxe_get_available_currencies' ) ) {
    function aqualuxe_get_available_currencies() {
        $currencies = array();
        $wc_currencies = get_woocommerce_currencies();
        
        // Get enabled currencies from theme mod
        $enabled_currencies = get_theme_mod( 'aqualuxe_enabled_currencies', array( 'USD', 'EUR', 'GBP' ) );
        
        if ( ! is_array( $enabled_currencies ) ) {
            $enabled_currencies = array( 'USD', 'EUR', 'GBP' );
        }
        
        foreach ( $enabled_currencies as $currency ) {
            if ( isset( $wc_currencies[ $currency ] ) ) {
                $currencies[ $currency ] = $wc_currencies[ $currency ];
            }
        }
        
        return $currencies;
    }
}