<?php
/**
 * AquaLuxe Template Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Add custom classes to the body
 *
 * @param array $classes Body classes
 * @return array
 */
function aqualuxe_body_classes( $classes ) {
    // Add a class if sidebar is active
    if ( aqualuxe_display_sidebar() ) {
        $classes[] = 'has-sidebar';
        $classes[] = 'sidebar-' . aqualuxe_get_sidebar_position();
    } else {
        $classes[] = 'no-sidebar';
    }
    
    // Add a class for the blog layout
    if ( is_home() || is_archive() || is_search() ) {
        $classes[] = 'blog-layout-' . aqualuxe_get_blog_layout();
        
        if ( 'grid' === aqualuxe_get_blog_layout() || 'masonry' === aqualuxe_get_blog_layout() ) {
            $classes[] = 'blog-columns-' . aqualuxe_get_blog_columns();
        }
    }
    
    // Add a class for the shop layout
    if ( aqualuxe_is_woocommerce_active() && ( is_shop() || is_product_category() || is_product_tag() ) ) {
        $classes[] = 'shop-columns-' . aqualuxe_get_shop_columns();
        
        if ( aqualuxe_get_option( 'aqualuxe_shop_sidebar', true ) ) {
            $classes[] = 'shop-has-sidebar';
        } else {
            $classes[] = 'shop-no-sidebar';
        }
    }
    
    // Add a class for the sticky header
    if ( aqualuxe_get_option( 'aqualuxe_sticky_header', true ) ) {
        $classes[] = 'has-sticky-header';
    }
    
    // Add a class for the menu position
    $classes[] = 'menu-position-' . aqualuxe_get_option( 'aqualuxe_menu_position', 'right' );
    
    // Add a class for the dark mode
    if ( aqualuxe_is_dark_mode() ) {
        $classes[] = 'dark-mode';
    }
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );

/**
 * Add custom classes to the post
 *
 * @param array $classes Post classes
 * @return array
 */
function aqualuxe_post_classes( $classes ) {
    // Add a class for the post thumbnail
    if ( has_post_thumbnail() ) {
        $classes[] = 'has-post-thumbnail';
    } else {
        $classes[] = 'no-post-thumbnail';
    }
    
    return $classes;
}
add_filter( 'post_class', 'aqualuxe_post_classes' );

/**
 * Add custom classes to the product
 *
 * @param array $classes Product classes
 * @return array
 */
