<?php
namespace Aqualuxe\Modules\Customizer;

defined('ABSPATH') || exit;

final class CustomizerModule {
    public static function register(): void {
        \add_action( 'customize_register', [ __CLASS__, 'register_controls' ] );
    }

    public static function register_controls( \WP_Customize_Manager $wp_customize ): void {
        $wp_customize->add_section( 'aqlx_ui', [
            'title' => __( 'Aqualuxe UI', 'aqualuxe' ),
            'priority' => 30,
        ] );

        $wp_customize->add_setting( 'aqlx_skin', [
            'default' => 'default',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ] );

        $wp_customize->add_control( 'aqlx_skin', [
            'type' => 'select',
            'section' => 'aqlx_ui',
            'label' => __( 'Theme Skin', 'aqualuxe' ),
            'choices' => [
                'default' => __( 'Default', 'aqualuxe' ),
                'dark' => __( 'Dark', 'aqualuxe' ),
            ],
        ] );
    }
}
