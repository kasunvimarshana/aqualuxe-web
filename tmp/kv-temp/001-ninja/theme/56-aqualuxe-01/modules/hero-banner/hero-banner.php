<?php
/**
 * Hero Banner Module
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Modules\HeroBanner;

use AquaLuxe\Core\Module;
use AquaLuxe\Core\ModuleInterface;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Hero Banner Module class
 */
class HeroBannerModule extends Module implements ModuleInterface {
    /**
     * Setup module properties
     *
     * @return void
     */
    protected function setup() {
        $this->id          = 'hero-banner';
        $this->name        = __( 'Hero Banner', 'aqualuxe' );
        $this->description = __( 'Display a hero banner with customizable content, background, and call-to-action buttons.', 'aqualuxe' );
        $this->version     = '1.0.0';
        $this->dependencies = [];
        $this->settings     = [
            'title'           => __( 'Welcome to AquaLuxe', 'aqualuxe' ),
            'subtitle'        => __( 'A premium WordPress theme for modern websites', 'aqualuxe' ),
            'description'     => __( 'Create stunning websites with our flexible and powerful WordPress theme. Perfect for businesses, portfolios, blogs, and e-commerce sites.', 'aqualuxe' ),
            'button_primary'  => __( 'Get Started', 'aqualuxe' ),
            'button_primary_url' => '#',
            'button_secondary' => __( 'Learn More', 'aqualuxe' ),
            'button_secondary_url' => '#',
            'background_type' => 'image',
            'background_image' => '',
            'background_video' => '',
            'background_color' => '#0073aa',
            'text_color'      => '#ffffff',
            'overlay_opacity' => 50,
            'height'          => 'medium',
            'alignment'       => 'center',
            'animation'       => 'fade',
        ];
    }

