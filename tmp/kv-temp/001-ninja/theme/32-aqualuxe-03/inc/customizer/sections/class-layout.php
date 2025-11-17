<?php
/**
 * AquaLuxe Theme Customizer - Layout Settings
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Customizer\Sections;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Layout Settings
 */
class Layout {

	/**
	 * WP_Customize_Manager instance.
	 *
	 * @var WP_Customize_Manager
	 */
	private $wp_customize;

	/**
	 * Constructor.
	 *
	 * @param \WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 */
	public function __construct( $wp_customize ) {
		$this->wp_customize = $wp_customize;
		$this->register_sections();
		$this->register_settings();
	}

	/**
	 * Register customizer sections.
	 *
	 * @return void
	 */
	public function register_sections() {
		$this->wp_customize->add_section(
			'aqualuxe_layout',
			array(
				'title'    => __( 'Layout Settings', 'aqualuxe' ),
				'priority' => 30,
			)
		);
	}

	/**
	 * Register customizer settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		// Site Layout.
		$this->wp_customize->add_setting(
			'aqualuxe_site_layout',
			array(
				'default'           => 'wide',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_site_layout',
			array(
				'label'    => __( 'Site Layout', 'aqualuxe' ),
				'section'  => 'aqualuxe_layout',
				'settings' => 'aqualuxe_site_layout',
				'type'     => 'select',
				'choices'  => array(
					'wide'     => __( 'Wide', 'aqualuxe' ),
					'boxed'    => __( 'Boxed', 'aqualuxe' ),
					'framed'   => __( 'Framed', 'aqualuxe' ),
					'full'     => __( 'Full Width', 'aqualuxe' ),
				),
			)
		);

		// Content Layout.
		$this->wp_customize->add_setting(
			'aqualuxe_content_layout',
			array(
				'default'           => 'right-sidebar',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_content_layout',
			array(
				'label'    => __( 'Content Layout', 'aqualuxe' ),
				'section'  => 'aqualuxe_layout',
				'settings' => 'aqualuxe_content_layout',
				'type'     => 'select',
				'choices'  => array(
					'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
					'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
					'no-sidebar'    => __( 'No Sidebar', 'aqualuxe' ),
				),
			)
		);

		// Single Post Layout.
		$this->wp_customize->add_setting(
			'aqualuxe_single_post_layout',
			array(
				'default'           => 'right-sidebar',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_single_post_layout',
			array(
				'label'    => __( 'Single Post Layout', 'aqualuxe' ),
				'section'  => 'aqualuxe_layout',
				'settings' => 'aqualuxe_single_post_layout',
				'type'     => 'select',
				'choices'  => array(
					'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
					'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
					'no-sidebar'    => __( 'No Sidebar', 'aqualuxe' ),
				),
			)
		);

		// Page Layout.
		$this->wp_customize->add_setting(
			'aqualuxe_page_layout',
			array(
				'default'           => 'no-sidebar',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_page_layout',
			array(
				'label'    => __( 'Page Layout', 'aqualuxe' ),
				'section'  => 'aqualuxe_layout',
				'settings' => 'aqualuxe_page_layout',
				'type'     => 'select',
				'choices'  => array(
					'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
					'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
					'no-sidebar'    => __( 'No Sidebar', 'aqualuxe' ),
				),
			)
		);

		// Archive Layout.
		$this->wp_customize->add_setting(
			'aqualuxe_archive_layout',
			array(
				'default'           => 'right-sidebar',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_archive_layout',
			array(
				'label'    => __( 'Archive Layout', 'aqualuxe' ),
				'section'  => 'aqualuxe_layout',
				'settings' => 'aqualuxe_archive_layout',
				'type'     => 'select',
				'choices'  => array(
					'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
					'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
					'no-sidebar'    => __( 'No Sidebar', 'aqualuxe' ),
				),
			)
		);

		// Blog Layout.
		$this->wp_customize->add_setting(
			'aqualuxe_blog_layout',
			array(
				'default'           => 'grid',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_blog_layout',
			array(
				'label'    => __( 'Blog Layout', 'aqualuxe' ),
				'section'  => 'aqualuxe_layout',
				'settings' => 'aqualuxe_blog_layout',
				'type'     => 'select',
				'choices'  => array(
					'grid'     => __( 'Grid', 'aqualuxe' ),
					'list'     => __( 'List', 'aqualuxe' ),
					'masonry'  => __( 'Masonry', 'aqualuxe' ),
					'standard' => __( 'Standard', 'aqualuxe' ),
				),
			)
		);

		// Blog Columns.
		$this->wp_customize->add_setting(
			'aqualuxe_blog_columns',
			array(
				'default'           => '3',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_blog_columns',
			array(
				'label'    => __( 'Blog Columns', 'aqualuxe' ),
				'section'  => 'aqualuxe_layout',
				'settings' => 'aqualuxe_blog_columns',
				'type'     => 'select',
				'choices'  => array(
					'1' => __( '1 Column', 'aqualuxe' ),
					'2' => __( '2 Columns', 'aqualuxe' ),
					'3' => __( '3 Columns', 'aqualuxe' ),
					'4' => __( '4 Columns', 'aqualuxe' ),
				),
			)
		);

		// Blog Posts Per Page.
		$this->wp_customize->add_setting(
			'aqualuxe_blog_posts_per_page',
			array(
				'default'           => '9',
				'sanitize_callback' => 'absint',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_blog_posts_per_page',
			array(
				'label'    => __( 'Posts Per Page', 'aqualuxe' ),
				'section'  => 'aqualuxe_layout',
				'settings' => 'aqualuxe_blog_posts_per_page',
				'type'     => 'number',
			)
		);

		// Blog Excerpt Length.
		$this->wp_customize->add_setting(
			'aqualuxe_blog_excerpt_length',
			array(
				'default'           => '25',
				'sanitize_callback' => 'absint',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_blog_excerpt_length',
			array(
				'label'    => __( 'Excerpt Length', 'aqualuxe' ),
				'section'  => 'aqualuxe_layout',
				'settings' => 'aqualuxe_blog_excerpt_length',
				'type'     => 'number',
			)
		);

		// Blog Read More Text.
		$this->wp_customize->add_setting(
			'aqualuxe_blog_read_more_text',
			array(
				'default'           => __( 'Read More', 'aqualuxe' ),
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_blog_read_more_text',
			array(
				'label'    => __( 'Read More Text', 'aqualuxe' ),
				'section'  => 'aqualuxe_layout',
				'settings' => 'aqualuxe_blog_read_more_text',
				'type'     => 'text',
			)
		);
	}

	/**
	 * Sanitize select.
	 *
	 * @param string $input Select value.
	 * @param object $setting Setting object.
	 * @return string
	 */
	public function sanitize_select( $input, $setting ) {
		$input   = sanitize_key( $input );
		$choices = $setting->manager->get_control( $setting->id )->choices;

		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}
}