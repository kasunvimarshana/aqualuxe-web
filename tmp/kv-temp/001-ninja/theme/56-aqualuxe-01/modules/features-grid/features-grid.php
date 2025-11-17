<?php
/**
 * Features Grid Module
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Modules\FeaturesGrid;

use AquaLuxe\Core\Module;
use AquaLuxe\Core\ModuleInterface;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Features Grid Module class
 */
class FeaturesGridModule extends Module implements ModuleInterface {
    /**
     * Setup module properties
     *
     * @return void
     */
    protected function setup() {
        $this->id          = 'features-grid';
        $this->name        = __( 'Features Grid', 'aqualuxe' );
        $this->description = __( 'Display a grid of features with icons, titles, and descriptions.', 'aqualuxe' );
        $this->version     = '1.0.0';
        $this->dependencies = [];
        $this->settings     = [
            'title'           => __( 'Our Features', 'aqualuxe' ),
            'subtitle'        => __( 'What makes us special', 'aqualuxe' ),
            'description'     => __( 'Discover the key features that set our products and services apart from the competition.', 'aqualuxe' ),
            'columns'         => 3,
            'layout'          => 'grid',
            'style'           => 'default',
            'animation'       => 'fade',
            'features'        => [
                [
                    'icon'        => 'bolt',
                    'title'       => __( 'Lightning Fast', 'aqualuxe' ),
                    'description' => __( 'Optimized for speed and performance to provide the best user experience.', 'aqualuxe' ),
                    'link'        => '#',
                    'link_text'   => __( 'Learn More', 'aqualuxe' ),
                ],
                [
                    'icon'        => 'mobile',
                    'title'       => __( 'Fully Responsive', 'aqualuxe' ),
                    'description' => __( 'Looks great on all devices, from mobile phones to large desktop screens.', 'aqualuxe' ),
                    'link'        => '#',
                    'link_text'   => __( 'Learn More', 'aqualuxe' ),
                ],
                [
                    'icon'        => 'palette',
                    'title'       => __( 'Customizable', 'aqualuxe' ),
                    'description' => __( 'Easily customize colors, layouts, and features to match your brand.', 'aqualuxe' ),
                    'link'        => '#',
                    'link_text'   => __( 'Learn More', 'aqualuxe' ),
                ],
                [
                    'icon'        => 'shield',
                    'title'       => __( 'Secure & Reliable', 'aqualuxe' ),
                    'description' => __( 'Built with security in mind to keep your data safe and your site running smoothly.', 'aqualuxe' ),
                    'link'        => '#',
                    'link_text'   => __( 'Learn More', 'aqualuxe' ),
                ],
                [
                    'icon'        => 'search',
                    'title'       => __( 'SEO Optimized', 'aqualuxe' ),
                    'description' => __( 'Follows best practices for search engine optimization to improve your visibility.', 'aqualuxe' ),
                    'link'        => '#',
                    'link_text'   => __( 'Learn More', 'aqualuxe' ),
                ],
                [
                    'icon'        => 'headset',
                    'title'       => __( 'Premium Support', 'aqualuxe' ),
                    'description' => __( 'Get help when you need it with our dedicated support team.', 'aqualuxe' ),
                    'link'        => '#',
                    'link_text'   => __( 'Learn More', 'aqualuxe' ),
                ],
            ],
        ];
    }

