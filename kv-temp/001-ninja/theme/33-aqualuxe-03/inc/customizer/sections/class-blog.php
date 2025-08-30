<?php
/**
 * Blog Customizer Section
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Customizer\Sections;

use AquaLuxe\Customizer\Controls\Radio_Image_Control;
use AquaLuxe\Customizer\Controls\Slider_Control;
use AquaLuxe\Customizer\Controls\Heading_Control;
use AquaLuxe\Customizer\Controls\Divider_Control;
use AquaLuxe\Customizer\Controls\Toggle_Control;
use AquaLuxe\Customizer\Controls\Sortable_Control;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Blog Customizer Section Class
 */
class Blog {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'register_settings' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'aqualuxe_customize_preview_js', array( $this, 'preview_js' ) );
	}

	/**
	 * Register customizer settings
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register_settings( $wp_customize ) {
		// Add Blog section.
		$wp_customize->add_section(
			'aqualuxe_blog',
			array(
				'title'    => esc_html__( 'Blog', 'aqualuxe' ),
				'priority' => 70,
				'panel'    => 'aqualuxe_theme_options',
			)
		);

		// Blog Archive Settings.
		$wp_customize->add_setting(
			'aqualuxe_blog_archive_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_blog_archive_heading',
				array(
					'label'    => esc_html__( 'Blog Archive', 'aqualuxe' ),
					'section'  => 'aqualuxe_blog',
					'priority' => 10,
				)
			)
		);

		// Blog Layout.
		$wp_customize->add_setting(
			'aqualuxe_blog_layout',
			array(
				'default'           => 'standard',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			new Radio_Image_Control(
				$wp_customize,
				'aqualuxe_blog_layout',
				array(
					'label'    => esc_html__( 'Blog Layout', 'aqualuxe' ),
					'section'  => 'aqualuxe_blog',
					'priority' => 20,
					'choices'  => array(
						'standard' => array(
							'label' => esc_html__( 'Standard', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/blog-standard.svg',
						),
						'grid'     => array(
							'label' => esc_html__( 'Grid', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/blog-grid.svg',
						),
						'masonry'  => array(
							'label' => esc_html__( 'Masonry', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/blog-masonry.svg',
						),
						'list'     => array(
							'label' => esc_html__( 'List', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/blog-list.svg',
						),
					),
				)
			)
		);

		// Grid Columns.
		$wp_customize->add_setting(
			'aqualuxe_blog_columns',
			array(
				'default'           => 2,
				'sanitize_callback' => 'absint',
				'active_callback'   => array( $this, 'is_blog_grid_or_masonry' ),
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_blog_columns',
				array(
					'label'       => esc_html__( 'Grid Columns', 'aqualuxe' ),
					'description' => esc_html__( 'Number of columns for grid and masonry layouts.', 'aqualuxe' ),
					'section'     => 'aqualuxe_blog',
					'priority'    => 30,
					'input_attrs' => array(
						'min'  => 1,
						'max'  => 4,
						'step' => 1,
					),
					'active_callback' => array( $this, 'is_blog_grid_or_masonry' ),
				)
			)
		);

		// Posts Per Page.
		$wp_customize->add_setting(
			'aqualuxe_blog_posts_per_page',
			array(
				'default'           => get_option( 'posts_per_page' ),
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_blog_posts_per_page',
				array(
					'label'       => esc_html__( 'Posts Per Page', 'aqualuxe' ),
					'description' => esc_html__( 'Number of posts to display per page.', 'aqualuxe' ),
					'section'     => 'aqualuxe_blog',
					'priority'    => 40,
					'input_attrs' => array(
						'min'  => 1,
						'max'  => 50,
						'step' => 1,
					),
				)
			)
		);

		// Pagination Style.
		$wp_customize->add_setting(
			'aqualuxe_blog_pagination',
			array(
				'default'           => 'numbered',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_blog_pagination',
			array(
				'label'    => esc_html__( 'Pagination Style', 'aqualuxe' ),
				'section'  => 'aqualuxe_blog',
				'type'     => 'select',
				'priority' => 50,
				'choices'  => array(
					'numbered'     => esc_html__( 'Numbered', 'aqualuxe' ),
					'prev-next'    => esc_html__( 'Previous / Next', 'aqualuxe' ),
					'load-more'    => esc_html__( 'Load More Button', 'aqualuxe' ),
					'infinite'     => esc_html__( 'Infinite Scroll', 'aqualuxe' ),
				),
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_blog_meta_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_blog_meta_divider',
				array(
					'section'  => 'aqualuxe_blog',
					'priority' => 60,
				)
			)
		);

		// Post Meta Settings.
		$wp_customize->add_setting(
			'aqualuxe_blog_meta_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_blog_meta_heading',
				array(
					'label'    => esc_html__( 'Post Meta', 'aqualuxe' ),
					'section'  => 'aqualuxe_blog',
					'priority' => 70,
				)
			)
		);

		// Archive Meta Elements.
		$wp_customize->add_setting(
			'aqualuxe_blog_meta_elements',
			array(
				'default'           => array( 'author', 'date', 'categories', 'comments' ),
				'sanitize_callback' => array( $this, 'sanitize_multi_choices' ),
			)
		);

		$wp_customize->add_control(
			new Sortable_Control(
				$wp_customize,
				'aqualuxe_blog_meta_elements',
				array(
					'label'    => esc_html__( 'Archive Meta Elements', 'aqualuxe' ),
					'section'  => 'aqualuxe_blog',
					'priority' => 80,
					'choices'  => array(
						'author'     => esc_html__( 'Author', 'aqualuxe' ),
						'date'       => esc_html__( 'Date', 'aqualuxe' ),
						'categories' => esc_html__( 'Categories', 'aqualuxe' ),
						'tags'       => esc_html__( 'Tags', 'aqualuxe' ),
						'comments'   => esc_html__( 'Comments', 'aqualuxe' ),
					),
				)
			)
		);

		// Single Post Meta Elements.
		$wp_customize->add_setting(
			'aqualuxe_single_meta_elements',
			array(
				'default'           => array( 'author', 'date', 'categories', 'tags', 'comments' ),
				'sanitize_callback' => array( $this, 'sanitize_multi_choices' ),
			)
		);

		$wp_customize->add_control(
			new Sortable_Control(
				$wp_customize,
				'aqualuxe_single_meta_elements',
				array(
					'label'    => esc_html__( 'Single Post Meta Elements', 'aqualuxe' ),
					'section'  => 'aqualuxe_blog',
					'priority' => 90,
					'choices'  => array(
						'author'     => esc_html__( 'Author', 'aqualuxe' ),
						'date'       => esc_html__( 'Date', 'aqualuxe' ),
						'categories' => esc_html__( 'Categories', 'aqualuxe' ),
						'tags'       => esc_html__( 'Tags', 'aqualuxe' ),
						'comments'   => esc_html__( 'Comments', 'aqualuxe' ),
					),
				)
			)
		);

		// Show Author Avatar.
		$wp_customize->add_setting(
			'aqualuxe_blog_show_author_avatar',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_blog_show_author_avatar',
				array(
					'label'       => esc_html__( 'Show Author Avatar', 'aqualuxe' ),
					'description' => esc_html__( 'Display author avatar in post meta.', 'aqualuxe' ),
					'section'     => 'aqualuxe_blog',
					'priority'    => 100,
				)
			)
		);

		// Date Format.
		$wp_customize->add_setting(
			'aqualuxe_blog_date_format',
			array(
				'default'           => 'default',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_blog_date_format',
			array(
				'label'    => esc_html__( 'Date Format', 'aqualuxe' ),
				'section'  => 'aqualuxe_blog',
				'type'     => 'select',
				'priority' => 110,
				'choices'  => array(
					'default'   => esc_html__( 'Default', 'aqualuxe' ),
					'human'     => esc_html__( 'Human Readable (1 day ago)', 'aqualuxe' ),
					'custom'    => esc_html__( 'Custom', 'aqualuxe' ),
				),
			)
		);

		// Custom Date Format.
		$wp_customize->add_setting(
			'aqualuxe_blog_custom_date_format',
			array(
				'default'           => get_option( 'date_format' ),
				'sanitize_callback' => 'sanitize_text_field',
				'active_callback'   => array( $this, 'is_custom_date_format' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_blog_custom_date_format',
			array(
				'label'       => esc_html__( 'Custom Date Format', 'aqualuxe' ),
				'description' => esc_html__( 'Enter a custom date format (e.g., F j, Y).', 'aqualuxe' ),
				'section'     => 'aqualuxe_blog',
				'type'        => 'text',
				'priority'    => 120,
				'active_callback' => array( $this, 'is_custom_date_format' ),
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_blog_content_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_blog_content_divider',
				array(
					'section'  => 'aqualuxe_blog',
					'priority' => 130,
				)
			)
		);

		// Content Settings.
		$wp_customize->add_setting(
			'aqualuxe_blog_content_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_blog_content_heading',
				array(
					'label'    => esc_html__( 'Content Settings', 'aqualuxe' ),
					'section'  => 'aqualuxe_blog',
					'priority' => 140,
				)
			)
		);

		// Featured Image.
		$wp_customize->add_setting(
			'aqualuxe_blog_featured_image',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_blog_featured_image',
				array(
					'label'       => esc_html__( 'Show Featured Image', 'aqualuxe' ),
					'description' => esc_html__( 'Display featured image on archive pages.', 'aqualuxe' ),
					'section'     => 'aqualuxe_blog',
					'priority'    => 150,
				)
			)
		);

		// Featured Image Position.
		$wp_customize->add_setting(
			'aqualuxe_blog_featured_image_position',
			array(
				'default'           => 'above',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
				'active_callback'   => array( $this, 'is_featured_image_enabled' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_blog_featured_image_position',
			array(
				'label'    => esc_html__( 'Featured Image Position', 'aqualuxe' ),
				'section'  => 'aqualuxe_blog',
				'type'     => 'select',
				'priority' => 160,
				'choices'  => array(
					'above'  => esc_html__( 'Above Title', 'aqualuxe' ),
					'below'  => esc_html__( 'Below Title', 'aqualuxe' ),
				),
				'active_callback' => array( $this, 'is_featured_image_enabled' ),
			)
		);

		// Content Display.
		$wp_customize->add_setting(
			'aqualuxe_blog_content_display',
			array(
				'default'           => 'excerpt',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_blog_content_display',
			array(
				'label'    => esc_html__( 'Content Display', 'aqualuxe' ),
				'section'  => 'aqualuxe_blog',
				'type'     => 'select',
				'priority' => 170,
				'choices'  => array(
					'excerpt' => esc_html__( 'Excerpt', 'aqualuxe' ),
					'full'    => esc_html__( 'Full Content', 'aqualuxe' ),
					'none'    => esc_html__( 'No Content', 'aqualuxe' ),
				),
			)
		);

		// Excerpt Length.
		$wp_customize->add_setting(
			'aqualuxe_blog_excerpt_length',
			array(
				'default'           => 55,
				'sanitize_callback' => 'absint',
				'active_callback'   => array( $this, 'is_excerpt_enabled' ),
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_blog_excerpt_length',
				array(
					'label'       => esc_html__( 'Excerpt Length', 'aqualuxe' ),
					'description' => esc_html__( 'Number of words in excerpt.', 'aqualuxe' ),
					'section'     => 'aqualuxe_blog',
					'priority'    => 180,
					'input_attrs' => array(
						'min'  => 10,
						'max'  => 200,
						'step' => 5,
					),
					'active_callback' => array( $this, 'is_excerpt_enabled' ),
				)
			)
		);

		// Read More Text.
		$wp_customize->add_setting(
			'aqualuxe_blog_read_more_text',
			array(
				'default'           => esc_html__( 'Read More', 'aqualuxe' ),
				'sanitize_callback' => 'sanitize_text_field',
				'active_callback'   => array( $this, 'is_excerpt_or_full_content' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_blog_read_more_text',
			array(
				'label'       => esc_html__( 'Read More Text', 'aqualuxe' ),
				'description' => esc_html__( 'Text for the read more link.', 'aqualuxe' ),
				'section'     => 'aqualuxe_blog',
				'type'        => 'text',
				'priority'    => 190,
				'active_callback' => array( $this, 'is_excerpt_or_full_content' ),
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_single_post_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_single_post_divider',
				array(
					'section'  => 'aqualuxe_blog',
					'priority' => 200,
				)
			)
		);

		// Single Post Settings.
		$wp_customize->add_setting(
			'aqualuxe_single_post_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_single_post_heading',
				array(
					'label'    => esc_html__( 'Single Post', 'aqualuxe' ),
					'section'  => 'aqualuxe_blog',
					'priority' => 210,
				)
			)
		);

		// Single Featured Image.
		$wp_customize->add_setting(
			'aqualuxe_single_featured_image',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_single_featured_image',
				array(
					'label'       => esc_html__( 'Show Featured Image', 'aqualuxe' ),
					'description' => esc_html__( 'Display featured image on single posts.', 'aqualuxe' ),
					'section'     => 'aqualuxe_blog',
					'priority'    => 220,
				)
			)
		);

		// Single Featured Image Style.
		$wp_customize->add_setting(
			'aqualuxe_single_featured_image_style',
			array(
				'default'           => 'standard',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
				'active_callback'   => array( $this, 'is_single_featured_image_enabled' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_single_featured_image_style',
			array(
				'label'    => esc_html__( 'Featured Image Style', 'aqualuxe' ),
				'section'  => 'aqualuxe_blog',
				'type'     => 'select',
				'priority' => 230,
				'choices'  => array(
					'standard'  => esc_html__( 'Standard', 'aqualuxe' ),
					'wide'      => esc_html__( 'Wide', 'aqualuxe' ),
					'full'      => esc_html__( 'Full Width', 'aqualuxe' ),
					'cover'     => esc_html__( 'Cover', 'aqualuxe' ),
				),
				'active_callback' => array( $this, 'is_single_featured_image_enabled' ),
			)
		);

		// Show Author Bio.
		$wp_customize->add_setting(
			'aqualuxe_single_author_bio',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_single_author_bio',
				array(
					'label'       => esc_html__( 'Show Author Bio', 'aqualuxe' ),
					'description' => esc_html__( 'Display author biography at the end of posts.', 'aqualuxe' ),
					'section'     => 'aqualuxe_blog',
					'priority'    => 240,
				)
			)
		);

		// Show Post Navigation.
		$wp_customize->add_setting(
			'aqualuxe_single_post_nav',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_single_post_nav',
				array(
					'label'       => esc_html__( 'Show Post Navigation', 'aqualuxe' ),
					'description' => esc_html__( 'Display previous/next post navigation.', 'aqualuxe' ),
					'section'     => 'aqualuxe_blog',
					'priority'    => 250,
				)
			)
		);

		// Show Related Posts.
		$wp_customize->add_setting(
			'aqualuxe_single_related_posts',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_single_related_posts',
				array(
					'label'       => esc_html__( 'Show Related Posts', 'aqualuxe' ),
					'description' => esc_html__( 'Display related posts at the end of posts.', 'aqualuxe' ),
					'section'     => 'aqualuxe_blog',
					'priority'    => 260,
				)
			)
		);

		// Related Posts Count.
		$wp_customize->add_setting(
			'aqualuxe_related_posts_count',
			array(
				'default'           => 3,
				'sanitize_callback' => 'absint',
				'active_callback'   => array( $this, 'is_related_posts_enabled' ),
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_related_posts_count',
				array(
					'label'       => esc_html__( 'Related Posts Count', 'aqualuxe' ),
					'description' => esc_html__( 'Number of related posts to display.', 'aqualuxe' ),
					'section'     => 'aqualuxe_blog',
					'priority'    => 270,
					'input_attrs' => array(
						'min'  => 1,
						'max'  => 6,
						'step' => 1,
					),
					'active_callback' => array( $this, 'is_related_posts_enabled' ),
				)
			)
		);

		// Related Posts Criteria.
		$wp_customize->add_setting(
			'aqualuxe_related_posts_criteria',
			array(
				'default'           => 'categories',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
				'active_callback'   => array( $this, 'is_related_posts_enabled' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_related_posts_criteria',
			array(
				'label'    => esc_html__( 'Related Posts Criteria', 'aqualuxe' ),
				'section'  => 'aqualuxe_blog',
				'type'     => 'select',
				'priority' => 280,
				'choices'  => array(
					'categories' => esc_html__( 'Categories', 'aqualuxe' ),
					'tags'       => esc_html__( 'Tags', 'aqualuxe' ),
					'author'     => esc_html__( 'Author', 'aqualuxe' ),
				),
				'active_callback' => array( $this, 'is_related_posts_enabled' ),
			)
		);
	}

	/**
	 * Check if blog layout is grid or masonry
	 *
	 * @return bool
	 */
	public function is_blog_grid_or_masonry() {
		$layout = get_theme_mod( 'aqualuxe_blog_layout', 'standard' );
		return ( 'grid' === $layout || 'masonry' === $layout );
	}

	/**
	 * Check if custom date format is selected
	 *
	 * @return bool
	 */
	public function is_custom_date_format() {
		return 'custom' === get_theme_mod( 'aqualuxe_blog_date_format', 'default' );
	}

	/**
	 * Check if featured image is enabled
	 *
	 * @return bool
	 */
	public function is_featured_image_enabled() {
		return get_theme_mod( 'aqualuxe_blog_featured_image', true );
	}

	/**
	 * Check if excerpt is enabled
	 *
	 * @return bool
	 */
	public function is_excerpt_enabled() {
		return 'excerpt' === get_theme_mod( 'aqualuxe_blog_content_display', 'excerpt' );
	}

	/**
	 * Check if excerpt or full content is enabled
	 *
	 * @return bool
	 */
	public function is_excerpt_or_full_content() {
		$content_display = get_theme_mod( 'aqualuxe_blog_content_display', 'excerpt' );
		return ( 'excerpt' === $content_display || 'full' === $content_display );
	}

	/**
	 * Check if single featured image is enabled
	 *
	 * @return bool
	 */
	public function is_single_featured_image_enabled() {
		return get_theme_mod( 'aqualuxe_single_featured_image', true );
	}

	/**
	 * Check if related posts are enabled
	 *
	 * @return bool
	 */
	public function is_related_posts_enabled() {
		return get_theme_mod( 'aqualuxe_single_related_posts', true );
	}

	/**
	 * Sanitize choices
	 *
	 * @param string $input Value to sanitize.
	 * @param object $setting Setting object.
	 * @return string Sanitized value.
	 */
	public function sanitize_choices( $input, $setting ) {
		// Get the list of possible choices.
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// Return input if valid or return default.
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}

	/**
	 * Sanitize multi choices
	 *
	 * @param array $input Value to sanitize.
	 * @return array Sanitized value.
	 */
	public function sanitize_multi_choices( $input ) {
		if ( ! is_array( $input ) ) {
			return array();
		}

		$valid_input = array();
		foreach ( $input as $value ) {
			if ( ! empty( $value ) ) {
				$valid_input[] = sanitize_text_field( $value );
			}
		}

		return $valid_input;
	}

	/**
	 * Enqueue frontend styles
	 */
	public function enqueue_styles() {
		// Get blog settings.
		$blog_layout = get_theme_mod( 'aqualuxe_blog_layout', 'standard' );
		$columns     = get_theme_mod( 'aqualuxe_blog_columns', 2 );

		// Generate inline styles.
		$css = '';

		// Grid and masonry layouts.
		if ( 'grid' === $blog_layout || 'masonry' === $blog_layout ) {
			$css .= '.blog-grid .posts-container, .blog-masonry .posts-container {';
			$css .= 'display: grid;';
			$css .= 'grid-template-columns: repeat(' . absint( $columns ) . ', 1fr);';
			$css .= 'grid-gap: 30px;';
			$css .= '}';

			// Responsive styles.
			$css .= '@media (max-width: 768px) {';
			$css .= '.blog-grid .posts-container, .blog-masonry .posts-container {';
			$css .= 'grid-template-columns: repeat(2, 1fr);';
			$css .= '}';
			$css .= '}';

			$css .= '@media (max-width: 480px) {';
			$css .= '.blog-grid .posts-container, .blog-masonry .posts-container {';
			$css .= 'grid-template-columns: 1fr;';
			$css .= '}';
			$css .= '}';
		}

		// List layout.
		if ( 'list' === $blog_layout ) {
			$css .= '.blog-list article {';
			$css .= 'display: flex;';
			$css .= 'margin-bottom: 30px;';
			$css .= '}';

			$css .= '.blog-list .post-thumbnail {';
			$css .= 'flex: 0 0 35%;';
			$css .= 'margin-right: 30px;';
			$css .= '}';

			$css .= '.blog-list .entry-content-wrap {';
			$css .= 'flex: 1;';
			$css .= '}';

			// Responsive styles.
			$css .= '@media (max-width: 768px) {';
			$css .= '.blog-list article {';
			$css .= 'flex-direction: column;';
			$css .= '}';
			$css .= '.blog-list .post-thumbnail {';
			$css .= 'flex: 0 0 100%;';
			$css .= 'margin-right: 0;';
			$css .= 'margin-bottom: 20px;';
			$css .= '}';
			$css .= '}';
		}

		// Add inline style.
		if ( ! empty( $css ) ) {
			wp_add_inline_style( 'aqualuxe-style', $css );
		}
	}

	/**
	 * Add preview JS
	 */
	public function preview_js() {
		wp_enqueue_script(
			'aqualuxe-blog-preview',
			get_template_directory_uri() . '/assets/js/admin/customizer-blog-preview.js',
			array( 'customize-preview', 'jquery' ),
			AQUALUXE_VERSION,
			true
		);
	}
}

// Initialize the class.
new Blog();