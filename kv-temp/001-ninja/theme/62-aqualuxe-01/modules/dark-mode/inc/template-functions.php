<?php
/**
 * AquaLuxe Dark Mode Module Template Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/Dark_Mode
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Display dark mode toggle
 *
 * @param string $style Toggle style (switch, icon, button)
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_dark_mode_toggle( $style = 'switch', $args = array() ) {
    // Default arguments
    $defaults = array(
        'location' => 'header',
    );
    
    // Parse arguments
    $args = wp_parse_args( $args, $defaults );
    
    // Get template part
    aqualuxe_get_template_part( 'template-parts/dark-mode-toggle', $style, $args );
}

/**
 * Display switch style dark mode toggle
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_dark_mode_toggle_switch( $args = array() ) {
    // Default arguments
    $defaults = array(
        'location' => 'header',
    );
    
    // Parse arguments
    $args = wp_parse_args( $args, $defaults );
    
    // Add class based on location
    $class = 'aqualuxe-dark-mode-toggle aqualuxe-dark-mode-toggle--switch aqualuxe-dark-mode-toggle--' . esc_attr( $args['location'] );
    
    // Output toggle
    ?>
    <div class="<?php echo esc_attr( $class ); ?>">
        <label for="aqualuxe-dark-mode-toggle-<?php echo esc_attr( $args['location'] ); ?>" class="aqualuxe-dark-mode-toggle__label">
            <input type="checkbox" id="aqualuxe-dark-mode-toggle-<?php echo esc_attr( $args['location'] ); ?>" class="aqualuxe-dark-mode-toggle__checkbox">
            <span class="aqualuxe-dark-mode-toggle__switch"></span>
            <span class="aqualuxe-dark-mode-toggle__text"><?php esc_html_e( 'Dark Mode', 'aqualuxe' ); ?></span>
        </label>
    </div>
    <?php
}

/**
 * Display icon style dark mode toggle
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_dark_mode_toggle_icon( $args = array() ) {
    // Default arguments
    $defaults = array(
        'location' => 'header',
    );
    
    // Parse arguments
    $args = wp_parse_args( $args, $defaults );
    
    // Add class based on location
    $class = 'aqualuxe-dark-mode-toggle aqualuxe-dark-mode-toggle--icon aqualuxe-dark-mode-toggle--' . esc_attr( $args['location'] );
    
    // Output toggle
    ?>
    <div class="<?php echo esc_attr( $class ); ?>">
        <button type="button" class="aqualuxe-dark-mode-toggle__button" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'aqualuxe' ); ?>">
            <span class="aqualuxe-dark-mode-toggle__icon aqualuxe-dark-mode-toggle__icon--light">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 18a6 6 0 1 1 0-12 6 6 0 0 1 0 12zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM11 1h2v3h-2V1zm0 19h2v3h-2v-3zM3.515 4.929l1.414-1.414L7.05 5.636 5.636 7.05 3.515 4.93zM16.95 18.364l1.414-1.414 2.121 2.121-1.414 1.414-2.121-2.121zm2.121-14.85l1.414 1.415-2.121 2.121-1.414-1.414 2.121-2.121zM5.636 16.95l1.414 1.414-2.121 2.121-1.414-1.414 2.121-2.121zM23 11v2h-3v-2h3zM4 11v2H1v-2h3z"/></svg>
            </span>
            <span class="aqualuxe-dark-mode-toggle__icon aqualuxe-dark-mode-toggle__icon--dark">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M10 7a7 7 0 0 0 12 4.9v.1c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2h.1A6.979 6.979 0 0 0 10 7zm-6 5a8 8 0 0 0 15.062 3.762A9 9 0 0 1 8.238 4.938 7.999 7.999 0 0 0 4 12z"/></svg>
            </span>
        </button>
    </div>
    <?php
}

/**
 * Display button style dark mode toggle
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_dark_mode_toggle_button( $args = array() ) {
    // Default arguments
    $defaults = array(
        'location' => 'header',
    );
    
    // Parse arguments
    $args = wp_parse_args( $args, $defaults );
    
    // Add class based on location
    $class = 'aqualuxe-dark-mode-toggle aqualuxe-dark-mode-toggle--button aqualuxe-dark-mode-toggle--' . esc_attr( $args['location'] );
    
    // Output toggle
    ?>
    <div class="<?php echo esc_attr( $class ); ?>">
        <button type="button" class="aqualuxe-dark-mode-toggle__button">
            <span class="aqualuxe-dark-mode-toggle__icon aqualuxe-dark-mode-toggle__icon--light">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 18a6 6 0 1 1 0-12 6 6 0 0 1 0 12zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM11 1h2v3h-2V1zm0 19h2v3h-2v-3zM3.515 4.929l1.414-1.414L7.05 5.636 5.636 7.05 3.515 4.93zM16.95 18.364l1.414-1.414 2.121 2.121-1.414 1.414-2.121-2.121zm2.121-14.85l1.414 1.415-2.121 2.121-1.414-1.414 2.121-2.121zM5.636 16.95l1.414 1.414-2.121 2.121-1.414-1.414 2.121-2.121zM23 11v2h-3v-2h3zM4 11v2H1v-2h3z"/></svg>
            </span>
            <span class="aqualuxe-dark-mode-toggle__icon aqualuxe-dark-mode-toggle__icon--dark">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M10 7a7 7 0 0 0 12 4.9v.1c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2h.1A6.979 6.979 0 0 0 10 7zm-6 5a8 8 0 0 0 15.062 3.762A9 9 0 0 1 8.238 4.938 7.999 7.999 0 0 0 4 12z"/></svg>
            </span>
            <span class="aqualuxe-dark-mode-toggle__text aqualuxe-dark-mode-toggle__text--light"><?php esc_html_e( 'Light', 'aqualuxe' ); ?></span>
            <span class="aqualuxe-dark-mode-toggle__text aqualuxe-dark-mode-toggle__text--dark"><?php esc_html_e( 'Dark', 'aqualuxe' ); ?></span>
        </button>
    </div>
    <?php
}

/**
 * Add dark mode toggle to header
 *
 * @return void
 */
