<?php
/**
 * AquaLuxe Multilingual Module WPML Compatibility
 *
 * @package AquaLuxe
 * @subpackage Modules/Multilingual
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Register strings for WPML translation
 *
 * @param string $string String to register
 * @param string $name String name
 * @param string $domain Text domain
 * @return void
 */
function aqualuxe_wpml_register_string( $string, $name, $domain = 'aqualuxe' ) {
    if ( function_exists( 'icl_register_string' ) ) {
        icl_register_string( $domain, $name, $string );
    }
}

/**
 * Translate string with WPML
 *
 * @param string $string String to translate
 * @param string $name String name
 * @param string $domain Text domain
 * @return string
 */
function aqualuxe_wpml_translate_string( $string, $name, $domain = 'aqualuxe' ) {
    if ( function_exists( 'icl_t' ) ) {
        return icl_t( $domain, $name, $string );
    }
    
    return $string;
}

/**
 * Register theme options for WPML translation
 *
 * @return void
 */
function aqualuxe_wpml_register_theme_options() {
    // Get customizer options
    $options = get_option( 'theme_mods_' . get_stylesheet() );
    
    if ( ! is_array( $options ) ) {
        return;
    }
    
    // Register text options
    $text_options = array(
        'aqualuxe_header_phone',
        'aqualuxe_header_email',
        'aqualuxe_footer_copyright',
        'aqualuxe_footer_address',
        'aqualuxe_footer_phone',
        'aqualuxe_footer_email',
    );
    
    foreach ( $text_options as $option ) {
        if ( isset( $options[ $option ] ) ) {
            aqualuxe_wpml_register_string( $options[ $option ], $option, 'Theme Options' );
        }
    }
}
add_action( 'after_setup_theme', 'aqualuxe_wpml_register_theme_options' );

/**
 * Filter theme options for WPML translation
 *
 * @param mixed $value Option value
 * @param string $option Option name
 * @return mixed
 */
function aqualuxe_wpml_filter_theme_options( $value, $option ) {
    // Check if option should be translated
    $text_options = array(
        'aqualuxe_header_phone',
        'aqualuxe_header_email',
        'aqualuxe_footer_copyright',
        'aqualuxe_footer_address',
        'aqualuxe_footer_phone',
        'aqualuxe_footer_email',
    );
    
    if ( in_array( $option, $text_options ) ) {
        return aqualuxe_wpml_translate_string( $value, $option, 'Theme Options' );
    }
    
    return $value;
}
add_filter( 'theme_mod_aqualuxe_header_phone', 'aqualuxe_wpml_filter_theme_options', 10, 2 );
add_filter( 'theme_mod_aqualuxe_header_email', 'aqualuxe_wpml_filter_theme_options', 10, 2 );
add_filter( 'theme_mod_aqualuxe_footer_copyright', 'aqualuxe_wpml_filter_theme_options', 10, 2 );
add_filter( 'theme_mod_aqualuxe_footer_address', 'aqualuxe_wpml_filter_theme_options', 10, 2 );
add_filter( 'theme_mod_aqualuxe_footer_phone', 'aqualuxe_wpml_filter_theme_options', 10, 2 );
add_filter( 'theme_mod_aqualuxe_footer_email', 'aqualuxe_wpml_filter_theme_options', 10, 2 );

/**
 * Add language switcher to WPML language switcher menu
 *
 * @param array $items Menu items
 * @return array
 */
function aqualuxe_wpml_language_switcher_menu( $items ) {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return $items;
    }
    
    // Check if WPML language switcher menu is enabled
    if ( ! $module->get_setting( 'wpml_language_switcher_menu', true ) ) {
        return $items;
    }
    
    // Get languages
    $languages = apply_filters( 'wpml_active_languages', null, array( 'skip_missing' => 0 ) );
    
    if ( ! is_array( $languages ) ) {
        return $items;
    }
    
    // Get current language
    $current_language = apply_filters( 'wpml_current_language', null );
    
    // Get language switcher style
    $style = $module->get_setting( 'wpml_language_switcher_style', 'dropdown' );
    
    // Get language switcher HTML
    ob_start();
    aqualuxe_language_switcher( $style, array(
        'location' => 'wpml',
        'show_flags' => $module->get_setting( 'show_flags', true ),
        'show_names' => $module->get_setting( 'show_names', true ),
    ) );
    $switcher = ob_get_clean();
    
    // Add language switcher to menu
    $items[] = array(
        'ID' => 'wpml-language-switcher',
        'title' => $switcher,
        'url' => '#',
        'menu_item_parent' => 0,
        'db_id' => 0,
        'classes' => array( 'menu-item', 'menu-item-language-switcher' ),
    );
    
    return $items;
}
add_filter( 'wp_nav_menu_items', 'aqualuxe_wpml_language_switcher_menu', 10, 2 );

