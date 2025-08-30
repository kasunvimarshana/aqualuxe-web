<?php
/**
 * Social Media Integration
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Get social media profiles.
 *
 * @return array
 */
if (! function_exists('aqualuxe_get_social_profiles')) :
    function aqualuxe_get_social_profiles() {
        $profiles = array(
            'facebook'  => array(
                'label' => __( 'Facebook', 'aqualuxe' ),
                'icon'  => 'facebook',
            ),
            'twitter'   => array(
                'label' => __( 'Twitter', 'aqualuxe' ),
                'icon'  => 'twitter',
            ),
            'instagram' => array(
                'label' => __( 'Instagram', 'aqualuxe' ),
                'icon'  => 'instagram',
            ),
            'linkedin'  => array(
                'label' => __( 'LinkedIn', 'aqualuxe' ),
                'icon'  => 'linkedin',
            ),
            'youtube'   => array(
                'label' => __( 'YouTube', 'aqualuxe' ),
                'icon'  => 'youtube',
            ),
            'pinterest' => array(
                'label' => __( 'Pinterest', 'aqualuxe' ),
                'icon'  => 'pinterest',
            ),
            'tiktok'    => array(
                'label' => __( 'TikTok', 'aqualuxe' ),
                'icon'  => 'tiktok',
            ),
        );
    
        return apply_filters( 'aqualuxe_social_profiles', $profiles );
    }
endif;

/**
 * Get social media URLs.
 *
 * @return array
 */
function aqualuxe_get_social_urls() {
    $profiles = aqualuxe_get_social_profiles();
    $urls = array();

    foreach ( $profiles as $id => $profile ) {
        $url = get_theme_mod( 'aqualuxe_social_' . $id );
        if ( $url ) {
            $urls[ $id ] = array(
                'url'   => $url,
                'label' => $profile['label'],
                'icon'  => $profile['icon'],
            );
        }
    }

    return apply_filters( 'aqualuxe_social_urls', $urls );
}

/**
 * Display social media icons.
 *
 * @param string $location Location of the social icons.
 * @return void
 */
function aqualuxe_display_social_icons( $location = '' ) {
    $urls = aqualuxe_get_social_urls();

    if ( empty( $urls ) ) {
        return;
    }

    $classes = array( 'social-icons' );
    if ( $location ) {
        $classes[] = 'social-icons-' . $location;
    }

    $class = implode( ' ', $classes );
    ?>
    <div class="<?php echo esc_attr( $class ); ?>">
        <ul class="social-icons-list">
            <?php foreach ( $urls as $id => $data ) : ?>
                <li class="social-icon-item social-icon-<?php echo esc_attr( $id ); ?>">
                    <a href="<?php echo esc_url( $data['url'] ); ?>" target="_blank" rel="noopener noreferrer" class="social-icon-link">
                        <span class="social-icon-wrap">
                            <i class="social-icon-<?php echo esc_attr( $data['icon'] ); ?>"></i>
                        </span>
                        <span class="screen-reader-text"><?php echo esc_html( $data['label'] ); ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
}

/**
 * Display social sharing buttons.
 *
 * @param string $title Title to share.
 * @param string $url   URL to share.
 * @return void
 */
function aqualuxe_display_social_sharing( $title = '', $url = '' ) {
    if ( ! $title ) {
        $title = get_the_title();
    }

    if ( ! $url ) {
        $url = get_permalink();
    }

    $encoded_title = rawurlencode( $title );
    $encoded_url = rawurlencode( $url );

    $networks = array(
        'facebook'  => array(
            'label' => __( 'Share on Facebook', 'aqualuxe' ),
            'icon'  => 'facebook',
            'url'   => 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url,
        ),
        'twitter'   => array(
            'label' => __( 'Share on Twitter', 'aqualuxe' ),
            'icon'  => 'twitter',
            'url'   => 'https://twitter.com/intent/tweet?text=' . $encoded_title . '&url=' . $encoded_url,
        ),
        'linkedin'  => array(
            'label' => __( 'Share on LinkedIn', 'aqualuxe' ),
            'icon'  => 'linkedin',
            'url'   => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $encoded_url . '&title=' . $encoded_title,
        ),
        'pinterest' => array(
            'label' => __( 'Share on Pinterest', 'aqualuxe' ),
            'icon'  => 'pinterest',
            'url'   => 'https://pinterest.com/pin/create/button/?url=' . $encoded_url . '&description=' . $encoded_title,
        ),
        'email'     => array(
            'label' => __( 'Share via Email', 'aqualuxe' ),
            'icon'  => 'envelope',
            'url'   => 'mailto:?subject=' . $encoded_title . '&body=' . $encoded_url,
        ),
    );

    $networks = apply_filters( 'aqualuxe_social_sharing_networks', $networks, $title, $url );

    if ( empty( $networks ) ) {
        return;
    }
    ?>
    <div class="social-sharing">
        <span class="social-sharing-title"><?php esc_html_e( 'Share:', 'aqualuxe' ); ?></span>
        <ul class="social-sharing-list">
            <?php foreach ( $networks as $id => $data ) : ?>
                <li class="social-sharing-item social-sharing-<?php echo esc_attr( $id ); ?>">
                    <a href="<?php echo esc_url( $data['url'] ); ?>" target="_blank" rel="noopener noreferrer" class="social-sharing-link">
                        <span class="social-icon-wrap">
                            <i class="social-icon-<?php echo esc_attr( $data['icon'] ); ?>"></i>
                        </span>
                        <span class="screen-reader-text"><?php echo esc_html( $data['label'] ); ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
}

