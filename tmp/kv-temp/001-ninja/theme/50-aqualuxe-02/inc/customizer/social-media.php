<?php
/**
 * AquaLuxe Social Media Customizer Options
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add social media options to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_social_media_customizer_options( $wp_customize ) {
    // Add Social Media section
    $wp_customize->add_section( 'aqualuxe_social_media', array(
        'title'    => __( 'Social Media', 'aqualuxe' ),
        'priority' => 90,
    ) );

    // Facebook
    $wp_customize->add_setting( 'aqualuxe_social_facebook', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( 'aqualuxe_social_facebook', array(
        'label'    => __( 'Facebook URL', 'aqualuxe' ),
        'section'  => 'aqualuxe_social_media',
        'type'     => 'url',
    ) );

    // Twitter
    $wp_customize->add_setting( 'aqualuxe_social_twitter', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( 'aqualuxe_social_twitter', array(
        'label'    => __( 'Twitter URL', 'aqualuxe' ),
        'section'  => 'aqualuxe_social_media',
        'type'     => 'url',
    ) );

    // Instagram
    $wp_customize->add_setting( 'aqualuxe_social_instagram', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( 'aqualuxe_social_instagram', array(
        'label'    => __( 'Instagram URL', 'aqualuxe' ),
        'section'  => 'aqualuxe_social_media',
        'type'     => 'url',
    ) );

    // YouTube
    $wp_customize->add_setting( 'aqualuxe_social_youtube', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( 'aqualuxe_social_youtube', array(
        'label'    => __( 'YouTube URL', 'aqualuxe' ),
        'section'  => 'aqualuxe_social_media',
        'type'     => 'url',
    ) );

    // LinkedIn
    $wp_customize->add_setting( 'aqualuxe_social_linkedin', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( 'aqualuxe_social_linkedin', array(
        'label'    => __( 'LinkedIn URL', 'aqualuxe' ),
        'section'  => 'aqualuxe_social_media',
        'type'     => 'url',
    ) );

    // Pinterest
    $wp_customize->add_setting( 'aqualuxe_social_pinterest', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( 'aqualuxe_social_pinterest', array(
        'label'    => __( 'Pinterest URL', 'aqualuxe' ),
        'section'  => 'aqualuxe_social_media',
        'type'     => 'url',
    ) );

    // TikTok
    $wp_customize->add_setting( 'aqualuxe_social_tiktok', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( 'aqualuxe_social_tiktok', array(
        'label'    => __( 'TikTok URL', 'aqualuxe' ),
        'section'  => 'aqualuxe_social_media',
        'type'     => 'url',
    ) );

    // Social Media Display Options
    $wp_customize->add_setting( 'aqualuxe_social_header_display', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'aqualuxe_social_header_display', array(
        'label'    => __( 'Display in Header', 'aqualuxe' ),
        'section'  => 'aqualuxe_social_media',
        'type'     => 'checkbox',
    ) );

    $wp_customize->add_setting( 'aqualuxe_social_footer_display', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'aqualuxe_social_footer_display', array(
        'label'    => __( 'Display in Footer', 'aqualuxe' ),
        'section'  => 'aqualuxe_social_media',
        'type'     => 'checkbox',
    ) );

    // Social Media Icon Style
    $wp_customize->add_setting( 'aqualuxe_social_icon_style', array(
        'default'           => 'rounded',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ) );

    $wp_customize->add_control( 'aqualuxe_social_icon_style', array(
        'label'    => __( 'Icon Style', 'aqualuxe' ),
        'section'  => 'aqualuxe_social_media',
        'type'     => 'select',
        'choices'  => array(
            'rounded'    => __( 'Rounded', 'aqualuxe' ),
            'square'     => __( 'Square', 'aqualuxe' ),
            'circle'     => __( 'Circle', 'aqualuxe' ),
            'plain'      => __( 'Plain (No Background)', 'aqualuxe' ),
        ),
    ) );

    // Social Media Icon Size
    $wp_customize->add_setting( 'aqualuxe_social_icon_size', array(
        'default'           => 'medium',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ) );

    $wp_customize->add_control( 'aqualuxe_social_icon_size', array(
        'label'    => __( 'Icon Size', 'aqualuxe' ),
        'section'  => 'aqualuxe_social_media',
        'type'     => 'select',
        'choices'  => array(
            'small'     => __( 'Small', 'aqualuxe' ),
            'medium'    => __( 'Medium', 'aqualuxe' ),
            'large'     => __( 'Large', 'aqualuxe' ),
        ),
    ) );

    // Social Share Buttons
    $wp_customize->add_setting( 'aqualuxe_social_share_posts', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'aqualuxe_social_share_posts', array(
        'label'    => __( 'Enable Social Sharing on Posts', 'aqualuxe' ),
        'section'  => 'aqualuxe_social_media',
        'type'     => 'checkbox',
    ) );

    $wp_customize->add_setting( 'aqualuxe_social_share_products', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'aqualuxe_social_share_products', array(
        'label'    => __( 'Enable Social Sharing on Products', 'aqualuxe' ),
        'section'  => 'aqualuxe_social_media',
        'type'     => 'checkbox',
    ) );
}
add_action( 'customize_register', 'aqualuxe_social_media_customizer_options' );

/**
 * Sanitize checkbox.
 *
 * @param bool $input Input value.
 * @return bool Sanitized value.
 */
