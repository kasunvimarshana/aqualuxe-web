<?php
/**
 * Multicurrency Compatibility File
 *
 * @package AquaLuxe
 */

/**
 * Make theme compatible with multicurrency plugins
 */
class AquaLuxe_Multicurrency {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_Multicurrency
     */
    private static $instance;

    /**
     * Get the singleton instance of this class
     *
     * @return AquaLuxe_Multicurrency
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
        // Initialize multicurrency compatibility
        $this->init();
    }

    /**
     * Initialize multicurrency compatibility
     */
    private function init() {
        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        // Add currency switcher to header
        add_action( 'aqualuxe_header_actions', array( $this, 'add_currency_switcher' ), 30 );
        
        // Add currency switcher to mobile menu
        add_action( 'aqualuxe_mobile_menu_after', array( $this, 'add_currency_switcher_mobile' ) );
        
        // Add currency switcher to footer
        add_action( 'aqualuxe_footer_before', array( $this, 'add_currency_switcher_footer' ) );
        
        // WPML WooCommerce Multicurrency compatibility
        if ( class_exists( 'WCML_Multi_Currency' ) ) {
            $this->setup_wpml_multicurrency();
        }
        
        // WooCommerce Currency Switcher (WOOCS) compatibility
        if ( class_exists( 'WOOCS' ) ) {
            $this->setup_woocs();
        }
        
        // Currency Switcher for WooCommerce by WP Wham compatibility
        if ( class_exists( 'Alg_WC_Currency_Switcher' ) ) {
            $this->setup_alg_currency_switcher();
        }
        
        // Add AJAX currency switching
        add_action( 'wp_ajax_aqualuxe_switch_currency', array( $this, 'ajax_switch_currency' ) );
        add_action( 'wp_ajax_nopriv_aqualuxe_switch_currency', array( $this, 'ajax_switch_currency' ) );
    }

    /**
     * Add currency switcher to header
     */
    public function add_currency_switcher() {
        // WPML WooCommerce Multicurrency
        if ( class_exists( 'WCML_Multi_Currency' ) ) {
            $this->render_wpml_currency_switcher( 'header' );
        }
        // WooCommerce Currency Switcher (WOOCS)
        elseif ( class_exists( 'WOOCS' ) ) {
            $this->render_woocs_currency_switcher( 'header' );
        }
        // Currency Switcher for WooCommerce by WP Wham
        elseif ( class_exists( 'Alg_WC_Currency_Switcher' ) ) {
            $this->render_alg_currency_switcher( 'header' );
        }
    }

    /**
     * Add currency switcher to mobile menu
     */
    public function add_currency_switcher_mobile() {
        // WPML WooCommerce Multicurrency
        if ( class_exists( 'WCML_Multi_Currency' ) ) {
            $this->render_wpml_currency_switcher( 'mobile' );
        }
        // WooCommerce Currency Switcher (WOOCS)
        elseif ( class_exists( 'WOOCS' ) ) {
            $this->render_woocs_currency_switcher( 'mobile' );
        }
        // Currency Switcher for WooCommerce by WP Wham
        elseif ( class_exists( 'Alg_WC_Currency_Switcher' ) ) {
            $this->render_alg_currency_switcher( 'mobile' );
        }
    }

    /**
     * Add currency switcher to footer
     */
    public function add_currency_switcher_footer() {
        if ( ! get_theme_mod( 'aqualuxe_footer_currency_switcher', true ) ) {
            return;
        }
        
        // WPML WooCommerce Multicurrency
        if ( class_exists( 'WCML_Multi_Currency' ) ) {
            $this->render_wpml_currency_switcher( 'footer' );
        }
        // WooCommerce Currency Switcher (WOOCS)
        elseif ( class_exists( 'WOOCS' ) ) {
            $this->render_woocs_currency_switcher( 'footer' );
        }
        // Currency Switcher for WooCommerce by WP Wham
        elseif ( class_exists( 'Alg_WC_Currency_Switcher' ) ) {
            $this->render_alg_currency_switcher( 'footer' );
        }
    }

    /**
     * Setup WPML WooCommerce Multicurrency
     */
    private function setup_wpml_multicurrency() {
        // Add custom currency switcher style
        add_filter( 'wcml_multi_currency_ajax_disable', '__return_true' );
    }

