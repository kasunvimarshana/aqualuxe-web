<?php
/**
 * AquaLuxe Multilingual Module Polylang Compatibility
 *
 * @package AquaLuxe
 * @subpackage Modules/Multilingual
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Register strings for Polylang translation
 *
 * @param string $string String to register
 * @param string $name String name
 * @param string $domain Text domain
 * @return void
 */
function aqualuxe_polylang_register_string( $string, $name, $domain = 'aqualuxe' ) {
    if ( function_exists( 'pll_register_string' ) ) {
        pll_register_string( $name, $string, $domain );
    }
}

/**
 * Translate string with Polylang
 *
 * @param string $string String to translate
 * @param string $name String name
 * @param string $domain Text domain
 * @return string
 */
function aqualuxe_polylang_translate_string( $string, $name, $domain = 'aqualuxe' ) {
    if ( function_exists( 'pll__' ) ) {
        return pll__( $string );
    }
    
    return $string;
}

/**
 * Register theme options for Polylang translation
 *
 * @return void
 */
function aqualuxe_polylang_register_theme_options() {
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
            aqualuxe_polylang_register_string( $options[ $option ], $option, 'Theme Options' );
        }
    }
}
add_action( 'after_setup_theme', 'aqualuxe_polylang_register_theme_options' );

/**
 * Filter theme options for Polylang translation
 *
 * @param mixed $value Option value
 * @param string $option Option name
 * @return mixed
 */
function aqualuxe_polylang_filter_theme_options( $value, $option ) {
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
        return aqualuxe_polylang_translate_string( $value, $option, 'Theme Options' );
    }
    
    return $value;
}
add_filter( 'theme_mod_aqualuxe_header_phone', 'aqualuxe_polylang_filter_theme_options', 10, 2 );
add_filter( 'theme_mod_aqualuxe_header_email', 'aqualuxe_polylang_filter_theme_options', 10, 2 );
add_filter( 'theme_mod_aqualuxe_footer_copyright', 'aqualuxe_polylang_filter_theme_options', 10, 2 );
add_filter( 'theme_mod_aqualuxe_footer_address', 'aqualuxe_polylang_filter_theme_options', 10, 2 );
add_filter( 'theme_mod_aqualuxe_footer_phone', 'aqualuxe_polylang_filter_theme_options', 10, 2 );
add_filter( 'theme_mod_aqualuxe_footer_email', 'aqualuxe_polylang_filter_theme_options', 10, 2 );

/**
 * Add language switcher to Polylang language switcher menu
 *
 * @param array $items Menu items
 * @return array
 */
function aqualuxe_polylang_language_switcher_menu( $items ) {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return $items;
    }
    
    // Check if Polylang language switcher menu is enabled
    if ( ! $module->get_setting( 'polylang_language_switcher_menu', true ) ) {
        return $items;
    }
    
    // Get languages
    $languages = pll_languages_list( array( 'fields' => 'all' ) );
    
    if ( ! is_array( $languages ) ) {
        return $items;
    }
    
    // Get current language
    $current_language = pll_current_language();
    
    // Get language switcher style
    $style = $module->get_setting( 'polylang_language_switcher_style', 'dropdown' );
    
    // Get language switcher HTML
    ob_start();
    aqualuxe_language_switcher( $style, array(
        'location' => 'polylang',
        'show_flags' => $module->get_setting( 'show_flags', true ),
        'show_names' => $module->get_setting( 'show_names', true ),
    ) );
    $switcher = ob_get_clean();
    
    // Add language switcher to menu
    $items[] = array(
        'ID' => 'polylang-language-switcher',
        'title' => $switcher,
        'url' => '#',
        'menu_item_parent' => 0,
        'db_id' => 0,
        'classes' => array( 'menu-item', 'menu-item-language-switcher' ),
    );
    
    return $items;
}
add_filter( 'wp_nav_menu_items', 'aqualuxe_polylang_language_switcher_menu', 10, 2 );

/**
 * Register Polylang widgets
 *
 * @return void
 */
function aqualuxe_polylang_register_widgets() {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return;
    }
    
    // Check if Polylang widgets are enabled
    if ( ! $module->get_setting( 'polylang_widgets', true ) ) {
        return;
    }
    
    // Register language switcher widget
    register_widget( 'AquaLuxe_Polylang_Language_Switcher_Widget' );
}
add_action( 'widgets_init', 'aqualuxe_polylang_register_widgets' );

/**
 * Polylang Language Switcher Widget
 */
