<?php
/**
 * Multi-vendor support for AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Check if WC Marketplace is active
 *
 * @return bool
 */
function aqualuxe_is_wcmp_active() {
    return class_exists( 'WCMp' );
}

/**
 * Check if WC Vendors is active
 *
 * @return bool
 */
function aqualuxe_is_wc_vendors_active() {
    return class_exists( 'WC_Vendors' );
}

/**
 * Check if Dokan is active
 *
 * @return bool
 */
function aqualuxe_is_dokan_active() {
    return class_exists( 'WeDevs_Dokan' );
}

/**
 * Check if WCFM is active
 *
 * @return bool
 */
function aqualuxe_is_wcfm_active() {
    return class_exists( 'WCFM' );
}

/**
 * Check if any multi-vendor plugin is active
 *
 * @return bool
 */
function aqualuxe_is_multi_vendor() {
    return aqualuxe_is_wcmp_active() || aqualuxe_is_wc_vendors_active() || aqualuxe_is_dokan_active() || aqualuxe_is_wcfm_active();
}

/**
 * Get vendor ID from product
 *
 * @param int|WC_Product $product Product ID or object
 * @return int|bool Vendor ID or false if not found
 */
function aqualuxe_get_vendor_id_from_product( $product ) {
    if ( ! aqualuxe_is_multi_vendor() ) {
        return false;
    }

    if ( is_numeric( $product ) ) {
        $product = wc_get_product( $product );
    }

    if ( ! $product ) {
        return false;
    }

    $product_id = $product->get_id();

    if ( aqualuxe_is_wcmp_active() ) {
        $vendor = get_wcmp_product_vendors( $product_id );
        return $vendor ? $vendor->id : false;
    } elseif ( aqualuxe_is_wc_vendors_active() ) {
        return WCV_Vendors::get_vendor_from_product( $product_id );
    } elseif ( aqualuxe_is_dokan_active() ) {
        return get_post_field( 'post_author', $product_id );
    } elseif ( aqualuxe_is_wcfm_active() && function_exists( 'wcfm_get_vendor_id_by_post' ) ) {
        return wcfm_get_vendor_id_by_post( $product_id );
    }

    return false;
}

/**
 * Get vendor from product
 *
 * @param int|WC_Product $product Product ID or object
 * @return object|bool Vendor object or false if not found
 */
function aqualuxe_get_vendor_from_product( $product ) {
    if ( ! aqualuxe_is_multi_vendor() ) {
        return false;
    }

    $vendor_id = aqualuxe_get_vendor_id_from_product( $product );

    if ( ! $vendor_id ) {
        return false;
    }

    return aqualuxe_get_vendor( $vendor_id );
}

/**
 * Get vendor object
 *
 * @param int $vendor_id Vendor ID
 * @return object|bool Vendor object or false if not found
 */
function aqualuxe_get_vendor( $vendor_id ) {
    if ( ! aqualuxe_is_multi_vendor() || ! $vendor_id ) {
        return false;
    }

    if ( aqualuxe_is_wcmp_active() ) {
        return get_wcmp_vendor( $vendor_id );
    } elseif ( aqualuxe_is_wc_vendors_active() && function_exists( 'WCV_Vendors' ) ) {
        return WCV_Vendors::get_vendor_data( $vendor_id );
    } elseif ( aqualuxe_is_dokan_active() && function_exists( 'dokan_get_vendor' ) ) {
        return dokan_get_vendor( $vendor_id );
    } elseif ( aqualuxe_is_wcfm_active() && function_exists( 'wcfmmp_get_store' ) ) {
        return wcfmmp_get_store( $vendor_id );
    }

    return false;
}

/**
 * Get vendor name
 *
 * @param int|object $vendor Vendor ID or object
 * @return string Vendor name
 */
function aqualuxe_get_vendor_name( $vendor ) {
    if ( ! aqualuxe_is_multi_vendor() ) {
        return '';
    }

    if ( is_numeric( $vendor ) ) {
        $vendor = aqualuxe_get_vendor( $vendor );
    }

    if ( ! $vendor ) {
        return '';
    }

    if ( aqualuxe_is_wcmp_active() ) {
        return $vendor->page_title;
    } elseif ( aqualuxe_is_wc_vendors_active() ) {
        return $vendor['user_id'] ? get_user_meta( $vendor['user_id'], 'pv_shop_name', true ) : '';
    } elseif ( aqualuxe_is_dokan_active() ) {
        return $vendor->get_shop_name();
    } elseif ( aqualuxe_is_wcfm_active() ) {
        return $vendor->get_shop_name();
    }

    return '';
}

/**
 * Get vendor shop URL
 *
 * @param int|object $vendor Vendor ID or object
 * @return string Vendor shop URL
 */
function aqualuxe_get_vendor_shop_url( $vendor ) {
    if ( ! aqualuxe_is_multi_vendor() ) {
        return '';
    }

    if ( is_numeric( $vendor ) ) {
        $vendor = aqualuxe_get_vendor( $vendor );
    }

    if ( ! $vendor ) {
        return '';
    }

    if ( aqualuxe_is_wcmp_active() ) {
        return $vendor->permalink;
    } elseif ( aqualuxe_is_wc_vendors_active() && function_exists( 'WCV_Vendors' ) ) {
        return $vendor['user_id'] ? WCV_Vendors::get_vendor_shop_page( $vendor['user_id'] ) : '';
    } elseif ( aqualuxe_is_dokan_active() ) {
        return $vendor->get_shop_url();
    } elseif ( aqualuxe_is_wcfm_active() ) {
        return $vendor->get_shop_url();
    }

    return '';
}

/**
 * Get vendor logo
 *
 * @param int|object $vendor Vendor ID or object
 * @param string     $size Image size
 * @return string Vendor logo URL
 */
