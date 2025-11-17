<?php
/**
 * Multilingual Module Bootstrap
 *
 * @package AquaLuxe
 * @subpackage Modules\Multilingual
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Multilingual Module Class
 */
class AquaLuxe_Multilingual {
    
    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;
    
    /**
     * Initialize the module.
     */
    public function __construct() {
        add_action( 'init', array( $this, 'init' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_head', array( $this, 'add_language_attributes' ) );
        add_filter( 'body_class', array( $this, 'add_language_body_classes' ) );
        
        // Hook into different multilingual plugins
        $this->setup_plugin_integrations();
    }
    
    /**
     * Return an instance of this class.
     *
     * @return object A single instance of this class.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Initialize module.
     */
    public function init() {
        // Add language switcher to appropriate locations
        add_action( 'aqualuxe_header_right', array( $this, 'language_switcher' ) );
        add_action( 'aqualuxe_footer_bottom', array( $this, 'language_switcher' ) );
        
        // Register strings for translation
        $this->register_theme_strings();
    }
    
    /**
     * Enqueue module scripts and styles.
     */
    public function enqueue_scripts() {
        // Get mix manifest for cache busting
        $mix_manifest = $this->get_mix_manifest();
        
        wp_enqueue_script(
            'aqualuxe-multilingual',
            $this->get_asset_url( 'js/modules/multilingual.js', $mix_manifest ),
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script( 'aqualuxe-multilingual', 'aqualuxe_multilingual', array(
            'ajax_url'     => admin_url( 'admin-ajax.php' ),
            'nonce'        => wp_create_nonce( 'aqualuxe_multilingual_nonce' ),
            'current_lang' => $this->get_current_language(),
            'rtl_langs'    => $this->get_rtl_languages(),
            'strings'      => array(
                'loading'         => esc_html__( 'Loading...', 'aqualuxe' ),
                'language_switch' => esc_html__( 'Switch Language', 'aqualuxe' ),
            ),
        ) );
    }
    
    /**
     * Setup integrations with multilingual plugins.
     */
    private function setup_plugin_integrations() {
        // WPML
        if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
            add_action( 'init', array( $this, 'wpml_integration' ) );
        }
        
        // Polylang
        if ( function_exists( 'pll_languages_list' ) ) {
            add_action( 'init', array( $this, 'polylang_integration' ) );
        }
        
        // TranslatePress
        if ( class_exists( 'TRP_Translate_Press' ) ) {
            add_action( 'init', array( $this, 'translatepress_integration' ) );
        }
        
        // Weglot
        if ( class_exists( 'Weglot' ) ) {
            add_action( 'init', array( $this, 'weglot_integration' ) );
        }
    }
    
    /**
     * WPML integration.
     */
    public function wpml_integration() {
        // Register theme strings with WPML
        if ( function_exists( 'icl_register_string' ) ) {
            $theme_strings = $this->get_theme_strings();
            foreach ( $theme_strings as $key => $string ) {
                icl_register_string( 'aqualuxe', $key, $string );
            }
        }
    }
    
    /**
     * Polylang integration.
     */
    public function polylang_integration() {
        // Register theme strings with Polylang
        if ( function_exists( 'pll_register_string' ) ) {
            $theme_strings = $this->get_theme_strings();
            foreach ( $theme_strings as $key => $string ) {
                pll_register_string( $key, $string, 'aqualuxe' );
            }
        }
    }
    
    /**
     * TranslatePress integration.
     */
    public function translatepress_integration() {
        // TranslatePress works automatically with theme strings
        // Add any specific integrations here if needed
    }
    
    /**
     * Weglot integration.
     */
    public function weglot_integration() {
        // Weglot works automatically
        // Add any specific integrations here if needed
    }
    
    /**
     * Display language switcher.
     */
    public function language_switcher() {
        $languages = $this->get_available_languages();
        
        if ( empty( $languages ) || count( $languages ) < 2 ) {
            return;
        }
        
        $current_lang = $this->get_current_language();
        ?>
        <div class="language-switcher">
            <div class="language-switcher-toggle">
                <span class="current-language">
                    <?php echo esc_html( $languages[ $current_lang ]['native_name'] ?? $current_lang ); ?>
                </span>
                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </div>
            
            <div class="language-dropdown">
                <?php foreach ( $languages as $code => $language ) : ?>
                    <?php if ( $code !== $current_lang ) : ?>
                        <a href="<?php echo esc_url( $language['url'] ); ?>" 
                           class="language-link" 
                           data-lang="<?php echo esc_attr( $code ); ?>"
                           hreflang="<?php echo esc_attr( $code ); ?>">
                            <?php if ( ! empty( $language['flag'] ) ) : ?>
                                <img src="<?php echo esc_url( $language['flag'] ); ?>" 
                                     alt="<?php echo esc_attr( $language['native_name'] ); ?>" 
                                     class="language-flag w-4 h-4 mr-2">
                            <?php endif; ?>
                            <span class="language-name"><?php echo esc_html( $language['native_name'] ); ?></span>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Get available languages.
     *
     * @return array
     */
    public function get_available_languages() {
        $languages = array();
        
        // WPML
        if ( defined( 'ICL_SITEPRESS_VERSION' ) && function_exists( 'icl_get_languages' ) ) {
            $wpml_languages = icl_get_languages( 'skip_missing=0' );
            foreach ( $wpml_languages as $code => $language ) {
                $languages[ $code ] = array(
                    'code'        => $code,
                    'native_name' => $language['native_name'],
                    'url'         => $language['url'],
                    'flag'        => $language['country_flag_url'] ?? '',
                );
            }
        }
        
        // Polylang
        elseif ( function_exists( 'pll_languages_list' ) ) {
            $pll_languages = pll_languages_list( array( 'fields' => 'slug' ) );
            $pll_names = pll_languages_list( array( 'fields' => 'name' ) );
            $pll_urls = pll_languages_list( array( 'fields' => 'url' ) );
            
            foreach ( $pll_languages as $key => $code ) {
                $languages[ $code ] = array(
                    'code'        => $code,
                    'native_name' => $pll_names[ $key ] ?? $code,
                    'url'         => $pll_urls[ $key ] ?? home_url(),
                    'flag'        => '',
                );
            }
        }
        
        // TranslatePress
        elseif ( class_exists( 'TRP_Translate_Press' ) ) {
            $trp = TRP_Translate_Press::get_trp_instance();
            if ( $trp ) {
                $trp_languages = $trp->get_component( 'languages' );
                $published_languages = $trp_languages->get_language_names( $trp_languages->get_published_languages() );
                
                foreach ( $published_languages as $code => $name ) {
                    $languages[ $code ] = array(
                        'code'        => $code,
                        'native_name' => $name,
                        'url'         => $trp_languages->get_language_url( $code ),
                        'flag'        => '',
                    );
                }
            }
        }
        
        return $languages;
    }
    
    /**
     * Get current language.
     *
     * @return string
     */
    public function get_current_language() {
        // WPML
        if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
            return ICL_LANGUAGE_CODE;
        }
        
        // Polylang
        if ( function_exists( 'pll_current_language' ) ) {
            return pll_current_language() ?: 'en';
        }
        
        // TranslatePress
        if ( class_exists( 'TRP_Translate_Press' ) ) {
            $trp = TRP_Translate_Press::get_trp_instance();
            if ( $trp ) {
                $trp_languages = $trp->get_component( 'languages' );
                return $trp_languages->get_current_language();
            }
        }
        
        // WordPress default
        return substr( get_locale(), 0, 2 );
    }
    
    /**
     * Get RTL languages.
     *
     * @return array
     */
    public function get_rtl_languages() {
        return array( 'ar', 'he', 'fa', 'ur', 'yi', 'ji', 'iw', 'ku', 'ps', 'sd' );
    }
    
    /**
     * Add language attributes to head.
     */
    public function add_language_attributes() {
        $current_lang = $this->get_current_language();
        $rtl_languages = $this->get_rtl_languages();
        
        if ( in_array( $current_lang, $rtl_languages ) ) {
            echo '<meta name="language-direction" content="rtl">';
        }
        
        echo '<meta name="language" content="' . esc_attr( $current_lang ) . '">';
    }
    
    /**
     * Add language-specific body classes.
     *
     * @param array $classes Existing body classes.
     * @return array
     */
    public function add_language_body_classes( $classes ) {
        $current_lang = $this->get_current_language();
        $rtl_languages = $this->get_rtl_languages();
        
        $classes[] = 'lang-' . $current_lang;
        
        if ( in_array( $current_lang, $rtl_languages ) ) {
            $classes[] = 'rtl';
        }
        
        return $classes;
    }
    
    /**
     * Register theme strings for translation.
     */
    private function register_theme_strings() {
        $strings = $this->get_theme_strings();
        
        // Register with active multilingual plugin
        if ( defined( 'ICL_SITEPRESS_VERSION' ) && function_exists( 'icl_register_string' ) ) {
            foreach ( $strings as $key => $string ) {
                icl_register_string( 'aqualuxe', $key, $string );
            }
        } elseif ( function_exists( 'pll_register_string' ) ) {
            foreach ( $strings as $key => $string ) {
                pll_register_string( $key, $string, 'aqualuxe' );
            }
        }
    }
    
    /**
     * Get theme strings that need translation.
     *
     * @return array
     */
    private function get_theme_strings() {
        return array(
            'read_more'           => __( 'Read More', 'aqualuxe' ),
            'search_placeholder'  => __( 'Search...', 'aqualuxe' ),
            'no_results'          => __( 'No results found', 'aqualuxe' ),
            'load_more'           => __( 'Load More', 'aqualuxe' ),
            'contact_us'          => __( 'Contact Us', 'aqualuxe' ),
            'get_started'         => __( 'Get Started', 'aqualuxe' ),
            'learn_more'          => __( 'Learn More', 'aqualuxe' ),
            'view_all'            => __( 'View All', 'aqualuxe' ),
            'back_to_top'         => __( 'Back to Top', 'aqualuxe' ),
            'menu_toggle'         => __( 'Menu Toggle', 'aqualuxe' ),
            'close'               => __( 'Close', 'aqualuxe' ),
            'next'                => __( 'Next', 'aqualuxe' ),
            'previous'            => __( 'Previous', 'aqualuxe' ),
            'home'                => __( 'Home', 'aqualuxe' ),
        );
    }
    
    /**
     * Get mix manifest for asset versioning.
     *
     * @return array
     */
    private function get_mix_manifest() {
        $manifest_path = AQUALUXE_ASSETS_DIR . '/dist/mix-manifest.json';
        
        if ( file_exists( $manifest_path ) ) {
            return json_decode( file_get_contents( $manifest_path ), true );
        }
        
        return array();
    }
    
    /**
     * Get asset URL with proper versioning.
     *
     * @param string $asset
     * @param array  $manifest
     * @return string
     */
    private function get_asset_url( $asset, $manifest = array() ) {
        $asset_path = '/' . $asset;
        
        if ( isset( $manifest[ $asset_path ] ) ) {
            return AQUALUXE_ASSETS_URI . '/dist' . $manifest[ $asset_path ];
        }
        
        return AQUALUXE_ASSETS_URI . '/dist' . $asset_path;
    }
}

// Initialize multilingual module
AquaLuxe_Multilingual::get_instance();

/**
 * Helper function to get multilingual instance.
 *
 * @return AquaLuxe_Multilingual
 */
function aqualuxe_multilingual() {
    return AquaLuxe_Multilingual::get_instance();
}

/**
 * Template function to display language switcher.
 */
function aqualuxe_language_switcher() {
    aqualuxe_multilingual()->language_switcher();
}

/**
 * Get current language.
 *
 * @return string
 */
function aqualuxe_get_current_language() {
    return aqualuxe_multilingual()->get_current_language();
}

/**
 * Get available languages.
 *
 * @return array
 */
function aqualuxe_get_available_languages() {
    return aqualuxe_multilingual()->get_available_languages();
}