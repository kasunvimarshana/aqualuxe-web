<?php
/**
 * The Customizer functionality of the theme.
 *
 * @link       https://aqualuxe.pro
 * @since      1.0.0
 *
 * @package    AquaLuxe
 * @subpackage AquaLuxe/inc/core
 */

/**
 * The Customizer functionality of the theme.
 *
 * @package    AquaLuxe
 * @subpackage AquaLuxe/inc/core
 * @author     Your Name <email@example.com>
 */
class AquaLuxe_Customizer {

    /**
     * The Customizer object.
     *
     * @since    1.0.0
     * @access   private
     * @var      object    $wp_customize    The Customizer object.
     */
    private $wp_customize;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      object    $wp_customize       The Customizer object.
     */
    public function __construct( $wp_customize ) {
        $this->wp_customize = $wp_customize;
        $this->register_panels();
        $this->register_sections();
        $this->register_settings();
    }

    /**
     * Register Customizer panels.
     *
     * @since    1.0.0
     */
    public function register_panels() {
        $this->wp_customize->add_panel( 'aqualuxe_theme_options', array(
            'title'       => __( 'AquaLuxe Theme Options', 'aqualuxe' ),
            'description' => __( 'AquaLuxe theme options', 'aqualuxe' ),
            'priority'    => 10,
        ) );
    }

    /**
     * Register Customizer sections.
     *
     * @since    1.0.0
     */
    public function register_sections() {
        $this->wp_customize->add_section( 'aqualuxe_colors', array(
            'title'      => __( 'Colors', 'aqualuxe' ),
            'panel'      => 'aqualuxe_theme_options',
            'priority'   => 10,
        ) );

        $this->wp_customize->add_section( 'aqualuxe_typography', array(
            'title'      => __( 'Typography', 'aqualuxe' ),
            'panel'      => 'aqualuxe_theme_options',
            'priority'   => 20,
        ) );

        $this->wp_customize->add_section( 'aqualuxe_layout', array(
            'title'      => __( 'Layout', 'aqualuxe' ),
            'panel'      => 'aqualuxe_theme_options',
            'priority'   => 30,
        ) );
    }

    /**
     * Register Customizer settings.
     *
     * @since    1.0.0
     */
	public function register_settings() {
		// Site Title and Tagline color
		$this->wp_customize->add_setting( 'header_textcolor', array(
			'default'           => '#000000',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		) );

		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'header_textcolor', array(
			'label'   => __( 'Header Text Color', 'aqualuxe' ),
			'section' => 'title_tagline',
		) ) );
		
		// Logo
		$this->wp_customize->add_setting( 'aqualuxe_logo', array(
            'transport' => 'refresh',
            'sanitize_callback' => 'absint',
        ) );

        $this->wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $this->wp_customize, 'aqualuxe_logo', array(
            'label'      => __( 'Logo', 'aqualuxe' ),
            'section'    => 'title_tagline',
            'settings'   => 'aqualuxe_logo',
            'width'      => 180,
            'height'     => 60,
            'flex_width'  => true,
            'flex_height' => true,
        ) ) );

        // Colors
        $this->wp_customize->add_setting( 'aqualuxe_primary_color', array(
            'default'   => '#0073aa',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ) );

        $this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'aqualuxe_primary_color', array(
            'label'      => __( 'Primary Color', 'aqualuxe' ),
            'section'    => 'aqualuxe_colors',
            'settings'   => 'aqualuxe_primary_color',
        ) ) );

        $this->wp_customize->add_setting( 'aqualuxe_secondary_color', array(
            'default'   => '#2271b1',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ) );

        $this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'aqualuxe_secondary_color', array(
            'label'      => __( 'Secondary Color', 'aqualuxe' ),
            'section'    => 'aqualuxe_colors',
            'settings'   => 'aqualuxe_secondary_color',
        ) ) );

        // Typography
        $this->wp_customize->add_setting( 'aqualuxe_body_font', array(
            'default'   => 'sans-serif',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ) );

        $this->wp_customize->add_control( 'aqualuxe_body_font', array(
            'label'      => __( 'Body Font', 'aqualuxe' ),
            'section'    => 'aqualuxe_typography',
            'settings'   => 'aqualuxe_body_font',
            'type'       => 'select',
            'choices'    => array(
                'sans-serif' => 'Sans-serif',
                'serif'      => 'Serif',
                'monospace'  => 'Monospace',
            ),
        ) );

        $this->wp_customize->add_setting( 'aqualuxe_heading_font', array(
            'default'   => 'sans-serif',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ) );

        $this->wp_customize->add_control( 'aqualuxe_heading_font', array(
            'label'      => __( 'Heading Font', 'aqualuxe' ),
            'section'    => 'aqualuxe_typography',
            'settings'   => 'aqualuxe_heading_font',
            'type'       => 'select',
            'choices'    => array(
                'sans-serif' => 'Sans-serif',
                'serif'      => 'Serif',
                'monospace'  => 'Monospace',
            ),
        ) );

        // Layout
        $this->wp_customize->add_setting( 'aqualuxe_layout_type', array(
            'default'   => 'full-width',
            'transport' => 'refresh',
            'sanitize_callback' => 'sanitize_key',
        ) );

        $this->wp_customize->add_control( 'aqualuxe_layout_type', array(
            'label'      => __( 'Layout Type', 'aqualuxe' ),
            'section'    => 'aqualuxe_layout',
            'settings'   => 'aqualuxe_layout_type',
            'type'       => 'radio',
            'choices'    => array(
                'full-width' => 'Full Width',
                'boxed'      => 'Boxed',
            ),
        ) );
    }
}
