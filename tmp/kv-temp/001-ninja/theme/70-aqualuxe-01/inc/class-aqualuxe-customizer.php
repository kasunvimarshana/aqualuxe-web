<?php
/**
 * Theme Customizer Class
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Customizer {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('customize_register', array($this, 'register_customizer'));
        add_action('customize_preview_init', array($this, 'customize_preview_js'));
        add_action('wp_head', array($this, 'customize_css'));
    }
    
    /**
     * Register customizer settings
     */
    public function register_customizer($wp_customize) {
        // Remove default sections we don't need
        $wp_customize->remove_section('colors');
        $wp_customize->remove_section('background_image');
        
        // Site Identity enhancements
        $this->add_site_identity_settings($wp_customize);
        
        // Colors
        $this->add_color_settings($wp_customize);
        
        // Typography
        $this->add_typography_settings($wp_customize);
        
        // Layout
        $this->add_layout_settings($wp_customize);
        
        // Header
        $this->add_header_settings($wp_customize);
        
        // Footer
        $this->add_footer_settings($wp_customize);
        
        // Blog
        $this->add_blog_settings($wp_customize);
        
        // WooCommerce
        if (class_exists('WooCommerce')) {
            $this->add_woocommerce_settings($wp_customize);
        }
        
        // Performance
        $this->add_performance_settings($wp_customize);
        
        // Advanced
        $this->add_advanced_settings($wp_customize);
    }
    
    /**
     * Site Identity Settings
     */
    private function add_site_identity_settings($wp_customize) {
        // Retina logo
        $wp_customize->add_setting('aqualuxe_retina_logo', array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aqualuxe_retina_logo', array(
            'label' => esc_html__('Retina Logo', 'aqualuxe'),
            'description' => esc_html__('Upload a high-resolution logo for retina displays (2x size).', 'aqualuxe'),
            'section' => 'title_tagline',
            'settings' => 'aqualuxe_retina_logo',
        )));
        
        // Favicon
        $wp_customize->add_setting('aqualuxe_favicon', array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aqualuxe_favicon', array(
            'label' => esc_html__('Favicon', 'aqualuxe'),
            'description' => esc_html__('Upload a custom favicon (32x32px recommended).', 'aqualuxe'),
            'section' => 'title_tagline',
            'settings' => 'aqualuxe_favicon',
        )));
    }
    
    /**
     * Color Settings
     */
    private function add_color_settings($wp_customize) {
        $wp_customize->add_section('aqualuxe_colors', array(
            'title' => esc_html__('Colors', 'aqualuxe'),
            'priority' => 40,
        ));
        
        // Primary color
        $wp_customize->add_setting('aqualuxe_primary_color', array(
            'default' => '#006B96',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
            'label' => esc_html__('Primary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_primary_color',
        )));
        
        // Secondary color
        $wp_customize->add_setting('aqualuxe_secondary_color', array(
            'default' => '#00A8CC',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
            'label' => esc_html__('Secondary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_secondary_color',
        )));
        
        // Accent color
        $wp_customize->add_setting('aqualuxe_accent_color', array(
            'default' => '#FFD60A',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', array(
            'label' => esc_html__('Accent Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_accent_color',
        )));
        
        // Text color
        $wp_customize->add_setting('aqualuxe_text_color', array(
            'default' => '#333333',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_text_color', array(
            'label' => esc_html__('Text Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_text_color',
        )));
        
        // Link color
        $wp_customize->add_setting('aqualuxe_link_color', array(
            'default' => '#006B96',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_link_color', array(
            'label' => esc_html__('Link Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_link_color',
        )));
    }
    
    /**
     * Typography Settings
     */
    private function add_typography_settings($wp_customize) {
        $wp_customize->add_section('aqualuxe_typography', array(
            'title' => esc_html__('Typography', 'aqualuxe'),
            'priority' => 50,
        ));
        
        // Primary font
        $wp_customize->add_setting('aqualuxe_primary_font', array(
            'default' => 'Inter',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_primary_font', array(
            'label' => esc_html__('Primary Font', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'select',
            'choices' => $this->get_google_fonts(),
        ));
        
        // Secondary font
        $wp_customize->add_setting('aqualuxe_secondary_font', array(
            'default' => 'Playfair Display',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_secondary_font', array(
            'label' => esc_html__('Secondary Font (Headings)', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'select',
            'choices' => $this->get_google_fonts(),
        ));
        
        // Font sizes
        $wp_customize->add_setting('aqualuxe_base_font_size', array(
            'default' => '16',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_base_font_size', array(
            'label' => esc_html__('Base Font Size (px)', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 12,
                'max' => 24,
                'step' => 1,
            ),
        ));
    }
    
    /**
     * Layout Settings
     */
    private function add_layout_settings($wp_customize) {
        $wp_customize->add_section('aqualuxe_layout', array(
            'title' => esc_html__('Layout', 'aqualuxe'),
            'priority' => 60,
        ));
        
        // Container width
        $wp_customize->add_setting('aqualuxe_container_width', array(
            'default' => '1200',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_container_width', array(
            'label' => esc_html__('Container Width (px)', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 960,
                'max' => 1920,
                'step' => 10,
            ),
        ));
        
        // Sidebar position
        $wp_customize->add_setting('aqualuxe_sidebar_position', array(
            'default' => 'right',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_sidebar_position', array(
            'label' => esc_html__('Sidebar Position', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type' => 'select',
            'choices' => array(
                'left' => esc_html__('Left', 'aqualuxe'),
                'right' => esc_html__('Right', 'aqualuxe'),
                'none' => esc_html__('No Sidebar', 'aqualuxe'),
            ),
        ));
    }
    
    /**
     * Header Settings
     */
    private function add_header_settings($wp_customize) {
        $wp_customize->add_section('aqualuxe_header', array(
            'title' => esc_html__('Header', 'aqualuxe'),
            'priority' => 70,
        ));
        
        // Header layout
        $wp_customize->add_setting('aqualuxe_header_layout', array(
            'default' => 'standard',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_header_layout', array(
            'label' => esc_html__('Header Layout', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'select',
            'choices' => array(
                'standard' => esc_html__('Standard', 'aqualuxe'),
                'centered' => esc_html__('Centered', 'aqualuxe'),
                'minimal' => esc_html__('Minimal', 'aqualuxe'),
            ),
        ));
        
        // Sticky header
        $wp_customize->add_setting('aqualuxe_sticky_header', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        
        $wp_customize->add_control('aqualuxe_sticky_header', array(
            'label' => esc_html__('Sticky Header', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'checkbox',
        ));
        
        // Top bar
        $wp_customize->add_setting('aqualuxe_top_bar', array(
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        
        $wp_customize->add_control('aqualuxe_top_bar', array(
            'label' => esc_html__('Show Top Bar', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'checkbox',
        ));
        
        // Top bar content
        $wp_customize->add_setting('aqualuxe_top_bar_content', array(
            'default' => 'Free shipping on orders over $100',
            'sanitize_callback' => 'wp_kses_post',
        ));
        
        $wp_customize->add_control('aqualuxe_top_bar_content', array(
            'label' => esc_html__('Top Bar Content', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'textarea',
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_top_bar', false);
            },
        ));
    }
    
    /**
     * Footer Settings
     */
    private function add_footer_settings($wp_customize) {
        $wp_customize->add_section('aqualuxe_footer', array(
            'title' => esc_html__('Footer', 'aqualuxe'),
            'priority' => 80,
        ));
        
        // Footer layout
        $wp_customize->add_setting('aqualuxe_footer_layout', array(
            'default' => '4-column',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_footer_layout', array(
            'label' => esc_html__('Footer Layout', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type' => 'select',
            'choices' => array(
                '1-column' => esc_html__('1 Column', 'aqualuxe'),
                '2-column' => esc_html__('2 Columns', 'aqualuxe'),
                '3-column' => esc_html__('3 Columns', 'aqualuxe'),
                '4-column' => esc_html__('4 Columns', 'aqualuxe'),
            ),
        ));
        
        // Copyright text
        $wp_customize->add_setting('aqualuxe_copyright_text', array(
            'default' => '© ' . date('Y') . ' AquaLuxe. All rights reserved.',
            'sanitize_callback' => 'wp_kses_post',
        ));
        
        $wp_customize->add_control('aqualuxe_copyright_text', array(
            'label' => esc_html__('Copyright Text', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type' => 'textarea',
        ));
        
        // Footer background color
        $wp_customize->add_setting('aqualuxe_footer_bg_color', array(
            'default' => '#1a1a1a',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_footer_bg_color', array(
            'label' => esc_html__('Footer Background Color', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'settings' => 'aqualuxe_footer_bg_color',
        )));
    }
    
    /**
     * Blog Settings
     */
    private function add_blog_settings($wp_customize) {
        $wp_customize->add_section('aqualuxe_blog', array(
            'title' => esc_html__('Blog', 'aqualuxe'),
            'priority' => 90,
        ));
        
        // Blog layout
        $wp_customize->add_setting('aqualuxe_blog_layout', array(
            'default' => 'grid',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_blog_layout', array(
            'label' => esc_html__('Blog Layout', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type' => 'select',
            'choices' => array(
                'list' => esc_html__('List', 'aqualuxe'),
                'grid' => esc_html__('Grid', 'aqualuxe'),
                'masonry' => esc_html__('Masonry', 'aqualuxe'),
            ),
        ));
        
        // Posts per page
        $wp_customize->add_setting('aqualuxe_blog_posts_per_page', array(
            'default' => get_option('posts_per_page'),
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_blog_posts_per_page', array(
            'label' => esc_html__('Posts Per Page', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 1,
                'max' => 20,
                'step' => 1,
            ),
        ));
        
        // Show excerpt
        $wp_customize->add_setting('aqualuxe_blog_show_excerpt', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        
        $wp_customize->add_control('aqualuxe_blog_show_excerpt', array(
            'label' => esc_html__('Show Post Excerpt', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type' => 'checkbox',
        ));
    }
    
    /**
     * WooCommerce Settings
     */
    private function add_woocommerce_settings($wp_customize) {
        $wp_customize->add_section('aqualuxe_woocommerce', array(
            'title' => esc_html__('WooCommerce', 'aqualuxe'),
            'priority' => 100,
        ));
        
        // Shop layout
        $wp_customize->add_setting('aqualuxe_shop_layout', array(
            'default' => 'grid',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_shop_layout', array(
            'label' => esc_html__('Shop Layout', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'select',
            'choices' => array(
                'grid' => esc_html__('Grid', 'aqualuxe'),
                'list' => esc_html__('List', 'aqualuxe'),
            ),
        ));
        
        // Products per page
        $wp_customize->add_setting('aqualuxe_shop_products_per_page', array(
            'default' => 12,
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_shop_products_per_page', array(
            'label' => esc_html__('Products Per Page', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 4,
                'max' => 48,
                'step' => 4,
            ),
        ));
        
        // Quick view
        $wp_customize->add_setting('aqualuxe_quick_view', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        
        $wp_customize->add_control('aqualuxe_quick_view', array(
            'label' => esc_html__('Enable Quick View', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'checkbox',
        ));
        
        // Wishlist
        $wp_customize->add_setting('aqualuxe_wishlist', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        
        $wp_customize->add_control('aqualuxe_wishlist', array(
            'label' => esc_html__('Enable Wishlist', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'checkbox',
        ));
    }
    
    /**
     * Performance Settings
     */
    private function add_performance_settings($wp_customize) {
        $wp_customize->add_section('aqualuxe_performance', array(
            'title' => esc_html__('Performance', 'aqualuxe'),
            'priority' => 110,
        ));
        
        // Lazy loading
        $wp_customize->add_setting('aqualuxe_lazy_loading', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        
        $wp_customize->add_control('aqualuxe_lazy_loading', array(
            'label' => esc_html__('Enable Lazy Loading', 'aqualuxe'),
            'section' => 'aqualuxe_performance',
            'type' => 'checkbox',
        ));
        
        // Minification
        $wp_customize->add_setting('aqualuxe_minification', array(
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        
        $wp_customize->add_control('aqualuxe_minification', array(
            'label' => esc_html__('Enable CSS/JS Minification', 'aqualuxe'),
            'description' => esc_html__('Only enable if you don\'t have a caching plugin.', 'aqualuxe'),
            'section' => 'aqualuxe_performance',
            'type' => 'checkbox',
        ));
    }
    
    /**
     * Advanced Settings
     */
    private function add_advanced_settings($wp_customize) {
        $wp_customize->add_section('aqualuxe_advanced', array(
            'title' => esc_html__('Advanced', 'aqualuxe'),
            'priority' => 120,
        ));
        
        // Custom CSS
        $wp_customize->add_setting('aqualuxe_custom_css', array(
            'default' => '',
            'sanitize_callback' => 'wp_strip_all_tags',
        ));
        
        $wp_customize->add_control('aqualuxe_custom_css', array(
            'label' => esc_html__('Custom CSS', 'aqualuxe'),
            'section' => 'aqualuxe_advanced',
            'type' => 'textarea',
        ));
        
        // Custom JS
        $wp_customize->add_setting('aqualuxe_custom_js', array(
            'default' => '',
            'sanitize_callback' => 'wp_strip_all_tags',
        ));
        
        $wp_customize->add_control('aqualuxe_custom_js', array(
            'label' => esc_html__('Custom JavaScript', 'aqualuxe'),
            'section' => 'aqualuxe_advanced',
            'type' => 'textarea',
        ));
    }
    
    /**
     * Get Google Fonts list
     */
    private function get_google_fonts() {
        return array(
            'default' => esc_html__('Default', 'aqualuxe'),
            'Inter' => 'Inter',
            'Playfair Display' => 'Playfair Display',
            'Montserrat' => 'Montserrat',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
            'Roboto' => 'Roboto',
            'Poppins' => 'Poppins',
            'Nunito' => 'Nunito',
            'Source Sans Pro' => 'Source Sans Pro',
            'Oswald' => 'Oswald',
            'Raleway' => 'Raleway',
            'Ubuntu' => 'Ubuntu',
        );
    }
    
    /**
     * Customize preview JavaScript
     */
    public function customize_preview_js() {
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            AQUALUXE_THEME_URL . '/assets/dist/js/customizer-preview.js',
            array('jquery', 'customize-preview'),
            AQUALUXE_VERSION,
            true
        );
    }
    
    /**
     * Output custom CSS
     */
    public function customize_css() {
        $primary_color = get_theme_mod('aqualuxe_primary_color', '#006B96');
        $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#00A8CC');
        $accent_color = get_theme_mod('aqualuxe_accent_color', '#FFD60A');
        $text_color = get_theme_mod('aqualuxe_text_color', '#333333');
        $link_color = get_theme_mod('aqualuxe_link_color', '#006B96');
        $container_width = get_theme_mod('aqualuxe_container_width', '1200');
        $base_font_size = get_theme_mod('aqualuxe_base_font_size', '16');
        $footer_bg_color = get_theme_mod('aqualuxe_footer_bg_color', '#1a1a1a');
        $custom_css = get_theme_mod('aqualuxe_custom_css', '');
        
        ?>
        <style type="text/css" id="aqualuxe-customizer-css">
            :root {
                --aqualuxe-primary-color: <?php echo esc_attr($primary_color); ?>;
                --aqualuxe-secondary-color: <?php echo esc_attr($secondary_color); ?>;
                --aqualuxe-accent-color: <?php echo esc_attr($accent_color); ?>;
                --aqualuxe-text-color: <?php echo esc_attr($text_color); ?>;
                --aqualuxe-link-color: <?php echo esc_attr($link_color); ?>;
                --aqualuxe-container-width: <?php echo esc_attr($container_width); ?>px;
                --aqualuxe-base-font-size: <?php echo esc_attr($base_font_size); ?>px;
                --aqualuxe-footer-bg-color: <?php echo esc_attr($footer_bg_color); ?>;
            }
            
            body {
                font-size: var(--aqualuxe-base-font-size);
                color: var(--aqualuxe-text-color);
            }
            
            .container {
                max-width: var(--aqualuxe-container-width);
            }
            
            a {
                color: var(--aqualuxe-link-color);
            }
            
            .site-footer {
                background-color: var(--aqualuxe-footer-bg-color);
            }
            
            <?php echo wp_strip_all_tags($custom_css); ?>
        </style>
        <?php
        
        // Custom JavaScript
        $custom_js = get_theme_mod('aqualuxe_custom_js', '');
        if (!empty($custom_js)) {
            ?>
            <script type="text/javascript">
                <?php echo wp_strip_all_tags($custom_js); ?>
            </script>
            <?php
        }
    }
}