function aqualuxe_get_vendor_logo( $vendor, $size = 'thumbnail' ) {
    if ( ! aqualuxe_is_multi_vendor() ) {
        return '';
    }

    if ( is_numeric( $vendor ) ) {
        $vendor = aqualuxe_get_vendor( $vendor );
    }

    if ( ! $vendor ) {
        return '';
    }

    if ( aqualuxe_is_wcmp_active() ) {
        $logo = $vendor->get_image() ? $vendor->get_image( $size ) : '';
        return $logo ? $logo : $vendor->get_image();
    } elseif ( aqualuxe_is_wc_vendors_active() ) {
        if ( ! isset( $vendor['user_id'] ) ) {
            return '';
        }
        $logo_id = get_user_meta( $vendor['user_id'], '_wcv_store_icon_id', true );
        return $logo_id ? wp_get_attachment_image_url( $logo_id, $size ) : '';
    } elseif ( aqualuxe_is_dokan_active() ) {
        return $vendor->get_avatar();
    } elseif ( aqualuxe_is_wcfm_active() ) {
        return $vendor->get_avatar();
    }

    return '';
}

/**
 * Get vendor banner
 *
 * @param int|object $vendor Vendor ID or object
 * @param string     $size Image size
 * @return string Vendor banner URL
 */
function aqualuxe_get_vendor_banner( $vendor, $size = 'full' ) {
    if ( ! aqualuxe_is_multi_vendor() ) {
        return '';
    }

    if ( is_numeric( $vendor ) ) {
        $vendor = aqualuxe_get_vendor( $vendor );
    }

    if ( ! $vendor ) {
        return '';
    }

    if ( aqualuxe_is_wcmp_active() ) {
        return $vendor->get_image( 'banner', $size );
    } elseif ( aqualuxe_is_wc_vendors_active() ) {
        if ( ! isset( $vendor['user_id'] ) ) {
            return '';
        }
        $banner_id = get_user_meta( $vendor['user_id'], '_wcv_store_banner_id', true );
        return $banner_id ? wp_get_attachment_image_url( $banner_id, $size ) : '';
    } elseif ( aqualuxe_is_dokan_active() ) {
        return $vendor->get_banner();
    } elseif ( aqualuxe_is_wcfm_active() ) {
        return $vendor->get_banner();
    }

    return '';
}

/**
 * Get vendor description
 *
 * @param int|object $vendor Vendor ID or object
 * @return string Vendor description
 */
function aqualuxe_get_vendor_description( $vendor ) {
    if ( ! aqualuxe_is_multi_vendor() ) {
        return '';
    }

    if ( is_numeric( $vendor ) ) {
        $vendor = aqualuxe_get_vendor( $vendor );
    }

    if ( ! $vendor ) {
        return '';
    }

    if ( aqualuxe_is_wcmp_active() ) {
        return $vendor->description;
    } elseif ( aqualuxe_is_wc_vendors_active() ) {
        if ( ! isset( $vendor['user_id'] ) ) {
            return '';
        }
        return get_user_meta( $vendor['user_id'], 'pv_shop_description', true );
    } elseif ( aqualuxe_is_dokan_active() ) {
        return $vendor->get_shop_info();
    } elseif ( aqualuxe_is_wcfm_active() ) {
        return $vendor->get_shop_description();
    }

    return '';
}

/**
 * Get vendor address
 *
 * @param int|object $vendor Vendor ID or object
 * @return string Vendor address
 */
function aqualuxe_get_vendor_address( $vendor ) {
    if ( ! aqualuxe_is_multi_vendor() ) {
        return '';
    }

    if ( is_numeric( $vendor ) ) {
        $vendor = aqualuxe_get_vendor( $vendor );
    }

    if ( ! $vendor ) {
        return '';
    }

    if ( aqualuxe_is_wcmp_active() ) {
        $address = $vendor->get_formatted_address();
        return $address ? $address : '';
    } elseif ( aqualuxe_is_wc_vendors_active() ) {
        if ( ! isset( $vendor['user_id'] ) ) {
            return '';
        }
        $address = get_user_meta( $vendor['user_id'], '_wcv_store_address', true );
        return $address ? $address : '';
    } elseif ( aqualuxe_is_dokan_active() ) {
        return $vendor->get_address();
    } elseif ( aqualuxe_is_wcfm_active() ) {
        return $vendor->get_address();
    }

    return '';
}

/**
 * Get vendor phone
 *
 * @param int|object $vendor Vendor ID or object
 * @return string Vendor phone
 */
function aqualuxe_get_vendor_phone( $vendor ) {
    if ( ! aqualuxe_is_multi_vendor() ) {
        return '';
    }

    if ( is_numeric( $vendor ) ) {
        $vendor = aqualuxe_get_vendor( $vendor );
    }

    if ( ! $vendor ) {
        return '';
    }

    if ( aqualuxe_is_wcmp_active() ) {
        return $vendor->phone;
    } elseif ( aqualuxe_is_wc_vendors_active() ) {
        if ( ! isset( $vendor['user_id'] ) ) {
            return '';
        }
        return get_user_meta( $vendor['user_id'], '_wcv_store_phone', true );
    } elseif ( aqualuxe_is_dokan_active() ) {
        return $vendor->get_phone();
    } elseif ( aqualuxe_is_wcfm_active() ) {
        return $vendor->get_phone();
    }

    return '';
}

/**
 * Get vendor email
 *
 * @param int|object $vendor Vendor ID or object
 * @return string Vendor email
 */
function aqualuxe_get_vendor_email( $vendor ) {
    if ( ! aqualuxe_is_multi_vendor() ) {
        return '';
    }

    if ( is_numeric( $vendor ) ) {
        $vendor = aqualuxe_get_vendor( $vendor );
    }

    if ( ! $vendor ) {
        return '';
    }

    if ( aqualuxe_is_wcmp_active() ) {
        return $vendor->user_data->user_email;
    } elseif ( aqualuxe_is_wc_vendors_active() ) {
        if ( ! isset( $vendor['user_id'] ) ) {
            return '';
        }
        $user = get_userdata( $vendor['user_id'] );
        return $user ? $user->user_email : '';
    } elseif ( aqualuxe_is_dokan_active() ) {
        return $vendor->get_email();
    } elseif ( aqualuxe_is_wcfm_active() ) {
        return $vendor->get_email();
    }

    return '';
}

