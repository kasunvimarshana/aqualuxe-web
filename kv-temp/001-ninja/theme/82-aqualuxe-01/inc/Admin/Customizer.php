<?php
namespace AquaLuxe\Admin;

class Customizer {
	public static function register( $wp_customize ) : void {
		// Colors
		$wp_customize->add_section( 'aqualuxe_colors', [
			'title' => ( function_exists('__') ? call_user_func('__', 'AquaLuxe Colors', 'aqualuxe' ) : 'AquaLuxe Colors' ),
			'priority' => 30,
		] );

		$wp_customize->add_setting( 'aqualuxe_primary_color', [
			'default' => '#0ea5e9',
			'sanitize_callback' => 'sanitize_hex_color',
		] );
		if ( class_exists('\\WP_Customize_Color_Control') ) {
			$__cc = '\\WP_Customize_Color_Control';
			$wp_customize->add_control( new $__cc( $wp_customize, 'aqualuxe_primary_color', [
				'label' => ( function_exists('__') ? call_user_func('__', 'Primary', 'aqualuxe' ) : 'Primary' ),
				'section' => 'aqualuxe_colors',
			] ) );
		}

		// Typography scale
		$wp_customize->add_section( 'aqualuxe_typography', [
			'title' => ( function_exists('__') ? call_user_func('__', 'AquaLuxe Typography', 'aqualuxe' ) : 'AquaLuxe Typography' ),
		] );
		$wp_customize->add_setting( 'aqualuxe_base_font_size', [
			'default' => 16,
			'sanitize_callback' => 'absint',
		] );
		$wp_customize->add_control( 'aqualuxe_base_font_size', [
			'label' => ( function_exists('__') ? call_user_func('__', 'Base font size (px)', 'aqualuxe' ) : 'Base font size (px)' ),
			'section' => 'aqualuxe_typography',
			'type' => 'number',
			'input_attrs' => [ 'min' => 14, 'max' => 22 ],
		] );

		// Layout width
		$wp_customize->add_section( 'aqualuxe_layout', [
			'title' => ( function_exists('__') ? call_user_func('__', 'Layout', 'aqualuxe' ) : 'Layout' ),
		] );
		$wp_customize->add_setting( 'aqualuxe_container_width', [
			'default' => 1200,
			'sanitize_callback' => 'absint',
		] );
		$wp_customize->add_control( 'aqualuxe_container_width', [
			'label' => ( function_exists('__') ? call_user_func('__', 'Container max width (px)', 'aqualuxe' ) : 'Container max width (px)' ),
			'section' => 'aqualuxe_layout',
			'type' => 'number',
			'input_attrs' => [ 'min' => 960, 'max' => 1600 ],
		] );

		// Hero settings
		$wp_customize->add_section( 'aqualuxe_hero', [
			'title' => ( function_exists('__') ? call_user_func('__', 'AquaLuxe Hero', 'aqualuxe' ) : 'AquaLuxe Hero' ),
			'priority' => 35,
		] );
		$wp_customize->add_setting( 'aqualuxe_enable_hero_audio', [
			'default' => 0,
			'sanitize_callback' => 'absint',
		] );
		$wp_customize->add_control( 'aqualuxe_enable_hero_audio', [
			'label' => ( function_exists('__') ? call_user_func('__', 'Enable ambient audio (hero)', 'aqualuxe' ) : 'Enable ambient audio (hero)' ),
			'description' => ( function_exists('__') ? call_user_func('__', 'Adds a toggle button on the hero; disabled globally when unchecked.', 'aqualuxe' ) : 'Adds a toggle button on the hero; disabled globally when unchecked.' ),
			'section' => 'aqualuxe_hero',
			'type' => 'checkbox',
		] );

		// Optional: choose an audio file from the media library
		$wp_customize->add_setting( 'aqualuxe_hero_audio_src', [
			'default' => '',
			'sanitize_callback' => 'esc_url_raw',
		] );
		if ( class_exists('\\WP_Customize_Media_Control') ) {
			$__mc = '\\WP_Customize_Media_Control';
			$wp_customize->add_control( new $__mc( $wp_customize, 'aqualuxe_hero_audio_src', [
				'label' => ( function_exists('__') ? call_user_func('__', 'Ambient audio file (MP3)', 'aqualuxe' ) : 'Ambient audio file (MP3)' ),
				'description' => ( function_exists('__') ? call_user_func('__', 'Optional. When set and audio is enabled, a toggle appears on the hero. If empty, no audio is requested.', 'aqualuxe' ) : 'Optional. When set and audio is enabled, a toggle appears on the hero. If empty, no audio is requested.' ),
				'section' => 'aqualuxe_hero',
				'mime_type' => 'audio',
			] ) );
		}

		// Commerce
		$wp_customize->add_section( 'aqualuxe_commerce', [
			'title' => call_user_func('__', 'Commerce', 'aqualuxe' ),
			'priority' => 40,
		] );

		// Enable mini cart
		$wp_customize->add_setting( 'aqualuxe_enable_mini_cart', [
			'default' => 1,
			'sanitize_callback' => 'absint',
		] );
		$wp_customize->add_control( 'aqualuxe_enable_mini_cart', [
			'label' => call_user_func('__', 'Enable mini cart drawer', 'aqualuxe' ),
			'section' => 'aqualuxe_commerce',
			'type' => 'checkbox',
		] );

		// Auto-detect free shipping threshold from zones
		$wp_customize->add_setting( 'aqualuxe_use_wc_threshold', [
			'default' => 1,
			'sanitize_callback' => 'absint',
		] );
		$wp_customize->add_control( 'aqualuxe_use_wc_threshold', [
			'label' => call_user_func('__', 'Auto-detect free shipping threshold from shipping zones', 'aqualuxe' ),
			'description' => call_user_func('__', 'If disabled, the value below will be used.', 'aqualuxe' ),
			'section' => 'aqualuxe_commerce',
			'type' => 'checkbox',
		] );
		$wp_customize->add_setting( 'aqualuxe_free_shipping_threshold', [
			'default' => 100,
			'sanitize_callback' => 'absint',
		] );
		$wp_customize->add_control( 'aqualuxe_free_shipping_threshold', [
			'label' => call_user_func('__', 'Free shipping threshold (subtotal)', 'aqualuxe' ),
			'description' => call_user_func('__', 'Used when auto-detection from shipping zones is disabled or unavailable.', 'aqualuxe' ),
			'section' => 'aqualuxe_commerce',
			'type' => 'number',
			'input_attrs' => [ 'min' => 0, 'step' => 1 ],
		] );
	}
}
