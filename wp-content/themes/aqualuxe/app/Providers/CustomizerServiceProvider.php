<?php

namespace App\Providers;

use App\Core\ServiceProvider;

class CustomizerServiceProvider extends ServiceProvider {
	public function register() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'customize_preview_init', [ $this, 'customize_preview_js' ] );
	}

	/**
	 * Add postMessage support for site title and description for the Theme Customizer.
	 *
	 * @param \WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function customize_register( $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$wp_customize->get_section( 'title_tagline' )->priority     = 0;

		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				'blogname',
				[
					'selector'        => '.site-title a',
					'render_callback' => [ $this, 'customize_partial_blogname' ],
				]
			);
			$wp_customize->selective_refresh->add_partial(
				'blogdescription',
				[
					'selector'        => '.site-description',
					'render_callback' => [ $this, 'customize_partial_blogdescription' ],
				]
			);
		}
	}

	/**
	 * Render the site title for the selective refresh partial.
	 *
	 * @return void
	 */
	public function customize_partial_blogname() {
		bloginfo( 'name' );
	}

	/**
	 * Render the site tagline for the selective refresh partial.
	 *
	 * @return void
	 */
	public function customize_partial_blogdescription() {
		bloginfo( 'description' );
	}

	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 */
	public function customize_preview_js() {
		wp_enqueue_script( 'aqualuxe-customizer', get_template_directory_uri() . '/assets/src/js/customizer.js', [ 'customize-preview' ], AQUALUXE_VERSION, true );
	}
}