/**
 * Get vendor social links
 *
 * @param int|object $vendor Vendor ID or object
 * @return array Vendor social links
 */
function aqualuxe_get_vendor_social_links( $vendor ) {
    if ( ! aqualuxe_is_multi_vendor() ) {
        return array();
    }

    if ( is_numeric( $vendor ) ) {
        $vendor = aqualuxe_get_vendor( $vendor );
    }

    if ( ! $vendor ) {
        return array();
    }

    $social_links = array();

    if ( aqualuxe_is_wcmp_active() ) {
        $vendor_id = $vendor->id;
        $social_links['facebook'] = get_user_meta( $vendor_id, '_vendor_fb_profile', true );
        $social_links['twitter'] = get_user_meta( $vendor_id, '_vendor_twitter_profile', true );
        $social_links['linkedin'] = get_user_meta( $vendor_id, '_vendor_linkdin_profile', true );
        $social_links['youtube'] = get_user_meta( $vendor_id, '_vendor_youtube', true );
        $social_links['instagram'] = get_user_meta( $vendor_id, '_vendor_instagram', true );
    } elseif ( aqualuxe_is_wc_vendors_active() ) {
        if ( ! isset( $vendor['user_id'] ) ) {
            return array();
        }
        $vendor_id = $vendor['user_id'];
        $social_links['facebook'] = get_user_meta( $vendor_id, '_wcv_facebook_url', true );
        $social_links['twitter'] = get_user_meta( $vendor_id, '_wcv_twitter_url', true );
        $social_links['linkedin'] = get_user_meta( $vendor_id, '_wcv_linkedin_url', true );
        $social_links['youtube'] = get_user_meta( $vendor_id, '_wcv_youtube_url', true );
        $social_links['instagram'] = get_user_meta( $vendor_id, '_wcv_instagram_url', true );
    } elseif ( aqualuxe_is_dokan_active() ) {
        $social_links = $vendor->get_social_profiles();
    } elseif ( aqualuxe_is_wcfm_active() ) {
        $social_links = $vendor->get_social();
    }

    return array_filter( $social_links );
}

/**
 * Get vendor rating
 *
 * @param int|object $vendor Vendor ID or object
 * @return array Vendor rating data
 */
function aqualuxe_get_vendor_rating( $vendor ) {
    if ( ! aqualuxe_is_multi_vendor() ) {
        return array(
            'rating' => 0,
            'count'  => 0,
        );
    }

    if ( is_numeric( $vendor ) ) {
        $vendor = aqualuxe_get_vendor( $vendor );
    }

    if ( ! $vendor ) {
        return array(
            'rating' => 0,
            'count'  => 0,
        );
    }

    if ( aqualuxe_is_wcmp_active() ) {
        $vendor_id = $vendor->id;
        $vendor_term_id = get_user_meta( $vendor_id, '_vendor_term_id', true );
        $rating_val = get_wcmp_vendor_review_count( $vendor_term_id );
        
        return array(
            'rating' => floatval( $rating_val['avg_rating'] ),
            'count'  => intval( $rating_val['total_rating'] ),
        );
    } elseif ( aqualuxe_is_wc_vendors_active() ) {
        if ( ! isset( $vendor['user_id'] ) || ! function_exists( 'WCVendors_Pro_Ratings_Controller' ) ) {
            return array(
                'rating' => 0,
                'count'  => 0,
            );
        }
        
        $vendor_id = $vendor['user_id'];
        $rating_controller = WCVendors_Pro_Ratings_Controller::get_instance();
        $rating = $rating_controller->get_ratings_average( $vendor_id );
        $count = $rating_controller->get_ratings_count( $vendor_id );
        
        return array(
            'rating' => floatval( $rating ),
            'count'  => intval( $count ),
        );
    } elseif ( aqualuxe_is_dokan_active() ) {
        $rating = $vendor->get_rating();
        
        return array(
            'rating' => isset( $rating['rating'] ) ? floatval( $rating['rating'] ) : 0,
            'count'  => isset( $rating['count'] ) ? intval( $rating['count'] ) : 0,
        );
    } elseif ( aqualuxe_is_wcfm_active() ) {
        $rating = $vendor->get_rating();
        
        return array(
            'rating' => isset( $rating['avg_rating'] ) ? floatval( $rating['avg_rating'] ) : 0,
            'count'  => isset( $rating['total_review'] ) ? intval( $rating['total_review'] ) : 0,
        );
    }

    return array(
        'rating' => 0,
        'count'  => 0,
    );
}

/**
 * Get vendor products count
 *
 * @param int|object $vendor Vendor ID or object
 * @return int Products count
 */
function aqualuxe_get_vendor_products_count( $vendor ) {
    if ( ! aqualuxe_is_multi_vendor() ) {
        return 0;
    }

    if ( is_numeric( $vendor ) ) {
        $vendor = aqualuxe_get_vendor( $vendor );
    }

    if ( ! $vendor ) {
        return 0;
    }

    if ( aqualuxe_is_wcmp_active() ) {
        return count( $vendor->get_products() );
    } elseif ( aqualuxe_is_wc_vendors_active() ) {
        if ( ! isset( $vendor['user_id'] ) ) {
            return 0;
        }
        
        $vendor_id = $vendor['user_id'];
        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'author'         => $vendor_id,
        );
        
        $products = new WP_Query( $args );
        return $products->found_posts;
    } elseif ( aqualuxe_is_dokan_active() ) {
        return $vendor->get_products()->total;
    } elseif ( aqualuxe_is_wcfm_active() ) {
        return $vendor->get_products_count();
    }

    return 0;
}

