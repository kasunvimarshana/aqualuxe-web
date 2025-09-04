<?php
// Theme Variants module: enable multiple style variants/skins without separate child themes.

// Determine current variant (url param > option > default), filterable
function aqlx_variant_current(): string {
    $allowed = apply_filters('aqualuxe/variants/allowed', ['default','lux','minimal']);
    $v = isset($_GET['variant']) ? sanitize_key((string) $_GET['variant']) : get_theme_mod('aqlx_variant', 'default');
    if (!in_array($v, $allowed, true)) { $v = 'default'; }
    return apply_filters('aqualuxe/variants/current', $v);
}

add_filter('body_class', function(array $classes){
    $classes[] = 'variant-' . aqlx_variant_current();
    return $classes;
});

// Simple switcher shortcode [aqualuxe_variant]
add_shortcode('aqualuxe_variant', function($atts){
    $v = aqlx_variant_current();
    return '<span class="aqlx-variant" data-variant="' . esc_attr($v) . '">' . esc_html(ucfirst($v)) . '</span>';
});

// Customizer control
add_action('customize_register', function($wp_customize){
    $wp_customize->add_setting('aqlx_variant', [ 'default' => 'default', 'transport' => 'refresh' ]);
    $wp_customize->add_control('aqlx_variant', [
        'label' => __('Theme Variant', 'aqualuxe'),
        'section' => 'title_tagline',
        'type' => 'select',
        'choices' => array_combine(apply_filters('aqualuxe/variants/allowed', ['default','lux','minimal']), apply_filters('aqualuxe/variants/allowed', ['default','lux','minimal'])),
    ]);
});