    /**
     * Render WPML currency switcher
     *
     * @param string $location Location of the switcher.
     */
    private function render_wpml_currency_switcher( $location = 'header' ) {
        global $woocommerce_wpml;
        
        if ( ! is_object( $woocommerce_wpml ) || ! property_exists( $woocommerce_wpml, 'multi_currency' ) ) {
            return;
        }
        
        $multi_currency = $woocommerce_wpml->multi_currency;
        
        if ( ! $multi_currency->get_currencies() ) {
            return;
        }
        
        $currencies = $multi_currency->get_currencies( 'include_default = 1' );
        $current_currency = $multi_currency->get_client_currency();
        
        if ( empty( $currencies ) ) {
            return;
        }
        
        $switcher_style = get_theme_mod( 'aqualuxe_currency_switcher_style', 'dropdown' );
        
        switch ( $location ) {
            case 'header':
                echo '<div class="header-currency-switcher">';
                break;
            case 'mobile':
                echo '<div class="mobile-currency-switcher">';
                echo '<h3>' . esc_html__( 'Currency', 'aqualuxe' ) . '</h3>';
                break;
            case 'footer':
                echo '<div class="footer-currency-switcher">';
                break;
        }
        
        if ( 'dropdown' === $switcher_style && 'mobile' !== $location ) {
            echo '<div class="currency-switcher-wrapper">';
            
            // Current currency
            echo '<button class="currency-switcher-toggle" aria-expanded="false">';
            echo '<span>' . esc_html( $current_currency ) . '</span>';
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';
            echo '</button>';
            
            // Currency dropdown
            echo '<ul class="currency-switcher-dropdown">';
            foreach ( $currencies as $code => $currency ) {
                $class = $code === $current_currency ? 'active' : '';
                echo '<li class="' . esc_attr( $class ) . '">';
                echo '<a href="#" data-currency="' . esc_attr( $code ) . '" class="aqualuxe-currency-switch">';
                echo '<span class="currency-code">' . esc_html( $code ) . '</span>';
                echo '<span class="currency-name">' . esc_html( $currency['languages'][ ICL_LANGUAGE_CODE ]['currency_name'] ) . '</span>';
                echo '</a>';
                echo '</li>';
            }
            echo '</ul>';
            
            echo '</div>';
        } else {
            echo '<ul class="currency-switcher-list">';
            foreach ( $currencies as $code => $currency ) {
                $class = $code === $current_currency ? 'active' : '';
                echo '<li class="' . esc_attr( $class ) . '">';
                echo '<a href="#" data-currency="' . esc_attr( $code ) . '" class="aqualuxe-currency-switch">';
                echo '<span class="currency-code">' . esc_html( $code ) . '</span>';
                echo '</a>';
                echo '</li>';
            }
            echo '</ul>';
        }
        
        echo '</div>';
    }

    /**
     * Setup WooCommerce Currency Switcher (WOOCS)
     */
    private function setup_woocs() {
        // Add custom currency switcher style
        add_filter( 'woocs_drop_down_view', array( $this, 'woocs_dropdown_template' ) );
    }

    /**
     * Custom WOOCS dropdown template
     *
     * @param string $template Template HTML.
     * @return string
     */
    public function woocs_dropdown_template( $template ) {
        return '<div class="woocs-style-1-dropdown">
            <span class="woocs-style-1-select">
                <span class="woocs-style-1-current">{{WOOCS_CURRENT}}</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </span>
            <div class="woocs-style-1-dropdown-content">
                {{WOOCS_OPTIONS}}
            </div>
        </div>';
    }

    /**
     * Render WOOCS currency switcher
     *
     * @param string $location Location of the switcher.
     */
    private function render_woocs_currency_switcher( $location = 'header' ) {
        global $WOOCS;
        
        if ( ! is_object( $WOOCS ) ) {
            return;
        }
        
        $currencies = $WOOCS->get_currencies();
        $current_currency = $WOOCS->current_currency;
        
        if ( empty( $currencies ) ) {
            return;
        }
        
        $switcher_style = get_theme_mod( 'aqualuxe_currency_switcher_style', 'dropdown' );
        
        switch ( $location ) {
            case 'header':
                echo '<div class="header-currency-switcher">';
                break;
            case 'mobile':
                echo '<div class="mobile-currency-switcher">';
                echo '<h3>' . esc_html__( 'Currency', 'aqualuxe' ) . '</h3>';
                break;
            case 'footer':
                echo '<div class="footer-currency-switcher">';
                break;
        }
        
        if ( 'dropdown' === $switcher_style && 'mobile' !== $location ) {
            echo '<div class="currency-switcher-wrapper">';
            
            // Current currency
            echo '<button class="currency-switcher-toggle" aria-expanded="false">';
            echo '<span>' . esc_html( $current_currency ) . '</span>';
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';
            echo '</button>';
            
            // Currency dropdown
            echo '<ul class="currency-switcher-dropdown">';
            foreach ( $currencies as $code => $currency ) {
                $class = $code === $current_currency ? 'active' : '';
                echo '<li class="' . esc_attr( $class ) . '">';
                echo '<a href="#" data-currency="' . esc_attr( $code ) . '" class="aqualuxe-currency-switch">';
                echo '<span class="currency-code">' . esc_html( $code ) . '</span>';
                echo '<span class="currency-name">' . esc_html( $currency['name'] ) . '</span>';
                echo '</a>';
                echo '</li>';
            }
            echo '</ul>';
            
            echo '</div>';
        } else {
            echo '<ul class="currency-switcher-list">';
            foreach ( $currencies as $code => $currency ) {
                $class = $code === $current_currency ? 'active' : '';
                echo '<li class="' . esc_attr( $class ) . '">';
                echo '<a href="#" data-currency="' . esc_attr( $code ) . '" class="aqualuxe-currency-switch">';
                echo '<span class="currency-code">' . esc_html( $code ) . '</span>';
                echo '</a>';
                echo '</li>';
            }
            echo '</ul>';
        }
        
        echo '</div>';
    }