/**
 * Display vendor information on product page
 */
function aqualuxe_display_vendor_info() {
    if ( ! aqualuxe_is_multi_vendor() || ! is_product() ) {
        return;
    }

    global $product;
    $vendor = aqualuxe_get_vendor_from_product( $product );

    if ( ! $vendor ) {
        return;
    }

    $vendor_name = aqualuxe_get_vendor_name( $vendor );
    $vendor_url = aqualuxe_get_vendor_shop_url( $vendor );
    $vendor_logo = aqualuxe_get_vendor_logo( $vendor );
    $vendor_rating = aqualuxe_get_vendor_rating( $vendor );
    ?>
    <div class="vendor-info mt-6 pt-6 border-t border-gray-200">
        <h3 class="text-lg font-bold mb-4"><?php esc_html_e( 'Sold by:', 'aqualuxe' ); ?></h3>
        <div class="vendor-details flex items-center">
            <?php if ( $vendor_logo ) : ?>
                <div class="vendor-logo mr-4">
                    <a href="<?php echo esc_url( $vendor_url ); ?>">
                        <img src="<?php echo esc_url( $vendor_logo ); ?>" alt="<?php echo esc_attr( $vendor_name ); ?>" class="w-16 h-16 object-cover rounded-full">
                    </a>
                </div>
            <?php endif; ?>
            <div class="vendor-data">
                <h4 class="vendor-name text-lg font-bold">
                    <a href="<?php echo esc_url( $vendor_url ); ?>" class="text-primary-600 hover:text-primary-800 transition-colors">
                        <?php echo esc_html( $vendor_name ); ?>
                    </a>
                </h4>
                <?php if ( $vendor_rating['rating'] > 0 ) : ?>
                    <div class="vendor-rating flex items-center">
                        <div class="star-rating" role="img" aria-label="<?php echo sprintf( esc_attr__( 'Rated %s out of 5', 'aqualuxe' ), $vendor_rating['rating'] ); ?>">
                            <span style="width: <?php echo esc_attr( ( $vendor_rating['rating'] / 5 ) * 100 ); ?>%;">
                                <?php echo sprintf( esc_html__( 'Rated %s out of 5', 'aqualuxe' ), $vendor_rating['rating'] ); ?>
                            </span>
                        </div>
                        <span class="rating-count text-sm text-gray-600 ml-2">
                            (<?php echo esc_html( $vendor_rating['count'] ); ?>)
                        </span>
                    </div>
                <?php endif; ?>
                <div class="vendor-actions mt-2">
                    <a href="<?php echo esc_url( $vendor_url ); ?>" class="inline-flex items-center text-primary-600 hover:text-primary-800 transition-colors text-sm">
                        <?php esc_html_e( 'Visit Store', 'aqualuxe' ); ?> <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_display_vendor_info', 25 );

/**
 * Add vendor tab to product tabs
 *
 * @param array $tabs Product tabs
 * @return array
 */
function aqualuxe_add_vendor_tab( $tabs ) {
    if ( ! aqualuxe_is_multi_vendor() || ! is_product() ) {
        return $tabs;
    }

    global $product;
    $vendor = aqualuxe_get_vendor_from_product( $product );

    if ( ! $vendor ) {
        return $tabs;
    }

    $vendor_name = aqualuxe_get_vendor_name( $vendor );
    
    $tabs['vendor'] = array(
        'title'    => sprintf( esc_html__( 'About %s', 'aqualuxe' ), $vendor_name ),
        'priority' => 30,
        'callback' => 'aqualuxe_vendor_tab_content',
    );

    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'aqualuxe_add_vendor_tab' );

/**
 * Vendor tab content
 */
function aqualuxe_vendor_tab_content() {
    global $product;
    $vendor = aqualuxe_get_vendor_from_product( $product );

    if ( ! $vendor ) {
        return;
    }

    $vendor_name = aqualuxe_get_vendor_name( $vendor );
    $vendor_url = aqualuxe_get_vendor_shop_url( $vendor );
    $vendor_logo = aqualuxe_get_vendor_logo( $vendor, 'medium' );
    $vendor_banner = aqualuxe_get_vendor_banner( $vendor );
    $vendor_description = aqualuxe_get_vendor_description( $vendor );
    $vendor_address = aqualuxe_get_vendor_address( $vendor );
    $vendor_phone = aqualuxe_get_vendor_phone( $vendor );
    $vendor_email = aqualuxe_get_vendor_email( $vendor );
    $vendor_rating = aqualuxe_get_vendor_rating( $vendor );
    $vendor_social = aqualuxe_get_vendor_social_links( $vendor );
    $vendor_products_count = aqualuxe_get_vendor_products_count( $vendor );
    ?>
    <div class="vendor-profile">
        <?php if ( $vendor_banner ) : ?>
            <div class="vendor-banner mb-6">
                <img src="<?php echo esc_url( $vendor_banner ); ?>" alt="<?php echo esc_attr( $vendor_name ); ?>" class="w-full h-auto rounded-lg">
            </div>
        <?php endif; ?>

        <div class="vendor-header flex flex-col md:flex-row items-start md:items-center mb-6">
            <?php if ( $vendor_logo ) : ?>
                <div class="vendor-logo mr-6 mb-4 md:mb-0">
                    <img src="<?php echo esc_url( $vendor_logo ); ?>" alt="<?php echo esc_attr( $vendor_name ); ?>" class="w-24 h-24 object-cover rounded-full">
                </div>
            <?php endif; ?>
            
            <div class="vendor-header-info">
                <h3 class="vendor-name text-2xl font-bold mb-2"><?php echo esc_html( $vendor_name ); ?></h3>
                
                <div class="vendor-meta flex flex-wrap items-center text-sm text-gray-600">
                    <?php if ( $vendor_rating['rating'] > 0 ) : ?>
                        <div class="vendor-rating flex items-center mr-4">
                            <div class="star-rating" role="img" aria-label="<?php echo sprintf( esc_attr__( 'Rated %s out of 5', 'aqualuxe' ), $vendor_rating['rating'] ); ?>">
                                <span style="width: <?php echo esc_attr( ( $vendor_rating['rating'] / 5 ) * 100 ); ?>%;">
                                    <?php echo sprintf( esc_html__( 'Rated %s out of 5', 'aqualuxe' ), $vendor_rating['rating'] ); ?>
                                </span>
                            </div>
                            <span class="rating-count ml-1">
                                (<?php echo esc_html( $vendor_rating['count'] ); ?>)
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( $vendor_products_count > 0 ) : ?>
                        <div class="vendor-products-count mr-4">
                            <i class="fas fa-box mr-1"></i>
                            <?php
                            /* translators: %d: products count */
                            printf( esc_html( _n( '%d Product', '%d Products', $vendor_products_count, 'aqualuxe' ) ), $vendor_products_count );
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( $vendor_address ) : ?>
                        <div class="vendor-location">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            <?php echo esc_html( $vendor_address ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ( $vendor_description ) : ?>
            <div class="vendor-description prose max-w-none mb-6">
                <?php echo wp_kses_post( wpautop( $vendor_description ) ); ?>
            </div>
        <?php endif; ?>

        <div class="vendor-details grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="vendor-contact">
                <h4 class="text-lg font-bold mb-4"><?php esc_html_e( 'Contact Information', 'aqualuxe' ); ?></h4>
                <ul class="space-y-2">
                    <?php if ( $vendor_address ) : ?>
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-2 text-primary-600"></i>
                            <span><?php echo esc_html( $vendor_address ); ?></span>
                        </li>
                    <?php endif; ?>
                    
                    <?php if ( $vendor_phone ) : ?>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-2 text-primary-600"></i>
                            <a href="tel:<?php echo esc_attr( $vendor_phone ); ?>" class="text-primary-600 hover:text-primary-800 transition-colors">
                                <?php echo esc_html( $vendor_phone ); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php if ( $vendor_email ) : ?>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2 text-primary-600"></i>
                            <a href="mailto:<?php echo esc_attr( $vendor_email ); ?>" class="text-primary-600 hover:text-primary-800 transition-colors">
                                <?php echo esc_html( $vendor_email ); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <?php if ( ! empty( $vendor_social ) ) : ?>
                <div class="vendor-social">
                    <h4 class="text-lg font-bold mb-4"><?php esc_html_e( 'Follow Us', 'aqualuxe' ); ?></h4>
                    <div class="social-links flex space-x-3">
                        <?php if ( isset( $vendor_social['facebook'] ) && $vendor_social['facebook'] ) : ?>
                            <a href="<?php echo esc_url( $vendor_social['facebook'] ); ?>" target="_blank" rel="noopener noreferrer" class="bg-blue-600 hover:bg-blue-700 text-white w-10 h-10 rounded-full flex items-center justify-center transition-colors">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ( isset( $vendor_social['twitter'] ) && $vendor_social['twitter'] ) : ?>
                            <a href="<?php echo esc_url( $vendor_social['twitter'] ); ?>" target="_blank" rel="noopener noreferrer" class="bg-blue-400 hover:bg-blue-500 text-white w-10 h-10 rounded-full flex items-center justify-center transition-colors">
                                <i class="fab fa-twitter"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ( isset( $vendor_social['instagram'] ) && $vendor_social['instagram'] ) : ?>
                            <a href="<?php echo esc_url( $vendor_social['instagram'] ); ?>" target="_blank" rel="noopener noreferrer" class="bg-pink-600 hover:bg-pink-700 text-white w-10 h-10 rounded-full flex items-center justify-center transition-colors">
                                <i class="fab fa-instagram"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ( isset( $vendor_social['youtube'] ) && $vendor_social['youtube'] ) : ?>
                            <a href="<?php echo esc_url( $vendor_social['youtube'] ); ?>" target="_blank" rel="noopener noreferrer" class="bg-red-600 hover:bg-red-700 text-white w-10 h-10 rounded-full flex items-center justify-center transition-colors">
                                <i class="fab fa-youtube"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ( isset( $vendor_social['linkedin'] ) && $vendor_social['linkedin'] ) : ?>
                            <a href="<?php echo esc_url( $vendor_social['linkedin'] ); ?>" target="_blank" rel="noopener noreferrer" class="bg-blue-800 hover:bg-blue-900 text-white w-10 h-10 rounded-full flex items-center justify-center transition-colors">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="vendor-cta">
            <a href="<?php echo esc_url( $vendor_url ); ?>" class="inline-flex items-center bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-6 rounded-md transition-colors">
                <?php esc_html_e( 'Visit Store', 'aqualuxe' ); ?> <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Add vendor info to product structured data
 *
 * @param array      $markup Structured data markup
 * @param WC_Product $product Product object
 * @return array
 */
function aqualuxe_add_vendor_to_structured_data( $markup, $product ) {
    if ( ! aqualuxe_is_multi_vendor() ) {
        return $markup;
    }

    $vendor = aqualuxe_get_vendor_from_product( $product );

    if ( ! $vendor ) {
        return $markup;
    }

    $vendor_name = aqualuxe_get_vendor_name( $vendor );
    $vendor_url = aqualuxe_get_vendor_shop_url( $vendor );
    $vendor_logo = aqualuxe_get_vendor_logo( $vendor );
    $vendor_description = aqualuxe_get_vendor_description( $vendor );

    if ( ! isset( $markup['seller'] ) ) {
        $markup['seller'] = array(
            '@type' => 'Organization',
            'name'  => $vendor_name,
        );

        if ( $vendor_url ) {
            $markup['seller']['url'] = $vendor_url;
        }

        if ( $vendor_logo ) {
            $markup['seller']['logo'] = $vendor_logo;
        }

        if ( $vendor_description ) {
            $markup['seller']['description'] = wp_strip_all_tags( $vendor_description );
        }
    }

    return $markup;
}
add_filter( 'woocommerce_structured_data_product', 'aqualuxe_add_vendor_to_structured_data', 10, 2 );

/**
 * Display vendor products on vendor profile
 *
 * @param int|object $vendor Vendor ID or object
 * @param int        $limit Products limit
 */
function aqualuxe_display_vendor_products( $vendor, $limit = 4 ) {
    if ( ! aqualuxe_is_multi_vendor() ) {
        return;
    }

    if ( is_numeric( $vendor ) ) {
        $vendor = aqualuxe_get_vendor( $vendor );
    }

    if ( ! $vendor ) {
        return;
    }

    $vendor_id = 0;

    if ( aqualuxe_is_wcmp_active() ) {
        $vendor_id = $vendor->id;
        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $limit,
            'author'         => $vendor_id,
        );
    } elseif ( aqualuxe_is_wc_vendors_active() ) {
        if ( ! isset( $vendor['user_id'] ) ) {
            return;
        }
        
        $vendor_id = $vendor['user_id'];
        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $limit,
            'author'         => $vendor_id,
        );
    } elseif ( aqualuxe_is_dokan_active() ) {
        $vendor_id = $vendor->get_id();
        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $limit,
            'author'         => $vendor_id,
        );
    } elseif ( aqualuxe_is_wcfm_active() ) {
        $vendor_id = $vendor->get_id();
        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $limit,
            'author'         => $vendor_id,
        );
    }

    if ( ! $vendor_id ) {
        return;
    }

    $products = new WP_Query( $args );

    if ( $products->have_posts() ) :
        ?>
        <div class="vendor-products mt-8 pt-8 border-t border-gray-200">
            <h3 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Products from this Vendor', 'aqualuxe' ); ?></h3>
            
            <div class="products grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php
                while ( $products->have_posts() ) :
                    $products->the_post();
                    wc_get_template_part( 'content', 'product' );
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
            
            <div class="vendor-products-link mt-6 text-center">
                <a href="<?php echo esc_url( aqualuxe_get_vendor_shop_url( $vendor ) ); ?>" class="inline-flex items-center bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-6 rounded-md transition-colors">
                    <?php esc_html_e( 'View All Products', 'aqualuxe' ); ?> <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
        <?php
    endif;
}

