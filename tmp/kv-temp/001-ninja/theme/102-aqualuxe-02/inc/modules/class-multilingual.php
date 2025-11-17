<?php
/**
 * Multilingual Module
 *
 * Handles theme multilingual support
 *
 * @package AquaLuxe\Modules
 * @since 1.0.0
 */

namespace AquaLuxe\Modules;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Multilingual
 *
 * Implements multilingual support for the theme
 *
 * @since 1.0.0
 */
class Multilingual {

    /**
     * Supported languages
     *
     * @var array
     */
    private $supported_languages = array();

    /**
     * Current language
     *
     * @var string
     */
    private $current_language = 'en';

    /**
     * Initialize the multilingual module
     *
     * @since 1.0.0
     */
    public function init() {
        $this->setup_languages();
        
        add_action( 'init', array( $this, 'setup_text_domain' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_language_scripts' ) );
        add_action( 'wp_head', array( $this, 'add_language_meta' ) );
        add_filter( 'locale', array( $this, 'set_locale' ) );
        
        // Language switcher
        add_action( 'wp_footer', array( $this, 'render_language_switcher' ) );
        add_action( 'wp_ajax_aqualuxe_switch_language', array( $this, 'switch_language' ) );
        add_action( 'wp_ajax_nopriv_aqualuxe_switch_language', array( $this, 'switch_language' ) );
        
        // RTL support
        add_action( 'wp_head', array( $this, 'add_rtl_support' ) );
        
        // URL structure
        add_filter( 'rewrite_rules_array', array( $this, 'add_language_rewrite_rules' ) );
        add_filter( 'query_vars', array( $this, 'add_language_query_vars' ) );
    }

    /**
     * Setup supported languages
     *
     * @since 1.0.0
     */
    private function setup_languages() {
        $this->supported_languages = array(
            'en' => array(
                'name'      => 'English',
                'native'    => 'English',
                'code'      => 'en',
                'locale'    => 'en_US',
                'direction' => 'ltr',
                'flag'      => '🇺🇸',
            ),
            'es' => array(
                'name'      => 'Spanish',
                'native'    => 'Español',
                'code'      => 'es',
                'locale'    => 'es_ES',
                'direction' => 'ltr',
                'flag'      => '🇪🇸',
            ),
            'fr' => array(
                'name'      => 'French',
                'native'    => 'Français',
                'code'      => 'fr',
                'locale'    => 'fr_FR',
                'direction' => 'ltr',
                'flag'      => '🇫🇷',
            ),
            'de' => array(
                'name'      => 'German',
                'native'    => 'Deutsch',
                'code'      => 'de',
                'locale'    => 'de_DE',
                'direction' => 'ltr',
                'flag'      => '🇩🇪',
            ),
            'ar' => array(
                'name'      => 'Arabic',
                'native'    => 'العربية',
                'code'      => 'ar',
                'locale'    => 'ar',
                'direction' => 'rtl',
                'flag'      => '🇸🇦',
            ),
            'zh' => array(
                'name'      => 'Chinese',
                'native'    => '中文',
                'code'      => 'zh',
                'locale'    => 'zh_CN',
                'direction' => 'ltr',
                'flag'      => '🇨🇳',
            ),
            'ja' => array(
                'name'      => 'Japanese',
                'native'    => '日本語',
                'code'      => 'ja',
                'locale'    => 'ja',
                'direction' => 'ltr',
                'flag'      => '🇯🇵',
            ),
        );

        // Filter supported languages
        $this->supported_languages = apply_filters( 'aqualuxe_supported_languages', $this->supported_languages );
        
        // Set current language
        $this->current_language = $this->detect_language();
    }

    /**
     * Detect current language
     *
     * @since 1.0.0
     * @return string
     */
    private function detect_language() {
        // Check URL parameter
        if ( isset( $_GET['lang'] ) ) {
            $lang = sanitize_key( $_GET['lang'] );
            if ( isset( $this->supported_languages[ $lang ] ) ) {
                setcookie( 'aqualuxe_language', $lang, time() + YEAR_IN_SECONDS, '/' );
                return $lang;
            }
        }

        // Check cookie
        if ( isset( $_COOKIE['aqualuxe_language'] ) ) {
            $lang = sanitize_key( $_COOKIE['aqualuxe_language'] );
            if ( isset( $this->supported_languages[ $lang ] ) ) {
                return $lang;
            }
        }

        // Check browser language
        if ( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
            $browser_languages = explode( ',', $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
            foreach ( $browser_languages as $browser_lang ) {
                $lang_code = substr( trim( $browser_lang ), 0, 2 );
                if ( isset( $this->supported_languages[ $lang_code ] ) ) {
                    return $lang_code;
                }
            }
        }

        // Default to English
        return 'en';
    }

    /**
     * Setup text domain
     *
     * @since 1.0.0
     */
    public function setup_text_domain() {
        load_theme_textdomain( 'aqualuxe', get_template_directory() . '/languages' );
        
        // Load language-specific text domain
        if ( $this->current_language !== 'en' ) {
            $language_file = get_template_directory() . '/languages/aqualuxe-' . $this->current_language . '.mo';
            if ( file_exists( $language_file ) ) {
                load_textdomain( 'aqualuxe', $language_file );
            }
        }
    }

    /**
     * Enqueue language-specific scripts
     *
     * @since 1.0.0
     */
    public function enqueue_language_scripts() {
        wp_localize_script( 'aqualuxe-script', 'aqualuxe_i18n', array(
            'current_language' => $this->current_language,
            'languages'        => $this->supported_languages,
            'rtl'              => $this->is_rtl(),
            'translations'     => array(
                'loading'           => esc_html__( 'Loading...', 'aqualuxe' ),
                'error'             => esc_html__( 'An error occurred.', 'aqualuxe' ),
                'success'           => esc_html__( 'Success!', 'aqualuxe' ),
                'confirm'           => esc_html__( 'Are you sure?', 'aqualuxe' ),
                'cancel'            => esc_html__( 'Cancel', 'aqualuxe' ),
                'close'             => esc_html__( 'Close', 'aqualuxe' ),
                'search'            => esc_html__( 'Search...', 'aqualuxe' ),
                'no_results'        => esc_html__( 'No results found.', 'aqualuxe' ),
                'load_more'         => esc_html__( 'Load More', 'aqualuxe' ),
                'read_more'         => esc_html__( 'Read More', 'aqualuxe' ),
                'switch_language'   => esc_html__( 'Switch Language', 'aqualuxe' ),
            ),
        ) );
    }

    /**
     * Add language meta tags
     *
     * @since 1.0.0
     */
    public function add_language_meta() {
        $current_lang = $this->supported_languages[ $this->current_language ];
        
        echo '<html lang="' . esc_attr( $current_lang['code'] ) . '" dir="' . esc_attr( $current_lang['direction'] ) . '">' . "\n";
        echo '<meta charset="utf-8">' . "\n";
        
        // Add hreflang tags for alternate languages
        foreach ( $this->supported_languages as $lang_code => $language ) {
            $url = $this->get_language_url( $lang_code );
            echo '<link rel="alternate" hreflang="' . esc_attr( $lang_code ) . '" href="' . esc_url( $url ) . '">' . "\n";
        }
        
        // Add x-default for English
        $default_url = $this->get_language_url( 'en' );
        echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $default_url ) . '">' . "\n";
    }

    /**
     * Set locale based on current language
     *
     * @since 1.0.0
     * @param string $locale Current locale
     * @return string
     */
    public function set_locale( $locale ) {
        if ( isset( $this->supported_languages[ $this->current_language ] ) ) {
            return $this->supported_languages[ $this->current_language ]['locale'];
        }
        
        return $locale;
    }

    /**
     * Add RTL support
     *
     * @since 1.0.0
     */
    public function add_rtl_support() {
        if ( $this->is_rtl() ) {
            echo '<link rel="stylesheet" href="' . esc_url( get_template_directory_uri() . '/assets/dist/css/rtl.css' ) . '" type="text/css">' . "\n";
        }
    }

    /**
     * Check if current language is RTL
     *
     * @since 1.0.0
     * @return bool
     */
    public function is_rtl() {
        $current_lang = $this->supported_languages[ $this->current_language ];
        return $current_lang['direction'] === 'rtl';
    }

    /**
     * Render language switcher
     *
     * @since 1.0.0
     */
    public function render_language_switcher() {
        if ( count( $this->supported_languages ) <= 1 ) {
            return;
        }
        
        ?>
        <div id="language-switcher" class="language-switcher fixed bottom-4 right-4 z-50">
            <button type="button" class="language-toggle bg-white dark:bg-gray-800 shadow-lg rounded-full p-3 text-gray-700 dark:text-gray-300 hover:text-primary-600 transition-colors" aria-label="<?php esc_attr_e( 'Switch Language', 'aqualuxe' ); ?>">
                <span class="current-language-flag text-xl">
                    <?php echo esc_html( $this->supported_languages[ $this->current_language ]['flag'] ); ?>
                </span>
            </button>
            
            <div class="language-dropdown hidden absolute bottom-full right-0 mb-2 bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden min-w-40">
                <?php foreach ( $this->supported_languages as $lang_code => $language ) : ?>
                    <button type="button" 
                            class="language-option w-full px-4 py-3 text-left hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center space-x-3 <?php echo $lang_code === $this->current_language ? 'bg-primary-50 dark:bg-primary-900 text-primary-600' : 'text-gray-700 dark:text-gray-300'; ?>"
                            data-language="<?php echo esc_attr( $lang_code ); ?>">
                        <span class="flag text-lg"><?php echo esc_html( $language['flag'] ); ?></span>
                        <span class="name text-sm font-medium"><?php echo esc_html( $language['native'] ); ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const switcher = document.getElementById('language-switcher');
            const toggle = switcher.querySelector('.language-toggle');
            const dropdown = switcher.querySelector('.language-dropdown');
            const options = switcher.querySelectorAll('.language-option');
            
            toggle.addEventListener('click', function() {
                dropdown.classList.toggle('hidden');
            });
            
            document.addEventListener('click', function(e) {
                if (!switcher.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
            
            options.forEach(function(option) {
                option.addEventListener('click', function() {
                    const language = this.dataset.language;
                    
                    fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            action: 'aqualuxe_switch_language',
                            language: language,
                            nonce: '<?php echo esc_js( wp_create_nonce( 'aqualuxe_language_nonce' ) ); ?>'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        }
                    });
                });
            });
        });
        </script>
        <?php
    }

