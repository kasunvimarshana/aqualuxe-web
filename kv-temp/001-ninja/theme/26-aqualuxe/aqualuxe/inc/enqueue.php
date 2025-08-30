<?php
/**
 * Enqueue scripts and styles.
 *
 * @package AquaLuxe
 */

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
    // Register and enqueue styles
    wp_enqueue_style( 'aqualuxe-style', get_stylesheet_uri(), array(), AQUALUXE_VERSION );
    wp_enqueue_style( 'aqualuxe-main', AQUALUXE_URI . 'assets/css/main.css', array(), AQUALUXE_VERSION );
    
    // Add Google Fonts
    $heading_font = get_theme_mod( 'aqualuxe_heading_font', 'Playfair Display' );
    $body_font = get_theme_mod( 'aqualuxe_body_font', 'Open Sans' );
    
    $google_fonts_url = 'https://fonts.googleapis.com/css2?';
    $google_fonts_url .= 'family=' . str_replace( ' ', '+', $heading_font ) . ':wght@400;500;600;700&';
    $google_fonts_url .= 'family=' . str_replace( ' ', '+', $body_font ) . ':wght@300;400;500;600;700&';
    $google_fonts_url .= 'display=swap';
    
    wp_enqueue_style( 'aqualuxe-google-fonts', $google_fonts_url, array(), null );
    
    // Register and enqueue scripts
    wp_enqueue_script( 'aqualuxe-navigation', AQUALUXE_URI . 'assets/js/navigation.js', array(), AQUALUXE_VERSION, true );
    wp_enqueue_script( 'aqualuxe-theme', AQUALUXE_URI . 'assets/js/theme.js', array( 'jquery' ), AQUALUXE_VERSION, true );
    
    // Dark mode script
    wp_enqueue_script( 'aqualuxe-dark-mode', AQUALUXE_URI . 'assets/js/dark-mode.js', array(), AQUALUXE_VERSION, true );
    
    // Localize script for dark mode
    wp_localize_script( 'aqualuxe-dark-mode', 'aqualuxeDarkMode', array(
        'defaultScheme' => get_theme_mod( 'aqualuxe_default_color_scheme', 'light' ),
        'cookieName'    => 'aqualuxe_color_scheme',
        'cookieExpiry'  => 365, // Days
    ) );
    
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
    
    // WooCommerce specific scripts
    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_script( 'aqualuxe-woocommerce', AQUALUXE_URI . 'assets/js/woocommerce.js', array( 'jquery' ), AQUALUXE_VERSION, true );
        
        // Quick view script
        if ( get_theme_mod( 'aqualuxe_enable_quick_view', true ) ) {
            wp_enqueue_script( 'aqualuxe-quick-view', AQUALUXE_URI . 'assets/js/quick-view.js', array( 'jquery', 'wc-add-to-cart-variation' ), AQUALUXE_VERSION, true );
            
            wp_localize_script( 'aqualuxe-quick-view', 'aqualuxeQuickView', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'aqualuxe-quick-view-nonce' ),
                'i18n'    => array(
                    'loading' => esc_html__( 'Loading...', 'aqualuxe' ),
                    'close'   => esc_html__( 'Close', 'aqualuxe' ),
                ),
            ) );
        }
        
        // Wishlist script
        if ( get_theme_mod( 'aqualuxe_enable_wishlist', true ) ) {
            wp_enqueue_script( 'aqualuxe-wishlist', AQUALUXE_URI . 'assets/js/wishlist.js', array( 'jquery' ), AQUALUXE_VERSION, true );
            
            wp_localize_script( 'aqualuxe-wishlist', 'aqualuxeWishlist', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'aqualuxe-wishlist-nonce' ),
                'i18n'    => array(
                    'addToWishlist'     => esc_html__( 'Add to Wishlist', 'aqualuxe' ),
                    'removeFromWishlist' => esc_html__( 'Remove from Wishlist', 'aqualuxe' ),
                    'addedToWishlist'   => esc_html__( 'Added to Wishlist', 'aqualuxe' ),
                    'removedFromWishlist' => esc_html__( 'Removed from Wishlist', 'aqualuxe' ),
                ),
            ) );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_scripts' );

/**
 * Enqueue admin scripts and styles.
 */