/**
 * Display vendor products on single product page
 */
function aqualuxe_display_vendor_products_on_product() {
    if ( ! aqualuxe_is_multi_vendor() || ! is_product() ) {
        return;
    }

    global $product;
    $vendor = aqualuxe_get_vendor_from_product( $product );

    if ( ! $vendor ) {
        return;
    }

    aqualuxe_display_vendor_products( $vendor, 4 );
}
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_display_vendor_products_on_product', 20 );

/**
 * Add vendor filter to shop page
 */
function aqualuxe_add_vendor_filter() {
    if ( ! aqualuxe_is_multi_vendor() || ! is_shop() ) {
        return;
    }

    $vendors = array();

    if ( aqualuxe_is_wcmp_active() ) {
        $vendors = get_wcmp_vendors();
    } elseif ( aqualuxe_is_wc_vendors_active() && function_exists( 'WCV_Vendors' ) ) {
        $vendor_ids = WCV_Vendors::get_vendors();
        foreach ( $vendor_ids as $vendor_id ) {
            $vendors[] = array(
                'id'   => $vendor_id,
                'name' => get_user_meta( $vendor_id, 'pv_shop_name', true ),
            );
        }
    } elseif ( aqualuxe_is_dokan_active() && function_exists( 'dokan_get_sellers' ) ) {
        $sellers = dokan_get_sellers();
        foreach ( $sellers['users'] as $seller ) {
            $vendors[] = array(
                'id'   => $seller->ID,
                'name' => $seller->display_name,
            );
        }
    } elseif ( aqualuxe_is_wcfm_active() && function_exists( 'wcfm_get_vendor_list' ) ) {
        $wcfm_vendors = wcfm_get_vendor_list();
        foreach ( $wcfm_vendors as $vendor_id => $vendor_name ) {
            $vendors[] = array(
                'id'   => $vendor_id,
                'name' => $vendor_name,
            );
        }
    }

    if ( empty( $vendors ) ) {
        return;
    }

    $current_vendor = isset( $_GET['vendor'] ) ? sanitize_text_field( wp_unslash( $_GET['vendor'] ) ) : '';
    ?>
    <div class="vendor-filter widget">
        <h3 class="widget-title"><?php esc_html_e( 'Filter by Vendor', 'aqualuxe' ); ?></h3>
        <ul class="vendor-list">
            <li>
                <a href="<?php echo esc_url( remove_query_arg( 'vendor' ) ); ?>" class="<?php echo empty( $current_vendor ) ? 'active' : ''; ?>">
                    <?php esc_html_e( 'All Vendors', 'aqualuxe' ); ?>
                </a>
            </li>
            <?php foreach ( $vendors as $vendor ) : ?>
                <li>
                    <a href="<?php echo esc_url( add_query_arg( 'vendor', $vendor['id'] ) ); ?>" class="<?php echo $current_vendor == $vendor['id'] ? 'active' : ''; ?>">
                        <?php echo esc_html( $vendor['name'] ); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
}
add_action( 'woocommerce_before_shop_loop', 'aqualuxe_add_vendor_filter', 30 );

