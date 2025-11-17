<?php
// Multi-theme switcher support (child skins) using theme_mod and body class.
// Keeps core clean; can be extended to swap color palettes and partials.

add_filter('body_class', function( $classes ){
    $skin = get_theme_mod('aqualuxe_skin', 'default');
    $classes[] = 'alx-skin-' . sanitize_html_class( $skin );
    return $classes;
});

add_action('customize_register', function( $wp_customize ){
    $wp_customize->add_section('aqualuxe_skins',[ 'title' => __('AquaLuxe Skins','aqualuxe') ]);
    $wp_customize->add_setting('aqualuxe_skin',[ 'default' => 'default', 'sanitize_callback' => 'sanitize_key' ]);
    $wp_customize->add_control('aqualuxe_skin',[
        'label' => __('Theme Skin','aqualuxe'),
        'section' => 'aqualuxe_skins',
        'type' => 'select',
        'choices' => apply_filters('aqualuxe_skin_choices', [
            'default' => __('Default','aqualuxe'),
            'deepblue' => __('Deep Blue','aqualuxe'),
            'emerald' => __('Emerald Reef','aqualuxe'),
        ])
    ]);
});
