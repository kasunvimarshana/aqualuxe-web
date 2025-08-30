<?php
add_action('customize_register', function($wp_customize) {
    // Logo, colors, typography, layout
    $wp_customize->add_section('aqualuxe_branding', [
        'title' => __('Branding', 'aqualuxe'),
        'priority' => 30,
    ]);
    $wp_customize->add_setting('aqualuxe_logo');
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aqualuxe_logo', [
        'label' => __('Logo', 'aqualuxe'),
        'section' => 'aqualuxe_branding',
        'settings' => 'aqualuxe_logo',
    ]));
    // ...colors, typography, layout (add as needed)
});
