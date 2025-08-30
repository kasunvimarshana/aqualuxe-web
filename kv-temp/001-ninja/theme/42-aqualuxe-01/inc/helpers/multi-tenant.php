<?php
/**
 * Multi-tenant support for AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Check if multi-tenant mode is enabled
 *
 * @return bool
 */
function aqualuxe_is_multi_tenant_enabled() {
    return apply_filters( 'aqualuxe_is_multi_tenant_enabled', get_theme_mod( 'aqualuxe_enable_multi_tenant', false ) );
}

/**
 * Get current tenant ID
 *
 * @return int|string|null
 */
function aqualuxe_get_current_tenant_id() {
    if ( ! aqualuxe_is_multi_tenant_enabled() ) {
        return null;
    }

    // Check for tenant ID in URL query parameter
    if ( isset( $_GET['tenant_id'] ) ) {
        return sanitize_text_field( wp_unslash( $_GET['tenant_id'] ) );
    }

    // Check for tenant ID in cookie
    if ( isset( $_COOKIE['aqualuxe_tenant_id'] ) ) {
        return sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_tenant_id'] ) );
    }

    // Check for tenant ID in subdomain
    $tenant_id = aqualuxe_get_tenant_id_from_subdomain();
    if ( $tenant_id ) {
        return $tenant_id;
    }

    // Default tenant ID
    return apply_filters( 'aqualuxe_default_tenant_id', get_theme_mod( 'aqualuxe_default_tenant_id', '1' ) );
}

/**
 * Get tenant ID from subdomain
 *
 * @return string|null
 */
function aqualuxe_get_tenant_id_from_subdomain() {
    $host = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
    
    if ( ! $host ) {
        return null;
    }

    $main_domain = get_theme_mod( 'aqualuxe_main_domain', '' );
    
    if ( ! $main_domain ) {
        return null;
    }

    // If the host is the main domain, return null
    if ( $host === $main_domain ) {
        return null;
    }

    // Extract subdomain from host
    $subdomain = str_replace( '.' . $main_domain, '', $host );
    
    // If subdomain is the same as host, it means there's no subdomain
    if ( $subdomain === $host ) {
        return null;
    }

    // Map subdomain to tenant ID
    $tenant_subdomains = aqualuxe_get_tenant_subdomains();
    
    foreach ( $tenant_subdomains as $tenant_id => $tenant_subdomain ) {
        if ( $tenant_subdomain === $subdomain ) {
            return $tenant_id;
        }
    }

    return null;
}

/**
 * Get tenant subdomains
 *
 * @return array
 */
function aqualuxe_get_tenant_subdomains() {
    $tenant_subdomains = get_option( 'aqualuxe_tenant_subdomains', array() );
    
    return apply_filters( 'aqualuxe_tenant_subdomains', $tenant_subdomains );
}

/**
 * Get tenant data
 *
 * @param int|string|null $tenant_id Tenant ID
 * @return array
 */
function aqualuxe_get_tenant_data( $tenant_id = null ) {
    if ( ! aqualuxe_is_multi_tenant_enabled() ) {
        return array();
    }

    if ( $tenant_id === null ) {
        $tenant_id = aqualuxe_get_current_tenant_id();
    }

    $tenants = aqualuxe_get_tenants();
    
    if ( isset( $tenants[$tenant_id] ) ) {
        return $tenants[$tenant_id];
    }

    return array();
}

/**
 * Get all tenants
 *
 * @return array
 */
function aqualuxe_get_tenants() {
    $tenants = get_option( 'aqualuxe_tenants', array() );
    
    return apply_filters( 'aqualuxe_tenants', $tenants );
}

/**
 * Get tenant name
 *
 * @param int|string|null $tenant_id Tenant ID
 * @return string
 */
function aqualuxe_get_tenant_name( $tenant_id = null ) {
    $tenant_data = aqualuxe_get_tenant_data( $tenant_id );
    
    if ( isset( $tenant_data['name'] ) ) {
        return $tenant_data['name'];
    }

    return get_bloginfo( 'name' );
}

/**
 * Get tenant logo
 *
 * @param int|string|null $tenant_id Tenant ID
 * @return string
 */
function aqualuxe_get_tenant_logo( $tenant_id = null ) {
    $tenant_data = aqualuxe_get_tenant_data( $tenant_id );
    
    if ( isset( $tenant_data['logo'] ) && $tenant_data['logo'] ) {
        return $tenant_data['logo'];
    }

    return get_theme_mod( 'custom_logo' );
}

/**
 * Get tenant primary color
 *
 * @param int|string|null $tenant_id Tenant ID
 * @return string
 */
function aqualuxe_get_tenant_primary_color( $tenant_id = null ) {
    $tenant_data = aqualuxe_get_tenant_data( $tenant_id );
    
    if ( isset( $tenant_data['primary_color'] ) && $tenant_data['primary_color'] ) {
        return $tenant_data['primary_color'];
    }

    return get_theme_mod( 'aqualuxe_primary_color', '#0891b2' );
}

/**
 * Get tenant secondary color
 *
 * @param int|string|null $tenant_id Tenant ID
 * @return string
 */
function aqualuxe_get_tenant_secondary_color( $tenant_id = null ) {
    $tenant_data = aqualuxe_get_tenant_data( $tenant_id );
    
    if ( isset( $tenant_data['secondary_color'] ) && $tenant_data['secondary_color'] ) {
        return $tenant_data['secondary_color'];
    }

    return get_theme_mod( 'aqualuxe_secondary_color', '#0e7490' );
}

/**
 * Get tenant accent color
 *
 * @param int|string|null $tenant_id Tenant ID
 * @return string
 */
function aqualuxe_get_tenant_accent_color( $tenant_id = null ) {
    $tenant_data = aqualuxe_get_tenant_data( $tenant_id );
    
    if ( isset( $tenant_data['accent_color'] ) && $tenant_data['accent_color'] ) {
        return $tenant_data['accent_color'];
    }

    return get_theme_mod( 'aqualuxe_accent_color', '#06b6d4' );
}

/**
 * Get tenant contact email
 *
 * @param int|string|null $tenant_id Tenant ID
 * @return string
 */
function aqualuxe_get_tenant_email( $tenant_id = null ) {
    $tenant_data = aqualuxe_get_tenant_data( $tenant_id );
    
    if ( isset( $tenant_data['email'] ) && $tenant_data['email'] ) {
        return $tenant_data['email'];
    }

    return get_theme_mod( 'aqualuxe_email', 'info@aqualuxe.com' );
}

/**
 * Get tenant phone number
 *
 * @param int|string|null $tenant_id Tenant ID
 * @return string
 */
function aqualuxe_get_tenant_phone( $tenant_id = null ) {
    $tenant_data = aqualuxe_get_tenant_data( $tenant_id );
    
    if ( isset( $tenant_data['phone'] ) && $tenant_data['phone'] ) {
        return $tenant_data['phone'];
    }

    return get_theme_mod( 'aqualuxe_phone_number', '+1 (555) 123-4567' );
}

