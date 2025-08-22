<?php
/**
 * AquaLuxe Multilingual Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe Multilingual Module Class
 */
class AquaLuxe_Multilingual_Module extends AquaLuxe_Module {
    /**
     * Module ID
     *
     * @var string
     */
    protected $module_id = 'multilingual';

    /**
     * Module name
     *
     * @var string
     */
    protected $module_name = 'Multilingual Support';

    /**
     * Module description
     *
     * @var string
     */
    protected $module_description = 'Adds multilingual support to the AquaLuxe theme with WPML and Polylang compatibility.';

    /**
     * Module version
     *
     * @var string
     */
    protected $module_version = '1.0.0';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $dependencies = array();

    /**
     * Initialize module
     *
     * @return void
     */
    public function init() {
        // Include required files
        $this->includes();
        
        // Register hooks
        $this->register_hooks();
        
        // Add language switcher to header
        add_action( 'aqualuxe_header_after_navigation', array( $this, 'add_language_switcher' ) );
        
        // Add language switcher to footer
        add_action( 'aqualuxe_footer_widgets_after', array( $this, 'add_language_switcher_footer' ) );
        
        // Add language switcher to mobile menu
        add_action( 'aqualuxe_mobile_menu_after', array( $this, 'add_language_switcher_mobile' ) );
        
        // Filter menu items for language specific content
        add_filter( 'wp_nav_menu_objects', array( $this, 'filter_menu_items' ), 10, 2 );
        
        // Add language meta tags to head
        add_action( 'wp_head', array( $this, 'add_language_meta_tags' ) );
        
        // Add hreflang links to head
        add_action( 'wp_head', array( $this, 'add_hreflang_links' ) );
        
        // Add language attributes to html tag
        add_filter( 'language_attributes', array( $this, 'filter_language_attributes' ) );
        
        // Add language class to body
        add_filter( 'body_class', array( $this, 'add_language_body_class' ) );
        
        // Register customizer settings
        add_action( 'customize_register', array( $this, 'register_customizer_settings' ) );
    }

    /**
     * Include required files
     *
     * @return void
     */
    private function includes() {
        // Include helper functions
        require_once $this->get_module_dir() . 'inc/helpers.php';
        
        // Include template functions
        require_once $this->get_module_dir() . 'inc/template-functions.php';
        
        // Include WPML compatibility if WPML is active
        if ( $this->is_wpml_active() ) {
            require_once $this->get_module_dir() . 'inc/wpml-compatibility.php';
        }
        
        // Include Polylang compatibility if Polylang is active
        if ( $this->is_polylang_active() ) {
            require_once $this->get_module_dir() . 'inc/polylang-compatibility.php';
        }
    }

    /**
     * Register hooks
     *
     * @return void
     */
    private function register_hooks() {
        // Register assets
        add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
        
        // Enqueue frontend assets
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
        
        // Enqueue admin assets
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
    }

    /**
     * Register assets
     *
     * @return void
     */
    public function register_assets() {
        // Register styles
        wp_register_style(
            'aqualuxe-multilingual',
            $this->get_module_uri() . 'assets/css/multilingual.css',
            array(),
            $this->get_module_version()
        );
        
        // Register scripts
        wp_register_script(
            'aqualuxe-multilingual',
            $this->get_module_uri() . 'assets/js/multilingual.js',
            array( 'jquery' ),
            $this->get_module_version(),
            true
        );
    }

