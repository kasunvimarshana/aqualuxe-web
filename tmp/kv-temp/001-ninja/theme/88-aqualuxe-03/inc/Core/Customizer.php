<?php
namespace AquaLuxe\Core;

class Customizer {
    public function __construct() {
        \add_action( 'customize_register', [ $this, 'register' ] );
    }

    public function register( $wp_customize ): void {
        // Colors
        $wp_customize->add_section( 'aqlx_colors', [ 'title' => __( 'AquaLuxe Colors', 'aqualuxe' ), 'priority' => 30 ] );
        $wp_customize->add_setting( 'aqlx_primary_color', [ 'default' => '#0ea5e9', 'transport' => 'refresh', 'sanitize_callback' => 'sanitize_hex_color' ] );
        if ( \class_exists( '\\WP_Customize_Color_Control' ) ) {
            $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'aqlx_primary_color', [
                'label' => __( 'Primary Color', 'aqualuxe' ),
                'section' => 'aqlx_colors',
            ] ) );
        }

        // Typography (simplified)
        $wp_customize->add_section( 'aqlx_typography', [ 'title' => __( 'AquaLuxe Typography', 'aqualuxe' ), 'priority' => 31 ] );
        $wp_customize->add_setting( 'aqlx_font_family', [ 'default' => 'ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji"', 'sanitize_callback' => [ $this, 'sanitize_font_stack' ] ] );
        $wp_customize->add_control( 'aqlx_font_family', [ 'label' => __( 'Font Stack', 'aqualuxe' ), 'type' => 'text', 'section' => 'aqlx_typography' ] );

        // Layout
        $wp_customize->add_section( 'aqlx_layout', [ 'title' => __( 'AquaLuxe Layout', 'aqualuxe' ), 'priority' => 32 ] );
        $wp_customize->add_setting( 'aqlx_container_width', [ 'default' => '1280px', 'sanitize_callback' => [ $this, 'sanitize_length' ] ] );
        $wp_customize->add_control( 'aqlx_container_width', [ 'label' => __( 'Container Max Width', 'aqualuxe' ), 'type' => 'text', 'section' => 'aqlx_layout' ] );
    }

    public function sanitize_font_stack( $value ) {
        return is_string( $value ) ? $value : '';
    }
    public function sanitize_length( $value ) {
        return preg_match( '/^\\d+(px|rem|em|%)$/', (string) $value ) ? $value : '1280px';
    }
}