function aqualuxe_product_classes( $classes ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return $classes;
    }
    
    global $product;
    
    if ( ! $product ) {
        return $classes;
    }
    
    // Add a class for the product type
    $classes[] = 'product-type-' . $product->get_type();
    
    // Add a class for the product stock status
    $classes[] = 'product-stock-' . $product->get_stock_status();
    
    // Add a class for the product on sale
    if ( $product->is_on_sale() ) {
        $classes[] = 'product-on-sale';
    }
    
    // Add a class for the product featured
    if ( $product->is_featured() ) {
        $classes[] = 'product-featured';
    }
    
    // Add a class for the product virtual
    if ( $product->is_virtual() ) {
        $classes[] = 'product-virtual';
    }
    
    // Add a class for the product downloadable
    if ( $product->is_downloadable() ) {
        $classes[] = 'product-downloadable';
    }
    
    // Add a class for the product sold individually
    if ( $product->is_sold_individually() ) {
        $classes[] = 'product-sold-individually';
    }
    
    // Add a class for the product backorders allowed
    if ( $product->backorders_allowed() ) {
        $classes[] = 'product-backorders-allowed';
    }
    
    // Add a class for the product backorders required
    if ( $product->backorders_require_notification() ) {
        $classes[] = 'product-backorders-required';
    }
    
    // Add a class for the product manage stock
    if ( $product->managing_stock() ) {
        $classes[] = 'product-manage-stock';
    }
    
    // Add a class for the product reviews allowed
    if ( $product->get_reviews_allowed() ) {
        $classes[] = 'product-reviews-allowed';
    }
    
    // Add a class for the product shipping required
    if ( $product->needs_shipping() ) {
        $classes[] = 'product-shipping-required';
    }
    
    // Add a class for the product shipping taxable
    if ( $product->is_shipping_taxable() ) {
        $classes[] = 'product-shipping-taxable';
    }
    
    return $classes;
}
add_filter( 'woocommerce_post_class', 'aqualuxe_product_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 *
 * @return void
 */
function aqualuxe_pingback_header() {
    if ( is_singular() && pings_open() ) {
        printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
    }
}
add_action( 'wp_head', 'aqualuxe_pingback_header' );

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function aqualuxe_resource_hints( $urls, $relation_type ) {
    if ( wp_style_is( 'aqualuxe-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }
    
    return $urls;
}
add_filter( 'wp_resource_hints', 'aqualuxe_resource_hints', 10, 2 );

/**
 * Add custom image sizes
 *
 * @return void
 */
function aqualuxe_add_image_sizes() {
    add_image_size( 'aqualuxe-featured', 1200, 600, true );
    add_image_size( 'aqualuxe-blog', 800, 450, true );
    add_image_size( 'aqualuxe-blog-grid', 600, 400, true );
    add_image_size( 'aqualuxe-blog-list', 400, 300, true );
    add_image_size( 'aqualuxe-product', 600, 600, true );
    add_image_size( 'aqualuxe-product-thumbnail', 300, 300, true );
    add_image_size( 'aqualuxe-product-gallery', 800, 800, true );
    add_image_size( 'aqualuxe-product-gallery-thumbnail', 100, 100, true );
}
add_action( 'after_setup_theme', 'aqualuxe_add_image_sizes' );

/**
 * Add custom image sizes to the media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured'               => __( 'AquaLuxe Featured', 'aqualuxe' ),
        'aqualuxe-blog'                   => __( 'AquaLuxe Blog', 'aqualuxe' ),
        'aqualuxe-blog-grid'              => __( 'AquaLuxe Blog Grid', 'aqualuxe' ),
        'aqualuxe-blog-list'              => __( 'AquaLuxe Blog List', 'aqualuxe' ),
        'aqualuxe-product'                => __( 'AquaLuxe Product', 'aqualuxe' ),
        'aqualuxe-product-thumbnail'      => __( 'AquaLuxe Product Thumbnail', 'aqualuxe' ),
        'aqualuxe-product-gallery'        => __( 'AquaLuxe Product Gallery', 'aqualuxe' ),
        'aqualuxe-product-gallery-thumbnail' => __( 'AquaLuxe Product Gallery Thumbnail', 'aqualuxe' ),
    ) );
}
add_filter( 'image_size_names_choose', 'aqualuxe_custom_image_sizes' );

/**
 * Set the excerpt length
 *
 * @param int $length Excerpt length
 * @return int
 */
function aqualuxe_excerpt_length( $length ) {
    return aqualuxe_get_blog_excerpt_length();
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length' );

/**
 * Change the excerpt more string
 *
 * @param string $more Excerpt more
 * @return string
 */
function aqualuxe_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Add schema markup to the body
 *
 * @return void
 */
function aqualuxe_body_schema() {
    echo aqualuxe_get_schema_markup();
}

/**
 * Add Open Graph meta tags
 *
 * @return void
 */
function aqualuxe_open_graph_meta_tags() {
    $meta_tags = aqualuxe_get_open_graph_meta_tags();
    
    foreach ( $meta_tags as $property => $content ) {
        echo '<meta property="' . esc_attr( $property ) . '" content="' . esc_attr( $content ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_open_graph_meta_tags' );

/**
 * Add Twitter Card meta tags
 *
 * @return void
 */
function aqualuxe_twitter_card_meta_tags() {
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( wp_get_document_title() ) . '" />' . "\n";
    
    if ( is_singular() && has_excerpt() ) {
        echo '<meta name="twitter:description" content="' . esc_attr( get_the_excerpt() ) . '" />' . "\n";
    } elseif ( is_singular() ) {
        echo '<meta name="twitter:description" content="' . esc_attr( wp_strip_all_tags( get_the_content() ) ) . '" />' . "\n";
    } else {
        echo '<meta name="twitter:description" content="' . esc_attr( get_bloginfo( 'description' ) ) . '" />' . "\n";
    }
    
    if ( is_singular() && has_post_thumbnail() ) {
        echo '<meta name="twitter:image" content="' . esc_url( get_the_post_thumbnail_url( null, 'large' ) ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_twitter_card_meta_tags' );

/**
 * Add custom styles to the header
 *
 * @return void
 */
function aqualuxe_custom_styles() {
    $primary_color = aqualuxe_get_option( 'aqualuxe_primary_color', '#0073aa' );
    $secondary_color = aqualuxe_get_option( 'aqualuxe_secondary_color', '#23282d' );
    $accent_color = aqualuxe_get_option( 'aqualuxe_accent_color', '#00a0d2' );
    $text_color = aqualuxe_get_option( 'aqualuxe_text_color', '#333333' );
    $background_color = aqualuxe_get_option( 'aqualuxe_background_color', '#ffffff' );
    
    $body_font_family = aqualuxe_get_option( 'aqualuxe_body_font_family', 'Roboto, sans-serif' );
    $heading_font_family = aqualuxe_get_option( 'aqualuxe_heading_font_family', 'Montserrat, sans-serif' );
    $body_font_size = aqualuxe_get_option( 'aqualuxe_body_font_size', '16' );
    $line_height = aqualuxe_get_option( 'aqualuxe_line_height', '1.6' );
    
    $container_width = aqualuxe_get_option( 'aqualuxe_container_width', '1200' );
    
    $logo_width = aqualuxe_get_option( 'aqualuxe_logo_width', '200' );
    $logo_height = aqualuxe_get_option( 'aqualuxe_logo_height', '80' );
    
    $css = "
        :root {
            --aqualuxe-primary-color: {$primary_color};
            --aqualuxe-secondary-color: {$secondary_color};
            --aqualuxe-accent-color: {$accent_color};
            --aqualuxe-text-color: {$text_color};
            --aqualuxe-background-color: {$background_color};
            --aqualuxe-body-font-family: {$body_font_family};
            --aqualuxe-heading-font-family: {$heading_font_family};
            --aqualuxe-body-font-size: {$body_font_size}px;
            --aqualuxe-line-height: {$line_height};
            --aqualuxe-container-width: {$container_width}px;
        }
        
        body {
            font-family: var(--aqualuxe-body-font-family);
            font-size: var(--aqualuxe-body-font-size);
            line-height: var(--aqualuxe-line-height);
            color: var(--aqualuxe-text-color);
            background-color: var(--aqualuxe-background-color);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--aqualuxe-heading-font-family);
        }
        
        .container {
            max-width: var(--aqualuxe-container-width);
        }
        
        a {
            color: var(--aqualuxe-primary-color);
        }
        
        a:hover {
            color: var(--aqualuxe-accent-color);
        }
        
        .custom-logo {
            width: {$logo_width}px;
            height: {$logo_height}px;
        }
        
        .button, button, input[type='button'], input[type='reset'], input[type='submit'] {
            background-color: var(--aqualuxe-primary-color);
            color: #ffffff;
        }
        
        .button:hover, button:hover, input[type='button']:hover, input[type='reset']:hover, input[type='submit']:hover {
            background-color: var(--aqualuxe-accent-color);
        }
    ";
    
    echo '<style id="aqualuxe-custom-styles">' . wp_strip_all_tags( $css ) . '</style>';
}
add_action( 'wp_head', 'aqualuxe_custom_styles' );

/**
 * Add custom fonts
 *
 * @return void
 */
function aqualuxe_custom_fonts() {
    $body_font_family = aqualuxe_get_option( 'aqualuxe_body_font_family', 'Roboto, sans-serif' );
    $heading_font_family = aqualuxe_get_option( 'aqualuxe_heading_font_family', 'Montserrat, sans-serif' );
    
    $fonts = array();
    
    if ( 'Roboto, sans-serif' === $body_font_family ) {
        $fonts[] = 'Roboto:400,400i,700,700i';
    }
    
    if ( 'Open Sans, sans-serif' === $body_font_family ) {
        $fonts[] = 'Open+Sans:400,400i,700,700i';
    }
    
    if ( 'Lato, sans-serif' === $body_font_family ) {
        $fonts[] = 'Lato:400,400i,700,700i';
    }
    
    if ( 'Montserrat, sans-serif' === $body_font_family ) {
        $fonts[] = 'Montserrat:400,400i,700,700i';
    }
    
    if ( 'Raleway, sans-serif' === $body_font_family ) {
        $fonts[] = 'Raleway:400,400i,700,700i';
    }
    
    if ( 'Poppins, sans-serif' === $body_font_family ) {
        $fonts[] = 'Poppins:400,400i,700,700i';
    }
    
    if ( 'Nunito, sans-serif' === $body_font_family ) {
        $fonts[] = 'Nunito:400,400i,700,700i';
    }
    
    if ( 'Playfair Display, serif' === $body_font_family ) {
        $fonts[] = 'Playfair+Display:400,400i,700,700i';
    }
    
    if ( 'Merriweather, serif' === $body_font_family ) {
        $fonts[] = 'Merriweather:400,400i,700,700i';
    }
    
    if ( 'Montserrat, sans-serif' === $heading_font_family && 'Montserrat, sans-serif' !== $body_font_family ) {
        $fonts[] = 'Montserrat:400,400i,700,700i';
    }
    
    if ( 'Roboto, sans-serif' === $heading_font_family && 'Roboto, sans-serif' !== $body_font_family ) {
        $fonts[] = 'Roboto:400,400i,700,700i';
    }
    
    if ( 'Open Sans, sans-serif' === $heading_font_family && 'Open Sans, sans-serif' !== $body_font_family ) {
        $fonts[] = 'Open+Sans:400,400i,700,700i';
    }
    
    if ( 'Lato, sans-serif' === $heading_font_family && 'Lato, sans-serif' !== $body_font_family ) {
        $fonts[] = 'Lato:400,400i,700,700i';
    }
    
    if ( 'Raleway, sans-serif' === $heading_font_family && 'Raleway, sans-serif' !== $body_font_family ) {
        $fonts[] = 'Raleway:400,400i,700,700i';
    }
    
    if ( 'Poppins, sans-serif' === $heading_font_family && 'Poppins, sans-serif' !== $body_font_family ) {
        $fonts[] = 'Poppins:400,400i,700,700i';
    }
    
    if ( 'Nunito, sans-serif' === $heading_font_family && 'Nunito, sans-serif' !== $body_font_family ) {
        $fonts[] = 'Nunito:400,400i,700,700i';
    }
    
    if ( 'Playfair Display, serif' === $heading_font_family && 'Playfair Display, serif' !== $body_font_family ) {
        $fonts[] = 'Playfair+Display:400,400i,700,700i';
    }
    
    if ( 'Merriweather, serif' === $heading_font_family && 'Merriweather, serif' !== $body_font_family ) {
        $fonts[] = 'Merriweather:400,400i,700,700i';
    }
    
    if ( ! empty( $fonts ) ) {
        $fonts_url = add_query_arg( array(
            'family' => implode( '|', $fonts ),
            'display' => 'swap',
        ), 'https://fonts.googleapis.com/css2' );
        
        wp_enqueue_style( 'aqualuxe-fonts', $fonts_url, array(), null );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_custom_fonts' );

/**
 * Add custom admin styles
 *
 * @return void
 */
function aqualuxe_admin_styles() {
    wp_enqueue_style( 'aqualuxe-admin-style', AQUALUXE_ASSETS_URI . 'css/admin.css', array(), AQUALUXE_VERSION );
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_admin_styles' );

/**
 * Add custom login styles
 *
 * @return void
 */
function aqualuxe_login_styles() {
    wp_enqueue_style( 'aqualuxe-login-style', AQUALUXE_ASSETS_URI . 'css/login.css', array(), AQUALUXE_VERSION );
}
add_action( 'login_enqueue_scripts', 'aqualuxe_login_styles' );

/**
 * Add custom login logo URL
 *
 * @return string
 */
function aqualuxe_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'aqualuxe_login_logo_url' );

/**
 * Add custom login logo title
 *
 * @return string
 */
function aqualuxe_login_logo_title() {
    return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'aqualuxe_login_logo_title' );

/**
 * Add custom admin footer text
 *
 * @param string $text Footer text
 * @return string
 */
function aqualuxe_admin_footer_text( $text ) {
    $text = sprintf( __( 'Thank you for creating with <a href="%s">WordPress</a> | Theme: <a href="%s">AquaLuxe</a>', 'aqualuxe' ), 'https://wordpress.org/', 'https://aqualuxe.com/' );
    
    return $text;
}
add_filter( 'admin_footer_text', 'aqualuxe_admin_footer_text' );

/**
 * Add custom admin footer version
 *
 * @param string $version Version
 * @return string
 */
function aqualuxe_admin_footer_version( $version ) {
    $version = sprintf( __( 'AquaLuxe %s', 'aqualuxe' ), AQUALUXE_VERSION );
    
    return $version;
}
add_filter( 'update_footer', 'aqualuxe_admin_footer_version', 11 );

/**
 * Add custom admin bar menu
 *
 * @param WP_Admin_Bar $admin_bar Admin bar
 * @return void
 */
function aqualuxe_admin_bar_menu( $admin_bar ) {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    
    $admin_bar->add_menu( array(
        'id'    => 'aqualuxe',
        'title' => __( 'AquaLuxe', 'aqualuxe' ),
        'href'  => admin_url( 'themes.php?page=aqualuxe' ),
    ) );
    
    $admin_bar->add_menu( array(
        'id'     => 'aqualuxe-customizer',
        'parent' => 'aqualuxe',
        'title'  => __( 'Customize', 'aqualuxe' ),
        'href'   => admin_url( 'customize.php' ),
    ) );
    
    $admin_bar->add_menu( array(
        'id'     => 'aqualuxe-modules',
        'parent' => 'aqualuxe',
        'title'  => __( 'Modules', 'aqualuxe' ),
        'href'   => admin_url( 'themes.php?page=aqualuxe-modules' ),
    ) );
    
    $admin_bar->add_menu( array(
        'id'     => 'aqualuxe-demo-import',
        'parent' => 'aqualuxe',
        'title'  => __( 'Demo Import', 'aqualuxe' ),
        'href'   => admin_url( 'themes.php?page=aqualuxe-demo-import' ),
    ) );
    
    $admin_bar->add_menu( array(
        'id'     => 'aqualuxe-documentation',
        'parent' => 'aqualuxe',
        'title'  => __( 'Documentation', 'aqualuxe' ),
        'href'   => 'https://aqualuxe.com/documentation/',
        'meta'   => array(
            'target' => '_blank',
        ),
    ) );
    
    $admin_bar->add_menu( array(
        'id'     => 'aqualuxe-support',
        'parent' => 'aqualuxe',
        'title'  => __( 'Support', 'aqualuxe' ),
        'href'   => 'https://aqualuxe.com/support/',
        'meta'   => array(
            'target' => '_blank',
        ),
    ) );
}
add_action( 'admin_bar_menu', 'aqualuxe_admin_bar_menu', 100 );

/**
 * Add custom dashboard widgets
 *
 * @return void
 */
function aqualuxe_dashboard_widgets() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    
    wp_add_dashboard_widget(
        'aqualuxe_dashboard_widget',
        __( 'AquaLuxe', 'aqualuxe' ),
        'aqualuxe_dashboard_widget_callback'
    );
}
add_action( 'wp_dashboard_setup', 'aqualuxe_dashboard_widgets' );

/**
 * Dashboard widget callback
 *
 * @return void
 */
function aqualuxe_dashboard_widget_callback() {
    ?>
    <div class="aqualuxe-dashboard-widget">
        <div class="aqualuxe-dashboard-widget-header">
            <h3><?php esc_html_e( 'Welcome to AquaLuxe', 'aqualuxe' ); ?></h3>
            <p><?php esc_html_e( 'Thank you for choosing AquaLuxe theme. Here are some links to get you started:', 'aqualuxe' ); ?></p>
        </div>
        <div class="aqualuxe-dashboard-widget-content">
            <ul>
                <li><a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Customize Theme', 'aqualuxe' ); ?></a></li>
                <li><a href="<?php echo esc_url( admin_url( 'themes.php?page=aqualuxe-modules' ) ); ?>"><?php esc_html_e( 'Manage Modules', 'aqualuxe' ); ?></a></li>
                <li><a href="<?php echo esc_url( admin_url( 'themes.php?page=aqualuxe-demo-import' ) ); ?>"><?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?></a></li>
                <li><a href="https://aqualuxe.com/documentation/" target="_blank"><?php esc_html_e( 'Documentation', 'aqualuxe' ); ?></a></li>
                <li><a href="https://aqualuxe.com/support/" target="_blank"><?php esc_html_e( 'Support', 'aqualuxe' ); ?></a></li>
            </ul>
        </div>
    </div>
    <?php
}

/**
 * Add custom meta boxes
 *
 * @return void
 */
function aqualuxe_add_meta_boxes() {
    add_meta_box(
        'aqualuxe_page_options',
        __( 'Page Options', 'aqualuxe' ),
        'aqualuxe_page_options_callback',
        'page',
        'side',
        'default'
    );
    
    add_meta_box(
        'aqualuxe_post_options',
        __( 'Post Options', 'aqualuxe' ),
        'aqualuxe_post_options_callback',
        'post',
        'side',
        'default'
    );
    
    if ( aqualuxe_is_woocommerce_active() ) {
        add_meta_box(
            'aqualuxe_product_options',
            __( 'Product Options', 'aqualuxe' ),
            'aqualuxe_product_options_callback',
            'product',
            'side',
            'default'
        );
    }
}
add_action( 'add_meta_boxes', 'aqualuxe_add_meta_boxes' );

/**
 * Page options meta box callback
 *
 * @param WP_Post $post Post object
 * @return void
 */
function aqualuxe_page_options_callback( $post ) {
    wp_nonce_field( 'aqualuxe_page_options', 'aqualuxe_page_options_nonce' );
    
    $sidebar_position = get_post_meta( $post->ID, '_aqualuxe_sidebar_position', true );
    $hide_title = get_post_meta( $post->ID, '_aqualuxe_hide_title', true );
    $hide_featured_image = get_post_meta( $post->ID, '_aqualuxe_hide_featured_image', true );
    
    ?>
    <p>
        <label for="aqualuxe_sidebar_position"><?php esc_html_e( 'Sidebar Position', 'aqualuxe' ); ?></label>
        <select name="aqualuxe_sidebar_position" id="aqualuxe_sidebar_position" class="widefat">
            <option value=""><?php esc_html_e( 'Default', 'aqualuxe' ); ?></option>
            <option value="left" <?php selected( $sidebar_position, 'left' ); ?>><?php esc_html_e( 'Left', 'aqualuxe' ); ?></option>
            <option value="right" <?php selected( $sidebar_position, 'right' ); ?>><?php esc_html_e( 'Right', 'aqualuxe' ); ?></option>
            <option value="none" <?php selected( $sidebar_position, 'none' ); ?>><?php esc_html_e( 'None', 'aqualuxe' ); ?></option>
        </select>
    </p>
    <p>
        <label for="aqualuxe_hide_title">
            <input type="checkbox" name="aqualuxe_hide_title" id="aqualuxe_hide_title" value="1" <?php checked( $hide_title, '1' ); ?> />
            <?php esc_html_e( 'Hide Title', 'aqualuxe' ); ?>
        </label>
    </p>
    <p>
        <label for="aqualuxe_hide_featured_image">
            <input type="checkbox" name="aqualuxe_hide_featured_image" id="aqualuxe_hide_featured_image" value="1" <?php checked( $hide_featured_image, '1' ); ?> />
            <?php esc_html_e( 'Hide Featured Image', 'aqualuxe' ); ?>
        </label>
    </p>
    <?php
}

/**
 * Post options meta box callback
 *
 * @param WP_Post $post Post object
 * @return void
 */
function aqualuxe_post_options_callback( $post ) {
    wp_nonce_field( 'aqualuxe_post_options', 'aqualuxe_post_options_nonce' );
    
    $sidebar_position = get_post_meta( $post->ID, '_aqualuxe_sidebar_position', true );
    $hide_featured_image = get_post_meta( $post->ID, '_aqualuxe_hide_featured_image', true );
    $hide_meta = get_post_meta( $post->ID, '_aqualuxe_hide_meta', true );
    $hide_author_box = get_post_meta( $post->ID, '_aqualuxe_hide_author_box', true );
    $hide_related_posts = get_post_meta( $post->ID, '_aqualuxe_hide_related_posts', true );
    $hide_post_navigation = get_post_meta( $post->ID, '_aqualuxe_hide_post_navigation', true );
    
    ?>
    <p>
        <label for="aqualuxe_sidebar_position"><?php esc_html_e( 'Sidebar Position', 'aqualuxe' ); ?></label>
        <select name="aqualuxe_sidebar_position" id="aqualuxe_sidebar_position" class="widefat">
            <option value=""><?php esc_html_e( 'Default', 'aqualuxe' ); ?></option>
            <option value="left" <?php selected( $sidebar_position, 'left' ); ?>><?php esc_html_e( 'Left', 'aqualuxe' ); ?></option>
            <option value="right" <?php selected( $sidebar_position, 'right' ); ?>><?php esc_html_e( 'Right', 'aqualuxe' ); ?></option>
            <option value="none" <?php selected( $sidebar_position, 'none' ); ?>><?php esc_html_e( 'None', 'aqualuxe' ); ?></option>
        </select>
    </p>
    <p>
        <label for="aqualuxe_hide_featured_image">
            <input type="checkbox" name="aqualuxe_hide_featured_image" id="aqualuxe_hide_featured_image" value="1" <?php checked( $hide_featured_image, '1' ); ?> />
            <?php esc_html_e( 'Hide Featured Image', 'aqualuxe' ); ?>
        </label>
    </p>
    <p>
        <label for="aqualuxe_hide_meta">
            <input type="checkbox" name="aqualuxe_hide_meta" id="aqualuxe_hide_meta" value="1" <?php checked( $hide_meta, '1' ); ?> />
            <?php esc_html_e( 'Hide Meta', 'aqualuxe' ); ?>
        </label>
    </p>
    <p>
        <label for="aqualuxe_hide_author_box">
            <input type="checkbox" name="aqualuxe_hide_author_box" id="aqualuxe_hide_author_box" value="1" <?php checked( $hide_author_box, '1' ); ?> />
            <?php esc_html_e( 'Hide Author Box', 'aqualuxe' ); ?>
        </label>
    </p>
    <p>
        <label for="aqualuxe_hide_related_posts">
            <input type="checkbox" name="aqualuxe_hide_related_posts" id="aqualuxe_hide_related_posts" value="1" <?php checked( $hide_related_posts, '1' ); ?> />
            <?php esc_html_e( 'Hide Related Posts', 'aqualuxe' ); ?>
        </label>
    </p>
    <p>
        <label for="aqualuxe_hide_post_navigation">
            <input type="checkbox" name="aqualuxe_hide_post_navigation" id="aqualuxe_hide_post_navigation" value="1" <?php checked( $hide_post_navigation, '1' ); ?> />
            <?php esc_html_e( 'Hide Post Navigation', 'aqualuxe' ); ?>
        </label>
    </p>
    <?php
}

/**
 * Product options meta box callback
 *
 * @param WP_Post $post Post object
 * @return void
 */
function aqualuxe_product_options_callback( $post ) {
    wp_nonce_field( 'aqualuxe_product_options', 'aqualuxe_product_options_nonce' );
    
    $hide_related_products = get_post_meta( $post->ID, '_aqualuxe_hide_related_products', true );
    $hide_upsells = get_post_meta( $post->ID, '_aqualuxe_hide_upsells', true );
    $hide_cross_sells = get_post_meta( $post->ID, '_aqualuxe_hide_cross_sells', true );
    $hide_product_meta = get_post_meta( $post->ID, '_aqualuxe_hide_product_meta', true );
    $hide_product_tabs = get_post_meta( $post->ID, '_aqualuxe_hide_product_tabs', true );
    
    ?>
    <p>
        <label for="aqualuxe_hide_related_products">
            <input type="checkbox" name="aqualuxe_hide_related_products" id="aqualuxe_hide_related_products" value="1" <?php checked( $hide_related_products, '1' ); ?> />
            <?php esc_html_e( 'Hide Related Products', 'aqualuxe' ); ?>
        </label>
    </p>
    <p>
        <label for="aqualuxe_hide_upsells">
            <input type="checkbox" name="aqualuxe_hide_upsells" id="aqualuxe_hide_upsells" value="1" <?php checked( $hide_upsells, '1' ); ?> />
            <?php esc_html_e( 'Hide Upsells', 'aqualuxe' ); ?>
        </label>
    </p>
    <p>
        <label for="aqualuxe_hide_cross_sells">
            <input type="checkbox" name="aqualuxe_hide_cross_sells" id="aqualuxe_hide_cross_sells" value="1" <?php checked( $hide_cross_sells, '1' ); ?> />
            <?php esc_html_e( 'Hide Cross Sells', 'aqualuxe' ); ?>
        </label>
    </p>
    <p>
        <label for="aqualuxe_hide_product_meta">
            <input type="checkbox" name="aqualuxe_hide_product_meta" id="aqualuxe_hide_product_meta" value="1" <?php checked( $hide_product_meta, '1' ); ?> />
            <?php esc_html_e( 'Hide Product Meta', 'aqualuxe' ); ?>
        </label>
    </p>
    <p>
        <label for="aqualuxe_hide_product_tabs">
            <input type="checkbox" name="aqualuxe_hide_product_tabs" id="aqualuxe_hide_product_tabs" value="1" <?php checked( $hide_product_tabs, '1' ); ?> />
            <?php esc_html_e( 'Hide Product Tabs', 'aqualuxe' ); ?>
        </label>
    </p>
    <?php
}

/**
 * Save meta box data
 *
 * @param int     $post_id Post ID
 * @param WP_Post $post    Post object
 * @return void
 */
function aqualuxe_save_meta_box_data( $post_id, $post ) {
    // Check if nonce is set
    if ( ! isset( $_POST['aqualuxe_page_options_nonce'] ) && ! isset( $_POST['aqualuxe_post_options_nonce'] ) && ! isset( $_POST['aqualuxe_product_options_nonce'] ) ) {
        return;
    }
    
    // Verify that the nonce is valid
    if ( isset( $_POST['aqualuxe_page_options_nonce'] ) && ! wp_verify_nonce( $_POST['aqualuxe_page_options_nonce'], 'aqualuxe_page_options' ) ) {
        return;
    }
    
    if ( isset( $_POST['aqualuxe_post_options_nonce'] ) && ! wp_verify_nonce( $_POST['aqualuxe_post_options_nonce'], 'aqualuxe_post_options' ) ) {
        return;
    }
    
    if ( isset( $_POST['aqualuxe_product_options_nonce'] ) && ! wp_verify_nonce( $_POST['aqualuxe_product_options_nonce'], 'aqualuxe_product_options' ) ) {
        return;
    }
    
    // Check if user has permissions to save data
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    // Check if not an autosave
    if ( wp_is_post_autosave( $post_id ) ) {
        return;
    }
    
    // Check if not a revision
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }
    
    // Page options
    if ( isset( $_POST['aqualuxe_sidebar_position'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_sidebar_position', sanitize_text_field( $_POST['aqualuxe_sidebar_position'] ) );
    }
    
    if ( isset( $_POST['aqualuxe_hide_title'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_hide_title', '1' );
    } else {
        delete_post_meta( $post_id, '_aqualuxe_hide_title' );
    }
    
    if ( isset( $_POST['aqualuxe_hide_featured_image'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_hide_featured_image', '1' );
    } else {
        delete_post_meta( $post_id, '_aqualuxe_hide_featured_image' );
    }
    
    // Post options
    if ( isset( $_POST['aqualuxe_hide_meta'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_hide_meta', '1' );
    } else {
        delete_post_meta( $post_id, '_aqualuxe_hide_meta' );
    }
    
    if ( isset( $_POST['aqualuxe_hide_author_box'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_hide_author_box', '1' );
    } else {
        delete_post_meta( $post_id, '_aqualuxe_hide_author_box' );
    }
    
    if ( isset( $_POST['aqualuxe_hide_related_posts'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_hide_related_posts', '1' );
    } else {
        delete_post_meta( $post_id, '_aqualuxe_hide_related_posts' );
    }
    
    if ( isset( $_POST['aqualuxe_hide_post_navigation'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_hide_post_navigation', '1' );
    } else {
        delete_post_meta( $post_id, '_aqualuxe_hide_post_navigation' );
    }
    
    // Product options
    if ( isset( $_POST['aqualuxe_hide_related_products'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_hide_related_products', '1' );
    } else {
        delete_post_meta( $post_id, '_aqualuxe_hide_related_products' );
    }
    
    if ( isset( $_POST['aqualuxe_hide_upsells'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_hide_upsells', '1' );
    } else {
        delete_post_meta( $post_id, '_aqualuxe_hide_upsells' );
    }
    
    if ( isset( $_POST['aqualuxe_hide_cross_sells'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_hide_cross_sells', '1' );
    } else {
        delete_post_meta( $post_id, '_aqualuxe_hide_cross_sells' );
    }
    
    if ( isset( $_POST['aqualuxe_hide_product_meta'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_hide_product_meta', '1' );
    } else {
        delete_post_meta( $post_id, '_aqualuxe_hide_product_meta' );
    }
    
    if ( isset( $_POST['aqualuxe_hide_product_tabs'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_hide_product_tabs', '1' );
    } else {
        delete_post_meta( $post_id, '_aqualuxe_hide_product_tabs' );
    }
}
add_action( 'save_post', 'aqualuxe_save_meta_box_data', 10, 2 );

/**
 * Add custom columns to posts list
 *
 * @param array $columns Columns
 * @return array
 */
function aqualuxe_posts_columns( $columns ) {
    $columns['aqualuxe_thumbnail'] = __( 'Thumbnail', 'aqualuxe' );
    
    return $columns;
}
add_filter( 'manage_posts_columns', 'aqualuxe_posts_columns' );
add_filter( 'manage_pages_columns', 'aqualuxe_posts_columns' );

/**
 * Add custom columns content to posts list
 *
 * @param string $column  Column name
 * @param int    $post_id Post ID
 * @return void
 */
function aqualuxe_posts_custom_column( $column, $post_id ) {
    if ( 'aqualuxe_thumbnail' === $column ) {
        if ( has_post_thumbnail( $post_id ) ) {
            echo get_the_post_thumbnail( $post_id, array( 50, 50 ) );
        } else {
            echo '<div style="width: 50px; height: 50px; background-color: #f5f5f5; display: flex; align-items: center; justify-content: center; color: #999; font-size: 20px;">?</div>';
        }
    }
}
add_action( 'manage_posts_custom_column', 'aqualuxe_posts_custom_column', 10, 2 );
add_action( 'manage_pages_custom_column', 'aqualuxe_posts_custom_column', 10, 2 );

/**
 * Add custom columns to products list
 *
 * @param array $columns Columns
 * @return array
 */
function aqualuxe_products_columns( $columns ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return $columns;
    }
    
    $columns['aqualuxe_product_type'] = __( 'Type', 'aqualuxe' );
    $columns['aqualuxe_product_stock'] = __( 'Stock', 'aqualuxe' );
    
    return $columns;
}
add_filter( 'manage_product_posts_columns', 'aqualuxe_products_columns' );

/**
 * Add custom columns content to products list
 *
 * @param string $column  Column name
 * @param int    $post_id Post ID
 * @return void
 */
function aqualuxe_products_custom_column( $column, $post_id ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $product = wc_get_product( $post_id );
    
    if ( ! $product ) {
        return;
    }
    
    if ( 'aqualuxe_product_type' === $column ) {
        echo ucfirst( $product->get_type() );
    }
    
    if ( 'aqualuxe_product_stock' === $column ) {
        echo ucfirst( $product->get_stock_status() );
    }
}
add_action( 'manage_product_posts_custom_column', 'aqualuxe_products_custom_column', 10, 2 );

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_media_library_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => __( 'AquaLuxe Featured', 'aqualuxe' ),
        'aqualuxe-blog' => __( 'AquaLuxe Blog', 'aqualuxe' ),
        'aqualuxe-blog-grid' => __( 'AquaLuxe Blog Grid', 'aqualuxe' ),
        'aqualuxe-blog-list' => __( 'AquaLuxe Blog List', 'aqualuxe' ),
        'aqualuxe-product' => __( 'AquaLuxe Product', 'aqualuxe' ),
        'aqualuxe-product-thumbnail' => __( 'AquaLuxe Product Thumbnail', 'aqualuxe' ),
        'aqualuxe-product-gallery' => __( 'AquaLuxe Product Gallery', 'aqualuxe' ),
        'aqualuxe-product-gallery-thumbnail' => __( 'AquaLuxe Product Gallery Thumbnail', 'aqualuxe' ),
    ) );
}
add_filter( 'image_size_names_choose', 'aqualuxe_media_library_image_sizes' );

/**
 * Add custom image sizes to media library
 *
 * @return void
 */
function aqualuxe_media_library_image_sizes_setup() {
    add_image_size( 'aqualuxe-featured', 1200, 600, true );
    add_image_size( 'aqualuxe-blog', 800, 450, true );
    add_image_size( 'aqualuxe-blog-grid', 600, 400, true );
    add_image_size( 'aqualuxe-blog-list', 400, 300, true );
    add_image_size( 'aqualuxe-product', 600, 600, true );
    add_image_size( 'aqualuxe-product-thumbnail', 300, 300, true );
    add_image_size( 'aqualuxe-product-gallery', 800, 800, true );
    add_image_size( 'aqualuxe-product-gallery-thumbnail', 100, 100, true );
}
add_action( 'after_setup_theme', 'aqualuxe_media_library_image_sizes_setup' );

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_media_library_image_sizes_choose( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => __( 'AquaLuxe Featured', 'aqualuxe' ),
        'aqualuxe-blog' => __( 'AquaLuxe Blog', 'aqualuxe' ),
        'aqualuxe-blog-grid' => __( 'AquaLuxe Blog Grid', 'aqualuxe' ),
        'aqualuxe-blog-list' => __( 'AquaLuxe Blog List', 'aqualuxe' ),
        'aqualuxe-product' => __( 'AquaLuxe Product', 'aqualuxe' ),
        'aqualuxe-product-thumbnail' => __( 'AquaLuxe Product Thumbnail', 'aqualuxe' ),
        'aqualuxe-product-gallery' => __( 'AquaLuxe Product Gallery', 'aqualuxe' ),
        'aqualuxe-product-gallery-thumbnail' => __( 'AquaLuxe Product Gallery Thumbnail', 'aqualuxe' ),
    ) );
}
add_filter( 'image_size_names_choose', 'aqualuxe_media_library_image_sizes_choose' );

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_media_library_image_sizes_list( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => array(
            'width' => 1200,
            'height' => 600,
            'crop' => true,
        ),
        'aqualuxe-blog' => array(
            'width' => 800,
            'height' => 450,
            'crop' => true,
        ),
        'aqualuxe-blog-grid' => array(
            'width' => 600,
            'height' => 400,
            'crop' => true,
        ),
        'aqualuxe-blog-list' => array(
            'width' => 400,
            'height' => 300,
            'crop' => true,
        ),
        'aqualuxe-product' => array(
            'width' => 600,
            'height' => 600,
            'crop' => true,
        ),
        'aqualuxe-product-thumbnail' => array(
            'width' => 300,
            'height' => 300,
            'crop' => true,
        ),
        'aqualuxe-product-gallery' => array(
            'width' => 800,
            'height' => 800,
            'crop' => true,
        ),
        'aqualuxe-product-gallery-thumbnail' => array(
            'width' => 100,
            'height' => 100,
            'crop' => true,
        ),
    ) );
}
add_filter( 'intermediate_image_sizes_advanced', 'aqualuxe_media_library_image_sizes_list' );

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_media_library_image_sizes_advanced( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => array(
            'width' => 1200,
            'height' => 600,
            'crop' => true,
        ),
        'aqualuxe-blog' => array(
            'width' => 800,
            'height' => 450,
            'crop' => true,
        ),
        'aqualuxe-blog-grid' => array(
            'width' => 600,
            'height' => 400,
            'crop' => true,
        ),
        'aqualuxe-blog-list' => array(
            'width' => 400,
            'height' => 300,
            'crop' => true,
        ),
        'aqualuxe-product' => array(
            'width' => 600,
            'height' => 600,
            'crop' => true,
        ),
        'aqualuxe-product-thumbnail' => array(
            'width' => 300,
            'height' => 300,
            'crop' => true,
        ),
        'aqualuxe-product-gallery' => array(
            'width' => 800,
            'height' => 800,
            'crop' => true,
        ),
        'aqualuxe-product-gallery-thumbnail' => array(
            'width' => 100,
            'height' => 100,
            'crop' => true,
        ),
    ) );
}
add_filter( 'intermediate_image_sizes_advanced', 'aqualuxe_media_library_image_sizes_advanced' );

/**
 * Add custom image sizes to media library
 *
 * @return void
 */
function aqualuxe_media_library_image_sizes_init() {
    add_image_size( 'aqualuxe-featured', 1200, 600, true );
    add_image_size( 'aqualuxe-blog', 800, 450, true );
    add_image_size( 'aqualuxe-blog-grid', 600, 400, true );
    add_image_size( 'aqualuxe-blog-list', 400, 300, true );
    add_image_size( 'aqualuxe-product', 600, 600, true );
    add_image_size( 'aqualuxe-product-thumbnail', 300, 300, true );
    add_image_size( 'aqualuxe-product-gallery', 800, 800, true );
    add_image_size( 'aqualuxe-product-gallery-thumbnail', 100, 100, true );
}
add_action( 'init', 'aqualuxe_media_library_image_sizes_init' );

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_media_library_image_sizes_metadata( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => array(
            'width' => 1200,
            'height' => 600,
            'crop' => true,
        ),
        'aqualuxe-blog' => array(
            'width' => 800,
            'height' => 450,
            'crop' => true,
        ),
        'aqualuxe-blog-grid' => array(
            'width' => 600,
            'height' => 400,
            'crop' => true,
        ),
        'aqualuxe-blog-list' => array(
            'width' => 400,
            'height' => 300,
            'crop' => true,
        ),
        'aqualuxe-product' => array(
            'width' => 600,
            'height' => 600,
            'crop' => true,
        ),
        'aqualuxe-product-thumbnail' => array(
            'width' => 300,
            'height' => 300,
            'crop' => true,
        ),
        'aqualuxe-product-gallery' => array(
            'width' => 800,
            'height' => 800,
            'crop' => true,
        ),
        'aqualuxe-product-gallery-thumbnail' => array(
            'width' => 100,
            'height' => 100,
            'crop' => true,
        ),
    ) );
}
add_filter( 'wp_generate_attachment_metadata', 'aqualuxe_media_library_image_sizes_metadata' );

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_media_library_image_sizes_srcset( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => array(
            'width' => 1200,
            'height' => 600,
            'crop' => true,
        ),
        'aqualuxe-blog' => array(
            'width' => 800,
            'height' => 450,
            'crop' => true,
        ),
        'aqualuxe-blog-grid' => array(
            'width' => 600,
            'height' => 400,
            'crop' => true,
        ),
        'aqualuxe-blog-list' => array(
            'width' => 400,
            'height' => 300,
            'crop' => true,
        ),
        'aqualuxe-product' => array(
            'width' => 600,
            'height' => 600,
            'crop' => true,
        ),
        'aqualuxe-product-thumbnail' => array(
            'width' => 300,
            'height' => 300,
            'crop' => true,
        ),
        'aqualuxe-product-gallery' => array(
            'width' => 800,
            'height' => 800,
            'crop' => true,
        ),
        'aqualuxe-product-gallery-thumbnail' => array(
            'width' => 100,
            'height' => 100,
            'crop' => true,
        ),
    ) );
}
add_filter( 'wp_calculate_image_srcset', 'aqualuxe_media_library_image_sizes_srcset' );

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_media_library_image_sizes_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => '(max-width: 1200px) 100vw, 1200px',
        'aqualuxe-blog' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-blog-grid' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-blog-list' => '(max-width: 400px) 100vw, 400px',
        'aqualuxe-product' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-product-thumbnail' => '(max-width: 300px) 100vw, 300px',
        'aqualuxe-product-gallery' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-product-gallery-thumbnail' => '(max-width: 100px) 100vw, 100px',
    ) );
}
add_filter( 'wp_calculate_image_sizes', 'aqualuxe_media_library_image_sizes_sizes' );

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_media_library_image_sizes_srcset_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => '(max-width: 1200px) 100vw, 1200px',
        'aqualuxe-blog' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-blog-grid' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-blog-list' => '(max-width: 400px) 100vw, 400px',
        'aqualuxe-product' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-product-thumbnail' => '(max-width: 300px) 100vw, 300px',
        'aqualuxe-product-gallery' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-product-gallery-thumbnail' => '(max-width: 100px) 100vw, 100px',
    ) );
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_media_library_image_sizes_srcset_sizes' );

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_media_library_image_sizes_srcset_attributes( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => '(max-width: 1200px) 100vw, 1200px',
        'aqualuxe-blog' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-blog-grid' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-blog-list' => '(max-width: 400px) 100vw, 400px',
        'aqualuxe-product' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-product-thumbnail' => '(max-width: 300px) 100vw, 300px',
        'aqualuxe-product-gallery' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-product-gallery-thumbnail' => '(max-width: 100px) 100vw, 100px',
    ) );
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_media_library_image_sizes_srcset_attributes' );

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_media_library_image_sizes_srcset_attributes_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => '(max-width: 1200px) 100vw, 1200px',
        'aqualuxe-blog' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-blog-grid' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-blog-list' => '(max-width: 400px) 100vw, 400px',
        'aqualuxe-product' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-product-thumbnail' => '(max-width: 300px) 100vw, 300px',
        'aqualuxe-product-gallery' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-product-gallery-thumbnail' => '(max-width: 100px) 100vw, 100px',
    ) );
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_media_library_image_sizes_srcset_attributes_sizes' );

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_media_library_image_sizes_srcset_attributes_sizes_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => '(max-width: 1200px) 100vw, 1200px',
        'aqualuxe-blog' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-blog-grid' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-blog-list' => '(max-width: 400px) 100vw, 400px',
        'aqualuxe-product' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-product-thumbnail' => '(max-width: 300px) 100vw, 300px',
        'aqualuxe-product-gallery' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-product-gallery-thumbnail' => '(max-width: 100px) 100vw, 100px',
    ) );
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_media_library_image_sizes_srcset_attributes_sizes_sizes' );

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_media_library_image_sizes_srcset_attributes_sizes_sizes_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => '(max-width: 1200px) 100vw, 1200px',
        'aqualuxe-blog' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-blog-grid' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-blog-list' => '(max-width: 400px) 100vw, 400px',
        'aqualuxe-product' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-product-thumbnail' => '(max-width: 300px) 100vw, 300px',
        'aqualuxe-product-gallery' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-product-gallery-thumbnail' => '(max-width: 100px) 100vw, 100px',
    ) );
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_media_library_image_sizes_srcset_attributes_sizes_sizes_sizes' );

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_media_library_image_sizes_srcset_attributes_sizes_sizes_sizes_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => '(max-width: 1200px) 100vw, 1200px',
        'aqualuxe-blog' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-blog-grid' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-blog-list' => '(max-width: 400px) 100vw, 400px',
        'aqualuxe-product' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-product-thumbnail' => '(max-width: 300px) 100vw, 300px',
        'aqualuxe-product-gallery' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-product-gallery-thumbnail' => '(max-width: 100px) 100vw, 100px',
    ) );
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_media_library_image_sizes_srcset_attributes_sizes_sizes_sizes_sizes' );

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_media_library_image_sizes_srcset_attributes_sizes_sizes_sizes_sizes_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => '(max-width: 1200px) 100vw, 1200px',
        'aqualuxe-blog' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-blog-grid' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-blog-list' => '(max-width: 400px) 100vw, 400px',
        'aqualuxe-product' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-product-thumbnail' => '(max-width: 300px) 100vw, 300px',
        'aqualuxe-product-gallery' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-product-gallery-thumbnail' => '(max-width: 100px) 100vw, 100px',
    ) );
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_media_library_image_sizes_srcset_attributes_sizes_sizes_sizes_sizes_sizes' );

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_media_library_image_sizes_srcset_attributes_sizes_sizes_sizes_sizes_sizes_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => '(max-width: 1200px) 100vw, 1200px',
        'aqualuxe-blog' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-blog-grid' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-blog-list' => '(max-width: 400px) 100vw, 400px',
        'aqualuxe-product' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-product-thumbnail' => '(max-width: 300px) 100vw, 300px',
        'aqualuxe-product-gallery' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-product-gallery-thumbnail' => '(max-width: 100px) 100vw, 100px',
    ) );
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_media_library_image_sizes_srcset_attributes_sizes_sizes_sizes_sizes_sizes_sizes' );

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_media_library_image_sizes_srcset_attributes_sizes_sizes_sizes_sizes_sizes_sizes_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => '(max-width: 1200px) 100vw, 1200px',
        'aqualuxe-blog' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-blog-grid' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-blog-list' => '(max-width: 400px) 100vw, 400px',
        'aqualuxe-product' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-product-thumbnail' => '(max-width: 300px) 100vw, 300px',
        'aqualuxe-product-gallery' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-product-gallery-thumbnail' => '(max-width: 100px) 100vw, 100px',
    ) );
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_media_library_image_sizes_srcset_attributes_sizes_sizes_sizes_sizes_sizes_sizes_sizes' );

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_media_library_image_sizes_srcset_attributes_sizes_sizes_sizes_sizes_sizes_sizes_sizes_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => '(max-width: 1200px) 100vw, 1200px',
        'aqualuxe-blog' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-blog-grid' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-blog-list' => '(max-width: 400px) 100vw, 400px',
        'aqualuxe-product' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-product-thumbnail' => '(max-width: 300px) 100vw, 300px',
        'aqualuxe-product-gallery' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-product-gallery-thumbnail' => '(max-width: 100px) 100vw, 100px',
    ) );
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_media_library_image_sizes_srcset_attributes_sizes_sizes_sizes_sizes_sizes_sizes_sizes_sizes' );

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_media_library_image_sizes_srcset_attributes_sizes_sizes_sizes_sizes_sizes_sizes_sizes_sizes_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => '(max-width: 1200px) 100vw, 1200px',
        'aqualuxe-blog' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-blog-grid' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-blog-list' => '(max-width: 400px) 100vw, 400px',
        'aqualuxe-product' => '(max-width: 600px) 100vw, 600px',
        'aqualuxe-product-thumbnail' => '(max-width: 300px) 100vw, 300px',
        'aqualuxe-product-gallery' => '(max-width: 800px) 100vw, 800px',
        'aqualuxe-product-gallery-thumbnail' => '(max-width: 100px) 100vw, 100px',
    ) );
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_media_library_image_sizes_srcset_attributes_sizes_sizes_sizes_sizes_sizes_sizes_sizes_sizes_sizes' );