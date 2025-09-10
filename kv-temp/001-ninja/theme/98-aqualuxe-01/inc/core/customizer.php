<?php
namespace AquaLuxe\Core;

/**
 * Theme Customizer settings (logo handled via theme support).
 */
final class Customizer {
	public static function init(): void {
		\add_action( 'customize_register', [ __CLASS__, 'register' ] );
	}

	public static function register( \WP_Customize_Manager $wp_customize ): void {
		// Panel
		$wp_customize->add_panel( 'aqualuxe_panel', [
			'title'       => __( 'AquaLuxe Options', 'aqualuxe' ),
			'description' => __( 'Theme appearance and layout settings.', 'aqualuxe' ),
			'priority'    => 160,
		] );

		// Colors Section
		$wp_customize->add_section( 'aqualuxe_colors', [
			'title' => __( 'Colors', 'aqualuxe' ),
			'panel' => 'aqualuxe_panel',
		] );

		$wp_customize->add_setting( 'aqualuxe_primary_color', [
			'default'           => '#0ea5e9',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_hex_color',
		] );
		$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'aqualuxe_primary_color', [
			'label'   => __( 'Primary Color', 'aqualuxe' ),
			'section' => 'aqualuxe_colors',
		] ) );

		// Typography Section
		$wp_customize->add_section( 'aqualuxe_typography', [
			'title' => __( 'Typography', 'aqualuxe' ),
			'panel' => 'aqualuxe_panel',
		] );
		$wp_customize->add_setting( 'aqualuxe_font_base', [
			'default'           => 'ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif',
			'sanitize_callback' => [ __CLASS__, 'sanitize_font_stack' ],
		] );
		$wp_customize->add_control( 'aqualuxe_font_base', [
			'label'   => __( 'Base Font Stack', 'aqualuxe' ),
			'section' => 'aqualuxe_typography',
			'type'    => 'text',
		] );

		// Layout Section
		$wp_customize->add_section( 'aqualuxe_layout', [
			'title' => __( 'Layout', 'aqualuxe' ),
			'panel' => 'aqualuxe_panel',
		] );
		$wp_customize->add_setting( 'aqualuxe_container_width', [
			'default'           => 1280,
			'sanitize_callback' => 'absint',
		] );
		$wp_customize->add_control( 'aqualuxe_container_width', [
			'label'       => __( 'Container Max Width (px)', 'aqualuxe' ),
			'section'     => 'aqualuxe_layout',
			'type'        => 'number',
			'input_attrs' => [ 'min' => 960, 'max' => 1920, 'step' => 10 ],
		] );

		// Output CSS vars in <head>.
		\add_action( 'wp_head', [ __CLASS__, 'output_css_vars' ] );
	}

	public static function sanitize_font_stack( string $value ): string {
		return \wp_strip_all_tags( $value );
	}

	public static function output_css_vars(): void {
		$primary = get_theme_mod( 'aqualuxe_primary_color', '#0ea5e9' );
		$font    = get_theme_mod( 'aqualuxe_font_base', 'ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif' );
		$width   = (int) get_theme_mod( 'aqualuxe_container_width', 1280 );
		echo '<style id="aqualuxe-css-vars">:root{--alx-primary:' . esc_attr( $primary ) . ';--alx-font-base:' . esc_attr( $font ) . ';--alx-container:' . esc_attr( $width ) . 'px;}</style>' . "\n";
	}
}
