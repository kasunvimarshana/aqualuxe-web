<?php
/**
 * Multilingual Class
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core;

/**
 * Multilingual Class
 * 
 * Handles multilingual functionality
 */
class Multilingual {
    /**
     * Instance of this class
     *
     * @var Multilingual
     */
    private static $instance = null;

    /**
     * Get the singleton instance
     *
     * @return Multilingual
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Register hooks
        add_action( 'after_setup_theme', [ $this, 'load_textdomain' ] );
        add_action( 'wp_head', [ $this, 'language_attributes' ] );
        add_filter( 'body_class', [ $this, 'language_body_class' ] );
        add_action( 'wp_footer', [ $this, 'language_switcher' ] );
        add_action( 'admin_menu', [ $this, 'add_language_menu' ] );
        add_action( 'admin_init', [ $this, 'register_language_settings' ] );
    }

    /**
     * Load textdomain
     */
    public function load_textdomain() {
        load_theme_textdomain( 'aqualuxe', AQUALUXE_DIR . 'languages' );
    }

    /**
     * Language attributes
     */
    public function language_attributes() {
        $language = $this->get_current_language();
        
        if ( $language ) {
            echo '<meta name="language" content="' . esc_attr( $language ) . '">' . "\n";
        }
    }

    /**
     * Language body class
     *
     * @param array $classes Body classes
     * @return array
     */
    public function language_body_class( $classes ) {
        $language = $this->get_current_language();
        
        if ( $language ) {
            $classes[] = 'language-' . $language;
        }
        
        return $classes;
    }

    /**
     * Language switcher
     */
    public function language_switcher() {
        if ( ! $this->is_multilingual_enabled() ) {
            return;
        }
        
        $languages = $this->get_available_languages();
        $current_language = $this->get_current_language();
        
        if ( empty( $languages ) ) {
            return;
        }
        
        // Get language switcher template
        Template::get_template_part( 'templates/global/language-switcher', null, [
            'languages'        => $languages,
            'current_language' => $current_language,
        ] );
    }

    /**
     * Add language menu
     */
    public function add_language_menu() {
        add_submenu_page(
            'aqualuxe',
            esc_html__( 'Languages', 'aqualuxe' ),
            esc_html__( 'Languages', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-languages',
            [ $this, 'language_settings_page' ]
        );
    }

    /**
     * Register language settings
     */
    public function register_language_settings() {
        register_setting(
            'aqualuxe_language_settings',
            'aqualuxe_language_settings',
            [
                'sanitize_callback' => [ $this, 'sanitize_language_settings' ],
            ]
        );
    }

    /**
     * Sanitize language settings
     *
     * @param array $settings Language settings
     * @return array
     */
    public function sanitize_language_settings( $settings ) {
        // Sanitize language settings
        return $settings;
    }

    /**
     * Language settings page
     */
    public function language_settings_page() {
        // Language settings page content
        include AQUALUXE_TEMPLATES_DIR . 'admin/language-settings.php';
    }

    /**
     * Check if multilingual is enabled
     *
     * @return bool
     */
    public function is_multilingual_enabled() {
        $theme_options = get_option( 'aqualuxe_theme_options', [] );
        
        return isset( $theme_options['enable_multilingual'] ) && $theme_options['enable_multilingual'];
    }

    /**
     * Get current language
     *
     * @return string
     */
    public function get_current_language() {
        $language = '';
        
        // Check if WPML is active
        if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
            $language = ICL_LANGUAGE_CODE;
        }
        
        // Check if Polylang is active
        if ( function_exists( 'pll_current_language' ) ) {
            $language = pll_current_language();
        }
        
        // Check if TranslatePress is active
        if ( function_exists( 'trp_get_current_language' ) ) {
            $language = trp_get_current_language();
        }
        
        // Check if qTranslate-X is active
        if ( function_exists( 'qtranxf_getLanguage' ) ) {
            $language = qtranxf_getLanguage();
        }
        
        // Check if Weglot is active
        if ( function_exists( 'weglot_get_current_language' ) ) {
            $language = weglot_get_current_language();
        }
        
        // If no language is found, use the site locale
        if ( ! $language ) {
            $language = get_locale();
        }
        
        return $language;
    }