/**
 * Register WPML widgets
 *
 * @return void
 */
function aqualuxe_wpml_register_widgets() {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return;
    }
    
    // Check if WPML widgets are enabled
    if ( ! $module->get_setting( 'wpml_widgets', true ) ) {
        return;
    }
    
    // Register language switcher widget
    register_widget( 'AquaLuxe_WPML_Language_Switcher_Widget' );
}
add_action( 'widgets_init', 'aqualuxe_wpml_register_widgets' );

/**
 * WPML Language Switcher Widget
 */
class AquaLuxe_WPML_Language_Switcher_Widget extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_wpml_language_switcher',
            __( 'AquaLuxe WPML Language Switcher', 'aqualuxe' ),
            array(
                'description' => __( 'Display a language switcher for WPML.', 'aqualuxe' ),
                'classname' => 'widget_aqualuxe_wpml_language_switcher',
            )
        );
    }
    
    /**
     * Widget output
     *
     * @param array $args Widget arguments
     * @param array $instance Widget instance
     * @return void
     */
    public function widget( $args, $instance ) {
        // Get title
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        
        // Apply filters to title
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
        
        // Get style
        $style = ! empty( $instance['style'] ) ? $instance['style'] : 'dropdown';
        
        // Get show flags
        $show_flags = isset( $instance['show_flags'] ) ? (bool) $instance['show_flags'] : true;
        
        // Get show names
        $show_names = isset( $instance['show_names'] ) ? (bool) $instance['show_names'] : true;
        
        // Before widget
        echo $args['before_widget'];
        
        // Title
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        // Language switcher
        aqualuxe_language_switcher( $style, array(
            'location' => 'widget',
            'show_flags' => $show_flags,
            'show_names' => $show_names,
        ) );
        
        // After widget
        echo $args['after_widget'];
    }
    
    /**
     * Widget form
     *
     * @param array $instance Widget instance
     * @return void
     */
    public function form( $instance ) {
        // Get title
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        
        // Get style
        $style = ! empty( $instance['style'] ) ? $instance['style'] : 'dropdown';
        
        // Get show flags
        $show_flags = isset( $instance['show_flags'] ) ? (bool) $instance['show_flags'] : true;
        
        // Get show names
        $show_names = isset( $instance['show_names'] ) ? (bool) $instance['show_names'] : true;
        
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Style:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>">
                <option value="dropdown" <?php selected( $style, 'dropdown' ); ?>><?php esc_html_e( 'Dropdown', 'aqualuxe' ); ?></option>
                <option value="horizontal" <?php selected( $style, 'horizontal' ); ?>><?php esc_html_e( 'Horizontal', 'aqualuxe' ); ?></option>
                <option value="vertical" <?php selected( $style, 'vertical' ); ?>><?php esc_html_e( 'Vertical', 'aqualuxe' ); ?></option>
            </select>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_flags ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_flags' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_flags' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_flags' ) ); ?>"><?php esc_html_e( 'Show flags', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_names ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_names' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_names' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_names' ) ); ?>"><?php esc_html_e( 'Show names', 'aqualuxe' ); ?></label>
        </p>
        <?php
    }
    
    /**
     * Update widget
     *
     * @param array $new_instance New instance
     * @param array $old_instance Old instance
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        
        // Title
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        
        // Style
        $instance['style'] = ! empty( $new_instance['style'] ) ? sanitize_text_field( $new_instance['style'] ) : 'dropdown';
        
        // Show flags
        $instance['show_flags'] = isset( $new_instance['show_flags'] ) ? (bool) $new_instance['show_flags'] : false;
        
        // Show names
        $instance['show_names'] = isset( $new_instance['show_names'] ) ? (bool) $new_instance['show_names'] : false;
        
        return $instance;
    }
}

/**
 * Add WPML language switcher to header
 *
 * @return void
 */
function aqualuxe_wpml_add_language_switcher_to_header() {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return;
    }
    
    // Check if WPML language switcher is enabled in header
    if ( ! $module->get_setting( 'wpml_language_switcher_header', true ) ) {
        return;
    }
    
    // Get language switcher style
    $style = $module->get_setting( 'wpml_language_switcher_style', 'dropdown' );
    
    // Get language switcher
    aqualuxe_language_switcher( $style, array(
        'location' => 'header',
        'show_flags' => $module->get_setting( 'show_flags', true ),
        'show_names' => $module->get_setting( 'show_names', true ),
    ) );
}
add_action( 'aqualuxe_header_after_navigation', 'aqualuxe_wpml_add_language_switcher_to_header' );

