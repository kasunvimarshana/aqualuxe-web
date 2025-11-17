<?php
/**
 * Top Bar
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Display top bar.
 *
 * @return void
 */
function aqualuxe_top_bar() {
    // Return if top bar is disabled.
    if ( ! get_theme_mod( 'aqualuxe_enable_top_bar', true ) ) {
        return;
    }
    ?>
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-inner">
                <div class="top-bar-left">
                    <?php
                    /**
                     * Hook: aqualuxe_top_bar_left
                     *
                     * @hooked aqualuxe_top_bar_text - 10
                     */
                    do_action( 'aqualuxe_top_bar_left' );
                    ?>
                </div><!-- .top-bar-left -->

                <div class="top-bar-right">
                    <?php
                    /**
                     * Hook: aqualuxe_top_bar_right
                     *
                     * @hooked aqualuxe_top_bar_contact - 10
                     * @hooked aqualuxe_add_social_icons_to_top_bar - 20
                     */
                    do_action( 'aqualuxe_top_bar_right' );
                    ?>
                </div><!-- .top-bar-right -->
            </div><!-- .top-bar-inner -->
        </div><!-- .container -->
    </div><!-- .top-bar -->
    <?php
}
add_action( 'aqualuxe_before_header', 'aqualuxe_top_bar', 10 );

/**
 * Display top bar text.
 *
 * @return void
 */
function aqualuxe_top_bar_text() {
    $text = get_theme_mod( 'aqualuxe_top_bar_text', __( 'Welcome to AquaLuxe', 'aqualuxe' ) );
    if ( $text ) {
        echo '<div class="top-bar-text">' . wp_kses_post( $text ) . '</div>';
    }
}
add_action( 'aqualuxe_top_bar_left', 'aqualuxe_top_bar_text', 10 );

/**
 * Display top bar contact information.
 *
 * @return void
 */
function aqualuxe_top_bar_contact() {
    $phone = get_theme_mod( 'aqualuxe_contact_phone' );
    $email = get_theme_mod( 'aqualuxe_contact_email' );

    if ( ! $phone && ! $email ) {
        return;
    }
    ?>
    <div class="top-bar-contact">
        <?php if ( $phone ) : ?>
            <span class="top-bar-phone">
                <i class="icon-phone"></i>
                <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
            </span>
        <?php endif; ?>

        <?php if ( $email ) : ?>
            <span class="top-bar-email">
                <i class="icon-envelope"></i>
                <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
            </span>
        <?php endif; ?>
    </div>
    <?php
}
add_action( 'aqualuxe_top_bar_right', 'aqualuxe_top_bar_contact', 10 );

/**
 * Add top bar options to customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function aqualuxe_top_bar_customizer( $wp_customize ) {
    // Add Top Bar section.
    $wp_customize->add_section(
        'aqualuxe_top_bar',
        array(
            'title'    => __( 'Top Bar', 'aqualuxe' ),
            'priority' => 110,
        )
    );

    // Enable Top Bar.
    $wp_customize->add_setting(
        'aqualuxe_enable_top_bar',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_top_bar',
        array(
            'label'    => __( 'Enable Top Bar', 'aqualuxe' ),
            'section'  => 'aqualuxe_top_bar',
            'type'     => 'checkbox',
            'priority' => 10,
        )
    );

    // Top Bar Text.
    $wp_customize->add_setting(
        'aqualuxe_top_bar_text',
        array(
            'default'           => __( 'Welcome to AquaLuxe', 'aqualuxe' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_top_bar_text',
        array(
            'label'    => __( 'Top Bar Text', 'aqualuxe' ),
            'section'  => 'aqualuxe_top_bar',
            'type'     => 'text',
            'priority' => 20,
        )
    );

    // Contact Phone.
    $wp_customize->add_setting(
        'aqualuxe_contact_phone',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_contact_phone',
        array(
            'label'    => __( 'Phone Number', 'aqualuxe' ),
            'section'  => 'aqualuxe_top_bar',
            'type'     => 'text',
            'priority' => 30,
        )
    );

    // Contact Email.
    $wp_customize->add_setting(
        'aqualuxe_contact_email',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_email',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_contact_email',
        array(
            'label'    => __( 'Email Address', 'aqualuxe' ),
            'section'  => 'aqualuxe_top_bar',
            'type'     => 'email',
            'priority' => 40,
        )
    );

    // Show Social Icons in Top Bar.
    $wp_customize->add_setting(
        'aqualuxe_show_social_icons_top_bar',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_social_icons_top_bar',
        array(
            'label'    => __( 'Show Social Icons in Top Bar', 'aqualuxe' ),
            'section'  => 'aqualuxe_top_bar',
            'type'     => 'checkbox',
            'priority' => 50,
        )
    );
}
add_action( 'customize_register', 'aqualuxe_top_bar_customizer' );