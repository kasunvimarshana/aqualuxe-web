<?php
/**
 * Critical CSS Implementation
 *
 * @package AquaLuxe
 * @subpackage Modules/Performance
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Add critical CSS to head
 *
 * @return void
 */
function aqualuxe_performance_add_critical_css() {
    if ( ! aqualuxe_is_critical_css_enabled() || is_admin() ) {
        return;
    }

    // Get critical CSS
    $critical_css = aqualuxe_performance_get_critical_css();

    if ( ! empty( $critical_css ) ) {
        echo '<style id="aqualuxe-critical-css">' . wp_strip_all_tags( $critical_css ) . '</style>' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_performance_add_critical_css', 1 );

/**
 * Get critical CSS based on current page
 *
 * @return string
 */
function aqualuxe_performance_get_critical_css() {
    $critical_css = '';
    $critical_css_dir = AQUALUXE_THEME_DIR . 'modules/performance/assets/css/critical/';

    // Create directory if it doesn't exist
    if ( ! file_exists( $critical_css_dir ) ) {
        wp_mkdir_p( $critical_css_dir );
    }

    // Get critical CSS file based on current page
    $critical_css_file = '';

    if ( is_front_page() || is_home() ) {
        $critical_css_file = $critical_css_dir . 'home.css';
    } elseif ( is_singular( 'post' ) ) {
        $critical_css_file = $critical_css_dir . 'single.css';
    } elseif ( is_page() ) {
        $critical_css_file = $critical_css_dir . 'page.css';
    } elseif ( is_archive() ) {
        $critical_css_file = $critical_css_dir . 'archive.css';
    } elseif ( is_search() ) {
        $critical_css_file = $critical_css_dir . 'search.css';
    } elseif ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
        if ( is_product() ) {
            $critical_css_file = $critical_css_dir . 'product.css';
        } elseif ( is_shop() ) {
            $critical_css_file = $critical_css_dir . 'shop.css';
        } elseif ( is_cart() ) {
            $critical_css_file = $critical_css_dir . 'cart.css';
        } elseif ( is_checkout() ) {
            $critical_css_file = $critical_css_dir . 'checkout.css';
        } else {
            $critical_css_file = $critical_css_dir . 'woocommerce.css';
        }
    }

    // Fallback to default critical CSS
    if ( empty( $critical_css_file ) || ! file_exists( $critical_css_file ) ) {
        $critical_css_file = $critical_css_dir . 'default.css';
    }

    // Create default critical CSS if it doesn't exist
    if ( ! file_exists( $critical_css_file ) ) {
        aqualuxe_performance_generate_default_critical_css( $critical_css_file );
    }

    // Get critical CSS content
    if ( file_exists( $critical_css_file ) ) {
        $critical_css = file_get_contents( $critical_css_file );
    }

    return $critical_css;
}

/**
 * Generate default critical CSS
 *
 * @param string $file File path.
 * @return void
 */
function aqualuxe_performance_generate_default_critical_css( $file ) {
    // Default critical CSS
    $critical_css = '
    /* Default Critical CSS */
    :root {
        --primary-color: #0073aa;
        --secondary-color: #005177;
        --text-color: #333333;
        --light-text-color: #767676;
        --border-color: #e0e0e0;
        --background-color: #ffffff;
        --light-background-color: #f8f8f8;
    }
    
    /* Typography */
    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        font-size: 16px;
        line-height: 1.6;
        color: var(--text-color);
        background-color: var(--background-color);
        margin: 0;
        padding: 0;
    }
    
    h1, h2, h3, h4, h5, h6 {
        margin-top: 0;
        margin-bottom: 0.5em;
        font-weight: 700;
        line-height: 1.2;
    }
    
    h1 {
        font-size: 2.5em;
    }
    
    h2 {
        font-size: 2em;
    }
    
    h3 {
        font-size: 1.75em;
    }
    
    p {
        margin-top: 0;
        margin-bottom: 1em;
    }
    
    a {
        color: var(--primary-color);
        text-decoration: none;
    }
    
    a:hover {
        color: var(--secondary-color);
    }
    
    /* Layout */
    .site {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    
    .site-content {
        flex: 1;
        padding: 2rem 0;
    }
    
    .container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }
    
    /* Header */
    .site-header {
        background-color: var(--background-color);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 1rem 0;
    }
    
    .site-header .container {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .site-branding {
        display: flex;
        align-items: center;
    }
    
    .site-title {
        font-size: 1.5em;
        margin: 0;
    }
    
    .site-title a {
        color: var(--text-color);
    }
    
    .site-description {
        margin: 0;
        font-size: 0.875em;
        color: var(--light-text-color);
    }
    
    /* Navigation */
    .main-navigation {
        display: flex;
        align-items: center;
    }
    
    .main-navigation ul {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .main-navigation li {
        position: relative;
        margin-left: 1.5rem;
    }
    
    .main-navigation a {
        display: block;
        padding: 0.5rem 0;
        color: var(--text-color);
    }
    
    .main-navigation a:hover {
        color: var(--primary-color);
    }
    
    /* Mobile menu toggle */
    .menu-toggle {
        display: none;
        background: none;
        border: none;
        font-size: 1.5em;
        padding: 0.5rem;
        cursor: pointer;
    }
    
    /* Content */
    .entry-header {
        margin-bottom: 1.5rem;
    }
    
    .entry-title {
        margin-bottom: 0.5rem;
    }
    
    .entry-meta {
        font-size: 0.875em;
        color: var(--light-text-color);
    }
    
    .entry-content {
        margin-bottom: 2rem;
    }
    
    /* Footer */
    .site-footer {
        background-color: var(--light-background-color);
        padding: 2rem 0;
        margin-top: 2rem;
    }
    
    .site-info {
        text-align: center;
        font-size: 0.875em;
        color: var(--light-text-color);
    }
    
    /* Responsive */
    @media screen and (max-width: 768px) {
        .menu-toggle {
            display: block;
        }
        
        .main-navigation ul {
            display: none;
        }
    }
    ';

    // Create directory if it doesn't exist
    $dir = dirname( $file );
    if ( ! file_exists( $dir ) ) {
        wp_mkdir_p( $dir );
    }

    // Save critical CSS
    file_put_contents( $file, $critical_css );
}

/**
 * Add async attribute to non-critical CSS
 *
 * @param string $html HTML.
 * @param string $handle Handle.
 * @param string $href HREF.
 * @param string $media Media.
 * @return string
 */
function aqualuxe_performance_async_css( $html, $handle, $href, $media ) {
    if ( ! aqualuxe_is_critical_css_enabled() || is_admin() ) {
        return $html;
    }

    // Skip critical CSS
    if ( $handle === 'aqualuxe-critical-css' ) {
        return $html;
    }

    // Skip essential styles
    $skip_handles = array( 'wp-block-library', 'wp-admin', 'admin-bar' );
    if ( in_array( $handle, $skip_handles, true ) ) {
        return $html;
    }

    // Add async attribute
    $html = str_replace( "media='$media'", "media='print' onload=&quot;this.media='$media'&quot;", $html );

    return $html;
}
add_filter( 'style_loader_tag', 'aqualuxe_performance_async_css', 10, 4 );

/**
 * Generate critical CSS for a page
 *
 * @param string $url Page URL.
 * @param string $file Output file path.
 * @return bool
 */
function aqualuxe_performance_generate_critical_css( $url, $file ) {
    // This is a simplified example. In a real theme, you would use a proper critical CSS generator.
    // For now, we'll just create a basic critical CSS file.

    // Default critical CSS
    $critical_css = '
    /* Generated Critical CSS for ' . esc_url( $url ) . ' */
    /* This is a simplified example. In a real theme, you would use a proper critical CSS generator. */
    
    /* Typography */
    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        font-size: 16px;
        line-height: 1.6;
        color: #333333;
        background-color: #ffffff;
        margin: 0;
        padding: 0;
    }
    
    h1, h2, h3, h4, h5, h6 {
        margin-top: 0;
        margin-bottom: 0.5em;
        font-weight: 700;
        line-height: 1.2;
    }
    
    h1 {
        font-size: 2.5em;
    }
    
    h2 {
        font-size: 2em;
    }
    
    h3 {
        font-size: 1.75em;
    }
    
    p {
        margin-top: 0;
        margin-bottom: 1em;
    }
    
    a {
        color: #0073aa;
        text-decoration: none;
    }
    
    /* Layout */
    .site {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    
    .site-content {
        flex: 1;
        padding: 2rem 0;
    }
    
    .container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }
    
    /* Header */
    .site-header {
        background-color: #ffffff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 1rem 0;
    }
    
    .site-header .container {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .site-branding {
        display: flex;
        align-items: center;
    }
    
    .site-title {
        font-size: 1.5em;
        margin: 0;
    }
    
    .site-title a {
        color: #333333;
    }
    
    /* Navigation */
    .main-navigation {
        display: flex;
        align-items: center;
    }
    
    .main-navigation ul {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .main-navigation li {
        position: relative;
        margin-left: 1.5rem;
    }
    
    .main-navigation a {
        display: block;
        padding: 0.5rem 0;
        color: #333333;
    }
    ';

    // Create directory if it doesn't exist
    $dir = dirname( $file );
    if ( ! file_exists( $dir ) ) {
        wp_mkdir_p( $dir );
    }

    // Save critical CSS
    return (bool) file_put_contents( $file, $critical_css );
}

/**
 * Generate critical CSS for all templates
 *
 * @return array
 */
function aqualuxe_performance_generate_all_critical_css() {
    $results = array();
    $critical_css_dir = AQUALUXE_THEME_DIR . 'modules/performance/assets/css/critical/';

    // Create directory if it doesn't exist
    if ( ! file_exists( $critical_css_dir ) ) {
        wp_mkdir_p( $critical_css_dir );
    }

    // Generate critical CSS for home page
    $home_url = home_url();
    $home_file = $critical_css_dir . 'home.css';
    $results['home'] = aqualuxe_performance_generate_critical_css( $home_url, $home_file );

    // Generate critical CSS for a single post
    $posts = get_posts( array( 'numberposts' => 1 ) );
    if ( ! empty( $posts ) ) {
        $post_url = get_permalink( $posts[0]->ID );
        $post_file = $critical_css_dir . 'single.css';
        $results['single'] = aqualuxe_performance_generate_critical_css( $post_url, $post_file );
    }

    // Generate critical CSS for a page
    $pages = get_pages( array( 'number' => 1 ) );
    if ( ! empty( $pages ) ) {
        $page_url = get_permalink( $pages[0]->ID );
        $page_file = $critical_css_dir . 'page.css';
        $results['page'] = aqualuxe_performance_generate_critical_css( $page_url, $page_file );
    }

    // Generate critical CSS for archive page
    $archive_url = get_post_type_archive_link( 'post' );
    if ( $archive_url ) {
        $archive_file = $critical_css_dir . 'archive.css';
        $results['archive'] = aqualuxe_performance_generate_critical_css( $archive_url, $archive_file );
    }

    // Generate critical CSS for search page
    $search_url = home_url( '?s=test' );
    $search_file = $critical_css_dir . 'search.css';
    $results['search'] = aqualuxe_performance_generate_critical_css( $search_url, $search_file );

    // Generate critical CSS for WooCommerce pages
    if ( class_exists( 'WooCommerce' ) ) {
        // Shop page
        $shop_url = wc_get_page_permalink( 'shop' );
        if ( $shop_url ) {
            $shop_file = $critical_css_dir . 'shop.css';
            $results['shop'] = aqualuxe_performance_generate_critical_css( $shop_url, $shop_file );
        }

        // Product page
        $products = wc_get_products( array( 'limit' => 1 ) );
        if ( ! empty( $products ) ) {
            $product_url = get_permalink( $products[0]->get_id() );
            $product_file = $critical_css_dir . 'product.css';
            $results['product'] = aqualuxe_performance_generate_critical_css( $product_url, $product_file );
        }

        // Cart page
        $cart_url = wc_get_cart_url();
        if ( $cart_url ) {
            $cart_file = $critical_css_dir . 'cart.css';
            $results['cart'] = aqualuxe_performance_generate_critical_css( $cart_url, $cart_file );
        }

        // Checkout page
        $checkout_url = wc_get_checkout_url();
        if ( $checkout_url ) {
            $checkout_file = $critical_css_dir . 'checkout.css';
            $results['checkout'] = aqualuxe_performance_generate_critical_css( $checkout_url, $checkout_file );
        }

        // General WooCommerce
        $woocommerce_file = $critical_css_dir . 'woocommerce.css';
        $results['woocommerce'] = aqualuxe_performance_generate_critical_css( $shop_url, $woocommerce_file );
    }

    // Generate default critical CSS
    $default_file = $critical_css_dir . 'default.css';
    $results['default'] = aqualuxe_performance_generate_critical_css( $home_url, $default_file );

    return $results;
}

/**
 * Add admin page for generating critical CSS
 *
 * @return void
 */
function aqualuxe_performance_add_critical_css_page() {
    add_submenu_page(
        'themes.php',
        __( 'Critical CSS', 'aqualuxe' ),
        __( 'Critical CSS', 'aqualuxe' ),
        'manage_options',
        'aqualuxe-critical-css',
        'aqualuxe_performance_critical_css_page'
    );
}
add_action( 'admin_menu', 'aqualuxe_performance_add_critical_css_page' );

/**
 * Critical CSS admin page
 *
 * @return void
 */
function aqualuxe_performance_critical_css_page() {
    // Check if user has permission
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have sufficient permissions to access this page.', 'aqualuxe' ) );
    }

    // Handle form submission
    if ( isset( $_POST['aqualuxe_generate_critical_css'] ) && check_admin_referer( 'aqualuxe_critical_css_nonce' ) ) {
        $results = aqualuxe_performance_generate_all_critical_css();
        $success = ! in_array( false, $results, true );
        
        if ( $success ) {
            echo '<div class="notice notice-success"><p>' . __( 'Critical CSS generated successfully.', 'aqualuxe' ) . '</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>' . __( 'Error generating critical CSS.', 'aqualuxe' ) . '</p></div>';
        }
    }

    // Display admin page
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__( 'Critical CSS', 'aqualuxe' ); ?></h1>
        
        <p><?php echo esc_html__( 'Generate critical CSS for your theme to improve page load performance.', 'aqualuxe' ); ?></p>
        
        <form method="post" action="">
            <?php wp_nonce_field( 'aqualuxe_critical_css_nonce' ); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php echo esc_html__( 'Critical CSS Status', 'aqualuxe' ); ?></th>
                    <td>
                        <?php
                        $critical_css_dir = AQUALUXE_THEME_DIR . 'modules/performance/assets/css/critical/';
                        $default_file = $critical_css_dir . 'default.css';
                        
                        if ( file_exists( $default_file ) ) {
                            echo '<span style="color: green;">' . esc_html__( 'Critical CSS is generated.', 'aqualuxe' ) . '</span>';
                        } else {
                            echo '<span style="color: red;">' . esc_html__( 'Critical CSS is not generated.', 'aqualuxe' ) . '</span>';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__( 'Templates', 'aqualuxe' ); ?></th>
                    <td>
                        <ul>
                            <?php
                            $templates = array(
                                'home' => __( 'Home Page', 'aqualuxe' ),
                                'single' => __( 'Single Post', 'aqualuxe' ),
                                'page' => __( 'Page', 'aqualuxe' ),
                                'archive' => __( 'Archive', 'aqualuxe' ),
                                'search' => __( 'Search', 'aqualuxe' ),
                            );
                            
                            if ( class_exists( 'WooCommerce' ) ) {
                                $templates = array_merge( $templates, array(
                                    'shop' => __( 'Shop', 'aqualuxe' ),
                                    'product' => __( 'Product', 'aqualuxe' ),
                                    'cart' => __( 'Cart', 'aqualuxe' ),
                                    'checkout' => __( 'Checkout', 'aqualuxe' ),
                                    'woocommerce' => __( 'WooCommerce (General)', 'aqualuxe' ),
                                ) );
                            }
                            
                            foreach ( $templates as $template => $label ) {
                                $file = $critical_css_dir . $template . '.css';
                                $status = file_exists( $file ) ? '<span style="color: green;">✓</span>' : '<span style="color: red;">✗</span>';
                                echo '<li>' . esc_html( $label ) . ': ' . $status . '</li>';
                            }
                            ?>
                        </ul>
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <input type="submit" name="aqualuxe_generate_critical_css" class="button button-primary" value="<?php echo esc_attr__( 'Generate Critical CSS', 'aqualuxe' ); ?>">
            </p>
        </form>
    </div>
    <?php
}