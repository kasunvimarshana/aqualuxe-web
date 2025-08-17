<?php
/**
 * Layout Options
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Get layout options.
 *
 * @return array
 */
function aqualuxe_get_layout_options() {
    $options = array(
        'right-sidebar' => array(
            'label' => __( 'Right Sidebar', 'aqualuxe' ),
            'icon'  => 'right-sidebar',
        ),
        'left-sidebar'  => array(
            'label' => __( 'Left Sidebar', 'aqualuxe' ),
            'icon'  => 'left-sidebar',
        ),
        'no-sidebar'    => array(
            'label' => __( 'No Sidebar', 'aqualuxe' ),
            'icon'  => 'no-sidebar',
        ),
        'full-width'    => array(
            'label' => __( 'Full Width', 'aqualuxe' ),
            'icon'  => 'full-width',
        ),
    );

    return apply_filters( 'aqualuxe_layout_options', $options );
}

/**
 * Get container options.
 *
 * @return array
 */
function aqualuxe_get_container_options() {
    $options = array(
        'default'     => array(
            'label' => __( 'Default', 'aqualuxe' ),
            'icon'  => 'default',
        ),
        'boxed'       => array(
            'label' => __( 'Boxed', 'aqualuxe' ),
            'icon'  => 'boxed',
        ),
        'full-width'  => array(
            'label' => __( 'Full Width', 'aqualuxe' ),
            'icon'  => 'full-width',
        ),
        'fluid'       => array(
            'label' => __( 'Fluid', 'aqualuxe' ),
            'icon'  => 'fluid',
        ),
    );

    return apply_filters( 'aqualuxe_container_options', $options );
}

/**
 * Get current layout.
 *
 * @return string
 */
function aqualuxe_get_layout() {
    $default = 'right-sidebar';
    $layout = '';

    // Get layout based on the current page.
    if ( is_singular() ) {
        // Get layout from post meta.
        $layout = get_post_meta( get_the_ID(), '_aqualuxe_layout', true );

        // If no layout is set, get the default layout for the post type.
        if ( ! $layout ) {
            $post_type = get_post_type();
            $layout = get_theme_mod( 'aqualuxe_' . $post_type . '_layout', $default );
        }
    } elseif ( is_home() || is_archive() || is_search() ) {
        // Get layout for blog, archive, and search pages.
        $layout = get_theme_mod( 'aqualuxe_blog_layout', $default );
    }

    // If no layout is set, get the global layout.
    if ( ! $layout ) {
        $layout = get_theme_mod( 'aqualuxe_global_layout', $default );
    }

    return apply_filters( 'aqualuxe_layout', $layout );
}

/**
 * Get current container type.
 *
 * @return string
 */
function aqualuxe_get_container_type() {
    $default = 'default';
    $container = '';

    // Get container type based on the current page.
    if ( is_singular() ) {
        // Get container type from post meta.
        $container = get_post_meta( get_the_ID(), '_aqualuxe_container', true );

        // If no container type is set, get the default container type for the post type.
        if ( ! $container ) {
            $post_type = get_post_type();
            $container = get_theme_mod( 'aqualuxe_' . $post_type . '_container', $default );
        }
    } elseif ( is_home() || is_archive() || is_search() ) {
        // Get container type for blog, archive, and search pages.
        $container = get_theme_mod( 'aqualuxe_blog_container', $default );
    }

    // If no container type is set, get the global container type.
    if ( ! $container ) {
        $container = get_theme_mod( 'aqualuxe_global_container', $default );
    }

    return apply_filters( 'aqualuxe_container_type', $container );
}

/**
 * Get layout class.
 *
 * @return string
 */
function aqualuxe_get_layout_class() {
    $layout = aqualuxe_get_layout();
    $classes = array(
        'layout-' . $layout,
    );

    return implode( ' ', apply_filters( 'aqualuxe_layout_class', $classes ) );
}

/**
 * Get container class.
 *
 * @return string
 */
function aqualuxe_get_container_class() {
    $container = aqualuxe_get_container_type();
    $classes = array(
        'container-' . $container,
    );

    return implode( ' ', apply_filters( 'aqualuxe_container_class', $classes ) );
}

