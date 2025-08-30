<?php
/**
 * Footer Widgets
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Register footer widget areas.
 *
 * @return void
 */
function aqualuxe_footer_widgets_init() {
    $footer_columns = get_theme_mod( 'aqualuxe_footer_widget_columns', 4 );
    
    // Register footer widget areas based on the number of columns.
    for ( $i = 1; $i <= $footer_columns; $i++ ) {
        register_sidebar(
            array(
                'name'          => sprintf( __( 'Footer %d', 'aqualuxe' ), $i ),
                'id'            => 'footer-' . $i,
                'description'   => sprintf( __( 'Add widgets here to appear in footer column %d.', 'aqualuxe' ), $i ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );
    }
}
add_action( 'widgets_init', 'aqualuxe_footer_widgets_init' );

/**
 * Get footer widget columns.
 *
 * @return int
 */
function aqualuxe_get_footer_widget_columns() {
    $columns = get_theme_mod( 'aqualuxe_footer_widget_columns', 4 );
    return apply_filters( 'aqualuxe_footer_widget_columns', $columns );
}

/**
 * Get footer widget class.
 *
 * @return string
 */
function aqualuxe_get_footer_widget_class() {
    $columns = aqualuxe_get_footer_widget_columns();
    $classes = array(
        'footer-widgets',
        'footer-widgets-' . $columns,
    );

    return implode( ' ', apply_filters( 'aqualuxe_footer_widget_class', $classes ) );
}

/**
 * Display footer widgets.
 *
 * @return void
 */
function aqualuxe_display_footer_widgets() {
    $columns = aqualuxe_get_footer_widget_columns();
    $has_widgets = false;

    // Check if any footer widget area has widgets.
    for ( $i = 1; $i <= $columns; $i++ ) {
        if ( is_active_sidebar( 'footer-' . $i ) ) {
            $has_widgets = true;
            break;
        }
    }

    // Return if no widgets.
    if ( ! $has_widgets ) {
        return;
    }
    ?>
    <div class="<?php echo esc_attr( aqualuxe_get_footer_widget_class() ); ?>">
        <div class="container">
            <div class="footer-widgets-inner">
                <?php for ( $i = 1; $i <= $columns; $i++ ) : ?>
                    <div class="footer-widget-area footer-widget-area-<?php echo esc_attr( $i ); ?>">
                        <?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
                            <?php dynamic_sidebar( 'footer-' . $i ); ?>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
    <?php
}
add_action( 'aqualuxe_before_footer', 'aqualuxe_display_footer_widgets', 10 );

/**
 * Add footer widget columns option to customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function aqualuxe_footer_widget_columns_customizer( $wp_customize ) {
    // Footer Widget Columns
    $wp_customize->add_setting(
        'aqualuxe_footer_widget_columns',
        array(
            'default'           => 4,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_widget_columns',
        array(
            'label'    => __( 'Footer Widget Columns', 'aqualuxe' ),
            'section'  => 'aqualuxe_footer_options',
            'type'     => 'select',
            'choices'  => array(
                1 => __( '1 Column', 'aqualuxe' ),
                2 => __( '2 Columns', 'aqualuxe' ),
                3 => __( '3 Columns', 'aqualuxe' ),
                4 => __( '4 Columns', 'aqualuxe' ),
            ),
            'priority' => 10,
        )
    );

    // Footer Copyright Text
    $wp_customize->add_setting(
        'aqualuxe_footer_copyright',
        array(
            'default'           => sprintf( __( '© %s AquaLuxe. All rights reserved.', 'aqualuxe' ), date( 'Y' ) ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_copyright',
        array(
            'label'    => __( 'Footer Copyright Text', 'aqualuxe' ),
            'section'  => 'aqualuxe_footer_options',
            'type'     => 'textarea',
            'priority' => 20,
        )
    );
}
add_action( 'customize_register', 'aqualuxe_footer_widget_columns_customizer' );