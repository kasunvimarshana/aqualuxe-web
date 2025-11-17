
<?php
/**
 * AquaLuxe Customizer Class
 *
 * Handles all theme customization options
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Customizer Class
 */
class AquaLuxe_Customizer {
    /**
     * Singleton instance
     *
     * @var AquaLuxe_Customizer
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return AquaLuxe_Customizer
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Add customizer settings
        add_action('customize_register', array($this, 'register_customizer_settings'));
        
        // Output custom CSS
        add_action('wp_head', array($this, 'output_custom_css'));
        
        // Enqueue customizer scripts
        add_action('customize_preview_init', array($this, 'customize_preview_js'));
        
        // Enqueue customizer controls scripts
        add_action('customize_controls_enqueue_scripts', array($this, 'customize_controls_js'));
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function register_customizer_settings($wp_customize) {
        // Add custom sections, settings, and controls
        
        // Header Section
        $wp_customize->add_section('aqualuxe_header_section', array(
            'title'    => esc_html__('AquaLuxe Header Options', 'aqualuxe'),
            'priority' => 30,
        ));
        
        // Header Style
        $wp_customize->add_setting('aqualuxe_header_style', array(
            'default'           => 'default',
            'sanitize_callback' => array($this, 'sanitize_select'),
        ));
        
        $wp_customize->add_control('aqualuxe_header_style', array(
            'label'    => esc_html__('Header Style', 'aqualuxe'),
            'section'  => 'aqualuxe_header_section',
            'type'     => 'select',
            'choices'  => array(
                'default'     => esc_html__('Default', 'aqualuxe'),
                'transparent' => esc_html__('Transparent', 'aqualuxe'),
                'centered'    => esc_html__('Centered', 'aqualuxe'),
                'minimal'     => esc_html__('Minimal', 'aqualuxe'),
            ),
        ));
        
        // Sticky Header
        $wp_customize->add_setting('aqualuxe_sticky_header', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        
        $wp_customize->add_control('aqualuxe_sticky_header', array(
            'label'    => esc_html__('Enable Sticky Header', 'aqualuxe'),
            'section'  => 'aqualuxe_header_section',
            'type'     => 'checkbox',
        ));
        
        // Colors Section
        $wp_customize->add_section('aqualuxe_colors_section', array(
            'title'    => esc_html__('AquaLuxe Color Options', 'aqualuxe'),
            'priority' => 40,
        ));
        
        // Primary Color
        $wp_customize->add_setting('aqualuxe_primary_color', array(
            'default'           => '#0077b6',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
            'label'    => esc_html__('Primary Color', 'aqualuxe'),
            'section'  => 'aqualuxe_colors_section',
        )));
        
        // Secondary Color
        $wp_customize->add_setting('aqualuxe_secondary_color', array(
            'default'           => '#00b4d8',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
            'label'    => esc_html__('Secondary Color', 'aqualuxe'),
            'section'  => 'aqualuxe_colors_section',
        )));
        
        // Accent Color
        $wp_customize->add_setting('aqualuxe_accent_color', array(
            'default'           => '#48cae4',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', array(
            'label'    => esc_html__('Accent Color', 'aqualuxe'),
            'section'  => 'aqualuxe_colors_section',
        )));
        
        // Text Color
        $wp_customize->add_setting('aqualuxe_text_color', array(
            'default'           => '#212529',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_text_color', array(
            'label'    => esc_html__('Text Color', 'aqualuxe'),
            'section'  => 'aqualuxe_colors_section',
        )));
        
        // Typography Section
        $wp_customize->add_section('aqualuxe_typography_section', array(
            'title'    => esc_html__('AquaLuxe Typography', 'aqualuxe'),
            'priority' => 50,
        ));
        
        // Heading Font
        $wp_customize->add_setting('aqualuxe_heading_font', array(
            'default'           => 'Montserrat',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_heading_font', array(
            'label'    => esc_html__('Heading Font', 'aqualuxe'),
            'section'  => 'aqualuxe_typography_section',
            'type'     => 'select',
            'choices'  => array(
                'Montserrat' => 'Montserrat',
                'Roboto'     => 'Roboto',
                'Open Sans'  => 'Open Sans',
                'Lato'       => 'Lato',
                'Oswald'     => 'Oswald',
            ),
        ));
        
        // Body Font
        $wp_customize->add_setting('aqualuxe_body_font', array(
            'default'           => 'Poppins',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_body_font', array(
            'label'    => esc_html__('Body Font', 'aqualuxe'),
            'section'  => 'aqualuxe_typography_section',
            'type'     => 'select',
            'choices'  => array(
                'Poppins'    => 'Poppins',
                'Roboto'     => 'Roboto',
                'Open Sans'  => 'Open Sans',
                'Lato'       => 'Lato',
                'Nunito'     => 'Nunito',
            ),
        ));
        
        // Layout Section
        $wp_customize->add_section('aqualuxe_layout_section', array(
            'title'    => esc_html__('AquaLuxe Layout Options', 'aqualuxe'),
            'priority' => 60,
        ));
        
        // Container Width
        $wp_customize->add_setting('aqualuxe_container_width', array(
            'default'           => '1140',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_container_width', array(
            'label'    => esc_html__('Container Width (px)', 'aqualuxe'),
            'section'  => 'aqualuxe_layout_section',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 960,
                'max'  => 1600,
                'step' => 10,
            ),
        ));
        
        // Sidebar Position
        $wp_customize->add_setting('aqualuxe_sidebar_position', array(
            'default'           => 'right',
            'sanitize_callback' => array($this, 'sanitize_select'),
        ));
        
        $wp_customize->add_control('aqualuxe_sidebar_position', array(
            'label'    => esc_html__('Sidebar Position', 'aqualuxe'),
            'section'  => 'aqualuxe_layout_section',
            'type'     => 'select',
            'choices'  => array(
                'right' => esc_html__('Right', 'aqualuxe'),
                'left'  => esc_html__('Left', 'aqualuxe'),
                'none'  => esc_html__('No Sidebar', 'aqualuxe'),
            ),
        ));
        
        // Shop Layout
        $wp_customize->add_setting('aqualuxe_shop_layout', array(
            'default'           => 'grid',
            'sanitize_callback' => array($this, 'sanitize_select'),
        ));
        
        $wp_customize->add_control('aqualuxe_shop_layout', array(
            'label'    => esc_html__('Shop Layout', 'aqualuxe'),
            'section'  => 'aqualuxe_layout_section',
            'type'     => 'select',
            'choices'  => array(
                'grid'     => esc_html__('Grid', 'aqualuxe'),
                'list'     => esc_html__('List', 'aqualuxe'),
                'masonry'  => esc_html__('Masonry', 'aqualuxe'),
            ),
        ));
        
        // Products Per Row
        $wp_customize->add_setting('aqualuxe_products_per_row', array(
            'default'           => '3',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_products_per_row', array(
            'label'    => esc_html__('Products Per Row', 'aqualuxe'),
            'section'  => 'aqualuxe_layout_section',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 2,
                'max'  => 6,
                'step' => 1,
            ),
        ));
        
        // Footer Section
        $wp_customize->add_section('aqualuxe_footer_section', array(
            'title'    => esc_html__('AquaLuxe Footer Options', 'aqualuxe'),
            'priority' => 70,
        ));
        
        // Footer Columns
        $wp_customize->add_setting('aqualuxe_footer_columns', array(
            'default'           => '4',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_footer_columns', array(
            'label'    => esc_html__('Footer Widget Columns', 'aqualuxe'),
            'section'  => 'aqualuxe_footer_section',
            'type'     => 'select',
            'choices'  => array(
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ),
        ));
        
        // Copyright Text
        $wp_customize->add_setting('aqualuxe_copyright_text', array(
            'default'           => sprintf(esc_html__('\u00a9 %s AquaLuxe. All Rights Reserved.', 'aqualuxe'), date('Y')),
            'sanitize_callback' => 'wp_kses_post',
        ));
        
        $wp_customize->add_control('aqualuxe_copyright_text', array(
            'label'    => esc_html__('Copyright Text', 'aqualuxe'),
            'section'  => 'aqualuxe_footer_section',
            'type'     => 'textarea',
        ));
        
        // Social Media Section
        $wp_customize->add_section('aqualuxe_social_section', array(
            'title'    => esc_html__('AquaLuxe Social Media', 'aqualuxe'),
            'priority' => 80,
        ));
        
        // Facebook URL
        $wp_customize->add_setting('aqualuxe_facebook_url', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control('aqualuxe_facebook_url', array(
            'label'    => esc_html__('Facebook URL', 'aqualuxe'),
            'section'  => 'aqualuxe_social_section',
            'type'     => 'url',
        ));
        
        // Twitter URL
        $wp_customize->add_setting('aqualuxe_twitter_url', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control('aqualuxe_twitter_url', array(
            'label'    => esc_html__('Twitter URL', 'aqualuxe'),
            'section'  => 'aqualuxe_social_section',
            'type'     => 'url',
        ));
        
        // Instagram URL
        $wp_customize->add_setting('aqualuxe_instagram_url', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control('aqualuxe_instagram_url', array(
            'label'    => esc_html__('Instagram URL', 'aqualuxe'),
            'section'  => 'aqualuxe_social_section',
            'type'     => 'url',
        ));
        
        // YouTube URL
        $wp_customize->add_setting('aqualuxe_youtube_url', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control('aqualuxe_youtube_url', array(
            'label'    => esc_html__('YouTube URL', 'aqualuxe'),
            'section'  => 'aqualuxe_social_section',
            'type'     => 'url',
        ));
    }

    /**
     * Output custom CSS
     */
    public function output_custom_css() {
        // Get customizer values
        $primary_color   = get_theme_mod('aqualuxe_primary_color', '#0077b6');
        $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#00b4d8');
        $accent_color    = get_theme_mod('aqualuxe_accent_color', '#48cae4');
        $text_color      = get_theme_mod('aqualuxe_text_color', '#212529');
        $heading_font    = get_theme_mod('aqualuxe_heading_font', 'Montserrat');
        $body_font       = get_theme_mod('aqualuxe_body_font', 'Poppins');
        $container_width = get_theme_mod('aqualuxe_container_width', '1140');
        
        // Output CSS
        ?>
        <style type="text/css">
            :root {
                --aqualuxe-primary: <?php echo esc_attr($primary_color); ?>;
                --aqualuxe-secondary: <?php echo esc_attr($secondary_color); ?>;
                --aqualuxe-accent: <?php echo esc_attr($accent_color); ?>;
                --aqualuxe-text: <?php echo esc_attr($text_color); ?>;
            }
            
            body {
                font-family: '<?php echo esc_attr($body_font); ?>', sans-serif;
                color: var(--aqualuxe-text);
            }
            
            h1, h2, h3, h4, h5, h6 {
                font-family: '<?php echo esc_attr($heading_font); ?>', sans-serif;
            }
            
            .container {
                max-width: <?php echo esc_attr($container_width); ?>px;
            }
            
            /* Additional custom CSS based on theme mods */
            <?php if (get_theme_mod('aqualuxe_sticky_header', true)) : ?>
            .site-header {
                position: sticky;
                top: 0;
                z-index: 999;
                transition: all 0.3s ease;
            }
            
            body.admin-bar .site-header {
                top: 32px;
            }
            
            @media screen and (max-width: 782px) {
                body.admin-bar .site-header {
                    top: 46px;
                }
            }
            <?php endif; ?>
            
            <?php if ('transparent' === get_theme_mod('aqualuxe_header_style', 'default')) : ?>
            .site-header {
                background-color: transparent;
                position: absolute;
                width: 100%;
                box-shadow: none;
            }
            
            .site-header .site-branding .site-title a,
            .site-header .main-navigation ul li a {
                color: #fff;
            }
            
            .site-header .main-navigation ul li a:hover {
                color: var(--aqualuxe-accent);
            }
            <?php endif; ?>
            
            <?php if ('centered' === get_theme_mod('aqualuxe_header_style', 'default')) : ?>
            .site-header .site-branding {
                width: 100%;
                text-align: center;
                margin-bottom: 1em;
            }
            
            .site-header .main-navigation {
                width: 100%;
                text-align: center;
            }
            <?php endif; ?>
            
            <?php if ('minimal' === get_theme_mod('aqualuxe_header_style', 'default')) : ?>
            .site-header {
                padding-top: 0.5em;
                padding-bottom: 0.5em;
            }
            
            .site-header .site-branding {
                margin-bottom: 0;
            }
            <?php endif; ?>
            
            <?php if ('left' === get_theme_mod('aqualuxe_sidebar_position', 'right')) : ?>
            @media (min-width: 768px) {
                .content-area {
                    float: right;
                }
                
                .widget-area {
                    float: left;
                }
            }
            <?php endif; ?>
            
            <?php if ('none' === get_theme_mod('aqualuxe_sidebar_position', 'right')) : ?>
            @media (min-width: 768px) {
                .content-area {
                    width: 100%;
                    float: none;
                }
                
                .widget-area {
                    display: none;
                }
            }
            <?php endif; ?>
            
            /* Products per row */
            @media (min-width: 768px) {
                .woocommerce-active .site-main ul.products li.product {
                    width: <?php echo esc_attr(100 / intval(get_theme_mod('aqualuxe_products_per_row', 3))); ?>%;
                }
            }
        </style>
        <?php
    }

    /**
     * Enqueue customizer preview JS
     */
    public function customize_preview_js() {
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            AQUALUXE_ASSETS_URI . '/js/customizer-preview.js',
            array('customize-preview', 'jquery'),
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Enqueue customizer controls JS
     */
    public function customize_controls_js() {
        wp_enqueue_script(
            'aqualuxe-customizer-controls',
            AQUALUXE_ASSETS_URI . '/js/customizer-controls.js',
            array('customize-controls', 'jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        wp_enqueue_style(
            'aqualuxe-customizer-controls',
            AQUALUXE_ASSETS_URI . '/css/customizer-controls.css',
            array(),
            AQUALUXE_VERSION
        );
    }

    /**
     * Sanitize select field
     *
     * @param string $input   The input from the setting.
     * @param object $setting The selected setting.
     * @return string The sanitized input.
     */
    public function sanitize_select($input, $setting) {
        // Get the list of choices from the control associated with the setting.
        $choices = $setting->manager->get_control($setting->id)->choices;
        
        // Return input if valid or return default.
        return (array_key_exists($input, $choices) ? $input : $setting->default);
    }
}
