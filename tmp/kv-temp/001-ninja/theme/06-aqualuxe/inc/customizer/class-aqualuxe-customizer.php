<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Theme Customizer Class
 */
class AquaLuxe_Customizer {
    /**
     * Constructor
     */
    public function __construct() {
        // Include customizer sections
        $this->includes();
        
        // Setup customizer
        add_action( 'customize_register', array( $this, 'register' ) );
        
        // Enqueue customizer scripts
        add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_controls_scripts' ) );
        add_action( 'customize_preview_init', array( $this, 'enqueue_preview_scripts' ) );
        
        // Output custom CSS
        add_action( 'wp_head', array( $this, 'output_custom_css' ), 999 );
    }

    /**
     * Include customizer sections
     */
    private function includes() {
        // Include customizer controls
        require_once AQUALUXE_DIR . 'inc/customizer/controls/class-aqualuxe-customizer-control-toggle.php';
        require_once AQUALUXE_DIR . 'inc/customizer/controls/class-aqualuxe-customizer-control-slider.php';
        require_once AQUALUXE_DIR . 'inc/customizer/controls/class-aqualuxe-customizer-control-color-alpha.php';
        require_once AQUALUXE_DIR . 'inc/customizer/controls/class-aqualuxe-customizer-control-typography.php';
        require_once AQUALUXE_DIR . 'inc/customizer/controls/class-aqualuxe-customizer-control-sortable.php';
        
        // Include customizer sections
        require_once AQUALUXE_DIR . 'inc/customizer/sections/general.php';
        require_once AQUALUXE_DIR . 'inc/customizer/sections/header.php';
        require_once AQUALUXE_DIR . 'inc/customizer/sections/footer.php';
        require_once AQUALUXE_DIR . 'inc/customizer/sections/typography.php';
        require_once AQUALUXE_DIR . 'inc/customizer/sections/colors.php';
        require_once AQUALUXE_DIR . 'inc/customizer/sections/blog.php';
        
        // Include WooCommerce customizer sections if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) ) {
            require_once AQUALUXE_DIR . 'inc/customizer/sections/woocommerce.php';
        }
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function register( $wp_customize ) {
        // Add custom controls
        $this->add_custom_controls( $wp_customize );
        
        // Add panels
        $this->add_panels( $wp_customize );
        
        // Add sections
        $this->add_sections( $wp_customize );
        
        // Add settings and controls
        $this->add_settings_and_controls( $wp_customize );
        
        // Add selective refresh support
        $this->add_selective_refresh( $wp_customize );
    }