function aqualuxe_sanitize_checkbox( $input ) {
    return ( isset( $input ) && true == $input ) ? true : false;
}

/**
 * Sanitize select.
 *
 * @param string $input Input value.
 * @param WP_Customize_Setting $setting Setting object.
 * @return string Sanitized value.
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    $input = sanitize_key( $input );
    $choices = $setting->manager->get_control( $setting->id )->choices;
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Display social media icons.
 *
 * @param string $location Location of the icons (header or footer).
 */
function aqualuxe_display_social_icons( $location = 'header' ) {
    // Check if social icons should be displayed in this location
    $display_setting = 'aqualuxe_social_' . $location . '_display';
    if ( ! get_theme_mod( $display_setting, true ) ) {
        return;
    }

    // Get social media URLs
    $social_networks = array(
        'facebook'   => array(
            'url'   => get_theme_mod( 'aqualuxe_social_facebook', '' ),
            'icon'  => 'fab fa-facebook-f',
            'label' => __( 'Facebook', 'aqualuxe' ),
        ),
        'twitter'    => array(
            'url'   => get_theme_mod( 'aqualuxe_social_twitter', '' ),
            'icon'  => 'fab fa-twitter',
            'label' => __( 'Twitter', 'aqualuxe' ),
        ),
        'instagram'  => array(
            'url'   => get_theme_mod( 'aqualuxe_social_instagram', '' ),
            'icon'  => 'fab fa-instagram',
            'label' => __( 'Instagram', 'aqualuxe' ),
        ),
        'youtube'    => array(
            'url'   => get_theme_mod( 'aqualuxe_social_youtube', '' ),
            'icon'  => 'fab fa-youtube',
            'label' => __( 'YouTube', 'aqualuxe' ),
        ),
        'linkedin'   => array(
            'url'   => get_theme_mod( 'aqualuxe_social_linkedin', '' ),
            'icon'  => 'fab fa-linkedin-in',
            'label' => __( 'LinkedIn', 'aqualuxe' ),
        ),
        'pinterest'  => array(
            'url'   => get_theme_mod( 'aqualuxe_social_pinterest', '' ),
            'icon'  => 'fab fa-pinterest-p',
            'label' => __( 'Pinterest', 'aqualuxe' ),
        ),
        'tiktok'     => array(
            'url'   => get_theme_mod( 'aqualuxe_social_tiktok', '' ),
            'icon'  => 'fab fa-tiktok',
            'label' => __( 'TikTok', 'aqualuxe' ),
        ),
    );

    // Get icon style and size
    $icon_style = get_theme_mod( 'aqualuxe_social_icon_style', 'rounded' );
    $icon_size = get_theme_mod( 'aqualuxe_social_icon_size', 'medium' );

    // Build CSS classes
    $icon_classes = array(
        'social-icon',
        'social-icon-' . $icon_style,
        'social-icon-' . $icon_size,
    );

    // Start output
    $output = '<div class="social-icons social-icons-' . esc_attr( $location ) . '">';
    
    foreach ( $social_networks as $network => $data ) {
        if ( ! empty( $data['url'] ) ) {
            $output .= '<a href="' . esc_url( $data['url'] ) . '" class="' . esc_attr( implode( ' ', $icon_classes ) ) . ' social-icon-' . esc_attr( $network ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( $data['label'] ) . '">';
            $output .= '<i class="' . esc_attr( $data['icon'] ) . '" aria-hidden="true"></i>';
            $output .= '</a>';
        }
    }
    
    $output .= '</div>';

    // Only output if there are social icons
    if ( strpos( $output, 'href' ) !== false ) {
        echo $output;
    }
}

/**
 * Display social sharing buttons.
 *
 * @param string $type Type of content (post or product).
 */
function aqualuxe_display_social_sharing( $type = 'post' ) {
    // Check if social sharing is enabled for this content type
    $share_setting = 'aqualuxe_social_share_' . $type . 's';
    if ( ! get_theme_mod( $share_setting, true ) ) {
        return;
    }

    // Get post data
    $title = get_the_title();
    $url = get_permalink();
    $image = get_the_post_thumbnail_url( get_the_ID(), 'large' );

    // Prepare share URLs
    $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $url );
    $twitter_url = 'https://twitter.com/intent/tweet?url=' . urlencode( $url ) . '&text=' . urlencode( $title );
    $pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . urlencode( $url ) . '&media=' . urlencode( $image ) . '&description=' . urlencode( $title );
    $linkedin_url = 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode( $url );
    $email_url = 'mailto:?subject=' . urlencode( $title ) . '&body=' . urlencode( $url );

    // Output sharing buttons
    ?>
    <div class="social-sharing">
        <h4 class="social-sharing-title"><?php esc_html_e( 'Share this', 'aqualuxe' ); ?></h4>
        <div class="social-sharing-buttons">
            <a href="<?php echo esc_url( $facebook_url ); ?>" class="social-share-button facebook" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Share on Facebook', 'aqualuxe' ); ?>">
                <i class="fab fa-facebook-f" aria-hidden="true"></i>
                <span class="screen-reader-text"><?php esc_html_e( 'Share on Facebook', 'aqualuxe' ); ?></span>
            </a>
            <a href="<?php echo esc_url( $twitter_url ); ?>" class="social-share-button twitter" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Share on Twitter', 'aqualuxe' ); ?>">
                <i class="fab fa-twitter" aria-hidden="true"></i>
                <span class="screen-reader-text"><?php esc_html_e( 'Share on Twitter', 'aqualuxe' ); ?></span>
            </a>
            <a href="<?php echo esc_url( $pinterest_url ); ?>" class="social-share-button pinterest" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Share on Pinterest', 'aqualuxe' ); ?>">
                <i class="fab fa-pinterest-p" aria-hidden="true"></i>
                <span class="screen-reader-text"><?php esc_html_e( 'Share on Pinterest', 'aqualuxe' ); ?></span>
            </a>
            <a href="<?php echo esc_url( $linkedin_url ); ?>" class="social-share-button linkedin" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Share on LinkedIn', 'aqualuxe' ); ?>">
                <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                <span class="screen-reader-text"><?php esc_html_e( 'Share on LinkedIn', 'aqualuxe' ); ?></span>
            </a>
            <a href="<?php echo esc_url( $email_url ); ?>" class="social-share-button email" aria-label="<?php esc_attr_e( 'Share via Email', 'aqualuxe' ); ?>">
                <i class="fas fa-envelope" aria-hidden="true"></i>
                <span class="screen-reader-text"><?php esc_html_e( 'Share via Email', 'aqualuxe' ); ?></span>
            </a>
        </div>
    </div>
    <?php
}

// Add social sharing to posts
function aqualuxe_add_social_sharing_to_posts() {
    if ( is_singular( 'post' ) ) {
        aqualuxe_display_social_sharing( 'post' );
    }
}
add_action( 'aqualuxe_after_post_content', 'aqualuxe_add_social_sharing_to_posts' );

// Add social sharing to products
function aqualuxe_add_social_sharing_to_products() {
    if ( is_singular( 'product' ) ) {
        aqualuxe_display_social_sharing( 'product' );
    }
}
add_action( 'woocommerce_share', 'aqualuxe_add_social_sharing_to_products' );