/**
 * Get tenant address
 *
 * @param int|string|null $tenant_id Tenant ID
 * @return string
 */
function aqualuxe_get_tenant_address( $tenant_id = null ) {
    $tenant_data = aqualuxe_get_tenant_data( $tenant_id );
    
    if ( isset( $tenant_data['address'] ) && $tenant_data['address'] ) {
        return $tenant_data['address'];
    }

    return get_theme_mod( 'aqualuxe_address', '123 Water Street, Oceanview, CA 90210' );
}

/**
 * Get tenant social links
 *
 * @param int|string|null $tenant_id Tenant ID
 * @return array
 */
function aqualuxe_get_tenant_social_links( $tenant_id = null ) {
    $tenant_data = aqualuxe_get_tenant_data( $tenant_id );
    $social_links = array();
    
    if ( isset( $tenant_data['social_links'] ) && is_array( $tenant_data['social_links'] ) ) {
        return $tenant_data['social_links'];
    }

    // Default social links from theme mods
    $social_links['facebook'] = get_theme_mod( 'aqualuxe_facebook', '' );
    $social_links['twitter'] = get_theme_mod( 'aqualuxe_twitter', '' );
    $social_links['instagram'] = get_theme_mod( 'aqualuxe_instagram', '' );
    $social_links['pinterest'] = get_theme_mod( 'aqualuxe_pinterest', '' );
    $social_links['youtube'] = get_theme_mod( 'aqualuxe_youtube', '' );
    $social_links['linkedin'] = get_theme_mod( 'aqualuxe_linkedin', '' );

    return array_filter( $social_links );
}

/**
 * Get tenant footer text
 *
 * @param int|string|null $tenant_id Tenant ID
 * @return string
 */
function aqualuxe_get_tenant_footer_text( $tenant_id = null ) {
    $tenant_data = aqualuxe_get_tenant_data( $tenant_id );
    
    if ( isset( $tenant_data['footer_text'] ) && $tenant_data['footer_text'] ) {
        return $tenant_data['footer_text'];
    }

    return get_theme_mod( 'aqualuxe_footer_about', 'AquaLuxe offers premium water-themed products and services with elegance and sophistication. Our commitment to quality and sustainability sets us apart in the industry.' );
}

/**
 * Get tenant copyright text
 *
 * @param int|string|null $tenant_id Tenant ID
 * @return string
 */
function aqualuxe_get_tenant_copyright_text( $tenant_id = null ) {
    $tenant_data = aqualuxe_get_tenant_data( $tenant_id );
    
    if ( isset( $tenant_data['copyright_text'] ) && $tenant_data['copyright_text'] ) {
        return $tenant_data['copyright_text'];
    }

    return get_theme_mod( 'aqualuxe_copyright_text', '&copy; ' . date( 'Y' ) . ' AquaLuxe. All Rights Reserved.' );
}

/**
 * Get tenant custom CSS
 *
 * @param int|string|null $tenant_id Tenant ID
 * @return string
 */
function aqualuxe_get_tenant_custom_css( $tenant_id = null ) {
    $tenant_data = aqualuxe_get_tenant_data( $tenant_id );
    
    if ( isset( $tenant_data['custom_css'] ) && $tenant_data['custom_css'] ) {
        return $tenant_data['custom_css'];
    }

    return get_theme_mod( 'aqualuxe_custom_css', '' );
}

/**
 * Get tenant custom JavaScript
 *
 * @param int|string|null $tenant_id Tenant ID
 * @return string
 */
function aqualuxe_get_tenant_custom_js( $tenant_id = null ) {
    $tenant_data = aqualuxe_get_tenant_data( $tenant_id );
    
    if ( isset( $tenant_data['custom_js'] ) && $tenant_data['custom_js'] ) {
        return $tenant_data['custom_js'];
    }

    return get_theme_mod( 'aqualuxe_custom_js', '' );
}

/**
 * Get tenant Google Analytics code
 *
 * @param int|string|null $tenant_id Tenant ID
 * @return string
 */
function aqualuxe_get_tenant_google_analytics( $tenant_id = null ) {
    $tenant_data = aqualuxe_get_tenant_data( $tenant_id );
    
    if ( isset( $tenant_data['google_analytics'] ) && $tenant_data['google_analytics'] ) {
        return $tenant_data['google_analytics'];
    }

    return get_theme_mod( 'aqualuxe_google_analytics', '' );
}

/**
 * Get tenant Facebook Pixel code
 *
 * @param int|string|null $tenant_id Tenant ID
 * @return string
 */
function aqualuxe_get_tenant_facebook_pixel( $tenant_id = null ) {
    $tenant_data = aqualuxe_get_tenant_data( $tenant_id );
    
    if ( isset( $tenant_data['facebook_pixel'] ) && $tenant_data['facebook_pixel'] ) {
        return $tenant_data['facebook_pixel'];
    }

    return get_theme_mod( 'aqualuxe_facebook_pixel', '' );
}

/**
 * Get tenant products
 *
 * @param int|string|null $tenant_id Tenant ID
 * @return array
 */
function aqualuxe_get_tenant_products( $tenant_id = null ) {
    if ( ! aqualuxe_is_multi_tenant_enabled() || ! class_exists( 'WooCommerce' ) ) {
        return array();
    }

    if ( $tenant_id === null ) {
        $tenant_id = aqualuxe_get_current_tenant_id();
    }

    $tenant_data = aqualuxe_get_tenant_data( $tenant_id );
    
    if ( ! isset( $tenant_data['product_ids'] ) || ! is_array( $tenant_data['product_ids'] ) ) {
        return array();
    }

    return $tenant_data['product_ids'];
}

/**
 * Get tenant categories
 *
 * @param int|string|null $tenant_id Tenant ID
 * @return array
 */
function aqualuxe_get_tenant_categories( $tenant_id = null ) {
    if ( ! aqualuxe_is_multi_tenant_enabled() || ! class_exists( 'WooCommerce' ) ) {
        return array();
    }

    if ( $tenant_id === null ) {
        $tenant_id = aqualuxe_get_current_tenant_id();
    }

    $tenant_data = aqualuxe_get_tenant_data( $tenant_id );
    
    if ( ! isset( $tenant_data['category_ids'] ) || ! is_array( $tenant_data['category_ids'] ) ) {
        return array();
    }

    return $tenant_data['category_ids'];
}

/**
 * Filter products by tenant
 *
 * @param WP_Query $query Query object
 * @return WP_Query
 */