    /**
     * Switch language via AJAX
     *
     * @since 1.0.0
     */
    public function switch_language() {
        if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'aqualuxe_language_nonce' ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Security check failed.', 'aqualuxe' ) ) );
        }

        $language = sanitize_key( $_POST['language'] ?? '' );
        
        if ( ! isset( $this->supported_languages[ $language ] ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid language.', 'aqualuxe' ) ) );
        }

        // Set language cookie
        setcookie( 'aqualuxe_language', $language, time() + YEAR_IN_SECONDS, '/' );
        
        wp_send_json_success( array(
            'message' => esc_html__( 'Language switched successfully.', 'aqualuxe' ),
            'language' => $language,
            'redirect_url' => $this->get_language_url( $language ),
        ) );
    }

    /**
     * Get URL for a specific language
     *
     * @since 1.0.0
     * @param string $language Language code
     * @return string
     */
    private function get_language_url( $language ) {
        $current_url = home_url( $_SERVER['REQUEST_URI'] ?? '/' );
        
        // Remove existing language parameter
        $current_url = remove_query_arg( 'lang', $current_url );
        
        // Add language parameter
        if ( $language !== 'en' ) {
            $current_url = add_query_arg( 'lang', $language, $current_url );
        }
        
        return $current_url;
    }

    /**
     * Add language rewrite rules
     *
     * @since 1.0.0
     * @param array $rules Existing rewrite rules
     * @return array
     */
    public function add_language_rewrite_rules( $rules ) {
        $new_rules = array();
        
        foreach ( $this->supported_languages as $lang_code => $language ) {
            if ( $lang_code === 'en' ) {
                continue; // Skip default language
            }
            
            $new_rules[ '^' . $lang_code . '/(.*)$' ] = 'index.php?lang=' . $lang_code . '&pagename=$matches[1]';
            $new_rules[ '^' . $lang_code . '/?$' ] = 'index.php?lang=' . $lang_code;
        }
        
        return array_merge( $new_rules, $rules );
    }