function aqualuxe_add_dark_mode_toggle_to_header() {
    // Check if dark mode is active
    if ( ! aqualuxe_is_dark_mode_active() ) {
        return;
    }
    
    // Check if toggle should be shown in header
    if ( ! aqualuxe_show_dark_mode_toggle( 'header' ) ) {
        return;
    }
    
    // Get toggle style
    $style = aqualuxe_get_dark_mode_toggle_style( 'header' );
    
    // Display toggle
    aqualuxe_dark_mode_toggle( $style, array(
        'location' => 'header',
    ) );
}
add_action( 'aqualuxe_header_after_navigation', 'aqualuxe_add_dark_mode_toggle_to_header' );

/**
 * Add dark mode toggle to footer
 *
 * @return void
 */
function aqualuxe_add_dark_mode_toggle_to_footer() {
    // Check if dark mode is active
    if ( ! aqualuxe_is_dark_mode_active() ) {
        return;
    }
    
    // Check if toggle should be shown in footer
    if ( ! aqualuxe_show_dark_mode_toggle( 'footer' ) ) {
        return;
    }
    
    // Get toggle style
    $style = aqualuxe_get_dark_mode_toggle_style( 'footer' );
    
    // Display toggle
    aqualuxe_dark_mode_toggle( $style, array(
        'location' => 'footer',
    ) );
}
add_action( 'aqualuxe_footer_widgets_after', 'aqualuxe_add_dark_mode_toggle_to_footer' );

/**
 * Add dark mode toggle to mobile menu
 *
 * @return void
 */
function aqualuxe_add_dark_mode_toggle_to_mobile_menu() {
    // Check if dark mode is active
    if ( ! aqualuxe_is_dark_mode_active() ) {
        return;
    }
    
    // Check if toggle should be shown in mobile menu
    if ( ! aqualuxe_show_dark_mode_toggle( 'mobile' ) ) {
        return;
    }
    
    // Get toggle style
    $style = aqualuxe_get_dark_mode_toggle_style( 'mobile' );
    
    // Display toggle
    aqualuxe_dark_mode_toggle( $style, array(
        'location' => 'mobile',
    ) );
}
add_action( 'aqualuxe_mobile_menu_after', 'aqualuxe_add_dark_mode_toggle_to_mobile_menu' );

/**
 * Add dark mode toggle widget
 *
 * @return void
 */
function aqualuxe_register_dark_mode_toggle_widget() {
    // Check if dark mode is active
    if ( ! aqualuxe_is_dark_mode_active() ) {
        return;
    }
    
    // Register widget
    register_widget( 'AquaLuxe_Dark_Mode_Toggle_Widget' );
}
add_action( 'widgets_init', 'aqualuxe_register_dark_mode_toggle_widget' );

/**
 * Dark Mode Toggle Widget
 */
class AquaLuxe_Dark_Mode_Toggle_Widget extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_dark_mode_toggle',
            __( 'AquaLuxe Dark Mode Toggle', 'aqualuxe' ),
            array(
                'description' => __( 'Display a dark mode toggle button.', 'aqualuxe' ),
                'classname' => 'widget_aqualuxe_dark_mode_toggle',
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
        $style = ! empty( $instance['style'] ) ? $instance['style'] : 'switch';
        
        // Before widget
        echo $args['before_widget'];
        
        // Title
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        // Dark mode toggle
        aqualuxe_dark_mode_toggle( $style, array(
            'location' => 'widget',
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
        $style = ! empty( $instance['style'] ) ? $instance['style'] : 'switch';
        
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Style:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>">
                <option value="switch" <?php selected( $style, 'switch' ); ?>><?php esc_html_e( 'Switch', 'aqualuxe' ); ?></option>
                <option value="icon" <?php selected( $style, 'icon' ); ?>><?php esc_html_e( 'Icon', 'aqualuxe' ); ?></option>
                <option value="button" <?php selected( $style, 'button' ); ?>><?php esc_html_e( 'Button', 'aqualuxe' ); ?></option>
            </select>
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
        $instance['style'] = ! empty( $new_instance['style'] ) ? sanitize_text_field( $new_instance['style'] ) : 'switch';
        
        return $instance;
    }
}