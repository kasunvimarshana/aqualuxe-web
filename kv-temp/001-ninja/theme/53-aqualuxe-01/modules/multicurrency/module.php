<?php
/**
 * Multicurrency Module
 *
 * @package AquaLuxe
 * @subpackage Modules\Multicurrency
 */

namespace AquaLuxe\Modules\Multicurrency;

use AquaLuxe\Core\Module_Base;

/**
 * Multicurrency Module class
 */
class Module extends Module_Base {
    /**
     * Module ID
     *
     * @var string
     */
    protected $module_id = 'multicurrency';

    /**
     * Module name
     *
     * @var string
     */
    protected $module_name = 'Multicurrency';

    /**
     * Module description
     *
     * @var string
     */
    protected $module_description = 'Adds multicurrency support to the theme.';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $module_dependencies = ['woocommerce'];

    /**
     * Available currencies
     *
     * @var array
     */
    private $currencies = [];

    /**
     * Current currency
     *
     * @var string
     */
    private $current_currency = '';

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();

        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return;
        }

        // Initialize currencies
        $this->init_currencies();

        // Register hooks
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('customize_register', [$this, 'customize_register']);
        add_action('init', [$this, 'register_currency_switcher_menu']);
        add_filter('body_class', [$this, 'body_classes']);
        
        // WooCommerce specific hooks
        add_filter('woocommerce_currency', [$this, 'get_woocommerce_currency']);
        add_filter('woocommerce_currency_symbol', [$this, 'get_woocommerce_currency_symbol']);
        add_filter('woocommerce_price_format', [$this, 'get_woocommerce_price_format'], 10, 2);
        add_filter('wc_get_price_decimals', [$this, 'get_price_decimals']);
    }

    /**
     * Initialize currencies
     *
     * @return void
     */
    private function init_currencies() {
        // Default currencies
        $this->currencies = [
            'USD' => [
                'name' => __('US Dollar', 'aqualuxe'),
                'symbol' => '$',
                'position' => 'left',
                'decimals' => 2,
                'rate' => 1,
            ],
            'EUR' => [
                'name' => __('Euro', 'aqualuxe'),
                'symbol' => '€',
                'position' => 'right',
                'decimals' => 2,
                'rate' => 0.85,
            ],
            'GBP' => [
                'name' => __('British Pound', 'aqualuxe'),
                'symbol' => '£',
                'position' => 'left',
                'decimals' => 2,
                'rate' => 0.75,
            ],
            'JPY' => [
                'name' => __('Japanese Yen', 'aqualuxe'),
                'symbol' => '¥',
                'position' => 'left',
                'decimals' => 0,
                'rate' => 110,
            ],
        ];

        // Get enabled currencies from theme mods
        $enabled_currencies = get_theme_mod('enabled_currencies', ['USD']);
        
        // Filter currencies
        foreach ($this->currencies as $code => $currency) {
            if (!in_array($code, $enabled_currencies, true)) {
                unset($this->currencies[$code]);
            }
        }

        // Set current currency
        $this->current_currency = $this->get_current_currency();
    }

    /**
     * Enqueue scripts
     *
     * @return void
     */
    public function enqueue_scripts() {
        wp_enqueue_style(
            'aqualuxe-multicurrency',
            AQUALUXE_MODULES_DIR . 'multicurrency/assets/css/multicurrency.css',
            [],
            AQUALUXE_VERSION
        );

        wp_enqueue_script(
            'aqualuxe-multicurrency',
            AQUALUXE_MODULES_DIR . 'multicurrency/assets/js/multicurrency.js',
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );

        wp_localize_script(
            'aqualuxe-multicurrency',
            'aqualuxeMulticurrency',
            [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'currentCurrency' => $this->current_currency,
                'saveInCookies' => get_theme_mod('currency_cookies', true),
                'cookieName' => 'aqualuxe_currency',
                'cookieExpiration' => 30, // days
            ]
        );
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    public function customize_register($wp_customize) {
        // Multicurrency Section
        $wp_customize->add_section(
            'multicurrency_section',
            [
                'title' => __('Multicurrency', 'aqualuxe'),
                'priority' => 45,
                'panel' => 'aqualuxe_theme_options',
            ]
        );

        // Enabled Currencies
        $wp_customize->add_setting(
            'enabled_currencies',
            [
                'default' => ['USD'],
                'sanitize_callback' => [$this, 'sanitize_currencies'],
            ]
        );

        $wp_customize->add_control(
            new \WP_Customize_Control(
                $wp_customize,
                'enabled_currencies',
                [
                    'label' => __('Enabled Currencies', 'aqualuxe'),
                    'description' => __('Select currencies to enable on your site.', 'aqualuxe'),
                    'section' => 'multicurrency_section',
                    'type' => 'select',
                    'choices' => [
                        'USD' => __('US Dollar ($)', 'aqualuxe'),
                        'EUR' => __('Euro (€)', 'aqualuxe'),
                        'GBP' => __('British Pound (£)', 'aqualuxe'),
                        'JPY' => __('Japanese Yen (¥)', 'aqualuxe'),
                    ],
                    'multiple' => true,
                ]
            )
        );

        // Default Currency
        $wp_customize->add_setting(
            'default_currency',
            [
                'default' => 'USD',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );

        $wp_customize->add_control(
            'default_currency',
            [
                'label' => __('Default Currency', 'aqualuxe'),
                'description' => __('Select the default currency for your site.', 'aqualuxe'),
                'section' => 'multicurrency_section',
                'type' => 'select',
                'choices' => [
                    'USD' => __('US Dollar ($)', 'aqualuxe'),
                    'EUR' => __('Euro (€)', 'aqualuxe'),
                    'GBP' => __('British Pound (£)', 'aqualuxe'),
                    'JPY' => __('Japanese Yen (¥)', 'aqualuxe'),
                ],
            ]
        );

        // Currency Switcher Position
        $wp_customize->add_setting(
            'currency_switcher_position',
            [
                'default' => 'header',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );

        $wp_customize->add_control(
            'currency_switcher_position',
            [
                'label' => __('Currency Switcher Position', 'aqualuxe'),
                'description' => __('Select where to display the currency switcher.', 'aqualuxe'),
                'section' => 'multicurrency_section',
                'type' => 'select',
                'choices' => [
                    'header' => __('Header', 'aqualuxe'),
                    'footer' => __('Footer', 'aqualuxe'),
                    'both' => __('Both', 'aqualuxe'),
                    'none' => __('None', 'aqualuxe'),
                ],
            ]
        );

        // Currency Cookies
        $wp_customize->add_setting(
            'currency_cookies',
            [
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );

        $wp_customize->add_control(
            'currency_cookies',
            [
                'label' => __('Save Currency Preference', 'aqualuxe'),
                'description' => __('Save user\'s currency preference in cookies.', 'aqualuxe'),
                'section' => 'multicurrency_section',
                'type' => 'checkbox',
            ]
        );

        // Currency Switcher Style
        $wp_customize->add_setting(
            'currency_switcher_style',
            [
                'default' => 'dropdown',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );

        $wp_customize->add_control(
            'currency_switcher_style',
            [
                'label' => __('Currency Switcher Style', 'aqualuxe'),
                'description' => __('Select the style for the currency switcher.', 'aqualuxe'),
                'section' => 'multicurrency_section',
                'type' => 'select',
                'choices' => [
                    'dropdown' => __('Dropdown', 'aqualuxe'),
                    'symbols' => __('Symbols', 'aqualuxe'),
                    'codes' => __('Currency Codes', 'aqualuxe'),
                ],
            ]
        );

        // Currency Exchange Rates
        foreach ($this->currencies as $code => $currency) {
            if ($code === 'USD') {
                continue; // Skip base currency
            }

            $wp_customize->add_setting(
                'currency_rate_' . $code,
                [
                    'default' => $currency['rate'],
                    'sanitize_callback' => 'floatval',
                ]
            );

            $wp_customize->add_control(
                'currency_rate_' . $code,
                [
                    'label' => sprintf(__('Exchange Rate: %s to USD', 'aqualuxe'), $code),
                    'description' => __('Enter the exchange rate relative to USD.', 'aqualuxe'),
                    'section' => 'multicurrency_section',
                    'type' => 'number',
                    'input_attrs' => [
                        'min' => 0.01,
                        'step' => 0.01,
                    ],
                ]
            );
        }
    }

    /**
     * Sanitize currencies
     *
     * @param array $input Currencies
     * @return array
     */
    public function sanitize_currencies($input) {
        $valid_currencies = ['USD', 'EUR', 'GBP', 'JPY'];
        $output = [];

        foreach ($input as $currency) {
            if (in_array($currency, $valid_currencies, true)) {
                $output[] = $currency;
            }
        }

        // Ensure at least one currency is enabled
        if (empty($output)) {
            $output[] = 'USD';
        }

        return $output;
    }

    /**
     * Register currency switcher menu
     *
     * @return void
     */
    public function register_currency_switcher_menu() {
        register_nav_menus(
            [
                'currency_switcher' => __('Currency Switcher', 'aqualuxe'),
            ]
        );
    }

    /**
     * Add body classes
     *
     * @param array $classes Body classes
     * @return array
     */
    public function body_classes($classes) {
        $classes[] = 'currency-' . strtolower($this->current_currency);
        return $classes;
    }

    /**
     * Get WooCommerce currency
     *
     * @param string $currency Currency
     * @return string
     */
    public function get_woocommerce_currency($currency) {
        return $this->current_currency;
    }

    /**
     * Get WooCommerce currency symbol
     *
     * @param string $symbol Currency symbol
     * @return string
     */
    public function get_woocommerce_currency_symbol($symbol) {
        if (isset($this->currencies[$this->current_currency])) {
            return $this->currencies[$this->current_currency]['symbol'];
        }
        return $symbol;
    }

    /**
     * Get WooCommerce price format
     *
     * @param string $format Price format
     * @param string $currency_pos Currency position
     * @return string
     */
    public function get_woocommerce_price_format($format, $currency_pos) {
        if (isset($this->currencies[$this->current_currency])) {
            $position = $this->currencies[$this->current_currency]['position'];
            
            switch ($position) {
                case 'left':
                    return '%1$s%2$s';
                case 'right':
                    return '%2$s%1$s';
                case 'left_space':
                    return '%1$s&nbsp;%2$s';
                case 'right_space':
                    return '%2$s&nbsp;%1$s';
                default:
                    return $format;
            }
        }
        
        return $format;
    }

    /**
     * Get price decimals
     *
     * @param int $decimals Decimals
     * @return int
     */
    public function get_price_decimals($decimals) {
        if (isset($this->currencies[$this->current_currency])) {
            return $this->currencies[$this->current_currency]['decimals'];
        }
        return $decimals;
    }

    /**
     * Get current currency
     *
     * @return string
     */
    public function get_current_currency() {
        // Check if currency is set in URL
        if (isset($_GET['currency']) && array_key_exists($_GET['currency'], $this->currencies)) {
            return sanitize_text_field($_GET['currency']);
        }

        // Check if currency is set in cookie
        if (isset($_COOKIE['aqualuxe_currency']) && array_key_exists($_COOKIE['aqualuxe_currency'], $this->currencies)) {
            return sanitize_text_field($_COOKIE['aqualuxe_currency']);
        }

        // Fallback to default currency
        return get_theme_mod('default_currency', 'USD');
    }

    /**
     * Render currency switcher
     *
     * @return void
     */
    public function render_currency_switcher() {
        $style = get_theme_mod('currency_switcher_style', 'dropdown');
        $position = get_theme_mod('currency_switcher_position', 'header');

        if ('none' === $position) {
            return;
        }

        if (count($this->currencies) <= 1) {
            return;
        }

        echo '<div class="currency-switcher currency-switcher-' . esc_attr($style) . '">';

        if ('dropdown' === $style) {
            $this->render_dropdown_switcher();
        } elseif ('symbols' === $style) {
            $this->render_symbols_switcher();
        } elseif ('codes' === $style) {
            $this->render_codes_switcher();
        }

        echo '</div>';
    }

    /**
     * Render dropdown switcher
     *
     * @return void
     */
    private function render_dropdown_switcher() {
        echo '<select id="currency-switcher-select" class="currency-switcher-select">';
        
        foreach ($this->currencies as $code => $currency) {
            $selected = $code === $this->current_currency ? ' selected' : '';
            echo '<option value="' . esc_attr($code) . '"' . $selected . '>' . esc_html($currency['name']) . ' (' . esc_html($currency['symbol']) . ')</option>';
        }
        
        echo '</select>';
    }

    /**
     * Render symbols switcher
     *
     * @return void
     */
    private function render_symbols_switcher() {
        echo '<ul class="currency-switcher-symbols">';
        
        foreach ($this->currencies as $code => $currency) {
            $active = $code === $this->current_currency ? ' class="active"' : '';
            echo '<li' . $active . '>';
            echo '<a href="?currency=' . esc_attr($code) . '" data-currency="' . esc_attr($code) . '">';
            echo esc_html($currency['symbol']);
            echo '</a>';
            echo '</li>';
        }
        
        echo '</ul>';
    }

    /**
     * Render codes switcher
     *
     * @return void
     */
    private function render_codes_switcher() {
        echo '<ul class="currency-switcher-codes">';
        
        foreach ($this->currencies as $code => $currency) {
            $active = $code === $this->current_currency ? ' class="active"' : '';
            echo '<li' . $active . '>';
            echo '<a href="?currency=' . esc_attr($code) . '" data-currency="' . esc_attr($code) . '">';
            echo esc_html($code);
            echo '</a>';
            echo '</li>';
        }
        
        echo '</ul>';
    }
}

// Initialize the module
new Module();