function aqualuxe_filter_products_by_tenant( $query ) {
    if ( ! aqualuxe_is_multi_tenant_enabled() || ! class_exists( 'WooCommerce' ) ) {
        return $query;
    }

    // Only filter product queries
    if ( ! $query->is_main_query() || ! ( is_shop() || is_product_category() || is_product_tag() ) ) {
        return $query;
    }

    $tenant_id = aqualuxe_get_current_tenant_id();
    $tenant_products = aqualuxe_get_tenant_products( $tenant_id );
    $tenant_categories = aqualuxe_get_tenant_categories( $tenant_id );

    // If tenant has specific products
    if ( ! empty( $tenant_products ) ) {
        $query->set( 'post__in', $tenant_products );
    }
    // If tenant has specific categories
    elseif ( ! empty( $tenant_categories ) ) {
        $tax_query = $query->get( 'tax_query', array() );
        
        $tax_query[] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => $tenant_categories,
            'operator' => 'IN',
        );
        
        $query->set( 'tax_query', $tax_query );
    }

    return $query;
}
add_action( 'pre_get_posts', 'aqualuxe_filter_products_by_tenant' );

/**
 * Filter product categories by tenant
 *
 * @param array $args Query arguments
 * @return array
 */
function aqualuxe_filter_product_categories_by_tenant( $args ) {
    if ( ! aqualuxe_is_multi_tenant_enabled() || ! class_exists( 'WooCommerce' ) ) {
        return $args;
    }

    $tenant_id = aqualuxe_get_current_tenant_id();
    $tenant_categories = aqualuxe_get_tenant_categories( $tenant_id );

    if ( ! empty( $tenant_categories ) ) {
        $args['include'] = $tenant_categories;
    }

    return $args;
}
add_filter( 'woocommerce_product_categories_widget_args', 'aqualuxe_filter_product_categories_by_tenant' );

/**
 * Add tenant class to body
 *
 * @param array $classes Body classes
 * @return array
 */