    /**
     * Register module hooks
     *
     * @return void
     */
    protected function register_hooks() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'aqualuxe_features_grid', [ $this, 'render' ] );
        add_shortcode( 'aqualuxe_features_grid', [ $this, 'shortcode' ] );
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
                if ( $key === 'features' ) {
                    $features = [];
                    $feature_count = get_theme_mod( 'aqualuxe_features_count', 6 );
                    
                    for ( $i = 1; $i <= $feature_count; $i++ ) {
                        $feature = [
                            'icon'        => get_theme_mod( "aqualuxe_feature_{$i}_icon", $default[$i-1]['icon'] ?? '' ),
                            'title'       => get_theme_mod( "aqualuxe_feature_{$i}_title", $default[$i-1]['title'] ?? '' ),
                            'description' => get_theme_mod( "aqualuxe_feature_{$i}_description", $default[$i-1]['description'] ?? '' ),
                            'link'        => get_theme_mod( "aqualuxe_feature_{$i}_link", $default[$i-1]['link'] ?? '' ),
                            'link_text'   => get_theme_mod( "aqualuxe_feature_{$i}_link_text", $default[$i-1]['link_text'] ?? '' ),
                        ];
                        
                        $features[] = $feature;
                    }
                    
                    $args['features'] = $features;
                } else {
                    $theme_mod = get_theme_mod( 'aqualuxe_features_' . $key, $default );
                    $args[ $key ] = $theme_mod;
                }
            }
        }
        
        $this->get_template_part( 'features-grid', $args );
    }

    /**
     * Shortcode handler
     *
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function shortcode( $atts ) {
        $atts = shortcode_atts( [
            'title'       => $this->settings['title'],
            'subtitle'    => $this->settings['subtitle'],
            'description' => $this->settings['description'],
            'columns'     => $this->settings['columns'],
            'layout'      => $this->settings['layout'],
            'style'       => $this->settings['style'],
            'animation'   => $this->settings['animation'],
            'feature_ids' => '', // Comma-separated list of feature post IDs if using custom post type
        ], $atts, 'aqualuxe_features_grid' );
        
        // If feature_ids is provided, get features from custom post type
        if ( ! empty( $atts['feature_ids'] ) ) {
            $feature_ids = explode( ',', $atts['feature_ids'] );
            $features = [];
            
            foreach ( $feature_ids as $feature_id ) {
                $feature_id = trim( $feature_id );
                $feature = get_post( $feature_id );
                
                if ( $feature && $feature->post_type === 'feature' ) {
                    $features[] = [
                        'icon'        => get_post_meta( $feature_id, '_feature_icon', true ),
                        'title'       => get_the_title( $feature_id ),
                        'description' => get_the_excerpt( $feature_id ),
                        'link'        => get_permalink( $feature_id ),
                        'link_text'   => get_post_meta( $feature_id, '_feature_link_text', true ) ?: __( 'Learn More', 'aqualuxe' ),
                    ];
                }
            }
            
            $atts['features'] = $features;
        } else {
            $atts['features'] = $this->settings['features'];
        }
        
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
        // Add Features Grid section
        $wp_customize->add_section( 'aqualuxe_features_grid', [
            'title'       => __( 'Features Grid', 'aqualuxe' ),
            'description' => __( 'Customize the features grid module', 'aqualuxe' ),
            'panel'       => 'aqualuxe_modules',
            'priority'    => 20,
        ] );
        
        // Content Settings
        $wp_customize->add_setting( 'aqualuxe_features_title', [
            'default'           => $this->settings['title'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_features_title', [
            'label'       => __( 'Title', 'aqualuxe' ),
            'description' => __( 'Enter the features grid title', 'aqualuxe' ),
            'section'     => 'aqualuxe_features_grid',
            'type'        => 'text',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_features_subtitle', [
            'default'           => $this->settings['subtitle'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_features_subtitle', [
            'label'       => __( 'Subtitle', 'aqualuxe' ),
            'description' => __( 'Enter the features grid subtitle', 'aqualuxe' ),
            'section'     => 'aqualuxe_features_grid',
            'type'        => 'text',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_features_description', [
            'default'           => $this->settings['description'],
            'sanitize_callback' => 'wp_kses_post',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_features_description', [
            'label'       => __( 'Description', 'aqualuxe' ),
            'description' => __( 'Enter the features grid description', 'aqualuxe' ),
            'section'     => 'aqualuxe_features_grid',
            'type'        => 'textarea',
        ] );
        
        // Layout Settings
        $wp_customize->add_setting( 'aqualuxe_features_columns', [
            'default'           => $this->settings['columns'],
            'sanitize_callback' => 'absint',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_features_columns', [
            'label'       => __( 'Columns', 'aqualuxe' ),
            'description' => __( 'Select the number of columns', 'aqualuxe' ),
            'section'     => 'aqualuxe_features_grid',
            'type'        => 'select',
            'choices'     => [
                1 => __( '1 Column', 'aqualuxe' ),
                2 => __( '2 Columns', 'aqualuxe' ),
                3 => __( '3 Columns', 'aqualuxe' ),
                4 => __( '4 Columns', 'aqualuxe' ),
                6 => __( '6 Columns', 'aqualuxe' ),
            ],
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_features_layout', [
            'default'           => $this->settings['layout'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_features_layout', [
            'label'       => __( 'Layout', 'aqualuxe' ),
            'description' => __( 'Select the layout style', 'aqualuxe' ),
            'section'     => 'aqualuxe_features_grid',
            'type'        => 'select',
            'choices'     => [
                'grid'    => __( 'Grid', 'aqualuxe' ),
                'list'    => __( 'List', 'aqualuxe' ),
                'masonry' => __( 'Masonry', 'aqualuxe' ),
                'carousel' => __( 'Carousel', 'aqualuxe' ),
            ],
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_features_style', [
            'default'           => $this->settings['style'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_features_style', [
            'label'       => __( 'Style', 'aqualuxe' ),
            'description' => __( 'Select the visual style', 'aqualuxe' ),
            'section'     => 'aqualuxe_features_grid',
            'type'        => 'select',
            'choices'     => [
                'default' => __( 'Default', 'aqualuxe' ),
                'boxed'   => __( 'Boxed', 'aqualuxe' ),
                'bordered' => __( 'Bordered', 'aqualuxe' ),
                'minimal' => __( 'Minimal', 'aqualuxe' ),
                'icon-top' => __( 'Icon Top', 'aqualuxe' ),
                'icon-left' => __( 'Icon Left', 'aqualuxe' ),
            ],
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_features_animation', [
            'default'           => $this->settings['animation'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_features_animation', [
            'label'       => __( 'Animation', 'aqualuxe' ),
            'description' => __( 'Select the animation effect', 'aqualuxe' ),
            'section'     => 'aqualuxe_features_grid',
            'type'        => 'select',
            'choices'     => [
                'none'   => __( 'None', 'aqualuxe' ),
                'fade'   => __( 'Fade', 'aqualuxe' ),
                'slide'  => __( 'Slide', 'aqualuxe' ),
                'zoom'   => __( 'Zoom', 'aqualuxe' ),
                'flip'   => __( 'Flip', 'aqualuxe' ),
            ],
        ] );
        
        // Features Count
        $wp_customize->add_setting( 'aqualuxe_features_count', [
            'default'           => count( $this->settings['features'] ),
            'sanitize_callback' => 'absint',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_features_count', [
            'label'       => __( 'Number of Features', 'aqualuxe' ),
            'description' => __( 'Select the number of features to display', 'aqualuxe' ),
            'section'     => 'aqualuxe_features_grid',
            'type'        => 'number',
            'input_attrs' => [
                'min'  => 1,
                'max'  => 12,
                'step' => 1,
            ],
        ] );
        
        // Individual Feature Settings
        $feature_count = get_theme_mod( 'aqualuxe_features_count', count( $this->settings['features'] ) );
        
        for ( $i = 1; $i <= $feature_count; $i++ ) {
            $default_feature = isset( $this->settings['features'][$i-1] ) ? $this->settings['features'][$i-1] : [
                'icon'        => '',
                'title'       => '',
                'description' => '',
                'link'        => '',
                'link_text'   => __( 'Learn More', 'aqualuxe' ),
            ];
            
            // Feature Title
            $wp_customize->add_setting( "aqualuxe_feature_{$i}_title", [
                'default'           => $default_feature['title'],
                'sanitize_callback' => 'sanitize_text_field',
            ] );
            
            $wp_customize->add_control( "aqualuxe_feature_{$i}_title", [
                'label'       => sprintf( __( 'Feature %d Title', 'aqualuxe' ), $i ),
                'description' => __( 'Enter the feature title', 'aqualuxe' ),
                'section'     => 'aqualuxe_features_grid',
                'type'        => 'text',
            ] );
            
            // Feature Icon
            $wp_customize->add_setting( "aqualuxe_feature_{$i}_icon", [
                'default'           => $default_feature['icon'],
                'sanitize_callback' => 'sanitize_text_field',
            ] );
            
            $wp_customize->add_control( "aqualuxe_feature_{$i}_icon", [
                'label'       => sprintf( __( 'Feature %d Icon', 'aqualuxe' ), $i ),
                'description' => __( 'Enter the feature icon name (FontAwesome)', 'aqualuxe' ),
                'section'     => 'aqualuxe_features_grid',
                'type'        => 'text',
            ] );
            
            // Feature Description
            $wp_customize->add_setting( "aqualuxe_feature_{$i}_description", [
                'default'           => $default_feature['description'],
                'sanitize_callback' => 'wp_kses_post',
            ] );
            
            $wp_customize->add_control( "aqualuxe_feature_{$i}_description", [
                'label'       => sprintf( __( 'Feature %d Description', 'aqualuxe' ), $i ),
                'description' => __( 'Enter the feature description', 'aqualuxe' ),
                'section'     => 'aqualuxe_features_grid',
                'type'        => 'textarea',
            ] );
            
            // Feature Link
            $wp_customize->add_setting( "aqualuxe_feature_{$i}_link", [
                'default'           => $default_feature['link'],
                'sanitize_callback' => 'esc_url_raw',
            ] );
            
            $wp_customize->add_control( "aqualuxe_feature_{$i}_link", [
                'label'       => sprintf( __( 'Feature %d Link', 'aqualuxe' ), $i ),
                'description' => __( 'Enter the feature link URL', 'aqualuxe' ),
                'section'     => 'aqualuxe_features_grid',
                'type'        => 'url',
            ] );
            
            // Feature Link Text
            $wp_customize->add_setting( "aqualuxe_feature_{$i}_link_text", [
                'default'           => $default_feature['link_text'],
                'sanitize_callback' => 'sanitize_text_field',
            ] );
            
            $wp_customize->add_control( "aqualuxe_feature_{$i}_link_text", [
                'label'       => sprintf( __( 'Feature %d Link Text', 'aqualuxe' ), $i ),
                'description' => __( 'Enter the feature link text', 'aqualuxe' ),
                'section'     => 'aqualuxe_features_grid',
                'type'        => 'text',
            ] );
        }
    }
}

// Initialize the module
return new FeaturesGridModule();