function aqualuxe_admin_scripts() {
    wp_enqueue_style( 'aqualuxe-admin-style', AQUALUXE_URI . 'assets/css/admin.css', array(), AQUALUXE_VERSION );
    wp_enqueue_script( 'aqualuxe-admin-script', AQUALUXE_URI . 'assets/js/admin.js', array( 'jquery' ), AQUALUXE_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_admin_scripts' );

/**
 * Enqueue block editor assets.
 */
function aqualuxe_block_editor_assets() {
    wp_enqueue_style( 'aqualuxe-editor-style', AQUALUXE_URI . 'assets/css/editor.css', array(), AQUALUXE_VERSION );
}
add_action( 'enqueue_block_editor_assets', 'aqualuxe_block_editor_assets' );

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function aqualuxe_resource_hints( $urls, $relation_type ) {
    if ( 'preconnect' === $relation_type ) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }
    
    return $urls;
}
add_filter( 'wp_resource_hints', 'aqualuxe_resource_hints', 10, 2 );

/**
 * Preload assets for better performance.
 */
function aqualuxe_preload_assets() {
    ?>
    <link rel="preload" href="<?php echo esc_url( AQUALUXE_URI . 'assets/css/main.css' ); ?>" as="style">
    <link rel="preload" href="<?php echo esc_url( AQUALUXE_URI . 'assets/js/theme.js' ); ?>" as="script">
    <?php
}
add_action( 'wp_head', 'aqualuxe_preload_assets', 1 );

/**
 * Add defer attribute to non-critical scripts.
 *
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string Modified script tag.
 */
function aqualuxe_defer_scripts( $tag, $handle ) {
    // List of scripts to defer
    $defer_scripts = array(
        'aqualuxe-navigation',
        'aqualuxe-theme',
        'aqualuxe-dark-mode',
        'aqualuxe-woocommerce',
        'aqualuxe-quick-view',
        'aqualuxe-wishlist',
    );
    
    if ( in_array( $handle, $defer_scripts, true ) ) {
        return str_replace( ' src', ' defer src', $tag );
    }
    
    return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_defer_scripts', 10, 2 );

/**
 * Add async attribute to non-critical scripts.
 *
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string Modified script tag.
 */
function aqualuxe_async_scripts( $tag, $handle ) {
    // List of scripts to load asynchronously
    $async_scripts = array(
        'comment-reply',
    );
    
    if ( in_array( $handle, $async_scripts, true ) ) {
        return str_replace( ' src', ' async src', $tag );
    }
    
    return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_async_scripts', 10, 2 );

/**
 * Add critical CSS inline.
 */
function aqualuxe_critical_css() {
    ?>
    <style id="aqualuxe-critical-css">
        /* Critical CSS for above-the-fold content */
        :root {
            --primary-color: <?php echo esc_attr( get_theme_mod( 'aqualuxe_primary_color', '#0073aa' ) ); ?>;
            --secondary-color: <?php echo esc_attr( get_theme_mod( 'aqualuxe_secondary_color', '#005177' ) ); ?>;
            --heading-font: "<?php echo esc_attr( get_theme_mod( 'aqualuxe_heading_font', 'Playfair Display' ) ); ?>", serif;
            --body-font: "<?php echo esc_attr( get_theme_mod( 'aqualuxe_body_font', 'Open Sans' ) ); ?>", sans-serif;
        }
        
        body {
            font-family: var(--body-font);
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--heading-font);
        }
        
        .site-header {
            position: relative;
            z-index: 100;
        }
        
        .site-branding {
            display: flex;
            align-items: center;
        }
        
        .main-navigation {
            display: block;
            width: 100%;
        }
        
        .main-navigation ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        
        .main-navigation li {
            position: relative;
        }
        
        .main-navigation a {
            display: block;
            text-decoration: none;
            padding: 0.5rem 1rem;
        }
        
        .screen-reader-text {
            border: 0;
            clip: rect(1px, 1px, 1px, 1px);
            clip-path: inset(50%);
            height: 1px;
            margin: -1px;
            overflow: hidden;
            padding: 0;
            position: absolute !important;
            width: 1px;
            word-wrap: normal !important;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'aqualuxe_critical_css', 1 );