    /**
     * Get available languages
     *
     * @return array
     */
    public function get_available_languages() {
        $languages = [];
        
        // Check if WPML is active
        if ( defined( 'ICL_LANGUAGE_CODE' ) && function_exists( 'icl_get_languages' ) ) {
            $wpml_languages = icl_get_languages( 'skip_missing=0' );
            
            if ( ! empty( $wpml_languages ) ) {
                foreach ( $wpml_languages as $language_code => $language_data ) {
                    $languages[ $language_code ] = [
                        'code'      => $language_code,
                        'name'      => $language_data['native_name'],
                        'url'       => $language_data['url'],
                        'flag'      => $language_data['country_flag_url'],
                        'active'    => $language_data['active'],
                        'translated' => $language_data['translated'],
                    ];
                }
            }
        }
        
        // Check if Polylang is active
        if ( function_exists( 'pll_languages_list' ) && function_exists( 'pll_the_languages' ) ) {
            $polylang_languages = pll_languages_list( [
                'fields' => '',
            ] );
            
            if ( ! empty( $polylang_languages ) ) {
                foreach ( $polylang_languages as $language ) {
                    $languages[ $language->slug ] = [
                        'code'      => $language->slug,
                        'name'      => $language->name,
                        'url'       => $language->home_url,
                        'flag'      => $language->flag_url,
                        'active'    => $language->slug === pll_current_language(),
                        'translated' => true,
                    ];
                }
            }
        }
        
        // Check if TranslatePress is active
        if ( function_exists( 'trp_get_languages' ) ) {
            $trp_languages = trp_get_languages();
            
            if ( ! empty( $trp_languages ) ) {
                foreach ( $trp_languages as $language_code => $language_name ) {
                    $languages[ $language_code ] = [
                        'code'      => $language_code,
                        'name'      => $language_name,
                        'url'       => add_query_arg( 'trp-edit-translation', $language_code, home_url() ),
                        'flag'      => '',
                        'active'    => $language_code === trp_get_current_language(),
                        'translated' => true,
                    ];
                }
            }
        }
        
        // Check if qTranslate-X is active
        if ( function_exists( 'qtranxf_getSortedLanguages' ) ) {
            $qtranslate_languages = qtranxf_getSortedLanguages();
            
            if ( ! empty( $qtranslate_languages ) ) {
                foreach ( $qtranslate_languages as $language_code ) {
                    $languages[ $language_code ] = [
                        'code'      => $language_code,
                        'name'      => qtranxf_getLanguageName( $language_code ),
                        'url'       => qtranxf_convertURL( '', $language_code ),
                        'flag'      => qtranxf_getLanguageFlag( $language_code ),
                        'active'    => $language_code === qtranxf_getLanguage(),
                        'translated' => true,
                    ];
                }
            }
        }
        
        // Check if Weglot is active
        if ( function_exists( 'weglot_get_languages_available' ) ) {
            $weglot_languages = weglot_get_languages_available();
            
            if ( ! empty( $weglot_languages ) ) {
                foreach ( $weglot_languages as $language ) {
                    $languages[ $language->getIso639() ] = [
                        'code'      => $language->getIso639(),
                        'name'      => $language->getLocalName(),
                        'url'       => weglot_get_current_language() === $language->getIso639() ? home_url() : weglot_create_url( home_url(), $language->getIso639() ),
                        'flag'      => '',
                        'active'    => weglot_get_current_language() === $language->getIso639(),
                        'translated' => true,
                    ];
                }
            }
        }
        
        // If no languages are found, use the site locale
        if ( empty( $languages ) ) {
            $locale = get_locale();
            
            $languages[ $locale ] = [
                'code'      => $locale,
                'name'      => $this->get_language_name( $locale ),
                'url'       => home_url(),
                'flag'      => '',
                'active'    => true,
                'translated' => true,
            ];
        }
        
        return $languages;
    }

