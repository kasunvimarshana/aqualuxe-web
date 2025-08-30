<?php
/**
 * Team Members Module
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Modules\TeamMembers;

use AquaLuxe\Core\Module;
use AquaLuxe\Core\ModuleInterface;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Team Members Module class
 */
class TeamMembersModule extends Module implements ModuleInterface {
    /**
     * Setup module properties
     *
     * @return void
     */
    protected function setup() {
        $this->id          = 'team-members';
        $this->name        = __( 'Team Members', 'aqualuxe' );
        $this->description = __( 'Display team members with images, titles, descriptions, and social links.', 'aqualuxe' );
        $this->version     = '1.0.0';
        $this->dependencies = [];
        $this->settings     = [
            'title'           => __( 'Our Team', 'aqualuxe' ),
            'subtitle'        => __( 'Meet Our Experts', 'aqualuxe' ),
            'description'     => __( 'Our talented team of professionals is dedicated to providing the best service and expertise in the industry.', 'aqualuxe' ),
            'layout'          => 'grid',
            'style'           => 'cards',
            'columns'         => 4,
            'show_social'     => true,
            'show_bio'        => true,
            'animation'       => 'fade',
            'members'         => [
                [
                    'name'        => 'John Doe',
                    'position'    => 'CEO & Founder',
                    'image'       => '',
                    'bio'         => 'John has over 15 years of experience in the industry and leads our company with a focus on innovation and customer satisfaction.',
                    'social'      => [
                        'linkedin'   => 'https://linkedin.com/',
                        'twitter'    => 'https://twitter.com/',
                        'facebook'   => '',
                        'instagram'  => '',
                    ],
                ],
                [
                    'name'        => 'Jane Smith',
                    'position'    => 'Creative Director',
                    'image'       => '',
                    'bio'         => 'With a background in design and marketing, Jane brings creative vision and strategic thinking to every project.',
                    'social'      => [
                        'linkedin'   => 'https://linkedin.com/',
                        'twitter'    => 'https://twitter.com/',
                        'facebook'   => 'https://facebook.com/',
                        'instagram'  => 'https://instagram.com/',
                    ],
                ],
                [
                    'name'        => 'Michael Johnson',
                    'position'    => 'Lead Developer',
                    'image'       => '',
                    'bio'         => 'Michael is an expert in web development with a passion for creating elegant, efficient, and user-friendly solutions.',
                    'social'      => [
                        'linkedin'   => 'https://linkedin.com/',
                        'twitter'    => '',
                        'facebook'   => '',
                        'instagram'  => 'https://instagram.com/',
                    ],
                ],
                [
                    'name'        => 'Emily Wilson',
                    'position'    => 'Marketing Manager',
                    'image'       => '',
                    'bio'         => 'Emily specializes in digital marketing strategies that drive growth and engagement for our clients.',
                    'social'      => [
                        'linkedin'   => 'https://linkedin.com/',
                        'twitter'    => 'https://twitter.com/',
                        'facebook'   => 'https://facebook.com/',
                        'instagram'  => '',
                    ],
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
        add_action( 'aqualuxe_team_members', [ $this, 'render' ] );
        add_shortcode( 'aqualuxe_team_members', [ $this, 'shortcode' ] );
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
                if ( $key === 'members' ) {
                    $members = [];
                    $member_count = get_theme_mod( 'aqualuxe_team_members_count', 4 );
                    
                    for ( $i = 1; $i <= $member_count; $i++ ) {
                        $member = [
                            'name'        => get_theme_mod( "aqualuxe_team_member_{$i}_name", $default[$i-1]['name'] ?? '' ),
                            'position'    => get_theme_mod( "aqualuxe_team_member_{$i}_position", $default[$i-1]['position'] ?? '' ),
                            'image'       => get_theme_mod( "aqualuxe_team_member_{$i}_image", $default[$i-1]['image'] ?? '' ),
                            'bio'         => get_theme_mod( "aqualuxe_team_member_{$i}_bio", $default[$i-1]['bio'] ?? '' ),
                            'social'      => [
                                'linkedin'   => get_theme_mod( "aqualuxe_team_member_{$i}_linkedin", $default[$i-1]['social']['linkedin'] ?? '' ),
                                'twitter'    => get_theme_mod( "aqualuxe_team_member_{$i}_twitter", $default[$i-1]['social']['twitter'] ?? '' ),
                                'facebook'   => get_theme_mod( "aqualuxe_team_member_{$i}_facebook", $default[$i-1]['social']['facebook'] ?? '' ),
                                'instagram'  => get_theme_mod( "aqualuxe_team_member_{$i}_instagram", $default[$i-1]['social']['instagram'] ?? '' ),
                            ],
                        ];
                        
                        $members[] = $member;
                    }
                    
                    $args['members'] = $members;
                } else {
                    $theme_mod = get_theme_mod( 'aqualuxe_team_' . $key, $default );
                    $args[ $key ] = $theme_mod;
                }
            }
        }
        
        $this->get_template_part( 'team-members', $args );
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
            'layout'      => $this->settings['layout'],
            'style'       => $this->settings['style'],
            'columns'     => $this->settings['columns'],
            'show_social' => $this->settings['show_social'],
            'show_bio'    => $this->settings['show_bio'],
            'animation'   => $this->settings['animation'],
            'member_ids'  => '', // Comma-separated list of team member post IDs if using custom post type
        ], $atts, 'aqualuxe_team_members' );
        