/**
 * Filter products by vendor
 *
 * @param WP_Query $query Query object
 * @return WP_Query
 */
function aqualuxe_filter_products_by_vendor( $query ) {
    if ( ! aqualuxe_is_multi_vendor() || ! is_shop() || ! $query->is_main_query() ) {
        return $query;
    }

    if ( ! isset( $_GET['vendor'] ) ) {
        return $query;
    }

    $vendor_id = sanitize_text_field( wp_unslash( $_GET['vendor'] ) );

    if ( ! $vendor_id ) {
        return $query;
    }

    if ( aqualuxe_is_wcmp_active() ) {
        $query->set( 'author', $vendor_id );
    } elseif ( aqualuxe_is_wc_vendors_active() ) {
        $query->set( 'author', $vendor_id );
    } elseif ( aqualuxe_is_dokan_active() ) {
        $query->set( 'author', $vendor_id );
    } elseif ( aqualuxe_is_wcfm_active() ) {
        $query->set( 'author', $vendor_id );
    }

    return $query;
}
add_action( 'pre_get_posts', 'aqualuxe_filter_products_by_vendor' );

/**
 * Add vendor contact form to vendor profile
 *
 * @param int|object $vendor Vendor ID or object
 */
function aqualuxe_vendor_contact_form( $vendor ) {
    if ( ! aqualuxe_is_multi_vendor() ) {
        return;
    }

    if ( is_numeric( $vendor ) ) {
        $vendor = aqualuxe_get_vendor( $vendor );
    }

    if ( ! $vendor ) {
        return;
    }

    $vendor_name = aqualuxe_get_vendor_name( $vendor );
    $vendor_email = aqualuxe_get_vendor_email( $vendor );

    if ( ! $vendor_email ) {
        return;
    }
    ?>
    <div class="vendor-contact-form mt-8 pt-8 border-t border-gray-200">
        <h3 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Contact Vendor', 'aqualuxe' ); ?></h3>
        
        <form id="vendor-contact-form" class="vendor-contact-form">
            <input type="hidden" name="vendor_id" value="<?php echo esc_attr( is_object( $vendor ) ? $vendor->id : $vendor ); ?>">
            <input type="hidden" name="vendor_email" value="<?php echo esc_attr( $vendor_email ); ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="form-group">
                    <label for="contact-name" class="block text-gray-700 font-medium mb-2"><?php esc_html_e( 'Name', 'aqualuxe' ); ?> <span class="required">*</span></label>
                    <input type="text" id="contact-name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" required>
                </div>
                
                <div class="form-group">
                    <label for="contact-email" class="block text-gray-700 font-medium mb-2"><?php esc_html_e( 'Email', 'aqualuxe' ); ?> <span class="required">*</span></label>
                    <input type="email" id="contact-email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" required>
                </div>
            </div>
            
            <div class="form-group mb-4">
                <label for="contact-subject" class="block text-gray-700 font-medium mb-2"><?php esc_html_e( 'Subject', 'aqualuxe' ); ?> <span class="required">*</span></label>
                <input type="text" id="contact-subject" name="subject" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" value="<?php echo esc_attr( sprintf( esc_html__( 'Message for %s', 'aqualuxe' ), $vendor_name ) ); ?>" required>
            </div>
            
            <div class="form-group mb-4">
                <label for="contact-message" class="block text-gray-700 font-medium mb-2"><?php esc_html_e( 'Message', 'aqualuxe' ); ?> <span class="required">*</span></label>
                <textarea id="contact-message" name="message" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" required></textarea>
            </div>
            
            <div class="form-response mb-4"></div>
            
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-6 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-primary-300 focus:ring-offset-2">
                <?php esc_html_e( 'Send Message', 'aqualuxe' ); ?>
            </button>
        </form>
    </div>
    <?php
}