    /**
     * Add language query vars
     *
     * @since 1.0.0
     * @param array $vars Query vars
     * @return array
     */
    public function add_language_query_vars( $vars ) {
        $vars[] = 'lang';
        return $vars;
    }

    /**
     * Get current language
     *
     * @since 1.0.0
     * @return string
     */
    public function get_current_language() {
        return $this->current_language;
    }

    /**
     * Get supported languages
     *
     * @since 1.0.0
     * @return array
     */
    public function get_supported_languages() {
        return $this->supported_languages;
    }

    /**
     * Translate string
     *
     * @since 1.0.0
     * @param string $string String to translate
     * @param string $domain Text domain
     * @return string
     */
    public function translate( $string, $domain = 'aqualuxe' ) {
        return __( $string, $domain );
    }

    /**
     * Get localized date format
     *
     * @since 1.0.0
     * @return string
     */
    public function get_date_format() {
        $formats = array(
            'en' => 'F j, Y',
            'es' => 'j \d\e F \d\e Y',
            'fr' => 'j F Y',
            'de' => 'j. F Y',
            'ar' => 'j F Y',
            'zh' => 'Y年n月j日',
            'ja' => 'Y年n月j日',
        );
        
        return $formats[ $this->current_language ] ?? $formats['en'];
    }

    /**
     * Get localized number format
     *
     * @since 1.0.0
     * @param float $number Number to format
     * @return string
     */
    public function format_number( $number ) {
        $formats = array(
            'en' => array( 'decimals' => 2, 'dec_point' => '.', 'thousands_sep' => ',' ),
            'es' => array( 'decimals' => 2, 'dec_point' => ',', 'thousands_sep' => '.' ),
            'fr' => array( 'decimals' => 2, 'dec_point' => ',', 'thousands_sep' => ' ' ),
            'de' => array( 'decimals' => 2, 'dec_point' => ',', 'thousands_sep' => '.' ),
            'ar' => array( 'decimals' => 2, 'dec_point' => '.', 'thousands_sep' => ',' ),
            'zh' => array( 'decimals' => 2, 'dec_point' => '.', 'thousands_sep' => ',' ),
            'ja' => array( 'decimals' => 2, 'dec_point' => '.', 'thousands_sep' => ',' ),
        );
        
        $format = $formats[ $this->current_language ] ?? $formats['en'];
        
        return number_format( $number, $format['decimals'], $format['dec_point'], $format['thousands_sep'] );
    }
}