function aqualuxe_add_tenant_body_class( $classes ) {
    if ( aqualuxe_is_multi_tenant_enabled() ) {
        $tenant_id = aqualuxe_get_current_tenant_id();
        $classes[] = 'tenant-' . sanitize_html_class( $tenant_id );
    }
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_add_tenant_body_class' );

/**
 * Add tenant CSS to head
 */
function aqualuxe_add_tenant_css() {
    if ( ! aqualuxe_is_multi_tenant_enabled() ) {
        return;
    }

    $tenant_id = aqualuxe_get_current_tenant_id();
    $primary_color = aqualuxe_get_tenant_primary_color( $tenant_id );
    $secondary_color = aqualuxe_get_tenant_secondary_color( $tenant_id );
    $accent_color = aqualuxe_get_tenant_accent_color( $tenant_id );
    $custom_css = aqualuxe_get_tenant_custom_css( $tenant_id );

    $css = "
        :root {
            --primary-color: {$primary_color};
            --secondary-color: {$secondary_color};
            --accent-color: {$accent_color};
        }

        {$custom_css}
    ";

    wp_add_inline_style( 'aqualuxe-style', $css );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_add_tenant_css', 20 );

/**
 * Add tenant JavaScript to footer
 */
function aqualuxe_add_tenant_js() {
    if ( ! aqualuxe_is_multi_tenant_enabled() ) {
        return;
    }

    $tenant_id = aqualuxe_get_current_tenant_id();
    $custom_js = aqualuxe_get_tenant_custom_js( $tenant_id );

    if ( $custom_js ) {
        echo '<script>' . $custom_js . '</script>';
    }
}
add_action( 'wp_footer', 'aqualuxe_add_tenant_js', 20 );

/**
 * Add tenant Google Analytics to head
 */
function aqualuxe_add_tenant_google_analytics() {
    if ( ! aqualuxe_is_multi_tenant_enabled() ) {
        return;
    }

    $tenant_id = aqualuxe_get_current_tenant_id();
    $google_analytics = aqualuxe_get_tenant_google_analytics( $tenant_id );

    if ( $google_analytics ) {
        echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . esc_attr( $google_analytics ) . '"></script>';
        echo '<script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag("js", new Date());
            gtag("config", "' . esc_attr( $google_analytics ) . '");
        </script>';
    }
}
add_action( 'wp_head', 'aqualuxe_add_tenant_google_analytics', 10 );

/**
 * Add tenant Facebook Pixel to head
 */
function aqualuxe_add_tenant_facebook_pixel() {
    if ( ! aqualuxe_is_multi_tenant_enabled() ) {
        return;
    }

    $tenant_id = aqualuxe_get_current_tenant_id();
    $facebook_pixel = aqualuxe_get_tenant_facebook_pixel( $tenant_id );

    if ( $facebook_pixel ) {
        echo '<script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version="2.0";
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,"script",
            "https://connect.facebook.net/en_US/fbevents.js");
            fbq("init", "' . esc_attr( $facebook_pixel ) . '");
            fbq("track", "PageView");
        </script>
        <noscript>
            <img height="1" width="1" style="display:none" 
                src="https://www.facebook.com/tr?id=' . esc_attr( $facebook_pixel ) . '&ev=PageView&noscript=1"/>
        </noscript>';
    }
}
add_action( 'wp_head', 'aqualuxe_add_tenant_facebook_pixel', 10 );

/**
 * Filter site title by tenant
 *
 * @param string $title Site title
 * @return string
 */
function aqualuxe_filter_site_title( $title ) {
    if ( ! aqualuxe_is_multi_tenant_enabled() ) {
        return $title;
    }

    $tenant_id = aqualuxe_get_current_tenant_id();
    $tenant_name = aqualuxe_get_tenant_name( $tenant_id );

    if ( $tenant_name ) {
        return $tenant_name;
    }

    return $title;
}
add_filter( 'option_blogname', 'aqualuxe_filter_site_title' );

/**
 * Filter site logo by tenant
 *
 * @param int $custom_logo_id Custom logo ID
 * @return int
 */
function aqualuxe_filter_site_logo( $custom_logo_id ) {
    if ( ! aqualuxe_is_multi_tenant_enabled() ) {
        return $custom_logo_id;
    }

    $tenant_id = aqualuxe_get_current_tenant_id();
    $tenant_logo = aqualuxe_get_tenant_logo( $tenant_id );

    if ( $tenant_logo ) {
        return $tenant_logo;
    }

    return $custom_logo_id;
}
add_filter( 'theme_mod_custom_logo', 'aqualuxe_filter_site_logo' );

/**
 * Filter email from address by tenant
 *
 * @param string $email_from Email from address
 * @return string
 */
function aqualuxe_filter_email_from( $email_from ) {
    if ( ! aqualuxe_is_multi_tenant_enabled() ) {
        return $email_from;
    }

    $tenant_id = aqualuxe_get_current_tenant_id();
    $tenant_email = aqualuxe_get_tenant_email( $tenant_id );

    if ( $tenant_email ) {
        return $tenant_email;
    }

    return $email_from;
}
add_filter( 'wp_mail_from', 'aqualuxe_filter_email_from' );

/**
 * Filter email from name by tenant
 *
 * @param string $email_from_name Email from name
 * @return string
 */
function aqualuxe_filter_email_from_name( $email_from_name ) {
    if ( ! aqualuxe_is_multi_tenant_enabled() ) {
        return $email_from_name;
    }

    $tenant_id = aqualuxe_get_current_tenant_id();
    $tenant_name = aqualuxe_get_tenant_name( $tenant_id );

    if ( $tenant_name ) {
        return $tenant_name;
    }

    return $email_from_name;
}
add_filter( 'wp_mail_from_name', 'aqualuxe_filter_email_from_name' );

/**
 * Add tenant information to AJAX requests
 *
 * @param array $data AJAX data
 * @return array
 */
function aqualuxe_add_tenant_to_ajax( $data ) {
    if ( aqualuxe_is_multi_tenant_enabled() ) {
        $data['tenant_id'] = aqualuxe_get_current_tenant_id();
    }
    
    return $data;
}
add_filter( 'aqualuxe_localize_script_data', 'aqualuxe_add_tenant_to_ajax' );

/**
 * Add tenant parameter to AJAX URL
 *
 * @param string $url AJAX URL
 * @return string
 */
function aqualuxe_add_tenant_to_ajax_url( $url ) {
    if ( aqualuxe_is_multi_tenant_enabled() ) {
        $url = add_query_arg( 'tenant_id', aqualuxe_get_current_tenant_id(), $url );
    }
    
    return $url;
}
add_filter( 'aqualuxe_ajax_url', 'aqualuxe_add_tenant_to_ajax_url' );

/**
 * Add tenant meta tag to head
 */
function aqualuxe_add_tenant_meta() {
    if ( aqualuxe_is_multi_tenant_enabled() ) {
        $tenant_id = aqualuxe_get_current_tenant_id();
        echo '<meta name="tenant-id" content="' . esc_attr( $tenant_id ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_add_tenant_meta' );

/**
 * Add tenant information to order meta
 *
 * @param int $order_id Order ID
 */
function aqualuxe_add_tenant_to_order_meta( $order_id ) {
    if ( ! aqualuxe_is_multi_tenant_enabled() || ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    $tenant_id = aqualuxe_get_current_tenant_id();
    update_post_meta( $order_id, '_aqualuxe_tenant_id', $tenant_id );
}
add_action( 'woocommerce_checkout_update_order_meta', 'aqualuxe_add_tenant_to_order_meta' );

/**
 * Display tenant information in order admin
 *
 * @param int $order_id Order ID
 */
function aqualuxe_display_tenant_in_order_admin( $order_id ) {
    if ( ! aqualuxe_is_multi_tenant_enabled() || ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    $tenant_id = get_post_meta( $order_id, '_aqualuxe_tenant_id', true );
    
    if ( $tenant_id ) {
        $tenant_name = aqualuxe_get_tenant_name( $tenant_id );
        
        echo '<p class="form-field form-field-wide">';
        printf(
            /* translators: %1$s: Tenant ID, %2$s: Tenant name */
            esc_html__( 'Tenant: %1$s (%2$s)', 'aqualuxe' ),
            '<strong>' . esc_html( $tenant_id ) . '</strong>',
            '<strong>' . esc_html( $tenant_name ) . '</strong>'
        );
        echo '</p>';
    }
}
add_action( 'woocommerce_admin_order_data_after_order_details', 'aqualuxe_display_tenant_in_order_admin' );

/**
 * Filter orders by tenant in admin
 *
 * @param WP_Query $query Query object
 * @return WP_Query
 */
function aqualuxe_filter_admin_orders_by_tenant( $query ) {
    if ( ! aqualuxe_is_multi_tenant_enabled() || ! class_exists( 'WooCommerce' ) ) {
        return $query;
    }

    if ( ! is_admin() ) {
        return $query;
    }

    global $pagenow, $typenow;

    if ( 'edit.php' !== $pagenow || 'shop_order' !== $typenow ) {
        return $query;
    }

    if ( ! isset( $_GET['tenant_id'] ) ) {
        return $query;
    }

    $tenant_id = sanitize_text_field( wp_unslash( $_GET['tenant_id'] ) );

    if ( ! $tenant_id ) {
        return $query;
    }

    $meta_query = $query->get( 'meta_query', array() );
    
    $meta_query[] = array(
        'key'     => '_aqualuxe_tenant_id',
        'value'   => $tenant_id,
        'compare' => '=',
    );
    
    $query->set( 'meta_query', $meta_query );

    return $query;
}
add_action( 'pre_get_posts', 'aqualuxe_filter_admin_orders_by_tenant' );

/**
 * Add tenant filter to orders admin
 */
function aqualuxe_add_tenant_filter_to_orders() {
    if ( ! aqualuxe_is_multi_tenant_enabled() || ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    global $typenow;

    if ( 'shop_order' !== $typenow ) {
        return;
    }

    $tenants = aqualuxe_get_tenants();
    $current_tenant = isset( $_GET['tenant_id'] ) ? sanitize_text_field( wp_unslash( $_GET['tenant_id'] ) ) : '';
    ?>
    <select name="tenant_id" id="filter-by-tenant">
        <option value=""><?php esc_html_e( 'All tenants', 'aqualuxe' ); ?></option>
        <?php foreach ( $tenants as $tenant_id => $tenant_data ) : ?>
            <option value="<?php echo esc_attr( $tenant_id ); ?>" <?php selected( $current_tenant, $tenant_id ); ?>>
                <?php echo esc_html( $tenant_data['name'] ); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}
add_action( 'restrict_manage_posts', 'aqualuxe_add_tenant_filter_to_orders' );

/**
 * Add multi-tenant settings to customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object
 */
function aqualuxe_multi_tenant_customizer( $wp_customize ) {
    // Add Multi-Tenant Section
    $wp_customize->add_section(
        'aqualuxe_multi_tenant',
        array(
            'title'       => esc_html__( 'Multi-Tenant', 'aqualuxe' ),
            'description' => esc_html__( 'Configure multi-tenant settings', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 90,
        )
    );

    // Enable Multi-Tenant
    $wp_customize->add_setting(
        'aqualuxe_enable_multi_tenant',
        array(
            'default'           => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_multi_tenant',
        array(
            'label'       => esc_html__( 'Enable Multi-Tenant', 'aqualuxe' ),
            'description' => esc_html__( 'Enable multi-tenant functionality', 'aqualuxe' ),
            'section'     => 'aqualuxe_multi_tenant',
            'type'        => 'checkbox',
        )
    );

    // Default Tenant ID
    $wp_customize->add_setting(
        'aqualuxe_default_tenant_id',
        array(
            'default'           => '1',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_default_tenant_id',
        array(
            'label'       => esc_html__( 'Default Tenant ID', 'aqualuxe' ),
            'description' => esc_html__( 'Default tenant ID to use when no tenant is specified', 'aqualuxe' ),
            'section'     => 'aqualuxe_multi_tenant',
            'type'        => 'text',
        )
    );

    // Main Domain
    $wp_customize->add_setting(
        'aqualuxe_main_domain',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_main_domain',
        array(
            'label'       => esc_html__( 'Main Domain', 'aqualuxe' ),
            'description' => esc_html__( 'Main domain for subdomain-based multi-tenant (e.g., example.com)', 'aqualuxe' ),
            'section'     => 'aqualuxe_multi_tenant',
            'type'        => 'text',
        )
    );
}
add_action( 'customize_register', 'aqualuxe_multi_tenant_customizer' );

/**
 * Add multi-tenant admin page
 */
function aqualuxe_add_multi_tenant_admin_page() {
    add_menu_page(
        esc_html__( 'Multi-Tenant', 'aqualuxe' ),
        esc_html__( 'Multi-Tenant', 'aqualuxe' ),
        'manage_options',
        'aqualuxe-multi-tenant',
        'aqualuxe_multi_tenant_admin_page',
        'dashicons-building',
        30
    );
}
add_action( 'admin_menu', 'aqualuxe_add_multi_tenant_admin_page' );

/**
 * Multi-tenant admin page content
 */
function aqualuxe_multi_tenant_admin_page() {
    // Check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Save tenant data
    if ( isset( $_POST['aqualuxe_save_tenant'] ) && isset( $_POST['tenant_id'] ) && isset( $_POST['tenant_name'] ) ) {
        if ( ! isset( $_POST['aqualuxe_multi_tenant_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aqualuxe_multi_tenant_nonce'] ) ), 'aqualuxe_multi_tenant' ) ) {
            wp_die( esc_html__( 'Security check failed', 'aqualuxe' ) );
        }

        $tenant_id = sanitize_text_field( wp_unslash( $_POST['tenant_id'] ) );
        $tenant_name = sanitize_text_field( wp_unslash( $_POST['tenant_name'] ) );
        $tenant_logo = isset( $_POST['tenant_logo'] ) ? absint( $_POST['tenant_logo'] ) : '';
        $tenant_primary_color = isset( $_POST['tenant_primary_color'] ) ? sanitize_hex_color( wp_unslash( $_POST['tenant_primary_color'] ) ) : '';
        $tenant_secondary_color = isset( $_POST['tenant_secondary_color'] ) ? sanitize_hex_color( wp_unslash( $_POST['tenant_secondary_color'] ) ) : '';
        $tenant_accent_color = isset( $_POST['tenant_accent_color'] ) ? sanitize_hex_color( wp_unslash( $_POST['tenant_accent_color'] ) ) : '';
        $tenant_email = isset( $_POST['tenant_email'] ) ? sanitize_email( wp_unslash( $_POST['tenant_email'] ) ) : '';
        $tenant_phone = isset( $_POST['tenant_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['tenant_phone'] ) ) : '';
        $tenant_address = isset( $_POST['tenant_address'] ) ? sanitize_text_field( wp_unslash( $_POST['tenant_address'] ) ) : '';
        $tenant_footer_text = isset( $_POST['tenant_footer_text'] ) ? wp_kses_post( wp_unslash( $_POST['tenant_footer_text'] ) ) : '';
        $tenant_copyright_text = isset( $_POST['tenant_copyright_text'] ) ? wp_kses_post( wp_unslash( $_POST['tenant_copyright_text'] ) ) : '';
        $tenant_custom_css = isset( $_POST['tenant_custom_css'] ) ? wp_strip_all_tags( wp_unslash( $_POST['tenant_custom_css'] ) ) : '';
        $tenant_custom_js = isset( $_POST['tenant_custom_js'] ) ? wp_strip_all_tags( wp_unslash( $_POST['tenant_custom_js'] ) ) : '';
        $tenant_google_analytics = isset( $_POST['tenant_google_analytics'] ) ? sanitize_text_field( wp_unslash( $_POST['tenant_google_analytics'] ) ) : '';
        $tenant_facebook_pixel = isset( $_POST['tenant_facebook_pixel'] ) ? sanitize_text_field( wp_unslash( $_POST['tenant_facebook_pixel'] ) ) : '';
        $tenant_subdomain = isset( $_POST['tenant_subdomain'] ) ? sanitize_text_field( wp_unslash( $_POST['tenant_subdomain'] ) ) : '';

        // Social links
        $tenant_social_links = array();
        $tenant_social_links['facebook'] = isset( $_POST['tenant_facebook'] ) ? esc_url_raw( wp_unslash( $_POST['tenant_facebook'] ) ) : '';
        $tenant_social_links['twitter'] = isset( $_POST['tenant_twitter'] ) ? esc_url_raw( wp_unslash( $_POST['tenant_twitter'] ) ) : '';
        $tenant_social_links['instagram'] = isset( $_POST['tenant_instagram'] ) ? esc_url_raw( wp_unslash( $_POST['tenant_instagram'] ) ) : '';
        $tenant_social_links['pinterest'] = isset( $_POST['tenant_pinterest'] ) ? esc_url_raw( wp_unslash( $_POST['tenant_pinterest'] ) ) : '';
        $tenant_social_links['youtube'] = isset( $_POST['tenant_youtube'] ) ? esc_url_raw( wp_unslash( $_POST['tenant_youtube'] ) ) : '';
        $tenant_social_links['linkedin'] = isset( $_POST['tenant_linkedin'] ) ? esc_url_raw( wp_unslash( $_POST['tenant_linkedin'] ) ) : '';

        // Product IDs
        $tenant_product_ids = array();
        if ( isset( $_POST['tenant_product_ids'] ) && is_array( $_POST['tenant_product_ids'] ) ) {
            foreach ( $_POST['tenant_product_ids'] as $product_id ) {
                $tenant_product_ids[] = absint( $product_id );
            }
        }

        // Category IDs
        $tenant_category_ids = array();
        if ( isset( $_POST['tenant_category_ids'] ) && is_array( $_POST['tenant_category_ids'] ) ) {
            foreach ( $_POST['tenant_category_ids'] as $category_id ) {
                $tenant_category_ids[] = absint( $category_id );
            }
        }

        // Get existing tenants
        $tenants = get_option( 'aqualuxe_tenants', array() );

        // Add or update tenant
        $tenants[$tenant_id] = array(
            'name'            => $tenant_name,
            'logo'            => $tenant_logo,
            'primary_color'   => $tenant_primary_color,
            'secondary_color' => $tenant_secondary_color,
            'accent_color'    => $tenant_accent_color,
            'email'           => $tenant_email,
            'phone'           => $tenant_phone,
            'address'         => $tenant_address,
            'social_links'    => $tenant_social_links,
            'footer_text'     => $tenant_footer_text,
            'copyright_text'  => $tenant_copyright_text,
            'custom_css'      => $tenant_custom_css,
            'custom_js'       => $tenant_custom_js,
            'google_analytics' => $tenant_google_analytics,
            'facebook_pixel'  => $tenant_facebook_pixel,
            'product_ids'     => $tenant_product_ids,
            'category_ids'    => $tenant_category_ids,
        );

        // Update tenants
        update_option( 'aqualuxe_tenants', $tenants );

        // Update tenant subdomains
        if ( $tenant_subdomain ) {
            $tenant_subdomains = get_option( 'aqualuxe_tenant_subdomains', array() );
            $tenant_subdomains[$tenant_id] = $tenant_subdomain;
            update_option( 'aqualuxe_tenant_subdomains', $tenant_subdomains );
        }

        // Show success message
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Tenant saved successfully.', 'aqualuxe' ) . '</p></div>';
    }

    // Delete tenant
    if ( isset( $_GET['action'] ) && 'delete' === $_GET['action'] && isset( $_GET['tenant_id'] ) ) {
        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'delete_tenant_' . sanitize_text_field( wp_unslash( $_GET['tenant_id'] ) ) ) ) {
            wp_die( esc_html__( 'Security check failed', 'aqualuxe' ) );
        }

        $tenant_id = sanitize_text_field( wp_unslash( $_GET['tenant_id'] ) );
        $tenants = get_option( 'aqualuxe_tenants', array() );

        if ( isset( $tenants[$tenant_id] ) ) {
            unset( $tenants[$tenant_id] );
            update_option( 'aqualuxe_tenants', $tenants );

            // Remove tenant subdomain
            $tenant_subdomains = get_option( 'aqualuxe_tenant_subdomains', array() );
            if ( isset( $tenant_subdomains[$tenant_id] ) ) {
                unset( $tenant_subdomains[$tenant_id] );
                update_option( 'aqualuxe_tenant_subdomains', $tenant_subdomains );
            }

            // Show success message
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Tenant deleted successfully.', 'aqualuxe' ) . '</p></div>';
        }
    }

    // Get tenants
    $tenants = get_option( 'aqualuxe_tenants', array() );
    $tenant_subdomains = get_option( 'aqualuxe_tenant_subdomains', array() );

    // Get tenant data for editing
    $edit_tenant_id = isset( $_GET['action'] ) && 'edit' === $_GET['action'] && isset( $_GET['tenant_id'] ) ? sanitize_text_field( wp_unslash( $_GET['tenant_id'] ) ) : '';
    $edit_tenant = array();

    if ( $edit_tenant_id && isset( $tenants[$edit_tenant_id] ) ) {
        $edit_tenant = $tenants[$edit_tenant_id];
    }

    // Admin page content
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

        <?php if ( ! get_theme_mod( 'aqualuxe_enable_multi_tenant', false ) ) : ?>
            <div class="notice notice-warning">
                <p><?php esc_html_e( 'Multi-tenant functionality is currently disabled. Enable it in the Customizer under "AquaLuxe Theme Options" > "Multi-Tenant".', 'aqualuxe' ); ?></p>
            </div>
        <?php endif; ?>

        <div class="aqualuxe-admin-content">
            <div class="aqualuxe-admin-main">
                <div class="aqualuxe-admin-card">
                    <h2><?php esc_html_e( 'Tenants', 'aqualuxe' ); ?></h2>
                    
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php esc_html_e( 'ID', 'aqualuxe' ); ?></th>
                                <th><?php esc_html_e( 'Name', 'aqualuxe' ); ?></th>
                                <th><?php esc_html_e( 'Subdomain', 'aqualuxe' ); ?></th>
                                <th><?php esc_html_e( 'Actions', 'aqualuxe' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ( empty( $tenants ) ) : ?>
                                <tr>
                                    <td colspan="4"><?php esc_html_e( 'No tenants found.', 'aqualuxe' ); ?></td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ( $tenants as $tenant_id => $tenant ) : ?>
                                    <tr>
                                        <td><?php echo esc_html( $tenant_id ); ?></td>
                                        <td><?php echo esc_html( $tenant['name'] ); ?></td>
                                        <td>
                                            <?php
                                            if ( isset( $tenant_subdomains[$tenant_id] ) ) {
                                                echo esc_html( $tenant_subdomains[$tenant_id] );
                                            } else {
                                                echo '&mdash;';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=aqualuxe-multi-tenant&action=edit&tenant_id=' . $tenant_id ) ); ?>" class="button button-small"><?php esc_html_e( 'Edit', 'aqualuxe' ); ?></a>
                                            <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=aqualuxe-multi-tenant&action=delete&tenant_id=' . $tenant_id ), 'delete_tenant_' . $tenant_id ) ); ?>" class="button button-small button-link-delete" onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to delete this tenant?', 'aqualuxe' ); ?>');"><?php esc_html_e( 'Delete', 'aqualuxe' ); ?></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="aqualuxe-admin-card">
                    <h2><?php echo $edit_tenant_id ? esc_html__( 'Edit Tenant', 'aqualuxe' ) : esc_html__( 'Add Tenant', 'aqualuxe' ); ?></h2>
                    
                    <form method="post" action="">
                        <?php wp_nonce_field( 'aqualuxe_multi_tenant', 'aqualuxe_multi_tenant_nonce' ); ?>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row"><label for="tenant_id"><?php esc_html_e( 'Tenant ID', 'aqualuxe' ); ?></label></th>
                                <td>
                                    <input type="text" name="tenant_id" id="tenant_id" value="<?php echo esc_attr( $edit_tenant_id ); ?>" class="regular-text" <?php echo $edit_tenant_id ? 'readonly' : ''; ?> required>
                                    <p class="description"><?php esc_html_e( 'Unique identifier for the tenant.', 'aqualuxe' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="tenant_name"><?php esc_html_e( 'Tenant Name', 'aqualuxe' ); ?></label></th>
                                <td>
                                    <input type="text" name="tenant_name" id="tenant_name" value="<?php echo isset( $edit_tenant['name'] ) ? esc_attr( $edit_tenant['name'] ) : ''; ?>" class="regular-text" required>
                                    <p class="description"><?php esc_html_e( 'Name of the tenant.', 'aqualuxe' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="tenant_subdomain"><?php esc_html_e( 'Subdomain', 'aqualuxe' ); ?></label></th>
                                <td>
                                    <input type="text" name="tenant_subdomain" id="tenant_subdomain" value="<?php echo isset( $tenant_subdomains[$edit_tenant_id] ) ? esc_attr( $tenant_subdomains[$edit_tenant_id] ) : ''; ?>" class="regular-text">
                                    <p class="description"><?php esc_html_e( 'Subdomain for the tenant (e.g., tenant1 for tenant1.example.com).', 'aqualuxe' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="tenant_logo"><?php esc_html_e( 'Logo', 'aqualuxe' ); ?></label></th>
                                <td>
                                    <input type="number" name="tenant_logo" id="tenant_logo" value="<?php echo isset( $edit_tenant['logo'] ) ? esc_attr( $edit_tenant['logo'] ) : ''; ?>" class="regular-text">
                                    <p class="description"><?php esc_html_e( 'Logo attachment ID.', 'aqualuxe' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="tenant_primary_color"><?php esc_html_e( 'Primary Color', 'aqualuxe' ); ?></label></th>
                                <td>
                                    <input type="text" name="tenant_primary_color" id="tenant_primary_color" value="<?php echo isset( $edit_tenant['primary_color'] ) ? esc_attr( $edit_tenant['primary_color'] ) : ''; ?>" class="color-picker">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="tenant_secondary_color"><?php esc_html_e( 'Secondary Color', 'aqualuxe' ); ?></label></th>
                                <td>
                                    <input type="text" name="tenant_secondary_color" id="tenant_secondary_color" value="<?php echo isset( $edit_tenant['secondary_color'] ) ? esc_attr( $edit_tenant['secondary_color'] ) : ''; ?>" class="color-picker">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="tenant_accent_color"><?php esc_html_e( 'Accent Color', 'aqualuxe' ); ?></label></th>
                                <td>
                                    <input type="text" name="tenant_accent_color" id="tenant_accent_color" value="<?php echo isset( $edit_tenant['accent_color'] ) ? esc_attr( $edit_tenant['accent_color'] ) : ''; ?>" class="color-picker">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="tenant_email"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></label></th>
                                <td>
                                    <input type="email" name="tenant_email" id="tenant_email" value="<?php echo isset( $edit_tenant['email'] ) ? esc_attr( $edit_tenant['email'] ) : ''; ?>" class="regular-text">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="tenant_phone"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></label></th>
                                <td>
                                    <input type="text" name="tenant_phone" id="tenant_phone" value="<?php echo isset( $edit_tenant['phone'] ) ? esc_attr( $edit_tenant['phone'] ) : ''; ?>" class="regular-text">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="tenant_address"><?php esc_html_e( 'Address', 'aqualuxe' ); ?></label></th>
                                <td>
                                    <input type="text" name="tenant_address" id="tenant_address" value="<?php echo isset( $edit_tenant['address'] ) ? esc_attr( $edit_tenant['address'] ) : ''; ?>" class="regular-text">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e( 'Social Links', 'aqualuxe' ); ?></th>
                                <td>
                                    <p>
                                        <label for="tenant_facebook"><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></label><br>
                                        <input type="url" name="tenant_facebook" id="tenant_facebook" value="<?php echo isset( $edit_tenant['social_links']['facebook'] ) ? esc_attr( $edit_tenant['social_links']['facebook'] ) : ''; ?>" class="regular-text">
                                    </p>
                                    <p>
                                        <label for="tenant_twitter"><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></label><br>
                                        <input type="url" name="tenant_twitter" id="tenant_twitter" value="<?php echo isset( $edit_tenant['social_links']['twitter'] ) ? esc_attr( $edit_tenant['social_links']['twitter'] ) : ''; ?>" class="regular-text">
                                    </p>
                                    <p>
                                        <label for="tenant_instagram"><?php esc_html_e( 'Instagram', 'aqualuxe' ); ?></label><br>
                                        <input type="url" name="tenant_instagram" id="tenant_instagram" value="<?php echo isset( $edit_tenant['social_links']['instagram'] ) ? esc_attr( $edit_tenant['social_links']['instagram'] ) : ''; ?>" class="regular-text">
                                    </p>
                                    <p>
                                        <label for="tenant_pinterest"><?php esc_html_e( 'Pinterest', 'aqualuxe' ); ?></label><br>
                                        <input type="url" name="tenant_pinterest" id="tenant_pinterest" value="<?php echo isset( $edit_tenant['social_links']['pinterest'] ) ? esc_attr( $edit_tenant['social_links']['pinterest'] ) : ''; ?>" class="regular-text">
                                    </p>
                                    <p>
                                        <label for="tenant_youtube"><?php esc_html_e( 'YouTube', 'aqualuxe' ); ?></label><br>
                                        <input type="url" name="tenant_youtube" id="tenant_youtube" value="<?php echo isset( $edit_tenant['social_links']['youtube'] ) ? esc_attr( $edit_tenant['social_links']['youtube'] ) : ''; ?>" class="regular-text">
                                    </p>
                                    <p>
                                        <label for="tenant_linkedin"><?php esc_html_e( 'LinkedIn', 'aqualuxe' ); ?></label><br>
                                        <input type="url" name="tenant_linkedin" id="tenant_linkedin" value="<?php echo isset( $edit_tenant['social_links']['linkedin'] ) ? esc_attr( $edit_tenant['social_links']['linkedin'] ) : ''; ?>" class="regular-text">
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="tenant_footer_text"><?php esc_html_e( 'Footer Text', 'aqualuxe' ); ?></label></th>
                                <td>
                                    <textarea name="tenant_footer_text" id="tenant_footer_text" rows="5" class="large-text"><?php echo isset( $edit_tenant['footer_text'] ) ? esc_textarea( $edit_tenant['footer_text'] ) : ''; ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="tenant_copyright_text"><?php esc_html_e( 'Copyright Text', 'aqualuxe' ); ?></label></th>
                                <td>
                                    <textarea name="tenant_copyright_text" id="tenant_copyright_text" rows="3" class="large-text"><?php echo isset( $edit_tenant['copyright_text'] ) ? esc_textarea( $edit_tenant['copyright_text'] ) : ''; ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="tenant_custom_css"><?php esc_html_e( 'Custom CSS', 'aqualuxe' ); ?></label></th>
                                <td>
                                    <textarea name="tenant_custom_css" id="tenant_custom_css" rows="10" class="large-text code"><?php echo isset( $edit_tenant['custom_css'] ) ? esc_textarea( $edit_tenant['custom_css'] ) : ''; ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="tenant_custom_js"><?php esc_html_e( 'Custom JavaScript', 'aqualuxe' ); ?></label></th>
                                <td>
                                    <textarea name="tenant_custom_js" id="tenant_custom_js" rows="10" class="large-text code"><?php echo isset( $edit_tenant['custom_js'] ) ? esc_textarea( $edit_tenant['custom_js'] ) : ''; ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="tenant_google_analytics"><?php esc_html_e( 'Google Analytics', 'aqualuxe' ); ?></label></th>
                                <td>
                                    <input type="text" name="tenant_google_analytics" id="tenant_google_analytics" value="<?php echo isset( $edit_tenant['google_analytics'] ) ? esc_attr( $edit_tenant['google_analytics'] ) : ''; ?>" class="regular-text">
                                    <p class="description"><?php esc_html_e( 'Google Analytics tracking ID (e.g., UA-XXXXXXXX-X or G-XXXXXXXXXX).', 'aqualuxe' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="tenant_facebook_pixel"><?php esc_html_e( 'Facebook Pixel', 'aqualuxe' ); ?></label></th>
                                <td>
                                    <input type="text" name="tenant_facebook_pixel" id="tenant_facebook_pixel" value="<?php echo isset( $edit_tenant['facebook_pixel'] ) ? esc_attr( $edit_tenant['facebook_pixel'] ) : ''; ?>" class="regular-text">
                                    <p class="description"><?php esc_html_e( 'Facebook Pixel ID.', 'aqualuxe' ); ?></p>
                                </td>
                            </tr>
                            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                                <tr>
                                    <th scope="row"><label for="tenant_product_ids"><?php esc_html_e( 'Product IDs', 'aqualuxe' ); ?></label></th>
                                    <td>
                                        <input type="text" name="tenant_product_ids" id="tenant_product_ids" value="<?php echo isset( $edit_tenant['product_ids'] ) ? esc_attr( implode( ',', $edit_tenant['product_ids'] ) ) : ''; ?>" class="regular-text">
                                        <p class="description"><?php esc_html_e( 'Comma-separated list of product IDs to show for this tenant.', 'aqualuxe' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="tenant_category_ids"><?php esc_html_e( 'Category IDs', 'aqualuxe' ); ?></label></th>
                                    <td>
                                        <input type="text" name="tenant_category_ids" id="tenant_category_ids" value="<?php echo isset( $edit_tenant['category_ids'] ) ? esc_attr( implode( ',', $edit_tenant['category_ids'] ) ) : ''; ?>" class="regular-text">
                                        <p class="description"><?php esc_html_e( 'Comma-separated list of product category IDs to show for this tenant.', 'aqualuxe' ); ?></p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </table>
                        
                        <p class="submit">
                            <input type="submit" name="aqualuxe_save_tenant" class="button button-primary" value="<?php echo $edit_tenant_id ? esc_attr__( 'Update Tenant', 'aqualuxe' ) : esc_attr__( 'Add Tenant', 'aqualuxe' ); ?>">
                        </p>
                    </form>
                </div>
            </div>

            <div class="aqualuxe-admin-sidebar">
                <div class="aqualuxe-admin-card">
                    <h3><?php esc_html_e( 'Multi-Tenant Documentation', 'aqualuxe' ); ?></h3>
                    <p><?php esc_html_e( 'The multi-tenant feature allows you to create multiple tenant configurations with different branding, colors, and content.', 'aqualuxe' ); ?></p>
                    <p><?php esc_html_e( 'Each tenant can have its own:', 'aqualuxe' ); ?></p>
                    <ul style="list-style-type: disc; padding-left: 20px;">
                        <li><?php esc_html_e( 'Logo and branding', 'aqualuxe' ); ?></li>
                        <li><?php esc_html_e( 'Color scheme', 'aqualuxe' ); ?></li>
                        <li><?php esc_html_e( 'Contact information', 'aqualuxe' ); ?></li>
                        <li><?php esc_html_e( 'Social media links', 'aqualuxe' ); ?></li>
                        <li><?php esc_html_e( 'Footer and copyright text', 'aqualuxe' ); ?></li>
                        <li><?php esc_html_e( 'Custom CSS and JavaScript', 'aqualuxe' ); ?></li>
                        <li><?php esc_html_e( 'Analytics tracking', 'aqualuxe' ); ?></li>
                        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                            <li><?php esc_html_e( 'Product selection', 'aqualuxe' ); ?></li>
                        <?php endif; ?>
                    </ul>
                    <p><?php esc_html_e( 'Tenants can be accessed via:', 'aqualuxe' ); ?></p>
                    <ul style="list-style-type: disc; padding-left: 20px;">
                        <li><?php esc_html_e( 'Subdomain (e.g., tenant1.example.com)', 'aqualuxe' ); ?></li>
                        <li><?php esc_html_e( 'Query parameter (e.g., ?tenant_id=tenant1)', 'aqualuxe' ); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <style>
        .aqualuxe-admin-content {
            display: flex;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .aqualuxe-admin-main {
            flex: 1;
            min-width: 600px;
            margin-right: 20px;
        }
        .aqualuxe-admin-sidebar {
            width: 300px;
        }
        .aqualuxe-admin-card {
            background: #fff;
            border: 1px solid #ccd0d4;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
            margin-bottom: 20px;
            padding: 15px;
        }
        @media screen and (max-width: 960px) {
            .aqualuxe-admin-main {
                min-width: 100%;
                margin-right: 0;
            }
            .aqualuxe-admin-sidebar {
                width: 100%;
            }
        }
    </style>

    <script>
        jQuery(document).ready(function($) {
            // Initialize color pickers
            $('.color-picker').wpColorPicker();

            // Handle product and category IDs
            $('#tenant_product_ids, #tenant_category_ids').on('change', function() {
                var value = $(this).val();
                value = value.replace(/\s+/g, ''); // Remove spaces
                $(this).val(value);
            });
        });
    </script>
    <?php
}

/**
 * Enqueue admin scripts for multi-tenant page
 *
 * @param string $hook Page hook
 */
function aqualuxe_multi_tenant_admin_scripts( $hook ) {
    if ( 'toplevel_page_aqualuxe-multi-tenant' !== $hook ) {
        return;
    }

    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_multi_tenant_admin_scripts' );

/**
 * Add tenant switcher to admin bar
 *
 * @param WP_Admin_Bar $admin_bar Admin bar object
 */
function aqualuxe_add_tenant_switcher_to_admin_bar( $admin_bar ) {
    if ( ! aqualuxe_is_multi_tenant_enabled() || ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $tenants = aqualuxe_get_tenants();
    $current_tenant_id = aqualuxe_get_current_tenant_id();

    $admin_bar->add_menu( array(
        'id'    => 'aqualuxe-tenant-switcher',
        'title' => sprintf( esc_html__( 'Tenant: %s', 'aqualuxe' ), esc_html( $current_tenant_id ) ),
        'href'  => admin_url( 'admin.php?page=aqualuxe-multi-tenant' ),
    ) );

    foreach ( $tenants as $tenant_id => $tenant ) {
        $admin_bar->add_menu( array(
            'parent' => 'aqualuxe-tenant-switcher',
            'id'     => 'aqualuxe-tenant-' . $tenant_id,
            'title'  => esc_html( $tenant['name'] ),
            'href'   => add_query_arg( 'tenant_id', $tenant_id ),
        ) );
    }
}
add_action( 'admin_bar_menu', 'aqualuxe_add_tenant_switcher_to_admin_bar', 100 );

/**
 * Set tenant cookie
 */
function aqualuxe_set_tenant_cookie() {
    if ( ! aqualuxe_is_multi_tenant_enabled() ) {
        return;
    }

    if ( isset( $_GET['tenant_id'] ) ) {
        $tenant_id = sanitize_text_field( wp_unslash( $_GET['tenant_id'] ) );
        setcookie( 'aqualuxe_tenant_id', $tenant_id, time() + MONTH_IN_SECONDS, '/' );
    }
}
add_action( 'init', 'aqualuxe_set_tenant_cookie' );