    /**
     * Get language name
     *
     * @param string $locale Locale
     * @return string
     */
    private function get_language_name( $locale ) {
        $languages = [
            'af'    => 'Afrikaans',
            'ar'    => 'العربية',
            'ary'   => 'العربية المغربية',
            'as'    => 'অসমীয়া',
            'az'    => 'Azərbaycan dili',
            'azb'   => 'گؤنئی آذربایجان',
            'bel'   => 'Беларуская мова',
            'bg_BG' => 'Български',
            'bn_BD' => 'বাংলা',
            'bo'    => 'བོད་ཡིག',
            'bs_BA' => 'Bosanski',
            'ca'    => 'Català',
            'ceb'   => 'Cebuano',
            'cs_CZ' => 'Čeština',
            'cy'    => 'Cymraeg',
            'da_DK' => 'Dansk',
            'de_DE' => 'Deutsch',
            'de_CH' => 'Deutsch (Schweiz)',
            'de_AT' => 'Deutsch (Österreich)',
            'dzo'   => 'རྫོང་ཁ',
            'el'    => 'Ελληνικά',
            'en_US' => 'English (US)',
            'en_AU' => 'English (Australia)',
            'en_CA' => 'English (Canada)',
            'en_GB' => 'English (UK)',
            'en_NZ' => 'English (New Zealand)',
            'en_ZA' => 'English (South Africa)',
            'eo'    => 'Esperanto',
            'es_ES' => 'Español',
            'es_AR' => 'Español de Argentina',
            'es_CL' => 'Español de Chile',
            'es_CO' => 'Español de Colombia',
            'es_GT' => 'Español de Guatemala',
            'es_MX' => 'Español de México',
            'es_PE' => 'Español de Perú',
            'es_PR' => 'Español de Puerto Rico',
            'es_VE' => 'Español de Venezuela',
            'et'    => 'Eesti',
            'eu'    => 'Euskara',
            'fa_IR' => 'فارسی',
            'fa_AF' => 'فارسی افغانستان',
            'fi'    => 'Suomi',
            'fr_FR' => 'Français',
            'fr_BE' => 'Français de Belgique',
            'fr_CA' => 'Français du Canada',
            'fur'   => 'Friulian',
            'gd'    => 'Gàidhlig',
            'gl_ES' => 'Galego',
            'gu'    => 'ગુજરાતી',
            'haz'   => 'هزاره گی',
            'he_IL' => 'עִבְרִית',
            'hi_IN' => 'हिन्दी',
            'hr'    => 'Hrvatski',
            'hu_HU' => 'Magyar',
            'hy'    => 'Հայերեն',
            'id_ID' => 'Bahasa Indonesia',
            'is_IS' => 'Íslenska',
            'it_IT' => 'Italiano',
            'ja'    => '日本語',
            'jv_ID' => 'Basa Jawa',
            'ka_GE' => 'ქართული',
            'kab'   => 'Taqbaylit',
            'kk'    => 'Қазақ тілі',
            'km'    => 'ភាសាខ្មែរ',
            'kn'    => 'ಕನ್ನಡ',
            'ko_KR' => '한국어',
            'ckb'   => 'كوردی‎',
            'lo'    => 'ພາສາລາວ',
            'lt_LT' => 'Lietuvių kalba',
            'lv'    => 'Latviešu valoda',
            'mk_MK' => 'Македонски јазик',
            'ml_IN' => 'മലയാളം',
            'mn'    => 'Монгол',
            'mr'    => 'मराठी',
            'ms_MY' => 'Bahasa Melayu',
            'my_MM' => 'ဗမာစာ',
            'nb_NO' => 'Norsk bokmål',
            'ne_NP' => 'नेपाली',
            'nl_NL' => 'Nederlands',
            'nl_BE' => 'Nederlands (België)',
            'nn_NO' => 'Norsk nynorsk',
            'oci'   => 'Occitan',
            'pa_IN' => 'ਪੰਜਾਬੀ',
            'pl_PL' => 'Polski',
            'ps'    => 'پښتو',
            'pt_BR' => 'Português do Brasil',
            'pt_PT' => 'Português',
            'ro_RO' => 'Română',
            'ru_RU' => 'Русский',
            'sah'   => 'Сахалыы',
            'si_LK' => 'සිංහල',
            'sk_SK' => 'Slovenčina',
            'sl_SI' => 'Slovenščina',
            'sq'    => 'Shqip',
            'sr_RS' => 'Српски језик',
            'sv_SE' => 'Svenska',
            'szl'   => 'Ślōnskŏ gŏdka',
            'ta_IN' => 'தமிழ்',
            'te'    => 'తెలుగు',
            'th'    => 'ไทย',
            'tl'    => 'Tagalog',
            'tr_TR' => 'Türkçe',
            'tt_RU' => 'Татар теле',
            'tah'   => 'Reo Tahiti',
            'ug_CN' => 'ئۇيغۇرچە',
            'uk'    => 'Українська',
            'ur'    => 'اردو',
            'uz_UZ' => 'O'zbekcha',
            'vi'    => 'Tiếng Việt',
            'zh_CN' => '简体中文',
            'zh_TW' => '繁體中文',
            'zh_HK' => '香港中文版',
        ];
        
        return isset( $languages[ $locale ] ) ? $languages[ $locale ] : $locale;
    }