    /**
     * Setup Currency Switcher for WooCommerce by WP Wham
     */
    private function setup_alg_currency_switcher() {
        // Add custom currency switcher style
        add_filter( 'alg_wc_currency_switcher_templates', array( $this, 'alg_currency_switcher_templates' ) );
    }

    /**
     * Custom ALG currency switcher templates
     *
     * @param array $templates Templates.
     * @return array
     */
    public function alg_currency_switcher_templates( $templates ) {
        $templates['aqualuxe'] = array(
            'name'    => 'AquaLuxe',
            'format'  => '%currency_code%',
            'wrapper' => '<div class="alg-currency-switcher-dropdown">%currencies%</div>',
        );
        
        return $templates;
    }

    /**
     * Render ALG currency switcher
     *
     * @param string $location Location of the switcher.
     */
    private function render_alg_currency_switcher( $location = 'header' ) {
        if ( ! function_exists( 'alg_get_current_currency_code' ) || ! function_exists( 'alg_get_enabled_currencies' ) ) {
            return;
        }
        
        $current_currency = alg_get_current_currency_code();
        $currencies = alg_get_enabled_currencies();
        
        if ( empty( $currencies ) ) {
            return;
        }
        
        $switcher_style = get_theme_mod( 'aqualuxe_currency_switcher_style', 'dropdown' );
        
        switch ( $location ) {
            case 'header':
                echo '<div class="header-currency-switcher">';
                break;
            case 'mobile':
                echo '<div class="mobile-currency-switcher">';
                echo '<h3>' . esc_html__( 'Currency', 'aqualuxe' ) . '</h3>';
                break;
            case 'footer':
                echo '<div class="footer-currency-switcher">';
                break;
        }
        
        if ( 'dropdown' === $switcher_style && 'mobile' !== $location ) {
            echo '<div class="currency-switcher-wrapper">';
            
            // Current currency
            echo '<button class="currency-switcher-toggle" aria-expanded="false">';
            echo '<span>' . esc_html( $current_currency ) . '</span>';
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';
            echo '</button>';
            
            // Currency dropdown
            echo '<ul class="currency-switcher-dropdown">';
            foreach ( $currencies as $code ) {
                $class = $code === $current_currency ? 'active' : '';
                echo '<li class="' . esc_attr( $class ) . '">';
                echo '<a href="#" data-currency="' . esc_attr( $code ) . '" class="aqualuxe-currency-switch">';
                echo '<span class="currency-code">' . esc_html( $code ) . '</span>';
                echo '</a>';
                echo '</li>';
            }
            echo '</ul>';
            
            echo '</div>';
        } else {
            echo '<ul class="currency-switcher-list">';
            foreach ( $currencies as $code ) {
                $class = $code === $current_currency ? 'active' : '';
                echo '<li class="' . esc_attr( $class ) . '">';
                echo '<a href="#" data-currency="' . esc_attr( $code ) . '" class="aqualuxe-currency-switch">';
                echo '<span class="currency-code">' . esc_html( $code ) . '</span>';
                echo '</a>';
                echo '</li>';
            }
            echo '</ul>';
        }
        
        echo '</div>';
    }

    /**
     * AJAX currency switching
     */
    public function ajax_switch_currency() {
        if ( ! isset( $_POST['currency'] ) || ! isset( $_POST['nonce'] ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid request', 'aqualuxe' ) ) );
        }
        
        if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_switch_currency' ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed', 'aqualuxe' ) ) );
        }
        
        $currency = sanitize_text_field( wp_unslash( $_POST['currency'] ) );
        
        // WPML WooCommerce Multicurrency
        if ( class_exists( 'WCML_Multi_Currency' ) ) {
            global $woocommerce_wpml;
            
            if ( is_object( $woocommerce_wpml ) && property_exists( $woocommerce_wpml, 'multi_currency' ) ) {
                $woocommerce_wpml->multi_currency->set_client_currency( $currency );
                wp_send_json_success( array( 'message' => __( 'Currency switched successfully', 'aqualuxe' ) ) );
            }
        }
        // WooCommerce Currency Switcher (WOOCS)
        elseif ( class_exists( 'WOOCS' ) ) {
            global $WOOCS;
            
            if ( is_object( $WOOCS ) ) {
                $WOOCS->set_currency( $currency );
                wp_send_json_success( array( 'message' => __( 'Currency switched successfully', 'aqualuxe' ) ) );
            }
        }
        // Currency Switcher for WooCommerce by WP Wham
        elseif ( class_exists( 'Alg_WC_Currency_Switcher' ) ) {
            if ( function_exists( 'alg_wc_cs_session_set' ) ) {
                alg_wc_cs_session_set( 'alg_currency', $currency );
                wp_send_json_success( array( 'message' => __( 'Currency switched successfully', 'aqualuxe' ) ) );
            }
        }
        
        wp_send_json_error( array( 'message' => __( 'Currency switching failed', 'aqualuxe' ) ) );
    }
}

// Initialize multicurrency compatibility
AquaLuxe_Multicurrency::get_instance();