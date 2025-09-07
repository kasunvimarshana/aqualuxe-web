<?php
/**
 * Social Sharing Module
 *
 * @package AquaLuxe
 */

// Add settings to the customizer
function aqualuxe_social_sharing_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'aqualuxe_social_sharing_section', array(
        'title'      => __( 'Social Sharing', 'aqualuxe' ),
        'priority'   => 160,
    ) );

    $wp_customize->add_setting( 'aqualuxe_social_sharing_enabled', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'aqualuxe_social_sharing_enabled', array(
        'type'      => 'checkbox',
        'label'     => __( 'Enable Social Sharing Buttons', 'aqualuxe' ),
        'section'   => 'aqualuxe_social_sharing_section',
    ) );
}
add_action( 'customize_register', 'aqualuxe_social_sharing_customize_register' );

// Add social sharing buttons to the content
function aqualuxe_add_social_sharing_buttons( $content ) {
    if ( is_single() && get_theme_mod( 'aqualuxe_social_sharing_enabled', true ) ) {
        $post_url = get_permalink();
        $post_title = get_the_title();

        $sharing_html = '<div class="aqualuxe-social-sharing">';
        $sharing_html .= '<h4>' . __( 'Share this post', 'aqualuxe' ) . '</h4>';
        $sharing_html .= '<a href="https://www.facebook.com/sharer/sharer.php?u=' . esc_url( $post_url ) . '" target="_blank" rel="noopener noreferrer" class="social-link facebook"><span class="dashicons dashicons-facebook-alt"></span></a>';
        $sharing_html .= '<a href="https://twitter.com/intent/tweet?text=' . esc_attr( $post_title ) . '&url=' . esc_url( $post_url ) . '" target="_blank" rel="noopener noreferrer" class="social-link twitter"><span class="dashicons dashicons-twitter"></span></a>';
        $sharing_html .= '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . esc_url( $post_url ) . '&title=' . esc_attr( $post_title ) . '" target="_blank" rel="noopener noreferrer" class="social-link linkedin"><span class="dashicons dashicons-linkedin"></span></a>';
        $sharing_html .= '<a href="https://pinterest.com/pin/create/button/?url=' . esc_url( $post_url ) . '&media=' . get_the_post_thumbnail_url() . '&description=' . esc_attr( $post_title ) . '" target="_blank" rel="noopener noreferrer" class="social-link pinterest"><span class="dashicons dashicons-pinterest"></span></a>';
        $sharing_html .= '</div>';

        $content .= $sharing_html;
    }
    return $content;
}
add_filter( 'the_content', 'aqualuxe_add_social_sharing_buttons' );