class AquaLuxe_Polylang_Language_Switcher_Widget extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_polylang_language_switcher',
            __( 'AquaLuxe Polylang Language Switcher', 'aqualuxe' ),
            array(
                'description' => __( 'Display a language switcher for Polylang.', 'aqualuxe' ),
                'classname' => 'widget_aqualuxe_polylang_language_switcher',
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
 * Add Polylang language switcher to header
 *
 * @return void
 */
function aqualuxe_polylang_add_language_switcher_to_header() {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return;
    }
    
    // Check if Polylang language switcher is enabled in header
    if ( ! $module->get_setting( 'polylang_language_switcher_header', true ) ) {
        return;
    }
    
    // Get language switcher style
    $style = $module->get_setting( 'polylang_language_switcher_style', 'dropdown' );
    
    // Get language switcher
    aqualuxe_language_switcher( $style, array(
        'location' => 'header',
        'show_flags' => $module->get_setting( 'show_flags', true ),
        'show_names' => $module->get_setting( 'show_names', true ),
    ) );
}
add_action( 'aqualuxe_header_after_navigation', 'aqualuxe_polylang_add_language_switcher_to_header' );

/**
 * Add Polylang language switcher to footer
 *
 * @return void
 */
function aqualuxe_polylang_add_language_switcher_to_footer() {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return;
    }
    
    // Check if Polylang language switcher is enabled in footer
    if ( ! $module->get_setting( 'polylang_language_switcher_footer', true ) ) {
        return;
    }
    
    // Get language switcher style
    $style = $module->get_setting( 'polylang_language_switcher_style_footer', 'horizontal' );
    
    // Get language switcher
    aqualuxe_language_switcher( $style, array(
        'location' => 'footer',
        'show_flags' => $module->get_setting( 'show_flags', true ),
        'show_names' => $module->get_setting( 'show_names', true ),
    ) );
}
add_action( 'aqualuxe_footer_widgets_after', 'aqualuxe_polylang_add_language_switcher_to_footer' );

/**
 * Add Polylang language switcher to mobile menu
 *
 * @return void
 */
function aqualuxe_polylang_add_language_switcher_to_mobile_menu() {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return;
    }
    
    // Check if Polylang language switcher is enabled in mobile menu
    if ( ! $module->get_setting( 'polylang_language_switcher_mobile', true ) ) {
        return;
    }
    
    // Get language switcher style
    $style = $module->get_setting( 'polylang_language_switcher_style_mobile', 'horizontal' );
    
    // Get language switcher
    aqualuxe_language_switcher( $style, array(
        'location' => 'mobile',
        'show_flags' => $module->get_setting( 'show_flags', true ),
        'show_names' => $module->get_setting( 'show_names', true ),
    ) );
}
add_action( 'aqualuxe_mobile_menu_after', 'aqualuxe_polylang_add_language_switcher_to_mobile_menu' );

/**
 * Add Polylang language switcher to WooCommerce account navigation
 *
 * @return void
 */
function aqualuxe_polylang_add_language_switcher_to_woocommerce_account() {
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return;
    }
    
    // Check if Polylang language switcher is enabled in WooCommerce account
    if ( ! $module->get_setting( 'polylang_language_switcher_woocommerce_account', true ) ) {
        return;
    }
    
    // Get language switcher style
    $style = $module->get_setting( 'polylang_language_switcher_style_woocommerce_account', 'horizontal' );
    
    // Get language switcher
    aqualuxe_language_switcher( $style, array(
        'location' => 'woocommerce_account',
        'show_flags' => $module->get_setting( 'show_flags', true ),
        'show_names' => $module->get_setting( 'show_names', true ),
    ) );
}
add_action( 'woocommerce_after_account_navigation', 'aqualuxe_polylang_add_language_switcher_to_woocommerce_account' );

/**
 * Add Polylang language switcher to WooCommerce checkout
 *
 * @return void
 */
function aqualuxe_polylang_add_language_switcher_to_woocommerce_checkout() {
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return;
    }
    
    // Check if Polylang language switcher is enabled in WooCommerce checkout
    if ( ! $module->get_setting( 'polylang_language_switcher_woocommerce_checkout', true ) ) {
        return;
    }
    
    // Get language switcher style
    $style = $module->get_setting( 'polylang_language_switcher_style_woocommerce_checkout', 'horizontal' );
    
    // Get language switcher
    echo '<div class="aqualuxe-checkout-language-switcher">';
    aqualuxe_language_switcher( $style, array(
        'location' => 'woocommerce_checkout',
        'show_flags' => $module->get_setting( 'show_flags', true ),
        'show_names' => $module->get_setting( 'show_names', true ),
    ) );
    echo '</div>';
}
add_action( 'woocommerce_before_checkout_form', 'aqualuxe_polylang_add_language_switcher_to_woocommerce_checkout', 5 );

/**
 * Add Polylang language switcher to WooCommerce cart
 *
 * @return void
 */