    /**
     * Register module hooks
     *
     * @return void
     */
    protected function register_hooks() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'aqualuxe_hero_banner', [ $this, 'render' ] );
        add_shortcode( 'aqualuxe_hero_banner', [ $this, 'shortcode' ] );
    }

    /**
     * Initialize the module
     *
     * @return void
     */
    public function init() {
        // Nothing to initialize
    }

    /**
     * Enqueue module assets
     *
     * @return void
     */
    public function enqueue_assets() {
        $this->enqueue_styles();
        $this->enqueue_scripts();
    }

    /**
     * Render the module
     *
     * @param array $args Module arguments.
     * @return void
     */
    public function render( $args = [] ) {
        $defaults = $this->settings;
        $args = wp_parse_args( $args, $defaults );
        
        // Get module settings from theme mods if not provided in args
        foreach ( $defaults as $key => $default ) {
            if ( ! isset( $args[ $key ] ) || empty( $args[ $key ] ) ) {
                $theme_mod = get_theme_mod( 'aqualuxe_hero_' . $key, $default );
                $args[ $key ] = $theme_mod;
            }
        }
        
        $this->get_template_part( 'hero-banner', $args );
    }

    /**
     * Shortcode handler
     *
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function shortcode( $atts ) {
        $atts = shortcode_atts( $this->settings, $atts, 'aqualuxe_hero_banner' );
        
        ob_start();
        $this->render( $atts );
        return ob_get_clean();
    }

    /**
     * Register module customizer settings
     *
     * @param \WP_Customize_Manager $wp_customize Customizer instance.
     * @return void
     */
    public function register_customizer_settings( $wp_customize ) {
        // Add Hero Banner section
        $wp_customize->add_section( 'aqualuxe_hero_banner', [
            'title'       => __( 'Hero Banner', 'aqualuxe' ),
            'description' => __( 'Customize the hero banner module', 'aqualuxe' ),
            'panel'       => 'aqualuxe_modules',
            'priority'    => 10,
        ] );
        
        // Content Settings
        $wp_customize->add_setting( 'aqualuxe_hero_title', [
            'default'           => $this->settings['title'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_hero_title', [
            'label'       => __( 'Title', 'aqualuxe' ),
            'description' => __( 'Enter the hero banner title', 'aqualuxe' ),
            'section'     => 'aqualuxe_hero_banner',
            'type'        => 'text',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_hero_subtitle', [
            'default'           => $this->settings['subtitle'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_hero_subtitle', [
            'label'       => __( 'Subtitle', 'aqualuxe' ),
            'description' => __( 'Enter the hero banner subtitle', 'aqualuxe' ),
            'section'     => 'aqualuxe_hero_banner',
            'type'        => 'text',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_hero_description', [
            'default'           => $this->settings['description'],
            'sanitize_callback' => 'wp_kses_post',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_hero_description', [
            'label'       => __( 'Description', 'aqualuxe' ),
            'description' => __( 'Enter the hero banner description', 'aqualuxe' ),
            'section'     => 'aqualuxe_hero_banner',
            'type'        => 'textarea',
        ] );
        
        // Button Settings
        $wp_customize->add_setting( 'aqualuxe_hero_button_primary', [
            'default'           => $this->settings['button_primary'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_hero_button_primary', [
            'label'       => __( 'Primary Button Text', 'aqualuxe' ),
            'description' => __( 'Enter the primary button text', 'aqualuxe' ),
            'section'     => 'aqualuxe_hero_banner',
            'type'        => 'text',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_hero_button_primary_url', [
            'default'           => $this->settings['button_primary_url'],
            'sanitize_callback' => 'esc_url_raw',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_hero_button_primary_url', [
            'label'       => __( 'Primary Button URL', 'aqualuxe' ),
            'description' => __( 'Enter the primary button URL', 'aqualuxe' ),
            'section'     => 'aqualuxe_hero_banner',
            'type'        => 'url',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_hero_button_secondary', [
            'default'           => $this->settings['button_secondary'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_hero_button_secondary', [
            'label'       => __( 'Secondary Button Text', 'aqualuxe' ),
            'description' => __( 'Enter the secondary button text', 'aqualuxe' ),
            'section'     => 'aqualuxe_hero_banner',
            'type'        => 'text',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_hero_button_secondary_url', [
            'default'           => $this->settings['button_secondary_url'],
            'sanitize_callback' => 'esc_url_raw',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_hero_button_secondary_url', [
            'label'       => __( 'Secondary Button URL', 'aqualuxe' ),
            'description' => __( 'Enter the secondary button URL', 'aqualuxe' ),
            'section'     => 'aqualuxe_hero_banner',
            'type'        => 'url',
        ] );
        
        // Background Settings
        $wp_customize->add_setting( 'aqualuxe_hero_background_type', [
            'default'           => $this->settings['background_type'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_hero_background_type', [
            'label'       => __( 'Background Type', 'aqualuxe' ),
            'description' => __( 'Select the background type', 'aqualuxe' ),
            'section'     => 'aqualuxe_hero_banner',
            'type'        => 'select',
            'choices'     => [
                'image' => __( 'Image', 'aqualuxe' ),
                'video' => __( 'Video', 'aqualuxe' ),
                'color' => __( 'Color', 'aqualuxe' ),
            ],
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_hero_background_image', [
            'default'           => $this->settings['background_image'],
            'sanitize_callback' => 'esc_url_raw',
        ] );
        
        $wp_customize->add_control( new \WP_Customize_Image_Control(
            $wp_customize,
            'aqualuxe_hero_background_image',
            [
                'label'       => __( 'Background Image', 'aqualuxe' ),
                'description' => __( 'Select the background image', 'aqualuxe' ),
                'section'     => 'aqualuxe_hero_banner',
            ]
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_hero_background_video', [
            'default'           => $this->settings['background_video'],
            'sanitize_callback' => 'esc_url_raw',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_hero_background_video', [
            'label'       => __( 'Background Video URL', 'aqualuxe' ),
            'description' => __( 'Enter the background video URL (YouTube or direct video file)', 'aqualuxe' ),
            'section'     => 'aqualuxe_hero_banner',
            'type'        => 'url',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_hero_background_color', [
            'default'           => $this->settings['background_color'],
            'sanitize_callback' => 'sanitize_hex_color',
        ] );
        
        $wp_customize->add_control( new \WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_hero_background_color',
            [
                'label'       => __( 'Background Color', 'aqualuxe' ),
                'description' => __( 'Select the background color', 'aqualuxe' ),
                'section'     => 'aqualuxe_hero_banner',
            ]
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_hero_text_color', [
            'default'           => $this->settings['text_color'],
            'sanitize_callback' => 'sanitize_hex_color',
        ] );
        
        $wp_customize->add_control( new \WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_hero_text_color',
            [
                'label'       => __( 'Text Color', 'aqualuxe' ),
                'description' => __( 'Select the text color', 'aqualuxe' ),
                'section'     => 'aqualuxe_hero_banner',
            ]
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_hero_overlay_opacity', [
            'default'           => $this->settings['overlay_opacity'],
            'sanitize_callback' => 'absint',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_hero_overlay_opacity', [
            'label'       => __( 'Overlay Opacity (%)', 'aqualuxe' ),
            'description' => __( 'Set the overlay opacity', 'aqualuxe' ),
            'section'     => 'aqualuxe_hero_banner',
            'type'        => 'range',
            'input_attrs' => [
                'min'  => 0,
                'max'  => 100,
                'step' => 5,
            ],
        ] );
        
        // Layout Settings
        $wp_customize->add_setting( 'aqualuxe_hero_height', [
            'default'           => $this->settings['height'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_hero_height', [
            'label'       => __( 'Height', 'aqualuxe' ),
            'description' => __( 'Select the hero banner height', 'aqualuxe' ),
            'section'     => 'aqualuxe_hero_banner',
            'type'        => 'select',
            'choices'     => [
                'small'  => __( 'Small', 'aqualuxe' ),
                'medium' => __( 'Medium', 'aqualuxe' ),
                'large'  => __( 'Large', 'aqualuxe' ),
                'full'   => __( 'Full Screen', 'aqualuxe' ),
            ],
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_hero_alignment', [
            'default'           => $this->settings['alignment'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_hero_alignment', [
            'label'       => __( 'Content Alignment', 'aqualuxe' ),
            'description' => __( 'Select the content alignment', 'aqualuxe' ),
            'section'     => 'aqualuxe_hero_banner',
            'type'        => 'select',
            'choices'     => [
                'left'   => __( 'Left', 'aqualuxe' ),
                'center' => __( 'Center', 'aqualuxe' ),
                'right'  => __( 'Right', 'aqualuxe' ),
            ],
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_hero_animation', [
            'default'           => $this->settings['animation'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_hero_animation', [
            'label'       => __( 'Animation', 'aqualuxe' ),
            'description' => __( 'Select the animation effect', 'aqualuxe' ),
            'section'     => 'aqualuxe_hero_banner',
            'type'        => 'select',
            'choices'     => [
                'none'   => __( 'None', 'aqualuxe' ),
                'fade'   => __( 'Fade', 'aqualuxe' ),
                'slide'  => __( 'Slide', 'aqualuxe' ),
                'zoom'   => __( 'Zoom', 'aqualuxe' ),
                'bounce' => __( 'Bounce', 'aqualuxe' ),
            ],
        ] );
    }
}

// Initialize the module
return new HeroBannerModule();