/**
 * Add social media options to customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function aqualuxe_social_media_customizer( $wp_customize ) {
    // Add Social Media section.
    $wp_customize->add_section(
        'aqualuxe_social_media',
        array(
            'title'    => __( 'Social Media', 'aqualuxe' ),
            'priority' => 120,
        )
    );

    // Add social media profile settings.
    $profiles = aqualuxe_get_social_profiles();
    $priority = 10;

    foreach ( $profiles as $id => $profile ) {
        $wp_customize->add_setting(
            'aqualuxe_social_' . $id,
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_social_' . $id,
            array(
                'label'    => $profile['label'],
                'section'  => 'aqualuxe_social_media',
                'type'     => 'url',
                'priority' => $priority,
            )
        );

        $priority += 10;
    }

    // Add social sharing settings.
    $wp_customize->add_setting(
        'aqualuxe_enable_social_sharing',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_social_sharing',
        array(
            'label'    => __( 'Enable Social Sharing', 'aqualuxe' ),
            'section'  => 'aqualuxe_social_media',
            'type'     => 'checkbox',
            'priority' => $priority,
        )
    );

    $priority += 10;

    $wp_customize->add_setting(
        'aqualuxe_social_sharing_position',
        array(
            'default'           => 'after',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_sharing_position',
        array(
            'label'    => __( 'Social Sharing Position', 'aqualuxe' ),
            'section'  => 'aqualuxe_social_media',
            'type'     => 'select',
            'choices'  => array(
                'before' => __( 'Before Content', 'aqualuxe' ),
                'after'  => __( 'After Content', 'aqualuxe' ),
                'both'   => __( 'Before and After Content', 'aqualuxe' ),
            ),
            'priority' => $priority,
        )
    );
}
add_action( 'customize_register', 'aqualuxe_social_media_customizer' );

/**
 * Add social sharing buttons to single posts.
 *
 * @param string $content Post content.
 * @return string
 */
function aqualuxe_add_social_sharing_buttons( $content ) {
    // Return if social sharing is disabled.
    if ( ! get_theme_mod( 'aqualuxe_enable_social_sharing', true ) ) {
        return $content;
    }

    // Return if not a single post.
    if ( ! is_singular( 'post' ) ) {
        return $content;
    }

    $position = get_theme_mod( 'aqualuxe_social_sharing_position', 'after' );
    $title = get_the_title();
    $url = get_permalink();

    ob_start();
    aqualuxe_display_social_sharing( $title, $url );
    $sharing_buttons = ob_get_clean();

    if ( 'before' === $position ) {
        return $sharing_buttons . $content;
    } elseif ( 'after' === $position ) {
        return $content . $sharing_buttons;
    } elseif ( 'both' === $position ) {
        return $sharing_buttons . $content . $sharing_buttons;
    }

    return $content;
}
add_filter( 'the_content', 'aqualuxe_add_social_sharing_buttons' );

/**
 * Add social icons to top bar.
 *
 * @return void
 */
function aqualuxe_add_social_icons_to_top_bar() {
    if ( get_theme_mod( 'aqualuxe_show_social_icons_top_bar', true ) ) {
        aqualuxe_display_social_icons( 'top-bar' );
    }
}
add_action( 'aqualuxe_top_bar', 'aqualuxe_add_social_icons_to_top_bar', 20 );

/**
 * Add social icons to footer.
 *
 * @return void
 */
function aqualuxe_add_social_icons_to_footer() {
    if ( get_theme_mod( 'aqualuxe_show_social_icons_footer', true ) ) {
        aqualuxe_display_social_icons( 'footer' );
    }
}
add_action( 'aqualuxe_footer_bottom', 'aqualuxe_add_social_icons_to_footer', 20 );