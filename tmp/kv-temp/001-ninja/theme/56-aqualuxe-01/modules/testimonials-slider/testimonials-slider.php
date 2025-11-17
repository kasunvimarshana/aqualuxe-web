<?php
/**
 * Testimonials Slider Module
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Modules\TestimonialsSlider;

use AquaLuxe\Core\Module;
use AquaLuxe\Core\ModuleInterface;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Testimonials Slider Module class
 */
class TestimonialsSliderModule extends Module implements ModuleInterface {
    /**
     * Setup module properties
     *
     * @return void
     */
    protected function setup() {
        $this->id          = 'testimonials-slider';
        $this->name        = __( 'Testimonials Slider', 'aqualuxe' );
        $this->description = __( 'Display a slider of testimonials with customer quotes, ratings, and images.', 'aqualuxe' );
        $this->version     = '1.0.0';
        $this->dependencies = [];
        $this->settings     = [
            'title'           => __( 'What Our Customers Say', 'aqualuxe' ),
            'subtitle'        => __( 'Testimonials', 'aqualuxe' ),
            'description'     => __( 'Read what our satisfied customers have to say about our products and services.', 'aqualuxe' ),
            'layout'          => 'slider',
            'style'           => 'cards',
            'columns'         => 3,
            'show_rating'     => true,
            'show_image'      => true,
            'show_company'    => true,
            'autoplay'        => true,
            'autoplay_speed'  => 5000,
            'animation'       => 'fade',
            'testimonials'    => [
                [
                    'name'        => 'John Doe',
                    'position'    => 'CEO',
                    'company'     => 'Acme Inc.',
                    'image'       => '',
                    'content'     => 'AquaLuxe has completely transformed our website. The design is stunning, and the functionality is top-notch. Our customers love the new experience, and we\'ve seen a significant increase in conversions.',
                    'rating'      => 5,
                ],
                [
                    'name'        => 'Jane Smith',
                    'position'    => 'Marketing Director',
                    'company'     => 'XYZ Corp',
                    'image'       => '',
                    'content'     => 'Working with AquaLuxe was a game-changer for our business. The theme is incredibly flexible, and the support team was always there to help us customize it to our specific needs.',
                    'rating'      => 5,
                ],
                [
                    'name'        => 'Michael Johnson',
                    'position'    => 'E-commerce Manager',
                    'company'     => 'Global Shop',
                    'image'       => '',
                    'content'     => 'The WooCommerce integration in AquaLuxe is seamless. Setting up our online store was a breeze, and the checkout process is smooth and user-friendly. Our sales have increased by 30% since switching to this theme.',
                    'rating'      => 4,
                ],
                [
                    'name'        => 'Emily Wilson',
                    'position'    => 'Creative Director',
                    'company'     => 'Design Studio',
                    'image'       => '',
                    'content'     => 'As a designer, I appreciate the attention to detail in AquaLuxe. The typography, spacing, and color schemes are perfectly balanced. It\'s rare to find a theme that combines aesthetics and functionality so well.',
                    'rating'      => 5,
                ],
                [
                    'name'        => 'David Brown',
                    'position'    => 'Founder',
                    'company'     => 'Tech Startup',
                    'image'       => '',
                    'content'     => 'The modular approach of AquaLuxe allowed us to build our site exactly how we wanted it. The performance is outstanding, and our page load times have decreased significantly.',
                    'rating'      => 5,
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
        add_action( 'aqualuxe_testimonials_slider', [ $this, 'render' ] );
        add_shortcode( 'aqualuxe_testimonials_slider', [ $this, 'shortcode' ] );
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
                if ( $key === 'testimonials' ) {
                    $testimonials = [];
                    $testimonial_count = get_theme_mod( 'aqualuxe_testimonials_count', 5 );
                    
                    for ( $i = 1; $i <= $testimonial_count; $i++ ) {
                        $testimonial = [
                            'name'        => get_theme_mod( "aqualuxe_testimonial_{$i}_name", $default[$i-1]['name'] ?? '' ),
                            'position'    => get_theme_mod( "aqualuxe_testimonial_{$i}_position", $default[$i-1]['position'] ?? '' ),
                            'company'     => get_theme_mod( "aqualuxe_testimonial_{$i}_company", $default[$i-1]['company'] ?? '' ),
                            'image'       => get_theme_mod( "aqualuxe_testimonial_{$i}_image", $default[$i-1]['image'] ?? '' ),
                            'content'     => get_theme_mod( "aqualuxe_testimonial_{$i}_content", $default[$i-1]['content'] ?? '' ),
                            'rating'      => get_theme_mod( "aqualuxe_testimonial_{$i}_rating", $default[$i-1]['rating'] ?? 5 ),
                        ];
                        
                        $testimonials[] = $testimonial;
                    }
                    
                    $args['testimonials'] = $testimonials;
                } else {
                    $theme_mod = get_theme_mod( 'aqualuxe_testimonials_' . $key, $default );
                    $args[ $key ] = $theme_mod;
                }
            }
        }
        
        $this->get_template_part( 'testimonials-slider', $args );
    }

    /**
     * Shortcode handler
     *
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function shortcode( $atts ) {
        $atts = shortcode_atts( [
            'title'           => $this->settings['title'],
            'subtitle'        => $this->settings['subtitle'],
            'description'     => $this->settings['description'],
            'layout'          => $this->settings['layout'],
            'style'           => $this->settings['style'],
            'columns'         => $this->settings['columns'],
            'show_rating'     => $this->settings['show_rating'],
            'show_image'      => $this->settings['show_image'],
            'show_company'    => $this->settings['show_company'],
            'autoplay'        => $this->settings['autoplay'],
            'autoplay_speed'  => $this->settings['autoplay_speed'],
            'animation'       => $this->settings['animation'],
            'testimonial_ids' => '', // Comma-separated list of testimonial post IDs if using custom post type
        ], $atts, 'aqualuxe_testimonials_slider' );
        
        // Convert string boolean values to actual booleans
        $atts['show_rating'] = filter_var( $atts['show_rating'], FILTER_VALIDATE_BOOLEAN );
        $atts['show_image'] = filter_var( $atts['show_image'], FILTER_VALIDATE_BOOLEAN );
        $atts['show_company'] = filter_var( $atts['show_company'], FILTER_VALIDATE_BOOLEAN );
        $atts['autoplay'] = filter_var( $atts['autoplay'], FILTER_VALIDATE_BOOLEAN );
        
        // If testimonial_ids is provided, get testimonials from custom post type
        if ( ! empty( $atts['testimonial_ids'] ) ) {
            $testimonial_ids = explode( ',', $atts['testimonial_ids'] );
            $testimonials = [];
            
            foreach ( $testimonial_ids as $testimonial_id ) {
                $testimonial_id = trim( $testimonial_id );
                $testimonial = get_post( $testimonial_id );
                
                if ( $testimonial && $testimonial->post_type === 'testimonial' ) {
                    $testimonials[] = [
                        'name'        => get_the_title( $testimonial_id ),
                        'position'    => get_post_meta( $testimonial_id, '_testimonial_position', true ),
                        'company'     => get_post_meta( $testimonial_id, '_testimonial_company', true ),
                        'image'       => get_the_post_thumbnail_url( $testimonial_id, 'thumbnail' ),
                        'content'     => get_the_content( null, false, $testimonial_id ),
                        'rating'      => get_post_meta( $testimonial_id, '_testimonial_rating', true ) ?: 5,
                    ];
                }
            }
            
            $atts['testimonials'] = $testimonials;
        } else {
            $atts['testimonials'] = $this->settings['testimonials'];
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
        // Add Testimonials Slider section
        $wp_customize->add_section( 'aqualuxe_testimonials_slider', [
            'title'       => __( 'Testimonials Slider', 'aqualuxe' ),
            'description' => __( 'Customize the testimonials slider module', 'aqualuxe' ),
            'panel'       => 'aqualuxe_modules',
            'priority'    => 30,
        ] );
        
        // Content Settings
        $wp_customize->add_setting( 'aqualuxe_testimonials_title', [
            'default'           => $this->settings['title'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_testimonials_title', [
            'label'       => __( 'Title', 'aqualuxe' ),
            'description' => __( 'Enter the testimonials slider title', 'aqualuxe' ),
            'section'     => 'aqualuxe_testimonials_slider',
            'type'        => 'text',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_testimonials_subtitle', [
            'default'           => $this->settings['subtitle'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_testimonials_subtitle', [
            'label'       => __( 'Subtitle', 'aqualuxe' ),
            'description' => __( 'Enter the testimonials slider subtitle', 'aqualuxe' ),
            'section'     => 'aqualuxe_testimonials_slider',
            'type'        => 'text',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_testimonials_description', [
            'default'           => $this->settings['description'],
            'sanitize_callback' => 'wp_kses_post',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_testimonials_description', [
            'label'       => __( 'Description', 'aqualuxe' ),
            'description' => __( 'Enter the testimonials slider description', 'aqualuxe' ),
            'section'     => 'aqualuxe_testimonials_slider',
            'type'        => 'textarea',
        ] );
        
        // Layout Settings
        $wp_customize->add_setting( 'aqualuxe_testimonials_layout', [
            'default'           => $this->settings['layout'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_testimonials_layout', [
            'label'       => __( 'Layout', 'aqualuxe' ),
            'description' => __( 'Select the layout style', 'aqualuxe' ),
            'section'     => 'aqualuxe_testimonials_slider',
            'type'        => 'select',
            'choices'     => [
                'slider'  => __( 'Slider', 'aqualuxe' ),
                'grid'    => __( 'Grid', 'aqualuxe' ),
                'masonry' => __( 'Masonry', 'aqualuxe' ),
            ],
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_testimonials_style', [
            'default'           => $this->settings['style'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_testimonials_style', [
            'label'       => __( 'Style', 'aqualuxe' ),
            'description' => __( 'Select the visual style', 'aqualuxe' ),
            'section'     => 'aqualuxe_testimonials_slider',
            'type'        => 'select',
            'choices'     => [
                'cards'    => __( 'Cards', 'aqualuxe' ),
                'minimal'  => __( 'Minimal', 'aqualuxe' ),
                'bordered' => __( 'Bordered', 'aqualuxe' ),
                'quotes'   => __( 'Quotes', 'aqualuxe' ),
                'modern'   => __( 'Modern', 'aqualuxe' ),
            ],
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_testimonials_columns', [
            'default'           => $this->settings['columns'],
            'sanitize_callback' => 'absint',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_testimonials_columns', [
            'label'       => __( 'Columns', 'aqualuxe' ),
            'description' => __( 'Select the number of columns (for grid and masonry layouts)', 'aqualuxe' ),
            'section'     => 'aqualuxe_testimonials_slider',
            'type'        => 'select',
            'choices'     => [
                1 => __( '1 Column', 'aqualuxe' ),
                2 => __( '2 Columns', 'aqualuxe' ),
                3 => __( '3 Columns', 'aqualuxe' ),
                4 => __( '4 Columns', 'aqualuxe' ),
            ],
        ] );
        
        // Display Settings
        $wp_customize->add_setting( 'aqualuxe_testimonials_show_rating', [
            'default'           => $this->settings['show_rating'],
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_testimonials_show_rating', [
            'label'       => __( 'Show Rating', 'aqualuxe' ),
            'description' => __( 'Display star ratings for testimonials', 'aqualuxe' ),
            'section'     => 'aqualuxe_testimonials_slider',
            'type'        => 'checkbox',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_testimonials_show_image', [
            'default'           => $this->settings['show_image'],
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_testimonials_show_image', [
            'label'       => __( 'Show Image', 'aqualuxe' ),
            'description' => __( 'Display customer images', 'aqualuxe' ),
            'section'     => 'aqualuxe_testimonials_slider',
            'type'        => 'checkbox',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_testimonials_show_company', [
            'default'           => $this->settings['show_company'],
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_testimonials_show_company', [
            'label'       => __( 'Show Company', 'aqualuxe' ),
            'description' => __( 'Display company information', 'aqualuxe' ),
            'section'     => 'aqualuxe_testimonials_slider',
            'type'        => 'checkbox',
        ] );
        
        // Slider Settings
        $wp_customize->add_setting( 'aqualuxe_testimonials_autoplay', [
            'default'           => $this->settings['autoplay'],
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_testimonials_autoplay', [
            'label'       => __( 'Autoplay', 'aqualuxe' ),
            'description' => __( 'Automatically advance slides', 'aqualuxe' ),
            'section'     => 'aqualuxe_testimonials_slider',
            'type'        => 'checkbox',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_testimonials_autoplay_speed', [
            'default'           => $this->settings['autoplay_speed'],
            'sanitize_callback' => 'absint',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_testimonials_autoplay_speed', [
            'label'       => __( 'Autoplay Speed (ms)', 'aqualuxe' ),
            'description' => __( 'Set the autoplay speed in milliseconds', 'aqualuxe' ),
            'section'     => 'aqualuxe_testimonials_slider',
            'type'        => 'number',
            'input_attrs' => [
                'min'  => 1000,
                'max'  => 10000,
                'step' => 500,
            ],
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_testimonials_animation', [
            'default'           => $this->settings['animation'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_testimonials_animation', [
            'label'       => __( 'Animation', 'aqualuxe' ),
            'description' => __( 'Select the animation effect', 'aqualuxe' ),
            'section'     => 'aqualuxe_testimonials_slider',
            'type'        => 'select',
            'choices'     => [
                'fade'       => __( 'Fade', 'aqualuxe' ),
                'slide'      => __( 'Slide', 'aqualuxe' ),
                'coverflow'  => __( 'Coverflow', 'aqualuxe' ),
                'flip'       => __( 'Flip', 'aqualuxe' ),
                'cube'       => __( 'Cube', 'aqualuxe' ),
            ],
        ] );
        
        // Testimonials Count
        $wp_customize->add_setting( 'aqualuxe_testimonials_count', [
            'default'           => count( $this->settings['testimonials'] ),
            'sanitize_callback' => 'absint',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_testimonials_count', [
            'label'       => __( 'Number of Testimonials', 'aqualuxe' ),
            'description' => __( 'Select the number of testimonials to display', 'aqualuxe' ),
            'section'     => 'aqualuxe_testimonials_slider',
            'type'        => 'number',
            'input_attrs' => [
                'min'  => 1,
                'max'  => 10,
                'step' => 1,
            ],
        ] );
        
        // Individual Testimonial Settings
        $testimonial_count = get_theme_mod( 'aqualuxe_testimonials_count', count( $this->settings['testimonials'] ) );
        
        for ( $i = 1; $i <= $testimonial_count; $i++ ) {
            $default_testimonial = isset( $this->settings['testimonials'][$i-1] ) ? $this->settings['testimonials'][$i-1] : [
                'name'        => '',
                'position'    => '',
                'company'     => '',
                'image'       => '',
                'content'     => '',
                'rating'      => 5,
            ];
            
            // Testimonial Name
            $wp_customize->add_setting( "aqualuxe_testimonial_{$i}_name", [
                'default'           => $default_testimonial['name'],
                'sanitize_callback' => 'sanitize_text_field',
            ] );
            
            $wp_customize->add_control( "aqualuxe_testimonial_{$i}_name", [
                'label'       => sprintf( __( 'Testimonial %d Name', 'aqualuxe' ), $i ),
                'description' => __( 'Enter the customer name', 'aqualuxe' ),
                'section'     => 'aqualuxe_testimonials_slider',
                'type'        => 'text',
            ] );
            
            // Testimonial Position
            $wp_customize->add_setting( "aqualuxe_testimonial_{$i}_position", [
                'default'           => $default_testimonial['position'],
                'sanitize_callback' => 'sanitize_text_field',
            ] );
            
            $wp_customize->add_control( "aqualuxe_testimonial_{$i}_position", [
                'label'       => sprintf( __( 'Testimonial %d Position', 'aqualuxe' ), $i ),
                'description' => __( 'Enter the customer position', 'aqualuxe' ),
                'section'     => 'aqualuxe_testimonials_slider',
                'type'        => 'text',
            ] );
            
            // Testimonial Company
            $wp_customize->add_setting( "aqualuxe_testimonial_{$i}_company", [
                'default'           => $default_testimonial['company'],
                'sanitize_callback' => 'sanitize_text_field',
            ] );
            
            $wp_customize->add_control( "aqualuxe_testimonial_{$i}_company", [
                'label'       => sprintf( __( 'Testimonial %d Company', 'aqualuxe' ), $i ),
                'description' => __( 'Enter the customer company', 'aqualuxe' ),
                'section'     => 'aqualuxe_testimonials_slider',
                'type'        => 'text',
            ] );
            
            // Testimonial Image
            $wp_customize->add_setting( "aqualuxe_testimonial_{$i}_image", [
                'default'           => $default_testimonial['image'],
                'sanitize_callback' => 'esc_url_raw',
            ] );
            
            $wp_customize->add_control( new \WP_Customize_Image_Control(
                $wp_customize,
                "aqualuxe_testimonial_{$i}_image",
                [
                    'label'       => sprintf( __( 'Testimonial %d Image', 'aqualuxe' ), $i ),
                    'description' => __( 'Select the customer image', 'aqualuxe' ),
                    'section'     => 'aqualuxe_testimonials_slider',
                ]
            ) );
            
            // Testimonial Content
            $wp_customize->add_setting( "aqualuxe_testimonial_{$i}_content", [
                'default'           => $default_testimonial['content'],
                'sanitize_callback' => 'wp_kses_post',
            ] );
            
            $wp_customize->add_control( "aqualuxe_testimonial_{$i}_content", [
                'label'       => sprintf( __( 'Testimonial %d Content', 'aqualuxe' ), $i ),
                'description' => __( 'Enter the testimonial content', 'aqualuxe' ),
                'section'     => 'aqualuxe_testimonials_slider',
                'type'        => 'textarea',
            ] );
            
            // Testimonial Rating
            $wp_customize->add_setting( "aqualuxe_testimonial_{$i}_rating", [
                'default'           => $default_testimonial['rating'],
                'sanitize_callback' => 'absint',
            ] );
            
            $wp_customize->add_control( "aqualuxe_testimonial_{$i}_rating", [
                'label'       => sprintf( __( 'Testimonial %d Rating', 'aqualuxe' ), $i ),
                'description' => __( 'Select the rating (1-5 stars)', 'aqualuxe' ),
                'section'     => 'aqualuxe_testimonials_slider',
                'type'        => 'range',
                'input_attrs' => [
                    'min'  => 1,
                    'max'  => 5,
                    'step' => 1,
                ],
            ] );
        }
    }
}

// Initialize the module
return new TestimonialsSliderModule();