/**
 * Add layout options to customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function aqualuxe_layout_options_customizer( $wp_customize ) {
    // Add Layout Options section.
    $wp_customize->add_section(
        'aqualuxe_layout_options',
        array(
            'title'    => __( 'Layout Options', 'aqualuxe' ),
            'priority' => 130,
        )
    );

    // Global Layout.
    $wp_customize->add_setting(
        'aqualuxe_global_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Radio_Image_Control(
            $wp_customize,
            'aqualuxe_global_layout',
            array(
                'label'    => __( 'Global Layout', 'aqualuxe' ),
                'section'  => 'aqualuxe_layout_options',
                'choices'  => aqualuxe_get_layout_options(),
                'priority' => 10,
            )
        )
    );

    // Blog Layout.
    $wp_customize->add_setting(
        'aqualuxe_blog_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Radio_Image_Control(
            $wp_customize,
            'aqualuxe_blog_layout',
            array(
                'label'    => __( 'Blog Layout', 'aqualuxe' ),
                'section'  => 'aqualuxe_layout_options',
                'choices'  => aqualuxe_get_layout_options(),
                'priority' => 20,
            )
        )
    );

    // Post Layout.
    $wp_customize->add_setting(
        'aqualuxe_post_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Radio_Image_Control(
            $wp_customize,
            'aqualuxe_post_layout',
            array(
                'label'    => __( 'Post Layout', 'aqualuxe' ),
                'section'  => 'aqualuxe_layout_options',
                'choices'  => aqualuxe_get_layout_options(),
                'priority' => 30,
            )
        )
    );

    // Page Layout.
    $wp_customize->add_setting(
        'aqualuxe_page_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Radio_Image_Control(
            $wp_customize,
            'aqualuxe_page_layout',
            array(
                'label'    => __( 'Page Layout', 'aqualuxe' ),
                'section'  => 'aqualuxe_layout_options',
                'choices'  => aqualuxe_get_layout_options(),
                'priority' => 40,
            )
        )
    );

    // Global Container Type.
    $wp_customize->add_setting(
        'aqualuxe_global_container',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Radio_Image_Control(
            $wp_customize,
            'aqualuxe_global_container',
            array(
                'label'    => __( 'Global Container Type', 'aqualuxe' ),
                'section'  => 'aqualuxe_layout_options',
                'choices'  => aqualuxe_get_container_options(),
                'priority' => 50,
            )
        )
    );

    // Blog Container Type.
    $wp_customize->add_setting(
        'aqualuxe_blog_container',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Radio_Image_Control(
            $wp_customize,
            'aqualuxe_blog_container',
            array(
                'label'    => __( 'Blog Container Type', 'aqualuxe' ),
                'section'  => 'aqualuxe_layout_options',
                'choices'  => aqualuxe_get_container_options(),
                'priority' => 60,
            )
        )
    );

    // Post Container Type.
    $wp_customize->add_setting(
        'aqualuxe_post_container',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Radio_Image_Control(
            $wp_customize,
            'aqualuxe_post_container',
            array(
                'label'    => __( 'Post Container Type', 'aqualuxe' ),
                'section'  => 'aqualuxe_layout_options',
                'choices'  => aqualuxe_get_container_options(),
                'priority' => 70,
            )
        )
    );

    // Page Container Type.
    $wp_customize->add_setting(
        'aqualuxe_page_container',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Radio_Image_Control(
            $wp_customize,
            'aqualuxe_page_container',
            array(
                'label'    => __( 'Page Container Type', 'aqualuxe' ),
                'section'  => 'aqualuxe_layout_options',
                'choices'  => aqualuxe_get_container_options(),
                'priority' => 80,
            )
        )
    );
}
add_action( 'customize_register', 'aqualuxe_layout_options_customizer' );

/**
 * Add layout meta box to posts and pages.
 *
 * @return void
 */
function aqualuxe_layout_meta_box() {
    // Add meta box to posts.
    add_meta_box(
        'aqualuxe_layout_meta_box',
        __( 'Layout Options', 'aqualuxe' ),
        'aqualuxe_layout_meta_box_callback',
        array( 'post', 'page' ),
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'aqualuxe_layout_meta_box' );

/**
 * Layout meta box callback.
 *
 * @param WP_Post $post Post object.
 * @return void
 */
function aqualuxe_layout_meta_box_callback( $post ) {
    // Add nonce for security and authentication.
    wp_nonce_field( 'aqualuxe_layout_meta_box_nonce', 'aqualuxe_layout_meta_box_nonce' );

    // Get current values.
    $layout = get_post_meta( $post->ID, '_aqualuxe_layout', true );
    $container = get_post_meta( $post->ID, '_aqualuxe_container', true );

    // Layout options.
    $layout_options = aqualuxe_get_layout_options();
    ?>
    <p>
        <label for="aqualuxe_layout"><?php esc_html_e( 'Layout', 'aqualuxe' ); ?></label>
        <select name="aqualuxe_layout" id="aqualuxe_layout" class="widefat">
            <option value=""><?php esc_html_e( 'Default', 'aqualuxe' ); ?></option>
            <?php foreach ( $layout_options as $key => $option ) : ?>
                <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $layout, $key ); ?>><?php echo esc_html( $option['label'] ); ?></option>
            <?php endforeach; ?>
        </select>
    </p>

    <?php
    // Container options.
    $container_options = aqualuxe_get_container_options();
    ?>
    <p>
        <label for="aqualuxe_container"><?php esc_html_e( 'Container Type', 'aqualuxe' ); ?></label>
        <select name="aqualuxe_container" id="aqualuxe_container" class="widefat">
            <option value=""><?php esc_html_e( 'Default', 'aqualuxe' ); ?></option>
            <?php foreach ( $container_options as $key => $option ) : ?>
                <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $container, $key ); ?>><?php echo esc_html( $option['label'] ); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <?php
}

/**
 * Save layout meta box data.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function aqualuxe_save_layout_meta_box( $post_id ) {
    // Check if nonce is set.
    if ( ! isset( $_POST['aqualuxe_layout_meta_box_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['aqualuxe_layout_meta_box_nonce'], 'aqualuxe_layout_meta_box_nonce' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }
    } else {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    // Save layout.
    if ( isset( $_POST['aqualuxe_layout'] ) ) {
        $layout = sanitize_text_field( $_POST['aqualuxe_layout'] );
        update_post_meta( $post_id, '_aqualuxe_layout', $layout );
    }

    // Save container type.
    if ( isset( $_POST['aqualuxe_container'] ) ) {
        $container = sanitize_text_field( $_POST['aqualuxe_container'] );
        update_post_meta( $post_id, '_aqualuxe_container', $container );
    }
}
add_action( 'save_post', 'aqualuxe_save_layout_meta_box' );

/**
 * Add layout and container classes to body.
 *
 * @param array $classes Body classes.
 * @return array
 */
function aqualuxe_layout_body_classes( $classes ) {
    // Add layout class.
    $classes[] = aqualuxe_get_layout_class();

    // Add container class.
    $classes[] = aqualuxe_get_container_class();

    return $classes;
}
add_filter( 'body_class', 'aqualuxe_layout_body_classes' );