<?php
/**
 * Back to Top Button
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Display back to top button.
 *
 * @return void
 */
function aqualuxe_back_to_top() {
    // Return if back to top button is disabled.
    if ( ! get_theme_mod( 'aqualuxe_enable_back_to_top', true ) ) {
        return;
    }
    ?>
    <a href="#page" id="back-to-top" class="back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'aqualuxe' ); ?>">
        <span class="back-to-top-icon"></span>
        <span class="screen-reader-text"><?php esc_html_e( 'Back to top', 'aqualuxe' ); ?></span>
    </a>
    <?php
}
add_action( 'aqualuxe_after_footer', 'aqualuxe_back_to_top', 10 );

/**
 * Add back to top options to customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function aqualuxe_back_to_top_customizer( $wp_customize ) {
    // Enable Back to Top.
    $wp_customize->add_setting(
        'aqualuxe_enable_back_to_top',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_back_to_top',
        array(
            'label'    => __( 'Enable Back to Top Button', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout_options',
            'type'     => 'checkbox',
            'priority' => 30,
        )
    );

    // Back to Top Style.
    $wp_customize->add_setting(
        'aqualuxe_back_to_top_style',
        array(
            'default'           => 'circle',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_back_to_top_style',
        array(
            'label'    => __( 'Back to Top Button Style', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout_options',
            'type'     => 'select',
            'choices'  => array(
                'circle' => __( 'Circle', 'aqualuxe' ),
                'square' => __( 'Square', 'aqualuxe' ),
                'rounded' => __( 'Rounded Square', 'aqualuxe' ),
            ),
            'priority' => 40,
        )
    );

    // Back to Top Position.
    $wp_customize->add_setting(
        'aqualuxe_back_to_top_position',
        array(
            'default'           => 'right',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_back_to_top_position',
        array(
            'label'    => __( 'Back to Top Button Position', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout_options',
            'type'     => 'select',
            'choices'  => array(
                'right' => __( 'Right', 'aqualuxe' ),
                'left'  => __( 'Left', 'aqualuxe' ),
            ),
            'priority' => 50,
        )
    );
}
add_action( 'customize_register', 'aqualuxe_back_to_top_customizer' );

/**
 * Add back to top button classes.
 *
 * @param array $classes Body classes.
 * @return array
 */
function aqualuxe_back_to_top_body_classes( $classes ) {
    // Add back to top style class.
    $style = get_theme_mod( 'aqualuxe_back_to_top_style', 'circle' );
    $classes[] = 'back-to-top-style-' . $style;

    // Add back to top position class.
    $position = get_theme_mod( 'aqualuxe_back_to_top_position', 'right' );
    $classes[] = 'back-to-top-position-' . $position;

    return $classes;
}
add_filter( 'body_class', 'aqualuxe_back_to_top_body_classes' );