    /**
     * Enqueue frontend assets
     *
     * @return void
     */
    public function enqueue_frontend_assets() {
        // Enqueue styles
        wp_enqueue_style( 'aqualuxe-multilingual' );
        
        // Enqueue scripts
        wp_enqueue_script( 'aqualuxe-multilingual' );
        
        // Localize script
        wp_localize_script(
            'aqualuxe-multilingual',
            'aqualuxeMultilingual',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'currentLang' => $this->get_current_language(),
                'defaultLang' => $this->get_default_language(),
                'languages' => $this->get_languages(),
                'switcherStyle' => $this->get_setting( 'switcher_style', 'dropdown' ),
                'showFlags' => $this->get_setting( 'show_flags', true ),
                'showNames' => $this->get_setting( 'show_names', true ),
            )
        );
    }

    /**
     * Enqueue admin assets
     *
     * @return void
     */
    public function enqueue_admin_assets() {
        // Enqueue admin styles
        wp_enqueue_style(
            'aqualuxe-multilingual-admin',
            $this->get_module_uri() . 'assets/css/multilingual-admin.css',
            array(),
            $this->get_module_version()
        );
        
        // Enqueue admin scripts
        wp_enqueue_script(
            'aqualuxe-multilingual-admin',
            $this->get_module_uri() . 'assets/js/multilingual-admin.js',
            array( 'jquery' ),
            $this->get_module_version(),
            true
        );
    }

    /**
     * Add language switcher to header
     *
     * @return void
     */
    public function add_language_switcher() {
        // Check if language switcher is enabled in header
        if ( ! $this->get_setting( 'show_in_header', true ) ) {
            return;
        }
        
        // Get language switcher style
        $style = $this->get_setting( 'switcher_style', 'dropdown' );
        
        // Get template part
        aqualuxe_get_template_part( 'template-parts/language-switcher', $style, array(
            'location' => 'header',
            'show_flags' => $this->get_setting( 'show_flags', true ),
            'show_names' => $this->get_setting( 'show_names', true ),
        ) );
    }

    /**
     * Add language switcher to footer
     *
     * @return void
     */
    public function add_language_switcher_footer() {
        // Check if language switcher is enabled in footer
        if ( ! $this->get_setting( 'show_in_footer', true ) ) {
            return;
        }
        
        // Get language switcher style
        $style = $this->get_setting( 'switcher_style_footer', 'horizontal' );
        
        // Get template part
        aqualuxe_get_template_part( 'template-parts/language-switcher', $style, array(
            'location' => 'footer',
            'show_flags' => $this->get_setting( 'show_flags', true ),
            'show_names' => $this->get_setting( 'show_names', true ),
        ) );
    }

    /**
     * Add language switcher to mobile menu
     *
     * @return void
     */
    public function add_language_switcher_mobile() {
        // Check if language switcher is enabled in mobile menu
        if ( ! $this->get_setting( 'show_in_mobile', true ) ) {
            return;
        }
        
        // Get language switcher style
        $style = $this->get_setting( 'switcher_style_mobile', 'horizontal' );
        
        // Get template part
        aqualuxe_get_template_part( 'template-parts/language-switcher', $style, array(
            'location' => 'mobile',
            'show_flags' => $this->get_setting( 'show_flags', true ),
            'show_names' => $this->get_setting( 'show_names', true ),
        ) );
    }

    /**
     * Filter menu items for language specific content
     *
     * @param array $items Menu items
     * @param object $args Menu arguments
     * @return array
     */
    public function filter_menu_items( $items, $args ) {
        // Check if menu filtering is enabled
        if ( ! $this->get_setting( 'filter_menus', true ) ) {
            return $items;
        }
        
        // Get current language
        $current_language = $this->get_current_language();
        
        // Filter items
        foreach ( $items as $key => $item ) {
            // Check if item has language meta
            $item_language = get_post_meta( $item->ID, '_menu_item_language', true );
            
            // If item has language meta and it doesn't match current language, remove it
            if ( $item_language && $item_language !== $current_language && $item_language !== 'all' ) {
                unset( $items[ $key ] );
            }
        }
        
        return $items;
    }

    /**
     * Add language meta tags to head
     *
     * @return void
     */
    public function add_language_meta_tags() {
        // Get current language
        $current_language = $this->get_current_language();
        
        // Output meta tag
        echo '<meta name="language" content="' . esc_attr( $current_language ) . '">' . "\n";
    }

    /**
     * Add hreflang links to head
     *
     * @return void
     */
    public function add_hreflang_links() {
        // Check if hreflang links are enabled
        if ( ! $this->get_setting( 'add_hreflang', true ) ) {
            return;
        }
        
        // Get languages
        $languages = $this->get_languages();
        
        // Get current URL
        $current_url = $this->get_current_url();
        
        // Loop through languages
        foreach ( $languages as $code => $language ) {
            // Get translated URL
            $translated_url = $this->get_translated_url( $current_url, $code );
            
            // Output hreflang link
            echo '<link rel="alternate" hreflang="' . esc_attr( $code ) . '" href="' . esc_url( $translated_url ) . '">' . "\n";
        }
        
        // Add x-default hreflang
        $default_language = $this->get_default_language();
        $default_url = $this->get_translated_url( $current_url, $default_language );
        echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $default_url ) . '">' . "\n";
    }

    /**
     * Filter language attributes
     *
     * @param string $output Language attributes
     * @return string
     */
    public function filter_language_attributes( $output ) {
        // Get current language
        $current_language = $this->get_current_language();
        
        // Replace lang attribute
        $output = preg_replace( '/lang="[^"]*"/', 'lang="' . esc_attr( $current_language ) . '"', $output );
        
        return $output;
    }

    /**
     * Add language body class
     *
     * @param array $classes Body classes
     * @return array
     */
    public function add_language_body_class( $classes ) {
        // Get current language
        $current_language = $this->get_current_language();
        
        // Add language class
        $classes[] = 'lang-' . sanitize_html_class( $current_language );
        
        return $classes;
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    public function register_customizer_settings( $wp_customize ) {
        // Add section
        $wp_customize->add_section( 'aqualuxe_multilingual', array(
            'title' => __( 'Multilingual Settings', 'aqualuxe' ),
            'priority' => 100,
            'panel' => 'aqualuxe_theme_options',
        ) );
        
        // Add settings
        $wp_customize->add_setting( 'aqualuxe_multilingual_show_in_header', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_multilingual_show_in_footer', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_multilingual_show_in_mobile', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_multilingual_switcher_style', array(
            'default' => 'dropdown',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport' => 'refresh',
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_multilingual_switcher_style_footer', array(
            'default' => 'horizontal',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport' => 'refresh',
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_multilingual_switcher_style_mobile', array(
            'default' => 'horizontal',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport' => 'refresh',
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_multilingual_show_flags', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_multilingual_show_names', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_multilingual_filter_menus', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_multilingual_add_hreflang', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        ) );
        
        // Add controls
        $wp_customize->add_control( 'aqualuxe_multilingual_show_in_header', array(
            'label' => __( 'Show language switcher in header', 'aqualuxe' ),
            'section' => 'aqualuxe_multilingual',
            'type' => 'checkbox',
        ) );
        
        $wp_customize->add_control( 'aqualuxe_multilingual_show_in_footer', array(
            'label' => __( 'Show language switcher in footer', 'aqualuxe' ),
            'section' => 'aqualuxe_multilingual',
            'type' => 'checkbox',
        ) );
        
        $wp_customize->add_control( 'aqualuxe_multilingual_show_in_mobile', array(
            'label' => __( 'Show language switcher in mobile menu', 'aqualuxe' ),
            'section' => 'aqualuxe_multilingual',
            'type' => 'checkbox',
        ) );
        
        $wp_customize->add_control( 'aqualuxe_multilingual_switcher_style', array(
            'label' => __( 'Header language switcher style', 'aqualuxe' ),
            'section' => 'aqualuxe_multilingual',
            'type' => 'select',
            'choices' => array(
                'dropdown' => __( 'Dropdown', 'aqualuxe' ),
                'horizontal' => __( 'Horizontal', 'aqualuxe' ),
                'vertical' => __( 'Vertical', 'aqualuxe' ),
            ),
        ) );
        
        $wp_customize->add_control( 'aqualuxe_multilingual_switcher_style_footer', array(
            'label' => __( 'Footer language switcher style', 'aqualuxe' ),
            'section' => 'aqualuxe_multilingual',
            'type' => 'select',
            'choices' => array(
                'dropdown' => __( 'Dropdown', 'aqualuxe' ),
                'horizontal' => __( 'Horizontal', 'aqualuxe' ),
                'vertical' => __( 'Vertical', 'aqualuxe' ),
            ),
        ) );
        
        $wp_customize->add_control( 'aqualuxe_multilingual_switcher_style_mobile', array(
            'label' => __( 'Mobile language switcher style', 'aqualuxe' ),
            'section' => 'aqualuxe_multilingual',
            'type' => 'select',
            'choices' => array(
                'dropdown' => __( 'Dropdown', 'aqualuxe' ),
                'horizontal' => __( 'Horizontal', 'aqualuxe' ),
                'vertical' => __( 'Vertical', 'aqualuxe' ),
            ),
        ) );
        
        $wp_customize->add_control( 'aqualuxe_multilingual_show_flags', array(
            'label' => __( 'Show language flags', 'aqualuxe' ),
            'section' => 'aqualuxe_multilingual',
            'type' => 'checkbox',
        ) );
        
        $wp_customize->add_control( 'aqualuxe_multilingual_show_names', array(
            'label' => __( 'Show language names', 'aqualuxe' ),
            'section' => 'aqualuxe_multilingual',
            'type' => 'checkbox',
        ) );
        
        $wp_customize->add_control( 'aqualuxe_multilingual_filter_menus', array(
            'label' => __( 'Filter menu items by language', 'aqualuxe' ),
            'section' => 'aqualuxe_multilingual',
            'type' => 'checkbox',
        ) );
        
        $wp_customize->add_control( 'aqualuxe_multilingual_add_hreflang', array(
            'label' => __( 'Add hreflang links to head', 'aqualuxe' ),
            'section' => 'aqualuxe_multilingual',
            'type' => 'checkbox',
        ) );
    }

    /**
     * Get default settings
     *
     * @return array
     */
    protected function get_default_settings() {
        return array(
            'active' => true,
            'show_in_header' => true,
            'show_in_footer' => true,
            'show_in_mobile' => true,
            'switcher_style' => 'dropdown',
            'switcher_style_footer' => 'horizontal',
            'switcher_style_mobile' => 'horizontal',
            'show_flags' => true,
            'show_names' => true,
            'filter_menus' => true,
            'add_hreflang' => true,
        );
    }

    /**
     * Check if WPML is active
     *
     * @return bool
     */
    public function is_wpml_active() {
        return defined( 'ICL_SITEPRESS_VERSION' );
    }

    /**
     * Check if Polylang is active
     *
     * @return bool
     */
    public function is_polylang_active() {
        return function_exists( 'pll_current_language' );
    }

    /**
     * Get current language
     *
     * @return string
     */
    public function get_current_language() {
        // Check if WPML is active
        if ( $this->is_wpml_active() ) {
            return apply_filters( 'wpml_current_language', null );
        }
        
        // Check if Polylang is active
        if ( $this->is_polylang_active() ) {
            return pll_current_language();
        }
        
        // Return default language
        return get_locale();
    }

    /**
     * Get default language
     *
     * @return string
     */
    public function get_default_language() {
        // Check if WPML is active
        if ( $this->is_wpml_active() ) {
            return apply_filters( 'wpml_default_language', null );
        }
        
        // Check if Polylang is active
        if ( $this->is_polylang_active() ) {
            return pll_default_language();
        }
        
        // Return default language
        return get_locale();
    }

    /**
     * Get languages
     *
     * @return array
     */
    public function get_languages() {
        // Check if WPML is active
        if ( $this->is_wpml_active() ) {
            $languages = apply_filters( 'wpml_active_languages', null, array( 'skip_missing' => 0 ) );
            $result = array();
            
            foreach ( $languages as $code => $language ) {
                $result[ $code ] = array(
                    'code' => $code,
                    'name' => $language['native_name'],
                    'flag' => $language['country_flag_url'],
                    'url' => $language['url'],
                    'active' => $language['active'],
                );
            }
            
            return $result;
        }
        
        // Check if Polylang is active
        if ( $this->is_polylang_active() ) {
            $languages = pll_languages_list( array( 'fields' => 'all' ) );
            $result = array();
            
            foreach ( $languages as $language ) {
                $result[ $language->slug ] = array(
                    'code' => $language->slug,
                    'name' => $language->name,
                    'flag' => $language->flag_url,
                    'url' => $language->home_url,
                    'active' => $language->slug === pll_current_language(),
                );
            }
            
            return $result;
        }
        
        // Return default language
        return array(
            get_locale() => array(
                'code' => get_locale(),
                'name' => get_bloginfo( 'language' ),
                'flag' => '',
                'url' => home_url(),
                'active' => true,
            ),
        );
    }

    /**
     * Get current URL
     *
     * @return string
     */
    public function get_current_url() {
        global $wp;
        return home_url( add_query_arg( array(), $wp->request ) );
    }

    /**
     * Get translated URL
     *
     * @param string $url URL
     * @param string $language Language code
     * @return string
     */
    public function get_translated_url( $url, $language ) {
        // Check if WPML is active
        if ( $this->is_wpml_active() ) {
            return apply_filters( 'wpml_permalink', $url, $language );
        }
        
        // Check if Polylang is active
        if ( $this->is_polylang_active() ) {
            return pll_translate_url( $url, $language );
        }
        
        // Return original URL
        return $url;
    }
}