<?php
/**
 * SEO Optimization Module
 *
 * @package AquaLuxe
 */

// Add settings to the customizer
function aqualuxe_seo_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'aqualuxe_seo_section', array(
        'title'      => __( 'SEO Settings', 'aqualuxe' ),
        'priority'   => 150,
    ) );

    // Home Meta Description
    $wp_customize->add_setting( 'aqualuxe_seo_home_meta_description', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'aqualuxe_seo_home_meta_description', array(
        'type'      => 'textarea',
        'label'     => __( 'Home Page Meta Description', 'aqualuxe' ),
        'section'   => 'aqualuxe_seo_section',
    ) );

    // Home Meta Keywords
    $wp_customize->add_setting( 'aqualuxe_seo_home_meta_keywords', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'aqualuxe_seo_home_meta_keywords', array(
        'type'      => 'textarea',
        'label'     => __( 'Home Page Meta Keywords', 'aqualuxe' ),
        'section'   => 'aqualuxe_seo_section',
    ) );
}
add_action( 'customize_register', 'aqualuxe_seo_customize_register' );

// Add meta tags to head
function aqualuxe_seo_meta_tags() {
    // Default meta
    echo '<meta charset="' . get_bloginfo( 'charset' ) . '">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<link rel="profile" href="http://gmpg.org/xfn/11">';

    if ( is_front_page() ) {
        $description = get_theme_mod( 'aqualuxe_seo_home_meta_description' );
        $keywords = get_theme_mod( 'aqualuxe_seo_home_meta_keywords' );

        if ( $description ) {
            echo '<meta name="description" content="' . esc_attr( $description ) . '">';
        }
        if ( $keywords ) {
            echo '<meta name="keywords" content="' . esc_attr( $keywords ) . '">';
        }
    } elseif ( is_singular() ) {
        $post = get_queried_object();
        if ( ! empty( $post->post_excerpt ) ) {
            echo '<meta name="description" content="' . esc_attr( strip_tags( $post->post_excerpt ) ) . '">';
        } else {
            echo '<meta name="description" content="' . esc_attr( wp_trim_words( strip_tags( $post->post_content ), 55 ) ) . '">';
        }
        $tags = get_the_tags();
        if ( $tags ) {
            $keywords = array();
            foreach ( $tags as $tag ) {
                $keywords[] = $tag->name;
            }
            echo '<meta name="keywords" content="' . esc_attr( implode( ', ', $keywords ) ) . '">';
        }
    }
}
add_action( 'wp_head', 'aqualuxe_seo_meta_tags' );

// Open Graph Meta
function aqualuxe_seo_og_meta_tags() {
    if ( is_singular() ) {
        $post = get_queried_object();
        echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '">';
        echo '<meta property="og:description" content="' . esc_attr( wp_trim_words( strip_tags( $post->post_content ), 55 ) ) . '">';
        echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '">';
        echo '<meta property="og:site_name" content="' . get_bloginfo( 'name' ) . '">';
        if ( has_post_thumbnail() ) {
            echo '<meta property="og:image" content="' . esc_url( get_the_post_thumbnail_url() ) . '">';
        }
    }
}
add_action( 'wp_head', 'aqualuxe_seo_og_meta_tags' );
