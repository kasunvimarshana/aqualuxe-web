<?php
/**
 * AquaLuxe Theme Customizer - Blog Settings
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Customizer\Sections;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Blog Settings
 */
class Blog {

	/**
	 * Constructor
	 */
	public function __construct( $wp_customize ) {
		$this->register_blog_section( $wp_customize );
	}

	/**
	 * Register Blog Section
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register_blog_section( $wp_customize ) {
		// Blog Section.
		$wp_customize->add_section(
			'aqualuxe_blog_section',
			array(
				'title'    => __( 'Blog Settings', 'aqualuxe' ),
				'priority' => 50,
				'panel'    => 'aqualuxe_theme_options',
			)
		);

		// Blog Layout.
		$wp_customize->add_setting(
			'aqualuxe_blog_layout',
			array(
				'default'           => 'grid',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_blog_layout',
			array(
				'label'    => __( 'Blog Layout', 'aqualuxe' ),
				'section'  => 'aqualuxe_blog_section',
				'type'     => 'select',
				'choices'  => array(
					'grid'    => __( 'Grid', 'aqualuxe' ),
					'list'    => __( 'List', 'aqualuxe' ),
					'masonry' => __( 'Masonry', 'aqualuxe' ),
				),
				'priority' => 10,
			)
		);

		// Posts Per Row.
		$wp_customize->add_setting(
			'aqualuxe_posts_per_row',
			array(
				'default'           => '3',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_posts_per_row',
			array(
				'label'       => __( 'Posts Per Row', 'aqualuxe' ),
				'description' => __( 'For Grid and Masonry layouts', 'aqualuxe' ),
				'section'     => 'aqualuxe_blog_section',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 4,
					'step' => 1,
				),
				'priority'    => 20,
				'active_callback' => function() {
					return get_theme_mod( 'aqualuxe_blog_layout', 'grid' ) !== 'list';
				},
			)
		);

		// Featured Image.
		$wp_customize->add_setting(
			'aqualuxe_show_featured_image',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_show_featured_image',
			array(
				'label'    => __( 'Show Featured Image', 'aqualuxe' ),
				'section'  => 'aqualuxe_blog_section',
				'type'     => 'checkbox',
				'priority' => 30,
			)
		);

		// Post Meta.
		$wp_customize->add_setting(
			'aqualuxe_show_post_meta',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_show_post_meta',
			array(
				'label'    => __( 'Show Post Meta', 'aqualuxe' ),
				'section'  => 'aqualuxe_blog_section',
				'type'     => 'checkbox',
				'priority' => 40,
			)
		);

		// Post Excerpt.
		$wp_customize->add_setting(
			'aqualuxe_show_post_excerpt',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_show_post_excerpt',
			array(
				'label'    => __( 'Show Post Excerpt', 'aqualuxe' ),
				'section'  => 'aqualuxe_blog_section',
				'type'     => 'checkbox',
				'priority' => 50,
			)
		);

		// Excerpt Length.
		$wp_customize->add_setting(
			'aqualuxe_excerpt_length',
			array(
				'default'           => 25,
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_excerpt_length',
			array(
				'label'       => __( 'Excerpt Length', 'aqualuxe' ),
				'section'     => 'aqualuxe_blog_section',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 100,
					'step' => 5,
				),
				'priority'    => 60,
				'active_callback' => function() {
					return get_theme_mod( 'aqualuxe_show_post_excerpt', true );
				},
			)
		);

		// Read More Text.
		$wp_customize->add_setting(
			'aqualuxe_read_more_text',
			array(
				'default'           => __( 'Read More', 'aqualuxe' ),
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_read_more_text',
			array(
				'label'    => __( 'Read More Text', 'aqualuxe' ),
				'section'  => 'aqualuxe_blog_section',
				'type'     => 'text',
				'priority' => 70,
			)
		);

		// Pagination Type.
		$wp_customize->add_setting(
			'aqualuxe_pagination_type',
			array(
				'default'           => 'numbered',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_pagination_type',
			array(
				'label'    => __( 'Pagination Type', 'aqualuxe' ),
				'section'  => 'aqualuxe_blog_section',
				'type'     => 'select',
				'choices'  => array(
					'numbered'     => __( 'Numbered', 'aqualuxe' ),
					'prev_next'    => __( 'Previous / Next', 'aqualuxe' ),
					'load_more'    => __( 'Load More Button', 'aqualuxe' ),
					'infinite'     => __( 'Infinite Scroll', 'aqualuxe' ),
				),
				'priority' => 80,
			)
		);
	}
}