/**
 * Add WPML language switcher to footer
 *
 * @return void
 */
function aqualuxe_wpml_add_language_switcher_to_footer() {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return;
    }
    
    // Check if WPML language switcher is enabled in footer
    if ( ! $module->get_setting( 'wpml_language_switcher_footer', true ) ) {
        return;
    }
    
    // Get language switcher style
    $style = $module->get_setting( 'wpml_language_switcher_style_footer', 'horizontal' );
    
    // Get language switcher
    aqualuxe_language_switcher( $style, array(
        'location' => 'footer',
        'show_flags' => $module->get_setting( 'show_flags', true ),
        'show_names' => $module->get_setting( 'show_names', true ),
    ) );
}
add_action( 'aqualuxe_footer_widgets_after', 'aqualuxe_wpml_add_language_switcher_to_footer' );

/**
 * Add WPML language switcher to mobile menu
 *
 * @return void
 */
function aqualuxe_wpml_add_language_switcher_to_mobile_menu() {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return;
    }
    
    // Check if WPML language switcher is enabled in mobile menu
    if ( ! $module->get_setting( 'wpml_language_switcher_mobile', true ) ) {
        return;
    }
    
    // Get language switcher style
    $style = $module->get_setting( 'wpml_language_switcher_style_mobile', 'horizontal' );
    
    // Get language switcher
    aqualuxe_language_switcher( $style, array(
        'location' => 'mobile',
        'show_flags' => $module->get_setting( 'show_flags', true ),
        'show_names' => $module->get_setting( 'show_names', true ),
    ) );
}
add_action( 'aqualuxe_mobile_menu_after', 'aqualuxe_wpml_add_language_switcher_to_mobile_menu' );

/**
 * Add WPML language switcher to WooCommerce account navigation
 *
 * @return void
 */
function aqualuxe_wpml_add_language_switcher_to_woocommerce_account() {
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return;
    }
    
    // Check if WPML language switcher is enabled in WooCommerce account
    if ( ! $module->get_setting( 'wpml_language_switcher_woocommerce_account', true ) ) {
        return;
    }
    
    // Get language switcher style
    $style = $module->get_setting( 'wpml_language_switcher_style_woocommerce_account', 'horizontal' );
    
    // Get language switcher
    aqualuxe_language_switcher( $style, array(
        'location' => 'woocommerce_account',
        'show_flags' => $module->get_setting( 'show_flags', true ),
        'show_names' => $module->get_setting( 'show_names', true ),
    ) );
}
add_action( 'woocommerce_after_account_navigation', 'aqualuxe_wpml_add_language_switcher_to_woocommerce_account' );

/**
 * Add WPML language switcher to WooCommerce checkout
 *
 * @return void
 */
function aqualuxe_wpml_add_language_switcher_to_woocommerce_checkout() {
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return;
    }
    
    // Check if WPML language switcher is enabled in WooCommerce checkout
    if ( ! $module->get_setting( 'wpml_language_switcher_woocommerce_checkout', true ) ) {
        return;
    }
    
    // Get language switcher style
    $style = $module->get_setting( 'wpml_language_switcher_style_woocommerce_checkout', 'horizontal' );
    
    // Get language switcher
    echo '<div class="aqualuxe-checkout-language-switcher">';
    aqualuxe_language_switcher( $style, array(
        'location' => 'woocommerce_checkout',
        'show_flags' => $module->get_setting( 'show_flags', true ),
        'show_names' => $module->get_setting( 'show_names', true ),
    ) );
    echo '</div>';
}
add_action( 'woocommerce_before_checkout_form', 'aqualuxe_wpml_add_language_switcher_to_woocommerce_checkout', 5 );

/**
 * Add WPML language switcher to WooCommerce cart
 *
 * @return void
 */
function aqualuxe_wpml_add_language_switcher_to_woocommerce_cart() {
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return;
    }
    
    // Check if WPML language switcher is enabled in WooCommerce cart
    if ( ! $module->get_setting( 'wpml_language_switcher_woocommerce_cart', true ) ) {
        return;
    }
    
    // Get language switcher style
    $style = $module->get_setting( 'wpml_language_switcher_style_woocommerce_cart', 'horizontal' );
    
    // Get language switcher
    echo '<div class="aqualuxe-cart-language-switcher">';
    aqualuxe_language_switcher( $style, array(
        'location' => 'woocommerce_cart',
        'show_flags' => $module->get_setting( 'show_flags', true ),
        'show_names' => $module->get_setting( 'show_names', true ),
    ) );
    echo '</div>';
}
add_action( 'woocommerce_before_cart', 'aqualuxe_wpml_add_language_switcher_to_woocommerce_cart', 5 );