/**
 * Vendor contact form AJAX handler
 */
function aqualuxe_vendor_contact_form_ajax() {
    if ( ! isset( $_POST['name'] ) || ! isset( $_POST['email'] ) || ! isset( $_POST['message'] ) || ! isset( $_POST['vendor_email'] ) || ! isset( $_POST['nonce'] ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Invalid request', 'aqualuxe' ) ) );
    }

    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_nonce' ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Security check failed', 'aqualuxe' ) ) );
    }

    $name = sanitize_text_field( wp_unslash( $_POST['name'] ) );
    $email = sanitize_email( wp_unslash( $_POST['email'] ) );
    $subject = sanitize_text_field( wp_unslash( $_POST['subject'] ) );
    $message = sanitize_textarea_field( wp_unslash( $_POST['message'] ) );
    $vendor_email = sanitize_email( wp_unslash( $_POST['vendor_email'] ) );

    // Validate email
    if ( ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Please enter a valid email address', 'aqualuxe' ) ) );
    }

    // Email subject
    $email_subject = $subject;

    // Email message
    $email_message = sprintf( esc_html__( 'Name: %s', 'aqualuxe' ), $name ) . "\r\n\r\n";
    $email_message .= sprintf( esc_html__( 'Email: %s', 'aqualuxe' ), $email ) . "\r\n\r\n";
    $email_message .= sprintf( esc_html__( 'Message: %s', 'aqualuxe' ), $message ) . "\r\n\r\n";
    $email_message .= sprintf( esc_html__( 'Sent from: %s', 'aqualuxe' ), home_url() );

    // Email headers
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $name . ' <' . $email . '>',
        'Reply-To: ' . $email,
    );

    // Send email
    $sent = wp_mail( $vendor_email, $email_subject, $email_message, $headers );

    if ( $sent ) {
        wp_send_json_success( array( 'message' => esc_html__( 'Your message has been sent successfully. The vendor will contact you soon.', 'aqualuxe' ) ) );
    } else {
        wp_send_json_error( array( 'message' => esc_html__( 'Failed to send your message. Please try again later.', 'aqualuxe' ) ) );
    }
}
add_action( 'wp_ajax_aqualuxe_vendor_contact', 'aqualuxe_vendor_contact_form_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_vendor_contact', 'aqualuxe_vendor_contact_form_ajax' );

/**
 * Add vendor information to order details
 *
 * @param WC_Order $order Order object
 */