function aqualuxe_polylang_add_language_switcher_to_woocommerce_cart() {
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return;
    }
    
    // Check if Polylang language switcher is enabled in WooCommerce cart
    if ( ! $module->get_setting( 'polylang_language_switcher_woocommerce_cart', true ) ) {
        return;
    }
    
    // Get language switcher style
    $style = $module->get_setting( 'polylang_language_switcher_style_woocommerce_cart', 'horizontal' );
    
    // Get language switcher
    echo '<div class="aqualuxe-cart-language-switcher">';
    aqualuxe_language_switcher( $style, array(
        'location' => 'woocommerce_cart',
        'show_flags' => $module->get_setting( 'show_flags', true ),
        'show_names' => $module->get_setting( 'show_names', true ),
    ) );
    echo '</div>';
}
add_action( 'woocommerce_before_cart', 'aqualuxe_polylang_add_language_switcher_to_woocommerce_cart', 5 );

/**
 * Add language column to posts list
 *
 * @param array $columns Columns
 * @return array
 */
function aqualuxe_polylang_add_language_column( $columns ) {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return $columns;
    }
    
    // Check if language column is enabled
    if ( ! $module->get_setting( 'polylang_language_column', true ) ) {
        return $columns;
    }
    
    // Add language column
    $new_columns = array();
    
    foreach ( $columns as $key => $value ) {
        $new_columns[ $key ] = $value;
        
        if ( $key === 'title' ) {
            $new_columns['language'] = __( 'Language', 'aqualuxe' );
        }
    }
    
    return $new_columns;
}
add_filter( 'manage_posts_columns', 'aqualuxe_polylang_add_language_column' );
add_filter( 'manage_pages_columns', 'aqualuxe_polylang_add_language_column' );

/**
 * Add language column content
 *
 * @param string $column Column name
 * @param int $post_id Post ID
 * @return void
 */
function aqualuxe_polylang_add_language_column_content( $column, $post_id ) {
    if ( $column !== 'language' ) {
        return;
    }
    
    // Get language
    $language = pll_get_post_language( $post_id );
    
    if ( ! $language ) {
        echo '—';
        return;
    }
    
    // Get language name
    $language_name = aqualuxe_get_language_name( $language );
    
    // Get language flag
    $language_flag = aqualuxe_get_language_flag_url( $language );
    
    // Output
    if ( $language_flag ) {
        echo '<img src="' . esc_url( $language_flag ) . '" alt="' . esc_attr( $language_name ) . '" title="' . esc_attr( $language_name ) . '" width="16" height="11" style="margin-right: 5px; vertical-align: middle;" />';
    }
    
    echo esc_html( $language_name );
}
add_action( 'manage_posts_custom_column', 'aqualuxe_polylang_add_language_column_content', 10, 2 );
add_action( 'manage_pages_custom_column', 'aqualuxe_polylang_add_language_column_content', 10, 2 );

/**
 * Add language filter to posts list
 *
 * @param string $post_type Post type
 * @return void
 */
function aqualuxe_polylang_add_language_filter( $post_type ) {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return;
    }
    
    // Check if language filter is enabled
    if ( ! $module->get_setting( 'polylang_language_filter', true ) ) {
        return;
    }
    
    // Check if post type is translatable
    if ( ! pll_is_translated_post_type( $post_type ) ) {
        return;
    }
    
    // Get languages
    $languages = pll_languages_list( array( 'fields' => 'all' ) );
    
    if ( ! is_array( $languages ) ) {
        return;
    }
    
    // Get current language
    $current_language = isset( $_GET['lang'] ) ? sanitize_text_field( $_GET['lang'] ) : '';
    
    // Output filter
    ?>
    <select name="lang" id="filter-by-language">
        <option value=""><?php esc_html_e( 'All languages', 'aqualuxe' ); ?></option>
        <?php foreach ( $languages as $language ) : ?>
            <option value="<?php echo esc_attr( $language->slug ); ?>" <?php selected( $current_language, $language->slug ); ?>>
                <?php echo esc_html( $language->name ); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}
add_action( 'restrict_manage_posts', 'aqualuxe_polylang_add_language_filter' );

/**
 * Filter posts by language
 *
 * @param WP_Query $query Query object
 * @return void
 */
function aqualuxe_polylang_filter_posts_by_language( $query ) {
    // Check if admin
    if ( ! is_admin() ) {
        return;
    }
    
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return;
    }
    
    // Check if language filter is enabled
    if ( ! $module->get_setting( 'polylang_language_filter', true ) ) {
        return;
    }
    
    // Check if language filter is set
    if ( ! isset( $_GET['lang'] ) || empty( $_GET['lang'] ) ) {
        return;
    }
    
    // Get language
    $language = sanitize_text_field( $_GET['lang'] );
    
    // Set language
    $query->set( 'lang', $language );
}
add_action( 'pre_get_posts', 'aqualuxe_polylang_filter_posts_by_language' );