    /**
     * Add custom controls
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_custom_controls( $wp_customize ) {
        // Register custom control types
        $wp_customize->register_control_type( 'AquaLuxe_Customizer_Control_Toggle' );
        $wp_customize->register_control_type( 'AquaLuxe_Customizer_Control_Slider' );
        $wp_customize->register_control_type( 'AquaLuxe_Customizer_Control_Color_Alpha' );
        $wp_customize->register_control_type( 'AquaLuxe_Customizer_Control_Typography' );
        $wp_customize->register_control_type( 'AquaLuxe_Customizer_Control_Sortable' );
    }

    /**
     * Add panels
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_panels( $wp_customize ) {
        // General Panel
        $wp_customize->add_panel(
            'aqualuxe_general_panel',
            array(
                'title'       => __( 'General Settings', 'aqualuxe' ),
                'description' => __( 'General theme settings', 'aqualuxe' ),
                'priority'    => 10,
            )
        );
        
        // Header Panel
        $wp_customize->add_panel(
            'aqualuxe_header_panel',
            array(
                'title'       => __( 'Header', 'aqualuxe' ),
                'description' => __( 'Header settings', 'aqualuxe' ),
                'priority'    => 20,
            )
        );
        
        // Footer Panel
        $wp_customize->add_panel(
            'aqualuxe_footer_panel',
            array(
                'title'       => __( 'Footer', 'aqualuxe' ),
                'description' => __( 'Footer settings', 'aqualuxe' ),
                'priority'    => 30,
            )
        );
        
        // Typography Panel
        $wp_customize->add_panel(
            'aqualuxe_typography_panel',
            array(
                'title'       => __( 'Typography', 'aqualuxe' ),
                'description' => __( 'Typography settings', 'aqualuxe' ),
                'priority'    => 40,
            )
        );
        
        // Colors Panel
        $wp_customize->add_panel(
            'aqualuxe_colors_panel',
            array(
                'title'       => __( 'Colors', 'aqualuxe' ),
                'description' => __( 'Color settings', 'aqualuxe' ),
                'priority'    => 50,
            )
        );
        
        // Blog Panel
        $wp_customize->add_panel(
            'aqualuxe_blog_panel',
            array(
                'title'       => __( 'Blog', 'aqualuxe' ),
                'description' => __( 'Blog settings', 'aqualuxe' ),
                'priority'    => 60,
            )
        );
        
        // WooCommerce Panel
        if ( class_exists( 'WooCommerce' ) ) {
            $wp_customize->add_panel(
                'aqualuxe_woocommerce_panel',
                array(
                    'title'       => __( 'WooCommerce', 'aqualuxe' ),
                    'description' => __( 'WooCommerce settings', 'aqualuxe' ),
                    'priority'    => 70,
                )
            );
        }
    }

    /**
     * Add sections
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_sections( $wp_customize ) {
        // General Sections
        $wp_customize->add_section(
            'aqualuxe_general_layout',
            array(
                'title'       => __( 'Layout', 'aqualuxe' ),
                'description' => __( 'Layout settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 10,
            )
        );
        
        $wp_customize->add_section(
            'aqualuxe_general_performance',
            array(
                'title'       => __( 'Performance', 'aqualuxe' ),
                'description' => __( 'Performance settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 20,
            )
        );
        
        // Header Sections
        $wp_customize->add_section(
            'aqualuxe_header_layout',
            array(
                'title'       => __( 'Layout', 'aqualuxe' ),
                'description' => __( 'Header layout settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_header_panel',
                'priority'    => 10,
            )
        );
        
        $wp_customize->add_section(
            'aqualuxe_header_navigation',
            array(
                'title'       => __( 'Navigation', 'aqualuxe' ),
                'description' => __( 'Navigation settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_header_panel',
                'priority'    => 20,
            )
        );
        
        // Footer Sections
        $wp_customize->add_section(
            'aqualuxe_footer_layout',
            array(
                'title'       => __( 'Layout', 'aqualuxe' ),
                'description' => __( 'Footer layout settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_footer_panel',
                'priority'    => 10,
            )
        );
        
        $wp_customize->add_section(
            'aqualuxe_footer_widgets',
            array(
                'title'       => __( 'Widgets', 'aqualuxe' ),
                'description' => __( 'Footer widgets settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_footer_panel',
                'priority'    => 20,
            )
        );
        
        $wp_customize->add_section(
            'aqualuxe_footer_copyright',
            array(
                'title'       => __( 'Copyright', 'aqualuxe' ),
                'description' => __( 'Copyright settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_footer_panel',
                'priority'    => 30,
            )
        );
        
        // Typography Sections
        $wp_customize->add_section(
            'aqualuxe_typography_general',
            array(
                'title'       => __( 'General', 'aqualuxe' ),
                'description' => __( 'General typography settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_typography_panel',
                'priority'    => 10,
            )
        );
        
        $wp_customize->add_section(
            'aqualuxe_typography_headings',
            array(
                'title'       => __( 'Headings', 'aqualuxe' ),
                'description' => __( 'Headings typography settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_typography_panel',
                'priority'    => 20,
            )
        );
        
        // Colors Sections
        $wp_customize->add_section(
            'aqualuxe_colors_general',
            array(
                'title'       => __( 'General', 'aqualuxe' ),
                'description' => __( 'General color settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_colors_panel',
                'priority'    => 10,
            )
        );
        
        $wp_customize->add_section(
            'aqualuxe_colors_header',
            array(
                'title'       => __( 'Header', 'aqualuxe' ),
                'description' => __( 'Header color settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_colors_panel',
                'priority'    => 20,
            )
        );
        
        $wp_customize->add_section(
            'aqualuxe_colors_footer',
            array(
                'title'       => __( 'Footer', 'aqualuxe' ),
                'description' => __( 'Footer color settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_colors_panel',
                'priority'    => 30,
            )
        );
        
        // Blog Sections
        $wp_customize->add_section(
            'aqualuxe_blog_archive',
            array(
                'title'       => __( 'Archive', 'aqualuxe' ),
                'description' => __( 'Blog archive settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_blog_panel',
                'priority'    => 10,
            )
        );
        
        $wp_customize->add_section(
            'aqualuxe_blog_single',
            array(
                'title'       => __( 'Single Post', 'aqualuxe' ),
                'description' => __( 'Single post settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_blog_panel',
                'priority'    => 20,
            )
        );
        
        // WooCommerce Sections
        if ( class_exists( 'WooCommerce' ) ) {
            $wp_customize->add_section(
                'aqualuxe_woocommerce_shop',
                array(
                    'title'       => __( 'Shop', 'aqualuxe' ),
                    'description' => __( 'Shop settings', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_woocommerce_panel',
                    'priority'    => 10,
                )
            );
            
            $wp_customize->add_section(
                'aqualuxe_woocommerce_product',
                array(
                    'title'       => __( 'Product', 'aqualuxe' ),
                    'description' => __( 'Product settings', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_woocommerce_panel',
                    'priority'    => 20,
                )
            );
            
            $wp_customize->add_section(
                'aqualuxe_woocommerce_cart',
                array(
                    'title'       => __( 'Cart', 'aqualuxe' ),
                    'description' => __( 'Cart settings', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_woocommerce_panel',
                    'priority'    => 30,
                )
            );
            
            $wp_customize->add_section(
                'aqualuxe_woocommerce_checkout',
                array(
                    'title'       => __( 'Checkout', 'aqualuxe' ),
                    'description' => __( 'Checkout settings', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_woocommerce_panel',
                    'priority'    => 40,
                )
            );
            
            $wp_customize->add_section(
                'aqualuxe_woocommerce_account',
                array(
                    'title'       => __( 'My Account', 'aqualuxe' ),
                    'description' => __( 'My Account settings', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_woocommerce_panel',
                    'priority'    => 50,
                )
            );
            
            $wp_customize->add_section(
                'aqualuxe_woocommerce_features',
                array(
                    'title'       => __( 'Features', 'aqualuxe' ),
                    'description' => __( 'Additional WooCommerce features', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_woocommerce_panel',
                    'priority'    => 60,
                )
            );
        }
    }

    /**
     * Add settings and controls
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_settings_and_controls( $wp_customize ) {
        // Call section-specific functions to add settings and controls
        aqualuxe_customizer_general( $wp_customize );
        aqualuxe_customizer_header( $wp_customize );
        aqualuxe_customizer_footer( $wp_customize );
        aqualuxe_customizer_typography( $wp_customize );
        aqualuxe_customizer_colors( $wp_customize );
        aqualuxe_customizer_blog( $wp_customize );
        
        // Add WooCommerce settings and controls if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) ) {
            aqualuxe_customizer_woocommerce( $wp_customize );
        }
    }

    /**
     * Add selective refresh support
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_selective_refresh( $wp_customize ) {
        // Site title and description
        $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
        $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
        
        // Site title
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector'        => '.site-title a',
                'render_callback' => function() {
                    return get_bloginfo( 'name', 'display' );
                },
            )
        );
        
        // Site description
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector'        => '.site-description',
                'render_callback' => function() {
                    return get_bloginfo( 'description', 'display' );
                },
            )
        );
        
        // Header layout
        $wp_customize->selective_refresh->add_partial(
            'aqualuxe_header_layout',
            array(
                'selector'        => '.site-header',
                'render_callback' => function() {
                    get_template_part( 'templates/parts/header' );
                },
            )
        );
        
        // Footer layout
        $wp_customize->selective_refresh->add_partial(
            'aqualuxe_footer_layout',
            array(
                'selector'        => '.site-footer',
                'render_callback' => function() {
                    get_template_part( 'templates/parts/footer' );
                },
            )
        );
        
        // Footer copyright
        $wp_customize->selective_refresh->add_partial(
            'aqualuxe_footer_copyright_text',
            array(
                'selector'        => '.site-info',
                'render_callback' => function() {
                    get_template_part( 'templates/parts/footer', 'copyright' );
                },
            )
        );
    }

    /**
     * Enqueue customizer control scripts
     */
    public function enqueue_controls_scripts() {
        // Enqueue customizer controls script
        wp_enqueue_script(
            'aqualuxe-customizer-controls',
            AQUALUXE_URI . 'assets/js/customizer-controls.js',
            array( 'jquery', 'customize-controls' ),
            AQUALUXE_VERSION,
            true
        );
        
        // Enqueue customizer controls style
        wp_enqueue_style(
            'aqualuxe-customizer-controls',
            AQUALUXE_URI . 'assets/css/customizer-controls.css',
            array(),
            AQUALUXE_VERSION
        );
    }