        // Convert string boolean values to actual booleans
        $atts['show_social'] = filter_var( $atts['show_social'], FILTER_VALIDATE_BOOLEAN );
        $atts['show_bio'] = filter_var( $atts['show_bio'], FILTER_VALIDATE_BOOLEAN );
        
        // If member_ids is provided, get members from custom post type
        if ( ! empty( $atts['member_ids'] ) ) {
            $member_ids = explode( ',', $atts['member_ids'] );
            $members = [];
            
            foreach ( $member_ids as $member_id ) {
                $member_id = trim( $member_id );
                $member = get_post( $member_id );
                
                if ( $member && $member->post_type === 'team_member' ) {
                    $members[] = [
                        'name'        => get_the_title( $member_id ),
                        'position'    => get_post_meta( $member_id, '_team_member_position', true ),
                        'image'       => get_the_post_thumbnail_url( $member_id, 'medium' ),
                        'bio'         => get_the_excerpt( $member_id ),
                        'social'      => [
                            'linkedin'   => get_post_meta( $member_id, '_team_member_linkedin', true ),
                            'twitter'    => get_post_meta( $member_id, '_team_member_twitter', true ),
                            'facebook'   => get_post_meta( $member_id, '_team_member_facebook', true ),
                            'instagram'  => get_post_meta( $member_id, '_team_member_instagram', true ),
                        ],
                    ];
                }
            }
            
            $atts['members'] = $members;
        } else {
            $atts['members'] = $this->settings['members'];
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
        // Add Team Members section
        $wp_customize->add_section( 'aqualuxe_team_members', [
            'title'       => __( 'Team Members', 'aqualuxe' ),
            'description' => __( 'Customize the team members module', 'aqualuxe' ),
            'panel'       => 'aqualuxe_modules',
            'priority'    => 50,
        ] );
        
        // Content Settings
        $wp_customize->add_setting( 'aqualuxe_team_title', [
            'default'           => $this->settings['title'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_team_title', [
            'label'       => __( 'Title', 'aqualuxe' ),
            'description' => __( 'Enter the team members section title', 'aqualuxe' ),
            'section'     => 'aqualuxe_team_members',
            'type'        => 'text',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_team_subtitle', [
            'default'           => $this->settings['subtitle'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_team_subtitle', [
            'label'       => __( 'Subtitle', 'aqualuxe' ),
            'description' => __( 'Enter the team members section subtitle', 'aqualuxe' ),
            'section'     => 'aqualuxe_team_members',
            'type'        => 'text',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_team_description', [
            'default'           => $this->settings['description'],
            'sanitize_callback' => 'wp_kses_post',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_team_description', [
            'label'       => __( 'Description', 'aqualuxe' ),
            'description' => __( 'Enter the team members section description', 'aqualuxe' ),
            'section'     => 'aqualuxe_team_members',
            'type'        => 'textarea',
        ] );
        
        // Layout Settings
        $wp_customize->add_setting( 'aqualuxe_team_layout', [
            'default'           => $this->settings['layout'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_team_layout', [
            'label'       => __( 'Layout', 'aqualuxe' ),
            'description' => __( 'Select the layout style', 'aqualuxe' ),
            'section'     => 'aqualuxe_team_members',
            'type'        => 'select',
            'choices'     => [
                'grid'    => __( 'Grid', 'aqualuxe' ),
                'list'    => __( 'List', 'aqualuxe' ),
                'carousel' => __( 'Carousel', 'aqualuxe' ),
            ],
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_team_style', [
            'default'           => $this->settings['style'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_team_style', [
            'label'       => __( 'Style', 'aqualuxe' ),
            'description' => __( 'Select the visual style', 'aqualuxe' ),
            'section'     => 'aqualuxe_team_members',
            'type'        => 'select',
            'choices'     => [
                'cards'    => __( 'Cards', 'aqualuxe' ),
                'minimal'  => __( 'Minimal', 'aqualuxe' ),
                'bordered' => __( 'Bordered', 'aqualuxe' ),
                'overlay'  => __( 'Overlay', 'aqualuxe' ),
                'circle'   => __( 'Circle', 'aqualuxe' ),
            ],
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_team_columns', [
            'default'           => $this->settings['columns'],
            'sanitize_callback' => 'absint',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_team_columns', [
            'label'       => __( 'Columns', 'aqualuxe' ),
            'description' => __( 'Select the number of columns', 'aqualuxe' ),
            'section'     => 'aqualuxe_team_members',
            'type'        => 'select',
            'choices'     => [
                1 => __( '1 Column', 'aqualuxe' ),
                2 => __( '2 Columns', 'aqualuxe' ),
                3 => __( '3 Columns', 'aqualuxe' ),
                4 => __( '4 Columns', 'aqualuxe' ),
            ],
        ] );
        
        // Display Settings
        $wp_customize->add_setting( 'aqualuxe_team_show_social', [
            'default'           => $this->settings['show_social'],
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_team_show_social', [
            'label'       => __( 'Show Social Links', 'aqualuxe' ),
            'description' => __( 'Display social media links for team members', 'aqualuxe' ),
            'section'     => 'aqualuxe_team_members',
            'type'        => 'checkbox',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_team_show_bio', [
            'default'           => $this->settings['show_bio'],
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_team_show_bio', [
            'label'       => __( 'Show Bio', 'aqualuxe' ),
            'description' => __( 'Display biography for team members', 'aqualuxe' ),
            'section'     => 'aqualuxe_team_members',
            'type'        => 'checkbox',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_team_animation', [
            'default'           => $this->settings['animation'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_team_animation', [
            'label'       => __( 'Animation', 'aqualuxe' ),
            'description' => __( 'Select the animation effect', 'aqualuxe' ),
            'section'     => 'aqualuxe_team_members',
            'type'        => 'select',
            'choices'     => [
                'none'   => __( 'None', 'aqualuxe' ),
                'fade'   => __( 'Fade', 'aqualuxe' ),
                'slide'  => __( 'Slide', 'aqualuxe' ),
                'zoom'   => __( 'Zoom', 'aqualuxe' ),
                'flip'   => __( 'Flip', 'aqualuxe' ),
            ],
        ] );
        
        // Team Members Count
        $wp_customize->add_setting( 'aqualuxe_team_members_count', [
            'default'           => count( $this->settings['members'] ),
            'sanitize_callback' => 'absint',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_team_members_count', [
            'label'       => __( 'Number of Team Members', 'aqualuxe' ),
            'description' => __( 'Select the number of team members to display', 'aqualuxe' ),
            'section'     => 'aqualuxe_team_members',
            'type'        => 'number',
            'input_attrs' => [
                'min'  => 1,
                'max'  => 12,
                'step' => 1,
            ],
        ] );
        
        // Individual Team Member Settings
        $member_count = get_theme_mod( 'aqualuxe_team_members_count', count( $this->settings['members'] ) );
        
        for ( $i = 1; $i <= $member_count; $i++ ) {
            $default_member = isset( $this->settings['members'][$i-1] ) ? $this->settings['members'][$i-1] : [
                'name'        => '',
                'position'    => '',
                'image'       => '',
                'bio'         => '',
                'social'      => [
                    'linkedin'   => '',
                    'twitter'    => '',
                    'facebook'   => '',
                    'instagram'  => '',
                ],
            ];
            
            // Member Name
            $wp_customize->add_setting( "aqualuxe_team_member_{$i}_name", [
                'default'           => $default_member['name'],
                'sanitize_callback' => 'sanitize_text_field',
            ] );
            
            $wp_customize->add_control( "aqualuxe_team_member_{$i}_name", [
                'label'       => sprintf( __( 'Member %d Name', 'aqualuxe' ), $i ),
                'description' => __( 'Enter the team member name', 'aqualuxe' ),
                'section'     => 'aqualuxe_team_members',
                'type'        => 'text',
            ] );
            
            // Member Position
            $wp_customize->add_setting( "aqualuxe_team_member_{$i}_position", [
                'default'           => $default_member['position'],
                'sanitize_callback' => 'sanitize_text_field',
            ] );
            
            $wp_customize->add_control( "aqualuxe_team_member_{$i}_position", [
                'label'       => sprintf( __( 'Member %d Position', 'aqualuxe' ), $i ),
                'description' => __( 'Enter the team member position', 'aqualuxe' ),
                'section'     => 'aqualuxe_team_members',
                'type'        => 'text',
            ] );
            
            // Member Image
            $wp_customize->add_setting( "aqualuxe_team_member_{$i}_image", [
                'default'           => $default_member['image'],
                'sanitize_callback' => 'esc_url_raw',
            ] );
            
            $wp_customize->add_control( new \WP_Customize_Image_Control(
                $wp_customize,
                "aqualuxe_team_member_{$i}_image",
                [
                    'label'       => sprintf( __( 'Member %d Image', 'aqualuxe' ), $i ),
                    'description' => __( 'Select the team member image', 'aqualuxe' ),
                    'section'     => 'aqualuxe_team_members',
                ]
            ) );
            
            // Member Bio
            $wp_customize->add_setting( "aqualuxe_team_member_{$i}_bio", [
                'default'           => $default_member['bio'],
                'sanitize_callback' => 'wp_kses_post',
            ] );
            
            $wp_customize->add_control( "aqualuxe_team_member_{$i}_bio", [
                'label'       => sprintf( __( 'Member %d Bio', 'aqualuxe' ), $i ),
                'description' => __( 'Enter the team member biography', 'aqualuxe' ),
                'section'     => 'aqualuxe_team_members',
                'type'        => 'textarea',
            ] );
            
            // Member LinkedIn
            $wp_customize->add_setting( "aqualuxe_team_member_{$i}_linkedin", [
                'default'           => $default_member['social']['linkedin'],
                'sanitize_callback' => 'esc_url_raw',
            ] );
            
            $wp_customize->add_control( "aqualuxe_team_member_{$i}_linkedin", [
                'label'       => sprintf( __( 'Member %d LinkedIn', 'aqualuxe' ), $i ),
                'description' => __( 'Enter the LinkedIn profile URL', 'aqualuxe' ),
                'section'     => 'aqualuxe_team_members',
                'type'        => 'url',
            ] );
            
            // Member Twitter
            $wp_customize->add_setting( "aqualuxe_team_member_{$i}_twitter", [
                'default'           => $default_member['social']['twitter'],
                'sanitize_callback' => 'esc_url_raw',
            ] );
            
            $wp_customize->add_control( "aqualuxe_team_member_{$i}_twitter", [
                'label'       => sprintf( __( 'Member %d Twitter', 'aqualuxe' ), $i ),
                'description' => __( 'Enter the Twitter profile URL', 'aqualuxe' ),
                'section'     => 'aqualuxe_team_members',
                'type'        => 'url',
            ] );
            
            // Member Facebook
            $wp_customize->add_setting( "aqualuxe_team_member_{$i}_facebook", [
                'default'           => $default_member['social']['facebook'],
                'sanitize_callback' => 'esc_url_raw',
            ] );
            
            $wp_customize->add_control( "aqualuxe_team_member_{$i}_facebook", [
                'label'       => sprintf( __( 'Member %d Facebook', 'aqualuxe' ), $i ),
                'description' => __( 'Enter the Facebook profile URL', 'aqualuxe' ),
                'section'     => 'aqualuxe_team_members',
                'type'        => 'url',
            ] );
            
            // Member Instagram
            $wp_customize->add_setting( "aqualuxe_team_member_{$i}_instagram", [
                'default'           => $default_member['social']['instagram'],
                'sanitize_callback' => 'esc_url_raw',
            ] );
            
            $wp_customize->add_control( "aqualuxe_team_member_{$i}_instagram", [
                'label'       => sprintf( __( 'Member %d Instagram', 'aqualuxe' ), $i ),
                'description' => __( 'Enter the Instagram profile URL', 'aqualuxe' ),
                'section'     => 'aqualuxe_team_members',
                'type'        => 'url',
            ] );
        }
    }
}

// Initialize the module
return new TeamMembersModule();