function aqualuxe_add_vendor_info_to_order_details( $order ) {
    if ( ! aqualuxe_is_multi_vendor() ) {
        return;
    }

    $items = $order->get_items();
    $vendors = array();

    foreach ( $items as $item ) {
        $product_id = $item->get_product_id();
        $vendor = aqualuxe_get_vendor_from_product( $product_id );

        if ( $vendor ) {
            $vendor_id = aqualuxe_is_wcmp_active() ? $vendor->id : ( aqualuxe_is_wc_vendors_active() ? $vendor['user_id'] : $vendor->get_id() );
            
            if ( ! isset( $vendors[$vendor_id] ) ) {
                $vendors[$vendor_id] = array(
                    'name'  => aqualuxe_get_vendor_name( $vendor ),
                    'url'   => aqualuxe_get_vendor_shop_url( $vendor ),
                    'items' => array(),
                );
            }

            $vendors[$vendor_id]['items'][] = $item;
        }
    }

    if ( empty( $vendors ) ) {
        return;
    }

    ?>
    <div class="order-vendors mt-6 pt-6 border-t border-gray-200">
        <h3 class="text-lg font-bold mb-4"><?php esc_html_e( 'Order Vendors', 'aqualuxe' ); ?></h3>
        
        <div class="vendors-list space-y-4">
            <?php foreach ( $vendors as $vendor_id => $vendor_data ) : ?>
                <div class="vendor-info bg-gray-50 p-4 rounded-lg">
                    <h4 class="vendor-name text-lg font-bold mb-2">
                        <a href="<?php echo esc_url( $vendor_data['url'] ); ?>" class="text-primary-600 hover:text-primary-800 transition-colors">
                            <?php echo esc_html( $vendor_data['name'] ); ?>
                        </a>
                    </h4>
                    
                    <div class="vendor-items">
                        <h5 class="text-sm font-bold text-gray-600 mb-2"><?php esc_html_e( 'Products from this vendor:', 'aqualuxe' ); ?></h5>
                        <ul class="list-disc pl-6 space-y-1">
                            <?php foreach ( $vendor_data['items'] as $item ) : ?>
                                <li>
                                    <?php
                                    echo esc_html( $item->get_name() );
                                    
                                    if ( $item->get_quantity() > 1 ) {
                                        echo ' &times; ' . esc_html( $item->get_quantity() );
                                    }
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}
add_action( 'woocommerce_order_details_after_order_table', 'aqualuxe_add_vendor_info_to_order_details' );

/**
 * Add vendor information to order emails
 *
 * @param WC_Order $order Order object
 * @param bool     $sent_to_admin Whether the email is sent to admin
 * @param bool     $plain_text Whether the email is plain text
 */
function aqualuxe_add_vendor_info_to_order_email( $order, $sent_to_admin, $plain_text = false ) {
    if ( ! aqualuxe_is_multi_vendor() ) {
        return;
    }

    $items = $order->get_items();
    $vendors = array();

    foreach ( $items as $item ) {
        $product_id = $item->get_product_id();
        $vendor = aqualuxe_get_vendor_from_product( $product_id );

        if ( $vendor ) {
            $vendor_id = aqualuxe_is_wcmp_active() ? $vendor->id : ( aqualuxe_is_wc_vendors_active() ? $vendor['user_id'] : $vendor->get_id() );
            
            if ( ! isset( $vendors[$vendor_id] ) ) {
                $vendors[$vendor_id] = array(
                    'name'  => aqualuxe_get_vendor_name( $vendor ),
                    'url'   => aqualuxe_get_vendor_shop_url( $vendor ),
                    'items' => array(),
                );
            }

            $vendors[$vendor_id]['items'][] = $item;
        }
    }

    if ( empty( $vendors ) ) {
        return;
    }

    if ( $plain_text ) {
        echo "\n\n" . esc_html__( 'Order Vendors:', 'aqualuxe' ) . "\n\n";
        
        foreach ( $vendors as $vendor_id => $vendor_data ) {
            echo esc_html( $vendor_data['name'] ) . "\n";
            echo esc_html__( 'Products from this vendor:', 'aqualuxe' ) . "\n";
            
            foreach ( $vendor_data['items'] as $item ) {
                echo '- ' . esc_html( $item->get_name() );
                
                if ( $item->get_quantity() > 1 ) {
                    echo ' x ' . esc_html( $item->get_quantity() );
                }
                
                echo "\n";
            }
            
            echo "\n";
        }
    } else {
        ?>
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e5e5;">
            <h3 style="font-size: 16px; margin-bottom: 10px;"><?php esc_html_e( 'Order Vendors', 'aqualuxe' ); ?></h3>
            
            <div style="margin-top: 10px;">
                <?php foreach ( $vendors as $vendor_id => $vendor_data ) : ?>
                    <div style="margin-bottom: 15px; padding: 10px; background-color: #f8f8f8; border-radius: 4px;">
                        <h4 style="font-size: 14px; margin-top: 0; margin-bottom: 5px;">
                            <a href="<?php echo esc_url( $vendor_data['url'] ); ?>" style="color: #0073aa; text-decoration: none;">
                                <?php echo esc_html( $vendor_data['name'] ); ?>
                            </a>
                        </h4>
                        
                        <div>
                            <h5 style="font-size: 12px; color: #666; margin-top: 5px; margin-bottom: 5px;"><?php esc_html_e( 'Products from this vendor:', 'aqualuxe' ); ?></h5>
                            <ul style="margin: 0; padding-left: 20px;">
                                <?php foreach ( $vendor_data['items'] as $item ) : ?>
                                    <li>
                                        <?php
                                        echo esc_html( $item->get_name() );
                                        
                                        if ( $item->get_quantity() > 1 ) {
                                            echo ' &times; ' . esc_html( $item->get_quantity() );
                                        }
                                        ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
add_action( 'woocommerce_email_after_order_table', 'aqualuxe_add_vendor_info_to_order_email', 10, 3 );