    /**
     * Enqueue customizer preview scripts
     */
    public function enqueue_preview_scripts() {
        // Enqueue customizer preview script
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            AQUALUXE_URI . 'assets/js/customizer-preview.js',
            array( 'jquery', 'customize-preview' ),
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Output custom CSS
     */
    public function output_custom_css() {
        // Get custom CSS
        $custom_css = $this->get_custom_css();
        
        // Output custom CSS
        if ( ! empty( $custom_css ) ) {
            echo '<style id="aqualuxe-custom-css">' . $this->minify_css( $custom_css ) . '</style>';
        }
    }

    /**
     * Get custom CSS
     *
     * @return string
     */
    private function get_custom_css() {
        $css = '';
        
        // Primary color
        $primary_color = get_theme_mod( 'aqualuxe_primary_color', '#0073aa' );
        if ( $primary_color ) {
            $css .= '
                :root {
                    --aqualuxe-primary-color: ' . $primary_color . ';
                }
                
                a,
                .primary-color,
                .site-header .menu > li.current-menu-item > a,
                .site-header .menu > li.current-menu-ancestor > a,
                .site-header .menu > li.current_page_parent > a,
                .site-header .menu > li > a:hover {
                    color: ' . $primary_color . ';
                }
                
                .btn-primary,
                .button,
                button,
                input[type="button"],
                input[type="reset"],
                input[type="submit"],
                .woocommerce #respond input#submit,
                .woocommerce a.button,
                .woocommerce button.button,
                .woocommerce input.button,
                .woocommerce #respond input#submit.alt,
                .woocommerce a.button.alt,
                .woocommerce button.button.alt,
                .woocommerce input.button.alt {
                    background-color: ' . $primary_color . ';
                }
                
                .border-primary,
                .site-header .menu > li.current-menu-item > a:after,
                .site-header .menu > li.current-menu-ancestor > a:after,
                .site-header .menu > li.current_page_parent > a:after,
                .site-header .menu > li > a:hover:after {
                    border-color: ' . $primary_color . ';
                }
            ';
        }
        
        // Secondary color
        $secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#00a0d2' );
        if ( $secondary_color ) {
            $css .= '
                :root {
                    --aqualuxe-secondary-color: ' . $secondary_color . ';
                }
                
                .secondary-color,
                a:hover,
                a:focus,
                a:active {
                    color: ' . $secondary_color . ';
                }
                
                .btn-secondary,
                .button:hover,
                button:hover,
                input[type="button"]:hover,
                input[type="reset"]:hover,
                input[type="submit"]:hover,
                .woocommerce #respond input#submit:hover,
                .woocommerce a.button:hover,
                .woocommerce button.button:hover,
                .woocommerce input.button:hover,
                .woocommerce #respond input#submit.alt:hover,
                .woocommerce a.button.alt:hover,
                .woocommerce button.button.alt:hover,
                .woocommerce input.button.alt:hover {
                    background-color: ' . $secondary_color . ';
                }
                
                .border-secondary {
                    border-color: ' . $secondary_color . ';
                }
            ';
        }
        
        // Body background color
        $body_bg_color = get_theme_mod( 'aqualuxe_body_background_color', '#ffffff' );
        if ( $body_bg_color ) {
            $css .= '
                body {
                    background-color: ' . $body_bg_color . ';
                }
            ';
        }
        
        // Body text color
        $body_text_color = get_theme_mod( 'aqualuxe_body_text_color', '#333333' );
        if ( $body_text_color ) {
            $css .= '
                body {
                    color: ' . $body_text_color . ';
                }
            ';
        }
        
        // Header background color
        $header_bg_color = get_theme_mod( 'aqualuxe_header_background_color', '#ffffff' );
        if ( $header_bg_color ) {
            $css .= '
                .site-header {
                    background-color: ' . $header_bg_color . ';
                }
            ';
        }
        
        // Header text color
        $header_text_color = get_theme_mod( 'aqualuxe_header_text_color', '#333333' );
        if ( $header_text_color ) {
            $css .= '
                .site-header,
                .site-header a {
                    color: ' . $header_text_color . ';
                }
            ';
        }
        
        // Footer background color
        $footer_bg_color = get_theme_mod( 'aqualuxe_footer_background_color', '#f5f5f5' );
        if ( $footer_bg_color ) {
            $css .= '
                .site-footer {
                    background-color: ' . $footer_bg_color . ';
                }
            ';
        }
        
        // Footer text color
        $footer_text_color = get_theme_mod( 'aqualuxe_footer_text_color', '#333333' );
        if ( $footer_text_color ) {
            $css .= '
                .site-footer {
                    color: ' . $footer_text_color . ';
                }
            ';
        }
        
        // Body font family
        $body_font_family = get_theme_mod( 'aqualuxe_body_font_family', 'Poppins' );
        if ( $body_font_family ) {
            $css .= '
                body {
                    font-family: "' . $body_font_family . '", sans-serif;
                }
            ';
        }
        
        // Headings font family
        $headings_font_family = get_theme_mod( 'aqualuxe_headings_font_family', 'Playfair Display' );
        if ( $headings_font_family ) {
            $css .= '
                h1, h2, h3, h4, h5, h6 {
                    font-family: "' . $headings_font_family . '", serif;
                }
            ';
        }
        
        // Container width
        $container_width = get_theme_mod( 'aqualuxe_container_width', '1200' );
        if ( $container_width ) {
            $css .= '
                .container {
                    max-width: ' . $container_width . 'px;
                }
            ';
        }
        
        return $css;
    }

    /**
     * Minify CSS
     *
     * @param string $css CSS to minify.
     * @return string
     */
    private function minify_css( $css ) {
        // Remove comments
        $css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
        
        // Remove space after colons
        $css = str_replace( ': ', ':', $css );
        
        // Remove whitespace
        $css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );
        
        return $css;
    }
}

// Initialize the class
new AquaLuxe_Customizer();