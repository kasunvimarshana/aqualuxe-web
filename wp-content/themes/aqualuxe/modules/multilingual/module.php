<?php
/**
 * Multilingual Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
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
    protected $id = 'multilingual';

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Multilingual';

    /**
     * Module description
     *
     * @var string
     */
    protected $description = 'Adds multilingual support with language switcher.';

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
    protected $dependencies = [];

    /**
     * Supported plugins
     *
     * @var array
     */
    protected $supported_plugins = [
        'polylang' => [
            'file' => 'polylang/polylang.php',
            'class' => 'Polylang',
            'function' => 'pll_current_language',
        ],
        'wpml' => [
            'file' => 'sitepress-multilingual-cms/sitepress.php',
            'class' => 'SitePress',
            'constant' => 'ICL_SITEPRESS_VERSION',
        ],
        'translatepress' => [
            'file' => 'translatepress-multilingual/index.php',
            'class' => 'TRP_Translate_Press',
            'function' => 'trp_get_languages',
        ],
    ];

    /**
     * Active plugin
     *
     * @var string
     */
    protected $active_plugin = '';

    /**
     * Constructor
     */
    public function __construct() {
        // Call parent constructor
        parent::__construct();
        
        // Detect active multilingual plugin
        $this->detect_active_plugin();
    }

    /**
     * Initialize module
     */
    public function init() {
        // Call parent init
        parent::init();
        
        // Add language switcher to header
        add_action('aqualuxe_header_after_navigation', [$this, 'render_language_switcher']);
        
        // Add language switcher to footer
        add_action('aqualuxe_footer_widgets', [$this, 'render_footer_language_switcher']);
        
        // Add language switcher widget
        add_action('widgets_init', [$this, 'register_language_switcher_widget']);
        
        // Add language switcher shortcode
        add_shortcode('aqualuxe_language_switcher', [$this, 'language_switcher_shortcode']);
        
        // Add language attributes to html tag
        add_filter('language_attributes', [$this, 'language_attributes']);
        
        // Add hreflang links to head
        add_action('wp_head', [$this, 'add_hreflang_links']);
        
        // Add customizer settings
        add_action('customize_register', [$this, 'register_customizer_settings']);
    }

    /**
     * Register assets
     */
    public function register_assets() {
        // Register multilingual script
        wp_register_script(
            'aqualuxe-multilingual',
            $this->uri . 'assets/js/multilingual.js',
            ['jquery'],
            $this->version,
            true
        );
        
        // Localize script
        wp_localize_script('aqualuxe-multilingual', 'aqualuxeMultilingual', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-multilingual'),
        ]);
        
        // Enqueue multilingual script
        wp_enqueue_script('aqualuxe-multilingual');
        
        // Register multilingual styles
        wp_register_style(
            'aqualuxe-multilingual',
            $this->uri . 'assets/css/multilingual.css',
            [],
            $this->version
        );
        
        // Enqueue multilingual styles
        wp_enqueue_style('aqualuxe-multilingual');
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     */
    public function register_customizer_settings($wp_customize) {
        // Add multilingual section
        $wp_customize->add_section('aqualuxe_multilingual', [
            'title'    => __('Multilingual', 'aqualuxe'),
            'priority' => 35,
        ]);
        
        // Add language switcher position setting
        $wp_customize->add_setting('aqualuxe_multilingual_switcher_position', [
            'default'           => 'header',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        ]);
        
        // Add language switcher position control
        $wp_customize->add_control('aqualuxe_multilingual_switcher_position', [
            'label'    => __('Language Switcher Position', 'aqualuxe'),
            'section'  => 'aqualuxe_multilingual',
            'type'     => 'select',
            'choices'  => [
                'header' => __('Header', 'aqualuxe'),
                'footer' => __('Footer', 'aqualuxe'),
                'both'   => __('Both', 'aqualuxe'),
                'none'   => __('None', 'aqualuxe'),
            ],
            'priority' => 10,
        ]);
        
        // Add language switcher style setting
        $wp_customize->add_setting('aqualuxe_multilingual_switcher_style', [
            'default'           => 'dropdown',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        ]);
        
        // Add language switcher style control
        $wp_customize->add_control('aqualuxe_multilingual_switcher_style', [
            'label'    => __('Language Switcher Style', 'aqualuxe'),
            'section'  => 'aqualuxe_multilingual',
            'type'     => 'select',
            'choices'  => [
                'dropdown' => __('Dropdown', 'aqualuxe'),
                'list'     => __('List', 'aqualuxe'),
                'flags'    => __('Flags', 'aqualuxe'),
            ],
            'priority' => 20,
        ]);
        
        // Add language switcher show flags setting
        $wp_customize->add_setting('aqualuxe_multilingual_switcher_show_flags', [
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        ]);
        
        // Add language switcher show flags control
        $wp_customize->add_control('aqualuxe_multilingual_switcher_show_flags', [
            'label'    => __('Show Flags', 'aqualuxe'),
            'section'  => 'aqualuxe_multilingual',
            'type'     => 'checkbox',
            'priority' => 30,
        ]);
        
        // Add language switcher show names setting
        $wp_customize->add_setting('aqualuxe_multilingual_switcher_show_names', [
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        ]);
        
        // Add language switcher show names control
        $wp_customize->add_control('aqualuxe_multilingual_switcher_show_names', [
            'label'    => __('Show Language Names', 'aqualuxe'),
            'section'  => 'aqualuxe_multilingual',
            'type'     => 'checkbox',
            'priority' => 40,
        ]);
    }

    /**
     * Detect active multilingual plugin
     */
    protected function detect_active_plugin() {
        foreach ($this->supported_plugins as $plugin_id => $plugin_data) {
            if (
                // Check if plugin file is active
                is_plugin_active($plugin_data['file']) ||
                // Check if class exists
                (isset($plugin_data['class']) && class_exists($plugin_data['class'])) ||
                // Check if function exists
                (isset($plugin_data['function']) && function_exists($plugin_data['function'])) ||
                // Check if constant is defined
                (isset($plugin_data['constant']) && defined($plugin_data['constant']))
            ) {
                $this->active_plugin = $plugin_id;
                break;
            }
        }
    }

    /**
     * Check if module is active
     *
     * @return bool
     */
    public function is_active() {
        // Check if parent is active
        $parent_active = parent::is_active();
        
        // Check if a multilingual plugin is active
        $plugin_active = !empty($this->active_plugin);
        
        return $parent_active && $plugin_active;
    }

    /**
     * Get current language
     *
     * @return string Current language code
     */
    public function get_current_language() {
        switch ($this->active_plugin) {
            case 'polylang':
                return function_exists('pll_current_language') ? pll_current_language() : get_locale();
                
            case 'wpml':
                return function_exists('icl_get_current_language') ? icl_get_current_language() : get_locale();
                
            case 'translatepress':
                return function_exists('trp_get_current_language') ? trp_get_current_language() : get_locale();
                
            default:
                return get_locale();
        }
    }

    /**
     * Get available languages
     *
     * @return array Available languages
     */
    public function get_languages() {
        $languages = [];
        
        switch ($this->active_plugin) {
            case 'polylang':
                if (function_exists('pll_languages_list')) {
                    $langs = pll_languages_list(['fields' => '']);
                    
                    foreach ($langs as $lang) {
                        $languages[$lang->slug] = [
                            'code' => $lang->slug,
                            'name' => $lang->name,
                            'url' => $lang->url,
                            'flag' => $lang->flag_url,
                            'current' => $lang->slug === $this->get_current_language(),
                        ];
                    }
                }
                break;
                
            case 'wpml':
                if (function_exists('icl_get_languages')) {
                    $langs = icl_get_languages('skip_missing=0');
                    
                    foreach ($langs as $code => $lang) {
                        $languages[$code] = [
                            'code' => $code,
                            'name' => $lang['native_name'],
                            'url' => $lang['url'],
                            'flag' => $lang['country_flag_url'],
                            'current' => !empty($lang['active']),
                        ];
                    }
                }
                break;
                
            case 'translatepress':
                if (function_exists('trp_get_languages')) {
                    $trp = TRP_Translate_Press::get_trp_instance();
                    $trp_settings = $trp->get_component('settings');
                    $settings = $trp_settings->get_settings();
                    
                    $langs = $settings['publish-languages'];
                    $current_lang = $this->get_current_language();
                    
                    foreach ($langs as $code) {
                        $languages[$code] = [
                            'code' => $code,
                            'name' => trp_get_language_names([$code])[$code],
                            'url' => trp_add_language_to_home_url(home_url(), $code),
                            'flag' => TRP_PLUGIN_URL . 'assets/images/flags/' . $code . '.png',
                            'current' => $code === $current_lang,
                        ];
                    }
                }
                break;
        }
        
        return $languages;
    }

    /**
     * Render language switcher
     */
    public function render_language_switcher() {
        // Check if language switcher is enabled in header
        $position = get_theme_mod('aqualuxe_multilingual_switcher_position', 'header');
        if ($position !== 'header' && $position !== 'both') {
            return;
        }
        
        // Get languages
        $languages = $this->get_languages();
        if (empty($languages)) {
            return;
        }
        
        // Get current language
        $current_language = $this->get_current_language();
        
        // Get style
        $style = get_theme_mod('aqualuxe_multilingual_switcher_style', 'dropdown');
        
        // Get flags and names settings
        $show_flags = get_theme_mod('aqualuxe_multilingual_switcher_show_flags', true);
        $show_names = get_theme_mod('aqualuxe_multilingual_switcher_show_names', true);
        
        // Render language switcher
        ?>
        <div class="aqualuxe-language-switcher aqualuxe-language-switcher--<?php echo esc_attr($style); ?>">
            <?php if ($style === 'dropdown') : ?>
                <div class="aqualuxe-language-switcher__dropdown">
                    <button class="aqualuxe-language-switcher__toggle" aria-expanded="false" aria-controls="aqualuxe-language-dropdown">
                        <?php if ($show_flags && isset($languages[$current_language]['flag'])) : ?>
                            <img class="aqualuxe-language-switcher__flag" src="<?php echo esc_url($languages[$current_language]['flag']); ?>" alt="<?php echo esc_attr($languages[$current_language]['name']); ?>">
                        <?php endif; ?>
                        
                        <?php if ($show_names) : ?>
                            <span class="aqualuxe-language-switcher__name"><?php echo esc_html($languages[$current_language]['name']); ?></span>
                        <?php endif; ?>
                        
                        <span class="aqualuxe-language-switcher__arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd" d="M12.53 16.28a.75.75 0 01-1.06 0l-7.5-7.5a.75.75 0 011.06-1.06L12 14.69l6.97-6.97a.75.75 0 111.06 1.06l-7.5 7.5z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </button>
                    
                    <div id="aqualuxe-language-dropdown" class="aqualuxe-language-switcher__list" hidden>
                        <?php foreach ($languages as $code => $language) : ?>
                            <?php if ($code !== $current_language) : ?>
                                <a href="<?php echo esc_url($language['url']); ?>" class="aqualuxe-language-switcher__item" hreflang="<?php echo esc_attr($code); ?>">
                                    <?php if ($show_flags && isset($language['flag'])) : ?>
                                        <img class="aqualuxe-language-switcher__flag" src="<?php echo esc_url($language['flag']); ?>" alt="<?php echo esc_attr($language['name']); ?>">
                                    <?php endif; ?>
                                    
                                    <?php if ($show_names) : ?>
                                        <span class="aqualuxe-language-switcher__name"><?php echo esc_html($language['name']); ?></span>
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else : ?>
                <ul class="aqualuxe-language-switcher__list">
                    <?php foreach ($languages as $code => $language) : ?>
                        <li class="aqualuxe-language-switcher__item <?php echo $language['current'] ? 'aqualuxe-language-switcher__item--current' : ''; ?>">
                            <a href="<?php echo esc_url($language['url']); ?>" hreflang="<?php echo esc_attr($code); ?>">
                                <?php if ($show_flags && isset($language['flag'])) : ?>
                                    <img class="aqualuxe-language-switcher__flag" src="<?php echo esc_url($language['flag']); ?>" alt="<?php echo esc_attr($language['name']); ?>">
                                <?php endif; ?>
                                
                                <?php if ($show_names) : ?>
                                    <span class="aqualuxe-language-switcher__name"><?php echo esc_html($language['name']); ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render footer language switcher
     */
    public function render_footer_language_switcher() {
        // Check if language switcher is enabled in footer
        $position = get_theme_mod('aqualuxe_multilingual_switcher_position', 'header');
        if ($position !== 'footer' && $position !== 'both') {
            return;
        }
        
        // Get languages
        $languages = $this->get_languages();
        if (empty($languages)) {
            return;
        }
        
        // Get flags and names settings
        $show_flags = get_theme_mod('aqualuxe_multilingual_switcher_show_flags', true);
        $show_names = get_theme_mod('aqualuxe_multilingual_switcher_show_names', true);
        
        // Render language switcher
        ?>
        <div class="aqualuxe-footer-language-switcher">
            <h3 class="aqualuxe-footer-language-switcher__title"><?php esc_html_e('Languages', 'aqualuxe'); ?></h3>
            
            <ul class="aqualuxe-footer-language-switcher__list">
                <?php foreach ($languages as $code => $language) : ?>
                    <li class="aqualuxe-footer-language-switcher__item <?php echo $language['current'] ? 'aqualuxe-footer-language-switcher__item--current' : ''; ?>">
                        <a href="<?php echo esc_url($language['url']); ?>" hreflang="<?php echo esc_attr($code); ?>">
                            <?php if ($show_flags && isset($language['flag'])) : ?>
                                <img class="aqualuxe-footer-language-switcher__flag" src="<?php echo esc_url($language['flag']); ?>" alt="<?php echo esc_attr($language['name']); ?>">
                            <?php endif; ?>
                            
                            <?php if ($show_names) : ?>
                                <span class="aqualuxe-footer-language-switcher__name"><?php echo esc_html($language['name']); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }

    /**
     * Register language switcher widget
     */
    public function register_language_switcher_widget() {
        register_widget('AquaLuxe_Language_Switcher_Widget');
    }

    /**
     * Language switcher shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function language_switcher_shortcode($atts) {
        $atts = shortcode_atts([
            'style' => 'dropdown',
            'show_flags' => 'yes',
            'show_names' => 'yes',
        ], $atts, 'aqualuxe_language_switcher');
        
        // Get languages
        $languages = $this->get_languages();
        if (empty($languages)) {
            return '';
        }
        
        // Get current language
        $current_language = $this->get_current_language();
        
        // Get style
        $style = $atts['style'];
        
        // Get flags and names settings
        $show_flags = $atts['show_flags'] === 'yes';
        $show_names = $atts['show_names'] === 'yes';
        
        // Start output buffer
        ob_start();
        
        // Render language switcher
        ?>
        <div class="aqualuxe-language-switcher aqualuxe-language-switcher--<?php echo esc_attr($style); ?>">
            <?php if ($style === 'dropdown') : ?>
                <div class="aqualuxe-language-switcher__dropdown">
                    <button class="aqualuxe-language-switcher__toggle" aria-expanded="false" aria-controls="aqualuxe-language-dropdown-shortcode">
                        <?php if ($show_flags && isset($languages[$current_language]['flag'])) : ?>
                            <img class="aqualuxe-language-switcher__flag" src="<?php echo esc_url($languages[$current_language]['flag']); ?>" alt="<?php echo esc_attr($languages[$current_language]['name']); ?>">
                        <?php endif; ?>
                        
                        <?php if ($show_names) : ?>
                            <span class="aqualuxe-language-switcher__name"><?php echo esc_html($languages[$current_language]['name']); ?></span>
                        <?php endif; ?>
                        
                        <span class="aqualuxe-language-switcher__arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd" d="M12.53 16.28a.75.75 0 01-1.06 0l-7.5-7.5a.75.75 0 011.06-1.06L12 14.69l6.97-6.97a.75.75 0 111.06 1.06l-7.5 7.5z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </button>
                    
                    <div id="aqualuxe-language-dropdown-shortcode" class="aqualuxe-language-switcher__list" hidden>
                        <?php foreach ($languages as $code => $language) : ?>
                            <?php if ($code !== $current_language) : ?>
                                <a href="<?php echo esc_url($language['url']); ?>" class="aqualuxe-language-switcher__item" hreflang="<?php echo esc_attr($code); ?>">
                                    <?php if ($show_flags && isset($language['flag'])) : ?>
                                        <img class="aqualuxe-language-switcher__flag" src="<?php echo esc_url($language['flag']); ?>" alt="<?php echo esc_attr($language['name']); ?>">
                                    <?php endif; ?>
                                    
                                    <?php if ($show_names) : ?>
                                        <span class="aqualuxe-language-switcher__name"><?php echo esc_html($language['name']); ?></span>
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else : ?>
                <ul class="aqualuxe-language-switcher__list">
                    <?php foreach ($languages as $code => $language) : ?>
                        <li class="aqualuxe-language-switcher__item <?php echo $language['current'] ? 'aqualuxe-language-switcher__item--current' : ''; ?>">
                            <a href="<?php echo esc_url($language['url']); ?>" hreflang="<?php echo esc_attr($code); ?>">
                                <?php if ($show_flags && isset($language['flag'])) : ?>
                                    <img class="aqualuxe-language-switcher__flag" src="<?php echo esc_url($language['flag']); ?>" alt="<?php echo esc_attr($language['name']); ?>">
                                <?php endif; ?>
                                
                                <?php if ($show_names) : ?>
                                    <span class="aqualuxe-language-switcher__name"><?php echo esc_html($language['name']); ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php
        
        // Return output buffer
        return ob_get_clean();
    }

    /**
     * Add language attributes to html tag
     *
     * @param string $output Language attributes
     * @return string Modified language attributes
     */
    public function language_attributes($output) {
        if (!empty($this->active_plugin)) {
            $current_language = $this->get_current_language();
            $output .= ' data-language="' . esc_attr($current_language) . '"';
        }
        
        return $output;
    }

    /**
     * Add hreflang links to head
     */
    public function add_hreflang_links() {
        // Get languages
        $languages = $this->get_languages();
        if (empty($languages)) {
            return;
        }
        
        // Add hreflang links
        foreach ($languages as $code => $language) {
            printf(
                '<link rel="alternate" hreflang="%s" href="%s" />' . PHP_EOL,
                esc_attr($code),
                esc_url($language['url'])
            );
        }
    }

    /**
     * Get default settings
     *
     * @return array
     */
    protected function get_default_settings() {
        return [
            'enabled'         => true,
            'switcher_position' => 'header',
            'switcher_style' => 'dropdown',
            'show_flags' => true,
            'show_names' => true,
        ];
    }
}