    /**
     * Translate string
     *
     * @param string $string String to translate
     * @param string $domain Text domain
     * @return string
     */
    public static function translate( $string, $domain = 'aqualuxe' ) {
        return __( $string, $domain );
    }

    /**
     * Translate and escape string
     *
     * @param string $string String to translate
     * @param string $domain Text domain
     * @return string
     */
    public static function translate_escape( $string, $domain = 'aqualuxe' ) {
        return esc_html__( $string, $domain );
    }

    /**
     * Translate and echo string
     *
     * @param string $string String to translate
     * @param string $domain Text domain
     */
    public static function translate_echo( $string, $domain = 'aqualuxe' ) {
        echo esc_html__( $string, $domain );
    }

    /**
     * Translate with context
     *
     * @param string $string  String to translate
     * @param string $context Context
     * @param string $domain  Text domain
     * @return string
     */
    public static function translate_with_context( $string, $context, $domain = 'aqualuxe' ) {
        return _x( $string, $context, $domain );
    }

    /**
     * Translate with context and escape
     *
     * @param string $string  String to translate
     * @param string $context Context
     * @param string $domain  Text domain
     * @return string
     */
    public static function translate_with_context_escape( $string, $context, $domain = 'aqualuxe' ) {
        return esc_html_x( $string, $context, $domain );
    }

    /**
     * Translate with context and echo
     *
     * @param string $string  String to translate
     * @param string $context Context
     * @param string $domain  Text domain
     */
    public static function translate_with_context_echo( $string, $context, $domain = 'aqualuxe' ) {
        echo esc_html_x( $string, $context, $domain );
    }

    /**
     * Translate plural
     *
     * @param string $single   Single string
     * @param string $plural   Plural string
     * @param int    $number   Number
     * @param string $domain   Text domain
     * @return string
     */
    public static function translate_plural( $single, $plural, $number, $domain = 'aqualuxe' ) {
        return _n( $single, $plural, $number, $domain );
    }

    /**
     * Translate plural and escape
     *
     * @param string $single   Single string
     * @param string $plural   Plural string
     * @param int    $number   Number
     * @param string $domain   Text domain
     * @return string
     */
    public static function translate_plural_escape( $single, $plural, $number, $domain = 'aqualuxe' ) {
        return esc_html( _n( $single, $plural, $number, $domain ) );
    }

    /**
     * Translate plural and echo
     *
     * @param string $single   Single string
     * @param string $plural   Plural string
     * @param int    $number   Number
     * @param string $domain   Text domain
     */
    public static function translate_plural_echo( $single, $plural, $number, $domain = 'aqualuxe' ) {
        echo esc_html( _n( $single, $plural, $number, $domain ) );
    }
}