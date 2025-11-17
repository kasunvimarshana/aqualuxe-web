<?php
/**
 * Multilingual Support Module
 *
 * @package AquaLuxe
 * @subpackage Modules/Multilingual
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Multilingual Support Module Class
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
    protected $module_description = 'Adds multilingual support with language switcher and RTL compatibility';

    /**
     * Module version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $dependencies = array();

    /**
     * Module settings
     *
     * @var array
     */
    protected $settings = array(
        'enabled_languages' => array( 'en_US' ),
        'default_language' => 'en_US',
        'switcher_position' => 'header',
        'switcher_style' => 'dropdown',
        'show_flags' => true,
        'auto_detect' => true,
        'rtl_support' => true,
    );

    /**
     * Available languages
     *
     * @var array
     */
    private $available_languages = array();

    /**
     * Current language
     *
     * @var string
     */
    private $current_language = '';

    /**
     * Initialize the module
     *
     * @return void
     */
    public function init() {
        // Load module settings
        $this->load_settings();

        // Set available languages
        $this->set_available_languages();

        // Set current language
        $this->set_current_language();

        // Include module files
        $this->include_files();
    }

    /**
     * Include module files
     *
     * @return void
     */
    private function include_files() {
        // Include helper functions
        require_once dirname( __FILE__ ) . '/includes/helpers.php';

        // Include template functions
        require_once dirname( __FILE__ ) . '/includes/template-functions.php';

        // Include WPML compatibility if available
        if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
            require_once dirname( __FILE__ ) . '/includes/wpml-compatibility.php';
        }

        // Include Polylang compatibility if available
        if ( defined( 'POLYLANG_VERSION' ) ) {
            require_once dirname( __FILE__ ) . '/includes/polylang-compatibility.php';
        }
    }

    /**
     * Setup module hooks
     *
     * @return void
     */
    public function setup_hooks() {
        // Enqueue scripts and styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

        // Add language switcher to header
        if ( 'header' === $this->settings['switcher_position'] ) {
            add_action( 'aqualuxe_header_after_navigation', array( $this, 'render_language_switcher' ) );
        }

        // Add language switcher to footer
        if ( 'footer' === $this->settings['switcher_position'] ) {
            add_action( 'aqualuxe_footer_before_widgets', array( $this, 'render_language_switcher' ) );
        }

        // Add RTL support
        if ( $this->settings['rtl_support'] ) {
            add_filter( 'body_class', array( $this, 'add_rtl_body_class' ) );
            add_action( 'wp_head', array( $this, 'add_rtl_inline_css' ) );
        }

        // Add language attributes to html tag
        add_filter( 'language_attributes', array( $this, 'language_attributes' ) );

        // Register widget
        add_action( 'widgets_init', array( $this, 'register_widgets' ) );

        // Register REST API endpoints
        add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
    }

    /**
     * Set available languages
     *
     * @return void
     */
    private function set_available_languages() {
        // Default language
        $this->available_languages = array(
            'en_US' => array(
                'name' => __( 'English (US)', 'aqualuxe' ),
                'native_name' => 'English (US)',
                'flag' => 'us',
                'locale' => 'en_US',
                'is_rtl' => false,
            ),
        );

        // Get available languages from WordPress
        $wp_languages = get_available_languages();
        
        // Add WordPress languages
        if ( ! empty( $wp_languages ) ) {
            foreach ( $wp_languages as $locale ) {
                $language_data = wp_get_available_translations();
                
                if ( isset( $language_data[ $locale ] ) ) {
                    $this->available_languages[ $locale ] = array(
                        'name' => $language_data[ $locale ]['english_name'],
                        'native_name' => $language_data[ $locale ]['native_name'],
                        'flag' => strtolower( substr( $locale, -2 ) ),
                        'locale' => $locale,
                        'is_rtl' => ( isset( $language_data[ $locale ]['is_rtl'] ) && $language_data[ $locale ]['is_rtl'] ),
                    );
                }
            }
        }

        // Add common languages if not already available
        $common_languages = array(
            'es_ES' => array(
                'name' => __( 'Spanish', 'aqualuxe' ),
                'native_name' => 'Español',
                'flag' => 'es',
                'locale' => 'es_ES',
                'is_rtl' => false,
            ),
            'fr_FR' => array(
                'name' => __( 'French', 'aqualuxe' ),
                'native_name' => 'Français',
                'flag' => 'fr',
                'locale' => 'fr_FR',
                'is_rtl' => false,
            ),
            'de_DE' => array(
                'name' => __( 'German', 'aqualuxe' ),
                'native_name' => 'Deutsch',
                'flag' => 'de',
                'locale' => 'de_DE',
                'is_rtl' => false,
            ),
            'it_IT' => array(
                'name' => __( 'Italian', 'aqualuxe' ),
                'native_name' => 'Italiano',
                'flag' => 'it',
                'locale' => 'it_IT',
                'is_rtl' => false,
            ),
            'pt_BR' => array(
                'name' => __( 'Portuguese (Brazil)', 'aqualuxe' ),
                'native_name' => 'Português do Brasil',
                'flag' => 'br',
                'locale' => 'pt_BR',
                'is_rtl' => false,
            ),
            'ar' => array(
                'name' => __( 'Arabic', 'aqualuxe' ),
                'native_name' => 'العربية',
                'flag' => 'sa',
                'locale' => 'ar',
                'is_rtl' => true,
            ),
            'ja' => array(
                'name' => __( 'Japanese', 'aqualuxe' ),
                'native_name' => '日本語',
                'flag' => 'jp',
                'locale' => 'ja',
                'is_rtl' => false,
            ),
            'zh_CN' => array(
                'name' => __( 'Chinese (Simplified)', 'aqualuxe' ),
                'native_name' => '简体中文',
                'flag' => 'cn',
                'locale' => 'zh_CN',
                'is_rtl' => false,
            ),
        );

        foreach ( $common_languages as $locale => $language ) {
            if ( ! isset( $this->available_languages[ $locale ] ) ) {
                $this->available_languages[ $locale ] = $language;
            }
        }

        // Filter available languages
        $this->available_languages = apply_filters( 'aqualuxe_available_languages', $this->available_languages );
    }

    /**
     * Set current language
     *
     * @return void
     */
    private function set_current_language() {
        // Default to WordPress locale
        $this->current_language = get_locale();

        // Check if we have a cookie
        if ( isset( $_COOKIE['aqualuxe_language'] ) ) {
            $cookie_language = sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_language'] ) );
            
            // Verify the language exists
            if ( isset( $this->available_languages[ $cookie_language ] ) ) {
                $this->current_language = $cookie_language;
            }
        }

        // Check if we have a query parameter
        if ( isset( $_GET['lang'] ) ) {
            $query_language = sanitize_text_field( wp_unslash( $_GET['lang'] ) );
            
            // Verify the language exists
            if ( isset( $this->available_languages[ $query_language ] ) ) {
                $this->current_language = $query_language;
                
                // Set cookie for 30 days
                setcookie( 'aqualuxe_language', $query_language, time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
            }
        }

        // Check for WPML
        if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
            $wpml_language = ICL_LANGUAGE_CODE;
            
            // Convert WPML language code to locale
            foreach ( $this->available_languages as $locale => $language ) {
                if ( strpos( $locale, $wpml_language . '_' ) === 0 || $locale === $wpml_language ) {
                    $this->current_language = $locale;
                    break;
                }
            }
        }

        // Check for Polylang
        if ( function_exists( 'pll_current_language' ) ) {
            $pll_language = pll_current_language( 'locale' );
            
            if ( $pll_language && isset( $this->available_languages[ $pll_language ] ) ) {
                $this->current_language = $pll_language;
            }
        }

        // Auto detect browser language if enabled
        if ( $this->settings['auto_detect'] && ! isset( $_COOKIE['aqualuxe_language'] ) && ! isset( $_GET['lang'] ) ) {
            $browser_language = $this->get_browser_language();
            
            if ( $browser_language && isset( $this->available_languages[ $browser_language ] ) ) {
                $this->current_language = $browser_language;
                
                // Set cookie for 30 days
                setcookie( 'aqualuxe_language', $browser_language, time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
            }
        }

        // Filter current language
        $this->current_language = apply_filters( 'aqualuxe_current_language', $this->current_language );
    }

    /**
     * Get browser language
     *
     * @return string|false
     */
    private function get_browser_language() {
        if ( ! isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
            return false;
        }

        $browser_languages = explode( ',', sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) );
        
        if ( empty( $browser_languages ) ) {
            return false;
        }

        $browser_language = strtolower( substr( $browser_languages[0], 0, 2 ) );
        
        // Find a matching language
        foreach ( $this->available_languages as $locale => $language ) {
            if ( strpos( strtolower( $locale ), $browser_language ) === 0 ) {
                return $locale;
            }
        }

        return false;
    }

    /**
     * Enqueue assets
     *
     * @return void
     */
    public function enqueue_assets() {
        // Enqueue language switcher script
        wp_enqueue_script(
            'aqualuxe-language-switcher',
            AQUALUXE_THEME_URI . 'modules/multilingual/assets/js/language-switcher.js',
            array( 'jquery' ),
            $this->version,
            true
        );

        // Localize script
        wp_localize_script(
            'aqualuxe-language-switcher',
            'aqualuxeLanguage',
            array(
                'currentLanguage' => $this->current_language,
                'defaultLanguage' => $this->settings['default_language'],
                'nonce'          => wp_create_nonce( 'aqualuxe_language_nonce' ),
                'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
            )
        );

        // Enqueue language switcher styles
        wp_enqueue_style(
            'aqualuxe-language-switcher',
            AQUALUXE_THEME_URI . 'modules/multilingual/assets/css/language-switcher.css',
            array(),
            $this->version
        );

        // Enqueue RTL styles if needed
        if ( $this->settings['rtl_support'] && $this->is_rtl() ) {
            wp_enqueue_style(
                'aqualuxe-rtl',
                AQUALUXE_THEME_URI . 'modules/multilingual/assets/css/rtl.css',
                array(),
                $this->version
            );
        }
    }

    /**
     * Render language switcher
     *
     * @return void
     */
    public function render_language_switcher() {
        // Get enabled languages
        $enabled_languages = $this->get_enabled_languages();
        
        if ( empty( $enabled_languages ) ) {
            return;
        }

        // Get current language
        $current_language = $this->get_current_language();
        
        // Get switcher style
        $switcher_style = $this->settings['switcher_style'];
        
        // Get show flags setting
        $show_flags = $this->settings['show_flags'];
        
        // Start output buffering
        ob_start();
        ?>
        <div class="language-switcher language-switcher--<?php echo esc_attr( $switcher_style ); ?>">
            <?php if ( 'dropdown' === $switcher_style ) : ?>
                <div class="language-switcher__dropdown">
                    <button class="language-switcher__current" aria-haspopup="true" aria-expanded="false">
                        <?php if ( $show_flags && isset( $current_language['flag'] ) ) : ?>
                            <span class="language-switcher__flag language-switcher__flag--<?php echo esc_attr( $current_language['flag'] ); ?>"></span>
                        <?php endif; ?>
                        <span class="language-switcher__name"><?php echo esc_html( $current_language['native_name'] ); ?></span>
                        <span class="language-switcher__arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </span>
                    </button>
                    <ul class="language-switcher__list">
                        <?php foreach ( $enabled_languages as $locale => $language ) : ?>
                            <li class="language-switcher__item<?php echo ( $locale === $this->current_language ) ? ' language-switcher__item--current' : ''; ?>">
                                <a href="<?php echo esc_url( $this->get_language_url( $locale ) ); ?>" class="language-switcher__link" data-lang="<?php echo esc_attr( $locale ); ?>">
                                    <?php if ( $show_flags && isset( $language['flag'] ) ) : ?>
                                        <span class="language-switcher__flag language-switcher__flag--<?php echo esc_attr( $language['flag'] ); ?>"></span>
                                    <?php endif; ?>
                                    <span class="language-switcher__name"><?php echo esc_html( $language['native_name'] ); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php else : ?>
                <ul class="language-switcher__list language-switcher__list--horizontal">
                    <?php foreach ( $enabled_languages as $locale => $language ) : ?>
                        <li class="language-switcher__item<?php echo ( $locale === $this->current_language ) ? ' language-switcher__item--current' : ''; ?>">
                            <a href="<?php echo esc_url( $this->get_language_url( $locale ) ); ?>" class="language-switcher__link" data-lang="<?php echo esc_attr( $locale ); ?>">
                                <?php if ( $show_flags && isset( $language['flag'] ) ) : ?>
                                    <span class="language-switcher__flag language-switcher__flag--<?php echo esc_attr( $language['flag'] ); ?>"></span>
                                <?php endif; ?>
                                <span class="language-switcher__name"><?php echo esc_html( $language['native_name'] ); ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php
        
        // Get the output buffer
        $output = ob_get_clean();
        
        // Echo the output
        echo $output;
    }

    /**
     * Get language URL
     *
     * @param string $locale Language locale.
     * @return string
     */
    public function get_language_url( $locale ) {
        // Check for WPML
        if ( defined( 'ICL_LANGUAGE_CODE' ) && function_exists( 'icl_get_languages' ) ) {
            $languages = icl_get_languages( 'skip_missing=0' );
            
            foreach ( $languages as $language ) {
                if ( strpos( $locale, $language['language_code'] . '_' ) === 0 || $locale === $language['language_code'] ) {
                    return $language['url'];
                }
            }
        }

        // Check for Polylang
        if ( function_exists( 'pll_the_languages' ) && function_exists( 'pll_current_language' ) ) {
            $languages = pll_the_languages( array( 'raw' => 1 ) );
            
            foreach ( $languages as $language ) {
                if ( $language['locale'] === $locale ) {
                    return $language['url'];
                }
            }
        }

        // Default: add query parameter
        $url = add_query_arg( 'lang', $locale );
        
        return $url;
    }

    /**
     * Get enabled languages
     *
     * @return array
     */
    public function get_enabled_languages() {
        $enabled_languages = array();
        
        foreach ( $this->settings['enabled_languages'] as $locale ) {
            if ( isset( $this->available_languages[ $locale ] ) ) {
                $enabled_languages[ $locale ] = $this->available_languages[ $locale ];
            }
        }
        
        return $enabled_languages;
    }

    /**
     * Get current language
     *
     * @return array
     */
    public function get_current_language() {
        if ( isset( $this->available_languages[ $this->current_language ] ) ) {
            return $this->available_languages[ $this->current_language ];
        }
        
        return $this->available_languages[ $this->settings['default_language'] ];
    }

    /**
     * Check if current language is RTL
     *
     * @return bool
     */
    public function is_rtl() {
        $current_language = $this->get_current_language();
        
        return isset( $current_language['is_rtl'] ) && $current_language['is_rtl'];
    }

    /**
     * Add RTL body class
     *
     * @param array $classes Body classes.
     * @return array
     */
    public function add_rtl_body_class( $classes ) {
        if ( $this->is_rtl() ) {
            $classes[] = 'rtl';
        }
        
        return $classes;
    }

    /**
     * Add RTL inline CSS
     *
     * @return void
     */
    public function add_rtl_inline_css() {
        if ( ! $this->is_rtl() ) {
            return;
        }

        $css = '
            html {
                direction: rtl;
                unicode-bidi: embed;
            }
        ';

        echo '<style id="aqualuxe-rtl-css">' . wp_strip_all_tags( $css ) . '</style>';
    }

    /**
     * Filter language attributes
     *
     * @param string $output Language attributes.
     * @return string
     */
    public function language_attributes( $output ) {
        $current_language = $this->get_current_language();
        
        if ( isset( $current_language['locale'] ) ) {
            $output = str_replace( 'lang="', 'lang="' . esc_attr( substr( $current_language['locale'], 0, 2 ) ), $output );
        }
        
        return $output;
    }

    /**
     * Register widgets
     *
     * @return void
     */
    public function register_widgets() {
        // Register language switcher widget
        register_widget( 'AquaLuxe_Language_Switcher_Widget' );
    }

    /**
     * Register REST API endpoints
     *
     * @return void
     */
    public function register_rest_routes() {
        register_rest_route(
            'aqualuxe/v1',
            '/languages',
            array(
                'methods'  => 'GET',
                'callback' => array( $this, 'get_languages_rest' ),
                'permission_callback' => '__return_true',
            )
        );

        register_rest_route(
            'aqualuxe/v1',
            '/languages/current',
            array(
                'methods'  => 'GET',
                'callback' => array( $this, 'get_current_language_rest' ),
                'permission_callback' => '__return_true',
            )
        );
    }

    /**
     * Get languages REST callback
     *
     * @param WP_REST_Request $request REST request.
     * @return WP_REST_Response
     */
    public function get_languages_rest( $request ) {
        return rest_ensure_response( $this->get_enabled_languages() );
    }

    /**
     * Get current language REST callback
     *
     * @param WP_REST_Request $request REST request.
     * @return WP_REST_Response
     */
    public function get_current_language_rest( $request ) {
        return rest_ensure_response( $this->get_current_language() );
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     * @return void
     */
    public function register_customizer_settings( $wp_customize ) {
        // Add Multilingual section
        $wp_customize->add_section(
            'aqualuxe_multilingual',
            array(
                'title'    => __( 'Multilingual Support', 'aqualuxe' ),
                'priority' => 35,
            )
        );

        // Enabled languages setting
        $wp_customize->add_setting(
            'aqualuxe_multilingual_enabled_languages',
            array(
                'default'           => $this->settings['enabled_languages'],
                'type'              => 'option',
                'sanitize_callback' => array( $this, 'sanitize_languages' ),
            )
        );

        // Create a list of available languages for the control
        $language_choices = array();
        foreach ( $this->available_languages as $locale => $language ) {
            $language_choices[ $locale ] = $language['name'] . ' (' . $language['native_name'] . ')';
        }

        $wp_customize->add_control(
            new WP_Customize_Multiple_Select_Control(
                $wp_customize,
                'aqualuxe_multilingual_enabled_languages',
                array(
                    'label'    => __( 'Enabled Languages', 'aqualuxe' ),
                    'section'  => 'aqualuxe_multilingual',
                    'choices'  => $language_choices,
                    'priority' => 10,
                )
            )
        );

        // Default language setting
        $wp_customize->add_setting(
            'aqualuxe_multilingual_default_language',
            array(
                'default'           => $this->settings['default_language'],
                'type'              => 'option',
                'sanitize_callback' => array( $this, 'sanitize_language' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_multilingual_default_language',
            array(
                'label'    => __( 'Default Language', 'aqualuxe' ),
                'section'  => 'aqualuxe_multilingual',
                'type'     => 'select',
                'choices'  => $language_choices,
                'priority' => 20,
            )
        );

        // Switcher position setting
        $wp_customize->add_setting(
            'aqualuxe_multilingual_switcher_position',
            array(
                'default'           => $this->settings['switcher_position'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_multilingual_switcher_position',
            array(
                'label'    => __( 'Language Switcher Position', 'aqualuxe' ),
                'section'  => 'aqualuxe_multilingual',
                'type'     => 'select',
                'choices'  => array(
                    'header' => __( 'Header', 'aqualuxe' ),
                    'footer' => __( 'Footer', 'aqualuxe' ),
                    'none'   => __( 'None (use widget or shortcode)', 'aqualuxe' ),
                ),
                'priority' => 30,
            )
        );

        // Switcher style setting
        $wp_customize->add_setting(
            'aqualuxe_multilingual_switcher_style',
            array(
                'default'           => $this->settings['switcher_style'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_multilingual_switcher_style',
            array(
                'label'    => __( 'Language Switcher Style', 'aqualuxe' ),
                'section'  => 'aqualuxe_multilingual',
                'type'     => 'select',
                'choices'  => array(
                    'dropdown' => __( 'Dropdown', 'aqualuxe' ),
                    'horizontal' => __( 'Horizontal List', 'aqualuxe' ),
                ),
                'priority' => 40,
            )
        );

        // Show flags setting
        $wp_customize->add_setting(
            'aqualuxe_multilingual_show_flags',
            array(
                'default'           => $this->settings['show_flags'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_multilingual_show_flags',
            array(
                'label'    => __( 'Show Language Flags', 'aqualuxe' ),
                'section'  => 'aqualuxe_multilingual',
                'type'     => 'checkbox',
                'priority' => 50,
            )
        );

        // Auto detect setting
        $wp_customize->add_setting(
            'aqualuxe_multilingual_auto_detect',
            array(
                'default'           => $this->settings['auto_detect'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_multilingual_auto_detect',
            array(
                'label'    => __( 'Auto Detect Browser Language', 'aqualuxe' ),
                'section'  => 'aqualuxe_multilingual',
                'type'     => 'checkbox',
                'priority' => 60,
            )
        );

        // RTL support setting
        $wp_customize->add_setting(
            'aqualuxe_multilingual_rtl_support',
            array(
                'default'           => $this->settings['rtl_support'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_multilingual_rtl_support',
            array(
                'label'    => __( 'RTL Language Support', 'aqualuxe' ),
                'section'  => 'aqualuxe_multilingual',
                'type'     => 'checkbox',
                'priority' => 70,
            )
        );
    }

    /**
     * Sanitize languages
     *
     * @param array $languages Languages.
     * @return array
     */
    public function sanitize_languages( $languages ) {
        if ( ! is_array( $languages ) ) {
            return array( 'en_US' );
        }

        $sanitized = array();
        
        foreach ( $languages as $language ) {
            if ( isset( $this->available_languages[ $language ] ) ) {
                $sanitized[] = $language;
            }
        }
        
        if ( empty( $sanitized ) ) {
            return array( 'en_US' );
        }
        
        return $sanitized;
    }

    /**
     * Sanitize language
     *
     * @param string $language Language.
     * @return string
     */
    public function sanitize_language( $language ) {
        if ( isset( $this->available_languages[ $language ] ) ) {
            return $language;
        }
        
        return 'en_US';
    }
}

/**
 * Language Switcher Widget
 */
class AquaLuxe_Language_Switcher_Widget extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_language_switcher',
            __( 'AquaLuxe Language Switcher', 'aqualuxe' ),
            array(
                'description' => __( 'Display a language switcher', 'aqualuxe' ),
            )
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     * @return void
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        $module = aqualuxe_get_module( 'multilingual' );
        
        if ( $module ) {
            $module->render_language_switcher();
        }
        
        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance.
     * @return void
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    /**
     * Update widget
     *
     * @param array $new_instance New instance.
     * @param array $old_instance Old instance.
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        
        return $instance;
    }
}

/**
 * Multiple Select Customizer Control
 */
if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'WP_Customize_Multiple_Select_Control' ) ) {
    /**
     * Multiple Select Customizer Control
     */
    class WP_Customize_Multiple_Select_Control extends WP_Customize_Control {
        /**
         * Control type
         *
         * @var string
         */
        public $type = 'multiple-select';

        /**
         * Render control
         *
         * @return void
         */
        public function render_content() {
            if ( empty( $this->choices ) ) {
                return;
            }
            ?>
            <label>
                <?php if ( ! empty( $this->label ) ) : ?>
                    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <?php endif; ?>
                
                <?php if ( ! empty( $this->description ) ) : ?>
                    <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                <?php endif; ?>
                
                <select <?php $this->link(); ?> multiple="multiple" style="height: 150px;">
                    <?php
                    foreach ( $this->choices as $value => $label ) {
                        $selected = in_array( $value, (array) $this->value(), true ) ? 'selected="selected"' : '';
                        echo '<option value="' . esc_attr( $value ) . '" ' . $selected . '>' . esc_html( $label ) . '</option>';
                    }
                    ?>
                </select>
            </label>
            <?php
        }
    }
}