/**
 * AquaLuxe Language Switcher Widget
 */
class AquaLuxe_Language_Switcher_Widget extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_language_switcher',
            __('AquaLuxe Language Switcher', 'aqualuxe'),
            [
                'description' => __('Displays a language switcher for multilingual sites.', 'aqualuxe'),
                'classname' => 'aqualuxe-language-switcher-widget',
            ]
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments
     * @param array $instance Widget instance
     */
    public function widget($args, $instance) {
        // Get module
        $module = aqualuxe()->get_module('multilingual');
        if (!$module) {
            return;
        }
        
        // Get title
        $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
        
        // Get style
        $style = !empty($instance['style']) ? $instance['style'] : 'dropdown';
        
        // Get flags and names settings
        $show_flags = isset($instance['show_flags']) ? (bool) $instance['show_flags'] : true;
        $show_names = isset($instance['show_names']) ? (bool) $instance['show_names'] : true;
        
        // Output widget
        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        // Output language switcher
        echo do_shortcode('[aqualuxe_language_switcher style="' . esc_attr($style) . '" show_flags="' . ($show_flags ? 'yes' : 'no') . '" show_names="' . ($show_names ? 'yes' : 'no') . '"]');
        
        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance
     * @return void
     */
    public function form($instance) {
        // Get title
        $title = isset($instance['title']) ? $instance['title'] : __('Languages', 'aqualuxe');
        
        // Get style
        $style = isset($instance['style']) ? $instance['style'] : 'dropdown';
        
        // Get flags and names settings
        $show_flags = isset($instance['show_flags']) ? (bool) $instance['show_flags'] : true;
        $show_names = isset($instance['show_names']) ? (bool) $instance['show_names'] : true;
        
        // Output form
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('style')); ?>"><?php esc_html_e('Style:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('style')); ?>" name="<?php echo esc_attr($this->get_field_name('style')); ?>">
                <option value="dropdown" <?php selected($style, 'dropdown'); ?>><?php esc_html_e('Dropdown', 'aqualuxe'); ?></option>
                <option value="list" <?php selected($style, 'list'); ?>><?php esc_html_e('List', 'aqualuxe'); ?></option>
                <option value="flags" <?php selected($style, 'flags'); ?>><?php esc_html_e('Flags', 'aqualuxe'); ?></option>
            </select>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_flags')); ?>" name="<?php echo esc_attr($this->get_field_name('show_flags')); ?>" <?php checked($show_flags); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('show_flags')); ?>"><?php esc_html_e('Show Flags', 'aqualuxe'); ?></label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_names')); ?>" name="<?php echo esc_attr($this->get_field_name('show_names')); ?>" <?php checked($show_names); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('show_names')); ?>"><?php esc_html_e('Show Language Names', 'aqualuxe'); ?></label>
        </p>
        <?php
    }

    /**
     * Update widget
     *
     * @param array $new_instance New instance
     * @param array $old_instance Old instance
     * @return array Updated instance
     */
    public function update($new_instance, $old_instance) {
        $instance = [];
        
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['style'] = !empty($new_instance['style']) ? sanitize_text_field($new_instance['style']) : 'dropdown';
        $instance['show_flags'] = isset($new_instance['show_flags']) ? (bool) $new_instance['show_flags'] : false;
        $instance['show_names'] = isset($new_instance['show_names']) ? (bool) $new_instance['show_names'] : false;
        
        return $instance;
    }
}