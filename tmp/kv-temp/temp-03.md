<details type="reasoning" done="false">
<summary>Thinking…</summary>
> Let me break down this complex request:
> 
> 1. The user wants me to create a complete WooCommerce child theme called "AquaLuxe" for an ornamental fish business.
> 2. The theme should be based on the Storefront parent theme.
> 3. It needs to be production-ready with full e-commerce functionality.
> 4. It should be optimized for security, SEO, and performance.
> 5. It needs to include all necessary templates and assets.
> 6. The user also wants a Dockerized solution for local development that works on both Windows and Linux.
> 7. The theme should follow WordPress and WooCommerce coding standards and best practices like SOLID, DRY, and KISS.
> 
> I'll start by creating the folder structure for the child theme, then implement all the necessary files. After that, I'll create the Docker configuration.
> 
> Let's start with the child theme structure:
> 
> ```
> aqualuxe/
> ├── style.css
> ├── functions.php
> ├── inc/
> │   ├── hooks/
> │   ├── customizer/
> │   ├── woocommerce/
> │   ├── helpers.php
> │   └── template-functions.php
> ├── assets/
> │   ├── css/
> │   │   ├── frontend/
> │   │   └── admin/
> │   ├── js/
> │   │   ├── frontend/
> │   │   └── admin/
> │   ├── fonts/
> │   └── images/
> ├── templates/
> │   ├── woocommerce/
> │   └── parts/
> ├── woocommerce/
> │   ├── global/
> │   ├── loop/
> │   ├── single-product/
> │   ├── cart/
> │   ├── checkout/
> │   ├── myaccount/
> │   └── order/
> ├── header.php
> ├── footer.php
> ├── index.php
> ├── page.php
> ├── single.php
> ├── archive.php
> ├── search.php
> ├── 404.php
> ├── sidebar.php
> └── README.md
> ```
> 
> Now, let's create each file with the necessary code:
> 
> 1. style.css - This file contains the theme header information and basic styles.
> 2. functions.php - This file will enqueue styles and scripts, set up theme features, and include necessary files.
> 3. inc/ directory - Contains custom functions, hooks, and theme setup.
> 4. assets/ directory - Contains CSS, JS, fonts, and images.
> 5. templates/ directory - Contains custom template files.
> 6. woocommerce/ directory - Contains WooCommerce template overrides.
> 7. Template files like header.php, footer.php, etc.
> 
> Let's start implementing these files:
> 
> ### style.css
> 
> ```css
> /*
> Theme Name: AquaLuxe
> Theme URI: https://github.com/kasunvimarshana
> Description: Premium WooCommerce Child Theme for Ornamental Fish Business
> Author: Kasun Vimarshana
> Author URI: https://github.com/kasunvimarshana
> Template: storefront
> Version: 1.0.0
> License: GNU General Public License v2 or later
> License URI: http://www.gnu.org/licenses/gpl-2.0.html
> Text Domain: aqualuxe
> Tags: e-commerce, woocommerce, responsive, clean, modern, multipurpose
> */
> 
> /* =Theme customization starts here
> -------------------------------------------------------------- */
> 
> /* Import parent theme styles */
> @import url("../storefront/style.css");
> 
> /* Custom AquaLuxe styles */
> :root {
>     --aqualuxe-primary: #0073aa;
>     --aqualuxe-secondary: #00a0d2;
>     --aqualuxe-accent: #2ea2cc;
>     --aqualuxe-dark: #2c4158;
>     --aqualuxe-light: #f8f9fa;
>     --aqualuxe-gray: #6c757d;
>     --aqualuxe-success: #28a745;
>     --aqualuxe-warning: #ffc107;
>     --aqualuxe-danger: #dc3545;
>     --aqualuxe-info: #17a2b8;
> }
> 
> body {
>     color: var(--aqualuxe-dark);
>     font-family: 'Source Sans Pro', sans-serif;
>     line-height: 1.6;
> }
> 
> a {
>     color: var(--aqualuxe-primary);
>     text-decoration: none;
>     transition: all 0.3s ease;
> }
> 
> a:hover,
> a:focus {
>     color: var(--aqualuxe-secondary);
>     text-decoration: underline;
> }
> 
> /* Header styles */
> .site-header {
>     background-color: #fff;
>     border-bottom: 1px solid rgba(0,0,0,0.05);
>     position: relative;
>     z-index: 9999;
> }
> 
> .site-branding {
>     padding: 1.5rem 0;
> }
> 
> .site-title {
>     font-size: 2rem;
>     font-weight: 700;
>     margin: 0;
>     line-height: 1.2;
> }
> 
> .site-title a {
>     color: var(--aqualuxe-dark);
> }
> 
> .site-description {
>     color: var(--aqualuxe-gray);
>     font-size: 1rem;
>     margin: 0.5rem 0 0;
> }
> 
> /* Navigation styles */
> .main-navigation {
>     background-color: var(--aqualuxe-primary);
> }
> 
> .main-navigation ul li a {
>     color: #fff;
>     font-weight: 500;
>     padding: 1rem;
> }
> 
> .main-navigation ul li:hover > a,
> .main-navigation ul li:focus > a {
>     background-color: var(--aqualuxe-secondary);
>     color: #fff;
> }
> 
> /* Button styles */
> .button,
> button,
> input[type="button"],
> input[type="reset"],
> input[type="submit"] {
>     background-color: var(--aqualuxe-primary);
>     border: 0;
>     border-radius: 0;
>     color: #fff;
>     font-weight: 600;
>     padding: 0.8rem 1.5rem;
>     text-transform: uppercase;
>     transition: all 0.3s ease;
> }
> 
> .button:hover,
> button:hover,
> input[type="button"]:hover,
> input[type="reset"]:hover,
> input[type="submit"]:hover {
>     background-color: var(--aqualuxe-secondary);
>     color: #fff;
> }
> 
> /* WooCommerce styles */
> .woocommerce ul.products li.product {
>     text-align: center;
> }
> 
> .woocommerce ul.products li.product .price {
>     color: var(--aqualuxe-primary);
>     font-weight: 600;
> }
> 
> .woocommerce span.onsale {
>     background-color: var(--aqualuxe-danger);
>     border-radius: 50%;
>     height: 6rem;
>     line-height: 6rem;
>     width: 6rem;
> }
> 
> .woocommerce div.product .product_title {
>     color: var(--aqualuxe-dark);
>     font-size: 2rem;
>     margin-bottom: 1rem;
> }
> 
> .woocommerce div.product p.price,
> .woocommerce div.product span.price {
>     color: var(--aqualuxe-primary);
>     font-size: 1.5rem;
>     font-weight: 600;
> }
> 
> .woocommerce div.product .woocommerce-tabs ul.tabs {
>     border-bottom: 1px solid rgba(0,0,0,0.1);
> }
> 
> .woocommerce div.product .woocommerce-tabs ul.tabs li {
>     border: 1px solid rgba(0,0,0,0.1);
>     border-bottom: 0;
>     background-color: var(--aqualuxe-light);
>     margin-right: 0.5rem;
> }
> 
> .woocommerce div.product .woocommerce-tabs ul.tabs li.active {
>     background-color: #fff;
>     border-bottom: 1px solid #fff;
>     margin-bottom: -1px;
> }
> 
> .woocommerce div.product .woocommerce-tabs ul.tabs li a {
>     color: var(--aqualuxe-dark);
>     font-weight: 600;
> }
> 
> .woocommerce-cart .cart-collaterals .cart_totals h2 {
>     background-color: var(--aqualuxe-primary);
>     color: #fff;
>     padding: 1rem;
> }
> 
> .woocommerce-checkout .woocommerce-checkout-payment .payment_methods {
>     background-color: var(--aqualuxe-light);
>     padding: 1.5rem;
> }
> 
> /* Footer styles */
> .site-footer {
>     background-color: var(--aqualuxe-dark);
>     color: #fff;
>     padding: 3rem 0;
> }
> 
> .site-footer a {
>     color: #fff;
> }
> 
> .site-footer a:hover,
> .site-footer a:focus {
>     color: var(--aqualuxe-accent);
> }
> 
> .site-footer .widget-title {
>     color: #fff;
>     font-size: 1.5rem;
>     margin-bottom: 1.5rem;
>     position: relative;
>     padding-bottom: 0.75rem;
> }
> 
> .site-footer .widget-title:after {
>     content: "";
>     position: absolute;
>     left: 0;
>     bottom: 0;
>     width: 50px;
>     height: 3px;
>     background-color: var(--aqualuxe-accent);
> }
> 
> .site-info {
>     background-color: rgba(0,0,0,0.2);
>     padding: 1.5rem 0;
>     text-align: center;
> }
> 
> /* Responsive styles */
> @media screen and (max-width: 768px) {
>     .site-title {
>         font-size: 1.5rem;
>     }
>     
>     .woocommerce ul.products li.product,
>     .woocommerce-page ul.products li.product {
>         width: 100%;
>         float: none;
>         margin-right: 0;
>     }
> }
> ```
> 
> ### functions.php
> 
> ```php
> <?php
> /**
>  * AquaLuxe functions and definitions
>  *
>  * @package AquaLuxe
>  */
> 
> /**
>  * Set the content width based on the theme's design and stylesheet.
>  */
> if ( ! isset( $content_width ) ) {
>     $content_width = 980; /* pixels */
> }
> 
> if ( ! function_exists( 'aqualuxe_setup' ) ) :
>     /**
>      * Sets up theme defaults and registers support for various WordPress features.
>      *
>      * Note that this function is hooked into the after_setup_theme hook, which
>      * runs before the init hook. The init hook is too late for some features, such
>      * as indicating support for post thumbnails.
>      */
>     function aqualuxe_setup() {
>         /*
>          * Make theme available for translation.
>          * Translations can be filed in the /languages/ directory.
>          * If you're building a theme based on AquaLuxe, use a find and replace
>          * to change 'aqualuxe' to the name of your theme in all the template files.
>          */
>         load_theme_textdomain( 'aqualuxe', get_template_directory() . '/languages' );
> 
>         // Add default posts and comments RSS feed links to head.
>         add_theme_support( 'automatic-feed-links' );
> 
>         /*
>          * Let WordPress manage the document title.
>          * By adding theme support, we declare that this theme does not use a
>          * hard-coded <title> tag in the document head, and expect WordPress to
>          * provide it for us.
>          */
>         add_theme_support( 'title-tag' );
> 
>         /*
>          * Enable support for Post Thumbnails on posts and pages.
>          *
>          * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
>          */
>         add_theme_support( 'post-thumbnails' );
> 
>         // This theme uses wp_nav_menu() in one location.
>         register_nav_menus( array(
>             'primary' => esc_html__( 'Primary Menu', 'aqualuxe' ),
>             'footer'  => esc_html__( 'Footer Menu', 'aqualuxe' ),
>         ) );
> 
>         /*
>          * Switch default core markup for search form, comment form, and comments
>          * to output valid HTML5.
>          */
>         add_theme_support( 'html5', array(
>             'search-form',
>             'comment-form',
>             'comment-list',
>             'gallery',
>             'caption',
>         ) );
> 
>         // Add theme support for selective refresh for widgets.
>         add_theme_support( 'customize-selective-refresh-widgets' );
> 
>         /**
>          * Add support for core custom logo.
>          *
>          * @link https://codex.wordpress.org/Theme_Logo
>          */
>         add_theme_support( 'custom-logo', array(
>             'height'      => 250,
>             'width'       => 250,
>             'flex-width'  => true,
>             'flex-height' => true,
>         ) );
> 
>         /**
>          * Add support for WooCommerce.
>          */
>         add_theme_support( 'woocommerce' );
>         add_theme_support( 'wc-product-gallery-zoom' );
>         add_theme_support( 'wc-product-gallery-lightbox' );
>         add_theme_support( 'wc-product-gallery-slider' );
>     }
> endif;
> add_action( 'after_setup_theme', 'aqualuxe_setup' );
> 
> /**
>  * Set the content width in pixels, based on the theme's design and stylesheet.
>  *
>  * Priority 0 to make it available to lower priority callbacks.
>  *
>  * @global int $content_width
>  */
> function aqualuxe_content_width() {
>     $GLOBALS['content_width'] = apply_filters( 'aqualuxe_content_width', 980 );
> }
> add_action( 'after_setup_theme', 'aqualuxe_content_width', 0 );
> 
> /**
>  * Register widget area.
>  *
>  * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
>  */
> function aqualuxe_widgets_init() {
>     register_sidebar( array(
>         'name'          => esc_html__( 'Sidebar', 'aqualuxe' ),
>         'id'            => 'sidebar-1',
>         'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
>         'before_widget' => '<section id="%1$s" class="widget %2$s">',
>         'after_widget'  => '</section>',
>         'before_title'  => '<h2 class="widget-title">',
>         'after_title'   => '</h2>',
>     ) );
> 
>     register_sidebar( array(
>         'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
>         'id'            => 'shop-sidebar',
>         'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
>         'before_widget' => '<section id="%1$s" class="widget %2$s">',
>         'after_widget'  => '</section>',
>         'before_title'  => '<h2 class="widget-title">',
>         'after_title'   => '</h2>',
>     ) );
> 
>     register_sidebar( array(
>         'name'          => esc_html__( 'Footer Column 1', 'aqualuxe' ),
>         'id'            => 'footer-1',
>         'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
>         'before_widget' => '<section id="%1$s" class="widget %2$s">',
>         'after_widget'  => '</section>',
>         'before_title'  => '<h2 class="widget-title">',
>         'after_title'   => '</h2>',
>     ) );
> 
>     register_sidebar( array(
>         'name'          => esc_html__( 'Footer Column 2', 'aqualuxe' ),
>         'id'            => 'footer-2',
>         'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
>         'before_widget' => '<section id="%1$s" class="widget %2$s">',
>         'after_widget'  => '</section>',
>         'before_title'  => '<h2 class="widget-title">',
>         'after_title'   => '</h2>',
>     ) );
> 
>     register_sidebar( array(
>         'name'          => esc_html__( 'Footer Column 3', 'aqualuxe' ),
>         'id'            => 'footer-3',
>         'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
>         'before_widget' => '<section id="%1$s" class="widget %2$s">',
>         'after_widget'  => '</section>',
>         'before_title'  => '<h2 class="widget-title">',
>         'after_title'   => '</h2>',
>     ) );
> 
>     register_sidebar( array(
>         'name'          => esc_html__( 'Footer Column 4', 'aqualuxe' ),
>         'id'            => 'footer-4',
>         'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
>         'before_widget' => '<section id="%1$s" class="widget %2$s">',
>         'after_widget'  => '</section>',
>         'before_title'  => '<h2 class="widget-title">',
>         'after_title'   => '</h2>',
>     ) );
> }
> add_action( 'widgets_init', 'aqualuxe_widgets_init' );
> 
> /**
>  * Enqueue scripts and styles.
>  */
> function aqualuxe_scripts() {
>     wp_enqueue_style( 'aqualuxe-style', get_stylesheet_uri() );
> 
>     wp_enqueue_style( 'aqualuxe-fonts', aqualuxe_fonts_url(), array(), null );
> 
>     wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), '4.5.0' );
> 
>     wp_enqueue_style( 'aqualuxe-main', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0' );
> 
>     wp_enqueue_script( 'aqualuxe-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), '20151215', true );
> 
>     wp_enqueue_script( 'aqualuxe-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), '20151215', true );
> 
>     wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), '4.5.0', true );
> 
>     wp_enqueue_script( 'aqualuxe-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true );
> 
>     if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
>         wp_enqueue_script( 'comment-reply' );
>     }
> }
> add_action( 'wp_enqueue_scripts', 'aqualuxe_scripts' );
> 
> /**
>  * Implement the Custom Header feature.
>  */
> require get_template_directory() . '/inc/custom-header.php';
> 
> /**
>  * Custom template tags for this theme.
>  */
> require get_template_directory() . '/inc/template-tags.php';
> 
> /**
>  * Functions which enhance the theme by hooking into WordPress.
>  */
> require get_template_directory() . '/inc/template-functions.php';
> 
> /**
>  * Customizer additions.
>  */
> require get_template_directory() . '/inc/customizer.php';
> 
> /**
>  * Load WooCommerce compatibility file.
>  */
> require get_template_directory() . '/inc/woocommerce.php';
> 
> /**
>  * Load custom hooks.
>  */
> require get_template_directory() . '/inc/hooks/hooks.php';
> 
> /**
>  * Load helper functions.
>  */
> require get_template_directory() . '/inc/helpers.php';
> 
> /**
>  * Load demo content importer.
>  */
> require get_template_directory() . '/inc/demo-content.php';
> 
> /**
>  * Load SEO functions.
>  */
> require get_template_directory() . '/inc/seo.php';
> 
> /**
>  * Load security functions.
>  */
> require get_template_directory() . '/inc/security.php';
> 
> /**
>  * Load performance optimizations.
>  */
> require get_template_directory() . '/inc/performance.php';
> 
> /**
>  * Google Fonts URL
>  */
> function aqualuxe_fonts_url() {
>     $fonts_url = '';
>     $fonts     = array();
>     $subsets   = 'latin,latin-ext';
> 
>     /* translators: If there are characters in your language that are not supported by Source Sans Pro, translate this to 'off'. Do not translate into your own language. */
>     if ( 'off' !== _x( 'on', 'Source Sans Pro font: on or off', 'aqualuxe' ) ) {
>         $fonts[] = 'Source Sans Pro:300,300i,400,400i,600,600i,700,700i,900,900i';
>     }
> 
>     if ( $fonts ) {
>         $fonts_url = add_query_arg( array(
>             'family' => urlencode( implode( '|', $fonts ) ),
>             'subset' => urlencode( $subsets ),
>         ), 'https://fonts.googleapis.com/css' );
>     }
> 
>     return esc_url_raw( $fonts_url );
> }
> 
> /**
>  * Add a pingback url auto-discovery header for single posts, pages, or attachments.
>  */
> function aqualuxe_pingback_header() {
>     if ( is_singular() && pings_open() ) {
>         printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
>     }
> }
> add_action( 'wp_head', 'aqualuxe_pingback_header' );
> 
> /**
>  * Add custom image sizes
>  */
> function aqualuxe_custom_image_sizes() {
>     add_image_size( 'aqualuxe-shop-thumbnail', 300, 300, true );
>     add_image_size( 'aqualuxe-shop-catalog', 400, 400, true );
>     add_image_size( 'aqualuxe-shop-single', 600, 600, true );
> }
> add_action( 'after_setup_theme', 'aqualuxe_custom_image_sizes' );
> 
> /**
>  * Change number or products per row to 4
>  */
> add_filter('loop_shop_columns', 'aqualuxe_loop_columns');
> if (!function_exists('aqualuxe_loop_columns')) {
>     function aqualuxe_loop_columns() {
>         return 4; // 4 products per row
>     }
> }
> 
> /**
>  * Redefine woocommerce_output_related_products()
>  */
> function woocommerce_output_related_products() {
>     woocommerce_related_products(4,4); // Display 4 products in rows of 4
> }
> 
> /**
>  * WooCommerce Breadcrumb
>  */
> add_filter( 'woocommerce_breadcrumb_defaults', 'aqualuxe_change_breadcrumb_delimiter' );
> function aqualuxe_change_breadcrumb_delimiter( $defaults ) {
>     // Change the breadcrumb delimiters from '/' to '>'
>     $defaults['delimiter'] = ' <span class="breadcrumb-separator">/</span> ';
>     return $defaults;
> }
> 
> /**
>  * Remove default WooCommerce wrapper
>  */
> remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
> remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
> 
> /**
>  * Add custom WooCommerce wrapper
>  */
> add_action('woocommerce_before_main_content', 'aqualuxe_wrapper_start', 10);
> add_action('woocommerce_after_main_content', 'aqualuxe_wrapper_end', 10);
> 
> function aqualuxe_wrapper_start() {
>     echo '<div id="primary" class="content-area"><main id="main" class="site-main" role="main"><div class="container"><div class="row"><div class="col-lg-9">';
> }
> 
> function aqualuxe_wrapper_end() {
>     echo '</div><!-- .col-lg-9 -->';
>     get_sidebar('shop');
>     echo '</div><!-- .row --></div><!-- .container --></main><!-- #main --></div><!-- #primary -->';
> }
> ```
> 
> Now let's create the inc directory and its files:
> 
> ### inc/custom-header.php
> 
> ```php
> <?php
> /**
>  * Custom header implementation
>  *
>  * @link https://codex.wordpress.org/Custom_Headers
>  *
>  * @package AquaLuxe
>  */
> 
> /**
>  * Set up the WordPress core custom header feature.
>  *
>  * @uses aqualuxe_header_style()
>  */
> function aqualuxe_custom_header_setup() {
>     add_theme_support( 'custom-header', apply_filters( 'aqualuxe_custom_header_args', array(
>         'default-image'          => '',
>         'default-text-color'     => '000000',
>         'width'                  => 1920,
>         'height'                 => 500,
>         'flex-height'            => true,
>         'wp-head-callback'       => 'aqualuxe_header_style',
>     ) ) );
> }
> add_action( 'after_setup_theme', 'aqualuxe_custom_header_setup' );
> 
> if ( ! function_exists( 'aqualuxe_header_style' ) ) :
>     /**
>      * Styles the header image and text displayed on the blog.
>      *
>      * @see aqualuxe_custom_header_setup().
>      */
>     function aqualuxe_header_style() {
>         $header_text_color = get_header_textcolor();
> 
>         /*
>          * If no custom options for text are set, let's bail.
>          * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header', array(
>          *   'header-text' => true
>          * ) );
>          */
>         if ( get_theme_support( 'custom-header', 'header-text' ) === false ) {
>             return;
>         }
> 
>         // If we get this far, we have custom styles. Let's do this.
>         ?>
>         <style type="text/css">
>         <?php
>         // Has the text been hidden?
>         if ( 'blank' === $header_text_color ) :
>     ?>
>         .site-title,
>         .site-description {
>             position: absolute;
>             clip: rect(1px, 1px, 1px, 1px);
>         }
>     <?php
>         // If the user has set a custom color for the text use that.
>         else :
>     ?>
>         .site-title a,
>         .site-description {
>             color: #<?php echo esc_attr( $header_text_color ); ?>;
>         }
>     <?php endif; ?>
>         </style>
>         <?php
>     }
> endif;
> ```
> 
> ### inc/template-tags.php
> 
> ```php
> <?php
> /**
>  * Custom template tags for this theme.
>  *
>  * Eventually, some of the functionality here could be replaced by core features.
>  *
>  * @package AquaLuxe
>  */
> 
> if ( ! function_exists( 'aqualuxe_posted_on' ) ) :
>     /**
>      * Prints HTML with meta information for the current post-date/time and author.
>      */
>     function aqualuxe_posted_on() {
>         $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
>         if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
>             $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
>         }
> 
>         $time_string = sprintf( $time_string,
>             esc_attr( get_the_date( 'c' ) ),
>             esc_html( get_the_date() ),
>             esc_attr( get_the_modified_date( 'c' ) ),
>             esc_html( get_the_modified_date() )
>         );
> 
>         $posted_on = sprintf(
>             /* translators: %s: post date. */
>             esc_html_x( 'Posted on %s', 'post date', 'aqualuxe' ),
>             '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
>         );
> 
>         $byline = sprintf(
>             /* translators: %s: post author. */
>             esc_html_x( 'by %s', 'post author', 'aqualuxe' ),
>             '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
>         );
> 
>         echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.
> 
>     }
> endif;
> 
> if ( ! function_exists( 'aqualuxe_entry_footer' ) ) :
>     /**
>      * Prints HTML with meta information for the categories, tags and comments.
>      */
>     function aqualuxe_entry_footer() {
>         // Hide category and tag text for pages.
>         if ( 'post' === get_post_type() ) {
>             /* translators: used between list items, there is a space after the comma */
>             $categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
>             if ( $categories_list ) {
>                 /* translators: 1: list of categories. */
>                 printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'aqualuxe' ) . '</span>', $categories_list ); // WPCS: XSS OK.
>             }
> 
>             /* translators: used between list items, there is a space after the comma */
>             $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
>             if ( $tags_list ) {
>                 /* translators: 1: list of tags. */
>                 printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'aqualuxe' ) . '</span>', $tags_list ); // WPCS: XSS OK.
>             }
>         }
> 
>         if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
>             echo '<span class="comments-link">';
>             comments_popup_link(
>                 sprintf(
>                     wp_kses(
>                         /* translators: %s: post title */
>                         __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe' ),
>                         array(
>                             'span' => array(
>                                 'class' => array(),
>                             ),
>                         )
>                     ),
>                     get_the_title()
>                 )
>             );
>             echo '</span>';
>         }
> 
>         edit_post_link(
>             sprintf(
>                 wp_kses(
>                     /* translators: %s: Name of current post. Only visible to screen readers */
>                     __( 'Edit <span class="screen-reader-text">%s</span>', 'aqualuxe' ),
>                     array(
>                         'span' => array(
>                             'class' => array(),
>                         ),
>                     )
>                 ),
>                 get_the_title()
>             ),
>             '<span class="edit-link">',
>             '</span>'
>         );
>     }
> endif;
> 
> if ( ! function_exists( 'aqualuxe_post_thumbnail' ) ) :
>     /**
>      * Displays an optional post thumbnail.
>      *
>      * Wraps the post thumbnail in an anchor element on index views, or a div
>      * element when on single views.
>      */
>     function aqualuxe_post_thumbnail() {
>         if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
>             return;
>         }
> 
>         if ( is_singular() ) :
>             ?>
> 
>             <div class="post-thumbnail">
>                 <?php the_post_thumbnail(); ?>
>             </div><!-- .post-thumbnail -->
> 
>         <?php else : ?>
> 
>             <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
>                 <?php
>                 the_post_thumbnail( 'post-thumbnail', array(
>                     'alt' => the_title_attribute( array(
>                         'echo' => false,
>                     ) ),
>                 ) );
>                 ?>
>             </a>
> 
>             <?php
>         endif; // End is_singular().
>     }
> endif;
> ```
> 
> ### inc/template-functions.php
> 
> ```php
> <?php
> /**
>  * Functions which enhance the theme by hooking into WordPress
>  *
>  * @package AquaLuxe
>  */
> 
> /**
>  * Adds custom classes to the array of body classes.
>  *
>  * @param array $classes Classes for the body element.
>  * @return array
>  */
> function aqualuxe_body_classes( $classes ) {
>     // Adds a class of hfeed to non-singular pages.
>     if ( ! is_singular() ) {
>         $classes[] = 'hfeed';
>     }
> 
>     // Adds a class of no-sidebar when there is no sidebar present.
>     if ( ! is_active_sidebar( 'sidebar-1' ) ) {
>         $classes[] = 'no-sidebar';
>     }
> 
>     return $classes;
> }
> add_filter( 'body_class', 'aqualuxe_body_classes' );
> 
> /**
>  * Add a pingback url auto-discovery header for single posts, pages, or attachments.
>  */
> function aqualuxe_pingback_header() {
>     if ( is_singular() && pings_open() ) {
>         printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
>     }
> }
> add_action( 'wp_head', 'aqualuxe_pingback_header' );
> 
> /**
>  * Add WooCommerce cart icon to menu
>  */
> function aqualuxe_add_cart_to_menu( $items, $args ) {
>     if ( $args->theme_location == 'primary' ) {
>         $items .= '<li class="menu-item menu-item-type-cart menu-item-type-woocommerce-cart">';
>         $items .= '<a href="' . wc_get_cart_url() . '" class="cart-contents">';
>         $items .= '<i class="fas fa-shopping-cart"></i> ';
>         $items .= '<span class="cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
>         $items .= '</a>';
>         $items .= '</li>';
>     }
>     return $items;
> }
> add_filter( 'wp_nav_menu_items', 'aqualuxe_add_cart_to_menu', 10, 2 );
> 
> /**
>  * Ensure cart contents update when products are added to the cart via AJAX
>  */
> function aqualuxe_add_to_cart_fragment( $fragments ) {
>     ob_start();
>     ?>
>     <a href="<?php echo wc_get_cart_url(); ?>" class="cart-contents">
>         <i class="fas fa-shopping-cart"></i> 
>         <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
>     </a>
>     <?php
>     $fragments['a.cart-contents'] = ob_get_clean();
>     return $fragments;
> }
> add_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_add_to_cart_fragment' );
> 
> /**
>  * Change number of products that are displayed per page (shop page)
>  */
> function aqualuxe_shop_per_page( $cols ) {
>     // $cols contains the current number of products per page
>     $cols = 12;
>     return $cols;
> }
> add_filter( 'loop_shop_per_page', 'aqualuxe_shop_per_page', 20 );
> 
> /**
>  * Change number of related products on product page
>  * Set your own value for 'posts_per_page'
>  */
> function aqualuxe_related_products_args( $args ) {
>     $args['posts_per_page'] = 4; // 4 related products
>     $args['columns'] = 4; // arranged in 4 columns
>     return $args;
> }
> add_filter( 'woocommerce_output_related_products_args', 'aqualuxe_related_products_args' );
> 
> /**
>  * Change number of upsells on product page
>  * Set your own value for 'posts_per_page'
>  */
> function aqualuxe_upsell_products_args( $args ) {
>     $args['posts_per_page'] = 4; // 4 upsell products
>     $args['columns'] = 4; // arranged in 4 columns
>     return $args;
> }
> add_filter( 'woocommerce_upsell_display_args', 'aqualuxe_upsell_products_args' );
> 
> /**
>  * Change number of cross-sells on cart page
>  * Set your own value for 'posts_per_page'
>  */
> function aqualuxe_cross_sells_total( $limit ) {
>     $limit = 4; // 4 cross-sell products
>     return $limit;
> }
> add_filter( 'woocommerce_cross_sells_total', 'aqualuxe_cross_sells_total' );
> 
> /**
>  * Change cross-sells columns
>  */
> function aqualuxe_cross_sells_columns( $columns ) {
>     $columns = 4; // 4 columns
>     return $columns;
> }
> add_filter( 'woocommerce_cross_sells_columns', 'aqualuxe_cross_sells_columns' );
> 
> /**
>  * Add custom body classes for WooCommerce
>  */
> function aqualuxe_woocommerce_body_class( $classes ) {
>     if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
>         $classes[] = 'woocommerce-page';
>     }
>     return $classes;
> }
> add_filter( 'body_class', 'aqualuxe_woocommerce_body_class' );
> 
> /**
>  * Add custom image sizes attribute to enhance responsive image functionality
>  * for content images
>  *
>  * @param string $sizes A source size value for use in a 'sizes' attribute.
>  * @param array  $size  Image size. Accepts an array of width and height
>  *                      values in pixels (in that order).
>  * @return string A source size value for use in a content image 'sizes' attribute.
>  */
> function aqualuxe_content_image_sizes_attr( $sizes, $size ) {
>     $width = $size[0];
> 
>     840 <= $width && $sizes = '(max-width: 1200px) 85vw, 1200px';
> 
>     if ( 'page' === get_post_type() ) {
>         840 <= $width && $sizes = '(max-width: 1200px) 85vw, 1200px';
>     }
> 
>     return $sizes;
> }
> add_filter( 'wp_calculate_image_sizes', 'aqualuxe_content_image_sizes_attr', 10, 2 );
> 
> /**
>  * Add custom image sizes attribute to enhance responsive image functionality
>  * for post thumbnails
>  *
>  * @param array $attr Attributes for the image markup.
>  * @param int   $attachment Image attachment ID.
>  * @param array $size Registered image size or flat array of height and width dimensions.
>  * @return string A source size value for use in a post thumbnail 'sizes' attribute.
>  */
> function aqualuxe_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
>     if ( 'post-thumbnail' === $size ) {
>         is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';
>         ! is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px';
>     }
>     return $attr;
> }
> add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_post_thumbnail_sizes_attr', 10, 3 );
> 
> /**
>  * Add custom product tabs
>  */
> function aqualuxe_custom_product_tabs( $tabs ) {
>     // Adds the new tab
>     $tabs['care_guide'] = array(
>         'title'     => __( 'Care Guide', 'aqualuxe' ),
>         'priority'  => 50,
>         'callback'  => 'aqualuxe_care_guide_tab_content'
>     );
>     return $tabs;
> }
> add_filter( 'woocommerce_product_tabs', 'aqualuxe_custom_product_tabs' );
> 
> /**
>  * Function that displays output for the care guide tab
>  */
> function aqualuxe_care_guide_tab_content() {
>     // The new tab content
>     echo '<h2>' . __( 'Care Guide', 'aqualuxe' ) . '</h2>';
>     echo '<p>' . __( 'Here you can add information about how to care for this fish.', 'aqualuxe' ) . '</p>';
> }
> 
> /**
>  * Add custom product data fields
>  */
> function aqualuxe_custom_product_fields() {
>     woocommerce_wp_text_input(
>         array(
>             'id'          => '_water_temperature',
>             'label'       => __( 'Water Temperature', 'aqualuxe' ),
>             'placeholder' => 'e.g. 72-82°F',
>             'desc_tip'    => 'true',
>             'description' => __( 'Enter the ideal water temperature for this fish.', 'aqualuxe' )
>         )
>     );
> 
>     woocommerce_wp_text_input(
>         array(
>             'id'          => '_ph_level',
>             'label'       => __( 'pH Level', 'aqualuxe' ),
>             'placeholder' => 'e.g. 6.5-7.5',
>             'desc_tip'    => 'true',
>             'description' => __( 'Enter the ideal pH level for this fish.', 'aqualuxe' )
>         )
>     );
> 
>     woocommerce_wp_text_input(
>         array(
>             'id'          => '_tank_size',
>             'label'       => __( 'Minimum Tank Size', 'aqualuxe' ),
>             'placeholder' => 'e.g. 20 gallons',
>             'desc_tip'    => 'true',
>             'description' => __( 'Enter the minimum tank size required for this fish.', 'aqualuxe' )
>         )
>     );
> }
> add_action( 'woocommerce_product_options_general_product_data', 'aqualuxe_custom_product_fields' );
> 
> /**
>  * Save custom product data fields
>  */
> function aqualuxe_save_custom_product_fields( $post_id ) {
>     $water_temperature = isset( $_POST['_water_temperature'] ) ? sanitize_text_field( $_POST['_water_temperature'] ) : '';
>     $ph_level = isset( $_POST['_ph_level'] ) ? sanitize_text_field( $_POST['_ph_level'] ) : '';
>     $tank_size = isset( $_POST['_tank_size'] ) ? sanitize_text_field( $_POST['_tank_size'] ) : '';
> 
>     update_post_meta( $post_id, '_water_temperature', $water_temperature );
>     update_post_meta( $post_id, '_ph_level', $ph_level );
>     update_post_meta( $post_id, '_tank_size', $tank_size );
> }
> add_action( 'woocommerce_process_product_meta', 'aqualuxe_save_custom_product_fields' );
> 
> /**
>  * Display custom product data on the product page
>  */
> function aqualuxe_display_custom_product_data() {
>     global $product;
> 
>     $water_temperature = $product->get_meta( '_water_temperature' );
>     $ph_level = $product->get_meta( '_ph_level' );
>     $tank_size = $product->get_meta( '_tank_size' );
> 
>     if ( $water_temperature || $ph_level || $tank_size ) {
>         echo '<div class="aqualuxe-product-specs">';
>         echo '<h3>' . __( 'Product Specifications', 'aqualuxe' ) . '</h3>';
>         echo '<ul>';
> 
>         if ( $water_temperature ) {
>             echo '<li><strong>' . __( 'Water Temperature:', 'aqualuxe' ) . '</strong> ' . esc_html( $water_temperature ) . '</li>';
>         }
> 
>         if ( $ph_level ) {
>             echo '<li><strong>' . __( 'pH Level:', 'aqualuxe' ) . '</strong> ' . esc_html( $ph_level ) . '</li>';
>         }
> 
>         if ( $tank_size ) {
>             echo '<li><strong>' . __( 'Minimum Tank Size:', 'aqualuxe' ) . '</strong> ' . esc_html( $tank_size ) . '</li>';
>         }
> 
>         echo '</ul>';
>         echo '</div>';
>     }
> }
> add_action( 'woocommerce_product_meta_start', 'aqualuxe_display_custom_product_data' );
> ```
> 
> ### inc/customizer.php
> 
> ```php
> <?php
> /**
>  * AquaLuxe Theme Customizer
>  *
>  * @package AquaLuxe
>  */
> 
> /**
>  * Add postMessage support for site title and description for the Theme Customizer.
>  *
>  * @param WP_Customize_Manager $wp_customize Theme Customizer object.
>  */
> function aqualuxe_customize_register( $wp_customize ) {
>     $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
>     $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
>     $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
> 
>     if ( isset( $wp_customize->selective_refresh ) ) {
>         $wp_customize->selective_refresh->add_partial( 'blogname', array(
>             'selector'        => '.site-title a',
>             'render_callback' => 'aqualuxe_customize_partial_blogname',
>         ) );
>         $wp_customize->selective_refresh->add_partial( 'blogdescription', array(
>             'selector'        => '.site-description',
>             'render_callback' => 'aqualuxe_customize_partial_blogdescription',
>         ) );
>     }
> 
>     // Add custom colors
>     $wp_customize->add_section( 'aqualuxe_colors', array(
>         'title'    => __( 'Theme Colors', 'aqualuxe' ),
>         'priority' => 120,
>     ) );
> 
>     // Primary color
>     $wp_customize->add_setting( 'aqualuxe_primary_color', array(
>         'default'           => '#0073aa',
>         'sanitize_callback' => 'sanitize_hex_color',
>         'transport'         => 'postMessage',
>     ) );
> 
>     $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_primary_color', array(
>         'label'    => __( 'Primary Color', 'aqualuxe' ),
>         'section'  => 'aqualuxe_colors',
>         'settings' => 'aqualuxe_primary_color',
>     ) ) );
> 
>     // Secondary color
>     $wp_customize->add_setting( 'aqualuxe_secondary_color', array(
>         'default'           => '#00a0d2',
>         'sanitize_callback' => 'sanitize_hex_color',
>         'transport'         => 'postMessage',
>     ) );
> 
>     $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_secondary_color', array(
>         'label'    => __( 'Secondary Color', 'aqualuxe' ),
>         'section'  => 'aqualuxe_colors',
>         'settings' => 'aqualuxe_secondary_color',
>     ) ) );
> 
>     // Accent color
>     $wp_customize->add_setting( 'aqualuxe_accent_color', array(
>         'default'           => '#2ea2cc',
>         'sanitize_callback' => 'sanitize_hex_color',
>         'transport'         => 'postMessage',
>     ) );
> 
>     $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_accent_color', array(
>         'label'    => __( 'Accent Color', 'aqualuxe' ),
>         'section'  => 'aqualuxe_colors',
>         'settings' => 'aqualuxe_accent_color',
>     ) ) );
> 
>     // Dark color
>     $wp_customize->add_setting( 'aqualuxe_dark_color', array(
>         'default'           => '#2c4158',
>         'sanitize_callback' => 'sanitize_hex_color',
>         'transport'         => 'postMessage',
>     ) );
> 
>     $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_dark_color', array(
>         'label'    => __( 'Dark Color', 'aqualuxe' ),
>         'section'  => 'aqualuxe_colors',
>         'settings' => 'aqualuxe_dark_color',
>     ) ) );
> 
>     // Light color
>     $wp_customize->add_setting( 'aqualuxe_light_color', array(
>         'default'           => '#f8f9fa',
>         'sanitize_callback' => 'sanitize_hex_color',
>         'transport'         => 'postMessage',
>     ) );
> 
>     $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_light_color', array(
>         'label'    => __( 'Light Color', 'aqualuxe' ),
>         'section'  => 'aqualuxe_colors',
>         'settings' => 'aqualuxe_light_color',
>     ) ) );
> 
>     // Typography
>     $wp_customize->add_section( 'aqualuxe_typography', array(
>         'title'    => __( 'Typography', 'aqualuxe' ),
>         'priority' => 130,
>     ) );
> 
>     // Body font size
>     $wp_customize->add_setting( 'aqualuxe_body_font_size', array(
>         'default'           => '16',
>         'sanitize_callback' => 'absint',
>         'transport'         => 'postMessage',
>     ) );
> 
>     $wp_customize->add_control( 'aqualuxe_body_font_size', array(
>         'label'       => __( 'Body Font Size (px)', 'aqualuxe' ),
>         'section'     => 'aqualuxe_typography',
>         'type'        => 'number',
>         'input_attrs' => array(
>             'min' => 12,
>             'max' => 24,
>         ),
>     ) );
> 
>     // Heading font size
>     $wp_customize->add_setting( 'aqualuxe_heading_font_size', array(
>         'default'           => '32',
>         'sanitize_callback' => 'absint',
>         'transport'         => 'postMessage',
>     ) );
> 
>     $wp_customize->add_control( 'aqualuxe_heading_font_size', array(
>         'label'       => __( 'Heading Font Size (px)', 'aqualuxe' ),
>         'section'     => 'aqualuxe_typography',
>         'type'        => 'number',
>         'input_attrs' => array(
>             'min' => 24,
>             'max' => 72,
>         ),
>     ) );
> 
>     // Layout options
>     $wp_customize->add_section( 'aqualuxe_layout', array(
>         'title'    => __( 'Layout Options', 'aqualuxe' ),
>         'priority' => 140,
>     ) );
> 
>     // Container width
>     $wp_customize->add_setting( 'aqualuxe_container_width', array(
>         'default'           => '1140',
>         'sanitize_callback' => 'absint',
>         'transport'         => 'postMessage',
>     ) );
> 
>     $wp_customize->add_control( 'aqualuxe_container_width', array(
>         'label'       => __( 'Container Width (px)', 'aqualuxe' ),
>         'section'     => 'aqualuxe_layout',
>         'type'        => 'number',
>         'input_attrs' => array(
>             'min' => 980,
>             'max' => 1920,
>         ),
>     ) );
> 
>     // Product columns
>     $wp_customize->add_setting( 'aqualuxe_product_columns', array(
>         'default'           => '4',
>         'sanitize_callback' => 'absint',
>         'transport'         => 'postMessage',
>     ) );
> 
>     $wp_customize->add_control( 'aqualuxe_product_columns', array(
>         'label'       => __( 'Product Columns', 'aqualuxe' ),
>         'section'     => 'aqualuxe_layout',
>         'type'        => 'number',
>         'input_attrs' => array(
>             'min' => 2,
>             'max' => 6,
>         ),
>     ) );
> 
>     // Shop page layout
>     $wp_customize->add_setting( 'aqualuxe_shop_layout', array(
>         'default'           => 'right-sidebar',
>         'sanitize_callback' => 'aqualuxe_sanitize_shop_layout',
>         'transport'         => 'postMessage',
>     ) );
> 
>     $wp_customize->add_control( 'aqualuxe_shop_layout', array(
>         'label'    => __( 'Shop Page Layout', 'aqualuxe' ),
>         'section'  => 'aqualuxe_layout',
>         'type'     => 'select',
>         'choices'  => array(
>             'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
>             'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
>             'full-width'    => __( 'Full Width', 'aqualuxe' ),
>         ),
>     ) );
> 
>     // Header options
>     $wp_customize->add_section( 'aqualuxe_header', array(
>         'title'    => __( 'Header Options', 'aqualuxe' ),
>         'priority' => 150,
>     ) );
> 
>     // Sticky header
>     $wp_customize->add_setting( 'aqualuxe_sticky_header', array(
>         'default'           => '1',
>         'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
>         'transport'         => 'postMessage',
>     ) );
> 
>     $wp_customize->add_control( 'aqualuxe_sticky_header', array(
>         'label'    => __( 'Enable Sticky Header', 'aqualuxe' ),
>         'section'  => 'aqualuxe_header',
>         'type'     => 'checkbox',
>     ) );
> 
>     // Footer options
>     $wp_customize->add_section( 'aqualuxe_footer', array(
>         'title'    => __( 'Footer Options', 'aqualuxe' ),
>         'priority' => 160,
>     ) );
> 
>     // Footer widgets columns
>     $wp_customize->add_setting( 'aqualuxe_footer_widgets_columns', array(
>         'default'           => '4',
>         'sanitize_callback' => 'absint',
>         'transport'         => 'postMessage',
>     ) );
> 
>     $wp_customize->add_control( 'aqualuxe_footer_widgets_columns', array(
>         'label'       => __( 'Footer Widgets Columns', 'aqualuxe' ),
>         'section'     => 'aqualuxe_footer',
>         'type'        => 'number',
>         'input_attrs' => array(
>             'min' => 1,
>             'max' => 4,
>         ),
>     ) );
> 
>     // Footer copyright text
>     $wp_customize->add_setting( 'aqualuxe_footer_copyright', array(
>         'default'           => __( 'Copyright &copy; {year} {site_title}. All Rights Reserved.', 'aqualuxe' ),
>         'sanitize_callback' => 'wp_kses_post',
>         'transport'         => 'postMessage',
>     ) );
> 
>     $wp_customize->add_control( 'aqualuxe_footer_copyright', array(
>         'label'    => __( 'Footer Copyright Text', 'aqualuxe' ),
>         'section'  => 'aqualuxe_footer',
>         'type'     => 'textarea',
>     ) );
> }
> add_action( 'customize_register', 'aqualuxe_customize_register' );
> 
> /**
>  * Render the site title for the selective refresh partial.
>  *
>  * @return void
>  */
> function aqualuxe_customize_partial_blogname() {
>     bloginfo( 'name' );
> }
> 
> /**
>  * Render the site tagline for the selective refresh partial.
>  *
>  * @return void
>  */
> function aqualuxe_customize_partial_blogdescription() {
>     bloginfo( 'description' );
> }
> 
> /**
>  * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
>  */
> function aqualuxe_customize_preview_js() {
>     wp_enqueue_script( 'aqualuxe-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
> }
> add_action( 'customize_preview_init', 'aqualuxe_customize_preview_js' );
> 
> /**
>  * Sanitize checkbox
>  */
> function aqualuxe_sanitize_checkbox( $checked ) {
>     return ( ( isset( $checked ) && true == $checked ) ? true : false );
> }
> 
> /**
>  * Sanitize shop layout
>  */
> function aqualuxe_sanitize_shop_layout( $layout ) {
>     if ( ! in_array( $layout, array( 'right-sidebar', 'left-sidebar', 'full-width' ) ) ) {
>         $layout = 'right-sidebar';
>     }
>     return $layout;
> }
> 
> /**
>  * Output customizer styles
>  */
> function aqualuxe_customizer_styles() {
>     $primary_color = get_theme_mod( 'aqualuxe_primary_color', '#0073aa' );
>     $secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#00a0d2' );
>     $accent_color = get_theme_mod( 'aqualuxe_accent_color', '#2ea2cc' );
>     $dark_color = get_theme_mod( 'aqualuxe_dark_color', '#2c4158' );
>     $light_color = get_theme_mod( 'aqualuxe_light_color', '#f8f9fa' );
>     $body_font_size = get_theme_mod( 'aqualuxe_body_font_size', '16' );
>     $heading_font_size = get_theme_mod( 'aqualuxe_heading_font_size', '32' );
>     $container_width = get_theme_mod( 'aqualuxe_container_width', '1140' );
>     $product_columns = get_theme_mod( 'aqualuxe_product_columns', '4' );
>     $shop_layout = get_theme_mod( 'aqualuxe_shop_layout', 'right-sidebar' );
>     $sticky_header = get_theme_mod( 'aqualuxe_sticky_header', '1' );
>     $footer_widgets_columns = get_theme_mod( 'aqualuxe_footer_widgets_columns', '4' );
>     $footer_copyright = get_theme_mod( 'aqualuxe_footer_copyright', __( 'Copyright &copy; {year} {site_title}. All Rights Reserved.', 'aqualuxe' ) );
> 
>     $custom_css = "
>         :root {
>             --aqualuxe-primary: {$primary_color};
>             --aqualuxe-secondary: {$secondary_color};
>             --aqualuxe-accent: {$accent_color};
>             --aqualuxe-dark: {$dark_color};
>             --aqualuxe-light: {$light_color};
>             --aqualuxe-body-font-size: {$body_font_size}px;
>             --aqualuxe-heading-font-size: {$heading_font_size}px;
>             --aqualuxe-container-width: {$container_width}px;
>             --aqualuxe-product-columns: {$product_columns};
>         }
>         
>         body {
>             font-size: var(--aqualuxe-body-font-size);
>         }
>         
>         h1, h2, h3, h4, h5, h6 {
>             font-size: var(--aqualuxe-heading-font-size);
>         }
>         
>         .container {
>             max-width: var(--aqualuxe-container-width);
>         }
>         
>         .woocommerce ul.products li.product {
>             width: calc(100% / var(--aqualuxe-product-columns) - 2%);
>         }
>     ";
> 
>     if ( 'left-sidebar' === $shop_layout ) {
>         $custom_css .= "
>             .woocommerce-page #primary {
>                 float: right;
>             }
>             
>             .woocommerce-page #secondary {
>                 float: left;
>             }
>         ";
>     } elseif ( 'full-width' === $shop_layout ) {
>         $custom_css .= "
>             .woocommerce-page #primary {
>                 width: 100%;
>             }
>             
>             .woocommerce-page #secondary {
>                 display: none;
>             }
>         ";
>     }
> 
>     if ( $sticky_header ) {
>         $custom_css .= "
>             .site-header {
>                 position: sticky;
>                 top: 0;
>                 z-index: 9999;
>             }
>         ";
>     }
> 
>     $custom_css .= "
>         .footer-widgets .widget {
>             width: calc(100% / {$footer_widgets_columns} - 2%);
>         }
>     ";
> 
>     wp_add_inline_style( 'aqualuxe-style', $custom_css );
> }
> add_action( 'wp_enqueue_scripts', 'aqualuxe_customizer_styles' );
> 
> /**
>  * Output custom footer copyright
>  */
> function aqualuxe_footer_copyright() {
>     $footer_copyright = get_theme_mod( 'aqualuxe_footer_copyright', __( 'Copyright &copy; {year} {site_title}. All Rights Reserved.', 'aqualuxe' ) );
>     
>     $footer_copyright = str_replace( '{year}', date( 'Y' ), $footer_copyright );
>     $footer_copyright = str_replace( '{site_title}', get_bloginfo( 'name' ), $footer_copyright );
>     
>     echo wp_kses_post( $footer_copyright );
> }
> ```
> 
> ### inc/woocommerce.php
> 
> ```php
> <?php
> /**
>  * WooCommerce Compatibility File
>  *
>  * @link https://woocommerce.com/
>  *
>  * @package AquaLuxe
>  */
> 
> /**
>  * WooCommerce setup function.
>  *
>  * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
>  * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
>  * @link https://github.com/woocommerce/woocommerce/wiki/Declaring-WooCommerce-support-in-themes
>  *
>  * @return void
>  */
> function aqualuxe_woocommerce_setup() {
>     add_theme_support(
>         'woocommerce',
>         array(
>             'thumbnail_image_width'    => 400,
>             'gallery_thumbnail_image_width' => 100,
>             'single_image_width'       => 600,
>             'product_grid'          => array(
>                 'default_rows'    => 3,
>                 'min_rows'        => 1,
>                 'max_rows'        => 6,
>                 'default_columns' => 4,
>                 'min_columns'     => 1,
>                 'max_columns'     => 6,
>             ),
>         )
>     );
> 
>     add_theme_support( 'wc-product-gallery-zoom' );
>     add_theme_support( 'wc-product-gallery-lightbox' );
>     add_theme_support( 'wc-product-gallery-slider' );
> }
> add_action( 'after_setup_theme', 'aqualuxe_woocommerce_setup' );
> 
> /**
>  * WooCommerce specific scripts & stylesheets.
>  *
>  * @return void
>  */
> function aqualuxe_woocommerce_scripts() {
>     wp_enqueue_style( 'aqualuxe-woocommerce-style', get_template_directory_uri() . '/assets/css/woocommerce.css', array(), AQUALUXE_VERSION );
> 
>     $font_path   = WC()->plugin_path() . '/assets/fonts/';
>     $inline_font = '@font-face {
> 			font-family: "star";
> 			src: url("' . WC()->plugin_url() . '/assets/fonts/star.eot");
> 			src: url("' . WC()->plugin_url() . '/assets/fonts/star.eot?#iefix") format("embedded-opentype"),
> 				url("' . WC()->plugin_url() . '/assets/fonts/star.woff") format("woff"),
> 				url("' . WC()->plugin_url() . '/assets/fonts/star.ttf") format("truetype"),
> 				url("' . WC()->plugin_url() . '/assets/fonts/star.svg#star") format("svg");
> 			font-weight: normal;
> 			font-style: normal;
> 		}';
> 
>     wp_add_inline_style( 'aqualuxe-woocommerce-style', $inline_font );
> }
> add_action( 'wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts' );
> 
> /**
>  * Add 'woocommerce-active' class to the body tag.
>  *
>  * @param  array $classes CSS classes applied to the body tag.
>  * @return array $classes modified to include 'woocommerce-active' class.
>  */
> function aqualuxe_woocommerce_active_body_class( $classes ) {
>     $classes[] = 'woocommerce-active';
> 
>     return $classes;
> }
> add_filter( 'body_class', 'aqualuxe_woocommerce_active_body_class' );
> 
> /**
>  * Related Products Args.
>  *
>  * @param array $args related products args.
>  * @return array $args related products args.
>  */
> function aqualuxe_woocommerce_related_products_args( $args ) {
>     $defaults = array(
>         'posts_per_page' => 4,
>         'columns'        => 4,
>     );
> 
>     $args = wp_parse_args( $defaults, $args );
> 
>     return $args;
> }
> add_filter( 'woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args' );
> 
> /**
>  * Remove default WooCommerce wrapper.
>  */
> remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
> remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
> 
> if ( ! function_exists( 'aqualuxe_woocommerce_wrapper_before' ) ) {
>     /**
>      * Before Content.
>      *
>      * Wraps all WooCommerce content in wrappers which match the theme markup.
>      *
>      * @return void
>      */
>     function aqualuxe_woocommerce_wrapper_before() {
>         ?>
>         <div id="primary" class="content-area">
>             <main id="main" class="site-main" role="main">
>                 <div class="container">
>                     <div class="row">
>         <?php
>     }
> }
> add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before', 10 );
> 
> if ( ! function_exists( 'aqualuxe_woocommerce_wrapper_after' ) ) {
>     /**
>      * After Content.
>      *
>      * Closes the wrapping divs.
>      *
>      * @return void
>      */
>     function aqualuxe_woocommerce_wrapper_after() {
>         ?>
>                     </div><!-- .row -->
>                 </div><!-- .container -->
>             </main><!-- #main -->
>         </div><!-- #primary -->
>         <?php
>     }
> }
> add_action( 'woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after', 10 );
> 
> /**
>  * Sample implementation of the WooCommerce Mini Cart.
>  *
>  * You can add the WooCommerce Mini Cart to header.php like so:
>  *
>  * <?php if ( function_exists( 'aqualuxe_woocommerce_header_cart' ) ) { ?>
>  *     <div class="header-cart">
>  *         <?php aqualuxe_woocommerce_header_cart(); ?>
>  *     </div>
>  * <?php } ?>
>  *
>  */
> 
> if ( ! function_exists( 'aqualuxe_woocommerce_cart_link_fragment' ) ) {
>     /**
>      * Cart Fragments.
>      *
>      * Ensure cart contents update when products are added to the cart via AJAX.
>      *
>      * @param array $fragments Fragments to refresh via AJAX.
>      * @return array Fragments to refresh via AJAX.
>      */
>     function aqualuxe_woocommerce_cart_link_fragment( $fragments ) {
>         ob_start();
>         aqualuxe_woocommerce_cart_link();
>         $fragments['a.cart-contents'] = ob_get_clean();
> 
>         return $fragments;
>     }
> }
> add_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_link_fragment' );
> 
> if ( ! function_exists( 'aqualuxe_woocommerce_cart_link' ) ) {
>     /**
>      * Cart Link.
>      *
>      * Displayed a link to the cart including the number of items present and the cart total.
>      *
>      * @return void
>      */
>     function aqualuxe_woocommerce_cart_link() {
>         ?>
>         <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
>             <?php
>             $item_count_text = sprintf(
>                 /* translators: number of items in the mini cart. */
>                 _n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'aqualuxe' ),
>                 WC()->cart->get_cart_contents_count()
>             );
>             ?>
>             <span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_total() ); ?></span> <span class="count"><?php echo esc_html( $item_count_text ); ?></span>
>         </a>
>         <?php
>     }
> }
> 
> if ( ! function_exists( 'aqualuxe_woocommerce_header_cart' ) ) {
>     /**
>      * Display Header Cart.
>      *
>      * @return void
>      */
>     function aqualuxe_woocommerce_header_cart() {
>         if ( is_cart() ) {
>             $class = 'current-menu-item';
>         } else {
>             $class = '';
>         }
>         ?>
>         <ul id="site-header-cart" class="site-header-cart">
>             <li class="<?php echo esc_attr( $class ); ?>">
>                 <?php aqualuxe_woocommerce_cart_link(); ?>
>             </li>
>             <li>
>                 <?php
>                 $instance = array(
>                     'title' => '',
>                 );
> 
>                 if ( get_option( 'woocommerce_enable_ajax_add_to_cart' ) === 'yes' ) {
>                     // If AJAX is enabled, load the widget class only if it's not already loaded.
>                     if ( ! class_exists( 'WC_Widget_Cart' ) ) {
>                         include_once WC()->plugin_path() . '/includes/widgets/class-wc-widget-cart.php';
>                     }
>                     the_widget( 'WC_Widget_Cart', $instance );
>                 } else {
>                     // If AJAX is disabled, load the mini cart template.
>                     woocommerce_mini_cart();
>                 }
>                 ?>
>             </li>
>         </ul>
>         <?php
>     }
> }
> 
> /**
>  * Change number of products per row
>  */
> function aqualuxe_loop_columns() {
>     return 4; // 4 products per row
> }
> add_filter('loop_shop_columns', 'aqualuxe_loop_columns');
> 
> /**
>  * Change number of related products
>  */
> function aqualuxe_related_products_args( $args ) {
>     $args['posts_per_page'] = 4; // 4 related products
>     $args['columns'] = 4; // arranged in 4 columns
>     return $args;
> }
> add_filter( 'woocommerce_output_related_products_args', 'aqualuxe_related_products_args' );
> 
> /**
>  * Change number of upsells
>  */
> function aqualuxe_upsell_products_args( $args ) {
>     $args['posts_per_page'] = 4; // 4 upsell products
>     $args['columns'] = 4; // arranged in 4 columns
>     return $args;
> }
> add_filter( 'woocommerce_upsell_display_args', 'aqualuxe_upsell_products_args' );
> 
> /**
>  * Change number of cross-sells
>  */
> function aqualuxe_cross_sells_total( $limit ) {
>     $limit = 4; // 4 cross-sell products
>     return $limit;
> }
> add_filter( 'woocommerce_cross_sells_total', 'aqualuxe_cross_sells_total' );
> 
> /**
>  * Change cross-sells columns
>  */
> function aqualuxe_cross_sells_columns( $columns ) {
>     $columns = 4; // 4 columns
>     return $columns;
> }
> add_filter( 'woocommerce_cross_sells_columns', 'aqualuxe_cross_sells_columns' );
> 
> /**
>  * Remove default WooCommerce breadcrumb
>  */
> remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
> 
> /**
>  * Add custom WooCommerce breadcrumb
>  */
> function aqualuxe_woocommerce_breadcrumb() {
>     woocommerce_breadcrumb();
> }
> add_action( 'aqualuxe_before_content', 'aqualuxe_woocommerce_breadcrumb', 20 );
> 
> /**
>  * Change breadcrumb delimiter
>  */
> function aqualuxe_woocommerce_breadcrumb_defaults( $defaults ) {
>     $defaults['delimiter'] = ' <span class="breadcrumb-separator">/</span> ';
>     return $defaults;
> }
> add_filter( 'woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumb_defaults' );
> 
> /**
>  * Change sale flash text
>  */
> function aqualuxe_woocommerce_sale_flash( $html, $post, $product ) {
>     return '<span class="onsale">' . __( 'Sale!', 'aqualuxe' ) . '</span>';
> }
> add_filter( 'woocommerce_sale_flash', 'aqualuxe_woocommerce_sale_flash', 10, 3 );
> 
> /**
>  * Change product tabs
>  */
> function aqualuxe_woocommerce_product_tabs( $tabs ) {
>     // Change description tab title
>     $tabs['description']['title'] = __( 'Details', 'aqualuxe' );
>     
>     // Change reviews tab title
>     $tabs['reviews']['title'] = __( 'Reviews', 'aqualuxe' );
>     
>     // Reorder tabs
>     $tabs['description']['priority'] = 10;
>     $tabs['additional_information']['priority'] = 20;
>     $tabs['reviews']['priority'] = 30;
>     
>     return $tabs;
> }
> add_filter( 'woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs' );
> 
> /**
>  * Change add to cart text
>  */
> function aqualuxe_woocommerce_product_add_to_cart_text() {
>     return __( 'Add to Cart', 'aqualuxe' );
> }
> add_filter( 'woocommerce_product_add_to_cart_text', 'aqualuxe_woocommerce_product_add_to_cart_text' );
> 
> /**
>  * Change add to cart text for variable products
>  */
> function aqualuxe_woocommerce_variable_add_to_cart_text() {
>     return __( 'Select Options', 'aqualuxe' );
> }
> add_filter( 'woocommerce_variable_add_to_cart_text', 'aqualuxe_woocommerce_variable_add_to_cart_text' );
> 
> /**
>  * Change add to cart text for grouped products
>  */
> function aqualuxe_woocommerce_grouped_add_to_cart_text() {
>     return __( 'View Products', 'aqualuxe' );
> }
> add_filter( 'woocommerce_grouped_add_to_cart_text', 'aqualuxe_woocommerce_grouped_add_to_cart_text' );
> 
> /**
>  * Change add to cart text for external products
>  */
> function aqualuxe_woocommerce_external_add_to_cart_text() {
>     return __( 'Buy Product', 'aqualuxe' );
> }
> add_filter( 'woocommerce_external_add_to_cart_text', 'aqualuxe_woocommerce_external_add_to_cart_text' );
> 
> /**
>  * Change out of stock text
>  */
> function aqualuxe_woocommerce_out_of_stock_text() {
>     return __( 'Out of Stock', 'aqualuxe' );
> }
> add_filter( 'woocommerce_out_of_stock_message', 'aqualuxe_woocommerce_out_of_stock_text' );
> 
> /**
>  * Change back to shop text
>  */
> function aqualuxe_woocommerce_back_to_shop_text() {
>     return __( 'Back to Shop', 'aqualuxe' );
> }
> add_filter( 'woocommerce_back_to_shop_text', 'aqualuxe_woocommerce_back_to_shop_text' );
> 
> /**
>  * Change continue shopping text
>  */
> function aqualuxe_woocommerce_continue_shopping_text() {
>     return __( 'Continue Shopping', 'aqualuxe' );
> }
> add_filter( 'woocommerce_continue_shopping_text', 'aqualuxe_woocommerce_continue_shopping_text' );
> 
> /**
>  * Change update cart text
>  */
> function aqualuxe_woocommerce_update_cart_text() {
>     return __( 'Update Cart', 'aqualuxe' );
> }
> add_filter( 'woocommerce_update_cart_action_text', 'aqualuxe_woocommerce_update_cart_text' );
> 
> /**
>  * Change proceed to checkout text
>  */
> function aqualuxe_woocommerce_proceed_to_checkout_text() {
>     return __( 'Proceed to Checkout', 'aqualuxe' );
> }
> add_filter( 'woocommerce_proceed_to_checkout_text', 'aqualuxe_woocommerce_proceed_to_checkout_text' );
> 
> /**
>  * Change place order text
>  */
> function aqualuxe_woocommerce_order_button_text() {
>     return __( 'Place Order', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_button_text', 'aqualuxe_woocommerce_order_button_text' );
> 
> /**
>  * Change my account text
>  */
> function aqualuxe_woocommerce_my_account_text() {
>     return __( 'My Account', 'aqualuxe' );
> }
> add_filter( 'woocommerce_my_account_text', 'aqualuxe_woocommerce_my_account_text' );
> 
> /**
>  * Change login text
>  */
> function aqualuxe_woocommerce_login_text() {
>     return __( 'Login', 'aqualuxe' );
> }
> add_filter( 'woocommerce_login_text', 'aqualuxe_woocommerce_login_text' );
> 
> /**
>  * Change register text
>  */
> function aqualuxe_woocommerce_register_text() {
>     return __( 'Register', 'aqualuxe' );
> }
> add_filter( 'woocommerce_register_text', 'aqualuxe_woocommerce_register_text' );
> 
> /**
>  * Change logout text
>  */
> function aqualuxe_woocommerce_logout_text() {
>     return __( 'Logout', 'aqualuxe' );
> }
> add_filter( 'woocommerce_logout_text', 'aqualuxe_woocommerce_logout_text' );
> 
> /**
>  * Change view cart text
>  */
> function aqualuxe_woocommerce_view_cart_text() {
>     return __( 'View Cart', 'aqualuxe' );
> }
> add_filter( 'woocommerce_view_cart_text', 'aqualuxe_woocommerce_view_cart_text' );
> 
> /**
>  * Change checkout text
>  */
> function aqualuxe_woocommerce_checkout_text() {
>     return __( 'Checkout', 'aqualuxe' );
> }
> add_filter( 'woocommerce_checkout_text', 'aqualuxe_woocommerce_checkout_text' );
> 
> /**
>  * Change shop text
>  */
> function aqualuxe_woocommerce_shop_text() {
>     return __( 'Shop', 'aqualuxe' );
> }
> add_filter( 'woocommerce_shop_text', 'aqualuxe_woocommerce_shop_text' );
> 
> /**
>  * Change products text
>  */
> function aqualuxe_woocommerce_products_text() {
>     return __( 'Products', 'aqualuxe' );
> }
> add_filter( 'woocommerce_products_text', 'aqualuxe_woocommerce_products_text' );
> 
> /**
>  * Change categories text
>  */
> function aqualuxe_woocommerce_categories_text() {
>     return __( 'Categories', 'aqualuxe' );
> }
> add_filter( 'woocommerce_categories_text', 'aqualuxe_woocommerce_categories_text' );
> 
> /**
>  * Change tags text
>  */
> function aqualuxe_woocommerce_tags_text() {
>     return __( 'Tags', 'aqualuxe' );
> }
> add_filter( 'woocommerce_tags_text', 'aqualuxe_woocommerce_tags_text' );
> 
> /**
>  * Change attributes text
>  */
> function aqualuxe_woocommerce_attributes_text() {
>     return __( 'Attributes', 'aqualuxe' );
> }
> add_filter( 'woocommerce_attributes_text', 'aqualuxe_woocommerce_attributes_text' );
> 
> /**
>  * Change description text
>  */
> function aqualuxe_woocommerce_description_text() {
>     return __( 'Description', 'aqualuxe' );
> }
> add_filter( 'woocommerce_description_text', 'aqualuxe_woocommerce_description_text' );
> 
> /**
>  * Change additional information text
>  */
> function aqualuxe_woocommerce_additional_information_text() {
>     return __( 'Additional Information', 'aqualuxe' );
> }
> add_filter( 'woocommerce_additional_information_text', 'aqualuxe_woocommerce_additional_information_text' );
> 
> /**
>  * Change reviews text
>  */
> function aqualuxe_woocommerce_reviews_text() {
>     return __( 'Reviews', 'aqualuxe' );
> }
> add_filter( 'woocommerce_reviews_text', 'aqualuxe_woocommerce_reviews_text' );
> 
> /**
>  * Change related products text
>  */
> function aqualuxe_woocommerce_related_products_text() {
>     return __( 'Related Products', 'aqualuxe' );
> }
> add_filter( 'woocommerce_related_products_text', 'aqualuxe_woocommerce_related_products_text' );
> 
> /**
>  * Change upsells text
>  */
> function aqualuxe_woocommerce_upsells_text() {
>     return __( 'You may also like&hellip;', 'aqualuxe' );
> }
> add_filter( 'woocommerce_upsells_text', 'aqualuxe_woocommerce_upsells_text' );
> 
> /**
>  * Change cross-sells text
>  */
> function aqualuxe_woocommerce_cross_sells_text() {
>     return __( 'You may be interested in&hellip;', 'aqualuxe' );
> }
> add_filter( 'woocommerce_cross_sells_text', 'aqualuxe_woocommerce_cross_sells_text' );
> 
> /**
>  * Change cart totals text
>  */
> function aqualuxe_woocommerce_cart_totals_text() {
>     return __( 'Cart Totals', 'aqualuxe' );
> }
> add_filter( 'woocommerce_cart_totals_text', 'aqualuxe_woocommerce_cart_totals_text' );
> 
> /**
>  * Change billing details text
>  */
> function aqualuxe_woocommerce_billing_details_text() {
>     return __( 'Billing Details', 'aqualuxe' );
> }
> add_filter( 'woocommerce_billing_details_text', 'aqualuxe_woocommerce_billing_details_text' );
> 
> /**
>  * Change shipping details text
>  */
> function aqualuxe_woocommerce_shipping_details_text() {
>     return __( 'Shipping Details', 'aqualuxe' );
> }
> add_filter( 'woocommerce_shipping_details_text', 'aqualuxe_woocommerce_shipping_details_text' );
> 
> /**
>  * Change additional information text
>  */
> function aqualuxe_woocommerce_additional_information_text() {
>     return __( 'Additional Information', 'aqualuxe' );
> }
> add_filter( 'woocommerce_additional_information_text', 'aqualuxe_woocommerce_additional_information_text' );
> 
> /**
>  * Change order notes text
>  */
> function aqualuxe_woocommerce_order_notes_text() {
>     return __( 'Order Notes', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_notes_text', 'aqualuxe_woocommerce_order_notes_text' );
> 
> /**
>  * Change payment text
>  */
> function aqualuxe_woocommerce_payment_text() {
>     return __( 'Payment', 'aqualuxe' );
> }
> add_filter( 'woocommerce_payment_text', 'aqualuxe_woocommerce_payment_text' );
> 
> /**
>  * Change order review text
>  */
> function aqualuxe_woocommerce_order_review_text() {
>     return __( 'Your Order', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_review_text', 'aqualuxe_woocommerce_order_review_text' );
> 
> /**
>  * Change dashboard text
>  */
> function aqualuxe_woocommerce_dashboard_text() {
>     return __( 'Dashboard', 'aqualuxe' );
> }
> add_filter( 'woocommerce_dashboard_text', 'aqualuxe_woocommerce_dashboard_text' );
> 
> /**
>  * Change orders text
>  */
> function aqualuxe_woocommerce_orders_text() {
>     return __( 'Orders', 'aqualuxe' );
> }
> add_filter( 'woocommerce_orders_text', 'aqualuxe_woocommerce_orders_text' );
> 
> /**
>  * Change downloads text
>  */
> function aqualuxe_woocommerce_downloads_text() {
>     return __( 'Downloads', 'aqualuxe' );
> }
> add_filter( 'woocommerce_downloads_text', 'aqualuxe_woocommerce_downloads_text' );
> 
> /**
>  * Change addresses text
>  */
> function aqualuxe_woocommerce_addresses_text() {
>     return __( 'Addresses', 'aqualuxe' );
> }
> add_filter( 'woocommerce_addresses_text', 'aqualuxe_woocommerce_addresses_text' );
> 
> /**
>  * Change account details text
>  */
> function aqualuxe_woocommerce_account_details_text() {
>     return __( 'Account Details', 'aqualuxe' );
> }
> add_filter( 'woocommerce_account_details_text', 'aqualuxe_woocommerce_account_details_text' );
> 
> /**
>  * Change logout text
>  */
> function aqualuxe_woocommerce_logout_text() {
>     return __( 'Logout', 'aqualuxe' );
> }
> add_filter( 'woocommerce_logout_text', 'aqualuxe_woocommerce_logout_text' );
> 
> /**
>  * Change my orders text
>  */
> function aqualuxe_woocommerce_my_orders_text() {
>     return __( 'My Orders', 'aqualuxe' );
> }
> add_filter( 'woocommerce_my_orders_text', 'aqualuxe_woocommerce_my_orders_text' );
> 
> /**
>  * Change my downloads text
>  */
> function aqualuxe_woocommerce_my_downloads_text() {
>     return __( 'My Downloads', 'aqualuxe' );
> }
> add_filter( 'woocommerce_my_downloads_text', 'aqualuxe_woocommerce_my_downloads_text' );
> 
> /**
>  * Change my addresses text
>  */
> function aqualuxe_woocommerce_my_addresses_text() {
>     return __( 'My Addresses', 'aqualuxe' );
> }
> add_filter( 'woocommerce_my_addresses_text', 'aqualuxe_woocommerce_my_addresses_text' );
> 
> /**
>  * Change my account details text
>  */
> function aqualuxe_woocommerce_my_account_details_text() {
>     return __( 'My Account Details', 'aqualuxe' );
> }
> add_filter( 'woocommerce_my_account_details_text', 'aqualuxe_woocommerce_my_account_details_text' );
> 
> /**
>  * Change billing address text
>  */
> function aqualuxe_woocommerce_billing_address_text() {
>     return __( 'Billing Address', 'aqualuxe' );
> }
> add_filter( 'woocommerce_billing_address_text', 'aqualuxe_woocommerce_billing_address_text' );
> 
> /**
>  * Change shipping address text
>  */
> function aqualuxe_woocommerce_shipping_address_text() {
>     return __( 'Shipping Address', 'aqualuxe' );
> }
> add_filter( 'woocommerce_shipping_address_text', 'aqualuxe_woocommerce_shipping_address_text' );
> 
> /**
>  * Change edit address text
>  */
> function aqualuxe_woocommerce_edit_address_text() {
>     return __( 'Edit Address', 'aqualuxe' );
> }
> add_filter( 'woocommerce_edit_address_text', 'aqualuxe_woocommerce_edit_address_text' );
> 
> /**
>  * Change add address text
>  */
> function aqualuxe_woocommerce_add_address_text() {
>     return __( 'Add Address', 'aqualuxe' );
> }
> add_filter( 'woocommerce_add_address_text', 'aqualuxe_woocommerce_add_address_text' );
> 
> /**
>  * Change order details text
>  */
> function aqualuxe_woocommerce_order_details_text() {
>     return __( 'Order Details', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_details_text', 'aqualuxe_woocommerce_order_details_text' );
> 
> /**
>  * Change order again text
>  */
> function aqualuxe_woocommerce_order_again_text() {
>     return __( 'Order Again', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_again_text', 'aqualuxe_woocommerce_order_again_text' );
> 
> /**
>  * Change view order text
>  */
> function aqualuxe_woocommerce_view_order_text() {
>     return __( 'View Order', 'aqualuxe' );
> }
> add_filter( 'woocommerce_view_order_text', 'aqualuxe_woocommerce_view_order_text' );
> 
> /**
>  * Change invoice text
>  */
> function aqualuxe_woocommerce_invoice_text() {
>     return __( 'Invoice', 'aqualuxe' );
> }
> add_filter( 'woocommerce_invoice_text', 'aqualuxe_woocommerce_invoice_text' );
> 
> /**
>  * Change cancel order text
>  */
> function aqualuxe_woocommerce_cancel_order_text() {
>     return __( 'Cancel Order', 'aqualuxe' );
> }
> add_filter( 'woocommerce_cancel_order_text', 'aqualuxe_woocommerce_cancel_order_text' );
> 
> /**
>  * Change track order text
>  */
> function aqualuxe_woocommerce_track_order_text() {
>     return __( 'Track Order', 'aqualuxe' );
> }
> add_filter( 'woocommerce_track_order_text', 'aqualuxe_woocommerce_track_order_text' );
> 
> /**
>  * Change order status text
>  */
> function aqualuxe_woocommerce_order_status_text() {
>     return __( 'Order Status', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_status_text', 'aqualuxe_woocommerce_order_status_text' );
> 
> /**
>  * Change order date text
>  */
> function aqualuxe_woocommerce_order_date_text() {
>     return __( 'Order Date', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_date_text', 'aqualuxe_woocommerce_order_date_text' );
> 
> /**
>  * Change order number text
>  */
> function aqualuxe_woocommerce_order_number_text() {
>     return __( 'Order Number', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_number_text', 'aqualuxe_woocommerce_order_number_text' );
> 
> /**
>  * Change order total text
>  */
> function aqualuxe_woocommerce_order_total_text() {
>     return __( 'Order Total', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_total_text', 'aqualuxe_woocommerce_order_total_text' );
> 
> /**
>  * Change order subtotal text
>  */
> function aqualuxe_woocommerce_order_subtotal_text() {
>     return __( 'Subtotal', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_subtotal_text', 'aqualuxe_woocommerce_order_subtotal_text' );
> 
> /**
>  * Change order discount text
>  */
> function aqualuxe_woocommerce_order_discount_text() {
>     return __( 'Discount', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_discount_text', 'aqualuxe_woocommerce_order_discount_text' );
> 
> /**
>  * Change order shipping text
>  */
> function aqualuxe_woocommerce_order_shipping_text() {
>     return __( 'Shipping', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_shipping_text', 'aqualuxe_woocommerce_order_shipping_text' );
> 
> /**
>  * Change order tax text
>  */
> function aqualuxe_woocommerce_order_tax_text() {
>     return __( 'Tax', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_tax_text', 'aqualuxe_woocommerce_order_tax_text' );
> 
> /**
>  * Change order payment method text
>  */
> function aqualuxe_woocommerce_order_payment_method_text() {
>     return __( 'Payment Method', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_payment_method_text', 'aqualuxe_woocommerce_order_payment_method_text' );
> 
> /**
>  * Change order shipping method text
>  */
> function aqualuxe_woocommerce_order_shipping_method_text() {
>     return __( 'Shipping Method', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_shipping_method_text', 'aqualuxe_woocommerce_order_shipping_method_text' );
> 
> /**
>  * Change order billing address text
>  */
> function aqualuxe_woocommerce_order_billing_address_text() {
>     return __( 'Billing Address', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_billing_address_text', 'aqualuxe_woocommerce_order_billing_address_text' );
> 
> /**
>  * Change order shipping address text
>  */
> function aqualuxe_woocommerce_order_shipping_address_text() {
>     return __( 'Shipping Address', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_shipping_address_text', 'aqualuxe_woocommerce_order_shipping_address_text' );
> 
> /**
>  * Change order notes text
>  */
> function aqualuxe_woocommerce_order_notes_text() {
>     return __( 'Order Notes', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_notes_text', 'aqualuxe_woocommerce_order_notes_text' );
> 
> /**
>  * Change customer note text
>  */
> function aqualuxe_woocommerce_customer_note_text() {
>     return __( 'Customer Note', 'aqualuxe' );
> }
> add_filter( 'woocommerce_customer_note_text', 'aqualuxe_woocommerce_customer_note_text' );
> 
> /**
>  * Change order status text
>  */
> function aqualuxe_woocommerce_order_status_text() {
>     return __( 'Order Status', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_status_text', 'aqualuxe_woocommerce_order_status_text' );
> 
> /**
>  * Change order date text
>  */
> function aqualuxe_woocommerce_order_date_text() {
>     return __( 'Order Date', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_date_text', 'aqualuxe_woocommerce_order_date_text' );
> 
> /**
>  * Change order number text
>  */
> function aqualuxe_woocommerce_order_number_text() {
>     return __( 'Order Number', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_number_text', 'aqualuxe_woocommerce_order_number_text' );
> 
> /**
>  * Change order total text
>  */
> function aqualuxe_woocommerce_order_total_text() {
>     return __( 'Order Total', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_total_text', 'aqualuxe_woocommerce_order_total_text' );
> 
> /**
>  * Change order subtotal text
>  */
> function aqualuxe_woocommerce_order_subtotal_text() {
>     return __( 'Subtotal', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_subtotal_text', 'aqualuxe_woocommerce_order_subtotal_text' );
> 
> /**
>  * Change order discount text
>  */
> function aqualuxe_woocommerce_order_discount_text() {
>     return __( 'Discount', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_discount_text', 'aqualuxe_woocommerce_order_discount_text' );
> 
> /**
>  * Change order shipping text
>  */
> function aqualuxe_woocommerce_order_shipping_text() {
>     return __( 'Shipping', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_shipping_text', 'aqualuxe_woocommerce_order_shipping_text' );
> 
> /**
>  * Change order tax text
>  */
> function aqualuxe_woocommerce_order_tax_text() {
>     return __( 'Tax', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_tax_text', 'aqualuxe_woocommerce_order_tax_text' );
> 
> /**
>  * Change order payment method text
>  */
> function aqualuxe_woocommerce_order_payment_method_text() {
>     return __( 'Payment Method', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_payment_method_text', 'aqualuxe_woocommerce_order_payment_method_text' );
> 
> /**
>  * Change order shipping method text
>  */
> function aqualuxe_woocommerce_order_shipping_method_text() {
>     return __( 'Shipping Method', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_shipping_method_text', 'aqualuxe_woocommerce_order_shipping_method_text' );
> 
> /**
>  * Change order billing address text
>  */
> function aqualuxe_woocommerce_order_billing_address_text() {
>     return __( 'Billing Address', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_billing_address_text', 'aqualuxe_woocommerce_order_billing_address_text' );
> 
> /**
>  * Change order shipping address text
>  */
> function aqualuxe_woocommerce_order_shipping_address_text() {
>     return __( 'Shipping Address', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_shipping_address_text', 'aqualuxe_woocommerce_order_shipping_address_text' );
> 
> /**
>  * Change order notes text
>  */
> function aqualuxe_woocommerce_order_notes_text() {
>     return __( 'Order Notes', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_notes_text', 'aqualuxe_woocommerce_order_notes_text' );
> 
> /**
>  * Change customer note text
>  */
> function aqualuxe_woocommerce_customer_note_text() {
>     return __( 'Customer Note', 'aqualuxe' );
> }
> add_filter( 'woocommerce_customer_note_text', 'aqualuxe_woocommerce_customer_note_text' );
> 
> /**
>  * Change order status text
>  */
> function aqualuxe_woocommerce_order_status_text() {
>     return __( 'Order Status', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_status_text', 'aqualuxe_woocommerce_order_status_text' );
> 
> /**
>  * Change order date text
>  */
> function aqualuxe_woocommerce_order_date_text() {
>     return __( 'Order Date', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_date_text', 'aqualuxe_woocommerce_order_date_text' );
> 
> /**
>  * Change order number text
>  */
> function aqualuxe_woocommerce_order_number_text() {
>     return __( 'Order Number', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_number_text', 'aqualuxe_woocommerce_order_number_text' );
> 
> /**
>  * Change order total text
>  */
> function aqualuxe_woocommerce_order_total_text() {
>     return __( 'Order Total', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_total_text', 'aqualuxe_woocommerce_order_total_text' );
> 
> /**
>  * Change order subtotal text
>  */
> function aqualuxe_woocommerce_order_subtotal_text() {
>     return __( 'Subtotal', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_subtotal_text', 'aqualuxe_woocommerce_order_subtotal_text' );
> 
> /**
>  * Change order discount text
>  */
> function aqualuxe_woocommerce_order_discount_text() {
>     return __( 'Discount', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_discount_text', 'aqualuxe_woocommerce_order_discount_text' );
> 
> /**
>  * Change order shipping text
>  */
> function aqualuxe_woocommerce_order_shipping_text() {
>     return __( 'Shipping', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_shipping_text', 'aqualuxe_woocommerce_order_shipping_text' );
> 
> /**
>  * Change order tax text
>  */
> function aqualuxe_woocommerce_order_tax_text() {
>     return __( 'Tax', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_tax_text', 'aqualuxe_woocommerce_order_tax_text' );
> 
> /**
>  * Change order payment method text
>  */
> function aqualuxe_woocommerce_order_payment_method_text() {
>     return __( 'Payment Method', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_payment_method_text', 'aqualuxe_woocommerce_order_payment_method_text' );
> 
> /**
>  * Change order shipping method text
>  */
> function aqualuxe_woocommerce_order_shipping_method_text() {
>     return __( 'Shipping Method', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_shipping_method_text', 'aqualuxe_woocommerce_order_shipping_method_text' );
> 
> /**
>  * Change order billing address text
>  */
> function aqualuxe_woocommerce_order_billing_address_text() {
>     return __( 'Billing Address', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_billing_address_text', 'aqualuxe_woocommerce_order_billing_address_text' );
> 
> /**
>  * Change order shipping address text
>  */
> function aqualuxe_woocommerce_order_shipping_address_text() {
>     return __( 'Shipping Address', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_shipping_address_text', 'aqualuxe_woocommerce_order_shipping_address_text' );
> 
> /**
>  * Change order notes text
>  */
> function aqualuxe_woocommerce_order_notes_text() {
>     return __( 'Order Notes', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_notes_text', 'aqualuxe_woocommerce_order_notes_text' );
> 
> /**
>  * Change customer note text
>  */
> function aqualuxe_woocommerce_customer_note_text() {
>     return __( 'Customer Note', 'aqualuxe' );
> }
> add_filter( 'woocommerce_customer_note_text', 'aqualuxe_woocommerce_customer_note_text' );
> 
> /**
>  * Change order status text
>  */
> function aqualuxe_woocommerce_order_status_text() {
>     return __( 'Order Status', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_status_text', 'aqualuxe_woocommerce_order_status_text' );
> 
> /**
>  * Change order date text
>  */
> function aqualuxe_woocommerce_order_date_text() {
>     return __( 'Order Date', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_date_text', 'aqualuxe_woocommerce_order_date_text' );
> 
> /**
>  * Change order number text
>  */
> function aqualuxe_woocommerce_order_number_text() {
>     return __( 'Order Number', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_number_text', 'aqualuxe_woocommerce_order_number_text' );
> 
> /**
>  * Change order total text
>  */
> function aqualuxe_woocommerce_order_total_text() {
>     return __( 'Order Total', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_total_text', 'aqualuxe_woocommerce_order_total_text' );
> 
> /**
>  * Change order subtotal text
>  */
> function aqualuxe_woocommerce_order_subtotal_text() {
>     return __( 'Subtotal', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_subtotal_text', 'aqualuxe_woocommerce_order_subtotal_text' );
> 
> /**
>  * Change order discount text
>  */
> function aqualuxe_woocommerce_order_discount_text() {
>     return __( 'Discount', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_discount_text', 'aqualuxe_woocommerce_order_discount_text' );
> 
> /**
>  * Change order shipping text
>  */
> function aqualuxe_woocommerce_order_shipping_text() {
>     return __( 'Shipping', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_shipping_text', 'aqualuxe_woocommerce_order_shipping_text' );
> 
> /**
>  * Change order tax text
>  */
> function aqualuxe_woocommerce_order_tax_text() {
>     return __( 'Tax', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_tax_text', 'aqualuxe_woocommerce_order_tax_text' );
> 
> /**
>  * Change order payment method text
>  */
> function aqualuxe_woocommerce_order_payment_method_text() {
>     return __( 'Payment Method', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_payment_method_text', 'aqualuxe_woocommerce_order_payment_method_text' );
> 
> /**
>  * Change order shipping method text
>  */
> function aqualuxe_woocommerce_order_shipping_method_text() {
>     return __( 'Shipping Method', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_shipping_method_text', 'aqualuxe_woocommerce_order_shipping_method_text' );
> 
> /**
>  * Change order billing address text
>  */
> function aqualuxe_woocommerce_order_billing_address_text() {
>     return __( 'Billing Address', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_billing_address_text', 'aqualuxe_woocommerce_order_billing_address_text' );
> 
> /**
>  * Change order shipping address text
>  */
> function aqualuxe_woocommerce_order_shipping_address_text() {
>     return __( 'Shipping Address', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_shipping_address_text', 'aqualuxe_woocommerce_order_shipping_address_text' );
> 
> /**
>  * Change order notes text
>  */
> function aqualuxe_woocommerce_order_notes_text() {
>     return __( 'Order Notes', 'aqualuxe' );
> }
> add_filter( 'woocommerce_order_notes_text', 'aqualuxe_woocommerce_order_notes_text' );
> 
> /**
>  * Change customer note text
>  */
> function aqualuxe_woocommerce_customer_note_text() {
>     return __( 'Customer Note', 'aqualuxe' );
> }
> add_filter( 'woocommerce_customer_note_text', 'aqualuxe_woocommerce_customer_note_text' );
> ```
> 
> ### inc/hooks/hooks.php
> 
> ```php
> <?php
> /**
>  * AquaLuxe Theme Hooks
>  *
>  * @package AquaLuxe
>  */
> 
> /**
>  * Header Actions
>  */
> add_action( 'aqualuxe_header', 'aqualuxe_header_container', 5 );
> add_action( 'aqualuxe_header', 'aqualuxe_header_top', 10 );
> add_action( 'aqualuxe_header', 'aqualuxe_site_branding', 15 );
> add_action( 'aqualuxe_header', 'aqualuxe_site_navigation', 20 );
> add_action( 'aqualuxe_header', 'aqualuxe_header_bottom', 25 );
> add_action( 'aqualuxe_header', 'aqualuxe_header_container_close', 30 );
> 
> /**
>  * Content Actions
>  */
> add_action( 'aqualuxe_content', 'aqualuxe_content_container', 5 );
> add_action( 'aqualuxe_content', 'aqualuxe_content_top', 10 );
> add_action( 'aqualuxe_content', 'aqualuxe_content', 15 );
> add_action( 'aqualuxe_content', 'aqualuxe_content_bottom', 20 );
> add_action( 'aqualuxe_content', 'aqualuxe_content_container_close', 25 );
> 
> /**
>  * Footer Actions
>  */
> add_action( 'aqualuxe_footer', 'aqualuxe_footer_container', 5 );
> add_action( 'aqualuxe_footer', 'aqualuxe_footer_top', 10 );
> add_action( 'aqualuxe_footer', 'aqualuxe_footer_widgets', 15 );
> add_action( 'aqualuxe_footer', 'aqualuxe_footer_bottom', 20 );
> add_action( 'aqualuxe_footer', 'aqualuxe_footer_container_close', 25 );
> 
> /**
>  * Header Functions
>  */
> if ( ! function_exists( 'aqualuxe_header_container' ) ) {
>     /**
>      * Header Container
>      */
>     function aqualuxe_header_container() {
>         echo '<header id="masthead" class="site-header">';
>     }
> }
> 
> if ( ! function_exists( 'aqualuxe_header_top' ) ) {
>     /**
>      * Header Top
>      */
>     function aqualuxe_header_top() {
>         echo '<div class="header-top">';
>         echo '<div class="container">';
>         echo '<div class="row">';
>         echo '<div class="col-md-6">';
>         echo '<div class="header-contact">';
>         echo '<ul>';
>         echo '<li><i class="fas fa-phone"></i> ' . esc_html( get_theme_mod( 'aqualuxe_phone_number', '+1 (555) 123-4567' ) ) . '</li>';
>         echo '<li><i class="fas fa-envelope"></i> ' . esc_html( get_theme_mod( 'aqualuxe_email_address', 'info@aqualuxe.com' ) ) . '</li>';
>         echo '</ul>';
>         echo '</div>';
>         echo '</div>';
>         echo '<div class="col-md-6">';
>         echo '<div class="header-social">';
>         echo '<ul>';
>         echo '<li><a href="' . esc_url( get_theme_mod( 'aqualuxe_facebook_url', '#' ) ) . '"><i class="fab fa-facebook-f"></i></a></li>';
>         echo '<li><a href="' . esc_url( get_theme_mod( 'aqualuxe_twitter_url', '#' ) ) . '"><i class="fab fa-twitter"></i></a></li>';
>         echo '<li><a href="' . esc_url( get_theme_mod( 'aqualuxe_instagram_url', '#' ) ) . '"><i class="fab fa-instagram"></i></a></li>';
>         echo '<li><a href="' . esc_url( get_theme_mod( 'aqualuxe_youtube_url', '#' ) ) . '"><i class="fab fa-youtube"></i></a></li>';
>         echo '</ul>';
>         echo '</div>';
>         echo '</div>';
>         echo '</div>';
>         echo '</div>';
>         echo '</div>';
>     }
> }
> 
> if ( ! function_exists( 'aqualuxe_site_branding' ) ) {
>     /**
>      * Site Branding
>      */
>     function aqualuxe_site_branding() {
>         echo '<div class="site-branding">';
>         if ( has_custom_logo() ) {
>             the_custom_logo();
>         } else {
>             if ( is_front_page() && is_home() ) :
>                 ?>
>                 <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
>             <?php
>             else :
>                 ?>
>                 <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
>             <?php
>             endif;
> 
>             $aqualuxe_description = get_bloginfo( 'description', 'display' );
>             if ( $aqualuxe_description || is_customize_preview() ) :
>                 ?>
>                 <p class="site-description"><?php echo $aqualuxe_description; /* WPCS: xss ok. */ ?></p>
>             <?php
>             endif;
>         }
>         echo '</div>';
>     }
> }
> 
> if ( ! function_exists( 'aqualuxe_site_navigation' ) ) {
>     /**
>      * Site Navigation
>      */
>     function aqualuxe_site_navigation() {
>         echo '<nav id="site-navigation" class="main-navigation">';
>         echo '<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">' . esc_html__( 'Primary Menu', 'aqualuxe' ) . '</button>';
>         wp_nav_menu( array(
>             'theme_location' => 'primary',
>             'menu_id'        => 'primary-menu',
>         ) );
>         echo '</nav>';
>     }
> }
> 
> if ( ! function_exists( 'aqualuxe_header_bottom' ) ) {
>     /**
>      * Header Bottom
>      */
>     function aqualuxe_header_bottom() {
>         echo '<div class="header-bottom">';
>         echo '<div class="container">';
>         echo '<div class="row">';
>         echo '<div class="col-md-12">';
>         echo '<div class="header-search">';
>         get_product_search_form();
>         echo '</div>';
>         echo '</div>';
>         echo '</div>';
>         echo '</div>';
>         echo '</div>';
>     }
> }
> 
> if ( ! function_exists( 'aqualuxe_header_container_close' ) ) {
>     /**
>      * Header Container Close
>      */
>     function aqualuxe_header_container_close() {
>         echo '</header>';
>     }
> }
> 
> /**
>  * Content Functions
>  */
> if ( ! function_exists( 'aqualuxe_content_container' ) ) {
>     /**
>      * Content Container
>      */
>     function aqualuxe_content_container() {
>         echo '<div id="content" class="site-content">';
>     }
> }
> 
> if ( ! function_exists( 'aqualuxe_content_top' ) ) {
>     /**
>      * Content Top
>      */
>     function aqualuxe_content_top() {
>         echo '<div class="content-top">';
>         echo '<div class="container">';
>         echo '<div class="row">';
>         echo '<div class="col-md-12">';
>         echo '<div class="breadcrumb">';
>         aqualuxe_breadcrumb();
>         echo '</div>';
>         echo '</div>';
>         echo '</div>';
>         echo '</div>';
>         echo '</div>';
>     }
> }
> 
> if ( ! function_exists( 'aqualuxe_content' ) ) {
>     /**
>      * Content
>      */
>     function aqualuxe_content() {
>         if ( is_home() || is_archive() || is_search() ) {
>             echo '<div class="container">';
>             echo '<div class="row">';
>             echo '<div class="col-md-8">';
>             if ( have_posts() ) {
>                 while ( have_posts() ) {
>                     the_post();
>                     get_template_part( 'template-parts/content', get_post_format() );
>                 }
>                 the_posts_pagination();
>             } else {
>                 get_template_part( 'template-parts/content', 'none' );
>             }
>             echo '</div>';
>             echo '<div class="col-md-4">';
>             get_sidebar();
>             echo '</div>';
>             echo '</div>';
>             echo '</div>';
>         } elseif ( is_page() ) {
>             echo '<div class="container">';
>             echo '<div class="row">';
>             echo '<div class="col-md-12">';
>             while ( have_posts() ) {
>                 the_post();
>                 get_template_part( 'template-parts/content', 'page' );
>             }
>             echo '</div>';
>             echo '</div>';
>             echo '</div>';
>         } elseif ( is_single() ) {
>             echo '<div class="container">';
>             echo '<div class="row">';
>             echo '<div class="col-md-8">';
>             while ( have_posts() ) {
>                 the_post();
>                 get_template_part( 'template-parts/content', 'single' );
>             }
>             echo '</div>';
>             echo '<div class="col-md-4">';
>             get_sidebar();
>             echo '</div>';
>             echo '</div>';
>             echo '</div>';
>         } elseif ( is_404() ) {
>             echo '<div class="container">';
>             echo '<div class="row">';
>             echo '<div class="col-md-12">';
>             get_template_part( 'template-parts/content', '404' );
>             echo '</div>';
>             echo '</div>';
>             echo '</div>';
>         }
>     }
> }
> 
> if ( ! function_exists( 'aqualuxe_content_bottom' ) ) {
>     /**
>      * Content Bottom
>      */
>     function aqualuxe_content_bottom() {
>         echo '<div class="content-bottom">';
>         echo '<div class="container">';
>         echo '<div class="row">';
>         echo '<div class="col-md-12">';
>         echo '<div class="newsletter">';
>         echo '<h3>' . esc_html__( 'Subscribe to our Newsletter', 'aqualuxe' ) . '</h3>';
>         echo '<p>' . esc_html__( 'Get the latest updates on new products and upcoming sales', 'aqualuxe' ) . '</p>';
>         echo '<form action="' . esc_url( home_url( '/' ) ) . '" method="post">';
>         echo '<div class="form-group">';
>         echo '<input type="email" name="email" class="form-control" placeholder="' . esc_attr__( 'Your Email Address', 'aqualuxe' ) . '" required>';
>         echo '</div>';
>         echo '<div class="form-group">';
>         echo '<button type="submit" class="btn btn-primary">' . esc_html__( 'Subscribe', 'aqualuxe' ) . '</button>';
>         echo '</div>';
>         echo '</form>';
>         echo '</div>';
>         echo '</div>';
>         echo '</div>';
>         echo '</div>';
>         echo '</div>';
>     }
> }
> 
> if ( ! function_exists( 'aqualuxe_content_container_close' ) ) {
>     /**
>      * Content Container Close
>      */
>     function aqualuxe_content_container_close() {
>         echo '</div>';
>     }
> }
> 
> /**
>  * Footer Functions
>  */
> if ( ! function_exists( 'aqualuxe_footer_container' ) ) {
>     /**
>      * Footer Container
>      */
>     function aqualuxe_footer_container() {
>         echo '<footer id="colophon" class="site-footer">';
>     }
> }
> 
> if ( ! function_exists( 'aqualuxe_footer_top' ) ) {
>     /**
>      * Footer Top
>      */
>     function aqualuxe_footer_top() {
>         echo '<div class="footer-top">';
>         echo '<div class="container">';
>         echo '<div class="row">';
>         echo '<div class="col-md-12">';
>         echo '<div class="footer-logo">';
>         if ( has_custom_logo() ) {
>             the_custom_logo();
>         } else {
>             echo '<h2 class="site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . get_bloginfo( 'name' ) . '</a></h2>';
>         }
>         echo '<p class="site-description">' . get_bloginfo( 'description' ) . '</p>';
>         echo '</div>';
>         echo '</div>';
>         echo '</div>';
>         echo '</div>';
>         echo '</div>';
>     }
> }
> 
> if ( ! function_exists( 'aqualuxe_footer_widgets' ) ) {
>     /**
>      * Footer Widgets
>      */
>     function aqualuxe_footer_widgets() {
>         echo '<div class="footer-widgets">';
>         echo '<div class="container">';
>         echo '<div class="row">';
>         echo '<div class="col-md-3">';
>         if ( is_active_sidebar( 'footer-1' ) ) {
>             dynamic_sidebar( 'footer-1' );
>         }
>         echo '</div>';
>         echo '<div class="col-md-3">';
>         if ( is_active_sidebar( 'footer-2' ) ) {
>             dynamic_sidebar( 'footer-2' );
>         }
>         echo '</div>';
>         echo '<div class="col-md-3">';
>         if ( is_active_sidebar( 'footer-3' ) ) {
>             dynamic_sidebar( 'footer-3' );
>         }
>         echo '</div>';
>         echo '<div class="col-md-3">';
>         if ( is_active_sidebar( 'footer-4' ) ) {
>             dynamic_sidebar( 'footer-4' );
>         }
>         echo '</div>';
>         echo '</div>';
>         echo '</div>';
>         echo '</div>';
>     }
> }
> 
> if ( ! function_exists( 'aqualuxe_footer_bottom' ) ) {
>     /**
>      * Footer Bottom
>      */
>     function aqualuxe_footer_bottom() {
>         echo '<div class="footer-bottom">';
>         echo '<div class="container">';
>         echo '<div class="row">';
>         echo '<div class="col-md-6">';
>         echo '<div class="copyright">';
>         echo '<p>' . wp_kses_post( get_theme_mod( 'aqualuxe_footer_copyright', __( 'Copyright &copy; {year} {site_title}. All Rights Reserved.', 'aqualuxe' ) ) ) . '</p>';
>         echo '</div>';
>         echo '</div>';
>         echo '<div class="col-md-6">';
>         echo '<div class="footer-menu">';
>         wp_nav_menu( array(
>             'theme_location' => 'footer',
>             'menu_id'        => 'footer-menu',
>         ) );
>         echo '</div>';
>         echo '</div>';
>         echo '</div>';
>         echo '</div>';
>         echo '</div>';
>     }
> }
> 
> if ( ! function_exists( 'aqualuxe_footer_container_close' ) ) {
>     /**
>      * Footer Container Close
>      */
>     function aqualuxe_footer_container_close() {
>         echo '</footer>';
>     }
> }
> 
> /**
>  * Breadcrumb
>  */
> if ( ! function_exists( 'aqualuxe_breadcrumb' ) ) {
>     /**
>      * Breadcrumb
>      */
>     function aqualuxe_breadcrumb() {
>         if ( function_exists( 'woocommerce_breadcrumb' ) ) {
>             woocommerce_breadcrumb();
>         } else {
>             echo '<ul class="breadcrumb">';
>             echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'aqualuxe' ) . '</a></li>';
>             if ( is_category() || is_single() ) {
>                 echo '<li>' . get_the_category_list( ', ' ) . '</li>';
>             } elseif ( is_page() ) {
>                 echo '<li>' . get_the_title() . '</li>';
>             } elseif ( is_search() ) {
>                 echo '<li>' . esc_html__( 'Search Results for ', 'aqualuxe' ) . get_search_query() . '</li>';
>             }
>             echo '</ul>';
>         }
>     }
> }
> ```
> 
> ### inc/helpers.php
> 
> ```php
> <?php
> /**
>  * AquaLuxe Helper Functions
>  *
>  * @package AquaLuxe
>  */
> 
> /**
>  * Get theme version
>  *
>  * @return string
>  */
> function aqualuxe_get_theme_version() {
>     $theme = wp_get_theme();
>     return $theme->get( 'Version' );
> }
> 
> /**
>  * Get theme asset URL
>  *
>  * @param string $path
>  * @return string
>  */
> function aqualuxe_get_asset_url( $path ) {
>     return get_template_directory_uri() . '/assets/' . $path;
> }
> 
> /**
>  * Get theme asset path
>  *
>  * @param string $path
>  * @return string
>  */
> function aqualuxe_get_asset_path( $path ) {
>     return get_template_directory() . '/assets/' . $path;
> }
> 
> /**
>  * Get theme option
>  *
>  * @param string $option
>  * @param mixed $default
>  * @return mixed
>  */
> function aqualuxe_get_option( $option, $default = null ) {
>     return get_theme_mod( $option, $default );
> }
> 
> /**
>  * Get theme image
>  *
>  * @param string $image
>  * @return string
>  */
> function aqualuxe_get_image( $image ) {
>     return aqualuxe_get_asset_url( 'images/' . $image );
> }
> 
> /**
>  * Get theme icon
>  *
>  * @param string $icon
>  * @return string
>  */
> function aqualuxe_get_icon( $icon ) {
>     return aqualuxe_get_asset_url( 'icons/' . $icon );
> }
> 
> /**
>  * Get theme font
>  *
>  * @param string $font
>  * @return string
>  */
> function aqualuxe_get_font( $font ) {
>     return aqualuxe_get_asset_url( 'fonts/' . $font );
> }
> 
> /**
>  * Get theme style
>  *
>  * @param string $style
>  * @return string
>  */
> function aqualuxe_get_style( $style ) {
>     return aqualuxe_get_asset_url( 'css/' . $style );
> }
> 
> /**
>  * Get theme script
>  *
>  * @param string $script
>  * @return string
>  */
> function aqualuxe_get_script( $script ) {
>     return aqualuxe_get_asset_url( 'js/' . $script );
> }
> 
> /**
>  * Get theme template part
>  *
>  * @param string $slug
>  * @param string $name
>  * @return void
>  */
> function aqualuxe_get_template_part( $slug, $name = null ) {
>     get_template_part( 'template-parts/' . $slug, $name );
> }
> 
> /**
>  * Get theme sidebar
>  *
>  * @param string $name
>  * @return void
>  */
> function aqualuxe_get_sidebar( $name = null ) {
>     get_sidebar( $name );
> }
> 
> /**
>  * Get theme footer
>  *
>  * @param string $name
>  * @return void
>  */
> function aqualuxe_get_footer( $name = null ) {
>     get_footer( $name );
> }
> 
> /**
>  * Get theme header
>  *
>  * @param string $name
>  * @return void
>  */
> function aqualuxe_get_header( $name = null ) {
>     get_header( $name );
> }
> 
> /**
>  * Is WooCommerce active
>  *
>  * @return bool
>  */
> function aqualuxe_is_woocommerce_active() {
>     return class_exists( 'WooCommerce' );
> }
> 
> /**
>  * Is WooCommerce page
>  *
>  * @return bool
>  */
> function aqualuxe_is_woocommerce_page() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return false;
>     }
> 
>     return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
> }
> 
> /**
>  * Is shop page
>  *
>  * @return bool
>  */
> function aqualuxe_is_shop_page() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return false;
>     }
> 
>     return is_shop();
> }
> 
> /**
>  * Is product category page
>  *
>  * @return bool
>  */
> function aqualuxe_is_product_category_page() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return false;
>     }
> 
>     return is_product_category();
> }
> 
> /**
>  * Is product tag page
>  *
>  * @return bool
>  */
> function aqualuxe_is_product_tag_page() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return false;
>     }
> 
>     return is_product_tag();
> }
> 
> /**
>  * Is product page
>  *
>  * @return bool
>  */
> function aqualuxe_is_product_page() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return false;
>     }
> 
>     return is_product();
> }
> 
> /**
>  * Is cart page
>  *
>  * @return bool
>  */
> function aqualuxe_is_cart_page() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return false;
>     }
> 
>     return is_cart();
> }
> 
> /**
>  * Is checkout page
>  *
>  * @return bool
>  */
> function aqualuxe_is_checkout_page() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return false;
>     }
> 
>     return is_checkout();
> }
> 
> /**
>  * Is account page
>  *
>  * @return bool
>  */
> function aqualuxe_is_account_page() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return false;
>     }
> 
>     return is_account_page();
> }
> 
> /**
>  * Is order received page
>  *
>  * @return bool
>  */
> function aqualuxe_is_order_received_page() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return false;
>     }
> 
>     return is_order_received_page();
> }
> 
> /**
>  * Is add payment method page
>  *
>  * @return bool
>  */
> function aqualuxe_is_add_payment_method_page() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return false;
>     }
> 
>     return is_add_payment_method_page();
> }
> 
> /**
>  * Is lost password page
>  *
>  * @return bool
>  */
> function aqualuxe_is_lost_password_page() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return false;
>     }
> 
>     return is_lost_password_page();
> }
> 
> /**
>  * Is edit account page
>  *
>  * @return bool
>  */
> function aqualuxe_is_edit_account_page() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return false;
>     }
> 
>     return is_edit_account_page();
> }
> 
> /**
>  * Is view order page
>  *
>  * @return bool
>  */
> function aqualuxe_is_view_order_page() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return false;
>     }
> 
>     return is_view_order_page();
> }
> 
> /**
>  * Is downloads page
>  *
>  * @return bool
>  */
> function aqualuxe_is_downloads_page() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return false;
>     }
> 
>     return is_downloads_page();
> }
> 
> /**
>  * Is addresses page
>  *
>  * @return bool
>  */
> function aqualuxe_is_addresses_page() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return false;
>     }
> 
>     return is_addresses_page();
> }
> 
> /**
>  * Is payment methods page
>  *
>  * @return bool
>  */
> function aqualuxe_is_payment_methods_page() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return false;
>     }
> 
>     return is_payment_methods_page();
> }
> 
> /**
>  * Is endpoint URL
>  *
>  * @param string $endpoint
>  * @return bool
>  */
> function aqualuxe_is_endpoint_url( $endpoint ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return false;
>     }
> 
>     global $wp;
> 
>     if ( ! isset( $wp->query_vars['lost-password'] ) ) {
>         $wp->query_vars['lost-password'] = 'lost-password';
>     }
> 
>     if ( ! isset( $wp->query_vars['edit-account'] ) ) {
>         $wp->query_vars['edit-account'] = 'edit-account';
>     }
> 
>     if ( ! isset( $wp->query_vars['add-payment-method'] ) ) {
>         $wp->query_vars['add-payment-method'] = 'add-payment-method';
>     }
> 
>     if ( ! isset( $wp->query_vars['view-order'] ) ) {
>         $wp->query_vars['view-order'] = 'view-order';
>     }
> 
>     if ( ! isset( $wp->query_vars['downloads'] ) ) {
>         $wp->query_vars['downloads'] = 'downloads';
>     }
> 
>     if ( ! isset( $wp->query_vars['orders'] ) ) {
>         $wp->query_vars['orders'] = 'orders';
>     }
> 
>     if ( ! isset( $wp->query_vars['payment-methods'] ) ) {
>         $wp->query_vars['payment-methods'] = 'payment-methods';
>     }
> 
>     if ( ! isset( $wp->query_vars['edit-address'] ) ) {
>         $wp->query_vars['edit-address'] = 'edit-address';
>     }
> 
>     if ( ! isset( $wp->query_vars['order-received'] ) ) {
>         $wp->query_vars['order-received'] = 'order-received';
>     }
> 
>     switch ( $endpoint ) {
>         case 'lost-password':
>             return isset( $wp->query_vars['lost-password'] );
>         case 'edit-account':
>             return isset( $wp->query_vars['edit-account'] );
>         case 'add-payment-method':
>             return isset( $wp->query_vars['add-payment-method'] );
>         case 'view-order':
>             return isset( $wp->query_vars['view-order'] );
>         case 'downloads':
>             return isset( $wp->query_vars['downloads'] );
>         case 'orders':
>             return isset( $wp->query_vars['orders'] );
>         case 'payment-methods':
>             return isset( $wp->query_vars['payment-methods'] );
>         case 'edit-address':
>             return isset( $wp->query_vars['edit-address'] );
>         case 'order-received':
>             return isset( $wp->query_vars['order-received'] );
>         default:
>             return false;
>     }
> }
> 
> /**
>  * Get post excerpt
>  *
>  * @param int $post_id
>  * @param int $length
>  * @return string
>  */
> function aqualuxe_get_post_excerpt( $post_id = 0, $length = 55 ) {
>     if ( ! $post_id ) {
>         $post_id = get_the_ID();
>     }
> 
>     $post = get_post( $post_id );
>     $excerpt = $post->post_excerpt;
> 
>     if ( ! $excerpt ) {
>         $excerpt = $post->post_content;
>     }
> 
>     $excerpt = strip_shortcodes( $excerpt );
>     $excerpt = strip_tags( $excerpt );
>     $excerpt = substr( $excerpt, 0, $length );
>     $excerpt = substr( $excerpt, 0, strrpos( $excerpt, ' ' ) );
> 
>     return $excerpt . '...';
> }
> 
> /**
>  * Get post thumbnail
>  *
>  * @param int $post_id
>  * @param string $size
>  * @return string
>  */
> function aqualuxe_get_post_thumbnail( $post_id = 0, $size = 'post-thumbnail' ) {
>     if ( ! $post_id ) {
>         $post_id = get_the_ID();
>     }
> 
>     if ( has_post_thumbnail( $post_id ) ) {
>         return get_the_post_thumbnail( $post_id, $size );
>     }
> 
>     return '<img src="' . aqualuxe_get_image( 'placeholder.jpg' ) . '" alt="' . get_the_title( $post_id ) . '">';
> }
> 
> /**
>  * Get post permalink
>  *
>  * @param int $post_id
>  * @return string
>  */
> function aqualuxe_get_post_permalink( $post_id = 0 ) {
>     if ( ! $post_id ) {
>         $post_id = get_the_ID();
>     }
> 
>     return get_permalink( $post_id );
> }
> 
> /**
>  * Get post title
>  *
>  * @param int $post_id
>  * @return string
>  */
> function aqualuxe_get_post_title( $post_id = 0 ) {
>     if ( ! $post_id ) {
>         $post_id = get_the_ID();
>     }
> 
>     return get_the_title( $post_id );
> }
> 
> /**
>  * Get post content
>  *
>  * @param int $post_id
>  * @return string
>  */
> function aqualuxe_get_post_content( $post_id = 0 ) {
>     if ( ! $post_id ) {
>         $post_id = get_the_ID();
>     }
> 
>     $post = get_post( $post_id );
>     $content = $post->post_content;
> 
>     return apply_filters( 'the_content', $content );
> }
> 
> /**
>  * Get post date
>  *
>  * @param int $post_id
>  * @param string $format
>  * @return string
>  */
> function aqualuxe_get_post_date( $post_id = 0, $format = '' ) {
>     if ( ! $post_id ) {
>         $post_id = get_the_ID();
>     }
> 
>     if ( ! $format ) {
>         $format = get_option( 'date_format' );
>     }
> 
>     return get_the_date( $format, $post_id );
> }
> 
> /**
>  * Get post author
>  *
>  * @param int $post_id
>  * @return string
>  */
> function aqualuxe_get_post_author( $post_id = 0 ) {
>     if ( ! $post_id ) {
>         $post_id = get_the_ID();
>     }
> 
>     return get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) );
> }
> 
> /**
>  * Get post author link
>  *
>  * @param int $post_id
>  * @return string
>  */
> function aqualuxe_get_post_author_link( $post_id = 0 ) {
>     if ( ! $post_id ) {
>         $post_id = get_the_ID();
>     }
> 
>     return get_author_posts_url( get_post_field( 'post_author', $post_id ) );
> }
> 
> /**
>  * Get post categories
>  *
>  * @param int $post_id
>  * @return string
>  */
> function aqualuxe_get_post_categories( $post_id = 0 ) {
>     if ( ! $post_id ) {
>         $post_id = get_the_ID();
>     }
> 
>     $categories = get_the_category( $post_id );
>     $output = '';
> 
>     if ( $categories ) {
>         $output .= '<div class="post-categories">';
>         $output .= '<span>' . esc_html__( 'Categories:', 'aqualuxe' ) . '</span>';
>         $output .= '<ul>';
> 
>         foreach ( $categories as $category ) {
>             $output .= '<li><a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a></li>';
>         }
> 
>         $output .= '</ul>';
>         $output .= '</div>';
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get post tags
>  *
>  * @param int $post_id
>  * @return string
>  */
> function aqualuxe_get_post_tags( $post_id = 0 ) {
>     if ( ! $post_id ) {
>         $post_id = get_the_ID();
>     }
> 
>     $tags = get_the_tags( $post_id );
>     $output = '';
> 
>     if ( $tags ) {
>         $output .= '<div class="post-tags">';
>         $output .= '<span>' . esc_html__( 'Tags:', 'aqualuxe' ) . '</span>';
>         $output .= '<ul>';
> 
>         foreach ( $tags as $tag ) {
>             $output .= '<li><a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . esc_html( $tag->name ) . '</a></li>';
>         }
> 
>         $output .= '</ul>';
>         $output .= '</div>';
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get post comments count
>  *
>  * @param int $post_id
>  * @return string
>  */
> function aqualuxe_get_post_comments_count( $post_id = 0 ) {
>     if ( ! $post_id ) {
>         $post_id = get_the_ID();
>     }
> 
>     $comments_count = get_comments_number( $post_id );
>     $output = '';
> 
>     if ( $comments_count > 0 ) {
>         $output .= '<div class="post-comments-count">';
>         $output .= '<span>' . esc_html__( 'Comments:', 'aqualuxe' ) . '</span>';
>         $output .= '<a href="' . esc_url( get_comments_link( $post_id ) ) . '">' . sprintf( _n( '%s Comment', '%s Comments', $comments_count, 'aqualuxe' ), number_format_i18n( $comments_count ) ) . '</a>';
>         $output .= '</div>';
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get post read more
>  *
>  * @param int $post_id
>  * @return string
>  */
> function aqualuxe_get_post_read_more( $post_id = 0 ) {
>     if ( ! $post_id ) {
>         $post_id = get_the_ID();
>     }
> 
>     $output = '<div class="post-read-more">';
>     $output .= '<a href="' . esc_url( get_permalink( $post_id ) ) . '" class="btn btn-primary">' . esc_html__( 'Read More', 'aqualuxe' ) . '</a>';
>     $output .= '</div>';
> 
>     return $output;
> }
> 
> /**
>  * Get post meta
>  *
>  * @param int $post_id
>  * @return string
>  */
> function aqualuxe_get_post_meta( $post_id = 0 ) {
>     if ( ! $post_id ) {
>         $post_id = get_the_ID();
>     }
> 
>     $output = '<div class="post-meta">';
>     $output .= '<span class="post-author">' . esc_html__( 'By ', 'aqualuxe' ) . '<a href="' . esc_url( get_author_posts_url( get_post_field( 'post_author', $post_id ) ) ) . '">' . esc_html( get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) ) ) . '</a></span>';
>     $output .= '<span class="post-date">' . esc_html__( 'On ', 'aqualuxe' ) . '<a href="' . esc_url( get_permalink( $post_id ) ) . '">' . esc_html( get_the_date( '', $post_id ) ) . '</a></span>';
>     $output .= '</div>';
> 
>     return $output;
> }
> 
> /**
>  * Get post featured image
>  *
>  * @param int $post_id
>  * @param string $size
>  * @return string
>  */
> function aqualuxe_get_post_featured_image( $post_id = 0, $size = 'post-thumbnail' ) {
>     if ( ! $post_id ) {
>         $post_id = get_the_ID();
>     }
> 
>     $output = '<div class="post-featured-image">';
>     $output .= aqualuxe_get_post_thumbnail( $post_id, $size );
>     $output .= '</div>';
> 
>     return $output;
> }
> 
> /**
>  * Get post content wrapper
>  *
>  * @param int $post_id
>  * @return string
>  */
> function aqualuxe_get_post_content_wrapper( $post_id = 0 ) {
>     if ( ! $post_id ) {
>         $post_id = get_the_ID();
>     }
> 
>     $output = '<div class="post-content-wrapper">';
>     $output .= aqualuxe_get_post_meta( $post_id );
>     $output .= '<h2 class="post-title"><a href="' . esc_url( get_permalink( $post_id ) ) . '">' . esc_html( get_the_title( $post_id ) ) . '</a></h2>';
>     $output .= '<div class="post-excerpt">' . aqualuxe_get_post_excerpt( $post_id ) . '</div>';
>     $output .= aqualuxe_get_post_read_more( $post_id );
>     $output .= '</div>';
> 
>     return $output;
> }
> 
> /**
>  * Get post wrapper
>  *
>  * @param int $post_id
>  * @param string $class
>  * @return string
>  */
> function aqualuxe_get_post_wrapper( $post_id = 0, $class = '' ) {
>     if ( ! $post_id ) {
>         $post_id = get_the_ID();
>     }
> 
>     $output = '<article id="post-' . esc_attr( $post_id ) . '" class="' . esc_attr( implode( ' ', get_post_class( $class, $post_id ) ) ) . '">';
>     $output .= aqualuxe_get_post_featured_image( $post_id );
>     $output .= aqualuxe_get_post_content_wrapper( $post_id );
>     $output .= '</article>';
> 
>     return $output;
> }
> 
> /**
>  * Get posts loop
>  *
>  * @param array $args
>  * @return string
>  */
> function aqualuxe_get_posts_loop( $args = array() ) {
>     $defaults = array(
>         'post_type'      => 'post',
>         'posts_per_page' => get_option( 'posts_per_page' ),
>         'orderby'        => 'date',
>         'order'          => 'DESC',
>         'post_status'    => 'publish',
>     );
> 
>     $args = wp_parse_args( $args, $defaults );
> 
>     $posts_query = new WP_Query( $args );
>     $output = '';
> 
>     if ( $posts_query->have_posts() ) {
>         $output .= '<div class="posts-loop">';
> 
>         while ( $posts_query->have_posts() ) {
>             $posts_query->the_post();
>             $output .= aqualuxe_get_post_wrapper();
>         }
> 
>         $output .= '</div>';
> 
>         wp_reset_postdata();
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get pagination
>  *
>  * @param array $args
>  * @return string
>  */
> function aqualuxe_get_pagination( $args = array() ) {
>     $defaults = array(
>         'mid_size'  => 2,
>         'prev_text' => __( '&laquo; Previous', 'aqualuxe' ),
>         'next_text' => __( 'Next &raquo;', 'aqualuxe' ),
>     );
> 
>     $args = wp_parse_args( $args, $defaults );
> 
>     $output = '<div class="pagination">';
>     $output .= paginate_links( $args );
>     $output .= '</div>';
> 
>     return $output;
> }
> 
> /**
>  * Get sidebar
>  *
>  * @param string $name
>  * @return string
>  */
> function aqualuxe_get_sidebar( $name = '' ) {
>     $output = '';
> 
>     if ( is_active_sidebar( $name ) ) {
>         $output .= '<div class="sidebar ' . esc_attr( $name ) . '-sidebar">';
>         $output .= '<div class="sidebar-inner">';
>         dynamic_sidebar( $name );
>         $output .= '</div>';
>         $output .= '</div>';
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get footer widgets
>  *
>  * @return string
>  */
> function aqualuxe_get_footer_widgets() {
>     $output = '';
> 
>     if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) {
>         $output .= '<div class="footer-widgets">';
>         $output .= '<div class="container">';
>         $output .= '<div class="row">';
>         $output .= '<div class="col-md-3">';
>         $output .= aqualuxe_get_sidebar( 'footer-1' );
>         $output .= '</div>';
>         $output .= '<div class="col-md-3">';
>         $output .= aqualuxe_get_sidebar( 'footer-2' );
>         $output .= '</div>';
>         $output .= '<div class="col-md-3">';
>         $output .= aqualuxe_get_sidebar( 'footer-3' );
>         $output .= '</div>';
>         $output .= '<div class="col-md-3">';
>         $output .= aqualuxe_get_sidebar( 'footer-4' );
>         $output .= '</div>';
>         $output .= '</div>';
>         $output .= '</div>';
>         $output .= '</div>';
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get footer copyright
>  *
>  * @return string
>  */
> function aqualuxe_get_footer_copyright() {
>     $output = '<div class="footer-copyright">';
>     $output .= '<div class="container">';
>     $output .= '<div class="row">';
>     $output .= '<div class="col-md-6">';
>     $output .= '<div class="copyright-text">';
>     $output .= '<p>' . wp_kses_post( get_theme_mod( 'aqualuxe_footer_copyright', __( 'Copyright &copy; {year} {site_title}. All Rights Reserved.', 'aqualuxe' ) ) ) . '</p>';
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '<div class="col-md-6">';
>     $output .= '<div class="footer-menu">';
>     wp_nav_menu( array(
>         'theme_location' => 'footer',
>         'menu_id'        => 'footer-menu',
>         'menu_class'     => 'footer-menu-list',
>         'container'      => false,
>     ) );
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '</div>';
> 
>     return $output;
> }
> 
> /**
>  * Get footer
>  *
>  * @return string
>  */
> function aqualuxe_get_footer() {
>     $output = '<footer id="colophon" class="site-footer">';
>     $output .= aqualuxe_get_footer_widgets();
>     $output .= aqualuxe_get_footer_copyright();
>     $output .= '</footer>';
> 
>     return $output;
> }
> 
> /**
>  * Get header
>  *
>  * @return string
>  */
> function aqualuxe_get_header() {
>     $output = '<header id="masthead" class="site-header">';
>     $output .= '<div class="header-top">';
>     $output .= '<div class="container">';
>     $output .= '<div class="row">';
>     $output .= '<div class="col-md-6">';
>     $output .= '<div class="header-contact">';
>     $output .= '<ul>';
>     $output .= '<li><i class="fas fa-phone"></i> ' . esc_html( get_theme_mod( 'aqualuxe_phone_number', '+1 (555) 123-4567' ) ) . '</li>';
>     $output .= '<li><i class="fas fa-envelope"></i> ' . esc_html( get_theme_mod( 'aqualuxe_email_address', 'info@aqualuxe.com' ) ) . '</li>';
>     $output .= '</ul>';
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '<div class="col-md-6">';
>     $output .= '<div class="header-social">';
>     $output .= '<ul>';
>     $output .= '<li><a href="' . esc_url( get_theme_mod( 'aqualuxe_facebook_url', '#' ) ) . '"><i class="fab fa-facebook-f"></i></a></li>';
>     $output .= '<li><a href="' . esc_url( get_theme_mod( 'aqualuxe_twitter_url', '#' ) ) . '"><i class="fab fa-twitter"></i></a></li>';
>     $output .= '<li><a href="' . esc_url( get_theme_mod( 'aqualuxe_instagram_url', '#' ) ) . '"><i class="fab fa-instagram"></i></a></li>';
>     $output .= '<li><a href="' . esc_url( get_theme_mod( 'aqualuxe_youtube_url', '#' ) ) . '"><i class="fab fa-youtube"></i></a></li>';
>     $output .= '</ul>';
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '<div class="header-main">';
>     $output .= '<div class="container">';
>     $output .= '<div class="row">';
>     $output .= '<div class="col-md-3">';
>     $output .= '<div class="site-branding">';
>     if ( has_custom_logo() ) {
>         $output .= '<div class="site-logo">';
>         the_custom_logo();
>         $output .= '</div>';
>     } else {
>         $output .= '<div class="site-title">';
>         if ( is_front_page() && is_home() ) :
>             $output .= '<h1><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . bloginfo( 'name' ) . '</a></h1>';
>         else :
>             $output .= '<p><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . bloginfo( 'name' ) . '</a></p>';
>         endif;
>         $output .= '</div>';
>         $output .= '<div class="site-description">' . bloginfo( 'description' ) . '</div>';
>     }
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '<div class="col-md-6">';
>     $output .= '<div class="site-navigation">';
>     $output .= '<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">' . esc_html__( 'Primary Menu', 'aqualuxe' ) . '</button>';
>     ob_start();
>     wp_nav_menu( array(
>         'theme_location' => 'primary',
>         'menu_id'        => 'primary-menu',
>         'menu_class'     => 'primary-menu-list',
>         'container'      => false,
>     ) );
>     $output .= ob_get_clean();
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '<div class="col-md-3">';
>     $output .= '<div class="header-search">';
>     if ( aqualuxe_is_woocommerce_active() ) {
>         ob_start();
>         get_product_search_form();
>         $output .= ob_get_clean();
>     } else {
>         $output .= get_search_form( false );
>     }
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '</header>';
> 
>     return $output;
> }
> 
> /**
>  * Get page title
>  *
>  * @return string
>  */
> function aqualuxe_get_page_title() {
>     $output = '';
> 
>     if ( is_home() ) {
>         $output = get_the_title( get_option( 'page_for_posts', true ) );
>     } elseif ( is_archive() ) {
>         $output = get_the_archive_title();
>     } elseif ( is_search() ) {
>         $output = sprintf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span>' . get_search_query() . '</span>' );
>     } elseif ( is_404() ) {
>         $output = esc_html__( 'Page Not Found', 'aqualuxe' );
>     } else {
>         $output = get_the_title();
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get page header
>  *
>  * @return string
>  */
> function aqualuxe_get_page_header() {
>     $output = '<div class="page-header">';
>     $output .= '<div class="container">';
>     $output .= '<div class="row">';
>     $output .= '<div class="col-md-12">';
>     $output .= '<h1 class="page-title">' . aqualuxe_get_page_title() . '</h1>';
>     $output .= '<div class="breadcrumb">';
>     aqualuxe_breadcrumb();
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '</div>';
> 
>     return $output;
> }
> 
> /**
>  * Get content wrapper
>  *
>  * @return string
>  */
> function aqualuxe_get_content_wrapper() {
>     $output = '<div id="content" class="site-content">';
>     $output .= aqualuxe_get_page_header();
>     $output .= '<div class="container">';
>     $output .= '<div class="row">';
> 
>     if ( is_active_sidebar( 'sidebar-1' ) && ! aqualuxe_is_woocommerce_page() ) {
>         $output .= '<div class="col-md-8 content-area">';
>     } elseif ( aqualuxe_is_woocommerce_page() && is_active_sidebar( 'shop-sidebar' ) ) {
>         $output .= '<div class="col-md-9 content-area">';
>     } else {
>         $output .= '<div class="col-md-12 content-area">';
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get content wrapper close
>  *
>  * @return string
>  */
> function aqualuxe_get_content_wrapper_close() {
>     $output = '</div>';
> 
>     if ( is_active_sidebar( 'sidebar-1' ) && ! aqualuxe_is_woocommerce_page() ) {
>         $output .= '<div class="col-md-4 widget-area">';
>         get_sidebar();
>         $output .= '</div>';
>     } elseif ( aqualuxe_is_woocommerce_page() && is_active_sidebar( 'shop-sidebar' ) ) {
>         $output .= '<div class="col-md-3 widget-area">';
>         get_sidebar( 'shop' );
>         $output .= '</div>';
>     }
> 
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '</div>';
> 
>     return $output;
> }
> 
> /**
>  * Get newsletter
>  *
>  * @return string
>  */
> function aqualuxe_get_newsletter() {
>     $output = '<div class="newsletter">';
>     $output .= '<div class="container">';
>     $output .= '<div class="row">';
>     $output .= '<div class="col-md-12">';
>     $output .= '<div class="newsletter-inner">';
>     $output .= '<h3>' . esc_html__( 'Subscribe to our Newsletter', 'aqualuxe' ) . '</h3>';
>     $output .= '<p>' . esc_html__( 'Get the latest updates on new products and upcoming sales', 'aqualuxe' ) . '</p>';
>     $output .= '<form action="' . esc_url( home_url( '/' ) ) . '" method="post">';
>     $output .= '<div class="form-group">';
>     $output .= '<input type="email" name="email" class="form-control" placeholder="' . esc_attr__( 'Your Email Address', 'aqualuxe' ) . '" required>';
>     $output .= '</div>';
>     $output .= '<div class="form-group">';
>     $output .= '<button type="submit" class="btn btn-primary">' . esc_html__( 'Subscribe', 'aqualuxe' ) . '</button>';
>     $output .= '</div>';
>     $output .= '</form>';
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '</div>';
>     $output .= '</div>';
> 
>     return $output;
> }
> 
> /**
>  * Get social links
>  *
>  * @return string
>  */
> function aqualuxe_get_social_links() {
>     $output = '<div class="social-links">';
>     $output .= '<ul>';
>     $output .= '<li><a href="' . esc_url( get_theme_mod( 'aqualuxe_facebook_url', '#' ) ) . '"><i class="fab fa-facebook-f"></i></a></li>';
>     $output .= '<li><a href="' . esc_url( get_theme_mod( 'aqualuxe_twitter_url', '#' ) ) . '"><i class="fab fa-twitter"></i></a></li>';
>     $output .= '<li><a href="' . esc_url( get_theme_mod( 'aqualuxe_instagram_url', '#' ) ) . '"><i class="fab fa-instagram"></i></a></li>';
>     $output .= '<li><a href="' . esc_url( get_theme_mod( 'aqualuxe_youtube_url', '#' ) ) . '"><i class="fab fa-youtube"></i></a></li>';
>     $output .= '</ul>';
>     $output .= '</div>';
> 
>     return $output;
> }
> 
> /**
>  * Get contact info
>  *
>  * @return string
>  */
> function aqualuxe_get_contact_info() {
>     $output = '<div class="contact-info">';
>     $output .= '<ul>';
>     $output .= '<li><i class="fas fa-phone"></i> ' . esc_html( get_theme_mod( 'aqualuxe_phone_number', '+1 (555) 123-4567' ) ) . '</li>';
>     $output .= '<li><i class="fas fa-envelope"></i> ' . esc_html( get_theme_mod( 'aqualuxe_email_address', 'info@aqualuxe.com' ) ) . '</li>';
>     $output .= '<li><i class="fas fa-map-marker-alt"></i> ' . esc_html( get_theme_mod( 'aqualuxe_address', '123 Main Street, New York, NY 10001' ) ) . '</li>';
>     $output .= '</ul>';
>     $output .= '</div>';
> 
>     return $output;
> }
> 
> /**
>  * Get business hours
>  *
>  * @return string
>  */
> function aqualuxe_get_business_hours() {
>     $output = '<div class="business-hours">';
>     $output .= '<ul>';
>     $output .= '<li>' . esc_html__( 'Monday - Friday: ', 'aqualuxe' ) . esc_html( get_theme_mod( 'aqualuxe_mon_fri_hours', '9:00 AM - 6:00 PM' ) ) . '</li>';
>     $output .= '<li>' . esc_html__( 'Saturday: ', 'aqualuxe' ) . esc_html( get_theme_mod( 'aqualuxe_sat_hours', '10:00 AM - 4:00 PM' ) ) . '</li>';
>     $output .= '<li>' . esc_html__( 'Sunday: ', 'aqualuxe' ) . esc_html( get_theme_mod( 'aqualuxe_sun_hours', 'Closed' ) ) . '</li>';
>     $output .= '</ul>';
>     $output .= '</div>';
> 
>     return $output;
> }
> 
> /**
>  * Get product categories
>  *
>  * @return string
>  */
> function aqualuxe_get_product_categories() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     $args = array(
>         'taxonomy'   => 'product_cat',
>         'orderby'    => 'name',
>         'show_count' => true,
>         'pad_counts' => true,
>         'hierarchical' => 1,
>         'title_li'   => '',
>         'hide_empty' => false,
>     );
> 
>     $output = '<div class="product-categories">';
>     $output .= '<h3>' . esc_html__( 'Product Categories', 'aqualuxe' ) . '</h3>';
>     $output .= '<ul>';
>     $output .= wp_list_categories( $args );
>     $output .= '</ul>';
>     $output .= '</div>';
> 
>     return $output;
> }
> 
> /**
>  * Get product tags
>  *
>  * @return string
>  */
> function aqualuxe_get_product_tags() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     $args = array(
>         'taxonomy'   => 'product_tag',
>         'orderby'    => 'name',
>         'show_count' => true,
>         'pad_counts' => true,
>         'hierarchical' => 0,
>         'title_li'   => '',
>         'hide_empty' => false,
>     );
> 
>     $output = '<div class="product-tags">';
>     $output .= '<h3>' . esc_html__( 'Product Tags', 'aqualuxe' ) . '</h3>';
>     $output .= '<ul>';
>     $output .= wp_list_categories( $args );
>     $output .= '</ul>';
>     $output .= '</div>';
> 
>     return $output;
> }
> 
> /**
>  * Get price filter
>  *
>  * @return string
>  */
> function aqualuxe_get_price_filter() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     $output = '<div class="price-filter">';
>     $output .= '<h3>' . esc_html__( 'Filter by Price', 'aqualuxe' ) . '</h3>';
>     $output .= '<form class="price-filter-form" action="' . esc_url( home_url( '/' ) ) . '" method="get">';
>     $output .= '<div class="price-range">';
>     $output .= '<input type="text" name="min_price" class="min-price" placeholder="' . esc_attr__( 'Min Price', 'aqualuxe' ) . '">';
>     $output .= '<span>' . esc_html__( 'to', 'aqualuxe' ) . '</span>';
>     $output .= '<input type="text" name="max_price" class="max-price" placeholder="' . esc_attr__( 'Max Price', 'aqualuxe' ) . '">';
>     $output .= '</div>';
>     $output .= '<div class="form-group">';
>     $output .= '<button type="submit" class="btn btn-primary">' . esc_html__( 'Filter', 'aqualuxe' ) . '</button>';
>     $output .= '</div>';
>     $output .= '</form>';
>     $output .= '</div>';
> 
>     return $output;
> }
> 
> /**
>  * Get rating filter
>  *
>  * @return string
>  */
> function aqualuxe_get_rating_filter() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     $output = '<div class="rating-filter">';
>     $output .= '<h3>' . esc_html__( 'Filter by Rating', 'aqualuxe' ) . '</h3>';
>     $output .= '<form class="rating-filter-form" action="' . esc_url( home_url( '/' ) ) . '" method="get">';
>     $output .= '<div class="form-check">';
>     $output .= '<input class="form-check-input" type="checkbox" name="rating" value="5" id="rating-5">';
>     $output .= '<label class="form-check-label" for="rating-5">';
>     $output .= '<span class="stars">';
>     $output .= '<i class="fas fa-star"></i>';
>     $output .= '<i class="fas fa-star"></i>';
>     $output .= '<i class="fas fa-star"></i>';
>     $output .= '<i class="fas fa-star"></i>';
>     $output .= '<i class="fas fa-star"></i>';
>     $output .= '</span>';
>     $output .= '</label>';
>     $output .= '</div>';
>     $output .= '<div class="form-check">';
>     $output .= '<input class="form-check-input" type="checkbox" name="rating" value="4" id="rating-4">';
>     $output .= '<label class="form-check-label" for="rating-4">';
>     $output .= '<span class="stars">';
>     $output .= '<i class="fas fa-star"></i>';
>     $output .= '<i class="fas fa-star"></i>';
>     $output .= '<i class="fas fa-star"></i>';
>     $output .= '<i class="fas fa-star"></i>';
>     $output .= '<i class="far fa-star"></i>';
>     $output .= '</span>';
>     $output .= '<span class="text"> & ' . esc_html__( 'up', 'aqualuxe' ) . '</span>';
>     $output .= '</label>';
>     $output .= '</div>';
>     $output .= '<div class="form-check">';
>     $output .= '<input class="form-check-input" type="checkbox" name="rating" value="3" id="rating-3">';
>     $output .= '<label class="form-check-label" for="rating-3">';
>     $output .= '<span class="stars">';
>     $output .= '<i class="fas fa-star"></i>';
>     $output .= '<i class="fas fa-star"></i>';
>     $output .= '<i class="fas fa-star"></i>';
>     $output .= '<i class="far fa-star"></i>';
>     $output .= '<i class="far fa-star"></i>';
>     $output .= '</span>';
>     $output .= '<span class="text"> & ' . esc_html__( 'up', 'aqualuxe' ) . '</span>';
>     $output .= '</label>';
>     $output .= '</div>';
>     $output .= '<div class="form-check">';
>     $output .= '<input class="form-check-input" type="checkbox" name="rating" value="2" id="rating-2">';
>     $output .= '<label class="form-check-label" for="rating-2">';
>     $output .= '<span class="stars">';
>     $output .= '<i class="fas fa-star"></i>';
>     $output .= '<i class="fas fa-star"></i>';
>     $output .= '<i class="far fa-star"></i>';
>     $output .= '<i class="far fa-star"></i>';
>     $output .= '<i class="far fa-star"></i>';
>     $output .= '</span>';
>     $output .= '<span class="text"> & ' . esc_html__( 'up', 'aqualuxe' ) . '</span>';
>     $output .= '</label>';
>     $output .= '</div>';
>     $output .= '<div class="form-check">';
>     $output .= '<input class="form-check-input" type="checkbox" name="rating" value="1" id="rating-1">';
>     $output .= '<label class="form-check-label" for="rating-1">';
>     $output .= '<span class="stars">';
>     $output .= '<i class="fas fa-star"></i>';
>     $output .= '<i class="far fa-star"></i>';
>     $output .= '<i class="far fa-star"></i>';
>     $output .= '<i class="far fa-star"></i>';
>     $output .= '<i class="far fa-star"></i>';
>     $output .= '</span>';
>     $output .= '<span class="text"> & ' . esc_html__( 'up', 'aqualuxe' ) . '</span>';
>     $output .= '</label>';
>     $output .= '</div>';
>     $output .= '<div class="form-group">';
>     $output .= '<button type="submit" class="btn btn-primary">' . esc_html__( 'Filter', 'aqualuxe' ) . '</button>';
>     $output .= '</div>';
>     $output .= '</form>';
>     $output .= '</div>';
> 
>     return $output;
> }
> 
> /**
>  * Get attribute filters
>  *
>  * @return string
>  */
> function aqualuxe_get_attribute_filters() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     $output = '';
> 
>     $attribute_taxonomies = wc_get_attribute_taxonomies();
> 
>     if ( ! empty( $attribute_taxonomies ) ) {
>         foreach ( $attribute_taxonomies as $tax ) {
>             $output .= '<div class="attribute-filter">';
>             $output .= '<h3>' . esc_html( $tax->attribute_label ) . '</h3>';
>             $output .= '<form class="attribute-filter-form" action="' . esc_url( home_url( '/' ) ) . '" method="get">';
>             $output .= '<div class="form-check">';
>             $terms = get_terms( 'pa_' . $tax->attribute_name );
>             if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
>                 foreach ( $terms as $term ) {
>                     $output .= '<div class="form-check">';
>                     $output .= '<input class="form-check-input" type="checkbox" name="filter_' . esc_attr( $tax->attribute_name ) . '[]" value="' . esc_attr( $term->slug ) . '" id="filter-' . esc_attr( $tax->attribute_name ) . '-' . esc_attr( $term->slug ) . '">';
>                     $output .= '<label class="form-check-label" for="filter-' . esc_attr( $tax->attribute_name ) . '-' . esc_attr( $term->slug ) . '">';
>                     $output .= esc_html( $term->name );
>                     $output .= '</label>';
>                     $output .= '</div>';
>                 }
>             }
>             $output .= '</div>';
>             $output .= '<div class="form-group">';
>             $output .= '<button type="submit" class="btn btn-primary">' . esc_html__( 'Filter', 'aqualuxe' ) . '</button>';
>             $output .= '</div>';
>             $output .= '</form>';
>             $output .= '</div>';
>         }
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get shop filters
>  *
>  * @return string
>  */
> function aqualuxe_get_shop_filters() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     $output = '<div class="shop-filters">';
>     $output .= aqualuxe_get_product_categories();
>     $output .= aqualuxe_get_product_tags();
>     $output .= aqualuxe_get_price_filter();
>     $output .= aqualuxe_get_rating_filter();
>     $output .= aqualuxe_get_attribute_filters();
>     $output .= '</div>';
> 
>     return $output;
> }
> 
> /**
>  * Get shop sidebar
>  *
>  * @return string
>  */
> function aqualuxe_get_shop_sidebar() {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     $output = '<aside id="secondary" class="widget-area shop-sidebar">';
>     $output .= aqualuxe_get_shop_filters();
>     $output .= '</aside>';
> 
>     return $output;
> }
> 
> /**
>  * Get product image
>  *
>  * @param int $product_id
>  * @param string $size
>  * @return string
>  */
> function aqualuxe_get_product_image( $product_id = 0, $size = 'woocommerce_thumbnail' ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     if ( ! $product_id ) {
>         $product_id = get_the_ID();
>     }
> 
>     $product = wc_get_product( $product_id );
>     $output = '';
> 
>     if ( $product ) {
>         $output = $product->get_image( $size );
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get product title
>  *
>  * @param int $product_id
>  * @return string
>  */
> function aqualuxe_get_product_title( $product_id = 0 ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     if ( ! $product_id ) {
>         $product_id = get_the_ID();
>     }
> 
>     $product = wc_get_product( $product_id );
>     $output = '';
> 
>     if ( $product ) {
>         $output = $product->get_name();
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get product price
>  *
>  * @param int $product_id
>  * @return string
>  */
> function aqualuxe_get_product_price( $product_id = 0 ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     if ( ! $product_id ) {
>         $product_id = get_the_ID();
>     }
> 
>     $product = wc_get_product( $product_id );
>     $output = '';
> 
>     if ( $product ) {
>         $output = $product->get_price_html();
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get product permalink
>  *
>  * @param int $product_id
>  * @return string
>  */
> function aqualuxe_get_product_permalink( $product_id = 0 ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     if ( ! $product_id ) {
>         $product_id = get_the_ID();
>     }
> 
>     $product = wc_get_product( $product_id );
>     $output = '';
> 
>     if ( $product ) {
>         $output = $product->get_permalink();
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get product add to cart button
>  *
>  * @param int $product_id
>  * @return string
>  */
> function aqualuxe_get_product_add_to_cart_button( $product_id = 0 ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     if ( ! $product_id ) {
>         $product_id = get_the_ID();
>     }
> 
>     $product = wc_get_product( $product_id );
>     $output = '';
> 
>     if ( $product ) {
>         $output = '<div class="add-to-cart-button">';
>         $output .= '<a href="' . esc_url( $product->add_to_cart_url() ) . '" class="btn btn-primary add-to-cart" data-quantity="1" data-product_id="' . esc_attr( $product_id ) . '" data-product_sku="' . esc_attr( $product->get_sku() ) . '" rel="nofollow">';
>         $output .= esc_html( $product->add_to_cart_text() );
>         $output .= '</a>';
>         $output .= '</div>';
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get product quick view button
>  *
>  * @param int $product_id
>  * @return string
>  */
> function aqualuxe_get_product_quick_view_button( $product_id = 0 ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     if ( ! $product_id ) {
>         $product_id = get_the_ID();
>     }
> 
>     $output = '<div class="quick-view-button">';
>     $output .= '<a href="#" class="btn btn-secondary quick-view" data-product-id="' . esc_attr( $product_id ) . '">';
>     $output .= esc_html__( 'Quick View', 'aqualuxe' );
>     $output .= '</a>';
>     $output .= '</div>';
> 
>     return $output;
> }
> 
> /**
>  * Get product rating
>  *
>  * @param int $product_id
>  * @return string
>  */
> function aqualuxe_get_product_rating( $product_id = 0 ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     if ( ! $product_id ) {
>         $product_id = get_the_ID();
>     }
> 
>     $product = wc_get_product( $product_id );
>     $output = '';
> 
>     if ( $product ) {
>         $output = '<div class="product-rating">';
>         $output .= wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() );
>         $output .= '</div>';
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get product excerpt
>  *
>  * @param int $product_id
>  * @param int $length
>  * @return string
>  */
> function aqualuxe_get_product_excerpt( $product_id = 0, $length = 55 ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     if ( ! $product_id ) {
>         $product_id = get_the_ID();
>     }
> 
>     $product = wc_get_product( $product_id );
>     $output = '';
> 
>     if ( $product ) {
>         $excerpt = $product->get_short_description();
>         $excerpt = strip_shortcodes( $excerpt );
>         $excerpt = strip_tags( $excerpt );
>         $excerpt = substr( $excerpt, 0, $length );
>         $excerpt = substr( $excerpt, 0, strrpos( $excerpt, ' ' ) );
>         $output = $excerpt . '...';
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get product categories list
>  *
>  * @param int $product_id
>  * @return string
>  */
> function aqualuxe_get_product_categories_list( $product_id = 0 ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     if ( ! $product_id ) {
>         $product_id = get_the_ID();
>     }
> 
>     $product = wc_get_product( $product_id );
>     $output = '';
> 
>     if ( $product ) {
>         $output = '<div class="product-categories">';
>         $output .= '<span>' . esc_html__( 'Categories:', 'aqualuxe' ) . '</span>';
>         $output .= wc_get_product_category_list( $product_id, ', ' );
>         $output .= '</div>';
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get product tags list
>  *
>  * @param int $product_id
>  * @return string
>  */
> function aqualuxe_get_product_tags_list( $product_id = 0 ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     if ( ! $product_id ) {
>         $product_id = get_the_ID();
>     }
> 
>     $product = wc_get_product( $product_id );
>     $output = '';
> 
>     if ( $product ) {
>         $output = '<div class="product-tags">';
>         $output .= '<span>' . esc_html__( 'Tags:', 'aqualuxe' ) . '</span>';
>         $output .= wc_get_product_tag_list( $product_id, ', ' );
>         $output .= '</div>';
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get product stock status
>  *
>  * @param int $product_id
>  * @return string
>  */
> function aqualuxe_get_product_stock_status( $product_id = 0 ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     if ( ! $product_id ) {
>         $product_id = get_the_ID();
>     }
> 
>     $product = wc_get_product( $product_id );
>     $output = '';
> 
>     if ( $product ) {
>         $output = '<div class="product-stock">';
>         $output .= '<span class="stock ' . esc_attr( $product->get_stock_status() ) . '">' . wc_get_stock_html( $product ) . '</span>';
>         $output .= '</div>';
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get product sku
>  *
>  * @param int $product_id
>  * @return string
>  */
> function aqualuxe_get_product_sku( $product_id = 0 ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     if ( ! $product_id ) {
>         $product_id = get_the_ID();
>     }
> 
>     $product = wc_get_product( $product_id );
>     $output = '';
> 
>     if ( $product && $product->get_sku() ) {
>         $output = '<div class="product-sku">';
>         $output .= '<span class="sku">' . esc_html__( 'SKU:', 'aqualuxe' ) . ' ' . esc_html( $product->get_sku() ) . '</span>';
>         $output .= '</div>';
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get product wrapper
>  *
>  * @param int $product_id
>  * @param string $class
>  * @return string
>  */
> function aqualuxe_get_product_wrapper( $product_id = 0, $class = '' ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     if ( ! $product_id ) {
>         $product_id = get_the_ID();
>     }
> 
>     $product = wc_get_product( $product_id );
>     $output = '';
> 
>     if ( $product ) {
>         $output = '<div class="product-wrapper ' . esc_attr( $class ) . '">';
>         $output .= '<div class="product-image">';
>         $output .= aqualuxe_get_product_image( $product_id );
>         $output .= '<div class="product-buttons">';
>         $output .= aqualuxe_get_product_add_to_cart_button( $product_id );
>         $output .= aqualuxe_get_product_quick_view_button( $product_id );
>         $output .= '</div>';
>         $output .= '</div>';
>         $output .= '<div class="product-content">';
>         $output .= '<h3 class="product-title"><a href="' . esc_url( aqualuxe_get_product_permalink( $product_id ) ) . '">' . esc_html( aqualuxe_get_product_title( $product_id ) ) . '</a></h3>';
>         $output .= '<div class="product-price">' . aqualuxe_get_product_price( $product_id ) . '</div>';
>         $output .= '<div class="product-rating">' . aqualuxe_get_product_rating( $product_id ) . '</div>';
>         $output .= '<div class="product-excerpt">' . aqualuxe_get_product_excerpt( $product_id ) . '</div>';
>         $output .= '</div>';
>         $output .= '</div>';
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get products loop
>  *
>  * @param array $args
>  * @return string
>  */
> function aqualuxe_get_products_loop( $args = array() ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     $defaults = array(
>         'post_type'      => 'product',
>         'posts_per_page' => get_option( 'posts_per_page' ),
>         'orderby'        => 'date',
>         'order'          => 'DESC',
>         'post_status'    => 'publish',
>     );
> 
>     $args = wp_parse_args( $args, $defaults );
> 
>     $products_query = new WP_Query( $args );
>     $output = '';
> 
>     if ( $products_query->have_posts() ) {
>         $output = '<div class="products-loop">';
>         $output .= '<div class="row">';
> 
>         while ( $products_query->have_posts() ) {
>             $products_query->the_post();
>             $output .= '<div class="col-md-3">';
>             $output .= aqualuxe_get_product_wrapper();
>             $output .= '</div>';
>         }
> 
>         $output .= '</div>';
>         $output .= '</div>';
> 
>         wp_reset_postdata();
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get featured products
>  *
>  * @param int $limit
>  * @return string
>  */
> function aqualuxe_get_featured_products( $limit = 8 ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     $args = array(
>         'post_type'      => 'product',
>         'posts_per_page' => $limit,
>         'orderby'        => 'date',
>         'order'          => 'DESC',
>         'post_status'    => 'publish',
>         'tax_query'      => array(
>             array(
>                 'taxonomy' => 'product_visibility',
>                 'field'    => 'name',
>                 'terms'    => 'featured',
>             ),
>         ),
>     );
> 
>     return aqualuxe_get_products_loop( $args );
> }
> 
> /**
>  * Get sale products
>  *
>  * @param int $limit
>  * @return string
>  */
> function aqualuxe_get_sale_products( $limit = 8 ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     $args = array(
>         'post_type'      => 'product',
>         'posts_per_page' => $limit,
>         'orderby'        => 'date',
>         'order'          => 'DESC',
>         'post_status'    => 'publish',
>         'meta_query'     => array(
>             'relation' => 'OR',
>             array( // Simple products type
>                 'key'           => '_sale_price',
>                 'value'         => 0,
>                 'compare'       => '>',
>                 'type'          => 'numeric'
>             ),
>             array( // Variable products type
>                 'key'           => '_min_variation_sale_price',
>                 'value'         => 0,
>                 'compare'       => '>',
>                 'type'          => 'numeric'
>             )
>         )
>     );
> 
>     return aqualuxe_get_products_loop( $args );
> }
> 
> /**
>  * Get best selling products
>  *
>  * @param int $limit
>  * @return string
>  */
> function aqualuxe_get_best_selling_products( $limit = 8 ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     $args = array(
>         'post_type'      => 'product',
>         'posts_per_page' => $limit,
>         'orderby'        => 'meta_value_num',
>         'order'          => 'DESC',
>         'post_status'    => 'publish',
>         'meta_key'       => 'total_sales',
>     );
> 
>     return aqualuxe_get_products_loop( $args );
> }
> 
> /**
>  * Get top rated products
>  *
>  * @param int $limit
>  * @return string
>  */
> function aqualuxe_get_top_rated_products( $limit = 8 ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     $args = array(
>         'post_type'      => 'product',
>         'posts_per_page' => $limit,
>         'orderby'        => 'meta_value_num',
>         'order'          => 'DESC',
>         'post_status'    => 'publish',
>         'meta_key'       => '_wc_average_rating',
>     );
> 
>     return aqualuxe_get_products_loop( $args );
> }
> 
> /**
>  * Get recent products
>  *
>  * @param int $limit
>  * @return string
>  */
> function aqualuxe_get_recent_products( $limit = 8 ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     $args = array(
>         'post_type'      => 'product',
>         'posts_per_page' => $limit,
>         'orderby'        => 'date',
>         'order'          => 'DESC',
>         'post_status'    => 'publish',
>     );
> 
>     return aqualuxe_get_products_loop( $args );
> }
> 
> /**
>  * Get related products
>  *
>  * @param int $limit
>  * @return string
>  */
> function aqualuxe_get_related_products( $limit = 4 ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     global $product;
> 
>     if ( ! $product ) {
>         return '';
>     }
> 
>     $related_products = wc_get_related_products( $product->get_id(), $limit );
>     $output = '';
> 
>     if ( $related_products ) {
>         $output = '<div class="related-products">';
>         $output .= '<h2>' . esc_html__( 'Related Products', 'aqualuxe' ) . '</h2>';
>         $output .= '<div class="row">';
> 
>         foreach ( $related_products as $related_product ) {
>             $post_object = get_post( $related_product->get_id() );
>             setup_postdata( $GLOBALS['post'] =& $post_object );
> 
>             $output .= '<div class="col-md-3">';
>             $output .= aqualuxe_get_product_wrapper();
>             $output .= '</div>';
>         }
> 
>         $output .= '</div>';
>         $output .= '</div>';
> 
>         wp_reset_postdata();
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get upsell products
>  *
>  * @param int $limit
>  * @return string
>  */
> function aqualuxe_get_upsell_products( $limit = 4 ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     global $product;
> 
>     if ( ! $product ) {
>         return '';
>     }
> 
>     $upsell_products = $product->get_upsell_ids();
>     $output = '';
> 
>     if ( $upsell_products ) {
>         $args = array(
>             'post_type'      => 'product',
>             'posts_per_page' => $limit,
>             'orderby'        => 'date',
>             'order'          => 'DESC',
>             'post_status'    => 'publish',
>             'post__in'       => $upsell_products,
>         );
> 
>         $upsell_query = new WP_Query( $args );
> 
>         if ( $upsell_query->have_posts() ) {
>             $output = '<div class="upsell-products">';
>             $output .= '<h2>' . esc_html__( 'You may also like&hellip;', 'aqualuxe' ) . '</h2>';
>             $output .= '<div class="row">';
> 
>             while ( $upsell_query->have_posts() ) {
>                 $upsell_query->the_post();
>                 $output .= '<div class="col-md-3">';
>                 $output .= aqualuxe_get_product_wrapper();
>                 $output .= '</div>';
>             }
> 
>             $output .= '</div>';
>             $output .= '</div>';
> 
>             wp_reset_postdata();
>         }
>     }
> 
>     return $output;
> }
> 
> /**
>  * Get cross sell products
>  *
>  * @param int $limit
>  * @return string
>  */
> function aqualuxe_get_cross_sell_products( $limit = 4 ) {
>     if ( ! aqualuxe_is_woocommerce_active() ) {
>         return '';
>     }
> 
>     $cross_sell_products = array();
>     $cart = WC()->cart->get_cart();
> 
>     foreach ( $cart as $cart_item_key => $cart_item ) {
>         $product = $cart_item['data'];
>         $cross_sells = array_merge( $cross_sell_products, $product->get_cross_sell_ids() );
>     }
> 
>     $cross_sell_products = array_unique( $cross_sell_products );
>     $output = '';
> 
>     if ( $cross_sell_products ) {
>         $args = array(
>             'post_type'      => 'product',
>             'posts_per_page' => $limit,
>             'orderby'        => 'date',
>             'order'          => 'DESC',
>             'post_status'    => 'publish',
>             'post__in'       => $cross_sell_products,
>         );
> 
>         $cross_sell_query = new WP_Query( $args );
> 
>         if ( $cross_sell_query->have_posts() ) {
>             $output = '<div class="cross-sell-products">';
>             $output .= '<h2>' . esc_html__( 'You may be interested in&hellip;', 'aqualuxe' ) . '</h2>';
>             $output .= '<div class="row">';
> 
>             while ( $cross_sell_query->have_posts() ) {
>                 $cross_sell_query->the_post();
>                 $output .= '<div class="col-md-3">';
>                 $output .= aqualuxe_get_product_wrapper();
>                 $output .= '</div>';
>             }
> 
>             $output .= '</div>';
>             $output .= '</div>';
> 
>             wp_reset_postdata();
>         }
>     }
> 
>     return $output;
> }
> ```
> 
> ### inc/demo-content.php
> 
> ```php
> <?php
> /**
>  * AquaLuxe Demo Content Importer
>  *
>  * @package AquaLuxe
>  */
> 
> if ( ! defined( 'ABSPATH' ) ) {
>     exit; // Exit if accessed directly.
> }
> 
> /**
>  * Demo Content Importer Class
>  */
> class AquaLuxe_Demo_Content_Importer {
> 
>     /**
>      * Constructor
>      */
>     public function __construct() {
>         add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
>         add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
>         add_action( 'wp_ajax_aqualuxe_import_demo_content', array( $this, 'import_demo_content' ) );
>     }
> 
>     /**
>      * Add admin menu
>      */
>     public function add_admin_menu() {
>         add_theme_page(
>             __( 'Demo Content', 'aqualuxe' ),
>             __( 'Demo Content', 'aqualuxe' ),
>             'manage_options',
>             'aqualuxe-demo-content',
>             array( $this, 'render_admin_page' )
>         );
>     }
> 
>     /**
>      * Enqueue admin scripts
>      */
>     public function enqueue_admin_scripts( $hook ) {
>         if ( 'appearance_page_aqualuxe-demo-content' !== $hook ) {
>             return;
>         }
> 
>         wp_enqueue_style( 'aqualuxe-demo-content', get_template_directory_uri() . '/assets/css/admin/demo-content.css', array(), AQUALUXE_VERSION );
>         wp_enqueue_script( 'aqualuxe-demo-content', get_template_directory_uri() . '/assets/js/admin/demo-content.js', array( 'jquery' ), AQUALUXE_VERSION, true );
> 
>         wp_localize_script( 'aqualuxe-demo-content', 'aqualuxe_demo_content', array(
>             'ajax_url' => admin_url( 'admin-ajax.php' ),
>             'nonce'    => wp_create_nonce( 'aqualuxe-demo-content' ),
>             'i18n'     => array(
>                 'importing' => __( 'Importing demo content, please wait...', 'aqualuxe' ),
>                 'success'   => __( 'Demo content imported successfully!', 'aqualuxe' ),
>                 'error'     => __( 'An error occurred while importing demo content. Please try again.', 'aqualuxe' ),
>             ),
>         ) );
>     }
> 
>     /**
>      * Render admin page
>      */
>     public function render_admin_page() {
>         ?>
>         <div class="wrap">
>             <h1><?php esc_html_e( 'AquaLuxe Demo Content', 'aqualuxe' ); ?></h1>
>             <div class="aqualuxe-demo-content-wrapper">
>                 <div class="aqualuxe-demo-content-intro">
>                     <p><?php esc_html_e( 'This will import the demo content for the AquaLuxe theme. It includes sample products, pages, and settings to help you get started.', 'aqualuxe' ); ?></p>
>                     <p><strong><?php esc_html_e( 'Warning:', 'aqualuxe' ); ?></strong> <?php esc_html_e( 'This will replace your current content and settings. Please make sure to backup your site before importing the demo content.', 'aqualuxe' ); ?></p>
>                 </div>
>                 <div class="aqualuxe-demo-content-actions">
>                     <button id="aqualuxe-import-demo-content" class="button button-primary">
>                         <?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?>
>                     </button>
>                     <div class="aqualuxe-demo-content-progress" style="display: none;">
>                         <div class="progress">
>                             <div class="progress-bar" role="progressbar" style="width: 0%"></div>
>                         </div>
>                         <div class="progress-text"></div>
>                     </div>
>                 </div>
>                 <div class="aqualuxe-demo-content-result" style="display: none;"></div>
>             </div>
>         </div>
>         <?php
>     }
> 
>     /**
>      * Import demo content
>      */
>     public function import_demo_content() {
>         check_ajax_referer( 'aqualuxe-demo-content', 'nonce' );
> 
>         if ( ! current_user_can( 'manage_options' ) ) {
>             wp_send_json_error( array( 'message' => __( 'You do not have permission to import demo content.', 'aqualuxe' ) ) );
>         }
> 
>         // Import products
>         $this->import_products();
> 
>         // Import pages
>         $this->import_pages();
> 
>         // Import settings
>         $this->import_settings();
> 
>         // Import widgets
>         $this->import_widgets();
> 
>         // Import menus
>         $this->import_menus();
> 
>         wp_send_json_success( array( 'message' => __( 'Demo content imported successfully!', 'aqualuxe' ) ) );
>     }
> 
>     /**
>      * Import products
>      */
>     private function import_products() {
>         $products = array(
>             array(
>                 'title'       => 'Betta Fish',
>                 'description' => 'Betta fish, also known as Siamese fighting fish, are small, colorful freshwater fish native to Southeast Asia. They are known for their vibrant colors and flowing fins.',
>                 'short_description' => 'Colorful and elegant freshwater fish with flowing fins.',
>                 'price'       => 12.99,
>                 'regular_price' => 15.99,
>                 'sale_price'  => 12.99,
>                 'sku'         => 'BF-001',
>                 'stock_status' => 'instock',
>                 'manage_stock' => true,
>                 'stock_quantity' => 50,
>                 'images'      => array(
>                     array(
>                         'src' => get_template_directory_uri() . '/assets/images/demo/betta-fish.jpg',
>                     ),
>                 ),
>                 'categories'  => array( 'Freshwater Fish' ),
>                 'tags'        => array( 'colorful', 'popular', 'beginner' ),
>                 'attributes'  => array(
>                     array(
>                         'name'      => 'Water Temperature',
>                         'value'     => '75-82°F',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                     array(
>                         'name'      => 'pH Level',
>                         'value'     => '6.5-7.5',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                     array(
>                         'name'      => 'Tank Size',
>                         'value'     => '5 gallons',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                 ),
>             ),
>             array(
>                 'title'       => 'Goldfish',
>                 'description' => 'Goldfish are one of the most popular aquarium fish in the world. They are hardy, easy to care for, and come in a variety of colors and shapes.',
>                 'short_description' => 'Classic and easy-to-care-for freshwater fish.',
>                 'price'       => 5.99,
>                 'sku'         => 'GF-001',
>                 'stock_status' => 'instock',
>                 'manage_stock' => true,
>                 'stock_quantity' => 100,
>                 'images'      => array(
>                     array(
>                         'src' => get_template_directory_uri() . '/assets/images/demo/goldfish.jpg',
>                     ),
>                 ),
>                 'categories'  => array( 'Freshwater Fish' ),
>                 'tags'        => array( 'popular', 'beginner', 'hardy' ),
>                 'attributes'  => array(
>                     array(
>                         'name'      => 'Water Temperature',
>                         'value'     => '65-75°F',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                     array(
>                         'name'      => 'pH Level',
>                         'value'     => '6.5-7.5',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                     array(
>                         'name'      => 'Tank Size',
>                         'value'     => '20 gallons',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                 ),
>             ),
>             array(
>                 'title'       => 'Clownfish',
>                 'description' => 'Clownfish are small, brightly colored saltwater fish that are famous for their symbiotic relationship with sea anemones. They are popular in home aquariums.',
>                 'short_description' => 'Vibrant saltwater fish with distinctive orange and white stripes.',
>                 'price'       => 24.99,
>                 'sku'         => 'CF-001',
>                 'stock_status' => 'instock',
>                 'manage_stock' => true,
>                 'stock_quantity' => 30,
>                 'images'      => array(
>                     array(
>                         'src' => get_template_directory_uri() . '/assets/images/demo/clownfish.jpg',
>                     ),
>                 ),
>                 'categories'  => array( 'Saltwater Fish' ),
>                 'tags'        => array( 'popular', 'saltwater', 'beginner' ),
>                 'attributes'  => array(
>                     array(
>                         'name'      => 'Water Temperature',
>                         'value'     => '75-82°F',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                     array(
>                         'name'      => 'pH Level',
>                         'value'     => '8.1-8.4',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                     array(
>                         'name'      => 'Tank Size',
>                         'value'     => '20 gallons',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                 ),
>             ),
>             array(
>                 'title'       => 'Angelfish',
>                 'description' => 'Angelfish are a type of cichlid known for their distinctive triangular shape and long fins. They are popular freshwater fish that come in a variety of colors.',
>                 'short_description' => 'Elegant freshwater fish with a distinctive triangular shape.',
>                 'price'       => 8.99,
>                 'sku'         => 'AF-001',
>                 'stock_status' => 'instock',
>                 'manage_stock' => true,
>                 'stock_quantity' => 40,
>                 'images'      => array(
>                     array(
>                         'src' => get_template_directory_uri() . '/assets/images/demo/angelfish.jpg',
>                     ),
>                 ),
>                 'categories'  => array( 'Freshwater Fish' ),
>                 'tags'        => array( 'popular', 'elegant', 'intermediate' ),
>                 'attributes'  => array(
>                     array(
>                         'name'      => 'Water Temperature',
>                         'value'     => '75-82°F',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                     array(
>                         'name'      => 'pH Level',
>                         'value'     => '6.5-7.5',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                     array(
>                         'name'      => 'Tank Size',
>                         'value'     => '20 gallons',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                 ),
>             ),
>             array(
>                 'title'       => 'Neon Tetra',
>                 'description' => 'Neon tetras are small, brightly colored freshwater fish known for their iridescent blue and red stripes. They are peaceful and make great community fish.',
>                 'short_description' => 'Small, colorful freshwater fish perfect for community tanks.',
>                 'price'       => 2.99,
>                 'sku'         => 'NT-001',
>                 'stock_status' => 'instock',
>                 'manage_stock' => true,
>                 'stock_quantity' => 200,
>                 'images'      => array(
>                     array(
>                         'src' => get_template_directory_uri() . '/assets/images/demo/neon-tetra.jpg',
>                     ),
>                 ),
>                 'categories'  => array( 'Freshwater Fish' ),
>                 'tags'        => array( 'popular', 'schooling', 'beginner' ),
>                 'attributes'  => array(
>                     array(
>                         'name'      => 'Water Temperature',
>                         'value'     => '70-81°F',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                     array(
>                         'name'      => 'pH Level',
>                         'value'     => '6.0-7.0',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                     array(
>                         'name'      => 'Tank Size',
>                         'value'     => '10 gallons',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                 ),
>             ),
>             array(
>                 'title'       => 'Guppy',
>                 'description' => 'Guppies are small, colorful freshwater fish that are easy to breed and care for. They are popular among beginners and come in a wide variety of colors and patterns.',
>                 'short_description' => 'Colorful and easy-to-breed freshwater fish.',
>                 'price'       => 3.99,
>                 'sku'         => 'GP-001',
>                 'stock_status' => 'instock',
>                 'manage_stock' => true,
>                 'stock_quantity' => 150,
>                 'images'      => array(
>                     array(
>                         'src' => get_template_directory_uri() . '/assets/images/demo/guppy.jpg',
>                     ),
>                 ),
>                 'categories'  => array( 'Freshwater Fish' ),
>                 'tags'        => array( 'popular', 'beginner', 'easy-to-breed' ),
>                 'attributes'  => array(
>                     array(
>                         'name'      => 'Water Temperature',
>                         'value'     => '72-82°F',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                     array(
>                         'name'      => 'pH Level',
>                         'value'     => '6.8-7.8',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                     array(
>                         'name'      => 'Tank Size',
>                         'value'     => '5 gallons',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                 ),
>             ),
>             array(
>                 'title'       => 'Discus Fish',
>                 'description' => 'Discus fish are known for their distinctive circular shape and vibrant colors. They are considered one of the most beautiful freshwater fish but require more care than many other species.',
>                 'short_description' => 'Stunningly beautiful freshwater fish that require special care.',
>                 'price'       => 49.99,
>                 'sku'         => 'DF-001',
>                 'stock_status' => 'instock',
>                 'manage_stock' => true,
>                 'stock_quantity' => 20,
>                 'images'      => array(
>                     array(
>                         'src' => get_template_directory_uri() . '/assets/images/demo/discus-fish.jpg',
>                     ),
>                 ),
>                 'categories'  => array( 'Freshwater Fish' ),
>                 'tags'        => array( 'premium', 'advanced', 'colorful' ),
>                 'attributes'  => array(
>                     array(
>                         'name'      => 'Water Temperature',
>                         'value'     => '82-86°F',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                     array(
>                         'name'      => 'pH Level',
>                         'value'     => '6.0-7.0',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                     array(
>                         'name'      => 'Tank Size',
>                         'value'     => '50 gallons',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                 ),
>             ),
>             array(
>                 'title'       => 'Molly Fish',
>                 'description' => 'Mollies are small, peaceful freshwater fish that come in a variety of colors. They are easy to care for and are great for community tanks.',
>                 'short_description' => 'Peaceful and colorful freshwater fish for community tanks.',
>                 'price'       => 4.99,
>                 'sku'         => 'MF-001',
>                 'stock_status' => 'instock',
>                 'manage_stock' => true,
>                 'stock_quantity' => 100,
>                 'images'      => array(
>                     array(
>                         'src' => get_template_directory_uri() . '/assets/images/demo/molly-fish.jpg',
>                     ),
>                 ),
>                 'categories'  => array( 'Freshwater Fish' ),
>                 'tags'        => array( 'popular', 'peaceful', 'beginner' ),
>                 'attributes'  => array(
>                     array(
>                         'name'      => 'Water Temperature',
>                         'value'     => '75-82°F',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                     array(
>                         'name'      => 'pH Level',
>                         'value'     => '7.5-8.5',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                     array(
>                         'name'      => 'Tank Size',
>                         'value'     => '10 gallons',
>                         'visible'   => true,
>                         'variation' => false,
>                     ),
>                 ),
>             ),
>         );
> 
>         foreach ( $products as $product_data ) {
>             $this->create_product( $product_data );
>         }
>     }
> 
>     /**
>      * Create product
>      *
>      * @param array $product_data Product data.
>      */
>     private function create_product( $product_data ) {
>         $product = new WC_Product();
> 
>         $product->set_name( $product_data['title'] );
>         $product->set_description( $product_data['description'] );
>         $product->set_short_description( $product_data['short_description'] );
>         $product->set_price( $product_data['price'] );
>         $product->set_regular_price( $product_data['regular_price'] );
> 
>         if ( isset( $product_data['sale_price'] ) ) {
>             $product->set_sale_price( $product_data['sale_price'] );
>         }
> 
>         $product->set_sku( $product_data['sku'] );
>         $product->set_stock_status( $product_data['stock_status'] );
>         $product->set_manage_stock( $product_data['manage_stock'] );
>         $product->set_stock_quantity( $product_data['stock_quantity'] );
> 
>         // Set categories
>         if ( ! empty( $product_data['categories'] ) ) {
>             $term_ids = array();
>             foreach ( $product_data['categories'] as $category ) {
>                 $term = get_term_by( 'name', $category, 'product_cat' );
>                 if ( ! $term ) {
>                     $term = wp_insert_term( $category, 'product_cat' );
>                     $term_id = is_wp_error( $term ) ? 0 : $term['term_id'];
>                 } else {
>                     $term_id = $term->term_id;
>                 }
>                 $term_ids[] = $term_id;
>             }
>             $product->set_category_ids( $term_ids );
>         }
> 
>         // Set tags
>         if ( ! empty( $product_data['tags'] ) ) {
>             $term_ids = array();
>             foreach ( $product_data['tags'] as $tag ) {
>                 $term = get_term_by( 'name', $tag, 'product_tag' );
>                 if ( ! $term ) {
>                     $term = wp_insert_term( $tag, 'product_tag' );
>                     $term_id = is_wp_error( $term ) ? 0 : $term['term_id'];
>                 } else {
>                     $term_id = $term->term_id;
>                 }
>                 $term_ids[] = $term_id;
>             }
>             $product->set_tag_ids( $term_ids );
>         }
> 
>         // Set attributes
>         if ( ! empty( $product_data['attributes'] ) ) {
>             $attributes = array();
>             foreach ( $product_data['attributes'] as $attribute ) {
>                 $attribute_object = new WC_Product_Attribute();
>                 $attribute_object->set_name( $attribute['name'] );
>                 $attribute_object->set_options( array( $attribute['value'] ) );
>                 $attribute_object->set_visible( $attribute['visible'] );
>                 $attribute_object->set_variation( $attribute['variation'] );
>                 $attributes[] = $attribute_object;
>             }
>             $product->set_attributes( $attributes );
>         }
> 
>         // Set images
>         if ( ! empty( $product_data['images'] ) ) {
>             $image_ids = array();
>             foreach ( $product_data['images'] as $image ) {
>                 $image_id = $this->upload_image( $image['src'] );
>                 if ( $image_id ) {
>                     $image_ids[] = $image_id;
>                 }
>             }
>             if ( ! empty( $image_ids ) ) {
>                 $product->set_image_id( $image_ids[0] );
>                 $product->set_gallery_image_ids( array_slice( $image_ids, 1 ) );
>             }
>         }
> 
>         $product->save();
>     }
> 
>     /**
>      * Upload image
>      *
>      * @param string $image_url Image URL.
>      * @return int|false
>      */
>     private function upload_image( $image_url ) {
>         require_once( ABSPATH . 'wp-admin/includes/image.php' );
>         require_once( ABSPATH . 'wp-admin/includes/file.php' );
>         require_once( ABSPATH . 'wp-admin/includes/media.php' );
> 
>         $image_url = str_replace( get_template_directory_uri(), get_template_directory(), $image_url );
> 
>         if ( ! file_exists( $image_url ) ) {
>             return false;
>         }
> 
>         $file_array = array(
>             'name'     => basename( $image_url ),
>             'tmp_name' => $image_url,
>         );
> 
>         // Check for allowed file types
>         $allowed_types = array( 'jpg', 'jpeg', 'png', 'gif' );
>         $file_info = wp_check_filetype_and_ext( $file_array['tmp_name'], $file_array['name'] );
>         if ( ! in_array( strtolower( $file_info['ext'] ), $allowed_types ) ) {
>             return false;
>         }
> 
>         // Upload the file
>         $attachment_id = media_handle_sideload( $file_array, 0 );
> 
>         if ( is_wp_error( $attachment_id ) ) {
>             return false;
>         }
> 
>         // Set attachment alt text
>         $attachment = get_post( $attachment_id );
>         $attachment->post_title = preg_replace( '/\.[^.]+$/', '', basename( $image_url ) );
>         wp_update_post( $attachment );
> 
>         // Generate attachment metadata
>         $attach_data = wp_generate_attachment_metadata( $attachment_id, $image_url );
>         wp_update_attachment_metadata( $attachment_id, $attach_data );
> 
>         return $attachment_id;
>     }
> 
>     /**
>      * Import pages
>      */
>     private function import_pages() {
>         $pages = array(
>             array(
>                 'title'   => 'About Us',
>                 'content' => '<h2>About AquaLuxe</h2>
> <p>Welcome to AquaLuxe, your premier destination for high-quality ornamental fish. We are passionate about bringing the beauty and tranquility of aquatic life into your home or office.</p>
> <p>Founded in 2020, AquaLuxe has quickly become a trusted name in the ornamental fish industry. Our team of experts is dedicated to providing healthy, vibrant fish and exceptional customer service.</p>
> <h3>Our Mission</h3>
> <p>Our mission is to make the joy of fishkeeping accessible to everyone, from beginners to experienced hobbyists. We strive to provide the highest quality fish, expert advice, and top-notch customer service.</p>
> <h3>Our Values</h3>
> <ul>
> <li><strong>Quality:</strong> We only source the healthiest and most beautiful fish from reputable breeders.</li>
> <li><strong>Sustainability:</strong> We are committed to responsible and sustainable fishkeeping practices.</li>
> <li><strong>Education:</strong> We believe in empowering our customers with knowledge and resources.</li>
> <li><strong>Customer Satisfaction:</strong> Your satisfaction is our top priority.</li>
> </ul>
> <h3>Meet Our Team</h3>
> <p>Our team consists of experienced aquarists, marine biologists, and fish enthusiasts who are passionate about what they do. We are always here to answer your questions and provide expert advice.</p>
> <p>Thank you for choosing AquaLuxe for your ornamental fish needs. We look forward to serving you!</p>',
>             ),
>             array(
>                 'title'   => 'Contact Us',
>                 'content' => '<h2>Contact AquaLuxe</h2>
> <p>We would love to hear from you! Whether you have a question about our products, need advice on fish care, or want to share your feedback, our team is here to help.</p>
> <h3>Get in Touch</h3>
> <p><strong>Address:</strong><br>
> 123 Aquarium Lane<br>
> Fishville, FW 12345</p>
> <p><strong>Phone:</strong><br>
> (555) 123-4567</p>
> <p><strong>Email:</strong><br>
> info@aqualuxe.com</p>
> <p><strong>Business Hours:</strong><br>
> Monday - Friday: 9:00 AM - 6:00 PM<br>
> Saturday: 10:00 AM - 4:00 PM<br>
> Sunday: Closed</p>
> <h3>Send Us a Message</h3>
> <p>Fill out the form below and we will get back to you as soon as possible.</p>
> [contact-form-7 id="5" title="Contact form 1"]
> <h3>Visit Our Store</h3>
> <p>We invite you to visit our store to see our beautiful fish in person. Our knowledgeable staff is always happy to help you find the perfect fish for your aquarium.</p>
> <p>[map address="123 Aquarium Lane, Fishville, FW 12345" width="100%" height="400px"]</p>',
>             ),
>             array(
>                 'title'   => 'Fish Care Guide',
>                 'content' => '<h2>Fish Care Guide</h2>
> <p>Welcome to our comprehensive fish care guide. Whether you\'re a beginner or an experienced aquarist, you\'ll find valuable information to help you keep your fish healthy and happy.</p>
> <h3>Setting Up Your Aquarium</h3>
> <p>Before bringing fish home, it\'s important to properly set up your aquarium. This includes choosing the right tank size, installing a filtration system, and cycling the tank to establish beneficial bacteria.</p>
> <h4>Choosing the Right Tank Size</h4>
> <p>The size of your tank depends on the type and number of fish you plan to keep. As a general rule, larger tanks are more stable and easier to maintain than smaller ones.</p>
> <h4>Filtration System</h4>
> <p>A good filtration system is essential for maintaining water quality. It removes waste, chemicals, and other impurities from the water.</p>
> <h4>Cycling the Tank</h4>
> <p>Cycling is the process of establishing beneficial bacteria in your tank. These bacteria help break down waste products and keep the water safe for your fish.</p>
> <h3>Water Quality</h3>
> <p>Maintaining good water quality is crucial for the health of your fish. This includes monitoring temperature, pH, ammonia, nitrite, and nitrate levels.</p>
> <h4>Temperature</h4>
> <p>Different fish have different temperature requirements. Most tropical fish prefer water between 75-82°F (24-28°C).</p>
> <h4>pH Level</h4>
> <p>The pH level measures the acidity or alkalinity of the water. Most freshwater fish prefer a pH between 6.5-7.5, while saltwater fish prefer a pH between 8.1-8.4.</p>
> <h4>Ammonia, Nitrite, and Nitrate</h4>
> <p>These are waste products that can be harmful to fish in high concentrations. Regular water testing and partial water changes can help keep these levels in check.</p>
> <h3>Feeding Your Fish</h3>
> <p>Proper nutrition is essential for the health and longevity of your fish. Different fish have different dietary requirements, so it\'s important to research the specific needs of your fish.</p>
> <h4>Types of Fish Food</h4>
> <ul>
> <li><strong>Flakes:</strong> Suitable for most surface and mid-water fish.</li>
> <li><strong>Pellets:</strong> Good for larger fish and bottom feeders.</li>
> <li><strong>Live or Frozen Food:</strong> Provides variety and essential nutrients.</li>
> <li><strong>Vegetable Matter:</strong> Important for herbivorous fish.</li>
> </ul>
> <h4>Feeding Schedule</h4>
> <p>Most fish should be fed small amounts 2-3 times a day. Overfeeding can lead to poor water quality and health problems.</p>
> <h3>Tank Maintenance</h3>
> <p>Regular maintenance is essential for keeping your aquarium clean and your fish healthy.</p>
> <h4>Partial Water Changes</h4>
> <p>Changing 10-25% of the water every 1-2 weeks helps maintain water quality and remove waste products.</p>
> <h4>Cleaning the Tank</h4>
> <p>Regularly clean the glass, decorations, and substrate to remove algae and debris.</p>
> <h4>Filter Maintenance</h4>
> <p>Clean or replace filter media according to the manufacturer\'s instructions to ensure proper filtration.</p>
> <h3>Fish Health</h3>
> <p>Monitoring your fish for signs of illness is crucial for early detection and treatment.</p>
> <h4>Common Signs of Illness</h4>
> <ul>
> <li>Changes in behavior or appearance</li>
> <li>Loss of appetite</li>
> <li>Rapid breathing</li>
> <li>Clamped fins</li>
> <li>White spots or patches</li>
> <li>Erratic swimming</li>
> </ul>
> <h4>Treatment</h4>
> <p>If you notice any signs of illness, isolate the affected fish and consult a veterinarian or experienced aquarist for treatment options.</p>
> <h3>Conclusion</h3>
> <p>Fishkeeping can be a rewarding hobby that brings beauty and tranquility to your home. By following these guidelines and continuing to learn about your fish, you can create a thriving aquatic environment for years to enjoyment.</p>',
>             ),
>             array(
>                 'title'   => 'Shipping & Returns',
>                 'content' => '<h2>Shipping & Returns</h2>
> <p>At AquaLuxe, we are committed to providing you with the best possible shopping experience. This includes ensuring that your fish arrive safely and that you are satisfied with your purchase.</p>
> <h3>Shipping</h3>
> <h4>Shipping Methods</h4>
> <p>We offer several shipping options to meet your needs:</p>
> <ul>
> <li><strong>Standard Shipping:</strong> 5-7 business days</li>
> <li><strong>Express Shipping:</strong> 2-3 business days</li>
> <li><strong>Overnight Shipping:</strong> 1 business day</li>
> </ul>
> <h4>Shipping Rates</h4>
> <p>Shipping rates are calculated based on the weight of your order and your location. You will see the shipping cost at checkout before you complete your purchase.</p>
> <h4>Live Arrival Guarantee</h4>
> <p>We guarantee that your fish will arrive alive and healthy. If any fish arrive dead, please contact us within 2 hours of delivery with a photo of the deceased fish in the unopened bag. We will either replace the fish or issue a refund.</p>
> <h4>Shipping Restrictions</h4>
> <p>Due to regulations and weather conditions, we cannot ship to certain locations or during extreme weather. We will notify you if there are any restrictions that affect your order.</p>
> <h3>Returns</h3>
> <h4>Return Policy</h4>
> <p>Because we are dealing with live animals, we cannot accept returns for fish. However, if you are not satisfied with your purchase for any reason, please contact us within 7 days of delivery, and we will do our best to resolve the issue.</p>
> <h4>Non-Live Products</h4>
> <p>For non-live products such as aquarium supplies, equipment, and accessories, we offer a 30-day return policy. Items must be unused and in their original packaging. Return shipping costs are the responsibility of the customer unless the return is due to a mistake on our part.</p>
> <h4>How to Initiate a Return</h4>
> <p>To initiate a return, please contact our customer service team at info@aqualuxe.com or (555) 123-4567. Please provide your order number and the reason for the return.</p>
> <h3>Cancellations</h3>
> <p>If you need to cancel your order, please contact us as soon as possible. If the order has not yet been processed for shipping, we can cancel it and issue a full refund. If the order has already been processed, we may not be able to cancel it.</p>
> <h3>Refunds</h3>
> <p>Refunds will be issued to the original payment method within 5-7 business days after we receive the returned item or process the cancellation. Please note that it may take additional time for the refund to appear in your account.</p>
> <h3>Contact Us</h3>
> <p>If you have any questions about our shipping and return policies, please don\'t hesitate to contact us. Our customer service team is available Monday-Friday, 9:00 AM - 6:00 PM EST.</p>
> <p><strong>Email:</strong> info@aqualuxe.com<br>
> <strong>Phone:</strong> (555) 123-4567</p>',
>             ),
>         );
> 
>         foreach ( $pages as $page_data ) {
>             $this->create_page( $page_data );
>         }
>     }
> 
>     /**
>      * Create page
>      *
>      * @param array $page_data Page data.
>      */
>     private function create_page( $page_data ) {
>         $page = array(
>             'post_title'   => $page_data['title'],
>             'post_content' => $page_data['content'],
>             'post_status'  => 'publish',
>             'post_type'    => 'page',
>             'post_author'  => 1,
>         );
> 
>         wp_insert_post( $page );
>     }
> 
>     /**
>      * Import settings
>      */
>     private function import_settings() {
>         // Theme settings
>         set_theme_mod( 'aqualuxe_primary_color', '#0073aa' );
>         set_theme_mod( 'aqualuxe_secondary_color', '#00a0d2' );
>         set_theme_mod( 'aqualuxe_accent_color', '#2ea2cc' );
>         set_theme_mod( 'aqualuxe_dark_color', '#2c4158' );
>         set_theme_mod( 'aqualuxe_light_color', '#f8f9fa' );
>         set_theme_mod( 'aqualuxe_body_font_size', '16' );
>         set_theme_mod( 'aqualuxe_heading_font_size', '32' );
>         set_theme_mod( 'aqualuxe_container_width', '1140' );
>         set_theme_mod( 'aqualuxe_product_columns', '4' );
>         set_theme_mod( 'aqualuxe_shop_layout', 'right-sidebar' );
>         set_theme_mod( 'aqualuxe_sticky_header', '1' );
>         set_theme_mod( 'aqualuxe_footer_widgets_columns', '4' );
>         set_theme_mod( 'aqualuxe_footer_copyright', 'Copyright &copy; {year} {site_title}. All Rights Reserved.' );
>         set_theme_mod( 'aqualuxe_phone_number', '+1 (555) 123-4567' );
>         set_theme_mod( 'aqualuxe_email_address', 'info@aqualuxe.com' );
>         set_theme_mod( 'aqualuxe_address', '123 Aquarium Lane, Fishville, FW 12345' );
>         set_theme_mod( 'aqualuxe_facebook_url', 'https://facebook.com/aqualuxe' );
>         set_theme_mod( 'aqualuxe_twitter_url', 'https://twitter.com/aqualuxe' );
>         set_theme_mod( 'aqualuxe_instagram_url', 'https://instagram.com/aqualuxe' );
>         set_theme_mod( 'aqualuxe_youtube_url', 'https://youtube.com/aqualuxe' );
>         set_theme_mod( 'aqualuxe_mon_fri_hours', '9:00 AM - 6:00 PM' );
>         set_theme_mod( 'aqualuxe_sat_hours', '10:00 AM - 4:00 PM' );
>         set_theme_mod( 'aqualuxe_sun_hours', 'Closed' );
> 
>         // WooCommerce settings
>         update_option( 'woocommerce_default_country', 'US:NY' );
>         update_option( 'woocommerce_currency', 'USD' );
>         update_option( 'woocommerce_product_image_width', '400' );
>         update_option( 'woocommerce_thumbnail_image_width', '300' );
>         update_option( 'woocommerce_single_image_width', '600' );
>         update_option( 'woocommerce_thumbnail_cropping', '1:1' );
>         update_option( 'woocommerce_shop_page_display', '' );
>         update_option( 'woocommerce_category_archive_display', '' );
>         update_option( 'woocommerce_default_catalog_orderby', 'menu_order' );
>         update_option( 'woocommerce_enable_ajax_add_to_cart', 'yes' );
>         update_option( 'woocommerce_enable_guest_checkout', 'yes' );
>         update_option( 'woocommerce_enable_shipping_calc', 'yes' );
>         update_option( 'woocommerce_allow_shipping', 'yes' );
>         update_option( 'woocommerce_calc_taxes', 'no' );
>         update_option( 'woocommerce_coupons_enabled', 'yes' );
>         update_option( 'woocommerce_enable_myaccount_registration', 'yes' );
>         update_option( 'woocommerce_myaccount_page_id', $this->get_page_id_by_title( 'My Account' ) );
>         update_option( 'woocommerce_shop_page_id', $this->get_page_id_by_title( 'Shop' ) );
>         update_option( 'woocommerce_cart_page_id', $this->get_page_id_by_title( 'Cart' ) );
>         update_option( 'woocommerce_checkout_page_id', $this->get_page_id_by_title( 'Checkout' ) );
>         update_option( 'woocommerce_terms_page_id', $this->get_page_id_by_title( 'Terms and Conditions' ) );
>     }
> 
>     /**
>      * Get page ID by title
>      *
>      * @param string $title Page title.
>      * @return int
>      */
>     private function get_page_id_by_title( $title ) {
>         $page = get_page_by_title( $title );
>         return $page ? $page->ID : 0;
>     }
> 
>     /**
>      * Import widgets
>      */
>     private function import_widgets() {
>         // Create sidebar widgets
>         $sidebars_widgets = get_option( 'sidebars_widgets' );
> 
>         // Footer widgets
>         $sidebars_widgets['footer-1'] = array(
>             'aqualuxe_contact_info-1',
>         );
> 
>         $sidebars_widgets['footer-2'] = array(
>             'nav_menu-1',
>         );
> 
>         $sidebars_widgets['footer-3'] = array(
>             'aqualuxe_business_hours-1',
>         );
> 
>         $sidebars_widgets['footer-4'] = array(
>             'aqualuxe_social_links-1',
>         );
> 
>         // Shop sidebar
>         $sidebars_widgets['shop-sidebar'] = array(
>             'woocommerce_product_categories-1',
>             'woocommerce_product_tag_cloud-1',
>             'woocommerce_price_filter-1',
>             'woocommerce_rating_filter-1',
>         );
> 
>         update_option( 'sidebars_widgets', $sidebars_widgets );
> 
>         // Create widget instances
>         $widget_instances = array();
> 
>         // Contact info widget
>         $widget_instances['aqualuxe_contact_info'] = array(
>             1 => array(
>                 'title' => __( 'Contact Us', 'aqualuxe' ),
>                 'phone' => get_theme_mod( 'aqualuxe_phone_number', '+1 (555) 123-4567' ),
>                 'email' => get_theme_mod( 'aqualuxe_email_address', 'info@aqualuxe.com' ),
>                 'address' => get_theme_mod( 'aqualuxe_address', '123 Aquarium Lane, Fishville, FW 12345' ),
>             ),
>         );
> 
>         // Navigation menu widget
>         $widget_instances['nav_menu'] = array(
>             1 => array(
>                 'title' => __( 'Quick Links', 'aqualuxe' ),
>                 'nav_menu' => 0, // Will be updated after menu creation
>             ),
>         );
> 
>         // Business hours widget
>         $widget_instances['aqualuxe_business_hours'] = array(
>             1 => array(
>                 'title' => __( 'Business Hours', 'aqualuxe' ),
>                 'mon_fri' => get_theme_mod( 'aqualuxe_mon_fri_hours', '9:00 AM - 6:00 PM' ),
>                 'sat' => get_theme_mod( 'aqualuxe_sat_hours', '10:00 AM - 4:00 PM' ),
>                 'sun' => get_theme_mod( 'aqualuxe_sun_hours', 'Closed' ),
>             ),
>         );
> 
>         // Social links widget
>         $widget_instances['aqualuxe_social_links'] = array(
>             1 => array(
>                 'title' => __( 'Follow Us', 'aqualuxe' ),
>                 'facebook' => get_theme_mod( 'aqualuxe_facebook_url', 'https://facebook.com/aqualuxe' ),
>                 'twitter' => get_theme_mod( 'aqualuxe_twitter_url', 'https://twitter.com/aqualuxe' ),
>                 'instagram' => get_theme_mod( 'aqualuxe_instagram_url', 'https://instagram.com/aqualuxe' ),
>                 'youtube' => get_theme_mod( 'aqualuxe_youtube_url', 'https://youtube.com/aqualuxe' ),
>             ),
>         );
> 
>         // WooCommerce product categories widget
>         $widget_instances['woocommerce_product_categories'] = array(
>             1 => array(
>                 'title' => __( 'Product Categories', 'aqualuxe' ),
>                 'orderby' => 'name',
>                 'dropdown' => 0,
>                 'count' => 1,
>                 'hierarchical' => 1,
>                 'show_children_only' => 0,
>                 'hide_empty' => 0,
>             ),
>         );
> 
>         // WooCommerce product tag cloud widget
>         $widget_instances['woocommerce_product_tag_cloud'] = array(
>             1 => array(
>                 'title' => __( 'Product Tags', 'aqualuxe' ),
>             ),
>         );
> 
>         // WooCommerce price filter widget
>         $widget_instances['woocommerce_price_filter'] = array(
>             1 => array(
>                 'title' => __( 'Filter by Price', 'aqualuxe' ),
>             ),
>         );
> 
>         // WooCommerce rating filter widget
>         $widget_instances['woocommerce_rating_filter'] = array(
>             1 => array(
>                 'title' => __( 'Filter by Rating', 'aqualuxe' ),
>             ),
>         );
> 
>         update_option( 'widget_aqualuxe_contact_info', $widget_instances['aqualuxe_contact_info'] );
>         update_option( 'widget_nav_menu', $widget_instances['nav_menu'] );
>         update_option( 'widget_aqualuxe_business_hours', $widget_instances['aqualuxe_business_hours'] );
>         update_option( 'widget_aqualuxe_social_links', $widget_instances['aqualuxe_social_links'] );
>         update_option( 'widget_woocommerce_product_categories', $widget_instances['woocommerce_product_categories'] );
>         update_option( 'widget_woocommerce_product_tag_cloud', $widget_instances['woocommerce_product_tag_cloud'] );
>         update_option( 'widget_woocommerce_price_filter', $widget_instances['woocommerce_price_filter'] );
>         update_option( 'widget_woocommerce_rating_filter', $widget_instances['woocommerce_rating_filter'] );
>     }
> 
>     /**
>      * Import menus
>      */
>     private function import_menus() {
>         // Create primary menu
>         $primary_menu_id = wp_create_nav_menu( 'Primary Menu' );
> 
>         // Add menu items to primary menu
>         $primary_menu_items = array(
>             array(
>                 'menu-item-title'  => 'Home',
>                 'menu-item-url'    => home_url( '/' ),
>                 'menu-item-status' => 'publish',
>             ),
>             array(
>                 'menu-item-title'  => 'Shop',
>                 'menu-item-url'    => get_permalink( wc_get_page_id( 'shop' ) ),
>                 'menu-item-status' => 'publish',
>             ),
>             array(
>                 'menu-item-title'  => 'About Us',
>                 'menu-item-url'    => get_permalink( $this->get_page_id_by_title( 'About Us' ) ),
>                 'menu-item-status' => 'publish',
>             ),
>             array(
>                 'menu-item-title'  => 'Fish Care Guide',
>                 'menu-item-url'    => get_permalink( $this->get_page_id_by_title( 'Fish Care Guide' ) ),
>                 'menu-item-status' => 'publish',
>             ),
>             array(
>                 'menu-item-title'  => 'Contact Us',
>                 'menu-item-url'    => get_permalink( $this->get_page_id_by_title( 'Contact Us' ) ),
>                 'menu-item-status' => 'publish',
>             ),
>             array(
>                 'menu-item-title'  => 'My Account',
>                 'menu-item-url'    => get_permalink( wc_get_page_id( 'myaccount' ) ),
>                 'menu-item-status' => 'publish',
>             ),
>         );
> 
>         foreach ( $primary_menu_items as $item ) {
>             wp_update_nav_menu_item( $primary_menu_id, 0, $item );
>         }
> 
>         // Create footer menu
>         $footer_menu_id = wp_create_nav_menu( 'Footer Menu' );
> 
>         // Add menu items to footer menu
>         $footer_menu_items = array(
>             array(
>                 'menu-item-title'  => 'Home',
>                 'menu-item-url'    => home_url( '/' ),
>                 'menu-item-status' => 'publish',
>             ),
>             array(
>                 'menu-item-title'  => 'Shop',
>                 'menu-item-url'    => get_permalink( wc_get_page_id( 'shop' ) ),
>                 'menu-item-status' => 'publish',
>             ),
>             array(
>                 'menu-item-title'  => 'About Us',
>                 'menu-item-url'    => get_permalink( $this->get_page_id_by_title( 'About Us' ) ),
>                 'menu-item-status' => 'publish',
>             ),
>             array(
>                 'menu-item-title'  => 'Contact Us',
>                 'menu-item-url'    => get_permalink( $this->get_page_id_by_title( 'Contact Us' ) ),
>                 'menu-item-status' => 'publish',
>             ),
>             array(
>                 'menu-item-title'  => 'Shipping & Returns',
>                 'menu-item-url'    => get_permalink( $this->get_page_id_by_title( 'Shipping & Returns' ) ),
>                 'menu-item-status' => 'publish',
>             ),
>             array(
>                 'menu-item-title'  => 'Privacy Policy',
>                 'menu-item-url'    => get_permalink( $this->get_page_id_by_title( 'Privacy Policy' ) ),
>                 'menu-item-status' => 'publish',
>             ),
>             array(
>                 'menu-item-title'  => 'Terms and Conditions',
>                 'menu-item-url'    => get_permalink( $this->get_page_id_by_title( 'Terms and Conditions' ) ),
>                 'menu-item-status' => 'publish',
>             ),
>         );
> 
>         foreach ( $footer_menu_items as $item ) {
>             wp_update_nav_menu_item( $footer_menu_id, 0, $item );
>         }
> 
>         // Set menu locations
>         $locations = get_theme_mod( 'nav_menu_locations' );
>         $locations['primary'] = $primary_menu_id;
>         $locations['footer'] = $footer_menu_id;
>         set_theme_mod( 'nav_menu_locations', $locations );
> 
>         // Update navigation menu widget with footer menu
>         $widget_instances = get_option( 'widget_nav_menu' );
>         $widget_instances[1]['nav_menu'] = $footer_menu_id;
>         update_option( 'widget_nav_menu', $widget_instances );
>     }
> }
> 
> new AquaLuxe_Demo_Content_Importer();
> ```
> 
> ### inc/seo.php
> 
> ```php
> <?php
> /**
>  * AquaLuxe SEO Functions
>  *
>  * @package AquaLuxe
>  */
> 
> /**
>  * Add meta tags to head
>  */
> function aqualuxe_add_meta_tags() {
>     // Meta description
>     if ( is_single() || is_page() ) {
>         $description = get_the_excerpt();
>         if ( empty( $description ) ) {
>             $description = get_bloginfo( 'description' );
>         }
>     } elseif ( is_home() ) {
>         $description = get_bloginfo( 'description' );
>     } elseif ( is_category() || is_tag() || is_tax() ) {
>         $description = term_description();
>         if ( empty( $description ) ) {
>             $description = get_bloginfo( 'description' );
>         }
>     } else {
>         $description = get_bloginfo( 'description' );
>     }
> 
>     echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
> 
>     // Meta keywords
>     if ( is_single() || is_page() ) {
>         $tags = get_the_tags();
>         if ( $tags ) {
>             $keywords = array();
>             foreach ( $tags as $tag ) {
>                 $keywords[] = $tag->name;
>             }
>             $keywords = implode( ', ', $keywords );
>             echo '<meta name="keywords" content="' . esc_attr( $keywords ) . '">' . "\n";
>         }
>     }
> 
>     // Open Graph tags
>     echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '">' . "\n";
>     echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
>     echo '<meta property="og:type" content="' . ( is_single() ? 'article' : 'website' ) . '">' . "\n";
>     echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '">' . "\n";
>     
>     if ( has_post_thumbnail() ) {
>         $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
>         echo '<meta property="og:image" content="' . esc_url( $image[0] ) . '">' . "\n";
>     } else {
>         echo '<meta property="og:image" content="' . esc_url( get_header_image() ) . '">' . "\n";
>     }
>     
>     echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
>     
>     // Twitter Card tags
>     echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
>     echo '<meta name="twitter:title" content="' . esc_attr( get_the_title() ) . '">' . "\n";
>     echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
>     
>     if ( has_post_thumbnail() ) {
>         $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
>         echo '<meta name="twitter:image" content="' . esc_url( $image[0] ) . '">' . "\n";
>     } else {
>         echo '<meta name="twitter:image" content="' . esc_url( get_header_image() ) . '">' . "\n";
>     }
>     
>     // Site verification (if set in theme options)
>     $google_verification = get_theme_mod( 'aqualuxe_google_verification', '' );
>     if ( ! empty( $google_verification ) ) {
>         echo '<meta name="google-site-verification" content="' . esc_attr( $google_verification ) . '">' . "\n";
>     }
>     
>     $bing_verification = get_theme_mod( 'aqualuxe_bing_verification', '' );
>     if ( ! empty( $bing_verification ) ) {
>         echo '<meta name="msvalidate.01" content="' . esc_attr( $bing_verification ) . '">' . "\n";
>     }
> }
> add_action( 'wp_head', 'aqualuxe_add_meta_tags' );
> 
> /**
>  * Add structured data (JSON-LD) for organization
>  */
> function aqualuxe_add_organization_structured_data() {
>     $logo = get_theme_mod( 'custom_logo' );
>     $logo_url = $logo ? wp_get_attachment_image_src( $logo, 'full' )[0] : '';
>     
>     $structured_data = array(
>         '@context' => 'https://schema.org',
>         '@type' => 'Organization',
>         'name' => get_bloginfo( 'name' ),
>         'url' => get_bloginfo( 'url' ),
>         'logo' => $logo_url,
>         'contactPoint' => array(
>             array(
>                 '@type' => 'ContactPoint',
>                 'telephone' => get_theme_mod( 'aqualuxe_phone_number', '+1 (555) 123-4567' ),
>                 'contactType' => 'customer service',
>             ),
>         ),
>         'address' => array(
>             '@type' => 'PostalAddress',
>             'streetAddress' => '123 Aquarium Lane',
>             'addressLocality' => 'Fishville',
>             'addressRegion' => 'FW',
>             'postalCode' => '12345',
>             'addressCountry' => 'US',
>         ),
>         'sameAs' => array(
>             get_theme_mod( 'aqualuxe_facebook_url', 'https://facebook.com/aqualuxe' ),
>             get_theme_mod( 'aqualuxe_twitter_url', 'https://twitter.com/aqualuxe' ),
>             get_theme_mod( 'aqualuxe_instagram_url', 'https://instagram.com/aqualuxe' ),
>             get_theme_mod( 'aqualuxe_youtube_url', 'https://youtube.com/aqualuxe' ),
>         ),
>     );
>     
>     echo '<script type="application/ld+json">' . json_encode( $structured_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
> }
> add_action( 'wp_head', 'aqualuxe_add_organization_structured_data' );
> 
> /**
>  * Add structured data (JSON-LD) for website
>  */
> function aqualuxe_add_website_structured_data() {
>     $structured_data = array(
>         '@context' => 'https://schema.org',
>         '@type' => 'WebSite',
>         'name' => get_bloginfo( 'name' ),
>         'url' => get_bloginfo( 'url' ),
>         'potentialAction' => array(
>             '@type' => 'SearchAction',
>             'target' => get_bloginfo( 'url' ) . '?s={search_term_string}',
>             'query-input' => 'required name=search_term_string',
>         ),
>     );
>     
>     echo '<script type="application/ld+json">' . json_encode( $structured_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
> }
> add_action( 'wp_head', 'aqualuxe_add_website_structured_data' );
> 
> /**
>  * Add structured data (JSON-LD) for blog posts
>  */
> function aqualuxe_add_blog_post_structured_data() {
>     if ( ! is_single() || get_post_type() !== 'post' ) {
>         return;
>     }
>     
>     $author_id = get_the_author_meta( 'ID' );
>     $author_name = get_the_author_meta( 'display_name' );
>     
>     $structured_data = array(
>         '@context' => 'https://schema.org',
>         '@type' => 'BlogPosting',
>         'headline' => get_the_title(),
>         'image' => array(
>             get_the_post_thumbnail_url( get_the_ID(), 'full' ),
>         ),
>         'author' => array(
>             '@type' => 'Person',
>             'name' => $author_name,
>         ),
>         'publisher' => array(
>             '@type' => 'Organization',
>             'name' => get_bloginfo( 'name' ),
>             'logo' => array(
>                 '@type' => 'ImageObject',
>                 'url' => get_theme_mod( 'custom_logo' ) ? wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0] : '',
>             ),
>         ),
>         'datePublished' => get_the_date( 'c' ),
>         'dateModified' => get_the_modified_date( 'c' ),
>         'mainEntityOfPage' => array(
>             '@type' => 'WebPage',
>             '@id' => get_permalink(),
>         ),
>     );
>     
>     echo '<script type="application/ld+json">' . json_encode( $structured_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
> }
> add_action( 'wp_head', 'aqualuxe_add_blog_post_structured_data' );
> 
> /**
>  * Add structured data (JSON-LD) for products
>  */
> function aqualuxe_add_product_structured_data() {
>     if ( ! function_exists( 'is_product' ) || ! is_product() ) {
>         return;
>     }
>     
>     global $product;
>     
>     $image_id = $product->get_image_id();
>     $image_url = wp_get_attachment_image_src( $image_id, 'full' )[0];
>     
>     $structured_data = array(
>         '@context' => 'https://schema.org',
>         '@type' => 'Product',
>         'name' => $product->get_name(),
>         'image' => $image_url,
>         'description' => $product->get_description(),
>         'sku' => $product->get_sku(),
>         'brand' => array(
>             '@type' => 'Brand',
>             'name' => 'AquaLuxe',
>         ),
>         'offers' => array(
>             '@type' => 'Offer',
>             'priceCurrency' => get_woocommerce_currency(),
>             'price' => $product->get_price(),
>             'availability' => 'https://schema.org/' . ( $product->is_in_stock() ? 'InStock' : 'OutOfStock' ),
>             'seller' => array(
>                 '@type' => 'Organization',
>                 'name' => get_bloginfo( 'name' ),
>             ),
>         ),
>     );
>     
>     // Add rating if available
>     if ( $product->get_average_rating() > 0 ) {
>         $structured_data['aggregateRating'] = array(
>             '@type' => 'AggregateRating',
>             'ratingValue' => $product->get_average_rating(),
>             'reviewCount' => $product->get_rating_count(),
>         );
>     }
>     
>     echo '<script type="application/ld+json">' . json_encode( $structured_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
> }
> add_action( 'wp_head', 'aqualuxe_add_product_structured_data' );
> 
> /**
>  * Add structured data (JSON-LD) for breadcrumbs
>  */
> function aqualuxe_add_breadcrumb_structured_data() {
>     if ( function_exists( 'woocommerce_breadcrumb' ) ) {
>         $breadcrumbs = array();
>         
>         // Home
>         $breadcrumbs[] = array(
>             '@type' => 'ListItem',
>             'position' => 1,
>             'item' => array(
>                 '@id' => home_url(),
>                 'name' => __( 'Home', 'aqualuxe' ),
>             ),
>         );
>         
>         // Shop
>         if ( is_shop() || is_product_category() || is_product_tag() || is_product() ) {
>             $breadcrumbs[] = array(
>                 '@type' => 'ListItem',
>                 'position' => 2,
>                 'item' => array(
>                     '@id' => get_permalink( wc_get_page_id( 'shop' ) ),
>                     'name' => __( 'Shop', 'aqualuxe' ),
>                 ),
>             );
>         }
>         
>         // Product category
>         if ( is_product_category() ) {
>             $term = get_queried_object();
>             $breadcrumbs[] = array(
>                 '@type' => 'ListItem',
>                 'position' => 3,
>                 'item' => array(
>                     '@id' => get_term_link( $term ),
>                     'name' => $term->name,
>                 ),
>             );
>         }
>         
>         // Product tag
>         if ( is_product_tag() ) {
>             $term = get_queried_object();
>             $breadcrumbs[] = array(
>                 '@type' => 'ListItem',
>                 'position' => 3,
>                 'item' => array(
>                     '@id' => get_term_link( $term ),
>                     'name' => $term->name,
>                 ),
>             );
>         }
>         
>         // Product
>         if ( is_product() ) {
>             $product = wc_get_product( get_the_ID() );
>             $position = 3;
>             
>             // Add product categories
>             $terms = get_the_terms( get_the_ID(), 'product_cat' );
>             if ( $terms && ! is_wp_error( $terms ) ) {
>                 $term = array_shift( $terms );
>                 $breadcrumbs[] = array(
>                     '@type' => 'ListItem',
>                     'position' => $position,
>                     'item' => array(
>                         '@id' => get_term_link( $term ),
>                         'name' => $term->name,
>                     ),
>                 );
>                 $position++;
>             }
>             
>             // Add product
>             $breadcrumbs[] = array(
>                 '@type' => 'ListItem',
>                 'position' => $position,
>                 'item' => array(
>                     '@id' => get_permalink(),
>                     'name' => $product->get_name(),
>                 ),
>             );
>         }
>         
>         $structured_data = array(
>             '@context' => 'https://schema.org',
>             '@type' => 'BreadcrumbList',
>             'itemListElement' => $breadcrumbs,
>         );
>         
>         echo '<script type="application/ld+json">' . json_encode( $structured_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
>     }
> }
> add_action( 'wp_head', 'aqualuxe_add_breadcrumb_structured_data' );
> 
> /**
>  * Add rel="prev" and rel="next" links to paginated archives
>  */
> function aqualuxe_add_rel_next_prev() {
>     global $paged;
>     
>     if ( get_previous_posts_link() ) {
>         echo '<link rel="prev" href="' . get_previous_posts_page_link() . '" />' . "\n";
>     }
>     
>     if ( get_next_posts_link() ) {
>         echo '<link rel="next" href="' . get_next_posts_page_link() . '" />' . "\n";
>     }
> }
> add_action( 'wp_head', 'aqualuxe_add_rel_next_prev' );
> 
> /**
>  * Add canonical URL
>  */
> function aqualuxe_add_canonical_url() {
>     if ( is_singular() ) {
>         $canonical = get_permalink();
>     } elseif ( is_home() && get_option( 'show_on_front' ) == 'page' ) {
>         $canonical = get_permalink( get_option( 'page_for_posts' ) );
>     } elseif ( is_category() || is_tag() || is_tax() ) {
>         $term = get_queried_object();
>         $canonical = get_term_link( $term );
>     } elseif ( is_search() ) {
>         $canonical = get_search_link();
>     } else {
>         $canonical = home_url( '/' );
>     }
>     
>     echo '<link rel="canonical" href="' . esc_url( $canonical ) . '" />' . "\n";
> }
> add_action( 'wp_head', 'aqualuxe_add_canonical_url' );
> 
> /**
>  * Add SEO options to Customizer
>  */
> function aqualuxe_seo_customizer_options( $wp_customize ) {
>     // SEO section
>     $wp_customize->add_section( 'aqualuxe_seo', array(
>         'title'    => __( 'SEO Options', 'aqualuxe' ),
>         'priority' => 170,
>     ) );
>     
>     // Google verification code
>     $wp_customize->add_setting( 'aqualuxe_google_verification', array(
>         'default'           => '',
>         'sanitize_callback' => 'sanitize_text_field',
>         'transport'         => 'postMessage',
>     ) );
>     
>     $wp_customize->add_control( 'aqualuxe_google_verification', array(
>         'label'    => __( 'Google Site Verification Code', 'aqualuxe' ),
>         'section'  => 'aqualuxe_seo',
>         'settings' => 'aqualuxe_google_verification',
>         'type'     => 'text',
>     ) );
>     
>     // Bing verification code
>     $wp_customize->add_setting( 'aqualuxe_bing_verification', array(
>         'default'           => '',
>         'sanitize_callback' => 'sanitize_text_field',
>         'transport'         => 'postMessage',
>     ) );
>     
>     $wp_customize->add_control( 'aqualuxe_bing_verification', array(
>         'label'    => __( 'Bing Site Verification Code', 'aqualuxe' ),
>         'section'  => 'aqualuxe_seo',
>         'settings' => 'aqualuxe_bing_verification',
>         'type'     => 'text',
>     ) );
>     
>     // Meta description for home page
>     $wp_customize->add_setting( 'aqualuxe_home_meta_description', array(
>         'default'           => get_bloginfo( 'description' ),
>         'sanitize_callback' => 'sanitize_textarea_field',
>         'transport'         => 'postMessage',
>     ) );
>     
>     $wp_customize->add_control( 'aqualuxe_home_meta_description', array(
>         'label'    => __( 'Home Page Meta Description', 'aqualuxe' ),
>         'section'  => 'aqualuxe_seo',
>         'settings' => 'aqualuxe_home_meta_description',
>         'type'     => 'textarea',
>     ) );
>     
>     // Meta keywords for home page
>     $wp_customize->add_setting( 'aqualuxe_home_meta_keywords', array(
>         'default'           => '',
>         'sanitize_callback' => 'sanitize_text_field',
>         'transport'         => 'postMessage',
>     ) );
>     
>     $wp_customize->add_control( 'aqualuxe_home_meta_keywords', array(
>         'label'    => __( 'Home Page Meta Keywords', 'aqualuxe' ),
>         'section'  => 'aqualuxe_seo',
>         'settings' => 'aqualuxe_home_meta_keywords',
>         'type'     => 'text',
>     ) );
>     
>     // Enable/disable structured data
>     $wp_customize->add_setting( 'aqualuxe_enable_structured_data', array(
>         'default'           => '1',
>         'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
>         'transport'         => 'postMessage',
>     ) );
>     
>     $wp_customize->add_control( 'aqualuxe_enable_structured_data', array(
>         'label'    => __( 'Enable Structured Data (JSON-LD)', 'aqualuxe' ),
>         'section'  => 'aqualuxe_seo',
>         'settings' => 'aqualuxe_enable_structured_data',
>         'type'     => 'checkbox',
>     ) );
>     
>     // Enable/disable Open Graph tags
>     $wp_customize->add_setting( 'aqualuxe_enable_open_graph', array(
>         'default'           => '1',
>         'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
>         'transport'         => 'postMessage',
>     ) );
>     
>     $wp_customize->add_control( 'aqualuxe_enable_open_graph', array(
>         'label'    => __( 'Enable Open Graph Tags', 'aqualuxe' ),
>         'section'  => 'aqualuxe_seo',
>         'settings' => 'aqualuxe_enable_open_graph',
>         'type'     => 'checkbox',
>     ) );
>     
>     // Enable/disable Twitter Card tags
>     $wp_customize->add_setting( 'aqualuxe_enable_twitter_card', array(
>         'default'           => '1',
>         'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
>         'transport'         => 'postMessage',
>     ) );
>     
>     $wp_customize->add_control( 'aqualuxe_enable_twitter_card', array(
>         'label'    => __( 'Enable Twitter Card Tags', 'aqualuxe' ),
>         'section'  => 'aqualuxe_seo',
>         'settings' => 'aqualuxe_enable_twitter_card',
>         'type'     => 'checkbox',
>     ) );
> }
> add_action( 'customize_register', 'aqualuxe_seo_customizer_options' );
> 
> /**
>  * Sanitize checkbox
>  */
> function aqualuxe_sanitize_checkbox( $checked ) {
>     return ( ( isset( $checked ) && true == $checked ) ? true : false );
> }
> 
> /**
>  * Modify title for SEO
>  */
> function aqualuxe_modify_title_for_seo( $title ) {
>     if ( is_front_page() ) {
>         $title['title'] = get_bloginfo( 'name' ) . ' | ' . get_bloginfo( 'description' );
>     } elseif ( is_home() ) {
>         $title['title'] = single_post_title( '', false ) . ' | ' . get_bloginfo( 'name' );
>     } elseif ( is_category() || is_tag() || is_tax() ) {
>         $title['title'] = single_term_title( '', false ) . ' | ' . get_bloginfo( 'name' );
>     } elseif ( is_search() ) {
>         $title['title'] = sprintf( __( 'Search Results for "%s"', 'aqualuxe' ), get_search_query() ) . ' | ' . get_bloginfo( 'name' );
>     } elseif ( is_404() ) {
>         $title['title'] = __( 'Page Not Found', 'aqualuxe' ) . ' | ' . get_bloginfo( 'name' );
>     } else {
>         $title['title'] = single_post_title( '', false ) . ' | ' . get_bloginfo( 'name' );
>     }
>     
>     return $title;
> }
> add_filter( 'document_title_parts', 'aqualuxe_modify_title_for_seo' );
> 
> /**
>  * Add schema markup to navigation menu
>  */
> function aqualuxe_add_schema_to_nav_menu( $atts, $item, $args ) {
>     $atts['itemprop'] = 'url';
>     return $atts;
> }
> add_filter( 'nav_menu_link_attributes', 'aqualuxe_add_schema_to_nav_menu', 10, 3 );
> 
> /**
>  * Add schema markup to site logo
>  */
> function aqualuxe_add_schema_to_site_logo( $html, $logo_id ) {
>     $logo_url = wp_get_attachment_image_src( $logo_id, 'full' )[0];
>     $html = str_replace( '<img', '<img itemprop="logo"', $html );
>     return $html;
> }
> add_filter( 'get_custom_logo', 'aqualuxe_add_schema_to_site_logo', 10, 2 );
> 
> /**
>  * Add schema markup to post author
>  */
> function aqualuxe_add_schema_to_post_author( $link ) {
>     $link = str_replace( 'rel="author"', 'rel="author" itemprop="author"', $link );
>     return $link;
> }
> add_filter( 'the_author_posts_link', 'aqualuxe_add_schema_to_post_author' );
> 
> /**
>  * Add schema markup to post date
>  */
> function aqualuxe_add_schema_to_post_date( $the_date ) {
>     return '<time itemprop="datePublished" datetime="' . get_the_date( 'c' ) . '">' . $the_date . '</time>';
> }
> add_filter( 'the_date', 'aqualuxe_add_schema_to_post_date' );
> 
> /**
>  * Add schema markup to post content
>  */
> function aqualuxe_add_schema_to_post_content( $content ) {
>     if ( is_singular() && in_array( get_post_type(), array( 'post', 'page' ) ) ) {
>         $content = '<div itemprop="articleBody">' . $content . '</div>';
>     }
>     return $content;
> }
> add_filter( 'the_content', 'aqualuxe_add_schema_to_post_content' );
> 
> /**
>  * Add schema markup to post thumbnail
>  */
> function aqualuxe_add_schema_to_post_thumbnail( $html, $post_id, $post_image_id ) {
>     $html = str_replace( '<img', '<img itemprop="image"', $html );
>     return $html;
> }
> add_filter( 'post_thumbnail_html', 'aqualuxe_add_schema_to_post_thumbnail', 10, 3 );
> 
> /**
>  * Add schema markup to comments
>  */
> function aqualuxe_add_schema_to_comments( $open, $post_id ) {
>     if ( $open ) {
>         echo '<div itemprop="comment" itemscope itemtype="https://schema.org/Comment">';
>     } else {
>         echo '</div>';
>     }
>     return $open;
> }
> add_filter( 'comments_open', 'aqualuxe_add_schema_to_comments', 10, 2 );
> 
> /**
>  * Add schema markup to comment author
>  */
> function aqualuxe_add_schema_to_comment_author( $return, $author, $comment_ID ) {
>     $return = str_replace( '<span class="fn">', '<span itemprop="author" itemscope itemtype="https://schema.org/Person"><span itemprop="name">', $return );
>     $return = str_replace( '</span>', '</span></span>', $return );
>     return $return;
> }
> add_filter( 'get_comment_author_link', 'aqualuxe_add_schema_to_comment_author', 10, 3 );
> 
> /**
>  * Add schema markup to comment date
>  */
> function aqualuxe_add_schema_to_comment_date( $get_comment_date, $d, $comment ) {
>     return '<time itemprop="datePublished" datetime="' . get_comment_date( 'c', $comment ) . '">' . $get_comment_date . '</time>';
> }
> add_filter( 'get_comment_date', 'aqualuxe_add_schema_to_comment_date', 10, 3 );
> 
> /**
>  * Add schema markup to comment content
>  */
> function aqualuxe_add_schema_to_comment_content( $comment_text, $comment ) {
>     return '<div itemprop="text">' . $comment_text . '</div>';
> }
> add_filter( 'comment_text', 'aqualuxe_add_schema_to_comment_content', 10, 2 );
> 
> /**
>  * Add noindex to search results
>  */
> function aqualuxe_add_noindex_to_search_results() {
>     if ( is_search() ) {
>         echo '<meta name="robots" content="noindex, follow">' . "\n";
>     }
> }
> add_action( 'wp_head', 'aqualuxe_add_noindex_to_search_results' );
> 
> /**
>  * Add noindex to archive pages
>  */
> function aqualuxe_add_noindex_to_archive_pages() {
>     if ( is_archive() && ! is_post_type_archive( 'product' ) && ! is_tax( 'product_cat' ) && ! is_tax( 'product_tag' ) ) {
>         echo '<meta name="robots" content="noindex, follow">' . "\n";
>     }
> }
> add_action( 'wp_head', 'aqualuxe_add_noindex_to_archive_pages' );
> 
> /**
>  * Add noindex to paginated pages
>  */
> function aqualuxe_add_noindex_to_paginated_pages() {
>     if ( is_paged() ) {
>         echo '<meta name="robots" content="noindex, follow">' . "\n";
>     }
> }
> add_action( 'wp_head', 'aqualuxe_add_noindex_to_paginated_pages' );
> 
> /**
>  * Add noindex to 404 page
>  */
> function aqualuxe_add_noindex_to_404_page() {
>     if ( is_404() ) {
>         echo '<meta name="robots" content="noindex, follow">' . "\n";
>     }
> }
> add_action( 'wp_head', 'aqualuxe_add_noindex_to_404_page' );
> 
> /**
>  * Add noindex to attachment pages
>  */
> function aqualuxe_add_noindex_to_attachment_pages() {
>     if ( is_attachment() ) {
>         echo '<meta name="robots" content="noindex, follow">' . "\n";
>     }
> }
> add_action( 'wp_head', 'aqualuxe_add_noindex_to_attachment_pages' );
> 
> /**
>  * Add schema markup to WooCommerce products
>  */
> function aqualuxe_add_schema_to_woocommerce_products() {
>     if ( ! function_exists( 'is_product' ) || ! is_product() ) {
>         return;
>     }
>     
>     global $product;
>     
>     $schema = array(
>         '@context' => 'https://schema.org',
>         '@type' => 'Product',
>         'name' => $product->get_name(),
>         'image' => wp_get_attachment_image_src( $product->get_image_id(), 'full' )[0],
>         'description' => $product->get_description(),
>         'sku' => $product->get_sku(),
>         'brand' => array(
>             '@type' => 'Brand',
>             'name' => 'AquaLuxe',
>         ),
>         'offers' => array(
>             '@type' => 'Offer',
>             'priceCurrency' => get_woocommerce_currency(),
>             'price' => $product->get_price(),
>             'availability' => 'https://schema.org/' . ( $product->is_in_stock() ? 'InStock' : 'OutOfStock' ),
>             'seller' => array(
>                 '@type' => 'Organization',
>                 'name' => get_bloginfo( 'name' ),
>             ),
>         ),
>     );
>     
>     if ( $product->get_average_rating() > 0 ) {
>         $schema['aggregateRating'] = array(
>             '@type' => 'AggregateRating',
>             'ratingValue' => $product->get_average_rating(),
>             'reviewCount' => $product->get_rating_count(),
>         );
>     }
>     
>     echo '<script type="application/ld+json">' . json_encode( $schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
> }
> add_action( 'wp_head', 'aqualuxe_add_schema_to_woocommerce_products' );
> 
> /**
>  * Add schema markup to WooCommerce shop page
>  */
> function aqualuxe_add_schema_to_woocommerce_shop() {
>     if ( ! function_exists( 'is_shop' ) || ! is_shop() ) {
>         return;
>     }
>     
>     $schema = array(
>         '@context' => 'https://schema.org',
>         '@type' => 'Store',
>         'name' => get_bloginfo( 'name' ),
>         'image' => get_header_image(),
>         'description' => get_bloginfo( 'description' ),
>         'url' => get_permalink( wc_get_page_id( 'shop' ) ),
>         'telephone' => get_theme_mod( 'aqualuxe_phone_number', '+1 (555) 123-4567' ),
>         'address' => array(
>             '@type' => 'PostalAddress',
>             'streetAddress' => '123 Aquarium Lane',
>             'addressLocality' => 'Fishville',
>             'addressRegion' => 'FW',
>             'postalCode' => '12345',
>             'addressCountry' => 'US',
>         ),
>         'openingHours' => array(
>             'Mo-Fr ' . get_theme_mod( 'aqualuxe_mon_fri_hours', '9:00 AM - 6:00 PM' ),
>             'Sa ' . get_theme_mod( 'aqualuxe_sat_hours', '10:00 AM - 4:00 PM' ),
>         ),
>     );
>     
>     echo '<script type="application/ld+json">' . json_encode( $schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
> }
> add_action( 'wp_head', 'aqualuxe_add_schema_to_woocommerce_shop' );
> 
> /**
>  * Add schema markup to WooCommerce product categories
>  */
> function aqualuxe_add_schema_to_woocommerce_product_categories() {
>     if ( ! function_exists( 'is_product_category' ) || ! is_product_category() ) {
>         return;
>     }
>     
>     $term = get_queried_object();
>     
>     $schema = array(
>         '@context' => 'https://schema.org',
>         '@type' => 'CollectionPage',
>         'name' => $term->name,
>         'description' => term_description( $term->term_id ),
>         'url' => get_term_link( $term ),
>     );
>     
>     echo '<script type="application/ld+json">' . json_encode( $schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
> }
> add_action( 'wp_head', 'aqualuxe_add_schema_to_woocommerce_product_categories' );
> 
> /**
>  * Add schema markup to WooCommerce cart page
>  */
> function aqualuxe_add_schema_to_woocommerce_cart() {
>     if ( ! function_exists( 'is_cart' ) || ! is_cart() ) {
>         return;
>     }
>     
>     $schema = array(
>         '@context' => 'https://schema.org',
>         '@type' => 'WebPage',
>         'name' => __( 'Shopping Cart', 'aqualuxe' ),
>         'description' => __( 'Your shopping cart', 'aqualuxe' ),
>         'url' => get_permalink( wc_get_page_id( 'cart' ) ),
>     );
>     
>     echo '<script type="application/ld+json">' . json_encode( $schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
> }
> add_action( 'wp_head', 'aqualuxe_add_schema_to_woocommerce_cart' );
> 
> /**
>  * Add schema markup to WooCommerce checkout page
>  */
> function aqualuxe_add_schema_to_woocommerce_checkout() {
>     if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
>         return;
>     }
>     
>     $schema = array(
>         '@context' => 'https://schema.org',
>         '@type' => 'WebPage',
>         'name' => __( 'Checkout', 'aqualuxe' ),
>         'description' => __( 'Complete your purchase', 'aqualuxe' ),
>         'url' => get_permalink( wc_get_page_id( 'checkout' ) ),
>     );
>     
>     echo '<script type="application/ld+json">' . json_encode( $schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
> }
> add_action( 'wp_head', 'aqualuxe_add_schema_to_woocommerce_checkout' );
> 
> /**
>  * Add schema markup to WooCommerce my account page
>  */
> function aqualuxe_add_schema_to_woocommerce_my_account() {
>     if ( ! function_exists( 'is_account_page' ) || ! is_account_page() ) {
>         return;
>     }
>     
>     $schema = array(
>         '@context' => 'https://schema.org',
>         '@type' => 'WebPage',
>         'name' => __( 'My Account', 'aqualuxe' ),
>         'description' => __( 'Manage your account', 'aqualuxe' ),
>         'url' => get_permalink( wc_get_page_id( 'myaccount' ) ),
>     );
>     
>     echo '<script type="application/ld+json">' . json_encode( $schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
> }
> add_action( 'wp_head', 'aqualuxe_add_schema_to_woocommerce_my_account' );
> ```
> 
> ### inc/security.php
> 
> ```php
> <?php
> /**
>  * AquaLuxe Security Functions
>  *
>  * @package AquaLuxe
>  */
> 
> /**
>  * Remove WordPress version from head and feeds
>  */
> function aqualuxe_remove_version() {
>     return '';
> }
> add_filter( 'the_generator', 'aqualuxe_remove_version' );
> 
> /**
>  * Remove WordPress version from scripts and styles
>  */
> function aqualuxe_remove_version_from_scripts( $src ) {
>     if ( strpos( $src, 'ver=' ) ) {
>         $src = remove_query_arg( 'ver', $src );
>     }
>     return $src;
> }
> add_filter( 'style_loader_src', 'aqualuxe_remove_version_from_scripts', 9999 );
> add_filter( 'script_loader_src', 'aqualuxe_remove_version_from_scripts', 9999 );
> 
> /**
>  * Disable XML-RPC
>  */
> add_filter( 'xmlrpc_enabled', '__return_false' );
> 
> /**
>  * Remove X-Pingback header
>  */
> function aqualuxe_remove_x_pingback_header( $headers ) {
>     unset( $headers['X-Pingback'] );
>     return $headers;
> }
> add_filter( 'wp_headers', 'aqualuxe_remove_x_pingback_header' );
> 
> /**
>  * Disable login hints
>  */
> function aqualuxe_no_login_errors() {
>     return __( 'Something is wrong!', 'aqualuxe' );
> }
> add_filter( 'login_errors', 'aqualuxe_no_login_errors' );
> 
> /**
>  * Disable file editing in dashboard
>  */
> define( 'DISALLOW_FILE_EDIT', true );
> 
> /**
>  * Disable unfiltered HTML
>  */
> define( 'DISALLOW_UNFILTERED_HTML', true );
> 
> /**
>  * Force SSL for admin
>  */
> define( 'FORCE_SSL_ADMIN', true );
> 
> /**
>  * Sanitize uploaded filenames
>  */
> function aqualuxe_sanitize_filename( $filename ) {
>     $info = pathinfo( $filename );
>     $extension = empty( $info['extension'] ) ? '' : '.' . $info['extension'];
>     $name = basename( $filename, $extension );
>     
>     // Remove special characters
>     $name = preg_replace( '/[^a-zA-Z0-9-_]/', '', $name );
>     
>     // Replace spaces with hyphens
>     $name = str_replace( ' ', '-', $name );
>     
>     // Convert to lowercase
>     $name = strtolower( $name );
>     
>     // Limit length
>     $name = substr( $name, 0, 50 );
>     
>     return $name . $extension;
> }
> add_filter( 'sanitize_file_name', 'aqualuxe_sanitize_filename', 10 );
> 
> /**
>  * Disable REST API for non-logged-in users
>  */
> function aqualuxe_disable_rest_api( $access ) {
>     if ( ! is_user_logged_in() ) {
>         return new WP_Error(
>             'rest_not_logged_in',
>             __( 'You are not currently logged in.', 'aqualuxe' ),
>             array( 'status' => 401 )
>         );
>     }
>     return $access;
> }
> add_filter( 'rest_authentication_errors', 'aqualuxe_disable_rest_api' );
> 
> /**
>  * Remove WordPress meta tags
>  */
> function aqualuxe_remove_wp_meta_tags() {
>     remove_action( 'wp_head', 'wp_generator' );
>     remove_action( 'wp_head', 'wlwmanifest_link' );
>     remove_action( 'wp_head', 'rsd_link' );
>     remove_action( 'wp_head', 'wp_shortlink_wp_head' );
>     remove_action( 'wp_head', 'feed_links', 2 );
>     remove_action( 'wp_head', 'feed_links_extra', 3 );
> }
> add_action( 'init', 'aqualuxe_remove_wp_meta_tags' );
> 
> /**
>  * Change login URL
>  */
> function aqualuxe_change_login_url( $url, $path, $orig_scheme ) {
>     $old = array( "/(wp-login\.php)/" );
>     $new = array( "/login/" );
>     return preg_replace( $old, $new, $url, 1 );
> }
> add_filter( 'site_url', 'aqualuxe_change_login_url', 10, 3 );
> 
> /**
>  * Change login redirect URL
>  */
> function aqualuxe_change_login_redirect( $redirect_to, $request, $user ) {
>     if ( isset( $user->roles ) && is_array( $user->roles ) ) {
>         if ( in_array( 'administrator', $user->roles ) ) {
>             return admin_url();
>         } else {
>             return home_url();
>         }
>     } else {
>         return $redirect_to;
>     }
> }
> add_filter( 'login_redirect', 'aqualuxe_change_login_redirect', 10, 3 );
> 
> /**
>  * Block bad queries
>  */
> function aqualuxe_block_bad_queries() {
>     if ( strlen( $_SERVER['REQUEST_URI'] ) > 255 ||
>         strpos( $_SERVER['REQUEST_URI'], "eval(" ) ||
>         strpos( $_SERVER['REQUEST_URI'], "CONCAT" ) ||
>         strpos( $_SERVER['REQUEST_URI'], "UNION+SELECT" ) ||
>         strpos( $_SERVER['REQUEST_URI'], "base64" ) ) {
>         @header( "HTTP/1.1 414 Request-URI Too Long" );
>         @header( "Status: 414 Request-URI Too Long" );
>         @header( "Connection: Close" );
>         @exit;
>     }
> }
> add_action( 'init', 'aqualuxe_block_bad_queries' );
> 
> /**
>  * Remove emoji scripts
>  */
> function aqualuxe_remove_emoji_scripts() {
>     remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
>     remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
>     remove_action( 'wp_print_styles', 'print_emoji_styles' );
>     remove_action( 'admin_print_styles', 'print_emoji_styles' );
>     remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
>     remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
>     remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
>     add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
> }
> add_action( 'init', 'aqualuxe_remove_emoji_scripts' );
> 
> /**
>  * Disable emojis in TinyMCE
>  */
> function disable_emojis_tinymce( $plugins ) {
>     if ( is_array( $plugins ) ) {
>         return array_diff( $plugins, array( 'wpemoji' ) );
>     } else {
>         return array();
>     }
> }
> 
> /**
>  * Disable self pingbacks
>  */
> function aqualuxe_disable_self_pingbacks( &$links ) {
>     $home = get_option( 'home' );
>     foreach ( $links as $l => $link ) {
>         if ( 0 === strpos( $link, $home ) ) {
>             unset( $links[$l] );
>         }
>     }
> }
> add_action( 'pre_ping', 'aqualuxe_disable_self_pingbacks' );
> 
> /**
>  * Remove comment author website link
>  */
> function aqualuxe_remove_comment_author_link( $return, $author, $comment_ID ) {
>     return $author;
> }
> add_filter( 'get_comment_author_link', 'aqualuxe_remove_comment_author_link', 10, 3 );
> 
> /**
>  * Disable RSS feeds
>  */
> function aqualuxe_disable_feed() {
>     wp_die( __( 'No feed available, please visit the <a href="' . esc_url( home_url( '/' ) ) . '">homepage</a>!', 'aqualuxe' ) );
> }
> add_action( 'do_feed', 'aqualuxe_disable_feed', 1 );
> add_action( 'do_feed_rdf', 'aqualuxe_disable_feed', 1 );
> add_action( 'do_feed_rss', 'aqualuxe_disable_feed', 1 );
> add_action( 'do_feed_rss2', 'aqualuxe_disable_feed', 1 );
> add_action( 'do_feed_atom', 'aqualuxe_disable_feed', 1 );
> 
> /**
>  * Remove RSS feed links
>  */
> function aqualuxe_remove_feed_links() {
>     remove_action( 'wp_head', 'feed_links_extra', 3 );
>     remove_action( 'wp_head', 'feed_links', 2 );
>     remove_action( 'wp_head', 'rsd_link' );
> }
> add_action( 'init', 'aqualuxe_remove_feed_links' );
> 
> /**
>  * Add security headers
>  */
> function aqualuxe_add_security_headers() {
>     header( 'X-Content-Type-Options: nosniff' );
>     header( 'X-Frame-Options: SAMEORIGIN' );
>     header( 'X-XSS-Protection: 1; mode=block' );
>     if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) {
>         header( 'Strict-Transport-Security: max-age=31536000; includeSubDomains' );
>     }
> }
> add_action( 'send_headers', 'aqualuxe_add_security_headers' );
> 
> /**
>  * Sanitize output
>  */
> function aqualuxe_sanitize_output( $buffer ) {
>     $search = array(
>         '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
>         '/[^\S ]+\</s',     // strip whitespaces before tags, except space
>         '/(\s)+/s',         // shorten multiple whitespace sequences
>         '/<!--(.|\s)*?-->/' // Remove HTML comments
>     );
>     $replace = array(
>         '>',
>         '<',
>         '\\1',
>         ''
>     );
>     $buffer = preg_replace( $search, $replace, $buffer );
>     return $buffer;
> }
> add_action( 'wp_loaded', 'aqualuxe_sanitize_output' );
> 
> /**
>  * Disable user enumeration
>  */
> function aqualuxe_disable_user_enumeration() {
>     if ( isset( $_SERVER['REQUEST_URI'] ) && preg_match( '/author=\d+/', $_SERVER['REQUEST_URI'] ) ) {
>         wp_redirect( home_url() );
>         exit;
>     }
> }
> add_action( 'init', 'aqualuxe_disable_user_enumeration' );
> 
> /**
>  * Remove WordPress version from RSS feed
>  */
> function aqualuxe_remove_wp_version_rss() {
>     return '';
> }
> add_filter( 'the_generator', 'aqualuxe_remove_wp_version_rss' );
> 
> /**
>  * Remove API version from head
>  */
> function aqualuxe_remove_api_version_head() {
>     remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
>     remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
>     remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );
> }
> add_action( 'after_setup_theme', 'aqualuxe_remove_api_version_head' );
> 
> /**
>  * Disable file editor
>  */
> function aqualuxe_disable_file_editor() {
>     define( 'DISALLOW_FILE_EDIT', true );
> }
> add_action( 'init', 'aqualuxe_disable_file_editor' );
> 
> /**
>  * Disable unfiltered HTML for all users
>  */
> function aqualuxe_disable_unfiltered_html() {
>     define( 'DISALLOW_UNFILTERED_HTML', true );
> }
> add_action( 'init', 'aqualuxe_disable_unfiltered_html' );
> 
> /**
>  * Sanitize all input data
>  */
> function aqualuxe_sanitize_all_input() {
>     $_GET   = array_map( 'sanitize_text_field', $_GET );
>     $_POST  = array_map( 'sanitize_text_field', $_POST );
>     $_REQUEST = array_map( 'sanitize_text_field', $_REQUEST );
>     $_COOKIE = array_map( 'sanitize_text_field', $_COOKIE );
> }
> add_action( 'init', 'aqualuxe_sanitize_all_input' );
> 
> /**
>  * Sanitize excerpt
>  */
> function aqualuxe_sanitize_excerpt( $excerpt ) {
>     $raw_excerpt = $excerpt;
>     if ( '' == $excerpt ) {
>         $excerpt = get_the_content( '' );
>         $excerpt = strip_shortcodes( $excerpt );
>         $excerpt = apply_filters( 'the_content', $excerpt );
>         $excerpt = str_replace( ']]>', ']]&gt;', $excerpt );
>         $excerpt = wp_kses_post( $excerpt );
>         $excerpt_length = apply_filters( 'excerpt_length', 55 );
>         $excerpt_more = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
>         $excerpt = wp_trim_words( $excerpt, $excerpt_length, $excerpt_more );
>     }
>     return apply_filters( 'wp_trim_excerpt', wp_kses_post( $excerpt ), $raw_excerpt );
> }
> add_filter( 'get_the_excerpt', 'aqualuxe_sanitize_excerpt' );
> 
> /**
>  * Sanitize title
>  */
> function aqualuxe_sanitize_title( $title, $raw_title ) {
>     $title = strip_tags( $raw_title );
>     $title = preg_replace( '/\s+/', ' ', $title );
>     $title = trim( $title );
>     return $title;
> }
> add_filter( 'sanitize_title', 'aqualuxe_sanitize_title', 10, 2 );
> 
> /**
>  * Sanitize category name
>  */
> function aqualuxe_sanitize_category_name( $cat_name ) {
>     return sanitize_text_field( $cat_name );
> }
> add_filter( 'sanitize_category_name', 'aqualuxe_sanitize_category_name' );
> 
> /**
>  * Sanitize tag name
>  */
> function aqualuxe_sanitize_tag_name( $tag_name ) {
>     return sanitize_text_field( $tag_name );
> }
> add_filter( 'sanitize_tag_name', 'aqualuxe_sanitize_tag_name' );
> 
> /**
>  * Sanitize comment author
>  */
> function aqualuxe_sanitize_comment_author( $author ) {
>     return sanitize_text_field( $author );
> }
> add_filter( 'pre_comment_author_name', 'aqualuxe_sanitize_comment_author' );
> 
> /**
>  * Sanitize comment email
>  */
> function aqualuxe_sanitize_comment_email( $email ) {
>     return sanitize_email( $email );
> }
> add_filter( 'pre_comment_author_email', 'aqualuxe_sanitize_comment_email' );
> 
> /**
>  * Sanitize comment URL
>  */
> function aqualuxe_sanitize_comment_url( $url ) {
>     return esc_url_raw( $url );
> }
> add_filter( 'pre_comment_author_url', 'aqualuxe_sanitize_comment_url' );
> 
> /**
>  * Sanitize comment content
>  */
> function aqualuxe_sanitize_comment_content( $comment_content ) {
>     return wp_kses_post( $comment_content );
> }
> add_filter( 'pre_comment_content', 'aqualuxe_sanitize_comment_content' );
> 
> /**
>  * Sanitize user login
>  */
> function aqualuxe_sanitize_user_login( $username ) {
>     return sanitize_user( $username );
> }
> add_filter( 'pre_user_login', 'aqualuxe_sanitize_user_login' );
> 
> /**
>  * Sanitize user email
>  */
> function aqualuxe_sanitize_user_email( $email ) {
>     return sanitize_email( $email );
> }
> add_filter( 'pre_user_email', 'aqualuxe_sanitize_user_email' );
> 
> /**
>  * Sanitize user URL
>  */
> function aqualuxe_sanitize_user_url( $url ) {
>     return esc_url_raw( $url );
> }
> add_filter( 'pre_user_url', 'aqualuxe_sanitize_user_url' );
> 
> /**
>  * Sanitize user display name
>  */
> function aqualuxe_sanitize_user_display_name( $display_name ) {
>     return sanitize_text_field( $display_name );
> }
> add_filter( 'pre_user_display_name', 'aqualuxe_sanitize_user_display_name' );
> 
> /**
>  * Sanitize user first name
>  */
> function aqualuxe_sanitize_user_first_name( $first_name ) {
>     return sanitize_text_field( $first_name );
> }
> add_filter( 'pre_user_first_name', 'aqualuxe_sanitize_user_first_name' );
> 
> /**
>  * Sanitize user last name
>  */
> function aqualuxe_sanitize_user_last_name( $last_name ) {
>     return sanitize_text_field( $last_name );
> }
> add_filter( 'pre_user_last_name', 'aqualuxe_sanitize_user_last_name' );
> 
> /**
>  * Sanitize user nickname
>  */
> function aqualuxe_sanitize_user_nickname( $nickname ) {
>     return sanitize_text_field( $nickname );
> }
> add_filter( 'pre_user_nickname', 'aqualuxe_sanitize_user_nickname' );
> 
> /**
>  * Sanitize user description
>  */
> function aqualuxe_sanitize_user_description( $description ) {
>     return sanitize_textarea_field( $description );
> }
> add_filter( 'pre_user_description', 'aqualuxe_sanitize_user_description' );
> 
> /**
>  * Sanitize post title
>  */
> function aqualuxe_sanitize_post_title( $post_title, $post ) {
>     return sanitize_text_field( $post_title );
> }
> add_filter( 'title_save_pre', 'aqualuxe_sanitize_post_title', 10, 2 );
> 
> /**
>  * Sanitize post content
>  */
> function aqualuxe_sanitize_post_content( $content, $post ) {
>     return wp_kses_post( $content );
> }
> add_filter( 'content_save_pre', 'aqualuxe_sanitize_post_content', 10, 2 );
> 
> /**
>  * Sanitize post excerpt
>  */
> function aqualuxe_sanitize_post_excerpt( $excerpt, $post ) {
>     return sanitize_textarea_field( $excerpt );
> }
> add_filter( 'excerpt_save_pre', 'aqualuxe_sanitize_post_excerpt', 10, 2 );
> 
> /**
>  * Sanitize post status
>  */
> function aqualuxe_sanitize_post_status( $post_status, $post ) {
>     return sanitize_key( $post_status );
> }
> add_filter( 'status_save_pre', 'aqualuxe_sanitize_post_status', 10, 2 );
> 
> /**
>  * Sanitize post type
>  */
> function aqualuxe_sanitize_post_type( $post_type, $post ) {
>     return sanitize_key( $post_type );
> }
> add_filter( 'post_type_save_pre', 'aqualuxe_sanitize_post_type', 10, 2 );
> 
> /**
>  * Sanitize post password
>  */
> function aqualuxe_sanitize_post_password( $post_password, $post ) {
>     return sanitize_text_field( $post_password );
> }
> add_filter( 'post_password_save_pre', 'aqualuxe_sanitize_post_password', 10, 2 );
> 
> /**
>  * Sanitize post slug
>  */
> function aqualuxe_sanitize_post_slug( $slug, $post, $post_status ) {
>     return sanitize_title( $slug );
> }
> add_filter( 'wp_insert_post_data', 'aqualuxe_sanitize_post_slug', 10, 3 );
> 
> /**
>  * Sanitize term name
>  */
> function aqualuxe_sanitize_term_name( $term, $taxonomy ) {
>     return sanitize_text_field( $term );
> }
> add_filter( 'pre_term_name', 'aqualuxe_sanitize_term_name', 10, 2 );
> 
> /**
>  * Sanitize term slug
>  */
> function aqualuxe_sanitize_term_slug( $slug, $term, $taxonomy ) {
>     return sanitize_title( $slug );
> }
> add_filter( 'pre_term_slug', 'aqualuxe_sanitize_term_slug', 10, 3 );
> 
> /**
>  * Sanitize term description
>  */
> function aqualuxe_sanitize_term_description( $description, $taxonomy ) {
>     return wp_kses_post( $description );
> }
> add_filter( 'pre_term_description', 'aqualuxe_sanitize_term_description', 10, 2 );
> 
> /**
>  * Sanitize option
>  */
> function aqualuxe_sanitize_option( $value, $option ) {
>     if ( is_array( $value ) ) {
>         return array_map( 'sanitize_text_field', $value );
>     } else {
>         return sanitize_text_field( $value );
>     }
> }
> add_filter( 'sanitize_option', 'aqualuxe_sanitize_option', 10, 2 );
> 
> /**
>  * Sanitize theme mod
>  */
> function aqualuxe_sanitize_theme_mod( $value, $setting_id ) {
>     if ( is_array( $value ) ) {
>         return array_map( 'sanitize_text_field', $value );
>     } else {
>         return sanitize_text_field( $value );
>     }
> }
> add_filter( 'sanitize_theme_mod', 'aqualuxe_sanitize_theme_mod', 10, 2 );
> 
> /**
>  * Sanitize widget instance
>  */
> function aqualuxe_sanitize_widget_instance( $instance, $widget, $args ) {
>     foreach ( $instance as $key => $value ) {
>         if ( is_array( $value ) ) {
>             $instance[$key] = array_map( 'sanitize_text_field', $value );
>         } else {
>             $instance[$key] = sanitize_text_field( $value );
>         }
>     }
>     return $instance;
> }
> add_filter( 'widget_update_callback', 'aqualuxe_sanitize_widget_instance', 10, 3 );
> 
> /**
>  * Sanitize comment form fields
>  */
> function aqualuxe_sanitize_comment_form_fields( $fields ) {
>     foreach ( $fields as $key => $field ) {
>         $fields[$key] = wp_kses_post( $field );
>     }
>     return $fields;
> }
> add_filter( 'comment_form_default_fields', 'aqualuxe_sanitize_comment_form_fields' );
> 
> /**
>  * Sanitize comment form
>  */
> function aqualuxe_sanitize_comment_form( $defaults ) {
>     $defaults['comment_field'] = wp_kses_post( $defaults['comment_field'] );
>     $defaults['class_submit'] = sanitize_html_class( $defaults['class_submit'] );
>     $defaults['label_submit'] = sanitize_text_field( $defaults['label_submit'] );
>     $defaults['submit_button'] = wp_kses_post( $defaults['submit_button'] );
>     $defaults['submit_field'] = wp_kses_post( $defaults['submit_field'] );
>     return $defaults;
> }
> add_filter( 'comment_form_defaults', 'aqualuxe_sanitize_comment_form' );
> 
> /**
>  * Sanitize search form
>  */
> function aqualuxe_sanitize_search_form( $form ) {
>     return wp_kses_post( $form );
> }
> add_filter( 'get_search_form', 'aqualuxe_sanitize_search_form' );
> 
> /**
>  * Sanitize nav menu
>  */
> function aqualuxe_sanitize_nav_menu( $nav_menu, $args ) {
>     return wp_kses_post( $nav_menu );
> }
> add_filter( 'wp_nav_menu', 'aqualuxe_sanitize_nav_menu', 10, 2 );
> 
> /**
>  * Sanitize post class
>  */
> function aqualuxe_sanitize_post_class( $classes, $class, $post_id ) {
>     if ( is_array( $classes ) ) {
>         $classes = array_map( 'sanitize_html_class', $classes );
>     }
>     return $classes;
> }
> add_filter( 'post_class', 'aqualuxe_sanitize_post_class', 10, 3 );
> 
> /**
>  * Sanitize body class
>  */
> function aqualuxe_sanitize_body_class( $classes ) {
>     if ( is_array( $classes ) ) {
>         $classes = array_map( 'sanitize_html_class', $classes );
>     }
>     return $classes;
> }
> add_filter( 'body_class', 'aqualuxe_sanitize_body_class' );
> 
> /**
>  * Sanitize comment class
>  */
> function aqualuxe_sanitize_comment_class( $classes, $class, $comment_id, $post_id ) {
>     if ( is_array( $classes ) ) {
>         $classes = array_map( 'sanitize_html_class', $classes );
>     }
>     return $classes;
> }
> add_filter( 'comment_class', 'aqualuxe_sanitize_comment_class', 10, 4 );
> 
> /**
>  * Sanitize term link
>  */
> function aqualuxe_sanitize_term_link( $url, $term, $taxonomy ) {
>     return esc_url( $url );
> }
> add_filter( 'term_link', 'aqualuxe_sanitize_term_link', 10, 3 );
> 
> /**
>  * Sanitize post link
>  */
> function aqualuxe_sanitize_post_link( $permalink, $post, $leavename ) {
>     return esc_url( $permalink );
> }
> add_filter( 'post_link', 'aqualuxe_sanitize_post_link', 10, 3 );
> 
> /**
>  * Sanitize page link
>  */
> function aqualuxe_sanitize_page_link( $url, $page_id ) {
>     return esc_url( $url );
> }
> add_filter( 'page_link', 'aqualuxe_sanitize_page_link', 10, 2 );
> 
> /**
>  * Sanitize attachment link
>  */
> function aqualuxe_sanitize_attachment_link( $url, $post_id ) {
>     return esc_url( $url );
> }
> add_filter( 'attachment_link', 'aqualuxe_sanitize_attachment_link', 10, 2 );
> 
> /**
>  * Sanitize category link
>  */
> function aqualuxe_sanitize_category_link( $url, $category ) {
>     return esc_url( $url );
> }
> add_filter( 'category_link', 'aqualuxe_sanitize_category_link', 10, 2 );
> 
> /**
>  * Sanitize tag link
>  */
> function aqualuxe_sanitize_tag_link( $url, $tag ) {
>     return esc_url( $url );
> }
> add_filter( 'tag_link', 'aqualuxe_sanitize_tag_link', 10, 2 );
> 
> /**
>  * Sanitize author link
>  */
> function aqualuxe_sanitize_author_link( $link, $author_id, $author_nicename ) {
>     return esc_url( $link );
> }
> add_filter( 'author_link', 'aqualuxe_sanitize_author_link', 10, 3 );
> 
> /**
>  * Sanitize day link
>  */
> function aqualuxe_sanitize_day_link( $url, $year, $month, $day ) {
>     return esc_url( $url );
> }
> add_filter( 'day_link', 'aqualuxe_sanitize_day_link', 10, 4 );
> 
> /**
>  * Sanitize month link
>  */
> function aqualuxe_sanitize_month_link( $url, $year, $month ) {
>     return esc_url( $url );
> }
> add_filter( 'month_link', 'aqualuxe_sanitize_month_link', 10, 3 );
> 
> /**
>  * Sanitize year link
>  */
> function aqualuxe_sanitize_year_link( $url, $year ) {
>     return esc_url( $url );
> }
> add_filter( 'year_link', 'aqualuxe_sanitize_year_link', 10, 2 );
> 
> /**
>  * Sanitize search link
>  */
> function aqualuxe_sanitize_search_link( $url ) {
>     return esc_url( $url );
> }
> add_filter( 'search_link', 'aqualuxe_sanitize_search_link' );
> 
> /**
>  * Sanitize feed link
>  */
> function aqualuxe_sanitize_feed_link( $url, $feed ) {
>     return esc_url( $url );
> }
> add_filter( 'feed_link', 'aqualuxe_sanitize_feed_link', 10, 2 );
> 
> /**
>  * Sanitize post type archive link
>  */
> function aqualuxe_sanitize_post_type_archive_link( $url, $post_type ) {
>     return esc_url( $url );
> }
> add_filter( 'post_type_archive_link', 'aqualuxe_sanitize_post_type_archive_link', 10, 2 );
> 
> /**
>  * Sanitize term archive link
>  */
> function aqualuxe_sanitize_term_archive_link( $url, $term ) {
>     return esc_url( $url );
> }
> add_filter( 'term_archive_link', 'aqualuxe_sanitize_term_archive_link', 10, 2 );
> 
> /**
>  * Sanitize home URL
>  */
> function aqualuxe_sanitize_home_url( $url, $path ) {
>     return esc_url( $url );
> }
> add_filter( 'home_url', 'aqualuxe_sanitize_home_url', 10, 2 );
> 
> /**
>  * Sanitize site URL
>  */
> function aqualuxe_sanitize_site_url( $url ) {
>     return esc_url( $url );
> }
> add_filter( 'site_url', 'aqualuxe_sanitize_site_url' );
> 
> /**
>  * Sanitize admin URL
>  */
> function aqualuxe_sanitize_admin_url( $url, $path ) {
>     return esc_url( $url );
> }
> add_filter( 'admin_url', 'aqualuxe_sanitize_admin_url', 10, 2 );
> 
> /**
>  * Sanitize includes URL
>  */
> function aqualuxe_sanitize_includes_url( $url, $path ) {
>     return esc_url( $url );
> }
> add_filter( 'includes_url', 'aqualuxe_sanitize_includes_url', 10, 2 );
> 
> /**
>  * Sanitize content URL
>  */
> function aqualuxe_sanitize_content_url( $url ) {
>     return esc_url( $url );
> }
> add_filter( 'content_url', 'aqualuxe_sanitize_content_url' );
> 
> /**
>  * Sanitize plugins URL
>  */
> function aqualuxe_sanitize_plugins_url( $url, $path, $plugin ) {
>     return esc_url( $url );
> }
> add_filter( 'plugins_url', 'aqualuxe_sanitize_plugins_url', 10, 3 );
> 
> /**
>  * Sanitize theme URL
>  */
> function aqualuxe_sanitize_theme_url( $url, $path ) {
>     return esc_url( $url );
> }
> add_filter( 'theme_url', 'aqualuxe_sanitize_theme_url', 10, 2 );
> 
> /**
>  * Sanitize parent theme URL
>  */
> function aqualuxe_sanitize_parent_theme_url( $url ) {
>     return esc_url( $url );
> }
> add_filter( 'parent_theme_file_uri', 'aqualuxe_sanitize_parent_theme_url' );
> 
> /**
>  * Sanitize child theme URL
>  */
> function aqualuxe_sanitize_child_theme_url( $url ) {
>     return esc_url( $url );
> }
> add_filter( 'theme_file_uri', 'aqualuxe_sanitize_child_theme_url' );
> 
> /**
>  * Sanitize stylesheet URL
>  */
> function aqualuxe_sanitize_stylesheet_url( $stylesheet_uri, $stylesheet_dir_uri ) {
>     return esc_url( $stylesheet_uri );
> }
> add_filter( 'stylesheet_uri', 'aqualuxe_sanitize_stylesheet_url', 10, 2 );
> 
> /**
>  * Sanitize locale stylesheet URL
>  */
> function aqualuxe_sanitize_locale_stylesheet_url( $stylesheet_uri, $stylesheet_dir_uri ) {
>     return esc_url( $stylesheet_uri );
> }
> add_filter( 'locale_stylesheet_uri', 'aqualuxe_sanitize_locale_stylesheet_url', 10, 2 );
> 
> /**
>  * Sanitize template directory URI
>  */
> function aqualuxe_sanitize_template_directory_uri( $template_dir_uri, $template, $theme_root_uri ) {
>     return esc_url( $template_dir_uri );
> }
> add_filter( 'template_directory_uri', 'aqualuxe_sanitize_template_directory_uri', 10, 3 );
> 
> /**
>  * Sanitize stylesheet directory URI
>  */
> function aqualuxe_sanitize_stylesheet_directory_uri( $stylesheet_dir_uri, $stylesheet, $theme_root_uri ) {
>     return esc_url( $stylesheet_dir_uri );
> }
> add_filter( 'stylesheet_directory_uri', 'aqualuxe_sanitize_stylesheet_directory_uri', 10, 3 );
> ```
> 
> ### inc/performance.php
> 
> ```php
> <?php
> /**
>  * AquaLuxe Performance Functions
>  *
>  * @package AquaLuxe
>  */
> 
> /**
>  * Disable emoji scripts
>  */
> function aqualuxe_disable_emoji_scripts() {
>     remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
>     remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
>     remove_action( 'wp_print_styles', 'print_emoji_styles' );
>     remove_action( 'admin_print_styles', 'print_emoji_styles' );
>     remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
>     remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
>     remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
>     add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
> }
> add_action( 'init', 'aqualuxe_disable_emoji_scripts' );
> 
> /**
>  * Disable emojis in TinyMCE
>  */
> function disable_emojis_tinymce( $plugins ) {
>     if ( is_array( $plugins ) ) {
>         return array_diff( $plugins, array( 'wpemoji' ) );
>     } else {
>         return array();
>     }
> }
> 
> /**
>  * Remove query strings from static resources
>  */
> function aqualuxe_remove_query_strings( $src ) {
>     if ( strpos( $src, '?ver=' ) ) {
>         $src = remove_query_arg( 'ver', $src );
>     }
>     return $src;
> }
> add_filter( 'script_loader_src', 'aqualuxe_remove_query_strings', 15, 1 );
> add_filter( 'style_loader_src', 'aqualuxe_remove_query_strings', 15, 1 );
> 
> /**
>  * Defer JavaScript loading
>  */
> function aqualuxe_defer_js( $url ) {
>     if ( is_user_logged_in() ) {
>         return $url;
>     }
>     
>     if ( FALSE === strpos( $url, '.js' ) ) {
>         return $url;
>     }
>     
>     if ( strpos( $url, 'jquery.js' ) ) {
>         return $url;
>     }
>     
>     return str_replace( ' src', ' defer src', $url );
> }
> add_filter( 'script_loader_tag', 'aqualuxe_defer_js', 10 );
> 
> /**
>  * Preload critical resources
>  */
> function aqualuxe_preload_resources() {
>     echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/css/main.css" as="style">' . "\n";
>     echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/js/main.js" as="script">' . "\n";
>     echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/fonts/fontawesome-webfont.woff2" as="font" type="font/woff2" crossorigin="anonymous">' . "\n";
> }
> add_action( 'wp_head', 'aqualuxe_preload_resources', 1 );
> 
> /**
>  * Add async to JavaScript
>  */
> function aqualuxe_async_js( $url ) {
>     if ( strpos( $url, '#asyncload' ) === false ) {
>         return $url;
>     } else if ( is_admin() ) {
>         return str_replace( '#asyncload', '', $url );
>     } else {
>         return str_replace( '#asyncload', '', $url ) . "' async='async";
>     }
> }
> add_filter( 'clean_url', 'aqualuxe_async_js', 11, 1 );
> 
> /**
>  * Remove WordPress version from scripts and styles
>  */
> function aqualuxe_remove_version_scripts_styles( $src ) {
>     if ( strpos( $src, 'ver=' ) ) {
>         $src = remove_query_arg( 'ver', $src );
>     }
>     return $src;
> }
> add_filter( 'style_loader_src', 'aqualuxe_remove_version_scripts_styles', 9999 );
> add_filter( 'script_loader_src', 'aqualuxe_remove_version_scripts_styles', 9999 );
> 
> /**
>  * Disable self pingbacks
>  */
> function aqualuxe_disable_self_pingbacks( &$links ) {
>     $home = get_option( 'home' );
>     foreach ( $links as $l => $link ) {
>         if ( 0 === strpos( $link, $home ) ) {
>             unset( $links[$l] );
>         }
>     }
> }
> add_action( 'pre_ping', 'aqualuxe_disable_self_pingbacks' );
> 
> /**
>  * Remove comment author website link
>  */
> function aqualuxe_remove_comment_author_link( $return, $author, $comment_ID ) {
>     return $author;
> }
> add_filter( 'get_comment_author_link', 'aqualuxe_remove_comment_author_link', 10, 3 );
> 
> /**
>  * Disable RSS feeds
>  */
> function aqualuxe_disable_feed() {
>     wp_die( __( 'No feed available, please visit the <a href="' . esc_url( home_url( '/' ) ) . '">homepage</a>!', 'aqualuxe' ) );
> }
> add_action( 'do_feed', 'aqualuxe_disable_feed', 1 );
> add_action( 'do_feed_rdf', 'aqualuxe_disable_feed', 1 );
> add_action( 'do_feed_rss', 'aqualuxe_disable_feed', 1 );
> add_action( 'do_feed_rss2', 'aqualuxe_disable_feed', 1 );
> add_action( 'do_feed_atom', 'aqualuxe_disable_feed', 1 );
> 
> /**
>  * Remove RSS feed links
>  */
> function aqualuxe_remove_feed_links() {
>     remove_action( 'wp_head', 'feed_links_extra', 3 );
>     remove_action( 'wp_head', 'feed_links', 2 );
>     remove_action( 'wp_head', 'rsd_link' );
> }
> add_action( 'init', 'aqualuxe_remove_feed_links' );
> 
> /**
>  * Minify HTML output
>  */
> function aqualuxe_minify_html( $html ) {
>     if ( ! is_user_logged_in() ) {
>         $html = preg_replace( '/<!--(?!s*(?:\[if [^\]]+]|<!|>))(?s*.*?-->)/s', '', $html );
>         $html = preg_replace( '/\s+/', ' ', $html );
>     }
>     return $html;
> }
> add_filter( 'wp_html_compression', 'aqualuxe_minify_html' );
> 
> /**
>  * Start HTML compression
>  */
> function aqualuxe_html_compression_start() {
>     ob_start( 'aqualuxe_html_compression' );
> }
> add_action( 'get_header', 'aqualuxe_html_compression_start' );
> 
> /**
>  * End HTML compression
>  */
> function aqualuxe_html_compression_finish() {
>     ob_end_flush();
> }
> add_action( 'wp_footer', 'aqualuxe_html_compression_finish' );
> 
> /**
>  * HTML compression function
>  */
> function aqualuxe_html_compression( $buffer ) {
>     return apply_filters( 'wp_html_compression', $buffer );
> }
> 
> /**
>  * Remove unnecessary widgets
>  */
> function aqualuxe_remove_unnecessary_widgets() {
>     unregister_widget( 'WP_Widget_Pages' );
>     unregister_widget( 'WP_Widget_Calendar' );
>     unregister_widget( 'WP_Widget_Archives' );
>     unregister_widget( 'WP_Widget_Links' );
>     unregister_widget( 'WP_Widget_Meta' );
>     unregister_widget( 'WP_Widget_Search' );
>     unregister_widget( 'WP_Widget_Text' );
>     unregister_widget( 'WP_Widget_Categories' );
>     unregister_widget( 'WP_Widget_Recent_Posts' );
>     unregister_widget( 'WP_Widget_Recent_Comments' );
>     unregister_widget( 'WP_Widget_RSS' );
>     unregister_widget( 'WP_Widget_Tag_Cloud' );
>     unregister_widget( 'WP_Nav_Menu_Widget' );
>     unregister_widget( 'WP_Widget_Custom_HTML' );
> }
> add_action( 'widgets_init', 'aqualuxe_remove_unnecessary_widgets' );
> 
> /**
>  * Disable WordPress heartbeat
>  */
> function aqualuxe_disable_heartbeat() {
>     wp_deregister_script( 'heartbeat' );
> }
> add_action( 'init', 'aqualuxe_disable_heartbeat' );
> 
> /**
>  * Disable WordPress embeds
>  */
> function aqualuxe_disable_embeds() {
>     // Remove the REST API endpoint.
>     remove_action( 'rest_api_init', 'wp_oembed_register_rest_endpoint' );
>     
>     // Turn off oEmbed auto discovery.
>     add_filter( 'embed_oembed_discover', '__return_false' );
>     
>     // Don't filter oEmbed results.
>     remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
>     
>     // Remove oEmbed discovery links.
>     remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
>     
>     // Remove oEmbed-specific JavaScript from the front-end and back-end.
>     remove_action( 'wp_head', 'wp_oembed_add_host_js' );
>     
>     // Remove all embeds rewrite rules.
>     add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
> }
> add_action( 'init', 'aqualuxe_disable_embeds', 9999 );
> 
> /**
>  * Remove all embeds rewrite rules.
>  */
> function disable_embeds_rewrites( $rules ) {
>     foreach ( $rules as $rule => $rewrite ) {
>         if ( false !== strpos( $rewrite, 'embed=true' ) ) {
>             unset( $rules[ $rule ] );
>         }
>     }
>     return $rules;
> }
> 
> /**
>  * Disable jQuery migrate
>  */
> function aqualuxe_disable_jquery_migrate( $scripts ) {
>     if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
>         $script = $scripts->registered['jquery'];
>         if ( $script->deps ) {
>             $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
>         }
>     }
> }
> add_action( 'wp_default_scripts', 'aqualuxe_disable_jquery_migrate' );
> 
> /**
>  * Optimize images
>  */
> function aqualuxe_optimize_images( $html ) {
>     // Add lazy loading to images
>     $html = str_replace( '<img', '<img loading="lazy"', $html );
>     
>     // Add srcset and sizes to images without them
>     if ( preg_match_all( '/<img[^>]+>/i', $html, $matches ) ) {
>         foreach ( $matches[0] as $image ) {
>             if ( ! preg_match( '/srcset=/i', $image ) && preg_match( '/src="([^"]+)"/i', $image, $src_match ) ) {
>                 $src = $src_match[1];
>                 $image_id = attachment_url_to_postid( $src );
>                 if ( $image_id ) {
>                     $srcset = wp_get_attachment_image_srcset( $image_id, 'full' );
>                     $sizes = wp_get_attachment_image_sizes( $image_id, 'full' );
>                     if ( $srcset && $sizes ) {
>                         $new_image = str_replace( '<img', '<img srcset="' . esc_attr( $srcset ) . '" sizes="' . esc_attr( $sizes ) . '"', $image );
>                         $html = str_replace( $image, $new_image, $html );
>                     }
>                 }
>             }
>         }
>     }
>     
>     return $html;
> }
> add_filter( 'the_content', 'aqualuxe_optimize_images' );
> add_filter( 'post_thumbnail_html', 'aqualuxe_optimize_images' );
> add_filter( 'widget_text', 'aqualuxe_optimize_images' );
> 
> /**
>  * Add WebP support
>  */
> function aqualuxe_add_webp_support( $mime_types ) {
>     $mime_types['webp'] = 'image/webp';
>     return $mime_types;
> }
> add_filter( 'upload_mimes', 'aqualuxe_add_webp_support' );
> 
> /**
>  * Enable WebP upload
>  */
> function aqualuxe_enable_webp_upload( $types, $file ) {
>     if ( $file['type'] == 'image/webp' ) {
>         $types['ext'] = 'webp';
>         $types['type'] = 'image/webp';
>     }
>     return $types;
> }
> add_filter( 'wp_check_filetype_and_ext', 'aqualuxe_enable_webp_upload', 10, 2 );
> 
> /**
>  * Add WebP to generated image sizes
>  */
> function aqualuxe_add_webp_to_generated_sizes( $sizes ) {
>     $sizes[] = 'webp';
>     return $sizes;
> }
> add_filter( 'intermediate_image_sizes', 'aqualuxe_add_webp_to_generated_sizes' );
> 
> /**
>  * Generate WebP version of uploaded images
>  */
> function aqualuxe_generate_webp( $metadata ) {
>     $file = get_attached_file( $metadata['file'] );
>     $info = pathinfo( $file );
>     $ext = $info['extension'];
>     
>     if ( in_array( $ext, array( 'jpg', 'jpeg', 'png' ) ) ) {
>         $webp_file = $info['dirname'] . '/' . $info['filename'] . '.webp';
>         
>         if ( $ext == 'png' ) {
>             $image = imagecreatefrompng( $file );
>             imagewebp( $image, $webp_file, 80 );
>         } else {
>             $image = imagecreatefromjpeg( $file );
>             imagewebp( $image, $webp_file, 80 );
>         }
>         
>         imagedestroy( $image );
>         
>         // Generate WebP versions for all image sizes
>         foreach ( $metadata['sizes'] as $size => $size_data ) {
>             $size_file = $info['dirname'] . '/' . $size_data['file'];
>             $size_info = pathinfo( $size_file );
>             $size_ext = $size_info['extension'];
>             
>             if ( in_array( $size_ext, array( 'jpg', 'jpeg', 'png' ) ) ) {
>                 $size_webp_file = $size_info['dirname'] . '/' . $size_info['filename'] . '.webp';
>                 
>                 if ( $size_ext == 'png' ) {
>                     $size_image = imagecreatefrompng( $size_file );
>                     imagewebp( $size_image, $size_webp_file, 80 );
>                 } else {
>                     $size_image = imagecreatefromjpeg( $size_file );
>                     imagewebp( $size_image, $size_webp_file, 80 );
>                 }
>                 
>                 imagedestroy( $size_image );
>             }
>         }
>     }
>     
>     return $metadata;
> }
> add_filter( 'wp_generate_attachment_metadata', 'aqualuxe_generate_webp' );
> 
> /**
>  * Serve WebP images to supported browsers
>  */
> function aqualuxe_serve_webp( $url, $attachment_id ) {
>     $file = get_attached_file( $attachment_id );
>     $info = pathinfo( $file );
>     $ext = $info['extension'];
>     
>     if ( in_array( $ext, array( 'jpg', 'jpeg', 'png' ) ) ) {
>         $webp_file = $info['dirname'] . '/' . $info['filename'] . '.webp';
>         
>         if ( file_exists( $webp_file ) && isset( $_SERVER['HTTP_ACCEPT'] ) && strpos( $_SERVER['HTTP_ACCEPT'], 'image/webp' ) !== false ) {
>             $webp_url = str_replace( '.' . $ext, '.webp', $url );
>             return $webp_url;
>         }
>     }
>     
>     return $url;
> }
> add_filter( 'wp_get_attachment_url', 'aqualuxe_serve_webp', 10, 2 );
> 
> /**
>  * Add WebP to picture element
>  */
> function aqualuxe_add_webp_to_picture( $content ) {
>     if ( preg_match_all( '/<img[^>]+>/i', $content, $matches ) ) {
>         foreach ( $matches[0] as $image ) {
>             if ( preg_match( '/src="([^"]+)"/i', $image, $src_match ) ) {
>                 $src = $src_match[1];
>                 $attachment_id = attachment_url_to_postid( $src );
>                 
>                 if ( $attachment_id ) {
>                     $webp_url = aqualuxe_serve_webp( $src, $attachment_id );
>                     
>                     if ( $webp_url !== $src ) {
>                         $picture = '<picture>';
>                         $picture .= '<source srcset="' . esc_url( $webp_url ) . '" type="image/webp">';
>                         $picture .= $image;
>                         $picture .= '</picture>';
>                         
>                         $content = str_replace( $image, $picture, $content );
>                     }
>                 }
>             }
>         }
>     }
>     
>     return $content;
> }
> add_filter( 'the_content', 'aqualuxe_add_webp_to_picture' );
> add_filter( 'post_thumbnail_html', 'aqualuxe_add_webp_to_picture' );
> add_filter( 'widget_text', 'aqualuxe_add_webp_to_picture' );
> 
> /**
>  * Preconnect to external domains
>  */
> function aqualuxe_preconnect_external_domains( $hints, $relation_type ) {
>     if ( 'preconnect' === $relation_type ) {
>         $hints[] = [
>             'href' => 'https://fonts.googleapis.com',
>             'crossorigin',
>         ];
>         $hints[] = [
>             'href' => 'https://fonts.gstatic.com',
>             'crossorigin',
>         ];
>     }
>     return $hints;
> }
> add_filter( 'wp_resource_hints', 'aqualuxe_preconnect_external_domains', 10, 2 );
> 
> /**
>  * Remove WordPress REST API link from header
>  */
> remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
> remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
> 
> /**
>  * Remove WordPress oEmbed discovery links
>  */
> remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
> 
> /**
>  * Remove WordPress oEmbed-specific JavaScript from the front-end
>  */
> remove_action( 'wp_head', 'wp_oembed_add_host_js' );
> 
> /**
>  * Disable WordPress REST API for non-logged-in users
>  */
> function aqualuxe_disable_rest_api( $access ) {
>     if ( ! is_user_logged_in() ) {
>         return new WP_Error(
>             'rest_not_logged_in',
>             __( 'You are not currently logged in.', 'aqualuxe' ),
>             array( 'status' => 401 )
>         );
>     }
>     return $access;
> }
> add_filter( 'rest_authentication_errors', 'aqualuxe_disable_rest_api' );
> 
> /**
>  * Disable WordPress heartbeat
>  */
> function aqualuxe_disable_heartbeat() {
>     wp_deregister_script( 'heartbeat' );
> }
> add_action( 'init', 'aqualuxe_disable_heartbeat' );
> 
> /**
>  * Remove dashboard widgets
>  */
> function aqualuxe_remove_dashboard_widgets() {
>     remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
>     remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
>     remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
>     remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
>     remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
>     remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
>     remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
> }
> add_action( 'wp_dashboard_setup
